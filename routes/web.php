<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\TeacherAuthController;
use App\Http\Controllers\Auth\StudentAuthController;

use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\StudentDashboardController;

use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StudentProjectController;
use App\Http\Controllers\StudentGroupController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ProjectSubmissionController;
use App\Http\Controllers\TeacherStudentController;
use App\Http\Controllers\StudentTeacherController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (auth()->check()) {

        return auth()->user()->role === 'teacher'
            ? redirect()->route('teacher.dashboard')
            : redirect()->route('student.dashboard');
    }

    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| LOGIN PAGE
|--------------------------------------------------------------------------
*/

Route::view('/login', 'auth.login')
    ->middleware('guest')
    ->name('login');

/*
|--------------------------------------------------------------------------
| TEACHER ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('teacher')
    ->as('teacher.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | GUEST ROUTES
        |--------------------------------------------------------------------------
        */

        Route::middleware('guest')->group(function () {

            Route::get(
                '/login',
                [TeacherAuthController::class, 'showLoginForm']
            )->name('login');

            Route::post(
                '/login',
                [TeacherAuthController::class, 'login']
            );

            Route::get(
                '/register',
                [TeacherAuthController::class, 'showRegisterForm']
            )->name('register');

            Route::post(
                '/register',
                [TeacherAuthController::class, 'register']
            );
        });

        /*
        |--------------------------------------------------------------------------
        | AUTH TEACHER
        |--------------------------------------------------------------------------
        */

        Route::middleware([
            'auth',
            'role:teacher'
        ])->group(function () {

            /*
            |--------------------------------------------------------------------------
            | DASHBOARD
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/dashboard',
                [TeacherDashboardController::class, 'index']
            )->name('dashboard');

            /*
            |--------------------------------------------------------------------------
            | LOGOUT
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/logout',
                [TeacherAuthController::class, 'logout']
            )->name('logout');

            /*
            |--------------------------------------------------------------------------
            | GROUPS
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'groups',
                GroupController::class
            );

            Route::post(
                'groups/{group}/add-student',
                [GroupController::class, 'addStudent']
            )->name('groups.addStudent');

            Route::delete(
                'groups/{group}/remove-student/{student}',
                [GroupController::class, 'removeStudent']
            )->name('groups.removeStudent');

            Route::post(
                'groups/{group}/regenerate-code',
                [GroupController::class, 'regenerateCode']
            )->name('groups.regenerateCode');

            /*
            |--------------------------------------------------------------------------
            | PROJECTS
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'projects',
                ProjectController::class
            );

            /*
            |--------------------------------------------------------------------------
            | SUBMISSIONS
            |--------------------------------------------------------------------------
            */

            /*
            |--------------------------------------------------------------------------
            | VIEW SUBMISSION
            |--------------------------------------------------------------------------
            */

            Route::get(
                'submissions/{submission}',
                [ProjectSubmissionController::class, 'show']
            )->name('submissions.show');

            /*
            |--------------------------------------------------------------------------
            | OPEN GRADE PAGE
            |--------------------------------------------------------------------------
            */

            Route::get(
                'submissions/{submission}/grade',
                [ProjectSubmissionController::class, 'gradeForm']
            )->name('submissions.grade');

            /*
            |--------------------------------------------------------------------------
            | SAVE GRADE
            |--------------------------------------------------------------------------
            */

            Route::put(
                'submissions/{submission}/grade',
                [ProjectSubmissionController::class, 'grade']
            )->name('submissions.grade.store');

            /*
            |--------------------------------------------------------------------------
            | GRADED PROJECTS
            |--------------------------------------------------------------------------
            */

            Route::get(
                'graded-projects',
                [GradeController::class, 'gradedProjects']
            )->name('graded.index');

            /*
            |--------------------------------------------------------------------------
            | MY STUDENTS (entered teacher code)
            |--------------------------------------------------------------------------
            */

            Route::get(
                'students',
                [TeacherStudentController::class, 'index']
            )->name('students.index');

            /*
            |--------------------------------------------------------------------------
            | PROJECT GRADES
            |--------------------------------------------------------------------------
            */

            Route::get(
                'projects/{project}/grades',
                [GradeController::class, 'project']
            )->name('grades.project');

            /*
            |--------------------------------------------------------------------------
            | EDIT GRADE
            |--------------------------------------------------------------------------
            */

            Route::get(
                'projects/{project}/grade/{student}/edit',
                [GradeController::class, 'edit']
            )->name('grades.edit');

            /*
            |--------------------------------------------------------------------------
            | STORE GRADE
            |--------------------------------------------------------------------------
            */

            Route::post(
                'projects/{project}/grade/{student}',
                [GradeController::class, 'store']
            )->name('grades.store');

            /*
            |--------------------------------------------------------------------------
            | BULK GRADE UPDATE
            |--------------------------------------------------------------------------
            */

            Route::post(
                'projects/{project}/grades/bulk-update',
                [GradeController::class, 'bulkUpdate']
            )->name('grades.bulkUpdate');
        });
    });

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('student')
    ->as('student.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | GUEST ROUTES
        |--------------------------------------------------------------------------
        */

        Route::middleware('guest')->group(function () {

            Route::get(
                '/login',
                [StudentAuthController::class, 'showLoginForm']
            )->name('login');

            Route::post(
                '/login',
                [StudentAuthController::class, 'login']
            );

            Route::get(
                '/register',
                [StudentAuthController::class, 'showRegisterForm']
            )->name('register');

            Route::post(
                '/register',
                [StudentAuthController::class, 'register']
            );
        });

        /*
        |--------------------------------------------------------------------------
        | AUTH STUDENT
        |--------------------------------------------------------------------------
        */

        Route::middleware([
            'auth',
            'role:student'
        ])->group(function () {

            /*
            |--------------------------------------------------------------------------
            | DASHBOARD
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/dashboard',
                [StudentDashboardController::class, 'index']
            )->name('dashboard');

            /*
            |--------------------------------------------------------------------------
            | MY GRADES
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/grades',
                [StudentDashboardController::class, 'grades']
            )->name('grades');

            /*
            |--------------------------------------------------------------------------
            | ENTER TEACHER CODE
            |--------------------------------------------------------------------------
            */

            Route::get(
                'teacher/join',
                [StudentTeacherController::class, 'joinForm']
            )->name('teacher.join');

            Route::post(
                'teacher/join',
                [StudentTeacherController::class, 'join']
            )->name('teacher.join.store');

            /*
            |--------------------------------------------------------------------------
            | LOGOUT
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/logout',
                [StudentAuthController::class, 'logout']
            )->name('logout');

            /*
            |--------------------------------------------------------------------------
            | PROJECTS
            |--------------------------------------------------------------------------
            */

            Route::get(
                'projects',
                [StudentProjectController::class, 'index']
            )->name('projects.index');

            Route::get(
                'projects/{project}',
                [StudentProjectController::class, 'show']
            )->name('projects.show');

            /*
            |--------------------------------------------------------------------------
            | PROJECT SUBMISSION
            |--------------------------------------------------------------------------
            */

            Route::get(
                'projects/{project}/submit',
                [StudentProjectController::class, 'submitForm']
            )->name('projects.submit');

            Route::post(
                'projects/{project}/submit',
                [StudentProjectController::class, 'submit']
            )->name('projects.submit.store');

            Route::post(
                'projects/{project}/finalize',
                [StudentProjectController::class, 'finalize']
            )->name('projects.finalize');

            /*
            |--------------------------------------------------------------------------
            | GROUPS
            |--------------------------------------------------------------------------
            */

            Route::get(
                'groups/join',
                [StudentGroupController::class, 'joinForm']
            )->name('groups.join');

            Route::post(
                'groups/join',
                [StudentGroupController::class, 'join']
            )->name('groups.join.store');

            Route::get(
                'groups/{group}',
                [StudentGroupController::class, 'show']
            )->name('groups.show');
        });
    });