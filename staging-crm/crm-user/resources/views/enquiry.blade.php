@extends('layouts.userlayout.index')
@section('container')
    <div class="col-md-10 col-12">
        <div class="border-shadow mb-4">
            <div class="card px-3">
                <div class="card-header bg-white information">
                    @include('layouts.userlayout.card-header')
                </div>
                <h6 class="text fw-bold mb-4 mt-3">Enquire for a Trip</h6>

                <div class="card border-shadow">
                    <div class="card-body">

                            <div class="col-12">
                                <form action="{{ route('enquirySubmit',['type'=>'enquiry']) }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-5 col-12 redeem-form ">
                                            <div class="mt-3">

                                                <label for="">Choose your Expedition <span>*</span></label><br>
                                                <select name="expedition" id="trips" class="border" required
                                                    onchange="getTripPrice(this.value)">
                                                    <option value="">—Please choose an option—</option>
                                                    @foreach ($trip as $trips)
                                                        <option value="{{ $trips->id }}">{{ $trips->name }}</option>
                                                    @endforeach
                                                    <option value="Tailor Made">Tailor Made</option>

                                                </select>
                                                <div id="tailor-made-comments" style="display: none; margin-top: 10px;">
                                                    <input type="text" style="width: 260px;" class="form-control" name="tailor_made_comment" id="tailor-made" placeholder="comments">
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <label for="">Add Travelers <span>*</span></label><br>
                                                <div class="row mt-4">
                                                    <div class="col-6 enquiry ">
                                                        <label for="" class="text-secondary ">Adults - Above 12
                                                            Years</label>
                                                        <div class=" borders mt-2">
                                                            <span onclick="changeValue('minus')">-</span>
                                                            <input type="text" name="adult" value="1"
                                                                onchange="totalinput(this.value)" id="adultvalue">
                                                            <span onclick="changeValue('plus')">+</span>
                                                        </div>

                                                    </div>
                                                    <div class="col-6 enquiry ">
                                                        <label for="" class="text-secondary ">Children - Below 12
                                                            Years</label>
                                                        <div class=" borders mt-2">
                                                            <span onclick="changeValueChild('minus')">-</span>
                                                            <input type="text" name="minor" value="0"
                                                                onchange="totalinput(this.value)" id="childvalue">
                                                            <span onclick="changeValueChild('plus')">+</span>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>

                                            <div class="mt-4">


                                                <span class=""><span class ="totalTravel">1</span> Travelers - <span
                                                        id ="adult-count">1</span> Adults, <span id ="child-count">0</span>
                                                    Child</span><br>

                                                <a href="javascript:void(0)" onclick="addTravelerModal()"
                                                    class="blue fw-bold">Add Travelers Details</a>

                                            </div>


                                            <input type="hidden" name="travelerListJson" id="travelerListJson"
                                                value=" ">
                                            <div class="mt-4 pe-3" id="traveler-final-list">



                                            </div>

                                        </div>
                                        <div class="col-md-7 col-12 border-start">
                                            <div class="p-sm-0 p-md-4">

                                                <span class="fw-bold">Your Total Available Points</span>
                                                <div class=" mt-4 mb-4 total-earning-card">
                                                   
                                                    <span class=" rounded shadows bg-white p-2 ">

                                                        <span class="font-size-14 font-size-mobile-14 fw-bold text"> You
                                                            Have Total</span>
                                                        &nbsp; <img
                                                            src="{{ asset('public/userpanel') }}/asset/images/star.svg"
                                                            alt="">
                                                        <span
                                                            class="fw-bold font-size-20 font-size-mobile-20">{{ Auth::user()->points }}</span>
                                                        <small class="font-size-10 font-size-mobile-10">Points</small>
                                                        &nbsp; <small class="font-size-9 font-size-mobile-9">Expiring on:
                                                            26th Jan, 2023</small>

                                                    </span>
                                                </div>
                                                @if(Auth::user()->points  !=0)
                                                <span class="fw-bold">Would you like to redeem your points?</span>

                                                <div class="redeem-form">
                                                    <select name="redeem_points_status" class="w-25 mt-2" id=""
                                                        required>

                                                        <option value="yes">Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                                @endif
                                                <div class="col-md-8">
                                                    <div class="d-flex justify-content-between mt-4">
                                                        <div>
                                                            <span>Total Trip Cost</span><br>
                                                            <small class="text-secondary">₹ <span id="trip-price">0</span> x
                                                                <span class="totalTravel">1</span> Travelers</small>
                                                            <input type="hidden" name="" id="trip-prices">
                                                        </div>
                                                        <input type="hidden" name="price" id='price'><span
                                                            class="fw-bold">₹ <span id="total-price">0</span></span>
                                                    </div>

                                                </div>
                                                
                                                <button class="button px-5 p-2 mt-3">Enquire Now</button>

                                                <!-- modal for traveller add -->

                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="modal fade" id="travelers" data-bs-backdrop="static" data-bs-keyboard="false"
                                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog rounded modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="staticBackdropLabel">Add Traveler
                                                    Details</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="col-md-12 p-2">

                                                    <!--<form action="">-->
                                                    <!--<div class="radio-toolbar" id="traveler-radio-list">-->


                                                        <!-- <small class="text-secondary">3 years old</small> -->
                                                    <!--</div>-->
                                                    <div class="row">
                                                        <div id="traveler_list_final">
                                                            
                                                        </div>
                                                            
                                                        <button onclick="addTravelers(0)"
                                                            class="button px-5 mt-3 p-2 w-50 ms-3">Add
                                                            Traveler</button>

                                                    </div>
                                                    <!--</form>-->
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                    </div>
                </div>

                <div class="modal fade" id="travelersEdit" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog rounded modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title" id="staticBackdropLabel">Edit Traveler
                                    Details</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-md-12 p-2">

                                    <input type="hidden" class="form-control mt-2" id="traveler-type-edit"
                                        value="">

                                    <div class="row">
                                        <div class="col-md-4 col-12 redeem-form">
                                            <label for="">Full Name<span
                                                    class="red">*</span></label> <br>
                                            <input type="text" name=""
                                                class="form-control mt-2" id="traveler-name-edit"
                                                placeholder="Enter Name">
                                        </div>
                                        <div class="col-md-4 col-12 redeem-form">
                                            <label for="">Age<span
                                                    class="red">*</span></label> <br>
                                            <input type="number" name=""
                                                class="form-control mt-2" id="traveler-dob-edit"
                                                placeholder="Enter Age">
                                        </div>
                                        <div class="col-md-3 col-12 redeem-form-edit">
                                            <label for="">Gender <span
                                                    class="red">*</span></label> <br>
                                            <select name="" class="w-100 mt-2"
                                                id="traveler-gender">

                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>

                                        </div>

                                        <button onclick="addTraveler(1)" class="button px-5 mt-3 p-2 w-50 ms-3">Update
                                            Traveler</button>

                                    </div>
                                    <!--</form>-->
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <!-- important note -->
               @include('imp')
            </div>
        </div>
    </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
 document.addEventListener("DOMContentLoaded", function () {
            let dropdown = document.getElementById("trips");
            let otherInput = document.getElementById("tailor-made-comments");

            dropdown.addEventListener("change", function () {
                if (this.value === "Tailor Made") {

                    otherInput.style.display = "block";
                } else {
                    
                    otherInput.style.display = "none";
                }
            });
        });

