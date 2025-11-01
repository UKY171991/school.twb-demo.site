<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class SystemNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'title',
        'message',
        'type',
        'data',
        'is_read',
        'read_at',
        'action_url',
        'icon',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scopes
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Accessors
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getIconClassAttribute(): string
    {
        if ($this->icon) {
            return $this->icon;
        }

        return match($this->type) {
            'success' => 'fas fa-check-circle text-success',
            'warning' => 'fas fa-exclamation-triangle text-warning',
            'error' => 'fas fa-times-circle text-danger',
            'info' => 'fas fa-info-circle text-info',
            default => 'fas fa-bell text-primary'
        };
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): bool
    {
        if ($this->is_read) {
            return true;
        }

        return $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(): bool
    {
        return $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Create a notification
     */
    public static function createNotification(array $data): self
    {
        return self::create([
            'school_id' => $data['school_id'] ?? null,
            'user_id' => $data['user_id'] ?? null,
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'] ?? 'info',
            'data' => $data['data'] ?? null,
            'action_url' => $data['action_url'] ?? null,
            'icon' => $data['icon'] ?? null,
        ]);
    }

    /**
     * Create notification for user
     */
    public static function notifyUser(int $userId, string $title, string $message, string $type = 'info', array $options = []): self
    {
        return self::createNotification(array_merge([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ], $options));
    }

    /**
     * Create notification for school
     */
    public static function notifySchool(int $schoolId, string $title, string $message, string $type = 'info', array $options = []): self
    {
        return self::createNotification(array_merge([
            'school_id' => $schoolId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ], $options));
    }

    /**
     * Create notification for all users in school
     */
    public static function notifySchoolUsers(int $schoolId, string $title, string $message, string $type = 'info', array $options = []): int
    {
        $users = User::where('school_id', $schoolId)->where('is_active', true)->get();
        $count = 0;

        foreach ($users as $user) {
            self::notifyUser($user->id, $title, $message, $type, array_merge($options, [
                'school_id' => $schoolId
            ]));
            $count++;
        }

        return $count;
    }

    /**
     * Bulk mark as read
     */
    public static function markAllAsReadForUser(int $userId): int
    {
        return self::where('user_id', $userId)
                  ->where('is_read', false)
                  ->update([
                      'is_read' => true,
                      'read_at' => now(),
                  ]);
    }

    /**
     * Clean old notifications
     */
    public static function cleanOldNotifications(int $days = 90): int
    {
        return self::where('created_at', '<', Carbon::now()->subDays($days))->delete();
    }
}
