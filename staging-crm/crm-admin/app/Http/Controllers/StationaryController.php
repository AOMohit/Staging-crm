<?php

namespace App\Http\Controllers;

use App\Models\Stationary;
use App\Models\Trip;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Response;

class StationaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.stationary.index');
    }

    public function get()
    {
        $data = Stationary::orderBy('id', 'Desc')->get();
        $data->map(function ($item, $index) {
            $item->created = date("M d, Y", strtotime($item->created_at));
        });
        // dd($data);
        return DataTables::of($data)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.stationary.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $data = new Stationary();
        $data->title = $request->title;
        $data->save();
        
        return redirect(route('setting.stationary.index'))->with('success', 'Updated Successfully !!');
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Stationary::where('id', $id)->first();
        return view('admin.stationary.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request);
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);


        $data = Stationary::find($request->id);
        $data->title = $request->title;
    
        $data->save();
        
        return redirect(route('setting.stationary.index'))->with('success', 'Updated Successfully !!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $check = Trip::whereJsonContains('stationary_id', "$id")->first();
        if($check){
            return redirect(route('setting.stationary.index'))->with('warning', 'stationary is Used in Vendor!!');
        }
        $data = Stationary::find($id);
        $data->delete();
        return redirect(route('setting.stationary.index'))->with('success', 'Deleted Successfully!!');
    }
}