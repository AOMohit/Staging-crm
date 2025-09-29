<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BirthdayController
{
    public function index(Request $request){

        $filter = $request->input('filter', '');
        $fromDate = $request->input('from_date', null);
        $toDate = $request->input('to_date', null);

        $query = Customer::selectRaw(
            "COUNT(*) as birthdaysCount,
         SUM(CASE WHEN birthday_email_sent = 1 THEN 1 ELSE 0 END) as emails_sent,
         SUM(CASE WHEN birthday_email_sent = 0 THEN 1 ELSE 0 END) as emails_not_sent"
        )->whereNotNull('dob')
       ->whereRaw("DATE_FORMAT(dob, '%m-%d-%Y') != '01-01-1970'");
        // Apply filter conditions
        $this->applyFiltersDynamically($filter, $query);

        if ($fromDate && $toDate) {
            $fromMonthDay = Carbon::parse($fromDate)->format('m-d');
            $toMonthDay = Carbon::parse($toDate)->format('m-d');

            $query->where(function ($q) use ($fromMonthDay, $toMonthDay) {
                $q->whereRaw("DATE_FORMAT(dob, '%m-%d') BETWEEN ? AND ?", [$fromMonthDay, $toMonthDay]);
            });
        //$query->whereBetween('dob', [$fromDate, $toDate]);
        }
        $counts = $query->first();

     
        return view('admin.birthday.index', [
            'title' => 'Birthday',
            'birthdaysCount' => $counts->birthdaysCount,
            'emails_sent' => $counts->emails_sent,
            'emails_not_sent' => $counts->emails_not_sent,
            ]);

    }

    public function getBirthdays(Request $request)
    {
        // Fetch results with filtering and pagination
        $results = $this->filterAndFetchBirthdays($request);

  

        // Prepare response
        $response = [
            "draw" => intval($request->input('draw', 1)),
            "recordsTotal" => $results['totalRecords'], // Total unfiltered records
            "recordsFiltered" => $results['filteredRecords'], // Total records after filtering
            "data" => $results['data'], // Data for the current page
            "birthdayCount" => $results['birthdayCount'], // Total birthday count for the current query
        ];
     


        return response()->json($response);
    }

    /**
     * @param mixed $filter
     * @param $query
     * @return void
     */
    private function applyFiltersDynamically(mixed $filter, $query): void
    {
        if ($filter === 'Daily') {
            $query->whereMonth('dob', now()->month)
                ->whereDay('dob', now()->day);
        } elseif ($filter === 'Weekly') {
            $next7Days = collect(range(0, 6))->map(fn($daysAhead) => now()->addDays($daysAhead));

//            $last7Days = collect(range(0, 6))->map(fn($daysAgo) => now()->subDays($daysAgo));
            $query->where(function ($q) use ($next7Days) {
                foreach ($next7Days as $date) {
                    $q->orWhere(function ($subQuery) use ($date) {
                        $subQuery->whereMonth('dob', $date->month)
                            ->whereDay('dob', $date->day);
                    });
                }
            });
        } elseif ($filter === 'Monthly') {
            // Fetch birthdays within the last 30 days (ignoring the year)
            $startDate = now(); // Today's date
            $endDate = now()->addDays(30); // Date 30 days from today

            $query->where(function ($q) use ($startDate, $endDate) {
                $q->where(function ($subQuery) use ($startDate) {
                    $subQuery->whereMonth('dob', $startDate->month)
                        ->whereDay('dob', '>=', $startDate->day);
                })->orWhere(function ($subQuery) use ($endDate) {
                    $subQuery->whereMonth('dob', $endDate->month)
                        ->whereDay('dob', '<=', $endDate->day);
                });
            });
        }
//        elseif ($filter === 'Yearly') {
//            $startDate = now(); // Today
//            $endDate = now()->addDays(365); // 365 days from today
//
//            $query->where(function ($q) use ($startDate, $endDate) {
//                $q->where(function ($subQuery) use ($startDate) {
//                    $subQuery->whereMonth('dob', $startDate->month)
//                        ->whereDay('dob', '>=', $startDate->day);
//                })->orWhere(function ($subQuery) use ($endDate) {
//                    $subQuery->whereMonth('dob', $endDate->month)
//                        ->whereDay('dob', '<=', $endDate->day);
//                });
//            });
//        }
    }

    private function filterAndFetchBirthdays(Request $request, $export = false)
    {
        $filter = $request->input('filter', '');
        $fromDate = $request->input('from_date', null);
        $toDate = $request->input('to_date', null);

        // Base query
        $query = Customer::whereNotNull('dob')
          
             ->whereRaw("DATE_FORMAT(dob, '%m-%d-%Y') != '01-01-1970'");

          

        // Apply filters dynamically
        $this->applyFiltersDynamically($filter, $query);

        // Apply date range filter
        if ($fromDate && $toDate) {
            $fromMonthDay = Carbon::parse($fromDate)->format('m-d');
            $toMonthDay = Carbon::parse($toDate)->format('m-d');

            $query->where(function ($q) use ($fromMonthDay, $toMonthDay) {
                $q->whereRaw("DATE_FORMAT(dob, '%m-%d') BETWEEN ? AND ?", [$fromMonthDay, $toMonthDay]);
            });
//            $query->whereBetween('dob', [$fromDate, $toDate]);
        }

        // Search functionality
        $search = $request->input('search.value', '');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%$search%")
                    ->orWhere('last_name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%")
                    ->orWhereRaw("DATE_FORMAT(dob, '%d') LIKE ?", ["%$search%"]) // Day
                    ->orWhereRaw("DATE_FORMAT(dob, '%M') LIKE ?", ["%$search%"]) // Month name
                    ->orWhereRaw("DATE_FORMAT(dob, '%Y') LIKE ?", ["%$search%"]); // Year
            });
        }

        // Sorting functionality
        $columns = [
            'first_name',         // First Name
            'email',              // Email
            DB::raw("DATE_FORMAT(dob, '%d')"), // Day (extracted from dob)
            DB::raw("MONTH(dob)"),  // Month (numeric for sorting)
            DB::raw("DATE_FORMAT(dob, '%Y')"), // Year (extracted from dob)
            'birthday_email_sent' // Email sent status
        ];

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc'); // 'asc' or 'desc'

        // Check if the column index exists in the array
        if (isset($columns[$orderColumnIndex])) {
            $query->orderBy($columns[$orderColumnIndex], $orderDirection);
        }

        // Total records before filtering
        $totalRecords = Customer::whereNotNull('dob')
          ->whereRaw("DATE_FORMAT(dob, '%m-%d-%Y') != '01-01-1970'")
            ->count();
          

        // Total records after filtering (for pagination purposes)
        $filteredRecords = $query->count();
       

        // Apply pagination
        if ($export) {
            $paginatedData = $query->get(); // Get all filtered records for export
        } else {
            $offset = $request->input('start', 0);
            $length = $request->input('length', 10);
            $paginatedData = $query->skip($offset)->take($length)->get();
        }

        // Map data and calculate birthday count
        $birthdayCount = 0;
        $data = $paginatedData->map(function ($item) use (&$birthdayCount) {
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->name = $item->first_name . " " . $item->last_name;

            if ($item->dob) {
                $birthdayCount++;
                $timestamp = strtotime($item->dob);

                $item->formatted_date = date("M d, Y", $timestamp);
                $item->day = date("d", $timestamp);
                $item->month = date("m", $timestamp);
                $item->year = date("Y", $timestamp);
                $item->month_name = date("F", $timestamp);
            } else {
                $item->created = '';
                $item->day = '';
                $item->month = '';
                $item->year = '';
                $item->month_name = '';
            }

            return $item;
        });

        return [
            'data' => $data, // Current page data
            'totalRecords' => $totalRecords, // Total unfiltered records
            'filteredRecords' => $filteredRecords, // Total records after applying filters
            'birthdayCount' => $birthdayCount, // Total birthdays in the current query
        ];
    }
    public function export(Request $request)
    {
        $results = $this->filterAndFetchBirthdays($request, true);

        $exportData = $results['data']->map(function ($item) {
            return [
                'Name' => $item->name,
                'Email' => $item->email,
                'Day' => $item->day,
                'Month' => $item->month_name,
                'Year' => $item->year,
                'Email Sent' => $item->birthday_email_sent == 1 ? 'Yes' : 'No',
            ];
        })->toArray();

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['Name', 'Email', 'Day', 'Month', 'Year', 'Email Sent']);
        // Add data to the spreadsheet
        $sheet->fromArray($exportData, null, 'A2');

        // Set auto column size for all columns
        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Create a writer for XLSX format
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="customer-birthdays.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the spreadsheet data to a file
        $writer->save('php://output');
    }
}
