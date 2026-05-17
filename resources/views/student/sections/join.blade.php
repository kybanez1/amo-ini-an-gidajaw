@extends('layouts.app')

@section('title', 'Join a Section')

@push('styles')
<style>
.join-wrap { max-width:560px; margin:0 auto; padding:1.5rem 1rem; }
.card { background:white; border:1.5px solid #e5e7eb; border-radius:18px; padding:2rem; margin-bottom:1.5rem; }
.card-icon { width:56px; height:56px; background:#eef2ff; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; margin:0 auto 1.25rem; }
.card-title { font-size:1.15rem; font-weight:800; color:#111827; text-align:center; margin-bottom:.4rem; }
.card-sub { font-size:.85rem; color:#6b7280; text-align:center; margin-bottom:1.5rem; line-height:1.5; }
.form-group { margin-bottom:1rem; }
.form-group label { display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:.45rem; }
.code-input { width:100%; padding:.75rem 1rem; border:2px solid #e5e7eb; border-radius:12px; font-size:1.4rem; font-weight:800; letter-spacing:.2em; text-align:center; text-transform:uppercase; outline:none; box-sizing:border-box; color:#111827; }
.code-input:focus { border-color:#818cf8; box-shadow:0 0 0 3px rgba(129,140,248,.15); }
.btn-join { width:100%; padding:.8rem; background:#4f46e5; color:white; border:none; border-radius:12px; font-size:.95rem; font-weight:700; cursor:pointer; margin-top:.5rem; }
.btn-join:hover { background:#4338ca; }
.error { background:#fee2e2; color:#dc2626; padding:.7rem 1rem; border-radius:9px; font-size:.83rem; margin-top:.75rem; }
.alert-success { background:#dcfce7; color:#166534; padding:.85rem 1.1rem; border-radius:10px; margin-bottom:1.25rem; font-size:.88rem; border:1px solid #bbf7d0; }
.back-link { display:block; text-align:center; color:#6b7280; font-size:.83rem; margin-top:1rem; text-decoration:none; }
.back-link:hover { color:#4f46e5; }
.section-list-title { font-weight:700; font-size:.95rem; margin-bottom:1rem; }
.section-row { display:flex; align-items:center; gap:.9rem; padding:.9rem; border:1.5px solid #e5e7eb; border-radius:12px; margin-bottom:.6rem; }
.section-icon-sm { width:38px; height:38px; background:#eef2ff; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; }
.section-info { flex:1; }
.section-nm { font-weight:700; font-size:.9rem; color:#111827; }
.section-meta { font-size:.76rem; color:#6b7280; margin-top:.15rem; }
.section-badge-sm { padding:.2rem .6rem; background:#dcfce7; color:#166534; border-radius:999px; font-size:.72rem; font-weight:700; }
</style>
@endpush

@section('content')
<div class="join-wrap">

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    {{-- JOIN CARD --}}
    <div class="card">
        <div class="card-icon">🏫</div>
        <div class="card-title">Join a Section</div>
        <div class="card-sub">
            Ask your teacher for the 6-character section code.<br>
            Joining a section makes it easy for your teacher to organize and assign projects to you.
        </div>

        <form method="POST" action="{{ route('student.sections.join.store') }}">
            @csrf
            <div class="form-group">
                <label>Section Code</label>
                <input type="text"
                       name="code"
                       class="code-input"
                       placeholder="ABC123"
                       maxlength="6"
                       value="{{ old('code') }}"
                       autocomplete="off"
                       autofocus
                       oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g,'')">
                @error('code')
                    <div class="error">⚠️ {{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn-join">✅ Join Section</button>
        </form>

        <a href="{{ route('student.dashboard') }}" class="back-link">← Back to Dashboard</a>
    </div>

    {{-- MY SECTIONS --}}
    @if($mySections->isNotEmpty())
    <div class="card">
        <div class="section-list-title">📚 My Sections ({{ $mySections->count() }})</div>
        @foreach($mySections as $sec)
        <div class="section-row">
            <div class="section-icon-sm">🏫</div>
            <div class="section-info">
                <div class="section-nm">{{ $sec->name }}</div>
                <div class="section-meta">
                    👩‍🏫 {{ $sec->teacher->name ?? 'Unknown' }}
                    @if($sec->school_year) &nbsp;·&nbsp; {{ $sec->school_year }} @endif
                    @if($sec->semester) &nbsp;·&nbsp; {{ $sec->semester }} @endif
                </div>
            </div>
            <span class="section-badge-sm">Enrolled</span>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
