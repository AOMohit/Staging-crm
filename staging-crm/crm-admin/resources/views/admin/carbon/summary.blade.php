<!DOCTYPE html>
<html>
<head>
    <title>Offset Summary</title>
</head>
<body>
    <h2>🌿 Your Carbon Offset Summary</h2>

    <p><strong>Distance:</strong> {{ $distance }} km</p>
    <p><strong>Mileage:</strong> {{ $mileage }} km/l</p>
    <p><strong>Fuel:</strong> {{ ucfirst($fuel) }}</p>
    <p><strong>Carbon Emission:</strong> {{ $co2_emission }} kg CO₂</p>
    <p><strong>Donation:</strong> ₹{{ $donation }}</p>
    <p><strong>Trees Needed:</strong> {{ $trees_required }}</p>

    <h3>👤 Contact</h3>
    <p>Name: {{ $request->name }}</p>
    <p>Email: {{ $request->email }}</p>
    <p>Mobile: {{ $request->mobile }}</p>
    <p>PAN: {{ $request->pan ?? '-' }}</p>
    <p>Address: {{ $request->address ?? '-' }}</p>

    <br>
    <form method="POST" action="#">
        <button type="submit">Pay ₹{{ $donation }} to Neutralise Now</button>
    </form>
</body>
</html>
