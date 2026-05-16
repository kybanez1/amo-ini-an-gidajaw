<?php $__env->startSection('content'); ?>

<div style="max-width:900px;margin:auto;padding:2rem;">

    <?php if(session('success')): ?>
        <div style="background:#dcfce7;padding:1rem;border-radius:10px;margin-bottom:1rem;color:#166534;">
            ✅ <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div style="background:#fee2e2;padding:1rem;border-radius:10px;margin-bottom:1rem;color:#991b1b;">
            <strong>Please fix the following:</strong>
            <ul style="margin-top:8px;padding-left:18px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <div style="background:white;border-radius:16px;padding:2rem;border:1px solid #e5e7eb;margin-bottom:1.5rem;">

        <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem;">
            📄 Submission Details
        </h2>

        <div style="margin-bottom:1rem;">
            <strong>Student:</strong>
            <?php echo e($submission->student->name); ?>

        </div>

        <div style="margin-bottom:1rem;">
            <strong>Project:</strong>
            <?php echo e($submission->project->title); ?>

        </div>

        <div style="margin-bottom:1rem;">
            <strong>Task:</strong>
            <?php echo e($submission->task->title ?? 'General Submission'); ?>

        </div>

        <div style="margin-bottom:1rem;">
            <strong>Status:</strong>
            <?php echo e(ucfirst($submission->status)); ?>

        </div>

        <div style="margin-bottom:1rem;">
            <strong>Submitted At:</strong>
            <?php echo e($submission->submitted_at ? $submission->submitted_at->format('M d, Y h:i A') : '—'); ?>

        </div>

        <?php if($submission->content): ?>
        <div style="margin-bottom:1rem;">
            <strong>Content:</strong>
            <div style="padding:1rem;border:1px solid #e5e7eb;border-radius:10px;background:#fafafa;white-space:pre-wrap;margin-top:6px;">
                <?php echo e($submission->content); ?>

            </div>
        </div>
        <?php endif; ?>

        <div style="margin-bottom:1.5rem;">
            <strong>File:</strong>
            <?php if($submission->file_path): ?>
                <a href="<?php echo e(asset('storage/' . $submission->file_path)); ?>"
                   target="_blank"
                   style="margin-left:8px;">
                    📎 Open Submission File
                </a>
            <?php else: ?>
                <span style="color:#9ca3af;margin-left:8px;">No file uploaded.</span>
            <?php endif; ?>
        </div>

    </div>

    
    <div style="background:white;border-radius:16px;padding:2rem;border:1px solid #e5e7eb;">

        <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:1.5rem;">
            ⭐ Grade This Submission
        </h2>

        <form method="POST"
              action="<?php echo e(route('teacher.submissions.grade.store', $submission->id)); ?>">

            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <?php if($submission->task): ?>
            <div style="margin-bottom:1rem;padding:1rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;">
                <div style="font-weight:700;color:#166534;margin-bottom:.75rem;">
                    📋 Task: <?php echo e($submission->task->title); ?>

                </div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">
                    Task Score
                    <span style="font-weight:400;color:#6b7280;font-size:.85rem;">
                        (max: <?php echo e($submission->task->max_points ?? 100); ?> pts)
                    </span>
                </label>
                <input type="number"
                       name="task_score"
                       value="<?php echo e(old('task_score', $submission->task_score)); ?>"
                       min="0"
                       max="<?php echo e($submission->task->max_points ?? 100); ?>"
                       style="width:100%;padding:.8rem;border:1px solid #d1d5db;border-radius:10px;margin-bottom:.5rem;">
                <div style="font-size:.78rem;color:#6b7280;">
                    Grades this specific task only — based on the max points set for this task.
                </div>
            </div>
            <?php endif; ?>

            
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-weight:600;margin-bottom:6px;">
                    Overall Project Score
                    <span style="font-weight:400;color:#6b7280;font-size:.85rem;">
                        (max: <?php echo e($submission->project->max_score); ?>)
                    </span>
                </label>
                <input type="number"
                       name="score"
                       value="<?php echo e(old('score', $submission->score)); ?>"
                       min="0"
                       max="<?php echo e($submission->project->max_score); ?>"
                       required
                       style="width:100%;padding:.8rem;border:1px solid #d1d5db;border-radius:10px;">
            </div>

            <div style="margin-bottom:1.5rem;">
                <label style="display:block;font-weight:600;margin-bottom:6px;">
                    Feedback
                </label>
                <textarea name="feedback"
                          rows="5"
                          style="width:100%;padding:.8rem;border:1px solid #d1d5db;border-radius:10px;"><?php echo e(old('feedback', $submission->feedback)); ?></textarea>
            </div>

            <div style="display:flex;gap:10px;flex-wrap:wrap;">

                <button type="submit"
                        style="background:#4f46e5;color:white;padding:.8rem 1.4rem;border:none;border-radius:10px;font-weight:700;cursor:pointer;">
                    ✅ Save Grade
                </button>

                <a href="<?php echo e(route('teacher.projects.show', $submission->project_id)); ?>"
                   style="border:1px solid #d1d5db;padding:.8rem 1.2rem;border-radius:10px;text-decoration:none;color:#374151;">
                    ← Back to Project
                </a>

            </div>

        </form>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/teacher/submissions/show.blade.php ENDPATH**/ ?>