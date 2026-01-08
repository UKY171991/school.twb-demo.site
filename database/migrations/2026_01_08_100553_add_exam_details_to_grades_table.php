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
            $table->string('exam_name')->nullable()->after('enrollment_id');
            $table->decimal('total_marks', 8, 2)->default(100)->after('grade');
            $table->decimal('passing_marks', 8, 2)->default(33)->after('total_marks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn(['exam_name', 'total_marks', 'passing_marks']);
        });
    }
};
