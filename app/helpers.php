<?php

use App\Services\NotificationService;

if (!function_exists('notify_user')) {
    /**
     * Unified notification sender (JSON-based)
     */
    function notify_user($userId, string $titleKey, string $bodyKey, ?string $url = null, array $vars = []): void
    {
        // تحميل الرسائل من ملف JSON
        $path = base_path('app/Messages/messages.json');
        $messages = file_exists($path)
            ? json_decode(file_get_contents($path), true)
            : [];

        // جلب النصوص
        $title = $messages[$titleKey] ?? $titleKey;
        $body  = $messages[$bodyKey] ?? $bodyKey;

        // استبدال المتغيرات {user}, {id} ...
        foreach ($vars as $k => $v) {
            $title = str_replace('{' . $k . '}', $v, $title);
            $body  = str_replace('{' . $k . '}', $v, $body);
        }

        // الإرسال عبر NotificationService الحالي
        NotificationService::send($userId, $title, $body, $url);
    }
}
