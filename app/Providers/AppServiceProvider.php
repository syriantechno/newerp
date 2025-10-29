<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */


        public function boot(): void
    {
        $modulesPath = base_path('Modules');
        if (File::exists($modulesPath)) {
            $modules = File::directories($modulesPath);

            foreach ($modules as $module) {
                $routesPath = $module . '/Routes/web.php';
                $viewsPath  = $module . '/Views';

                // تحميل الراوتات
                if (File::exists($routesPath)) {
                    Route::middleware('web')
                        ->group($routesPath);
                }

                // تحميل الواجهات
                if (File::isDirectory($viewsPath)) {
                    $moduleName = basename($module);
                    $this->loadViewsFrom($viewsPath, strtolower($moduleName));
                }

                // تحميل جميع ملفات الـ migrations الخاصة بالموديولات
                foreach (File::directories(base_path('Modules')) as $modulePath) {
                    $migrationPath = $modulePath . '/Database/Migrations';
                    if (File::isDirectory($migrationPath)) {
                        $this->loadMigrationsFrom($migrationPath);
                    }
                }

            }
        }
    }


}
