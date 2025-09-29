<!DOCTYPE html>
<html>
<head>
    <title>Carbon Offset Donation</title>
</head>
<body>
    <h2>🌍 Offset Your Journey's Carbon Emissions</h2>

    <form method="POST" action="{{ route('offset') }}">
        @csrf

        <h3>🚗 Journey Facts</h3>
        <label>Distance (km)*</label><br>
        <input type="number" name="distance" required><br>

        <label>Mileage (km/l)*</label><br>
        <input type="number" name="mileage" required><br>

        <label>CC Engine*</label><br>
        <select name="cc_engine" required>
            <option value="1000">1000cc</option>
            <option value="1500">1500cc</option>
            <option value="2000">2000cc+</option>
        </select><br>

        <label>Fuel Type*</label><br>
        <select name="fuel" required>
            <option value="petrol">Petrol</option>
            <option value="diesel">Diesel</option>
            <option value="cng">CNG</option>
            <option value="electric">Electric</option>
        </select><br><br>

        <h3>📇 Contact Details</h3>
        <label>Name*</label><br>
        <input type="text" name="name" required><br>

        <label>Email*</label><br>
        <input type="email" name="email" required><br>

        <label>Mobile No*</label><br>
        <input type="text" name="mobile" required><br>

        <label>PAN Card</label><br>
        <input type="text" name="pan"><br>

        <label>Address</label><br>
        <textarea name="address" rows="3"></textarea><br><br>

        <button type="submit">Calculate & Offset</button>
    </form>
</body>
</html>
