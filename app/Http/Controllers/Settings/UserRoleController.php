<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function assign(Request $r)
    {
        $r->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::find($r->user_id);
        $role = Role::find($r->role_id);

        $user->roles()->sync([$role->id]);
        $user->flushPermissionCache();

        return back()->with('success', "Role '{$role->name}' assigned to {$user->name}");
    }
}
