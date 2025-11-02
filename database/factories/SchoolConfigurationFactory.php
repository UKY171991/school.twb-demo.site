<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchoolConfiguration>
 */
class SchoolConfigurationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $configKeys = [
            'school_year_start' => ['value' => '09-01', 'type' => 'string', 'is_public' => true],
            'school_year_end' => ['value' => '06-30', 'type' => 'string', 'is_public' => true],
            'attendance_required_percentage' => ['value' => $this->faker->numberBetween(70, 85), 'type' => 'integer', 'is_public' => true],
            'max_students_per_class' => ['value' => $this->faker->numberBetween(25, 35), 'type' => 'integer', 'is_public' => false],
            'enable_parent_portal' => ['value' => $this->faker->boolean(90), 'type' => 'boolean', 'is_public' => false],
            'enable_student_portal' => ['value' => $this->faker->boolean(95), 'type' => 'boolean', 'is_public' => false],
            'notification_email' => ['value' => $this->faker->email, 'type' => 'string', 'is_public' => false],
            'grading_scale' => [
                'value' => json_encode([
                    'A' => ['min' => 90, 'max' => 100],
                    'B' => ['min' => 80, 'max' => 89],
                    'C' => ['min' => 70, 'max' => 79],
                    'D' => ['min' => 60, 'max' => 69],
                    'F' => ['min' => 0, 'max' => 59],
                ]),
                'type' => 'json',
                'is_public' => true
            ],
            'school_colors' => [
                'value' => json_encode([
                    'primary' => $this->faker->hexColor,
                    'secondary' => $this->faker->hexColor
                ]),
                'type' => 'json',
                'is_public' => true
            ]
        ];

        $selectedKey = $this->faker->randomElement(array_keys($configKeys));
        $config = $configKeys[$selectedKey];

        return [
            'school_id' => \App\Models\School::factory(),
            'key' => $selectedKey . '_' . $this->faker->unique()->numberBetween(1, 1000),
            'value' => is_bool($config['value']) ? ($config['value'] ? '1' : '0') : (string) $config['value'],
            'type' => $config['type'],
            'description' => $this->faker->sentence,
            'is_public' => $config['is_public'],
        ];
    }
}
