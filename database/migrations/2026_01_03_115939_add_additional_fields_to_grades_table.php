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
        Schema::table('grades', function (Blueprint $table) {
            $table->integer('capacity')->nullable()->default(40)->after('teacher_id');
            $table->text('description')->nullable()->after('capacity');
            $table->integer('grade_theme')->nullable()->default(1)->after('description');
            $table->string('status')->nullable()->default('active')->after('grade_theme');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn(['capacity', 'description', 'grade_theme', 'status']);
        });
    }
};
