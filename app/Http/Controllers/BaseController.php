<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\School;
use App\Models\User;
use App\Services\SchoolContextService;

abstract class BaseController extends Controller
{
    protected $user;
    protected $school;
    protected $userRole;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->userRole = $this->user->user_type ?? null;
            $this->school = $this->user->school ?? null;
            
            return $next($request);
        });
    }

    /**
     * Get current authenticated user
     */
    protected function getCurrentUser(): ?User
    {
        return $this->user;
    }

    /**
     * Get current user's school (using service)
     */
    protected function getCurrentSchool(): ?School
    {
        return SchoolContextService::getCurrentSchool($this->user);
    }

    /**
     * Get current user's role
     */
    protected function getCurrentUserRole(): ?string
    {
        return $this->userRole;
    }

    /**
     * Check if user has access to specific school (using service)
     */
    protected function hasSchoolAccess(int $schoolId): bool
    {
        return SchoolContextService::canAccessSchool($schoolId, $this->user);
    }

    /**
     * Get schools accessible by current user (using service)
     */
    protected function getAccessibleSchools()
    {
        return SchoolContextService::getAccessibleSchools($this->user);
    }

    /**
     * Apply school context filter to query (using service)
     */
    protected function applySchoolContext($query, $schoolIdColumn = 'school_id')
    {
        return SchoolContextService::applySchoolFilter($query, $schoolIdColumn, $this->user);
    }

    /**
     * Get dashboard route based on user role
     */
    protected function getDashboardRoute(): string
    {
        return match($this->userRole) {
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
    protected function getMenuItems(): array
    {
        $menuItems = [];

        switch ($this->userRole) {
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
     * Check if user has specific permission
     */
    protected function hasPermission(string $permission): bool
    {
        return $this->user->can($permission);
    }

    /**
     * Authorize school access or fail
     */
    protected function authorizeSchoolAccess(int $schoolId): void
    {
        if (!$this->hasSchoolAccess($schoolId)) {
            abort(403, 'Unauthorized access to school data.');
        }
    }

    /**
     * Get common view data
     */
    protected function getCommonViewData(): array
    {
        $schoolContextData = SchoolContextService::getSchoolContextData($this->user);
        
        return array_merge([
            'currentUser' => $this->user,
            'userRole' => $this->userRole,
            'menuItems' => $this->getMenuItems(),
        ], $schoolContextData);
    }
}