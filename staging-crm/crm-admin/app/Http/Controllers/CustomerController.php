<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TripBooking;
use App\Models\LoyalityPts;
use App\Models\TransferPts;
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
use Illuminate\Support\Str;
use App\Events\SendMailEvent;
use App\Models\Country;
use App\Models\Trip;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.customers.index');
    }

    public function getCustomers()
    {
        $data = Customer::whereNotNull('email')->where('email', '!=', "null")->orderBy('id', 'Desc');
        $dataCount = Customer::whereNotNull('email')->where('email', '!=', "null")->count();

        // query speed purpose
        $searchVal = $_GET['search']['value'];
        if(isset($searchVal) && $searchVal != ""){
            $data->where('first_name', 'like', "$searchVal"."%")->orWhere('last_name', 'like', "$searchVal"."%");
        }

        $offset = 0;
        if (isset($_GET['start'])) {
            $offset = $_GET['start'];
        }
        $length = 0;
        if (isset($_GET['length'])  && $_GET['length'] > 0) {
            $length = $_GET['length'];
        }

        $data = $data->skip($offset)->take($length)->get();
        // query speed purpose
        
        $data->map(function ($item, $index) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->name = $item->first_name ." ".$item->last_name;
            $item->phone = $item->telephone_code.$item->phone;
            $item->trip_count = getTripCountbyCustomerId($item->id);
            $item->trip_spent = number_format(totalTripCostOfCustomerById($item->id)) ?? 0;
        });

        $response = array(
            "draw" => intval($_GET['draw']),
            "iTotalRecords" => $dataCount,
            "iTotalDisplayRecords" => $dataCount,
            "aaData" => $data
        );

        return json_encode($response);
        // return DataTables::of($data)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customers.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:'.Customer::class],
                'phone' => ['required', 'unique:'.Customer::class],
                'gender' => ['required'],
                'telephone_code' => ['required'],
            ]);
        }catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $data = new Customer();
        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->gender = $request->gender;
        $data->telephone_code = $request->telephone_code;
        $randomPassword = Str::random(10);
        $data->password = Hash::make($randomPassword);
        
        if($request->refer_by != null){
            $checkUser = Customer::where('email', $request->refer_by)->first();
           
            if($checkUser){
                $expiryDate = now()->addYears(2)->format('Y-m-d');
                $referrel_points =  intval($request->trip_cost * 0.01);
                $checkUser->points = $checkUser->points + $referrel_points;
                $checkUser->save();
                
                $referralTransaction = new LoyalityPts();
                $referralTransaction->customer_id = $checkUser->id;
                $referralTransaction->trip_name = $request->trip_name;
                $referralTransaction->reason = 'Referral'; 
                $referralTransaction->trans_type = 'Cr';
                $referralTransaction->trans_amt = $referrel_points;
                $referralTransaction->balance = $checkUser->points;
                $referralTransaction->expiry_date = $expiryDate;
                $referralTransaction->trans_page = 'Booking trip Referral'; 
                $referralTransaction->save();

                $data->referred_by = $request->refer_by;
            }
        }
        
        $data->save();

        // Activity Tracker
        $activity = new ActivityTracker();
        $activity->admin_id = Auth::user()->id;
        $activity->action = Auth::user()->name ." has Added ". $request->first_name ." Customer";
        $activity->page = "customer";
        $activity->page_data_id = $data->id;
        $activity->save();
        // Activity Tracker


        // mail
        // $data = [
        //     'email' => $request->email,
        //     'name' => $request->first_name." ".$request->last_name,
        //     'password' => $randomPassword,
        //     'link' => env('USER_URL'),
        // ];
        // if(setting('mail_status') == 1){
        //     event(new SendMailEvent("$request->email", 'Your Account Login Details | Adventures Overland!', 'emails.login-details', $data));
        // }
        // // mail
        
        if($request->popup){
            return redirect()->back()->with('success', 'Customer Added Successfully');
        }
        return redirect(route('customer.index'))->with('success', 'Updated Successfully !!');
    }
    

    public function minorStore(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required'],
            'dob' => ['required'],
            'parent' => ['required'],
            'relation' => ['required'],
        ]);


        $data = new Customer();
        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->gender = $request->gender;
        $data->dob = $request->dob;
        $data->parent = $request->parent;
        $data->relation = $request->relation;
        
        $data->save();

        // Activity Tracker
        $activity = new ActivityTracker();
        $activity->admin_id = Auth::user()->id;
        $activity->action = Auth::user()->name ." has Added ". $request->first_name ." Member";
        $activity->page = "customer";
        $activity->page_data_id = $data->id;
        $activity->save();
        // Activity Tracker
        
        return redirect()->back()->with('success', 'Member Added Successfully');
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
                $cellValueL = $sheet->getCell('L'. $row)->getValue();
                $cellValueM = $sheet->getCell('M'. $row)->getValue();
                $cellValueN = $sheet->getCell('N'. $row)->getValue();
                $cellValueO = $sheet->getCell('O'. $row)->getValue();
                $cellValueP = $sheet->getCell('P'. $row)->getValue();
                $cellValueQ = $sheet->getCell('Q'. $row)->getValue();
                $cellValueR = $sheet->getCell('R'. $row)->getValue();
                $cellValueS = $sheet->getCell('S'. $row)->getValue();
                $cellValueT = $sheet->getCell('T'. $row)->getValue();
                $cellValueU = $sheet->getCell('U'. $row)->getValue();
                $cellValueV = $sheet->getCell('V'. $row)->getValue();
                $cellValueW = $sheet->getCell('W'. $row)->getValue();
                $cellValueX = $sheet->getCell('X'. $row)->getValue();

                // Check if email or phone already exists in the database
                $existingRecord = Customer::where('email', $cellValueA)->first();

                // If record already exists, store it in the duplicate entries array
                if ($existingRecord) {
                    $duplicateEntries[] = [
                        'email' => $cellValueA,
                        'phone' => $cellValueB,
                    ];
                } else {
                    // Create a new model instance
                    $model = new Customer();
                    
                    // Assign values to model attributes
                    $model->email = $cellValueA;
                    $model->phone = $cellValueB;
                    $model->first_name = $cellValueC;
                    $model->last_name = $cellValueD;
                    $model->gender = $cellValueE;
                    $model->password = Hash::make("12345678");
                    $model->dob = date("Y-m-d", strtotime($cellValueF));
                    $model->address = $cellValueG;
                    $model->city = $cellValueH;
                    $model->country = $cellValueI;
                    $model->state = $cellValueJ;
                    $model->pincode = $cellValueK;
                    $model->meal_preference = $cellValueL;
                    $model->blood_group = $cellValueM;
                    $model->profession = $cellValueN;
                    $model->emg_name = $cellValueO;
                    $model->emg_contact = $cellValueP;
                    $model->t_size = $cellValueQ;
                    $model->medical_condition = $cellValueR;
                    $model->vaccination = $cellValueS;
                    $model->something = $cellValueT;
                    $model->have_road_trip = $cellValueU;
                    $model->three_travel = $cellValueV;
                    $model->three_place = $cellValueW;
                    $model->referred_by = $cellValueX;


                    // Save the model instance to the database
                    $model->save();


                    // Activity Tracker
                    $activity = new ActivityTracker();
                    $activity->admin_id = Auth::user()->id;
                    $activity->action = Auth::user()->name ." has Imported ". $cellValueC ." Customer through XLSX";
                    $activity->page = "customer";
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
        $sampleFilePath = public_path('samples/sample-customer.xlsx');
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        // Return the sample file as a download response
        return Response::download($sampleFilePath, 'sample-customer.xlsx', $headers);
    }


