<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n[SEEDER] Seeding roles...\n";

        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Full system access and configuration control.',
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Can manage HR, Attendance, and Approvals modules.',
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Basic access to personal HR information and requests.',
            ],
        ];

        foreach ($roles as $role) {
            $exists = DB::table('roles')->where('slug', $role['slug'])->exists();
            if (!$exists) {
                DB::table('roles')->insert([
                    'name' => $role['name'],
                    'slug' => $role['slug'],
                    'description' => $role['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        echo "[SEEDER] Roles seeded successfully.\n";

        // Auto-assign Admin role to admin@example.com if user exists
        $admin = DB::table('users')->where('email', 'admin@example.com')->first();
        $adminRole = DB::table('roles')->where('slug', 'admin')->first();

        if ($admin && $adminRole) {
            $exists = DB::table('role_user')
                ->where('user_id', $admin->id)
                ->where('role_id', $adminRole->id)
                ->exists();

            if (!$exists) {
                DB::table('role_user')->insert([
                    'user_id' => $admin->id,
                    'role_id' => $adminRole->id,
                ]);
                echo "[SEEDER] Admin role assigned to admin@example.com\n";
            }
        }
    }
}
