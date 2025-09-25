<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Trip;
use App\Models\Stationary;
use App\Models\Merchandise;

class TripApiController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'trip_type' => 'required|string',
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'price' => 'required|numeric',
            'region_type' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

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
        $data->overview = $request->overview;
        $data->region_type = $request->region_type;
        $data->stationary_id = json_encode($request->stationary);
        $data->merchandise_id = json_encode($request->merchandise);

        $adminId = "1"; 
        $data->relation_manager_id = json_encode([$adminId]);
        
        $data->tree_no = 0;
        $data->donation_amt = 0;

        $data->added_by = $adminId;

        if ($request->hasFile('image')) {
            $data->image = $request->file('image')->store('admin/trip', 'public'); // store in public disk
        }
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Trip created successfully',
            'trip' => $data
        ], 201);
    }
}
