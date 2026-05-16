<?php $__env->startSection('title', 'My Projects'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/teacher-projects.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="pms-page">

    <div class="page-header">
        <div>
            <div class="page-title">📂 My Projects</div>
            <div class="page-subtitle">Manage all your classroom projects</div>
        </div>
        <a href="<?php echo e(route('teacher.projects.create')); ?>" class="btn-primary">+ New Project</a>
    </div>

    <div class="projects-grid">

        <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

        <div class="project-card">

            <div class="project-header">
                <div class="project-icon">📂</div>
                <span class="status-badge"><?php echo e(ucfirst($project->status)); ?></span>
            </div>

            <div class="project-title"><?php echo e($project->title); ?></div>

            <div class="project-desc">
                <?php echo e(Str::limit($project->description, 80) ?? 'No description'); ?>

            </div>

            <div class="project-stats">

                <div class="stat">
                    <div class="value"><?php echo e($project->group->name ?? '—'); ?></div>
                    <div class="label">Group</div>
                </div>

                
                <div class="stat">
                    <div class="value">
                        <?php echo e($project->due_date
                            ? \Carbon\Carbon::parse($project->due_date)->format('M d')
                            : '—'); ?>

                    </div>
                    <div class="label">Due</div>
                </div>

                <div class="stat">
                    <div class="value"><?php echo e($project->max_score); ?></div>
                    <div class="label">Score</div>
                </div>

            </div>

            <div class="project-actions">
                <a href="<?php echo e(route('teacher.projects.show', $project->id)); ?>" class="btn-outline">👁 View</a>
                <a href="<?php echo e(route('teacher.projects.edit', $project->id)); ?>" class="btn-outline">✏️ Edit</a>
            </div>

        </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

        <div style="grid-column:1/-1;text-align:center;padding:3rem;color:#9ca3af;">
            No projects yet. Create your first project.
        </div>

        <?php endif; ?>

    </div>

    <div style="margin-top:1.5rem;">
        <?php echo e($projects->links()); ?>

    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/teacher/projects/index.blade.php ENDPATH**/ ?>