<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/teacher-dashboard.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="pms-dash">

    
    <div class="dash-header">
        <div class="dash-greeting">
            <h2>Good day, <?php echo e(Auth::user()->name); ?> 👋</h2>
            <p>Here's what's happening in your classroom</p>
        </div>
        <div class="header-actions">
            <span class="role-pill">🎓 Teacher</span>
            <span style="background:#eef2ff;border:1px solid #c7d2fe;padding:.4rem .9rem;
                         border-radius:999px;font-size:.75rem;font-weight:700;
                         letter-spacing:.15em;color:#4f46e5;font-family:monospace;
                         cursor:pointer;" onclick="copyTeacherCode()" title="Click to copy">
                🔑 <?php echo e(Auth::user()->teacher_code ?? 'N/A'); ?>

            </span>
            <a href="<?php echo e(route('teacher.students.index')); ?>" class="btn-outline">🧑‍🎓 My Students</a>
            <a href="<?php echo e(route('teacher.groups.create')); ?>" class="btn-outline">＋ New Group</a>
            <a href="<?php echo e(route('teacher.projects.create')); ?>" class="btn-primary">＋ New Project</a>
        </div>
    </div>

    
    <div class="stats-row">

        <div class="stat-card">
            📁 <strong><?php echo e($totalProjects); ?></strong> Projects
        </div>

        <div class="stat-card">
            👥 <strong><?php echo e($totalGroups); ?></strong> Groups
        </div>

        <div class="stat-card">
            🧑‍🎓 <strong><?php echo e($totalStudents); ?></strong> Students
        </div>

        <div class="stat-card">
            ⏳ <strong><?php echo e($pendingGrades); ?></strong> Pending Grades
        </div>

    </div>

    <div style="display:grid;grid-template-columns:1fr 360px;gap:1.5rem;">

        
        <div>

            
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">📂 Your Projects</div>
                    <a href="<?php echo e(route('teacher.projects.index')); ?>" class="panel-action">View all →</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Group</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($project->title); ?></strong></td>
                                <td><?php echo e($project->group->name ?? '—'); ?></td>
                                <td><?php echo e(ucfirst($project->status)); ?></td>
                                <td>
                                    <a href="<?php echo e(route('teacher.projects.show', $project->id)); ?>" class="action-btn">View</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="4">No projects yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">✅ Recently Graded</div>
                    <a href="<?php echo e(route('teacher.graded.index')); ?>" class="panel-action">View all →</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Project</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentlyGraded; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($submission->student->name ?? 'Unknown'); ?></td>
                                <td><?php echo e($submission->project->title ?? 'Project'); ?></td>
                                <td><span class="badge badge-success">Graded</span></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3">No graded submissions yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">👥 Your Groups</div>
                    <a href="<?php echo e(route('teacher.groups.index')); ?>" class="panel-action">Manage →</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Students</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($group->name); ?></td>
                                <td><?php echo e($group->students_count); ?></td>
                                <td>
                                    <a href="<?php echo e(route('teacher.groups.show', $group->id)); ?>" class="action-btn">View</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3">No groups yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

        
        <div>
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">🧑‍🎓 Students</div>
                </div>
                <?php $__empty_1 = true; $__currentLoopData = $students->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div style="padding:1rem;border-top:1px solid #f3f4f6;">
                        <?php echo e($student->name); ?>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div style="padding:1.5rem;color:#9ca3af;text-align:center;">No students yet.</div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/js/pages/teacher-dashboard.js')); ?>"></script>
<script>
function copyTeacherCode() {
    var code = '<?php echo e(Auth::user()->teacher_code ?? ""); ?>';
    navigator.clipboard.writeText(code).then(function() {
        alert('Teacher code ' + code + ' copied!');
    }).catch(function() {
        // Fallback for browsers that don't support clipboard API
        var el = document.createElement('textarea');
        el.value = code;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        alert('Teacher code ' + code + ' copied!');
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/teacher/dashboard.blade.php ENDPATH**/ ?>