<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('approvals', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // ✅ مهم جداً
            $table->id();
            $table->string('module');
            $table->unsignedBigInteger('record_id');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('approvals');
    }
};
