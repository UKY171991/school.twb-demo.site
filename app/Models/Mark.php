<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'exam_type_id',
        'mark_obtained',
        'total_marks',
        'exam_type',
        'exam_date'
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function examType()
    {
        return $this->belongsTo(ExamType::class);
    }

    public function getPercentageAttribute()
    {
        return $this->total_marks > 0 ? ($this->mark_obtained / $this->total_marks) * 100 : 0;
    }

    public function getGradeAttribute()
    {
        $percentage = $this->percentage;
        
        // Use configurable grading system
        $gradingSystem = \App\Models\GradingSystem::getGradeForPercentage($percentage);
        
        if ($gradingSystem) {
            return $gradingSystem->grade;
        }
        
        // Fallback to default grading if no system configured
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        if ($percentage >= 33) return 'D';
        return 'F';
    }

    public function isPassed()
    {
        $percentage = $this->percentage;
        $passPercentage = \App\Models\SystemSetting::get('pass_percentage', 33);
        
        return $percentage >= $passPercentage;
    }
}