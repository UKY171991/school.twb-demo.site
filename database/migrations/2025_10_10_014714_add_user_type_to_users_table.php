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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'user_type')) {
                $table->enum('user_type', ['super_admin', 'admin', 'teacher', 'student', 'parent'])->default('student')->after('password');
            }
            if (!Schema::hasColumn('users', 'school_id')) {
                $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade')->after('user_type');
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('school_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn(['user_type', 'school_id', 'is_active']);
        });
    }
};
