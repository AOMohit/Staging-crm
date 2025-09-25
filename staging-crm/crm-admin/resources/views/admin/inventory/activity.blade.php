@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a
                    href="{{ route('inventory.index') }}">Inventory</a>/</span>
            Activity > {{ $stock->title }}</h4>
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="p-3">

                </div>
                <table id="myDatatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--/ DataTable with Buttons -->
@endsection

@section('script')
    <script>
        $('th').css('white-space', 'nowrap');

        $(document).ready(function() {
            getfilterdata();
        });

        function getfilterdata() {
            // var sdatefrom = $("#sdatefrom").val();

            var table = $('#myDatatable').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('inventory.activity-get') !!}",
                    "type": 'GET',
                    "data": {
                        "user_type": "admin",
                        "id": "{{ $data }}",
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        data: 'created',
                        name: 'created'
                    }, {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                'rowCallback': function(row, data, index) {
                    $('td', row).css('white-space', 'nowrap');
                },
                'columnDefs': [{
                    "targets": [],
                    "orderable": false
                }],
                "language": {
                    "paginate": {
                        "previous": '&nbsp;',
                        "next": '&nbsp;'
                    }
                },
            });
        }
    </script>
@endsection
