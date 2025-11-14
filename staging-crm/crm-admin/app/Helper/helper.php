<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Setting;
use App\Models\User;
use App\Models\Customer;
use App\Models\TripBooking;
use App\Models\Trip;
use App\Models\Expense;
use App\Models\TripCarbonInfo;
use App\Models\ExtraService;
use App\Models\VendorService;
use App\Models\VendorCategory;
use App\Models\Merchandise;
use App\Models\Stationary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Mail\Email;

if(!function_exists('setting')){
    function setting($key){
        $res = Setting::where('id', 1)->first();
        return $res[$key];
    }
}

if(!function_exists('getTripById')){
    function getTripById($id){
        $res = Trip::find($id);
        return $res;
    }
}

if(!function_exists('getCustomerById')){
    function getCustomerById($id){
        $res = Customer::find($id);
        if($res){
            $res->name = $res->first_name ." ".$res->last_name;
        }else{
            $res = null;
        }
        return $res;
    }
}

if(!function_exists('getTierPerByCustomer')){
    function getTierPerByCustomer($num){
        $customers = Customer::count();
        if($customers){
            $res = ($num*100)/$customers;
        }else{
                $res = 0;
        }
        return $res;
    }
}

if(!function_exists('getCustomerByEmail')){
    function getCustomerByEmail($id){
        $res = Customer::where('email', $id)->first();
        $res->name = $res->first_name ." ".$res->last_name;
        return $res;
    }
}

if(!function_exists('checkAdminRole')){
    function checkAdminRole(){
        $user = User::find(Auth::user()->id);

        if($user->role_id == 0){
            return true;
        }

        $res = $user->role->permission;
        if($res->admin == 1){
            return true;
        }else{
            return false;
        }
    }
}

if(!function_exists('getYearsFromDob')){
    function getYearsFromDob($date){
        $today = new DateTime('today');
        $diff = date_diff(new DateTime($date), $today);
        $age = $diff->y;
        return $age;
    }
}

if(!function_exists('getDaysDiff')){
    function getDaysDiff($date2, $date1){
        $diff = date_diff(new DateTime($date2), new DateTime($date1));
        $res = $diff->days;
        return $res;
    }
}

if(!function_exists('getTripCountbyCustomerId')){
    function getTripCountbyCustomerId($id){
        $res = TripBooking::whereJsonContains('customer_id', "$id")
                            ->where('trip_status', '!=', 'Cancelled')
                            ->where('trip_status', '!=', 'Draft')
                            ->count();
        return $res;
    }
}

if(!function_exists('getReferralByAgent')){
    function getReferralByAgent($id){
        $res = TripBooking::where('lead_source', 'Agent')->where('sub_lead_source', "$id")->where('trip_status', "!=" ,"Draft")->count();
        return $res;
    }
}

if(!function_exists('getPaxFromTripId')){
    function getPaxFromTripId($id){
        $res = TripBooking::where('trip_id', $id)->whereNotIn('trip_status', ['Cancelled', 'Draft'])->get();
        $count = 0;
        foreach($res as $item){
            $cc = count(json_decode($item->customer_id));
            $count += $cc;
        }
        return $count;
    }
}


if(!function_exists('totalTripCostById')){
    function totalTripCostById($id){
        $res = TripBooking::where('trip_id', $id)->where('trip_status', "!=" ,"Draft")->get();
        $amt = 0;
        foreach($res as $item){
            $costs = json_decode($item->trip_cost);
            foreach($costs as $cost){
                $amt += $cost->cost ?? 0;
            }
        }
        return $amt;
    }
}

if(!function_exists('totalTripAmountRcvdById')){
    function totalTripAmountRcvdById($id){
        $res = TripBooking::where('trip_id', $id)->whereNotIn('trip_status', ['Cancelled', 'Draft'])->get();
        $amt = 0;
        if($res){
            foreach($res as $item){

                $amt += $item->payment_amt ?? 0;
               
                if($item->part_payment_list){
                    $pps = json_decode($item->part_payment_list);
                   
                    foreach($pps as $cost){
                        $amt += $cost->amount ?? 0;
                    }
                }
            }
            
        }
        
        return $amt;
    }
}

if(!function_exists('actualTripCostSumById')){
    function actualTripCostSumById($id){
        $res = TripBooking::where('trip_id', $id)->whereNotIn('trip_status', ['Cancelled', 'Draft'])->get();
    
        $amt = 0;
        if($res){
            foreach($res as $item){
                if($item->trip_cost){
                    $pps = json_decode($item->trip_cost);
                    foreach($pps as $cost){
                        $amt += $cost->cost ?? 0;
                    }
                    // dd($pps);
                }
            }
        }
        // dd($amt);
        return $amt;
    }
}

