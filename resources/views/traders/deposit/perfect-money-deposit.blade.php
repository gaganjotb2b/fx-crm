@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title','Perfect Money Deposit')
@section('page-css')
@if(App\Services\systems\VersionControllService::check_version()==='lite')
<link id="pagestyle" href="{{ asset('trader-assets/assets/css/soft-ui-dashboard.css?v=1.0.8') }}" rel="stylesheet" />
@endif
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

    .card-congratulations {
        background: linear-gradient(118deg, var(--custom-primary), var(--custom-primary));
        color: #fff;
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
            <h3 class="mt-5">{{__('page.deposit_via_perfect_money')}}</h3>
            <h5 class="text-secondary font-weight-normal">{{__('page.we_need_your_information_becuase_you_make_a_transaction')}}.</h5>
            <div class="multisteps-form mb-5 mt-5">
                <!--form panels-->
                <div class="row">
                    <div class="col-12 col-lg-8 m-auto ">
                        <div class="card pt-5 pb-5">
                            <div class="col-8 mx-auto">
                                <!-- check kyc required for deposit -->
                                <?php

                                use Illuminate\Support\Facades\Request;

                                $website = Request::is('user/deposit/perfect-money-deposit');
                                // if payment status
                                ?>
                                <!-- check deposit permited -->
                                @if(\App\Services\Trader\FinanceService::check_op('deposit'))
                                <!-- check kyc required -->
                                @if(\App\Services\AllFunctionService::kyc_required((auth()->user()->id),'deposit') == false )
                                <div class="pt-2">
                                    @if ($pm_deposit_status == 0)
                                    <form id="pm_form" action="https://perfectmoney.is/api/step1.asp" method="post" role="form" class="form-demo" style="width:100%;">
                                        <!-- @csrf -->
                                        <div class="col-12 col-sm-12 mx-auto mt-4 mt-sm-0 text-start">
                                            <!-- hidden field  -->
                                            <input type="hidden" name="refresh" value="1" />
                                            <input type="hidden" name="PAYEE_ACCOUNT" value="<?= isset($perfectMoney->info) ? $info->PAYEE_ACCOUNT : null; ?>">
                                            <input type="hidden" name="PAYEE_NAME" value="<?= isset($perfectMoney->info) ? $info->PAYEE_NAME : null; ?>">
                                            <input type="hidden" name="PAYMENT_UNITS" value="USD">
                                            <input type="hidden" name="STATUS_URL" value="{{ route('user.deposit.perfect-money-deposit-process') }}">
                                            <input type="hidden" name="PAYMENT_URL" value="{{ route('user.deposit.cancel-perfect-money-deposit') }}">
                                            <input type="hidden" name="PAYMENT_URL_METHOD" value="GET">
                                            <input type="hidden" name="NOPAYMENT_URL" value="{{ route('user.deposit.cancel-perfect-money-deposit') }}">
                                            <input type="hidden" name="NOPAYMENT_URL_METHOD" value="GET">


                                            <input type="hidden" id="BAGGAGE_FIELDS" name="BAGGAGE_FIELDS" value="USER_ID">
                                            <input type="hidden" name="USER_ID" value="<?= (isset(auth()->user()->id)) ? auth()->user()->id : "" ?>">

                                            <div class="form-group">
                                                <label for="email">{{__('page.email')}}</label>
                                                <input type="email" class="form-control multisteps-form__input" name="PAYMENT_ID" value="<?= auth()->user()->email ?>">
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input mt-1" type="checkbox" id="platform-check" value="" />
                                                    <label>Platform</label>
                                                </div>
                                            </div>
                                            <div class="form-group d-none" id="platform-option">
                                                <!-- platform select option will be gone here-->
                                            </div>

                                            <div class="form-group d-none" id="account-number">
                                                <!-- account number select option will be gone here -->
                                            </div>

                                            <!-- select  deposit option-->
                                            @if(\App\Services\systems\TransactionSettings::is_account_deposit() == true)
                                            <div class="form-group">
                                                <label for="deposit-options">Deposit Options</label>
                                                <select name="deposit_option" id="deposit-options" class="form-select form-control">
                                                    <option value="account" id="account_deposit">Account deposit</option>
                                                    <option value="wallet" selected id="wallet_deposit">Wallet deposit</option>
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

                                            <div class="form-group">
                                                <label for="amount">{{__('page.amount')}}</label>
                                                <input type="text" class="form-control multisteps-form__input" name="PAYMENT_AMOUNT" placeholder="$ 0.00">
                                            </div>

                                            <div class="form-group mt-4">
                                                <button id="pm_submit" type="button" name="PAYMENT_METHOD" class="btn bg-gradient-primary float-end">Submit Request</button>
                                            </div>
                                        </div>
                                    </form>
                                    @elseif($pm_deposit_status == 1 && $is_success)
                                    <style>
                                        .card-congratulations {
                                            background: linear-gradient(118deg, var(--custom-primary), var(--custom-primary));
                                            color: #fff;
                                        }
                                    </style>
                                    <!-- Congratulations Card -->
                                    <div class="col-12">
                                        <div class="card pt-5 pb-5 card-congratulations">
                                            <div class="card-body text-center">
                                                <img src="{{ asset('admin-assets/app-assets/images/elements/decore-left.png') }}" class="congratulations-img-left" alt="card-img-left" />
                                                <img src="{{ asset('admin-assets/app-assets/images/elements/decore-right.png') }}" class="congratulations-img-right" alt="card-img-right" />
                                                <div class="text-center">
                                                    <h2 class="mb-1 text-white">Congratulations!</h2>

                                                </div>
                                                <button class="btn btn-light text-dark mt-5"><a href="{{route('user.deposit.perfect-money-deposit')}}">Go Back To Deposit</a></button>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <!-- Congratulations Card -->
                                    <div class="col-12">
                                        <div class="card pt-5 pb-5 card-congratulations">
                                            <div class="card-body text-center">
                                                <img src="{{ asset('admin-assets/app-assets/images/elements/decore-left.png') }}" class="congratulations-img-left" alt="card-img-left" />
                                                <img src="{{ asset('admin-assets/app-assets/images/elements/decore-right.png') }}" class="congratulations-img-right" alt="card-img-right" />
                                                <div class="text-center">
                                                    <h2 class="mb-1 text-danger">Deposit Failed!</h2>

                                                </div>
                                                <button class="btn btn-light text-dark mt-5"><a href="{{route('user.deposit.perfect-money-deposit')}}">Go Back To Deposit</a></button>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @else
                                <!-- warning kyc required -->
                                <div class="col-8 mx-auto">
                                    <div class="alert alert-warning" role="alert">
                                        <strong>Warning!</strong> KYC Required for deposit
                                    </div>
                                </div>
                                @endif
                                @else
                                <!-- warning deposit operation -->
                                <div class="col-8 mx-auto">
                                    <div class="alert alert-warning" role="alert">
                                        <strong>Warning!</strong> You are not permited to deposit! Please contact with <strong> {{config('app.name')}} </strong> Support.
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footer')
</div>
@stop
@section('corejs')
<script src="{{asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js')}}"></script>
@stop
@section('page-js')
<!-- get trading account -->
<!-- <script src="{{asset('trader-assets/assets/js/scripts/pages/wallet-to-account.js')}}"></script> -->
<script src="{{asset('trader-assets/assets/js/scripts/pages/get-data-on-input.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/scripts/pages/file-upload-with-form.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js')}}"></script>
<script>
    $('#platform').removeAttr('required');
    $("#pm_submit").click(function() {
        var postData = $('#pm_form').serializeArray();
        var submit = true;
        $.each(postData, function(k, v) {
            if (v.value == '') {
                notify('error', v.name + " is required!");
                submit = false;
                return false;
            }
        });

        if (submit) {
            $("#pm_form").submit();
        }
    });


    // platform checkuncheck 
    $(document).on('change', '#platform-check', function() {
        if ($(this).is(':checked')) {
            $('#platform-option').removeClass('d-none');
            $('#platform-option').html('<select class="form-control multisteps-form__input choice-colors" id="platform" name="PLATFORM"><option value="">Choose A Platform</option><option value="MT4">MT4</option><option value="MT5">MT5</option></select>');

            $('#account-number').removeClass('d-none');

            var oldB = $("#BAGGAGE_FIELDS").val();
            console.log(oldB);
            var ms = oldB + " " + "ACCOUNT_NUMBER";
            console.log(ms);
            $("#BAGGAGE_FIELDS").val(ms);
        } else {
            $('#platform-option').addClass('d-none');
            $('#account-number').addClass('d-none');
            $("#BAGGAGE_FIELDS").val("USER_ID");
        }
    });

    // change server-------------------
    $(document).on("change", "#platform", function() {
        let platform = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: 'post',
            dataType: 'json',
            url: '/user/transfer/get-meta-logo',
            data: {
                platform: platform
            },
            success: function(data) {
                // $("#account").html(data.option);
                $("#account-number").html('<label for="amount">{{__("page.reciever_account_number")}}</label><select class="form-control multisteps-form__input choice-colors" id="account" name="ACCOUNT_NUMBER">' + data.option + '</select>');
            }
        });
    });
    $(document).on("click", "#account_deposit", function() {
        $('.account_number').removeClass('d-none');
    });
    $(document).on("click", "#wallet_deposit", function() {
        $('.account_number').addClass('d-none');
    });

</script>
@stop