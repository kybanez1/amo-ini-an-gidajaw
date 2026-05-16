@extends('layouts.app')

@section('title', 'My Projects')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-projects.css') }}">
@endsection

@section('content')
<div class="pms-page">

    <div class="page-header">
        <div>
            <div class="page-title">📂 My Projects</div>
            <div class="page-subtitle">Manage all your classroom projects</div>
        </div>
        <a href="{{ route('teacher.projects.create') }}" class="btn-primary">+ New Project</a>
    </div>

    <div class="projects-grid">

        @forelse($projects as $project)

        <div class="project-card">

            <div class="project-header">
                <div class="project-icon">📂</div>
                <span class="status-badge">{{ ucfirst($project->status) }}</span>
            </div>

            <div class="project-title">{{ $project->title }}</div>

            <div class="project-desc">
                {{ Str::limit($project->description, 80) ?? 'No description' }}
            </div>

            <div class="project-stats">

                <div class="stat">
                    <div class="value">{{ $project->group->name ?? '—' }}</div>
                    <div class="label">Group</div>
                </div>

                {{-- FIX: null-safe due_date --}}
                <div class="stat">
                    <div class="value">
                        {{ $project->due_date
                            ? \Carbon\Carbon::parse($project->due_date)->format('M d')
                            : '—' }}
                    </div>
                    <div class="label">Due</div>
                </div>

                <div class="stat">
                    <div class="value">{{ $project->max_score }}</div>
                    <div class="label">Score</div>
                </div>

            </div>

            <div class="project-actions">
                <a href="{{ route('teacher.projects.show', $project->id) }}" class="btn-outline">👁 View</a>
                <a href="{{ route('teacher.projects.edit', $project->id) }}" class="btn-outline">✏️ Edit</a>
            </div>

        </div>

        @empty

        <div style="grid-column:1/-1;text-align:center;padding:3rem;color:#9ca3af;">
            No projects yet. Create your first project.
        </div>

        @endforelse

    </div>

    <div style="margin-top:1.5rem;">
        {{ $projects->links() }}
    </div>

</div>
@endsection