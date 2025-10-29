<?php

namespace Modules\HR\Providers;

use Illuminate\Support\ServiceProvider;

class HRServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // إذا بدك تسجل أي binding أو helper مستقبلاً
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // ✅ تحميل المسارات الخاصة بالموديول
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        // ✅ تحميل ملفات العرض (views)
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'hr');

        // ✅ تحميل الـ migrations (لو عندك)
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
    }
}
