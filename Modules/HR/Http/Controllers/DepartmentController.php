<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Entities\Department;
use Modules\HR\Entities\Company;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function index()
    {
        $companies = Company::select('id', 'name')->get();
        return view('hr::departments.index', compact('companies'));
    }

    public function table()
    {
        $departments = Department::with('company')->latest()->get();
        return response()->json(['data' => $departments]);
    }

//    public function table()
//    {
//        $departments = Department::latest()->get();
//        return response()->json(['data' => $departments]);
//    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,inactive',
            'company_id'  => 'required|exists:hr_companies,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $department = Department::create($validator->validated());
        return response()->json(['message' => 'Department added successfully', 'data' => $department]);
    }

    public function update(Request $request, Department $department)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,inactive',
            'company_id'  => 'required|exists:hr_companies,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $department->update($validator->validated());
        return response()->json(['message' => 'Department updated successfully']);
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json(['message' => 'Department deleted successfully']);
    }
}
