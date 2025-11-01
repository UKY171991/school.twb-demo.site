<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSchoolMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Super admins don't need school assignment
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user has a school assigned
        if (!$user->school_id) {
            return $this->handleNoSchoolAssignment($request, 'You are not assigned to any school. Please contact the administrator.');
        }

        // Check if the assigned school exists and is active
        $school = $user->school;
        if (!$school) {
            return $this->handleNoSchoolAssignment($request, 'Your assigned school no longer exists. Please contact the administrator.');
        }

        if (!$school->is_active) {
            return $this->handleNoSchoolAssignment($request, 'Your assigned school is currently inactive. Please contact the administrator.');
        }

        // Check if user account is active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')
                           ->with('error', 'Your account has been deactivated. Please contact the administrator.');
        }

        return $next($request);
    }

    /**
     * Handle cases where user has no valid school assignment
     */
    private function handleNoSchoolAssignment(Request $request, string $message): Response
    {
        // If AJAX request, return JSON error
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'code' => 'NO_SCHOOL_ASSIGNMENT',
                'redirect' => route('login')
            ], 403);
        }

        // For regular requests, logout and redirect to login
        Auth::logout();
        return redirect()->route('login')->with('error', $message);
    }
}