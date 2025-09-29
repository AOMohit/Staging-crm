<div class="card-header-top col-12">
    <div class="d-flex justify-content-center laptop ">
        {{-- <div class="col-md-3 col-6 header-card-data "> --}}
        
        <!--new code start here-->
        
        
        <div class="bg-white shadows rounded p-2  information-trip mx-2 info-box totals">
            <div class="text curent-tier info-title">Total Trips</div>
            <div class="text fw-bold information-trip-count info-value numberss">{{ count(tripCount()) }}
            </div>
        </div>
        {{-- </div> --}}
        {{-- <div class="col-md-4 col-6 header-card-data "> --}}
        
        
        <div class="bg-white shadows rounded p-2  information-trip mx-2 info-box">
            <div class="text curent-tier info-title"> The Current Tier</div> 
            <div class="text fw-bold information-trip-count info-value">{{ Auth::user()->tier }}</div>
        </div>
        
        
        
        {{-- </div> --}}
        {{-- <div class=" col-lg-5 col-md-12 col-12 header-card-data "> --}}
        @php 
        $points= getPoints(Auth::user()->id);
      
        @endphp
            @if($points != 0)
                <div class="bg-white shadows rounded p-2 information-trip total mx-2 info-box">
                    <div class="new-ak">
                        <div class="curent-tier info-title text">Total Points Balance</div>
                        <img src="{{ asset('public/userpanel') }}/asset/images/star.svg" alt="">
                        <b class="text fw-bold information-trip-count info-value">{{getPoints( Auth::user()->id)}}</b>
                        <span>Points</span>
                        <div class="fw-bold green  rounded px-3 expiring-date">
                            
                            Total points {{getPoints( Auth::user()->id)}} = Rs. {{getPoints( Auth::user()->id)}} (1 point = Rs. 1)
                        </div>
                    </div>
                </div>
            @endif
            
            
            
            <div class="bg-white shadows rounded p-2 information-trip mx-2 info-box"  id="expiryPoints">
                <div class="expiring-point info-title">
                </div>
                <div class="featured-label">
              <a href="{{route('redeemPoint')}}" class="blink-text">Redeem Now</a>
               </div>
            </div>
       
        {{-- </div> --}}


    </div>
    <div class="mobile">
        <div class="row">
            <div class="col-6 header-card-data ">
                <div class="bg-white shadows rounded p-2  information-trip mx-2 text-center">
                    <span class="text"><span class="curent-tier">Total</span> Trips:</span> <span
                        class="text fw-bold information-trip-count">100</span>
                </div>
            </div>
            <div class="col-6 header-card-data ">
                <div class="bg-white shadows rounded p-2  information-trip mx-2 text-center">
                    <span class="text"> <span class="curent-tier"> The Current </span> Tier:</span> <span
                        class="text fw-bold information-trip-count">{{ Auth::user()->tier }}</span>
                </div>
            </div>
            <div class="col-12 header-card-data text-center w-100">
                <div class="bg-white shadows rounded p-2 information-trip mx-2">
                    <span class="text"><span class="curent-tier"> You have total:</span></span>
                    <img src="{{ asset('public/userpanel') }}/asset/images/star.svg" alt="">
                    <span class="text fw-bold information-trip-count">{{ Auth::user()->points }}</span>
                    <small>Points</small>
                    <small class="expiring-point">Expiring on: 26th Jan, 2023</small>
                </div>
            </div>


        </div>
    </div>

</div>
