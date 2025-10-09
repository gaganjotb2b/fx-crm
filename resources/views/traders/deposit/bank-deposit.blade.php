@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title','Bank Deposit')
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
</style>
@stop
@section('bread_crumb')
@php use App\Services\BankService; @endphp
<!-- bread crumb -->
{!!App\Services\systems\BreadCrumbService::get_trader_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid py-4">
    <div class="row custom-height-con">
        <div class="col-12 text-center">
            <h3 class="mt-5">{{__('finance.Bank Deposit')}}</h3>
            <!--<h5 class="text-secondary font-weight-normal">{{__('page.we_need_your_information_becuase_you_make_a_transaction')}}.</h5>-->
            <div class="row d-flex justify-content-center">

                <div class="col-md-6 card p-3 border-radius-xl bg-white js-active">
                    <p>For direct deposits or bank transfers, please contact <span class="text-nowrap text-info">+44 7446 165557</span> for assistance.</p>
                    <a href="https://wa.me/+447446165557" target="_blank" class="text-success fs-4">
                        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                    </a>
                </div>

            </div>
            {{-- <div class="multisteps-form mb-5">
                <!--progress bar-->
                <div class="row">
                    <div class="col-12 col-lg-8 mx-auto my-5">
                        <div class="multisteps-form__progress">
                            <!-- progress select bank -->
                            <button disabled class="multisteps-form__progress-btn js-active" type="button" title="User Info">
                                <span>{{__('page.selete_bank')}}</span>
                            </button>
                            <!-- progress transfer request -->
                            <button disabled class="multisteps-form__progress-btn" type="button" title="Order Info">
                                <span>{{__('page.transfer_request')}}</span>
                            </button>
                            <!-- progress status -->
                            <button disabled class="multisteps-form__progress-btn" type="button" title="Order Info">
                                <span>{{__('page.status')}}</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!--form panels-->
                <div class="row">
                    <div class="col-12 col-lg-8 m-auto">
                        <div class="multisteps-form__form">
                            <form class="form-demo" action="{{route('user.deposit.bank-deposit')}}" method="post" id="bank-deposit-form">
                                <!--single form panel-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <!-- first step description and heading -->
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{__('page.let\'s_start_with_bank_selection')}}</h5>
                                            <p>{{__('page.please_select_bank_tab_for_crm_bank_selection_this_bank_account_for_where_you_transfer_money')}}.</p>
                                        </div>
                                    </div>
                                    <!-- first step form -->
                                    <div class="multisteps-form__content">
                                        <div class="row mt-3">
                                            <!-- first step form -->
                                            <div class="col-12 col-sm-12 mt-4 mt-sm-0 text-start">
                                                <!-- check deposit operation permited -->
                                                @if(\App\Services\Trader\FinanceService::check_op('deposit'))
                                                <!-- check kyc required for deposit -->
                                                @if(\App\Services\AllFunctionService::kyc_required((auth()->user()->id),'deposit') == false )
                                                <!-- check bank account have or not -->
                                                @if (count($banks) != 0)
                                                <div class="col-12 mx-auto">
                                                    <div class="form-group al-error-solve">
                                                        <label for="platform">Select A Bank</label>
                                                        <select class="form-control multisteps-form__input choice-colors" id="bank_id" name="bank_id">
                                                            <option value="">Select a bank</option>
                                                            @foreach($banks as $value)
                                                            <option value="{{($value->id)}}">{{$value->tab_name??$value->bank_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="tab-content">
                                                        <!-- <input type="hidden" name="bank_id" value=""> -->
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover mt-4">
                                                                <tr>
                                                                    <th class="w-40">{{__('page.bank-name')}}</th>
                                                                    <td class="bank-name">---</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>{{__('page.account-name')}}</th>
                                                                    <td class="account-name">---</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>{{__('page.account-number')}}</th>
                                                                    <td class="account-number">---</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>{{BankService::swift_code_label($value->bank_country)}}</th>
                                                                    <td class="swift-code">---</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>{{__('page.Routing_Number')}}</th>
                                                                    <td class="routing-number">---</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>{{__('page.bank-country')}}</th>
                                                                    <td class="bank-country">---</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>{{__('page.bank-address')}}</th>
                                                                    <td class="bank-address">---</td>
                                                                </tr>
                                                                <!-- multi currency -->
                                                                @if(\App\Services\BankService::is_multicurrency('all'))
                                                                <tr>
                                                                    <th>Currency</th>
                                                                    <?php
                                                                    $currency_setup = App\Models\CurrencySetup::where('id', $value->currency_id)->first();
                                                                    $currency = $currency_setup->currency ?? "---";
                                                                    $transaction_type = $currency_setup->transaction_type ?? "";
                                                                    ?>
                                                                    <td class="currency">{{$currency}}</td>
                                                                    <td class="d-none transaction_type">{{$transaction_type}}</td>
                                                                    <input class="currency-name" type="hidden" value="{{$currency}}">
                                                                </tr>
                                                                @endif
                                                                <tr>
                                                                    <th>{{__('group-setting.Minimum Deposit')}}</th>
                                                                    <td class="minimum-deposit">---</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Note</th>
                                                                    <td class="bank-note">---</td>
                                                                </tr>
                                                                <tr class='d-none'>
                                                                    <th>{{__('page.ific')}}</th>
                                                                    <td class="bank-ific">---</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="col-8 mx-auto">
                                                    <div class="nav-wrapper position-relative end-0">
                                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                            <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                                                            <span class="alert-text">
                                                                <strong>Warning!</strong>
                                                                <span> Currently Admin does not assign any bank account. For more information contact with the Admin</span>!
                                                            </span>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
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
                                        <div class="row  mt-4">
                                            <div class="col-12 col-sm-4">

                                            </div>
                                            <div class="col-12 col-sm-8 mt-4 mt-sm-0 text-start">
                                                <!-- check kyc required -->
                                                @if(!\App\Services\AllFunctionService::kyc_required((auth()->user()->id),'deposit'))
                                                <div class="col-8 mx-auto col-md-12">
                                                    @if (count($banks) != 0)
                                                    <!-- first step submit button -->
                                                    <button class="d-none btn bg-gradient-primary ms-auto mb-0 js-btn-next float-end bank-deposit-details" type="button" title="Next" id="btn-js-next-2" style="width: 200px">{{__('page.next')}}</button>
                                                    @endif
                                                </div>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!--single form panel-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{__('page.enter_amount_&_bank_proof')}}</h5>
                                            <p>{{__('page.please_enter_your_deposit_amount')}}</p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row mt-3">
                                            <div class="col-12 col-sm-4">
                                                <div class="avatar avatar-xxl position-relative">
                                                    <img id="platform-logo" src="{{asset('comon-icon/bank-icon.png')}}" class="border-radius-md img-thumbnail" alt="team-2">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-8 mt-4 mt-sm-0 text-start">
                                                <div class="col-8 mx-auto">
                                                    @csrf
                                                    <!-- select  deposit option-->
                                                    @if(\App\Services\systems\TransactionSettings::is_account_deposit() == true)
                                                    <div class="form-group">
                                                        <label for="deposit-options">Deposit Options</label>
                                                        <select name="deposit_option" id="deposit-options" class="form-select form-control">
                                                            <option value="account" id="account_deposit">Trading Account deposit</option>
                                                            <option value="wallet" selected id="wallet_deposit">Wallet deposit</option>
                                                        </select>
                                                    </div>
                                                    <!-- Account Number -->
                                                    <div class="form-group account_number d-none" id="trading-accounts" title="Trading account number">
                                                        <label for="account_number">Account Number</label>
                                                        <select class="select2 form-select" id="account_number" name="trading_account_number">
                                                            @foreach($trading_accounts as $value)
                                                            <option value="{{ encrypt($value->id)}}">{{ $value->account_number}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @endif

                                                    <!-- usd amount -->
                                                    <div class="form-group">
                                                        <label for="amount">{{__('page.amount')}} (USD)</label>
                                                        <input type="text" name="amount" id="amount" placeholder="0.00" class="form-control form-control-alternative currency" />
                                                    </div>
                                                    <!-- local currency amount -->
                                                    <!-- multi currency -->
                                                    @if(\App\Services\BankService::is_multicurrency('all'))
                                                    <div class="form-group local-currency-field">
                                                        <input type="hidden" name="currency" class="l_cur" value="">
                                                        <input type="hidden" class="t_type" value="">
                                                        <label for="amount-local">{{__('page.amount')}} <span class="local-currency"><span></label>
                                                        <input type="text" name="local_amount" id="amount-local" placeholder="0.00" class="form-control form-control-alternative" />
                                                    </div>
                                                    @endif
                                                    <div class="dropzone dropzone-area bank-proof-dropzone" id="id-dropzone-bank-proof" data-field="document" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your document">
                                                        <div class="dz-message">
                                                            <div class="dz-message-label">{{__('page.drop-your-document')}}.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row  mt-4">
                                            <div class="col-12 col-sm-4">

                                            </div>
                                            <div class="col-12 col-sm-8 mt-4 mt-sm-0 text-start">
                                                <div class="col-8 mx-auto">
                                                    @php
                                                    $disable = '';
                                                    $has_multi_submit = has_multi_submit('bank-withdraw',60);
                                                    if($has_multi_submit)
                                                    {
                                                    $disable = 'disabled';
                                                    }
                                                    @endphp
                                                    <button type="button" data-label="Submit Request" id="btn-submit-request" class="btn bg-gradient-primary mt-4 float-end mb-3" data-submit_wait="{{submit_wait('bank-withdraw',60)}}" {{$disable}} data-loading="<i class='fa-spin fas fa-circle-notch'></i>">{{__('page.submit-request')}}</button>
                                                </div>
                                            </div>
                                            <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next" id="btn-js-next">{{__('page.next')}}</button>
                                        </div>
                                    </div>
                                </div>
                                <!--single form panel-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{__('page.your_transaction_status')}}</h5>
                                            <p>{{__('page.your_bank_account_trasaction_status')}}</p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row text-start">
                                            <h5>{{__('page.last_transaction_status')}}</h5>
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th>{{__('page.amount')}}</th>
                                                        <th id="last-amount">{{($last_transaction)?$last_transaction->amount:'---'}}</th>
                                                    </tr>
                                                    <!-- multi currency -->
                                                    @if(\App\Services\BankService::is_multicurrency('all'))
                                                    <tr class="currency-field">
                                                        <th>Local Currency</th>
                                                        <th id="last-currency">{{ $last_transaction ? $last_transaction->currency : '---'}}</th>
                                                    </tr>
                                                    <tr class="currency-field">
                                                        <th>Local Currency Rate</th>
                                                        <th id="last-local-currency">{{ $last_transaction ? $last_transaction->local_currency : '---'}}</th>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <th>{{__('finance.Transaction Type')}}</th>
                                                        <th id="last-txn-id">{{($last_transaction)?ucwords($last_transaction->transaction_type):'---'}}</th>
                                                    </tr>
                                                    <tr>
                                                        <th>{{__('page.status')}}</th>
                                                        <th id="last-status">
                                                            @php
                                                            $status = '' ;
                                                            $badge = '';
                                                            @endphp
                                                            @if($last_transaction)
                                                            @if($last_transaction->approved_status == 'A')
                                                            @php
                                                            $status = 'Approved' ;
                                                            $badge = 'success';
                                                            @endphp
                                                            @elseif($last_transaction->approved_status == 'P')
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
                                    <div class="row">
                                        <div class="button-row d-flex mt-4 col-12">
                                            <!-- <button class="btn bg-gradient-light mb-0 js-btn-prev float-end" type="button" title="Prev">{{__('page.previous')}}</button> -->
                                            <!-- <button class="btn bg-gradient-dark ms-auto mb-0" type="button" title="Send">Send</button> -->
                                            <button class="btn bg-gradient-light mb-0 float-end"><a href="{{route('user.deposit.bank-deposit-form')}}">Go Back To Deposit</a></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> --}}
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
<script src="{{asset('trader-assets/assets/js/plugins/multistep-form.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/scripts/pages/wallet-to-account.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/scripts/pages/get-data-on-input.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/scripts/pages/file-upload-with-form.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js')}}"></script>
<script>
    // get bank details
    var selected_bank = $("#bank_id").val();
    bank_selection(selected_bank);
    $(document).on("change", "#bank_id", function() {
        bank_selection($(this).val());
    });

    function bank_selection(bank_id) {
        var bank_id = bank_id;
        console.log(bank_id);
        if (bank_id == "") {
            bank_id = 99999;
        }
        $.ajax({
            url: "/admin-bank-details/get/" + bank_id,
            method: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $('.bank-name').html(data.bank_name);
                $('.account-name').html(data.account_name);
                $('.account-number').html(data.account_number);
                $('.swift-code').html(data.swift_code);
                $('.routing-number').html(data.routing !== "" ? data.routing : "---");
                $('.bank-country').html(data.bank_country);
                $('.bank-address').html(data.bank_address);
                $('.minimum-deposit').html(data.minimum_deposit);
                $('.bank-note').html(data.note);
                $('.bank-ific').html(data.ifsc_code);
                $('.currency').html(data.loc_currency);
                $('.l_cur').val(data.loc_currency);
                $('.local-currency').html("(" + data.loc_currency + ")");
                if (data.account_number != "---") {
                    $('.bank-deposit-details').removeClass('d-none');
                } else {
                    $('.bank-deposit-details').addClass('d-none');
                }
            }
        });
    }

    $(document).on('click', '.bank-deposit-details', function() {
        var currency_name = $('.tab-pane.active').find('.currency-name').val();
        if (currency_name == "---") {
            $('.local-currency-field').addClass('d-none');
        } else {
            $('.local-currency-field').removeClass('d-none');
        }
    });
    var currency = $('.tab-pane.active').find(".currency").html();
    var transaction_type = $('.tab-pane.active').find(".transaction_type").html();
    $('.local-currency').html("(" + currency + ")");
    $('.l_cur').val(currency);
    $('.t_type').val(transaction_type);
    $(document).on('click', '.tab-bank.active', function() {
        var currency = $('.tab-pane.active').find(".currency").html();
        var transaction_type = $('.tab-pane.active').find(".transaction_type").html();
        $('.local-currency').html("(" + currency + ")");
        $('.l_cur').val(currency);
        $('.t_type').val(transaction_type);
    });
    submit_wait("#btn-submit-request");

    // id proof--------------
    file_upload(
        "/user/deposit/bank-deposit-request", //<--request url for proccessing
        false, //<---auto process true or false
        ".bank-proof-dropzone", //<---dropzones selectore
        "bank-deposit-form", //<---form id/selectore
        "#btn-submit-request", //<---submit button selectore
        "Bank Deposit", //<---Notification Title
        true //<--multple wizer true
    );
    // chnage bank id on hidden input

    // $("input[name=bank_id]").val($('.selected-tab').data('bank'));
    // $(document).on("click", ".tab-bank", function() {
    //     $("input[name=bank_id]").val($(this).data('bank'));
    // });

    // usd to local currency exchange
    $(document).on("input", "#amount", function() {
        currency = $('.l_cur').val();
        transaction_type = $('.t_type').val();
        let amount = $(this).val();
        if (amount == "") {
            amount = 0;
        }
        $.ajax({
            url: "/currency/get-currency/" + amount + "/from/USD/to/" + currency + "/transaction-type/" + transaction_type,
            method: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $("#amount-local").val(data);
            }
        });
    });
    // local currency to usd exchange
    $(document).on("input", "#amount-local", function() {
        currency = $('.l_cur').val();
        transaction_type = $('.t_type').val();
        let amount = $(this).val();
        if (amount == "") {
            amount = 0;
        }
        $.ajax({
            url: "/currency/get-currency/" + amount + "/from/" + currency + "/to/USD/transaction-type/" + transaction_type,
            method: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $("#amount").val(data);
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        var tabList = $('#tabList');
        var tabItems = tabList.find('.nav-item');

        var totalTabWidth = 0;
        tabItems.each(function() {
            totalTabWidth += $(this).outerWidth();
        });

        var containerWidth = tabList.parent().width();

        if (totalTabWidth > containerWidth) {
            var scrollOffset = 30; // Adjust this value as needed

            tabList.css('width', totalTabWidth + scrollOffset + 'px');

            $('.nav-wrapper').css({
                'overflow-x': 'auto',
                'scroll-behavior': 'smooth',
                'scrollbar-width': 'thin',
                'scrollbar-color': '#888 #f5f5f5'
            });
        }

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