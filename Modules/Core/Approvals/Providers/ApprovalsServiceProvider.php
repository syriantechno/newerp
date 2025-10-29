<?php
//
//namespace Modules\Core\Approvals\Providers;
//
//use Illuminate\Support\ServiceProvider;
//
//class ApprovalsServiceProvider extends ServiceProvider
//{
//    public function boot()
//    {
//        // تحميل المسارات
//        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
//
//
//        // تحميل الـ views
//        $this->loadViewsFrom(base_path('Modules/Core/Approvals/Resources/views'), 'core');
//
//        // تحميل الـ migrations
//        $this->loadMigrationsFrom(base_path('Modules/Core/Approvals/Database/migrations'));
//    }
//}




namespace Modules\Core\Approvals\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ApprovalsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // تسجيل المسارات يدويًا
        Route::middleware('web')
            ->group(__DIR__ . '/../Routes/web.php');

        // تحميل الـ views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'core');

        // تحميل الـ migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
    }
}

