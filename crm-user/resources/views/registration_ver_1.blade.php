<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Adventure Expedition Booking – Fixed</title>
  <link href="{{ asset('public/userpanel') }}/asset/css/registration.css" rel="stylesheet">
  
</head>
<body>
  <div class="form-container">
    <div class="form-header">
      <h1>Booking Registration Form</h1>
      {{-- <p>Book Your Dream Expedition Today</p> --}}
    </div>

    <div class="step-indicator">
      <div class="step active" id="step-indicator-1">
        <div class="step-circle">1</div><span>Personal Info</span>
      </div>
      <div class="step-line"></div>
      <div class="step" id="step-indicator-2">
        <div class="step-circle">2</div><span>Contact Details</span>
      </div>
      <div class="step-line"></div>
      <div class="step" id="step-indicator-3">
        <div class="step-circle">3</div><span>Documents & Terms</span>
      </div>
    </div>

    {{-- <form id="expeditionForm" class="form-content" novalidate> --}}
        <form action="{{ route('registrationSubmit') }}" method="POST" enctype="multipart/form-data" id="registrationForm" class="form-content" novalidate>
            @csrf
            <input type="hidden" name="form_type" value="{{ $form_type }}">
        
            <!-- STEP 1: PERSONAL INFO -->
            <div class="form-step active" id="step1">
                <h2 class="step-title">Personal Information</h2>
        
                <div class="form-row">
                    @if($data)
                        <div class="form-group">
                            <label for="firstName">First Name <span class="required">*</span></label>
                            <input type="text" id="firstName" name="first_name"
                                value="{{ old('first_name', $data->first_name) }}" required>
                            @error('first_name')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name <span class="required">*</span></label>
                            <input type="text" id="lastName" name="last_name"
                                value="{{ old('last_name', $data->last_name) }}" required>
                            @error('last_name')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    @else
                        <div class="form-group single">
                            <label for="traveller_id">Traveller <span class="required">*</span></label>
                            <select name="traveller_id" id="traveller_id" class="form-control" required>
                                <option value="">—Please choose an option—</option>
                                @foreach($nonRegUsers as $nonRegUser)
                                    <option value="{{ $nonRegUser->id }}" {{ old('traveller_id') == $nonRegUser->id ? 'selected' : '' }}>
                                        {{ $nonRegUser->first_name." ".$nonRegUser->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('traveller_id')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    @endif
                </div>
        
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone"
                            value="{{ old('phone', $data->phone ?? '') }}" required>
                        @error('phone')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email"
                            value="{{ old('email', $data->email ?? '') }}"
                            {{ isset($data) ? 'readonly style=opacity:0.7;pointer-events:none;' : '' }} required>
                        @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
        
                <div class="form-row single">
                    <div class="form-group">
                        <label for="expedition">Choose Your Expedition <span class="required">*</span></label>
                        <select id="expedition" name="letest_trip" required style="pointer-events:none; opacity:0.7;">
                            @foreach ($trip as $trips)
                                <option value="{{ $trips->id }}"
                                    {{ request()->trip_id == $trips->id ? 'selected' : '' }}>
                                    {{ $trips->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        
            <!-- STEP 2: CONTACT & OTHER INFO -->
            <div class="form-step" id="step2">
                <h2 class="step-title">Contact & Other Information</h2>
        
                <div class="form-group">
                    <label for="country">Country <span class="required">*</span></label>
                    <select id="country" name="country" onchange="getState(this.value)" required>
                        <option value="">Select Country</option>
                        @foreach (allCountry() as $contrys)
                            <option value="{{ $contrys->name }}"
                                {{ old('country', $data->country ?? '') == $contrys->name ? 'selected' : '' }}>
                                {{ $contrys->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('country')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
        
                <div class="form-group">
                    <label for="address">Full Address <span class="required">*</span></label>
                    <textarea id="address" name="address" rows="3">{{ old('address', $data->address ?? '') }}</textarea>
                    @error('address')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
        
                <div class="form-row">
                    <div class="form-group">
                        <label for="state">State/Province <span class="required">*</span></label>
                        <input type="hidden" id="selected-State" value="{{ $data->state ?? '' }}">
                        <select id="state" name="state" required>
                            <option value="">Select State</option>
                        </select>
                        @error('state')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="city">City <span class="required">*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city', $data->city ?? '') }}" required>
                        @error('city')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
        
                <div class="form-row">
                    <div class="form-group">
                        <label for="pincode">PIN/ZIP Code <span class="required">*</span></label>
                        <input type="text" id="pincode" name="pincode" value="{{ old('pincode', $data->pincode ?? '') }}" required>
                        @error('pincode')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth <span class="required">*</span></label>
                        <input type="date" id="dob" name="dob" value="{{ old('dob', $data->dob ?? '') }}" required>
                        @error('dob')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
        
                <div class="form-row">
                    <div class="form-group">
                        <label for="profession">Profession <span class="required">*</span></label>
                        <input type="text" id="profession" name="profession" value="{{ old('profession', $data->profession ?? '') }}" required>
                        @error('profession')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="bloodGroup">Blood Group <span class="required">*</span></label>
                        <select id="bloodGroup" name="blood_group" required>
                            <option value="">Select blood group...</option>
                            @foreach (['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                <option value="{{ strtolower($bg) }}" {{ old('blood_group', $data->blood_group ?? '') == strtolower($bg) ? 'selected' : '' }}>
                                    {{ $bg }}
                                </option>
                            @endforeach
                        </select>
                        @error('blood_group')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
        
                <div class="form-row">
                    <div class="form-group">
                        <label for="mealPref">Meal Preference <span class="required">*</span></label>
                        <input type="text" id="mealPref" name="meal_preference" value="{{ old('meal_preference', $data->meal_preference ?? '') }}" required>
                        @error('meal_preference')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="tshirtSize">T-Shirt Size <span class="required">*</span></label>
                        <select id="tshirtSize" name="t_size" required>
                            <option value="">Select size...</option>
                            @foreach(['Kids','XS','S','M','L','XL','2XL','3XL'] as $size)
                                <option value="{{ $size }}" {{ old('t_size', $data->t_size ?? '') == $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                        @error('t_size')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
        
                <div class="form-group">
                    <label for="medicalCondition">Medical Condition</label>
                    <input type="text" id="medicalCondition" name="medical_condition" value="{{ old('medical_condition', $data->medical_condition ?? '') }}">
                    @error('medical_condition')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
        
                <div class="form-row">
                    <div class="form-group">
                        <label for="emergencyName">Emergency Contact Name <span class="required">*</span></label>
                        <input type="text" id="emergencyName" name="emg_name" value="{{ old('emg_name', $data->emg_name ?? '') }}" required>
                        @error('emg_name')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="emergencyPhone">Emergency Contact Number <span class="required">*</span></label>
                        <input type="tel" id="emergencyPhone" name="emg_contact" value="{{ old('emg_contact', $data->emg_contact ?? '') }}" required>
                        @error('emg_contact')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        
            <!-- STEP 3: DOCUMENTS + TERMS -->
            <div class="form-step" id="step3">
                <h2 class="step-title">Document Uploads & Terms</h2>
        
                @php $originalNames = isset($data->original_filenames) ? json_decode($data->original_filenames,true) : []; @endphp
        
                <div class="form-row">
                    <div class="form-group">
                        <label>Passport Upload (Front)</label>
                        <input type="file" name="passport_front">
                        @if(isset($data->passport_front))
                            <img src="{{ asset('storage/app/'.$data->passport_front) }}" width="100px">
                            <span>{{ $originalNames['passport_front'] ?? '' }}</span>
                            <input type="hidden" name="old_passport_front" value="{{ $data->passport_front }}">
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Passport Upload (Back)</label>
                        <input type="file" name="passport_back">
                        @if(isset($data->passport_back))
                            <img src="{{ asset('storage/app/'.$data->passport_back) }}" width="100px">
                            <span>{{ $originalNames['passport_back'] ?? '' }}</span>
                            <input type="hidden" name="old_passport_back" value="{{ $data->passport_back }}">
                        @endif
                    </div>
                </div>
        
                <div class="form-row">
                    <div class="form-group">
                        <label>Pan Card</label>
                        <input type="file" name="pan_gst">
                        @if(isset($data->pan_gst))
                            <img src="{{ asset('storage/app/'.$data->pan_gst) }}" width="100px">
                            <span>{{ $originalNames['pan_gst'] ?? '' }}</span>
                            <input type="hidden" name="old_pan_gst" value="{{ $data->pan_gst }}">
                        @endif
                    </div>
                    <div class="form-group">
                        <label>GST Certificate</label>
                        <input type="file" name="gst_certificate">
                        @if(isset($data->gst_certificate))
                            <img src="{{ asset('storage/app/'.$data->gst_certificate) }}" width="100px">
                            <span>{{ $originalNames['gst_certificate'] ?? '' }}</span>
                            <input type="hidden" name="old_gst_certificate" value="{{ $data->gst_certificate }}">
                        @endif
                    </div>
                </div>
        
                <div class="form-row">
                    <div class="form-group">
                        <label>Aadhaar Card Upload</label>
                        <input type="file" name="adhar_card">
                        @if(isset($data->adhar_card))
                            <img src="{{ asset('storage/app/'.$data->adhar_card) }}" width="100px">
                            <span>{{ $originalNames['adhar_card'] ?? '' }}</span>
                            <input type="hidden" name="old_adhar_card" value="{{ $data->adhar_card }}">
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Driving License Upload</label>
                        <input type="file" name="driving">
                        @if(isset($data->driving))
                            <img src="{{ asset('storage/app/'.$data->driving) }}" width="100px">
                            <span>{{ $originalNames['driving'] ?? '' }}</span>
                            <input type="hidden" name="old_driving" value="{{ $data->driving }}">
                        @endif
                    </div>
                </div>
        
                <div class="form-group file-upload" id="profile-upload-box">
                    <label>Profile Picture Upload (Candid) <span class="required">*</span></label>
                    <input 
                        type="file" 
                        name="profile" 
                        id="profile-input"
                        accept="image/*"
                        @if(!isset($data->profile)) required @endif
                        style="display:none;"
                    >
                
                    @if(isset($data->profile))
                        <div class="uploaded-file" id="profile-preview" style="position: relative; display:inline-block; cursor:pointer;">
                            <img src="{{ asset('storage/app/'.$data->profile) }}" 
                                 width="100px" 
                                 id="profile-img-preview" 
                                 class="thumb" 
                                 style="display:block;border-radius:6px;">
                            <span class="muted" id="profile-file-name">
                                {{ $originalNames['profile'] ?? basename($data->profile) }}
                            </span>
                
                            <div id="upload-overlay" 
                                 style="position:absolute;bottom:0;left:0;right:0;
                                        background:rgba(0,0,0,0.5);color:#fff;
                                        text-align:center;padding:4px;font-size:12px;
                                        cursor:pointer;border-radius:0 0 6px 6px;">
                                <i class="fas fa-upload"></i> Change
                            </div>
                
                            <button type="button" id="remove-profile" 
                                style="position:absolute;top:0;right:0;background:red;color:white;
                                       border:none;border-radius:50%;width:22px;height:22px;cursor:pointer;">
                                ✕
                            </button>
                
                            <input type="hidden" name="old_profile" value="{{ $data->profile }}">
                        </div>
                    @else
                        <div class="uploaded-file" id="profile-preview" style="display:none; position: relative; display:inline-block; cursor:pointer;">
                            <img src="" width="100px" id="profile-img-preview" class="thumb" style="display:none;border-radius:6px;">
                            <span class="muted" id="profile-file-name"></span>
                
                            <div id="upload-overlay" 
                                 style="display:none;position:absolute;bottom:0;left:0;right:0;
                                        background:rgba(0,0,0,0.5);color:#fff;
                                        text-align:center;padding:4px;font-size:12px;
                                        cursor:pointer;border-radius:0 0 6px 6px;">
                                <i class="fas fa-upload"></i> Upload
                            </div>
                
                            <button type="button" id="remove-profile" 
                                style="display:none;position:absolute;top:0;right:0;background:red;color:white;
                                       border:none;border-radius:50%;width:22px;height:22px;cursor:pointer;">
                                ✕
                            </button>
                        </div>
                    @endif
                </div>
                

                <div class="terms-container">
                    <label><strong>Terms and Conditions</strong></label>
                    <div class="terms-text">{!! setting('terms_condition') !!}</div>
                    <div class="checkbox-container">
                        <input type="checkbox" id="acceptTerms" name="terms_accepted"
                            {{ (!isset($data) || $data->terms_accepted == 1) ? 'checked' : '' }} required>
                        <label for="acceptTerms">Yes, I have read and agree <span class="required">*</span></label>
                    </div>
                </div>
            </div>
        
            <!-- NAVIGATION -->
            <div class="form-buttons">
                <button type="button" class="btn btn-secondary" id="backBtn" style="display:none">Back</button>
                <button type="button" class="btn btn-primary" id="nextBtn">Next Step</button>
                <button type="button" class="btn btn-ghost" id="previewBtn" style="display:none">Preview</button>
                <button type="submit" class="btn btn-primary" id="submitBtn" style="display:none">Submit Application</button>
            </div>
        </form>
        
  </div>

  <!-- Preview modal -->
  <div class="modal-backdrop" id="previewBackdrop" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="previewTitle">
      <header>
        <h3 id="previewTitle">Preview your details</h3>
        <button class="btn btn-ghost" id="closePreview" aria-label="Close preview">Close</button>
      </header>
      <p class="muted">Please review the information below. If something looks off, click Close to edit.</p>
      <div class="preview-grid" id="previewGrid"></div>
      <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:14px">
        <button class="btn btn-secondary" id="closePreview2">Edit</button>
        <button class="btn btn-primary" id="confirmSubmit">Confirm & Submit</button>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="{{ asset('public/userpanel') }}/asset/js/registration.js"></script>
  <script>
    // Start code for auto selected state and country
    $(document).ready(function () {
        var selectedCountry = $('#country').val();
        var selectedState = $('#selected-State').val();
        if (selectedCountry) {
            getState(selectedCountry, selectedState);
        }

        $('#country').on('change', function () {
            var newCountry = $(this).val();
            getState(newCountry);
        });
    });

    function getState(countryName, selectedState = '') {
    //  alert('method');
        $.ajax({
            url: "{{route('getState')}}",
            type: "POST",
            data: {
                value: countryName,
                selected: selectedState,
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
            
                if (response === "") {
                    $('#state').html('<option value="">No State Found</option>');
                } else {
                    $('#state').html(response);
                }
            },
            error: function () {
                $('#state').html('<option value="">Error loading states</option>');
            }
        });
    }
  </script>
</body>
</html>
