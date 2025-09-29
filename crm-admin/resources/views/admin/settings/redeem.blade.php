@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Setting/</span>Redeem Points</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Redeem Points </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setting.redeem-update') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" name="redeem_points_title"
                                    value="{{ $data->redeem_points_title }}">
                                <label for="discovery">Title</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <textarea name="redeem_points" id="redeem_points" class="form-control" style="height: 60px">{{ $data->redeem_points }}</textarea>
                                <label for="Redeem Points Description">Redeem Points Description</label>
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
