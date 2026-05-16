<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuthController extends Controller
{
    /**
     * Show student login form
     */
    public function showLoginForm()
    {
        return view('auth.student-login');
    }

    /**
     * Handle student login
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // Attempt login
        if (Auth::attempt($validated, $request->filled('remember'))) {
            $user = Auth::user();

            // Check if user is actually a student
            if ($user->role === 'student') {
                $request->session()->regenerate();
                return redirect()->intended(route('student.dashboard'))->with('success', 'Welcome back, ' . $user->name . '!');
            }

            // If not a student, logout
            Auth::logout();
            return back()->withErrors(['email' => 'You are not registered as a student. Please use the teacher login.']);
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    /**
     * Show student register form
     */
    public function showRegisterForm()
    {
        return view('auth.student-register');
    }

    /**
     * Handle student registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'student_id' => 'nullable|string|max:255|unique:users,student_id',
            'department' => 'nullable|string|max:255',
        ]);

        // Create user as student
        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'student',
            'status' => 'active',
            'student_id' => $validated['student_id'] ?? null,
            'department' => $validated['department'] ?? null
        ]);

        // Login the user
        Auth::login($user);

        return redirect()->route('student.dashboard')->with('success', 'Registration successful! Welcome ' . $user->name);
    }

    /**
     * Handle student logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
