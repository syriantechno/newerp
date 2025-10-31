<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('system_modules', function (Blueprint $table) {
            if (!Schema::hasColumn('system_modules', 'route')) {
                $table->string('route')->nullable()->after('icon');
            }
        });
    }

    public function down(): void
    {
        Schema::table('system_modules', function (Blueprint $table) {
            $table->dropColumn('route');
        });
    }
};
