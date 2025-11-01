<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'website',
        'description',
        'logo',
        'principal_name',
        'principal_phone',
        'principal_email',
        'is_active',
        'configuration',
        'established_date',
        'timezone',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'configuration' => 'array',
        'established_date' => 'date',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function parents()
    {
        return $this->hasMany(ParentModel::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function notifications()
    {
        return $this->hasMany(SystemNotification::class);
    }

    public function configurations()
    {
        return $this->hasMany(SchoolConfiguration::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get school statistics
     */
    public function getStatistics(): array
    {
        return [
            'total_students' => $this->getActiveStudentsCount(),
            'total_teachers' => $this->getActiveTeachersCount(),
            'total_classes' => $this->classes()->where('is_active', true)->count(),
            'total_subjects' => $this->subjects()->where('is_active', true)->count(),
            'total_parents' => $this->parents()->count(),
            'present_today' => $this->getTodayAttendanceCount('present'),
            'absent_today' => $this->getTodayAttendanceCount('absent'),
            'recent_enrollments' => $this->getRecentEnrollmentsCount(),
        ];
    }

    /**
     * Get active students count
     */
    public function getActiveStudentsCount(): int
    {
        return $this->students()->where('status', 'active')->count();
    }

    /**
     * Get active teachers count
     */
    public function getActiveTeachersCount(): int
    {
        return $this->teachers()->where('is_active', true)->count();
    }

    /**
     * Get today's attendance count by status
     */
    public function getTodayAttendanceCount(string $status): int
    {
        return $this->attendance()
                   ->where('date', Carbon::today())
                   ->where('status', $status)
                   ->count();
    }

    /**
     * Get recent enrollments count (last 30 days)
     */
    public function getRecentEnrollmentsCount(int $days = 30): int
    {
        return $this->students()
                   ->where('created_at', '>=', Carbon::now()->subDays($days))
                   ->count();
    }

    /**
     * Get school configuration
     */
    public function getConfiguration(): array
    {
        return $this->configuration ?? [];
    }

    /**
     * Get configuration value
     */
    public function getConfigValue(string $key, $default = null)
    {
        return data_get($this->configuration, $key, $default);
    }

    /**
     * Set configuration value
     */
    public function setConfigValue(string $key, $value): bool
    {
        $config = $this->configuration ?? [];
        data_set($config, $key, $value);
        
        return $this->update(['configuration' => $config]);
    }

    /**
     * Get school logo URL
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        
        return asset('vendor/adminlte/dist/img/AdminLTELogo.png');
    }

    /**
     * Get school's full address
     */
    public function getFullAddressAttribute(): string
    {
        return $this->address ?? 'Address not provided';
    }

    /**
     * Get school's contact information
     */
    public function getContactInfoAttribute(): array
    {
        return [
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'principal_name' => $this->principal_name,
            'principal_phone' => $this->principal_phone,
            'principal_email' => $this->principal_email,
        ];
    }

    /**
     * Get academic year configuration
     */
    public function getAcademicYear(): array
    {
        return $this->getConfigValue('academic_year', [
            'start_date' => Carbon::now()->startOfYear(),
            'end_date' => Carbon::now()->endOfYear(),
            'current_semester' => 1,
        ]);
    }

    /**
     * Get school working days
     */
    public function getWorkingDays(): array
    {
        return $this->getConfigValue('working_days', [
            'monday' => true,
            'tuesday' => true,
            'wednesday' => true,
            'thursday' => true,
            'friday' => true,
            'saturday' => false,
            'sunday' => false,
        ]);
    }

    /**
     * Get school timings
     */
    public function getSchoolTimings(): array
    {
        return $this->getConfigValue('school_timings', [
            'start_time' => '08:00',
            'end_time' => '15:00',
            'break_start' => '10:30',
            'break_end' => '11:00',
            'lunch_start' => '12:30',
            'lunch_end' => '13:30',
        ]);
    }

    /**
     * Check if school is currently open
     */
    public function isCurrentlyOpen(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();
        $dayName = strtolower($now->format('l'));
        $workingDays = $this->getWorkingDays();
        
        if (!($workingDays[$dayName] ?? false)) {
            return false;
        }

        $timings = $this->getSchoolTimings();
        $startTime = Carbon::createFromFormat('H:i', $timings['start_time']);
        $endTime = Carbon::createFromFormat('H:i', $timings['end_time']);
        
        return $now->between($startTime, $endTime);
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics(): array
    {
        $totalStudents = $this->getActiveStudentsCount();
        $totalTeachers = $this->getActiveTeachersCount();
        
        if ($totalStudents === 0 || $totalTeachers === 0) {
            return [
                'student_teacher_ratio' => 0,
                'average_class_size' => 0,
                'attendance_rate' => 0,
                'pass_rate' => 0,
            ];
        }

        $totalClasses = $this->classes()->where('is_active', true)->count();
        $attendanceRate = $this->calculateAttendanceRate();
        
        return [
            'student_teacher_ratio' => round($totalStudents / $totalTeachers, 2),
            'average_class_size' => $totalClasses > 0 ? round($totalStudents / $totalClasses, 2) : 0,
            'attendance_rate' => $attendanceRate,
            'pass_rate' => $this->calculatePassRate(),
        ];
    }

    /**
     * Calculate overall attendance rate
     */
    private function calculateAttendanceRate(): float
    {
        $totalAttendance = $this->attendance()->count();
        if ($totalAttendance === 0) {
            return 0;
        }

        $presentCount = $this->attendance()->where('status', 'present')->count();
        return round(($presentCount / $totalAttendance) * 100, 2);
    }

    /**
     * Calculate pass rate (placeholder - would need grade thresholds)
     */
    private function calculatePassRate(): float
    {
        // This would need to be implemented based on grading system
        // For now, return a placeholder
        return 85.5;
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCode($query, string $code)
    {
        return $query->where('code', $code);
    }

    public function scopeEstablishedAfter($query, Carbon $date)
    {
        return $query->where('established_date', '>=', $date);
    }

    public function scopeWithStudentCount($query)
    {
        return $query->withCount(['students' => function($q) {
            $q->where('status', 'active');
        }]);
    }

    public function scopeWithTeacherCount($query)
    {
        return $query->withCount(['teachers' => function($q) {
            $q->where('is_active', true);
        }]);
    }
}
