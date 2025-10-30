<?php
namespace App\Models\Concerns;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

trait HasRoles
{
    public function roles(): BelongsToMany {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function assignRole(string|Role $role): void {
        $role = $role instanceof Role ? $role : Role::where('slug',$role)->firstOrFail();
        $this->roles()->syncWithoutDetaching([$role->id]);
        $this->flushPermissionCache();
    }

    public function revokeRole(string|Role $role): void {
        $role = $role instanceof Role ? $role : Role::where('slug',$role)->firstOrFail();
        $this->roles()->detach($role->id);
        $this->flushPermissionCache();
    }

    public function hasPermission(string $module, string $action): bool {
        $cacheKey = "uperm:{$this->id}";
        $perms = Cache::remember($cacheKey, 3600, function () {
            return $this->roles()->with('permissions')->get()
                ->flatMap(fn($r) => $r->permissions->map->slug)
                ->values()
                ->unique()
                ->all();
        });

        if (in_array('*.*', $perms, true)) return true;
        if (in_array($module.'.*', $perms, true)) return true;
        return in_array($module.'.'.$action, $perms, true);
    }

    public function flushPermissionCache(): void {
        Cache::forget("uperm:{$this->id}");
    }
}
