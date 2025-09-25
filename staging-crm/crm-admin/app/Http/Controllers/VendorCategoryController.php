<?php

namespace App\Http\Controllers;

use App\Models\VendorCategory;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Response;

class VendorCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.vendor_category.index');
    }

    public function get()
    {
        $data = VendorCategory::orderBy('id', 'Desc')->get();
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
        return view('admin.vendor_category.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $data = new VendorCategory();
        $data->title = $request->title;
        $data->save();
        
        if ($request->ajax()) {
            $result = "<option value='".$data->id."'>".$request->title."</option>";
            return $result;
        }else{
            return redirect(route('setting.vendor_category.index'))->with('success', 'Updated Successfully !!');
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = VendorCategory::where('id', $id)->first();
        return view('admin.vendor_category.edit', compact('data'));
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


        $data = VendorCategory::find($request->id);
        $data->title = $request->title;
    
        $data->save();
        
        return redirect(route('setting.vendor_category.index'))->with('success', 'Updated Successfully !!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $check = Vendor::whereJsonContains('vendor_type', "$id")->first();
        if($check){
            return redirect(route('setting.vendor_category.index'))->with('warning', 'Category is Used in Vendor!!');
        }
        $data = VendorCategory::find($id);
        $data->delete();
        return redirect(route('setting.vendor_category.index'))->with('success', 'Deleted Successfully!!');
    }
}