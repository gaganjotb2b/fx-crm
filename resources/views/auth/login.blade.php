<?php

use Illuminate\Support\Facades\Cookie;
?>
@extends('layouts.trader-auth')
@section('title','Trader Login')
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
            <a class="brand-logo rounded-pill p-1 mobile-view-logo" href="{{route('trader.login')}}">
                <img src="{{ get_user_logo() }}" height="30" alt="{{ config('app.name') }}">
            </a>
        </div>
        <div class="card card-plain mt-5">
            <div class="card-header pb-0 text-start login-form-body">
                <h3 class="font-weight-bolder text-primary text-gradient">Welcome back</h3>
                <p class="mb-0">Enter your email and password to sign in</p>
            </div>
            <div class="card-header pb-0 text-start mail-verification-form-body d-none">
                <h3 class="font-weight-bolder text-primary text-gradient">Mail Verification</h3>
                <p class="mb-0">Enter your email verification code to sign in</p>
            </div>
            <div class="card-header pb-0 text-start google-verification-form-body d-none">
                <h3 class="font-weight-bolder text-primary text-gradient">Google Verification</h3>
                <p class="mb-0">Enter your google verification code to sign in</p>
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
                <!-- login form -->
                <form action="{{ route('trader.login.action') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start login-form-body" id="trader-login-form">
                    @csrf
                    <label>Email</label>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control email" placeholder="Email" aria-label="Email" value="{{ Cookie::get('remember_email') }}" required>
                    </div>
                    <label>Password</label>
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
                        <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0 temp_disabled" id="loginBtn" onclick="_run(this)" data-el="fg" data-form="trader-login-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="traderLoginCallBack" data-btnid="loginBtn">Sign In</button>
                    </div>
                </form>
                <!-- mail verification -->
                <form action="{{ route('trader.login.action') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start mail-verification-form-body d-none" id="trader-login-mail-verification-form">
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
                        <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0 temp_disabled" id="mailVerificationBtn" onclick="_run(this)" data-el="fg" data-form="trader-login-mail-verification-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="traderLoginMailVerificationCallBack" data-btnid="mailVerificationBtn">Verify</button>
                        <span class="text-muted text-sm">Haven't received it?<a href="#" class="send_verification_key"> Resend a new code</a>.</span>
                    </div>
                </form>
                <!-- google verification -->
                <form action="{{ route('trader.login.action') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start google-verification-form-body d-none" id="trader-login-google-verification-form">
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
                        <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0 temp_disabled" id="googleVerificationBtn" onclick="_run(this)" data-el="fg" data-form="trader-login-google-verification-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="traderLoginGoogleVerificationCallBack" data-btnid="googleVerificationBtn">Verify</button>

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
                    <input type="hidden" name="user_type" value="0">
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
                        <input type="hidden" name="user_type" value="0">
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
                    <input type="hidden" name="user_type" value="0">
                    <div class="text-center">
                        <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0" id="createNewPasswordBtn" onclick="_run(this)" data-el="fg" data-form="trader-create-new-password-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="createNewPasswordCallBack" data-btnid="createNewPasswordBtn">Save Change</button>
                    </div>
                </form>
                <!-- create a new password end-->

                <!--End: forgot password-->
            </div>
            <div class="card-footer text-center pt-0 px-lg-2 px-1 login-form-body">
                <p class="mb-0 text-sm mx-auto">
                    Don't have an account?
                    <a href="{{ route('trader.registration') }}" class="text-info text-gradient font-weight-bold">Sign up</a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
            <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style='background-image:unset; background-color: var(--custom-primary);'>
                <a class="brand-logo p-2 bg-gradient-light rounded-2 w-100" href="{{route('trader.login')}}">
                    <img src="{{ get_user_logo() }}" height="40" alt="{{ config('app.name') }}">
                </a>
                <div class="__al_shape_text">
                    <div class="alert " role="alert">
                        <h4 class="alert-heading" style="color:#fff;">Start Trading with {{config('app.name')}}. Trade and invest in Trading
                            platforms,
                            Buy and sells</h4>
                        <div class="alert-body" style="color:#fff;">
                            Login into {{config('app.name')}}, the trusted client portal traders for over few years. Trade on
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    document.onkeydown = function(evt) {
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if (keyCode == 13) {
            $('#loginBtn').trigger('click');
        }
    }
    // trader login
    function traderLoginCallBack(data) {
        $('#loginBtn').prop('disabled', true);
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
                notify('success', data.message, 'User Login');
                setTimeout(function() {
                    window.location.href = "/user/dashboard";
                }, 1000 * 2);
            }
        } else {
            if (data.message != null) {
                notify('error', data.message, 'User Login');
                $.validator("trader-login-form", data.errors);
                if (data.id) {
                    showResendEmailPopup(data.id);
                }
            } else {
                notify('error', 'Something Went Wrong!', 'User Login');
                $.validator("trader-login-form", data.errors);
            }
        }
        setTimeout(function() {
            $('#loginBtn').prop('disabled', false);
        }, 3000);
    }
    // email verification
    function traderLoginMailVerificationCallBack(data) {
        $('#mailVerificationBtn').prop('disabled', true);
        if (data.status == true) {
            notify('success', data.message, 'User Login');
            setTimeout(function() {
                window.location.href = "/user/dashboard";
            }, 1000 * 2);
        } else {
            notify('error', data.message, 'User Login');
            $.validator("trader-login-mail-verification-form", data.errors);
        }
        setTimeout(function() {
            $('#mailVerificationBtn').prop('disabled', false);
        }, 3000);
    }
    // google verification
    function traderLoginGoogleVerificationCallBack(data) {
        $('#googleVerificationBtn').prop('disabled', true);
        if (data.status == true) {
            notify('success', data.message, 'User Login');
            setTimeout(function() {
                window.location.href = "/user/dashboard";
            }, 1000 * 2);
        } else {
            notify('error', data.message, 'User Login');
            $.validator("trader-login-mail-verification-form", data.errors);
        }
        setTimeout(function() {
            $('#googleVerificationBtn').prop('disabled', false);
        }, 3000);
    }

    function resendVerificationEmail(userId) {
        Swal.fire({
            title: "Sending...",
            text: "Please wait while we resend the verification email.",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: `trader/resend/verification/${userId}`,
            method: "GET",
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message || "Verification email has been resent successfully.",
                        icon: "success"
                    });
                    
                    // Start countdown timer for 50 seconds
                    startResendCooldown(userId);
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: response.message || "Failed to resend the email. Please try again.",
                        icon: "error"
                    });
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.cooldown) {
                    // Show cooldown message with remaining time
                    const remainingTime = xhr.responseJSON.remaining_time;
                    Swal.fire({
                        title: "Please Wait",
                        text: `You can request another verification email in ${remainingTime} seconds.`,
                        icon: "warning",
                        timer: 3000,
                        timerProgressBar: true
                    });
                    
                    // Start countdown timer with remaining time
                    startResendCooldown(userId, remainingTime);
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: xhr.responseJSON?.message || "Failed to resend the email. Please try again.",
                        icon: "error"
                    });
                }
            }
        });
    }

    // Global variable to store countdown timers
    window.resendCooldowns = {};

    function startResendCooldown(userId, remainingTime = 50) {
        // Clear existing timer if any
        if (window.resendCooldowns[userId]) {
            clearInterval(window.resendCooldowns[userId]);
        }

        let timeLeft = remainingTime;
        
        // Store the countdown state
        window.resendCooldowns[userId] = {
            timer: null,
            timeLeft: timeLeft,
            isActive: true
        };
        
        const updateCountdown = () => {
            if (timeLeft > 0) {
                timeLeft--;
                window.resendCooldowns[userId].timeLeft = timeLeft;
            } else {
                // Clear countdown
                clearInterval(window.resendCooldowns[userId].timer);
                window.resendCooldowns[userId].isActive = false;
                delete window.resendCooldowns[userId];
            }
        };
        
        // Start countdown
        window.resendCooldowns[userId].timer = setInterval(updateCountdown, 1000);
    }

    function showResendEmailPopup(userId) {
        // Check if user is in cooldown
        if (window.resendCooldowns && window.resendCooldowns[userId] && window.resendCooldowns[userId].isActive) {
            const timeLeft = window.resendCooldowns[userId].timeLeft;
            
            Swal.fire({
                title: "Please Wait",
                html: `
                    <div class="text-center">
                        <p>You can request another verification email in:</p>
                        <div class="countdown-timer" style="font-size: 2rem; font-weight: bold; color: #7367f0; margin: 1rem 0;">
                            ${timeLeft} seconds
                        </div>
                    </div>
                `,
                icon: "warning",
                showConfirmButton: true,
                confirmButtonText: "OK",
                timer: null,
                timerProgressBar: false
            });
            
            // Update countdown in popup
            const updatePopupCountdown = () => {
                if (window.resendCooldowns[userId] && window.resendCooldowns[userId].isActive) {
                    const remaining = window.resendCooldowns[userId].timeLeft;
                    const countdownElement = document.querySelector('.countdown-timer');
                    if (countdownElement) {
                        countdownElement.textContent = `${remaining} seconds`;
                    }
                }
            };
            
            // Update popup countdown every second
            const popupTimer = setInterval(updatePopupCountdown, 1000);
            
            // Clear timer when popup is closed
            Swal.getPopup().addEventListener('click', () => {
                clearInterval(popupTimer);
            });
            
            return;
        }

        Swal.fire({
            title: "Verify Your Email",
            text: "It looks like your email is not verified. Would you like to resend the verification email?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Resend Email",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                resendVerificationEmail(userId);
            }
        });
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
        $('#forgotEmailBtn').prop('disabled', true);
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
        setTimeout(function() {
            $('#forgotEmailBtn').prop('disabled', false);
        }, 3000);
    }
    // forgot password verification callback
    function fpVerifyBtnCallBack(data) {
        $('#fpVerifyBtn').prop('disabled', true);
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
        setTimeout(function() {
            $('#fpVerifyBtn').prop('disabled', false);
        }, 3000);
    }
    // create new password callback
    function createNewPasswordCallBack(data) {
        $('#createNewPasswordBtn').prop('disabled', true);
        if (data.status == true) {
            notify('success', data.message, 'Forgot Password');
            setTimeout(function() {
                location.reload();
            }, 5000);
        } else {
            notify('error', "Fix The Following Error!", "Forgot Password");
            $.validator("trader-create-new-password-form", data.errors);
        }
        setTimeout(function() {
            $('#createNewPasswordBtn').prop('disabled', false);
        }, 3000);
    }
</script>
@stop