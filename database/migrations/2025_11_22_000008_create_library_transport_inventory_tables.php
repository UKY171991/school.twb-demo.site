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
        // Book Categories
        Schema::create('book_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Books
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('book_categories')->onDelete('cascade');
            $table->string('book_no')->unique();
            $table->string('isbn')->nullable();
            $table->string('title');
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->year('publish_year')->nullable();
            $table->string('edition')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('available_quantity')->default(1);
            $table->string('rack_no')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Book Issues
        Schema::create('book_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->string('member_type'); // student, teacher, employee
            $table->foreignId('member_id'); // ID of student/teacher/employee
            $table->date('issue_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->decimal('fine_amount', 10, 2)->default(0);
            $table->boolean('fine_paid')->default(false);
            $table->enum('status', ['issued', 'returned', 'lost'])->default('issued');
            $table->text('note')->nullable();
            $table->foreignId('issued_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('returned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Routes
        Schema::create('transport_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('route_name');
            $table->string('route_number')->nullable();
            $table->text('route_description')->nullable();
            $table->decimal('fare', 10, 2)->nullable();
            $table->json('stops')->nullable(); // Array of stops with timings
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Vehicles
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('vehicle_number')->unique();
            $table->string('vehicle_model')->nullable();
            $table->year('manufacture_year')->nullable();
            $table->date('registration_date')->nullable();
            $table->integer('seating_capacity');
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('driver_license')->nullable();
            $table->text('note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Route Vehicle Assignment
        Schema::create('route_vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('transport_routes')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->date('assigned_from');
            $table->date('assigned_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Student Transport
        Schema::create('student_transport', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('route_id')->constrained('transport_routes')->onDelete('cascade');
            $table->string('pickup_point')->nullable();
            $table->string('drop_point')->nullable();
            $table->decimal('monthly_fee', 10, 2)->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Inventory Categories
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Inventory Items
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('inventory_categories')->onDelete('cascade');
            $table->string('item_code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit'); // pcs, kg, liter, etc.
            $table->integer('quantity')->default(0);
            $table->integer('min_stock_level')->default(0);
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Inventory Stock
        Schema::create('inventory_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->string('transaction_type'); // in, out, adjustment
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->string('reference_no')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Inventory Issues
        Schema::create('inventory_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->string('issued_to_type'); // teacher, employee, department
            $table->foreignId('issued_to_id');
            $table->integer('quantity');
            $table->date('issue_date');
            $table->date('return_date')->nullable();
            $table->boolean('is_returned')->default(false);
            $table->text('purpose')->nullable();
            $table->foreignId('issued_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_issues');
        Schema::dropIfExists('inventory_stock');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('inventory_categories');
        Schema::dropIfExists('student_transport');
        Schema::dropIfExists('route_vehicles');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('transport_routes');
        Schema::dropIfExists('book_issues');
        Schema::dropIfExists('books');
        Schema::dropIfExists('book_categories');
    }
};
