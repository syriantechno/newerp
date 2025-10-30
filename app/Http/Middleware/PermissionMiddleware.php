<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $module, $action)
    {
        $user = Auth::user();

        if (!$user || !$user->hasPermission($module, $action)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
