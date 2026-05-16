<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Project;
use App\Models\Group;
use App\Models\Submission;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | TEACHER PROJECTS
        |--------------------------------------------------------------------------
        */

        $projects = $user->projects()
            ->with('group')
            ->latest()
            ->take(8)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TEACHER GROUPS
        |--------------------------------------------------------------------------
        */

        $groups = $user->groups()
            ->with('students')
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | COUNTS
        |--------------------------------------------------------------------------
        */

        $totalProjects = $user->projects()->count();

        $totalGroups = $user->groups()->count();

        /*
        |--------------------------------------------------------------------------
        | ONLY STUDENTS INSIDE THIS TEACHER'S GROUPS
        |--------------------------------------------------------------------------
        */

        $groupIds = $user->groups()->pluck('groups.id');

        $totalStudents = User::where('role', 'student')
            ->whereHas('groups', function ($query) use ($groupIds) {
                $query->whereIn('groups.id', $groupIds);
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | ONLY THIS TEACHER'S GRADED SUBMISSIONS
        |--------------------------------------------------------------------------
        */

        $gradedCount = Submission::whereHas('project', function ($query) use ($user) {
                $query->where('teacher_id', $user->id);
            })
            ->where('status', 'graded')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | RECENTLY GRADED
        |--------------------------------------------------------------------------
        */

        $recentlyGraded = Submission::with(['student', 'project'])
            ->whereHas('project', function ($query) use ($user) {
                $query->where('teacher_id', $user->id);
            })
            ->where('status', 'graded')
            ->latest()
            ->take(5)
            ->get();

        return view('teacher.dashboard', compact(
            'projects',
            'groups',
            'totalProjects',
            'totalGroups',
            'totalStudents',
            'gradedCount',
            'recentlyGraded'
        ));
    }
}