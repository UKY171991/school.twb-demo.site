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
        Schema::create('marking_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // 'percentage', 'points', 'letter'
            $table->json('configuration'); // Store flexible configuration
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marking_schemes');
    }
};
