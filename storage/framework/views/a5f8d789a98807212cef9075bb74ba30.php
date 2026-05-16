<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/student-projects.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="projects-wrap">

    <div class="page-header">

        <div>

            <div class="page-title">
                📂 My Projects
            </div>

            <div class="page-sub">
                <?php echo e($assignedProjects->count()); ?> project(s) assigned to you
            </div>

        </div>

        <a href="<?php echo e(route('student.dashboard')); ?>"
           style="font-size:0.85rem;color:#6b7280;text-decoration:none;">

            ← Dashboard

        </a>

    </div>

    
    <div class="filter-bar">

        <a href="<?php echo e(route('student.projects.index')); ?>"
           class="filter-btn <?php echo e(!request('status') ? 'active' : ''); ?>">

            All

        </a>

        <a href="<?php echo e(route('student.projects.index', ['status' => 'assigned'])); ?>"
           class="filter-btn <?php echo e(request('status') === 'assigned' ? 'active' : ''); ?>">

            ⏳ Pending

        </a>

        <a href="<?php echo e(route('student.projects.index', ['status' => 'submitted'])); ?>"
           class="filter-btn <?php echo e(request('status') === 'submitted' ? 'active' : ''); ?>">

            📤 Submitted

        </a>

        <a href="<?php echo e(route('student.projects.index', ['status' => 'graded'])); ?>"
           class="filter-btn <?php echo e(request('status') === 'graded' ? 'active' : ''); ?>">

            ✅ Graded

        </a>

    </div>

    <?php

        /*
        |--------------------------------------------------------------------------
        | FILTER PROJECTS CORRECTLY
        |--------------------------------------------------------------------------
        */

        $filtered = collect();

        foreach($assignedProjects as $project){

            /*
            |--------------------------------------------------------------------------
            | DEFAULT STATUS FROM PIVOT
            |--------------------------------------------------------------------------
            */

            $status =
                $project->pivot->assignment_status
                ?? 'assigned';

            /*
            |--------------------------------------------------------------------------
            | KEEP GRADED STATUS
            |--------------------------------------------------------------------------
            */

            if($status !== 'graded'){

                /*
                |--------------------------------------------------------------------------
                | GROUP SUBMISSION CHECK
                |--------------------------------------------------------------------------
                */

                $groupSubmitted = false;

                if($project->group_id){

                    $groupStudentIds =
                        \DB::table('group_student')
                            ->where(
                                'group_id',
                                $project->group_id
                            )
                            ->pluck('student_id');

                    $groupSubmitted =
                        \App\Models\ProjectSubmission::where(
                            'project_id',
                            $project->id
                        )
                        ->whereIn(
                            'student_id',
                            $groupStudentIds
                        )
                        ->whereIn('status', [
                            'submitted',
                            'graded',
                            'reviewed'
                        ])
                        ->exists();
                }

                /*
                |--------------------------------------------------------------------------
                | AUTO STATUS
                |--------------------------------------------------------------------------
                */

                if($groupSubmitted){
                    $status = 'submitted';
                }
            }

            /*
            |--------------------------------------------------------------------------
            | FILTER LOGIC
            |--------------------------------------------------------------------------
            */

            if(!request('status')){

                $filtered->push($project);

            }elseif(request('status') === $status){

                $filtered->push($project);
            }
        }

    ?>

    <?php if($filtered->isEmpty()): ?>

        <div class="empty-state">

            <div class="icon">
                📭
            </div>

            <h3>
                No projects found
            </h3>

            <p>

                <?php if(request('status') === 'assigned'): ?>
                    No pending projects found.
                <?php elseif(request('status') === 'submitted'): ?>
                    No submitted projects found.
                <?php elseif(request('status') === 'graded'): ?>
                    No graded projects found.
                <?php else: ?>
                    No projects have been assigned to you yet.
                <?php endif; ?>

            </p>

        </div>

    <?php else: ?>

        <div class="projects-grid">

            <?php $__currentLoopData = $filtered; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php

                    /*
                    |--------------------------------------------------------------------------
                    | REAL STATUS
                    |--------------------------------------------------------------------------
                    */

                    $status =
                        $project->pivot->assignment_status
                        ?? 'assigned';

                    $score =
                        $project->pivot->score
                        ?? null;

                    /*
                    |--------------------------------------------------------------------------
                    | DO NOT OVERRIDE GRADED
                    |--------------------------------------------------------------------------
                    */

                    if($status !== 'graded'){

                        $groupSubmitted = false;

                        if($project->group_id){

                            $groupStudentIds =
                                \DB::table('group_student')
                                    ->where(
                                        'group_id',
                                        $project->group_id
                                    )
                                    ->pluck('student_id');

                            $groupSubmitted =
                                \App\Models\ProjectSubmission::where(
                                    'project_id',
                                    $project->id
                                )
                                ->whereIn(
                                    'student_id',
                                    $groupStudentIds
                                )
                                ->whereIn('status', [
                                    'submitted',
                                    'graded',
                                    'reviewed'
                                ])
                                ->exists();
                        }

                        if($groupSubmitted){
                            $status = 'submitted';
                        }
                    }

                    $dueDate =
                        \Carbon\Carbon::parse(
                            $project->due_date
                        );

                    $isOverdue =
                        $dueDate->isPast()
                        && $status === 'assigned';

                ?>

                <div class="project-card">

                    <div class="project-card-header">

                        <div>

                            <div class="project-title">
                                <?php echo e($project->title); ?>

                            </div>

                            <div class="project-teacher">

                                by
                                <?php echo e($project->teacher->name ?? '—'); ?>


                            </div>

                        </div>

                        <span class="status-badge <?php echo e($status); ?>">

                            <?php if($status === 'graded'): ?>

                                ✅ Graded

                            <?php elseif($status === 'submitted'): ?>

                                📤 Submitted

                            <?php else: ?>

                                ⏳ Pending

                            <?php endif; ?>

                        </span>

                    </div>

                    <?php if($project->description): ?>

                        <div class="project-desc">

                            <?php echo e(\Illuminate\Support\Str::limit(
                                $project->description,
                                100
                            )); ?>


                        </div>

                    <?php endif; ?>

                    <div class="project-meta-row">

                        <div class="meta-item">

                            <span class="meta-label">
                                Due Date
                            </span>

                            <span class="meta-val <?php echo e($isOverdue ? 'due-overdue' : ''); ?>">

                                <?php echo e($dueDate->format('M d, Y')); ?>


                                <?php if($isOverdue): ?>
                                    ⚠️
                                <?php endif; ?>

                            </span>

                        </div>

                        <div class="meta-item">

                            <span class="meta-label">
                                Max Score
                            </span>

                            <span class="meta-val">
                                <?php echo e($project->max_score); ?>

                            </span>

                        </div>

                        <?php if($status === 'graded' && $score !== null): ?>

                            <div class="meta-item">

                                <span class="meta-label">
                                    Your Score
                                </span>

                                <span class="score-chip">

                                    <?php echo e($score); ?>/<?php echo e($project->max_score); ?>


                                </span>

                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="project-actions">

                        <a href="<?php echo e(route('student.projects.show', $project->id)); ?>"
                           class="btn-view">

                            👁 View

                        </a>

                        <?php if($status === 'assigned'): ?>

                            <a href="<?php echo e(route('student.projects.submit', $project->id)); ?>"
                               class="btn-submit">

                                📤 Submit

                            </a>

                        <?php elseif($status === 'graded'): ?>

                            <div class="btn-submit"
                                 style="background:#16a34a;cursor:default;pointer-events:none;">

                                ✅ Graded

                            </div>

                        <?php else: ?>

                            <div class="btn-submit"
                                 style="background:#7c3aed;cursor:default;pointer-events:none;">

                                📤 Submitted

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

    <?php endif; ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\BROKEN_SYSTEM\resources\views/student/projects/index.blade.php ENDPATH**/ ?>