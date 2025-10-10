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
            $table->enum('user_type', ['super_admin', 'admin', 'teacher', 'student', 'parent'])->default('student')->after('password');
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade')->after('user_type');
            $table->boolean('is_active')->default(true)->after('school_id');
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
