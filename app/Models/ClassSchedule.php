<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClassSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'class_id',
        'subject_id',
        'teacher_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room_number',
        'academic_year_id',
        'semester',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    protected $appends = [
        'duration_minutes',
        'time_slot',
        'day_name',
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    // Accessors
    public function getDurationMinutesAttribute(): int
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }
        
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        
        return $start->diffInMinutes($end);
    }

    public function getTimeSlotAttribute(): string
    {
        if (!$this->start_time || !$this->end_time) {
            return 'Not set';
        }
        
        return Carbon::parse($this->start_time)->format('H:i') . ' - ' . Carbon::parse($this->end_time)->format('H:i');
    }

    public function getDayNameAttribute(): string
    {
        $days = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        return $days[$this->day_of_week] ?? ucfirst($this->day_of_week);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->is_active) {
            true => '<span class="badge badge-success">Active</span>',
            false => '<span class="badge badge-secondary">Inactive</span>',
        };
    }

    /**
     * Check if this schedule conflicts with another schedule
     */
    public function hasConflictWith(ClassSchedule $other): bool
    {
        // Different days don't conflict
        if ($this->day_of_week !== $other->day_of_week) {
            return false;
        }

        // Same teacher can't be in two places at once
        if ($this->teacher_id === $other->teacher_id) {
            return $this->timeOverlapsWith($other);
        }

        // Same class can't have two subjects at once
        if ($this->class_id === $other->class_id) {
            return $this->timeOverlapsWith($other);
        }

        // Same room can't be used by two classes at once
        if ($this->room_number && $other->room_number && $this->room_number === $other->room_number) {
            return $this->timeOverlapsWith($other);
        }

        return false;
    }

    /**
     * Check if time slots overlap
     */
    private function timeOverlapsWith(ClassSchedule $other): bool
    {
        $thisStart = Carbon::parse($this->start_time);
        $thisEnd = Carbon::parse($this->end_time);
        $otherStart = Carbon::parse($other->start_time);
        $otherEnd = Carbon::parse($other->end_time);

        return $thisStart->lt($otherEnd) && $thisEnd->gt($otherStart);
    }

    /**
     * Get conflicting schedules
     */
    public function getConflicts(): array
    {
        $conflicts = [];
        
        $potentialConflicts = self::where('school_id', $this->school_id)
                                 ->where('day_of_week', $this->day_of_week)
                                 ->where('is_active', true)
                                 ->where('id', '!=', $this->id ?? 0)
                                 ->get();

        foreach ($potentialConflicts as $schedule) {
            if ($this->hasConflictWith($schedule)) {
                $conflicts[] = [
                    'type' => $this->getConflictType($schedule),
                    'schedule' => $schedule,
                    'message' => $this->getConflictMessage($schedule)
                ];
            }
        }

        return $conflicts;
    }

    /**
     * Get conflict type
     */
    private function getConflictType(ClassSchedule $other): string
    {
        if ($this->teacher_id === $other->teacher_id) {
            return 'teacher';
        }
        
        if ($this->class_id === $other->class_id) {
            return 'class';
        }
        
        if ($this->room_number && $other->room_number && $this->room_number === $other->room_number) {
            return 'room';
        }
        
        return 'unknown';
    }

    /**
     * Get conflict message
     */
    private function getConflictMessage(ClassSchedule $other): string
    {
        $type = $this->getConflictType($other);
        
        return match($type) {
            'teacher' => "Teacher {$this->teacher->full_name} is already scheduled for {$other->subject->name} with {$other->class->full_name}",
            'class' => "Class {$this->class->full_name} already has {$other->subject->name} scheduled",
            'room' => "Room {$this->room_number} is already booked for {$other->class->full_name} - {$other->subject->name}",
            default => "Schedule conflict detected"
        };
    }

    /**
     * Get schedule statistics
     */
    public function getStatistics(): array
    {
        return [
            'duration_minutes' => $this->duration_minutes,
            'duration_hours' => round($this->duration_minutes / 60, 2),
            'day' => $this->day_name,
            'time_slot' => $this->time_slot,
            'room' => $this->room_number ?? 'Not assigned',
            'conflicts_count' => count($this->getConflicts()),
        ];
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

    public function scopeByDay($query, string $day)
    {
        return $query->where('day_of_week', $day);
    }

    public function scopeByClass($query, int $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeByTeacher($query, int $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeBySubject($query, int $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByTimeRange($query, string $startTime, string $endTime)
    {
        return $query->where('start_time', '>=', $startTime)
                    ->where('end_time', '<=', $endTime);
    }

    public function scopeByAcademicYear($query, int $academicYearId)
    {
        return $query->where('academic_year_id', $academicYearId);
    }

    public function scopeBySemester($query, string $semester)
    {
        return $query->where('semester', $semester);
    }

    public function scopeWithConflicts($query)
    {
        return $query->whereExists(function($subQuery) {
            $subQuery->select('id')
                    ->from('class_schedules as cs2')
                    ->whereColumn('cs2.school_id', 'class_schedules.school_id')
                    ->whereColumn('cs2.day_of_week', 'class_schedules.day_of_week')
                    ->where('cs2.is_active', true)
                    ->whereColumn('cs2.id', '!=', 'class_schedules.id')
                    ->where(function($conflictQuery) {
                        $conflictQuery->whereColumn('cs2.teacher_id', 'class_schedules.teacher_id')
                                     ->orWhereColumn('cs2.class_id', 'class_schedules.class_id')
                                     ->orWhereColumn('cs2.room_number', 'class_schedules.room_number');
                    })
                    ->whereRaw('cs2.start_time < class_schedules.end_time')
                    ->whereRaw('cs2.end_time > class_schedules.start_time');
        });
    }
}