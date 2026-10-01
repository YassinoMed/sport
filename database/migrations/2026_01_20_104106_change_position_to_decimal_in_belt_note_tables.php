<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('belt_note_categories', function (Blueprint $table) {
            $table->decimal('position', 8, 2)->default(0)->change();
        });

        Schema::table('belt_note_subcategories', function (Blueprint $table) {
            $table->decimal('position', 8, 2)->default(0)->change();
        });

        Schema::table('belt_note_items', function (Blueprint $table) {
            $table->decimal('position', 8, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('belt_note_categories', function (Blueprint $table) {
            $table->unsignedInteger('position')->default(0)->change();
        });

        Schema::table('belt_note_subcategories', function (Blueprint $table) {
            $table->unsignedInteger('position')->default(0)->change();
        });

        Schema::table('belt_note_items', function (Blueprint $table) {
            $table->unsignedInteger('position')->default(0)->change();
        });
    }
};
