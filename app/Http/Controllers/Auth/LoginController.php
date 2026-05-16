<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Unified login — one form, role auto-detected from email prefix.
 * Teacher accounts use institutional email (contains @school.edu or a teacher_ prefix).
 * The form uses a hidden tab switcher on the frontend; the POST route is role-specific.
 * This controller handles the root "/" unified page which just renders the view.
 */
class LoginController extends Controller
{
    /**
     * Show the unified login form (one page, teacher + student tabs).
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Attempt login for either role — used when role is known from the active tab.
     * The frontend submits to either route('teacher.login') or route('student.login')
     * so this method is a fallback smart-login for the generic /login route.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'teacher') {
                return redirect()->intended(route('teacher.dashboard'));
            }

            return redirect()->intended(route('student.dashboard'));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    /**
     * Log out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}