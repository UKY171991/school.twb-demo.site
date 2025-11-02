<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParentModel>
 */
class ParentModelFactory extends Factory
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
        
        $relationships = ['father', 'mother', 'guardian'];
        $occupations = [
            'Teacher', 'Engineer', 'Doctor', 'Nurse', 'Lawyer', 'Accountant', 'Manager', 'Sales Representative',
            'Technician', 'Consultant', 'Designer', 'Developer', 'Analyst', 'Administrator', 'Supervisor'
        ];

        return [
            'school_id' => \App\Models\School::factory(),
            'user_id' => \App\Models\User::factory(),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'middle_name' => $this->faker->optional(0.3)->firstName,
            'phone' => $this->faker->phoneNumber,
            'email' => strtolower($firstName . '.' . $lastName) . '@email.com',
            'address' => $this->faker->address,
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-25 years'),
            'gender' => $gender,
            'occupation' => $this->faker->randomElement($occupations),
            'company' => $this->faker->optional(0.8)->company,
            'annual_income' => $this->faker->numberBetween(25000, 150000),
            'relationship' => $this->faker->randomElement($relationships),
            'is_primary_contact' => $this->faker->boolean(60), // 60% chance of being primary contact
        ];
    }
}
