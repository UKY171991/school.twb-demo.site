<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'sender_id',
        'receiver_id',
        'conversation_id',
        'subject',
        'message',
        'priority',
        'status',
        'attachments',
        'attachment_path',
        'parent_message_id',
        'read_at',
        'is_read',
        'is_important',
        'is_archived',
    ];

    protected $casts = [
        'attachments' => 'array',
        'read_at' => 'datetime',
        'is_read' => 'boolean',
        'is_important' => 'boolean',
        'is_archived' => 'boolean',
    ];

    protected $appends = [
        'is_read',
        'priority_badge',
        'status_badge',
        'time_ago',
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function parentMessage()
    {
        return $this->belongsTo(Message::class, 'parent_message_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_message_id');
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    // Accessors
    public function getIsReadAttribute(): bool
    {
        return !is_null($this->read_at);
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

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => '<span class="badge badge-secondary">Draft</span>',
            'sent' => '<span class="badge badge-info">Sent</span>',
            'delivered' => '<span class="badge badge-primary">Delivered</span>',
            'read' => '<span class="badge badge-success">Read</span>',
            default => '<span class="badge badge-light">Unknown</span>'
        };
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'read_at' => now(),
                'status' => 'read'
            ]);
        }
    }

    /**
     * Get conversation thread
     */
    public function getConversationThread()
    {
        $rootMessage = $this->parent_message_id ? $this->parentMessage : $this;
        
        return Message::where(function($query) use ($rootMessage) {
            $query->where('id', $rootMessage->id)
                  ->orWhere('parent_message_id', $rootMessage->id);
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at')
        ->get();
    }

    /**
     * Scopes
     */
    public function scopeBySchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->orWhere('receiver_id', $userId);
        });
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRootMessages($query)
    {
        return $query->whereNull('parent_message_id');
    }
}
