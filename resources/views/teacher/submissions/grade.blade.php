@extends('layouts.app')

@section('content')

<div style="max-width:700px;margin:auto;padding:2rem;">

    {{-- SUCCESS --}}
    @if(session('success'))
        <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- ERRORS --}}
    @if($errors->any())
        <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#fee2e2;color:#991b1b;border:1px solid #fecaca;">

            <strong>Please fix the following:</strong>

            <ul style="margin-top:10px;padding-left:20px;">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>
    @endif

    <div style="background:#fff;border-radius:16px;padding:2rem;border:1px solid #e5e7eb;">

        <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:1.5rem;">
            ⭐ Grade Submission
        </h2>

        {{-- STUDENT INFO --}}
        <div style="margin-bottom:1.5rem;padding:1rem;border-radius:12px;background:#f9fafb;border:1px solid #e5e7eb;">

            <div style="margin-bottom:.6rem;">
                <strong>Student:</strong>
                {{ $submission->student->name ?? 'Unknown Student' }}
            </div>

            <div style="margin-bottom:.6rem;">
                <strong>Project:</strong>
                {{ $submission->project->title ?? 'Unknown Project' }}
            </div>

            <div>
                <strong>Task:</strong>
                {{ $submission->task->title ?? 'General Submission' }}
            </div>

        </div>

        <form method="POST"
              action="{{ route('teacher.submissions.grade.store', $submission->id) }}">

            @csrf
            @method('PUT')

            {{-- SCORE --}}
            <div style="margin-bottom:1rem;">

                <label style="display:block;font-weight:600;margin-bottom:8px;">
                    Score
                </label>

                <input type="number"
                       name="score"
                       min="0"
                       max="{{ $submission->project->max_score }}"
                       value="{{ old('score', $submission->score) }}"
                       required
                       style="width:100%;padding:.8rem;border:1px solid #d1d5db;border-radius:10px;">

            </div>

            {{-- FEEDBACK --}}
            <div style="margin-bottom:1.5rem;">

                <label style="display:block;font-weight:600;margin-bottom:8px;">
                    Feedback
                </label>

                <textarea name="feedback"
                          rows="5"
                          style="width:100%;padding:.8rem;border:1px solid #d1d5db;border-radius:10px;">{{ old('feedback', $submission->feedback) }}</textarea>

            </div>

            {{-- ACTIONS --}}
            <div style="display:flex;gap:10px;flex-wrap:wrap;">

                <button type="submit"
                        style="background:#4f46e5;color:white;padding:.8rem 1.2rem;border:none;border-radius:10px;font-weight:600;cursor:pointer;">

                    💾 Save Grade

                </button>

                <a href="{{ route('teacher.submissions.show', $submission->id) }}"
                   style="border:1px solid #d1d5db;padding:.8rem 1.2rem;border-radius:10px;text-decoration:none;color:#374151;">

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>

@endsection