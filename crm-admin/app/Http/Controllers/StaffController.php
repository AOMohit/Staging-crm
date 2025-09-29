<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\ActivityTracker;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.staff.index');
    }

    public function get()
    {
        $data = User::orderBy('id', 'Desc')->where('is_main_admin', '!=', 1)->get();
        $data->map(function ($item, $index) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->role_name = $item->role->name;
        });
        // dd($data);
        return DataTables::of($data)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.staff.add', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:'.User::class],
            'role_id' => ['required'],
        ]);


        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->role_id = $request->role_id;
        $data->password = Hash::make("12345678");
        
        $data->save();

        // Activity Tracker
        $activity = new ActivityTracker();
        $activity->admin_id = Auth::user()->id;
        $activity->action = Auth::user()->name ." has Added ". $request->name ." Staff";
        $activity->page = "staff";
        $activity->page_data_id = $data->id;
        $activity->save();
        // Activity Tracker
        
        return redirect(route('staff.index'))->with('success', 'Updated Successfully !!');
    }


    public function export()
    {
        // Fetch data from the Customer model
        $customers = User::all();

        // Prepare data for export
        $data = [];
        $data[] = ['Email', 'Name', 'Role', 'Created At'];

        foreach ($customers as $customer) {
            $data[] = [
                $customer->email,
                $customer->name,
                $customer->role->name,
                $customer->created_at,
            ];
        }

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add data to the spreadsheet
        $sheet->fromArray($data, null, 'A1');

        // Set auto column size for all columns
        foreach(range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Create a writer for XLSX format
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="staff.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet data to a file
        $writer->save('php://output');
    }

    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = User::where('id', $id)->first();
        $roles = Role::all();
        return view('admin.staff.edit', compact('data', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $request->id],
            'role_id' => ['required'],
        ]);


        $data = User::find($request->id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->role_id = $request->role_id;

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
        $activity->page = "staff";
        $activity->page_data_id = $request->id;
        $activity->save();
        // Activity Tracker
        
        return redirect(route('staff.index'))->with('success', 'Updated Successfully !!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = User::find($id);

        

        // Activity Tracker
        $activity = new ActivityTracker();
        $activity->admin_id = Auth::user()->id;
        $activity->action = Auth::user()->name ." has Deleted ". $data->name ." Staff";
        $activity->page = "staff";
        $activity->page_data_id = $data->id;
        $activity->save();
        // Activity Tracker

        $data->delete();

        return redirect(route('staff.index'))->with('success', 'Deleted Successfully!!');
    }

    public function activityPage($id)
    {
        $data = $id;
        $staff = User::find($id);
        return view('admin.staff.activity', compact('data', 'staff'));
    }

    public function activity(Request $request)
    {
        if(checkAdminRole()){
            $data = ActivityTracker::where(['page'=> 'staff', 'page_data_id'=>$request->id])->orderBy('id', 'desc')->get();
        }else{
            $data = ActivityTracker::where(['page'=> 'staff', 'page_data_id'=>$request->id])->where('admin_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        }

        $data->map(function ($item, $index) {
            $item->created = date("M d, Y H:i:s", strtotime($item->created_at));
            $item->name = $item->admin->name;
        });
        return DataTables::of($data)->make(true);
    }

}