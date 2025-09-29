@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="{{ route('trip.index') }}">Trip</a>/</span>
            Add</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add Trip</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('trip.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row col-12">
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="trip_type" class="form-control">
                                            <option value="">Select Trip Type</option>
                                            <option value="Fixed Departure">Fixed Departure</option>
                                            <option value="Tailor Made">Tailor Made</option>
                                            <option value="Self Drive Tailormade">Self Drive Tailormade</option>
                                        </select>
                                        @error('trip_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <label for="basic-default-fullname">Trip Type <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="region_type" class="form-control">
                                            <option value="">Select Region Type</option>
                                            <option value="Domestic">Domestic</option>
                                            <option value="International">International</option>
                                        </select>
                                           @error('region_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <label for="basic-default-fullname">Region Type <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="name" class="form-control"
                                            id="basic-default-fullname" placeholder="Trip Name" />
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <label for="basic-default-fullname">Trip Name <span
                                                class="text-danger">*</span></label>
                                                <small class="text-muted" style="font-size: 70%;">
                                           Trip Name Format: tripName_12Aug2025 - 18Aug2025.
                                        </small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="date" onchange="checkEndDate(this.value)" name="start_date"
                                            class="form-control" id="basic-default-fullname" placeholder="Start Date" />
                                        @error('start_date')
                                            <span class="text-danger">{{ $message }}</span> 
                                        @enderror
                                        <label for="basic-default-fullname">Start Date <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="date" name="end_date" class="form-control" id="end-date"
                                            placeholder="End Date" />
                                        @error('end_date')  
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <label for="end-date">End Date <span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="number" name="price" class="form-control"
                                            id="basic-default-fullname" placeholder="Cost " />
                                        @error('price')
                                            <span class="text-danger">{{ $message }}</span> 
                                        @enderror
                                        <label for="basic-default-fullname">Cost <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="number" name="duration_nights" class="form-control"
                                            id="basic-default-fullname" placeholder="Duration Nights " />
                                        <label for="basic-default-fullname">Duration Nights <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div> --}}
                                <div class="col-md-6">

                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="continent" class="form-control"
                                            id="basic-default-fullname" placeholder="Continent " />
                                        <label for="basic-default-fullname">Continent <span
                                                class="text-danger"></span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="landscape" class="form-control"
                                            id="basic-default-fullname" placeholder="Landscape" />
                                        <label for="basic-default-fullname">Landscape <span
                                                class="text-danger"></span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="style" class="form-control"
                                            id="basic-default-fullname" placeholder="Style " />
                                        <label for="basic-default-fullname">Style <span class="text-danger"></span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" name="activity" class="form-control"
                                            id="basic-default-fullname" placeholder="Activity" />
                                        <label for="basic-default-fullname">Activity <span
                                                class="text-danger"></span></label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-floating form-floating-outline">
                                        <select name="merchandise[]" id=""
                                            class="select2 form-select form-select-lg" data-allow-clear="true" multiple>
                                            @foreach ($merchandises as $merchandise)
                                                <option value="{{ $merchandise->id }}">
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
                                                <option value="{{ $stationary->id }}">
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
                                            <input class="form-check-input" type="radio" name="drive_tour_type" id="radio1" value="Self Drive Road Trip">
                                            <label class="form-check-label" for="radio1">Self Drive Road Trip</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="radio" name="drive_tour_type" id="radio2" value="Fly & Drive Road Trip">
                                            <label class="form-check-label" for="radio2">Fly & Drive Road Trip</label>
                                        </div>
                                    </div>
                                     @error('drive_tour_type')
                                            <span class="text-danger">{{ $message }}</span> 
                                        @enderror
                                </div>

                                {{----radio button----}}
                   
                               
                                 <div class="col-md-12 mb-4">
                                    <div class="form-floating form-floating-outline">
                                        <select name="relationManager[]" id=""
                                            class="select2 form-select form-select-lg" data-allow-clear="true" multiple>
                                            @foreach ($relationManagers as $relationmanagers)
                                                <option value="{{ $relationmanagers->id }}">
                                                    {{ $relationmanagers->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('relationManager')
                                            <span class="text-danger">{{ $message }}</span> 
                                        @enderror
                                        <label for="">Relation Manager <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <textarea style="min-height:100px;" name="overview" class="form-control"></textarea>
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
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function checkEndDate(value) {
                $("#end-date").attr("min", value);
            }
        </script>
    @endsection
