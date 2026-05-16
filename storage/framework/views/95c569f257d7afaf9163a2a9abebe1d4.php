<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/layout-sidebar.css')); ?>">


<aside id="layout-menu"
       class="layout-menu menu-vertical menu bg-menu-theme">

<?php if(auth()->guard()->check()): ?>
<!-- BRAND -->
<div class="app-brand">

    <a href="<?php echo e(auth()->user()->role === 'teacher'
        ? route('teacher.dashboard')
        : route('student.dashboard')); ?>"
       class="app-brand-link text-decoration-none">

        <div class="brand-box">

            <div class="brand-logo">
                P
            </div>

            <div class="brand-text">

                <span class="brand-title">
                    PMS
                </span>

                <span class="brand-sub">
                    Project Portal
                </span>

            </div>

        </div>

    </a>

</div>

<ul class="menu-inner py-1">

    <!-- DASHBOARD -->
    <li class="menu-item
        <?php echo e(request()->routeIs('teacher.dashboard')
            || request()->routeIs('student.dashboard')
            ? 'active' : ''); ?>">

        <a href="<?php echo e(auth()->user()->role === 'teacher'
            ? route('teacher.dashboard')
            : route('student.dashboard')); ?>"
           class="menu-link">

            <i class="menu-icon tf-icons bx bx-home-alt"></i>

            <div>
                Dashboard
            </div>

        </a>

    </li>

    
    
    
    <?php if(auth()->user()->role === 'teacher'): ?>

        <li class="menu-section">
            Teacher Panel
        </li>

        <!-- PROJECTS -->
        <li class="menu-item
            <?php echo e(request()->routeIs('teacher.projects.*')
                ? 'active' : ''); ?>">

            <a href="<?php echo e(route('teacher.projects.index')); ?>"
               class="menu-link">

                <i class="menu-icon tf-icons bx bx-folder"></i>

                <div>
                    Projects
                </div>

            </a>

        </li>

        <!-- GROUPS -->
        <li class="menu-item
            <?php echo e(request()->routeIs('teacher.groups.*')
                ? 'active' : ''); ?>">

            <a href="<?php echo e(route('teacher.groups.index')); ?>"
               class="menu-link">

                <i class="menu-icon tf-icons bx bx-group"></i>

                <div>
                    Groups
                </div>

            </a>

        </li>

        <!-- GRADED -->
        <li class="menu-item
            <?php echo e(request()->routeIs('teacher.graded.index')
                ? 'active' : ''); ?>">

            <a href="<?php echo e(route('teacher.graded.index')); ?>"
               class="menu-link">

                <i class="menu-icon tf-icons bx bx-check-circle"></i>

                <div>
                    Graded Projects
                </div>

                <?php

                    $gradedCount = \App\Models\ProjectSubmission::where('status', 'reviewed')
                        ->whereHas('project', function ($query) {
                            $query->where('teacher_id', auth()->id());
                        })
                        ->count();

                ?>

                <?php if($gradedCount > 0): ?>

                    <span class="menu-badge">
                        <?php echo e($gradedCount); ?>

                    </span>

                <?php endif; ?>

            </a>

        </li>

    <?php endif; ?>

    
    
    
    <?php if(auth()->user()->role === 'student'): ?>

        <li class="menu-section">
            Student Panel
        </li>

        <!-- MY PROJECTS -->
        <li class="menu-item
            <?php echo e(request()->routeIs('student.projects.*')
                ? 'active' : ''); ?>">

            <a href="<?php echo e(route('student.projects.index')); ?>"
               class="menu-link">

                <i class="menu-icon tf-icons bx bx-folder-open"></i>

                <div>
                    My Projects
                </div>

            </a>

        </li>

        <!-- MY GRADES -->
        <li class="menu-item
            <?php echo e(request()->routeIs('student.grades')
                ? 'active' : ''); ?>">

            <a href="<?php echo e(route('student.grades')); ?>"
               class="menu-link">

                <i class="menu-icon tf-icons bx bx-award"></i>

                <div>
                    My Grades
                </div>

            </a>

        </li>

    <?php endif; ?>

    <!-- LOGOUT -->
    <li class="menu-section">
        Account
    </li>

    <li class="menu-item">

        <form method="POST"
              action="<?php echo e(auth()->user()->role === 'teacher'
                    ? route('teacher.logout')
                    : route('student.logout')); ?>">

            <?php echo csrf_field(); ?>

            <a href="#"
               class="menu-link logout-link"
               onclick="event.preventDefault();
                        this.closest('form').submit();">

                <i class="menu-icon tf-icons bx bx-log-out"></i>

                <div>
                    Logout
                </div>

            </a>

        </form>

    </li>

</ul>

<?php endif; ?>

</aside><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>