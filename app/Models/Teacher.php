<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'employee_id',
        'first_name',
        'last_name',
        'middle_name',
        'phone',
        'email',
        'address',
        'date_of_birth',
        'gender',
        'qualification',
        'experience',
        'salary',
        'joining_date',
        'photo',
        'is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'full_name',
        'age',
        'photo_url',
        'years_of_service',
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, ClassModel::class);
    }

    public function schedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        $name = trim($this->first_name . ' ' . $this->last_name);
        return $name ?: 'Unknown Teacher';
    }

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : 0;
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        
        return asset('vendor/adminlte/dist/img/user2-160x160.jpg');
    }

    public function getYearsOfServiceAttribute(): float
    {
        if (!$this->joining_date) {
            return 0;
        }
        
        return round($this->joining_date->diffInYears(Carbon::now(), true), 1);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->full_name . ' (' . $this->employee_id . ')';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->is_active) {
            true => '<span class="badge badge-success">Active</span>',
            false => '<span class="badge badge-secondary">Inactive</span>',
        };
    }

    /**
     * Get teacher's professional information
     */
    public function getProfessionalInfo(): array
    {
        return [
            'employee_id' => $this->employee_id,
            'qualification' => $this->qualification,
            'experience' => $this->experience,
            'years_of_service' => $this->years_of_service,
            'joining_date' => $this->joining_date?->format('Y-m-d'),
            'salary' => $this->salary,
            'is_active' => $this->is_active,
        ];
    }

    /**
     * Get teaching statistics
     */
    public function getTeachingStatistics(): array
    {
        $totalClasses = $this->classes()->count();
        $totalStudents = $this->students()->count();
        $totalSubjects = $this->subjects()->count();
        $totalGrades = $this->grades()->count();
        
        return [
            'total_classes' => $totalClasses,
            'total_students' => $totalStudents,
            'total_subjects' => $totalSubjects,
            'total_grades_entered' => $totalGrades,
            'average_class_size' => $totalClasses > 0 ? round($totalStudents / $totalClasses, 2) : 0,
        ];
    }

    /**
     * Get current workload
     */
    public function getCurrentWorkload(): array
    {
        $activeClasses = $this->classes()->where('is_active', true)->count();
        $activeSubjects = $this->subjects()->where('is_active', true)->count();
        $activeStudents = $this->students()->where('status', 'active')->count();
        
        return [
            'active_classes' => $activeClasses,
            'active_subjects' => $activeSubjects,
            'active_students' => $activeStudents,
            'workload_score' => $this->calculateWorkloadScore($activeClasses, $activeStudents),
        ];
    }

    /**
     * Calculate workload score (0-100)
     */
    private function calculateWorkloadScore(int $classes, int $students): int
    {
        // Basic formula: classes * 10 + students * 0.5
        // This can be adjusted based on school's standards
        $score = ($classes * 10) + ($students * 0.5);
        return min(100, (int)$score);
    }

    /**
     * Get recent grades entered (last 30 days)
     */
    public function getRecentGrades(int $days = 30): array
    {
        return $this->grades()
                   ->with(['student', 'subject'])
                   ->where('created_at', '>=', Carbon::now()->subDays($days))
                   ->orderBy('created_at', 'desc')
                   ->get()
                   ->map(function($grade) {
                       return [
                           'student' => $grade->student->full_name ?? 'Unknown',
                           'subject' => $grade->subject->name ?? 'Unknown',
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
     * Get class performance summary
     */
    public function getClassPerformanceSummary(): array
    {
        $classes = $this->classes()->with(['students', 'grades'])->get();
        
        return $classes->map(function($class) {
            $students = $class->students;
            $grades = $class->grades()->where('teacher_id', $this->id)->get();
            
            $averageGrade = $grades->avg('marks_obtained');
            $passRate = $grades->where('marks_obtained', '>=', 60)->count() / max($grades->count(), 1) * 100;
            
            return [
                'class_name' => $class->name,
                'total_students' => $students->count(),
                'grades_entered' => $grades->count(),
                'average_grade' => round($averageGrade, 2),
                'pass_rate' => round($passRate, 2),
            ];
        })->toArray();
    }

    /**
     * Get attendance marking statistics
     */
    public function getAttendanceStatistics(): array
    {
        // This would require attendance records to be linked to teachers
        // For now, return placeholder data
        return [
            'days_marked' => 0,
            'total_attendance_records' => 0,
            'last_marked_date' => null,
        ];
    }

    /**
     * Get teacher's schedule for today
     */
    public function getTodaySchedule(): array
    {
        // This would require a schedule/timetable system
        // For now, return classes as schedule
        return $this->classes()
                   ->where('is_active', true)
                   ->with('subject')
                   ->get()
                   ->map(function($class) {
                       return [
                           'class_name' => $class->name,
                           'subject' => $class->subject->name ?? 'General',
                           'room' => $class->room_number,
                           'students_count' => $class->students()->count(),
                       ];
                   })
                   ->toArray();
    }

    /**
     * Check if teacher is currently active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get contact information
     */
    public function getContactInfo(): array
    {
        return [
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
        ];
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics(): array
    {
        $stats = $this->getTeachingStatistics();
        $workload = $this->getCurrentWorkload();
        
        return [
            'efficiency_score' => $this->calculateEfficiencyScore($stats),
            'workload_balance' => $this->getWorkloadBalance($workload['workload_score']),
            'student_engagement' => $this->calculateStudentEngagement(),
            'grade_consistency' => $this->calculateGradeConsistency(),
        ];
    }

    /**
     * Calculate efficiency score based on teaching statistics
     */
    private function calculateEfficiencyScore(array $stats): int
    {
        // Simple formula based on grades entered vs students
        if ($stats['total_students'] === 0) {
            return 0;
        }
        
        $gradeRatio = $stats['total_grades_entered'] / $stats['total_students'];
        return min(100, (int)($gradeRatio * 20)); // Assuming 5 grades per student is 100%
    }

    /**
     * Get workload balance status
     */
    private function getWorkloadBalance(int $score): string
    {
        return match(true) {
            $score <= 30 => 'Light',
            $score <= 60 => 'Moderate',
            $score <= 80 => 'Heavy',
            default => 'Overloaded'
        };
    }

    /**
     * Calculate student engagement (placeholder)
     */
    private function calculateStudentEngagement(): int
    {
        // This would require more complex metrics
        // For now, return a placeholder based on class sizes
        $avgClassSize = $this->getTeachingStatistics()['average_class_size'];
        return max(0, min(100, (int)(100 - ($avgClassSize * 2))));
    }

    /**
     * Calculate grade consistency
     */
    private function calculateGradeConsistency(): int
    {
        $grades = $this->grades()->pluck('marks_obtained');
        
        if ($grades->count() < 2) {
            return 100;
        }
        
        $standardDeviation = $this->calculateStandardDeviation($grades->toArray());
        $mean = $grades->avg();
        
        if ($mean == 0) {
            return 100;
        }
        
        $coefficientOfVariation = ($standardDeviation / $mean) * 100;
        return max(0, min(100, (int)(100 - $coefficientOfVariation)));
    }

    /**
     * Calculate standard deviation
     */
    private function calculateStandardDeviation(array $values): float
    {
        $count = count($values);
        if ($count === 0) {
            return 0;
        }
        
        $mean = array_sum($values) / $count;
        $variance = array_sum(array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $values)) / $count;
        
        return sqrt($variance);
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

    public function scopeByExperience($query, int $minYears)
    {
        return $query->where('experience', '>=', $minYears);
    }

    public function scopeJoinedAfter($query, Carbon $date)
    {
        return $query->where('joining_date', '>=', $date);
    }

    public function scopeWithHighWorkload($query, int $threshold = 80)
    {
        return $query->whereHas('classes', function($q) use ($threshold) {
            $q->selectRaw('teacher_id, COUNT(*) * 10 + (SELECT COUNT(*) FROM students WHERE class_id = classes.id) * 0.5 as workload_score')
              ->groupBy('teacher_id')
              ->havingRaw('workload_score > ?', [$threshold]);
        });
    }
}
