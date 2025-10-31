<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $table = 'hr_designations';
    protected $fillable = ['name'];
    public $timestamps = false;
}
