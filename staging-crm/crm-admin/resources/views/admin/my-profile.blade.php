@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Setting/</span> My Profile</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">My Profile</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile-update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-floating form-floating-outline mb-4">
                                <input required type="text" value="{{ auth()->user()->name }}" name="name"
                                    class="form-control" id="basic-default-fullname" placeholder="Name" />
                                <label for="basic-default-fullname">Full Name</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="file" name="image" class="form-control" id="basic-default-fullname" />
                                <label for="basic-default-fullname">Profile Image</label>
                            </div>
                            <img height="150px" src="{{ url('storage/app/' . auth()->user()->image) }}" alt="">

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
