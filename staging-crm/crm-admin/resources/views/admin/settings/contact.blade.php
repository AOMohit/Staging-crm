@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Setting/</span> Contact Setting</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Contact Setting</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setting.contact-update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="email" name="email" value="{{ $data->email }}" class="form-control"
                                    id="email" placeholder="Email" />
                                <label for="email">Email</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="phone" value="{{ $data->phone }}" class="form-control"
                                    id="phone" placeholder="Phone" />
                                <label for="phone">Phone</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <textarea name="address" id="address" class="form-control" placeholder="Hi, Do you have a moment to talk Joe?"
                                    style="height: 60px">{{ $data->address }}</textarea>
                                <label for="address">Address</label>
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
