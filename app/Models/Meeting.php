<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'teacher_id',
        'student_id',
        'requested_at',
        'scheduled_at',
        'preferred_date',
        'preferred_time',
        'purpose',
        'meeting_type',
        'status',
        'agenda',
        'notes',
        'follow_up_required',
        'follow_up_notes',
        'meeting_link',
        'location'
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'preferred_date' => 'date',
        'follow_up_required' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge-warning',
            'confirmed' => 'badge-success',
            'completed' => 'badge-info',
            'cancelled' => 'badge-danger',
            'rescheduled' => 'badge-secondary'
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getFormattedScheduledTimeAttribute()
    {
        if (!$this->scheduled_at) {
            return null;
        }

        return $this->scheduled_at->format('M d, Y \a\t g:i A');
    }

    public function getFormattedPreferredTimeAttribute()
    {
        if (!$this->preferred_date || !$this->preferred_time) {
            return null;
        }

        $date = Carbon::parse($this->preferred_date);
        $time = Carbon::parse($this->preferred_time);
        
        return $date->format('M d, Y') . ' at ' . $time->format('g:i A');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>', now())
                    ->where('status', 'confirmed');
    }

    public function scopeForParent($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function canBeRescheduled()
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->scheduled_at > now()->addHours(24);
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }
}