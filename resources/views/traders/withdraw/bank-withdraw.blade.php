@extends(App\Services\systems\VersionControllService::get_layout('trader'))
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
@section('bread_crumb')
<!-- bread crumb -->
<!-- check crm version -->
@if(App\Services\systems\VersionControllService::check_version()==='lite')
<!-- include breadcrumb lite -->
@include('traders.breadcrumb.breadcurmb-lite')
@else
<!-- inlclude breadcrumb pro -->
@include('traders.breadcrumb.breadcrumb-pro')
@endif
@stop
@section('content')
<div class="container-fluid py-4">
    <div class="row custom-height-con">
        <div class="col-12 text-center">
            <h3 class="mt-5">{{ __('page.bank_withdraw') }}</h3>
            <h5 class="text-secondary font-weight-normal">
                {{ __('page.we_need_your_information_becuase_you_make_a_transaction') }}.
            </h5>
            <div class="multisteps-form mb-5">
                <!--progress bar-->
                <div class="row">
                    <div class="col-12 col-lg-8 mx-auto my-5">
                        <div class="multisteps-form__progress">
                            <!-- bank info progress button -->
                            <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">
                                <span>{{ __('page.bank_info') }}</span>
                            </button>
                            <!-- address progress button -->
                            <button class="multisteps-form__progress-btn" type="button" title="Order Info" disabled>
                                <span>{{ __('page.address') }} & {{ __('page.amount') }}</span>
                            </button>
                            <!-- otp progress button -->
                            @if($otp_settings == true && $user_otp_settings == true)
                            @if($otp_settings->withdraw == true && $user_otp_settings->withdraw == true)
                            <button class="multisteps-form__progress-btn" type="button" title="Order Info" disabled>
                                <span>{{ __('Transaction Pin') }}</span>
                            </button>
                            @endif
                            @endif
                            <!-- status progress button -->
                            <button class="multisteps-form__progress-btn" type="button" title="Order Info" disabled>
                                <span>{{ __('page.status') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!--form panels-->
                <div class="row">
                    <div class="col-12 col-lg-8 m-auto">
                        <div class="multisteps-form__form bg-custom-dark-for rounded-3">
                            <form action="{{ route('user.withdraw.bank-withdraw') }}" method="post" id="bank-withdraw-form">
                                <!--single form panel-->
                                <input type="hidden" name="op" value="step-1" id="op">
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                    <!-- heading and description -->
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">
                                                {{ __('page.lets-start-with-the-basic-information') }}
                                            </h5>
                                            <p>{{ __('page.please_read_crarefully_first_and_submit') }} <span class="help"><i class="fas fa-help"></i></span></p>
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
                                                <!-- check withdraw permited or not -->
                                                @if(\App\Services\Trader\FinanceService::check_op('withdraw'))
                                                <!-- select  withdraw option-->
                                                @if(\App\Services\systems\TransactionSettings::is_account_withdraw())
                                                <div class="form-group">
                                                    <label for="withdraw-options">Withdraw Options</label>
                                                    <select name="withdraw_option" id="withdraw-options" class="form-select form-control">
                                                        <option value="account">Account withdraw</option>
                                                        <option value="wallet" selected>Wallet withdraw</option>
                                                    </select>
                                                </div>
                                                <!-- Account Number -->
                                                <div class="form-group account_number d-none" id="row-trading-account">
                                                    <label for="trading-accounts">Trading Account Number</label>
                                                    <select class="select2 form-select" id="trading-accounts" name="trading_account_number">
                                                        @foreach($trading_accounts as $value)
                                                        <option value="{{ encrypt($value->id)}}">{{ $value->account_number}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endif

                                                <!-- check kyc required or not -->
                                                @if(\App\Services\AllFunctionService::kyc_required((auth()->user()->id),'withdraw') == false )
                                                @csrf
                                                <div class="form-group">
                                                    <label for="bank-account">{{ __('finance.Bank') }}</label>
                                                    <select class="form-control" id="bank" name="bank">
                                                        <option value="">{{ __('page.select_bank') }}</option>
                                                        @foreach ($banks as $value)
                                                        <option value="{{ $value->bank_name }}">
                                                            {{ $value->bank_name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="bank-account">Bank Account</label>
                                                    <select class="form-control" id="bank-account" name="bank_account">
                                                        <option value="">
                                                            {{ __('page.Select_your_bank_account') }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="bank-account-name">{{ __('page.bank_account_name') }}</label>
                                                    <input type="text" name="bank_account_name" id="bank-account-name" placeholder="{{ __('page.bank_account_name') }}" class="form-control form-control-alternative" disabled />
                                                </div>
                                                <div class="form-group">
                                                    <label for="swift-code" id="swift-code-label">{{ __('page.swift-code') }}</label>
                                                    <input type="text" name="code" id="swift-code" placeholder="IFSC Code" class="form-control form-control-alternative" disabled />
                                                </div>
                                                <div class="form-group d-none">
                                                    <label for="iban">{{ __('page.IBAN') }}</label>
                                                    <input type="text" name="iban" id="iban" placeholder="{{ __('page.bank') }} {{ __('page.IBAN') }}" class="form-control form-control-alternative" disabled />
                                                </div>
                                                <!-- multi currency -->
                                                @if(\App\Services\BankService::is_multicurrency('all'))
                                                <div class="form-group currency-field">
                                                    <label for="currency">Currency</label>
                                                    <input type="text" name="currency" id="currency" class="form-control form-control-alternative" disabled readonly />
                                                </div>
                                                @endif
                                                @else
                                                <div class="">
                                                    <div class="alert alert-warning" role="alert">
                                                        <strong>Warning!</strong> KYC Required for withdraw!
                                                    </div>
                                                </div>
                                                @endif
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
                                        <div class="button-row d-flex mt-4">
                                            @php
                                            $disable = '';
                                            $has_multi_submit = has_multi_submit('crypto-withdraw', 60);
                                            if ($has_multi_submit) {
                                            $disable = 'disabled';
                                            }
                                            @endphp
                                            <div class="col-4">
                                                <a type="button" href="{{url('user/user-admin/user-banking')}}" class="btn bg-gradient-warning ms-auto mb-3 mt-4 float-start" style="width: 200px; margin-left: 20px !important;">+Add New Bank</a>
                                            </div>
                                            <div class="col-6 mx-auto">
                                                <!-- first step submit button -->
                                                @if(\App\Services\Trader\FinanceService::check_op('withdraw'))
                                                <!-- check kyc required or not -->
                                                @if(\App\Services\AllFunctionService::kyc_required((auth()->user()->id),'withdraw') == false )
                                                <button type="button" data-label="Next" id="btn-submit-request" data-btnid="btn-submit-request" data-callback="bank_withdraw_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="bank-withdraw-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto mb-3 mt-4 float-end" data-submit_wait="{{ submit_wait('bank-withdraw', 60) }}" {{ $disable }} style="width: 200px">Next</button>
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
                                            <h5 class="font-weight-normal">
                                                {{ __('page.Your_Bank_Address_and_Transaction_Password') }}
                                            </h5>
                                            <p>{{ __('page.Your_Bank_Address_and_Transaction_Password_for_your_secure') }}.
                                            </p>
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
                                                    <label for="country">{{ __('finance.country') }}</label>
                                                    <select class="form-control" id="country" name="country">
                                                        <option>India</option>
                                                        <option>{{ __('finance.country') }}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="address">{{ __('page.address') }}</label>
                                                    <textarea name="address" id="address" placeholder="Address" class="form-control form-control-alternative"></textarea>
                                                </div>
                                                <!-- usd amount -->
                                                <div class="form-group">
                                                    <label for="amount">{{__('page.amount')}} (USD)</label>
                                                    <input type="text" name="amount" id="amount" placeholder="0.00" class="form-control form-control-alternative currency" />
                                                </div>
                                                <!-- local currency amount -->
                                                <!-- multi currency -->
                                                @if(\App\Services\BankService::is_multicurrency('all'))
                                                <div class="form-group currency-field">
                                                    <input type="hidden" name="currency_name" id="currency_name" value="">
                                                    <input type="hidden" name="transaction_type" id="transaction_type" value="">
                                                    <label for="amount-local">{{__('page.amount')}} <span class="local-currency"><span></label>
                                                    <input type="text" name="amount_local" id="amount-local" placeholder="0.00" class="form-control form-control-alternative" />
                                                </div>
                                                @endif
                                                <!--<div class="form-group">-->
                                                <!--    <label for="transaction-password">{{ __('page.transaction_password') }}</label>-->
                                                <!--    <input type="password" name="transaction_password" id="transaction-password" placeholder="Transaction Password" class="form-control form-control-alternative" />-->
                                                <!--</div>-->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="button-row d-flex mt-4">
                                        <div class="col-4"></div>
                                        <div class="col-6 mx-auto">
                                            <!-- <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="btn-js-next-2">Next</button> -->
                                            <button type="button" data-label="Next" id="btn-submit-request-2" data-btnid="btn-submit-request-2" data-callback="bank_withdraw_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="bank-withdraw-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto mb-3 mt-4 float-end visualy-hidden" style="width: 200px">{{ __('page.next') }}</button>
                                        </div>
                                        {{-- @if($otp_settings->transfer == true && $user_otp_settings->transfer == true)
                                        <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="btn-js-next">{{ __('page.next') }}</button>
                                        @else --}}
                                        <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="btn-js-next-2">{{ __('page.next') }}</button>
                                        {{-- @endif --}}
                                    </div>
                                </div>
                                <!-- otp checking -->
                                {{-- @if($otp_settings->transfer == true && $user_otp_settings->transfer == true) --}}
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{ __('page.Confirm_Its_You') }}?</h5>
                                            <p class="text-danger">Please check your email and provide transaction pin into this input field.</p>
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
                                                    <!--<span class="text-muted text-sm">{{ __('page.have\'t_recive') }}?-->
                                                    <!--    <button type="button" id="btn-js-next-resend" data-label="Resend Code" class="btn mb-0 text-capitalize" data-loading="<i class='fa-spin fas fa-circle-notch'></i>">{{ __('page.resend_code') }}</button>-->
                                                    <!--    <button type="button" data-label="Resend Code" id="btn-resend-code" data-btnid="btn-resend-code" data-callback="bank_withdraw_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="bank-withdraw-form" data-el="fg" onclick="_run(this)" class="btn mb-0 text-capitalize btn-submit-request visually-hidden" data-op="step-2" data-submit_wait="{{ submit_wait('resend-code', 30) }}" {{ $disable }} style="width:200px">{{ __('page.resend_code') }}</button>.</span>-->
                                                    <div class="form-group">
                                                        <label class="text-left" for="transaction-password">{{ __('page.transaction_password') }}</label>
                                                        <input type="password" name="transaction_password" id="transaction-password" placeholder="Transaction Pin" class="form-control form-control-alternative" />
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
                                                <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="js-btn-next-final">{{ __('page.next') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- @endif --}}
                                <!--single form panel-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{ __('page.your_transaction_status') }}
                                            </h5>
                                            <p>{{ __('page.your_bank_account_trasaction_status') }}.</p>
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
                                                    <!-- multi currency -->
                                                    @if(\App\Services\BankService::is_multicurrency('all'))
                                                    <tr class="currency-field">
                                                        <th>Local Currency</th>
                                                        <th id="last-currency">{{ $last_transaction ? $last_transaction->currency : '---'}}</th>
                                                    </tr>
                                                    <tr class="currency-field">
                                                        <th>Local Currency Amount</th>
                                                        <th id="last-local-currency">{{ $last_transaction ? $last_transaction->local_currency : '---'}}</th>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <th>{{ __('page.transaction_id') }}</th>
                                                        <th id="last-txn-id">
                                                            {{ $last_transaction ? $last_transaction->transaction_id : '---' }}
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('page.status') }}</th>
                                                        <th class="badge-dark" id="last-status">
                                                            <span class="badge rounded-pill badge-warning badge-sm">Pending</span>
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
                                            <button class="btn bg-gradient-light mb-0 float-end"><a href="{{route('user.withdraw.bank-withdraw-form')}}">Go Back To Withdraw</a></button>
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
            url: '/user/withdraw/bank',
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
            url: '/user/withdraw/bank',
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
    // disable final submit button
    $(document).on('click', '#btn-submit-request-2', function() {
        $(this).prop('disabled', true);
        setTimeout(() => {
            $(this).prop('disabled', false);
        }, 30000);
    })

    $(document).on('click', '#btn-submit-request', function() {
        $(this).prop('disabled', true);
    })
    // create request for bank withdraw
    function bank_withdraw_call_back(data) {
        // form step status
        if (data.status == true) {
            notify('success', data.message, 'Bank Withdraw');
            $(this).prop('disabled', false);
            $("#bank-withdraw-form").trigger('reset');
            $("#last-amount").text("$" + data.last_transaction.amount);
            $("#last-currency").text(data.last_transaction.currency);
            $("#last-local-currency").text(data.last_transaction.local_currency);
            $("#last-txn-id").text(data.last_transaction.transaction_id);
            // $("#last-status").find('.badge').removeClass('badge-success, badge-warning').addClass('badge-dark').text('Pending');
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
            $('#btn-js-next-resend').html('Resend Code');
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

    // direct withdraw options
    // when direct account deposit is one
    change_depsoit_options($('#withdraw-options').val());
    $(document).on("change", "#withdraw-options", function() {
        change_depsoit_options($(this).val());
    });

    function change_depsoit_options($value) {
        $('.multisteps-form__panel').height_control();
        if ($value === 'account') {
            $('#row-trading-account').removeClass('d-none');
        } else {
            $('#row-trading-account').addClass('d-none');
        }
    }
</script>
@stop