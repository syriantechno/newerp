<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('system_messages', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();     // مفتاح ثابت للرسالة (ex: approval_assigned)
            $table->string('title');             // عنوان الرسالة الظاهر للمستخدم
            $table->text('content');             // النص الكامل للرسالة
            $table->string('type')->default('notification'); // نوع الرسالة (notification, email, sms...)
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_messages');
    }
};
