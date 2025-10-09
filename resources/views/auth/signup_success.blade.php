@extends('layouts.trader-auth')
@section('title','Signup Success')
@section('content')
<style>
    .cbutton {
        border: 1px solid !important;
        border-radius: 8px;
        display: inline-block;
        padding: 20px;
        width: 200px;
        text-decoration: none;
    }

    .pbutton {
        border: 1px solid !important;
        border-radius: 8px;
        display: inline-block;
        padding: 20px;
        width: 200px;
    }

    .icon {
        font-size: 65px !important;
    }
</style>



<div class="row">

    <div class="col-lg-12 my-auto text-center">

        <center>
            <img src="{{ get_user_logo() }}" alt="{{ config('app.name') }}" class="img-fluid" style="height:50px; margin-top:100px;">
            <br><br>
        </center>

        <center>
            <div class="ptext">
                <span class="text-primary">Congratulations !!! </span>
                You Have Registered On {{ config('app.name') }} Successfully Please Check Your E-Mail For Your Account Details.
            </div>
            <br><br>
        </center>

        <center>
            <a href="/">
                <div class="pbutton s-trader-cabinate text-primary"> <i class="fa fa-support icon cabinet-icon text-primary"> </i> <br><br>Client Login</div>
            </a>

            <a href="/">
                <div class="pbutton s-go-home text-primary"> <i class="fa fa-home icon s-go-home-icon text-primary"></i> <br><br> Go Home </div>
            </a>
            <br><br><br>

            @if ($platform != null)


            <p class="cbutton  s-download-mt4 text-primary">
                <a class="default s-mt4-text text-primary" href="{{  $downloadLink }}" target="_blank">
                    <strong style="text-transform: uppercase">DOWNLOAD {{ $platform }}</strong>
                </a>
            </p>
            <br><br><br><br><br>
            @endif

        </center>
    </div>



    @stop
    @section('page-js')
    <!-- BEGIN: Page JS-->

    <!-- END: Page JS-->

    @stop