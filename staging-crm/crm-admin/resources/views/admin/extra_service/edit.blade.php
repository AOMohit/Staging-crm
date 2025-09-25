@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a
                    href="{{ route('setting.extra_service.index') }}">Extra
                    Service</a>/</span> Edit</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Extra Service</h5>
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
                        <form action="{{ route('setting.extra_service.update') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="title" class="form-control" id="basic-default-fullname"
                                    placeholder=" Name" value="{{ $data->title }}" />
                                <label for="basic-default-fullname"> Name <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-floating form-floating-outline mb-4">
                                <select name="is_redeemable" class="form-control">
                                    <option @if ($data->is_redeemable == 0) selected @endif value="0">No</option>
                                    <option @if ($data->is_redeemable == 1) selected @endif value="1">Yes</option>
                                </select>
                                <label for="basic-default-fullname">Is Redeemable<span class="text-danger">*</span></label>
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
