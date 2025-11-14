<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use App\Events\SendMailEvent;
use DB;
use PDF;
use App\Models\PartPaymentHistory;   
use App\Models\ExpenseHistory;
use App\Models\Expense;
use App\Models\ExtraService;
use App\Models\VendorCategory;
use App\Models\VendorService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Http;

use App\Models\Customer;
use App\Models\Setting;
use App\Models\TripBooking;
use App\Models\Trip;
use App\Models\Vendor;
use App\Models\Agent;
use App\Models\Inventory;
use App\Models\User;
use App\Models\LoyalityPts;

class CronController extends Controller
{
    public function customerTier(){
        $customers = Customer::where('parent', 0)->get();
        foreach($customers as $customer){
            $tripCountOfCustomer = getTripCountbyCustomerId($customer->id);
            $tripCostOfCustomer = totalTripCostOfCustomerById($customer->id);
            if($tripCountOfCustomer > 9){
                $tier = "Legends";
            }elseif($tripCostOfCustomer > 2500000){
                $tier = "Explorer";
            }elseif($tripCostOfCustomer > 1000000 && $tripCostOfCustomer <= 2500000){
                $tier = "Adventurer";
            }elseif($tripCostOfCustomer >= 0 && $tripCostOfCustomer <= 1000000){
                $tier = "Discovery";
            }else{
                $tier = "Discovery";
            }

            $data = Customer::find($customer->id);
            $data->tier = $tier;
            $data->save();

            info($customer->id ." ==========> ".$tier);
        }
    }

    public function ongoingTripReport(){

        if(setting('mail_status') == 1){
            $fileUrl = route('ongoing_trip');
            $response = Http::get($fileUrl);
            

            if ($response->ok()) {

                // Save the file temporarily
                $tempFilePath = storage_path('app/public/Upcoming_Trip_Revenue_Report.xlsx');

                // Ensure the directory exists
                if (!file_exists(dirname($tempFilePath))) {
                    mkdir(dirname($tempFilePath), 0755, true); // Create the directory if it doesn't exist
                }
                file_put_contents($tempFilePath, $response->body());
                $emails = setting('trip_ongoing_report');
                $emails = explode(',', $emails) ?? [];


               

                $dataMail = [
                    'attachment' => $tempFilePath,
                ];
                foreach($emails as $cEmail){
                    $cEmail = trim($cEmail);
                    @event(new SendMailEvent("$cEmail", 'Weekly Upcoming Trip Revenue Report', 'emails.weekly-ongoing-trip-report', $dataMail));
                }

                // Clean up the temporary file after the email is sent
                register_shutdown_function(function () use ($tempFilePath) {
                    if (file_exists($tempFilePath)) {
                        unlink($tempFilePath);
                    }
                });

              
            }
        }
    }
    
    
    public function sendBirthdayEmail(){

            // if(setting('mail_status') == 0){
            $today = now()->format('m-d');
            $customers = Customer::whereRaw("DATE_FORMAT(dob, '%m-%d') = ?", [$today])->get();

            foreach ($customers as $customer) {

                $name = $customer->first_name . ' ' . $customer->last_name;
                $email = $customer->email;
                $phone = $customer->phone;

                $data = array(
                    'name' => $name,
                    'email' => $customer->email,
                    'for' => 'birthday_mail'
                );

                $pdfFile = "birthday-certificate-" . $name . ".pdf";
                $pdf = PDF::setPaper('A4', 'landscape')->loadView('pdf.birthday-certificate', $data);

                $tempFilePathPdf = storage_path('pdf/' . $pdfFile);
                if (!file_exists(dirname($tempFilePathPdf))) {
                    mkdir(dirname($tempFilePathPdf), 0755, true);
                }

                $pdf->save($tempFilePathPdf);
                $pdfUrl = storage_path('pdf/' . $pdfFile);
                $data['attachment'] = [$pdfUrl];
                
                // $bccEmail = Setting::whereNotNull('birthday_email')->first();
                // $bccEmail=$bccEmail->birthday_email;
                
                Log::info("pdf url  - ".$pdfUrl);

                if (empty($email)) {
                    Log::warning("No email found for customer ID: {$customer->id}, birthday email not sent.");
                } else {
                    try {
                        event(new SendMailEvent($email, '🌳 Happy Birthday! A Tree Planted in Your Honor 🌟', 'emails.birthday-email', $data));
                         $customer->birthday_email_sent = 1;
                         $customer->save();
                        Log::info("Birthday email sent to $email - " . json_encode($data));
                    } catch (\Exception $e) {
                        Log::error("Failed to send birthday email to $email: " . $e->getMessage());
                    }
                }

                $body = [
                    "broadcast_name" => "bday_message",
                    "parameters" => [
                        [
                            "name" => "name",
                            "value" => $name,
                        ],
                        [
                            "name" => "pdf_file",
                            "value" => rawurlencode($pdfFile)
                        ]
                    ],
                    "template_name" => "bday_message"
                ];
                Log::info("Whatsapp url  - ". 'https://live-server-8452.wati.io/api/v1/sendTemplateMessage?whatsappNumber='.$phone);
                Log::info("Whatsapp body  - ". json_encode($body));
                
               if (!empty($phone)) {
                    try 
                    {
                        $client = new Client();
                        $response = $client->request('POST', 'https://live-server-8452.wati.io/api/v1/sendTemplateMessage?whatsappNumber=' . $phone, [
                            'body' => json_encode($body),
                            'headers' => [
                                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJlMDhmNjczYy01YWM5LTQ4OWItYjM2OC0yMzAwMjcxMGJlNWIiLCJ1bmlxdWVfbmFtZSI6ImluZm9AYWR2ZW50dXJlc292ZXJsYW5kLmNvbSIsIm5hbWVpZCI6ImluZm9AYWR2ZW50dXJlc292ZXJsYW5kLmNvbSIsImVtYWlsIjoiaW5mb0BhZHZlbnR1cmVzb3ZlcmxhbmQuY29tIiwiYXV0aF90aW1lIjoiMTEvMjUvMjAyNCAwOTo1NToyNSIsInRlbmFudF9pZCI6Ijg0NTIiLCJkYl9uYW1lIjoibXQtcHJvZC1UZW5hbnRzIiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9yb2xlIjoiQURNSU5JU1RSQVRPUiIsImV4cCI6MjUzNDAyMzAwODAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.zqxefTmcTMfTb4D0L6CO0FzZpKqN-mjd9EpMhjfWBxY',
                                'content-type' => 'application/json',
                            ],
                            'http_errors' => false, // Important: disables Guzzle exceptions on HTTP error status
                        ]);
                        $statusCode = $response->getStatusCode();
                        $responseBody = json_decode($response->getBody()->getContents(), true);

                        if ($statusCode >= 200 && $statusCode < 300) {
                            $customer->whats_app_sent = 1;
                            $customer->save();
                            Log::info("Whatsapp message sent to $phone - " . json_encode($responseBody));
                        } 
                        else 
                        {
                            Log::error("WhatsApp API error for $phone: HTTP $statusCode - " . json_encode($responseBody));
                        }
                    } catch (\Exception $e) {
                            Log::error("Failed to send WhatsApp message to $phone: " . $e->getMessage());
                        }
                    } 
                    else {
                        Log::warning("No phone number for customer ID: {$customer->id}, WhatsApp message not sent.");
                }
            }
    }
    
