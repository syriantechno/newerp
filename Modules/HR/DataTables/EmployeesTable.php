<?php

namespace Modules\HR\DataTables;

use Illuminate\Http\Request;
use Modules\HR\Models\Employee;

class EmployeesTable
{
    public function build(Request $request)
    {
        $query = Employee::with(['company', 'department', 'designation'])
            ->select('id', 'name', 'email', 'status', 'company_id', 'department_id', 'designation_id');

        // 🔸 Filters
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('designation_id')) {
            $query->where('designation_id', $request->designation_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        // 🔸 Final mapping
        $employees = $query->latest()->get()->map(function ($e) {
            return [
                'id' => $e->id,
                'name' => $e->name,
                'email' => $e->email,
                'company' => $e->company->name ?? '-',
                'department' => $e->department->name ?? '-',
                'designation' => $e->designation->name ?? '-',
                'status' => '<span class="badge bg-gradient-' .
                    ($e->status === 'active' ? 'success' : 'secondary') .
                    ' text-white">' . ucfirst($e->status) . '</span>',
            ];
        });

        return response()->json(['data' => $employees]);
    }
}
