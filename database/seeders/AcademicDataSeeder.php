<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = \App\Models\School::all();

        foreach ($schools as $school) {
            $teachers = \App\Models\Teacher::where('school_id', $school->id)->get();
            $students = \App\Models\Student::where('school_id', $school->id)->get();

            // Create 8-12 Classes per school
            $classCount = rand(8, 12);
            $classes = \App\Models\ClassModel::factory($classCount)->create([
                'school_id' => $school->id,
                'teacher_id' => $teachers->random()->id ?? null,
            ]);

            // Create 10-15 Subjects per school
            $subjectCount = rand(10, 15);
            $subjects = \App\Models\Subject::factory($subjectCount)->create([
                'school_id' => $school->id,
                'teacher_id' => $teachers->random()->id ?? null,
            ]);

            // Assign students to classes randomly
            foreach ($students as $student) {
                $randomClass = $classes->random();
                $student->update(['class_id' => $randomClass->id]);
            }

            // Create attendance records for the last 30 days
            foreach ($students as $student) {
                $attendanceCount = rand(20, 30); // Not every day
                \App\Models\Attendance::factory($attendanceCount)->create([
                    'school_id' => $school->id,
                    'student_id' => $student->id,
                    'class_id' => $student->class_id,
                ]);
            }

            // Create grades for students
            foreach ($students as $student) {
                $gradeCount = rand(5, 15); // Multiple grades per student
                foreach ($subjects->random(min(5, $subjects->count())) as $subject) {
                    \App\Models\Grade::factory(rand(1, 3))->create([
                        'school_id' => $school->id,
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'teacher_id' => $subject->teacher_id,
                    ]);
                }
            }

            $this->command->info('Created academic data for school: ' . $school->name);
        }
    }
}
