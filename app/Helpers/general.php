<?php

if (!function_exists('msg')) {
    function msg(string $key, array $vars = []): string
    {
        static $messages = null;

        if ($messages === null) {
            $path = base_path('app/Messages/messages.json');
            if (file_exists($path)) {
                $json = file_get_contents($path);
                $messages = json_decode($json, true) ?? [];
            } else {
                $messages = [];
            }
        }

        $text = $messages[$key] ?? $key;

        foreach ($vars as $k => $v) {
            $text = str_replace('{' . $k . '}', $v, $text);
        }

        return $text;
    }
}
