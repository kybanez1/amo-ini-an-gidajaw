<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\ProjectSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Show all projects
     */
    public function index(): View
    {
        $teacher = auth()->user();

        if (!$teacher || !$teacher->isTeacher()) {
            abort(403, 'Unauthorized');
        }

        $projects = $teacher->projects()
            ->with([
                'group',
                'assignments',
                'tasks',
            ])
            ->latest()
            ->paginate(10);

        return view(
            'teacher.projects.index',
            compact('projects')
        );
    }

    /**
     * Create form
     */
    public function create(): View
    {
        $teacher = auth()->user();

        if (!$teacher || !$teacher->isTeacher()) {
            abort(403, 'Unauthorized');
        }

        $groups = $teacher->groups()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view(
            'teacher.projects.create',
            compact('groups')
        );
    }

    /**
     * Store project
     */
    public function store(Request $request): RedirectResponse
    {
        $teacher = auth()->user();

        if (!$teacher || !$teacher->isTeacher()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([

            'title' => 'required|string|max:255',

            'description' => 'nullable|string|max:5000',

            'requirements' => 'nullable|string|max:5000',

            'group_id'    => 'nullable|exists:groups,id',
            'student_ids'   => 'nullable|array',
            'student_ids.*' => 'exists:users,id',

            'start_date' => 'nullable|date',

            'due_date' => 'nullable|date',

            'max_score' => 'required|integer|min:1|max:1000',

            /*
            |--------------------------------------------------------------------------
            | TASKS
            |--------------------------------------------------------------------------
            */
            'tasks' => 'nullable|array',

            'tasks.*.title' => 'nullable|string|max:255',

            'tasks.*.description' => 'nullable|string',

            'tasks.*.due_date'     => 'nullable|date',
            'tasks.*.max_points'  => 'nullable|integer|min:1|max:10000',

            'instruction_file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,png,jpg,jpeg|max:20480',
            'instruction_link' => 'nullable|url|max:2048',
        ]);

        /*
        |--------------------------------------------------------------------------
        | VERIFY GROUP OWNERSHIP
        |--------------------------------------------------------------------------
        */
        if (!empty($validated['group_id'])) {

            $group = Group::find($validated['group_id']);

            if (
                !$group ||
                $group->teacher_id !== $teacher->id
            ) {
                abort(403, 'Unauthorized');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE PROJECT
        |--------------------------------------------------------------------------
        */
        /*
        |--------------------------------------------------------------------------
        | HANDLE INSTRUCTION FILE UPLOAD
        |--------------------------------------------------------------------------
        */
        $instructionFilePath = null;
        $instructionFileName = null;

        if ($request->hasFile('instruction_file')) {
            $file = $request->file('instruction_file');
            $instructionFilePath = $file->store('instruction_files', 'public');
            $instructionFileName = $file->getClientOriginalName();
        }

        $project = $teacher->projects()->create([

            'title' => $validated['title'],

            'description' => $validated['description'] ?? null,

            'requirements' => $validated['requirements'] ?? null,

            'group_id' => $validated['group_id'] ?? null,

            'start_date' => $validated['start_date'] ?? null,

            'due_date' => $validated['due_date'] ?? null,

            'status' => 'pending',

            'max_score' => $validated['max_score'],

            'instruction_file'             => $instructionFilePath,
            'instruction_file_name'        => $instructionFileName,
            'instruction_file_uploaded_at' => $instructionFilePath ? now() : null,
            'instruction_link'             => !$instructionFilePath ? ($validated['instruction_link'] ?? null) : null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | SAVE TASKS
        |--------------------------------------------------------------------------
        */
        if (!empty($validated['tasks'])) {

            foreach ($validated['tasks'] as $taskData) {

                /*
                |--------------------------------------------------------------------------
                | SKIP EMPTY TASK
                |--------------------------------------------------------------------------
                */
                if (
                    empty($taskData['title']) &&
                    empty($taskData['description'])
                ) {
                    continue;
                }

                $project->tasks()->create([
                    'title'       => $taskData['title'] ?? 'Untitled Task',
                    'description' => $taskData['description'] ?? null,
                    'due_date'    => $taskData['due_date'] ?? null,
                    'status'      => 'pending',
                    'score'       => 0,
                    'max_points'  => $taskData['max_points'] ?? 100,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | AUTO ASSIGN GROUP
        |--------------------------------------------------------------------------
        */
        if ($project->group) {
            $project->assignToGroup($project->group);
        }

        /*
        |--------------------------------------------------------------------------
        | ASSIGN INDIVIDUAL STUDENTS (when no group is selected)
        |--------------------------------------------------------------------------
        */
        if (!empty($validated['student_ids'])) {
            foreach ($validated['student_ids'] as $studentId) {
                $student = User::find($studentId);
                if ($student && $student->role === 'student') {
                    $project->assignToStudent($student);
                }
            }
        }

        return redirect()
            ->route(
                'teacher.projects.show',
                $project->id
            )
            ->with(
                'success',
                'Project created successfully!'
            );
    }

    /**
     * Show project
     */
    public function show(Project $project): View
    {
        $teacher = auth()->user();

        if (
            !$teacher ||
            $project->teacher_id !== $teacher->id
        ) {
            abort(403, 'Unauthorized');
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD RELATIONS
        |--------------------------------------------------------------------------
        */
        $project->load([
            'group',
            'assignments',
            'tasks',
            'teacher',
        ]);

        $assignments = $project->assignments()
            ->paginate(15);

        /*
        |--------------------------------------------------------------------------
        | SUBMISSIONS
        |--------------------------------------------------------------------------
        */
        // Only show task submissions (whereNotNull task_id) to avoid confusing
        // "General Submission" duplicates. Null-task_id records are legacy/internal only.
        $submissions = ProjectSubmission::where('project_id', $project->id)
            ->whereNotNull('task_id')
            ->with(['student', 'task'])
            ->latest()
            ->paginate(15);

        $submittedCount = $project->getSubmittedCount();

        $gradedCount = $project->getGradedCount();

        return view(
            'teacher.projects.show',
            compact(
                'project',
                'assignments',
                'submissions',
                'submittedCount',
                'gradedCount'
            )
        );
    }

    /**
     * Edit form
     */
    public function edit(Project $project): View
    {
        $teacher = auth()->user();

        if (
            !$teacher ||
            $project->teacher_id !== $teacher->id
        ) {
            abort(403, 'Unauthorized');
        }

        $project->load('tasks');

        $groups = $teacher->groups()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view(
            'teacher.projects.update_project',
            compact(
                'project',
                'groups'
            )
        );
    }

    /**
     * Update project
     */
    public function update(
        Request $request,
        Project $project
    ): RedirectResponse {

        $teacher = auth()->user();

        if (
            !$teacher ||
            $project->teacher_id !== $teacher->id
        ) {
            abort(403, 'Unauthorized');
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDATE
        |--------------------------------------------------------------------------
        */
        $validated = $request->validate([

            'title' => 'required|string|max:255',

            'description' => 'nullable|string|max:5000',

            'requirements' => 'nullable|string|max:5000',

            'group_id' => 'nullable|exists:groups,id',

            'start_date' => 'nullable|date',

            'due_date' => 'nullable|date',

            /*
            |--------------------------------------------------------------------------
            | FIXED STATUS VALUES
            |--------------------------------------------------------------------------
            */
            'status' => 'required|in:pending,draft,published,ongoing,active,completed,archived',

            'max_score' => 'required|integer|min:1|max:1000',

            /*
            |--------------------------------------------------------------------------
            | TASKS
            |--------------------------------------------------------------------------
            */
            'tasks' => 'nullable|array',

            'tasks.*.id' => 'nullable|exists:tasks,id',

            'tasks.*.title' => 'nullable|string|max:255',

            'tasks.*.description' => 'nullable|string',

            'tasks.*.due_date'     => 'nullable|date',
            'tasks.*.max_points'  => 'nullable|integer|min:1|max:10000',

            'instruction_file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,png,jpg,jpeg|max:20480',
            'instruction_link' => 'nullable|url|max:2048',
        ]);

        /*
        |--------------------------------------------------------------------------
        | VERIFY GROUP
        |--------------------------------------------------------------------------
        */
        if (!empty($validated['group_id'])) {

            $group = Group::find(
                $validated['group_id']
            );

            if (
                !$group ||
                $group->teacher_id !== $teacher->id
            ) {
                abort(403, 'Unauthorized');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SAFE STATUS
        |--------------------------------------------------------------------------
        */
        $allowedStatuses = [
            'pending',
            'draft',
            'published',
            'ongoing',
            'active',
            'completed',
            'archived',
        ];

        $status = in_array(
            $validated['status'],
            $allowedStatuses
        )
            ? $validated['status']
            : 'pending';

        /*
        |--------------------------------------------------------------------------
        | HANDLE INSTRUCTION FILE UPLOAD
        |--------------------------------------------------------------------------
        */
        $instructionFilePath = $project->instruction_file;
        $instructionFileName = $project->instruction_file_name;
        $instructionUploadedAt = $project->instruction_file_uploaded_at;

        if ($request->hasFile('instruction_file')) {
            // Delete old file if exists
            if ($project->instruction_file) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($project->instruction_file);
            }
            $file = $request->file('instruction_file');
            $instructionFilePath = $file->store('instruction_files', 'public');
            $instructionFileName = $file->getClientOriginalName();
            $instructionUploadedAt = now();
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE PROJECT
        |--------------------------------------------------------------------------
        */
        $project->update([

            'title' => $validated['title'],

            'description' => $validated['description'] ?? null,

            'requirements' => $validated['requirements'] ?? null,

            'group_id' => $validated['group_id'] ?? null,

            'start_date' => $validated['start_date']
                ?? $project->start_date,

            'due_date' => $validated['due_date']
                ?? $project->due_date,

            'status' => $status,

            'max_score' => $validated['max_score'],

            'instruction_file'             => $instructionFilePath,
            'instruction_file_name'        => $instructionFileName,
            'instruction_file_uploaded_at' => $instructionUploadedAt,
            'instruction_link'             => !$instructionFilePath ? ($validated['instruction_link'] ?? null) : null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE / CREATE TASKS
        |--------------------------------------------------------------------------
        */
        if (!empty($validated['tasks'])) {

            foreach ($validated['tasks'] as $taskData) {

                /*
                |--------------------------------------------------------------------------
                | SKIP EMPTY TASK
                |--------------------------------------------------------------------------
                */
                if (
                    empty($taskData['title']) &&
                    empty($taskData['description'])
                ) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | UPDATE EXISTING TASK
                |--------------------------------------------------------------------------
                */
                if (!empty($taskData['id'])) {

                    $task = Task::where(
                            'project_id',
                            $project->id
                        )
                        ->where(
                            'id',
                            $taskData['id']
                        )
                        ->first();

                    if ($task) {

                        $task->update([
                            'title'       => $taskData['title'] ?? 'Untitled Task',
                            'description' => $taskData['description'] ?? null,
                            'due_date'    => $taskData['due_date'] ?? null,
                            'max_points'  => $taskData['max_points'] ?? $task->max_points ?? 100,
                        ]);
                    }

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | CREATE NEW TASK
                    |--------------------------------------------------------------------------
                    */
                    $project->tasks()->create([
                        'title'       => $taskData['title'] ?? 'Untitled Task',
                        'description' => $taskData['description'] ?? null,
                        'due_date'    => $taskData['due_date'] ?? null,
                        'status'      => 'pending',
                        'score'       => 0,
                        'max_points'  => $taskData['max_points'] ?? 100,
                    ]);
                }
            }
        }

        return redirect()
            ->route(
                'teacher.projects.show',
                $project->id
            )
            ->with(
                'success',
                'Project updated successfully!'
            );
    }

    /**
     * Assign to group
     */
    public function assignToGroup(
        Request $request,
        Project $project
    ): RedirectResponse {

        $teacher = auth()->user();

        if (
            !$teacher ||
            $project->teacher_id !== $teacher->id
        ) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);

        $group = Group::find(
            $validated['group_id']
        );

        if (
            !$group ||
            $group->teacher_id !== $teacher->id
        ) {
            abort(403, 'Unauthorized');
        }

        $project->assignToGroup($group);

        return back()->with(
            'success',
            'Project assigned successfully!'
        );
    }

    /**
     * Assign to student
     */
    public function assignToStudent(
        Request $request,
        Project $project
    ): RedirectResponse {

        $teacher = auth()->user();

        if (
            !$teacher ||
            $project->teacher_id !== $teacher->id
        ) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $student = User::find(
            $validated['student_id']
        );

        if (
            !$student ||
            $student->role !== 'student'
        ) {
            return back()->with(
                'error',
                'Invalid student.'
            );
        }

        $project->assignToStudent($student);

        return back()->with(
            'success',
            'Student assigned successfully!'
        );
    }

    /**
     * Remove student
     */
    public function removeStudent(
        Project $project,
        User $student
    ): RedirectResponse {

        $teacher = auth()->user();

        if (
            !$teacher ||
            $project->teacher_id !== $teacher->id
        ) {
            abort(403, 'Unauthorized');
        }

        $project->students()->detach(
            $student->id
        );

        return back()->with(
            'success',
            'Student removed successfully!'
        );
    }

    /**
     * Delete project
     */
    public function destroy(
        Project $project
    ): RedirectResponse {

        $teacher = auth()->user();

        if (
            !$teacher ||
            $project->teacher_id !== $teacher->id
        ) {
            abort(403, 'Unauthorized');
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE TASKS
        |--------------------------------------------------------------------------
        */
        $project->tasks()->delete();

        /*
        |--------------------------------------------------------------------------
        | DELETE PROJECT
        |--------------------------------------------------------------------------
        */
        $project->delete();

        return redirect()
            ->route('teacher.projects.index')
            ->with(
                'success',
                'Project deleted successfully!'
            );
    }
}