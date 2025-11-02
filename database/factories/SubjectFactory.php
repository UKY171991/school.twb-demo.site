<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH', 'credits' => 4, 'type' => 'core'],
            ['name' => 'English Language Arts', 'code' => 'ELA', 'credits' => 4, 'type' => 'core'],
            ['name' => 'Science', 'code' => 'SCI', 'credits' => 3, 'type' => 'core'],
            ['name' => 'Social Studies', 'code' => 'SS', 'credits' => 3, 'type' => 'core'],
            ['name' => 'Physical Education', 'code' => 'PE', 'credits' => 2, 'type' => 'elective'],
            ['name' => 'Art', 'code' => 'ART', 'credits' => 2, 'type' => 'elective'],
            ['name' => 'Music', 'code' => 'MUS', 'credits' => 2, 'type' => 'elective'],
            ['name' => 'Computer Science', 'code' => 'CS', 'credits' => 3, 'type' => 'elective'],
            ['name' => 'Foreign Language', 'code' => 'FL', 'credits' => 3, 'type' => 'elective'],
            ['name' => 'Health', 'code' => 'HLTH', 'credits' => 1, 'type' => 'core']
        ];

        $subject = $this->faker->randomElement($subjects);
        $uniqueCode = $subject['code'] . $this->faker->numberBetween(100, 999);

        return [
            'school_id' => \App\Models\School::factory(),
            'teacher_id' => \App\Models\Teacher::factory(),
            'name' => $subject['name'],
            'code' => $uniqueCode,
            'description' => $this->faker->paragraph(2),
            'credits' => $subject['credits'],
            'type' => $subject['type'],
            'is_active' => $this->faker->boolean(95),
        ];
    }
}
