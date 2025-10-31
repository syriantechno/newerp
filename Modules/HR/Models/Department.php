<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'hr_departments';
    protected $fillable = ['name','company_id','parent_id'];
    public function company(){ return $this->belongsTo(Company::class); }
    public function parent(){ return $this->belongsTo(Department::class,'parent_id'); }
    public function children(){ return $this->hasMany(Department::class,'parent_id'); }
}
