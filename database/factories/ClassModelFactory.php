<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassModel>
 */
class ClassModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $grades = ['Kindergarten', '1st Grade', '2nd Grade', '3rd Grade', '4th Grade', '5th Grade', '6th Grade', '7th Grade', '8th Grade', '9th Grade', '10th Grade', '11th Grade', '12th Grade'];
        $sections = ['A', 'B', 'C', 'D', 'E'];
        
        $grade = $this->faker->randomElement($grades);
        $section = $this->faker->randomElement($sections);

        return [
            'school_id' => \App\Models\School::factory(),
            'teacher_id' => \App\Models\Teacher::factory(),
            'name' => $grade,
            'section' => $section,
            'capacity' => $this->faker->numberBetween(20, 35),
            'room_number' => $this->faker->numberBetween(101, 999),
            'description' => $this->faker->optional(0.7)->sentence,
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
