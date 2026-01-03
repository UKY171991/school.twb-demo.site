<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix cascading deletes that cause data loss
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['students_school_id_foreign']);
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('set null');
        });

        Schema::table('marksheets', function (Blueprint $table) {
            $table->dropForeign(['marksheets_school_id_foreign']);
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('set null');
        });

        Schema::table('school_timetables', function (Blueprint $table) {
            $table->dropForeign(['school_timetables_school_id_foreign']);
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('cascade');
        });

        Schema::table('exam_timetables', function (Blueprint $table) {
            $table->dropForeign(['exam_timetables_school_id_foreign']);
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('cascade');
        });

        Schema::table('exam_types', function (Blueprint $table) {
            $table->dropForeign(['exam_types_school_id_foreign']);
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('set null');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['subjects_school_id_foreign']);
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('set null');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['grades_school_id_foreign']);
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('cascade');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['teachers_school_id_foreign']);
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('set null');
        });

        // Keep student-grade relationship as cascade since students should be deleted if grade is deleted
        // But remove cascade from other relationships
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original cascading deletes
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['students_school_id_foreign']);
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('cascade');
        });

        Schema::table('marksheets', function (Blueprint $table) {
            $table->dropForeign(['marksheets_school_id_foreign']);
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('cascade');
        });

        Schema::table('school_timetables', function (Blueprint $table) {
            $table->dropForeign(['school_timetables_school_id_foreign']);
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('cascade');
        });

        Schema::table('exam_timetables', function (Blueprint $table) {
            $table->dropForeign(['exam_timetables_school_id_foreign']);
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('cascade');
        });

        Schema::table('exam_types', function (Blueprint $table) {
            $table->dropForeign(['exam_types_school_id_foreign']);
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('cascade');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['subjects_school_id_foreign']);
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('cascade');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['grades_school_id_foreign']);
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('cascade');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['teachers_school_id_foreign']);
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('cascade');
        });
    }
};
