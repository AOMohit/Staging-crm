@extends('layouts.userlayout.index')
@section('container')

<div class="col-md-10 col-12">
    <div class="border-shadow">
        <div class="card">
            <div class="card-header bg-white information">
                 @include('layouts.userlayout.card-header')
            </div>
            <div class="card-body">
                <h6 class="text fw-bold mb-3">How to Redeem Points</h6>
                <div class="card border-shadow pb-4 ">
                    <div class="col-md-12 p-3">
                        <form action="{{route('enquirySubmit')}}" method="post">
                            @csrf
                            <input type="hidden" value="redeem" name="type">
                            <div class="row">
                                <div class="col-md-3 col-12 redeem-form">
                                    <label for="">Trip Name <span class="red">*</span></label> <br>
                                    <input type="text" name="expedition" class="form-control mt-2" id="" required placeholder="Enter Trip Name">
                                </div>
                                <div class="col-md-5 col-12 redeem-form">
                                    <label for="">How many points you want to redeem <span class="red">*</span></label> <br>
                                    <input type="text" name="redeem_points" class="form-control mt-2" id="" placeholder="Enter Point">
                                </div>
                                <div class="col-md-4 col-12 redeem-form">
                                    <label for=""> </label><br>
                                    <button class="button p-2 px-2"style="margin-top: 10px!important;">Redeem Points</button>
                                </div>
                            </div>
                        </form>
                      
                    </div>
                    <div class="p-4">
                        <h6 class="text fw-bold">{!!setting('redeem_points_title')!!}</h6>
                       {!!setting('redeem_points')!!}
                    </div>
                </div>
            </div>
            <div class=" px-4">
                @include('imp')
            </div>
        </div>
    </div>
</div>
@endsection