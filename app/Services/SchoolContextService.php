<?php

namespace App\Services;

use App\Models\School;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class SchoolContextService
{
    /**
     * Get the current active school for the user
     */
    public static function getCurrentSchool(?User $user = null): ?School
    {
        $user = $user ?? auth()->user();
        
        if (!$user) {
            return null;
        }

        // For super admins, check if they have an active school set in session
        if ($user->isSuperAdmin()) {
            $activeSchoolId = Session::get('active_school_id');
            if ($activeSchoolId) {
                return School::find($activeSchoolId);
            }
            return null; // Super admin viewing all schools
        }

        // For other users, return their assigned school
        return $user->school;
    }

    /**
     * Get all schools accessible by the user
     */
    public static function getAccessibleSchools(?User $user = null): Collection
    {
        $user = $user ?? auth()->user();
        
        if (!$user) {
            return collect();
        }

        if ($user->isSuperAdmin()) {
            return School::where('is_active', true)->get();
        }

        if ($user->school && $user->school->is_active) {
            return collect([$user->school]);
        }

        return collect();
    }

    /**
     * Check if user can access a specific school
     */
    public static function canAccessSchool(int $schoolId, ?User $user = null): bool
    {
        $user = $user ?? auth()->user();
        
        if (!$user) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return School::where('id', $schoolId)->where('is_active', true)->exists();
        }

        return $user->school_id == $schoolId && $user->school?->is_active;
    }

    /**
     * Switch active school for super admin
     */
    public static function switchSchool(?int $schoolId): bool
    {
        $user = auth()->user();
        
        if (!$user || !$user->isSuperAdmin()) {
            return false;
        }

        if ($schoolId === null) {
            // Switch to "all schools" view
            Session::forget('active_school_id');
            Session::put('active_school_context', 'all');
            return true;
        }

        $school = School::where('id', $schoolId)->where('is_active', true)->first();
        if (!$school) {
            return false;
        }

        Session::put('active_school_id', $school->id);
        Session::put('active_school_context', $school->id);
        return true;
    }

    /**
     * Get current school context (for super admins)
     */
    public static function getCurrentSchoolContext(?User $user = null): string|int
    {
        $user = $user ?? auth()->user();
        
        if (!$user || !$user->isSuperAdmin()) {
            return $user?->school_id ?? 'none';
        }

        return Session::get('active_school_context', 'all');
    }

    /**
     * Apply school context filter to a query builder
     */
    public static function applySchoolFilter($query, string $schoolColumn = 'school_id', ?User $user = null)
    {
        $user = $user ?? auth()->user();
        
        if (!$user) {
            return $query->whereRaw('1 = 0'); // Return no results
        }

        if ($user->isSuperAdmin()) {
            $activeSchoolId = Session::get('active_school_id');
            if ($activeSchoolId) {
                return $query->where($schoolColumn, $activeSchoolId);
            }
            // Return all schools for super admin
            return $query;
        }

        // Filter by user's school
        if ($user->school_id) {
            return $query->where($schoolColumn, $user->school_id);
        }

        return $query->whereRaw('1 = 0'); // Return no results if no school assigned
    }

    /**
     * Get school statistics for dashboard
     */
    public static function getSchoolStatistics(?int $schoolId = null): array
    {
        $user = auth()->user();
        
        if (!$user) {
            return [];
        }

        // Determine which school to get stats for
        if ($schoolId) {
            if (!self::canAccessSchool($schoolId, $user)) {
                return [];
            }
            $school = School::find($schoolId);
        } else {
            $school = self::getCurrentSchool($user);
        }

        if (!$school) {
            // Return aggregate stats for super admin viewing all schools
            if ($user->isSuperAdmin()) {
                return [
                    'total_schools' => School::where('is_active', true)->count(),
                    'total_students' => \App\Models\Student::count(),
                    'total_teachers' => \App\Models\Teacher::count(),
                    'total_classes' => \App\Models\ClassModel::count(),
                ];
            }
            return [];
        }

        // Return stats for specific school
        return [
            'school_name' => $school->name,
            'total_students' => $school->students()->count(),
            'total_teachers' => $school->teachers()->count(),
            'total_classes' => $school->classes()->count(),
            'active_students' => $school->students()->where('status', 'active')->count(),
        ];
    }

    /**
     * Get school context data for views
     */
    public static function getSchoolContextData(?User $user = null): array
    {
        $user = $user ?? auth()->user();
        
        if (!$user) {
            return [
                'current_school' => null,
                'accessible_schools' => collect(),
                'can_switch_schools' => false,
                'school_context' => 'none'
            ];
        }

        return [
            'current_school' => self::getCurrentSchool($user),
            'accessible_schools' => self::getAccessibleSchools($user),
            'can_switch_schools' => $user->isSuperAdmin(),
            'school_context' => self::getCurrentSchoolContext($user),
            'total_accessible_schools' => self::getAccessibleSchools($user)->count()
        ];
    }
}