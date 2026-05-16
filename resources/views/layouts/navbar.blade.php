<link rel="stylesheet" href="{{ asset('assets/css/pages/layout-navbar.css') }}">
<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme custom-navbar"
     id="layout-navbar">
<!-- Mobile Menu -->
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4"
           href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center ms-auto"
         id="navbar-collapse">

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <!-- Theme Toggle -->
            <li class="nav-item">
                <a class="nav-link theme-toggle hide-arrow"
                   href="javascript:void(0);">

                    <i class="bx bx-sun bx-sm"></i>

                </a>
            </li>

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">

                <a class="nav-link dropdown-toggle hide-arrow p-0"
                   href="javascript:void(0);"
                   data-bs-toggle="dropdown">

                    <!-- Animated Face -->
                    <div class="avatar-face">

                        <div class="face">

                            <div class="eye left"></div>

                            <div class="eye right"></div>

                            <div class="mouth"></div>

                        </div>

                    </div>

                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    @auth

                    <!-- User Info -->
                    <li>

                        <a class="dropdown-item"
                           href="javascript:void(0);">

                            <div class="profile-box">

                                <div class="avatar-face">

                                    <div class="face">

                                        <div class="eye left"></div>

                                        <div class="eye right"></div>

                                        <div class="mouth"></div>

                                    </div>

                                </div>

                                <div class="profile-info">

                                    <h6>
                                        {{ Auth::user()->name }}
                                    </h6>

                                    <small>
                                        {{ ucfirst(Auth::user()->role) }}
                                    </small>

                                </div>

                            </div>

                        </a>

                    </li>

                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>

                    <!-- Profile -->
                    <li>

                        <a class="dropdown-item"
                           href="#">

                            <i class="bx bx-user me-2 menu-icon"></i>

                            <span class="align-middle">
                                My Profile
                            </span>

                        </a>

                    </li>

                    <!-- Settings -->
                    <li>

                        <a class="dropdown-item"
                           href="#">

                            <i class="bx bx-cog me-2 menu-icon"></i>

                            <span class="align-middle">
                                Settings
                            </span>

                        </a>

                    </li>

                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>

                    <!-- Logout -->
                    <li>

                        <form method="POST"
                              action="{{ Auth::user()->role === 'teacher'
                                    ? route('teacher.logout')
                                    : route('student.logout') }}"
                              id="navbar-logout-form">

                            @csrf

                            <a class="dropdown-item logout-btn"
                               href="#"
                               onclick="event.preventDefault();
                               document.getElementById('navbar-logout-form').submit();">

                                <i class="bx bx-power-off me-2 text-danger"></i>

                                <span class="align-middle text-danger">
                                    Log Out
                                </span>

                            </a>

                        </form>

                    </li>

                    @else

                    <!-- Login -->
                    <li>

                        <a class="dropdown-item"
                           href="{{ route('login') }}">

                            <i class="bx bx-log-in me-2"></i>

                            Login

                        </a>

                    </li>

                    <!-- Register Teacher -->
                    <li>

                        <a class="dropdown-item"
                           href="{{ route('teacher.register') }}">

                            <i class="bx bx-user-plus me-2"></i>

                            Register as Teacher

                        </a>

                    </li>

                    <!-- Register Student -->
                    <li>

                        <a class="dropdown-item"
                           href="{{ route('student.register') }}">

                            <i class="bx bx-user-plus me-2"></i>

                            Register as Student

                        </a>

                    </li>

                    @endauth

                </ul>

            </li>

        </ul>

    </div>

</nav>