<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherStudentController extends Controller
{
    /**
     * LIST — all students who entered this teacher's code
     */
    public function index(): View
    {
        $teacher = auth()->user();

        if (!$teacher->isTeacher()) {
            abort(403);
        }

        $students = $teacher->myStudents()
            ->orderBy('name')
            ->paginate(20);

        return view('teacher.students.index', compact('teacher', 'students'));
    }
}