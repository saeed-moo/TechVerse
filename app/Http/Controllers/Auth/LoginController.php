<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
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
     * Handle login request with rate limiting.
     */
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Generate throttle key (email + IP)
        $throttleKey = $this->throttleKey($request);

        // Check if too many attempts
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);

            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Your account is locked for {$minutes} minutes. Please try again later.",
            ])->status(429);
        }

        // Attempt login
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            // Clear rate limiter on successful login
            RateLimiter::clear($throttleKey);

            return redirect()->intended('/');
        }

        // Increment failed attempts
        RateLimiter::hit($throttleKey, 1800); // 1800 seconds = 30 minutes

        // Count remaining attempts
        $attempts = RateLimiter::attempts($throttleKey);
        $remaining = 5 - $attempts;

        // Return error with remaining attempts
        $message = $remaining > 0
            ? "These credentials do not match our records. You have {$remaining} attempt(s) remaining."
            : "These credentials do not match our records.";

        throw ValidationException::withMessages([
            'email' => $message,
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

        return redirect('/');
    }

    /**
     * Get the rate limiting throttle key.
     */
    protected function throttleKey(Request $request)
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }
}
