<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('system_modules', function (Blueprint $table) {
            if (!Schema::hasColumn('system_modules', 'order')) {
                $table->integer('order')->default(0)->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('system_modules', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
