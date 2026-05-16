<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    /**
     * Show student dashboard
     */
    public function index(): View
    {
        $student = auth()->user();

        if (!$student->isStudent()) {
            abort(403, 'Unauthorized: Only students can access this page.');
        }

        $assignedProjects = $student->assignedProjects()
                                   ->with('teacher')
                                   ->orderBy('due_date', 'asc')
                                   ->get();

        $totalProjects = $assignedProjects->count();
        $submittedCount = $assignedProjects->filter(function ($project) {
            return in_array($project->pivot->assignment_status, ['submitted', 'graded']);
        })->count();
        $pendingCount = $assignedProjects->filter(function ($project) {
            return $project->pivot->assignment_status === 'assigned';
        })->count();

        // Only show groups student actively joined via code
        $groups = $student->joinedGroups()->with('teacher')->get();

        $recentSubmissions = $student->submissions()
                                    ->with(['project', 'project.teacher'])
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();

        $gradedProjects = $assignedProjects->filter(function ($project) {
            return $project->pivot->assignment_status === 'graded' && $project->pivot->score !== null;
        });

        $averageScore = $gradedProjects->count() > 0
            ? round($gradedProjects->avg('pivot.score'), 2)
            : 0;

        return view('student.dashboard', [
            'assignedProjects' => $assignedProjects,
            'totalProjects'    => $totalProjects,
            'submittedCount'   => $submittedCount,
            'pendingCount'     => $pendingCount,
            'groups'           => $groups,
            'recentSubmissions' => $recentSubmissions,
            'averageScore'     => $averageScore,
            'student'          => $student,
        ]);
    }

    /**
     * Show My Grades page
     */
    public function grades(): View
    {
        $student = auth()->user();

        if (!$student->isStudent()) {
            abort(403);
        }

        $gradedProjects = $student->assignedProjects()
            ->with('teacher')
            ->wherePivot('assignment_status', 'graded')
            ->orderBy('due_date', 'desc')
            ->get();

        $averageScore = $gradedProjects->count() > 0
            ? round($gradedProjects->avg('pivot.score'), 2)
            : null;

        return view('student.grades', compact('gradedProjects', 'averageScore', 'student'));
    }
}
