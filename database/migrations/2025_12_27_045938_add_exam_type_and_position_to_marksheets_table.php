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
        Schema::table('marksheets', function (Blueprint $table) {
            $table->foreignId('exam_type_id')->nullable()->after('student_id')->constrained('exam_types')->onDelete('set null');
            $table->integer('class_position')->nullable()->after('result');
            $table->integer('total_students')->nullable()->after('class_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marksheets', function (Blueprint $table) {
            $table->dropForeign(['exam_type_id']);
            $table->dropColumn(['exam_type_id', 'class_position', 'total_students']);
        });
    }
};
