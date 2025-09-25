<?php

namespace App\Http\Controllers;

use App\Models\EnqueiryModel;
use App\Models\TransferModel;
use App\Models\Countrie;
use App\Models\State;
use App\Models\Faq;
use App\Models\Trip;
use App\Models\ExtraDocuments;
use App\Models\User;
use App\Models\TripCarbonInfo;
use App\Models\LoaltyPointsModel;
use App\Models\Mytrips;
use App\Models\Admin;
use Carbon\Carbon;
use App\Models\TripBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Http\Controllers\Admin\MailContorller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Session;

class DashboardController extends Controller
{
   

    function home(){
        $user = User::where('id',Auth::user()->id)->first();

        return view('dashboard',['user'=>$user]);
    }

    function myTrip(){
        $cos_id = Auth::user()->id;
        $trip = TripBooking::whereJsonContains('customer_id', "$cos_id")
        ->where('trip_status','!=','Draft')->with('trip')
        ->join('trips', 'trip_bookings.trip_id', '=', 'trips.id') // Adjust this based on your actual relationship column
        ->orderBy('trips.end_date', 'desc')->get();
        $trip->map(function($trip) {
            if (isset($trip->trip->end_date) && $trip->trip->end_date < date("Y-m-d")) {
                $trip->trip_status = "Completed"; // Set status to Completed
            }
           
        });
       
        
        return view('my-trip',['trip'=>$trip]);
    }
    function nearestExpiringPoint()
    {
        // Get all earned points (with expiry) for the logged-in user
        $userId = Auth::user()->id;

        // Fetch all credited (earned) points — regardless of expiry being set or not
        $points = LoaltyPointsModel::where('customer_id', $userId)
            ->where('trans_type', 'Cr') // earned points
            ->orderBy('expiry_date', 'asc') // null expiry will go last
            ->get();
    
        if ($points->isEmpty()) {
            return response()->json(['data' => []], 200);
        }
    
        // Total used points (Dr)
        $usedPoints = LoaltyPointsModel::where('customer_id', $userId)
            ->where('trans_type', 'Dr') // redeemed or transferred
            ->sum('trans_amt');
    
        $adjustedPoints = [];
    
        foreach ($points as $point) {
            $originalAmount = $point->trans_amt;
    
            // Deduct used points in order
            if ($usedPoints >= $originalAmount) {
                $adjustedAmount = 0;
                $usedPoints -= $originalAmount;
            } elseif ($usedPoints > 0) {
                $adjustedAmount = $originalAmount - $usedPoints;
                $usedPoints = 0;
            } else {
                $adjustedAmount = $originalAmount;
            }
    
            // Include only remaining (non-zero) points
            if ($adjustedAmount > 0) {
                $adjustedPoints[] = [
                    'expiry_date' => $point->expiry_date ?? Carbon::parse($point->created_at)->addMonths(6), // fallback expiry
                    'original_points' => $originalAmount,
                    'remaining_points' => $adjustedAmount,
                ];
            }
        }
    
        return response()->json(['data' => $adjustedPoints], 200);
    }




    function tripDetails(Request $request){
        $data = TripBooking::where('token',$request->token)->first();
       
        return view('trip-details', compact('data'));
    }

    function myPoint()
    {
        $redeem = LoaltyPointsModel::where(['customer_id'=>Auth::user()->id, 'trans_type'=>'Dr'])->sum('trans_amt');
        $point = LoaltyPointsModel::where('customer_id',Auth::user()->id)->orderBy('id','desc')->paginate(10);
        return view('my-point',['point'=> $point, 'redeem'=> $redeem]);
    }
    function climbTier(){
        return view('climb-tier');
    }

    function howToEarn()
    {
        return view('how-earn');
    }

