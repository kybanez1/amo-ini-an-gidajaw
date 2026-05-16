<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Group;
use App\Models\User;
use App\Models\ProjectSubmission;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $teacher = auth()->user();

        if (!$teacher || !$teacher->isTeacher()) {
            abort(403, 'Unauthorized');
        }

        /*
        |--------------------------------------------------------------------------
        | PROJECTS — this teacher only, latest 5
        |--------------------------------------------------------------------------
        */
        $projects = Project::with(['group', 'submissions'])
            ->where('teacher_id', $teacher->id)
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | GROUPS — this teacher only, latest 5
        |--------------------------------------------------------------------------
        */
        $groups = Group::withCount('students')
            ->where('teacher_id', $teacher->id)
            ->latest()
            ->take(5)
            ->get();

        $groupIds = $groups->pluck('id');

        /*
        |--------------------------------------------------------------------------
        | STUDENTS — who entered this teacher's code
        |--------------------------------------------------------------------------
        */
        $students = $teacher->myStudents()->get();

        /*
        |--------------------------------------------------------------------------
        | RECENTLY GRADED — scoped to this teacher, status 'graded' OR 'reviewed'
        |--------------------------------------------------------------------------
        */
        $recentlyGraded = ProjectSubmission::with(['student', 'project'])
            ->whereHas('project', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->whereIn('status', ['graded', 'reviewed'])
            ->latest()
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | GRADED COUNT — scoped to this teacher
        |--------------------------------------------------------------------------
        */
        $gradedCount = ProjectSubmission::whereHas('project', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->whereIn('status', ['graded', 'reviewed'])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | PENDING GRADES — scoped to this teacher
        |--------------------------------------------------------------------------
        */
        $pendingGrades = ProjectSubmission::whereHas('project', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->where('status', 'submitted')
            ->count();

        return view('teacher.dashboard', [
            'projects'       => $projects,
            'totalProjects'  => $projects->count(),
            'groups'         => $groups,
            'totalGroups'    => $groups->count(),
            'students'       => $students,
            'totalStudents'  => $students->count(),
            'recentlyGraded' => $recentlyGraded,
            'gradedCount'    => $gradedCount,
            'pendingGrades'  => $pendingGrades,
        ]);
    }
}