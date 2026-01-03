<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'admin@school.com'],
            [
                'name' => 'School Administrator',
                'email' => 'admin@school.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create teacher user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'teacher@school.com'],
            [
                'name' => 'Test Teacher',
                'email' => 'teacher@school.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
