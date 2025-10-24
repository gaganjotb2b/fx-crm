<!DOCTYPE html>
<html lang="en">
<?php
use App\Services\PermissionService;
?>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="refresh" content="<?php echo e(config('session.lifetime') * 1); ?>; <?php echo e(url('/ib/lock-screen' . '/' . base64_encode(auth()->user()->id) . '/' . base64_encode(url()->current()))); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <meta name="description" content="<?php echo e(get_company_name()); ?> is a broker company focuses in Forex Trading. We believe in transparency, accountability, and accuracy of services. Experience trading in the most seamless way, straight to global market, and the easiness of withdrawal.">
    <meta name="keywords" content="<?php echo e(get_company_name()); ?> is operated by <?php echo e(get_company_name()); ?> and has registered in Saint Vincent & the Grenadines with LLC number 892 LLC 2021, regulated by the Financial Services Authority (FSA) of Saint Vincent and the Grenadines. High Risk Warning : Before you enter foreign exchange and stock markets, you have to remember that trading currencies and other investment products is trading in nature and always involves a considerable risk. As a result of various financial fluctuations, you may not only significantly increase your capital, but also lose it completely.">
    <meta name="author" content="<?php echo e(get_company_name()); ?>">
    <!-- style sheet -->
    <link rel="stylesheet" href="<?php echo e(asset('trader-assets/assets/css/root-color.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')); ?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(get_favicon_icon()); ?>">
    <link rel="icon" type="image/png" href="<?php echo e(get_favicon_icon()); ?>">
    <title id="minutes"><?php echo e(strtoupper(config('app.name'))); ?> - <?php echo $__env->yieldContent('title'); ?> </title>
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
    <link id="pagestyle" href="<?php echo e(asset('trader-assets/assets/css/soft-ui-dashboard.css?v=1.0.8')); ?>" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/extensions/toastr.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('trader-assets/assets/css/style.css')); ?>">
    <!-- custom page css can added here -->
    <?php $themeColor = get_theme_colors_forAll('user_theme') ?>
    <style>
        :root {
            --custom-primary: <?= $themeColor->primary_color ?? '#D1B970' ?>;
            --custom-form-color: <?= $themeColor->form_color ?? '#979fa6' ?>;
            --bs-body-color: <?= $themeColor->body_color ?? '#67748e' ?>;
        }
    </style>
    <?php echo $__env->yieldContent('page-css'); ?>
    <style>
        .error-msg {
            color: red;
        }

        .al-error-solve {
            position: relative;
        }

        .al-error-solve .error-msg {
            position: absolute;
            bottom: -21px;
            left: 0;
        }

        .dropzone .dz-message {
            font-size: 2rem;
            color: var(--custom-primary);
        }

        .dropzone .dz-message::before {
            content: '\f093';
            font-family: 'FontAwesome';
            background-image: url("");
            font-size: 25px;
            position: absolute;
            top: 2rem;
            width: 80px;
            height: 80px;
            display: inline-block;
            line-height: 1;
            z-index: 2;
            color: var(--custom-primary);
            text-indent: 0px;
            font-weight: normal;
            -webkit-font-smoothing: antialiased;
        }

        .dropzone .dz-message {
            font-size: 1rem;
            position: absolute;
            top: 37px;
            left: 0;
            width: 100%;
            height: 100%;
            color: var(--custom-primary);
            display: flex;
            justify-content: center;
            align-items: first baseline;
            margin: 0;
        }

        .dropzone {
            min-height: 156px;
            border: 2px dashed var(--custom-primary);
            background: #f8f8f8;
            position: relative;
        }

        .fixed-plugin .card {
            z-index: 10000;
        }

        .flag-icon {
            margin-right: 5px;
        }

        .custom-height-con {
            min-height: calc(100vh - 200px);
        }

        .navbar-vertical.navbar-expand-xs {
            display: block;
            position: fixed;
            top: 0;
            bottom: 0;
            width: 100%;
            max-width: 15.625rem !important;
            overflow-y: auto;
            padding: 0;
            box-shadow: 0 1px 10px 1px rgb(0 0 0 / 5%) !important;
            background-color: white;
        }

        @media only screen and (min-width:1200px) {
            .mobile-view-logo {
                display: none;
            }

            .toggler-visibility {
                display: none;
            }
        }

        @media only screen and (max-width:1200px) {
            .ms-1 {
                display: none;
            }

            .ib-referal .input-group-text+.form-control {
                padding-left: 50px !important;
            }

            .trd-referal .input-group-text+.form-control {
                padding-left: 50px !important;
            }

            .selected-language,
            .breadcrumb-area,
            .footer-links-details,
            .dashboard-small-size,
            .balance-badge {
                display: none !important;
            }

            .navbar-collapse .navbar-nav {
                width: 100%;
            }
        }

        @media only screen and (max-width:500px) {
            .dropdown-language {
                display: none;
            }
        }

        .g-sidenav-show.g-sidenav-pinned .sidenav {
            transform: translateX(0);
            z-index: 10000000;
        }

        .sidenav {
            z-index: 10001;
        }

        span.nav-link-text.ms-1 {
            display: contents;
        }

        .swal2-container {
            z-index: 99999 !important;
        }

        .swal2-icon {
            width: 3em !important;
            height: 3em !important;
        }

        .swal2-popup {
            width: 26em !important;
        }

        .dark-version .navbar-vertical .navbar-nav .nav-item .nav-link {
            color: var(--bs-body-color) !important;
            opacity: 1;
        }

        @media only screen and (max-width:1200px) {
            .dark-version .navbar-vertical .navbar-nav .nav-item .nav-link {
                color: var(--bs-body-color) !important;
                opacity: 1;
            }

            .dark-version .navbar-vertical .navbar-nav .nav-item .nav-link[data-bs-toggle="collapse"]:after {
                color: var(--bs-body-color) !important;
            }

            .dark-version .sidenav .navbar-nav .nav-item .collapse .nav .nav-item .nav-link:before,
            .dark-version .sidenav .navbar-nav .nav-item .collapsing .nav .nav-item .nav-link:before {
                background: var(--bs-body-color) !important;
            }
        }

        .dark-version #need-support {
            color: white !important;
        }

        .btn-check:focus+.btn-primary,
        .btn-primary:focus {
            /* color: #fff; */
            /* background-color: var(--custom-primary); */
            border-color: unset !important;
            box-shadow: unset !important;
        }
    </style>
    <?php echo $__env->yieldContent('style'); ?>