if(!function_exists('totalPayableAmtOfTrip')){
    function totalPayableAmtOfTrip($id){
        $res = TripBooking::where('trip_id', $id)
        ->whereNotIn('trip_status', ['Cancelled', 'Draft'])
        ->sum('payable_amt');
        $amt = $res ?? 0;
        return $amt;
    }
}

if(!function_exists('totalTripCost')){
    function totalTripCost(){
        $res = TripBooking::where('trip_status', "!=" ,"Draft")->sum('payable_amt');
        $amt = $res ?? 0;
        return $amt;
    }
}

if(!function_exists('totalTripProfit')){
    function totalTripProfit(){
        $res = TripBooking::where('trip_status', "!=" ,"Draft")->sum('payable_amt');
        $amt = $res ?? 0;

        $exp = Expense::sum('total_amount');

        $profit = $amt - $exp;
        return $profit;
    }
}

if(!function_exists('totalTripCostOfCustomerById')){
    function totalTripCostOfCustomerById($cId, $bId=null){
        $res = TripBooking::whereJsonContains('customer_id', "$cId");
        if($bId){
            $res->where('id', $bId);
        }
        $res = $res->whereNotIn('trip_status', ['Cancelled'])->get();

        $tripCost = 0;
        $carbonAmt = 0;
        $extraCost = 0;
        $taxAmt = 0;
        $devAmt = 0;
        $vehicleSecAmt = 0;
        $supplimentry = 0;

        foreach($res as $item){
            $costs = json_decode($item->trip_cost);
            if($costs){
                foreach($costs as $cost){
                    if($cost->c_id == $cId){
                        $tripCost += $cost->cost ?? 0;
                    }
                }
            }

            $tripDev = json_decode($item->trip_deviation);
            if($tripDev){
                foreach($tripDev as $dev){
                    if($dev->c_id == $cId){
                        if($dev->deviation_type == "Add"){
                            $devAmt += $dev->deviation_amt ?? 0;
                        }else{
                            $devAmt -= $dev->deviation_amt ?? 0;
                        }
                    }
                }
            }

            $exc = json_decode($item->extra_services);
            if($exc){
                foreach($exc as $ex){
                    if($ex->traveler == $cId){
                        $totalEx = $ex->amount + $ex->markup + (($ex->markup*$ex->tax)/100);
                        $extraCost += $totalEx;
                    }
                }
            }

            $packageBSum = 0;
            $room_info = json_decode($item->room_info) ?? [""];
            if(count($room_info)){
                foreach($room_info as $packB){
                    if($packB && $packB->room_type_amt){
                        $packageBSum += $packB->room_type_amt;
                    }
                }
            }
            $packageBSum += $item->vehical_seat_amt;
            $tripCostData = json_decode($item->trip_cost);
            if (is_array($tripCostData) && count($tripCostData) > 0) {
                $travellerCount = count($tripCostData);
            } else {
                $travellerCount = 1;
            }
            $perTravellerPackageB = $packageBSum/$travellerCount;
            $supplimentry += $perTravellerPackageB;

            $vehicleSecAmt += ($item->vehical_security_amt ?? 0) / $travellerCount;

            if($item->tax_required == 0 || $item->tax_required == null){
                $gst = setting('gst') ?? 0;
                $tcs = setting('tcs') ?? 0;

                $tripTax = json_decode($item->trip_cost);
                $gst_amt = 0;
                if($tripTax){
                    foreach($tripTax as $tt){
                        if($tt->c_id == $cId){
                            $netCost = $tt->cost + $perTravellerPackageB;
                            $gst_amt += (($netCost*$gst)/100);
                        }
                    }
                }

                $tcs_amt = 0;
                if($tripTax){
                    foreach($tripTax as $tt){
                        if($tt->c_id == $cId){
                            $netCost = $tt->cost + $perTravellerPackageB;
                            if($item->payment_from == "Individual" && $item->payment_from_tax){
                                if($item->payment_from_tax == "Auto"){
                                    if($netCost > 700000){
                                        $netCost1 = 700000;
                                        $netCost2 = $netCost - $netCost1;
                                        $tcs_amt1 = (($netCost1*5)/100);
                                        $tcs_amt2 = (($netCost2*20)/100);
                                        $tcs_amt = $tcs_amt1 + $tcs_amt2;
                                    }else{
                                        $tcs_amt = (($netCost*5)/100);
                                    }
                                    $tcs_per = $item->payment_from_tax;
                                }
                                else if($item->payment_from_tax == "Manual"){
                                     if (!property_exists($tt, 'amount_1') || $tt->amount_1 === null) {
                                            $tt->amount_1 = 0;
                                        }
                                        if (!property_exists($tt, 'amount_2') || $tt->amount_2 === null) {
                                            $tt->amount_2 = 0;
                                        }
                                        if (!property_exists($tt, 'tcs_1') || $tt->tcs_1 === null) {
                                            $tt->tcs_1 = 0;
                                        }
                                        if (!property_exists($tt, 'tcs_2') || $tt->tcs_2 === null) {
                                            $tt->tcs_2 = 0;
                                        }
                                    $tcs1_amt = (($tt->amount_1*$tt->tcs_1)/100);
                                    $tcs2_amt = 0;
                                    if($tt->tcs_2 != null){
                                        $tcs2_amt = (($tt->amount_2*$tt->tcs_2)/100);
                                    }
                                    $tcs_amt = $tcs1_amt + $tcs2_amt;
                                    $tcs_per = $item->payment_from_tax;
                                }
                                else{
                                    $tcs_amt = (($netCost*$item->payment_from_tax)/100);
                                    $tcs_per = $item->payment_from_tax;
                                }
                            }elseif($item->payment_from == "Company"){
                                if($item->is_tds == 0){
                                    $tcs = 0;
                                }
                                $tcs_amt += (($netCost*$tcs)/100);
                            }
                        }
                    }
                }

                $taxAmt += $gst_amt + $tcs_amt;
            }

            $carbon = TripCarbonInfo::where('customer_id', $cId)->where('booking_id', $item->id)->first();
            $carbonAmt += $carbon->donation_amt ?? 0;
        }

        $totalSpend = $tripCost + $extraCost + $taxAmt + $vehicleSecAmt + $supplimentry + $carbonAmt + $devAmt;

        return $totalSpend;
    }
}

