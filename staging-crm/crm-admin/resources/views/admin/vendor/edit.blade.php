@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a
                    href="{{ route('vendors.index') }}">Vendor</a>/</span> Edit</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Vendor</h5>
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
                        <form action="{{ route('vendors.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <div class="row col-12">
                                <div class="form-floating form-floating-outline mb-4 col-6">
                                    <input value="{{ $data->first_name }}" type="text" name="first_name"
                                        class="form-control" id="basic-default-fullname" placeholder="First Name" />
                                    <label for="basic-default-fullname">First Name <span
                                            class="text-danger">*</span></label>
                                </div>

                                <div class="form-floating form-floating-outline mb-4 col-6">
                                    <input type="text" value="{{ $data->last_name }}" name="last_name"
                                        class="form-control" id="basic-default-fullname" placeholder="Last Name" />
                                    <label for="basic-default-fullname">Last Name</label>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="form-floating form-floating-outline mb-4 col-6">
                                    <input type="email" value="{{ $data->email }}" name="email" class="form-control"
                                        id="basic-default-fullname" placeholder="Email Id" />
                                    <label for="basic-default-fullname">Email Id <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-4">
                                    <div class="form-floating form-floating-outline">
                                        <select name="telephone_code" class="form-control" required>
                                            <option value="">Select</option>
                                            @foreach (getTelephoneCode() as $code)
                                                <option @if ($data->telephone_code == $code->country_code) selected @endif
                                                    value="{{ $code->country_code }}">
                                                    {{ $code->country_name . ' (' . $code->country_code . ')' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="basic-default-fullname">Country Code <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="phone" oninput="validateNumberInput(event)"
                                            class="form-control" value="{{ $data->phone }}" id="basic-default-fullname"
                                            placeholder="Contact No" />
                                        <label for="basic-default-fullname">Contact No <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="form-floating form-floating-outline mb-4 col-6">
                                    <input type="text" value="{{ $data->company }}" name="company" class="form-control"
                                        id="basic-default-fullname" placeholder="Company Name " />
                                    <label for="basic-default-fullname">Company Name <span
                                            class="text-danger">*</span></label>
                                </div>

                                <div class="form-floating form-floating-outline mb-4 col-6">
                                    <select onchange="getStateByCountry(this.value)" name="country" class="form-control">
                                        <option value="{{ $data->country }}">{{ $data->country }}</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->name }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="basic-default-fullname">Country <span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="form-floating form-floating-outline mb-4 col-6">
                                    <select name="state" id="states" class="form-control">
                                        <option value="{{ $data->state }}">{{ $data->state }}</option>
                                    </select>
                                    <label for="basic-default-fullname">State </label>
                                </div>

                                <div class="form-floating form-floating-outline mb-4 col-6">
                                    <input type="text" value="{{ $data->city }}" name="city" class="form-control"
                                        id="basic-default-fullname" placeholder="City " />
                                    <label for="basic-default-fullname">City </label>
                                </div>
                            </div>

                            <div class="col-12 row">
                                <div class="form-floating form-floating-outline mb-4 col-6">
                                    <input type="text" value="{{ $data->pincode }}" name="pincode"
                                        class="form-control" id="basic-default-fullname" placeholder="Pincode " />
                                    <label for="basic-default-fullname">Pincode </label>
                                </div>

                                <div class="form-floating form-floating-outline mb-4 col-6">
                                    <input type="text" value="{{ $data->address }}" name="address"
                                        class="form-control" id="basic-default-fullname" placeholder="Address " />
                                    <label for="basic-default-fullname">Address </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-floating form-floating-outline">
                                        <select name="vendor_type[]" id="vendor_types"
                                            class="select2 form-select form-select-lg" data-allow-clear="true">
                                            @foreach ($cats as $cat)
                                                <option @if (isset($data) && isset($data->vendor_type) && in_array($cat->id, json_decode($data->vendor_type))) selected @endif
                                                    value="{{ $cat->id }}">
                                                    {{ $cat->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="vendor_types">Vendor Category<span
                                                class="text-danger fixed">*</span></label>
                                    </div>
                                    <div class="text-end">
                                        <a onclick="addNewCategory()" href="javaScript:void(0)"
                                            class="btn btn-warning btn-sm">+ Add New Category</a>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-floating form-floating-outline">
                                        <select name="service_id[]" id="service_ids"
                                            class="select2 form-select form-select-lg" data-allow-clear="true" multiple>
                                            @foreach ($services as $service)
                                                <option @if (isset($data) && isset($data->service_id) && in_array($service->id, json_decode($data->service_id))) selected @endif
                                                    value="{{ $service->id }}">
                                                    {{ $service->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="service_ids">Vendor Service<span
                                                class="text-danger fixed">*</span></label>
                                    </div>
                                    <div class="text-end">
                                        <a onclick="addNewService()" href="javaScript:void(0)"
                                            class="btn btn-warning btn-sm">+ Add New Service</a>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="gst" value="{{ $data->gst }}"
                                            class="form-control" id="basic-default-fullname" placeholder="GST " />
                                        <label for="basic-default-fullname">GST</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating form-floating-outline mb-4 ">
                                <input type="file" name="image" class="form-control" id="basic-default-fullname"
                                    placeholder="Profile Image" />
                                <label for="basic-default-fullname">Profile Image</label>
                            </div>

                            <img style="height: 100px;" src="{{ url('storage/app/' . $data->image) }}" alt="">

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for add New Category -->
        <div class="modal fade" id="addNewCategoryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Add New Category</h5>
                        </div>
                        <div class="card-body">
                            <form id="addNewCategoryForm">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <input type="text" name="title" class="form-control"
                                                id="basic-default-fullname" placeholder="Title" />
                                            <label for="basic-default-fullname">Title<span
                                                    class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <a href="javaScript:void(0)" onclick="saveNewCategory()"
                                        class="btn btn-primary">Submit</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for add New Service -->
        <div class="modal fade" id="addNewServiceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Add New Service</h5>
                        </div>
                        <div class="card-body">
                            <form id="addNewServiceForm">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <input type="text" name="title" class="form-control"
                                                id="basic-default-fullname" placeholder="Title" required />
                                            <label for="basic-default-fullname">Title<span
                                                    class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <a href="javaScript:void(0)" onclick="saveNewService()"
                                        class="btn btn-primary">Submit</a>
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
            function addNewCategory() {
                $("#addNewCategoryModal").modal('show');
            }

            function addNewService() {
                $("#addNewServiceModal").modal('show');
            }
        </script>

        <script>
            function saveNewCategory() {
                var form = document.querySelector("#addNewCategoryForm");
                var formData = new FormData(form);

                var isEmpty = false;
                form.querySelectorAll("input, select, textarea").forEach(function(input) {
                    if (input.value.trim() === "") {
                        isEmpty = true;
                    }
                });

                if (isEmpty) {
                    Toast.fire({
                        icon: "error",
                        title: "Field is Required!"
                    });
                    return; // Stop the form from being submitted
                }

                $.ajax({
                    url: "{{ route('setting.vendor_category.store') }}",
                    method: "POST",
                    data: formData,
                    processData: false, // Prevent jQuery from processing the data
                    contentType: false, // Prevent jQuery from setting the content-type header
                    success: function(res) {
                        $("#vendor_type").append(res);
                        Toast.fire({
                            icon: "success",
                            title: "Data Saved Successfully."
                        });
                        $("#addNewCategoryModal").modal('hide');
                        form.reset();
                    },
                    error: function(err) {
                        console.log(err); // Handle the error here if needed
                    }
                });
            }

            function saveNewService() {
                var form = document.querySelector("#addNewServiceForm");
                var formData = new FormData(form);

                var isEmpty = false;
                form.querySelectorAll("input, select, textarea").forEach(function(input) {
                    if (input.value.trim() === "") {
                        isEmpty = true;
                    }
                });

                if (isEmpty) {
                    Toast.fire({
                        icon: "error",
                        title: "Field is Required!"
                    });
                    return; // Stop the form from being submitted
                }

                $.ajax({
                    url: "{{ route('setting.vendor_service.store') }}",
                    method: "POST",
                    data: formData,
                    processData: false, // Prevent jQuery from processing the data
                    contentType: false, // Prevent jQuery from setting the content-type header
                    success: function(res) {
                        $("#service_id").append(res);
                        Toast.fire({
                            icon: "success",
                            title: "Data Saved Successfully."
                        });
                        $("#addNewServiceModal").modal('hide');
                        form.reset();
                    },
                    error: function(err) {
                        console.log(err); // Handle the error here if needed
                    }
                });
            }
        </script>
    @endsection
