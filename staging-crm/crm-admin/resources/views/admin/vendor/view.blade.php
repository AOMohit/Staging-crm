@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="{{ route('vendors.index') }}">Vendor</a> /
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
                                    <img class="img-fluid rounded mb-3 mt-4" src="{{ env('USER_URL' . $data->profile) }}"
                                        height="120" width="120" alt="{{ $data->first_name }}" />
                                @else
                                    <img class="img-fluid rounded mb-3 mt-4"
                                        src="{{ url('public/admin') }}/assets/img/avatars/10.png" height="120"
                                        width="120" alt="{{ $data->first_name }}" />
                                @endif
                                <div class="user-info text-center">
                                    <h4>{{ $data->first_name . ' ' . $data->last_name }}</h4>
                                    <span class="badge bg-label-danger">{{ 'Vendor' }}</span>
                                </div>
                            </div>
                        </div>
                        <h5 class="pb-3 border-bottom mb-3">Details</h5>
                        <div class="info-container">
                            <ul class="list-unstyled mb-4">
                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Name:</span>
                                    <span>{{ $data->first_name . ' ' . $data->last_name }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Email:</span>
                                    <span>{{ $data->email }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Company:</span>
                                    <span class="">{{ $data->company }}</span>
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
                                    <span class="fw-semibold text-heading me-2">Category:</span>
                                    <span>
                                        @if ($data->vendor_type)
                                            @foreach (json_decode($data->vendor_type) as $es)
                                                {{ getVendorCategoryById($es)->title . ', ' }}
                                            @endforeach
                                        @endif
                                    </span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-semibold text-heading me-2">Service:</span>
                                    <span>
                                        @if ($data->vendor_type)
                                            @foreach (json_decode($data->service_id) as $es)
                                                {{ getVendorServiceById($es)->title . ', ' }}
                                            @endforeach
                                        @endif
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /User Card -->
                <!-- Plan Card -->
                <div class="card">
                    <div class="row">
                        <div class="col-7">
                            <div class="card-body">
                                <div class="card-info mb-3 pb-2">
                                    <h5 class="mb-3 text-nowrap">Earnings</h5>
                                </div>
                                <div class="d-flex align-items-end">
                                    <h6 class="mb-0 me-2">Total: ₹{{ indian_number_format($total_exp) }}</h6>
                                </div>
                                <div class="d-flex align-items-end mt-2">
                                    <h6 class="mb-0 me-2">Pending: ₹{{ indian_number_format($pending_exp) }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-5 text-end d-flex align-items-end">
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
                                        data-bs-target="#navs-top-services" aria-controls="navs-top-services"
                                        aria-selected="true">
                                        Services
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-top-earning" aria-controls="navs-top-earning"
                                        aria-selected="false">
                                        Earnings
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content p-0">
                            <div class="tab-pane fade show active" id="navs-top-services" role="tabpanel">
                                <div class="card-datatable table-responsive pt-0">
                                    <table id="myDatatableServices" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Trip Name</th>
                                                <th>Category</th>
                                                <th>Service</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-top-earning" role="tabpanel">
                                <div class="card-datatable table-responsive pt-0">
                                    <table id="myDatatableEarning" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Trip Name</th>
                                                <th>Category</th>
                                                <th>Service</th>
                                                <th>Total Amount</th>
                                                <th>Paid Amount</th>
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
            <!--/ User Content -->
        </div>

    </div>
@endsection

@section('script')
    <script>
        var vendor_id = "{{ request()->id }}";
        $('th').css('white-space', 'nowrap');

        $(document).ready(function() {
            getfilterdataServices();
            getfilterdataEarning();
        });

        function getfilterdataServices() {

            var table = $('#myDatatableServices').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('vendors.details.services') !!}",
                    "type": 'GET',
                    "data": {
                        "vendor_id": vendor_id,
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
                        data: 'service_name',
                        name: 'service_name'
                    },
                    {
                        data: 'vendorServiceName',
                        name: 'vendorServiceName'
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

        function getfilterdataEarning() {

            var table = $('#myDatatableEarning').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('vendors.details.services') !!}",
                    "type": 'GET',
                    "data": {
                        "vendor_id": vendor_id,
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
                        data: 'service_name',
                        name: 'service_name'
                    },
                    {
                        data: 'vendorServiceName',
                        name: 'vendorServiceName'
                    },
                    {
                        name: 'total_amount',
                        "render": function(data, type, row, meta) {
                            var text = row.total_amount;

                            return "₹" + text;
                        }
                    },
                    {
                        name: 'paid_amount',
                        "render": function(data, type, row, meta) {
                            var text = row.paid_amount;

                            return "₹" + text;
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
    </script>
@endsection
