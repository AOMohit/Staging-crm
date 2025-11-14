@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="{{ route('agent.index') }}">Agent</a>/</span>
            Add</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add Agent</h5>
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
                        <form action="{{ route('agent.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row col-12">
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="first_name" class="form-control"
                                            id="basic-default-fullname" placeholder="First Name" />
                                        <label for="basic-default-fullname">First Name <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="last_name" class="form-control"
                                            id="basic-default-fullname" placeholder="Last Name" />
                                        <label for="basic-default-fullname">Last Name <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="email" name="email" class="form-control"
                                            id="basic-default-fullname" placeholder="Email Id" />
                                        <label for="basic-default-fullname">Email Id <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-4">
                                        <div class="form-floating form-floating-outline">
                                            <select name="telephone_code" class="form-control" required>
                                                <option value="">Select</option>
                                                @foreach (getTelephoneCode() as $code)
                                                    <option value="{{ $code->country_code }}">
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
                                                class="form-control" id="basic-default-fullname" placeholder="Contact No" />
                                            <label for="basic-default-fullname">Contact No <span
                                                    class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4 ">
                                        <input type="text" name="agency" class="form-control"
                                            id="basic-default-fullname" placeholder="Agency Name " />
                                        <label for="basic-default-fullname">Agency Name <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-floating form-floating-outline mb-4">
                                        <select onchange="getStateByCountry(this.value)" name="country"
                                            class="form-control">
                                            <option value="">Select Country</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->name }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="basic-default-fullname">Country <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="state" id="states" class="form-control">
                                            <option value="">Select State</option>
                                        </select>
                                        <label for="basic-default-fullname">State <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="city" class="form-control"
                                            id="basic-default-fullname" placeholder="City " />
                                        <label for="basic-default-fullname">City <span class="text-danger">*</span></label>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="pincode" class="form-control"
                                            id="basic-default-fullname" placeholder="Pincode " />
                                        <label for="basic-default-fullname">Pincode <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="address" class="form-control"
                                            id="basic-default-fullname" placeholder="Address " />
                                        <label for="basic-default-fullname">Address <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="gst" class="form-control"
                                            id="basic-default-fullname" placeholder="GST " />
                                        <label for="basic-default-fullname">GST</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline mb-4 ">
                                        <input type="file" name="image" class="form-control"
                                            id="basic-default-fullname" placeholder="Profile Image" />
                                        <label for="basic-default-fullname">Profile Image</label>
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
    @endsection
