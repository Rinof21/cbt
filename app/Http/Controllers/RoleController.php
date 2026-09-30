<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with(['permissions', 'users'])->withCount(['users', 'permissions'])->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            $parts = explode('_', $permission->name);
            return end($parts);
        });
        $allPermissions = Permission::orderBy('name')->get();

        return view('roles.create', compact('permissions', 'allPermissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => strtolower(trim($validated['name'])),
            'guard_name' => 'web',
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', "Role '{$role->name}' berhasil ditambahkan.");
    }

    public function edit(Role $role)
    {
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $allPermissions = Permission::orderBy('name')->get();

        return view('roles.edit', compact('role', 'rolePermissions', 'allPermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $rules = [
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ];

        // Do not allow changing name of super_admin
        if ($role->name !== 'super_admin') {
            $rules['name'] = 'required|string|max:100|unique:roles,name,' . $role->id;
        }

        $validated = $request->validate($rules);

        if ($role->name !== 'super_admin' && isset($validated['name'])) {
            $role->name = strtolower(trim($validated['name']));
            $role->save();
        }

        $permissions = $validated['permissions'] ?? [];
        $role->syncPermissions($permissions);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', "Role '{$role->name}' berhasil diperbarui.");
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'super_admin') {
            return back()->with('error', 'Role super_admin adalah role utama sistem dan tidak boleh dihapus.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', "Role '{$role->name}' masih digunakan oleh {$role->users()->count()} pengguna. Lepaskan role dari pengguna terlebih dahulu.");
        }

        $roleName = $role->name;
        $role->delete();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', "Role '{$roleName}' berhasil dihapus.");
    }
}
