<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\School;

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
        
        if (!$currentSchoolId) {
            // Auto-select the first active school if none selected
            $firstSchool = School::active()->first();
            if ($firstSchool) {
                session(['current_school_id' => $firstSchool->id]);
                $currentSchoolId = $firstSchool->id;
            }
        }
        
        // Make current school available globally
        if ($currentSchoolId) {
            $currentSchool = School::find($currentSchoolId);
            view()->share('currentSchool', $currentSchool);
            
            // Store in request for controllers to use
            $request->merge(['current_school_id' => $currentSchoolId]);
        }
        
        return $next($request);
    }
}