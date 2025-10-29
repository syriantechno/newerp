<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Employee;
use App\Models\DynamicField;
use App\Models\DynamicFieldValue;

class EmployeeController extends Controller
{
    /**
     * ✅ عرض قائمة الموظفين
     */
    public function index()
    {
        $employees = Employee::orderBy('id', 'desc')->get();
        return view('hr::employees.index', compact('employees'));
    }

    /**
     * ✅ عرض صفحة الإضافة
     */
    public function create()
    {
        $customFields = DynamicField::where('module', 'HR')
            ->where('is_active', true)
            ->get();

        // توليد كود الموظف القادم
        $lastId = Employee::max('id');
        $nextCode = $lastId ? $lastId + 1 : 1;
        $employeeCode = 'EMP-' . str_pad($nextCode, 4, '0', STR_PAD_LEFT);

        return view('hr::employees.create', compact('customFields', 'employeeCode'));
    }

    /**
     * ✅ تخزين بيانات الموظف (AJAX + عادي)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_code' => 'required|string|unique:hr_employees,employee_code',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'national_id' => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:255',
            'join_date' => 'nullable|date',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric',
            'contract_type' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:150',
            'bank_account' => 'nullable|string|max:150',
            'iban' => 'nullable|string|max:150',
            'emergency_contact_name' => 'nullable|string|max:150',
            'emergency_contact_phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // معالجة الصورة إن وجدت
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/employees'), $filename);
            $data['photo'] = 'uploads/employees/' . $filename;
        }

        // إنشاء الموظف
        $employee = Employee::create($data);

        // الحقول الديناميكية
        if ($request->has('custom') && is_array($request->custom)) {
            foreach ($request->custom as $fieldId => $value) {
                if (!is_null($value) && $value !== '') {
                    DynamicFieldValue::updateOrCreate(
                        [
                            'module'    => 'HR',
                            'record_id' => $employee->id,
                            'field_id'  => $fieldId,
                        ],
                        ['value' => $value]
                    );
                }
            }
        }

        // الرد في حال طلب Ajax
        if ($request->ajax()) {
            $nextId = Employee::max('id') + 1;
            $nextCode = 'EMP-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            return response()->json([
                'status' => 'success',
                'message' => 'Employee added successfully ✅',
                'next_code' => $nextCode
            ]);
        }

        // الرد العادي
        return redirect()
            ->route('hr.employees.index')
            ->with('success', 'Employee created successfully ✅');
    }
}
