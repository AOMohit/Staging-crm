<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Geologica:wght@100;300&family=Lato&family=Open+Sans&family=Poppins&family=Roboto&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <title>{{ setting('site_name') }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ env('ADMIN_URL') . 'storage/app/' . setting('logo') }}">
    
    <!-- ...existing code... -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- ...existing code... -->
     <link href="{{ asset('public/userpanel') }}/asset/css/style.css?v={{ time() }}" rel="stylesheet">

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    
</head>
@php
    $current_route = request()->route()->getName();

@endphp

<body>
 <style>
    .hover-name-container {
        position: relative;
        cursor: pointer;
    }

    .full-name-hover {
        display: none;
        position: absolute;
        top: 110%;
        left: 50%;
        transform: translateX(-50%);
        background-color: #333;
        color: #fff;
        padding: 4px 10px;
        font-size: 12px;
        border-radius: 6px;
        white-space: nowrap;
        z-index: 100;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    .hover-name-container:hover .full-name-hover {
        display: block;
    }
</style>
    <header class="header-bg ">
        <div style="margin: 0 auto; flex-wrap: inherit; align-items: center; justify-content: space-between; max-width: 1320px;">
            <span style="padding: 7px; border-radius: 5px; background: #ededed; color: #000; font-size: 12px; font-style: normal; font-weight: 500; line-height: normal; letter-spacing: 0.65px; float: right; margin-top: 6px; margin-right: 76px;">
                <a href="https://stagingnew.adventuresoverland.com/" class="websiteLink">Visit AO Website</a>
            </span>
        </div>
    </header>
  @php
    $firstInitial = isset(Auth::user()->first_name[0]) ? strtoupper(substr( Auth::user()->first_name[0], 0, 1)) : '';
    $lastInitial = isset(Auth::user()->last_name[0]) ? strtoupper(substr(Auth::user()->last_name[0], 0, 1)) : '';
 
    $initials = $firstInitial . $lastInitial;
@endphp
    <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ env('ADMIN_URL') . 'storage/app/' . setting('logo') }}" alt="">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                aria-controls="offcanvasRight" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">

                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="col-md-12 p-0 mb-3" >
                        <div class="background p-2 border-radius d-flex profile">
                             @if(Auth::user()->profile)
                                        <img src="{{ asset('storage/app/' . Auth::user()->profile) }}" class="rounded-circle me-2" width="42" height="42" alt="Profile Image">
                                    @else
                                        <div class="rounded-circle d-flex justify-content-center align-items-center me-2"
                                            style="background-color: #ffffff; margin-top: 0px; width: 43px; height: 43px; font-size: 24px; font-weight: bold;">
                                            {{ $initials }}

                                            <div class="full-name-hover">
                                               
                                                {{ ucwords(strtolower(getCustomerById(Auth::user()->id)->name)) }} <br>
                                                {{ ucwords(strtolower(getCustomerById(Auth::user()->id)->email)) }}
                                            </div>
                                        </div>
                                    @endif
                            <div class="p-2 ps-3">
                                <h6 class="greeting" style="color: #ffff; font-size: 12px; font-weight: 500; margin: 0px;">
                                    Greetings from
                                     </h6>
                                 <h5 class="Ao" style=" color: #fff; font-size: 15px; font-weight: bold;">Adventures Overland</h5>
                            </div>

                        </div>


                        <div class="mt-4 side-nav" style="max-height: 733px;overflow-y:auto;">
                            <ul>
                                <li class="{{ $current_route == 'dashboard' ? 'active' : '' }}"><a
                                        href="{{ route('dashboard') }}">
                                        @if ($current_route == 'dashboard')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/overview.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/overview-not-active.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">Overview</span>
                                    </a>
                                </li>

                                <li class="mt-4 {{ $current_route == 'climbTier' ? 'active' : '' }}"><a
                                    href="{{ route('climbTier') }}">
                                    @if ($current_route == 'climbTier')
                                        <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/climb-active.svg"
                                            alt="">
                                    @else
                                        <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/climb.svg"
                                            alt="">
                                    @endif
                                    <span class="ps-2">How to Climb a Tier</span>
                                    </a>
                            </li>
                                <li
                                    class="mt-4 {{ $current_route == 'mytrip' ? 'active' : '' }} {{ $current_route == 'tripDetails' ? 'active' : '' }}">
                                    <a href="{{ route('mytrip') }}">
                                        @if ($current_route == 'mytrip' || $current_route == 'tripDetails')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/trip-active.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/trip.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">My Trips</span>
                                    </a>
                                </li>

                                <li class="mt-4 {{ $current_route == 'myPoint' ? 'active' : '' }}"><a
                                        href="{{ route('myPoint') }}">
                                        @if ($current_route == 'myPoint')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/point-active.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/point.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">My Points</span>
                                    </a>
                                </li>
                              
                                <li class="mt-4 {{ $current_route == 'howToEarn' ? 'active' : '' }}"><a
                                        href="{{ route('howToEarn') }}">
                                        @if ($current_route == 'howToEarn')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/earn-active.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/earn.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">How to Earn Points</span>
                                    </a>
                                </li>

                                <li class="mt-4 {{ $current_route == 'redeemPoint' ? 'active' : '' }}"><a
                                        href="{{ route('redeemPoint') }}">
                                        @if ($current_route == 'redeemPoint')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/earn-active.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/earn.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">Redeem Points</span>
                                    </a>
                                </li>
                                <li class="mt-4 {{ $current_route == 'transferPoint' ? 'active' : '' }}"><a
                                        href="{{ route('transferPoint') }}">
                                        @if ($current_route == 'transferPoint')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/transfer-active.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/transfer.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">Transfer Points</span>
                                    </a>
                                </li>
                                {{-- <li class="mt-4 {{ $current_route == 'enquiry' ? 'active' : '' }}"><a
                                        href="{{ route('enquiry') }}">
                                        @if ($current_route == 'enquiry')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/enquiry-active.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/enquiry.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">Enquire Now</span>
                                    </a>
                                </li> --}}
                                <li class="mt-4 {{ $current_route == 'user-profile' ? 'active' : '' }}">
                                    <a href="{{ route('user-profile') }}">
                                        @if ($current_route == 'user-profile')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/profile-active.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/profile.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">My Profile</span>
                                    </a>
                                </li>
                                <li class="mt-4 {{ $current_route == 'change-passwords' ? 'active' : '' }}">

                                    <a href="{{ route('change-passwords') }}">
                                        @if ($current_route == 'change-passwords')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/password-active.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/password.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">Change Password</span>
                                    </a>
                                </li>
                                <li class="mt-4"><a href="{{ route('logout') }}">
                                        <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/logout.svg"
                                            alt=""><span class="ps-2">Log Out</span></a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        <div class="text-end d-none d-md-block">
                <a href="#" class="btn-btn-header">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19"
                            fill="none">
                            <path
                                d="M9 0C8.01109 0 7.04439 0.293245 6.22215 0.842652C5.3999 1.39206 4.75904 2.17295 4.3806 3.08658C4.00216 4.00021 3.90315 5.00555 4.09607 5.97545C4.289 6.94536 4.7652 7.83627 5.46447 8.53553C6.16373 9.2348 7.05464 9.711 8.02455 9.90393C8.99445 10.0969 9.99979 9.99784 10.9134 9.6194C11.827 9.24096 12.6079 8.6001 13.1573 7.77785C13.7068 6.95561 14 5.98891 14 5C14 3.67392 13.4732 2.40215 12.5355 1.46447C11.5979 0.526784 10.3261 0 9 0ZM9 8C8.40666 8 7.82664 7.82405 7.33329 7.49441C6.83994 7.16476 6.45542 6.69623 6.22836 6.14805C6.0013 5.59987 5.94189 4.99667 6.05764 4.41473C6.1734 3.83279 6.45912 3.29824 6.87868 2.87868C7.29824 2.45912 7.83279 2.1734 8.41473 2.05764C8.99667 1.94189 9.59987 2.0013 10.1481 2.22836C10.6962 2.45542 11.1648 2.83994 11.4944 3.33329C11.8241 3.82664 12 4.40666 12 5C12 5.79565 11.6839 6.55871 11.1213 7.12132C10.5587 7.68393 9.79565 8 9 8ZM18 19V18C18 16.1435 17.2625 14.363 15.9497 13.0503C14.637 11.7375 12.8565 11 11 11H7C5.14348 11 3.36301 11.7375 2.05025 13.0503C0.737498 14.363 0 16.1435 0 18V19H2V18C2 16.6739 2.52678 15.4021 3.46447 14.4645C4.40215 13.5268 5.67392 13 7 13H11C12.3261 13 13.5979 13.5268 14.5355 14.4645C15.4732 15.4021 16 16.6739 16 18V19H18Z"
                                fill="black" />
                        </svg> &nbsp; WELCOME <span class="text-uppercase">{{ Auth::user()->first_name }}
                            {{ Auth::user()->last_name }}</span>
                    </span>
                </a>
            </div>

        </div>
    </nav>
  
    <div class="wrap mt-1 pt-3 pb-1">
        <div class="container">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-2  d-none d-md-block border-shadow p-0 ak-stkyy">
                        <div class="background p-2 border-radius d-flex align-items-center profile">
                            <div class="d-flex align-items-center">
                                    @if(Auth::user()->profile)
                                        <img src="{{ asset('storage/app/' . Auth::user()->profile) }}" class="rounded-circle me-2" width="42" height="42" alt="Profile Image">
                                    @else
                                        <div class="hover-name-container rounded-circle d-flex justify-content-center align-items-center me-2"
                                            style="background-color: #ffffff; margin-top: 0px; width: 43px; height: 43px; font-size: 24px; font-weight: bold;">
                                            {{ $initials }}

                                            <div class="full-name-hover">
                                               
                                                {{ ucwords(strtolower(getCustomerById(Auth::user()->id)->name)) }} <br>
                                                {{ ucwords(strtolower(getCustomerById(Auth::user()->id)->email)) }}
                                            </div>
                                        </div>
                                    @endif

                                    <div>
                                        <h6 class="greeting mb-0" style="color: #fff; font-size: 10px;">Greetings from</h6>
                                        <h5 class="Ao mb-0" style="color: #fff; font-size: 13px; font-weight: bold; white-space: nowrap;">Adventures Overland</h5>
                                    </div>
                            </div>
                        </div>



                        <div class="mt-3 side-nav side-ds">
                            <ul>
                                <li class="{{ $current_route == 'dashboard' ? 'active' : '' }}"><a
                                        href="{{ route('dashboard') }}">
                                        @if ($current_route == 'dashboard')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/overview.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/overview-not-active.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">Overview</span>
                                    </a>
                                </li>
                                <li class="algn mt-4 {{ $current_route == 'climbTier' ? 'active' : '' }}"><a
                                    href="{{ route('climbTier') }}">
                                    @if ($current_route == 'climbTier')
                                        <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/climb-active.svg"
                                            alt="">
                                    @else
                                        <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/climb.svg"
                                            alt="">
                                    @endif
                                    <span class="ps-2">How to Climb a Tier</span>
                                </a>
                            </li>
                                <li
                                    class="algn mt-4 {{ $current_route == 'mytrip' ? 'active' : '' }} {{ $current_route == 'tripDetails' ? 'active' : '' }}">
                                    <a href="{{ route('mytrip') }}">
                                        @if ($current_route == 'mytrip' || $current_route == 'tripDetails')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/trip-active.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/trip.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">My Trips</span>
                                    </a>
                                </li>

                                <li class="algn mt-4 {{ $current_route == 'myPoint' ? 'active' : '' }}"><a
                                        href="{{ route('myPoint') }}">
                                        @if ($current_route == 'myPoint')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/point-active.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/point.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">My Points</span>
                                    </a>
                                </li>
                               
                                <li class="algn mt-4 {{ $current_route == 'howToEarn' ? 'active' : '' }}"><a
                                        href="{{ route('howToEarn') }}">
                                        @if ($current_route == 'howToEarn')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/earn-active.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/earn.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">How to Earn Points</span>
                                    </a>
                                </li>

                                <li class="algn mt-4 {{ $current_route == 'redeemPoint' ? 'active' : '' }}"><a
                                        href="{{ route('redeemPoint') }}">
                                        @if ($current_route == 'redeemPoint')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/earn-active.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/earn.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">Redeem Points</span>
                                    </a>
                                </li>
                                <li class="algn mt-4 {{ $current_route == 'transferPoint' ? 'active' : '' }}"><a
                                        href="{{ route('transferPoint') }}">
                                        @if ($current_route == 'transferPoint')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/transfer-active.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/transfer.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">Transfer Points</span>
                                    </a>
                                </li>
                                {{-- <li class="algn mt-4 {{ $current_route == 'enquiry' ? 'active' : '' }}"><a
                                        href="{{ route('enquiry') }}">
                                        @if ($current_route == 'enquiry')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/enquiry-active.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/enquiry.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">Enquire Now</span>
                                    </a>
                                </li> --}}
                                <li class="algn mt-4 {{ $current_route == 'user-profile' ? 'active' : '' }}"><a
                                        href="{{ route('user-profile') }}">
                                        @if ($current_route == 'user-profile')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/profile-active.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/profile.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">My Profile</span>
                                    </a>
                                </li>
                                <li class="algn mt-4 {{ $current_route == 'change-passwords' ? 'active' : '' }}">

                                    <a href="{{ route('change-passwords') }}">
                                        @if ($current_route == 'change-passwords')
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/password-active.svg"
                                                alt="">
                                        @else
                                            <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/password.svg"
                                                alt="">
                                        @endif
                                        <span class="ps-2">Change Password</span>
                                    </a>
                                </li>
                                <li class="algn mt-4"><a href="{{ route('logout') }}">
                                        <img src="{{ asset('public/userpanel') }}/asset/images/sidebar/logout.svg"
                                            alt=""><span style="padding-left: 12px!important;">Log Out</span></a>
                                </li>
                            </ul>
                        </div>

                    </div>
