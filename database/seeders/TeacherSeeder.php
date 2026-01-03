<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\School;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first school
        $school = School::first();
        if (!$school) {
            return;
        }

        // Create sample teachers
        $teachers = [
            ['name' => 'John Smith', 'email' => 'john.smith@school.com'],
            ['name' => 'Jane Doe', 'email' => 'jane.doe@school.com'],
            ['name' => 'Robert Johnson', 'email' => 'robert.johnson@school.com'],
        ];

        foreach ($teachers as $teacherData) {
            Teacher::create(array_merge($teacherData, [
                'school_id' => $school->id,
                'gender' => 'male',
                'phone' => '0000000000',
                'address' => 'Teacher Address',
            ]));
        }
    }
}
