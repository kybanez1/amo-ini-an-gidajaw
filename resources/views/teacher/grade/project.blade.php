@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-grade-project.css') }}">
@endpush

@section('content')
<div class="wrap">

    {{-- SUCCESS --}}
    @if(session('success'))
        <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#dcfce7;color:#166534;">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- ERROR --}}
    @if(session('error'))
        <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#fee2e2;color:#991b1b;">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- ACTIONS --}}
    <div class="top-actions">

        <a href="{{ route('teacher.projects.show', $project->id) }}"
           class="btn btn-outline">
            ← Back to Project
        </a>

    </div>

    {{-- STATS --}}
    <div class="stats">

        <div class="stat">
            <div class="stat-label">Total Students</div>
            <div class="stat-value">
                {{ $stats['total'] }}
            </div>
        </div>

        <div class="stat">
            <div class="stat-label">Submitted</div>
            <div class="stat-value">
                {{ $stats['submitted'] }}
            </div>
        </div>

        <div class="stat">
            <div class="stat-label">Graded</div>
            <div class="stat-value">
                {{ $stats['graded'] }}
            </div>
        </div>

        <div class="stat">
            <div class="stat-label">Pending</div>
            <div class="stat-value">
                {{ $stats['pending'] }}
            </div>
        </div>

    </div>

    {{-- SUBMISSIONS --}}
    <div class="card">

        <div class="card-header">

            <div class="card-title">
                👥 Student Submissions
            </div>

            <div style="font-size:.8rem;color:#9ca3af;">
                Max Score: {{ $project->max_score }}
            </div>

        </div>

        <div class="card-body">

            @if($submissions->isEmpty())

                <div class="empty">
                    No student submissions yet.
                </div>

            @else

                <table>

                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                            <th>File</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($submissions as $submission)

                            <tr>

                                {{-- STUDENT --}}
                                <td>

                                    <div class="student">
                                        {{ $submission->student->name }}
                                    </div>
                                    @if($submission->student->student_id)
                                    <div style="font-size:.72rem;color:#6b7280;margin-top:2px;">
                                        🆔 {{ $submission->student->student_id }}
                                    </div>
                                    @endif

                                </td>

                                {{-- STATUS --}}
                                <td>

                                    <span class="badge

                                        @if($submission->status === 'submitted')
                                            submitted
                                        @elseif($submission->status === 'reviewed')
                                            reviewed
                                        @else
                                            draft
                                        @endif

                                    ">

                                        {{ ucfirst($submission->status) }}

                                    </span>

                                </td>

                                {{-- DATE --}}
                                <td>

                                    {{ $submission->submitted_at
                                        ? $submission->submitted_at->format('M d, Y h:i A')
                                        : '—'
                                    }}

                                </td>

                                {{-- FILE --}}
                                <td>

                                    @if($submission->file_path)

                                        <a href="{{ asset('storage/' . $submission->file_path) }}"
                                           target="_blank"
                                           class="file-link">

                                            📎 View File

                                        </a>

                                    @else
                                        —
                                    @endif

                                </td>

                                {{-- ACTION --}}
                                <td>

                                    <a href="{{ route('teacher.grades.edit', [$project->id, $submission->student_id]) }}"
                                       class="btn btn-primary">

                                        Grade

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @endif

        </div>

    </div>

</div>

@endsection