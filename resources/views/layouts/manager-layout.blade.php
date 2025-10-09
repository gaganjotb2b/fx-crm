<!DOCTYPE html>
@php
use App\Services\checkSettingsService;
use App\Services\PermissionService;
@endphp
<html class="loading {{ get_admin_theme() }}" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 1 }}; {{ url('/admin/lock-screen' . '/' . base64_encode(auth()->user()->id) . '/' . base64_encode(url()->current())) }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="{{get_company_name()}} is a broker company focuses in Forex Trading. We believe in transparency, accountability, and accuracy of services. Experience trading in the most seamless way, straight to global market, and the easiness of withdrawal.">
    <meta name="keywords" content="{{get_company_name()}} is operated by {{get_company_name()}} and has registered in Saint Vincent & the Grenadines with LLC number 892 LLC 2021, regulated by the Financial Services Authority (FSA) of Saint Vincent and the Grenadines. High Risk Warning : Before you enter foreign exchange and stock markets, you have to remember that trading currencies and other investment products is trading in nature and always involves a considerable risk. As a result of various financial fluctuations, you may not only significantly increase your capital, but also lose it completely.">
    <meta name="author" content="{{get_company_name()}}">
    <!-- csrf token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- style sheet -->
    <title id="minutes">{{ strtoupper(config('app.name')) }} - @yield('title') </title>
    @php $themeColor = get_theme_colors_forAll('admin_theme') @endphp
    <style>
        :root {
            --custom-primary: <?= $themeColor->primary_color ?? '#7367f0' ?>;
            --custom-form-color: <?= $themeColor->form_color ?? '#979fa6' ?>;
            --bs-body-color: <?= $themeColor->body_color ?? '#67748e' ?>;
        }
    </style>
    <link rel="shortcut icon" type="image/x-icon" href="{{ get_favicon_icon() }}">
    <link rel="apple-touch-icon" href="{{ get_favicon_icon() }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/toastr.min.css') }}">
    @yield('vendor-css')
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/themes/bordered-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/themes/semi-dark-layout.css') }}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/pages/ui-feather.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/pages/dashboard-ecommerce.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/charts/chart-apex.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/shepherd.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/shepherd.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-tour.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/quill/quill.snow.css') }}">
    @yield('page-css')
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/style.css') }}">
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

        /* .al-error-solve:has(> .error-msg) {
            'margin-bottom' : '15px';
        } */
    </style>
    @yield('custom-css')
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-fluid">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
                </ul>
                <!-- dashboard -->
                <ul class="nav navbar-nav bookmark-icons">
                    <li class="nav-item d-none d-lg-block">
                        <a class="nav-link" href="{{ route('manager.index') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Home">
                            <i class="ficon" data-feather="home"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ms-auto">
                <li class="nav-item dropdown dropdown-language">
                    <a class="nav-link dropdown-toggle" id="dropdown-flag" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @if (session()->get('locale') == 'fr')
                        @php($lang = __('language.french'))
                        @php($flag = 'fr')
                        @elseif(session()->get('locale') == 'de')
                        @php($lang = __('language.german'))
                        @php($flag = 'de')
                        @elseif(session()->get('locale') == 'pt')
                        @php($lang = __('language.portuguese'))
                        @php($flag = 'pt')
                        @elseif(session()->get('locale') == 'zh')
                        @php($lang = __('language.chinese'))
                        @php($flag = 'cn')
                        @else
                        @php($lang = __('language.english'))
                        @php($flag = 'us')
                        @endif
                        <i class="flag-icon flag-icon-{{ $flag }}"></i>
                        <span class="selected-language">
                            {{ $lang }}
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-flag">
                        <a class="dropdown-item lang-change" href="#" data-language="en"><i class="flag-icon flag-icon-us"></i>{{ __('language.english') }}</a>
                        <a class="dropdown-item lang-change" href="#" data-language="fr"><i class="flag-icon flag-icon-fr"></i> {{ __('language.french') }}</a>
                        <a class="dropdown-item lang-change" href="#" data-language="de"><i class="flag-icon flag-icon-de"></i> {{ __('language.german') }}</a>
                        <a class="dropdown-item lang-change" href="#" data-language="pt"><i class="flag-icon flag-icon-pt"></i> {{ __('language.portuguese') }}</a>
                        <a class="dropdown-item lang-change" href="#" data-language="zh"><i class="flag-icon flag-icon-cn"></i> {{ __('language.chinese') }}</a>
                    </div>
                </li>
                <!--<li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon"-->
                <!--            data-feather="moon"></i></a></li>-->
                <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon" data-feather="search"></i></a>
                    <div class="search-input">
                        <div class="search-input-icon"><i data-feather="search"></i></div>
                        <input class="form-control input" type="text" placeholder="Search the menue" tabindex="-1" data-search="menu">
                        <div class="search-input-close"><i data-feather="x"></i></div>
                        <ul class="search-list search-list-main"></ul>
                    </div>
                </li>
                <li class="nav-item dropdown dropdown-notification me-25"><a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon" data-feather="bell"></i><span id="notiBel" class="badge rounded-pill bg-danger badge-up"></span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">Notifications</h4>
                                <div class="badge rounded-pill badge-light-primary"><span id="notiBelBottom"></span>
                                    New</div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list">
                            <div class="list-item d-flex align-items-center">
                                <h6 class="fw-bolder me-auto mb-0">System Notifications</h6>
                                <div class="form-check form-check-primary form-switch">
                                    <input class="form-check-input" id="systemNotification" type="checkbox" checked="">
                                    <label class="form-check-label" for="systemNotification"></label>
                                </div>
                            </div><a class="d-flex" href="#">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-danger">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="x"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Server
                                                down</span>&nbsp;registered</p><small class="notification-text"> USA
                                            Server is down due to high CPU usage</small>
                                    </div>
                                </div>
                            </a>

                            <a class="d-flex" href="#">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-success">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="check"></i></div>
                                        </div>
                                    </div>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Sales
                                                report</span>&nbsp;generated</p><small class="notification-text"> Last
                                            month sales report generated</small>
                                    </div>
                                </div>
                            </a>
                            <?php

                            use App\Services\NotificationService;

                            $obj = new NotificationService();
                            $notification = $obj->system_notification();
                            $count = 0;

                            ?>
                            {{-- Software Settings Notification  --}}
                            @if ($notification['software_settings'] != '')
                            @if ($notification['software_settings']['crm_type'] == null or
                            $notification['software_settings']['platform_book'] == null or
                            $notification['software_settings']['create_meta_acc'] == null or
                            $notification['software_settings']['platform_book'] == null or
                            $notification['software_settings']['acc_limit'] == null or
                            $notification['software_settings']['brute_force_attack'] == null)
                            <a class="d-flex" href="/admin/settings/software_setting">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-warning">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="alert-triangle"></i></div>
                                        </div>
                                    </div>
                                    <?php
                                    $count = $count + 1;
                                    ?>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Please Update Your
                                                Software Settings</p><small class="notification-text">You need
                                            to
                                            update</small>
                                    </div>
                                </div>
                            </a>
                            @endif

                            {{-- SMTP Settings Notification  --}}
                            @if ($notification['software_settings']['mail_driver'] == null or
                            $notification['software_settings']['host'] == null or
                            $notification['software_settings']['port'] == null or
                            $notification['software_settings']['mail_user'] == null or
                            $notification['software_settings']['mail_password'] == null or
                            $notification['software_settings']['mail_encryption'] == null)
                            <a class="d-flex" href="/admin/settings/smtp_setup">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-warning">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="alert-triangle"></i></div>
                                        </div>
                                    </div>
                                    <?php
                                    $count = $count + 1;
                                    ?>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Please Update Your
                                                SMTP
                                                Settings</p><small class="notification-text">You need to
                                            update</small>
                                    </div>
                                </div>
                            </a>
                            @endif
                            {{-- Company Setup notification  --}}
                            @if ($notification['software_settings']['com_name'] == null or
                            $notification['software_settings']['com_email'] == null or
                            $notification['software_settings']['com_phone'] == null or
                            $notification['software_settings']['com_license'] == null or
                            $notification['software_settings']['com_website'] == null or
                            $notification['software_settings']['com_address'] == null or
                            $notification['software_settings']['com_authority'] == null or
                            $notification['software_settings']['com_social_info'] == null or
                            $notification['software_settings']['copyright'] == null or
                            $notification['software_settings']['support_email'] == null or
                            $notification['software_settings']['auto_email'] == null)
                            <a class="d-flex" href="/admin/settings/company_setup">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-warning">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="alert-triangle"></i></div>
                                        </div>
                                    </div>
                                    <?php
                                    $count = $count + 1;
                                    ?>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Please Update Your
                                                Company Setup </p><small class="notification-text">You need to
                                            update</small>
                                    </div>
                                </div>
                            </a>
                            @endif
                            {{-- API Configuration Notification  --}}
                            @if ($notification['software_settings']['platform_type'] == null or
                            $notification['software_settings']['server_type'] == null or
                            $notification['software_settings']['platform_download_link'] == null or
                            $notification['software_settings']['server_ip'] == null or
                            $notification['software_settings']['manager_login'] == null or
                            $notification['software_settings']['manager_password'] == null or
                            $notification['software_settings']['api_password'] == null)
                            <a class="d-flex" href="/admin/settings/api_configuration">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-warning">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="alert-triangle"></i></div>
                                        </div>
                                    </div>
                                    <?php
                                    $count = $count + 1;
                                    ?>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Please Update Your
                                                API
                                                Configuration </p><small class="notification-text">You need to
                                            update</small>
                                    </div>
                                </div>
                            </a>
                            @endif
                            @endif
                            {{-- Crypto Address Settings --}}
                            @if (!isset($notification['CryptoAddress']))
                            <a class="d-flex" href="/admin/settings/crypto_deposit_settings">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-warning">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="alert-triangle"></i></div>
                                        </div>
                                    </div>
                                    <?php
                                    $count = $count + 1;
                                    ?>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Add Crypto address</p>
                                        <small class="notification-text">You need add to crypto address</small>
                                    </div>
                                </div>
                            </a>
                            @endif
                            {{-- Currency Pair Settings --}}
                            @if (!isset($notification['Symbol']))
                            <a class="d-flex" href="/admin/settings/currency-pair">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-warning">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="alert-triangle"></i></div>
                                        </div>
                                    </div>
                                    <?php
                                    $count = $count + 1;
                                    ?>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Add Currency Pair</p>
                                        <small class="notification-text">You need add to currency pair</small>
                                    </div>
                                </div>
                            </a>
                            @endif

                            {{-- IB Settings --}}
                            @if (!isset($notification['IbSetting']))
                            <a class="d-flex" href="/admin/settings/ib_setting">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-warning">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="alert-triangle"></i></div>
                                        </div>
                                    </div>
                                    <?php
                                    $count = $count + 1;
                                    ?>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Add New IB</p><small class="notification-text">You need add IB Setting</small>
                                    </div>
                                </div>
                            </a>
                            @endif
                            {{-- IB Setup --}}
                            @if (!isset($notification['IbSetup']))
                            <a class="d-flex" href="/admin/ib-management/ib-setup">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-warning">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="alert-triangle"></i></div>
                                        </div>
                                    </div>
                                    <?php
                                    $count = $count + 1;
                                    ?>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Setup Your Ib</p><small class="notification-text">You need Ib setup</small>
                                    </div>
                                </div>
                            </a>
                            @endif
                            {{-- IB Commission structure Setup --}}
                            @if (!isset($notification['IbCommissionStructure']))
                            <a class="d-flex" href="/admin/ib-management/ib-commission-structure">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-warning">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="alert-triangle"></i></div>
                                        </div>
                                    </div>
                                    <?php
                                    $count = $count + 1;
                                    ?>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">IB Commission Structure
                                                Add</p><small class="notification-text">You need to Ib commission
                                            structure add</small>
                                    </div>
                                </div>
                            </a>
                            @endif
                            {{-- Trader Setting --}}
                            @if (!isset($notification['TraderSetting']))
                            <a class="d-flex" href="/admin/ib-management/ib-commission-structure">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-warning">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="alert-triangle"></i></div>
                                        </div>
                                    </div>
                                    <?php
                                    $count = $count + 1;
                                    ?>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Add New Trader</p><small class="notification-text">You need add to trader setting</small>
                                    </div>
                                </div>
                            </a>
                            @endif
                            {{-- Finance Setting --}}
                            @if (!isset($notification['TransactionSetting']))
                            <a class="d-flex" href="/admin/settings/finance_setting">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-warning">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="alert-triangle"></i></div>
                                        </div>
                                    </div>
                                    <?php
                                    $count = $count + 1;
                                    ?>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">Add New Finance</p><small class="notification-text">You need add to first finance setting</small>
                                    </div>
                                </div>
                            </a>
                            @endif

                            <span id="notiCount" class="d-none">{{ $count }}</span>



                        </li>
                        <li class="dropdown-menu-footer"><a class="btn btn-primary w-100" href="{{ route('admin.allNotification.allNotification') }}">Read all
                                notifications</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown dropdown-user">
                    <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none">
                            <span class="user-name fw-bolder">{{config('app.name')}}</span>
                            <span class="user-status">{{ __('admin-menue-left.' . auth()->user()->type) }}</span>
                        </div>
                        <span class="avatar">
                            <img class="round bg-gradient-primary" src="{{ asset(avatar()) }}" alt="avatar" height="40" width="40">
                            <span class="avatar-status-online"></span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                        <a class="dropdown-item" href="{{ route('admin.profile-settings') }}">
                            <i class="me-50" data-feather="user"></i>
                            Profile
                        </a>

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="me-50" data-feather="power"></i> Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
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
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="me-75"><img src="{{ asset('admin-assets/app-assets/images/icons/xls.png') }}" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Two new item submitted</p><small class="text-muted">Marketing Manager</small>
                    </div>
                </div><small class="search-data-size me-50 text-muted">&apos;17kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="me-75"><img src="{{ asset('admin-assets/app-assets/images/icons/jpg.png') }}" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">52 JPG file Generated</p><small class="text-muted">FontEnd
                            Developer</small>
                    </div>
                </div><small class="search-data-size me-50 text-muted">&apos;11kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="me-75"><img src="{{ asset('admin-assets/app-assets/images/icons/pdf.png') }}" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">25 PDF File Uploaded</p><small class="text-muted">Digital
                            Marketing Manager</small>
                    </div>
                </div><small class="search-data-size me-50 text-muted">&apos;150kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="me-75"><img src="{{ asset('admin-assets/app-assets/images/icons/doc.png') }}" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Anna_Strong.doc</p><small class="text-muted">Web
                            Designer</small>
                    </div>
                </div><small class="search-data-size me-50 text-muted">&apos;256kb</small>
            </a></li>
        <li class="d-flex align-items-center"><a href="#">
                <h6 class="section-label mt-75 mb-0">Members</h6>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view-account.html">
                <div class="d-flex align-items-center">
                    <div class="avatar me-75"><img src="{{ asset('admin-assets/app-assets/images/portrait/small/avatar-s-8.jpg') }}" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">John Doe</p><small class="text-muted">UI
                            designer</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view-account.html">
                <div class="d-flex align-items-center">
                    <div class="avatar me-75"><img src="{{ asset('admin-assets/app-assets/images/portrait/small/avatar-s-1.jpg') }}" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Michal Clark</p><small class="text-muted">FontEnd
                            Developer</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view-account.html">
                <div class="d-flex align-items-center">
                    <div class="avatar me-75"><img src="{{ asset('admin-assets/app-assets/images/portrait/small/avatar-s-14.jpg') }}" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Milena Gibson</p><small class="text-muted">Digital
                            Marketing Manager</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view-account.html">
                <div class="d-flex align-items-center">
                    <div class="avatar me-75"><img src="{{ asset('admin-assets/app-assets/images/portrait/small/avatar-s-6.jpg') }}" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Anna Strong</p><small class="text-muted">Web
                            Designer</small>
                    </div>
                </div>
            </a></li>
    </ul>
    <ul class="main-search-list-defaultlist-other-list d-none">
        <li class="auto-suggestion justify-content-between"><a class="d-flex align-items-center justify-content-between w-100 py-50">
                <div class="d-flex justify-content-start"><span class="me-75" data-feather="alert-circle"></span><span>No results found.</span></div>
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
                            <img class="img img-fluid" src="{{ get_user_logo() }}" alt="{{ config('app.name') }}" style="max-width:100%">
                        </span>
                    </a>
                </li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <!-- dashboard -->
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="{{ Request::is('manager/index') ? 'active' : '' }}" id="mainMenuLi">
                    <a class="d-flex align-items-center" href="{{ route('manager.index') }}">
                        <i data-feather="home"></i>
                        <span class="menu-item text-truncate" data-i18n="Configuration">{{ __('page.dashboard') }}</span>
                    </a>
                </li>
                <li class="{{ Request::is('manager/dashboard') ? 'active' : '' }}" id="mainMenuLi">
                    <a class="d-flex align-items-center" href="{{ route('manager.dashboard') }}">
                        <i data-feather="home"></i>
                        <span class="menu-item text-truncate" data-i18n="Manager Analysis">{{ __('Manager Analysis') }}</span>
                    </a>
                </li>
                <!-- START: Finance -->
                @if (PermissionService::has_permission('finance','admin'))
                @role('finance')
                <li class=" nav-item {{ Request::is('admin/finance/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='dollar-sign'></i>
                        <span class="menu-title text-truncate" data-i18n="Finance">{{ __('admin-menue-left.finance') }}</span>
                    </a>
                    <ul class="menu-content">
                        <!-- balance management -->
                        @if (PermissionService::has_permission('balance_management','admin'))
                        @role('balance management')
                        <li class="{{ Request::is('admin/finance/balance-management') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.finance-balance') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Balance Management">{{ __('admin-menue-left.balance_management') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- credit management -->
                        @if (PermissionService::has_permission('credit_management','admin'))
                        @role('credit management')
                        <li class="{{ Request::is('admin/finance/credit-management') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.finance-credit') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Credit Management">{{ __('admin-menue-left.credit_management') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- finance reports -->
                        @if (PermissionService::has_permission('fund_management','admin'))
                        @role('fund management')
                        <li class="{{ Request::is('admin/finance/fund-management') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.finance-fund-management') }}">
                                <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Fund Management">{{ __('admin-menue-left.fund_management') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- finance reports -->
                        @if (PermissionService::has_permission('finance_reports','admin'))
                        @role('finance report')
                        <li class="{{ Request::is('admin/finance/finance-report') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.finance-report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Finance Reports">{{ __('admin-menue-left.finance_reports') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                    </ul>
                </li>
                @endrole
                @endif
                <!-- END: finance -->
                <!-- fund transfer -->
                @if (PermissionService::has_permission('fund_transfer','admin'))
                @role('fund transfer')
                <li class="check_height nav-item {{ Request::is('admin/fund/*') ? 'open' : '' }} ">
                    <a class="d-flex align-items-center" id="height_check" href="#">
                        <i data-feather='credit-card'></i>
                        <span class="menu-title text-truncate" data-i18n="Fund Transfer">{{ __('admin-menue-left.fund_trasfer') }}</span>
                    </a>
                    <ul class="menu-content">
                        <!-- internal fund transfer -->
                        @if (PermissionService::has_permission('internal_fund_transfer','admin'))
                        @role('internal fund transfer')
                        <li class="{{ Request::is('admin/fund/internal-fund-transfer') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.fund-report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Internal Fund Transfer">{{ __('admin-menue-left.internal_transfer') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- external fund transfer -->
                        @if (PermissionService::has_permission('external_fund_transfer','admin'))
                        @role('external fund transfer')
                        <li class="{{ Request::is('admin/fund/external-fund-transfer') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.external-fund-report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="External Fund Transfer">{{ __('admin-menue-left.external_transfer') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                    </ul>
                </li>
                @endrole
                @endif
                <!-- IB Management Nav -->
                @if (PermissionService::has_permission('ib_management','admin'))
                @role('ib management')
                <li class=" nav-item {{ Request::is('admin/ib-management/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='package'></i>
                        <span class="menu-title text-truncate" data-i18n="IB Management">{{ __('admin-menue-left.ib_management') }}</span>
                    </a>
                    <ul class="menu-content">
                        <!-- ib setup -->
                        @if (PermissionService::has_permission('ib_setup','admin'))
                        @role('ib setup')
                        <li class="{{ Request::is('admin/ib-management/ib-setup') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib-setup-view') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Setup">{{ __('admin-menue-left.ib_setup') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib commission structure -->
                        @if (PermissionService::has_permission('ib_commission_structure','admin'))
                        @role('ib commission structure')
                        <li class="{{ Request::is('admin/ib-management/ib-commission-structure') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib-commission-structure') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Commission Structure">{{ __('admin-menue-left.ib_commission_structure') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib tree -->
                        @if (PermissionService::has_permission('ib_tree','admin'))
                        @role('ib tree')
                        <li class="{{ Request::is('admin/ib-management/ib-tree') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib-tree') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Tree">{{ __('admin-menue-left.ib_tree') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- master ib -->
                        @if (PermissionService::has_permission('master_ib','admin'))
                        @role('master ib')
                        <li class="{{ Request::is('admin/ib_management/master_ib_report') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib_management.master_ib_report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Master IB">
                                    {{ __('admin-menue-left.Master_IB') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- pending commission -->
                        @if (PermissionService::has_permission('pending_commission_list','admin'))
                        @role('panding commission list')
                        <li class="{{ Request::is('admin/ib_management/pending_commission_list') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib_management.pending_commission_list') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Pending Commission List">
                                    {{ __('admin-menue-left.Pending_Commission_List') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- no commission list -->
                        @if (PermissionService::has_permission('no_commission_list','admin'))
                        @role('no commission list')
                        <li class="{{ Request::is('admin/ib_management/no_commission_list') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib_management.no_commission_list') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="No Commission List">
                                    {{ __('admin-menue-left.No_Commission_List') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib chain -->
                        @if (PermissionService::has_permission('ib_chain','admin'))
                        @role('ib-chain')
                        <li class="{{ Request::is('admin/ib-management/ib-chain') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib_management.ib_chain') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Chain">
                                    {{ __('admin-menue-left.IB_Chain') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib admin -->
                        @if (PermissionService::has_permission('ib_admin','admin'))
                        @role('ib admin')
                        <li class="{{ Request::is('admin/ib-management/ib-admin-report') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('ib.admin.report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Admin">{{ __('admin-menue-left.ib_admin') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib verification request -->
                        @if (PermissionService::has_permission('ib_verification_request','admin'))
                        @role('ib verification request')
                        <li class="{{ Request::is('admin/ib-management/ib-verification-request') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.verification.request-report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Verification Request">{{ __('admin-menue-left.ib_verification_request') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib analysis -->
                        @if (PermissionService::has_permission('ib_analysis','admin'))
                        @role('ib analysis')
                        <li class="{{ Request::is('admin/ib-management/ib-analysis') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib-analysis') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Verification Analysis">{{ __('admin-menue-left.ib_analysis') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib request for combine -->
                        <li class="{{ Request::is('admin/ib-management/ib-request') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.combine-ib-request') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Verification Analysis">IB Registration Request</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole
                @endif
                
                <!-- Start: KYC Managemnt -->
                @if (PermissionService::has_permission('kyc_management','admin'))
                @role('kyc management')
                <li class=" nav-item {{ Request::is('admin/kyc-management/*') ? 'open' : '' }} ">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='archive'></i>
                        <span class="menu-title text-truncate" data-i18n="KYC Management">{{ __('admin-menue-left.kyc_management') }}</span>
                    </a>
                    <ul class="menu-content">
                        <!-- kyc upload -->
                        @if (PermissionService::has_permission('kyc_upload','admin'))
                        @role('kyc upload')
                        <li class="{{ Request::is('admin/kyc-management/kyc-upload-view') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.kyc-upload-view') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="KYC Upload">{{ __('admin-menue-left.kyc_upload') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- kyc reports -->
                        @if (PermissionService::has_permission('kyc_reports','admin'))
                        @role('kyc reports')
                        <li class="{{ Request::is('admin/kyc-management/kyc-report') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('kyc.management.report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="KYC Reports">{{ __('admin-menue-left.kyc_reports') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- kyc request -->
                        @if (PermissionService::has_permission('kyc_request','admin'))
                        @role('kyc request')
                        <li class="{{ Request::is('admin/kyc-management/kyc-request') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('kyc.management.request') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="KYC Request">{{ __('admin-menue-left.kyc_request') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                    </ul>
                </li>
                @endrole
                @endif
                <!-- Admin profile -->
                @if (PermissionService::has_permission('admin_profile','admin'))
                @role('admin profile')
                <li class=" nav-item">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='user-check'></i>
                        <span class="menu-title text-truncate" data-i18n="Admin-Profile">{{ __('page.admin_profile') }}</span>
                        <!-- <span class="badge badge-light-warning rounded-pill ms-auto me-1">1</span> -->
                        <span id="notiDashboard" class="badge badge-light-warning rounded-pill ms-auto me-1"></span>
                    </a>
                    <ul class="menu-content">
                        <!-- profile change -->
                        @if (PermissionService::has_permission('change_profile','admin'))
                        @role('change profile')
                        <li class="{{ Request::is('admin/profile/*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.profile-settings') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Admin-Profile">{{ __('page.change_profile') }}</span></a>
                        </li>
                        @endrole
                        @endif
                        <!-- notifiction -->
                        @if (PermissionService::has_permission('notification','admin'))
                        <li class="{{ Request::is('admin/allNotification/allNotification*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.allNotification.allNotification') }}">
                                <i data-feather="bell"></i>
                                <span class="menu-item text-truncate" data-i18n="eCommerce">{{ __('page.notifications') }} &nbsp;
                                    <span class="badge badge-light-warning rounded-pill ms-auto me-1" id="allNotification"></span>
                                </span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endrole
                @endif
                <!-- Client management -->
                @if (PermissionService::has_permission('manage_client','admin'))
                @role('manage client')
                <li class=" nav-item {{ Request::is('admin/client-management/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="users"></i>
                        <span class="menu-title text-truncate" data-i18n="Manage Client">{{ __('admin-menue-left.client_management') }}</span>
                    </a>
                    <ul class="menu-content">
                        <!-- trader admin -->
                        @if (PermissionService::has_permission('trader_admin','admin'))
                        @role('trader admin')
                        <li class="{{ Request::is('admin/client-management/trader-admin') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.trader-admin') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Basic">{{ __('admin-menue-left.trader_admin') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- trader analysis -->
                        @if (PermissionService::has_permission('trader_analysis','admin'))
                        @role('trader analysis')
                        <li class="{{ Request::is('admin/client-management/trader-analysis') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.trader-analysis') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Basic">Trader Analysis</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        
                         <li class="{{ Request::is('manager/lead/report') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('manager.lead.report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate"
                                    data-i18n="Basic">Lead Manager</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>
                @endrole
                @endif
                <!-- manage trade -->
                @if (PermissionService::has_permission('manage_trade','admin'))
                @role('manage trade')
                <li class=" nav-item {{ Request::is('admin/manage-trade/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="users"></i>
                        <span class="menu-title text-truncate" data-i18n="Manage Trade">{{ __('admin-menue-left.manage_trade') }}</span>
                    </a>
                    <ul class="menu-content">
                        <!-- trading report -->
                        @if (PermissionService::has_permission('trading_report','admin'))
                        @role('trading trade report')
                        <li class="{{ Request::is('admin/manage-trade/trading-trade-report') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.trading-trade-report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Basic">{{ __('admin-menue-left.trading_report') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- trade commission -->
                        @if (PermissionService::has_permission('trade_commission','admin'))
                        @role('trade commission status')
                        <li class="{{ Request::is('admin/manage-trade/trade-commission-status') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.trade-commission-status') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Basic">{{ __('admin-menue-left.trade_commission_status') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                    </ul>
                </li>
                @endrole
                @endif
                <!-- ADMIN: Management -->
                @if (PermissionService::has_permission('manage_admin','admin'))
                @role('manage admin')
                <li class=" nav-item {{ Request::is('admin/admin-management/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="users"></i>
                        <span class="menu-title text-truncate" data-i18n="Admin Management">{{ __('admin-menue-left.admin_management') }}</span>
                    </a>
                    <ul class="menu-content">
                        <!-- admin group -->
                        @if (PermissionService::has_permission('admin_groups','admin'))
                        @role('admin groups')
                        <li class="{{ Request::is('admin/admin-management/admin-groups') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.admin-groups') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Admin Groups">{{ __('admin-menue-left.admin_groups') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- admin registration -->
                        @if (PermissionService::has_permission('admin_registration','admin'))
                        @role('admin registration')
                        <li class="{{ Request::is('admin/admin-management/admin-registration') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.admin-registration') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Admin Registration">{{ __('admin-menue-left.admin_registration') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- admin right management -->
                        @if (PermissionService::has_permission('admin_right_management','admin'))
                        @role('admin right management')
                        <li class="{{ Request::is('admin/admin-management/roles') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.roles') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Admin Right Management">{{ __('admin-menue-left.admin_right') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                    </ul>
                </li>
                @endrole
                @endif
                <!-- START: Manager settings -->
                @if (PermissionService::has_permission('manager_settings','admin'))
                @role('manager settings')
                <li class=" nav-item {{ Request::is('admin/manager-settings/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="users"></i>
                        <span class="menu-title text-truncate" data-i18n="Manager Settings">{{ __('admin-menue-left.manager_settings') }}</span>
                    </a>
                    <ul class="menu-content">
                        <!-- manager groups -->
                        @if (PermissionService::has_permission('manager_groups','admin'))
                        @role('manager groups')
                        <li class="{{ Request::is('admin/manager-settings/manager-group') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.manager-groups') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Manager Groups">{{ __('admin-menue-left.manager_groups') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- add manager -->
                        @if (PermissionService::has_permission('add_manager','admin'))
                        @role('add manager')
                        <li class="{{ Request::is('admin/manager-settings/add-manager') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.add-manager') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Add Manager">{{ __('admin-menue-left.add_manager') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- manager list -->
                        @if (PermissionService::has_permission('manager_list','admin'))
                        @role('manager list')
                        <li class="{{ Request::is('admin/manager-settings/get-manager') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.get-manager') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Manager List">{{ __('admin-menue-left.manager_list') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- manager right -->
                        @if (PermissionService::has_permission('manager_right','admin'))
                        @role('manager right')
                        <li class="{{ Request::is('admin/manager-settings/manager-right') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.all-manager-with-right') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Manager Right">{{ __('admin-menue-left.manager_right') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- manager analysis -->
                        @if (PermissionService::has_permission('manager_analysis','admin'))
                        @role('manager analysis')
                        <li class="{{ Request::is('admin/manager-settings/manager-analysis') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.manager-analysis-view') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Manager Analysis">{{ __('admin-menue-left.manager_analysis') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                    </ul>
                </li>
                @endrole
                @endif
                <!-- END: manager settings -->
                <!-- START: manage accounts -->
                @if (PermissionService::has_permission('manage_accounts','admin'))
                @role('manage accounts')
                <li class=" nav-item"><a class="d-flex align-items-center" href="#">
                        <i data-feather="grid"></i>
                        <span class="menu-title text-truncate" data-i18n="Manage Accounts">
                            {{ __('admin-menue-left.Manage_Accounts') }}
                        </span>
                    </a>
                    <ul class="menu-content">
                        <!-- live trading account -->
                        @if (PermissionService::has_permission('live_trading_account','admin'))
                        @role('live trading account')
                        <li class="{{ Request::is('admin/trading-account-details-live') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ url('admin/trading-account-details-live') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Live Trading Account List">
                                    {{ __('admin-menue-left.Live_Trading_Account_List') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        @if (PermissionService::has_permission('demo_trading_account','admin'))
                        @role('demo trading account')
                        <!-- demo trading account -->
                        <li class="{{ Request::is('admin/trading-account-details-demo') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ url('admin/trading-account-details-demo') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Demo Trading Account List">
                                    {{ __('admin-menue-left.Demo_Trading_Account_List') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                    </ul>
                </li>
                @endrole
                @endif
                <!-- END: manage accounts -->
                <!-- START: manage bank accounts -->
                @if (PermissionService::has_permission('manage_banks','admin'))
                @role('manage banks')
                <li class=" nav-item"><a class="d-flex align-items-center" href="#">
                        <i data-feather="grid"></i>
                        <span class="menu-title text-truncate" data-i18n="Manage Banks">
                            {{ __('admin-menue-left.Manage_Banks') }}
                        </span>
                    </a>
                    <ul class="menu-content">
                        <!-- bank account list -->
                        @if (PermissionService::has_permission('bank_account_list','admin'))
                        @role('bank account list')
                        <li class="{{ Request::is('admin/manage_banks/bank_account_list') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.manage_banks.bank_account_list') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Bank Account List">
                                    {{ __('admin-menue-left.Bank_Account_List') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- company bank list -->
                        @role('company bank list')
                        <li class="{{ Request::is('admin/manage_banks/company-bank-list') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.company-bank-list') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Company Bank List">
                                    Company Bank List
                                </span>
                            </a>
                        </li>
                        @endrole
                    </ul>
                </li>
                @endrole
                @endif
                <!-- END: manage bank accounts -->
                <!--Social Trade-->
                @if (PermissionService::has_permission('social_trade','admin'))
                @role('social trade')
                <li class="nav-item {{ Request::is('admin/pamm/*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='user-check'></i>
                        <span class="menu-title text-truncate" data-i18n="Admin-Profile">{{ __('admin-menue-left.social_trade') }}</span>
                        <span id="notiDashboard" class="badge badge-light-warning rounded-pill ms-auto me-1"></span>
                    </a>
                    <ul class="menu-content">
                        <!-- social trade -->
                        @if (PermissionService::has_permission('social_dashboard','admin'))
                        @role('social dashboard')
                        <li class="{{ Request::is('admin/pamm/copy-dashboard') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.pamm.copy-dashboard') }}">
                                <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Admin-Profile">Dashboard
                                </span></a>
                        </li>
                        @endrole
                        @endif
                        <!-- pamm settings -->
                        @if (PermissionService::has_permission('pamm_settings','admin'))
                        @role('pamm settings')
                        <li class="{{ Request::is('admin/pamm/pamm-settings') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.pamm') }}">
                                <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Admin-Profile">{{ __('admin-menue-left.pamm_setting') }}
                                </span></a>
                        </li>
                        @endrole
                        @endif
                        <!-- pamm manager -->
                        @if (PermissionService::has_permission('pamm_manager','admin'))
                        @role('pamm manager')
                        <li class="{{ Request::is('admin/pamm/pamm-manager') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.manager.pamm') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Basic">{{ __('admin-menue-left.pamm_manager') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- copy trades report -->
                        @if (PermissionService::has_permission('copy_trades_report','admin'))
                        @role('copy trades report')
                        <li class="{{ Request::is('admin/pamm/copy-trades-report') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.pamm.copy-trade-report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="copy">{{ __('admin-menue-left.copy_trades_report') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- social trade activiy reports -->
                        @if (PermissionService::has_permission('social_trades_activity_reports','admin'))
                        @role('social trades activity report')
                        <li class="{{ Request::is('admin/pamm/social-trades-ativity-report') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.social-report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Basic">{{ __('admin-menue-left.social_trades_report') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- manage mamm -->
                        @if (PermissionService::has_permission('manage_mamm','admin'))
                        @role('manage mamm')
                        <li class="{{ Request::is('admin/pamm/social-trades/manage-mam') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.mamm.manage') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="manage">{{ __('admin-menue-left.manage_mamm') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <li class="{{ Request::is('admin/pamm/pamm-request') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('user.pamm_request') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="manage">PAMM Request</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole
                @endif
                <!-- START: Support -->
                @if (PermissionService::has_permission('support','admin'))
                @role('support')
                <li class=" nav-item {{ Request::is('admin/support/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='mail'></i>
                        <span class="menu-title text-truncate" data-i18n="support">{{ __('admin-menue-left.support') }}</span>
                    </a>
                    <ul class="menu-content">
                        <!-- client supports -->
                        <!-- @if (PermissionService::has_permission('support_tickets','admin'))
                        @role('client ticket') -->
                        <li class="{{ Request::is('admin/support/support-ticket') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.support.support-ticket') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Balance Management">{{ __('admin-menue-left.client_tickets') }}</span>
                            </a>
                        </li>
                        <!-- @endrole
                        @endif -->
                    </ul>
                </li>
                @endrole
                @endif
                <!-- END: Support -->

                <!-- Categor Manager -->
                @if (PermissionService::has_permission('category_manager','admin'))
                @role('category manager')
                <li class="nav-item  {{ Request::is('admin/categories') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ url('admin/categories') }}">
                        <i data-feather='layers'></i>
                        <span class="menu-title text-truncate" data-i18n="Category Manager">{{ __('admin-menue-left.category_manger') }}</span>
                    </a>
                </li>
                @endrole
                @endif
                <!-- IB Management Nav -->
                @if (PermissionService::has_permission('ib_management','admin'))
                @role('ib management')
                <li class=" nav-item {{ Request::is('admin/ib-management/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='package'></i>
                        <span class="menu-title text-truncate" data-i18n="IB Management">{{ __('admin-menue-left.ib_management') }}</span>
                    </a>
                    <ul class="menu-content">
                        <!-- ib setup -->
                        @if (PermissionService::has_permission('ib_setup','admin'))
                        @role('ib setup')
                        <li class="{{ Request::is('admin/ib-management/ib-setup') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib-setup-view') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Setup">{{ __('admin-menue-left.ib_setup') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib commission structure -->
                        @if (PermissionService::has_permission('ib_commission_structure','admin'))
                        @role('ib commission structure')
                        <li class="{{ Request::is('admin/ib-management/ib-commission-structure') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib-commission-structure') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Commission Structure">{{ __('admin-menue-left.ib_commission_structure') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib tree -->
                        @if (PermissionService::has_permission('ib_tree','admin'))
                        @role('ib tree')
                        <li class="{{ Request::is('admin/ib-management/ib-tree') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib-tree') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Tree">{{ __('admin-menue-left.ib_tree') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- master ib -->
                        @if (PermissionService::has_permission('master_ib','admin'))
                        @role('master ib')
                        <li class="{{ Request::is('admin/ib_management/master_ib_report') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib_management.master_ib_report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Master IB">
                                    {{ __('admin-menue-left.Master_IB') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- pending commission -->
                        @if (PermissionService::has_permission('pending_commission_list','admin'))
                        @role('panding commission list')
                        <li class="{{ Request::is('admin/ib_management/pending_commission_list') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib_management.pending_commission_list') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Pending Commission List">
                                    {{ __('admin-menue-left.Pending_Commission_List') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- no commission list -->
                        @if (PermissionService::has_permission('no_commission_list','admin'))
                        @role('no commission list')
                        <li class="{{ Request::is('admin/ib_management/no_commission_list') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib_management.no_commission_list') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="No Commission List">
                                    {{ __('admin-menue-left.No_Commission_List') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib chain -->
                        @if (PermissionService::has_permission('ib_chain','admin'))
                        @role('ib-chain')
                        <li class="{{ Request::is('admin/ib-management/ib-chain') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib_management.ib_chain') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Chain">
                                    {{ __('admin-menue-left.IB_Chain') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib admin -->
                        @if (PermissionService::has_permission('ib_admin','admin'))
                        @role('ib admin')
                        <li class="{{ Request::is('admin/ib-management/ib-admin-report') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('ib.admin.report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Admin">{{ __('admin-menue-left.ib_admin') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib verification request -->
                        @if (PermissionService::has_permission('ib_verification_request','admin'))
                        @role('ib verification request')
                        <li class="{{ Request::is('admin/ib-management/ib-verification-request') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.verification.request-report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Verification Request">{{ __('admin-menue-left.ib_verification_request') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib analysis -->
                        @if (PermissionService::has_permission('ib_analysis','admin'))
                        @role('ib analysis')
                        <li class="{{ Request::is('admin/ib-management/ib-analysis') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib-analysis') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Verification Analysis">{{ __('admin-menue-left.ib_analysis') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib request for combine -->
                        @role('ib registration request')
                        <li class="{{ Request::is('admin/ib-management/ib-request') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.combine-ib-request') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Verification Analysis">IB Registration Request</span>
                            </a>
                        </li>
                        @endrole
                    </ul>
                </li>
                @endrole
                @endif
                <!-- Settings Nav -->
                @if (PermissionService::has_permission('settings','admin'))
                @role('settings')
                <li class=" nav-item {{ Request::is('admin/settings/*') ? 'open' : '' }}" id="left_setting_menu">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="settings"></i>
                        <span class="menu-title text-truncate" data-i18n="Settings">{{ __('admin-menue-left.settings') }}</span>
                    </a>
                    <ul class="menu-content">
                        <!-- crypto address -->
                        @if (PermissionService::has_permission('add_crypto_address','admin'))
                        @role('add crypto address')
                        <li class="{{ Request::is('admin/settings/crypto_deposit_settings') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="crypto_deposit_setting" href="{{ route('admin.settings.crypto_deposit_settings') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Add Crypto Address">
                                    {{ __('admin-menue-left.Add_Crypto_Address') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- announcement setting -->
                        @if (PermissionService::has_permission('announcement','admin'))
                        @role('announcement')
                        <li class="{{ Request::is('admin/settings/announcement') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="announcement" href="{{ route('admin.settings.announcement') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Announcement">
                                    {{ __('admin-menue-left.announcement') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- api configurations -->
                        @if (PermissionService::has_permission('api_configuration','admin'))
                        @role('api configuration')
                        <li class="{{ Request::is('admin/settings/api_configuration') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="api_configuration" href="{{ route('admin.settings.api_configuration') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="API Configuration">
                                    {{ __('admin-menue-left.API_Configuration') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- admin bank setup -->
                        @if (PermissionService::has_permission('bank_setting','admin'))
                        @role('bank setting')
                        <li class="{{ Request::is('admin/settings/bank-account-setup') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="smtp_setup" href="{{ route('admin.bank-account-setup') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="SMTP Setup">
                                    {{ __('admin-menue-left.Bank_Setting') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <li class="{{ Request::is('admin/settings/currency-setup') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="smtp_setup" href="{{ route('admin.settings.currency-setup') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Currency Setup">Currency Setup</span>
                            </a>
                        </li>
                        <!-- banner setting -->
                        @if (PermissionService::has_permission('banner_setup','admin'))
                        @role('banner setup')
                        <li class="{{ Request::is('admin/settings/banner-setup') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.banner-setup') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Banner Setup">{{ __('admin-menue-left.banner_setup') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- company setup -->
                        @if (PermissionService::has_permission('company_setup','admin'))
                        @role('company setup')
                        <li class="{{ Request::is('admin/settings/company_setup') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="company_setup" href="{{ route('admin.settings.company_setup') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Company Setup">
                                    {{ __('admin-menue-left.Company_Setup') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- currency pair setting -->
                        @if (PermissionService::has_permission('currency_pair','admin'))
                        @role('currency pair')
                        <li class="{{ Request::is('admin/settings/currency-pair') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="currency-pair" href="{{ route('admin.settings.currency-pair') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Currency Pair">{{ __('admin-menue-left.currency_pair') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- finance setting -->
                        @if (PermissionService::has_permission('finance_settings','admin'))
                        @role('finance settings')
                        <li class="{{ Request::is('admin/settings/finance_setting') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="finance_setting" href="{{ route('admin.settings.finance_setting') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Finance Settings">
                                    {{ __('admin-menue-left.Finance_Settings') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib settings -->
                        @if (PermissionService::has_permission('ib_settings','admin'))
                        @role('ib setting')
                        <li class="{{ Request::is('admin/settings/ib_setting') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="ib_setting" href="{{ route('admin.settings.ib_setting') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Setting">
                                    {{ __('admin-menue-left.IB_Setting') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- notification setting -->
                        @if (PermissionService::has_permission('notification_settings','admin'))
                        @role('notification setting')
                        <li class="{{ Request::is('admin/settings/notification_setting') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="notification_setting" href="{{ route('admin.settings.notification_setting') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Notification Setting">
                                    {{ __('admin-menue-left.Notification_Setting') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- security settings -->
                        @if (PermissionService::has_permission('security_settings','admin'))
                        @role('security setting')

                        <li class="{{ Request::is('admin/settings/security_setting') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="security_setting" href="{{ route('admin.settings.security_setting') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Security Setting">
                                    {{ __('admin-menue-left.Security_Setting') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- smtp setup -->
                        @if (PermissionService::has_permission('smtp_setup','admin'))
                        @role('smtp setup')
                        <li class="{{ Request::is('admin/settings/smtp_setup') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="smtp_setup" href="{{ route('admin.settings.smtp_setup') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="SMTP Setup">
                                    {{ __('admin-menue-left.SMTP_Setup') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- software setting -->
                        @if (PermissionService::has_permission('software_settings','admin'))
                        @role('software settings')
                        <li class="{{ Request::is('admin/settings/software_setting') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="software_setting" href="{{ route('admin.settings.software_setting') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Software Settings">
                                    {{ __('admin-menue-left.Software_Settings') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- trader settings -->
                        @if (PermissionService::has_permission('trader_settings','admin'))
                        @role('trader setting')
                        <li class="{{ Request::is('admin/settings/trader_setting') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" id="trader_setting" href="{{ route('admin.settings.trader_setting') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Trader Setting">
                                    {{ __('admin-menue-left.Trader_Setting') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                    </ul>
                </li>
                @endrole
                @endif

                <!-- request management -->
                @if (PermissionService::has_permission('manage_request','admin'))
                @role('manage request')
                <li class=" nav-item {{ Request::is('admin/manage-report/*') ? 'open' : '' }} ">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="copy"></i>
                        <span class="menu-title text-truncate" data-i18n="Request Management">{{ __('admin-menue-left.request_management') }}</span>
                    </a>
                    <ul class="menu-content">
                        <!-- deposit request -->
                        @if (PermissionService::has_permission('deposit_request','admin'))
                        @role('deposit request')
                        <li class="{{ Request::is('admin/manage-report/deposit-request') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.manage.deposit') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Deposit Request">{{ __('admin-menue-left.deposit_request') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- withdraw request -->
                        @if (PermissionService::has_permission('withdraw_request','admin'))
                        @role('withdraw request')
                        <li class="{{ Request::is('admin/manage-report/withdraw-request') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.manage.withdraw') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Withdraw Request">{{ __('admin-menue-left.withdraw_request') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- account request -->
                        @if (PermissionService::has_permission('account_request','admin'))
                        @role('account request')
                        <li class="{{ Request::is('admin/manage-report/account-request') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.account-request') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Withdraw Request">{{ __('admin-menue-left.account_request') }}</span>
                            </a>
                        </li>

                        @endrole
                        @endif
                        <!-- balance transfer -->
                        @if (PermissionService::has_permission('balance_transfer_request','admin'))
                        @role('balance transfer')
                        <li class="{{ Request::is('admin/manage-report/balance-transfer') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.balance-transfer') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Balance Transfer">{{ __('admin-menue-left.balance_transfer') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib transfer request -->
                        @if (PermissionService::has_permission('ib_transfer_request','admin'))
                        @role('ib transfer')
                        <li class="{{ Request::is('admin/manage-report/ib-transfer') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib-transfer') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Transfer">{{ __('admin-menue-left.ib_transfer') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib withdraw request -->
                        @if (PermissionService::has_permission('ib_withdraw_request','admin'))
                        @role('ib withdraw request')
                        <li class="{{ Request::is('admin/manage-report/ib-withdraw-request') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.ib-transfer.withdraw') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Withdraw Request">{{ __('admin-menue-left.ib_withdraw_request') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                    </ul>
                </li>
                @endrole
                @endif
                <!-- all reports -->
                @if (PermissionService::has_permission('reports','admin'))
                @role('reports')
                <li class="check_height nav-item {{ Request::is('admin/report/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='sliders'></i>
                        <span class="menu-title text-truncate" data-i18n="Reports">{{ __('admin-menue-left.reports') }}</span>
                    </a>
                    <ul class="menu-content">
                        <!-- ib withdraw -->
                        @if (PermissionService::has_permission('ib_withdraw','admin'))
                        @role('ib withdraw')
                        <li class="{{ Request::is('admin/report/withdraw/ib') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ url('admin/report/withdraw/ib') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Withdraw">{{ __('admin-menue-left.ib_withdraw') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib commission report -->
                        @if (PermissionService::has_permission('ib_commission','admin'))
                        <li class="{{ Request::is('admin/report/ib-commission') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ url('admin/report/ib-commission') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Commission">{{ __('admin-menue-left.ib_commission') }}</span>
                            </a>
                        </li>
                        @endif
                        <!-- trader withdraw -->
                        @if (PermissionService::has_permission('trader_withdraw','admin'))
                        @role('trader withdraw')
                        <li class="{{ Request::is('admin/report/withdraw/trader') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ url('admin/report/withdraw/trader') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Trader Withdraw">{{ __('admin-menue-left.trader_withdraw') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- deposit request -->
                        <!-- @if (PermissionService::has_permission('deposit_request','admin'))
                        @role('deposit request report')
                        <li class="{{ Request::is('admin/report/deposit') ? 'active' : '' }} d-none">
                            <a class="d-flex align-items-center" href="{{ route('admin.deposit-report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Deposit Request">{{ __('admin-menue-left.deposit_request') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif -->
                        <!-- ********************************* *************-->
                        <!-- don't remove this section, it's for later -->
                        <!-- ************************************************ -->
                        <!-- <li class="{{ Request::is('admin/report/log') ? 'active' : '' }}">
                                                                                    <a class="d-flex align-items-center" href="{{ route('admin.log-report') }}">
                                                                                        <i data-feather="circle"></i>
                                                                                        <span class="menu-item text-truncate" data-i18n="Log Report">{{ __('admin-menue-left.log_report') }}</span>
                                                                                    </a>
                                                                                </li> -->
                        <!-- activity log -->
                        @if (PermissionService::has_permission('activity_log','admin'))
                        @role('activity log')
                        <li class="{{ Request::is('admin/report/activity-log') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.activity-log') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Activity log">{{ __('admin-menue-left.activity_log') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- trader deposit report -->
                        @if (PermissionService::has_permission('trader_deposit','admin'))
                        @role('trader deposit report')
                        <li class="{{ Request::is('admin/report/trader-deposit') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.trader-report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Trader Deposit">Trader deposit</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- bonus report -->
                        @if (PermissionService::has_permission('bonus_report','admin'))
                        @role('bonus report')
                        <li class="{{ Request::is('admin/report/user-bonus-report') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.user.bonus-report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Basic">
                                    {{ __('admin-menue-left.Bonus_Report') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ib fund transfer -->
                        @if (PermissionService::has_permission('ib_fund_transfer','admin'))
                        @role('ib fund transfer')
                        <li class="{{ Request::is('admin/report/ib-fund-transfer-report') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.user.ib-fund-transfer') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="IB Fund Transfer">{{ __('admin-menue-left.ib_fund_transfer') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- ledger report -->
                        @if (PermissionService::has_permission('security_settings','admin'))
                        @role('ledger report')
                        <li class="{{ Request::is('admin/report/ledger-report') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.report.ledger') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Ledger Report">{{ __('admin-menue-left.ledger_report') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- individual ledger report -->
                        @if (PermissionService::has_permission('individual_ledger_report','admin'))
                        @role('individual ledger report')
                        <li class="{{ Request::is('admin/report/individual-ledger-report') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.report.ledger-individual') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="individual Ledger Report">{{ __('admin-menue-left.individual_ledger_report') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                    </ul>
                </li>
                @endrole
                @endif
                <!-- Voucher Generate -->
                @if (PermissionService::has_permission('offers','admin'))
                @role('offers')
                <li class="check_height nav-item {{ Request::is('admin/voucher/*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='award'></i>
                        <span class="menu-title text-truncate" data-i18n="Offers">
                            {{ __('admin-menue-left.Offers') }} </span>
                    </a>
                    <ul class="menu-content">
                        <!-- voucher generate -->
                        @if (PermissionService::has_permission('voucher_generate','admin'))
                        @role('voucher generate')
                        <li class="{{ Request::is('admin/voucher') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.voucher.show') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Voucher Generate">
                                    {{ __('admin-menue-left.Voucher_Generate') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- voucher report -->
                        @if (PermissionService::has_permission('voucher_report','admin'))
                        @role('voucher report')
                        <li class="{{ Request::is('admin/voucher/voucher-report') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.voucher.report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Voucher Generate">
                                    {{ __('admin-menue-left.Voucher_Report') }} </span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!--bonus navbar here-->
                        <li class="{{ Request::is('admin/bonus/create-bonus') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.bonus.create') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Create Bonus">
                                    Create Bonus </span>
                            </a>
                        </li>
                        <li class="{{ Request::is('admin/bonus/bonus-list') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.bonus.list') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Create Bonus">
                                    Bonus List</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('admin/bonus/bonus-report') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.bonus.report') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Create Bonus">
                                    Bonus Report</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole


                @endif
                <!-- group settings -->
                @if (PermissionService::has_permission('group_settings','admin'))
                @role('group settings')
                <li class="check_height nav-item"><a class="d-flex align-items-center" href="#">
                        <i data-feather='tool'></i>
                        <span class="menu-title text-truncate" data-i18n="Group Settings">{{ __('admin-menue-left.group_settins') }}</span>
                    </a>
                    <ul class="menu-content">
                        <!-- group manager -->
                        @if (PermissionService::has_permission('group_manager','admin'))
                        @role('group manager')
                        <li class="{{ Request::is('admin/client-groups/create') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ url('admin/client-groups/create') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Group Manager">{{ __('admin-menue-left.group_manager') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- group list -->
                        @if (PermissionService::has_permission('group_list','admin'))
                        @role('group list')
                        <li class="{{ Request::is('admin/client-groups') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ url('admin/client-groups') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Group List">{{ __('admin-menue-left.group_list') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                        <!-- manage ib group -->
                        @if (PermissionService::has_permission('manage_ib_group','admin'))
                        @role('manage ib group')
                        <li class="{{ Request::is('admin/ib-groups') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ url('admin/ib-groups') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Manage IB Group">{{ __('admin-menue-left.manage_ib_group') }}</span>
                            </a>
                        </li>
                        @endrole
                        @endif
                    </ul>
                </li>
                @endrole
                @endif
                <!-------------------------Contest------------------->
                @if (PermissionService::has_permission('contest','admin'))
                @role('contest')
                <li class=" nav-item"><a class="d-flex align-items-center" href="#">
                        <i data-feather='trello'></i>
                        <span class="menu-title text-truncate" data-i18n="contest">Contest</span>
                    </a>
                    <ul class="menu-content">
                        <li class="{{ Request::is('admin/contest/create-contest') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.contest.create') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Group Manager">Create
                                    Contest</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('admin/contest/contest-list') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.contest.list') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Group Manager">Contest List</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('/admin/contest/contest-participant') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.contest.participant') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate" data-i18n="Group Manager">Contest participant
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole
                @endif
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
    @yield('content')
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0">
            <span class="float-md-start d-block d-md-inline-block mt-25">
                {{get_copyright()}} &copy; {{date('Y')}}
            </span>
        </p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->
    <!-- include session expire soon modal -->
    @include('layouts.lock-screen-modal')
    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/vendors.min.js') }}"></script>
    @yield('vendor-js')
    <!-- BEGIN Vendor JS-->
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js') }}"></script>
    <script src="{{asset('common-js/layout-js/lockscreen.js')}}"></script>
    @yield('page-vendor-js')
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('admin-assets/app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/core/app.js') }}"></script>
    <script src="{{ asset('admin-assets/src/js/core/confirm-alert.js') }}"></script>
    <!--confirm alert || mail send with notify-->


    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('admin-assets/app-assets/js/scripts/ui/ui-feather.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/table-color.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/common-ajax.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/lang-change.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>
    <!-- BEGIN: Page tour JS-->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/tether.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/shepherd.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/extensions/ext-component-tour.js') }}"></script>
    <script src="{{ asset('/common-js/custom-from-validation.js') }}"></script>
    {{-- <!-- <script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-select2.js')}}"></script> --> --}}
    <script src="{{ asset('trader-assets/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <!-- enter key handler -->
    <script src="{{asset('common-js/enter-key-handler.js')}}"></script>
    <script src="{{ asset('admin-assets/app-assets/quill/quill.js')}}"></script>
    <!-- <script src="{{asset('common-js/inspector-disable.js')}}"></script> -->
    @yield('page-js')
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

        // if (document.querySelector('.select4')) {
        //     var element = document.querySelector('.choice-material');
        //     const example = new Choices(element, {
        //         searchEnabled: true,
        //         itemSelectText: ''
        //     });
        // };

        // button color fix
        // $(document).on("click","button".function(){
        //    
        // })

        $(document).ready(function() {
            $("button").each(function() {
                $(this).removeClass("waves-effect");
            });
        });
    </script>
</body>
<!-- END: Body-->

</html>