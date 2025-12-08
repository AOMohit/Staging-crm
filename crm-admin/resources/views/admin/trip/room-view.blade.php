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
                            <th>Hotel Name</th>
                            <th>Hotel Room</th>
                            <th>Hotel Place</th>
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

    {{-- Edit Room Modal --}}
    <div class="modal fade" id="editRoomModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('trip.details.room-update') }}">
                @csrf
                <input type="hidden" name="room_id" id="edit_room_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Room</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Hotel Name</label>
                            <input type="text" name="hotel_name" id="edit_hotel_name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Hotel Room</label>
                            <input type="text" name="hotel_room" id="edit_hotel_room" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Hotel Place</label>
                            <input type="text" name="hotel_place" id="edit_hotel_place" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Comment</label>
                            <input type="text" name="comment" id="edit_comment" class="form-control">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </div>
            </form>
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
                    "url": "{!! route('trip.details.room-get') !!}",
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
                        data: 'hotel_name',
                        name: 'hotel_name'
                    }, {
                        data: 'hotel_room',
                        name: 'hotel_room'
                    }, {
                        data: 'hotel_place',
                        name: 'hotel_place'
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

                            var routeDlt = "{{ route('trip.details.room-delete', ['id' => 'rowID']) }}";
                            routeDlt = routeDlt.replace('rowID', id);

                            var text = `<div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" style="">
                                    <!-- ✅ EDIT -->
                                    <a class="dropdown-item waves-effect" href="javascript:void(0)" onclick="openEditModal(${row.id}, '${row.hotel_name}', '${row.hotel_room}', '${row.hotel_place}', '${row.comment ?? ''}')">
                                        <i class="mdi mdi-pencil-outline me-1"></i> Edit
                                    </a>
                                    <a class="dropdown-item waves-effect" data-bs-toggle="modal" onclick="deleteModal('${routeDlt}')" data-bs-target="#deleteModal" href="javaScript:void(0)">
                                        <i class="mdi mdi-trash-can-outline me-1"></i> Delete
                                    </a>
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

        function openEditModal(id, hotelName, hotelRoom, hotelPlace, comment) {
            $('#edit_room_id').val(id);
            $('#edit_hotel_name').val(hotelName);
            $('#edit_hotel_room').val(hotelRoom);
            $('#edit_hotel_place').val(hotelPlace);
            $('#edit_comment').val(comment);

            $('#editRoomModal').modal('show');
        }

    </script>
@endsection
