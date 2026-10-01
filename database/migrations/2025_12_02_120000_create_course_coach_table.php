<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_coach', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('coach_id');
            $table->foreign('course_id')->references('id')->on('courses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('coach_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['course_id', 'coach_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_coach');
    }
};
