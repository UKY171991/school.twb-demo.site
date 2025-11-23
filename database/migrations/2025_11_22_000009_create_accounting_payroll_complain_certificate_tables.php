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
        // Chart of Accounts
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('account_code')->unique();
            $table->string('account_name');
            $table->string('account_type'); // asset, liability, equity, income, expense
            $table->foreignId('parent_id')->nullable()->constrained('chart_of_accounts')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Income
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained('chart_of_accounts')->onDelete('cascade');
            $table->string('income_head');
            $table->string('invoice_no')->nullable();
            $table->date('income_date');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method'); // cash, bank, cheque, online
            $table->text('description')->nullable();
            $table->string('attachment')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Expenses
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained('chart_of_accounts')->onDelete('cascade');
            $table->string('expense_head');
            $table->string('invoice_no')->nullable();
            $table->date('expense_date');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method'); // cash, bank, cheque, online
            $table->text('description')->nullable();
            $table->string('attachment')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('invoice_no')->unique();
            $table->string('invoice_type'); // fee, transport, hostel, etc.
            $table->foreignId('student_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('balance', 10, 2);
            $table->enum('status', ['draft', 'sent', 'paid', 'partial', 'overdue', 'cancelled'])->default('draft');
            $table->text('note')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Invoice Items
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });

        // Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('payment_no')->unique();
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method'); // cash, bank, cheque, online, card
            $table->string('transaction_id')->nullable();
            $table->string('cheque_no')->nullable();
            $table->date('cheque_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->text('note')->nullable();
            $table->string('receipt_file')->nullable();
            $table->foreignId('received_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Salary Templates
        Schema::create('salary_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('basic_salary', 10, 2);
            $table->json('allowances')->nullable(); // {name: amount}
            $table->json('deductions')->nullable(); // {name: amount}
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Salary Payments
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('employee_type'); // teacher, employee
            $table->foreignId('employee_id'); // ID of teacher or employee
            $table->string('month'); // YYYY-MM format
            $table->decimal('basic_salary', 10, 2);
            $table->json('allowances')->nullable();
            $table->json('deductions')->nullable();
            $table->decimal('gross_salary', 10, 2);
            $table->decimal('net_salary', 10, 2);
            $table->date('payment_date');
            $table->string('payment_method');
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->text('note')->nullable();
            $table->foreignId('processed_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['employee_type', 'employee_id', 'month']);
        });

        // Complain Types
        Schema::create('complain_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Complains
        Schema::create('complains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('complain_type_id')->constrained()->onDelete('cascade');
            $table->string('complain_by_type'); // student, teacher, parent, employee
            $table->foreignId('complain_by_id'); // ID of the complainant
            $table->string('subject');
            $table->text('description');
            $table->date('complain_date');
            $table->string('attachment')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'closed'])->default('pending');
            $table->text('action_taken')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        // Certificates
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('certificate_type'); // transfer, character, bonafide, etc.
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('certificate_no')->unique();
            $table->date('issue_date');
            $table->text('content')->nullable();
            $table->string('template')->nullable();
            $table->string('file_path')->nullable();
            $table->foreignId('issued_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('complains');
        Schema::dropIfExists('complain_types');
        Schema::dropIfExists('salary_payments');
        Schema::dropIfExists('salary_templates');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('incomes');
        Schema::dropIfExists('chart_of_accounts');
    }
};
