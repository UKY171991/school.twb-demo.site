<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin (no school assignment)
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'superadmin@school.com',
            'password' => Hash::make('password'),
            'role_id' => 1, // Super Admin
            'school_id' => null,
            'phone' => '+44 111 111 1111',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Windsor Park School Users
        $windsorSchoolId = 1;
        $roles = [
            ['name' => 'Windsor Admin', 'email' => 'admin.windsor@school.com', 'role_id' => 2], // Admin
            ['name' => 'Windsor Guardian', 'email' => 'guardian.windsor@school.com', 'role_id' => 5], // Guardian
            ['name' => 'Windsor Student', 'email' => 'student.windsor@school.com', 'role_id' => 4], // Student
            ['name' => 'Windsor Teacher', 'email' => 'teacher.windsor@school.com', 'role_id' => 3], // Teacher
            ['name' => 'Windsor Accountant', 'email' => 'accountant.windsor@school.com', 'role_id' => 6], // Accountant
            ['name' => 'Windsor Librarian', 'email' => 'librarian.windsor@school.com', 'role_id' => 7], // Librarian
            ['name' => 'Windsor Receptionist', 'email' => 'receptionist.windsor@school.com', 'role_id' => 8], // Receptionist
            ['name' => 'Windsor Staff', 'email' => 'staff.windsor@school.com', 'role_id' => 9], // Staff
        ];

        foreach ($roles as $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'role_id' => $user['role_id'],
                'school_id' => $windsorSchoolId,
                'phone' => '+44 222 222 2222',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Ideal Stevenson School Users
        $stevensonSchoolId = 2;
        $roles = [
            ['name' => 'Stevenson Admin', 'email' => 'admin.stevenson@school.com', 'role_id' => 2], // Admin
            ['name' => 'Stevenson Guardian', 'email' => 'guardian.stevenson@school.com', 'role_id' => 5], // Guardian
            ['name' => 'Stevenson Student', 'email' => 'student.stevenson@school.com', 'role_id' => 4], // Student
            ['name' => 'Stevenson Teacher', 'email' => 'teacher.stevenson@school.com', 'role_id' => 3], // Teacher
            ['name' => 'Stevenson Accountant', 'email' => 'accountant.stevenson@school.com', 'role_id' => 6], // Accountant
            ['name' => 'Stevenson Librarian', 'email' => 'librarian.stevenson@school.com', 'role_id' => 7], // Librarian
            ['name' => 'Stevenson Receptionist', 'email' => 'receptionist.stevenson@school.com', 'role_id' => 8], // Receptionist
            ['name' => 'Stevenson Staff', 'email' => 'staff.stevenson@school.com', 'role_id' => 9], // Staff
        ];

        foreach ($roles as $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'role_id' => $user['role_id'],
                'school_id' => $stevensonSchoolId,
                'phone' => '+44 333 333 3333',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
