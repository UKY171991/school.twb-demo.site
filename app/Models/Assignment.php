<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'class_id',
        'subject_id',
        'teacher_id',
        'title',
        'description',
        'instructions',
        'type',
        'assigned_date',
        'due_date',
        'due_time',
        'total_marks',
        'status',
        'attachments',
        'allow_late_submission',
        'late_penalty_percentage',
        'submission_instructions',
        'is_active',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'due_date' => 'date',
        'due_time' => 'datetime:H:i',
        'attachments' => 'array',
        'allow_late_submission' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'due_datetime',
        'is_overdue',
        'days_until_due',
        'status_badge',
        'type_badge',
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

    // Accessors
    public function getDueDatetimeAttribute(): ?Carbon
    {
        if (!$this->due_date) {
            return null;
        }
        
        $date = $this->due_date->copy();
        
        if ($this->due_time) {
            $time = Carbon::parse($this->due_time);
            $date->setTime($time->hour, $time->minute);
        } else {
            $date->setTime(23, 59); // Default to end of day
        }
        
        return $date;
    }

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->due_datetime || $this->status === 'completed') {
            return false;
        }
        
        return now()->gt($this->due_datetime);
    }

    public function getDaysUntilDueAttribute(): int
    {
        if (!$this->due_date) {
            return 0;
        }
        
        return now()->startOfDay()->diffInDays($this->due_date, false);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => '<span class="badge badge-secondary">Draft</span>',
            'published' => '<span class="badge badge-primary">Published</span>',
            'completed' => '<span class="badge badge-success">Completed</span>',
            'cancelled' => '<span class="badge badge-danger">Cancelled</span>',
            default => '<span class="badge badge-light">Unknown</span>'
        };
    }

    public function getTypeBadgeAttribute(): string
    {
        return match($this->type) {
            'homework' => '<span class="badge badge-info">Homework</span>',
            'project' => '<span class="badge badge-success">Project</span>',
            'quiz' => '<span class="badge badge-warning">Quiz</span>',
            'exam' => '<span class="badge badge-danger">Exam</span>',
            'presentation' => '<span class="badge badge-primary">Presentation</span>',
            'other' => '<span class="badge badge-secondary">Other</span>',
            default => '<span class="badge badge-light">Unknown</span>'
        };
    }

    /**
     * Get assignment statistics
     */
    public function getStatistics(): array
    {
        $totalStudents = $this->class->students()->where('status', 'active')->count();
        
        return [
            'total_students' => $totalStudents,
            'is_overdue' => $this->is_overdue,
            'days_until_due' => $this->days_until_due,
        ];
    }

    /**
     * Check if assignment is due soon (within specified days)
     */
    public function isDueSoon(int $days = 3): bool
    {
        if (!$this->due_date || $this->status !== 'published') {
            return false;
        }
        
        return $this->days_until_due <= $days && $this->days_until_due >= 0;
    }

    /**
     * Get priority level based on due date and status
     */
    public function getPriority(): string
    {
        if ($this->is_overdue) {
            return 'high';
        }
        
        if ($this->isDueSoon(1)) {
            return 'high';
        }
        
        if ($this->isDueSoon(3)) {
            return 'medium';
        }
        
        return 'low';
    }

    /**
     * Get priority color for UI
     */
    public function getPriorityColor(): string
    {
        return match($this->getPriority()) {
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'success',
            default => 'secondary'
        };
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeBySchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByClass($query, int $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeBySubject($query, int $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByTeacher($query, int $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today());
    }

    public function scopeDueSoon($query, int $days = 7)
    {
        return $query->whereBetween('due_date', [today(), today()->addDays($days)]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', today())
                    ->where('status', '!=', 'completed');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByDateRange($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('due_date', [$startDate, $endDate]);
    }
}
