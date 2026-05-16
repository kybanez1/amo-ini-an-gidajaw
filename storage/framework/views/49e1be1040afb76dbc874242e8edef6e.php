<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/teacher-grade-edit.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="grade-wrap">

    <a href="<?php echo e(route('teacher.grades.project', $project->id)); ?>" class="page-back">← Back to Grading</a>

    <?php if($errors->any()): ?>
        <div style="margin-bottom:1rem;padding:1rem;border-radius:10px;background:#fee2e2;color:#991b1b;">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div><?php echo e($error); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <div class="info-value"><?php echo e(Str::limit($project->title, 30)); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Max Score</div>
                    <div class="info-value"><?php echo e($project->max_score); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Submitted</div>
                    
                    <div class="info-value">
                        <?php echo e($assignment && $assignment->pivot && $assignment->pivot->submitted_at
                            ? \Carbon\Carbon::parse($assignment->pivot->submitted_at)->format('M d, Y')
                            : ($submission->submitted_at
                                ? $submission->submitted_at->format('M d, Y')
                                : '—')); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <div class="card-title">📄 Student Submission</div>
        </div>
        <div class="card-body">

            <div class="form-group">
                <div class="info-label">Message / Notes</div>
                <div class="submission-box">
                    <?php echo e($submission->content ?? $submission->message ?? 'No message provided.'); ?>

                </div>
            </div>

            <div class="form-group">
                <div class="info-label">Uploaded File</div>
                <?php if($submission->file_path): ?>
                    <div style="margin-top:10px;padding:14px;border:1px solid #e5e7eb;border-radius:12px;background:#f9fafb;">
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
                            <div>📎 <strong><?php echo e(basename($submission->file_path)); ?></strong></div>
                            <a href="<?php echo e(asset('storage/' . $submission->file_path)); ?>" target="_blank"
                               style="padding:8px 14px;background:#4f46e5;color:white;border-radius:8px;text-decoration:none;font-size:.85rem;font-weight:600;">
                                ⬇ Download File
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="color:#9ca3af;margin-top:8px;">No file uploaded.</div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <div class="card-title">✏️ Grade This Student</div>
        </div>
        <div class="card-body">

            <form method="POST"
                  action="<?php echo e(route('teacher.grades.store', [$project->id, $student->id])); ?>"
                  id="gradeForm">
                <?php echo csrf_field(); ?>

                
                <div class="form-group">
                    <label class="form-label">
                        Score <span style="color:#dc2626;">*</span>
                    </label>
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
                    <?php $__errorArgs = ['score'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-msg"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="score-preview" id="scorePreview" style="display:none;">
                    <span class="score-preview-label">Grade Preview</span>
                    <span class="score-preview-val" id="previewVal">—</span>
                </div>

                
                <div class="form-group">
                    <label class="form-label">
                        Feedback <span style="font-weight:400;color:#9ca3af;">(optional)</span>
                    </label>
                    <textarea name="feedback"
                              class="feedback-textarea"
                              placeholder="Provide constructive feedback..."><?php echo e(old('feedback', $assignment && $assignment->pivot ? $assignment->pivot->feedback : '')); ?></textarea>
                    <?php $__errorArgs = ['feedback'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-msg"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <button type="submit" class="btn-submit">
                    
                    <?php echo e(($assignment && $assignment->pivot && $assignment->pivot->assignment_status === 'graded')
                        ? '✅ Update Grade'
                        : '✅ Submit Grade'); ?>

                </button>

            </form>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<script src="<?php echo e(asset('assets/js/pages/teacher-grade-edit.js')); ?>"></script>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\BROKEN_SYSTEM\resources\views/teacher/grade/edit.blade.php ENDPATH**/ ?>