@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="p-3">
                {{-- filter --}}
                <div class="row">

                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select id="customer_name" class="select2 form-select form-select-lg">
                                <option value=" ">Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->first_name . ' ' . $customer->last_name }}</option>
                                @endforeach
                            </select>
                            <label for="basic-default-fullname">Customer</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select id="trip_type" class="form-control">
                                <option value="">Select Trip Type</option>
                                <option value="Fixed Departure">Fixed Departure</option>
                                <option value="Tailor Made">Tailor Made</option>
                            </select>
                            <label for="basic-default-fullname">Trip Type</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select id="trip_id" class="select2 form-select form-select-lg">
                                <option value=" ">Select Trip</option>
                                @foreach ($trips as $trip)
                                    <option value="{{ $trip->id }}">{{ $trip->name }}</option>
                                @endforeach
                            </select>
                            <label for="basic-default-fullname">Trip Name</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select id="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="Completed">Completed</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Correction">Correction</option>
                            </select>
                            <label for="basic-default-fullname">Status</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select id="admin_id" class="form-control">
                                <option value="">Spoc</option>
                                @foreach ($admins as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                @endforeach
                            </select>
                            <label for="basic-default-fullname">Spoc</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="date" id="date" class="form-control" id="basic-default-fullname" />
                            <label for="basic-default-fullname">Date</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select id="invoice_status" class="form-control">
                                <option value="">Invoice Status</option>
                                <option value="Sent">Sent</option>
                                <option value="Not Sent">Pending</option>
                            </select>
                            <label for="basic-default-fullname">Invoice Status</label>
                        </div>
                    </div>

                    <div class="col-md-12 mb-2">
                        <div class="text-center">
                            <a href="" class="btn btn-secondary">Reset</a>
                            <button onclick="filter()" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                    <hr>

                    {{-- filter --}}

                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <div class="dt-buttons btn-group flex-wrap">

                            <button class="btn btn-secondary  btn-label-primary me-2" data-bs-toggle="modal"
                                data-bs-target="#basicModal">
                                <i class="mdi mdi-tray-arrow-down me-sm-1"></i> Import
                            </button>

                            <a href="{{ route('booking.export') }}"
                                class="btn btn-secondary buttons-collection btn-label-primary me-2" tabindex="0"
                                aria-controls="DataTables_Table_0" type="button" aria-haspopup="dialog"
                                aria-expanded="false"><span><i class="mdi mdi-export-variant me-sm-1"></i> <span
                                        class="d-none d-sm-inline-block">Export</span></span></span>
                            </a>

                            <a class="btn btn-secondary btn-primary text-white" href="{{ route('booking.new-trip') }}"
                                tabindex="0"><span><i class="mdi mdi-plus me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">Create booking</span></span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <div class="nav-align-top">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="true">
                                    All Booking ({{ $allBookingCount }})
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-completed" aria-controls="navs-top-completed"
                                    aria-selected="false">
                                    Completed ({{ $completedBookingCount }})
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-cancelled" aria-controls="navs-top-cancelled"
                                    aria-selected="false">
                                    Cancelled ({{ $cancelledBookingCount }})
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-correction" aria-controls="navs-top-correction"
                                    aria-selected="false">
                                    Correction ({{ $correctionBookingCount }})
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-draft" aria-controls="navs-top-draft"
                                    aria-selected="false">
                                    Draft ({{ $draftBookingCount }})
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="navs-top-home" role="tabpanel">
                            <div class="card-datatable table-responsive pt-0">

                                <table id="myDatatable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Pax</th>
                                            <th>Trip Name</th>
                                            <th>Relation Manager</th>
                                            <th>Trip Type</th>
                                            <th>Trip Cost</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th>Booking Status</th>
                                            <th>Invoice</th>
                                            <th>Invoice Sent Date</th>
                                            <th>Spoc</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-top-completed" role="tabpanel">
                            <div class="card-datatable table-responsive pt-0">
                                <table id="myDatatableCompleted" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Pax</th>
                                            <th>Trip Name</th>
                                            <th>Relation Manager</th>
                                            <th>Trip Type</th>
                                            <th>Trip Cost</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th>Booking Status</th>
                                            <th>Invoice</th>
                                            <th>Invoice Sent Date</th>
                                            <th>Spoc</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-top-cancelled" role="tabpanel">
                            <div class="card-datatable table-responsive pt-0">

                                <table id="myDatatableCancelled" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Pax</th>
                                            <th>Trip Name</th>
                                            <th>Relation Manager</th>
                                            <th>Trip Type</th>
                                            <th>Trip Cost</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th>Booking Status</th>
                                            <th>Invoice</th>
                                            <th>Invoice Sent Date</th>
                                            <th>Spoc</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-top-correction" role="tabpanel">
                            <div class="card-datatable table-responsive pt-0">

                                <table id="myDatatableCorrection" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Pax</th>
                                            <th>Trip Name</th>
                                            <th>Relation Manager</th>
                                            <th>Trip Type</th>
                                            <th>Trip Cost</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th>Booking Status</th>
                                            <th>Spoc</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-top-draft" role="tabpanel">
                            <div class="card-datatable table-responsive pt-0">

                                <table id="myDatatableDraft" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Trip Name</th>
                                            <th>Relation Manager</th>
                                            <th>Trip Type</th>
                                            <th>Trip Cost</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th>Booking Status</th>
                                            <th>Invoice</th>
                                            <th>Invoice Sent Date</th>
                                            <th>Spoc</th>
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
        <!--/ DataTable with Buttons -->
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

        <!-- upload booking -->
        <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1">Import Bookings List</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('booking.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-4 mt-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="file" name="file" class="form-control" placeholder="File"
                                            accept=".xlsx" required />
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
    @endsection

    @section('script')
        <script>
            $('th').css('white-space', 'nowrap');

            $(document).ready(function() {
                getfilterdata();
                getfilterdataCompleted();
                getfilterdataCancelled();
                getfilterdataCorrection();
                getfilterdataDraft();
            });

            function filter() {
                getfilterdata();
                getfilterdataCompleted();
                getfilterdataCancelled();
                getfilterdataCorrection();
                getfilterdataDraft();
            }

            function getfilterdata() {
                var customer_name = $("#customer_name").val();
                  
                var trip_type = $("#trip_type").val();
                var trip_id = $("#trip_id").val();
                var status = $("#status").val();
                var admin_id = $("#admin_id").val();
              
              
                var date = $("#date").val();
                var invoice_status = $("#invoice_status").val();

                var table = $('#myDatatable').DataTable({
                    "lengthMenu": [
                        [20, 50, 100],
                        [20, 50, 100]
                    ],
                    "order": [],
                    "processing": true,
                    "destroy": true,
                    "ajax": {
                        "url": "{!! route('booking.get') !!}",
                        "type": 'GET',
                        "data": {
                            "type": "all",
                            "customer_name": customer_name,
                            "trip_type": trip_type,
                            "trip_id": trip_id,
                            "status": status,
                            "admin_id": admin_id,
                            "date": date,
                            "invoice_status": invoice_status,
                        },
                       "error": function(xhr, error, thrown) {
                            console.log(xhr.responseJSON.error);
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                Toast.fire({
                                    icon: "error",
                                    title: "Something went wrong! Please try after Some time"
                                });
                            } else {
                                 Toast.fire({
                                    icon: "error",
                                    title: "Something went wrong! Please try after Some time"
                                });
                               
                            }
                        } 
                    },
                    "serverSide": true,
                    "deferRender": true,
                    "columns": [{
                            name: 'Action',
                            "render": function(data, type, row, meta) {
                                var id = row.token;
                                var routeEdit = "{{ route('booking.new-trip', ['token' => 'rowID']) }}";
                                routeEdit = routeEdit.replace('rowID', id);

                                var routeView = "{{ route('booking.view', ['token' => 'rowID']) }}";
                                routeView = routeView.replace('rowID', id);

                                var routeActivity = "{{ route('booking.activity', ['id' => 'rowID']) }}";
                                routeActivity = routeActivity.replace('rowID', row.id);

                                var text = `<div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" style="">
                                <a class="dropdown-item waves-effect" href="${routeActivity}"><i class="mdi mdi-book-outline me-1"></i> Activity Log</a>
                                <a class="dropdown-item waves-effect" href="${routeView}"><i class="mdi mdi-eye-outline me-1"></i> View</a>
                                <a class="dropdown-item waves-effect" href="${routeEdit}"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                                </div>
                                
                            </div>`;
                                return text;
                            }
                        }, {
                            data: 'created',
                            name: 'created'
                        }, {
                            name: 'customer',
                            "render": function(data, type, row, meta) {
                                var id = row.token;
                                var routeView = "{{ route('booking.view', ['token' => 'rowID']) }}";
                                routeView = routeView.replace('rowID', id);

                                var data = row.customers;
                                if (data.length > 0) {
                                   
                                    var text =
                                        `<a class="dropdown-item waves-effect text-primary" href="${routeView}">${data[0].name}</a>`;
                                } else {
                                    text = '';
                                }
                                return text;
                            }
                        }, {
                            data: 'pax',
                            name: 'pax'
                        },
                        {
                            data: 'trip_name',
                            name: 'trip_name'
                        },
                         {
                            data: 'relation_manager_names',
                            name: 'relation_manager_names',
                            render: function(data, type, row, meta) {
                                if (data && data.trim() !== "" && data.trim().toUpperCase() !== "N/A") {
                                    return "<span>" + data + "</span>";
                                } else {
                                    return "<span class='badge bg-danger'>No</span>";
                                }
                            }
                        },
                        {
                            data: 'trip_type',
                            name: 'trip_type'
                        },
                        {
                            
                            data: 'payable_amt',
                            name: 'payable_amt',
                            "render": function(data, type, row, meta) {
                                return `₹${data}`;
                            }
                        },
                        {
                            data: 'payment_amt',
                            name: 'payment_amt',
                            "render": function(data, type, row, meta) {
                                var text = `₹${row.total_payment_amount_received}`;
                                return text;
                            }
                        },
                        {
                            data: 'balance',
                            name: 'balance',
                            "render": function(data, type, row, meta) {
                                var text = `₹${row.balance}`;
                                return text;
                            }
                        },
                        {
                           
                            name: 'trip_status',
                            "render": function(data, type, row, meta) {
                                var stat = row.trip_status;
                                if (stat == "Cancelled") {
                                    var text = "<span class='badge bg-danger'>Cancelled</span>"
                                } else if (stat == "Completed") {
                                    var text = "<span class='badge bg-success'>Completed</span>"
                                } else if (stat == "Confirmed") {
                                    var text = "<span class='badge bg-info'>Confirmed</span>"
                                } else if (stat == "Draft") {
                                    var text = "<span class='badge bg-secondary'>Draft</span>"
                                } else if (stat == "Correction") {
                                    var text = "<span class='badge bg-warning'>Correction Required</span>"
                                }

                                return text;
                            }
                        },
                        {
                            data: 'invoice_status',
                            name: 'invoice_status',
                            "render": function(data, type, row, meta) {
                                var stat = row.invoice_status;
                                if (stat == "NA") {
                                    var text = "<span class='badge bg-warning'>NA</span>"
                                } else if (stat == "Sent") {
                                    var text = "<span class='badge bg-success'>Sent</span>"
                                } else if (stat == "Pending") {
                                    var text = "<span class='badge bg-danger'>Pending</span>"
                                }
                                return text;
                            }
                        },
                        {
                            data: 'invoice_sent_date',
                            name: 'invoice_sent_date'
                        },
                        {
                            data: 'admin_id',
                            name: 'admin_id'
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

            function getfilterdataCompleted() {
                // var sdatefrom = $("#sdatefrom").val();
                var customer_name = $("#customer_name").val();
                var trip_type = $("#trip_type").val();
                var trip_id = $("#trip_id").val();
                var status = $("#status").val();
                var admin_id = $("#admin_id").val();
                var date = $("#date").val();
                var invoice_status = $("#invoice_status").val();

                var table = $('#myDatatableCompleted').DataTable({
                    "lengthMenu": [
                        [20, 50, 100],
                        [20, 50, 100]
                    ],
                    "order": [],
                    "processing": true,
                    "searching": false,
                    "destroy": true,
                    "ajax": {
                        "url": "{!! route('booking.get') !!}",
                        "type": 'GET',
                        "data": {
                            "type": "completed",
                            "customer_name": customer_name,
                            "trip_type": trip_type,
                            "trip_id": trip_id,
                            "status": status,
                            "admin_id": admin_id,
                            "date": date,
                            "invoice_status": invoice_status,
                        },
                         "error": function(xhr, error, thrown) {
                             console.log(xhr.responseJSON);
                            console.log(xhr.responseJSON.error);
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                  alert(xhr.responseJSON.error);
                                console.log(xhr.responseJSON);
                                Toast.fire({
                                    icon: "erro",
                                    title: "Something went wrong! Please try after Some time"
                                });
                            } else {
                                 Toast.fire({
                                    icon: "error",
                                    title: "Something went wrong! Please try after Some time"
                                });
                               
                            }
                        } 
                    },
                    "serverSide": true,
                    "deferRender": true,
                    "columns": [{
                            name: 'Action',
                            "render": function(data, type, row, meta) {
                                var id = row.token;
                                var routeEdit = "{{ route('booking.new-trip', ['token' => 'rowID']) }}";
                                routeEdit = routeEdit.replace('rowID', id);

                                // var routeDlt = "{{ route('booking.delete', ['id' => 'rowID']) }}";
                                // routeDlt = routeDlt.replace('rowID', id);

                                var routeView = "{{ route('booking.view', ['token' => 'rowID']) }}";
                                routeView = routeView.replace('rowID', id);

                                var routeActivity = "{{ route('booking.activity', ['id' => 'rowID']) }}";
                                routeActivity = routeActivity.replace('rowID', row.id);

                                var text = `<div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" style="">
                                <a class="dropdown-item waves-effect" href="${routeActivity}"><i class="mdi mdi-book-outline me-1"></i> Activity Log</a>
                                <a class="dropdown-item waves-effect" href="${routeView}"><i class="mdi mdi-eye-outline me-1"></i> View</a>
                                <a class="dropdown-item waves-effect" href="${routeEdit}"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                                </div>
                                
                            </div>`;
                                return text;
                            }
                        }, {
                            data: 'created',
                            name: 'created'
                        }, {
                            name: 'customer',
                            "render": function(data, type, row, meta) {
                                var id = row.token;
                                var routeView = "{{ route('booking.view', ['token' => 'rowID']) }}";
                                routeView = routeView.replace('rowID', id);

                                var data = row.customers;
                                if (data.length > 0) {
                                    var text = `<a href="${routeView}">${data[0].name}</a>`;
                                } else {
                                    text = '';
                                }
                                return text;
                            }
                        }, {
                            data: 'pax',
                            name: 'pax'
                        },
                        {
                            data: 'trip_name',
                            name: 'trip_name'
                        },
                         {
                            data: 'relation_manager_names',
                            name: 'relation_manager_names',
                            render: function(data, type, row, meta) {
                                if (data && data.trim() !== "" && data.trim().toUpperCase() !== "N/A") {
                                    return "<span>" + data + "</span>";
                                } else {
                                    return "<span class='badge bg-danger'>No</span>";
                                }
                            }
                        },
                        {
                            data: 'trip_type',
                            name: 'trip_type'
                        },
                        {
                            name: 'payable_amt',
                            "render": function(data, type, row, meta) {
                                var text = `₹${row.payable_amt}`;
                                return text;
                            }
                        },
                        {
                            name: 'payment_amt',
                            "render": function(data, type, row, meta) {
                                var text = `₹${row.total_payment_amount_received}`;
                                return text;
                            }
                        },
                        {
                            name: 'balance',
                            "render": function(data, type, row, meta) {
                                var text = `₹${row.balance}`;
                                return text;
                            }
                        },
                        {
                            name: 'trip_status',
                            "render": function(data, type, row, meta) {
                                var stat = row.trip_status;
                                if (stat == "Cancelled") {
                                    var text = "<span class='badge bg-danger'>Cancelled</span>"
                                } else if (stat == "Completed") {
                                    var text = "<span class='badge bg-success'>Completed</span>"
                                } else if (stat == "Confirmed") {
                                    var text = "<span class='badge bg-success'>Confirmed</span>"
                                } else if (stat == "Draft") {
                                    var text = "<span class='badge bg-secondary'>Draft</span>"
                                } else if (stat == "Correction") {
                                    var text = "<span class='badge bg-warning'>Correction Required</span>"
                                }

                                return text;
                            }
                        },
                        {
                            name: 'invoice_status',
                            "render": function(data, type, row, meta) {
                                var stat = row.invoice_status;
                                if (stat == "NA") {
                                    var text = "<span class='badge bg-warning'>NA</span>"
                                } else if (stat == "Sent") {
                                    var text = "<span class='badge bg-success'>Sent</span>"
                                } else if (stat == "Pending") {
                                    var text = "<span class='badge bg-success'>Pending</span>"
                                }
                                return text;
                            }
                        },
                        {
                            data: 'invoice_sent_date',
                            name: 'invoice_sent_date'
                        },
                        {
                            data: 'admin_id',
                            name: 'admin_id'
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

            function getfilterdataCancelled() {
                // var sdatefrom = $("#sdatefrom").val();
                var customer_name = $("#customer_name").val();
                var trip_type = $("#trip_type").val();
                var trip_id = $("#trip_id").val();
                var status = $("#status").val();
                var admin_id = $("#admin_id").val();
                var date = $("#date").val();
                var invoice_status = $("#invoice_status").val();

                var table = $('#myDatatableCancelled').DataTable({
                    "lengthMenu": [
                        [20, 50, 100],
                        [20, 50, 100]
                    ],
                    "order": [],
                    "processing": true,
                    "searching": false,
                    "destroy": true,
                    "ajax": {
                        "url": "{!! route('booking.get') !!}",
                        "type": 'GET',
                        "data": {
                            "type": "cancelled",
                            "customer_name": customer_name,
                            "trip_type": trip_type,
                            "trip_id": trip_id,
                            "status": status,
                            "admin_id": admin_id,
                            "date": date,
                            "invoice_status": invoice_status,
                        },
                         "error": function(xhr, error, thrown) {
                             console.log(xhr.responseJSON);
                            console.log(xhr.responseJSON.error);
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                  alert(xhr.responseJSON.error);
                                console.log(xhr.responseJSON);
                                Toast.fire({
                                    icon: "error",
                                    title: "Something went wrong! Please try after Some time"
                                });
                            } else {
                                 Toast.fire({
                                    icon: "error",
                                    title: "Something went wrong! Please try after Some time"
                                });
                               
                            }
                        } 
                    },
                    "serverSide": true,
                    "deferRender": true,
                    "columns": [{
                            name: 'Action',
                            "render": function(data, type, row, meta) {
                                var id = row.token;
                                var routeEdit = "{{ route('booking.new-trip', ['token' => 'rowID']) }}";
                                routeEdit = routeEdit.replace('rowID', id);

                                // var routeDlt = "{{ route('booking.delete', ['id' => 'rowID']) }}";
                                // routeDlt = routeDlt.replace('rowID', id);

                                var routeView = "{{ route('booking.view', ['token' => 'rowID']) }}";
                                routeView = routeView.replace('rowID', id);

                                var routeActivity = "{{ route('booking.activity', ['id' => 'rowID']) }}";
                                routeActivity = routeActivity.replace('rowID', row.id);

                                var text = `<div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" style="">
                                <a class="dropdown-item waves-effect" href="${routeActivity}"><i class="mdi mdi-book-outline me-1"></i> Activity Log</a>
                                <a class="dropdown-item waves-effect" href="${routeView}"><i class="mdi mdi-eye-outline me-1"></i> View</a>
                                <a class="dropdown-item waves-effect" href="${routeEdit}"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                                </div>
                                
                            </div>`;
                                return text;
                            }
                        }, {
                            data: 'created',
                            name: 'created'
                        }, {
                            name: 'customer',
                            "render": function(data, type, row, meta) {
                                var id = row.token;
                                var routeView = "{{ route('booking.view', ['token' => 'rowID']) }}";
                                routeView = routeView.replace('rowID', id);

                                var data = row.customers;
                                if (data.length > 0) {
                                    var text = `<a href="${routeView}">${data[0].name}</a>`;
                                } else {
                                    text = '';
                                }
                                return text;
                            }
                        }, {
                            data: 'pax',
                            name: 'pax'
                        },
                        
                        {
                            data: 'trip_name',
                            name: 'trip_name'
                        },
                          {
                            data: 'relation_manager_names',
                            name: 'relation_manager_names',
                            render: function(data, type, row, meta) {
                                if (data && data.trim() !== "" && data.trim().toUpperCase() !== "N/A") {
                                    return "<span>" + data + "</span>";
                                } else {
                                    return "<span class='badge bg-danger'>No</span>";
                                }
                            }
                        },
                        {
                            data: 'trip_type',
                            name: 'trip_type'
                        },
                        {
                            name: 'payable_amt',
                            "render": function(data, type, row, meta) {
                                var text = `₹${row.payable_amt}`;
                                return text;
                            }
                        },
                        {
                            name: ' payment_amt',
                            "render": function(data, type, row, meta) {
                                var text = `₹${row.total_payment_amount_received}`;
                                return text;
                            }
                        },
                        {
                            name: ' balance',
                            "render": function(data, type, row, meta) {
                                var text = `₹${row.balance}`;
                                return text;
                            }
                        },
                        {
                            name: 'trip_status',
                            "render": function(data, type, row, meta) {
                                var stat = row.trip_status;
                                if (stat == "Cancelled") {
                                    var text = "<span class='badge bg-danger'>Cancelled</span>"
                                } else if (stat == "Completed") {
                                    var text = "<span class='badge bg-success'>Completed</span>"
                                } else if (stat == "Confirmed") {
                                    var text = "<span class='badge bg-success'>Confirmed</span>"
                                } else if (stat == "Draft") {
                                    var text = "<span class='badge bg-secondary'>Draft</span>"
                                } else if (stat == "Correction") {
                                    var text = "<span class='badge bg-warning'>Correction Required</span>"
                                }

                                return text;
                            }
                        },
                        {
                            name: 'invoice_status',
                            "render": function(data, type, row, meta) {
                                var stat = row.invoice_status;
                                if (stat == "NA") {
                                    var text = "<span class='badge bg-warning'>NA</span>"
                                } else if (stat == "Sent") {
                                    var text = "<span class='badge bg-success'>Sent</span>"
                                } else if (stat == "Pending") {
                                    var text = "<span class='badge bg-danger'>Pending</span>"
                                }
                                return text;
                            }
                        },
                        {
                            data: 'invoice_sent_date',
                            name: 'invoice_sent_date'
                        },
                        {
                            data: 'admin_id',
                            name: 'admin_id'
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

            function getfilterdataCorrection() {
                // var sdatefrom = $("#sdatefrom").val();
                var customer_name = $("#customer_name").val();
                var trip_type = $("#trip_type").val();
                var trip_id = $("#trip_id").val();
                var status = $("#status").val();
                var admin_id = $("#admin_id").val();
                var date = $("#date").val();
                var invoice_status = $("#invoice_status").val();

                var table = $('#myDatatableCorrection').DataTable({
                    "lengthMenu": [
                        [20, 50, 100],
                        [20, 50, 100]
                    ],
                    "order": [],
                    "processing": true,
                    "searching": false,
                    "destroy": true,
                    "ajax": {
                        "url": "{!! route('booking.get') !!}",
                        "type": 'GET',
                        "data": {
                            "type": "correction",
                            "customer_name": customer_name,
                            "trip_type": trip_type,
                            "trip_id": trip_id,
                            "status": status,
                            "admin_id": admin_id,
                            "date": date,
                            "invoice_status": invoice_status,
                        },
                         "error": function(xhr, error, thrown) {
                            console.log(xhr.responseJSON);
                            console.log(xhr.responseJSON.error);
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                  alert(xhr.responseJSON.error);
                                console.log(xhr.responseJSON);
                                Toast.fire({
                                    icon: "erro",
                                    title: "Something went wrong! Please try after Some time"
                                });
                            } else {
                                 Toast.fire({
                                    icon: "error",
                                    title: "Something went wrong! Please try after Some time"
                                });
                               
                            }
                        } 
                    },
                    "serverSide": true,
                    "deferRender": true,
                    "columns": [{
                            name: 'Action',
                            "render": function(data, type, row, meta) {
                                var id = row.token;
                                var routeEdit = "{{ route('booking.new-trip', ['token' => 'rowID']) }}";
                                routeEdit = routeEdit.replace('rowID', id);

                                // var routeDlt = "{{ route('booking.delete', ['id' => 'rowID']) }}";
                                // routeDlt = routeDlt.replace('rowID', id);

                                var routeView = "{{ route('booking.view', ['token' => 'rowID']) }}";
                                routeView = routeView.replace('rowID', id);

                                var routeActivity = "{{ route('booking.activity', ['id' => 'rowID']) }}";
                                routeActivity = routeActivity.replace('rowID', row.id);

                                var text = `<div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" style="">
                                <a class="dropdown-item waves-effect" href="${routeActivity}"><i class="mdi mdi-book-outline me-1"></i> Activity Log</a>
                                <a class="dropdown-item waves-effect" href="${routeView}"><i class="mdi mdi-eye-outline me-1"></i> View</a>
                                <a class="dropdown-item waves-effect" href="${routeEdit}"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                                </div>
                                
                            </div>`;
                                return text;
                            }
                        }, {
                            data: 'created',
                            name: 'created'
                        }, {
                            name: 'customer',
                            "render": function(data, type, row, meta) {
                                var id = row.token;
                                var routeView = "{{ route('booking.view', ['token' => 'rowID']) }}";
                                routeView = routeView.replace('rowID', id);

                                var data = row.customers;
                                if (data.length > 0) {
                                    var text = `<a href="${routeView}">${data[0].name}</a>`;
                                } else {
                                    text = '';
                                }
                                return text;
                            }
                        },
                        {
                            data: 'pax',
                            name: 'pax'
                        },
                        {
                            data: 'trip_name',
                            name: 'trip_name'
                        },
                         {
                            data: 'relation_manager_names',
                            name: 'relation_manager_names',
                            render: function(data, type, row, meta) {
                                if (data && data.trim() !== "" && data.trim().toUpperCase() !== "N/A") {
                                    return "<span>" + data + "</span>";
                                } else {
                                    return "<span class='badge bg-danger'>No</span>";
                                }
                            }
                        },
                        {
                            data: 'trip_type',
                            name: 'trip_type'
                        },
                        {
                            name: 'payable_amt',
                            "render": function(data, type, row, meta) {
                                var text = `₹${row.payable_amt}`;
                                return text;
                            }
                        },
                        {
                            name: ' payment_amt',
                            "render": function(data, type, row, meta) {
                                var text = `₹${row.total_payment_amount_received}`;
                                return text;
                            }
                        },
                        {
                            name: ' balance',
                            "render": function(data, type, row, meta) {
                                var text = `₹${row.balance}`;
                                return text;
                            }
                        },
                        {
                            name: 'trip_status',
                            "render": function(data, type, row, meta) {
                                var stat = row.trip_status;
                                if (stat == "Cancelled") {
                                    var text = "<span class='badge bg-danger'>Cancelled</span>"
                                } else if (stat == "Completed") {
                                    var text = "<span class='badge bg-success'>Completed</span>"
                                } else if (stat == "Confirmed") {
                                    var text = "<span class='badge bg-success'>Confirmed</span>"
                                } else if (stat == "Draft") {
                                    var text = "<span class='badge bg-secondary'>Draft</span>"
                                } else if (stat == "Correction") {
                                    var text = "<span class='badge bg-warning'>Correction Required</span>"
                                }
                                return text;
                            }
                        },

                        {
                            data: 'admin_id',
                            name: 'admin_id'
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


            function getfilterdataDraft() {
               
                var customer_name = $("#customer_name").val();
                var trip_type = $("#trip_type").val();
                var trip_id = $("#trip_id").val();
                var status = $("#status").val();
                var admin_id = $("#admin_id").val();
                var date = $("#date").val();
                var invoice_status = $("#invoice_status").val();



                var table = $('#myDatatableDraft').DataTable({
                    "lengthMenu": [
                        [20, 50, 100],
                        [20, 50, 100]
                    ],
                    "order": [],
                    "processing": true,
                    "searching": false,
                    "destroy": true,
                    "ajax": {
                        "url": "{!! route('booking.get') !!}",
                        "type": 'GET',
                        "data": {
                            "type": "draft",
                            "customer_name": customer_name,
                            "trip_type": trip_type,
                            "trip_id": trip_id,
                            "status": status,
                            "admin_id": admin_id,
                            "date": date,
                            "invoice_status": invoice_status,
                        },
                         "error": function(xhr, error, thrown) {
                            console.log(xhr.responseJSON);
                            console.log(xhr.responseJSON.error);
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                alert(xhr.responseJSON.error);
                                console.log(xhr.responseJSON);
                                Toast.fire({
                                    icon: "erro",
                                    title: "Something went wrong! Please try after Some time"
                                });
                            } else {
                                 Toast.fire({
                                    icon: "error",
                                    title: "Something went wrong! Please try after Some time"
                                });
                               
                            }
                        } 
                    },
                    "serverSide": true,
                    "deferRender": true,
                    "columns": [{
                            name: 'Action',
                            "render": function(data, type, row, meta) {
                                var id = row.token;
                                var routeEdit = "{{ route('booking.new-trip', ['token' => 'rowID']) }}";
                                routeEdit = routeEdit.replace('rowID', id);

                                var routeDlt = "{{ route('booking.delete', ['id' => 'rowID']) }}";
                                routeDlt = routeDlt.replace('rowID', id);

                                // var routeView = "{{ route('booking.view', ['id' => 'rowID']) }}";
                                // routeView = routeView.replace('rowID', id);

                                var routeActivity = "{{ route('booking.activity', ['id' => 'rowID']) }}";
                                routeActivity = routeActivity.replace('rowID', row.id);

                                var text = `<div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" style="">
                                <a class="dropdown-item waves-effect" href="${routeActivity}"><i class="mdi mdi-book-outline me-1"></i> Activity Log</a>
                                <a class="dropdown-item waves-effect" href="${routeEdit}"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                                <a class="dropdown-item waves-effect" data-bs-toggle="modal" onclick="deleteModal('${routeDlt}')"
                                            data-bs-target="#deleteModal" href="javaScript:void(0)"><i class="mdi mdi-trash-can-outline me-1"></i> Delete</a>
                                </div>
                                
                            </div>`;
                                return text;
                            }
                        }, {
                            data: 'created',
                            name: 'created'
                        }, {
                            name: 'customer',
                            "render": function(data, type, row, meta) {
                                var id = row.token;
                                var routeView = "{{ route('booking.view', ['token' => 'rowID']) }}";
                                routeView = routeView.replace('rowID', id);

                                var data = row.customers;
                                if (data.length > 0) {
                                    var text = `${data[0].name}`;
                                } else {
                                    text = '';
                                }
                                return text;
                            }
                        },
                        {
                            data: 'trip_name',
                            name: 'trip_name'
                        },
                           {
                            data: 'relation_manager_names',
                            name: 'relation_manager_names',
                            render: function(data, type, row, meta) {
                                if (data && data.trim() !== "" && data.trim().toUpperCase() !== "N/A") {
                                    return "<span>" + data + "</span>";
                                } else {
                                    return "<span class='badge bg-danger'>No</span>";
                                }
                            }
                        },
                        {
                            data: 'trip_type',
                            name: 'trip_type'
                        },
                        {
                            name: 'payable_amt',
                            "render": function(data, type, row, meta) {
                                var text = `₹${row.payable_amt}`;
                                return text;
                            }
                        },
                        {
                            name: ' payment_amt',
                            "render": function(data, type, row, meta) {
                                var text = `₹${row.total_payment_amount_received}`;
                                return text;
                            }
                        },
                        {
                            name: ' balance',
                            "render": function(data, type, row, meta) {
                                var text = `₹${row.balance}`;
                                return text;
                            }
                        },
                        {
                            name: 'trip_status',
                            "render": function(data, type, row, meta) {
                                var stat = row.trip_status;
                                if (stat == "Cancelled") {
                                    var text = "<span class='badge bg-danger'>Cancelled</span>"
                                } else if (stat == "Completed") {
                                    var text = "<span class='badge bg-success'>Completed</span>"
                                } else if (stat == "Confirmed") {
                                    var text = "<span class='badge bg-success'>Confirmed</span>"
                                } else if (stat == "Draft") {
                                    var text = "<span class='badge bg-secondary'>Draft</span>"
                                } else if (stat == "Correction") {
                                    var text = "<span class='badge bg-warning'>Correction Required</span>"
                                }
                                return text;
                            }
                        },
                        {
                            name: 'invoice_status',
                            "render": function(data, type, row, meta) {
                                var stat = row.invoice_status;
                                if (stat == "NA") {
                                    var text = "<span class='badge bg-warning'>NA</span>"
                                } else if (stat == "Sent") {
                                    var text = "<span class='badge bg-success'>Sent</span>"
                                } else if (stat == "Pending") {
                                    var text = "<span class='badge bg-danger'>Pending</span>"
                                }
                                return text;
                            }
                        },
                        {
                            data: 'invoice_sent_date',
                            name: 'invoice_sent_date'
                        },
                        {
                            data: 'admin_id',
                            name: 'admin_id'
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

            function deleteModal(route) {
                $('#deleteBtn').attr('href', route);
            }
        </script>
    @endsection
