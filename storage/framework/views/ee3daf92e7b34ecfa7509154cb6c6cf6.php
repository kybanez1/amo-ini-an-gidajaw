<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/student-group-show.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page" style="max-width:900px;margin:auto;padding:1.5rem;font-family:'Sora',sans-serif;">

    <a href="<?php echo e(route('student.dashboard')); ?>"
       style="display:inline-block;margin-bottom:1.25rem;color:#374151;text-decoration:none;font-size:.88rem;">
        ← Back to Dashboard
    </a>

    
    <div style="background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:16px;padding:2rem;color:white;margin-bottom:1.5rem;">
        <div style="font-size:1.5rem;font-weight:800;margin-bottom:.5rem;">
            👥 <?php echo e($group->name); ?>

        </div>
        <?php if($group->description): ?>
            <div style="opacity:.85;font-size:.9rem;"><?php echo e($group->description); ?></div>
        <?php endif; ?>
        <div style="margin-top:1rem;font-size:.82rem;opacity:.75;">
            Teacher: <?php echo e($group->teacher->name ?? '—'); ?>

            · <?php echo e($group->students->count()); ?> member(s)
        </div>
    </div>

    
    <div style="background:white;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;margin-bottom:1.5rem;">
        <div style="padding:1.1rem 1.5rem;border-bottom:1px solid #e5e7eb;font-weight:700;font-size:1rem;">
            📂 Group Projects
        </div>

        <?php if($group->projects->isEmpty()): ?>
            <div style="padding:3rem;text-align:center;color:#9ca3af;">
                No projects assigned to this group yet.
            </div>
        <?php else: ?>
            <?php $__currentLoopData = $group->projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="padding:1.25rem 1.5rem;border-top:1px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
                    <div>
                        <div style="font-weight:700;color:#111827;"><?php echo e($project->title); ?></div>
                        <div style="font-size:.78rem;color:#6b7280;margin-top:3px;">
                            Due: <?php echo e($project->due_date ? \Carbon\Carbon::parse($project->due_date)->format('M d, Y') : '—'); ?>

                            · Max Score: <?php echo e($project->max_score); ?>

                        </div>
                    </div>
                    <a href="<?php echo e(route('student.projects.show', $project->id)); ?>"
                       style="padding:.55rem 1rem;background:#4f46e5;color:white;border-radius:8px;text-decoration:none;font-size:.82rem;font-weight:600;">
                        👁 View Project
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>

    
    <div style="background:white;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;">
        <div style="padding:1.1rem 1.5rem;border-bottom:1px solid #e5e7eb;font-weight:700;font-size:1rem;">
            🧑‍🎓 Members (<?php echo e($group->students->count()); ?>)
        </div>
        <?php $__empty_1 = true; $__currentLoopData = $group->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="padding:1rem 1.5rem;border-top:1px solid #f3f4f6;display:flex;align-items:center;gap:.75rem;">
                <div style="width:36px;height:36px;background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:.85rem;flex-shrink:0;">
                    <?php echo e(strtoupper(substr($member->name, 0, 1))); ?>

                </div>
                <div>
                    <div style="font-weight:600;color:#111827;font-size:.88rem;"><?php echo e($member->name); ?></div>
                    <div style="font-size:.72rem;color:#9ca3af;"><?php echo e($member->student_id ?? $member->email); ?></div>
                </div>
                <?php if($member->id === auth()->id()): ?>
                    <span style="margin-left:auto;background:#dbeafe;color:#1d4ed8;padding:.25rem .6rem;border-radius:999px;font-size:.7rem;font-weight:700;">
                        You
                    </span>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="padding:2rem;text-align:center;color:#9ca3af;">No members yet.</div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\BROKEN_SYSTEM\resources\views/student/group/show.blade.php ENDPATH**/ ?>