    function redeemPoint()
    {
        // $trip = Trip::where('trip_type','Fixed Departure')->orderBy('id', 'desc')->get();
        $trip = Trip::orderBy('name', 'asc')->where('trip_type','Fixed Departure')->where('status', '!=', 'Cancelled')->whereDate('end_date', '>=', date("Y-m-d"))->get();
        return view('remeed-point',['trip'=>$trip]);
    }
    function transferPoint()
    {
        $userId = Auth::user()->id;
        $userEmail = Auth::user()->email;
      
    
        $transfer = LoaltyPointsModel::where('customer_id', $userId)
                                 ->where('trans_type', 'Dr')->where('trans_page', 'transfer')
                                 ->sum('trans_amt');
    
        $receiver = LoaltyPointsModel::where('customer_id', $userId)
                                ->where('trans_type', 'Cr')->where('trans_page', 'transfer')
                                ->sum('trans_amt');
    
        $point = LoaltyPointsModel::where(function ($query) use ($userId, $userEmail) {
                            $query->where('customer_id', $userId);  })->orderBy('id', 'desc')->paginate(10);
    
  
        return view('transfer-point', compact('transfer', 'receiver', 'point'));
    }
    
    function registration(Request $request){
     
        if((isset($_GET['email']) && $_GET['email'] != Null || isset($_GET['form_type'])) && isset($_GET['token'])){
            if(isset($_GET['form_type']) && $_GET['form_type'] == "not_user"){
                $formType = 1;
            }else{
                $formType = 0;
            }
            $data = User::where('email', $_GET['email'])->first();
           
            $bookingData = TripBooking::where('token', $request->token)->first();
            $bookingId = $bookingData->id ?? 0;
            
            $trip = Trip::orderBy('id', 'desc')->get();
            $tripData = Trip::where('id', $request->trip_id)->first();
                
            if(isset($data) && $data != Null){
                
                $extra = ExtraDocuments::where('user_id', $data->id)->get();
                // $carbonData = TripCarbonInfo::where(['booking_id'=>$bookingId, 'customer_id'=>$data->id, 'trip_id'=>$request->trip_id])->first();
                $nonRegUser = [];
                
            }elseif($bookingData){
                
                $customersArr = json_decode($bookingData->customer_id);
                if($customersArr){
                    $nonRegUser = User::whereIn('id', $customersArr)->whereNull('email')->get();
                    if(count($nonRegUser) == 0){
                        return redirect()->route('login');
                    }
                }else{
                    $nonRegUser = [];
                }
                
                $extra = [];
                
            }else{
                return redirect()->route('login');
            }
             session()->regenerate();
             
            return view('registration', ['trip' => $trip, 'tripData'=>$tripData, 'data' => $data,'extra'=>$extra, 'nonRegUsers'=>$nonRegUser, 'form_type'=>$formType]);
        }
        else{
            return redirect()->route('login');
        }
        
    }

    function email(){
        return view('email');
    }
    function transferCoin(Request $request)
    {

        $sender = Auth::user();
      
        $receiver = User::where('email', $request->email)->first();
        if (!$receiver) {
            return redirect()->back()->with('error', 'Receiver not found.');
        }
    
        if ($sender->email === $receiver->email) {
            return redirect()->back()->with('error', 'You cannot send points to yourself.');
        }
    
        if ($sender->points < $request->points) {
            return redirect()->back()->with('error', 'Insufficient Points.');
        }
         if ($sender->points < $request->points) {
            return redirect()->back()->with('error', 'Insufficient Points.');
        }
        $expiryDate = now()->addYears(2)->format('Y-m-d');
    
    
        $sender->points -= $request->points;
        $sender->save();
    
        $receiver->points += $request->points;
        $receiver->save();
    
        $senderTransaction = new LoaltyPointsModel();
        $senderTransaction->customer_id = $sender->id;
        $senderTransaction->sender_email =$sender->email;
        $senderTransaction->receiver_email = $request->email; // fixed
        
        $senderTransaction->reason = 'Transfer Sent ';

        $senderTransaction->trans_type = 'Dr';
        $senderTransaction->trans_amt = $request->points;
        $senderTransaction->balance = $sender->points;
        $senderTransaction->expiry_date = $expiryDate;
        $senderTransaction->trans_page = 'transfer';

        $senderTransaction->save();
    
        $receiverTransaction = new LoaltyPointsModel();
        $receiverTransaction->customer_id = $receiver->id;
        $receiverTransaction->reason = 'Transfer Received'; // fixed
        $receiverTransaction->sender_email = $sender->email;
        $receiverTransaction->receiver_email = $request->email;
        $receiverTransaction->trans_type = 'Cr';
        $receiverTransaction->trans_amt = $request->points;
        $receiverTransaction->balance = $receiver->points;
        $receiverTransaction->expiry_date = $expiryDate;
        $receiverTransaction->trans_page = 'transfer'; // fixed

        $receiverTransaction->save();
    
       
        if ($senderTransaction->exists) {
            Log::info('Sending email to sender: ' . $sender->email);
            $mail = new MailContorller();
            $mail->index2($senderTransaction, 'trans-sender-mail');
        }
    
        if ($receiverTransaction->exists) {
            Log::info('Sending email to receiver: ' . $receiver->email);
            $mail = new MailContorller();
            $mail->index2($receiverTransaction, 'trans-receiver-mail');
        }
    
        return redirect()->back()->with('message', 'Points Sent Successfully.');
    }
    
