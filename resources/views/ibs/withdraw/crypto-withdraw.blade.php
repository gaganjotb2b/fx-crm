@extends(App\Services\systems\VersionControllService::get_ib_layout())
@section('title', 'Crypto Withdraw')
@section('page-css')
@if(App\Services\systems\VersionControllService::check_version()==='lite')
<link id="pagestyle" href="{{ asset('trader-assets/assets/css/soft-ui-dashboard.css?v=1.0.8') }}" rel="stylesheet" />
@endif
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css') }}">
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
{!!App\Services\systems\BreadCrumbService::get_ib_breadcrumb()!!}
@stop
@section('content')
@php use App\Services\IBManagementService; @endphp
<div class="container-fluid py-4">
    <div class="row custom-height-con">
        <div class="col-12 text-center">
            <h3 class="mt-5">{{ __('page.crypto_withdraw') }}</h3>
            <h5 class="text-secondary font-weight-normal">
                {{ __('page.we_need_your_information_becuase_you_make_a_transaction') }}.
            </h5>
            <div class="multisteps-form mb-5">
                <!--progress bar-->
                <div class="row">
                    <div class="col-12 col-lg-8 mx-auto my-5">
                        <div class="multisteps-form__progress">
                            <!-- progress crypto address -->
                            <button class="multisteps-form__progress-btn js-active" type="button" title="User Info" disabled>
                                <span>Crypto Address</span>
                            </button>
                            <!-- progress amount -->
                            <button class="multisteps-form__progress-btn" type="button" title="Order Info" disabled>
                                <span>Amount</span>
                            </button>
                            <!-- progress otp verifications -->
                            @if ($otp_settings == true && $user_otp_settings == true)
                            @if ($otp_settings->withdraw == true && $user_otp_settings->withdraw == true)
                            <button class="multisteps-form__progress-btn" type="button" title="Order Info" disabled>
                                <span>Transaction Pin</span>
                            </button>
                            @endif
                            @endif
                            <!-- progress status -->
                            <button class="multisteps-form__progress-btn" type="button" title="Order Info" disabled>
                                <span>Status</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!--form panels-->
                <div class="row">
                    <div class="col-12 col-lg-8 m-auto">
                        <div class="multisteps-form__form">
                            <form class="form-demo" action="{{ route('ib.withdraw.crypto-withdraw') }}" method="post" id="crypto-withdraw-form">
                                <!--single form panel-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">
                                                {{ __('page.lets-start-with-the-basic-information') }}
                                            </h5>
                                            <p>{{ __('page.please_read_crarefully_first_and_submit') }}<span class="help"><i class="fas fa-help"></i></span></p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row mt-3">
                                            <div class="col-12 col-sm-4">
                                                <!-- first step bitcoin logo -->
                                                <div class="avatar avatar-xxl position-relative">
                                                    <img id="platform-logo" src="{{ asset('trader-assets/assets/img/logos/currency-logo/bitcoin.png') }}" class="border-radius-md img-thumbnail" alt="team-2">
                                                </div>
                                            </div>
                                            <!-- check withdraw permited or not -->
                                            @if(\App\Services\Trader\FinanceService::check_op('withdraw'))
                                            @php $kyc_status = auth()->user()->kyc_status; @endphp
                                            @if (!IBManagementService::withdrawStatus())
                                            <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                                <div class="nav-wrapper position-relative end-0">
                                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                        <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                                                        <span class="alert-text">
                                                            <strong>{{ __('page.warning') }}!</strong>
                                                            <span>
                                                                @php
                                                                if ($kyc_status == 2) {
                                                                echo 'Please wait while your kyc approve for crypto withdraw';
                                                                } else {
                                                                echo 'Please first verify your kyc for crypto withdraw';
                                                                }
                                                                @endphp
                                                            </span>!
                                                        </span>
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                                <input type="hidden" name="op" id="op" value="step-1">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="block-chain">{{ __('page.cryptocurrency') }}</label>
                                                    <select class="form-control multisteps-form__input" id="block-chain" name="block_chain">
                                                        <option value="">{{__('page.selete_a_crypto')}}</option>
                                                        @foreach($block_chains as $value)
                                                        <option value="{{strtoupper($value->block_chain)}}">{{$value->block_chain}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="crypto-name">{{ __('page.block_chain') }}</label>
                                                    <select class="form-control multisteps-form__input" id="crypto-name" name="instrument">
                                                        <option value="">
                                                            {{ __('page.select_a_crypto_instrument') }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="crypto-address">{{ __('page.reciever_crypto_address') }}</label>
                                                    <input type="text" class="form-control multisteps-form__input" id="crypto-address" name="crypto_address">
                                                </div>
                                            </div>
                                            @endif
                                            @else
                                            <!-- warning trader to ib operation -->
                                            <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                                <div class="alert alert-warning" role="alert">
                                                    <strong>Warning!</strong> You are not permited to Withdraw! Please contact with <strong> {{config('app.name')}} </strong> Support.
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <!-- hide buttons if kyc not verified -->
                                        @if (IBManagementService::withdrawStatus())
                                        <div class="button-row d-flex mt-4">
                                            <div class="col-4"></div>
                                            <div class="col-6 mx-auto">
                                                <!-- first step submit button -->
                                                <!-- check withdraw permited or not -->
                                                @if(\App\Services\Trader\FinanceService::check_op('withdraw'))
                                                <button type="button" data-label="Next" id="btn-submit-request" data-btnid="btn-submit-request" data-callback="crypto_withdraw_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="crypto-withdraw-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto mb-3 mt-4 float-end" style="width:200px">Next</button>
                                                @endif
                                            </div>
                                            <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="btn-js-next">Next</button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <!--otp transaction-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">Check Your Transaction Carefully.</h5>
                                            <p>Please provide your transaction information after click next.</p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row mt-3">
                                            <div class="col-4">
                                                <div class="avatar avatar-xxl position-relative">
                                                    <img id="platform-logo" src="{{ asset('trader-assets/assets/img/logos/currency-logo/bitcoin.png') }}" class="border-radius-md img-thumbnail" alt="team-2">
                                                </div>
                                            </div>
                                            <div class="col-6 mx-auto text-start">
                                                <div class="form-group">
                                                    <label for="usd-amount">USD Amount</label>
                                                    <input type="text" class="form-control multisteps-form__input" id="usd-amount" name="usd_amount">
                                                </div>
                                                <div class="form-group">
                                                    <label for="crypto-amount">Crypto Amount</label>
                                                    <input type="text" class="form-control multisteps-form__input" id="crypto-amount" name="crypto_amount">
                                                </div>
                                                <!--<div class="form-group">-->
                                                <!--    <label for="transaction-password">Transaction password</label>-->
                                                <!--    <input type="password" class="form-control multisteps-form__input" id="transaction-password" name="transaction_password">-->
                                                <!--</div>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="button-row d-flex mt-4 col-12">
                                            <div class="col-4">&nbsp;</div>
                                            <div class="col-6 mx-auto">
                                                <button type="button" data-label="Next" id="btn-submit-request-2" data-btnid="btn-submit-request-2" data-callback="crypto_withdraw_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="crypto-withdraw-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto ms-3 float-end" style="width:200px">Next</button>
                                                <button class="btn bg-gradient-light mb-0 js-btn-prev float-end me-3" type="button" title="Prev">Previous</button>
                                                <div class="clearfix"></div>
                                            </div>
                                            <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="btn-js-next-2">Next</button>
                                        </div>
                                    </div>
                                </div>
                                <!--otp varification-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{ __('page.confirm-its-you?') }}</h5>
                                            <p>Please check your email and provide transaction pin into this input field.</p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row">
                                            <div class="col-12 col-sm-4">
                                                <div class="avatar avatar-xxl position-relative">
                                                    <img id="platform-logo" src="{{ asset('trader-assets/assets/img/logos/currency-logo/bitcoin.png') }}" class="border-radius-md img-thumbnail" alt="team-2">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                                <div class=" text-center row gx-2 gx-sm-3 custom-otp-boxs border p-6 rounded">
                                                    <input type="hidden" name="otp" id="otp">
                                                    <!--<div class="col">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <input type="text" name="otp_1" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="none">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <!--<div class="col">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <input type="text" name="otp_2" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="none">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <!--<div class="col">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <input type="text" name="otp_3" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="none">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <!--<div class="col">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <input type="text" name="otp_4" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="none">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <!--<div class="col">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <input type="text" name="otp_5" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="none">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <!--<div class="col">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <input type="text" name="otp_6" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="none">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <!--<span class="text-muted text-sm">Haven't received it?-->
                                                    <!--    <button type="button" id="btn-js-next-resend" data-label="Resend Code" class="btn mb-0 text-capitalize" data-loading="<i class='fa-spin fas fa-circle-notch'></i>">Resend-->
                                                    <!--        Code</button>-->
                                                    <!--    <button type="button" data-label="Resend Code" id="btn-resend-code" data-btnid="btn-resend-code" data-callback="open_live_account_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="live-account-form" data-el="fg" onclick="_run(this)" class="btn mb-0 text-capitalize btn-submit-request visually-hidden" data-op="step-2" style="width:200px">Resend-->
                                                    <!--        Code</button>.</span>-->
                                                    
                                                    <div class="form-group">
                                                        <label for="transaction-password">Transaction Pin</label>
                                                        <input type="password" class="form-control multisteps-form__input" id="transaction-password" name="transaction_password">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="button-row d-flex mt-4 col-12">
                                            <div class="col-4"></div>
                                            <div class="col-6 mx-auto">
                                                <button type="button" data-label="Submit Request" id="btn-submit-request-final" data-btnid="btn-submit-request-final" data-callback="crypto_withdraw_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="crypto-withdraw-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto ms-3 float-end" style="width:200px">Submit Request</button>
                                                <button class="btn bg-gradient-light mb-0 js-btn-prev float-end me-3" type="button" title="Prev">Previous</button>
                                            </div>

                                            <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="btn-js-next-3">Next</button>
                                        </div>
                                    </div>
                                </div>
                                <!--withdraw status-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">Your Transaction Status</h5>
                                            <p>{{ __('page.Your crypto withdraw trasaction status here. You can find olny your last crypto withdraw transaction status. You also find your all transaction report in reports area.') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row text-start mt-3">
                                            <div class="col-6 mx-auto">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <th>Amount</th>
                                                            <th id="last-amount">
                                                                {{ $last_transaction ? $last_transaction->amount : '---' }}
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>Transaction ID</th>
                                                            <th id="last-txn-id">
                                                                {{ $last_transaction ? $last_transaction->transaction_id : '---' }}
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>Status</th>
                                                            <th id="last-status">
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
                                                                <span class="badge rounded-pill badge-{{ $badge }} badge-sm">{{ $status }}</span>
                                                                @endif

                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 col-6 mx-auto">
                                        <!-- <button class="btn bg-gradient-light mb-0 js-btn-prev float-end d-none" type="button" title="Prev">Previous</button> -->
                                        <!-- <button class="btn bg-gradient-dark ms-auto mb-0" type="button" title="Send">Send</button> -->
                                        <button class="btn bg-gradient-secondary m-1 float-end reload-button" type="button">Go Back To Home</button>
                                    </div>
                                </div>
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
<script src="{{ asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js') }}"></script>
@stop
@section('page-js')
<script src="{{ asset('trader-assets/assets/js/plugins/multistep-form.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/scripts/pages/wallet-to-account.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/scripts/pages/get-data-on-input.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/scripts/pages/file-upload-with-form.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js') }}"></script>
<script src="{{ asset('common-js/multistep-controller.js') }}"></script>
<script>
    // reload page
    $(document).on('click', '.reload-button', function() {
        location.reload();
    });
    submit_wait("#btn-submit-request");
    $("#crypto-withdraw-form").trigger("reset");
    $("#op").val('step-1');
    // open demo trading account--------------
    function crypto_withdraw_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Crypto Withdraw');
            $("#crypto-withdraw-form").trigger('reset');
            $("#last-amount").text(data.last_transaction.amount);
            $("#last-txn-id").text(data.last_transaction.transaction_id);
            $("#last-status").find('.badge').removeClass('badge-success, badge-warning').addClass('badge-dark').text('Pending');
            $("#btn-js-next-3").trigger("click");
            $.validator("crypto-withdraw-form", data.errors);
        }
        if (data.status == false) {
            notify('error', data.message, 'Crypto withdraw');
            $.validator("crypto-withdraw-form", data.errors);
        }
        // validation status
        if (data.valid_status == false) {
            notify('error', data.message, 'Crypto Withdraw');
            $.validator("crypto-withdraw-form", data.errors);
        }
        // step 1 status
        if (data.step_1_status == true) {
            $("#btn-js-next").trigger('click');
            $("#op").val('step-2');
            $.validator("crypto-withdraw-form", data.errors);
        }
        // seding otp status
        if (data.otp_send == true) {
            $("#op").val('step-3');
            $("#btn-js-next-2").trigger('click');
        }
        if (data.otp_status == false) {
            notify('error', data.message, 'OTP Errors');
            $.each(data.errors, function(index, value) {
                $("input[name=" + index + "]").addClass('is-invalid');
            });
        }
        submit_wait("#btn-submit-request-final", data.submit_wait);
        $('.multisteps-form__panel').height_control();
    }
    $(document).on("change", "#block-chain", function() {
        let block_chain = $(this).val();
        // get existing address

        $.ajax({
            dataType: 'json',
            method: 'GET',
            url: '/admin/settings/get_crypto_address/' + block_chain,
            success: function(data) {
                $("#crypto_address").val(data.address);
            }
        });
        // change instrument options
        if (block_chain === 'USDT') {
            $('#crypto-name').html(
                '<option value="BEP20">BEP20</option><option value="ERC20">ERC20</option><option value="TRC20">TRC20</option>'
            );
        } else if (block_chain === 'BTC') {
            $('#crypto-name').html('<option value="bitcoin">Bitcoin</option>');
        } else if (block_chain === 'ETH') {
            $('#crypto-name').html('<option value="etherariam">Etherariam</option>');
        } else if (block_chain === 'LTC') {
            $('#crypto-name').html('<option value="ltc">LTC</option>');
        } else {
            $('#crypto-name').html('<option value="">Please select block chain first</option>');
        }
    })
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
    currency_convert("usd", "crypto-amount", "usd-amount");
    // otp input fucus
    $(document).on("keyup", ".otp-value", function() {
        let $value = $(this).val();
        if ($value != "") {
            $(this).addClass('is-valid').removeClass('is-invalid');
            $(this).closest(".col").next(".col").find(".otp-value").focus();
        } else {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
    });
</script>
@stop
