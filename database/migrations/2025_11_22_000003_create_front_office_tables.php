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
        // Visitor Purpose
        Schema::create('visitor_purposes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Visitor Info
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('purpose_id')->constrained('visitor_purposes')->onDelete('cascade');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('id_card_type')->nullable(); // passport, driving_license, etc.
            $table->string('id_card_number')->nullable();
            $table->integer('number_of_people')->default(1);
            $table->date('visit_date');
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->text('note')->nullable();
            $table->string('photo')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Call Log
        Schema::create('call_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('phone');
            $table->date('call_date');
            $table->time('call_time');
            $table->string('call_type'); // incoming, outgoing
            $table->string('call_duration')->nullable();
            $table->text('description')->nullable();
            $table->string('follow_up_date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Postal Dispatch
        Schema::create('postal_dispatches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('reference_no')->unique();
            $table->string('to_title');
            $table->text('address');
            $table->date('dispatch_date');
            $table->string('note')->nullable();
            $table->string('from_title')->nullable();
            $table->string('attachment')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Postal Receive
        Schema::create('postal_receives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('reference_no');
            $table->string('from_title');
            $table->date('receive_date');
            $table->string('to_title')->nullable();
            $table->string('note')->nullable();
            $table->string('attachment')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postal_receives');
        Schema::dropIfExists('postal_dispatches');
        Schema::dropIfExists('call_logs');
        Schema::dropIfExists('visitors');
        Schema::dropIfExists('visitor_purposes');
    }
};
