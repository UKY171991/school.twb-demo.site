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
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('current_semester')->default(1);
            $table->integer('total_semesters')->default(2);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['school_id', 'is_active']);
            $table->index(['school_id', 'is_current']);
            $table->index(['start_date', 'end_date']);
            
            // Ensure only one current academic year per school
            $table->unique(['school_id', 'is_current'], 'unique_current_academic_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
