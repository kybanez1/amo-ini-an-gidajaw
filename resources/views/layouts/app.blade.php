<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets/') }}"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>@yield('title', 'PMS') &mdash; PORTAL</title>

    <meta name="description" content="@yield('description', '')" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- PMS Components CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/pms-toast.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!-- Config -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    @yield('styles')
    @stack('styles')
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <!-- Sidebar / Menu -->
        @include('layouts.sidebar')

        <!-- Layout page -->
        <div class="layout-page">

          <!-- Navbar -->
          @include('layouts.navbar')

          <!-- Content wrapper -->
          <div class="content-wrapper">

            <!-- Flash Messages / Toast Notifications -->
            <div class="container-xxl pt-3">

              @if (session('success'))
                <div class="pms-toast pms-toast-success">
                  <span class="pms-toast-icon">✅</span>
                  <span>{{ session('success') }}</span>
                  <button onclick="this.parentElement.remove()" class="pms-toast-close">×</button>
                </div>
              @endif

              @if (session('error'))
                <div class="pms-toast pms-toast-error">
                  <span class="pms-toast-icon">❌</span>
                  <span>{{ session('error') }}</span>
                  <button onclick="this.parentElement.remove()" class="pms-toast-close">×</button>
                </div>
              @endif

              @if (session('saved'))
                <div class="pms-toast pms-toast-info">
                  <span class="pms-toast-icon">💾</span>
                  <span>{{ session('saved') }}</span>
                  <button onclick="this.parentElement.remove()" class="pms-toast-close">×</button>
                </div>
              @endif

              @if (session('warning'))
                <div class="pms-toast pms-toast-warning">
                  <span class="pms-toast-icon">⚠️</span>
                  <span>{{ session('warning') }}</span>
                  <button onclick="this.parentElement.remove()" class="pms-toast-close">×</button>
                </div>
              @endif

            </div>
            <!-- /Flash Messages -->

            <!-- Page Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              @yield('content')
            </div>
            <!-- /Page Content -->

            <!-- Footer -->
            @include('layouts.footer')
            <!-- /Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- /Content wrapper -->

        </div>
        <!-- /Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- /Layout wrapper -->

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- PMS Components JS -->
    <script src="{{ asset('assets/js/pms-toast.js') }}"></script>

    @yield('scripts')
  </body>
</html>
