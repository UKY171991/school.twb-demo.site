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
        Schema::create('student_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('activity_id')->nullable()->constrained('school_activities')->onDelete('cascade');
            $table->enum('permission_type', ['field_trip', 'sports_event', 'school_event', 'medical', 'transport', 'after_school']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('activity_date')->nullable();
            $table->datetime('deadline')->nullable();
            $table->enum('status', ['pending', 'approved', 'denied', 'expired'])->default('pending');
            $table->text('parent_notes')->nullable();
            $table->text('teacher_notes')->nullable();
            $table->datetime('requested_at');
            $table->datetime('responded_at')->nullable();
            $table->boolean('requires_payment')->default(false);
            $table->decimal('payment_amount', 8, 2)->nullable();
            $table->datetime('payment_deadline')->nullable();
            $table->boolean('medical_form_required')->default(false);
            $table->boolean('transport_required')->default(false);
            $table->datetime('pickup_time')->nullable();
            $table->datetime('return_time')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
            
            $table->index(['student_id', 'status']);
            $table->index(['permission_type', 'status']);
            $table->index('deadline');
            $table->index('activity_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_permissions');
    }
};
