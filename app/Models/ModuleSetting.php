<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleSetting extends Model
{
    protected $table = 'modules_settings';
    protected $fillable = [
        'name', 'label', 'icon', 'route', 'active', 'order', 'path'
    ];
}
