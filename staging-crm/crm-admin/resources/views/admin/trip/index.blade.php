@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="p-3">
                    {{-- filter --}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-floating form-floating-outline mb-4">
                                <select id="name" class="select2 form-select form-select-lg">
                                    <option value=" ">Select Trip</option>
                                    @foreach ($trips as $trip)
                                        <option value="{{ $trip->id }}">{{ $trip->name }}</option>
                                    @endforeach
                                </select>
                                <label for="basic-default-fullname">Trip Name </label>
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
                                <select id="status" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="Ongoing">Ongoing</option>
                                    <option value="Completed">Completed</option>
                                </select>
                                <label for="basic-default-fullname">Status</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating form-floating-outline mb-4">
                                <select id="admin" class="form-control">
                                    <option value="">Created By</option>
                                    @foreach ($admins as $admin)
                                        <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                    @endforeach
                                </select>
                                <label for="basic-default-fullname">Created By</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="date" id="date" class="form-control" id="basic-default-fullname" />
                                <label for="basic-default-fullname">Date</label>
                            </div>
                        </div>

                        <div class="col-md-12 mb-2">
                            <div class="text-center">
                                <a href="" class="btn btn-secondary">Reset</a>
                                <button onclick="getfilterdata()" class="btn btn-primary">Filter</button>
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


                                <a href="{{ route('trip.export') }}"
                                    class="btn btn-secondary buttons-collection btn-label-primary me-2" tabindex="0"
                                    aria-controls="DataTables_Table_0" type="button" aria-haspopup="dialog"
                                    aria-expanded="false"><span><i class="mdi mdi-export-variant me-sm-1"></i> <span
                                            class="d-none d-sm-inline-block">Export</span></span></span>
                                </a>

                                <a class="btn btn-secondary btn-primary text-white" href="{{ route('trip.add') }}"
                                    tabindex="0"><span><i class="mdi mdi-plus me-sm-1"></i>
                                        <span class="d-none d-sm-inline-block">Add Trip</span></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <table id="myDatatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Creation Date</th>
                                <th>Trip Name</th>
                                <th>Relation Manager</th>
                                <th>Trip Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Pax</th>
                                <th>Status</th>
                                <th>Sold Out</th>
                                <th>Created By</th>
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
                        <h4 class="modal-title" id="exampleModalLabel1">Import Trip List</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('trip.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-4 mt-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="file" name="file" class="form-control" placeholder="File"
                                            accept=".xlsx" required />
                                        <label for="nameBasic">Upload File</label>
                                    </div>
                                    <a href="{{ route('trip.export-sample') }}">click here</a> to download sample file
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

        {{-- modal for trip Cancel --}}
        <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Cancel Trip</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('trip.cancel') }}" method="post">
                                @csrf
                                <input type="hidden" id="trip_id" name="trip_id" value="">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <input required type="text" name="cancelation_reason" class="form-control"
                                                id="basic-default-fullname" placeholder="Cancelation Reason" />
                                            <label for="basic-default-fullname">Reason <span
                                                    class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-warning">Submit</button>
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
            $('th').css('white-space', 'nowrap');

            $(document).ready(function() {
                getfilterdata();
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
                        "url": "{!! route('trip.get') !!}",
                        "type": 'GET',
                        "data": {
                            "user_type": "admin",
                            "name": name,
                            "trip_type": trip_type,
                            "status": status,
                            "admin": admin,
                            "date": date,
                        },
                        "error": function(xhr, error, thrown) {
                            console.log("Ajax Error:", xhr.responseText);
                        }
                    },
                    "serverSide": true,
                    "deferRender": true,
                    "columns": [{
                            name: 'Action',
                            "render": function(data, type, row, meta) {
                                var checkEditable = row.editable;
                                var id = row.id;
                                var routeEdit = "{{ route('trip.edit', ['id' => 'rowID']) }}";
                                routeEdit = routeEdit.replace('rowID', id);

                                var routeDlt = "{{ route('trip.delete', ['id' => 'rowID']) }}";
                                routeDlt = routeDlt.replace('rowID', id);

                                var routeView = "{{ route('trip.view', ['id' => 'rowID']) }}";
                                routeView = routeView.replace('rowID', id);

                                var routeActivity = "{{ route('trip.activity', ['id' => 'rowID']) }}";
                                routeActivity = routeActivity.replace('rowID', id);

                                var routeCancel = "{{ route('trip.cancel', ['id' => 'rowID']) }}";
                                routeCancel = routeCancel.replace('rowID', id);

                                var text =
                                    `<div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="mdi mdi-dots-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu" style="">
                                                    <a class="dropdown-item waves-effect" href="${routeActivity}"><i class="mdi mdi-book-outline me-1"></i> Activity Log</a>
                                                <a class="dropdown-item waves-effect" href="${routeView}"><i class="mdi mdi-eye-outline me-1"></i> View</a>`;
                                //if (checkEditable == 1) {
                                text +=
                                    `<a class="dropdown-item waves-effect" href="${routeEdit}"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>`;
                                //}
                                if (row.statuss != "Completed") {
                                    text +=
                                        `<a class="dropdown-item waves-effect" data-bs-toggle="modal" onclick="deleteModal('${routeDlt}')"
                                                            data-bs-target="#deleteModal" href="javaScript:void(0)"><i class="mdi mdi-trash-can-outline me-1"></i> Delete</a>`;
                                }
                                text += `<a class="dropdown-item waves-effect"  onclick="cancelModal('${id}')"
                                                        href="javaScript:void(0)"><i class="mdi mdi-close me-1"></i> Cancel</a>
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
                                var id = row.id;
                                var routeView = "{{ route('trip.view', ['id' => 'rowID']) }}";
                                routeView = routeView.replace('rowID', id);
                                var text = `<a href="${routeView}">${row.name}</a>`;
                                return text;
                            }
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
                            data: 'start_from',
                            name: 'start_from'
                        }, {
                            data: 'end_to',
                            name: 'end_to'
                        }, {
                            data: 'pax',
                            name: 'pax'
                        }, {
                            name: 'status',
                            "render": function(data, type, row, meta) {
                                var stat = row.statuss;
                                if (stat == "Cancelled") {
                                    var text = "<span class='badge bg-danger'>Cancelled</span>"
                                } else if (stat == "Completed") {
                                    var text = "<span class='badge bg-success'>Completed</span>"
                                } else if (stat == "Ongoing") {
                                    var text = "<span class='badge bg-secondary'>Ongoing</span>"
                                } else if (stat == "Upcomming") {
                                    var text = "<span class='badge bg-warning'>Upcoming</span>"
                                }

                                return text;
                            }
                        },
                        {
                            name: 'status',
                            "render": function(data, type, row, meta) {
                                var stat = row.status;
                                if (stat == "Sold Out") {
                                    var text = "<span class='badge bg-danger'>Yes</span>"
                                } else {
                                    var text = "<span class='badge bg-success'>No</span>"
                                }

                                return text;
                            }
                        }, {
                            data: 'added_by',
                            name: 'added_by'
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

            function cancelModal(id) {
                $('#trip_id').val(id);
                $("#cancelModal").modal('show');
            }
        </script>
    @endsection
