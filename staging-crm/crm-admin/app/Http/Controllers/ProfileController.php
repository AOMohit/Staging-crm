<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use App\Events\SendMailEvent;
use Illuminate\Validation\Rules\Password;
use App\Models\BookingLog;
use App\Models\State;
use App\Models\Country;
use App\Models\Enquiry;
use App\Models\Customer;
use App\Models\Setting;
use App\Models\TripBooking;
use App\Models\Trip;
use App\Models\Vendor;
use App\Models\Agent;
use App\Models\Inventory;
use App\Models\User;
use App\Models\LoyalityPts;
use DB;



class ProfileController extends Controller
{
    public function unreadCount()
    {
        $count = Enquiry::where('is_read', false)->count();

        return response()->json(['unread_count' => $count]);
    }
    public function dashboard(Request $request){

    
        $fdate = date('Y-m-d', strtotime('01-04-' . date('Y')));
        $fdateMinus365Days = date('Y-m-d', strtotime($fdate . ' +364 days'));


        if(isset($request->filter) && $request->filter == "Yearly"){
            $request->from_date = $fdate;
            $request->to_date = $fdateMinus365Days;
        }else{
            $date = date("Y-m-d");
            $dateBefore7Days = date('Y-m-d', strtotime('-7 days'));
            $dateBefore28Days = date('Y-m-d', strtotime('-28 days'));
        }

        $bookingCount = TripBooking::where('trip_status', '!=', 'Draft');
        if(isset($request->from_date) && isset($request->to_date)){
            $bookingCount->whereDate('created_at', '>=', $request->from_date)->whereDate('created_at', '<=', $request->to_date);
        }elseif(isset($request->filter)){

            if($request->filter == "Daily"){
                $bookingCount->whereDate('created_at', '=', $date);
            }elseif($request->filter == "Weekly"){
                $bookingCount->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore7Days);
            }if($request->filter == "Monthly"){
                $bookingCount->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore28Days);
            }
        }
        $bookingCount = $bookingCount->count();


        $tripCount = Trip::whereNotNull('created_at');
        if(isset($request->from_date) && isset($request->to_date)){
            $tripCount->whereDate('created_at', '>=', $request->from_date)->whereDate('created_at', '<=', $request->to_date);
        }elseif(isset($request->filter)){

            if($request->filter == "Daily"){
                $tripCount->whereDate('created_at', '=', $date);
            }elseif($request->filter == "Weekly"){
                $tripCount->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore7Days);
            }if($request->filter == "Monthly"){
                $tripCount->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore28Days);
            }
        }
        $tripCount = $tripCount->count();


        $customerCount = Customer::whereNotNull('first_name');
        if(isset($request->from_date) && isset($request->to_date)){
            $customerCount->whereDate('created_at', '>=', $request->from_date)->whereDate('created_at', '<=', $request->to_date);
        }elseif(isset($request->filter)){

            if($request->filter == "Daily"){
                $customerCount->whereDate('created_at', '=', $date);
            }elseif($request->filter == "Weekly"){
                $customerCount->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore7Days);
            }if($request->filter == "Monthly"){
                $customerCount->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore28Days);
            }
        }
        $customerCount = $customerCount->count();


        $vendorCount = Vendor::whereNotNull('first_name');
        if(isset($request->from_date) && isset($request->to_date)){
            $vendorCount->whereDate('created_at', '>=', $request->from_date)->whereDate('created_at', '<=', $request->to_date);
        }elseif(isset($request->filter)){

            if($request->filter == "Daily"){
                $vendorCount->whereDate('created_at', '=', $date);
            }elseif($request->filter == "Weekly"){
                $vendorCount->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore7Days);
            }if($request->filter == "Monthly"){
                $vendorCount->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore28Days);
            }
        }
        $vendorCount = $vendorCount->count();


        $agentCount = Agent::whereNotNull('first_name');
        if(isset($request->from_date) && isset($request->to_date)){
            $agentCount->whereDate('created_at', '>=', $request->from_date)->whereDate('created_at', '<=', $request->to_date);
        }elseif(isset($request->filter)){

            if($request->filter == "Daily"){
                $agentCount->whereDate('created_at', '=', $date);
            }elseif($request->filter == "Weekly"){
                $agentCount->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore7Days);
            }if($request->filter == "Monthly"){
                $agentCount->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore28Days);
            }
        }
        $agentCount = $agentCount->count();


        $inventoryCount = Inventory::whereNotNull('created_at');
        if(isset($request->from_date) && isset($request->to_date)){
            $inventoryCount->whereDate('created_at', '>=', $request->from_date)->whereDate('created_at', '<=', $request->to_date);
        }elseif(isset($request->filter)){

            if($request->filter == "Daily"){
                $inventoryCount->whereDate('created_at', '=', $date);
            }elseif($request->filter == "Weekly"){
                $inventoryCount->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore7Days);
            }if($request->filter == "Monthly"){
                $inventoryCount->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore28Days);
            }
        }
        $inventoryCount = $inventoryCount->count();


        $userCount = User::whereNotNull('email');
        if(isset($request->from_date) && isset($request->to_date)){
            $userCount->whereDate('created_at', '>=', $request->from_date)->whereDate('created_at', '<=', $request->to_date);
        }elseif(isset($request->filter)){

            if($request->filter == "Daily"){
                $userCount->whereDate('created_at', '=', $date);
            }elseif($request->filter == "Weekly"){
                $userCount->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore7Days);
            }if($request->filter == "Monthly"){
                $userCount->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore28Days);
            }
        }
        $userCount = $userCount->count();


        $lpTotal = LoyalityPts::where('trans_type', 'Cr');
        if(isset($request->from_date) && isset($request->to_date)){
            $lpTotal->whereDate('created_at', '>=', $request->from_date)->whereDate('created_at', '<=', $request->to_date);
        }elseif(isset($request->filter)){

            if($request->filter == "Daily"){
                $lpTotal->whereDate('created_at', '=', $date);
            }elseif($request->filter == "Weekly"){
                $lpTotal->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore7Days);
            }if($request->filter == "Monthly"){
                $lpTotal->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore28Days);
            }
        }
        $lpTotal = $lpTotal->sum('trans_amt');



        $popularTrips = TripBooking::select('trips.name as trip_name', DB::raw('COUNT(trip_bookings.trip_id) as total_bookings'))
                        ->where('trip_status', '!=', 'Draft');
                        if(isset($request->from_date) && isset($request->to_date)){
                            $popularTrips->whereDate('trip_bookings.created_at', '>=', $request->from_date)->whereDate('trip_bookings.created_at', '<=', $request->to_date);
                        }elseif(isset($request->filter)){

                            if($request->filter == "Daily"){
                                $popularTrips->whereDate('trip_bookings.created_at', '=', $date);
                            }elseif($request->filter == "Weekly"){
                                $popularTrips->whereDate('trip_bookings.created_at', '<=', $date)->whereDate('trip_bookings.created_at', '>=', $dateBefore7Days);
                            }if($request->filter == "Monthly"){
                                $popularTrips->whereDate('trip_bookings.created_at', '<=', $date)->whereDate('trip_bookings.created_at', '>=', $dateBefore28Days);
                            }
                        }
                        $popularTrips =  $popularTrips->join('trips', 'trip_bookings.trip_id', '=', 'trips.id')
                        ->groupBy('trip_bookings.trip_id', 'trips.name')
                        ->orderByDesc('total_bookings')
                        ->limit(5)
                        ->get();

        $tripOverview = TripBooking::select('trip_status', DB::raw('COUNT(*) as trip_status_count'))
                        ->where('trip_status', '!=', 'Cancelled');
                        if(isset($request->from_date) && isset($request->to_date)){
                            $tripOverview->whereDate('trip_bookings.created_at', '>=', $request->from_date)->whereDate('trip_bookings.created_at', '<=', $request->to_date);
                        }elseif(isset($request->filter)){

                            if($request->filter == "Daily"){
                                $tripOverview->whereDate('trip_bookings.created_at', '=', $date);
                            }elseif($request->filter == "Weekly"){
                                $tripOverview->whereDate('trip_bookings.created_at', '<=', $date)->whereDate('trip_bookings.created_at', '>=', $dateBefore7Days);
                            }if($request->filter == "Monthly"){
                                $tripOverview->whereDate('trip_bookings.created_at', '<=', $date)->whereDate('trip_bookings.created_at', '>=', $dateBefore28Days);
                            }
                        }
                    $tripOverview =  $tripOverview->groupBy('trip_status')
                        ->get();

        $customerCounts = Customer::select('country', 'state', DB::raw('COUNT(*) as customer_count'))
                        ->whereNotNull('country')
                        ->whereNotNull('state');
                        if(isset($request->from_date) && isset($request->to_date)){
                            $customerCounts->whereDate('created_at', '>=', $request->from_date)->whereDate('created_at', '<=', $request->to_date);
                        }elseif(isset($request->filter)){

                            if($request->filter == "Daily"){
                                $customerCounts->whereDate('created_at', '=', $date);
                            }elseif($request->filter == "Weekly"){
                                $customerCounts->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore7Days);
                            }if($request->filter == "Monthly"){
                                $customerCounts->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore28Days);
                            }
                        }
                        $customerCounts = $customerCounts->groupBy('country', 'state')->orderByDesc('customer_count')->limit(5)->get();

        $discovery = Customer::where('tier', 'Discovery')->count();
        $adventurer = Customer::where('tier', 'Adventurer')->count();
        $explorer = Customer::where('tier', 'Explorer')->count();
        $legends = Customer::where('tier', 'Legends')->count();

        return view('admin.dashboard', compact('discovery', 'adventurer','explorer','legends','tripCount', 'bookingCount', 'customerCount', 'vendorCount', 'agentCount', 'inventoryCount', 'userCount', 'lpTotal', 'popularTrips', 'tripOverview', 'customerCounts'));
    }

    function tripstat(){
        $bookingCounts = Trip::leftJoin('trip_bookings', 'trips.id', '=', 'trip_bookings.trip_id')
            ->select('trips.name', DB::raw('COUNT(trip_bookings.id) as booking_count'));

            if(isset($request->from_date) && isset($request->to_date)){
                $bookingCounts->whereDate('created_at', '>=', $request->from_date)->whereDate('created_at', '<=', $request->to_date);
            }elseif(isset($request->filter)){
                $date = date("Y-m-d");
                $dateBefore7Days = date('Y-m-d', strtotime('-7 days'));
                $dateBefore28Days = date('Y-m-d', strtotime('-28 days'));
                if($request->filter == "Daily"){
                    $bookingCounts->whereDate('created_at', '=', $date);
                }elseif($request->filter == "Weekly"){
                    $bookingCounts->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore7Days);
                }if($request->filter == "Monthly"){
                    $bookingCounts->whereDate('created_at', '<=', $date)->whereDate('created_at', '>=', $dateBefore28Days);
                }
            }
        $bookingCounts = $bookingCounts->groupBy('trips.name')
            ->pluck('booking_count', 'name');

            $bookingCountsArray = $bookingCounts->toArray();

        return json_encode($bookingCounts);
    }

    public function changePassword(){
        return view('admin.change-passwod');
    }

    public function updatePassword(Request $request){
        // dd($request);
        $data = User::find(Auth::user()->id);
        if($request->password == $request->confirm_password){
            $data->password = Hash::make($request->password);
            $data->save();
        }

        return redirect()->back()->with('success', 'Password Update');
    }

    public function myProfile(){
        return view('admin.my-profile');
    }

    public function updateprofile(Request $request){
        $data = User::find(Auth::user()->id);
        $data->name = $request->name;
        if($request->hasfile('image')){
            @unlink('storage/app/'.$data->image);
            $data->image = $request->file('image')->store('admin/profile');
        }
        $data->save();

        return redirect()->back()->with('success', 'Profile Update Successfully!');
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function getStateByCountry(Request $request){
    
        $countryId = Country::where('name', $request->country)->first();
        $data = State::where('country_id', $countryId->id)->orderBy('name', 'asc')->get();
        $selected = $request->selected ?? '';

        if ($data->isEmpty()) {
            return '<option value="">No State Found</option>';
        }
           $html = '<option value="">Select State</option>';
        foreach ($data as $allstate) {
            $isSelected = ($allstate->name == $selected) ? 'selected' : '';
            $html .= '<option value="'. $allstate->name .'" '.$isSelected.'>' . $allstate->name . '</option>';
        }
        return $html;
    }

    public function getCustomerDetailsById(Request $request){

     
        $customers = $request->ids;

        $token = $request->token;
        
        if(!isset($customers)){
            $customers = [];
        }
        
        $checkChild = Customer::whereIn('id', $customers)->where('parent', '!=', 0)->get();
        foreach($checkChild as $child){
            $check = TripBooking::where('token', $token)->whereJsonContains('customer_id', "$child->parent")->first();
            if(!$check){
                return 0;
            }
        }
        
        $data = Customer::select('id', 'first_name', 'last_name', 'email', 'telephone_code', 'phone', 'points', 'parent', 'gender', 'dob','tier')->whereIn('id', $customers)->get();
      
        $booking = TripBooking::where('token', $token)->first();
       
       
        if(!$booking){
            $bookingData = [];
            $bookingDevData = [];
        }else{
            $bookingData = json_decode($booking->trip_cost);
            $bookingDevData = json_decode($booking->trip_deviation);
        }

        if(!$booking || $booking->no_of_rooms == null){
            $rooms = 0;
        }else{
            $rooms = $booking->no_of_rooms;
        }

        $res = [
            'trip_price' => $booking->trip->price ?? 0,
            'bookingData' =>$booking,
            'customers' => $data,
            'tripCosts' => $bookingData,
            'tripDeviation' => $bookingDevData,
            'rooms' => $rooms,
            'is_multiple_payment' => $booking->is_multiple_payment,
            'payment_from_tax' => $booking->payment_from_tax,
        ];
        $data = $data->sortBy(function($item) use ($customers) {
            return array_search($item->id, $customers);
        })->values();
     
        $customerDetailsArr = [];
        foreach ($data as $cust) {
            $customerDetailsArr[] = [
                'id' => $cust->id,
                'name' => $cust->first_name . ' ' . $cust->last_name,
                'tier' => $cust->tier ?? '',
            ];
        }
        if(count($customers)) {
            $bookingRecord = TripBooking::where('token', $token)->first();

            if ($bookingRecord) {
                $existingTierDetails = json_decode($bookingRecord->tier_details, true) ?? [];
                $existingCustomerIds = array_column($existingTierDetails, 'id');

                foreach ($customerDetailsArr as $newCust) {
                    if (!in_array($newCust['id'], $existingCustomerIds)) {
                        $existingTierDetails[] = $newCust; // only add if it's a new customer
                    }
                }

                $bookingRecord->customer_id = json_encode($customers);
                $bookingRecord->tier_details = json_encode($existingTierDetails);
                $bookingRecord->save();
            }
        }
        return json_encode($res);
    }

    // booking part
    // send OTP
    public function sendOtpToCustomer(Request $request){
        $customer_id = $request->customer_id;
        $customer = Customer::find($customer_id);
        $token = $request->token;
        $otp = rand(111111, 999999);

        TripBooking::updateOrCreate(
            ['token' => $token],
            ['redeem_points_otp' => $otp, 'admin_id'=>Auth::user()->id]
        );
        $bookingData = TripBooking::where('token', $token)->first();

        if($customer->email != NULL){
            $res = 1;
            $data = [
                'email' => $customer->email,
                'name' => $customer->first_name . " ". $customer->last_name,
                'otp' => $otp,
                'trip_name'=> $bookingData->trip->name ?? "Trip",
            ];
            if(setting('mail_status') == 1){
                event(new SendMailEvent("$customer->email", 'OTP to Redeem Points on Adventures Overland!', 'emails.booking-point-redeem', $data));
            }
            if($res){
                return 1;
            }else{
                return 2;
            }
        }else{
            return 2;
        }
    }

    // verify otp
    public function verifyOTP(Request $request){
        $token = $request->token;
        $redeemable_points = $request->redeemable_points;
        $customer_id = $request->customer_id;

        $booking = TripBooking::where('token', $token)->first();
        if($booking && $booking->redeem_points_otp){
            if($request->user_otp == $booking->redeem_points_otp){
                $oldRedeem = json_decode($booking->redeem_points) ?? [];
                $newData = ['c_id'=> $customer_id, 'points'=>$redeemable_points];

                if (empty($oldRedeem)) {
                    $oldRedeem[] = $newData;
                }else{
                    $cIdExists = false;
                    foreach ($oldRedeem as $record) {
                        if ($record->c_id == $newData['c_id']) {
                            $record->points = $newData['points']; // Update points if c_id exists
                            $cIdExists = true;
                            break;
                        }
                    }
                    if (!$cIdExists) {
                        $oldRedeem[] = $newData; // Insert new record if c_id does not exist
                    }
                }
                $rp = json_encode($oldRedeem);

                TripBooking::updateOrCreate(
                    ['token' => $token],
                    ['redeem_points_verified' => 1, 'redeem_points'=>$rp, 'admin_id'=>Auth::user()->id]
                );

                // debit pts
                $cust = Customer::find($customer_id);
                $cust->points = $cust->points - $redeemable_points;
                $cust->save();
                // debit pts

                $data = [
                    'email' => $cust->email,
                    'points' => $redeemable_points,
                    'remains_point' => $cust->points,
                    'trip_name' => $booking->trip->name ?? "Current Trip",
                    'name' => $cust->first_name . " ". $cust->last_name,
                    'date' => date("Y-m-d"),
                ];
                if(setting('mail_status') == 1){
                    event(new SendMailEvent("$cust->email", 'You successfully redeemed '.$redeemable_points.' points | Adventures Overland!', 'emails.success-point-redeem', $data));
                }

                return 1;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }
}
