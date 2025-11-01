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
        // Enhance users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('password');
            $table->string('phone', 20)->nullable()->after('profile_photo');
            $table->timestamp('last_login_at')->nullable()->after('phone');
            $table->json('preferences')->nullable()->after('last_login_at');
        });

        // Enhance schools table
        Schema::table('schools', function (Blueprint $table) {
            $table->json('configuration')->nullable()->after('is_active');
            $table->date('established_date')->nullable()->after('configuration');
            $table->string('timezone', 50)->default('UTC')->after('established_date');
        });

        // Add indexes for better performance
        Schema::table('users', function (Blueprint $table) {
            $table->index(['user_type', 'is_active']);
            $table->index(['school_id', 'user_type']);
            $table->index('last_login_at');
        });

        Schema::table('schools', function (Blueprint $table) {
            $table->index(['is_active', 'created_at']);
            $table->index('code');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->index(['school_id', 'status']);
            $table->index(['class_id', 'status']);
            $table->index('student_id');
            $table->index('admission_date');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->index(['school_id', 'is_active']);
            $table->index('employee_id');
            $table->index('joining_date');
        });

        Schema::table('parents', function (Blueprint $table) {
            $table->index(['school_id', 'is_primary_contact']);
            $table->index('relationship');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove added columns from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_photo', 'phone', 'last_login_at', 'preferences']);
        });

        // Remove added columns from schools table
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['configuration', 'established_date', 'timezone']);
        });

        // Remove indexes
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['user_type', 'is_active']);
            $table->dropIndex(['school_id', 'user_type']);
            $table->dropIndex(['last_login_at']);
        });

        Schema::table('schools', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'created_at']);
            $table->dropIndex(['code']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['school_id', 'status']);
            $table->dropIndex(['class_id', 'status']);
            $table->dropIndex(['student_id']);
            $table->dropIndex(['admission_date']);
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropIndex(['school_id', 'is_active']);
            $table->dropIndex(['employee_id']);
            $table->dropIndex(['joining_date']);
        });

        Schema::table('parents', function (Blueprint $table) {
            $table->dropIndex(['school_id', 'is_primary_contact']);
            $table->dropIndex(['relationship']);
        });
    }
};
