<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function checkNew(Request $request)
    {
        $user = Auth::user();
        $lastId = $request->query('last_id', 0);

        $new = DB::table('notifications')
            ->where('user_id', $user->id)
            ->where('id', '>', $lastId)
            ->orderByDesc('created_at')
            ->first();

        if ($new) {
            return response()->json([
                'new' => true,
                'id' => $new->id,
                'title' => $new->title,
                'message' => $new->message,
            ]);
        }

        return response()->json(['new' => false]);
    }
}
