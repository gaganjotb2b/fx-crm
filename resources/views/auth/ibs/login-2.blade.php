@extends('layouts.trader-auth')
@section('title','IB Login')
@section('content')
<style>
    .form-control-lg {
        text-align: center;
    }

    .error-msg {
        color: red;
    }

    .brand-logo {
        top: 10%;
        left: 24%;
        position: absolute;
    }

    .ms-n6 {
        margin-left: -5rem !important;
    }

    @media only screen and (min-width:768px) {
        .mobile-view-logo {
            display: none;
        }
    }
</style>
<div class="row">
    <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">

        <div class="card mt-5">
            <a class="brand-logo rounded-pill p-1 mobile-view-logo" href="{{route('login')}}">
                <img src="{{ get_user_logo() }}" height="30" alt="{{ config('app.name') }}">
            </a>
        </div>
        <div class="card card-plain mt-5">
            <div class="card-header pb-0 text-start login-form-body">
                <h3 class="font-weight-bolder text-primary text-gradient">{{__('page.welcome_back')}}</h3>
                <p class="mb-0">{{__('page.enter_your_email_and_password_to_sign_in')}}</p>
            </div>
            <div class="card-header pb-0 text-start mail-verification-form-body d-none">
                <h3 class="font-weight-bolder text-primary text-gradient">{{__('page.email')}} {{__('page.verification')}}</h3>
                <p class="mb-0">{{__('page.enter_your_email_verification_code_to_sign_in')}}</p>
            </div>
            <div class="card-header pb-0 text-start google-verification-form-body d-none">
                <h3 class="font-weight-bolder text-primary text-gradient">{{__('page.google')}} {{__('page.verification')}}</h3>
                <p class="mb-0">{{__('page.enter_your_google_verification_code_to_sign_in')}}</p>
            </div>
            <div class="card-header pb-0 text-start user-forgot-password-form-body d-none">
                <h3 class="font-weight-bolder text-primary text-gradient">Forgot Password</h3>
                <p class="mb-0">Enter your email to find your account.</p>
            </div>
            <div class="card-header pb-0 text-start user-forgot-password-verification-form-body d-none">
                <h3 class="font-weight-bolder text-primary text-gradient">Forgot Password</h3>
                <p class="mb-0">Provide the verification key from your email.</p>
            </div>
            <div class="card-header pb-0 text-start user-create-new-password-form-body d-none">
                <h3 class="font-weight-bolder text-primary text-gradient">Forgot Password</h3>
                <p class="mb-0">Create a new password.</p>
            </div>
            <div class="card-body">
                <form action="{{ route('ib.login.action') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start login-form-body" id="ib-login-form">
                    @csrf
                    <label>{{__('page.email')}}</label>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control email" placeholder="Email" aria-label="Email" required>
                    </div>
                    <label>{{__('page.password')}}</label>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control password" placeholder="Password" aria-label="Password" required>
                    </div>
                    <input type="hidden" name="request_form" value="login_form">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me">
                        <label class="form-check-label" for="rememberMe">{{__('page.remember_me')}}</label>
                        <span class="float-end"><a class="text-danger small forgot_password" href="#"><u>Forgot Password?</u></a></span>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0" id="loginBtn" onclick="_run(this)" data-el="fg" data-form="ib-login-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="ibLoginCallBack" data-btnid="loginBtn">{{__('page.sign_in')}}</button>
                    </div>
                </form>
                <!-- mail verification -->
                <form action="{{ route('ib.login.action') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start mail-verification-form-body d-none" id="ib-login-mail-verification-form">
                    @csrf
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
                        <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0" id="mailVerificationBtn" onclick="_run(this)" data-el="fg" data-form="ib-login-mail-verification-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="ibLoginMailVerificationCallBack" data-btnid="mailVerificationBtn">{{__('page.verify')}}</button>
                        <span class="text-muted text-sm">{{__('page.haven\'t-received-it?')}}<a href="javascript:;">{{__('page.resend_a new_code')}} </a>.</span>
                    </div>
                </form>
                <!-- google verification -->
                <form action="{{ route('ib.login.action') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start google-verification-form-body d-none" id="ib-login-google-verification-form">
                    @csrf
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
                        <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0" id="googleVerificationBtn" onclick="_run(this)" data-el="fg" data-form="ib-login-google-verification-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="ibLoginGoogleVerificationCallBack" data-btnid="googleVerificationBtn">{{__('page.verify')}}</button>
                        <span class="text-muted text-sm">{{__('page.haven\'t-received-it?')}}<a href="javascript:;"> {{__('page.resend_a new_code')}}</a>.</span>
                    </div>
                </form>

                <!--Start: forgot password-->
                <form action="{{ route('user.forgot_password') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start user-forgot-password-form-body d-none" id="trader-forgot-password-form">
                    @csrf
                    <label>Email</label>
                    <div class="mb-3">
                        <input type="email" name="forgot_email" class="form-control forgot_email" placeholder="Find Your Account By Email" aria-label="Email" required>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0" id="forgotEmailBtn" onclick="_run(this)" data-el="fg" data-form="trader-forgot-password-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="forgotEmailCallBack" data-btnid="forgotEmailBtn">Send</button>
                    </div>
                    <input type="hidden" name="submit_form" value="fp_email">
                    <input type="hidden" name="user_type" value="4">
                </form>
                <!-- forgot password verification start-->
                <form action="{{ route('user.forgot_password') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start user-forgot-password-verification-form-body d-none" id="trader-forgot-password-verification-form">
                    @csrf
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
                        <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0" id="fpVerifyBtn" onclick="_run(this)" data-el="fg" data-form="trader-forgot-password-verification-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="fpVerifyBtnCallBack" data-btnid="fpVerifyBtn">Verify</button>
                        <span class="text-muted text-sm">Haven't received it?<a href="#" class="send_verification_key"> Resend a new code</a>.</span>
                    </div>
                </form>
                <!-- forgot password verification end-->
                <!-- create a new password start-->
                <form action="{{ route('user.forgot_password') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start user-create-new-password-form-body d-none" id="trader-create-new-password-form">
                    @csrf
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
                        <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0" id="createNewPasswordBtn" onclick="_run(this)" data-el="fg" data-form="trader-create-new-password-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="createNewPasswordCallBack" data-btnid="createNewPasswordBtn">Save Change</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center pt-0 px-lg-2 px-1 login-form-body">
                <p class="mb-4 text-sm mx-auto">
                    {{__('page.don\'t_have_an_account?')}}
                    <a href="{{ route('ib.registration') }}" class="text-info text-gradient font-weight-bold">{{__('page.sign_up')}}</a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
            <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style='background-image:unset; background-color: var(--custom-primary);'>
                <a class="brand-logo rounded-pill p-1" href="{{route('admin.login')}}">
                    <img src="{{ get_user_logo() }}" height="50" alt="{{ config('app.name') }}">
                </a>
                <div class="__al_shape_text">
                    <div class="alert " role="alert">
                        <h4 class="alert-heading" style="color:#fff;">Start Trading with {{ strtoupper(config('app.name')) }}. Trade and invest in Trading
                            platforms,
                            Buy and sells</h4>
                        <div class="alert-body" style="color:#fff;">
                            Login into {{ strtoupper(config('app.name')) }}, the trusted client portal traders for over few years. Trade on
                            MetaTrader – one of the most popular trading platforms in the world. <br> sign-in to your
                            account
                            and continue to the dashboard.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-js')
<!-- BEGIN: Page JS-->
<!-- END: Page JS-->
<script>
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
        $('#loginBtn').prop('disabled', false);
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
        $('#mailVerificationBtn').prop('disabled', false);
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
        $('#mailVerificationBtn').prop('disabled', false);
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
@stop