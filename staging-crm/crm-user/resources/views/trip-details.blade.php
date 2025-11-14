@extends('layouts.userlayout.index')
<style>
    th {
        white-space: nowrap;
    }

    td {
        white-space: nowrap;
    }

    .table>:not(caption)>*>* {
        padding: 0.5rem 0.5rem;
        background-color: var(--bs-table-bg);
        border-bottom-width: 0 !important;
        box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
    }
    h6 span{
         font-size: 15px;
    }
</style>
@section('container')
    <div class="col-md-10 col-12">
        <div class="border-shadow">
            <div class="card">
                <div class="card-header bg-white information">
                    @include('layouts.userlayout.card-header')
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>

                            <h6 class="text mb-4">Trip Detail Page
                        
                                @if ($data->trip->end_date < date("Y-m-d"))
                                @php
                                $data->trip_status = 'Completed';
                                @endphp
                                    <span class="@if($data->trip_status == 'Completed') bg-Success @endif p-1 px-3 ms-3 rounded text-white "> Trip {{ $data->trip_status }}
                                    </span>

                                @else
                                    <span
                                        class="@if($data->trip_status == 'Cancelled') bg-danger
                                                @elseif ($data->trip_status == 'Confirmed') bg-info
                                                @elseif($data->trip_status == 'Correction') bg-warning  
                                                @elseif($data->trip_status == 'Draft') bg-secondary
                                            @endif 
                                        p-1 px-3 ms-3 rounded text-white">
                                        {{ $data->trip_status }}
                                    </span>
                                @endif

                            </h6>

                        </div>

                        <div class="d-block d-md-none">

                            @isset($data->invoice_file)
                                <a href="{{ env('ADMIN_URL').'/storage/app/'. $data->invoice_file }}" target="_blank">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="22" viewBox="0 0 20 22"
                                        fill="none">
                                        <path d="M6.45166 6.45142H11.613V7.74174H6.45166V6.45142Z" fill="#FFB224" />
                                        <path d="M6.45166 9.03223H11.613V10.3225H6.45166V9.03223Z" fill="#FFB224" />
                                        <path d="M6.45166 3.87085H11.613V5.16117H6.45166V3.87085Z" fill="#FFB224" />
                                        <path
                                            d="M6.45166 15.4838H10.0096C10.3381 14.5575 10.8952 13.7386 11.613 13.0954V11.6128H6.45166V15.4838Z"
                                            fill="#FFB224" />
                                        <path
                                            d="M9.71346 16.7742C7.49265 16.7742 4.74865 16.7742 2.58065 16.7742C2.58065 16.4409 2.58065 4.39389 2.58065 2.58064H12.9032V12.219C13.6811 11.8314 14.5574 11.6129 15.4839 11.6129V0H0V21.9355H11.8388C10.3001 20.6912 9.49265 18.7622 9.71346 16.7742ZM6.45161 19.3548H2.58065V18.0645H6.45161V19.3548Z"
                                            fill="#FFB224" />
                                        <path d="M3.87091 6.45142H5.16123V7.74174H3.87091V6.45142Z" fill="#FFB224" />
                                        <path d="M3.87091 3.87085H5.16123V5.16117H3.87091V3.87085Z" fill="#FFB224" />
                                        <path d="M3.87091 11.6128H5.16123V12.9031H3.87091V11.6128Z" fill="#FFB224" />
                                        <path d="M3.87091 9.03223H5.16123V10.3225H3.87091V9.03223Z" fill="#FFB224" />
                                        <path d="M3.87091 14.1934H5.16123V15.4837H3.87091V14.1934Z" fill="#FFB224" />
                                        <path
                                            d="M15.4838 12.9031C12.9936 12.9031 10.9677 14.929 10.9677 17.4192C10.9677 19.9094 12.9936 21.9353 15.4838 21.9353C17.974 21.9353 20 19.9094 20 17.4192C20 14.929 17.974 12.9031 15.4838 12.9031ZM15.4838 20.13L13.1905 18.6011L13.9063 17.5275L14.8387 18.1492V14.8386H16.129V18.1492L17.0615 17.5276L17.7773 18.6012L15.4838 20.13Z"
                                            fill="#FFB224" />
                                    </svg><span class="font-size-14">Download Invoice</span>
                                </a>
                            @endisset
                        </div>
                    </div>
                    <div class="card border-shadow">

                        <div class="card-body">
                            <div class="border-bottom">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold font-size-15">Trip Booking Id: #{{ $data->id }}</span>
                                    <a href="{{ route('mytrip') }}" class="fw-bold">
                                        < Back <span class="curent-tier">to Trips History</span>
                                    </a>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6 pe-3">
                                    <p class="fw-bold text font-size-14"><u>Trip Name</u></p>
                                    <h5 class="fw-bold text font-size-18">{{ $data->trip->name }}</h5>
                                    <div class="trip-dates">
                                        <div class="row">
                                            <div class="col-xl-6 col-lg-12 col-md-12 col-12">
                                                <span class="font-size-12">Trip Start Date:</span><span
                                                    class="fw-bold font-size-12"> &nbsp;
                                                    {{ date('d M, Y', strtotime($data->trip->start_date)) }}</span>

                                            </div>
                                            <div class="col-xl-6 col-lg-12 col-md-12 col-12">
                                                <span class="font-size-12">Trip End Date:</span><span
                                                    class="fw-bold font-size-12"> &nbsp;
                                                    {{ date('d M, Y', strtotime($data->trip->end_date)) }}
                                                </span>
                                            </div>
                                        </div>


                                    </div>
                                    @php
                                        $firstTraveler = json_decode($data->customer_id)[0];
                                        $travelerOne = getCustomerById($firstTraveler);
                                    @endphp
                                    <h6 class="fw-bold text mt-2 font-size-14">{{ $travelerOne->name }}</h6>
                                    <div class="trip-dates font-size-14">
                                        <p>
                                            @if($travelerOne->address)
                                                {{ $travelerOne->address . ', ' }}
                                            @endif
                                            @if($travelerOne->city)
                                                {{ $travelerOne->city . ', ' }}
                                            @endif
                                            @if($travelerOne->state)
                                                {{ $travelerOne->state . ', ' }}
                                            @endif
                                            @if($travelerOne->pincode)
                                                {{ $travelerOne->pincode . ', ' }}
                                            @endif
                                            @if($travelerOne->country)
                                                {{ $travelerOne->country }}
                                            @endif
                                        </p>
                                    </div>
                                    <h6 class="fw-bold text mt-2 font-size-14">Phone number</h6>
                                    <div class="trip-dates font-size-14">
                                        <p>{{ $travelerOne->phone }}</p>
                                    </div>
                                    <h6 class="fw-bold text mt-2 font-size-14">Email</h6>
                                    <div class="trip-dates font-size-14">
                                        <p>{{ $travelerOne->email }}</p>
                                    </div>
                                </div>          
                                <div class="col-md-4 mx-auto d-block">
                                    <p class="fw-bold text font-size-14"><u>
                                            @if ($data->trip_status == 'Completed')
                                                Points Earned
                                            @else
                                                Points to be earned
                                            @endif
                                        </u></p>
                                    <div>
                                        <img src="{{ asset('public/userpanel') }}/asset/images/star.svg" alt="">
                                        <span class="text fw-bold information-trip-count" id="totalSumpoints"
                                            style="font-size: 22px">0</span>
                                        <!--<small class="font-size-12">Points</small><br>-->
                                        {{-- <small class="">Expiring on: 26th Jan, 2023</small> --}}
                                    </div>
                                    <div class="mt-4">
                                        <h5 class="text fw-bold font-size-14"><u>Trip Members Details</u>
                                        </h5>
                                        @foreach (json_decode($data->customer_id) as $key => $c_id)
                                            @php
                                                $travelers = getCustomerById($c_id);
                                            @endphp
                                            <span class="fw-bold text font-size-14">Traveler
                                                {{ $key + 1 }} @if ($travelers->parent > 0)
                                                    (Minor)
                                                @endif
                                                :</span><span>&nbsp;{{ $travelers->name }}
                                                ({{ $travelers->relation }}, {{ getYearsFromDob($travelers->dob) }}
                                                yrs)
                                            </span><br>
                                        @endforeach
                                    </div>
                                    <!--<div class="mt-4">-->
                                    <!--    <h5 class="text fw-bold font-size-14">Room Type</h5>-->
                                    <!--    <h5 class="text fw-bold font-size-14">{{ $data->room_type }}</h5>-->
                                    <!--</div>-->
                                </div>
                                @if ($data->trip_status == 'Completed')
                                    <div class="col-md-2 d-none d-md-block">
                                        @isset($data->invoice_file)
                                            <p class="fw-bold text font-size-14"><u>More actions</u></p>
                                            <a href="{{ env('ADMIN_URL').'/storage/app/' . $data->invoice_file }}" target="_blank">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="22"
                                                    viewBox="0 0 20 22" fill="none">
                                                    <path d="M6.45166 6.45142H11.613V7.74174H6.45166V6.45142Z" fill="#FFB224" />
                                                    <path d="M6.45166 9.03223H11.613V10.3225H6.45166V9.03223Z" fill="#FFB224" />
                                                    <path d="M6.45166 3.87085H11.613V5.16117H6.45166V3.87085Z" fill="#FFB224" />
                                                    <path
                                                        d="M6.45166 15.4838H10.0096C10.3381 14.5575 10.8952 13.7386 11.613 13.0954V11.6128H6.45166V15.4838Z"
                                                        fill="#FFB224" />
                                                    <path
                                                        d="M9.71346 16.7742C7.49265 16.7742 4.74865 16.7742 2.58065 16.7742C2.58065 16.4409 2.58065 4.39389 2.58065 2.58064H12.9032V12.219C13.6811 11.8314 14.5574 11.6129 15.4839 11.6129V0H0V21.9355H11.8388C10.3001 20.6912 9.49265 18.7622 9.71346 16.7742ZM6.45161 19.3548H2.58065V18.0645H6.45161V19.3548Z"
                                                        fill="#FFB224" />
                                                    <path d="M3.87091 6.45142H5.16123V7.74174H3.87091V6.45142Z"
                                                        fill="#FFB224" />
                                                    <path d="M3.87091 3.87085H5.16123V5.16117H3.87091V3.87085Z"
                                                        fill="#FFB224" />
                                                    <path d="M3.87091 11.6128H5.16123V12.9031H3.87091V11.6128Z"
                                                        fill="#FFB224" />
                                                    <path d="M3.87091 9.03223H5.16123V10.3225H3.87091V9.03223Z"
                                                        fill="#FFB224" />
                                                    <path d="M3.87091 14.1934H5.16123V15.4837H3.87091V14.1934Z"
                                                        fill="#FFB224" />
                                                    <path
                                                        d="M15.4838 12.9031C12.9936 12.9031 10.9677 14.929 10.9677 17.4192C10.9677 19.9094 12.9936 21.9353 15.4838 21.9353C17.974 21.9353 20 19.9094 20 17.4192C20 14.929 17.974 12.9031 15.4838 12.9031ZM15.4838 20.13L13.1905 18.6011L13.9063 17.5275L14.8387 18.1492V14.8386H16.129V18.1492L17.0615 17.5276L17.7773 18.6012L15.4838 20.13Z"
                                                        fill="#FFB224" />
                                                </svg><span class="font-size-14">Download Invoice</span>
                                            </a>
                                        @endisset
                                    </div>
                                @endif
                                <div class="col-md-10 border-top pt-2 table-responsive">
                                    <table class="table border-0">
                                        <tr>
                                            <!--<th scope="col">Lead Source</th>-->
                                            <th scope="col">Vehicle Type</th>
                                            <th scope="col">Vehicle Seat</th>
                                            <th scope="col">Room Type</th>
                                            <th scope="col">Room Category</th>
                                        </tr>

                                        <tr>
                                            <!--<td>{{ $data->lead_source }}, {{ $data->sub_lead_source }}</td>-->
                                            <td>{{ $data->vehical_type }}</td>
                                            <td>{{ $data->vehical_seat }}</td>
                                            <td>
                                                @if ($data->room_info)
                                                {{ collect(json_decode($data->room_info))->pluck('room_type')->join(', ') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($data->room_info)
                                                {{ collect(json_decode($data->room_info))->pluck('room_cat')->join(', ') }}
                                            @endif
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card border-shadow mt-3 pb-5">
                        <div class="card-body">
                            <div class="col-md-10 col-12">
                                <h6 class="text fw-bold ">Summary</h6>
                                <div class="row mt-5">
                                    <div class="col-md-6">

                                        <div class="border-bottom mt-3">
                                            <h6 class="fw-bold text font-size-14">Basic Package Cost</h6>
                                            <div id="trip_cost">

                                            </div>
                                        </div>
                                        <div class="border-bottom mt-3">
                                            <h6 class="fw-bold text font-size-14">Discount</h6>
                                            <div id="discount_trip_cost">


                                            </div>
                                        </div>

                                        <div class="border-bottom mt-3">
                                            <h6 class="fw-bold text font-size-14">Points Redeemed</h6><br>
                                            <div id="redeem_points_list">

                                            </div>
                                        </div>

                                        <div class="border-bottom mt-3">
                                            <h6 class="fw-bold text font-size-14">Package Cost Offered (A)</h6>
                                            <div id="actual_trip_cost">

                                            </div>
                                        </div>

                                        <div class="border-bottom mt-3">
                                            <h6 class="fw-bold text font-size-14">Supplementary Services (B)</h6>
                                            <div id="vehicle_seat_amt">

                                            </div>
                                            <div id="room_amt_info">

                                            </div>
                                        </div>

                                        <div class="border-bottom mt-3">
                                            <h6 class="fw-bold text font-size-14">Total Package Cost (A+B)</h6>
                                            <div id="package_A_B">


                                            </div>
                                        </div>

                                        <div class="border-bottom mt-3">
                                            <div id="tax_info">

                                            </div>
                                        </div>

                                        <div class="border-bottom mt-3">
                                            <h6 class="fw-bold text font-size-14">Total Package Cost (incl taxes) - [C]
                                            </h6>
                                            <div id="package_C">

                                            </div>
                                        </div>

                                        <div class="border-bottom mt-3">
                                            <h6 class="fw-bold text font-size-14">Extra Services (incl taxes) - [D]</h6>
                                            <div id="extra_service_data">

                                            </div>
                                            <div id="vehicle_sec_amt_info">

                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <h6 class="fw-bold text font-size-14">Carbon Offset Donation Amount - [E]</h6>
                                            <div id="carbon_info">

                                            </div>
                                        </div>
                                        <hr>
                                        <div class="border-bottom mt-3">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="fw-bold text font-size-14">Total Receivable [C + D + E]</h6>

                                                <h6 class="fw-bold text font-size-14" id="total_payable_amt">0</h6>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- secend row --}}
                                    <div class="col-md-6 payment-details">
                                        <div class="border-bottoms mb-3">
                                            <h6 class="fw-bold text font-size-14">Total Receivable</h6>
                                            <div id="payment_details_list">

                                            </div>
                                        </div>
                                        <div class="border-bottoms mb-3">
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold text-success font-size-14">Total Amount
                                                    Received</span>

                                                <h6 class="font-size-14" id="total_rec_amt">0</h6>
                                            </div>
                                        </div>
                                        <div class="border-bottoms mb-3">
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold text-danger font-size-14">Total Pending Amount</span>

                                                <h6 class="font-size-14" id="total_pending_amt">0</h6>
                                            </div>
                                        </div>

                                        <div class="border-bottoms mb-3">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="fw-bold font-size-14">Loyalty Points Detail</h6>
                                            </div>
                                        </div>

                                        <div class="border-bottoms mb-3">
                                            <h6 class="fw-bold font-size-14">Package Cost Offered (A)</h6>
                                            <div id="actual_trip_cost_for_loyalty">

                                            </div>
                                            <div id="extra_services_redeemable">

                                            </div>
                                        </div>

                                        <div class="border-bottoms mb-3">
                                            <h6 class="fw-bold font-size-14">Total</h6>
                                            <div id="total_calc_loyalty">

                                            </div>
                                        </div>
                                        <div>
                                            <span class="fw-bold text font-size-14">Loyalty Points Earned</span>
                                        </div>
                                        <div id="points-grp">

                                        </div>

                                        @if ($data->trip_status != 'Completed')
                                            @if ($data->trip_status != 'Confirmed')
                                                <div class="col-md-12">
                                                    @if ($data->trip_status == 'Cancelled' && $data->cancelation_reason)
                                                        <h6 class=" alert alert-danger"><span class="fw-bold">Cancellation
                                                                Summary</span>
                                                            <br>
                                                            <br>
                                                            <table style="font-size: 14px;">
                                                                <tr>
                                                                    <td>Cancellation Charges (excl taxes)</td>
                                                                    <td>₹{{ number_format($data->cancelation_amount) }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Cancellation Charges (incl 5% GST)</td>
                                                                    <td>₹{{ number_format($data->cancelation_amount_5_gst) }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>TCS/TDS to be adjusted</td>
                                                                    <td>₹{{ number_format($data->cancelation_amount_tcs) }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Amount to be refunded via bank transfer</td>
                                                                    <td>₹{{ number_format($data->cancelation_amount_refunded) }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Amount of credit note to be raised</td>
                                                                    <td>₹{{ number_format($data->cancelation_amount_credit_note) }}
                                                                    </td>
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

                    </div>

                    @include('imp')
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        // Create our number formatter.
        const formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'INR',
        });


        $(document).ready(function() {
            getSummary();
        });


        function getSummary() {
            $.ajax({
                url: "{{ route('summary') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    token: "{{ request()->token }}",
                },
                success: function(res) {
                    if (!res) {
                        // alert("Somthing Went Wrong, try again.")
                    } else {
                        var data = JSON.parse(res);
                        var trip_costs = data.trip_costs;
                        var extra_services = data.extra_services;
                        var vsa = data.vehicle_security;
                        var seatCharge = data.vehicle_seat_charge;
                        var roomInfo = data.room_info;
                        var taxes = data.taxes;
                        var points = data.points;
                        var payment = data.advance_payment;
                        var partPayment = data.part_payment;
                        var earnedpoints = data.points_list;
                        var actual_trip_cost = data.actual_trip_cost;
                        var real_trip_amt = data.real_trip_amt;
                        var package_offer_A = data.package_offer_A;
                        var a_and_b = data.a_and_b;
                        var package_c_data = data.package_c;
                        var extra_services_redeemable = data.extra_services_redeemable;
                        var vehicle_seat = data.vehicle_seat;
                        var vehicle_type = data.vehicle_type;
                        var carbon_infos = data.carbon_infos;

                        var total_trip_cost = 0;
                        var total_redeem_amt = 0;
                        var total_payable_amt = 0;
                        var total_rcvd_amt = 0;
                        var totalPoints = 0;
                        var totalVehicleSecAmt = 0;
                        var totalVehicleSeatAmt = 0;
                        var totalRoomCharges = 0;

                        var trip_cost_list = "";
                        var extra_service_list = ``;
                        var vs = ``;
                        var tax_list = `<h6 class="fw-bold text font-size-14">Tax</h6>`;
                        var redeem_poins_data = "";
                        var final_paid = "";
                        var room_info_data = "";
                        var actual_trip_cost_data = "";
                        var loyalty_total_data = "";
                        var discount_trip_cost_data = "";
                        var package_a_b = "";
                        var vehicle_seat_amt = "";
                        var package_c = "";
                        var extra_service_list_redeem = "";
                        var carbon_infos_data = ``;

                        // Basic Package Cost
                        $.each(trip_costs, function(key, value) {
                            total_trip_cost += parseInt(value.cost);

                            var isParent = "";
                            if (value.parent > 0) {
                                isParent = "(Minor)";
                            }
                            trip_cost_list += `<div class="d-flex justify-content-between">
                                                    <p class="gray font-size-12">Traveler ${ key + 1 }
                                                        (${value.traveler})  ${isParent}
                                                        <span data-bs-toggle="tooltip" data-bs-placement="right"
                                                            title="Vehicle Amount = ${formatter.format(value.vehicle_amt)} , Room Amount = ${formatter.format(value.room_amt)} ">
                                                            <img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg"
                                                                alt="" class="ps-2">
                                                        </span>
                                                    </p>
                                                    <h6 class="font-size-14">${formatter.format(value.cost)}
                                                    </h6>
                                                </div>`;
                        });
                        $("#trip_cost").html(trip_cost_list);
                        // Basic Package Cost

                        // discount in trip
                        $.each(trip_costs, function(key, value) {

                            discount_trip_cost_data += `<div class="d-flex justify-content-between">
                            <p class="gray font-size-12"> ${value.traveler}
                            </p>
                            <h6 class="font-size-14">${formatter.format(real_trip_amt - value.cost)}
                            </h6>
                        </div>`;
                        });
                        $("#discount_trip_cost").html(discount_trip_cost_data);
                        // discount in trip

                        // redeem points
                        $.each(points, function(key, value) {
                            total_redeem_amt += parseInt(value.points);
                            redeem_poins_data += `<div class="d-flex justify-content-between">
                                                <span class="gray font-size-12"> ${value.traveler} </span>
                                                <h6 class="font-size-12">-${formatter.format(value.points)}</h6>
                                            </div>`;
                        });
                        $("#redeem_points_list").html(redeem_poins_data);
                        // redeem points

                        // Package Cost Offered (A)
                        $.each(package_offer_A, function(key, value) {

                            actual_trip_cost_data += `<div class="d-flex justify-content-between">
                                                    <p class="gray font-size-12"> ${value.traveler}
                                                    </p>
                                                    <h6 class="font-size-14">${formatter.format(value.cost)}
                                                    </h6>
                                                </div>`;
                        });
                        $("#actual_trip_cost, #actual_trip_cost_for_loyalty").html(actual_trip_cost_data);
                        // Package Cost Offered (A)

                        // Supplementary start
                        if (parseInt(seatCharge) > 0) {
                            total_trip_cost += parseInt(seatCharge);
                            vehicle_seat_amt += `<div class="">
                                <div class="d-flex justify-content-between">
                                    <span class="text font-size-14">Extra Seat (${vehicle_seat})${vehicle_type && vehicle_type !== '' ? ' - ' + vehicle_type : ''}</span>

                                    <h6 class="font-size-14">${formatter.format(seatCharge)}</h6>
                                </div>
                            </div>`;
                        }
                        $("#vehicle_seat_amt").html(vehicle_seat_amt);

                        // room info
                        $.each(roomInfo, function(key, value) {
                            if (value.room_type_amt) {
                                total_trip_cost += parseInt(value.room_type_amt);
                                room_info_data += `<div class="d-flex justify-content-between">
                                                    <span class="gray font-size-12">Room Charges (${value.room_type}) ${value.room_cat && value.room_cat !== '' ? ' - ' + value.room_cat : ''}</span>
                                                <h6 class="font-size-12">${formatter.format(value.room_type_amt)}</h6>
                                            </div>`;
                            }
                        });
                        $("#room_amt_info").html(room_info_data);
                        // Supplementary end


                        // package A + B
                        $.each(a_and_b, function(key, value) {
                            package_a_b += `<div class="d-flex justify-content-between">
                                            <p class="gray font-size-12"> ${value.traveler}
                                            </p>
                                            <h6 class="font-size-14">${formatter.format(value.cost)}
                                            </h6>
                                        </div>`;
                        });
                        $("#package_A_B").html(package_a_b);
                        // package A + B end

                        // package C
                        $.each(package_c_data, function(key, value) {
                            package_c += `<div class="d-flex justify-content-between">
                                            <p class="gray font-size-12"> ${value.traveler}
                                            </p>
                                            <h6 class="font-size-14">${formatter.format(value.cost)}
                                            </h6>
                                        </div>`;
                        });
                        $("#package_C").html(package_c);
                        // package C end

                        // tax
                        $.each(taxes.gst, function(key, value) {
                            total_trip_cost += parseInt(value.gst);
                            tax_list += `<div class="d-flex justify-content-between mt-2">
                                        <div>
                                            <span class="fw-bold text  font-size-14">GST ${value.gst_per}%</span><br>
                                            <span class="gray font-size-12"> ${value.traveler}</span>
                                        </div>
                                        <h6 class="font-size-14">+${formatter.format(value.gst)}</h6>
                                    </div>`;
                        });
                        $.each(taxes.tcs, function(key, value) {
                            if (value.tcs_per == 2) {
                                var taxComp = "TDS";
                                var taxSign = "-";
                                total_trip_cost -= parseInt(value.tcs);
                            } else {
                                var taxComp = "TCS";
                                var taxSign = "+";
                                total_trip_cost += parseInt(value.tcs);
                            }
                            tax_list += `<div class="d-flex justify-content-between mt-2">
                                        <div>
                                            <span class="fw-bold text  font-size-14">${taxComp} ${value.tcs_per}%</span><br>
                                            <span class="gray font-size-12"> ${value.traveler}</span>
                                        </div>
                                        <h6 class="font-size-14">${taxSign} ${formatter.format(value.tcs)}</h6>
                                    </div>`;
                        });
                        $("#tax_info").html(tax_list);
                        // tax

                        // extra_service_list
                        $.each(extra_services, function(key, value) {
                            total_trip_cost += parseInt(value.extra_charges);
                            extra_service_list += `<div class="d-flex justify-content-between">
                                                    <p class="gray font-size-12"> ${value.services} for ${value.traveler_name}<span
                                                            data-bs-toggle="tooltip" data-bs-placement="right"
                                                            title="Net Cost = ${formatter.format(value.amount)}, Markup = ${formatter.format(value.markup)}, Tax = ${value.tax}%, Comment = ${value.comment}">
                                                            <img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg"
                                                                alt="" class="ps-2">
                                                        </span></p>
                                                    <h6 class="font-size-14">+${formatter.format(value.extra_charges)}</h6>
                                                </div>`;
                        });
                        $("#extra_service_data").html(extra_service_list);

                        // vehicle security
                        if (parseInt(vsa.amount) > 0) {
                            total_trip_cost += parseInt(vsa.amount);
                            vs += `<div class="">
                                <div class="d-flex justify-content-between">
                                    <span class="text font-size-14">Vehicle Security Amount <span data-bs-toggle="tooltip"
                                        data-bs-placement="right"
                                        title="${vsa.comment}">
                                        <img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg"
                                            alt="" class="ps-2">
                                    </span></span></span>

                                    <h6 class="font-size-14">${formatter.format(vsa.amount)}</h6>
                                </div>
                            </div>`;
                        }
                        $("#vehicle_sec_amt_info").html(vs);
                        // extra_service_list

                        // Carbon Info
                        $.each(carbon_infos, function(key, carbon_info) {
                            if (parseInt(carbon_info.amount) > 0) {
                                total_trip_cost += parseInt(carbon_info.amount);
                                carbon_infos_data += `<div class="">
                                    <div class="d-flex justify-content-between">
                                        <span class="text font-size-14">${carbon_info.customer_name}</span>

                                        <h6 class="font-size-14">${formatter.format(carbon_info.amount)}</h6>
                                    </div>
                                </div>`;
                            }
                        });
                        $("#carbon_info").html(carbon_infos_data);
                        // Carbon Info end


                        // extra service redeemable
                        $.each(extra_services_redeemable, function(key, value) {
                            extra_service_list_redeem += `<div class="d-flex justify-content-between">
                                                    <p class="gray font-size-12"> ${value.services} for ${value.traveler_name} (ex taxes)</p>
                                                    <h6 class="font-size-14">${formatter.format(value.extra_charges)}</h6>
                                                </div>`;
                        });
                        $("#extra_services_redeemable").html(extra_service_list_redeem);
                        // extra service redeemable

                        // payment
                        if (payment.payment) {
                            total_rcvd_amt += parseInt(payment.payment);
                            final_paid += `<div class="d-flex justify-content-between">
                                            <div>
                                                <span class="fw-bold text font-size-14">Advance Payment
                                                    <span data-bs-toggle="tooltip"
                                                        data-bs-placement="right"
                                                        title="First Advance payment of Total payable amount">
                                                        <img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg"
                                                            alt="" class="ps-2">
                                                    </span></span><br>
                                                <span class="gray font-size-12"> Received on ${payment.date}
                                                </span>
                                            </div>
                                            <h6 class="font-size-14">${formatter.format(payment.payment)}</h6>
                                        </div>`;

                            $.each(partPayment, function(key, value) {
                                total_rcvd_amt += parseInt(value.amount);
                                final_paid += `<div class="d-flex justify-content-between mt-1">
                                                <div>
                                                    <span class="fw-bold text font-size-14">Payment ${key+1}
                                                        <span data-bs-toggle="tooltip"
                                                            data-bs-placement="right"
                                                            title="Remarks: ${value.remark ?? "NA"}, Comment: ${value.comment ?? "NA"}">
                                                            <img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg"
                                                                alt="" class="ps-2">
                                                        </span></span><br>
                                                    <span class="gray font-size-12"> Received on ${value.date}
                                                    </span>
                                                </div>
                                                <h6 class="font-size-14">${formatter.format(value.amount)}</h6>
                                            </div>`;
                            });

                            $("#total_rec_amt").text(formatter.format(total_rcvd_amt));
                            $("#payment_details_list").html(final_paid);
                        }
                        // payment

                        // loyalty Total
                        $.each(actual_trip_cost, function(key, value) {
                            loyalty_total_data += `<div class="d-flex justify-content-between">
                                            <p class="gray font-size-12"> ${value.traveler}
                                            </p>
                                            <h6 class="font-size-14">${formatter.format(value.cost)}
                                            </h6>
                                        </div>`;
                        });
                        $("#total_calc_loyalty").html(loyalty_total_data);
                        // loyalty Total end

                        // total amount
                        $("#total_trip_cost_amt").text(formatter.format(total_trip_cost))

                        // total
                        total_payable_amt = parseInt(total_trip_cost) - parseInt(total_redeem_amt);
                        $("#total_payable_amt").text(formatter.format(total_payable_amt));
                        $("#payable_amt_to_saved").val(total_payable_amt);
                        // points

                        var pointData = "";
                        $.each(earnedpoints, function(keys, data) {
                            $.each(data, function(key, value) {
                                totalPoints += parseInt(value['points']);
                                pointData += `<div class="d-flex justify-content-between mt-2">
                                            <div>
                                                <span class="fw text font-size-14"> ${value['name']}</span><br>
                                                <span class="gray font-size-12">${value['reward']}% of total</span>
                                            </div>
                                            <h6 class="font-size-14" id="">${value['points']}</h6>
                                        </div>`;
                            });
                        });
                        $("#totalSumpoints").text(totalPoints);
                        $("#points-grp").html(pointData);

                        // pending amount 
                        $("#total_pending_amt").text(formatter.format(total_payable_amt - total_rcvd_amt));

                    }
                }
            });
        }
    </script>
@endsection
