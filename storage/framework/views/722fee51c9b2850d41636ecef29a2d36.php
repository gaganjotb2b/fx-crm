<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />

    <meta name="description" content="<?php echo e(get_company_name()); ?> is a broker company focuses in Forex Trading. We believe in transparency, accountability, and accuracy of services. Experience trading in the most seamless way, straight to global market, and the easiness of withdrawal.">
    <meta name="keywords" content="<?php echo e(get_company_name()); ?> is operated by <?php echo e(get_company_name()); ?> and has registered in Saint Vincent & the Grenadines with LLC number 892 LLC 2021, regulated by the Financial Services Authority (FSA) of Saint Vincent and the Grenadines. High Risk Warning : Before you enter foreign exchange and stock markets, you have to remember that trading currencies and other investment products is trading in nature and always involves a considerable risk. As a result of various financial fluctuations, you may not only significantly increase your capital, but also lose it completely.">
    <meta name="author" content="<?php echo e(get_company_name()); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('trader-assets/assets/css/root-color.css')); ?>">

    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(asset('trader-assets/assets/img/apple-icon.png')); ?>">
    <link rel="icon" type="image/png" href="<?php echo e(get_favicon_icon()); ?>">
    <title><?php echo e(strtoupper(config('app.name'))); ?> - <?php echo $__env->yieldContent('title'); ?> </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="<?php echo e(asset('trader-assets/assets/css/nucleo-icons.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('trader-assets/assets/css/nucleo-svg.css')); ?>" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="<?php echo e(asset('trader-assets/assets/css/nucleo-svg.css')); ?>" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/vendors.min.css')); ?>">
    <!-- CSS Files -->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/extensions/toastr.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/plugins/extensions/ext-component-toastr.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')); ?>">
    <link id="pagestyle" href="<?php echo e(asset('trader-assets/assets/css/soft-ui-dashboard.css?v=1.0.8')); ?>" rel="stylesheet" />
    <link id="pagestyle" href="<?php echo e(asset('trader-assets/assets/css/style.css')); ?>" rel="stylesheet" />
    <?php $themeColor = get_theme_colors_forAll('user_theme') ?>
    <style>
        :root {
            --custom-primary: <?= $themeColor->primary_color ?? '#D1B970' ?>;
            --custom-form-color: <?= $themeColor->form_color ?? '#979fa6' ?>;
            --bs-body-color: <?= $themeColor->body_color ?? '#67748e' ?>;
        }

        @media only screen and (max-width:1200px) {
            .footer-links-details {
                display: none;
            }
        }

        .brand-logo {
            top: 10%;
            left: 24%;
            position: absolute;
        }
    </style>
    <?php echo $__env->yieldContent('style'); ?>
</head>

<body class="<?php echo e(get_client_theme_color()); ?>">
    <main class="main-content main-content-bg mt-0">
        <section>
            <div class="page-header min-vh-75">
                <div class="container">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </section>
    </main>
    <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <!-- include login footer -->
    <?php echo $__env->make('layouts.login-footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <!--   Core JS Files   -->
    <script src="<?php echo e(asset('trader-assets/assets/js/core/popper.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/core/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/core/jquery-3.6.0.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')); ?>"></script>

    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/perfect-scrollbar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/smooth-scrollbar.min.js')); ?>"></script>
    <!-- Kanban scripts -->
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/dragula/dragula.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/jkanban/jkanban.js')); ?>"></script>

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- common ajax -->


    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/src/js/core/confirm-alert.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/pages/common-ajax.js')); ?>"></script>
    <script src="<?php echo e(asset('/common-js/custom-from-validation.js')); ?>"></script>
    <!-- enter key handler -->
    <script src="<?php echo e(asset('common-js/enter-key-handler.js')); ?>"></script>
    <!-- BEGIN: Page JS-->
    <?php echo $__env->yieldContent('page-js'); ?>
    <!-- END: Page JS-->
</body>

</html><?php /**PATH I:\code\fxcrm\fx\crm-new-laravel\resources\views/layouts/trader-auth.blade.php ENDPATH**/ ?>