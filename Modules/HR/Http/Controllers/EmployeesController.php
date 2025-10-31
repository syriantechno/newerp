<?php

namespace Modules\HR\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HR\Models\Employee;
use Modules\HR\Models\Department;
use Modules\HR\Models\Designation;

class EmployeesController extends Controller
{
    /**
     * Display the main employees page.
     */
    public function index()
    {
        return view('hr::employees.index');
    }

    /**
     * Return JSON data for DataTable.
     */
    public function table()
    {
        $employees = Employee::with(['department:id,name', 'designation:id,name'])
            ->orderByDesc('id')
            ->get();

        return response()->json(['data' => $employees]);
    }

    /**
     * Store a new employee via AJAX.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:hr_employees,email',
            'company_id'    => 'required|integer|exists:hr_companies,id',
            'department_id' => 'nullable|integer',
            'designation_id'=> 'nullable|integer',
            'status'        => 'nullable|string',
            'join_date'     => 'nullable|date',
            'notes'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            // 🔹 توليد رقم موظف تلقائي فريد مثل EMP-0001
            $lastId = \Modules\HR\Entities\Employee::max('id') ?? 0;
            $empCode = 'EMP-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

            $employee = \Modules\HR\Entities\Employee::create([
                'emp_code'      => $empCode, // ✅ توليد تلقائي
                'name'          => $request->name,
                'email'         => $request->email,
                'company_id'    => $request->company_id,
                'department_id' => $request->department_id,
                'designation_id'=> $request->designation_id,
                'status'        => $request->status ?? 'active',
                'join_date'     => $request->join_date,
                'notes'         => $request->notes,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Employee added successfully!',
                'data'    => $employee,
            ]);
        } catch (\Throwable $e) {
            \Log::error('[EmployeeStore] '.$e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to save employee: '.$e->getMessage(),
            ], 500);


        \Log::error('[EmployeeStore] '.$e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to save employee: '.$e->getMessage(),
            ], 500);
        }
    }


    /**
     * Update an existing employee.
     */
    public function update(Request $request, Employee $employee)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:hr_employees,email,' . $employee->id,
            'department_id' => 'nullable|integer',
            'designation_id'=> 'nullable|integer',
            'status'        => 'nullable|string',
            'join_date'     => 'nullable|date',
            'notes'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $employee->update($validator->validated());

        return response()->json([
            'success'  => true,
            'message'  => 'Employee updated successfully',
            'employee' => $employee
        ]);
    }

    /**
     * Delete an employee.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully'
        ]);
    }

    /**
     * Return dropdown lists for Departments & Designations (used in modal)
     */
    public function dropdowns()
    {
        return response()->json([
            'departments'  => Department::select('id', 'name')->get(),
            'designations' => Designation::select('id', 'name')->get(),
        ]);
    }
}
