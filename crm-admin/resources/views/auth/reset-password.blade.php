<!DOCTYPE html>
<html lang="en"
    class="@if (setting('site_theme') == 'Dark') dark-style @else light-style @endif customizer-hide"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="{{ url('public/admin') }}/assets/"
    data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>{{ setting('site_name') }} - Reset Password</title>

    <link rel="icon" type="image/x-icon" href="{{ url('storage/app/' . setting('logo')) }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/fonts/materialdesignicons.css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/fonts/fontawesome.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/css/rtl/core.css"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/css/rtl/theme-default.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/css/demo.css" />

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <link rel="stylesheet" href="{{ url('public/admin') }}/assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="{{ url('public/admin') }}/assets/vendor/js/helpers.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/js/template-customizer.js"></script>
    <script src="{{ url('public/admin') }}/assets/js/config.js"></script>
</head>

<body>
    <div class="authentication-wrapper authentication-cover">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="auth-cover-brand d-flex align-items-center gap-2">
            <span class="app-brand-logo demo">
                <img style="height: 70px;" src="{{ url('storage/app/' . setting('logo')) }}" alt="Logo">
            </span>
        </a>
        <!-- /Logo -->

        <div class="authentication-inner row m-0">
            <!-- Left Illustration -->
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center justify-content-center p-5 pb-2">
                <img src="{{ url('public/admin') }}/assets/img/illustrations/auth-login-illustration-light.png"
                    class="auth-cover-illustration w-100"
                    alt="auth-illustration"
                    data-app-light-img="illustrations/auth-login-illustration-light.png"
                    data-app-dark-img="illustrations/auth-login-illustration-dark.png" />
                <img src="{{ url('public/admin') }}/assets/img/illustrations/auth-cover-login-mask-light.png"
                    class="authentication-image"
                    alt="mask"
                    data-app-light-img="illustrations/auth-cover-login-mask-light.png"
                    data-app-dark-img="illustrations/auth-cover-login-mask-dark.png" />
            </div>
            <!-- /Left Section -->

            <!-- Reset Password Form -->
            <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg position-relative py-sm-5 px-4 py-4">
                <div class="w-px-400 mx-auto pt-5 pt-lg-0">
                    <h4 class="mb-2 fw-semibold">Reset your password 🔒</h4>
                    <p class="mb-4">Enter your new password below to reset your account access.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" id="email" name="email" value="{{ old('email') }}" required />

                        <!-- New Password -->
                        <div class="mb-3">
                            <label class="form-label" for="password">New Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter new password" required />
                                <span class="input-group-text cursor-pointer toggle-password">
                                    <i class="mdi mdi-eye-off-outline"></i>
                                </span>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Confirm new password" required />
                                <span class="input-group-text cursor-pointer toggle-password">
                                    <i class="mdi mdi-eye-off-outline"></i>
                                </span>
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary d-grid w-100">
                            {{ __('Reset Password') }}
                        </button>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                <i class="mdi mdi-arrow-left"></i> Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /Reset Password Form -->
        </div>
    </div>

    <!-- Core JS -->
    <script src="{{ url('public/admin') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/js/menu.js"></script>

    <!-- Vendors JS -->
    <script src="{{ url('public/admin') }}/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ url('public/admin') }}/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>

    <!-- Main JS -->
    <script src="{{ url('public/admin') }}/assets/js/main.js"></script>
    <script src="{{ url('public/admin') }}/assets/js/pages-auth.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Decode email from URL
            const params = new URLSearchParams(window.location.search);
            const encodedEmail = params.get('email');
            if (encodedEmail) {
                document.getElementById('email').value = decodeURIComponent(encodedEmail);
            }

            // Toggle password visibility
            document.querySelectorAll(".toggle-password").forEach(icon => {
                icon.addEventListener("click", function () {
                    const input = this.closest(".input-group").querySelector("input");
                    const eyeIcon = this.querySelector("i");

                    if (input.type === "password") {
                        input.type = "text";
                        eyeIcon.classList.remove("mdi-eye-off-outline");
                        eyeIcon.classList.add("mdi-eye-outline");
                    } else {
                        input.type = "password";
                        eyeIcon.classList.remove("mdi-eye-outline");
                        eyeIcon.classList.add("mdi-eye-off-outline");
                    }
                });
            });
        });
    </script>

</body>
</html>
