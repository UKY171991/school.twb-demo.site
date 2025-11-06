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
        Schema::create('school_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('activity_type', ['field_trip', 'sports_event', 'school_event', 'fundraiser', 'academic', 'cultural']);
            $table->date('activity_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->integer('max_participants')->nullable();
            $table->boolean('requires_permission')->default(true);
            $table->boolean('requires_payment')->default(false);
            $table->decimal('payment_amount', 8, 2)->nullable();
            $table->datetime('payment_deadline')->nullable();
            $table->datetime('permission_deadline')->nullable();
            $table->boolean('medical_form_required')->default(false);
            $table->boolean('transport_provided')->default(false);
            $table->string('pickup_location')->nullable();
            $table->string('return_location')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->string('contact_email')->nullable();
            $table->text('special_instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['school_id', 'activity_date']);
            $table->index(['activity_type', 'is_active']);
            $table->index('organizer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_activities');
    }
};
