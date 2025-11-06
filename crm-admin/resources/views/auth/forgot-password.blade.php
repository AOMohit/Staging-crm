<!DOCTYPE html>
<html lang="en" class="@if (setting('site_theme') == 'Dark') dark-style @else light-style @endif customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="{{ url('public/admin') }}/assets/" data-template="vertical-menu-template">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>{{ setting('site_name') }} - Login</title>
    <meta name="description" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ url('storage/app/' . setting('logo')) }}" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <!-- Icons -->
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/fonts/materialdesignicons.css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/fonts/fontawesome.css" />
    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/node-waves/node-waves.css" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/css/demo.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/typeahead-js/typeahead.css" />
    <!-- Vendor -->
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="{{ url('public/admin') }}/assets/vendor/js/helpers.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/js/template-customizer.js"></script>
    <script src="{{ url('public/admin') }}/assets/js/config.js"></script>
</head>

<body>
    <div class="authentication-wrapper authentication-cover">
        <a href="index.html" class="auth-cover-brand d-flex align-items-center gap-2">
            <span class="app-brand-logo demo">
                <span style="color: var(--bs-primary)">
                    <img style="height: 70px;" src="{{ url('storage/app/' . setting('logo')) }}" alt="">
                </span>
            </span>
        </a>
        <div class="authentication-inner row m-0">
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center justify-content-center p-5 pb-2">
                <img src="{{ url('public/admin') }}/assets/img/illustrations/auth-login-illustration-light.png"
                    class="auth-cover-illustration w-100" alt="auth-illustration"
                    data-app-light-img="illustrations/auth-login-illustration-light.png"
                    data-app-dark-img="illustrations/auth-login-illustration-dark.png" />
                <img src="{{ url('public/admin') }}/assets/img/illustrations/auth-cover-login-mask-light.png"
                    class="authentication-image" alt="mask"
                    data-app-light-img="illustrations/auth-cover-login-mask-light.png"
                    data-app-dark-img="illustrations/auth-cover-login-mask-dark.png" />
            </div>
            <!-- Login -->
            <div
                class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg position-relative py-sm-5 px-4 py-4">
                <div class="w-px-400 mx-auto pt-5 pt-lg-0">
                    <h4 class="mb-2 fw-semibold">Welcome to {{ setting('site_name') }}! 👋</h4>
                    <p class="mb-4">Please Enter your email</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-floating form-floating-outline mb-3">
                            <input
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Enter your email"
                                required
                                autofocus
                            />
                            <label for="email">Email</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary d-grid w-100">
                            {{ __('Email Password Reset Link') }}
                        </button>
                    </form>

                    {{-- <p class="text-center mt-2">
                        <span>New on our platform?</span>
                        <a href="auth-register-cover.html">
                            <span>Create an account</span>
                        </a>
                    </p> 
                    <div class="divider my-4">
                        <div class="divider-text">or</div>
                    </div>

                    <div class="d-flex justify-content-center gap-2">
                        <a href="javascript:;" class="btn btn-icon btn-lg rounded-pill btn-text-facebook">
                            <i class="tf-icons mdi mdi-24px mdi-facebook"></i>
                        </a>

                        <a href="javascript:;" class="btn btn-icon btn-lg rounded-pill btn-text-twitter">
                            <i class="tf-icons mdi mdi-24px mdi-twitter"></i>
                        </a>

                        <a href="javascript:;" class="btn btn-icon btn-lg rounded-pill btn-text-github">
                            <i class="tf-icons mdi mdi-24px mdi-github"></i>
                        </a>

                        <a href="javascript:;" class="btn btn-icon btn-lg rounded-pill btn-text-google-plus">
                            <i class="tf-icons mdi mdi-24px mdi-google"></i>
                        </a>
                    </div> --}}
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>

    <!-- / Content -->

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
    <script src="{{ url('public/admin') }}/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>

    <!-- Main JS -->
    <script src="{{ url('public/admin') }}/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="{{ url('public/admin') }}/assets/js/pages-auth.js"></script>
</body>

</html>
