<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryHistory;
use App\Models\Vendor;
use App\Models\Trip;
use App\Models\User;
use App\Models\InventoryCategory;
use App\Models\ActivityTracker;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;


class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.inventory.index');
    }

    public function get()
    {
        // $data = Inventory::with(['category', 'vendor', 'trip'])->orderBy('id', 'Desc')->get();
        $data = Inventory::selectRaw("
                IFNULL(trip_id, inventories.id) as group_key, 
                trip_id, 
                GROUP_CONCAT(DISTINCT inventories.title) as title, 
                GROUP_CONCAT(DISTINCT inventory_categories.title) as category_name,
                SUM(inventories.qty) as qty,
                SUM(inventories.main_qty) as main_qty,
                MAX(inventories.created_at) as created_at, MAX(inventories.id) as id")
            ->leftJoin('inventory_categories', 'inventories.category_id', '=', 'inventory_categories.id')
            ->groupBy('group_key', 'trip_id')
            ->orderBy('inventories.id', 'Desc')
            ->get();


        $data->map(function ($item, $index) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->purchase_for = $item->trip->name ?? "Inhouse";
        });
        return DataTables::of($data)->make(true);
    }

    public function viewProduct(Request $request){
        $check = Inventory::where('id', $request->id)->first();
        if($check && $check->trip_id != null){
            $data = Inventory::where('trip_id', $check->trip_id)->orderBy('id', 'Desc')->get();
        }else{
            $data = Inventory::where('id', $request->id)->orderBy('id', 'Desc')->get();
        }

        $data->map(function ($item, $index) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->purchase_for = $item->trip->name ?? "Inhouse";
            $item->category_name = $item->category->title ?? ' ';
        });
        return DataTables::of($data)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $cats = InventoryCategory::all();
        $vendors = Vendor::all();
        $trips = Trip::all();
        $vendors->map(function ($item, $index) {
            $item->name = $item->first_name ." ".$item->last_name;
        });
        return view('admin.inventory.add', compact('cats', 'vendors', 'trips'));
    }

    public function editStock(Request $request)
    {   
        $cats = InventoryCategory::all();
        $vendors = Vendor::all();
        $users = User::all();
        $trips = Trip::all();
        $data = InventoryHistory::find($request->id);
        $vendors->map(function ($item, $index) {
            $item->name = $item->first_name ." ".$item->last_name;
        });
        return view('admin.inventory.edit-stock', compact('cats', 'vendors', 'trips', 'users', 'data'));
      
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'qty' => ['required'],
            'price' => ['required'],
            'purchase_from' => ['required']
        ]);


        $data = new Inventory();
        $data->category_id = $request->category_id;
        $data->title = $request->title;
        $data->code = $request->code;
        $data->qty = $request->qty;
        $data->main_qty = $request->qty;
        $data->price = $request->price;
        $data->tax = $request->tax;
        $data->purchase_from = $request->purchase_from;
        $data->vendor_id = $request->vendor_id;
        $data->source = $request->source;
        if($request->description){
            $data->description = $request->description;
        }
        if($request->trip_id){
            $data->trip_id = $request->trip_id;
        }
        
        if($request->hasfile('image')){
            $data->image = $request->file('image')->store('admin/inventory');
        }

        if($request->hasfile('file')){
            $data->file = $request->file('file')->store('admin/inventory');
        }
        $data->save();


        // Activity Tracker
        $activity = new ActivityTracker();
        $activity->admin_id = Auth::user()->id;
        $activity->action = Auth::user()->name ." has Added ". $request->title ." Product in Inventory";
        $activity->page = "inventory";
        $activity->page_data_id = $data->id;
        $activity->save();
        // Activity Tracker
        
        return redirect()->back()->with('success', 'Data Added Successfully !!');
    }



    public function export()
    {
        // Fetch data from the Customer model
        $datas = Inventory::all();

        // Prepare data for export
        $data = [];
        $data[] = ['Category', 'Name', 'Code', 'Qty', 'Price', 'tax', 'Purchase From', 'Source', 'Vendor', 'Description', 'Trip'];

        foreach ($datas as $customer) {
            $data[] = [
                $customer->category->title ?? null,
                $customer->title,
                $customer->code,
                $customer->qty,
                $customer->price,
                $customer->tax,
                $customer->purchase_from,
                $customer->source,
                $customer->vendor->first_name ?? null,
                $customer->description,
                $customer->trip->name ?? null,
            ];
        }

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add data to the spreadsheet
        $sheet->fromArray($data, null, 'A1');

        // Set auto column size for all columns
        foreach(range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Create a writer for XLSX format
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="inventory.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet data to a file
        $writer->save('php://output');
    }

    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Inventory::where('id', $id)->first();
        $cats = InventoryCategory::all();
        $vendors = Vendor::all();
        $trips = Trip::all();
        $vendors->map(function ($item, $index) {
            $item->name = $item->first_name ." ".$item->last_name;
        });
        return view('admin.inventory.edit', compact('data', 'cats', 'vendors', 'trips'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required'],
            'purchase_from' => ['required']
        ]);


        $data = Inventory::find($request->id);
        $data->category_id = $request->category_id;
        $data->title = $request->title;
        $data->code = $request->code;
        $data->price = $request->price;
        $data->tax = $request->tax;
        $data->purchase_from = $request->purchase_from;
        $data->vendor_id = $request->vendor_id;
        $data->source = $request->source;
        if($request->description){
            $data->description = $request->description;
        }
        if($request->trip_id){
            $data->trip_id = $request->trip_id;
        }
        
        if($request->hasfile('image')){
            @unlink('storage/app/'.$data->image);
            $data->image = $request->file('image')->store('admin/inventory');
        }

        if($request->hasfile('file')){
            @unlink('storage/app/'.$data->file);
            $data->file = $request->file('file')->store('admin/inventory');
        }

        $action = Auth::user()->name ." has edited ";
        foreach($data->getDirty() as $field=>$value){
            $oldData = $data->getOriginal($field) ?? "NULL";
            $action .= $field." from ". $oldData . " to ". $value . " ";
        }

        $data->save();

        // Activity Tracker
        $activity = new ActivityTracker();
        $activity->admin_id = Auth::user()->id;
        $activity->action = $action;
        $activity->page = "inventory";
        $activity->page_data_id = $request->id;
        $activity->save();
        // Activity Tracker
        
        return redirect(route('inventory.index'))->with('success', 'Updated Successfully !!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Inventory::find($id);
        $tripId = $data->trip_id;
        @unlink('storage/app/'.$data->image);
        $data->delete();

        if($tripId){
            $newId = Inventory::where('trip_id', $tripId)->first();
            if($newId){
                return redirect()->route('inventory.view', $newId)->with('success', 'Deleted Successfully!!');
            }else{
                return redirect()->route('inventory.index')->with('success', 'Deleted Successfully!!');
            }
        }else{
            return redirect()->route('inventory.index')->with('success', 'Deleted Successfully!!');
        }

    }

    public function view($id)
    {   
        $check = Inventory::find($id);
        $trips = Trip::all();
        $users = User::all();

        $cats = InventoryCategory::all();
        $vendors = Vendor::all();
        $vendors->map(function ($item, $index) {
            $item->name = $item->first_name ." ".$item->last_name;
        });

        $data = Inventory::selectRaw("
                    IFNULL(trip_id, inventories.id) as group_key, 
                    trip_id, 
                    GROUP_CONCAT(DISTINCT inventories.title) as title, 
                    GROUP_CONCAT(DISTINCT inventory_categories.title) as category_name,
                    SUM(inventories.qty) as qty,
                    SUM(inventories.main_qty * inventories.price) as total_price,
                    SUM(inventories.main_qty) as main_qty,
                    MAX(inventories.created_at) as created_at, 
                    MAX(inventories.id) as id")
                ->leftJoin('inventory_categories', 'inventories.category_id', '=', 'inventory_categories.id')
                ->where('trip_id', $check->trip_id)
                ->groupBy('group_key', 'trip_id')
                ->orderBy('inventories.id', 'Desc')
                ->first();


        return view('admin.inventory.view', compact('data', 'trips', 'users', 'check', 'cats', 'vendors'));
    }

    public function viewDetails($id)
    {   
        $data = Inventory::find($id);
        $trips = Trip::all();
        $users = User::all();
        return view('admin.inventory.details', compact('data', 'trips', 'users'));
    }

    public function activityPage($id)
    {
        $data = $id;
        $stock = Inventory::find($id);
        return view('admin.inventory.activity', compact('data', 'stock'));
    }

    public function activity(Request $request)
    {
        if(checkAdminRole()){
            $data = ActivityTracker::where(['page'=> 'inventory', 'page_data_id'=>$request->id])->orderBy('id', 'desc')->get();
        }else{
            $data = ActivityTracker::where(['page'=> 'inventory', 'page_data_id'=>$request->id])->where('admin_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        }

        $data->map(function ($item, $index) {
            $item->created = date("M d, Y H:i:s", strtotime($item->created_at));
            $item->name = $item->admin->name;
        });
        return DataTables::of($data)->make(true);
    }

    public function history(Request $request)
    {
        $data = InventoryHistory::where('inventory_id', $request->inventory_id)->orderBy('id', 'Desc')->get();
        
        $data->map(function ($item, $index) {
            $item->created = date("M d, Y H:i:s", strtotime($item->created_at));
            $item->admin_name = $item->admin->name;
            $item->stock_for_trip = $item->trip->name ?? null;
            $item->given_to_user = $item->given->name ?? null;
        });
        return DataTables::of($data)->make(true);
    }

    public function stock(Request $request)
    {
        $data = Inventory::where('id', $request->inventory_id)->first();
        return $data->qty ?? 0;
    }

    public function stockUpdate(Request $request)
    {
        
        $request->validate([
            'type' => ['required'],
            'qty' => ['required'],
        ]);


        $data = new InventoryHistory();
        $data->type = $request->type;
        $data->qty = $request->qty;
        $data->inventory_id = $request->inventory_id;
        $data->admin_id = Auth::user()->id;
        $data->stock_for = $request->stock_for;
        $data->given_to = $request->given_to;
        $data->comment = $request->comment;
        $data->save();

        $inventory = Inventory::find($request->inventory_id);
        if($request->type == "In"){
            $inventory->qty = $inventory->qty+$request->qty;
            $sign = "Added";
        }elseif($request->type == "Out"){
            $inventory->qty = $inventory->qty-$request->qty;
            $sign = "Removed";
        }
        $inventory->save();

        $action = Auth::user()->name ." has ".$sign." ".$request->qty." Inventory Stock";

        
        // Activity Tracker
        $activity = new ActivityTracker();
        $activity->admin_id = Auth::user()->id;
        $activity->action = $action;
        $activity->page = "inventory_history";
        $activity->page_data_id = $data->id;
        $activity->save();
        // Activity Tracker
        // return redirect()->route('inventory.view', ['id' => $request->inventory_id])->with('success', 'Updated Successfully !!');

        return redirect()->back()->with('success', 'Updated Successfully !!');
    }

    public function stockHistoryUpdate(Request $request)
    {
        $request->validate([
            'type' => ['required'],
            'qty' => ['required'],
        ]);


        $data = InventoryHistory::find($request->id);
        $data->type = $request->type;
        $data->qty = $request->qty;
        $data->admin_id = Auth::user()->id;
        $data->stock_for = $request->stock_for;
        $data->given_to = $request->given_to;
        $data->comment = $request->comment;


        $action = Auth::user()->name ." has edited ";
        foreach($data->getDirty() as $field=>$value){
            $oldData = $data->getOriginal($field) ?? "NULL";
            $action .= $field." from ". $oldData . " to ". $value . " ";
        }
        // Activity Tracker
        $activity = new ActivityTracker();
        $activity->admin_id = Auth::user()->id;
        $activity->action = $action;
        $activity->page = "inventory_history";
        $activity->page_data_id = $request->id;
        $activity->save();
        // Activity Tracker
        
        $data->save();

        $inventory = Inventory::find($data->inventory_id);
        if($request->type == "In"){
            $inventory->qty = $inventory->qty-$request->qty;
        }elseif($request->type == "Out"){
            $inventory->qty = $inventory->qty+$request->qty;
        }
        $inventory->save();
        
        return redirect()->back()->with('success', 'Updated Successfully !!');
    }

    public function deleteStock(Request $request)
    {
        $data = InventoryHistory::find($request->id);
        
        $inventory = Inventory::find($data->inventory_id);
        if($data->type == "In"){
            $inventory->qty = $inventory->qty-$data->qty;
        }elseif($data->type == "Out"){
            $inventory->qty = $inventory->qty+$data->qty;
        }
        $inventory->save();

        // Activity Tracker
        $action = Auth::user()->name ." has deleted Inventory Stock History";
        $activity = new ActivityTracker();
        $activity->admin_id = Auth::user()->id;
        $activity->action = $action;
        $activity->page = "inventory_history";
        $activity->page_data_id = $request->id;
        $activity->save();
        // Activity Tracker

        $data->delete();
        return redirect()->back()->with('success', 'Deleted Successfully!!');
    }

    public function activityStock(Request $request)
    {
        $data = $request->id;
        return view('admin.inventory.activity-history', compact('data'));
    }

    public function activityStockHistory(Request $request)
    {
        if(checkAdminRole()){
            $data = ActivityTracker::where(['page'=> 'inventory_history', 'page_data_id'=>$request->id])->orderBy('id', 'desc')->get();
        }else{
            $data = ActivityTracker::where(['page'=> 'inventory_history', 'page_data_id'=>$request->id])->where('admin_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        }

        $data->map(function ($item, $index) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->name = $item->admin->name;
        });
        return DataTables::of($data)->make(true);
    }
}