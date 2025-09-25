<?php

namespace App\Http\Controllers\Api;
use App\Models\TripBooking;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class BookingApiController extends Controller
{
    public function createBooking(Request $request)
    {
        try {
            $request->validate([
                'booking_for' => 'required|string',
                'trip_id' => 'required|integer',
                'expedition' => 'required|string',
                'customer_ids' => 'required|Array',
                'sub_lead_source' => 'nullable|string',
                'no_of_rooms' => 'required|integer',
                'room_type' => 'required|array',
                'room_cat' => 'required|array',
                'room_type_amt' => 'nullable|array',
                'payment_from' => 'required|string',
                'payment_from_cmpy' => 'nullable|string',
                'payment_from_tax' => 'nullable|string',
                'billing_customer_id' => 'required|array',
                'payment_all_done_by_this'=>'nullable|integer',
                'payment_by_customer_id'=>'nullable|integer',
                'trip_costs' => 'required|array',
                'sch_amount' => 'required|array',
                'sch_date' => 'required|array',
                'payment_type' => 'required|string',
                'payment_amt' => 'required|numeric',
                'payment_date' => 'required|date',

            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }

        $customers = $request->input('customer_ids', []);
            if (!is_array($customers)) {
                $customers = [$customers];
        } 
        if ($request->booking_for === 'solo' && count($customers) !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Solo booking must have exactly one customer.'
            ], 400);
        }    
        $token = Str::random(40);
        $hashedToken = hash('sha256', $token);
        $admin_id=1;

        /**Tier details */
        $data = Customer::select('id', 'first_name', 'last_name', 
        'email', 'telephone_code', 'phone', 'points', 'parent', 
        'gender', 'dob','tier')
        ->whereIn('id', $customers)
        ->get();
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
        /** End Tier details */

       
       
        $booking = new TripBooking();
        $admin_id=1;
        $booking->token = $hashedToken;
        $booking->booking_for = $request->booking_for;
        $booking->trip_id = $request->trip_id;
        $booking->customer_id = json_encode($customers);;
        $booking->expedition = $request->expedition;
        $booking->admin_id = $admin_id;
        $booking->booking_from='Website';

        $booking->lead_source ="Online";
        $booking->sub_lead_source = $request->sub_lead_source;

        /** Vehicle Information */
        $booking->vehical_type = "AO Vehcile";
        $booking->vehical_seat = $request->vehical_seat;
        $booking->vehical_seat_amt = $request->vehical_seat_amt ?? 0;
        $booking->vehical_security_amt = $request->vehical_security_amt ?? 0;
        $booking->vehical_security_amt_cmt = $request->vehical_security_amt_cmt ?? '';
        /** End Vehicle Information */


        /** Room Information */
        $booking->no_of_rooms = $request->no_of_rooms;
        $room_type = $request->room_type;
        $room_type_amt = $request->room_type_amt ?? 0;
        $room_cat = $request->room_cat;
        $arr = [];
        foreach($room_type as $key=>$type){
            $data = ['room_type'=> $type, 'room_type_amt'=>$room_type_amt[$key] ?? 0, 'room_cat'=>$room_cat[$key]];
            array_push($arr, $data);
        }
        $booking->room_info = json_encode($arr);
        /** End Room Information */
        $booking->payment_from = $request->payment_from;
        $booking->payment_from_cmpny = $request->payment_from_cmpy;
        $booking->payment_from_tax = $request->payment_from_tax;
        $booking->billing_to = $request->billing_customer_id ? json_encode($request->billing_customer_id) : null;
        
        $booking->payment_all_done_by_this = $request->payment_all_done_by_this ?? 0;
        $booking->payment_by_customer_id = $request->payment_by_customer_id ?? null;
        

        /** Trip Cost Information */
        $c_ids = $request->c_id;
        $trip_costs = $request->trip_costs;
        $trip_cost_cmts = $request->trip_cost_cmt ?? [];
        $multiple_payment_tax = $request->multiple_payment_tax ?? [];

        $arr = [];
        $arrDev = [];
        foreach ($c_ids as $key => $c_id) {
            $data = [
                'c_id' => $c_id,
                'cost' => $trip_costs[$key],
                'comment' => $trip_cost_cmts[$key] ?? "NA",
                'multiple_payment_tax' => $multiple_payment_tax[$key] ?? null
            ];
             // for deviation
            foreach ($request->deviation_amt as $index => $amt) {
                $arrDev[] = [
                    'c_id'=> $c_id,
                    'deviation_type'    => $request->deviation_type[$index] ?? null,
                    'deviation_amt'     => $amt,
                    'deviation_comment' => $request->deviation_comment[$index] ?? 'NA',
                ];
            }
            $arr[] = $data;
            
        }
        $booking->trip_deviation = json_encode($arrDev);
        $booking->trip_cost = json_encode($arr);
        /** End Trip Cost Information */
        if (count($customers)) {
            if ($booking) {
           
                $newTierDetails = [];

                foreach ($customerDetailsArr as $newCust) {
                    $newTierDetails[] = $newCust; 
                }
                $booking->tier_details = json_encode($newTierDetails);
               
            }
        }
        /** Tax required or not */
        if($request->tax_required){ $tax = 'Yes'; }else{ $tax = 'No';}
        if($request->tax_type == "all"){ $booking->tax_required = $request->tax_required;}
        elseif($request->tax_type == "tds"){ $booking->is_tds = $request->tax_required;  }

        /** End Tax required or not */

        $booking->payment_type =$request->payment_type ?? null;
        $booking->payment_amt = $request->payment_amt ?? 0;
        $booking->payment_date = $request->payment_date ?? null;
        

        /** Scheduled Payment List */
        $amount = $request->sch_amount;
        $date = $request->sch_date;
        $cmt = $request->sch_comment;
        $arr = [];
        foreach($amount as $key=>$amt){
            $data = ['amount'=>$amt, 'date'=>$date[$key],'comment'=>$cmt[$key] ?? "NA"];
            array_push($arr, $data);
        }
        $booking->sch_payment_list = json_encode($arr);
        /** End Scheduled Payment List */

        $booking->form_submited =1;
        $booking->payable_amt = $request->payable_amt ?? 0;
        $booking->is_form_submitted=0;
        $booking->invoice_status='Pending';
        $booking->trip_status = 'Confirmed';


        $booking->save();
     
        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking
        ], 201);
    }
}
