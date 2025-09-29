<!DOCTYPE html>
<html>
<head>
    <title>Carbon Result</title>
</head>
<body>
    <h2>Calculation Result</h2>
    <p>Distance: {{ $distance }} km</p>
    <p>Vehicle Type: {{ ucfirst($vehicle) }}</p>
    <p><strong>Carbon Emission:</strong> {{ $carbon }} kg CO₂</p>
    <a href="/calculator">Back</a>
</body>
</html>
