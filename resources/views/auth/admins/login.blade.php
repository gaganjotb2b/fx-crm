@extends('layouts.admin-auth')
@section('title','Admin Login')
@section('content')
<div class="auth-wrapper auth-cover">
    <div class="auth-inner row m-0">
        <!-- Left Text-->
        <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
            <div class="w-100 d-lg-flex align-items-center justify-content-center px-5"><img class="img-fluid" src="{{ asset('admin-assets/app-assets/images/pages/login-v2.svg') }}" alt="Login V2" /></div>
        </div>
        <!-- /Left Text-->
        <!-- Login-->
        <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                <!-- Brand logo-->
                <a class="brand-logo pb-4 w-auto" href="{{route('admin.login')}}">
                    <img src="{{ get_admin_logo() }}" height="40" alt="{{ config('app.name') }}">
                </a>
                <!-- /Brand logo-->
                <!-- start admin login header -->
                <h2 class="card-title fw-bold mb-1 admin-login-form">Welcome to {{ strtoupper(config('app.name')) }}! </h2>
                <h2 class="card-title fw-bold mb-1 mail-verification-form d-none">Mail Verification</h2>
                <h2 class="card-title fw-bold mb-1 google-verification-form d-none">Google Verification</h2>
                <p class="card-text mb-2 admin-login-form">Please sign-in to your account and start the adventure</p>
                <p class="card-text mb-5 mail-verification-form d-none">Enter your email verification code to sign in</p>
                <p class="card-text mb-5 google-verification-form d-none">Enter your google verification code to sign in</p>
                <h2 class="card-title fw-bold mb-1 admin-forgot-password-form d-none">Forgot Password</h2>
                <p class="card-text mb-5 admin-forgot-password-form d-none">Enter your email to find your account.</p>
                <h2 class="card-title fw-bold mb-1 admin-forgot-password-verification-form d-none">Forgot Password</h2>
                <p class="card-text mb-5 admin-forgot-password-verification-form d-none">Provide the verification key from your email.</p>
                <h2 class="card-title fw-bold mb-1 admin-create-new-password-form d-none">Forgot Password</h2>
                <p class="card-text mb-5 admin-create-new-password-form d-none">Create a new password.</p>
                @if(session('login-success'))
                <div class="alert alert-success" role="alert">
                    {{ session('login-success') }}
                </div>
                @endif
                <div id="alert-message" class="alert alert-danger p-1 d-none" role="alert">

                </div>
                <!-- end admin login header -->

                <!-- start admin login body -->
                <form class="auth-login-form mt-2 admin-login-form" action="{{route('admin.login.action')}}" method="POST" enctype="multipart/form-data" id="admin-login-form">
                    @csrf
                    <div class="mb-1">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control email" type="text" name="email" placeholder="john@example.com" aria-describedby="email" autofocus="" tabindex="1" />
                    </div>
                    <div class="mb-1">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="password">Password</label>
                        </div>
                        <div class="input-group input-group-merge form-password-toggle">
                            <input class="form-control form-control-merge password" type="password" name="password" placeholder="············" aria-describedby="password" tabindex="2" /><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                        </div>
                        <div class="d-flex justify-content-between"></div>
                    </div>
                    <input type="hidden" name="request_form" value="login_form">
                    <div class="mb-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me">
                            <label class="form-check-label" for="remember-me"> Remember Me</label><a class="text-danger float-end forgot_password" href="#"><u>{{__('page.forgot_password')}}?</u></a>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary w-100 temp_disabled" id="loginBtn" onclick="_run(this)" data-el="fg" data-form="admin-login-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="adminLoginCallBack" data-btnid="loginBtn">Sign In</button>
                </form>
                <!-- end admin login main -->

                <!-- start admin mail verification form body -->
                <form class="mt-2 mail-verification-form d-none" action="{{route('admin.login.action')}}" method="POST" id="mail-verification-form">
                    @csrf
                    <h6>Type your 6 digit security code</h6>
                    <div class="row gx-2 gx-sm-3 mt-5">
                        <div class="col-2 p-0 m-0">
                            <div class="form-group">
                                <input type="text" name="v_code1" class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" maxlength="1" autocomplete="off" autocapitalize="off">
                            </div>
                        </div>
                        <div class="col-2 p-0 m-0">
                            <div class="form-group">
                                <input type="text" name="v_code2" class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" maxlength="1" autocomplete="off" autocapitalize="off">
                            </div>
                        </div>
                        <div class="col-2 p-0 m-0">
                            <div class="form-group">
                                <input type="text" name="v_code3" class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" maxlength="1" autocomplete="off" autocapitalize="off">
                            </div>
                        </div>
                        <div class="col-2 p-0 m-0">
                            <div class="form-group">
                                <input type="text" name="v_code4" class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" maxlength="1" autocomplete="off" autocapitalize="off">
                            </div>
                        </div>
                        <div class="col-2 p-0 m-0">
                            <div class="form-group">
                                <input type="text" name="v_code5" class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" maxlength="1" autocomplete="off" autocapitalize="off">
                            </div>
                        </div>
                        <div class="col-2 p-0 m-0">
                            <div class="form-group">
                                <input type="text" name="v_code6" class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" maxlength="1" autocomplete="off" autocapitalize="off">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="request_form" value="mail_verify">
                    <input type="hidden" name="email" class="form-control v_email" placeholder="Email" aria-label="Email" required>
                    <input type="hidden" name="password" class="form-control v_password" placeholder="Password" aria-label="Password" required>
                    <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0 temp_disabled" id="mailVerificationBtn" onclick="_run(this)" data-el="fg" data-form="mail-verification-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="adminLoginMailVerificationCallBack" data-btnid="mailVerificationBtn">Verify</button>
                </form>
                <!-- end admin mail verification form body -->
                <!-- start admin google verification form body -->
                <form class="mt-2 mb-3 google-verification-form d-none" action="{{route('admin.login.action')}}" method="POST" id="google-verification-form">
                    @csrf
                    <h6>Type your 6 digit security code</h6>
                    <div class="auth-input-wrapper d-flex align-items-center justify-content-between pb-2">
                        <input class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" name="v_code1" type="text" maxlength="1" autofocus="" />
                        <input class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" name="v_code2" type="text" maxlength="1" />
                        <input class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" name="v_code3" type="text" maxlength="1" />
                        <input class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" name="v_code4" type="text" maxlength="1" />
                        <input class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" name="v_code5" type="text" maxlength="1" />
                        <input class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" name="v_code6" type="text" maxlength="1" />
                    </div>
                    <input type="hidden" name="request_form" value="google_verify">
                    <input type="hidden" name="email" class="form-control v_email" placeholder="Email" aria-label="Email" required>
                    <input type="hidden" name="password" class="form-control v_password" placeholder="Password" aria-label="Password" required>
                    <button type="button" class="btn btn-primary w-100 temp_disabled" id="googleVerificationBtn" onclick="_run(this)" data-el="fg" data-form="google-verification-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="adminLoginGoogleVerificationCallBack" data-btnid="googleVerificationBtn">Verify</button>
                </form>
                <!-- end admin mail verification form body -->




                <!--Start: forgot password-->
                <form action="{{ route('user.forgot_password') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start admin-forgot-password-form d-none" id="trader-forgot-password-form">
                    @csrf
                    <label>Email</label>
                    <div class="mb-3">
                        <input type="email" name="forgot_email" class="form-control forgot_email" placeholder="Find Your Account By Email" aria-label="Email" required>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn bg-primary text-light w-100 mt-2 mb-0 temp_disabled" id="forgotEmailBtn" onclick="_run(this)" data-el="fg" data-form="trader-forgot-password-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="forgotEmailCallBack" data-btnid="forgotEmailBtn">Send</button>
                    </div>
                    <input type="hidden" name="submit_form" value="fp_email">
                    <input type="hidden" name="user_type" value="2">
                </form>
                <!-- forgot password verification start-->
                <form action="{{ route('user.forgot_password') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start admin-forgot-password-verification-form d-none" id="trader-forgot-password-verification-form">
                    @csrf
                    <div class="auth-input-wrapper d-flex align-items-center justify-content-between pb-2">
                        <input class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" name="v_code1" type="text" maxlength="1" />
                        <input class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" name="v_code2" type="text" maxlength="1" />
                        <input class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" name="v_code3" type="text" maxlength="1" />
                        <input class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" name="v_code4" type="text" maxlength="1" />
                        <input class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" name="v_code5" type="text" maxlength="1" />
                        <input class="otp-value form-control auth-input height-50 text-center numeral-mask mx-25 mb-1" name="v_code6" type="text" maxlength="1" />
                    </div>

                    <input type="hidden" name="submit_form" value="fp_vcode">
                    <input type="hidden" name="user_type" value="2">
                    <input type="hidden" name="fp_email" class="form-control fp_email" placeholder="Email" aria-label="Email" required>
                    <div class="text-center">
                        <button type="button" class="btn bg-primary text-light w-100 mt-4 mb-0 temp_disabled" id="fpVerifyBtn" onclick="_run(this)" data-el="fg" data-form="trader-forgot-password-verification-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="fpVerifyBtnCallBack" data-btnid="fpVerifyBtn">Verify</button>
                        <span class="text-muted text-sm">Haven't received it?<a href="#" class="send_verification_key"> Resend a new code</a>.</span>
                    </div>
                </form>
                <!-- forgot password verification end-->
                <!-- create a new password start-->
                <form action="{{ route('user.forgot_password') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start admin-create-new-password-form d-none" id="trader-create-new-password-form">
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
                    <input type="hidden" name="user_type" value="2">
                    <div class="text-center">
                        <button type="button" class="btn bg-primary text-light w-100 mt-4 mb-0 temp_disabled" id="createNewPasswordBtn" onclick="_run(this)" data-el="fg" data-form="trader-create-new-password-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="createNewPasswordCallBack" data-btnid="createNewPasswordBtn">Save Change</button>
                    </div>
                </form>
                <!-- create a new password end-->
                <!-- start admin login footer -->
                <!-- <p class="text-center mt-2 mail-verification-form"><span>Haven't received it?</span><a class="send_verification_key" href="#"><span>&nbsp;Resend a new code</span></a></p>
                <p class="text-center mt-2 mail-verification-form"><span>&nbsp;</span><a class="send_verification_key" href="#"><span>&nbsp;</span></a></p>
                <p class="text-center mt-2 admin-login-form"><span>New on our platform?</span><a href="{{ route('admin.registration') }}"><span>&nbsp;Create an account</span></a></p>
                <div class="divider my-2 admin-login-form">
                    <div class="divider-text admin-login-form">or</div>
                </div> -->

                <!-- end admin login footer -->
            </div>
        </div>
        <!-- /Login-->
    </div>
