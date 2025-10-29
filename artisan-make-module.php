<?php

$moduleName = $argv[1] ?? null;

if (!$moduleName) {
    echo "❌ استخدم: php artisan-make-module.php HR\n";
    exit;
}

$basePath = __DIR__ . "/Modules/$moduleName";

$structure = [
    'Http/Controllers',
    'Models',
    'Routes',
    'Views',
    'Database/migrations',
];

foreach ($structure as $path) {
    $dir = "$basePath/$path";
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
        echo "📁 Created: $dir\n";
    }
}

// إنشاء ملفات افتراضية
file_put_contents("$basePath/Routes/web.php", <<<PHP
<?php
use Illuminate\\Support\\Facades\\Route;

Route::middleware('web')->prefix(strtolower('$moduleName'))->group(function() {
    Route::get('/', function() {
        return view(strtolower('$moduleName')."::index");
    });
});
PHP);

file_put_contents("$basePath/Views/index.blade.php", <<<BLADE
@extends('layouts.user_type.auth')
@section('content')
<div class="card mt-4 p-4">
  <h2 class="text-center text-primary">🧩 موديول $moduleName</h2>
  <p class="text-center text-secondary">تم إنشاؤه تلقائيًا بنجاح 🎯</p>
</div>
@endsection
BLADE);

echo "✅ موديول $moduleName تم إنشاؤه بالكامل!\n";
