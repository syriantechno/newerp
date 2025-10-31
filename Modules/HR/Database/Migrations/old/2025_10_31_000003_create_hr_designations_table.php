<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('hr_designations', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->foreignId('company_id')->constrained('hr_companies')->cascadeOnDelete();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('hr_designations'); }
};
