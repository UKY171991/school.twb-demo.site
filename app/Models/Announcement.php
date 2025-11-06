<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'author_id',
        'title',
        'content',
        'type',
        'priority',
        'target_audience',
        'target_classes',
        'attachments',
        'is_published',
        'is_pinned',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'target_classes' => 'array',
        'attachments' => 'array',
        'is_published' => 'boolean',
        'is_pinned' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $appends = [
        'is_expired',
        'is_active',
        'type_badge',
        'priority_badge',
        'time_ago',
        'read_count',
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reads()
    {
        return $this->hasMany(AnnouncementRead::class);
    }

    // Accessors
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && now()->gt($this->expires_at);
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->is_published && !$this->is_expired;
    }

    public function getTypeBadgeAttribute(): string
    {
        return match($this->type) {
            'general' => '<span class="badge badge-primary">General</span>',
            'academic' => '<span class="badge badge-info">Academic</span>',
            'event' => '<span class="badge badge-success">Event</span>',
            'urgent' => '<span class="badge badge-danger">Urgent</span>',
            'maintenance' => '<span class="badge badge-warning">Maintenance</span>',
            default => '<span class="badge badge-light">Unknown</span>'
        };
    }

    public function getPriorityBadgeAttribute(): string
    {
        return match($this->priority) {
            'urgent' => '<span class="badge badge-danger">Urgent</span>',
            'high' => '<span class="badge badge-warning">High</span>',
            'normal' => '<span class="badge badge-primary">Normal</span>',
            'low' => '<span class="badge badge-secondary">Low</span>',
            default => '<span class="badge badge-light">Unknown</span>'
        };
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->published_at ? $this->published_at->diffForHumans() : $this->created_at->diffForHumans();
    }

    public function getReadCountAttribute(): int
    {
        return $this->reads()->count();
    }

    /**
     * Check if user has read this announcement
     */
    public function isReadByUser(int $userId): bool
    {
        return $this->reads()->where('user_id', $userId)->exists();
    }

    /**
     * Mark as read by user
     */
    public function markAsReadByUser(int $userId): void
    {
        if (!$this->isReadByUser($userId)) {
            AnnouncementRead::create([
                'announcement_id' => $this->id,
                'user_id' => $userId,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Check if announcement is targeted to user
     */
    public function isTargetedToUser(User $user): bool
    {
        if ($this->target_audience === 'all') {
            return true;
        }

        if ($this->target_audience === 'students' && $user->isStudent()) {
            if ($this->target_classes && count($this->target_classes) > 0) {
                $student = $user->student;
                return $student && in_array($student->class_id, $this->target_classes);
            }
            return true;
        }

        if ($this->target_audience === 'teachers' && $user->isTeacher()) {
            return true;
        }

        if ($this->target_audience === 'parents' && $user->isParent()) {
            return true;
        }

        if ($this->target_audience === 'staff' && ($user->isAdmin() || $user->isSuperAdmin())) {
            return true;
        }

        return false;
    }

    /**
     * Scopes
     */
    public function scopeBySchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_published', true)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeForUser($query, User $user)
    {
        return $query->where(function($q) use ($user) {
            $q->where('target_audience', 'all');
            
            if ($user->isStudent()) {
                $q->orWhere('target_audience', 'students');
                if ($user->student && $user->student->class_id) {
                    $q->orWhereJsonContains('target_classes', $user->student->class_id);
                }
            }
            
            if ($user->isTeacher()) {
                $q->orWhere('target_audience', 'teachers');
            }
            
            if ($user->isParent()) {
                $q->orWhere('target_audience', 'parents');
            }
            
            if ($user->isAdmin() || $user->isSuperAdmin()) {
                $q->orWhere('target_audience', 'staff');
            }
        });
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }
}
