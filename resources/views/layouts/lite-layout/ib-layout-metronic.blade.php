<!DOCTYPE html>
<html lang="en">
@php
use App\Services\PermissionService;
@endphp

<head>
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 1 }}; {{ url('/ib/lock-screen' . '/' . base64_encode(auth()->user()->id) . '/' . base64_encode(url()->current())) }}" />
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
    <!-- theme css -->
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
    <!-- <link href="{{ asset('lite-asset/assets/css/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lite-asset/assets/css/custom.css') }}" rel="stylesheet">
 -->

    <!-- Theme Styles -->
    <link href="{{ asset('lite-asset/assets/css/main.min.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('lite-asset/assets/css/darktheme.css') }}" rel="stylesheet"> -->
    <link href="{{ asset('lite-asset/assets/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/common-css/data-list-style.css') }}">


    @if (get_client_theme_color() === 'light-layout')
    <link href="{{ asset('lite-asset/assets/css/custom-white-style.css') }}" rel="stylesheet">
    @elseif(get_client_theme_color() === 'dark-version')
    <link href="{{ asset('lite-asset/assets/css/darktheme.css') }}" rel="stylesheet">
    <link href="{{ asset('lite-asset/assets/css/custom-dark-style.css') }}" rel="stylesheet">
    @else
    <link href="{{ asset('lite-asset/assets/css/custom-white-style.css') }}" rel="stylesheet">
    <style>
        .input-rang-group-text {
            display: flex;
            align-items: center;
            padding: 0.453rem 1rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 2.01 !important;
            color: #6e6b7b;
            text-align: center;
            white-space: nowrap;
            background-color: #fff;
            border: 1px solid #d8d6de;
        }

        .btn {
            display: inline-block;
            padding: 9.5px 20px;
            border-radius: 5px;
            font-size: 14px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-transition: all .2s ease-in-out !important;
            -moz-transition: all .2s ease-in-out !important;
            -o-transition: all .2s ease-in-out !important;
            transition: all .2s ease-in-out !important;
        }
    </style>
    @endif
    <link rel="stylesheet" href="{{asset('common-css/metronic.css')}}">
    <!-- <link rel="stylesheet" href="{{asset('trader-assets/assets/css/custom_datatable.css')}}"> -->
    <!-- custom page css can added here -->
    @yield('page-css')
</head>
<?php

use App\Services\checkSettingsService;

$checkSettings = new checkSettingsService();

?>

