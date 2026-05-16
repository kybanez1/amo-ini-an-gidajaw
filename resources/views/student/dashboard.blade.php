@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/student-dashboard.css') }}">
@endpush

@section('content')
<div class="student-dashboard">

    @php
        $gradedProjects = $assignedProjects->filter(function($project){
            return $project->pivot->assignment_status === 'graded';
        });

        // Use real recentSubmissions from controller (not reassigned)
        $highestScore = $gradedProjects->max(function($project){
            return $project->pivot->score ?? 0;
        });

        $lowestScore = $gradedProjects->min(function($project){
            return $project->pivot->score ?? 0;
        });
    @endphp

    <!-- HERO -->
    <div class="hero">

        <div class="hero-top">

            <div>

                <div class="hero-title">
                    Welcome back, {{ Auth::user()->name }} 👋
                </div>

                <div class="hero-sub">
                    {{ Auth::user()->student_id ?? 'Student ID' }}
                    •
                    {{ Auth::user()->department ?? 'Student Dashboard' }}
                </div>

            </div>

            <div class="student-badge">
                🎓 Student Portal
            </div>

        </div>

        <!-- STATS -->
        <div class="stats-grid">

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        📂
                    </div>

                </div>

                <div class="stat-value">
                    {{ $totalProjects }}
                </div>

                <div class="stat-label">
                    Total Projects
                </div>

            </div>

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        ⏳
                    </div>

                </div>

                <div class="stat-value">
                    {{ $pendingCount }}
                </div>

                <div class="stat-label">
                    Pending Tasks
                </div>

            </div>

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        ✅
                    </div>

                </div>

                <div class="stat-value">
                    {{ $submittedCount }}
                </div>

                <div class="stat-label">
                    Submitted
                </div>

            </div>

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        ⭐
                    </div>

                </div>

                <div class="stat-value">
                    {{ $averageScore ?? 0 }}%
                </div>

                <div class="stat-label">
                    Average Score
                </div>

            </div>

        </div>

    </div>

    <div class="dashboard-grid">

        <!-- LEFT -->
        <div>

            <!-- PROJECTS -->
            <div class="panel">

                <div class="panel-header">

                    <div>

                        <div class="panel-title">
                            📚 Assigned Projects
                        </div>

                        <div class="panel-sub">
                            Manage and submit your active school projects
                        </div>

                    </div>

                </div>

                @if($assignedProjects->isEmpty())

                    <div class="empty">
                        No assigned projects yet
                    </div>

                @else

                    <div class="project-table-wrap">

                        <table class="project-table">

                            <thead>

                                <tr>
                                    <th>Project</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Score</th>
                                    <th>Actions</th>
                                </tr>

                            </thead>

                            <tbody>

                            @foreach($assignedProjects as $project)

                                @php
                                    $pivotStatus = $project->pivot->assignment_status ?? 'assigned';
                                    $score = $project->pivot->score;
                                @endphp

                                <tr class="project-row">

                                    <td>

                                        <div class="project-name">
                                            {{ $project->title }}
                                        </div>

                                        <div class="project-teacher">
                                            {{ $project->teacher->name ?? 'Teacher' }}
                                        </div>

                                    </td>

                                    <td>

                                        {{ $project->due_date
                                            ? \Carbon\Carbon::parse($project->due_date)->format('M d, Y')
                                            : 'No deadline'
                                        }}

                                    </td>

                                    <td>

                                        @if($pivotStatus === 'graded')

                                            <span class="status graded">
                                                ✅ Graded
                                            </span>

                                        @elseif($pivotStatus === 'submitted')

                                            <span class="status submitted">
                                                📤 Submitted
                                            </span>

                                        @else

                                            <span class="status pending">
                                                ⏳ Pending
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        <span class="score-pill">
                                            {{ $score ?? '—' }}
                                            /
                                            {{ $project->max_score }}
                                        </span>

                                    </td>

                                    <td>

                                        <div class="actions">

                                            <a href="{{ route('student.projects.show',$project->id) }}"
                                               class="btn btn-dark">

                                                👁 View

                                            </a>

                                            @if($pivotStatus !== 'graded')

                                                <a href="{{ route('student.projects.submit',$project->id) }}"
                                                   class="btn btn-primary">

                                                    🚀 Submit

                                                </a>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                        </table>

                    </div>

                @endif

            </div>

            <!-- GROUPS -->
            <div class="panel">

                <div class="panel-header">

                    <div>

                        <div class="panel-title">
                            👥 My Groups
                        </div>

                        <div class="panel-sub">
                            Your joined academic groups and classes
                        </div>

                    </div>

                </div>

                <div style="margin-bottom:1rem;display:flex;gap:.75rem;flex-wrap:wrap;">
                    <a href="{{ route('student.teacher.join') }}"
                       style="display:inline-flex;align-items:center;gap:6px;
                              padding:.65rem 1.1rem;background:#7c3aed;color:white;
                              border-radius:10px;text-decoration:none;font-size:.82rem;
                              font-weight:700;">
                        🎓 Enter Teacher Code
                    </a>
                    <a href="{{ route('student.groups.join') }}"
                       style="display:inline-flex;align-items:center;gap:6px;
                              padding:.65rem 1.1rem;background:#4f46e5;color:white;
                              border-radius:10px;text-decoration:none;font-size:.82rem;
                              font-weight:700;">
                        🔑 Join a Group
                    </a>
                </div>

                @if($groups->isEmpty())

                    <div class="empty">
                        No groups joined yet.<br>
                        <span style="font-size:.82rem;color:#9ca3af;">
                            Ask your teacher for a join code.
                        </span>
                    </div>

                @else

                    <div class="group-list">

                        @foreach($groups as $group)

                            <div class="group-item">

                                <div>

                                    <div class="group-name">
                                        {{ $group->name }}
                                    </div>

                                    <div class="group-teacher">
                                        {{ $group->teacher->name ?? 'Teacher' }}
                                    </div>

                                </div>

                                <a href="{{ route('student.groups.show',$group->id) }}"
                                   class="btn btn-primary">

                                    👁 Open

                                </a>

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>

        <!-- RIGHT -->
        <div>

            <!-- SUMMARY -->
            <div class="panel">

                <div class="panel-header">

                    <div>

                        <div class="panel-title">
                            📊 Performance
                        </div>

                        <div class="panel-sub">
                            Academic overview and score insights
                        </div>

                    </div>

                </div>

                <div class="summary-wrap">

                    <div class="summary-box">

                        <div class="summary-label">
                            Average Score
                        </div>

                        <div class="summary-value">
                            {{ $averageScore ?? 0 }}%
                        </div>

                    </div>

                    <div class="summary-box">

                        <div class="summary-label">
                            Highest Score
                        </div>

                        <div class="summary-value">
                            {{ $highestScore ?? 0 }}
                        </div>

                    </div>

                    <div class="summary-box">

                        <div class="summary-label">
                            Lowest Score
                        </div>

                        <div class="summary-value">
                            {{ $lowestScore ?? 0 }}
                        </div>

                    </div>

                </div>

            </div>

            <!-- RECENT -->
            <div class="panel">

                <div class="panel-header">

                    <div>

                        <div class="panel-title">
                            📤 Recent Activity
                        </div>

                        <div class="panel-sub">
                            Latest submissions and grading updates
                        </div>

                    </div>

                </div>

                @if($recentSubmissions->isEmpty())

                    <div class="empty">
                        No recent activity
                    </div>

                @else

                    <div class="recent-wrap">

                        @foreach($recentSubmissions as $submission)

                            <div class="recent-item">

                                <div class="recent-title">
                                    {{ $submission->project->title ?? 'Project' }}
                                </div>

                                <div class="recent-meta">
                                    {{ $submission->project->teacher->name ?? 'Teacher' }}
                                    •
                                    {{ $submission->created_at->diffForHumans() }}
                                </div>

                                @if($submission->status === 'graded' && $submission->score !== null)
                                    <div class="recent-grade">
                                        ⭐ {{ $submission->score }} / {{ $submission->project->max_score ?? '—' }}
                                    </div>
                                @else
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:4px;">
                                        {{ ucfirst($submission->status) }}
                                    </div>
                                @endif

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection