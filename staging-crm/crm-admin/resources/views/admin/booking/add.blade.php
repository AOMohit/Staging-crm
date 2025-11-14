@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">
                <a href="{{ route('booking.index') }}">Booking</a>/</span>Add New</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-md-10 col-sm-12 col-xs-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Create Booking</h5>
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
                        {{-- <form action="" method="POST"> --}}
                        {{-- booking for --}}
                        <div class="row">
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select onchange="bookingFor(this.value)" name="booking_for" id="booking_for"
                                        class="form-control">
                                        
                                        <option value="">Select Booking For</option>
                                        <option @if (isset($data) && $data->booking_for == 'Solo') selected @endif value="Solo">Solo
                                        </option>
                                        <option @if (isset($data) && $data->booking_for == 'Friends') selected @endif value="Friends">
                                            Friends</option>
                                        <option @if (isset($data) && $data->booking_for == 'Family') selected @endif value="Family">Family
                                        </option>
                                    </select>
                                    <label for="booking_for">Booking For<span class="text-danger fixed">*</span></label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select onchange="expeditionData(this.value)" name="expedition" id="expedition" class="form-control">
                                        <option value="">Select Trip Type</option>
                                        <option @if (isset($data) && $data->expedition == 'Fixed Departure') selected @endif value="Fixed Departure">
                                            Fixed Departure</option>
                                        <option @if (isset($data) && $data->expedition == 'Tailor Made') selected @endif value="Tailor Made">
                                            Tailor Made</option>
                                        <option @if (isset($data) && $data->expedition == 'Self Drive Tailormade') selected @endif value="Self Drive Tailormade">
                                            Self Drive Tailormade</option>
                                    </select>
                                    <label for="expedition">Trip Type<span class="text-danger fixed">*</span></label>
                                </div>
                            </div>
                        </div>

                        {{-- Trip --}}
                        <div class="col-12 row">
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select onchange="tripData(this.value)" name="trip_id" class="form-control"
                                        id="expedition_trips">
                                        <option value="">Select Trip Name</option>
                                        @foreach ($trips as $trip)
                                        <span>{{$trip->id}}</span>
                                            <option @if(isset($data->trip_id) && $data->trip_id == $trip->id) selected @endif
                                                value="{{ $trip->id }}">{{ $trip->name }} @if ($trip->status == 'Sold Out')
                                                    (Sold Out)
                                                @endif
                                            </option>
                                        @endforeach

                                    </select>
                                    <label for="basic-default-fullname">Trip Name<span
                                            class="text-danger fixed">*</span></label>
                                    <small>Trip Cost: ₹<span id="trip_amt">
                                            @if (isset($data) && $data->trip_id)
                                                {{ getTripById($data->trip_id)->price }}
                                            @endif
                                        </span></small>
                                </div>
                            </div>
                        </div>

                        {{-- customer --}}
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-floating form-floating-outline">
                                    <select onchange="getSelectedCustomers(this)" name="customer_id[]" id="customer_ids"
                                        class="select2 form-select form-select-lg" data-allow-clear="true" multiple>
                                        @foreach ($customerswithChild as $customer)
                                            <option @if(isset($data) && isset($data->customer_id) && in_array($customer->id, json_decode($data->customer_id))) selected @endif
                                                value="{{ $customer->id }}">
                                                {{ $customer->first_name . ' ' . $customer->last_name . ' (' . $customer->identity . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">*Please select your primary customer first before choosing others.</small>
                                    <label for="customer_id">Customer Name<span class="text-danger fixed">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <div class="text-end">
                                    <a data-bs-toggle="modal" data-bs-target="#customerAdd" class="btn btn-success"
                                        href="javaScript:void(0)">+ Add New Customer</a>
                                    <a onclick="addNewMember()" class="btn btn-warning" id="addNewMembers"
                                        href="javaScript:void(0)">+ Add New
                                        Member</a>
                                </div>
                            </div>

                            <div class="row" id="selected-customer-list">


                            </div>
                        </div>

                        {{-- Lead source --}}
                        <div class="col-12 row mt-4">
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select onchange="leadSource(this.value)" name="lead_source" id="lead_source"
                                        class="form-control">
                                        <option value="">Select Lead Source</option>
                                        <option @if (isset($data) && $data->lead_source == 'Agent') selected @endif value="Agent">Agent
                                        </option>
                                        <option @if (isset($data) && $data->lead_source == 'Social Media') selected @endif value="Social Media">
                                            Social Media</option>
                                        <option @if (isset($data) && $data->lead_source == 'Referrals') selected @endif value="Referrals">
                                            Referrals</option>
                                        <option @if (isset($data) && $data->lead_source == 'Organic') selected @endif value="Organic">
                                            Organic</option>
                                        <option @if (isset($data) && $data->lead_source == 'Repeated') selected @endif value="Repeated">
                                            Repeated</option>
                                    </select>
                                    <label for="lead_source">Lead Source<span class="text-danger fixed">*</span></label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4 sub_lead_source @if (isset($data) && $data->lead_source == 'Repeated') sub_lead_source_repeat @endif"
                                    id="referal_case">
                                    <select onchange="subLeadSource(this.value)" name="sub_lead_source" id="sub_lead_source"
                                        class="form-control">
                                        <option
                                            value="@if (isset($data) && $data->sub_lead_source) {{ $data->sub_lead_source }} @endif">
                                            @if (isset($data) && $data->sub_lead_source)
                                                {{ $data->sub_lead_source }}
                                            @endif
                                        </option>

                                    </select>
                                    <label for="sub_lead_source">Sub Lead Source<span
                                            class="text-danger fixed">*</span></label>
                                </div>
                            </div>

                        </div>

                        {{-- vehicle --}}
                        <div class="row">
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select onchange="vehicleType(this.value)" name="vehical_type" class="form-control">
                                        <option value="">Select Vehicle</option>
                                        <option @if (isset($data) && $data->vehical_type == 'AO Vehicle') selected @endif value="AO Vehicle">
                                            AO
                                            Vehicle</option>
                                        <option @if (isset($data) && $data->vehical_type == 'Own Vehicle') selected @endif value="Own Vehicle">Own
                                            Vehicle</option>
                                        <option @if (isset($data) && $data->vehical_type == 'AO Vehicle Self Drive') selected @endif
                                            value="AO Vehicle Self Drive">AO Vehicle Self Drive(Crew Car)</option>
                                        <option @if (isset($data) && $data->vehical_type == 'Chauffeur Driven') selected @endif
                                            value="Chauffeur Driven">Chauffeur Driven</option>
                                        <option @if (isset($data) && $data->vehical_type == 'Other') selected @endif value="Other">Other
                                        </option>
                                    </select>
                                    <label for="">Vehicle Type<span
                                            class="text-danger fixed tailor-made">*</span></label>
                                </div>
                            </div>
                            <div class="col-6" id="vehicle_type_other_cmt_box"
                                @if (isset($data) && $data->expedition == 'Tailor Made' && $data->vehical_type == 'Other') style="display:block;" @endif>
                                <div class="form-floating form-floating-outline mb-4">
                                    <input onblur="vehicleTypeCmt(this.value)" type="text" id="vehicle_type_other_cmt"
                                        class="form-control" value="<?php if (isset($data) && $data->vehicle_type_other_cmt) {
                                            echo $data->vehicle_type_other_cmt;
                                        } ?>" placeholder="Enter Comment">
                                    <label for="vehicle_type_other_cmt">Comment</label>
                                </div>
                            </div>
                        </div>

                        {{-- vehicle seat --}}
                        <div class="col-12 row">
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select onchange="vehicleSeat(this.value)" name="vehicle_seat" class="form-control">
                                        <option value="">Select Vehicle Seat</option>
                                        <option @if (isset($data) && $data->vehical_seat == '0.25') selected @endif value="0.25">0.25
                                        </option>
                                        <option @if (isset($data) && $data->vehical_seat == '0.5') selected @endif value="0.5">0.5
                                        </option>
                                        <option @if (isset($data) && $data->vehical_seat == '0.75') selected @endif value="0.75">0.75
                                        </option>
                                        <option @if (isset($data) && $data->vehical_seat == '1') selected @endif value="1">1
                                        </option>
                                    </select>
                                    <label for="">Vehicle Seat</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4" id="vehical_seat_amt">
                                    <input onblur="vehicleSeatPrice(this.value)" type="number" id="vehical_seat_amt"
                                        class="form-control" value="<?php if (isset($data) && $data->vehical_seat_amt) {
                                            echo $data->vehical_seat_amt;
                                        } ?>" placeholder="Enter Amount">
                                    <label for="vehical_seat_amt">Amount</label>
                                </div>
                            </div>
                        </div>

                        {{-- vehicle security --}}
                        <div class="col-12 row">
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input onblur="vehicleSecAmt(this.value)" type="number" name="vehical_security_amt"
                                        class="form-control" value="<?php if (isset($data) && $data->vehical_security_amt) {
                                            echo $data->vehical_security_amt;
                                        } ?>"
                                        placeholder="Vehicle Security Amount">
                                    <label for="basic-default-fullname">Vehicle Security Amount</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input onblur="vehicleSecAmtCmt(this.value)" type="text"
                                        value="@isset($data) {{ $data->vehical_security_amt_cmt }} @endisset"
                                        name="vehical_security_amt_cmt" class="form-control" placeholder="Comment">
                                    <label for="basic-default-fullname">Comment</label>
                                </div>
                            </div>
                        </div>
                        {{-- Room Number --}}
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline mb-4">
                                <select onchange="roomNumber(this.value)" name="no_of_rooms" id="no_of_rooms" class="form-control">
                                    <option value="">Select Number Of Rooms</option>
                                     {{-- @if (!empty($data) && !empty($data->no_of_rooms))
                                        <option value="{{ $data->no_of_rooms }}" selected>
                                            {{ $data->no_of_rooms }}
                                        </option>
                                    @endif --}}
                                </select>
                                <label for="no_of_rooms">Number Of Rooms<span class="text-danger fixed tailor-made">*</span></label>
                            </div>
                        </div>

                        {{-- room type, amt, category --}}
                        <form id="room_type_amt_cat">
                            @csrf
                            <input type="hidden" name="token" value="{{ request()->token }}">
                            <div class="row" id="data_by_room">

                            </div>
                            <div class="text-center mb-4" id="data_by_room_btn">
                                <a href="javaScript:void(0)" onclick="roomInfo()" class="btn btn-warning">Save Room
                                    Info</a>
                                <hr>
                            </div>
                        </form>

                        {{-- payment from --}}
                        <div class="row mb-1">
                            <div class="col-6">
                                <div class="form-floating form-floating-outline">
                                    <select onchange="paymentFrom(this.value)" name="payment_from" class="form-control">
                                        <option value="">Payment From</option>
                                        <option @if (isset($data) && $data->payment_from == 'Individual') selected @endif value="Individual">
                                            Individual</option>
                                        <option @if (isset($data) && $data->payment_from == 'Company') selected @endif value="Company">
                                            Company</option>
                                    </select>
                                    <label for="">Payment From<span
                                            class="text-danger fixed tailor-made">*</span></label>
                                </div>
                            </div>

                            <div class="col-6" id="payment_from_company"
                                @if (!isset($data) || $data->payment_from_cmpny == null || $data->payment_from == 'Individual') style="display: none" @endif>
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" onblur="paymentFromCmpny(this.value)" name="payment_from_cmpny"
                                        id="payment_from_cmpny_name" class="form-control"
                                        value="@if (isset($data) && $data->payment_from_cmpny) {{ $data->payment_from_cmpny }} @endif"
                                        placeholder="Company">
                                    <label for="basic-default-fullname">Company<span
                                            class="text-danger fixed tailor-made">*</span></label>
                                </div>
                            </div>
                            <div class="col-6" id="payment_from_tds"
                                @if (isset($data) && $data->is_multiple_payment == 1) style="display: none" @endif
                                @if (!isset($data) || $data->payment_from_tax == null || $data->payment_from == 'Company') style="display: none" @endif>
                                <div class="form-floating form-floating-outline">
                                    <select onchange="paymentFromTax(this.value)" id="payment_from_tax"
                                        name="payment_from_tax" class="form-control">
                                        {{-- <option value="">TCS</option> --}}
                                        <option @if (isset($data) && $data->payment_from_tax == 'Auto') selected @endif value="Auto">
                                            Auto</option>
                                        <option @if (isset($data) && $data->payment_from_tax == 'Manual') selected @endif value="Manual">
                                            Manual</option>
                                        <option @if (isset($data) && $data->payment_from_tax == '0') selected @endif value="0">
                                            0%</option>
                                        <option @if (isset($data) && $data->payment_from_tax == '5') selected @endif value="5">
                                            5%</option>
                                        <option @if (isset($data) && $data->payment_from_tax == '20') selected @endif value="20">
                                            20%</option>

                                    </select>
                                    <label for="">TCS<span class="text-danger fixed tailor-made">*</span></label>
                                </div>
                                <!-- Edit Button -->
                                <div id="editManualTaxButton"
                                     @if (isset($data) && $data->payment_from_tax == 'Manual')
                                         style="display: block; margin-top: 10px;"
                                     @else
                                         style="display: none; margin-top: 10px;"
                                    @endif>
                                    <button type="button" class="btn btn-secondary" onclick="editManualTax()">Edit Manual Tax</button>
                                </div>
                            </div>
                        </div>

                        {{-- all Payment --}}
                        <div class="form-group mb-4">
                            <input @if (isset($data) && $data->payment_all_done_by_this == 1) checked @endif type="checkbox"
                                onchange="paymentDoneBy(this.value)" id="payment_all_done_by_this"
                                name="payment_all_done_by_this">
                            <label for="payment_all_done_by_this">Allocate loyalty points exclusively to customer making
                                the purchase.</label>

                            @if (isset($data) && $data->payment_all_done_by_this == 1 && $data->payment_by_customer_id)
                                @php
                                    $customer = getCustomerById($data->payment_by_customer_id);
                                @endphp
                                [<span class="fw-bold">{{ $customer->first_name }} {{ $customer->last_name }}</span>]
                            @else
                                <span class="fw-bold payAllCustomer"></span>
                            @endif
                        </div>


                        <div class="col-6 mb-4" id="billing_customer" 
                     
                                @if (isset($data) && $data->is_multiple_payment == 1) style="display: none" @endif
                                @if (!isset($data) || $data->payment_from_tax == null || $data->payment_from == 'Company') style="display: none" @endif>
                                <div class="form-floating form-floating-outline">
                                    <select onchange="getbillingCustomers(this)" name="billing_customer_id[]" id="billing_customer_ids"
                                            class="select2 form-select form-select-lg" data-allow-clear="true" multiple>
                                                    <option value="">No billing customers available</option>
                                               
                                    </select>
                                    <label for="customer_id">Bill To<span class="text-danger fixed">*</span></label>
                                </div>
                        </div>
                        {{-- trip cost per person --}}
                        <div id="trip_costing">
                            <h6>Add Trip Cost: </h6>
                           
                            <div class="mb-4" id="multiple_payment_gst"
                                @if (isset($data) && $data->payment_from == 'Individual' && $data->is_multiple_payment == 1) @else style="display: none;" @endif>
                                <input onchange="multiplePayment()" type="checkbox" id="multiple_payment"
                                    @if (isset($data) && $data->payment_from == 'Individual' && $data->is_multiple_payment == 1) checked @endif>
                                <label for="multiple_payment">
                                    Wants to add Multiple Payment Options
                                </label>
                            </div>
                            <form id="trip_amt_details_form">
                                <div id="trip_amt_details">
                                    
                                    
                                </div>
                               
                                 
                                <div class="text-center">
                                    <a href="javaScript:void(0)"
                                       onclick="saveCosts()"
                                       class="btn btn-warning"
                                       >
                                        Save Trip Cost
                                    </a>
                                </div>
                            </form>
                           
                        </div>

                        <hr>
                        {{-- extra Services --}}
                        <div class="mb-4">
                            <div>
                                <h6>Extra Services: <a href="javaScript:void(0)" onclick="addNewField()"
                                        class="btn btn-success m-1"> +
                                        Add Services</a></h6>
                            </div>

                            <div id="fieldsContainer">
                                <div class="row field">
                                    <div class="form-group col-2 mt-4">
                                        <label for="exampleInputEmail1">Select Traveler</label>
                                        <select name="extra_traveler[]" id="selected-travelers" class="form-control">
                                            <option value="">Traveler</option>

                                        </select>
                                    </div>

                                    <div class="form-group col-2 mt-4">
                                        <label for="exampleInputEmail1">Extra Services</label>
                                        <select name="extra_services[]" class="form-control">
                                            <option value="">Services</option>
                                            @foreach ($extraServices as $es)
                                                <option value="{{ $es->title }}">{{ $es->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-2 mt-4">
                                        <label for="exampleInputEmail1">Amount</label>
                                        <input type="number" name="extra_amount[]" class="form-control"
                                            id="exampleInputEmail1" placeholder="amount">
                                    </div>

                                    <div class="form-group col-2 mt-4">
                                        <label for="exampleInputEmail1">Markup</label>
                                        <input type="number" name="extra_markup[]" class="form-control"
                                            id="exampleInputEmail1" placeholder="amount">
                                    </div>

                                    <div class="form-group col-1 mt-4">
                                        <label for="exampleInputEmail1">Tax %</label>
                                        <input type="text" name="extra_tax[]" value="18" class="form-control"
                                            id="exampleInputEmail1" readonly>
                                    </div>

                                    <div class="form-group col-2 mt-4">
                                        <label for="exampleInputEmail1">Comment</label>
                                        <input type="text" name="extra_comment[]" class="form-control"
                                            id="exampleInputEmail1" placeholder="Comment">
                                    </div>

                                    <div class="form-group col-1 mt-4">
                                        <a href="javaScript:void(0)" onclick="removeSingleField(this)"
                                            class="badge bg-danger mt-4">
                                            <b>X</b> </a>
                                    </div>
                                </div>
                                <form id="extraServicesForm">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ request()->token }}">
                                    <div class="row" id="cloneContainer">
                                        @if (isset($data) && $data->extra_services != null)
                                            @foreach (json_decode($data->extra_services) as $service)
                                                <div class="row">
                                                    <div class="form-group col-2 mt-4">
                                                        <label for="exampleInputEmail1">Select Traveler</label>
                                                        <select name="extra_traveler[]" class="form-control">
                                                            <option value="{{ $service->traveler }}">
                                                                {{ getCustomerById($service->traveler)->name }}
                                                            </option>

                                                        </select>
                                                    </div>

                                                    <div class="form-group col-2 mt-4">
                                                        <label for="exampleInputEmail1">Extra Services</label>
                                                        <select name="extra_services[]" class="form-control">
                                                            <option value="">Services</option>
                                                            @foreach ($extraServices as $es)
                                                                <option @if ($service->services == $es->title) selected @endif
                                                                    value="{{ $es->title }}">{{ $es->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-2 mt-4">
                                                        <label for="exampleInputEmail1">Amount</label>
                                                        <input type="number" name="extra_amount[]"
                                                            value="{{ $service->amount }}" class="form-control"
                                                            id="exampleInputEmail1" placeholder="amount">
                                                    </div>

                                                    <div class="form-group col-2 mt-4">
                                                        <label for="exampleInputEmail1">Markup</label>
                                                        <input type="number" name="extra_markup[]"
                                                            value="{{ $service->markup }}" class="form-control"
                                                            id="exampleInputEmail1" placeholder="amount">
                                                    </div>

                                                    <div class="form-group col-1 mt-4">
                                                        <label for="exampleInputEmail1">Tax %</label>
                                                        <input type="text" name="extra_tax[]" value="18"
                                                            class="form-control" id="exampleInputEmail1" readonly>
                                                    </div>

                                                    <div class="form-group col-2 mt-4">
                                                        <label for="exampleInputEmail1">Comment</label>
                                                        <input type="text" name="extra_comment[]"
                                                            value="{{ $service->comment }}" class="form-control"
                                                            id="exampleInputEmail1" placeholder="Comment">
                                                    </div>

                                                    <div class="form-group col-1 mt-4">
                                                        <a href="javaScript:void(0)" onclick="removeSingleField(this)"
                                                            class="badge bg-danger mt-4">
                                                            <b>X</b> </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="text-center mt-4" id="extraServces">
                                        <a href="javaScript:void(0)" onclick="saveExtraServices()"
                                            class="btn btn-warning">Save Extra
                                            Services</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr>

                        {{-- tax info --}}
                        <div class="form-group mt-4 mb-4">
                            <input @if (isset($data) && $data->tax_required == 1) checked @endif onchange="taxInfo('all')"
                                type="checkbox" id="tax_required" name="tax_required">
                            <label for="tax_required">Is tax not required for this booking?</label>
                        </div>

                        {{-- tds info --}}
                        <div class="form-group mt-4 mb-4 @if (isset($data) && $data->payment_from == 'Individual') is_tds_parent @endif"
                            id="is_tds_parent">
                            <input @if (isset($data) && $data->is_tds == 0) checked @endif onchange="taxInfo('tds')"
                                type="checkbox" id="is_tds" name="is_tds">
                            <label for="is_tds">TDS Not Deductible</label>
                        </div>

                        {{-- payment type --}}
                        <div class="col-12 row  mb-4">
                            <div class="col-4">
                                <div class="form-floating form-floating-outline">
                                    <select onchange="paymentType(this.value)" id="payment_type" class="form-control" @if(isset($data) && $data->payment_type) disabled @endif >
                                        <option value="">Payment Type</option>
                                        <option @if (isset($data) && $data->payment_type == 'Full Payment') selected @endif value="Full Payment">
                                            Full Payment</option>
                                        <option @if (isset($data) && $data->payment_type == 'Part Payment') selected @endif value="Part Payment">
                                            Part Payment</option>
                                    </select>
                                    <label for="">Payment Type<span
                                            class="text-danger fixed tailor-made">*</span></label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="number" onblur="paymentAmt(this.value)" value="<?php if (isset($data) && $data->payment_amt) {
                                        echo $data->payment_amt;
                                    } ?>"
                                        name="payment_amt" id="last_pay_amt" class="form-control" placeholder="Amount" @if(isset($data) && $data->payment_amt) disabled @endif>
                                    <label for="basic-default-fullname">Amount<span
                                            class="text-danger fixed tailor-made">*</span></label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="date" onchange="paymentDate(this.value)" value="<?php if (isset($data) && $data->payment_date) {
                                        echo $data->payment_date;
                                    } ?>"
                                        name="payment_date" class="form-control" placeholder="Date" id="" @if(isset($data) && $data->payment_date) disabled @endif
                                        max="{{ date('Y-m-d') }}"  >
                                    <label for="basic-default-fullname">Date<span
                                            class="text-danger fixed tailor-made">*</span></label>
                                </div>
                            </div>
                        </div>

                        <hr>

                       <div class="form-group mt-4 mb-4">
                            <input type="checkbox" id="is_Amount_paid"
                                @if (isset($data) && $data->is_full_Amount_paid == 1) checked @endif>
                            <label for="is_Amount_paid">Is Amount Paid in Full</label>
                        </div>
                        {{-- Payment Schedule --}}
                        <div class="mb-4"  id="schPaymentSection">
                            <div>
                                <h6>Pending Payment Schedule:
                                    <button  href="javaScript:void(0)" onclick="addNewSchField()"
                                    class="btn btn-success m-1"  id="addnewschbtn" @if (isset($data) && $data->is_full_Amount_paid == 1) disabled @endif> + Add Schedule</button></h6>
                            </div>
                           
                       @if ( (!$data) || $data->is_full_Amount_paid == "0" && $data->sch_payment_list == null )
                                <div class="row" id="is_no_payment" >
                                    <div class="form-group col-3 mt-4">
                                        <label for="schDate">Date<span class="text-danger">*</span></label>
                                        <input type="date" name="sch_date[]" class="form-control" id="schDate">
                                    </div>

                                    <div class="form-group col-3 mt-4">
                                        <label for="schAmt">Amount<span class="text-danger">*</span></label>
                                        <input type="number" name="sch_amount[]" class="form-control sch_payment_amt"
                                            id="schAmt" placeholder="amount">
                                    </div>

                                    <div class="form-group col-3 mt-4">
                                        <label for="schCmt">Comment</label>
                                        <input type="text" name="sch_comment[]" class="form-control" id="schCmt"
                                            placeholder="Comment">
                                    </div>

                                    
                                </div>
                                @endif
                            

                            <div id="scheduleFieldsContainer">
                                
                                <div class="row schField">
                                    <div class="form-group col-3 mt-4">
                                        <label for="schDate">Date<span class="text-danger">*</span></label>
                                        <input type="date" name="sch_date[]" class="form-control" id="schDate">
                                    </div>

                                    <div class="form-group col-3 mt-4">
                                        <label for="schAmt">Amount<span class="text-danger">*</span></label>
                                        <input type="number" name="sch_amount[]" class="form-control sch_payment_amt"
                                            id="schAmt" placeholder="amount">
                                    </div>

                                    <div class="form-group col-3 mt-4">
                                        <label for="schCmt">Comment</label>
                                        <input type="text" name="sch_comment[]" class="form-control" id="schCmt"
                                            placeholder="Comment">
                                    </div>

                                    <div class="form-group col-2 mt-4">
                                        <a href="javaScript:void(0)" onclick="removeSingleSchField(this)"
                                            class="badge bg-danger mt-4">
                                            <b>X</b> </a>
                                    </div>
                                </div>
                                <form id="pymentSchForm">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ request()->token }}" id="schtoken">
                                    <div class="row" id="cloneSchContainer">
                                        @if (isset($data) && $data->sch_payment_list != null)
                                            @foreach (json_decode($data->sch_payment_list) as $key => $sch)
                                                <div class="row">

                                                    <div class="form-group col-3 mt-4">
                                                        <label for="">Date<span
                                                                class="text-danger">*</span></label>
                                                        <input type="date" name="sch_date[]"
                                                            value="{{ $sch->date }}" class="form-control"
                                                            id="">
                                                    </div>

                                                    <div class="form-group col-3 mt-4">
                                                        <label for="">Amount<span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" name="sch_amount[]"
                                                            value="{{ $sch->amount }}"
                                                            class="form-control sch_payment_amt" id=""
                                                            placeholder="amount">
                                                    </div>

                                                    <div class="form-group col-3 mt-4">
                                                        <label for="">Comment</label>
                                                        <input type="text" name="sch_comment[]"
                                                            value="{{ $sch->comment }}" class="form-control"
                                                            id="" placeholder="comment">
                                                    </div>

                                                    <div class="form-group col-2 mt-4">
                                                        <a href="javaScript:void(0)" onclick="removeSingleSchField(this)"
                                                            class="badge bg-danger mt-4">
                                                            <b>X</b> </a>
                                                    </div>

                                                    <div class="form-group col-1 mt-4">
                                                        <div class="mt-4">
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
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="text-center mt-4" id="paymentScheduleBtn"
                                        @if (isset($data) && $data->sch_payment_list != null) style="display: block;" @endif >
                                        <button href="javaScript:void(0)" onclick="savePaymentSchedule()"
                                            class="btn btn-warning" id="savebtnsch" @if (isset($data) && $data->is_full_Amount_paid == 1) disabled @endif>Save
                                            Payment Schedule</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr>

                        {{-- bill summary --}}
                        <div class="mb-4 col-12" style="font-size: 12px;">
                            <div class="card border-shadow mt-3 pb-5">
                                @include('admin.include.summary')
                            </div>
                        </div>
                        <input type="hidden" id="payable_amt_to_saved" value="">
                        <div class="text-center mt-4">
                            <button onclick="submitBookingForm()" class="btn btn-primary">Submit</button>
                        </div>
                        {{-- </form> --}}
                    </div>
                </div>
            </div>
        </div>

        <div id="manualTaxPopupContainer">
            @include('admin.include.manual-tax', ['customers' => []]) <!-- Initially empty -->
        </div>

        @include('admin.include.booking');
    @endsection


    <style>
        #fieldsContainer> :first-child,
        #scheduleFieldsContainer> :first-child,
        #trip_costing,
        #extraServces,
   
        #vehicle_type_other_cmt_box,
        .sub_lead_source_repeat,
        .is_tds_parent {
            display: none;
        }
    </style>

    @section('script')



    
        {{-- add Member form for family and friend --}}
        <script>

        $(document).on("change", "#is_Amount_paid", function () {
            const isChecked = $(this).prop("checked");
            let token= $('#schtoken').val();
            if (isChecked) {
                $("#addnewschbtn").prop("disabled", true);
                $("#is_no_payment").hide();
                 $("#paymentScheduleBtn").hide();
                
            
               $.ajax({
                    url: "{{ route('booking.trip.ischeckedStore') }}",
                    type: "POST",
                    data: {
                        token:token,
                        _token: '{{ csrf_token() }}',
                        ischeck: 1
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        } 
                        else{
                            $("#savebtnsch").prop("disabled", true);
                        }
        
                    }
                });
            

            } else {
                $("#addnewschbtn").prop("disabled", false);
                $("#is_no_payment").show();
                $("#paymentScheduleBtn").show();

                  $.ajax({
                    url: "{{ route('booking.trip.ischeckedStore') }}",
                    type: "POST",
                    data: {
                        token:token,
                        _token: '{{ csrf_token() }}',
                        ischeck: 0
                    },
                    success: function(res) {
                        if (!res) {
                                alert("Somthing Went Wrong, try again.")
                        }
                        else{
                         $("#savebtnsch").prop("disabled", false);

                        }

                    }
                    });
            
              
                
            }
        });
          
            function addNewMember() {
                var bookingFor = $("#booking_for").val();

                if (bookingFor == 'Friends' || bookingFor == 'Solo') {
                    $("#relation_lable").html(`Choose Friend<span class="text-danger">*</span>`);
                    $("#relation_mem").html(`<option value="Friend">Friend</option>`);
                } else if (bookingFor == 'Family') {
                    $("#relation_lable").html(`Choose Family Member<span class="text-danger">*</span>`);
                    $("#relation_mem").html(`<option value="">Select Relationship</option>
                                        <option value="Son">Son</option>
                                        <option value="Daughter">Daughter</option>
                                        <option value="Guardian">Guardian</option>
                                        <option value="Wife">Wife</option>
                                        <option value="Husband">Husband</option>`);
                }
                $("#minorAdd").modal('show');
            }
        </script>

        <script>
            $(document).ready(function() {
                getSelectedCustomers();
                var customer_name='';
            });

            var exp = $("#expedition").val();
            if (exp == "Tailor Made") {
                $(".tailor-made").hide();
            } else {
                $(".fixed").show();
            }
        </script>

        <script>

            function bookingFor(val) {
                $.ajax({
                    url: "{{ route('booking.trip.booking-for') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        booking_for: val
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        }
                    }

                });
            }

            function leadSource(val) {
                $.ajax({
                    url: "{{ route('booking.trip.lead') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        lead_source: val
                    },
                    success: function(res) {
                        if (res != 0) {
                            if (!res) {
                                alert("Somthing Went Wrong, try again.")
                            } else {
                                if (res == 1) {
                                    var text =
                                        `<input onblur="subLeadSource(this.value)" type="text" name="sub_lead_source" class="form-control" id="sub_lead_source"
                                        placeholder="Enter Referrals Email">
                                    <label for="sub_lead_source">Enter Referrals Email <span class="text-danger fixed">*</span></label>`;
                                    $("#referal_case").html(text);
                                } else {
                                    $(".sub_lead_source").html(res);
                                }
                                $(".sub_lead_source").show();
                            }
                        }
                    }

                });
            }

            function subLeadSource(val) {
                $.ajax({
                    url: "{{ route('booking.trip.sub-lead') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        sub_lead_source: val
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        }
                    }

                });
            }

            function expeditionData(val) {
                $.ajax({
                    url: "{{ route('booking.trip.expedition') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        expedition: val
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        } else {
                            $("#expedition_trips").html(res);
                        }
                        if (val == "Tailor Made") {
                            $(".tailor-made").hide();
                        } else {
                            $(".fixed").show();
                        }
                    }

                });
            }

            function tripData(val) {
                $.ajax({
                    url: "{{ route('booking.trip.trips') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        trip_id: val
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        } else {
                            $("#trip_amt").html(res.price);
                            if (res.region_type == "Domestic") {
                                $("#payment_from_tax").val("0")
                            } else {
                                $("#payment_from_tax").val("Auto")
                            }
                            paymentFromTax($("#payment_from_tax").val());
                        }
                    }

                });
            }

            function vehicleType(val) {
                var expedition = $("#expedition").val();
                $.ajax({
                    url: "{{ route('booking.trip.vehicles') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        vehical_type: val
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        }
                        if (val == 'Other' && expedition == "Tailor Made") {
                            $("#vehicle_type_other_cmt_box").show();
                        }
                    }

                });
            }

            function vehicleTypeCmt(val) {
                $.ajax({
                    url: "{{ route('booking.trip.vehicleCmt') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        vehicle_type_other_cmt: val
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        }
                    }

                });
            }

            function vehicleSeat(val) {
                $.ajax({
                    url: "{{ route('booking.trip.seats') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        vehical_seat: val
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        }
                        // if (val != "0.25") {
                        //     $("#vehical_seat_amt").show();
                        // } else {
                        //     $("#vehical_seat_amt").hide();
                        // }
                    }

                });
            }

            function vehicleSeatPrice(val) {
                $.ajax({
                    url: "{{ route('booking.trip.seatsAmt') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        vehical_seat_amt: val
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        }
                    }

                });
            }

            function vehicleSecAmt(val) {
                $.ajax({
                    url: "{{ route('booking.trip.vehicleSecAmt') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        vehical_security_amt: val
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        }
                    }
                });
            }

            function vehicleSecAmtCmt(val) {
                $.ajax({
                    url: "{{ route('booking.trip.vehicleSecAmtCmt') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        vehical_security_amt_cmt: val
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        }
                    }
                });
            }

            function roomNumber(val) {
                if (val == 0) {
                    $("#data_by_room").hide();
                    $("#data_by_room_btn").hide();
                }
                $.ajax({
                    url: "{{ route('booking.trip.roomNumber') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        room_number: val
                    },
                    success: function(res) {
                        var text = '';
                        var i;
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        }

                        // imp
                        var values = 0;
                        if (res.room_info) {
                            values = JSON.parse(res.room_info);
                        }
                        for (i = 0; i < val; i++) {
                            if (i + 1 <= values.length) {
                                if (values[i]['room_type'] == "0.5") {
                                    var selected1 = "selected";
                                }
                                if (values[i]['room_type'] == "1") {
                                    var selected2 = "selected";
                                }
                                if (values[i]['room_type'] == "1.5") {
                                    var selected3 = "selected";
                                }

                                if (values[i]['room_cat'] == "Twin bed") {
                                    var selectedTwin = "selected";
                                }
                                if (values[i]['room_cat'] == "Solo Occupancy") {
                                    var selectedSingle = "selected";
                                }
                                if (values[i]['room_cat'] == "Double bed") {
                                    var selectedDouble = "selected";
                                }
                                if (values[i]['room_cat'] == "Twin + Extra bed") {
                                    var selectedTExtra = "selected";
                                }
                                if (values[i]['room_cat'] == "Double + Extra bed") {
                                    var selectedDExtra = "selected";
                                }
                                text += `
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="room_type[]" id="room_type"
                                            class="form-control">
                                            <option value="">Select Room Type</option>
                                            <option ${selected1} value="0.5">0.5
                                            </option>
                                            <option ${selected2} value="1">1
                                            </option>
                                            <option ${selected3} value="1.5">1.5
                                            </option>
                                    </select>
                                        <label for="room_type">Room Type<span
                                                class="text-danger fixed tailor-made">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="room_cat[]" class="form-control">
                                            <option value="">Select Room Category</option>
                                            <option ${selectedSingle} value="Solo Occupancy">
                                                Solo Occupancy
                                            </option>
                                            <option ${selectedTwin}  value="Twin bed">
                                                Twin bed</option>
                                            <option  ${selectedDouble} value="Double bed">
                                                Double bed</option>
                                            <option ${selectedTExtra}
                                                value="Twin + Extra bed">Twin + Extra bed</option>
                                            <option ${selectedDExtra}
                                                value="Double + Extra bed">Double + Extra bed</option>

                                        </select>
                                        <label for="">Room Category<span
                                                class="text-danger fixed tailor-made">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline mb-4" id="room_type_amt">
                                        <input type="number" name="room_type_amt[]"
                                            value="${values[i]['room_type_amt']}" class="form-control" placeholder="Enter Amount">
                                        <label for="room_type_amt">Amount</label>
                                    </div>
                                </div>`;
                            } else {
                                text += `
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="room_type[]" id="room_type"
                                            class="form-control">
                                            <option value="">Select Room Type</option>
                                            <option value="0.5">0.5
                                            </option>
                                            <option value="1">1
                                            </option>
                                            <option value="1.5">1.5
                                            </option>
                                    </select>
                                        <label for="room_type">Room Type<span
                                                class="text-danger fixed tailor-made">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="room_cat[]" class="form-control">
                                            <option value="">Select Room Category</option>
                                            <option value="Solo Occupancy">
                                                Solo Occupancy
                                            </option>
                                            <option value="Twin bed">
                                                Twin bed</option>
                                            <option value="Double bed">
                                                Double bed</option>
                                            <option
                                                value="Twin + Extra bed">Twin + Extra bed</option>
                                            <option
                                                value="Double + Extra bed">Double + Extra bed</option>
                                        </select>
                                        <label for="">Room Category<span
                                                class="text-danger fixed tailor-made">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline mb-4" id="room_type_amt">
                                        <input type="number" name="room_type_amt[]"
                                            value="" class="form-control" placeholder="Enter Amount">
                                        <label for="room_type_amt">Amount</label>
                                    </div>
                                </div>`;
                            }
                        }

                        if (val == 0 || val == "") {
                            $("#data_by_room").hide();
                            $("#data_by_room_btn").hide();
                        } else {
                            $("#data_by_room").show();
                            $("#data_by_room_btn").show();
                        }
                        $("#data_by_room").html(text);
                    }

                });
            }

            function roomInfo() {
                var formIsValid = true;
                $("#room_type_amt_cat").find('select').each(function() {
                    if ($(this).val() === '') {
                        formIsValid = false;
                        return false;
                    }
                });
                if (formIsValid) {
                    $.ajax({
                        url: "{{ route('booking.trip.saveRoomInfo') }}",
                        type: "POST",
                        data: $("#room_type_amt_cat").serialize(),
                        success: function(res) {
                            if (!res) {
                                alert("Somthing Went Wrong, try again.")
                            } else {
                                Toast.fire({
                                    icon: "success",
                                    title: "Room Info Saved Successfully!"
                                });
                            }
                            getSummary();
                        }

                    });
                } else {
                    alert("All Fields are Required");
                }
            }

            function paymentFrom(val) {
                $.ajax({
                    url: "{{ route('booking.trip.paymentFrom') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        payment_from: val
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        }
                        if (val == "Individual") {
                            $("#multiple_payment_gst").show();
                            $("#payment_from_tds").show();
                             $("#billing_customer").show();
                            $("#payment_from_company, #is_tds_parent").hide();
                            paymentFromTax($("#payment_from_tax").val());
                        } else if (val == "Company") {
                            $("#multiple_payment_gst").hide();
                            $("#payment_from_tds").hide();
                               $("#billing_customer").hide();
                            $("#payment_from_company, #is_tds_parent").show();
                        } else {
                            $("#multiple_payment_gst").hide();
                            $("#payment_from_tds").hide();
                               $("#billing_customer").hide();
                            $("#payment_from_company, #is_tds_parent").hide();

                        }
                        getSummary()

                    }

                });
            }
            
            let selectedBillingOrder = [];

            const editBillingValues = $('#billing_customer_ids').val();
            if (editBillingValues) {
                selectedBillingOrder = [...editBillingValues];
            }
            function getbillingCustomers(select = "") {
                var pageToken = "{{ request()->token }}";
                let selectedBillingOrder = $('#billing_customer_ids').val()
                selectedBillingOrder = selectedBillingOrder.filter(item => item !== id);
                var selectedCustomerIds = selectedBillingOrder;
                console.log(selectedBillingOrder)

                $.ajax({
                    url: "{{ route('booking.billing-customer') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: pageToken,
                        billing_customer_id: selectedCustomerIds
                    },
                   
                });
            }

            function paymentFromCmpny(val) {
                $.ajax({
                    url: "{{ route('booking.trip.paymentFromCmpny') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        payment_from_cmpny: val
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        }
                    }

                });
            }
            let selectedCustomers = [];

            function paymentFromTax(val) {
                const editButton = document.getElementById("editManualTaxButton");

                if (val === "Manual") {

                    editButton.style.display = "block";

                    const selectedCustomerIds = $('#customer_ids').val();

                    if (!selectedCustomerIds || selectedCustomerIds.length === 0) {
                        alert("Please select customers before choosing Manual Tax.");
                        return;
                    }

                    $.ajax({
                        url: "{{ route('booking.trip.paymentFromTax') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            token: "{{ request()->token }}",
                            payment_from_tax: val
                        },
                        success: function(res) {
                            if (!res) {
                                alert("Somthing Went Wrong, try again.")
                            }
                            else{
                                getSummary();

                                $.ajax({
                                    url: "{{ route('getCustomerDetailsById') }}",
                                    type: "POST",
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        ids: selectedCustomerIds
                                    },
                                    success: function(res) {
                                        if (res) {
                                            const customers = JSON.parse(res).customers;
                                          
                                            renderManualTaxPopup(customers);
                                        } else {
                                            alert("Something went wrong while fetching customer details. Try again.");
                                        }
                                    },
                                    error: function(err) {
                                        alert("Error fetching customer details.");
                                    }
                                });
                            }

                        }

                    });

                } else {
                    editButton.style.display = "none";

                    $.ajax({
                        url: "{{ route('booking.trip.paymentFromTax') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            token: "{{ request()->token }}",
                            payment_from_tax: val
                        },
                        success: function(res) {
                            if (!res) {
                                alert("Somthing Went Wrong, try again.")
                            }
                            else{
                                if (val === "Manual") {
                                    $("#manualTaxPopup").modal("show");
                                }
                            }
                            getSummary();
                            getSelectedCustomers();
                        }

                    });
                }
            }

            function editManualTax() {
                const selectedCustomerIds = $('#customer_ids').val();


                if (!selectedCustomerIds || selectedCustomerIds.length === 0) {
                    alert("Please select customers before choosing Manual Tax.");
                    return;
                }

                $.ajax({
                    url: "{{ route('booking.getCustomerManualTax') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        customer_ids: selectedCustomerIds
                    },
                    success: function(res) {
                        if (res && res.customers) {
                            const customers = res.customers;
                            if (customers && customers.length > 0) {
                                renderManualTaxPopup(customers);
                            }
                            else{
                                Toast.fire({
                                    icon: "error",
                                    title: "No taxes are available"
                                });
                            }

                        } else {
                            alert("No data found for the selected customers.");
                        }
                    },
                    error: function(err) {
                        alert("Error fetching customer details.");
                        console.error(err);
                    }
                });
            }

            function paymentDoneBy(val) {
                var customer_name='';
                var check = 0;
                if ($('#payment_all_done_by_this').is(':checked')) {
                    var ids = $("#customer_ids").val();
                    if (ids != '') {
                        $.ajax({
                            url: "{{ route('getCustomerDetailsById') }}",
                            type: "POST",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                token: "{{ request()->token }}",
                                ids: ids
                            },
                            success: function(res) {
                                var data = JSON.parse(res);
                                data = data.customers;
                                var text = "<option value=''>Select Customer</option>";
                                $.each(data, function(key, value) {
                                    if (value.parent == 0) {
                                        text +=
                                            `<option value="${value.id}">${value.first_name} ${value.last_name}</option>`;
                                    }
                                });
                                $("#selected-customers-for-payment").html(text);
                                $("#paymentByModal").modal("show");
                            }
                        });
                    }
                } else {
                    $("#paymentByModal").modal("hide");
                    var customer_name='';
                    check = 0;
                    $.ajax({
                        url: "{{ route('booking.trip.paymentAllDoneCheck') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            token: "{{ request()->token }}",
                            payment_all_done_by_this: check,
                            payment_by_customer_id: null
                        },
                        success: function(res) {
                            var customer_name='';
                            $('.payAllCustomer').html(customer_name);
                            if (!res) {
                                alert("Somthing Went Wrong, try again.")
                            }
                        }
                    });
                }


            }

            function paymentByCustomer() {
                var customer_name='';
                var c_id = $("#selected-customers-for-payment").val();
                if (c_id != '') {
                    $.ajax({
                        url: "{{ route('booking.trip.paymentByCustomer') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            token: "{{ request()->token }}",
                            payment_by_customer_id: c_id
                        },
                        success: function(res) {
                            if (!res) {
                                alert("Somthing Went Wrong, try again.")
                            } else {
                                $("#paymentByModal").modal("hide");
                                var customer_name=res;
                                $('.payAllCustomer').html(customer_name);
                                Toast.fire({
                                    icon: "success",
                                    title: "Customer saved Successfully."
                                });
                            }
                            saveCreditNoteAmt();
                        }
                    });
                } else {
                    Toast.fire({
                        icon: "error",
                        title: "Select Customer First."
                    });
                }
            }

            function paymentType(val) {
                $.ajax({
                    url: "{{ route('booking.trip.paymentType') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        payment_type: val
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        }
                        saveCreditNoteAmt();
                    }
                });

            }

            function paymentAmt(val) {
                var payableAmt = $("#total_payable_amt").text();
                var numericPart = payableAmt.replace(/[^\d.]/g, '');
                var numericPayableValue = parseFloat(numericPart);

                if ($("#payment_type").val() == "Full Payment") {
                    if (numericPayableValue != val) {
                        $("#last_pay_amt").val("");
                        Toast.fire({
                            icon: "error",
                            title: "In Case of Full Payment You have to enter whole payable amount!"
                        });
                        return 0;
                    }
                } else if ($("#payment_type").val() == "Part Payment") {
                    if (numericPayableValue <= val) {
                        $("#last_pay_amt").val("");
                        Toast.fire({
                            icon: "error",
                            title: "In Case of Part Payment You have to enter less than payable amount!"
                        });
                        return 0;
                    }
                }
                $.ajax({
                    url: "{{ route('booking.trip.paymentAmt') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        payment_amt: val
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        }
                        getSummary();
                        saveCreditNoteAmt();
                    }
                });

            }

            function paymentDate(val) {
                $.ajax({
                    url: "{{ route('booking.trip.paymentDate') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        payment_date: val
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        }

                        getSummary();
                        saveCreditNoteAmt();
                    }
                });

            }

            function saveCosts(fromManual = false) {

                var formIsValid = true;

                if(!fromManual){
                    $("#trip_amt_details_form").find('.trip_cost_amt').each(function() {
                    
                        if ($(this).val() === '') {
                            formIsValid = false;
                            return false;
                        }

                    });
                }


                if (formIsValid) {
                    let formData = $("#trip_amt_details_form").serialize();

                    formData += '&from_manual='+ fromManual;
                    $.ajax({
                        url: "{{ route('booking.trip.costs') }}",
                        type: "POST",
                        data: formData,
                        success: function(res) {
                            if (!res) {
                                alert("Somthing Went Wrong, try again.")
                            } else {
                                Toast.fire({
                                    icon: "success",
                                    title: "Trip Cost Saved Successfully!"
                                });
                            }
                            getSummary();
                            saveCreditNoteAmt();
                        }
                    });
                } else {
                    alert("All Fields are Required");
                }
            }

            function saveCreditNoteAmt() {
                var payable_amt = $("#payable_amt_to_saved").val();
                if (payable_amt) {
                    $.ajax({
                        url: "{{ route('booking.trip.credit-note-amt-add') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            token: "{{ request()->token }}",
                            total_amount: payable_amt,
                        },
                        success: function(res) {
                            getSummary();
                        }
                    });
                }
            }

            function saveExtraServices() {

                var formIsValid = true;
                $("#extraServicesForm").find('input[type="number"], select').each(function() {
                    if ($(this).val() === '') {
                        formIsValid = false;
                        return false;
                    }
                });

                if (formIsValid) {
                    $.ajax({
                        url: "{{ route('booking.trip.extraServices') }}",
                        type: "POST",
                        data: $("#extraServicesForm").serialize(),
                        success: function(res) {
                            if (!res) {
                                alert("Somthing Went Wrong, try again.")
                            } else {
                                Toast.fire({
                                    icon: "success",
                                    title: "Extra Services Saved Successfully!"
                                });
                            }
                            getSummary();
                        }
                    });
                } else {
                    alert("All Fields are Required");
                }
            }

           function getAllScheduleValues() {
                let schedules = [];
              
                let formIsValidforSch = true;
                // Collect values from #is_no_payment
                $("#is_no_payment").find('input[name="sch_date[]"], input[name="sch_amount[]"], input[name="sch_comment[]"]').each(function (index, el) {
                    // group inputs by index (0=date,1=amount,2=comment)
                    if (index % 3 === 0) schedules.push({});
                    let fieldIndex = index % 3;

                    if (fieldIndex === 0) schedules[schedules.length - 1].date = $(el).val();
                    if (fieldIndex === 1) schedules[schedules.length - 1].amount = $(el).val();
                    if (fieldIndex === 2) schedules[schedules.length - 1].comment = $(el).val();

                });

                // Collect values from #cloneSchContainer
                $("#cloneSchContainer .row").each(function () {
                    let schDate = $(this).find("input[name='sch_date[]']").val();
                    let schAmount = $(this).find("input[name='sch_amount[]']").val();
                    let schComment = $(this).find("input[name='sch_comment[]']").val();
                   


                    if (schDate || schAmount) {
                        schedules.push({
                            date: schDate,
                            amount: schAmount,
                            comment: schComment
                        });
                        if (!schDate || !schAmount) formIsValidforSch = false;
                    }
                });

                return { schedules, formIsValidforSch };
            }

            function savePaymentSchedule() {
                let token= $('#schtoken').val();
                let { schedules, formIsValidforSch } = getAllScheduleValues();    
                var pendingAmtTotal = $("#total_pending_amt_inp").val();

                var totalAmount = 0;
                $(".sch_payment_amt").each(function() {
                    var value = parseFloat($(this).val()) || 0;
                    totalAmount += value;
                });

                if (totalAmount != pendingAmtTotal) {
                    alert("Total payment schedule amount should be equal to pending amount");
                    return false;
                }

                if (formIsValidforSch) {
                    $.ajax({
                        url: "{{ route('booking.trip.schPayment') }}",

                        type: "POST",
                        data: {
                            token:token,
                             _token: '{{ csrf_token() }}',
                            schedules: schedules
                        },
                        success: function(res) {
                            if (!res) {
                                alert("Somthing Went Wrong, try again.")
                            } else {
                                Toast.fire({
                                    icon: "success",
                                    title: "Payment Schedule Saved Successfully!"
                                });
                            }
                            getSummary();
                            saveCreditNoteAmt();
                        }
                    });
                } else {
                    alert("All Fields are Required");
                }
            }
            

            function taxInfo(tax_type) {

                if (tax_type == "all") {
                    var field = $('#tax_required').is(':checked');
                    var check = 0;
                    if (field) {
                        check = 1;
                    } else {
                        check = 0;
                    }
                } else if (tax_type == "tds") {
                    var check = 1;
                    var field = $('#is_tds').is(':checked');
                    if (field) {
                        check = 0;
                    } else {
                        check = 1;
                    }
                }

                $.ajax({
                    url: "{{ route('booking.trip.taxRequired') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        tax_type: tax_type,
                        tax_required: check
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        }
                        getSummary();
                    }

                });
            }

            function submitBookingForm() {

                let paymentFrom = $("#payment_from").val();
                let billingCustomerIds = $("#billing_customer_ids").val();
                    if (paymentFrom === "Individual") {
                        if (!billingCustomerIds || billingCustomerIds.length === 0) {
                            alert("Please select at least one billing customer.");
                            return false;
                        }
                    }

                if ($("#customer_ids").val() != "" && $("#payment_from").val() != "" && $("#last_pay_amt").val() != "" && ($(
                        "#payment_from_tax").val() != "" || $("#payment_from_cmpny_name").val() != "")) {
                    var check = 1;
                    var payable_amount = $("#payable_amt_to_saved").val();

                    roomInfo();
                    $.ajax({
                        url: "{{ route('booking.trip.formSubmited') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            token: "{{ request()->token }}",
                            form_submited: check,
                            payable_amount: payable_amount
                        },
                        success: function(res) {
                            if (!res) {
                                alert("Somthing Went Wrong, try again.")
                            } else {
                                window.location.href = "{{ route('booking.index') }}";
                            }
                        }
                    });
                    window.location.href = "{{ route('booking.index') }}";
                } else {
                    alert("required fields are empty");
                }
            }
        </script>

        <script>
            function addNewField() {
                var existingField = document.querySelector('.field');
                var newField = existingField.cloneNode(true);

                document.getElementById("cloneContainer").appendChild(newField);
                $("#extraServces").show();
            }

            function removeSingleField(element) {
                var row = element.closest('.row');
                row.parentNode.removeChild(row);
            }
        </script>

        <script>
            function addNewSchField() {
                var existingField = document.querySelector('.schField');
                var newField = existingField.cloneNode(true);

                document.getElementById("cloneSchContainer").appendChild(newField);
                $("#paymentScheduleBtn").show();
            }

            function removeSingleSchField(element) {
                var row = element.closest('.row');
                row.parentNode.removeChild(row);
            }
        </script>

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

             let selectedCustomerOrder = [];

             const editValues = $('#customer_ids').val(); 
         
                selectedCustomerOrder = [...editValues]; 
                $('#customer_ids').on('select2:select', function (e) {
                    const id = e.params.data.id;
                    if (!selectedCustomerOrder.includes(id)) {
                        selectedCustomerOrder.push(id);
                    }
                    $('#customer_ids').val(selectedCustomerOrder).trigger('change.select2');
                });

                $('#customer_ids').on('select2:unselect', function (e) {
                    const id = e.params.data.id;
                    selectedCustomerOrder = selectedCustomerOrder.filter(item => item !== id);
                    $('#customer_ids').val(selectedCustomerOrder).trigger('change.select2');
                });

            function getSelectedCustomers(select = "") {
               
                var ids = selectedCustomerOrder;
           
               
                var bookingFor = $("#booking_for").val();
                var expedition = $("#expedition").val();

                if (bookingFor) {
                    var count = 0;
                    if (bookingFor == "Solo") {
                        count = 1;
                    } else if ((bookingFor == "Friends" || bookingFor == "Family") && expedition == "Fixed Departure") {
                        count = 4;
                    } else {
                        count = 10000;
                    }
                   
                    if (ids.length <= count) {
                        $.ajax({
                            url: "{{ route('getCustomerDetailsById') }}",
                            type: "POST",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                token: "{{ request()->token }}",
                                ids: ids
                            },
                            success: function(res) {
                           
                                if (res == 0) {
                                    Toast.fire({
                                        icon: "error",
                                        title: "There are No Parent Exist for this child!"
                                    });
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000);
                                }
                                var data = JSON.parse(res);
                           
                                var customers = data.customers;
                                var billingData = data.bookingData.billing_to ? JSON.parse(data.bookingData.billing_to) : [];
                                var customersCount = data.customers.length;
                                var trip_price = data.trip_price;

                                var tripCosts = data.tripCosts;
                                var tripDeviation = data.tripDeviation;
                                var rooms = data.rooms;
                                var is_multiple_payment = data.is_multiple_payment;
                                var paymentFromTax = data.payment_from_tax;
                                var text = "";
                                var tripUi = "";
                                var selected = "<option value=''>Select</option>";
                                var selectedForBilling = "<option value=''>Select</option>";
                                let tripCost, cost, cmt, tcs_tax;
                                let tripDev, dev_type, dev_amt, dev_cmt, select1, select2, devCheck;
                                $("#trip_costing").show();
                                if (customersCount) {

                                    var i;
                                    var numText = '<option value="">Select Number Of Rooms</option>';
                                    for (i = 1; i <= customersCount; i++) {
                                        if (i == rooms) {
                                            var select = 'selected';
                                        } else {
                                            var select = '';
                                        }
                                        numText += `<option ${select} value="${i}">${i}</option>`;
                                    }

                                    $("#no_of_rooms").html(numText);
                                }

                                var rn = 0;
                                if ($("#no_of_rooms").val() > 0) {
                                    rn = $("#no_of_rooms").val();
                                }
                                if (rn) {
                                    roomNumber(rn);
                                }


                                $.each(customers, function(key, value) {
                                    if (tripCosts) {
                                        tripCost = tripCosts.filter(val => val.c_id == value.id);
                                    } else {
                                        tripCost = null;
                                    }

                                    if (tripDeviation) {
                                        tripDev = tripDeviation.find(val => val.c_id == value.id);
                                    } else {
                                        tripDev = null;
                                    }

                                    if (is_multiple_payment == 1) {
                                        var multiple_payment_status = "";
                                    } else {
                                        var multiple_payment_status =
                                            "style='display:none;'";
                                    }

                                    var select0 = "";
                                    var select5 = '';
                                    var select20 = '';
                                    var selectAuto = '';
                                    

                                    if (tripCost && tripCost.length > 0) {
                                        var checkUser = [];
                                        $.each(tripCost, function(key, tripCost) {

                                           
                                            if (tripCost) {
                                                cost = tripCost.cost;
                                                cmt = tripCost.comment;
                                                tcs_tax = tripCost.multiple_payment_tax;
                                            } else {
                                                cost = '';
                                                cmt = '';
                                                tcs_tax = '';
                                            }
                                            if (tcs_tax == '0') {
                                                var select0 = 'selected';
                                            }
                                            if (tcs_tax == '5') {
                                                var select5 = 'selected';
                                            }
                                            if (tcs_tax == '20') {
                                                var select20 = 'selected';
                                            }
                                            if (tcs_tax == 'Auto') {
                                                var selectAuto = 'selected';
                                            }


                                            if (!checkUser.includes(tripCost.c_id)) {
                                                tripUi += `
                                                    <div class="form-group mb-2">
                                                        <input type="checkbox" id="deviationCheck_${value.id}" ${devCheck}>
                                                        <label data-bs-toggle="modal" data-bs-target="#deviation_${value.id}" for="deviationCheck_${value.id}">Deviation For ${value.first_name} ${value.last_name}</label>
                                                    </div>`;
                                            }

                                            tripUi += `
                                                    <div class="tripCostRawParent">
                                                        <div class="row tripCostRaw">
                                                            @csrf
                                                            <input type="hidden" value="${value.id}" name="c_id[]" required>
                                                            <input type="hidden" value="{{ request()->token }}" name="token" required>

                                                            <div class="col-4">
                                                                <div class="form-floating form-floating-outline mb-4">
                                                                    <input type="number" value="${cost}" name="trip_cost[]" class="form-control trip_cost_amt"
                                                                        placeholder="Trip Cost" required
                                                                        ${paymentFromTax === 'Manual' ? 'disabled' : ''}>
                                                                    <label for="basic-default-fullname">Cost For ${value.first_name} ${value.last_name}<span
                                                                            class="text-danger fixed">*</span></label>
                                                                    <small>Trip Cost: ₹<span class="price-amt"></span></small>
                                                                </div>
                                                              
                                                            </div>

                                                            <div class="col-4">
                                                                <div class="form-floating form-floating-outline mb-4">
                                                                    <input type="text" value="${cmt}" name="trip_cost_cmt[]" class="form-control"
                                                                        placeholder="Comment" required>
                                                                    <label for="basic-default-fullname">Comment</label>
                                                                </div>
                                                            </div>

                                                            <div class="col-2 multiple-accept-payment" id="" ${multiple_payment_status}>
                                                                <div class="form-floating form-floating-outline">
                                                                    <select name="multiple_payment_tax[]" class="form-control">
                                                                        <option value="">TCS</option>
                                                                        <option ${select0} value="0">0%</option>
                                                                        <option ${select5} value="5">5%</option>
                                                                        <option ${select20} value="20">20%</option>
                                                                        <option ${selectAuto} value="Auto">Auto</option>
                                                                    </select>
                                                                    <label for="">TCS<span class="text-danger fixed tailor-made">*</span></label>
                                                                </div>
                                                            </div>`;


                                            if (checkUser.includes(tripCost.c_id)) {
                                                tripUi += `<div class="form-group col-1 multiple-accept-payment" ${multiple_payment_status}>
                                                                    <a href="javaScript:void(0)" onclick="removeSingleMultipleAmount(this)" class="badge bg-danger mt-3"><b>X</b></a>
                                                                </div>`;
                                            }

                                            if (!checkUser.includes(tripCost.c_id)) {
                                                checkUser.push(tripCost.c_id);
                                                tripUi += `<div class="form-group col-1 multiple-accept-payment" ${multiple_payment_status}>
                                                                    <a href="javaScript:void(0)" onclick="addSingleMultipleAmount(this)" class="badge bg-success mt-3"><b>+</b></a>
                                                                </div>`;
                                            }

                                            tripUi += `</div>
                                                    </div>`;
                                        });
                                    } else {

                                        tripUi += `
                                                <div class="form-group mb-2">
                                                    <input type="checkbox" id="deviationCheck_${value.id}" ${devCheck}>
                                                    <label data-bs-toggle="modal" data-bs-target="#deviation_${value.id}" for="deviationCheck_${value.id}">Deviation For ${value.first_name} ${value.last_name}</label>
                                                </div>
                                                <div class="tripCostRawParent">
                                                    <div class="row tripCostRaw" >
                                                        @csrf
                                                        <input type="hidden" value="${value.id}" name="c_id[]" required>
                                                        <input type="hidden" value="{{ request()->token }}" name="token" required>
                                                          
                                                        <div class="col-4">
                                                            <div class="form-floating form-floating-outline mb-4">
                                                                <input type="number" value="" name="trip_cost[]" class="form-control trip_cost_amt"
                                                                    placeholder="Trip Cost" required>
                                                                <label for="basic-default-fullname">Cost For ${value.first_name} ${value.last_name}<span
                                                                    class="text-danger fixed">*</span>
                                                                </label>
                                                                <small>Trip Cost: ₹<span class="price-amt"></span></small>
                                                            </div>
                                                        
                                                        </div>

                                                        <div class="col-4">
                                                            <div class="form-floating form-floating-outline mb-4">
                                                                <input type="text" value="" name="trip_cost_cmt[]" class="form-control"
                                                                    placeholder="Comment" required>
                                                                <label for="basic-default-fullname">Comment</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-2 multiple-accept-payment" id="" ${multiple_payment_status}>
                                                            <div class="form-floating form-floating-outline">
                                                                <select name="multiple_payment_tax[]" class="form-control">
                                                                    <option value="">TCS</option>
                                                                    <option ${select0} value="0">0%</option>
                                                                    <option ${select5} value="5">5%</option>
                                                                    <option ${select20} value="20">20%</option>
                                                                    <option ${selectAuto} value="Auto">Auto</option>
                                                                </select>
                                                                <label for="">TCS<span class="text-danger fixed tailor-made">*</span></label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-1 multiple-accept-payment d-none" ${multiple_payment_status}>
                                                            <a href="javaScript:void(0)" onclick="removeSingleMultipleAmount(this)" class="badge bg-danger mt-3"><b>X</b></a>
                                                        </div>

                                                        <div class="form-group col-1 multiple-accept-payment" ${multiple_payment_status}>
                                                            <a href="javaScript:void(0)" onclick="addSingleMultipleAmount(this)" class="badge bg-success mt-3"><b>+</b></a>
                                                        </div>

                                                    </div>
                                                </div>`;
                                    }

                                    tripUi += `<div class="modal fade" id="deviation_${value.id}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="card">
                                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                                    <h5 class="mb-0">Add Deviation</h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-12 mb-4">
                                                                            <div class="form-floating form-floating-outline">
                                                                                <select name="deviation_type_${value.id}" class="form-control">
                                                                                    <option value="">Select Deviation</option>
                                                                                    <option ${select1} value="Add">Add</option>
                                                                                    <option ${select2} value="Subtract">Subtract</option>
                                                                                </select>
                                                                                <label for="basic-default-fullname">Deviation <span class="text-danger">*</span></label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-12 mb-4">
                                                                            <div class="form-floating form-floating-outline">
                                                                                <input type="number" value="${dev_amt}" name="deviation_amt_${value.id}" class="form-control" id="basic-default-fullname" placeholder="Deviation Amount" />
                                                                                <label for="basic-default-fullname">Deviation Amount<span
                                                                                        class="text-danger">*</span></label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-12 mb-4">
                                                                            <div class="form-floating form-floating-outline">
                                                                                <input type="text" value="${dev_cmt}"  name="deviation_comment_${value.id}" class="form-control" id="basic-default-fullname" placeholder="Comment" />
                                                                                <label for="basic-default-fullname">Comment<span
                                                                                        class="text-danger"></span></label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="text-center">
                                                                        <a href="javaScript:void(0)" data-bs-dismiss="modal" class="btn btn-primary text-white">Save</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>`;


                                    if (tripDev) {
                                        dev_type = tripDev.deviation_type
                                        dev_amt = tripDev.deviation_amt;
                                        dev_cmt = tripDev.deviation_comment;
                                        if (dev_type == "Subtract") {
                                            select2 = "selected";
                                        } else {
                                            select2 = "";
                                        }
                                        if (dev_type == "Add") {
                                            select1 = "selected";
                                        } else {
                                            select1 = "";
                                        }

                                        devCheck = "checked";
                                    } else {
                                        dev_type = '';
                                        dev_cmt = '';
                                        dev_amt = '';
                                        select1 = '';
                                        select2 = '';
                                        devCheck = '';
                                    }



                                    if (value.parent == 0) {
                                        text +=
                                            `<div style="font-size: 0.8em;" class="col-md-4 mt-2">
                                            <div style="border: 1px dashed black" class="p-3">
                                                <b>
                                                    <p>Customer Information <a href="javaScript:void(0)" onclick="editUserDetails(${value.id}, '${value.first_name}', '${value.last_name}', '${value.telephone_code}', '${value.phone}', '${value.email}', '${value.gender}', '${value.dob}', '${value.parent}')"><i
                                                                class="mdi mdi-pencil-outline me-1"></i></a></p>
                                                    <p>Name: ${value.first_name} ${value.last_name} </p>
                                                    <p>Email: ${value.email} </p>
                                                    <p>Contact: ${value.phone} </p>
                                                    <p>Available Points: ${value.points} </p>`;
                                        if (value.points > 0) {
                                            text += `<div class="text-center">
                                                            <a onclick="redeemPoints(${value.id}, ${value.points}, '${value.email}')" href="javaScript:void(0)">Redeem Points</a>
                                                        </div>`;
                                        }
                                        text += `</b>
                                            </div>
                                        </div>`;
                                    } else {
                                        text += `<div style="font-size: 0.8em;" class="col-md-4 mt-2">
                                            <div style="border: 1px dashed black" class="p-3">
                                                <b>
                                                    <p>Member Information <a href="javaScript:void(0)" onclick="editUserDetails(${value.id}, '${value.first_name}', '${value.last_name}','${value.telephone_code}', '${value.phone}', '${value.email}', '${value.gender}', '${value.dob}', '${value.parent}')"><i
                                                                class="mdi mdi-pencil-outline me-1"></i></a></p>
                                                    <p>Name: ${value.first_name} ${value.last_name} </p>
                                                    <p>Gender: ${value.gender} </p>
                                                    <p>DOB: ${value.dob} </p>
                                                </b>
                                            </div>
                                        </div>`;
                                    }

                                    selected +=
                                        `<option value="${value.id}">${value.first_name} ${value.last_name}</option>`;

                                    selectedForBilling +=
                                        `<option ${billingData.includes(String(value.id)) ? "selected": "unselected"} value="${value.id}">${value.first_name} ${value.last_name}</option>`;
                       
                                    });
                              
                                $("#selected-customer-list").html(text);
                                $("#trip_amt_details").html(tripUi);
                                $("#selected-travelers").html(selected);
                                $("#billing_customer_ids").html(selectedForBilling);

                                getSummary();
                            }
                        });
                    } else {
                        // var allowedIds = ids.slice(0, count);
                        // $("#customer_ids").val(allowedIds).trigger("change");
                        Toast.fire({
                            icon: "warning",
                            title: "Maximum " + count + " Coustomers are Allowed!"
                        });
                    }
                } else {
                    if (ids.length > 0) {
                        Toast.fire({
                            icon: "warning",
                            title: "Select Booking For First!"
                        });
                    }
                }
            }

            function addSingleMultipleAmount(element) {
                var existingField = element.closest('.tripCostRaw');
                var newField = existingField.cloneNode(true);

                var addButton = newField.querySelector("a[onclick*='addSingleMultipleAmount']");
                if (addButton) {
                    addButton.remove();
                }

                var removeButtonDiv = newField.querySelector('.multiple-accept-payment.d-none');
                if (removeButtonDiv) {
                    removeButtonDiv.classList.remove('d-none');
                }

                element.closest(".tripCostRawParent").appendChild(newField);
            }

            function removeSingleMultipleAmount(element) {
                var row = element.closest('.row');
                row.parentNode.removeChild(row);
            }

            function editUserDetails(id, first_name, last_name, telephone_code, phone, email, gender, dob, parent) {
                $("#customerEditid").val(id);
                $("#customerEditfirst_name").val(first_name);
                $("#customerEditlast_name").val(last_name);
                if (parent == 0) {
                    $("#customerEditphone").val(phone);
                    $("#telephone_code").val(telephone_code);
                    $("#customerEditemail").val(email);

                    $("#customerEditphone").parent().show();
                    $("#telephone_code").parent().show();
                    $("#customerEditemail").parent().show();
                } else {
                    $("#customerEditphone").val('');
                    $("#telephone_code").val('');
                    $("#customerEditemail").val('');

                    $("#customerEditphone").parent().hide();
                    $("#telephone_code").parent().hide();
                    $("#customerEditemail").parent().hide();
                }
                $("#customerEditgender").val(gender);
                $("#customerEditdob").val(dob);
                $("#customerEdit").modal('show');
            }

            function redeemPoints(id, points, email) {
                $(".user-id").val(id);
                $(".user-email-id").text(email);
                $("#remaining-points").text(points)
                $("#user_total_points").text(points);
                $("#customerRedeemPoint").modal('show');
            }

            function checkPoints(current_val) {
                var total_val = $("#user_total_points").text();
                var rp = 0;
                rp = total_val - current_val;
                if (parseInt(current_val) > parseInt(total_val)) {
                    Toast.fire({
                        icon: "error",
                        title: "You Can't Redeem More than " + total_val + " points!"
                    });
                    $("#user_redeem_points").val(total_val);
                    rp = 0;
                }

                $("#remaining-points").text(rp)
            }

            function multiplePayment() {
                if ($('#multiple_payment').is(':checked')) {
                    var val = 1;
                    $('#payment_from_tds').hide();
                    $(".multiple-accept-payment").show();
                } else {
                    $('#payment_from_tds').show();
                    $(".multiple-accept-payment").hide();
                    var val = 0;
                }
                $.ajax({
                    url: "{{ route('booking.trip.multipe-payment') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        token: "{{ request()->token }}",
                        is_multiple_payment: val,
                    },
                    success: function(res) {
                        if (!res) {
                            alert("Somthing Went Wrong, try again.")
                        } else {

                        }
                    }
                });
            }
        </script>

        <script>
            function sendOtpToCustomer() {
                var customer_id = $(".user-id").val();
                var redeemable_points = $("#user_redeem_points").val();

                if (redeemable_points > 0) {
                    Toast.fire({
                        icon: "success",
                        title: "OTP sent Successfully, check Email for OTP."
                    });

                    $.ajax({
                        url: "{{ route('sendOtpToCustomer') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            token: "{{ request()->token }}",
                            customer_id: customer_id,
                            redeemable_points: redeemable_points,
                        },
                        success: function(res) {
                            if (res == 1) {
                                $("#customerRedeemPoint").modal('hide');
                                $("#customerRedeemPointVerify").modal('show');
                            } else {
                                Toast.fire({
                                    icon: "error",
                                    title: "Somthing went Wrong!"
                                });
                            }
                        }
                    });

                } else {
                    $("#customerRedeemPoint").modal('hide');
                    Toast.fire({
                        icon: "error",
                        title: "Somthing Went Wrong"
                    });
                }
            }

            function verifyOTP() {
                var user_otp = $("#user_otp").val();
                var customer_id = $(".user-id").val();
                var redeemable_points = $("#user_redeem_points").val();
                if (user_otp != "") {
                    $.ajax({
                        url: "{{ route('verifyOTP') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            token: "{{ request()->token }}",
                            customer_id: customer_id,
                            user_otp: user_otp,
                            redeemable_points: redeemable_points
                        },
                        success: function(res) {
                            if (res == 1) {
                                $("#customerRedeemPointVerify").modal('hide');
                                Toast.fire({
                                    icon: "success",
                                    title: "Successfully Redeemed!"
                                });
                            } else {
                                Toast.fire({
                                    icon: "error",
                                    title: "You have entered Incorrect OTP!"
                                });
                            }
                        }
                    });
                } else {
                    Toast.fire({
                        icon: "error",
                        title: "OTP field is required!"
                    });
                }
            }
        </script>

        <script>
            $(document).ready(function() {
                $('#minor-age-validate').on('change', function() {
                    var dateOfBirth = new Date($(this).val());
                    var today = new Date();
                    var age = today.getFullYear() - dateOfBirth.getFullYear();
                    var monthDiff = today.getMonth() - dateOfBirth.getMonth();
                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dateOfBirth.getDate())) {
                        age--;
                    }
                    if (age > 12) {
                        alert("Age must be 12 years or younger.");
                        $(this).val(''); // Clear the input field
                    }
                });
            });
        </script>
    @endsection