    public function sendReminderToUnsubmittedForms()
    {
        $processedCustomers = []; 
        $bookings = TripBooking::where('is_form_submitted', 0)->whereNotIn('trip_status', ['draft', 'Cancelled','Completed'])->get();
        foreach ($bookings as $booking) {
            $customers = json_decode($booking->customer_id);
             
            if (Carbon::parse($booking->trip->end_date)->gt(Carbon::today())) {
                foreach ($customers as $travelerId) 
                {
                    $customer = getCustomerById($travelerId);
                    if (in_array($travelerId, $processedCustomers)) {
                        Log::info("Skipping already processed customer ID: $travelerId for booking ID: " . $booking->id);
                        continue;
                    } else {
                        $processedCustomers[] = $travelerId;
                    }
                  

                    if (!$customer) {
                        Log::warning("Customer ID: $travelerId does not exist. Skipping.");
                        continue;
                    }
            
                    if (empty($customer->email)) {
                        Log::warning("Skipping customer ID: $travelerId due to missing email.");
                        continue;
                    }
            
                    $cust = Customer::where('id', $travelerId)->first();

                    if($booking->trip->status != 'Completed' || $booking->trip_status != 'Cancelled') {
                    
                        if ($cust && $cust->is_form_submitted == 0) {
            
                            if (!$booking->trip) {
                                Log::warning("Trip is missing for booking ID: " . ($booking->id ?? 'N/A'));
                                continue;
                            }
            
                            $url = env('USER_URL') . 'registration?token=' . $booking->token . '&email=' . $customer->email . '&trip_id=' . $booking->trip_id;
                
                            $data = [
                                'name' => $customer->name,
                                'email' => $customer->email,
                                'paid_amt' => $booking->payment_amt ?? 0,
                                'slot' => count($customers),
                                'trip' => $booking->trip->name,
                                'link' => $url,
                                'booking_id' => $booking->id,
                                'for' => 'customer',
                            ];
            
                            try {
                                if (setting('mail_status') == 1) {
                                    event(new SendMailEvent($customer->email, 'Action Required: Registration Form Pending', 'emails.reminder', $data));
                                    Log::info("Reminder mail sent to " . $customer->email . " for booking ID " . $booking->id);
                                }
                            } catch (\Exception $e) {
                                Log::error("Error find while sending email to {$customer->email}: " . $e->getMessage());
                            }
                        }
                        else {
                            Log::warning("Customer ID: $travelerId has already submitted the form or does not exist.");
                        }
                    }
                    else {
                        Log::info("Booking ID: " . $booking->id . " is already completed or cancelled. Skipping reminder email.");
                    }
                
                }
            }
        }
        Log::warning("Reminder mail sent to all customers who have not submitted the form.");
    
        return "Reminder Emails Sent!";
    }



