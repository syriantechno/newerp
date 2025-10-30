<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        // تحديد التبويب الحالي (افتراضي: general)
        $activeTab = $request->get('tab', 'general');

        // تحميل الرسائل من ملف messages.json
        $messages = [];
        $messagesFile = base_path('app/Messages/messages.json');
        if (File::exists($messagesFile)) {
            $messages = json_decode(File::get($messagesFile), true) ?? [];
        }

        // تحميل أي بيانات أخرى (مثل modules) لاحقًا
        $modules = [];

        return view('settings.index', compact('messages', 'modules', 'activeTab'));
    }
}
