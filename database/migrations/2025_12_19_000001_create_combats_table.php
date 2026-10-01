<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('combats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athlete_a_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('athlete_b_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('weight_kg', 5, 2);
            $table->dateTime('scheduled_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('combats');
    }
};

