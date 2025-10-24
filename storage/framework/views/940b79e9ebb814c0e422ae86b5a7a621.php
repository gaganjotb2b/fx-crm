<!--
=========================================================
* Soft UI Dashboard PRO - v1.0.8
=========================================================

* Product Page:  https://www.creative-tim.com/product/soft-ui-dashboard-pro 
* Copyright 2022 Creative Tim (https://www.creative-tim.com)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(get_favicon_icon()); ?>">
    <link rel="icon" type="image/png" href="<?php echo e(get_favicon_icon()); ?>">
    <title>
        <?php echo e(strtoupper(config('app.name'))); ?> - <?php echo $__env->yieldContent('title'); ?>
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="<?php echo e(asset('trader-assets/assets/css/nucleo-icons.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('trader-assets/assets/css/nucleo-svg.css')); ?>" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('/admin-assets/app-assets/vendors/css/extensions/toastr.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/plugins/extensions/ext-component-toastr.css')); ?>">
    <link id="pagestyle" href="<?php echo e(asset('/trader-assets/assets/css/soft-ui-dashboard.css?v=1.0.8')); ?>" rel="stylesheet" />
    
    
    <style>
        .loc-logo {
            max-width: 178px;
        }

        @media (min-width: 992px) {
            .mt-lg-n10 {
                margin-top: -15rem !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 dark-version" id="sidenav-scrollbar">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3  navbar-transparent mt-4">
        <div class="container d-flex justify-content-around">
            <img class="loc-logo" src="<?php echo e(get_user_logo()); ?>" alt="<?php echo e(config('app.name')); ?>">
        </div>
    </nav>
    <!-- End Navbar -->
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('../../../assets/img/curved-images/curved8.jpg');">
            <span class="mask bg-gradient-dark opacity-6"></span>
        </div>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </div>
    </main>
    <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <?php echo $__env->make('layouts.login-footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <!--   Core JS Files   -->
    <script src="<?php echo e(asset('/trader-assets/assets/js/core/jquery-3.6.0.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/core/popper.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/core/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/perfect-scrollbar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/smooth-scrollbar.min.js')); ?>"></script>
    <!-- Kanban scripts -->
    <script src="<?php echo e(asset('/trader-assets/assets/js/plugins/dragula/dragula.min.js')); ?>"></script>
    <script src="<?php echo e(asset('/trader-assets/assets/js/plugins/jkanban/jkanban.js')); ?>"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="<?php echo e(asset('/trader-assets/assets/js/soft-ui-dashboard.min.js?v=1.0.8')); ?>"></script>
    <script src="<?php echo e(asset('common-js/enter-key-handler.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/src/js/core/confirm-alert.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/pages/common-ajax.js')); ?>"></script>
    <?php echo $__env->yieldContent('page-js'); ?>
    <script>
        // trigger login when press enter
        document.onkeydown = function(evt) {
            var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
            if (keyCode == 13) {
                $('#lockscreenBtn').trigger('click');
            }
        }
    </script>
</body>

</html><?php /**PATH I:\code\fxcrm\fx\crm-new-laravel\resources\views/layouts/lock-layout.blade.php ENDPATH**/ ?>