@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/dashboard.css') }}">
@endpush

@section('title', 'Teacher Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Good day, {{ Auth::user()->name }} 👋
            </h3>

            <p class="text-muted mb-0">
                Here's what's happening in your classroom
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('teacher.groups.create') }}"
               class="btn btn-outline-primary">

                + New Group

            </a>

            <a href="{{ route('teacher.projects.create') }}"
               class="btn btn-primary">

                + New Project

            </a>

        </div>

    </div>

    {{-- STATS --}}
    <div class="row mb-4">

        <div class="col-md-3 mb-3">

            <div class="stat-card">

                <div class="stat-icon bg-purple">
                    📁
                </div>

                <h3 class="fw-bold">
                    {{ $totalProjects }}
                </h3>

                <div class="text-muted">
                    Total Projects
                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="stat-card">

                <div class="stat-icon bg-blue">
                    👥
                </div>

                <h3 class="fw-bold">
                    {{ $totalGroups }}
                </h3>

                <div class="text-muted">
                    Total Groups
                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="stat-card">

                <div class="stat-icon bg-green">
                    🎓
                </div>

                <h3 class="fw-bold">
                    {{ $totalStudents }}
                </h3>

                <div class="text-muted">
                    Students
                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="stat-card">

                <div class="stat-icon bg-orange">
                    ✅
                </div>

                <h3 class="fw-bold">
                    {{ $gradedCount }}
                </h3>

                <div class="text-muted">
                    Graded Submissions
                </div>

            </div>

        </div>

    </div>

    <div class="row">

        {{-- PROJECTS --}}
        <div class="col-lg-8 mb-4">

            <div class="card dashboard-card h-100">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        📚 Your Projects
                    </h5>

                    <a href="{{ route('teacher.projects.index') }}">
                        View all →
                    </a>

                </div>

                <div class="table-responsive">

                    <table class="table mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>PROJECT</th>
                                <th>GROUP</th>
                                <th>STATUS</th>
                                <th>DUE DATE</th>
                                <th></th>
                            </tr>

                        </thead>

                        <tbody>

                        @forelse($projects as $project)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $project->title }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $project->group->name ?? 'No Group' }}
                                </td>

                                <td>

                                    @if($project->status === 'published')

                                        <span class="badge bg-success">
                                            Published
                                        </span>

                                    @elseif($project->status === 'ongoing')

                                        <span class="badge bg-warning">
                                            Ongoing
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Draft
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    {{ $project->due_date
                                        ? \Carbon\Carbon::parse($project->due_date)->format('M d, Y')
                                        : 'No deadline'
                                    }}

                                </td>

                                <td>

                                    <a href="{{ route('teacher.projects.show',$project->id) }}"
                                       class="btn btn-sm btn-dark">

                                        View

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5">

                                    <div class="empty-box">
                                        No projects yet.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        {{-- RECENT GRADED --}}
        <div class="col-lg-4 mb-4">

            <div class="card dashboard-card h-100">

                <div class="card-header">

                    <h5 class="mb-0">
                        ✅ Recently Graded
                    </h5>

                </div>

                <div class="card-body p-0">

                    @forelse($recentlyGraded as $submission)

                        <div class="p-3 border-bottom">

                            <div class="fw-semibold">
                                {{ $submission->student->name ?? 'Student' }}
                            </div>

                            <div class="small text-muted mb-2">
                                {{ $submission->project->title ?? 'Project' }}
                            </div>

                            <span class="badge-soft-success">
                                GRADED
                            </span>

                        </div>

                    @empty

                        <div class="empty-box">
                            No graded submissions yet.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>

@endsection