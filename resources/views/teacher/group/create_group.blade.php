@extends('layouts.app')

@section('title', 'Create Group')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-group-create.css') }}">
<style>
    .section-select-wrapper { position: relative; }
    .section-select-wrapper select {
        width: 100%; padding: 0.6rem 0.9rem;
        border: 1.5px solid #e5e7eb; border-radius: 8px;
        font-size: 0.92rem; background: #fff; color: #374151;
        appearance: none; cursor: pointer; transition: border-color .2s;
    }
    .section-select-wrapper select:focus {
        outline: none; border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.12);
    }
    .section-select-wrapper::after {
        content: '▾'; position: absolute; right: 0.85rem; top: 50%;
        transform: translateY(-50%); color: #9ca3af; pointer-events: none;
    }
    .section-badge {
        display: none; align-items: center; gap: 0.35rem;
        padding: 0.25rem 0.65rem; background: #eef2ff; color: #4f46e5;
        border-radius: 999px; font-size: 0.75rem; font-weight: 600; margin-top: 0.5rem;
    }
    .student-filter-bar { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; flex-wrap: wrap; }
    .filter-btn {
        padding: 0.3rem 0.85rem; border-radius: 999px; border: 1.5px solid #e5e7eb;
        background: #f9fafb; color: #6b7280; font-size: 0.78rem; font-weight: 600;
        cursor: pointer; transition: all .15s; white-space: nowrap;
    }
    .filter-btn:hover, .filter-btn.active { background: #eef2ff; border-color: #6366f1; color: #4f46e5; }
    .filter-btn.active { box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
    .filter-count { background: #6366f1; color: #fff; border-radius: 999px; padding: 0 0.4rem; font-size: 0.7rem; margin-left: 0.25rem; }
    .student-search-input {
        width: 100%; padding: 0.55rem 0.85rem 0.55rem 2.2rem;
        border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 0.88rem;
        background: #f9fafb url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%239ca3af' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zm-5.442 1.656a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z'/%3E%3C/svg%3E") 0.7rem center no-repeat;
        margin-bottom: 0.5rem; transition: border-color .2s; box-sizing: border-box;
    }
    .student-search-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
    .student-dropdown { max-height: 260px; overflow-y: auto; border: 1.5px solid #e5e7eb; border-radius: 8px; background: #fff; }
    .student-option {
        display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.65rem 0.9rem;
        cursor: pointer; border-bottom: 1px solid #f3f4f6; transition: background .12s;
    }
    .student-option:last-child { border-bottom: none; }
    .student-option:hover { background: #f9fafb; }
    .student-option input[type="checkbox"] { margin-top: 2px; accent-color: #6366f1; flex-shrink: 0; }
    .student-option.hidden-student { display: none !important; }
    .student-section-tag {
        display: none; align-items: center; gap: 0.2rem; padding: 0.1rem 0.45rem;
        background: #dcfce7; color: #16a34a; border-radius: 999px; font-size: 0.68rem; font-weight: 700; margin-left: 0.4rem;
    }
    .student-option.in-section .student-section-tag { display: inline-flex; }
    .no-students-msg { padding: 1.2rem; text-align: center; color: #9ca3af; font-size: 0.85rem; display: none; }
    .form-hint { font-size: 0.78rem; color: #9ca3af; margin-top: 0.35rem; }
</style>
@endsection

@section('content')
<div class="pms-page">
    <div class="breadcrumb">
        <a href="{{ route('teacher.dashboard') }}">Dashboard</a> ›
        <a href="{{ route('teacher.groups.index') }}">Groups</a> › Create
    </div>
    <div class="page-title">👥 Create New Group</div>

    @if($errors->any())
    <div class="alert-error">
        <strong>Please fix the following:</strong>
        <ul style="margin:0.5rem 0 0;padding-left:1.25rem;">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('teacher.groups.store') }}">
            @csrf

            {{-- Group Name --}}
            <div class="form-field">
                <label>Group Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Group Alpha — BSIT 3A" />
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Description --}}
            <div class="form-field">
                <label>Description <span style="color:#9ca3af;font-weight:400;">(optional)</span></label>
                <textarea name="description" rows="3" placeholder="Brief description of this group..." style="resize:vertical;">{{ old('description') }}</textarea>
                @error('description')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Class Section --}}
            <div class="form-field">
                <label>Class Section <span style="color:#9ca3af;font-weight:400;">(optional — filters the student list below)</span></label>
                @if($sections->isEmpty())
                    <div style="padding:0.75rem 1rem;background:#f9fafb;border-radius:8px;color:#9ca3af;font-size:0.85rem;border:1.5px dashed #e5e7eb;">
                        No sections yet. <a href="{{ route('teacher.sections.index') }}" style="color:#6366f1;font-weight:600;">Create a section</a> to use this filter.
                    </div>
                @else
                    <div class="section-select-wrapper">
                        <select name="section_id" id="sectionPicker">
                            <option value="">— No section (show all students) —</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}"
                                    {{ old('section_id') == $section->id ? 'selected' : '' }}
                                    data-name="{{ $section->name }}">
                                    {{ $section->name }}
                                    @if($section->school_year) · {{ $section->school_year }}@endif
                                    @if($section->semester) · {{ $section->semester }}@endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="sectionBadge" class="section-badge">
                        🏷️ <span id="sectionBadgeText"></span>
                    </div>
                @endif
                @error('section_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Add Students --}}
            <div class="form-field">
                <label>Add Students <span style="color:#9ca3af;font-weight:400;">(optional — can add later)</span></label>

                @if($students->isEmpty())
                    <div style="padding:1rem;background:#f9fafb;border-radius:8px;color:#9ca3af;font-size:0.85rem;text-align:center;">
                        No students registered yet. You can add students after they register.
                    </div>
                @else
                    @php
                        $sectionStudentMap = [];
                        foreach($sections as $sec) {
                            $sectionStudentMap[$sec->id] = $sec->students->pluck('id')->toArray();
                        }
                    @endphp

                    <div class="student-filter-bar">
                        <button type="button" class="filter-btn active" data-filter="all">
                            All <span class="filter-count" id="countAll">{{ $students->count() }}</span>
                        </button>
                        <button type="button" class="filter-btn" data-filter="section" id="filterSectionBtn" style="display:none;">
                            In Selected Section <span class="filter-count" id="countSection">0</span>
                        </button>
                        <button type="button" class="filter-btn" data-filter="selected" id="filterSelectedBtn">
                            Selected <span class="filter-count" id="countSelected">0</span>
                        </button>
                    </div>

                    <input type="text" id="studentSearch" class="student-search-input" placeholder="Search by name or ID…">

                    <div class="student-dropdown" id="studentList">
                        @foreach($students as $student)
                        <label class="student-option"
                               data-name="{{ strtolower($student->name) }}"
                               data-sid="{{ strtolower($student->student_id ?? '') }}"
                               data-userid="{{ $student->id }}">
                            <input type="checkbox" name="students[]" value="{{ $student->id }}"
                                   {{ in_array($student->id, old('students', [])) ? 'checked' : '' }}
                                   class="student-checkbox" />
                            <div style="flex:1;min-width:0;">
                                <div style="font-weight:500;display:flex;align-items:center;flex-wrap:wrap;gap:0.2rem;">
                                    {{ $student->name }}
                                    <span class="student-section-tag">✔ In section</span>
                                </div>
                                <div style="font-size:0.75rem;color:#9ca3af;">
                                    {{ $student->student_id ?? 'No ID' }} · {{ $student->department ?? 'No course' }}
                                </div>
                            </div>
                        </label>
                        @endforeach
                        <div class="no-students-msg" id="noStudentsMsg">No students match your search.</div>
                    </div>
                    <div class="form-hint" id="studentHint">{{ $students->count() }} student(s) available</div>
                @endif
            </div>

            <div class="form-actions">
                <a href="{{ route('teacher.groups.index') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary">✔ Create Group</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const sectionStudentMap = @json($sectionStudentMap ?? []);

    const sectionPicker     = document.getElementById('sectionPicker');
    const sectionBadge      = document.getElementById('sectionBadge');
    const sectionBadgeText  = document.getElementById('sectionBadgeText');
    const filterSectionBtn  = document.getElementById('filterSectionBtn');
    const studentSearch     = document.getElementById('studentSearch');
    const studentList       = document.getElementById('studentList');
    const noStudentsMsg     = document.getElementById('noStudentsMsg');
    const studentHint       = document.getElementById('studentHint');
    const countAll          = document.getElementById('countAll');
    const countSection      = document.getElementById('countSection');
    const countSelected     = document.getElementById('countSelected');

    let currentFilter    = 'all';
    let activeSectionIds = [];

    function allRows() {
        return studentList ? [...studentList.querySelectorAll('.student-option')] : [];
    }

    function updateCounts() {
        const rows = allRows();
        if (countAll)      countAll.textContent      = rows.length;
        if (countSection)  countSection.textContent  = rows.filter(r => activeSectionIds.includes(+r.dataset.userid)).length;
        if (countSelected) countSelected.textContent = rows.filter(r => r.querySelector('.student-checkbox')?.checked).length;
    }

    function applyFilter() {
        const query = studentSearch ? studentSearch.value.toLowerCase().trim() : '';
        let visible = 0;
        allRows().forEach(function(row) {
            const userId  = +row.dataset.userid;
            const checked = row.querySelector('.student-checkbox')?.checked;

            let show = !query || row.dataset.name.includes(query) || row.dataset.sid.includes(query);
            if (currentFilter === 'section')  show = show && activeSectionIds.includes(userId);
            if (currentFilter === 'selected') show = show && !!checked;

            row.classList.toggle('hidden-student', !show);
            if (show) visible++;
        });
        if (noStudentsMsg) noStudentsMsg.style.display = visible === 0 ? 'block' : 'none';
        if (studentHint) {
            const label = currentFilter === 'section' ? 'in this section' : currentFilter === 'selected' ? 'selected' : 'shown';
            studentHint.textContent = visible + ' student(s) ' + label;
        }
        updateCounts();
    }

    function onSectionChange() {
        if (!sectionPicker) return;
        const sectionId = sectionPicker.value;
        const label     = sectionPicker.options[sectionPicker.selectedIndex]?.dataset?.name || '';

        if (sectionId && sectionStudentMap[sectionId]) {
            activeSectionIds = sectionStudentMap[sectionId];
            allRows().forEach(function(row) {
                row.classList.toggle('in-section', activeSectionIds.includes(+row.dataset.userid));
            });
            if (filterSectionBtn) filterSectionBtn.style.display = '';
            if (sectionBadge)     { sectionBadge.style.display = 'inline-flex'; }
            if (sectionBadgeText) sectionBadgeText.textContent = label;
            setFilter('section');
        } else {
            activeSectionIds = [];
            allRows().forEach(function(row) { row.classList.remove('in-section'); });
            if (filterSectionBtn) filterSectionBtn.style.display = 'none';
            if (sectionBadge)     sectionBadge.style.display = 'none';
            setFilter('all');
        }
    }

    function setFilter(filter) {
        currentFilter = filter;
        document.querySelectorAll('.filter-btn').forEach(function(btn) {
            btn.classList.toggle('active', btn.dataset.filter === filter);
        });
        applyFilter();
    }

    if (sectionPicker) {
        sectionPicker.addEventListener('change', onSectionChange);
        if (sectionPicker.value) onSectionChange();
    }
    if (studentSearch) studentSearch.addEventListener('input', applyFilter);
    document.querySelectorAll('.filter-btn').forEach(function(btn) {
        btn.addEventListener('click', function() { setFilter(btn.dataset.filter); });
    });
    if (studentList) {
        studentList.addEventListener('change', function() {
            updateCounts();
            if (currentFilter === 'selected') applyFilter();
        });
    }

    updateCounts();
})();
</script>
@endsection
