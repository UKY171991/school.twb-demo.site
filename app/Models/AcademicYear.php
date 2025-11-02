<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'start_date',
        'end_date',
        'current_semester',
        'total_semesters',
        'is_active',
        'is_current',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_current' => 'boolean',
        'total_semesters' => 'integer',
    ];

    protected $appends = [
        'duration_days',
        'progress_percentage',
        'semester_info',
        'status_text',
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function schedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_academic_years')
                   ->withPivot(['semester', 'status', 'enrollment_date'])
                   ->withTimestamps();
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    // Accessors
    public function getDurationDaysAttribute(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }
        
        return $this->start_date->diffInDays($this->end_date);
    }

    public function getProgressPercentageAttribute(): float
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }
        
        $today = Carbon::today();
        
        if ($today->lt($this->start_date)) {
            return 0;
        }
        
        if ($today->gt($this->end_date)) {
            return 100;
        }
        
        $totalDays = $this->duration_days;
        $elapsedDays = $this->start_date->diffInDays($today);
        
        return $totalDays > 0 ? round(($elapsedDays / $totalDays) * 100, 2) : 0;
    }

    public function getSemesterInfoAttribute(): array
    {
        return [
            'current' => $this->current_semester,
            'total' => $this->total_semesters,
            'remaining' => max(0, $this->total_semesters - $this->current_semester),
            'progress' => $this->total_semesters > 0 ? round(($this->current_semester / $this->total_semesters) * 100, 2) : 0,
        ];
    }

    public function getStatusTextAttribute(): string
    {
        if (!$this->is_active) {
            return 'Inactive';
        }
        
        if ($this->is_current) {
            return 'Current';
        }
        
        $today = Carbon::today();
        
        if ($today->lt($this->start_date)) {
            return 'Upcoming';
        }
        
        if ($today->gt($this->end_date)) {
            return 'Completed';
        }
        
        return 'Active';
    }

    public function getStatusBadgeAttribute(): string
    {
        $status = $this->status_text;
        
        return match($status) {
            'Current' => '<span class="badge badge-primary">Current</span>',
            'Active' => '<span class="badge badge-success">Active</span>',
            'Upcoming' => '<span class="badge badge-info">Upcoming</span>',
            'Completed' => '<span class="badge badge-secondary">Completed</span>',
            'Inactive' => '<span class="badge badge-danger">Inactive</span>',
            default => '<span class="badge badge-light">Unknown</span>',
        };
    }

    /**
     * Get academic year statistics
     */
    public function getStatistics(): array
    {
        $totalStudents = $this->students()->count();
        $activeStudents = $this->students()->wherePivot('status', 'active')->count();
        $totalSchedules = $this->schedules()->count();
        $activeSchedules = $this->schedules()->where('is_active', true)->count();
        $totalGrades = $this->grades()->count();
        
        return [
            'total_students' => $totalStudents,
            'active_students' => $activeStudents,
            'total_schedules' => $totalSchedules,
            'active_schedules' => $activeSchedules,
            'total_grades' => $totalGrades,
            'duration_days' => $this->duration_days,
            'progress_percentage' => $this->progress_percentage,
            'current_semester' => $this->current_semester,
            'total_semesters' => $this->total_semesters,
        ];
    }

    /**
     * Progress to next semester
     */
    public function progressToNextSemester(): bool
    {
        if ($this->current_semester >= $this->total_semesters) {
            return false; // Already at the last semester
        }
        
        $this->increment('current_semester');
        
        // Update student enrollments to next semester
        $this->students()->wherePivot('status', 'active')
                        ->wherePivot('semester', $this->current_semester - 1)
                        ->updateExistingPivot($this->students->pluck('id'), [
                            'semester' => $this->current_semester
                        ]);
        
        return true;
    }

    /**
     * Activate this academic year (deactivate others)
     */
    public function activate(): bool
    {
        // Deactivate all other academic years for this school
        self::where('school_id', $this->school_id)
            ->where('id', '!=', $this->id)
            ->update(['is_current' => false]);
        
        // Activate this academic year
        $this->update([
            'is_current' => true,
            'is_active' => true
        ]);
        
        return true;
    }

    /**
     * Check if academic year can be deleted
     */
    public function canBeDeleted(): bool
    {
        // Cannot delete if it has grades
        if ($this->grades()->exists()) {
            return false;
        }
        
        // Cannot delete if it has active student enrollments
        if ($this->students()->wherePivot('status', 'active')->exists()) {
            return false;
        }
        
        // Cannot delete if it's the current academic year
        if ($this->is_current) {
            return false;
        }
        
        return true;
    }

    /**
     * Get semester list
     */
    public function getSemesterList(): array
    {
        $semesters = [];
        
        for ($i = 1; $i <= $this->total_semesters; $i++) {
            $semesters[] = [
                'number' => $i,
                'name' => "Semester {$i}",
                'is_current' => $i === $this->current_semester,
                'is_completed' => $i < $this->current_semester,
                'is_upcoming' => $i > $this->current_semester,
            ];
        }
        
        return $semesters;
    }

    /**
     * Get enrollment statistics by semester
     */
    public function getEnrollmentStatistics(): array
    {
        $stats = [];
        
        for ($i = 1; $i <= $this->total_semesters; $i++) {
            $totalEnrolled = $this->students()->wherePivot('semester', $i)->count();
            $activeEnrolled = $this->students()->wherePivot('semester', $i)
                                              ->wherePivot('status', 'active')
                                              ->count();
            
            $stats["semester_{$i}"] = [
                'semester' => $i,
                'total_enrolled' => $totalEnrolled,
                'active_enrolled' => $activeEnrolled,
                'inactive_enrolled' => $totalEnrolled - $activeEnrolled,
            ];
        }
        
        return $stats;
    }

    /**
     * Check if date falls within academic year
     */
    public function containsDate(Carbon $date): bool
    {
        return $date->between($this->start_date, $this->end_date);
    }

    /**
     * Get current semester based on date
     */
    public function getCurrentSemesterByDate(Carbon $date = null): int
    {
        $date = $date ?? Carbon::today();
        
        if (!$this->containsDate($date)) {
            return 0; // Date is outside academic year
        }
        
        $totalDays = $this->duration_days;
        $elapsedDays = $this->start_date->diffInDays($date);
        $daysPerSemester = $totalDays / $this->total_semesters;
        
        return min($this->total_semesters, (int) ceil(($elapsedDays + 1) / $daysPerSemester));
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeBySchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByDateRange($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->where(function($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function($subQ) use ($startDate, $endDate) {
                  $subQ->where('start_date', '<=', $startDate)
                       ->where('end_date', '>=', $endDate);
              });
        });
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', Carbon::today());
    }

    public function scopeCompleted($query)
    {
        return $query->where('end_date', '<', Carbon::today());
    }

    public function scopeInProgress($query)
    {
        $today = Carbon::today();
        return $query->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
    }
}