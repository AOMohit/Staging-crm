@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Setting/</span> Site</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Site Setting</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setting.site-update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="site_name" value="{{ $data->site_name }}" class="form-control"
                                    id="basic-default-fullname" placeholder="Site Name" />
                                <label for="basic-default-fullname">Site Name</label>
                            </div>
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="file" class="form-control" name="logo" id="basic-default-company"
                                    placeholder="logo" />
                                <label for="basic-default-company">Logo</label>
                            </div>
                            <img class="mb-3" style="height: 100px;" src="{{ url('storage/app/' . $data->logo) }}"
                                alt="">

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="copyright" value="{{ $data->copyright }}" class="form-control"
                                    id="basic-default-fullname1" placeholder="Copyright" />
                                <label for="basic-default-fullname1">Copyright</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <select id="multicol-country" class="select2 form-select" data-allow-clear="true"
                                    name="site_theme">
                                    <option @if ($data->site_theme == 'Light') selected @endif value="Light">Light</option>
                                    <option @if ($data->site_theme == 'Dark') selected @endif value="Dark">Dark</option>
                                </select>
                                <label for="multicol-country">Theme</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="terms_link" value="{{ $data->terms_link }}" class="form-control"
                                    id="basic-default-fullname1" placeholder="Terms & Conditions Link" />
                                <label for="basic-default-fullname1">Terms & Conditions Link</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="faq_link" value="{{ $data->faq_link }}" class="form-control"
                                    id="basic-default-fullname1" placeholder="Faq Page Link" />
                                <label for="basic-default-fullname1">Faq Page Link</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="merchandise_email" value="{{ $data->merchandise_email }}"
                                    class="form-control" id="basic-default-fullname1" placeholder="Merchandise Email" />
                                <label for="basic-default-fullname1">Merchandise Email</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="stationary_email" value="{{ $data->stationary_email }}"
                                    class="form-control" id="basic-default-fullname1" placeholder="Stationary Email" />
                                <label for="basic-default-fullname1">Stationary Email</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="admin_mail" value="{{ $data->admin_mail }}" class="form-control"
                                    id="basic-default-fullname1" placeholder="Admin Email" />
                                <label for="basic-default-fullname1">Admin Email</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="account_mail" value="{{ $data->account_mail }}"
                                    class="form-control" id="basic-default-fullname1" placeholder="Account Email" />
                                <label for="basic-default-fullname1">Account Email</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="operation_mail" value="{{ $data->operation_mail }}"
                                    class="form-control" id="basic-default-fullname1" placeholder="Operation Email" />
                                <label for="basic-default-fullname1">Operation Email</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="sales_email" value="{{ $data->sales_email }}"
                                    class="form-control" id="basic-default-fullname1" placeholder="Sales Email" />
                                <label for="basic-default-fullname1">Sales Email</label>
                            </div>
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="birthday_email" value="{{ $data->birthday_email }}"
                                    class="form-control" id="basic-default-fullname1" placeholder="Birthday Email" />
                                <label for="basic-default-fullname1">Birthday Email</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="trip_ongoing_report"
                                    value="{{ $data->trip_ongoing_report }}" class="form-control"
                                    id="basic-default-fullname1" placeholder="Email" />
                                <label for="basic-default-fullname1">Ongoing Trip Report User Mail</label>
                                <small>Eg:- abc@mail.com,def@mail.com</small>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
