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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('teacher_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['course_evaluation', 'teacher_feedback', 'suggestion', 'complaint', 'general'])->default('general');
            $table->string('title');
            $table->text('content');
            $table->integer('rating')->nullable(); // 1-5 rating scale
            $table->json('ratings')->nullable(); // Detailed ratings for different aspects
            $table->enum('status', ['submitted', 'under_review', 'responded', 'resolved', 'closed'])->default('submitted');
            $table->text('admin_response')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('responded_at')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->timestamps();
            
            $table->index(['school_id', 'type', 'status']);
            $table->index(['student_id', 'created_at']);
            $table->index(['subject_id', 'teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
