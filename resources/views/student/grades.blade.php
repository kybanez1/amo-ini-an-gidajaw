@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/student-grades.css') }}">
@endpush

@section('title', 'My Grades')

@section('content')
<div class="grades-wrap">

    <!-- HEADER -->
    <div class="page-header">
        <div>
            <div class="page-title">🏆 My Grades</div>
            <div class="page-subtitle">
                View your scores and teacher feedback for all graded projects
            </div>
        </div>
        <a href="{{ route('student.projects.index') }}"
           style="padding:.55rem 1rem;border:1px solid #d1d5db;border-radius:8px;font-size:.82rem;text-decoration:none;color:#374151;background:#fff;">
            ← My Projects
        </a>
    </div>

    <!-- SUMMARY -->
    <div class="summary-row">

        <div class="summary-card">
            <div class="summary-icon icon-blue">🎓</div>
            <div>
                <div class="summary-val">{{ $gradedProjects->count() }}</div>
                <div class="summary-label">Projects Graded</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon icon-green">📊</div>
            <div>
                <div class="summary-val">
                    {{ $averageScore !== null ? $averageScore : '—' }}
                </div>
                <div class="summary-label">Average Score</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon icon-amber">🆔</div>
            <div>
                <div class="summary-val" style="font-size:1rem;">
                    {{ $student->student_id ?? '—' }}
                </div>
                <div class="summary-label">Student ID</div>
            </div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="table-card">

        @if($gradedProjects->isEmpty())

            <div class="empty-state">
                <div class="icon">📭</div>
                <h3>No grades yet</h3>
                <p>Your grades will appear here once your teacher reviews your submissions.</p>
            </div>

        @else

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project</th>
                        <th>Teacher</th>
                        <th>Score</th>
                        <th>Submission Status</th>
                        <th>Teacher Remarks</th>
                        <th>Graded At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gradedProjects as $index => $project)
                    @php
                        $score      = $project->pivot->score ?? 0;
                        $maxScore   = $project->max_score ?? 100;
                        $feedback   = $project->pivot->feedback ?? null;
                        $gradedAt   = $project->pivot->graded_at ?? null;
                        $pct        = ($maxScore > 0 && $score !== null) ? round(($score / $maxScore) * 100) : 0;

                        if ($pct >= 75) $pillClass = '';
                        elseif ($pct >= 50) $pillClass = 'mid';
                        else $pillClass = 'low';
                    @endphp
                    <tr>
                        <td style="color:#9ca3af;font-size:.78rem;">{{ $index + 1 }}</td>

                        <td>
                            <div class="project-name">{{ $project->title }}</div>
                            <div class="teacher-name">
                                Due {{ $project->due_date ? \Carbon\Carbon::parse($project->due_date)->format('M d, Y') : '—' }}
                            </div>
                        </td>

                        <td>
                            {{ $project->teacher->name ?? '—' }}
                        </td>

                        <td>
                            <span class="score-pill {{ $pillClass }}">
                                {{ $score }} / {{ $maxScore }}
                                <span style="font-size:.7rem;opacity:.7;">({{ $pct }}%)</span>
                            </span>
                        </td>

                        <td>
                            <span class="status-chip chip-graded">✅ Graded</span>
                        </td>

                        <td>
                            @if($feedback)
                                <div class="remarks-box">{{ $feedback }}</div>
                            @else
                                <span style="color:#9ca3af;font-size:.78rem;">No remarks</span>
                            @endif
                        </td>

                        <td style="font-size:.78rem;color:#6b7280;">
                            @if($gradedAt)
                                {{ \Carbon\Carbon::parse($gradedAt)->format('M d, Y') }}
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        @endif

    </div>

</div>
@endsection
