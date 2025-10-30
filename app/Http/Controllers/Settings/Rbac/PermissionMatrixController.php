<?php

namespace App\Http\Controllers\Settings\Rbac;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionMatrixController extends Controller
{
    public function index(Request $r, $roleId = null)
    {
        $roles = Role::orderBy('name')->get();
        $activeRole = $roleId ? Role::find($roleId) : $roles->first();
        $permissions = Permission::orderBy('module')->orderBy('action')->get()->groupBy('module');

        return view('settings.rbac.permissions_matrix', compact('roles', 'permissions', 'activeRole'));
    }

    public function sync(Request $r, Role $role)
    {
        $ids = collect($r->input('permission_ids', []))
            ->map(fn($id) => (int)$id)
            ->filter()
            ->values()
            ->all();

        $role->permissions()->sync($ids);

        foreach ($role->users as $u)
            $u->flushPermissionCache();

        return back()->with('success', 'Permissions updated');
    }
}
