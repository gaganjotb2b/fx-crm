@extends('layouts.trader-layout')
@section('title','B2B Deposit')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css')}}">
<link rel="stylesheet" href="{{asset('common-css/select-2-component.css')}}">
<style>
    .error-msg {
        color: red;
    }

    #b-icon-dollar {
        font-size: 3rem;
    }

    .dark-version .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #cfcfd2;
        line-height: 28px;
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
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page"><span class="text-infos">Now </span>Payments</li>
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
                            <form method="post" action="{{route('user.deposit.nowpayments.submit')}}" id="form-b2b">
                                @csrf
                                <!--single form panel-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">Crypto Currency Deposit (<span class="text-info">NOW</span>Payments)</h5>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row mt-3">
                                            <div class="col-12 col-sm-4 d-none">
                                                <div class="avatar avatar-xxl position-relative">
                                                    <img id="platform-logo" src="{{asset('comon-icon/b2b.png')}}" class="border-radius-md img-thumbnail" alt="team-2">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 mt-4 mt-sm-0 text-start mx-auto">
                                                <!-- select  deposit option-->
                                                @if(\App\Services\systems\TransactionSettings::is_account_deposit() == true)
                                                <div class="form-group al-error-solve">
                                                    <label for="deposit-options">Deposit Options</label>
                                                    <select name="deposit_option" id="deposit-options" class="form-select form-control">
                                                        <option value="account">Trading Account deposit</option>
                                                        <option value="wallet" selected>Wallet deposit</option>
                                                    </select>
                                                </div>
                                                <!-- Account Number -->
                                                <div class="form-group account_number d-none al-error-solve" id="trading-accounts" title="Trading account number">
                                                    <label for="account_number">Trading account</label>
                                                    <select class="select2 form-select" id="account_number" name="trading_account">
                                                        @foreach($trading_accounts as $value)
                                                        <option value="{{ encrypt($value->id)}}">{{ $value->account_number}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endif
                                                <!-- crypto currency -->
                                                <div class="form-group al-error-solve">
                                                    <label for="crypto-currency">Currency</label>
                                                    <select id="crypto-currency" class="form-control form-select text-white" name="pay_currency">
                                                        <option value="">Select A Currency</option>
                                                        <option value="USDTTRC20">USDTTRC20</option>
                                                    </select>
                                                </div>
                                                <input type="hidden" name="currency" id="hidden-currency">
                                                <!-- amount usd -->
                                                <div class="form-group">
                                                    <label for="user-email">Amount(USD)</label>
                                                    <input type="text" name="amount_usd" class="form-input form-control" value="" placeholder="0" id="usd-amount">
                                                </div>
                                                <div class="form-group">
                                                    <label for="crypto-amount">Total Transfer( <span id="label-crypto">Crypto</span> )</label>
                                                    <input type="text" name="amount_crypto" class="form-control form-input" id="crypto-amount">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row  mt-4">
                                            <div class="col-12 col-sm-6 mt-4 mt-sm-0 text-start mx-auto">
                                                <button class="btn bg-gradient-primary ms-auto mb-0 float-end" type="button" title="Next" id="btn-b2b" style="width: 200px" data-btnid="btn-b2b" data-callback="b2b_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="form-b2b" data-el="fg" onclick="_run(this)">Submit</button>
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
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
@stop
@section('page-js')
<!-- <script src="{{asset('trader-assets/assets/js/plugins/multistep-form.js')}}"></script> -->
<script src="{{asset('trader-assets/assets/js/scripts/pages/wallet-to-account.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/scripts/pages/get-data-on-input.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/scripts/pages/file-upload-with-form.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js')}}"></script>
<script>
    // submit form b2b pay
    function b2b_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'NOWPayments deposit');
            window.location = data.redirect_to;
        } else {
            notify('error', data.message, 'NOWPayments deposit');
        }
        $.validator("form-b2b", data.errors);
    }
    // select2
    $(function() {
        $("#crypto-currency").select2({
            templateResult: formatOption,
        });

        function formatOption(option) {
            var $option = $(
                '<div><strong>' + option.text + '</strong></div><div>' + option.title + '</div>'
            );
            return $option;
        };
    });
    // change lavel for total transfer
    $(document).on('change', "#crypto-currency", function() {
        let currency = $(this).val();
        $("#label-crypto").text(currency);
        $("#usd-amount").val('');
        $("#crypto-amount").val('');
        $("#hidden-currency").val($(this).find("option:selected").data('iso'));

    });
    // convert currency
    // amount convert usd to crypto
    function currency_convert(convart_from, convart_to, input_id, convart_to_id) {
        $(document).on("blur keyup", "#" + input_id, function() {
            let amount = $(this).val();
            let crypto_currency = $("#crypto-currency").val();
            let crypto_name = $("#crypto-name").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/user/deposit/nowpayments/estimate-price',
                method: 'get',
                dataType: 'JSON',
                data: {
                    amount: amount,
                    convart_from: convart_from,
                    currency: $('#crypto-currency').val(),
                },
                success: function(data) {
                    if (convart_from === 'usd') {
                        $("#usd-amount").val(data.result.estimated_amount);
                    } else {
                        $("#crypto-amount").val(data.result.estimated_amount);
                    }

                }
            })
        })
    }
    // function call for convart usd to crypto
    currency_convert("usd", "usd-amount", "crypto-amount");
    // amount convert crypto to usd
    currency_convert("crypto", "crypto-amount", "usd-amount");


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