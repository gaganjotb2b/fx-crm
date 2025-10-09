<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 1 }}; {{ url('/lock-screen' . '/' . base64_encode(auth()->user()->id) . '/' . base64_encode(url()->current())) }}" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" href="{{ get_favicon_icon() }}">
    <!-- custom page css can added here -->
    @php $themeColor = get_theme_colors_forAll('user_theme') @endphp
    <style>
        :root {
            --custom-primary: <?= $themeColor->primary_color ?? '#D1B970' ?>;
            --custom-form-color: <?= $themeColor->form_color ?? '#979fa6' ?>;
            --bs-body-color: <?= $themeColor->body_color ?? '#67748e' ?>;
        }
    </style>
    <!-- Title -->
    <title id="minutes">{{ strtoupper(config('app.name')) }} - @yield('title')</title>

    <!-- Styles -->
    <!-- <link rel="preconnect" href="https://fonts.gstatic.com"> -->
    <link rel="stylesheet" href="{{asset('lite-asset/assets/css/googleapis/google-apis-css2.css')}}">
    <link href="{{asset('lite-asset/assets/css/googleapis/metronic-display.css')}}" rel="stylesheet">
    <link href="{{asset('lite-asset/assets/css/googleapis/google-apis.css')}}" rel="stylesheet">
    <!-- fontawesome -->
    <link rel="stylesheet" href="{{asset('common-css/fontawesome/v6/css/all.css')}}">

    <link href="{{ asset('lite-asset/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lite-asset/assets/plugins/perfectscroll/perfect-scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('lite-asset/assets/plugins/pace/pace.css') }}" rel="stylesheet">
    <link href="{{ asset('lite-asset/assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lite-asset/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('lite-asset/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet"> -->
    <link href="{{ asset('lite-asset/assets/plugins/highlight/styles/github-gist.css') }}" rel="stylesheet">

    <!-- Nucleo Icons -->
    <link href="{{ asset('lite-asset/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('lite-asset/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
    <link href="{{ asset('comon-icon/css/all.min.css') }}" rel="stylesheet">

    <!-- Theme Styles -->
    <link href="{{ asset('lite-asset/assets/css/main.min.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('lite-asset/assets/css/darktheme.css') }}" rel="stylesheet"> -->
    <link href="{{ asset('lite-asset/assets/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/common-css/data-list-style.css') }}">

    @if(get_client_theme_color() === "light-layout")
    <link href="{{ asset('lite-asset/assets/css/custom-white-style.css') }}" rel="stylesheet">
    @elseif(get_client_theme_color() === "dark-version")
    <link href="{{ asset('lite-asset/assets/css/darktheme.css') }}" rel="stylesheet">
    <link href="{{ asset('lite-asset/assets/css/custom-dark-style.css') }}" rel="stylesheet">
    @else
    <link href="{{ asset('lite-asset/assets/css/custom-white-style.css') }}" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="{{asset('common-css/metronic.css')}}">
    <!-- custom page css can added here -->
    @yield('page-css')
    <style>
        .dt-buttons .buttons-csv,
        .dt-buttons .buttons-excel,
        .dt-buttons .buttons-copy {
            display: none;
        }
    </style>
</head>
<?php

use App\Services\checkSettingsService;
use App\Services\PermissionService;

$checkSettings = new checkSettingsService();

?>