if(!function_exists('getPointPerByCustomerId')){
    function getPointPerByCustomerId($bookingId,$id){

   $tier = null;
        $booking=TripBooking::find($bookingId);
        if($booking && $booking->tier_details){
           
            $tierDetails = json_decode($booking->tier_details, true);
            if(is_array($tierDetails))
            {
                foreach($tierDetails as $tierDetail){
                    if($tierDetail['id'] == $id){
                        $tier= $tierDetail['tier'];
                        break;
                    }
                }

            }
           
        }
    
        if(!$tier){
            $res = Customer::find($id);
            $tier = $res->tier;
        }
      

        $per = 1;
        if($tier == "Discovery"){
            $per = 1;
        }elseif($tier == "Adventurer"){
            $per = 2;
        }elseif($tier == "Explorer"){
            $per = 3;
        }elseif($tier == "Legends"){
            $per = 5;
        }
        // dd($per);
        return $per;
    }
}

if(!function_exists('getTripCostByBookingAndCustomerId')){
    function getTripCostByBookingAndCustomerId($bId, $cId){
        $res = TripBooking::where('id', $bId)->whereJsonContains('customer_id', "$cId")->where('trip_status', "Completed")->get();
        $tripCost = 0;
        foreach($res as $item){
            $costs = json_decode($item->trip_cost);
            if($costs){
                foreach($costs as $cost){
                    if($cost->c_id == $cId){
                        $tripCost += $cost->cost ?? 0;
                    }
                }
            }
        }

        $extraCost = 0;
        foreach($res as $item){
            $exc = json_decode($item->extra_services);
            if($exc){
                foreach($exc as $ex){
                    if($ex->traveler == $cId){
                        $totalEx = $ex->amount + $ex->markup + (($ex->markup*$ex->tax)/100);
                        $extraCost += $totalEx;
                    }
                }
            }
        }

        $taxAmt = 0;
        foreach($res as $item){
            if($item->tax_required == 0 || $item->tax_required == null){
                $gst = setting('gst') ?? 0;
                $tcs = setting('tcs') ?? 0;

                $tripTax = json_decode($item->trip_cost);
                $gst_amt = 0;
                if($tripTax){
                    foreach($tripTax as $tt){
                        if($cost->c_id == $cId){
                            $netCost = $tt->cost;
                            $gst_amt += (($netCost*$gst)/100);
                        }
                    }
                }

                $tcs_amt = 0;
                if($tripTax){
                    foreach($tripTax as $tt){
                        $netCost = $tt->cost;
                        if($item->payment_from == "Individual" && $item->payment_from_tax){
                            if($item->payment_from_tax == "Auto"){
                                if($netCost > 700000){
                                    $netCost1 = 700000;
                                    $netCost2 = $netCost - $netCost1;
                                    $tcs_amt1 = (($netCost1*5)/100);
                                    $tcs_amt2 = (($netCost2*20)/100);
                                    $tcs_amt = $tcs_amt1 + $tcs_amt2;
                                }else{
                                    $tcs_amt = (($netCost*5)/100);
                                }
                                $tcs_per = $item->payment_from_tax;
                            }else{
                                $tcs_amt = (($netCost * (int)$item->payment_from_tax) / 100);
                                $tcs_per = $item->payment_from_tax;
                            }
                        }elseif($item->payment_from == "Company"){
                            if($item->is_tds == 0){
                                $tcs = 0;
                            }
                            $tcs_amt += (($netCost*$tcs)/100);
                        }
                    }
                }

                $taxAmt = $gst_amt + $tcs_amt;
            }
        }
        // dd($tripCost, $extraCost, $taxAmt);
        $totalSpend = $tripCost + $extraCost + $taxAmt;
        return $totalSpend;
    }
}

