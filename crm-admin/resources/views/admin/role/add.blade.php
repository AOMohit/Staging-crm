@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="{{ route('roles_permission.index') }}">Roles &
                    Permission</a>/</span> Add</h4>

        <!-- Basic Layout -->
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add Roles with Permission</h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('roles_permission.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="name" class="form-control" id="basic-default-fullname"
                                    placeholder="Role Name" />
                                <label for="basic-default-fullname">Role Name <span class="text-danger">*</span></label>
                            </div>

                            <div class="col-12 row">
                                <h5>Choose Permissions</h5>
                                <div class="col-4 mt-1">
                                    <input onchange="checkAdmin()" type="checkbox" name="admin" value="1"
                                        id="admin" />
                                    <label for="admin">Admin (All Permission) </label>
                                </div>
                                <div class="col-4 mt-1">
                                    <input type="checkbox" name="setting" value="1" id="setting" />
                                    <label for="setting">Setting </label>
                                </div>
                                <div class="col-4 mt-1">
                                    <input type="checkbox" name="roles_permission" value="1" id="roles_permission" />
                                    <label for="roles_permission">Roles and Permission </label>
                                </div>
                                <div class="col-4 mt-3">
                                    <input type="checkbox" name="staff" value="1" id="staff" />
                                    <label for="staff">Team </label>
                                </div>
                                <div class="col-4 mt-3">
                                    <input type="checkbox" name="trip" value="1" id="trip" />
                                    <label for="trip">Trip </label>
                                </div>
                                <div class="col-4 mt-3">
                                    <input type="checkbox" name="booking" value="1" id="booking" />
                                    <label for="booking">Booking </label>
                                </div>
                                <div class="col-4 mt-3">
                                    <input type="checkbox" name="enquiry" value="1" id="enquiry" />
                                    <label for="enquiry">Enquiry </label>
                                </div>
                                <div class="col-4 mt-3">
                                    <input type="checkbox" name="customer" value="1" id="customer" />
                                    <label for="customer">Customer </label>
                                </div>
                                <div class="col-4 mt-3">
                                    <input type="checkbox" name="agent" value="1" id="agent" />
                                    <label for="agent">Agents</label>
                                </div>
                                <div class="col-4 mt-3">
                                    <input type="checkbox" name="vendors" value="1" id="vendors" />
                                    <label for="vendors">Vendors</label>
                                </div>
                                <div class="col-4 mt-3">
                                    <input type="checkbox" name="inventory_category" value="1"
                                        id="inventory_category" />
                                    <label for="inventory_category">Inventory-Category</label>
                                </div>
                                <div class="col-4 mt-3">
                                    <input type="checkbox" name="inventory" value="1" id="inventory" />
                                    <label for="inventory">Inventory Stock</label>
                                </div>

                                <div class="col-4 mt-3">
                                    <input type="checkbox" name="report" value="1" id="report" />
                                    <label for="report">Reports</label>
                                </div>

                                <div class="col-4 mt-3">
                                    <input type="checkbox" name="loyalty" value="1" id="loyalty" />
                                    <label for="loyalty">Loyalty Program</label>
                                </div>

                                <div class="col-4 mt-3">
                                    <input type="checkbox" name="sustainability" value="1" id="sustainability" />
                                    <label for="sustainability">Sustainability</label>
                                </div>

                                <div class="col-4 mt-3">
                                    <input type="checkbox" name="accounts" value="1" id="accounts" />
                                    <label for="accounts">Accounts</label>
                                </div>
                                
                                <div class="col-4 mt-3">
                                    <input type="checkbox" name="birthdays" value="1" id="birthdays" />
                                    <label for="birthdays">Birthdays</label>
                                </div>

                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('script')
        <script>
            function checkAdmin() {

                var isChecked = $("input[name='admin']").prop("checked");
                if (isChecked) {
                    $(":checkbox").prop("checked", true);
                } else {
                    $(":checkbox").prop("checked", false);
                }
            }
        </script>
    @endsection
