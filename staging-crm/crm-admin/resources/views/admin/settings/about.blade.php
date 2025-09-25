@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Setting/</span> Overview</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Overview Setting</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setting.about-update') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-floating form-floating-outline mb-4">
                                <textarea name="about_us" id="about_us" class="form-control" placeholder="Hi, Do you have a moment to talk Joe?"
                                    style="height: 60px">{{ $data->about_us }}</textarea>
                                <label for="about_us">Overview</label>
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
