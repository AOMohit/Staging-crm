@extends('layouts.userlayout.index')
@section('container')
    <div class="col-md-10 col-12">
        <div class="border-shadow">
            <div class="card-body">
                <div class="background-2 rounded p-2 overview w-100">
                    <p class="text ">
                        {!! setting('about_us') !!}
                    </p>
                </div>

                <div class="mt-3">
                    <h6 class="text fw-bold">Current Tier</h6>
                    <div class="row mt-3">
                        <div class="col-md-3 col-12 overview-img">
                            {{-- @dd($user->tire) --}}
                            @if ($user->tier == 'Discovery')
                                <img src="{{ asset('public/userpanel') }}/asset/images/DiscoveryTier.png" alt="">
                            @elseif ($user->tier == 'Adventurer')
                                <img src="{{ asset('public/userpanel') }}/asset/images/adventurer-tier2.png" alt="">
                            @elseif ($user->tier == 'Explorer')
                                <img src="{{ asset('public/userpanel') }}/asset/images/explorer-tier3.png" alt="">
                            @elseif($user->tier == 'Legends')
                                <img src="{{ asset('public/userpanel') }}/asset/images/legendsTier.png" alt="">
                            @endif
                        </div>
                        <div class="col-md-9 col-12 overview-cards">
                            <span class=" rounded shadows bg-white p-3">

                                <span class="font-size-14 font-size-mobile-14 fw-bold text"> Total Points Balance:</span>
                                &nbsp; <img src="{{ asset('public/userpanel') }}/asset/images/star.svg" alt="">
                                <span class="fw-bold font-size-20 font-size-mobile-20">{{ Auth::user()->points }}</span>
                                <small class="font-size-10 font-size-mobile-10">Points</small>
                                {{-- &nbsp; <small class="font-size-9 font-size-mobile-9">Expiring on: 26th Jan, 2023</small> --}}

                            </span>
                            <div class="mt-4">
                            <h6 class="text fw-bold">Tier Level</h6>
                         </div>
                           <div class="desktopp">
                            <div class="d-flex justify-content-between mt-3 overview"> 
                                 <a href="{{ route('climbTier') }}#discovery">
                                    <p class="lavel active level-discovery new-icns" data-bs-toggle="tooltip" data-bs-placement="right" title="Discovery level">
                                        <span class="icon-circle">
                                            <img src="{{ asset('public/userpanel/asset/images/unlock.svg') }}" alt="">
                                        </span>
                                        <span class="level-text">
                                            <span class="level-label">Level 1</span>
                                            <span class="level-title">Discovery</span>
                                        </span>
                                    </p>
                                </a>
                                 <a href="{{ route('climbTier') }}#adventurer">
                                    <p class="lavel level-adventure new-icns level-2" data-bs-toggle="tooltip" data-bs-placement="right"
                                        title="Adeventurer level"> 
                                        <span class="icon-circle">
                                        <img src="{{ asset('public/userpanel') }}/asset/images/lock-new.svg"
                                            id="image-adventurer" alt="Adventurer">
                                            </span>
                                            <span class="level-text">
                                        <span class="level-label">Level 2</span>
                                            <span class="level-title">Adventurer</span>
                                            </span>
                                    </p>
                                </a>
                                <a href="{{ route('climbTier') }}#explorer">
                                    <p class="lavel level-explore new-icns level-3" data-bs-toggle="tooltip" data-bs-placement="right"
                                        title="Explorer level">
                                        <span class="icon-circle">
                                        <img
                                            src="{{ asset('public/userpanel') }}/asset/images/lock-new.svg"
                                            alt="" id="image-explorer">
                                            </span>
                                            <span class="level-text">
                                        <span class="level-label">Level 3</span>
                                            <span class="level-title">Explorer</span>
                                            </span></p>
                                </a>
                                <a href="{{ route('climbTier') }}#legends">
                                    <p class="lavel level-legends new-icns level-4" data-bs-toggle="tooltip" data-bs-placement="right"
                                        title="Legends level">
                                        <span class="icon-circle">
                                        <img
                                            src="{{ asset('public/userpanel') }}/asset/images/lock-new.svg"
                                            id="image-legend" alt="">
                                            </span>
                                            <span class="level-text">
                                        <span class="level-label">Level 4</span>
                                        <span class="level-title">Legends</span>
                                        </span> </p>
                                    </a>
                                        

                            </div>
                          </div>

                         <!-- Only for mobile -->
                         <div class="mobile">
                            <div class="col-6 overview d-flex justify-content-between">  
                                <a href="{{ route('climbTier') }}#discovery">
                                    <p class="lavel active level-discovery new-icns" data-bs-toggle="tooltip" data-bs-placement="right" title="Tooltip with svg">
                                        <span class="icon-circle">
                                            <img src="{{ asset('public/userpanel') }}/asset/images/unlock.svg" alt="" class="imgsize-cng">
                                        </span>
                                        <span class="level-text">
                                            <span class="level-label">Level 1</span>
                                            <span class="level-title">Discovery</span>
                                        </span>
                                    </p>
                                </a>
                                <a href="{{ route('climbTier') }}#adventurer">
                                    <p class="lavel level-adventure new-icns level-2" data-bs-toggle="tooltip" data-bs-placement="right"
                                        title="Tooltip with svg"> 
                                        <span class="icon-circle">
                                            <img src="{{ asset('public/userpanel') }}/asset/images/lock-logo.svg" id="image-adventurer-new" alt="" class="imgsize-cng">
                                        </span>
                                        <span class="level-text">
                                        <span class="level-label">Level 2</span>
                                            <span class="level-title">Adventurer</span>
                                        </span>
                                    </p>
                                </a>
                            </div>
                            <div class="col-6 overview d-flex justify-content-between">  
                                 <a href="{{ route('climbTier') }}#explorer">
                                    <p class="lavel level-explore new-icns level-3" data-bs-toggle="tooltip" data-bs-placement="right"
                                        title="Tooltip with svg">
                                        <span class="icon-circle">
                                            <img src="{{ asset('public/userpanel') }}/asset/images/lock-logo.svg"
                                            alt="" id="image-explorer-new" class="imgsize-cng">
                                        </span>
                                        <span class="level-text">
                                        <span class="level-label">Level 3</span>
                                            <span class="level-title">Explorer</span>
                                        </span>
                                    </p>
                                </a>
                                <a href="{{ route('climbTier') }}#legends">
                                    <p class="lavel level-legends new-icns level-4" data-bs-toggle="tooltip" data-bs-placement="right"
                                        title="Tooltip with svg">
                                        <span class="icon-circle">
                                            <img src="{{ asset('public/userpanel') }}/asset/images/lock-logo.svg" id="image-legend-new" alt="" class="imgsize-cng">
                                        </span>
                                        <span class="level-text">
                                        <span class="level-label">Level 4</span>
                                        <span class="level-title">Legends</span>
                                        </span> 
                                    </p>
                                </a>
                            </div>
                        </div>
                    <div>

        
                            @if ($user->tier == 'Discovery')
                            <span class="text"><b>You are on Discovery Tier.</b></span><br>
                                <span class="text">You need to earn 10k+ points to move to <span
                                        class="fw-bold"> Adventurer tier (Level 2)</span>  and start earning 2% of total spends.</span>
                            @elseif ($user->tier == 'Adventurer')
                            <span class="text"><b>You are on Adventurer Tier.</b></span><br>
                                <span class="text">
                                    You need to earn 25k+ points to move to <span
                                        class="fw-bold">Explorer tier (Level 3) </span>  tier and start earning 3% of total spends.</span>
                            @elseif ($user->tier == 'Explorer')
                            <span class="text"><b>You are on Explorer Tier.</b></span><br>
                                <span class="text">You have to complete 10+ trips to move to <span class="fw-bold">Legend tier (Topmost Level)</span>and start earning 5% of total spends.</span>
                            @elseif($user->tier == 'Legends')
                                <span class="text">Hurrayy!! You’re a true road trip enthusiast and one of our most loyal customers.</span>
                            @endif

                        </div>
                    </div>

                </div>
                @include('imp')
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        jQuery(document).ready(function() {
            var tier = '{{ $user->tier }}';
              if (tier == 'Discovery') {
              $('.level-adventure').each(function () {
                const $parent = $(this).parent();
                        if ($parent.is('a')) {
                            $parent.replaceWith($(this));
                        }
                    });
                $('.level-legends').each(function () {
                const $parent = $(this).parent();
                        if ($parent.is('a')) {
                            $parent.replaceWith($(this));
                        }
                    });
                    $('.level-explore').each(function () {
                const $parent = $(this).parent();
                        if ($parent.is('a')) {
                            $parent.replaceWith($(this));
                        }
                    });
            }
            if (tier == 'Adventurer') {
                $('.level-adventure').addClass('active');
                $('#image-adventurer').attr("src", "{{ asset('public/userpanel') }}/asset/images/unlock-brawn.svg")
                $('#image-adventurer-new').attr("src", "{{ asset('public/userpanel') }}/asset/images/unlock-brawn.svg")
                $('.range-division-middel-1').addClass('range-active');
                $('.level-legends').each(function () {
                const $parent = $(this).parent();
                        if ($parent.is('a')) {
                            $parent.replaceWith($(this));
                        }
                    });
                    $('.level-explore').each(function () {
                const $parent = $(this).parent();
                        if ($parent.is('a')) {
                            $parent.replaceWith($(this));
                        }
                    });
            }
          if (tier == 'Explorer') {
                // Add classes
                $('.level-explore').addClass('active');
                $('.level-adventure').addClass('active');
                $('.range-division-middel-1').addClass('range-active');
                $('.range-division-middel-2').addClass('range-active');

                // Change image sources
                $('#image-explorer').attr("src", "{{ asset('public/userpanel') }}/asset/images/unlock-gray-icons.svg");
                $('#image-adventurer').attr("src", "{{ asset('public/userpanel') }}/asset/images/unlock-brawn.svg");
                $('#image-explorer-new').attr("src", "{{ asset('public/userpanel') }}/asset/images/unlock-gray-icons.svg");
                $('#image-adventurer-new').attr("src", "{{ asset('public/userpanel') }}/asset/images/unlock-brawn.svg");

                $('.level-legends').each(function () {
                const $parent = $(this).parent();
                if ($parent.is('a')) {
                    $parent.replaceWith($(this));
                }
            });
               
            }
            if (tier == 'Legends') {
                $('.level-legends').addClass('active');
                $('.level-explore').addClass('active');
                $('.level-adventure').addClass('active');
                $('.range-division-middel-1').addClass('range-active');

                $('.range-division-middel-2').addClass('range-active');
                $('.range-division-end1').addClass('range-active');
                $('.range-division-end').addClass('range-active');

                $('#image-adventurer').attr("src", "{{ asset('public/userpanel') }}/asset/images/unlock-brawn.svg")
                $('#image-explorer').attr("src", "{{ asset('public/userpanel') }}/asset/images/unlock-gray-icons.svg")
                $('#image-legend').attr("src", "{{ asset('public/userpanel') }}/asset/images/unlock-gold-icon.svg")
                $('#image-adventurer-new').attr("src", "{{ asset('public/userpanel') }}/asset/images/unlock-brawn.svg")
                $('#image-explorer-new').attr("src", "{{ asset('public/userpanel') }}/asset/images/unlock-gray-icons.svg")
                $('#image-legend-new').attr("src", "{{ asset('public/userpanel') }}/asset/images/unlock-gold-icon.svg")


            }
        });
    </script>
@endsection
