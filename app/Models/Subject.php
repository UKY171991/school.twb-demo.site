<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'teacher_id',
        'name',
        'code',
        'description',
        'credits',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credits' => 'integer',
    ];

    protected $appends = [
        'display_name',
        'grade_count',
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'class_subjects');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subjects');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    // Accessors
    public function getDisplayNameAttribute(): string
    {
        return $this->name . ($this->code ? ' (' . $this->code . ')' : '');
    }

    public function getGradeCountAttribute(): int
    {
        return $this->grades()->count();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->is_active) {
            true => '<span class="badge badge-success">Active</span>',
            false => '<span class="badge badge-secondary">Inactive</span>',
        };
    }

    public function getTypeBadgeAttribute(): string
    {
        return match($this->type) {
            'core' => '<span class="badge badge-primary">Core</span>',
            'elective' => '<span class="badge badge-info">Elective</span>',
            'optional' => '<span class="badge badge-secondary">Optional</span>',
            default => '<span class="badge badge-light">General</span>',
        };
    }

    /**
     * Get subject statistics
     */
    public function getStatistics(): array
    {
        $totalGrades = $this->grades()->count();
        $totalStudents = $this->students()->count();
        $totalClasses = $this->classes()->count();
        
        return [
            'total_students' => $totalStudents,
            'total_classes' => $totalClasses,
            'total_grades' => $totalGrades,
            'credits' => $this->credits,
            'type' => $this->type,
            'has_teacher' => !is_null($this->teacher_id),
        ];
    }

    /**
     * Get academic performance for this subject
     */
    public function getAcademicPerformance(): array
    {
        $grades = $this->grades();
        $totalGrades = $grades->count();
        
        if ($totalGrades === 0) {
            return [
                'average_grade' => 0,
                'highest_grade' => 0,
                'lowest_grade' => 0,
                'pass_rate' => 0,
                'grade_distribution' => [],
            ];
        }

        $averageGrade = $grades->avg('marks_obtained');
        $highestGrade = $grades->max('marks_obtained');
        $lowestGrade = $grades->min('marks_obtained');
        $passCount = $grades->where('marks_obtained', '>=', 60)->count();
        $passRate = ($passCount / $totalGrades) * 100;

        // Grade distribution
        $gradeRanges = [
            'A (90-100)' => $grades->whereBetween('marks_obtained', [90, 100])->count(),
            'B (80-89)' => $grades->whereBetween('marks_obtained', [80, 89])->count(),
            'C (70-79)' => $grades->whereBetween('marks_obtained', [70, 79])->count(),
            'D (60-69)' => $grades->whereBetween('marks_obtained', [60, 69])->count(),
            'F (0-59)' => $grades->where('marks_obtained', '<', 60)->count(),
        ];

        return [
            'average_grade' => round($averageGrade, 2),
            'highest_grade' => $highestGrade,
            'lowest_grade' => $lowestGrade,
            'pass_rate' => round($passRate, 2),
            'grade_distribution' => $gradeRanges,
        ];
    }

    /**
     * Get recent grades (last 30 days)
     */
    public function getRecentGrades(int $days = 30): array
    {
        return $this->grades()
                   ->with(['student'])
                   ->where('created_at', '>=', now()->subDays($days))
                   ->orderBy('created_at', 'desc')
                   ->get()
                   ->map(function($grade) {
                       return [
                           'student_name' => $grade->student->full_name ?? 'Unknown',
                           'marks_obtained' => $grade->marks_obtained,
                           'total_marks' => $grade->total_marks,
                           'percentage' => round(($grade->marks_obtained / $grade->total_marks) * 100, 2),
                           'exam_type' => $grade->exam_type,
                           'exam_date' => $grade->exam_date?->format('Y-m-d'),
                           'created_at' => $grade->created_at->format('Y-m-d H:i'),
                       ];
                   })
                   ->toArray();
    }

    /**
     * Get curriculum information
     */
    public function getCurriculumInfo(): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'credits' => $this->credits,
            'type' => $this->type,
            'teacher' => $this->teacher?->full_name,
            'classes_count' => $this->classes()->count(),
            'students_count' => $this->students()->count(),
        ];
    }

    /**
     * Check if subject is assigned to any classes
     */
    public function hasClassAssignments(): bool
    {
        return $this->classes()->exists();
    }

    /**
     * Check if subject has enrolled students
     */
    public function hasStudentEnrollments(): bool
    {
        return $this->students()->exists();
    }

    /**
     * Get workload for this subject
     */
    public function getWorkload(): array
    {
        $classCount = $this->classes()->count();
        $studentCount = $this->students()->count();
        $gradeCount = $this->grades()->count();
        
        // Simple workload calculation
        $workloadScore = ($classCount * 10) + ($studentCount * 0.5) + ($gradeCount * 0.1);
        
        return [
            'classes' => $classCount,
            'students' => $studentCount,
            'grades_entered' => $gradeCount,
            'workload_score' => round($workloadScore, 2),
            'workload_level' => $this->getWorkloadLevel($workloadScore),
        ];
    }

    /**
     * Get workload level description
     */
    private function getWorkloadLevel(float $score): string
    {
        return match(true) {
            $score <= 20 => 'Light',
            $score <= 50 => 'Moderate',
            $score <= 80 => 'Heavy',
            default => 'Very Heavy'
        };
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBySchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeWithTeacher($query)
    {
        return $query->whereNotNull('teacher_id');
    }

    public function scopeWithoutTeacher($query)
    {
        return $query->whereNull('teacher_id');
    }

    public function scopeByCredits($query, int $credits)
    {
        return $query->where('credits', $credits);
    }

    public function scopeCore($query)
    {
        return $query->where('type', 'core');
    }

    public function scopeElective($query)
    {
        return $query->where('type', 'elective');
    }
}
