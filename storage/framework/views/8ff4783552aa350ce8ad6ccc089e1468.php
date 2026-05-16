<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/teacher-grade-project.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="wrap">

    
    <?php if(session('success')): ?>
        <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#dcfce7;color:#166534;">
            ✅ <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <?php if(session('error')): ?>
        <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#fee2e2;color:#991b1b;">
            ❌ <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <div class="top-actions">

        <a href="<?php echo e(route('teacher.projects.show', $project->id)); ?>"
           class="btn btn-outline">
            ← Back to Project
        </a>

    </div>

    
    <div class="stats">

        <div class="stat">
            <div class="stat-label">Total Students</div>
            <div class="stat-value">
                <?php echo e($stats['total']); ?>

            </div>
        </div>

        <div class="stat">
            <div class="stat-label">Submitted</div>
            <div class="stat-value">
                <?php echo e($stats['submitted']); ?>

            </div>
        </div>

        <div class="stat">
            <div class="stat-label">Graded</div>
            <div class="stat-value">
                <?php echo e($stats['graded']); ?>

            </div>
        </div>

        <div class="stat">
            <div class="stat-label">Pending</div>
            <div class="stat-value">
                <?php echo e($stats['pending']); ?>

            </div>
        </div>

    </div>

    
    <div class="card">

        <div class="card-header">

            <div class="card-title">
                👥 Student Submissions
            </div>

            <div style="font-size:.8rem;color:#9ca3af;">
                Max Score: <?php echo e($project->max_score); ?>

            </div>

        </div>

        <div class="card-body">

            <?php if($submissions->isEmpty()): ?>

                <div class="empty">
                    No student submissions yet.
                </div>

            <?php else: ?>

                <table>

                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                            <th>File</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <tr>

                                
                                <td>

                                    <div class="student">
                                        <?php echo e($submission->student->name); ?>

                                    </div>
                                    <?php if($submission->student->student_id): ?>
                                    <div style="font-size:.72rem;color:#6b7280;margin-top:2px;">
                                        🆔 <?php echo e($submission->student->student_id); ?>

                                    </div>
                                    <?php endif; ?>

                                </td>

                                
                                <td>

                                    <span class="badge

                                        <?php if($submission->status === 'submitted'): ?>
                                            submitted
                                        <?php elseif($submission->status === 'reviewed'): ?>
                                            reviewed
                                        <?php else: ?>
                                            draft
                                        <?php endif; ?>

                                    ">

                                        <?php echo e(ucfirst($submission->status)); ?>


                                    </span>

                                </td>

                                
                                <td>

                                    <?php echo e($submission->submitted_at
                                        ? $submission->submitted_at->format('M d, Y h:i A')
                                        : '—'); ?>


                                </td>

                                
                                <td>

                                    <?php if($submission->file_path): ?>

                                        <a href="<?php echo e(asset('storage/' . $submission->file_path)); ?>"
                                           target="_blank"
                                           class="file-link">

                                            📎 View File

                                        </a>

                                    <?php else: ?>
                                        —
                                    <?php endif; ?>

                                </td>

                                
                                <td>

                                    <a href="<?php echo e(route('teacher.grades.edit', [$project->id, $submission->student_id])); ?>"
                                       class="btn btn-primary">

                                        Grade

                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </tbody>

                </table>

            <?php endif; ?>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\BROKEN_SYSTEM\resources\views/teacher/grade/project.blade.php ENDPATH**/ ?>