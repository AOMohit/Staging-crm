@php
    $current_route = request()->route()->getName();
    $user = App\Models\User::find(auth()->user()->id);
    if ($user->role_id != 0) {
        $check = $user->role->permission;
        $checkAdmin = $check->admin;
    } else {
        $check = [];
        $checkAdmin = 1;
    }
    $perArray = [];
    if ($checkAdmin == 1) {
        true;
    } else {
        $per = $check->toArray();
        foreach ($per as $key => $val) {
            if ($val == 1) {
                array_push($perArray, $key);
            }
        }
    }

@endphp

<!DOCTYPE html>

<html lang="en"
    class="@if (setting('site_theme') == 'Dark') dark-style @else light-style @endif layout-navbar-fixed layout-menu-fixed"
    dir="ltr" data-theme="theme-default" data-assets-path="{{ url('public/admin') }}/assets/"
    data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ setting('site_name') }}</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ url('storage/app/' . setting('logo')) }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/fonts/materialdesignicons.css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/fonts/fontawesome.css" />
    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/node-waves/node-waves.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/css/rtl/core.css"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/css/rtl/theme-default.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet"
        href="{{ url('public/admin') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet"
        href="{{ url('public/admin') }}/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ url('public/admin') }}/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/swiper/swiper.css" />

    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet"
        href="{{ url('public/admin') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/tagify/tagify.css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/typeahead-js/typeahead.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/css/pages/cards-statistics.css" />
    <!-- Helpers -->
    <script src="{{ url('public/admin') }}/assets/vendor/js/helpers.js"></script>

    <script src="{{ url('public/admin') }}/assets/vendor/js/template-customizer.js"></script>
    <script src="{{ url('public/admin') }}/assets/js/config.js"></script>

    <link rel="stylesheet" href="{{ url('public/admin/assets/bootstrap-4.min.css') }}">
<style>
   
   .swal2-title{
    font-size: 13px !important;
    font-weight: 600 !important;
    padding: 25px 15px !important
   }
   .swal2-container{
        z-index: 999999 !important;
    }
