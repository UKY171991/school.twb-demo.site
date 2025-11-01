<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DashboardWidget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'widget_type',
        'position',
        'configuration',
        'is_active',
    ];

    protected $casts = [
        'configuration' => 'array',
        'is_active' => 'boolean',
        'position' => 'integer',
    ];

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('widget_type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    /**
     * Get widget configuration value
     */
    public function getConfig(string $key, $default = null)
    {
        return data_get($this->configuration, $key, $default);
    }

    /**
     * Set widget configuration value
     */
    public function setConfig(string $key, $value): bool
    {
        $config = $this->configuration ?? [];
        data_set($config, $key, $value);
        
        return $this->update(['configuration' => $config]);
    }

    /**
     * Get available widget types
     */
    public static function getAvailableTypes(): array
    {
        return [
            'stats_card' => 'Statistics Card',
            'recent_activities' => 'Recent Activities',
            'quick_actions' => 'Quick Actions',
            'calendar' => 'Calendar Widget',
            'notifications' => 'Notifications',
            'chart' => 'Chart Widget',
            'todo_list' => 'Todo List',
            'weather' => 'Weather Widget',
        ];
    }

    /**
     * Create default widgets for user
     */
    public static function createDefaultWidgets(int $userId, string $userType): void
    {
        $defaultWidgets = self::getDefaultWidgetsByUserType($userType);
        
        foreach ($defaultWidgets as $position => $widget) {
            self::create([
                'user_id' => $userId,
                'widget_type' => $widget['type'],
                'position' => $position,
                'configuration' => $widget['config'] ?? [],
                'is_active' => true,
            ]);
        }
    }

    /**
     * Get default widgets by user type
     */
    private static function getDefaultWidgetsByUserType(string $userType): array
    {
        return match($userType) {
            'super_admin' => [
                ['type' => 'stats_card', 'config' => ['title' => 'Total Schools', 'metric' => 'schools_count']],
                ['type' => 'stats_card', 'config' => ['title' => 'Total Users', 'metric' => 'users_count']],
                ['type' => 'recent_activities', 'config' => ['limit' => 10]],
                ['type' => 'quick_actions', 'config' => ['actions' => ['create_school', 'manage_users']]],
            ],
            'admin' => [
                ['type' => 'stats_card', 'config' => ['title' => 'Total Students', 'metric' => 'students_count']],
                ['type' => 'stats_card', 'config' => ['title' => 'Total Teachers', 'metric' => 'teachers_count']],
                ['type' => 'recent_activities', 'config' => ['limit' => 8]],
                ['type' => 'quick_actions', 'config' => ['actions' => ['add_student', 'add_teacher']]],
            ],
            'teacher' => [
                ['type' => 'stats_card', 'config' => ['title' => 'My Classes', 'metric' => 'classes_count']],
                ['type' => 'stats_card', 'config' => ['title' => 'My Students', 'metric' => 'students_count']],
                ['type' => 'calendar', 'config' => ['view' => 'week']],
                ['type' => 'quick_actions', 'config' => ['actions' => ['mark_attendance', 'enter_grades']]],
            ],
            'student' => [
                ['type' => 'stats_card', 'config' => ['title' => 'Current GPA', 'metric' => 'gpa']],
                ['type' => 'stats_card', 'config' => ['title' => 'Attendance', 'metric' => 'attendance_percentage']],
                ['type' => 'calendar', 'config' => ['view' => 'month']],
                ['type' => 'notifications', 'config' => ['limit' => 5]],
            ],
            'parent' => [
                ['type' => 'stats_card', 'config' => ['title' => 'Children', 'metric' => 'children_count']],
                ['type' => 'recent_activities', 'config' => ['limit' => 6, 'filter' => 'children']],
                ['type' => 'notifications', 'config' => ['limit' => 5]],
                ['type' => 'quick_actions', 'config' => ['actions' => ['view_grades', 'contact_teacher']]],
            ],
            default => [],
        };
    }

    /**
     * Reorder widgets for user
     */
    public static function reorderWidgets(int $userId, array $widgetIds): bool
    {
        foreach ($widgetIds as $position => $widgetId) {
            self::where('id', $widgetId)
                ->where('user_id', $userId)
                ->update(['position' => $position]);
        }
        
        return true;
    }
}
