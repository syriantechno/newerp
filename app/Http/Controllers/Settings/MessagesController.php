<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class MessagesController extends Controller
{
    protected string $filePath;

    public function __construct()
    {
        $this->filePath = base_path('app/Messages/messages.json');
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'messages' => 'required|array',
        ]);

        File::put($this->filePath, json_encode($data['messages'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return back()->with('success', '✅ Messages updated successfully.');
    }
}
