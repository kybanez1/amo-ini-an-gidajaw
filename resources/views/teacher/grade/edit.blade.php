@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-grade-edit.css') }}">
@endpush

@section('content')
<div class="grade-wrap">

    <a href="{{ route('teacher.grades.project', $project->id) }}" class="page-back">← Back to Grading</a>

    @if($errors->any())
        <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#fee2e2;color:#991b1b;">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Student Banner --}}
    <div class="card">
        <div class="student-banner">
            <div class="student-avatar">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
            <div>
                <div class="student-name">{{ $student->name }}</div>
                <div class="student-email">{{ $student->email }}</div>
                @if($student->student_id)
                    <div style="font-size:.75rem;color:#6b7280;margin-top:2px;">🆔 {{ $student->student_id }}</div>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="project-info">
                <div class="info-item">
                    <div class="info-label">Project</div>
                    <div class="info-value">{{ Str::limit($project->title, 30) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Max Score</div>
                    <div class="info-value">{{ $project->max_score }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Submitted</div>
                    {{-- FIX: null-safe pivot access --}}
                    <div class="info-value">
                        {{ $assignment && $assignment->pivot && $assignment->pivot->submitted_at
                            ? \Carbon\Carbon::parse($assignment->pivot->submitted_at)->format('M d, Y')
                            : ($submission->submitted_at
                                ? $submission->submitted_at->format('M d, Y')
                                : '—') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Student Submission --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">📄 Student Submission</div>
        </div>
        <div class="card-body">

            <div class="form-group">
                <div class="info-label">Message / Notes</div>
                <div class="submission-box">
                    {{ $submission->content ?? $submission->message ?? 'No message provided.' }}
                </div>
            </div>

            <div class="form-group">
                <div class="info-label">Uploaded File</div>
                @if($submission->file_path)
                    <div style="margin-top:10px;padding:14px;border:1px solid #e5e7eb;border-radius:12px;background:#f9fafb;">
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
                            <div>📎 <strong>{{ basename($submission->file_path) }}</strong></div>
                            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank"
                               style="padding:8px 14px;background:#4f46e5;color:white;border-radius:8px;text-decoration:none;font-size:.85rem;font-weight:600;">
                                ⬇ Download File
                            </a>
                        </div>
                    </div>
                @else
                    <div style="color:#9ca3af;margin-top:8px;">No file uploaded.</div>
                @endif
            </div>

        </div>
    </div>

    {{-- Grading Form --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">✏️ Grade This Student</div>
        </div>
        <div class="card-body">

            <form method="POST"
                  action="{{ route('teacher.grades.store', [$project->id, $student->id]) }}"
                  id="gradeForm">
                @csrf

                {{-- Score --}}
                <div class="form-group">
                    <label class="form-label">
                        Score <span style="color:#dc2626;">*</span>
                    </label>
                    <div class="score-input-row">
                        <input type="number"
                               name="score"
                               id="scoreInput"
                               class="score-input"
                               min="0"
                               max="{{ $project->max_score }}"
                               {{-- FIX: null-safe pivot score --}}
                               value="{{ old('score', $assignment && $assignment->pivot ? $assignment->pivot->score : '') }}"
                               required
                               oninput="updatePreview(this.value)">
                        <span class="score-max">/ {{ $project->max_score }}</span>
                    </div>
                    @error('score')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Score Preview --}}
                <div class="score-preview" id="scorePreview" style="display:none;">
                    <span class="score-preview-label">Grade Preview</span>
                    <span class="score-preview-val" id="previewVal">—</span>
                </div>

                {{-- Feedback --}}
                <div class="form-group">
                    <label class="form-label">
                        Feedback <span style="font-weight:400;color:#9ca3af;">(optional)</span>
                    </label>
                    <textarea name="feedback"
                              class="feedback-textarea"
                              placeholder="Provide constructive feedback...">{{ old('feedback', $assignment && $assignment->pivot ? $assignment->pivot->feedback : '') }}</textarea>
                    @error('feedback')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    {{-- FIX: null-safe pivot assignment_status --}}
                    {{ ($assignment && $assignment->pivot && $assignment->pivot->assignment_status === 'graded')
                        ? '✅ Update Grade'
                        : '✅ Submit Grade' }}
                </button>

            </form>
        </div>
    </div>

</div>
@endsection

<script src="{{ asset('assets/js/pages/teacher-grade-edit.js') }}"></script>
