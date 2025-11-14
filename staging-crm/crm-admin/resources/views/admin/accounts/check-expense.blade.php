@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="p-3">
                    {{-- filter --}}
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="date" id="date-from" class="form-control" />
                                <label for="date-from">Date From</label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="date" id="date-to" class="form-control" />
                                <label for="date-to">Date To</label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-floating form-floating-outline mb-4">
                                <select id="trip-name" class="select2 form-select form-select-lg">
                                    <option value=" ">Select Trip</option>
                                    @foreach ($trips as $trip)
                                        <option value="{{ $trip->id }}">{{ $trip->name }}</option>
                                    @endforeach
                                </select>
                                <label for="trip-name">Trip Name </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating form-floating-outline mb-4">
                                <select id="vendor" class="select2 form-select form-select-lg">
                                    <option value=" ">Select Vendor</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">
                                            {{ $vendor->first_name . ' ' . $vendor->last_name }}</option>
                                    @endforeach
                                </select>
                                <label for="vendor">Vendor</label>
                            </div>
                        </div>


                        <div class="col-md-12 mb-2">
                            <div class="text-center">
                                <a href="" class="btn btn-secondary">Reset</a>
                                <button onclick="getfilterdata()" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                        <hr>

                        {{-- filter end --}}

                        <div class="dt-action-buttons text-end pt-3 pt-md-0">
                            <div class="dt-buttons btn-group flex-wrap">

                                <a href="{{ route('accounts.export-check-expense') }}"
                                    class="btn btn-secondary buttons-collection btn-label-primary me-2" tabindex="0"
                                    aria-controls="DataTables_Table_0" type="button" aria-haspopup="dialog"
                                    aria-expanded="false"><span><i class="mdi mdi-export-variant me-sm-1"></i> <span
                                            class="d-none d-sm-inline-block">Export</span></span></span>
                                </a>

                            </div>
                        </div>
                    </div>
                    <table id="myDatatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Date Time</th>
                                <th>Expenses added by</th>
                                <th>Trip Name</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
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
            });

            function getfilterdata() {
                var tripName = $("#trip-name").val();
                var vendor = $("#vendor").val();
                var dateFrom = $("#date-from").val();
                var dateTo = $("#date-to").val();

                var table = $('#myDatatable').DataTable({
                    "lengthMenu": [
                        [20, 50, 100],
                        [20, 50, 100]
                    ],
                    "order": [],
                    "processing": true,
                    "destroy": true,
                    "ajax": {
                        "url": "{!! route('accounts.get-check-expense') !!}",
                        "type": 'POST',
                        "data": {
                            "_token": "{{ csrf_token() }}",
                            "user_type": "admin",
                            "tripName": tripName,
                            "vendor": vendor,
                            "dateFrom": dateFrom,
                            "dateTo": dateTo,
                        }
                    },
                    "serverSide": true,
                    "deferRender": true,
                    "columns": [{
                            name: 'amount',
                            "render": function(data, type, row, meta) {
                                var text = `<span>₹${row.total_amount}</span>`;
                                return text;
                            }
                        }, {
                            data: 'created',
                            name: 'created'
                        }, {
                            data: 'added_by',
                            name: 'added_by'
                        },
                        {
                            data: 'trip_name',
                            name: 'trip_name'
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
