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
        <a href="<?php echo e(route('teacher.projects.show', $project->id)); ?>" class="btn btn-outline">
            ← Back to Project
        </a>
    </div>

    
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div class="card-title">📂 <?php echo e($project->title); ?></div>
            <div style="font-size:.8rem;color:#9ca3af;">Max Score: <?php echo e($project->max_score); ?></div>
        </div>
        <div class="card-body" style="padding:1rem 1.5rem;">
            <div style="display:flex;gap:2rem;flex-wrap:wrap;">
                <div>
                    <div style="font-size:.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Group</div>
                    <div style="font-weight:600;"><?php echo e($group ? $group->name : 'No group assigned'); ?></div>
                </div>
                <div>
                    <div style="font-size:.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Due Date</div>
                    <div style="font-weight:600;"><?php echo e($project->due_date ? $project->due_date->format('M d, Y') : '—'); ?></div>
                </div>
                <div>
                    <div style="font-size:.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Status</div>
                    <div style="font-weight:600;"><?php echo e(ucfirst($project->status)); ?></div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="stats">
        <div class="stat">
            <div class="stat-label">Total Students</div>
            <div class="stat-value"><?php echo e($stats['total']); ?></div>
        </div>
        <div class="stat">
            <div class="stat-label">Submitted</div>
            <div class="stat-value"><?php echo e($stats['submitted']); ?></div>
        </div>
        <div class="stat">
            <div class="stat-label">Graded</div>
            <div class="stat-value"><?php echo e($stats['graded']); ?></div>
        </div>
        <div class="stat">
            <div class="stat-label">Pending</div>
            <div class="stat-value"><?php echo e($stats['pending']); ?></div>
        </div>
    </div>

    
    <?php if($submissions->isNotEmpty()): ?>
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div class="card-title">📄 Student Submissions</div>
        </div>
        <div class="card-body">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                        <th>Score</th>
                        <th>File</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <div class="student"><?php echo e($sub->student->name ?? '—'); ?></div>
                            <?php if($sub->student && $sub->student->student_id): ?>
                                <div style="font-size:.72rem;color:#6b7280;">🆔 <?php echo e($sub->student->student_id); ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?php echo e($sub->status === 'graded' ? 'reviewed' : ($sub->status === 'submitted' ? 'submitted' : 'draft')); ?>">
                                <?php echo e(ucfirst($sub->status)); ?>

                            </span>
                        </td>
                        <td><?php echo e($sub->submitted_at ? $sub->submitted_at->format('M d, Y h:i A') : '—'); ?></td>
                        <td>
                            <?php if($sub->score !== null): ?>
                                <strong><?php echo e($sub->score); ?></strong> / <?php echo e($project->max_score); ?>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($sub->file_path): ?>
                                <a href="<?php echo e(asset('storage/' . $sub->file_path)); ?>" target="_blank" class="file-link">📎 View</a>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="card">
        <div class="card-header">
            <div class="card-title">✏️ Grade This Project</div>
            <div style="font-size:.8rem;color:#6b7280;">One grade applies to all group members</div>
        </div>
        <div class="card-body">

            <?php if($errors->any()): ?>
                <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#fee2e2;color:#991b1b;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div><?php echo e($error); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('teacher.grades.storeProject', $project->id)); ?>">
                <?php echo csrf_field(); ?>

                
                <?php if($group && $group->students->count() > 0): ?>
                <div style="margin-bottom:1.25rem;padding:1rem;background:#eef2ff;border-radius:10px;border:1px solid #c7d2fe;">
                    <div style="font-size:.85rem;font-weight:600;color:#4f46e5;margin-bottom:.5rem;">👥 Group Members (<?php echo e($group->students->count()); ?>)</div>
                    <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                        <?php $__currentLoopData = $group->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span style="padding:.25rem .65rem;background:white;border:1px solid #c7d2fe;border-radius:999px;font-size:.8rem;color:#3730a3;">
                                <?php echo e($member->name); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div style="font-size:.75rem;color:#6b7280;margin-top:.5rem;">All members will receive the same project grade.</div>
                </div>
                <?php endif; ?>

                
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block;font-size:.85rem;font-weight:600;color:#374151;margin-bottom:.5rem;">
                        Score <span style="color:#dc2626;">*</span>
                    </label>
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <input type="number"
                               name="score"
                               min="0"
                               max="<?php echo e($project->max_score); ?>"
                               value="<?php echo e(old('score', $groupGrade && $groupGrade->score !== null ? $groupGrade->score : '')); ?>"
                               required
                               style="width:120px;padding:.65rem 1rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:1.1rem;font-weight:700;text-align:center;">
                        <span style="color:#6b7280;font-size:.9rem;">/ <?php echo e($project->max_score); ?></span>
                    </div>
                </div>

                
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;font-size:.85rem;font-weight:600;color:#374151;margin-bottom:.5rem;">
                        Feedback <span style="font-weight:400;color:#9ca3af;">(optional)</span>
                    </label>
                    <textarea name="feedback"
                              rows="4"
                              placeholder="Provide constructive feedback for the group..."
                              style="width:100%;padding:.75rem 1rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:.9rem;resize:vertical;box-sizing:border-box;"><?php echo e(old('feedback', $groupGrade ? $groupGrade->feedback : '')); ?></textarea>
                </div>

                <button type="submit"
                        style="padding:.75rem 2rem;background:#4f46e5;color:white;border:none;border-radius:10px;font-size:.95rem;font-weight:600;cursor:pointer;">
                    <?php echo e($stats['graded'] > 0 ? '✅ Update Project Grade' : '✅ Submit Project Grade'); ?>

                </button>

            </form>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/teacher/grade/project.blade.php ENDPATH**/ ?>