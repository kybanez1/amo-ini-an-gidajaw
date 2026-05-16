<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/teacher-grade-edit.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="grade-wrap">

    <a href="<?php echo e(route('teacher.projects.show', $project->id)); ?>" class="page-back">← Back to Project</a>

    <?php if($errors->any()): ?>
        <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#fee2e2;color:#991b1b;">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div><?php echo e($error); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    
    <div class="card">
        <div class="student-banner">
            <div class="student-avatar"><?php echo e(strtoupper(substr($student->name, 0, 1))); ?></div>
            <div>
                <div class="student-name"><?php echo e($student->name); ?></div>
                <div class="student-email"><?php echo e($student->email); ?></div>
                <?php if($student->student_id): ?>
                    <div style="font-size:.75rem;color:#6b7280;margin-top:2px;">🆔 <?php echo e($student->student_id); ?></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="project-info">
                <div class="info-item">
                    <div class="info-label">Project</div>
                    <div class="info-value"><?php echo e(Str::limit($project->title, 35)); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Max Score</div>
                    <div class="info-value"><?php echo e($project->max_score); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Current Status</div>
                    <div class="info-value">
                        <?php $pivotStatus = $assignment->pivot->assignment_status ?? 'assigned'; ?>
                        <span style="padding:.2rem .6rem;border-radius:999px;font-size:.78rem;font-weight:700;
                            background:<?php echo e($pivotStatus === 'graded' ? '#dcfce7' : '#fef9c3'); ?>;
                            color:<?php echo e($pivotStatus === 'graded' ? '#166534' : '#854d0e'); ?>;">
                            <?php echo e(ucfirst($pivotStatus)); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <?php if($project->tasks->count()): ?>
    <div class="card">
        <div class="card-header">
            <div class="card-title">📋 Task Submissions</div>
        </div>
        <div class="card-body" style="padding:0;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                        <th style="padding:.75rem 1.25rem;text-align:left;font-size:.78rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Task</th>
                        <th style="padding:.75rem 1.25rem;text-align:left;font-size:.78rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Status</th>
                        <th style="padding:.75rem 1.25rem;text-align:left;font-size:.78rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Submitted</th>
                        <th style="padding:.75rem 1.25rem;text-align:left;font-size:.78rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">File</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $project->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $ts = $taskSubmissions->get($task->id); ?>
                        <tr style="border-bottom:1px solid #f3f4f6;">
                            <td style="padding:.85rem 1.25rem;font-weight:600;font-size:.88rem;"><?php echo e($task->title); ?></td>
                            <td style="padding:.85rem 1.25rem;">
                                <?php if($ts): ?>
                                    <span style="padding:.2rem .65rem;border-radius:999px;font-size:.78rem;font-weight:700;background:#dcfce7;color:#166534;">✅ Submitted</span>
                                <?php else: ?>
                                    <span style="padding:.2rem .65rem;border-radius:999px;font-size:.78rem;font-weight:700;background:#fef9c3;color:#854d0e;">⏳ Pending</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding:.85rem 1.25rem;font-size:.83rem;color:#6b7280;">
                                <?php echo e($ts && $ts->submitted_at ? $ts->submitted_at->format('M d, Y h:i A') : '—'); ?>

                            </td>
                            <td style="padding:.85rem 1.25rem;">
                                <?php if($ts && $ts->file_path): ?>
                                    <a href="<?php echo e(asset('storage/' . $ts->file_path)); ?>" target="_blank"
                                       style="color:#4f46e5;font-size:.83rem;font-weight:600;text-decoration:none;">📎 View File</a>
                                <?php elseif($ts && $ts->content): ?>
                                    <span style="font-size:.8rem;color:#6b7280;" title="<?php echo e($ts->content); ?>">📝 Text submission</span>
                                <?php else: ?>
                                    <span style="color:#d1d5db;">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($generalSubmission): ?>
    <div class="card">
        <div class="card-header"><div class="card-title">📄 General Submission</div></div>
        <div class="card-body">
            <?php if($generalSubmission->content): ?>
                <div style="padding:1rem;background:#f9fafb;border-radius:8px;font-size:.88rem;margin-bottom:1rem;">
                    <?php echo e($generalSubmission->content); ?>

                </div>
            <?php endif; ?>
            <?php if($generalSubmission->file_path): ?>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem;background:#f5f3ff;border:1px solid #c7d2fe;border-radius:10px;">
                    <span style="font-weight:600;color:#4338ca;">📎 <?php echo e(basename($generalSubmission->file_path)); ?></span>
                    <a href="<?php echo e(asset('storage/' . $generalSubmission->file_path)); ?>" target="_blank"
                       style="padding:.5rem 1rem;background:#4f46e5;color:white;border-radius:8px;text-decoration:none;font-size:.83rem;font-weight:600;">
                        ⬇ Download
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="card">
        <div class="card-header">
            <div class="card-title">✏️ Grade This Student</div>
            <div style="font-size:.8rem;color:#6b7280;">Individual grade — only applies to <?php echo e($student->name); ?></div>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('teacher.grades.individual.store', [$project->id, $student->id])); ?>" id="gradeForm">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label class="form-label">Score <span style="color:#dc2626;">*</span></label>
                    <div class="score-input-row">
                        <input type="number"
                               name="score"
                               id="scoreInput"
                               class="score-input"
                               min="0"
                               max="<?php echo e($project->max_score); ?>"
                               value="<?php echo e(old('score', $assignment && $assignment->pivot ? $assignment->pivot->score : '')); ?>"
                               required
                               oninput="updatePreview(this.value)">
                        <span class="score-max">/ <?php echo e($project->max_score); ?></span>
                    </div>
                </div>

                <div class="score-preview" id="scorePreview" style="display:none;">
                    <span class="score-preview-label">Grade Preview</span>
                    <span class="score-preview-val" id="previewVal">—</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Feedback <span style="font-weight:400;color:#9ca3af;">(optional)</span></label>
                    <textarea name="feedback"
                              class="feedback-textarea"
                              placeholder="Provide constructive feedback..."><?php echo e(old('feedback', $assignment && $assignment->pivot ? $assignment->pivot->feedback : '')); ?></textarea>
                </div>

                <button type="submit" class="btn-submit">
                    <?php echo e(($assignment && $assignment->pivot && $assignment->pivot->assignment_status === 'graded') ? '✅ Update Grade' : '✅ Submit Grade'); ?>

                </button>
            </form>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/js/pages/teacher-grade-edit.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/teacher/grade/individual.blade.php ENDPATH**/ ?>