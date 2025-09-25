@extends('admin.inc.layout')

<style>
    @media screen and (max-width: 480px) {
        .payment-details {
            padding-inline: 12px;
            padding-top: 5px;
        }
    }

    th {
        white-space: nowrap;
    }

    td {
        white-space: nowrap;
    }

    .payment-details {
        padding-inline: 12px;
        padding-top: 40px;
    }

    .table>:not(caption)>> {
        padding: 0.5rem 0.5rem;
        background-color: var(--bs-table-bg);
        border-bottom-width: 0 !important;
        box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
    }

    label {
        color: blue;
    }
    .vl{
        border-left: 2px solid #666cff;
        padding-right:5px;
   
    }
</style>
@section('content')
    @php
        $hasAtLeastOneValidStatus = $bookingInvoices->contains(function ($invoice) {
            return $invoice->invoice_status !== null && in_array($invoice->invoice_status, [0, 1]);
        });
        $billingToIds = json_decode($data->billing_to, true) ?? [];
    @endphp
    <div class="container-xxl flex-grow-1 container-p-y">
        
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h6 class="text fw-bold mb-4">Trip Booking Detail
                        @if($data->trip_status == 'Completed')
                            <span class="badge bg-success">
                                Completed
                            </span>
                        @elseif ($data->trip_status == 'Cancelled')
                            <span class="badge bg-danger">
                                Cancelled
                            </span>
                        @elseif ($data->trip_status == 'Correction')
                            <span class="badge bg-warning">
                                Correction Required
                            </span>
                        @elseif ($data->trip_status == 'Confirmed')
                            <span class="badge bg-success">
                                 Confirmed
                            </span>
                        @endif
                    </h6>
                    @if ($data->trip_status != 'Cancelled')
                        @if ($data->trip_status != 'Correction')
                            <div class=" d-block d-md-none">
                                @if ($data->invoice_sent_date == null)
{{--                                    (!isset($bookingInvoice) || auth()->user()->id == $bookingInvoice->invoice_sent_by) &&--}}
{{--                                        $data->invoice_sent_date == null)--}}
                                    @if ($hasAtLeastOneValidStatus)
                                        <a href="javaScript:void(0)" onclick="invoiceUpdate()">

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
                                                <path d="M3.87091 6.45142H5.16123V7.74174H3.87091V6.45142Z" fill="#FFB224" />
                                                <path d="M3.87091 3.87085H5.16123V5.16117H3.87091V3.87085Z" fill="#FFB224" />
                                                <path d="M3.87091 11.6128H5.16123V12.9031H3.87091V11.6128Z" fill="#FFB224" />
                                                <path d="M3.87091 9.03223H5.16123V10.3225H3.87091V9.03223Z" fill="#FFB224" />
                                                <path d="M3.87091 14.1934H5.16123V15.4837H3.87091V14.1934Z" fill="#FFB224" />
                                                <path
                                                    d="M15.4838 12.9031C12.9936 12.9031 10.9677 14.929 10.9677 17.4192C10.9677 19.9094 12.9936 21.9353 15.4838 21.9353C17.974 21.9353 20 19.9094 20 17.4192C20 14.929 17.974 12.9031 15.4838 12.9031ZM15.4838 20.13L13.1905 18.6011L13.9063 17.5275L14.8387 18.1492V14.8386H16.129V18.1492L17.0615 17.5276L17.7773 18.6012L15.4838 20.13Z"
                                                    fill="#FFB224" />
                                            </svg><span class="font-size-14">Upload Invoice</span>
                                        </a>
                                        <br>
                                    @endif
                                @endif

                                {{-- include status of booking file --}}
                                @include('admin.include.invoice-status')

                            </div>
                        @endif
                    @endif
                </div>
                <div class="card border-shadow">

                    <div class="card-body">
                        <div class="border-bottom">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold font-size-15">Trip Booking Id: #{{ $data->id }}</span>
                                <div>
                                    @php
                                        $checkRegForm = false;
                                    @endphp
                                    @foreach (json_decode($data->customer_id) as $key => $c_id)
                                        @php
                                            $travelers = getCustomerById($c_id);
                                            if ($travelers->email == null || $travelers->email == 'null') {
                                                $checkRegForm = true;
                                                break;
                                            }
                                        @endphp
                                    @endforeach

                                    @if ($checkRegForm)
                                        <a target="_blank"
                                            href="{{ env('USER_URL') . 'registration?token=' . $data->token . '&email=&trip_id=' . $data->trip_id . '&form_type=not_user' }}"
                                            class="btn btn-success btn-sm">
                                            Registration Form
                                        </a>
                                    @endif

                                    <a href="{{ route('booking.new-trip', ['token' => $data->token]) }}"
                                        class="btn btn-primary btn-sm">
                                        Edit
                                    </a>
                                    @if ($data->trip_status != 'Correction')
                                        @if ($data->trip_status != 'Cancelled')
                                            <a href="javaScript:void(0)" onclick="correctionConfirm()"
                                                class="btn btn-warning btn-sm">
                                                Need Correction
                                            </a>
                                        @endif
                                    @endif

                                    @if ($data->trip_status != 'Cancelled')
                                        <a href="javaScript:void(0)" onclick="cancelConfirm()"
                                            class="btn btn-danger btn-sm">
                                            Cancel Booking
                                        </a>
                                    @endif

                                    <a href="{{ route('booking.activity', $data->id) }}"
                                        class="btn btn-warning text-white btn-sm">
                                        Activity Log </a>

                                    <a href="{{ route('booking.index') }}" class="btn btn-secondary text-white btn-sm">
                                        Back </a>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6 pe-3">
                                <p class="fw-bold text font-size-14"><u>Trip Name</u></p>
                                <h5 class="fw-bold text font-size-18">{{ $data->trip->name ?? 'Trip is deleted' }}</h5>
                                <div class="trip-dates">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-12 col-md-12 col-12">
                                            <span class="font-size-12">Trip Start Date:</span><span
                                                class="fw-bold font-size-12"> &nbsp;
                                                {{ date('d M, y', strtotime($data->trip->start_date)) ?? 'Trip is deleted' }}</span>

                                        </div>
                                        <div class="col-xl-6 col-lg-12 col-md-12 col-12">
                                            <span class="font-size-12">Trip End Date:</span><span
                                                class="fw-bold font-size-12"> &nbsp;
                                                {{ date('d M, y', strtotime($data->trip->end_date)) ?? 'Trip is deleted' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                @php
                                    $firstTraveler = json_decode($data->customer_id)[0];
                                    $travelerOne = getCustomerById($firstTraveler);
                                @endphp
                                <div>
                                    <h6 style="font-size: 1.2rem;" class="fw-bold text mt-2 mb-0">{{ $travelerOne->name }}
                                    </h6>
                                    <div class="trip-dates">
                                        <p>
                                            {{ $travelerOne->address . ' ' . $travelerOne->city . ' ' . $travelerOne->state . ' ' . $travelerOne->pincode . ' ' . $travelerOne->country }}
                                        </p>
                                    </div>
                                    <h6 class="fw-bold text mt-2 font-size-14 mb-0">Phone number</h6>
                                    <div class="trip-dates font-size-14">
                                        <p>{{ $travelerOne->telephone_code . $travelerOne->phone }}</p>
                                    </div>
                                    <h6 class="fw-bold text mt-2 font-size-14 mb-0">Email</h6>
                                    <div class="trip-dates font-size-14">
                                        <p>{{ $travelerOne->email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mx-auto d-block">
                                <div class="row">
                                    <div class="col-md-6">

                                        <p class="fw-bold text font-size-14"><u>
                                                @isset($data->invoice_sent_date)
                                                    Points Earned
                                                @else
                                                    Points to be earned
                                                @endisset
                                            </u></p>

                                        <img src="{{ env('USER_URL') }}public/userpanel/asset/images/star.svg"
                                            alt="">
                                        <span class="text fw-bold information-trip-count" id="totalSumpoints"
                                            style="font-size: 22px">0</span>
                                        <small class="font-size-12">Points</small><br>
                                    </div>
                               

                                    @if ($data->trip_status != 'Cancelled')
                                        @if ($data->trip_status != 'Correction')
                                            @if (isset($data->trip->end_date) && $data->trip->end_date < date('Y-m-d'))
                                                <div class="col-md-6 d-none d-md-block ">
                                                    <p class="fw-bold text font-size-14"><u>More actions</u></p>
                                                    @if($bookingInvoices->count() < 5)
                                                        @if ((!isset($bookingInvoice) || auth()->user()->id == $bookingInvoice->invoice_sent_by) &&
                                                                $data->invoice_sent_date == null)
                                                            @if (!$hasAtLeastOneValidStatus)
                                                                <a href="javaScript:void(0)" onclick="invoiceUpdate()">

                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                        height="22" viewBox="0 0 20 22" fill="none">
                                                                        <path d="M6.45166 6.45142H11.613V7.74174H6.45166V6.45142Z"
                                                                            fill="#FFB224" />
                                                                        <path d="M6.45166 9.03223H11.613V10.3225H6.45166V9.03223Z"
                                                                            fill="#FFB224" />
                                                                        <path d="M6.45166 3.87085H11.613V5.16117H6.45166V3.87085Z"
                                                                            fill="#FFB224" />
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
                                                                    </svg><span class="font-size-14">Upload Invoice</span>
                                                                </a>
                                                                <br>
                                                            @endif

                                                        @endif
                                                    @endif
                                                    @if($bookingInvoices->count() > 0)
                                                    {{-- include status of booking file --}}
                                                        @include('admin.include.invoice-status')
                                                    @endif
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                                <br>
                                <div class="mt-4">
                                    <h6 class="text fw-bold font-size-14"><u>Trip Members Details</u>
                                    </h6>
                                    @foreach (json_decode($data->customer_id) as $key => $c_id)
                                        @php
                                            $travelers = getCustomerById($c_id);
                                        @endphp
                                        <span class="fw-bold text font-size-14">Traveler
                                            {{ $key + 1 }}
                                            :</span><span>&nbsp;{{ $travelers->name }}
                                            ({{ $travelers->relation }}, {{ getYearsFromDob($travelers->dob) }}
                                            yrs)
                                        </span> &nbsp;<a onclick="viewUserInfo({{ $c_id }})"
                                            href="javaScript:void(0)" data-bs-toggle="tooltip" data-bs-placement="right"
                                            title="View User Info" class="btn btn-warning btn-xs font-size-14">View
                                        </a>
                                        &nbsp;
                                        @if (isset($travelers->email) && $travelers->email != null && $travelers->email != 'null')
                                        <input type="hidden" id="seeker-link-{{ $c_id }}"
                                        value="{{ env('USER_URL') .'seeker?&email=' . $travelers->email  }}">

                                            <input type="hidden" id="traveler-link-{{ $c_id }}"
                                                value="https://www.adventuresoverland.com/booking-registration-form/">
                                                <!-- {{ env('USER_URL') . 'registration?token=' . $data->token . '&email=' . $travelers->email . '&trip_id=' . $data->trip_id }} -->
                                            <a href="javaScript:void(0)" data-bs-toggle="tooltip"
                                                data-bs-placement="right" title="Registration Link"
                                                onclick="copyData(this,{{ $c_id }})"
                                                class="btn btn-outline-warning btn-xs font-size-14 cpy">Copy
                                            </a>
                                        @endif
                                        <br>
                                    @endforeach

                                </div>
                                <div class="d-flex">
                                    @if(isset($data->relation_manager_names))
                                        <div class="mt-4">
                                                <h6 class="text fw-bold font-size-14">Relation Manager</h6>
                                                {{$data->relation_manager_names}}
                                        </div>
                                    @endif
                                    @if($data->admin_name)
                                        <div class="mt-4 ml-5" style="margin-left:85px;">
                                            <h6 class="fw-bold font-size-14">Spoc Name</h6>
                                            <span class="badge bg-success">{{$data->admin_name }}<span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-10 border-top pt-2 mb-3 table-responsive">
                                <table class="table border-0">
                                    <tr>
                                        <th scope="col">Lead Source</th>
                                        <th scope="col">Vehicle Type</th>
                                        <th scope="col">Vehicle Seat</th>
                                        <th scope="col">Room Type</th>
                                        <th scope="col">Room Category</th>
                                        <th scope="col">Payment From</th>
                                        <th scope="col">Bill To</th>
                                    </tr>

                                    <tr>
                                        <td>{{ $data->lead_source }} @isset($data->sub_lead_source)
                                                , {{ $data->sub_lead_source }}
                                            @endisset
                                        </td>
                                        <td>{{ $data->vehical_type }}</td>
                                        <td>{{ $data->vehical_seat }}</td>
                                        <td>
                                            @if ($data->room_info)
                                                @foreach (json_decode($data->room_info) as $key => $rinfo)
                                                    {{ $rinfo->room_type }}
                                                    @if (count(json_decode($data->room_info)) - 1 == $key)
                                                        {{ '' }}
                                                    @else
                                                        {{ ' ,' }}
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if ($data->room_info)
                                                @foreach (json_decode($data->room_info) as $key => $rinfo)
                                                    {{ $rinfo->room_cat }}
                                                    @if (count(json_decode($data->room_info)) - 1 == $key)
                                                        {{ '' }}
                                                    @else
                                                        {{ ' ,' }}
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>{{ $data->payment_from }} @isset($data->payment_from_cmpny)
                                                /{{ $data->payment_from_cmpny }}
                                            @endisset
                                            @isset($data->payment_by_customer_id)
                                                ( {{ getCustomerById($data->payment_by_customer_id)->name }})
                                            @endisset
                                        </td>
                                         <td>
                                            @if ($data->billing_to)
                                                @foreach (json_decode($data->billing_to) as $key => $rinfo)
                                                    {{ getCustomerById($rinfo)->name ?? "-" }}
                                                    @if (count(json_decode($data->billing_to)) - 1 == $key)
                                                        {{ '' }}
                                                    @else
                                                        {{ ' ,' }}
                                                    @endif
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>


                            @if ($data->sch_payment_list)
                                <div class="col-md-6 mt-4">
                                    <h5>Pending Payment Schedule</h5>
                                    <table class="table border-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">Date</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Comment</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalSchAmt = 0;
                                            @endphp
                                            @foreach (json_decode($data->sch_payment_list) as $key => $sch)
                                                @php
                                                    $totalSchAmt += $sch->amount;
                                                @endphp
                                                <tr>
                                                    <td>{{ date('d-m-Y', strtotime($sch->date)) }}</td>
                                                    <td>{{ '₹' . $sch->amount }}</td>
                                                    <td>{{ $sch->comment }}</td>
                                                    <td>
                                                        @if ($data->payment_amt == $data->payable_amt)
                                                            <span class="badge bg-success">Done</span>
                                                        @else
                                                            @if ($dueKey > $key)
                                                                <span class="badge bg-success">Done</span>
                                                            @elseif ($dueKey < $key)
                                                                <span class="badge bg-danger">Pending</span>
                                                            @elseif ($dueKey == $key)
                                                                @if ($pendingAmt > 0 && $pendingAmt == $sch->amount)
                                                                    <span class="badge bg-danger">Pending</span>
                                                                @elseif ($pendingAmt > 0)
                                                                    <span class="badge bg-warning">Partial</span>
                                                                @else
                                                                    <span class="badge bg-success">Done</span>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td scope="col">Total</td>
                                                <td scope="col">₹{{ $totalSchAmt }}</td>
                                            </tr>

                                        </tfoot>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


                {{-- summary --}}
                <div class="card border-shadow mt-3 pb-5" style="font-size: 12px;">
                    @include('admin.include.summary')
                </div>
            </div>
        </div>
    </div>

    {{-- delete confirmation --}}
    <div class="modal fade" id="cancelModalConfirm" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are You Sure Want
                        to Cancel?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="javaScript:void(0)" onclick="cancelBooking()" class="btn btn-danger">Cancel Booking
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for booking cancel -->
    <div class="modal fade" id="cancelModalBooking" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Cancel Trip Booking</h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <input type="hidden" id="can_id" name="id" value="{{ $data->id }}">

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline mb-3">
                                        <input type="number" id="can_cancelation_amount" name="cancelation_amount"
                                            class="form-control" placeholder="₹ " required />
                                        <label for="basic-default-fullname">Cancellation Charges (excl taxes) <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline mb-3">
                                        <input type="number" id="can_cancelation_amount_5_gst"
                                            name="cancelation_amount_5_gst" class="form-control" placeholder="₹ "
                                            required />
                                        <label for="basic-default-fullname">Cancellation charges (incl 5% GST) <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline mb-3">
                                        <input type="number" name="cancelation_amount_tcs" class="form-control"
                                            id="can_cancelation_amount_tcs" placeholder="₹ " required />
                                        <label for="basic-default-fullname">TCS/TDS to be adjusted <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline mb-3">
                                        <input type="number" name="cancelation_amount_refunded" class="form-control"
                                            id="can_cancelation_amount_refunded" placeholder="₹ " required />
                                        <label for="basic-default-fullname">Amount to be refunded via bank transfer <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline mb-3">
                                        <input type="number" name="cancelation_amount_credit_note" class="form-control"
                                            id="can_cancelation_amount_credit_note" placeholder="₹" required />
                                        <label for="basic-default-fullname">Amount of credit note to be raised <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input required type="text" name="cancelation_reason" class="form-control"
                                            id="can_cancelation_reason" placeholder="Comment" />
                                        <label for="basic-default-fullname">Comment </label>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="button" onclick="checkCancelationAmt()"
                                    class="btn btn-warning">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for booking payment update -->
    <div class="modal fade" id="paymentUpdate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Payment Update</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('booking.trip.add-payment') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <div class="row">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select name="payment_type" class="form-control" required>
                                        <option value="">Select Payment Type</option>
                                        <option value="Full Payment">Full Payment</option>
                                        <option value="Part Payment">Part Payment</option>
                                    </select>
                                    <label for="basic-default-fullname">Payment Type <span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>
                             <div class="row">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select name="billing_cust" id="billing_cust" class="form-control" required>
                                        <option value="">Select Payment By</option>
                                       @foreach($customers as $option)

                                            <option value="{{ $option }}">
                                                {{ getCustomerById($option)->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="billing_cust">Payment from <span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select onchange="remarkType(this.value)" name="remark" class="form-control"
                                        required>
                                        <option value="">Select Remarks Type</option>
                                        <option value="Package Cost">Package Cost</option>
                                        <option value="Extra Services">Extra Services</option>
                                        <option value="Vehicle Security">Vehicle Security</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <label for="basic-default-fullname">Add Remarks Type <span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="row" id="remark_type_cmt">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="comment" class="form-control"
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
                                            class="form-control" id="rm-inp-bal" placeholder="amount" required />
                                        <label for="rm-inp-bal">Amount <span class="text-danger">*</span></label>
                                        <input type="hidden" type="number"name="rm_balance" value="{{ $rmBal }}"/>
                                      
                                        <small>Remaining Amount ₹<span class="fw-bold" id="rm-blnc">{{ $rmBal }}</span></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="date" name="date" class="form-control"
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
   

    <!-- Modal for invoice Action By Verified Staff -->
    <div class="modal fade" id="invoiceActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Take Action</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('booking.trip.upload-invoice-action') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $data->id }}">

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <div class="form-group mb-4">
                                            <input type="radio" name="booking_action" value="0" id="edit-action"
                                                required>
                                            <label for="edit-action" style="color: black; cursor:pointer;">Edit and
                                                Re-upload</label>
                                        </div>
                                        <div class="form-group mb-4">
                                            <input type="radio" name="booking_action" value="1" id="save-action"
                                                required>
                                            <label for="save-action" style="color: black; cursor:pointer;">Everything
                                                found satisfactory,
                                                Send Invoice</label>
                                        </div>
                                        <div class="form-group mb-4">
                                            <textarea name="comment" class="form-control" placeholder="Comment"></textarea>
                                        </div>
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


    <!-- Modal for invoice update -->
    <div class="modal fade" id="invoiceUpdate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Upload Invoice</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('booking.trip.upload-invoice') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $data->id }}">

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input accept=".pdf" type="file" name="invoice_files[]" class="form-control"
                                               id="invoice_files" multiple required onchange="validateFileLimit(this)" />
                                        <label for="invoice_files">Invoices <span class="text-danger">*</span></label>
                                        <p id="file-limit-message" class="text-muted">Maximum of 5 invoices can be uploaded.</p>
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


    <!-- Modal for invocies list -->
    <div id="modalsContainer">
        @include('admin.include.multiple-invoices', ['bookingInvoices' => $bookingInvoices])
    </div>

{{--    <div class="modal fade" id="invoicesModelss" tabindex="-1" aria-hidden="true">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-header d-flex justify-content-between align-items-center">--}}
{{--                        <h5 class="mb-0">Invoices</h5>--}}
{{--                    </div>--}}
{{--                    <div class="card-body">--}}
{{--                        @php $index = 1; @endphp--}}
{{--                        <ul>--}}
{{--                            @foreach ($tripInvoices as $invoice)--}}
{{--                                    <li>--}}
{{--                                        <span>--}}
{{--                                            <a target="_blank" href="{{ url('storage/app/' . $invoice->invoice_path) }}">Invoice {{ $index }}  </a>--}}
{{--                                            @if (auth()->user()->id != $bookingInvoice->invoice_sent_by)--}}
{{--                                                <i id="approveIcon-{{ $invoice->id }}"--}}
{{--                                                   class="mdi mdi-check"--}}
{{--                                                   style="font-size: 16px; color: gray; cursor: pointer;"--}}
{{--                                                   onclick="toggleApprove({{ $invoice->id }})"></i>--}}
{{--                                                <i id="rejectIcon-{{ $invoice->id }}"--}}
{{--                                                   class="mdi mdi-close"--}}
{{--                                                   style="font-size: 16px; color: gray; cursor: pointer;"--}}
{{--                                                   onclick="toggleReject({{ $invoice->id }})"></i>--}}
{{--                                            @endif--}}
{{--                                        </span>--}}
{{--                                    </li>--}}
{{--                                @php $index++; @endphp--}}
{{--                                <br/>--}}
{{--                            @endforeach--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    <!-- Modal for view customer details -->
    <div class="modal fade" id="customerDetailsModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('booking.trip.upload-media') }}" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Customer Details</h5>
                            <div>
                                {{-- <a target="_blank" id="seeker" class="border-bottom">Edit Seeker Link</a> <span class="vl"></span> --}}
                              
                                <a target="_blank" id="route-extra-docx" class="border-bottom">Edit Registration Link</a>
                                &nbsp;

                            </div>
                        </div>
                        <div class="card-body">

                            @csrf
                            <input type="hidden" value="{{ $data->id }}" name="id">
                            <div id="customer_view_data">

                            </div>

                            <div class="text-center">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for booking correction -->
    <div class="modal fade" id="correctionModalBooking" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Correction Trip Booking</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('booking.trip.correction-booking') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $data->id }}">

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input required type="text" name="correction_reason" class="form-control"
                                            id="basic-default-fullname" placeholder="Correction Reason" />
                                        <label for="basic-default-fullname">Reason <span
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
@endsection

@section('script')
    <script>
             window.onload = function () {
                var selectedValue = document.getElementById("remarkSelect").value; 
                viewUserInfo(selectedValue); // Call the function with the pre-filled value
            };
        function validateFileLimit(input) {
            const maxFiles = 5; // Maximum allowed files
            const fileLimitMessage = document.getElementById('file-limit-message');

            // Check if the number of selected files exceeds the limit
            if (input.files.length > maxFiles) {
                fileLimitMessage.classList.remove('text-muted');
                fileLimitMessage.classList.add('text-danger');
                fileLimitMessage.textContent = `You can only upload a maximum of ${maxFiles} files. Please select fewer files.`;

                // Clear the file input to enforce the limit
                input.value = '';
            } else if (input.files.length > 0) {
                fileLimitMessage.classList.remove('text-danger');
                fileLimitMessage.classList.add('text-muted');
                fileLimitMessage.textContent = `${input.files.length} file(s) selected. Maximum of ${maxFiles} allowed.`;
            } else {
                fileLimitMessage.classList.remove('text-danger');
                fileLimitMessage.classList.add('text-muted');
                fileLimitMessage.textContent = `Maximum of ${maxFiles} invoices can be uploaded.`;
            }
        }
        function checkCancelationAmt() {
            var id = $("#can_id").val();
            var cancelation_amount = $("#can_cancelation_amount").val() ?? 0;
            var cancelation_amount_5_gst = $("#can_cancelation_amount_5_gst").val() ?? 0;
            var cancelation_amount_tcs = $("#can_cancelation_amount_tcs").val() ?? 0;
            var cancelation_amount_refunded = $("#can_cancelation_amount_refunded").val() ?? 0;
            var cancelation_amount_credit_note = $("#can_cancelation_amount_credit_note").val() ?? 0;
            var cancelation_reason = $("#can_cancelation_reason").val();
            var rec_amt = $("#total_rec_amt_inp").val();
            var sumAmt = parseInt(cancelation_amount_5_gst) + parseInt(cancelation_amount_tcs) + parseInt(
                    cancelation_amount_refunded) +
                parseInt(cancelation_amount_credit_note);

            if (sumAmt == rec_amt) {
                $.ajax({
                    url: "{{ route('booking.trip.cancel-booking') }}",
                    method: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "cancelation_amount": cancelation_amount,
                        "cancelation_amount_5_gst": cancelation_amount_5_gst,
                        "cancelation_amount_tcs": cancelation_amount_tcs,
                        "cancelation_amount_refunded": cancelation_amount_refunded,
                        "cancelation_amount_credit_note": cancelation_amount_credit_note,
                        "cancelation_reason": cancelation_reason,
                    },
                    success: function(res) {
                        $("#cancelModalBooking").modal("hide");
                        Toast.fire({
                            icon: "success",
                            title: "Booking has been cancelled successfully and Rs. " +
                                cancelation_amount_credit_note +
                                " as credit note has been added to customer account."
                        });
                        setTimeout(() => {
                            window.location.href = window.location.href;
                        }, 3000);
                    }
                });
            } else {
                Toast.fire({
                    icon: "error",
                    title: "Sum of Amount should be Equal to Total Amount Received"
                });
            }
        }
    </script>

    <script>
        function checkRmBlnc(val) {
            var rm = $("#rm-blnc").text();
            if (parseInt(val) > parseInt(rm)) {
                $("#rm-inp-bal").val(rm);
                Toast.fire({
                    icon: "warning",
                    title: "You can not add amount more than Remaining."
                });
            }
        }

        $("#remark_type_cmt").hide();
        $("#edit_remark_type_cmt").hide();


        function addPayment() {
            $("#paymentUpdate").modal('show');
        }
      

        function cancelConfirm() {
            $("#cancelModalConfirm").modal('show');
        }

        function correctionConfirm() {
            $("#correctionModalBooking").modal('show');
        }

        function cancelBooking() {
            $("#cancelModalConfirm").modal('hide');
            $("#cancelModalBooking").modal('show');
        }

        function invoiceUpdate() {
            $("#invoiceUpdate").modal('show');
        }
        function showInvoices() {
            $("#invoicesModel").modal('show');
        }

        function approveInvoice(invoiceId) {
            alert(`Invoice ${invoiceId} approved.`);
        }

        function rejectInvoice(invoiceId) {
            alert(`Invoice ${invoiceId} rejected.`);
        }

        function invoiceActionModal() {
            $("#invoicesModel").modal('hide');
            $("#invoiceActionModal").modal('show');
        }

        function viewUserInfo(id) {
            $.ajax({
                url: "{{ route('booking.trip.view-customer-details') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                    booking_id: "{{ $data->id }}"
                },
                success: function(res) {
                    if (!res) {
                        alert("Somthing Went Wrong, try again.")
                    } else {
                        var routeEdit = "{{ route('customer.edit', ['id' => 'rowID']) }}";
                        var customer = JSON.parse(res);
                        var extras = customer.extra_doc;
                        var text = `<div class="row" style="font-size: 12px !important;">
                                <hr>
                                <input type="hidden" name="c_id" value="${customer.id}">
                                <div class="row">
                                    <div class="col-6">
                                        Date Of Birth :
                                    </div>
                                    <div class="col-6">
                                        ${customer.dob  ?? ''}
                                    </div>
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        Meal Preference :
                                    </div>
                                    <div class="col-6">
                                        ${customer.meal_preference ?? ''}
                                    </div>
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        Blood Group :
                                    </div>
                                    <div class="col-6">
                                        ${customer.blood_group  ?? ''}
                                    </div>
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        Profession :
                                    </div>
                                    <div class="col-6">
                                        ${customer.profession  ?? ''}
                                    </div>
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        Emergency Contact Number :
                                    </div>
                                    <div class="col-6">
                                        ${customer.emg_contact  ?? ''}
                                    </div>
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        T-shirt Size :
                                    </div>
                                    <div class="col-6">
                                        ${customer.t_size  ?? ''}
                                    </div>
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        Medical Condition id any :
                                    </div>
                                    <div class="col-6">
                                        ${customer.medical_condition  ?? ''}
                                    </div>
                                    <hr>
                                </div>
                               
                                <div class="row">
                                    <div class="col-6">
                                        Passport Upload (Front) :
                                    </div>
                                    <div class="col-6">`;
                        if (customer.passport_front) {
                            text +=
                                `<a target="_blank" href="${customer.passport_front}">Download</a> &nbsp;&nbsp;
                                <a href="javaScript:void(0)" onclick="deleteUserMedia(${customer.id}, 'passport_front')" class="text-danger">Delete</a>`;
                        }
                        text += `

                                    </div>
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        Passport Upload (Back) :
                                    </div>
                                    <div class="col-6">`;
                        if (customer.passport_back) {
                            text +=
                                `<a target="_blank" href="${customer.passport_back}">Download</a> &nbsp;&nbsp;
                                <a href="javaScript:void(0)" onclick="deleteUserMedia(${customer.id}, 'passport_back')" class="text-danger">Delete</a>
                                `;
                        }
                        text += `  </div>
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        Pan Card / GST Certificate :
                                    </div>
                                    <div class="col-6">`;
                        if (customer.pan_gst) {
                            text +=
                                `<a target="_blank" href="${customer.pan_gst}">Download</a> &nbsp;&nbsp;
                                <a href="javaScript:void(0)" onclick="deleteUserMedia(${customer.id}, 'pan_gst')" class="text-danger">Delete</a>`;
                        }
                        text += `
                                    </div>
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        Adhaar Card Upload :
                                    </div>
                                    <div class="col-6">`;
                        if (customer.adhar_card) {
                            text +=
                                `<a target="_blank" href="${customer.adhar_card}">Download</a> &nbsp;&nbsp;
                                <a href="javaScript:void(0)" onclick="deleteUserMedia(${customer.id}, 'adhar_card')" class="text-danger border-bottom">Delete</a>
                                `;
                        }
                        text += `  </div>
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        Driving Licence Upload :
                                    </div>
                                    <div class="col-6">`;
                        if (customer.driving) {
                            text +=
                                `<a target="_blank" href="${customer.driving}">Download</a> &nbsp;&nbsp;
                                <a href="javaScript:void(0)" onclick="deleteUserMedia(${customer.id}, 'driving')" class="text-danger">Delete</a>
                                `;
                        }
                        text += `   </div>
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        Profile Picture Upload :
                                    </div>
                                    <div class="col-6">`;
                        if (customer.profile) {
                            text +=
                                `<a target="_blank" href="${customer.profile}">Download</a> &nbsp;&nbsp;
                                <a href="javaScript:void(0)" onclick="deleteUserMedia(${customer.id}, 'profile')" class="text-danger">Delete</a>
                                `;
                        }
                        text += ` </div>
                                    <hr>
                                </div>
                               
                            `;

                        text += `
                                <div class="row">
                                    <div class="col-6">
                                        Term's & Condition :
                                    </div>
                                    <div class="col-6">
                                        ${customer.terms_accepted  ?? ''}
                                    </div>
                                    <hr>
                                </div>`;

                          
                       
                        text += `
                                <div class="row">
                                    <div class="col-6">
                                       <h6>Extra Documents</h6>
                                    </div>
                                </div>`;

                        $.each(extras, function(index, val) {
                            text += `<div class="row">
                                        <div class="col-6">
                                            ${val.title  ?? ''} :
                                        </div>
                                        <div class="col-6">
                                            <a target="_blank" href="${val.image}">Download</a> &nbsp;&nbsp;
                                        </div>
                                        <hr>
                                    </div>`;
                        })


                        $("#customer_view_data").html(text);
                        routeEdit = routeEdit.replace('rowID', customer.id);
                        $("#route-edit").attr('href', routeEdit);
                        $("#route-extra-docx").attr('href', $("#traveler-link-" + id).val());
                        // $("#seeker").attr('href', $("#seeker-link-" + id).val());

                        $("#customerDetailsModal").modal('show');
                    }
                }

            });

        }

        function deleteUserMedia(c_id, clmn) {
            $.ajax({
                url: "{{ route('booking.trip.delete-media') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    c_id: c_id,
                    clmn: clmn,
                },
                success: function(res) {
                    if (res) {
                        $("#customerDetailsModal").modal('hide');
                        viewUserInfo(res);
                    }
                }
            });
        }
    </script>


    <script>
        function copyData(anchor, id) {
            var copyText = document.getElementById("traveler-link-" + id);

            copyText.select();
            navigator.clipboard.writeText(copyText.value);
            anchor.innerText = "Copied";
        }
    </script>
@endsection
