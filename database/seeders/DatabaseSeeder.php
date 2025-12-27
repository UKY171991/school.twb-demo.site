<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@school.com',
            'password' => bcrypt('password'),
        ]);

        // Create Grades
        $grades = [
            ['name' => 'Grade 1', 'section' => 'A'],
            ['name' => 'Grade 2', 'section' => 'A'],
            ['name' => 'Grade 3', 'section' => 'B'],
            ['name' => 'Grade 4', 'section' => 'A'],
            ['name' => 'Grade 5', 'section' => 'B'],
        ];

        foreach ($grades as $grade) {
            \App\Models\Grade::create($grade);
        }

        // Create Teachers
        $teachers = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@school.com',
                'phone' => '1234567890',
                'gender' => 'male',
                'date_of_birth' => '1985-05-15',
                'date_of_joining' => '2020-01-10',
                'address' => '123 Main St, City'
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@school.com',
                'phone' => '0987654321',
                'gender' => 'female',
                'date_of_birth' => '1990-08-22',
                'date_of_joining' => '2019-09-01',
                'address' => '456 Oak Ave, City'
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.brown@school.com',
                'phone' => '5551234567',
                'gender' => 'male',
                'date_of_birth' => '1988-03-10',
                'date_of_joining' => '2021-03-15',
                'address' => '789 Pine Rd, City'
            ],
        ];

        foreach ($teachers as $teacher) {
            \App\Models\Teacher::create($teacher);
        }

        // Create Students
        $students = [
            [
                'name' => 'Emma Wilson',
                'email' => 'emma.wilson@student.com',
                'phone' => '1112223333',
                'date_of_birth' => '2015-06-12',
                'gender' => 'female',
                'address' => '321 Elm St, City',
                'grade_id' => 1
            ],
            [
                'name' => 'Oliver Davis',
                'email' => 'oliver.davis@student.com',
                'phone' => '4445556666',
                'date_of_birth' => '2014-09-25',
                'gender' => 'male',
                'address' => '654 Maple Dr, City',
                'grade_id' => 2
            ],
            [
                'name' => 'Sophia Martinez',
                'email' => 'sophia.martinez@student.com',
                'phone' => '7778889999',
                'date_of_birth' => '2013-12-03',
                'gender' => 'female',
                'address' => '987 Cedar Ln, City',
                'grade_id' => 3
            ],
        ];

        foreach ($students as $student) {
            \App\Models\Student::create($student);
        }

        // Create Subjects
        $subjects = [
            [
                'name' => 'Mathematics',
                'code' => 'MATH101',
                'description' => 'Basic Mathematics',
                'grade_id' => 1,
                'teacher_id' => 1
            ],
            [
                'name' => 'English',
                'code' => 'ENG101',
                'description' => 'English Language',
                'grade_id' => 1,
                'teacher_id' => 2
            ],
            [
                'name' => 'Science',
                'code' => 'SCI101',
                'description' => 'General Science',
                'grade_id' => 2,
                'teacher_id' => 3
            ],
        ];

        foreach ($subjects as $subject) {
            \App\Models\Subject::create($subject);
        }
    }
}
