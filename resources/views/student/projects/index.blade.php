@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/student-projects.css') }}">
@endpush

@section('content')
<div class="projects-wrap">

    <div class="page-header">

        <div>

            <div class="page-title">
                📂 My Projects
            </div>

            <div class="page-sub">
                {{ $assignedProjects->count() }} project(s) assigned to you
            </div>

        </div>

        <a href="{{ route('student.dashboard') }}"
           style="font-size:0.85rem;color:#6b7280;text-decoration:none;">

            ← Dashboard

        </a>

    </div>

    {{-- FILTER --}}
    <div class="filter-bar">

        <a href="{{ route('student.projects.index') }}"
           class="filter-btn {{ !request('status') ? 'active' : '' }}">

            All

        </a>

        <a href="{{ route('student.projects.index', ['status' => 'assigned']) }}"
           class="filter-btn {{ request('status') === 'assigned' ? 'active' : '' }}">

            ⏳ Pending

        </a>

        <a href="{{ route('student.projects.index', ['status' => 'submitted']) }}"
           class="filter-btn {{ request('status') === 'submitted' ? 'active' : '' }}">

            📤 Submitted

        </a>

        <a href="{{ route('student.projects.index', ['status' => 'graded']) }}"
           class="filter-btn {{ request('status') === 'graded' ? 'active' : '' }}">

            ✅ Graded

        </a>

    </div>

    @php

        /*
        |--------------------------------------------------------------------------
        | FILTER PROJECTS CORRECTLY
        |--------------------------------------------------------------------------
        */

        $filtered = collect();

        foreach($assignedProjects as $project){

            /*
            |--------------------------------------------------------------------------
            | DEFAULT STATUS FROM PIVOT
            |--------------------------------------------------------------------------
            */

            $status =
                $project->pivot->assignment_status
                ?? 'assigned';

            /*
            |--------------------------------------------------------------------------
            | KEEP GRADED STATUS
            |--------------------------------------------------------------------------
            */

            if($status !== 'graded'){

                /*
                |--------------------------------------------------------------------------
                | GROUP SUBMISSION CHECK
                |--------------------------------------------------------------------------
                */

                $groupSubmitted = false;

                if($project->group_id){

                    $groupStudentIds =
                        \DB::table('group_student')
                            ->where(
                                'group_id',
                                $project->group_id
                            )
                            ->pluck('student_id');

                    $groupSubmitted =
                        \App\Models\ProjectSubmission::where(
                            'project_id',
                            $project->id
                        )
                        ->whereIn(
                            'student_id',
                            $groupStudentIds
                        )
                        ->whereIn('status', [
                            'submitted',
                            'graded',
                            'reviewed'
                        ])
                        ->exists();
                }

                /*
                |--------------------------------------------------------------------------
                | AUTO STATUS
                |--------------------------------------------------------------------------
                */

                if($groupSubmitted){
                    $status = 'submitted';
                }
            }

            /*
            |--------------------------------------------------------------------------
            | FILTER LOGIC
            |--------------------------------------------------------------------------
            */

            if(!request('status')){

                $filtered->push($project);

            }elseif(request('status') === $status){

                $filtered->push($project);
            }
        }

    @endphp

    @if($filtered->isEmpty())

        <div class="empty-state">

            <div class="icon">
                📭
            </div>

            <h3>
                No projects found
            </h3>

            <p>

                @if(request('status') === 'assigned')
                    No pending projects found.
                @elseif(request('status') === 'submitted')
                    No submitted projects found.
                @elseif(request('status') === 'graded')
                    No graded projects found.
                @else
                    No projects have been assigned to you yet.
                @endif

            </p>

        </div>

    @else

        <div class="projects-grid">

            @foreach($filtered as $project)

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | REAL STATUS
                    |--------------------------------------------------------------------------
                    */

                    $status =
                        $project->pivot->assignment_status
                        ?? 'assigned';

                    $score =
                        $project->pivot->score
                        ?? null;

                    /*
                    |--------------------------------------------------------------------------
                    | DO NOT OVERRIDE GRADED
                    |--------------------------------------------------------------------------
                    */

                    if($status !== 'graded'){

                        $groupSubmitted = false;

                        if($project->group_id){

                            $groupStudentIds =
                                \DB::table('group_student')
                                    ->where(
                                        'group_id',
                                        $project->group_id
                                    )
                                    ->pluck('student_id');

                            $groupSubmitted =
                                \App\Models\ProjectSubmission::where(
                                    'project_id',
                                    $project->id
                                )
                                ->whereIn(
                                    'student_id',
                                    $groupStudentIds
                                )
                                ->whereIn('status', [
                                    'submitted',
                                    'graded',
                                    'reviewed'
                                ])
                                ->exists();
                        }

                        if($groupSubmitted){
                            $status = 'submitted';
                        }
                    }

                    $dueDate =
                        \Carbon\Carbon::parse(
                            $project->due_date
                        );

                    $isOverdue =
                        $dueDate->isPast()
                        && $status === 'assigned';

                @endphp

                <div class="project-card">

                    <div class="project-card-header">

                        <div>

                            <div class="project-title">
                                {{ $project->title }}
                            </div>

                            <div class="project-teacher">

                                by
                                {{ $project->teacher->name ?? '—' }}

                            </div>

                        </div>

                        <span class="status-badge {{ $status }}">

                            @if($status === 'graded')

                                ✅ Graded

                            @elseif($status === 'submitted')

                                📤 Submitted

                            @else

                                ⏳ Pending

                            @endif

                        </span>

                    </div>

                    @if($project->description)

                        <div class="project-desc">

                            {{ \Illuminate\Support\Str::limit(
                                $project->description,
                                100
                            ) }}

                        </div>

                    @endif

                    <div class="project-meta-row">

                        <div class="meta-item">

                            <span class="meta-label">
                                Due Date
                            </span>

                            <span class="meta-val {{ $isOverdue ? 'due-overdue' : '' }}">

                                {{ $dueDate->format('M d, Y') }}

                                @if($isOverdue)
                                    ⚠️
                                @endif

                            </span>

                        </div>

                        <div class="meta-item">

                            <span class="meta-label">
                                Max Score
                            </span>

                            <span class="meta-val">
                                {{ $project->max_score }}
                            </span>

                        </div>

                        @if($status === 'graded' && $score !== null)

                            <div class="meta-item">

                                <span class="meta-label">
                                    Your Score
                                </span>

                                <span class="score-chip">

                                    {{ $score }}/{{ $project->max_score }}

                                </span>

                            </div>

                        @endif

                    </div>

                    <div class="project-actions">

                        <a href="{{ route('student.projects.show', $project->id) }}"
                           class="btn-view">

                            👁 View

                        </a>

                        @if($status === 'assigned')

                            <a href="{{ route('student.projects.submit', $project->id) }}"
                               class="btn-submit">

                                📤 Submit

                            </a>

                        @elseif($status === 'graded')

                            <div class="btn-submit"
                                 style="background:#16a34a;cursor:default;pointer-events:none;">

                                ✅ Graded

                            </div>

                        @else

                            <div class="btn-submit"
                                 style="background:#7c3aed;cursor:default;pointer-events:none;">

                                📤 Submitted

                            </div>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    @endif

</div>

@endsection