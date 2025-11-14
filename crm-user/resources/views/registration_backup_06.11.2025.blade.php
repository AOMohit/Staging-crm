<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Adventure Expedition Booking – Fixed</title>
  <link href="{{ asset('public/userpanel') }}/asset/css/registration.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css" />
  <style>
    textarea#address { padding: 0px 20px; }

    @media (max-width: 768px) {
        .preview-grid {
            grid-template-columns: 1fr;
            display: block;
        
        }        
        .preview-item {
            margin-bottom: 12px;
            word-break: break-all;
        }
        .form-header h1 { font-size: 26px; }
        .step-circle {
            width: 30px!important;
            height: 30px!important;
            margin-right: 0;
        }
        .form-content { padding: 13px 24px; }
        .form-row {
            grid-template-columns: 1fr;
            gap: 2px;
        } 
        .doc-col { width: 100%; }
        .edit-button { gap: 34px !important; }
    }
  </style>
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
                    {{-- <input type="tel" id="phone" name="phone" value="{{ old('phone', $data->phone ?? '') }}" required>
                    <input type="hidden" name="telephone_code" id="telephone_code"> --}}
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $data->phone ?? '') }}" required>
                    <input type="hidden" name="telephone_code" id="telephone_code" value="{{ old('telephone_code', $data->telephone_code ?? '') }}">

                    @error('phone')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="email">E-mail <span class="required">*</span></label>
                    <input type="email" id="email" name="email"
                        value="{{ old('email', $data->email ?? '') }}"
                        {{ isset($data) ? 'readonly style=opacity:0.7;pointer-events:none;background-color:bisque;' : '' }} required>
                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
    
            <div class="form-row single">
                <div class="form-group">
                    <label for="expedition">Choose Your Expedition <span class="required">*</span></label>
                    <select id="expedition" name="letest_trip" required style="pointer-events:none;opacity:0.7;background-color: bisque;">
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
            <div class="form-row">
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
                    <label for="state">State <span class="required">*</span></label>
                    <input type="hidden" id="selected-State" value="{{ $data->state ?? '' }}">
                    <select id="state" name="state" required>
                        <option value="">Select State</option>
                    </select>
                    @error('state')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-row">
               
                <div class="form-group">
                    <label for="city">City <span class="required">*</span></label>
                    <input type="text" id="city" name="city" value="{{ old('city', $data->city ?? '') }}" required>
                    @error('city')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="pincode">Pincode <span class="required">*</span></label>
                    <input type="text" id="pincode" name="pincode" value="{{ old('pincode', $data->pincode ?? '') }}" required>
                    @error('pincode')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-row">
               <div class="form-group">
                 <label for="address">Address <span class="required">*</span></label>
                  <textarea id="address" name="address" rows="3" style="transform: translateY(-1px); width: 100%; height: 45px;">{{ old('address', $data->address ?? '') }}</textarea>
                     @error('address')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="dob">Date Of Birth <span class="required">*</span></label>
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
                    <label for="tshirtSize">Choose T-Shirt Size <span class="required">*</span></label>
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
                <label for="medicalCondition">Medical Condition if any</label>
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
                        <button type="button" class="remove">×</button>
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
                    <h2>Aadhaar Card Upload</h2>
                
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
                    <h2>Driving License Upload</h2>
                
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
                    <h2>Profile Picture Upload <span class="required">*</span></h2>
                    <div class="upload-box">
                        <input type="file" name="profile" class="file-input" accept="image/*">
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

            <!-- Extra Documents -->
            <div class="extra-documents">
                <h2>Extra Documents</h2>
            
                <div id="extraDocumentsContainer">
                    {{-- Show existing uploaded extra docs --}}
                    @if(isset($extra) && count($extra) > 0)
                        @foreach($extra as $key => $doc)
                            <div class="extra-doc-row" style="margin-bottom: 15px;">
                                <div class="extra-doc-fields" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                                    <input type="hidden" name="id[]" value="{{ $doc->id }}">
                                    <input type="text" name="title[]" class="form-control" value="{{ $doc->title }}" required style="flex:1; min-width:200px;">
                                    
                                    <div class="upload-preview" style="display:flex; align-items:center; gap:10px;">
                                        @if($doc->image)
                                            <a href="{{ asset('storage/app/' . $doc->image) }}" target="_blank">
                                                <img src="{{ asset('storage/app/' . $doc->image) }}" 
                                                     alt="Document" 
                                                     style="width:60px; height:60px; object-fit:cover; border-radius:8px; border:1px solid #ccc;">
                                            </a>
                                        @endif
                                        <input type="file" name="image[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" style="flex:1; min-width:250px;">
                                    </div>
            
                                    <button type="button" class="btn btn-danger removeDocBtn">Remove</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            
                <button type="button" class="btn btn-secondary" id="addExtraDocumentBtn" style="margin-top:10px;">
                    + Add Extra Document
                </button>
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
    <div class="modal preview-box" role="dialog" aria-modal="true" aria-labelledby="previewTitle" style="max-width:950px;">
      <header>
        <h3 id="previewTitle">Preview your details</h3>
        <button class="btn btn-ghost" id="closePreview" aria-label="Close preview">Close</button>
      </header>
      <p class="muted">Please review the information below. If something looks off, click Close to edit.</p>
      <div class="preview-grid" id="previewGrid"></div>
      <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:14px;" class="edit-button">
        <button class="btn btn-secondary" id="closePreview2">Edit</button>
        <button class="btn btn-primary" id="confirmSubmit">Confirm & Submit</button>
      </div>
    </div>
  </div>
  <!-- Zoom Popup -->
            <div id="img-popup">
                <span id="close-popup">&times;</span>
                <img id="popup-img" src="" alt="">
            </div>


    <!-- Glass-style modal container -->
    <div id="customModal" 
        style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%);
                background:rgba(255,255,255,0.95); backdrop-filter:blur(25px);
                padding:28px; border-radius:18px; box-shadow:rgba(79,51,37,0.2) 0px 25px 70px;
                border:1px solid var(--glass-border); z-index:10000; color:var(--text-primary);
                text-align:center; max-width:520px; overflow-y:auto; max-height:90vh;">
    <div id="modalEmoji" style="font-size:2.6rem;margin-bottom:8px">⚠️</div>
    <h2 id="modalTitle" style="margin-bottom:8px;color:var(--primary);font-weight:700"></h2>
    <p id="modalMessage" style="margin-bottom:18px;line-height:1.6"></p>
    <div id="modalButtons" style="display:flex;justify-content:center;gap:10px;"></div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('public/userpanel') }}/asset/js/registration.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
    <script>
        window.extraDocuments = @json($extra);
        
        // Telephone Country Code Script 
        const phoneInput = document.getElementById('phone');
        const codeInput = document.getElementById('telephone_code');
        let initialCountry = 'in';
        if (codeInput.value) {
            const countryData = window.intlTelInputGlobals.getCountryData();
            const found = countryData.find(c => `+${c.dialCode}` === codeInput.value);
            if (found) initialCountry = found.iso2;
        }
        const iti = window.intlTelInput(phoneInput, {
            initialCountry: initialCountry,
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
        });
        if (phoneInput.value) iti.setNumber(phoneInput.value);
        phoneInput.addEventListener('countrychange', () => {
            codeInput.value = '+' + iti.getSelectedCountryData().dialCode;
        });
        codeInput.value = '+' + iti.getSelectedCountryData().dialCode;
    </script>
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

        
        // === Akanksha's File Upload Script ===


    const MAX_SIZE = 20 * 1024 * 1024; // 20MB in bytes

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(0) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    document.querySelectorAll('.doc-col').forEach(col => {
        const input = col.querySelector('.file-input');
        const box = col.querySelector('.upload-box');
        const preview = col.querySelector('.preview');
        const img = col.querySelector('.preview-img');
        const nameEl = col.querySelector('.name');
        const sizeEl = col.querySelector('.size');
        const removeBtn = col.querySelector('.remove');
        const zoomBtn = col.querySelector('.zoom');

        function handleFile(file) {
            if (file.size > MAX_SIZE) {
                alert('File exceeds 20MB limit');
                return;
            }

            nameEl.textContent = file.name;
            sizeEl.textContent = formatSize(file.size);

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    preview.classList.add('active');
                };
                reader.readAsDataURL(file);
            } else {
                img.src = 'data:image/svg+xml,%3Csvg width="80" height="80" xmlns="http://www.w3.org/2000/svg"%3E%3Crect width="80" height="80" fill="%23E0E0E0"/%3E%3Ctext x="40" y="45" font-family="Arial" font-size="14" fill="%23666" text-anchor="middle"%3EPDF%3C/text%3E%3C/svg%3E';
                preview.classList.add('active');
            }
        }

        function clearPreview(e) {
            if (e) e.stopPropagation(); // Prevent click bubbling
            input.value = '';
            preview.classList.remove('active');
            img.src = '';
            nameEl.textContent = '';
            sizeEl.textContent = '';
        }

        input.onchange = e => {
            const file = e.target.files[0];
            if (file) handleFile(file);
        };

        if (removeBtn) {
            removeBtn.onclick = clearPreview;
        }

        box.ondragover = e => {
            e.preventDefault();
            box.classList.add('drag-over');
        };

        box.ondragleave = e => {
            e.preventDefault();
            box.classList.remove('drag-over');
        };

        box.ondrop = e => {
            e.preventDefault();
            box.classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            if (file) handleFile(file);
        };

        if (zoomBtn) {
            zoomBtn.onclick = e => {
                e.stopPropagation(); // Prevent preview click
                if (img.src) {
                    document.getElementById('popup-img').src = img.src;
                    document.getElementById('img-popup').style.display = 'flex';
                }
            };
        }

        // Preview click opens zoom
        preview.onclick = () => {
            if (img.src && img.src !== '') {
                document.getElementById('popup-img').src = img.src;
                document.getElementById('img-popup').style.display = 'flex';
            }
        };
    });

    // Close popup handler
    const closePopupBtn = document.getElementById('close-popup');
    if (closePopupBtn) {
        closePopupBtn.onclick = e => {
            e.stopPropagation(); // Prevent bubble to preview
            document.getElementById('img-popup').style.display = 'none';
            document.getElementById('popup-img').src = ''; // Clear image to prevent blank reopen
        };
    }

    // If image already exists (from server)
    document.querySelectorAll('.doc-col').forEach(col => {
        const preview = col.querySelector('.preview');
        const img = col.querySelector('.preview-img');
        const nameEl = col.querySelector('.name');
        const sizeEl = col.querySelector('.size');

        if (img && img.getAttribute('src') && img.getAttribute('src').trim() !== '') {
            preview.classList.add('active');

            const oldName = nameEl.textContent.trim();
            const oldSize = sizeEl.textContent.trim();

            if (oldName === '') {
                nameEl.textContent = img.src.split('/').pop();
            }
            if (oldSize === '') {
                sizeEl.textContent = '(Previously uploaded)';
            }
        }
    });

    // Extra Documents script start here:
    document.addEventListener('DOMContentLoaded', function() {
      let container = document.getElementById('extraDocumentsContainer');
      let addBtn = document.getElementById('addExtraDocumentBtn');

      // ✅ Add new extra document row
      addBtn.addEventListener('click', function() {
          let div = document.createElement('div');
          div.classList.add('extra-doc-row');
          div.style.marginBottom = '15px';
          div.innerHTML = `
              <div class="extra-doc-fields" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                  <input type="text" name="title[]" class="form-control" placeholder="Document Name" required style="flex:1; min-width:200px;">
                  <input type="file" name="image[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required style="flex:1; min-width:250px;">
                  <button type="button" class="btn btn-danger removeDocBtn">Remove</button>
              </div>
          `;
          container.appendChild(div);
      });

      // ✅ Remove existing or newly added document
      container.addEventListener('click', function(e) {
          if (e.target.classList.contains('removeDocBtn')) {
              let row = e.target.closest('.extra-doc-row');
              let docIdInput = row.querySelector('input[name="id[]"]');
              let docId = docIdInput ? docIdInput.value : null;

                if (docId) {
                    customConfirm("Are you sure you want to delete this document permanently?", "Confirm Deletion", "⚠️")
                        .then(userConfirmed => {
                        if (userConfirmed) {
                            fetch(`{{ route('removeImage') }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ id: docId })
                            })
                            .then(res => res.text())
                            .then(data => {
                            if (data.trim() === '1') {
                                customAlert("Document deleted successfully!", "Deleted", "🗑️");
                                row.remove();
                            } else {
                                customAlert("Failed to delete document. Please try again.", "Error", "❌");
                            }
                            })
                            .catch(err => {
                            console.error(err);
                            customAlert("Something went wrong while deleting.", "Error", "❌");
                            });
                        }
                        });
                    } else {
                    row.remove();
                    }

          }
      });
    });
</script>
  
</body>
</html>
