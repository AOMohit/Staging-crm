<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\TripBooking;
use App\Models\Country;
use App\Models\State;
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

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.agent.index');
    }

    public function getAgents()
    {
        $data = Agent::orderBy('id', 'Desc')->get();
        $data->map(function ($item, $index) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->name = $item->first_name ." ".$item->last_name;
            $item->phone = $item->telephone_code.$item->phone;
        });
        // dd($data);
        return DataTables::of($data)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.agent.add', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:'.Agent::class],
            'phone' => ['required', 'unique:'.Agent::class],
            'agency' => ['required'],
            'country' => ['required'],
            'state' => ['required'],
            'city' => ['required'],
            'pincode' => ['required'],
            'address' => ['required'],
            'telephone_code' => ['required'],
        ]);


        $data = new Agent();
        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->agency = $request->agency;
        $data->country = $request->country;
        $data->state = $request->state;
        $data->city = $request->city;
        $data->pincode = $request->pincode;
        $data->address = $request->address;
        $data->telephone_code = $request->telephone_code ?? null;
        if($request->gst){
            $data->gst = $request->gst;
        }

        if($request->hasfile('image')){
            $data->image = $request->file('image')->store('admin/agent');
        }
        $data->save();

        // Activity Tracker
        $activity = new ActivityTracker();
        $activity->admin_id = Auth::user()->id;
        $activity->action = Auth::user()->name ." has Added ". $request->first_name ." Agent";
        $activity->page = "agent";
        $activity->page_data_id = $data->id;
        $activity->save();
        // Activity Tracker
        
        return redirect(route('agent.index'))->with('success', 'Updated Successfully !!');
    }

    /**
     * Display the specified resource.
     */
    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);


        // Check if a file has been uploaded
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Load the Excel file
            $spreadsheet = IOFactory::load($file);

            // Get the first sheet of the workbook
            $sheet = $spreadsheet->getActiveSheet();

            // Get the highest row number
            $highestRow = $sheet->getHighestDataRow();

            // Define an array to store duplicate entries
            $duplicateEntries = [];

            // Iterate through each row
            for ($row = 2; $row <= $highestRow; ++$row) {
                // Get the cell value of each column
                $cellValueA = $sheet->getCell('A'. $row)->getValue();
                $cellValueB = $sheet->getCell('B'. $row)->getValue();
                $cellValueC = $sheet->getCell('C'. $row)->getValue();
                $cellValueD = $sheet->getCell('D'. $row)->getValue();
                $cellValueE = $sheet->getCell('E'. $row)->getValue();
                $cellValueF = $sheet->getCell('F'. $row)->getValue();
                $cellValueG = $sheet->getCell('G'. $row)->getValue();
                $cellValueH = $sheet->getCell('H'. $row)->getValue();
                $cellValueI = $sheet->getCell('I'. $row)->getValue();
                $cellValueJ = $sheet->getCell('J'. $row)->getValue();
                $cellValueK = $sheet->getCell('K'. $row)->getValue();

                // Check if email or phone already exists in the database
                $existingRecord = Agent::where('email', $cellValueA)->orWhere('phone', $cellValueB)->first();

                // If record already exists, store it in the duplicate entries array
                if ($existingRecord) {
                    $duplicateEntries[] = [
                        'email' => $cellValueA,
                        'phone' => $cellValueB,
                    ];
                } else {
                    // Create a new model instance
                    $model = new Agent();
                    
                    // Assign values to model attributes
                    $model->email = $cellValueA;
                    $model->phone = $cellValueB;
                    $model->first_name = $cellValueC;
                    $model->last_name = $cellValueD;
                    $model->agency = $cellValueE;
                    $model->country = $cellValueF;
                    $model->state = $cellValueG;
                    $model->city = $cellValueH;
                    $model->pincode = $cellValueI;
                    $model->address = $cellValueJ;
                    $model->gst = $cellValueK;

                    // Save the model instance to the database
                    $model->save();

                    // Activity Tracker
                    $activity = new ActivityTracker();
                    $activity->admin_id = Auth::user()->id;
                    $activity->action = Auth::user()->name ." has Imported ". $request->first_name ." Agent using XLSX";
                    $activity->page = "agent";
                    $activity->page_data_id = $model->id;
                    $activity->save();
                    // Activity Tracker
                }
            }

            // Redirect back with success message
            return redirect()->back()->with('success', 'File imported successfully. Duplicate entries: ' . count($duplicateEntries));
        }

        // Redirect back with error message if no file was uploaded
        return redirect()->back()->with('error', 'No file uploaded.');
    
    }

    public function exportSample(){
        // Define the path to your sample file
        $sampleFilePath = public_path('samples/sample-agent.xlsx');
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        // Return the sample file as a download response
        return Response::download($sampleFilePath, 'sample-agent.xlsx', $headers);
    }

    public function export()
    {
        // Fetch data from the Customer model
        $customers = Agent::all();

        // Prepare data for export
        $data = [];
        $data[] = ['Email', 'Phone', 'First Name', 'Last Name', 'Agency', 'Country', 'State', 'City', 'Pincode', 'Address', 'GST'];

        foreach ($customers as $customer) {
            $data[] = [
                $customer->email,
                "$customer->telephone_code"."$customer->phone",
                $customer->first_name,
                $customer->last_name,
                $customer->agency,
                $customer->country,
                $customer->state,
                $customer->city,
                $customer->pincode,
                $customer->address,
                $customer->gst,
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
        header('Content-Disposition: attachment;filename="agents.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet data to a file
        $writer->save('php://output');
    }

    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Agent::where('id', $id)->first();
        $countries = Country::all();
        return view('admin.agent.edit', compact('data', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request);
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:agents,email,' . $request->id],
            'phone' => ['required', 'max:255', 'unique:agents,phone,' . $request->id],
            'agency' => ['required'],
            'country' => ['required'],
            'state' => ['required'],
            'city' => ['required'],
            'pincode' => ['required'],
            'address' => ['required'],
            'telephone_code' => ['required'],
        ]);


        $data = Agent::find($request->id);
        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->agency = $request->agency;
        $data->country = $request->country;
        $data->state = $request->state;
        $data->city = $request->city;
        $data->pincode = $request->pincode;
        $data->address = $request->address;
        $data->telephone_code = $request->telephone_code ?? null;
        if($request->gst){
            $data->gst = $request->gst;
        }

        if($request->hasfile('image')){
            @unlink('storage/app/'.$data->image);
            $data->image = $request->file('image')->store('admin/agent');
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
        $activity->page = "agent";
        $activity->page_data_id = $request->id;
        $activity->save();
        // Activity Tracker
        
        return redirect(route('agent.index'))->with('success', 'Updated Successfully !!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Agent::find($id);
        @unlink('storage/app/'.$data->image);

        // Activity Tracker
        $activity = new ActivityTracker();
        $activity->admin_id = Auth::user()->id;
        $activity->action = Auth::user()->name ." has Deleted ". $data->name ." Agent";
        $activity->page = "agent";
        $activity->page_data_id = $data->id;
        $activity->save();
        // Activity Tracker
        
        $data->delete();
        return redirect(route('agent.index'))->with('success', 'Deleted Successfully!!');
    }

    public function view($id)
    {
        $data = Agent::where('id', $id)->first();
        return view('admin.agent.view', compact('data'));
    }

    public function activityPage($id)
    {
        $data = $id;
        $agent = Agent::where('id', $id)->first();
        return view('admin.agent.activity', compact('data', 'agent'));
    }

    public function activity(Request $request)
    {
        if(checkAdminRole()){
            $data = ActivityTracker::where(['page'=> 'agent', 'page_data_id'=>$request->id])->orderBy('id', 'desc')->get();
        }else{
            $data = ActivityTracker::where(['page'=> 'agent', 'page_data_id'=>$request->id])->where('admin_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        }

        $data->map(function ($item, $index) {
            $item->created = date("M d, Y H:i:s", strtotime($item->created_at));
            $item->name = $item->admin->name;
        });
        return DataTables::of($data)->make(true);
    }

    // view
    public function referrals(Request $request){
        $id = $request->agent_id;
        $agent = Agent::find($id);
        $name = $agent->first_name ." ".$agent->last_name;
        $data = TripBooking::where('lead_source', 'Agent')->where('sub_lead_source', "$name")->where('trip_status', '!=', 'Draft')->orderBy('id', 'Desc')->get();
        $data->map(function ($item, $index) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->trip_name = $item->trip->name ?? null;
            $item->trip_status = $item->trip_status;
            $item->admin_name = $item->admin->name;
        });
        return DataTables::of($data)->make(true);
    }
}