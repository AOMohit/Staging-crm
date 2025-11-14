@extends('layouts.userlayout.index')
<style>
    label {
        margin-top: 10px;
    }
</style>



@php
    $contry = allCountry();
@endphp
@section('container')
    <div class="col-md-10 col-12">
        <div class="border-shadow">
            <div class="card px-3">
                <div class="card-header bg-white information">
                    @include('layouts.userlayout.card-header')
                </div>
                <div class=" ">
                    <div class="">
                        <div class="p-2">
                            <div class="col-12">
                                <div class="row mt-4">
                                    <h6 class="fw-bold textss pb-3">
                                        <i class="fa-solid fa-user"></i> Profile Information
                                    </h6>
                                       <div class="profile-glass-card">

                                        @if (isset(Auth::user()->profile))
        
                                            <img src="{{ asset('storage/app/' . Auth::user()->profile) }}" alt="Profile Picture" class="profile-image">
                                        @else
                                            @php
                                          
                                                $initials = '';
                                                if (Auth::user()->first_name && Auth::user()->last_name) {
                                                    $initials = strtoupper(Auth::user()->first_name[0] . Auth::user()->last_name[0]);
                                                } elseif (Auth::user()->first_name) {
                                                    $initials = strtoupper(Auth::user()->first_name[0]);
                                                } elseif (Auth::user()->last_name) {
                                                    $initials = strtoupper(Auth::user()->last_name[0]);
                                                } else {
                                                    $initials = 'N/A';
                                                }
                                            @endphp
                                            <div class="profile-initials">
                                                <p>{{ $initials }}</p>
                                            </div>
                                       
                                           
                                        @endif


                                        <div class="info-grid">
                                            <div class="box"><h6>First Name</h6><p>{{ Auth::user()->first_name ?? "-" }}</p></div>
                                            <div class="box"><h6>Last Name</h6><p>{{ Auth::user()->last_name ?? "-" }}</p></div>
                                            <div class="box"><h6>Email</h6><p>{{ Auth::user()->email ?? "-" }}</p></div>
                                            <div class="box"><h6>Phone</h6><p>{{ Auth::user()->phone ?? "-" }}</p></div>
                                            <div class="box"><h6>Country</h6><p>{{ Auth::user()->country ?? "-" }}</p></div>
                                            <div class="box"><h6>State</h6><p>{{ Auth::user()->state ?? "-" }}</p></div>
                                            <div class="box"><h6>City</h6><p>{{ Auth::user()->city ?? "-" }}</p></div>
                                            <div class="box"><h6>Pincode</h6><p>{{ Auth::user()->pincode ?? "-" }}</p></div>
                                            <div class="box"><h6>Address</h6><p>{{ Auth::user()->address ?? "-" }}</p></div>
                                            <div class="box"><h6>Date of Birth</h6><p>{{ Auth::user()->dob ?? "-" }}</p></div>
                                            <div class="box"><h6>Blood Group</h6><p>{{ Auth::user()->blood_group ?? "-" }}</p></div>
                                            <div class="box"><h6>Meal Preference</h6><p>{{ Auth::user()->meal_preference ?? "-" }}</p></div>
                                            <div class="box"><h6>T-shirt Size</h6><p>{{ Auth::user()->t_size ?? "-" }}</p></div>
                                            <div class="box"><h6>Medical Condition</h6><p>{{ Auth::user()->medical_condition ?? "-" }}</p></div>
                                            <div class="box"><h6>Emergency Contact</h6><p>{{ Auth::user()->emg_contact ?? "-" }}</p></div>
                                            <div class="box"><h6>Emergency Name</h6><p>{{ Auth::user()->emg_name ?? "-" }}</p></div>
                                            
                                        </div>

                                        <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                         Edit Profile
                                        </button>
                                    </div>
                                                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
                                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text fw-bold" id="staticBackdropLabel">Profile
                                                        Update</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('profile.update') }}" method="post"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        {{-- @method('patch') --}}
                                                        <div class="row p-2">
                                                            <div class="col-md-6 col-12">
                                                                <div>
                                                                    <label for="">First Name <span class="text-danger">*</span></label>
                                                                    <input type="text" required name="first_name"
                                                                        value="{{ Auth::user()->first_name }}"
                                                                        class="form-control" id="">
                                                                </div>
                                                                <div>
                                                                    <label for="">Email Id </label>
                                                                    <input type="text"
                                                                        value="{{ Auth::user()->email }}" readonly
                                                                        class="form-control" id="">
                                                                </div>
                                                                <div>
                                                                    <label for="">Phone No  <span class="text-danger">*</span> </label>
                                                                    <input type="text" required name="phone"
                                                                        value="{{ Auth::user()->phone }}"
                                                                        class="form-control" id="">
                                                                </div>
                                                                <div>
                                                                    <label for="">Country</label>
                                                                    <select name="country" id=""
                                                                        class="form-control"
                                                                        onchange="getState(this.value)">
                                                                        <option>Select Country</option>
                                                                        @foreach ($contry as $contrys)
                                                                            <option @if(isset(Auth::user()->country) && $contrys->name == Auth::user()->country) value="{{ Auth::user()->country }}" selected @else value="{{ $contrys->name }}" @endif>
                                                                                {{ $contrys->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    {{-- <input type="text" name="country" value="{{Auth::user()->country}}" class="form-control" id=""> --}}
                                                                </div>
                                                                <div>
                                                                    <label for="">State</label>
                                                                    <select class="form-select" name="state" id="state" onfocus="getState(document.querySelector('[name=country]').value)">
                                                                        @if(isset(Auth::user()->state)) 
                                                                        <option value="{{ Auth::user()->state }}" selected>
                                                                                {{ Auth::user()->state }}
                                                                        </option>
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                                <div>
                                                                    <label for="">City</label>
                                                                    <input type="text" name="city"
                                                                        value="{{ Auth::user()->city }}"
                                                                        class="form-control" id="">
                                                                </div>
                                                                <div>
                                                                    <label for="">Address</label>
                                                                    <input type="text" name="address"
                                                                        value="{{ Auth::user()->address }}"
                                                                        class="form-control" id="">
                                                                </div>
                                                                <div>
                                                                    <label for="">Pincode</label>
                                                                    <input type="text" name="pincode"
                                                                        value="{{ Auth::user()->pincode }}"
                                                                        class="form-control" id="">
                                                                </div>

                                                                <div>
                                                                    <label for="">Date of Birth</label>
                                                                    <input type="date" name="dob"
                                                                        value="{{ Auth::user()->dob }}"
                                                                        class="form-control" id="">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-6 col-12">
                                                                <div>
                                                                    <label for="">Last Name  <span class="text-danger">*</span></label>
                                                                    <input type="text" required name="last_name"
                                                                        value="{{ Auth::user()->last_name }}"
                                                                        class="form-control" id="">
                                                                </div>
                                                                <div>
                                                                    <label for="">Meal Preference</label>
                                                                    <input type="text" name="meal_preference"
                                                                        value="{{ Auth::user()->meal_preference }}"
                                                                        class="form-control" id="">
                                                                </div>
                                                                <div>
                                                                    <label for="">Blood Group</label>
                                                                    <select name="blood_group" class="w-100 form-control"
                                                                        id="" >
                                                                        <option value="">—Please choose an option—
                                                                        </option>
                                                                        <option
                                                                            @if (Auth::user()->blood_group == 'a+') selected @endif
                                                                            value="a+">A+
                                                                        </option>
                                                                        <option
                                                                            @if (Auth::user()->blood_group == 'a-') selected @endif
                                                                            value="a-">A-
                                                                        </option>
                                                                        <option
                                                                            @if (Auth::user()->blood_group == 'b+') selected @endif
                                                                            value="b+">B+
                                                                        </option>
                                                                        <option
                                                                            @if (Auth::user()->blood_group == 'b-') selected @endif
                                                                            value="b-">B-
                                                                        </option>
                                                                        <option
                                                                            @if (Auth::user()->blood_group == 'o+') selected @endif
                                                                            value="o+">O+
                                                                        </option>
                                                                        <option
                                                                            @if (Auth::user()->blood_group == 'o-') selected @endif
                                                                            value="o-">O-
                                                                        </option>
                                                                        <option
                                                                            @if (Auth::user()->blood_group == 'ab+') selected @endif
                                                                            value="ab+">AB+
                                                                        </option>
                                                                        <option
                                                                            @if (Auth::user()->blood_group == 'ab-') selected @endif
                                                                            value="ab-">AB-
                                                                        </option>

                                                                    </select>
                                                                </div>
                                                                <div>
                                                                    <label for="">Profession</label>
                                                                    <input type="text" name="profession"
                                                                        value="{{ Auth::user()->profession }}"
                                                                        class="form-control" id="">
                                                                </div>
                                                                <div>
                                                                    <label for="">Emergency Contact Number</label>
                                                                    <input type="text" name="emg_contact"
                                                                        value="{{ Auth::user()->emg_contact }}"
                                                                        class="form-control" id="">
                                                                </div>
                                                                
                                                                <div>
                                                                    <label for="">T-shirt Size</label>
                                                                    <select name="t_size" class="w-100 form-control"
                                                                        id="" >
                                                                        <option value="">—Please choose an option—
                                                                        </option>
                                                                        <option
                                                                            @if (Auth::user()->t_size == 'Kids') selected @endif
                                                                            value="Kids">Kids
                                                                        </option>
                                                                        <option
                                                                            @if (Auth::user()->t_size == 'XS') selected @endif
                                                                            value="XS">XS
                                                                        </option>
                                                                        <option
                                                                            @if (Auth::user()->t_size == 'S') selected @endif
                                                                            value="S">S
                                                                        </option>
                                                                        <option
                                                                            @if (Auth::user()->t_size == 'M') selected @endif
                                                                            value="M">M
                                                                        </option>
                                                                        <option
                                                                            @if (Auth::user()->t_size == 'L') selected @endif
                                                                            value="L">L
                                                                        </option>
                                                                         <option
                                                                            @if (Auth::user()->t_size == 'XL') selected @endif
                                                                            value="XL">L
                                                                        </option>
                                                                        <option
                                                                            @if (Auth::user()->t_size == '2XL') selected @endif
                                                                            value="2XL">2XL
                                                                        </option>
                                                                        <option
                                                                            @if (Auth::user()->t_size == '3XL') selected @endif
                                                                            value="3XL">3XL
                                                                        </option>


                                                                    </select>
                                                                </div>
                                                                <div>
                                                                    <label for="">Medical Condition if Any</label>
                                                                    <input type="text" name="medical_condition"
                                                                        value="{{ Auth::user()->medical_condition }}"
                                                                        class="form-control" id="">
                                                                </div>
                                                               
                                                                <div>
                                                                    <label for="">Profile Image</label><br>
                                                                    @if (isset(Auth::user()->profile))
                                                                        <img src="{{ asset('storage/app/' . Auth::user()->profile) }}"
                                                                            alt="" width="100px">
                                                                    @endif
                                                                    <input type="file" name="profile"
                                                                        value="{{ Auth::user()->profile }}"
                                                                        class="form-control mt-2" id="">
                                                                </div>


                                                            </div>
                                                           
                                                            <div class="text-center mt-4">
                                                                <button type="submit"
                                                                    class="button px-5 p-2 w-100">Update</button>
                                                            </div>

                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- important note -->
                <div class="mt-5 important-note px-4">
                    @include('imp')
                </div>
                

            </div>
        </div>
    </div>
   
    <script>
        function getState(value){
            $.ajax({
                url:"{{route('getState')}}",
                type:"post",
                data:{
                    value:value,
                    _token:"{{csrf_token()}}"
                },
               success:function(responce){
                    $('#state').html(responce);
                }
            });
        }
    </script>
    <script>
        function openReadMore(value){
            
            $('.readmore'+value).css('display','none');
        }
    </script>
@endsection
