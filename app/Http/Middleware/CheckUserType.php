<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$types): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if (!in_array($user->user_type, $types)) {
            // Log unauthorized access attempt
            logger()->warning('User type access denied', [
                'user_id' => $user->id,
                'user_type' => $user->user_type,
                'required_types' => $types,
                'route' => $request->route()?->getName(),
                'url' => $request->url()
            ]);

            // If AJAX request, return JSON error
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. You do not have permission to access this area.',
                    'code' => 'USER_TYPE_ACCESS_DENIED',
                    'redirect' => $this->getRedirectRoute($user->user_type)
                ], 403);
            }

            // For regular requests, redirect to appropriate dashboard
            return redirect()->route($this->getRedirectRoute($user->user_type))
                           ->with('error', 'Access denied. You do not have permission to access this area.');
        }

        return $next($request);
    }

    /**
     * Get redirect route based on user type
     */
    private function getRedirectRoute(string $userType): string
    {
        return match($userType) {
            'super_admin' => 'superadmin.dashboard',
            'admin' => 'admin.dashboard',
            'teacher' => 'teacher.dashboard',
            'student' => 'student.dashboard',
            'parent' => 'parent.dashboard',
            default => 'login'
        };
    }
}
