<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Greenwood High School',
                'code' => 'GHS001',
                'address' => '123 Education Street, Learning City, LC 12345',
                'phone' => '+1-555-0123',
                'email' => 'info@greenwoodhigh.edu',
                'website' => 'https://www.greenwoodhigh.edu',
                'principal_name' => 'Dr. Sarah Johnson',
                'description' => 'A premier educational institution committed to excellence in academics and character development.',
                'status' => 'active',
            ],
            [
                'name' => 'Riverside Elementary School',
                'code' => 'RES002',
                'address' => '456 River Road, Riverside, RS 67890',
                'phone' => '+1-555-0456',
                'email' => 'contact@riverside-elem.edu',
                'website' => 'https://www.riverside-elem.edu',
                'principal_name' => 'Mr. Michael Chen',
                'description' => 'Nurturing young minds with innovative teaching methods and a caring environment.',
                'status' => 'active',
            ],
            [
                'name' => 'Oakwood Academy',
                'code' => 'OAK003',
                'address' => '789 Oak Avenue, Oakville, OV 13579',
                'phone' => '+1-555-0789',
                'email' => 'admin@oakwoodacademy.edu',
                'website' => 'https://www.oakwoodacademy.edu',
                'principal_name' => 'Ms. Emily Rodriguez',
                'description' => 'A comprehensive K-12 institution focusing on STEM education and arts integration.',
                'status' => 'active',
            ],
        ];

        foreach ($schools as $school) {
            School::create($school);
        }
    }
}
