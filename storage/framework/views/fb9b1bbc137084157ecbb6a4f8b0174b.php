<!DOCTYPE html>
<?php
    use App\Services\checkSettingsService;
    use App\Services\PermissionService;
?>
<html class="loading <?php echo e(get_admin_theme()); ?>" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta http-equiv="refresh"
        content="<?php echo e(config('session.lifetime') * 1); ?>; <?php echo e(url('/admin/lock-screen' . '/' . base64_encode(auth()->user()->id) . '/' . base64_encode(url()->current()))); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description"
        content="<?php echo e(get_company_name()); ?> is a broker company focuses in Forex Trading. We believe in transparency, accountability, and accuracy of services. Experience trading in the most seamless way, straight to global market, and the easiness of withdrawal.">
    <meta name="keywords"
        content="<?php echo e(get_company_name()); ?> is operated by <?php echo e(get_company_name()); ?> and has registered in Saint Vincent & the Grenadines with LLC number 892 LLC 2021, regulated by the Financial Services Authority (FSA) of Saint Vincent and the Grenadines. High Risk Warning : Before you enter foreign exchange and stock markets, you have to remember that trading currencies and other investment products is trading in nature and always involves a considerable risk. As a result of various financial fluctuations, you may not only significantly increase your capital, but also lose it completely.">
    <meta name="author" content="<?php echo e(get_company_name()); ?>">
    <!-- csrf token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <!-- style sheet -->
    <title id="minutes"><?php echo e(strtoupper(config('app.name'))); ?> - <?php echo $__env->yieldContent('title'); ?> </title>
    <?php $themeColor = get_theme_colors_forAll('admin_theme') ?>
    <style>
        :root {
            --custom-primary: <?=$themeColor->primary_color ?? '#7367f0' ?>;
            --custom-form-color: <?=$themeColor->form_color ?? '#979fa6' ?>;
            --bs-body-color: <?=$themeColor->body_color ?? '#67748e' ?>;
        }
    </style>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(get_favicon_icon()); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(get_favicon_icon()); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/vendors.min.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('admin-assets/app-assets/vendors/css/charts/apexcharts.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('admin-assets/app-assets/vendors/css/extensions/toastr.min.css')); ?>">
    <?php echo $__env->yieldContent('vendor-css'); ?>
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/bootstrap.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/bootstrap-extended.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/colors.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/components.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/themes/dark-layout.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('admin-assets/app-assets/css/themes/bordered-layout.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('admin-assets/app-assets/css/themes/semi-dark-layout.css')); ?>">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/pages/ui-feather.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('admin-assets/app-assets/css/pages/dashboard-ecommerce.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('admin-assets/app-assets/css/plugins/charts/chart-apex.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('admin-assets/app-assets/css/plugins/extensions/ext-component-toastr.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('admin-assets/app-assets/css/plugins/extensions/shepherd.min.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('admin-assets/app-assets/css/plugins/extensions/shepherd.min.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('admin-assets/app-assets/css/plugins/extensions/ext-component-tour.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/quill/quill.snow.css')); ?>">
    <?php echo $__env->yieldContent('page-css'); ?>
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/assets/css/style.css')); ?>">
    <!-- END: Custom CSS-->
    <style>
        .flag-icon {
            margin-right: 5px;
        }

        .dataTables_info {
            display: inline-block;
        }

        .dataTables_paginate.paging_simple_numbers {
            display: inline-block;
            float: right;
            margin: .5rem !important;
        }

        div.dataTables_wrapper div.dataTables_paginate ul.pagination {
            margin-top: 0;
        }

        .data-list-page-item.dl-active {
            background-color: var(--custom-primary);
        }

        .al-error-solve {
            position: relative;
        }

        .al-error-solve .error-msg {
            position: absolute;
            bottom: -21px;
            left: 0;
        }

        .al-input-error-fixed {
            position: relative;
        }

        .al-input-error-fixed .error-msg {
            position: absolute;
            left: auto;
            bottom: -20px;
            z-index: 11;
        }

        .main-menu .navbar-header .navbar-brand {
            margin-top: 0.35rem !important;
        }

        /* Notification dropdown styling */
        .dropdown-notification .nav-link {
            position: relative;
        }

        .dropdown-notification .badge-up {
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 18px;
            height: 18px;
            font-size: 10px;
            line-height: 18px;
            text-align: center;
        }

        .dropdown-notification .dropdown-menu {
            min-width: 320px;
            max-width: 350px;
        }

        .dropdown-notification .dropdown-menu-header {
            border-bottom: 1px solid #e7e7ff;
        }

        .dropdown-notification .scrollable-container {
            max-height: 300px;
            overflow-y: auto;
        }

        .dropdown-notification .list-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f8f9fa;
        }

        .dropdown-notification .list-item:last-child {
            border-bottom: none;
        }

        .dropdown-notification .avatar {
            width: 32px;
            height: 32px;
        }

        .dropdown-notification .avatar-content {
            width: 100%;
            height: 100%;
        }

        /* Purple color for IB Registration Request notifications */
        .bg-purple {
            background-color: #6f42c1 !important;
        }
        .badge-light-purple {
            background-color: #f3e5f5 !important;
            color: #6f42c1 !important;
        }
        .btn-purple {
            background-color: #6f42c1 !important;
            border-color: #6f42c1 !important;
            color: #fff !important;
        }
        .btn-purple:hover {
            background-color: #5a32a3 !important;
            border-color: #5a32a3 !important;
            color: #fff !important;
        }
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dropdown-notification .avatar-icon {
            width: 16px;
            height: 16px;
        }

        .dropdown-notification .media-heading {
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .dropdown-notification .notification-text {
            font-size: 0.75rem;
            color: #6c757d;
        }

        /* Notification type specific colors */
        .bg-light-primary {
            background-color: #e3f2fd !important;
        }

        .bg-light-success {
            background-color: #e8f5e8 !important;
        }

        .bg-light-warning {
            background-color: #fff3cd !important;
        }

        .bg-light-info {
            background-color: #d1ecf1 !important;
        }

        .bg-light-secondary {
            background-color: #e2e3e5 !important;
        }

        .bg-light-danger {
            background-color: #f8d7da !important;
        }
    </style>
    <?php echo $__env->yieldContent('custom-css'); ?>
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click"
    data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    <nav
        class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-fluid">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon"
                                data-feather="menu"></i></a></li>
                </ul>
                <ul class="nav navbar-nav bookmark-icons">
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Home"><i class="ficon"
                                data-feather="home"></i></a></li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ms-auto">
                <!-- language change -->
                <li class="nav-item dropdown dropdown-language">
                    <a class="nav-link dropdown-toggle" id="dropdown-flag" href="#" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <?php
                            $language = \App\Services\systems\LanguageService::language();
                        ?>
                        <i class="flag-icon flag-icon-<?php echo e($language->flag); ?>"></i>
                        <span class="selected-language">
                            <?php echo e($language->lang); ?>

                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-flag">
                        <a class="dropdown-item lang-change" href="#" data-language="en"><i
                                class="flag-icon flag-icon-us"></i><?php echo e(__('language.english')); ?></a>
                        <a class="dropdown-item lang-change" href="#" data-language="fr"><i
                                class="flag-icon flag-icon-fr"></i> <?php echo e(__('language.french')); ?></a>
                        <a class="dropdown-item lang-change" href="#" data-language="de"><i
                                class="flag-icon flag-icon-de"></i> <?php echo e(__('language.german')); ?></a>
                        <a class="dropdown-item lang-change" href="#" data-language="pt"><i
                                class="flag-icon flag-icon-pt"></i> <?php echo e(__('language.portuguese')); ?></a>
                        <a class="dropdown-item lang-change" href="#" data-language="zh"><i
                                class="flag-icon flag-icon-cn"></i> <?php echo e(__('language.chinese')); ?></a>
                    </div>
                </li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon"
                            data-feather="moon"></i></a></li>
                <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon"
                            data-feather="search"></i></a>
                    <div class="search-input">
                        <div class="search-input-icon"><i data-feather="search"></i></div>
                        <input class="form-control input" type="text" placeholder="Search the menue"
                            tabindex="-1" data-search="menu">
                        <div class="search-input-close"><i data-feather="x"></i></div>
                        <ul class="search-list search-list-main"></ul>
                    </div>
                </li>
                <!-- Client Notifications -->
                <li class="nav-item dropdown dropdown-notification me-2">
                    <a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon"
                            data-feather="users"></i><span id="clientNotiBadge"
                            class="badge rounded-pill bg-primary badge-up"></span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">Client Notifications</h4>
                                <div class="badge rounded-pill badge-light-primary"><span id="clientNotiCount"></span>
                                    New</div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list">
                            <div id="client-notifications-list">
                                <!-- Loaded by AJAX -->
                            </div>
                        </li>
                        <li class="dropdown-menu-footer">
                            <a class="btn btn-primary w-100"
                                href="<?php echo e(route('admin.system-notification.view-all')); ?>?type=client">View All Client</a>
                        </li>
                    </ul>
                </li>

                <!-- Deposit Notifications -->
                <li class="nav-item dropdown dropdown-notification me-2">
                    <a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon"
                            data-feather="plus-circle"></i><span id="depositNotiBadge"
                            class="badge rounded-pill bg-success badge-up"></span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">Deposit Notifications</h4>
                                <div class="badge rounded-pill badge-light-success"><span id="depositNotiCount"></span>
                                    New</div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list">
                            <div id="deposit-notifications-list">
                                <!-- Loaded by AJAX -->
                            </div>
                        </li>
                        <li class="dropdown-menu-footer">
                            <a class="btn btn-success w-100"
                                href="<?php echo e(route('admin.system-notification.view-all')); ?>?type=deposit">View All Deposits</a>
                        </li>
                    </ul>
                </li>

                <!-- Withdrawal Notifications -->
                <li class="nav-item dropdown dropdown-notification me-2">
                    <a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon"
                            data-feather="minus-circle"></i><span id="withdrawalNotiBadge"
                            class="badge rounded-pill bg-warning badge-up"></span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">Withdrawal Notifications</h4>
                                <div class="badge rounded-pill badge-light-warning"><span id="withdrawalNotiCount"></span>
                                    New</div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list">
                            <div id="withdrawal-notifications-list">
                                <!-- Loaded by AJAX -->
                            </div>
                        </li>
                        <li class="dropdown-menu-footer">
                            <a class="btn btn-warning w-100"
                                href="<?php echo e(route('admin.system-notification.view-all')); ?>?type=withdraw">View All Withdrawals</a>
                        </li>
                    </ul>
                </li>

                <!-- Transfer Notifications -->
                <li class="nav-item dropdown dropdown-notification me-2">
                    <a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon"
                            data-feather="repeat"></i><span id="transferNotiBadge"
                            class="badge rounded-pill bg-info badge-up"></span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">Transfer Notifications</h4>
                                <div class="badge rounded-pill badge-light-info"><span id="transferNotiCount"></span>
                                    New</div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list">
                            <div id="transfer-notifications-list">
                                <!-- Loaded by AJAX -->
                            </div>
                        </li>
                        <li class="dropdown-menu-footer">
                            <a class="btn btn-info w-100"
                                href="<?php echo e(route('admin.system-notification.view-all')); ?>?type=transfer">View All Transfers</a>
                        </li>
                    </ul>
                </li>

                <!-- Account Management Notifications -->
                <li class="nav-item dropdown dropdown-notification me-2">
                    <a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon"
                            data-feather="settings"></i><span id="accountNotiBadge"
                            class="badge rounded-pill bg-secondary badge-up"></span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">Account Management</h4>
                                <div class="badge rounded-pill badge-light-secondary"><span id="accountNotiCount"></span>
                                    New</div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list">
                            <div id="account-notifications-list">
                                <!-- Loaded by AJAX -->
                            </div>
                        </li>
                        <li class="dropdown-menu-footer">
                            <a class="btn btn-secondary w-100"
                                href="<?php echo e(route('admin.system-notification.view-all')); ?>?type=account">View All Account</a>
                        </li>
                    </ul>
                </li>

                <!-- IB Registration Request Notifications -->
                <li class="nav-item dropdown dropdown-notification me-2">
                    <a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon"
                            data-feather="user-plus"></i><span id="ibRequestNotiBadge"
                            class="badge rounded-pill bg-purple badge-up"></span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">IB Registration Request</h4>
                                <div class="badge rounded-pill badge-light-purple"><span id="ibRequestNotiCount"></span>
                                    New</div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list">
                            <div id="ib-request-notifications-list">
                                <!-- Loaded by AJAX -->
                            </div>
                        </li>
                        <li class="dropdown-menu-footer">
                            <a class="btn btn-purple w-100"
                                href="<?php echo e(route('admin.combine-ib-request')); ?>">View All IB Requests</a>
                        </li>
                    </ul>
                </li>

                <!-- Bank Account List Notifications -->
                <li class="nav-item dropdown dropdown-notification me-2">
                    <a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon"
                            data-feather="credit-card"></i><span id="bankAccountNotiBadge"
                            class="badge rounded-pill bg-info badge-up"></span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">Bank Account List</h4>
                                <div class="badge rounded-pill badge-light-info"><span id="bankAccountNotiCount"></span>
                                    New</div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list">
                            <div id="bank-account-notifications-list">
                                <!-- Bank Account notifications will be loaded here -->
                            </div>
                        </li>
                        <li class="dropdown-menu-footer">
                            <a class="btn btn-info w-100" href="<?php echo e(route('admin.manage_banks.bank_account_list')); ?>">View All Bank Accounts</a>
                        </li>
                    </ul>
                </li>

                <!-- System Notifications (Original) -->
                <li class="nav-item dropdown dropdown-notification me-25">
                    <a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon"
                            data-feather="bell"></i><span id="notiBel"
                            class="badge rounded-pill bg-danger badge-up"></span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">System Notifications</h4>
                                <div class="badge rounded-pill badge-light-danger"><span id="notiBelBottom"></span>
                                    New</div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list">
                            <div id="system-notificion">
                                <!-- load by ajax -->
                            </div>
                        </li>
                        <li class="dropdown-menu-footer">
                            <a class="btn btn-danger w-100"
                                href="<?php echo e(route('admin.system-notification.view-all')); ?>?type=system">View All System</a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown dropdown-user">
                    <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none">
                            <span class="user-name fw-bolder"><?php echo e(config('app.name')); ?></span>
                            <span class="user-status"><?php echo e(__('admin-menue-left.' . auth()->user()->type)); ?></span>
                        </div>
                        <span class="avatar">
                            <img class="round bg-gradient-primary" src="<?php echo e(asset(avatar())); ?>" alt="avatar"
                                height="40" width="40">
                            <span class="avatar-status-online"></span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                        <a class="dropdown-item" href="<?php echo e(route('admin.profile-settings')); ?>">
                            <i class="me-50" data-feather="user"></i>
                            Profile
                        </a>

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                class="me-50" data-feather="power"></i> Logout</a>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST"
                            style="display: none;">
                            <?php echo csrf_field(); ?>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <ul class="main-search-list-defaultlist d-none">
        <li class="d-flex align-items-center"><a href="#">
                <h6 class="section-label mt-75 mb-0">Files</h6>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                href="app-file-manager.html">
                <div class="d-flex">
                    <div class="me-75"><img src="<?php echo e(asset('admin-assets/app-assets/images/icons/xls.png')); ?>"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Two new item submitted</p><small
                            class="text-muted">Marketing Manager</small>
                    </div>
                </div><small class="search-data-size me-50 text-muted">&apos;17kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                href="app-file-manager.html">
                <div class="d-flex">
                    <div class="me-75"><img src="<?php echo e(asset('admin-assets/app-assets/images/icons/jpg.png')); ?>"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">52 JPG file Generated</p><small class="text-muted">FontEnd
                            Developer</small>
                    </div>
                </div><small class="search-data-size me-50 text-muted">&apos;11kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                href="app-file-manager.html">
                <div class="d-flex">
                    <div class="me-75"><img src="<?php echo e(asset('admin-assets/app-assets/images/icons/pdf.png')); ?>"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">25 PDF File Uploaded</p><small class="text-muted">Digital
                            Marketing Manager</small>
                    </div>
                </div><small class="search-data-size me-50 text-muted">&apos;150kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                href="app-file-manager.html">
                <div class="d-flex">
                    <div class="me-75"><img src="<?php echo e(asset('admin-assets/app-assets/images/icons/doc.png')); ?>"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Anna_Strong.doc</p><small class="text-muted">Web
                            Designer</small>
                    </div>
                </div><small class="search-data-size me-50 text-muted">&apos;256kb</small>
            </a></li>
        <li class="d-flex align-items-center"><a href="#">
                <h6 class="section-label mt-75 mb-0">Members</h6>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                href="app-user-view-account.html">
                <div class="d-flex align-items-center">
                    <div class="avatar me-75"><img
                            src="<?php echo e(asset('admin-assets/app-assets/images/portrait/small/avatar-s-8.jpg')); ?>"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">John Doe</p><small class="text-muted">UI
                            designer</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                href="app-user-view-account.html">
                <div class="d-flex align-items-center">
                    <div class="avatar me-75"><img
                            src="<?php echo e(asset('admin-assets/app-assets/images/portrait/small/avatar-s-1.jpg')); ?>"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Michal Clark</p><small class="text-muted">FontEnd
                            Developer</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                href="app-user-view-account.html">
                <div class="d-flex align-items-center">
                    <div class="avatar me-75"><img
                            src="<?php echo e(asset('admin-assets/app-assets/images/portrait/small/avatar-s-14.jpg')); ?>"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Milena Gibson</p><small class="text-muted">Digital
                            Marketing Manager</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                href="app-user-view-account.html">
                <div class="d-flex align-items-center">
                    <div class="avatar me-75"><img
                            src="<?php echo e(asset('admin-assets/app-assets/images/portrait/small/avatar-s-6.jpg')); ?>"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Anna Strong</p><small class="text-muted">Web
                            Designer</small>
                    </div>
                </div>
            </a></li>
    </ul>
    <ul class="main-search-list-defaultlist-other-list d-none">
        <li class="auto-suggestion justify-content-between"><a
                class="d-flex align-items-center justify-content-between w-100 py-50">
                <div class="d-flex justify-content-start"><span class="me-75"
                        data-feather="alert-circle"></span><span>No results found.</span></div>
            </a></li>
    </ul>
    <!-- END: Header-->

    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item me-auto" style="width:76%">
                    <a class="navbar-brand" href="#">
                        <span class="brand-logo">
                            <img class="img img-fluid" src="<?php echo e(get_admin_logo()); ?>" alt="<?php echo e(config('app.name')); ?>"
                                style="max-width:100%">
                        </span>
                    </a>
                </li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0"
                        data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4"
                            data-feather="x"></i><i
                            class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary"
                            data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="<?php echo e(Request::is('admin/dashboard') ? 'active' : ''); ?>" id="mainMenuLi">
                    <a class="d-flex align-items-center" href="<?php echo e(route('admin.dashboard')); ?>">
                        <i data-feather="home"></i>
                        <span class="menu-item text-truncate"
                            data-i18n="Configuration"><?php echo e(__('page.dashboard')); ?></span>
                    </a>
                </li>
                <?php if(auth()->user()->type === "manager"): ?>
                <li class="<?php echo e(Request::is('manager/dashboard') ? 'active' : ''); ?>">
                    <a class="d-flex align-items-center" href="<?php echo e(route('manager.dashboard')); ?>">
                        <i data-feather="home"></i>
                        <span class="menu-item text-truncate" data-i18n="Manager Analysis"><?php echo e(__('Manager Analysis')); ?></span>
                    </a>
                </li>
                <?php endif; ?>
                <!-- Admin profile -->
                <?php if(PermissionService::has_permission('admin_profile', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin profile')): ?>
                        <li class=" nav-item">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather='user-check'></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="Admin-Profile"><?php echo e(__('page.admin_profile')); ?></span>
                                <!-- <span class="badge badge-light-warning rounded-pill ms-auto me-1">1</span> -->
                                <span id="notiDashboard"
                                    class="badge badge-light-warning rounded-pill ms-auto me-1"></span>
                            </a>
                            <ul class="menu-content">
                                <!-- profile change -->
                                <?php if(PermissionService::has_permission('change_profile', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'change profile')): ?>
                                        <li class="<?php echo e(Request::is('admin/profile/*') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.profile-settings')); ?>"><i
                                                    data-feather="circle"></i><span class="menu-item text-truncate"
                                                    data-i18n="Admin-Profile"><?php echo e(__('page.change_profile')); ?></span></a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- notifiction -->
                                <?php if(PermissionService::has_permission('notification', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'notification')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/allNotification/allNotification*') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.allNotification.allNotification')); ?>">
                                                <i data-feather="bell"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="eCommerce"><?php echo e(__('page.notifications')); ?> &nbsp;
                                                    <span class="badge badge-light-warning rounded-pill ms-auto me-1"
                                                        id="allNotification"></span>
                                                </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- Manage Client -->
                <?php if(PermissionService::has_permission('manage_client', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'manage client')): ?>
                        <li class=" nav-item <?php echo e(Request::is('admin/client-management/*') ? 'open' : ''); ?>">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather="users"></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="Manage Client"><?php echo e(__('admin-menue-left.client_management')); ?></span>
                            </a>
                            <ul class="menu-content">
                                <!-- trader admin -->
                                <?php if(PermissionService::has_permission('trader_admin', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'trader admin')): ?>
                                        <li class="<?php echo e(Request::is('admin/client-management/trader-admin') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.trader-admin')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Basic"><?php echo e(__('admin-menue-left.trader_admin')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <!-- lead admin -->
                                <?php if(PermissionService::has_permission('trader_admin', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'trader admin')): ?>
                                        <li class="<?php echo e(Request::is('admin/client-management/lead-admin') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.lead-admin')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Basic">Lead Admin</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- trader analysis -->
                                <!-- trader analysis -->
                                <?php if(PermissionService::has_permission('trader_analysis', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'trader analysis')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/client-management/trader-analysis') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.trader-analysis')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Basic">Trader Analysis</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php if(PermissionService::has_permission('trader_analysis', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'trader analysis')): ?>
                                        <li
                                        class="<?php echo e(Request::is('admin/client-management/special-customer') ? 'active' : ''); ?>">
                                        <a class="d-flex align-items-center" href="<?php echo e(route('admin.trader-special-customer')); ?>">
                                            <i data-feather="circle"></i>
                                            <span class="menu-item text-truncate" data-i18n="Basic">Special Client</span>
                                        </a>
                                    </li>
                                    
                                     <!-- assign group -->
                                <li class="<?php echo e(Request::is('admin/client-management/assign-group') ? 'active' : ''); ?>">
                                    <a class="d-flex align-items-center" href="<?php echo e(route('admin.assign-group')); ?>">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="Basic">Assign Group</span>
                                    </a>
                                </li>
                                <li class="<?php echo e(Request::is('admin/client-management/manager-groups') ? 'active' : ''); ?>">
                                    <a class="d-flex align-items-center" href="<?php echo e(route('admin.client-management.manager-groups')); ?>">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="Basic">Manager Groups</span>
                                    </a>
                                </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- IB Management -->
                <?php if(PermissionService::has_permission('ib_management', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'ib management')): ?>
                        <li class=" nav-item <?php echo e(Request::is('admin/ib-management/*') ? 'open' : ''); ?>">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather='package'></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="IB Management"><?php echo e(__('admin-menue-left.ib_management')); ?></span>
                            </a>
                            <ul class="menu-content">
                                <!-- ib setup -->
                                <?php if(PermissionService::has_permission('ib_setup', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'ib setup')): ?>
                                        <li class="<?php echo e(Request::is('admin/ib-management/ib-setup') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.ib-setup-view')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="IB Setup"><?php echo e(__('admin-menue-left.ib_setup')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- ib commission structure -->
                                <?php if(PermissionService::has_permission('ib_commission_structure', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'ib commission structure')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/ib-management/ib-commission-structure') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.ib-commission-structure')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="IB Commission Structure"><?php echo e(__('admin-menue-left.ib_commission_structure')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- ib tree -->
                                <?php if(PermissionService::has_permission('ib_tree', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'ib tree')): ?>
                                        <li class="<?php echo e(Request::is('admin/ib-management/ib-tree') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.ib-tree')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="IB Tree"><?php echo e(__('admin-menue-left.ib_tree')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- master ib -->
                                <?php if(PermissionService::has_permission('master_ib', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'master ib')): ?>
                                        <li class="<?php echo e(Request::is('admin/ib_management/master_ib_report') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.ib_management.master_ib_report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Master IB">
                                                    <?php echo e(__('admin-menue-left.Master_IB')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- pending commission -->
                                <?php if(PermissionService::has_permission('pending_commission_list', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'panding commission list')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/ib_management/pending_commission_list') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.ib_management.pending_commission_list')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Pending Commission List">
                                                    <?php echo e(__('admin-menue-left.Pending_Commission_List')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- no commission list -->
                                <?php if(PermissionService::has_permission('no_commission_list', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'no commission list')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/ib_management/no_commission_list') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.ib_management.no_commission_list')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="No Commission List">
                                                    <?php echo e(__('admin-menue-left.No_Commission_List')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- ib chain -->
                                <?php if(PermissionService::has_permission('ib_chain', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'ib-chain')): ?>
                                        <li class="<?php echo e(Request::is('admin/ib-management/ib-chain') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.ib_management.ib_chain')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="IB Chain">
                                                    <?php echo e(__('admin-menue-left.IB_Chain')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- ib admin -->
                                <?php if(PermissionService::has_permission('ib_admin', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'ib admin')): ?>
                                        <li class="<?php echo e(Request::is('admin/ib-management/ib-admin-report') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('ib.admin.report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="IB Admin"><?php echo e(__('admin-menue-left.ib_admin')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- ib verification request -->
                                <?php if(PermissionService::has_permission('ib_verification_request', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'ib verification request')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/ib-management/ib-verification-request') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.verification.request-report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="IB Verification Request"><?php echo e(__('admin-menue-left.ib_verification_request')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- ib analysis -->
                                <?php if(PermissionService::has_permission('ib_analysis', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'ib analysis')): ?>
                                        <li class="<?php echo e(Request::is('admin/ib-management/ib-analysis') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.ib-analysis')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="IB Verification Analysis"><?php echo e(__('admin-menue-left.ib_analysis')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- ib request for combine -->
                                
                                        <li class="<?php echo e(Request::is('admin/ib-management/ib-request') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.combine-ib-request')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="IB Verification Analysis">IB
                                                    Registration Request</span>
                                            </a>
                                        </li>
                                
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- Manage Request -->
                <?php if(PermissionService::has_permission('manage_request', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'manage request')): ?>
                        <li class=" nav-item <?php echo e(Request::is('admin/manage-report/*') ? 'open' : ''); ?> ">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather="copy"></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="Request Management"><?php echo e(__('admin-menue-left.request_management')); ?></span>
                            </a>
                            <ul class="menu-content">
                                <!-- deposit request -->
                                <?php if(PermissionService::has_permission('deposit_request', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'deposit request')): ?>
                                        <li class="<?php echo e(Request::is('admin/manage-report/deposit-request') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.manage.deposit')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Deposit Request"><?php echo e(__('admin-menue-left.deposit_request')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- withdraw request -->
                                <?php if(PermissionService::has_permission('withdraw_request', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'withdraw request')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/manage-report/withdraw-request') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.manage.withdraw')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Withdraw Request"><?php echo e(__('admin-menue-left.withdraw_request')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- account request -->
                                <?php if(PermissionService::has_permission('account_request', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'account request')): ?>
                                        <li class="<?php echo e(Request::is('admin/manage-report/account-request') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.account-request')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Withdraw Request"><?php echo e(__('admin-menue-left.account_request')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- balance transfer -->
                                <?php if(PermissionService::has_permission('balance_transfer_request', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'balance transfer')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/manage-report/balance-transfer') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.balance-transfer')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Balance Transfer"><?php echo e(__('admin-menue-left.balance_transfer')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- ib transfer request -->
                                <?php if(PermissionService::has_permission('ib_transfer_request', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'ib transfer')): ?>
                                        <li class="<?php echo e(Request::is('admin/manage-report/ib-transfer') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.ib-transfer')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="IB Transfer"><?php echo e(__('admin-menue-left.ib_transfer')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- ib withdraw request -->
                                <?php if(PermissionService::has_permission('ib_withdraw_request', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'ib withdraw request')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/manage-report/ib-withdraw-request') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.ib-transfer.withdraw')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="IB Withdraw Request"><?php echo e(__('admin-menue-left.ib_withdraw_request')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- all reports -->
                <?php if(PermissionService::has_permission('reports', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'reports')): ?>
                        <li class="check_height nav-item <?php echo e(Request::is('admin/report/*') ? 'open' : ''); ?>">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather='sliders'></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="Reports"><?php echo e(__('admin-menue-left.reports')); ?></span>
                            </a>
                            <ul class="menu-content">
                                <!-- ib withdraw -->
                                <?php if(PermissionService::has_permission('ib_withdraw', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'ib withdraw')): ?>
                                        <li class="<?php echo e(Request::is('admin/report/withdraw/ib') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(url('admin/report/withdraw/ib')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="IB Withdraw"><?php echo e(__('admin-menue-left.ib_withdraw')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- ib commission report -->
                                <?php if(PermissionService::has_permission('ib_commission', 'admin')): ?>
                                    <li class="<?php echo e(Request::is('admin/report/ib-commission') ? 'active' : ''); ?>">
                                        <a class="d-flex align-items-center"
                                            href="<?php echo e(url('admin/report/ib-commission')); ?>">
                                            <i data-feather="circle"></i>
                                            <span class="menu-item text-truncate"
                                                data-i18n="IB Commission"><?php echo e(__('admin-menue-left.ib_commission')); ?></span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <!-- trader withdraw -->
                                <?php if(PermissionService::has_permission('trader_withdraw', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'trader withdraw')): ?>
                                        <li class="<?php echo e(Request::is('admin/report/withdraw/trader') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(url('admin/report/withdraw/trader')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Trader Withdraw"><?php echo e(__('admin-menue-left.trader_withdraw')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- block user -->
                                <?php if(PermissionService::has_permission('blocked_users', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'blocked user')): ?>
                                        <li class="<?php echo e(Request::is('admin/report/blocked_user') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(url('admin/report/blocked_user')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Blocked User">Blocked
                                                    Users</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- activity log -->
                                <?php if(PermissionService::has_permission('activity_log', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'activity log')): ?>
                                        <li class="<?php echo e(Request::is('admin/report/activity-log') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.activity-log')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Activity log"><?php echo e(__('admin-menue-left.activity_log')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- trader deposit report -->
                                <?php if(PermissionService::has_permission('trader_deposit', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'trader deposit report')): ?>
                                        <li class="<?php echo e(Request::is('admin/report/trader-deposit') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.trader-report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Trader Deposit">Trader
                                                    deposit</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- bonus report -->
                                <?php if(PermissionService::has_permission('bonus_report', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'bonus report')): ?>
                                        <li class="<?php echo e(Request::is('admin/report/user-bonus-report') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.user.bonus-report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Basic">
                                                    <?php echo e(__('admin-menue-left.Bonus_Report')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- ib fund transfer -->
                                <?php if(PermissionService::has_permission('ib_fund_transfer', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'ib fund transfer')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/report/ib-fund-transfer-report') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.user.ib-fund-transfer')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="IB Fund Transfer"><?php echo e(__('admin-menue-left.ib_fund_transfer')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- External Fund Transfer -->
                                <?php if(PermissionService::has_permission('external_fund_transfer', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'external fund transfer')): ?>
                                        <li class="<?php echo e(Request::is('admin/report/external-fund-transfer') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.external-fund-transfer-report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="IB Fund Transfer">External
                                                    Fund Transfer</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- IB Balance Add -->
                                <?php if(PermissionService::has_permission('balance_upload_and_deduction', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'balance upload report')): ?>
                                        <li class="<?php echo e(Request::is('admin/report/ib-balance-add-report') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('ib.balance.report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Balance Upload and Deduction">IB Balance Add</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- ledger report -->
                                <?php if(PermissionService::has_permission('security_settings', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'ledger report')): ?>
                                        <li class="<?php echo e(Request::is('admin/report/ledger-report') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.report.ledger')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Ledger Report"><?php echo e(__('admin-menue-left.ledger_report')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- individual ledger report -->
                                <?php if(PermissionService::has_permission('individual_ledger_report', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'individual ledger report')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/report/individual-ledger-report') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.report.ledger-individual')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="individual Ledger Report"><?php echo e(__('admin-menue-left.individual_ledger_report')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>

                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- ENDING REPORT MANAGEMENT -->
                <!-- START Manage Admin -->
                <?php if(PermissionService::has_permission('manage_admin', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'manage admin')): ?>
                        <li class=" nav-item <?php echo e(Request::is('admin/admin-management/*') ? 'open' : ''); ?>">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather="users"></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="Admin Management"><?php echo e(__('admin-menue-left.admin_management')); ?></span>
                            </a>
                            <ul class="menu-content">
                                <!-- admin group -->
                                <?php if(PermissionService::has_permission('admin_groups', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin groups')): ?>
                                        <li class="<?php echo e(Request::is('admin/admin-management/admin-groups') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.admin-groups')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Admin Groups"><?php echo e(__('admin-menue-left.admin_groups')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- admin registration -->
                                <?php if(PermissionService::has_permission('admin_registration', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin registration')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/admin-management/admin-registration') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.admin-registration')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Admin Registration"><?php echo e(__('admin-menue-left.admin_registration')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- admin right management -->
                                <?php if(PermissionService::has_permission('admin_right_management', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin right management')): ?>
                                        <li class="<?php echo e(Request::is('admin/admin-management/roles') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.roles')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Admin Right Management"><?php echo e(__('admin-menue-left.admin_right')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- ENDING ADMIN MANAGEMENT -->
                <!-- START: Manager settings -->
                <?php if(PermissionService::has_permission('manager_settings', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'manager settings')): ?>
                        <li class=" nav-item <?php echo e(Request::is('admin/manager-settings/*') ? 'open' : ''); ?>">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather="users"></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="Manager Settings"><?php echo e(__('admin-menue-left.manager_settings')); ?></span>
                            </a>
                            <ul class="menu-content">
                                <!-- manager groups -->
                                <?php if(PermissionService::has_permission('manager_groups', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'manager groups')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/manager-settings/manager-group') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.manager-groups')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Manager Groups"><?php echo e(__('admin-menue-left.manager_groups')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- add manager -->
                                <?php if(PermissionService::has_permission('add_manager', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'add manager')): ?>
                                        <li class="<?php echo e(Request::is('admin/manager-settings/add-manager') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.add-manager')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Add Manager"><?php echo e(__('admin-menue-left.add_manager')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- manager list -->
                                <?php if(PermissionService::has_permission('manager_list', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'manager list')): ?>
                                        <li class="<?php echo e(Request::is('admin/manager-settings/get-manager') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.get-manager')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Manager List"><?php echo e(__('admin-menue-left.manager_list')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- manager right -->
                                <?php if(PermissionService::has_permission('manager_right', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'manager right')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/manager-settings/manager-right') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.all-manager-with-right')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Manager Right"><?php echo e(__('admin-menue-left.manager_right')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- manager analysis -->
                                <?php if(PermissionService::has_permission('manager_analysis', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'manager analysis')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/manager-settings/manager-analysis') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.manager-analysis-view')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Manager Analysis"><?php echo e(__('admin-menue-left.manager_analysis')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- manage all -->
                                <?php if(PermissionService::has_permission('manager_settings', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'manage all')): ?>  
                                        <li class="<?php echo e(Request::is('admin/manager-settings/manage-all') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.manage-all')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Manage All">Manage All</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- ENDING MANAGER SETTINGS -->
                <!-- START: Finance -->
                <?php if(PermissionService::has_permission('finance', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'finance')): ?>
                        <li class=" nav-item <?php echo e(Request::is('admin/finance/*') ? 'open' : ''); ?>">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather='dollar-sign'></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="Finance"><?php echo e(__('admin-menue-left.finance')); ?></span>
                            </a>
                            <ul class="menu-content">
                                <!-- balance management -->
                                <?php if(PermissionService::has_permission('balance_management', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'balance management')): ?>
                                        <li class="<?php echo e(Request::is('admin/finance/balance-management') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.finance-balance')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Balance Management"><?php echo e(__('admin-menue-left.balance_management')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- credit management -->
                                <?php if(PermissionService::has_permission('credit_management', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'credit management')): ?>
                                        <li class="<?php echo e(Request::is('admin/finance/credit-management') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.finance-credit')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Credit Management"><?php echo e(__('admin-menue-left.credit_management')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- finance reports -->
                                <?php if(PermissionService::has_permission('fund_management', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'fund management')): ?>
                                        <li class="<?php echo e(Request::is('admin/finance/fund-management') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.finance-fund-management')); ?>">
                                                <i data-feather="circle"></i><span class="menu-item text-truncate"
                                                    data-i18n="Fund Management"><?php echo e(__('admin-menue-left.fund_management')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- Admin Deposit reports -->
                                <?php if(PermissionService::has_permission('admin_deposit', 'admin')): ?>
                                    <li class="<?php echo e(Request::is('admin/finance/deposit-report') ? 'active' : ''); ?>">
                                        <a class="d-flex align-items-center" href="<?php echo e(route('admin.deposit-report')); ?>">
                                            <i data-feather="circle"></i>
                                            <span class="menu-item text-truncate" data-i18n="Finance Reports">Admin
                                                Deposit</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <!-- Admin Withdraw reports -->
                                <?php if(PermissionService::has_permission('admin_withdraw', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin withdraw')): ?>
                                        <li class="<?php echo e(Request::is('admin/finance/withdraw-report') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.withdraw-report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Finance Reports">Admin
                                                    Withdraw</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- END: finance -->
                <!-- START fund transfer -->
                <?php if(PermissionService::has_permission('fund_transfer', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'fund transfer')): ?>
                        <li class="check_height nav-item <?php echo e(Request::is('admin/fund/*') ? 'open' : ''); ?> ">
                            <a class="d-flex align-items-center" id="height_check" href="#">
                                <i data-feather='credit-card'></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="Fund Transfer"><?php echo e(__('admin-menue-left.fund_trasfer')); ?></span>
                            </a>
                            <ul class="menu-content">
                                <!-- internal fund transfer -->
                                <?php if(PermissionService::has_permission('internal_fund_transfer', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'internal fund transfer')): ?>
                                        <li class="<?php echo e(Request::is('admin/fund/internal-fund-transfer') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.fund-report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Internal Fund Transfer"><?php echo e(__('admin-menue-left.internal_transfer')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- external fund transfer -->
                                <?php if(PermissionService::has_permission('external_fund_transfer', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'external fund transfer')): ?>
                                        <li class="<?php echo e(Request::is('admin/fund/external-fund-transfer') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.external-fund-report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="External Fund Transfer"><?php echo e(__('admin-menue-left.external_transfer')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- group settings -->
                <?php if(PermissionService::has_permission('group_settings', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'group settings')): ?>
                        <li class="check_height nav-item"><a class="d-flex align-items-center" href="#">
                                <i data-feather='tool'></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="Group Settings"><?php echo e(__('admin-menue-left.group_settins')); ?></span>
                            </a>
                            <ul class="menu-content">
                                <!-- group manager -->
                                <?php if(PermissionService::has_permission('group_manager', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'group manager')): ?>
                                        <li class="<?php echo e(Request::is('admin/client-groups/create') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(url('admin/client-groups/create')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Group Manager"><?php echo e(__('admin-menue-left.group_manager')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- group list -->
                                <?php if(PermissionService::has_permission('group_list', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'group list')): ?>
                                        <li class="<?php echo e(Request::is('admin/client-groups') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(url('admin/client-groups')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Group List"><?php echo e(__('admin-menue-left.group_list')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- manage ib group -->
                                <?php if(PermissionService::has_permission('manage_ib_group', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'manage ib group')): ?>
                                        <li class="<?php echo e(Request::is('admin/ib-groups') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(url('admin/ib-groups')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Manage IB Group"><?php echo e(__('admin-menue-left.manage_ib_group')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Start: KYC Managemnt -->
                <?php if(PermissionService::has_permission('kyc_management', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'kyc management')): ?>
                        <li class=" nav-item <?php echo e(Request::is('admin/kyc-management/*') ? 'open' : ''); ?> ">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather='archive'></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="KYC Management"><?php echo e(__('admin-menue-left.kyc_management')); ?></span>
                            </a>
                            <ul class="menu-content">
                                <!-- kyc upload -->
                                <?php if(PermissionService::has_permission('kyc_upload', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'kyc upload')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/kyc-management/kyc-upload-view') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.kyc-upload-view')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="KYC Upload"><?php echo e(__('admin-menue-left.kyc_upload')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- kyc reports -->
                                <?php if(PermissionService::has_permission('kyc_reports', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'kyc reports')): ?>
                                        <li class="<?php echo e(Request::is('admin/kyc-management/kyc-report') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('kyc.management.report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="KYC Reports"><?php echo e(__('admin-menue-left.kyc_reports')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- kyc request -->
                                <?php if(PermissionService::has_permission('kyc_request', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'kyc request')): ?>
                                        <li class="<?php echo e(Request::is('admin/kyc-management/kyc-request') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('kyc.management.request')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="KYC Request"><?php echo e(__('admin-menue-left.kyc_request')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>


                <!-- manage trade -->
                <?php if(PermissionService::has_permission('manage_trade', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'manage trade')): ?>
                        <li class=" nav-item <?php echo e(Request::is('admin/manage-trade/*') ? 'open' : ''); ?>">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather="users"></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="Manage Trade"><?php echo e(__('admin-menue-left.manage_trade')); ?></span>
                            </a>
                            <ul class="menu-content">
                                <!-- trading report -->
                                <?php if(PermissionService::has_permission('trading_report', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'trading trade report')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/manage-trade/trading-trade-report') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.trading-trade-report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Basic"><?php echo e(__('admin-menue-left.trading_report')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- trade commission -->
                                <?php if(PermissionService::has_permission('trade_commission', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'trade commission status')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/manage-trade/trade-commission-status') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.trade-commission-status')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Basic">Commission
                                                    Status</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- END: manager settings -->
                <!-- START: manage accounts -->
                <?php if(PermissionService::has_permission('manage_accounts', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'manage accounts')): ?>
                        <li class=" nav-item"><a class="d-flex align-items-center" href="#">
                                <i data-feather="grid"></i>
                                <span class="menu-title text-truncate" data-i18n="Manage Accounts">
                                    <?php echo e(__('admin-menue-left.Manage_Accounts')); ?>

                                </span>
                            </a>
                            <ul class="menu-content">
                                <!-- live trading account -->
                                <?php if(PermissionService::has_permission('live_trading_account', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'live account')): ?>
                                        <li class="<?php echo e(Request::is('admin/trading-account-details-live') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(url('admin/trading-account-details-live')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Live Trading Account List">
                                                    <?php echo e(__('admin-menue-left.live_account')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if(PermissionService::has_permission('demo_trading_account', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'demo account')): ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                        <!-- demo trading account -->
                                        <li class="<?php echo e(Request::is('admin/trading-account-details-demo') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(url('admin/trading-account-details-demo')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Demo Trading Account List">
                                                    <?php echo e(__('admin-menue-left.demo_account')); ?> </span>
                                            </a>
                                        </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- END: manage accounts -->
                <!-- START: manage bank accounts -->
                <?php if(PermissionService::has_permission('manage_banks', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'manage banks')): ?>
                        <li class=" nav-item"><a class="d-flex align-items-center" href="#">
                                <i data-feather="grid"></i>
                                <span class="menu-title text-truncate" data-i18n="Manage Banks">
                                    <?php echo e(__('admin-menue-left.Manage_Banks')); ?>

                                </span>
                            </a>
                            <ul class="menu-content">
                                <!-- bank account list -->
                                <?php if(PermissionService::has_permission('bank_account_list', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'bank account list')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/manage_banks/bank_account_list') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.manage_banks.bank_account_list')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Bank Account List">
                                                    <?php echo e(__('admin-menue-left.Bank_Account_List')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- company bank list -->
                                <?php if(PermissionService::has_permission('company_bank_list', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'company bank list')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/manage_banks/company-bank-list') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.company-bank-list')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Company Bank List">
                                                    Company Bank List
                                                </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- END: manage bank accounts -->

                <!-- START Offer -->
                <?php if(PermissionService::has_permission('offers', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'offers')): ?>
                        <li class="check_height nav-item <?php echo e(Request::is('admin/voucher/*') ? 'open' : ''); ?>">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather='award'></i>
                                <span class="menu-title text-truncate" data-i18n="Offers">
                                    <?php echo e(__('admin-menue-left.Offers')); ?> </span>
                            </a>
                            <ul class="menu-content">
                                <!-- voucher generate -->
                                <?php if(PermissionService::has_permission('voucher_generate', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'voucher generate')): ?>
                                        <li class="<?php echo e(Request::is('admin/voucher') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.voucher.show')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Voucher Generate">
                                                    <?php echo e(__('admin-menue-left.Voucher_Generate')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- voucher report -->
                                <?php if(PermissionService::has_permission('voucher_report', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'voucher report')): ?>
                                        <li class="<?php echo e(Request::is('admin/voucher/voucher-report') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.voucher.report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Voucher Generate">
                                                    <?php echo e(__('admin-menue-left.Voucher_Report')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!--bonus navbar here-->
                                <?php if(PermissionService::has_permission('create_bonus', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'create bonus')): ?>
                                        <li class="<?php echo e(Request::is('admin/bonus/create-bonus') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.bonus.create')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Create Bonus">
                                                    Create Bonus </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- bonus list -->
                                <?php if(PermissionService::has_permission('bonus_list', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'bonus list')): ?>
                                        <li class="<?php echo e(Request::is('admin/bonus/bonus-list') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.bonus.list')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Create Bonus">
                                                    Bonus List</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- offer bonus report -->
                                <?php if(PermissionService::has_permission('offer_bonus_report', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'offer bonus report')): ?>
                                        <li class="<?php echo e(Request::is('admin/bonus/bonus-report') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.bonus.report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Create Bonus">
                                                    Bonus Report
                                                </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Categor Manager -->
                <?php if(PermissionService::has_permission('category_manager', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'category manager')): ?>
                        <li class="nav-item  <?php echo e(Request::is('admin/categories') ? 'active' : ''); ?>">
                            <a class="d-flex align-items-center" href="<?php echo e(url('admin/categories')); ?>">
                                <i data-feather='layers'></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="Category Manager"><?php echo e(__('admin-menue-left.category_manger')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Categor Manager -->
                <?php if(PermissionService::has_permission('lead_management', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'category manager')): ?>
                        <li class="nav-item  <?php echo e(Request::is('admin/lead-management') ? 'active' : ''); ?>">
                            <a class="d-flex align-items-center" href="<?php echo e(url('admin/lead-management')); ?>">
                                <i data-feather='layers'></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="Lead Management"><?php echo e(__('admin-menue-left.lead_management')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <!--Social Trade-->
                <?php if(PermissionService::has_permission('social_trade', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'social trade')): ?>
                        <!-- social trade -->
                        <li class="nav-item <?php echo e(Request::is('admin/pamm/*') ? 'open' : ''); ?>">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather='user-check'></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="Admin-Profile"><?php echo e(__('admin-menue-left.social_trade')); ?></span>
                                <span id="notiDashboard"
                                    class="badge badge-light-warning rounded-pill ms-auto me-1"></span>
                            </a>
                            <ul class="menu-content">
                                <!-- social trade -->
                                <?php if(PermissionService::has_permission('social_dashboard', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'social dashboard')): ?>
                                        <li class="<?php echo e(Request::is('admin/pamm/copy-dashboard') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.pamm.copy-dashboard')); ?>">
                                                <i data-feather="circle"></i><span class="menu-item text-truncate"
                                                    data-i18n="Admin-Profile">Dashboard
                                                </span></a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- pamm settings -->
                                <?php if(PermissionService::has_permission('pamm_settings', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'pamm settings')): ?>
                                        <li class="<?php echo e(Request::is('admin/pamm/pamm-settings') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.pamm')); ?>">
                                                <i data-feather="circle"></i><span class="menu-item text-truncate"
                                                    data-i18n="Admin-Profile">Social Trade Settings</span></a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- pamm manager -->
                                <?php if(PermissionService::has_permission('pamm_manager', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'pamm manager')): ?>
                                        <li class="<?php echo e(Request::is('admin/pamm/pamm-manager') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.manager.pamm')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Basic">Social Trade Manager</span>
                                            </a>
                                        </li>
                                        <li class="<?php echo e(Request::is('admin/pamm/master-profit-share-report') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.pamm.master-profit-share-report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Basic">Master Profit Share</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- copy trades report -->
                                <?php if(PermissionService::has_permission('copy_trades_report', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'copy trades report')): ?>
                                        <li class="<?php echo e(Request::is('admin/pamm/copy-trades-report') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.pamm.copy-trade-report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="copy"><?php echo e(__('admin-menue-left.copy_trades_report')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- social trade activiy reports -->
                                <?php if(PermissionService::has_permission('social_trades_activity_reports', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'social trades activity report')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/pamm/social-trades-ativity-report') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.social-report')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Basic"><?php echo e(__('admin-menue-left.social_trades_report')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- manage mamm -->
                                <?php if(PermissionService::has_permission('manage_mamm', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'manage mamm')): ?>
                                        <li class="<?php echo e(Request::is('admin/pamm/social-trades/manage-mam') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.mamm.manage')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="manage"><?php echo e(__('admin-menue-left.manage_mamm')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- strat pamm request -->
                                <?php if(PermissionService::has_permission('pamm_request', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'pamm request')): ?>
                                        <li class="<?php echo e(Request::is('admin/pamm/pamm-request') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('user.pamm_request')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="manage">Social Trade Request</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <!--tournaments-->
                <li class="nav-item <?php echo e(Request::is('admin/tournament/*') ? 'open' : ''); ?>">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='user-check'></i>
                        <span class="menu-title text-truncate"
                            data-i18n="Admin-Profile">Tournaments</span>
                        <span id="notiDashboard"
                            class="badge badge-light-warning rounded-pill ms-auto me-1"></span>
                    </a>
                    <ul class="menu-content">
                        <!-- tournament settings -->
                        <li class="<?php echo e(Request::is('admin/tournament/setting-view') ? 'active' : ''); ?>">
                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.tournament.setting-view')); ?>">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Admin-Profile">Settings</span>
                            </a>
                        </li>
                        <!-- tournament group list -->
                        <li class="<?php echo e(Request::is('admin/tournament/group-list') ? 'active' : ''); ?>">
                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.tournament.group-list')); ?>">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Admin-Profile">Group List</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- START: Support -->
                <?php if(PermissionService::has_permission('support', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'support')): ?>
                        <li class=" nav-item <?php echo e(Request::is('admin/support/*') ? 'open' : ''); ?>">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather='mail'></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="support"><?php echo e(__('admin-menue-left.support')); ?></span>
                            </a>
                            <ul class="menu-content">
                                <!-- client supports -->
                                <?php if(PermissionService::has_permission('support_tickets', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'client ticket')): ?>
                                        <li class="<?php echo e(Request::is('admin/support/support-ticket') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.support.support-ticket')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Balance Management"><?php echo e(__('admin-menue-left.client_tickets')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- END: Support -->

                <!-- Settings -->
                <?php if(PermissionService::has_permission('settings', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'settings')): ?>
                        <li class=" nav-item <?php echo e(Request::is('admin/settings/*') ? 'open' : ''); ?>"
                            id="left_setting_menu">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather="settings"></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="Settings"><?php echo e(__('admin-menue-left.settings')); ?></span>
                            </a>
                            <ul class="menu-content">
                                <!-- crypto address -->
                                <?php if(PermissionService::has_permission('add_crypto_address', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'add crypto address')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/settings/crypto_deposit_settings') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="crypto_deposit_setting"
                                                href="<?php echo e(route('admin.settings.crypto_deposit_settings')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Add Crypto Address">
                                                    <?php echo e(__('admin-menue-left.Add_Crypto_Address')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- announcement setting -->
                                <?php if(PermissionService::has_permission('announcement', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'announcement')): ?>
                                        <li class="<?php echo e(Request::is('admin/settings/announcement') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="announcement"
                                                href="<?php echo e(route('admin.settings.announcement')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Announcement">
                                                    <?php echo e(__('admin-menue-left.announcement')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- api configurations -->
                                <?php if(PermissionService::has_permission('api_configuration', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'api configuration')): ?>
                                        <li class="<?php echo e(Request::is('admin/settings/api_configuration') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="api_configuration"
                                                href="<?php echo e(route('admin.settings.api_configuration')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="API Configuration">
                                                    <?php echo e(__('admin-menue-left.API_Configuration')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- admin bank setup -->
                                <?php if(PermissionService::has_permission('bank_setting', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'bank setting')): ?>
                                        <li class="<?php echo e(Request::is('admin/settings/bank-account-setup') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="smtp_setup"
                                                href="<?php echo e(route('admin.bank-account-setup')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="SMTP Setup">
                                                    <?php echo e(__('admin-menue-left.Bank_Setting')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- currency setup -->
                                <!-- settings -->
                                <?php if(PermissionService::has_permission('currency_setup', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'currency setup')): ?>
                                        <li class="<?php echo e(Request::is('admin/settings/currency-setup') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="smtp_setup"
                                                href="<?php echo e(route('admin.settings.currency-setup')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Currency Setup">Currency
                                                    Setup</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- copy symbol -->
                                <!-- settings -->
                                <?php if(PermissionService::has_permission('copy_symbols', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'copy symbols')): ?>
                                        <li class="<?php echo e(Request::is('admin/settings/add-copy-symbol') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="smtp_setup"
                                                href="<?php echo e(route('admin.add-copy-symbol')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Currency Setup">Copy
                                                    Symbols</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- banner setting -->
                                <?php if(PermissionService::has_permission('banner_setup', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'banner setup')): ?>
                                        <li class="<?php echo e(Request::is('admin/settings/banner-setup') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.banner-setup')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Banner Setup"><?php echo e(__('admin-menue-left.banner_setup')); ?></span>
                                            </a>
                                        </li>
                                        <li class="<?php echo e(Request::is('admin/settings/popup-setup') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.settings.popup-setup')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Popup Setup">Popup
                                                    Setup</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- company setup -->
                                <?php if(PermissionService::has_permission('company_setup', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'company setup')): ?>
                                        <li class="<?php echo e(Request::is('admin/settings/company_setup') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="company_setup"
                                                href="<?php echo e(route('admin.settings.company_setup')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Company Setup">
                                                    <?php echo e(__('admin-menue-left.Company_Setup')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- currency pair setting -->
                                <?php if(PermissionService::has_permission('currency_pair', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'currency pair')): ?>
                                        <li class="<?php echo e(Request::is('admin/settings/currency-pair') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="currency-pair"
                                                href="<?php echo e(route('admin.settings.currency-pair')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate"
                                                    data-i18n="Currency Pair"><?php echo e(__('admin-menue-left.currency_pair')); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- finance setting -->
                                <?php if(PermissionService::has_permission('finance_settings', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'finance settings')): ?>
                                        <li class="<?php echo e(Request::is('admin/settings/finance_setting') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="finance_setting"
                                                href="<?php echo e(route('admin.settings.finance_setting')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Finance Settings">
                                                    <?php echo e(__('admin-menue-left.Finance_Settings')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- ib settings -->
                                <?php if(PermissionService::has_permission('ib_settings', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'ib setting')): ?>
                                        <li class="<?php echo e(Request::is('admin/settings/ib_setting') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="ib_setting"
                                                href="<?php echo e(route('admin.settings.ib_setting')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="IB Setting">
                                                    <?php echo e(__('admin-menue-left.IB_Setting')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- notification setting -->
                                <?php if(PermissionService::has_permission('notification_settings', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'notification setting')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/settings/notification_setting') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="notification_setting"
                                                href="<?php echo e(route('admin.settings.notification_setting')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Notification Setting">
                                                    <?php echo e(__('admin-menue-left.Notification_Setting')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- security settings -->
                                <?php if(PermissionService::has_permission('security_settings', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'security setting')): ?>
                                        <li class="<?php echo e(Request::is('admin/settings/security_setting') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="security_setting"
                                                href="<?php echo e(route('admin.settings.security_setting')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Security Setting">
                                                    <?php echo e(__('admin-menue-left.Security_Setting')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- smtp setup -->
                                <?php if(PermissionService::has_permission('smtp_setup', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'smtp setup')): ?>
                                        <li class="<?php echo e(Request::is('admin/settings/smtp_setup') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="smtp_setup"
                                                href="<?php echo e(route('admin.settings.smtp_setup')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="SMTP Setup">
                                                    <?php echo e(__('admin-menue-left.SMTP_Setup')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- software setting -->
                                <?php if(PermissionService::has_permission('software_settings', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'software settings')): ?>
                                        <li class="<?php echo e(Request::is('admin/settings/software_setting') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="software_setting"
                                                href="<?php echo e(route('admin.settings.software_setting')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Software Settings">
                                                    <?php echo e(__('admin-menue-left.Software_Settings')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- trader settings -->
                                <?php if(PermissionService::has_permission('trader_settings', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'trader setting')): ?>
                                        <li class="<?php echo e(Request::is('admin/settings/trader_setting') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="trader_setting"
                                                href="<?php echo e(route('admin.settings.trader_setting')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Trader Setting">
                                                    <?php echo e(__('admin-menue-left.Trader_Setting')); ?> </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- payment gatewayts settings -->
                                <?php if(PermissionService::has_permission('payment_gateways', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'payment gateways')): ?>
                                        <li class="<?php echo e(Request::is('admin/settings/payment-gateways') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="trader_setting"
                                                href="<?php echo e(route('admin.settings.paymentgateway')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Trader Setting">
                                                    Payment Gateways
                                                </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- settings -->
                                <!-- notification template -->
                                <?php if(PermissionService::has_permission('notification_template', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'notification template')): ?>
                                        <li
                                            class="<?php echo e(Request::is('admin/settings/notification-templates') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" id="trader_setting"
                                                href="<?php echo e(route('admin.settings.notification-templates')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Trader Setting">
                                                    Notification template
                                                </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <!-------------------------Contest------------------->
                <?php if(PermissionService::has_permission('contest', 'admin')): ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'contest')): ?>
                        <li class=" nav-item"><a class="d-flex align-items-center" href="#">
                                <i data-feather='trello'></i>
                                <span class="menu-title text-truncate" data-i18n="contest">Contest</span>
                            </a>
                            <ul class="menu-content">
                                <?php if(PermissionService::has_permission('create_contest', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'create contest')): ?>
                                        <li class="<?php echo e(Request::is('admin/contest/create-contest') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.contest.create')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Group Manager">Create
                                                    Contest</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- contest list -->
                                <?php if(PermissionService::has_permission('contest_list', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'contest list')): ?>
                                        <li class="<?php echo e(Request::is('admin/contest/contest-list') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center" href="<?php echo e(route('admin.contest.list')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Group Manager">Contest
                                                    List</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- contest participant -->
                                <?php if(PermissionService::has_permission('contest_participant', 'admin')): ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'contest participant')): ?>
                                        <li class="<?php echo e(Request::is('/admin/contest/contest-participant') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.contest.participant')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Group Manager">Contest
                                                    participant
                                                </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                
                
                  <!------Reward-------->
                <?php if(PermissionService::has_permission('contest', 'admin')): ?>
                        <li class=" nav-item"><a class="d-flex align-items-center" href="#">
                                <i data-feather='trello'></i>
                                <span class="menu-title text-truncate" data-i18n="reward">Reward</span>
                            </a>
                            <ul class="menu-content">
                                
                                        <li class="<?php echo e(Request::is('admin/reward/create-reward') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.reward.create')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Group Manager">Create
                                                    Reward</span>
                                            </a>
                                        </li>

                                        <li class="<?php echo e(Request::is('admin/reward/rewards') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.rewards')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Group Manager">
                                                    Reward List</span>
                                            </a>
                                        </li>

                                        <li class="<?php echo e(Request::is('admin/claim/reward/list') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.claim.reward.list')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Group Manager">
                                                    Reward Claim Requests</span>
                                            </a>
                                        </li>
                                        
                                         <li class="<?php echo e(Request::is('admin/reward/participants/list') ? 'active' : ''); ?>">
                                            <a class="d-flex align-items-center"
                                                href="<?php echo e(route('admin.reward.participants.list')); ?>">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="Group Manager">
                                                    Reward Participants List</span>
                                            </a>
                                        </li>
                                <!-- contest list -->
                                
                                <!-- contest participant -->
                                
                            </ul>
                        </li>
                <?php endif; ?>
                <!-------------------->
                
                
                
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->
    <!-- Basic tour -->
    <section id="basic-tour" class="d-none">
        <div class="row">
            <div class="col-sm-4 offset-md-4 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tour</h4>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-outline-primary" id="tour">Start Tour</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--/ Basic tour -->

    <!-- BEGIN: Content-->
    <?php echo $__env->yieldContent('content'); ?>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    <span id="envSEssionTime" data-session="<?php echo e(config('lifetime')); ?>"></span>
    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0">
            <span class="float-md-start d-block d-md-inline-block mt-25">
                <?php echo e(get_copyright()); ?> &copy; <?php echo e(date('Y')); ?>

            </span>
        </p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->
    <!-- include session expire soon modal -->
    <?php echo $__env->make('layouts.lock-screen-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- BEGIN: Vendor JS-->
    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/vendors.min.js')); ?>"></script>
    <?php echo $__env->yieldContent('vendor-js'); ?>
    <!-- BEGIN Vendor JS-->
    <!-- BEGIN: Page Vendor JS-->
    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js')); ?>"></script>
    <script src="<?php echo e(asset('common-js/layout-js/lockscreen.js')); ?>"></script>
    <?php echo $__env->yieldContent('page-vendor-js'); ?>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="<?php echo e(asset('admin-assets/app-assets/js/core/app-menu.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/js/core/app.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/src/js/core/confirm-alert.js')); ?>"></script>
    <!--confirm alert || mail send with notify-->


    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/ui/ui-feather.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/table-color.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/pages/common-ajax.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/pages/lang-change.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js')); ?>"></script>
    <!-- BEGIN: Page tour JS-->
    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/extensions/tether.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/extensions/shepherd.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/extensions/ext-component-tour.js')); ?>"></script>
    <script src="<?php echo e(asset('/common-js/custom-from-validation.js')); ?>"></script>
    
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/smooth-scrollbar.min.js')); ?>"></script>
    <!-- enter key handler -->
    <script src="<?php echo e(asset('common-js/enter-key-handler.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/quill/quill.js')); ?>"></script>
    <!-- <script src="<?php echo e(asset('common-js/inspector-disable.js')); ?>"></script> -->
    <?php echo $__env->yieldContent('page-js'); ?>
    <!-- END: Page JS-->

    <script>
        // scroll to bottom bottom menu 
        $('.check_height').click(function() {
            $(".main-menu-content").animate({
                scrollTop: $('.main-menu-content').prop("scrollHeight")
            }, 1000);
        });

        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        });

        var notiCount = $('#notiCount').html();
        $(document).ready(function() {
            if (notiCount > 9) {
                $('#notiBel').html('9+');
                $('#notiBelBottom').html('9+');
                $('#notiDashboard').html('9+');
                $('#allNotification').html('9+');
            } else if (notiCount == 0) {
                $('#notiBel').hide();
                $('#notiBelBottom').hide();
                $('#notiDashboard').hide();
                $('#allNotification').hide();
            } else {
                $('#notiBel').html(notiCount);
                $('#notiBelBottom').html(notiCount);
                $('#notiDashboard').html(notiCount);
                $('#allNotification').html(notiCount);
            }
        });

        if ($('.shepherd-cancel-icon').click()) {
            $('#dashMainMenuID').addClass('open');
            $('#dashboard_first').addClass('active');
            $(".left-setting-menu").closest('li').removeClass('open sidebar-group-active');
        }
        $(document).ready(function() {
            $("button").each(function() {
                $(this).removeClass("waves-effect");
            });
        });
        // Load notifications by type
        $(document).ready(function() {
            // Client notifications
            $.ajax({
                url: '/admin/notification/by-type/client',
                dataType: 'JSON',
                success: function(data) {
                    $("#client-notifications-list").html(data.html);
                    $("#clientNotiBadge").text(data.count);
                    $("#clientNotiCount").text(data.count);
                    if (data.count == 0) {
                        $("#clientNotiBadge").hide();
                    } else {
                        $("#clientNotiBadge").show();
                    }
                }
            });

            // Deposit notifications
            $.ajax({
                url: '/admin/notification/by-type/deposit',
                dataType: 'JSON',
                success: function(data) {
                    $("#deposit-notifications-list").html(data.html);
                    $("#depositNotiBadge").text(data.count);
                    $("#depositNotiCount").text(data.count);
                    if (data.count == 0) {
                        $("#depositNotiBadge").hide();
                    } else {
                        $("#depositNotiBadge").show();
                    }
                }
            });

            // Withdrawal notifications
            $.ajax({
                url: '/admin/notification/by-type/withdraw',
                dataType: 'JSON',
                success: function(data) {
                    $("#withdrawal-notifications-list").html(data.html);
                    $("#withdrawalNotiBadge").text(data.count);
                    $("#withdrawalNotiCount").text(data.count);
                    if (data.count == 0) {
                        $("#withdrawalNotiBadge").hide();
                    } else {
                        $("#withdrawalNotiBadge").show();
                    }
                }
            });

            // Transfer notifications
            $.ajax({
                url: '/admin/notification/by-type/transfer',
                dataType: 'JSON',
                success: function(data) {
                    $("#transfer-notifications-list").html(data.html);
                    $("#transferNotiBadge").text(data.count);
                    $("#transferNotiCount").text(data.count);
                    if (data.count == 0) {
                        $("#transferNotiBadge").hide();
                    } else {
                        $("#transferNotiBadge").show();
                    }
                }
            });

            // Account management notifications
            $.ajax({
                url: '/admin/notification/by-type/account',
                dataType: 'JSON',
                success: function(data) {
                    $("#account-notifications-list").html(data.html);
                    $("#accountNotiBadge").text(data.count);
                    $("#accountNotiCount").text(data.count);
                    if (data.count == 0) {
                        $("#accountNotiBadge").hide();
                    } else {
                        $("#accountNotiBadge").show();
                    }
                }
            });

            // IB Registration Request notifications
            $.ajax({
                url: '/admin/notification/by-type/ib_request',
                dataType: 'JSON',
                success: function(data) {
                    $("#ib-request-notifications-list").html(data.html);
                    $("#ibRequestNotiBadge").text(data.count);
                    $("#ibRequestNotiCount").text(data.count);
                    if (data.count == 0) {
                        $("#ibRequestNotiBadge").hide();
                    } else {
                        $("#ibRequestNotiBadge").show();
                    }
                }
            });

            // Bank Account List notifications
            $.ajax({
                url: '/admin/notification/by-type/bank_account',
                dataType: 'JSON',
                success: function(data) {
                    $("#bank-account-notifications-list").html(data.html);
                    $("#bankAccountNotiBadge").text(data.count);
                    $("#bankAccountNotiCount").text(data.count);
                    if (data.count == 0) {
                        $("#bankAccountNotiBadge").hide();
                    } else {
                        $("#bankAccountNotiBadge").show();
                    }
                }
            });

            // System notifications
            $.ajax({
                url: '/admin/notification/system-notification',
                dataType: 'JSON',
                success: function(data) {
                    $("#system-notificion").append(data);
                }
            });

            // Count all notifications
            $.ajax({
                url: '/admin/notification/count',
                dataType: 'JSON',
                success: function(data) {
                    $("#notiBel").text(data);
                    $("#notiBelBottom").text(data);
                    if (data == 0) {
                        $("#notiBel").hide();
                        $("#notiBelBottom").hide();
                    } else {
                        $("#notiBel").show();
                        $("#notiBelBottom").show();
                    }
                }
            });
        });
    </script>
</body>
<!-- END: Body-->

</html>
<?php /**PATH I:\code\fxcrm\fx\crm-new-laravel\resources\views/layouts/admin-layout.blade.php ENDPATH**/ ?>