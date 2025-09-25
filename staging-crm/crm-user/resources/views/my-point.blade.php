@extends('layouts.userlayout.index')
<style>
    th {
        white-space: nowrap;
    }
    td {
        white-space:nowrap;
    }
    .background-5{
        background-color:orange !important;
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
                    <h6 class="text fw-bold mb-3">How to Earn Points</h6>

                    <div class="card border-shadow pb-4 ">

                        <div class="col-md-7 col-12 mx-auto d-block mt-3 ">
                            <div class="row px-2">
                                <div class="col-md-6 col-12 ">
                                    <div class="background-4 rounded p-2 text-center text-white  total-earnd">

                                        <span class="fw-bold">Total Earned</span> <img
                                            src="{{ asset('public/userpanel') }}/asset/images/star.svg">
                                        {{ Auth::user()->points }} <small>Points</small>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 earn-card">
                                    <div class="background-5 rounded p-2 text-center text-white  total-earnd">

                                        <span class="fw-bold">Total Redeemed</span> <img
                                            src="{{ asset('public/userpanel') }}/asset/images/star.svg"> {{ $redeem }}
                                        <small>Points</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (count($point) > 0)
                        <div class="px-3 mt-4" id="transfer-point">
                            <h6 class="text fw-bold">Recent Transactions</h6>

                            <div class="col-12 table-responsive">
                                {{-- @dd($point) --}}
                               
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Earned Date</th>
                                                <th scope="col">Reason</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Trip Name</th>
                                                <th scope="col">Trip Cost</th>
                                                <th scope="col">Expiry Date</th>
                                                <th scope="col">CR/DR</th>
                                             
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($point as $allpoint)
                                                <tr>
                                                    <td>{{ date('d M, Y', strtotime($allpoint->created_at)) }}</td>
                                                    <td>{{ $allpoint->reason }}</td>
                                                     <td>
                                                        @if($allpoint->reason == 'Transfer Received')
                                                            {{ $allpoint->sender_email ?? "-" }}
                                                        @else
                                                            {{ $allpoint->receiver_email ?? "-" }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($allpoint->trip_name)
                                                            {{ $allpoint->trip_name }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($allpoint->cost)
                                                            ₹ {{ number_format($allpoint->cost) }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ date('d M, Y', strtotime($allpoint->expiry_date)) }}</td>
                                                    <td><span
                                                            class="@if ($allpoint->trans_type == 'Cr') background-3 green @else background-6 red @endif p-1 rounded fw-bold">
                                                            @if ($allpoint->trans_type == 'Cr')
                                                                +
                                                            @else
                                                                -
                                                            @endif {{ $allpoint->trans_amt }}
                                                        </span></td>

                                                   
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                    {{ $point->links('pagination::bootstrap-5') }}
                               
                            </div>
                        </div>
                    @endif

                    </div>

                </div>
                <div class=" px-4">
                    @include('imp')
                </div>

            </div>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('page')) {
            const pointsSection = document.getElementById("transfer-point");
            if (pointsSection) {
                pointsSection.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
    </script>

@endsection
