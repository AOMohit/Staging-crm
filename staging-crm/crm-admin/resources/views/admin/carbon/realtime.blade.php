<!DOCTYPE html>
<html>
<head>
  <title>Carbon Footprint Calculator</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 40px;
      max-width: 1100px;
      margin: auto;
      background-color: #f9f9f9;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
    }

    .input-row {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      justify-content: center;
      align-items: center;
      margin-bottom: 20px;
    }

    .input-row input,
    .input-row select,
    .input-row button {
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 6px;
      min-width: 150px;
    }

    .input-row button {
      background-color: green;
      color: white;
      border: none;
      cursor: pointer;
    }

    .input-row button:hover {
      background-color: darkgreen;
    }

    #result, #contact-form {
      margin-top: 30px;
      display: none;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    form input, form button {
      width: 100%;
      margin-top: 10px;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    form button {
      background-color: green;
      color: white;
      border: none;
      font-weight: bold;
    }

    form button:hover {
      background-color: darkgreen;
    }
  </style>
</head>
<body>

  <h2>🌱 Calculate your Carbon Footprint and Offset it</h2>

  <div class="input-row">
    <input type="number" id="distance" placeholder="Distance (km)" min="1" required>
    <input type="number" id="mileage" placeholder="Mileage (km/l)" min="1" required>
    
    <select id="fuel" required>
      <option value="">Fuel Type</option>
      <option value="petrol">Petrol</option>
      <option value="diesel">Diesel</option>
      <option value="cng">CNG</option>
      <option value="electric">Electric</option>
    </select>

    <select id="cc" required>
      <option value="">Engine CC</option>
      <option value="0-1000">0–1000 CC</option>
      <option value="1001-1500">1001–1500 CC</option>
      <option value="1501-2000">1501–2000 CC</option>
      <option value="2001-2500">2001–2500 CC</option>
      <option value="2501-3000">2501–3000 CC</option>
      <option value=">3000">>3000 CC</option>
    </select>

    <button onclick="calculate()">Calculate</button>
  </div>

  <div id="result">
    <h3>🌍 Offset Summary</h3>
    <p><strong>CO₂ Emission:</strong> <span id="co2"></span> kg</p>
    <p><strong>Trees Needed:</strong> <span id="trees"></span></p>
    <p><strong>Donation to Offset:</strong> ₹<span id="donation"></span></p>
  </div>

  <div id="contact-form">
    <h3>🙋 Contribute to Offset</h3>
    <form method="POST" action="/offset">
      <input type="hidden" id="donation_input" name="donation">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="mobile" placeholder="Mobile" required>
      <button type="submit">Donate ₹<span id="pay-now">0</span> to Offset</button>
    </form>
  </div>

  <script>
    function calculate() {
      const distance = parseFloat(document.getElementById('distance').value);
      const mileage = parseFloat(document.getElementById('mileage').value);
      const fuel = document.getElementById('fuel').value;
      const cc = document.getElementById('cc').value;

      if (!distance || !mileage || !fuel || !cc || distance <= 0 || mileage <= 0) {
        alert("Please fill all values correctly.");
        return;
      }

      const emissionFactors = {
        petrol: 2.31,
        diesel: 2.68,
        cng: 2.01,
        electric: 0.2
      };

      // Engine CC Multiplier
      let ccMultiplier = 1.0;
      if (cc === "1001-1500") ccMultiplier = 1.1;
      else if (cc === "1501-2000") ccMultiplier = 1.2;
      else if (cc === "2001-2500") ccMultiplier = 1.3;
      else if (cc === "2501-3000") ccMultiplier = 1.4;
      else if (cc === ">3000") ccMultiplier = 1.5;

      const fuelUsed = fuel === "electric" ? distance : distance / mileage;
      let co2 = fuelUsed * emissionFactors[fuel] * ccMultiplier;
      co2 = +co2.toFixed(2);

      const trees = Math.ceil(co2 / 21);
      const donation = Math.ceil(co2 * 1.5);

      document.getElementById('co2').innerText = co2;
      document.getElementById('trees').innerText = trees;
      document.getElementById('donation').innerText = donation;
      document.getElementById('pay-now').innerText = donation;
      document.getElementById('donation_input').value = donation;

      document.getElementById('result').style.display = 'block';
      document.getElementById('contact-form').style.display = 'block';
    }
  </script>

</body>
</html>
