@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row gy-4 mb-4">
            <!-- Cards with few info -->
            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <div class="avatar me-3">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-account-outline mdi-24px"> </i>
                                </div>
                            </div>
                            <div class="card-info pt-4 pb-4">
                                <div class="d-flex align-items-center">
                                    <h4 class="mb-0">{{ indian_number_format($earned) }}</h4>
                                </div>
                                <small class="text-muted">Total Points Earned </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body row widget-separator">
                        <div class="col-sm-12 g-2 text-nowrap d-flex flex-column justify-content-between px-4 gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <small>Discovery</small>
                                <div class="progress w-100 rounded" style="height:10px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{ getTierPerByCustomer($discovery) }}%"
                                        aria-valuenow="{{ getTierPerByCustomer($discovery) }}" aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="w-px-20 text-end">{{ indian_number_format($discovery) }}</small>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <small>Adventurer</small>
                                <div class="progress w-100 rounded" style="height:10px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{ getTierPerByCustomer($adventurer) }}%"
                                        aria-valuenow="{{ getTierPerByCustomer($adventurer) }}" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                                <small class="w-px-20 text-end">{{ indian_number_format($adventurer) }}</small>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <small>Explorer</small>
                                <div class="progress w-100 rounded" style="height:10px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{ getTierPerByCustomer($explorer) }}%"
                                        aria-valuenow="{{ getTierPerByCustomer($explorer) }}" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                                <small class="w-px-20 text-end">{{ indian_number_format($explorer) }}</small>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <small>Legends</small>
                                <div class="progress w-100 rounded" style="height:10px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{ getTierPerByCustomer($legends) }}%"
                                        aria-valuenow="{{ getTierPerByCustomer($legends) }}" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                                <small class="w-px-20 text-end">{{ indian_number_format($legends) }}</small>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <div class="avatar me-3">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-account-outline mdi-24px"> </i>
                                </div>
                            </div>
                            <div class="card-info  pt-4 pb-4">
                                <div class="d-flex align-items-center ">
                                    <h4 class="mb-0">{{ indian_number_format($gift) }}</h4>
                                </div>
                                <small class="text-muted">Total Points Gifted </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <div class="avatar me-3">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-account-outline mdi-24px"> </i>
                                </div>
                            </div>
                            <div class="card-info  pt-4 pb-4">
                                <div class="d-flex align-items-center ">
                                    <h4 class="mb-0">{{ indian_number_format($redeem) }}</h4>
                                </div>
                                <small class="text-muted">Total Points Redeemed </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <div class="avatar me-3">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-account-outline mdi-24px"> </i>
                                </div>
                            </div>
                            <div class="card-info  pt-4 pb-4">
                                <div class="d-flex align-items-center ">
                                    <h4 class="mb-0">{{ indian_number_format($transfer) }}</h4>
                                </div>
                                <small class="text-muted">Total Points Transferred </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <div class="avatar me-3">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-account-outline mdi-24px"> </i>
                                </div>
                            </div>
                            <div class="card-info  pt-4 pb-4">
                                <div class="d-flex align-items-center ">
                                    <h4 class="mb-0">{{ indian_number_format($expired) }}</h4>
                                </div>
                                <small class="text-muted">Total Points Expired </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="p-3">
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <div class="dt-buttons btn-group flex-wrap">

                            <a href="{{ route('loyalty.export') }}"
                                class="btn btn-secondary buttons-collection btn-label-primary me-2" tabindex="0"
                                aria-controls="DataTables_Table_0" type="button" aria-haspopup="dialog"
                                aria-expanded="false"><span><i class="mdi mdi-export-variant me-sm-1"></i> <span
                                        class="d-none d-sm-inline-block">Export</span></span></span>
                            </a>

                            <a href="{{ route('loyalty.gift') }}"
                                class="btn btn-secondary buttons-collection btn-label-primary me-2" tabindex="0"
                                aria-controls="DataTables_Table_0" type="button" aria-haspopup="dialog"
                                aria-expanded="false"><span><i class="menu-icon tf-icons mdi mdi-gift-outline"></i> <span
                                        class="d-none d-sm-inline-block">Gift Loyalty Points</span></span></span>
                            </a>

                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <div class="nav-align-top">
                        <ul class="nav nav-tabs" role="tablist" style="font-size: 0.7em">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-trip" aria-controls="navs-top-trip" aria-selected="false">
                                    trip
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-gift" aria-controls="navs-top-gift" aria-selected="true">
                                    gift
                                </button>
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="navs-top-trip" role="tabpanel">
                            <div class="card-datatable table-responsive pt-0">
                                <table id="myDatatableTrip" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Date</th>
                                            <th>Customers</th>
                                            <th>Trip Name</th>
                                            <th>Points</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade show " id="navs-top-gift" role="tabpanel">
                            <div class="card-datatable table-responsive pt-0">
                                <table id="myDatatable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Date</th>
                                            <th>Customers</th>
                                            <th>Reason</th>
                                            <th>Initiated By</th>
                                            <th>Points</th>
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
    <!--/ DataTable with Buttons -->
