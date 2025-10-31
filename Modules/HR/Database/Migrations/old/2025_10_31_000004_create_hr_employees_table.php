<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('hr_employees', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained('hr_companies')->cascadeOnDelete();
            $t->foreignId('department_id')->nullable()->constrained('hr_departments')->nullOnDelete();
            $t->foreignId('designation_id')->nullable()->constrained('hr_designations')->nullOnDelete();
            $t->string('emp_code')->unique();
            $t->string('name');
            $t->string('email')->nullable()->unique();
            $t->string('phone')->nullable();
            $t->date('join_date')->nullable();
            $t->enum('status', ['active','inactive'])->default('active');
            $t->json('extra')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('hr_employees'); }
};
