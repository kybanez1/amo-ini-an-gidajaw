<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherAuthController extends Controller
{
    public function dashboard()
    {
        $teacher = auth()->user();

        $totalProjects = $teacher->projects()->count();

        $totalGroups = $teacher->groups()->count();

        $totalStudents = User::where('role', 'student')
            ->whereHas('studentGroups', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->count();

        return view('teacher.dashboard', compact(
            'totalProjects',
            'totalGroups',
            'totalStudents'
        ));
    }

    public function showLoginForm()
    {
        return view('auth.teacher-login');
    }

    /**
     * Handle teacher login
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

            // Check if user is actually a teacher
            if ($user->role === 'teacher') {
                $request->session()->regenerate();
                return redirect()->intended(route('teacher.dashboard'))->with('success', 'Welcome back, ' . $user->name . '!');
            }

            // If not a teacher, logout
            Auth::logout();
            return back()->withErrors(['email' => 'You are not registered as a teacher. Please use the student login.']);
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    /**
     * Show teacher register form
     */
    public function showRegisterForm()
    {
        return view('auth.teacher-register');
    }

    /**
     * Handle teacher registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'department' => 'nullable|string|max:255',
        ]);

        // Create user as teacher
        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'teacher',
            'status' => 'active',
            'department' => $validated['department'] ?? null
        ]);

        // Login the user
        Auth::login($user);

        return redirect()->route('teacher.dashboard')->with('success', 'Registration successful! Welcome ' . $user->name);
    }

    /**
     * Handle teacher logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
