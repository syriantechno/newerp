<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\HR\Entities\Company;

class CompaniesController extends Controller
{
    public function index()
    {
        return view('hr::companies.index');
    }

    public function table()
    {
        $companies = Company::latest()->get();
        return response()->json(['data' => $companies]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'trade_license' => 'nullable|string|max:255',
            'vat_number' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $company = Company::create([
            'name' => $request->name,
            'trade_license' => $request->trade_license,
            'vat_number' => $request->vat_number,
        ]);

        logger('✅ [HR] Company added: '.$company->name);
        return response()->json(['message' => 'Company added successfully', 'data' => $company]);
    }

    public function destroy($id)
    {
        $company = Company::find($id);
        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        $company->delete();
        return response()->json(['message' => 'Company deleted successfully']);
    }
}
