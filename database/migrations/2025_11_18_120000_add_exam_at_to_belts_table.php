<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('belts', function (Blueprint $table) {
            $table->date('exam_at')->nullable()->after('achieved_at');
        });
    }

    public function down(): void
    {
        Schema::table('belts', function (Blueprint $table) {
            $table->dropColumn('exam_at');
        });
    }
};