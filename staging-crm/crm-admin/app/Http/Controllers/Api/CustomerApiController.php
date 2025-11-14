<?php

    /**
     * Add a new member to a customer and create customer and update with id  .
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\LoyalityPts;
use App\Models\ActivityTracker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerApiController extends Controller
{


    public function store(Request $request)
    {
        try {
            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:customers,email'],
                'phone' => ['required', 'unique:customers,phone'],
                'gender' => ['required'],
                'telephone_code' => ['required'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors()
            ], 422);
        }

        $data = new Customer();
        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->gender = $request->gender;
        $data->telephone_code = $request->telephone_code;
        $randomPassword = Str::random(10);
        $data->password = Hash::make($randomPassword);
        $data->terms_accepted = 0;

        if ($request->refer_by) {
            $checkUser = Customer::where('email', $request->refer_by)->first();
            if ($checkUser) {
                $expiryDate = now()->addYears(2)->format('Y-m-d');
                $referrel_points = intval($request->trip_cost * 0.01);
                $checkUser->points += $referrel_points;
                $checkUser->save();

                $referralTransaction = new LoyalityPts();
                $referralTransaction->customer_id = $checkUser->id;
                $referralTransaction->trip_name = $request->trip_name ?? null;
                $referralTransaction->reason = 'Referral';
                $referralTransaction->trans_type = 'Cr';
                $referralTransaction->trans_amt = $referrel_points;
                $referralTransaction->balance = $checkUser->points;
                $referralTransaction->expiry_date = $expiryDate;
                $referralTransaction->trans_page = 'Booking trip Referral';
                $referralTransaction->save();

                $data->referred_by = $request->refer_by;
            }
        }

        $data->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Customer added successfully',
            'customer' => $data,
        ],201);
    }


    public function update(Request $request, $id)
    {
        
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found'
            ], 404);
        }

        try {
            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name'  => ['required', 'string', 'max:255'],
                'email'      => ['required', 'email', 'max:255', 'unique:customers,email,' . $id],
                'telephone_code' => ['required'],
                'phone'      => ['required'],
                'gender'     => ['required'],
                'dob'            => ['required', 'date'],
                'country'        => ['required'],
                'state'          => ['required'],
                'city'           => ['required'],
                'pincode'    => ['required'],
                'address'        => ['required'],
                'meal_preference'      => ['required', 'string', 'max:255'],
                'blood_group'  => ['required', 'string', 'max:255'],
                'profession'   => ['required', 'string', 'max:255'],
                'emg_contact'    => ['required'],
                'emg_name'  => ['required', 'string', 'max:255'],  
                't_size'       => ['required', 'string', 'max:10'],
                'medical_condition' => ['required', 'string', 'max:500'],
                
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors()
            ], 422);
        }

     
        $customer->first_name     = $request->first_name;
        $customer->last_name      = $request->last_name;
        $customer->email          = $request->email;
        $customer->telephone_code = $request->telephone_code;
        $customer->phone          = $request->phone;
        $customer->gender         = $request->gender;
        $customer->dob            = $request->dob;
        $customer->country        = $request->country;
        $customer->state          = $request->state;    
        $customer->city           = $request->city;
        $customer->pincode        = $request->pincode;
        $customer->address        = $request->address;
        $customer->meal_preference = $request->meal_preference;
        $customer->blood_group    = $request->blood_group;
        $customer->profession     = $request->profession;
        $customer->emg_contact    = $request->emg_contact;
        $customer->emg_name       = $request->emg_name;
        $customer->t_size         = $request->t_size;
        $customer->medical_condition = $request->medical_condition;


        $customer->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Customer updated successfully',
            'customer' => $customer,
        ], 200);
    }

  
    public function addMembers(Request $request)
    {
      
        try{
             $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required'],
            'dob'   => ['required', 'date'],
            'parent' => ['required'],
            'relation' => ['required'],
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors()
            ], 422);

        }
        $data = new Customer();
        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->gender = $request->gender;
        $data->dob = $request->dob;
        $data->parent = $request->parent;
        $data->relation = $request->relation;
        $data->terms_accepted = 0;
        $data->save();

        return response()->json(['status' => 'success', 'message' => 'Member added successfully', 'member' => $data,],201);
    }

}
