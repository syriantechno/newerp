<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $table = 'hr_designations';
    protected $fillable = ['name','company_id'];
    public function company(){ return $this->belongsTo(Company::class); }
}
