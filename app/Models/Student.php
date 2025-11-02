<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'class_id',
        'parent_id',
        'student_id',
        'first_name',
        'last_name',
        'middle_name',
        'phone',
        'email',
        'address',
        'date_of_birth',
        'gender',
        'blood_group',
        'emergency_contact',
        'emergency_phone',
        'photo',
        'status',
        'admission_date',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
    ];

    protected $appends = [
        'full_name',
        'age',
        'photo_url',
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

    public function classModel()
    {
        return $this->belongsTo(ClassModel::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subjects');
    }

    public function parents()
    {
        return $this->belongsToMany(User::class, 'student_parents', 'student_id', 'parent_id')
                   ->where('user_type', 'parent');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        $name = trim($this->first_name . ' ' . $this->last_name);
        return $name ?: 'Unknown Student';
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

    public function getDisplayNameAttribute(): string
    {
        return $this->full_name . ' (' . $this->student_id . ')';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => '<span class="badge badge-success">Active</span>',
            'inactive' => '<span class="badge badge-secondary">Inactive</span>',
            'graduated' => '<span class="badge badge-primary">Graduated</span>',
            'transferred' => '<span class="badge badge-warning">Transferred</span>',
            default => '<span class="badge badge-light">Unknown</span>'
        };
    }

    /**
     * Get student's academic information
     */
    public function getAcademicInfo(): array
    {
        return [
            'student_id' => $this->student_id,
            'class' => $this->classModel?->name,
            'admission_date' => $this->admission_date?->format('Y-m-d'),
            'status' => $this->status,
            'total_subjects' => $this->subjects()->count(),
        ];
    }

    /**
     * Get attendance statistics
     */
    public function getAttendanceStatistics(): array
    {
        $totalDays = $this->attendance()->count();
        $presentDays = $this->attendance()->where('status', 'present')->count();
        $absentDays = $this->attendance()->where('status', 'absent')->count();
        $lateDays = $this->attendance()->where('status', 'late')->count();
        
        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;
        
        return [
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'late_days' => $lateDays,
            'attendance_percentage' => $attendancePercentage,
        ];
    }

    /**
     * Get grade statistics
     */
    public function getGradeStatistics(): array
    {
        $grades = $this->grades();
        $totalGrades = $grades->count();
        
        if ($totalGrades === 0) {
            return [
                'total_grades' => 0,
                'average_grade' => 0,
                'highest_grade' => 0,
                'lowest_grade' => 0,
                'grade_distribution' => [],
            ];
        }

        $averageGrade = $grades->avg('marks_obtained');
        $highestGrade = $grades->max('marks_obtained');
        $lowestGrade = $grades->min('marks_obtained');
        
        return [
            'total_grades' => $totalGrades,
            'average_grade' => round($averageGrade, 2),
            'highest_grade' => $highestGrade,
            'lowest_grade' => $lowestGrade,
            'grade_distribution' => $this->getGradeDistribution(),
        ];
    }

    /**
     * Get grade distribution by subject
     */
    private function getGradeDistribution(): array
    {
        return $this->grades()
                   ->with('subject')
                   ->get()
                   ->groupBy('subject.name')
                   ->map(function($grades) {
                       return [
                           'count' => $grades->count(),
                           'average' => round($grades->avg('marks_obtained'), 2),
                           'latest' => $grades->sortByDesc('created_at')->first()->marks_obtained ?? 0,
                       ];
                   })
                   ->toArray();
    }

    /**
     * Get recent attendance (last 30 days)
     */
    public function getRecentAttendance(int $days = 30): array
    {
        return $this->attendance()
                   ->where('date', '>=', Carbon::now()->subDays($days))
                   ->orderBy('date', 'desc')
                   ->get()
                   ->map(function($attendance) {
                       return [
                           'date' => $attendance->date->format('Y-m-d'),
                           'status' => $attendance->status,
                           'remarks' => $attendance->remarks,
                       ];
                   })
                   ->toArray();
    }

    /**
     * Get recent grades (last 10)
     */
    public function getRecentGrades(int $limit = 10): array
    {
        return $this->grades()
                   ->with(['subject', 'teacher'])
                   ->orderBy('created_at', 'desc')
                   ->limit($limit)
                   ->get()
                   ->map(function($grade) {
                       return [
                           'subject' => $grade->subject->name ?? 'Unknown',
                           'marks_obtained' => $grade->marks_obtained,
                           'total_marks' => $grade->total_marks,
                           'percentage' => round(($grade->marks_obtained / $grade->total_marks) * 100, 2),
                           'grade' => $grade->grade,
                           'exam_type' => $grade->exam_type,
                           'exam_date' => $grade->exam_date?->format('Y-m-d'),
                           'teacher' => $grade->teacher->full_name ?? 'Unknown',
                       ];
                   })
                   ->toArray();
    }

    /**
     * Check if student is currently active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get emergency contact information
     */
    public function getEmergencyContactInfo(): array
    {
        return [
            'contact_name' => $this->emergency_contact,
            'contact_phone' => $this->emergency_phone,
            'parent_name' => $this->parent?->full_name,
            'parent_phone' => $this->parent?->phone,
            'parent_email' => $this->parent?->email,
        ];
    }

    /**
     * Get student's current academic status
     */
    public function getAcademicStatus(): array
    {
        $attendanceStats = $this->getAttendanceStatistics();
        $gradeStats = $this->getGradeStatistics();
        
        return [
            'overall_performance' => $this->calculateOverallPerformance($attendanceStats, $gradeStats),
            'attendance_status' => $this->getAttendanceStatus($attendanceStats['attendance_percentage']),
            'grade_status' => $this->getGradeStatus($gradeStats['average_grade']),
            'needs_attention' => $this->needsAttention($attendanceStats, $gradeStats),
        ];
    }

    /**
     * Calculate overall performance score
     */
    private function calculateOverallPerformance(array $attendanceStats, array $gradeStats): string
    {
        $attendanceScore = $attendanceStats['attendance_percentage'];
        $gradeScore = $gradeStats['average_grade'];
        
        if ($gradeStats['total_grades'] === 0) {
            $overallScore = $attendanceScore;
        } else {
            // Weight: 40% attendance, 60% grades
            $overallScore = ($attendanceScore * 0.4) + ($gradeScore * 0.6);
        }
        
        return match(true) {
            $overallScore >= 90 => 'Excellent',
            $overallScore >= 80 => 'Good',
            $overallScore >= 70 => 'Average',
            $overallScore >= 60 => 'Below Average',
            default => 'Poor'
        };
    }

    /**
     * Get attendance status
     */
    private function getAttendanceStatus(float $percentage): string
    {
        return match(true) {
            $percentage >= 95 => 'Excellent',
            $percentage >= 85 => 'Good',
            $percentage >= 75 => 'Average',
            $percentage >= 65 => 'Below Average',
            default => 'Poor'
        };
    }

    /**
     * Get grade status
     */
    private function getGradeStatus(float $average): string
    {
        return match(true) {
            $average >= 90 => 'Excellent',
            $average >= 80 => 'Good',
            $average >= 70 => 'Average',
            $average >= 60 => 'Below Average',
            default => 'Poor'
        };
    }

    /**
     * Check if student needs attention
     */
    private function needsAttention(array $attendanceStats, array $gradeStats): bool
    {
        return $attendanceStats['attendance_percentage'] < 75 || 
               $gradeStats['average_grade'] < 60 ||
               $attendanceStats['absent_days'] > 10;
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByClass($query, int $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeBySchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByGender($query, string $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeAdmittedAfter($query, Carbon $date)
    {
        return $query->where('admission_date', '>=', $date);
    }

    public function scopeWithLowAttendance($query, float $threshold = 75.0)
    {
        return $query->whereHas('attendance', function($q) use ($threshold) {
            $q->selectRaw('student_id, (COUNT(CASE WHEN status = "present" THEN 1 END) * 100.0 / COUNT(*)) as attendance_percentage')
              ->groupBy('student_id')
              ->havingRaw('attendance_percentage < ?', [$threshold]);
        });
    }
}
