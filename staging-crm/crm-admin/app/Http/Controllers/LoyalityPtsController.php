<?php

namespace App\Http\Controllers;

use App\Models\LoyalityPts;
use App\Models\TransferPts;
use App\Models\Customer;
use App\Models\Trip;
use App\Models\User;
use App\Models\ActivityTracker;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Events\SendMailEvent;
use Illuminate\Support\Collection;


class LoyalityPtsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gift = LoyalityPts::where('trans_page', 'AdminGift')->sum('trans_amt');
        $earned = LoyalityPts::where('trans_page', 'TripBooking')->sum('trans_amt');
        $discovery = Customer::where('tier', 'Discovery')->count();
        $adventurer = Customer::where('tier', 'Adventurer')->count();
        $explorer = Customer::where('tier', 'Explorer')->count();
        $legends = Customer::where('tier', 'Legends')->count();

        $expired = LoyalityPts::whereDate('expiry_date', '<', date("Y-m-d"))->sum('trans_amt');
        $redeem = LoyalityPts::where('trans_type', 'Dr')->sum('trans_amt');
        $transfer = TransferPts::sum('trans_amt');

        return view('admin.loyalty.index', compact('earned', 'gift', 'legends', 'explorer', 'adventurer', 'discovery', 'expired', 'transfer', 'redeem'));
    }

    public function get()
    {
        $data = LoyalityPts::where('trans_page', 'AdminGift')->orderBy('id', 'Desc')->get();

        $data->map(function ($item, $index) {
            $item->created = date("M d, Y H:i:s", strtotime($item->created_at));
            $item->name = $item->customer->first_name ." ".$item->customer->last_name;
            $item->email = $item->customer->email;
            $item->admin_name = $item->admin->name;
        });
        

        return DataTables::of($data)->make(true);
    }

    public function cashback()
    {
        $data = LoyalityPts::where('trans_page', 'TripBooking')->orderBy('id', 'Desc')->get();

        $data->map(function ($item, $index) {
            $item->created = date("M d, Y H:i:s", strtotime($item->created_at));
            $item->name = ($item->customer ? $item->customer->first_name : '') . " " . ($item->customer ? $item->customer->last_name : '');
            $item->email = $item->customer->email ?? null;
            $item->admin_name = $item->admin->name ?? null;
        });
        return DataTables::of($data)->make(true);
    }



    public function create(){
        $customers = Customer::where('parent', 0)->orderBy('first_name', 'asc')->get();
        $trips = Trip::orderBy('name', 'asc')->get();
        return view('admin.loyalty.add', compact('customers', 'trips'));
    }

    public function store(Request $request){
        $request->validate([
            'customer' => ['required'],
            'trans_amt' => ['required'],
            'reason' => ['required'],
            'otp' => ['required'],
        ]);

        // verify OTP first
        if($request->otp == session()->get('loyality_otp')){
            $data = new LoyalityPts();
            $data->customer_id = $request->customer;
            $data->trans_amt = $request->trans_amt;
            $data->reason = $request->reason;
            $data->admin_id = Auth::user()->id;

            if($request->trip != null){
                $trip = Trip::find($request->trip);
                $trip_name = $trip->name;
                $trip_cost = $trip->price;
                
                $data->trip_name = $trip_name;
                $data->cost = $trip_cost;
            }
            $data->expiry_date = date('Y-m-d', strtotime('+2 year'));

            $data->trans_type = "Cr";
            $data->balance = $request->trans_amt;
            $data->trans_page = "AdminGift";

            $data->save();

            // add customer's wallet
            $customer = Customer::find($request->customer);
            $customer->points = $customer->points + $request->trans_amt;
            $customer->save();


            // Activity Tracker
            $activity = new ActivityTracker();
            $activity->admin_id = Auth::user()->id;
            $activity->action = Auth::user()->name ." has gifted ". $request->trans_amt ." points to ". $customer->first_name;
            $activity->page = "loyalty";
            $activity->page_data_id = $data->id;
            $activity->save();
            // Activity Tracker
            $cData = getCustomerById($request->customer);
            if($cData){
                $data = [
                    'email' => $cData->email,
                    'name' => $cData->name,
                    'points' => $request->trans_amt,
                    'tier' => $cData->tier,
                    'link' => env('USER_URL'),
                ];
                if(setting('mail_status') == 1){
                    event(new SendMailEvent("$cData->email", 'You Recieved '.$request->trans_amt.' Loyalty Points as Gift | Adventures Overland!', 'emails.point-redeem', $data));
                }
            }
        }else{
            return redirect(route('loyalty.gift'))->with('error', 'Incorrect OTP Found!!');
        }
        
        return redirect(route('loyalty.index'))->with('success', 'Updated Successfully !!');
    }



    public function export()
    {
        // Fetch data from the Customer model
        $datas = LoyalityPts::where('trans_page', 'AdminGift')->get();

        // Prepare data for export
        $data = [];
        $data[] = ["Added Date","Customer Name", "Initated By", "Reason", "Amount", "Status"];

        foreach ($datas as $item) {
            $data[] = [
                $item->created_at,
                $item->customer->first_name." ".$item->customer->last_name,
                $item->admin->name ?? null,
                $item->reason,
                $item->trans_amt,
                $item->status,
            ];
        }

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add data to the spreadsheet
        $sheet->fromArray($data, null, 'A1');

        // Set auto column size for all columns
        foreach(range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Create a writer for XLSX format
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="loyaly-transactions.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet data to a file
        $writer->save('php://output');
    }

    public function activityPage($id)
    {
        $data = $id;
        return view('admin.loyalty.activity', compact('data'));
    }

    public function activity(Request $request)
    {
        if(checkAdminRole()){
            $data = ActivityTracker::where(['page'=> 'loyalty', 'page_data_id'=>$request->id])->orderBy('id', 'desc')->get();
        }else{
            $data = ActivityTracker::where(['page'=> 'loyalty', 'page_data_id'=>$request->id])->where('admin_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        }

        $data->map(function ($item, $index) {
            $item->created = date("M d, Y H:i:s", strtotime($item->created_at));
            $item->name = $item->admin->name;
        });
        return DataTables::of($data)->make(true);
    }

    public function confirmationEmail(Request $request){
        $customer_id = $request->customer_id;
        $points = $request->points;
        $customer = getCustomerById($customer_id);
        

        if($customer){
            $otp = rand(111111, 999999);
            // =========== email ==================
            $adminEmail = setting('master_admin_mail');

            //mail to admin 
            if($adminEmail){
                $data = [
                    'email' => $customer->email,
                    'name' => $customer->name,
                    'points' => $points,
                    'otp' => $otp,
                    'admin_email'=>$adminEmail,
                ];
                if(setting('mail_status') == 1){
                    event(new SendMailEvent("$adminEmail", 'OTP for Loyalty Points Gift to '.$customer->name.' !', 'emails.verify-point-redeem', $data));
                }
            }
            session()->put('loyality_otp', $otp);
        }
        echo 1;
        return 1;
    }

}