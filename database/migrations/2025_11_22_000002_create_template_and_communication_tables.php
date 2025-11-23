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
        // SMS Templates
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('purpose'); // attendance_absent, fee_reminder, exam_notification, etc.
            $table->text('message');
            $table->json('variables')->nullable(); // Available variables like {student_name}, {date}, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Email Templates
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('purpose'); // welcome, password_reset, report_card, etc.
            $table->string('subject');
            $table->text('body'); // HTML content
            $table->json('variables')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // SMS Log
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('template_id')->nullable()->constrained('sms_templates')->onDelete('set null');
            $table->string('recipient_phone');
            $table->string('recipient_name')->nullable();
            $table->text('message');
            $table->string('status'); // pending, sent, failed, delivered
            $table->string('provider')->nullable();
            $table->string('provider_message_id')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        // Email Log
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('template_id')->nullable()->constrained('email_templates')->onDelete('set null');
            $table->string('recipient_email');
            $table->string('recipient_name')->nullable();
            $table->string('subject');
            $table->text('body');
            $table->json('attachments')->nullable();
            $table->string('status'); // pending, sent, failed, bounced
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
        Schema::dropIfExists('sms_logs');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('sms_templates');
    }
};
