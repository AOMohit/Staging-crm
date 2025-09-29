@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Setting/</span> Loyalty Points Terms & Conditions</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Loyalty Points Terms & Conditions</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setting.loyalty-point-terms_conditions-update') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-floating form-floating-outline mb-4">
                                <textarea name="loyalty_points_terms" id="loyalty_points_terms" class="form-control"
                                placeholder="Hi, Do you have a moment to talk Joe?" style="height: 60px">{{$data->loyalty_points_terms}}</textarea>
                                <label for="Loyalty Points Terms & Conditions">Loyalty Points Terms & Conditions</label>
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
