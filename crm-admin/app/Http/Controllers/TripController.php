<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripImage; // Added TripImage model 25.11.2025

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Room;
use App\Models\Expense;
use App\Models\Customer;
use App\Models\ExpenseHistory;
use App\Models\Vendor;
use App\Models\ExtraService;
use App\Models\VendorCategory;
use App\Models\VendorService;
use App\Models\TripBooking;
use App\Models\ActivityTracker;
use App\Models\Merchandise;
use App\Models\Stationary;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;         // Add Log facade       25.11.2025
use Illuminate\Support\Facades\Storage;     // Add Storage facade   25.11.2025
use App\Events\SendMailEvent;
use App\Events\SendExpenseMailEvent;
use App\Models\EmailActivity;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;


use Illuminate\Support\Facades\Response;

class TripController extends Controller
{

    public function index()
    {

        $admins = User::all();
        $trips = Trip::orderBy('id', 'Desc')->get();
        return view('admin.trip.index', compact('admins', 'trips'));
    }

    public function get(Request $request)
    {
        $date = date("Y-m-d");
        $data = Trip::orderBy('id', 'Desc');

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

      
        // storing query in a session
        session()->put('trip_query', $data);

        $data->map(function ($item, $index) use ($date) {
         
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->start_from = date("M d, Y", strtotime($item->start_date));
            $item->end_to = date("M d, Y", strtotime($item->end_date));
            if($item->status == "Cancelled"){
                $item->statuss = "Cancelled";
            }else{
                // check here do not edit the trip after 14 days of complition
                $item->editable = 1;

                if(strtotime($date) > strtotime($item->end_date)){
                    $item->statuss = "Completed";
                    if(getDaysDiff($date, $item->end_date) > setting('trip_edit_limit_days')){
                        $item->editable = 0;
                    }
                }elseif((strtotime($date) >= strtotime($item->start_date)) && strtotime($date ) <= strtotime($item->end_date) ){
                    $item->statuss = "Ongoing";
                }else{
                    $item->statuss = "Upcomming";
                }
            }
      
            $item->relation_manager_names = $item->relation_manager_names ?? "";
            
            $item->pax = getPaxFromTripId($item->id);
            $item->added_by = $item->admin->name ?? null;

        });
   
        return DataTables::of($data)->make(true);
    }

    public function create()
    {
        $relationManagers = User::all();
        $stationarys = Stationary::all();
        $merchandises = Merchandise::all();
        return view('admin.trip.add', compact('stationarys', 'merchandises','relationManagers'));
    }

    public function store(Request $request)
    {

    
            $request->validate([
                'trip_type' => ['required', 'string', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'start_date' => ['required'],
                'end_date' => ['required'],
                'price' => ['required'],
                'region_type' => ['required'],
                'tree_no' => ['required'],
                'donation_amt' => ['required'],
                'relationManager' => ['required'],

                // gallery images validation 25.11.2025
                'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            ]);
        try {
            $data = new Trip();
            $data->trip_type = $request->trip_type;
            $data->name = $request->name;
            $data->start_date = $request->start_date;
            $data->end_date = $request->end_date;
            $data->price = $request->price;
            $data->duration_nights = (getDaysDiff($request->end_date, $request->start_date) ?? 0);
            $data->continent = $request->continent;
            $data->landscape = $request->landscape;
            $data->style = $request->style;
            $data->activity = $request->activity;
            $data->tree_no = $request->tree_no;
            $data->donation_amt = $request->donation_amt;
            $data->overview = $request->overview;
            $data->region_type = $request->region_type;
            $data->stationary_id = json_encode($request->stationary);
            $data->merchandise_id = json_encode($request->merchandise);
            if(!empty($request->relationManager)) {
                $data->relation_manager_id = json_encode($request->relationManager);
            }
            $data->added_by = Auth::user()->id;

            if ($request->hasfile('image')) {
                $data->image = $request->file('image')->store('admin/trip');
            }

            $data->save();

            // Handling gallery images upload 25.11.2025
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    if ($file && $file->isValid()) {
                        $path = $file->store('admin/trip/gallery');
                        TripImage::create([
                            'trip_id' => $data->id,
                            'image' => $path,
                        ]);
                    }
                }
            }

            // Activity Tracker
            $activity = new ActivityTracker();
            $activity->admin_id = Auth::user()->id;
            $activity->action = Auth::user()->name . " has Added " . $request->name . " Trip";
            $activity->page = "trip";
            $activity->page_data_id = $data->id;
            $activity->save();

            return redirect(route('trip.index'))->with('success', 'Updated Successfully !!');

        } catch (\Exception $e) {
            // Optionally log the error
            \Log::error('Trip creation failed: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'An error occurred while saving the trip. Please try again.');
        }

      
       
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);


        // Check if a file has been uploaded
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Load the Excel file
            $spreadsheet = IOFactory::load($file);

            // Get the first sheet of the workbook
            $sheet = $spreadsheet->getActiveSheet();

            // Get the highest row number
            $highestRow = $sheet->getHighestDataRow();

            // Define an array to store duplicate entries
            $duplicateEntries = [];

            // Iterate through each row
            for ($row = 2; $row <= $highestRow; ++$row) {
                // Get the cell value of each column
                $cellValueA = $sheet->getCell('A'. $row)->getValue();
                $cellValueB = $sheet->getCell('B'. $row)->getValue();
                $cellValueC = $sheet->getCell('C'. $row)->getValue();
                $cellValueD = $sheet->getCell('D'. $row)->getValue();
                $cellValueE = $sheet->getCell('E'. $row)->getValue();
                $cellValueF = $sheet->getCell('F'. $row)->getValue();
                $cellValueG = $sheet->getCell('G'. $row)->getValue();
                $cellValueH = $sheet->getCell('H'. $row)->getValue();
                $cellValueI = $sheet->getCell('I'. $row)->getValue();
                $cellValueJ = $sheet->getCell('J'. $row)->getValue();
                $cellValueK = $sheet->getCell('K'. $row)->getValue();
                    // Create a new model instance
                    $model = new Trip();

                    // Assign values to model attributes
                    $model->added_by = Auth::user()->id;
                    $model->trip_type = $cellValueA;
                    $model->name = $cellValueB;
                    $model->start_date = date("Y-m-d", strtotime($cellValueC));
                    $model->end_date = date("Y-m-d", strtotime($cellValueD));
                    $model->price = $cellValueE;
                    $model->duration_nights = $cellValueF;
                    $model->continent = $cellValueG;
                    $model->landscape = $cellValueH;
                    $model->style = $cellValueI;
                    $model->activity = $cellValueJ;
                    $model->overview = $cellValueK;

                    // Save the model instance to the database
                    $model->save();


                    // Activity Tracker
                    $activity = new ActivityTracker();
                    $activity->admin_id = Auth::user()->id;
                    $activity->action = Auth::user()->name ." has Imported ". $cellValueB ." Trip Through xlsx.";
                    $activity->page = "trip";
                    $activity->page_data_id = $model->id;
                    $activity->save();
                    // Activity Tracker
            }

            // Redirect back with success message
            return redirect()->back()->with('success', 'File imported successfully. Duplicate entries: ' . count($duplicateEntries));
        }

        // Redirect back with error message if no file was uploaded
        return redirect()->back()->with('error', 'No file uploaded.');

    }

    public function exportSample(){
        // Define the path to your sample file
        $sampleFilePath = public_path('samples/sample-trip.xlsx');
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        // Return the sample file as a download response
        return Response::download($sampleFilePath, 'sample-trip.xlsx', $headers);
    }

    public function export()
    {
        // Fetch data from the Customer model
        if (session()->has('trip_query')) {
            $customers = session()->get('trip_query');
        }else{
            $customers = Trip::all();
        }

        // Prepare data for export
        $data = [];
        $data[] = ["Trip Type",	"Name",'Relation Manager',	"Start Date",	"End Date",	"Cost",	"Duration Nights",	"Continent",	"Landscape",	"Style",	"Activity",	"Overview", "No Of Tree", "Donation Amount"];

        foreach ($customers as $customer) {
            $data[] = [
                $customer->trip_type,
                $customer->name,
                $customer->relation_manager_names = $customer->relation_manager_names ?? "",
                $customer->start_date,
                $customer->end_date,
                "₹".$customer->price,
                $customer->duration_nights,
                $customer->continent,
                $customer->landscape,
                $customer->style,
                $customer->activity,
                $customer->overview,
                $customer->tree_no,
                "₹".$customer->donation_amt,
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
        header('Content-Disposition: attachment;filename="trips.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet data to a file
        $writer->save('php://output');
    }

    public function edit($id)
    {
        // check here do not edit the trip after 14 days of complition
        $date = date("Y-m-d");
        $data = Trip::where('id', $id)->first();
        // dd($data);
        // if(strtotime($date) > strtotime($data->end_date)){
        //     if(getDaysDiff($date, $data->end_date) > setting('trip_edit_limit_days')){
        //         return redirect()->back();
        //     }
        // }
        $relationManagers= User::all();
        $stationarys = Stationary::all();
        $merchandises = Merchandise::all();
        return view('admin.trip.edit', compact('data', 'stationarys', 'merchandises','relationManagers'));
    }

    public function update(Request $request)
    {
       
        $request->validate([
            'trip_type' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'price' => ['required'],
            'region_type' => ['required'],
            'relationManager' => ['required'],

        ]);


        $data = Trip::find($request->id);
        $data->trip_type = $request->trip_type;
        $data->name = $request->name;
        $data->start_date = $request->start_date;
        $data->end_date = $request->end_date;
        $data->price = $request->price;
        $data->duration_nights = (getDaysDiff($request->end_date, $request->start_date) ?? 0);
        $data->continent = $request->continent;
        $data->landscape = $request->landscape;
        $data->style = $request->style;
        $data->activity = $request->activity;
        $data->tree_no = $request->tree_no;
        $data->donation_amt = $request->donation_amt;
        $data->overview = $request->overview;
        $data->relation_manager_id = json_encode($request->relationManager);
        $data->region_type = $request->region_type;
        $data->stationary_id = json_encode($request->stationary);
        $data->merchandise_id = json_encode($request->merchandise);

        $data->status = $request->status;
        $data->added_by = Auth::user()->id;

        if($request->hasfile('image')){
            @unlink('storage/app/'.$data->image);
            $data->image = $request->file('image')->store('admin/trip');
        }

        $action = Auth::user()->name ." has edited ";
        foreach($data->getDirty() as $field=>$value){
            $action .= $field." from ". $data->getOriginal($field) . " to ". $value . " ";
        }

        $data->save();

        // handle newly uploaded gallery images (store on public)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('admin/trip/gallery');
                    TripImage::create([
                        'trip_id' => $data->id,
                        'image' => $path,
                    ]);
                }
            }
        }

        // Activity Tracker
        $activity = new ActivityTracker();
        $activity->admin_id = Auth::user()->id;
        $activity->action = $action;
        $activity->page = "trip";
        $activity->page_data_id = $data->id;
        $activity->save();
        // Activity Tracker

        return redirect(route('trip.index'))->with('success', 'Updated Successfully !!');
    }

