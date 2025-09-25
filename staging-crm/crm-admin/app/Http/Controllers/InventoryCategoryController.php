<?php

namespace App\Http\Controllers;

use App\Models\InventoryCategory;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Response;

class InventoryCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.inventory_category.index');
    }

    public function get()
    {
        $data = InventoryCategory::orderBy('id', 'Desc')->get();
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
        return view('admin.inventory_category.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $data = new InventoryCategory();
        $data->title = $request->title;
        $data->save();
        
        return redirect(route('inventory_category.index'))->with('success', 'Updated Successfully !!');
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = InventoryCategory::where('id', $id)->first();
        return view('admin.inventory_category.edit', compact('data'));
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


        $data = InventoryCategory::find($request->id);
        $data->title = $request->title;
    
        $data->save();
        
        return redirect(route('inventory_category.index'))->with('success', 'Updated Successfully !!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $check = Inventory::where('category_id')->first();
        if($check){
            return redirect(route('inventory_category.index'))->with('warning', 'Category is Used in Inventory!!');
        }
        $data = InventoryCategory::find($id);
        $data->delete();
        return redirect(route('inventory_category.index'))->with('success', 'Deleted Successfully!!');
    }
}