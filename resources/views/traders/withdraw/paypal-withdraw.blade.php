@extends('layouts.trader-layout')
@section('title','PayPal Withdraw')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css')}}">
<style>
    .error-msg {
        color: red;
    }

    #b-icon-dollar {
        font-size: 3rem;
    }
</style>
@stop
@section('bread_crumb')
@php use App\Services\BankService; @endphp
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm">
            <a class="opacity-3 text-dark" href="javascript:;">
                <svg width="12px" height="12px" class="mb-1" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <title>shop </title>
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-1716.000000, -439.000000)" fill="#252f40" fill-rule="nonzero">
                            <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(0.000000, 148.000000)">
                                    <path d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"></path>
                                    <path d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"></path>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </a>
        </li>
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">{{__('page.withdraw')}}</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">PayPal</li>
    </ol>
    <h6 class="font-weight-bolder mb-0">{{__('page.trader-area')}}</h6>
</nav>
@stop
@section('content')
<div class="container-fluid py-4">
    <div class="row custom-height-con">
        <div class="col-12 text-center">
            <div class="multisteps-form mb-5">
                <!--form panels-->
                <div class="row">
                    <div class="col-12 col-lg-8 m-auto">
                        <div class="multisteps-form__form">
                            <!-- <form class="form-demo" action="{{route('user.withdraw.bank-withdraw')}}" method="post" id="bank-deposit-form"> -->
                            <form method="post" action="{{route('withdraw.paypal.request')}}" id="withdraw_method">
                                @csrf
                                <!--single form panel-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">Balance Withdraw With PayPal</h5>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row mt-3">
                                            <div class="col-12 col-sm-4">
                                                <div class="avatar avatar-xxl position-relative">
                                                    <img id="platform-logo" src="{{asset('comon-icon/paypal.png')}}" class="border-radius-md img-thumbnail" alt="team-2">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 mt-4 mt-sm-0 text-start">
                                                <!-- check withdraw permited or not -->
                                                @if(\App\Services\Trader\FinanceService::check_op('withdraw'))
                                                <!-- email -->
                                                <div class="form-group">
                                                    <label for="user-email">Email</label>
                                                    <input type="text" name="user_email" id="user-email" class="form-input form-control" disabled value="{{$email}}" placeholder="Enter your paypal email">
                                                </div>
                                                <!-- select  withdraw option-->
                                                @if(\App\Services\systems\TransactionSettings::is_account_withdraw() == true)
                                                <div class="form-group">
                                                    <label for="withdraw-options">Deposit Options</label>
                                                    <select name="withdraw_option" id="withdraw-options" class="form-select form-control">
                                                        <option value="account" id="account_withdraw">Account withdraw</option>
                                                        <option value="wallet" selected id="wallet_withdraw">Wallet withdraw</option>
                                                    </select>
                                                </div>
                                                @endif
                                                <!-- Account Number -->
                                                <div class="form-group account_number d-none">
                                                    <label for="account_number">Account Number</label>
                                                    <select class="select2 form-select" id="account_number" name="account_number">
                                                        @foreach($bank_accounts as $bank_account)
                                                        <option value="{{ $bank_account->bank_ac_number}}">{{ $bank_account->bank_ac_number}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <!-- amount usd -->
                                                <div class="form-group">
                                                    <label for="amount withdraw">Amount(USD)</label>
                                                    <input type="text" name="user_amount" id="user-amount" class="form-input form-control" value="" placeholder="0">
                                                </div>
                                                @else
                                                <!-- warning withdraw operation -->
                                                <div class="col-8 mx-auto">
                                                    <div class="alert alert-warning" role="alert">
                                                        <strong>Warning!</strong> You are not permited to withdraw! Please contact with <strong> {{config('app.name')}} </strong> Support.
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row  mt-4">
                                            <div class="col-12 col-sm-4">

                                            </div>
                                            <div class="col-12 col-sm-8 mt-4 mt-sm-0 text-start">
                                                <div class="col-6 mx-auto">
                                                    <!-- Replace "test" with your own sandbox Business account app client ID -->
                                                    <div id=""></div>
                                                    <!-- Set up a container element for the button -->
                                                    @if(\App\Services\Trader\FinanceService::check_op('withdraw'))
                                                    <button type="button" data-label="Submit Request" id="btn-submit-request-final" data-btnid="btn-submit-request-final" data-callback="paypal_withdraw_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="withdraw_method" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto ms-3 float-end" style="width:200px">{{ __('page.submit-request') }}</button>
                                                    <!-- <button class="btn bg-gradient-primary ms-auto mb-0 float-end" type="button" title="Next" id="btn-js-next-2" style="width: 200px">{{__('page.next')}}</button> -->
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!--single form panel-->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- include footer -->
    @include('layouts.footer')
</div>
@stop
@section('corejs')
<script src="{{asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js')}}"></script>
@stop
@section('page-js')
<!-- <script src="{{asset('trader-assets/assets/js/plugins/multistep-form.js')}}"></script> -->
<script src="{{asset('trader-assets/assets/js/scripts/pages/wallet-to-account.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/scripts/pages/get-data-on-input.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/scripts/pages/file-upload-with-form.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js')}}"></script>
<script>
    function paypal_withdraw_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'PayPal Withdraw');
            $('#user-amount').val('');
        } else {
            notify('error', data.message, 'PayPal Withdraw');
            $.validator("withdraw_method", data.errors);
        }

    }
    //Account Transaction Setting
    $(document).on("click", "#account_withdraw", function() {
        $('.account_number').removeClass('d-none');
    });
    $(document).on("click", "#wallet_withdraw", function() {
        $('.account_number').addClass('d-none');
    });
</script>
@stop