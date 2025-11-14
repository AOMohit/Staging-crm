@extends('layouts.userlayout.index')
@section('container')

<div class="col-md-10 col-12">
    <div class="border-shadow">
        <div class="card">
            <div class="card-header bg-white information">
               
                @include('layouts.userlayout.card-header')
                
            </div>
            <div class="card-body">
                <h6 class="text fw-bold mb-3">How to Earn Points</h6>

                <div class="card border-shadow pb-4 ">
                   

                    <div class="p-4">
                        <h6 class="text fw-bold">{!!setting('how_to_earn_title')!!}</h6>
                                                  {!!setting('how_to_earn')!!}

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