<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/teacher-groups.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('title', 'My Groups'); ?>

<?php $__env->startSection('content'); ?>
<div class="pms-page">

    <div class="page-header">
        <div>
            <div class="page-title">👥 My Groups</div>
            <div class="page-subtitle">Organize your students into groups</div>
        </div>

        
        <button class="btn-primary" id="openCreateModal">+ New Group</button>
    </div>

    <?php if(session('success')): ?>
        <div class="alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert-error"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="groups-grid">

        <?php $__empty_1 = true; $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <div class="group-card">

                <div class="group-header">
                    <div class="group-icon">👥</div>
                    <span class="badge">ACTIVE</span>
                </div>

                <div class="group-name"><?php echo e($group->name); ?></div>

                <div class="group-desc">
                    <?php echo e($group->description ?: 'No description available.'); ?>

                </div>

                <div class="group-stats">
                    <div class="stat">
                        <div class="stat-value"><?php echo e($group->students->count()); ?></div>
                        <div class="stat-label">Students</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value"><?php echo e($group->projects()->count()); ?></div>
                        <div class="stat-label">Projects</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value"><?php echo e($group->created_at->format('M d')); ?></div>
                        <div class="stat-label">Created</div>
                    </div>
                </div>

                <div class="group-actions">
                    <a href="<?php echo e(route('teacher.groups.show', $group->id)); ?>" class="btn-outline">👁 View</a>
                    <a href="<?php echo e(route('teacher.groups.edit', $group->id)); ?>" class="btn-outline">✏️ Edit</a>
                    <form method="POST"
                          action="<?php echo e(route('teacher.groups.destroy', $group->id)); ?>"
                          onsubmit="return confirm('Delete this group?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn-danger">🗑 Delete</button>
                    </form>
                </div>

            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <div class="empty-state">
                <div style="font-size:60px;">👥</div>
                <h3>No groups yet</h3>
                <p>Create your first student group.</p>
                <button class="btn-primary" id="openCreateModal2">+ Create Group</button>
            </div>

        <?php endif; ?>

    </div>

</div>


<div id="createGroupModal">
    <div class="modal-box" id="createGroupModalBox">

        <div class="modal-head">
            <div class="modal-head-title">👥 Create New Group</div>
            <button type="button" class="modal-close-btn" id="closeModalBtn">×</button>
        </div>

        <div class="modal-body">

            <?php if($errors->any()): ?>
            <div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:.75rem 1rem;border-radius:8px;font-size:.82rem;margin-bottom:1rem;">
                <strong>Please fix the following:</strong>
                <ul style="margin:.4rem 0 0;padding-left:1.2rem;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($e); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('teacher.groups.store')); ?>" id="createGroupForm">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label>Group Name <span style="color:#ef4444;">*</span></label>
                    <input type="text"
                           name="name"
                           id="groupNameInput"
                           value="<?php echo e(old('name')); ?>"
                           placeholder="e.g. BSIT 3A — Group Alpha"
                           required>
                </div>

                <div class="form-group">
                    <label>Description <span style="color:#9ca3af;font-weight:400;">(optional)</span></label>
                    <textarea name="description"
                              rows="2"
                              placeholder="Brief description of this group..."><?php echo e(old('description')); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Add Students <span style="color:#9ca3af;font-weight:400;">(optional — you can add later)</span></label>

                    <?php
                        $allStudents = auth()->user()->myStudents()->orderBy('name')->get();
                    ?>

                    <?php if($allStudents->isEmpty()): ?>
                        <div style="padding:.85rem;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;color:#9ca3af;font-size:.82rem;text-align:center;">
                            No students registered yet.
                        </div>
                    <?php else: ?>
                        <input type="text"
                               id="studentSearchInput"
                               placeholder="🔍 Search by name or ID..."
                               style="margin-bottom:8px;"
                               oninput="filterStudents(this.value)">

                        <div class="student-checklist" id="studentList">
                            <?php $__currentLoopData = $allStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="student-check-item"
                                   data-name="<?php echo e(strtolower($s->name)); ?>"
                                   data-sid="<?php echo e(strtolower($s->student_id ?? '')); ?>">
                                <input type="checkbox"
                                       name="students[]"
                                       value="<?php echo e($s->id); ?>"
                                       style="accent-color:#4f46e5;flex-shrink:0;"
                                       <?php echo e(in_array($s->id, old('students', [])) ? 'checked' : ''); ?>>
                                <div>
                                    <div style="font-weight:600;font-size:.85rem;"><?php echo e($s->name); ?></div>
                                    <div style="font-size:.72rem;color:#9ca3af;">
                                        ID: <?php echo e($s->student_id ?? '—'); ?> &nbsp;·&nbsp; <?php echo e($s->department ?? 'No course'); ?>

                                    </div>
                                </div>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div style="font-size:.72rem;color:#9ca3af;margin-top:5px;">
                            <?php echo e($allStudents->count()); ?> student(s) available
                        </div>
                    <?php endif; ?>
                </div>

                <div class="modal-foot">
                    <button type="button" class="btn-outline" id="cancelModalBtn">Cancel</button>
                    <button type="submit" class="btn-primary">✅ Create Group</button>
                </div>

            </form>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/js/pages/teacher-groups.js')); ?>"></script>
<?php if($errors->any()): ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('createGroupModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            setTimeout(function () {
                var f = document.getElementById('groupNameInput');
                if (f) f.focus();
            }, 80);
        }
    });
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/teacher/group/index_group.blade.php ENDPATH**/ ?>