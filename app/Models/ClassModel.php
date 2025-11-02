<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'school_id',
        'teacher_id',
        'name',
        'section',
        'capacity',
        'room_number',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'full_name',
        'student_count',
        'capacity_percentage',
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

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subjects');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function schedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->name . ($this->section ? ' - ' . $this->section : '');
    }

    public function getStudentCountAttribute(): int
    {
        return $this->students()->where('status', 'active')->count();
    }

    public function getCapacityPercentageAttribute(): float
    {
        if (!$this->capacity || $this->capacity == 0) {
            return 0;
        }
        return round(($this->student_count / $this->capacity) * 100, 2);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->is_active) {
            true => '<span class="badge badge-success">Active</span>',
            false => '<span class="badge badge-secondary">Inactive</span>',
        };
    }

    /**
     * Get class statistics
     */
    public function getStatistics(): array
    {
        $totalStudents = $this->students()->count();
        $activeStudents = $this->students()->where('status', 'active')->count();
        $maleStudents = $this->students()->where('gender', 'male')->count();
        $femaleStudents = $this->students()->where('gender', 'female')->count();
        
        return [
            'total_students' => $totalStudents,
            'active_students' => $activeStudents,
            'male_students' => $maleStudents,
            'female_students' => $femaleStudents,
            'capacity' => $this->capacity,
            'capacity_used' => $this->capacity_percentage,
            'available_seats' => max(0, $this->capacity - $activeStudents),
        ];
    }

    /**
     * Get academic performance
     */
    public function getAcademicPerformance(): array
    {
        $grades = $this->grades();
        $totalGrades = $grades->count();
        
        if ($totalGrades === 0) {
            return [
                'average_grade' => 0,
                'pass_rate' => 0,
                'total_assessments' => 0,
            ];
        }

        $averageGrade = $grades->avg('marks_obtained');
        $passCount = $grades->where('marks_obtained', '>=', 60)->count();
        $passRate = ($passCount / $totalGrades) * 100;

        return [
            'average_grade' => round($averageGrade, 2),
            'pass_rate' => round($passRate, 2),
            'total_assessments' => $totalGrades,
        ];
    }

    /**
     * Get attendance summary
     */
    public function getAttendanceSummary(): array
    {
        $totalAttendance = $this->attendance()->count();
        
        if ($totalAttendance === 0) {
            return [
                'attendance_rate' => 0,
                'total_records' => 0,
                'present_count' => 0,
                'absent_count' => 0,
            ];
        }

        $presentCount = $this->attendance()->where('status', 'present')->count();
        $absentCount = $this->attendance()->where('status', 'absent')->count();
        $attendanceRate = ($presentCount / $totalAttendance) * 100;

        return [
            'attendance_rate' => round($attendanceRate, 2),
            'total_records' => $totalAttendance,
            'present_count' => $presentCount,
            'absent_count' => $absentCount,
        ];
    }

    /**
     * Check if class has capacity for more students
     */
    public function hasCapacity(int $additionalStudents = 1): bool
    {
        if (!$this->capacity) {
            return true; // No capacity limit set
        }
        
        return ($this->student_count + $additionalStudents) <= $this->capacity;
    }

    /**
     * Get available capacity
     */
    public function getAvailableCapacity(): int
    {
        if (!$this->capacity) {
            return PHP_INT_MAX; // No limit
        }
        
        return max(0, $this->capacity - $this->student_count);
    }

    /**
     * Get class schedule for a specific day
     */
    public function getScheduleForDay(string $day): array
    {
        return $this->schedules()
                   ->where('day_of_week', $day)
                   ->orderBy('start_time')
                   ->get()
                   ->toArray();
    }

    /**
     * Check for schedule conflicts
     */
    public function hasScheduleConflict(string $day, string $startTime, string $endTime, ?int $excludeScheduleId = null): bool
    {
        $query = $this->schedules()
                     ->where('day_of_week', $day)
                     ->where(function($q) use ($startTime, $endTime) {
                         $q->whereBetween('start_time', [$startTime, $endTime])
                           ->orWhereBetween('end_time', [$startTime, $endTime])
                           ->orWhere(function($subQ) use ($startTime, $endTime) {
                               $subQ->where('start_time', '<=', $startTime)
                                    ->where('end_time', '>=', $endTime);
                           });
                     });

        if ($excludeScheduleId) {
            $query->where('id', '!=', $excludeScheduleId);
        }

        return $query->exists();
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

    public function scopeWithTeacher($query)
    {
        return $query->whereNotNull('teacher_id');
    }

    public function scopeWithoutTeacher($query)
    {
        return $query->whereNull('teacher_id');
    }

    public function scopeByCapacity($query, int $minCapacity, int $maxCapacity = null)
    {
        $query->where('capacity', '>=', $minCapacity);
        
        if ($maxCapacity) {
            $query->where('capacity', '<=', $maxCapacity);
        }
        
        return $query;
    }

    public function scopeWithAvailableSeats($query)
    {
        return $query->whereRaw('capacity > (SELECT COUNT(*) FROM students WHERE class_id = classes.id AND status = "active")');
    }
}
