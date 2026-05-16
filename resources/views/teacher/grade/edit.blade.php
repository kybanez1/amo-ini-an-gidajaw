@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-grade-project.css') }}">
@endpush

@section('content')
<div class="wrap">
    <div class="top-actions">
        <a href="{{ route('teacher.grades.project', $project->id) }}" class="btn btn-outline">← Back to Grading</a>
    </div>
    <div class="card" style="margin-top:1.5rem;padding:2rem;text-align:center;">
        <div style="font-size:3rem;margin-bottom:1rem;">👥</div>
        <h2 style="margin-bottom:.5rem;">This project uses Group Grading</h2>
        <p style="color:#6b7280;margin-bottom:1.5rem;">
            Grades are applied to the entire group at once. Please use the project grading page to assign a grade.
        </p>
        <a href="{{ route('teacher.grades.project', $project->id) }}"
           style="padding:.75rem 2rem;background:#4f46e5;color:white;border-radius:10px;text-decoration:none;font-weight:600;">
            ✏️ Grade This Project
        </a>
    </div>
</div>
@endsection
