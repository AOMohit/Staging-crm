@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="{{ route('trip.index') }}">Trip</a>/</span>
            Edit</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Trip</h5>
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
                        <form action="{{ route('trip.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <div class="row col-12">
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="trip_type" class="form-control">
                                            <option value="">Select Trip Type</option>
                                            <option @if ($data->trip_type == 'Fixed Departure') selected @endif
                                                value="Fixed Departure">
                                                Fixed Departure</option>
                                            <option @if ($data->trip_type == 'Tailor Made') selected @endif value="Tailor Made">
                                                Tailor Made</option>
                                            <option @if ($data->trip_type == 'Self Drive Tailormade') selected @endif
                                                value="Self Drive Tailormade">Self Drive Tailormade</option>
                                        </select>
                                        <label for="basic-default-fullname">Trip Type <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="region_type" class="form-control">
                                            <option value="">Select Region Type</option>
                                            <option @if ($data->region_type == 'Domestic') selected @endif value="Domestic">
                                                Domestic</option>
                                            <option @if ($data->region_type == 'International') selected @endif value="International">
                                                International</option>
                                        </select>
                                        <label for="basic-default-fullname">Region Type <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" value="{{ $data->name }}" name="name"
                                            class="form-control" id="basic-default-fullname" placeholder="Trip Name" />
                                        <label for="basic-default-fullname">Trip Name <span
                                                class="text-danger">*</span></label>
                                                <small class="text-muted" style="font-size: 70%;">
                                           Trip Name Format: tripName_12Aug2025.
                                        </small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="date" value="{{ $data->start_date }}" name="start_date"
                                            class="form-control" id="basic-default-fullname" placeholder="Start Date" />
                                        <label for="basic-default-fullname">Start Date <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="date" value="{{ $data->end_date }}" name="end_date"
                                            class="form-control" id="basic-default-fullname" placeholder="End Date" />
                                        <label for="basic-default-fullname">End Date <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="number" name="price" value="{{ $data->price }}"
                                            class="form-control" id="basic-default-fullname" placeholder="Cost " />
                                        <label for="basic-default-fullname">Cost <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="number" value="{{ $data->duration_nights }}" name="duration_nights"
                                            class="form-control" id="basic-default-fullname"
                                            placeholder="Duration Nights " />
                                        <label for="basic-default-fullname">Duration Nights <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div> --}}

                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" value="{{ $data->continent }}" name="continent"
                                            class="form-control" id="basic-default-fullname" placeholder="Continent " />
                                        <label for="basic-default-fullname">Continent <span
                                                class="text-danger"></span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" value="{{ $data->landscape }}" name="landscape"
                                            class="form-control" id="basic-default-fullname" placeholder="Landscape" />
                                        <label for="basic-default-fullname">Landscape <span
                                                class="text-danger"></span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" value="{{ $data->style }}" name="style"
                                            class="form-control" id="basic-default-fullname" placeholder="Style " />
                                        <label for="basic-default-fullname">Style <span
                                                class="text-danger"></span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" value="{{ $data->activity }}" name="activity"
                                            class="form-control" id="basic-default-fullname" placeholder="Activity" />
                                        <label for="basic-default-fullname">Activity <span
                                                class="text-danger"></span></label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-floating form-floating-outline">
                                        <select name="merchandise[]" id=""
                                            class="select2 form-select form-select-lg" data-allow-clear="true" multiple>
                                            @php
                                                $selected = ' ';
                                            @endphp
                                            @foreach ($merchandises as $merchandise)
                                                @if (isset($data) && isset($data->merchandise_id) && $data->merchandise_id != 'null')
                                                    @if (in_array($merchandise->id, json_decode($data->merchandise_id)))
                                                        $selected = 'selected';
                                                    @endif
                                                @endif
                                                <option value="{{ $merchandise->id }}" {{ $selected }}>
                                                    {{ $merchandise->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="">Merchandise</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-floating form-floating-outline">
                                        <select name="stationary[]" id=""
                                            class="select2 form-select form-select-lg" data-allow-clear="true" multiple>
                                            @foreach ($stationarys as $stationary)
                                                <option @if (isset($data) &&
                                                        isset($data->stationary_id) &&
                                                        $data->stationary_id != 'null' &&
                                                        in_array($stationary->id, json_decode($data->stationary_id))) selected @endif
                                                    value="{{ $stationary->id }}">
                                                    {{ $stationary->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="">Stationary</label>
                                    </div>
                                </div>
                                {{-------radio button---}}
                                <div class="row mb-3 mt-3">
                                    <div class="col-md-6">
                                        <div class="form-check mb-4">
                                           <input class="form-check-input" type="radio" name="drive_tour_type" id="radio1" value="Self Drive Road Trip"
                                            @if($data->drive_tour_type == 'Self Drive Road Trip') checked @endif>
                                          <label class="form-check-label" for="radio1">Self Drive Road Trip</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-4">
                                          <input class="form-check-input" type="radio" name="drive_tour_type" id="radio2" value="Fly & Drive Road Trip"
                                            @if($data->drive_tour_type == 'Fly & Drive Road Trip') checked @endif>
                                          <label class="form-check-label" for="radio2">Fly & Drive Road Trip</label>
                                        </div>
                                    </div>
                                     @error('drive_tour_type')
                                            <span class="text-danger">{{ $message }}</span> 
                                        @enderror
                                </div>
                                {{----radio button----}}

                                {{-- Relation Manager --}}
                                <div class="col-md-12 mb-4">
                                    <div class="form-floating form-floating-outline">
                                       <select name="relationManager[]" class="select2 form-select form-select-lg" data-allow-clear="true" multiple>
                                            @php
                                                $selectedManagers = [];
                                                if (isset($data) && isset($data->relation_manager_id) && $data->relation_manager_id != 'null') {
                                                    $decoded = json_decode($data->relation_manager_id, true);
                                                    if (is_array($decoded)) {
                                                        $selectedManagers = $decoded;
                                                    } elseif (!empty($decoded)) {
                                                        $selectedManagers = [$decoded];
                                                    }
                                                }
                                            @endphp
                                            @foreach ($relationManagers as $relationManager)
                                                <option value="{{ $relationManager->id }}" @if (in_array($relationManager->id, $selectedManagers)) selected @endif>
                                                    {{ $relationManager->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="">Relation Manager</label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <textarea style="min-height:100px;" name="overview" class="form-control">{{ $data->overview }}</textarea>
                                        <label for="basic-default-fullname">Overview</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline mb-4 ">
                                        <input type="file" name="image" class="form-control"
                                            id="basic-default-fullname" placeholder="Thumbnail" />
                                        <label for="basic-default-fullname">Thumbnail <span
                                                class="text-danger"></span></label>
                                    </div>
                                    <img height="100px" src="{{ url('storage/app/' . $data->image) }}" alt="">
                                </div>

                                <div class="col-md-6 mt-4">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="status" class="form-control">
                                            <option value="">Status</option>
                                            <option @if ($data->status == 'Approved') selected @endif value="Approved">
                                                Approved</option>
                                            <option @if ($data->status == 'Sold Out') selected @endif value="Sold Out">
                                                Sold Out</option>
                                        </select>
                                        <label for="basic-default-fullname">Trip Status <span
                                                class="text-danger">*</span></label>
                                    </div>
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
