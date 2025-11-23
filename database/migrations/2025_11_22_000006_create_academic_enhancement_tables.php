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
        // Course Materials
        Schema::create('course_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable(); // pdf, doc, video, link
            $table->string('external_link')->nullable();
            $table->date('available_from')->nullable();
            $table->date('available_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Live Classes
        Schema::create('live_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('platform'); // zoom, google_meet, teams, etc.
            $table->string('meeting_id')->nullable();
            $table->string('meeting_password')->nullable();
            $table->text('meeting_link');
            $table->dateTime('scheduled_at');
            $table->integer('duration_minutes')->default(60);
            $table->enum('status', ['scheduled', 'live', 'completed', 'cancelled'])->default('scheduled');
            $table->text('recording_link')->nullable();
            $table->timestamps();
        });

        // Syllabus
        Schema::create('syllabi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->json('topics')->nullable(); // Array of topics
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Assignment Submissions (enhance existing assignments table)
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->text('submission_text')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamp('submitted_at');
            $table->decimal('marks_obtained', 5, 2)->nullable();
            $table->text('teacher_feedback')->nullable();
            $table->enum('status', ['submitted', 'graded', 'late', 'resubmit'])->default('submitted');
            $table->foreignId('graded_by')->nullable()->constrained('teachers')->onDelete('set null');
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
        });

        // Student Types
        Schema::create('student_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Regular, Scholarship, Special Needs, etc.
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add student_type_id to students table if not exists
        if (!Schema::hasColumn('students', 'student_type_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->foreignId('student_type_id')->nullable()->after('school_id')->constrained()->onDelete('set null');
            });
        }

        // Online Admission Applications
        Schema::create('online_admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('application_no')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('email');
            $table->string('phone');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->text('address')->nullable();
            $table->string('parent_name');
            $table->string('parent_phone');
            $table->string('parent_email')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('photo')->nullable();
            $table->json('documents')->nullable(); // Birth certificate, transfer certificate, etc.
            $table->enum('status', ['pending', 'approved', 'rejected', 'admitted'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('admin_note')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_admissions');
        
        if (Schema::hasColumn('students', 'student_type_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropForeign(['student_type_id']);
                $table->dropColumn('student_type_id');
            });
        }
        
        Schema::dropIfExists('student_types');
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('syllabi');
        Schema::dropIfExists('live_classes');
        Schema::dropIfExists('course_materials');
    }
};