<body class="bg-gray-100">
    <div class="app align-content-stretch d-flex flex-wrap">
        <div class="app-sidebar">

            <div class="logo d-flex justify-between align-items-center">
                <a href="{{ route('ib.dashboard') }}" class="logo-icon">
                    <img src="{{ get_user_logo() }}" height="40" alt="{{ config('app.name') }}"></a>
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
                        Apps
                    </li>
                    <!-- dashboard -->
                    <li class="{{ Request::is('ib/dashboard') ? 'active-page' : '' }}">
                        <a href="{{ route('ib.dashboard') }}" class=" {{ Request::is('ib/dashboard') ? 'active' : '' }}"><i class="material-icons-two-tone">dashboard</i>{{ __('page.dashboard') }}</a>
                    </li>
                    <!-- my admin -->
                    @if (PermissionService::has_permission('my_admin', 'ib'))
                    <li class="{{ Request::is('ib/ib-admin/*') ? 'show active-page' : '' }}">
                        <a href="#"><i class="material-icons-two-tone">person</i>{{ __('page.my-admin') }}<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu {{ Request::is('ib/ib-admin/*') ? 'd-block' : '' }} ">
                            @if (PermissionService::has_permission('profile_overview', 'ib'))
                            <li>
                                <a class="text-truncate {{ Request::is('ib/ib-admin/profile-overview') ? 'active' : '' }}" href="{{ route('ib.ib-admin.profile-overview') }}">{{ __('page.profile-overview') }}
                                </a>
                            </li>
                            @endif
                            @if (PermissionService::has_permission('settings', 'ib'))
                            <li>
                                <a class="text-truncate {{ Request::is('ib/ib-admin/settings') ? 'active' : '' }}" href="{{ route('ib.ib-admin-account-settings') }}">{{ __('page.settings') }}</a>
                            </li>
                            @endif
                            @if (PermissionService::has_permission('verification', 'ib'))
                            <li>
                                <a class="text-truncate {{ Request::is('ib/ib-admin/account-verification') ? 'active' : '' }}" href="{{ route('ib.ib-admin-account-verification') }}">{{ __('page.verification') }}</a>
                            </li>
                            @endif
                            @if (PermissionService::has_permission('banking', 'ib'))
                            <li>
                                <a class="text-truncate {{ Request::is('ib/ib-admin/user-banking') ? 'active' : '' }}" href="{{ route('ib.ib-admin.ib-banking') }}">{{ __('page.banking') }}</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!--nav divider-->
                    <li class="sidebar-title">
                        <hr>
                    </li>
                    <!-- Affiliates -->
                    @if (PermissionService::has_permission('affiliate', 'ib'))
                    <li class="{{ Request::is('ib/affiliates/*') ? 'show active-page' : '' }}">
                        <a href="#"><i class="material-icons-two-tone">analytics</i>{{ __('ib-menu-left.Affiliate') }}<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu {{ Request::is('ib/affiliates/*') ? 'd-block' : '' }} ">
                            @if (PermissionService::has_permission('ib_tree', 'ib'))
                            <li>
                                <a class="text-truncate {{ Request::is('ib/affiliates/ib-tree') ? 'active' : '' }}" href="{{ route('ib.my-ib.tree') }}">{{ __('ib-menu-left.IB Tree') }}
                                </a>
                            </li>
                            @endif
                            @if (PermissionService::has_permission('withdraw_reports', 'ib'))
                            <li>
                                <a class="text-truncate {{ Request::is('ib/affiliates/my-ib') ? 'active' : '' }}" href="{{ route('ib.my-ib.report') }}">{{ __('ib-menu-left.My IB (s)') }}
                                </a>
                            </li>
                            @endif
                            @if (PermissionService::has_permission('deposit_reports', 'ib'))
                            <li>
                                <a class="text-truncate {{ Request::is('ib/affiliates/my-clients') ? 'active' : '' }}" href="{{ route('ib.myclients.report') }}">{{ __('ib-menu-left.My Clients') }}
                                </a>
                            </li>
                            @endif
                            @if (PermissionService::has_permission('my_clients', 'ib'))
                            <li>
                                <a class="text-truncate {{ Request::is('ib/affiliates/clients-deposit-report') ? 'active' : '' }}" href="{{ route('ib.affilates.deposit-reports') }}">{{ __('ib-menu-left.Deposit Reports') }}
                                </a>
                            </li>
                            @endif
                            @if (PermissionService::has_permission('my_ib', 'ib'))
                            <li>
                                <a class="text-truncate {{ Request::is('ib/affiliates/clients-withdraw-report') ? 'active' : '' }}" href="{{ url('ib/affiliates/clients-withdraw-report') }}">
                                    {{ __('ib-menu-left.Withdraw Reports') }} </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!-- reports -->
                    @if (PermissionService::has_permission('reports', 'ib'))
                    <li class="{{ Request::is('ib/reports/*') ? 'show active-page' : '' }}">
                        <a href="#"><i class="material-icons-two-tone">menu</i>{{ __('page.reports') }}<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu {{ Request::is('ib/reports/*') ? 'd-block' : '' }}">
                            @if (PermissionService::has_permission('trade_commission', 'ib'))
                            <li>
                                <a class=" text-truncate {{ Request::is('ib/reports/ib-comission') ? 'active' : '' }}" href="{{ url('ib/reports/ib-comission') }}">{{ __('ib-menu-left.Trade Commission') }}</a>
                            </li>
                            @endif
                            @if (PermissionService::has_permission('withdraw_report', 'ib'))
                            <li>
                                <a class=" text-truncate {{ Request::is('ib/reports/withdraw') ? 'active' : '' }}" href="{{ url('ib/reports/withdraw') }}">IB Withdraw</a>
                            </li>
                            @endif
                            @if (PermissionService::has_permission('ib_to_trader_balance_trnasfer', 'ib'))
                            <li>
                                <a class="  text-truncate {{ Request::is('ib/reports/balance-transfer-ib-to-trader') ? 'active' : '' }}" href="{{ url('ib/reports/balance-transfer-ib-to-trader') }}">IB To Trader Transfer</a>
                            </li>
                            @endif
                            @if (PermissionService::has_permission('trader_to_ib_balance_transfer', 'ib'))
                            <li>
                                <a class=" text-truncate {{ Request::is('ib/reports/balance-transfer-trader-to-ib') ? 'active' : '' }}" href="{{ url('ib/reports/balance-transfer-trader-to-ib') }}">Trader To IB Transfer</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!-- Withdrawals -->
                    @if (PermissionService::has_permission('withdraw', 'ib'))
                    <li class="{{ Request::is('ib/withdraw/*') ? 'show active-page' : '' }}">
                        <a href="#"><i class="material-icons-two-tone">account_balance_wallet</i>{{ __('page.withdraw') }}<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu {{ Request::is('ib/withdraw/*') ? 'd-block' : '' }}">
                            @if (PermissionService::has_permission('bank_withdraw', 'ib'))
                            <li>
                                <a class=" text-truncate{{ Request::is('ib/withdraw/bank-withdraw') ? 'active' : '' }}" href="{{ route('ib.withdraw.bank-withdraw') }}">{{ __('ib-menu-left.Bank Withdraw') }}</a>
                            </li>
                            @endif
                            @if (PermissionService::has_permission('crypto_withdraw', 'ib'))
                            <li>
                                <a class="text-truncate {{ Request::is('ib/withdraw/crypto-withdraw') ? 'active' : '' }}" href="{{ route('ib.withdraw.crypto-withdraw') }}">{{ __('ib-menu-left.Crypto Withdraw') }}</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!-- transfer -->
                    @if (PermissionService::has_permission('transfer', 'ib'))
                    <li class="{{ Request::is('ib/transfer/*') ? 'show active-page' : '' }}">
                        <a href="#"><i class="material-icons-two-tone">paid</i>{{ __('ib-menu-left.Transfer') }}<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu {{ Request::is('uib/transfer/*') ? 'd-block' : '' }}">
                            @if (PermissionService::has_permission('ib_to_trader_transfer', 'ib'))
                            <li>
                                <a class="text-truncate {{ Request::is('ib/transfer/ib-to-trader') ? 'active' : '' }}" href="{{ route('ib.transfer.ib-to-trader') }}">{{ __('ib-menu-left.IB to Trader Transfer') }}
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <!--nav divider-->
                    <li class="sidebar-title">
                        <hr>
                    </li>

                    <!-- support-->
                    @if (PermissionService::has_permission('support', 'ib'))
                    <li class="{{ Request::is('ib/support/*') ? 'show active-page' : '' }}">
                        <a href="#"><i class="material-icons-two-tone">report</i>{{ __('page.support') }}<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu {{ Request::is('ib/support/*') ? 'd-block' : '' }}">
                            @if (PermissionService::has_permission('support_ticket', 'ib'))
                            <li>
                                <a class="text-truncate {{ Request::is('ib/support/ticket') ? 'active' : '' }}" href="{{ route('ib.support.ticket') }}">{{ __('support-ticket.support_ticket') }}</a>
                            </li>
                            @endif
                        </ul>
                    </li>
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
                            <ul class="navbar-nav">
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
    <!-- lock screen popup -->
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
                    Your session will expire in <span id="modalMinutes"></span> seconds. Do you want to extend the
                    session?
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

    <!-- <script src="{{ asset('lite-asset/assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('lite-asset/assets/js/pages/select2.js') }}"></script> -->
    {{-- <script src="{{ asset('lite-asset/assets/js/pages/dashboard.js') }}"></script> --}}
    <script src="{{ asset('lite-asset/assets/plugins/highlight/highlight.pack.js') }}"></script>
    <script src="{{ asset('lite-asset/assets/plugins/datatables/datatables.min.js') }}"></script>
    <!--old script-->
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/common-ajax.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('admin-assets/src/js/core/confirm-alert.js') }}"></script>
    <script src="{{ asset('/common-js/custom-from-validation.js') }}"></script>
    <!-- fontawesome -->
    <script src="{{ asset('comon-icon/js/all.min.js') }}"></script>
    <script src="{{asset('common-css/fontawesome/v6/js/all.js')}}"></script>
    <script src="{{ asset('common-js/enter-key-handler.js') }}"></script>
    @yield('corejs')
    @yield('page-js')
    @yield('customjs')
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