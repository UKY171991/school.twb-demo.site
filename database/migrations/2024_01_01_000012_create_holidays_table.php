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
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('date');
            $table->enum('type', ['national', 'state', 'school', 'other'])->default('school');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('school_id');
            $table->timestamps();
            
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->index(['school_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
