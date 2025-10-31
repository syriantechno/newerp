<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hr_attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->index();
            $table->date('date')->index();
            $table->enum('status', [
                'present', 'absent', 'half_day', 'late',
                'holiday', 'day_off', 'leave', 'not_registered'
            ])->default('not_registered')->index();
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->enum('source', ['manual', 'device', 'import'])->default('manual')->index();
            $table->json('meta')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_attendance');
    }
};
