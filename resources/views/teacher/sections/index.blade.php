@extends('layouts.app')

@section('title', 'My Sections')

@push('styles')
<style>
.sections-page { padding: 1.5rem 2rem; max-width: 1100px; }
.page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.75rem; }
.page-title { font-size:1.4rem; font-weight:700; color:#111827; }
.page-subtitle { font-size:.85rem; color:#6b7280; margin-top:.2rem; }
.sections-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:1.25rem; }
.section-card { background:white; border:1.5px solid #e5e7eb; border-radius:16px; padding:1.5rem; transition:box-shadow .2s; }
.section-card:hover { box-shadow:0 4px 20px rgba(79,70,229,.1); border-color:#c7d2fe; }
.section-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; }
.section-icon { width:44px; height:44px; border-radius:12px; background:#eef2ff; display:flex; align-items:center; justify-content:center; font-size:1.3rem; }
.section-badge { padding:.25rem .65rem; border-radius:999px; font-size:.72rem; font-weight:700; background:#dcfce7; color:#166534; }
.section-badge.inactive { background:#f3f4f6; color:#6b7280; }
.section-name { font-size:1.05rem; font-weight:700; color:#111827; margin-bottom:.35rem; }
.section-desc { font-size:.82rem; color:#6b7280; margin-bottom:1rem; min-height:1.2rem; }
.section-code-row { display:flex; align-items:center; gap:.5rem; background:#f5f3ff; border:1.5px dashed #a5b4fc; border-radius:10px; padding:.55rem .85rem; margin-bottom:1rem; }
.section-code { font-family:monospace; font-size:1.1rem; font-weight:800; color:#4f46e5; letter-spacing:.15em; flex:1; }
.copy-btn { background:none; border:none; cursor:pointer; font-size:.8rem; color:#7c3aed; font-weight:600; white-space:nowrap; }
.copy-btn:hover { color:#4f46e5; }
.section-meta { display:flex; gap:1.5rem; margin-bottom:1rem; }
.meta-item { font-size:.78rem; }
.meta-label { color:#9ca3af; margin-bottom:.15rem; }
.meta-val { font-weight:600; color:#374151; }
.section-actions { display:flex; gap:.6rem; }
.btn-view { flex:1; padding:.5rem; text-align:center; background:#eef2ff; color:#4f46e5; border-radius:9px; text-decoration:none; font-size:.82rem; font-weight:600; border:none; cursor:pointer; }
.btn-view:hover { background:#e0e7ff; }
.btn-del { padding:.5rem .8rem; background:#fef2f2; color:#dc2626; border-radius:9px; font-size:.82rem; font-weight:600; border:none; cursor:pointer; }
.btn-del:hover { background:#fee2e2; }
.empty-state { grid-column:1/-1; text-align:center; padding:4rem 2rem; color:#9ca3af; }
.empty-icon { font-size:4rem; margin-bottom:1rem; }
.empty-title { font-size:1.1rem; font-weight:700; color:#374151; margin-bottom:.5rem; }
.btn-primary { padding:.6rem 1.3rem; background:#4f46e5; color:white; border:none; border-radius:10px; font-weight:600; cursor:pointer; font-size:.88rem; }
.btn-primary:hover { background:#4338ca; }
.alert { padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.25rem; font-size:.88rem; }
.alert-success { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
/* Modal */
#createModal { display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:9999; align-items:center; justify-content:center; }
#createModal.open { display:flex; }
.modal-box { background:white; border-radius:18px; width:100%; max-width:480px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.2); }
.modal-head { display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid #f3f4f6; }
.modal-head-title { font-weight:700; font-size:1rem; }
.modal-close { background:none; border:none; font-size:1.3rem; cursor:pointer; color:#6b7280; }
.modal-body { padding:1.5rem; }
.form-group { margin-bottom:1rem; }
.form-group label { display:block; font-size:.83rem; font-weight:600; color:#374151; margin-bottom:.4rem; }
.form-group input, .form-group textarea, .form-group select { width:100%; padding:.65rem .9rem; border:1.5px solid #e5e7eb; border-radius:9px; font-size:.88rem; outline:none; box-sizing:border-box; }
.form-group input:focus, .form-group textarea:focus, .form-group select:focus { border-color:#818cf8; }
.modal-foot { display:flex; gap:.75rem; justify-content:flex-end; margin-top:1.25rem; }
.btn-outline { padding:.55rem 1.1rem; background:white; border:1.5px solid #e5e7eb; border-radius:9px; font-size:.85rem; font-weight:600; cursor:pointer; color:#374151; }
.btn-outline:hover { border-color:#818cf8; color:#4f46e5; }
</style>
@endpush

@section('content')
<div class="sections-page">

    <div class="page-header">
        <div>
            <div class="page-title">🏫 My Sections</div>
            <div class="page-subtitle">Create class sections with unique join codes for easy student enrollment</div>
        </div>
        <button class="btn-primary" onclick="document.getElementById('createModal').classList.add('open')">
            + New Section
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    <div class="sections-grid">
        @forelse($sections as $section)
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">🏫</div>
                <span class="section-badge {{ $section->status === 'inactive' ? 'inactive' : '' }}">
                    {{ strtoupper($section->status) }}
                </span>
            </div>

            <div class="section-name">{{ $section->name }}</div>
            <div class="section-desc">{{ $section->description ?: 'No description.' }}</div>

            <div class="section-code-row">
                <span class="section-code" id="code-{{ $section->id }}">{{ $section->code }}</span>
                <button class="copy-btn" onclick="copyCode('{{ $section->code }}', this)">📋 Copy</button>
            </div>

            <div class="section-meta">
                <div class="meta-item">
                    <div class="meta-label">Students</div>
                    <div class="meta-val">{{ $section->students->count() }}</div>
                </div>
                @if($section->school_year)
                <div class="meta-item">
                    <div class="meta-label">School Year</div>
                    <div class="meta-val">{{ $section->school_year }}</div>
                </div>
                @endif
                @if($section->semester)
                <div class="meta-item">
                    <div class="meta-label">Semester</div>
                    <div class="meta-val">{{ $section->semester }}</div>
                </div>
                @endif
            </div>

            <div class="section-actions">
                <a href="{{ route('teacher.sections.show', $section->id) }}" class="btn-view">👁 Manage</a>
                <form method="POST" action="{{ route('teacher.sections.destroy', $section->id) }}"
                      onsubmit="return confirm('Delete section {{ addslashes($section->name) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-del">🗑</button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">🏫</div>
            <div class="empty-title">No sections yet</div>
            <p>Create your first section and share the code with your students.</p>
            <button class="btn-primary" onclick="document.getElementById('createModal').classList.add('open')">
                + Create Section
            </button>
        </div>
        @endforelse
    </div>

    {{ $sections->links() }}

</div>

{{-- CREATE MODAL --}}
<div id="createModal">
    <div class="modal-box">
        <div class="modal-head">
            <span class="modal-head-title">🏫 Create New Section</span>
            <button class="modal-close" onclick="document.getElementById('createModal').classList.remove('open')">×</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('teacher.sections.store') }}">
                @csrf
                <div class="form-group">
                    <label>Section Name <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="e.g. BSICT-2A1" required autofocus>
                    @error('name')<div style="color:#dc2626;font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Description <span style="color:#9ca3af;font-weight:400">(optional)</span></label>
                    <textarea name="description" rows="2"
                              placeholder="e.g. Bachelor of Science in ICT — 2nd Year, Section A1">{{ old('description') }}</textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                    <div class="form-group">
                        <label>School Year</label>
                        <input type="text" name="school_year" value="{{ old('school_year') }}"
                               placeholder="e.g. 2025-2026">
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <select name="semester">
                            <option value="">— Select —</option>
                            <option value="1st Semester" {{ old('semester') === '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                            <option value="2nd Semester" {{ old('semester') === '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                            <option value="Summer" {{ old('semester') === 'Summer' ? 'selected' : '' }}>Summer</option>
                        </select>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-outline"
                            onclick="document.getElementById('createModal').classList.remove('open')">Cancel</button>
                    <button type="submit" class="btn-primary">✅ Create Section</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function copyCode(code, btn) {
    navigator.clipboard.writeText(code).then(function() {
        var orig = btn.textContent;
        btn.textContent = '✅ Copied!';
        setTimeout(function() { btn.textContent = orig; }, 2000);
    });
}
@if($errors->any())
document.getElementById('createModal').classList.add('open');
@endif
// Close modal on backdrop click
document.getElementById('createModal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('open');
});
</script>
@endsection
