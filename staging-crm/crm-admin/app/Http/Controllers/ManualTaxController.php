<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ManualTax;
use App\Models\TripBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManualTaxController
{
    public function getCustomerManualTax(Request $request)
    {
        $request->validate([
            'customer_ids' => 'required|array',
            'customer_ids.*' => 'integer',
        ]);

        $token = $request->token;

        // Fetch the trip booking using the token
        $booking = TripBooking::where('token', $token)->first();
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        $tripBookingId = $booking->id;

        // Decode the customer_id array from the booking table
        $customerIds = json_decode($booking->customer_id, true);

        // Fetch customers with manual tax data
        $customers = DB::table('customers')
            ->whereIn('id', $customerIds)
            ->get();

        // Fetch manual taxes for the provided customer IDs and trip_booking_id
        $manualTaxes = DB::table('manual_taxes')
            ->where('trip_booking_id', $tripBookingId)
            ->whereIn('customer_id', $request->customer_ids)
            ->get();

        // Combine customers and manual taxes
        $result = $customers->map(function ($customer) use ($manualTaxes) {
            // Match manual tax for the current customer
            $manualTax = $manualTaxes->firstWhere('customer_id', $customer->id);

            return [
                'customer_id' => $customer->id,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'amount_1' => $manualTax->amount_1 ?? null,
                'tcs_1' => $manualTax->tcs_1 ?? null,
                'amount_2' => $manualTax->amount_2 ?? null,
                'tcs_2' => $manualTax->tcs_2 ?? null,
            ];
        });

        return response()->json(['success' => true, 'customers' => $result]);
    }
    public function saveCustomerManualTax(Request $request)
    {
        // Validate the request
        $request->validate([
            'token' => 'required|string',
            'customers' => 'required|array',
            'customers.*.customer_id' => 'required|integer',
            'customers.*.manual_amount_1' => 'required|numeric',
            'customers.*.tcs_1' => 'required|numeric',
        ]);

        $token = $request->token;

        // Fetch the trip booking ID using the token
        $booking = TripBooking::where('token', $token)->first();

        if (!$booking) {
            return response()->json(['error' => 'Invalid booking.'], 400);
        }

        $tripBookingId = $booking->id;

        $tripCostData = [];

        foreach ($request->customers as $customer) {
            DB::table('manual_taxes')->updateOrInsert(
                [
                    'trip_booking_id' => $tripBookingId,
                    'customer_id' => $customer['customer_id'],
                ],
                [
                    'amount_1' => $customer['manual_amount_1'],
                    'tcs_1' => $customer['tcs_1'],
                    'amount_2' => $customer['manual_amount_2'],
                    'tcs_2' => $customer['tcs_2'],
                    'updated_at' => now(),
                ]
            );

            $totalCost = $customer['manual_amount_1'] + ($customer['manual_amount_2'] ?? 0);

            $tripCostData[] = [
                'c_id' => $customer['customer_id'],
                'cost' => $totalCost,
                'comment' => $customer['comment'] ?? 'NA',
                'multiple_payment_tax' => $customer['tcs_2'] ?? null,
                'amount_1' => $customer['manual_amount_1'],
                'amount_2' => $customer['manual_amount_2'],
                'tcs_1' => $customer['tcs_1'],
                'tcs_2' => $customer['tcs_2'] ?? null,
            ];
        }

        $booking->update([
            'trip_cost' => json_encode($tripCostData),
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Manual Tax data saved successfully.'
        ]);
    }
}
