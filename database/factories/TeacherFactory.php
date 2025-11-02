<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        $firstName = $this->faker->firstName($gender);
        $lastName = $this->faker->lastName;
        
        $qualifications = [
            'Bachelor of Education',
            'Master of Education',
            'Bachelor of Arts',
            'Master of Arts',
            'Bachelor of Science',
            'Master of Science',
            'PhD in Education',
            'Bachelor of Mathematics',
            'Master of Mathematics'
        ];

        return [
            'school_id' => \App\Models\School::factory(),
            'user_id' => \App\Models\User::factory(),
            'employee_id' => 'EMP' . $this->faker->unique()->numberBetween(1000, 9999),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'middle_name' => $this->faker->optional(0.3)->firstName,
            'phone' => $this->faker->phoneNumber,
            'email' => strtolower($firstName . '.' . $lastName) . '@school.edu',
            'address' => $this->faker->address,
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-25 years'),
            'gender' => $gender,
            'qualification' => $this->faker->randomElement($qualifications),
            'experience' => $this->faker->numberBetween(1, 30),
            'salary' => $this->faker->numberBetween(35000, 85000),
            'joining_date' => $this->faker->dateTimeBetween('-10 years', 'now'),
            'is_active' => $this->faker->boolean(95), // 95% chance of being active
        ];
    }
}
