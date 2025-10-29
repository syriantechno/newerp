<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name',        // اسم الموديول (HR, Inventory...)
        'slug',        // الرابط الخاص (hr, inventory...)
        'icon_svg',    // كود SVG أو null
        'is_active',   // حالة التفعيل
        'order',       // ترتيب الظهور في القائمة
    ];
}
