<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'hr_departments';

    protected $fillable = [
        'name',
        'code',
        'description',
        'company_id',
        'parent_id',
        'manager_id',
        'status',
        'created_by',
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function employees()
    {
        // Assumes HR Employee model exists with department_id column
        return $this->hasMany(\Modules\HR\Models\Employee::class, 'department_id');
    }

    public function manager()
    {
        return $this->belongsTo(\Modules\HR\Models\Employee::class, 'manager_id');
    }

    // Scopes
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('status', 'active');
    }

    public function scopeFilter(Builder $q, array $f = []): Builder
    {
        if (!empty($f['search'])) {
            $s = trim($f['search']);
            $q->where(function (Builder $qq) use ($s) {
                $qq->where('name', 'like', "%{$s}%")
                    ->orWhere('code', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%");
            });
        }
        if (!empty($f['status']) && in_array($f['status'], ['active','inactive'])) {
            $q->where('status', $f['status']);
        }
        if (!empty($f['company_id'])) {
            $q->where('company_id', $f['company_id']);
        }
        if (!empty($f['parent_id'])) {
            $q->where('parent_id', $f['parent_id']);
        }
        return $q;
    }
}
