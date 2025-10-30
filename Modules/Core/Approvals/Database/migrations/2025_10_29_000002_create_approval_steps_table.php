<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('approval_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approval_id')->constrained('approvals')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('step_order'); // ✅ اسم واضح لتجنب تضارب كلمة order
            $table->enum('status', ['waiting', 'pending', 'approved', 'rejected'])->default('waiting');
            $table->timestamps();

            $table->index(['approval_id','step_order']);
            $table->unique(['approval_id','step_order']); // كل خطوة ترتيب فريد داخل نفس الموافقة
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_steps');
    }
};
