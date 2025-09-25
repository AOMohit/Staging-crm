<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ongoing Trip Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .table-container {
            width: 100%;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f4f4f4;
            color: #333;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Ongoing Trip Report</h1>
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Trip Name</th>
                    <th>Trip Type</th>
                    <th>Total revenue</th>
                    <th>Amount collected</th>
                    <th>Amount pending</th>
                    <th>Amount transferred to vendor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $trip)
                    <tr>
                        <td>{{ $trip['trip_name'] }}</td>
                        <td>{{ $trip['trip_type'] }}</td>
                        <td>{{ $trip['total_sale'] }}</td>
                        <td>{{ $trip['amount_collected'] }}</td>
                        <td>{{ $trip['amount_pending'] }}</td>
                        <td>{{ $trip['vendor_payment'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Ongoing Trip Report</p>
    </div>
</body>

</html>
