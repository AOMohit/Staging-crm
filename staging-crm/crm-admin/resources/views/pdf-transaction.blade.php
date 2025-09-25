<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    <h2 class="text-center">Transaction's List</h2>
    <h5 class="text-center">{{ $data[0]['shop_name'] }} [{{ date('d-M-Y') }}]</h5>
    <table class="table table-responsive table-striped">
        <thead>
            <tr>
                <th>No.</th>
                <th>Date</th>
                <th>Customer Name</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ $count++ }}</td>
                    <td>{{ date('d-m-Y', strtotime($item['created_at'])) }}</td>
                    <td>{{ $item['customer_name'] }}</td>
                    <td>{{ $item['amount'] }}</td>
                    <td>{{ $item['type'] }}</td>
                    <td>{{ $item['status'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
