<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class ModuleScanner
{
    public static function getModules(): array
    {
        $modulesPath = base_path('Modules');
        if (!File::isDirectory($modulesPath)) {
            return [];
        }

        $modules = [];
        foreach (File::directories($modulesPath) as $dir) {
            $jsonPath = $dir . '/module.json';
            if (!File::exists($jsonPath)) {
                continue;
            }

            try {
                $data = json_decode(File::get($jsonPath), true);
                if (!empty($data['active'])) {
                    $modules[] = [
                        'name' => $data['name'] ?? basename($dir),
                        'label' => $data['label'] ?? basename($dir),
                        'icon' => $data['icon'] ?? 'ni ni-folder-17',
                        'route' => $data['route'] ?? '#',
                        'order' => $data['order'] ?? 100,
                    ];
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        // ترتيب حسب order ثم label
        usort($modules, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        return $modules;
    }
}
