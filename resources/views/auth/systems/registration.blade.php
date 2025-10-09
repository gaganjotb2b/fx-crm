@extends('layouts.admin-auth')
@section('title','System Registration')
@section('content')
<div class="auth-wrapper auth-cover">
    <div class="auth-inner row m-0">
        <!-- Brand logo-->
        <a class="brand-logo" href="{{route('system.login')}}">
            <img src="{{ asset('uploads/logos/icon.png') }}" height="28" alt="brand-logo">
            <h2 class="brand-text text-primary ms-1 text-uppercase">{{ config('app.name') }}</h2>
        </a>
        <!-- /Brand logo-->
        <!-- Left Text-->
        <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
            <div class="w-100 d-lg-flex align-items-center justify-content-center px-5"><img class="img-fluid" src="{{ asset('admin-assets/app-assets/images/pages/login-v2.svg') }}" alt="Login V2" /></div>
        </div>
        <!-- /Left Text-->
        <!-- Register-->
        <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                <h2 class="card-title fw-bold mb-1">Adventure starts here 🚀</h2>
                <p class="card-text mb-2">Make your app management easy and fun!</p>
                <form action="{{ route('system.registration.action') }}" method="POST" id="reg-form" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-1">
                        <label class="form-label" for="name">Name</label>
                        <input class="form-control @error('name') is-invalid @enderror" id="name" type="text" name="name" value="{{ old('name') }}" placeholder="johndoe" aria-describedby="name" required autofocus="" tabindex="1" />
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" type="text" name="email" value="{{ old('email') }}" placeholder="john@example.com" aria-describedby="email" tabindex="2" required autofocus="" />
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-group input-group-merge form-password-toggle">
                            <input class="form-control form-control-merge" id="password" type="password" name="password" value="{{ old('password') }}" placeholder="············" required aria-describedby="password" tabindex="3" /><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="password-confirm">Password</label>
                        <div class="input-group input-group-merge form-password-toggle">
                            <input id="password-confirm" type="password" class="form-control form-control-merge" name="password_confirmation" required autocomplete="new-password" tabindex="3" /><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                        </div>
                    </div>
                    <div class="mb-1">
                        <div class="form-check">
                            <input class="form-check-input" id="register-privacy-policy" type="checkbox" tabindex="4" />
                            <label class="form-check-label" for="register-privacy-policy">I agree to<a href="#">&nbsp;privacy policy & terms</a></label>
                        </div>
                    </div>
                    <button id="reg-submit" type="submit" class="btn btn-primary w-100" tabindex="5">Sign up</button>
                </form>
                <p class="text-center mt-2"><span>Already have an account?</span><a href="{{ route('system.login') }}"><span>&nbsp;Sign in instead</span></a></p>
                <div class="divider my-2">
                    <div class="divider-text">or</div>
                </div>
                <div class="auth-footer-btn d-flex justify-content-center"><a class="btn btn-facebook" href="#"><i data-feather="facebook"></i></a><a class="btn btn-twitter white" href="#"><i data-feather="twitter"></i></a><a class="btn btn-google" href="#"><i data-feather="mail"></i></a><a class="btn btn-github" href="#"><i data-feather="github"></i></a></div>
            </div>
        </div>
        <!-- /Register-->
    </div>
</div>

<script>

</script>
@stop

@section('page-js')
<!-- BEGIN: Page JS-->
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/system-registration.js') }}"></script>
<!-- END: Page JS-->
@stop