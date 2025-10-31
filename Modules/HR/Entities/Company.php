<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'hr_companies';

    protected $fillable = [
        'name',
        'trade_license',
        'vat_number',
    ];
}
