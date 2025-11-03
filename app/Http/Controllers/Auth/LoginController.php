<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // OWASP A03: Injection - Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|max:255',
            'remember' => 'boolean',
        ]);

        if ($validator->fails()) {
            // OWASP A09: Security Logging - Log validation failures
            Log::warning('Login validation failed', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'errors' => $validator->errors()->toArray()
            ]);
            
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // OWASP A04: Insecure Design - Rate limiting is handled by middleware
        // OWASP A07: Identification & Authentication Failures - Check if user exists and is active
        $user = \App\Models\User::where('email', $request->email)->first();
        
        if (!$user || !$user->isActive()) {
            // OWASP A09: Security Logging - Log failed login attempts
            Log::warning('Login attempt with inactive/non-existent user', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'exists' => $user ? true : false,
                'active' => $user ? $user->isActive() : false
            ]);

            // Use generic error message to prevent user enumeration
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // OWASP A07: Identification & Authentication Failures - Verify password
        if (!Hash::check($request->password, $user->password)) {
            // OWASP A09: Security Logging - Log failed password verification
            Log::warning('Login attempt with invalid password', [
                'user_id' => $user->id,
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // OWASP A07: Identification & Authentication Failures - Update last login info
        $user->updateLastLogin($request->ip());

        // OWASP A09: Security Logging - Log successful login
        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            // OWASP A08: Software & Data Integrity Failures - Regenerate session ID
            $request->session()->regenerateToken();

            return redirect()->intended(route('dashboard'));
        }

        // This should not be reached due to previous checks, but included as fallback
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // OWASP A09: Security Logging - Log logout
        Log::info('User logged out', [
            'user_id' => Auth::id(),
            'email' => Auth::user()->email,
            'ip' => $request->ip()
        ]);

        Auth::logout();

        // OWASP A08: Software & Data Integrity Failures - Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function validateLogin(Request $request)
    {
        return $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }
}