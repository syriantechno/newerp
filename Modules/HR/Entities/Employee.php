<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'hr_employees';

    protected $fillable = [
        'emp_code',        // ✅ مهم جداً
        'name',
        'email',
        'company_id',
        'department_id',
        'designation_id',
        'status',
        'join_date',
        'notes',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
