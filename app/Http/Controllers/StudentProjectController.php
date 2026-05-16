<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectSubmission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class StudentProjectController extends Controller
{
    /**
     * CHECK ACCESS
     */
    private function canAccessProject($student, $project): bool
    {
        /*
        |--------------------------------------------------------------------------
        | DIRECT ASSIGNMENT
        |--------------------------------------------------------------------------
        */
        $assigned = $student->assignedProjects()
            ->where('projects.id', $project->id)
            ->exists();

        /*
        |--------------------------------------------------------------------------
        | GROUP ACCESS — only if student joined via code (is_joined = 1)
        |--------------------------------------------------------------------------
        */
        $groupAccess = false;

        if ($project->group_id) {

            $groupAccess = DB::table('group_student')
                ->where('group_id', $project->group_id)
                ->where('student_id', $student->id)
                ->where('is_joined', 1)
                ->exists();
        }

        return $assigned || $groupAccess;
    }

    /**
     * ALL PROJECTS
     */
    public function index(): View
    {
        $student = auth()->user();

        if (!$student->isStudent()) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | GET GROUP IDS
        |--------------------------------------------------------------------------
        */
        // Only groups student has actively joined via code
        $groupIds = DB::table('group_student')
            ->where('student_id', $student->id)
            ->where('is_joined', 1)
            ->pluck('group_id');

        /*
        |--------------------------------------------------------------------------
        | GET PROJECTS
        |--------------------------------------------------------------------------
        */
        $assignedProjects = Project::with([
                'teacher',
                'group',
                'tasks',
            ])
            ->where(function ($query) use ($student, $groupIds) {

                /*
                |--------------------------------------------------------------------------
                | GROUP PROJECTS
                |--------------------------------------------------------------------------
                */
                if ($groupIds->count()) {

                    $query->orWhereIn(
                        'group_id',
                        $groupIds
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | DIRECT ASSIGNMENTS
                |--------------------------------------------------------------------------
                */
                $query->orWhereHas(
                    'assignments',
                    function ($q) use ($student) {

                        $q->where(
                            'project_student.student_id',
                            $student->id
                        );
                    }
                );
            })
            ->latest()
            ->get();

        // Status filter
        $statusFilter = request('status');
        if ($statusFilter) {
            $assignedProjects = $assignedProjects->filter(function ($p) use ($statusFilter) {
                $s = $p->pivot->assignment_status ?? 'assigned';
                return $s === $statusFilter;
            })->values();
        }

        return view(
            'student.projects.index',
            compact('assignedProjects')
        );
    }

    /**
     * SHOW PROJECT
     */
    public function show(Project $project): View
    {
        $student = auth()->user();

        abort_unless(
            $this->canAccessProject($student, $project),
            403
        );

        // Eager-load tasks so the view can iterate them
        $project->loadMissing(['teacher', 'tasks', 'group']);

        /*
        |--------------------------------------------------------------------------
        | LOAD SUBMISSIONS — keyed by task_id so view can do $submissions->get($task->id)
        |--------------------------------------------------------------------------
        */
        $submissions = ProjectSubmission::where('student_id', $student->id)
            ->where('project_id', $project->id)
            ->get()
            ->keyBy('task_id');

        return view(
            'student.projects.show',
            compact(
                'project',
                'submissions'
            )
        );
    }

    /**
     * SUBMIT PAGE
     */
    public function submitForm(
        Project $project,
        Request $request
    ): View {

        $student = auth()->user();

        if (!$student->isStudent()) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | ACCESS CHECK
        |--------------------------------------------------------------------------
        */
        abort_unless(
            $this->canAccessProject($student, $project),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | TASK
        |--------------------------------------------------------------------------
        */
        $task = null;

        if ($request->task) {

            $task = $project->tasks()
                ->where(
                    'id',
                    $request->task
                )
                ->firstOrFail();
        }

        /*
        |--------------------------------------------------------------------------
        | SUBMISSION
        |--------------------------------------------------------------------------
        */
        $submission = ProjectSubmission::where([
                'project_id' => $project->id,
                'student_id' => $student->id,
                'task_id'    => $task?->id,
            ])
            ->latest()
            ->first();

        return view(
            'student.projects.submit',
            compact(
                'project',
                'task',
                'submission'
            )
        );
    }

    /**
     * FINALIZE SUBMISSION
     */
    public function finalize(
        Request $request,
        Project $project
    ): RedirectResponse {

        $student = auth()->user();

        if (!$student->isStudent()) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | ACCESS CHECK
        |--------------------------------------------------------------------------
        */
        abort_unless(
            $this->canAccessProject($student, $project),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDATE
        |--------------------------------------------------------------------------
        */
        $validated = $request->validate([

            'task_id' => 'nullable|exists:tasks,id',

            'content' => 'nullable|string|max:10000',

            'file' => 'nullable|file|max:10240',
        ]);

        $taskId =
            $validated['task_id'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | FIND SUBMISSION — null-safe task_id so each task tracked separately
        |--------------------------------------------------------------------------
        */
        $submissionQuery = ProjectSubmission::where('project_id', $project->id)
            ->where('student_id', $student->id);

        if ($taskId) {
            $submissionQuery->where('task_id', $taskId);
        } else {
            $submissionQuery->whereNull('task_id');
        }

        $submission = $submissionQuery->first();

        /*
        |--------------------------------------------------------------------------
        | FILE
        |--------------------------------------------------------------------------
        */
        $filePath = $submission->file_path ?? null;

        if ($request->hasFile('file')) {

            $filePath = $request->file('file')->store(
                'submissions/' . $project->id,
                'public'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CONTENT
        |--------------------------------------------------------------------------
        */
        $content =
            $validated['content'] ??
            ($submission->content ?? null);

        /*
        |--------------------------------------------------------------------------
        | EMPTY CHECK
        |--------------------------------------------------------------------------
        */
        if (
            empty(trim($content ?? '')) &&
            empty($filePath)
        ) {
            return back()->with(
                'error',
                'Please upload a file or write content.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE OR CREATE
        |--------------------------------------------------------------------------
        */
        if ($submission) {

            $submission->update([

                'content' => $content,

                'file_path' => $filePath,

                'status' => 'submitted',

                'submitted_at' => now(),
            ]);

        } else {

            ProjectSubmission::create([

                'project_id' => $project->id,

                'task_id' => $taskId,

                'student_id' => $student->id,

                'content' => $content,

                'file_path' => $filePath,

                'status' => 'submitted',

                'submitted_at' => now(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */
        return redirect()
            ->route(
                'student.projects.show',
                $project->id
            )
            ->with(
                'success',
                'Task submitted successfully.'
            );
    }
}