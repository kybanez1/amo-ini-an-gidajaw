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
     * GRADED PROJECTS PAGE
     */
    public function gradedProjects(): View
    {
        $teacher = auth()->user();

        if (!$teacher || !$teacher->isTeacher()) {
            abort(403, 'Unauthorized');
        }

        $gradedProjects = Project::with(['group', 'submissions'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('submissions', function ($q) {
                $q->where('status', 'graded');
            })
            ->latest()
            ->paginate(10);

        return view('teacher.projects.graded', [
            'gradedSubmissions' => $gradedProjects,
        ]);
    }

    /**
     * SHOW PROJECT GRADING PAGE (group/project grade)
     */
    public function project(Project $project): View
    {
        $teacher = auth()->user();

        if (!$teacher || !$teacher->isTeacher() || $project->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized');
        }

        // Get the group and its students
        $group = $project->group()->with('students')->first();

        // Get all submissions for this project
        $submissions = ProjectSubmission::where('project_id', $project->id)
            ->with('student')
            ->latest()
            ->get();

        // Check if project already has a group grade
        $groupGrade = $submissions->first(); // use first submission's grade as group grade

        $stats = [
            'total'     => $group ? $group->students()->count() : 0,
            'submitted' => $submissions->count(),
            'graded'    => $submissions->where('status', 'graded')->count(),
            'pending'   => max(0, ($group ? $group->students()->count() : 0) - $submissions->count()),
        ];

        return view(
            'teacher.grade.project',
            compact('project', 'submissions', 'stats', 'group', 'groupGrade')
        );
    }

    /**
     * STORE GROUP/PROJECT GRADE — applies one grade to all submissions in the project
     */
    public function storeProjectGrade(
        Request $request,
        Project $project
    ): RedirectResponse {

        $teacher = auth()->user();

        if (!$teacher || !$teacher->isTeacher() || $project->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'score'    => ['required', 'numeric', 'min:0', 'max:' . $project->max_score],
            'feedback' => 'nullable|string|max:5000',
        ]);

        // Get all students in the group
        $group = $project->group()->with('students')->first();
        $students = $group ? $group->students : collect();

        // Update or create a submission for every student with the same grade
        foreach ($students as $student) {
            $submission = ProjectSubmission::firstOrCreate(
                ['project_id' => $project->id, 'student_id' => $student->id],
                ['status' => 'graded', 'submitted_at' => now()]
            );

            $submission->update([
                'status'    => 'graded',
                'score'     => $validated['score'],
                'feedback'  => $validated['feedback'] ?? null,
                'graded_at' => now(),
            ]);

            // Also update the pivot if exists
            if ($project->assignments()->where('users.id', $student->id)->exists()) {
                $project->assignments()->updateExistingPivot($student->id, [
                    'assignment_status' => 'graded',
                    'score'             => $validated['score'],
                    'feedback'          => $validated['feedback'] ?? null,
                    'graded_at'         => now(),
                ]);
            }
        }

        return redirect()
            ->route('teacher.grades.project', $project->id)
            ->with('success', 'Project graded successfully! All group members received the same grade.');
    }

    /**
     * GRADED PROJECTS INDEX
     */
    public function gradedIndex(): View
    {
        return $this->gradedProjects();
    }

    // Keep legacy individual grade methods for backward compat
    public function edit(Project $project, $studentId): View
    {
        return $this->project($project);
    }

    public function store(Request $request, Project $project, $studentId): RedirectResponse
    {
        return $this->storeProjectGrade($request, $project);
    }

    public function bulkUpdate(Request $request, Project $project): RedirectResponse
    {
        return $this->storeProjectGrade($request, $project);
    }

    /**
     * INDIVIDUAL STUDENT GRADE FORM
     */
    public function editIndividual(Project $project, User $student): View
    {
        $teacher = auth()->user();

        if (!$teacher || !$teacher->isTeacher() || $project->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized');
        }

        // Ensure student is assigned
        $assignment = $project->assignments()->where('users.id', $student->id)->first();
        if (!$assignment) {
            $project->assignToStudent($student);
            $assignment = $project->assignments()->where('users.id', $student->id)->first();
        }

        // Get all task submissions for this student
        $taskSubmissions = ProjectSubmission::where('project_id', $project->id)
            ->where('student_id', $student->id)
            ->whereNotNull('task_id')
            ->get()
            ->keyBy('task_id');

        // General (no-task) submission
        $generalSubmission = ProjectSubmission::where('project_id', $project->id)
            ->where('student_id', $student->id)
            ->whereNull('task_id')
            ->latest()
            ->first();

        $project->loadMissing(['tasks']);

        return view('teacher.grade.individual', compact(
            'project', 'student', 'assignment', 'taskSubmissions', 'generalSubmission'
        ));
    }

    /**
     * SAVE INDIVIDUAL STUDENT GRADE
     */
    public function storeIndividual(Request $request, Project $project, User $student): RedirectResponse
    {
        $teacher = auth()->user();

        if (!$teacher || !$teacher->isTeacher() || $project->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'score'    => ['required', 'numeric', 'min:0', 'max:' . $project->max_score],
            'feedback' => 'nullable|string|max:5000',
        ]);

        // Update pivot record
        if ($project->assignments()->where('users.id', $student->id)->exists()) {
            $project->assignments()->updateExistingPivot($student->id, [
                'assignment_status' => 'graded',
                'score'             => $validated['score'],
                'feedback'          => $validated['feedback'] ?? null,
                'graded_at'         => now(),
            ]);
        } else {
            $project->assignToStudent($student);
            $project->assignments()->updateExistingPivot($student->id, [
                'assignment_status' => 'graded',
                'score'             => $validated['score'],
                'feedback'          => $validated['feedback'] ?? null,
                'graded_at'         => now(),
            ]);
        }

        // Update general submission grade if exists
        $submission = ProjectSubmission::where('project_id', $project->id)
            ->where('student_id', $student->id)
            ->whereNull('task_id')
            ->latest()
            ->first();

        if ($submission) {
            $submission->update([
                'status'    => 'graded',
                'score'     => $validated['score'],
                'feedback'  => $validated['feedback'] ?? null,
                'graded_at' => now(),
            ]);
        }

        return redirect()
            ->route('teacher.projects.show', $project->id)
            ->with('success', $student->name . ' graded successfully!');
    }

}