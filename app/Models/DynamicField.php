<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicField extends Model
{
    protected $fillable = [
        'module', 'name', 'label', 'type', 'options', 'is_required',
        'validation', 'visibility', 'group_id', 'is_active', 'order'
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(DynamicFieldGroup::class, 'group_id');
    }

    public function values()
    {
        return $this->hasMany(DynamicFieldValue::class, 'field_id');
    }
}
