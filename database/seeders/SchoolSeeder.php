<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Windsor Park School',
                'code' => 'WPS',
                'address' => '123 Park Avenue, Windsor, UK',
                'phone' => '+44 123 456 7890',
                'email' => 'info@windsorpark.edu',
                'website' => 'https://windsorpark.edu',
                'description' => 'A prestigious institution focused on academic excellence',
                'is_active' => true,
            ],
            [
                'name' => 'Ideal Stevenson School',
                'code' => 'ISS',
                'address' => '456 Stevenson Road, London, UK',
                'phone' => '+44 987 654 3210',
                'email' => 'contact@idealstevenson.edu',
                'website' => 'https://idealstevenson.edu',
                'description' => 'Nurturing young minds for a brighter future',
                'is_active' => true,
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->insert([
                'name' => $school['name'],
                'code' => $school['code'],
                'address' => $school['address'],
                'phone' => $school['phone'],
                'email' => $school['email'],
                'website' => $school['website'],
                'description' => $school['description'],
                'is_active' => $school['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
