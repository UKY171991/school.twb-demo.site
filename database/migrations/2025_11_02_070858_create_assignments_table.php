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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->enum('type', ['homework', 'project', 'quiz', 'exam', 'presentation', 'other'])->default('homework');
            $table->date('assigned_date');
            $table->date('due_date');
            $table->time('due_time')->nullable();
            $table->integer('total_marks')->default(100);
            $table->enum('status', ['draft', 'published', 'completed', 'cancelled'])->default('draft');
            $table->json('attachments')->nullable();
            $table->boolean('allow_late_submission')->default(false);
            $table->integer('late_penalty_percentage')->default(0);
            $table->text('submission_instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['school_id', 'class_id']);
            $table->index(['due_date', 'status']);
            $table->index(['teacher_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
