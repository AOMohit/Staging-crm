<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Geologica:wght@100;300&family=Lato&family=Open+Sans&family=Poppins&family=Roboto&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <title>{{ setting('site_name') }}</title>
    <link href="{{ asset('public/userpanel') }}/asset/css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ env('ADMIN_URL') . 'storage/app/' . setting('logo') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <style>
        /*::-webkit-scrollbar {*/
        /*    display: none;*/
        /*}*/

        /* #fieldsContainer{
            display: none;
        } */
    .upload-style-btn{
        border-radius: 11px;
        border: none;
        height: 96px;
        margin-top: 8px;
    }
    .symbol{
        color:red;
    }
    .file-label {
        cursor: pointer;
        display: flexbox;
        padding: 7px;
        background-color: #c834db;
        color: #fff;
        border-radius: 8px;
        margin-bottom: 60px;
        transition: background-color 0.3s;
    }

    .file-label:hover {
        background-color: #73ec10;
    }
    .progress-container {
        margin-top: 6px;
        margin-bottom:30px;
        position: relative;
        height: 20px;
    }

    .progress-bar {
        width: 0;
        height: 20%;
        background-color: #2ecc71;
        border-radius: 5px;
        transition: width 0.3s;
    }

    .progress-text {
        position: absolute;
        top: 0;
        left: 100%;
        transform: translateX(-50%);
        color: #333;
        font-size: 14px;
        display: none;
    }

    .file-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: -40px;
    }
    .view-button {
        border-radius: 5px;
        margin-top:4px;
        margin-left: 90px;
        padding: 1px 7px;
    }
    .file-name {
        color: rgb(19, 2, 255);
    }

    .clear-button {
        margin-top:4px;
        padding: 0px 8px;
        background-color: #e74c3c;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        display: none;
    }

    .clear-button:hover {
        background-color: #c0392b;
    }

    .preview-modal {
            display: none;
            position: fixed;
            z-index: 1050;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }

    .modal-content.uploaded-image-modal {
        margin: auto;
        display: block;
        max-width: 90%;
        max-height: 80vh;
        border-radius: 8px;
    }

    .preview-modal .close {
        position: absolute;
        top: 30px;
        right: 30px;
        color: #fff;
        font-size: 30px;
        font-weight: bold;
        cursor: pointer;
    }

    .preview-modal .close:hover {
        color: #ccc;
    }
    



    </style>
</head>

