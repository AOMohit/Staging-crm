@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="p-3">
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <div class="dt-buttons btn-group flex-wrap">

                            <a href="{{ route('inventory.export') }}"
                                class="btn btn-secondary buttons-collection btn-label-primary me-2" tabindex="0"
                                aria-controls="DataTables_Table_0" type="button" aria-haspopup="dialog"
                                aria-expanded="false"><span><i class="mdi mdi-export-variant me-sm-1"></i> <span
                                        class="d-none d-sm-inline-block">Export</span></span></span>
                            </a>

                            <a class="btn btn-secondary btn-primary text-white" href="{{ route('inventory.add') }}"
                                tabindex="0"><span><i class="mdi mdi-plus me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">Add Inventory</span></span>
                            </a>
                        </div>
                    </div>
                </div>
                <table id="myDatatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Added Date</th>
                            <th>Purchase For</th>
                            <th>Category</th>
                            <th>Product Name</th>
                            <th>Purchased Total Qty</th>
                            <th>Remaining Qty</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--/ DataTable with Buttons -->

    {{-- delete confirmation --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are You Sure Want
                        to Delete?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a id="deleteBtn" href="" class="btn btn-danger">Delete
                    </a>
                </div>
            </div>
        </div>
    </div>
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
                    "url": "{!! route('inventory.get') !!}",
                    "type": 'GET',
                    "data": {
                        "user_type": "admin",
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        name: 'Action',
                        "render": function(data, type, row, meta) {
                            var id = row.id;
                            var routeView = "{{ route('inventory.view', ['id' => 'rowID']) }}";
                            routeView = routeView.replace('rowID', id);

                            var routeActivity = "{{ route('inventory.activity', ['id' => 'rowID']) }}";
                            routeActivity = routeActivity.replace('rowID', id);

                            var text = `<div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" style="">
                                <a class="dropdown-item waves-effect" href="${routeActivity}"><i class="mdi mdi-book-outline me-1"></i> Activity Log</a>
                                <a class="dropdown-item waves-effect" href="${routeView}"><i class="mdi mdi-eye-outline me-1"></i> View</a>
                                </div>
                            </div>`;
                            return text;
                        }
                    },
                    {
                        data: 'created',
                        name: 'created'
                    },
                    {
                        name: 'purchase_for',
                        "render": function(data, type, row, meta) {
                            var id = row.id;

                            var routeView = "{{ route('inventory.view', ['id' => 'rowID']) }}";
                            routeView = routeView.replace('rowID', id);

                            var text =
                                `<a class="dropdown-item waves-effect text-primary" href="${routeView}">${row.purchase_for}</a>`;
                            return text;
                        }
                    },
                    {
                        name: 'category_name',
                        "render": function(data, type, row, meta) {
                            var str = row.category_name;
                            var count = (str.match(/,/g) || []).length + 1;

                            var text =
                                `<span data-bs-toggle="tooltip" data-bs-placement="right" title="${str}"><span class="badge bg-success">${count} Category<img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg"
                                                                alt="" class="ps-2"></span> </span>`;
                            return text;
                        }
                    },
                    {
                        name: 'title',
                        "render": function(data, type, row, meta) {
                            var str = row.title;
                            var count = (str.match(/,/g) || []).length + 1;

                            var text =
                                `<span data-bs-toggle="tooltip" data-bs-placement="right" title="${str}"><span class="badge bg-warning">${count} Product<img src="{{ env('USER_URL') }}/public/userpanel/asset/images/hover.svg"
                                                                alt="" class="ps-2"> </span>`;
                            return text;
                        }
                    },
                    {
                        data: 'main_qty',
                        name: 'main_qty'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                ],
                'rowCallback': function(row, data, index) {
                    $('td', row).css('white-space', 'nowrap');
                    $(function() {
                        $('[data-bs-toggle="tooltip"]').tooltip();
                    });
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

        function deleteModal(route) {
            $('#deleteBtn').attr('href', route);
        }
    </script>
@endsection
