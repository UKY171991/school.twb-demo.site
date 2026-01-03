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
        Schema::create('school_timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('class');
            $table->string('section')->nullable();
            $table->enum('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room_number')->nullable();
            $table->text('notes')->nullable();
            $table->string('academic_year');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes for better performance
            $table->index(['school_id', 'class', 'section', 'day_of_week']);
            $table->index(['teacher_id', 'day_of_week']);
            $table->index(['academic_year', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_timetables');
    }
};
