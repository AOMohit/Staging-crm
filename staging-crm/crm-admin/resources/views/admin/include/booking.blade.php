<!-- Modal for add customer -->
<div class="modal fade" id="customerAdd" tabindex="-1" aria-hidden="true" style="@if ($errors->any()) display: block; @endif">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add Customer</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="popup" value="1">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" name="first_name" class="form-control"
                                        id="basic-default-fullname" placeholder="First Name"  />
                                    <label for="basic-default-fullname">First Name <span
                                            class="text-danger">*</span></label>
                                       
                                            
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" name="last_name" class="form-control"
                                        id="basic-default-fullname" placeholder="Last Name"  />
                                    <label for="basic-default-fullname">Last Name <span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="email" name="email" class="form-control"
                                        id="basic-default-fullname" placeholder="Email Id"  />
                                    <label for="basic-default-fullname">Email Id <span
                                            class="text-danger">*</span></label>
                                            
                                </div>
                            </div>
                            <!--<div class="col-6">-->

                            <!--    <div class="form-floating form-floating-outline mb-4">-->
                            <!--        <input type="text" name="phone" class="form-control"-->
                            <!--            id="basic-default-fullname" placeholder="Contact No" required />-->
                            <!--        <label for="basic-default-fullname">Contact No <span-->
                            <!--                class="text-danger">*</span></label>-->
                            <!--    </div>-->
                            <!--</div>-->
                        </div>
                        <div class="row mb-4">
                            <div class="col-4">
                                <div class="form-floating form-floating-outline">
                                    <select name="telephone_code" class="form-control" >
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
                            <select name="gender" class="form-control" >
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
                        <input type="hidden" name="trip_cost"
                        value="{{ isset($data) && $data->trip_id ? getTripById($data->trip_id)->price : '' }}">
                          
                        </span></small>
                        <div class="form-floating form-floating-outline mb-4 " id="referal">
                            <input type="text" name="refer_by" class="form-control" id="basic-default-fullname"
                                placeholder="Referal Email" />
                            <label for="basic-default-fullname">Referal Email</label>
                        </div>
                        <input type="hidden" name="trip_name"
                        value="{{ isset($data) && $data->trip_id ? getTripById($data->trip_id)->name : '' }}">

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for add customer minor -->
<div class="modal fade" id="minorAdd" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add Member</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.minor.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" name="first_name" class="form-control"
                                        id="basic-default-fullname" placeholder="First Name" required />
                                    <label for="basic-default-fullname">First Name <span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" name="last_name" class="form-control"
                                        id="basic-default-fullname" placeholder="Last Name" required />
                                    <label for="basic-default-fullname">Last Name <span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select name="gender" class="form-control" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <label for="basic-default-fullname">Gender <span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-6">

                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="date" name="dob" class="form-control"
                                        id="minor-age-validates" placeholder="Date of Birth" required />
                                    <label for="minor-age-validates">Date of Birth <span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select name="parent" id="customer_ids_for_minors"
                                        class="select2 form-select form-select-lg" data-allow-clear="true">
                                        <option value="">Select</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">
                                                {{ $customer->first_name . ' (' . $customer->email . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="customer_ids_for_minors" id="relation_lable">Choose Registered Customer to Link<span
                                            class="text-danger fixed">*</span></label>
                                </div>
                            </div>
                            <div class="col-12" id="">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select id="relation_mem" name="relation" class="form-control" required>
                                        <option value="">Select Relationship</option>
                                        @foreach ($relationships as $relationship)
                                            <option value="{{ $relationship->title }}">{{ $relationship->title }}
                                            </option>
                                        @endforeach

                                    </select>
                                    <label for="basic-default-fullname">Relationship <span
                                            class="text-danger">*</span></label>
                                </div>
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

<!-- Modal for edit customer -->
<div class="modal fade" id="customerEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{-- <h5 class="mb-0">Edit Customer</h5> --}}
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="popup" value="1">
                        <input type="hidden" name="id" id="customerEditid" value="">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" name="first_name" class="form-control"
                                        id="customerEditfirst_name" placeholder="First Name" required />
                                    <label for="customerEditfirst_name">First Name <span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" name="last_name" class="form-control"
                                        id="customerEditlast_name" placeholder="Last Name" required />
                                    <label for="customerEditlast_name">Last Name <span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" name="email" class="form-control" id="customerEditemail"
                                        placeholder="Email Id" readonly />
                                    <label for="customerEditemail">Email Id <span class="text-danger">*</span></label>
                                </div>
                            </div>

                        </div>
                        <div class="row mb-4">
                            <div class="col-4">
                                <div class="form-floating form-floating-outline">
                                    <select name="telephone_code" id="telephone_code" class="form-control">
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
                                        class="form-control" id="customerEditphone" placeholder="Contact No" />
                                    <label for="customerEditphone">Contact No <span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select class="form-control" name="gender" id="customerEditgender" required>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <label for="customerEditgender">Gender <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="date" name="dob" class="form-control" id="customerEditdob"
                                        placeholder="DOB" required />
                                    <label for="customerEditdob">DOB <span class="text-danger">*</span></label>
                                </div>
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

<!-- Modal for Redeem Point -->
<div class="modal fade" id="customerRedeemPoint" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Redeem Points</h5>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        @csrf
                        <input type="hidden" class="user-id">
                        <div class="card rounded shadows bg-white point-card">
                            <div class="card-body">
                                <div>
                                    <span class="">Total available Points</span>
                                    <img src="{{ getEnv('USER_URL') }}/public/userpanel/asset/images/star.svg"
                                        alt=""> <span class="fw-bold" id="user_total_points">2400</span>
                                    {{-- <small class="">Expiring on: 26th Jan, 2023</small> --}}
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input onkeyup="checkPoints(this.value)" type="number" name="user_redeem_points"
                                        id="user_redeem_points" class="form-control" id="basic-default-redeem"
                                        placeholder="Points To Redeem" required />
                                    <label for="basic-default-redeem">Points To Redeem <span
                                            class="text-danger">*</span></label>
                                </div>
                                <small>You have left <span id="remaining-points">0</span> Points now!</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-2">
                                <a href="javaScript:void(0)" onclick="sendOtpToCustomer()"
                                    class="btn btn-warning">Next</a>
                            </div>
                            <div class="col-10 mt-2">
                                An OTP will be Sent to <b><span class="user-email-id">example@email.com</span></b>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Redeem Point otp verification -->
<div class="modal fade" id="customerRedeemPointVerify" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Redeem Points</h5>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        @csrf
                        <input type="hidden" class="user-id">
                        <div>
                            <p>We have sent an OTP to <b><span class="user-email-id">example@email.com</span></b>,
                                Please
                                Enter OTP below.</p>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="number" name="otp" id="user_otp" class="form-control"
                                        id="basic-default-redeem" placeholder="OTP" required />
                                    <label for="basic-default-redeem">OTP <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-2">
                                <a href="javaScript:void(0)" onclick="verifyOTP()" class="btn btn-warning">Sumbit</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Payment By customer -->
<div class="modal fade" id="paymentByModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Select Customer</h5>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        @csrf
                        <input type="hidden" class="user-id">
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select class="form-control" id="selected-customers-for-payment">
                                        <option value="">Select Customer</option>

                                    </select>
                                    <label for="">Choose Customer <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="">
                            <a href="javaScript:void(0)" onclick="paymentByCustomer()"
                                class="btn btn-warning">Sumbit</a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
