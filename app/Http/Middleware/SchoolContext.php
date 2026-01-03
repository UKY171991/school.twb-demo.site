<?php

namespace App\Http\Middleware;

use App\Models\School;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SchoolContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user has selected a school
        $currentSchoolId = session('current_school_id');

        if (! $currentSchoolId) {
            // Auto-select first active school if none selected
            $firstSchool = School::active()->first();
            if ($firstSchool) {
                session(['current_school_id' => $firstSchool->id]);
                $currentSchoolId = $firstSchool->id;
                // Optional: Notify user about auto-selection in next session persistent check
            }
        }

        // Make current school available globally
        if ($currentSchoolId) {
            $currentSchool = School::find($currentSchoolId);
            
            // If school no longer exists, clear session and retry auto-select
            if (!$currentSchool) {
                session()->forget('current_school_id');
                $firstSchool = School::active()->first();
                if ($firstSchool) {
                    session(['current_school_id' => $firstSchool->id]);
                    $currentSchool = $firstSchool;
                    $currentSchoolId = $firstSchool->id;
                }
            }

            if ($currentSchool) {
                view()->share('currentSchool', $currentSchool);
                // Store in request for controllers to use
                $request->merge(['current_school_id' => $currentSchoolId]);
            }
        }

        return $next($request);
    }
}
