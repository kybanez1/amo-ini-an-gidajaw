<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/teacher-project-show.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="wrap">

    <?php if(session('success')): ?>
        <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">
            ✅ <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#fee2e2;color:#991b1b;border:1px solid #fecaca;">
            ❌ <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <div class="card">
        <div class="header">
            <div>📂 <?php echo e($project->title); ?></div>
            <div>
                <span class="badge <?php echo e(in_array($project->status, ['ongoing','published','active']) ? 'active' : 'closed'); ?>">
                    <?php echo e(ucfirst($project->status)); ?>

                </span>
            </div>
        </div>
        <div class="body">
            <div class="grid">
                <div>
                    <div class="label">Description</div>
                    <div class="value"><?php echo e($project->description ?: 'No description provided.'); ?></div>
                </div>
                <div>
                    <div class="label">Group</div>
                    <div class="value">
                        <?php echo e($project->group->name ?? 'No group assigned'); ?>

                        <?php if($project->group): ?>
                            <div style="margin-top:8px;"><span class="group-badge">👥 Group Project</span></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div>
                    <div class="label">Requirements</div>
                    <div class="value"><?php echo e($project->requirements ?: 'No requirements provided.'); ?></div>
                </div>
                <div>
                    <div class="label">Max Score</div>
                    <div class="value"><?php echo e($project->max_score); ?></div>
                </div>
                <div>
                    <div class="label">Teacher</div>
                    <div class="value"><?php echo e($project->teacher->name ?? '—'); ?></div>
                </div>
                <div>
                    <div class="label">Start Date</div>
                    <div class="value">
                        <?php echo e($project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y h:i A') : '—'); ?>

                    </div>
                </div>
                <div>
                    <div class="label">Due Date</div>
                    <div class="value">
                        <?php echo e($project->due_date ? \Carbon\Carbon::parse($project->due_date)->format('M d, Y h:i A') : '—'); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="body">
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-number"><?php echo e($submittedCount ?? 0); ?></div>
                    <div class="stat-label">Submitted</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number"><?php echo e($gradedCount ?? 0); ?></div>
                    <div class="stat-label">Graded</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number"><?php echo e($project->tasks->count()); ?></div>
                    <div class="stat-label">Total Tasks</div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="body">
            <div class="btn-row">
                <a href="<?php echo e(route('teacher.projects.edit', $project->id)); ?>" class="btn btn-primary">✏️ Edit Project</a>
                <a href="<?php echo e(route('teacher.grades.project', $project->id)); ?>" class="btn btn-outline">⭐ Grade All</a>
                <a href="<?php echo e(route('teacher.projects.index')); ?>" class="btn btn-outline">← Back</a>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="header">📋 Project Tasks</div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Description</th>
                        <th>Deadline</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $project->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $taskSubmission = \App\Models\ProjectSubmission::where('project_id', $project->id)
                                ->where('task_id', $task->id)
                                ->whereIn('status', ['submitted','graded','reviewed'])
                                ->with('student')
                                ->first();
                        ?>
                        <tr>
                            <td><div class="student-name"><?php echo e($task->title); ?></div></td>
                            <td><?php echo e($task->description ?? '—'); ?></td>
                            <td>
                                <?php if($task->due_date): ?>
                                    <?php echo e(\Carbon\Carbon::parse($task->due_date)->format('M d, Y h:i A')); ?>

                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($taskSubmission): ?>
                                    <span class="status-pill status-submitted">✅ Submitted</span>
                                    <?php if($taskSubmission->student): ?>
                                        <div style="margin-top:6px;font-size:.75rem;color:#6b7280;">
                                            by <strong><?php echo e($taskSubmission->student->name); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="status-pill status-pending">⏳ Pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4"><div class="empty-box">No tasks added yet.</div></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="card">
        <div class="header">🧑‍🎓 Student Submissions</div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Score</th>
                        <th>File</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $submissions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="student-name"><?php echo e($submission->student->name ?? 'Unknown Student'); ?></div>
                                <?php if(isset($submission->student->student_id)): ?>
                                    <div style="font-size:.72rem;color:#6b7280;margin-top:2px;">
                                        ID: <?php echo e($submission->student->student_id); ?>

                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($submission->task->title ?? 'General Submission'); ?></td>
                            <td>
                                <span class="status-pill
                                    <?php echo e($submission->status === 'graded'
                                        ? 'status-graded'
                                        : (in_array($submission->status, ['submitted','reviewed'])
                                            ? 'status-submitted'
                                            : 'status-pending')); ?>">
                                    <?php echo e(ucfirst($submission->status)); ?>

                                </span>
                            </td>
                            <td>
                                <?php echo e($submission->submitted_at
                                    ? $submission->submitted_at->format('M d, Y h:i A')
                                    : '—'); ?>

                            </td>
                            <td>
                                <?php if($submission->score !== null): ?>
                                    <strong><?php echo e($submission->score); ?></strong> / <?php echo e($project->max_score); ?>

                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($submission->file_path): ?>
                                    <a href="<?php echo e(asset('storage/' . $submission->file_path)); ?>"
                                       target="_blank" class="file-link">📎 View</a>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                    
                                    <a href="<?php echo e(route('teacher.submissions.show', $submission->id)); ?>"
                                       class="btn btn-outline" style="font-size:.78rem;padding:.45rem .8rem;">
                                        👁 View
                                    </a>
                                    
                                    <a href="<?php echo e(route('teacher.grades.edit', [$project->id, $submission->student_id])); ?>"
                                       class="btn btn-primary" style="font-size:.78rem;padding:.45rem .8rem;">
                                        ⭐ Grade
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7"><div class="empty-box">No submissions yet.</div></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div style="padding:1rem 1.5rem;">
            <?php echo e($submissions->links()); ?>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\BROKEN_SYSTEM\resources\views/teacher/projects/show.blade.php ENDPATH**/ ?>