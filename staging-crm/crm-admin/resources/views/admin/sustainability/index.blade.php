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
                                    <option value="">Select Trip Type</option>
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
                                <button onclick="getfilterdata()" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                        <hr>

                        {{-- filter --}}
                    </div>
                    <table id="myDatatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Trip Creation Date</th>
                                <th>Trip Name</th>
                                <th>Status</th>
                                <th>Total Traveler</th>
                                <th>Opt In</th>
                                <th>Opt Out</th>
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
                        "url": "{!! route('sustainability.get') !!}",
                        "type": 'GET',
                        "data": {
                            "user_type": "admin",
                            "name": name,
                            "trip_type": trip_type,
                            "status": status,
                            "admin": admin,
                            "date": date,
                        }
                    },
                    "serverSide": true,
                    "deferRender": true,
                    "columns": [{
                            name: 'Action',
                            "render": function(data, type, row, meta) {
                                var id = row.id;

                                var routeView = "{{ route('sustainability.view', ['id' => 'rowID']) }}";
                                routeView = routeView.replace('rowID', id);

                                var text = `<div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="mdi mdi-dots-vertical"></i>
                                                </button>
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
                        },
                        {
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
                                    var text = "<span class='badge bg-warning'>Upcomming</span>"
                                }

                                return text;
                            }
                        },
                        {
                            data: 'pax',
                            name: 'pax'
                        },
                        {
                            data: 'opt_in',
                            name: 'opt_in'
                        },
                        {
                            data: 'opt_out',
                            name: 'opt_out'
                        },
                        {
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
