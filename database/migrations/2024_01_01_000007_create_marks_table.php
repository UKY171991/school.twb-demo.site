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
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->integer('marks_obtained');
            $table->integer('full_marks');
            $table->enum('grade', ['A+', 'A', 'B+', 'B', 'C+', 'C', 'D', 'F'])->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('exam_type_id');
            $table->unsignedBigInteger('school_id');
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('exam_type_id')->references('id')->on('exam_types')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->unique(['student_id', 'subject_id', 'exam_type_id']);
            $table->index(['school_id', 'exam_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
