<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarksheetMark extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'marksheet_id',
        'obtained_marks'
    ];

    protected $appends = ['grade'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function marksheet()
    {
        return $this->belongsTo(Marksheet::class);
    }

    public function getGradeAttribute()
    {
        $max = $this->subject && $this->subject->max_marks ? (float) $this->subject->max_marks : 0.0;
        $obt = (float) $this->obtained_marks;
        $percentage = $max > 0 ? ($obt / $max) * 100 : 0;
        
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
        $percentage = ($this->obtained_marks / $this->subject->max_marks) * 100;
        $passPercentage = \App\Models\SystemSetting::get('pass_percentage', 33);
        
        return $percentage >= $passPercentage;
    }
}
