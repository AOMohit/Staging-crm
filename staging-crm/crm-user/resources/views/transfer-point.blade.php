@extends('layouts.userlayout.index')
<style>
    th {
        white-space: nowrap;
    }
    td {
        white-space:nowrap;
    }
</style>
<style>
    #points {
      scroll-margin-top: 79px; /* adjust to your header height */
    }
    html {
      scroll-behavior: smooth;
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
                <h6 class="text fw-bold mb-3">Transfer Your Points</h6>

                <div class="card border-shadow pb-4 ">
                    

                    <div class="col-md-7 mx-auto d-block mt-3 justify-content-center">
                        <div class="row px-2" style="justify-content: center; margin-bottom: 10px;">
                            <div class="col-md-8 col-12">
                                <div class="background-4  rounded p-2 text-center text-white total-earnd">
                                    <span class="fw-bold">Total Transferred Received</span> 
                                    <img src="{{asset('public/userpanel')}}/asset/images/star.svg"> 
                                    &nbsp; {{$receiver}} 
                                    <small>Points</small>
                                </div>
                            </div>

                            {{-- <div class="col-md-6 col-12 earn-card">
                                <div class="background-7 rounded p-2 text-center text-white total-earnd">

                                    <span class="fw-bold">Total Transferred</span> <img src="{{asset('public/userpanel')}}/asset/images/star.svg"> &nbsp; {{$transfer}} <small>Points</small>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="container mt-4" >
                        <div class="row">
                            <div class="col-12 col-md-4 redeem-form">
                                <h6 class="text fw-bold">Transfer your points</h6>
                                <form action="{{route('transferCoin')}}" method="post" id="transferForm">
                                    @csrf
                                    <label for="" class="mt-3">Enter email id</label><br>
                                    <input type="text" name="email" class="form-control" placeholder="name@mail.com" required>

                                    <label for="" class="mt-3">Enter points to transfer</label><br>
                                    <input type="text" name="points" class="form-control" placeholder="500" required>

                                    <button class="button mt-3 px-4 p-2 ">Transfer Points</button>
                                </form>


                            </div>
                            <div class="col-12 col-md-1">

                            </div>
                            <div class="col-12 col-md-7">
                                <div class="background-2 rounded p-2">
                                    {!!setting('transfer_points')!!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (count($point)>0)
                    <div class="px-3 mt-5" id="points"  >
                        <h6 class="text fw-bold">Recent Transactions</h6>

                        <div class="col-12 table-responsive">
                          
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
            <!-- important note -->
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
            const pointsSection = document.getElementById('points');
            if (pointsSection) {
                pointsSection.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
    const transferForm = document.getElementById('transferForm');
        if (transferForm) {
            transferForm.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to transfer points?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ffb224',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, transfer!',
                    cancelButtonText: 'Cancel',
                    width: '400px',
                    heightAuto: '-10px',
                }).then((result) => {
                    if (result.isConfirmed) {
                        transferForm.submit();
                    }
            });
        });
        }
</script>
    
@endsection