<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/student-dashboard.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="student-dashboard">

    <?php
        $gradedProjects = $assignedProjects->filter(function($project){
            return $project->pivot->assignment_status === 'graded';
        });

        // Use real recentSubmissions from controller (not reassigned)
        $highestScore = $gradedProjects->max(function($project){
            return $project->pivot->score ?? 0;
        });

        $lowestScore = $gradedProjects->min(function($project){
            return $project->pivot->score ?? 0;
        });
    ?>

    <!-- HERO -->
    <div class="hero">

        <div class="hero-top">

            <div>

                <div class="hero-title">
                    Welcome back, <?php echo e(Auth::user()->name); ?> 👋
                </div>

                <div class="hero-sub">
                    <?php echo e(Auth::user()->student_id ?? 'Student ID'); ?>

                    •
                    <?php echo e(Auth::user()->department ?? 'Student Dashboard'); ?>

                </div>

            </div>

            <div class="student-badge">
                🎓 Student Portal
            </div>

        </div>

        <!-- STATS -->
        <div class="stats-grid">

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        📂
                    </div>

                </div>

                <div class="stat-value">
                    <?php echo e($totalProjects); ?>

                </div>

                <div class="stat-label">
                    Total Projects
                </div>

            </div>

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        ⏳
                    </div>

                </div>

                <div class="stat-value">
                    <?php echo e($pendingCount); ?>

                </div>

                <div class="stat-label">
                    Pending Tasks
                </div>

            </div>

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        ✅
                    </div>

                </div>

                <div class="stat-value">
                    <?php echo e($submittedCount); ?>

                </div>

                <div class="stat-label">
                    Submitted
                </div>

            </div>

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        ⭐
                    </div>

                </div>

                <div class="stat-value">
                    <?php echo e($averageScore ?? 0); ?>%
                </div>

                <div class="stat-label">
                    Average Score
                </div>

            </div>

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        🏫
                    </div>

                </div>

                <div class="stat-value">
                    <?php echo e($sections->count()); ?>

                </div>

                <div class="stat-label">
                    My Sections
                </div>

            </div>

        </div>

    </div>

    <div class="dashboard-grid">

        <!-- LEFT -->
        <div>

            <!-- PROJECTS -->
            <div class="panel">

                <div class="panel-header">

                    <div>

                        <div class="panel-title">
                            📚 Assigned Projects
                        </div>

                        <div class="panel-sub">
                            Manage and submit your active school projects
                        </div>

                    </div>

                </div>

                <?php if($assignedProjects->isEmpty()): ?>

                    <div class="empty">
                        No assigned projects yet
                    </div>

                <?php else: ?>

                    <div class="project-table-wrap">

                        <table class="project-table">

                            <thead>

                                <tr>
                                    <th>Project</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Score</th>
                                    <th>Actions</th>
                                </tr>

                            </thead>

                            <tbody>

                            <?php $__currentLoopData = $assignedProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php
                                    $pivotStatus = $project->pivot->assignment_status ?? 'assigned';
                                    $score = $project->pivot->score;
                                ?>

                                <tr class="project-row">

                                    <td>

                                        <div class="project-name">
                                            <?php echo e($project->title); ?>

                                        </div>

                                        <div class="project-teacher">
                                            <?php echo e($project->teacher->name ?? 'Teacher'); ?>

                                        </div>

                                    </td>

                                    <td>

                                        <?php echo e($project->due_date
                                            ? \Carbon\Carbon::parse($project->due_date)->format('M d, Y')
                                            : 'No deadline'); ?>


                                    </td>

                                    <td>

                                        <?php if($pivotStatus === 'graded'): ?>

                                            <span class="status graded">
                                                ✅ Graded
                                            </span>

                                        <?php elseif($pivotStatus === 'submitted'): ?>

                                            <span class="status submitted">
                                                📤 Submitted
                                            </span>

                                        <?php else: ?>

                                            <span class="status pending">
                                                ⏳ Pending
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <span class="score-pill">
                                            <?php echo e($score ?? '—'); ?>

                                            /
                                            <?php echo e($project->max_score); ?>

                                        </span>

                                    </td>

                                    <td>

                                        <div class="actions">

                                            <a href="<?php echo e(route('student.projects.show',$project->id)); ?>"
                                               class="btn btn-dark">

                                                👁 View

                                            </a>

                                            <?php if($pivotStatus !== 'graded'): ?>

                                                <a href="<?php echo e(route('student.projects.submit',$project->id)); ?>"
                                                   class="btn btn-primary">

                                                    🚀 Submit

                                                </a>

                                            <?php endif; ?>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>

                        </table>

                    </div>

                <?php endif; ?>

            </div>

            <!-- GROUPS -->
            <div class="panel">

                <div class="panel-header">

                    <div>

                        <div class="panel-title">
                            👥 My Groups
                        </div>

                        <div class="panel-sub">
                            Your joined academic groups and classes
                        </div>

                    </div>

                </div>

                <div style="margin-bottom:1rem;display:flex;gap:.75rem;flex-wrap:wrap;">
                    <a href="<?php echo e(route('student.teacher.join')); ?>"
                       style="display:inline-flex;align-items:center;gap:6px;
                              padding:.65rem 1.1rem;background:#7c3aed;color:white;
                              border-radius:10px;text-decoration:none;font-size:.82rem;
                              font-weight:700;">
                        🎓 Enter Teacher Code
                    </a>
                    <a href="<?php echo e(route('student.groups.join')); ?>"
                       style="display:inline-flex;align-items:center;gap:6px;
                              padding:.65rem 1.1rem;background:#4f46e5;color:white;
                              border-radius:10px;text-decoration:none;font-size:.82rem;
                              font-weight:700;">
                        🔑 Join a Group
                    </a>
                    <a href="<?php echo e(route('student.sections.join')); ?>"
                       style="display:inline-flex;align-items:center;gap:6px;
                              padding:.65rem 1.1rem;background:#0d9488;color:white;
                              border-radius:10px;text-decoration:none;font-size:.82rem;
                              font-weight:700;">
                        🏫 Join a Section
                    </a>
                </div>

                <?php if($groups->isEmpty()): ?>

                    <div class="empty">
                        No groups joined yet.<br>
                        <span style="font-size:.82rem;color:#9ca3af;">
                            Ask your teacher for a join code.
                        </span>
                    </div>

                <?php else: ?>

                    <div class="group-list">

                        <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <div class="group-item">

                                <div>

                                    <div class="group-name">
                                        <?php echo e($group->name); ?>

                                    </div>

                                    <div class="group-teacher">
                                        <?php echo e($group->teacher->name ?? 'Teacher'); ?>

                                    </div>

                                </div>

                                <a href="<?php echo e(route('student.groups.show',$group->id)); ?>"
                                   class="btn btn-primary">

                                    👁 Open

                                </a>

                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>

                <?php endif; ?>

            </div>

        </div>

            <!-- SECTIONS -->
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">🏫 My Sections</div>
                        <div class="panel-sub">Class sections you are enrolled in</div>
                    </div>
                    <a href="<?php echo e(route('student.sections.join')); ?>"
                       style="display:inline-flex;align-items:center;gap:5px;
                              padding:.5rem 1rem;background:#0d9488;color:white;
                              border-radius:9px;text-decoration:none;font-size:.8rem;font-weight:700;">
                        ＋ Join Section
                    </a>
                </div>

                <?php if($sections->isEmpty()): ?>
                    <div class="empty">
                        Not enrolled in any section yet.<br>
                        <span style="font-size:.82rem;color:#9ca3af;">Ask your teacher for a section code.</span>
                    </div>
                <?php else: ?>
                    <div class="group-list">
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="group-item">
                                <div>
                                    <div class="group-name"><?php echo e($section->name); ?></div>
                                    <div class="group-teacher">
                                        👩‍🏫 <?php echo e($section->teacher->name ?? 'Teacher'); ?>

                                        <?php if($section->school_year): ?> &nbsp;·&nbsp; <?php echo e($section->school_year); ?> <?php endif; ?>
                                        <?php if($section->semester): ?> &nbsp;·&nbsp; <?php echo e($section->semester); ?> <?php endif; ?>
                                    </div>
                                </div>
                                <span style="padding:.3rem .75rem;background:#ccfbf1;color:#0f766e;
                                             border-radius:999px;font-size:.75rem;font-weight:700;">
                                    ✅ Enrolled
                                </span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- RIGHT -->
        <div>

            <!-- SUMMARY -->
            <div class="panel">

                <div class="panel-header">

                    <div>

                        <div class="panel-title">
                            📊 Performance
                        </div>

                        <div class="panel-sub">
                            Academic overview and score insights
                        </div>

                    </div>

                </div>

                <div class="summary-wrap">

                    <div class="summary-box">

                        <div class="summary-label">
                            Average Score
                        </div>

                        <div class="summary-value">
                            <?php echo e($averageScore ?? 0); ?>%
                        </div>

                    </div>

                    <div class="summary-box">

                        <div class="summary-label">
                            Highest Score
                        </div>

                        <div class="summary-value">
                            <?php echo e($highestScore ?? 0); ?>

                        </div>

                    </div>

                    <div class="summary-box">

                        <div class="summary-label">
                            Lowest Score
                        </div>

                        <div class="summary-value">
                            <?php echo e($lowestScore ?? 0); ?>

                        </div>

                    </div>

                </div>

            </div>

            <!-- RECENT -->
            <div class="panel">

                <div class="panel-header">

                    <div>

                        <div class="panel-title">
                            📤 Recent Activity
                        </div>

                        <div class="panel-sub">
                            Latest submissions and grading updates
                        </div>

                    </div>

                </div>

                <?php if($recentSubmissions->isEmpty()): ?>

                    <div class="empty">
                        No recent activity
                    </div>

                <?php else: ?>

                    <div class="recent-wrap">

                        <?php $__currentLoopData = $recentSubmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <div class="recent-item">

                                <div class="recent-title">
                                    <?php echo e($submission->project->title ?? 'Project'); ?>

                                </div>

                                <div class="recent-meta">
                                    <?php echo e($submission->project->teacher->name ?? 'Teacher'); ?>

                                    •
                                    <?php echo e($submission->created_at->diffForHumans()); ?>

                                </div>

                                <?php if($submission->status === 'graded' && $submission->score !== null): ?>
                                    <div class="recent-grade">
                                        ⭐ <?php echo e($submission->score); ?> / <?php echo e($submission->project->max_score ?? '—'); ?>

                                    </div>
                                <?php else: ?>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:4px;">
                                        <?php echo e(ucfirst($submission->status)); ?>

                                    </div>
                                <?php endif; ?>

                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/student/dashboard.blade.php ENDPATH**/ ?>