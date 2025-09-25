<!DOCTYPE html>
<html>
<head>
    <title>Carbon Calculator</title>
</head>
<body>
    <h2>Carbon Emission Calculator</h2>
    <form method="POST" action="{{ route('calculate')}} ">
        @csrf
        <label>Distance Traveled (km):</label>
        <input type="number" name="distance" required><br><br>

        <label>Vehicle Type:</label>
        <select name="vehicle_type" required>
            <option value="car">Car</option>
            <option value="bus">Bus</option>
            <option value="train">Train</option>
        </select><br><br>

        <button type="submit">Calculate</button>
    </form>
</body>
</html>
