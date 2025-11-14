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
        .iti__country-list {
            white-space: normal;
            width: 413px;
            max-height: 119px!important;
        
        }
        button#previewBtn { display: none !important; }
        .img-wrap {
            position: relative;
            width: 100px;
            height: 60px;
            flex-shrink: 0;
            cursor: pointer;
        }
        input.form-control.extra-doc-name { height: 68px; }
        button.btn.btn-danger.removeDocBtn { margin-top: 15px; }
        .zoom { display: none; }
        hr.line-hr { margin: 25px 0px 10px 0px; }
        .extra-documents { margin-bottom: 35px; }
        textarea#address { padding: 0px 20px; }

        .iti {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .prefilled-select {
            pointer-events: none;
            opacity: 0.7;
            background-color: bisque;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: none !important;
        }

        .prefilled-select::-ms-expand { display: none; }
        .extra-doc-row { margin-top: 10px; }
        .extra-doc-row .doc-col { flex: 1; min-width: 280px; }
        .extra-doc-fields { align-items: flex-start !important; }
            
        input.form-control.extra-document {
            height: 68px;
            margin-top: 0;
            padding: 0 20px;
        }

        .extra-doc-row .doc-col .upload-box input.file-input {
            height: 68px; 
            padding: 0 20px;
        }
            
        button.btn.btn-danger.removeDocBtn.extra-remove-btn {
            margin-top: 13px;
        }
        .preview {
            margin-top: 3px!important;
            border: 1px solid #ccc;
            height: 68px!important;
            padding: 5px!important;
        }
        
        @media (max-width: 768px) {
            .iti__country-list {
                white-space: normal;
                width: auto !important;
            }
            .form-rowss .doc-col {
                width: 100%;
                margin-bottom: 20px;
            }
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
                width: 30px !important;
                height: 30px !important;
                margin-right: 0;
            }
            .form-content { padding: 13px 24px; }
            .form-row {
                grid-template-columns: 1fr;
                gap: 2px;
            }
            .edit-button { gap: 34px !important; }
        }

        .is-invalid {
            border-color: red !important;
            outline: none;
        }

        .error-msg {
            display: block;
            margin-top: 4px;
            color: red;
            font-size: 13px;
        } 
    </style>

    {{-- Define the PDF placeholder SVG as a PHP variable for easy use in Blade --}}
    @php
        $pdfSvg = 'data:image/svg+xml,%3Csvg width="80" height="80" xmlns="http://www.w3.org/2000/svg"%3E%3Crect width="80" height="80" fill="%23E53935" rx="8"/%3E%3Ctext x="40" y="45" font-family="Arial, sans-serif" font-size="20" font-weight="bold" fill="white" text-anchor="middle" dominant-baseline="middle"%3EPDF%3C/text%3E%3C/svg%3E';
    @endphp
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
            <input type="hidden" name="customer_id" value="{{ $data->id }}">
            <div class="form-step active" id="step1">
                <h2 class="step-title">Personal Information</h2>
        
                <div class="form-row">
                    @if(isset($data)) {{-- Use isset() for robustness --}}
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
                    <div class="form-group phone-group">
                        <label for="phone">Phone Number <span class="required">*</span></label>
                        {{-- The telephone input uses JS for country code, hidden input stores the code --}}
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
                        <label for="expedition"> Choose Your Expedition <span class="required">*</span></label>
                        <select id="expedition" name="letest_trip" required class="{{ request()->trip_id ? 'prefilled-select' : '' }}">
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
                        {{-- Hidden input holds the old state value for initialization in JS --}}
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
                            {{-- Note: Using an array in the loop ensures the blood group values are consistently passed as lowercase as per the old() check --}}
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

            <div class="form-step" id="step3">
                <h2 class="step-title">Document Uploads & Terms</h2>
                {{-- Ensure $originalNames is an array for safety --}}
                @php $originalNames = isset($data->original_filenames) ? json_decode($data->original_filenames,true) : []; @endphp
                
                {{-- Helper function to check if a file path is a PDF (assuming standard storage structure/naming) --}}
                @php
                    function isPdf($path) {
                        if (is_string($path)) {
                            return strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf';
                        }
                        return false;
                    }
                @endphp
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
                        <div class="preview" style="{{ isset($data->passport_front) ? 'display: flex;' : 'display: none;' }}">
                            <div class="img-wrap">
                                @if(isset($data->passport_front))
                                    @php
                                        $is_pdf = isPdf($data->passport_front);
                                        $image_src = $is_pdf ? $pdfSvg : asset('storage/app/'.$data->passport_front);
                                    @endphp
                                    <img src="{{ $image_src }}" class="preview-img" alt="{{ $is_pdf ? 'PDF Document' : 'Passport Front' }}">
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
                                    {{-- Placeholder for size --}}
                                </div>
                            </div>
                            {{-- The remove button calls the JS function defined below --}}
                            @if(isset($data->passport_front))
                                <button class="remove" type="button" onclick="removeFile('passport_front', this)" style="display: block;">×</button>
                            @endif
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
                    
                        <div class="preview" style="{{ isset($data->passport_back) ? 'display: flex;' : 'display: none;' }}">
                            <div class="img-wrap">
                                @if(isset($data->passport_back))
                                    @php
                                        $is_pdf = isPdf($data->passport_back);
                                        $image_src = $is_pdf ? $pdfSvg : asset('storage/app/'.$data->passport_back);
                                    @endphp
                                    <img src="{{ $image_src }}" class="preview-img" alt="{{ $is_pdf ? 'PDF Document' : 'Passport Back' }}">
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
                                    {{-- Placeholder for size --}}
                                </div>
                            </div>
                    
                            @if(isset($data->passport_back))
                                <button class="remove" type="button" onclick="removeFile('passport_back', this)" style="display: block;">×</button>
                            @endif
                        </div>
                    
                        <div class="max-size">(Max Size: 20mb)</div>
                    </div>
                </div>

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
                    
                        <div class="preview" style="{{ isset($data->pan_gst) ? 'display: flex;' : 'display: none;' }}">
                            <div class="img-wrap">
                                @if(isset($data->pan_gst))
                                    @php
                                        $is_pdf = isPdf($data->pan_gst);
                                        $image_src = $is_pdf ? $pdfSvg : asset('storage/app/'.$data->pan_gst);
                                    @endphp
                                    <img src="{{ $image_src }}" class="preview-img" alt="{{ $is_pdf ? 'PDF Document' : 'Pan Card' }}">
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
                                    {{-- Placeholder for size --}}
                                </div>
                            </div>
                    
                            @if(isset($data->pan_gst))
                                <button class="remove" type="button" onclick="removeFile('pan_gst', this)" style="display: block;">×</button>
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
                    
                        <div class="preview" style="{{ isset($data->gst_certificate) ? 'display: flex;' : 'display: none;' }}">
                            <div class="img-wrap">
                                @if(isset($data->gst_certificate))
                                    @php
                                        $is_pdf = isPdf($data->gst_certificate);
                                        $image_src = $is_pdf ? $pdfSvg : asset('storage/app/'.$data->gst_certificate);
                                    @endphp
                                    <img src="{{ $image_src }}" class="preview-img" alt="{{ $is_pdf ? 'PDF Document' : 'GST Certificate' }}">
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
                                    {{-- Placeholder for size --}}
                                </div>
                            </div>
                    
                            @if(isset($data->gst_certificate))
                                <button class="remove" type="button" onclick="removeFile('gst_certificate', this)" style="display: block;">×</button>
                            @endif
                        </div>
                    
                        <div class="max-size">(Max Size: 20mb)</div>
                    </div>
                </div>

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
                    
                        <div class="preview" style="{{ isset($data->adhar_card) ? 'display: flex;' : 'display: none;' }}">
                            <div class="img-wrap">
                                @if(isset($data->adhar_card))
                                    @php
                                        $is_pdf = isPdf($data->adhar_card);
                                        $image_src = $is_pdf ? $pdfSvg : asset('storage/app/'.$data->adhar_card);
                                    @endphp
                                    <img src="{{ $image_src }}" class="preview-img" alt="{{ $is_pdf ? 'PDF Document' : 'Aadhaar Card' }}">
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
                                    {{-- Placeholder for size --}}
                                </div>
                            </div>
                    
                            @if(isset($data->adhar_card))
                                <button class="remove" type="button" onclick="removeFile('adhar_card', this)" style="display: block;">×</button>
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
                    
                        <div class="preview" style="{{ isset($data->driving) ? 'display: flex;' : 'display: none;' }}">
                            <div class="img-wrap">
                                @if(isset($data->driving))
                                    @php
                                        $is_pdf = isPdf($data->driving);
                                        $image_src = $is_pdf ? $pdfSvg : asset('storage/app/'.$data->driving);
                                    @endphp
                                    <img src="{{ $image_src }}" class="preview-img" alt="{{ $is_pdf ? 'PDF Document' : 'Driving License' }}">
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
                                    {{-- Placeholder for size --}}
                                </div>
                            </div>
                    
                            @if(isset($data->driving))
                                <button class="remove" type="button" onclick="removeFile('driving', this)" style="display: block;">×</button>
                            @endif
                        </div>
                    
                        <div class="max-size">(Max Size: 20mb)</div>
                    </div>
                </div>

                <div class="form-rowss">
                    <div class="doc-col" style="width:100%;">
                        <h2>Profile Picture Upload <span class="required">*</span></h2>
                        <div class="upload-box profile-upload-box">
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
                        <div class="preview profile-preview" style="{{ isset($data->profile) ? 'display: flex;' : 'display: none;' }}">
                            <div class="img-wrap">
                                @if(isset($data->profile))
                                    <img src="{{ asset('storage/app/' . $data->profile) }}" id="user_profile" class="preview-img" alt="Profile Picture">
                                    <input type="hidden" name="old_profile" value="{{ $data->profile }}">
                                @else
                                    <img class="preview-img" id="user_profile" src="" alt="">
                                @endif
                                <div class="zoom">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 8v6m-3-3h6"/></svg>
                                </div>
                            </div>
                            <div class="info">
                                <div class="name">{{ $originalNames['profile'] ?? '' }}</div>
                                <div class="size">
                                    {{-- Placeholder for size --}}
                                </div>
                            </div>
                            @if(isset($data->profile))
                                <button class="remove" type="button" onclick="removeFile('profile', this)" style="display: block;">×</button>
                            @endif
                        </div>
                        <div class="max-size">(Max Size: 20mb)</div>
                    </div>
                </div>
                
                
                <hr class="line-hr">
                <div class="extra-documents">
                    <h2>Extra Documents</h2>

                    <div id="extraDocumentsContainer">
                            @if(isset($extra) && count($extra) > 0)
                                @foreach($extra as $key => $doc)
                                    <div class="extra-doc-row" style="margin-bottom: 15px;">
                                        <div class="extra-doc-fields" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                                            <input type="hidden" name="id[]" value="{{ $doc->id }}">
                                                <input type="text" name="title[]" class="form-control extra-doc-name" value="{{ $doc->title }}" required style="flex:1; min-width:200px;">
                                    <div class="doc-col">
                                        <div class="upload-box" style="{{ !empty($doc->image) ? 'display: none;' : 'display: block;' }}">
                                            <input 
                                                type="file" 
                                                class="file-input form-control" 
                                                name="image[]" 
                                                accept="image/*,.pdf"
                                                data-existing="{{ !empty($doc->image) ? '1' : '0' }}" {{-- Mark existing image status --}}
                                            >
                                            <div class="upload-content">
                                                <span class="upload-text">Choose File</span>
                                                <div class="upload-icons">
                                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
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

                                        <div class="preview" style="{{ !empty($doc->image) ? 'display: flex;' : 'display: none;' }}">
                                            <div class="img-wrap">
                                                @if(!empty($doc->image))
                                                    @php
                                                        $is_pdf = isPdf($doc->image);
                                                        $image_src = $is_pdf ? $pdfSvg : asset('storage/app/'.$doc->image);
                                                    @endphp
                                                    <img src="{{ $image_src }}" class="preview-img" alt="{{ $is_pdf ? 'PDF Document' : 'Extra Document' }}">
                                                    <input type="hidden" name="old_image[]" value="{{ $doc->image }}">
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
                                                <div class="name">{{ $doc->title ?? basename($doc->image ?? '') }}</div>
                                                <div class="size">
                                                    {{-- Placeholder for size --}}
                                                </div>
                                            </div>

                                            {{-- The remove button is handled by the general JS logic --}}
                                            <button type="button" class="remove" style="{{ !empty($doc->image) ? 'display: block;' : 'display: none;' }}">×</button> 
                                        </div>

                                        <div class="max-size">(Max Size: 20mb)</div>
                                    </div>
                            
                                {{-- Remove button for the row --}}
                                <button type="button" class="btn btn-danger removeDocBtn" data-doc-id="{{ $doc->id }}">Remove</button>
                            </div>
                        </div>
                            @endforeach
                        @endif
                    </div>

                    <button type="button" class="btn btn-secondary" id="addExtraDocumentBtn" style="margin-top:10px;">
                        + Add Extra Document
                    </button>
                </div>

                <div class="terms-container">
                    <label><strong>Terms and Conditions</strong></label>
                    <div class="terms-text">{!! setting('terms_condition') !!}</div>
                    <div class="checkbox-container">
                        <input type="checkbox" id="acceptTerms" name="terms_accepted"
                            {{ (!isset($data) || ($data->terms_accepted ?? 1) == 1) ? 'checked' : '' }} required> {{-- Added safety check for $data->terms_accepted --}}
                        <label for="acceptTerms">Yes, I have read and agree <span class="required">*</span></label>
                    </div>
                </div>
            </div>

            <div class="form-buttons">
                <button type="button" class="btn btn-secondary" id="backBtn" style="display:none">Back</button>
                <button type="button" class="btn btn-primary" id="nextBtn">Next Step</button>
                <button type="button" class="btn btn-ghost" id="previewBtn" style="display:none!important"></button>
                <button type="submit" class="btn btn-primary" id="submitBtn" style="display:none">Submit Application</button>
            </div>
        </form>
    </div>

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
  <div id="img-popup">
                <span id="close-popup">&times;</span>
                <img id="popup-img" src="" alt="">
   </div>


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
    <div id="modalOverlay" 
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
               background:rgba(0,0,0,0.6); z-index:9999;">
        
    </div> {{-- Overlay for modal --}}




