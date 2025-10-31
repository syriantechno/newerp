<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'hr_employees';
    protected $fillable = [
        'company_id','department_id','designation_id','emp_code','name','email','phone','join_date','status','extra'
    ];
    protected $casts = ['extra'=>'array','join_date'=>'date'];
    public function company(){ return $this->belongsTo(Company::class); }
    public function department(){ return $this->belongsTo(Department::class); }
    public function designation(){ return $this->belongsTo(Designation::class); }
    public function documents(){ return $this->morphMany(Document::class, 'documentable'); }
}
