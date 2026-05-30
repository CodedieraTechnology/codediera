<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles'],
            'description' => ['nullable', 'string', 'max:500'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '_'),
            'description' => $request->description,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('status', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
            'description' => ['nullable', 'string', 'max:500'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => $role->slug === 'super_admin' ? 'super_admin' : Str::slug($request->name, '_'),
            'description' => $request->description,
        ]);

        if ($role->slug !== 'super_admin') {
            $role->permissions()->sync($request->permissions ?? []);
        } else {
            // Super admin always has all permissions
            $allPermissions = Permission::pluck('id')->toArray();
            $role->permissions()->sync($allPermissions);
        }

        return redirect()->route('admin.roles.index')->with('status', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->slug === 'super_admin') {
            return redirect()->route('admin.roles.index')->with('error', 'The Super Admin role cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('status', 'Role deleted successfully.');
    }
}
