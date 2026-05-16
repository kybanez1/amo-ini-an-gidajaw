<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/student-grades.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('title', 'My Grades'); ?>

<?php $__env->startSection('content'); ?>
<div class="grades-wrap">

    <!-- HEADER -->
    <div class="page-header">
        <div>
            <div class="page-title">🏆 My Grades</div>
            <div class="page-subtitle">
                View your scores and teacher feedback for all graded projects
            </div>
        </div>
        <a href="<?php echo e(route('student.projects.index')); ?>"
           style="padding:.55rem 1rem;border:1px solid #d1d5db;border-radius:8px;font-size:.82rem;text-decoration:none;color:#374151;background:#fff;">
            ← My Projects
        </a>
    </div>

    <!-- SUMMARY -->
    <div class="summary-row">

        <div class="summary-card">
            <div class="summary-icon icon-blue">🎓</div>
            <div>
                <div class="summary-val"><?php echo e($gradedProjects->count()); ?></div>
                <div class="summary-label">Projects Graded</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon icon-green">📊</div>
            <div>
                <div class="summary-val">
                    <?php echo e($averageScore !== null ? $averageScore : '—'); ?>

                </div>
                <div class="summary-label">Average Score</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon icon-amber">🆔</div>
            <div>
                <div class="summary-val" style="font-size:1rem;">
                    <?php echo e($student->student_id ?? '—'); ?>

                </div>
                <div class="summary-label">Student ID</div>
            </div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="table-card">

        <?php if($gradedProjects->isEmpty()): ?>

            <div class="empty-state">
                <div class="icon">📭</div>
                <h3>No grades yet</h3>
                <p>Your grades will appear here once your teacher reviews your submissions.</p>
            </div>

        <?php else: ?>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project</th>
                        <th>Teacher</th>
                        <th>Score</th>
                        <th>Submission Status</th>
                        <th>Teacher Remarks</th>
                        <th>Graded At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $gradedProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $score      = $project->pivot->score ?? 0;
                        $maxScore   = $project->max_score ?? 100;
                        $feedback   = $project->pivot->feedback ?? null;
                        $gradedAt   = $project->pivot->graded_at ?? null;
                        $pct        = ($maxScore > 0 && $score !== null) ? round(($score / $maxScore) * 100) : 0;

                        if ($pct >= 75) $pillClass = '';
                        elseif ($pct >= 50) $pillClass = 'mid';
                        else $pillClass = 'low';
                    ?>
                    <tr>
                        <td style="color:#9ca3af;font-size:.78rem;"><?php echo e($index + 1); ?></td>

                        <td>
                            <div class="project-name"><?php echo e($project->title); ?></div>
                            <div class="teacher-name">
                                Due <?php echo e($project->due_date ? \Carbon\Carbon::parse($project->due_date)->format('M d, Y') : '—'); ?>

                            </div>
                        </td>

                        <td>
                            <?php echo e($project->teacher->name ?? '—'); ?>

                        </td>

                        <td>
                            <span class="score-pill <?php echo e($pillClass); ?>">
                                <?php echo e($score); ?> / <?php echo e($maxScore); ?>

                                <span style="font-size:.7rem;opacity:.7;">(<?php echo e($pct); ?>%)</span>
                            </span>
                        </td>

                        <td>
                            <span class="status-chip chip-graded">✅ Graded</span>
                        </td>

                        <td>
                            <?php if($feedback): ?>
                                <div class="remarks-box"><?php echo e($feedback); ?></div>
                            <?php else: ?>
                                <span style="color:#9ca3af;font-size:.78rem;">No remarks</span>
                            <?php endif; ?>
                        </td>

                        <td style="font-size:.78rem;color:#6b7280;">
                            <?php if($gradedAt): ?>
                                <?php echo e(\Carbon\Carbon::parse($gradedAt)->format('M d, Y')); ?>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

        <?php endif; ?>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/student/grades.blade.php ENDPATH**/ ?>