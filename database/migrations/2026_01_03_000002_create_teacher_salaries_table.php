<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('salary_month');
            $table->year('salary_year');
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->decimal('house_allowance', 10, 2)->default(0);
            $table->decimal('transport_allowance', 10, 2)->default(0);
            $table->decimal('medical_allowance', 10, 2)->default(0);
            $table->decimal('other_allowance', 10, 2)->default(0);
            $table->decimal('bonus', 10, 2)->default(0);
            $table->decimal('overtime', 10, 2)->default(0);
            $table->decimal('deduction_tax', 10, 2)->default(0);
            $table->decimal('deduction_pf', 10, 2)->default(0);
            $table->decimal('deduction_loan', 10, 2)->default(0);
            $table->decimal('other_deduction', 10, 2)->default(0);
            $table->decimal('gross_salary', 10, 2)->default(0);
            $table->decimal('total_deductions', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2)->default(0);
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('slip_number')->nullable();
            $table->enum('status', ['paid', 'pending', 'cancelled'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'teacher_id', 'salary_month', 'salary_year'], 'unique_teacher_salary');
            $table->index(['school_id', 'salary_month', 'salary_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_salaries');
    }
};
