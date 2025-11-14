@extends('layouts.userlayout.index')
@section('container')
    <div class="col-md-10 col-12">
        <div class="border-shadow">
            <div class="card">
                <div class="card-header bg-white information">

                    @include('layouts.userlayout.card-header')

                </div>
                <div class="card-body">
                    <h6 class="text fw-bold mb-3">How to Climb a Tier</h6>
                    <div class="col-12" id="discovery">
                        <div class="row">
                            <div class="col-md-3 col-12 overview-img">
                                <img src="{{ asset('public/userpanel') }}/asset/images/DiscoveryTier.png" alt="">
                            </div>
                            <div class="col-md-9 col-12 px-3">
                                {!! setting('discovery') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3" id="adventurer">
                        <div class="row">
                            <div class="col-md-3 col-12 overview-img">
                                <img src="{{ asset('public/userpanel') }}/asset/images/adventurer-tier2.png" alt="">
                            </div>
                            <div class="col-md-9 col-12 px-3">
                                {!! setting('adventurer') !!}

                            </div>
                        </div>
                    </div>


                    <div class="col-12 mt-3"  id="explorer">
                        <div class="row">
                            <div class="col-md-3 col-12 overview-img">
                                <img src="{{ asset('public/userpanel') }}/asset/images/explorer-tier3.png" alt="">
                            </div>
                            <div class="col-md-9 col-12 px-3">
                                {!! setting('explorer') !!}

                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 mt-3" id="legends">
                        <div class="row">
                            <div class="col-md-3 col-12 overview-img">
                                <img src="{{ asset('public/userpanel') }}/asset/images/legendsTier.png" alt="">
                            </div>
                            <div class="col-md-9 col-12 px-3">
                                {!! setting('legends') !!}

                            </div>
                        </div>
                    </div>
                    @include('imp')
                </div>
            </div>
        </div>
    </div>
@endsection
