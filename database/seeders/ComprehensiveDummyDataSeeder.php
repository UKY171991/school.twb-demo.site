<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Grade;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ExamType;
use App\Models\Marksheet;
use App\Models\MarksheetMark;
use App\Models\Mark;
use App\Models\Attendance;
use App\Models\SystemSetting;
use App\Models\GradingSystem;
use Carbon\Carbon;

class ComprehensiveDummyDataSeeder extends Seeder
{
    public function run()
    {
        // Create Schools
        $schools = [
            [
                'name' => 'Greenwood High School',
                'code' => 'GHS',
                'address' => '123 Education Street, Downtown',
                'phone' => '+1-555-0101',
                'email' => 'info@greenwood.edu',
                'website' => 'www.greenwood.edu',
                'principal_name' => 'Dr. Margaret Thompson',
                'status' => 'active'
            ],
            [
                'name' => 'Riverside Academy',
                'code' => 'RA',
                'address' => '456 River Road, Riverside',
                'phone' => '+1-555-0202',
                'email' => 'admin@riverside.edu',
                'website' => 'www.riverside.edu',
                'principal_name' => 'Mr. James Wilson',
                'status' => 'active'
            ],
            [
                'name' => 'Oakwood International School',
                'code' => 'OIS',
                'address' => '789 Oak Avenue, Oakwood',
                'phone' => '+1-555-0303',
                'email' => 'contact@oakwood.edu',
                'website' => 'www.oakwood.edu',
                'principal_name' => 'Ms. Sarah Johnson',
                'status' => 'active'
            ]
        ];

        foreach ($schools as $schoolData) {
            School::create($schoolData);
        }

        $schoolIds = School::pluck('id')->toArray();

        // Create Exam Types for each school
        $examTypes = [
            ['name' => 'Half Yearly', 'code' => 'HY', 'description' => 'Mid-year examination', 'sort_order' => 1],
            ['name' => 'Annual', 'code' => 'AN', 'description' => 'Final year examination', 'sort_order' => 2],
            ['name' => 'Unit Test 1', 'code' => 'UT1', 'description' => 'First unit test', 'sort_order' => 3],
            ['name' => 'Unit Test 2', 'code' => 'UT2', 'description' => 'Second unit test', 'sort_order' => 4],
            ['name' => 'Monthly Test', 'code' => 'MT', 'description' => 'Monthly assessment', 'sort_order' => 5],
        ];

        foreach ($schoolIds as $schoolId) {
            foreach ($examTypes as $examType) {
                ExamType::create(array_merge($examType, ['school_id' => $schoolId]));
            }
        }

        // Create Grades for each school
        $grades = [
            ['name' => 'Nursery', 'section' => 'A', 'capacity' => 25],
            ['name' => 'Nursery', 'section' => 'B', 'capacity' => 25],
            ['name' => 'KG-1', 'section' => 'A', 'capacity' => 30],
            ['name' => 'KG-1', 'section' => 'B', 'capacity' => 30],
            ['name' => 'KG-2', 'section' => 'A', 'capacity' => 30],
            ['name' => 'Grade 1', 'section' => 'A', 'capacity' => 35],
            ['name' => 'Grade 1', 'section' => 'B', 'capacity' => 35],
            ['name' => 'Grade 2', 'section' => 'A', 'capacity' => 35],
            ['name' => 'Grade 2', 'section' => 'B', 'capacity' => 35],
            ['name' => 'Grade 3', 'section' => 'A', 'capacity' => 40],
            ['name' => 'Grade 3', 'section' => 'B', 'capacity' => 40],
            ['name' => 'Grade 4', 'section' => 'A', 'capacity' => 40],
            ['name' => 'Grade 4', 'section' => 'B', 'capacity' => 40],
            ['name' => 'Grade 5', 'section' => 'A', 'capacity' => 40],
            ['name' => 'Grade 5', 'section' => 'B', 'capacity' => 40],
        ];

        foreach ($schoolIds as $schoolId) {
            foreach ($grades as $grade) {
                Grade::create(array_merge($grade, ['school_id' => $schoolId]));
            }
        }

        // Create Teachers for each school
        $teachers = [
            ['name' => 'Dr. Emily Rodriguez', 'email' => 'emily.rodriguez@school.edu', 'phone' => '+1-555-1001', 'gender' => 'female', 'date_of_birth' => '1985-03-15', 'date_of_joining' => '2020-08-01', 'address' => '123 Teacher Lane', 'qualification' => 'PhD in Mathematics', 'salary' => 75000],
            ['name' => 'Mr. David Chen', 'email' => 'david.chen@school.edu', 'phone' => '+1-555-1002', 'gender' => 'male', 'date_of_birth' => '1988-07-22', 'date_of_joining' => '2019-09-15', 'address' => '456 Faculty Street', 'qualification' => 'MSc in Physics', 'salary' => 68000],
            ['name' => 'Ms. Sarah Williams', 'email' => 'sarah.williams@school.edu', 'phone' => '+1-555-1003', 'gender' => 'female', 'date_of_birth' => '1990-11-08', 'date_of_joining' => '2021-01-10', 'address' => '789 Education Ave', 'qualification' => 'MA in English Literature', 'salary' => 62000],
            ['name' => 'Mr. Michael Johnson', 'email' => 'michael.johnson@school.edu', 'phone' => '+1-555-1004', 'gender' => 'male', 'date_of_birth' => '1987-05-30', 'date_of_joining' => '2018-03-20', 'address' => '321 Academic Road', 'qualification' => 'MSc in Chemistry', 'salary' => 70000],
            ['name' => 'Dr. Lisa Anderson', 'email' => 'lisa.anderson@school.edu', 'phone' => '+1-555-1005', 'gender' => 'female', 'date_of_birth' => '1983-09-12', 'date_of_joining' => '2017-07-01', 'address' => '654 Scholar Street', 'qualification' => 'PhD in Biology', 'salary' => 78000],
            ['name' => 'Mr. Robert Taylor', 'email' => 'robert.taylor@school.edu', 'phone' => '+1-555-1006', 'gender' => 'male', 'date_of_birth' => '1989-12-25', 'date_of_joining' => '2020-02-14', 'address' => '987 Learning Lane', 'qualification' => 'MA in History', 'salary' => 58000],
            ['name' => 'Ms. Jennifer Brown', 'email' => 'jennifer.brown@school.edu', 'phone' => '+1-555-1007', 'gender' => 'female', 'date_of_birth' => '1991-04-18', 'date_of_joining' => '2021-08-30', 'address' => '147 Knowledge Street', 'qualification' => 'BA in Art Education', 'salary' => 55000],
            ['name' => 'Mr. Christopher Davis', 'email' => 'christopher.davis@school.edu', 'phone' => '+1-555-1008', 'gender' => 'male', 'date_of_birth' => '1986-10-05', 'date_of_joining' => '2019-11-12', 'address' => '258 Wisdom Way', 'qualification' => 'MSc in Computer Science', 'salary' => 72000],
        ];

        foreach ($schoolIds as $schoolId) {
            foreach ($teachers as $teacher) {
                Teacher::create(array_merge($teacher, ['school_id' => $schoolId]));
            }
        }

        // Get created grades and teachers for relationships
        $allGrades = Grade::all();
        $allTeachers = Teacher::all();

        // Create Subjects for each grade
        $subjectTemplates = [
            // Nursery & KG subjects
            ['name' => 'English', 'code' => 'ENG', 'max_marks' => 100, 'pass_marks' => 40, 'description' => 'English Language'],
            ['name' => 'Mathematics', 'code' => 'MATH', 'max_marks' => 100, 'pass_marks' => 40, 'description' => 'Basic Mathematics'],
            ['name' => 'General Knowledge', 'code' => 'GK', 'max_marks' => 50, 'pass_marks' => 20, 'description' => 'General Knowledge'],
            ['name' => 'Drawing', 'code' => 'ART', 'max_marks' => 50, 'pass_marks' => 20, 'description' => 'Art and Drawing'],
            
            // Primary grades additional subjects
            ['name' => 'Science', 'code' => 'SCI', 'max_marks' => 100, 'pass_marks' => 40, 'description' => 'General Science'],
            ['name' => 'Social Studies', 'code' => 'SST', 'max_marks' => 100, 'pass_marks' => 40, 'description' => 'Social Studies'],
            ['name' => 'Computer', 'code' => 'COMP', 'max_marks' => 50, 'pass_marks' => 20, 'description' => 'Computer Studies'],
            ['name' => 'Physical Education', 'code' => 'PE', 'max_marks' => 50, 'pass_marks' => 20, 'description' => 'Physical Education'],
        ];

        foreach ($allGrades as $grade) {
            $schoolTeachers = $allTeachers->where('school_id', $grade->school_id);
            
            // Determine subjects based on grade level
            if (in_array($grade->name, ['Nursery', 'KG-1', 'KG-2'])) {
                $subjects = array_slice($subjectTemplates, 0, 4); // First 4 subjects for early grades
            } else {
                $subjects = $subjectTemplates; // All subjects for primary grades
            }
            
            foreach ($subjects as $index => $subject) {
                $teacher = $schoolTeachers->skip($index % $schoolTeachers->count())->first();
                Subject::create(array_merge($subject, [
                    'grade_id' => $grade->id,
                    'teacher_id' => $teacher->id,
                    'school_id' => $grade->school_id
                ]));
            }
        }

        // Create Students for each grade
        $studentNames = [
            // Male names
            ['name' => 'Alexander Johnson', 'gender' => 'male'],
            ['name' => 'Benjamin Smith', 'gender' => 'male'],
            ['name' => 'Christopher Brown', 'gender' => 'male'],
            ['name' => 'Daniel Wilson', 'gender' => 'male'],
            ['name' => 'Ethan Davis', 'gender' => 'male'],
            ['name' => 'Felix Martinez', 'gender' => 'male'],
            ['name' => 'Gabriel Anderson', 'gender' => 'male'],
            ['name' => 'Henry Taylor', 'gender' => 'male'],
            ['name' => 'Isaac Thompson', 'gender' => 'male'],
            ['name' => 'Jacob White', 'gender' => 'male'],
            
            // Female names
            ['name' => 'Amelia Rodriguez', 'gender' => 'female'],
            ['name' => 'Bella Chen', 'gender' => 'female'],
            ['name' => 'Charlotte Williams', 'gender' => 'female'],
            ['name' => 'Diana Garcia', 'gender' => 'female'],
            ['name' => 'Emma Lopez', 'gender' => 'female'],
            ['name' => 'Fiona Miller', 'gender' => 'female'],
            ['name' => 'Grace Lee', 'gender' => 'female'],
            ['name' => 'Hannah Clark', 'gender' => 'female'],
            ['name' => 'Isabella Lewis', 'gender' => 'female'],
            ['name' => 'Julia Walker', 'gender' => 'female'],
        ];

        foreach ($allGrades as $grade) {
            $studentsPerSection = min(15, $grade->capacity); // Max 15 students per section for demo
            
            for ($i = 0; $i < $studentsPerSection; $i++) {
                $studentTemplate = $studentNames[$i % count($studentNames)];
                $rollNumber = str_pad($i + 1, 3, '0', STR_PAD_LEFT);
                
                Student::create([
                    'name' => $studentTemplate['name'],
                    'roll_number' => $grade->name . '-' . $grade->section . '-' . $rollNumber,
                    'email' => strtolower(str_replace(' ', '.', $studentTemplate['name'])) . '@student.edu',
                    'phone' => '+1-555-' . rand(2000, 9999),
                    'date_of_birth' => Carbon::now()->subYears(rand(5, 12))->subDays(rand(1, 365)),
                    'gender' => $studentTemplate['gender'],
                    'address' => rand(100, 999) . ' Student Street, City',
                    'father_name' => 'Mr. ' . explode(' ', $studentTemplate['name'])[1],
                    'mother_name' => 'Mrs. ' . explode(' ', $studentTemplate['name'])[1],
                    'guardian_phone' => '+1-555-' . rand(3000, 8999),
                    'grade_id' => $grade->id,
                    'school_id' => $grade->school_id,
                    'admission_date' => Carbon::now()->subMonths(rand(1, 24)),
                    'is_active' => true
                ]);
            }
        }

        // Create System Settings for each school
        $systemSettings = [
            ['key' => 'school_name', 'value' => 'Default School Name'],
            ['key' => 'school_logo', 'value' => null],
            ['key' => 'school_favicon', 'value' => null],
            ['key' => 'academic_year', 'value' => '2024-2025'],
            ['key' => 'pass_percentage', 'value' => '40'],
            ['key' => 'grading_system', 'value' => 'percentage'],
        ];

        foreach ($schoolIds as $schoolId) {
            foreach ($systemSettings as $setting) {
                SystemSetting::create(array_merge($setting, ['school_id' => $schoolId]));
            }
        }

        // Create Grading Systems
        $gradingSystems = [
            ['name' => 'Standard Grading', 'description' => 'Standard percentage-based grading', 'is_active' => true],
            ['name' => 'Advanced Grading', 'description' => 'Advanced GPA-based grading', 'is_active' => false],
        ];

        foreach ($schoolIds as $schoolId) {
            foreach ($gradingSystems as $system) {
                GradingSystem::create(array_merge($system, ['school_id' => $schoolId]));
            }
        }

        // Create Grading System Grades
        $gradingGrades = [
            ['grade' => 'A+', 'min_percentage' => 90, 'max_percentage' => 100, 'grade_points' => 4.0, 'is_passing' => true, 'sort_order' => 1],
            ['grade' => 'A', 'min_percentage' => 80, 'max_percentage' => 89, 'grade_points' => 3.7, 'is_passing' => true, 'sort_order' => 2],
            ['grade' => 'B+', 'min_percentage' => 70, 'max_percentage' => 79, 'grade_points' => 3.3, 'is_passing' => true, 'sort_order' => 3],
            ['grade' => 'B', 'min_percentage' => 60, 'max_percentage' => 69, 'grade_points' => 3.0, 'is_passing' => true, 'sort_order' => 4],
            ['grade' => 'C+', 'min_percentage' => 50, 'max_percentage' => 59, 'grade_points' => 2.7, 'is_passing' => true, 'sort_order' => 5],
            ['grade' => 'C', 'min_percentage' => 40, 'max_percentage' => 49, 'grade_points' => 2.0, 'is_passing' => true, 'sort_order' => 6],
            ['grade' => 'D', 'min_percentage' => 33, 'max_percentage' => 39, 'grade_points' => 1.0, 'is_passing' => true, 'sort_order' => 7],
            ['grade' => 'F', 'min_percentage' => 0, 'max_percentage' => 32, 'grade_points' => 0.0, 'is_passing' => false, 'sort_order' => 8],
        ];

        foreach ($schoolIds as $schoolId) {
            foreach ($gradingGrades as $grade) {
                GradingSystem::create(array_merge($grade, [
                    'name' => 'Grade ' . $grade['grade'],
                    'description' => 'Grade ' . $grade['grade'] . ' for ' . $grade['min_percentage'] . '-' . $grade['max_percentage'] . '%',
                    'is_active' => true,
                    'school_id' => $schoolId
                ]));
            }
        }

        // Create Marksheets and Marks
        $allStudents = Student::with('grade')->get();
        $allSubjects = Subject::all();
        $allExamTypes = ExamType::all();

        foreach ($allStudents as $student) {
            $gradeSubjects = $allSubjects->where('grade_id', $student->grade_id);
            $schoolExamTypes = $allExamTypes->where('school_id', $student->school_id);
            
            foreach ($schoolExamTypes->take(3) as $examType) { // Create marksheets for 3 exam types
                $marksheet = Marksheet::create([
                    'student_id' => $student->id,
                    'exam_name' => $examType->name . ' Examination',
                    'exam_type_id' => $examType->id,
                    'exam_date' => Carbon::now()->subDays(rand(30, 180)),
                    'class' => $student->grade->name,
                    'section' => $student->grade->section,
                    'academic_year' => '2024-2025',
                    'total_marks' => 0,
                    'obtained_marks' => 0,
                    'percentage' => 0,
                    'grade' => 'A',
                    'result' => 'PASS',
                    'school_id' => $student->school_id
                ]);

                $totalMarks = 0;
                $obtainedMarks = 0;

                foreach ($gradeSubjects as $subject) {
                    $obtained = rand($subject->pass_marks, $subject->max_marks);
                    $totalMarks += $subject->max_marks;
                    $obtainedMarks += $obtained;

                    // Calculate grade
                    $percentage = ($obtained / $subject->max_marks) * 100;
                    if ($percentage >= 90) $grade = 'A+';
                    elseif ($percentage >= 80) $grade = 'A';
                    elseif ($percentage >= 70) $grade = 'B+';
                    elseif ($percentage >= 60) $grade = 'B';
                    elseif ($percentage >= 50) $grade = 'C+';
                    elseif ($percentage >= 40) $grade = 'C';
                    elseif ($percentage >= 33) $grade = 'D';
                    else $grade = 'F';

                    MarksheetMark::create([
                        'marksheet_id' => $marksheet->id,
                        'subject_id' => $subject->id,
                        'obtained_marks' => $obtained,
                        'grade' => $grade
                    ]);
                }

                // Update marksheet totals
                $overallPercentage = ($obtainedMarks / $totalMarks) * 100;
                if ($overallPercentage >= 90) $overallGrade = 'A+';
                elseif ($overallPercentage >= 80) $overallGrade = 'A';
                elseif ($overallPercentage >= 70) $overallGrade = 'B+';
                elseif ($overallPercentage >= 60) $overallGrade = 'B';
                elseif ($overallPercentage >= 50) $overallGrade = 'C+';
                elseif ($overallPercentage >= 40) $overallGrade = 'C';
                elseif ($overallPercentage >= 33) $overallGrade = 'D';
                else $overallGrade = 'F';

                $marksheet->update([
                    'total_marks' => $totalMarks,
                    'obtained_marks' => $obtainedMarks,
                    'percentage' => round($overallPercentage, 2),
                    'grade' => $overallGrade,
                    'result' => $overallPercentage >= 40 ? 'PASS' : 'FAIL'
                ]);
            }
        }

        // Create Attendance Records
        foreach ($allStudents as $student) {
            for ($i = 0; $i < 30; $i++) { // 30 days of attendance
                $date = Carbon::now()->subDays($i);
                if ($date->isWeekday()) { // Only weekdays
                    $statuses = ['present', 'absent', 'late', 'excused'];
                    $weights = [85, 5, 7, 3]; // 85% present, 5% absent, 7% late, 3% excused
                    
                    $status = $this->weightedRandom($statuses, $weights);
                    
                    Attendance::create([
                        'student_id' => $student->id,
                        'attendance_date' => $date,
                        'status' => $status,
                        'note' => $status === 'absent' ? 'Family emergency' : null
                    ]);
                }
            }
        }

        // Create Individual Marks (separate from marksheets)
        foreach ($allStudents->take(50) as $student) { // Limit to 50 students for performance
            $gradeSubjects = $allSubjects->where('grade_id', $student->grade_id);
            $schoolExamTypes = $allExamTypes->where('school_id', $student->school_id);
            
            foreach ($gradeSubjects->take(3) as $subject) { // 3 subjects per student
                foreach ($schoolExamTypes->take(2) as $examType) { // 2 exam types
                    Mark::create([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'mark_obtained' => rand($subject->pass_marks, $subject->max_marks),
                        'total_marks' => $subject->max_marks,
                        'exam_type' => $examType->name,
                        'exam_date' => Carbon::now()->subDays(rand(15, 90))
                    ]);
                }
            }
        }

        $this->command->info('Comprehensive dummy data created successfully!');
        $this->command->info('Created:');
        $this->command->info('- ' . School::count() . ' Schools');
        $this->command->info('- ' . Grade::count() . ' Grades/Classes');
        $this->command->info('- ' . Teacher::count() . ' Teachers');
        $this->command->info('- ' . Student::count() . ' Students');
        $this->command->info('- ' . Subject::count() . ' Subjects');
        $this->command->info('- ' . ExamType::count() . ' Exam Types');
        $this->command->info('- ' . Marksheet::count() . ' Marksheets');
        $this->command->info('- ' . MarksheetMark::count() . ' Marksheet Marks');
        $this->command->info('- ' . Mark::count() . ' Individual Marks');
        $this->command->info('- ' . Attendance::count() . ' Attendance Records');
    }

    private function weightedRandom($values, $weights)
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        
        $currentWeight = 0;
        foreach ($values as $index => $value) {
            $currentWeight += $weights[$index];
            if ($random <= $currentWeight) {
                return $value;
            }
        }
        
        return $values[0]; // Fallback
    }
}