<?php $__env->startSection('title', $group->name); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/teacher-group-show.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="pms-page">
    <div class="breadcrumb">
        <a href="<?php echo e(route('teacher.dashboard')); ?>">Dashboard</a> ›
        <a href="<?php echo e(route('teacher.groups.index')); ?>">Groups</a> ›
        <?php echo e($group->name); ?>

    </div>

    <div class="page-header">
        <div>
            <div class="page-title"><?php echo e($group->name); ?></div>
            <div class="page-subtitle">
                <?php echo e($group->description ?? 'No description'); ?> &nbsp;·&nbsp;
                <span class="badge <?php echo e($group->status === 'active' ? 'badge-active' : 'badge-inactive'); ?>"><?php echo e(ucfirst($group->status)); ?></span>
            </div>
        </div>
        <div style="display:flex;gap:0.75rem;">
            <a href="<?php echo e(route('teacher.groups.edit', $group->id)); ?>" class="btn-outline">✏️ Edit Group</a>
            <a href="<?php echo e(route('teacher.groups.index')); ?>" class="btn-outline">← Back</a>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert-success">✅ <?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="alert-error">⚠️ <?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="stats-row">
        <div class="stat-card">
            <div class="value"><?php echo e($students->total()); ?></div>
            <div class="label">Students</div>
        </div>
        <div class="stat-card">
            <div class="value"><?php echo e($group->projects()->count()); ?></div>
            <div class="label">Projects</div>
        </div>
        <div class="stat-card">
            <div class="value"><?php echo e($group->created_at->format('M d')); ?></div>
            <div class="label">Created</div>
        </div>
    </div>

    
    <div class="info-card" style="margin-bottom:1.5rem;background:linear-gradient(135deg,#eef2ff,#f5f3ff);border-color:#c7d2fe;">

        <h3 style="color:#4338ca;margin-bottom:1rem;">🔑 Group Join Code</h3>

        <p style="font-size:.85rem;color:#6b7280;margin-bottom:1.25rem;">
            Share this code with your students. They enter it on their dashboard to join this group
            and get access to all assigned projects.
        </p>

        <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">

            
            <div id="joinCodeDisplay"
                 style="font-size:2.2rem;font-weight:800;letter-spacing:.3em;color:#4f46e5;
                        background:white;border:2px solid #c7d2fe;border-radius:14px;
                        padding:.75rem 1.75rem;font-family:monospace;cursor:pointer;
                        user-select:all;"
                 onclick="copyCode()"
                 title="Click to copy">
                <?php echo e($group->join_code ?? '------'); ?>

            </div>

            
            <button onclick="copyCode()"
                    id="copyBtn"
                    type="button"
                    style="padding:.7rem 1.2rem;background:#4f46e5;color:white;border:none;
                           border-radius:10px;font-weight:600;font-size:.85rem;cursor:pointer;">
                📋 Copy Code
            </button>

            
            <form method="POST"
                  action="<?php echo e(route('teacher.groups.regenerateCode', $group->id)); ?>"
                  onsubmit="return confirm('Generate a new join code? The old code will stop working immediately.')">
                <?php echo csrf_field(); ?>
                <button type="submit"
                        style="padding:.7rem 1.2rem;background:white;color:#6b7280;
                               border:1px solid #d1d5db;border-radius:10px;
                               font-weight:600;font-size:.85rem;cursor:pointer;">
                    🔄 New Code
                </button>
            </form>

        </div>

        <div id="copyConfirm"
             style="display:none;margin-top:.75rem;font-size:.82rem;color:#166534;font-weight:600;">
            ✅ Code copied to clipboard!
        </div>

    </div>

    <div class="two-col">
        <!-- Students Panel -->
        <div class="info-card">
            <h3>👤 Students in this Group</h3>
            <div class="student-list">
                <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="student-row">
                    <div class="student-avatar"><?php echo e(strtoupper(substr($student->name, 0, 1))); ?></div>
                    <div class="student-info">
                        <div class="student-name"><?php echo e($student->name); ?></div>
                        <div class="student-meta"><?php echo e($student->student_id ?? 'No ID'); ?> · <?php echo e($student->department ?? 'No course'); ?></div>
                    </div>
                    <form method="POST" action="<?php echo e(route('teacher.groups.removeStudent', [$group->id, $student->id])); ?>"
                          onsubmit="return confirm('Remove <?php echo e($student->name); ?> from this group?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn-danger" style="padding:4px 10px;font-size:0.75rem;">Remove</button>
                    </form>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-note">No students in this group yet.</div>
                <?php endif; ?>
            </div>

            <?php if($students->hasPages()): ?>
            <div style="margin-top:1rem;"><?php echo e($students->links()); ?></div>
            <?php endif; ?>

            <!-- Add Student -->
            <?php if($availableStudents->count() > 0): ?>
            <form method="POST" action="<?php echo e(route('teacher.groups.addStudent', $group->id)); ?>" class="add-student-form">
                <?php echo csrf_field(); ?>
                <select name="student_id" required>
                    <option value="">— Select student to add —</option>
                    <?php $__currentLoopData = $availableStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?> (<?php echo e($s->student_id ?? 'No ID'); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="submit" class="btn-primary" style="white-space:nowrap;">+ Add</button>
            </form>
            <?php else: ?>
            <p style="font-size:0.8rem;color:#9ca3af;margin-top:1rem;">All registered students are already in this group.</p>
            <?php endif; ?>
        </div>

        <!-- Projects Panel -->
        <div class="info-card">
            <h3>📁 Projects Assigned to Group</h3>
            <?php $__empty_1 = true; $__currentLoopData = $group->projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="student-row">
                <div class="student-avatar" style="background:#fef3c7;color:#d97706;">📁</div>
                <div class="student-info">
                    <div class="student-name"><?php echo e($project->title); ?></div>
                    <div class="student-meta">Due <?php echo e($project->due_date ? $project->due_date->format('M d, Y') : '—'); ?> · <?php echo e(ucfirst($project->status)); ?></div>
                </div>
                <a href="<?php echo e(route('teacher.projects.show', $project->id)); ?>" class="btn-outline" style="padding:4px 10px;font-size:0.75rem;">View</a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-note">No projects assigned to this group yet.</div>
            <?php endif; ?>

            <div style="margin-top:1.25rem;">
                <a href="<?php echo e(route('teacher.projects.create')); ?>" class="btn-primary" style="width:100%;justify-content:center;">+ Create Project for Group</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    var joinCode = "<?php echo e($group->join_code ?? ''); ?>";

    function copyCode() {
        if (!joinCode) return;
        navigator.clipboard.writeText(joinCode).then(function () {
            var confirmEl = document.getElementById('copyConfirm');
            var btn = document.getElementById('copyBtn');
            if (confirmEl) confirmEl.style.display = 'block';
            if (btn) btn.textContent = '✅ Copied!';
            setTimeout(function () {
                if (confirmEl) confirmEl.style.display = 'none';
                if (btn) btn.textContent = '📋 Copy Code';
            }, 2500);
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/teacher/group/show.blade.php ENDPATH**/ ?>