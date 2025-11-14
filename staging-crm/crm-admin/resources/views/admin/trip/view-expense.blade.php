@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">

                <table id="myDatatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action </th>
                            <th>Created Date </th>
                            <th>Payment Date </th>
                            <th>Vendor Company Name</th>
                            <th>Service Amount</th>
                            <th>Payment Mode</th>
                            <th>Added By</th>
                            <th>Comment</th>
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

    {{-- expense payment edit --}}
    <div class="modal fade" id="editVendorPayment" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Vendor Payment Entry</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('trip.details.edit-expense-history') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" id="expense_history_id" value="">

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" name="amount" id="amount" class="form-control"
                                            placeholder="₹ Amount" required>
                                        <label for="basic-default-redeem">Amount<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <select name="payment_mode" class="form-control" id="payment_mode">
                                            <option value="">Select</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="UPI">UPI</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Credit Card">Credit Card</option>
                                        </select>
                                        <label for="basic-default-redeem">Payment Mode<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" name="date" id="date" class="form-control" required>
                                        <label for="basic-default-redeem">Date<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="comment" id="commant" class="form-control"
                                            placeholder="comment">
                                        <label for="basic-default-redeem">Comment</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="text-center">
                                    <button class="btn btn-warning " type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
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

            var table = $('#myDatatable').DataTable({
                "lengthMenu": [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                "order": [],
                "processing": true,
                "destroy": true,
                "ajax": {
                    "url": "{!! route('trip.details.get') !!}",
                    "type": 'GET',
                    "data": {
                        "expense_id": "{{ request()->id }}",
                    }
                },
                "serverSide": true,
                "deferRender": true,
                "columns": [{
                        name: 'action',
                        "render": function(data, type, row, meta) {
                            var id = row.id;
                            var routeDlt = "{{ route('trip.details.delete-expense', ['id' => 'rowID']) }}";
                            routeDlt = routeDlt.replace('rowID', id);
                            if (row.editable) {
                                var text =
                                    `<div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu" style="">
                                    <a class="dropdown-item waves-effect" onclick="editExpenseHistory('${id}', '${row.payment_date_real}', '${row.amount}', '${row.payment_mode}', '${row.comment}')" href="javaScript:void(0)"><i class="mdi mdi-pen me-1"></i> Edit</a>
                                    <a class="dropdown-item waves-effect" data-bs-toggle="modal" onclick="deleteModal('${routeDlt}')"
                                                            data-bs-target="#deleteModal" href="javaScript:void(0)"><i class="mdi mdi-trash-can-outline me-1"></i> Delete</a>
                                    </div>
                                </div>`;
                            } else {
                                var text = '';
                            }
                            return text;
                        }
                    },
                    {
                        data: 'created',
                        name: 'created'
                    }, {
                        data: 'payment_date',
                        name: 'payment_date'
                    }, {
                        data: 'vendor_name',
                        name: 'vendor_name'
                    }, {
                        name: 'amount',
                        "render": function(data, type, row, meta) {
                            var text = "₹" + row.amount;
                            return text;
                        }
                    }, {
                        data: 'payment_mode',
                        name: 'payment_mode'
                    }, {
                        data: 'added_by',
                        name: 'added_by'
                    }, {
                        data: 'comment',
                        name: 'comment'
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

        function deleteModal(route) {
            $('#deleteBtn').attr('href', route);
        }

        function editExpenseHistory(id, date, amount, mode, comment) {
            $("#expense_history_id").val(id);
            $("#date").val(date);
            $("#amount").val(amount);
            $("#payment_mode").val(mode);
            $("#comment").val(comment);
            $("#editVendorPayment").modal('show');
        }
    </script>
@endsection
