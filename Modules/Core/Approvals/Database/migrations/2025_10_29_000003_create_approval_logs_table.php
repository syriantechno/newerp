<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('approval_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approval_id')->constrained('approvals')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // approve / reject / create
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->index(['approval_id','created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_logs');
    }
};
