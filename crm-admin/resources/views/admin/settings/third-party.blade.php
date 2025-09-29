@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Setting/</span> Third Party</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Third Party Setting</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setting.third-party-update') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="form-floating form-floating-outline mb-4">
                                <select id="multicol-country" class="select2 form-select" data-allow-clear="true"
                                    name="mail_status">
                                    <option @if ($data->mail_status == '1') selected @endif value="1">Active</option>
                                    <option @if ($data->mail_status == '0') selected @endif value="0">InActive
                                    </option>
                                </select>
                                <label for="multicol-country">Mail Status</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <select id="multicol-country2" class="select2 form-select" data-allow-clear="true"
                                    name="whatsapp_status">
                                    <option @if ($data->whatsapp_status == '1') selected @endif value="1">Active</option>
                                    <option @if ($data->whatsapp_status == '0') selected @endif value="0">InActive
                                    </option>
                                </select>
                                <label for="multicol-country2">Whatsapp Status</label>
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
