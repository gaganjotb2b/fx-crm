@extends(App\Services\systems\VersionControllService::get_ib_layout())
@section('title', 'Bank Withdraw')
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
<!-- bread crumb -->
@section('bread_crumb')
@php use App\Services\IBManagementService; @endphp
{!!App\Services\systems\BreadCrumbService::get_ib_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid py-4">
    <div class="row custom-height-con">
        <div class="col-12 text-center">
            <h3 class="mt-5">{{ __('page.bank_withdraw') }}</h3>
            <h5 class="text-secondary font-weight-normal">
                {{ __('page.we_need_your_information_becuase_you_transfer_your_balance') }}.
            </h5>
            <div class="multisteps-form mb-5">
                <!--progress bar-->
                <div class="row">
                    <div class="col-12 col-lg-8 mx-auto my-5">
                        <div class="multisteps-form__progress">
                            <!-- progress bank info -->
                            <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">
                                <span>{{ __('page.bank_info') }}</span>
                            </button>
                            <!-- progress address and amount -->
                            <button class="multisteps-form__progress-btn" type="button" title="Order Info" disabled>
                                <span>{{ __('page.address_&_amount') }}</span>
                            </button>
                            <!-- progress otp verfications -->
                            @if ($otp_settings == true && $user_otp_settings == true)
                            @if ($otp_settings->withdraw == true && $user_otp_settings->withdraw == true)
                            <button class="multisteps-form__progress-btn" type="button" title="Order Info" disabled>
                                <span>Transaction Pin</span>
                            </button>
                            @endif
                            @endif
                            <!-- progress status -->
                            <button class="multisteps-form__progress-btn" type="button" title="Order Info" disabled>
                                <span>{{ __('page.status') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!--form panels-->
                <div class="row">
                    <div class="col-12 col-lg-8 m-auto">
                        <div class="multisteps-form__form">
                            <form action="{{ route('ib.withdraw.bank-withdraw') }}" method="post" id="bank-withdraw-form">
                                <!--single form panel-->
                                <input type="hidden" name="op" value="step-1" id="op">
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <!-- heading adn description -->
                                            <h5 class="font-weight-normal">
                                                {{ __('page.lets_start_with_your_account_information') }}
                                            </h5>
                                            <p>{{ __('page.please_read_crarefully_first_and_submit_your_request') }}
                                                <span class="help"><i class="fas fa-help"></i></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row mt-3">
                                            <!-- bank logo -->
                                            <div class="col-12 col-sm-4">
                                                <div class="avatar avatar-xxl position-relative">
                                                    <img id="platform-logo" src="{{ asset('comon-icon/bank-icon.png') }}" class="border-radius-md img-thumbnail" alt="team-2">
                                                </div>
                                            </div>
                                            @if(\App\Services\Trader\FinanceService::check_op('withdraw'))
                                            <!-- check kyc verified or not -->
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
                                                                echo 'Please wait while your kyc approve for bank withdraw';
                                                                } else {
                                                                echo 'Please first verify your kyc for bank withdraw';
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
                                            @elseif (count($banks) == 0)
                                            <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                                <div class="nav-wrapper position-relative end-0">
                                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                        <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                                                        <span class="alert-text">
                                                            <strong>{{ __('page.warning') }}!</strong>
                                                            <span> Currently you don't add any bank account. To
                                                                continue add bank account first </span>!
                                                        </span>
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                                @csrf
                                                <div class="form-group al-error-solve">
                                                    <label for="bank-account">{{ __('page.bank') }}</label>
                                                    <select class="form-control choice-colors" id="bank" name="bank">
                                                        <option value="">{{ __('page.select_your_bank') }}
                                                        </option>
                                                        @foreach ($banks as $value)
                                                        <option value="{{ $value->bank_name }}">
                                                            {{ $value->bank_name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="bank-account">{{ __('page.bank_account') }}</label>
                                                    <select class="form-control" id="bank-account" name="bank_account">
                                                        <option value="">{{ __('page.select_your_bank') }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="bank-account-name">{{ __('page.bank_account_name') }}</label>
                                                    <input type="text" name="bank_account_name" id="bank-account-name" placeholder="{{ __('page.bank_account_name') }}" class="form-control form-control-alternative" disabled />
                                                </div>
                                                <div class="form-group">
                                                    <label for="swift-code" id="swift-code-label">{{ __('page.swift-code') }}</label>
                                                    <input type="password" name="code" id="swift-code" placeholder="{{ __('page.swift-code') }}" class="form-control form-control-alternative" />
                                                </div>
                                                <div class="form-group d-none">
                                                    <label for="iban">{{ __('page.IBAN') }}</label>
                                                    <input type="text" name="iban" id="iban" placeholder="{{ __('page.IBAN') }}" class="form-control form-control-alternative" disabled />
                                                </div>
                                                <!-- multi currency -->
                                                @if(\App\Services\BankService::is_multicurrency('all'))
                                                <div class="form-group">
                                                    <label for="currency">Currency</label>
                                                    <input type="text" name="currency" id="currency" class="form-control form-control-alternative" disabled readonly />
                                                </div>
                                                @endif
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
                                        <div class="button-row d-flex mt-4">
                                            @php
                                            $disable = '';
                                            $has_multi_submit = has_multi_submit('crypto-withdraw', 15);
                                            if ($has_multi_submit) {
                                            $disable = 'disabled';
                                            }
                                            @endphp
                                            <div class="col-4"></div>
                                            <div class="col-6 mx-auto">
                                                <!-- first step submit button -->
                                                @if(\App\Services\Trader\FinanceService::check_op('withdraw'))
                                                @if (count($banks) != 0)
                                                <button type="button" data-label="Next" id="btn-submit-request" data-btnid="btn-submit-request" data-callback="bank_withdraw_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="bank-withdraw-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto mb-3 mt-4 float-end" data-submit_wait="{{ submit_wait('bank-withdraw', 15) }}" {{ $disable }} style="width: 200px">Next</button>
                                                @else
                                                <a href="{{ route('ib.ib-admin.ib-banking') }}" class="btn bg-gradient-warning ms-auto mb-3 mt-4 float-end">+Add Bank</a>
                                                @endif
                                                @endif
                                            </div>
                                            <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="btn-js-next">Next</button>
                                        </div>
                                    </div>
                                </div>
                                <!--single form panel-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">Your Bank Address and Transaction Password
                                            </h5>
                                            <p>We Need your bank country and address for your secure transactions.
                                                Transaction pin required for your security varification.</p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row mt-3">
                                            <div class="col-12 col-sm-4">
                                                <div class="avatar avatar-xxl position-relative">
                                                    <img id="platform-logo" src="{{ asset('comon-icon/bank-icon.png') }}" class="border-radius-md img-thumbnail" alt="team-2">

                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                                <div class="form-group">
                                                    <label for="country">{{ __('page.country') }}</label>
                                                    <select class="form-control" id="country" name="country">
                                                        <option>India</option>
                                                        <option>country</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="address">{{ __('page.address') }}</label>
                                                    <textarea name="address" id="address" placeholder="Address" class="form-control form-control-alternative"></textarea>
                                                </div>
                                                <!-- amount usd -->
                                                <div class="form-group">
                                                    <label for="amount">{{ __('page.amount') }} (USD)</label>
                                                    <input type="text" name="amount" id="amount" placeholder="0.00" class="form-control form-control-alternative currency" data-currency="USD" />
                                                </div>
                                                <!-- local currency amount -->
                                                <!-- multi currency -->
                                                @if(\App\Services\BankService::is_multicurrency('all'))
                                                <div class="form-group">
                                                    <input type="hidden" name="currency_name" id="currency_name" value="">
                                                    <input type="hidden" name="transaction_type" id="transaction_type" value="">
                                                    <label for="amount-local">{{__('page.amount')}} <span class="local-currency"><span></label>
                                                    <input type="text" name="amount_local" id="amount-local" placeholder="0.00" class="form-control form-control-alternative" />
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="button-row d-flex mt-4">
                                        <div class="col-4"></div>
                                        <div class="col-6 mx-auto">
                                            <!-- <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="btn-js-next-2">Next</button> -->
                                            <button type="button" data-label="Next" id="btn-submit-request-2" data-btnid="btn-submit-request-2" data-callback="bank_withdraw_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="bank-withdraw-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto mb-3 mt-4 float-end visualy-hidden" style="width: 200px">{{ __('page.next') }}</button>
                                        </div>
                                        <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="btn-js-next-2">Next</button>
                                    </div>
                                </div>
                                <!-- otp checking -->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{ __('page.confirm-its-you?') }}</h5>
                                            <p>Please check your email and provide transaction pin into this input field.</p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">

                                        <div class="row mt-3">
                                            <div class="col-12 col-sm-4">
                                                <div class="avatar avatar-xxl position-relative">
                                                    <img id="platform-logo" src="{{ asset('comon-icon/bank-icon.png') }}" class="border-radius-md img-thumbnail" alt="team-2">
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
                                                    <!--<span class="text-muted text-sm">{{ __('page.haven\'t-received-it?') }}-->
                                                    <!--    <button type="button" id="btn-js-next-resend" data-label="Resend Code" class="btn mb-0 text-capitalize" data-loading="<i class='fa-spin fas fa-circle-notch'></i>">{{ __('page.resend-code') }}</button>-->
                                                    <!--    <button type="button" data-label="Resend Code" id="btn-resend-code" data-btnid="btn-resend-code" data-callback="open_live_account_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="live-account-form" data-el="fg" onclick="_run(this)" class="btn mb-0 text-capitalize btn-submit-request visually-hidden" data-op="step-2" data-submit_wait="{{ submit_wait('resend-code', 30) }}" {{ $disable }} style="width:200px">Resend-->
                                                    <!--        Code</button>.</span>-->
                                                    
                                                    <div class="form-group">
                                                        <label for="transaction-password">{{ __('page.transaction-password') }}</label>
                                                        <input type="password" name="transaction_password" id="transaction-password" placeholder="Transaction Password" class="form-control form-control-alternative" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4"></div>
                                            <div class="col-sm-6 mx-auto pe-0">
                                                @php
                                                $disable = '';
                                                $has_multi_submit = has_multi_submit('bank-withdraw', 30);
                                                if ($has_multi_submit) {
                                                $disable = 'disabled';
                                                }
                                                @endphp
                                                <button type="button" data-label="Submit Request" id="btn-submit-request-final" data-btnid="btn-submit-request-final" data-callback="bank_withdraw_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="bank-withdraw-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto float-end mb-0 mt-4 btn-submit-request" data-op="step-2" data-submit_wait="{{ submit_wait('bank-withdraw', 30) }}" {{ $disable }} style="width:200px">{{ __('page.submit-request') }}</button>
                                                <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="js-btn-next-final">Next</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--single form panel-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{ __('page.your-transaction-status') }}
                                            </h5>
                                            <p>Your bank account trasaction status here. You can find olny your last
                                                bank withdraw transaction status. You also find your all transaction
                                                report in reports area.</p>
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
                                    <div class="row">
                                        <div class="button-row d-flex mt-4 col-12">
                                            <!-- <button class="btn bg-gradient-light mb-0 js-btn-prev float-end" type="button" title="Prev">Previous</button> -->
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
<!-- <script src="{{ asset('trader-assets/assets/js/scripts/pages/bank-withdraw.js') }}"></script> -->
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js') }}"></script>
<script src="{{ asset('common-js/multistep-controller.js') }}"></script>
<script>
    $(document).on("change", "#bank", function() {
        let bank_name = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: '/ib/withdraw/bank',
            data: {
                bank_name: bank_name,
                op: 'banks'
            },
            success: function(data) {
                var value = (data.bank_options);
                $("#bank-account").html(value)
                $("#bank-account-name").val(data.bank_accounts.bank_account_name);
                $("#swift-code").val(data.bank_accounts.swift_code);
                $("#iban").val(data.bank_accounts.iban);
                $("#country").html(data.bank_accounts.country);
                $("#address").val(data.bank_accounts.address);
                $("#swift-code-label").text(data.bank_accounts.swift_code_label);
            }
        })
    });

    // change bank accounts ----------------------
    $(document).on("change", "#bank-account", function() {
        let bank_account = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: '/ib/withdraw/bank',
            data: {
                bank_account: bank_account,
                op: 'bank-accounts'
            },
            success: function(data) {
                $("#bank-account").html(data.bank_options);
                $("#bank-account-name").val(data.bank_accounts.bank_account_name);
                $("#swift-code").val(data.bank_accounts.swift_code);
                $("#iban").val(data.bank_accounts.iban);
                $("#country").html(data.bank_accounts.country);
                $("#address").val(data.bank_accounts.address);
            }
        })
    });
    submit_wait("#btn-submit-request-final");
    $("#bank-withdraw-form").trigger('reset');
    $("#op").val('step-1');
    // resending otp
    $(document).on("click", "#btn-js-next-resend", function() {
        $("#op").val('resend');
        $(this).html($(this).data('loading'));
        $("#btn-resend-code").trigger('click');
    });
    $(document).on('click', '#btn-submit-request-2', function() {
        $(this).prop('disabled', true);
    });
    // create request for bank withdraw
    function bank_withdraw_call_back(data) {
        // form step status
        if (data.status == true) {
            $("#btn-submit-request-2").prop('disabled', false);
            notify('success', data.message, 'Bank Withdraw');
            $("#bank-withdraw-form").trigger('reset');
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
            $("#last-status").find('.badge').removeClass('badge-success, badge-warning').addClass('badge-dark').text(
                status);
            $("#js-btn-next-final").trigger('click');
            $.validator("bank-withdraw-form", data.errors);
        }
        if (data.status == false) {
            notify('error', data.message, 'Bank Withdraw');
            $.validator("bank-withdraw-form", data.errors);
        }
        // validation status
        if (data.valid_status == false) {
            notify('error', data.message, 'Bank Withdraw');
            $.validator("bank-withdraw-form", data.errors);
        }
        // step 1 status
        if (data.step_1_status == true) {
            $("#btn-js-next").trigger('click');
            $("#op").val('step-2');
            $.validator("bank-withdraw-form", data.errors);
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
        $('.multisteps-form__panel').height_control()
    }
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
    // *******************************************************************************
    // exchange to local currency
    // *******************************************************************************
    // usd to local currency exchange
    $(document).on("input", "#amount", function() {
        currency = $('#currency_name').val();
        transaction_type = $('#transaction_type').val();
        let amount = $(this).val();
        if (amount == "") {
            amount = 0;
        }
        $.ajax({
            url: "/currency/get-currency/" + amount + "/from/USD/to/" + currency + "/transaction-type/withdraw",
            method: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $("#amount-local").val(data);
            }
        });
    });
    // local currency to usd exchange
    $(document).on("input", "#amount-local", function() {
        currency = $('#currency_name').val();
        transaction_type = $('#transaction_type').val();
        let amount = $(this).val();
        if (amount == "") {
            amount = 0;
        }
        $.ajax({
            url: "/currency/get-currency/" + amount + "/from/" + currency + "/to/USD/transaction-type/withdraw",
            method: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $("#amount").val(data);
            }
        });
    });
</script>
@stop