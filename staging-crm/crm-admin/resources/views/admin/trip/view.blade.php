@extends('admin.inc.layout')

@section('content')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: red;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .tab-slider {
            display: none;
        }
      
    </style>

    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="{{ route('trip.index') }}">Trip</a> /
                view
        </h4>
        <!-- DataTable with Buttons -->
        <div class="row">
            <div class="p-3">
                <div class="row">
                    <div class="col-3">
                        <label class="switch text-start" title="Sold Out">
                            <input onchange="changeTripStatus()" type="checkbox"
                                @if ($data->status == 'Sold Out') checked @endif>
                            <span class="slider"></span>
                        </label>
                        <b>Sold Out</b>
                    </div>
                 
                      @if($carbonInfo->isEmpty())
                        <div class="col-6"></div>
                      @else
                        <div class="col-6">
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="This means carbon neutral info is already imported for this trip.">
                                    <i class="bi bi-info-circle-fill text-primary"></i>Carbon Neutral Information Imported for this trip
                            </span>
                        </div>
                      @endif
                    <div class="dt-action-buttons text-end pt-3 pt-md-0 col-3">
                        <div class="dt-buttons btn-group flex-wrap">
                            <div class="dropdown d-inline-block">
                               <button class="btn btn-primary dropdown-toggle d-flex align-items-center" type="button" id="actionsDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.9em; margin-right:20px;">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="actionsDropdown">
                                    @if (checkTripEditable(request()->id))
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="addExpense()">Add Expense</a>
                                        </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ route('trip.details.export-master', ['trip_id' => request()->id]) }}">Master Detail Report</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('trip.details.export-expense', ['trip_id' => request()->id]) }}">Generate Expense Report</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('trip.details.export-room', ['trip_id' => request()->id]) }}">Export Room List</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('trip.details.export-vehicle', ['trip_id' => request()->id]) }}">Export Convoy List</a>
                                    </li>

                                    
                                @if(strtotime(date('Y-m-d')) > strtotime($data->end_date))
                                            {{-- @if(in_array(auth()->user()->email, ['Vageesh@adventuresoverland.com'])) --}}
                                            @if(auth()->user()->email == 'Vageesh@adventuresoverland.com')
                                            
                                                <li>
                                                    <a class="dropdown-item" id="bulk-carbon-neutral-btn" data-bs-toggle="modal" data-bs-target="#bulkCarbonCertificate" href="javascript:void(0)">Bulk Send Carbon Neutral Certificate</a>
                                                </li>
                                            @endif
                                @else
                                        @if($carbonInfo->isEmpty())
                                            <li>
                                                <a class="dropdown-item" id="carbon-neutral-btn" data-bs-toggle="modal" data-bs-target="#basicModal" href="javascript:void(0)">Add Carbon Neutral Info</a>
                                            </li>
                                        @else
                                            <li>
                                                <a class="dropdown-item" id="edit_carbon_neutral_btn" onclick="editCarbonNeutralInfo({{request()->id}})">Edit Carbon Neutral Info</a>
                                            </li>
                                        @endif
                                  
                                        
                                @endif
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">

                    <!-- User Card -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="user-avatar-section">
                                <div class="d-flex align-items-center flex-column">
                                    @if ($data->image)
                                        <img class="img-fluid rounded mb-3 mt-4"
                                            src="{{ url('storage/app/' . $data->image) }}" height="120" width="120"
                                            alt="{{ $data->name }}" />
                                    @else
                                        <img class="img-fluid rounded mb-3 mt-4"
                                            src="{{ url('public/admin') }}/assets/img/avatars/10.png" height="120"
                                            width="120" alt="{{ $data->name }}" />
                                    @endif
                                    <div class="user-info text-center">
                                        <h4>{{ $data->name }}
                                            @if (checkTripEditable(request()->id))
                                                <a data-bs-toggle="tooltip" data-bs-placement="right"
                                                    data-bs-original-title="Edit"
                                                    href="{{ route('trip.edit', $data->id) }}"><i
                                                        class="fa fa-pen"></i></a>
                                            @endif
                                        </h4>
                                        @if ($data->status == 'Sold Out')
                                            <span class="badge bg-danger">Sold Out</span>
                                        @elseif ($data->status == 'Cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            @if (strtotime(date('Y-m-d')) > strtotime($data->end_date))
                                                <span class="badge bg-success">Completed</span>
                                            @elseif(strtotime(date('Y-m-d')) >= strtotime($data->start_date) && strtotime(date('Y-m-d')) <= strtotime($data->end_date))
                                                <span class="badge bg-secondary">Ongoing</span>
                                            @else
                                                <span class="badge bg-warning">Upcoming</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <h5 class="pb-3 border-bottom mb-3">Details</h5>
                            <div class="info-container">
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-3">
                                        <span class="fw-semibold text-heading me-2">Relation Manager:</span>
                                        <span>{{  $data->relation_manager_names}}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-semibold text-heading me-2">Trip Type:</span>
                                        <span>{{ $data->trip_type }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-semibold text-heading me-2">Start Date:</span>
                                        <span>{{ $data->start_date }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-semibold text-heading me-2">End Date:</span>
                                        <span class="">{{ $data->end_date }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-semibold text-heading me-2">Trip Cost:</span>
                                        <span class="">₹{{ indian_number_format($data->price) }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-semibold text-heading me-2">Total Trip Cost:</span>
                                        <span
                                            class="">₹{{ indian_number_format(actualTripCostSumById($data->id)) }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-semibold text-heading me-2">Duration Night:</span>
                                        <span>{{ $data->duration_nights }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-semibold text-heading me-2">Continent:</span>
                                        <span>{{ $data->continent }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-semibold text-heading me-2">Landscape:</span>
                                        <span>{{ $data->landscape }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-semibold text-heading me-2">Style:</span>
                                        <span>{{ $data->style }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-semibold text-heading me-2">Activity:</span>
                                        <span>{{ $data->activity }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-semibold text-heading me-2">Overview:</span>
                                        <span>{{ $data->overview }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-semibold text-heading me-2">Status:</span>
                                        <span>{{ $data->status }}</span>
                                    </li>

                                    @if ($data->reason)
                                        <li class="mb-3">
                                            <div class="alert alert-danger">
                                                <span class="fw-semibold text-heading me-2">Cancelation Reason:</span>
                                                <span>{{ $data->reason }}</span>
                                            </div>
                                        </li>
                                    @endif
                                    @if ($data->merchandise_id != null && $data->merchandise_id != 'null')
                                        <li class="mb-3">
                                            <span class="fw-semibold text-heading me-2">Merchandise:</span>
                                            @foreach (json_decode($data->merchandise_id) as $mch_id)
                                                <span>{{ getMerchandiseById($mch_id)->title }}, </span>
                                            @endforeach
                                        </li>
                                    @endif
                                    @if ($data->stationary_id != null && $data->stationary_id != 'null')
                                        <li class="mb-3">
                                            <span class="fw-semibold text-heading me-2">Stationary:</span>
                                            @foreach (json_decode($data->stationary_id) as $stn_id)
                                                <span>{{ getstationaryById($stn_id)->title }}, </span>
                                            @endforeach
                                        </li>
                                    @endif

                                    <li class="mb-3">
                                        <span class="fw-semibold text-heading me-2">Sustainability:</span>
                                        @if ($data->tree_no != null && $data->donation_amt != null)
                                            <span>Enabled</span>
                                        @else
                                            <span>Disabled</span>
                                        @endif
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /User Card -->
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="nav-align-top">
                                <ul class="nav nav-tabs" role="tablist" style="font-size: 0.7em">
                                    <li class="nav-item">
                                        <button type="button" class="nav-link active" role="tab"
                                            data-bs-toggle="tab" data-bs-target="#navs-top-travelers"
                                            aria-controls="navs-top-travelers" aria-selected="true">
                                            Customers
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-top-rooms" aria-controls="navs-top-rooms"
                                            aria-selected="false">
                                            Rooms
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-top-vehicles" aria-controls="navs-top-vehicles"
                                            aria-selected="false">
                                            Vehicles
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-top-extra" aria-controls="navs-top-extra"
                                            aria-selected="false">
                                            Extra Services
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-top-vendors" aria-controls="navs-top-vendors"
                                            aria-selected="false">
                                            Expense
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-top-merchandise" aria-controls="navs-top-merchandise"
                                            aria-selected="false">
                                            Merchandise
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-top-stationary" aria-controls="navs-top-stationary"
                                            aria-selected="false">
                                            Stationary
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-top-agents" aria-controls="navs-top-agents"
                                            aria-selected="false">
                                            Travel Agent
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-top-rec" aria-controls="navs-top-rec"
                                            aria-selected="false">
                                            Net Receivables
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <div class="tab-pane fade show active" id="navs-top-travelers" role="tabpanel">
                                      <div class="text-end">
                                        <a href="{{ route('customer_registration_data', ['trip_id' => request()->id]) }}"
                                            class="btn btn-info btn-sm"> <i class="mdi mdi-export-variant me-sm-1"></i>
                                            Export</a>
                                    </div>
                                <div class="card-datatable table-responsive pt-0">
                                        <table id="myDatatableCustomer" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Customer</th>
                                                    <th>Pax</th>
                                                    <th>Total Amount</th>
                                                    <th>Pending Amount</th>
                                                    <th>Contact No.</th>
                                                    <th>Booking Date</th>
                                                    <th>Spoc</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="navs-top-rooms" role="tabpanel">
                                    <div class="text-end">
                                        <a onclick="allotRoom({{ request()->id }})" href="javaScript:void(0)"
                                            class="btn btn-warning btn-sm">Allot</a>
                                    </div>
                                    <div class="card-datatable table-responsive pt-0">
                                        <table id="myDatatableRooms" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Customer</th>
                                                    <th>Room Type</th>
                                                    <th>Room Category</th>
                                                    <th>Amount</th>
                                                    <th>Allotment</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $roomTypeSum }}</td>
                                                    <td></td>
                                                    <td>₹{{ indian_number_format($roomTypeAmtSum) }}</td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="navs-top-vehicles" role="tabpanel">
                                    <div class="text-end">
                                        <a onclick="allotVehicle({{ request()->id }})" href="javaScript:void(0)"
                                            class="btn btn-warning btn-sm">Allot</a>
                                    </div>
                                    <div class="card-datatable table-responsive pt-0">
                                        <table id="myDatatableVehicle" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Customer</th>
                                                    <th>Vehicle Type</th>
                                                    <th>Vehicle Category</th>
                                                    <th>Supplement</th>
                                                    <th>Allotment</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <thead>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ $vehicleSeat }}</td>
                                                    <td>₹{{ indian_number_format($vehicleSeatAmt) }}</td>
                                                    <td></td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="navs-top-extra" role="tabpanel">
                                    <div class="card-datatable table-responsive pt-0">

                                        <table id="myDatatableExtra" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Customer</th>
                                                    <th>Extra Service</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="navs-top-vendors" role="tabpanel">
                                    <div class="card-datatable table-responsive pt-0">
                                        <div class="text-end">
                                            <a href="{{ route('trip.details.expense-report-downlaod', ['trip_id' => request()->id]) }}"
                                                    class="btn btn-info btn-sm"> <i class="mdi mdi-export-variant me-sm-1"></i>
                                                    Export</a>
                                        </div>

                                        <table id="myDatatableVendor" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Vendor</th>
                                                    <th>Category</th>
                                                    <th>Service</th>
                                                    <th>Amount Due</th>
                                                    <th>Amount paid</th>
                                                    <th>Pending Amount</th>
                                                    <th>Document</th>
                                                    <th>Comment</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="navs-top-stationary" role="tabpanel">
                                    <div class="text-end">
                                        <a href="{{ route('stationary_export', ['trip_id' => request()->id]) }}"
                                            class="btn btn-info btn-sm"> <i class="mdi mdi-export-variant me-sm-1"></i>
                                            Export</a>
                                        <a href="{{ route('trip.details.send-stationary-email', ['trip_id' => request()->id]) }}"
                                            class="btn btn-warning btn-sm">Send Email</a>
                                    </div>
                                    <div class="card-datatable table-responsive pt-0">
                                        <table id="myDatatableStationary" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Trip Name</th>
                                                    <th>Trip Date</th>
                                                    <th>Customer</th>
                                                    <th>Stationary Type</th>
                                                    <th>Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="navs-top-merchandise" role="tabpanel">
                                    <div class="text-end">
                                        <a href="{{ route('merchandise_export', ['trip_id' => request()->id]) }}"
                                            class="btn btn-info btn-sm"> <i class="mdi mdi-export-variant me-sm-1"></i>
                                            Export</a>
                                        <a href="{{ route('trip.details.send-merchandise-email', ['trip_id' => request()->id]) }}"
                                            class="btn btn-warning btn-sm">Send Email</a>
                                    </div>
                                    <div class="card-datatable table-responsive pt-0">
                                        <table id="myDatatableMerchandise" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Trip Name</th>
                                                    <th>Trip Date</th>
                                                    <th>Traveller Name</th>
                                                    <th>Merchandise Type </th>
                                                    <th>Size</th>
                                                    <th>Qty</th>
                                                    <th>Gender</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="navs-top-agents" role="tabpanel">
                                    <div class="card-datatable table-responsive pt-0">

                                        <table id="myDatatableAgent" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Customer</th>
                                                    <th>Agent Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="navs-top-rec" role="tabpanel">
                                    <div class="card-datatable table-responsive pt-0">
                                        <table id="myDatatableRec" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Customer Name</th>
                                                    <th>Total Receivables</th>
                                                    <th>Total Recieved</th>
                                                    <th>Net Receivables</th>
                                                    <th>Send Email</th>
                                                    <th>View</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="card h-100" style="font-size: 0.8em;">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Trip Summary</h5>
                    </div>

                    <div class="table-responsive text-nowrap border-top">
                        <table class="table">
                            <tbody class="table-border-bottom-0">
                                <tr>
                                    <td class="pe-5"><span class="text-heading">Total Pax</span></td>
                                    <td class="ps-5 d-flex justify-content-end">
                                        <span
                                            class="text-heading fw-semibold">{{ indian_number_format(getPaxFromTripId(request()->id)) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-5"><span class="text-heading">Total Recievable Amount</span></td>
                                    <td class="ps-5 d-flex justify-content-end">
                                        <span
                                            class="text-heading fw-semibold">₹{{ indian_number_format(totalTripAmountRcvdById($data->id) + abs(totalPayableAmtOfTrip($data->id) - totalTripAmountRcvdById($data->id))) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-5"><span class="text-heading">Amount Recieved</span></td>
                                    <td class="ps-5 d-flex justify-content-end">
                                        <span
                                            class="text-heading fw-semibold">₹{{ indian_number_format(totalTripAmountRcvdById($data->id)) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-5"><span class="text-heading">Pending Amount</span></td>
                                    <td class="ps-5 d-flex justify-content-end">
                                        <span class="text-heading fw-semibold">
                                            ₹{{ indian_number_format(abs(totalPayableAmtOfTrip($data->id) - totalTripAmountRcvdById($data->id))) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-5"><span class="text-heading">Discount</span></td>
                                    <td class="ps-5 d-flex justify-content-end">
                                        <span class="text-heading fw-semibold">
                                            ₹{{ indian_number_format($data->price * getCustomerCountByTripId($data->id) - totalTripCostById($data->id)) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-5"><span class="text-heading">Vendor Due Payment</span></td>
                                    <td class="ps-5 d-flex justify-content-end">
                                        <span
                                            class="text-heading fw-semibold">₹{{ indian_number_format($exp_total) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-5"><span class="text-heading">Payment Made to Vendor</span></td>
                                    <td class="ps-5 d-flex justify-content-end">
                                        <span
                                            class="text-heading fw-semibold">₹{{ indian_number_format($exp_paid) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-5"><span class="text-heading">Pending Vendor Payment</span></td>
                                    <td class="ps-5 d-flex justify-content-end">
                                        <span
                                            class="text-heading fw-semibold">₹{{ indian_number_format($exp_total - $exp_paid) }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ DataTable with Buttons -->
{{-- bulk certificate send Model --}}
   <div class="modal fade" id="bulkCarbonCertificate" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1">Import Data for Bulk Certificate</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="bulk-carbon-certificate"  method="POST" enctype="multipart/form-data">
                        @csrf
                          <input type="hidden" name="trip_id" id="trip_id" value="{{ request()->id }}">
                        <div class="modal-body">
                            <div id="carbon-import-errors" class="alert alert-danger d-none"></div>
                            <div class="row">
                                <div class="col mb-4 mt-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="file" name="file" class="form-control" placeholder="File"
                                            accept=".xlsx" />
                                        <label for="nameBasic">Upload File</label>
                                    </div>
                                
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <!-- Modal for add carbon offset -->
        <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1">Import Trip List</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="carbon-import-form"  method="POST" enctype="multipart/form-data">
                        @csrf
                          <input type="hidden" name="trip_id" id="trip_id" value="{{ request()->id }}">
                        <div class="modal-body">
                            <div id="carbon-import-errors" class="alert alert-danger d-none"></div>
                            <div class="row">
                                <div class="col mb-4 mt-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="file" name="file" class="form-control" placeholder="File"
                                            accept=".xlsx" />
                                        <label for="nameBasic">Upload File</label>
                                    </div>
                                    <a href="{{ route('trip.details.carbonSampleSheet',['trip_id' => request()->id]) }}">click here</a> to download sample file
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



    <!-- Modal for edit expense -->
    <div class="modal fade" id="editExpense" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 expense-form-title">Edit Expense</h5>
                    </div>
                    <div class="card-body">
                        <form class="expense-form" action="{{ route('trip.details.expense') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="trip_id" value="{{ request()->id }}">
                            <input type="hidden" name="expense_row_id" id="expense_row_id" value="">
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <select onchange="vendorByExp(this.value)" name="extra_service_id"
                                            class="form-control" required>
                                            <option value="">Select Vendor Category</option>
                                            @foreach ($ess as $es)
                                                <option value="{{ $es->id }}">{{ $es->title }}</option>
                                            @endforeach
                                        </select>
                                        <label for="basic-default-redeem">Vendor Category<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <select onchange="getServiceByVendor(this.value)" name="vendor_id" id=""
                                            class="form-control vendor_id" required>
                                            <option value="">Select Vendor</option>
                                        </select>
                                        <label for="basic-default-redeem">Vendor<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <select name="vendor_service_id" id=""
                                            class="form-control vendor_service_id" required>
                                            <option value="">Select Vendor Service</option>

                                        </select>
                                        <label for="basic-default-redeem">Vendor Service<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" name="total_amount" class="form-control"
                                            placeholder="₹ Amount" required>
                                        <label for="basic-default-redeem">Amount<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="comment" class="form-control" placeholder="comment">
                                        <label for="basic-default-redeem">Comment</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <input type="file" name="docx" class="form-control">
                                        <label for="basic-default-redeem">Upload Document</label>
                                    </div>
                                    <a target="_blank" class="docx_link" href="">Click Here to see Document</a>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="text-center">
                                    <button class="btn btn-warning " type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for add expense -->
    <div class="modal fade" id="addExpense" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 expense-form-title">Add Expense</h5>
                    </div>
                    <div class="card-body">
                        <form class="expense-form" action="{{ route('trip.details.expense') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="trip_id" value="{{ request()->id }}">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-floating form-floating-outline">
                                        <select onchange="vendorByExp(this.value)" name="extra_service_id"
                                            class="form-control" required>
                                            <option value="">Select Vendor Category</option>
                                            @foreach ($ess as $es)
                                                <option value="{{ $es->id }}">{{ $es->title }}</option>
                                            @endforeach
                                        </select>
                                        <label for="basic-default-redeem">Vendor Category<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="form-floating form-floating-outline">
                                        <select onchange="getServiceByVendor(this.value)" name="vendor_id" id=""
                                            class="form-control vendor_id" required>
                                            <option value="">Select Vendor</option>
                                        </select>
                                        <label for="basic-default-redeem">Vendor<span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="form-floating form-floating-outline">
                                        <select name="vendor_service_id" id=""
                                            class="form-control vendor_service_id" required>
                                            <option value="">Select Vendor Service</option>

                                        </select>
                                        <label for="basic-default-redeem">Vendor Service<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" name="total_amount" class="form-control"
                                            placeholder="₹ Amount" id="exp_total_amount" required>
                                        <label for="basic-default-redeem">Amount<span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="comment" class="form-control" placeholder="comment">
                                        <label for="basic-default-redeem">Comment</label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="form-floating form-floating-outline">
                                        <input type="file" name="docx" class="form-control">
                                        <label for="basic-default-redeem">Upload Document</label>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            {{-- payment section --}}
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-floating form-floating-outline">
                                        <select onchange="paymentStatus(this.value)" name="payment_status"
                                            class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="Paid">Paid</option>
                                            <option value="Not Paid">Not Paid</option>
                                        </select>
                                        <label for="basic-default-redeem">Payment Status<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4" id="payment_status_type">
                                    <div class="form-floating form-floating-outline">
                                        <select onchange="paymentStatusType(this.value)" name="payment_status_type"
                                            class="form-control">
                                            <option value="">Select</option>
                                            <option value="Fully Paid">Fully Paid</option>
                                            <option value="Partial Paid">Partial Paid</option>
                                        </select>
                                        <label for="basic-default-redeem">Payment Type<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="fieldsContainer">
                                <div class="row field" id="expense_paid_details">
                                    <div class="col-md-6 mb-4" id="">
                                        <div class="form-floating form-floating-outline">
                                            <input type="number" name="expense_paid_amount[]" class="form-control"
                                                placeholder="₹ Amount">
                                            <label for="basic-default-redeem">Payment Amount<span
                                                    class="text-danger">*</span></label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4" id="">
                                        <div class="form-floating form-floating-outline">
                                            <select name="expense_paid_payment_mode[]" class="form-control">
                                                <option value="">Select</option>
                                                <option value="Bank Transfer">Bank Transfer</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="UPI">UPI</option>
                                                <option value="Cash">Cash</option>
                                                <option value="Credit Card">Credit Card</option>
                                            </select>
                                            <label for="basic-default-redeem">Payment Mode<span
                                                    class="text-danger">*</span></label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4" id="">
                                        <div class="form-floating form-floating-outline">
                                            <input type="date" name="expense_paid_date[]" class="form-control">
                                            <label for="basic-default-redeem">Date<span
                                                    class="text-danger">*</span></label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4" id="">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" name="expense_paid_cmt[]" class="form-control"
                                                placeholder="Comment">
                                            <label for="basic-default-redeem">Comment</label>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <div class="row justify-content-end" id="addRemoveHandle">
                                <div class="col-md-3 text-end">
                                    <a href="javaScript:void(0)" onclick="addNewField()" class="btn btn-success btn-sm">
                                        + Add</a>
                                </div>

                                <div class="col-md-3">
                                    <a href="javaScript:void(0)" onclick="removeSingleField(this)"
                                        class="btn btn-danger btn-sm"> - Remove</a>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="text-center">
                                    <button class="btn btn-warning " type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for add payment of expense -->
    <div class="modal fade" id="makeVendorPayment" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Vendor Payment Entry</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('trip.details.makeExpPayment') }}" method="post">
                            @csrf
                            <input type="hidden" name="expense_id" id="expense_id" value="">

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" name="amount" id="amount" class="form-control"
                                            placeholder="₹ Amount" required>
                                        <label for="basic-default-redeem">Amount<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <select name="payment_mode" class="form-control">
                                            <option value="">Select</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="UPI">UPI</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Credit Card">Credit Card</option>
                                        </select>
                                        <label for="basic-default-redeem">Payment Mode<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" name="date" id="date" class="form-control"
                                            required>
                                        <label for="basic-default-redeem">Date<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="comment" id="commant" class="form-control"
                                            placeholder="comment">
                                        <label for="basic-default-redeem">Comment</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="text-center">
                                    <button class="btn btn-warning " type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- delete confirmation --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are You Sure Want
                        to Delete?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a id="deleteBtn" href="" class="btn btn-danger">Delete
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for allot Rooms -->
    <div class="modal fade" id="allotRoom" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Allot Room for Traveler</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('trip.details.allotRoom') }}" method="post">
                            @csrf
                            <input type="hidden" name="trip_id" id="room_trip_id" value="{{ request()->id }}">
                            <input type="hidden" name="booking_id" id="room_booking_id" value="">

                            <div class="row mt-4">
                                <div class="col-md-12 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <select required name="customer_id[]" id="booking_customers"
                                            class="select2 form-select form-select-lg" data-allow-clear="true" multiple>

                                        </select>
                                        <label for="booking_customers">Customer Name<span
                                                class="text-danger fixed">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="comment" id="commant" class="form-control"
                                            placeholder="comment">
                                        <label for="basic-default-redeem">Comment</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="text-center">
                                    <button class="btn btn-warning " type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for allot Vehicle -->
    <div class="modal fade" id="allotVehicle" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Allot Vehicle for Traveler</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('trip.details.allotVehicle') }}" method="post">
                            @csrf
                            <input type="hidden" name="trip_id" id="vehicle_trip_id" value="{{ request()->id }}">
                            <input type="hidden" name="booking_id" id="vehicle_booking_id" value="">

                            <div class="row mt-4">
                                <div class="col-md-12 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <select name="customer_id[]" id="booking_customers_vehicle"
                                            class="select2 form-select form-select-lg" data-allow-clear="true" multiple>

                                        </select>
                                        <label for="booking_customers_vehicle">Customer Name<span
                                                class="text-danger fixed">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="vehicle_no" id="commant" class="form-control"
                                            placeholder="Vehicle" required>
                                        <label for="basic-default-redeem">Car Number<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="comment" id="commant" class="form-control"
                                            placeholder="comment">
                                        <label for="basic-default-redeem">Comment</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="text-center">
                                    <button class="btn btn-warning " type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Carbon Neutral Info Edit -->
    <div class="modal fade" id="carbonNeutralModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#5256cc;">
                    <h4 class="modal-title text-white " id="exampleModalLabel1" >
                        Edit Carbon Neutral Info
                        <p class="text-white" id="type" style="font-size:10px;"></p>
                    </h4>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  
                </div>
                <div class="modal-body">
                <div id="carbonNeutralContainer">
                        <div style="overflow-x:auto;">
                            <table id="carbonNeutralTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="d-none">Id</th>
                                        <th class="d-none">Trip Id </th>
                                        <th>Trip Name</th>
                                        <th>Customer first Name</th>
                                        <th>Customer Last Name</th>
                                        <th>Customer Email</th>
                                        <th>Customer Phone</th>
                                        <th>No of Trees</th>
                                        <th>Carbon Emission</th>
                                        <th>Total Distance</th>
                                        <th>Car Sequence No</th>
                                        <th data-col="car_name">Car Name</th>
                                        
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <button class="btn btn-success fw-900 mt-4" style="font-weight:bold;font-size:10px;"onclick="addEditableRow()">Add Pending Customer</button>
                        <button class="btn btn-primary fw-900 mt-4"  style=" font-weight:bold; font-size:10px;" onclick="saveEditableData()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        function addExpense() {
            document.querySelector(".expense-form").reset();
            $("#addExpense").modal('show');
        }

        function allotRoom(id) {
            $.ajax({
                url: "{{ route('trip.details.getCustomerByBookingId') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                    trip_id: "{{ request()->id }}",
                },
                success: function(res) {
                    var data = JSON.parse(res);
                    var text = '';
                    $.each(data, function(key, value) {
                        text +=
                            `<option value="${value.id}">${value.name}</option>`;
                    });
                    $("#booking_customers").html(text);
                    $("#room_booking_id").val(id);
                    $("#allotRoom").modal('show');
                }
            });
        }

        function allotVehicle(id) {
            $.ajax({
                url: "{{ route('trip.details.getCustomerByBookingId') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                    trip_id: "{{ request()->id }}",
                },
                success: function(res) {
                    var data = JSON.parse(res);
                    var text = '';
                    $.each(data, function(key, value) {
                        text +=
                            `<option value="${value.id}">${value.name}</option>`;
                    });
                    $("#booking_customers_vehicle").html(text);
                    $("#vehicle_booking_id").val(id);
                    $("#allotVehicle").modal('show');
                }
            });
        }

        function vendorByExp(id) {
            $.ajax({
                url: "{{ route('trip.details.vendorByExp') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id
                },
                success: function(res) {
                    $(".vendor_id").html(res);
                }
            });
        }

        function getServiceByVendor(id) {
            $.ajax({
                url: "{{ route('trip.details.getServiceByVendor') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id
                },
                success: function(res) {
                    $(".vendor_service_id").html(res);
                }
            });
        }
    </script>

    <script>
        var trip_id = "{{ request()->id }}";
        $('th').css('white-space', 'nowrap');

        $(document).ready(function() {
            getfilterdataTraveler();
            getfilterdataRooms();
            getfilterdataVehicle();
            getfilterdataExtra();
            getfilterdataVendor();
            getfilterdataMerchandise();
            getfilterdataStationary();
            getfilterdataAgent();
            getfilterdataRec();
        });

        function getfilterdataTraveler() {

            var table = $('#myDatatableCustomer').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('trip.details.travelers') !!}",
                    "type": 'GET',
                    "data": {
                        "trip_id": trip_id,
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        name: 'customer',
                        "render": function(data, type, row, meta) {
                            var data = row.customers;
                            var id = data[0].c_id;
                            var routeView = "{{ route('customer.view', ['id' => 'rowID']) }}";
                            routeView = routeView.replace('rowID', id);

                            if (data.length > 0) {
                                var text = `<a href="${routeView}">${data[0].name}</a>`;
                            } else {
                                text = '';
                            }
                            return text;
                        }
                    },
                    {
                        name: 'pax',
                        "render": function(data, type, row, meta) {
                            var text = `${row.pax} <span data-bs-toggle="tooltip" data-bs-placement="right" title="${row.members}">Members <img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg"
                                                                alt="" class="ps-2"> </span>`;
                            return text;
                        }
                    },
                    {
                        name: 'total_amount',
                        "render": function(data, type, row, meta) {
                            var text = `₹${row.total_amount}`;
                            return text;
                        }
                    },
                    {
                        name: 'pending_amount',
                        "render": function(data, type, row, meta) {
                            var text = `₹${row.pending_amount}`;
                            return text;
                        }
                    },
                    {
                        name: 'customer',
                        "render": function(data, type, row, meta) {
                            var data = row.customers;
                            var text = data[0].phone;
                            return text;
                        }
                    },
                    {
                        name: 'trip_status',
                        data: "created"
                    },
                    {
                        data: 'admin_id',
                        name: 'admin_id'
                    },
                    {
                        name: 'action',
                        "render": function(data, type, row, meta) {
                            var id = row.token;
                            var routeView = "{{ route('booking.view', ['token' => 'rowID']) }}";
                            routeView = routeView.replace('rowID', id);

                            var routeEdit = "{{ route('booking.new-trip', ['token' => 'rowID']) }}";
                            routeEdit = routeEdit.replace('rowID', id);

                            var text =
                                `<a target="_blank" class="btn btn-success btn-sm" href="${routeView}">View</a> &nbsp`;
                            text +=
                                `<a target="_blank" class="btn btn-warning btn-sm" href="${routeEdit}">Edit</a>`;
                            return text;
                        }
                    },
                ],
                'rowCallback': function(row, data, index) {
                    $('td', row).css('white-space', 'nowrap');
                    $(function() {
                        $('[data-bs-toggle="tooltip"]').tooltip();
                    });
                },
                'columnDefs': [{
                    "targets": [],
                    "orderable": false
                }],
                "language": {
                    "paginate": {
                        "previous": '&nbsp;',
                        "next": '&nbsp;'
                    }
                },
            });
        }

        function getfilterdataRooms() {

            var table = $('#myDatatableRooms').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('trip.details.rooms') !!}",
                    "type": 'GET',
                    "data": {
                        "trip_id": trip_id,
                    },

                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        name: 'customer',
                        "render": function(data, type, row, meta) {
                            var data = row.customers;
                            if (data.length > 0) {
                                var text = `<div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                        <div>${data[0].name}</div> <span class="mdi mdi-chevron-down"></span>
                    </button>
                    <div class="dropdown-menu" style="">`;
                                $.each(data, function(key, value) {
                                    text +=
                                        `<div class="dropdown-item waves-effect text-center"><div>${value.name}</div><div>${value.email}</div></div>`;
                                });
                                text += `</div>
                    
                </div>`;
                            } else {
                                text = '';
                            }
                            return text;
                        }
                    },
                    {
                        data: 'room_type',
                        name: 'room_type'
                    },
                    {
                        data: 'room_cat',
                        name: 'room_cat'
                    },
                    {
                        name: 'room_type_amt',
                        "render": function(data, type, row, meta) {
                            if (row.room_type_amt) {
                                var text = "₹" + row.room_type_amt;
                            } else {
                                var text = "";
                            }
                            return text;
                        }
                    },
                    {
                        name: 'action',
                        "render": function(data, type, row, meta) {
                            var id = row.id;
                            var routeView = "{{ route('trip.details.room-view', ['id' => 'rowID']) }}";
                            routeView = routeView.replace('rowID', id);

                            var text =
                                `<a href="${routeView}" class="btn btn-success btn-sm">View</a>`;
                            return text;
                        }
                    },
                ],
                'rowCallback': function(row, data, index) {
                    $('td', row).css('white-space', 'nowrap');
                },
                'columnDefs': [{
                    "targets": [],
                    "orderable": false
                }],
                "language": {
                    "paginate": {
                        "previous": '&nbsp;',
                        "next": '&nbsp;'
                    }
                },
            });
        }

        function getfilterdataVehicle() {

            var table = $('#myDatatableVehicle').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('trip.details.vehicle') !!}",
                    "type": 'GET',
                    "data": {
                        "trip_id": trip_id,
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        name: 'customer',
                        "render": function(data, type, row, meta) {
                            var data = row.customers;
                            if (data.length > 0) {
                                var text = `<div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                            <div>${data[0].name}</div> <span class="mdi mdi-chevron-down"></span>
                        </button>
                        <div class="dropdown-menu" style="">`;
                                $.each(data, function(key, value) {
                                    text +=
                                        `<div class="dropdown-item waves-effect text-center"><div>${value.name}</div><div>${value.email}</div></div>`;
                                });
                                text += `</div>
                        
                    </div>`;
                            } else {
                                text = '';
                            }
                            return text;
                        }
                    },
                    {
                        data: 'vehical_type',
                        name: 'vehical_type'
                    },
                    {
                        data: 'vehical_seat',
                        name: 'vehical_seat'
                    },
                    {
                        name: 'vehical_seat_amt',
                        "render": function(data, type, row, meta) {
                            if (row.vehical_seat_amt) {
                                var text = "₹" + row.vehical_seat_amt;
                            } else {
                                var text = "";
                            }
                            return text;
                        }
                    },
                    {
                        name: 'action',
                        "render": function(data, type, row, meta) {
                            var id = row.id;
                            var routeView = "{{ route('trip.details.vehicle-view', ['id' => 'rowID']) }}";
                            routeView = routeView.replace('rowID', id);

                            var text =
                                `<a href="${routeView}" class="btn btn-success btn-sm">View</a>`;
                            return text;
                        }
                    },
                ],
                'rowCallback': function(row, data, index) {
                    $('td', row).css('white-space', 'nowrap');
                },
                'columnDefs': [{
                    "targets": [],
                    "orderable": false
                }],
                "language": {
                    "paginate": {
                        "previous": '&nbsp;',
                        "next": '&nbsp;'
                    }
                },
            });
        }

        function getfilterdataExtra() {

            var table = $('#myDatatableExtra').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('trip.details.extra') !!}",
                    "type": 'GET',
                    "data": {
                        "trip_id": trip_id,
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        name: 'customer',
                        "render": function(data, type, row, meta) {
                            if (row.extras.length > 0) {
                                var text = "";
                                $.each(row.extras, function(key, value) {
                                    text +=
                                        `<div>${value.name}</div>`;
                                });
                                return text;
                            }
                        }
                    }, {
                        name: 'service',
                        "render": function(data, type, row, meta) {
                            if (row.extras.length > 0) {
                                var text = "";
                                $.each(row.extras, function(key, value) {
                                    text +=
                                        `<div>${value.service}</div>`;
                                });
                                return text;
                            }
                        }
                    },
                    {
                        name: 'amount',
                        "render": function(data, type, row, meta) {
                            if (row.extras.length > 0) {
                                var text = "";
                                $.each(row.extras, function(key, value) {
                                    text +=
                                        `<div>₹${value.amount}</div>`;
                                });
                                return text;
                            }
                        }
                    },
                ],
                'rowCallback': function(row, data, index) {
                    $('td', row).css('white-space', 'nowrap');
                },
                'columnDefs': [{
                    "targets": [],
                    "orderable": false
                }],
                "language": {
                    "paginate": {
                        "previous": '&nbsp;',
                        "next": '&nbsp;'
                    }
                },
            });
        }

        function getfilterdataVendor() {

            var table = $('#myDatatableVendor').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('trip.details.vendor') !!}",
                    "type": 'GET',
                    "data": {
                        "trip_id": trip_id,
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        name: 'date',
                        "render": function(data, type, row, meta) {
                            return row.created;
                        }
                    }, {
                        name: 'vendor',
                        "render": function(data, type, row, meta) {
                            return row.vendor_name;
                        }
                    }, {
                        name: 'service_name',
                        "render": function(data, type, row, meta) {
                            return row.service_name;
                        }
                    },
                    {
                        name: 'service_id',
                        "render": function(data, type, row, meta) {
                            return row.vendorServiceName;
                        }
                    },
                    {
                        name: 'total',
                        "render": function(data, type, row, meta) {
                            var total = parseInt(row.total_amount);
                            return "₹" + total;
                        }
                    },
                    {
                        name: 'paid',
                        "render": function(data, type, row, meta) {
                            var total = parseInt(row.paid_amount);
                            return "₹" + total;
                        }
                    },
                    {
                        name: 'paid_amount',
                        "render": function(data, type, row, meta) {
                            var remain = parseInt(row.total_amount) - parseInt(row.paid_amount);
                            return "₹" + remain;
                        }
                    }, {
                        name: 'docx',
                        "render": function(data, type, row, meta) {
                            if (row.docx) {
                                return `<a href="${row.docx}" target="_blank">View</a>`;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        name: 'comment',
                        "render": function(data, type, row, meta) {
                            return row.comment;
                        }
                    },

                    {
                        name: 'Action',
                        "render": function(data, type, row, meta) {
                            var id = row.id;

                            var routeDlt = "{{ route('trip.details.delete', ['id' => 'rowID']) }}";
                            routeDlt = routeDlt.replace('rowID', id);

                            var routeView = "{{ route('trip.details.view', ['id' => 'rowID']) }}";
                            routeView = routeView.replace('rowID', id);

                            var text =
                                `<div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" style="">
                                <a class="dropdown-item waves-effect" href="${routeView}"><i class="mdi mdi-eye-outline me-1"></i> View</a>`;
                            if (row.editable) {
                                text +=
                                    `<a class="dropdown-item waves-effect" href="javaScript:void(0)" onclick="makeVendorPayment(${row.id}, ${row.vendor_id})"><i class="mdi mdi-currency-inr me-1"></i> Pay </a>
                                <a class="dropdown-item waves-effect" href="javaScript:void(0)" onclick="editExpense(${row.id})"><i class="mdi mdi-pencil-outline me-1"></i> Edit </a>
                                <a class="dropdown-item waves-effect" data-bs-toggle="modal" onclick="deleteModal('${routeDlt}')"
                                            data-bs-target="#deleteModal" href="javaScript:void(0)"><i class="mdi mdi-trash-can-outline me-1"></i> Delete</a>`;

                            }
                            text += `</div></div>`;
                            return text;
                        }
                    },
                ],
                'rowCallback': function(row, data, index) {
                    $('td', row).css('white-space', 'nowrap');
                },
                'columnDefs': [{
                    "targets": [],
                    "orderable": false
                }],
                "language": {
                    "paginate": {
                        "previous": '&nbsp;',
                        "next": '&nbsp;'
                    }
                },
            });
        }

        function getfilterdataStationary() {

            var table = $('#myDatatableStationary').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('trip.details.stationary') !!}",
                    "type": 'GET',
                    "data": {
                        "trip_id": trip_id,
                    },
                   "error": function(xhr, error, thrown) {
                        console.log("Ajax Error:", xhr.responseText);
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        name: 'trip_name',
                        "render": function(data, type, row, meta) {
                            return row.trip_name;
                        }
                    }, {
                        name: 'date',
                        "render": function(data, type, row, meta) {
                            return row.created;
                        }
                    }, {
                        name: 'customers',
                        "render": function(data, type, row, meta) {
                            return row.customers;
                        }
                    }, {
                        name: 'stationary_name',
                        "render": function(data, type, row, meta) {
                            return row.stationary_name;
                        }
                    },
                    {
                        name: 'qty',
                        "render": function(data, type, row, meta) {
                            return row.qty;
                        }
                    }
                ],
                'rowCallback': function(row, data, index) {
                    $('td', row).css('white-space', 'nowrap');
                },
                'columnDefs': [{
                    "targets": [],
                    "orderable": false
                }],
                "language": {
                    "paginate": {
                        "previous": '&nbsp;',
                        "next": '&nbsp;'
                    }
                },
            });
        }

        function getfilterdataMerchandise() {

            var table = $('#myDatatableMerchandise').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('trip.details.merchandise') !!}",
                    "type": 'GET',
                    "data": {
                        "trip_id": trip_id,
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        name: 'trip',
                        "render": function(data, type, row, meta) {
                            return row.trip_name;
                        }
                    }, {
                        name: 'date',
                        "render": function(data, type, row, meta) {
                            return row.created;
                        }
                    }, {
                        name: 'customers',
                        "render": function(data, type, row, meta) {
                            return row.customers;
                        }
                    },
                    {
                        name: 'merchandise_name',
                        "render": function(data, type, row, meta) {
                            return row.merchandise_name;
                        }
                    },
                    {
                        name: 'size',
                        "render": function(data, type, row, meta) {
                            return row.size;
                        }
                    },
                    {
                        name: 'qty',
                        "render": function(data, type, row, meta) {
                            return row.qty;
                        }
                    },
                    {
                        name: 'gender',
                        "render": function(data, type, row, meta) {
                            return row.gender;
                        }
                    }
                ],
                'rowCallback': function(row, data, index) {
                    $('td', row).css('white-space', 'nowrap');
                },
                'columnDefs': [{
                    "targets": [],
                    "orderable": false
                }],
                "language": {
                    "paginate": {
                        "previous": '&nbsp;',
                        "next": '&nbsp;'
                    }
                },
            });
        }

        function getfilterdataAgent() {

            var table = $('#myDatatableAgent').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('trip.details.agents') !!}",
                    "type": 'GET',
                    "data": {
                        "trip_id": trip_id,
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        name: 'customer',
                        "render": function(data, type, row, meta) {
                            var data = row.customers;
                            if (data.length > 0) {
                                var text = `<div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                        <div>${data[0].name}</div> <span class="mdi mdi-chevron-down"></span>
                    </button>
                    <div class="dropdown-menu" style="">`;
                                $.each(data, function(key, value) {
                                    text +=
                                        `<div class="dropdown-item waves-effect text-center"><div>${value.name}</div><div>${value.email}</div></div>`;
                                });
                                text += `</div>
                    
                </div>`;
                            } else {
                                text = '';
                            }
                            return text;
                        }
                    },
                    {
                        name: 'agent',
                        "render": function(data, type, row, meta) {
                            var data = row.sub_lead_source;
                            return data;
                        }
                    },
                ],
                'rowCallback': function(row, data, index) {
                    $('td', row).css('white-space', 'nowrap');
                },
                'columnDefs': [{
                    "targets": [],
                    "orderable": false
                }],
                "language": {
                    "paginate": {
                        "previous": '&nbsp;',
                        "next": '&nbsp;'
                    }
                },
            });
        }

        function getfilterdataRec() {

            var table = $('#myDatatableRec').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('trip.details.receivable') !!}",
                    "type": 'GET',
                    "data": {
                        "trip_id": trip_id,
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        name: 'customers',
                        "render": function(data, type, row, meta) {
                            return row.customers;
                        }
                    },
                    {
                        name: 'total_rec',
                        "render": function(data, type, row, meta) {
                            return `<span data-bs-toggle="tooltip" data-bs-placement="right" title="${row.tooltip}">₹` +
                                row.total_rec + `<img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg"
                                                                alt="" class="ps-2"></span>`;
                        }
                    },
                    {
                        name: 'total_recieved',
                        "render": function(data, type, row, meta) {
                            return "₹" + row.total_recieved;
                        }
                    },
                    {
                        name: 'total_due',
                        "render": function(data, type, row, meta) {
                            return "₹" + row.total_due;
                        }
                    },
                    {
                        name: 'email',
                        "render": function(data, type, row, meta) {
                            var b_id = row.id;
                            var t_id = row.trip_id;
                            var c_id = row.customer_id;
                            var sendEmail =
                                "{{ route('trip.details.sendEmail', ['b_id' => 'rowbID', 't_id' => 'rowtID', 'c_id' => 'rowcID']) }}";
                            sendEmail = sendEmail.replace('rowbID', b_id);
                            sendEmail = sendEmail.replace('rowtID', t_id);
                            sendEmail = sendEmail.replace('rowcID', c_id);

                            return `<div class="text-center"><a href="${sendEmail}" class="btn btn-warning btn-sm">Send Email</a><br><span>${row.last_mail  }</span></div>`;
                        }
                    },
                    {
                        name: 'view',
                        "render": function(data, type, row, meta) {
                            var id = row.token;
                            var tripView = "{{ route('booking.view', ['token' => 'rowID']) }}";
                            tripView = tripView.replace('rowID', id);

                            return `<a href="${tripView}" class="btn btn-info btn-sm">View</a>`;
                        }
                    },
                ],
                'rowCallback': function(row, data, index) {
                    $('td', row).css('white-space', 'nowrap');
                    // tooltip init here
                    $(function() {
                        $('[data-bs-toggle="tooltip"]').tooltip();
                    });
                    // tooltip init here
                },
                'columnDefs': [{
                    "targets": [],
                    "orderable": false
                }],
                "language": {
                    "paginate": {
                        "previous": '&nbsp;',
                        "next": '&nbsp;'
                    }
                },
            });


        }
    </script>

    <script>
        function deleteModal(route) {
            $('#deleteBtn').attr('href', route);
        }

        function makeVendorPayment(id, v_id) {
            $("#expense_id").val(id);
            $("#makeVendorPayment").modal('show');
        }

        function editExpense(id) {
            document.querySelector(".expense-form").reset();
            $("#expense_row_id").val(id);
            $("#editExpense").modal('show');

            $.ajax({
                url: "{{ route('trip.getExpenseById') }}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id
                },
                success: function(res) {
                    if (res) {
                        $("select[name='extra_service_id']").val(res.extra_service_id);
                        $("select[name='extra_service_id']").trigger('onchange');
                        setTimeout(() => {
                            $("select[name='vendor_id']").val(res.vendor_id);
                            $("select[name='vendor_id']").trigger('onchange');
                        }, 500);
                        setTimeout(() => {
                            $("select[name='vendor_service_id']").val(res.vendor_service_id);
                        }, 1000);
                        $("input[name='total_amount']").val(res.total_amount);
                        $("input[name='comment']").val(res.comment);
                        $(".docx_link").hide();
                        if (res.docx) {
                            $(".docx_link").show();
                            $(".docx_link").attr('href', res.docx);
                        }
                    }
                }
            });
        }
    </script>

    <script>
        function changeTripStatus() {
            $.ajax({
                url: "{{ route('trip.change-status') }}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': "{{ request()->id }}"
                },
                success: function(res) {
                    window.location.href = window.location.href;
                }
            });
        }
    </script>

    {{-- add expense payment condition --}}
    <script>
        $("#payment_status_type").hide();

        function paymentStatus(val) {
            $("select[name='payment_status_type']").val('');
            if (val == 'Paid') {
                $("#payment_status_type").show();
            } else {
                $("#payment_status_type").hide();
                $("#expense_paid_details").hide();
                $("#addRemoveHandle").hide();
            }
        }

        $("#expense_paid_details").hide();
        $("#addRemoveHandle").hide();

        function paymentStatusType(val) {
            if (val == 'Fully Paid') {
                $("input[name='expense_paid_amount[]']").val($("#exp_total_amount").val());
                $("input[name='expense_paid_amount[]']").prop('readonly', true);
                $("#expense_paid_details").show();
                $("#addRemoveHandle").hide();
            } else if (val == 'Partial Paid') {
                $("#expense_paid_details").hide();
                $("#expense_paid_details").show();
                $("input[name='expense_paid_amount[]']").prop('readonly', false);
                $("#addRemoveHandle").show();
            } else {
                $("#expense_paid_details").hide();
                $("#addRemoveHandle").hide();
            }
        }
    </script>

    {{-- dynamic field --}}
    <script>
        function addNewField() {
            var existingField = document.querySelector('.field');
            var newField = existingField.cloneNode(true);

            document.getElementById("fieldsContainer").appendChild(newField);
            $("#extraServces").show();
        }

        function removeSingleField() {
            var fieldsContainer = document.getElementById("fieldsContainer");
            var rows = fieldsContainer.getElementsByClassName("row");

            if (rows.length > 1) {
                fieldsContainer.removeChild(rows[rows.length - 1]);
            } else {
                Toast.fire({
                    icon: "warning",
                    title: "No more fields to remove!"
                });
            }
        }
    </script>

    {{-- validate form of add expense --}}
    <script>
        $(document).ready(function() {
            $('.expense-form').on('submit', function(e) {
                let isValid = true;

                var payment_status_type = $("select[name='payment_status_type']").val();

                if ($("select[name='payment_status']").val() == 'Paid' && payment_status_type == "") {
                    isValid = false;
                    $("select[name='payment_status_type']").css('border', '1px solid red');
                } else {
                    let isValid = true;
                    $("select[name='payment_status_type']").css('border', '');
                }

                if (payment_status_type == 'Fully Paid' || payment_status_type == 'Partial Paid') {
                    var totalAmount = 0;
                    $("input[name='expense_paid_amount[]']").each(function() {
                        totalAmount += parseInt($(this).val());
                        if ($(this).val() === '') {
                            isValid = false;
                            $(this).css('border', '1px solid red'); // Highlight empty field
                        } else {
                            $(this).css('border', ''); // Reset border if the field is filled
                        }
                    });

                    $("select[name='expense_paid_payment_mode[]']").each(function() {
                        if ($(this).val() === '') {
                            isValid = false;
                            $(this).css('border', '1px solid red'); // Highlight empty field
                        } else {
                            $(this).css('border', ''); // Reset border if the field is filled
                        }
                    });

                    $("input[name='expense_paid_date[]']").each(function() {
                        if ($(this).val() === '') {
                            isValid = false;
                            $(this).css('border', '1px solid red'); // Highlight empty field
                        } else {
                            $(this).css('border', ''); // Reset border if the field is filled
                        }
                    });
                }

                if (!isValid) {
                    e.preventDefault(); // Prevent form submission
                    Toast.fire({
                        icon: "warning",
                        title: "Fill the Required Fileds !"
                    });
                }
            });
        });
    </script>
   
    <script>
        $(document).ready(function() {
            $('#carbon-import-form').on('submit', function(e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    // Hide previous errors
                $('#carbon-import-errors').addClass('d-none').html('');
                $.ajax({
                        url: "{{ route('trip.details.carbonInfoImport')}}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            if(res.status== true)
                            {
                                $('#basicModal').modal('hide');
                               
                                Toast.fire({
                                    icon: "warning",
                                    title: res.message,
                                    timer: 5000, // 2 seconds
                                    timerProgressBar: true,
                                    didClose: () => {
                                         location.reload();
                                       
                                    }
                                });
                            }
                        },
                        error: function(xhr) {
                            // Show validation errors
                            if(xhr.status === 404 && xhr.responseJSON && xhr.responseJSON.errors)
                            {
                                  Toast.fire({
                                    icon: "warning",
                                    title: xhr.responseJSON.errors
                                });
                            }
                           
                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                             
                                var errors = xhr.responseJSON.errors;
                               
                                // Display errors in a user-friendly way
                                var html = '<ul>';
                                $.each(errors, function(key, msgs) {
                                    $.each(msgs, function(i, msg) {
                                        html += '<li>' + msg + '</li>';
                                    });
                                });
                                html += '</ul>';
                                $('#carbon-import-errors').removeClass('d-none').html(html);
                            }
                             else {
                                // alert('Import failed: ' + (xhr.responseJSON?.message || 'Unknown error'));
                            }
                        }
                });
            });
        });
    </script>

