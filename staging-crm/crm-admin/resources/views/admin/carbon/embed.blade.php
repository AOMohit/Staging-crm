<!DOCTYPE html>
<html>
<head>
    <title>Embeddable Carbon Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h3>Carbon Calculator</h3>
    <form method="POST" action="/calculate" target="_self">
        @csrf
        <label>Distance (km):</label>
        <input type="number" name="distance" required><br><br>

        <label>Vehicle:</label>
        <select name="vehicle_type">
            <option value="car">Car</option>
            <option value="bus">Bus</option>
            <option value="train">Train</option>
        </select><br><br>

        <button type="submit">Calculate</button>
    </form>
</body>
</html>
