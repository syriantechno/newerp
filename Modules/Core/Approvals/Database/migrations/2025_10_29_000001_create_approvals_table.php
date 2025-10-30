<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Untitled');
            $table->string('module')->default('general');
            $table->unsignedBigInteger('record_id')->default(0);
            $table->enum('status', ['pending','in_progress','approved','rejected'])->default('pending');
            $table->unsignedInteger('current_step')->default(1); // ✅ يعتمد عليه النظام
            $table->timestamps();

            $table->index(['module','record_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
