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
        Schema::table('students', function (Blueprint $table) {
            $table->string('roll_number')->nullable()->after('name');
            $table->string('class')->nullable()->after('roll_number');
            $table->string('section')->nullable()->after('class');
            $table->string('father_name')->nullable()->after('section');
            $table->string('mother_name')->nullable()->after('father_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['roll_number', 'class', 'section', 'father_name', 'mother_name']);
        });
    }
};
