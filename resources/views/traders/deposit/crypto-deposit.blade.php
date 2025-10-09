@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Crypto deposit')
@section('page-css')
    @if (App\Services\systems\VersionControllService::check_version() === 'lite')
        <link id="pagestyle" href="{{ asset('trader-assets/assets/css/soft-ui-dashboard.css?v=1.0.8') }}" rel="stylesheet" />
    @endif
    <link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css') }}">
    <style>
        .error-msg {
            color: red !important;
        }

        #b-icon-dollar {
            font-size: 3rem;
        }
    </style>
@stop
@section('bread_crumb')
    <!-- bread crumb -->
    {!! App\Services\systems\BreadCrumbService::get_trader_breadcrumb() !!}
@stop
<!-- main content -->
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12 text-center">
                <h3 class="mt-5">{{ __('page.crypto_deposit') }}</h3>
                <h5 class="text-secondary font-weight-normal">
                    {{ __('page.we_need_your_information_becuase_you_make_a_transaction') }}</h5>
                <div class="multisteps-form mb-5">
                    <!--progress bar-->
                    <div class="row">
                        <div class="col-12 col-lg-8 mx-auto my-5">
                            <div class="multisteps-form__progress">
                                <!-- progress crypto selection -->
                                <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">
                                    <span>{{ __('page.crypto_selection') }}</span>
                                </button>
                                <!-- progress aq address -->
                                <button class="multisteps-form__progress-btn" type="button" title="QR Address" disabled
                                    id="btn-step-2">
                                    <span>{{ __('page.qr_address') }}</span>
                                </button>
                                <!-- progress status -->
                                <button class="multisteps-form__progress-btn" type="button" title="Order Info" disabled
                                    id="btn-step-3">
                                    <span>{{ __('page.status') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!--form panels-->
                    <div class="row">
                        <div class="col-12 col-lg-8 m-auto">
                            <form class="multisteps-form__form bg-custom-dark-for rounded-3form-demo"
                                action="{{ route('user.deposit.crypto-deposit-request') }}" method="post"
                                id="crypto-deposit-form">
                                <!--single form panel-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active"
                                    data-animation="FadeIn">
                                    <div class="row text-center">
                                        <!-- first step heading and description -->
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">
                                                {{ __('page.lets-start-with-the-basic-information') }}</h5>
                                            <p>{{ __('page.please_read_crarefully_first_and_submit') }} <span
                                                    class="help"><i class="fas fa-help"></i></span></p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content" id="first-wizer-inputs">
                                        <div class="row mt-3">
                                            <!-- first step logo bitcoin -->
                                            <div class="col-12 col-sm-4">
                                                <div class="avatar avatar-xxl position-relative">
                                                    <img id="platform-logo"
                                                        src="{{ asset('trader-assets/assets/img/logos/currency-logo/bitcoin.png') }}"
                                                        class="border-radius-md img-thumbnail" alt="team-2">
                                                </div>
                                            </div>
                                            <!-- first step form -->
                                            <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                                @if (\App\Services\Trader\FinanceService::check_op('deposit'))
                                                    <!-- select  deposit option-->
                                                    @if (\App\Services\systems\TransactionSettings::is_account_deposit() == true)
                                                        <div class="form-group">
                                                            <label for="deposit-options">Deposit Options</label>
                                                            <select name="deposit_option" id="deposit-options"
                                                                class="form-select form-control">
                                                                <option value="account" id="account_deposit">Account deposit
                                                                </option>
                                                                <option value="wallet" selected id="wallet_deposit">Wallet
                                                                    deposit</option>
                                                            </select>
                                                        </div>
                                                    @endif
                                                    <!-- Account Number -->
                                                    <div class="form-group account_number d-none">
                                                        <label for="account_number">Account Number</label>
                                                        <select class="select2 form-select" id="account_number"
                                                            name="account_number">
                                                            @foreach ($bank_accounts as $bank_account)
                                                                <option value="{{ $bank_account->bank_ac_number }}">
                                                                    {{ $bank_account->bank_ac_number }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <!-- check kyc required or not -->
                                                    @if (\App\Services\AllFunctionService::kyc_required(auth()->user()->id, 'deposit') == false)
                                                        @csrf
                                                        <div class="form-group al-error-solve">
                                                            <label
                                                                for="block-chain">{{ __('page.cryptocurrency') }}</label>
                                                            <select
                                                                class="form-control multisteps-form__input select2 btExport"
                                                                id="block-chain" name="block_chain">
                                                                <option value="">{{ __('page.selete_a_crypto') }}
                                                                </option>
                                                                @foreach ($block_chains as $value)
                                                                    <option value="{{ strtoupper($value->block_chain) }}">
                                                                        {{ $value->block_chain }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group al-error-solve">
                                                            <label for="crypto-name">{{ __('page.block_chain') }}</label>
                                                            <select class="form-control multisteps-form__input select2 "
                                                                id="crypto-name" name="instrument">
                                                                <option value="">
                                                                    {{ __('page.select_a_block_chain') }}</option>
                                                            </select>
                                                        </div>
                                                        <!--<div class="form-group">-->
                                                        <!--    <label for="usd-amount">{{ __('page.usd_amount') }}</label>-->
                                                        <!--    <input type="text"-->
                                                        <!--        class="form-control multisteps-form__input" id="usd-amount"-->
                                                        <!--        name="usd_amount">-->
                                                        <!--</div>-->
                                                        <!--<div class="form-group">-->
                                                        <!--    <label-->
                                                        <!--        for="crypto-amount">{{ __('page.crypto_amount') }}</label>-->
                                                        <!--    <input type="text"-->
                                                        <!--        class="form-control multisteps-form__input"-->
                                                        <!--        id="crypto-amount" name="crypto_amount">-->
                                                        <!--</div>-->
                                                    @else
                                                        <!-- warning kyc required -->
                                                        <div class="col-8 mx-auto">
                                                            <div class="alert alert-warning" role="alert">
                                                                <strong>Warning!</strong> KYC Verification Required for
                                                                Deposit
                                                            </div>
                                                        </div>
                                                    @endif
                                                @else
                                                    <!-- warning deposit operation -->
                                                    <div class="col-8 mx-auto">
                                                        <div class="alert alert-warning" role="alert">
                                                            <strong>Warning!</strong> You are not permited to deposit!
                                                            Please contact with <strong> {{ config('app.name') }} </strong>
                                                            Support.
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="button-row d-flex mt-4">
                                            <div class="col-4"></div>
                                            <div class="col-6 mx-auto">
                                                <!-- check kyc required or not -->
                                                @if (\App\Services\Trader\FinanceService::check_op('deposit'))
                                                    @if (\App\Services\AllFunctionService::kyc_required(auth()->user()->id, 'deposit') == false)
                                                        <!-- first step submit button -->
                                                        <button
                                                            class="btn bg-gradient-primary ms-auto mb-0 js-btn-next visually-hidden"
                                                            type="button" title="Next"
                                                            id="btn-js-next">{{ __('page.next') }}</button>
                                                        <button class="btn bg-gradient-primary ms-auto float-end mb-0 mb-3"
                                                            type="button"
                                                            data-loading="<i class='fa-spinner fas fa-circle-notch'></i>"
                                                            data-el="fg" title="Next" id="btn-custom-next"
                                                            style="width: 200px;">{{ __('page.next') }}</button>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="multisteps-form__panel1"
                                    class="card multisteps-form__panel p-3 border-radius-xl bg-white"
                                    data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{ __('page.please_scan_qr') }}</h5>
                                            <p>{{ __('page.give-us-more-details-about-you-what-do-you-enjoy-doing-in-your-spare-time?') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                @php $qr_code = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data='.$crypto_address.'&choe=UTF-8'; @endphp
                                                <img class="img img-fluid img-thumbnail" src="{{ $qr_code }}"
                                                    id="crypto-qr">
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="row">
                                                    <div class="col-2"></div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <div class="input-group input-group-alternative mb-4 d-flex">
                                                                <input class="form-control multisteps-form__input"
                                                                    placeholder="Crypto Address" type="text"
                                                                    id="crypto-address" name="crypto_address">
                                                                <span
                                                                    class="input-group-text position-relative cursor-pointer p-3 border-start"
                                                                    id="copy-address"><i class="fas fa-copy"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-2"></div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-2"></div>
                                                    <div class="col-md-8">
                                                        <div class="form-group text-start">
                                                            <label
                                                                for="transaction-id">{{ __('page.transaction_hash') }}</label>
                                                            <input type="text"
                                                                class="form-control multisteps-form__input"
                                                                id="transaction-id" name="transaction_id">
                                                        </div>
                                                    </div>
                                                    <div class="col-2"></div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-2"></div>
                                                    <div class="col-md-8">
                                                        <div class="form-group text-start">
                                                            <label for="usd-amount">{{ __('page.usd_amount') }}</label>
                                                            <input type="text" class="form-control multisteps-form__input" id="usd-amount" name="usd_amount">
                                                        </div>
                                                        <div class="form-group text-start">
                                                            <label for="crypto-amount">{{ __('page.crypto_amount') }}</label>
                                                            <input type="text" class="form-control multisteps-form__input" id="crypto-amount" name="crypto_amount">
                                                        </div>
                                                    </div>
                                                    <div class="col-2"></div>
                                                </div>
                                            </div>
                                            <div class="button-row d-flex mt-4">
                                                <button class="btn bg-gradient-light mb-0 js-btn-prev" type="button"
                                                    title="Prev">{{ __('page.previous') }}</button>
                                                <button
                                                    class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden"
                                                    type="button" title="Next"
                                                    id="js-btn-next-2">{{ __('page.next') }}</button>
                                                @php
                                                    $disable = '';
                                                    $has_multi_submit = has_multi_submit('crypto-withdraw', 15);
                                                    if ($has_multi_submit) {
                                                        $disable = 'disabled';
                                                    }
                                                @endphp
                                                <button type="button" data-label="Submit Request"
                                                    id="btn-submit-request" data-btnid="btn-submit-request"
                                                    data-callback="crypto_deposit_call_back"
                                                    data-loading="<i class='fa-spinner fas fa-circle-notch'></i>"
                                                    data-form="crypto-deposit-form" data-el="fg" onclick="_run(this)"
                                                    class="btn bg-gradient-primary ms-auto mb-0 w-lg-25"
                                                    data-submit_wait="{{ submit_wait('crypto-withdraw', 15) }}"
                                                    {{ $disable }}>{{ __('page.submit-request') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--single form panel-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white"
                                    data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{ __('page.your_transaction_status') }}</h5>
                                            <p>{{ __('page.your_wallet_to_trading_account') }}.</p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row text-start">
                                            <h5>{{ __('page.last_transaction_status') }}</h5>
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th>{{ __('page.amount') }}</th>
                                                        <th id="last-amount">
                                                            {{ $last_transaction ? $last_transaction->amount : '---' }}
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('page.transaction_hash') }}</th>
                                                        <th id="last-txn-id">
                                                            {{ $last_transaction ? $last_transaction->transaction_id : '---' }}
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('page.status') }}</th>
                                                        <th>
                                                            <span class="badge rounded-pill badge-success badge-sm">{{(($softwareSettings?->crypto_deposit == 'auto') ? 'Approved' : 'Pending')}}</span>
                                                        </th>
                                                        <th class="d-none" id="last-status">
                                                            @if ($last_transaction)
                                                                @if ($last_transaction->approved_status == 'A')
                                                                    @php
                                                                        $status = 'Approved';
                                                                        $badge = 'success';
                                                                    @endphp
                                                                @elseif($last_transaction->approved_status == 'P')
                                                                    @php
                                                                        $status = 'Pending';
                                                                        $badge = 'dark';
                                                                    @endphp
                                                                @else
                                                                    @php
                                                                        $status = 'Decline';
                                                                        $badge = 'warning';
                                                                    @endphp
                                                                @endif
                                                                <span
                                                                    class="badge rounded-pill badge-{{ $badge }} badge-sm">{{ $status }}</span>
                                                            @endif

                                                        </th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="button-row d-flex mt-4 col-12">
                                            <button class="btn bg-gradient-light mb-0 js-btn-prev float-end"
                                                type="button" title="Prev">{{ __('page.previous') }}</button>
                                            <!-- <button class="btn bg-gradient-dark ms-auto mb-0" type="button" title="Send">Send</button> -->
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footer')
    </div>
@stop
@section('corejs')
    <script src="{{ asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js') }}"></script>
@stop
@section('page-js')
    <script src="{{ asset('trader-assets/assets/js/plugins/multistep-form.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/scripts/pages/wallet-to-account.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/scripts/pages/get-data-on-input.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/scripts/pages/custom-validation.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js') }}"></script>
    <script src="{{ asset('/common-js/copy-js.js') }}"></script>
    <script>
        // secret key copy script start
        $(document).on('click', '#copy-address', function() {
            var clipboardText = "";
            clipboardText = $('#crypto-address').val();
            copyToClipboard(clipboardText);
            notify('success', "Copied To Clipboard", 'Crypto Address');

        });

        function copyToClipboard(text) {
            var sampleTextarea = document.createElement("textarea");
            document.body.appendChild(sampleTextarea);
            sampleTextarea.value = text; //save main text in it
            sampleTextarea.select(); //select textarea contenrs
            document.execCommand("copy");
            document.body.removeChild(sampleTextarea);
        }
        // secret key copy script end



        submit_wait("#btn-submit-request");
        $("#crypto-deposit-form").trigger("reset");
        // wizered validation--------
        // validate first wizered
        $(document).on("click", '#btn-custom-next', function() {
            crypto_wizer_valid("crypto-deposit-form", 1)
        });
        // open demo trading account--------------
        function crypto_deposit_call_back(data) {
            $('#btn-submit-request').prop('disabled', true);
            if (data.status == true) {
                notify('success', data.message, 'Crypto Deposit');
                $("#crypto-deposit-form").trigger('reset');
                $("#last-amount").text(data.last_transaction.amount);
                $("#last-txn-id").text(data.last_transaction.transaction_id);
                let status = '';
                if (data.last_transaction.approved_status === 'A') {
                    status = 'Approved';
                } else if (data.last_transaction.approved_status === 'P') {
                    status = 'Pending';
                } else {
                    status = 'Decline';
                }
                // console.log(data.last_transaction.approved_status);
                $("#last-status").find('.badge').addClass('badge-dark').text('Pending');
                $("#js-btn-next-2").trigger("click");
            }
            if (data.status == false) {
                notify('error', data.message, 'Crypto Deposit');
            }
            $.validator("crypto-deposit-form", data.errors);
            submit_wait("#btn-submit-request", data.submit_wait);
        }

        function get_crypto(op, op_id) {
            $(document).on("change", "#" + op_id, function() {
                let request_data = $(this).val();
                // get existing address
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    dataType: 'json',
                    method: 'POST',
                    url: '/user/crypto-instrument',
                    data: {
                        op: op,
                        request_data: request_data
                    },
                    success: function(data) {
                        if (op === 'instrument') {

                            $('#crypto-name').html(data.instrument);
                            $('#crypto-address').val(data.crypto_address);
                            let qr_url =
                                'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' + data
                                .crypto_address + '&choe=UTF-8';
                            $("#crypto-qr").attr('src', qr_url);
                        }
                        if (op === 'address') {
                            $('#crypto-address').val(data.crypto_address);
                            let qr_url =
                                'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' + data
                                .crypto_address + '&choe=UTF-8';
                            $("#crypto-qr").attr('src', qr_url);
                        }
                    }
                });
            })
        }
        get_crypto('instrument', 'block-chain');
        get_crypto('address', 'crypto-name');

        // amount convert usd to crypto
        function currency_convert(convart_from, input_id, convart_to_id) {
            $(document).on("blur keyup", "#" + input_id, function() {
                let usd_amount = $(this).val();
                let crypto_type = $("#block-chain").val();
                let crypto_name = $("#crypto-name").val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/user/crypto-convert',
                    method: 'post',
                    dataType: 'JSON',
                    data: {
                        usd_amount: usd_amount,
                        crypto_name: crypto_name,
                        crypto_type: crypto_type,
                        convart_from: convart_from
                    },
                    success: function(data) {
                        $("#" + convart_to_id).val(data);
                    }
                })
            })
        }
        // function call for convart usd to crypto
        currency_convert("usd", "usd-amount", "crypto-amount");
        // amount convert crypto to usd
        currency_convert("crypto", "crypto-amount", "usd-amount");
        //Account Transaction Setting
        $(document).on("click", "#account_deposit", function() {
            $('.account_number').removeClass('d-none');
        });
        $(document).on("click", "#wallet_deposit", function() {
            $('.account_number').addClass('d-none');
        });
    </script>
@stop
