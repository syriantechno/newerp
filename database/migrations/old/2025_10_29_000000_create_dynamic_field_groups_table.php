<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDynamicFieldGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('dynamic_field_groups', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dynamic_field_groups');
    }
}
