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
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create teachers
        $teacher1 = User::create([
            'name' => 'John Doe',
            'email' => 'teacher1@example.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);
        \App\Models\Teacher::create([
            'user_id' => $teacher1->id,
            'employee_id' => 'T001',
            'department' => 'Mathematics',
            'bio' => 'Experienced math teacher.',
        ]);

        $teacher2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'teacher2@example.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);
        \App\Models\Teacher::create([
            'user_id' => $teacher2->id,
            'employee_id' => 'T002',
            'department' => 'Science',
            'bio' => 'Passionate about science education.',
        ]);

        // Create subjects
        $math = \App\Models\Subject::create([
            'name' => 'Mathematics',
            'description' => 'Basic to advanced math concepts.',
        ]);

        $science = \App\Models\Subject::create([
            'name' => 'Science',
            'description' => 'Physics, Chemistry, Biology.',
        ]);

        // Create classrooms
        $classroom1 = \App\Models\Classroom::create([
            'name' => 'Math 101',
            'teacher_id' => 1, // teacher1
            'subject_id' => $math->id,
            'capacity' => 25,
        ]);

        $classroom2 = \App\Models\Classroom::create([
            'name' => 'Science 101',
            'teacher_id' => 2, // teacher2
            'subject_id' => $science->id,
            'capacity' => 30,
        ]);

        // Create students
        $student1 = User::create([
            'name' => 'Alice Johnson',
            'email' => 'student1@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        \App\Models\Student::create([
            'user_id' => $student1->id,
            'student_id' => 'S001',
            'date_of_birth' => '2005-05-15',
            'address' => '123 Main St',
            'phone' => '555-1234',
        ]);

        $student2 = User::create([
            'name' => 'Bob Wilson',
            'email' => 'student2@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        \App\Models\Student::create([
            'user_id' => $student2->id,
            'student_id' => 'S002',
            'date_of_birth' => '2004-08-20',
            'address' => '456 Oak Ave',
            'phone' => '555-5678',
        ]);

        // Enroll students
        \App\Models\Enrollment::create([
            'student_id' => 1, // student1
            'classroom_id' => $classroom1->id,
        ]);

        \App\Models\Enrollment::create([
            'student_id' => 2, // student2
            'classroom_id' => $classroom2->id,
        ]);

        // Add grades
        \App\Models\Grade::create([
            'enrollment_id' => 1,
            'grade' => 95.5,
            'comments' => 'Excellent work!',
        ]);

        \App\Models\Grade::create([
            'enrollment_id' => 2,
            'grade' => 88.0,
            'comments' => 'Good effort.',
        ]);
    }
}
