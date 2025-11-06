@extends('admin.inc.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">
            <a href="{{ route('roles_permission.index') }}">Roles & Permission</a> /
        </span> Edit
    </h4>

    <div class="row d-flex justify-content-center">
        <div class="col-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Roles with Permission</h5>
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

                    <form action="{{ route('roles_permission.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="r_id" value="{{ $data->id }}">
                        <input type="hidden" name="p_id" value="{{ $data->permission_id }}">

                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" name="name" value="{{ $data->name }}" class="form-control"
                                   id="basic-default-fullname" placeholder="Role Name" />
                            <label for="basic-default-fullname">Role Name <span class="text-danger">*</span></label>
                        </div>

                        <div class="col-12 row">
                            <h5>Choose Permissions</h5>

                            {{-- Admin --}}
                            <div class="col-4 mt-1">
                                <input onchange="checkAdmin()" 
                                       type="checkbox" 
                                       name="admin" value="1" 
                                       id="admin"
                                       @if ($data->permission && $data->permission->admin == 1) checked @endif />
                                <label for="admin">Admin (All Permission)</label>
                            </div>

                            {{-- Dynamic Modules --}}
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

                                // ✅ Use correct key name here
                                $modulePermissions = isset($data->modulePermissions)
                                    ? collect($data->modulePermissions)->keyBy('module_name')
                                    : collect();
                            @endphp

                            @foreach ($modules as $key => $label)
                                @php
                                    $mp = $modulePermissions->get($key);
                                    $hasPermission = ($mp || ($data->permission && $data->permission->$key == 1));
                                @endphp

                                <div class="col-4 mt-3">
                                    <input type="checkbox"
                                           class="main-permission"
                                           data-target="{{ $key }}"
                                           name="{{ $key }}"
                                           value="1"
                                           id="{{ $key }}"
                                           @if ($hasPermission) checked @endif>
                                    <label for="{{ $key }}">{{ $label }}</label>

                                    {{-- Sub Permissions --}}
                                    <div class="sub-permissions mt-2 ms-3"
                                         id="{{ $key }}-options"
                                         style="{{ $hasPermission ? 'display:block;' : 'display:none;' }}">

                                        <div>
                                            <input type="checkbox"
                                                   name="{{ $key }}_view"
                                                   id="{{ $key }}_view"
                                                   value="1"
                                                   @if ($mp && $mp->can_view == 1) checked @endif>
                                            <label for="{{ $key }}_view">View</label>
                                        </div>
                                        <div>
                                            <input type="checkbox"
                                                   name="{{ $key }}_edit"
                                                   id="{{ $key }}_edit"
                                                   value="1"
                                                   @if ($mp && $mp->can_edit == 1) checked @endif>
                                            <label for="{{ $key }}_edit">Edit</label>
                                        </div>
                                        <div>
                                            <input type="checkbox"
                                                   name="{{ $key }}_delete"
                                                   id="{{ $key }}_delete"
                                                   value="1"
                                                   @if ($mp && $mp->can_delete == 1) checked @endif>
                                            <label for="{{ $key }}_delete">Delete</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Update</button>
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

    // ✅ Ensure sub-permissions are shown for already checked modules
    $(".main-permission").each(function () {
        let target = $(this).data("target");
        if ($(this).prop("checked")) {
            $("#" + target + "-options").show();
        } else {
            $("#" + target + "-options").hide();
        }
    });

    // ✅ Toggle nested visibility dynamically
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
