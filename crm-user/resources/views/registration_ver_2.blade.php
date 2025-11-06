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

            <div class="form-group">
                <label for="address">Full Address <span class="required">*</span></label>
                <textarea id="address" name="address" rows="3">{{ old('address', $data->address ?? '') }}</textarea>
                @error('address')<span class="text-danger">{{ $message }}</span>@enderror
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

        <!-- STEP 3: DOCUMENTS + TERMS (merged) updated -->
        
        <div class="form-step" id="step3">
            <h2 class="step-title">Document Uploads & Terms</h2>
            @php $originalNames = isset($data->original_filenames) ? json_decode($data->original_filenames,true) : []; @endphp

            <!-- ROW 1: Passport Front & Back -->
            <div class="form-rowss">
                <div class="doc-col">
                    <h2>Passport Upload (Front)</h2>
                    <div class="upload-box">
                        <input type="file" class="file-input" name="passport_front" accept="image/*,.pdf">
                        <div class="upload-content">
                            <span class="upload-text">Choose File</span>
                            <div class="upload-icons">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span class="divider">|</span>
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="preview">
                        <div class="img-wrap">
                            @if(isset($data->passport_front))
                                <img src="{{ asset('storage/app/'.$data->passport_front) }}" class="preview-img" alt="">
                                <input type="hidden" name="old_passport_front" value="{{ $data->passport_front }}">
                            @else
                                <img class="preview-img" src="" alt="">
                            @endif
                            <div class="zoom">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 8v6m-3-3h6"/></svg>
                            </div>
                        </div>
                        <div class="info">
                            <div class="name">{{ $originalNames['passport_front'] ?? '' }}</div>
                            <div class="size">
                                {{-- @if(isset($data->passport_front))
                                    {{ number_format(Storage::size($data->passport_front) / 1024, 2) }} KB
                                @endif --}}
                            </div>
                        </div>
                        <button class="remove" type="button">×</button>
                    </div>
                    <div class="max-size">(Max Size: 20mb)</div>
                </div>

                <div class="doc-col">
                    <h2>Passport Upload (Back)</h2>
                
                    <div class="upload-box">
                        <input type="file" class="file-input" name="passport_back" accept="image/*,.pdf">
                        <div class="upload-content">
                            <span class="upload-text">Choose File</span>
                            <div class="upload-icons">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                <span class="divider">|</span>
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                
                    <div class="preview">
                        <div class="img-wrap">
                            @if(isset($data->passport_back))
                                <img src="{{ asset('storage/app/' . $data->passport_back) }}" class="preview-img" alt="">
                                <input type="hidden" name="old_passport_back" value="{{ $data->passport_back }}">
                            @else
                                <img class="preview-img" src="" alt="">
                            @endif
                            <div class="zoom">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8" stroke-width="2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-4.35-4.35M11 8v6m-3-3h6"/>
                                </svg>
                            </div>
                        </div>
                
                        <div class="info">
                            <div class="name">{{ $originalNames['passport_back'] ?? '' }}</div>
                            <div class="size">
                                {{-- @if(isset($data->passport_back))
                                    {{ number_format(Storage::size($data->passport_back) / 1024, 2) }} KB
                                @endif --}}
                            </div>
                        </div>
                
                        @if(isset($data->passport_back))
                            <button class="remove" type="button" onclick="removeFile('passport_back')">×</button>
                        @endif
                    </div>
                
                    <div class="max-size">(Max Size: 20mb)</div>
                </div>
            </div>

            <!-- ROW 2: Pan Card & GST -->
            <div class="form-rowss">
                <div class="doc-col">
                    <h2>Pan Card</h2>
                
                    <div class="upload-box">
                        <input type="file" class="file-input" name="pan_gst" accept="image/*,.pdf">
                        <div class="upload-content">
                            <span class="upload-text">Choose File</span>
                            <div class="upload-icons">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                <span class="divider">|</span>
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                
                    <div class="preview">
                        <div class="img-wrap">
                            @if(isset($data->pan_gst))
                                <img src="{{ asset('storage/app/' . $data->pan_gst) }}" class="preview-img" alt="">
                                <input type="hidden" name="old_pan_gst" value="{{ $data->pan_gst }}">
                            @else
                                <img class="preview-img" src="" alt="">
                            @endif
                            <div class="zoom">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8" stroke-width="2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-4.35-4.35M11 8v6m-3-3h6"/>
                                </svg>
                            </div>
                        </div>
                
                        <div class="info">
                            <div class="name">{{ $originalNames['pan_gst'] ?? '' }}</div>
                            <div class="size">
                                {{-- @if(isset($data->pan_gst))
                                    {{ number_format(Storage::size($data->pan_gst) / 1024, 2) }} KB
                                @endif --}}
                            </div>
                        </div>
                
                        @if(isset($data->pan_gst))
                            <button class="remove" type="button" onclick="removeFile('pan_gst')">×</button>
                        @endif
                    </div>
                
                    <div class="max-size">(Max Size: 20mb)</div>
                </div>

                <div class="doc-col">
                    <h2>GST Certificate (For Billing Purpose)</h2>
                
                    <div class="upload-box">
                        <input type="file" class="file-input" name="gst_certificate" accept="image/*,.pdf">
                        <div class="upload-content">
                            <span class="upload-text">Choose File</span>
                            <div class="upload-icons">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                <span class="divider">|</span>
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                
                    <div class="preview">
                        <div class="img-wrap">
                            @if(isset($data->gst_certificate))
                                <img src="{{ asset('storage/app/' . $data->gst_certificate) }}" class="preview-img" alt="">
                                <input type="hidden" name="old_gst_certificate" value="{{ $data->gst_certificate }}">
                            @else
                                <img class="preview-img" src="" alt="">
                            @endif
                            <div class="zoom">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8" stroke-width="2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-4.35-4.35M11 8v6m-3-3h6"/>
                                </svg>
                            </div>
                        </div>
                
                        <div class="info">
                            <div class="name">{{ $originalNames['gst_certificate'] ?? '' }}</div>
                            <div class="size">
                                {{-- @if(isset($data->gst_certificate))
                                    {{ number_format(Storage::size($data->gst_certificate) / 1024, 2) }} KB
                                @endif --}}
                            </div>
                        </div>
                
                        @if(isset($data->gst_certificate))
                            <button class="remove" type="button" onclick="removeFile('gst_certificate')">×</button>
                        @endif
                    </div>
                
                    <div class="max-size">(Max Size: 20mb)</div>
                </div>
            </div>

            <!-- ROW 3: Aadhaar & Driving License -->
            <div class="form-rowss">
                <div class="doc-col">
                    <h2>Aadhaar Card</h2>
                
                    <div class="upload-box">
                        <input type="file" class="file-input" name="adhar_card" accept="image/*,.pdf">
                        <div class="upload-content">
                            <span class="upload-text">Choose File</span>
                            <div class="upload-icons">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                <span class="divider">|</span>
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                
                    <div class="preview">
                        <div class="img-wrap">
                            @if(isset($data->adhar_card))
                                <img src="{{ asset('storage/app/' . $data->adhar_card) }}" class="preview-img" alt="">
                                <input type="hidden" name="old_adhar_card" value="{{ $data->adhar_card }}">
                            @else
                                <img class="preview-img" src="" alt="">
                            @endif
                            <div class="zoom">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8" stroke-width="2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-4.35-4.35M11 8v6m-3-3h6"/>
                                </svg>
                            </div>
                        </div>
                
                        <div class="info">
                            <div class="name">{{ $originalNames['adhar_card'] ?? '' }}</div>
                            <div class="size">
                                {{-- @if(isset($data->adhar_card))
                                    {{ number_format(Storage::size($data->adhar_card) / 1024, 2) }} KB
                                @endif --}}
                            </div>
                        </div>
                
                        @if(isset($data->adhar_card))
                            <button class="remove" type="button" onclick="removeFile('adhar_card')">×</button>
                        @endif
                    </div>
                
                    <div class="max-size">(Max Size: 20mb)</div>
                </div>

                <div class="doc-col">
                    <h2>Driving License</h2>
                
                    <div class="upload-box">
                        <input type="file" class="file-input" name="driving" accept="image/*,.pdf">
                        <div class="upload-content">
                            <span class="upload-text">Choose File</span>
                            <div class="upload-icons">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                <span class="divider">|</span>
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                
                    <div class="preview">
                        <div class="img-wrap">
                            @if(isset($data->driving))
                                <img src="{{ asset('storage/app/' . $data->driving) }}" class="preview-img" alt="">
                                <input type="hidden" name="old_driving" value="{{ $data->driving }}">
                            @else
                                <img class="preview-img" src="" alt="">
                            @endif
                            <div class="zoom">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8" stroke-width="2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-4.35-4.35M11 8v6m-3-3h6"/>
                                </svg>
                            </div>
                        </div>
                
                        <div class="info">
                            <div class="name">{{ $originalNames['driving'] ?? '' }}</div>
                            <div class="size">
                                {{-- @if(isset($data->driving))
                                    {{ number_format(Storage::size($data->driving) / 1024, 2) }} KB
                                @endif --}}
                            </div>
                        </div>
                
                        @if(isset($data->driving))
                            <button class="remove" type="button" onclick="removeFile('driving')">×</button>
                        @endif
                    </div>
                
                    <div class="max-size">(Max Size: 20mb)</div>
                </div>
            </div>

            <!-- ROW 4: Profile Picture -->
            <div class="form-rowss">
                <div class="doc-col" style="width:100%;">
                    <h2>Profile Picture</h2>
                    <div class="upload-box">
                        <input type="file" class="file-input" accept="image/*">
                        <div class="upload-content">
                            <span class="upload-text">Choose File</span>
                            <div class="upload-icons">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span class="divider">|</span>
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="preview">
                        <div class="img-wrap">
                            @if(isset($data->profile))
                                <img src="{{ asset('storage/app/' . $data->profile) }}" class="preview-img" alt="">
                                <input type="hidden" name="old_profile" value="{{ $data->profile }}">
                            @else
                                <img class="preview-img" src="" alt="">
                            @endif
                            <div class="zoom">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 8v6m-3-3h6"/></svg>
                            </div>
                        </div>
                        <div class="info">
                            <div class="name">{{ $originalNames['profile'] ?? '' }}</div>
                            <div class="size">
                                {{-- @if(isset($data->profile))
                                    {{ number_format(Storage::size($data->profile) / 1024, 2) }} KB
                                @endif --}}
                            </div>
                        </div>
                        @if(isset($data->profile))
                            <button class="remove" type="button" onclick="removeFile('profile')">×</button>
                        @endif
                    </div>
                    <div class="max-size">(Max Size: 20mb)</div>
                </div>
            </div>

            <!-- Zoom Popup -->
            <div id="img-popup">
                <span id="close-popup">&times;</span>
                <img id="popup-img" src="" alt="">
            </div>


            <!-- Terms & Conditions -->
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

        // Akanksha's Script start here:
        const MAX = 5 * 1024 * 1024;
        function formatSize(b){return b<1024?b+'B':b<5242880?(b/1024).toFixed(0)+'KB':(b/1048576).toFixed(1)+'MB';}
        document.querySelectorAll('.doc-col').forEach(col=>{
            const input=col.querySelector('.file-input');
            const box=col.querySelector('.upload-box');
            const preview=col.querySelector('.preview');
            const img=col.querySelector('.preview-img');
            const nameEl=col.querySelector('.name');
            const sizeEl=col.querySelector('.size');
            const rem=col.querySelector('.remove');
            const zoom=col.querySelector('.zoom');

            function handleFile(f){
                if(f.size>MAX)return alert('File exceeds 20MB limit');
                nameEl.textContent=f.name;
                sizeEl.textContent=formatSize(f.size);
                if(f.type.startsWith('image/')){
                    const reader=new FileReader();
                    reader.onload=e=>{img.src=e.target.result; preview.classList.add('active');};
                    reader.readAsDataURL(f);
                }else{
                    img.src='data:image/svg+xml,%3Csvg width="80" height="80" xmlns="http://www.w3.org/2000/svg"%3E%3Crect width="80" height="80" fill="%23E0E0E0"/%3E%3Ctext x="40" y="45" font-family="Arial" font-size="14" fill="%23666" text-anchor="middle"%3EPDF%3C/text%3E%3C/svg%3E';
                    preview.classList.add('active');
                }
            }
            function clear(){input.value=''; preview.classList.remove('active'); img.src='';}
            input.onchange=e=>e.target.files[0]&&handleFile(e.target.files[0]);
            if (rem) {
                rem.onclick = clear;
            }
            box.ondragover=e=>{e.preventDefault(); box.classList.add('drag-over');};
            box.ondragleave=e=>{e.preventDefault(); box.classList.remove('drag-over');};
            box.ondrop=e=>{e.preventDefault(); box.classList.remove('drag-over'); e.dataTransfer.files[0]&&handleFile(e.dataTransfer.files[0]);};
            zoom.onclick=()=>{if(img.src){document.getElementById('popup-img').src=img.src; document.getElementById('img-popup').style.display='flex';}};
        });
        document.getElementById('close-popup').onclick=()=>{document.getElementById('img-popup').style.display='none';};

        // Add this after your existing JS handling each preview
        document.querySelectorAll('.preview').forEach(preview=>{
            const img = preview.querySelector('img');
            preview.onclick = () => {
                if(img.src){
                    document.getElementById('popup-img').src = img.src;
                    document.getElementById('img-popup').style.display = 'flex';
                }
            };
        });
        document.getElementById('close-popup').onclick = () => {
            document.getElementById('img-popup').style.display = 'none';
        };
        // Akanksha's script end here

        document.querySelectorAll('.doc-col').forEach(col => {
            const preview = col.querySelector('.preview');
            const img = col.querySelector('.preview-img');
            const nameEl = col.querySelector('.name');
            const sizeEl = col.querySelector('.size');
            
            // If old image exists (from Blade)
            if (img && img.getAttribute('src') && img.getAttribute('src').trim() !== '') {
                preview.classList.add('active');

                // Optional: if you want to re-display stored file name and size properly
                const oldName = nameEl.textContent.trim();
                const oldSize = sizeEl.textContent.trim();
                if (oldName === '') {
                    // Fallback if not printed by Blade
                    nameEl.textContent = img.src.split('/').pop();
                }
                if (oldSize === '') {
                    // You could show "Previously uploaded"
                    sizeEl.textContent = '(Previously uploaded)';
                }
            }
        });

    </script>
  
</body>
</html>
