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
        // General Settings
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, number, boolean, json
            $table->string('group')->default('general'); // general, system, email, sms, etc.
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Payment Settings
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('gateway_name'); // stripe, paypal, razorpay, etc.
            $table->boolean('is_active')->default(false);
            $table->json('credentials')->nullable(); // API keys, secrets, etc.
            $table->json('settings')->nullable(); // Additional settings
            $table->timestamps();
        });

        // SMS Settings
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('provider'); // twilio, nexmo, msg91, etc.
            $table->boolean('is_active')->default(false);
            $table->json('credentials')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        // Email Settings
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('driver')->default('smtp'); // smtp, sendmail, mailgun, etc.
            $table->string('host')->nullable();
            $table->integer('port')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('encryption')->nullable();
            $table->string('from_address')->nullable();
            $table->string('from_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // User Credentials Log
        Schema::create('user_credentials_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('changed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action'); // password_reset, username_change, credential_sent
            $table->text('details')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
        });

        // Backup Log
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('type'); // full, database, files
            $table->bigInteger('size')->nullable(); // in bytes
            $table->string('status'); // pending, completed, failed
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('error_message')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Opening Hours
        Schema::create('opening_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('day_of_week'); // monday, tuesday, etc.
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opening_hours');
        Schema::dropIfExists('backup_logs');
        Schema::dropIfExists('user_credentials_log');
        Schema::dropIfExists('email_settings');
        Schema::dropIfExists('sms_settings');
        Schema::dropIfExists('payment_settings');
        Schema::dropIfExists('general_settings');
    }
};
