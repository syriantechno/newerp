<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicFieldGroup extends Model
{
    protected $fillable = ['module', 'name', 'description', 'order'];

    public function fields()
    {
        return $this->hasMany(DynamicField::class, 'group_id');
    }
}
