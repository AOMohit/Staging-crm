<?php

namespace App\Http\Controllers;

use App\Models\VendorService;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Response;

class VendorServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.vendor_service.index');
    }

    public function get()
    {
        $data = VendorService::orderBy('id', 'Desc')->get();
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
        return view('admin.vendor_service.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $data = new VendorService();
        $data->title = $request->title;
        $data->save();
        if ($request->ajax()) {
            $result = "<option value='".$data->id."'>".$request->title."</option>";
            return $result;
        }else{
            return redirect(route('setting.vendor_service.index'))->with('success', 'Updated Successfully !!');
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = VendorService::where('id', $id)->first();
        return view('admin.vendor_service.edit', compact('data'));
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


        $data = VendorService::find($request->id);
        $data->title = $request->title;
    
        $data->save();
        
        return redirect(route('setting.vendor_service.index'))->with('success', 'Updated Successfully !!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $check = Vendor::whereJsonContains('service_id', "$id")->first();
        if($check){
            return redirect(route('setting.vendor_service.index'))->with('warning', 'Service is Used in Vendor!!');
        }
        $data = VendorService::find($id);
        $data->delete();
        return redirect(route('setting.vendor_service.index'))->with('success', 'Deleted Successfully!!');
    }
}