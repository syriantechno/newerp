<?php


namespace App\Services;

class MessageService
{
    protected static $messages = null;

    protected static function loadMessages()
    {
        if (self::$messages === null) {
            $path = app_path('Messages/messages.php');
            self::$messages = file_exists($path) ? include $path : [];
        }
        return self::$messages;
    }

    public static function get(string $key, string $default = null): string
    {
        $messages = self::loadMessages();
        $segments = explode('.', $key);
        $value = $messages;

        foreach ($segments as $segment) {
            if (!isset($value[$segment])) {
                return $default ?? "[$key]";
            }
            $value = $value[$segment];
        }

        return $value;
    }
}
