<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('hr_employees', function (Blueprint $table) {
            $table->id();

            // 🟢 الحقول الأساسية (إلزامية)
            $table->string('employee_code')->unique();
            $table->string('first_name');
            $table->string('last_name');

            // 🟡 الحقول الثانوية (كلها nullable لتفادي الأخطاء)
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('nationality')->nullable();
            $table->string('photo')->nullable();
            $table->string('national_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->date('join_date')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->string('contract_type')->nullable();
            $table->string('status')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('iban')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hr_employees');
    }
}
