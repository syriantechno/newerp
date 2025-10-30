<?php

namespace App\Http\Controllers\Settings\Rbac;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('is_system', 'desc')->orderBy('name')->get();
        return view('settings.rbac.roles_index', compact('roles'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:roles,slug',
            'description' => 'nullable|string',
        ]);

        Role::create($data);
        return back()->with('success', 'Role created');
    }

    public function update(Request $r, Role $role)
    {
        if ($role->is_system)
            return back()->withErrors('System role cannot be modified');

        $data = $r->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $role->update($data);
        return back()->with('success', 'Role updated');
    }

    public function destroy(Role $role)
    {
        if ($role->is_system)
            return back()->withErrors('System role cannot be deleted');

        $role->delete();
        return back()->with('success', 'Role deleted');
    }
}
