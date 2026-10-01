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
        Schema::create('course_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participation_id')->constrained('participations')->onDelete('cascade');
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'excused'])->default('absent');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour optimiser les requêtes
            $table->index(['participation_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_attendances');
    }
};
