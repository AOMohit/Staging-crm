<?php

namespace App\Http\Controllers;

use App\Models\Relationship;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Response;

class RelationshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.relationship.index');
    }

    public function get()
    {
        $data = Relationship::orderBy('id', 'Desc')->get();
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
        return view('admin.relationship.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $data = new Relationship();
        $data->title = $request->title;
        $data->save();
        
        return redirect(route('setting.relationship.index'))->with('success', 'Updated Successfully !!');
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Relationship::where('id', $id)->first();
        return view('admin.relationship.edit', compact('data'));
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


        $data = Relationship::find($request->id);
        $data->title = $request->title;
    
        $data->save();
        
        return redirect(route('setting.relationship.index'))->with('success', 'Updated Successfully !!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Relationship::find($id);
        $data->delete();
        return redirect(route('setting.relationship.index'))->with('success', 'Deleted Successfully!!');
    }
}