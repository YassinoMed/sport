<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('amount_cents');
            $table->date('billing_month');
            $table->enum('status', ['paid','due','refund'])->default('paid');
            $table->string('method')->default('cash');
            $table->timestamp('paid_at')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->unique(['user_id','billing_month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_payments');
    }
};

