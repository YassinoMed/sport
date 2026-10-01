<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('athlete_tests', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name_fr');
            $table->string('name_ar')->nullable();
            $table->string('category');
            $table->string('unit')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('athlete_tests');
    }
};