@endsection

@section('script')
    <script>
        $('th').css('white-space', 'nowrap');

        $(document).ready(function() {
            getfilterdata();
            getfilterdataTrip();
        });

        function getfilterdata() {
            // var sdatefrom = $("#sdatefrom").val();

            var table = $('#myDatatable').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('loyalty.get') !!}",
                    "type": 'GET',
                    "data": {
                        "user_type": "admin",
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        name: 'Action',
                        "render": function(data, type, row, meta) {
                            var id = row.id;

                            var routeActivity = "{{ route('loyalty.activity', ['id' => 'rowID']) }}";
                            routeActivity = routeActivity.replace('rowID', id);

                            var text = `<div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" style="">
                                <a class="dropdown-item waves-effect" href="${routeActivity}"><i class="mdi mdi-book-outline me-1"></i> Activity Log</a>
                                </div>
                                
                            </div>`;
                            return text;
                        }
                    },
                    {

                        data: 'created',
                        name: 'created'
                    }, {
                        name: 'name',
                        "render": function(data, type, row, meta) {
                            var text = `<div><div>${row.name}</div><div>${row.email}</div></div>`;
                            return text;
                        }
                    }, {
                        name: 'reason',
                        "render": function(data, type, row, meta) {
                            var text = `<span style="text-wrap: wrap;">${row.reason}</span>`;
                            return text;
                        }
                    }, {
                        data: 'admin_name',
                        name: 'admin_name'
                    }, {
                        name: 'trans_amt',
                        "render": function(data, type, row, meta) {
                            var text = `₹<span class="">${row.trans_amt}</span>`;
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

        function getfilterdataTrip() {

            var table = $('#myDatatableTrip').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('loyalty.cashback') !!}",
                    "type": 'GET',
                    "data": {
                        "user_type": "admin",
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        name: 'Action',
                        "render": function(data, type, row, meta) {
                            var id = row.id;
                            var token = row.token;

                            var routeActivity = "{{ route('loyalty.activity', ['id' => 'rowID']) }}";
                            routeActivity = routeActivity.replace('rowID', id);

                            var routeDetails = "{{ route('booking.view', ['token' => 'rowID']) }}";
                            routeDetails = routeDetails.replace('rowID', token);

                            var text = `<div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" style="">
                                <a class="dropdown-item waves-effect" href="${routeActivity}"><i class="mdi mdi-book-outline me-1"></i> Activity Log</a>
                                <a class="dropdown-item waves-effect" href="${routeDetails}"><i class="mdi mdi-eye-outline me-1"></i> View Booking</a>
                                </div>
                                
                            </div>`;
                            return text;
                        }
                    },
                    {
                        data: 'created',
                        name: 'created'
                    }, {
                        name: 'name',
                        "render": function(data, type, row, meta) {
                            var text = `<div><div>${row.name}</div><div>${row.email}</div></div>`;
                            return text;
                        }
                    }, {
                        data: 'trip_name',
                        name: 'trip_name'
                    }, {
                        name: 'trans_amt',
                        "render": function(data, type, row, meta) {
                            var text = `₹<span class="">${row.trans_amt}</span>`;
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

        function deleteModal(route) {
            $('#deleteBtn').attr('href', route);
        }
    </script>
@endsection
