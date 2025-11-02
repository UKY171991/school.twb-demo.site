<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userTypes = ['super_admin', 'admin', 'teacher', 'student', 'parent'];
        $userType = $this->faker->randomElement($userTypes, [5, 15, 25, 40, 15]); // Weighted distribution

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'user_type' => $userType,
            'school_id' => $userType === 'super_admin' ? null : \App\Models\School::factory(),
            'phone' => $this->faker->optional(0.8)->phoneNumber,
            'is_active' => $this->faker->boolean(95),
            'last_login_at' => $this->faker->optional(0.7)->dateTimeBetween('-30 days', 'now'),
            'preferences' => [
                'theme' => $this->faker->randomElement(['light', 'dark']),
                'language' => $this->faker->randomElement(['en', 'es', 'fr']),
                'notifications' => [
                    'email' => $this->faker->boolean(80),
                    'sms' => $this->faker->boolean(60),
                    'push' => $this->faker->boolean(90),
                ]
            ],
        ];
    }

    /**
     * Create a super admin user
     */
    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'super_admin',
            'school_id' => null,
            'is_active' => true,
        ]);
    }

    /**
     * Create an admin user
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'admin',
            'is_active' => true,
        ]);
    }

    /**
     * Create a teacher user
     */
    public function teacher(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'teacher',
            'is_active' => true,
        ]);
    }

    /**
     * Create a student user
     */
    public function student(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'student',
            'is_active' => true,
        ]);
    }

    /**
     * Create a parent user
     */
    public function parent(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'parent',
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
