<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_category_id')->constrained('expense_categories')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('expense_subcategory_id')->nullable()->constrained('expense_subcategories')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('gym_id')->nullable()->constrained('gyms')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('amount_cents');
            $table->date('expense_date');
            $table->string('method')->default('cash');
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};

