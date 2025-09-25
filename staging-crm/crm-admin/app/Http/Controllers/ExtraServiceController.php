<?php

namespace App\Http\Controllers;

use App\Models\ExtraService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Response;

class ExtraServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.extra_service.index');
    }

    public function get()
    {
        $data = ExtraService::orderBy('id', 'Desc')->get();
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
        return view('admin.extra_service.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $data = new ExtraService();
        $data->title = $request->title;
        $data->is_redeemable = $request->is_redeemable;
        $data->save();
        
        return redirect(route('setting.extra_service.index'))->with('success', 'Updated Successfully !!');
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = ExtraService::where('id', $id)->first();
        return view('admin.extra_service.edit', compact('data'));
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


        $data = ExtraService::find($request->id);
        $data->title = $request->title;
        $data->is_redeemable = $request->is_redeemable;
    
        $data->save();
        
        return redirect(route('setting.extra_service.index'))->with('success', 'Updated Successfully !!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = ExtraService::find($id);
        $data->delete();
        return redirect(route('setting.extra_service.index'))->with('success', 'Deleted Successfully!!');
    }
}