    function password()
    {
        
        return view('change-password');
    }

    function enquiry()
    {
        $trip = Trip::orderBy('name', 'asc')->where('trip_type','Fixed Departure')->where('status', '!=', 'Cancelled')->whereDate('end_date', '>=', date("Y-m-d"))->get();
        return view('enquiry', ['trip' => $trip]);
    }

    function enquirySubmit(Request $request)
    {
      
        if($request->type == 'enquiry'){
            if(!isset($request->travelerListJson) && $request->travelerListJson == NULL){
                return redirect()->back()->with('error', 'Please Add Treveler');
            }
           
        }
        
        $enquiry = new EnqueiryModel();
        $enquiry->traveler = $request->travelerListJson;
        $enquiry->expedition = $request->expedition;
        $enquiry->tailor_made_comment = $request->tailor_made_comment;
        $enquiry->minor = $request->minor;
        $enquiry->adult = $request->adult;
        $enquiry->redeem_points_status = $request->redeem_points_status;
        $enquiry->is_read = 0;
        if($request->redeem_points_status == 'yes'){
            
            $enquiry->redeem_points = Auth::user()->points;
        }
        $enquiry->customer_id = Auth::user()->id;
        if(Auth::user()->points >= $request->redeem_points){

            $enquiry->redeem_points =$request->redeem_points;
        }else{
            return redirect()->back()->with('error', 'You Have Not Sufficient Coins');

        }
        
        
        $enquiry->save();
       
        if (isset($request->type) && $request->type == 'redeem') {
            
            $mail = new MailContorller();
            $type = 'redeem';
            $mail->index2($enquiry,$type);

            if(setting('mail_status') == 1){
                $type = 'redeem_sales'; 
                $mail->index2($enquiry,$type);
            }
          
            return redirect()->back()->with('message','We have received your request for redeeming points. Someone will get back to you shortly.');

        }
        else{
            $mail = new MailContorller();
            $type = 'enquiry';
            $mail->index2($enquiry, $type);
            return redirect()->back()->with('message','We have recieved your request. Someone will get back to you shortly.');

        }
        

    }

    function profile()
    {   

        return view('profile-page');
    }
    

