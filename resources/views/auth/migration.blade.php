<?php

use Illuminate\Support\Facades\Cookie;
?>
@extends('layouts.trader-auth')
@section('title','Migration')
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
                <!--<form action="{{ route('migration') }}" method="POST" enctype="multipart/form-data" role="form" class="text-start login-form-body" id="trader-login-form">-->
                <!--    @csrf-->
                <!--    <label for="csv_file">CSV File</label>-->
                <!--    <div class="mb-3">-->
                <!--        <input type="file" name="csv_file" class="form-control" required>-->
                <!--    </div>-->
                <!--    <div class="text-center">-->
                <!--        <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0 temp_disabled" id="loginBtn" onclick="_run(this)" data-el="fg" data-form="trader-login-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="traderLoginCallBack" data-btnid="loginBtn">Sign In</button>-->
                <!--    </div>-->
                <!--</form>-->
                                        <form action="{{ route('migration') }}" method="POST" enctype="multipart/form-data" id="migrationForm">
                                            @csrf
                                            <label for="csv_file">CSV File</label>
                                            <div class="mb-3">
                                                <input type="file" name="csv_file" class="form-control" required>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn bg-gradient-primary w-100 mt-4 mb-0">Upload</button>
                                            </div>
                                        </form>
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
<script>
    $(document).on('click', '.temp_disabled', function() {
        $('.temp_disabled').prop('disabled', true);
        setTimeout(function() {
            $('.temp_disabled').prop('disabled', false);
        }, 3000);
    });
    // trigger login when press enter
    document.onkeydown = function(evt) {
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if (keyCode == 13) {
            $('#loginBtn').trigger('click');
        }
    }
    // // trader login
    // function traderLoginCallBack(data) {
    //     $('#loginBtn').prop('disabled', true);
    //     if (data.status == true) {
    //         console.log(data);
    //     }
    //     setTimeout(function() {
    //         $('#loginBtn').prop('disabled', false);
    //     }, 3000);
    // }

    $(document).ready(function() {
            $("#login").click(function() {
                $("#migrationForm").submit(function(e) {
                    $("#msg").html("");
                    $(".la-anim-10").addClass("la-animate");
                    var postData = $(this).serializeArray();
                    var formURL = $(this).attr("action");
                    $.ajax({
                        url: formURL,
                        type: "POST",
                        data: postData,
                        success: function(data, textStatus, jqXHR) {
                            console.log(data);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $("#msg").html('<pre><code class="prettyprint">AJAX Request Failed<br/> textStatus=' + textStatus + ', errorThrown=' + errorThrown + '</code></pre>');
                        }
                    });
                    e.preventDefault(); //STOP default action
                    e.unbind();
                });

                $("#migrationForm").submit(); //SUBMIT FORM
            });

        });
    
</script>
@stop