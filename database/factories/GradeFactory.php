<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Grade>
 */
class GradeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $examTypes = ['Quiz', 'Midterm', 'Final', 'Assignment', 'Project', 'Test'];
        $totalMarks = $this->faker->randomElement([50, 100, 200]);
        $marksObtained = $this->faker->numberBetween(0, $totalMarks);
        $percentage = ($marksObtained / $totalMarks) * 100;
        
        // Determine grade letter based on percentage
        $gradeLetter = match(true) {
            $percentage >= 90 => 'A',
            $percentage >= 80 => 'B',
            $percentage >= 70 => 'C',
            $percentage >= 60 => 'D',
            default => 'F'
        };

        $remarks = null;
        if ($percentage >= 90) {
            $remarks = $this->faker->optional(0.3)->randomElement(['Excellent work!', 'Outstanding performance!', 'Keep it up!']);
        } elseif ($percentage < 60) {
            $remarks = $this->faker->optional(0.7)->randomElement(['Needs improvement', 'Please see me after class', 'Consider extra help']);
        }

        return [
            'school_id' => \App\Models\School::factory(),
            'student_id' => \App\Models\Student::factory(),
            'subject_id' => \App\Models\Subject::factory(),
            'teacher_id' => \App\Models\Teacher::factory(),
            'exam_type' => $this->faker->randomElement($examTypes),
            'marks_obtained' => $marksObtained,
            'total_marks' => $totalMarks,
            'percentage' => round($percentage, 2),
            'grade_letter' => $gradeLetter,
            'remarks' => $remarks,
            'exam_date' => $this->faker->dateTimeBetween('-60 days', 'now'),
        ];
    }
}
