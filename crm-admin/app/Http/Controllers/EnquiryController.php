<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Response;

class EnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.enquiry.index');
    }

    public function get()
    {
        $data = Enquiry::orderBy('id', 'Desc')->get();

        $data->map(function ($item, $index) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->name = $item->customer->first_name ." ".$item->customer->last_name;
            $item->phone = $item->customer->phone;
            $item->email = $item->customer->email;
            $item->trip_name = $item->trip->name ?? ($item->expedition . '-' . $item->{"tailor_made_comment"});
            $item->traveler = json_decode($item->traveler);
        });
        

        return DataTables::of($data)->make(true);
    }


    public function view(Request $request)
    {
        $enquiry = Enquiry::where('id', $request->id)->update(['is_read' => true]);;
        $data = Enquiry::where('id', $request->id)->first();
        return view('admin.enquiry.view', compact('data'));
    }





    public function export()
    {
        // Fetch data from the Customer model
        $datas = Enquiry::all();

        // Prepare data for export
        $data = [];
        $data[] = ["Added Date","Customer Name","Customer Phone","Customer Email", "Expedition","Adult","Minor","Redeem Points","Traveler"];

        foreach ($datas as $item) {
            $data[] = [
                $item->created_at,
                $item->customer->first_name." ".$item->customer->last_name,
                $item->customer->phone,
                $item->customer->email,
                $item->trip->name,
                $item->adult,
                $item->minor,
                $item->redeem_points,
                $item->traveler
            ];
        }

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add data to the spreadsheet
        $sheet->fromArray($data, null, 'A1');

        // Set auto column size for all columns
        foreach(range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Create a writer for XLSX format
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="enquiries.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet data to a file
        $writer->save('php://output');
    }

}