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
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('exam_name');
            $table->date('exam_date');
            $table->string('class');
            $table->string('section');
            $table->string('academic_year');
            $table->integer('total_marks')->default(0);
            $table->integer('obtained_marks')->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->string('grade')->nullable();
            $table->enum('result', ['PASS', 'FAIL'])->nullable();
            $table->timestamps();
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
