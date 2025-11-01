<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\School;
use Symfony\Component\HttpFoundation\Response;

class SchoolContextMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to authenticated users
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        
        // Set school context based on user type
        $this->setSchoolContext($user, $request);
        
        // Validate school access if school_id is in request
        $this->validateSchoolAccess($user, $request);
        
        // Share school context with views
        $this->shareSchoolContextWithViews($user);

        return $next($request);
    }

    /**
     * Set school context for the current request
     */
    private function setSchoolContext($user, Request $request): void
    {
        // Super admins can access all schools
        if ($user->isSuperAdmin()) {
            $request->attributes->set('user_school_context', 'all');
            $request->attributes->set('accessible_schools', School::where('is_active', true)->get());
            return;
        }

        // Other users are limited to their assigned school
        if ($user->school_id) {
            $school = $user->school;
            if ($school && $school->is_active) {
                $request->attributes->set('user_school_context', $school->id);
                $request->attributes->set('current_school', $school);
                $request->attributes->set('accessible_schools', collect([$school]));
            } else {
                // User's school is inactive or doesn't exist
                $request->attributes->set('user_school_context', null);
                $request->attributes->set('accessible_schools', collect());
            }
        } else {
            // User has no school assigned
            $request->attributes->set('user_school_context', null);
            $request->attributes->set('accessible_schools', collect());
        }
    }

    /**
     * Validate school access for the current request
     */
    private function validateSchoolAccess($user, Request $request): void
    {
        // Check if request contains school_id parameter
        $requestedSchoolId = $request->route('school') ?? 
                           $request->input('school_id') ?? 
                           $request->header('X-School-ID');

        if (!$requestedSchoolId) {
            return; // No specific school requested
        }

        // Super admins can access any school
        if ($user->isSuperAdmin()) {
            return;
        }

        // Other users can only access their assigned school
        if ($user->school_id != $requestedSchoolId) {
            abort(403, 'You do not have access to this school\'s data.');
        }
    }

    /**
     * Share school context with views
     */
    private function shareSchoolContextWithViews($user): void
    {
        $currentSchool = $user->school;
        $accessibleSchools = $user->isSuperAdmin() 
            ? School::where('is_active', true)->get()
            : ($currentSchool ? collect([$currentSchool]) : collect());

        View::share([
            'currentSchool' => $currentSchool,
            'accessibleSchools' => $accessibleSchools,
            'userCanAccessAllSchools' => $user->isSuperAdmin(),
            'schoolContext' => [
                'current_school_id' => $currentSchool?->id,
                'current_school_name' => $currentSchool?->name,
                'can_switch_schools' => $user->isSuperAdmin(),
                'total_accessible_schools' => $accessibleSchools->count()
            ]
        ]);
    }
}