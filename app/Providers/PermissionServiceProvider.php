<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class PermissionServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::define('perm', function ($user, string $module, string $action) {
            return $user->hasPermission($module, $action);
        });

        Route::macro('perm', function (string $module, string $action) {
            /** @var \Illuminate\Routing\Route $this */
            return $this->middleware("perm:{$module},{$action}");
        });

        Blade::directive('canp', function ($expr) {
            // usage: @canp('approvals','add') ... @endcanp
            return "<?php if(auth()->check() && auth()->user()->hasPermission{$expr}): ?>";
        });
        Blade::directive('endcanp', fn() => "<?php endif; ?>");
    }
}
