@extends(App\Services\systems\VersionControllService::get_layout('ib'))
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
@php use App\Services\IBManagementService; @endphp
{!!App\Services\systems\BreadCrumbService::get_ib_breadcrumb()!!}
@stop
@section('content')
<div class="container-fluid py-4">
    <div class="row custom-height-con">
        <div class="col-12 text-center">
            <h3 class="mt-5">Gcash Withdraw</h3>
            <h5 class="text-secondary font-weight-normal">
                we need your information becuase you make a withdraw
            </h5>
            <div class="multisteps-form mb-5">
                <!--progress bar-->
                <div class="row">
                    <div class="col-12 col-lg-8 mx-auto my-5">
                        <div class="multisteps-form__progress">
                            <!-- bank info progress button -->
                            <button class="multisteps-form__progress-btn js-active" type="button" title="User Info" id="btn-request-progress">
                                <span>Request</span>
                            </button>
                            <!-- otp progress button -->
                            @if($otp_settings == true && $user_otp_settings == true)
                            @if($otp_settings->withdraw == true && $user_otp_settings->withdraw == true)
                            <button class="multisteps-form__progress-btn" type="button" title="Order Info" id="btn-otp-progress">
                                <span>{{ __('page.otp') }}</span>
                            </button>
                            @endif
                            @endif
                            <!-- status progress button -->
                            <button class="multisteps-form__progress-btn" type="button" title="Order Info" id="btn-status-progress">
                                <span>{{ __('page.status') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!--form panels-->
                <div class="row">
                    <div class="col-12 col-lg-8 m-auto">
                        <div class="multisteps-form__form bg-custom-dark-for rounded-3">
                            <form action="{{ route('user.withdraw.gcash-index.request') }}" method="post" id="bank-withdraw-form">
                                <!--single form panel-->
                                <input type="hidden" name="op" value="request" id="op">
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                    <!-- heading and description -->
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <p>Please fill up fields thats are required. The amount in USD and enter your GCash ID on the bellow input field.<span class="help"><i class="fas fa-help"></i></span></p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row mt-3">
                                            <div class="col-12 col-sm-4">
                                                <div class="avatar avatar-xxl position-relative">
                                                    <img id="platform-logo" src="{{ asset('comon-icon/gcash.png') }}" class="border-radius-md img-thumbnail" alt="GCash">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                                <!-- check withdraw permited or not -->
                                                @if(\App\Services\Trader\FinanceService::check_op('withdraw'))
                                                <!-- check kyc required or not -->
                                                @if(\App\Services\AllFunctionService::kyc_required((auth()->user()->id),'withdraw') == false )
                                                @csrf
                                                <!-- client email -->
                                                <div class="form-group">
                                                    <label for="account-email">Email</label>
                                                    <input type="text" id="account-email" name="email" value="{{auth()->user()->email}}" disabled class="form-control form-input form-control-alternative">
                                                </div>
                                                <!-- amount usd -->
                                                <div class="form-group">
                                                    <label for="bank-account">Amount (USD)</label>
                                                    <input type="text" id="usd-amount" name="amount" placeholder="0.00" class="form-control form-input form-control-alternative">
                                                </div>
                                                <!-- gcash id -->
                                                <div class="form-group">
                                                    <label for="gcash-id">GCash ID</label>
                                                    <input type="text" id="gcash-id" name="gcash_ID" class="form-control form-input-alternative" placeholder="GCash ID">
                                                </div>
                                                <!-- transaction password -->
                                                <div class="form-group">
                                                    <label for="transaction-password">{{ __('page.transaction_password') }}</label>
                                                    <input type="password" name="transaction_password" id="transaction-password" placeholder="Transaction Password" class="form-control form-control-alternative" />
                                                </div>
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

                                            <div class="col-4"></div>
                                            <div class="col-6 mx-auto">
                                                <!-- first step submit button -->
                                                @if(\App\Services\Trader\FinanceService::check_op('withdraw'))
                                                <!-- check kyc required or not -->
                                                @if(\App\Services\AllFunctionService::kyc_required((auth()->user()->id),'withdraw') == false )
                                                <button type="button" data-label="Next" id="btn-submit-request" data-btnid="btn-submit-request" data-callback="bank_withdraw_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="bank-withdraw-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto mb-3 mt-4 float-end" style="width: 200px">Submit Request</button>
                                                @endif
                                                @endif
                                            </div>
                                            <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="btn-js-next">Next</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- otp checking -->
                                @if($otp_settings->transfer == true && $user_otp_settings->transfer == true)
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{ __('page.Confirm_Its_You') }}?</h5>
                                            <p>{{ __('page.We_check_OTP_for_your_account_make_secure') }}.</p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">

                                        <div class="row mt-3">
                                            <div class="col-12 col-sm-4">
                                                <div class="avatar avatar-xxl position-relative">
                                                    <img id="platform-logo" src="{{ asset('comon-icon/gcash.png') }}" class="border-radius-md img-thumbnail" alt="GCash">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                                <div class=" text-center row gx-2 gx-sm-3 custom-otp-boxs border p-6 rounded">
                                                    <input type="hidden" name="otp" id="otp">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" name="otp_1" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="none">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" name="otp_2" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="none">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" name="otp_3" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="none">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" name="otp_4" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="none">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" name="otp_5" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="none">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" name="otp_6" class="form-control form-control-lg otp-value" maxlength="1" autocomplete="off" autocapitalize="none">
                                                        </div>
                                                    </div>
                                                    <span class="text-muted text-sm">{{ __('page.have\'t_recive') }}?
                                                        <button type="button" id="btn-js-next-resend" data-label="Resend Code" class="btn mb-0 text-capitalize" data-loading="<i class='fa-spin fas fa-circle-notch'></i>">{{ __('page.resend_code') }}</button>
                                                        <button type="button" data-label="Resend Code" id="btn-resend-code" data-btnid="btn-resend-code" data-callback="bank_withdraw_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="bank-withdraw-form" data-el="fg" onclick="_run(this)" class="btn mb-0 text-capitalize btn-submit-request visually-hidden" style="width:200px">{{ __('page.resend_code') }}</button>.</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <button class="btn bg-gradient-light mb-0 mt-4 js-btn-prev" type="button" title="Prev">Previous</button>
                                            </div>
                                            <div class="col-sm-6 mx-auto pe-0">
                                                <button type="button" data-label="Submit Request" id="btn-submit-request-final" data-btnid="btn-submit-request-final" data-callback="bank_withdraw_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="bank-withdraw-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto float-end mb-0 mt-4 btn-submit-request" style="width:200px">{{ __('page.submit-request') }}</button>
                                                <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="js-btn-next-final">{{ __('page.next') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <!--single form panel-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{ __('page.your_transaction_status') }}
                                            </h5>
                                            <p>You Gcash withdraw status. Here all statuss available only for current sumited withdraw request.</p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row text-start">
                                            <h5>Current transaction status</h5>
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th>{{ __('page.amount') }}</th>
                                                        <th id="last-amount">
                                                            --
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('page.transaction_id') }}</th>
                                                        <th id="last-txn-id">
                                                            ---
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('page.status') }}</th>
                                                        <th class="badge-dark" id="last-status">
                                                            <span class="badge rounded-pill badge-success badge-sm">--</span>
                                                        </th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="button-row d-flex mt-4 col-12">
                                            <button class="btn bg-gradient-light mb-0 js-btn-prev float-end" data-target="start" type="button" title="Prev">Back to new request</button>
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
<script src="{{ asset('trader-assets/assets/js/scripts/pages/bank-withdraw.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js') }}"></script>
<script>
    $("#bank-withdraw-form").trigger('reset');
    $("#op").val('request');
    // resending otp
    $(document).on("click", "#btn-js-next-resend", function() {
        $("#op").val('resend');
        $(this).html($(this).data('loading'));
        $("#btn-resend-code").trigger('click');
    });
    // disable final submit button
    $(document).on('click', '#btn-submit-request', function() {
        $(this).prop('disabled', true);
        setTimeout(() => {
            $(this).prop('disabled', false);
        }, 30000);
    });
    // disable submit button
    $(document).on('click', '#btn-submit-request-final', function() {
        $(this).prop('disabled', true);
        setTimeout(() => {
            $(this).prop('disabled', false);
        }, 30000);
    })
    // create request for bank withdraw
    function bank_withdraw_call_back(data) {
        // form step status
        if (data.status == true && data.next_step === 'preview') {
            notify('success', data.message, 'GCash Withdraw');
            $("#bank-withdraw-form").trigger('reset');
            $("#last-amount").text("$" + data.last_transaction.amount);
            $("#last-txn-id").text(data.last_transaction.transaction_id);
            $("#last-status").find('.badge').removeClass('badge-success, badge-warning').addClass('badge-dark').text('Pending');
            // $("#js-btn-next-final").trigger('click');
            $("#btn-status-progress").trigger('click');
        }
        if (data.status == true && data.next_step === 'otp') {
            $("#btn-js-next").trigger('click');
            $("#op").val(data.next_step);
        }
        if (data.status == false) {
            notify('error', data.message, 'GCash Withdraw');
            $("#op").val(data.next_step);
            if (data.next_step === 'request') {
                $("#btn-request-progress").trigger('click');
            }
            if (data.next_step === 'otp') {
                $("#btn-otp-progress").trigger('click');
            }

        }
        $.validator("bank-withdraw-form", data.errors);
        $('.multisteps-form__panel').height_control();
    }
    // previous button controll
    $(document).on("click", '.js-btn-prev', function() {
        $("#btn-request-progress").trigger('click');
    })
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