     public function sendTodayExpenseReport()
    {
        try {
            $today = Carbon::today();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Today Expense');
            $sheet->getStyle('A1:O1')->getFont()->setBold(true);

            $headers = [
                'Created Date', 'Trip Name', 'Vendor', 'Category', 'Service',
                'Amount Due', 'Amount Paid', 'Pending Amount', 'Document', 'Comment',
                'Payment Date', 'Vendor Company Name', 'Service Amount', 'Payment Mode', 'Added By',
            ];

            $data = [];
            $data[] = $headers;

            $expenses = Expense::with(['trip', 'vendor', 'service', 'vendorService'])
                ->whereDate('created_at', $today)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($expenses->isEmpty()) {
                Log::info("No expenses found for today: " . $today->format('Y-m-d'));
                return;
            }

            $histories = [];
            foreach ($expenses as $expense) {
                try {
                    $histories[$expense->id] = ExpenseHistory::where('expense_id', $expense->id)->get();
                    $expenseHistories = $histories[$expense->id] ?? collect();
                    $history = $expenseHistories->first();

                     $documentLink = $expense->docx
                        ? '=HYPERLINK("' . asset('storage/app/' . $expense->docx) . '", "View")'
                        : '-';

                    $data[] = [
                        Carbon::parse($expense->created_at)->format('d M Y'),
                        $expense->trip->name ?? '-',
                        $expense->vendor->first_name ?? '-',
                        $expense->service->title ?? '-',
                        $expense->vendorService->title ?? '-',
                        $expense->total_amount ?? 0,
                        $expense->paid_amount ?? 0,
                        ($expense->total_amount ?? 0) - ($expense->paid_amount ?? 0),
                        $documentLink,
                        $expense->comment ?? '-',
                        $history && $history->date ? Carbon::parse($history->date)->format('d M Y') : '-',
                        $expense->vendor->company ?? '-',
                        $history->amount ?? '-',
                        $history->payment_mode ?? '-',
                        $history && $history->admin ? $history->admin->name : '-'
                    ];
                } catch (\Throwable $e) {
                    Log::error("Error processing expense ID {$expense->id}: " . $e->getMessage());
                }
            }

            $sheet->fromArray($data, null, 'A1');
            foreach (range('A', 'O') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Save file
            $directory = storage_path('app/admin/daily-add-expense-reports');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $fileName = 'today_expense_report_' . $today->format('Y-m-d') . '.xlsx';
            $filePath = $directory . '/' . $fileName;

            try {
                $writer = new Xlsx($spreadsheet);
                $writer->save($filePath);
            } catch (\Throwable $e) {
                Log::error("Error saving spreadsheet file: " . $e->getMessage());
                return;
            }

            $accountEmail = setting('account_mail');
            $extraEmail   = "tarun@adventuresoverland.com";
            $operationmail= setting('operation_mail');
            $extraOperationMail = config('app.ExtraMail');

            $emails = array_unique(
                    array_merge(
                        array_map('trim', explode(',', $accountEmail)),     
                        [$extraEmail],                                       
                        array_map('trim', explode(',', $operationmail)),    
                        [$extraOperationMail]                                
                    )
                );

            $dataMail = [
                'attachment' => $filePath,
                'today' => $today->format('d M Y'),
            ];

            foreach ($emails as $cEmail) {
             
                $cEmail = trim($cEmail);
                if (!empty($cEmail)) {
                    try {
                        if (setting('mail_status') == 1) {
                            @event(new SendMailEvent(
                                $cEmail,
                                "Today's Expense Report - " . $today->format('d M Y'),
                                'emails.today-expense-report',
                                $dataMail
                            ));
                            Log::info("Today's Expense Report email sent to $cEmail for " . $today->format('Y-m-d'));
                        }
                        else {
                            Log::info("Mail status is disabled, not sending email to $cEmail for " . $today->format('Y-m-d'));
                        }
                    } catch (\Throwable $e) {
                        Log::error("Error sending email to {$cEmail}: " . $e->getMessage());
                    }
                }
                else {
                    Log::warning("Email address is empty, skipping email for " . $today->format('Y-m-d'));
                }
            }

        } catch (\Throwable $e) {
            Log::error("Unexpected error in sendTodayExpenseReport: " . $e->getMessage());
        }
    }
    public function dailypartPaymentReport()
    {
        try {
            $today = Carbon::today();

        
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Daily Part Payments');
            $sheet->getStyle('A1:J1')->getFont()->setBold(true);

          
            $headers = [
               
                'Trip Name',
                'Customers',
                'Billing To',
                'Payment From',
                'Payment Type',
                'Amount',
                'Remark',
                'Comment',
                'Payment Date',
            ];

            $data = [];
            $data[] = $headers;

            $partPayments = PartPaymentHistory::with(['booking.trip'])
                ->whereDate('created_at', $today)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($partPayments->isEmpty()) {
                Log::info("No part payments found for today: " . $today->format('Y-m-d'));
                return;
            }
          
            foreach ($partPayments as $payment) {
              
                    $details = $payment->details;
                    $tripName = $payment->booking->trip->name ?? 'N/A';
                 
                    

                 
                
                    $customerIds = json_decode($payment->booking->customer_id, true);
                    if (!is_array($customerIds)) {
                        $customerIds = [$customerIds];
                    }

                    $customers = Customer::whereIn('id', $customerIds)
                        ->get()
                        ->map(fn($c) => trim($c->first_name . ' ' . $c->last_name))
                        ->toArray();
                    $customerNames = implode(', ', $customers);


                    $billing_to = json_decode($payment->booking->billing_to, true);
                    if (!is_array($billing_to)) {
                        $billing_to = [$billing_to];
                    }

                    $billingcustomers = Customer::whereIn('id', $billing_to)
                        ->get()
                        ->map(fn($c) => trim($c->first_name . ' ' . $c->last_name))
                        ->toArray();
                    $billingcustomerNames = implode(', ', $billingcustomers);
                
                  
                    $date        = $details['date'] ?? '-';
                    $amount      = $details['amount'] ?? 0;
                    $remark      = $details['remark'] ?? '-';
                    $comment     = $details['comment'] ?? '-';
                    $paymentfrom = $details['billing_cust'] ?? null;
                    $paymentType = $details['payment_type'] ?? '-';

                    $paymentCustomer = $paymentfrom
                        ? Customer::find($paymentfrom)
                        : null;

                    $paymentfromCustomerName = $paymentCustomer
                        ? trim($paymentCustomer->first_name . ' ' . $paymentCustomer->last_name)
                        : '-';

                    $data[] = [
                        $tripName,
                        $customerNames,
                        $billingcustomerNames,
                        $paymentfromCustomerName,
                        $paymentType,
                        $amount,
                        $remark,
                        $comment,
                        $date,
                    ];
            
            }

            $sheet->fromArray($data, null, 'A1');
            foreach (range('A', 'J') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

          
            $directory = storage_path('app/admin/daily-partpayment-reports');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $fileName = 'daily_partpayment_report_' . $today->format('Y-m-d') . '.xlsx';
            $filePath = $directory . '/' . $fileName;

         
                $writer = new Xlsx($spreadsheet);
                $writer->save($filePath);
          

            $accountEmail = "vageesh@adventuresoverland.com";
            $operationmail = "vageeshpaliwal007@gmail.com";

            $emails = array_unique(
                array_merge(
                    array_map('trim', explode(',', $accountEmail)),
                    array_map('trim', explode(',', $operationmail))
                )
            );

            $dataMail = [
                'attachment' => $filePath,
                'today' => $today->format('d M Y'),
            ];

            foreach ($emails as $cEmail) {
                $cEmail = trim($cEmail);
                if (!empty($cEmail)) {
                        if (setting('mail_status') == 1) {
                            @event(new SendMailEvent(
                                $cEmail,
                                "Today's Part Payment Report - " . $today->format('d M Y'),
                                'emails.daily-partpayment-report',
                                $dataMail
                            ));
                            Log::info("Today's Part Payment Report email sent to $cEmail for " . $today->format('Y-m-d'));
                        } else {
                            Log::info("Mail status is disabled, not sending email to $cEmail");
                        }
                }
            }
        } catch (\Throwable $e) {
            Log::error("Unexpected error in dailypartPaymentReport: " . $e->getMessage());
        }
    }


}
