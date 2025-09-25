@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table id="myDatatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Booking ID</th>
                            <th>Travelers</th>
                            <th>Vehicle </th>
                            <th>Added By</th>
                            <th>Comment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--/ DataTable with Buttons -->

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

            var table = $('#myDatatable').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('trip.details.vehicle-get') !!}",
                    "type": 'GET',
                    "data": {
                        "booking_id": "{{ request()->id }}",
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        data: 'created',
                        name: 'created'
                    }, {
                        name: 'booking_id',
                        "render": function(data, type, row, meta) {
                            var text = "#" + row.booking_id;
                            return text;
                        }
                    }, {
                        name: 'traveler',
                        "render": function(data, type, row, meta) {
                            var text = "";
                            $.each(row.customers, function(index, value) {
                                text += `<div>${value.name}</div>`;
                            });
                            return text;
                        }
                    }, {
                        data: 'vehicle_no',
                        name: 'vehicle_no'
                    }, {
                        data: 'added_by',
                        name: 'added_by'
                    }, {
                        data: 'comment',
                        name: 'comment'
                    },
                    {
                        name: 'Action',
                        "render": function(data, type, row, meta) {
                            var id = row.id;

                            var routeDlt = "{{ route('trip.details.vehicle-delete', ['id' => 'rowID']) }}";
                            routeDlt = routeDlt.replace('rowID', id);

                            var text = `<div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" style="">
                                <a class="dropdown-item waves-effect" data-bs-toggle="modal" onclick="deleteModal('${routeDlt}')"
                                            data-bs-target="#deleteModal" href="javaScript:void(0)"><i class="mdi mdi-trash-can-outline me-1"></i> Delete</a>
                                </div>
                                
                            </div>`;
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
