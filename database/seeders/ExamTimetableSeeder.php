<?php

namespace Database\Seeders;

use App\Models\ExamTimetable;
use App\Models\ExamType;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class ExamTimetableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::all();

        foreach ($schools as $school) {
            $examTypes = ExamType::where('school_id', $school->id)->get();
            $subjects = Subject::where('school_id', $school->id)->take(5)->get();

            if ($examTypes->isEmpty() || $subjects->isEmpty()) {
                continue;
            }

            $classes = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
            $sections = ['A', 'B'];

            foreach ($examTypes->take(2) as $examType) { // Only first 2 exam types
                foreach ($classes as $class) {
                    foreach ($sections as $section) {
                        $startDate = now()->addDays(rand(10, 30));

                        foreach ($subjects as $index => $subject) {
                            ExamTimetable::create([
                                'school_id' => $school->id,
                                'exam_type_id' => $examType->id,
                                'subject_id' => $subject->id,
                                'class' => $class,
                                'section' => $section,
                                'academic_year' => '2024-2025',
                                'exam_date' => $startDate->copy()->addDays($index),
                                'start_time' => '10:00',
                                'end_time' => '13:00',
                                'exam_center' => $school->name,
                                'is_active' => true,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
