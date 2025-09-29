@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Membership Transactions</h4>
        </div>
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table id="datatable" class="table table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th>Business</th>
                            <th>Days</th>
                            <th>Payments</th>
                            <th>Transaction Id</th>
                            <th>Purchase Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ shopDetailsById($item->shop_id)->business_name ?? null }}</td>
                                <td> {{ $item->days }}</td>
                                <td> {{ $item->payment }}</td>
                                <td> {{ $item->transaction_id }}</td>
                                <td>{{ date('d-M-Y H:i', strtotime($item->created_at)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>


@section('script')
    <script>
        $(function() {
            var dt_basic_table = $('#datatable'),
                dt_basic = dt_basic_table.DataTable({

                    lengthMenu: [7, 10, 25, 50, 75, 100],
                });
        });
    </script>
@endsection
@endsection
