<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    public static function send($userId, string $title, string $message = '', string $url = null): void
    {
        DB::table('notifications')->insert([
            'id' => \Str::uuid(),
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'url' => $url,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // optional: send mail if user has email
        $user = User::find($userId);
        if ($user && $user->email) {
            try {
                \Mail::raw($message ?: $title, function ($mail) use ($user, $title) {
                    $mail->to($user->email)->subject($title);
                });
            } catch (\Throwable $e) {
                // silent fail
            }
        }
    }
}
