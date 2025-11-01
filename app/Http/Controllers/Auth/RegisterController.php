<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_type' => 'admin',
            'is_active' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        $redirectUrl = route('admin.dashboard');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registration completed successfully.',
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect()->intended($redirectUrl)->with('success', 'Registration completed successfully.');
    }
}
