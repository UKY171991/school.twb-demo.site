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
        Schema::create('marksheets', function (Blueprint $table) {
            $table->id();
            $table->string('roll_number');
            $table->string('student_name');
            $table->integer('total_marks');
            $table->integer('total_full_marks');
            $table->decimal('percentage', 5, 2);
            $table->string('grade');
            $table->integer('position')->nullable();
            $table->enum('status', ['passed', 'failed', 'promoted'])->default('passed');
            $table->unsignedBigInteger('exam_type_id');
            $table->unsignedBigInteger('grade_id');
            $table->unsignedBigInteger('school_id');
            $table->timestamps();
            
            $table->foreign('exam_type_id')->references('id')->on('exam_types')->onDelete('cascade');
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->unique(['school_id', 'exam_type_id', 'grade_id', 'roll_number']);
            $table->index(['school_id', 'exam_type_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marksheets');
    }
};
