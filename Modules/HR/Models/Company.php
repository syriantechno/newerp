<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'hr_companies';
    protected $fillable = ['name','trade_license','vat_number'];
    public function departments(){ return $this->hasMany(Department::class); }
    public function designations(){ return $this->hasMany(Designation::class); }
}
