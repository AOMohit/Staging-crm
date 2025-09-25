@extends('admin.inc.layout')

@section('content')
<style>
    #expedition {
        width: 100%; /* Ensure the dropdown takes the full width of its container */
    }
</style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="{{ route('customer.index') }}">Customer</a> /
                view
        </h4>
        <div class="row">
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <!-- User Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                @if ($data->profile)
                                    <img class="img-fluid rounded mb-3 mt-4"
                                        src="{{ getenv('USER_URL') . 'storage/app/' . $data->profile }}" height="120"
                                        width="120" alt="{{ $data->first_name }}" />
                                @else
                                    <img class="img-fluid rounded mb-3 mt-4"
                                        src="{{ url('public/admin') }}/assets/img/avatars/10.png" height="120"
                                        width="120" alt="{{ $data->first_name }}" />
                                @endif
                                <div class="user-info text-center">
                                    <h4>{{ $data->first_name . ' ' . $data->last_name }} <a
                                            href="{{ route('customer.edit', $data->id) }}"><span
                                                class="fa fa-pen"></span></a></h4>
                                    <span class="badge bg-label-danger">{{ 'Customer' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between flex-wrap my-2 py-3">
                            <div class="d-flex align-items-center me-4 mt-3 gap-3">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-check mdi-24px"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-normal">{{ getTripCountbyCustomerId($data->id) }}</h5>
                                    <span>Trips Done</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-3 gap-3">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-star-outline mdi-24px"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-normal">{{ $data->tier }}</h5>
                                    <span>Current Tier</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-3 gap-3">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-cash mdi-24px"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-normal">
                                        {{ '₹' . indian_number_format(totalTripCostOfCustomerById($data->id)) ?? 0 }}</h5>
                                    <span>Total Spends</span>
                                </div>
                            </div>
                        </div>
                        <h5 class="pb-3 border-bottom mb-3">Details</h5>
                        <div class="info-container">
                            <ul class="list-unstyled mb-4">
                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Customer Id:</span>
                                    <span>{{ '#' . $data->id }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Name:</span>
                                    <span>{{ $data->first_name . ' ' . $data->last_name }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Email:</span>
                                    <span>{{ $data->email }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Contact:</span>
                                    <span class="">{{ $data->telephone_code . $data->phone }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Address:</span>
                                    <span>{{ $data->address }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">State:</span>
                                    <span>{{ $data->state }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">City:</span>
                                    <span>{{ $data->city }}</span>
                                </li>

                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Country:</span>
                                    <span>{{ $data->country }}</span>
                                </li>

                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Date Of Birth :</span>
                                    <span>{{ $data->dob }}</span>
                                </li>

                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Meal Preference :</span>
                                    <span>{{ $data->meal_preference }}</span>
                                </li>

                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Blood Group:</span>
                                    <span>{{ $data->blood_group }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Profession:</span>
                                    <span>{{ $data->profession }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Emergency Contact Name:</span>
                                    <span>{{ $data->emg_name }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Emergency Contact Number:</span>
                                    <span>{{ $data->emg_contact }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">T-shirt Size:</span>
                                    <span>{{ $data->t_size }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Medical Condition id any:</span>
                                    <span>{{ $data->medical_condition }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /User Card -->
                <!-- Plan Card -->

                <div class="card mb-4">
                    <div class="row">
                        <div class="col-6">
                            <div class="card-body">
                                <div class="card-info mb-3 pb-2">
                                    <h5 class="mb-3 text-nowrap">Available Points</h5>
                                </div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 me-2">{{ indian_number_format($totalAmtPoint) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-end d-flex align-items-end">
                            <div class="card-body pb-0 pt-3">
                                <img src="{{ url('public/admin') }}/assets/img/illustrations/card-ratings-illustration.png"
                                    alt="Ratings" class="img-fluid" width="95">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="row">
                        <div class="col-6">
                            <div class="card-body">
                                <div class="card-info mb-3 pb-2">
                                    <h5 class="mb-3 text-nowrap">Available Credit Note Amount</h5>
                                </div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 me-2">₹{{ indian_number_format($data->credit_note_wallet) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-end d-flex align-items-end">
                            <div class="card-body pb-0 pt-3">
                                <img src="{{ url('public/admin') }}/assets/img/illustrations/card-ratings-illustration.png"
                                    alt="Ratings" class="img-fluid" width="95">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /Plan Card -->
            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <div class="card">
                    <div class="card-header">
                        <div class="nav-align-top">
                            <ul class="nav nav-tabs" role="tablist" style="font-size: 0.7em">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-top-trips" aria-controls="navs-top-trips"
                                        aria-selected="true">
                                        trips
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-top-points" aria-controls="navs-top-points"
                                        aria-selected="false">
                                        points
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-top-transferred" aria-controls="navs-top-transferred"
                                        aria-selected="false">
                                        transferred points
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-top-referrals" aria-controls="navs-top-referrals"
                                        aria-selected="false">
                                        Referrals
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content p-0">
                            <div class="tab-pane fade show active" id="navs-top-trips" role="tabpanel">
                                <div class="card-datatable table-responsive pt-0">
                                    <table id="myDatatableTrips" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Booking Date</th>
                                                <th>Trip Name</th>
                                                <th>Trip Cost</th>
                                                <th>Members Travelled</th>
                                                <th>Status</th>
                                                <th>Spoc</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-top-points" role="tabpanel">
                                <div class="card-datatable table-responsive pt-0">
                                    <table id="myDatatablePoints" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Points</th>
                                                <th>Trip Name</th>
                                                <th>Reason</th>
                                                <th>Type</th>
                                                <th>Spoc</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-top-transferred" role="tabpanel">
                                <div class="card-datatable table-responsive pt-0">

                                    <table id="myDatatableTransferred" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Receiver</th>
                                                <th>Points</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-top-referrals" role="tabpanel">
                                <div class="card-datatable table-responsive pt-0">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReferalModal">Add Referral</button>
                                    <table id="myDatatablereferal" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Customer</th>
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

                @if ($minorCheck)
                    <div class="card mt-4">
                        <div class="card-header">
                            <div class="nav-align-top">
                                <ul class="nav nav-tabs" role="tablist" style="font-size: 0.7em">
                                    <li class="nav-item">
                                        <button type="button" class="nav-link active" role="tab"
                                            data-bs-toggle="tab" data-bs-target="#navs-top-minors"
                                            aria-controls="navs-top-minors" aria-selected="true">
                                            Member's List
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="tab-content p-0">
                                <div class="tab-pane fade show active" id="navs-top-minors" role="tabpanel">
                                    <div class="card-datatable table-responsive pt-0">
                                        <table id="myDatatableMinor" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Relation</th>
                                                    <th>Name</th>
                                                    <th>Gender</th>
                                                    <th>DOB</th>
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
                @endif
            </div>
            <!--/ User Content -->
        </div>

    </div>
   <!-- filepath: c:\wamp64\www\local-crm-admin\resources\views\admin\customers\view.blade.php -->
<div class="modal fade" id="addReferalModal" tabindex="-1" aria-hidden="true" style="@if ($errors->any()) display: block; @endif">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add Referral Customer</h5>
                </div>
                <div class="card-body">
                    <form id="addReferal">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="Referrer_email" class="form-control"value="{{ $data->email }}"/>
                            <div class="col-12">
                                <div class="form-floating form-floating-outline mb-4">

                                    <input type="text" name="referee_email" class="form-control" id="searchEmail" placeholder="Search Email" autocomplete="off"  />
                                    <label for="searchEmail">Search Customer Email <span class="text-danger">*</span></label>
                                    <div id="emailSuggestions" class="list-group " style="z-index: 1000; display: none;"></div>
                                    <span class="referee_email_error"></span>
                                </div>
                                
                            </div>
                            <div class="mb-3">
                                <label for="expedition" class="form-label">Expedition</label>
                                <select id="expedition" name="expedition" class="form-select">
                                    <option value="" disabled selected>Select Expedition</option>
                                    @foreach (getAllTrips() as $trip)
                                        <option value="{{ $trip->id }}">{{ $trip->name }}</option>
                                     
                                    @endforeach
                                </select>
                              
                                <span class="expedition_error"></span>
                           
                            </div>
                          
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

    <script>
        var customer_id = "{{ request()->id }}";
        $('th').css('white-space', 'nowrap');

        $(document).ready(function() {
            getfilterdataTrips();
            getfilterdataPoints();
            getfilterdataTransfer();
            getfilterdataReferal();
            getfilterdataMinor();
        });

        function getfilterdataTrips() {

            var table = $('#myDatatableTrips').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('customer.details.trips') !!}",
                    "type": 'GET',
                    "data": {
                        "customer_id": customer_id,
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        data: 'created',
                        name: 'created'
                    },
                    {
                        data: 'trip_name',
                        name: 'trip_name'
                    },
                    {
                        data: 'trip_amt',
                        name: 'trip_amt'
                    }, {
                        name: 'minor_travelled',
                        "render": function(data, type, row, meta) {
                            if (row.members == "No") {
                                text = `<span>${row.members}</span>`;
                            } else {
                                text =
                                    `<span data-bs-toggle="tooltip" data-bs-placement="right" title="${row.members}">Members<img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg"
                                                                alt="" class="ps-2"></span>`;
                            }
                            return text;
                        }
                    }, {
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
                                var text = "<span class='badge bg-secendory'>Draft</span>"
                            } else if (stat == "Correction") {
                                var text = "<span class='badge bg-warning'>Correction Required</span>"
                            }

                            return text;
                        }
                    },
                    {
                        data: 'admin_name',
                        name: 'admin_name'
                    },
                    {
                        name: 'action',
                        "render": function(data, type, row, meta) {
                            var id = row.token;
                            var routeView = "{{ route('booking.view', ['token' => 'rowID']) }}";
                            routeView = routeView.replace('rowID', id);

                            var text =
                                `<a target="_blank" class="btn btn-success btn-sm" href="${routeView}">View</a>`;
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

        function getfilterdataPoints() {

            var table = $('#myDatatablePoints').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('customer.details.points') !!}",
                    "type": 'GET',
                    "data": {
                        "customer_id": customer_id,
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        data: 'created',
                        name: 'created'
                    },
                    {
                        data: 'trans_amt',
                        name: 'trans_amt'
                    }, {
                        data: 'trip_name',
                        name: 'trip_name'
                    }, {
                        data: 'reason',
                        name: 'reason'
                    }, {
                        name: 'trans_type',
                        "render": function(data, type, row, meta) {
                            var stat = row.trans_type;
                            if (stat == "Cr") {
                                var text = "<span class='badge bg-success'>Cr</span>"
                            } else if (stat == "Dr") {
                                var text = "<span class='badge bg-danger'>Dr</span>"
                            }

                            return text;
                        }
                    },
                    {
                        data: 'admin_name',
                        name: 'admin_name'
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

        function getfilterdataTransfer() {

            var table = $('#myDatatableTransferred').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('customer.details.transfer') !!}",
                    "type": 'GET',
                    "data": {
                        "customer_id": customer_id,
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        data: 'created',
                        name: 'created'
                    },
                    {
                        name: 'customer_name',
                        "render": function(data, type, row, meta) {
                            var text = `<div>${row.reciever_mail}</div><div>${row.customer_name}</div>`;

                            return text;
                        }
                    },
                    {
                        data: 'trans_amt',
                        name: 'trans_amt'
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

        function getfilterdataReferal() {

            var table = $('#myDatatablereferal').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('customer.details.referal') !!}",
                    "type": 'GET',
                    "data": {
                        "customer_id": customer_id,
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        data: 'created',
                        name: 'created'
                    },
                    {
                        name: 'customer_name',
                        "render": function(data, type, row, meta) {
                            var text =
                                `<div>${row.email}</div><div>${row.first_name} ${row.last_name}</div>`;

                            return text;
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

        function getfilterdataMinor() {

            var table = $('#myDatatableMinor').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('customer.details.minor') !!}",
                    "type": 'GET',
                    "data": {
                        "customer_id": customer_id,
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        data: 'relation',
                        name: 'relation'
                    },
                    {
                        name: 'name',
                        "render": function(data, type, row, meta) {
                            var text = row.first_name + " " + row.last_name;
                            return text;
                        }
                    }, {
                        data: 'gender',
                        name: 'gender'
                    },
                    {
                        data: 'dob',
                        name: 'dob'
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
  
    </script>
   <script>
    document.addEventListener('DOMContentLoaded', function () {
        const emailInput = document.getElementById('searchEmail');
        const suggestionsBox = document.getElementById('emailSuggestions');

        emailInput.addEventListener('input', function () {
            const query = emailInput.value;

            if (query.length > 2) {
                fetch(`{{ route('customer.details.email-suggestions') }}?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsBox.innerHTML = '';
                        suggestionsBox.style.display = 'block';

                        data.forEach(customer => {
                            const suggestionItem = document.createElement('a');
                            suggestionItem.href = '#';
                            suggestionItem.classList.add('list-group-item', 'list-group-item-action');
                            suggestionItem.textContent = `${customer.email} (${customer.first_name} ${customer.last_name})`;
                            suggestionItem.style.cursor = 'pointer';

                            // ✅ FIX: Use 'mousedown' instead of 'click'
                            suggestionItem.addEventListener('mousedown', function (e) {
                                e.preventDefault();
                                emailInput.value = customer.email;
                                suggestionsBox.style.display = 'none';
                            });

                            suggestionsBox.appendChild(suggestionItem);
                        });
                    });
            } else {
                suggestionsBox.style.display = 'none';
            }
        });

        // Hide suggestions if clicked outside
        document.addEventListener('click', function (e) {
            if (!suggestionsBox.contains(e.target) && e.target !== emailInput) {
                suggestionsBox.style.display = 'none';
            }
        });

        // Optional: Log value before form submit to check
        const form = emailInput.closest('form');
        if (form) {
            form.addEventListener('submit', function () {
                console.log('Submitting email:', emailInput.value);
            });
        }
    });
</script>

  

<script>
    $(document).ready(function() {
        $('#addReferal').on('submit', function(e) {
            e.preventDefault();
            $('.referee_email_error').text(''); 
            $('.expedition_error').text('');
            var formData = $(this).serialize();
          
            $.ajax({
                url: "{{ route('customer.referal-store') }}",
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                 console.log(response);
                 if(response.status == 'success'){
                   alert(response.message);
                 }
                  
                },
                error: function(xhr) {
                    if (xhr.status==422 || xhr.status==400) {
                        const errors = xhr.responseJSON.errors;
                        const response = xhr.responseJSON;
                        if (response.status === 'error') {
                            $('.referee_email_error').text(response.message).css('color', 'red');// Show error message
                        }
                        $('.referee_email_error').text(errors.referee_email[0]).css('color', 'red');
                        $('.expedition_error').text(errors.expedition[0]).css('color', 'red');
                       
                       
                    } else {
                        alert('Something went wrong.');
                    }
                }
            });
        });
    });
    </script>
@endsection
