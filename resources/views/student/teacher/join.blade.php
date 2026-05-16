@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/student-teacher-join.css') }}">
@endpush

@section('content')
<div class="wrap">

    @if(session('success'))
        <div style="margin-bottom:1.5rem;padding:1rem;background:#dcfce7;border:1px solid #bbf7d0;
                    color:#166534;border-radius:12px;font-size:.9rem;">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- ENTER CODE --}}
    <div class="card">

        <div class="icon">🎓</div>
        <div class="title">Enter Teacher Code</div>
        <div class="sub">
            Ask your teacher for their 6-character personal code.
            Once entered, you'll appear in their student list and
            they can assign you to groups and projects.
        </div>

        <form method="POST" action="{{ route('student.teacher.join.store') }}">
            @csrf

            <label class="label" for="teacher_code">Teacher Code</label>

            <input type="text"
                   id="teacher_code"
                   name="teacher_code"
                   class="code-input"
                   placeholder="ABC123"
                   maxlength="6"
                   value="{{ old('teacher_code') }}"
                   autocomplete="off"
                   autofocus
                   oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g,'')">

            @error('teacher_code')
                <div class="error">⚠️ {{ $message }}</div>
            @enderror

            <button type="submit" class="btn-join">
                ✅ Register with Teacher
            </button>

        </form>

        <a href="{{ route('student.dashboard') }}" class="back-link">← Back to Dashboard</a>

    </div>

    {{-- MY TEACHERS --}}
    @if($myTeachers->isNotEmpty())
    <div class="card">
        <div style="font-weight:700;margin-bottom:1rem;">👩‍🏫 My Teachers</div>
        <div class="teacher-list">
            @foreach($myTeachers as $teacher)
                <div class="teacher-row">
                    <div class="avatar">{{ strtoupper(substr($teacher->name, 0, 1)) }}</div>
                    <div>
                        <div class="t-name">{{ $teacher->name }}</div>
                        <div class="t-email">{{ $teacher->email }}</div>
                    </div>
                    <div style="margin-left:auto;">
                        <span style="background:#dcfce7;color:#166534;padding:.3rem .7rem;
                                     border-radius:999px;font-size:.72rem;font-weight:700;">
                            ✅ Registered
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection