<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission, string $guard = null): Response
    {
        if (!Auth::guard($guard)->check()) {
            return redirect()->route('login');
        }

        $user = Auth::guard($guard)->user();

        // Check if user has the required permission
        if (!$user->can($permission)) {
            // Log unauthorized access attempt
            logger()->warning('Unauthorized access attempt', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'permission' => $permission,
                'route' => $request->route()?->getName(),
                'url' => $request->url(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // If AJAX request, return JSON error
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have the required permission to perform this action.',
                    'code' => 'PERMISSION_DENIED',
                    'required_permission' => $permission
                ], 403);
            }

            // For regular requests, redirect with error
            return redirect()->back()
                           ->with('error', 'You do not have the required permission to perform this action.');
        }

        return $next($request);
    }
}