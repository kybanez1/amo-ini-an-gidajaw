@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/student-group-show.css') }}">
@endpush

@section('content')
<div class="page" style="max-width:900px;margin:auto;padding:1.5rem;font-family:'Sora',sans-serif;">

    <a href="{{ route('student.dashboard') }}"
       style="display:inline-block;margin-bottom:1.25rem;color:#374151;text-decoration:none;font-size:.88rem;">
        ← Back to Dashboard
    </a>

    {{-- GROUP HEADER --}}
    <div style="background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:16px;padding:2rem;color:white;margin-bottom:1.5rem;">
        <div style="font-size:1.5rem;font-weight:800;margin-bottom:.5rem;">
            👥 {{ $group->name }}
        </div>
        @if($group->description)
            <div style="opacity:.85;font-size:.9rem;">{{ $group->description }}</div>
        @endif
        <div style="margin-top:1rem;font-size:.82rem;opacity:.75;">
            Teacher: {{ $group->teacher->name ?? '—' }}
            · {{ $group->students->count() }} member(s)
        </div>
    </div>

    {{-- PROJECTS IN THIS GROUP --}}
    <div style="background:white;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;margin-bottom:1.5rem;">
        <div style="padding:1.1rem 1.5rem;border-bottom:1px solid #e5e7eb;font-weight:700;font-size:1rem;">
            📂 Group Projects
        </div>

        @if($group->projects->isEmpty())
            <div style="padding:3rem;text-align:center;color:#9ca3af;">
                No projects assigned to this group yet.
            </div>
        @else
            @foreach($group->projects as $project)
                <div style="padding:1.25rem 1.5rem;border-top:1px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
                    <div>
                        <div style="font-weight:700;color:#111827;">{{ $project->title }}</div>
                        <div style="font-size:.78rem;color:#6b7280;margin-top:3px;">
                            Due: {{ $project->due_date ? \Carbon\Carbon::parse($project->due_date)->format('M d, Y') : '—' }}
                            · Max Score: {{ $project->max_score }}
                        </div>
                    </div>
                    <a href="{{ route('student.projects.show', $project->id) }}"
                       style="padding:.55rem 1rem;background:#4f46e5;color:white;border-radius:8px;text-decoration:none;font-size:.82rem;font-weight:600;">
                        👁 View Project
                    </a>
                </div>
            @endforeach
        @endif
    </div>

    {{-- MEMBERS --}}
    <div style="background:white;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;">
        <div style="padding:1.1rem 1.5rem;border-bottom:1px solid #e5e7eb;font-weight:700;font-size:1rem;">
            🧑‍🎓 Members ({{ $group->students->count() }})
        </div>
        @forelse($group->students as $member)
            <div style="padding:1rem 1.5rem;border-top:1px solid #f3f4f6;display:flex;align-items:center;gap:.75rem;">
                <div style="width:36px;height:36px;background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:.85rem;flex-shrink:0;">
                    {{ strtoupper(substr($member->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight:600;color:#111827;font-size:.88rem;">{{ $member->name }}</div>
                    <div style="font-size:.72rem;color:#9ca3af;">{{ $member->student_id ?? $member->email }}</div>
                </div>
                @if($member->id === auth()->id())
                    <span style="margin-left:auto;background:#dbeafe;color:#1d4ed8;padding:.25rem .6rem;border-radius:999px;font-size:.7rem;font-weight:700;">
                        You
                    </span>
                @endif
            </div>
        @empty
            <div style="padding:2rem;text-align:center;color:#9ca3af;">No members yet.</div>
        @endforelse
    </div>

</div>
@endsection
