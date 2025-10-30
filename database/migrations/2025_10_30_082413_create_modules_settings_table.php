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
        Schema::create('modules_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');        // الاسم الداخلي للموديول
            $table->string('label');       // الاسم الظاهر
            $table->string('icon')->nullable();
            $table->string('route')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('order')->default(100);
            $table->string('path')->nullable(); // مسار المجلد الحقيقي
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules_settings');
    }
};
