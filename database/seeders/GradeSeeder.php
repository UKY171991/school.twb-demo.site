<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grade;
use App\Models\School;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first school (or create one if none exists)
        $school = School::first();
        if (!$school) {
            $school = School::create([
                'name' => 'Default School',
                'code' => 'DEFAULT001',
                'address' => 'Default Address',
                'phone' => '0000000000',
                'email' => 'default@school.com',
            ]);
        }

        // Create sample grades
        $grades = [
            ['name' => 'Class 1', 'section' => 'A', 'capacity' => 40, 'grade_theme' => 1],
            ['name' => 'Class 2', 'section' => 'A', 'capacity' => 35, 'grade_theme' => 2],
            ['name' => 'Class 3', 'section' => 'B', 'capacity' => 45, 'grade_theme' => 3],
            ['name' => 'Class 4', 'section' => 'A', 'capacity' => 40, 'grade_theme' => 4],
            ['name' => 'Class 5', 'section' => 'B', 'capacity' => 38, 'grade_theme' => 5],
        ];

        foreach ($grades as $gradeData) {
            Grade::create(array_merge($gradeData, [
                'school_id' => $school->id,
                'status' => 'active',
                'description' => "Sample description for {$gradeData['name']}",
            ]));
        }
    }
}
