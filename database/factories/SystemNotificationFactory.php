<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SystemNotification>
 */
class SystemNotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['info', 'success', 'warning', 'error'];
        $type = $this->faker->randomElement($types);
        
        $titles = [
            'info' => ['New Assignment Posted', 'Schedule Update', 'System Maintenance', 'New Feature Available'],
            'success' => ['Grade Submitted Successfully', 'Attendance Marked', 'Profile Updated', 'Payment Processed'],
            'warning' => ['Assignment Due Soon', 'Low Attendance Warning', 'Password Expires Soon', 'Missing Information'],
            'error' => ['Failed to Submit Grade', 'Login Failed', 'System Error', 'Connection Timeout']
        ];

        $messages = [
            'info' => [
                'A new assignment has been posted for your class.',
                'Your class schedule has been updated.',
                'System maintenance is scheduled for tonight.',
                'Check out the new features in your dashboard.'
            ],
            'success' => [
                'Your grade has been submitted successfully.',
                'Attendance has been marked for today.',
                'Your profile information has been updated.',
                'Payment has been processed successfully.'
            ],
            'warning' => [
                'You have an assignment due in 2 days.',
                'Your attendance is below the required percentage.',
                'Your password will expire in 7 days.',
                'Please complete your profile information.'
            ],
            'error' => [
                'Failed to submit grade. Please try again.',
                'Login failed. Please check your credentials.',
                'A system error occurred. Please contact support.',
                'Connection timeout. Please refresh the page.'
            ]
        ];

        $isRead = $this->faker->boolean(60); // 60% chance of being read

        return [
            'school_id' => $this->faker->optional(0.8)->randomElement(\App\Models\School::pluck('id')->toArray() ?: [1]),
            'user_id' => $this->faker->optional(0.9)->randomElement(\App\Models\User::pluck('id')->toArray() ?: [1]),
            'title' => $this->faker->randomElement($titles[$type]),
            'message' => $this->faker->randomElement($messages[$type]),
            'type' => $type,
            'data' => $this->faker->optional(0.3)->randomElement([
                ['assignment_id' => $this->faker->numberBetween(1, 100)],
                ['class_id' => $this->faker->numberBetween(1, 50)],
                ['grade_id' => $this->faker->numberBetween(1, 200)]
            ]),
            'is_read' => $isRead,
            'read_at' => $isRead ? $this->faker->dateTimeBetween('-30 days', 'now') : null,
            'action_url' => $this->faker->optional(0.4)->url,
            'icon' => $this->faker->optional(0.3)->randomElement([
                'fas fa-bell',
                'fas fa-info-circle',
                'fas fa-exclamation-triangle',
                'fas fa-check-circle'
            ]),
        ];
    }
}
