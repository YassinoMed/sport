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
            $table->date('start_date')->nullable()->after('billing_month');
            $table->date('end_date')->nullable()->after('start_date');
            $table->unsignedBigInteger('package_id')->nullable()->after('user_id');
            $table->string('invoice_number')->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_payments', function (Blueprint $table) {
            //
        });
    }
};
