<?php

namespace App\Http\Controllers;

use App\Models\ProjectSubmission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectSubmissionController extends Controller
{
    /**
     * VIEW SUBMISSION
     */
    public function show(
        ProjectSubmission $submission
    ): View {

        $teacher = auth()->user();

        if (
            !$teacher ||
            !$teacher->isTeacher()
        ) {
            abort(403);
        }

        $submission->load([
            'student',
            'project',
            'task',
        ]);

        return view(
            'teacher.submissions.show',
            compact('submission')
        );
    }

    /**
     * GRADE FORM
     */
    public function gradeForm(
        ProjectSubmission $submission
    ): View {

        $teacher = auth()->user();

        if (
            !$teacher ||
            !$teacher->isTeacher()
        ) {
            abort(403);
        }

        $submission->load([
            'student',
            'project',
            'task',
        ]);

        return view(
            'teacher.submissions.grade',
            compact('submission')
        );
    }

    /**
     * SAVE GRADE
     */
    public function grade(
        Request $request,
        ProjectSubmission $submission
    ): RedirectResponse {

        $teacher = auth()->user();

        if (
            !$teacher ||
            !$teacher->isTeacher()
        ) {
            abort(403);
        }

        $submission->load(['project', 'task']);

        $maxTaskPoints = $submission->task ? ($submission->task->max_points ?? 100) : null;

        $validated = $request->validate([

            'score' => [
                'required',
                'numeric',
                'min:0',
                'max:' . $submission->project->max_score,
            ],

            'task_score' => array_filter([
                'nullable',
                'numeric',
                'min:0',
                $maxTaskPoints ? 'max:' . $maxTaskPoints : null,
            ]),

            'feedback' => 'nullable|string|max:5000',
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE SUBMISSION RECORD
        |--------------------------------------------------------------------------
        */
        $submission->update([
            'status'     => 'graded',
            'score'      => $validated['score'],
            'task_score' => isset($validated['task_score']) ? $validated['task_score'] : null,
            'feedback'   => $validated['feedback'] ?? null,
            'graded_at'  => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | SYNC GRADE TO PIVOT (so student grades page shows the score)
        |--------------------------------------------------------------------------
        */
        $submission->project->assignments()->updateExistingPivot(
            $submission->student_id,
            [
                'assignment_status' => 'graded',
                'score'             => $validated['score'],
                'feedback'          => $validated['feedback'] ?? null,
                'graded_at'         => now(),
            ]
        );

        return redirect()
            ->route(
                'teacher.submissions.show',
                $submission->id
            )
            ->with(
                'success',
                'Submission graded successfully.'
            );
    }
}