@extends(App\Services\systems\VersionControllService::get_layout('ib'))
@section('title', 'Settings')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<style>
    .error-msg {
        color: red !important;
    }

    .steper {
        width: 32px !important;
        height: 32px !important;
    }

    .vertical-line::after {
        content: "";
        background-color: var(--custom-primary);
        height: 100%;
        width: 2px;
        position: absolute;
        left: 31px;
        z-index: 1;
        top: 58%;
    }

    .last-connector::after {
        content: "";
        background-color: var(--custom-primary);
        height: 50%;
        width: 2px;
        position: absolute;
        left: 31px;
        z-index: 1;
        top: 19px;
    }

    .last-connector-vertical::after {
        content: "";
        background-color: var(--custom-primary);
        height: 2px;
        width: 102%;
        position: absolute;
        left: 17px;
        z-index: 1;
        top: 113px;
    }

    .accounts-tab-list .moving-tab {
        width: 33.33% !important;
    }

    .loader-container {
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        padding-top: 13%;
    }

    .z-index-sticky {
        z-index: 10000;
    }

    /* Start: date picker css */
    .date_picker_field:focus {
        color: #495057;
        background-color: #fff;
        border-color: var(--custom-primary);
        outline: 0;
        box-shadow: 0 0 0 2px var(--custom-primary);
    }

    .date_picker_field {
        background-color: #fff;
    }

    #date_of_birth {
        border-top-right-radius: 0.5rem !important;
        border-bottom-right-radius: 0.5rem !important;
        font-size: 0.9rem;
        padding-left: 1rem;
        border-left: none !important;
    }

    .input-rang-group-date-logo {
        /* display: flex; */
        align-items: center;
        padding: 0.75rem 0.6rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.45;
        color: #6e6b7b;
        text-align: center;
        white-space: nowrap;
        background-color: #fff;
        border-top-left-radius: 0.5rem !important;
        border-bottom-left-radius: 0.5rem !important;
        border-right: none !important;
        border: 1px solid #d8d6de !important;
    }

    .social-placeholder {
        margin-left: 32px !important;
    }

    .trans-tab .moving-tab {
        padding: 0px;
        transition: all 0.5s ease 0s;
        transform: translate3d(0px, 0px, 0px);
        min-width: 50%
    }

    .settings-nav-link {
        display: flex;
    }

    /* End: date picker css */
