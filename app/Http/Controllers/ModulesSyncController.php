<?php

namespace App\Http\Controllers;

use App\Services\SyncModulesService;
use Illuminate\Http\Request;

class ModulesSyncController extends Controller
{
    public function sync(Request $request)
    {
        try {
            SyncModulesService::sync();
            return redirect()->route('settings.index')
                ->with('success', 'Modules synchronized successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('settings.index')
                ->with('error', 'Sync failed: ' . $e->getMessage());
        }
    }
}
