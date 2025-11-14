<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <title>Carbon Clear Calculator</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
      <style>
        /* your CSS from earlier - keep as-is */
        body { background: #f9f9f9; font-family: 'Segoe UI', sans-serif; }
        body {
            background: #f9f9f9;
            font-family: 'Segoe UI', sans-serif;
            }
            .form-check-input:checked {
            background-color: #0FB915!important;
            border-color: #0FB915!important;
            }
            .form-check-input:focus {
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 .25rem rgb(13 110 253 / 0%)!important;
            }
            h5.ct {
            font-size: 19px;
            font-weight: 600;
            }
            .section-box {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            }
            .input-box {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 12px 14px 6px;
            position: relative;
            background-color: #fff;
            margin-right: 1px;
            }
            .input-box label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: -5px;
            color: #222;
            display: block;
            }
            .input-box input,
            .input-box select {
            border: none;
            outline: none;
            padding: 0px;
            width: 100%;
            font-size: 14px;
            background: transparent;
            color: #6c757d!important;
            }
            .input-box .bottom-border {
            border-bottom: 1px solid #000;
            margin-top: 0px;
            }
            label.form-check-label {
            font-size: 13px;
            font-weight: 500;
            margin: 0;
            }
            .subtxt {
            font-size: 10px;
            color: #6c757d !important;
            }
            .form-control, .form-select {
            border-radius: 10px;
            height: 28px;
            font-size: 13px;
            color: #6c757d;
            }
            .form-control:focus {
            color: var(--bs-body-color);
            background-color: var(--bs-body-bg);
            border-color: #86b7fe00;
            outline: 0;
            box-shadow: 0 0 0 .25rem rgb(13 110 253 / 0%);
            }
            .form-check-input {
            margin-right: 10px;
            }
            input.form-control {
            padding: 0px;
            }
            button.rest {
            border-radius: 30px;
            padding: 11px 34px;
            border: 2px solid #0FB915;
            color: #0FB915;
            background: transparent;
            }
            button.btn-greenss {
            border: 2px solid #0FB915;
            border-radius: 30px;
            color: #fff;
            background: #0FB915;
            font-weight: 600;
            padding: 0px 20px;
            }

            button.btn-greenss:hover {
            border: 2px solid #12a117;
            border-radius: 30px;
            color: #fff;
            background: #12a117;
            font-weight: 600;
            padding: 0px 20px;
            }
            .right-btn {
            border-radius: 30px;
            padding: 10px 25px;
            border: 2px solid #0FB915;
            color: #fff;
            background: #0FB915;
            font-weight: 600;
            }

            /*right side */
            .results-box {
            border: 1.5px solid #00c853;
            border-radius: 5px;
            color: #00c853;
            font-family: 'Segoe UI', sans-serif;
            display: inline-block;
            text-align: center;
            }
            .box-small {
            width: 144px;
            padding: 6px;
            }
            .box-medium {
            width: 178px;
            padding: 6px;
            }
            .box-large {
            width: 235px;
            padding: 6px;
            }
            .col-auto.mb-3 {
            padding: 0;
            }
            .results-box h5 {
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 6px;
            color: #00c853;
            margin:0px;
            }
            .results-box small {
            font-size: 13px;
            font-weight: 500;
            color: #00c853;
            }
            .form-section input {
            margin-bottom: 15px;
            }
            .custom-box-input {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px 14px 5px;
            position: relative;
            margin-bottom: 20px;
            background: #fff;
            }
            .custom-box-input label {
            font-size: 14px;
            font-weight: 600;
            display: block;
            margin-bottom: 4px;
            color: #222;
            }
            .custom-box-input input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 14px;
            background: transparent;
            color: #333;
            }
            .custom-box-input::after {
            content: "";
            display: block;
            border-bottom: 1px solid #000;
            }
            .form-section input {
            margin-bottom: 1px;
            }
            .form-select:focus {
            color: #6c757d;
            background-color: var(--bs-body-bg);
            border-color: #86b7fe00;
            outline: 0;
            padding: 0px;
            box-shadow: 0 0 0 .25rem rgb(13 110 253 / 0%);
            }
            small.sml {
            color: #0FB915;
            }
            .form-section {
            display: none;
            }
            .form-section.active {
            display: block;
            }
            .custom-select-arrow {
            background-color: #fff !important;
            color: #6c757d !important;
            appearance: auto !important;
            -webkit-appearance: auto !important;
            -moz-appearance: auto !important;
            margin-left: -4px;
            }
            h6.corbn-offset {
            font-size: 12px;
            margin-left: 20rem;
            vertical-align: middle !important;
            }
            select.right-crbn {
            font-size: 13px;
            margin-top: -5px;
            border: 1px solid #B8B8B8;
            border-radius: 4px;
            }
            .right-crbn:focus {
               border: 1px solid #B8B8B8 !important;
               outline: none;
            }


         /*impact section css */
                  .impact-section {
            background: url('https://i.imgur.com/zN1SPjX.png') no-repeat center;
            background-size: cover;
            padding: 50px 20px;
         }

         .impact-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
         }

         .impact-text {
            flex: 1 1 500px;
            max-width: 600px;
         }

         .impact-text h2 {
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: 600;
         }

         .impact-text p {
            font-size: 14px;
            line-height: 1.7;
            color: #333;
         }

         .impact-cards {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
         }

         .impact-card {
            background: #fff;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            text-align: center;
            min-width: 140px;
            flex: 1 1 140px;
         }

         .impact-card img {
            width: 50px;
            margin-bottom: 10px;
         }

         .impact-card h3 {
            font-size: 22px;
            margin-bottom: 5px;
            font-weight: 600;
         }

         .impact-card p {
            font-size: 12px;
            color: #333;
         }

         .impact-tree {
            flex: 1 1 300px;
            text-align: center;
         }

         .impact-tree img {
            max-width: 100%;
            height: auto;
         }

         @media (max-width: 768px) {
            .impact-container {
            flex-direction: column;
            text-align: center;
            }

            .impact-text, .impact-tree {
            max-width: 100%;
            }

            .impact-cards {
            justify-content: center;
            }
         }


         /*bg*/
         .impact-section {
            background: url('https://new.adventuresoverland.com/wp-content/uploads/2025/06/green-fields-trees-left-corner-cloudy-sky-panoramic-view-scaled-e1750660581373.jpg') no-repeat center;
            background-size: cover;
            padding: 50px 20px;
         }

         .impact-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
         }

         .impact-text {
            flex: 1 1 500px;
            max-width: 600px;
         }

         .impact-text h2 {
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: 600;
         }

         .impact-text p {
            font-size: 14px;
            line-height: 1.7;
            color: #333;
         }

         .impact-cards {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
         }

         .impact-card {
            background: #fff;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            text-align: center;
            min-width: 140px;
            flex: 1 1 140px;
         }

         .impact-card img {
            width: 50px;
            margin-bottom: 10px;
         }

         .impact-card h3 {
            font-size: 22px;
            margin-bottom: 5px;
            font-weight: 600;
         }

         .impact-card p {
            font-size: 12px;
            color: #333;
         }

         .impact-tree {
            flex: 1 1 300px;
            text-align: center;
         }

         .impact-tree img {
            max-width: 100%;
            height: auto;
         }

         @media (max-width: 768px) {
            .impact-container {
            flex-direction: column;
            text-align: center;
            }

            .impact-text, .impact-tree {
            max-width: 100%;
            }

            .impact-cards {
            justify-content: center;
            }
         }

         /*end*/




            @media (max-width: 768px) {
            h6.corbn-offset {
            font-size: 12px;
            margin-left: 0rem;
            vertical-align: middle !important;
            }
            button.rest {
            border-radius: 30px;
            padding: 11px 34px;
            border: 2px solid #0FB915;
            color: #0FB915;
            background: transparent;
            font-size: 14px;
            }
            button.btn-greenss {
            border: 2px solid #0FB915;
            border-radius: 30px;
            color: #fff;
            background: #0FB915;
            font-weight: normal;
            padding: 0px 6px;
            font-size: 14px;
            }
            .right-sections {
            margin-top: 20px;
            }
            .results-box h5 {
            font-weight: 600;
            font-size: 14px;
            color: #00c853;
            margin: 0px;
            }
            .results-box small
            {
            font-size: 12px;
            font-weight: 500;
            color: #00c853;
            }
            .box-small {
            width: 129px;

            }
            .box-medium {
            width: 159px;
            }
            .box-large {
            width: 303px;
            }
            .col-auto.mb-3 {
            margin-bottom: 0px !important;
            }
            }
      </style>
   </head>
   <body>
      <div class="container py-5">
         <h3 class="text-center" style="margin: 0px;">Carbon Clear Calculator</h3>
         <p class="text-center text-muted">Calculate your Carbon Footprint and offset it by selecting any one of the two options.</p>
         <div class="row mt-5 align-items-stretch">
            <!-- Left Section -->
            <div class="col-lg-5 d-flex">
               <div class="section-box w-100">
                  <div>
                     <h5 class="mb-3 ct">Calculation type</h5>
                     <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="calcType" value="vehicle" id="byVehicle" checked>
                        <label class="form-check-label" for="byVehicle">By Vehicle Information</label>
                        <small class="text-muted d-block subtxt">You need to provide the journey facts such as distance, miles, CC engine and fuel.</small>
                     </div>
                     <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="calcType" value="trees" id="byTrees">
                        <label class="form-check-label" for="byTrees">By Number of trees to plant</label>
                        <small class="text-muted d-block subtxt">You need to provide the number of trees you want to plant</small>
                     </div>
                     <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="calcType" value="donation" id="byDonation">
                        <label class="form-check-label" for="byDonation">By Donation Amount</label>
                        <small class="text-muted d-block subtxt">You need to provide the donation amount.</small>
                     </div>
                     <hr>
                     <div class="form-section active" id="vehicle">
                        <h6 class="form-check-label">By Vehicle Information</h6>
                        <small class="text-muted d-block subtxt mb-3">Please provide the journey facts such as distance, miles, CC engine and fuel.</small>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="custom-box-input">
                                 <label>Distance (km) <span class="text-danger">*</span></label>
                                 <input type="number" id="distance" placeholder="Distance (km)" min="1" required>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="custom-box-input">
                                 <label>Mileage (km/l) <span class="text-danger">*</span></label>
                                 <input type="number" id="mileage" placeholder="Mileage (km/l)" min="1" required>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="input-box mb-3 custom-box-input">
                                 <label>CC Engine <span class="text-danger">*</span></label>
                                 <select class="form-select custom-select-arrow" id="cc">
                                    <option value="">Select CC Engine</option>
                                    <option value="1001-1500">1001 CC - 1500 CC</option>
                                    <option value="1501-2000">1501 CC - 2000 CC</option>
                                    <option value="2001-2500">2001 CC - 2500 CC</option>
                                    <option value="2501-3000">2501 CC - 3000 CC</option>
                                    <option value=">3000">&gt;3001 CC</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="input-box mb-3 custom-box-input">
                                 <label>Fuel <span class="text-danger">*</span></label>
                                 <select class="form-select custom-select-arrow" id="fuel">
                                    <option value="">Select Fuel</option>
                                    <option value="diesel">Diesel</option>
                                    <option value="petrol">Petrol</option>
                                    <option value="electric">Electric</option>
                                    <option value="cng">CNG</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="form-section"  id="trees_plant">
                     <h6 class="form-check-label">By Number of trees to plant</h6>
                        <small class="text-muted d-block subtxt mb-3">Please provide number of trees.</small>
                     <div class="input-box ">
                        <label for="trees" class="cntheads">Number of Trees to Plant <span class="text-danger">*</span></label>
                        <input type="text" id="trees_input_local" placeholder="Enter number of trees" class="form-control"> 
                        <div class="bottom-border"></div>
                     </div>
                   </div>
                   <div class="form-section" id="donation_amount">
                    <h6 class="form-check-label">By Donation Amount</h6>
                        <small class="text-muted d-block subtxt mb-3">Enter donation amount.</small>
                     <div class="input-box">
                        <label for="donationAmt" class="cntheads">Donation Amount <span class="text-danger">*</span></label>
                        <input type="text" id="donation_input_local" placeholder="Enter donation amount" class="form-control"> 
                        <div class="bottom-border"></div>
                     </div>
                     </div>
                  </div>
                  <div class="d-flex justify-content-evenly mt-3">
                     <button class="rest" type="button" onclick="resetForm()">Reset</button>
                     <button class="btn-greenss" type="button" onclick="calculate()">Calculate Carbon Footprint</button>
                  </div>
               </div>
            </div>

            <!-- Right Section -->
            <div class="col-lg-7 d-flex right-sections">
               <div class="section-box w-100">
                  <form method="POST"
                        action="{{ route('carbon.payu') }}"
                        target="_top">
                     @csrf
                     <input type="hidden" id="donation_input" name="donation">
                     <input type="hidden" name="co2" id="co2_input">
                     <input type="hidden" name="trees" id="trees_input">
                     <div>
                        <div class="form-section right-extra" id="vehicleExtras" style="display: flex; padding-bottom: 10px;">
                           <h5 class="mb-3 ct">Results</h5>
                           <div id="offsetOnlyWrap" style="display: flex; align-items: center; gap: 10px;">
                              <h6 class="corbn-offset mb-0">Carbon Offset for Vehicle</h6>
                              <select class="right-crbn">
                                 <option>Full</option>
                              </select>
                           </div>
                        </div>

                        <div class="row text-center justify-content-start gap-3" style="margin-left: 0px;">
                           <div class="col-auto mb-3">
                              <div class="results-box box-small">
                                 <h5><span id="co2">0</span></h5>
                                 <small>Carbon Offset (in kgs)</small>
                              </div>
                           </div>

                           <div class="col-auto mb-3">
                              <div class="results-box box-medium">
                                 <h5>₹ <span id="donation">0</span></h5>
                                 <small>Donation for Carbon Offset</small>
                              </div>
                           </div>

                           <div class="col-auto mb-3">
                              <div class="results-box box-large">
                                 <h5><span id="trees">0</span></h5>
                                 <small>Trees to be planted for Carbon Offset</small>
                              </div>
                           </div>
                        </div>
                        <hr>
                        <h6 class="form-check-label">Contact Details</h6>
                        <small class="text-muted d-block subtxt mb-3">
                           Furnish PAN Card, address, and contact details for 80G tax exemption receipt on your donation.
                        </small>
                        <div class="right-sid">
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="custom-box-input">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" placeholder="Enter Name" required/>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="custom-box-input">
                                    <label>Email Id <span class="text-danger">*</span></label>
                                    <input type="email" name="email" placeholder="Enter Email" required/>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="custom-box-input">
                                    <label>Mobile No. <span class="text-danger">*</span></label>
                                    <input type="text" name="mobile" placeholder="Enter Mobile Number" required/>
                                 </div>
                              </div>

                              <div class="col-md-4">
                                 <div class="custom-box-input">
                                    <label>Pan Card</label>
                                    <input type="text" name="pan_card" placeholder="Enter PAN" />
                                 </div>
                              </div>
                              <div class="col-md-8">
                                 <div class="custom-box-input">
                                    <label>Address</label>
                                    <input type="text" name="address" placeholder="Enter Address" />
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="mt-4">
                        <button type="submit" class="right-btn">Pay ₹ <span id="pay-now">0</span> to Neutralize Now</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>

      <script>
         const radios = document.querySelectorAll('input[name="calcType"]');
         const sections = {
            vehicle: document.getElementById('vehicle'),
            trees: document.getElementById('trees_plant'),
            donation: document.getElementById('donation_amount')
         };

         const offsetOnlyWrap = document.getElementById('offsetOnlyWrap');

         radios.forEach(radio => {
            radio.addEventListener('change', () => {
               const selected = radio.value;
               for (let key in sections) {
                 sections[key].classList.remove('active');
               }
               sections[selected].classList.add('active');

               if (selected === 'vehicle') {
                  offsetOnlyWrap.style.display = 'flex';
               } else {
                  offsetOnlyWrap.style.display = 'none';
               }
            });
         });

         function resetForm() {
            document.querySelectorAll('input[type="number"]').forEach(input => input.value = '');
            document.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
            document.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
            document.getElementById('byVehicle').checked = true;
            for (let key in sections) {
               sections[key].classList.remove('active');
            }
            sections['vehicle'].classList.add('active');
            offsetOnlyWrap.style.display = 'flex';
         }

         function calculate() {
            const selectedType = document.querySelector('input[name="calcType"]:checked').value;

            let co2 = 0, trees = 0, donation = 0;

            if (selectedType === "vehicle") {
               const distance = parseFloat(document.getElementById('distance').value);
               const mileage = parseFloat(document.getElementById('mileage').value);
               const fuel = document.getElementById('fuel').value;
               const cc = document.getElementById('cc').value;

               if (!distance || !mileage || !fuel || !cc || distance <= 0 || mileage <= 0) {
                     alert("Please fill all vehicle fields correctly.");
                     return;
               }

               const emissionFactors = {
                  petrol: 1.155,
                  diesel: 1.34,
                  cng: 1.375,
                  lpg: 0.755,
                  electric: 0.1
               };

               let ccMultiplier = 1.0;
               if (cc === "1001-1500") ccMultiplier = 1.05;
               else if (cc === "1501-2000") ccMultiplier = 1.10;
               else if (cc === "2001-2500") ccMultiplier = 1.15;
               else if (cc === "2501-3000") ccMultiplier = 1.20;
               else if (cc === ">3000") ccMultiplier = 1.25;

               const fuelUsed = fuel === "electric" ? distance : distance / mileage;
               co2 = fuelUsed * emissionFactors[fuel] * ccMultiplier;

               co2 = +co2.toFixed(2);
               trees = Math.ceil(co2 / 20);
               donation = Math.ceil(trees * 150);
            } else if (selectedType === "trees") {
               const treeCountInput = document.querySelector('#trees_plant input').value;
               const treeCount = parseInt(treeCountInput);
               if (!treeCount || treeCount <= 0) {
                     alert("Please enter a valid number of trees.");
                     return;
               }
               co2 = +(treeCount * 20).toFixed(2);
               trees = treeCount;
               donation = Math.ceil(treeCount * 150);
            } else if (selectedType === "donation") {
               const donationInput = document.querySelector('#donation_amount input').value;
               const donationAmount = parseFloat(donationInput);
               if (!donationAmount || donationAmount <= 0) {
                     alert("Please enter a valid donation amount.");
                     return;
               }
               trees = Math.floor(donationAmount / 150);
               co2 = +(trees * 20).toFixed(2);
               donation = donationAmount;
            }

            document.getElementById('co2').innerText = co2;
            document.getElementById('trees').innerText = trees;
            document.getElementById('donation').innerText = donation;
            document.getElementById('pay-now').innerText = donation;
            document.getElementById('donation_input').value = donation;

            document.getElementById('co2_input').value = co2;
            document.getElementById('trees_input').value = trees;
         }
      </script>
   </body>
</html>
