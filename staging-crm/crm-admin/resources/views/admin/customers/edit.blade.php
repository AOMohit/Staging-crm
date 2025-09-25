@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a
                    href="{{ route('customer.index') }}">Customer</a>/</span> Edit</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Customer</h5>
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
                        <form action="{{ route('customer.update') }}" method="post" enctype="multipart/form-data">
                            <div class="row">
                                @csrf
                                <input type="hidden" name="id" value="{{ $data->id }}">
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="first_name" class="form-control"
                                            id="basic-default-fullname" placeholder="First Name"
                                            value="{{ $data->first_name }}" />
                                        <label for="basic-default-fullname">First Name <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="last_name" value="{{ $data->last_name }}"
                                            class="form-control" id="basic-default-fullname" placeholder="Last Name" />
                                        <label for="basic-default-fullname">Last Name <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="email" name="email" value="{{ $data->email }}"
                                            class="form-control" id="basic-default-fullname" placeholder="Email Id" />
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
                                                class="form-control" id="basic-default-fullname" value="{{ $data->phone }}"
                                                placeholder="Contact No" />
                                            <label for="basic-default-fullname">Contact No <span
                                                    class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="gender" class="form-control">
                                            <option @if ($data->gender == 'Male') selected @endif value="Male">Male
                                            </option>
                                            <option @if ($data->gender == 'Female') selected @endif value="Female">Female
                                            </option>
                                            <option @if ($data->gender == 'Other') selected @endif value="Other">Other
                                            </option>
                                        </select>
                                        <label for="basic-default-fullname">Gender <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="date" name="dob" value="{{ $data->dob }}"
                                            class="form-control" id="basic-default-fullname" />
                                        <label for="basic-default-fullname">Date Of Birth <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select onchange="getStateByCountry(this.value)" name="country"
                                            class="form-control">
                                            <option value="{{ $data->country }}">{{ $data->country }}</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->name }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="basic-default-fullname">Country <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating form-floating-outline mb-4 ">
                                        <select name="state" id="states" class="form-control" onfocus="getStateByCountry(document.querySelector('[name=country]').value)">
                                            <option value="{{ $data->state }}">{{ $data->state }}</option>
                                        </select>
                                        <label for="basic-default-fullname">State <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" value="{{ $data->city }}" name="city"
                                            class="form-control" id="basic-default-fullname" placeholder="City " />
                                        <label for="basic-default-fullname">City <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="number" value="{{ $data->pincode }}" name="pincode"
                                            class="form-control" id="basic-default-fullname" placeholder="pincode " />
                                        <label for="basic-default-fullname">pincode <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" value="{{ $data->address }}" name="address"
                                            class="form-control" id="basic-default-fullname" placeholder="Address " />
                                        <label for="basic-default-fullname">Address <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="meal_preference"
                                            value="{{ $data->meal_preference }}" class="form-control"
                                            id="basic-default-fullname" />
                                        <label for="basic-default-fullname">Meal Preference<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                               <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="blood_group"  class="w-100 form-control" id="">
                                            <option value="">—Please choose an option—</option>
                                            <option @if (isset($data) && $data->blood_group == 'a+') selected @endif value="a+">A+</option>
                                            <option @if (isset($data) && $data->blood_group == 'a-') selected @endif value="a-">A-</option>
                                            <option @if (isset($data) && $data->blood_group == 'b+') selected @endif value="b+">B+</option>
                                            <option @if (isset($data) && $data->blood_group == 'b-') selected @endif value="b-">B-</option>
                                            <option @if (isset($data) && $data->blood_group == 'o+') selected @endif value="o+">O+</option>
                                            <option @if (isset($data) && $data->blood_group == 'o-') selected @endif value="o-">O-</option>
                                            <option @if (isset($data) && $data->blood_group == 'ab+') selected @endif value="ab+">AB+</option>
                                            <option @if (isset($data) && $data->blood_group == 'ab-') selected @endif value="ab-">AB-</option>
                                        </select>
                                            <label for="basic-default-fullname">Blood Group <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="profession" value="{{ $data->profession }}"
                                            class="form-control" id="basic-default-fullname" />
                                        <label for="basic-default-fullname">Profession<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="emg_contact" value="{{ $data->emg_contact }}"
                                            class="form-control" id="basic-default-fullname" />
                                        <label for="basic-default-fullname">Emergency Contact<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="emg_name" value="{{ $data->emg_name }}"
                                            class="form-control" id="basic-default-fullname" />
                                        <label for="basic-default-fullname">Emergency Contact Name<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="t_size"  class="w-100 form-control" id="">
                                            <option value="">—Please choose an option—</option>
                                            <option @if (isset($data) && $data->t_size == 'Kids') selected @endif value="Kids">Kids
                                            </option>
                                            <option @if (isset($data) && $data->t_size == 'XS') selected @endif value="XS">XS
                                            </option>
                                            <option @if (isset($data) && $data->t_size == 'S') selected @endif value="S">S
                                            </option>
                                            <option @if (isset($data) && $data->t_size == 'M') selected @endif value="M">M
                                            </option>
                                            <option @if (isset($data) && $data->t_size == 'L') selected @endif value="L">L
                                            </option>
                                            <option @if (isset($data) && $data->t_size == 'XL') selected @endif value="XL">XL
                                            </option>
                                            <option @if (isset($data) && $data->t_size == '2XL') selected @endif value="2XL">2XL
                                            </option>
                                            <option @if (isset($data) && $data->t_size == '3XL') selected @endif value="3XL">3XL
                                            </option>
                                        </select>
                                        <label for="basic-default-fullname">T-Shirt Size <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="medical_condition"
                                            value="{{ $data->medical_condition }}" class="form-control"
                                            id="basic-default-fullname" />
                                        <label for="basic-default-fullname">Medical Condition<span
                                                class="text-danger">*</span></label>
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
