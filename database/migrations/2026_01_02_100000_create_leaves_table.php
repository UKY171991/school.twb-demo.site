<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('type')->default('student'); // 'student' or 'holiday'
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('status')->default('approved');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leaves');
    }
};
