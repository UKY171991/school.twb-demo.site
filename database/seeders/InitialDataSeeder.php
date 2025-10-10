<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\School;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@school.com',
            'password' => Hash::make('password'),
            'user_type' => 'super_admin',
            'is_active' => true,
        ]);

        // Create Sample School
        $school = School::create([
            'name' => 'Demo School',
            'code' => 'DEMO001',
            'address' => '123 Education Street, Learning City, LC 12345',
            'phone' => '+1-555-0123',
            'email' => 'info@demoschool.com',
            'website' => 'https://demoschool.com',
            'description' => 'A comprehensive educational institution focused on student development.',
            'principal_name' => 'Dr. Jane Smith',
            'principal_phone' => '+1-555-0124',
            'principal_email' => 'principal@demoschool.com',
            'is_active' => true,
        ]);

        // Create Admin User
        $admin = User::create([
            'name' => 'School Admin',
            'email' => 'admin@demoschool.com',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'school_id' => $school->id,
            'is_active' => true,
        ]);

        // Create Teacher Users and Profiles
        $teacherUsers = [
            [
                'name' => 'John Teacher',
                'email' => 'john.teacher@demoschool.com',
                'employee_id' => 'T001',
                'first_name' => 'John',
                'last_name' => 'Teacher',
                'phone' => '+1-555-0201',
                'address' => '456 Teacher Lane, Education City',
                'date_of_birth' => '1985-03-15',
                'gender' => 'male',
                'qualification' => 'M.Ed in Mathematics',
                'experience' => '5 years',
                'salary' => 45000.00,
                'joining_date' => '2020-09-01',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@demoschool.com',
                'employee_id' => 'T002',
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'phone' => '+1-555-0202',
                'address' => '789 Educator Street, Learning Town',
                'date_of_birth' => '1988-07-22',
                'gender' => 'female',
                'qualification' => 'B.Ed in English Literature',
                'experience' => '3 years',
                'salary' => 42000.00,
                'joining_date' => '2021-09-01',
            ],
            [
                'name' => 'Mike Wilson',
                'email' => 'mike.wilson@demoschool.com',
                'employee_id' => 'T003',
                'first_name' => 'Mike',
                'last_name' => 'Wilson',
                'phone' => '+1-555-0203',
                'address' => '321 Science Avenue, Knowledge City',
                'date_of_birth' => '1982-11-10',
                'gender' => 'male',
                'qualification' => 'M.Sc in Physics',
                'experience' => '7 years',
                'salary' => 48000.00,
                'joining_date' => '2019-09-01',
            ]
        ];

        $teachers = [];
        foreach ($teacherUsers as $teacherData) {
            $user = User::create([
                'name' => $teacherData['name'],
                'email' => $teacherData['email'],
                'password' => Hash::make('password'),
                'user_type' => 'teacher',
                'school_id' => $school->id,
                'is_active' => true,
            ]);

            $teacher = Teacher::create([
                'school_id' => $school->id,
                'user_id' => $user->id,
                'employee_id' => $teacherData['employee_id'],
                'first_name' => $teacherData['first_name'],
                'last_name' => $teacherData['last_name'],
                'phone' => $teacherData['phone'],
                'email' => $teacherData['email'],
                'address' => $teacherData['address'],
                'date_of_birth' => $teacherData['date_of_birth'],
                'gender' => $teacherData['gender'],
                'qualification' => $teacherData['qualification'],
                'experience' => $teacherData['experience'],
                'salary' => $teacherData['salary'],
                'joining_date' => $teacherData['joining_date'],
                'is_active' => true,
            ]);

            $teachers[] = $teacher;
        }

        // Create Classes
        $classes = [
            ['name' => 'Grade 1', 'section' => 'A', 'capacity' => 30, 'teacher_id' => $teachers[0]->id],
            ['name' => 'Grade 2', 'section' => 'A', 'capacity' => 30, 'teacher_id' => $teachers[1]->id],
            ['name' => 'Grade 3', 'section' => 'A', 'capacity' => 30, 'teacher_id' => $teachers[2]->id],
            ['name' => 'Grade 4', 'section' => 'A', 'capacity' => 30, 'teacher_id' => $teachers[0]->id],
            ['name' => 'Grade 5', 'section' => 'A', 'capacity' => 30, 'teacher_id' => $teachers[1]->id],
        ];

        $createdClasses = [];
        foreach ($classes as $classData) {
            $class = ClassModel::create([
                'school_id' => $school->id,
                'teacher_id' => $classData['teacher_id'],
                'name' => $classData['name'],
                'section' => $classData['section'],
                'capacity' => $classData['capacity'],
                'room_number' => 'Room ' . substr($classData['name'], -1),
                'description' => $classData['name'] . ' ' . $classData['section'] . ' Class',
                'is_active' => true,
            ]);
            $createdClasses[] = $class;
        }

        // Create Subjects
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH001', 'teacher_id' => $teachers[0]->id, 'type' => 'core'],
            ['name' => 'English', 'code' => 'ENG001', 'teacher_id' => $teachers[1]->id, 'type' => 'core'],
            ['name' => 'Science', 'code' => 'SCI001', 'teacher_id' => $teachers[2]->id, 'type' => 'core'],
            ['name' => 'Social Studies', 'code' => 'SOC001', 'teacher_id' => $teachers[0]->id, 'type' => 'core'],
            ['name' => 'Physical Education', 'code' => 'PE001', 'teacher_id' => $teachers[1]->id, 'type' => 'extra_curricular'],
        ];

        $createdSubjects = [];
        foreach ($subjects as $subjectData) {
            $subject = Subject::create([
                'school_id' => $school->id,
                'teacher_id' => $subjectData['teacher_id'],
                'name' => $subjectData['name'],
                'code' => $subjectData['code'],
                'description' => $subjectData['name'] . ' subject',
                'credits' => 1,
                'type' => $subjectData['type'],
                'is_active' => true,
            ]);
            $createdSubjects[] = $subject;
        }

        // Create Parent Users and Profiles
        $parentUsers = [
            [
                'name' => 'Robert Parent',
                'email' => 'robert.parent@email.com',
                'first_name' => 'Robert',
                'last_name' => 'Parent',
                'phone' => '+1-555-0301',
                'address' => '100 Parent Street, Family City',
                'date_of_birth' => '1975-05-20',
                'gender' => 'male',
                'occupation' => 'Engineer',
                'company' => 'Tech Corp',
                'annual_income' => 75000.00,
                'relationship' => 'father',
                'is_primary_contact' => true,
            ],
            [
                'name' => 'Lisa Parent',
                'email' => 'lisa.parent@email.com',
                'first_name' => 'Lisa',
                'last_name' => 'Parent',
                'phone' => '+1-555-0302',
                'address' => '200 Family Avenue, Parent Town',
                'date_of_birth' => '1978-08-15',
                'gender' => 'female',
                'occupation' => 'Teacher',
                'company' => 'Education Inc',
                'annual_income' => 45000.00,
                'relationship' => 'mother',
                'is_primary_contact' => true,
            ]
        ];

        $parents = [];
        foreach ($parentUsers as $parentData) {
            $user = User::create([
                'name' => $parentData['name'],
                'email' => $parentData['email'],
                'password' => Hash::make('password'),
                'user_type' => 'parent',
                'school_id' => $school->id,
                'is_active' => true,
            ]);

            $parent = ParentModel::create([
                'school_id' => $school->id,
                'user_id' => $user->id,
                'first_name' => $parentData['first_name'],
                'last_name' => $parentData['last_name'],
                'phone' => $parentData['phone'],
                'email' => $parentData['email'],
                'address' => $parentData['address'],
                'date_of_birth' => $parentData['date_of_birth'],
                'gender' => $parentData['gender'],
                'occupation' => $parentData['occupation'],
                'company' => $parentData['company'],
                'annual_income' => $parentData['annual_income'],
                'relationship' => $parentData['relationship'],
                'is_primary_contact' => $parentData['is_primary_contact'],
            ]);

            $parents[] = $parent;
        }

        // Create Student Users and Profiles
        $studentUsers = [
            [
                'name' => 'Alex Student',
                'email' => 'alex.student@demoschool.com',
                'student_id' => 'ST001',
                'first_name' => 'Alex',
                'last_name' => 'Student',
                'phone' => '+1-555-0401',
                'address' => '300 Student Street, Learning City',
                'date_of_birth' => '2015-03-10',
                'gender' => 'male',
                'blood_group' => 'A+',
                'emergency_contact' => 'Robert Parent',
                'emergency_phone' => '+1-555-0301',
                'status' => 'active',
                'admission_date' => '2023-09-01',
                'class_id' => $createdClasses[0]->id,
                'parent_id' => $parents[0]->id,
            ],
            [
                'name' => 'Emma Student',
                'email' => 'emma.student@demoschool.com',
                'student_id' => 'ST002',
                'first_name' => 'Emma',
                'last_name' => 'Student',
                'phone' => '+1-555-0402',
                'address' => '400 Learning Lane, Education City',
                'date_of_birth' => '2014-07-25',
                'gender' => 'female',
                'blood_group' => 'B+',
                'emergency_contact' => 'Lisa Parent',
                'emergency_phone' => '+1-555-0302',
                'status' => 'active',
                'admission_date' => '2023-09-01',
                'class_id' => $createdClasses[1]->id,
                'parent_id' => $parents[1]->id,
            ]
        ];

        foreach ($studentUsers as $studentData) {
            $user = User::create([
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'password' => Hash::make('password'),
                'user_type' => 'student',
                'school_id' => $school->id,
                'is_active' => true,
            ]);

            Student::create([
                'school_id' => $school->id,
                'user_id' => $user->id,
                'class_id' => $studentData['class_id'],
                'parent_id' => $studentData['parent_id'],
                'student_id' => $studentData['student_id'],
                'first_name' => $studentData['first_name'],
                'last_name' => $studentData['last_name'],
                'phone' => $studentData['phone'],
                'email' => $studentData['email'],
                'address' => $studentData['address'],
                'date_of_birth' => $studentData['date_of_birth'],
                'gender' => $studentData['gender'],
                'blood_group' => $studentData['blood_group'],
                'emergency_contact' => $studentData['emergency_contact'],
                'emergency_phone' => $studentData['emergency_phone'],
                'status' => $studentData['status'],
                'admission_date' => $studentData['admission_date'],
            ]);
        }

        $this->command->info('Initial data seeded successfully!');
        $this->command->info('Super Admin: superadmin@school.com / password');
        $this->command->info('Admin: admin@demoschool.com / password');
        $this->command->info('Teachers: john.teacher@demoschool.com, sarah.johnson@demoschool.com, mike.wilson@demoschool.com / password');
        $this->command->info('Students: alex.student@demoschool.com, emma.student@demoschool.com / password');
        $this->command->info('Parents: robert.parent@email.com, lisa.parent@email.com / password');
    }
}