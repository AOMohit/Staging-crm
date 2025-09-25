<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarbonDonationController extends Controller
{
     public function submit(Request $request)
    {
        // Normally: Save to database or send email, etc.
        // For now just return the donation
        return "Payment received of ₹" . $request->donation;
    }
    // public function showForm()
    // {
    //     return view('admin.carbon.offset');
    // }

    // public function calculate(Request $request)
    // {
    
    //     $request->validate([
    //         'distance' => 'required|numeric',
    //         'mileage' => 'required|numeric|min:1',
    //         'cc_engine' => 'required',
    //         'fuel' => 'required',
    //         'name' => 'required|string',
    //         'email' => 'required|email',
    //         'mobile' => 'required',
    //     ]);

    //     $distance = $request->distance;
    //     $mileage = $request->mileage;
    //     $fuel = $request->fuel;

    //     // Emission factors (approx)
    //     $emission_factors = [
    //         'petrol' => 2.3,  // kg CO2 per litre
    //         'diesel' => 2.6,
    //         'cng' => 2.0,
    //         'electric' => 0.5
    //     ];

    //     $fuel_used = $distance / $mileage;
    //     $co2_emission = round($fuel_used * ($emission_factors[$fuel] ?? 2.3), 2);

    //     $donation_per_kg = 1.5; // ₹ per kg CO2
    //     $trees_required = ceil($co2_emission / 21); // Avg 1 tree = 21 kg CO₂/year
    //     $donation = ceil($co2_emission * $donation_per_kg);

    //     return view('admin.carbon.summary', compact(
    //         'distance', 'mileage', 'fuel', 'co2_emission', 'donation', 'trees_required', 'request'
    //     ));
    // }
}
