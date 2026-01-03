<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateBasicUsers extends Seeder
{
    public function run(): void
    {
        // Create admin user if not exists
        $admin = User::firstOrCreate([
            'email' => 'admin@school.com'
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
        ]);

        // Create manager user if not exists
        $manager = User::firstOrCreate([
            'email' => 'manager@school.com'
        ], [
            'name' => 'School Manager',
            'password' => Hash::make('password'),
        ]);

        // Create teacher user if not exists
        $teacher = User::firstOrCreate([
            'email' => 'teacher@school.com'
        ], [
            'name' => 'Teacher User',
            'password' => Hash::make('password'),
        ]);

        $this->command->info('Basic users created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@school.com / password');
        $this->command->info('Manager: manager@school.com / password');
        $this->command->info('Teacher: teacher@school.com / password');
    }
}
