<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller as BaseController;
use Modules\HR\Models\Department;

class DepartmentController extends BaseController
{
    public function index(Request $request)
    {
        // Debug prints for safe testing
        Log::info('[Departments] index called', ['q' => $request->all()]);

        $filters = [
            'search'     => $request->string('search')->toString(),
            'status'     => $request->string('status')->toString(),
            'company_id' => $request->input('company_id'),
            'parent_id'  => $request->input('parent_id'),
        ];

        $departments = Department::withCount('employees')
            ->with(['parent', 'manager'])
            ->filter($filters)
            ->orderBy('name')
            ->paginate(15)
            ->appends($request->query());

        // For parent dropdown
        $allDepartments = Department::orderBy('name')->get(['id','name','parent_id']);
        $managers = \Modules\HR\Models\Employee::orderBy('name')->get(['id','name']); // optional

        return view('hr::departments.index', compact('departments', 'filters', 'allDepartments', 'managers'));
    }

    public function store(Request $request)
    {
        Log::info('[Departments] store called', ['payload' => $request->all()]);

        $data = $request->validate([
            'name'        => ['required','string','max:190'],
            'code'        => ['nullable','string','max:50'],
            'description' => ['nullable','string'],
            'company_id'  => ['nullable','integer'],
            'parent_id'   => ['nullable','integer','different:id'],
            'manager_id'  => ['nullable','integer'],
            'status'      => ['required', Rule::in(['active','inactive'])],
        ]);

        $data['created_by'] = auth()->id();

        Department::create($data);

        return redirect()->route('hr.departments.index')->with('success', 'Department created successfully.');
    }

    public function update(Request $request, Department $department)
    {
        Log::info('[Departments] update called', ['id' => $department->id, 'payload' => $request->all()]);

        $data = $request->validate([
            'name'        => ['required','string','max:190'],
            'code'        => ['nullable','string','max:50'],
            'description' => ['nullable','string'],
            'company_id'  => ['nullable','integer'],
            'parent_id'   => ['nullable','integer','different:id'],
            'manager_id'  => ['nullable','integer'],
            'status'      => ['required', Rule::in(['active','inactive'])],
        ]);

        $department->update($data);

        return redirect()->route('hr.departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        Log::info('[Departments] destroy called', ['id' => $department->id]);

        if ($department->employees()->exists()) {
            return redirect()
                ->route('hr.departments.index')
                ->with('error', 'Cannot delete department with assigned employees.');
        }

        $department->delete();

        return redirect()->route('hr.departments.index')->with('success', 'Department deleted successfully.');
    }
}
