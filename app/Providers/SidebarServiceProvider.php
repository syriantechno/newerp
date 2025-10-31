<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use App\Models\SystemModule;

class SidebarServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            try {
                if (class_exists(SystemModule::class)) {
                    $modules = SystemModule::where('is_active', 1)->orderBy('id')->get();
                } else {
                    $modules = collect();
                }
            } catch (\Throwable $e) {
                Log::error('Sidebar modules error: '.$e->getMessage());
                $modules = collect();
            }

            $view->with([
                'modules' => $modules,
                'autoModules' => $modules,
            ]);
        });
    }
}