</style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="{{ route('dashboard') }}" class="app-brand-link ml-3">
                        <span class="app-brand-logo demo">
                            <img style="height: 55px; width: auto;" src="{{ url('storage/app/' . setting('logo')) }}"
                                alt="{{ setting('site_name') }}">
                        </span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.4854 4.88844C11.0081 4.41121 10.2344 4.41121 9.75715 4.88844L4.51028 10.1353C4.03297 10.6126 4.03297 11.3865 4.51028 11.8638L9.75715 17.1107C10.2344 17.5879 11.0081 17.5879 11.4854 17.1107C11.9626 16.6334 11.9626 15.8597 11.4854 15.3824L7.96672 11.8638C7.48942 11.3865 7.48942 10.6126 7.96672 10.1353L11.4854 6.61667C11.9626 6.13943 11.9626 5.36568 11.4854 4.88844Z"
                                fill="currentColor" fill-opacity="0.6" />
                            <path
                                d="M15.8683 4.88844L10.6214 10.1353C10.1441 10.6126 10.1441 11.3865 10.6214 11.8638L15.8683 17.1107C16.3455 17.5879 17.1192 17.5879 17.5965 17.1107C18.0737 16.6334 18.0737 15.8597 17.5965 15.3824L14.0778 11.8638C13.6005 11.3865 13.6005 10.6126 14.0778 10.1353L17.5965 6.61667C18.0737 6.13943 18.0737 5.36568 17.5965 4.88844C17.1192 4.41121 16.3455 4.41121 15.8683 4.88844Z"
                                fill="currentColor" fill-opacity="0.38" />
                        </svg>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">

                    <li class="menu-item {{ $current_route == 'dashboard' ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                            <div data-i18n="Dashboard">Dashboard</div>
                        </a>
                    </li>

                    @if (in_array('trip', $perArray) || $checkAdmin == 1)
                        <li
                            class="menu-item {{ $current_route == 'trip.index' || $current_route == 'trip.view' || $current_route == 'trip.add' || $current_route == 'trip.edit' ? 'active' : '' }}">
                            <a href="{{ route('trip.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-plane-car"></i>
                                <div data-i18n="Trips">Trips</div>
                            </a>
                        </li>
                    @endif

                    @if (in_array('booking', $perArray) || $checkAdmin == 1)
                        <li
                            class="menu-item {{ $current_route == 'booking.index' || $current_route == 'booking.view' || $current_route == 'booking.new-trip' ? 'active' : '' }}">
                            <a href="{{ route('booking.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-book-account"></i>
                                <div data-i18n="Bookings">Bookings</div>
                            </a>
                        </li>
                    @endif

                    @if (in_array('customer', $perArray) || $checkAdmin == 1)
                        <li
                            class="menu-item {{ $current_route == 'customer.index' || $current_route == 'customer.view' || $current_route == 'customer.add' || $current_route == 'customer.edit' ? 'active' : '' }}">
                            <a href="{{ route('customer.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-account-group"></i>
                                <div data-i18n="Customers">Customers</div>
                            </a>
                        </li>
                    @endif

                    @if (in_array('birthdays', $perArray) || $checkAdmin == 1)
                        <li class="menu-item {{ $current_route == 'birthday.index' ? 'active' : '' }}">
                            <a href="{{ route('birthday.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-cake-variant"></i>
                                <div data-i18n="Birthdays">Birthdays</div>
                            </a>
                        </li>
                    @endif

                    @if (in_array('agent', $perArray) || $checkAdmin == 1)
                        <li
                            class="menu-item {{ $current_route == 'agent.index' || $current_route == 'agent.view' || $current_route == 'agent.add' || $current_route == 'agent.edit' ? 'active' : '' }}">
                            <a href="{{ route('agent.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-face-agent"></i>
                                <div data-i18n="Travel Agents">Travel Agents</div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('vendors', $perArray) || $checkAdmin == 1)
                        <li
                            class="menu-item {{ $current_route == 'vendors.index' || $current_route == 'vendors.view' || $current_route == 'vendors.add' || $current_route == 'vendors.edit' ? 'active' : '' }}">
                            <a href="{{ route('vendors.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-store"></i>
                                <div data-i18n="Vendors">Vendors</div>
                            </a>
                        </li>
                    @endif

                    @if (in_array('inventory_category', $perArray) || $checkAdmin == 1)
                        <li class="menu-item {{ $current_route == 'inventory_category.index' ? 'active' : '' }}">
                            <a href="{{ route('inventory_category.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-shape"></i>
                                <div data-i18n="Inventory Category">Inventory Category</div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('inventory', $perArray) || $checkAdmin == 1)
                        <li
                            class="menu-item {{ $current_route == 'inventory.index' || $current_route == 'inventory.view' ? 'active' : '' }}">
                            <a href="{{ route('inventory.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-shopping"></i>
                                <div data-i18n="Inventory Stock">Inventory Stock</div>
                            </a>
                        </li>
                    @endif

                    @if (in_array('report', $perArray) || $checkAdmin == 1)
                        <li class="menu-item {{ $current_route == 'report.index' ? 'active' : '' }}">
                            <a href="{{ route('report.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-file-chart"></i>
                                <div data-i18n="Reports">Reports</div>
                            </a>
                        </li>
                    @endif

                    @if (in_array('loyalty', $perArray) || $checkAdmin == 1)
                        <li
                            class="menu-item {{ $current_route == 'loyalty.index' || $current_route == 'loyalty.gift' ? 'active' : '' }}">
                            <a href="{{ route('loyalty.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-star-four-points-circle-outline"></i>
                                <div data-i18n="Loyalty Program">Loyalty Program</div>
                            </a>
                        </li>
                    @endif


                    @if (in_array('setting', $perArray) || $checkAdmin == 1)
                        <!-- setting -->
                        <li class="menu-item  @if (
                            $current_route == 'setting.site' ||
                                $current_route == 'setting.loyalty-point-terms-condition' ||
                                $current_route == 'setting.loyalty-point-faq' ||
                                $current_route == 'setting.contact' ||
                                $current_route == 'setting.terms' ||
                                $current_route == 'setting.third-party' ||
                                $current_route == 'setting.about' ||
                                $current_route == 'setting.important' ||
                                $current_route == 'setting.tier' ||
                                $current_route == 'setting.earn' ||
                                $current_route == 'setting.redeem' ||
                                $current_route == 'setting.transfer' ||
                                $current_route == 'setting.extra_service.index' ||
                                $current_route == 'setting.vendor_category.index' ||
                                $current_route == 'setting.vendor_service.index' ||
                                $current_route == 'setting.merchandise.index' ||
                                $current_route == 'setting.stationary.index' ||
                                $current_route == 'setting.relationship.index' ||
                                $current_route == 'setting.offset' ||
                                $current_route == 'setting.privacy') active open @endif">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons mdi mdi-cog"></i>
                                <div data-i18n="Settings">Settings</div>
                                {{-- <div class="badge bg-primary rounded-pill ms-auto">3</div> --}}
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item {{ $current_route == 'setting.site' ? 'active' : '' }}">
                                    <a href="{{ route('setting.site') }}" class="menu-link">
                                        <div data-i18n="Site">Site</div>
                                    </a>
                                </li>
                                <li class="menu-item {{ $current_route == 'setting.loyalty-point-terms-condition' ? 'active' : '' }}">
                                    <a href="{{ route('setting.loyalty-point-terms-condition') }}" class="menu-link">
                                        <div data-i18n="Loyalty Points T&C">Loyalty Points T&C</div>
                                    </a>
                                </li>
                                <li class="menu-item {{ $current_route == 'setting.loyalty-point-faq' ? 'active' : '' }}">
                                    <a href="{{ route('setting.loyalty-point-faq') }}" class="menu-link">
                                        <div data-i18n="Loyalty Points FAQ">Loyalty Points FAQ</div>
                                    </a>
                                </li>
                                <li class="menu-item {{ $current_route == 'setting.third-party' ? 'active' : '' }}">
                                    <a href="{{ route('setting.third-party') }}" class="menu-link">
                                        <div data-i18n="Third Party">Third Party</div>
                                    </a>
                                </li>
                                {{-- <li class="menu-item {{ $current_route == 'setting.contact' ? 'active' : '' }}">
                                    <a href="{{ route('setting.contact') }}" class="menu-link">
                                        <div data-i18n="Contact">Contact</div>
                                    </a>
                                </li> --}}
                                {{-- <li class="menu-item {{ $current_route == 'setting.privacy' ? 'active' : '' }}">
                                    <a href="{{ route('setting.privacy') }}" class="menu-link">
                                        <div data-i18n="Privacy Policy">Privacy Policy</div>
                                    </a>
                                </li> --}}

                                <li class="menu-item {{ $current_route == 'setting.terms' ? 'active' : '' }}">
                                    <a href="{{ route('setting.terms') }}" class="menu-link">
                                        <div data-i18n="Terms Conditions">Terms Conditions</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ $current_route == 'setting.about' ? 'active' : '' }}">
                                    <a href="{{ route('setting.about') }}" class="menu-link">
                                        <div data-i18n="Overview">Overview</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ $current_route == 'setting.important' ? 'active' : '' }}">
                                    <a href="{{ route('setting.important') }}" class="menu-link">
                                        <div data-i18n="Important Notes">Important Notes</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ $current_route == 'setting.tier' ? 'active' : '' }}">
                                    <a href="{{ route('setting.tier') }}" class="menu-link">
                                        <div data-i18n="How to Climb a Tier">How to Climb a Tier</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ $current_route == 'setting.earn' ? 'active' : '' }}">
                                    <a href="{{ route('setting.earn') }}" class="menu-link">
                                        <div data-i18n="How to Earn Points">How to Earn Points</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ $current_route == 'setting.redeem' ? 'active' : '' }}">
                                    <a href="{{ route('setting.redeem') }}" class="menu-link">
                                        <div data-i18n="Redeem Points">Redeem Points</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ $current_route == 'setting.transfer' ? 'active' : '' }}">
                                    <a href="{{ route('setting.transfer') }}" class="menu-link">
                                        <div data-i18n="Transfer Points">Transfer Points</div>
                                    </a>
                                </li>

                                <li
                                    class="menu-item {{ $current_route == 'setting.extra_service.index' ? 'active' : '' }}">
                                    <a href="{{ route('setting.extra_service.index') }}" class="menu-link">
                                        <div data-i18n="Extra Services">Extra Services</div>
                                    </a>
                                </li>
                                <li
                                    class="menu-item {{ $current_route == 'setting.vendor_category.index' ? 'active' : '' }}">
                                    <a href="{{ route('setting.vendor_category.index') }}" class="menu-link">
                                        <div data-i18n="Vendor Category">Vendor Category</div>
                                    </a>
                                </li>
                                <li
                                    class="menu-item {{ $current_route == 'setting.vendor_service.index' ? 'active' : '' }}">
                                    <a href="{{ route('setting.vendor_service.index') }}" class="menu-link">
                                        <div data-i18n="Vendor Service">Vendor Service</div>
                                    </a>
                                </li>

                                <li
                                    class="menu-item {{ $current_route == 'setting.merchandise.index' ? 'active' : '' }}">
                                    <a href="{{ route('setting.merchandise.index') }}" class="menu-link">
                                        <div data-i18n="Merchandise">Merchandise</div>
                                    </a>
                                </li>

                                <li
                                    class="menu-item {{ $current_route == 'setting.stationary.index' ? 'active' : '' }}">
                                    <a href="{{ route('setting.stationary.index') }}" class="menu-link">
                                        <div data-i18n="Stationary">Stationary</div>
                                    </a>
                                </li>

                                <li
                                    class="menu-item {{ $current_route == 'setting.relationship.index' ? 'active' : '' }}">
                                    <a href="{{ route('setting.relationship.index') }}" class="menu-link">
                                        <div data-i18n="Relationship">Relationship</div>
                                    </a>
                                </li>

                                 {{-- <li
                                    class="menu-item {{ $current_route == 'setting.offset' ? 'active' : '' }}">
                                    <a href="{{ route('setting.offset') }}" class="menu-link">
                                        <div data-i18n="Carbon Offset">Carbon Offset</div>
                                    </a>
                                </li> --}}
                               
                               

                            </ul>
                        </li>
                    @endif

                    @if (in_array('roles_permission', $perArray) || $checkAdmin == 1)
                        <li class="menu-item {{ $current_route == 'roles_permission.index' ? 'active' : '' }}">
                            <a href="{{ route('roles_permission.index') }}" class="menu-link">
                                <i class="menu-icon mdi mdi-account-key"></i>
                                <div data-i18n="Roles & Permissions">Roles & Permissions</div>
                            </a>
                        </li>
                    @endif


                    @if (in_array('staff', $perArray) || $checkAdmin == 1)
                        <li class="menu-item {{ $current_route == 'staff.index' ? 'active' : '' }}">
                            <a href="{{ route('staff.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-account-star"></i>
                                <div data-i18n="Team">Team</div>
                            </a>
                        </li>
                    @endif


                    @if (in_array('enquiry', $perArray) || $checkAdmin == 1)
                        <li class="menu-item {{ $current_route == 'enquiry.index' ? 'active' : '' }}">
                            <a href="{{ route('enquiry.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-help-circle"></i>
                                <div data-i18n="Enquiries">Enquiries</div>
                                <span id="unread-count" style="background-color: orange; color: white; font-size: 14px; font-weight: bold; padding: 8px; border-radius: 50%; display: inline-flex; justify-content: center; align-items: center; width: 21px; height: 20px; text-align: center; margin-left:70px;">
                                
                            </span>
                            </a>
                          
                        </li>
                                           
               
                    @endif

                    @if (in_array('sustainability', $perArray) || $checkAdmin == 1)
                        <li class="menu-item {{ $current_route == 'sustainability.index' ? 'active' : '' }}">
                            <a href="{{ route('sustainability.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-certificate-outline"></i>
                                <div data-i18n="Sustainability">Sustainability</div>
                            </a>
                        </li>
                    @endif

                    <!-- Account Section -->
                    @if (in_array('accounts', $perArray) || $checkAdmin == 1)
                        <li class="menu-item  @if ($current_route == 'accounts.check-expense' || $current_route == 'accounts.payment-received') active open @endif">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons mdi mdi-book-open-page-variant"></i>
                                <div data-i18n="Accounts">Accounts</div>
                            </a>
                            <ul class="menu-sub">
                                <li
                                    class="menu-item {{ $current_route == 'accounts.check-expense' ? 'active' : '' }}">
                                    <a href="{{ route('accounts.check-expense') }}" class="menu-link">
                                        <div data-i18n="Check Expense">Check Expense</div>
                                    </a>
                                </li>
                                <li
                                    class="menu-item {{ $current_route == 'accounts.payment-received' ? 'active' : '' }}">
                                    <a href="{{ route('accounts.payment-received') }}" class="menu-link">
                                        <div data-i18n="Payment Received">Payment Received</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    <li class="menu-item {{ $current_route == 'logout' ? 'active' : '' }}">
                        <a href="{{ route('logout') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-location-exit"></i>
                            <div data-i18n="Logout">Logout</div>
                        </a>
                    </li>


                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="mdi mdi-menu mdi-24px"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        {{-- <div class="navbar-nav align-items-center">
                            <div class="nav-item navbar-search-wrapper mb-0">
                                <a class="nav-item nav-link search-toggler fw-normal px-0" href="javascript:void(0);">
                                    <i class="mdi mdi-magnify mdi-24px scaleX-n1-rtl"></i>
                                    <span class="d-none d-md-inline-block text-muted">Search (Ctrl+/)</span>
                                </a>
                            </div>
                        </div> --}}
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">

                            <!-- Style Switcher -->
                            <li class="nav-item me-1 me-xl-0">
                                <a class="nav-link btn btn-text-secondary rounded-pill btn-icon style-switcher-toggle hide-arrow"
                                    href="javascript:void(0);">
                                    <i class="mdi mdi-24px"></i>
                                </a>
                            </li>
                            <!--/ Style Switcher -->

                            <!-- Notification -->
                            {{-- <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2 me-xl-1">
                                <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                    href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                    aria-expanded="false">
                                    <i class="mdi mdi-bell-outline mdi-24px"></i>
                                    <span
                                        class="position-absolute top-0 start-50 translate-middle-y badge badge-dot bg-danger mt-2 border"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end py-0">
                                    <li class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h6 class="mb-0 me-auto">Notification</h6>
                                            <span class="badge rounded-pill bg-label-primary">8 New</span>
                                        </div>
                                    </li>

                                    <li class="dropdown-menu-footer border-top p-2">
                                        <a href="javascript:void(0);"
                                            class="btn btn-primary d-flex justify-content-center">
                                            View all notifications
                                        </a>
                                    </li>
                                </ul>
                            </li> --}}
                            <!--/ Notification -->

                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="@if (auth()->user()->image) {{ url('storage/app/' . auth()->user()->image) }} @else {{ url('public/admin') }}/assets/img/avatars/1.png @endif"
                                            alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javaScript:void(0)">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="@if (auth()->user()->image) {{ url('storage/app/' . auth()->user()->image) }} @else {{ url('public/admin') }}/assets/img/avatars/1.png @endif"
                                                            alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span
                                                        class="fw-semibold d-block">{{ auth()->user()->name }}</span>
                                                    <small class="text-muted">{{ auth()->user()->email }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('my-profile') }}">
                                            <i class="mdi mdi-account-outline me-2"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('change-password') }}">
                                            <i class="mdi mdi-account-outline me-2"></i>
                                            <span class="align-middle">Change Password</span>
                                        </a>
                                    </li>

                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" target="_blank">
                                            <i class="mdi mdi-logout me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>

                    <!-- Search Small Screens -->
                    {{-- <div class="navbar-search-wrapper search-input-wrapper d-none">
                        <input type="text" class="form-control search-input container-xxl border-0"
                            placeholder="Search..." aria-label="Search..." />
                        <i class="mdi mdi-close search-toggler cursor-pointer"></i>
                    </div> --}}
                </nav>

                <!-- / Navbar -->

                @yield('content')




            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- buid/assets/vendor/js/core.js -->
    <script src="{{ url('public/admin') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/node-waves/node-waves.js"></script>

    <script src="{{ url('public/admin') }}/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>

    <script src="{{ url('public/admin') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ url('public/admin') }}/assets/vendor/libs/select2/select2.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/tagify/tagify.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/bloodhound/bloodhound.js"></script>

    <script src="{{ url('public/admin') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/swiper/swiper.js"></script>

    <!-- Main JS -->
    <script src="{{ url('public/admin') }}/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="{{ url('public/admin') }}/assets/js/dashboards-ecommerce.js"></script>

    <!-- Page JS -->
    <script src="{{ url('public/admin') }}/assets/js/tables-datatables-basic.js"></script>
    <script src="{{ url('public/admin') }}/assets/js/forms-selects.js"></script>
    <script src="{{ url('public/admin') }}/assets/js/forms-tagify.js"></script>
    <script src="{{ url('public/admin') }}/assets/js/forms-typeahead.js"></script>

    @yield('script')

    <script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('about_us');
        CKEDITOR.replace('terms_condition');
      
        CKEDITOR.replace('privacy_policy');
        CKEDITOR.replace('important_notes');
        CKEDITOR.replace('discovery');
        CKEDITOR.replace('explorer');
        CKEDITOR.replace('legends');
        CKEDITOR.replace('adventurer');
        CKEDITOR.replace('how_to_earn');
        CKEDITOR.replace('redeem_points');
        CKEDITOR.replace('transfer_points');
        CKEDITOR.replace('loyalty_points_terms');
    </script>
