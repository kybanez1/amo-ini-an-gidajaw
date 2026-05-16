@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-groups.css') }}">
@endpush

@section('title', 'My Groups')

@section('content')
<div class="pms-page">

    <div class="page-header">
        <div>
            <div class="page-title">👥 My Groups</div>
            <div class="page-subtitle">Organize your students into groups</div>
        </div>

        {{-- OPEN MODAL BUTTON --}}
        <button class="btn-primary" id="openCreateModal">+ New Group</button>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="groups-grid">

        @forelse($groups as $group)

            <div class="group-card">

                <div class="group-header">
                    <div class="group-icon">👥</div>
                    <span class="badge">ACTIVE</span>
                </div>

                <div class="group-name">{{ $group->name }}</div>

                <div class="group-desc">
                    {{ $group->description ?: 'No description available.' }}
                </div>

                <div class="group-stats">
                    <div class="stat">
                        <div class="stat-value">{{ $group->students->count() }}</div>
                        <div class="stat-label">Students</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value">{{ $group->projects()->count() }}</div>
                        <div class="stat-label">Projects</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value">{{ $group->created_at->format('M d') }}</div>
                        <div class="stat-label">Created</div>
                    </div>
                </div>

                <div class="group-actions">
                    <a href="{{ route('teacher.groups.show', $group->id) }}" class="btn-outline">👁 View</a>
                    <a href="{{ route('teacher.groups.edit', $group->id) }}" class="btn-outline">✏️ Edit</a>
                    <form method="POST"
                          action="{{ route('teacher.groups.destroy', $group->id) }}"
                          onsubmit="return confirm('Delete this group?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">🗑 Delete</button>
                    </form>
                </div>

            </div>

        @empty

            <div class="empty-state">
                <div style="font-size:60px;">👥</div>
                <h3>No groups yet</h3>
                <p>Create your first student group.</p>
                <button class="btn-primary" id="openCreateModal2">+ Create Group</button>
            </div>

        @endforelse

    </div>

</div>

{{-- ═══════════════════════════════════════════
     CREATE GROUP MODAL
     Placed OUTSIDE .pms-page, directly before @endsection
     so Sneat's layout-overlay cannot intercept clicks
═══════════════════════════════════════════ --}}
<div id="createGroupModal">
    <div class="modal-box" id="createGroupModalBox">

        <div class="modal-head">
            <div class="modal-head-title">👥 Create New Group</div>
            <button type="button" class="modal-close-btn" id="closeModalBtn">×</button>
        </div>

        <div class="modal-body">

            @if($errors->any())
            <div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:.75rem 1rem;border-radius:8px;font-size:.82rem;margin-bottom:1rem;">
                <strong>Please fix the following:</strong>
                <ul style="margin:.4rem 0 0;padding-left:1.2rem;">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('teacher.groups.store') }}" id="createGroupForm">
                @csrf

                <div class="form-group">
                    <label>Group Name <span style="color:#ef4444;">*</span></label>
                    <input type="text"
                           name="name"
                           id="groupNameInput"
                           value="{{ old('name') }}"
                           placeholder="e.g. BSIT 3A — Group Alpha"
                           required>
                </div>

                <div class="form-group">
                    <label>Description <span style="color:#9ca3af;font-weight:400;">(optional)</span></label>
                    <textarea name="description"
                              rows="2"
                              placeholder="Brief description of this group...">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Add Students <span style="color:#9ca3af;font-weight:400;">(optional — you can add later)</span></label>

                    @php
                        $allStudents = auth()->user()->myStudents()->orderBy('name')->get();
                    @endphp

                    @if($allStudents->isEmpty())
                        <div style="padding:.85rem;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;color:#9ca3af;font-size:.82rem;text-align:center;">
                            No students registered yet.
                        </div>
                    @else
                        <input type="text"
                               id="studentSearchInput"
                               placeholder="🔍 Search by name or ID..."
                               style="margin-bottom:8px;"
                               oninput="filterStudents(this.value)">

                        <div class="student-checklist" id="studentList">
                            @foreach($allStudents as $s)
                            <label class="student-check-item"
                                   data-name="{{ strtolower($s->name) }}"
                                   data-sid="{{ strtolower($s->student_id ?? '') }}">
                                <input type="checkbox"
                                       name="students[]"
                                       value="{{ $s->id }}"
                                       style="accent-color:#4f46e5;flex-shrink:0;"
                                       {{ in_array($s->id, old('students', [])) ? 'checked' : '' }}>
                                <div>
                                    <div style="font-weight:600;font-size:.85rem;">{{ $s->name }}</div>
                                    <div style="font-size:.72rem;color:#9ca3af;">
                                        ID: {{ $s->student_id ?? '—' }} &nbsp;·&nbsp; {{ $s->department ?? 'No course' }}
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        <div style="font-size:.72rem;color:#9ca3af;margin-top:5px;">
                            {{ $allStudents->count() }} student(s) available
                        </div>
                    @endif
                </div>

                <div class="modal-foot">
                    <button type="button" class="btn-outline" id="cancelModalBtn">Cancel</button>
                    <button type="submit" class="btn-primary">✅ Create Group</button>
                </div>

            </form>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/js/pages/teacher-groups.js') }}"></script>
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('createGroupModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            setTimeout(function () {
                var f = document.getElementById('groupNameInput');
                if (f) f.focus();
            }, 80);
        }
    });
</script>
@endif
@endsection
