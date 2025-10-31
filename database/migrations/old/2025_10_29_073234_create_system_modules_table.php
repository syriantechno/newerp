<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('system_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // internal name (e.g. HR)
            $table->string('label');          // display label (e.g. Human Resources)
            $table->string('icon')->nullable(); // optional icon class (FontAwesome)
            $table->string('route_prefix');   // e.g. hr, crm, payroll
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_modules');
    }
};
