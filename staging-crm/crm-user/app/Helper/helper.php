<?php

use App\Models\Countrie;
use App\Models\Customer;
use App\Models\ExtraService;
use App\Models\Setting;
use App\Models\Trip;
use App\Models\TripBooking;
use App\Models\LoaltyPointsModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

    function tripCount(){
        $cos_id = Auth::user()->id;
        $trip = TripBooking::whereJsonContains('customer_id', "$cos_id")->orderBy('id', 'desc')->get();
        return $trip;
    }

    function allCountry(){
        $country = Countrie::all();

        return $country;
    }

    function setting($key){
        $setting = Setting::find(1);
       return $setting->$key;
    }
if (!function_exists('getCustomerById')) {
    function getCustomerById($id)
    {
        $res = User::find($id);


        $res->name = $res->first_name . " " . $res->last_name;
        return $res;
    }
}
if (!function_exists('getYearsFromDob')) {
    function getYearsFromDob($date)
    {
        $today = new DateTime('today');
        $diff = date_diff(new DateTime($date), $today);
        $age = $diff->y;
        return $age;
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
        return $per;
    }
}

if(!function_exists('getPoints')){
    function getPoints($id){
        $usedPoints = LoaltyPointsModel::where('customer_id', $id)
            ->where('trans_type', 'Dr') 
            ->sum('trans_amt');
        $earnedPoints = LoaltyPointsModel::where('customer_id', $id)
            ->where('trans_type', 'Cr') 
            ->sum('trans_amt');

        $res = $earnedPoints - $usedPoints;
        $res= max(0,$res);
        return $res;
    }
}
?>