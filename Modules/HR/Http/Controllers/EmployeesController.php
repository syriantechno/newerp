<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Modules\HR\Entities\Employee;
use Modules\HR\Entities\Company;
use Modules\HR\Entities\Department;
use Modules\HR\Entities\Designation;

class EmployeesController extends Controller
{
    private function buildEmployeeQuery(Request $request)
    {
        Log::debug('[DEBUG] EmployeesController@buildEmployeeQuery');

        $q = Employee::query();

        if ($request->filled('company_id')) {
            $q->where('company_id', $request->company_id);
        }
        if ($request->filled('department_id')) {
            $q->where('department_id', $request->department_id);
        }
        if ($request->filled('designation_id')) {
            $q->where('designation_id', $request->designation_id);
        }
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function ($qq) use ($s) {
                $qq->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('emp_code', 'like', "%{$s}%");
            });
        }

        return $q->orderBy('id', 'desc');
    }

    public function index(Request $request)
    {
        Log::debug('[DEBUG] EmployeesController@index called');

        $companies = Company::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();

        $employees = $this->buildEmployeeQuery($request)
            ->paginate(10)
            ->withQueryString();

        return view('hr::employees.index', compact(
            'employees',
            'companies',
            'departments',
            'designations'
        ));
    }

    public function filter(Request $request)
    {
        Log::debug('[DEBUG] EmployeesController@filter called (AJAX)');

        $employees = $this->buildEmployeeQuery($request)
            ->paginate(10)
            ->withQueryString();

        $tbodyHtml = View::make('hr::employees.partials.table', [
            'employees' => $employees
        ])->render();

        $paginationHtml = View::make('hr::employees.partials.pagination', [
            'employees' => $employees
        ])->render();

        $summaryHtml = View::make('hr::employees.partials.summary', [
            'count' => $employees->total(),
            'filters' => [
                'company_id' => $request->company_id,
                'department_id' => $request->department_id,
                'designation_id' => $request->designation_id,
                'status' => $request->status,
                'search' => $request->search,
            ]
        ])->render();

        return response()->json([
            'ok' => true,
            'tbody' => $tbodyHtml,
            'pagination' => $paginationHtml,
            'summary' => $summaryHtml,
            'total' => $employees->total(),
        ]);
    }

    public function create()
    {
        Log::debug('[DEBUG] EmployeesController@create called');
        $companies = Company::all();
        $departments = Department::all();
        $designations = Designation::all();

        return view('hr::employees.create', compact('companies', 'departments', 'designations'));
    }

    public function store(Request $request)
    {
        Log::debug('[DEBUG] EmployeesController@store called', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'company_id' => 'nullable|integer',
            'department_id' => 'nullable|integer',
            'designation_id' => 'nullable|integer',
            'status' => 'nullable|string|max:20',
            'join_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $employee = Employee::create($validated);
        Log::debug('[DEBUG] Employee created ID=' . $employee->id);

        return redirect()->route('hr.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show($id)
    {
        Log::debug("[DEBUG] EmployeesController@show called for ID={$id}");
        $employee = Employee::findOrFail($id);
        return view('hr::employees.show', compact('employee'));
    }

    public function edit($id)
    {
        Log::debug("[DEBUG] EmployeesController@edit called for ID={$id}");

        $employee = Employee::findOrFail($id);
        $companies = Company::all();
        $departments = Department::all();
        $designations = Designation::all();

        return view('hr::employees.edit', compact(
            'employee',
            'companies',
            'departments',
            'designations'
        ));
    }

    public function update(Request $request, $id)
    {
        Log::debug("[DEBUG] EmployeesController@update called for ID={$id}", $request->all());

        $employee = Employee::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'company_id' => 'nullable|integer',
            'department_id' => 'nullable|integer',
            'designation_id' => 'nullable|integer',
            'status' => 'nullable|string|max:20',
            'join_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $employee->update($validated);
        Log::debug('[DEBUG] Employee updated ID=' . $employee->id);

        return redirect()->route('hr.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy($id)
    {
        Log::debug("[DEBUG] EmployeesController@destroy called for ID={$id}");

        $employee = Employee::findOrFail($id);
        $employee->delete();

        Log::debug('[DEBUG] Employee deleted ID=' . $id);

        return redirect()->route('hr.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
