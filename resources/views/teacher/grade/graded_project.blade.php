@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-graded.css') }}">
@endpush

@section('content')
<div class="page-wrap">

    <div style="margin-bottom:1.5rem;">

        <h2 style="font-size:1.8rem;font-weight:700;">
            ✅ Graded Projects
        </h2>

        <p style="color:#6b7280;">
            View all graded student submissions
        </p>

    </div>

    <div class="panel">

        <div class="panel-header">
            ✅ Recently Graded
        </div>

        @if($gradedSubmissions->count())

            <table>

                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Project</th>
                        <th>Score</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($gradedSubmissions as $submission)

                        @php

                            $assignment = DB::table('project_student')
                                ->where('project_id', $submission->project_id)
                                ->where('student_id', $submission->student_id)
                                ->first();

                            $score = $assignment->score ?? 0;

                        @endphp

                        <tr>

                            <td>
                                {{ $submission->student->name ?? 'Unknown' }}
                            </td>

                            <td>
                                {{ $submission->project->title ?? 'Untitled' }}
                            </td>

                            <td>

                                <span class="score-pill">

                                    {{ $score }}

                                    /

                                    {{ $submission->project->max_score ?? 0 }}

                                </span>

                            </td>

                            <td>

                                <span class="grade-badge">
                                    GRADED
                                </span>

                            </td>

                            <td>

                                <a href="{{ route('teacher.projects.show',$submission->project_id) }}"
                                   class="btn-view">

                                    👁 View

                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty-box">

                <div style="font-size:60px;">
                    📄
                </div>

                <h3>
                    No graded submissions yet
                </h3>

                <p style="color:#6b7280;">
                    Graded student work will appear here.
                </p>

            </div>

        @endif

    </div>

    <div class="pagination-wrap">
        {{ $gradedSubmissions->links() }}
    </div>

</div>

@endsection