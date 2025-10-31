<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('hr_departments', function (Blueprint $table) {
            // Add missing columns only if they don't exist
            if (!Schema::hasColumn('hr_departments', 'code')) {
                $table->string('code')->nullable()->after('name')->index();
            }

            if (!Schema::hasColumn('hr_departments', 'description')) {
                $table->text('description')->nullable()->after('code');
            }

            if (!Schema::hasColumn('hr_departments', 'manager_id')) {
                $table->unsignedBigInteger('manager_id')->nullable()->after('parent_id')->index();
            }

            if (!Schema::hasColumn('hr_departments', 'status')) {
                $table->enum('status', ['active','inactive'])->default('active')->after('manager_id')->index();
            }

            if (!Schema::hasColumn('hr_departments', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('status')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('hr_departments', function (Blueprint $table) {
            // Safe rollback
            foreach (['code', 'description', 'manager_id', 'status', 'created_by'] as $col) {
                if (Schema::hasColumn('hr_departments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
