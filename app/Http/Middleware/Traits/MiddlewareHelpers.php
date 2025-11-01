<?php

namespace App\Http\Middleware\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

trait MiddlewareHelpers
{
    /**
     * Handle unauthorized access for both AJAX and regular requests
     */
    protected function handleUnauthorizedAccess(
        Request $request, 
        string $message, 
        string $redirectRoute = 'login',
        string $errorCode = 'UNAUTHORIZED'
    ): JsonResponse|RedirectResponse {
        
        // Log the unauthorized access attempt
        $this->logUnauthorizedAccess($request, $message, $errorCode);

        // If AJAX request, return JSON error
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'code' => $errorCode,
                'redirect' => route($redirectRoute)
            ], 403);
        }

        // For regular requests, redirect with error message
        return redirect()->route($redirectRoute)->with('error', $message);
    }

    /**
     * Log unauthorized access attempts
     */
    protected function logUnauthorizedAccess(Request $request, string $message, string $errorCode): void
    {
        logger()->warning('Unauthorized access attempt', [
            'message' => $message,
            'error_code' => $errorCode,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()?->email,
            'route' => $request->route()?->getName(),
            'url' => $request->url(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get appropriate redirect route based on user type
     */
    protected function getRedirectRouteForUser(?string $userType = null): string
    {
        $userType = $userType ?? auth()->user()?->user_type;

        return match($userType) {
            'super_admin' => 'superadmin.dashboard',
            'admin' => 'admin.dashboard',
            'teacher' => 'teacher.dashboard',
            'student' => 'student.dashboard',
            'parent' => 'parent.dashboard',
            default => 'login'
        };
    }

    /**
     * Check if request is AJAX
     */
    protected function isAjaxRequest(Request $request): bool
    {
        return $request->ajax() || $request->wantsJson();
    }

    /**
     * Get user's accessible schools
     */
    protected function getUserAccessibleSchools($user): \Illuminate\Support\Collection
    {
        if ($user->isSuperAdmin()) {
            return \App\Models\School::where('is_active', true)->get();
        }

        if ($user->school_id && $user->school && $user->school->is_active) {
            return collect([$user->school]);
        }

        return collect();
    }

    /**
     * Validate school access for user
     */
    protected function validateUserSchoolAccess($user, int $schoolId): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->school_id == $schoolId;
    }
}