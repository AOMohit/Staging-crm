<?php

namespace App\Http\Controllers;
use App\Models\TripBooking;
use App\Models\Trip;
use App\Models\TripInvoices;
use App\Models\User;
use App\Models\Customer;
use App\Models\ActivityTracker;
use App\Models\Agent;
use App\Models\CarbonInfo;
use App\Models\TripCarbonInfo;
use App\Models\ExtraService;
use App\Models\LoyalityPts;
use App\Models\Relationship;
use App\Models\PartPaymentHistory;
use App\Models\BookingsInvoiceDetail;
use App\Models\BookingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Str;
use App\Events\SendMailEvent;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use PDF;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class TripBookingController extends Controller
{
    public function index(){
        $admins = User::all();
        $trips = Trip::where('status', '!=', 'Cancelled')->get();
        $customers = Customer::where('parent', 0)->get();

        $allBookingCount = TripBooking::where('trip_status','!=', 'Draft')->count();
        $completedBookingCount = TripBooking::where('trip_status', 'Completed')->count();
        $cancelledBookingCount = TripBooking::where('trip_status', 'Cancelled')->count();
        $correctionBookingCount = TripBooking::where('trip_status', 'Correction')->count();
        $draftBookingCount = TripBooking::where('trip_status', 'Draft')->count();

        return view('admin.booking.index', compact('admins', 'trips', 'customers', 'allBookingCount', 'completedBookingCount', 'cancelledBookingCount', 'correctionBookingCount', 'draftBookingCount'));
    }

    public function get(Request $request)
    {

        if($request->type == "all"){
            $data = TripBooking::where('form_submited', 1)->orderBy('id', 'desc');
        }elseif($request->type == "completed"){
            $data = TripBooking::where('trip_status', 'Completed')->orderBy('id', 'desc');
        }elseif($request->type == "cancelled"){
            $data = TripBooking::where('trip_status', 'Cancelled')->orderBy('id', 'desc');
        }elseif($request->type == "correction"){
            $data = TripBooking::where('trip_status', 'Correction')->orderBy('id', 'desc');
        }elseif($request->type == "draft"){
            $data = TripBooking::where('trip_status', 'Draft')->orderBy('id', 'desc');
        }

        // filter
        if(isset($request->trip_id)){
            $data->where('trip_id', $request->trip_id);
        }
        if(isset($request->status)){
            $data->where('trip_status', $request->status);
        }
        if(isset($request->admin_id)){
            $data->where('admin_id', $request->admin_id);

            
        }
        if(isset($request->date)){
            $data->whereDate('created_at', $request->date);
        }
        if(isset($request->invoice_status)){
            if($request->invoice_status == "Sent"){
                $data->where('invoice_status', "Sent");
            }else{
                $data->where('invoice_status', '!=' ,"Sent");
            }
        }
        if(isset($request->trip_type)){
            $data->whereHas('trip', function ($query) use($request) {
                $query->where('trip_type', $request->trip_type);
            });
        }
        if(isset($request->customer_name)){
            $data->whereJsonContains('customer_id',"$request->customer_name");
           
        }
        $dataCount = $data->count();

        // query speed purpose
        // filter
        $offset = 0;
        if (isset($_GET['start'])) {
            $offset = $_GET['start'];
        }
        $length = 0;
        if (isset($_GET['length'])  && $_GET['length'] > 0) {
            $length = $_GET['length'];
        }

        $data = $data->skip($offset)->take($length)->get();
        // query speed purpose

        session()->put('booking_query', $data);

        $data->map(function ($item, $index) {
            $item->created = date("d M, Y", strtotime($item->created_at));
            if($item->invoice_sent_date ){
                $item->invoice_sent_date = date("d M, Y", strtotime($item->invoice_sent_date));
            }
            $item->admin_id = $item->admin->name ?? null;
            $item->spoc_name = $item->spoc->name ?? null;
            $item->trip_name = $item->trip->name ?? null;
            $item->trip_type = $item->trip->trip_type ?? null;
           
   
            $item->relation_manager_names = $item->trip ? $item->trip->relation_manager_names : 'N/A';
            if($item->customer_id){
                $customers = json_decode($item->customer_id);
            }else{
                $customers = [];
            }
            $item->pax = count($customers);

            $customerData = [];
            $trip_spent = 0;
            foreach ($customers as $c_id) {
                $customer = getCustomerById($c_id);
                $c_name = $customer->name ?? null;
            
                if ($customer && $customer->email && $customer->email != 'null') {
                    $c_email = $customer->email;
                } else {
                    $parentCustomer = $customer && $customer->parent ? getCustomerById($customer->parent) : null;
                    $parentEmail = $parentCustomer ? $parentCustomer->email : null;
                    $c_email = ($customer->relation ?? null) . " of " . ($parentEmail ?? "Unknown");
                }
            
                array_push($customerData, ['name' => $c_name, 'email' => $c_email]);
            
                $trip_spent += (totalTripCostOfCustomerById($c_id, $item->id)) ?? 0;
            }
            $item->customers = $customerData;

            $partAmt = 0;
            if ($item->part_payment_list) {
                foreach (json_decode($item->part_payment_list) as $pp) {
                    $partAmt += $pp->amount;
                }
            }
            $item->balance =number_format($trip_spent - $item->payment_amt - $partAmt);
            $item->payable_amt = number_format($trip_spent);
            
            if($item->payment_amt == null){
                $item->payment_amt = 0;
            }
            
            $item->total_payment_amount_received = $item->payment_amt + $partAmt;
            if($item->trip_status != 'Correction'){
                if($item->trip_status != 'Cancelled'){
                    if(isset($item->trip->end_date) && $item->trip->end_date < date("Y-m-d")){
                        $item->trip_status = "Completed";
                    }
                }
            }
        });

        $response = array(
            "draw" => intval($_GET['draw']),
            "iTotalRecords" => $dataCount,
            "iTotalDisplayRecords" => $dataCount,
            "aaData" => $data
        );


        return json_encode($response);

        // return DataTables::of($data)->make(true);
    }

    public function create(Request $request){


        $dueKey = 0;
        $pendingAmt = 0;
        if(isset($request->token)){
            $data = TripBooking::where('token', $request->token)->first();

            // for sch payment
            $partAmt = 0;
            if($data && $data->part_payment_list){
                foreach(json_decode($data->part_payment_list) as $pp){
                    $partAmt+=$pp->amount;
                }
            }

            $rec_amount = $partAmt;
            $total_amount = 0;
            if($data && $data->sch_payment_list && $data->sch_payment_list != "null"){
                foreach(json_decode($data->sch_payment_list) as $sch){
                    $total_amount += $sch->amount;
                }
            }

            $checkPending = 0;
            if($rec_amount < $total_amount){
                foreach(json_decode($data->sch_payment_list) as $key=>$sch){
                    $checkPending += $sch->amount;
                    if($rec_amount < $checkPending){
                        $dueKey = $key;
                        break;
                    }
                }
            }else{
                $dueKey = 0;
            }
            $pendingAmt = $checkPending - $rec_amount;
        }else{
            $token = Str::random(40);
            $hashedToken = hash('sha256', $token);

            return redirect()->route('booking.new-trip', ['token' => $hashedToken]);
        }

        $customerswithChild = Customer::with('minors')->orderBy('first_name', 'asc')->get();
        $customerswithChild->map(function ($item, $index) {
            $item->identity = ($item->email && $item->email != "null")
                ? $item->email
                : (($item->minors && $item->relation)
                    ? $item->relation . " of " . $item->minors->email
                    : null);
        });

        $customers = Customer::where('parent', 0)->orderBy('first_name', 'asc')->get();
        $trips = Trip::orderBy('name', 'asc')->where('status', '!=', 'Cancelled')->whereDate('end_date', '>=', date("Y-m-d"))->get();
        $extraServices = ExtraService::orderBy('title', 'asc')->get();
        $relationships = Relationship::orderBy('title', 'asc')->get();
        return view('admin.booking.add', compact('data', 'customers', 'trips', 'customerswithChild', 'extraServices', 'dueKey', 'pendingAmt', 'relationships'));
    }

    public function destroy($token, Request $request)
    {

        $data = TripBooking::where(['token'=> $token, 'trip_status'=>'Draft'])->first();

        // Activity Tracker
        $activity = new BookingLog();
        $activity->admin_id = Auth::user()->id;
        $activity->booking_id = $data->id;
        $activity->page = "Bookings List";
        $activity->action = "<b>".Auth::user()->name ."<b> has Deleted a Trip booking : #".$data->id;
        $activity->save();
        // Activity Tracker

        $data->delete();

        return redirect(route('booking.index'))->with('success', 'Draft Booking Deleted!!');

    }

    public function activityPage($id)
    {
        $tripData = TripBooking::where('id', $id)->first();
        $trip_name = $tripData->trip->name ?? "Deleted";

        if(checkAdminRole()){
            $data = BookingLog::where(['booking_id'=>$id])->orderBy('id', 'desc')->get();
        }else{
            $data = BookingLog::where(['booking_id'=>$id])->where('admin_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        }

        $data->map(function ($item, $index) {
            $dateTime = date("M d, Y H:i:s", strtotime($item->created_at));
            $item->created = Carbon::parse($dateTime)->format('d M, Y H:i:s') . ' (' . Carbon::parse($dateTime)->diffForHumans() . ')';
            $item->name = $item->admin->name;
        });

        return view('admin.booking.activity', compact('trip_name', 'data'));
    }

    public function activity(Request $request)
    {
        $data = BookingLog::where(['id'=>$request->id])->first();
        if($data && $data->other){
            $res = $data->other;
        }else{
            $res = null;
        }
        return $res;
    }

    public function view(Request $request)
    {
        
        $data = TripBooking::where('token', $request->token)->first();

        $data->admin_name = $data->admin->name ?? null;
        $data->relation_manager_names = $data->trip ? $data->trip->relation_manager_names : 'N/A';

        $carbonAmtSum = TripCarbonInfo::where('booking_id', $data->id)->sum('donation_amt') ?? 0;
        $total = $data->payable_amt;
   
        $firstPay = $data->payment_amt;
        $partAmt = 0;
        if($data->part_payment_list){
            foreach(json_decode($data->part_payment_list) as $pp){
                $partAmt+=$pp->amount;
                
            }
        }
        $billingTo = json_decode($data->billing_to, true);
        $customers = json_decode($data->customer_id, true);
      
      
        $rmBal = $total + $carbonAmtSum - ($firstPay + $partAmt);


        if($data->trip_status != 'Correction'){
            if($data->trip_status != 'Cancelled'){
                if($data->trip->end_date < date("Y-m-d")){
                    $data->trip_status = "Completed";
                }
            }
        }
       

        // Status of Schedule payment
            $rec_amount = $partAmt;
            $total_amount = 0;
         
            if($data->sch_payment_list && $data->sch_payment_list != "null"){
                foreach(json_decode($data->sch_payment_list) as $sch){
                    $total_amount += $sch->amount;
                }
            }
       
            $checkPending = 0;
            if($rec_amount <= $total_amount){
                $schPayments = json_decode($data->sch_payment_list) ?: [];
                $dueKey = null;
                foreach($schPayments as $key=>$sch){
                    $checkPending += $sch->amount;
                    if($rec_amount <= $checkPending){
                        $dueKey = $key;
                        break;
                    }
                }
                if($dueKey === null){
                    $dueKey = 0;
                }
            }else{
                $dueKey = 0;
            }
   

            $pendingAmt = $checkPending - $rec_amount;
          
        // Status of Schedule payment




        $bookingInvoices = BookingsInvoiceDetail::where('booking_id', $data->id)->get();
        $bookingId = $data->id;


      

        return view('admin.booking.view', compact('data', 'customers','rmBal', 'dueKey', 'pendingAmt', 'bookingInvoices','bookingId'));
    }


    // -----------------------------------------------------
    // ------------------ VIP trip booking -----------------
    // -----------------------------------------------------
    public function bookingFor(Request $request){       
        $token = $request->token;
        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['booking_for' => $request->booking_for]
        );
        
        if (!$bookingData->wasRecentlyCreated) {
            $action = "<b>".Auth::user()->name . "</b> has Updated 'booking for' to '$request->booking_for'";
              $activity = new BookingLog();
              $activity->admin_id = Auth::user()->id;
              $activity->booking_id = $bookingData->id;
              $activity->page = "Booking Form";
              $activity->action = $action;
              $activity->save();
        }
        return true;
    }

    public function leadSource(Request $request){
        $token = $request->token;
        $lead = $request->lead_source;

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['lead_source' => $lead]
        );

        if($lead == "Agent"){
            $agents = Agent::all();
            $data = "<select onchange='subLeadSource(this.value)' id='sub_lead_source'
            class='form-control'><option value=''>Select</option>";
            foreach($agents as $agent){
                $name = $agent->first_name ." ".$agent->last_name;
                $data .= "<option value='".$name."'>".$name."</option>";
            }
            $data .= "</select>";
        }elseif($lead == "Social Media"){
            $data = "<select onchange='subLeadSource(this.value)'  id='sub_lead_source'
            class='form-control'><option value=''>Select</option><option value='Google'>Google</option><option value='Facebook'>Facebook</option><option value='Instagram'>Instagram</option><option value='Other'>Other</option></select>";
        }elseif($lead == "Referrals"){
            $data = 1; //for input
        }
        elseif($lead == "Organic"){
            $data = "<select onchange='subLeadSource(this.value)'  id='sub_lead_source'
            class='form-control'><option value=''>Select</option><option value='Call'>Call</option><option value='Email'>Email</option><option value='Website'>Website</option></select>";
        }
        elseif($lead == "Repeated"){
            $data = "<input class='d-none'>";
        }
        else{
            $data = 0;
        }
        if (!$bookingData->wasRecentlyCreated) {
            $action = "<b>".Auth::user()->name . "</b> has Updated 'Lead source' to '$request->lead_source'";
              $activity = new BookingLog();
              $activity->admin_id = Auth::user()->id;
              $activity->booking_id = $bookingData->id;
              $activity->page = "Booking Form";
              $activity->action = $action;
              $activity->save();
        }

        return $data;
    }

    public function subLeadSource(Request $request){
        $token = $request->token;

        TripBooking::updateOrCreate(
            ['token' => $token],
            ['sub_lead_source' => $request->sub_lead_source]
        );
        if (!$bookingData->wasRecentlyCreated) {
            $action = "<b>".Auth::user()->name . "</b> has Updated 'Lead source' to ' $request->sub_lead_source'";
              $activity = new BookingLog();
              $activity->admin_id = Auth::user()->id;
              $activity->booking_id = $bookingData->id;
              $activity->page = "Booking Form";
              $activity->action = $action;
              $activity->save();
        }

        return true;
    }

    public function expedition(Request $request){
        $token = $request->token;
        $expedition = $request->expedition;

        // $booking = TripBooking::where('token', $token)->first();
        // $formSubmitedOldValue = $booking->form_submited;


        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['expedition' => $expedition]
        );

        // $customers = json_decode($booking->customer_id) ?? null;
        // $cList = "";
        // foreach($customers as $cK=>$customer){
        //     $cList .= getCustomerById($customer)->name;
        //     if(($cK+1) != count($customers)){
        //         $cList .= ", ";
        //     }
        // }
        if (!$bookingData->wasRecentlyCreated) {
              $action = "<b>".Auth::user()->name . "</b> has Updated trip type to $expedition";
              $activity = new BookingLog();
              $activity->admin_id = Auth::user()->id;
              $activity->booking_id = $bookingData->id;
              $activity->page = "Booking Form";
              $activity->action = $action;
              $activity->save();

        }


        $trips = Trip::where('trip_type', $expedition)->where('status', '!=', 'Cancelled')->whereDate('end_date', '>=', date("Y-m-d"))->get();
        $data = "<option value=''>Select</option>";
        foreach($trips as $trip){
            if ($trip->status == 'Sold Out'){
                $status = '(Sold Out)';
            }else{
                $status ="";
            }
            $data .= "<option value='".$trip->id."'>".$trip->name." ".$status. "</option>";
        }


        return $data;
    }

    public function trips(Request $request){
        $token = $request->token;
        $trip = Trip::find($request->trip_id);

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['trip_id' => $request->trip_id]
        );
        if($trip){
            if (!$bookingData->wasRecentlyCreated) {
                $action = "<b>".Auth::user()->name . "</b> has Updated trip  to $trip->name";
                $activity = new BookingLog();
                $activity->admin_id = Auth::user()->id;
                $activity->booking_id = $bookingData->id;
                $activity->page = "Booking Form";
                $activity->action = $action;
                $activity->save();
    
            }
            return $trip;
        }
       
        return true;
    }

    public function vehicles(Request $request){
        $token = $request->token;

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['vehical_type' => $request->vehical_type]
        );

        if (!$bookingData->wasRecentlyCreated) {
            $action = "<b>".Auth::user()->name . "</b> has Updated vehical type to $request->vehical_type";
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();

        }

        return true;
    }

    public function seats(Request $request){
        $token = $request->token;

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['vehical_seat' => $request->vehical_seat]
        );
        
        if (!$bookingData->wasRecentlyCreated) {
            $action = "<b>".Auth::user()->name . "</b> has Updated vehical seat to  $request->vehical_seat";
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();

        }
        

        return true;
    }

    public function seatsAmt(Request $request){
        $token = $request->token;

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['vehical_seat_amt' => $request->vehical_seat_amt]
        );

        if (!$bookingData->wasRecentlyCreated) {
            $action = "<b>".Auth::user()->name . "</b> has Updated vehical seat Amount to $request->vehical_seat_amt";
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();

        }

        return true;
    }

    public function vehicleSecAmt(Request $request){
        $token = $request->token;

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['vehical_security_amt' => $request->vehical_security_amt]
        );

        if (!$bookingData->wasRecentlyCreated) {
            $action = "<b>".Auth::user()->name . "</b> has Updated vehical Security Amount to $request->vehical_security_amt";
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();

        }

        return true;
    }

    public function vehicleSecAmtCmt(Request $request){
        $token = $request->token;

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['vehical_security_amt_cmt' => $request->vehical_security_amt_cmt ?? "NA"]
        );

        if (!$bookingData->wasRecentlyCreated) {
            $action = "<b>".Auth::user()->name . "</b> has Updated vehical Security Amount Comment to $request->vehical_security_amt_cmt ?? NA";
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();

        }

        return true;
    }

    public function roomNumber(Request $request){
        $token = $request->token;

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['no_of_rooms' => $request->room_number]
        );
        if(!$bookingData->wasRecentlyCreated) {
            $action = "<b>" . Auth::user()->name . "</b> has Updated Room Number to $request->room_number";
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();
        }

        $data = TripBooking::select('room_info')->where('token', $token)->first();
        return $data;
    }

    public function saveRoomInfo(Request $request){


        $token = $request->token;
        $room_type = $request->room_type;
        $room_type_amt = $request->room_type_amt ?? 0;
        $room_cat = $request->room_cat;
        $arr = [];
        foreach($room_type as $key=>$type){
            $data = ['room_type'=> $type, 'room_type_amt'=>$room_type_amt[$key] ?? 0, 'room_cat'=>$room_cat[$key]];
            array_push($arr, $data);
        }

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['room_info' => json_encode($arr)]
        );

        if (!$bookingData->wasRecentlyCreated) {
           
            $roomInfoString = collect($arr)->map(function ($room) {
                return "Room Type: {$room['room_type']}, Amount: {$room['room_type_amt']}, Category: {$room['room_cat']}";
            })->implode('; ');

            $action = "<b>" . Auth::user()->name . "</b> has Updated Room Information to: " . $roomInfoString;
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();

        }

        return true;
    }

    // public function roomTypeAmt(Request $request){
    //     $token = $request->token;

    //     $bookingData = TripBooking::updateOrCreate(
    //         ['token' => $token],
    //         ['room_type_amt' => $request->room_type_amt, 'admin_id'=>Auth::user()->id]
    //     );

    //     return true;
    // }

    // public function roomCat(Request $request){
    //     $token = $request->token;

    //     $bookingData = TripBooking::updateOrCreate(
    //         ['token' => $token],
    //         ['room_cat' => $request->room_cat, 'admin_id'=>Auth::user()->id]
    //     );

    //     return true;
    // }

    public function paymentFrom(Request $request){
        $token = $request->token;

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['payment_from' => $request->payment_from]
        );

        if (!$bookingData->wasRecentlyCreated) {
            $action = "<b>".Auth::user()->name . "</b> has Updated Payment From to $request->payment_from";
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();

        }

        return true;
    }

    public function paymentFromCmpny(Request $request){
        $token = $request->token;

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['payment_from_cmpny' => $request->payment_from_cmpny]
        );
        if (!$bookingData->wasRecentlyCreated) {
            $action = "<b>".Auth::user()->name . "</b> has Updated Payment From Company to $request->payment_from_cmpny";
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();

        }


        return true;
    }

    public function paymentFromTax(Request $request){
        $token = $request->token;

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['payment_from_tax' => $request->payment_from_tax]
        );

        Log::info(json_encode($bookingData));

        return true;
    }

    public function multiplePayment(Request $request){
        $token = $request->token;
        $data = TripBooking::where('token', $token)->first();
        if($data && $data->payment_from == 'Individual' && $request->is_multiple_payment == 1){
            $is_multiple_payment = 1;
        }else{
            $is_multiple_payment = 0;
        }
        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['is_multiple_payment' => $is_multiple_payment]
        );
        return true;
    }

    public function paymentByCustomer(Request $request){
        $token = $request->token;

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['payment_by_customer_id' => $request->payment_by_customer_id, 'payment_all_done_by_this' => 1]
        );

        $check = TripBooking::where('token', $token)->where('payment_all_done_by_this', 1)->first();
        if($check){
            return getCustomerById($check->payment_by_customer_id)->name;
        }
        
        if (!$bookingData->wasRecentlyCreated) {
            
            $action = "<b>".Auth::user()->name . "</b> has Updated Payment by Customer to " .getCustomerById($check->payment_by_customer_id)->name;
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();

        }

        return true;
    }

    public function paymentAllDoneCheck(Request $request){

        $token = $request->token;

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['payment_all_done_by_this' => $request->payment_all_done_by_this,'payment_by_customer_id' => $request->payment_by_customer_id]
        );

        return true;
    }

    public function paymentType(Request $request){
        $token = $request->token;

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['payment_type' => $request->payment_type]
        );
        if (!$bookingData->wasRecentlyCreated) {
            $action = "<b>".Auth::user()->name . "</b> has Updated Payment Type  $request->payment_type";
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();

        }

        return true;
    }

    public function paymentAmt(Request $request){
        $token = $request->token;

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['payment_amt' => $request->payment_amt]
        );
        if (!$bookingData->wasRecentlyCreated) {
            $action = "<b>".Auth::user()->name . "</b> has Updated Payment Amount  $request->payment_amt";
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();

        }

        return true;
    }

    public function paymentDate(Request $request){
        $token = $request->token;

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['payment_date' => $request->payment_date]
        );

        if (!$bookingData->wasRecentlyCreated) {
            $action = "<b>".Auth::user()->name . "</b> has Updated Payment Date   $request->payment_date";
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();

        }

        return true;
    }

    public function vehicleCmt(Request $request){
        $token = $request->token;


        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['vehicle_type_other_cmt' => $request->vehicle_type_other_cmt]
        );

        if (!$bookingData->wasRecentlyCreated) {
            $action = "<b>".Auth::user()->name . "</b> has Updated Vehicle Comment $request->vehicle_type_other_cmt";
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();

        }

        return true;
    }

    public function costs(Request $request){
        $token = $request->token;
        $c_ids = $request->c_id;
        $trip_costs = $request->trip_cost;
        $trip_cost_cmts = $request->trip_cost_cmt ?? "NA";
        $multiple_payment_tax = $request->multiple_payment_tax;
        $fromManual = $request->from_manual ?? false;

        $arr = [];
        $arrDev = [];

        if ($fromManual === true) {
            $existingBooking = TripBooking::where('token', $token)->first();

            if (!$existingBooking) {
                return response()->json(['error' => 'Booking not found'], 400);
            }

            $existingTripCosts = json_decode($existingBooking->trip_cost, true);

            foreach ($c_ids as $key => $c_id) {
                foreach ($existingTripCosts as &$tripCost) {
                    if ($tripCost['c_id'] == $c_id) {
                        $tripCost['comment'] = $trip_cost_cmts[$key] ?? $tripCost['comment'];
                    }
                }
            }

            // Save the updated JSON back to the database
            $existingBooking->update([
                'trip_cost' => json_encode($existingTripCosts),
            ]);
            

            return response()->json(['success' => true, 'message' => 'Comments updated successfully.']);
        }

        foreach($c_ids as $key=>$c_id){

            $data = ['c_id'=>$c_id, 'cost'=>$trip_costs[$key], 'comment'=>$trip_cost_cmts[$key] ?? "NA", 'multiple_payment_tax'=>$multiple_payment_tax[$key]];
            array_push($arr, $data);

            // for deviation
            $deviation_amt = "deviation_amt_".$c_id;
            $deviation_type = "deviation_type_".$c_id;
            $deviation_comment = "deviation_comment_".$c_id;

            if(isset($request->$deviation_amt)){
                $dataDev = ['c_id'=>$c_id, 'deviation_type'=>$request->$deviation_type, 'deviation_amt'=> $request->$deviation_amt, 'deviation_comment'=>$request->$deviation_comment ?? "NA"];
                array_push($arrDev, $dataDev);
            }
        }

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['trip_cost' => json_encode($arr), 'trip_deviation' => json_encode($arrDev)]
        );
        if (!$bookingData->wasRecentlyCreated) {
            $action = "<b>" . Auth::user()->name . "</b> has Updated Trip costs: ";
            foreach ($arr as $cost) {
                $action .= "Customer ID: {$cost['c_id']}, Cost: {$cost['cost']}, Comment: {$cost['comment']}, Tax: {$cost['multiple_payment_tax']}; ";
            }
            $action .= " and Deviation: ";
            foreach ($arrDev as $deviation) {
                $action .= "Customer ID: {$deviation['c_id']}, Type: {$deviation['deviation_type']}, Amount: {$deviation['deviation_amt']}, Comment: {$deviation['deviation_comment']}; ";
            }
            $action = rtrim($action, '; '); // Remove the trailing semicolon and space
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();

        }

        return true;
    }

    public function creditNoteAmt(Request $request){
        $token = $request->token;
        $total_amount = $request->total_amount;
        //add condition here for check do not accept greater amount from total

        $data = TripBooking::where('token', $token)->first();

        if($data && $data->form_submited == 0){
            $creditNoteList = [];
            if($data->payment_all_done_by_this && $data->payment_by_customer_id){
                $credit_note = getCustomerById($data->payment_by_customer_id)->credit_note_wallet;
                if($credit_note > $total_amount){
                    $credit_note = $total_amount;
                }
                if($credit_note){
                    array_push($creditNoteList, ['traveler'=>$data->payment_by_customer_id, 'credit_note'=>$credit_note]);
                }
            }else{
                $tripCustomers = json_decode($data->customer_id);
                foreach($tripCustomers as $c_id){
                    $credit_note = getCustomerById($c_id)->credit_note_wallet;
                    if($credit_note > $total_amount){
                        $credit_note = $total_amount;
                    }
                    if($credit_note){
                        array_push($creditNoteList, ['traveler'=>$c_id, 'credit_note'=>$credit_note]);
                    }
                }
            }

            $bookingData = TripBooking::updateOrCreate(
                ['token' => $token],
                ['redeem_credit_note_amt' => json_encode($creditNoteList)]
            );
            if (!$bookingData->wasRecentlyCreated) {
                $action = "<b>".Auth::user()->name . "</b> has Updated Credit Note Amount to $total_amount";
                $activity = new BookingLog();
                $activity->admin_id = Auth::user()->id;
                $activity->booking_id = $bookingData->id;
                $activity->page = "Booking Form";
                $activity->action = $action;
                $activity->save();
    
            }
        }

        return true;
    }

    public function extraServices(Request $request){
        $token = $request->token;
        $extra_traveler = $request->extra_traveler;
        $extra_services = $request->extra_services;
        $extra_amount = $request->extra_amount;
        $extra_markup = $request->extra_markup;
        $extra_comment = $request->extra_comment ?? "NA";
        $extra_tax = $request->extra_tax;
        $arr = [];
        if($extra_traveler){
            foreach($extra_traveler as $key=>$c_id){

                $data = ['traveler'=>$c_id, 'services'=>$extra_services[$key], 'amount'=>$extra_amount[$key], 'markup'=>$extra_markup[$key], 'tax'=>$extra_tax[$key], 'comment'=>$extra_comment[$key] ?? "NA"];
                array_push($arr, $data);
            }
        }

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['extra_services' => json_encode($arr)]
        );

        if (!$bookingData->wasRecentlyCreated) {
            // Convert each sub-array in $arr to a readable string
            $arrString = collect($arr)->map(function ($item) {
                return collect($item)->map(fn($value, $key) => ucfirst($key) . ': ' . ($value ?? 'N/A'))->join(', ');
            })->join('; ');
        
            $action = "<b>" . Auth::user()->name . "</b> has Updated Extra services to " . $arrString;
        
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();
        }

        return true;
    }

    public function schPayment(Request $request){
        $token = $request->token;
        $amount = $request->sch_amount;
        $date = $request->sch_date;
        $cmt = $request->sch_comment;

        $arr = [];
        foreach($amount as $key=>$amt){
            $data = ['amount'=>$amt, 'date'=>$date[$key],'comment'=>$cmt[$key] ?? "NA"];
            array_push($arr, $data);
        }

        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            ['sch_payment_list' => json_encode($arr)]
        );
        if (!$bookingData->wasRecentlyCreated) {
            // Convert each sub-array in $arr to a readable string
            $arrString = collect($arr)->map(function ($item) {
                return "Amount: {$item['amount']}, Date: {$item['date']}, Comment: " . ($item['comment'] ?? 'NA');
            })->join('; ');
        
            $action = "<b>" . Auth::user()->name . "</b> has Updated Schedule Payment to: " . $arrString;
        
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $bookingData->id;
            $activity->page = "Booking Form";
            $activity->action = $action;
            $activity->save();
        }

        return true;
    }

    public function taxRequired(Request $request){
        $token = $request->token;

        if($request->tax_required){
            $tax = 'Yes';
        }else{
            $tax = 'No';
        }

        if($request->tax_type == "all"){
            $bookingData = TripBooking::updateOrCreate(
                ['token' => $token],
                ['tax_required' => $request->tax_required]
               
            );
            if (!$bookingData->wasRecentlyCreated) {
                $action = "<b>" . Auth::user()->name . "</b> has Updated Tax Required to: $request->tax_required";
                $activity = new BookingLog();
                $activity->admin_id = Auth::user()->id;
                $activity->booking_id = $bookingData->id;
                $activity->page = "Booking Form";
                $activity->action = $action;
                $activity->save();
            }
        }elseif($request->tax_type == "tds"){
            $bookingData = TripBooking::updateOrCreate(
                ['token' => $token],
                ['is_tds' => $request->tax_required]
            );
             if(!$bookingData->wasRecentlyCreated) {
                $action = "<b>".Auth::user()->name . "</b> has Updated Is TDS to : $request->tax_required";
                $activity = new BookingLog();
                $activity->admin_id = Auth::user()->id;
                $activity->booking_id = $bookingData->id;
                $activity->page = "Booking Form";
                $activity->action = $action;
                $activity->save();
    
            }
           
        }
        return true;
    }

    public function formSubmited(Request $request){ 
    
        $token = $request->token;
        $booking = TripBooking::where('token', $token)->first();
        $formSubmitedOldValue = $booking->form_submited;
        $bookingData = TripBooking::updateOrCreate(
            ['token' => $token],
            [
                'form_submited' => $request->form_submited, 
                'payable_amt'=>$request->payable_amount, 
                'is_form_submitted'=> 0,
                'invoice_status'=>'Pending',
                'trip_status'=>'Confirmed',
                'admin_id'=> Auth::user()->id
            ]
        );

        if($booking->redeem_credit_note_amt && $booking->form_submited == 0){
            $tripCustomers = json_decode($booking->redeem_credit_note_amt);
            foreach($tripCustomers as $credit){
                $credit_note = $credit->credit_note;

                if($credit_note){
                    $customer = Customer::find($credit->traveler);
                    $customer->credit_note_wallet = $customer->credit_note_wallet - $credit_note;
                    $customer->save();
                }
            }
        }


        $customers = json_decode($booking->customer_id) ?? null;
        $cList = "";
        foreach($customers as $cK=>$customer){
            $cList .= getCustomerById($customer)->name;
            if(($cK+1) != count($customers)){
                $cList .= ", ";
            }
        }              

        // Activity Tracker
        if($booking && $formSubmitedOldValue == 1){
            $action = "<b>".Auth::user()->name . "</b> has Updated The Booking For <b>".$cList."<b>";
        }else{
            $action = "<b>".Auth::user()->name . "</b> has Created new Booking For <b>".$cList."<b>";
        }
        // Activity Tracker

        $activity = new BookingLog();
        $activity->admin_id = Auth::user()->id;
        $activity->booking_id = $bookingData->id;
        $activity->page = "Booking Form";
        $activity->action = $action;
        $activity->save();

        if($formSubmitedOldValue == 0){
            foreach(json_decode($booking->customer_id) as $traveler){
                $customer = getCustomerById($traveler);
                // $url = env('USER_URL') .'registration?token=' . $booking->token . '&email=' . $customer->email . '&trip_id=' . $booking->trip_id;
                $url="https://www.adventuresoverland.com/booking-registration-form/";
                $adminName = Auth::user()->name;
                $data = [
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'paid_amt' => $booking->payment_amt ?? 0,
                    'slot' => count(json_decode($booking->customer_id)),
                    'trip'=> $booking->trip->name .' ('.$booking->trip->start_date .' - '.$booking->trip->end_date.')',
                    'link'=> $url,
                    'booking_id'=>$bookingData->id,
                    'for'=>'customer'
                ];
                if(setting('mail_status') == 1){
                    event(new SendMailEvent("$customer->email", 'Thanks for choosing Adventures Overland!', 'emails.trip-booking', $data));
                }

                // =========== email ==================
                $accountEmail = setting('account_mail');
                $opsEmail = setting('operation_mail');
                $extraMail = config('app.ExtraMail');

                if($extraMail){
                    $data = [
                        'email' => $customer->email,
                        'name' => $customer->name,
                        'paid_amt' => $booking->payment_amt ?? "N/A",
                        'slot' => count(json_decode($booking->customer_id)),
                        'spoc_person' => $adminName,
                        'trip_name' => $booking->trip->name ?? "N/A",
                        'admin_email'=>$accountEmail,
                    ];
                    if(setting('mail_status') == 1){
                        event(new SendMailEvent("$extraMail", 'New Booking Received from '.$customer->name.' for '. $booking->trip->name .' !', 'emails.admin-booking-confirm', $data));
                    }
                }

                //mail to account
                if($accountEmail){
                    $data = [
                        'email' => $customer->email,
                        'name' => $customer->name,
                        'spoc_person' => $adminName,
                        'paid_amt' => $booking->payment_amt ?? 0,
                        'trip_name' => $booking->trip->name ?? "Trip",
                        'admin_email'=>$accountEmail,
                    ];
                    if(setting('mail_status') == 1){
                        event(new SendMailEvent("$accountEmail", 'New Booking Recieved from '.$customer->name.' for '. $booking->trip->name .' !', 'emails.admin-booking-confirm', $data));
                    }
                }

                //mail to operation
                if($opsEmail){
                    $data = [
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                        'name' => $customer->name,
                        'spoc_person' => $adminName,
                        'slot' => count(json_decode($booking->customer_id)),
                        'paid_amt' => $booking->payment_amt ?? "N/A",
                        'trip_name' => $booking->trip->name ?? "N/A",
                        'admin_email'=>$opsEmail,
                    ];
                    if(setting('mail_status') == 1){
                        event(new SendMailEvent("$opsEmail", 'New Booking Recieved from '.$customer->name.' for '. $booking->trip->name .' !', 'emails.admin-booking-confirm', $data));
                    }
                }
                // =========== email ==================
                if(setting('whatsapp_status') == 1){
                    $client = new Client();
                    $response = $client->request('POST', 'https://live-server-8452.wati.io/api/v1/sendTemplateMessage?whatsappNumber='.$customer->phone, [
                        'body' => '{"broadcast_name":"booking_thanks_new1","parameters":[{"name":"trip_name","value":"'.$booking->trip->name.'"},{"name":"name","value":"'.$customer->name.'"}],"template_name":"booking_thanks_new1"}',
                        'headers' => [
                            'Authorization' => env('WATI_AUTH'),
                            'content-type' => 'text/json',
                        ],
                    ]);
                }
            }
        }
       
        return true;
    }

    public function summary(Request $request){
        $token = $request->token;

        $data = TripBooking::where('token', $token)->first();
       
        //point distribution Accrding trip status Completed and remaining balance 0
        $carbonAmtSum = TripCarbonInfo::where('booking_id', $data->id)->sum('donation_amt') ?? 0;
        $total = $data->payable_amt;

        $firstPay = $data->payment_amt;
        $partAmt = 0;
        if($data->part_payment_list){
            foreach(json_decode($data->part_payment_list) as $pp){
                $partAmt+=$pp->amount;
                
            }
        }
        if($data->points_distributed==Null){
            $rmBal = $total + $carbonAmtSum - ($firstPay + $partAmt);
            if($data->trip->end_date < date("Y-m-d") && $rmBal==0)
            {
                if($data->payment_all_done_by_this == 1 && $data->payment_by_customer_id != null){
                    $payablePoints=0;
                    $allCustomers = $data->customer_id;
    
                    foreach (json_decode($allCustomers) as $customerId) {
                        $tripCostForCustomer = getTripCostWithoutTaxByBookingAndCustomerId($data->id, $customerId);
                        $customerPoints = round(($tripCostForCustomer * getPointPerByCustomerId($data->id, $customerId)) / 100);
                        $payablePoints += $customerPoints;
                    }
                   
                    $customer = Customer::find($data->payment_by_customer_id);
                    $customer->points = $customer->points + $payablePoints;
                
                    $customer->save();
    
                    $pointModal = new LoyalityPts();
                    $pointModal->customer_id = $data->payment_by_customer_id;
                    $pointModal->admin_id = Auth::user()->id;
                    $pointModal->reason = 'Trip Completed';
                    $pointModal->trip_name = $data->trip->name ?? "Trip Deleted";
                    $pointModal->cost = $data->trip->price ?? "Trip Deleted";
                    $pointModal->expiry_date = date('Y-m-d', strtotime('+2 year'));
                    $pointModal->trans_type = 'Cr';
                    $pointModal->trans_amt = $payablePoints;
                    $pointModal->balance = $payablePoints;
                    $pointModal->status = 'Approved';
                    $pointModal->trans_page = 'TripCompleted';
                    $pointModal->save();
                }
                else
                {
                // send points provide to each customer
                    if($data->customer_id){
                        foreach(json_decode($data->customer_id) as $c_id){
                            $cusCheck = getCustomerByid($c_id)->parent;
                           
                            $payablePoints = round((getTripCostWithoutTaxByBookingAndCustomerId($data->id, $c_id) * getPointPerByCustomerId($data->id,$c_id))/100);
                            
                            $customer = Customer::find($c_id);
                            $customer->points = $customer->points + $payablePoints;
                            $customer->save();
    
                            $pointModal = new LoyalityPts();
                            $pointModal->customer_id = $c_id;
                            $pointModal->token = $data->token;
                            $pointModal->admin_id = Auth::user()->id;
                            $pointModal->reason = 'Trip Completed';
                            $pointModal->trip_name = $data->trip->name ?? "Trip Deleted";
                            $pointModal->cost = $data->trip->price ?? "Trip Deleted";
                            $pointModal->expiry_date = date('Y-m-d', strtotime('+2 year'));
                            $pointModal->trans_type = 'Cr';
                            $pointModal->trans_amt = $payablePoints;
                            $pointModal->balance = $payablePoints;
                            $pointModal->status = 'Approved';
                            $pointModal->trans_page = 'TripCompleted';
                            $pointModal->save();
                        }
                    }
                }

                $data->points_distributed = 1; // Assuming this field exists in the database
                $data->save();
    
            }
        }
        //point distribution Accrding trip status Completed and remaining balance 0
        $result = [];

        if($data){
            // trip cost
            if($data->trip_cost != null){
                $tripCostData = json_decode($data->trip_cost);

                $tripCost = [];

                foreach ($tripCostData as $trip) {
                    $c_id = $trip->c_id;
                    if (isset($tripCost[$c_id])) {
                        $tripCost[$c_id]->cost += $trip->cost;
                    } else {
                        $tripCost[$c_id] = $trip;
                       
                        $tripCost[$c_id]->cost = (int)$trip->cost; // Convert cost to integer for summing
                    }
                }

                foreach($tripCost as $key=>$tc){

                    $tc->traveler = getCustomerById($tc->c_id)->name;
                    $tc->parent = getCustomerById($tc->c_id)->parent;
                    $tc->relation = getCustomerById($tc->c_id)->relation;
                    $tc->vehicle_amt = $data->vehical_seat_amt ?? 0;
                    $tc->room_amt = $data->room_type_amt ?? 0;
                    $tc->comment = $data->vehicle_type_other_cmt ?? "NA";
                }
            }else{
                $tripCost = [];
            }
            $result['trip_costs'] = $tripCost;
            // trip cost

            // credit note amount
            $creditNoteList = [];
            if($data->customer_id != null){
                if($data->form_submited){
                    if($data->redeem_credit_note_amt){
                        $credits = json_decode($data->redeem_credit_note_amt);
                        foreach($credits as $credit){
                            $traveler = getCustomerById($credit->traveler)->name;
                            array_push($creditNoteList, ['traveler'=>$traveler, 'credit_note'=>$credit->credit_note]);
                        }
                    }
                }else{
                    $tripCustomers = json_decode($data->customer_id);
                    $paymentByCust = $data->payment_by_customer_id;
                    if($paymentByCust && $data->payment_all_done_by_this){
                        $traveler = getCustomerById($paymentByCust)->name;
                        $credit_note = getCustomerById($paymentByCust)->credit_note_wallet;
                        array_push($creditNoteList, ['traveler'=>$traveler, 'credit_note'=>$credit_note]);
                    }else{
                        foreach($tripCustomers as $c_id){
                            $traveler = getCustomerById($c_id)->name;
                            $credit_note = getCustomerById($c_id)->credit_note_wallet;
                            array_push($creditNoteList, ['traveler'=>$traveler, 'credit_note'=>$credit_note]);
                        }
                    }
                }
            }
            $result['credit_note_amounts'] = $creditNoteList;
            // credit note amount

            // deviations
            if($data->trip_deviation != null){
                $tripDev = json_decode($data->trip_deviation);
                foreach($tripDev as $dev){
                    $dev->traveler = getCustomerById($dev->c_id)->name;
                    $dev->parent = getCustomerById($dev->c_id)->parent;
                    $dev->relation = getCustomerById($dev->c_id)->relation;
                }
            }else{
                $tripDev = [];
            }
            $result['deviation'] = $tripDev;
            // deviations

            // extra services
            if($data->trip_cost != null && $data->extra_services != null){
                $extraServices = json_decode($data->extra_services);
                foreach($extraServices as $es){
                    $es->traveler_name = getCustomerById($es->traveler)->name;
                    $es->extra_charges = $es->amount + $es->markup + (($es->markup*$es->tax)/100);
                }
            }else{
                $extraServices = [];
            }
            $result['extra_services'] = $extraServices;
            // extra services
            //vehicle sec amount
            $result['vehicle_security'] = ['amount'=>$data->vehical_security_amt, 'comment'=>$data->vehical_security_amt_cmt];
            $result['vehicle_seat_charge'] = $data->vehical_seat_amt;
            $result['vehicle_seat'] = $data->vehical_seat;
            $result['vehicle_type'] = $data->vehical_type;

            //room info
            $result['room_info'] = json_decode($data->room_info) ?? [""];

            // sum of package B
            $packageBSum = 0;
            if(count($result['room_info'])){
                foreach($result['room_info'] as $packB){
                    if($packB && $packB->room_type_amt){
                        $packageBSum += $packB->room_type_amt;
                    }
                }
            }
            $packageBSum += $data->vehical_seat_amt;
            if(count($tripCost) > 0){
                $travellerCount = count($tripCost);
            }else{
                $travellerCount = 1;
            }
            $perTravellerPackageB = $packageBSum/$travellerCount;
            // Package B


            // tax
            if($data->trip_id && $data->customer_id && $data->trip_cost && ($data->tax_required == null || $data->tax_required == 0)){
                $gst = setting('gst') ?? 0;
                $tcs = setting('tcs') ?? 0;

                $tripTaxData = json_decode($data->trip_cost);
                $tripTax = [];
                foreach ($tripTaxData as $trip) {
                    $c_id = $trip->c_id;

                    if (isset($tripTax[$c_id])) {
                        $tripTax[$c_id]->cost += $trip->cost;
                    } else {
                        $tripTax[$c_id] = $trip;
                        $tripTax[$c_id]->cost = (int)$trip->cost; // Convert cost to integer for summing
                    }
                }

                $gsts = [];
                foreach($tripTax as $tt){
                    $netCost = $tt->cost + $perTravellerPackageB;
                    $traveler = getCustomerById($tt->c_id)->name;
                    $gst_amt = (($netCost*$gst)/100);
                    $gst_per = $gst;
                    array_push($gsts, ['c_id'=>$tt->c_id,'traveler'=>$traveler, 'gst'=>$gst_amt, 'gst_per'=>$gst_per]);
                }

                $tripTax = json_decode($data->trip_cost);
                $tcss = [];
                $checkBAmt = [];

                foreach($tripTax as $tt){

                    if (isset($checkBAmt[$tt->c_id])) {
                        $netCost = $tt->cost + 0;
                    } else {
                        $checkBAmt[$tt->c_id] = $tt;
                        $netCost = $tt->cost + $perTravellerPackageB;
                    }

                    $tooltip = " ";

                    $traveler = getCustomerById($tt->c_id)->name;
                    if($data->payment_from == "Individual" && $data->payment_from_tax != null && $data->is_multiple_payment == 0){
                        if($data->payment_from_tax == "Auto"){
                            if($netCost > 1000000){
                                $netCost1 = 1000000;
                                $netCost2 = $netCost - $netCost1;
                                $tcs_amt1 = (($netCost1*5)/100);
                                $tcs_amt2 = (($netCost2*20)/100);
                                $tcs_amt = $tcs_amt1 + $tcs_amt2;
                                $tooltip .= "$netCost1 5% = ".$tcs_amt1;
                                $tooltip .= ", $netCost2 20% = ".$tcs_amt2;
                            }else{
                                $tcs_amt = (($netCost*5)/100);
                                $tooltip .= "$netCost 5% = ".$tcs_amt;
                            }
                            $tcs_per = $data->payment_from_tax;
                        }
                        else if($data->payment_from_tax == "Manual"){
                            $tcs1_amt = (($tt->amount_1*$tt->tcs_1)/100);
                            $tcs2_amt = 0;
                            $tooltip .= $tt->amount_1 ." $tt->tcs_1% = ".$tcs1_amt;
                            if($tt->tcs_2 != null){
                                $tcs2_amt = (($tt->amount_2*$tt->tcs_2)/100);
                                $tooltip .= ','.$tt->amount_2 ." $tt->tcs_2% = ".$tcs2_amt;
                            }
                            $tcs_amt = $tcs1_amt + $tcs2_amt;
                            $tcs_per = $data->payment_from_tax;
                        }
                        else{
                            $tcs_amt = (($netCost*$data->payment_from_tax)/100);
                            $tcs_per = $data->payment_from_tax;
                            $tooltip .= $netCost ." $data->payment_from_tax% = ".$tcs_amt;
                        }
                    }elseif($data->payment_from == "Individual" && $data->is_multiple_payment == 1){
                        if($tt->multiple_payment_tax == "Auto"){
                            if($netCost > 1000000){
                                $netCost1 = 1000000;
                                $netCost2 = $netCost - $netCost1;
                                $tcs_amt1 = (($netCost1*5)/100);
                                $tcs_amt2 = (($netCost2*20)/100);
                                $tcs_amt = $tcs_amt1 + $tcs_amt2;
                                $tooltip .= "$netCost1 5% = ".$tcs_amt1;
                                $tooltip .= ", $netCost2 20% = ".$tcs_amt2;
                            }else{
                                $tcs_amt = (($netCost*5)/100);
                                $tooltip .= "$netCost 5% = ".$tcs_amt;
                            }
                            $tcs_per = $tt->multiple_payment_tax;
                        }
                        else if($data->payment_from_tax == "Manual"){
                            $tcs1_amt = (($tt->amount_1*$tt->tcs_1)/100);
                            $tcs2_amt = 0;
                            $tooltip .= $tt->amount_1 ." $tt->tcs_1% = ".$tcs1_amt;
                            if($tt->tcs_2 != null){
                                $tcs2_amt = (($tt->amount_2*$tt->tcs_2)/100);
                                $tooltip .= ','.$tt->amount_2 ." $tt->tcs_2% = ".$tcs2_amt;
                            }
                            $tcs_amt = $tcs1_amt + $tcs2_amt;
                            $tcs_per = $data->payment_from_tax;
                        }
                        else{
                            $tcs_amt = (($netCost*$tt->multiple_payment_tax)/100);
                            $tcs_per = $tt->multiple_payment_tax;
                            $tooltip .= $netCost ." $tt->multiple_payment_tax% = ".$tcs_amt;
                        }
                    }elseif($data->payment_from == "Company"){
                        if($data->is_tds == 0){
                            $tcs = 0;
                        }
                        $tcs_amt = (($netCost*$tcs)/100);
                        $tcs_per = $tcs;
                        $tooltip .= $netCost ." $tcs% = ".$tcs_amt;
                    }

                    if($data->payment_from == "Company" || $data->payment_from == "Individual" || $data->payment_from == "Manual"){
                        array_push($tcss, ['c_id'=>$tt->c_id, 'traveler'=>$traveler, 'tcs'=>$tcs_amt, 'tcs_per'=>$tcs_per, 'tooltip'=>$tooltip]);
                    }
                }
                $tripTax = ['gst'=>$gsts, 'tcs'=>$tcss];
            }else{
                $tripTax = [];
            }
            $result['taxes'] = $tripTax;
            // tax

            // redeemable points
            if($data->redeem_points){
                $rps = json_decode($data->redeem_points);
                if($rps){
                    foreach($rps as $rp){
                        $rp->traveler = getCustomerById($rp->c_id)->name;
                    }
                    $result['points'] = $rps;
                }
            }else{
                $result['points'] = 0;
            }
            // redeemable points

            // package offered A
            if($data->trip_cost != null){
                $packageOfferAData = json_decode($data->trip_cost);

                $packageOfferA = [];
                foreach ($packageOfferAData as $trip) {
                    $c_id = $trip->c_id;

                    if (isset($packageOfferA[$c_id])) {
                        $packageOfferA[$c_id]->cost += $trip->cost;
                    } else {
                        $packageOfferA[$c_id] = $trip;
                        $packageOfferA[$c_id]->cost = (int)$trip->cost; // Convert cost to integer for summing
                    }
                }

                $rps = json_decode($data->redeem_points);
                $devs = json_decode($data->trip_deviation);

                foreach($packageOfferA as $tc){
                    $tc->traveler = getCustomerById($tc->c_id)->name;
                    if($rps){
                        foreach($rps as $rp){
                            if($rp->c_id == $tc->c_id){
                                $tc->cost = $tc->cost - $rp->points;
                            }
                        }
                    }
                    if($devs){
                        foreach($devs as $dev){
                            if($dev->c_id == $tc->c_id){
                                if($dev->deviation_type == "Add"){
                                    $tc->cost += $dev->deviation_amt;
                                }else{
                                    $tc->cost -= $dev->deviation_amt;
                                }
                            }
                        }
                    }
                }
            }else{
                $packageOfferA = [];
            }
            $result['package_offer_A'] = $packageOfferA;
            // package offered A

            // A + B
            if($data->trip_cost != null){
                $A_BData = json_decode($data->trip_cost);

                $A_B = [];
                foreach ($A_BData as $trip) {
                    $c_id = $trip->c_id;

                    if (isset($A_B[$c_id])) {
                        $A_B[$c_id]->cost += $trip->cost;
                    } else {
                        $A_B[$c_id] = $trip;
                        $A_B[$c_id]->cost = (int)$trip->cost; // Convert cost to integer for summing
                    }
                }

                $rps = json_decode($data->redeem_points);
                $devs = json_decode($data->trip_deviation);
                foreach($A_B as $tc){
                    $tc->traveler = getCustomerById($tc->c_id)->name;
                    $tc->cost = $tc->cost + $perTravellerPackageB;
                    // dd($tc->cost);
                    if($rps){
                        foreach($rps as $rp){
                            if($rp->c_id == $tc->c_id){
                                $tc->cost = $tc->cost - $rp->points;
                            }
                        }
                    }
                    if($devs){
                        foreach($devs as $dev){
                            if($dev->c_id == $tc->c_id){
                                if($dev->deviation_type == "Add"){
                                    $tc->cost += $dev->deviation_amt;
                                }else{
                                    $tc->cost -= $dev->deviation_amt;
                                }
                            }
                        }
                    }
                }
            }else{
                $A_B = [];
            }
            $result['a_and_b'] = $A_B;
            // A + B

            // C
            if($data->trip_cost != null){
                $packageCData = json_decode($data->trip_cost);

                $packageC = [];
                foreach ($packageCData as $trip) {
                    $c_id = $trip->c_id;

                    if (isset($packageC[$c_id])) {
                        $packageC[$c_id]->cost += $trip->cost;
                    } else {
                        $packageC[$c_id] = $trip;
                        $packageC[$c_id]->cost = (int)$trip->cost; // Convert cost to integer for summing
                    }
                }

                $rps = json_decode($data->redeem_points);
                $devs = json_decode($data->trip_deviation);
                foreach($packageC as $tc){
                    $tc->traveler = getCustomerById($tc->c_id)->name;
                    $tc->cost = $tc->cost + $perTravellerPackageB;
                    if($rps){
                        foreach($rps as $rp){
                            if($rp->c_id == $tc->c_id){
                                $tc->cost = $tc->cost - $rp->points;
                            }
                        }
                    }
                    if($devs){
                        foreach($devs as $dev){
                            if($dev->c_id == $tc->c_id){
                                if($dev->deviation_type == "Add"){
                                    $tc->cost += $dev->deviation_amt;
                                }else{
                                    $tc->cost -= $dev->deviation_amt;
                                }
                            }
                        }
                    }

                    if(isset($tripTax['gst'])){
                        foreach($tripTax['gst'] as $gst){
                            if($gst['c_id'] == $tc->c_id){
                                $tc->cost = $tc->cost + $gst['gst'];
                            }
                        }
                    }
                    if(isset($tripTax['tcs'])){
                        foreach($tripTax['tcs'] as $tcs){
                            if($tcs['c_id'] == $tc->c_id){
                                if($tcs['tcs_per'] == 2){
                                    $tc->cost = $tc->cost - $tcs['tcs'];
                                }else{
                                    $tc->cost = $tc->cost + $tcs['tcs'];
                                }
                            }
                        }
                    }
                }
            }else{
                $packageC = [];
            }
            $result['package_c'] = $packageC;
            // C

            // advance payment
            if($data->payment_amt && $data->payment_date){
                $result['advance_payment'] = ['payment' =>$data->payment_amt, 'date'=> date("d M, y", strtotime($data->payment_date))];
            }else{
                $result['advance_payment'] = [];
            }
            // payment

            // part payment
            if(isset($data->part_payment_list)){
                $pp = json_decode($data->part_payment_list);
                foreach($pp as &$p){
                    $p->date = date("d M, Y", strtotime($p->date));
                }
                $result['part_payment'] = $pp ?? null;
            }else{
                $result['part_payment'] = [];
            }
            // payment


            // extra services redeemable
            if($data->trip_cost != null && $data->extra_services != null){
                $extraServices = json_decode($data->extra_services);
                $newExtraServices = [];
                foreach($extraServices as $es){
                    if(getExtraServiceByName($es->services) && getExtraServiceByName($es->services)->is_redeemable){
                        $es->traveler_name = getCustomerById($es->traveler)->name;
                        $es->extra_charges = $es->amount;
                        array_push($newExtraServices, $es);
                    }
                }
            }else{
                $newExtraServices = [];
            }
            $result['extra_services_redeemable'] = $newExtraServices;
            // extra services redeemable

            // carbon info E
            $carbonArr = [];
            if($data->customer_id){
                foreach(json_decode($data->customer_id) as $customer){
                    $carbonCheck = TripCarbonInfo::where('customer_id', $customer)->where('booking_id', $data->id)->first();
                    if($carbonCheck){
                        $carbonArr[] = ['customer_id'=>$customer, 'customer_name'=>getCustomerById($customer)->name, 'amount' => $carbonCheck->donation_amt];
                    }
                }
            }
            $result['carbon_infos'] = $carbonArr;
            // carbon info E

            // points calc
            if($data->customer_id){
                $pointsArr = [];
                $actualTripCost = [];
              
          
                foreach(json_decode($data->customer_id) as $customer){
                    $actualCost = getTripCostWithoutTaxByBookingAndCustomerId($data->id, $customer);
                    // actual trip cost
                    array_push($actualTripCost, ['traveler'=>getCustomerById($customer)->name ?? "Deleted", 'cost'=>$actualCost]);
                     $bookingId=$data->id;
                    $cDataa = getCustomerById($customer)->parent;
                    $cTier = getPointPerByCustomerId($bookingId, $customer);
                    $points = ($actualCost * $cTier)/100;
                    if($cDataa == 0 && !isset($pointsArr[$customer])){
                        array_push($pointsArr, [$customer => ['points'=>$points, 'name'=>getCustomerById($customer)->name,'reward'=>$cTier]]);
                    }
                    else{
                        if ($pointsArr && array_key_exists($cDataa, $pointsArr[0])) {
                            $pointsArr[0][$cDataa]['points'] = $pointsArr[0][$cDataa]['points'] + $points; 
                        }
                    }

                    if($data->payment_all_done_by_this == 1 && $data->payment_by_customer_id != null){
                        $totalpoints=[];
                        $payablePoints=0;
                        $allCustomers = $data->customer_id;
                        $first_name=getCustomerById($data->payment_by_customer_id)->first_name;
                        $last_name=getCustomerById($data->payment_by_customer_id)->last_name;
                        $full_name=$first_name.' '.$last_name;
                        // dd($full_name);
                        foreach (json_decode($allCustomers) as $customerId) {
                            $tripCostForCustomer = getTripCostWithoutTaxByBookingAndCustomerId($data->id, $customerId);
                            $customerPoints = round(($tripCostForCustomer * getPointPerByCustomerId($bookingId,$customerId)) / 100); 
                            $payablePoints += $customerPoints;
                        }
                        array_push($totalpoints, ['all_points_send_to_paid_customer'=>$payablePoints,'name'=>$full_name]);
                        $result['all_payment_payer'] = $totalpoints;

                    }
                }
              

                $result['points_list'] = $pointsArr;
                $result['actual_trip_cost'] = $actualTripCost;
                $result['real_trip_amt'] = $data->trip->price ?? "0";

            }
            return json_encode($result);
        }
    }

    public function export()
    {
        // Fetch data from the Customer model
        if (session()->has('trip_query')) {
            $datas = session()->get('booking_query');
        }else{
            $datas = TripBooking::where('trip_status', '!=', 'Draft')->orderby('id','Desc')->get();
        }



        // Prepare data for export
        $data = [];
        $data[] = ['Booked By', 'Created Date', 'Booking For', 'Customers', 'Lead Source', 'Sub Lead Source', 'Expedition','Relation Manager','Trip', 'Vehicle Type', 'Vehicle Seat', 'Vehicle Seat Amount', 'Vehicle Security Amount', 'Vehicle Amount Comment', 'Room Type', 'Room Type Amount', 'Room Category', 'Payment From', 'Company', 'TCS', 'Complete Payment By', 'Payment Type', 'Payment Amount', 'Payment Date', 'Trip Cost', 'Extra Services', 'Tax Required', 'Payable Amount', 'Trip Status', 'Invoice File', 'Invoice Status', 'Invoice Sent Date', 'Redeem Points', 'Cancellation Reason', 'Cancellation Amount', 'Cancellation Date'];

        foreach ($datas as $item) {
            if (!$item || !$item->trip) {
                Log::error("Invalid booking object or missing trip for booking ID: " . ($item->id ?? 'Unknown'));
                continue;
            }
           
            $customers = json_decode($item->customer_id) ?? null;
            $cList = "";
            foreach($customers as $customer){
                $cList .= (getCustomerById($customer)->name ?? " ") .", ";
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
                    $tripCost .= (getCustomerById($tc->c_id)->name ?? " ")." : "."₹".$tc->cost .", ";
                }
            }

            $extraServices = "";
            if($item->extra_services){
                foreach(json_decode($item->extra_services) as $tc){
                    $extraServices .= (getCustomerById($tc->traveler)->name ?? " ")." : ".$tc->services." : "." ₹".$tc->amount." : ". "₹".$tc->markup." : ".$tc->tax."%"." : ".$tc->comment .", ";
                }
            }

            $redeemPoints = "";
            if($item->redeem_points){
                foreach(json_decode($item->redeem_points) as $tc){
                    $redeemPoints .= (getCustomerById($tc->c_id)->name ?? " ")." : ".$tc->points.", ";
                }
            }

             $relationManagerName = null;
            if ($item->trip && $item->trip->relation_manager_id) {
                $rmIds = json_decode($item->trip->relation_manager_id, true);
              
             
                if (is_array($rmIds)) {
                    $names = [];
                    foreach ($rmIds as $rmId) {
                   
                        if (isset($rmId)) {
                            $user = \App\Models\User::find($rmId);
                            
                        } else {
                            $user = \App\Models\User::find($rmId);
                        }
                        if ($user) {
                
                            $names[] = $user->name;
                        }
                    }
                    $relationManagerName = implode(', ', $names);
                  
                } else {
                
                    $user = \App\Models\User::find($rmIds);
                    $relationManagerName = $user ? $user->name : null;
                 
                }
            }
            $data[] = [
                $item->admin->name ?? null,
                $item->created_at ? date("d M, Y", strtotime($item->created_at)) : null,
                $item->booking_for,
                $cList,
                $item->lead_source,
                $item->sub_lead_source,
                $item->expedition,
                $relationManagerName, 
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
              $item->payment_by_customer_id ? (getCustomerById($item->payment_by_customer_id) ? getCustomerById($item->payment_by_customer_id)->name : null) : null,
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
        header('Content-Disposition: attachment;filename="bookings.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet data to a file
        $writer->save('php://output');
    }

    // -----------------------------------------
    // ------------- View Booking -------------
    // -----------------------------------------


    public function addPayment(Request $request)
    {
      
        try {

            if($request->amount > $request->rm_balance){
                return redirect()->back()->with('error', 'Payment Amount Should be greater than Remaining Balance');
            }
            $id = $request->id;
            $data = TripBooking::findOrFail($id);

            // Update part payment list
            $oldPartPayment = json_decode($data->part_payment_list) ?? [];
            $newData = [
                'payment_type' => $request->payment_type,
                'billing_cust' =>$request->billing_cust,
                'remark' => $request->remark,
                'comment' => $request->comment,
                'amount' => $request->amount,
                'date' => $request->date
            ];
            $oldPartPayment[] = $newData;
            $data->part_payment_list = json_encode($oldPartPayment);

            // Prepare activity log
            $action = "<b>".Auth::user()->name . "</b> has added Payment of RS. <b>".$request->amount."<b>";
            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $id;
            $activity->page = "View Booking";
            $activity->action = $action;

            $travelers = json_decode($data->customer_id);
            $emailsSent = 0;

            foreach ($travelers as $traveler) {
                $customer = getCustomerById($traveler);
                if (empty($customer->email)) {
                    continue;
                }

                // Send email to customer
                $mailData = [
                    'email' => $customer->email,
                    'name' => $customer->name,
                    'payment' => $request->amount,
                    'trip_name' => $data->trip->name ?? "Trip",
                    'date' => date("Y-m-d"),
                    'for' => 'customer',
                    'booking_id' => $data->id,
                ];

                if (setting('mail_status') == 1) {
                    try {
                        event(new SendMailEvent(
                            $customer->email,
                            'Your payment has been updated | Adventures Overland!',
                            'emails.add-payment',
                            $mailData
                        ));
                        $emailsSent++;

                        // Send email to Ops/Admin
                        $opsEmail = setting('operation_mail');
                        if ($opsEmail) {
                            $datas = [
                                'name' => $customer->name,
                                'admin_email' => $opsEmail,
                                'trip_name' => $data->trip->name ?? "Trip",
                                'payment' => $request->amount,
                                'date' => date("Y-m-d"),
                            ];
                            event(new SendMailEvent(
                                $opsEmail,
                                'Payment received for ' . $data->trip->name . ' from ' . $customer->name . '!',
                                'emails.admin-booking-payment',
                                $datas
                            ));
                        }

                    } catch (Exception $e) {
                        Log::error("Failed to send email to " . $customer->email . ": " . $e->getMessage());
                        throw new Exception("Failed to send email to " . $customer->email . ": " . $e->getMessage());
                    }
                }
            }

            // Fail if no emails were sent
            if ($emailsSent === 0 && setting('mail_status') == 1) {
                throw new Exception("Email(s) were not sent to any customer.");
            }

            // Save only after successful emails
            $data->save();
            $activity->save();
            $this->storePaymentHistory($data, $newData);


            Log::info("mail send to " . $customer->email . " for payment of " . $request->amount . " for trip " . $data->trip->name ?? "Trip");
            return redirect()->back()->with('success', 'Payment Updated and Emails Sent Successfully!');

        } catch (Exception $e) {
            Log::error('Payment update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment update failed: ' . $e->getMessage());
        }
    }
    
    public function storePaymentHistory($booking,$paymentData)
    {
        if (empty($booking)) {
            Log::error('Part Payment update failed because booking data is missing:  ' . $e->getMessage());
            return false;
        }
        if (empty($paymentData) || !is_array($paymentData)) {
            Log::error('Part Payment update failed because payment data is missing:  ' . $e->getMessage());
            return false;
        }
        PartPaymentHistory::create([
            'booking_id' => $booking->id,
            'trip_id'    => $booking->trip_id,
            'details'    => $paymentData,
        ]);

    }
    
    public function editPayment(Request $request)
    {
    
        $id=$request->id;
        $index=$request->index;
        $editdata = TripBooking::find($id);
        $oldPartPayment = json_decode($editdata->part_payment_list) ?? [];

        $data = TripBooking::where('token', $request->token)->first();
        $carbonAmtSum = TripCarbonInfo::where('booking_id', $data->id)->sum('donation_amt') ?? 0;
        $total = $data->payable_amt;
        $firstPay = $data->payment_amt;
        $partAmt = 0;
        if($data->part_payment_list){
            foreach(json_decode($data->part_payment_list) as $pp){
                $partAmt+=$pp->amount;
            }
        }
        $rmBal = $total + $carbonAmtSum - ($firstPay + $partAmt);

        $editPaymentData=$oldPartPayment[$index];
        if ($editPaymentData) {
            $editPaymentData->index = $index;
            $editPaymentData->rmBal = $rmBal;

        }


        return response()->json(['success' => true,'data'=>$editPaymentData]);
    }
    public function updatePartPayment(Request $request)
    {
        
            $id = $request->id;
            $index = $request->index_id;
          
            $data = TripBooking::find($id);

            if (!$data) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }

            $oldPartPayment = json_decode($data->part_payment_list, true) ?? [];

            if (isset($oldPartPayment[$index])) {
                $oldPartPayment[$index]['payment_type'] = $request->payment_type ?? $oldPartPayment[$index]['payment_type'];
                $oldPartPayment[$index]['remark'] = $request->remark ?? $oldPartPayment[$index]['remark'];
                $oldPartPayment[$index]['comment'] = $request->comment ?? $oldPartPayment[$index]['comment'];
                $oldPartPayment[$index]['amount'] = $request->amount ?? $oldPartPayment[$index]['amount'];
                $oldPartPayment[$index]['date'] = $request->date ?? $oldPartPayment[$index]['date'];
            } else {
                return redirect()->back()->with('error', 'Somthing Went Wrong With Payment');
            }

          
            $data->part_payment_list = json_encode($oldPartPayment);
            $data->save();
             // Activity Tracker
            $action = "<b>".Auth::user()->name . "</b> has updated Payment of RS. <b>".$request->amount."<b>";

            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $id;
            $activity->page = "View Booking";
            $activity->action = $action;
            $activity->save();
            // Activity Tracker

            return redirect()->back()->with('success', 'Payment Updated Successfully!!');

    }

    public function cancelBooking(Request $request){
        $data = TripBooking::find($request->id);
        $tripName = $data->trip->name ?? "Trip Deleted";
        $data->trip_status = 'Cancelled';
        $data->cancelation_amount = $request->cancelation_amount;
        $data->cancelation_amount_5_gst = $request->cancelation_amount_5_gst;
        $data->cancelation_amount_tcs = $request->cancelation_amount_tcs;
        $data->cancelation_amount_refunded = $request->cancelation_amount_refunded;
        $data->cancelation_amount_credit_note = $request->cancelation_amount_credit_note;
        $data->cancelation_reason = $request->cancelation_reason;
        $data->cancelation_date = date("Y-m-d");
        $data->save();


        // Activity Tracker
        $action = "<b>".Auth::user()->name . "</b> has cancelled the booking. <b>Reason: ".$request->cancelation_reason."</b>";

        $activity = new BookingLog();
        $activity->admin_id = Auth::user()->id;
        $activity->booking_id = $request->id;
        $activity->page = "Bookings";
        $activity->action = $action;
        $activity->save();
        // Activity Tracker
        $customerDetails = [];
        foreach (json_decode($data->customer_id) as $traveler) {
            $customer = getCustomerById($traveler);
            if ($customer) {
                $customerDetails[] = $customer->name . ' (' . $customer->email . ')';
            }
        }
        $customerDetailsStr = implode(', ', $customerDetails);

        // add Credit note in Users Wallet
        $travelers = json_decode($data->customer_id);
        $payment_all_done_by = $data->payment_all_done_by_this;
        if($payment_all_done_by){
            $cust = Customer::find($payment_all_done_by);
            if($cust){
                if($request->cancelation_amount_credit_note){
                    $cust->credit_note_wallet = $cust->credit_note_wallet + $request->cancelation_amount_credit_note;
                    $cust->save();

                    // Activity Tracker
                    $action = "Credit note amount RS. <b>".$request->cancelation_amount_credit_note."</b> has been added to customer wallet.";

                    $activity = new BookingLog();
                    $activity->admin_id = Auth::user()->id;
                    $activity->booking_id = $request->id;
                    $activity->page = "View Booking";
                    $activity->action = $action;
                    $activity->save();
                    // Activity Tracker
                }
            }
        }else{
            $totalCustomer = count($travelers) ?? 1;
            $perCustomerCreditNoteAmt = $request->cancelation_amount_credit_note/$totalCustomer;
            if($request->cancelation_amount_credit_note && $perCustomerCreditNoteAmt){
                foreach($travelers as $c_id){
                    $cusCheck = getCustomerByid($c_id)->parent;
                    if($cusCheck == 0){
                        $credit_note_amt = $perCustomerCreditNoteAmt;
                    }else{
                        $credit_note_amt = $perCustomerCreditNoteAmt;
                        $c_id = $cusCheck;
                    }

                    $customer = Customer::find($c_id);
                    $customer->credit_note_wallet = $customer->credit_note_wallet + $credit_note_amt;
                    $customer->save();

                    // Activity Tracker
                    $action = "Credit note amount RS. <b>".$credit_note_amt."</b> has been added to customer wallet.";

                    $activity = new BookingLog();
                    $activity->admin_id = Auth::user()->id;
                    $activity->booking_id = $request->id;
                    $activity->page = "View Booking";
                    $activity->action = $action;
                    $activity->save();
                    // Activity Tracker
                }
            }
        }

        // Whatsapp Notification
        if(setting('whatsapp_status') == 1){
            foreach($travelers as $traveler){
                $customer = getCustomerById($traveler);
                if($customer->phone){
                    $client = new Client();
                    $response = $client->request('POST', 'https://live-server-8452.wati.io/api/v1/sendTemplateMessage?whatsappNumber='.$customer->phone, [
                        'body' => '{"broadcast_name":"booking_cancelled_new1","parameters":[{"name":"trip_name","value":"'.$tripName.'"},{"name":"name","value":"'.$customer->name.'"}],"template_name":"booking_cancelled_new1"}',
                        'headers' => [
                            'Authorization' => env('WATI_AUTH'),
                            'content-type' => 'text/json',
                        ],
                    ]);
                }
            }
        }

        // =========== email ==================
        $adminEmail = setting('admin_mail');
        $accountEmail = setting('account_mail');
        $opsEmail = setting('operation_mail');
        $extraMail = config('app.ExtraMail');

        if($extraMail){
            $data = [
                'reason' => $request->cancelation_reason,
                'trip_name' => $data->trip->name ?? "Trip",
                'admin_email'=>$adminEmail,
                'customer_name' => $customerDetailsStr,
            ];
            if(setting('mail_status') == 1){
                event(new SendMailEvent("$extraMail", 'Booking Cancelled!', 'emails.admin-booking-cancelled', $data));
            }
        }

        //mail to admin
        if($adminEmail){
            $data = [
                'reason' => $request->cancelation_reason,
                'trip_name' => $data->trip->name ?? "Trip",
                'admin_email'=>$adminEmail,
                'customer_name' => $customerDetailsStr,
            ];
            if(setting('mail_status') == 1){
                event(new SendMailEvent("$adminEmail", 'Booking Cancelled!', 'emails.admin-booking-cancelled', $data));
            }
        }

        if($accountEmail){
            $data = [
                'reason' => $request->cancelation_reason,
                'trip_name' => $data->trip->name ?? "Trip",
                'admin_email'=>$accountEmail,
                  'customer_name' => $customerDetailsStr,
            ];
            if(setting('mail_status') == 1){
                event(new SendMailEvent("$accountEmail", 'Booking Cancelled!', 'emails.admin-booking-cancelled', $data));
            }
        }

        if($opsEmail){
            $data = [
                'reason' => $request->cancelation_reason,
                'trip_name' => $data->trip->name ?? "Trip",
                'admin_email'=>$opsEmail,
                'customer_name' => $customerDetailsStr,
            ];
            if(setting('mail_status') == 1){
                event(new SendMailEvent("$opsEmail", 'Booking Cancelled!', 'emails.admin-booking-cancelled', $data));
            }
        }

        foreach($travelers as $traveler){
            $customer = getCustomerById($traveler);
            $mailData = [
                'email' => $customer->email,
                'name' => $customer->name,
                'trip_name' => $tripName,
                'date' => date("Y-m-d"),
                'reason' => $request->cancelation_reason ?? " ",
                'for'=>'customer',
                'booking_id' => $request->id,
            ];
            if(setting('mail_status') == 1){
                event(new SendMailEvent("$customer->email", 'Booking Cancelled | Adventures Overland!', 'emails.canceled-user-booking', $mailData));
            }
        }
        // =========== email ==================

        return true;
        // return redirect()->route('booking.index')->with('success', 'Trip Booking Cancelled Successfully!');
    }

    public function uploadInvoice(Request $request){
        $tripbooking= TripBooking::where('id', $request->id)->first();
        $trip_id=$tripbooking->trip_id;
        $this->generateCertificateforcorbon($trip_id);
        DB::beginTransaction();
        try {

            if ($request->has('invoice_id')) {

                $existingInvoice = BookingsInvoiceDetail::find($request->invoice_id);

                if (!$existingInvoice) {
                    return redirect()->back()->with('error', 'Invoice not found!');
                }

                // Delete the old file if it exists
                if ($existingInvoice->invoice_path && Storage::exists($existingInvoice->invoice_path)) {
                    Storage::delete($existingInvoice->invoice_path);
                }

                // Check if a new file is uploaded
                if ($request->hasFile('invoice_files')) {
                    $file = $request->file('invoice_files');
                    $filePath = $file->store('admin/invoice');

                    // Update the existing invoice record
                    $existingInvoice->invoice_path = $filePath;
                    $existingInvoice->invoice_reuploaded = 1;
                    $existingInvoice->invoice_status = null;
                    $existingInvoice->save();

                    $accountEmail = setting('account_mail');
                    if ($accountEmail) 
                    {
                        $datass = [
                            'trip_name' => $data->trip->name ?? "Trip",
                        ];
                        if (setting('mail_status') == 1) {
                            event(new SendMailEvent("$accountEmail", 'The final review  of Invoice ', 'emails.invoice-approve',$datass));
                        }
                       
                        Log::info(' notification mail send to account ' . $accountEmail);
                                    
                    }

                } else {
                    return redirect()->back()->with('error', 'A new file is required to update the invoice!');
                }

            }
            else{
                $data = TripBooking::find($request->id);
               

                $fileCount = count($request->file('invoice_files'));

                $existingInvoicesCount = BookingsInvoiceDetail::where('booking_id', $data->id)->count();

                $totalInvoices = $existingInvoicesCount + $fileCount;

                if ($totalInvoices >= 5) {
                    return redirect()->back()->with('error', 'You can only upload up to 5 invoices!');
                }

                if ($request->hasFile('invoice_files')) {
                    foreach ($request->file('invoice_files') as $file) {
                        $filePath = $file->store('admin/invoice');

                        // Invoice Upload With Details
                        $bookingInvoice = new BookingsInvoiceDetail();
                        $bookingInvoice->invoice_path = $filePath;
                        $bookingInvoice->booking_id = $data->id;
                        $bookingInvoice->invoice_name = $file->getClientOriginalName();
                        $bookingInvoice->invoice_sent_by = Auth::user()->id;
                        $bookingInvoice->save();


                        $accountEmail = setting('account_mail');
                        if ($accountEmail) 
                        {
                            $datass = [
                                'trip_name' => $data->trip->name ?? "Trip",
                            ];
                            if (setting('mail_status') == 1) {
                                event(new SendMailEvent("$accountEmail", 'The final review  of Invoice ', 'emails.invoice-approve',$datass));
                            }
                        Log::info(' notification mail send to account ' . $accountEmail);

                                        
                        }


                    //                    if($request->id){
                    //                        if($check){
                    //                            $bookingInvoice = $check;
                    //                            $bookingInvoice->invoice_reuploaded = 1;
                    //                            $bookingInvoice->invoice_status = null;
                    //                            $bookingInvoice->save();
                    //                        }else{
                    //                            $bookingInvoice->invoice_path = $filePath;
                    //                            $bookingInvoice->booking_id = $data->id;
                    //                            $bookingInvoice->invoice_sent_by = Auth::user()->id;
                    //                            $bookingInvoice->save();
                    //                        }
                    //                    }
                    }
                }
                else{
                    return redirect()->back()->with('error', 'Atleast one file is required!');
                }

                    //            if($request->hasfile('invoice_file')){
                    //                @unlink('storage/app/'.$data->invoice_file);
                    //                $data->invoice_file = $request->file('invoice_file')->store('admin/invoice');
                    //            }
                $data->trip_status = 'Completed';
                $data->save();
            }



            // Activity Tracker
            $action = "<b>".Auth::user()->name . "</b> has added invoice for verification.";

            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $request->id;
            $activity->page = "View Booking";
            $activity->action = $action;
            $activity->save();
            // Activity Tracker

            DB::commit();
            return redirect()->back()->with('success', 'Invoice uploaded and waiting for the final review.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while adding the item: ' . $e->getMessage());
        }
    }
    public function generateCertificateforcorbon($data)
    {
        $carbonData = CarbonInfo::where('trip_id', $data)->get();
        foreach ($carbonData as $carboninfo) {
                $trip_name =$carboninfo->trip->name ?? "Trip";
                $name = $carboninfo->customer_first_name . ' ' . $carboninfo->customer_last_name;
                $email = $carboninfo->customer_email;
                $carnumber= $carboninfo->car_sequence_number;
                $distance = $carboninfo->total_distance;
                $carbon_emission = $carboninfo->carbon_emission;
                $no_of_trees = $carboninfo->no_of_trees;


                $data = array(
                    'trip_name' => $trip_name,
                    'name' => $name,
                    'email' => $email,
                    'carnumber' => $carnumber,
                    'distance' => $distance,
                    'carbon_emission' => $carbon_emission,
                    'no_of_trees' => $no_of_trees,

                );
               

                $pdfFile = "carbon-certificate-" . $name . ".pdf";
                $pdf = PDF::setPaper('A4', 'landscape')->loadView('pdf.carbon-certificate', $data);

                $tempFilePathPdf = storage_path('pdf/carboncertificate/' . $pdfFile);
                if (!file_exists(dirname($tempFilePathPdf))) {
                    mkdir(dirname($tempFilePathPdf), 0755, true);
                }
                // Save the PDF to a temporary file
                $pdf->save($tempFilePathPdf);
                $pdfUrl = storage_path('pdf/carboncertificate/' . $pdfFile);
                $data['attachment'] = [$pdfUrl];
                
                Log::info("pdf url  - ".$pdfUrl);
               

                if (empty($email)) {
                    Log::warning("No email found for customer:$email}, birthday email not sent.");
                } else {
                    try {
                        event(new SendMailEvent('vageesh@adventuresoverland.com', '🌳 Carbon Certificate 🌟', 'emails.carbon-certificate', $data));
                        Log::info("Carbon Certificate email sent to $email - " . json_encode($data));
                    } catch (\Exception $e) {
                        Log::error("Failed to send Carbon certificate email to $email: " . $e->getMessage());
                    }
                }

                // Save the PDF file to the storage
        }
         
    
    }

    public function uploadMultipleInvoiceAction(Request $request){
        DB::beginTransaction();
        try {

            $approvedInvoices = $request->approvedInvoices;
            $rejectedInvoices = $request->rejectedInvoices;

            if($approvedInvoices) {
                foreach ($approvedInvoices as $approvedInvoice) {

                    Log::info('invoiceid - ' . $approvedInvoice['invoiceId']);
                    Log::info('booking_id - ' . $request->id);
                    $bookingInvoice = BookingsInvoiceDetail::where('id', $approvedInvoice['invoiceId'])->first();

                    if ($bookingInvoice) {
                        $bookingInvoice->invoice_verified_by = Auth::user()->id;
                        $bookingInvoice->invoice_status = 1;
                        $bookingInvoice->comment = null;
                        $bookingInvoice->save();
                    } else {
                        Log::info('Approved Invoice not found!');
                        DB::rollBack();
                        return redirect()->route('booking.index')->with('error', 'Booking Invoice not found!');
                    }
                }
            }

            if($rejectedInvoices) {
                foreach ($rejectedInvoices as $rejectedInvoice) {
                    $bookingInvoice = BookingsInvoiceDetail::where('id', $rejectedInvoice['invoiceId'])->first();

                    if ($bookingInvoice) {
                        $bookingInvoice->invoice_verified_by = Auth::user()->id;
                        $bookingInvoice->invoice_status = 0;
                        $bookingInvoice->invoice_reuploaded = 0;
                        $bookingInvoice->comment = $rejectedInvoice['comment'];
                        $bookingInvoice->save();
                    } else {
                        Log::info('Rejected Invoice not found!');
                        DB::rollBack();
                        return redirect()->route('booking.index')->with('error', 'Booking Invoice not found!');
                    }
                }
            }
            Log::info('Updated');
            DB::commit();
            return redirect()->route('booking.index')->with('success', 'All Invoices are Updated Successfully!');
        }
        catch (\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('booking.index')->with('error', 'An error occurred while adding the item: ' . $e->getMessage());
        }
    }

    public function uploadInvoiceAction(Request $request){
        DB::beginTransaction();
        try 
        {

            $data = TripBooking::find($request->id);
           
        
            $bookingInvoices = BookingsInvoiceDetail::where('booking_id', $data->id)->get();
            $allInvoicesApproved = $bookingInvoices->every(function ($invoice) {
                return $invoice->invoice_status == 1;
            });
           

            if(!$allInvoicesApproved){
                return redirect()->route('booking.index')->with('error', 'All invoices must be approved before taking action!');
            }

            

            // Activity Tracker
            $action = "<b>".Auth::user()->name . "</b> has verified and sent final invoice.";

            $activity = new BookingLog();
            $activity->admin_id = Auth::user()->id;
            $activity->booking_id = $request->id;
            $activity->page = "View Booking";
            $activity->action = $action;
            $activity->save();
            // Activity Tracker
            // $data = TripBooking::find($request->id);
            $bookingInvoices = BookingsInvoiceDetail::where('booking_id', $data->id)->get();
            // $attachment = url('storage/app/'.$data->invoice_file);
            $attachments = $bookingInvoices->map(function ($invoice) {
                return Storage_path('app/'.$invoice->invoice_path);
            })->toArray();

            $trList = "";
            $travelers =  json_decode($data->customer_id);

            // =========== email ==================
            $errors = [];
            foreach ($travelers as $traveler) {
                try {
                    $customer = getCustomerById($traveler);
                    $trList .= $customer->name . ", ";
                    $datas = [
                        'email' => $customer->email,
                        'name' => $customer->name,
                        'trip_name' => $data->trip->name ?? "Trip",
                        'date' => date("Y-m-d"),
                        'attachment' => $attachments,
                        'booking_id' => $data->id,
                        'for' => 'customer',
                    ];
            
                    // Send email to customer
                    if ($customer->email) 
                    {
                            if (setting('mail_status') == 1) {
                              $mail=  event(new SendMailEvent("$customer->email", 'Thanks for travelling with Adventures Overland!', 'emails.invoice-booking', $datas));
                            }
                            if($mail)
                            {  
                                # mail to account
                                  try {
                                        $accountEmail = setting('account_mail');
                                        if ($accountEmail) {
                                                $datass = [
                                                    'points' => $payablePoints,
                                                    'trip_name' => $booking->trip->name ?? "Trip",
                                                    'admin_email' => $accountEmail,
                                                    'attachment' => $attachments,
                                                    'name' => $trList ?? null,
                                                ];
                                                if (setting('mail_status') == 1) {
                                                    event(new SendMailEvent("$accountEmail", 'Trip Completed!', 'emails.admin-booking-completed', $datass));
                                                }
                                        
                                        }
                                        else{
                                            $errors[] = 'Account Mail not found';
                                        }
                                } catch (\Throwable $e) {
                                    Log::error('Error in sending account email: ' . $e->getMessage());
                                    $errors[] = 'Failed to send email to account: ';
                                }

                                #  // Send email to Sales
                                try {
                                    $salesEmail = setting('sales_email');
                                    if ($salesEmail) {
                                            $datass = [
                                                'points' => $payablePoints,
                                                'trip_name' => $booking->trip->name ?? "Trip",
                                                'admin_email' => $salesEmail,
                                                'attachment' => $attachments,
                                                'name' => $trList ?? null,
                                            ];
                                            if (setting('mail_status') == 1) {
                                                event(new SendMailEvent("$salesEmail",'Trip Completed!', 'emails.admin-booking-completed', $datass));
                                            }
                                    }
                                    else{
                                        $errors[] = 'Sales mail not found';
                                    }
                                } catch (\Throwable $e) {
                                    Log::error('Error in sending sales email: ' . $e->getMessage());
                                    $errors[] = 'Failed to send email to sales';
                                }
                                // update the status of invoice
                                $data->invoice_status = 'Sent';
                                $data->invoice_sent_date = date("Y-m-d");
                                $data->save();
                            }
                    }
                } 
                catch (\Throwable $e) {
                    Log::error('Error in sending customer email: ' . $e->getMessage());
                    return response()->json(['error' => 'You Can not send Invoice to Customer!Somthing went wrong'], 500);

                }
            }
                
            // =========== email ==================

            DB::commit();
            if (count($errors) > 0) {
                if (count($errors) == 1) {
                    return response()->json(['success' => true,'errors' => $errors], 200);
                } elseif(count($errors) == 2){
                    return response()->json([ 'success' => true,'errors' => $errors], 200);
                }elseif(count($errors) == 3){
                    return response()->json(['success' => true,'errors' => $errors], 200);
                }
                else{
                    return response()->json(['success' => false,'message' => 'There are Something Wrong! Please Try Later','errors' => $errors], 500);
                }
            } else {
                // If no errors, send a success message
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice Sent Successfully!'
                ], 200);
            }

        }catch (\Exception $e) {
            Log::info('uploadInvoiceAction - '.$e->getMessage());
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while adding the item!'], 500);
        }
    }

    public function customerDetails(Request $request){
        $data = Customer::find($request->id);

        $extraDocx = DB::table('extra_documents')->where('user_id', $request->id)->get();
        // $carbonData = DB::table('trip_carbon_infos')->where(['customer_id'=> $request->id, 'booking_id'=>$request->booking_id])->first();

        if($data){
            if($data->terms_accepted == 1){
                $data->terms_accepted = "Accepted";
            }else{
                $data->terms_accepted = "Not Accepted";
            }

            // if($carbonData->carbon_accepted == 1){
            //     $data->carbon_accepted = "Accepted";
            // }else{
            //     $data->carbon_accepted = "Not Accepted";
            // }
        }else{
            $data->terms_accepted = $data->terms_accepted ?? "Not Accepted";
        }
        if($data->passport_front){
            $data->passport_front = env('USER_URL').'storage/app/'.$data->passport_front;
        }else{
            $data->passport_front = '';
        }
        if($data->passport_back){
            $data->passport_back = env('USER_URL').'storage/app/'.$data->passport_back;
        }else{
            $data->passport_back = '';
        }
        if($data->pan_gst){
            $data->pan_gst = env('USER_URL').'storage/app/'.$data->pan_gst;
        }else{
            $data->pan_gst = '';
        }
        if($data->adhar_card){
            $data->adhar_card = env('USER_URL').'storage/app/'.$data->adhar_card;
        }else{
            $data->adhar_card = '';
        }
        if($data->driving){
            $data->driving = env('USER_URL').'storage/app/'.$data->driving;
        }else{
            $data->driving = '';
        }
        if($data->profile){
            $data->profile = env('USER_URL').'storage/app/'.$data->profile;
        }else{
            $data->profile = '';
        }

        $extras = [];
        foreach($extraDocx as $extra){
            array_push($extras, ['title'=>$extra->title, 'image'=>env('USER_URL').'storage/app/'.$extra->image]);
        }
        $data->extra_doc = $extras;
        return json_encode($data);
    }

    public function deleteMedia(Request $request){
        $id = $request->c_id;
        $column = $request->clmn;

        $cus = Customer::find($id);
        $cus->$column = NULL;
        $cus->save();
        return $id;
    }

    public function correctionBooking(Request $request){
        $data = TripBooking::find($request->id);
        $spoc_mail=$data->admin->email;
        $data->trip_status = 'Correction';
        $data->correction_reason = $request->correction_reason;
        $data->save();
        if($data)
        {
            if ($spoc_mail) 
            {
                $datass = [
                    'trip_name' => $data->trip->name ?? "Trip",
                    'reason'=> $data->correction_reason
                ];
                if (setting('mail_status') == 1) {
                    event(new SendMailEvent("$spoc_mail", 'Correction for Booking', 'emails.correction-booking',$datass));
                }
                            
            }
            $accountEmail = setting('account_mail');
            if ($accountEmail) 
            {
                $datass = [
                    'trip_name' => $data->trip->name ?? "Trip",
                    'reason'=> $data->correction_reason
                ];
                if (setting('mail_status') == 1) {
                    event(new SendMailEvent("$accountEmail", 'Correction for Booking', 'emails.correction-booking',$datass));
                }
                            
            }
            else{
                return redirect()->route('booking.index')->with('error', 'Please Check For Account Mail');

            }
            $salesEmail = setting('sales_email');
            if ($salesEmail) {
                    $datass = [
                        'trip_name' => $data->trip->name ?? "Trip",
                        'reason'=> $data->correction_reason
                    ];
                    if (setting('mail_status') == 1) {
                        event(new SendMailEvent("$salesEmail",'Correction for Booking', 'emails.correction-booking', $datass));
                    }
            }
            else{
                return redirect()->route('booking.index')->with('error', 'Please Check For Sales Mail');
                
            }

        }
        return redirect()->route('booking.index')->with('success', 'Trip Booking Correction Request sent Successfully!');
    }

    public function uploadMedia(Request $request){

        $url = env('USER_URL').'api/file?email='.getCustomerById($request->c_id)->email;

        $postData = [
            'passport_front' => $request->file('passport_front'),
            'passport_back' => $request->file('passport_back'),
            'profile' => $request->file('profile'),
            'pan_gst' => $request->file('pan_gst'),
            'adhar_card' => $request->file('adhar_card'),
            'driving' => $request->file('driving'),
        ];

        $response = Http::attach('passport_front', $postData['passport_front']->path(), $postData['passport_front']->getClientOriginalName(), ['Content-Type' => $postData['passport_front']->getClientMimeType()])
                        ->attach('passport_back', $postData['passport_back']->path(), $postData['passport_back']->getClientOriginalName(), ['Content-Type' => $postData['passport_back']->getClientMimeType()])
                        ->attach('profile', $postData['profile']->path(), $postData['profile']->getClientOriginalName(), ['Content-Type' => $postData['profile']->getClientMimeType()])
                        ->attach('pan_gst', $postData['pan_gst']->path(), $postData['pan_gst']->getClientOriginalName(), ['Content-Type' => $postData['pan_gst']->getClientMimeType()])
                        ->attach('adhar_card', $postData['adhar_card']->path(), $postData['adhar_card']->getClientOriginalName(), ['Content-Type' => $postData['adhar_card']->getClientMimeType()])
                        ->attach('driving', $postData['driving']->path(), $postData['driving']->getClientOriginalName(), ['Content-Type' => $postData['driving']->getClientMimeType()])
                        ->post($url, $postData);

        return redirect()->back()->with('success', 'Updated Successfully');

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
                $bookedBy = $sheet->getCell('A'. $row)->getValue();
                $bookingFor = $sheet->getCell('B'. $row)->getValue();
                $customerName = $sheet->getCell('C'. $row)->getValue();
                $emailId = $sheet->getCell('D'. $row)->getValue();
                $leadSource = $sheet->getCell('E'. $row)->getValue();
                $subLeadSource = $sheet->getCell('F'. $row)->getValue();
                $expedition = $sheet->getCell('G'. $row)->getValue();
                $trip = $sheet->getCell('H'. $row)->getValue();
                $vehicleType = $sheet->getCell('I'. $row)->getValue();
                $vehicleSeat = $sheet->getCell('J'. $row)->getValue();
                $vehicleSeatAmt = $sheet->getCell('K'. $row)->getValue();
                $vehicleSecurityAmt = $sheet->getCell('L'. $row)->getValue();
                $vehicleSecurityAmtCmt = $sheet->getCell('M'. $row)->getValue();
                $roomType = $sheet->getCell('N'. $row)->getValue();
                $roomTypeAmt = $sheet->getCell('O'. $row)->getValue();
                $roomCat = $sheet->getCell('P'. $row)->getValue();
                $paymentFrom = $sheet->getCell('Q'. $row)->getValue();
                $company = $sheet->getCell('R'. $row)->getValue();
                $paymentFromTcs = $sheet->getCell('S'. $row)->getValue();
                $completePaymentBy = $sheet->getCell('T'. $row)->getValue();
                $paymentType = $sheet->getCell('U'. $row)->getValue();
                $paymentAmt = $sheet->getCell('V'. $row)->getValue();
                $paymentDate = $sheet->getCell('W'. $row)->getValue();
                $tripCost = $sheet->getCell('X'. $row)->getValue();
                $extraService = $sheet->getCell('Y'. $row)->getValue();
                $taxRequired = $sheet->getCell('Z'. $row)->getValue();
                $payableAmt = $sheet->getCell('AA'. $row)->getValue();
                $tripStatus = $sheet->getCell('AB'. $row)->getValue();
                $invoiceFile = $sheet->getCell('AC'. $row)->getValue();
                $invoiceStatus = $sheet->getCell('AD'. $row)->getValue();
                $invoiceSentDate = $sheet->getCell('AE'. $row)->getValue();
                $redeemPoints = $sheet->getCell('AF'. $row)->getValue();
                $cancellationReason = $sheet->getCell('AG'. $row)->getValue();
                $cancellationAmt = $sheet->getCell('AH'. $row)->getValue();
                $cancellationDate = $sheet->getCell('AI'. $row)->getValue();

                if($emailId){
                    $customerData = Customer::where('email', $emailId)->first();
                    $tripData = Trip::where('name', $trip)->first();
                    if($customerData && $tripData){
                        $token = Str::random(40);
                        $hashedToken = hash('sha256', $token);

                        $roomInfo = ['room_type'=>$roomType??null, 'room_type_amt'=>$roomTypeAmt??0, 'room_cat'=>$roomCat??null];
                        $tripCost = ['c_id'=>$customerData->id??null, 'cost'=>$tripCost ?? 0, 'comment'=> 'NA'];
                        if($taxRequired == "Yes" || empty($taxRequired)){
                            $taxRequired = 0;
                        }else{
                            $taxRequired = 1;
                        }
                        if($invoiceFile){
                            $invoiceFile = "admin/invoice/".$invoiceFile;
                        }

                        // Booking insert
                        $data = new TripBooking();
                        $data->token = $hashedToken ?? null;
                        $data->admin_id = Auth::user()->id;
                        $data->booking_for = $bookingFor ?? null;
                        $data->customer_id = json_encode(["$customerData->id"]) ?? null;
                        $data->lead_source = $leadSource ?? null;
                        $data->sub_lead_source = $subLeadSource ?? null;
                        $data->expedition = $expedition ?? null;
                        $data->trip_id = $tripData->id ?? null;
                        $data->vehical_type = $vehicleType ?? null;
                        $data->vehical_seat = $vehicleSeat ?? null;
                        $data->vehical_seat_amt = $vehicleSeatAmt ?? null;
                        $data->vehical_security_amt = $vehicleSecurityAmt ?? null;
                        $data->vehical_security_amt_cmt = $vehicleSecurityAmtCmt ?? null;
                        $data->no_of_rooms = null;
                        $data->room_info = json_encode([$roomInfo]) ?? null;
                        $data->payment_from = $paymentFrom ?? null;
                        $data->payment_from_cmpny = $company ?? null;
                        $data->payment_from_tax = $paymentFromTcs ?? null;
                        $data->payment_all_done_by_this = null;
                        $data->payment_by_customer_id = $customerData->id ?? null;
                        $data->payment_type = $paymentType ?? null;
                        $data->payment_amt = $paymentAmt ?? null;
                        $data->payment_date = date("Y-m-d", strtotime($paymentDate)) ?? null;
                        $data->trip_cost = json_encode([$tripCost]) ?? null;
                        $data->extra_services = null;
                        $data->tax_required = $taxRequired ?? null;
                        $data->payable_amt = $payableAmt ?? 0;
                        $data->trip_status = $tripStatus ?? null;
                        $data->invoice_file = $invoiceFile ?? null;
                        $data->invoice_status = $invoiceStatus ?? null;
                        $data->invoice_sent_date = date("Y-m-d", strtotime($invoiceSentDate)) ?? null;
                        $data->form_submited = 1;
                        $data->cancelation_reason = $cancellationReason ?? null;
                        $data->cancelation_amount = $cancellationAmt ?? null;
                        if(!empty($cancellationDate)){
                            $data->cancelation_date = date("Y-m-d", strtotime($cancellationDate)) ?? null;
                        }
                        $res = $data->save();

                        // Activity Tracker
                        $activity = new ActivityTracker();
                        $activity->admin_id = Auth::user()->id;
                        $activity->action = Auth::user()->name ." has Imported Booking for ". $customerData->first_name ." Through xlsx.";
                        $activity->page = "booking";
                        $activity->page_data_id = $hashedToken;
                        $activity->save();
                        // Activity Tracker

                        if($res){
                            $customerData->points = $customerData->points + ($redeemPoints ?? 0);
                            $customerData->save();

                            // loyalty points history
                            if($redeemPoints && $redeemPoints > 0){
                                $currentDate = date('Y-m-d');
                                $oneYearLater = date('Y-m-d', strtotime('+1 year', strtotime($currentDate)));
                                $LoyalityPtsData = new LoyalityPts();
                                $LoyalityPtsData->customer_id = $customerData->id;
                                $LoyalityPtsData->admin_id = Auth::user()->id;
                                $LoyalityPtsData->reason = 'Trip Completed';
                                $LoyalityPtsData->trip_name = $tripData->name;
                                $LoyalityPtsData->cost = $tripData->price;
                                $LoyalityPtsData->expiry_date = $oneYearLater;
                                $LoyalityPtsData->trans_type = 'Cr';
                                $LoyalityPtsData->trans_amt = $redeemPoints;
                                $LoyalityPtsData->balance = $redeemPoints;
                                $LoyalityPtsData->status = 'Approved';
                                $LoyalityPtsData->trans_page = 'TripBooking';
                                $LoyalityPtsData->token = $hashedToken ?? null;
                                $LoyalityPtsData->save();
                            }
                        }
                    }else{
                        $duplicateEntries[] = [
                            'email' => $emailId,
                        ];
                    }
                }else{
                    $duplicateEntries[] = [
                        'email' => $emailId,
                    ];
                }
            }

            // Redirect back with success message
            return redirect()->back()->with('success', 'File imported successfully.');
        }

        // Redirect back with error message if no file was uploaded
        return redirect()->back()->with('error', 'No file uploaded.');

    }

    public function billingCustomer(Request $request)
    {
        $token = $request->token;
        $customers = $request->billing_customer_id ?? [];


        $data = Customer::select('id', 'first_name', 'last_name', 'email','telephone_code', 'phone', 'points', 'parent', 'gender', 'dob','tier')->whereIn('id', $customers)->get();
        $bookingRecord = TripBooking::where('token', $token)->first();
        if ($bookingRecord) {
            $bookingRecord->billing_to = json_encode($customers);
            $bookingRecord->save();
        }
         $res = [
           
            'customers' => $data,
           
        ];

        return json_encode($data);
    }
}
