<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_category_id')->constrained('expense_categories')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->unique(['expense_category_id','name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_subcategories');
    }
};

