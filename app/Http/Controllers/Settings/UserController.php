<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $r)
    {
        $r->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $r->name,
            'email' => $r->email,
            'password' => Hash::make($r->password),
        ]);

        return back()->with('success', "User '{$user->name}' created successfully");
    }

    public function update(Request $r, User $user)
    {
        $r->validate([
            'name' => 'required|string|max:100',
            'email' => "required|email|unique:users,email,{$user->id}",
        ]);

        $user->update($r->only('name', 'email'));

        return back()->with('success', "User '{$user->name}' updated successfully");
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', "User deleted successfully");
    }
}
