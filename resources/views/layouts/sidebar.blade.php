<link rel="stylesheet" href="{{ asset('assets/css/pages/layout-sidebar.css') }}">
{{-- resources/views/layouts/sidebar.blade.php --}}

<aside id="layout-menu"
       class="layout-menu menu-vertical menu bg-menu-theme">

@auth
<!-- BRAND -->
<div class="app-brand">

    <a href="{{ auth()->user()->role === 'teacher'
        ? route('teacher.dashboard')
        : route('student.dashboard') }}"
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
        {{ request()->routeIs('teacher.dashboard')
            || request()->routeIs('student.dashboard')
            ? 'active' : '' }}">

        <a href="{{ auth()->user()->role === 'teacher'
            ? route('teacher.dashboard')
            : route('student.dashboard') }}"
           class="menu-link">

            <i class="menu-icon tf-icons bx bx-home-alt"></i>

            <div>
                Dashboard
            </div>

        </a>

    </li>

    {{-- ====================================================== --}}
    {{-- TEACHER --}}
    {{-- ====================================================== --}}
    @if(auth()->user()->role === 'teacher')

        <li class="menu-section">
            Teacher Panel
        </li>

        <!-- PROJECTS -->
        <li class="menu-item
            {{ request()->routeIs('teacher.projects.*')
                ? 'active' : '' }}">

            <a href="{{ route('teacher.projects.index') }}"
               class="menu-link">

                <i class="menu-icon tf-icons bx bx-folder"></i>

                <div>
                    Projects
                </div>

            </a>

        </li>

        <!-- GROUPS -->
        <li class="menu-item
            {{ request()->routeIs('teacher.groups.*')
                ? 'active' : '' }}">

            <a href="{{ route('teacher.groups.index') }}"
               class="menu-link">

                <i class="menu-icon tf-icons bx bx-group"></i>

                <div>
                    Groups
                </div>

            </a>

        </li>

        <!-- SECTIONS -->
        <li class="menu-item
            {{ request()->routeIs('teacher.sections.*')
                ? 'active' : '' }}">

            <a href="{{ route('teacher.sections.index') }}"
               class="menu-link">

                <i class="menu-icon tf-icons bx bx-chalkboard"></i>

                <div>
                    Sections
                </div>

            </a>

        </li>

        <!-- GRADED -->
        <li class="menu-item
            {{ request()->routeIs('teacher.graded.index')
                ? 'active' : '' }}">

            <a href="{{ route('teacher.graded.index') }}"
               class="menu-link">

                <i class="menu-icon tf-icons bx bx-check-circle"></i>

                <div>
                    Graded Projects
                </div>

                @php

                    $gradedCount = \App\Models\ProjectSubmission::where('status', 'reviewed')
                        ->whereHas('project', function ($query) {
                            $query->where('teacher_id', auth()->id());
                        })
                        ->count();

                @endphp

                @if($gradedCount > 0)

                    <span class="menu-badge">
                        {{ $gradedCount }}
                    </span>

                @endif

            </a>

        </li>

    @endif

    {{-- ====================================================== --}}
    {{-- STUDENT --}}
    {{-- ====================================================== --}}
    @if(auth()->user()->role === 'student')

        <li class="menu-section">
            Student Panel
        </li>

        <!-- MY PROJECTS -->
        <li class="menu-item
            {{ request()->routeIs('student.projects.*')
                ? 'active' : '' }}">

            <a href="{{ route('student.projects.index') }}"
               class="menu-link">

                <i class="menu-icon tf-icons bx bx-folder-open"></i>

                <div>
                    My Projects
                </div>

            </a>

        </li>

        <!-- MY GRADES -->
        <li class="menu-item
            {{ request()->routeIs('student.grades')
                ? 'active' : '' }}">

            <a href="{{ route('student.grades') }}"
               class="menu-link">

                <i class="menu-icon tf-icons bx bx-award"></i>

                <div>
                    My Grades
                </div>

            </a>

        </li>

        <!-- MY SECTIONS (student) -->
        @if(auth()->user()->isStudent())
        <li class="menu-item
            {{ request()->routeIs('student.sections.*')
                ? 'active' : '' }}">

            <a href="{{ route('student.sections.join') }}"
               class="menu-link">

                <i class="menu-icon tf-icons bx bx-chalkboard"></i>

                <div>
                    My Sections
                </div>

            </a>

        </li>
        @endif

    @endif

    <!-- LOGOUT -->
    <li class="menu-section">
        Account
    </li>

    <li class="menu-item">

        <form method="POST"
              action="{{ auth()->user()->role === 'teacher'
                    ? route('teacher.logout')
                    : route('student.logout') }}">

            @csrf

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

@endauth

</aside>