</head>

<body class="g-sidenav-show <?php echo e(get_client_theme_color()); ?> bg-gray-100">
    <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
(function () {
    var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
    s1.async = true;
    s1.src = 'https://embed.tawk.to/623b69122bd26d087e74606c/1fus14sop';
    s1.charset = 'UTF-8';
    s1.setAttribute('crossorigin', '*');
    s0.parentNode.insertBefore(s1, s0);
})();
</script>
<style>
/* Custom CSS for Tawk.to widget placement */
#tawkchat-container {
    bottom: 0 !important;
    right: 0 !important;
    position: fixed !important;
    z-index: 9999 !important;
}
</style>
<!--End of Tawk.to Script-->

    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="#" target="_blank">
                <img class="img img-fluid" src="<?php echo e(get_user_logo()); ?>" alt="<?php echo e(config('app.name')); ?>" style="max-width:100%">
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <?php if(\App\Services\CombinedService::is_combined('client')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('trader.dashboard')); ?>" id="btn-cancel-ib" class="nav-link bg-gradient-faded-info btn-close-white bg-gradient-faded-white btn" aria-controls="dashboardsExamples" role="button">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">
                            <svg class="text-dark" width="16px" height="16px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>Trader Dashboard</title>
                                <g id="ib-request-btn" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Rounded-Icons" transform="translate(-1717.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                            <g id="ib-registration" transform="translate(1.000000, 0.000000)">
                                                <path class="color-background" d="M45,0 L26,0 C25.447,0 25,0.447 25,1 L25,20 C25,20.379 25.214,20.725 25.553,20.895 C25.694,20.965 25.848,21 26,21 C26.212,21 26.424,20.933 26.6,20.8 L34.333,15 L45,15 C45.553,15 46,14.553 46,14 L46,1 C46,0.447 45.553,0 45,0 Z" id="Path" opacity="0.59858631"></path>
                                                <path class="color-foreground" d="M22.883,32.86 C20.761,32.012 17.324,31 13,31 C8.676,31 5.239,32.012 3.116,32.86 C1.224,33.619 0,35.438 0,37.494 L0,41 C0,41.553 0.447,42 1,42 L25,42 C25.553,42 26,41.553 26,41 L26,37.494 C26,35.438 24.776,33.619 22.883,32.86 Z" id="Path"></path>
                                                <path class="color-foreground" d="M13,28 C17.432,28 21,22.529 21,18 C21,13.589 17.411,10 13,10 C8.589,10 5,13.589 5,18 C5,22.529 8.568,28 13,28 Z" id="Path"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1" id="btn-label">Trader Dashboard</span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('ib.dashboard')); ?>" class="nav-link <?php echo e(Request::is('ib/dashboard') ? 'active' : ''); ?>" aria-controls="dashboardsExamples" role="button">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">
                            <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>Dashbaord</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(0.000000, 148.000000)">
                                                <path class="color-background" d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z" opacity="0.598981585"></path>
                                                <path class="color-background" d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z">
                                                </path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1"><?php echo e(__('ib-menu-left.Dashboard')); ?></span>
                    </a>
                </li>
                <!-- myadmin -->
                <?php if(PermissionService::has_permission('my_admin','ib')): ?>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#dashboardsExamples" class="nav-link <?php echo e(Request::is('user/user-admin/*') ? 'active' : ''); ?>" aria-controls="dashboardsExamples" role="button" aria-expanded="false">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">
                            <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>shop </title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(0.000000, 148.000000)">
                                                <polygon class="color-background" opacity="0.596981957" points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667">
                                                </polygon>
                                                <path class="color-background" d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z" opacity="0.596981957"></path>
                                                <path class="color-background" d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z">
                                                </path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1"><?php echo e(__('ib-menu-left.my-admin')); ?></span>
                    </a>
                    <div class="collapse  <?php echo e(Request::is('ib/ib-admin/*') ? 'show' : ''); ?> " id="dashboardsExamples">
                        <ul class="nav ms-4 ps-3">
                            <!-- profile overview -->
                            <?php if(PermissionService::has_permission('profile_overview','ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/ib-admin/profile-overview') ? 'active' : ''); ?>">
                                <a class="nav-link <?php echo e(Request::is('ib/ib-admin/profile-overview') ? 'active' : ''); ?>" href="<?php echo e(route('ib.ib-admin.profile-overview')); ?>">
                                    <span class="sidenav-mini-icon"> P </span>
                                    <span class="sidenav-normal"><?php echo e(__('ib-menu-left.profile-overview')); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <!-- settings -->
                            <?php if(PermissionService::has_permission('settings','ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/ib-admin/settings') ? 'active' : ''); ?>">
                                <a class="nav-link <?php echo e(Request::is('ib/ib-admin/settings') ? 'active' : ''); ?>" href="<?php echo e(route('ib.ib-admin-account-settings')); ?>">
                                    <span class="sidenav-mini-icon"> S </span>
                                    <span class="sidenav-normal"> <?php echo e(__('ib-menu-left.settings')); ?> </span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <!-- verification -->
                            <?php if(PermissionService::has_permission('verification','ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/ib-admin/account-verification') ? 'active' : ''); ?>">
                                <a class="nav-link <?php echo e(Request::is('ib/ib-admin/account-verification') ? 'active' : ''); ?>" href="<?php echo e(route('ib.ib-admin-account-verification')); ?>">
                                    <span class="sidenav-mini-icon"> V </span>
                                    <span class="sidenav-normal"><?php echo e(__('ib-menu-left.verification')); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <!-- banking -->
                            <?php if(PermissionService::has_permission('banking','ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/ib-admin/ib-banking') ? 'active' : ''); ?>">
                                <a class="nav-link  <?php echo e(Request::is('ib/ib-admin/ib-banking') ? 'active' : ''); ?>" href="<?php echo e(route('ib.ib-admin.ib-banking')); ?>">
                                    <span class="sidenav-mini-icon"> B </span>
                                    <span class="sidenav-normal"><?php echo e(__('ib-menu-left.banking')); ?> </span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                <hr class="horizontal dark">
                <!-- affiliate -->
                <?php if(PermissionService::has_permission('affiliate','ib')): ?>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#affiliates" class="nav-link <?php echo e(Request::is('ib/affiliates/*') ? 'active' : ''); ?>" aria-controls="affiliates" role="button" aria-expanded="false">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">
                            <svg width="12px" height="20px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>Affiliate</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1720.000000, -592.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(4.000000, 301.000000)">
                                                <path class="color-background" d="M45,0 L26,0 C25.447,0 25,0.447 25,1 L25,20 C25,20.379 25.214,20.725 25.553,20.895 C25.694,20.965 25.848,21 26,21 C26.212,21 26.424,20.933 26.6,20.8 L34.333,15 L45,15 C45.553,15 46,14.553 46,14 L46,1 C46,0.447 45.553,0 45,0 Z" opacity="0.59858631"></path>
                                                <path class="color-foreground" d="M22.883,32.86 C20.761,32.012 17.324,31 13,31 C8.676,31 5.239,32.012 3.116,32.86 C1.224,33.619 0,35.438 0,37.494 L0,41 C0,41.553 0.447,42 1,42 L25,42 C25.553,42 26,41.553 26,41 L26,37.494 C26,35.438 24.776,33.619 22.883,32.86 Z">
                                                </path>
                                                <path class="color-foreground" d="M13,28 C17.432,28 21,22.529 21,18 C21,13.589 17.411,10 13,10 C8.589,10 5,13.589 5,18 C5,22.529 8.568,28 13,28 Z">
                                                </path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1"><?php echo e(__('ib-menu-left.Affiliate')); ?></span>
                    </a>
                    <div class="collapse <?php echo e(Request::is('ib/affiliates/*') ? 'show' : ''); ?>" id="affiliates">
                        <ul class="nav ms-4 ps-3">
                            <!-- ib tree -->
                            <?php if(PermissionService::has_permission('ib_tree','ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/affiliates/ib-tree') ? 'active' : ''); ?>">
                                <a class="nav-link " href="<?php echo e(route('ib.my-ib.tree')); ?>">
                                    <span class="sidenav-mini-icon"> I </span>
                                    <span class="sidenav-normal"><?php echo e(__('ib-menu-left.IB Tree')); ?> </span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <!-- ny ib -->
                            <?php if(PermissionService::has_permission('my_ib','ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/affiliates/my-ib') ? 'active' : ''); ?>">
                                <a class="nav-link " href="<?php echo e(route('ib.my-ib.report')); ?>">
                                    <span class="sidenav-mini-icon"> I </span>
                                    <span class="sidenav-normal"><?php echo e(__('ib-menu-left.My IB (s)')); ?> </span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <!-- my clients -->
                            <?php if(PermissionService::has_permission('my_clients','ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/affiliates/my-clients') ? 'active' : ''); ?>">
                                <a class="nav-link " href="<?php echo e(route('ib.myclients.report')); ?>">
                                    <span class="sidenav-mini-icon"> C </span>
                                    <span class="sidenav-normal"><?php echo e(__('ib-menu-left.My Clients')); ?> </span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <!-- client deposit report -->
                            <?php if(PermissionService::has_permission('deposit_reports','ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/affiliates/clients-deposit-report') ? 'active' : ''); ?>">
                                <a class="nav-link <?php echo e(Request::is('ib/affiliates/clients-deposit-report') ? 'active' : ''); ?>" href="<?php echo e(route('ib.affilates.deposit-reports')); ?>">
                                    <span class="sidenav-mini-icon"> D </span>
                                    <span class="sidenav-normal"> <?php echo e(__('ib-menu-left.Deposit Reports')); ?> </span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <!-- client withdraw report -->
                            <?php if(PermissionService::has_permission('withdraw_reports','ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/affiliates/clients-withdraw-report') ? 'active' : ''); ?>">
                                <a class="nav-link <?php echo e(Request::is('ib/affiliates/clients-withdraw-report') ? 'active' : ''); ?>" href="<?php echo e(url('ib/affiliates/clients-withdraw-report')); ?>">
                                    <span class="sidenav-mini-icon"> W </span>
                                    <span class="sidenav-normal"><?php echo e(__('ib-menu-left.Withdraw Reports')); ?> </span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                <!-- reports -->
                <?php if(PermissionService::has_permission('reports','ib')): ?>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#reports" class="nav-link <?php echo e(Request::is('ib/reports/*') ? 'active' : ''); ?>" aria-controls="reports" role="button" aria-expanded="false">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">
                            <i class="ni ni-single-copy-04 text-primary"></i>
                        </div>
                        <span class="nav-link-text ms-1"><?php echo e(__('ib-menu-left.Reports')); ?></span>
                    </a>
                    <div class="collapse <?php echo e(Request::is('ib/reports/*') ? 'show' : ''); ?>" id="reports">
                        <ul class="nav ms-4 ps-3">
                            <!-- IB commission -->
                            <?php if(PermissionService::has_permission('trade_commission','ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/reports/ib-comission') ? 'active' : ''); ?>">
                                <a class="nav-link <?php echo e(Request::is('ib/reports/ib-comission') ? 'active' : ''); ?>" href="<?php echo e(url('ib/reports/ib-comission')); ?>">
                                    <span class="sidenav-mini-icon"> C </span>
                                    <span class="sidenav-normal"> IB Commission </span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <!-- witdraw report -->
                            <?php if(PermissionService::has_permission('withdraw_report','ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/reports/withdraw') ? 'active' : ''); ?>">
                                <a class="nav-link <?php echo e(Request::is('ib/reports/withdraw') ? 'active' : ''); ?>" href="<?php echo e(url('ib/reports/withdraw')); ?>">
                                    <span class="sidenav-mini-icon"> W </span>
                                    <span class="sidenav-normal">IB Withdraw </span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <!-- ib to trader balance transfer report -->
                            <!--<?php if(PermissionService::has_permission('ib_balance_trnasfer','ib')): ?>-->
                            <!--<li class="nav-item <?php echo e(Request::is('ib/reports/balance-transfer-ib-to-trader') ? 'active' : ''); ?>">-->
                            <!--    <a class="nav-link <?php echo e(Request::is('ib/reports/balance-transfer-ib-to-trader') ? 'active' : ''); ?>" href="<?php echo e(url('ib/reports/balance-transfer-ib-to-trader')); ?>">-->
                            <!--        <span class="sidenav-mini-icon"> B </span>-->
                            <!--        <span class="sidenav-normal">IB To Trader Transfer</span>-->
                            <!--    </a>-->
                            <!--</li>-->
                            <!--<?php endif; ?>-->
                            <!-- ib to trader balance transfer report -->
                            <!--<?php if(PermissionService::has_permission('trader_to_ib_balance_transfer','ib')): ?>-->
                            <!--<li class="nav-item <?php echo e(Request::is('ib/reports/balance-transfer-trader-to-ib') ? 'active' : ''); ?>">-->
                            <!--    <a class="nav-link <?php echo e(Request::is('ib/reports/balance-transfer-trader-to-ib') ? 'active' : ''); ?>" href="<?php echo e(url('ib/reports/balance-transfer-trader-to-ib')); ?>">-->
                            <!--        <span class="sidenav-mini-icon"> B </span>-->
                            <!--        <span class="sidenav-normal">Trader To IB Transfer</span>-->
                            <!--    </a>-->
                            <!--</li>-->
                            <!--<?php endif; ?>-->
                            <!-- IB Balance send -->
                            <?php if(PermissionService::has_permission('ib_balance_send','ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/reports/ib-balance/send') ? 'active' : ''); ?>">
                                <a class="nav-link <?php echo e(Request::is('ib/reports/ib-balance/send') ? 'active' : ''); ?>" href="<?php echo e(url('ib/reports/ib-balance/send')); ?>">
                                    <span class="sidenav-mini-icon"> BS </span>
                                    <span class="sidenav-normal">IB Balance Send</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <!-- IB Balance Recived -->
                            <?php if(PermissionService::has_permission('ib_balance_receive','ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/reports/ib-balance/recived') ? 'active' : ''); ?>">
                                <a class="nav-link <?php echo e(Request::is('ib/reports/ib-balance/recived') ? 'active' : ''); ?>" href="<?php echo e(url('ib/reports/ib-balance/recived')); ?>">
                                    <span class="sidenav-mini-icon"> BR </span>
                                    <span class="sidenav-normal">IB Balance Received</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                <!-- withdraw -->
                <?php if(PermissionService::has_permission('withdraw','ib')): ?>
                <li class="nav-item ">
                    <a data-bs-toggle="collapse" href="#withdraw" class="nav-link <?php echo e(Request::is('ib/withdraw/*') ? 'active' : ''); ?>" aria-controls="withdraw" role="button" aria-expanded="false">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">
                            <svg width="12px" height="12px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>Withdraw</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(304.000000, 151.000000)">
                                                <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                                <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                                </path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1"><?php echo e(__('ib-menu-left.Withdraw')); ?></span>
                    </a>
                    <div class="collapse <?php echo e(Request::is('ib/withdraw/*') ? 'show' : ''); ?>" id="withdraw">
                        <ul class="nav ms-4 ps-3">
                            <!-- bank withdraw -->
                            <?php if(PermissionService::has_permission('bank_withdraw','ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/withdraw/bank-withdraw') ? 'active' : ''); ?>">
                                <a class="nav-link <?php echo e(Request::is('ib/withdraw/bank-withdraw') ? 'active' : ''); ?>" href="<?php echo e(route('ib.withdraw.bank-withdraw')); ?>">
                                    <span class="sidenav-mini-icon"> B </span>
                                    <span class="sidenav-normal"><?php echo e(__('ib-menu-left.Bank Withdraw')); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <!-- crypto withdraw -->
                            <?php if(PermissionService::has_permission('crypto_withdraw','ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/withdraw/crypto-withdraw') ? 'active' : ''); ?>">
                                <a class="nav-link <?php echo e(Request::is('ib/withdraw/crypto-withdraw') ? 'active' : ''); ?>" href="<?php echo e(route('ib.withdraw.crypto-withdraw')); ?>">
                                    <span class="sidenav-mini-icon"> C </span>
                                    <span class="sidenav-normal"><?php echo e(__('ib-menu-left.Crypto Withdraw')); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                <!-- transfer -->
                <?php if(PermissionService::has_permission('transfer','ib')): ?>
                <li class="nav-item ">
                    <a data-bs-toggle="collapse" href="#transfer" class="nav-link <?php echo e(Request::is('ib/transfer/*') ? 'active' : ''); ?>" aria-controls="transfer" role="button" aria-expanded="false">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">
                            <svg width="12px" height="12px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>transfer</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(304.000000, 151.000000)">
                                                <path class="color-background" d="M39.3,0.706666667 C38.9660984,0.370464027 38.5048767,0.192278529 38.0316667,0.216666667 C14.6516667,1.43666667 6.015,22.2633333 5.93166667,22.4733333 C5.68236407,23.0926189 5.82664679,23.8009159 6.29833333,24.2733333 L15.7266667,33.7016667 C16.2013871,34.1756798 16.9140329,34.3188658 17.535,34.065 C17.7433333,33.98 38.4583333,25.2466667 39.7816667,1.97666667 C39.8087196,1.50414529 39.6335979,1.04240574 39.3,0.706666667 Z M25.69,19.0233333 C24.7367525,19.9768687 23.3029475,20.2622391 22.0572426,19.7463614 C20.8115377,19.2304837 19.9992882,18.0149658 19.9992882,16.6666667 C19.9992882,15.3183676 20.8115377,14.1028496 22.0572426,13.5869719 C23.3029475,13.0710943 24.7367525,13.3564646 25.69,14.31 C26.9912731,15.6116662 26.9912731,17.7216672 25.69,19.0233333 L25.69,19.0233333 Z">
                                                </path>
                                                <path class="color-background" d="M1.855,31.4066667 C3.05106558,30.2024182 4.79973884,29.7296005 6.43969145,30.1670277 C8.07964407,30.6044549 9.36054508,31.8853559 9.7979723,33.5253085 C10.2353995,35.1652612 9.76258177,36.9139344 8.55833333,38.11 C6.70666667,39.9616667 0,40 0,40 C0,40 0,33.2566667 1.855,31.4066667 Z">
                                                </path>
                                                <path class="color-background" d="M17.2616667,3.90166667 C12.4943643,3.07192755 7.62174065,4.61673894 4.20333333,8.04166667 C3.31200265,8.94126033 2.53706177,9.94913142 1.89666667,11.0416667 C1.5109569,11.6966059 1.61721591,12.5295394 2.155,13.0666667 L5.47,16.3833333 C8.55036617,11.4946947 12.5559074,7.25476565 17.2616667,3.90166667 L17.2616667,3.90166667 Z" opacity="0.598539807"></path>
                                                <path class="color-background" d="M36.0983333,22.7383333 C36.9280725,27.5056357 35.3832611,32.3782594 31.9583333,35.7966667 C31.0587397,36.6879974 30.0508686,37.4629382 28.9583333,38.1033333 C28.3033941,38.4890431 27.4704606,38.3827841 26.9333333,37.845 L23.6166667,34.53 C28.5053053,31.4496338 32.7452344,27.4440926 36.0983333,22.7383333 L36.0983333,22.7383333 Z" opacity="0.598539807"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1"><?php echo e(__('ib-menu-left.Transfer')); ?></span>
                    </a>
                    <div class="collapse <?php echo e(Request::is('ib/transfer/*') ? 'show' : ''); ?>" id="transfer">
                        <ul class="nav ms-4 ps-3">
                            <!-- ib to trader transfer -->
                            <?php if(PermissionService::has_permission('ib_to_trader_transfer','ib')): ?>
                            <?php if(\App\Services\Trader\FinanceService::check_op('ib_to_trader')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/transfer/ib-to-trader') ? 'active' : ''); ?>">
                                <a class="nav-link <?php echo e(Request::is('ib/transfer/ib-to-trader') ? 'active' : ''); ?>" href="<?php echo e(route('ib.transfer.ib-to-trader')); ?>">
                                    <span class="sidenav-mini-icon"> IB </span>
                                    <span class="sidenav-normal"><?php echo e(__('ib-menu-left.IB to Trader Transfer')); ?>

                                    </span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php endif; ?>
                            <!-- ib to ib transfer -->
                            <?php if(PermissionService::has_permission('ib_to_ib_transfer','ib')): ?>
                            <?php if(\App\Services\Trader\FinanceService::check_op('ib_to_ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/transfer/ib-to-ib') ? 'active' : ''); ?>">
                                <a class="nav-link <?php echo e(Request::is('ib/transfer/ib-to-ib') ? 'active' : ''); ?>" href="<?php echo e(route('ib.transfer.ib-to-ib')); ?>">
                                    <span class="sidenav-mini-icon"> IB </span>
                                    <span class="sidenav-normal"><?php echo e(__('page.ib-to-ib-transfer')); ?>

                                    </span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                <hr class="horizontal dark">

                <!--  Client support -->
                <?php if(PermissionService::has_permission('support','ib')): ?>
                <li class="nav-item <?php echo e(Request::is('ib/support/*') ? 'active' : ''); ?>">
                    <a data-bs-toggle="collapse" href="#support" class="nav-link <?php echo e(Request::is('ib/support/*') ? 'active' : ''); ?>" aria-controls="basicExamples" role="button" aria-expanded="false">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">
                            <svg width="12px" height="20px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>Support</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1720.000000, -592.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(4.000000, 301.000000)">
                                                <path class="color-background" d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z" opacity="0.603585379"></path>
                                                <path class="color-background" d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z">
                                                </path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1"><?php echo e(__('page.support')); ?></span>
                    </a>
                    <div class="collapse <?php echo e(Request::is('ib/support/*') ? 'show' : ''); ?>" id="support">
                        <ul class="nav ms-4 ps-3">
                            <!-- support ticket -->
                            <?php if(PermissionService::has_permission('support_ticket','ib')): ?>
                            <li class="nav-item <?php echo e(Request::is('ib/support/ticket') ? 'active' : ''); ?>">
                                <a class="nav-link <?php echo e(Request::is('ib/support/ticket') ? 'active' : ''); ?>" href="<?php echo e(route('ib.support.ticket')); ?>">
                                    <span class="sidenav-mini-icon"> S </span>
                                    <span class="sidenav-normal"><?php echo e(__('support-ticket.support_ticket')); ?> </span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                <!-- End  Client support -->
                <!-- Start: Marketing -->
                <li class="nav-item <?php echo e(Request::is('ib/marketing/*') ? 'active' : ''); ?>">
                    <a data-bs-toggle="collapse" href="#marketing" class="nav-link <?php echo e(Request::is('ib/marketing/*') ? 'active' : ''); ?>" aria-controls="marketing" role="button" aria-expanded="false">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">
                            <svg width="12px" height="12px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>Withdraw</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(304.000000, 151.000000)">
                                                <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                                <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                                </path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">Marketing</span>
                    </a>
                    <div class="collapse <?php echo e(Request::is('ib/marketing/*') ? 'show' : ''); ?>" id="marketing">
                        <ul class="nav ms-4 ps-3">
                            <!-- english -->
                            <li class="nav-item <?php echo e(Request::is('ib/marketing/ib-banner') ? 'active' : ''); ?>">
                                <a class="nav-link <?php echo e(Request::is('ib/marketing/ib-banner') ? 'active' : ''); ?>" href="<?php echo e(route('ib.marketing.ib-banner')); ?>">
                                    <span class="sidenav-mini-icon"> IBB </span>
                                    <span class="sidenav-normal">IB Banner</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="collapse <?php echo e(Request::is('ib/marketing/*') ? 'show' : ''); ?>" id="marketing">
                        <ul class="nav ms-4 ps-3">
                            <!-- english -->
                            <li class="nav-item <?php echo e(Request::is('ib/marketing/trader-banner') ? 'active' : ''); ?>">
                                <a class="nav-link <?php echo e(Request::is('ib/marketing/trader-banner') ? 'active' : ''); ?>" href="<?php echo e(route('ib.marketing.trader-banner')); ?>">
                                    <span class="sidenav-mini-icon"> TB </span>
                                    <span class="sidenav-normal">Trader Banner</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- End: Marketing -->
            </ul>
        </div>
        <div class="sidenav-footer mx-3 mt-3 pt-3">
            <div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
                <div class="full-background" style="background-image: url('assets/img/curved-images/white-curved.jpg')"></div>
                <div class="card-body text-start p-3 w-100">
                    <div class="icon icon-shape icon-sm bg-white shadow text-center mb-3 d-flex align-items-center justify-content-center border-radius-md">
                        <i class="fa fa-question-circle text-dark text-gradient text-lg top-0" aria-hidden="true" id="sidenavCardIcon"></i>
                    </div>
                    <div class="docs-info">
                        <h6 class="text-white up mb-0"><?php echo e(__('ib-menu-left.Need Support?')); ?></h6>
                        <p class="text-xs font-weight-bold"><?php echo e(__('ib-menu-left.Please send a support tiket')); ?></p>
                        <!-- support ticket -->
                        <?php if(PermissionService::has_permission('support','ib')): ?>
                        <a href="/ib/support/ticket" target="_blank" class="btn btn-white btn-sm w-100 mb-0"><?php echo e(__('ib-menu-left.Support Tiket')); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <div style="margin-right: 1rem;">
                    <a class="brand-logo rounded-pill p-1 mobile-view-logo" href="<?php echo e(route('trader.login')); ?>">
                        <img src="<?php echo e(get_user_logo()); ?>" height="30" alt="<?php echo e(config('app.name')); ?>">
                    </a>
                </div>
                <div class="breadcrumb-area">
                    <?php echo $__env->yieldContent('bread_crumb'); ?>
                </div>
                <div class="toggler-visibility">
                    <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none">
                        <a href="javascript:;" class="nav-link text-body p-0">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center balance-badge">
                        <?php

                        use App\Services\BalanceService;
                        ?>
                        <span class="badge badge-secondary">Balance : $ <?php echo e(\App\Services\balance\BalanceSheetService::ib_wallet_balance(auth()->user()->id)); ?></span>
                    </div>
                    <ul class="navbar-nav  justify-content-end">
                        <?php if(session()->has('admin_id')): ?>
                            <li class="nav-item d-flex align-items-center" style="margin-right:1rem">
                                    <a type="button" class="btn btn-sm bg-gradient-primary m-0" href="<?php echo e(route('admin.admin.dashboard')); ?>">Admin Dashboard</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item dropdown dropdown-language" style="margin-right: 1rem;">
                            <a class="nav-link dropdown-toggle" id="dropdown-flag" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php if(session()->get('locale') == 'fr'): ?>
                                <?php ($lang = __('language.french')); ?>
                                <?php ($flag = 'fr'); ?>
                                <?php elseif(session()->get('locale') == 'de'): ?>
                                <?php ($lang = __('language.german')); ?>
                                <?php ($flag = 'de'); ?>
                                <?php elseif(session()->get('locale') == 'pt'): ?>
                                <?php ($lang = __('language.portuguese')); ?>
                                <?php ($flag = 'pt'); ?>
                                <?php elseif(session()->get('locale') == 'zh'): ?>
                                <?php ($lang = __('language.chinese')); ?>
                                <?php ($flag = 'cn'); ?>
                                <?php else: ?>
                                <?php ($lang = __('language.english')); ?>
                                <?php ($flag = 'us'); ?>
                                <?php endif; ?>
                                <i class="flag-icon flag-icon-<?php echo e($flag); ?>"></i>
                                <span class="selected-language">
                                    <?php echo e($lang); ?>

                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-flag">
                                <a class="dropdown-item lang-change" href="#" data-language="en"><i class="flag-icon flag-icon-us"></i><?php echo e(__('language.english')); ?></a>
                                <a class="dropdown-item lang-change" href="#" data-language="fr"><i class="flag-icon flag-icon-fr"></i> <?php echo e(__('language.french')); ?></a>
                                <a class="dropdown-item lang-change" href="#" data-language="de"><i class="flag-icon flag-icon-de"></i> <?php echo e(__('language.german')); ?></a>
                                <a class="dropdown-item lang-change" href="#" data-language="pt"><i class="flag-icon flag-icon-pt"></i> <?php echo e(__('language.portuguese')); ?></a>
                                <a class="dropdown-item lang-change" href="#" data-language="zh"><i class="flag-icon flag-icon-cn"></i> <?php echo e(__('language.chinese')); ?></a>
                            </div>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <a href="<?php echo e(route('logout')); ?>" class="nav-link text-body font-weight-bold px-0" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out" aria-hidden="true" style="font-size:1.5rem"></i>
                            </a>
                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                        </li>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        <!-- main content starts -->
        <?php echo $__env->yieldContent('content'); ?>
        <!-- main content end -->
    </main>
    <div class="fixed-plugin-hide d-none">
        <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
            <i class="fa fa-cog py-2"> </i>
        </a>
        <div class="card shadow-lg blur">
            <div class="card-header pb-0 pt-3  bg-transparent ">
                <div class="float-start">
                    <h5 class="mt-3 mb-0">
                        <?php
                        $company = config('app.name');
                        $company = str_replace('_', ' ', $company);
                        echo $company;
                        ?>
                    </h5>
                    <p>Dashboard Customization.</p>
                </div>
                <div class="float-end mt-4">
                    <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
                <!-- End Toggle Button -->
            </div>
            <hr class="horizontal dark my-1">
            <div class="card-body pt-sm-3 pt-0">
                <!-- Sidebar Backgrounds -->
                <div>
                    <h6 class="mb-0">Sidebar Colors</h6>
                </div>
                <a href="javascript:void(0)" class="switch-trigger background-color">
                    <div class="badge-colors my-2 text-start">
                        <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
                    </div>
                </a>
                <!-- Sidenav Type -->
                <div class="mt-3">
                    <h6 class="mb-0">Sidenav Type</h6>
                    <p class="text-sm">Choose between 2 different sidenav types.</p>
                </div>
                <div class="d-flex">
                    <button class="btn bg-gradient-primary w-100 px-3 mb-2 active" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
                    <button class="btn bg-gradient-primary w-100 px-3 mb-2 ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
                </div>
                <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
                <!-- Navbar Fixed -->
                <div class="mt-3">
                    <h6 class="mb-0">Navbar Fixed</h6>
                </div>
                <div class="form-check form-switch ps-0">
                    <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
                </div>
                <hr class="horizontal dark mb-1 d-xl-block d-none">
                <div class="mt-2 d-xl-block d-none">
                    <h6 class="mb-0">Sidenav Mini</h6>
                </div>
                <div class="form-check form-switch ps-0 d-xl-block d-none">
                    <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarMinimize" onclick="navbarMinimize(this)">
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- include session expire soon modal -->
    <?php echo $__env->make('layouts.lock-screen-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!--   Core JS Files   -->

    <script src="<?php echo e(asset('trader-assets/assets/js/core/jquery-3.6.0.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/core/popper.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/core/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/perfect-scrollbar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/smooth-scrollbar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/choices.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/photoswipe.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/photoswipe-ui-default.min.js')); ?>"></script>
    <?php echo $__env->yieldContent('corejs'); ?>
    <!-- Kanban scripts -->
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/dragula/dragula.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/jkanban/jkanban.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/chartjs.min.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/threejs.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/orbit-controls.js')); ?>"></script>
    <script src="<?php echo e(asset('common-js/layout-js/lockscreen.js')); ?>"></script>
    <!-- enter key handler -->
    <script src="<?php echo e(asset('common-js/enter-key-handler.js')); ?>"></script>
    <script>
        if (document.getElementById('choices-quantity')) {
            var element = document.getElementById('choices-quantity');
            const example = new Choices(element, {
                searchEnabled: false,
                itemSelectText: '',
                shouldSort: false,
            });
        };

        if (document.querySelector('.choice-material')) {
            var element = document.querySelector('.choice-material');
            const example = new Choices(element, {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false,
            });
        };

        if (document.querySelector('.choice-colors')) {
            var element = document.querySelector('.choice-colors');
            const example = new Choices(element, {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false,
            });
        };

        if (document.querySelector('.btExport')) {
            var element = document.querySelector('.btExport');
            const example = new Choices(element, {
                searchEnabled: false,
                itemSelectText: '',
                shouldSort: false,
            });
        };
    </script>
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
    <script src="<?php echo e(asset('trader-assets/assets/js/soft-ui-dashboard.min.js?v=1.0.8')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/src/js/core/confirm-alert.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/pages/common-ajax.js')); ?>"></script>

    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')); ?>"></script>
    <!--Custom js added here--->
    <?php echo $__env->yieldContent('page-js'); ?>
    <script src="<?php echo e(asset('/common-js/custom-from-validation.js')); ?>"></script>
    <?php echo $__env->yieldContent('customjs'); ?>

    <!-- language change script -->
    <script>
        (function(window, document, $) {
            $(document).on('click', ".lang-change", function() {
                let lang = $(this).data('language');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/ib/change-language',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        lang: lang
                    },
                    success: function(data) {
                        if (data.status === true) {
                            location.reload();
                        }
                    }
                });
            });
        })(window, document, jQuery);
    </script>

</body>

</html><?php /**PATH I:\code\fxcrm\fx\crm-new-laravel\resources\views/layouts/ib-layout.blade.php ENDPATH**/ ?>