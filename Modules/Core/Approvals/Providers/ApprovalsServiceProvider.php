<?php

namespace Modules\Core\Approvals\Providers;

use Illuminate\Support\ServiceProvider;

class ApprovalsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // هنا يمكن لاحقًا تسجيل أي binding خاص بالموديول
    }

    public function boot(): void
    {
        // تحميل المسارات الخاصة بالموديول
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        // تحميل ملفات الواجهات وتحديد alias = 'core'
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'core');

        // تحميل ملفات الـmigrations تلقائيًا
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
