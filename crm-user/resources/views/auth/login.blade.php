<!DOCTYPE html>
<html lang="en">

<head>
    <title>CRM - Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/userpanel/login') }}/css/util.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/userpanel/login') }}/css/main.css">
    <meta name="robots" content="noindex, follow">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />

    <style>
        .windowPopup___hXPQC {
            height: 56px;
            width: 56px;
            border-radius: 7px 0px 0px 7px;
            overflow: hidden;
            transition: height 0.5s, width 0.5s;
            float: right;
            display: flex;
            flex-direction: column;
            margin-top: 6px;
            box-shadow: rgba(0, 0, 0, 0.25) 0px 1px 1px, rgba(0, 0, 0, 0.25) 0px 1.5px 6px, rgba(255, 255, 255, 0.1) 0px 0px 0px 1px inset;
            font-family: Helvetica, Arial, sans-serif;
            border: 0;
        }

        .windowPopup___hXPQC.expanded___0nU-K {
            width: 350px;
            height: 250px;
        }

        .windowPopup___hXPQC.expanded___0nU-K .titleBar___rb1QT {
            cursor: default;
        }

        .windowPopup___hXPQC .titleBar___rb1QT {
            background-image: linear-gradient(45deg, #f4496f 0%, #ff4bcf 100%);
            height: 56px;
            width: 100%;
            display: flex;
            align-items: center;
            overflow: hidden;
            padding: 8px 4px 8px 8px;
            flex-shrink: 0;
            cursor: pointer;
        }

        .windowPopup___hXPQC .titleBar___rb1QT .shopifyIcon___9P37F {
            height: 40px;
            width: 40px;
            -webkit-user-drag: none;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .windowPopup___hXPQC .titleBar___rb1QT .titleBarText___k69VM {
            color: white;
            font-size: 21px;
            font-weight: lighter;
            white-space: nowrap;
            flex-grow: 1;
            user-select: none;
        }

        .windowPopup___hXPQC .titleBar___rb1QT .closeButton___YHs1q {
            color: white;
        }

        .windowPopup___hXPQC .titleBar___rb1QT .closeButton___YHs1q:hover {
            background-color: rgba(0, 0, 0, 0.12);
        }

        .windowPopup___hXPQC .popupContent___8Zm76 {
            background-color: white;
            flex-grow: 1;
            padding: 12px;
            display: flex;
            flex-direction: column;
        }

        .windowPopup___hXPQC .popupContent___8Zm76 .shopInfo___PRZ3p {
            flex-grow: 1;
            font-size: 18px;
        }

        .windowPopup___hXPQC .popupContent___8Zm76 .shopInfo___PRZ3p>* {
            min-width: 326px;
        }

        .windowPopup___hXPQC .popupContent___8Zm76 .shopInfo___PRZ3p .popupContentText___rSaSv {
            margin-bottom: 12px;
        }

        .windowPopup___hXPQC .popupContent___8Zm76 .shopInfo___PRZ3p .randomShopInfo___nHBKK {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .windowPopup___hXPQC .popupContent___8Zm76 .checkOutMore___QfkE4 {
            min-width: 326px;
            display: flex;
            align-items: center;
        }

        .windowPopup___hXPQC .popupContent___8Zm76 .checkOutMore___QfkE4 .checkOutMoreImage___LqSw5 {
            width: 150px;
            margin-right: 8px;
        }

        .windowPopup___hXPQC .popupContent___8Zm76 .checkOutMore___QfkE4 .checkOutMoreText___p-Mtf {
            font-size: 14px;
        }

        .windowPopup___hXPQC .popupContent___8Zm76 .blockOptions___Lxhtg {
            flex-shrink: 0;
            display: flex;
            justify-content: center;
            margin-top: 12px;
        }

        .windowPopup___hXPQC .popupContent___8Zm76 .blockOptions___Lxhtg .optionsButton___vRN2w {
            padding-top: 4px;
            color: #b3b3b3;
            text-decoration: underline;
            cursor: pointer;
            width: fit-content;
        }

        .windowPopupIframe___4Hb8g {
            width: 0px;
            height: 0px;
            z-index: 2147483647 !important;
            position: fixed !important;
            top: 285px !important;
            right: 0px !important;
            border: 0 !important;
        }

        </style><style>div[value="null"] {
            display: none;
        }
    </style>
</head>

<body data-new-gr-c-s-check-loaded="14.1155.0" data-gr-ext-installed="">
    <div class="limiter">
        <div class="container-login100"
            style="background-image: url('{{ asset('public/userpanel/login') }}/images/bg-01.jpg');">
            <div class="wrap-login100">
                <form class="login100-form validate-form" action="{{ route('login') }}" method="post">
                    @csrf
                    <span class="login100-form-logo">
                        <img src="{{ asset('public/userpanel/login') }}/images/logo-AO.png" alt=""
                            style="width: 100px;">
                    </span>
                    <span class="login100-form-title p-b-34 p-t-27">
                        Adventures Overland Loyalty Club Program
                    </span>
                    <div class="wrap-input100 validate-input" data-validate="Enter Email">
                        <input class="input100" type="text" name="email" placeholder="Email">
                        <span class="focus-input100" data-placeholder=""></span>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <input class="input100" type="password" name="password" placeholder="Password">
                        <span class="focus-input100" data-placeholder=""></span>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                    </div>
                    <div class="contact100-form-checkbox">
                        <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
                        <label class="label-checkbox100" for="ckb1">
                            Remember me
                        </label>
                    </div>
                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn">
                            Login
                        </button>
                    </div>
                    <div class="text-center p-t-90">
                        <a class="txt1" href="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="dropDownSelect1"></div>

    <script src="{{ asset('public/userpanel/login') }}/js/jquery-3.2.1.min.js"></script>
    {{-- <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/main.js"></script> --}}

    <script src="{{ asset('public/userpanel/login') }}/js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script>
        @if (Session::has('message'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.success("{{ session('message') }}");
        @endif

        @if (Session::has('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.error("{{ session('error') }}");
        @endif

        @if (Session::has('info'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.info("{{ session('info') }}");
        @endif

        @if (Session::has('warning'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>


</body>


</html>
<!-- Session Status -->