</style>
@stop
<!-- bread crumb -->
@section('bread_crumb')
{!!App\Services\systems\BreadCrumbService::get_ib_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
@php
$OtpSetting = App\Models\UserOtpSetting::where('user_id', auth()->user()->id)->first();
$adminOtpSetting = App\Models\OtpSetting::first();
use App\Services\AllFunctionService;
use Stevebauman\Location\Facades\Location;
@endphp
<div class="container-fluid mt-4">
    <div class="row align-items-center">
        <div class="col-lg-4 col-sm-12">
            <div class="nav-wrapper position-relative end-0">
                <ul class="nav nav-pills nav-fill p-1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1 active  active " data-bs-toggle="tab" href="#profile-settings" role="tab" aria-selected="true">
                            {{ __('page.profile-settings') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" href="#tab-social" role="tab" aria-selected="false">
                            {{ __('page.social') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="tab-content custom-height-con">
    <div id="profile-settings" class="tab-pane active">
        <div class="container-fluid my-3 py-3">
            <div class="row mb-5">
                <div class="col-lg-3">
                    <div class="card position-sticky top-1 nav-wrapper position-relative end-0">
                        <ul class="nav flex-column bg-white border-radius-lg p-3 nav-pills nav-fill flex-column p-1" role="tablist">
                            <!-- nav item cprofile -->
                            <li class="nav-item">
                                <a class="nav-link text-body mb-2 px-3 py-1 active text-start settings-nav-link" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="preview" aria-selected="true">
                                    <div class="icon me-2">
                                        <svg class="text-dark mb-1" width="16px" height="16px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>{{ __('page.spaceship') }}</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-1720.000000, -592.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(4.000000, 301.000000)">
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
                                    <span class="text-sm">{{ __('page.profile') }}</span>
                                </a>
                            </li>
                            <!-- change password -->
                            <li class="nav-item">
                                <a class="nav-link text-body mb-2 px-3 py-1 text-start settings-nav-link" data-bs-toggle="tab" href="#change-password" role="tab" aria-controls="preview" aria-selected="true">
                                    <div class="icon me-2">
                                        <svg class="text-dark mb-1" width="16px" height="16px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>{{ __('page.box-3d-50') }}</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(603.000000, 0.000000)">
                                                            <path class="color-background" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z">
                                                            </path>
                                                            <path class="color-background" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z" opacity="0.7"></path>
                                                            <path class="color-background" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z" opacity="0.7"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="text-sm">{{ __('page.change-password') }}</span>
                                </a>
                            </li>
                            <!-- change transaction password -->
                            <li class="nav-item">
                                <a class="nav-link text-body mb-2 px-3 py-1 text-start settings-nav-link" data-bs-toggle="tab" href="#change-transaction-password" role="tab" aria-controls="preview" aria-selected="true">
                                    <div class="icon me-2">
                                        <svg class="text-dark mb-1" width="16px" height="16px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>{{ __('page.box-3d-50') }}</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(603.000000, 0.000000)">
                                                            <path class="color-background" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z">
                                                            </path>
                                                            <path class="color-background" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z" opacity="0.7"></path>
                                                            <path class="color-background" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z" opacity="0.7"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="text-sm">{{ __('page.change_transaction_password') }}</span>
                                </a>
                            </li>


                            <!-- nav item 2fa -->
                            <li class="nav-item">
                                <a class="nav-link text-body mb-2 px-3 py-1 text-start settings-nav-link" data-bs-toggle="tab" href="#two-fa" role="tab" aria-controls="preview" aria-selected="true">
                                    <div class="icon me-2">
                                        <svg class="text-dark mb-1" width="16px" height="16px" viewBox="0 0 40 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>{{ __('page.switches') }}</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-1870.000000, -440.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(154.000000, 149.000000)">
                                                            <path class="color-background" d="M10,20 L30,20 C35.4545455,20 40,15.4545455 40,10 C40,4.54545455 35.4545455,0 30,0 L10,0 C4.54545455,0 0,4.54545455 0,10 C0,15.4545455 4.54545455,20 10,20 Z M10,3.63636364 C13.4545455,3.63636364 16.3636364,6.54545455 16.3636364,10 C16.3636364,13.4545455 13.4545455,16.3636364 10,16.3636364 C6.54545455,16.3636364 3.63636364,13.4545455 3.63636364,10 C3.63636364,6.54545455 6.54545455,3.63636364 10,3.63636364 Z" opacity="0.6"></path>
                                                            <path class="color-background" d="M30,23.6363636 L10,23.6363636 C4.54545455,23.6363636 0,28.1818182 0,33.6363636 C0,39.0909091 4.54545455,43.6363636 10,43.6363636 L30,43.6363636 C35.4545455,43.6363636 40,39.0909091 40,33.6363636 C40,28.1818182 35.4545455,23.6363636 30,23.6363636 Z M30,40 C26.5454545,40 23.6363636,37.0909091 23.6363636,33.6363636 C23.6363636,30.1818182 26.5454545,27.2727273 30,27.2727273 C33.4545455,27.2727273 36.3636364,30.1818182 36.3636364,33.6363636 C36.3636364,37.0909091 33.4545455,40 30,40 Z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="text-sm">2FA</span>
                                </a>
                            </li>
                            <!-- nav item notifications -->
                            <li class="nav-item">
                                <a class="nav-link text-body mb-2 px-3 py-1 text-start d-none settings-nav-link" data-bs-toggle="tab" href="#notifications" role="tab" aria-controls="preview" aria-selected="true">
                                    <div class="icon me-2">
                                        <svg class="text-dark mb-1" width="16px" height="16px" viewBox="0 0 44 43" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>{{ __('page.megaphone') }}</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-2168.000000, -591.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(452.000000, 300.000000)">
                                                            <path class="color-background" d="M35.7958333,0.273166667 C35.2558424,-0.0603712374 34.5817509,-0.0908856664 34.0138333,0.1925 L19.734,7.33333333 L9.16666667,7.33333333 C4.10405646,7.33333333 0,11.4373898 0,16.5 C0,21.5626102 4.10405646,25.6666667 9.16666667,25.6666667 L19.734,25.6666667 L34.0138333,32.8166667 C34.5837412,33.1014624 35.2606401,33.0699651 35.8016385,32.7334768 C36.3426368,32.3969885 36.6701539,31.8037627 36.6666942,31.1666667 L36.6666942,1.83333333 C36.6666942,1.19744715 36.3370375,0.607006911 35.7958333,0.273166667 Z">
                                                            </path>
                                                            <path class="color-background" d="M38.5,11 L38.5,22 C41.5375661,22 44,19.5375661 44,16.5 C44,13.4624339 41.5375661,11 38.5,11 Z" opacity="0.601050967"></path>
                                                            <path class="color-background" d="M18.5936667,29.3333333 L10.6571667,29.3333333 L14.9361667,39.864 C15.7423448,41.6604248 17.8234451,42.4993948 19.6501416,41.764381 C21.4768381,41.0293672 22.3968823,38.982817 21.7341667,37.1286667 L18.5936667,29.3333333 Z" opacity="0.601050967"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="text-sm">{{ __('page.notifications') }}</span>
                                </a>
                            </li>
                            <!-- nav item settings -->
                            <li class="nav-item">
                                <a class="nav-link text-body mb-2 px-3 py-1 text-start btn-session-block settings-nav-link" data-bs-toggle="tab" href="#sessions" role="tab" aria-controls="preview" aria-selected="true">
                                    <div class="icon me-2">
                                        <svg class="text-dark mb-1" width="16px" height="16px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>{{ __('page.settings') }}</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(304.000000, 151.000000)">
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
                                    <span class="text-sm">{{ __('page.sessions') }}</span>
                                </a>
                            </li>
                            <!-- nav item otp verifications -->
                            @if ($adminOtpSetting->deposit == true ||
                            $adminOtpSetting->withdraw == true ||
                            $adminOtpSetting->transfer == true ||
                            $adminOtpSetting->account_create == true)
                            <li class="nav-item">
                                <a class="nav-link text-body mb-2 px-3 py-1 text-start btn-otp-block" data-bs-toggle="tab" href="#otpVerification" role="tab" aria-controls="preview" aria-selected="true">
                                    <div class="icon me-2">
                                        <svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>Document</title>
                                            <g id="Basic-Elements" stroke="none" stroke-width="1" fill="var(--custom-primary)" fill-rule="evenodd">
                                                <g id="Rounded-Icons" transform="translate(-1870.000000, -591.000000)" fill="var(--custom-primary)" fill-rule="nonzero">
                                                    <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                                        <g id="document" transform="translate(154.000000, 300.000000)">
                                                            <path class="color-background" d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z" id="Path" opacity="0.603585379"></path>
                                                            <path class="color-background" d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z" id="Shape"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="text-sm">OTP Verification</span>
                                </a>
                            </li>
                            @endif

                        </ul>
                    </div>
                </div>
                <div class="col-lg-9 mt-lg-0 mt-4">
                    <!-- Card Profile -->
                    <div class="tab-content">
                        <!-- profile -->
                        <div id="profile" class="tab-pane active">
                            <div class="card card-body" id="profile-card">
                                <div class="row justify-content-center align-items-center">
                                    <div class="col-sm-auto col-4">
                                        <div class="avatar avatar-xl position-relative">
                                            <img src="{{ asset('admin-assets/app-assets/images/avatars/' . $avatar) }}" alt="bruce" class="w-100 border-radius-lg shadow-sm">
                                        </div>
                                    </div>
                                    <div class="col-sm-auto col-8 my-auto">
                                        <div class="h-100">
                                            <h5 class="mb-1 font-weight-bolder">
                                                {{ isset(auth()->user()->name) ? ucwords(auth()->user()->name) : ''}}
                                            </h5>
                                            <p class="mb-0 font-weight-bold text-sm">
                                                {{ isset(auth()->user()->type) ? ucwords(auth()->user()->type) : '' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-sm-auto ms-sm-auto mt-sm-0 mt-3 d-flex">

                                    </div>
                                </div>
                            </div>
                            <!-- Card Basic Info -->
                            <div class="card mt-4" id="basic-info">
                                <div class="card-header">
                                    <h5>{{ __('page.basic-info') }}</h5>
                                </div>
                                <form class="card-body pt-0" action="{{ route('ib.ib-admin-settings.basic-info') }}" method="POST" id="basic-info-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label">{{ __('page.full-name') }}</label>
                                            <div class="input-group">
                                                <input id="full-name" name="full_name" class="form-control" type="text" placeholder="john arifin" required="required" value="{{ isset(auth()->user()->name) ? ucwords(auth()->user()->name) : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">{{ __('page.email') }}</label>
                                            <div class="input-group">
                                                <input id="email" name="email" class="form-control " type="email" placeholder="exampl@example.com" required="required" disabled value="{{ isset(auth()->user()->email) ? auth()->user()->email : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label mt-4">{{ __('page.gender') }}</label>
                                            <select class="form-control" name="gender" id="choices-gender">
                                                <option value="Male" <?= $user_description->gender == 'Male' ? 'selected' : '' ?>>
                                                    {{ __('page.male') }}
                                                </option>
                                                <option value="Female" <?= $user_description->gender == 'Female' ? 'selected' : '' ?>>
                                                    {{ __('page.female') }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label mt-4">{{ __('page.phone-number') }}</label>
                                            <div class="input-group">
                                                <input id="phone" name="phone" class="form-control" type="text" placeholder="+40 735 631 620" value="{{ isset($user_description->phone) ? ucwords($user_description->phone) : '' }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label mt-4">{{ __('page.birth-date') }}</label>
                                            <div class="col-12 d-flex">
                                                <span class="input-rang-group-date-logo">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                                    </svg>
                                                </span>
                                                <input type="text" id="date_of_birth" class="flatpickr-basic border w-100 date_picker_field" name="date_of_birth" placeholder="YY-MM-DD" value="{{ isset($user_description->date_of_birth) ? $user_description->date_of_birth : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label mt-4">{{ __('page.country') }}</label>
                                            <select class="form-control" name="country" id="country">
                                                @foreach ($countries as $country)
                                                <option value="{{ isset($country->id) ? $country->id : ''}}" <?= $country->id == $user_description->country_id ? 'selected' : '' ?>>
                                                    {{ isset($country->name) ? $country->name : ''}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label mt-4">{{ __('page.state') }}</label>
                                            <div class="input-group">
                                                <input id="state" name="state" class="form-control" type="text" placeholder="Your state" value="{{ isset($user_description->state) ? ucwords($user_description->state) :'' }}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label mt-4">{{ __('page.city') }}</label>
                                            <div class="input-group">
                                                <input id="city" name="city" class="form-control" type="text" placeholder="Your City" value="{{ isset($user_description->city) ? ucwords($user_description->city) : ''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label mt-4">{{ __('page.address') }}</label>
                                            <textarea name="address" id="address" rows="3" class="form-control">{{ ucwords($user_description->address) }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label mt-4">{{ __('page.zip-code') }}</label>
                                            <input class="form-control" id="zipcode" name="zipcode" type="text" value="{{ isset($user_description->zip_code) ? ucwords($user_description->zip_code) : '' }}" placeholder="Your Zipcode" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" data-label="Submit Request" id="btn-submit-request" data-btnid="btn-submit-request" data-callback="ib_info_call_back" data-loading="<i class='fa-spinner fas fa-circle-notch'></i>" data-form="basic-info-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto mb-0 w-lg-25 mt-4 float-end">{{ __('page.submit-request') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- change password -->
                        <div id="change-password" class="tab-pane fade">
                            <!-- Card Change Password -->
                            <div class="card" id="password-card">
                                <div class="card-body pt-0">
                                    @csrf
                                    <div class="row">
                                        <!-- inner tab change password -->
                                        <div class="nav-wrapper position-relative end-0 pt-3 col-12">
                                            <ul class="nav nav-pills nav-fill p-1 trans-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#inner-change-password" role="tab" aria-controls="profile" aria-selected="true">
                                                        Change Password
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#inner-reset-password" role="tab" aria-controls="dashboard" aria-selected="false">
                                                        Reset Password
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- inner tab content change password -->
                                        <div class="col-12 tab-content" id="inner-pills-tabContent">
                                            <div class="tab-pane fade show active" id="inner-change-password" role="tabpanel" aria-labelledby="pills-home-tab">
                                                <!-- change password form -->
                                                <form action="{{ route('ib.ib-admin-settings.update-password') }}" id="password-update-form" method="post">
                                                    @csrf
                                                    <!-- change password note -->
                                                    <div class="row mt-3">
                                                        <div class="col-6 col-md-4 mx-auto">
                                                            <h5 class="mt-5">{{ __('page.password-requirements') }}</h5>
                                                            <p class="text-muted mb-2">
                                                                {{ __('page.please-follow-this-guide-for-a-strong-password') }}:
                                                            </p>
                                                            <ul class="text-muted ps-4 mb-0 float-start">
                                                                <li>
                                                                    <span class="text-sm">{{ __('page.one-special-characters') }}</span>
                                                                </li>
                                                                <li>
                                                                    <span class="text-sm">{{ __('page.min-6-characters') }}</span>
                                                                </li>
                                                                <li>
                                                                    <span class="text-sm">{{ __('page.one-number-2-are-recommended') }}</span>
                                                                </li>
                                                                <li>
                                                                    <span class="text-sm">{{ __('page.change-it-often') }}</span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <!-- change password input field -->
                                                        <div class="col-6 col-md-4 mx-auto">
                                                            <label class="form-label">{{ __('page.current-password') }}</label>
                                                            <div class="form-group">
                                                                <input class="form-control" type="password" id="current_password" name="current_password" placeholder="Current password">
                                                            </div>
                                                            <label class="form-label">{{ __('page.new-password') }}</label>
                                                            <div class="form-group">
                                                                <input class="form-control" type="password" id="new_password" name="new_password" placeholder="New password">
                                                            </div>
                                                            <label class="form-label">{{ __('page.confirm-new-password') }}</label>
                                                            <div class="form-group">
                                                                <input class="form-control" type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password">
                                                            </div>
                                                            <button type="button" data-label="Submit Request" id="btn-submit-request-pass" data-btnid="btn-submit-request-pass" data-callback="password_update_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="password-update-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto mb-0 mt-4 float-end" data-submit_wait="">{{ __('page.submit-request') }}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="inner-reset-password" role="tabpanel" aria-labelledby="pills-profile-tab">
                                                <!-- reset password form -->
                                                <form class="card-body pt-0" action="{{ route('ib.ib-admin-settings.reset-password') }}" id="create_password_form" method="post">
                                                    @csrf
                                                    <div class="row mt-3">
                                                        <div class="col-4 mx-auto">
                                                            <p class="text-muted mb-2 text-strong">
                                                                {{ __('page.please-follow-this-guide-for-a-strong-password') }}:
                                                            </p>
                                                            <ul class="text-muted ps-4 mb-0 float-start ">
                                                                <li>
                                                                    <span class="text-sm">{{ __('page.one-special-characters') }}</span>
                                                                </li>
                                                                <li>
                                                                    <span class="text-sm">{{ __('page.min-6-characters') }}</span>
                                                                </li>
                                                                <li>
                                                                    <span class="text-sm">{{ __('page.one-number-2-are-recommended') }}</span>
                                                                </li>
                                                                <li>
                                                                    <span class="text-sm">{{ __('page.change-it-often') }}</span>
                                                                </li>
                                                            </ul>
                                                        </div>

                                                        <div class="col-6 mx-auto" id="email_verification_section">
                                                            <div class="form-group ">
                                                                <h6 class="mb-5">Create Transection Password</h6>
                                                                <div class="mb-3">
                                                                    <input type="email" name="forgot_email" class="form-control forgot_email" placeholder="Find Your Account By Email" value="{{isset(auth()->user()->email) ? auth()->user()->email : ''}}" aria-label="Email" readonly required>
                                                                </div>
                                                                <input type="hidden" id="submit_email_form" name="submit_email_form" value="ftp_email">
                                                                <input type="hidden" name="user_type" value="0">
                                                                <div class="text-center">
                                                                    <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0" id="create_password_btn" onclick="_run(this)" data-el="fg" data-form="create_password_form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="reset_password_callback" data-btnid="create_password_btn">
                                                                        Create Password
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--End: find othenticated user -->
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- change transaction password tab content -->
                        <div id="change-transaction-password" class="tab-pane fade vh-100">
                            <!-- Card Change Transaction Password -->
                            <div class="card" id="password-card">
                                <div class="card-body p-4">
                                    <!-- tab button change transaction password -->
                                    <div class="nav-wrapper position-relative end-0 trans-tab">
                                        <ul class="nav nav-pills nav-fill mb-5 bg-transparent" id="pills-tab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active w-100" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">
                                                    Change Transaction Password</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link w-100" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Reset Transaction Password</button>
                                            </li>

                                        </ul>
                                    </div>
                                    <!-- transaction password tab content -->
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                                            <form class="card-body pt-0" action="{{ route('ib.ib-admin-settings.update-transaction-password') }}" id="transaction-password-update-form" method="post">
                                                @csrf
                                                <div class="row gx-4">
                                                    <!-- note for change transaction password -->
                                                    <div class="col-4">
                                                        <p class="text-muted mb-2 text-strong">
                                                            {{ __('page.please-follow-this-guide-for-a-strong-password') }}:
                                                        </p>
                                                        <ul class="text-muted ps-4 mb-0 float-start ">
                                                            <li>
                                                                <span class="text-sm">{{ __('page.one-special-characters') }}</span>
                                                            </li>
                                                            <li>
                                                                <span class="text-sm">{{ __('page.min-6-characters') }}</span>
                                                            </li>
                                                            <li>
                                                                <span class="text-sm">{{ __('page.one-number-2-are-recommended') }}</span>
                                                            </li>
                                                            <li>
                                                                <span class="text-sm">{{ __('page.change-it-often') }}</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <!-- change transaction password -->
                                                    <div class="col-6 mx-auto" id="transaction-password-section">
                                                        <label class="form-label">{{ __('page.current-transaction-password') }}</label>
                                                        <div class="form-group">
                                                            <input class="form-control" type="password" id="current_transaction_password" name="current_transaction_password" placeholder="Current password">
                                                        </div>
                                                        <div class="password_gen ">
                                                            <div class="form-group pasGen-form-group password_ch_toltip">
                                                                <button class="copy_btn" type="button">Copy</button>
                                                                <label for="old-password">{{ __('page.new-transaction-password') }}</label>
                                                                <div class="input-group">
                                                                    <input class="form-control copy_password check_password_chrac copy-pass-input" name="new_transaction_password" data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" placeholder="New Password" type="password" id="new_transaction_password">
                                                                    <span class="input-group-text position-relative bg-gradient-primary cursor-pointer btn-gen-password " style="padding:13px">
                                                                        <i class="fas fa-key"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <label class="form-label">{{ __('page.confirm-new-transaction-password') }}</label>
                                                            <div class="form-group">
                                                                <input class="form-control password_gen" rel="gp" type="password" id="confirm_transaction_password" name="confirm_transaction_password" placeholder="Confirm password">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <button class="btn bg-gradient-primary ms-auto float-end mb-0 mt-4 btn-submit-request" type="button" data-label="Submit Request" id="btn-transaction-pass" data-btnid="btn-transaction-pass" data-callback="transaction_password_update_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="transaction-password-update-form" data-el="fg" onclick="_run(this)" style="width:200px">{{ __('page.submit-request') }}</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                                            <form class="card-body pt-0" action="{{ route('ib.ib-admin.settings.create.transection.password') }}" id="create_trans_password_form" method="post">
                                                @csrf
                                                <div class="row gx-4">
                                                    <!-- transaction password reset note -->
                                                    <div class="col-4">
                                                        <p class="text-muted mb-2 text-strong">
                                                            {{ __('page.please-follow-this-guide-for-a-strong-password') }}:
                                                        </p>
                                                        <ul class="text-muted ps-4 mb-0 float-start ">
                                                            <li>
                                                                <span class="text-sm">{{ __('page.one-special-characters') }}</span>
                                                            </li>
                                                            <li>
                                                                <span class="text-sm">{{ __('page.min-6-characters') }}</span>
                                                            </li>
                                                            <li>
                                                                <span class="text-sm">{{ __('page.one-number-2-are-recommended') }}</span>
                                                            </li>
                                                            <li>
                                                                <span class="text-sm">{{ __('page.change-it-often') }}</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <!-- transaction pssword reset input -->
                                                    <div class="col-6 mx-auto" id="email_verification_section">
                                                        <div class="form-group ">
                                                            <h6 class="mb-5">Create Transection Password</h6>
                                                            <div class="mb-3">
                                                                <input type="email" name="forgot_email" class="form-control forgot_email" placeholder="Find Your Account By Email" value="{{ isset(auth()->user()->email) ? auth()->user()->email : ''}}" aria-label="Email" readonly required>
                                                            </div>
                                                            <input type="hidden" id="submit_email_form" name="submit_email_form" value="ftp_email">
                                                            <input type="hidden" name="user_type" value="0">
                                                            <div class="text-center">
                                                                <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0" id="create_trans_password_btn" onclick="_run(this)" data-el="fg" data-form="create_trans_password_form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="findTPemailCallBack" data-btnid="create_trans_password_btn">
                                                                    Create Password
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--End: find othenticated user -->
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <div id="two-fa" class="tab-pane fade">
                            <!-- Card Change Password -->
                            <div class="card" id="2fa-card">
                                <div class="card-header d-flex">
                                    <h5 class="mb-0">{{ __('page.two-factor-authentication') }}</h5>
                                    <!-- <span class="badge badge-success ms-auto">Enabled</span> -->
                                </div>
                                <div class="row">
                                    <div class="col-11 mx-auto">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td class="ps-1" colspan="4">
                                                        <div class="my-auto">
                                                            <span class="text-dark d-block text-sm">{{ __('page.secure-by') }}</span>
                                                            <span class="text-xs font-weight-normal">{{ __('page.no-additional-check-required') }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                            <input class="form-check-input switch-2fa" type="checkbox" id="noAuthCheck" value="no_auth" <?= auth()->user()->email_auth == 0 && auth()->user()->g_auth == 0 ? 'checked' : '' ?> />
                                                        </div>
                                                    </td>
                                                </tr>
                                                <hr class="horizontal dark">
                                                <tr>
                                                    <td class="ps-1" colspan="4">
                                                        <div class="my-auto">
                                                            <span class="text-dark d-block text-sm">{{ __('page.secure-by') }}</span>
                                                            <span class="text-xs font-weight-normal">{{ __('page.auto-email-authentication') }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                            <input class="form-check-input switch-2fa" type="checkbox" id="mailAuthCheck" value="mail_auth" <?= auth()->user()->email_auth == 1 ? 'checked' : '' ?> />
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="ps-1" colspan="4">
                                                        <div class="my-auto">
                                                            <span class="text-dark d-block text-sm">{{ __('page.secure-by') }}</span>
                                                            <span class="text-xs font-weight-normal">{{ __('page.google-2FA-authentication') }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                            <input class="form-check-input switch-2fa" type="checkbox" id="googleAuthCheck" value="google_auth" <?= auth()->user()->g_auth == 1 ? 'checked' : '' ?> />
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-google-auth row" style="display: none">
                                    @php
                                    use App\Services\GoogleAuthService;
                                    $google = new GoogleAuthService();
                                    $secret = $google->createSecret();
                                    $qrCodeUrl = $google->getQRCodeGoogleUrl(auth()->user()->email, $secret, config('app.name'));
                                    @endphp
                                    <ul class="col-11 mx-auto" id="step">
                                        <!-- google auth setup form -->
                                        <form class="mx-2" action="{{ route('ib.ib-admin-settings.google-security-setting') }}" method="post" enctype="multipart/form-data" id="google_auth_setup_form">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ isset(auth()->user()->id) ? auth()->user()->id : ''}}">
                                            <li class="list-group-item active">
                                                <h5>{{ __('page.google-authenticator') }}</h5>
                                            </li>
                                            <li class="list-group-item d-flex align-items-center position-relative border-0">
                                                <div class="col-4">
                                                    <div class="d-flex">
                                                        <span class="steper border-2 border-primary border text-center rounded-circle text-primary font-weight-bolder d-block z-index-2 bg-body">1</span>
                                                        <h6 class="mb-0 ms-3">
                                                            {{ __('page.download-2-FA-backup-key') }}:
                                                        </h6>
                                                    </div>
                                                </div>

                                                <div class="col-8 p-4 vertical-line">
                                                    <div class="form-group mb-0">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control d-block" id="secret_key" name="secret_key" value="{{isset($secret) ? $secret : ''}}" aria-describedby="secret_key">
                                                            <span class="input-group-text position-relative" style="padding: 13px;" data-clipboard-target="#secret_key" id="copy_secret_key"> <i class="fas fa-download"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center position-relative border-0">
                                                <div class="col-4">
                                                    <div class="d-flex">
                                                        <span class="steper border-2 border-primary border text-center rounded-circle text-primary font-weight-bolder d-block z-index-2 bg-body">2</span>
                                                        <h6 class="ms-3 mb-0">{{ __('page.download-and-install') }}:
                                                        </h6>
                                                    </div>
                                                </div>
                                                <div class="col-8 p-4 vertical-line">
                                                    <div class="dapp d-flex">
                                                        <a class="w-100" href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank">
                                                            <img class="img-fluid w-100 h-100" style="padding: 0 2.5px 0 0;" src="{{ asset('trader-assets/assets/img/logos/brands/iphone.png') }}" alt="App Store">
                                                        </a>

                                                        <a class="w-100" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&amp;hl=en" target="_blank">
                                                            <img class="img-fluid  w-100" style="padding: 0 0 0 2.5px;" src="{{ asset('trader-assets/assets/img/logos/brands/android.png') }}" alt="App Store">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="clr"></div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center position-relative border-0">
                                                <div class="col-4 position-relative">
                                                    <div class="d-flex last-connector-vertical">
                                                        <span class="steper border-2 border-primary border text-center rounded-circle text-primary font-weight-bolder block z-index-2 bg-body">3</span>
                                                        <h6 class="ms-3">{{ __('page.scan-QR') }}:</h6>
                                                    </div>
                                                    <div id="qrcode" class="ms-5">
                                                        <img class="z-index-2 position-relative" src='<?= $qrCodeUrl ?>'>
                                                    </div>
                                                </div>
                                                <div class="code_in col-8 p-4 last-connector">
                                                    <h6>{{ __('page.enter-2FA-code-form-the-app') }}:</h6>
                                                    <div class="input-group mb-md">
                                                        <input class="form-control d-block" type="text" onfocus="focused(this)" onfocusout="defocused(this)" name="v_code" placeholder="Enter 2FA verification code form the app">
                                                    </div>
                                                </div>
                                            </li>
                                        </form>
                                        <li class="list-group-item d-flex align-items-center position-relative border-0">
                                            <div class="col-4 position-relative">
                                                &nbsp;
                                            </div>
                                            <div class="code_in col-8 p-4">
                                                <button type="button" class="btn bg-gradient-primary float-end" style="width: 200px" id="googleAuthSetupBtn" onclick="_run(this)" data-el="fg" data-form="google_auth_setup_form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="googleAuthSetupCallBack" data-btnid="googleAuthSetupBtn">{{ __('page.save-change') }}</button>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="accounts" class="tab-pane fade">
                            <!-- Card Accounts -->
                            <div class="card" id="accounts-card">
                                <div class="card-body pt-0">
                                    <div class="container-fluid">
                                        <div class="nav-wrapper position-relative end-0 my-3">
                                            <ul class="nav nav-pills nav-fill p-1 accounts-tab-list" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="true">
                                                        {{ __('page.settings') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#account-create" role="tab" aria-controls="create-account" aria-selected="false">
                                                        {{ __('page.live-account') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#account-create" role="tab" aria-controls="create-account" aria-selected="false">
                                                        {{ __('page.demo-account') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="tab-content mt-5" id="accont-content">
                                            <div class="tab-pane fade" id="account-create" role="tabpanel" aria-labelledby="account-create">
                                                <form class="form-demo" action="{{ route('ib.trading-account.open-demo-account-form') }}" method="post" id="demo-account-form">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-4 text-center">
                                                            <div class="avatar avatar-xxl position-relative">
                                                                <img id="platform-logo" src="{{ asset('trader-assets/assets/img/logos/platform-logo/mt5.png') }}" class="border-radius-md" alt="team-2">
                                                                <a href="javascript:;" class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2">
                                                                    <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" aria-hidden="true" data-bs-original-title="Edit Image" aria-label="Edit Image"></i><span class="sr-only">{{ __('page.edit-image') }}</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 mx-auto">
                                                            <div class="form-group">
                                                                <label for="server">Server</label>
                                                                <select class="form-control multisteps-form__input" id="server" name="platform">
                                                                    <option value="">
                                                                        {{ __('page.choose-a-server') }}
                                                                    </option>
                                                                    {!! $server !!}
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="client-group">{{ __('page.account-type') }}</label>
                                                                <select class="form-control multisteps-form__input" id="client-group" name="account_type">
                                                                    <option value="">
                                                                        {{ __('page.choose-an-account-type') }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="leverage">{{ __('page.leverage') }}</label>
                                                                <select class="form-control multisteps-form__input" id="leverage" name="leverage">
                                                                    <option value="">
                                                                        {{ __('page.choose-a-leverage') }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="settings">
                                                <table class="table datatables-ajax bg-gray-100 table-striped table-hover w-100" id="datatables-ajax">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('page.account') }}</th>
                                                            <th>{{ __('page.server') }}</th>
                                                            <th>{{ __('page.leverage') }}</th>
                                                            <th>{{ __('page.balance') }}</th>
                                                            <th>{{ __('page.action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="notifications" class="tab-pane fade">
                            <!-- Card Notifications -->
                            <div class="card" id="notifications-card">
                                <div class="card-header">
                                    <h5>{{ __('page.notifications') }}</h5>
                                    <p class="text-sm">
                                        {{ __('page.choose-how-you-receive-notifications-these-notification-settings-apply-to-the-things-you’re-watching') }}
                                    </p>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="ps-1" colspan="4">
                                                        <p class="mb-0">{{ __('page.activity') }}</p>
                                                    </th>
                                                    <th class="text-center">
                                                        <p class="mb-0">{{ __('page.email') }}</p>
                                                    </th>
                                                    <th class="text-center">
                                                        <p class="mb-0">{{ __('page.push') }}</p>
                                                    </th>
                                                    <th class="text-center">
                                                        <p class="mb-0">{{ __('page.sms') }}</p>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="ps-1" colspan="4">
                                                        <div class="my-auto">
                                                            <span class="text-dark d-block text-sm">{{ __('page.mentions') }}</span>
                                                            <span class="text-xs font-weight-normal">{{ __('page.notify-when-another-user-mentions-you-in-a-comment') }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                            <input class="form-check-input" checked type="checkbox" id="flexSwitchCheckDefault11">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault12">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault13">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="ps-1" colspan="4">
                                                        <div class="my-auto">
                                                            <span class="text-dark d-block text-sm">{{ __('page.comments') }}</span>
                                                            <span class="text-xs font-weight-normal">{{ __('page.notify-when-another-user-comments-your-item') }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                            <input class="form-check-input" checked type="checkbox" id="flexSwitchCheckDefault14">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                            <input class="form-check-input" checked type="checkbox" id="flexSwitchCheckDefault15">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault16">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="ps-1" colspan="4">
                                                        <div class="my-auto">
                                                            <span class="text-dark d-block text-sm">{{ __('page.follows') }}</span>
                                                            <span class="text-xs font-weight-normal">{{ __('page.notify-when-another-user-follows-you') }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault17">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                            <input class="form-check-input" checked type="checkbox" id="flexSwitchCheckDefault18">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault19">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="ps-1" colspan="4">
                                                        <div class="my-auto">
                                                            <p class="text-sm mb-0">
                                                                {{ __('page.log-in-from-a-new-device') }}
                                                            </p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                            <input class="form-check-input" checked type="checkbox" id="flexSwitchCheckDefault20">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                            <input class="form-check-input" checked type="checkbox" id="flexSwitchCheckDefault21">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                            <input class="form-check-input" checked type="checkbox" id="flexSwitchCheckDefault22">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="sessions" class="tab-pane fade">
                            <!-- Card Sessions -->
                            <div class="card" id="sessions-card" style="min-height: 323px;">
                                <div class="card-header pb-3">
                                    <h5>{{ __('page.sessions') }}</h5>
                                    <p class="text-sm">
                                        {{ __('page.this-is-a-list-of-devices-that-have-logged-into-your-account-remove-those-that-you-do-not-recognize') }}
                                    </p>
                                </div>

                                <div class="card-body pt-0">
                                    <div class=" bg-gradient-faded-white-vertical loader-container text-center" id="loader-card" style="display: none;">
                                        <div>
                                            <div class="spinner-border text-default" role="status">
                                                <span class="sr-only"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="login-session-card">
                                        <div class="d-flex align-items-center">
                                            <div class="text-center w-5">
                                                <i class="fas fa-desktop text-lg opacity-6" id="device_1_icon"></i>
                                            </div>
                                            <div class="my-auto ms-3">
                                                <div class="h-100">
                                                    <p class="text-sm mb-1" id="device_1_name">
                                                        {{ __('page.bucharest') }} 68.133.163.201
                                                    </p>
                                                    <p class="mb-0 text-xs" id="device_1_browser">
                                                        {{ __('page.your-current-session') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <span class="badge badge-success badge-sm my-auto ms-auto me-3" id="active_1">{{ __('page.active') }}</span>
                                            <p class="text-secondary text-sm my-auto me-3" id="country_1">
                                                {{ __('page.eu') }}
                                            </p>
                                            <!-- <a href="javascript:;" class="text-primary text-sm icon-move-right my-auto">See more
                                                                                                                    <i class="fas fa-arrow-right text-xs ms-1" aria-hidden="true"></i>
                                                                                                                </a> -->
                                        </div>
                                        <hr class="horizontal dark">
                                        <div class="d-flex align-items-center">
                                            <div class="text-center w-5">
                                                <i class="fas fa-desktop text-lg opacity-6" id="device_2_icon"></i>
                                            </div>
                                            <div class="my-auto ms-3">
                                                <div class="h-100">
                                                    <p class="text-sm mb-1" id="device_2_name">
                                                        {{ __('page.bucharest') }} 68.133.163.201
                                                    </p>
                                                    <p class="mb-0 text-xs" id="device_2_browser">
                                                        {{ __('page.your-current-session') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <span class="badge badge-success badge-sm my-auto ms-auto me-3" id="active_2"></span>
                                            <p class="text-secondary text-sm my-auto me-3" id="country_2">
                                                US</p>
                                            <!-- <a href="javascript:;" class="text-primary text-sm icon-move-right my-auto">See more
                                                                                                                    <i class="fas fa-arrow-right text-xs ms-1" aria-hidden="true"></i>
                                                                                                                </a> -->
                                        </div>
                                        <hr class="horizontal dark">
                                        <div class="d-flex align-items-center">
                                            <div class="text-center w-5">
                                                <i class="fas fa-mobile text-lg opacity-6" id="device_3_icon"></i>
                                            </div>
                                            <div class="my-auto ms-3">
                                                <div class="h-100">
                                                    <p class="text-sm mb-1" id="device_3_name">
                                                        {{ __('page.bucharest') }} 68.133.163.201
                                                    </p>
                                                    <p class="mb-0 text-xs" id="device_3_browser">
                                                        {{ __('page.your-current-session') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <span class="badge badge-success badge-sm my-auto ms-auto me-3" id="active_3"></span>
                                            <p class="text-secondary text-sm my-auto me-3" id="country_3">
                                                {{ __('page.rakib') }}US
                                            </p>
                                            <!-- <a href="javascript:;" class="text-primary text-sm icon-move-right my-auto">See more
                                                                                                                    <i class="fas fa-arrow-right text-xs ms-1" aria-hidden="true"></i>
                                                                                                                </a> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="delete-account" class="tab-pane fade">
                            <!-- Card Delete Account -->
                            <div class="card" id="delete-account-card">
                                <div class="card-header">
                                    <h5>{{ __('page.delete-account') }}</h5>
                                    <p class="text-sm mb-0">
                                        {{ __('page.once-you-delete-your-account-there-is-no-going-back-please-be-certain') }}
                                    </p>
                                </div>
                                <div class="card-body d-sm-flex pt-0">
                                    <div class="d-flex align-items-center mb-sm-0 mb-4">
                                        <div>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault0">
                                            </div>
                                        </div>
                                        <div class="ms-2">
                                            <span class="text-dark font-weight-bold d-block text-sm">{{ __('page.confirm') }}</span>
                                            <span class="text-xs d-block">{{ __('page.i-want-to-delete-my-account') }}</span>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-secondary mb-0 ms-auto" type="button" name="button">{{ __('page.deactivate') }}</button>
                                    <button class="btn bg-gradient-danger mb-0 ms-2" type="button" name="button">{{ __('page.delete-account') }}</button>
                                </div>
                            </div>
                        </div>
                        <div id="otpVerification" class="tab-pane fade">
                            <div class="content-body">
                                <div class="security_tabs position-relative">
                                    <div class=" bg-gradient-faded-white-vertical loader-container text-center" id="loader-card-otp" style="display: none;">
                                        <div>
                                            <div class="spinner-border text-default" role="status">
                                                <span class="sr-only"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card p-2" style="min-height:280px">
                                        <div class="row gx-3 align-items-center ml-5">
                                            <div class="card-header">
                                                <h5>OTP Verification</h5>
                                            </div>
                                            <div class="col-md-7" style="border-right:1px solid var(--custom-primary); padding-left:30px">
                                                @if ($adminOtpSetting->deposit == true)
                                                <div class="card-body p-0">
                                                    <div class="title-wrapper d-flex">
                                                        <div class="d-flex flex-column float-start">
                                                            <div class="form-check form-switch form-check-primary">
                                                                <input type="checkbox" class="form-check-input" id="otp_deposit" name="otp_deposit" <?= isset($OtpSetting->deposit) ? ($OtpSetting->deposit == 1 ? 'checked' : '') : '' ?> />
                                                                <label class="form-check-label" for="otp_deposit">
                                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <p class="todo-title">{{ __('page.otp_deposit') }}</p>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($adminOtpSetting->withdraw == true)
                                                <div class="card-body p-0">
                                                    <div class="title-wrapper d-flex">
                                                        <div class="d-flex flex-column float-start">
                                                            <div class="form-check form-switch form-check-primary">
                                                                <input type="checkbox" class="form-check-input" id="otp_withdraw" name="otp_withdraw" {{ isset($OtpSetting->withdraw) ? ($OtpSetting->withdraw == 1 ? 'checked' : '') : '' }} />
                                                                <label class="form-check-label" for="otp_withdraw">
                                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <p class="todo-title">{{ __('page.otp_withdraw') }}</p>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($adminOtpSetting->transfer == true)
                                                <div class="card-body p-0">
                                                    <div class="title-wrapper d-flex">
                                                        <div class="d-flex flex-column float-start">
                                                            <div class="form-check form-switch form-check-primary">
                                                                <input type="checkbox" class="form-check-input" id="otp_transfer" name="otp_transfer" {{ isset($OtpSetting->transfer) ? ($OtpSetting->transfer == 1 ? 'checked' : '') : '' }} />
                                                                <label class="form-check-label" for="otp_transfer">
                                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <p class="todo-title">{{ __('page.otp_transfer') }}</p>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($adminOtpSetting->account_create == true)
                                                <div class="card-body p-0 ">
                                                    <div class="title-wrapper d-flex">
                                                        <div class="d-flex flex-column float-start">
                                                            <div class="form-check form-switch form-check-primary">
                                                                <input type="checkbox" class="form-check-input" id="otp_live_account" name="otp_live_account" {{ isset($OtpSetting->account_create) ? ($OtpSetting->account_create == 1 ? 'checked' : '') : '' }} />
                                                                <label class="form-check-label" for="otp_live_account">
                                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <p class="todo-title">{{ __('page.otp_live_account') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="col-md-5 " style="padding:0 30px;">
                                                <div class="card p-0 m-0" style="border:1px solid var(--custom-primary);padding:17px 7px 0 7px !important">
                                                    <div class="card-body p-0">
                                                        <div class="title-wrapper d-flex">
                                                            <div class="d-flex flex-column float-start">
                                                                <div class="form-check form-switch form-check-primary">
                                                                    <input type="checkbox" class="form-check-input" id="otp_all" name="otp_all" {{ $OtpSetting ? (($OtpSetting->deposit == 1 and $OtpSetting->account_create == 1 and $OtpSetting->transfer == 1 and $OtpSetting->withdraw == 1) ? 'checked' : '') : '' }} />
                                                                    <label class="form-check-label" for="otp_all">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <p class="todo-title">{{ __('page.otp_all') }}.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="tab-social" class="tab-pane fade">
        <div class="container-fluid py-4 my-3">
            <div class="row">
                <div class="col-8 text-center mx-auto">
                    <div class="mb-3">
                        <h4>{{ __('page.your-social-links') }}</h4>
                        <p>{{ __('page.add-update-your-social-link-its-optional-for-your-identity') }}</p>
                    </div>
                    <div class="card">
                        <div class="card-body p-4">
                            <form action="{{ route('ib.ib-admin-settings.add-update') }}" method="post" id="social-info-form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mx-auto mt-3">
                                        <div class="form-group">
                                            <div class="input-group mb-4">
                                                <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                                <input class="form-control social-placeholder" id="fb_link" name="fb_link" value="{{ isset($social_link->facebook) ? $social_link->facebook : '' }}" placeholder="https://" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mx-auto mt-3">
                                        <div class="form-group">
                                            <div class="input-group mb-4">
                                                <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                                <input class="form-control social-placeholder" id="twitter_link" name="twitter_link" value="{{ isset($social_link->twitter) ? $social_link->twitter : '' }}" placeholder="https://" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mx-auto mt-3">
                                        <div class="form-group">
                                            <div class="input-group mb-4">
                                                <span class="input-group-text"><i class="fab fa-skype"></i></span>
                                                <input class="form-control social-placeholder" id="skype_link" name="skype_link" value="<?= isset($social_link->skype) ? $social_link->skype : '' ?>" placeholder="https://" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mx-auto mt-3">
                                        <div class="form-group">
                                            <div class="input-group mb-4">
                                                <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                                                <input class="form-control social-placeholder" id="linkedin_link" name="linkedin_link" value="<?= isset($social_link->linkedin) ? $social_link->linkedin : '' ?>" placeholder="https://" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mx-auto mt-3">

                                    </div>
                                    <div class="col-md-6 mx-auto mt-3">
                                        <div class="form-group">
                                            <div class="input-group mb-4">
                                                <span class="input-group-text"><i class="fab fa-telegram"></i></span>
                                                <input class="form-control social-placeholder" id="telegram_link" name="telegram_link" value="<?= isset($social_link->telegram) ? $social_link->telegram : '' ?>" placeholder="https://" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" data-label="Submit Request" id="btn-submit-request" data-btnid="btn-submit-request" data-callback="social_info_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="social-info-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto mb-0 w-lg-25 mt-4 float-end">{{ __('page.submit-request') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="tab-notification" class="tab-pane fade">
        <div class="container-fluid my-3 py-3">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h3 class="mt-5">{{ __('page.build-your-profile') }}</h3>
                    <h5 class="text-secondary font-weight-normal">
                        {{ __('page.this-information-will-let-us-know-more-about-you') }}
                    </h5>
                    <div class="multisteps-form mb-5">
                        <!--progress bar-->
                        <div class="row">
                            <div class="col-12 col-lg-8 mx-auto my-5">
                                <div class="multisteps-form__progress">
                                    <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">
                                        <span>{{ __('page.about') }}</span>
                                    </button>
                                    <button class="multisteps-form__progress-btn" type="button" title="Address">
                                        <span>{{ __('page.account') }}</span>
                                    </button>
                                    <button class="multisteps-form__progress-btn" type="button" title="Order Info">
                                        <span>{{ __('page.address') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!--form panels-->
                        <div class="row">
                            <div class="col-12 col-lg-8 m-auto">
                                <form class="multisteps-form__form">
                                    <!--single form panel-->
                                    <div class="card multisteps-form__panel position-relative p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                        <div class="row text-center">
                                            <div class="col-10 mx-auto">
                                                <h5 class="font-weight-normal">
                                                    {{ __('page.lets-start-with-the-basic-information') }}
                                                </h5>
                                                <p>{{ __('page.let-us-know-your-name-and-email-address-use-an-address-you-don\'t-mind-other-users-contacting-you-at') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="multisteps-form__content">
                                            <div class="row mt-3">
                                                <div class="col-12 col-sm-4">
                                                    <div class="avatar avatar-xxl position-relative">
                                                        <img src="../../assets/img/team-2.jpg" class="border-radius-md" alt="team-2">
                                                        <a href="javascript:;" class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2">
                                                            <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" aria-hidden="true" data-bs-original-title="Edit Image" aria-label="Edit Image"></i><span class="sr-only">{{ __('page.edit-image') }}</span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-8 mt-4 mt-sm-0 text-start">
                                                    <label>{{ __('page.first-name') }}</label>
                                                    <input class="multisteps-form__input form-control mb-3" type="text" placeholder="Eg. Michael" />
                                                    <label>{{ __('page.last-name') }}</label>
                                                    <input class="multisteps-form__input form-control mb-3" type="text" placeholder="Eg. Tomson" />
                                                    <label>{{ __('page.email-address') }}</label>
                                                    <input class="multisteps-form__input form-control" type="email" placeholder="Eg. soft@dashboard.com" />
                                                </div>
                                            </div>
                                            <div class="button-row d-flex mt-4">
                                                <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next" type="button" title="Next">{{ __('page.next') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!--single form panel-->
                                    <div class="card multisteps-form__panel position-relative p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                        <div class="row text-center">
                                            <div class="col-10 mx-auto">
                                                <h5 class="font-weight-normal">{{ __('page.what-are-you-doing?') }}
                                                </h5>
                                                <p>{{ __('page.give-us-more-details-about-you-what-do-you-enjoy-doing-in-your-spare-time?') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="multisteps-form__content">
                                            <div class="row mt-4">
                                                <div class="col-sm-3 ms-auto">
                                                    <input type="checkbox" class="btn-check" id="btncheck1">
                                                    <label class="btn btn-lg btn-outline-secondary border-2 px-6 py-5" for="btncheck1">
                                                        <svg class="text-dark" width="20px" height="20px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                            <title>{{ __('page.settings') }}</title>
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                                    <g transform="translate(1716.000000, 291.000000)">
                                                                        <g transform="translate(304.000000, 151.000000)">
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
                                                    </label>
                                                    <h6>{{ __('page.design') }}</h6>
                                                </div>
                                                <div class="col-sm-3">
                                                    <input type="checkbox" class="btn-check" id="btncheck2">
                                                    <label class="btn btn-lg btn-outline-secondary border-2 px-6 py-5" for="btncheck2">
                                                        <svg class="text-dark" width="20px" height="20px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                            <title>{{ __('page.box-3d-50') }}</title>
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                                    <g transform="translate(1716.000000, 291.000000)">
                                                                        <g transform="translate(603.000000, 0.000000)">
                                                                            <path class="color-background" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z">
                                                                            </path>
                                                                            <path class="color-background" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z" opacity="0.7"></path>
                                                                            <path class="color-background" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z" opacity="0.7"></path>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </label>
                                                    <h6>Code</h6>
                                                </div>
                                                <div class="col-sm-3 me-auto">
                                                    <input type="checkbox" class="btn-check" id="btncheck3">
                                                    <label class="btn btn-lg btn-outline-secondary border-2 px-6 py-5" for="btncheck3">
                                                        <svg class="text-dark" width="20px" height="20px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                            <title>{{ __('page.spaceship') }}</title>
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <g transform="translate(-1720.000000, -592.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                                    <g transform="translate(1716.000000, 291.000000)">
                                                                        <g transform="translate(4.000000, 301.000000)">
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
                                                    </label>
                                                    <h6>{{ __('page.develop') }}</h6>
                                                </div>
                                            </div>
                                            <div class="button-row d-flex mt-4">
                                                <button class="btn bg-gradient-light mb-0 js-btn-prev" type="button" title="Prev">{{ __('page.prev') }}</button>
                                                <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next" type="button" title="Next">{{ __('page.next') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!--single form panel-->
                                    <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                        <div class="row text-center">
                                            <div class="col-10 mx-auto">
                                                <h5 class="font-weight-normal">
                                                    {{ __('page.are-you-living-in-a-nice-area?') }}
                                                </h5>
                                                <p>{{ __('page.one-thing-i-love-about-the-later-sunsets-is-the-chance-to-go-for-a-walk-through-the-neighborhood-woods-before-dinner') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="multisteps-form__content">
                                            <div class="row text-start">
                                                <div class="col-12 col-md-8 ms-auto mt-3">
                                                    <label>{{ __('page.street-name') }}</label>
                                                    <input class="multisteps-form__input form-control" type="text" placeholder="Eg. Soft" />
                                                </div>
                                                <div class="col-12 col-md-4 ms-auto mt-3">
                                                    <label>{{ __('page.street-no') }}</label>
                                                    <input class="multisteps-form__input form-control" type="number" placeholder="Eg. 221" />
                                                </div>
                                                <div class="col-12 col-md-7 ms-auto mt-3">
                                                    <label>{{ __('page.city') }}</label>
                                                    <input class="multisteps-form__input form-control" type="text" placeholder="Eg. Tokyo" />
                                                </div>
                                                <div class="col-12 col-md-5 ms-auto mt-3 text-start">
                                                    <label>{{ __('page.country') }}</label>
                                                    <select class="form-control" name="choices-country" id="choices-country">
                                                        <option value="Argentina">Argentina
                                                        </option>
                                                        <option value="Albania">Albania
                                                        </option>
                                                        <option value="Algeria">Algeria
                                                        </option>
                                                        <option value="Andorra">Andorra
                                                        </option>
                                                        <option value="Angola">Angola</option>
                                                        <option value="Brasil">Brasil</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="button-row d-flex mt-4 col-12">
                                                    <button class="btn bg-gradient-light mb-0 js-btn-prev" type="button" title="Prev">{{ __('page.prev') }}</button>
                                                    <button class="btn bg-gradient-dark ms-auto mb-0" type="button" title="Send">{{ __('page.send') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="tab-backup" class="tab-pane fade">
        {{ __('page.tab-backup') }}
    </div>
</div>
<!-- include footer -->
@include('layouts.footer')

<!-- all modal -->
<!-- Modal -->
<div class="modal fade" id="modal-change-password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('page.change-password') }}</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="old-password">{{ __('page.old-password') }}</label>
                    <div class="input-group mb-4">
                        <input class="form-control" placeholder="Old Password" type="password">
                        <span class="input-group-text position-relative" style="padding:13px">
                            <i class="ni ni-zoom-split-in"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="old-password">{{ __('page.new-password') }}</label>
                    <div class="input-group mb-4">
                        <input class="form-control" placeholder="Old Password" type="password">
                        <span class="input-group-text position-relative" style="padding:13px">
                            <i class="ni ni-zoom-split-in"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="old-password">{{ __('page.confirm-new-password') }}</label>
                    <div class="input-group mb-4">
                        <input class="form-control" placeholder="Old Password" type="password">
                        <span class="input-group-text position-relative" style="padding:13px">
                            <i class="ni ni-zoom-split-in"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">{{ __('page.close') }}</button>
                <button type="button" class="btn bg-gradient-primary">{{ __('page.save-change') }}</button>
            </div>
        </form>
    </div>
</div>
@stop
@section('corejs')
<script src="{{ asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js') }}"></script>
@stop
@section('page-js')
<!-- Start: date picker -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
<!-- End: date picker -->

<script type="text/javascript" src="{{ asset('trader-assets/assets/js/datatables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js') }}"></script>
<script src="{{ asset('/common-js/copy-js.js') }}"></script>
<script src="{{ asset('/common-js/password-gen.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>
<script>
    if (document.getElementById('choices-gender')) {
        var gender = document.getElementById('choices-gender');
        const example = new Choices(gender);
    }

    if (document.getElementById('country')) {
        var language = document.getElementById('country');
        const example = new Choices(language);
    }

    if (document.getElementById('choices-skills')) {
        var skills = document.getElementById('choices-skills');
        const example = new Choices(skills, {
            delimiter: ',',
            editItems: true,
            maxItemCount: 5,
            removeItemButton: true,
            addItems: true
        });
    }

    if (document.getElementById('choices-year')) {
        var year = document.getElementById('choices-year');
        setTimeout(function() {
            const example = new Choices(year);
        }, 1);

        for (y = 1900; y <= 2020; y++) {
            var optn = document.createElement("OPTION");
            optn.text = y;
            optn.value = y;

            if (y == 2020) {
                optn.selected = true;
            }

            year.options.add(optn);
        }
    }

    if (document.getElementById('choices-day')) {
        var day = document.getElementById('choices-day');
        setTimeout(function() {
            const example = new Choices(day);
        }, 1);


        for (y = 1; y <= 31; y++) {
            var optn = document.createElement("OPTION");
            optn.text = y;
            optn.value = y;

            if (y == 1) {
                optn.selected = true;
            }

            day.options.add(optn);
        }

    }

    if (document.getElementById('choices-month')) {
        var month = document.getElementById('choices-month');
        setTimeout(function() {
            const example = new Choices(month);
        }, 1);

        var d = new Date();
        var monthArray = new Array();
        monthArray[0] = "January";
        monthArray[1] = "February";
        monthArray[2] = "March";
        monthArray[3] = "April";
        monthArray[4] = "May";
        monthArray[5] = "June";
        monthArray[6] = "July";
        monthArray[7] = "August";
        monthArray[8] = "September";
        monthArray[9] = "October";
        monthArray[10] = "November";
        monthArray[11] = "December";
        for (m = 0; m <= 11; m++) {
            var optn = document.createElement("OPTION");
            optn.text = monthArray[m];
            // server side month start from one
            optn.value = (m + 1);
            // if june selected
            if (m == 1) {
                optn.selected = true;
            }
            month.options.add(optn);
        }
    }

    function visible() {
        var elem = document.getElementById('profileVisibility');
        if (elem) {
            if (elem.innerHTML == "Switch to visible") {
                elem.innerHTML = "Switch to invisible"
            } else {
                elem.innerHTML = "Switch to visible"
            }
        }
    }

    var openFile = function(event) {
        var input = event.target;

        // Instantiate FileReader
        var reader = new FileReader();
        reader.onload = function() {
            imageFile = reader.result;

            document.getElementById("imageChange").innerHTML = '<img width="200" src="' + imageFile +
                '" class="rounded-circle w-100 shadow" />';
        };
        reader.readAsDataURL(input.files[0]);
    };
</script>
<script>
    //ib updated callback function
    function ib_info_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'IB Details');
            $('#full-name').val(data.full_name);
            if (gender == 'Female') {
                $('#choices-gender option[value="Famale"]').val(gender);
                $('#choices-gender').prop('selectedIndex', 0).trigger("change");
            } else if (gender == 'Male') {
                $('#choices-gender option[value="Male"]').val(gender);
                $('#choices-gender').prop('selectedIndex', 1).trigger("change");
            }
            $('#date_of_birth').val(data.date_of_birth);

            $('#phone').val(data.phone);
            $('#state').val(data.state);
            $('#city').val(data.city);
            $('#zipcode').val(data.zipcode);
            $('#address').val(data.address);
            // $("#basic-info-form").trigger('reset');
        }
        if (data.status == false) {
            notify('error', data.message, 'IB Details');
        }
        $.validator("basic-info-form", data.errors);
    }
    //password update callback function
    function password_update_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Update Password');
            // $("#password-update-form").trigger('reset');
        }
        if (data.status == false) {
            notify('error', data.message, 'Update Password');
        }
        $.validator("password-update-form", data.errors);
    }
    //social info add or updated function
    function social_info_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Add Or Update');
            $('#fb_link').val(data.fb_link);
            $('#twitter_link').val(data.twitter_link);
            $('#skype_link').val(data.skype_link);
            $('#linkedin_link').val(data.linkedin_link);
            $('#telegram_link').val(data.telegram_link);
        }
        if (data.status == false) {
            notify('error', data.message, 'Add Or Update');
        }
        $.validator("social-info-form", data.errors);
    }
    // two factore authentication
    $(document).ready(function() {
        function is_checked() {
            if ($("#googleAuthCheck").is(':checked')) {
                $(".card-google-auth").slideDown();
            } else {
                $(".card-google-auth").slideUp();
            }
        }
        is_checked();
        $(document).on("change", ".switch-2fa", function() {
            is_checked();

        })
    });
    var trade_report = dt_fetch_data(
        '/ib/ib-admin/account-settings/accounts-dt', //request url
        [{
                "data": "account"
            }, //collumns
            {
                "data": "server"
            },
            {
                "data": "leverage"
            },
            {
                "data": "balance"
            },
            {
                "data": "action"
            }
        ],
        false, //filter
        false, //feather icon
        false //exports
        ,
        "", //exports collumns,
        "", //footer sum collumn
        false, // change length
        true //language
    )
    // change pasword--------------------------
    $(document).on('click', ".btn-change-password", function() {
        $("#modal-change-password").modal('show');
    });
    // session-----------------------------
    $(document).on("click", ".btn-session-block", function() {
        $("#loader-card").fadeIn();
        $('#login-session-card').fadeOut();
        $.ajax({
            url: "/ib/ib-admin/sessions/login-device",
            method: 'get',
            dataType: 'json',
            success: function(data) {
                $("#login-session-card").html(data);
                $("#loader-card").fadeOut();
                $('#login-session-card').fadeIn();
            }
        });
    });
</script>

<!-- 2FA authentication script -->
<script>
    // secret key copy script start
    $(document).on('click', '#copy_secret_key', function() {
        var clipboardText = "";
        clipboardText = $('#secret_key').val();
        copyToClipboard(clipboardText);
        notify('success', "Copied To Clipboard", 'Secret Key');

    });

    function copyToClipboard(text) {
        var sampleTextarea = document.createElement("textarea");
        document.body.appendChild(sampleTextarea);
        sampleTextarea.value = text; //save main text in it
        sampleTextarea.select(); //select textarea contenrs
        document.execCommand("copy");
        document.body.removeChild(sampleTextarea);
    }
    // secret key copy script end

    if ($('#googleAuthCheck[type="checkbox"]')) {
        if ($('#googleAuthCheck').prop("checked") == true) {
            $('#google_auth_modal').show();
        } else if ($('#googleAuthCheck').prop("checked") == false) {

        }
    }
    // check or uncheck property
    // no auth
    $('#noAuthCheck[type="checkbox"]').click(function() {
        if ($(this).is(":checked")) {
            $('#noAuthCheck').prop('checked', true);
            $('#mailAuthCheck').prop('checked', false);
            $('#googleAuthCheck').prop('checked', false);
        } else if ($(this).is(":not(:checked)")) {
            $('#noAuthCheck').prop('checked', false);
            $('#mailAuthCheck').prop('checked', false);
            $('#googleAuthCheck').prop('checked', false);
        }
    });
    // mail auth
    $('#mailAuthCheck[type="checkbox"]').click(function() {
        if ($(this).is(":checked")) {
            $('#noAuthCheck').prop('checked', false);
            $('#mailAuthCheck').prop('checked', true);
            $('#googleAuthCheck').prop('checked', false);
        } else if ($(this).is(":not(:checked)")) {
            $('#noAuthCheck').prop('checked', false);
            $('#mailAuthCheck').prop('checked', true);
            $('#googleAuthCheck').prop('checked', false);
        }
    });
    // google auth
    $('#googleAuthCheck[type="checkbox"]').click(function() {
        if ($(this).is(":checked")) {
            $('#noAuthCheck').prop('checked', false);
            $('#mailAuthCheck').prop('checked', false);
            $('#googleAuthCheck').prop('checked', true);
            $('#google_auth_modal').show();
        } else if ($(this).is(":not(:checked)")) {
            $('#noAuthCheck').prop('checked', false);
            $('#mailAuthCheck').prop('checked', false);
            $('#googleAuthCheck').prop('checked', false);
        }
    });

    // no auth
    $(document).on('change', '#noAuthCheck', function(event) {
        let check_auth = $('#noAuthCheck').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/ib/ib-admin/settings/security-setting/' + check_auth,
            method: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,

            success: function(data) {
                if (data.success) {
                    notify('success', data.message, 'Authentication');
                    $('#noAuthCheck').prop('checked', true);
                    $('#mailAuthCheck').prop('checked', false);
                    $('#googleAuthCheck').prop('checked', false);
                } else {
                    notify('error', data.message, 'Authentication');
                }
            }
        });
    });
    // mail auth
    $(document).on('click', '#mailAuthCheck', function(event) {

        let check_auth = $('#mailAuthCheck').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/ib/ib-admin/settings/security-setting/' + check_auth,
            method: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,

            success: function(data) {
                if (data.success) {
                    notify('success', data.message, 'Authentication');
                    $('#noAuthCheck').prop('checked', false);
                    $('#mailAuthCheck').prop('checked', true);
                    $('#googleAuthCheck').prop('checked', false);
                } else {
                    notify('error', data.message, 'Authentication');
                }
            }
        });
    });

    // google auth setup callback
    function googleAuthSetupCallBack(data) {
        $('#googleAuthSetupBtn').prop('disabled', false);
        if (data.success) {
            notify('success', data.message, 'Google Authentication');
            $('#noAuthCheck').prop('checked', false);
            $('#mailAuthCheck').prop('checked', false);
            $('#googleAuthCheck').prop('checked', true);
        } else {
            notify('error', data.message, 'Google Authentication');
        }
    }

    //transaction password update callback function
    function transaction_password_update_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Update Password');
            $("#transaction-password-update-form").trigger('reset');
        }
        if (data.status == false) {
            notify('error', data.message, 'Update Password');
        }
        $.validator("transaction-password-update-form", data.errors);
    }


    // genrate randome password
    $(document).on('click', ".btn-gen-password", function() {
        var field = $(this).closest('div.password_gen').find('input[rel="gp"]');
        field.val(rand_string(field));
        field.attr('type', 'text');
        $(this).closest('div.password_gen').find('.copy_btn').show();
    });
    $('.copy_btn').on("click", function(e) {
        e.preventDefault();
        $(this).html('copied');
        setTimeout(() => {
            $(this).hide();
            $(this).html('Copy');
        }, 1000);
        let id = $(this).closest('div.password_gen').find('.copy-pass-input').attr('id');
        $(this).closest('div.password_gen').find('.copy-pass-input').select();
        if ($(this).closest('div.password_gen').find('.copy-pass-input').val() != "") {
            copy_to_clipboard(id);
        }
        $(this).closest('div.password_gen').find('input[rel="gp"]').attr('type', 'password');
    });
    // find transaction password callback
    function findTPemailCallBack(data) {
        if (data.status == true) {
            // $('#email_verification_section').toggle(2000);
            // $('#otp_verification_section').toggle(2000);
            $('#submit_email_form').val("");
            $('#submit_otp_form').val("ftp_vcode");
            $('.ftp_email').val(data.ftp_email);
            notify('success', data.message, "Reset Transaction Password");
            $("#create_password_btn").prop('disabled', true);
        } else {
            notify('error', data.message, "Reset Transaction Password");
        }
    }


    // OTP Verification 
    $('#otp_all[type="checkbox"]').click(function() {
        if ($(this).is(":checked")) {
            $('#otp_live_account').prop('checked', true);
            $('#otp_transfer').prop('checked', true);
            $('#otp_withdraw').prop('checked', true);
            $('#otp_deposit').prop('checked', true);
        } else if ($(this).is(":not(:checked)")) {
            $('#otp_live_account').prop('checked', false);
            $('#otp_transfer').prop('checked', false);
            $('#otp_withdraw').prop('checked', false);
            $('#otp_deposit').prop('checked', false);
        }
    });

    // otp deposit ajax 
    $(document).on('change', '#otp_all', function(event) {
        $("#loader-card-otp").fadeIn();
        let check_value = ($('#otp_all').prop("checked") == true) ? 1 : 0;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/ib/otp_verification_submit/otp_all/' + check_value,
            method: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,

            success: function(data) {
                if (data.status) {
                    notify('success', data.message, 'OTP Activated');
                } else {
                    notify('error', data.message, 'OTP Not Active');
                }
                $("#loader-card-otp").fadeOut();
            }
        });
    });

    // otp deposit ajax 
    $(document).on('change', '#otp_deposit', function(event) {
        $("#loader-card-otp").fadeIn();
        let check_value = ($('#otp_deposit').prop("checked") == true) ? 1 : 0;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/ib/otp_verification_submit/otp_deposit/' + check_value,
            method: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,

            success: function(data) {
                if (data.status) {
                    notify('success', data.message, 'OTP Activated');
                } else {
                    notify('error', data.message, 'OTP Not Active');
                }
                $("#loader-card-otp").fadeOut();
            }
        });
    });
    // otp withdraw ajax 
    $(document).on('change', '#otp_withdraw', function(event) {
        $("#loader-card-otp").fadeIn();
        let check_value = ($('#otp_withdraw').prop("checked") == true) ? 1 : 0;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/ib/otp_verification_submit/otp_withdraw/' + check_value,
            method: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,

            success: function(data) {
                if (data.status) {
                    notify('success', data.message, 'OTP Activated');
                } else {
                    notify('error', data.message, 'OTP Not Active');
                }
                $("#loader-card-otp").fadeOut();
            }
        });
    });

    // otp_transfer ajax 
    $(document).on('change', '#otp_transfer', function(event) {
        $("#loader-card-otp").fadeIn();
        let check_value = ($('#otp_transfer').prop("checked") == true) ? 1 : 0;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/ib/otp_verification_submit/otp_transfer/' + check_value,
            method: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,

            success: function(data) {
                if (data.status) {
                    notify('success', data.message, 'OTP Activated');
                } else {
                    notify('error', data.message, 'OTP Not Active');
                }
                $("#loader-card-otp").fadeOut();
            }
        });
    });

    // Live Account ajax 
    $(document).on('change', '#otp_live_account', function(event) {
        $("#loader-card-otp").fadeIn();
        let check_value = ($('#otp_live_account').prop("checked") == true) ? 1 : 0;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/ib/otp_verification_submit/otp_live_account/' + check_value,
            method: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,

            success: function(data) {
                if (data.status) {
                    notify('success', data.message, 'OTP Activated');
                } else {
                    notify('error', data.message, 'OTP Not Active');
                }
                $("#loader-card-otp").fadeOut();
            }

        });
    });
    // password reset callback
    function reset_password_callback(data) {
        if (data.status) {
            notify('success', data.message, 'Password Reset');
        } else {
            notify('error', data.message, 'Password Reset');
        }
    }
</script>
@stop