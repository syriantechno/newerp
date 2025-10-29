<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicFields;

class Employee extends Model
{
    use HasFactory, HasDynamicFields;

    // ✅ Table name
    protected $table = 'hr_employees';

    // ✅ Mass assignable attributes
    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'gender',
        'birth_date',
        'national_id',
        'nationality',
        'phone',
        'email',
        'address',
        'join_date',
        'department',
        'position',
        'salary',
        'contract_type',
        'status',
        'bank_name',
        'bank_account',
        'iban',
        'emergency_contact_name',
        'emergency_contact_phone',
        'notes',
        'photo',
    ];

    // ✅ Optional: define any relationships (for clarity)
    // Example: each employee might have many custom field values
    public function customFieldValues()
    {
        return $this->hasMany(\App\Models\DynamicFieldValue::class, 'record_id')
            ->where('module', $this->getModuleName())
            ->with('field');
    }
}
