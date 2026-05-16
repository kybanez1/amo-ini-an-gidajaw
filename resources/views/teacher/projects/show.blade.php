@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-project-show.css') }}">
@endpush

@section('content')
<div class="wrap">

    @if(session('success'))
        <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#fee2e2;color:#991b1b;border:1px solid #fecaca;">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- PROJECT DETAILS --}}
    <div class="card">
        <div class="header">
            <div>📂 {{ $project->title }}</div>
            <div>
                <span class="badge {{ in_array($project->status, ['ongoing','published','active']) ? 'active' : 'closed' }}">
                    {{ ucfirst($project->status) }}
                </span>
            </div>
        </div>
        <div class="body">
            <div class="grid">
                <div>
                    <div class="label">Description</div>
                    <div class="value">{{ $project->description ?: 'No description provided.' }}</div>
                </div>
                <div>
                    <div class="label">Group</div>
                    <div class="value">
                        {{ $project->group->name ?? 'No group assigned' }}
                        @if($project->group)
                            <div style="margin-top:8px;"><span class="group-badge">👥 Group Project</span></div>
                        @endif
                    </div>
                </div>
                <div>
                    <div class="label">Requirements</div>
                    <div class="value">{{ $project->requirements ?: 'No requirements provided.' }}</div>
                </div>
                <div>
                    <div class="label">Max Score</div>
                    <div class="value">{{ $project->max_score }}</div>
                </div>
                <div>
                    <div class="label">Teacher</div>
                    <div class="value">{{ $project->teacher->name ?? '—' }}</div>
                </div>
                <div>
                    <div class="label">Start Date</div>
                    <div class="value">
                        {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y h:i A') : '—' }}
                    </div>
                </div>
                <div>
                    <div class="label">Due Date</div>
                    <div class="value">
                        {{ $project->due_date ? \Carbon\Carbon::parse($project->due_date)->format('M d, Y h:i A') : '—' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STATS --}}
    <div class="card">
        <div class="body">
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-number">{{ $submittedCount ?? 0 }}</div>
                    <div class="stat-label">Submitted</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">{{ $gradedCount ?? 0 }}</div>
                    <div class="stat-label">Graded</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">{{ $project->tasks->count() }}</div>
                    <div class="stat-label">Total Tasks</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ACTIONS --}}
    <div class="card">
        <div class="body">
            <div class="btn-row">
                <a href="{{ route('teacher.projects.edit', $project->id) }}" class="btn btn-primary">✏️ Edit Project</a>
                <a href="{{ route('teacher.grades.project', $project->id) }}" class="btn btn-outline">⭐ Grade All</a>
                <a href="{{ route('teacher.projects.index') }}" class="btn btn-outline">← Back</a>
            </div>
        </div>
    </div>

    {{-- PROJECT TASKS --}}
    <div class="card">
        <div class="header">📋 Project Tasks</div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Description</th>
                        <th>Deadline</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($project->tasks as $task)
                        @php
                            $taskSubmission = \App\Models\ProjectSubmission::where('project_id', $project->id)
                                ->where('task_id', $task->id)
                                ->whereIn('status', ['submitted','graded','reviewed'])
                                ->with('student')
                                ->first();
                        @endphp
                        <tr>
                            <td><div class="student-name">{{ $task->title }}</div></td>
                            <td>{{ $task->description ?? '—' }}</td>
                            <td>
                                @if($task->due_date)
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y h:i A') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($taskSubmission)
                                    <span class="status-pill status-submitted">✅ Submitted</span>
                                    @if($taskSubmission->student)
                                        <div style="margin-top:6px;font-size:.75rem;color:#6b7280;">
                                            by <strong>{{ $taskSubmission->student->name }}</strong>
                                        </div>
                                    @endif
                                @else
                                    <span class="status-pill status-pending">⏳ Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty-box">No tasks added yet.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SUBMISSIONS --}}
    <div class="card">
        <div class="header">🧑‍🎓 Student Submissions</div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Score</th>
                        <th>File</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions ?? [] as $submission)
                        <tr>
                            <td>
                                <div class="student-name">{{ $submission->student->name ?? 'Unknown Student' }}</div>
                                @if(isset($submission->student->student_id))
                                    <div style="font-size:.72rem;color:#6b7280;margin-top:2px;">
                                        ID: {{ $submission->student->student_id }}
                                    </div>
                                @endif
                            </td>
                            <td>{{ $submission->task->title ?? 'General Submission' }}</td>
                            <td>
                                <span class="status-pill
                                    {{ $submission->status === 'graded'
                                        ? 'status-graded'
                                        : (in_array($submission->status, ['submitted','reviewed'])
                                            ? 'status-submitted'
                                            : 'status-pending') }}">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </td>
                            <td>
                                {{ $submission->submitted_at
                                    ? $submission->submitted_at->format('M d, Y h:i A')
                                    : '—' }}
                            </td>
                            <td>
                                @if($submission->score !== null)
                                    <strong>{{ $submission->score }}</strong> / {{ $project->max_score }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($submission->file_path)
                                    <a href="{{ asset('storage/' . $submission->file_path) }}"
                                       target="_blank" class="file-link">📎 View</a>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                    {{-- View submission detail --}}
                                    <a href="{{ route('teacher.submissions.show', $submission->id) }}"
                                       class="btn btn-outline" style="font-size:.78rem;padding:.45rem .8rem;">
                                        👁 View
                                    </a>
                                    {{-- Grade via GradeController (per-student, with pivot) --}}
                                    <a href="{{ route('teacher.grades.edit', [$project->id, $submission->student_id]) }}"
                                       class="btn btn-primary" style="font-size:.78rem;padding:.45rem .8rem;">
                                        ⭐ Grade
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"><div class="empty-box">No submissions yet.</div></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:1rem 1.5rem;">
            {{ $submissions->links() }}
        </div>
    </div>

</div>
@endsection