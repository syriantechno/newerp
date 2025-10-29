<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDynamicFieldsTable extends Migration
{
    public function up()
    {
        Schema::create('dynamic_fields', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->string('name');
            $table->string('label');
            $table->enum('type', ['text','number','date','boolean','select','textarea','file']);
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->string('validation')->nullable();
            $table->enum('visibility', ['all','admin','manager','employee'])->default('all');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dynamic_fields');
    }
}