// ...existing code...
public function export()
{
    $customers = Customer::all();

  
    $data = [];
    $data[] = [
        'Email', 'Phone', 'First Name', 'Last Name', 'Gender', 'DOB', 'Address', 'City', 'Country', 'State', 'Pincode',
        'Meal Preference', 'Blood Group', 'Profession', 'Emergency Conatct Name', 'Emergency Contact Number', 'T-shirt Size',
        'Medical Condition', 'Referred By',
        'Parent Name',
        'Relation'    
    ];

    foreach ($customers as $customer) {
        // Get Parent Name
        $parentName = 'No Parent';
        if ($customer->parent != 0) {
            $parent = Customer::find($customer->parent);
            if ($parent) {
                $parentName = $parent->first_name . ' ' . $parent->last_name;
            }
        }

        $data[] = [
            $customer->email,
            "$customer->telephone_code"."$customer->phone",
            $customer->first_name,
            $customer->last_name,
            $customer->gender,
            $customer->dob,
            $customer->address,
            $customer->city,
            $customer->country,
            $customer->state,
            $customer->pincode,
            $customer->meal_preference,
            $customer->blood_group,
            $customer->profession,
            $customer->emg_name,
            $customer->emg_contact,
            $customer->t_size,
            $customer->medical_condition,
            $customer->referred_by,
            $parentName,                // <-- Added
            $customer->relation ?? '',  // <-- Added
        ];
    }

    // Create a new PhpSpreadsheet instance
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Add data to the spreadsheet
    $sheet->fromArray($data, null, 'A1');

    // Set auto column size for all columns
    foreach(range('A', $sheet->getHighestColumn()) as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Create a writer for XLSX format
    $writer = new Xlsx($spreadsheet);

    // Set headers for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="customers.xlsx"');
    header('Cache-Control: max-age=0');

    // Output the spreadsheet data to a file
    $writer->save('php://output');
}
// ...existing code...

    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Customer::where('id', $id)->first();
        $countries = Country::all();
        return view('admin.customers.edit', compact('data', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if($request->popup){
            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
            ]);
        }else{
          $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required'],
            'telephone_code' => ['required'],
            'phone' => ['required', 'max:255', 'unique:customers,phone,' . $request->id],
        ];
        if ($request->filled('email')) {
            $rules['email'] = ['required', 'email', 'max:255', 'unique:customers,email,' . $request->id];
        }
        $request->validate($rules);
        }


        $data = Customer::find($request->id);
        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->gender = $request->gender;
        $data->telephone_code = $request->telephone_code ?? null;
        if($request->dob){
            $data->dob = $request->dob;
        }

        $data->country = $request->country;
        $data->state = $request->state;
        $data->pincode = $request->pincode;
        $data->city = $request->city;
        $data->address = $request->address;


        $data->meal_preference = $request->meal_preference;
        $data->blood_group = $request->blood_group;
        $data->profession = $request->profession;
        $data->emg_contact = $request->emg_contact;
        $data->t_size = $request->t_size;
        $data->medical_condition = $request->medical_condition;
      
        $data->emg_name = $request->emg_name;
      

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
        $activity->page = "customer";
        $activity->page_data_id = $request->id;
        $activity->save();
        // Activity Tracker

        if($request->popup){
            return redirect()->back()->with('success', 'Customer Updated Successfully');
        }
        
        return redirect(route('customer.index'))->with('success', 'Updated Successfully !!');
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     $data = Customer::find($id);

    //     // Activity Tracker
    //     $activity = new ActivityTracker();
    //     $activity->admin_id = Auth::user()->id;
    //     $activity->action = Auth::user()->name ." has Deleted ". $data->name ." Customer";
    //     $activity->page = "customer";
    //     $activity->page_data_id = $data->id;
    //     $activity->save();
    //     // Activity Tracker

    //     $data->delete();
    //     return redirect(route('customer.index'))->with('success', 'Deleted Successfully!!');
    // }
    public function emailSuggestions(Request $request)
    {
        $query = $request->input('query');
        $customers = Customer::where('email', 'like', "%$query%")
            ->take(10)
            ->get(['id', 'email', 'first_name', 'last_name']);
    
        return response()->json($customers);
    }
    public function view($id)
    {
        $data = Customer::where('id', $id)->first();
        $minorCheck = Customer::where('parent', $id)->count();
          $avalPoints = LoyalityPts::where('customer_id', $data->id)
            ->where('status', 'Approved')
            ->get();

        $totalAmtPoint = $avalPoints->sum('trans_amt');
        return view('admin.customers.view', compact('data', 'minorCheck','totalAmtPoint'));
    }

    public function activityPage($id)
    {
        $data = $id;
        return view('admin.customers.activity', compact('data'));
    }

    public function activity(Request $request)
    {
        if(checkAdminRole()){
            $data = ActivityTracker::where(['page'=> 'customer', 'page_data_id'=>$request->id])->orderBy('id', 'desc')->get();
        }else{
            $data = ActivityTracker::where(['page'=> 'customer', 'page_data_id'=>$request->id])->where('admin_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        }

        $data->map(function ($item, $index) {
            $item->created = date("M d, Y H:i:s", strtotime($item->created_at));
            $item->name = $item->admin->name;
        });
        return DataTables::of($data)->make(true);
    }



    // view
    public function trips(Request $request){
        $id = $request->customer_id;
        $data = TripBooking::whereJsonContains('customer_id', "$id")->where('trip_status', '!=', 'Draft')->orderBy('id', 'Desc')->get();
        $data->map(function ($item, $index) use($id) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->trip_name = $item->trip->name ?? null;
            $item->trip_status = $item->trip_status;
            $item->admin_name = $item->admin->name ?? null;
            $item->trip_amt = "₹".number_format(totalTripCostOfCustomerById($id, $item->id)) ?? 0;
           

            $customers = json_decode($item->customer_id);
            // dd($customers);
            $memTravel = "";
            foreach($customers as $customer){
                $cus = getCustomerById($customer);
                if($cus->relation == "Self"){
                    $memTravel .= $cus->name .", ";
                }else{
                    $memTravel .= $cus->name ."(". $cus->relation . "), ";
                }
            }

            if(count($customers) > 1){
                $item->members = $memTravel;
            }else{
                $item->members = "No";
            }
        });
        return DataTables::of($data)->make(true);
    }

    public function points(Request $request){
        $id = $request->customer_id;
        $data = LoyalityPts::where('customer_id', "$id")->orderBy('id', 'Desc')->where('status', 'Approved')->get();
        // dd($data);
        $data->map(function ($item, $index) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->admin_name = $item->admin->name ?? null;
        });
        return DataTables::of($data)->make(true);
    }

    public function transfer(Request $request){
        $id = $request->customer_id;
        $data = TransferPts::where('customer_id', "$id")->orderBy('id', 'Desc')->get();
        $data->map(function ($item, $index) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->customer_name = getCustomerByEmail($item->reciever_mail)->name;
        });
        return DataTables::of($data)->make(true);
    }

    public function referal(Request $request){
        $id = $request->customer_id;
        $email = getCustomerById($id)->email;
        $data = Customer::where('referred_by', "$email")->orderBy('id', 'Desc')->get();
        $data->map(function ($item, $index) {
            $item->created = date("M d, Y", strtotime($item->created_at));
        });
        return DataTables::of($data)->make(true);
    }

    public function minor(Request $request){
        $id = $request->customer_id;
        $data = Customer::where('parent', "$id")->orderBy('id', 'Desc')->get();
        
        return DataTables::of($data)->make(true);
    }

    public function referalStore(Request $request)
    {
       
            $request->validate([
                'referee_email' => 'required|email',
                'expedition' => 'required',
            ]);
 
            $trip = Trip::where('id', $request->expedition)->first();

            $trip_cost = $trip->price ?? null;
            $refer_by_email = $request->Referrer_email;
         
            $referredUser_email = $request->referee_email;
    
            if ($request->referee_email != null) {
                $referredUser = Customer::where('email', $referredUser_email)->first();
    
                if ($referredUser->referred_by != null) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'This Customer is already referred.'
                    ], 400);
                }
    
                $referby = Customer::where('email', $refer_by_email)->first();
                if ($referby) {
                    $expiryDate = now()->addYears(2)->format('Y-m-d');
                    $referrel_points = intval($trip_cost * 0.01);
                    $referby->points = $referby->points + $referrel_points;
                    $referby->save();
    
                    $referralTransaction = new LoyalityPts();
                    $referralTransaction->customer_id = $referby->id;
                    $referralTransaction->trip_name = $trip->name;
                    $referralTransaction->reason = 'Referral';
                    $referralTransaction->trans_type = 'Cr';
                    $referralTransaction->trans_amt = $referrel_points;
                    $referralTransaction->balance = $referby->points;
                    $referralTransaction->expiry_date = $expiryDate;
                    $referralTransaction->trans_page = 'Customer Referral';
                    $referralTransaction->save();

                    $referredUser->referred_by = $refer_by_email;
                    $referredUser->save();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Referral Updated successfully.'
                    ], 200);
                }
    
                
            }
       
    }
    
}