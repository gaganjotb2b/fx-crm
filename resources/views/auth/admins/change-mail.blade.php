@extends('layouts.trader-auth')
@section('title', 'Email Changing')
@section('style')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .error-msg {
            color: red;
            float: left;
            padding: 5px 0;
        }
    </style>

@endsection
@section('content')

    <div class="row">
        <div class="col-8 mx-auto my-auto text-center">
            <div class="card card-plain mt-5">
                <img src="{{ get_admin_logo() }}" class="img-fluid" height="100" alt="brand-logo">
                <br><br>
                <div class="ptext">
                    <span class="text-primary">Email Changing ! </span>
                    Enter your New email and current password to Change Email Address
                </div>
                <br><br>

                <div class="card-body">
                    <!-- login form -->
                    <form action="{{ route('admin.change-mail-req') }}" method="POST" id="change-from">
                        @csrf
                        {{-- <label>New Email</label> --}}
                        <input type="hidden" name="op" value="change_email">
                        <input type="hidden" name="user_id" value="{{ request()->hash }}">
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control email" placeholder="New Email"
                                aria-label="Email" value="" required="">
                        </div>
                        {{-- <label>Current Password</label> --}}
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control password"
                                placeholder="Current Password" aria-label="Password" value="" required="">
                        </div>

                        <div class="text-center">
                            <button type="button" class="btn bg-gradient-primary w-100 mt-4 mb-0" id="loginBtn"
                                onclick="_run(this)" data-el="fg" data-form="change-from"
                                data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                data-callback="email_change_callBack" data-btnid="loginBtn">Change</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>


    @stop
    @section('page-js')
        <!-- BEGIN: Page JS-->
        <script>
            function getBaseUrl() {
                var re = new RegExp(/^.*\//);
                return re.exec(window.location.href);
            }

            function email_change_callBack(data) {
                if (data.status) {
                    notify('success', data.message, 'Email Changing')
                } else {
                    notify('error', data.message, 'Email Changing')
                }
                console.log(data);
                $.validator("change-from", data.errors);
            }
        </script>
        <!-- END: Page JS-->

    @stop
