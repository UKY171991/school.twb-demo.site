<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\School;
use Symfony\Component\HttpFoundation\Response;

class SchoolSwitchMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Only super admins can switch schools
        if (!$user->isSuperAdmin()) {
            return $next($request);
        }

        // Handle school switching request
        if ($request->has('switch_school')) {
            return $this->handleSchoolSwitch($request, $next);
        }

        // Set current active school for super admin
        $this->setActiveSchoolForSuperAdmin($request);

        return $next($request);
    }

    /**
     * Handle school switching for super admin
     */
    private function handleSchoolSwitch(Request $request, Closure $next): Response
    {
        $schoolId = $request->input('switch_school');

        // Validate school exists and is active
        if ($schoolId === 'all') {
            Session::forget('active_school_id');
            Session::put('active_school_context', 'all');
        } else {
            $school = School::where('id', $schoolId)->where('is_active', true)->first();
            
            if (!$school) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid or inactive school selected.'
                    ], 400);
                }
                
                return redirect()->back()->with('error', 'Invalid or inactive school selected.');
            }

            Session::put('active_school_id', $school->id);
            Session::put('active_school_context', $school->id);
        }

        // If AJAX request, return success
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'School context switched successfully.',
                'reload' => true
            ]);
        }

        // Redirect back to current page
        return redirect()->back()->with('success', 'School context switched successfully.');
    }

    /**
     * Set active school context for super admin
     */
    private function setActiveSchoolForSuperAdmin(Request $request): void
    {
        $activeSchoolId = Session::get('active_school_id');
        $activeSchoolContext = Session::get('active_school_context', 'all');

        if ($activeSchoolId && $activeSchoolContext !== 'all') {
            $activeSchool = School::find($activeSchoolId);
            if ($activeSchool && $activeSchool->is_active) {
                $request->attributes->set('active_school', $activeSchool);
                $request->attributes->set('active_school_context', $activeSchool->id);
            } else {
                // Clear invalid school from session
                Session::forget('active_school_id');
                Session::put('active_school_context', 'all');
                $request->attributes->set('active_school_context', 'all');
            }
        } else {
            $request->attributes->set('active_school_context', 'all');
        }
    }
}