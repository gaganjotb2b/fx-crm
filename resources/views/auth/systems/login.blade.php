@extends('layouts.admin-auth')
@section('title','System Login')
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
                    <div class="logo-background">
                        <img src="{{ get_admin_logo() }}" height="40" alt="{{ config('app.name') }}">
                    </div>
                </a>
                <!-- /Brand logo-->
                <h2 class="card-title fw-bold mb-1">Welcome to {{ strtoupper(config('app.name')) }}! </h2>
                <p class="card-text mb-2">Please sign-in to your account and start the adventure</p>
                <div id="alert-message" class="alert alert-danger p-1 d-none" role="alert">
                </div>

                <form id="system-login-form" class="auth-login-form mt-2" action="{{route('system.login.action')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-1">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" id="email" type="text" name="email" placeholder="john@example.com" aria-describedby="email" autofocus="" tabindex="1" />
                    </div>
                    <div class="mb-1">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="password">Password</label><a href="#"><small>Forgot Password?</small></a>
                        </div>
                        <div class="input-group input-group-merge form-password-toggle">
                            <input class="form-control form-control-merge" id="password" type="password" name="password" placeholder="············" aria-describedby="password" tabindex="2" /><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary w-100" id="loginBtn" onclick="_run(this)" data-el="fg" data-form="system-login-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="systemLoginCallBack" data-btnid="loginBtn">Sign In</button>
                </form>
            </div>
        </div>
        <!-- /Login-->
    </div>
</div>
@stop
@section('page-js')
<!-- BEGIN: Page JS-->
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<!-- <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/system-login.js') }}"></script> -->
<!-- END: Page JS-->
<script>
    // trigger login when press enter
    document.onkeydown = function(evt) {
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if (keyCode == 13) {
            $('#loginBtn').trigger('click');
        }
    }

    // system login
    function systemLoginCallBack(data) {
        $('#loginBtn').prop('disabled', false);
        if (data.status == true) {
            notify('success', data.message, 'System Login');
            setTimeout(function() {
                window.location.href = "/system/dashboard";
            }, 1000 * 2);
        } else {
            if (data.message != null) {
                notify('error', data.message, 'System Login');
                $.validator("admin-login-form", data.errors);
            } else {
                notify('error', 'Something Went Wrong!', 'System Login');
                $.validator("admin-login-form", data.errors);
            }
        }
    }
</script>
@stop