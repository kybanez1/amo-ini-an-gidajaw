@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-dashboard.css') }}">
@endpush

@section('content')
<div class="pms-dash">

    {{-- HEADER --}}
    <div class="dash-header">
        <div class="dash-greeting">
            <h2>Good day, {{ Auth::user()->name }} 👋</h2>
            <p>Here's what's happening in your classroom</p>
        </div>
        <div class="header-actions">
            <span class="role-pill">🎓 Teacher</span>
            <span style="background:#eef2ff;border:1px solid #c7d2fe;padding:.4rem .9rem;
                         border-radius:999px;font-size:.75rem;font-weight:700;
                         letter-spacing:.15em;color:#4f46e5;font-family:monospace;
                         cursor:pointer;" onclick="copyTeacherCode()" title="Click to copy">
                🔑 {{ Auth::user()->teacher_code ?? 'N/A' }}
            </span>
            <a href="{{ route('teacher.students.index') }}" class="btn-outline">🧑‍🎓 My Students</a>
            <a href="{{ route('teacher.groups.create') }}" class="btn-outline">＋ New Group</a>
            <a href="{{ route('teacher.sections.index') }}" class="btn-outline" style="border-color:#a5b4fc;color:#4f46e5;">🏫 Sections</a>
            <a href="{{ route('teacher.projects.create') }}" class="btn-primary">＋ New Project</a>
        </div>
    </div>

    {{-- STATS — all passed from controller, no inline queries --}}
    <div class="stats-row">

        <div class="stat-card">
            📁 <strong>{{ $totalProjects }}</strong> Projects
        </div>

        <div class="stat-card">
            👥 <strong>{{ $totalGroups }}</strong> Groups
        </div>

        <div class="stat-card">
            🧑‍🎓 <strong>{{ $totalStudents }}</strong> Students
        </div>

        <div class="stat-card">
            ⏳ <strong>{{ $pendingGrades }}</strong> Pending Grades
        </div>

        <div class="stat-card">
            🏫 <strong>{{ $totalSections }}</strong> Sections
        </div>

    </div>

    <div style="display:grid;grid-template-columns:1fr 360px;gap:1.5rem;">

        {{-- LEFT --}}
        <div>

            {{-- PROJECTS --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">📂 Your Projects</div>
                    <a href="{{ route('teacher.projects.index') }}" class="panel-action">View all →</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Group</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td><strong>{{ $project->title }}</strong></td>
                                <td>{{ $project->group->name ?? '—' }}</td>
                                <td>{{ ucfirst($project->status) }}</td>
                                <td>
                                    <a href="{{ route('teacher.projects.show', $project->id) }}" class="action-btn">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4">No projects yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- RECENTLY GRADED — passed from controller, scoped to this teacher --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">✅ Recently Graded</div>
                    <a href="{{ route('teacher.graded.index') }}" class="panel-action">View all →</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Project</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentlyGraded as $submission)
                            <tr>
                                <td>{{ $submission->student->name ?? 'Unknown' }}</td>
                                <td>{{ $submission->project->title ?? 'Project' }}</td>
                                <td><span class="badge badge-success">Graded</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3">No graded submissions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- GROUPS — passed from controller, scoped to this teacher --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">👥 Your Groups</div>
                    <a href="{{ route('teacher.groups.index') }}" class="panel-action">Manage →</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Students</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $group)
                            <tr>
                                <td>{{ $group->name }}</td>
                                <td>{{ $group->students_count }}</td>
                                <td>
                                    <a href="{{ route('teacher.groups.show', $group->id) }}" class="action-btn">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3">No groups yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- SECTIONS --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">🏫 Your Sections</div>
                    <a href="{{ route('teacher.sections.index') }}" class="panel-action">Manage →</a>
                </div>
                @if($sections->isEmpty())
                    <div style="padding:1.5rem;text-align:center;color:#9ca3af;font-size:.88rem;">
                        No sections yet.
                        <a href="{{ route('teacher.sections.index') }}" style="color:#4f46e5;font-weight:600;text-decoration:none;">Create one →</a>
                    </div>
                @else
                <table>
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Code</th>
                            <th>Students</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sections as $section)
                            <tr>
                                <td>
                                    <strong>{{ $section->name }}</strong>
                                    @if($section->semester)
                                        <div style="font-size:.75rem;color:#9ca3af;">{{ $section->semester }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-family:monospace;font-size:.85rem;font-weight:700;
                                                 color:#4f46e5;background:#eef2ff;padding:.2rem .5rem;
                                                 border-radius:6px;letter-spacing:.1em;">
                                        {{ $section->code }}
                                    </span>
                                </td>
                                <td>{{ $section->students_count }}</td>
                                <td>
                                    <a href="{{ route('teacher.sections.show', $section->id) }}" class="action-btn">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>

        </div>

        {{-- RIGHT — students scoped to this teacher's groups --}}
        <div>
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">🧑‍🎓 Students</div>
                </div>
                @forelse($students->take(10) as $student)
                    <div style="padding:1rem;border-top:1px solid #f3f4f6;">
                        {{ $student->name }}
                    </div>
                @empty
                    <div style="padding:1.5rem;color:#9ca3af;text-align:center;">No students yet.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/pages/teacher-dashboard.js') }}"></script>
<script>
function copyTeacherCode() {
    var code = '{{ Auth::user()->teacher_code ?? "" }}';
    navigator.clipboard.writeText(code).then(function() {
        alert('Teacher code ' + code + ' copied!');
    }).catch(function() {
        // Fallback for browsers that don't support clipboard API
        var el = document.createElement('textarea');
        el.value = code;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        alert('Teacher code ' + code + ' copied!');
    });
}
</script>
@endsection
