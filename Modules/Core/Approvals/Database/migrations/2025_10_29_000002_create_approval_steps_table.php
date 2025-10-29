<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('approval_steps', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('approval_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('step_order')->default(1);
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('approval_id')
                ->references('id')
                ->on('approvals')
                ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('approval_steps');
    }
};
