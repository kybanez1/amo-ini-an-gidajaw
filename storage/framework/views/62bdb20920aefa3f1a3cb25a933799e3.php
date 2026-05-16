<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/teacher-projects-graded.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page">

    <div class="title">
        ✅ Graded Projects
    </div>

    <div class="subtitle">
        Projects with graded student submissions
    </div>

    <?php if($gradedSubmissions->count()): ?>

        <div class="grid">

            <?php $__currentLoopData = $gradedSubmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php

                    $gradedCount = $project->assignments
                        ->where('pivot.assignment_status', 'graded')
                        ->count();

                ?>

                <div class="card">

                    <div class="body">

                        <div class="badge">
                            GRADED
                        </div>

                        <div class="name">
                            <?php echo e($project->title); ?>

                        </div>

                        <div class="desc">
                            <?php echo e($project->description); ?>

                        </div>

                        <div class="stats">

                            <div class="pill">
                                👥 <?php echo e($gradedCount); ?> Graded
                            </div>

                            <div class="pill">
                                🏆 <?php echo e($project->max_score); ?> Max Score
                            </div>

                        </div>

                        <a href="<?php echo e(route('teacher.projects.show', $project->id)); ?>"
                           class="btn">

                            👁 View Project

                        </a>

                    </div>

                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

        <div style="margin-top:2rem;">
            <?php echo e($gradedSubmissions->links()); ?>

        </div>

    <?php else: ?>

        <div class="empty">

            <div style="font-size:60px;">
                📄
            </div>

            <h2>No graded projects yet</h2>

            <p style="color:#6b7280;">
                Once you grade student submissions,
                projects will appear here.
            </p>

        </div>

    <?php endif; ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/teacher/projects/graded.blade.php ENDPATH**/ ?>