<body>
    <header class="header-bg ">

    </header>

    <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('public/userpanel') }}/asset/images/logo.png" alt="">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                aria-controls="offcanvasRight" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">

                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

            </div>
            <div class="text-end d-none d-md-block">
                <a href="#" class="btn-btn-header">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19"
                            fill="none">
                            <path
                                d="M9 0C8.01109 0 7.04439 0.293245 6.22215 0.842652C5.3999 1.39206 4.75904 2.17295 4.3806 3.08658C4.00216 4.00021 3.90315 5.00555 4.09607 5.97545C4.289 6.94536 4.7652 7.83627 5.46447 8.53553C6.16373 9.2348 7.05464 9.711 8.02455 9.90393C8.99445 10.0969 9.99979 9.99784 10.9134 9.6194C11.827 9.24096 12.6079 8.6001 13.1573 7.77785C13.7068 6.95561 14 5.98891 14 5C14 3.67392 13.4732 2.40215 12.5355 1.46447C11.5979 0.526784 10.3261 0 9 0ZM9 8C8.40666 8 7.82664 7.82405 7.33329 7.49441C6.83994 7.16476 6.45542 6.69623 6.22836 6.14805C6.0013 5.59987 5.94189 4.99667 6.05764 4.41473C6.1734 3.83279 6.45912 3.29824 6.87868 2.87868C7.29824 2.45912 7.83279 2.1734 8.41473 2.05764C8.99667 1.94189 9.59987 2.0013 10.1481 2.22836C10.6962 2.45542 11.1648 2.83994 11.4944 3.33329C11.8241 3.82664 12 4.40666 12 5C12 5.79565 11.6839 6.55871 11.1213 7.12132C10.5587 7.68393 9.79565 8 9 8ZM18 19V18C18 16.1435 17.2625 14.363 15.9497 13.0503C14.637 11.7375 12.8565 11 11 11H7C5.14348 11 3.36301 11.7375 2.05025 13.0503C0.737498 14.363 0 16.1435 0 18V19H2V18C2 16.6739 2.52678 15.4021 3.46447 14.4645C4.40215 13.5268 5.67392 13 7 13H11C12.3261 13 13.5979 13.5268 14.5355 14.4645C15.4732 15.4021 16 16.6739 16 18V19H18Z"
                                fill="black" />
                        </svg> &nbsp; WELCOME <span class="text-uppercase">
                            @if($data)
                                {{ $data->first_name }}
                                {{ $data->last_name }}
                            @endif

                        </span>
                    </span>
                </a>
            </div>
        </div>
    </nav>

    <div class="wrap mt-5 mb-5">
        <div class="container">
            <div class="col-12">
              
                <div class="row">
                    <div class="col-md-8 col-12 mx-auto d-block">
                        <h1 class="text-center">REGISTRATION FORM</h1>

                        <form action="{{ route('registrationSubmit') }}" method="POST" enctype="multipart/form-data" id="registrationForm">
                            @csrf
    
                            <div class="row">
                            <input type="hidden" name="form_type" value="{{ $form_type }}">
                            @if($data)
                                <input type="hidden" name="email" value="{{ $data->email }}">
                                <div class="col-md-6 col-12 my-2">
                                    <label for="">First Name <span class="symbol">*</span></label>
                                    <input type="text" name="first_name"  value="{{ $data->first_name }}"
                                        placeholder="Full Name" class="form-control" id="">
                                        @error('first_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                    <label for="">Last Name <span class="symbol">*</span></label>
                                    <input type="text" name="last_name"  value="{{ $data->last_name }}"
                                        placeholder="Last Name" class="form-control" id="">
                                        @error('last_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                            @else
                                <div class="col-md-6 col-12 my-2">
                                    <label for="">Traveller <span class="symbol">*</span></label>
                                    <select name="traveller_id"  class="w-100 form-control" id="">
                                        <option value="">—Please choose an option—</option>
                                        @foreach($nonRegUsers as $nonRegUser)
                                            <option value="{{$nonRegUser->id}}">{{$nonRegUser->first_name." ".$nonRegUser->last_name}}</option>
                                        @endforeach
                                    </select>
                                      @error('traveller_id')
                                            <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                            
                                <div class="col-md-6 col-12 my-2">
                                    <label for="">Phone Number <span class="symbol">*</span></label>
                                    <input type="number" name="phone"  value="@if($data){{ $data->phone }}@endif"
                                        placeholder="Phone Number" class="form-control" id="">
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>

                                <div class="col-md-6 col-12 my-2">
                                    <label for="">Choose Your Expedition</label>
                                    <select name="letest_trip" class="w-100 form-control" id="" readonly>
                                        @foreach ($trip as $trips)
                                            @if (request()->trip_id == $trips->id)
                                            <option
                                                 value="{{ request()->trip_id }}" selected  >
                                                {{ $trips->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                @if($data) 
                                <div class="col-md-6 col-12 my-2">
                                    <label for="">E-mail<span class="symbol">*</span></label>
                                    <input type="email" name="email" value="{{ $data->email }}" readonly
                                        placeholder="Email" class="form-control" id="">
                                </div>
                                @else
                                <div class="col-md-6 col-12 my-2">
                                    <label for="">E-mail <span class="symbol">*</span> </label>
                                    <input type="email" name="email"
                                        placeholder="Email" class="form-control" id="">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                @endif
                                @php
                                    $contry = allCountry();
                                    $originalNames = (isset($data) && isset($data->original_filenames)) ? json_decode($data->original_filenames, true) : [];

                                @endphp

                                <div class="col-md-6 col-12 my-2">
                                    <label for="">Country<span class="symbol">*</span></label>
                                    <select name="country" id="country" class="form-control" onchange="getState(this.value)">
                                        <option value="">Select Country</option>
                                        @foreach ($contry as $contrys)
                                            <option value="{{ $contrys->name }}"
                                                @if (isset($data->country) && $contrys->name == $data->country) selected @endif>
                                                {{ $contrys->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                    <label for="">State<span class="symbol">*</span></label>
                                    <input type="hidden" id="selected-State" name="state" value="{{$data->state ?? ''}}">
                                    <select class="form-select" name="state" id="state">
                                        <option value="">Select State</option>
                                    </select>
                                    @error('state')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                    <label for="">City<span class="symbol">*</span></label>
                                    <input type="text" name="city" value="@if(isset($data)){{ $data->city }}@endif" placeholder=""
                                        class="form-control" id="">
                                        @error('city')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="col-md-12 col-12 my-2">
                                    <label for="">Pincode <span class="symbol">*</span></label>
                                    <input type="text" name="pincode" value="@if(isset($data)){{ $data->pincode }}@endif"
                                        placeholder="" class="form-control" id="">
                                        @error('pincode')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="col-md-12 col-12 my-2">
                                    <label for="">Address <span class="symbol">*</span></label>
                                    <textarea name="address" id="" cols="10" rows="3" class="form-control">@if(isset($data)){{ $data->address }}@endif</textarea>
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                    <label for="">Date Of Birth <span class="symbol">*</span></label>
                                    <input type="date" name="dob"  value="@if(isset($data)){{ $data->dob }}@endif"
                                        class="form-control" id="">
                                        @error('dob')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                    <label for="">Profession </label>
                                    <input type="text" name="profession"  value="{{ old('profession', $data->profession ?? '') }}"
                                        class="form-control" id="">
                                        @error('profession')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                    <label for="">Blood Group <span class="symbol">*</span></label>
                                    <select name="blood_group"  class="w-100 form-control" id="">
                                        <option value="">—Please choose an option—</option>
                                        <option @if (isset($data) && $data->blood_group == 'a+') selected @endif value="a+">A+
                                        </option>
                                        <option @if (isset($data) && $data->blood_group == 'a-') selected @endif value="a-">A-
                                        </option>
                                        <option @if (isset($data) && $data->blood_group == 'b+') selected @endif value="b+">B+
                                        </option>
                                        <option @if (isset($data) && $data->blood_group == 'b-') selected @endif value="b-">B-
                                        </option>
                                        <option @if (isset($data) && $data->blood_group == 'o+') selected @endif value="o+">O+
                                        </option>
                                        <option @if (isset($data) && $data->blood_group == 'o-') selected @endif value="o-">O-
                                        </option>
                                        <option @if (isset($data) && $data->blood_group == 'ab+') selected @endif value="ab+">AB+
                                        </option>
                                        <option @if (isset($data) && $data->blood_group == 'ab-') selected @endif value="ab-">AB-
                                        </option>
                                    </select>
                                    @error('blood_group')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                    <label for="">Meal Preference <span class="symbol">*</span></label>
                                    <input type="text"  name="meal_preference"
                                        value="{{ old('meal_preference', $data->meal_preference ?? '') }}" placeholder="" class="form-control"
                                        id="">
                                        @error('meal_preference')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                    <label for="">Choose T-Shirt Size <span class="symbol">*</span></label>
                                    <select name="t_size"  class="w-100 form-control" id=""
                                        >
                                        <option value="">—Please choose an option—</option>
                                        <option @if (isset($data) && $data->t_size == 'Kids') selected @endif value="Kids">Kids
                                        </option>
                                        <option @if (isset($data) && $data->t_size == 'XS') selected @endif value="XS">XS
                                        </option>
                                        <option @if (isset($data) && $data->t_size == 'S') selected @endif value="S">S
                                        </option>
                                        <option @if (isset($data) && $data->t_size == 'M') selected @endif value="M">M
                                        </option>
                                        <option @if (isset($data) && $data->t_size == 'L') selected @endif value="L">L
                                        </option>
                                        <option @if (isset($data) && $data->t_size == 'XL') selected @endif value="XL">XL
                                        </option>
                                        <option @if (isset($data) && $data->t_size == '2XL') selected @endif value="2XL">2XL
                                        </option>
                                        <option @if (isset($data) && $data->t_size == '3XL') selected @endif value="3XL">3XL
                                        </option>
                                    </select>
                                        @error('t_size')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                    <label for="">Medical Condition if any <span class="symbol">*</span></label>
                                    <input type="text"  name="medical_condition"
                                        value=" {{ old('medical_condition', $data->medical_condition ?? '') }}" placeholder="" class="form-control"
                                        id="">
                                        @error('medical_condition')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                    <label for="">Emergency Contact Name <span class="symbol">*</span></label>
                                    <input type="text"  name="emg_name" value="{{ old('emg_name', $data->emg_name ?? '') }}"
                                        placeholder="" class="form-control" id="">
                                        @error('emg_name')
                                        
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                    <label for="">Emergency Contact Number<span class="symbol">*</span> </label>
                                    <input type="number" name="emg_contact" value="{{ old('emg_contact', $data->emg_contact ?? '') }}"
                                        placeholder="" class="form-control" id="">
                                        @error('emg_contact')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>


                                {{-- image --}}
                                <!-- Passport Front Upload -->

                                <div class="col-md-6 col-12 my-2 file-upload-group passport-div">
                                    <label>Passport Upload (Front)</label><br>
                                    <input type="file" name="passport_front" class="form-control file-input">
                                      @if (isset($data->passport_front))
                                        <img src="{{ asset('storage/app/' . $data->passport_front) }}" width="100px" alt=""><br>
                                         <span>{{ $originalNames['passport_front'] ?? '' }}</span>
                                    @endif
                                     <div class="progress-container">
                                        <div class="progress-bar"></div>
                                        <div class="progress-text"></div>
                                    </div>
                                    <div class="file-details">
                                        <div class="file-name"></div>
                                        <div class="preview-container"></div>
                                        <button type="button" class="clear-button">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal preview-modal">
                                        <span class="close">&times;</span>
                                        <img class="modal-content uploaded-image-modal">
                                    </div>
                               
                                   
                                    @error('passport_front')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Passport Back Upload -->
                                <div class="col-md-6 col-12 my-2 file-upload-group">
                                    <label>Passport Upload (Back)</label><br>
                                    <input type="file" name="passport_back" class="form-control file-input">
                                    @if (isset($data->passport_back))
                                        <img src="{{ asset('storage/app/' . $data->passport_back) }}" width="100px" alt=""><br>
                                        <span>{{ $originalNames['passport_back'] ?? '' }}</span>
                                    @endif
                                      <div class="progress-container">
                                        <div class="progress-bar"></div>
                                        <div class="progress-text"></div>
                                    </div>
                                    <div class="file-details">
                                        <div class="file-name"></div>
                                        <div class="preview-container"></div>
                                        <button type="button" class="clear-button">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal preview-modal">
                                        <span class="close">&times;</span>
                                        <img class="modal-content uploaded-image-modal">
                                    </div>
                               

                                   


                                  
                                </div>

                                <!-- PAN / GST Upload -->
                                <div class="col-md-6 col-12 my-2 file-upload-group">
                                    <label>Pan Card </label><br>
                                    <input type="file" name="pan_gst" class="form-control file-input" value="{{ old('pan_gst') }}">
                                    @if (isset($data->pan_gst))
                                        <img src="{{ asset('storage/app/' . $data->pan_gst) }}" width="100px" alt=""><br>
                                        <span>{{ $originalNames['pan_gst'] ?? '' }}</span>
                                    @endif
                                     <div class="progress-container">
                                        <div class="progress-bar"></div>
                                        <div class="progress-text"></div>
                                    </div>
                                    <div class="file-details">
                                        <div class="file-name"></div>
                                        <div class="preview-container"></div>
                                        <button type="button" class="clear-button">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal preview-modal">
                                        <span class="close">&times;</span>
                                        <img class="modal-content uploaded-image-modal">
                                    </div>
                                    

                                   
                                    @error('pan_gst')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- GST certificate --}}
                                <div class="col-md-6 col-12 my-2 file-upload-group">
                                    <label>GST Certificate (For Billing Purpose) </label><br>
                                    <input type="file" name="gst_certificate" class="form-control file-input" value="{{ old('gst_certificate') }}">
                                     @if (isset($data->gst_certificate))
                                        <img src="{{ asset('storage/app/' . $data->gst_certificate) }}" width="100px" alt="">
                                        <span>{{ $originalNames['gst_certificate'] ?? '' }}</span>
                                    @endif
                                     <div class="progress-container">
                                        <div class="progress-bar"></div>
                                        <div class="progress-text"></div>
                                    </div>
                                    <div class="file-details">
                                        <div class="file-name"></div>
                                        <div class="preview-container"></div>
                                        <button type="button" class="clear-button">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal preview-modal">
                                        <span class="close">&times;</span>
                                        <img class="modal-content uploaded-image-modal">
                                    </div>
                                    @error('gst_certificate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Aadhaar Card Upload -->
                                <div class="col-md-6 col-12 my-2 file-upload-group">
                                    <label>Aadhaar Card Upload</label><br>
                                    <input type="file" name="adhar_card" class="form-control file-input">
                                    @if (isset($data->adhar_card))
                                        <img src="{{ asset('storage/app/' . $data->adhar_card) }}" width="100px" alt=""><br>
                                        <span>{{ $originalNames['adhar_card'] ?? '' }}</span>
                                    @endif
                                    <div class="progress-container">
                                        <div class="progress-bar"></div>
                                        <div class="progress-text"></div>
                                    </div>
                                    <div class="file-details">
                                        <div class="file-name"></div>
                                        <div class="preview-container"></div>
                                        <button type="button" class="clear-button">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal preview-modal">
                                        <span class="close">&times;</span>
                                        <img class="modal-content uploaded-image-modal">
                                    </div>
                                  
                                </div>

                                <!-- Driving License Upload -->
                                <div class="col-md-6 col-12 my-2 file-upload-group">
                                    <label>Driving License Upload</label><br>
                                    <input type="file" name="driving" class="form-control file-input">
                                      @if (isset($data->driving))
                                        <img src="{{ asset('storage/app/' . $data->driving) }}" width="100px" alt=""><br>
                                         <span>{{ $originalNames['driving'] ?? '' }}</span>
                                    @endif
                                    <div class="progress-container">
                                        <div class="progress-bar"></div>
                                        <div class="progress-text"></div>
                                    </div>
                                    <div class="file-details">
                                        <div class="file-name"></div>
                                        <div class="preview-container"></div>
                                        <button type="button" class="clear-button">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal preview-modal">
                                        <span class="close">&times;</span>
                                        <img class="modal-content uploaded-image-modal">
                                    </div>
                                </div>

                                <!-- Profile Picture Upload -->
                                <div class="col-md-6 col-12 my-2 file-upload-group">
                                    <label>Profile Picture Upload (Candid) <span class="symbol">*</span> (Max Size: 3mb)</label><br>
                                    <input type="file" name="profile" class="form-control file-input" value="{{ old('profile') }}">
                                     @if (isset($data->profile))
                                        <img src="{{ asset('storage/app/' . $data->profile) }}" width="100px" alt=""><br>
                                         <span>{{ $originalNames['profile'] ?? '' }}</span>
                                    @endif
                                     <div class="progress-container">
                                        <div class="progress-bar"></div>
                                        <div class="progress-text"></div>
                                    </div>
                                    <div class="file-details">
                                        <div class="file-name"></div>
                                        <div class="preview-container"></div>
                                        <button type="button" class="clear-button">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal preview-modal">
                                        <span class="close">&times;</span>
                                        <img class="modal-content uploaded-image-modal">
                                    </div>
                                    @error('profile')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 mt-4 file-upload-group">
                                    <h3 class="mb-0">Extra Documents<small> (Like visa etc.)</small></h3><hr>
                                        <div id="editField">
                                            @if(count($extra) > 0)
                                                @foreach ($extra as $data)
                                                    <div class="row field-edit mb-2 file-upload-group" id="docImage{{ $data->id }}">
                                                        <input type="hidden" name="id[]" value="{{ $data->id }}">

                                                        <div class="col-md-4 my-2">
                                                            <label>Document Name</label>
                                                            <input type="text" name="title[]" value="{{ $data->title }}" class="form-control">
                                                        </div>

                                                        <div class="col-md-4 my-2">
                                                            <label>File (Max Size: 3mb)</label>
                                                            <input type="file" name="image[]" class="form-control file-input">
                                                            <div class="progress-container">
                                                                <div class="progress-bar"></div>
                                                                <div class="progress-text"></div>
                                                            </div>

                                                            <div class="file-details">
                                                                <div class="file-name"></div>
                                                                <div class="preview-container" ></div>
                                                                <button type="button" class="clear-button"  style="margin-left:8px;">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                            <div class="modal preview-modal">
                                                                <span class="close">&times;</span>
                                                                <img class="modal-content uploaded-image-modal">
                                                            </div>
                                                       
                                                        </div>
                                                       
                                                        <div class="col-md-2">
                                                            <img src="{{ asset('storage/app/' . $data->image) }}" alt="" width="100px" style=" margin-top: 18px;">
                                                        </div>

                                                        <div class="col-md-2 d-flex">
                                                            <a href="javascript:void(0)" onclick="removeFieldEdit({{ $data->id }})" class="btn btn-danger mt-4 w-100" style="height: fit-content; margin-top:31px !important;">Remove</a>
                                                        </div>
                                                      
                                                        
                                                        <div id="editAddContainer">
                                                                <div id="editDynamicFields"></div>

                                                                <div class="text-end mb-3">
                                                                    <button type="button" class="btn btn-success" onclick="addNewExtraField(true)">Add More</button>
                                                                </div>
                                                        </div>
                                                         
                                                        
                                                    </div>
                                                    
                                                @endforeach
                                            
                                            @else
                                                <div class="mb-3 docubutton " id="styledAddDocumentWrapper">
                                                    <button type="button" id="showAddExtraBtn" onclick="showAddExtraForm()" class="upload-style-btn w-100">
                                                        <i class="fas fa-file"></i>
                                                        <span>Click to Add Extra Document</span>
                                                    </button>
                                                </div>
                                         
                                                <div id="addExtraFormContainer"  style="display: none;">
                                                    <div id="addExtraFormFields" >
                                                        
                                                    </div>
                                                    <div class="text-end mb-3" id="addmore">
                                                        <button type="button" class="btn btn-success" onclick="addNewExtraField(false)">Add More</button>
                                                    </div>
                                                   
                                                </div>
                                                
                                            @endif
                                        </div>
                                </div>


                                <div class="col-md-12 col-12 my-2 mt-3">
                                    <label for="">Terms and Conditions <span class="symbol">*</span></label>
                                    <div class="border border-secondary p-3 mt-1 rounded"
                                        style="max-height:250px;overflow-y:scroll;">
                                        {!! setting('terms_condition') !!}
                                    </div>
                                </div>
           
                                <div class="col-md-12 col-12 my-2">
                                    <input type="checkbox" name="terms_accepted" id="" @if (!isset($data) || $data->terms_accepted == 1) checked @endif> Yes, I have read and agree to the Terms of Service.
                                </div>

                                {{-- <div class="col-md-12 col-12 my-2">
                                    <input type="hidden" name="booking_id" value="{{ request()->token }}">
                                    <!--@if (isset($tripData) && isset($tripData->donation_amt))-->
                                    <!--    <input type="checkbox" name="carbon_accepted"-->
                                    <!--        @if ($carbonData && $carbonData->carbon_accepted == 1) checked @endif> I agree to donate-->
                                    <!--    Rs. {{ $tripData->donation_amt ?? 0 }} to plant {{ $tripData->tree_no ?? 0 }}-->
                                    <!--    trees-->
                                    <!--    and offset-->
                                    <!--    the-->
                                    <!--    carbon footprint-->
                                    <!--    generated by my road trip.-->
                                    <!--@endif-->
                                </div> --}}

                            </div>
                            <button class="button rounded-pill p-2 px-5 mt-2">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.file-upload-group').forEach(group => {
                initializeFileUploadFeatures(group);
            });
        });
    </script>
    <script>
       

        function initializeFileUploadFeatures(group) {
            const input = group.querySelector('.file-input');
            const progressBar = group.querySelector('.progress-bar');
            const progressText = group.querySelector('.progress-text');
            const fileName = group.querySelector('.file-name');
            const previewContainer = group.querySelector('.preview-container');
            const clearButton = group.querySelector('.clear-button');
            const modal = group.querySelector('.preview-modal');
            const modalImage = group.querySelector('.uploaded-image-modal');
            const closeModal = group.querySelector('.close');

            if (clearButton) clearButton.type = "button";

            input.addEventListener('change', (event) => {
                const file = event.target.files[0];
                if (!file) return;

                const allowedTypes = [
                    'image/jpeg', 'image/png', 'image/jpg', 'application/pdf',
                    'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ];
                if (!allowedTypes.includes(file.type)) {
                    alert('Invalid file type.');
                    input.value = '';
                    return;
                }

                progressBar.style.width = '0%';
                progressBar.style.display = 'block';
                progressText.style.display = 'block';
                progressText.innerText = '0%';
                previewContainer.style.display = 'none';
                clearButton.style.display = 'none';

                const uploadTime = 2000;
                const interval = 50;
                const steps = uploadTime / interval;
                let currentStep = 0;

                const updateProgress = () => {
                    const progress = (currentStep / steps) * 100;
                    progressBar.style.width = `${progress}%`;
                    progressText.innerText = `${Math.round(progress)}%`;
                    currentStep++;
                    if (currentStep <= steps) {
                        setTimeout(updateProgress, interval);
                    } else {
                        progressBar.style.width = '100%';
                        progressText.style.display = 'none';
                        fileName.innerText = file.name;

                        let isImage = file.type.startsWith('image/');
                        if (isImage) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewContainer.innerHTML = `<button type="button" class="btn btn-primary btn-sm view-button"><i class="fa fa-eye"></i></button>`;
                                previewContainer.style.display = 'block';
                                clearButton.style.display = 'block';
                                progressBar.style.display = 'none';
                                $('.extra-error').hide();

                                const viewBtn = group.querySelector('.view-button');
                                viewBtn.onclick = () => {
                                    modal.style.display = 'block';
                                    modalImage.src = e.target.result;
                                    modalImage.style.display = 'block';
                                };
                            };
                            reader.readAsDataURL(file);
                        } else {
                            previewContainer.innerHTML = `<button type="button" class="btn btn-primary btn-sm view-button"><i class="fa fa-eye"></i></button>`;
                            previewContainer.style.display = 'block';
                            clearButton.style.display = 'block';
                            progressBar.style.display = 'none';

                            const viewBtn = group.querySelector('.view-button');
                            viewBtn.onclick = () => {
                                const fileURL = URL.createObjectURL(file);
                                window.open(fileURL, '_blank');
                            };
                        }
                    }
                };
                updateProgress();
            });

            clearButton.addEventListener('click', (e) => {
                e.preventDefault();
                input.value = '';
                progressBar.style.width = '0%';
                progressBar.style.display = 'none';
                progressText.style.display = 'none';
                fileName.innerText = '';
                previewContainer.style.display = 'none';
                clearButton.style.display = 'none';
                modalImage.src = '';
                modal.style.display = 'none';
            });

            closeModal.addEventListener('click', () => {
                modal.style.display = 'none';
                modalImage.src = '';
            });

            window.addEventListener('click', (event) => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                    modalImage.src = '';
                }
            });
        }



    </script>
    
    <script>
        let extraFieldCount = 0;

        function showAddExtraForm() {
            document.getElementById('addExtraFormContainer').style.display = 'block';
            document.getElementById('showAddExtraBtn').style.display = 'none';
            addNewExtraField(false);
        }
        function addNewExtraField(isEditMode = false) {
            extraFieldCount++;

            const div = document.createElement('div');
            div.classList.add('row', 'field', 'mb-2');
            div.id = 'extraField' + extraFieldCount;

            // ✅ Full HTML with all required elements
            div.innerHTML = `
                <div class="col-md-5 my-2">
                    <label>Document Name</label>
                    <input type="text" name="title[]" class="form-control">
                </div>

                <div class="col-md-5 my-2 file-upload-group">
                    <label>File  </label>
                    <input type="file" name="image[]" class="form-control file-input">

                    <div class="progress-container">
                        <div class="progress-bar"></div>
                        <div class="progress-text"></div>
                    </div>

                    <div class="file-details">
                        <div class="file-name"></div>
                        <div class="preview-container"></div>
                        <button type="button" class="clear-button">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="modal preview-modal">
                        <span class="close">&times;</span>
                        <img class="modal-content uploaded-image-modal">
                    </div>
                </div>

                <div class="col-md-2 d-flex">
                    <a href="javascript:void(0)" onclick="removeExtraField('${div.id}', ${isEditMode})" class="btn btn-danger mt-4 w-100" style="height: fit-content; margin-top:31px !important;">Remove</a>
                </div>
            `;

            if (isEditMode) {
                document.getElementById('editDynamicFields').appendChild(div);
            } else {
                document.getElementById('addExtraFormFields').appendChild(div);
            }

            // ✅ Important: Initialize file upload for this newly added field
            initializeFileUploadFeatures(div.querySelector('.file-upload-group'));
        }

        function removeExtraField(id, isEditMode = false) {  
            const el = document.getElementById(id);
            if (el) el.remove();
            

            if (!isEditMode) {
                const remaining = document.querySelectorAll('#addExtraFormFields .field');
                if (remaining.length === 0) 
                {
                    document.getElementById('addExtraFormContainer').style.display = 'none';
                    document.getElementById('showAddExtraBtn').style.display = 'block';
                }
               
            }
        }
    </script>



    <script>
        // var fieldCounter = 0;

        function removeFieldEdit(id) {
          
            var fields = document.querySelectorAll('.field-edit');
            $.ajax({
                url: "{{ route('removeImage') }}",
                type: 'post',
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}",
                },

                success: function(responce) {
                    if (responce == 1) {
                        $('#docImage' + id).remove();
                        $('#showAddExtraBtn').show();
                        toastr.success("Document Remove Successfully");
                        setTimeout(function() {
                         location.reload();
                        }, 2000);
                      
                    }
                     

                }
            });
        }
    </script>
    <script>
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
    </script>

    <script>
        $(document).ready(function() {
            const allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc'];

            function isValidFileType(filename) {
                if (!filename) return false;
                const ext = filename.split('.').pop().toLowerCase();
                return allowedExtensions.includes(ext);
            }

            $('#registrationForm').on('submit', function(e) {
                let isValid = true;
                $('.extra-error').remove();

                // Add mode fields
                $('#addExtraFormFields .field').each(function() {
                    let title = $(this).find('input[name="title[]"]').val();
                    let imageInput = $(this).find('input[name="image[]"]')[0];
                    let image = imageInput ? imageInput.value : '';

                    if (!title) {
                        isValid = false;
                        $(this).find('input[name="title[]"]').after('<span class="text-danger extra-error">Document Name is required.</span>');
                    }
                    if (!image) {
                        isValid = false;
                        $(this).find('input[name="image[]"]').after('<span class="text-danger extra-error">File is required.</span>');
                    } else if (!isValidFileType(image)) {
                        isValid = false;
                        $(this).find('input[name="image[]"]').after('<span class="text-danger extra-error">Only PDF, JPG, JPEG, PNG, DOC files allowed.</span>');
                    } else if (imageInput && imageInput.files && imageInput.files[0] && imageInput.files[0].size > 3 * 1024 * 1024) {
                        isValid = false;
                        $(this).find('input[name="image[]"]').after('<span class="text-danger extra-error">File size must not exceed 3 MB.</span>');
                    }
                });

                // Edit mode fields
                $('.field-edit').each(function() {
                    let title = $(this).find('input[name="title[]"]').val();
                    let imageInput = $(this).find('input[name="image[]"]')[0];
                    let image = imageInput ? imageInput.value : '';

                    // Document Name required in edit mode also
                    if (!title) {
                        isValid = false;
                        $(this).find('input[name="title[]"]').after('<span class="text-danger extra-error">Document Name is required.</span>');
                    }
                    // File required nahi hai, lekin agar select kiya hai to validate karo
                    if (image) {
                        if (!isValidFileType(image)) {
                            isValid = false;
                            $(this).find('input[name="image[]"]').after('<span class="text-danger extra-error">Only PDF, JPG, JPEG, PNG, DOC files allowed.</span>');
                        } else if (imageInput && imageInput.files && imageInput.files[0] && imageInput.files[0].size > 3 * 1024 * 1024) {
                            isValid = false;
                            $(this).find('input[name="image[]"]').after('<span class="text-danger extra-error">File size must not exceed 3 MB.</span>');
                        }
                    }
                });

                // EditDynamicFields (edit mode me add kiye gaye extra fields)
                $('#editDynamicFields .field').each(function() {
                    let title = $(this).find('input[name="title[]"]').val();
                    let imageInput = $(this).find('input[name="image[]"]')[0];
                    let image = imageInput ? imageInput.value : '';

                    if (!title) {
                        isValid = false;
                        $(this).find('input[name="title[]"]').after('<span class="text-danger extra-error">Document Name is required.</span>');
                    }
                    if (!image) {
                        isValid = false;
                        $(this).find('input[name="image[]"]').after('<span class="text-danger extra-error">File is required.</span>');
                    } else if (!isValidFileType(image)) {
                        isValid = false;
                        $(this).find('input[name="image[]"]').after('<span class="text-danger extra-error">Only PDF, JPG, JPEG, PNG, DOC files allowed.</span>');
                    } else if (imageInput && imageInput.files && imageInput.files[0] && imageInput.files[0].size > 3 * 1024 * 1024) {
                        isValid = false;
                        $(this).find('input[name="image[]"]').after('<span class="text-danger extra-error">File size must not exceed 3 MB.</span>');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: $(".extra-error:first").offset().top - 100
                    }, 500);
                }
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
   
    <script>
        @if (Session::has('message'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.success("{{ session('message') }}");
        @endif

        @if (Session::has('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.error("{{ session('error') }}");
        @endif

        @if (Session::has('info'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.info("{{ session('info') }}");
        @endif

        @if (Session::has('warning'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>

</body>

</html>
