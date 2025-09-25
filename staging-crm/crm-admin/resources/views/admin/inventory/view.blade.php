@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">


        <div class="">
            <div class="dt-action-buttons text-end pt-md-0 d-flex" style="justify-content: space-between !important;">
                <div>
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a
                                href="{{ route('inventory.index') }}">Inventory
                                Stock</a> /
                            view
                    </h4>
                </div>
                @if ($check->trip)
                    <div class="">
                        <a onclick="addInventoryModal({{ $check->trip->id }})"
                            class="btn btn-secondary btn-primary text-white" href="javaScript:void(0)"
                            tabindex="0"><span><i class="mdi mdi-plus me-sm-1"></i>
                                <span class="d-none d-sm-inline-block">Add Inventory</span></span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body row">
                        <div class="col-md-12">
                            @if ($check->trip)
                                <p><b>Trip</b>: {{ $check->trip->name ?? null }}</p>
                            @endif
                            <p><b>Product Name</b>: {{ $data->title }}</p>
                            <p><b>Product Category</b>: {{ $data->category_name }}</p>
                            <p><b>Purchased Total Qty</b>: {{ $data->main_qty }}</p>
                            <p><b>Remaining Qty</b>: {{ $data->qty }}</p>
                            <p><b>Total Cost</b>: ₹{{ $data->total_price }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTable with Buttons -->
        <div class="card mt-4">
            <div class="card-datatable table-responsive pt-0">
                <table id="myDatatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Added Date</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Purchased Total Qty</th>
                            <th>Remaining Qty</th>
                            <th>Cost/Unit</th>
                            <th>Total Cost</th>
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

    {{-- add inventory confirmation --}}
    <div class="modal fade" id="addInventoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('inventory.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row col-12">
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select id="tripdata" name="trip_id" class="form-control">
                                            <option value=" ">Select Trip</option>
                                            @foreach ($trips as $trip)
                                                <option value="{{ $trip->id }}">{{ $trip->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="basic-default-fullname">Trip<span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class=" col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="category_id" class="form-control" required>
                                            <option value="">Select Category</option>
                                            @foreach ($cats as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                            @endforeach
                                        </select>
                                        <label for="basic-default-fullname">Category<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class=" col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input required type="text" name="title" class="form-control"
                                            id="basic-default-fullname" placeholder="Product Name" />
                                        <label for="basic-default-fullname">Product Name <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class=" col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="code" class="form-control"
                                            id="basic-default-fullname" placeholder="Code" />
                                        <label for="basic-default-fullname">Code </label>
                                    </div>
                                </div>
                                <div class=" col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input required type="number" name="qty" class="form-control"
                                            id="basic-default-fullname" placeholder="Qty" />
                                        <label for="basic-default-fullname">Qty <span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class=" col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input required type="number" name="price" class="form-control"
                                            id="basic-default-fullname" placeholder="Price/Unit" />
                                        <label for="basic-default-fullname">Price/Unit<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class=" col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="number" name="tax" class="form-control"
                                            id="basic-default-fullname" placeholder="Tax(%)" />
                                        <label for="basic-default-fullname">Tax(%)</label>
                                    </div>
                                </div>

                                <div class=" col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select required onchange="purchaseFrom(this.value)" name="purchase_from"
                                            class="form-control">
                                            <option value="">Select Purchased From</option>
                                            <option value="Online">Online</option>
                                            <option value="Offline">Offline</option>
                                        </select>
                                        <label for="basic-default-fullname">Purchased From <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class=" col-md-12">
                                    <div id="vendor" class="form-floating form-floating-outline mb-4">
                                        <select name="vendor_id" class="form-control">
                                            <option value="">Select Vendor</option>
                                            @foreach ($vendors as $vendor)
                                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="basic-default-fullname">Vendor <span
                                                class="text-danger">*</span></label>
                                    </div>

                                    <div id="source" class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="source" class="form-control"
                                            id="basic-default-fullname" placeholder="Source " />
                                        <label for="basic-default-fullname">Source <span
                                                class="text-danger">*</span></label>
                                    </div>

                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="description" class="form-control"
                                            id="basic-default-fullname" placeholder="Description " />
                                        <label for="basic-default-fullname">Description</label>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline mb-4 ">
                                        <input type="file" name="image" class="form-control"
                                            id="basic-default-fullname" placeholder=" Image" />
                                        <label for="basic-default-fullname">Image</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline mb-4 ">
                                        <input type="file" name="file" class="form-control"
                                            id="basic-default-fullname" placeholder=" Invoice" />
                                        <label for="basic-default-fullname">Invoice</label>
                                    </div>
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
@endsection

<style>
    #vendor,
    #source {
        display: none;
    }
</style>

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
                    "url": "{!! route('inventory.view-product') !!}",
                    "type": 'GET',
                    "data": {
                        "user_type": "admin",
                        "id": "{{ request()->id }}"
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        name: 'Action',
                        "render": function(data, type, row, meta) {
                            var id = row.id;
                            var routeEdit = "{{ route('inventory.edit', ['id' => 'rowID']) }}";
                            routeEdit = routeEdit.replace('rowID', id);

                            var routeDlt = "{{ route('inventory.delete', ['id' => 'rowID']) }}";
                            routeDlt = routeDlt.replace('rowID', id);

                            var routeView = "{{ route('inventory.view', ['id' => 'rowID']) }}";
                            routeView = routeView.replace('rowID', id);

                            var routeActivity = "{{ route('inventory.activity', ['id' => 'rowID']) }}";
                            routeActivity = routeActivity.replace('rowID', id);

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
                    },
                    {
                        name: 'title',
                        "render": function(data, type, row, meta) {
                            var id = row.id;

                            var routeView = "{{ route('inventory.view-details', ['id' => 'rowID']) }}";
                            routeView = routeView.replace('rowID', id);

                            var text =
                                `<a class="dropdown-item waves-effect text-primary" href="${routeView}">${row.title}</a>`;
                            return text;
                        }
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'main_qty',
                        name: 'main_qty'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        name: 'price',
                        "render": function(data, type, row, meta) {
                            var text =
                                `₹${row.price}`;
                            return text;
                        }
                    },
                    {
                        name: 'total',
                        "render": function(data, type, row, meta) {
                            var text =
                                `₹${row.price * row.main_qty}`;
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
    <script>
        function addInventoryModal(id) {
            $("#addInventoryModal").modal("show");
            $("#tripdata").val(id);
        }

        function purchaseFrom(val) {
            if (val == "Online") {
                $("#source").show();
                $("#vendor").hide();
            } else if (val == "Offline") {
                $("#vendor").show();
                $("#source").hide();
            } else {
                $("#source").hide();
                $("#vendor").hide();
            }
        }
    </script>
@endsection
