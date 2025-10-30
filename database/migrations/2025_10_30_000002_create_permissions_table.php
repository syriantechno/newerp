<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('module');              // e.g., approvals, users, messages
            $table->string('action');              // view, add, edit, delete, *
            $table->string('label');               // human label
            $table->string('slug')->unique();      // module.action
            $table->string('description')->nullable();
            $table->timestamps();
            $table->unique(['module','action']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('permissions');
    }
};