</script>
<script>
    function addTravelerModal() {
        var adults = $("#adult-count").text();
        var childs = $("#child-count").text();

        var adultList;
        var count = 1;
        for (var i = 1; i <= adults; i++) {
           
                adultList += `
                            <div class="row mt-2"><input type="hidden" id="list${count}" name="travelerTypes[]" value="Traveler ${count} - Adult">    
                            <div class="col-md-4 col-12 redeem-form">
                                <label for="">Full Name (Traveler ${count} - Adult)<span class="red">*</span></label> <br>
                                <input type="text" name="travelerName[]" class="form-control mt-2"
                                    id="traveler-name${count}" placeholder="Enter Name">
                            </div>
                            <div class="col-md-4 col-12 redeem-form">
                                <label for="">Age<span class="red">*</span></label> <br>
                                <input type="number" name="travelerDob[]" class="form-control mt-2"
                                    id="traveler-dob${count}" placeholder="Enter Age">
                            </div>
                            <div class="col-md-3 col-12 redeem-form">
                                <label for="">Gender <span class="red">*</span></label> <br>
                                <select name="travelerGender[]" class="w-100 mt-2" id="traveler-gender${count}">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
    
                            </div></div>`;

            count++;
        }

        for (var j = 1; j <= childs; j++) {
            adultList += `
                        <div class="row mt-2"><input type="hidden" id="list${count}" name="travelerTypes[]" value="Traveler ${count} - Child">
                        <div class="col-md-4 col-12 redeem-form">
                            <label for="">Full Name (Traveler ${count} - Child)<span class="red">*</span></label> <br>
                            <input type="text" name="travelerName[]" class="form-control mt-2"
                                id="traveler-name${count}" placeholder="Enter Name">
                        </div>
                        <div class="col-md-4 col-12 redeem-form">
                            <label for="">Age<span class="red">*</span></label> <br>
                            <input type="number" name="travelerDob[]" class="form-control mt-2"
                                id="traveler-dob${count}" placeholder="Enter Age">
                        </div>
                        <div class="col-md-3 col-12 redeem-form">
                            <label for="">Gender <span class="red">*</span></label> <br>
                            <select name="travelerGender[]" class="w-100 mt-2" id="traveler-gender${count}">

                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>

                        </div></div>`;
            count++
        }
        adultList = adultList.replace("undefined", "");

        // console.log(adultList);
        $("#traveler_list_final").html(adultList);


        $("#travelers").modal('show');
        
    }


    var travelersArray = [];

    function addTraveler(type) {
        if (type == 1) {
            var checkedValue = $("#traveler-type-edit").val();
            var travelerName = $("#traveler-name-edit").val();
            var travelerDob = $("#traveler-dob-edit").val();
            var travelerGender = $("#traveler-gender-edit").val();
        } else {
            var checkedValue = $('input[name="travelerTypes"]:checked').val();
            var travelerName = $("#traveler-name").val();
            var travelerDob = $("#traveler-dob").val();
            var travelerGender = $("#traveler-gender").val();
        }


        if (travelerName != "" && travelerDob != "") {
            var existingTravelerIndex = -1;

            // Check if a traveler with the same checkedValue exists
            travelersArray.forEach(function(traveler, index) {
                if (traveler.checkedValue === checkedValue) {
                    existingTravelerIndex = index;
                }
            });

            if (existingTravelerIndex !== -1) {
                // Update existing traveler data
                travelersArray[existingTravelerIndex] = {
                    "checkedValue": checkedValue,
                    "name": travelerName,
                    "dob": travelerDob,
                    "gender": travelerGender
                };
            } else {
                // Create a new traveler object
                var travelerData = {
                    "checkedValue": checkedValue,
                    "name": travelerName,
                    "dob": travelerDob,
                    "gender": travelerGender
                };
                // Add new traveler data to the array
                travelersArray.push(travelerData);
            }

            // print data in list
            var text = "";
            travelersArray.forEach(function(traveler, index) {
                text += `<div class="d-flex justify-content-between border-bottom">
                        <div class="teveler-data">
                            <small>${traveler.checkedValue}</small>
                            <h6 class="fw-bold">${traveler.name}</h6>
                        </div>
                        <a href="javascript:void(0)" onclick="editModel('${traveler.checkedValue}','${traveler.name}','${traveler.dob}','${traveler.gender}')" class="blue  mt-3">Edit</a>
                      </div>`;
            });

            $("#traveler-final-list").html(text);
            // print data in list

            // Convert the array of traveler data into JSON
            var jsonTravelersData = JSON.stringify(travelersArray);
    


            $("#travelerListJson").val(jsonTravelersData);
            // alert($("#travelerListJson").val(jsonTravelersData))

            // make empty fields
            $("#traveler-name").val('');
            $("#traveler-dob").val('');
            $("#traveler-gender").val('Male');
        } else {
            alert("All Fields are Required!");
        }
        
        $("#travelersEdit").modal('hide');

    }
    
    function addTravelers(type){
        
        var allFieldsFilled = true;

        // Check each input field
        $("#traveler_list_final").find('input,select').each(function() {
          if ($(this).val() === '') {
            allFieldsFilled = false;
            return false; // Exit the loop early if any field is empty
          }
        });
    
        // If any field is empty, show an error message
        if (!allFieldsFilled) {
          alert('All fields are required');
          return;
        }
    
        
        var checkedValue = $('input[name="travelerTypes[]"]').map(function() {
          return $(this).val();
        }).get();
        var travelerName = $('input[name="travelerName[]"]').map(function() {
          return $(this).val();
        }).get();
        var travelerDob = $('input[name="travelerDob[]"]').map(function() {
          return $(this).val();
        }).get();
        var travelerGender = $('select[name="travelerGender[]"]').map(function() {
          return $(this).val();
        }).get();
        
        $.each(checkedValue, function(index, val) {
            var travelerData = {
                "checkedValue": val,
                "name": travelerName[index],
                "dob": travelerDob[index],
                "gender": travelerGender[index]
            };
            travelersArray.push(travelerData);
        });
        
        var jsonTravelersData = JSON.stringify(travelersArray);
    


            $("#travelerListJson").val(jsonTravelersData);
        
        var text = "";
        travelersArray.forEach(function(traveler, index) {
            text += `<div class="d-flex justify-content-between border-bottom">
                    <div class="teveler-data">
                        <small>${traveler.checkedValue}</small>
                        <h6 class="fw-bold">${traveler.name}</h6>
                    </div>
                    <a href="javascript:void(0)" onclick="editModel('${traveler.checkedValue}','${traveler.name}','${traveler.dob}','${traveler.gender}')" class="blue  mt-3">Edit</a>
                  </div>`;
        });

        $("#traveler-final-list").html(text);
        $("#travelers").modal('hide');
    }
    

    function getDataFromArray(val) {
        if ($("#travelerListJson").val() !== "") {
            var jsonData = JSON.parse($("#travelerListJson").val());

            const filteredData = jsonData.filter(item => item.checkedValue === val);

            if (filteredData.length > 0) {
                $("#traveler-name").val(filteredData[0].name);
                $("#traveler-dob").val(filteredData[0].dob);
                $("#traveler-gender").val(filteredData[0].gender);
            } else {
                $("#traveler-name").val('');
                $("#traveler-dob").val('');
                $("#traveler-gender").val('Male');
            }
        }
    }

    function editModel(type, name, dob, gender) {

        $('#traveler-type-edit').val(type);
        $('#traveler-name-edit').val(name);
        $('#traveler-gender-edit').val(gender);
        $('#traveler-dob-edit').val(dob);

        $('#travelersEdit').modal('show');



    }
</script>