</body>

</html>
<script>
    
    $(document).ready(function() {
        fetchUnreadCount();
        setInterval(fetchUnreadCount, 3000);
       
    });
    function fetchUnreadCount() {
            $.ajax({
                url: "{{ url('/enquiries/unread-count') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                   
                   
                    if(response.unread_count != 0)
                    {
                        $("#unread-count").text(response.unread_count);
                    }
                    else{
                        $("#unread-count").hide();
                    }
                  
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching unread count:", error);
                }
            });
        }
</script>
<script>
  function getStateByCountry(country) {
    const selectedState = document.querySelector('[name=state]').value;

    $.ajax({
        url: "{{ route('getStateByCountry') }}",
        method: "POST",
        data: {
            "_token": "{{ csrf_token() }}",
            "country": country,
            "selected": selectedState  
        },
        success: function(res) {
            $("#states").html(res);
        }
    });
}
</script>

<script src="{{ url('public/admin/assets/sweetalert2.min.js') }}"></script>
<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer:5000,
        timerProgressBar: true,
    });

</script>

{{-- error msg --}}
<script>
    @if (Session::has('success'))
        $(function() {
            Toast.fire({
                icon: "success",
                title: "{{ session('success') }}"
            });
        });
    @endif

    @if (Session::has('error'))
        $(function() {
            Toast.fire({
                icon: "error",
                title: "{{ session('error') }}"
            });
        });
    @endif

    @if (Session::has('warning'))
        $(function() {
            Toast.fire({
                icon: "warning",
                title: "{{ session('warning') }}"
            });
        });
    @endif
