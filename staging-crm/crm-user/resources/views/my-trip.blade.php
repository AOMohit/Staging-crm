@extends('layouts.userlayout.index')
@section('container')

<div class="col-md-10 col-12">
    <div class="border-shadow">
        <div class="card">
            <div class="card-header bg-white information">
                @include('layouts.userlayout.card-header')
            </div>
            <div class="card-body">
                <h6 class="text fw-bold mb-4">Booked Trip History</h6>
                @if(count($trip)>0)
                @foreach ($trip as $trips)
                
                   
               
                <div class="col-12 mt-3">
                    <div class="card border-shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <div class="row trip-dates">
                                        <div class="col-md-4 col-12">

                                            <span>Trip Start Date:</span><span class="fw-bold trip-date"> {{date('d M, Y', strtotime($trips->trip->start_date ?? null))}}</span>
                                        </div>
                                        <div class="col-md-4 col-12 ">

                                            <span class="">Trip End Date:</span><span class="fw-bold trip-date">{{date('d M, Y', strtotime($trips->trip->end_date ?? null))}}</span>
                                        </div>
                                        <div class="col-md-4 col-12 d-flex align-items-center gap-2 flex-nowrap">
                                        
                                        @if(isset($trips->trip->end_date) && $trips->trip->end_date < date("Y-m-d"))
                                            <span class="p-1 px-3 rounded text-white
                                                @if($trips->invoice_status == 'Sent') bg-success
                                                @elseif($trips->trip_status == 'Completed') bg-success
                                                @endif" style="white-space: nowrap;">
                                                Trip {{$trips->trip_status}}

                                               
                                            </span>
                                        @else
                                            <span class="p-1 px-3 rounded text-white
                                                @if($trips->trip_status == 'Cancelled') bg-danger
                                                @elseif($trips->trip_status == 'Confirmed') bg-info
                                                
                                                @elseif($trips->trip_status == 'Correction') bg-warning
                                                @elseif($trips->trip_status == 'Draft') bg-secondary
                                                @endif">
                                                {{$trips->trip_status}}
                                            </span>
                                        @endif
                                    

                                       
                                    </div>



                                    </div>
                                </div>
                                <div class="col-4 text-end trip-details">
                                    <a href="{{route('tripDetails',['token'=>$trips->token])}}" class="fw-bold trip-details-a">Trip Details ></a>
                                </div>
                            </div>
                            <h5 class="fw-bold mt-2  text headingss ">{{$trips->trip->name ?? null}}</h5>
                        </div>
                    </div>
                </div>
                 @endforeach
                @else
                    <div class="text-center mt-5">
                        <h5>No Data Found !</h5>
                    </div>
                @endif
               @include('imp')
            </div>
        </div>
    </div>
</div>

@endsection