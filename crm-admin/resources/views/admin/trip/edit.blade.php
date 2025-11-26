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
                                           Trip Name Format: tripName_12Aug2025 - 18Aug2025.
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

                                {{-- carbon --}}
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="number" name="tree_no" value="{{ $data->tree_no }}"
                                            class="form-control" id="basic-default-fullname"
                                            placeholder="Number of Trees " />
                                        <label for="basic-default-fullname">Number of Trees <span
                                                class="text-danger"></span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="number" name="donation_amt" value="{{ $data->donation_amt }}"
                                            class="form-control" id="basic-default-fullname"
                                            placeholder="Donation Amount" />
                                        <label for="basic-default-fullname">Donation Amount <span
                                                class="text-danger"></span></label>
                                    </div>
                                </div>
                                {{-- carbon --}}
                                {{-- Relation Manager --}}
                                <div class="col-md-12 mb-4">
                                    <div class="form-floating form-floating-outline">
                                        <select name="relationManager[]" class="select2 form-select form-select-lg" data-allow-clear="true" multiple>
                                            @php
                                                $selectedManagers = [];
                                                if (isset($data) && isset($data->relation_manager_id) && $data->relation_manager_id != 'null') {
                                                    $selectedManagers = json_decode($data->relation_manager_id, true) ?? [];
                                                }
                                            @endphp
                                            @foreach ($relationManagers as $relationManager)
                                                <option value="{{ $relationManager->id }}"
                                                    @if (in_array($relationManager->id, $selectedManagers)) selected @endif>
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
                                    @if($data->image)
                                        <img height="100px" src="{{ url('storage/app/' . $data->image) }}" alt="thumbnail">
                                    @endif
                                </div>

                                {{-- Gallery Images (existing) --}}
                                <div class="col-md-12">
                                    <label class="form-label">Gallery Images</label>
                                    <div class="row">
                                        @forelse($data->images as $img)
                                            <div class="col-md-3 mb-3" id="gallery-card-{{ $img->id }}">
                                                <div class="card">
                                                    <img src="{{ url('storage/app/' . $img->image) }}" class="card-img-top" style="height:150px;object-fit:cover;" alt="gallery">
                                                    <div class="card-body p-2 text-center">
                                                        <button type="button"
                                                                class="btn btn-sm btn-danger w-100 gallery-delete-btn"
                                                                data-id="{{ $img->id }}"
                                                                data-url="{{ route('trip.image.delete', $img->id) }}">
                                                            Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 mb-2">
                                                <small class="text-muted">No gallery images.</small>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- Upload additional gallery images --}}
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline mb-2">
                                        <label class="form-label">Add More Gallery Images</label>
                                        <input type="file" name="images[]" id="gallery-input" class="form-control" multiple accept="image/*" />
                                        <small class="text-muted" style="font-size: 70%;">You can add more images here (max 10 files client-side)</small>
                                        @if ($errors->has('images.*'))
                                            <div class="text-danger mt-1">
                                                @foreach ($errors->get('images.*') as $errGroup)
                                                    @foreach ($errGroup as $err)
                                                        <div>{{ $err }}</div>
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    {{-- preview for newly selected images --}}
                                    <div id="gallery-preview" class="row"></div>
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

        <script>
            // client-side preview for newly selected images
            document.addEventListener('DOMContentLoaded', function () {
                const input = document.getElementById('gallery-input');
                const preview = document.getElementById('gallery-preview');
                const MAX_FILES = 10;
                const MAX_SIZE = 5 * 1024 * 1024; // 5MB

                if(!input) return;
                input.addEventListener('change', function () {
                    preview.innerHTML = '';
                    const files = Array.from(input.files);
                    if (files.length > MAX_FILES) {
                        alert('You can upload a maximum of ' + MAX_FILES + ' images.');
                        input.value = '';
                        return;
                    }

                    files.forEach(file => {
                        if (!file.type.startsWith('image/')) return;
                        if (file.size > MAX_SIZE) {
                            alert('File "' + file.name + '" exceeds the max size of 5MB.');
                            input.value = '';
                            preview.innerHTML = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function (ev) {
                            const col = document.createElement('div');
                            col.className = 'col-md-3 mb-3';
                            const img = document.createElement('img');
                            img.src = ev.target.result;
                            img.style.width = '100%';
                            img.style.height = '150px';
                            img.style.objectFit = 'cover';
                            img.className = 'rounded';
                            col.appendChild(img);
                            preview.appendChild(col);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            });

            document.addEventListener('DOMContentLoaded', function () {
                const token = "{{ csrf_token() }}";
                document.querySelectorAll('.gallery-delete-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        if (!confirm('Delete this image?')) return;

                        const id = this.dataset.id;
                        const url = this.dataset.url;
                        const card = document.getElementById('gallery-card-' + id);

                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin',
                        })
                        .then(resp => resp.json())
                        .then(json => {
                            if (json.success) {
                                if (card) card.remove();
                            } else {
                                alert(json.message || 'Failed to delete image');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Failed to delete image (network/error). Check console.');
                        });
                    });
                });
            });

        </script>
    @endsection
