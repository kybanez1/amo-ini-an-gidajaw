@extends('layouts.app')

@section('title', 'Create Group')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-group-create.css') }}">
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

            <div class="form-field">
                <label>Group Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Group Alpha — BSIT 3A" />
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-field">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Brief description of this group..." style="resize:vertical;">{{ old('description') }}</textarea>
                @error('description')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-field">
                <label>Add Students <span style="color:#9ca3af;font-weight:400;">(optional — can add later)</span></label>
                @if($students->isEmpty())
                <div style="padding:1rem;background:#f9fafb;border-radius:8px;color:#9ca3af;font-size:0.85rem;text-align:center;">
                    No students registered yet. You can add students after they register.
                </div>
                @else
                <div class="student-dropdown">
                    @foreach($students as $student)
                    <label class="student-option">
                        <input type="checkbox" name="students[]" value="{{ $student->id }}"
                               {{ in_array($student->id, old('students', [])) ? 'checked' : '' }} />
                        <div>
                            <div style="font-weight:500;">{{ $student->name }}</div>
                            <div style="font-size:0.75rem;color:#9ca3af;">{{ $student->student_id ?? 'No ID' }} · {{ $student->email }} · {{ $student->department ?? 'No course' }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
                <div class="form-hint">{{ $students->count() }} student(s) available</div>
                @endif
            </div>

            <div class="form-actions">
                <a href="{{ route('teacher.groups.index') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary">Create Group</button>
            </div>
        </form>
    </div>
</div>
@endsection