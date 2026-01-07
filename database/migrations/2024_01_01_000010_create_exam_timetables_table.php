<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exam_timetables', function (Blueprint $table) {
            $table->id();
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room_number')->nullable();
            $table->text('instructions')->nullable();
            $table->unsignedBigInteger('exam_type_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('grade_id');
            $table->unsignedBigInteger('school_id');
            $table->timestamps();
            
            $table->foreign('exam_type_id')->references('id')->on('exam_types')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->index(['school_id', 'exam_type_id', 'exam_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_timetables');
    }
};
