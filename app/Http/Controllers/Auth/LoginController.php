<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => ['Your account is inactive. Please contact administrator.'],
                ]);
            }

            // Redirect based on user type
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!',
                    'redirect' => $this->getRedirectPath($user)
                ]);
            }

            return redirect()->intended($this->getRedirectPath($user));
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials. Please check your email and password.',
            ], 401);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully!',
                'redirect' => route('login')
            ]);
        }

        return redirect()->route('login');
    }

    /**
     * Get the redirect path based on user type.
     */
    protected function getRedirectPath($user)
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return route('admin.dashboard');
        } elseif ($user->isTeacher()) {
            return route('teacher.dashboard');
        } elseif ($user->isStudent()) {
            return route('student.dashboard');
        } elseif ($user->isParent()) {
            return route('parent.dashboard');
        }

        return route('dashboard');
    }
}
