@extends('layouts.app')

@section('title', $group->name)

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-group-show.css') }}">
@endsection

@section('content')
<div class="pms-page">
    <div class="breadcrumb">
        <a href="{{ route('teacher.dashboard') }}">Dashboard</a> ›
        <a href="{{ route('teacher.groups.index') }}">Groups</a> ›
        {{ $group->name }}
    </div>

    <div class="page-header">
        <div>
            <div class="page-title">{{ $group->name }}</div>
            <div class="page-subtitle">
                {{ $group->description ?? 'No description' }} &nbsp;·&nbsp;
                <span class="badge {{ $group->status === 'active' ? 'badge-active' : 'badge-inactive' }}">{{ ucfirst($group->status) }}</span>
            </div>
        </div>
        <div style="display:flex;gap:0.75rem;">
            <a href="{{ route('teacher.groups.edit', $group->id) }}" class="btn-outline">✏️ Edit Group</a>
            <a href="{{ route('teacher.groups.index') }}" class="btn-outline">← Back</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert-error">⚠️ {{ session('error') }}</div>
    @endif

    <div class="stats-row">
        <div class="stat-card">
            <div class="value">{{ $students->total() }}</div>
            <div class="label">Students</div>
        </div>
        <div class="stat-card">
            <div class="value">{{ $group->projects()->count() }}</div>
            <div class="label">Projects</div>
        </div>
        <div class="stat-card">
            <div class="value">{{ $group->created_at->format('M d') }}</div>
            <div class="label">Created</div>
        </div>
    </div>

    {{-- JOIN CODE CARD --}}
    <div class="info-card" style="margin-bottom:1.5rem;background:linear-gradient(135deg,#eef2ff,#f5f3ff);border-color:#c7d2fe;">

        <h3 style="color:#4338ca;margin-bottom:1rem;">🔑 Group Join Code</h3>

        <p style="font-size:.85rem;color:#6b7280;margin-bottom:1.25rem;">
            Share this code with your students. They enter it on their dashboard to join this group
            and get access to all assigned projects.
        </p>

        <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">

            {{-- CODE DISPLAY --}}
            <div id="joinCodeDisplay"
                 style="font-size:2.2rem;font-weight:800;letter-spacing:.3em;color:#4f46e5;
                        background:white;border:2px solid #c7d2fe;border-radius:14px;
                        padding:.75rem 1.75rem;font-family:monospace;cursor:pointer;
                        user-select:all;"
                 onclick="copyCode()"
                 title="Click to copy">
                {{ $group->join_code ?? '------' }}
            </div>

            {{-- COPY BUTTON --}}
            <button onclick="copyCode()"
                    id="copyBtn"
                    type="button"
                    style="padding:.7rem 1.2rem;background:#4f46e5;color:white;border:none;
                           border-radius:10px;font-weight:600;font-size:.85rem;cursor:pointer;">
                📋 Copy Code
            </button>

            {{-- REGENERATE BUTTON --}}
            <form method="POST"
                  action="{{ route('teacher.groups.regenerateCode', $group->id) }}"
                  onsubmit="return confirm('Generate a new join code? The old code will stop working immediately.')">
                @csrf
                <button type="submit"
                        style="padding:.7rem 1.2rem;background:white;color:#6b7280;
                               border:1px solid #d1d5db;border-radius:10px;
                               font-weight:600;font-size:.85rem;cursor:pointer;">
                    🔄 New Code
                </button>
            </form>

        </div>

        <div id="copyConfirm"
             style="display:none;margin-top:.75rem;font-size:.82rem;color:#166534;font-weight:600;">
            ✅ Code copied to clipboard!
        </div>

    </div>

    <div class="two-col">
        <!-- Students Panel -->
        <div class="info-card">
            <h3>👤 Students in this Group</h3>
            <div class="student-list">
                @forelse($students as $student)
                <div class="student-row">
                    <div class="student-avatar">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
                    <div class="student-info">
                        <div class="student-name">{{ $student->name }}</div>
                        <div class="student-meta">{{ $student->student_id ?? 'No ID' }} · {{ $student->department ?? 'No course' }}</div>
                    </div>
                    <form method="POST" action="{{ route('teacher.groups.removeStudent', [$group->id, $student->id]) }}"
                          onsubmit="return confirm('Remove {{ $student->name }} from this group?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger" style="padding:4px 10px;font-size:0.75rem;">Remove</button>
                    </form>
                </div>
                @empty
                <div class="empty-note">No students in this group yet.</div>
                @endforelse
            </div>

            @if($students->hasPages())
            <div style="margin-top:1rem;">{{ $students->links() }}</div>
            @endif

            <!-- Add Student -->
            @if($availableStudents->count() > 0)
            <form method="POST" action="{{ route('teacher.groups.addStudent', $group->id) }}" class="add-student-form">
                @csrf
                <select name="student_id" required>
                    <option value="">— Select student to add —</option>
                    @foreach($availableStudents as $s)
                    <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->student_id ?? 'No ID' }})</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary" style="white-space:nowrap;">+ Add</button>
            </form>
            @else
            <p style="font-size:0.8rem;color:#9ca3af;margin-top:1rem;">All registered students are already in this group.</p>
            @endif
        </div>

        <!-- Projects Panel -->
        <div class="info-card">
            <h3>📁 Projects Assigned to Group</h3>
            @forelse($group->projects as $project)
            <div class="student-row">
                <div class="student-avatar" style="background:#fef3c7;color:#d97706;">📁</div>
                <div class="student-info">
                    <div class="student-name">{{ $project->title }}</div>
                    <div class="student-meta">Due {{ $project->due_date ? $project->due_date->format('M d, Y') : '—' }} · {{ ucfirst($project->status) }}</div>
                </div>
                <a href="{{ route('teacher.projects.show', $project->id) }}" class="btn-outline" style="padding:4px 10px;font-size:0.75rem;">View</a>
            </div>
            @empty
            <div class="empty-note">No projects assigned to this group yet.</div>
            @endforelse

            <div style="margin-top:1.25rem;">
                <a href="{{ route('teacher.projects.create') }}" class="btn-primary" style="width:100%;justify-content:center;">+ Create Project for Group</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var joinCode = "{{ $group->join_code ?? '' }}";

    function copyCode() {
        if (!joinCode) return;
        navigator.clipboard.writeText(joinCode).then(function () {
            var confirmEl = document.getElementById('copyConfirm');
            var btn = document.getElementById('copyBtn');
            if (confirmEl) confirmEl.style.display = 'block';
            if (btn) btn.textContent = '✅ Copied!';
            setTimeout(function () {
                if (confirmEl) confirmEl.style.display = 'none';
                if (btn) btn.textContent = '📋 Copy Code';
            }, 2500);
        });
    }
</script>
@endsection
