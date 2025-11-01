<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Modules\HR\Entities\Employee;
use Modules\HR\Entities\Company;
use Modules\HR\Entities\Department;
use Modules\HR\Entities\Designation;
use Modules\HR\Models\Attendance;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        Log::debug('[DEBUG] Attendance@index called');

        $companies    = Company::orderBy('name')->get();
        $departments  = Department::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();
        $employees    = Employee::orderBy('name')->get();

        // defaults
        $month = (int)($request->get('month', now()->month));
        $year  = (int)($request->get('year', now()->year));

        return view('hr::attendance.index', compact(
            'companies','departments','designations','employees','month','year'
        ));
    }

    public function table()
    {
        $records = \Modules\HR\Entities\Attendance::with(['employee', 'department', 'designation'])
            ->select('id', 'employee_id', 'date', 'status', 'check_in', 'check_out')
            ->latest()
            ->get()
            ->map(fn($a) => [
                'id' => $a->id,
                'employee' => $a->employee->name ?? '-',
                'date' => $a->date,
                'status' => ucfirst(str_replace('_', ' ', $a->status)),
                'check_in' => $a->check_in,
                'check_out' => $a->check_out,
            ]);

        return response()->json(['data' => $records]);
    }


    public function filter(Request $request)
    {
        Log::debug('[DEBUG] Attendance@filter called (AJAX)', $request->all());

        $month = (int)$request->get('month', now()->month);
        $year  = (int)$request->get('year', now()->year);
        $start = Carbon::create($year, $month, 1)->startOfDay();
        $end   = (clone $start)->endOfMonth();

        $empQuery = Employee::query();

        if ($request->filled('employee_id')) {
            $empQuery->where('id', $request->employee_id);
        }
        if ($request->filled('company_id')) {
            $empQuery->where('company_id', $request->company_id);
        }
        if ($request->filled('department_id')) {
            $empQuery->where('department_id', $request->department_id);
        }
        if ($request->filled('designation_id')) {
            $empQuery->where('designation_id', $request->designation_id);
        }

        $employees = $empQuery->orderBy('name')->get(['id','name','email','company_id','department_id','designation_id']);

        // fetch attendance for given month
        $records = Attendance::whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->whereIn('employee_id', $employees->pluck('id'))
            ->get()
            ->groupBy('employee_id');

        $daysCount = $start->daysInMonth;
        $daysMeta = [];
        for ($d = 1; $d <= $daysCount; $d++) {
            $day = Carbon::create($year, $month, $d);
            $daysMeta[] = [
                'd' => $d,
                'dow_short' => $day->format('D'),
            ];
        }

        $tableHtml = View::make('hr::attendance.partials.table', [
            'employees' => $employees,
            'records'   => $records,
            'daysMeta'  => $daysMeta,
            'month'     => $month,
            'year'      => $year,
        ])->render();

        return response()->json([
            'ok' => true,
            'table' => $tableHtml,
        ]);
    }

    public function mark(Request $request)
    {
        Log::debug('[DEBUG] Attendance@mark called', $request->all());

        $data = $request->validate([
            'employee_id' => 'required|exists:hr_employees,id',
            'date'        => 'required|date',
            'status'      => 'required|in:present,absent,half_day,late,holiday,day_off,leave,not_registered',
            'check_in'    => 'nullable|date_format:H:i',
            'check_out'   => 'nullable|date_format:H:i',
            'remarks'     => 'nullable|string',
        ]);

        $att = Attendance::updateOrCreate(
            ['employee_id' => $data['employee_id'], 'date' => $data['date']],
            [
                'status'    => $data['status'],
                'check_in'  => $data['check_in'] ?? null,
                'check_out' => $data['check_out'] ?? null,
                'source'    => 'manual',
                'remarks'   => $data['remarks'] ?? null,
            ]
        );

        Log::debug('[DEBUG] Attendance saved ID='.$att->id);

        return back()->with('success', 'Attendance saved.');
    }

    public function import(Request $request)
    {
        Log::debug('[DEBUG] Attendance@import called');
        // TODO: parse CSV/XLSX and bulk insert/update with source = import
        return back()->with('success', 'Import placeholder ready.');
    }

    public function export(Request $request)
    {
        Log::debug('[DEBUG] Attendance@export called');
        // TODO: generate export (CSV/XLSX/PDF)
        return back()->with('success', 'Export placeholder ready.');
    }
}
