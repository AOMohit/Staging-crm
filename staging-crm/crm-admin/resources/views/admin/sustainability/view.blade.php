@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- DataTable with Buttons -->
        <div class="card">

            <div class="card-header">
                <div class="nav-align-top">
                    <ul class="nav nav-tabs" role="tablist" style="font-size: 0.7em">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-top-optIn" aria-controls="navs-top-optIn" aria-selected="true">
                                Opt In
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-top-optOut" aria-controls="navs-top-optOut" aria-selected="false">
                                Opt Out
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class="dt-buttons btn-group flex-wrap">
                    <a href="{{ route('sustainability.export') }}"
                        class="btn btn-secondary buttons-collection btn-label-primary me-2" tabindex="0"
                        aria-controls="DataTables_Table_0" type="button" aria-haspopup="dialog"
                        aria-expanded="false"><span><i class="mdi mdi-export-variant me-sm-1"></i> <span
                                class="d-none d-sm-inline-block">Export</span></span></span>
                    </a>
                </div>
            </div>

            <div class="tab-content p-0">
                <div class="tab-pane fade show active" id="navs-top-optIn" role="tabpanel">
                    <div class="card-datatable table-responsive pt-0">
                        <div class="p-3">
                            <table id="myDatatable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Creation Date</th>
                                        <th>Trip Name</th>
                                        <th>Customer Name</th>
                                        <th>Donation Amount</th>
                                        <th>No Of Tree</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show" id="navs-top-optOut" role="tabpanel">
                    <div class="card-datatable table-responsive pt-0">
                        <div class="p-3">
                            <table id="myDatatableOptOut" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Creation Date</th>
                                        <th>Trip Name</th>
                                        <th>Customer Name</th>
                                        <th>Donation Amount</th>
                                        <th>No Of Tree</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
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
                    getfilterdataOptOut();
                });

                function getfilterdata() {
                    var name = $("#name").val();
                    var trip_type = $("#trip_type").val();
                    var status = $("#status").val();
                    var admin = $("#admin").val();
                    var date = $("#date").val();

                    var table = $('#myDatatable').DataTable({
                        "lengthMenu": [
                            [20, 50, 100],
                            [20, 50, 100]
                        ],
                        "order": [],
                        "processing": true,
                        "destroy": true,
                        "ajax": {
                            "url": "{!! route('sustainability.sustainabilityList') !!}",
                            "type": 'GET',
                            "data": {
                                "user_type": "admin",
                                "trip_id": "{{ request()->id }}",
                                'type': 'optIn'
                            }
                        },
                        "serverSide": true,
                        "deferRender": true,
                        "columns": [{
                                data: 'created',
                                name: 'created'
                            }, {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'customer_name',
                                name: 'customer_name'
                            }, {
                                data: 'donation_amt',
                                name: 'donation_amt'
                            },
                            {
                                data: 'tree_no',
                                name: 'tree_no'
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

                function getfilterdataOptOut() {
                    var name = $("#name").val();
                    var trip_type = $("#trip_type").val();
                    var status = $("#status").val();
                    var admin = $("#admin").val();
                    var date = $("#date").val();

                    var table = $('#myDatatableOptOut').DataTable({
                        "lengthMenu": [
                            [20, 50, 100],
                            [20, 50, 100]
                        ],
                        "order": [],
                        "processing": true,
                        "destroy": true,
                        "ajax": {
                            "url": "{!! route('sustainability.sustainabilityList') !!}",
                            "type": 'GET',
                            "data": {
                                "user_type": "admin",
                                "trip_id": "{{ request()->id }}",
                                'type': 'optOut'
                            }
                        },
                        "serverSide": true,
                        "deferRender": true,
                        "columns": [{
                                data: 'created',
                                name: 'created'
                            }, {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'customer_name',
                                name: 'customer_name'
                            }, {
                                data: 'donation_amt',
                                name: 'donation_amt'
                            },
                            {
                                data: 'tree_no',
                                name: 'tree_no'
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
        @endsection
