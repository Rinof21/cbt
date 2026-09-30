<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('roles')->withCount('roles')->orderBy('name')->get();
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('permissions.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:permissions,name',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $permission = Permission::create([
            'name' => strtolower(trim($validated['name'])),
            'guard_name' => 'web',
        ]);

        if (!empty($validated['roles'])) {
            $permission->syncRoles($validated['roles']);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('permissions.index')->with('success', "Permission '{$permission->name}' berhasil ditambahkan.");
    }

    public function edit(Permission $permission)
    {
        $roles = Role::orderBy('name')->get();
        $permissionRoles = $permission->roles->pluck('name')->toArray();
        return view('permissions.edit', compact('permission', 'roles', 'permissionRoles'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:permissions,name,' . $permission->id,
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $permission->name = strtolower(trim($validated['name']));
        $permission->save();

        $roles = $validated['roles'] ?? [];
        $permission->syncRoles($roles);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('permissions.index')->with('success', "Permission '{$permission->name}' berhasil diperbarui.");
    }

    public function destroy(Permission $permission)
    {
        $name = $permission->name;
        $permission->delete();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('permissions.index')->with('success', "Permission '{$name}' berhasil dihapus.");
    }
}
