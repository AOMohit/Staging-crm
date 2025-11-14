@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a
                    href="{{ route('loyalty.index') }}">Loyalty</a>/</span> Gift</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Gift Loyalty Points</h5>
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
                        <form action="{{ route('loyalty.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            {{-- <div class="form-floating form-floating-outline mb-4">
                                <select name="trip" class="form-control">
                                    <option value="">Select Trip</option>
                                    @foreach ($trips as $trip)
                                        <option value="{{ $trip->id }}">{{ $trip->name }}</option>
                                    @endforeach
                                </select>
                                <label for="basic-default-fullname">Trip (optional)</label>
                            </div> --}}

                            <div class="col-md-12 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <select name="customer" id="customer" class="select2 form-select form-select-lg"
                                        data-allow-clear="true">
                                        <option value="">Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">
                                                {{ $customer->first_name . ' ' . $customer->last_name . ' (' . $customer->email . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="customer">Customer <span class="text-danger">*</span></label>
                                </div>
                            </div>


                            <div class="form-floating form-floating-outline mb-4">
                                <input type="number" max="50000" name="trans_amt" class="form-control" id="points"
                                    placeholder="Loyalty Points" />
                                <label for="points">Loyalty Points<span class="text-danger">*</span></label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="reason" oninput="limitText(this, 60);" class="form-control"
                                    id="reasons" placeholder="Reason" />
                                <label for="reasons">Reason<span class="text-danger">*</span></label>
                                <div id="reason_text">0 / 60 characters</div>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="number" name="otp" class="form-control" id="basic-default-fullname"
                                    placeholder="OTP" />
                                <label for="basic-default-fullname">OTP<span class="text-danger">*</span></label>
                                <small class="text-secondary">Click on Get OTP button for OTP</small>
                            </div>


                            <div class="text-center">
                                <a href="javaScript:void(0)" id="sendOtpBtn" onclick="sendOTP()" class="btn btn-warning">Get
                                    OTP</a>
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

        <script>
            function sendOTP() {
                var customer_id = $("#customer").val();
                var points = $("#points").val();
                if (customer_id) {
                    $.ajax({
                        url: "{{ route('loyalty.confirmation-email') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            customer_id: customer_id,
                            points: points,
                        },
                        succrss: function(res) {
                            Toast.fire({
                                icon: "success",
                                title: "Email Sent Successfully!"
                            });
                        }
                    });
                } else {
                    Toast.fire({
                        icon: "error",
                        title: "Customer Field is required!"
                    });
                }

                Toast.fire({
                    icon: "success",
                    title: "Email Sent Successfully!"
                });

                $('#sendOtpBtn').prop('disabled', true);
                $('#sendOtpBtn').off('click');

                var countdown = 120; // 2 minutes in seconds
                var timer = setInterval(function() {
                    countdown--;
                    $('#sendOtpBtn').text('Resend Mail in ' + countdown + ' seconds');
                    if (countdown <= 0) {
                        clearInterval(timer);
                        $('#sendOtpBtn').text('GET OTP').prop('disabled', false);
                        $('#sendOtpBtn').on('click');
                    }
                }, 1000);
            }
        </script>

        <script>
            function limitText(field, maxChar) {
                if (field.value.length > maxChar) {
                    field.value = field.value.substring(0, maxChar);
                }
                document.getElementById('reason_text').textContent = field.value.length + ' / ' + maxChar + ' characters';
            }
        </script>
    @endsection