if(!function_exists('getTripCostWithoutTaxByBookingAndCustomerId')){
    function getTripCostWithoutTaxByBookingAndCustomerId($bId, $cId){
        $res = TripBooking::where('id', $bId)->whereJsonContains('customer_id', "$cId")->get();
        $tripCost = 0;
        foreach($res as $item){
            $costs = json_decode($item->trip_cost);
            if($costs){
                foreach($costs as $cost){
                    if($cost->c_id == $cId){
                        $tripCost += $cost->cost ?? 0;
                    }
                }
            }
        }

        $extraCost = 0;
        $ExtraServices = ExtraService::select('title')->where('is_redeemable', 1)->pluck('title')->toArray();

        foreach($res as $item){
            $exc = json_decode($item->extra_services);
            if($exc){
                foreach($exc as $ex){
                    if(in_array($ex->services, $ExtraServices)){
                        if($ex->traveler == $cId){
                            $totalEx = $ex->amount;
                            $extraCost += $totalEx;
                        }
                    }
                }
            }
        }

        $redeemedPoints = 0;
        foreach($res as $item){
            $redeems = json_decode($item->redeem_points);
            if($exc && $redeems){
                foreach($redeems as $redeem){
                    if($redeem->c_id == $cId){
                        $totalPoints = $redeem->points;
                        $redeemedPoints += $totalPoints;
                    }
                }
            }
        }

        // $vsa = 0;
        // foreach($res as $item){
        //     $vsa += $item->vehical_seat_amt ?? 0;
        // }

        // $roomAmt = 0;
        // foreach($res as $item){
        //     $rooms = json_decode($item->room_info);
        //     if($rooms){
        //         foreach($rooms as $rps){
        //             $roomAmt += (int)$rps->room_type_amt ?? 0;
        //         }
        //     }
        // }

        // $totalSpend = $tripCost + $extraCost + $vsa + $roomAmt;
        $totalSpend = $tripCost + $extraCost - $redeemedPoints;
        return $totalSpend;
    }
}

if(!function_exists('getCustomerCountByTripId')){
    function getCustomerCountByTripId($id){
        $res = TripBooking::where('trip_status', '!=','Draft')
                ->where('trip_id', $id)->get();


        $count = 0;
        if($res){
            foreach($res as $booking){
                if($booking->customer_id){
                    $count += count(json_decode($booking->customer_id));
                }
            }
        }
        return $count;
    }
}

if(!function_exists('getVendorCategoryById')){
    function getVendorCategoryById($id){
        $res = VendorCategory::find($id);
        return $res;
    }
}

if(!function_exists('getVendorServiceById')){
    function getVendorServiceById($id){
        $res = VendorService::find($id);
        return $res;
    }
}

if(!function_exists('getExtraServiceByName')){
    function getExtraServiceByName($name){
        $res = ExtraService::where('title', $name)->first();
        return $res;
    }
}

if(!function_exists('getstationaryById')){
    function getstationaryById($id){
        $res = Stationary::find($id);
        return $res;
    }
}

if(!function_exists('getMerchandiseById')){
    function getMerchandiseById($id){
        $res = Merchandise::find($id);
        return $res;
    }
}

if(!function_exists('getTelephoneCode')){
    function getTelephoneCode(){
        $res = DB::table('telephone_codes')->get();
        return $res;
    }
}

function indian_number_format($number, $decimals = 2) {
    $formatted = number_format($number, $decimals, '.', '');

    $parts = explode('.', $formatted);
    $integerPart = $parts[0];
    $decimalPart = isset($parts[1]) ? '.' . $parts[1] : '';

    $lastThree = substr($integerPart, -3);
    $restUnits = substr($integerPart, 0, -3);
    if (strlen($restUnits) > 0) {
        $restUnits = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $restUnits);
        $integerPart = $restUnits . ',' . $lastThree;
    }

    return $integerPart . $decimalPart;
}

if(!function_exists('checkTripEditable')){
    function checkTripEditable($id){
        $date = date("Y-m-d");
        $res = Trip::find($id);
        $check = 1;
        if(strtotime($date) > strtotime($res->end_date)){
            if(getDaysDiff($date, $res->end_date) > setting('trip_edit_limit_days')){
                $check = 0;
            }
        }

        return $check;
    }
}

if (!function_exists('getAllTrips')) {
    function getAllTrips()
    {
        $trips = Trip::all();
        return $trips;
    }
}