<script>

    function editCarbonNeutralInfo(id) {
        const trip_id=id; 
        console.log(trip_id);
        $.ajax({
            url: "{{ route('trip.details.carboninfoData')}}",
            type: 'GET',
            data: { id: trip_id },
           // ...existing code...
            success: function(data) {
                const tbody = $("#carbonNeutralTable tbody");
                tbody.empty();
                const driveType = data.drive_tour_type;
                $('#type').text(driveType);
                const showCarName = (driveType !== "Fly & Drive Road Trip");
                console.log(showCarName);
                data.carboninfoData.forEach((item, index) => {
                    let row = `
                        <tr>  
                            <td contenteditable="true" class="editable d-none" data-name="id">${item.id ?? ''}</td>
                            <td contenteditable="true" class="editable d-none" data-name="trip_id">${item.trip_id ?? ''}</td>
                            <td class="" data-name="trip_name">${item.trip_name ?? ''}</td>
                            <td class="" data-name="customer_first_name">${item.customer_first_name ?? ''}</td>
                            <td class="" data-name="customer_last_name">${item.customer_last_name ?? ''}</td>
                            <td class="" data-name="customer_email">${item.customer_email ?? ''}</td>
                            <td class="" data-name="customer_email">${item.customer_phone ?? ''}</td>
                            <td contenteditable="true" class="editable" data-name="no_of_trees">${item.no_of_trees ?? ''}</td>
                            <td contenteditable="true" class="editable" data-name="total_distance">${item.total_distance ?? ''}</td>
                            <td contenteditable="true" class="editable" data-name="carbon_emission">${item.carbon_emission ?? ''}</td>
                            <td contenteditable="true" class="editable" data-name="car_sequence_number">${item.car_sequence_number ?? ''}</td>
                    `;
                    if (showCarName) {
                        row += `<td contenteditable="true" class="editable" data-name="car_name">${item.car_name ?? ''}</td>`;
                    }
                    row += `</tr>`;
                    tbody.append(row);
                });
                if (showCarName) {
                  
                    $('#carbonNeutralTable thead tr th[data-col="car_name"]').show();
                } else {
                  
                    $('#carbonNeutralTable thead tr th[data-col="car_name"]').hide();
                }

                $('#carbonNeutralModal').modal('show');
            }
// ...existing code...
        });
    }
    //carbon neutral new addtion
    function addEditableRow() {
    
        const trip_id = "{{ request()->id }}";
        let existingCustomers = [];
        $("#carbonNeutralTable tbody tr").each(function() {
            const email = $(this).find('td[data-name="customer_email"]').text().trim();
            if(email) existingCustomers.push(email);
        });

      
        $.ajax({
            url: "{{ route('trip.details.get-new-carbon-customers') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                trip_id: trip_id,
                existing_customers: existingCustomers
            },
            success: function(res) {
                console.log(res);
                if(res.length > 0) {
                    alert(res.length);
                    console.log(res);
                    res.forEach(function(cust) {

                        alert(cust);
                        const row = `
                            <tr>
                                <td contenteditable="true" class="editable d-none" data-name="id">${cust.id ?? ''}</td>
                                <td contenteditable="true" class="editable d-none" data-name="trip_id">${cust.trip_id ?? ''}</td>
                                <td class="" data-name="trip_name">${cust.trip_name}</td>
                                <td data-name="customer_first_name">${cust.customer_first_name ?? ''}</td>
                                <td data-name="customer_last_name">${cust.customer_last_name ?? ''}</td>
                                <td data-name="customer_email">${cust.customer_email ?? ''}</td>
                                <td contenteditable="true" class="editable" data-name="no_of_trees"></td>
                                <td contenteditable="true" class="editable" data-name="total_distance"></td>
                                <td contenteditable="true" class="editable" data-name="carbon_emission"></td>
                                <td contenteditable="true" class="editable" data-name="car_sequence_number"></td>
                                <td contenteditable="true" class="editable" data-name="car_name"></td>
                                <td><button onclick="removeRow(this)">Delete</button></td>
                            </tr>
                        `;
                        $("#carbonNeutralTable tbody").append(row);
                        $('#carbonNeutralModal').modal('show');

                    });
                } else {
                  
                    Toast.fire({
                        icon: "warning",
                        title: "NO Pending Customer To Add",
                        timer: 5000,
                        timerProgressBar: true,
                    });
                
                }
            }
        });
    }
    function removeRow(button) {
        $(button).closest("tr").remove();
    }

    $(document).on('input', '#carbonNeutralTable td.editable', function() {
        $(this).closest('tr').addClass('edited');
    });

    function saveEditableData() {
        const rows = [];
        $("#carbonNeutralTable tbody tr.edited").each(function () {
            const row = {};
            $(this).find("td").each(function () {
                const key = $(this).data("name");
                const value = $(this).text().trim();
                row[key] = value;
            });
            rows.push(row);
        });

        if(rows.length === 0) {
            Toast.fire({
                icon: "warning",
                title: "No changes to save!",
                timer: 3000,
                timerProgressBar: true,
            });
            return;
        }

        $.ajax({
            url: "{{ route('trip.details.update-carbon-neutral-data')}}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                data: rows
            },
            success: function(response) {
                if(response.status==true)
                {
                    Toast.fire({
                        icon: "success",
                        title: response.message,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                   
                }
            
                $('#carbonNeutralModal').modal('show');
            },
            error: function() {
                Toast.fire({
                        icon: "error",
                        title: "Something went wrong while saving.",
                        timer: 3000,
                        timerProgressBar: true,
                });
            }
        });
    }
</script>

@endsection
