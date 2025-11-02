<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'student_id',
        'subject_id',
        'teacher_id',
        'exam_type',
        'marks_obtained',
        'total_marks',
        'percentage',
        'grade_letter',
        'remarks',
        'exam_date',
    ];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'total_marks' => 'decimal:2',
        'percentage' => 'decimal:2',
        'exam_date' => 'date',
    ];

    protected $appends = [
        'calculated_percentage',
        'grade_letter',
        'status_badge',
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    // Accessors
    public function getCalculatedPercentageAttribute(): float
    {
        if ($this->total_marks > 0) {
            return round(($this->marks_obtained / $this->total_marks) * 100, 2);
        }
        return 0;
    }

    public function getGradeLetterAttribute(): string
    {
        $percentage = $this->calculated_percentage;
        
        return match(true) {
            $percentage >= 90 => 'A+',
            $percentage >= 85 => 'A',
            $percentage >= 80 => 'A-',
            $percentage >= 75 => 'B+',
            $percentage >= 70 => 'B',
            $percentage >= 65 => 'B-',
            $percentage >= 60 => 'C+',
            $percentage >= 55 => 'C',
            $percentage >= 50 => 'C-',
            $percentage >= 45 => 'D',
            default => 'F'
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        $percentage = $this->calculated_percentage;
        
        return match(true) {
            $percentage >= 90 => '<span class="badge badge-success">Excellent</span>',
            $percentage >= 80 => '<span class="badge badge-info">Good</span>',
            $percentage >= 70 => '<span class="badge badge-primary">Average</span>',
            $percentage >= 60 => '<span class="badge badge-warning">Below Average</span>',
            default => '<span class="badge badge-danger">Poor</span>'
        };
    }

    public function getExamTypeBadgeAttribute(): string
    {
        return match($this->exam_type) {
            'quiz' => '<span class="badge badge-light">Quiz</span>',
            'midterm' => '<span class="badge badge-primary">Midterm</span>',
            'final' => '<span class="badge badge-danger">Final</span>',
            'assignment' => '<span class="badge badge-info">Assignment</span>',
            'project' => '<span class="badge badge-success">Project</span>',
            default => '<span class="badge badge-secondary">Other</span>'
        };
    }

    /**
     * Check if grade is passing
     */
    public function isPassing(): bool
    {
        return $this->calculated_percentage >= 60;
    }

    /**
     * Get grade performance level
     */
    public function getPerformanceLevel(): string
    {
        $percentage = $this->calculated_percentage;
        
        return match(true) {
            $percentage >= 90 => 'Excellent',
            $percentage >= 80 => 'Good',
            $percentage >= 70 => 'Average',
            $percentage >= 60 => 'Below Average',
            default => 'Poor'
        };
    }

    /**
     * Get grade color for charts
     */
    public function getGradeColor(): string
    {
        $percentage = $this->calculated_percentage;
        
        return match(true) {
            $percentage >= 90 => '#28a745', // Green
            $percentage >= 80 => '#17a2b8', // Teal
            $percentage >= 70 => '#007bff', // Blue
            $percentage >= 60 => '#ffc107', // Yellow
            default => '#dc3545' // Red
        };
    }

    /**
     * Scopes
     */
    public function scopeByTeacher($query, int $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeByStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeBySubject($query, int $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByClass($query, int $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeByExamType($query, string $examType)
    {
        return $query->where('exam_type', $examType);
    }

    public function scopePassing($query)
    {
        return $query->whereRaw('(marks_obtained / total_marks) * 100 >= 60');
    }

    public function scopeFailing($query)
    {
        return $query->whereRaw('(marks_obtained / total_marks) * 100 < 60');
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
