<?php

namespace App\Services;

use App\Models\ModuleSetting;
use Illuminate\Support\Facades\File;

class SyncModulesService
{
    public static function sync(): void
    {
        $modulesPath = base_path('Modules');
        if (!File::isDirectory($modulesPath)) {
            return;
        }

        $foundModules = [];

        // بحث متداخل داخل كل المجلدات (Core, HR, Approvals...)
        $stack = [$modulesPath];
        while (!empty($stack)) {
            $current = array_pop($stack);

            foreach (File::directories($current) as $dir) {
                $stack[] = $dir;

                $json = $dir . DIRECTORY_SEPARATOR . 'module.json';
                if (!File::exists($json)) continue;

                try {
                    $data = json_decode(File::get($json), true);
                } catch (\Throwable $e) {
                    continue;
                }

                if (!$data || !isset($data['name'])) continue;

                $name  = $data['name'];
                $label = $data['label'] ?? $name;

                $foundModules[$name] = [
                    'name'   => $name,
                    'label'  => $label,
                    'icon'   => $data['icon'] ?? 'ni ni-folder-17',
                    'route'  => $data['route'] ?? null,
                    'active' => $data['active'] ?? true,
                    'order'  => $data['order'] ?? 100,
                    'path'   => str_replace(base_path(), '', $dir),
                ];
            }
        }

        // حفظ التعديلات في قاعدة البيانات
        foreach ($foundModules as $name => $module) {
            ModuleSetting::updateOrCreate(
                ['name' => $name],
                $module
            );
        }

        // حذف أي موديول لم يعد موجود فعليًا
        $existingNames = array_keys($foundModules);
        ModuleSetting::whereNotIn('name', $existingNames)->delete();

        echo "[SyncModulesService] Synced " . count($foundModules) . " modules.\n";
    }
}
