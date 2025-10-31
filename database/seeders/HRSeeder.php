<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HRSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n[SEEDER] Seeding default HR structure...\n";

        // 1️⃣ Company
        $companyId = DB::table('hr_companies')->insertGetId([
            'name'          => 'Default Company',
            'trade_license' => 'TL-0001',
            'vat_number'    => 'VAT-0001',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        echo "[SEEDER] Company created (ID: $companyId)\n";

        // 2️⃣ Department
        $departmentId = DB::table('hr_departments')->insertGetId([
            'name'         => 'General Department',
            'code'         => 'DEPT001',
            'description'  => 'Default department for initial employees',
            'company_id'   => $companyId,
            'parent_id'    => null,
            'manager_id'   => null,
            'status'       => 'active',
            'created_by'   => 1,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
        echo "[SEEDER] Department created (ID: $departmentId)\n";

        // 3️⃣ Employee
        $employeeId = DB::table('hr_employees')->insertGetId([
            'company_id'     => $companyId,
            'department_id'  => $departmentId,
            'designation_id' => null,
            'emp_code'       => 'EMP001',
            'name'           => 'Default Employee',
            'email'          => 'employee@defaultcompany.com',
            'phone'          => '0500000000',
            'join_date'      => now()->toDateString(),
            'notes'          => 'Auto-created by HRSeeder',
            'status'         => 'active',
            'extra'          => null,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
        echo "[SEEDER] Employee created (ID: $employeeId)\n";

        // 4️⃣ User account (if users table exists)
        if (DB::getSchemaBuilder()->hasTable('users')) {
            $userId = DB::table('users')->insertGetId([
                'name'       => 'Default Employee',
                'email'      => 'employee@defaultcompany.com',
                'password'   => Hash::make('employee123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "[SEEDER] User created (ID: $userId, email: employee@defaultcompany.com, pass: employee123)\n";

            // 5️⃣ Assign role "user" if exists
            if (DB::getSchemaBuilder()->hasTable('roles') && DB::getSchemaBuilder()->hasTable('role_user')) {
                $userRole = DB::table('roles')->where('slug', 'user')->first();
                if ($userRole) {
                    DB::table('role_user')->insertOrIgnore([
                        'user_id' => $userId,
                        'role_id' => $userRole->id,
                    ]);
                    echo "[SEEDER] Role 'user' assigned to Default Employee.\n";
                }
            }
        }

        echo "[SEEDER] HRSeeder completed successfully.\n";
    }
}
