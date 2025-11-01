<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\Traits\MiddlewareHelpers;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedAccessMiddleware
{
    use MiddlewareHelpers;
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = $user->user_type;

        // Check if user has any of the required roles
        if (!in_array($userRole, $roles)) {
            return $this->handleUnauthorizedAccess(
                $request,
                'You do not have permission to access this resource.',
                $this->getRedirectRouteForUser($userRole),
                'INSUFFICIENT_PERMISSIONS'
            );
        }

        return $next($request);
    }
}