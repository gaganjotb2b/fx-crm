@extends('layouts.trader-layout')
@section('title','Help2Pay Deposit')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<style>
    .error-msg {
        color: red;
    }

    #b-icon-dollar {
        font-size: 3rem;
    }

    .select2-#D1B970er--classic.select2-container--focus,
    .select2-container--default.select2-container--focus {
        outline: 0;
    }

    .select2-container {
        width: 100% !important;
        margin: 0;
        display: inline-block;
        position: relative;
        vertical-align: middle;
        box-sizing: border-box;
    }

    .select2-container--classic .select2-selection--single,
    .select2-container--default .select2-selection--single {
        min-height: 2.714rem;
        padding: 5px;
        border: 1px solid #d8d6de;
    }

    .select2-container--classic .select2-selection--single .select2-selection__arrow b,
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23d8d6de' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-down'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-size: 18px 14px, 18px 14px;
        background-repeat: no-repeat;
        height: 1rem;
        padding-right: 1.5rem;
        margin-left: 0;
        margin-top: 0;
        left: -8px;
        border-style: none;
    }

    .dark-layout .select2-container .select2-selection__arrow b {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23b4b7bd' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-down'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    }

    .custom-height-con {
        min-height: calc(100vh - 0px) !important;
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
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">{{__('page.deposit')}}</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">{{__('finance.Bank Deposit')}}</li>
    </ol>
    <h6 class="font-weight-bolder mb-0">{{__('page.trader-area')}}</h6>
</nav>
@stop
@section('content')
<div class="container-fluid py-4">
    <div class="row custom-height-con">
        <div class="col-12 text-center">
            <h3 class="mt-5">Help2pay Deposit</h3>
            <h5 class="text-secondary font-weight-normal">{{__('page.we_need_your_information_becuase_you_make_a_transaction')}}.</h5>
            <div class="multisteps-form mb-5">
                <!--form panels-->
                <div class="row">
                    <div class="col-12 col-lg-8 m-auto">
                        <div class="multisteps-form__form">
                            <!--single form panel-->
                            <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                <div class="multisteps-form__content">
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-4">
                                            <div class="avatar avatar-xxl position-relative">
                                                <img id="platform-logo" src="{{asset('trader-assets/assets/img/help2.png')}}" class="border-radius-md img-thumbnail" alt="team-2">
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 mt-4 mt-sm-0 text-start pb-5">
                                            @if(\App\Services\AllFunctionService::kyc_required((auth()->user()->id),'deposit') == false )
                                            <form action="{{route('user.deposit.help2pay-set-form-value')}}" method="post" id="set-form-value">
                                                @csrf
                                                <!-- select  deposit option-->
                                                @if(\App\Services\systems\TransactionSettings::is_account_deposit() == true)
                                                <div class="form-group">
                                                    <label for="deposit-options">Deposit Options</label>
                                                    <select name="deposit_option" id="deposit-options" class="form-select form-control">
                                                        <option value="account">Trading Account deposit</option>
                                                        <option value="wallet" selected>Wallet deposit</option>
                                                    </select>
                                                </div>
                                                <!-- Account Number -->
                                                <div class="form-group account_number d-none" id="trading-accounts" title="Trading account number">
                                                    <label for="account_number">Trading account</label>
                                                    <select class="select2 form-select" id="account_number" name="trading_account">
                                                        @foreach($trading_accounts as $value)
                                                        <option value="{{ encrypt($value->id)}}">{{ $value->account_number}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endif
                                                <!-- country -->
                                                <div class="form-group" id="not-modal">
                                                    <label for="location">Location</label>
                                                    <select name="location" id="location" class="form-select form-control select2 text-start">
                                                        <option value="">Choose your location</option>
                                                    </select>
                                                </div>
                                                <div class="form-group" id="bank-parent">
                                                    <label for="bank">Bank</label>
                                                    <input type="hidden" name="bank" value="" id="key-bank">
                                                    <select name="currency" id="bank" class="form-select form-control select2">
                                                        <!-- <option value="">Choose Bank</option> -->
                                                        <!-- QRIS -->
                                                        <option value="IDR" data-bank="DANAQRIS">DANA QRIS</option>
                                                        <option value="IDR" data-bank="GOPAYQRIS">GO PAY QRIS</option>
                                                        <option value="IDR" data-bank="ISTB">IDR Semi Deposit</option>
                                                        <option value="IDR" data-bank="LINKAJAQRIS">LINK AJA QRIS</option>
                                                        <option value="IDR" data-bank="OVOQRIS">OVO QRIS</option>
                                                        <option value="IDR" data-bank="QRIS">QRIS</option>
                                                        <option value="IDR" data-bank="SHOPEEQRIS">SHOPEE PAY QRIS</option>

                                                        <option value="IDR" data-bank="BCA">Bank Central Asia</option>
                                                        <option value="IDR" data-bank="BNI">Bank Negara Indonesia</option>
                                                        <option value="IDR" data-bank="BRI">Bank Rakyat Indonesia</option>
                                                        <option value="IDR" data-bank="MDR">Mandiri Bank</option>
                                                        <option value="IDR" data-bank="CIMBN">CIMB Niaga</option>
                                                        <!-- MYR -->
                                                        <option value="MYR" data-bank="AFF">Affin Bank</option>
                                                        <option value="MYR" data-bank="ALB">Alliance Bank Malaysia Berhad</option>
                                                        <option value="MYR" data-bank="AMB">AmBank Group</option>
                                                        <option value="MYR" data-bank="BIMB">Bank Islam Malaysia Berha</option>
                                                        <option value="MYR" data-bank="BSN">Bank Simpanan Nasional</option>
                                                        <option value="MYR" data-bank="CIMB">CIMB Bank Berhad</option>
                                                        <option value="MYR" data-bank="HLB">Hong Leong Bank Berhad</option>
                                                        <option value="MYR" data-bank="HSBC">HSBC Bank (Malaysia) Berhad</option>
                                                        <option value="MYR" data-bank="OCBC">OCBC Bank (Malaysia) Berhad</option>
                                                        <option value="MYR" data-bank="MBB">Maybank Berhad</option>
                                                        <option value="MYR" data-bank="PBB">Public Bank Berhad</option>
                                                        <option value="MYR" data-bank="RHB">RHB Banking Group</option>
                                                        <option value="MYR" data-bank="UOB">United Overseas Bank (Malaysia) Bhd</option>


                                                        <option value="THB" data-bank="KKR">Karsikorn Bank (K-Bank)</option>
                                                        <option value="THB" data-bank="BBL">Bangkok Bank</option>
                                                        <option value="THB" data-bank="SCB">Siam Commercial Bank</option>
                                                        <option value="THB" data-bank="KTB">Krung Thai Bank</option>
                                                        <option value="THB" data-bank="BOA">Bank of Ayudhya (Krungsri)</option>
                                                        <option value="THB" data-bank="CIMBT">CIMB Thai</option>
                                                        <option value="THB" data-bank="KNK">Kiatnakin Bank</option>

                                                        <option value="VND" data-bank="TCB">Techcombank</option>
                                                        <option value="VND" data-bank="VCB">Vietcombank</option>
                                                        <option value="VND" data-bank="ACB">Asia Commercial Bank</option>
                                                        <option value="VND" data-bank="DAB">DongA Bank</option>
                                                        <option value="VND" data-bank="VTB">Vietinbank</option>
                                                        <option value="VND" data-bank="BIDV">Bank for Investment and Development of Vietnam</option>
                                                        <option value="VND" data-bank="EXIM">Eximbank Vietnam</option>

                                                        <!-- <option value="INR" data-bank="AXIS">AXIS BanK</option>
                                                        <option value="INR" data-bank="HDFC">HDFC Bank</option> -->
                                                        <!--  -->
                                                        <option value="PHP" data-bank="BDO">Banco de Oro</option>
                                                        <option value="PHP" data-bank="BPI">Bank of the Philippine Islands</option>
                                                        <option value="PHP" data-bank="LBP">Land Bank of the Philippines</option>
                                                        <option value="PHP" data-bank="MTB">MetroBank</option>
                                                        <option value="PHP" data-bank="RCBC">Rizal Commercial Banking Corporation</option>
                                                        <option value="PHP" data-bank="SBC">Security Bank Corporation</option>


                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="local-currency">Amount( <span id="local-currency-label"></span> )</label>
                                                    <input type="text" id="local-currency" class="form-control" name="local_currency" value="0" placeholder="0" style="color: black;">
                                                </div>
                                                <div class="form-group">
                                                    <label for="input-amount">Amount (USD)</label>
                                                    <input type="text" id="input-amount" name="amount" placeholder="0" class="form-control" style="color: black;">
                                                </div>
                                                <div class="form-group">
                                                    <button type="button" data-label="Submit Request" id="btn-submit-request" data-btnid="btn-submit-request" data-callback="help2Callback" data-loading="<i class='fa-spinner fas fa-circle-notch'></i>" data-form="set-form-value" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto mb-0 float-end">{{__('page.submit-request')}}</button>
                                                </div>
                                            </form>
                                            <!-- <form action="https://api.testingzone88.com/MerchantTransfer" method="post" id="api-form"> -->
                                            <form action="https://api.safepaymentapp.com/MerchantTransfer" method="post" id="api-form">
                                                @csrf
                                                @php $customer = auth()->user()->email @endphp
                                                <input type="hidden" name="Merchant" value="{{($help2pay)?$help2pay->merchent_code:''}}" />
                                                <input type="hidden" name="Currency" value="IDR" id="hidden-currency" />
                                                <input type="hidden" name="Customer" value="{{$customer}}" />
                                                <input id="reference" type="hidden" name="Reference" value="" />
                                                <input id="key" type="hidden" name="Key" value="" />
                                                <input id="amount" type="hidden" name="Amount" value="" />
                                                <input type="hidden" name="Note" value="Trader Deposit {{request()->ip()}}" />
                                                <input id="date-time" type="hidden" name="Datetime" value="" />
                                                <input type="hidden" name="FrontURI" value="{{route('user.deposit.help2pay.client')}}" />
                                                <input type="hidden" name="BackURI" value="{{route('user.deposit.help2pay.client')}}" />
                                                <input type="hidden" name="Language" value="en-us" />
                                                <input type="hidden" name="Bank" value="BCA" id="hidden-bank" />
                                                <input type="hidden" name="ClientIP" value="{{request()->ip()}}" />
                                                <input type="hidden" name="CompanyName" value="{{config('app.name')}}" />
                                                <button class="btn bg-gradient-primary ms-auto mb-0 float-end visually-hidden" type="button" title="Next" id="btn-js-next-2" style="width: 200px">{{__('page.next')}}</button>
                                            </form>
                                            @else
                                            <div class="alert alert-danger text-white" role="alert">
                                                <strong>Warning!</strong> KYC Verification Required for Deposit
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--single form panel-->
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
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
@stop
@section('page-js')
<script src="{{asset('trader-assets/assets/js/plugins/multistep-form.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/scripts/pages/wallet-to-account.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/scripts/pages/get-data-on-input.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/scripts/pages/file-upload-with-form.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('common-js/select2-get-country.js') }}"></script>
<script>
    // location
    $('#location').get_country({
        modal_id: "#not-modal",
        url: '/search/country/with-name'
    });
    // onchange location
    $(document).on('change', "#location", function() {
        let $location = $(this).val();
        $("#bank").html('').trigger('change');
        $("#bank").get_select2({
            modal_id: "#bank-parent",
            url: '/search/bank',
            data: {
                country: $location,
            }
        });

    });
    // disable submit buttons
    $(document).on('click', "#btn-submit-request", function() {
        $(this).prop('disabled', true);
    })
    // help2 pay create callbak
    function help2Callback(data) {

        if (data.status) {
            $("#date-time").val(data.datetime);
            $("#amount").val(data.amount);
            $("#key").val(data.key);
            $("#reference").val(data.reference);
            setTimeout(() => {
                $("#api-form").trigger("submit");
            }, 200);
            notify('success', data.message, 'Help2pay Deposit');
            setTimeout(() => {
                $("#btn-submit-request").prop('disabled', false);
            }, 2000);
        } else {
            notify('error', data.message, 'Help2pay Deposit');
            setTimeout(() => {
                $("#btn-submit-request").prop('disabled', false);
            }, 2000);
        }
        $.validator("set-form-value", data.errors);
    }
    $(document).on('change', "#bank", function() {
        // console.log($(this).find(":selected").data('bank'));
        $("#hidden-bank").val($(this).find(":selected").data('bank'));
        $("#hidden-currency").val($(this).val());
        $("#key-currency").val($(this).val());
        $("#local-currency-label").text($(this).val());
        $("#input-amount").val('');
        $("#local-currency").val('');

    })
    $("#input-amount").val('');
    $("#local-currency").val('');
    $("#hidden-bank").val($("#bank").find(":selected").data('bank'));
    $("#hidden-currency").val($("#bank").val());
    $("#key-currency").val($("#bank").val());
    $("#local-currency-label").text($("#bank").val());
    // convert currency
    $(document).on('input', "#local-currency", function() {
        var local_amount = $(this).val();
        var local_currency = $("#bank").val();
        $.ajax({
            url: "/user/deposit/help2pay/currency-convert/local/" + local_currency + "/rate/" + local_amount,
            method: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $("#input-amount").val(data);
                if (local_amount == 0 || local_amount == "") {
                    $("#input-amount").val(0);
                }
            }
        });
        if (local_amount == 0 || local_amount == "") {
            $("#input-amount").val(0);
        }
    })
    // reverse currency
    $(document).on('input', "#input-amount", function() {
        var local_amount = $(this).val();
        var local_currency = $("#bank").val();
        $.ajax({
            url: "/user/deposit/help2pay/currency-convert/usd/" + local_currency + "/rate/" + local_amount,
            method: 'GET',
            dataType: 'JSON',
            success: function(data) {
                if (local_amount == 0 || local_amount == "") {
                    $("#local-currency").val(0);
                }
                $("#local-currency").val(data);
            }
        })
        if (local_amount == 0 || local_amount == "") {
            $("#local-currency").val(0);
        }
    });
    $(document).on("click", "#account_deposit", function() {
        $('.account_number').removeClass('d-none');
    });
    $(document).on("click", "#wallet_deposit", function() {
        $('.account_number').addClass('d-none');
    });
    // when direct account deposit is one
    change_depsoit_options($('#deposit-options').val());
    $(document).on("change", "#deposit-options", function() {
        change_depsoit_options($(this).val());
    });

    function change_depsoit_options($value) {
        if ($value === 'account') {
            $('#trading-accounts').removeClass('d-none');
        } else {
            $('#trading-accounts').addClass('d-none');
        }
    }
</script>
@stop