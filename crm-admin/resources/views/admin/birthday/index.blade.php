@extends('admin.inc.layout')

@section('content')
<style>
    .nowrap {
    white-space: nowrap !important;
}
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- DataTable with Buttons -->
        <div class="card">
                <div class="p-3">
                    {{-- Filter --}}
                    <div class="row mb-2 d-flex justify-content-start">
                        <div class="col-2">
                            <label for="">Filter</label>
                            <select id="filter" class="form-control">
                                <option value="">Select</option>
                                <option @if (request()->filter == 'Daily') selected @endif value="Daily">Today</option>
                                <option @if (request()->filter == 'Weekly') selected @endif value="Weekly">Next 7 days</option>
                                <option @if (request()->filter == 'Monthly') selected @endif value="Monthly">Next 30 days</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <label for="">From</label>
                            <input value="{{ request()->input('from_date') }}" id="from_date" type="date" class="form-control">
                        </div>
                        <div class="col-2">
                            <label for="">To</label>
                            <input value="{{ request()->input('to_date') }}" id="to_date" type="date" class="form-control">
                        </div>
                        <div class="col-2">
                            <div class="row" style="margin-right: 4px;">
                                <button class="btn btn-warning mt-4" onclick="filterBirthdays()">Filter</button>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="row" style="margin-right: 4px;">
                                <button
                                    id="resetButton"
                                    class="btn btn-warning mt-4"
                                    style="background-color: #6d788d; border-color: #6d788d;">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                    {{-- End Filter --}}
                    <br />
                    <div class="row gy-4">
                        <!-- Cards with few info -->
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <div class="avatar me-3">
                                            <div class="avatar-initial bg-label-primary rounded">
                                                <span class="mdi mdi-cake-variant"></span>
                                            </div>
                                        </div>
                                        <div class="card-info">
                                            <div class="d-flex align-items-center">
                                                <h4 class="mb-0">{{ $birthdaysCount }}</h4>
                                            </div>
                                            <small class="text-muted">Total Birthdays</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <div class="avatar me-3">
                                            <div class="avatar-initial bg-label-primary rounded">
                                                <span class="mdi mdi-email-fast"></span>
                                            </div>
                                        </div>
                                        <div class="card-info">
                                            <div class="d-flex align-items-center">
                                                <h4 class="mb-0">{{ $emails_sent }}</h4>
                                            </div>
                                            <small class="text-muted">Notifications Sent</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <div class="avatar me-3">
                                            <div class="avatar-initial bg-label-primary rounded">
                                                <span class="mdi mdi-account-clock"></span>
                                            </div>
                                        </div>
                                        <div class="card-info">
                                            <div class="d-flex align-items-center">
                                                <h4 class="mb-0">{{ $emails_not_sent }}</h4>
                                            </div>
                                            <small class="text-muted">Notifications Pending</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <div class="dt-buttons btn-group flex-wrap">
                            <a href="{{ route('birthday.export') }}"
                               id="exportButton"
                               class="btn btn-secondary buttons-collection btn-label-primary me-2" tabindex="0"
                               aria-controls="DataTables_Table_0" type="button" aria-haspopup="dialog"
                               aria-expanded="false"><span><i class="mdi mdi-export-variant me-sm-1"></i> <span
                                        class="d-none d-sm-inline-block">Export</span></span>
                            </a>
                        </div>
                    </div>
                </div>
                    <div class="card-datatable table-responsive pt-0">
                        <table id="myDatatable" class="table table-bordered">
                            <thead>
                            <tr>
                                <th data-name="name">Customer Name</th>
                                <th data-name="email">Email ID</th>
                                <th data-name="phone">phone</th>
                                <th data-name="day">Day</th>
                                <th data-name="month_name">Month</th>
                                <th data-name="year">Year</th>
                                <th data-name="birthday_email_sent">Email Sent</th>
                                <th data-name="whats_app_sent">Whats App Sent</th>

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
        $(document).ready(function () {
            getfilterdata();
            updateExportUrl();

            $('#resetButton').on('click', function (e) {
                e.preventDefault();

                // Clear the filter inputs
                $('#filter').val('');
                $('#from_date').val('');
                $('#to_date').val('');

                // Reload the page without query parameters
                var baseUrl = window.location.href.split('?')[0];
                window.location.href = baseUrl;
            });
        });

        function updateExportUrl() {
            var filter = $('#filter').val();
            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();

            var exportUrl = "{{ route('birthday.export') }}";
            var params = new URLSearchParams();

            if (filter) params.append('filter', filter);
            if (fromDate) params.append('from_date', fromDate);
            if (toDate) params.append('to_date', toDate);

            // Update the href attribute
            $('#exportButton').attr('href', exportUrl + '?' + params.toString());
        }

        // Trigger URL update when filters change
        $('#filter, #from_date, #to_date').on('change', updateExportUrl);

        function getfilterdata() {
            var filter = $('#filter').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $('#myDatatable').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "searching": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('birthday.get-birthdays') !!}",
                    "type": 'GET',
                    "data": {
                        "user_type": "admin",
                        "filter": filter,
                        "from_date": from_date,
                        "to_date": to_date
                    },   
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [
                    { data: 'name', name: 'name', className: 'nowrap'},
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'day', name: 'day' },
                    { data: 'month_name', name: 'month_name' },
                    { data: 'year', name: 'year' },
                    
                    {
                        data: 'birthday_email_sent',
                        name: 'birthday_email_sent',
                         className: 'nowrap',
                        render: function (data, type, row) {
                            return data === 1 ? 'Yes' : 'No';
                        },
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'whats_app_sent',
                        name: 'whats_app_sent',
                        title: 'WhatsApp Sent',
                        width: '150px',
                        className: 'nowrap',
                        render: function (data, type, row) {
                            return data === 1 ? 'Yes' : 'No';
                        },
                        orderable: true,
                        searchable: false
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

        function filterBirthdays() {
            var filter = $("#filter").val();
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var fullUrl = "{{ url()->current() }}";

            window.location.href = fullUrl + "?filter=" + filter + "&from_date=" + from_date + "&to_date=" + to_date;
        }
    </script>
@endsection