</script>

<script>
    // Create our number formatter.
    const formatter = new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
    });


    $(document).ready(function() {
        getSummary();
    });


    function getSummary() {
        $.ajax({
            url: "{{ route('booking.trip.summary') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                token: "{{ request()->token }}",
            },
            success: function(res) {
             
             
                if (!res) {
                    // alert("Somthing Went Wrong, try again.")
                } else {
                    var data = JSON.parse(res);
                    var all_point_send_to_paid_customer = data.all_payment_payer;
                    var trip_costs = data.trip_costs;
                    var deviations = data.deviation;
                    var extra_services = data.extra_services;
                    var vsa = data.vehicle_security;
                    var seatCharge = data.vehicle_seat_charge;
                    var roomInfo = data.room_info;
                    var taxes = data.taxes;
                    var points = data.points;
                    var payment = data.advance_payment;
                    var partPayment = data.part_payment;
                    var earnedpoints = data.points_list;
                    var actual_trip_cost = data.actual_trip_cost;
                    var real_trip_amt = data.real_trip_amt;
                    var package_offer_A = data.package_offer_A;
                    var a_and_b = data.a_and_b;
                    var package_c_data = data.package_c;
                    var extra_services_redeemable = data.extra_services_redeemable;
                    var vehicle_type=data.vehicle_type;
                    var vehicle_seat = data.vehicle_seat;
                    var carbon_infos = data.carbon_infos;
                    var credit_note_amounts = data.credit_note_amounts;

                    var total_trip_cost = 0;
                    var total_redeem_amt = 0;
                    var total_payable_amt = 0;
                    var total_rcvd_amt = 0;
                    var totalPoints = 0;
                    var totalVehicleSecAmt = 0;
                    var totalVehicleSeatAmt = 0;
                    var totalRoomCharges = 0;

                    var trip_cost_list = "";
                    var extra_service_list = ``;
                    var vs = ``;
                    var tax_list = `<h6 class="fw-bold text font-size-14">Tax</h6>`;
                    var redeem_poins_data = "";
                    var final_paid = "";
                    var room_info_data = "";
                    var actual_trip_cost_data = "";
                    var loyalty_total_data = "";
                    var discount_trip_cost_data = "";
                    var package_a_b = "";
                    var vehicle_seat_amt = "";
                    var package_c = "";
                    var extra_service_list_redeem = "";
                    var carbon_infos_data = ``;
                    var deviations_list = ``;
                    var credit_note_list = ``;
                    
                    $.each(all_point_send_to_paid_customer, function(key, value) {
                        var totalpaidpoints=`<span class="gray t-t font-size-12">Points transferred to {${value['name']}}, because this customer paid for entire trip ${value['all_points_send_to_paid_customer']} Points   </span><br>`;
                    $("#toptal-points-grp").html(totalpaidpoints);
                    });
                    
                   

                    // Basic Package Cost
                    $.each(trip_costs, function(key, value) {
                        total_trip_cost += parseInt(value.cost);
                        var isParent = "";
                        if (value.parent > 0) {
                            isParent = `(${value.relation})`;
                        }
                        trip_cost_list += `<div class="d-flex justify-content-between">
                                                    <p class="gray font-size-12">
                                                        ${value.traveler}  ${isParent}
                                                        <span data-bs-toggle="tooltip" data-bs-placement="right"
                                                            title="Vehicle Amount = ${formatter.format(value.vehicle_amt)}, Room Amount = ${formatter.format(value.room_amt)}, Comment = ${value.comment} ">
                                                            <img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg"
                                                                alt="" class="ps-2">
                                                        </span>
                                                    </p>
                                                    <h6 class="font-size-14">${formatter.format(value.cost)}
                                                    </h6>
                                                </div>`;
                    });
                    $("#trip_cost").html(trip_cost_list);
                    // Basic Package Cost


                    // Deviation
                    if(deviations && deviations.length > 0)
                    {
                        $.each(deviations, function(key, value) {
                        if (value && value.deviation_type == "Add") {
                            total_trip_cost += parseInt(value.deviation_amt);
                            var devSign = "+";
                        } else {
                            total_trip_cost -= parseInt(value.deviation_amt);
                            var devSign = "-";
                        }

                        var isParent = "";
                        if (value.parent > 0) {
                            isParent = `(${value.relation})`;
                        }
                    
                       
                            deviations_list += `<div class="d-flex justify-content-between">
                                                    <p class="gray font-size-12">Traveler ${ key + 1 }
                                                        (${value.traveler})  ${isParent}
                                                        <span data-bs-toggle="tooltip" data-bs-placement="right"
                                                            title="Type = ${value.deviation_type}, Comment = ${value.deviation_comment},">
                                                            <img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg"
                                                                alt="" class="ps-2">
                                                        </span>
                                                    </p>
                                                    <h6 class="font-size-14">${devSign}${formatter.format(value.deviation_amt)}
                                                    </h6>
                                                </div>`;
                                                $("#deviations").html(deviations_list);
                     
                       
                        });
                    }
                    else{
                        $(".deviation").hide();


                    }
                   
                    // Devation

                    // discount in trip
                    $.each(trip_costs, function(key, value) {
                         let discount = 0;

                            if (value.cost !== 0) {
                                discount = real_trip_amt - value.cost;
                            }

                        discount_trip_cost_data += `<div class="d-flex justify-content-between">
                            <p class="gray font-size-12"> ${value.traveler}
                            </p>
                            <h6 class="font-size-14">${formatter.format(discount)}
                            </h6>
                        </div>`;
                    });
                    $("#discount_trip_cost").html(discount_trip_cost_data);
                    // discount in trip

                    // redeem points
                    $.each(points, function(key, value) {
                        total_redeem_amt += parseInt(value.points);
                        redeem_poins_data += `<div class="d-flex justify-content-between">
                                                <span class="gray font-size-12"> ${value.traveler} </span>
                                                <h6 class="font-size-12">-${formatter.format(value.points)}</h6>
                                            </div>`;
                    });
                    $("#redeem_points_list").html(redeem_poins_data);
                    // redeem points

                    // Package Cost Offered (A)
                    $.each(package_offer_A, function(key, value) {

                        actual_trip_cost_data += `<div class="d-flex justify-content-between">
                                                    <p class="gray font-size-12"> ${value.traveler}
                                                    </p>
                                                    <h6 class="font-size-14">${formatter.format(value.cost)}
                                                    </h6>
                                                </div>`;
                    });
                    $("#actual_trip_cost, #actual_trip_cost_for_loyalty").html(actual_trip_cost_data);
                    // Package Cost Offered (A)

                    // Supplementary start
                    if (parseInt(seatCharge) > 0) {
                        total_trip_cost += parseInt(seatCharge);
                        console.log(total_trip_cost);
                        vehicle_seat_amt += `<div class="border-bottoms">
                                <div class="d-flex justify-content-between">
                                    <span class="text font-size-14 d-flex">Extra Seat (${vehicle_seat})- ${vehicle_type}</span>
                                    <h6 class="font-size-14">${formatter.format(seatCharge)}</h6>
                                </div>
                            </div>`;
                    }
                    $("#vehicle_seat_amt").html(vehicle_seat_amt);

                    // room info
                    $.each(roomInfo, function(key, value) {
                        // if (value.room_type_amt) {
                            total_trip_cost += parseInt(value.room_type_amt);
                            room_info_data += `<div class="d-flex justify-content-between">
                                                    <span class="gray font-size-12">
                                                        Room Charges (${value.room_type})${value.room_cat && value.room_cat !== '' ? ' - ' + value.room_cat : ''}
                                                    </span>
                                                    {{--<h6 class="font-size-12">${formatter.format(value.room_type_amt)}</h6>--}}
                                                    <h6 class="font-size-12"> ${value.room_type_amt > 0 ? formatter.format(value.room_type_amt) : ''}</h6>
                                                </div>`;
                        // }
                    });
                    $("#room_amt_info").html(room_info_data);
                    // Supplementary end


                    // package A + B
                    $.each(a_and_b, function(key, value) {
                        package_a_b += `<div class="d-flex justify-content-between">
                                            <p class="gray font-size-12"> ${value.traveler}
                                            </p>
                                            <h6 class="font-size-14">${formatter.format(value.cost)}
                                            </h6>
                                        </div>`;
                    });
                    $("#package_A_B").html(package_a_b);
                    // package A + B end

                    // package C
                    $.each(package_c_data, function(key, value) {
                        package_c += `<div class="d-flex justify-content-between">
                                            <p class="gray font-size-12"> ${value.traveler}
                                            </p>
                                            <h6 class="font-size-14">${formatter.format(value.cost)}
                                            </h6>
                                        </div>`;
                    });
                    $("#package_C").html(package_c);
                    // package C end

                    // tax
                    $.each(taxes.gst, function(key, value) {
                        total_trip_cost += parseInt(value.gst);
                        tax_list += `<div class="d-flex justify-content-between mt-2">
                                        <div>
                                            <span class="fw-bold text  font-size-14">GST ${value.gst_per}%</span><br>
                                            <span class="gray font-size-12"> ${value.traveler}</span>
                                        </div>
                                        <h6 class="font-size-14">+${formatter.format(value.gst)}</h6>
                                    </div>`;
                    });
                    $.each(taxes.tcs, function(key, value) {
                        if (value.tcs_per == 2 || value.tcs_per == 0) {
                            var taxComp = "TDS";
                            var taxSign = "-";
                            total_trip_cost -= parseInt(value.tcs);
                        } else {
                            var taxComp = "TCS";
                            var taxSign = "+";
                            total_trip_cost += parseInt(value.tcs);
                        }
                        tax_list += `<div class="d-flex justify-content-between mt-2">
                                        <div>
                                            <span class="fw-bold text  font-size-14">${taxComp} ${value.tcs_per}%</span>
                                            <span data-bs-toggle="tooltip" data-bs-placement="right" title="${value.tooltip}">
                                                <img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg"
                                                                alt="" class="ps-2"></span>
                                            <br>
                                            <span class="gray font-size-12"> ${value.traveler}</span>
                                        </div>
                                        <h6 class="font-size-14">${taxSign} ${formatter.format(value.tcs)}</h6>
                                    </div>`;
                    });
                    $("#tax_info").html(tax_list);
                    // tax

                    // extra_service_list
                    $.each(extra_services, function(key, value) {
                        total_trip_cost += parseInt(value.extra_charges);
                      
                        extra_service_list += `<div class="d-flex justify-content-between">
                                                    <p class="gray font-size-12"> ${value.services} for ${value.traveler_name}<span
                                                            data-bs-toggle="tooltip" data-bs-placement="right"
                                                            title="Net Cost = ${formatter.format(value.amount)}, Markup = ${formatter.format(value.markup)}, Tax = ${value.tax}%, Comment = ${value.comment}">
                                                            <img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg"
                                                                alt="" class="ps-2">
                                                        </span></p>
                                                    <h6 class="font-size-14">+${formatter.format(value.extra_charges)}</h6>
                                                </div>`;
                    });
                    $("#extra_service_data").html(extra_service_list);

                    // vehicle security
                    if (parseInt(vsa.amount) > 0) {
                        total_trip_cost += parseInt(vsa.amount);
                   
                        vs += `<div class="">
                                <div class="d-flex justify-content-between">
                                    <span class="text font-size-14">Vehicle Security Amount <span data-bs-toggle="tooltip"
                                        data-bs-placement="right"
                                        title="${vsa.comment}">
                                        <img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg"
                                            alt="" class="ps-2">
                                    </span></span></span>

                                    <h6 class="font-size-14">${formatter.format(vsa.amount)}</h6>
                                </div>
                            </div>`;
                    }
                    $("#vehicle_sec_amt_info").html(vs);
                    // extra_service_list

                    // Carbon Info
                    $.each(carbon_infos, function(key, carbon_info) {
                        if (parseInt(carbon_info.amount) > 0) {
                            total_trip_cost += parseInt(carbon_info.amount);
                            carbon_infos_data += `<div class="">
                                    <div class="d-flex justify-content-between">
                                        <span class="text font-size-14">${carbon_info.customer_name}</span>

                                        <h6 class="font-size-14">${formatter.format(carbon_info.amount)}</h6>
                                    </div>
                                </div>`;
                        }
                    });
                    $("#carbon_info").html(carbon_infos_data);
                    // Carbon Info end

                    // extra service redeemable
                    $.each(extra_services_redeemable, function(key, value) {
                        extra_service_list_redeem += `<div class="d-flex justify-content-between">
                                                    <p class="gray font-size-12"> ${value.services} for ${value.traveler_name} (ex taxes)</p>
                                                    <h6 class="font-size-14">${formatter.format(value.extra_charges)}</h6>
                                                </div>`;
                    });
                    $("#extra_services_redeemable").html(extra_service_list_redeem);
                    // extra service redeemable

                    // payment
                    if (payment.payment) {
                        total_rcvd_amt += parseInt(payment.payment);
                        final_paid += `<div class="d-flex justify-content-between" style="width:93%">
                                        <div>
                                            <span class="fw-bold text font-size-14">Advance Payment
                                                <span data-bs-toggle="tooltip" data-bs-placement="right"
                                                    title="First Advance payment of Total payable amount">
                                                    <img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg"
                                                        alt="" class="ps-2">
                                                </span>
                                            </span><br>
                                            <span class="gray font-size-12">Received on ${payment.date}</span>
                                        </div>
                                        <div class=" align-items-center" style="width:36%">
                                            <h6 class="font-size-14 mb-0">${formatter.format(payment.payment)}</h6>
                                        </div>
                                    </div>`;

                        $.each(partPayment, function(key, value) {
                            total_rcvd_amt += parseInt(value.amount);
                            final_paid += `<div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="fw-bold text font-size-14">
                                                    Payment ${key + 1}
                                                    <span data-bs-toggle="tooltip" data-bs-placement="right"
                                                        title="Remarks: ${value.remark ?? 'NA'}, Comment: ${value.comment ?? 'NA'}">
                                                        <img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg" 
                                                            alt="" class="ps-2">
                                                    </span>
                                                </span><br>
                                                <span class="gray font-size-12">Received on ${value.date}</span>
                                            </div>

                                            <!-- Amount & Edit Icon -->
                                           <div class="d-flex justify-content-between align-items-center" style="width: 40%;">
                                <!-- Amount -->
                                <h6 class="font-size-14 mb-0" style="min-width: 80px;">${formatter.format(value.amount)}</h6>

                                <!-- Edit Icon -->
                                <a href="javascript:void(0)" onclick="editPayment(${key})" class="text-decoration-none d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 72 72" fill="orange">
                                        <path d="M57.828,22.266c1.562,1.562,1.562,4.095,0,5.657L32.108,53.644L16.52,58.857c-2.088,0.698-4.076-1.29-3.378-3.378
                                            l5.213-15.587l25.721-25.721c1.562-1.562,4.095-1.562,5.657,0L57.828,22.266z M42.905,25.243L24.471,43.676l-1.703,5.092
                                            l0.463,0.463l5.092-1.703l18.434-18.434L42.905,25.243z"></path>
                                    </svg>
                                </a>
                            </div>

                        </div>`;

                            // Insert the form into the modal
                        
                        });

                        $("#total_rec_amt_inp").val(total_rcvd_amt);
                        $("#total_rec_amt").text(formatter.format(total_rcvd_amt));
                        $("#payment_details_list").html(final_paid);
                     

                    }
                    // payment

                    // credit Note Amounts
                    var totalCreditAmt = 0;
                    $.each(credit_note_amounts, function(key, value) {
                        totalCreditAmt += value.credit_note;
                        credit_note_list += `<div class="d-flex justify-content-between">
                                            <p class="gray font-size-12"> ${value.traveler}
                                            </p>
                                            <h6 class="font-size-14">${formatter.format(value.credit_note)}
                                            </h6>
                                        </div>`;
                    });
                    $("#credit_note_amt_list").html(credit_note_list);
                    // credit Note Amounts end

                    // loyalty Total
                    $.each(actual_trip_cost, function(key, value) {
                        loyalty_total_data += `<div class="d-flex justify-content-between">
                                            <p class="gray font-size-12"> ${value.traveler}
                                            </p>
                                            <h6 class="font-size-14">${formatter.format(value.cost)}
                                            </h6>
                                        </div>`;
                    });
                    $("#total_calc_loyalty").html(loyalty_total_data);
                    // loyalty Total end

                    // total amount
                    $("#total_trip_cost_amt").text(formatter.format(total_trip_cost))
                 
                    // total
                    all_payable_amt = parseInt(total_trip_cost) - parseInt(total_redeem_amt);
                    var total_payable_amt = parseInt(all_payable_amt) - parseInt(totalCreditAmt);
                    $("#total_payable_amt").text(formatter.format(parseInt(all_payable_amt)));

                    $("#final_total_payable_amt").text(formatter.format(parseInt(total_payable_amt)));

                    $("#payable_amt_to_saved").val(parseInt(total_payable_amt));
                    // points

                    var pointData = "";
                    $.each(earnedpoints, function(keys, data) {
                        $.each(data, function(key, value) {
                            totalPoints += parseInt(value['points']);
                            pointData += `<div class="d-flex justify-content-between mt-2 id="allpoints">
                                            <div>
                                                <span class="fw text font-size-14"> ${value['name']}</span><br>
                                                <span class="gray font-size-12">${value['reward']}% of total</span><br>
                                                
                                            </div>
                                            <h6 class="font-size-14" id="">${value['points']}</h6>
                                        </div>`;
                        });
                    });

                    $("#totalSumpoints").text(totalPoints);
                    $("#points-grp").html(pointData);

                    // trip price
                    $trip_trip_price=`<div class="d-flex justify-content-between">
                                                    <h6 class="font-size-14">${formatter.format(real_trip_amt)}
                                                    </h6>
                                                </div>`;
                    $("#trip_price").html($trip_trip_price);
                    $(".price-amt").html(real_trip_amt);



                    // pending amount
                    $("#total_pending_amt_inp").val(total_payable_amt - payment.payment ?? 0);
                    // $("#total_pending_amt_inp").val(total_payable_amt - total_rcvd_amt);
                    $("#total_pending_amt").text(formatter.format(total_payable_amt - total_rcvd_amt));

                    // tooltip init here
                    $(function() {
                        $('[data-bs-toggle="tooltip"]').tooltip();
                    });
                    // tooltip init here
                }
            }
        });
    }
</script>

{{-- 10 digits phone validation --}}
<script>
    function validateNumberInput(event) {
        const input = event.target;
        input.value = input.value.replace(/\D/g, ''); // Remove all non-digit characters
        if (input.value.length > 10) {
            input.value = input.value.slice(0, 10); // Limit to 10 digits
        }
    }
</script>

{{-- style given for do not scroll pagination and search bar --}}
<script>
    // window.onload = function() {
    //     const tables = document.querySelectorAll('table');
    //     tables.forEach(table => {
    //         const parentDiv = table.parentElement;
    //         if (parentDiv && parentDiv.tagName.toLowerCase() === 'div') {
    //             parentDiv.style.overflowX = 'auto';
    //         }
    //     });
    // };
</script>
