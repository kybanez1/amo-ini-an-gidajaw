<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentTeacherController extends Controller
{
    /**
     * SHOW — enter teacher code form
     */
    public function joinForm(): View
    {
        $student = auth()->user();

        if (!$student->isStudent()) {
            abort(403);
        }

        $myTeachers = $student->myTeachers()->get();

        return view('student.teacher.join', compact('myTeachers'));
    }

    /**
     * POST — process teacher code
     */
    public function join(Request $request): RedirectResponse
    {
        $student = auth()->user();

        if (!$student->isStudent()) {
            abort(403);
        }

        $validated = $request->validate([
            'teacher_code' => 'required|string|size:6',
        ], [
            'teacher_code.required' => 'Please enter a teacher code.',
            'teacher_code.size'     => 'Teacher code must be exactly 6 characters.',
        ]);

        /*
        |----------------------------------------------------------------------
        | FIND TEACHER BY CODE
        |----------------------------------------------------------------------
        */
        $teacher = User::where('role', 'teacher')
            ->where(
                'teacher_code',
                strtoupper(trim($validated['teacher_code']))
            )
            ->first();

        if (!$teacher) {
            return back()
                ->withInput()
                ->withErrors([
                    'teacher_code' => 'Invalid teacher code. Please check and try again.',
                ]);
        }

        /*
        |----------------------------------------------------------------------
        | ALREADY REGISTERED UNDER THIS TEACHER
        |----------------------------------------------------------------------
        */
        $alreadyRegistered = $teacher->myStudents()
            ->where('users.id', $student->id)
            ->exists();

        if ($alreadyRegistered) {
            return back()
                ->withInput()
                ->withErrors([
                    'teacher_code' => 'You are already registered under ' . $teacher->name . '.',
                ]);
        }

        /*
        |----------------------------------------------------------------------
        | REGISTER STUDENT UNDER TEACHER
        |----------------------------------------------------------------------
        */
        $teacher->myStudents()->attach($student->id);

        return redirect()
            ->route('student.teacher.join')
            ->with('success', 'You are now registered under ' . $teacher->name . '!');
    }
}