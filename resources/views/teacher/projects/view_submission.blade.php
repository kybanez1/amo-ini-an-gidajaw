@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-view-submission.css') }}">
@endpush

@section('content')
<div class="wrap">

    <div class="card">

        <div class="title">
            📄 Student Submission
        </div>

        <div class="section">
            <div class="label">Student</div>
            <div class="value">
                {{ $submission->student->name ?? 'Unknown' }}
            </div>
        </div>

        <div class="section">
            <div class="label">Task</div>
            <div class="value">
                {{ $submission->task->title ?? 'General Submission' }}
            </div>
        </div>

        <div class="section">
            <div class="label">Submitted At</div>
            <div class="value">
                {{ $submission->submitted_at
                    ? $submission->submitted_at->format('M d, Y h:i A')
                    : '—'
                }}
            </div>
        </div>

        <div class="section">
            <div class="label">Content</div>

            <div style="padding:1rem;border:1px solid #e5e7eb;border-radius:12px;background:#fafafa;white-space:pre-wrap;">
                {{ $submission->content ?: 'No written content submitted.' }}
            </div>
        </div>

        <div class="section">
            <div class="label">Attached File</div>

            @if($submission->file_path)

                <a href="{{ asset('storage/' . $submission->file_path) }}"
                   target="_blank"
                   class="btn btn-primary">

                    📎 Open File

                </a>

            @else

                <div class="value">
                    No file uploaded.
                </div>

            @endif
        </div>

    </div>

    <div class="card">

        <div class="title">
            📝 Grade Submission
        </div>

        <form method="POST"
              action="{{ route('teacher.grades.store', [$project->id, $submission->student_id]) }}">

            @csrf

            <input type="hidden"
                   name="submission_id"
                   value="{{ $submission->id }}">

            <div class="grade-box">

                <div class="section">

                    <div class="label">
                        Score
                    </div>

                    <input type="number"
                           name="score"
                           min="0"
                           max="{{ $project->max_score }}"
                           value="{{ $submission->score ?? 0 }}"
                           required>

                </div>

                <div class="section">

                    <div class="label">
                        Status
                    </div>

                    <input type="text"
                           value="graded"
                           disabled>

                </div>

            </div>

            <div class="section">

                <div class="label">
                    Feedback
                </div>

                <textarea name="feedback"
                          rows="5">{{ $submission->feedback }}</textarea>

            </div>

            <div style="display:flex;gap:10px;">

                <button type="submit"
                        class="btn btn-primary">

                    ✅ Save Grade

                </button>

                <a href="{{ route('teacher.projects.show', $project->id) }}"
                   class="btn btn-outline">

                    ← Back

                </a>

            </div>

        </form>

    </div>

</div>

@endsection