@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Setting/</span> Change Password</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('password-update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="password" name="password" class="form-control" id="basic-default-fullname"
                                    placeholder="***********" />
                                <label for="basic-default-fullname">Password</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="password" name="confirm_password" class="form-control"
                                    id="basic-default-fullname" placeholder="***********" />
                                <label for="basic-default-fullname">Confirm Password</label>
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
