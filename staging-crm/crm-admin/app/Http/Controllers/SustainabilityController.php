<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\User;
use App\Models\TripCarbonInfo;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SustainabilityController extends Controller
{
    public function index(){
        $date = date("Y-m-d");
        $admins = User::all();
        $trips = Trip::orderBy('id', 'Desc')->where('end_date', '<', $date)->get();
        return view('admin.sustainability.index', compact('admins', 'trips'));
    }

    public function get(Request $request)
    {
        $date = date("Y-m-d");
        $data = Trip::whereNotNull('tree_no')->whereNotNull('donation_amt');

        // filter
        if(isset($request->name)){
            $data->where('id',$request->name);
        }
        if(isset($request->trip_type)){
            $data->where('trip_type', $request->trip_type);
        }
        if(isset($request->admin)){
            $data->where('added_by', $request->admin);
        }
        if(isset($request->date)){
            $data->whereDate('start_date', '<=' ,$request->date)
                      ->whereDate('end_date', '>=' ,$request->date);
        }
        if(isset($request->status)){
            if($request->status == "Ongoing"){
                $data->where('start_date', '<=' , date("Y-m-d"))->where('end_date', '>=' , date("Y-m-d"));
            }

            if($request->status == "Completed"){
                $data->where('end_date', '<' , date("Y-m-d"));
            }
        }

        // filter

        $data = $data->get();
        
        $data->map(function ($item, $index) use ($date) {
            $optIn = TripCarbonInfo::where('trip_id', $item->id)->count();
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->start_from = date("M d, Y", strtotime($item->start_date));
            $item->end_to = date("M d, Y", strtotime($item->end_date));
            if($item->status == "Cancelled"){
                $item->statuss = "Cancelled";
            }else{
                if(strtotime($date ) > strtotime($item->end_date)){
                    $item->statuss = "Completed";
                }elseif((strtotime($date ) >= strtotime($item->start_date)) && strtotime($date ) <= strtotime($item->end_date) ){
                    $item->statuss = "Ongoing";
                }else{
                    $item->statuss = "Upcomming";
                }
            }

            $pax = getPaxFromTripId($item->id);
            $item->pax = $pax;
            $item->opt_in = $optIn;
            $item->opt_out = $pax - $optIn;
            $item->added_by = $item->admin->name;
        });
        // dd($data);
        return DataTables::of($data)->make(true);
    }

    public function view(){
        return view('admin.sustainability.view');
    }

    public function sustainabilityList(Request $request)
    {
        $date = date("Y-m-d");
        if($request->type == "optIn"){
            $data = TripCarbonInfo::where('trip_id', $request->trip_id)->where('carbon_accepted', 1)->get();
        }elseif($request->type == "optOut"){
            $data = TripCarbonInfo::where('trip_id', $request->trip_id)->where('carbon_accepted', 0)->get();
        }
        $data->map(function ($item, $index) use ($date) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->name = $item->trip->name;
            $item->customer_name = \getCustomerById($item->customer_id)->name ??  null;
        });
        // dd($data);
        return DataTables::of($data)->make(true);
    }

    public function export()
    {

        $customers = TripCarbonInfo::all();
        $customers->map(function ($item, $index) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->name = $item->trip->name;
            $item->customer_name = \getCustomerById($item->customer_id)->name ??  null;
            $item->pan_card = \getCustomerById($item->customer_id)->pan_gst ??  null;
        });

        // Prepare data for export
        $data = [];
        $data[] = ["Trip Name",	"Traveller Name",	"Donation Amount",	"No of Tree",	"Pan Card", "Sustainability Type"];

        foreach ($customers as $customer) {
            if($customer->carbon_accepted == 1){
                $type = "Opt In";
            }else{
                $type = "Opt Out";
            }
            $data[] = [
                $customer->name,
                $customer->customer_name,
                "₹".$customer->donation_amt,
                $customer->tree_no,
                $customer->pan_card,
                $type,
            ];
        }

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add data to the spreadsheet
        $sheet->fromArray($data, null, 'A1');

        // Set auto column size for all columns
        foreach(range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Create a writer for XLSX format
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="sustainability-data.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet data to a file
        $writer->save('php://output');
    }
}