    function getState(Request $request){

        $value = Countrie::where('name', $request->value)->first();
        $data = State::where('country_id', $value->id)->orderBy('name', 'asc')->get();

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

    function profileUpdate(Request $request)
    { 
        $user = User::find(Auth::user()->id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->dob = $request->dob;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->country = $request->country;
        $user->pincode = $request->pincode;
        $user->pincode = $request->pincode;
        $user->address = $request->address;
        $user->meal_preference = $request->meal_preference;
        $user->profession = $request->profession;
        $user->blood_group = $request->blood_group;
        $user->emg_contact = $request->emg_contact;
        $user->t_size = $request->t_size;
        $user->medical_condition = $request->medical_condition;
         $user->something = $request->something;
        $user->have_road_trip = $request->have_road_trip;
        $user->thrilling_exp = $request->thrilling_exp;
        $user->three_travel = $request->three_travel;
        $user->three_place = $request->three_place;
        $user->letest_trip = $request->letest_trip;
        $user->vaccination = $request->vaccination;
        if ($request->hasFile('profile')) {
            @unlink('storage/app/' . $user->profile);
            $user->profile = $request->file('profile')->store('public/image/user');
        }

        $user->save();
        return redirect()->back();
    }

    function getPrice(Request $request){
        $trip = Trip::where('id',$request->value)->first();
        $price = $trip->price;

        return $price;

    }

    public function registrationSubmit(Request $request)
    {
    
        $data = User::where('email', $request->email)->first();
        $rules = [
            'email' => 'required',
            'phone' => 'required|numeric|digits_between:1,15',
            'state'=> 'required',
            'country'=> 'required',
            'city' => 'required|regex:/^[a-zA-Z\s]+$/',
            'pincode' => 'required|digits:6',
            'address' => 'required',
            'dob' => 'required|date',
            'meal_preference' =>  ['required', 'regex:/^[a-zA-Z\s]+$/'],
            'blood_group' => 'required',
            'emg_name' => ['required','regex:/^[a-zA-Z\s]+$/'],
            'emg_contact' => 'required|numeric|digits_between:1,15',
            't_size' => 'required',
            'medical_condition' => ['required', 'regex:/^[a-zA-Z\s]+$/'],
            'passport_front' => 'mimes:pdf,jpg,jpeg,png|max:3072',
            'profile' => (!empty($data->profile)) ? 'nullable|mimes:jpg,jpeg,png|max:3072' : 'required|mimes:pdf,jpg,jpeg,png|max:3072',
            'pan_gst' => (!empty($data->pan_gst)) ? 'nullable|mimes:pdf,jpg,jpeg,png|max:3072' : 'mimes:pdf,jpg,jpeg,png|max:3072',
            'gst_certificate' => (!empty($data->gst_certificate)) ? 'nullable|mimes:pdf,docx,doc,jpg,jpeg,png|max:3072' : 'mimes:pdf,doc,docx,jpg,jpeg,png|max:3072',
        ];
        if ($request->has('traveller_id')) {
            $rules['traveller_id'] = 'required';
        }
        if ($request->has('first_name')) {
            $rules['first_name'] = 'required|regex:/^[a-zA-Z\s]+$/';
        }
        if ($request->has('last_name')) {
            $rules['last_name'] = 'required|regex:/^[a-zA-Z\s]+$/';
        }
      

        $messages = [
            'first_name.required' => 'First name is mandatory.',
            'first_name.regex' => 'First name must contain only letters and spaces.',
            'last_name.regex' => 'Last name must contain only letters and spaces.',
            'last_name.required' => 'Last name is mandatory.',
            'traveller_id.required' => 'Traveller is mandatory.',
            'email.required' => 'We need your email address.',
            'phone.required' => 'Phone is mandatory.',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
            'state.required' => 'State is mandatory.',
            'country.required' => 'Country is mandatory.',
            'city.required' => 'City is mandatory.',
            'city.regex' => 'City must contain only letters and spaces.',   
            'pincode.required' => 'Pincode is mandatory.',
            'pincode.digits' => 'Pincode must be exactly 6 digits.',
            'address.required' => 'Address is mandatory.',
            'dob.required' => 'Date of birth is mandatory.',
            'meal_preference.required' => 'Meal preference is mandatory.',
            'meal_preference.regex' => 'Meal preference must contain only letters and spaces.',
            'blood_group.required' => 'Blood group is mandatory.',
            'emg_name.required' => 'Emergency name is mandatory.',
            'emg_name.regex' => 'Emergency name must contain only letters and spaces.',
            'emg_contact.required' => 'Emergency contact is mandatory.',
            'emg_contact.digits' => 'Emergency contact must be exactly 10 digits.',
            't_size.required' => 'T-shirt size is mandatory.',
            'medical_condition.required' => 'Medical condition is mandatory.',
            'medical_condition.regex' => 'Medical condition must contain only letters and spaces.',
            'passport_front.mimes' => 'Only PDF, JPEG, PNG, and JPG files are allowed for passport front photo.',
            'passport_front.max' => 'Passport front photo must not exceed 3MB.',
            'profile.required' => 'Profile Image is mandatory.',
            'profile.mimes' => 'Only JPEG, PNG, and JPG files are allowed for profile photo.',
            'pan_gst.mimes' => 'Only PDF, JPEG, PNG, and JPG files are allowed for Pan/GST photo.',
            'gst_certificate.mimes' => 'Only PDF, DOCX, DOC, JPEG, PNG, and JPG files are allowed for GST certificate.',
            'gst_certificate.max' => 'GST certificate must not exceed 3MB.',
        ];
       
        $validated = $request->validate($rules, $messages);
        $tripData = Trip::where('id', $request->letest_trip)->first();
        try
        {
            $user = User::where('id', $request->traveller_id)->first();
            if($user){
                $checkUniqueEmail = User::where('id', '!=', $request->traveller_id)->where('email', $request->email)->first();
                if($checkUniqueEmail){
                    return redirect()->back()->with('error', 'Email Already Exist With Us!');
                }
                $user->email = $request->email;
            }
            else{
                $user = User::where('email', $request->email)->first();
            }
                
            if( $request->first_name){
                $user->first_name = $request->first_name;
            }
            
            if($request->last_name){
                $user->last_name = $request->last_name;
            }
            
            $user->phone = $request->phone;
            $user->dob = $request->dob;
            $user->city = $request->city;
            $user->state = $request->state;
            $user->country = $request->country;
            $user->pincode = $request->pincode; 
            $user->address = $request->address;
            $user->meal_preference = $request->meal_preference;
            $user->profession = $request->profession;
            $user->blood_group = $request->blood_group;
            $user->emg_contact = $request->emg_contact;
            $user->emg_name = $request->emg_name;
            $user->t_size = $request->t_size;
            $user->medical_condition = $request->medical_condition;
            $user->is_form_submitted=1;
        
            $user->letest_trip = $request->letest_trip;
            $originalNames = $user->original_filenames ? json_decode($user->original_filenames, true) : [];
            if ($request->hasFile('profile')) {
                @unlink('storage/app/' . $user->profile);
                $file = $request->file('profile');
                $user->profile = $file->store('public/image/user');
                $originalNames['profile'] = $file->getClientOriginalName();
            }
            if ($request->hasFile('passport_front')) {
            
                @unlink('storage/app/' . $user->passport_front);

                $file = $request->file('passport_front');
                $user->passport_front = $file->store('public/image/user');
                $originalNames['passport_front'] = $file->getClientOriginalName();
            }
            if ($request->hasFile('passport_back')) {
            
                @unlink('storage/app/' . $user->passport_back);
                $file=$request->file('passport_back');
                $user->passport_back =  $file->store('public/image/user');
                $originalNames['passport_back'] = $file->getClientOriginalName();

            }
            if ($request->hasFile('pan_gst')) {
            
                @unlink('storage/app/' . $user->pan_gst);
                $file = $request->file('pan_gst');
                $user->pan_gst = $file->store('public/image/user');
                $originalNames['pan_gst'] = $file->getClientOriginalName();
               
            }
            if ($request->hasFile('gst_certificate')) {
            
                @unlink('storage/app/' . $user->gst_certificate);
                $file = $request->file('gst_certificate');
                $user->gst_certificate = $file->store('public/image/user');
                $originalNames['gst_certificate'] = $file->getClientOriginalName();
            }
            if ($request->hasFile('adhar_card')) {
            
                @unlink('storage/app/' . $user->adhar_card);
                $file = $request->file('adhar_card');
                $user->adhar_card = $file->store('public/image/user');
                $originalNames['adhar_card'] = $file->getClientOriginalName();
            }
            if ($request->hasFile('driving')) {
            
                @unlink('storage/app/' . $user->driving);
                $file = $request->file('driving');
                $user->driving = $file->store('public/image/user');
                $originalNames['driving'] = $file->getClientOriginalName();
            }
            $user->original_filenames = json_encode($originalNames);
            
            if($request->terms_accepted){
                $termsType = 1;
            }else{
                $termsType = 0;
            }
            $user->terms_accepted = $termsType;
            $user->save();
            Log::info('User profile updated or added for user ID: ' . $user->id, ['email' => $user->email]);

            $bookingData = TripBooking::where('token', $request->booking_id)->first();
            $bookingId = $bookingData->id;
            $bookingData->trip_status = 'Confirmed';
            $bookingData->is_form_submitted = 1;
            $bookingData->save();
            Log::info('Booking status updated for booking ID: ' . $bookingId, ['trip_status' => $bookingData->trip_status]);
            
            

            // if($request->carbon_accepted){
            //     $carbon_accepted = 1;
            // }else{
            //     $carbon_accepted = 0;
            // }
            // $carbonData = TripCarbonInfo::where(['booking_id'=>$bookingId, 'customer_id'=>$user->id, 'trip_id'=>$tripData->id])->first();
            // if(!$carbonData){
            //     $carbonData = new TripCarbonInfo();
            // }
            
            // $carbonData->customer_id = $user->id;
            // $carbonData->trip_id = $tripData->id;
            // $carbonData->booking_id = $bookingId;
            // $carbonData->tree_no = $tripData->tree_no;
            // $carbonData->donation_amt = $tripData->donation_amt;
            // $carbonData->terms_accepted = $termsType;
            // $carbonData->carbon_accepted = $carbon_accepted;
            // $carbonData->save();

            if (is_array($request->title)) {
                foreach($request->title as $key=>$title){
                    if(isset($title) && $title != NULL){

                        if(isset($request->id[$key]) && $request->id[$key] != NULL){
                            $doc = ExtraDocuments::where('id',$request->id[$key])->first();
                        } else {
                        
                            $doc = new ExtraDocuments();
                        }
                        $doc->title = $title;
                        if (isset($request->file('image')[$key]) && $request->file('image')[$key] != NULL) {
                            @unlink('storage/app/' . $doc->image[$key]);
                            $doc->image = $request->file('image')[$key]->store('public/image/user/extra');
                        }
                    
                        $doc->user_id = $user->id;
                        $doc->save();
                        Log::info('Extra document saved for user ID: ' . $user->id, ['title' => $title, 'image' => $doc->image]);
                    }
                }
            }
            if(setting('mail_status') == 1){
                $tripName = $tripData->name;
                $mail = new MailContorller();
                $type = 'registartion-user-mail';
                $mail->index2($user, $type);
                
                //mail to registration mail 
                $registration_mail = setting('registration_mail');
                if($registration_mail){
                    $data = [
                        'user_mail'=>$user->email,
                        'admin_email' => $registration_mail,
                        'trip' => $tripName,
                    ];
                    
                    $mail = new MailContorller();
                    $type = 'registartion-mail';
                    $mail->index2($data, $type);
                }
                
            }
            return redirect()->to('https://www.adventuresoverland.com/registration-thank-you/');
        } 
        catch (\Exception $e) {
                \Log::error('Registration error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
                return redirect()->back()->with('error', 'Something went wrong. Please try again or contact support.');
        }
       
    }

  
    function removeImage(Request $request){
        $data = ExtraDocuments::find($request->id);



        @unlink('storage/image/'.$data->image);
        $data->delete($request->id);

        echo 1;
    }

    public function summary(Request $request){
        $token = $request->token;

        $data = TripBooking::where('token', $token)->first();

        $result = [];

        if($data){
            // trip cost
            if($data->trip_cost != null){
                $tripCost = json_decode($data->trip_cost);
                foreach($tripCost as $tc){
                    $tc->traveler = getCustomerById($tc->c_id)->name;
                    $tc->parent = getCustomerById($tc->c_id)->parent;
                    $tc->vehicle_amt = $data->vehical_seat_amt ?? 0;
                    $tc->room_amt = $data->room_type_amt ?? 0;
                }
            }else{
                $tripCost = [];
            }
            $result['trip_costs'] = $tripCost;
            // trip cost

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
            $result['vehicle_type'] = $data->vehical_type ?? '';

            //room info
            $result['room_info'] = json_decode($data->room_info) ?? [""]; 

            // sum of package B
            $packageBSum = 0;
            if(count($result['room_info'])){
                foreach($result['room_info'] as $packB){
                    if($packB->room_type_amt){
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

                $tripTax = json_decode($data->trip_cost);
                $gsts = [];
                foreach($tripTax as $tt){
                    $netCost = $tt->cost + $perTravellerPackageB;
                    $traveler = getCustomerById($tt->c_id)->name;
                    $gst_amt = (($netCost*$gst)/100);
                    $gst_per = $gst;
                    array_push($gsts, ['c_id'=>$tt->c_id,'traveler'=>$traveler, 'gst'=>$gst_amt, 'gst_per'=>$gst_per]);
                }

                $tcss = [];
                foreach($tripTax as $tt){
                    $netCost = $tt->cost + $perTravellerPackageB;
                    $traveler = getCustomerById($tt->c_id)->name;
                
                    if($data->payment_from == "Individual" && $data->payment_from_tax != null){
                        $tcs_amt = (($netCost*$data->payment_from_tax)/100);
                        $tcs_per = $data->payment_from_tax;
                    }elseif($data->payment_from == "Company"){
                        $tcs_amt = (($netCost*$tcs)/100);
                        $tcs_per = $tcs;
                    }
                    array_push($tcss, ['c_id'=>$tt->c_id, 'traveler'=>$traveler, 'tcs'=>$tcs_amt, 'tcs_per'=>$tcs_per]);
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

            // package offered A
            if($data->trip_cost != null){
                $packageOfferA = json_decode($data->trip_cost);
                $rps = json_decode($data->redeem_points);
                foreach($packageOfferA as $tc){
                    $tc->traveler = getCustomerById($tc->c_id)->name;
                    if($rps){
                        foreach($rps as $rp){
                            if($rp->c_id == $tc->c_id){
                                $tc->cost = $tc->cost - $rp->points;
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
                $A_B = json_decode($data->trip_cost);
                $rps = json_decode($data->redeem_points);
                foreach($A_B as $tc){
                    $tc->traveler = getCustomerById($tc->c_id)->name;
                    $tc->cost = $tc->cost + $perTravellerPackageB;
                    if($rps){
                        foreach($rps as $rp){
                            if($rp->c_id == $tc->c_id){
                                $tc->cost = $tc->cost - $rp->points;
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
                $packageC = json_decode($data->trip_cost);
            
                $rps = json_decode($data->redeem_points);
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

            // points calc
            if($data->customer_id){
                $pointsArr = [];
                $actualTripCost = [];
                foreach(json_decode($data->customer_id) as $customer){
                    $actualCost = getTripCostWithoutTaxByBookingAndCustomerId($data->id, $customer);
                    
                    // actual trip cost
                    array_push($actualTripCost, ['traveler'=>getCustomerById($customer)->name ?? "Deleted", 'cost'=>$actualCost]);
                    // actual trip cost
                    
                    $cDataa = getCustomerById($customer)->parent;
                    $cTier = getPointPerByCustomerId($customer);
                    $points = ($actualCost * $cTier)/100;
                    // dd($points);
                    if($cDataa == 0 && !isset($pointsArr[$customer])){
                        array_push($pointsArr, [$customer => ['points'=>$points, 'name'=>getCustomerById($customer)->name, 'reward'=>$cTier]]);
                    }else{
                        if ($pointsArr && array_key_exists($cDataa, $pointsArr[0])) {
                            $pointsArr[0][$cDataa]['points'] = $pointsArr[0][$cDataa]['points'] + $points;
                        }
                    }
                }
                // dd($actualTripCost);
                $result['points_list'] = $pointsArr;
                $result['actual_trip_cost'] = $actualTripCost;
                $result['real_trip_amt'] = $data->trip->price ?? "0";
            }
            
            return json_encode($result);
        }
    } 

    function faq(){
        $data = Faq::orderBy('created_at', 'desc')->get();
        return view('faq',['data'=>$data]);
    }
}