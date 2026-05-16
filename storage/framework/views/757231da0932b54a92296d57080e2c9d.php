<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?php echo e(asset('assets/')); ?>"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title><?php echo $__env->yieldContent('title', 'PMS'); ?> &mdash; PORTAL</title>

    <meta name="description" content="<?php echo $__env->yieldContent('description', ''); ?>" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('assets/img/favicon/favicon.ico')); ?>" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/fonts/boxicons.css')); ?>" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/css/core.css')); ?>" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/css/theme-default.css')); ?>" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/demo.css')); ?>" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')); ?>" />

    <!-- PMS Components CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/pms-toast.css')); ?>" />

    <!-- Helpers -->
    <script src="<?php echo e(asset('assets/vendor/js/helpers.js')); ?>"></script>

    <!-- Config -->
    <script src="<?php echo e(asset('assets/js/config.js')); ?>"></script>

    <?php echo $__env->yieldContent('styles'); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <!-- Sidebar / Menu -->
        <?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Layout page -->
        <div class="layout-page">

          <!-- Navbar -->
          <?php echo $__env->make('layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

          <!-- Content wrapper -->
          <div class="content-wrapper">

            <!-- Flash Messages / Toast Notifications -->
            <div class="container-xxl pt-3">

              <?php if(session('success')): ?>
                <div class="pms-toast pms-toast-success">
                  <span class="pms-toast-icon">✅</span>
                  <span><?php echo e(session('success')); ?></span>
                  <button onclick="this.parentElement.remove()" class="pms-toast-close">×</button>
                </div>
              <?php endif; ?>

              <?php if(session('error')): ?>
                <div class="pms-toast pms-toast-error">
                  <span class="pms-toast-icon">❌</span>
                  <span><?php echo e(session('error')); ?></span>
                  <button onclick="this.parentElement.remove()" class="pms-toast-close">×</button>
                </div>
              <?php endif; ?>

              <?php if(session('saved')): ?>
                <div class="pms-toast pms-toast-info">
                  <span class="pms-toast-icon">💾</span>
                  <span><?php echo e(session('saved')); ?></span>
                  <button onclick="this.parentElement.remove()" class="pms-toast-close">×</button>
                </div>
              <?php endif; ?>

              <?php if(session('warning')): ?>
                <div class="pms-toast pms-toast-warning">
                  <span class="pms-toast-icon">⚠️</span>
                  <span><?php echo e(session('warning')); ?></span>
                  <button onclick="this.parentElement.remove()" class="pms-toast-close">×</button>
                </div>
              <?php endif; ?>

            </div>
            <!-- /Flash Messages -->

            <!-- Page Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <?php echo $__env->yieldContent('content'); ?>
            </div>
            <!-- /Page Content -->

            <!-- Footer -->
            <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
    <script src="<?php echo e(asset('assets/vendor/libs/jquery/jquery.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/libs/popper/popper.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/js/bootstrap.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/js/menu.js')); ?>"></script>

    <!-- Main JS -->
    <script src="<?php echo e(asset('assets/js/main.js')); ?>"></script>

    <!-- PMS Components JS -->
    <script src="<?php echo e(asset('assets/js/pms-toast.js')); ?>"></script>

    <?php echo $__env->yieldContent('scripts'); ?>
  </body>
</html>
<?php /**PATH C:\wamp64\www\BROKEN_SYSTEM\resources\views/layouts/app.blade.php ENDPATH**/ ?>