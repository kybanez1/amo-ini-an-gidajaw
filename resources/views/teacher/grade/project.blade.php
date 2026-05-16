@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-grade-project.css') }}">
@endpush

@section('content')
<div class="wrap">

    @if(session('success'))
        <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#dcfce7;color:#166534;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#fee2e2;color:#991b1b;">
            ❌ {{ session('error') }}
        </div>
    @endif

    <div class="top-actions">
        <a href="{{ route('teacher.projects.show', $project->id) }}" class="btn btn-outline">
            ← Back to Project
        </a>
    </div>

    {{-- PROJECT INFO --}}
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div class="card-title">📂 {{ $project->title }}</div>
            <div style="font-size:.8rem;color:#9ca3af;">Max Score: {{ $project->max_score }}</div>
        </div>
        <div class="card-body" style="padding:1rem 1.5rem;">
            <div style="display:flex;gap:2rem;flex-wrap:wrap;">
                <div>
                    <div style="font-size:.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Group</div>
                    <div style="font-weight:600;">{{ $group ? $group->name : 'No group assigned' }}</div>
                </div>
                <div>
                    <div style="font-size:.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Due Date</div>
                    <div style="font-weight:600;">{{ $project->due_date ? $project->due_date->format('M d, Y') : '—' }}</div>
                </div>
                <div>
                    <div style="font-size:.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Status</div>
                    <div style="font-weight:600;">{{ ucfirst($project->status) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- STATS --}}
    <div class="stats">
        <div class="stat">
            <div class="stat-label">Total Students</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Submitted</div>
            <div class="stat-value">{{ $stats['submitted'] }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Graded</div>
            <div class="stat-value">{{ $stats['graded'] }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
        </div>
    </div>

    {{-- SUBMISSIONS LIST --}}
    @if($submissions->isNotEmpty())
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div class="card-title">📄 Student Submissions</div>
        </div>
        <div class="card-body">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                        <th>Score</th>
                        <th>File</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $sub)
                    <tr>
                        <td>
                            <div class="student">{{ $sub->student->name ?? '—' }}</div>
                            @if($sub->student && $sub->student->student_id)
                                <div style="font-size:.72rem;color:#6b7280;">🆔 {{ $sub->student->student_id }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $sub->status === 'graded' ? 'reviewed' : ($sub->status === 'submitted' ? 'submitted' : 'draft') }}">
                                {{ ucfirst($sub->status) }}
                            </span>
                        </td>
                        <td>{{ $sub->submitted_at ? $sub->submitted_at->format('M d, Y h:i A') : '—' }}</td>
                        <td>
                            @if($sub->score !== null)
                                <strong>{{ $sub->score }}</strong> / {{ $project->max_score }}
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($sub->file_path)
                                <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="file-link">📎 View</a>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- GROUP GRADE FORM --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">✏️ Grade This Project</div>
            <div style="font-size:.8rem;color:#6b7280;">One grade applies to all group members</div>
        </div>
        <div class="card-body">

            @if($errors->any())
                <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#fee2e2;color:#991b1b;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('teacher.grades.storeProject', $project->id) }}">
                @csrf

                {{-- Group Members Notice --}}
                @if($group && $group->students->count() > 0)
                <div style="margin-bottom:1.25rem;padding:1rem;background:#eef2ff;border-radius:10px;border:1px solid #c7d2fe;">
                    <div style="font-size:.85rem;font-weight:600;color:#4f46e5;margin-bottom:.5rem;">👥 Group Members ({{ $group->students->count() }})</div>
                    <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                        @foreach($group->students as $member)
                            <span style="padding:.25rem .65rem;background:white;border:1px solid #c7d2fe;border-radius:999px;font-size:.8rem;color:#3730a3;">
                                {{ $member->name }}
                            </span>
                        @endforeach
                    </div>
                    <div style="font-size:.75rem;color:#6b7280;margin-top:.5rem;">All members will receive the same project grade.</div>
                </div>
                @endif

                {{-- Score --}}
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block;font-size:.85rem;font-weight:600;color:#374151;margin-bottom:.5rem;">
                        Score <span style="color:#dc2626;">*</span>
                    </label>
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <input type="number"
                               name="score"
                               min="0"
                               max="{{ $project->max_score }}"
                               value="{{ old('score', $groupGrade && $groupGrade->score !== null ? $groupGrade->score : '') }}"
                               required
                               style="width:120px;padding:.65rem 1rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:1.1rem;font-weight:700;text-align:center;">
                        <span style="color:#6b7280;font-size:.9rem;">/ {{ $project->max_score }}</span>
                    </div>
                </div>

                {{-- Feedback --}}
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;font-size:.85rem;font-weight:600;color:#374151;margin-bottom:.5rem;">
                        Feedback <span style="font-weight:400;color:#9ca3af;">(optional)</span>
                    </label>
                    <textarea name="feedback"
                              rows="4"
                              placeholder="Provide constructive feedback for the group..."
                              style="width:100%;padding:.75rem 1rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:.9rem;resize:vertical;box-sizing:border-box;">{{ old('feedback', $groupGrade ? $groupGrade->feedback : '') }}</textarea>
                </div>

                <button type="submit"
                        style="padding:.75rem 2rem;background:#4f46e5;color:white;border:none;border-radius:10px;font-size:.95rem;font-weight:600;cursor:pointer;">
                    {{ $stats['graded'] > 0 ? '✅ Update Project Grade' : '✅ Submit Project Grade' }}
                </button>

            </form>
        </div>
    </div>

</div>
@endsection
