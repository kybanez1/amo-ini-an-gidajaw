@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-students.css') }}">
@endpush

@section('content')
<div class="wrap">

    <div class="page-header">
        <div>
            <div class="page-title">🧑‍🎓 My Students</div>
            <div class="page-sub">Students who registered using your teacher code</div>
        </div>
        <a href="{{ route('teacher.dashboard') }}"
           style="padding:.65rem 1.2rem;border:1px solid #d1d5db;border-radius:10px;
                  text-decoration:none;color:#374151;font-size:.85rem;font-weight:600;">
            ← Dashboard
        </a>
    </div>

    {{-- TEACHER CODE --}}
    <div class="code-box">
        <div>
            <div class="code-label">YOUR TEACHER CODE</div>
            <div class="code-value" id="teacherCode" onclick="copyCode()" title="Click to copy">
                {{ $teacher->teacher_code ?? '------' }}
            </div>
        </div>
        <div>
            <button class="btn-copy" id="copyBtn" onclick="copyCode()">📋 Copy</button>
            <div id="copyConfirm"
                 style="display:none;font-size:.75rem;color:#166534;font-weight:600;margin-top:4px;">
                ✅ Copied!
            </div>
            <div style="font-size:.72rem;color:#9ca3af;margin-top:4px;max-width:180px;">
                Share this with students so they can register under you.
            </div>
        </div>
    </div>

    @if(session('success'))
        <div style="margin-bottom:1rem;padding:1rem;background:#dcfce7;color:#166534;
                    border-radius:10px;border:1px solid #bbf7d0;">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- STUDENTS TABLE --}}
    <div class="panel">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Student ID</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td style="color:#9ca3af;">{{ $loop->iteration }}</td>
                        <td>
                            <div class="name-cell">
                                <div class="avatar">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
                                <div>
                                    <div class="s-name">{{ $student->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $student->student_id ?? '—' }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->department ?? '—' }}</td>
                        <td style="color:#9ca3af;font-size:.78rem;">
                            {{ $student->pivot->created_at
                                ? $student->pivot->created_at->format('M d, Y')
                                : '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty">
                                <div class="empty-icon">🧑‍🎓</div>
                                <div style="font-weight:600;color:#374151;margin-bottom:.5rem;">
                                    No students yet
                                </div>
                                <div style="font-size:.85rem;">
                                    Share your teacher code
                                    <strong style="color:#4f46e5;">
                                        {{ $teacher->teacher_code }}
                                    </strong>
                                    with your students.
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem;">
        {{ $students->links() }}
    </div>

</div>
@endsection

<script src="{{ asset('assets/js/pages/teacher-students.js') }}"></script>
