<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->unsignedInteger('capacity')->default(0);
            // Registration fee stored in cents (TND)
            $table->unsignedBigInteger('registration_fee')->default(0);
            $table->string('belt_requirement')->nullable();
            $table->timestamp('start_at');
            $table->timestamp('end_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

