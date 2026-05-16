<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/student-project-show.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page">

    <a href="<?php echo e(route('student.projects.index')); ?>"
       style="display:inline-block;margin-bottom:1rem;color:#374151;text-decoration:none;">
        ← Back
    </a>

    <div class="card">

        <!-- TITLE -->
        <div class="title">
            <?php echo e($project->title); ?>

        </div>

        <!-- META -->
        <div class="meta">
            Teacher:
            <?php echo e($project->teacher->name ?? 'Teacher'); ?>


            ·

            Due:
            <?php echo e(\Carbon\Carbon::parse($project->due_date)->format('M d, Y')); ?>

        </div>

        <!-- DESCRIPTION -->
        <div class="section">

            <div class="section-title">
                📌 Project Description
            </div>

            <div class="box">
                <?php echo e($project->description); ?>

            </div>

        </div>

        <!-- REQUIREMENTS -->
        <div class="section">

            <div class="section-title">
                📝 Instructions
            </div>

            <div class="box">

                <?php if($project->requirements): ?>
                    <div style="margin-bottom:1rem;"><?php echo e($project->requirements); ?></div>
                <?php else: ?>
                    <div style="margin-bottom:<?php echo e($project->instruction_file ? '1rem' : '0'); ?>;color:#6b7280;">
                        Follow teacher instructions carefully.
                    </div>
                <?php endif; ?>

                <?php if($project->instruction_file || $project->instruction_link): ?>
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                flex-wrap:wrap;gap:.75rem;padding:1rem;
                                background:#f5f3ff;border:1px solid #c7d2fe;
                                border-radius:10px;margin-top:.5rem;">
                        <div>
                            <div style="font-weight:700;color:#4338ca;margin-bottom:3px;">
                                <?php if($project->instruction_file): ?>
                                    📎 <?php echo e($project->instruction_file_name ?? basename($project->instruction_file)); ?>

                                <?php else: ?>
                                    🔗 Instruction Link
                                <?php endif; ?>
                            </div>
                            <div style="font-size:.75rem;color:#6b7280;">
                                <?php if($project->instruction_file_uploaded_at): ?>
                                    Uploaded <?php echo e(\Carbon\Carbon::parse($project->instruction_file_uploaded_at)->format('M d, Y')); ?>

                                <?php else: ?>
                                    Uploaded by teacher
                                <?php endif; ?>
                            </div>
                        </div>
                        <div style="display:flex;gap:8px;">
                            <?php if($project->instruction_file): ?>
                                <a href="<?php echo e(asset('storage/' . $project->instruction_file)); ?>"
                                   target="_blank"
                                   style="padding:.5rem 1rem;background:#eef2ff;color:#4338ca;
                                          border:1px solid #c7d2fe;border-radius:8px;
                                          text-decoration:none;font-size:.82rem;font-weight:600;">
                                    👁 View
                                </a>
                                <a href="<?php echo e(asset('storage/' . $project->instruction_file)); ?>"
                                   download="<?php echo e($project->instruction_file_name ?? basename($project->instruction_file)); ?>"
                                   style="padding:.5rem 1rem;background:#4f46e5;color:white;
                                          border-radius:8px;text-decoration:none;
                                          font-size:.82rem;font-weight:600;">
                                    ⬇ Download
                                </a>
                            <?php elseif($project->instruction_link): ?>
                                <a href="<?php echo e($project->instruction_link); ?>"
                                   target="_blank"
                                   style="padding:.5rem 1rem;background:#4f46e5;color:white;
                                          border-radius:8px;text-decoration:none;
                                          font-size:.82rem;font-weight:600;">
                                    🔗 Open Link
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

        </div>

        <!-- PROJECT TASKS -->
        <div class="section">

            <div class="section-title">
                📋 Assigned Tasks
            </div>

            <?php if($project->tasks && $project->tasks->count()): ?>

                <div class="tasks-wrap">

                    <?php $__currentLoopData = $project->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php

                            /*
                            |--------------------------------------------------------------------------
                            | CHECK STUDENT SUBMISSION
                            |--------------------------------------------------------------------------
                            */

                            $taskSubmission = \App\Models\ProjectSubmission::where([
                                'project_id' => $project->id,
                                'task_id'    => $task->id,
                                'student_id' => auth()->id(),
                            ])->whereIn('status', [
                                'submitted',
                                'reviewed',
                                'graded'
                            ])->first();

                            $isCompleted = $taskSubmission ? true : false;

                        ?>

                        <div class="task-card">

                            <div class="task-top">

                                <div>

                                    <div class="task-title">
                                        <?php echo e($task->title); ?>

                                    </div>

                                    <?php if($task->description): ?>

                                        <div class="task-desc">
                                            <?php echo e($task->description); ?>

                                        </div>

                                    <?php endif; ?>

                                </div>

                                <div>

                                    <span class="status-pill
                                        <?php echo e($isCompleted
                                            ? 'status-completed'
                                            : 'status-pending'); ?>">

                                        <?php echo e($isCompleted ? 'Completed' : 'Pending'); ?>


                                    </span>

                                </div>

                            </div>

                            <!-- TASK META -->
                            <div class="task-meta">

                                <?php if($task->due_date): ?>

                                    <div class="meta-pill">
                                        ⏰ Due:
                                        <?php echo e(\Carbon\Carbon::parse($task->due_date)->format('M d, Y h:i A')); ?>

                                    </div>

                                <?php endif; ?>

                                <?php if($taskSubmission && $taskSubmission->submitted_at): ?>

                                    <div class="meta-pill"
                                         style="background:#dcfce7;color:#166534;">
                                        ✅ Submitted:
                                        <?php echo e($taskSubmission->submitted_at->format('M d, Y h:i A')); ?>

                                    </div>

                                <?php endif; ?>

                                <?php if($taskSubmission && $taskSubmission->task_score !== null): ?>
                                    <div class="meta-pill"
                                         style="background:#dbeafe;color:#1d4ed8;font-weight:700;">
                                        ⭐ Score: <?php echo e($taskSubmission->task_score); ?> / <?php echo e($task->max_points ?? 100); ?>

                                    </div>
                                <?php endif; ?>

                                <?php if($taskSubmission && $taskSubmission->feedback): ?>
                                    <div class="meta-pill"
                                         style="background:#f0fdf4;color:#166534;">
                                        💬 <?php echo e($taskSubmission->feedback); ?>

                                    </div>
                                <?php endif; ?>

                            </div>

                            <!-- TASK ACTION -->
                            <div class="task-actions">

                                <?php if($taskSubmission): ?>

                                    <button
                                        class="task-btn-closed"
                                        disabled>

                                        ✅ Task Closed

                                    </button>

                                <?php else: ?>

                                    <a
                                        href="<?php echo e(route('student.projects.submit', [$project->id, 'task' => $task->id])); ?>"
                                        class="btn btn-primary"
                                    >
                                        📤 Submit Task
                                    </a>

                                <?php endif; ?>

                            </div>

                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>

            <?php else: ?>

                <div class="empty-task">
                    No tasks assigned yet.
                </div>

            <?php endif; ?>

        </div>



    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/student/projects/show.blade.php ENDPATH**/ ?>