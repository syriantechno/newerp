<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemModule extends Model
{
    protected $fillable = [
        'name', 'label', 'icon', 'route_prefix', 'is_active'
    ];
}
