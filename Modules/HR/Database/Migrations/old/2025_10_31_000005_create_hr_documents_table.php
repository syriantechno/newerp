<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('hr_documents', function (Blueprint $t) {
            $t->id();
            $t->morphs('documentable');
            $t->string('type');
            $t->string('file_path');
            $t->date('issued_at')->nullable();
            $t->date('expires_at')->nullable();
            $t->string('number')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('hr_documents'); }
};
