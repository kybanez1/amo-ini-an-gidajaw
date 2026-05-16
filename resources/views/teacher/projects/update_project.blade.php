@extends('layouts.app')

@section('title', 'Update Project')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-project-update.css') }}">
@endsection

@section('content')

<div class="pms-page">

    <div class="page-header">

        <div>
            <div class="page-title">
                ✏️ Update Project
            </div>

            <div class="page-subtitle">
                Edit project details and tasks
            </div>
        </div>

        <a href="{{ route('teacher.projects.index') }}"
           class="btn-outline">
            ← Back
        </a>

    </div>

    @if(session('success'))
        <div class="alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-error">

            <strong>Please fix the following:</strong>

            <ul style="margin-top:10px;padding-left:20px;">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>
    @endif

    <div class="card">

        <form method="POST"
              action="{{ route('teacher.projects.update', $project->id) }}"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            {{-- TITLE --}}
            <div class="form-field">

                <label>Project Title *</label>

                <input type="text"
                       name="title"
                       value="{{ old('title', $project->title) }}"
                       required>

            </div>

            {{-- DESCRIPTION --}}
            <div class="form-field">

                <label>Description</label>

                <textarea name="description"
                          rows="4">{{ old('description', $project->description) }}</textarea>

            </div>

            {{-- REQUIREMENTS --}}
            <div class="form-field">

                <label>Requirements</label>

                <textarea name="requirements"
                          rows="4">{{ old('requirements', $project->requirements) }}</textarea>

            </div>

            {{-- GROUP --}}
            <div class="form-field">

                <label>Group</label>

                <select name="group_id">

                    <option value="">
                        Select Group
                    </option>

                    @foreach($groups as $group)

                        <option value="{{ $group->id }}"
                            {{ old('group_id', $project->group_id) == $group->id ? 'selected' : '' }}>

                            {{ $group->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- STATUS --}}
            <div class="form-field">

                <label>Status</label>

                <select name="status" required>

                    <option value="draft"
                        {{ old('status', $project->status) == 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>

                    <option value="published"
                        {{ old('status', $project->status) == 'published' ? 'selected' : '' }}>
                        Published
                    </option>

                    <option value="ongoing"
                        {{ old('status', $project->status) == 'ongoing' ? 'selected' : '' }}>
                        Ongoing
                    </option>

                    <option value="completed"
                        {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>
                        Completed
                    </option>

                    <option value="archived"
                        {{ old('status', $project->status) == 'archived' ? 'selected' : '' }}>
                        Archived
                    </option>

                </select>

            </div>

            {{-- START DATE --}}
            <div class="form-field">

                <label>Start Date</label>

                <input type="datetime-local"
                       name="start_date"
                       value="{{ old('start_date', optional($project->start_date)->format('Y-m-d\TH:i')) }}">

            </div>

            {{-- DUE DATE --}}
            <div class="form-field">

                <label>Due Date</label>

                <input type="datetime-local"
                       name="due_date"
                       value="{{ old('due_date', optional($project->due_date)->format('Y-m-d\TH:i')) }}">

            </div>

            {{-- INSTRUCTION FILE / LINK --}}
            <div class="form-field">

                <label>Instruction File <span style="color:#9ca3af;font-weight:400;">(optional)</span></label>

                {{-- TAB SWITCHER --}}
                <div style="display:flex;gap:0;margin-bottom:.75rem;border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;width:fit-content;">
                    <button type="button" id="tab-file-update"
                            onclick="switchTab('update','file')"
                            style="padding:.5rem 1.1rem;font-size:.82rem;font-weight:600;border:none;cursor:pointer;background:#4f46e5;color:white;">
                        📎 Upload File
                    </button>
                    <button type="button" id="tab-link-update"
                            onclick="switchTab('update','link')"
                            style="padding:.5rem 1.1rem;font-size:.82rem;font-weight:600;border:none;cursor:pointer;background:white;color:#6b7280;">
                        🔗 Paste Link
                    </button>
                </div>

                {{-- CURRENT FILE/LINK --}}
                @if($project->instruction_file || $project->instruction_link)
                    <div style="padding:.75rem 1rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;margin-bottom:.75rem;font-size:.82rem;color:#166534;">
                        ✅ Current:
                        @if($project->instruction_file)
                            <strong>{{ $project->instruction_file_name ?? basename($project->instruction_file) }}</strong>
                        @elseif($project->instruction_link)
                            <a href="{{ $project->instruction_link }}" target="_blank" style="color:#166534;">
                                {{ $project->instruction_link }}
                            </a>
                        @endif
                        — upload/paste a new one to replace it.
                    </div>
                @endif

                {{-- FILE UPLOAD --}}
                <div id="panel-file-update">
                    <input type="file"
                           name="instruction_file"
                           style="padding:.5rem;border:1.5px dashed #d1d5db;border-radius:10px;width:100%;box-sizing:border-box;">
                    <div style="font-size:.78rem;color:#9ca3af;margin-top:6px;">
                        Upload PDF, DOCX, PPT, ZIP, Images. Maximum file size: 20MB
                    </div>
                </div>

                {{-- LINK INPUT --}}
                <div id="panel-link-update" style="display:none;">
                    <input type="url"
                           name="instruction_link"
                           placeholder="https://drive.google.com/... or any URL"
                           value="{{ old('instruction_link', $project->instruction_link ?? '') }}"
                           style="width:100%;padding:.75rem 1rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:.9rem;box-sizing:border-box;">
                    <div style="font-size:.78rem;color:#9ca3af;margin-top:6px;">
                        Paste a Google Drive, Dropbox, OneDrive link, or any URL.
                    </div>
                </div>

            </div>

            {{-- MAX SCORE --}}
            <div class="form-field">

                <label>Max Score</label>

                <input type="number"
                       name="max_score"
                       min="1"
                       value="{{ old('max_score', $project->max_score) }}"
                       required>

            </div>

            {{-- TASKS --}}
            <hr style="margin:2rem 0;border:none;border-top:1px solid #e5e7eb;">

            <div style="margin-bottom:1rem;">

                <h3 style="font-size:1.05rem;font-weight:700;color:#111827;">
                    📋 Project Tasks
                </h3>

            </div>

            <div id="tasks-wrapper">

                @forelse($project->tasks as $index => $task)

                    <div class="task-box">

                        <div class="flex-between" style="margin-bottom:1rem;">

                            <div class="task-title">
                                Task {{ $index + 1 }}
                            </div>

                            <button type="button"
                                    class="remove-task-btn"
                                    onclick="removeTask(this)">
                                Remove
                            </button>

                        </div>

                        <input type="hidden"
                               name="tasks[{{ $index }}][id]"
                               value="{{ $task->id }}">

                        <div class="form-field">

                            <label>Task Title</label>

                            <input type="text"
                                   name="tasks[{{ $index }}][title]"
                                   value="{{ old('tasks.'.$index.'.title', $task->title) }}">

                        </div>

                        <div class="form-field">

                            <label>Task Description</label>

                            <textarea name="tasks[{{ $index }}][description]"
                                      rows="3">{{ old('tasks.'.$index.'.description', $task->description) }}</textarea>

                        </div>

                        <div class="form-field">

                            <label>Task Due Date</label>
                            <input type="datetime-local"
                                   name="tasks[{{ $index }}][due_date]"
                                   value="{{ old('tasks.'.$index.'.due_date', optional($task->due_date)->format('Y-m-d\TH:i')) }}">
                        </div>

                        <div class="task-field">
                            <label>Max Points
                                <span style="color:#9ca3af;font-weight:400;">(current: {{ $task->max_points ?? 100 }})</span>
                            </label>
                            <input type="number"
                                   name="tasks[{{ $index }}][max_points]"
                                   min="1" max="10000"
                                   value="{{ old('tasks.'.$index.'.max_points', $task->max_points ?? 100) }}"
                                   style="width:100%;">
                        </div>

                    </div>

                @empty

                    <div class="task-box">

                        <div class="task-title">
                            Task 1
                        </div>

                        <div class="form-field">

                            <label>Task Title</label>

                            <input type="text"
                                   name="tasks[0][title]">

                        </div>

                        <div class="form-field">

                            <label>Task Description</label>

                            <textarea name="tasks[0][description]"
                                      rows="3"></textarea>

                        </div>

                        <div class="form-field">

                            <label>Task Due Date</label>
                            <input type="datetime-local" name="tasks[0][due_date]">
                        </div>

                        <div class="task-field">
                            <label>Max Points</label>
                            <input type="number" name="tasks[0][max_points]"
                                   min="1" max="10000" placeholder="100" value="100"
                                   style="width:100%;">
                        </div>

                    </div>

                @endforelse

            </div>

            <button type="button"
                    class="add-task-btn"
                    onclick="addTask()">

                ➕ Add Another Task

            </button>

            <div style="display:flex;gap:10px;margin-top:2rem;">

                <button type="submit"
                        class="btn-primary">

                    💾 Save Changes

                </button>

                <a href="{{ route('teacher.projects.index') }}"
                   class="btn-outline">

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/js/pages/teacher-project-update.js') }}"></script>
@endsection
