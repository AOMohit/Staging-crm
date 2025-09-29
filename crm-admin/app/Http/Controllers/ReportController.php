<?php

namespace App\Http\Controllers;

use App\Models\TripBooking;
use App\Models\Expense;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Vendor;
use App\Models\EmailActivity;
use App\Models\LoyalityPts;
use App\Models\Agent;
use App\Models\Trip;
use App\Models\User;
use App\Models\TripCarbonInfo;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\Log;



class ReportController extends Controller
{
    public function index(){
        return view('admin.report.index');
    }

    public function bookingByTrip(){
        $gst = setting('gst') ?? 0;
        $tcs = setting('tcs') ?? 0;
        $bookings = TripBooking::where('trip_status', '!=' ,'Draft')->get();

        $uniqueTripWithSummary = [];
        foreach($bookings as $booking){
            if (!$booking || !$booking->trip) {
                Log::error("Invalid booking object or missing trip for booking ID: " . ($booking->id ?? 'Unknown'));
                continue;
            }
            $tripId = $booking->trip_id;
            $customerCounts = count(json_decode($booking->customer_id));
            $total_sale = $booking->payable_amt;
            if($booking->tax_required == 0 || $booking->tax_required == null){
                if($booking->trip_cost){
                    $tax = 0;
                    $ta = 0;

                    foreach(json_decode($booking->trip_cost) as $tc){
                        $ta += $tc->cost;

                        $netCost = $tc->cost;
                        $gstamt = (((float)$netCost * (float)$gst)/100);
                        $tax += $gstamt;
                        if($booking->payment_from == "Individual" && $booking->payment_from_tax){
                            $tcsamt = (((float)$netCost * (float)$booking->payment_from_tax)/100);
                            $tax += $tcsamt;
                        }elseif($booking->payment_from == "Company"){
                            if($booking->is_tds == 0){
                                $tcs = 0;
                            }
                            $tcsamt = (((float)$netCost * (float)$tcs)/100);
                            $tax += $tcsamt;
                        }
                    }
                }
                if($booking->extra_services){
                    $es = 0;
                    foreach(json_decode($booking->extra_services) as $ess){
                        $es += ($ess->amount + $ess->markup + ((float)$ess->markup * (float)$ess->tax)/100);
                    }
                }else{
                    $es = 0;
                }
            }else{
                $tax = 0;
            }
            $gross_sale = $ta;
            $net_sale = $ta + $es;

            if (!isset($uniqueTripWithSummary[$tripId])) {
                $uniqueTripWithSummary[$tripId] = [
                    'trip_id' => $tripId,
                    'trip_name' => $booking->trip->name,
                    'trip_type' => $booking->trip->trip_type,
                    'customer_count' => $customerCounts,
                    'gross_sale' => $gross_sale,
                    'net_sale' => $net_sale,
                    'total_sale' => $total_sale,
                    'tax' => $tax,
                ];
            }else{
                $uniqueTripWithSummary[$tripId]['customer_count'] += $customerCounts;
                $uniqueTripWithSummary[$tripId]['gross_sale'] += $gross_sale;
                $uniqueTripWithSummary[$tripId]['net_sale'] += $net_sale;
                $uniqueTripWithSummary[$tripId]['total_sale'] += $total_sale;
                $uniqueTripWithSummary[$tripId]['tax'] += 0;
            }
        }
        // dd($uniqueTripWithSummary);

        $data = [];
        $data[] = ['Trip Name', 'Trip Type', 'Total Travellers', 'Gross Sales', 'Net Sales', 'Tax', 'Total Sales'];

        foreach ($uniqueTripWithSummary as $item) {
            $data[] = [
                $item['trip_name'] ?? null,
                $item['trip_type'] ?? null,
                $item['customer_count'],
                $item['gross_sale'],
                $item['net_sale'],
                $item['tax'],
                $item['total_sale'],
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($data, null, 'A1');

        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="bookings-by-trip.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function bookingByTraveler(){

        $gst = setting('gst') ?? 0;
        $tcs = setting('tcs') ?? 0;
        $customers = Customer::where('parent', 0)->get();
        foreach($customers as $customer){
            $cId = $customer->id;
            $customer->trip_count = getTripCountbyCustomerId($cId);


            $bookings = TripBooking::whereJsonContains('customer_id', "$cId")->where('trip_status', '!=', 'Draft')->get();

            $ta = 0;
            $es = 0;
            $tax = 0;
            $totalAmt = 0;
            foreach($bookings as $booking){
                $tripId = $booking->trip_id;
                $total_sale = $booking->payable_amt;

                if($booking->tax_required == 0 || $booking->tax_required == null){
                    if($booking->trip_cost){

                        foreach(json_decode($booking->trip_cost) as $tc){
                            if($tc->c_id == $cId){
                                $netCost = $tc->cost;
                                $gstamt = (((float)$netCost * (float)$gst)/100);
                                $tax += $gstamt;
                                if($booking->payment_from == "Individual" && $booking->payment_from_tax){
                                    if($booking->payment_from_tax == "Auto"){
                                        if($netCost > 700000){
                                            $netCost1 = 700000;
                                            $netCost2 = $netCost - $netCost1;
                                            $tcs_amt1 = (((float)$netCost1 * 5)/100);
                                            $tcs_amt2 = (((float)$netCost2 * 20)/100);
                                            $tcs_amt = $tcs_amt1 + $tcs_amt2;
                                        }else{
                                            $tcs_amt = (((float)$netCost * 5)/100);
                                        }
                                        $tcs_per = $booking->payment_from_tax;
                                    }else{
                                        $tcs_amt = (((float)$netCost * (float)$booking->payment_from_tax)/100);
                                        $tcs_per = $booking->payment_from_tax;
                                    }

                                    $tax += $tcs_amt;
                                }elseif($booking->payment_from == "Company"){
                                    if($booking->is_tds == 0){
                                        $tcs = 0;
                                    }
                                    $tcsamt = (((float)$netCost * $tcs)/100);
                                    $tax += $tcsamt;
                                }
                            }
                        }
                    }
                }

                if($booking->trip_cost){
                    foreach(json_decode($booking->trip_cost) as $tc){
                        if($tc->c_id == $cId){
                            $ta += $tc->cost;
                        }
                    }
                }else{
                    $ta = 0;
                }
                if($booking->extra_services){
                    foreach(json_decode($booking->extra_services) as $ess){
                        if($ess->traveler == $cId){
                            $es += ($ess->amount + $ess->markup + ((float)$ess->markup * (float)$ess->tax)/100);
                        }
                    }
                }else{
                    $es = 0;
                }
                $totalAmt += getTripCostByBookingAndCustomerId($booking->id, $cId);
            }

            $customer->gross_sale = $ta;
            $customer->tax = $tax;
            $customer->net_sale = $ta + $es;
            $customer->total_sale = $totalAmt;
        }

        $data = [];
        $data[] = ['Customer', 'Email', 'Phone', 'Gender', 'City', 'Country', 'State', 'Booking Count', 'Gross Sales', 'Net Sales', 'Tax', 'Total Sales'];

        foreach ($customers as $item) {
            $data[] = [
                $item->first_name . " ".$item->last_name ?? null,
                $item->email ?? null,
                $item->phone ?? null,
                $item->gender,
                $item->city,
                $item->country,
                $item->state,
                $item->trip_count,
                $item->gross_sale,
                $item->net_sale,
                $item->tax,
                $item->total_sale,
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($data, null, 'A1');

        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="bookings-by-traveler.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function customerByLocation(){

        $customers = Customer::where('parent', 0)->get();

        $data = [];
        $data[] = ['Traveler Name', 'Phone' ,'Email', 'Country', 'State', 'City'];

        foreach ($customers as $item) {
            $data[] = [
                $item->first_name . " ".$item->last_name ?? null,
                $item->phone ?? null,
                $item->email ?? null,
                $item->country ?? null,
                $item->state ?? null,
                $item->city ?? null,
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($data, null, 'A1');

        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="customer-by-location.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function paymentByType(){

        $customers = TripBooking::where('trip_status', '!=', 'Draft')->get();

        $data = [];
        $data[] = ['Payment Type', 'Trip Name' ,'Customer Name'];

        foreach ($customers as $item) {

            $cList = json_decode($item->customer_id) ?? null;
            $cLists = "";
            foreach($cList as $list){
                $cLists .= getCustomerById($list)->name .", ";
            }

            $data[] = [
                $item->payment_type,
                $item->trip->name ?? null,
                $cLists ?? null,
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($data, null, 'A1');

        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="payment-by-type.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function expenseByTrip(){

        $customers = Expense::all();

        $data = [];
        $data[] = ['Trip Name', 'Vendor', 'Category' ,'Expense Type', 'Total Amount', 'Paid Amount', 'Remaining Amount'];

        foreach ($customers as $item) {

            if(isset($item->vendor->vendor_type)){
                $vendorType = $item->vendor->vendor_type;
                $vendorTypeData = getVendorCategoryById(json_decode($item->vendor->vendor_type)[0])->title;
            }else{
                $vendorTypeData = " ";
            }


            $data[] = [
                $item->trip->name ?? null,
                ($item->vendor->first_name ?? " ") ." ".($item->vendor->last_name ?? " "),
                $vendorTypeData,
                $item->service->title ?? null,
                $item->total_amount,
                $item->paid_amount,
                $item->total_amount-$item->paid_amount
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($data, null, 'A1');

        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="expense-by-trip.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function expenseByVendor(){

        $customers = Expense::all();

        $data = [];
        $data[] = ['Trip Name', 'Vendor', 'Expense Type', 'Total Amount', 'Paid Amount', 'Remaining Amount'];

        foreach ($customers as $item) {

            $data[] = [
                $item->trip->name ?? null,
                $item->vendor->first_name ." ". $item->vendor->last_name ?? null,
                $item->service->title ?? null,
                $item->total_amount,
                $item->paid_amount,
                $item->total_amount-$item->paid_amount
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($data, null, 'A1');

        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="expense-by-trip.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function bookingByAgent(){

        $bookings = TripBooking::where('trip_status', '!=', 'Draft')->where('lead_source', 'Agent')->get();


        $data = [];
        $data[] = ['Agent Name', 'Trip Name', 'Referred Customer Name', 'Total Travellers'];

        foreach ($bookings as $item) {

            $cList = json_decode($item->customer_id) ?? null;
            $cListCount = count(json_decode($item->customer_id));
            $cLists = "";
            foreach($cList as $list){
                $cLists .= getCustomerById($list)->name .", ";
            }

            $data[] = [
                $item->sub_lead_source,
                $item->trip->name ?? null,
                $cLists ?? null,
                $cListCount,
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($data, null, 'A1');

        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="bookings-by-agent.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function receivables(Request $request){
        $data = TripBooking::whereNotIn('trip_status', ['Cancelled', 'Draft'])->orderBy('id', 'desc')->get();

        $expData = [];
        $expData[] = ['Trip Name' ,'Total Receivables', 'Total Recieved', 'Net Receivables'];

        foreach($data as $item){
            $advanceAmt = 0;
            $totalRec = 0;
            $totalPart = 0;
            if (!$item || !$item->trip) {
                Log::error("Invalid booking object or missing trip for booking ID: " . ($item->id ?? 'Unknown'));
                continue;
            }

            $item->trip_name = $item->trip->name;

            $advanceAmt += $item->payment_amt ?? 0;

            if($item->sch_payment_list){
                $schs = json_decode($item->sch_payment_list) ?? [""];

                foreach($schs as $sch){
                    $totalRec += $sch->amount;
                }
            }

            if($item->part_payment_list){
                $parts = json_decode($item->part_payment_list) ?? [""];

                foreach($parts as $part){
                    $totalPart += $part->amount;
                }
            }
            $total_due = $totalRec - $totalPart;

            if($totalRec != null && $totalRec > 0){
                $expData[] = [
                    $item->trip->name,
                    "₹".$totalRec ?? null,
                    "₹".$totalPart ?? null,
                    "₹".$total_due ?? null,
                ];
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($expData, null, 'A1');

        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="net-receivables.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function loyaltyPtsRdm(Request $request){
        $bookings = TripBooking::whereNotIn('trip_status', ['Cancelled', 'Draft'])->whereNotNull('redeem_points')->get();

        $data = [];
        $data[] = ['Customer Name', 'Email', 'Phone' ,'Trip Name', 'Points'];

        foreach($bookings as $item){
            if($item->redeem_points != null){
                foreach(json_decode($item->redeem_points) as $point){
                    $item->customer_name = getCustomerById($point->c_id)->name;
                    $item->customer_email = getCustomerById($point->c_id)->email;
                    $item->points = $point->points;
                }
                $data[] = [
                    $item->customer_name,
                    $item->customer_email ?? null,
                    "$item->telephone_code"."$item->phone" ?? null,
                    $item->trip->name,
                    $item->points,
                ];
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($data, null, 'A1');

        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="loyalty-points-redeemed.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function loyaltyPtsAvail(Request $request){
        $bookings = Customer::all();

        $data = [];
        $data[] = ['Customer Name', 'Email', 'Phone', 'Points'];

        foreach($bookings as $item){
            $item->customer_name = $item->first_name." ".$item->last_name;
            $item->customer_email = $item->email;
            $item->points = $item->points;
            $data[] = [
                $item->customer_name,
                $item->customer_email ?? null,
                "$item->telephone_code"."$item->phone" ?? null,
                $item->points,
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($data, null, 'A1');

        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="loyalty-points-available.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function bookingByLead(Request $request){
        $data = TripBooking::whereNotIn('trip_status', ['Cancelled', 'Draft'])->orderBy('id', 'desc')->get();

        $expData = [];
        $expData[] = ['Customer Name', 'Email','Phone', 'Booking Source', 'Sub Source' ,'Trip Name'];

        foreach($data as $item){
            if (!$item || !$item->trip) {
                Log::error("Invalid booking object or missing trip for booking ID: " . ($item->id ?? 'Unknown'));
                continue;
            }
            $item->trip_name = $item->trip->name;

            $cList = json_decode($item->customer_id) ?? null;
            foreach($cList as $list){
                $cName = getCustomerById($list)->name ?? " ";
                $cPhone = (getCustomerById($list)->telephone_code ?? " ").(getCustomerById($list)->phone ?? " ");
                $cEmail = getCustomerById($list)->email ?? " ";

                $expData[] = [
                    $cName,
                    $cEmail,
                    $cPhone,
                    $item->lead_source,
                    $item->sub_lead_source,
                    $item->trip->name,
                ];
            }

        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($expData, null, 'A1');

        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="booking-by-lead.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function bookingByGender(Request $request){
        $data = TripBooking::whereNotIn('trip_status', ['Cancelled', 'Draft'])->orderBy('id', 'desc')->get();

        $expData = [];
        $expData[] = ['Customer Name', 'Email', 'Phone', 'Gender' ,'Trip Name'];

        foreach($data as $item){
            if (!$item || !$item->trip) {
                Log::error("Invalid booking object or missing trip for booking ID: " . ($item->id ?? 'Unknown'));
                continue;
            }
            $item->trip_name = $item->trip->name;

            $cList = json_decode($item->customer_id) ?? null;
            foreach($cList as $list){
                $cName = getCustomerById($list)->name ?? " ";
                $cPhone = (getCustomerById($list)->telephone_code ?? " ").(getCustomerById($list)->phone ?? " ");
                $cEmail = getCustomerById($list)->email ?? " ";
                $cGender = getCustomerById($list)->gender ?? " ";

                $expData[] = [
                    $cName,
                    $cEmail,
                    $cPhone,
                    $cGender,
                    $item->trip->name,
                ];
            }

        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($expData, null, 'A1');

        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="booking-by-gender.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function bookingByShirt(Request $request){
        $data = TripBooking::whereNotIn('trip_status', ['Cancelled', 'Draft'])->orderBy('id', 'desc')->get();

        $expData = [];
        $expData[] = ['Customer Name', 'Email', 'Phone', 'T-shirt Size' ,'Trip Name'];

        foreach($data as $item){
            if (!$item || !$item->trip) {
                Log::error("Invalid booking object or missing trip for booking ID: " . ($item->id ?? 'Unknown'));
                continue;
            }
            $item->trip_name = $item->trip->name;

            $cList = json_decode($item->customer_id) ?? null;
            foreach($cList as $list){
                $cName = getCustomerById($list)->name ?? " ";
                $cPhone = (getCustomerById($list)->telephone_code ?? " ").(getCustomerById($list)->phone ?? " ");
                $cEmail = getCustomerById($list)->email ?? " ";
                $cSize = getCustomerById($list)->t_size ?? " ";

                $expData[] = [
                    $cName,
                    $cEmail,
                    $cPhone,
                    $cSize,
                    $item->trip->name,
                ];
            }

        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($expData, null, 'A1');

        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="booking-by-t-shirt.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function sustainability(Request $request){
        $data = TripCarbonInfo::orderBy('id', 'desc')->get();

        $expData = [];
        $expData[] = ['Trip Name', 'Customer Name', 'Email', 'Phone', 'Donation Amount', 'No Of Tree', 'Type'];

        foreach($data as $item){
            if($item->carbon_accepted == 1){
                $type = "Opt In";
            }else{
                $type = "Opt Out";
            }
            $item->trip_name = $item->trip->name;

            $cName = getCustomerById($item->customer_id)->name;
            $cPhone = getCustomerById($item->customer_id)->telephone_code.getCustomerById($item->customer_id)->phone;
            $cEmail = getCustomerById($item->customer_id)->email;
            $expData[] = [
                $item->trip_name ?? "Deleted",
                $cName,
                $cEmail,
                $cPhone,
                "₹".$item->donation_amt,
                $item->tree_no,
                $type,
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($expData, null, 'A1');

        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="carbon-offset-report.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function ongoingTrip()
    {
        $currentDate = Carbon::today()->toDateString();

   $bookings = TripBooking::whereNotIn('trip_status', ['Draft', 'Cancelled'])
            ->whereHas('trip', function ($query) use ($currentDate) {
                $query->where(function ($q) use ($currentDate) {
                    $q->where('start_date', '<=', $currentDate)
                        ->where('end_date', '>=', $currentDate);
                })->orWhere(function ($q) use ($currentDate) {
                    $q->where('start_date', '>', $currentDate);
                });
            })->get();

        $uniqueTripWithSummary = [];

        foreach ($bookings as $booking) {
            $tripId = $booking->trip_id;
            $tripType = $booking->trip->trip_type ?? 'Unknown';
            $tripName = $booking->trip->name ?? 'Unknown';
            $pax = getPaxFromTripId($tripId);

        // $expenses = Expense::where('trip_id', $tripId)->sum('paid_amount');
            $vendorPayments = Expense::where('trip_id', $tripId)->get();
            // $totalVendorPaidAmount = $vendorPayments->sum('paid_amount');
            // $totalVendorAmount = $vendorPayments->sum('total_amount');
            // $pendingVendorPayment = $totalVendorAmount - $totalVendorPaidAmount;
            $expensePaidAmount=0;
            foreach ($vendorPayments as $payment) {
                $expensePaidAmount = $expensePaidAmount + $payment->paid_amount ?? 0;
            }
            $total_sale = $booking->payable_amt ?? 0;
            $expense_due = $total_sale * 70/100;
      
            $expense_pending = $expense_due - $expensePaidAmount;

            $total_sale = $booking->payable_amt ?? 0;
            $amountRec = $booking->payment_amt ?? 0;
            $amountPending = 0;

            // Handle part payments
            $partPaymentLists = json_decode($booking->part_payment_list) ?? [];
            if (!empty($partPaymentLists)) {
                foreach ($partPaymentLists as $partPayment) {
                    $amountRec += ($partPayment->amount ?? 0);
                }
            }

            $amountPending = (float)$total_sale - (float)$amountRec;

            // Group by trip type
            if (!isset($uniqueTripWithSummary[$tripType])) {
                $uniqueTripWithSummary[$tripType] = [];
            }

            // Store trip data grouped by trip type
            if (!isset($uniqueTripWithSummary[$tripType][$tripId])) {
                $uniqueTripWithSummary[$tripType][$tripId] = [
                    'trip_id' => $tripId,
                    'trip_name' => $tripName,
                    'trip_type' => $tripType,
                    'pax' => $pax,
                    'total_sale' => $total_sale,
                    'amount_collected' => $amountRec,
                    'amount_pending' => $amountPending,
                     'expense_due' => $expense_due,
                    'expense_Paid_Amount' => $expensePaidAmount,
                    'expense_pending' => $expense_pending,
                    // 'vendor_due_payment' => $totalVendorAmount,
                    // 'vendor_paid_amount' => $totalVendorPaidAmount,
                    // 'vendor_amount' => $totalVendorAmount,
                    // 'pending_vendor_amount' => $pendingVendorPayment,
                ];
            } else {
                $uniqueTripWithSummary[$tripType][$tripId]['total_sale'] += $total_sale;
                $uniqueTripWithSummary[$tripType][$tripId]['amount_collected'] += $amountRec;
                $uniqueTripWithSummary[$tripType][$tripId]['amount_pending'] += $amountPending;
                $uniqueTripWithSummary[$tripType][$tripId]['expense_due'] += $expense_due;
                $uniqueTripWithSummary[$tripType][$tripId]['expense_Paid_Amount'] += $expensePaidAmount;
                $uniqueTripWithSummary[$tripType][$tripId]['expense_pending'] += $expense_pending;
                // $uniqueTripWithSummary[$tripType][$tripId]['vendor_due_payment'] = $totalVendorAmount;
                // $uniqueTripWithSummary[$tripType][$tripId]['vendor_paid_amount'] = $totalVendorPaidAmount;
                // $uniqueTripWithSummary[$tripType][$tripId]['pending_vendor_amount'] = $pendingVendorPayment;
            }
        }

        // Create Excel File
        $spreadsheet = new Spreadsheet();

        foreach ($uniqueTripWithSummary as $tripType => $trips) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle(substr($tripType, 0, 30)); // Sheet name limit is 31 characters

            // Header Row
            $data = [['Trip Name', 'Trip Type', 'Pax', 'Sales Revenue', 'Amount Collected', 'Amount Pending', 'Expense Due', 'Expense Paid Amount', 'Expense Pending']];

            $salesRevenue = 0;
            $totalCollected = 0;
            $totalPending = 0;
            // $totalVendorAmount = 0;
            $totalPax = 0;
            $expense_due = 0;
            $expense_paid  = 0;
            $expense_pending = 0;
            // $vendorPaidAmount = 0;
            // $pendingVendorPayment = 0;

            foreach ($trips as $trip) {
                $data[] = [
                    $trip['trip_name'],
                    $trip['trip_type'],
                    $trip['pax'],
                    $trip['total_sale'],
                    $trip['amount_collected'],
                    $trip['amount_pending'],
                    $trip['expense_due'],
                    $trip['expense_Paid_Amount'], 
                    $trip['expense_pending'],
                    // $trip['vendor_due_payment'],
                    // $trip['vendor_paid_amount'],
                    // $trip['pending_vendor_amount'],
                ];

                // Sum up totals
                $totalPax += $trip['pax'];
                $salesRevenue += $trip['total_sale'];
                $totalCollected += $trip['amount_collected'];
                $totalPending += $trip['amount_pending'];
                // $totalVendorAmount += $trip['vendor_due_payment'];
                // $vendorPaidAmount += $trip['vendor_paid_amount'];
                // $pendingVendorPayment += $trip['pending_vendor_amount'];
                 $expense_due += $trip['expense_due'];
                $expense_paid += $trip['expense_Paid_Amount'];
                $expense_pending += $trip['expense_pending'];

            }

            // Add total row
            $data[] = ['Total', '',$totalPax, $salesRevenue, $totalCollected, $totalPending, $expense_due, $expense_paid, $expense_pending];

            // Add data to sheet
            $sheet->fromArray($data, null, 'A1');

            // Auto size columns
            foreach (range('A', 'H') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            // Style total row (bold)
            $lastRow = count($data);
            $sheet->getStyle("A{$lastRow}:H{$lastRow}")->getFont()->setBold(true);
        }

        // Remove the default sheet (if empty)
        $spreadsheet->removeSheetByIndex(0);

        // Generate and download the Excel file
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Upcoming-trips-revenue-report.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }


    public function ongoingTripPdf(){
        $currentDate = Carbon::today()->toDateString();

        $bookings = TripBooking::where('trip_status', '!=', 'Draft')
                    ->whereHas('trip', function ($query) use ($currentDate) {
                        $query->where('start_date', '<=', $currentDate)
                            ->where('end_date', '>=', $currentDate);
                    })->get();

        $uniqueTripWithSummary = [];
        foreach($bookings as $booking){
            $expenses = Expense::where('trip_id', $booking->trip_id)->sum('paid_amount');

            $tripId = $booking->trip_id;
            $total_sale = $booking->payable_amt ?? 0;
            $amountRec = $booking->payment_amt ?? 0;
            $amountPending = 0;

            $partPaymentLists = json_decode($booking->part_payment_list) ?? [];
            if(count($partPaymentLists)){
                foreach($partPaymentLists as $partPaymentList){
                    $amountRec += ($partPaymentList->amount ?? 0);
                }
            }
            $amountPending += (float)$total_sale - (float)$amountRec;

            if (!isset($uniqueTripWithSummary[$tripId])) {
                $uniqueTripWithSummary[$tripId] = [
                    'trip_id' => $tripId,
                    'total_sale' => $total_sale,
                    'amount_collected' => $amountRec,
                    'amount_pending' => $amountPending,
                    'vendor_payment' => $expenses,
                    'trip_name' => $booking->trip->name,
                    'trip_type' => $booking->trip->trip_type,
                ];
            }else{
                $uniqueTripWithSummary[$tripId]['total_sale'] += $total_sale;
                $uniqueTripWithSummary[$tripId]['amount_collected'] += $amountRec;
                $uniqueTripWithSummary[$tripId]['amount_pending'] += $amountPending;
                $uniqueTripWithSummary[$tripId]['vendor_payment'] = $expenses;
                $uniqueTripWithSummary[$tripId]['trip_name'] = $booking->trip->name;
                $uniqueTripWithSummary[$tripId]['trip_type'] = $booking->trip->trip_type;
            }
        }

        return $uniqueTripWithSummary;
    }


    public function sentInvoice()
    {

        $sentInvoiceData = TripBooking::with('trip') 
            ->where('invoice_status', '=', 'sent')
            ->get();

        $expData = [];
        $expData[] = ['Date', 'Trip Name', 'Customer Name', 'Pax', 'Trip Type', 'Trip Cost', 'Paid','Balance' ,'Booking Status', 'Invoice Status', 'Invoice Sent Date', 'Spoc'];

        foreach ($sentInvoiceData as $data) {
            $trip_name = $data->trip->name ?? 'N/A';
            $customerIds = json_decode($data->customer_id);

            $customer_names = [];
            if (is_array($customerIds)) {
                $customers = Customer::whereIn('id', $customerIds)->get();
                $pax = count($customers);
                foreach ($customers as $customer) {
                    $customer_names[] = $customer->first_name . ' ' . $customer->last_name;
                }
            }
            $customer_name = implode(', ', $customer_names) ?: 'N/A';

        
            $trip_type = $data->trip->trip_type ?? 'N/A';
            $trip_cost = $data->payable_amt ?? 0;
            $paid = $data->payment_amt ?? 0;
            $balance = $trip_cost - $paid; 
            $booking_status = $data->trip_status ?? 'N/A';
            $invoice_status = $data->invoice_status ?? 'N/A';
            $invoice_sent_date = $data->invoice_sent_date ?? 'N/A';
            $spoc =  $data->admin->name;
        

            $expData[] = [
                $data->created_at->format('Y-m-d'),
                $trip_name,
                $customer_name,
                $pax,
                $trip_type,
                $trip_cost,
                $paid,
                $balance,
                $booking_status,
                $invoice_status,
                $invoice_sent_date,
                $spoc,
            ];
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($expData, null, 'A1');

    
        foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="sent-invoices.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
    public function pendingInvoice()
    {

        $sentInvoiceData = TripBooking::with('trip') 
            ->where('invoice_status', '=', 'Pending')
            ->get();

        $expData = [];
        $expData[] = ['Date', 'Trip Name', 'Customer Name', 'Pax', 'Trip Type', 'Trip Cost', 'Paid','Balance' ,'Booking Status', 'Invoice Status','Spoc'];

        foreach ($sentInvoiceData as $data) {
            $trip_name = $data->trip->name ?? 'N/A';
            $customerIds = json_decode($data->customer_id);

            $customer_names = [];
            if (is_array($customerIds)) {
                $customers = Customer::whereIn('id', $customerIds)->get();
                $pax = count($customers);
                foreach ($customers as $customer) {
                    $customer_names[] = $customer->first_name . ' ' . $customer->last_name;
                }
            }
            $customer_name = implode(', ', $customer_names) ?: 'N/A';

        
            $trip_type = $data->trip->trip_type ?? 'N/A';
            $trip_cost = $data->payable_amt ?? 0;
            $paid = $data->payment_amt ?? 0;
            $balance = $trip_cost - $paid; 
            $booking_status = $data->trip_status ?? 'N/A';
            $invoice_status = $data->invoice_status ?? 'N/A';
            $invoice_sent_date = $data->invoice_sent_date ?? 'N/A';
            $spoc =  $data->admin->name ?? 'NA';
        

            $expData[] = [
                $data->created_at->format('Y-m-d'),
                $trip_name,
                $customer_name,
                $pax,
                $trip_type,
                $trip_cost,
                $paid,
                $balance,
                $booking_status,
                $invoice_status,
                $spoc,
            ];
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($expData, null, 'A1');

    
        foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Pending-invoices.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
