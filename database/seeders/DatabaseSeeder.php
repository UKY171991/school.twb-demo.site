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

        // Create additional users
        User::factory()->create([
            'name' => 'School Manager',
            'email' => 'manager@school.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Teacher User',
            'email' => 'teacher@school.com',
            'password' => bcrypt('password'),
        ]);

        $this->command->info('Database seeded successfully with basic users!');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@school.com / password');
        $this->command->info('Manager: manager@school.com / password');
        $this->command->info('Teacher: teacher@school.com / password');
    }
}
