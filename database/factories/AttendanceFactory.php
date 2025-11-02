<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['present', 'absent', 'late', 'excused'];
        $status = $this->faker->randomElement($statuses, [70, 15, 10, 5]); // 70% present, 15% absent, etc.
        
        $date = $this->faker->dateTimeBetween('-30 days', 'now');
        $checkInTime = null;
        $checkOutTime = null;
        $remarks = null;

        if ($status === 'present' || $status === 'late') {
            $baseTime = $status === 'late' ? '08:30' : '08:00';
            $checkInTime = $this->faker->time('H:i', $baseTime);
            $checkOutTime = $this->faker->time('H:i', '15:30');
        }

        if ($status === 'absent') {
            $remarks = $this->faker->optional(0.6)->randomElement([
                'Sick',
                'Family emergency',
                'Medical appointment',
                'Personal reasons',
                'Weather conditions'
            ]);
        }

        if ($status === 'excused') {
            $remarks = $this->faker->randomElement([
                'School field trip',
                'Medical appointment',
                'Family emergency',
                'Religious observance',
                'Court appearance'
            ]);
        }

        return [
            'school_id' => \App\Models\School::factory(),
            'student_id' => \App\Models\Student::factory(),
            'class_id' => \App\Models\ClassModel::factory(),
            'date' => $date,
            'status' => $status,
            'check_in_time' => $checkInTime,
            'check_out_time' => $checkOutTime,
            'remarks' => $remarks,
        ];
    }
}
