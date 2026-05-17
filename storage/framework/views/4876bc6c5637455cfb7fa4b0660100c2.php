<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/teacher-students.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="wrap">

    <div class="page-header">
        <div>
            <div class="page-title">🧑‍🎓 My Students</div>
            <div class="page-sub">Students who registered using your teacher code</div>
        </div>
        <a href="<?php echo e(route('teacher.dashboard')); ?>"
           style="padding:.65rem 1.2rem;border:1px solid #d1d5db;border-radius:10px;
                  text-decoration:none;color:#374151;font-size:.85rem;font-weight:600;">
            ← Dashboard
        </a>
    </div>

    
    <div class="code-box">
        <div>
            <div class="code-label">YOUR TEACHER CODE</div>
            <div class="code-value" id="teacherCode" onclick="copyCode()" title="Click to copy">
                <?php echo e($teacher->teacher_code ?? '------'); ?>

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

    <?php if(session('success')): ?>
        <div style="margin-bottom:1rem;padding:1rem;background:#dcfce7;color:#166534;
                    border-radius:10px;border:1px solid #bbf7d0;">
            ✅ <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
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
                <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="color:#9ca3af;"><?php echo e($loop->iteration); ?></td>
                        <td>
                            <div class="name-cell">
                                <div class="avatar"><?php echo e(strtoupper(substr($student->name, 0, 1))); ?></div>
                                <div>
                                    <div class="s-name"><?php echo e($student->name); ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($student->student_id ?? '—'); ?></td>
                        <td><?php echo e($student->email); ?></td>
                        <td><?php echo e($student->department ?? '—'); ?></td>
                        <td style="color:#9ca3af;font-size:.78rem;">
                            <?php echo e($student->pivot->created_at
                                ? $student->pivot->created_at->format('M d, Y')
                                : '—'); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
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
                                        <?php echo e($teacher->teacher_code); ?>

                                    </strong>
                                    with your students.
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem;">
        <?php echo e($students->links()); ?>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/js/pages/teacher-students.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/teacher/students/index.blade.php ENDPATH**/ ?>