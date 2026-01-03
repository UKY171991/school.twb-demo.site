<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Marksheet;
use App\Models\MarksheetMark;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class MarksheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create grades if they don't exist
        $grade10 = Grade::firstOrCreate(['name' => 'Grade 10']);
        $grade9 = Grade::firstOrCreate(['name' => 'Grade 9']);

        // Create sample students
        $students = [
            [
                'name' => 'John Doe',
                'roll_number' => 'STU001',
                'class' => '10',
                'section' => 'A',
                'father_name' => 'Robert Doe',
                'mother_name' => 'Mary Doe',
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'date_of_birth' => '2008-05-15',
                'gender' => 'male',
                'address' => '123 Main Street',
                'grade_id' => $grade10->id,
            ],
            [
                'name' => 'Jane Smith',
                'roll_number' => 'STU002',
                'class' => '10',
                'section' => 'A',
                'father_name' => 'James Smith',
                'mother_name' => 'Lisa Smith',
                'email' => 'jane@example.com',
                'phone' => '1234567891',
                'date_of_birth' => '2008-08-20',
                'gender' => 'female',
                'address' => '456 Oak Avenue',
                'grade_id' => $grade10->id,
            ],
            [
                'name' => 'Mike Johnson',
                'roll_number' => 'STU003',
                'class' => '9',
                'section' => 'B',
                'father_name' => 'David Johnson',
                'mother_name' => 'Sarah Johnson',
                'email' => 'mike@example.com',
                'phone' => '1234567892',
                'date_of_birth' => '2009-03-10',
                'gender' => 'male',
                'address' => '789 Pine Road',
                'grade_id' => $grade9->id,
            ],
        ];

        foreach ($students as $studentData) {
            Student::firstOrCreate(['roll_number' => $studentData['roll_number']], $studentData);
        }

        // Create sample subjects
        $subjects = [
            [
                'name' => 'Mathematics',
                'code' => 'MATH101',
                'description' => 'Basic Mathematics',
                'max_marks' => 100,
                'pass_marks' => 33,
                'grade_id' => $grade10->id,
            ],
            [
                'name' => 'English',
                'code' => 'ENG101',
                'description' => 'English Language',
                'max_marks' => 100,
                'pass_marks' => 33,
                'grade_id' => $grade10->id,
            ],
            [
                'name' => 'Science',
                'code' => 'SCI101',
                'description' => 'General Science',
                'max_marks' => 100,
                'pass_marks' => 33,
                'grade_id' => $grade10->id,
            ],
            [
                'name' => 'History',
                'code' => 'HIS101',
                'description' => 'World History',
                'max_marks' => 100,
                'pass_marks' => 33,
                'grade_id' => $grade10->id,
            ],
            [
                'name' => 'Geography',
                'code' => 'GEO101',
                'description' => 'Physical Geography',
                'max_marks' => 100,
                'pass_marks' => 33,
                'grade_id' => $grade10->id,
            ],
        ];

        foreach ($subjects as $subjectData) {
            Subject::firstOrCreate(['code' => $subjectData['code']], $subjectData);
        }

        // Create sample marksheets
        $student1 = Student::where('roll_number', 'STU001')->first();
        $student2 = Student::where('roll_number', 'STU002')->first();

        if ($student1) {
            $marksheet1 = Marksheet::create([
                'student_id' => $student1->id,
                'exam_name' => 'Mid Term Examination',
                'exam_date' => '2024-10-15',
                'class' => $student1->class,
                'section' => $student1->section,
                'academic_year' => '2024-2025',
            ]);

            // Add marks for student 1
            $marks1 = [
                'MATH101' => 85,
                'ENG101' => 78,
                'SCI101' => 92,
                'HIS101' => 76,
                'GEO101' => 88,
            ];

            foreach ($marks1 as $subjectCode => $obtainedMarks) {
                $subject = Subject::where('code', $subjectCode)->first();
                if ($subject) {
                    MarksheetMark::create([
                        'student_id' => $student1->id,
                        'subject_id' => $subject->id,
                        'marksheet_id' => $marksheet1->id,
                        'obtained_marks' => $obtainedMarks,
                    ]);
                }
            }

            $marksheet1->calculateResult();
        }

        if ($student2) {
            $marksheet2 = Marksheet::create([
                'student_id' => $student2->id,
                'exam_name' => 'Mid Term Examination',
                'exam_date' => '2024-10-15',
                'class' => $student2->class,
                'section' => $student2->section,
                'academic_year' => '2024-2025',
            ]);

            // Add marks for student 2
            $marks2 = [
                'MATH101' => 72,
                'ENG101' => 89,
                'SCI101' => 67,
                'HIS101' => 84,
                'GEO101' => 91,
            ];

            foreach ($marks2 as $subjectCode => $obtainedMarks) {
                $subject = Subject::where('code', $subjectCode)->first();
                if ($subject) {
                    MarksheetMark::create([
                        'student_id' => $student2->id,
                        'subject_id' => $subject->id,
                        'marksheet_id' => $marksheet2->id,
                        'obtained_marks' => $obtainedMarks,
                    ]);
                }
            }

            $marksheet2->calculateResult();
        }
    }
}