    public function deleteImage(Request $request, $id)
    {
        try {
            $img = TripImage::findOrFail($id);

            if ($img->image) {
                // delete from storage/app/<path>
                $file = storage_path('app/' . $img->image);
                if (file_exists($file)) {
                    @unlink($file);
                }
            }

            $img->delete();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back()->with('success', 'Image deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete trip image: ' . $e->getMessage());
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete image'], 500);
            }
            return redirect()->back()->with('error', 'Failed to delete image.');
        }
    }

    public function changeStatus(Request $request){
        $data = Trip::find($request->id);
        if($data->status == "Sold Out"){
            $status = "Approved";
        }else{
            $status = "Sold Out";
        }

        $data->status = $status;


        $action = Auth::user()->name ." has edited ";
        foreach($data->getDirty() as $field=>$value){
            $action .= $field." from ". $data->getOriginal($field) . " to ". $value . " ";
        }

        $data->save();

        // Activity Tracker
        $activity = new ActivityTracker();
        $activity->admin_id = Auth::user()->id;
        $activity->action = $action;
        $activity->page = "trip";
        $activity->page_data_id = $data->id;
        $activity->save();
        // Activity Tracker

        return 1;
    }

    public function cancelTrip(Request $request){
        $check = TripBooking::where('trip_id', $request->trip_id)->first();
        if(!$check){
            $data = Trip::find($request->trip_id);
            $data->status = 'Cancelled';
            $data->reason = $request->cancelation_reason;

            $action = Auth::user()->name ." has edited ";
            foreach($data->getDirty() as $field=>$value){
                $action .= $field." from ". $data->getOriginal($field) . " to ". $value . " ";
            }

            $data->save();

            // Activity Tracker
            $activity = new ActivityTracker();
            $activity->admin_id = Auth::user()->id;
            $activity->action = $action;
            $activity->page = "trip";
            $activity->page_data_id = $data->id;
            $activity->save();
            // Activity Tracker

            $travelers =  json_decode($check->customer_id);
            foreach($travelers as $traveler){
                $customer = getCustomerById($traveler);
                $mailData = [
                    'email' => $customer->email,
                    'name' => $customer->name,
                    'trip_name' => $check->trip->name ?? "Trip",
                    'date' => date("Y-m-d"),
                ];
                if(setting('mail_status') == 1){
                    event(new SendMailEvent("$customer->email", 'Your Trip has been Cancelled | Adventures Overland!', 'emails.cancel-booking', $mailData));
                }
            }

            return redirect()->back()->with('success', 'Trip Cancelled Successfully');
        }else{
            return redirect()->back()->with('warning', 'Kindly cancel all the bookings from this trip to cancel this trip.');
        }

    }

    public function destroy($id)
    {
        $data = Trip::find($id);
        @unlink('storage/app/'.$data->image);


        // Activity Tracker
        $activity = new ActivityTracker();
        $activity->admin_id = Auth::user()->id;
        $activity->action = Auth::user()->name ." has deleted ". $data->name ." Trip";
        $activity->page = "trip";
        $activity->page_data_id = $id;
        $activity->save();
        // Activity Tracker

        $data->delete();

        return redirect(route('trip.index'))->with('success', 'Deleted Successfully!!');
    }

    public function view($id)
    {
        $ess = VendorCategory::all();
        $vservices = VendorService::all();
        $exp_total = Expense::where('trip_id', $id)->sum('total_amount');
        $exp_paid = Expense::where('trip_id', $id)->sum('paid_amount');
        $data = Trip::find($id);
        $tripInfo = TripBooking::where('trip_id', $id)->where('form_submited', 1)->where('trip_status', '!=', 'Cancelled')->get();
        // dd($tripInfo);
        $roomTypeSum = 0;
        $roomTypeAmtSum = 0;
        foreach($tripInfo as $ri){
            if($ri->room_info){
                foreach(json_decode($ri->room_info) as $roomData){
                    $roomTypeSum += $roomData->room_type;
                    $roomTypeAmtSum += $roomData->room_type_amt;
                }
            }
        }

        $vehicleSeat = 0;
        $vehicleSeatAmt = 0;
        foreach($tripInfo as $ri){
            if($ri->vehical_seat){
                $vehicleSeat += $ri->vehical_seat;
            }
            if($ri->vehical_seat_amt){
                $vehicleSeatAmt += $ri->vehical_seat_amt;
            }
        }
        // $payableTripAmt = TripBooking::where('trip_status', '!=', 'Draft')->where('trip_id', $id)->sum('payable_amt');
        $payableTripAmt = $data->price * getCustomerCountByTripId($id);

        $customerIds = $tripInfo->flatMap(function ($ri) {
            return json_decode($ri->customer_id, true) ?: [];
        })->unique()->values()->toArray();

        $customerNames = Customer::whereIn('id', $customerIds)->get()->mapWithKeys(function ($c) {
            return [
                $c->id => trim($c->first_name . ' ' . $c->last_name)
            ];
        });

        // customer_id => trip_booking_id
        $customerBookingMap = [];
        foreach ($tripInfo as $ri) {
            $cids = json_decode($ri->customer_id, true) ?: [];
            foreach ($cids as $cid) {
                // e.g. 1184 => 1620, 1194 => 1620, 1196 => 1624
                $customerBookingMap[$cid] = $ri->id;
            }
        }

        return view('admin.trip.view', compact('data', 'ess', 'exp_total', 'exp_paid', 'payableTripAmt', 'vservices', 'roomTypeSum', 'roomTypeAmtSum', 'vehicleSeat', 'vehicleSeatAmt', 'customerIds', 'customerNames', 'customerBookingMap'));
    }

    // Add new extra Serviece store function 02.12.2025
    public function storeExtraService(Request $request)
    {
        $tripId     = $request->trip_id;
        $travelers  = $request->input('extra_traveler', []);
        $serviceIds = $request->input('extra_services', []);
        $amounts    = $request->input('extra_amount', []);
        $markups    = $request->input('extra_markup', []);
        $taxes      = $request->input('extra_tax', []);
        $comments   = $request->input('extra_comment', []);
        $bookingIds = $request->input('trip_booking_id', []);

        $serviceTitleMap = VendorService::whereIn('id', array_filter($serviceIds))
            ->pluck('title', 'id')
            ->toArray();

        foreach ($travelers as $i => $travelerId) 
        {
            if (empty($travelerId)) {
                continue;
            }
            $bookingId = $bookingIds[$i] ?? null;
            if (!$bookingId) {
                continue;
            }
            $serviceId = $serviceIds[$i] ?? null;
            if (empty($serviceId)) {
                continue;
            }
            $amount = $amounts[$i] ?? null;
            if ($amount === null || $amount === '') {
                continue;
            }

            $markup  = $markups[$i] ?? 0;
            $tax     = $taxes[$i] ?? 18;
            $comment = $comments[$i] ?? '';
            $booking = TripBooking::find($bookingId);
            if (!$booking) { continue; }
            $rawExtra = $booking->extra_services ?: '[]';
            $extra = json_decode($rawExtra, true);
            if (!is_array($extra)) { $extra = []; }
            $serviceTitle = $serviceTitleMap[$serviceId] ?? '';
            $entry = [
                'traveler' => (string) $travelerId,
                'services' => $serviceTitle ?: (string) $serviceId,
                'amount'   => (string) $amount,
                'markup'   => (string) $markup,
                'tax'      => (string) $tax,
                'comment'  => (string) $comment,
            ];

            $foundIndex = null;
            foreach ($extra as $idx => $row) {
                if (
                    isset($row['traveler'], $row['services']) &&
                    (string) $row['traveler'] === (string) $travelerId &&
                    (string) $row['services'] === (string) $entry['services']
                ) {
                    $foundIndex = $idx;
                    break;
                }
            }

            if ($foundIndex !== null) {
                $extra[$foundIndex] = $entry;
            } else {
                $extra[] = $entry;
            }
            $booking->extra_services = json_encode($extra);
            $booking->save();
        }

        return redirect()
            ->route('trip.view', $tripId)
            ->with('success', 'Extra services updated successfully.');
    }

    public function activityPage($id)
    {
        $data = $id;
        return view('admin.trip.activity', compact('data'));
    }

    public function activity(Request $request)
    {
        if(checkAdminRole()){
            $data = ActivityTracker::where(['page'=> 'trip', 'page_data_id'=>$request->id])->orderBy('id', 'desc')->get();
        }else{
            $data = ActivityTracker::where(['page'=> 'trip', 'page_data_id'=>$request->id])->where('admin_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        }

        $data->map(function ($item, $index) {
            $item->created = date("M d, Y H:i:s", strtotime($item->created_at));
            $item->name = $item->admin->name;
        });
        return DataTables::of($data)->make(true);
    }


    // view page data
    public function travelers(Request $request){
        $trip_id = $request->trip_id;
        $data = TripBooking::where('form_submited', 1)->where('trip_id', $trip_id)->where('trip_status', '!=', 'Cancelled')->orderBy('id', 'desc')->get();

        $data->map(function ($item, $index) {
            $paidAmount = 0;
            $paidAmount += $item->payment_amt ?? 0;
            if($item->part_payment_list){
                $pps = json_decode($item->part_payment_list);
                foreach($pps as $cost){
                    $paidAmount += $cost->amount ?? 0;
                }
            }

            $item->created = date("d M, Y", strtotime($item->created_at));
            $item->admin_id = $item->admin->name ?? null;
            $customers = json_decode($item->customer_id);
            $customerData = [];
            $customerList = '';
            foreach($customers as $key=>$c_id){
                $c_name = getCustomerById($c_id)->name ?? null;
                $c_email = getCustomerById($c_id)->email ?? null;
                $c_phone = getCustomerById($c_id)->phone ?? null;

                $customerList .= $c_name;
                if(count($customers) != ($key+1)){
                    $customerList .= ', ';
                }

                array_push($customerData, ['name'=>$c_name, 'email'=>$c_email,'phone'=>$c_phone, 'c_id'=>$c_id]);
            }
            $item->pax = count($customers);
            $item->customers = $customerData;
            $item->members = $customerList;
            $item->total_amount = $item->payable_amt;
            // $item->paid = $item->payment_amt;
            $item->paid = $paidAmount;

            $pending_amount = $item->payable_amt - $paidAmount;
            $item->pending_amount = $pending_amount;
        });
        // dd($data);
        return DataTables::of($data)->make(true);
    }

    public function rooms(Request $request){
        $trip_id = $request->trip_id;
        $data = TripBooking::where('form_submited', 1)->where('trip_id', $trip_id)->where('trip_status', '!=', 'Cancelled')->orderBy('id', 'desc')->get();

        $data->map(function ($item, $index) {
            $item->admin_id = $item->admin->name ?? null;
            $customers = json_decode($item->customer_id);
            $customerData = [];
            foreach($customers as $c_id){
                $c_name = getCustomerById($c_id)->name ?? null;
                $c_email = getCustomerById($c_id)->email ?? null;
                $c_phone = getCustomerById($c_id)->phone ?? null;
                array_push($customerData, ['name'=>$c_name, 'email'=>$c_email,'phone'=>$c_phone]);
            }
            $room_type = "";
            if ($item->room_info){
                foreach (json_decode($item->room_info) as $rinfo){
                    $room_type .= $rinfo->room_type . ', ';
                }
            }
            $room_cat = "";
            if ($item->room_info){
                foreach (json_decode($item->room_info) as $rinfo){
                    $room_cat .= $rinfo->room_cat . ', ';
                }
            }
            $room_type_amt = "";
            if ($item->room_info){
                foreach (json_decode($item->room_info) as $rinfo){
                    $room_type_amt .= $rinfo->room_type_amt . ', ';
                }
            }
            $item->room_type = $room_type;
            $item->room_cat = $room_cat;
            $item->room_type_amt = $room_type_amt;
            $item->customers = $customerData;
        });
        // dd($data);
        return DataTables::of($data)->make(true);
    }

    public function vehicle(Request $request){
        $trip_id = $request->trip_id;
        $data = TripBooking::where('form_submited', 1)->where('trip_id', $trip_id)->where('trip_status', '!=', 'Cancelled')->orderBy('id', 'desc')->get();

        $data->map(function ($item, $index) {
            $item->admin_id = $item->admin->name ?? null;
            $customers = json_decode($item->customer_id);
            $customerData = [];
            foreach($customers as $c_id){
                $c_name = getCustomerById($c_id)->name ?? null;
                $c_email = getCustomerById($c_id)->email ?? null;
                $c_phone = getCustomerById($c_id)->phone ?? null;
                array_push($customerData, ['name'=>$c_name, 'email'=>$c_email,'phone'=>$c_phone]);
            }
            $item->customers = $customerData;
        });
        // dd($data);
        return DataTables::of($data)->make(true);
    }

    public function extra(Request $request){
        $trip_id = $request->trip_id;
        $data = TripBooking::where('form_submited', 1)->where('trip_id', $trip_id)->whereNotNull('extra_services')->where('extra_services', '!=', '[]')->where('trip_status', '!=', 'Cancelled')->orderBy('id', 'desc')->get();

        $rows = collect();
        foreach ($data as $booking) {
            $extras = json_decode($booking->extra_services) ?: [];

            foreach ($extras as $es) {
                $rows->push([
                    'customer' => optional(getCustomerById($es->traveler))->name,
                    'service'  => $es->services ?? null,
                    'amount'   => ($es->amount ?? 0)
                                + ($es->markup ?? 0)
                                + ((($es->markup ?? 0) * ($es->tax ?? 0)) / 100),
                ]);
            }
        }

        return DataTables::of($rows)->make(true);
    }

    public function vendor(Request $request){
        $trip_id = $request->trip_id;
        $data = Expense::where('trip_id', $trip_id)->orderBy('id', 'desc')->get();
        $data->map(function ($item, $index) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->vendor_name = $item->vendor->company ?? " ";
            $item->service_name = $item->service->title ?? " ";
            $item->vendorServiceName = $item->vendorService->title ?? " ";
            if($item->docx){
                $item->docx = url('storage/app/'.$item->docx);
            }else{
                $item->docx = null;
            }

            $item->editable = checkTripEditable($item->trip_id);
        });
        return DataTables::of($data)->make(true);
    }

    // public function merchandise(Request $request){
    //     $trip_id = $request->trip_id;
    //     $data = TripBooking::where('trip_id', $trip_id)->whereNotIn('trip_status', ['Cancelled', 'Draft'])->orderBy('id', 'desc')->get();

    //     $data->map(function ($item, $index) {
    //         $customers = json_decode($item->customer_id);
    //         $customerData = [];
    //         // dd($customers);
    //         foreach($customers as $c_id){
    //             $item->created = date("d M, Y", strtotime($item->created_at));
    //             $item->customers = getCustomerById($c_id)->name ?? null;
    //             $item->address = getCustomerById($c_id)->address ?? null;
    //             $item->address .= getCustomerById($c_id)->city ?? null;
    //             $item->address .= getCustomerById($c_id)->state ?? null;
    //             $item->address .= getCustomerById($c_id)->country ?? null;
    //             $item->address .= getCustomerById($c_id)->pincode ?? null;

    //             $item->trip_name = $item->trip->name;
    //             $merchandiseData = "";
    //             if($item->trip->merchandise_id){
    //                 $stationaries = json_decode($item->trip->merchandise_id) ?? [""];
    //                 foreach($stationaries as $merchandise_id){
    //                     $st = getMerchandiseById($merchandise_id)->title ?? null;
    //                     $merchandiseData .= $st. ", ";
    //                 }
    //             }

    //             $item->size = getCustomerById($c_id)->t_size ?? null;
    //             $item->qty = 1;
    //             $item->gender = getCustomerById($c_id)->gender ?? null;
    //             $item->merchandise_name = $merchandiseData;
    //         }
    //     });
    //     // dd($data);
    //     return DataTables::of($data)->make(true);
    // }

    public function merchandise(Request $request)
    {
        $trip_id = $request->trip_id;
        $data = TripBooking::where('trip_id', $trip_id)
            ->whereNotIn('trip_status', ['Cancelled', 'Draft'])
            ->orderBy('id', 'desc')
            ->get();

        $data->transform(function($item){
            $item->created = date("d M, Y", strtotime($item->created_at));
            $item->trip_name = $item->trip->name ?? null;
            $item->merchandise_name = '';
            if (!empty($item->trip->merchandise_id)) {
                $ids = json_decode($item->trip->merchandise_id) ?: [];
                $titles = [];
                foreach ($ids as $mid) {
                    $m = getMerchandiseById($mid);
                    if (!empty($m->title)) $titles[] = $m->title;
                }
                $item->merchandise_name = implode(', ', $titles);
            }

            $customers = json_decode($item->customer_id) ?: [];
            foreach ($customers as $c_id) {
                $cust = getCustomerById($c_id);
                if (!$cust) continue;

                $item->customers = $cust->name ?? null;
                $parts = [
                    isset($cust->address) ? trim($cust->address) : '',
                    isset($cust->city)    ? trim($cust->city)    : '',
                    isset($cust->state)   ? trim($cust->state)   : '',
                    isset($cust->country) ? trim($cust->country) : '',
                    isset($cust->pincode) ? trim($cust->pincode) : '',
                ];
                $parts = array_values(array_filter($parts, fn($v) => $v !== ''));
                $item->address = $parts ? implode(', ', $parts) : null;

                $item->size = $cust->t_size ?? null;
                $item->qty = 1;
                $item->gender = $cust->gender ?? null;
            }

            return $item;
        });

        return DataTables::of($data)->make(true);
    }

    public function stationary(Request $request){
        $trip_id = $request->trip_id;
        $data = TripBooking::where('form_submited', 1)->where('trip_id', $trip_id)->where('trip_status', '!=', 'Cancelled')->orderBy('id', 'desc')->get();

        $data->map(function ($item, $index) {
            $item->created = date("d M, Y", strtotime($item->created_at));
            $customers = json_decode($item->customer_id);
            foreach($customers as $c_id){
                $c_name = getCustomerById($c_id)->name ?? null;
                $item->customers = $c_name;
                $item->trip_name = $item->trip->name;
                $stationaryData = "";
                if($item->trip->stationary_id){
                    $stationaries = json_decode($item->trip->stationary_id) ?? [""];
                    foreach($stationaries as $stationary_id){
                        $st = getStationaryById($stationary_id)->title ?? null;
                        $stationaryData .= $st. ", ";
                    }
                }

                $item->qty = 1;
                $item->stationary_name = $stationaryData;
            }

        });
        // dd($data);
        return DataTables::of($data)->make(true);
    }

    public function agents(Request $request){
        $trip_id = $request->trip_id;
        $data = TripBooking::where('trip_id', $trip_id)->where('lead_source', 'Agent')->where('trip_status', '!=', 'Cancelled')->orderBy('id', 'desc')->get();
        $data->map(function ($item, $index) {
            $customers = json_decode($item->customer_id);
            $customerData = [];
            foreach($customers as $c_id){
                $c_name = getCustomerById($c_id)->name ?? null;
                $c_email = getCustomerById($c_id)->email ?? null;
                $c_phone = getCustomerById($c_id)->phone ?? null;
                array_push($customerData, ['name'=>$c_name, 'email'=>$c_email,'phone'=>$c_phone]);
            }
            $item->customers = $customerData;
        });
        return DataTables::of($data)->make(true);
    }

    public function expense(Request $request){
        $request->validate([
            'extra_service_id' => ['required'],
            'vendor_id' => ['required'],
            'vendor_service_id' => ['required'],
            'total_amount' => ['required'],
            'trip_id' => ['required'],
        ]);
        if(isset($request->expense_row_id)){
            $data = Expense::find($request->expense_row_id);
        }else{
            $data = new Expense();
        }
        $data->trip_id = $request->trip_id ?? null;
        $data->extra_service_id = $request->extra_service_id ?? null;
        $data->vendor_service_id = $request->vendor_service_id ?? null;
        $data->vendor_id = $request->vendor_id ?? null;
        $data->total_amount = $request->total_amount ?? null;
        $data->comment = $request->comment ?? null;
        $data->admin_id = Auth::user()->id ?? null;
        if($request->hasfile('docx')){
            $data->docx = $request->file('docx')->store('admin/expense') ?? null;
        }

        @$data->save();

        if(!isset($request->expense_row_id)){
            if(isset($request->expense_paid_amount) && $request->expense_paid_amount[0] != null && ($request->payment_status_type == "Fully Paid" || $request->payment_status_type == "Partial Paid")){
                $expense_paid_payment_mode = $request->expense_paid_payment_mode;
                $expense_paid_date = $request->expense_paid_date;
                $expense_paid_cmt = $request->expense_paid_cmt;

                foreach($request->expense_paid_amount as $key=>$val){

                    if(isset($val) && $val > 0){
                        $expHistory = new ExpenseHistory();
                        $expHistory->expense_id = $data->id;
                        $expHistory->amount = $val;
                        $expHistory->payment_mode = $expense_paid_payment_mode[$key];
                        $expHistory->date = $expense_paid_date[$key];
                        $expHistory->comment = $expense_paid_cmt[$key] ?? null;
                        $expHistory->admin_id = Auth::user()->id;
                        $expHistory->save();

                        $exp = Expense::find($data->id);
                        $exp->paid_amount = $exp->paid_amount + $val;
                        $exp->save();
                    }
                }
            }
        }

        /**
         * -------------------------
         * SEND MAIL (Add / Edit)
         * -------------------------
         * Build the $mailData array and $mailData['attachment'] as an array of absolute paths.
        */
        try {
            $isNew = !isset($request->expense_row_id);
            $recipients = array_filter([ setting('account_mail'), setting('operation_mail') ]);

            $tripName = 'Deleted';
            if (!empty($data->trip_id)) {
                $trip = Trip::find($data->trip_id);
                if ($trip) {
                    $tripName = $trip->name ?? ($trip->title ?? 'Deleted');
                }
            }

            $vendorName = 'N/A';
            if (!empty($data->vendor_id)) {
                $vendor = Vendor::find($data->vendor_id);
                if ($vendor) {
                    $first = $vendor->first_name ?? '';
                    $last = $vendor->last_name ?? '';
                    $full = trim($first . ' ' . $last);
                    $vendorName = $full !== '' ? $full : ($vendor->name ?? 'N/A');
                }
            }

            $vendorServiceName = 'N/A';
            if (!empty($data->vendor_service_id)) {
                $vs = VendorService::find($data->vendor_service_id);
                if ($vs) {
                    $vendorServiceName = $vs->title ?? $vs->name ?? 'N/A';
                }
            }

            $mailData = [
                'action' => $isNew ? 'Expense Added' : 'Expense Edited',
                'trip_name' => $tripName,
                'expense_id' => $data->id,
                'vendor_name' => $vendorName,
                'vendor_service_name' => $vendorServiceName,
                'total_amount' => $data->total_amount,
                'comment' => $data->comment,
                'today' => date('Y-m-d'),
            ];

            $attachments = [];
            if (!empty($data->docx)) {
                $storedPath = $data->docx;
                try {
                    $abs = Storage::path($storedPath);
                } catch (\Throwable $e) {
                    $abs = storage_path('app/' . ltrim($storedPath, '/'));
                }

                if (file_exists($abs)) {
                    $attachments[] = $abs;
                } else {
                    $disk = config('filesystems.default');
                    try {
                        if ($disk === 's3' || Storage::disk('s3')->exists($storedPath)) {
                            $tmpFile = sys_get_temp_dir() . '/' . basename($storedPath);
                            $content = Storage::disk('s3')->get($storedPath);
                            file_put_contents($tmpFile, $content);
                            if (file_exists($tmpFile)) $attachments[] = $tmpFile;
                        } else {
                            if (strpos($storedPath, '/storage/') !== false) {
                                $relative = substr($storedPath, strpos($storedPath, '/storage/') + strlen('/storage/'));
                                $possible = storage_path('app/public/' . ltrim($relative, '/'));
                                if (file_exists($possible)) $attachments[] = $possible;
                            }
                        }
                    } catch (\Throwable $e) {
                        \Log::error('Expense attachment error: '.$e->getMessage());
                    }
                }
            }

            if (!empty($attachments)) {
                $mailData['attachment'] = $attachments;
            }

            $subject = ($isNew ? 'Expense Added - ' : 'Expense Edited - ') . ($mailData['trip_name'] ?? 'Trip');
            // dd($mailData,$recipients);
            foreach ($recipients as $to) {
                event(new SendExpenseMailEvent($to, $subject, 'emails.expense-notification', $mailData));
            }

        } catch (\Throwable $e) {
            \Log::error("Expense mail error: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Expense saved Successfully !!');
    }

    public function vendorByExp(Request $request){
        $id = $request->id;
        $data = Vendor::whereJsonContains('vendor_type', "$id")->get();
        $vendor = "<option value=''>Select Vendor</option>";
        foreach($data as $item){
            $vendor .= "<option value='".$item->id."'>".$item->company."</option>";
        }
        return $vendor;
    }

    public function getServiceByVendor(Request $request){
        $id = $request->id;
        $data = Vendor::find($id);
        $vendor = "<option value=''>Select Service</option>";
        if($data){
            foreach(json_decode($data->service_id) as $item){
                $vendor .= "<option value='".$item."'>".getVendorServiceById($item)->title."</option>";
            }
        }
        return $vendor;
    }

    public function viewExpense(){
        return view('admin.trip.view-expense');
    }

    public function getExpense(Request $request){
        $data = ExpenseHistory::where('expense_id', $request->expense_id)->orderBy('id', 'Desc')->get();
        $data->map(function ($item, $index) {
            $item->created = date("M d, Y H:i A", strtotime($item->created_at));
            $item->payment_date = date("M d, Y", strtotime($item->date));
            $item->payment_date_real = date("Y-m-d", strtotime($item->date));
            $item->vendor_name = $item->expense->vendor->company;
            $item->added_by = $item->admin->name;
            $item->payment_mode = $item->payment_mode;
            $item->editable = checkTripEditable($item->expense->trip_id);
        });
        // dd($data);
        return DataTables::of($data)->make(true);
    }

    public function makeExpPayment(Request $request){
        $request->validate([
            'amount' => ['required'],
        ]);


        $data = new ExpenseHistory();
        $data->expense_id = $request->expense_id;
        $data->amount = $request->amount;
        $data->payment_mode = $request->payment_mode;
        $data->date = $request->date;
        $data->comment = $request->comment ?? null;
        $data->admin_id = Auth::user()->id;
        $data->save();


        $exp = Expense::find($request->expense_id);
        $exp->paid_amount = $exp->paid_amount + $request->amount;
        $exp->save();

        return redirect()->back()->with('success', 'Payment Added Successfully !!');
    }

    // public function deleteExpense($id){

    //     $exp = Expense::find($id);
    //     @unlink('storage/app/'.$exp->docx);
    //     $expHis = ExpenseHistory::where('expense_id', $id)->delete();
    //     $exp->delete();

    //     return redirect()->back()->with('success', 'Deleted Successfully !!');
    // }
    public function deleteExpense($id)
    {
        $exp = Expense::findOrFail($id);
        $tripName = 'Deleted';
        if (!empty($exp->trip_id)) {
            $trip = Trip::find($exp->trip_id);
            $tripName = $trip ? ($trip->name ?? ($trip->title ?? 'Deleted')) : 'Deleted';
        }
        $vendorName = 'N/A';
        if (!empty($exp->vendor_id)) {
            $vendor = Vendor::find($exp->vendor_id);
            if ($vendor) {
                $vendorName = trim(($vendor->first_name ?? '') . ' ' . ($vendor->last_name ?? '')) ?: ($vendor->name ?? 'N/A');
            }
        }
        $vendorServiceName = 'N/A';
        if (!empty($exp->vendor_service_id)) {
            $vs = VendorService::find($exp->vendor_service_id);
            if ($vs) $vendorServiceName = $vs->title ?? $vs->name ?? 'N/A';
        }
        $attachments = [];
        $tempFilesCreated = [];
        if (!empty($exp->docx)) {
            $storedPath = $exp->docx;

            $abs = null;
            try {
                $abs = Storage::path($storedPath);
            } catch (\Throwable $e) {
                $abs = storage_path('app/' . ltrim($storedPath, '/'));
            }

            if ($abs && file_exists($abs)) {
                $tmpFile = sys_get_temp_dir() . '/' . uniqid('expense_attach_', true) . '_' . basename($storedPath);
                if (@copy($abs, $tmpFile)) {
                    $attachments[] = $tmpFile;
                    $tempFilesCreated[] = $tmpFile;
                    \Log::info("Expense delete: created temp copy for attachment: {$tmpFile}");
                } else {
                    \Log::warning("Expense delete: failed to copy local file {$abs} to temp {$tmpFile}");
                }
            } else {
                try {
                    $disk = config('filesystems.default');
                    if ($disk === 's3' || Storage::disk('s3')->exists($storedPath)) {
                        $tmpFile = sys_get_temp_dir() . '/' . uniqid('expense_attach_', true) . '_' . basename($storedPath);
                        $content = Storage::disk('s3')->get($storedPath);
                        if (file_put_contents($tmpFile, $content) !== false) {
                            $attachments[] = $tmpFile;
                            $tempFilesCreated[] = $tmpFile;
                            \Log::info("Expense delete: downloaded S3 file to temp: {$tmpFile}");
                        } else {
                            \Log::warning("Expense delete: failed to write S3 file to temp: {$tmpFile}");
                        }
                    } else {
                        if (strpos($storedPath, '/storage/') !== false) {
                            $relative = substr($storedPath, strpos($storedPath, '/storage/') + strlen('/storage/'));
                            $possible = storage_path('app/public/' . ltrim($relative, '/'));
                            if (file_exists($possible)) {
                                $tmpFile = sys_get_temp_dir() . '/' . uniqid('expense_attach_', true) . '_' . basename($possible);
                                if (@copy($possible, $tmpFile)) {
                                    $attachments[] = $tmpFile;
                                    $tempFilesCreated[] = $tmpFile;
                                    \Log::info("Expense delete: created temp copy from public storage: {$tmpFile}");
                                }
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    \Log::error('Expense delete: error preparing remote attachment: ' . $e->getMessage());
                }
            }
        }

        ExpenseHistory::where('expense_id', $id)->delete();
        if (!empty($exp->docx)) {
            try {
                if (Storage::exists($exp->docx)) {
                    Storage::delete($exp->docx);
                    \Log::info("Expense delete: deleted original storage file: {$exp->docx}");
                } else {
                    \Log::info("Expense delete: Storage::exists returned false for: {$exp->docx}");
                }
            } catch (\Throwable $e) {
                \Log::warning('Expense delete: Could not delete stored docx via Storage::delete(): ' . $e->getMessage());
            }

            try {
                if (!empty($abs) && file_exists($abs)) {
                    @unlink($abs);
                    \Log::info("Expense delete: unlinked absolute file: {$abs}");
                }
            } catch (\Throwable $e) {
                \Log::warning('Expense delete: Could not unlink absolute file: ' . $e->getMessage());
            }
        }

        $exp->delete();
        $mailData = [
            'action' => 'Expense Deleted',
            'trip_name' => $tripName,
            'expense_id' => $id,
            'vendor_name' => $vendorName,
            'vendor_service_name' => $vendorServiceName,
            'total_amount' => $exp->total_amount,
            'comment' => $exp->comment,
            'today' => date('Y-m-d'),
        ];
        if (!empty($attachments)) {
            $mailData['attachment'] = $attachments;
        }

        $recipients = array_filter([ setting('account_mail'), setting('operation_mail') ]);
        foreach ($recipients as $to) {
            event(new \App\Events\SendExpenseMailEvent($to, 'Expense Deleted - ' . $tripName, 'emails.expense-notification', $mailData));
        }

        $queueConn = env('QUEUE_CONNECTION', 'sync');
        if ($queueConn === 'sync' && !empty($tempFilesCreated)) {
            foreach ($tempFilesCreated as $tmp) {
                if (file_exists($tmp)) {
                    @unlink($tmp);
                    \Log::info("Expense delete: cleaned up temp file: {$tmp}");
                }
            }
        } else {
            if (!empty($tempFilesCreated)) {
                \Log::info('Expense delete: temp files left for queued processing: ' . implode(', ', $tempFilesCreated));
            }
        }

        return redirect()->back()->with('success', 'Deleted Successfully !!');
    }

    public function getCustomerByBookingId(Request $request){

        $datas = TripBooking::where('trip_id', $request->trip_id)->get();
        $res = [];
        foreach($datas as $data){
            if($data && $data->customer_id){
                $customers = json_decode($data->customer_id);
                $booking_id = $data->id;

                foreach($customers as $customer){
                    $check = Room::whereJsonContains('travelers', "$customer")->where('trip_id', $request->trip_id)->first();
                    if(!$check){
                        $cData = [
                            'id' => $customer,
                            'name' => getCustomerById($customer)->name,
                            'booking_id' => $booking_id,
                        ];

                        array_push($res, $cData);
                    }
                }

            }
        }
        return json_encode($res);
    }

    public function allotRoom(Request $request){
        $request->validate([
            'trip_id' => ['required'],
            'customer_id'         => ['required', 'array'],
            'customer_booking_map'=> ['required'],
        ]);
        
        $map = json_decode($request->customer_booking_map, true);

        // Method 1: For single booking_id have multiple customers
        $hotelNames  = $request->hotel_name  ?? []; // [customer_id => 'Hotel A']
        $hotelRooms  = $request->hotel_room  ?? []; // [customer_id => '101']
        $hotelPlaces = $request->hotel_place ?? []; // [customer_id => 'Jaipur']
        $bookingGroups = [];
        foreach ($map as $customerId => $bookingId) {
            if (!$bookingId) {
                continue;
            }
            $bookingGroups[$bookingId][] = $customerId;
        }
        foreach ($bookingGroups as $bookingId => $customers) {
            $firstCustomerId = $customers[0];

            $room = new Room();
            $room->trip_id    = $request->trip_id;
            $room->booking_id = $bookingId;                 
            $room->travelers  = json_encode($customers);     
            $room->comment    = $request->comment ?? null;

            $room->hotel_name  = $hotelNames[$firstCustomerId]  ?? null;
            $room->hotel_room  = $hotelRooms[$firstCustomerId]  ?? null;
            $room->hotel_place = $hotelPlaces[$firstCustomerId] ?? null;

            $room->admin_id   = Auth::user()->id;
            $room->save();
        }

        $customerNames = "";
        foreach($request->customer_id as $cust){
            $customerNames .= getCustomerById($cust)->name .", ";
        }

        // Method 2: For each customer have separate booking_id
        // $customerNames = '';
        // $hotelNames  = $request->hotel_name ?? []; 
        // $hotelRooms  = $request->hotel_room ?? []; 
        // $hotelPlaces = $request->hotel_place ?? [];
        
        // foreach ($request->customer_id as $custId) {
        //     $room = new Room();
        //     $room->trip_id    = $request->trip_id;
        //     $room->booking_id = $map[$custId] ?? null;
        //     $room->travelers  = json_encode([$custId]);
        //     $room->comment    = $request->comment ?? null;
        //     $room->hotel_name = $hotelNames[$custId]  ?? null;
        //     $room->hotel_room = $hotelRooms[$custId]  ?? null;
        //     $room->hotel_place= $hotelPlaces[$custId] ?? null;
        //     $room->admin_id   = Auth::user()->id;
        //     $room->save();

        //     $customerNames .= getCustomerById($custId)->name . ', ';
        // }
        // End code section for different methods

        // Activity Tracker
        $activity = new ActivityTracker();
        $activity->admin_id = Auth::user()->id;
        $activity->action = Auth::user()->name ." has alloted room for ". "$customerNames" ;
        $activity->page = "trip";
        $activity->page_data_id = $request->trip_id;
        $activity->save();
        // Activity Tracker

        return redirect()->back()->with('success', 'Room Alloted Successfully');
    }

    public function roomView(Request $request){
        return view('admin.trip.room-view');
    }

    public function roomGet(Request $request){
        $data = Room::where('booking_id', request()->booking_id)->orderBy('id', 'Desc')->get();
        $data->map(function ($item, $index) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->added_by = $item->admin->name;
            $travelers = json_decode($item->travelers);
            $tData = [];
            foreach($travelers as $traveler){
                $t = ['id'=>$traveler, 'name'=>getCustomerById($traveler)->name];
                array_push($tData, $t);
            }
            $item->customers = $tData;
        });
        // dd($data);
        return DataTables::of($data)->make(true);
    }

    public function roomDelete(Request $request){
        $data = Room::where('id', $request->id)->delete();
        return redirect()->back()->with('success', 'Room Deleted Successfuly !');
    }

    public function updateRoom(Request $request)
    {
        $request->validate([
            'room_id'    => 'required',
            'hotel_name' => 'required',
            'hotel_room' => 'required',
            'hotel_place'=> 'required'
        ]);

        $room = Room::findOrFail($request->room_id);

        $room->hotel_name  = $request->hotel_name;
        $room->hotel_room  = $request->hotel_room;
        $room->hotel_place = $request->hotel_place;
        $room->comment     = $request->comment;
        $room->save();

        return back()->with('success', 'Room Updated Successfully');
    }

    public function getCustomerByBookingIdForVehicle(Request $request){
        $data = TripBooking::find($request->id);
        if($data && $data->customer_id){
            $customers = json_decode($data->customer_id);

            $res = [];
            foreach($customers as $customer){
                $check = Vehicle::whereJsonContains('travelers', "$customer")->where('trip_id', $request->id)->first();
                if(!$check){
                    $cData = [
                        'id' => $customer,
                        'name' => getCustomerById($customer)->name,
                    ];

                    array_push($res, $cData);
                }
            }

            return json_encode($res);
        }
    }

    public function allotVehicle(Request $request){
        $request->validate([
            'trip_id' => ['required'],
            'booking_id' => ['required'],
            'customer_id' => ['required'],
        ]);


        $data = new Vehicle();
        $data->trip_id = $request->trip_id;
        $data->booking_id = $request->booking_id;
        $data->travelers = json_encode($request->customer_id);
        $data->comment = $request->comment ?? null;
        $data->vehicle_no = $request->vehicle_no ?? null;
        $data->admin_id = Auth::user()->id;
        $data->save();

        $customerNames = "";
        foreach($request->customer_id as $cust){
            $customerNames .= getCustomerById($cust)->name .", ";
        }
        // Activity Tracker
        $activity = new ActivityTracker();
        $activity->admin_id = Auth::user()->id;
        $activity->action = Auth::user()->name ." has alloted Vehicle for ". "$customerNames" ;
        $activity->page = "trip";
        $activity->page_data_id = $request->trip_id;
        $activity->save();
        // Activity Tracker

        return redirect()->back()->with('success', 'Room Alloted Successfully');
    }

    public function vehicleView(Request $request){
        return view('admin.trip.vehicle-view');
    }

    public function vehicleGet(Request $request){
        $data = Vehicle::where('booking_id', request()->booking_id)->orderBy('id', 'Desc')->get();
        $data->map(function ($item, $index) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->added_by = $item->admin->name ?? null;
            $travelers = json_decode($item->travelers);
            $tData = [];
            foreach($travelers as $traveler){
                $t = ['id'=>$traveler, 'name'=>getCustomerById($traveler)->name];
                array_push($tData, $t);
            }
            $item->customers = $tData;
        });
        // dd($data);
        return DataTables::of($data)->make(true);
    }

    public function vehicleDelete(Request $request){
        $data = Vehicle::where('id', $request->id)->delete();
        return redirect()->back()->with('success', 'Room Deleted Successfuly !');
    }

    public function exportRoom(Request $request){
        // Fetch data from the Customer model
        $rooms = Room::where('trip_id', $request->trip_id)->orderBy('id', 'desc')->get();
        // Prepare data for export
        $data = [];
        $data[] = ["Date", "Trip Name",	"Booking Id", "Travelers","Added By", "Comment"];

        foreach ($rooms as $item) {

            $travelerss = json_decode($item->travelers);
            $tData = "";
            foreach($travelerss as $traveler){
                $tData .= getCustomerById($traveler)->name.", ";
            }

            $data[] = [
                date("d M, y", strtotime($item->created_at)),
                $item->booking->trip->name,
                "#".$item->booking_id,
                $tData,
                $item->admin->name,
                $item->comment,
            ];
        }

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add data to the spreadsheet
        $sheet->fromArray($data, null, 'A1');

        // Set auto column size for all columns
        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Create a writer for XLSX format
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export-room-list.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet data to a file
        $writer->save('php://output');
    }

    public function exportVehicle(Request $request){
        // Fetch data from the Customer model
        $rooms = Vehicle::where('trip_id', $request->trip_id)->orderBy('id', 'desc')->get();
        // Prepare data for export
        $data = [];
        $data[] = ["Date", "Trip Name",	"Booking Id", "Travelers", "Vehicle","Added By", "Comment"];

        foreach ($rooms as $item) {

            $travelerss = json_decode($item->travelers);
            $tData = "";
            foreach($travelerss as $traveler){
                $tData .= getCustomerById($traveler)->name.", ";
            }

            $data[] = [
                date("d M, y", strtotime($item->created_at)),
                $item->booking->trip->name,
                "#".$item->booking_id,
                $tData,
                $item->vehicle_no,
                $item->admin->name,
                $item->comment,
            ];
        }

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add data to the spreadsheet
        $sheet->fromArray($data, null, 'A1');

        // Set auto column size for all columns
        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Create a writer for XLSX format
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export-convoy-list.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet data to a file
        $writer->save('php://output');
    }

    public function exportExpense(Request $request){
        // Fetch data from the Customer model
        $rooms = Expense::where('trip_id', $request->trip_id)->orderBy('id', 'desc')->get();
        // Prepare data for export
        $data = [];
        $data[] = ["Date", "Trip Name",	"Vendors", "Services", "Added By", "Comment", "Total Amount", "Paid Amount", "Remaining Amount"];

        foreach ($rooms as $item) {

            $data[] = [
                date("d M, y", strtotime($item->created_at)),
                $item->trip->name,
                $item->vendor->first_name ." ".$item->vendor->last_name,
                $item->service->title ?? Null,
                $item->admin->name,
                $item->comment,
                "₹".$item->total_amount,
                "₹".$item->paid_amount,
                "₹".($item->total_amount - $item->paid_amount),
            ];
        }

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add data to the spreadsheet
        $sheet->fromArray($data, null, 'A1');

        // Set auto column size for all columns
        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Create a writer for XLSX format
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export-expense-list.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet data to a file
        $writer->save('php://output');
    }

    public function exportMaster(Request $request)
    {
        // Fetch data from the Customer model
        $datas = TripBooking::where('trip_id', $request->trip_id)->where('trip_status', '!=', 'Draft')->get();

        // Prepare data for export
        $data = [];
        $data[] = ['Booked By', 'Booking For', 'Customers', 'Lead Source', 'Sub Lead Source', 'Expedition', 'Trip', 'Vehicle Type', 'Vehicle Seat', 'Vehicle Seat Amount', 'Vehicle Security Amount', 'Vehicle Amount Comment', 'Room Type', 'Room Type Amount', 'Room Category', 'Payment From', 'Company', 'TCS', 'Complete Payment By', 'Payment Type', 'Payment Amount', 'Payment Date', 'Trip Cost', 'Extra Services', 'Tax Required', 'Payable Amount', 'Trip Status', 'Invoice File', 'Invoice Status', 'Invoice Sent Date', 'Redeem Points', 'Cancellation Reason', 'Cancellation Amount', 'Cancellation Date'];

        foreach ($datas as $item) {
            $customers = json_decode($item->customer_id) ?? null;
            $cList = "";
            foreach($customers as $customer){
                $cList .= getCustomerById($customer)->name .", ";
            }
            // payment Type
            $paymentType = $item->payment_type. ", ";
            $paymentAmount = $item->payment_amt. ", ";
            $paymentDate = $item->payment_date. ", ";
            if($item->part_payment_list){
                foreach(json_decode($item->part_payment_list) as $pp){
                    $paymentType .= $pp->payment_type .", ";
                    $paymentAmount .= "₹".$pp->amount .", ";
                    $paymentDate .= $pp->date .", ";
                }
            }

            $tripCost = "";
            if($item->trip_cost){
                foreach(json_decode($item->trip_cost) as $tc){
                    $tripCost .= getCustomerById($tc->c_id)->name." : "."₹".$tc->cost .", ";
                }
            }

            $extraServices = "";
            if($item->extra_services){
                foreach(json_decode($item->extra_services) as $tc){
                    $extraServices .= getCustomerById($tc->traveler)->name." : ".$tc->services." : "." ₹".$tc->amount." : ". "₹".$tc->markup." : ".$tc->tax."%"." : ".$tc->comment .", ";
                }
            }

            $redeemPoints = "";
            if($item->redeem_points){
                foreach(json_decode($item->redeem_points) as $tc){
                    $redeemPoints .= getCustomerById($tc->c_id)->name." : ".$tc->points.", ";
                }
            }

            $data[] = [
                $item->admin->name ?? null,
                $item->booking_for,
                $cList,
                $item->lead_source,
                $item->sub_lead_source,
                $item->expedition,
                $item->trip->name ?? "Trip Deleted",
                $item->vehical_type,
                $item->vehical_seat,
                "₹".$item->vehical_seat_amt ?? 0,
                "₹".$item->vehical_security_amt ?? 0,
                $item->vehical_security_amt_cmt,
                $item->room_type,
                "₹".$item->room_type_amt ?? 0,
                $item->room_cat,
                $item->payment_from,
                $item->payment_from_cmpny,
                $item->payment_from_tax ."%",
                $item->payment_by_customer_id ? getCustomerById($item->payment_by_customer_id)->name : null,
                $paymentType,
                $paymentAmount,
                $paymentDate,
                $tripCost,
                $extraServices,
                ($item->tax_required == 1 ? "No" : "Yes"),
                $item->payable_amt,
                $item->trip_status,
                url('storage/app/'.$item->invoice_file),
                $item->invoice_status,
                $item->invoice_sent_date,
                $redeemPoints,
                $item->cancelation_reason,
                $item->cancelation_amount,
                $item->cancelation_date,

            ];
        }

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add data to the spreadsheet
        $sheet->fromArray($data, null, 'A1');

        // Set auto column size for all columns
        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
            $sheet->getColumnDimension("A".$columnID)->setAutoSize(true);
        }

        // Create a writer for XLSX format
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export-master-list.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet data to a file
        $writer->save('php://output');
    }

    // public function customerRegistrationData(Request $request)
    // {
       
    //     $trip_id = $request->trip_id;
    //     $TripName = Trip::where('id', $trip_id)->first();
    //     $TripName = $TripName ? $TripName->name : 'N/A';

        
    //     // dd($latestTrip->name);
    //     $mData = TripBooking::where('form_submited', 1)
    //         ->where('trip_id', $trip_id)
    //         ->whereNotIn('trip_status', ['Cancelled', 'Draft'])
    //         ->orderBy('id', 'desc')
    //         ->get();
    
    //     $customerIds = $mData->pluck('customer_id')->map(function ($id) {
    //         return json_decode($id, true);
    //     })->flatten()->unique()->toArray();

    //     if (empty($customerIds)) {
    //         return redirect()->back()->with('warning', 'No customers found for this trip.');
    //     }
    
    //     $customers = Customer::whereIn('id', $customerIds)->get();

    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();
    //     $sheet->setTitle('Customer Registration Data');
    
      
    //     $headers = [
    //         "id", "trip_name", "first_name", "last_name", "gender", "email", "telephone_code", "phone","profile","email_verified_at","address","city","country","state","pincode","dob","meal_preference","blood_group","profession","emg_contact","t_size","medical_condition","vaccination","tier","points","credit_note_wallet","referred_by","parent","relation","emg_name","something","have_road_trip","thrilling_exp","three_travel","three_place","passport_front","passport_back","pan_gst","gst_certificate","adhar_card","driving","letest_trip","created_at","is_password_changed","updated_at","deleted_at"
    //     ];
    
    //     $sheet->fromArray([$headers], null, 'A1');
    //     $sheet->getStyle('A1:' . Coordinate::stringFromColumnIndex(count($headers)) . '1')->getFont()->setBold(true);

    //     $row = 2;
    //     $baseUrl = str_replace('crm-admin', 'crm-user', url('storage/app/')) . '/';

    //     foreach ($customers as $customer) {
    //         $customer->profile = $customer->profile ? $baseUrl . $customer->profile : '';
    //         $customer->passport_front = $customer->passport_front ? $baseUrl . $customer->passport_front : '';
    //         $customer->passport_back = $customer->passport_back ? $baseUrl . $customer->passport_back : '';
    //         $customer->pan_gst = $customer->pan_gst ? $baseUrl . $customer->pan_gst : '';
    //         $customer->gst_certificate = $customer->gst_certificate ? $baseUrl . $customer->gst_certificate : '';
    //         $customer->adhar_card = $customer->adhar_card ? $baseUrl . $customer->adhar_card : '';
    //         $customer->driving = $customer->driving ? $baseUrl . $customer->driving : '';
         
    //         $latestTrip = Trip::where('id', $customer->letest_trip)->first();
    //         $latestTripName = $latestTrip ? $latestTrip->name : 'N/A';

    //         $sheet->fromArray([
    //             [
    //                 $customer->id,$TripName, $customer->first_name, $customer->last_name, $customer->gender, $customer->email, $customer->telephone_code, $customer->phone, $customer->profile,$customer->email_verified_at,
    //                 $customer->address,$customer->city,$customer->country,$customer->state,$customer->pincode,$customer->dob,$customer->meal_preference,$customer->blood_group,$customer->profession,$customer->emg_contact,
    //                 $customer->t_size,$customer->medical_condition,$customer->vaccination,$customer->tier,$customer->points,$customer->credit_note_wallet,$customer->referred_by,$customer->parent,$customer->relation,
    //                 $customer->emg_name,$customer->something,$customer->have_road_trip,$customer->thrilling_exp,$customer->three_travel,$customer->three_place,$customer->passport_front,$customer->passport_back,
    //                 $customer->pan_gst,$customer->gst_certificate,$customer->adhar_card,$customer->driving,$latestTripName,date("d M, Y", strtotime($customer->created_at)),$customer->is_password_changed,date("d M, Y", strtotime($customer->updated_at)),
    //                 date("d M, Y", strtotime($customer->deleted_at)),
    //             ]
    //         ], null, 'A' . $row);
    
    //         $row++;
    //     }
    
    //     $columnCount = count($headers);
    //     $lastColumn = Coordinate::stringFromColumnIndex($columnCount); 
    
    //     for ($col = 1; $col <= $columnCount; $col++) {
    //         $columnLetter = Coordinate::stringFromColumnIndex($col);
    //         $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
    //     }
    
    //     $writer = new Xlsx($spreadsheet);
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment; filename="customers_registration_data.xlsx"');
    //     header('Cache-Control: max-age=0');
    
    //     $writer->save('php://output');
    //     exit;
    // }

    public function customerRegistrationData(Request $request)
    {
        $trip_id = $request->trip_id;
        $trip = Trip::find($trip_id);
        $TripName = $trip ? $trip->name : 'N/A';

        $mData = TripBooking::where('form_submited', 1)
            ->where('trip_id', $trip_id)
            ->whereNotIn('trip_status', ['Cancelled', 'Draft'])
            ->orderBy('id', 'desc')
            ->get();

        if ($mData->isEmpty()) {
            return redirect()->back()->with('warning', 'No bookings found for this trip.');
        }

        $adminIds = $mData->pluck('admin_id')->unique()->toArray();
        $customerIds = $mData->pluck('customer_id')->map(fn($id) => json_decode($id, true))->flatten()->unique()->toArray();

        if (empty($customerIds)) {
            return redirect()->back()->with('warning', 'No customers found for this trip.');
        }

        $customers     = Customer::whereIn('id', $customerIds)->get()->keyBy('id');
        $bookedByUsers = User::whereIn('id', $adminIds)->pluck('name', 'id');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Customer Registration Data');

        $headers = [
            "Booked By","Booking Id","Booking For",
            "Customers","Lead Source","Sub Lead Source","Expedition","Trip",
            "Vehicle Type","Vehicle Seat","Vehicle Seat Amount","Vehicle Security Amount",
            "Vehicle Amount Comment","Room Type","Room Type Amount","Room Category",
            "Payment From","Company","TCS","Complete Payment By","Payment Type",
            "Payment Amount","Payment Date","Trip Cost","Extra Services",
            "Tax Required","Payable Amount","Trip Status","Invoice File",
            "Invoice Status","Invoice Sent Date","Redeem Points","Cancellation Reason",
            "Cancellation Amount","Cancellation Date",
            "gender","email","telephone_code","phone","profile","email_verified_at","address",
            "city","country","state","pincode","dob","meal_preference","blood_group","profession",
            "emg_contact","t_size","medical_condition","vaccination","tier","points",
            "credit_note_wallet","referred_by","parent","relation","emg_name","something",
            "have_road_trip","thrilling_exp","three_travel","three_place","passport_front",
            "passport_back","pan_gst","gst_certificate","adhar_card","driving","letest_trip",
            "created_at","is_password_changed","updated_at","deleted_at"
        ];

        $sheet->fromArray([$headers], null, 'A1');
        $sheet->getStyle('A1:' . Coordinate::stringFromColumnIndex(count($headers)) . '1')->getFont()->setBold(true);

        $row = 2;
        $baseUrl = str_replace('crm-admin', 'crm-user', url('storage/app/')) . '/';

        foreach ($mData as $booking) {
            $bookingCustomerIds = json_decode($booking->customer_id, true) ?: [];
            if (empty($bookingCustomerIds)) continue;

            $fullNames = [];
            $primaryCustomer = null;
            foreach ($bookingCustomerIds as $cid) {
                if (!isset($customers[$cid])) continue;
                $c = $customers[$cid];
                $fullNames[] = trim(($c->first_name ?? '') . ' ' . ($c->last_name ?? ''));
                if (!$primaryCustomer) $primaryCustomer = $c;
            }
            if (!$primaryCustomer) continue;

            $bookedByName  = $bookedByUsers[$booking->admin_id] ?? '';
            $bookingFor    = $booking->booking_for ?? '';
            $leadSource    = $booking->lead_source ?? '';
            $subLeadSource = $booking->sub_lead_source ?? '';
            $expedition    = $booking->expedition ?? '';
            $thisTripName  = $booking->trip->name ?? $TripName;

            $vehicleType    = $booking->vehical_type ?? '';
            $vehicleSeat    = $booking->vehical_seat ?? '';
            $vehicleSeatAmt = $booking->vehical_seat_amt ?? '';
            $vehicleSecAmt  = $booking->vehical_security_amt ?? '';
            $vehicleAmtCmt  = $booking->vehical_security_amt_cmt ?? '';

            $roomType = $roomTypeAmt = $roomCategory = '';
            if (!empty($booking->room_info)) {
                $roomInfo = json_decode($booking->room_info, true);
                if (!empty($roomInfo[0])) {
                    $roomType    = $roomInfo[0]['room_type']     ?? '';
                    $roomTypeAmt = $roomInfo[0]['room_type_amt'] ?? '';
                    $roomCategory= $roomInfo[0]['room_cat']      ?? '';
                }
            }
            if ($roomType === '')     $roomType     = $booking->room_type     ?? '';
            if ($roomTypeAmt === '')  $roomTypeAmt  = $booking->room_type_amt ?? '';
            if ($roomCategory === '') $roomCategory = $booking->room_cat      ?? '';

            $paymentFrom   = $booking->payment_from ?? '';
            $company       = $booking->payment_from_cmpny ?? '';
            $tcs           = $booking->payment_from_tax ?? '';
            $completePayBy = '';
            if (!empty($booking->payment_by_customer_id) && isset($customers[$booking->payment_by_customer_id])) {
                $pc = $customers[$booking->payment_by_customer_id];
                $completePayBy = trim(($pc->first_name ?? '') . ' ' . ($pc->last_name ?? ''));
            }
            $paymentType   = $booking->payment_type ?? '';
            $paymentAmount = $booking->payment_amt ?? '';
            $paymentDate   = $booking->payment_date ?? '';

            $tripCost = '';
            if (!empty($booking->trip_cost)) {
                $tripCostData = json_decode($booking->trip_cost, true);
                if (!empty($tripCostData[0]['cost'])) $tripCost = $tripCostData[0]['cost'];
            }

            $extraServices      = $booking->extra_services ?? '';
            $taxRequired        = ($booking->tax_required == 1) ? 'No' : 'Yes';
            $payableAmount      = $booking->payable_amt ?? '';
            $tripStatus         = $booking->trip_status ?? '';
            $invoiceFile        = $booking->invoice_file ? url('storage/app/' . $booking->invoice_file) : '';
            $invoiceStatus      = $booking->invoice_status ?? '';
            $invoiceSentDate    = $booking->invoice_sent_date ?? '';
            $redeemPoints       = $booking->redeem_points ?? '';
            $cancellationReason = $booking->cancelation_reason ?? '';
            $cancellationAmount = $booking->cancelation_amount ?? '';
            $cancellationDate   = $booking->cancelation_date ?? '';
            $bookingId          = $booking->id;

            $profile         = $primaryCustomer->profile         ? $baseUrl . $primaryCustomer->profile         : '';
            $passport_front  = $primaryCustomer->passport_front  ? $baseUrl . $primaryCustomer->passport_front  : '';
            $passport_back   = $primaryCustomer->passport_back   ? $baseUrl . $primaryCustomer->passport_back   : '';
            $pan_gst         = $primaryCustomer->pan_gst         ? $baseUrl . $primaryCustomer->pan_gst         : '';
            $gst_certificate = $primaryCustomer->gst_certificate ? $baseUrl . $primaryCustomer->gst_certificate : '';
            $adhar_card      = $primaryCustomer->adhar_card      ? $baseUrl . $primaryCustomer->adhar_card      : '';
            $driving         = $primaryCustomer->driving         ? $baseUrl . $primaryCustomer->driving         : '';

            $latestTrip = $primaryCustomer->letest_trip ? Trip::find($primaryCustomer->letest_trip) : null;
            $latestTripName = $latestTrip ? $latestTrip->name : 'N/A';

            $createdAt = $primaryCustomer->created_at ? date("d M, Y", strtotime($primaryCustomer->created_at)) : '';
            $updatedAt = $primaryCustomer->updated_at ? date("d M, Y", strtotime($primaryCustomer->updated_at)) : '';
            $deletedAt = $primaryCustomer->deleted_at ? date("d M, Y", strtotime($primaryCustomer->deleted_at)) : '';

            $sheet->fromArray([[
                $bookedByName,
                $bookingId,
                $bookingFor,
                implode(', ', $fullNames),
                $leadSource,
                $subLeadSource,
                $expedition,
                $thisTripName,
                $vehicleType,
                $vehicleSeat,
                $vehicleSeatAmt,
                $vehicleSecAmt,
                $vehicleAmtCmt,
                $roomType,
                $roomTypeAmt,
                $roomCategory,
                $paymentFrom,
                $company,
                $tcs,
                $completePayBy,
                $paymentType,
                $paymentAmount,
                $paymentDate,
                $tripCost,
                $extraServices,
                $taxRequired,
                $payableAmount,
                $tripStatus,
                $invoiceFile,
                $invoiceStatus,
                $invoiceSentDate,
                $redeemPoints,
                $cancellationReason,
                $cancellationAmount,
                $cancellationDate,
                $primaryCustomer->gender,
                $primaryCustomer->email,
                $primaryCustomer->telephone_code,
                $primaryCustomer->phone,
                $profile,
                $primaryCustomer->email_verified_at,
                $primaryCustomer->address,
                $primaryCustomer->city,
                $primaryCustomer->country,
                $primaryCustomer->state,
                $primaryCustomer->pincode,
                $primaryCustomer->dob,
                $primaryCustomer->meal_preference,
                $primaryCustomer->blood_group,
                $primaryCustomer->profession,
                $primaryCustomer->emg_contact,
                $primaryCustomer->t_size,
                $primaryCustomer->medical_condition,
                $primaryCustomer->vaccination,
                $primaryCustomer->tier,
                $primaryCustomer->points,
                $primaryCustomer->credit_note_wallet,
                $primaryCustomer->referred_by,
                $primaryCustomer->parent,
                $primaryCustomer->relation,
                $primaryCustomer->emg_name,
                $primaryCustomer->something,
                $primaryCustomer->have_road_trip,
                $primaryCustomer->thrilling_exp,
                $primaryCustomer->three_travel,
                $primaryCustomer->three_place,
                $passport_front,
                $passport_back,
                $pan_gst,
                $gst_certificate,
                $adhar_card,
                $driving,
                $latestTripName,
                $createdAt,
                $primaryCustomer->is_password_changed,
                $updatedAt,
                $deletedAt,
            ]], null, 'A' . $row);

            $row++;
        }

        $columnCount = count($headers);
        for ($col = 1; $col <= $columnCount; $col++) {
            $columnLetter = Coordinate::stringFromColumnIndex($col);
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="customers_registration_data.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }


    // public function merchandiseExport(Request $request)
    // {
    //     // Fetch data from the Customer model

    //     $trip_id = $request->trip_id;
    //     $mData = TripBooking::where('form_submited', 1)->where('trip_id', $trip_id)->whereNotIn('trip_status', ['Cancelled', 'Draft'])->orderBy('id', 'desc')->get();
    //     if($mData)
    //     {
    //           return redirect()->back()->with('warning', 'No Merchandise Data Found .');
    //     }
    //     $mData->map(function ($item, $index) {
    //         $customers = json_decode($item->customer_id);
    //         $customerData = [];
    //         foreach($customers as $c_id){
    //             $item->created = date("d M, Y", strtotime($item->created_at));
    //             $item->customers = getCustomerById($c_id)->name ?? null;
    //             $item->trip_name = $item->trip->name;
    //             $merchandiseData = "";
    //             if($item->trip->merchandise_id){
    //                 $stationaries = json_decode($item->trip->merchandise_id) ?? [""];
    //                 foreach($stationaries as $merchandise_id){
    //                     $st = getMerchandiseById($merchandise_id)->title ?? null;
    //                     $merchandiseData .= $st. ", ";
    //                 }
    //             }

    //             $item->size = getCustomerById($c_id)->t_size ?? null;
    //             $item->qty = 1;
    //             $item->gender = getCustomerById($c_id)->gender ?? null;
    //             $item->merchandise_name = $merchandiseData;
    //         }
    //     });

    //     // Prepare data for export
    //     $data = [];
    //     $data[] = ["Trip Type",	"Date",	"Traveller Name",	"Merchandise Type",	"Size",	"Qty",	"Gender"];

    //     foreach ($mData as $customer) {
    //         $data[] = [
    //             $customer->trip_name,
    //             $customer->created,
    //             $customer->customers,
    //             $customer->merchandise_name,
    //             $customer->size,
    //             $customer->qty,
    //             $customer->gender,
    //         ];
    //     }

    //     // Create a new PhpSpreadsheet instance
    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     // Add data to the spreadsheet
    //     $sheet->fromArray($data, null, 'A1');

    //     // Set auto column size for all columns
    //     foreach(range('A', 'Z') as $columnID) {
    //         $sheet->getColumnDimension($columnID)->setAutoSize(true);
    //     }

    //     // Create a writer for XLSX format
    //     $writer = new Xlsx($spreadsheet);

    //     // Set headers for download
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment;filename="merchandise.xlsx"');
    //     header('Cache-Control: max-age=0');

    //     // Output the spreadsheet data to a file
    //     $writer->save('php://output');
    // }
    public function merchandiseExport(Request $request)
    {
        $trip_id = $request->trip_id;

        $bookings = TripBooking::where('form_submited', 1)
            ->where('trip_id', $trip_id)
            ->whereNotIn('trip_status', ['Cancelled', 'Draft'])
            ->orderBy('id', 'desc')
            ->get();

        if ($bookings->isEmpty()) {
            return redirect()->back()->with('warning', 'No Merchandise Data Found.');
        }

        $rows = [];
        $rows[] = ["Trip Type", "Date", "Traveller Name", "Address", "Merchandise Type", "Size", "Qty", "Gender"];

        foreach ($bookings as $item) {
            $created = date("d M, Y", strtotime($item->created_at));
            $trip_name = $item->trip->name ?? null;

            $merchandise_name = '';
            if (!empty($item->trip->merchandise_id)) {
                $ids = json_decode($item->trip->merchandise_id) ?: [];
                $titles = [];
                foreach ($ids as $mid) {
                    $m = getMerchandiseById($mid);
                    if (!empty($m->title)) $titles[] = $m->title;
                }
                $merchandise_name = implode(', ', $titles);
            }

            $customers = json_decode($item->customer_id) ?: [];
            foreach ($customers as $c_id) {
                $cust = getCustomerById($c_id);
                if (!$cust) continue;

                $name = $cust->name ?? null;

                $parts = [
                    isset($cust->address) ? trim($cust->address) : '',
                    isset($cust->city)    ? trim($cust->city)    : '',
                    isset($cust->state)   ? trim($cust->state)   : '',
                    isset($cust->country) ? trim($cust->country) : '',
                    isset($cust->pincode) ? trim($cust->pincode) : '',
                ];
                $parts = array_values(array_filter($parts, fn($v) => $v !== ''));
                $address = $parts ? implode(', ', $parts) : null;

                $size = $cust->t_size ?? null;
                $qty = 1;
                $gender = $cust->gender ?? null;

                $rows[] = [
                    $trip_name,
                    $created,
                    $name,
                    $address,
                    $merchandise_name,
                    $size,
                    $qty,
                    $gender,
                ];
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($rows, null, 'A1');
        foreach (range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $writer = new Xlsx($spreadsheet);

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="merchandise.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function stationaryExport(Request $request)
    {
        // Fetch data from the Customer model

        $trip_id = $request->trip_id;
        $mData = TripBooking::where('form_submited', 1)->where('trip_id', $trip_id)->whereNotIn('trip_status', ['Cancelled', 'Draft'])->orderBy('id', 'desc')->get();
       
        if($mData)
        {
              return redirect()->back()->with('warning', 'No Stationary Data Found .');
        }
        $mData->map(function ($item, $index) {
            $item->created = date("d M, Y", strtotime($item->created_at));
            $customers = json_decode($item->customer_id);
            foreach($customers as $c_id){
                $c_name = getCustomerById($c_id)->name ?? null;
                $item->customers = $c_name;
                $item->trip_name = $item->trip->name;
                $stationaryData = "";
                if($item->trip->stationary_id){
                    $stationaries = json_decode($item->trip->stationary_id) ?? [""];
                    foreach($stationaries as $stationary_id){
                        $st = getstationaryById($stationary_id)->title ?? null;
                        $stationaryData .= $st. ", ";
                    }
                }

                $item->qty = 1;
                $item->stationary_name = $stationaryData;
            }

        });

        // Prepare data for export
        $data = [];
        $data[] = ["Trip Type",	"Date",	"Traveller Name", "Stationary Type", "Qty"];

        foreach ($mData as $customer) {
            $data[] = [
                $customer->trip_name,
                $customer->created,
                $customer->customers,
                $customer->stationary_name,
                $customer->qty,
            ];
        }

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add data to the spreadsheet
        $sheet->fromArray($data, null, 'A1');

        // Set auto column size for all columns
        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Create a writer for XLSX format
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="stationary.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet data to a file
        $writer->save('php://output');
    }

    public function sendMerEmail(Request $request){
        $tripName = getTripById($request->trip_id)->name ?? "Deleted Trip";
        $url = route('merchandise_export', ['trip_id'=>$request->trip_id]);
        $merEmail = setting('merchandise_email');
        $data = [
            'email'=>$merEmail,
            'link'=> $url
        ];
        if($merEmail){
            if(setting('mail_status') == 1){
                event(new SendMailEvent("$merEmail", "$tripName customers merchandise details | Adventures Overland!", 'emails.merchandise', $data));
            }
            return redirect()->back()->with('success', 'Mail Sent Successfully!');
        }else{
            return redirect()->back()->with('error', 'Mail Not Found, Kindly add Merchandise Email!');
        }
    }

    public function sendStatEmail(Request $request){
        $tripName = getTripById($request->trip_id)->name ?? "Deleted Trip";
        $url = route('stationary_export', ['trip_id'=>$request->trip_id]);
        $statEmail = setting('stationary_email');
        $data = [
            'email'=>$statEmail,
            'link'=> $url
        ];
        if($statEmail){
            if(setting('mail_status') == 1){
                event(new SendMailEvent("$statEmail", "$tripName customers stationary details | Adventures Overland!", 'emails.stationary', $data));
            }
            return redirect()->back()->with('success', 'Mail Sent Successfully!');
        }else{
            return redirect()->back()->with('error', 'Mail Not Found, Kindly add Stationary Email!');
        }
    }

    public function receivable(Request $request){
        $trip_id = $request->trip_id;
        $data = TripBooking::where('trip_id', $trip_id)->whereNotNull('sch_payment_list')->whereNotIn('trip_status', ['Cancelled', 'Draft'])->orderBy('id', 'desc')->get();

        foreach($data as $key=>$item){

            $tooltipPayment = "";
            if($item->part_payment_list != null){
                foreach(json_decode($item->part_payment_list) as $ppList){
                    $tooltipPayment .= "₹".$ppList->amount." [Received on ". date("d M, Y", strtotime($ppList->date))."], \n";
                }
            }
            if($tooltipPayment == ""){
                $tooltipPayment = "No Amount";
            }

            $customers = json_decode($item->customer_id);
            foreach($customers as $key=>$c_id){
                $getLastMail = EmailActivity::where('booking_id', $item->id)->where('customer_id', $c_id)->first();

                $item->created = date("d M, Y", strtotime($item->created_at));
                $item->customers = getCustomerById($c_id)->name ?? null;
                $item->customer_id = $c_id;
                $item->trip_name = $item->trip->name;
                $item->last_mail = $getLastMail->mail_date ?? " ";
                $item->tooltip = $tooltipPayment ?? "No Amount";

                $advanceAmt = $item->payment_amt ?? 0;
                $totalRec = 0;
                if($item->sch_payment_list){
                    $schs = json_decode($item->sch_payment_list) ?? [""];

                    foreach($schs as $sch){
                        $totalRec += $sch->amount;
                    }
                }
                $item->total_rec = $totalRec;
                $totalPart = 0;
                if($item->part_payment_list){
                    $parts = json_decode($item->part_payment_list) ?? [""];

                    foreach($parts as $part){
                        $totalPart += $part->amount;
                    }
                }
                $item->total_recieved = $totalPart;
                $item->total_due = $item->total_rec - $item->total_recieved;
            }
        }
        // dd($data);
        return DataTables::of($data)->make(true);
    }

    public function sendEmail(Request $request){
        $bId = $request->b_id;
        $tId = $request->t_id;
        $cId = $request->c_id;

        $customer = getCustomerById($cId);
        $booking = TripBooking::find($bId);
        if($booking->sch_payment_list != null && $booking->sch_payment_list != "null"){

            $rec_amount = 0;
            if($booking->part_payment_list != null && $booking->part_payment_list != "null"){
                foreach(json_decode($booking->part_payment_list) as $part){
                    $rec_amount += $part->amount;
                }
            }

            $total_amount = 0;
            foreach(json_decode($booking->sch_payment_list) as $sch){
                $total_amount += $sch->amount;
            }

            $checkPending = 0;
            if($rec_amount < $total_amount){
                if($rec_amount == 0){
                    $dueKey = 0;
                }else{
                    foreach(json_decode($booking->sch_payment_list) as $key=>$sch){
                        $checkPending += $sch->amount;
                        if($rec_amount < $checkPending){
                            $dueKey = $key;
                            break;
                        }
                    }
                }
            }else{
                return redirect()->back()->with('error', 'No Schedule Pending Payment Found!');
            }


            $schPaymentList = json_decode($booking->sch_payment_list);
            $pendingAmt = $checkPending - $rec_amount;
            $dueDate = $schPaymentList[$dueKey]->date;

            // user email
            if(strtotime($dueDate) <= strtotime(date("Y-m-d"))){
                $data = [
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'pending_amt' => $pendingAmt,
                    'due_date' => $dueDate,
                    'total_rec'=> $total_amount,
                    'due_type' => 'is',
                ];
            }else{
                $data = [
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'pending_amt' => $pendingAmt,
                    'due_date' => $dueDate,
                    'total_rec'=> $total_amount,
                    'due_type' => 'was',
                ];
            }
            if(setting('mail_status') == 1){
                event(new SendMailEvent("$customer->email", 'Gentle Reminder: Upcoming Payment for Your Trip | Adventures Overland!', 'emails.booking-payment-due', $data));
            }

            // =========== email ==================
            $accountEmail = setting('account_mail');
            $opsEmail = setting('operation_mail');

            if($accountEmail){
                $dataAdmin = [
                    'customer_name' => $customer->name,
                    'email' => $accountEmail,
                    'trip_name'=> $booking->trip->name ?? "Deleted",
                    'trip_date' => $booking->trip->start_date ?? "Deleted",
                    'due_date' => $dueDate,
                    'pending_amt' => $pendingAmt,
                    'total_recievables'=> $total_amount,
                    'total_rec'=> $rec_amount,
                    'net_recievables'=> $pendingAmt,
                ];
                if(setting('mail_status') == 1){
                    event(new SendMailEvent("$accountEmail", 'Payment Reminder: Upcoming Trip '.$booking->trip->name.' for '. $customer->name .' | Adventures Overland!', 'emails.admin-booking-payment-due', $dataAdmin));
                }
            }

            if($opsEmail){
                $dataAdmin = [
                    'customer_name' => $customer->name,
                    'email' => $opsEmail,
                    'trip_name'=> $booking->trip->name ?? "Deleted",
                    'trip_date' => $booking->trip->start_date ?? "Deleted",
                    'due_date' => $dueDate,
                    'pending_amt' => $pendingAmt,
                    'total_recievables'=> $total_amount,
                    'total_rec'=> $rec_amount,
                    'net_recievables'=> $pendingAmt,
                ];
                if(setting('mail_status') == 1){
                    event(new SendMailEvent("$opsEmail", 'Payment Reminder: Upcoming Trip '.$booking->trip->name.' for '. $customer->name .' | Adventures Overland!', 'emails.admin-booking-payment-due', $dataAdmin));
                }
            }

            // save email record
            $emailAct = new EmailActivity();
            $emailAct->trip_id = $booking->trip_id;
            $emailAct->booking_id = $booking->id;
            $emailAct->admin_id = Auth::user()->id;
            $emailAct->customer_id = $customer->id;
            $emailAct->mail_date = date("Y-m-d");
            $emailAct->save();
            return redirect()->back()->with('success', 'Mail Sent Successfully!');
        }else{
            return redirect()->back()->with('error', 'No Schedule Pending Payment Found!');
        }
    }

    public function getExpenseById(Request $request){
        $res = Expense::find($request->id);
        if($res->docx){
            $res->docx = url('storage/app').$res->docx;
        }else{
            $res->docx = null;
        }
        return $res;
    }

    public function deleteExpenseDetail($id){
        $res = ExpenseHistory::find($id);

        $exp = Expense::find($res->expense_id);

        // check if trip is not editable
        if(!checkTripEditable($exp->trip_id)){
            return redirect()->back();
        }

        $exp->paid_amount = $exp->paid_amount - $res->amount;
        $exp->save();
        $res->delete();
        return redirect()->back()->with('success', 'Deleted Successfully!');
    }

    public function editExpenseDetail(Request $request){
        $request->validate([
            'amount' => ['required'],
        ]);


        $data = ExpenseHistory::find($request->id);

        // check if trip is not editable
        if(!checkTripEditable($data->expense->trip_id)){
            return redirect()->back();
        }

        $amountDiff = $request->amount - $data->amount;
        $data->amount = $request->amount;
        $data->payment_mode = $request->payment_mode;
        $data->date = $request->date;
        $data->comment = $request->comment ?? null;
        $data->save();

        $exp = Expense::find($data->expense_id);
        $exp->paid_amount = $exp->paid_amount + $amountDiff;
        $exp->save();

        return redirect()->back()->with('success', 'Payment Saved Successfully !!');
    }

     // expense report download
    public function expenseReportDownload(Request $request)
    {
      
        $tripId = $request->trip_id;
      
        $expenses = Expense::with(['trip', 'vendor', 'service','vendorService'])->where('trip_id', $tripId)->get();


        if ($expenses->isEmpty()) {
            return redirect()->back()->with('warning', 'No expenses found for this trip.');
        }

        $headers = [
            'Created Date',
            'Trip Name',
            'Vendor',
            'Category',
            'Service',
            'Amount Due',
            'Amount Paid',
            'Pending Amount',
            'Document',
            'Comment',
            'Payment Date',
            'Vendor Company Name',
            'Service Amount',
            'Payment Mode',
            'Added By',
        ];

        $data = [];
        $data[] = $headers;

         foreach ($expenses as $expense) {
                $histories[$expense->id] = ExpenseHistory::where('expense_id', $expense->id)->get();
                $expenseHistories = $histories[$expense->id] ?? collect();
                $history = $expenseHistories->first();
                if($expense->document)
                {
                    $expense->document = '<a href="' . asset('storage/' . $expense->document) . '" target="_blank">View</a>';
                } else {
                    $expense->document = '-';
                }
        
                $data[] = [
                    Carbon::parse($expense->created_at)->format('d M Y'),
                    $expense->trip->name ?? '-',
                    $expense->vendor->first_name ?? '-',
                    $expense->service->title ?? '-',
                    $expense->vendorService->title ?? '-',
                    $expense->total_amount ?? 0,
                    $expense->paid_amount ?? 0,
                    ($expense->total_amount ?? 0) - ($expense->paid_amount ?? 0),
                    $expense->document ?? '-',
                    $expense->comment ?? '-',
                    $history && $history->date ? Carbon::parse($history->date)->format('d M Y') : '-',
                    $expense->vendor->company ?? '-',
                    $history->amount ?? '-',
                    $history->payment_mode ?? '-',
                    $history && $history->admin ? $history->admin->name : '-' 
                ];
            }
          

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($data, null, 'A1');
        $sheet->fromArray([$headers], null, 'A1');
        $sheet->getStyle('A1:' . Coordinate::stringFromColumnIndex(count($headers)) . '1')->getFont()->setBold(true);

        $row = 2;

        foreach (range('A', 'O') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="expense_report.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

}
