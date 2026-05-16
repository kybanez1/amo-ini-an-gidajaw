<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/student-submit.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="submit-wrap">

    <a href="<?php echo e(route('student.projects.show', $project->id)); ?>"
       class="page-back">
        ← Back to Project
    </a>

    <?php if(session('success')): ?>
        <div class="alert-success">
            ✅ <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert-error">
            ❌ <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php

        $task = $task ?? null;

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT FIX
        |--------------------------------------------------------------------------
        */

        $submission = \App\Models\ProjectSubmission::where([
            'project_id' => $project->id,
            'student_id' => auth()->id(),
            'task_id'    => $task?->id,
        ])->latest()->first();

        $due = $task && $task->due_date
            ? \Carbon\Carbon::parse($task->due_date)
            : \Carbon\Carbon::parse($project->due_date);

        /*
        |--------------------------------------------------------------------------
        | STATUS FIX
        |--------------------------------------------------------------------------
        */

        $isSubmitted = $submission &&
            in_array($submission->status, [
                'submitted',
                'reviewed',
                'graded'
            ]);

        $isGraded = $submission &&
            in_array($submission->status, [
                'graded',
                'reviewed'
            ]);

        $daysLeft = now()->diffInDays($due, false);

        $isOverdue = now()->isAfter($due);

    ?>

    
    <?php if(!$isSubmitted && !$isGraded): ?>

    <div style="padding:.75rem 1.1rem;border-radius:12px;margin-bottom:1.25rem;display:flex;align-items:center;gap:.75rem;
        <?php echo e($isOverdue ? 'background:#fef2f2;border:1px solid #fecaca;color:#991b1b;' : ($daysLeft <= 3 ? 'background:#fff7ed;border:1px solid #fdba74;color:#9a3412;' : 'background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;')); ?>">

        <span style="font-size:1.3rem;">
            <?php echo e($isOverdue ? '🔴' : ($daysLeft <= 3 ? '🟡' : '🟢')); ?>

        </span>

        <span style="font-weight:600;font-size:.875rem;">

            <?php if($isOverdue): ?>

                ⚠️ This task is overdue.

            <?php elseif($daysLeft === 0): ?>

                ⏰ Due today!

            <?php else: ?>

                ⏳ <?php echo e((int)$daysLeft); ?> day(s) left.

            <?php endif; ?>

        </span>

    </div>

    <?php endif; ?>

    
    <div class="project-banner">

        <div style="font-size:2rem;">📂</div>

        <div>

            <div class="project-banner-title">
                <?php echo e($project->title); ?>

            </div>

            <div class="project-banner-meta">

                Teacher:
                <?php echo e($project->teacher->name ?? '—'); ?>


                ·

                Max Score:
                <?php echo e($project->max_score); ?>


            </div>

            <?php if($task): ?>

            <div style="margin-top:10px;">

                <div style="font-weight:700;color:#4338ca;">
                    📋 Task:
                    <?php echo e($task->title); ?>

                </div>

                <?php if($task->description): ?>

                    <div style="font-size:.8rem;color:#6b7280;margin-top:4px;">
                        <?php echo e($task->description); ?>

                    </div>

                <?php endif; ?>

            </div>

            <?php endif; ?>

        </div>

    </div>

    
    <?php if($isSubmitted): ?>

        <div class="state-card submitted">

            <div class="state-icon">✅</div>

            <div class="state-title">
                Task Closed
            </div>

            <div style="margin-top:8px;font-size:.85rem;">
                You already submitted this task.
            </div>

            <?php if($submission->submitted_at): ?>

                <div style="margin-top:10px;font-size:.8rem;color:#0369a1;">

                    Submitted:
                    <?php echo e($submission->submitted_at->format('M d, Y h:i A')); ?>


                </div>

            <?php endif; ?>

        </div>

    
    <?php elseif($isGraded): ?>

        <div class="state-card graded">

            <div class="state-icon">🏆</div>

            <div style="font-size:2.5rem;font-weight:700;">

                <?php echo e($submission->score ?? 0); ?>


                <span style="font-size:1rem;">
                    / <?php echo e($project->max_score); ?>

                </span>

            </div>

            <div class="state-title">
                Task Graded
            </div>

            <?php if($submission->feedback): ?>

                <div class="feedback-box">

                    <strong>Teacher Feedback:</strong>
                    <br>

                    <?php echo e($submission->feedback); ?>


                </div>

            <?php endif; ?>

        </div>

    
    <?php else: ?>

        <div class="card">

            <div class="card-header">
                📝 Submit Task
            </div>

            <div class="card-body">

                <form method="POST"
                      action="<?php echo e(route('student.projects.finalize', $project->id)); ?>"
                      enctype="multipart/form-data">

                    <?php echo csrf_field(); ?>

                    <?php if($task): ?>
                        <input type="hidden"
                               name="task_id"
                               value="<?php echo e($task->id); ?>">
                    <?php endif; ?>

                    <div class="form-group">

                        <label class="form-label">
                            Message / Notes
                        </label>

                        <textarea
                            name="content"
                            class="form-textarea"
                            placeholder="Write your answer here..."
                        ></textarea>

                    </div>

                    <div class="form-group">

                        <label class="form-label">
                            Upload File
                        </label>

                        <div class="file-upload-area"
                             onclick="document.getElementById('fileInput').click()">

                            <div style="font-size:1.8rem;margin-bottom:8px;">
                                ☁️
                            </div>

                            <div style="font-size:.83rem;color:#6b7280;">

                                <strong style="color:#4f46e5;">
                                    Click to choose file
                                </strong>

                            </div>

                            <input type="file"
                                   name="file"
                                   id="fileInput"
                                   style="display:none"
                                   onchange="showFile(this)">

                        </div>

                        <div class="file-name-display"
                             id="fileName"></div>

                    </div>

                    <div class="alert-warning">
                        ⚠️ Once submitted, this task will close.
                    </div>

                    <div class="btn-row">

                        <button type="submit"
                                class="btn-submit"
                                onclick="return confirm('Submit this task?')">

                            🚀 Submit Task

                        </button>

                    </div>

                </form>

            </div>

        </div>

    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<script src="<?php echo e(asset('assets/js/pages/student-submit.js')); ?>"></script>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\BROKEN_SYSTEM\resources\views/student/projects/submit.blade.php ENDPATH**/ ?>