<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentGroupController extends Controller
{
    /**
     * =========================================================
     * SHOW JOIN FORM
     * =========================================================
     */
    public function joinForm(): View
    {
        $student = auth()->user();

        if (!$student->isStudent()) {
            abort(403);
        }

        return view('student.group.join');
    }

    /**
     * =========================================================
     * PROCESS JOIN CODE
     * =========================================================
     */
    public function join(Request $request): RedirectResponse
    {
        $student = auth()->user();

        if (!$student->isStudent()) {
            abort(403);
        }

        $validated = $request->validate([
            'join_code' => 'required|string|size:6',
        ], [
            'join_code.required' => 'Please enter a join code.',
            'join_code.size'     => 'Join code must be exactly 6 characters.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIND GROUP BY CODE (case-insensitive)
        |--------------------------------------------------------------------------
        */
        $group = Group::where(
            'join_code',
            strtoupper(trim($validated['join_code']))
        )->first();

        if (!$group) {
            return back()
                ->withInput()
                ->withErrors(['join_code' => 'Invalid join code. Please check and try again.']);
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK IF ALREADY JOINED VIA CODE (is_joined = 1)
        |--------------------------------------------------------------------------
        */
        $alreadyJoined = $group->joinedStudents()
            ->where('users.id', $student->id)
            ->exists();

        if ($alreadyJoined) {
            return back()
                ->withInput()
                ->withErrors(['join_code' => 'You have already joined "' . $group->name . '" with this code.']);
        }

        /*
        |--------------------------------------------------------------------------
        | TEACHER-ADDED (is_joined=0) — just flip the flag
        |--------------------------------------------------------------------------
        */
        $teacherAdded = $group->students()
            ->where('users.id', $student->id)
            ->exists();

        if ($teacherAdded) {
            $group->students()->updateExistingPivot($student->id, ['is_joined' => 1]);
        } else {
            // Brand new — attach with is_joined=1
            $group->students()->attach($student->id, ['is_joined' => 1]);
        }

        /*
        |--------------------------------------------------------------------------
        | AUTO-ASSIGN ANY PROJECTS TIED TO THIS GROUP
        |--------------------------------------------------------------------------
        */
        foreach ($group->projects as $project) {
            $project->assignToStudent($student);
        }

        return redirect()
            ->route('student.groups.show', $group->id)
            ->with('success', 'You have successfully joined "' . $group->name . '"!');
    }

    /**
     * =========================================================
     * SHOW GROUP
     * =========================================================
     */
    public function show(Group $group): View
    {
        $student = auth()->user();

        $group->load(['teacher', 'students', 'projects']);

        // Must have joined via code (is_joined = 1)
        $hasJoined = $group->joinedStudents()
            ->where('users.id', $student->id)
            ->exists();

        if (!$hasJoined) {
            return redirect()
                ->route('student.groups.join')
                ->with('info', 'Please enter the join code to access this group.');
        }

        return view('student.group.show', compact('group'));
    }
}