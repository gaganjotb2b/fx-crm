@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title','Trader to trader transfer')
@section('page-css')
@if(App\Services\systems\VersionControllService::check_version()==='lite')
<link id="pagestyle" href="{{ asset('trader-assets/assets/css/soft-ui-dashboard.css?v=1.0.8') }}" rel="stylesheet" />
@endif
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
</style>
@stop
@section('bread_crumb')
<!-- bread crumb -->
{!!App\Services\systems\BreadCrumbService::get_trader_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid py-4">
    <div class="row custom-height-con">
        <div class="col-12 text-center">
            <h3 class="mt-5">{{ __('page.trader-to-trader-transfer') }}</h3>
            <h5 class="text-secondary font-weight-normal">{{ __('page.we-need-some-information') }}</h5>
            <div class="multisteps-form mb-5">
                <!--progress bar-->
                <div class="row">
                    <div class="col-12 col-lg-8 mx-auto my-5">
                        <div class="multisteps-form__progress">
                            <!-- progress trasnfer request -->
                            <button class="multisteps-form__progress-btn js-active" type="button" title="User Info" disabled>
                                <span>{{ __('page.transfer-request') }}</span>
                            </button>
                            <!-- progress otp -->
                            @if(\App\Services\OtpService::has_otp('transfer',auth()->user()->id))
                            <button class="multisteps-form__progress-btn" id="otp_btn" type="button" title="Order Info" disabled>
                                <span>{{ __('page.otp') }}</span>
                            </button>
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
                        <div class="multisteps-form__form bg-custom-dark-for rounded-3">
                            <form class="form-demo" action="{{route('user.transfer.trader-to-trader-transfer-form')}}" method="post" id="trader-transfer-form">
                                <!--single form panel-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{ __('page.let\'s-start-with-your-account-information') }}</h5>
                                            <p>{{ __('page.the-recipient-amount-and-transaction-password-required-for-your-secure-transaction') }}</p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row mt-3">
                                            <div class="col-12 col-sm-4">
                                                <!-- first step avater -->
                                                <div class="avatar avatar-xxl position-relative">
                                                    <img id="platform-logo" src="{{asset('admin-assets/app-assets/images/avatars/'.$avatar)}}" class="border-radius-md img-thumbnail" alt="team-2">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 mt-4 mt-sm-0 text-start">
                                                @if(\App\Services\Trader\FinanceService::check_op('trader_to_trader'))
                                                <input type="hidden" name="op" id="op" value="op">
                                                @csrf
                                                <div class="form-group fg al-error-solve">
                                                    <label for="recipient">{{ __('page.recipient') }}</label>
                                                    <!-- <input type="text" name="recipient" list="traders" id="recipient" class="form-control multisteps-form__input">
                                                    <datalist id="traders">

                                                    </datalist> -->
                                                    <select name="recipient" id="recipient" class="form-control form-select select2-trader">

                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="amount">{{ __('page.amount') }}</label>
                                                    <input type="text" class="form-control multisteps-form__input" id="amount" name="amount">
                                                </div>
                                                <div class="form-group">
                                                    <label for="transaction-password">{{ __('page.transaction-password') }}</label>
                                                    <input type="password" class="form-control multisteps-form__input" id="transaction-password" name="transaction_password">
                                                </div>
                                                @else
                                                <!-- warning withdraw operation -->
                                                <div class="col-8 mx-auto">
                                                    <div class="alert alert-warning" role="alert">
                                                        <strong>Warning!</strong> You are not permited to trader to trader! Please contact with <strong> {{config('app.name')}} </strong> Support.
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="button-row d-flex mt-4">
                                            @php
                                            $disable = '';
                                            $has_multi_submit = has_multi_submit('atw-transfer',15);
                                            if($has_multi_submit)
                                            {
                                            $disable = 'disabled';
                                            }
                                            @endphp
                                            <div class="col-4"></div>
                                            <div class="col-6">
                                                <!-- first step submit button -->
                                                <!-- check trader to trader transfer permited or not -->
                                                @if(\App\Services\Trader\FinanceService::check_op('trader_to_trader'))
                                                <button type="button" data-label="Submit Request" id="btn-submit-request" data-btnid="btn-submit-request" data-callback="trader_transfer_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="trader-transfer-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto mb-3 mt-4 float-end" data-submit_wait="{{submit_wait('atw-transfer',15)}}" {{$disable}} style="width:200px">{{ __('page.submit-request') }}</button>
                                                @endif
                                            </div>

                                            @if(\App\Services\OtpService::has_otp('transfer',auth()->user()->id))
                                            <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="btn-js-next">{{ __('page.next') }}</button>
                                            @else
                                            <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="btn-js-next-2">{{ __('page.next') }}</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if(\App\Services\OtpService::has_otp('transfer',auth()->user()->id))
                                <!--otp varification-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{ __('page.your-transaction-status') }}</h5>
                                            <p>{{ __('page.your-neteller-withdraw-account-trasaction-status-here-you-can-find-olny-your-last-netller-withdraw-transaction-status-you-also-find-your-all-transaction-report-in-reports-area') }}</p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row">
                                            <div class="col-12 col-sm-4">
                                                <div class="avatar avatar-xxl position-relative">
                                                    <img id="platform-logo" src="{{asset('admin-assets/app-assets/images/avatars/'.$avatar)}}" class="border-radius-md img-thumbnail" alt="team-2">
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
                                                    <span class="text-muted text-sm">Haven't received it?
                                                        <button type="button" id="btn-js-next-resend" data-label="Resend Code" class="btn mb-0 text-capitalize" data-loading="<i class='fa-spin fas fa-circle-notch'></i>">{{ __('page.resend-code') }}</button>
                                                        <button type="button" data-label="Resend Code" id="btn-resend-code" data-btnid="btn-resend-code" data-callback="trader_transfer_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="trader-transfer-form" data-el="fg" onclick="_run(this)" class="btn mb-0 text-capitalize btn-submit-request visually-hidden" data-op="step-2" style="width:200px">{{ __('page.resend-code') }}</button>.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="button-row d-flex mt-4 col-12">
                                            <div class="col-4"></div>
                                            <div class="col-6 mx-auto">
                                                <button type="button" data-label="Submit Request" id="btn-submit-request-final" data-btnid="btn-submit-request-final" data-callback="trader_transfer_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="trader-transfer-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto ms-3 float-end" style="width:200px">{{ __('page.submit-request') }}</button>
                                                <button class="btn bg-gradient-light mb-0 js-btn-prev float-end me-3" type="button" title="Prev">{{ __('page.previous') }}</button>
                                            </div>

                                            <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="btn-js-next-2">{{ __('page.next') }}</button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <!--single form panel-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{ __('page.your-transaction-status') }}</h5>
                                            <p>{{ __('page.your-trader-trasaction-status-here-you-can-find-olny-your-last-wallet-to-transaction-status-you-also-find-your-all-transaction-report-in-reports-area') }}</p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row text-start">
                                            <div class="col-6 mx-auto">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <th>{{ __('page.amount') }}</th>
                                                            <th id="last-amount">{{($last_transaction)?$last_transaction->amount:'---'}}</th>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __('page.transaction-id') }}</th>
                                                            <th id="last-txn-id">{{($last_transaction)?$last_transaction->txnid:'---'}}</th>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __('page.status') }}</th>
                                                            <th id="last-status">
                                                                @php
                                                                $status = '';
                                                                $badge = '';
                                                                @endphp
                                                                @if($last_transaction)
                                                                @if($last_transaction->status == 'A')
                                                                @php
                                                                $status = 'Approved' ;
                                                                $badge = 'success';
                                                                @endphp
                                                                @elseif($last_transaction->status == 'P')
                                                                @php
                                                                $status = 'Pending';
                                                                $badge = 'dark';
                                                                @endphp
                                                                @else
                                                                @php
                                                                $status = 'Decline' ;
                                                                $badge = 'warning';
                                                                @endphp
                                                                @endif
                                                                @endif
                                                                <span class="badge rounded-pill badge-{{$badge}} badge-sm">{{$status}}</span>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mx-auto mt-4 col-6">
                                            <button class="btn bg-gradient-light mb-0 js-btn-prev float-end" type="button" title="Prev">{{ __('page.previous') }}</button>
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
<script src="{{asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-select2.js')}}"></script>
@stop
@section('page-js')
<script src="{{asset('trader-assets/assets/js/plugins/multistep-form.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/scripts/pages/wallet-to-account.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/scripts/pages/get-data-on-input.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/scripts/pages/file-upload-with-form.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js')}}"></script>
<script src="{{asset('common-js/select2-get-trader.js')}}"></script>
<script>
    filter_user(0, "recipient", "traders");
    submit_wait("#btn-submit-request");
    $("#trader-transfer-form").trigger("reset");
    $("#op").val('step-1');
    // resending otp
    $(document).on("click", "#btn-js-next-resend", function() {
        $("#op").val('resend');
        $(this).html($(this).data('loading'));
        $("#btn-resend-code").trigger('click');
    });
    // disable submit button
    $(document).on('click','#btn-submit-request',function () {
        $(this).prop('disabled',true);
        setTimeout(() => {
            $(this).prop('disabled',false);
        }, 30000);
    });
    function trader_transfer_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Trader to Trader Fund Transfer');
            $("#trader-transfer-form").trigger('reset');
            $("#last-amount").text(data.last_transaction.amount);
            $("#last-txn-id").text(data.last_transaction.txnid);
            let status = '';
            if (data.last_transaction.status === 'A') {
                status = 'Approved';
            } else if (data.last_transaction.status === 'P') {
                status = 'Pending';
            } else {
                status = 'Decline';
            }
            $("#last-status").find('.badge').removeClass('badge-success, badge-warning').addClass('badge-dark').text(status);
            $("#btn-js-next-2").trigger("click");
            $.validator("trader-transfer-form", data.errors);
        }
        if (data.status == false) {
            notify('error', data.message, 'Trader to Trader Fund Transfer');
            $.validator("trader-transfer-form", data.errors);
        }
        // validation status
        if (data.valid_status == false) {
            notify('error', data.message, 'Trader to Trader Fund Transfer');
            $.validator("trader-transfer-form", data.errors);
        }

        // seding otp status
        if (data.otp_send == true) {
            $("#op").val('step-2');
            $("#btn-js-next").trigger('click');
            $("#btn-js-next-resend").html($("#btn-js-next-resend").data('label'));
        }
        if (data.otp_status == false) {
            notify('error', data.message, 'OTP Errors');
            $.each(data.errors, function(index, value) {
                $("input[name=" + index + "]").addClass('is-invalid');
            });
        }
        submit_wait("#btn-submit-request", data.submit_wait);
        $('.multisteps-form__panel').height_control()
    }
    // disabled button
    $(document).on("click", "#btn-submit-request-final", function() {
        $(this).prop('disabled', true);
    });
    $(document).on("keyup", ".otp-value", function() {
        let $value = $(this).val();
        if ($value != "") {
            $(this).addClass('is-valid').removeClass('is-invalid');
            $(this).closest(".col").next(".col").find(".otp-value").focus();
        } else {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
    });
    // otp click and disbaled btn js 
    $(document).on('click', '#otp_btn', function() {
        $("#btn-submit-request-final").prop('disabled', true);
        setTimeout(function() {
            $("#btn-submit-request-final").prop('disabled', false);
        }, 30000);
    });
</script>
@stop