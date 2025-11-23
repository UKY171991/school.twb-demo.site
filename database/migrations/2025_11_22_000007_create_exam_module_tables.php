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
        // Exam Types
        Schema::create('exam_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Mid-term, Final, Quiz, etc.
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Exams
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('exam_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        // Exam Schedules
        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->nullable();
            $table->integer('total_marks')->default(100);
            $table->integer('passing_marks')->default(40);
            $table->text('instructions')->nullable();
            $table->timestamps();
        });

        // Exam Halls
        Schema::create('exam_halls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('room_number')->nullable();
            $table->integer('capacity');
            $table->text('facilities')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Exam Marks
        Schema::create('exam_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->decimal('marks_obtained', 5, 2);
            $table->decimal('total_marks', 5, 2);
            $table->string('grade')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('is_absent')->default(false);
            $table->foreignId('entered_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['exam_schedule_id', 'student_id']);
        });

        // Marks Grades (Grading System)
        Schema::create('marks_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('grade_name'); // A+, A, B+, etc.
            $table->decimal('min_percentage', 5, 2);
            $table->decimal('max_percentage', 5, 2);
            $table->decimal('grade_point', 3, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Online Exam Questions
        Schema::create('question_banks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('question_type'); // mcq, true_false, short_answer, essay
            $table->text('question');
            $table->json('options')->nullable(); // For MCQ
            $table->text('correct_answer');
            $table->decimal('marks', 5, 2)->default(1);
            $table->string('difficulty_level')->default('medium'); // easy, medium, hard
            $table->text('explanation')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Online Exams
        Schema::create('online_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('duration_minutes');
            $table->decimal('total_marks', 5, 2);
            $table->decimal('passing_marks', 5, 2);
            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('show_result_immediately')->default(false);
            $table->enum('status', ['draft', 'published', 'completed'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Online Exam Questions (Junction table)
        Schema::create('online_exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('online_exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_bank_id')->constrained()->onDelete('cascade');
            $table->integer('question_order')->default(0);
            $table->timestamps();
        });

        // Online Exam Results
        Schema::create('online_exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('online_exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->decimal('marks_obtained', 5, 2)->nullable();
            $table->decimal('total_marks', 5, 2);
            $table->decimal('percentage', 5, 2)->nullable();
            $table->string('grade')->nullable();
            $table->enum('status', ['not_started', 'in_progress', 'submitted', 'graded'])->default('not_started');
            $table->json('answers')->nullable(); // Student's answers
            $table->timestamps();
            
            $table->unique(['online_exam_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_exam_results');
        Schema::dropIfExists('online_exam_questions');
        Schema::dropIfExists('online_exams');
        Schema::dropIfExists('question_banks');
        Schema::dropIfExists('marks_grades');
        Schema::dropIfExists('exam_marks');
        Schema::dropIfExists('exam_halls');
        Schema::dropIfExists('exam_schedules');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('exam_types');
    }
};
