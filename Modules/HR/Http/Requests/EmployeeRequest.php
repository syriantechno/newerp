<?php

namespace Modules\HR\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }
    public function rules(): array
    {
        $id = $this->route('employee')?->id;
        return [
            'company_id'    => ['required','exists:hr_companies,id'],
            'department_id' => ['nullable','exists:hr_departments,id'],
            'designation_id'=> ['nullable','exists:hr_designations,id'],
            'emp_code'      => ['required','string','max:50','unique:hr_employees,emp_code,'.($id ?? 'NULL').',id'],
            'name'          => ['required','string','max:190'],
            'email'         => ['nullable','email','unique:hr_employees,email,'.($id ?? 'NULL').',id'],
            'phone'         => ['nullable','string','max:50'],
            'join_date'     => ['nullable','date'],
            'status'        => ['required','in:active,inactive'],
            'extra'         => ['nullable','array'],
        ];
    }
}
