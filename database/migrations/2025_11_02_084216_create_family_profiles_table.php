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
        Schema::create('family_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->string('family_name');
            $table->string('primary_contact_name');
            $table->string('primary_contact_phone', 20);
            $table->string('primary_contact_email');
            $table->string('secondary_contact_name')->nullable();
            $table->string('secondary_contact_phone', 20)->nullable();
            $table->string('secondary_contact_email')->nullable();
            $table->text('home_address')->nullable();
            $table->text('work_address')->nullable();
            $table->text('medical_information')->nullable();
            $table->text('special_instructions')->nullable();
            $table->json('notification_preferences')->nullable();
            $table->json('communication_preferences')->nullable();
            $table->json('privacy_settings')->nullable();
            $table->timestamps();
            
            $table->unique('parent_id');
            $table->index('family_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_profiles');
    }
};
