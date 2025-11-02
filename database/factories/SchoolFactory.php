<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $schoolNames = [
            'Greenwood Elementary School',
            'Riverside High School',
            'Oakmont Middle School',
            'Sunset Valley Academy',
            'Maple Grove School',
            'Pine Ridge Elementary',
            'Cedar Hills High School',
            'Brookstone Academy',
            'Willowbrook School',
            'Heritage Preparatory School'
        ];

        $name = $this->faker->randomElement($schoolNames);
        $code = strtoupper($this->faker->unique()->lexify('SCH???'));

        return [
            'name' => $name,
            'code' => $code,
            'address' => $this->faker->streetAddress . ', ' . $this->faker->city . ', ' . $this->faker->stateAbbr . ' ' . $this->faker->postcode,
            'phone' => $this->faker->phoneNumber,
            'email' => strtolower(str_replace(' ', '', $name)) . '@school.edu',
            'website' => 'https://' . strtolower(str_replace(' ', '', $name)) . '.edu',
            'description' => $this->faker->paragraph(3),
            'principal_name' => $this->faker->name,
            'principal_phone' => $this->faker->phoneNumber,
            'principal_email' => 'principal@' . strtolower(str_replace(' ', '', $name)) . '.edu',
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            'established_date' => $this->faker->dateTimeBetween('-50 years', '-5 years'),
            'timezone' => $this->faker->randomElement(['America/New_York', 'America/Chicago', 'America/Denver', 'America/Los_Angeles']),
            'configuration' => [
                'academic_year' => [
                    'start_date' => '2024-09-01',
                    'end_date' => '2025-06-30',
                    'current_semester' => 1,
                ],
                'working_days' => [
                    'monday' => true,
                    'tuesday' => true,
                    'wednesday' => true,
                    'thursday' => true,
                    'friday' => true,
                    'saturday' => false,
                    'sunday' => false,
                ],
                'school_timings' => [
                    'start_time' => '08:00',
                    'end_time' => '15:00',
                    'break_start' => '10:30',
                    'break_end' => '11:00',
                    'lunch_start' => '12:30',
                    'lunch_end' => '13:30',
                ],
            ],
        ];
    }
}
