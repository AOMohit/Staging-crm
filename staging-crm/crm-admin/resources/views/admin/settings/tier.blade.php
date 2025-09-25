@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Setting/</span>Tier Information</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tier Information </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setting.tier-update') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-floating form-floating-outline mb-4">
                                <textarea name="discovery" id="discovery" class="form-control" style="height: 60px">{{ $data->discovery }}</textarea>
                                <label for="discovery">Discovery</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <textarea name="adventurer" id="adventurer" class="form-control" style="height: 60px">{{ $data->adventurer }}</textarea>
                                <label for="adventurer">Adventurer</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <textarea name="explorer" id="explorer" class="form-control" style="height: 60px">{{ $data->explorer }}</textarea>
                                <label for="explorer">Explorer</label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <textarea name="legends" id="legends" class="form-control" style="height: 60px">{{ $data->legends }}</textarea>
                                <label for="legends">Legends</label>
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
