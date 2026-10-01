<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->after('group_id')->constrained('courses')->onDelete('cascade');
            $table->index(['course_id', 'group_id']);
        });
    }

    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex(['course_id', 'group_id']);
            $table->dropConstrainedForeignId('course_id');
        });
    }
};

