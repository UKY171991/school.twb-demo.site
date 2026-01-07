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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('section')->nullable();
            $table->integer('capacity')->default(40);
            $table->string('room_number')->nullable();
            $table->text('description')->nullable();
            $table->string('grade_theme')->default('1');
            $table->enum('status', ['active', 'inactive', 'upcoming'])->default('active');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->timestamps();
            
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
            $table->index(['school_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
