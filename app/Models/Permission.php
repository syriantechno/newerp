<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = ['module','action','label','slug','description'];

    protected static function booted(): void {
        static::creating(function ($perm) {
            $perm->slug = $perm->slug ?: "{$perm->module}.{$perm->action}";
        });
    }

    public function roles(): BelongsToMany {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
}

