@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a
                    href="{{ route('customer.index') }}">Customer</a>/</span> Add</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add Customer</h5>
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
                        <form action="{{ route('customer.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="first_name" class="form-control" id="basic-default-fullname"
                                    placeholder="First Name" />
                                <label for="basic-default-fullname">First Name <span class="text-danger">*</span></label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="last_name" class="form-control" id="basic-default-fullname"
                                    placeholder="Last Name" />
                                <label for="basic-default-fullname">Last Name <span class="text-danger">*</span></label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="email" name="email" class="form-control" id="basic-default-fullname"
                                    placeholder="Email Id" />
                                <label for="basic-default-fullname">Email Id <span class="text-danger">*</span></label>
                            </div>

                            <div class="row mb-4">
                                <div class="col-4">
                                    <div class="form-floating form-floating-outline">
                                        <select name="telephone_code" class="form-control" required>
                                            <option value="">Select</option>
                                            @foreach (getTelephoneCode() as $code)
                                                <option value="{{ $code->country_code }}">
                                                    {{ $code->country_name . ' (' . $code->country_code . ')' }}</option>
                                            @endforeach
                                        </select>
                                        <label for="basic-default-fullname">Country Code<span
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

                            <div class="form-floating form-floating-outline mb-4">
                                <select name="gender" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <label for="basic-default-fullname">Gender <span class="text-danger">*</span></label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input onchange="referValidate()" type="checkbox" id="is_refered">
                                Is this customer referred by other customer?
                            </div>

                            <div class="form-floating form-floating-outline mb-4 " id="referal">
                                <input type="text" name="refer_by" class="form-control" id="basic-default-fullname"
                                    placeholder="Referal Email" />
                                <label for="basic-default-fullname">Referal Email</label>
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

    @section('script')
        <script>
            document.getElementById("referal").style.display = "none";

            function referValidate() {
                if (document.getElementById('is_refered').checked) {
                    document.getElementById("referal").style.display = "block";
                } else {
                    document.getElementById("referal").style.display = "none";
                }
            }
        </script>
    @endsection
