<div class="card-body">
    <div class="col-md-10 col-12">
        <div class="row">
            <div class="col-6">
                <h5 class="text fw-bold"></h5>
            </div>
           @if (request()->route()->getName() != 'booking.new-trip')
           
                @if ($data->trip_status != 'Completed')
                
                    @if ($data->trip_status != 'Cancelled')
                        <div class="col-6 text-end">
                            <a href="javaScript:void(0)" onclick="addPayment()" style="font-size: 0.7em;"
                                class="btn btn-warning">Add Payments</a>
                        </div>
                    @endif
                @elseif (auth()->user()->email == 'Vageesh@adventuresoverland.com')
                 <span>hello trip status is not completed</span>
                    <div class="col-6 text-end">
                        <a href="javaScript:void(0)" onclick="addPayment()" style="font-size: 0.7em;"
                            class="btn btn-warning">Add Payments</a>
                    </div>
                @endif
            @endif
        </div>
        
        <div class="row mt-5">
            <div class="col-md-6">
                <div class="d-flex justify-content-between">
                    <h6 class="fw text">Trip Cost</h6>
                    <div id="trip_price">

                    </div>
                </div>
                <hr>
                <div class="tax mt-3">
                    <h6 class="fw text">Discount</h6>
                    <div id="discount_trip_cost">


                    </div>
                </div>
               <hr> 

                <div class="">
                    <h6 class="fw text">Basic Package Cost</h6>
                    <div id="trip_cost">

                    </div>
                </div>
                <hr>

                <div class="deviation">
                    <h6 class="fw text">Deviation</h6>
                    <div id="deviations">

                    </div>
                </div>
                <hr>

               
                

                <div class="mt-3 pb-2">
                    <h6 class="fw text">Points Redeemed</h6><br>
                    <div id="redeem_points_list">

                    </div>
                </div>
                <hr>

                <div class="tax mt-3">
                    <h6 class="fw-bold text">Package Cost Offered (A)</h6>
                    <div id="actual_trip_cost">


                    </div>
                </div>
                <hr>

                <div class="tax mt-3 pb-2">
                    <h6 class="fw-bold text">Supplementary Services (B)</h6>
                    <div id="vehicle_seat_amt">

                    </div>
                    <div id="room_amt_info">

                    </div>
                </div>
                <hr>

                <div class="tax mt-3 pb-2">
                    <h6 class="fw-bold text">Total Package Cost (A+B)</h6>
                    <div id="package_A_B">


                    </div>
                </div>
                <hr>

                <div class="tax mt-3 border-bottoms pb-2">
                    <div id="tax_info">


                    </div>
                </div>
                <hr>

                <div class="tax mt-3 pb-2">
                    <h6 class="fw-bold text">Total Package Cost (incl taxes) - [C]</h6>
                    <div id="package_C">


                    </div>
                </div>
                <hr>

                <div class="mt-3">
                    <h6 class="fw-bold text font-size-14">Extra Services (incl taxes) - [D]</h6>
                    <div id="extra_service_data">

                    </div>
                    <div id="vehicle_sec_amt_info">

                    </div>
                </div>
                <hr>

                <div class="mt-3">
                    <h6 class="fw-bold text font-size-14">Carbon Offset Donation Amount - [E]</h6>
                    <div id="carbon_info">

                    </div>
                </div>
                <hr>

                <div class="mt-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="fw-bold text font-size-14">Total Receivable [C + D + E]</h6>

                        <h6 class="font-size-12" id="total_payable_amt">0</h6>
                    </div>
                </div>
                <hr>

                <div class="mt-3">
                    <h6 class="fw-bold text">Credit Note Amount</h6>
                    <div id="credit_note_amt_list">

                    </div>
                </div>
                <hr>

                <div class="mt-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="fw-bold text font-size-14">Total asdsReceivable</h6>

                        <h6 class="font-size-12" id="final_total_payable_amt">0</h6>
                    </div>
                </div>
                <hr>

            </div>
            <div class="col-md-1"></div>
            <div class="col-md-5 payment-details p-0">
                <div class="border-bottoms pb-3">
                    <h6 class="fw-bold text font-size-14">Total Receivable</h6>
                    <div id="payment_details_list" style="width:120%;">

                    </div>
                </div>
                <hr>
                <div class="mt-3 pb-2">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold text-success font-size-14">Total Amount Received</span>
                        <input type="hidden" id="total_rec_amt_inp" value="0">
                        <h6 class="font-size-14" id="total_rec_amt">0</h6>
                    </div>
                </div>
                <hr>
                <div class="mt-3 pb-2">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold text-danger font-size-14">Total Pending Amount</span>
                        <input type="hidden" id="total_pending_amt_inp" value="0">
                        <h6 class="font-size-14" id="total_pending_amt">0</h6>
                    </div>
                </div>

                <hr>
                <div class="mt-3 pb-2">
                    <div class="d-flex justify-content-between">
                        <h6 class="fw-bold font-size-14">Loyalty Points Detail</h6>
                    </div>
                </div>

                <hr>
                <div class="tax mt-3">
                    <h6 class="fw-bold text">Package Cost Offered (A)</h6>
                    <div id="actual_trip_cost_for_loyalty">

                    </div>
                    <div id="extra_services_redeemable">

                    </div>
                </div>

                <hr>
                <div class="tax mt-3">
                    <h6 class="fw-bold text">Total</h6>
                    <div id="total_calc_loyalty">

                    </div>
                </div>

                <hr>
                <div>
                    <span class="fw-bold text font-size-14">Loyalty Points Earned</span>
                </div>
                <div id="points-grp">

                </div>
                <div id="toptal-points-grp">

                </div>
                
                <hr>

                @if ($data && $data->trip_status != 'Completed')
                    @if ($data->trip_status != 'Confirmed')
                        <div class="col-md-12">
                            @if ($data->trip_status == 'Cancelled')
                                <h6 class=" alert alert-danger"><span class="fw-bold">Cancellation Summary</span>
                                    <br>
                                    <br>
                                    <table style="font-size: 14px;">
                                        <tr>
                                            <td>Cancellation Charges (excl taxes)</td>
                                            <td>₹{{ indian_number_format($data->cancelation_amount) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Cancellation Charges (incl 5% GST)</td>
                                            <td>₹{{ indian_number_format($data->cancelation_amount_5_gst) }}</td>
                                        </tr>
                                        <tr>
                                            <td>TCS/TDS to be adjusted</td>
                                            <td>₹{{ indian_number_format($data->cancelation_amount_tcs) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Amount to be refunded via bank transfer</td>
                                            <td>₹{{ indian_number_format($data->cancelation_amount_refunded) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Amount of credit note to be raised</td>
                                            <td>₹{{ indian_number_format($data->cancelation_amount_credit_note) }}</td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <p>Comment:</p>
                                    <p>{{ $data->cancelation_reason }}</p>
                                </h6>
                            @endif
                            @if ($data->trip_status == 'Correction' && $data->correction_reason)
                                <h6 class=" alert alert-warning"><span class="fw-bold">Correction
                                        Reason</span>
                                    <br>
                                    <br>
                                    {{ $data->correction_reason }}
                                </h6>
                            @endif
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
</div>
 <!-- Modal for booking payment edit -->
 <div class="modal fade" id="paymentEdit" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Payment Edit</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('booking.trip.update-part-payment') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="id" value="{{ $data->id ?? '' }}">
                                <input type="hidden" name="index_id" id="indexid" value="" >

                                <div class="row">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="payment_type" class="form-control" required>
                                            <option value="">Select Payment Type</option>
                                            <option value="Full Payment">
                                            Full Payment</option>
                                            <option value="Part Payment">
                                            Part Payment</option>
                                        </select>
                                        <label for="basic-default-fullname">Payment Type <span class="text-danger">*</span></label>
                                    </div>
                                </div>
    
                                <div class="row">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select id="remarkSelect" onchange="remarkType(this.value)" name="remark" class="form-control"
                                            required>
                                            <option value="">Select Remarks Type</option>
                                            <option  value="Package Cost">
                                            Package Cost</option>
                                            <option  value="Extra Services">
                                            Extra Services</option>
                                            <option   value="Vehicle Security">
                                            Vehicle Security</option>
                                            <option  value="Other">
                                            Other</option>
                                         
                                        </select>
                                        <label for="basic-default-fullname">Add Remarks Type <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
    
                                <div class="row" id="edit_remark_type_cmt">
                                    <div class="col-12">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <input type="text" name="comment"class="form-control"  value=""
                                                id="basic-default-fullname" placeholder="Comment" />
                                            <label for="basic-default-fullname">Comment <span
                                                    class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <input onkeyup="checkRmBlnc(this.value)" type="number" name="amount"
                                                value="" class="form-control" id="rm-inp-bal" placeholder="amount" required />
                                            <label for="rm-inp-bal">Amount <span class="text-danger">*</span></label>
                                            <small>Remaining Amount ₹<span class="fw-bold"
                                                    id="rm-blnc"></span></small>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <input type="date" name="date" class="form-control" value=""
                                                id="basic-default-fullname" required />
                                            <label for="basic-default-fullname">Date Of Payment <span
                                                    class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="text-center">
                                    <button type="submit" class="btn btn-warning">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
<script>
        function editPayment(index) {
            var id = $('#id').val();
            $.ajax({
                url: "{{ route('booking.trip.edit-payment') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    token: "{{ request()->token }}",
                    id:id,
                    index:index
                },
                success: function(res) {
                    console.log(res.data);
                    if (res && res.data) {
                        $('#paymentEdit').find("select[name='payment_type']").val(res.data.payment_type).trigger('change'); 
                        $('#paymentEdit').find("select[name='remark']").val(res.data.remark).trigger('change');
                        $('#paymentEdit').find("input[name='comment']").val(res.data.comment || ""); 
                        $('#paymentEdit').find("input[name='amount']").val(res.data.amount); 
                        $('#paymentEdit').find("input[name='date']").val(res.data.date); 
                        $('#paymentEdit').find("input[name='index_id']").val(res.data.index);
                        $('#paymentEdit').find("#rm-blnc").html(res.data.rmBal);


                        $("#paymentEdit").modal('show');
                    } else {
                        console.error("Response data is missing or undefined");
                    }
                }

            });
            
        }
        function remarkType(val) {
            if (val == "Other") {
                $("#remark_type_cmt").show();
                $("#edit_remark_type_cmt").show();

            } else {
                $("#remark_type_cmt").hide();
                $("#edit_remark_type_cmt").hide();

            }
        }
</script>

