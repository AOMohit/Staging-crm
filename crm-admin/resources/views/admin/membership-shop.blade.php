@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Pro shop</h4>
        </div>
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table id="datatable" class="table table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th width="25%;">Business</th>
                            <th width="25%;">Contact</th>
                            <th width="35%;">Membership Details</th>
                            <th>Membership Status</th>
                            <th width="15%;">Purchase Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            @php
                                $membershipD = checkMembershipByShopId($item->shop_id);
                                $membership = membershipCheck($item->is_membership_taken);
                            @endphp
                            <tr>
                                <td>
                                    <strong>Name:</strong> {{ $item->business_name }}
                                    <br>
                                    <strong>Owner:</strong> {{ $item->business_owner }}
                                    <br>
                                    <strong>Type:</strong> {{ $item->business_type }}
                                </td>

                                <td>
                                    <strong>Phone:</strong> <a href="tel:{{ $item->phone }}">{{ $item->phone }}</a>
                                    <br>
                                    <strong>Email:</strong> <a href="mailto:{{ $item->email }}">{{ $item->email }}</a>
                                </td>

                                <td>
                                    <strong>Days: </strong> {{ $membership->days }}
                                    <br>
                                    <strong>Price: </strong> {{ $membership->payment }}
                                    <br>
                                    <strong>Transaction Id: </strong> {{ $membership->transaction_id }}
                                </td>

                                <td>
                                    @if ($membershipD !== 'Expired')
                                        {{ $membershipD }} Day's Left
                                    @else
                                        <span class="badge bg-danger">Expired</span>
                                    @endif

                                </td>
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
