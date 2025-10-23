<!DOCTYPE html>
<html class="loading <?php echo e(get_admin_theme()); ?>" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">

    <meta name="description" content="<?php echo e(get_company_name()); ?> is a broker company focuses in Forex Trading. We believe in transparency, accountability, and accuracy of services. Experience trading in the most seamless way, straight to global market, and the easiness of withdrawal.">
    <meta name="keywords" content="<?php echo e(get_company_name()); ?> is operated by <?php echo e(get_company_name()); ?> and has registered in Saint Vincent & the Grenadines with LLC number 892 LLC 2021, regulated by the Financial Services Authority (FSA) of Saint Vincent and the Grenadines. High Risk Warning : Before you enter foreign exchange and stock markets, you have to remember that trading currencies and other investment products is trading in nature and always involves a considerable risk. As a result of various financial fluctuations, you may not only significantly increase your capital, but also lose it completely.">
    <meta name="author" content="<?php echo e(get_company_name()); ?>">

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title><?php echo e(strtoupper(config('app.name'))); ?> - <?php echo $__env->yieldContent('title'); ?> </title>
    <?php $themeColor = get_theme_colors_forAll('admin_theme') ?>
    <style>
        :root {
            --custom-primary: <?= $themeColor->primary_color ?? '#7367f0' ?>;
            --custom-form-color: <?= $themeColor->form_color ?? '#979fa6' ?>;
            --bs-body-color: <?= $themeColor->body_color ?? '#67748e' ?>;
        }
    </style>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(get_favicon_icon()); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/vendors.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/extensions/toastr.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/plugins/extensions/ext-component-toastr.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')); ?>">
    <?php echo $__env->yieldContent('vendor-css'); ?>
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/bootstrap.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/bootstrap-extended.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/colors.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/components.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/themes/dark-layout.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/themes/bordered-layout.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/themes/semi-dark-layout.css')); ?>">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/pages/authentication.css')); ?>">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/assets/css/style.css')); ?>">
    <!-- END: Custom CSS-->
    <style>
        .auth-wrapper.auth-cover .brand-logo {
            position: unset;
            top: 2rem;
            left: 2rem;
            margin: 0;
            z-index: 1;
            justify-content: unset;
        }
       
    </style>

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/vendors.min.js')); ?>"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')); ?>"></script>
    <?php echo $__env->yieldContent('page-vendor-js'); ?>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="<?php echo e(asset('admin-assets/app-assets/js/core/app-menu.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/js/core/app.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/src/js/core/confirm-alert.js')); ?>"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!-- <script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/pages/auth-register.js')); ?>"></script> -->
    <script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/ui/ui-feather.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/pages/common-ajax.js')); ?>"></script>
    <script src="<?php echo e(asset('/common-js/custom-from-validation.js')); ?>"></script>
    <!-- enter key form submit handler -->
    <script src="<?php echo e(asset('common-js/enter-key-handler.js')); ?>"></script>
    <?php echo $__env->yieldContent('page-js'); ?>
    <!-- END: Page JS-->
    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
</body>
<!-- END: Body-->

</html><?php /**PATH I:\code\fxcrm\fx\crm-new-laravel\resources\views/layouts/admin-auth.blade.php ENDPATH**/ ?>