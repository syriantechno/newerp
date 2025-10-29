<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\DynamicField;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    // Show all dynamic fields grouped by module
    public function index()
    {
        $fields = DynamicField::orderBy('module')->orderBy('order')->get()->groupBy('module');
        return view('settings.custom_fields.index', compact('fields'));
    }

    // Show form to create a new field
    public function create()
    {
        return view('settings.custom_fields.create');
    }

    // Store new field
    public function store(Request $request)
    {
        $data = $request->validate([
            'module' => 'required|string|max:50',
            'name' => 'required|string|max:100|unique:dynamic_fields,name',
            'label' => 'required|string|max:255',
            'type' => 'required|string|in:text,number,date,boolean,select,textarea,file',
            'options' => 'nullable|string',
            'is_required' => 'boolean',
            'validation' => 'nullable|string',
        ]);

        if (!empty($data['options'])) {
            $data['options'] = json_encode(array_map('trim', explode(',', $data['options'])));
        }

        DynamicField::create($data);

        return redirect()->route('settings.custom-fields.index')
            ->with('success', 'Field created successfully.');
    }

    // Delete field
    public function destroy($id)
    {
        DynamicField::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Field deleted successfully.');
    }
}
