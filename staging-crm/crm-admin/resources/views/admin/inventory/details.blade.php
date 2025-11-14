@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="{{ route('inventory.index') }}"> Stock</a>/ <a
                    href="{{ route('inventory.view', request()->id) }}"> Stock Details</a> /
                view
        </h4>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-datatable table-responsive pt-0 text-center mt-3">
                        @if ($data->image)
                            <img style="height: 190px;width: 190px;" class="img-fluid rounded"
                                src="{{ url('storage/app/' . $data->image) }}" alt="">
                        @else
                            <img class="img-fluid rounded" src="{{ url('public/admin') }}/assets/img/avatars/10.png"
                                alt="{{ $data->title }}" />
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-body row">
                        <div class="col-md-6">
                            <p><b>Product Name</b>: {{ $data->title }}</p>
                            <p><b>Product Category</b>: {{ $data->category->title }}</p>
                            <p><b>Purchased Total Qty</b>: {{ $data->main_qty }}</p>
                            <p><b>Remaining Qty</b>: {{ $data->qty }}</p>
                            <p><b>Price</b>: ₹{{ $data->price }}</p>
                            <p><b>Tax</b>: {{ $data->tax }}%</p>
                        </div>
                        <div class="col-md-6">
                            <p><b>Purchase From</b>: {{ $data->purchase_from }} @isset($data->source)
                                    ({{ $data->source }})
                                @endisset
                            </p>
                            <p><b>Vendor</b>: {{ $data->vendor->first_name ?? null }}</p>
                            <p><b>Trip</b>: {{ $data->trip->name ?? null }}</p>
                            <p><b>Description</b>: {{ $data->description }}</p>
                            <p><b>Invoice</b>: <a target="_blank" href="{{ url('storage/app/' . $data->file) }}">View</a>
                                </h5>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- DataTable with Buttons -->
        <div class="card mt-4">
            <div class="card-datatable table-responsive pt-0">
                <div class="p-3">
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <div class="dt-buttons btn-group flex-wrap">

                            <a href="javaScript:void(0)" onclick="updateStock()"
                                class="btn btn-secondary buttons-collection btn-label-primary me-2" tabindex="0"
                                aria-controls="DataTables_Table_0" type="button" aria-haspopup="dialog"
                                aria-expanded="false"><span> <span class="d-none d-sm-inline-block">Update
                                        Stock</span></span></span>
                            </a>

                        </div>
                    </div>
                </div>
                <table id="myDatatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Spoc</th>
                            <th>Stock</th>
                            <th>For</th>
                            <th>Given To</th>
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

    <!-- Modal for update Stock -->
    <div class="modal fade" id="stockUpdate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Update Stock</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('inventory.details.stockUpdate') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="inventory_id" value="{{ request()->id }}">
                            <div class="row">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select name="type" class="form-control" required>
                                        <option value="">Stock Type</option>
                                        <option value="In">In</option>
                                        <option value="Out">Out</option>
                                    </select>
                                    <label for="basic-default-fullname">Stock Type <span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="form-floating form-floating-outline mb-4 " id="">
                                <input type="number" name="qty" class="form-control" id="basic-default-fullname"
                                    placeholder="Qty" />
                                <label for="basic-default-fullname">Qty <span class="text-danger">*</span></label>
                                <small>Balance Stock Qty <span id="availableQty">100</span></small>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <select name="stock_for" class="select2 form-select form-select-lg">
                                    <option value=" ">Stock For</option>
                                    @foreach ($trips as $trip)
                                        <option value="{{ $trip->id }}">{{ $trip->name }}</option>
                                    @endforeach
                                </select>
                                <label for="basic-default-fullname">Stock For <span class="text-danger"></span></label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <select name="given_to" class="select2 form-select form-select-lg">
                                    <option value=" ">Given to</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <label for="basic-default-fullname">Given to <span class="text-danger"></span></label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4 ">
                                <input type="text" name="comment" class="form-control" id="basic-default-fullname"
                                    placeholder="Comment" />
                                <label for="basic-default-fullname">Comment</label>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
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
        function updateStock() {
            var inventory_id = "{{ request()->id }}";
            $.ajax({
                url: "{{ route('inventory.details.stock') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    inventory_id: inventory_id
                },
                success: function(res) {
                    $("#availableQty").text(res);
                    $("#stockUpdate").modal('show');
                }
            });
        }
    </script>

    <script>
        $('th').css('white-space', 'nowrap');

        $(document).ready(function() {
            getfilterdata();
        });

        function getfilterdata() {
            var inventory_id = "{{ request()->id }}";

            var table = $('#myDatatable').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('inventory.details.get') !!}",
                    "type": 'GET',
                    "data": {
                        "inventory_id": inventory_id,
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        data: 'created',
                        name: 'created'
                    }, {
                        data: 'type',
                        name: 'type'
                    }, {
                        data: 'admin_name',
                        name: 'admin_name'
                    },
                    {
                        name: 'qty',
                        "render": function(data, type, row, meta) {
                            if (row.type == "In") {
                                var text = `<span class="text-success">+${row.qty}</span>`;
                            } else if (row.type == "Out") {
                                var text = `<span class="text-danger">-${row.qty}</span>`;
                            }
                            return text;
                        }
                    },
                    {
                        data: 'stock_for_trip',
                        name: 'stock_for'
                    },
                    {
                        data: 'given_to_user',
                        name: 'given_to'
                    },
                    {
                        name: 'Action',
                        "render": function(data, type, row, meta) {
                            var id = row.id;
                            var routeEdit = "{{ route('inventory.edit-stock', ['id' => 'rowID']) }}";
                            routeEdit = routeEdit.replace('rowID', id);

                            var routeDlt = "{{ route('inventory.delete-stock', ['id' => 'rowID']) }}";
                            routeDlt = routeDlt.replace('rowID', id);

                            var routeActivity =
                                "{{ route('inventory.activity-stock', ['id' => 'rowID']) }}";
                            routeActivity = routeActivity.replace('rowID', id);

                            var text = `<div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" style="">
                                <a class="dropdown-item waves-effect" href="${routeActivity}"><i class="mdi mdi-book-outline me-1"></i> Activity Log</a>
                                <a class="dropdown-item waves-effect" href="${routeEdit}"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
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
