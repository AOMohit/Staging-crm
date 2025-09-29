@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="p-3">
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <div class="dt-buttons btn-group flex-wrap">

                            <button class="btn btn-secondary  btn-label-primary me-2" data-bs-toggle="modal"
                                data-bs-target="#basicModal">
                                <i class="mdi mdi-tray-arrow-down me-sm-1"></i> Import
                            </button>


                            <a href="{{ route('vendors.export') }}"
                                class="btn btn-secondary buttons-collection btn-label-primary me-2" tabindex="0"
                                aria-controls="DataTables_Table_0" type="button" aria-haspopup="dialog"
                                aria-expanded="false"><span><i class="mdi mdi-export-variant me-sm-1"></i> <span
                                        class="d-none d-sm-inline-block">Export</span></span></span>
                            </a>

                            <a class="btn btn-secondary btn-primary text-white" href="{{ route('vendors.add') }}"
                                tabindex="0"><span><i class="mdi mdi-plus me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">Add Vendor</span></span>
                            </a>
                        </div>
                    </div>
                </div>
                <table id="myDatatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Added Date</th>
                            <th>Company Name</th>
                            <th>Vendor Name</th>
                            <th>Contact No.</th>
                            <th>Country</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--/ DataTable with Buttons -->

    <!-- Modal -->
    <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1">Import Customers List</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('vendors.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-4 mt-2">
                                <div class="form-floating form-floating-outline">
                                    <input type="file" name="file" class="form-control" placeholder="File"
                                        accept=".xlsx" required />
                                    <label for="nameBasic">Upload File</label>
                                </div>
                                <a href="{{ route('vendors.export-sample') }}">click here</a> to download sample file
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
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('vendors.get-vendors') !!}",
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
                            var routeEdit = "{{ route('vendors.edit', ['id' => 'rowID']) }}";
                            routeEdit = routeEdit.replace('rowID', id);

                            var routeActivity = "{{ route('vendors.activity', ['id' => 'rowID']) }}";
                            routeActivity = routeActivity.replace('rowID', id);

                            var routeView = "{{ route('vendors.view', ['id' => 'rowID']) }}";
                            routeView = routeView.replace('rowID', id);

                            var routeDlt = "{{ route('vendors.delete', ['id' => 'rowID']) }}";
                            routeDlt = routeDlt.replace('rowID', id);

                            var text = `<div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" style="">
                                <a class="dropdown-item waves-effect" href="${routeActivity}"><i class="mdi mdi-book-outline me-1"></i> Activity Log</a>
                                <a class="dropdown-item waves-effect" href="${routeView}"><i class="mdi mdi-eye-outline me-1"></i> View</a>
                                <a class="dropdown-item waves-effect" href="${routeEdit}"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                                <a class="dropdown-item waves-effect" data-bs-toggle="modal" onclick="deleteModal('${routeDlt}')"
                                            data-bs-target="#deleteModal" href="javaScript:void(0)"><i class="mdi mdi-trash-can-outline me-1"></i> Delete</a>
                                </div>
                            </div>`;
                            return text;
                        }
                    },
                    {
                        data: 'created',
                        name: 'created'
                    }, {
                        data: 'company',
                        name: 'company'
                    }, {
                        name: 'agent',
                        "render": function(data, type, row, meta) {
                            var id = row.id;

                            var routeView = "{{ route('vendors.view', ['id' => 'rowID']) }}";
                            routeView = routeView.replace('rowID', id);

                            var text =
                                `<a class="dropdown-item waves-effect text-primary" href="${routeView}"><div><div>${row.name} <div> <div>${row.email}</div></div></a>`;
                            return text;
                        }
                    }, {
                        data: 'phone',
                        name: 'phone'
                    }, {
                        data: 'country',
                        name: 'country'
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
