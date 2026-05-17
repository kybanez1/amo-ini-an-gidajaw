<?php $__env->startSection('title', $section->name); ?>

<?php $__env->startPush('styles'); ?>
<style>
.section-wrap { padding:1.5rem 2rem; max-width:1000px; }
.back-link { display:inline-flex; align-items:center; gap:.4rem; color:#4f46e5; font-size:.85rem; font-weight:600; text-decoration:none; margin-bottom:1.5rem; }
.back-link:hover { color:#3730a3; }
.card { background:white; border:1.5px solid #e5e7eb; border-radius:16px; margin-bottom:1.5rem; overflow:hidden; }
.card-head { padding:1.1rem 1.5rem; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; justify-content:space-between; }
.card-title { font-weight:700; font-size:.95rem; }
.card-body { padding:1.5rem; }
.section-name-big { font-size:1.5rem; font-weight:800; color:#111827; margin-bottom:.25rem; }
.section-desc { color:#6b7280; font-size:.88rem; margin-bottom:1.25rem; }
.code-box { display:inline-flex; align-items:center; gap:.75rem; background:#f5f3ff; border:2px dashed #a5b4fc; border-radius:12px; padding:.75rem 1.25rem; margin-bottom:1.5rem; }
.code-val { font-family:monospace; font-size:1.6rem; font-weight:900; color:#4f46e5; letter-spacing:.2em; }
.copy-btn { padding:.45rem 1rem; background:#4f46e5; color:white; border:none; border-radius:8px; font-size:.82rem; font-weight:600; cursor:pointer; }
.copy-btn:hover { background:#4338ca; }
.regen-btn { padding:.45rem 1rem; background:#f3f4f6; color:#374151; border:none; border-radius:8px; font-size:.82rem; font-weight:600; cursor:pointer; }
.regen-btn:hover { background:#e5e7eb; }
.info-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:1rem; margin-bottom:1.5rem; }
.info-item { background:#f9fafb; border-radius:10px; padding:.85rem 1rem; }
.info-label { font-size:.72rem; color:#9ca3af; text-transform:uppercase; letter-spacing:.05em; margin-bottom:.3rem; }
.info-val { font-weight:700; color:#111827; font-size:.95rem; }
.alert { padding:.85rem 1.1rem; border-radius:10px; margin-bottom:1.25rem; font-size:.85rem; }
.alert-success { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
.alert-error { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
table { width:100%; border-collapse:collapse; }
th { padding:.75rem 1.25rem; text-align:left; font-size:.75rem; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; background:#f9fafb; border-bottom:1px solid #e5e7eb; }
td { padding:.85rem 1.25rem; border-bottom:1px solid #f3f4f6; font-size:.88rem; }
tr:last-child td { border-bottom:none; }
.student-name { font-weight:600; color:#111827; }
.student-id { font-size:.75rem; color:#6b7280; }
.btn-sm-danger { padding:.35rem .7rem; background:#fee2e2; color:#dc2626; border:none; border-radius:7px; font-size:.78rem; font-weight:600; cursor:pointer; }
.btn-sm-danger:hover { background:#fecaca; }
.btn-primary { padding:.6rem 1.2rem; background:#4f46e5; color:white; border:none; border-radius:9px; font-weight:600; cursor:pointer; font-size:.85rem; }
.btn-primary:hover { background:#4338ca; }
.form-row { display:flex; gap:.75rem; align-items:center; }
.form-row select { flex:1; padding:.6rem .9rem; border:1.5px solid #e5e7eb; border-radius:9px; font-size:.85rem; outline:none; }
.form-row select:focus { border-color:#818cf8; }
.empty-row td { text-align:center; color:#9ca3af; padding:2.5rem; }
/* Edit form */
.edit-form input, .edit-form textarea, .edit-form select { width:100%; padding:.65rem .9rem; border:1.5px solid #e5e7eb; border-radius:9px; font-size:.87rem; outline:none; box-sizing:border-box; }
.edit-form input:focus, .edit-form textarea:focus, .edit-form select:focus { border-color:#818cf8; }
.form-group { margin-bottom:1rem; }
.form-group label { display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:.4rem; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="section-wrap">

    <a href="<?php echo e(route('teacher.sections.index')); ?>" class="back-link">← Back to Sections</a>

    <?php if(session('success')): ?>
        <div class="alert alert-success">✅ <?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-error">❌ <?php echo e(session('error')); ?></div>
    <?php endif; ?>

    
    <div class="card">
        <div class="card-head">
            <div class="card-title">🏫 Section Info</div>
            <span style="padding:.25rem .65rem;border-radius:999px;font-size:.75rem;font-weight:700;
                background:<?php echo e($section->status === 'active' ? '#dcfce7' : '#f3f4f6'); ?>;
                color:<?php echo e($section->status === 'active' ? '#166534' : '#6b7280'); ?>;">
                <?php echo e(strtoupper($section->status)); ?>

            </span>
        </div>
        <div class="card-body">
            <div class="section-name-big"><?php echo e($section->name); ?></div>
            <div class="section-desc"><?php echo e($section->description ?: 'No description set.'); ?></div>

            <div style="margin-bottom:.65rem;font-size:.8rem;font-weight:600;color:#6b7280;">SECTION JOIN CODE</div>
            <div class="code-box">
                <span class="code-val" id="sectionCode"><?php echo e($section->code); ?></span>
                <button class="copy-btn" onclick="copyCode()">📋 Copy Code</button>
                <form method="POST" action="<?php echo e(route('teacher.sections.regenerateCode', $section->id)); ?>"
                      onsubmit="return confirm('Regenerate the join code? Students will need the new code to join.')">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="regen-btn">🔄 New Code</button>
                </form>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Students</div>
                    <div class="info-val"><?php echo e($section->students->count()); ?></div>
                </div>
                <?php if($section->school_year): ?>
                <div class="info-item">
                    <div class="info-label">School Year</div>
                    <div class="info-val"><?php echo e($section->school_year); ?></div>
                </div>
                <?php endif; ?>
                <?php if($section->semester): ?>
                <div class="info-item">
                    <div class="info-label">Semester</div>
                    <div class="info-val"><?php echo e($section->semester); ?></div>
                </div>
                <?php endif; ?>
                <div class="info-item">
                    <div class="info-label">Created</div>
                    <div class="info-val"><?php echo e($section->created_at->format('M d, Y')); ?></div>
                </div>
            </div>

            
            <details style="margin-top:.5rem;">
                <summary style="cursor:pointer;font-size:.85rem;font-weight:600;color:#4f46e5;user-select:none;">✏️ Edit Section Details</summary>
                <div style="margin-top:1rem;" class="edit-form">
                    <form method="POST" action="<?php echo e(route('teacher.sections.update', $section->id)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                            <div class="form-group" style="grid-column:1/-1;">
                                <label>Section Name</label>
                                <input type="text" name="name" value="<?php echo e($section->name); ?>" required>
                            </div>
                            <div class="form-group" style="grid-column:1/-1;">
                                <label>Description</label>
                                <textarea name="description" rows="2"><?php echo e($section->description); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>School Year</label>
                                <input type="text" name="school_year" value="<?php echo e($section->school_year); ?>" placeholder="2025-2026">
                            </div>
                            <div class="form-group">
                                <label>Semester</label>
                                <select name="semester">
                                    <option value="">— None —</option>
                                    <option value="1st Semester" <?php echo e($section->semester === '1st Semester' ? 'selected' : ''); ?>>1st Semester</option>
                                    <option value="2nd Semester" <?php echo e($section->semester === '2nd Semester' ? 'selected' : ''); ?>>2nd Semester</option>
                                    <option value="Summer" <?php echo e($section->semester === 'Summer' ? 'selected' : ''); ?>>Summer</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status">
                                    <option value="active" <?php echo e($section->status === 'active' ? 'selected' : ''); ?>>Active</option>
                                    <option value="inactive" <?php echo e($section->status === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary">💾 Save Changes</button>
                    </form>
                </div>
            </details>
        </div>
    </div>

    
    <div class="card">
        <div class="card-head">
            <div class="card-title">👨‍🎓 Enrolled Students (<?php echo e($section->students->count()); ?>)</div>
        </div>
        <div class="card-body" style="padding:0;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Department</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $section->students->sortBy('name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="color:#9ca3af;"><?php echo e($i + 1); ?></td>
                        <td>
                            <div class="student-name"><?php echo e($student->name); ?></div>
                            <div class="student-id"><?php echo e($student->email); ?></div>
                        </td>
                        <td><?php echo e($student->student_id ?? '—'); ?></td>
                        <td><?php echo e($student->department ?? '—'); ?></td>
                        <td style="color:#6b7280;font-size:.8rem;">
                            <?php echo e($student->pivot->joined_at ? \Carbon\Carbon::parse($student->pivot->joined_at)->format('M d, Y') : '—'); ?>

                        </td>
                        <td>
                            <form method="POST"
                                  action="<?php echo e(route('teacher.sections.removeStudent', [$section->id, $student->id])); ?>"
                                  onsubmit="return confirm('Remove <?php echo e(addslashes($student->name)); ?> from this section?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-sm-danger">✕ Remove</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr class="empty-row">
                        <td colspan="6">No students enrolled yet. Share the code <strong><?php echo e($section->code); ?></strong> with your class.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($available->isNotEmpty()): ?>
        <div style="padding:1rem 1.5rem;border-top:1px solid #f3f4f6;background:#f9fafb;">
            <div style="font-size:.82rem;font-weight:600;color:#374151;margin-bottom:.6rem;">➕ Add Student Manually</div>
            <form method="POST" action="<?php echo e(route('teacher.sections.addStudent', $section->id)); ?>" class="form-row">
                <?php echo csrf_field(); ?>
                <select name="student_id">
                    <option value="">— Select a student —</option>
                    <?php $__currentLoopData = $available; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?> <?php if($s->student_id): ?>(<?php echo e($s->student_id); ?>)<?php endif; ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="submit" class="btn-primary">Add</button>
            </form>
        </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function copyCode() {
    var code = document.getElementById('sectionCode').textContent.trim();
    navigator.clipboard.writeText(code).then(function() {
        var btn = document.querySelector('.copy-btn');
        var orig = btn.textContent;
        btn.textContent = '✅ Copied!';
        setTimeout(function() { btn.textContent = orig; }, 2000);
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/teacher/sections/show.blade.php ENDPATH**/ ?>