<!--Script code start here-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- Assuming registration_new.js contains the multi-step form logic (next/back/preview) --}}
    <script src="{{ asset('public/userpanel') }}/asset/js/registration_new.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
    
    <script>
        // Pass the PHP PDF SVG to JS for consistent rendering
        window.pdfSvgDataUrl = @json($pdfSvg);
        window.extraDocuments = @json($extra); // Added null coalesce for safety
        
      
        // =========================================================================
        // Telephone Country Code Script 
        // =========================================================================
        const phoneInput = document.getElementById('phone');
        const codeInput = document.getElementById('telephone_code');
        const profileInput = document.querySelector('input[name="profile"]');

        let initialCountry = 'in';
        if (codeInput.value) {
            // Check if window.intlTelInputGlobals is available before accessing it
            if (window.intlTelInputGlobals) {
                const countryData = window.intlTelInputGlobals.getCountryData();
                const found = countryData.find(c => `+${c.dialCode}` === codeInput.value);
                if (found) initialCountry = found.iso2;
            }
        }
        
        // This relies on the utils.js being loaded, which is included above.
        const iti = window.intlTelInput(phoneInput, {
            initialCountry: initialCountry,
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
        });
        
        if (phoneInput.value) {
            // Attempt to set the number, which will automatically adjust the flag
            iti.setNumber(phoneInput.value);
        }

        phoneInput.addEventListener('countrychange', () => {
            codeInput.value = '+' + iti.getSelectedCountryData().dialCode;
        });

        codeInput.value = iti ? ('+' + iti.getSelectedCountryData().dialCode) : (codeInput.value || '+91'); 
        phoneInput.addEventListener('keypress', function (e) {
            const charCode = e.which ? e.which : e.keyCode;
            if (charCode < 48 || charCode > 57) {
                e.preventDefault();
            }
        });
        phoneInput.addEventListener('paste', function (e) {
            const pasted = (e.clipboardData || window.clipboardData).getData('text');
            if (!/^[0-9]+$/.test(pasted)) {
                e.preventDefault();
            }
        });
        phoneInput.addEventListener('input', function (e) {
            this.value = this.value.replace(/\D/g, '').slice(0, 15);
            toggleNextButton();
        });

        // nextBtn.disabled = true;
        // nextBtn.style.opacity = '0.6';
        // nextBtn.style.cursor = 'not-allowed';

        function toggleNextButton() {
            const len = phoneInput.value.trim().length;
            if (len >= 9 && len <= 15) {
                nextBtn.disabled = false;
                nextBtn.style.opacity = '1';
                nextBtn.style.cursor = 'pointer';
            } else {
                nextBtn.disabled = true;
                nextBtn.style.opacity = '0.6';
                nextBtn.style.cursor = 'not-allowed';
            }
        }

        document.querySelector('form').addEventListener('submit', function (e) {
            const phoneValue = phoneInput.value.trim();
            if (phoneValue.length < 9 || phoneValue.length > 15) {
                e.preventDefault();
                phoneInput.focus();
            }
        });

        const pincodeInput = document.getElementById('pincode');

        pincodeInput.addEventListener('keypress', function (e) {
            const charCode = e.which ? e.which : e.keyCode;
            if (charCode < 48 || charCode > 57) {
                e.preventDefault();
            }
        });

        pincodeInput.addEventListener('paste', function (e) {
            const pasted = (e.clipboardData || window.clipboardData).getData('text');
            if (!/^[0-9]+$/.test(pasted)) {
                e.preventDefault();
            }
        });

        // pincodeInput.addEventListener('input', function () {
        //     this.value = this.value.replace(/\D/g, '').slice(0, 6);
        // });
    </script>

    <script>
        // ============================================================
        // Frontend Validation for Required Fields
        // ============================================================

        // Common reusable validation functions
        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function isValidDate(date) {
            return !isNaN(new Date(date).getTime());
        }

        function showError(input, message) {
            // Remove old error first
            removeError(input);
            const span = document.createElement('span');
            span.className = 'error-msg';
            span.style.color = 'red';
            span.style.fontSize = '13px';
            span.textContent = message;
            input.insertAdjacentElement('afterend', span);
            input.classList.add('is-invalid');
        }

        // Show error message under upload box
        function showImageError(message) {
            removeImageError();
            const error = document.createElement('span');
            error.className = 'error-msg image-error';
            error.style.color = 'red';
            error.style.fontSize = '13px';
            error.style.display = 'block';
            error.style.marginTop = '5px';
            profileInput.closest('.upload-box').insertAdjacentElement('afterend', error);
            error.textContent = message;
        }

        function removeError(input) {
            input.classList.remove('is-invalid');
            const next = input.nextElementSibling;
            if (next && next.classList.contains('error-msg')) next.remove();
        }

        function removeImageError() {
            const existing = document.querySelector('.image-error');
            if (existing) existing.remove();
        }

        // When user selects a file, remove error automatically
        if (profileInput) {
            profileInput.addEventListener('change', () => {
                if (profileInput.files.length > 0) {
                    removeImageError();
                    toggleNextButton(); // recheck all form validations
                } else {
                    showImageError('Please upload a profile picture');
                    toggleNextButton();
                }
            });
        }

        // All required inputs for validation
        const requiredInputs = document.querySelectorAll('#registrationForm input[required], #registrationForm select[required], #registrationForm #address, #registrationForm textarea[required]');
        // const nextBtn = document.querySelector('#nextBtn') || document.querySelector('button[type="submit"]');

        // Main validation checker
        function validateInput(input) {
            const value = input.value.trim();
            let valid = true;

            if (!value) {
                showError(input, 'This field is required');
                valid = false;
            } else if (input.type === 'email' && !isValidEmail(value)) {
                showError(input, 'Enter a valid email address');
                valid = false;
            } else if (input.id === 'pincode' && value.length !== 6) {
                showError(input, 'Pincode must be 6 digits');
                valid = false;
            } else if ((input.id === 'phone' || input.id === 'emergencyPhone') && (value.length < 9 || value.length > 15)) {
                showError(input, 'Enter valid phone number');
                valid = false;
            } else if (input.id === 'dob' && !isValidDate(value)) {
                showError(input, 'Enter valid date of birth');
                valid = false;
            } else {
                removeError(input);
            }

            return valid;
        }

        // Attach live validation
        requiredInputs.forEach(input => {
            input.addEventListener('input', () => {
                validateInput(input);
                toggleNextButton();
            });
            input.addEventListener('blur', () => {
                validateInput(input);
                toggleNextButton();
            });
        });

        // Modify your existing toggleNextButton() to check image too
        const originalToggle = toggleNextButton;
        toggleNextButton = function () {
            let allValid = true;
            requiredInputs.forEach(input => {
                if (!validateInput(input)) allValid = false;
            });

            // Additional check for profile image
            if (submitBtn && submitBtn.style.display !== 'none') { // only check on final step
                if (!profileInput.files.length && !document.querySelector('input[name="old_profile"]')) {
                    allValid = false;
                    showImageError('Please upload a profile picture');
                } else {
                    removeImageError();
                }
            }

            // Enable or disable button
            const activeBtn = document.querySelector('#nextBtn').style.display === 'none' ? submitBtn : nextBtn;
            if (allValid) {
                activeBtn.disabled = false;
                activeBtn.style.opacity = '1';
                activeBtn.style.cursor = 'pointer';
            } else {
                activeBtn.disabled = true;
                activeBtn.style.opacity = '0.6';
                activeBtn.style.cursor = 'not-allowed';
            }
        };

        // Prevent form submit if image missing
        document.querySelector('#registrationForm').addEventListener('submit', (e) => {
            if (!profileInput.files.length && !document.querySelector('input[name="old_profile"]')) {
                e.preventDefault();
                showImageError('Please upload a profile picture');
                profileInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });

        // Toggle button enable/disable
        function toggleNextButton() {
            let allValid = true;
            requiredInputs.forEach(input => {
                if (!validateInput(input)) allValid = false;
            });

            if (allValid) {
                nextBtn.disabled = false;
                nextBtn.style.opacity = '1';
                nextBtn.style.cursor = 'pointer';
            } else {
                nextBtn.disabled = true;
                nextBtn.style.opacity = '0.6';
                nextBtn.style.cursor = 'not-allowed';
            }
        }

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userProfileImg = document.getElementById('user_profile');
            const submitBtn = document.getElementById('submitBtn');
            const uploadBox = document.querySelector('.profile-upload-box');
            const previewBox = document.querySelector('.profile-preview');
            const fileInput = document.querySelector('input[name="profile"]');
            const form = document.getElementById('registrationForm');
            const extraDocsContainer = document.getElementById('extraDocumentsContainer');
            
            // --- Helper functions ---
            function showProfileError() {
                removeProfileError();
                const span = document.createElement('span');
                span.className = 'error-msg profile-error';
                span.style.color = 'red';
                span.style.fontSize = '13px';
                span.textContent = 'Please upload a profile picture';
                previewBox.insertAdjacentElement('afterend', span);
                disableSubmit();
            }

            function removeProfileError() {
                const existing = document.querySelector('.error-msg.profile-error');
                if (existing) existing.remove();
            }

            function showExtraDocError(input) {
                removeExtraDocError(input);
                const span = document.createElement('span');
                span.className = 'error-msg extra-doc-error';
                span.style.color = 'red';
                span.style.fontSize = '13px';
                span.textContent = 'Please upload a document file';
                input.closest('.doc-col').insertAdjacentElement('afterend', span);
                disableSubmit();
            }

            function removeExtraDocError(input) {
                const existing = input.closest('.doc-col').nextElementSibling;
                if (existing && existing.classList.contains('extra-doc-error')) {
                    existing.remove();
                }
            }

            function disableSubmit() {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
                submitBtn.style.cursor = 'not-allowed';
            }

            function enableSubmit() {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            }

            // --- Add Remove Button Dynamically ---
            function ensureRemoveButton() {
                if (!previewBox.querySelector('.remove')) {
                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'remove';
                    removeBtn.type = 'button';
                    removeBtn.innerHTML = '×';
                    removeBtn.style.display = 'block';
                    removeBtn.onclick = function() {
                        fileInput.value = '';
                        userProfileImg.src = '';
                        previewBox.style.display = 'none';
                        removeBtn.remove();
                        // validateProfileImage();
                        validateForm();
                    };
                    previewBox.appendChild(removeBtn);
                }
            }

            // --- Main validation function ---
            function validateProfileImage() {
                const src = userProfileImg.getAttribute('src');
                if (!src || src.trim() === '') {
                    showProfileError();
                    return false; // invalid
                } else {
                    removeProfileError();
                    ensureRemoveButton();
                    return true; // valid
                }
            }

            function validateExtraDocs() {
                let allValid = true;
                const extraRows = extraDocsContainer.querySelectorAll('.extra-doc-row');

                extraRows.forEach(row => {
                    const fileInput = row.querySelector('input[type="file"][name="image[]"]');
                    const oldImage = row.querySelector('input[type="hidden"][name="old_image[]"]');

                    if ((!fileInput || !fileInput.files || fileInput.files.length === 0) && (!oldImage || !oldImage.value)) {
                        showExtraDocError(fileInput);
                        allValid = false;
                    } else {
                        removeExtraDocError(fileInput);
                    }
                });

                return allValid;
            }

            function validateForm() {
                const profileValid = validateProfileImage();
                const extraDocsValid = validateExtraDocs();
                if (profileValid && extraDocsValid) enableSubmit();
                else disableSubmit();
            }

            // --- Run once on page load ---
            // validateProfileImage();

            // --- Listen for file changes dynamically ---
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    if (fileInput.files && fileInput.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            userProfileImg.src = e.target.result;
                            previewBox.style.display = 'flex';
                            validateProfileImage();
                        };
                        reader.readAsDataURL(fileInput.files[0]);
                    } else {
                        userProfileImg.src = '';
                        previewBox.style.display = 'none';
                        validateProfileImage();
                    }
                });
            }

            // Extra document change
            extraDocsContainer.addEventListener('change', function (e) {
                if (e.target.matches('input[type="file"][name="image[]"]')) {
                    validateForm();
                }
            });


            // Validate before submit
            if (form) {
                form.addEventListener('submit', function (e) {
                    if (!validateProfileImage() || !validateExtraDocs()) {
                        e.preventDefault();
                        validateForm();
                        userProfileImg.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
            }
            
            // --- Optional: recheck before submitting ---
           document.getElementById('registrationForm')?.addEventListener('submit', function(e) {
                if (!userProfileImg.getAttribute('src') || userProfileImg.getAttribute('src').trim() === '') {
                    e.preventDefault();
                    showProfileError();
                    userProfileImg.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
            
            validateForm();
        });
    </script>
    
    <script>
    // ============================================================
    // Auto-selected State and Country
    // ============================================================
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
        if (!countryName) {
            $('#state').html('<option value="">Select State</option>');
            return;
        }

        $.ajax({
            url: "{{ route('getState') }}",
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

    // ============================================================
    // File Upload Script
    // ============================================================
    const MAX_SIZE = 20 * 1024 * 1024; // 20MB
    const PDF_SVG_DATA_URL = window.pdfSvgDataUrl;

    function formatSize(bytes) {
        if (bytes === 0) return '0 B';
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(0) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    /**
     * Global function for main document removal (used by inline PHP onclick)
     */
    function removeFile(fieldName, removeButton) {
        const col = removeButton.closest('.doc-col');
        if (!col) return;

        const input = col.querySelector(`.file-input[name="${fieldName}"]`);
        const oldInput = col.querySelector(`input[type="hidden"][name="old_${fieldName}"]`);
        const box = col.querySelector('.upload-box');
        const preview = col.querySelector('.preview');
        const img = col.querySelector('.preview-img');
        const nameEl = col.querySelector('.name');
        const sizeEl = col.querySelector('.size');
        const oldFilePath = oldInput ? oldInput.value : '';

        // Add your hidden customer_id input somewhere in the form
        const customerIdEl = document.querySelector('input[name="customer_id"]');
        const customerId = customerIdEl ? customerIdEl.value : null;

        if (!customerId) {
            console.error("Customer ID not found — add hidden input name='customer_id' in form.");
            return;
        }

        if (!oldFilePath) {
            resetPreview();
            return;
        }

        // Confirmation before delete
        if (typeof customConfirm === 'function') {
            customConfirm(
                "Are you sure you want to permanently delete this document?",
                "Confirm Deletion",
                "⚠️"
            ).then(userConfirmed => {
                if (!userConfirmed) return;

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error("CSRF token not found");
                    return;
                }

                fetch(`{{ route('removeDocsImage') }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        customer_id: customerId,
                        name: fieldName // e.g. "profile", "adhar_card", "gst_certificate"
                    })
                })
                .then(res => res.text())
                .then(data => {
                    if (data.trim() === '1') {
                        if (typeof customAlert === 'function') {
                            customAlert("Document deleted successfully!", "Deleted", "🗑️");
                        }
                        resetPreview();
                    } else {
                        if (typeof customAlert === 'function') {
                            customAlert("Failed to delete document. Please try again.", "Error", "❌");
                        }
                    }
                })
                .catch(err => {
                    console.error(err);
                    if (typeof customAlert === 'function') {
                        customAlert("Something went wrong while deleting.", "Error", "❌");
                    }
                });
            });
        } else {
            console.error("customConfirm is not defined");
        }

        function resetPreview() {
            if (input) input.value = '';
            if (oldInput) oldInput.value = '';
            if (preview) preview.style.display = 'none';
            if (box) box.style.display = 'block';
            if (img) img.src = '';
            if (nameEl) nameEl.textContent = '';
            if (sizeEl) sizeEl.textContent = '';
            if (removeButton) removeButton.style.display = 'none';
        }
    }

    // ============================================================
    // Single Document Column Handler
    // ============================================================
    function initDocCol(col) {
        const input = col.querySelector('.file-input');
        const box = col.querySelector('.upload-box');
        const preview = col.querySelector('.preview');
        const img = col.querySelector('.preview-img');
        const nameEl = col.querySelector('.name');
        const sizeEl = col.querySelector('.size');
        let removeBtn = col.querySelector('.remove');
        const zoomBtn = col.querySelector('.zoom');

        if (!input || !box || !preview || !img || !nameEl || !sizeEl) return;

        // --------------------------------------------
        // Clear Visuals Function
        // --------------------------------------------
        function clearVisuals(e) {
            if (e) e.stopPropagation();

            const contextInput = col.querySelector('.file-input');
            if (contextInput) contextInput.value = '';

            preview.classList.remove('active');
            preview.style.display = 'none';
            box.classList.remove('has-file');
            box.style.display = 'block';
            img.src = '';
            nameEl.textContent = '';
            sizeEl.textContent = '';
            if (removeBtn) removeBtn.style.display = 'none';

            const oldImageInput = col.querySelector('input[name="old_image[]"]');
            if (oldImageInput) oldImageInput.remove();

            const mainDocOldInput = col.querySelector('input[type="hidden"][name^="old_"]');
            if (mainDocOldInput && !mainDocOldInput.name.includes('old_image')) {
                mainDocOldInput.value = '';
            }
        }

        // --------------------------------------------
        // Handle File Uploads
        // --------------------------------------------
        function handleFile(file) {
            if (!file) return;

            if (file.size > MAX_SIZE) {
                if (typeof customAlert === 'function') {
                    customAlert('File exceeds 20MB limit', 'File Too Large', '🛑');
                } else {
                    alert('File exceeds 20MB limit');
                }
                input.value = '';
                clearVisuals();
                return;
            }

            nameEl.textContent = file.name;
            sizeEl.textContent = formatSize(file.size);

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    preview.classList.add('active');
                    box.classList.add('has-file');
                    box.style.display = 'none';
                    preview.style.display = 'flex';
                };
                reader.readAsDataURL(file);
            } else {
                img.src = PDF_SVG_DATA_URL;
                preview.classList.add('active');
                box.classList.add('has-file');
                box.style.display = 'none';
                preview.style.display = 'flex';
            }

            const oldInput = col.querySelector('input[type="hidden"][name^="old_"]');
            if (oldInput && !oldInput.name.includes('old_image')) {
                oldInput.value = '';
            }

            const oldImageInput = col.querySelector('input[name="old_image[]"]');
            if (oldImageInput) oldImageInput.remove();

            // Show remove button dynamically
            if (!removeBtn || removeBtn.getAttribute('onclick')) {
                removeBtn = col.querySelector('.remove');

                if (removeBtn && !removeBtn.getAttribute('onclick')) {
                    const clone = removeBtn.cloneNode(true);
                    removeBtn.parentNode.replaceChild(clone, removeBtn);
                    removeBtn = clone;
                    removeBtn.addEventListener('click', clearVisuals);
                }
                if (removeBtn) removeBtn.style.display = 'block';
            } else if (removeBtn) {
                removeBtn.style.display = 'block';
            }
        }

        // --------------------------------------------
        // Input Event Listeners
        // --------------------------------------------
        input.addEventListener('change', e => {
            const file = e.target.files[0];
            if (file) handleFile(file);
        });

        if (removeBtn && !removeBtn.getAttribute('onclick')) {
            const clone = removeBtn.cloneNode(true);
            removeBtn.parentNode.replaceChild(clone, removeBtn);
            removeBtn = clone;
            removeBtn.addEventListener('click', e => clearVisuals(e));

            if (img && img.getAttribute('src') && img.getAttribute('src').trim() !== '') {
                removeBtn.style.display = 'block';
                preview.style.display = 'flex';
                box.style.display = 'none';
            }
        }

        // --------------------------------------------
        // Drag & Drop
        // --------------------------------------------
        box.addEventListener('dragover', e => {
            e.preventDefault();
            box.classList.add('drag-over');
        });

        box.addEventListener('dragleave', e => {
            e.preventDefault();
            box.classList.remove('drag-over');
        });

        box.addEventListener('drop', e => {
            e.preventDefault();
            box.classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            if (file) handleFile(file);
        });

        // --------------------------------------------
        // Zoom Button
        // --------------------------------------------
        if (zoomBtn) {
            const clone = zoomBtn.cloneNode(true);
            zoomBtn.parentNode.replaceChild(clone, zoomBtn);

            clone.addEventListener('click', e => {
                e.stopPropagation();
                if (img.src && img.src.indexOf('svg+xml') === -1 && img.src !== '') {
                    const popupImg = document.getElementById('popup-img');
                    const imgPopup = document.getElementById('img-popup');
                    if (popupImg && imgPopup) {
                        popupImg.src = img.src;
                        imgPopup.style.display = 'flex';
                    }
                }
            });
        }

        preview.addEventListener('click', e => {
            if (e.target.classList.contains('remove')) return;

            if (img.src && img.src.indexOf('svg+xml') === -1 && img.src !== '') {
                const popupImg = document.getElementById('popup-img');
                const imgPopup = document.getElementById('img-popup');
                if (popupImg && imgPopup) {
                    popupImg.src = img.src;
                    imgPopup.style.display = 'flex';
                }
            }
        });

        // --------------------------------------------
        // Upload Box Visibility Logic
        // --------------------------------------------
        if (img && img.getAttribute('src') && img.getAttribute('src').trim() !== '') {
            preview.classList.add('active');
            preview.style.display = 'flex';
            box.classList.add('has-file');
            box.style.display = 'none';

            const oldName = nameEl.textContent.trim();
            const oldSize = sizeEl.textContent.trim();
            const isPdfPreloaded = img.src === PDF_SVG_DATA_URL;

            if (oldName === '') {
                const fieldName = input ? input.name : (col.querySelector('input[name="old_image[]"]') ? 'Extra Document' : 'Document');
                nameEl.textContent = fieldName.replace(/old_|\[\]/g, '').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            }

            if (oldSize === '' || oldSize === '(Previously uploaded)') {
                sizeEl.textContent = isPdfPreloaded ? 'PDF' : '(Previously uploaded)';
            }

            if (removeBtn) removeBtn.style.display = 'block';
        } else {
            box.style.display = 'block';
            preview.style.display = 'none';
            if (removeBtn) removeBtn.style.display = 'none';
        }
    }

    // ============================================================
    // Global Initialization
    // ============================================================
    function initAllDocCols() {
        document.querySelectorAll('.doc-col').forEach(col => {
            initDocCol(col);
        });
    }

    const closePopupBtn = document.getElementById('close-popup');
    if (closePopupBtn) {
        closePopupBtn.addEventListener('click', e => {
            e.stopPropagation();
            const imgPopup = document.getElementById('img-popup');
            const popupImg = document.getElementById('popup-img');
            if (imgPopup) imgPopup.style.display = 'none';
            if (popupImg) popupImg.src = '';
        });
    }

    // ============================================================
    // Extra Documents Script
    // ============================================================
    document.addEventListener('DOMContentLoaded', function () {
        initAllDocCols();

        let container = document.getElementById('extraDocumentsContainer');
        let addBtn = document.getElementById('addExtraDocumentBtn');

        if (!container || !addBtn) {
            console.warn('Extra documents container or add button not found');
            return;
        }

        const newDocRowTemplate = `
            <div class="extra-doc-fields" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                <input type="text" name="title[]" class="form-control extra-document" placeholder="Document Name" required style="flex:1; min-width:200px;">
                <div class="doc-col">
                    <div class="upload-box" style="display: block;">
                        <input type="file" class="file-input form-control" name="image[]" accept="image/*,.pdf" data-existing="0">
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

                    <div class="preview" style="display: none;">
                        <div class="img-wrap">
                            <img class="preview-img extra-doc-file" src="" alt="">
                            <div class="zoom">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8" stroke-width="2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-4.35-4.35M11 8v6m-3-3h6"/>
                                </svg>
                            </div>
                        </div>

                        <div class="info">
                            <div class="name"></div>
                            <div class="size"></div>
                        </div>

                        <button type="button" class="remove" style="display: none;">×</button>
                    </div>

                    <div class="max-size">(Max Size: 20mb)</div>
                </div>
                <button type="button" class="btn btn-danger removeDocBtn extra-remove-btn">Remove</button>
            </div>
        `;

        addBtn.addEventListener('click', function () {
            let div = document.createElement('div');
            div.classList.add('extra-doc-row');
            div.style.marginBottom = '15px';
            div.innerHTML = newDocRowTemplate;

            container.appendChild(div);

            const newDocCol = div.querySelector('.doc-col');
            if (newDocCol) initDocCol(newDocCol);
        });

        container.addEventListener('click', function (e) {
            if (e.target.classList.contains('removeDocBtn')) {
                let row = e.target.closest('.extra-doc-row');
                let docId = e.target.getAttribute('data-doc-id');

                if (!docId) {
                    row.remove();
                    return;
                }

                if (docId) {
                    if (typeof customConfirm === 'function') {
                        customConfirm("Are you sure you want to delete this document permanently?", "Confirm Deletion", "⚠️")
                            .then(userConfirmed => {
                                if (userConfirmed) {
                                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                                    if (!csrfToken) {
                                        console.error('CSRF token not found');
                                        return;
                                    }

                                    fetch(`{{ route('removeImage') }}`, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken.content,
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({ id: docId })
                                    })
                                    .then(res => res.text())
                                    .then(data => {
                                        if (data.trim() === '1') {
                                            if (typeof customAlert === 'function') {
                                                customAlert("Document deleted successfully!", "Deleted", "🗑️");
                                            }
                                            row.remove();
                                        } else {
                                            if (typeof customAlert === 'function') {
                                                customAlert("Failed to delete document. Please try again.", "Error", "❌");
                                            }
                                        }
                                    })
                                    .catch(err => {
                                        console.error(err);
                                        if (typeof customAlert === 'function') {
                                            customAlert("Something went wrong while deleting.", "Error", "❌");
                                        }
                                    });
                                }
                            });
                    } else {
                        console.error('customConfirm function not defined');
                    }
                }
            }
        });
    });
</script>

  
</body>
</html>