<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarkingScheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'configuration',
        'description',
        'is_active'
    ];

    protected $casts = [
        'configuration' => 'array',
        'is_active' => 'boolean'
    ];

    public static function getActiveScheme()
    {
        return self::where('is_active', true)->first();
    }

    public function calculateGrade($obtainedMarks, $totalMarks)
    {
        $percentage = ($obtainedMarks / $totalMarks) * 100;
        
        switch ($this->type) {
            case 'percentage':
                return $this->calculatePercentageGrade($percentage);
            case 'points':
                return $this->calculatePointsGrade($obtainedMarks, $totalMarks);
            case 'letter':
                return $this->calculateLetterGrade($percentage);
            default:
                return $this->calculatePercentageGrade($percentage);
        }
    }

    private function calculatePercentageGrade($percentage)
    {
        $grades = $this->configuration['grades'] ?? [];
        
        foreach ($grades as $grade) {
            if ($percentage >= $grade['min'] && $percentage <= $grade['max']) {
                return $grade['grade'];
            }
        }
        
        return 'F';
    }

    private function calculatePointsGrade($obtainedMarks, $totalMarks)
    {
        // Implementation for points-based grading
        $percentage = ($obtainedMarks / $totalMarks) * 100;
        return $this->calculatePercentageGrade($percentage);
    }

    private function calculateLetterGrade($percentage)
    {
        // Implementation for letter-based grading
        return $this->calculatePercentageGrade($percentage);
    }
}
