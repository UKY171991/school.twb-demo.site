<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Grade;
use App\Models\School;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first school
        $school = School::first();
        if (!$school) {
            return;
        }

        // Get grades to assign to students
        $grades = Grade::where('school_id', $school->id)->get();
        if ($grades->isEmpty()) {
            return;
        }

        // Create sample students
        $students = [
            ['name' => 'Alice Johnson', 'roll_number' => 'STU001', 'grade_id' => 1],
            ['name' => 'Bob Smith', 'roll_number' => 'STU002', 'grade_id' => 1],
            ['name' => 'Charlie Brown', 'roll_number' => 'STU003', 'grade_id' => 2],
            ['name' => 'Diana Prince', 'roll_number' => 'STU004', 'grade_id' => 2],
            ['name' => 'Edward Norton', 'roll_number' => 'STU005', 'grade_id' => 3],
            ['name' => 'Fiona Green', 'roll_number' => 'STU006', 'grade_id' => 3],
            ['name' => 'George Wilson', 'roll_number' => 'STU007', 'grade_id' => 4],
            ['name' => 'Hannah Montana', 'roll_number' => 'STU008', 'grade_id' => 4],
            ['name' => 'Ian McKellen', 'roll_number' => 'STU009', 'grade_id' => 5],
            ['name' => 'Julia Roberts', 'roll_number' => 'STU010', 'grade_id' => 5],
        ];

        foreach ($students as $studentData) {
            // Assign a random grade from available grades
            $grade = $grades->random();
            Student::create([
                'name' => $studentData['name'],
                'grade_id' => $grade->id,
                'gender' => 'male',
                'date_of_birth' => '2010-01-01',
                'phone' => '0000000000',
                'address' => 'Student Address',
            ]);
        }
    }
}
