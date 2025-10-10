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
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->text('address');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->string('occupation')->nullable();
            $table->string('company')->nullable();
            $table->decimal('annual_income', 12, 2)->nullable();
            $table->string('photo')->nullable();
            $table->enum('relationship', ['father', 'mother', 'guardian']);
            $table->boolean('is_primary_contact')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
