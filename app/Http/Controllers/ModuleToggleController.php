<?php

namespace App\Http\Controllers;

use App\Models\ModuleSetting;
use Illuminate\Http\Request;

class ModuleToggleController extends Controller
{
    public function toggle($id)
    {
        $module = ModuleSetting::findOrFail($id);
        $module->active = !$module->active;
        $module->save();

        return response()->json([
            'success' => true,
            'active' => $module->active,
            'label' => $module->label,
        ]);
    }
}
