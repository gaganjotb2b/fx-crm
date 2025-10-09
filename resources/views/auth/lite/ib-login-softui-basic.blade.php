<?php

use Illuminate\Support\Facades\Cookie;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta name="description" content="{{get_company_name()}} is a broker company focuses in Forex Trading. We believe in transparency, accountability, and accuracy of services. Experience trading in the most seamless way, straight to global market, and the easiness of withdrawal.">
    <meta name="keywords" content="{{get_company_name()}} is operated by {{get_company_name()}} and has registered in Saint Vincent & the Grenadines with LLC number 892 LLC 2021, regulated by the Financial Services Authority (FSA) of Saint Vincent and the Grenadines. High Risk Warning : Before you enter foreign exchange and stock markets, you have to remember that trading currencies and other investment products is trading in nature and always involves a considerable risk. As a result of various financial fluctuations, you may not only significantly increase your capital, but also lose it completely.">
    <meta name="author" content="{{get_company_name()}}">

    <link rel="stylesheet" href="{{ asset('trader-assets/assets/css/root-color.css') }}">

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('trader-assets/assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ get_favicon_icon() }}">
    <title>
        {{ strtoupper(config('app.name')) }} - IB Login
    </title>
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('trader-assets/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('trader-assets/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ asset('trader-assets/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">

    <!-- CSS Files -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
    <link id="pagestyle" href="{{ asset('trader-assets/assets/css/soft-ui-dashboard.css?v=1.0.8') }}" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('trader-assets/assets/css/style.css') }}" rel="stylesheet" />

    @php $themeColor = get_theme_colors_forAll('user_theme') @endphp
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

        .card .card-header {
            border-left: none;
        }

        .text-border::before {
            right: 0.5em;
            margin-left: -38%;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- End Navbar -->
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('../../../assets/img/curved-images/curved9.jpg');">
            <span class="mask" style="background-color: var(--custom-primary);"></span>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 text-center mx-auto">
                        <h1 class="text-white mb-2 mt-5">Welcome!</h1>
                        <p class="text-lead text-white">
                            Login into {{config('app.name')}}, the trusted client portal traders for over few years. Trade on
                            MetaTrader – one of the most popular trading platforms in the world.
                        </p>
                        <!-- <h3 class="font-weight-bolder text-dark">Welcome Back To {{ config('app.name') }}🤩</h3> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                    <div class="card z-index-0">
                        <div class="card-header text-center pt-4">
                            <img src="{{ get_user_logo() }}" height="30" alt="{{ config('app.name') }}">
                        </div>
                        <div class="row px-xl-5 px-sm-4 px-3 d-none">
                            <div class="col-3 ms-auto px-1">
                                <a class="btn btn-outline-light w-100" href="javascript:;">
                                    <svg width="24px" height="32px" viewBox="0 0 64 64" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g transform="translate(3.000000, 3.000000)" fill-rule="nonzero">
                                                <circle fill="#3C5A9A" cx="29.5091719" cy="29.4927506" r="29.4882047"></circle>
                                                <path d="M39.0974944,9.05587273 L32.5651312,9.05587273 C28.6886088,9.05587273 24.3768224,10.6862851 24.3768224,16.3054653 C24.395747,18.2634019 24.3768224,20.1385313 24.3768224,22.2488655 L19.8922122,22.2488655 L19.8922122,29.3852113 L24.5156022,29.3852113 L24.5156022,49.9295284 L33.0113092,49.9295284 L33.0113092,29.2496356 L38.6187742,29.2496356 L39.1261316,22.2288395 L32.8649196,22.2288395 C32.8649196,22.2288395 32.8789377,19.1056932 32.8649196,18.1987181 C32.8649196,15.9781412 35.1755132,16.1053059 35.3144932,16.1053059 C36.4140178,16.1053059 38.5518876,16.1085101 39.1006986,16.1053059 L39.1006986,9.05587273 L39.0974944,9.05587273 L39.0974944,9.05587273 Z" fill="#FFFFFF"></path>
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                            </div>
                            <div class="col-3 px-1">
                                <a class="btn btn-outline-light w-100" href="javascript:;">
                                    <svg width="24px" height="32px" viewBox="0 0 64 64" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g transform="translate(7.000000, 0.564551)" fill="#000000" fill-rule="nonzero">
                                                <path d="M40.9233048,32.8428307 C41.0078713,42.0741676 48.9124247,45.146088 49,45.1851909 C48.9331634,45.4017274 47.7369821,49.5628653 44.835501,53.8610269 C42.3271952,57.5771105 39.7241148,61.2793611 35.6233362,61.356042 C31.5939073,61.431307 30.2982233,58.9340578 25.6914424,58.9340578 C21.0860585,58.9340578 19.6464932,61.27947 15.8321878,61.4314159 C11.8738936,61.5833617 8.85958554,57.4131833 6.33064852,53.7107148 C1.16284874,46.1373849 -2.78641926,32.3103122 2.51645059,22.9768066 C5.15080028,18.3417501 9.85858819,15.4066355 14.9684701,15.3313705 C18.8554146,15.2562145 22.5241194,17.9820905 24.9003639,17.9820905 C27.275104,17.9820905 31.733383,14.7039812 36.4203248,15.1854154 C38.3824403,15.2681959 43.8902255,15.9888223 47.4267616,21.2362369 C47.1417927,21.4153043 40.8549638,25.1251794 40.9233048,32.8428307 M33.3504628,10.1750144 C35.4519466,7.59650964 36.8663676,4.00699306 36.4804992,0.435448578 C33.4513624,0.558856931 29.7884601,2.48154382 27.6157341,5.05863265 C25.6685547,7.34076135 23.9632549,10.9934525 24.4233742,14.4943068 C27.7996959,14.7590956 31.2488715,12.7551531 33.3504628,10.1750144"></path>
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                            </div>
                            <div class="col-3 me-auto px-1">
                                <a class="btn btn-outline-light w-100" href="javascript:;">
                                    <svg width="24px" height="32px" viewBox="0 0 64 64" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g transform="translate(3.000000, 2.000000)" fill-rule="nonzero">
                                                <path d="M57.8123233,30.1515267 C57.8123233,27.7263183 57.6155321,25.9565533 57.1896408,24.1212666 L29.4960833,24.1212666 L29.4960833,35.0674653 L45.7515771,35.0674653 C45.4239683,37.7877475 43.6542033,41.8844383 39.7213169,44.6372555 L39.6661883,45.0037254 L48.4223791,51.7870338 L49.0290201,51.8475849 C54.6004021,46.7020943 57.8123233,39.1313952 57.8123233,30.1515267" fill="#4285F4"></path>
                                                <path d="M29.4960833,58.9921667 C37.4599129,58.9921667 44.1456164,56.3701671 49.0290201,51.8475849 L39.7213169,44.6372555 C37.2305867,46.3742596 33.887622,47.5868638 29.4960833,47.5868638 C21.6960582,47.5868638 15.0758763,42.4415991 12.7159637,35.3297782 L12.3700541,35.3591501 L3.26524241,42.4054492 L3.14617358,42.736447 C7.9965904,52.3717589 17.959737,58.9921667 29.4960833,58.9921667" fill="#34A853"></path>
                                                <path d="M12.7159637,35.3297782 C12.0932812,33.4944915 11.7329116,31.5279353 11.7329116,29.4960833 C11.7329116,27.4640054 12.0932812,25.4976752 12.6832029,23.6623884 L12.6667095,23.2715173 L3.44779955,16.1120237 L3.14617358,16.2554937 C1.14708246,20.2539019 0,24.7439491 0,29.4960833 C0,34.2482175 1.14708246,38.7380388 3.14617358,42.736447 L12.7159637,35.3297782" fill="#FBBC05"></path>
                                                <path d="M29.4960833,11.4050769 C35.0347044,11.4050769 38.7707997,13.7975244 40.9011602,15.7968415 L49.2255853,7.66898166 C44.1130815,2.91684746 37.4599129,0 29.4960833,0 C17.959737,0 7.9965904,6.62018183 3.14617358,16.2554937 L12.6832029,23.6623884 C15.0758763,16.5505675 21.6960582,11.4050769 29.4960833,11.4050769" fill="#EB4335"></path>
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('ib.login.action') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start login-form-body" id="ib-login-form">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col">
                                        <p class="mb-0">Enter your email and password to sign in</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input type="email" name="email" class="form-control email" placeholder="Email" aria-label="Email" value="{{ Cookie::get('remember_email') }}" required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" name="password" class="form-control password" placeholder="Password" aria-label="Password" value="{{ Cookie::get('remember_password') }}" required>
                                </div>
                                <input type="hidden" name="request_form" value="login_form">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me">
                                    <label class="form-check-label" for="remember_me">Remember me</label>
                                    <span class="float-end"><a class="text-danger small forgot_password" href="#"><u>Forgot Password?</u></a></span>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0 temp_disabled" id="loginBtn" onclick="_run(this)" data-el="fg" data-form="ib-login-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="ibLoginCallBack" data-btnid="loginBtn">{{__('page.sign_in')}}</button>
                                </div>
                                <div class="mb-2 position-relative text-center">
                                    <p class="text-sm font-weight-bold mb-2 text-secondary text-border d-inline z-index-2 bg-white px-3">
                                        or
                                    </p>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('ib.registration') }}" class="text-light font-weight-bold"><button type="button" class="btn bg-gradient-warning w-100 mt-2 mb-4">Sign up</button></a>
                                </div>
                            </form>
                            <!-- mail verification -->
                            <form action="{{ route('ib.login.action') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start mail-verification-form-body d-none" id="ib-login-mail-verification-form">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col">
                                        <h3 class="font-weight-bolder text-primary text-gradient">Mail Verification</h3>
                                        <p class="mb-0">Enter your email verification code to sign in</p>
                                    </div>
                                </div>
                                <div class="row gx-2 gx-sm-3 mt-5">
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code1" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code2" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code3" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code4" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code5" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code6" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <input type="hidden" name="request_form" value="mail_verify">
                                    <input type="hidden" name="email" class="form-control v_email" placeholder="Email" aria-label="Email" required>
                                    <input type="hidden" name="password" class="form-control v_password" placeholder="Password" aria-label="Password" required>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0 temp_disabled" id="mailVerificationBtn" onclick="_run(this)" data-el="fg" data-form="ib-login-mail-verification-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="ibLoginMailVerificationCallBack" data-btnid="mailVerificationBtn">{{__('page.verify')}}</button>
                                    <span class="text-muted text-sm send_verification_key">{{__('page.haven\'t-received-it?')}}<a href="javascript:;">{{__('page.resend_a new_code')}} </a>.</span>
                                </div>
                            </form>
                            <!-- google verification -->
                            <form action="{{ route('ib.login.action') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start google-verification-form-body d-none" id="ib-login-google-verification-form">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col">
                                        <h3 class="font-weight-bolder text-primary text-gradient">Google Verification</h3>
                                        <p class="mb-0">Enter your google verification code to sign in</p>
                                    </div>
                                </div>
                                <div class="row gx-2 gx-sm-3 mt-5">
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code1" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code2" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code3" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code4" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code5" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code6" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <input type="hidden" name="request_form" value="google_verify">
                                    <input type="hidden" name="email" class="form-control v_email" placeholder="Email" aria-label="Email" required>
                                    <input type="hidden" name="password" class="form-control v_password" placeholder="Password" aria-label="Password" required>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0 temp_disabled" id="googleVerificationBtn" onclick="_run(this)" data-el="fg" data-form="ib-login-google-verification-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="ibLoginGoogleVerificationCallBack" data-btnid="googleVerificationBtn">{{__('page.verify')}}</button>
                                    <span class="text-muted text-sm">{{__('page.haven\'t-received-it?')}}<a href="javascript:;"> {{__('page.resend_a new_code')}}</a>.</span>
                                </div>
                            </form>

                            <!--Start: forgot password-->
                            <form action="{{ route('user.forgot_password') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start user-forgot-password-form-body d-none" id="trader-forgot-password-form">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col">
                                        <h3 class="font-weight-bolder text-primary text-gradient">Forgot Password</h3>
                                        <p class="mb-0">Enter your email to find your account.</p>
                                    </div>
                                </div>
                                <label>Email</label>
                                <div class="mb-3">
                                    <input type="email" name="forgot_email" class="form-control forgot_email" placeholder="Find Your Account By Email" aria-label="Email" required>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0 temp_disabled" id="forgotEmailBtn" onclick="_run(this)" data-el="fg" data-form="trader-forgot-password-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="forgotEmailCallBack" data-btnid="forgotEmailBtn">Send</button>
                                </div>
                                <input type="hidden" name="submit_form" value="fp_email">
                                <input type="hidden" name="user_type" value="4">
                            </form>
                            <!-- forgot password verification start-->
                            <form action="{{ route('user.forgot_password') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start user-forgot-password-verification-form-body d-none" id="trader-forgot-password-verification-form">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col">
                                        <h3 class="font-weight-bolder text-primary text-gradient">Forgot Password</h3>
                                        <p class="mb-0">Provide the verification key from your email.</p>
                                    </div>
                                </div>
                                <div class="row gx-2 gx-sm-3 mt-5">
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code1" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code2" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code3" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code4" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code5" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="v_code6" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="off">
                                        </div>
                                    </div>
                                    <input type="hidden" name="submit_form" value="fp_vcode">
                                    <input type="hidden" name="user_type" value="4">
                                    <input type="hidden" name="fp_email" class="form-control fp_email" placeholder="Email" aria-label="Email" required>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0 temp_disabled" id="fpVerifyBtn" onclick="_run(this)" data-el="fg" data-form="trader-forgot-password-verification-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="fpVerifyBtnCallBack" data-btnid="fpVerifyBtn">Verify</button>
                                    <span class="text-muted text-sm">Haven't received it?<a href="#" class="send_verification_key"> Resend a new code</a>.</span>
                                </div>
                            </form>
                            <!-- forgot password verification end-->
                            <!-- create a new password start-->
                            <form action="{{ route('user.forgot_password') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start user-create-new-password-form-body d-none" id="trader-create-new-password-form">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col">
                                        <h3 class="font-weight-bolder text-primary text-gradient">Forgot Password</h3>
                                        <p class="mb-0">Create a new password.</p>
                                    </div>
                                </div>
                                <label>New Password</label>
                                <div class="mb-3">
                                    <input type="password" name="password" class="form-control password" placeholder="New Password" aria-label="Password" required>
                                </div>
                                <label>Repeat New Password</label>
                                <div class="mb-3">
                                    <input type="password" name="repeat_password" class="form-control password" placeholder="Repeat Password" aria-label="Password" required>
                                </div>
                                <input type="hidden" name="fp_email" class="form-control fp_email" placeholder="Email" aria-label="Email" required>
                                <input type="hidden" name="submit_form" value="create_password">
                                <input type="hidden" name="user_type" value="4">
                                <div class="text-center">
                                    <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0 temp_disabled" id="createNewPasswordBtn" onclick="_run(this)" data-el="fg" data-form="trader-create-new-password-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="createNewPasswordCallBack" data-btnid="createNewPasswordBtn">Save Change</button>
                                </div>
                            </form>
                            <!-- create a new password end-->

                            <!--End: forgot password-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <!-- include login footer -->
    @include('layouts.login-footer')
    <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <!--   Core JS Files   -->
    <script src="{{ asset('trader-assets/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/core/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>

    <script src="{{ asset('trader-assets/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <!-- Kanban scripts -->
    <script src="{{ asset('trader-assets/assets/js/plugins/dragula/dragula.min.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/plugins/jkanban/jkanban.js') }}"></script>

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
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('admin-assets/src/js/core/confirm-alert.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/common-ajax.js') }}"></script>
    <script src="{{ asset('common-js/custom-from-validation.js') }}"></script>
    <!-- enter key handler -->
    <!-- <script src="{{asset('common-js/enter-key-handler.js')}}"></script> -->
    <!-- Github buttons -->
    <!-- <script async defer src="https://buttons.github.io/buttons.js"></script> -->
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <!-- <script src="{{asset('trader-assets/assets/js/soft-ui-dashboard.min.js?v=1.0.8')}}"></script> -->

    <script>
        $(document).on('click', '.temp_disabled', function() {
            $('.temp_disabled').prop('disabled', true);
            setTimeout(function() {
                $('.temp_disabled').prop('disabled', false);
            }, 3000);
        });
        $('.mail-verification-form-body').hide();
        $('.google-verification-form-body').hide();
        $('.user-forgot-password-form-body').hide();
        $('.user-forgot-password-verification-form-body').hide();
        $('.user-create-new-password-form-body').hide();
        // trigger login when press enter
        // added by reza
        document.onkeydown = function(evt) {
            var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
            if (keyCode == 13) {
                $('#loginBtn').trigger('click');
            }
        }
        // ib login
        function ibLoginCallBack(data) {
            if (data.status == true) {
                if (data.modal == 'mail-verification-form') {
                    $('.login-form-body').slideToggle(1500);
                    $('.mail-verification-form-body').removeClass('d-none');
                    $('.mail-verification-form-body').slideToggle(3000);
                    $('.v_email').val(data.email);
                    $('.v_password').val(data.password);
                    notify('success', data.message, 'Mail Verification');
                } else if (data.modal == 'google-verification-form') {
                    $('.login-form-body').slideToggle(1500);
                    $('.google-verification-form-body').removeClass('d-none');
                    $('.google-verification-form-body').slideToggle(3000);
                    $('.v_email').val(data.email);
                    $('.v_password').val(data.password);
                } else {
                    notify('success', data.message, 'IB Login');
                    setTimeout(function() {
                        window.location.href = "/ib/dashboard";
                    }, 1000 * 2);
                }

            } else {
                if (data.message != null) {
                    notify('error', data.message, 'IB Login');
                    $.validator("ib-login-form", data.errors);
                } else {
                    notify('error', 'Something Went Wrong!', 'IB Login');
                    $.validator("ib-login-form", data.errors);
                }
            }
        }
        // email verification
        function ibLoginMailVerificationCallBack(data) {
            if (data.status == true) {
                notify('success', data.message, 'IB Login');
                setTimeout(function() {
                    window.location.href = "/ib/dashboard";
                }, 1000 * 2);
            } else {
                notify('error', data.message, 'IB Login');
                $.validator("ib-login-mail-verification-form", data.errors);
            }
        }
        // google verification
        function ibLoginGoogleVerificationCallBack(data) {
            if (data.status == true) {
                notify('success', data.message, 'IB Login');
                setTimeout(function() {
                    window.location.href = "/ib/dashboard";
                }, 1000 * 2);
            } else {
                notify('error', data.message, 'IB Login');
                $.validator("ib-login-mail-verification-form", data.errors);
            }
        }
        // resend verification key
        $(document).on('click', '.send_verification_key', function() {
            var v_email = $('.v_email').val();
            var fp_email = $('.fp_email').val();
            if (v_email != "") {
                v_email = v_email;
            } else {
                v_email = fp_email;
            }
            event.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '/resent/v_code/' + v_email,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    if (data.status == true) {
                        notify('success', data.message, 'Mail Verification');
                    } else {
                        notify('error', data.message, 'Mail Verification');
                    }
                }
            });
        });
        // otp value jQuery property 
        $(document).on("keyup", ".otp-value", function(e) {
            let $value = $(this).val();
            if ($value != "") {
                $(this).closest(".col").next(".col").find(".otp-value").focus();
            }
            if ((e.keyCode == 8)) {
                $(this).closest(".col").prev(".col").find(".otp-value").focus();
            }

        });

        // forgot password property
        $(document).on('click', '.forgot_password', function() {
            $('.login-form-body').slideToggle(1500);
            $('.user-forgot-password-form-body').removeClass('d-none');
            $('.user-forgot-password-form-body').css({
                'display': 'content'
            });
            $('.user-forgot-password-form-body').slideToggle(3000);
        });
        // forgot password callback
        function forgotEmailCallBack(data) {
            if (data.status == true) {
                $('.user-forgot-password-form-body').slideToggle(1500);
                $('.user-forgot-password-verification-form-body').removeClass('d-none');
                $('.user-forgot-password-verification-form-body').css({
                    'display': 'content'
                });
                $('.user-forgot-password-verification-form-body').slideToggle(3000);
                $('.fp_email').val(data.fp_email);
            } else {
                notify('error', data.message, "Forgot Password");
            }
        }
        // forgot password verification callback
        function fpVerifyBtnCallBack(data) {
            if (data.status == true) {
                $('.user-forgot-password-verification-form-body').slideToggle(1500);
                $('.user-create-new-password-form-body').removeClass('d-none');
                $('.user-create-new-password-form-body').css({
                    'display': 'content'
                });
                $('.user-create-new-password-form-body').slideToggle(3000);
                $('.fp_email').val(data.fp_email);
                // notify('success', data.message, 'Forgot Password');
            } else {
                notify('error', data.message, "Forgot Password");
            }
        }
        // create new password callback
        function createNewPasswordCallBack(data) {
            if (data.status == true) {
                notify('success', data.message, 'Forgot Password');
                setTimeout(function() {
                    location.reload();
                }, 5000);
            } else {
                notify('error', "Fix The Following Error!", "Forgot Password");
                $.validator("trader-create-new-password-form", data.errors);
            }
        }
    </script>
</body>

</html>