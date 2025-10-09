@extends('layouts.trader-auth')
@section('title','Signup Success')
@section('style')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<style>
    .progress-bar-success {
        background-color: var(--custom-primary);
    }

    .btn-primary {
        color: #fff;
        background-color: var(--custom-primary);
        border-color: #2e6da4;
    }

    .btn-primary.active.focus,
    .btn-primary.active:focus,
    .btn-primary.active:hover,
    .btn-primary:active.focus,
    .btn-primary:active:focus,
    .btn-primary:active:hover,
    .open>.dropdown-toggle.btn-primary.focus,
    .open>.dropdown-toggle.btn-primary:focus,
    .open>.dropdown-toggle.btn-primary:hover {
        color: #fff;
        background-color: #204d74;
        border-color: #43b2a8;
    }

    .btn:hover:not(.btn-icon-only) {
        box-shadow: 0 3px 5px -1px rgb(67, 178, 168), 0 2px 3px -1px rgb(67, 178, 168);
        transform: scale(1.02);
    }

    .btn-primary:hover {
        color: #fff;
        background-color: #69d7cd;
        border-color: #59b7af;
    }
    .brand-logo {
		display: none;
	}
</style>
@endsection
@section('content')
<style>
    .progress-bar {
        width: 10%;
        transition: all 0.3s ease-in 0s;
        height: 18px;
    }
</style>



<div class="row">

    <div class="col-8 mx-auto my-auto text-center">


        <img src="{{ get_admin_logo() }}" alt="{{ config('app.name') }}" class="img-fluid" height="100"  style="max-width:15%">
        <br><br>



        <div class="ptext">
            <span class="text-primary">Congratulations !!! </span>
            You Have Registered On {{ config('app.name') }}  Successfully Please Check Your E-Mail For Your Account Details.
        </div>
        <br><br>

        <div class="progress">
            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:40%">
                40%
            </div>
        </div>

        <a href="{{route('login')}}" class="btn btn-primary py-4" style="width: 200px;">Login Now</a>
        @php
        $user_id = request()->hash;
        $user_id = decrypt($user_id);
        @endphp
    </div>
    @stop
    @section('page-js')
    <!-- BEGIN: Page JS-->
    <script>
        function getBaseUrl() {
            var re = new RegExp(/^.*\//);
            return re.exec(window.location.href);
        }

        var steps = ['30%', '60%', '100%'];
        var call = 0;

        function mt4(c) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            call++;
            $(".progress-bar").css("width", steps[call]);
            $(".progress-bar").html(steps[call]);
            $.ajax({
                url: "/trader/demo/registration",
                type: "post",
                data: {
                    op: 'meta-account',
                    user_id: '<?php echo ($user_id) ?>'
                },
                success: function(data) {
                    if (data.status == 1) {
                        Show();
                        notify('success', data.message, 'Profile Activation')
                    } else if (data.status == 0) {
                        mt4(call);
                    } else {
                        Show();
                        notify('warning', data.message, 'Profile Activation')
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }

        function Show() {
            $(".progress-bar").css("width", "100%");
            $(".progress-bar").html("100%");
            setTimeout(function() {
                $(".progress").hide();
                $("#text").show();
            }, 1000);
        }
        mt4(call);
    </script>
    <!-- END: Page JS-->

    @stop