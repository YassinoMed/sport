<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('belt_note_categories', function (Blueprint $table) {
            $table->id();
            $table->string('belt_name')->index();
            $table->string('name');
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::create('belt_note_subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('belt_note_category_id')->constrained('belt_note_categories')->onDelete('cascade');
            $table->string('name');
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::create('belt_note_items', function (Blueprint $table) {
            $table->id();
            $table->morphs('noteable');
            $table->text('content');
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('belt_note_items');
        Schema::dropIfExists('belt_note_subcategories');
        Schema::dropIfExists('belt_note_categories');
    }
};

