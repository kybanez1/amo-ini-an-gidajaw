<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GradeController extends Controller
{
    /**
     * =========================================================
     * GRADED PROJECTS PAGE — scoped to this teacher
     * =========================================================
     */
    public function gradedProjects(): View
    {
        $teacher = auth()->user();

        if (!$teacher || !$teacher->isTeacher()) {
            abort(403, 'Unauthorized');
        }

        $gradedSubmissions = Project::with(['assignments'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('assignments', function ($q) {
                $q->where('assignment_status', 'graded');
            })
            ->latest()
            ->paginate(10);

        return view('teacher.projects.graded', [
            'gradedSubmissions' => $gradedSubmissions,
        ]);
    }

    /**
     * =========================================================
     * SHOW GRADING FORM
     * =========================================================
     */
    public function edit(
        Project $project,
        $studentId
    ): View {

        $teacher = auth()->user();

        if (
            !$teacher ||
            !$teacher->isTeacher() ||
            $project->teacher_id !== $teacher->id
        ) {
            abort(403, 'Unauthorized');
        }

        $student = User::where([
            'id'   => $studentId,
            'role' => 'student',
        ])->firstOrFail();

        $submission = ProjectSubmission::where([
            'project_id' => $project->id,
            'student_id' => $studentId,
        ])->latest()->first();

        if (!$submission) {
            return redirect()
                ->back()
                ->with('error', 'Student has not submitted this project yet.');
        }

        /*
        |--------------------------------------------------------------------------
        | GET PIVOT — null-safe: create a default if not assigned yet
        |--------------------------------------------------------------------------
        */
        $assignment = $project->assignments()
            ->where('users.id', $studentId)
            ->first();

        if (!$assignment) {

            // Auto-assign so grading can proceed
            $project->assignToStudent($student);

            $assignment = $project->assignments()
                ->where('users.id', $studentId)
                ->first();
        }

        return view(
            'teacher.grade.edit',
            compact('project', 'student', 'submission', 'assignment')
        );
    }

    /**
     * =========================================================
     * STORE GRADE
     * =========================================================
     */
    public function store(
        Request $request,
        Project $project,
        $studentId
    ): RedirectResponse {

        $teacher = auth()->user();

        if (
            !$teacher ||
            !$teacher->isTeacher() ||
            $project->teacher_id !== $teacher->id
        ) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'score' => [
                'required',
                'numeric',
                'min:0',
                'max:' . $project->max_score,
            ],
            'feedback' => 'nullable|string|max:5000',
        ]);

        $submission = ProjectSubmission::where([
            'project_id' => $project->id,
            'student_id' => $studentId,
        ])->latest()->first();

        if (!$submission) {
            return back()->with('error', 'Submission not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE PIVOT
        |--------------------------------------------------------------------------
        */
        $project->assignments()->updateExistingPivot(
            $studentId,
            [
                'assignment_status' => 'graded',
                'score'             => $validated['score'],
                'feedback'          => $validated['feedback'] ?? null,
                'graded_at'         => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | UPDATE SUBMISSION — use 'graded' (not 'reviewed') for consistency
        |--------------------------------------------------------------------------
        */
        $submission->update([
            'status'    => 'graded',
            'score'     => $validated['score'],
            'feedback'  => $validated['feedback'] ?? null,
            'graded_at' => now(),
        ]);

        return redirect()
            ->route('teacher.projects.show', $project->id)
            ->with('success', 'Student graded successfully!');
    }

    /**
     * =========================================================
     * SHOW PROJECT GRADING PAGE
     * =========================================================
     */
    public function project(Project $project): View
    {
        $teacher = auth()->user();

        if (
            !$teacher ||
            !$teacher->isTeacher() ||
            $project->teacher_id !== $teacher->id
        ) {
            abort(403, 'Unauthorized');
        }

        $submissions = ProjectSubmission::where('project_id', $project->id)
            ->with('student')
            ->latest()
            ->get();

        $stats = [
            'total'     => $project->assignments()->count(),
            'submitted' => $project->assignments()
                ->wherePivot('assignment_status', 'submitted')
                ->count(),
            'graded'    => $project->assignments()
                ->wherePivot('assignment_status', 'graded')
                ->count(),
            'pending'   => $project->assignments()
                ->wherePivotIn('assignment_status', ['assigned', 'draft'])
                ->count(),
        ];

        return view(
            'teacher.grade.project',
            compact('project', 'submissions', 'stats')
        );
    }

    /**
     * =========================================================
     * BULK UPDATE GRADES
     * =========================================================
     */
    public function bulkUpdate(
        Request $request,
        Project $project
    ): RedirectResponse {

        $teacher = auth()->user();

        if (
            !$teacher ||
            !$teacher->isTeacher() ||
            $project->teacher_id !== $teacher->id
        ) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'grades'                => 'required|array',
            'grades.*.student_id'   => 'required|exists:users,id',
            'grades.*.score'        => [
                'required',
                'numeric',
                'min:0',
                'max:' . $project->max_score,
            ],
            'grades.*.feedback'     => 'nullable|string|max:5000',
        ]);

        foreach ($validated['grades'] as $gradeData) {

            $project->assignments()->updateExistingPivot(
                $gradeData['student_id'],
                [
                    'assignment_status' => 'graded',
                    'score'             => $gradeData['score'],
                    'feedback'          => $gradeData['feedback'] ?? null,
                    'graded_at'         => now(),
                ]
            );

            $submission = ProjectSubmission::where([
                'project_id' => $project->id,
                'student_id' => $gradeData['student_id'],
            ])->latest()->first();

            if ($submission) {
                $submission->update([
                    'status'    => 'graded',
                    'score'     => $gradeData['score'],
                    'feedback'  => $gradeData['feedback'] ?? null,
                    'graded_at' => now(),
                ]);
            }
        }

        return redirect()
            ->route('teacher.projects.show', $project->id)
            ->with('success', 'All grades updated successfully!');
    }
}