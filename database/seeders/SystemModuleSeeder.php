<?php

namespace Database\Seeders;

use App\Models\SystemModule;
use Illuminate\Database\Seeder;

class SystemModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            [
                'name' => 'HR',
                'label' => 'HR System',
                'icon' => 'ni ni-check-bold',
                'route_prefix' => 'hr',
                'route' => 'hr.employees.index',
                'is_active' => 1,
                'order' => 1,
            ],
            [
                'name' => 'Approvals',
                'label' => 'Approvals',
                'icon' => 'ni ni-send',
                'route_prefix' => 'approvals',
                'route' => 'approvals.index',
                'is_active' => 1,
                'order' => 2,
            ],
        ];

        foreach ($modules as $data) {
            // ✅ إضافة فقط إن لم يكن موجود بنفس الاسم أو المسار
            SystemModule::updateOrCreate(
                ['route' => $data['route']],
                $data
            );
        }

        $this->command->info('✅ System Modules seeded successfully.');
    }
}
