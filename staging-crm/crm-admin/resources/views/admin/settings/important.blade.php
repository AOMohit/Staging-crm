@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Setting/</span> Important Notes</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"> Important Notes </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setting.important-update') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-floating form-floating-outline mb-4">
                                <textarea name="important_notes" id="important_notes" class="form-control" style="height: 60px">{{ $data->important_notes }}</textarea>
                                <label for="important_notes"> Important Notes</label>
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
