<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('fee_month');
            $table->year('fee_year');
            $table->decimal('tuition_fee', 10, 2)->default(0);
            $table->decimal('admission_fee', 10, 2)->default(0);
            $table->decimal('exam_fee', 10, 2)->default(0);
            $table->decimal('transport_fee', 10, 2)->default(0);
            $table->decimal('library_fee', 10, 2)->default(0);
            $table->decimal('sports_fee', 10, 2)->default(0);
            $table->decimal('computer_fee', 10, 2)->default(0);
            $table->decimal('other_fee', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('fine', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('receipt_number')->nullable();
            $table->enum('status', ['paid', 'partial', 'unpaid'])->default('unpaid');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'student_id', 'fee_month', 'fee_year'], 'unique_student_fee');
            $table->index(['school_id', 'fee_month', 'fee_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_fees');
    }
};
