<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('waitlists', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->constrained()->onDelete('cascade');
            $blueprint->foreignId('course_id')->constrained()->onDelete('cascade');
            $blueprint->integer('priority')->default(0);
            $blueprint->enum('status', ['pending', 'notified', 'converted', 'cancelled'])->default('pending');
            $blueprint->timestamps();

            // Un utilisateur ne peut être qu'une seule fois en liste d'attente pour un même cours
            $blueprint->unique(['user_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waitlists');
    }
};
