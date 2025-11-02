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
        Schema::create('student_academic_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->integer('semester')->default(1);
            $table->enum('status', ['active', 'inactive', 'transferred', 'graduated'])->default('active');
            $table->date('enrollment_date');
            $table->timestamps();

            // Indexes for better performance
            $table->index(['student_id', 'academic_year_id']);
            $table->index(['academic_year_id', 'semester', 'status']);
            
            // Ensure unique enrollment per student per academic year per semester
            $table->unique(['student_id', 'academic_year_id', 'semester'], 'unique_student_academic_year_semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_academic_years');
    }
};