<body>
    <div class="app align-content-stretch d-flex flex-wrap">
        <div class="app-sidebar">

            <div class="logo d-flex justify-between align-items-center">
                <a href="{{ route('trader.dashboard') }}" class="logo-icon">
                    <img src="{{ get_user_logo() }}" height="40" alt="{{ config('app.name') }}">
                </a>
                <div class="sidebar-user-switcher user-activity-online">
                    <a href="#">
                        <img class="bg-info rounded-circle" src="{{ asset(avatar()) }}" style="width: 32px; height:32px">
                        <span class="activity-indicator"></span>
                        <span class="user-info-text d-none"><span class="user-state-info">{{ config('app.name') }}<br>LITE</span></span>
                    </a>
                </div>
            </div>
            <div class="app-menu">
                <ul class="accordion-menu">
                    <li class="sidebar-title">
                        {{config('app.name')}}
                    </li>
                    <!-- dashboard -->
                    <!-- ib registration for combined -->
                    @if(\App\Services\CombinedService::is_combined())
                    @if(!\App\Services\CombinedService::is_combined('client') && \App\Services\CombinedService::is_requested() == false)
                    <li class="nav-item">
                        <a href="javascript:void(0)" id="btn-convert-ib" class="btn btn-danger text-white mx-5 shadow" aria-controls="dashboardsExamples" role="button">
                            <span class="nav-link-text ms-1" id="btn-label">IB Registration</span>
                        </a>
                    </li>
                    <!-- check ib already requested -->
                    @elseif(\App\Services\CombinedService::is_requested(auth()->user()->id) == true)
                    <li class="nav-item">
                        <a href="javascript:void(0)" id="btn-cancel-ib" class="btn" aria-controls="dashboardsExamples" role="button">

                            <span class="nav-link-text ms-1" id="btn-label">IB Requested</span>
                        </a>
                    </li>
                    @elseif(\App\Services\CombinedService::is_combined('client',auth()->user()->id) && \App\Services\CombinedService::is_requested()==false)
                    <!-- already ib in Combine system -->
                    <li class="">
                        <a href="{{route('ib.dashboard')}}" class="btn" aria-controls="dashboardsExamples" role="button">
                            <i class="material-icons-two-tone"></i>
                            <span class="">IB Dashboard</span>
                        </a>
                    </li>
                    @endif
                    @endif

                    <li class="{{ Request::is('user/dashboard') ? 'active-page' : '' }}">
                        <a href="{{ route('trader.dashboard') }}" class=" {{ Request::is('user/dashboard') ? 'active' : '' }}"><i class="material-icons-two-tone">dashboard</i>{{ __('page.dashboard') }}</a>
                    </li>
                    <!-- user admin -->
                    @if (PermissionService::has_permission('my_admin','trader'))
                    <li class="{{ Request::is('user/user-admin/*') ? 'show active-page' : '' }}">
                        <a href="#"><i class="material-icons-two-tone">person</i>{{ __('page.my-admin') }}<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu {{ Request::is('user/user-admin/*') ? 'd-block' : '' }} ">
                            <!-- profile overiview -->
                            @if (PermissionService::has_permission('profile_overview','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/user-admin/profile-overview') ? 'active' : '' }}" href="{{ route('user.user-admin.profile-overview') }}">{{ __('page.profile-overview') }}
                                </a>
                            </li>
                            @endif
                            <!-- settings -->
                            @if (PermissionService::has_permission('settings','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/user-admin/settings') ? 'active' : '' }}" href="{{ route('user.user-admin-account-settings') }}">{{ __('page.settings') }}</a>
                            </li>
                            @endif
                            <!-- verficatins -->
                            @if (PermissionService::has_permission('verification','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/user-admin/account-verification') ? 'active' : '' }}" href="{{ route('user.user-admin-account-verification') }}">{{ __('page.verification') }}</a>
                            </li>
                            @endif
                            <!-- banking -->
                            @if (PermissionService::has_permission('banking','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/user-admin/user-banking') ? 'active' : '' }}" href="{{ route('user.user-admin.user-banking') }}">{{ __('page.banking') }}</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!-- trading account -->
                    @if (PermissionService::has_permission('trading_accounts','trader'))
                    <li class="{{ Request::is('user/trading-account/*') ? 'show active-page' : '' }}">
                        <a href="#"><i class="material-icons-two-tone">analytics</i>{{ __('page.trading-accounts') }}<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu {{ Request::is('user/trading-account/*') ? 'd-block' : '' }} ">
                            <!-- open demo account -->
                            @if (PermissionService::has_permission('open_demo_account','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/trading-account/open-demo-trading-account') ? 'active' : '' }}" href="{{ route('user.trading-account.open-demo-account') }}">{{ __('page.open-demo-account') }}</a>
                            </li>
                            @endif
                            <!-- open live account -->
                            @if (PermissionService::has_permission('open_live_account','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/trading-account/open-live-account') ? 'active' : '' }}" href="{{ route('user.trading.open-account') }}">{{ __('page.open-live-account') }}
                                </a>
                            </li>
                            @endif
                            <!-- tading account settings -->
                            @if (PermissionService::has_permission('trading_account_settings','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/trading-account/settings') ? 'active' : '' }}" href="{{ route('user.trading-account.settings') }}">{{ __('page.trading_account') }}
                                    {{ __('page.settings') }} </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!-- finance nav-->
                    <li class="sidebar-title">
                        {{ __('page.finance') }}
                    </li>
                    <!-- reports -->
                    @if (PermissionService::has_permission('reports','trader'))
                    <li class="{{ Request::is('user/reports/*') ? 'show active-page' : '' }}">
                        <a href="#"><i class="material-icons-two-tone">menu</i>{{ __('page.reports') }}<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu {{ Request::is('user/reports/*') ? 'd-block' : '' }}">
                            <!-- deposit report -->
                            @if (PermissionService::has_permission('deposit_report','trader'))
                            <li>
                                <a class=" text-truncate {{ Request::is('user/reports/deposit-report') ? 'active' : '' }}" href="{{ route('user.deposit-report') }}">{{ __('page.deposit_report') }}</a>
                            </li>
                            @endif
                            <!-- withdraw report -->
                            @if (PermissionService::has_permission('withdraw_reports','trader'))
                            <li>
                                <a class=" text-truncate {{ Request::is('user/reports/withdraw-report') ? 'active' : '' }}" href="{{ route('user.withdraw-report') }}">{{ __('page.withdraw') }}
                                    {{ __('page.reports') }}</a>
                            </li>
                            @endif
                            <!-- external transfer report -->
                            @if (PermissionService::has_permission('external_transfer_report','trader'))
                            <li>
                                <a class="  text-truncate {{ Request::is('user/reports/external-fund-transfer-report') ? 'active' : '' }}" href="{{ route('user.external-report') }}">{{ __('page.external_transfer_report') }}</a>
                            </li>
                            @endif
                            <!-- internal transfer report -->
                            @if (PermissionService::has_permission('internal_transfer_report','trader'))
                            <li>
                                <a class=" text-truncate {{ Request::is('user/reports/internal-transfer-report') ? 'active' : '' }}" href="{{ route('user.internal-report') }}">{{ __('page.internal_transfer_report') }}</a>
                            </li>
                            @endif
                            <!-- trading report -->
                            @if (PermissionService::has_permission('trading_report','trader'))
                            <li>
                                <a class=" text-truncate {{ Request::is('user/reports/trading-report') ? 'active' : '' }}" href="{{ route('user.trading-report') }}">{{ __('page.trading_report') }}</a>
                            </li>
                            @endif
                           
                        </ul>
                    </li>
                    @endif
                    <!-- deposit -->
                    @if (PermissionService::has_permission('deposit','trader'))
                    <li class="{{ Request::is('user/deposit/*') ? 'show active-page' : '' }}">
                        <a href="#"><i class="material-icons-two-tone">account_balance_wallet</i>{{ __('page.deposit') }}<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu {{ Request::is('user/deposit/*') ? 'd-block' : '' }}">
                            <!-- bank deposit -->
                            @if (PermissionService::has_permission('bank_deposit','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/deposit/bank-deposit') ? 'active' : '' }}" href="{{ route('user.deposit.bank-deposit-form') }}">{{ __('page.bank_deposit') }}</a>
                            </li>
                            @endif
                            <!-- crypto deposit -->
                            @if (PermissionService::has_permission('crypto_deposit','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/deposit/crypto-deposit') ? 'active' : '' }}" href="{{ route('user.deposit.crypto-deposit-form') }}">{{ __('page.crypto_deposit') }}</a>
                            </li>
                            @endif
                            <!-- perfect money -->
                            @if (PermissionService::has_permission('perfect_money_deposit','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/deposit/perfect-money-deposit') ? 'active' : '' }}" href="{{ route('user.deposit.perfect-money-deposit') }}">{{ __('page.perfect_money_deposit') }}</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!-- withdraw -->
                    @if (PermissionService::has_permission('withdraw','trader'))
                    <li class="{{ Request::is('user/withdraw/*') ? 'show active-page' : '' }}">
                        <a href="#"><i class="material-icons-two-tone">account_balance_wallet</i>{{ __('page.withdraw') }}<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu {{ Request::is('user/withdraw/*') ? 'd-block' : '' }}">
                            <!-- bank withdraw -->
                            @if (PermissionService::has_permission('bank_withdraw','trader'))
                            <li>
                                <a class=" text-truncate{{ Request::is('user/withdraw/bank-withdraw') ? 'active' : '' }}" href="{{ route('user.withdraw.bank-withdraw-form') }}">{{ __('page.bank_withdraw') }}</a>
                            </li>
                            @endif
                            <!-- cyrpto withdraw -->
                            @if (PermissionService::has_permission('crypto_withdraw','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/withdraw/crypto-withdraw') ? 'active' : '' }}" href="{{ route('user.withdraw.crypto-withdraw-form') }}">{{ __('page.crypto_withdraw') }}</a>
                            </li>
                            @endif
                            <!-- skrill withdraw -->
                            @if (PermissionService::has_permission('skrill_withdraw','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/withdraw/skrill-withdraw') ? 'active' : '' }}" href="{{ route('user.withdraw.skrill-withdraw-form') }}">{{ __('page.skrill_withdraw') }}</a>
                            </li>
                            @endif
                            <!-- neteller withdraw -->
                            @if (PermissionService::has_permission('neteller_withdraw','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/withdraw/neteller-withdraw') ? 'active' : '' }}" href="{{ route('user.withdraw.neteller-withdraw-form') }}">{{ __('page.neteller_withdraw') }}</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!-- transfer -->
                    @if (PermissionService::has_permission('transfer','trader'))
                    <li class="{{ Request::is('user/transfer/*') ? 'show active-page' : '' }}">
                        <a href="#"><i class="material-icons-two-tone">paid</i>{{ __('page.transfer') }}<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu {{ Request::is('user/transfer/*') ? 'd-block' : '' }}">
                            <!-- wallet to account transfer -->
                            @if (PermissionService::has_permission('wallet_to_account','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/transfer/wallet-to-account-transfer') ? 'active' : '' }}" href="{{ route('user.transfer.wallet-to-account-transfer') }}">{{ __('page.wallet-to-account') }}</a>
                            </li>
                            @endif
                            <!-- account to wallet transfer -->
                            @if (PermissionService::has_permission('account_to_wallet','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/transfer/account-to-wallet-transfer') ? 'active' : '' }}" href="{{ route('user.transfer.account-to-wallet-transfer') }}">{{ __('page.account-to-wallet') }}</a>
                            </li>
                            @endif
                            <!-- trader to trader transfer -->
                            @if (PermissionService::has_permission('trader_to_trader','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/transfer/trader-to-trader-transfer') ? 'active' : '' }}" href="{{ route('user.transfer.trader-to-trader-transfer') }}">{{ __('page.trader-to-trader') }}</a>
                            </li>
                            @endif
                            <!-- trader to ib transfer -->
                            @if (PermissionService::has_permission('trader_to_ib','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/transfer/trader-to-ib-transfer') ? 'active' : '' }}" href="{{ route('user.transfer.trader-to-ib-transfer') }}">{{ __('page.trader-to-ib') }}</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    <!--  Client support -->
                    @if (PermissionService::has_permission('support','trader'))
                    <li class="{{ Request::is('user/support/*') ? 'show active-page' : '' }}">
                        <a href="#"><i class="material-icons-two-tone">report</i>{{ __('page.support') }}<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu {{ Request::is('user/support/*') ? 'd-block' : '' }}">
                            <!-- support ticket -->
                            @if (PermissionService::has_permission('support_ticket','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/support/ticket') ? 'active' : '' }}" href="{{ route('user.support.ticket') }}">{{ __('support-ticket.support_ticket') }}</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    <!-- economic calender -->
                    @if (PermissionService::has_permission('economic_calendar','trader'))
                    <li class="sidebar-title">
                        Economic Calendar
                        {{-- {{ __('page.social') }} {{ __('page.trade') }} --}}
                    </li>
                    <li class="">
                        <a href="{{ route('user.economic-calendar') }}"><i class="material-icons-two-tone">calendar_today</i>Economic Calendar</a>
                    </li>
                    @endif

                    <!-- social mam pamm trade-->
                    @if ($checkSettings->TraderSettings('Pamm') || $checkSettings->TraderSettings('Mam') || $checkSettings->TraderSettings('Copy Reports'))
                    <li class="sidebar-title">
                        {{ __('page.social') }} {{ __('page.trade') }}
                    </li>
                    <!-- pamm -->
                    @if (PermissionService::has_permission('pamm','trader'))
                    <li class="{{ Request::is('user/user-pamm/*') ? 'show active-page' : '' }}">
                        <a href="javascript:void(0)"><i class="material-icons-two-tone">account_balance_wallet</i>PAMM<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu {{ Request::is('user/user-pamm/*') ? 'd-block' : '' }}">
                            <!-- pamm profile -->
                            @if (PermissionService::has_permission('pamm_profile','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/user-pamm/user-pamm-profile') ? 'active' : '' }}" href="{{ route('user.pamm.profile') }}">Pamm Profile</a>
                            </li>
                            @endif
                            <!-- pamm profile restration -->
                            @if (PermissionService::has_permission('pamm_registration','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/user-pamm/user-pamm-registration') ? 'active' : '' }}" href="{{ route('user.pamm.registraion') }}">Pamm Registration</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!-- mamm -->
                    @if (PermissionService::has_permission('mamm','trader'))
                    <li class="{{ Request::is('user/user-mam/*') ? 'show active-page' : '' }}">
                        <a href="javascript:void(0)"><i class="material-icons-two-tone">account_balance_wallet</i>MAMM<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu {{ Request::is('user/user-mam/*') ? 'd-block' : '' }}">
                            <!-- maange slave account -->
                            @if (PermissionService::has_permission('manage_slave_account','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/user-mam/manage-slave-account') ? 'active' : '' }}" href="{{ route('user.mam.manage.slave.account') }}">Manage Slave Account</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!-- copy trade -->
                    @if (PermissionService::has_permission('copy_trading','trader'))
                    <li class="{{ Request::is('user/user-copy/*') ? 'show active-page' : '' }}">
                        <a href="javascript:void(0)"><i class="material-icons-two-tone">account_balance_wallet</i>Copy Trading<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu {{ Request::is('user/user-copy/*') ? 'd-block' : '' }}">
                            <!-- social traders report -->
                            @if (PermissionService::has_permission('social_traders_report','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/user-copy/social-traders-report') ? 'active' : '' }}" href="{{ route('user.copy.social.traders.report') }}">Social Traders Report</a>
                            </li>
                            @endif
                            <!-- social activities reports -->
                            @if (PermissionService::has_permission('social_activities_report','trader'))
                            <li>
                                <a class="text-truncate {{ Request::is('user/user-copy/traders-activities-report') ? 'active' : '' }}" href="{{ route('user.copy.traders.activities.report') }}">Social Traders Activities Report</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @endif
                </ul>
            </div>
        </div>
        <div class="app-container">
            <div class="search">
                <form>
                    <input class="form-control" type="text" placeholder="Type here..." aria-label="Search">
                </form>
                <a href="#" class="toggle-search"><i class="material-icons">close</i></a>
            </div>
            <div class="app-header">
                <nav class="navbar navbar-light navbar-expand-lg">
                    <div class="container-fluid">
                        <div class="navbar-nav align-items-center" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link hide-sidebar-toggle-button" href="#"><i class="material-icons">first_page</i></a>
                                </li>
                            </ul>
                            @yield('bread_crumb')
                        </div>
                        <div class="d-flex">
                            <ul class="navbar-nav align-items-center">
                                <li class="nav-item hidden-on-mobile">
                                    <a class="nav-link language-dropdown-toggle" href="#" id="languageDropDown" data-bs-toggle="dropdown">
                                        @php $flag = "" @endphp
                                        @if (session()->get('locale') == 'fr')
                                        @php $lang = __('language.french') @endphp
                                        @php $flag = 'fr' @endphp
                                        @elseif(session()->get('locale') == 'de')
                                        @php $lang = __('language.german') @endphp
                                        @php $flag = 'lite-asset/assets/img/flags/germany.png' @endphp
                                        @elseif(session()->get('locale') == 'pt')
                                        @php $lang = __('language.portuguese') @endphp
                                        @php $flag = 'pt' @endphp
                                        @elseif(session()->get('locale') == 'zh')
                                        @php $lang = __('language.chinese') @endphp
                                        @php $flag = 'lite-asset/assets/img/flags/china.png' @endphp
                                        @else
                                        @php $lang = __('language.english') @endphp
                                        @php $flag = 'lite-asset/assets/img/flags/us.png' @endphp
                                        @endif
                                        <img src="{{ asset($flag) }}" alt="">
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end language-dropdown" aria-labelledby="languageDropDown">
                                        <li>
                                            <a class="dropdown-item lang-change" href="#" data-language="de">
                                                <img src="{{ asset('lite-asset/assets/img/flags/germany.png') }}" alt="DE">
                                                German
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item lang-change" href="#" data-language="en">
                                                <img src="{{ asset('lite-asset/assets/img/flags/us.png') }}" alt="EN">
                                                USA
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item lang-change" href="#" data-language="zh">
                                                <img src="{{ asset('lite-asset/assets/img/flags/china.png') }}" alt="ZH">
                                                Chinese
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link logoute_class language-dropdown-toggle" type="button" href="{{ route('logout') }}" class="flex-grow-1 m-r-xxs" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" data-bs-toggle="tooltip" data-bs-placement="top" title="Logout">
                                        {{-- <span class="bold-notifications-text">{{ __('page.sign_out') }}</span> --}}
                                        {{-- <i class="material-icons">LogoutTwoTone</i> --}}
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>

                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="app-content">
                <!-- main content -->
                @yield('content')
            </div>
        </div>
    </div>
    <span class="d-none" id="envSessionTime">{{ env('SESSION_LIFETIME') }}</span>
    <button type="button" id="sesstionLockButton" class="btn btn-primary d-none " data-bs-toggle="modal" data-bs-target="#sesstionLockPopup"></button>

    <!-- Modal -->
    <div class="modal fade" id="sesstionLockPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="sesstionLockPopupLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sesstionLockPopupLabel">Session Expire Soon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="font-size: 16px">
                    Your session will expire in <span id="modalMinutes"></span> seconds. Do you want to extend the session?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 14px">Close</button>
                    <button type="button" id="session_button_extent" class="btn btn-primary" style="font-size: 14px">Extent</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascripts -->
    <script src="{{ asset('lite-asset/assets/plugins/jquery/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('lite-asset/assets/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('lite-asset/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('lite-asset/assets/plugins/perfectscroll/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('lite-asset/assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ asset('lite-asset/assets/js/main.min.js') }}"></script>
    <script src="{{ asset('lite-asset/assets/js/custom.js') }}"></script>

    <script src="{{ asset('lite-asset/assets/plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('lite-asset/assets/js/pages/datepickers.js') }}"></script>
    <script src="{{ asset('lite-asset/assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <!-- <script src="{{ asset('lite-asset/assets/js/pages/charts-apex.js') }}"></script> -->

    <!--    <script src="{{ asset('lite-asset/assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('lite-asset/assets/js/pages/select2.js') }}"></script> -->
    {{-- <script src="{{ asset('lite-asset/assets/js/pages/dashboard.js') }}"></script> --}}
    <script src="{{ asset('lite-asset/assets/plugins/highlight/highlight.pack.js') }}"></script>
    <script src="{{ asset('lite-asset/assets/plugins/datatables/datatables.min.js') }}"></script>
    <!-- page js script -->
    @yield('corejs')
    <!-- <script src="{{ asset('comon-icon/js/all.min.js') }}"></script> -->
    @yield('page-js')
    <!--old script-->
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/common-ajax.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('admin-assets/src/js/core/confirm-alert.js') }}"></script>
    <script src="{{ asset('/common-js/custom-from-validation.js') }}"></script>
    <script src="{{ asset('comon-icon/js/all.min.js') }}"></script>
    <script src="{{asset('common-js/enter-key-handler.js')}}"></script>
    <script src="{{ asset('common-js/multistep-controller.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>

    <script>
        (function(window, document, $) {
            // convert trader to ib
            $(document).on('click', '#btn-convert-ib', function() {
                let label = $(this).find('#btn-label').text();
                let $this = $(this);
                $(this).find('#btn-label').html("<i class='fa-spin fas fa-circle-notch'></i>");
                Swal.fire({
                    icon: 'warning',
                    title: 'Are you sure? to request for IB!',
                    html: 'If you want to registration as IB please click OK, otherwise simply click cancel',

                    showCancelButton: true,
                    customClass: {
                        confirmButton: 'btn btn-warning',
                        cancelButton: 'btn btn-danger'
                    },
                }).then((willDelete) => {
                    if (willDelete.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: '/user/combined/ib-request/convert',
                            method: 'POST',
                            dataType: 'JSON',
                            success: function(data) {
                                if (data.status == true) {
                                    // notify('success', data.message, 'IB Request')
                                    let $url = '/user/combined/ib-request/mail';
                                    send_mail('IB Request', 'Please wait whilte we sending mail to user.', $url, true)
                                    setTimeout(() => {
                                        location.reload();
                                    }, 2000);
                                } else {
                                    notify('error', data.message, 'IB Request')
                                }
                                $($this).find('#btn-label').html(label);
                            }
                        })
                    } else {
                        $($this).find('#btn-label').html(label);
                    }
                });
            })
            // change language
            $(document).on('click', ".lang-change", function() {
                let lang = $(this).data('language');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/user/change-language',
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

        // var minutesLabel = document.getElementById("minutes");
        var secondsLabel = document.getElementById("minutes");
        var modalLabel = document.getElementById("modalMinutes")
        var totalSeconds = document.getElementById("envSessionTime").textContent;

        setInterval(setTime, 100000);
        var i = 0;

        function setTime() {
            --totalSeconds;
            if (totalSeconds <= 30) {
                modalLabel.innerHTML = pad(totalSeconds % 60);
                secondsLabel.innerHTML = pad(parseInt(totalSeconds / 60)) + ":" + pad(totalSeconds % 60) + " " +
                    "Seconds after Will be lock ";
                if (i == 0) {
                    $('#sesstionLockButton').click();
                    i = 1;
                }
            }


        }

        function pad(val) {
            var valString = val + "";
            if (valString.length < 2) {
                return "0" + valString;
            } else {
                return valString;
            }
        }

        $('#session_button_extent').click(function() {
            window.location.reload();
        })
    </script>
</body>

</html>