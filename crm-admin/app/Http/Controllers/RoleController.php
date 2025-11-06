<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\ModulePermission;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Response;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.role.index');
    }

    public function get()
    {
        $data = Role::orderBy('id', 'Desc')->get();
        
        $data->map(function ($item, $index) {
            $perArray = collect($item->permission->toArray());
            $perArray->shift();
            $perArray->splice(-2);
            
            $keysWithValueOne = collect($perArray)
                ->filter(function ($value) {
                    return $value === 1;
                })->keys()->toArray();
                
            $item->created = date("M d, Y", strtotime($item->created_at));
            $item->permission_name = $keysWithValueOne;
        });
        return DataTables::of($data)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $users = User::all();
        return view('admin.role.add', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $permission = new Permission();
        if($request->admin){
            $permission->admin = $request->admin;
        }
        if($request->setting){
            $permission->setting = $request->setting;
        }
        if($request->roles_permission){
            $permission->roles_permission = $request->roles_permission;
        }
        if($request->staff){
            $permission->staff = $request->staff;
        }
        if($request->trip){
            $permission->trip = $request->trip;
        }
        if($request->booking){
            $permission->booking = $request->booking;
        }
        if($request->enquiry){
            $permission->enquiry = $request->enquiry;
        }
        if($request->customer){
            $permission->customer = $request->customer;
        }
        if($request->agent){
            $permission->agent = $request->agent;
        }
        if($request->vendors){
            $permission->vendors = $request->vendors;
        }
        if($request->inventory_category){
            $permission->inventory_category = $request->inventory_category;
        }
        if($request->inventory){
            $permission->inventory = $request->inventory;
        }
        if($request->report){
            $permission->report = $request->report;
        }
        if($request->loyalty){
            $permission->loyalty = $request->loyalty;
        }
        if($request->loyalty){
            $permission->loyalty = $request->loyalty;
        }
        if($request->sustainability){
            $permission->sustainability = $request->sustainability;
        }
        if($request->accounts){
            $permission->accounts = $request->accounts;
        }
        
        if($request->accounts){
            $permission->birthdays = $request->birthdays;
        }

        $permission->save();

        // role
        $role = new Role();
        $role->name = $request->name;
        $role->permission_id = $permission->id;
        $role->save();

        // Add role permissions in modulepermission
        $modules = [
            'setting', 'roles_permission', 'staff', 'trip', 'booking', 'enquiry', 
            'customer', 'agent', 'vendors', 'inventory_category', 'inventory', 
            'report', 'loyalty', 'sustainability', 'accounts', 'birthdays'
        ];

        foreach ($modules as $module) {
            if ($request->has($module)) {
                \App\Models\ModulePermission::create([
                    'role_id' => $role->id,
                    'module_name' => $module,
                    'can_view'   => $request->has($module.'_view') ? 1 : 0,
                    'can_edit'   => $request->has($module.'_edit') ? 1 : 0,
                    'can_delete' => $request->has($module.'_delete') ? 1 : 0,
                ]);
            }
        }
        
        return redirect(route('roles_permission.index'))->with('success', 'Updated Successfully !!');
    }


    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // $data = Role::where('id', $id)->first();
        $data = Role::with(['permission', 'modulePermissions'])->find($id);
        return view('admin.role.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $permission = Permission::find($request->p_id);
        if($request->admin){
            $permission->admin = $request->admin;
        }else{
            $permission->admin = 0;
        }
        if($request->setting){
            $permission->setting = $request->setting;
        }else{
            $permission->setting = 0;
        }
        if($request->roles_permission){
            $permission->roles_permission = $request->roles_permission;
        }else{
            $permission->roles_permission = 0;
        }
        if($request->staff){
            $permission->staff = $request->staff;
        }else{
            $permission->staff = 0;
        }
        if($request->trip){
            $permission->trip = $request->trip;
        }else{
            $permission->trip = 0;
        }
        if($request->booking){
            $permission->booking = $request->booking;
        }else{
            $permission->booking = 0;
        }
        if($request->enquiry){
            $permission->enquiry = $request->enquiry;
        }else{
            $permission->enquiry = 0;
        }
        if($request->customer){
            $permission->customer = $request->customer;
        }else{
            $permission->customer = 0;
        }
        if($request->agent){
            $permission->agent = $request->agent;
        }else{
            $permission->agent = 0;
        }
        if($request->vendors){
            $permission->vendors = $request->vendors;
        }else{
            $permission->vendors = 0;
        }
        if($request->inventory_category){
            $permission->inventory_category = $request->inventory_category;
        }else{
            $permission->inventory_category = 0;
        }
        if($request->inventory){
            $permission->inventory = $request->inventory;
        }else{
            $permission->inventory = 0;
        }
        if($request->report){
            $permission->report = $request->report;
        }else{
            $permission->report = 0;
        }
        if($request->loyalty){
            $permission->loyalty = $request->loyalty;
        }else{
            $permission->loyalty = 0;
        }
        if($request->sustainability){
            $permission->sustainability = $request->sustainability;
        }else{
            $permission->sustainability = 0;
        }
        if($request->accounts){
            $permission->accounts = $request->accounts;
        }else{
            $permission->accounts = 0;
        }
        
        if($request->birthdays){
            $permission->birthdays = $request->birthdays;
        }else{
            $permission->birthdays = 0;
        }

        $permission->save();

        // role
        $role = Role::find($request->r_id);
        $role->name = $request->name;
        $role->save();

        ModulePermission::where('role_id', $role->id)->delete();
        $modules = [
            'setting', 'roles_permission', 'staff', 'trip', 'booking', 'enquiry', 
            'customer', 'agent', 'vendors', 'inventory_category', 'inventory', 
            'report', 'loyalty', 'sustainability', 'accounts', 'birthdays'
        ];

        foreach ($modules as $module) {
            if ($request->has($module)) {
                ModulePermission::create([
                    'role_id' => $role->id,
                    'module_name' => $module,
                    'can_view'   => $request->has($module.'_view') ? 1 : 0,
                    'can_edit'   => $request->has($module.'_edit') ? 1 : 0,
                    'can_delete' => $request->has($module.'_delete') ? 1 : 0,
                ]);
            }
        }
        
        return redirect(route('roles_permission.index'))->with('success', 'Updated Successfully !!');
    }
    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id, Request $request)
    // {
    //     $check = User::where('role_id', $id)->first();
    //     if($check){
    //         return redirect(route('roles_permission.index'))->with('warning', 'This Role is Assigned to a User!!');
    //     }else{
    //         $per = Permission::find($request->p_id);
    //         $per->delete();

    //         $role = Role::find($id);
    //         $role->delete();
    //         return redirect(route('roles_permission.index'))->with('success', 'Deleted Successfully!!');
    //     }

    //     return redirect(route('roles_permission.index'))->with('error', 'Somthing went Wrong!!');

    // }
    public function destroy($id, Request $request)
    {
        $check = User::where('role_id', $id)->first();
        if ($check) {
            return redirect(route('roles_permission.index'))
                ->with('warning', 'This Role is Assigned to a User!!');
        }

        $role = Role::with(['permission', 'modulePermissions'])->find($id);

        if (!$role) {
            return redirect(route('roles_permission.index'))
                ->with('error', 'Role not found!');
        }

        try {
            ModulePermission::where('role_id', $role->id)->delete();
            if ($role->permission) {
                $role->permission->delete();
            }

            $role->delete();

            return redirect(route('roles_permission.index'))
                ->with('success', 'Role and related permissions deleted successfully!');
        } catch (\Exception $e) {
            return redirect(route('roles_permission.index'))
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

}