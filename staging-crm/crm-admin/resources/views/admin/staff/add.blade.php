@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="{{ route('staff.index') }}">Staff</a>/</span>
            Add</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add Staff</h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('staff.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="name" class="form-control" id="basic-default-fullname"
                                    placeholder=" Name" />
                                <label for="basic-default-fullname"> Name <span class="text-danger">*</span></label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="email" name="email" class="form-control" id="basic-default-fullname"
                                    placeholder="Email Id" />
                                <label for="basic-default-fullname">Email Id <span class="text-danger">*</span></label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <select name="role_id" class="form-control">
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <label for="basic-default-fullname">Role <span class="text-danger">*</span></label>
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
