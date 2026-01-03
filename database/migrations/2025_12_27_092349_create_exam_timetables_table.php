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
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('exam_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('class');
            $table->string('section')->nullable();
            $table->string('academic_year');
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('exam_center')->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Ensure unique combination per school/exam/subject/class
            $table->unique(['school_id', 'exam_type_id', 'subject_id', 'class', 'section', 'academic_year'], 'unique_exam_schedule');
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
