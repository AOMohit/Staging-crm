@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a
                    href="{{ route('inventory.index') }}">Inventory</a>/</span>
            Add</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add Inventory</h5>
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
                        <form action="{{ route('inventory.details.stockHistoryUpdate') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <div class="row">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select name="type" class="form-control" required>
                                        <option value="">Stock Type</option>
                                        <option @if ($data->type == 'In') selected @endif value="In">In</option>
                                        <option @if ($data->type == 'Out') selected @endif value="Out">Out
                                        </option>
                                    </select>
                                    <label for="basic-default-fullname">Stock Type <span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="form-floating form-floating-outline mb-4 " id="">
                                <input type="number" name="qty" value="{{ $data->qty }}" class="form-control"
                                    id="basic-default-fullname" placeholder="Qty" />
                                <label for="basic-default-fullname">Qty <span class="text-danger">*</span></label>
                                <small>Balance Stock Qty <span id="availableQty">{{ $data->inventory->qty }}</span></small>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <select name="stock_for" class="form-control">
                                    <option value="">Stock For</option>
                                    @foreach ($trips as $trip)
                                        <option @if ($data->stock_for == $trip->id) selected @endif
                                            value="{{ $trip->id }}">{{ $trip->name }}</option>
                                    @endforeach
                                </select>
                                <label for="basic-default-fullname">Stock For <span class="text-danger"></span></label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <select name="given_to" class="form-control">
                                    <option value="">Given to</option>
                                    @foreach ($users as $user)
                                        <option @if ($data->given_to == $user->id) selected @endif
                                            value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <label for="basic-default-fullname">Given to <span class="text-danger"></span></label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4 ">
                                <input type="text" name="comment" value="{{ $data->comment }}" class="form-control"
                                    id="basic-default-fullname" placeholder="Comment" />
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
    @endsection

    <style>
        #vendor,
        #source {
            display: none;
        }
    </style>

    @section('script')
        <script>
            document.getElementById("tripdata").style.display = "none";

            function tripFound() {
                if (document.getElementById('is_tripdata').checked) {
                    document.getElementById("tripdata").style.display = "block";
                } else {
                    document.getElementById("tripdata").style.display = "none";
                }
            }
        </script>

        <script>
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
