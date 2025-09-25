@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a
                    href="{{ route('inventory.index') }}">Inventory</a>/</span>
            Edit</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Inventory</h5>
                    </div>
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
                        <form action="{{ route('inventory.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{ $data->id }}" name="id">
                            <div class="row col-12">
                                <div class="col-md-6">
                                    <label for="is_tripdata">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <input onchange="tripFound()" @if ($data->trip_id != null) checked @endif
                                                type="checkbox" id="is_tripdata">
                                            Is this product purchased for trip?
                                        </div>
                                    </label>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4" id="tripdata">
                                        <select name="trip_id" class="select2 form-select form-select-lg">
                                            <option value=" ">Select Trip</option>
                                            @foreach ($trips as $trip)
                                                <option @if ($data->trip_id == $trip->id) selected @endif
                                                    value="{{ $trip->id }}">{{ $trip->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="basic-default-fullname">Trip<span class="text-danger">*</span></label>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4 ">
                                        <select name="category_id" class="form-control">
                                            <option value="">Select Category</option>
                                            @foreach ($cats as $cat)
                                                <option @if ($data->category_id == $cat->id) selected @endif
                                                    value="{{ $cat->id }}">{{ $cat->title }}</option>
                                            @endforeach
                                        </select>
                                        <label for="basic-default-fullname">Category<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4 ">
                                        <input type="text" value="{{ $data->title }}" name="title"
                                            class="form-control" id="basic-default-fullname" placeholder="Product Name" />
                                        <label for="basic-default-fullname">Product Name <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-floating form-floating-outline mb-4 ">
                                        <input type="text" value="{{ $data->code }}" name="code"
                                            class="form-control" id="basic-default-fullname" placeholder="Code" />
                                        <label for="basic-default-fullname">Code </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4 ">
                                        <input disabled type="number" value="{{ $data->main_qty }}" name="main_qty"
                                            class="form-control" id="basic-default-fullname" placeholder="Qty" />
                                        <label for="basic-default-fullname">Qty <span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4 ">
                                        <input type="number" value="{{ $data->price }}" name="price"
                                            class="form-control" id="basic-default-fullname" placeholder="Price/Unit" />
                                        <label for="basic-default-fullname">Price/Unit<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4 ">
                                        <input type="number" value="{{ $data->tax }}" name="tax"
                                            class="form-control" id="basic-default-fullname" placeholder="Tax(%)" />
                                        <label for="basic-default-fullname">Tax(%)</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4 ">
                                        <select onchange="purchaseFrom(this.value)" name="purchase_from" id="purchase_from"
                                            class="form-control">
                                            <option value="">Select Purchased From</option>
                                            <option @if ($data->purchase_from == 'Online') selected @endif value="Online">Online
                                            </option>
                                            <option @if ($data->purchase_from == 'Offline') selected @endif value="Offline">
                                                Offline
                                            </option>
                                        </select>
                                        <label for="basic-default-fullname">Purchased From <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="vendor" class="form-floating form-floating-outline mb-4 ">
                                        <select name="vendor_id" class="form-control">
                                            <option value="">Select Vendor</option>
                                            @foreach ($vendors as $vendor)
                                                <option @if ($data->vendor_id == $vendor->id) selected @endif
                                                    value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="basic-default-fullname">Vendor <span
                                                class="text-danger">*</span></label>
                                    </div>

                                    <div id="source" class="form-floating form-floating-outline mb-4 ">
                                        <input type="text" value="{{ $data->source }}" name="source"
                                            class="form-control" id="basic-default-fullname" placeholder="Source " />
                                        <label for="basic-default-fullname">Source <span
                                                class="text-danger">*</span></label>
                                    </div>

                                </div>

                                <div class="col-12">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input value="{{ $data->description }}" type="text" name="description"
                                            class="form-control" id="basic-default-fullname"
                                            placeholder="Description " />
                                        <label for="basic-default-fullname">Description</label>
                                    </div>
                                </div>


                                <div class="form-floating form-floating-outline mb-4 ">
                                    <input type="file" name="image" class="form-control"
                                        id="basic-default-fullname" placeholder=" Image" />
                                    <label for="basic-default-fullname">Image</label>
                                </div>

                                <div class="form-floating form-floating-outline mb-4 ">
                                    <input type="file" name="file" class="form-control"
                                        id="basic-default-fullname" placeholder=" Invoice" />
                                    <label for="basic-default-fullname">Invoice</label>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    <style>
        #vendor,
        #source,
        #tripdata {
            display: none;
        }
    </style>

    @if ($data->trip_id != null)
        <style>
            #tripdata {
                display: block;
            }
        </style>
    @endif

    @section('script')
        <script>
            function tripFound() {
                if (document.getElementById('is_tripdata').checked) {
                    document.getElementById("tripdata").style.display = "block";
                } else {
                    document.getElementById("tripdata").style.display = "none";
                }
            }
        </script>

        <script>
            $(document).ready(function() {
                purchaseFrom($("#purchase_from").val());
            });

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
