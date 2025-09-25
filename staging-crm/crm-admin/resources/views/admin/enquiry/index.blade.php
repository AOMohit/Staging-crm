@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="p-3">
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <div class="dt-buttons btn-group flex-wrap">

                            <a href="{{ route('enquiry.export') }}"
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
                            <th>Action</th>
                            <th>Added Date</th>
                            <th>Enquired By</th>
                            <th>Contact No.</th>
                            <th>Email</th>
                            <th>Expedition</th>
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
            // var sdatefrom = $("#sdatefrom").val();

            var table = $('#myDatatable').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [[1, "desc"]],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('enquiry.get') !!}",
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
                            var isRead = row.is_read;
                            var routeView = "{{ route('enquiry.view', ['id' => 'rowID']) }}";
                            routeView = routeView.replace('rowID', id);
                            var newLabel = (isRead == 0) ? `<span class="badge new-blink bg-info ms-1">New</span>` : '';
                            var text = `<div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                                </button> ${newLabel}
                                <div class="dropdown-menu" style="">
                                <a class="dropdown-item waves-effect" href="${routeView}"><i class="mdi mdi-eye-outline me-1"></i> View</a>
                                </div>
                            </div>`;
                            return text;
                        }
                    },
                    {
                        data: 'created',
                        name: 'created'
                    }, {
                        data: 'name',
                        name: 'name'
                    }, {
                        data: 'phone',
                        name: 'phone'
                    }, {
                        data: 'email',
                        name: 'email'
                    }, {
                        data: 'trip_name',
                        name: 'trip_name'
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
