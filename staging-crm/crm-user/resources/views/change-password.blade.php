@extends('layouts.userlayout.index')
@section('container')
    <div class="col-md-10 col-12">
        <div class="border-shadow">
            <div class="card">
                <div class="card-header bg-white information">
                    @include('layouts.userlayout.card-header')
                </div>
                <div class="card-body">
                    <h6 class="text fw-bold mb-5 mt-3">Change Password</h6>

                    <div class="card border-shadow pb-4 ">

                        <div class="container mt-3">
                            <div class="row">
                                @if (auth()->user()->is_password_changed == 0)
                                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"
                                            viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                            <path
                                                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                        </svg>
                                        <div>
                                            Kindly Change The Password to Access The Dashboard.
                                        </div>
                                    </div>
                                @endif

                                <div class="col-12 col-md-4 redeem-form mx-auto d-block py-5">
                                    <div class="text-center">

                                        <span class="text">Update your password for</span>
                                        <h6 class="text fw-bold">{{ Auth::user()->email }}</h6>
                                    </div>
                                    <form action="{{ route('password.update') }}" method="POST" class="mt-5">
                                        @csrf
                                        @method('put')

                                       <div class="mt-2 position-relative">
                                            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                                            <x-text-input id="update_password_current_password" name="current_password"
                                                placeholder="*********" type="password" class="mt-1 block form-control pr-5"
                                                autocomplete="current-password" />
                                            <span class="position-absolute" style="top: 38px; right: 15px; cursor:pointer;" onclick="togglePassword('update_password_current_password', this)">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                                        </div>

                                        <div class="mt-2 position-relative">
                                            <x-input-label for="update_password_password" :value="__('New Password')" />
                                            <x-text-input id="update_password_password" name="password" type="password"
                                                placeholder="*********" class="mt-1 block form-control pr-5"
                                                autocomplete="new-password" />
                                            <span class="position-absolute" style="top: 38px; right: 15px; cursor:pointer;" onclick="togglePassword('update_password_password', this)">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                        </div>

                                        <div class="mt-2 position-relative">
                                            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                                            <x-text-input id="update_password_password_confirmation"
                                                name="password_confirmation" type="password" class="mt-1 block form-control pr-5"
                                                placeholder="*********" autocomplete="new-password" />
                                            <span class="position-absolute" style="top: 38px; right: 15px; cursor:pointer;" onclick="togglePassword('update_password_password_confirmation', this)">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                        </div>

                                        {{-- <label for="" class="mt-3">Current Password</label><br>
                                        <input type="text" name="current_password" class="form-control"
                                            placeholder="Enter Current password">
                                        <x-input-error :messages="$errors->get('current_password')" class="mt-2" />

                                        <label for="" class="mt-3">New Password</label><br>
                                        <input type="text" name="password" class="form-control"
                                            placeholder="Enter new password">
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />

                                        <label for="" class="mt-3">Confirm New Password</label><br>
                                        <input type="text" name="confirmed" class="form-control"
                                            placeholder="Confirm new password">
                                        <x-input-error :messages="$errors->get('confirmed')" class="mt-2" /> --}}

                                        <div class="text-center">

                                            <button class="button mt-3 px-4 p-2 ">Update password</button>
                                        </div>
                                    </form>


                                </div>

                            </div>
                        </div>


                    </div>

                </div>
                <!-- important note -->
                <div class="mt-5 important-note px-4">
                    @include('imp')

                </div>


            </div>
        </div>
    </div>
<script>
function togglePassword(fieldId, el) {
    const input = document.getElementById(fieldId);
    const icon = el.querySelector('i');
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection
