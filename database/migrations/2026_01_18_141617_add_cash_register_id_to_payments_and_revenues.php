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
        Schema::table('user_payments', function (Blueprint $table) {
            $table->foreignId('cash_register_id')->nullable()->constrained('cash_registers')->onDelete('set null');
        });
        Schema::table('revenues', function (Blueprint $table) {
            $table->foreignId('cash_register_id')->nullable()->constrained('cash_registers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('user_payments', function (Blueprint $table) {
            $table->dropForeign(['cash_register_id']);
            $table->dropColumn('cash_register_id');
        });
        Schema::table('revenues', function (Blueprint $table) {
            $table->dropForeign(['cash_register_id']);
            $table->dropColumn('cash_register_id');
        });
    }
};
