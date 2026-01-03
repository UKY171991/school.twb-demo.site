<?php

use App\Models\ExamType;
use App\Models\Grade;
use App\Models\Marksheet;
use App\Models\School;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get the first school (default school)
        $defaultSchool = School::first();

        if ($defaultSchool) {
            // Assign all existing data to the default school
            Student::whereNull('school_id')->update(['school_id' => $defaultSchool->id]);
            Teacher::whereNull('school_id')->update(['school_id' => $defaultSchool->id]);
            Grade::whereNull('school_id')->update(['school_id' => $defaultSchool->id]);
            Subject::whereNull('school_id')->update(['school_id' => $defaultSchool->id]);
            Marksheet::whereNull('school_id')->update(['school_id' => $defaultSchool->id]);
            ExamType::whereNull('school_id')->update(['school_id' => $defaultSchool->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set all school_id fields back to null
        Student::update(['school_id' => null]);
        Teacher::update(['school_id' => null]);
        Grade::update(['school_id' => null]);
        Subject::update(['school_id' => null]);
        Marksheet::update(['school_id' => null]);
        ExamType::update(['school_id' => null]);
    }
};
