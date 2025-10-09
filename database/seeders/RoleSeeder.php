<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Full system access across all schools'],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'School administrator'],
            ['name' => 'Teacher', 'slug' => 'teacher', 'description' => 'Teaching staff'],
            ['name' => 'Student', 'slug' => 'student', 'description' => 'Student'],
            ['name' => 'Guardian', 'slug' => 'guardian', 'description' => 'Parent/Guardian'],
            ['name' => 'Accountant', 'slug' => 'accountant', 'description' => 'Manages fees and accounts'],
            ['name' => 'Librarian', 'slug' => 'librarian', 'description' => 'Library management'],
            ['name' => 'Receptionist', 'slug' => 'receptionist', 'description' => 'Front desk management'],
            ['name' => 'Staff', 'slug' => 'staff', 'description' => 'General staff member'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role['name'],
                'slug' => $role['slug'],
                'description' => $role['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
