<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'hr_attendance';

    protected $fillable = [
        'employee_id','date','status','check_in','check_out',
        'source','meta','remarks'
    ];

    protected $casts = [
        'date' => 'date',
        'meta' => 'array',
    ];
}
