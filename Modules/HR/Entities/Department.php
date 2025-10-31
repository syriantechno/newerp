<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'hr_departments';
    protected $fillable = ['name'];
    public $timestamps = false;
}