</div>
@stop
@section('page-js')
<!-- BEGIN: Page JS-->
<script>
    $(document).on('click', '.temp_disabled', function() {
        $('.temp_disabled').prop('disabled', true);
        setTimeout(function() {
            $('.temp_disabled').prop('disabled', false);
        }, 3000);
    });
    $('.mail-verification-form').hide();
    $('.google-verification-form').hide();
    $('.admin-forgot-password-form').hide();
    $('.admin-forgot-password-verification-form').hide();
    $('.admin-create-new-password-form').hide();
    // trigger when press enter key
    document.onkeydown = function(evt) {
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if (keyCode == 13) {
            $('#loginBtn').trigger('click');
        }
    }
    // admin login
    function adminLoginCallBack(data) {
        $('#loginBtn').prop('disabled', true);
        if (data.status == true) {
            if (data.modal == 'mail-verification-form') {
                $('.admin-login-form').slideToggle(1500);
                $('.mail-verification-form').removeClass('d-none');
                $('.mail-verification-form').slideToggle(3000);
                $('.v_email').val(data.email);
                $('.v_password').val(data.password);
                notify('success', data.message, 'Mail Verification');
            } else if (data.modal == 'google-verification-form') {
                $('.admin-login-form').slideToggle(1500);
                $('.google-verification-form').removeClass('d-none');
                $('.google-verification-form').slideToggle(3000);
                $('.v_email').val(data.email);
                $('.v_password').val(data.password);
            } else {
                notify('success', data.message, 'Admin Login');
                setTimeout(function() {
                    window.location.href = "/admin/dashboard";
                }, 1000 * 2);
            }
        } else {
            if (data.message != null) {
                notify('error', data.message, 'Admin Login');
                $.validator("admin-login-form", data.errors);
            } else {
                notify('error', 'Something Went Wrong!', 'Admin Login');
                $.validator("admin-login-form", data.errors);
            }
        }

        setTimeout(function() {
            $('#loginBtn').prop('disabled', false);
        }, 3000);
    }
    // email verification
    function adminLoginMailVerificationCallBack(data) {
        $('#mailVerificationBtn').prop('disabled', false);
        if (data.status == true) {
            notify('success', data.message, 'Admin Login');
            setTimeout(function() {
                window.location.href = "/admin/dashboard";
            }, 1000 * 2);
        } else {
            notify('error', data.message, 'Admin Login');
            $.validator("mail-verification-form", data.errors);
        }
    }
    // google verification
    function adminLoginGoogleVerificationCallBack(data) {
        $('#googleVerificationBtn').prop('disabled', false);
        if (data.status == true) {
            notify('success', data.message, 'Admin Login');
            setTimeout(function() {
                window.location.href = "/admin/dashboard";
            }, 1000 * 2);
        } else {
            notify('error', data.message, 'Admin Login');
            $.validator("google-verification-form", data.errors);
        }
    }

    // otp value jQuery property 
    $(document).on("keyup", ".otp-value", function(e) {
        let $value = $(this).val();
        if ((e.keyCode == 8)) {
            $(this).prev(".otp-value").focus();
        }
        if ($value != "") {
            $(this).next(".otp-value").focus();
            // $(this).closest(".col").prev(".col").find(".otp-value").focus();
        }

    });
    // otp value jQuery property 
    $(document).on("keyup", ".otp-value", function(e) {
        let $value = $(this).val();
        if ($value != "") {
            $(this).closest(".col-2").next(".col-2").find(".otp-value").focus();
        }
        if ((e.keyCode == 8)) {
            $(this).closest(".col-2").prev(".col-2").find(".otp-value").focus();
        }

    });

    // resend verification key
    $(document).on('click', '.send_verification_key', function() {
        var v_email = $('.fp_email').val();
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
    $(document).ready(function() {
        $("button").each(function() {
            $(this).removeClass("waves-effect");
        });
    })



    // forgot password property
    $(document).on('click', '.forgot_password', function() {
        $('.admin-login-form').slideToggle(1500);
        $('.admin-forgot-password-form').removeClass('d-none');
        $('.admin-forgot-password-form').css({
            'display': 'content'
        });
        $('.admin-forgot-password-form').slideToggle(3000);
    });
    // forgot password callback
    function forgotEmailCallBack(data) {
        if (data.status == true) {
            $('.admin-forgot-password-form').slideToggle(1500);
            $('.admin-forgot-password-verification-form').removeClass('d-none');
            $('.admin-forgot-password-verification-form').css({
                'display': 'content'
            });
            $('.admin-forgot-password-verification-form').slideToggle(3000);
            $('.fp_email').val(data.fp_email);
        } else {
            notify('error', data.message, "Forgot Password");
        }
    }
    // forgot password verification callback
    function fpVerifyBtnCallBack(data) {
        if (data.status == true) {
            $('.admin-forgot-password-verification-form').slideToggle(1500);
            $('.admin-create-new-password-form').removeClass('d-none');
            $('.admin-create-new-password-form').css({
                'display': 'content'
            });
            $('.admin-create-new-password-form').slideToggle(3000);
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
<!-- END: Page JS-->
@stop