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
        $this->call([
            CreateBasicUsers::class,
        ]);

        $this->command->info('Database seeded successfully with basic users!');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@school.com / password');
        $this->command->info('Manager: manager@school.com / password');
        $this->command->info('Teacher: teacher@school.com / password');
    }
}
