<?php

namespace Modules\Core\Approvals\Providers;

use Illuminate\Support\ServiceProvider;

class ApprovalsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // تحميل المسارات
        $this->loadRoutesFrom(base_path('Modules/Core/Approvals/Routes/web.php'));

        // تحميل الـ views
        $this->loadViewsFrom(base_path('Modules/Core/Approvals/Resources/views'), 'core');

        // تحميل الـ migrations
        $this->loadMigrationsFrom(base_path('Modules/Core/Approvals/Database/migrations'));
    }
}
