<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicFieldValue extends Model
{
    protected $fillable = ['module', 'record_id', 'field_id', 'value'];

    public function field()
    {
        return $this->belongsTo(DynamicField::class, 'field_id');
    }
}

