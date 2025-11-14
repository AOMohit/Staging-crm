<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripBooking;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\Expense;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function checkExpense()
    {
        $vendors = Vendor::all();
        $trips = Trip::orderBy('id', 'Desc')->get();
        return view('admin.accounts.check-expense', compact('vendors', 'trips'));
    }

    public function getCheckExpense(Request $request)
    {
        $date = date("Y-m-d");
        $data = Expense::orderBy('id', 'Desc');
        
        // filter
        if(isset($request->tripName)){
            $data->where('trip_id',$request->tripName);
        }

        if(isset($request->vendor)){
            $data->where('vendor_id',$request->vendor);
        }
        
        if(isset($request->dateFrom) && isset($request->dateTo)){
            $data->whereDate('created_at', '>=', $request->dateFrom)->whereDate('created_at', '<=', $request->dateTo);
        }elseif(!isset($request->dateFrom) && isset($request->dateTo)){
            $data->whereDate('created_at', '>=', $date)->whereDate('created_at', '<=', $request->dateTo);
        }elseif(isset($request->dateFrom) && !isset($request->dateTo)){
            $data->whereDate('created_at', '>=', $request->dateFrom)->whereDate('created_at', '<=', $date);
        }
        
        // filter

        $data = $data->get();
        // storing query in a session
        session()->put('account_expense_query', $data);
        
        $data->map(function ($item, $index) use ($date) {
            $item->created = date("M d, Y H:i:s", strtotime($item->created_at));
            $item->trip_name = $item->trip->name ?? " ";
            $item->added_by = $item->admin->name ?? " ";
        });
        // dd($data);
        return DataTables::of($data)->make(true);
    }
    
    public function exportCheckExpense()
    {
        if (session()->has('account_expense_query')) {
            $datas = session()->get('account_expense_query');
        }else{
            $datas = Expense::orderBy('id', 'Desc')->get();
        }

        // Prepare data for export
        $data = [];
        $data[] = ['Amount', 'Date Time', 'Expense Added By', 'Trip Name'];

        foreach ($datas as $item) {
            $data[] = [
                "₹".$item->total_amount,
                date("Y-m-d H:i:s", strtotime($item->created_at)),
                $item->admin->name,
                $item->trip->name ?? null,
            ];
        }

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add data to the spreadsheet
        $sheet->fromArray($data, null, 'A1');

        // Set auto column size for all columns
        foreach(range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Create a writer for XLSX format
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="check-expense.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet data to a file
        $writer->save('php://output');
    }

    public function paymentReceived()
    {
        $customers = Customer::all();
        $trips = Trip::orderBy('id', 'Desc')->get();
        return view('admin.accounts.payment-received', compact('customers', 'trips'));
    }

    public function getPaymentReceived(Request $request)
    {
        $date = date("Y-m-d");
        $dataArr = [];
        $data = TripBooking::orderBy('id', 'DESC');
        
        // filter
        if(isset($request->tripName)){
            $data->where('trip_id',$request->tripName);
        }

        if(isset($request->customer)){
            $data->whereJsonContains('customer_id',"$request->customer");
        }

        $data = $data->get();
        foreach($data as $item){

            if($item->customer_id){
                $customerName = '';
                foreach(json_decode($item->customer_id) as $key=>$c_id){
                    $customerName .= getCustomerById($c_id)->name ?? null;
                    if(count(json_decode($item->customer_id)) != $key+1){
                        $customerName .= ', ';
                    }
                }
            }

            if($item->payment_amt > 0){
                $newData1 = ['id'=>$item->id, 'trip_name'=>$item->trip->name ?? 'Trip', 'spoc'=>$item->admin->name ?? 'Spoc', 'date'=>date("M d, Y", strtotime($item->payment_date)), 'real_date'=>date("Y-m-d", strtotime($item->payment_date)), 'amount'=>$item->payment_amt, 'customer'=>$customerName];
                array_push($dataArr, $newData1);
            }

            if($item->part_payment_list && json_decode($item->part_payment_list)){
                $part_payment_list = json_decode($item->part_payment_list);
                if(count($part_payment_list) > 0){
                    foreach($part_payment_list as $part_payment){
                        if($part_payment->amount > 0){
                            $newData2 = ['id'=>$item->id, 'trip_name'=>$item->trip->name ?? 'Trip', 'spoc'=>$item->admin->name ?? 'Spoc', 'date'=>date("M d, Y", strtotime($part_payment->date)), 'real_date'=>date("Y-m-d", strtotime($part_payment->date)),  'amount'=>$part_payment->amount, 'customer'=>$customerName];
                            array_push($dataArr, $newData2);
                        }
                    }
                }
            }
        }
        
        if(isset($request->dateFrom) && isset($request->dateTo)){
            $dateFrom = $request->dateFrom;
            $dateTo = $request->dateTo;
        }elseif(!isset($request->dateFrom) && isset($request->dateTo)){
            $dateFrom = $date;
            $dateTo = $request->dateTo;
        }elseif(isset($request->dateFrom) && !isset($request->dateTo)){
            $dateFrom = $request->dateFrom;
            $dateTo = $date;
        }else{
            $dateFrom = null;
            $dateTo = null;
        }
        // filter
        if($dateFrom && $dateTo){
            $dataArr = array_filter($dataArr, function ($item) use ($dateFrom, $dateTo) {
                return $item['real_date'] >= $dateFrom && $item['real_date'] <= $dateTo;
            });
        }

        
        usort($dataArr, function ($a, $b) {
            return strcmp($b['real_date'], $a['real_date']); // Swapped order for descending
        });
        
        
        // storing query in a session
        session()->put('payment_rec_query', $dataArr);
        
        // dd($dataArr);
        return DataTables::of($dataArr)->make(true);
    }

    public function exportPaymentReceived()
    {
        if (session()->has('payment_rec_query')) {
            $datas = session()->get('payment_rec_query');
        }else{
            return redirect()->route('accounts.payment-received');
        }
        // dd($datas);
        // Prepare data for export
        $data = [];
        $data[] = ['Amount', 'Date Time', 'Spoc', 'Customer Name', 'Trip Name'];

        foreach ($datas as $item) {
            $data[] = [
                "₹".$item['amount'],
                $item['date'],
                $item['spoc'],
                $item['customer'],
                $item['trip_name'] ?? null,
            ];
        }

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add data to the spreadsheet
        $sheet->fromArray($data, null, 'A1');

        // Set auto column size for all columns
        foreach(range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Create a writer for XLSX format
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="payment-received.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet data to a file
        $writer->save('php://output');
    }

}