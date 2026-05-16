@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/student-project-show.css') }}">
@endpush

@section('content')
<div class="page">

    <a href="{{ route('student.projects.index') }}"
       style="display:inline-block;margin-bottom:1rem;color:#374151;text-decoration:none;">
        ← Back
    </a>

    <div class="card">

        <!-- TITLE -->
        <div class="title">
            {{ $project->title }}
        </div>

        <!-- META -->
        <div class="meta">
            Teacher:
            {{ $project->teacher->name ?? 'Teacher' }}

            ·

            Due:
            {{ \Carbon\Carbon::parse($project->due_date)->format('M d, Y') }}
        </div>

        <!-- DESCRIPTION -->
        <div class="section">

            <div class="section-title">
                📌 Project Description
            </div>

            <div class="box">
                {{ $project->description }}
            </div>

        </div>

        <!-- REQUIREMENTS -->
        <div class="section">

            <div class="section-title">
                📝 Instructions
            </div>

            <div class="box">

                @if($project->requirements)
                    <div style="margin-bottom:1rem;">{{ $project->requirements }}</div>
                @else
                    <div style="margin-bottom:{{ $project->instruction_file ? '1rem' : '0' }};color:#6b7280;">
                        Follow teacher instructions carefully.
                    </div>
                @endif

                @if($project->instruction_file || $project->instruction_link)
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                flex-wrap:wrap;gap:.75rem;padding:1rem;
                                background:#f5f3ff;border:1px solid #c7d2fe;
                                border-radius:10px;margin-top:.5rem;">
                        <div>
                            <div style="font-weight:700;color:#4338ca;margin-bottom:3px;">
                                @if($project->instruction_file)
                                    📎 {{ $project->instruction_file_name ?? basename($project->instruction_file) }}
                                @else
                                    🔗 Instruction Link
                                @endif
                            </div>
                            <div style="font-size:.75rem;color:#6b7280;">
                                @if($project->instruction_file_uploaded_at)
                                    Uploaded {{ \Carbon\Carbon::parse($project->instruction_file_uploaded_at)->format('M d, Y') }}
                                @else
                                    Uploaded by teacher
                                @endif
                            </div>
                        </div>
                        <div style="display:flex;gap:8px;">
                            @if($project->instruction_file)
                                <a href="{{ asset('storage/' . $project->instruction_file) }}"
                                   target="_blank"
                                   style="padding:.5rem 1rem;background:#eef2ff;color:#4338ca;
                                          border:1px solid #c7d2fe;border-radius:8px;
                                          text-decoration:none;font-size:.82rem;font-weight:600;">
                                    👁 View
                                </a>
                                <a href="{{ asset('storage/' . $project->instruction_file) }}"
                                   download="{{ $project->instruction_file_name ?? basename($project->instruction_file) }}"
                                   style="padding:.5rem 1rem;background:#4f46e5;color:white;
                                          border-radius:8px;text-decoration:none;
                                          font-size:.82rem;font-weight:600;">
                                    ⬇ Download
                                </a>
                            @elseif($project->instruction_link)
                                <a href="{{ $project->instruction_link }}"
                                   target="_blank"
                                   style="padding:.5rem 1rem;background:#4f46e5;color:white;
                                          border-radius:8px;text-decoration:none;
                                          font-size:.82rem;font-weight:600;">
                                    🔗 Open Link
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

            </div>

        </div>

        <!-- PROJECT TASKS -->
        <div class="section">

            <div class="section-title">
                📋 Assigned Tasks
            </div>

            @if($project->tasks && $project->tasks->count())

                <div class="tasks-wrap">

                    @foreach($project->tasks as $task)

                        @php
                            // Use pre-loaded $submissions collection (keyed by task_id)
                            $taskSubmission = $submissions->get($task->id);
                            $isCompleted = $taskSubmission && in_array($taskSubmission->status, ['submitted', 'reviewed', 'graded']);
                        @endphp

                        <div class="task-card">

                            <div class="task-top">

                                <div>

                                    <div class="task-title">
                                        {{ $task->title }}
                                    </div>

                                    @if($task->description)

                                        <div class="task-desc">
                                            {{ $task->description }}
                                        </div>

                                    @endif

                                </div>

                                <div>

                                    <span class="status-pill
                                        {{ $isCompleted
                                            ? 'status-completed'
                                            : 'status-pending'
                                        }}">

                                        {{ $isCompleted ? 'Completed' : 'Pending' }}

                                    </span>

                                </div>

                            </div>

                            <!-- TASK META -->
                            <div class="task-meta">

                                @if($task->due_date)

                                    <div class="meta-pill">
                                        ⏰ Due:
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y h:i A') }}
                                    </div>

                                @endif

                                @if($taskSubmission && $taskSubmission->submitted_at)

                                    <div class="meta-pill"
                                         style="background:#dcfce7;color:#166534;">
                                        ✅ Submitted:
                                        {{ $taskSubmission->submitted_at->format('M d, Y h:i A') }}
                                    </div>

                                @endif

                                @if($taskSubmission && $taskSubmission->task_score !== null)
                                    <div class="meta-pill"
                                         style="background:#dbeafe;color:#1d4ed8;font-weight:700;">
                                        ⭐ Score: {{ $taskSubmission->task_score }} / {{ $task->max_points ?? 100 }}
                                    </div>
                                @endif

                                @if($taskSubmission && $taskSubmission->feedback)
                                    <div class="meta-pill"
                                         style="background:#f0fdf4;color:#166534;">
                                        💬 {{ $taskSubmission->feedback }}
                                    </div>
                                @endif

                            </div>

                            <!-- TASK ACTION -->
                            <div class="task-actions">

                                @if($taskSubmission)

                                    <button
                                        class="task-btn-closed"
                                        disabled>

                                        ✅ Task Closed

                                    </button>

                                @else

                                    <a
                                        href="{{ route('student.projects.submit', [$project->id, 'task' => $task->id]) }}"
                                        class="btn btn-primary"
                                    >
                                        📤 Submit Task
                                    </a>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="empty-task">
                    No tasks assigned yet.
                </div>

            @endif

        </div>



    </div>

</div>

@endsection