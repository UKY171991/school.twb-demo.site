<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Services\SchoolContextService;
use Carbon\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'school_id',
        'is_active',
        'profile_photo',
        'phone',
        'last_login_at',
        'preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'preferences' => 'array',
        ];
    }

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function parent()
    {
        return $this->hasOne(ParentModel::class);
    }

    public function notifications()
    {
        return $this->hasMany(SystemNotification::class);
    }

    public function dashboardWidgets()
    {
        return $this->hasMany(DashboardWidget::class)->ordered();
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Helper methods
    public function isSuperAdmin(): bool
    {
        return $this->user_type === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->user_type === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->user_type === 'student';
    }

    public function isParent(): bool
    {
        return $this->user_type === 'parent';
    }

    /**
     * Get dashboard route based on user type
     */
    public function getDashboardRoute(): string
    {
        return match($this->user_type) {
            'super_admin' => route('superadmin.dashboard'),
            'admin' => route('admin.dashboard'),
            'teacher' => route('teacher.dashboard'),
            'student' => route('student.dashboard'),
            'parent' => route('parent.dashboard'),
            default => route('login')
        };
    }

    /**
     * Get menu items based on user role and permissions
     */
    public function getMenuItems(): array
    {
        $menuItems = [];

        switch ($this->user_type) {
            case 'super_admin':
                $menuItems = [
                    ['title' => 'Dashboard', 'route' => 'superadmin.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                    ['title' => 'Schools', 'route' => 'superadmin.schools.index', 'icon' => 'fas fa-school'],
                    ['title' => 'Users', 'route' => 'superadmin.users.index', 'icon' => 'fas fa-users'],
                    ['title' => 'Reports', 'route' => 'superadmin.reports.index', 'icon' => 'fas fa-chart-bar'],
                ];
                break;

            case 'admin':
                $menuItems = [
                    ['title' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                    ['title' => 'Students', 'route' => 'admin.students.index', 'icon' => 'fas fa-user-graduate'],
                    ['title' => 'Teachers', 'route' => 'admin.teachers.index', 'icon' => 'fas fa-chalkboard-teacher'],
                    ['title' => 'Classes', 'route' => 'admin.classes.index', 'icon' => 'fas fa-door-open'],
                    ['title' => 'Subjects', 'route' => 'admin.subjects.index', 'icon' => 'fas fa-book'],
                    ['title' => 'Parents', 'route' => 'admin.parents.index', 'icon' => 'fas fa-user-friends'],
                    ['title' => 'Reports', 'route' => 'admin.reports.index', 'icon' => 'fas fa-chart-line'],
                ];
                break;

            case 'teacher':
                $menuItems = [
                    ['title' => 'Dashboard', 'route' => 'teacher.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                    ['title' => 'My Classes', 'route' => 'teacher.classes.index', 'icon' => 'fas fa-door-open'],
                    ['title' => 'Attendance', 'route' => 'teacher.attendance.index', 'icon' => 'fas fa-calendar-check'],
                    ['title' => 'Grades', 'route' => 'teacher.grades.index', 'icon' => 'fas fa-star'],
                    ['title' => 'Students', 'route' => 'teacher.students.index', 'icon' => 'fas fa-user-graduate'],
                    ['title' => 'Schedule', 'route' => 'teacher.schedule.index', 'icon' => 'fas fa-calendar'],
                ];
                break;

            case 'student':
                $menuItems = [
                    ['title' => 'Dashboard', 'route' => 'student.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                    ['title' => 'My Grades', 'route' => 'student.grades.index', 'icon' => 'fas fa-star'],
                    ['title' => 'Attendance', 'route' => 'student.attendance.index', 'icon' => 'fas fa-calendar-check'],
                    ['title' => 'Schedule', 'route' => 'student.schedule.index', 'icon' => 'fas fa-calendar'],
                    ['title' => 'Profile', 'route' => 'student.profile.index', 'icon' => 'fas fa-user'],
                ];
                break;

            case 'parent':
                $menuItems = [
                    ['title' => 'Dashboard', 'route' => 'parent.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                    ['title' => 'My Children', 'route' => 'parent.children.index', 'icon' => 'fas fa-child'],
                    ['title' => 'Communications', 'route' => 'parent.communications.index', 'icon' => 'fas fa-comments'],
                    ['title' => 'Reports', 'route' => 'parent.reports.index', 'icon' => 'fas fa-chart-line'],
                ];
                break;
        }

        return $menuItems;
    }

    /**
     * Check if user has access to specific school
     */
    public function hasSchoolAccess(int $schoolId): bool
    {
        return SchoolContextService::canAccessSchool($schoolId, $this);
    }

    /**
     * Get accessible schools for this user
     */
    public function getAccessibleSchools()
    {
        return SchoolContextService::getAccessibleSchools($this);
    }

    /**
     * Get user's profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        
        return asset('vendor/adminlte/dist/img/user2-160x160.jpg');
    }

    /**
     * Get user's display name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get user's role display name
     */
    public function getRoleDisplayNameAttribute(): string
    {
        return match($this->user_type) {
            'super_admin' => 'Super Administrator',
            'admin' => 'School Administrator',
            'teacher' => 'Teacher',
            'student' => 'Student',
            'parent' => 'Parent',
            default => 'User'
        };
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Get user preference
     */
    public function getPreference(string $key, $default = null)
    {
        return data_get($this->preferences, $key, $default);
    }

    /**
     * Set user preference
     */
    public function setPreference(string $key, $value): bool
    {
        $preferences = $this->preferences ?? [];
        data_set($preferences, $key, $value);
        
        return $this->update(['preferences' => $preferences]);
    }

    /**
     * Check if user is active and can login
     */
    public function canLogin(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Check if school is active (except for super admins)
        if (!$this->isSuperAdmin() && $this->school && !$this->school->is_active) {
            return false;
        }

        return true;
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('user_type', $type);
    }

    public function scopeBySchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeRecentlyActive($query, int $days = 30)
    {
        return $query->where('last_login_at', '>=', Carbon::now()->subDays($days));
    }
}
