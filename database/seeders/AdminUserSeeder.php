<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Try to find by email or create if not exists
        $admin = User::where('email', 'admin@softui.com')->first();

        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@softui.com',
                'password' => bcrypt('admin123'),
            ]);
        }

        $role = Role::where('slug', 'admin')->first();
        if ($role && !$admin->roles()->where('role_id', $role->id)->exists()) {
            $admin->assignRole($role);
        }
    }
}
