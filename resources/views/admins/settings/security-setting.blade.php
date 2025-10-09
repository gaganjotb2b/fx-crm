@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Security Setting')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
<!-- google font -->


@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/assets/css/config-form.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/admin.css') }}">
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
@php
$OtpSetting = App\Models\OtpSetting::where('admin_id',auth()->user()->id)->first();

@endphp
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{__('admin-menue-left.Security_Setting')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{__('category.Settings')}}</a></li>
                                <li class="breadcrumb-item active">{{__('admin-menue-left.Security_Setting')}}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                        <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#"><i class="me-1" data-feather="info"></i>
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="content-body">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h4> {{__('ib-management.Note')}}</h4>
                                <code class="bg">{{__('ib-management.please read carefully')}}</code>
                            </div>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="border-start-3 border-start-primary p-1 bg-light-primary mb-1">
                                {{__('ib-management.in login security when you checked email verification, you will get mail verification email for login your system when your ip address changed')}}
                            </div>
                            <div class="border-start-3 border-start-info p-1 bg-light-primary mb-1">
                                {{__('ib-management.if you checked google authenticator, for login your system you need verify code through google authenticator app')}}
                            </div>
                            <div class="border-start-3 border-start-danger p-1 bg-light-primary mb-1">
                                {{__('ib-management.in otp verfication if you checked or enable OTP verification, when you do deposit,withdraw,transfer or create live account operation OTP will be send to your mail for every transaction')}}
                            </div>
                            <div class="border-start-3 border-start-success p-1 bg-light-primary mb-1">
                                {{__('ib-management.in kyc security if you checked or enable the option, when need to upload kyc, the kyc uploader will be show in kyc form')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="content-body">
                    <div class="security_tabs">
                        <div class="card">
                            <div class="card-header border-bottom mb-0">
                                <div class="card my-0 py-0 w-100">
                                    <div class="card-body my-0 py-0">
                                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Login Security</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">OTP Verification</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">KYC Security</button>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="pills-tabContent">
                                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                                <div class="card p-0">
                                                    <div class="card-body p-0 py-1">
                                                        <div class="title-wrapper d-flex">
                                                            <div class="d-flex flex-column float-start">
                                                                <div class="form-check form-switch form-check-primary">
                                                                    <input type="checkbox" class="form-check-input" id="noAuthCheck" value="no_auth" <?= ($users->email_auth == 0 && $users->g_auth == 0) ? "checked" : "" ?> />
                                                                    <label class="form-check-label" for="noAuthCheck">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <p class="todo-title">{{__('page.normal')}} - {{__('page.ss1')}}.</p>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-0 py-1">
                                                        <div class="title-wrapper d-flex">
                                                            <div class="d-flex flex-column float-start">
                                                                <div class="form-check form-switch form-check-primary">
                                                                    <input type="checkbox" class="form-check-input" id="mailAuthCheck" value="mail_auth" <?= ($users->email_auth == 1) ? "checked" : "" ?> />
                                                                    <label class="form-check-label" for="mailAuthCheck">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <p class="todo-title">{{__('page.email')}} {{__('page.verification')}}-{{__('page.ss2')}}.</p>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-0 py-1">
                                                        <div class="title-wrapper d-flex">
                                                            <div class="d-flex flex-column float-start">
                                                                <div class="form-check form-switch form-check-primary">
                                                                    <input type="checkbox" class="form-check-input" id="googleAuthCheck" value="google_auth" <?= ($users->g_auth == 1) ? "checked" : "" ?> />
                                                                    <label class="form-check-label" for="googleAuthCheck">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <p class="todo-title">{{__('page.google-authenticator')}}-{{__('page.ss3')}} . </p>
                                                        </div>
                                                    </div>
                                                    <!-- google authenticator modal -->
                                                    <section class="panel-dark mt-5" id="google_auth_modal">
                                                        @php

                                                        use App\Services\GoogleAuthenticator;

                                                        $ga = new GoogleAuthenticator();
                                                        $secret = $ga->createSecret();
                                                        $qrCodeUrl = $ga->getQRCodeGoogleUrl(auth()->user()->email, $secret, config('app.name'));

                                                        @endphp
                                                        <header class="panel-heading bg-primary">
                                                            <h2 class="panel-title text-light">GOOGLE ATHENTICATOR</h2>
                                                        </header>
                                                        <style>
                                                            .light-layout .tfa-card {
                                                                background: #f6f6f6 !important;
                                                            }

                                                            .dark-layout .tfa-card {
                                                                background: #161d31 !important;
                                                            }
                                                        </style>
                                                        <div class="panel-body tfa-card">
                                                            <!-- google auth setup form -->
                                                            <form action="{{ route('admin.settings.security_setting.google_auth_set') }}" method="post" enctype="multipart/form-data" id="google_auth_setup_form">
                                                                @csrf
                                                                <input type="hidden" name="user_id" value="<?= auth()->user()->id ?>">
                                                                <ul class="row">
                                                                    <li class="col-sm-12 p-3">
                                                                        <div class="col-sm-1 staper"><span class="step">1</span></div>
                                                                        <div class="col-sm-5 step-title">
                                                                            <h4>Download 2 FA backup key</h4>
                                                                        </div>
                                                                        <div class="col-sm-6" style="float: left;">
                                                                            <div class="input-group has-validation">
                                                                                <input type="text" class="form-control" id="secret_key" name="secret_key" value="<?= $secret ?>" aria-describedby="secret_key" />
                                                                                <button type="button" class="input-group-text" data-clipboard-target="#secret_key" id="copy_secret_key">
                                                                                    <i data-feather='download'></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </li>


                                                                    <li class="col-sm-12 p-3" style="padding-top: 0px !important; padding-bottom: 0px !important;">
                                                                        <div class="col-sm-1 staper"><span class="step">2</span></div>
                                                                        <div class="col-sm-5 step-title">
                                                                            <h4>Download and Install</h4>
                                                                        </div>
                                                                        <div class="col-sm-6" style="float:left;">
                                                                            <div class="app-link">
                                                                                <a class="pb-1 inner-app-link" style="float: right;" href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank"><img class="auth-app-logo" src="{{ asset('admin-assets/images/iphone.png') }}" /></a>

                                                                                <a class="pb-1" style="float: right;" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank"><img class="auth-app-logo" src="{{ asset('admin-assets/images/android.png') }}" /></a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="clr"></div>
                                                                    </li>
                                                                    <li class="col-sm-12 p-3">
                                                                        <div class="col-sm-1 staper"><span class="step">3</span></div>
                                                                        <div class="col-sm-11 step-title">
                                                                            <h4>Scan QR:</h4>
                                                                        </div>
                                                                    </li>
                                                                    <li class="col-sm-12 pl-3 pb-3">
                                                                        <div class="col-sm-1" style="float: left;">&nbsp;</div>
                                                                        <div class="col-sm-4" id="qrcode" style="float: left; height: 100%;">
                                                                            <img src='<?= $qrCodeUrl; ?>' />
                                                                        </div>
                                                                        <div class="col-sm-6 pt-2" style="float: left;">
                                                                            <h4>Enter 2FA verification code form the app</h4>
                                                                            <div class="input-group mb-md">
                                                                                <button class="input-group-text">
                                                                                    <img class="app-input-logo" src="{{ asset('admin-assets/images/apple-brands.svg') }}" />
                                                                                </button>
                                                                                <input type="text" class="form-control" name="v_code" placeholder="Enter 2FA verification code form the app" />
                                                                                <button class="input-group-text">
                                                                                    <img class="app-input-logo" src="{{ asset('admin-assets/images/android-brands.svg') }}" />
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                                <div class="col-sm-12 mt-1" style="text-align: center;">
                                                                    <label class="form-label">&nbsp;</label>
                                                                    <div>
                                                                        <button type="button" class="btn btn-primary me-1 mb-4" id="googleAuthSetupBtn" onclick="_run(this)" data-el="fg" data-form="google_auth_setup_form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="googleAuthSetupCallBack" data-btnid="googleAuthSetupBtn">Save Change</button>
                                                                        <button type="reset" class="btn btn-outline-secondary mb-4">Reset</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </section>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                                <form action="{{route('admin.otp-settings.save')}}" method="post" id="form-otp-settings" class="row gx-3 align-items-center">
                                                    @csrf
                                                    <div class="col-md-7" style="border-right:1px solid var(--custom-primary)">
                                                        <div class="card p-0 m-0">
                                                            <!-- otp deposit -->
                                                            <div class="card-body p-0 d-none">
                                                                <div class="title-wrapper d-flex">
                                                                    <div class="d-flex flex-column float-start">
                                                                        <div class="form-check form-switch form-check-primary">
                                                                            <input type="checkbox" class="form-check-input input-otp" id="otp_deposit" name="deposit" {{$deposit_check}} />
                                                                            <label class="form-check-label" for="otp_deposit">
                                                                                <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                                <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <label for="otp_deposit" class="todo-title">{{__('page.otp_deposit')}}</label>
                                                                </div>
                                                            </div>
                                                            <!-- otp withdraw -->
                                                            <div class="card-body p-0">
                                                                <div class="title-wrapper d-flex">
                                                                    <div class="d-flex flex-column float-start">
                                                                        <div class="form-check form-switch form-check-primary">
                                                                            <input type="checkbox" class="form-check-input input-otp" name="withdraw" id="otp_withdraw" {{$withdraw_check}} />
                                                                            <label class="form-check-label" for="otp_withdraw">
                                                                                <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                                <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <label for="otp_withdraw" class="todo-title">{{__('page.otp_withdraw')}}</label>
                                                                </div>
                                                            </div>
                                                            <!-- orpt transfer -->
                                                            <div class="card-body p-0">
                                                                <div class="title-wrapper d-flex">
                                                                    <div class="d-flex flex-column float-start">
                                                                        <div class="form-check form-switch form-check-primary">
                                                                            <input type="checkbox" class="form-check-input input-otp" name="transfer" id="otp_transfer" {{$transfer_check}} />
                                                                            <label class="form-check-label" for="otp_transfer">
                                                                                <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                                <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <label for="otp_transfer" class="todo-title">{{__('page.otp_transfer')}}</label>
                                                                </div>
                                                            </div>
                                                            <!-- otp open account -->
                                                            <div class="card-body p-0 ">
                                                                <div class="title-wrapper d-flex">
                                                                    <div class="d-flex flex-column float-start">
                                                                        <div class="form-check form-switch form-check-primary">
                                                                            <input type="checkbox" class="form-check-input input-otp" name="open_account" id="otp_live_account" {{$open_account_check}} />
                                                                            <label class="form-check-label" for="otp_live_account">
                                                                                <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                                <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <label for="otp_live_account" class="todo-title">{{__('page.otp_live_account')}}</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- otp all -->
                                                    <div class="col-md-5">
                                                        <div class="card p-0 m-0" style="border:1px solid var(--custom-primary);padding:13px 7px 0 7px !important">
                                                            <div class="card-body p-0">
                                                                <div class="title-wrapper d-flex">
                                                                    <div class="d-flex flex-column float-start">
                                                                        <div class="form-check form-switch form-check-primary">
                                                                            <input type="checkbox" class="form-check-input" id="otp_all" name="otp_all" {{$all_check}} />
                                                                            <label class="form-check-label" for="otp_all">
                                                                                <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                                <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <label for="otp_all" class="todo-title">{{__('page.otp_all')}}.</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <!-- submit button -->
                                                <div class="col-12 col-md-3 ms-auto mt-4">
                                                    <button type="button" id="btn-submit-otp" data-btnid="btn-submit-otp" data-form="form-otp-settings" onclick="_run(this)" class="btn btn-primary w-100" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="otp_settings_callback">Save OTP Settings</button>
                                                </div>
                                                <!-- ending otp form -->
                                            </div>
                                            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                                                <div class="card">
                                                    <div class="card-header border-bottom mb-0">
                                                        <div class="card my-0 py-0 w-100">
                                                            <div class="card-body my-0 p-0">
                                                                <div class="title-wrapper d-flex align-items-center">
                                                                    <div class="d-flex flex-column">
                                                                        <div class="form-check form-switch form-check-primary">
                                                                            <input type="checkbox" class="form-check-input" id="kyc-back-part" value="kyc_back_part" {{ isset($system_configs->kyc_back_part) ? (($system_configs->kyc_back_part == 1) ? "checked" : "") : "" }} />
                                                                            <label class="form-check-label" for="kyc-back-part">
                                                                                <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                                <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <p class="todo-title float-start mb-0">KYC {{__('page.Back_Part_Required')}}.</p>
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
        </div>
    </div>
</div>
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<!-- datatable -->
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- admin settings common ajax -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-settings.js')}}"></script>
<script>
    // kyc back part required ajax
    $(document).on('change', '#kyc-back-part', function(event) {
        let check_value = ($('#kyc-back-part').prop("checked") == true) ? 1 : 0;
        // console.log(check_value);
        $(this).confirm2({
            request_url: '/admin/settings/kyc-back-part/' + check_value,
            click: false,
            title: ($(this).prop("checked") == true) ? 'Enable KYC Back Part' : 'Disable KYC Back Part',
            message: 'Are you confirm to Enable KYC back part?',
            button_text: 'Enable',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'KYC Back Part');
            } else {
                notify('error', data.message, 'KYC Back Part');
            }

        });
    });

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
            $('#google_auth_modal').hide();
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
            $('#google_auth_modal').hide();
        }
    });
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
    // no auth
    $(document).on('change', '#noAuthCheck', function(event) {
        $('#google_auth_modal').hide();
        let check_auth = $('#noAuthCheck').val();
        $(this).confirm2({
            request_url: '/admin/settings/security_setting/update/' + check_auth,
            click: false,
            title: ($(this).prop("checked") == true) ? 'Enable Simple Security System' : 'Enable Email Authentication',
            message: 'Are you confirm to Enable Simple Security System?',
            button_text: 'Enable',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'Authentication');
            } else {
                notify('error', data.message, 'Authentication');
            }

        });
    });



    // mail auth
    $(document).on('click', '#mailAuthCheck', function(event) {
        $('#google_auth_modal').hide();
        let check_auth = $('#mailAuthCheck').val();
        $(this).confirm2({
            request_url: '/admin/settings/security_setting/update/' + check_auth,
            click: false,
            title: ($(this).prop("checked") == true) ? 'Enable Email Authentication' : 'Enable Simple Security Authentication',
            message: 'Are you confirm to Enable Email Authentication System?',
            button_text: 'Enable',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'Authentication');
            } else {
                notify('error', data.message, 'Authentication');
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
    // otp settings callback
    function otp_settings_callback(data) {
        if (data.status) {
            notify('success', data.message, 'OTP Settings');
        } else {
            notify('error', data.message, 'OTP Settings');
        }
    }
</script>
@stop
<!-- BEGIN: page JS -->