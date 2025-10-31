<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n[SEEDER] Seeding permissions...\n";

        // Base permissions per module
        $permissions = [
            // HR Employees
            ['name' => 'view_employees', 'module' => 'hr', 'label' => 'View Employees'],
            ['name' => 'create_employees', 'module' => 'hr', 'label' => 'Create Employees'],
            ['name' => 'edit_employees', 'module' => 'hr', 'label' => 'Edit Employees'],
            ['name' => 'delete_employees', 'module' => 'hr', 'label' => 'Delete Employees'],

            // HR Attendance
            ['name' => 'view_attendance', 'module' => 'hr', 'label' => 'View Attendance'],
            ['name' => 'mark_attendance', 'module' => 'hr', 'label' => 'Mark Attendance'],
            ['name' => 'import_attendance', 'module' => 'hr', 'label' => 'Import Attendance'],
            ['name' => 'export_attendance', 'module' => 'hr', 'label' => 'Export Attendance'],

            // HR Departments
            ['name' => 'view_departments', 'module' => 'hr', 'label' => 'View Departments'],
            ['name' => 'create_departments', 'module' => 'hr', 'label' => 'Create Departments'],
            ['name' => 'edit_departments', 'module' => 'hr', 'label' => 'Edit Departments'],
            ['name' => 'delete_departments', 'module' => 'hr', 'label' => 'Delete Departments'],

            // Approvals
            ['name' => 'view_approvals', 'module' => 'approvals', 'label' => 'View Approvals'],
            ['name' => 'create_approvals', 'module' => 'approvals', 'label' => 'Create Approval Requests'],
            ['name' => 'approve_requests', 'module' => 'approvals', 'label' => 'Approve Requests'],
            ['name' => 'reject_requests', 'module' => 'approvals', 'label' => 'Reject Requests'],

            // Settings
            ['name' => 'view_settings', 'module' => 'settings', 'label' => 'View Settings'],
            ['name' => 'update_settings', 'module' => 'settings', 'label' => 'Update Settings'],
            ['name' => 'manage_users', 'module' => 'settings', 'label' => 'Manage Users'],
            ['name' => 'manage_roles', 'module' => 'settings', 'label' => 'Manage Roles'],
        ];

        foreach ($permissions as $perm) {
            $exists = DB::table('permissions')->where('name', $perm['name'])->exists();
            if (!$exists) {
                DB::table('permissions')->insert([
                    'name' => $perm['name'],
                    'module' => $perm['module'],
                    'label' => $perm['label'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        echo "[SEEDER] Permissions seeded successfully.\n";

        // Assign all permissions to Admin role automatically
        if (DB::table('roles')->where('slug', 'admin')->exists()) {
            $admin = DB::table('roles')->where('slug', 'admin')->first();
            $allPerms = DB::table('permissions')->pluck('id')->toArray();

            foreach ($allPerms as $pid) {
                $exists = DB::table('role_permission')
                    ->where('role_id', $admin->id)
                    ->where('permission_id', $pid)
                    ->exists();

                if (!$exists) {
                    DB::table('role_permission')->insert([
                        'role_id' => $admin->id,
                        'permission_id' => $pid,
                    ]);
                }
            }

            echo "[SEEDER] All permissions granted to Admin role.\n";
        }
    }
}
