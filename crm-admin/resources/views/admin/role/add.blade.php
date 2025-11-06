@extends('admin.inc.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">
            <a href="{{ route('roles_permission.index') }}">Roles & Permission</a> /
        </span> Add
    </h4>

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
                            <input type="text" name="name" class="form-control" id="basic-default-fullname" placeholder="Role Name" />
                            <label for="basic-default-fullname">Role Name <span class="text-danger">*</span></label>
                        </div>

                        <div class="col-12 row">
                            <h5>Choose Permissions</h5>

                            {{-- Admin --}}
                            <div class="col-4 mt-1">
                                <input onchange="checkAdmin()" type="checkbox" name="admin" value="1" id="admin" />
                                <label for="admin">Admin (All Permission)</label>
                            </div>

                            {{-- Modules with View/Edit/Delete --}}
                            @php
                                $modules = [
                                    'setting' => 'Setting',
                                    'roles_permission' => 'Roles & Permission',
                                    'staff' => 'Team',
                                    'trip' => 'Trip',
                                    'booking' => 'Booking',
                                    'enquiry' => 'Enquiry',
                                    'customer' => 'Customer',
                                    'agent' => 'Agents',
                                    'vendors' => 'Vendors',
                                    'inventory_category' => 'Inventory-Category',
                                    'inventory' => 'Inventory Stock',
                                    'report' => 'Reports',
                                    'loyalty' => 'Loyalty Program',
                                    'sustainability' => 'Sustainability',
                                    'accounts' => 'Accounts',
                                    'birthdays' => 'Birthdays'
                                ];
                            @endphp

                            @foreach($modules as $key => $label)
                                <div class="col-4 mt-2">
                                    <input type="checkbox" class="main-permission" data-target="{{ $key }}" name="{{ $key }}" value="1" id="{{ $key }}">
                                    <label for="{{ $key }}">{{ $label }}</label>

                                    <div class="sub-permissions mt-2 ms-3" id="{{ $key }}-options" style="display:none;">
                                        <div>
                                            <input type="checkbox" name="{{ $key }}_view" value="2" id="{{ $key }}_view">
                                            <label for="{{ $key }}_view">View</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="{{ $key }}_edit" value="3" id="{{ $key }}_edit">
                                            <label for="{{ $key }}_edit">Edit</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="{{ $key }}_delete" value="4" id="{{ $key }}_delete">
                                            <label for="{{ $key }}_delete">Delete</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Submit</button>
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
    function checkAdmin() {
        var isChecked = $("input[name='admin']").prop("checked");
        if (isChecked) {
            $(":checkbox").prop("checked", true);
            $(".sub-permissions").slideDown();
        } else {
            $(":checkbox").prop("checked", false);
            $(".sub-permissions").slideUp();
        }
    }

    $(document).ready(function () {
        // Show/hide sub-permissions when main checkbox changes
        $(".main-permission").change(function () {
            let target = $(this).data("target");
            if ($(this).prop("checked")) {
                $("#" + target + "-options").slideDown();
            } else {
                $("#" + target + "-options").slideUp();
                $("#" + target + "-options input[type='checkbox']").prop("checked", false);
            }
        });
    });
</script>
@endsection
