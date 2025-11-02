<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
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
        
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $statuses = ['active', 'inactive', 'graduated', 'transferred'];

        return [
            'school_id' => \App\Models\School::factory(),
            'user_id' => \App\Models\User::factory(),
            'class_id' => \App\Models\ClassModel::factory(),
            'student_id' => 'STU' . $this->faker->unique()->numberBetween(10000, 99999),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'middle_name' => $this->faker->optional(0.3)->firstName,
            'phone' => $this->faker->optional(0.7)->phoneNumber,
            'email' => $this->faker->optional(0.8)->email,
            'address' => $this->faker->address,
            'date_of_birth' => $this->faker->dateTimeBetween('-18 years', '-5 years'),
            'gender' => $gender,
            'blood_group' => $this->faker->randomElement($bloodGroups),
            'emergency_contact' => $this->faker->name,
            'emergency_phone' => $this->faker->phoneNumber,
            'status' => $this->faker->randomElement($statuses, [85, 5, 5, 5]), // 85% active
            'admission_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
        ];
    }
}
