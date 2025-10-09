@extends('layouts.admin-layout')
@section('title', 'Fund Management')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/fonts/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/jstree.min.css') }}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<!-- number input -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-tree.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('common-css/search-dropdown.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css') }}">
<style>
    .has-error {
        margin-bottom: 1.5rem;
    }

    .dark-layout .dropdown-content {
        background-color: #283046;
        border-color: #404656;
        border-radius: 6px;
    }

    .dropdown-content {
        background-color: #fff;
        border-color: #d8d6de;
        border-radius: 6px;
    }

    .dark-layout .dropdown-content a:hover {
        background-color: #404656;
        color: #fff;
    }

    .dark-layout .dropdown-content a {
        color: #b4b7bd;
    }

    #myInput:focus {
        outline: none;
    }

    .dark-layout #myInput {
        background-image: url('searchicon.png');
        border-bottom: 1px solid;
        border-color: #404656;
        border-radius: 6px;
    }

    .al-fixed-input-error .has-error {
        position: absolute;
        left: auto;
        bottom: auto;
    }

    .position-relative.al-fixed-input-error-select2 {
        margin-bottom: 15px;
    }

    .position-relative.al-fixed-input-error-select2 .has-error {
        position: absolute;
        bottom: -20px;
        left: 0;
    }
</style>
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('finance.Fund Management') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('finance.home') }}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{ __('finance.Finance') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('finance.Fund Management') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                        <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#"><i class="me-1" data-feather="info"></i>
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-12">
                    <div class="card">
                        <div class="card-header d-flex">
                            <h4 id="user-type" class="text-capitalize">Trader</h4>
                            <h5 id="user-name-top" class="text-capitalize">---</h5>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="rounded ms-1 dt-trader-img img-finance">
                                <div class="h-100">
                                    <img class="img img-fluid bg-light-primary img-trader-admin user-avatar" src="{{ asset('admin-assets/app-assets/images/avatars/' . $avatar) }}" alt="avatar">
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <!-- wallet balance -->
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="me-1" data-feather="dollar-sign" class="font-medium-2"></i>
                                    <span> {{ __('finance.Wallet Balance') }}</span>
                                    <span class="badge bg-primary rounded-pill ms-auto" id="wallet-balance">0</span>
                                </li>
                                <!-- last deposit -->
                                <li class="list-group-item d-flex align-items-center" id="list-last-deposit">
                                    <i class="me-1" data-feather="dollar-sign" class="font-medium-2"></i>
                                    <span>{{ __('finance.Last Deposit') }}</span>
                                    <span class="badge bg-danger rounded-pill ms-auto" id="last-deposit-status" style="display:none">{{ __('finance.Pending') }}</span>
                                    <span class="badge bg-primary rounded-pill ms-auto" id="last-deposit">0</span>
                                </li>
                                <!-- last deposit date -->
                                <li class="list-group-item align-items-center" id="last-d-date-list" style="display:none">
                                    <i class="me-1" data-feather="dollar-sign" class="font-medium-2"></i>
                                    <span>{{ __('finance.Last Deposit Date') }}</span>
                                    <span class="rounded-pill ms-auto" id="last-deposit-date">27 june 2022</span>
                                </li>
                                <!-- last withdraw -->
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="me-1" data-feather="dollar-sign" class="font-medium-2"></i>
                                    <span>{{ __('finance.Last Withdraw') }}</span>
                                    <span class="badge bg-success rounded-pill ms-auto" id="last-withdraw-status" style="display:none">{{ __('finance.Approved') }}</span>
                                    <span class="badge bg-primary rounded-pill ms-auto" id="last-withdraw">0</span>
                                </li>
                                <!-- last withdraw date -->
                                <li class="list-group-item align-items-center" id="last-w-date-list" style="display:none">
                                    <i class="me-1" data-feather="dollar-sign" class="font-medium-2"></i>
                                    <span>{{ __('finance.Last Withdraw Date') }}</span>
                                    <span class=" ms-auto" id="last-withdraw-date">27 june 2022</span>
                                </li>
                                <!-- last credit -->
                                <li class="list-group-item d-flex align-items-center d-none">
                                    <i class="me-1" data-feather="dollar-sign" class="font-medium-2"></i>
                                    <span> {{ __('finance.Last Credit') }}</span>
                                    <span class="badge bg-primary rounded-pill ms-auto">0</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @if (Auth::user()->hasDirectPermission('create fund management'))
                <div class="col-md-8 col-sm-8 col-12">
                    <!-- Modern Horizontal Wizard -->
                    <section class="modern-horizontal-wizard">
                        <div class="bs-stepper wizard-modern modern-wizard-example">
                            <div class="bs-stepper-header">
                                <div class="step" data-target="#account-details-modern" role="tab" id="account-details-modern-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="file-text" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Deposit</span>
                                            <span class="bs-stepper-subtitle">Add Balance to account</span>
                                        </span>
                                    </button>
                                </div>
                                <div class="line">
                                    &nbsp;
                                </div>
                                <div class="step" data-target="#personal-info-modern" role="tab" id="personal-info-modern-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="user" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Withdraw</span>
                                            <span class="bs-stepper-subtitle">Deduct Balance From account</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <!-- stepper content -->
                            <div class="bs-stepper-content">
                                <!-- add balance to client wallet -->
                                <form id="account-details-modern" action="{{route('admin.finance-fund-management.deposit')}}" method="post" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger">
                                    @csrf
                                    <div class="content-header">
                                        <h5 class="mb-0">Deposit</h5>
                                        <small class="text-muted">Add balance to account.</small>
                                    </div>
                                    <!-- Amount -->
                                    <div class="mb-1 row">
                                        <label for="amount" class="col-sm-3 col-form-label">{{ __('finance.Amount') }}</label>
                                        <div class="col-sm-9">
                                            <div class="input-group client-type-group">
                                                <span class="input-group-text" id="basic-addon1"><i data-feather='dollar-sign'></i></span>
                                                <input type="text" id="amount" class="form-control" name="amount" placeholder="0.00" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- client -->
                                    <div class="mb-1 row">
                                        <label for="trader" class="col-sm-3 col-form-label">{{ __('finance.Trader') }}</label>
                                        <div class="col-sm-9 fg">
                                            <select class="max-length form-select select2-trader" id="trader" name="trader">

                                            </select>
                                        </div>
                                    </div>
                                    <!-- trading account -->
                                    <div class="mb-1 row">
                                        <label for="trading_account" class="col-sm-3 col-form-label">{{ __('finance.Trading Account') }}</label>
                                        <div class="col-sm-9 fg">
                                            <select class="select2 form-select" id="trading_account" name="trading_account">
                                                <option value="">{{ __('finance.Select an account') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- method -->
                                    <div class="mb-1 row" id="method-row">
                                        <label for="method" class="col-sm-3 col-form-label">{{ __('finance.Method') }}</label>
                                        <div class="col-sm-9 fg">
                                            <select class="select2 form-select" id="method" name="transaction_method">
                                                <option value="" selected>{{ __('finance.Select a method') }}
                                                </option>
                                                <option value="cash">{{ __('finance.Cash Deposit') }}</option>
                                                <option value="Cash Back">{{ __('finance.Voucher Deposit') }}</option>
                                                <option value="trc20">{{ __('Usdt Trc20 Deposit') }}</option>
                                                <option value="localbank">{{ __('Local Bank Deposit') }}</option>
                                                <option value="bank">{{ __('finance.Bank Deposit') }}</option>
                                                <option value="bitcoin">{{ __('finance.Bitcoin Deposit') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- expire date -->
                                    <div class="mb-1 row" id="expire-row" style="display: none;">
                                        <label for="expire_date" class="col-sm-3 col-form-label">{{__('finance.Expire Date')}}</label>
                                        <div class="col-sm-9">
                                            <div class="input-group client-type-group">
                                                <span class="input-group-text" id="basic-addon1"><i data-feather='calendar'></i></span>
                                                <input type="text" id="expire_date" name="expire_date" class="form-control flatpickr-human-friendly" placeholder="October 14, 2022" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- note -->
                                    <div class="mb-1 row mt-3">
                                        <label for="client-type" class="col-sm-3 col-form-label">Note
                                            &#40;Optional&#41;</label>
                                        <div class="col-sm-9">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-floating mb-0">
                                                        <textarea data-length="191" name="note" class="form-control char-textarea" id="textarea-counter" rows="3" placeholder="Counter" style="height: 100px"></textarea>
                                                        <label for="textarea-counter">{{ __('finance.Write a note') }}</label>
                                                    </div>
                                                    <small class="textarea-counter-value float-end"><span class="char-count">0</span> / 191 </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- submit button -->
                                    <div class="d-flex justify-content-between mt-2">
                                        <span>&nbsp;</span>
                                        <button class="btn btn-primary float-end" type="button" id="submit-request" data-label="Submit Request" data-form="account-details-modern" data-el="fg" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="createCallBackDeposit" data-btnid="submit-request" data-i18n="Submit Request" onclick="_run(this)" style="width:200px">Submit
                                            Request</button>
                                    </div>
                                </form>
                                <!-- withdraw form -->
                                <form id="personal-info-modern" action="{{route('admin.finance-fund-management.withdraw')}}" method="post" class="content" role="tabpanel" aria-labelledby="personal-info-modern-trigger">
                                    @csrf
                                    <div class="content-header">
                                        <h5 class="mb-0">Withdraw</h5>
                                        <small>Balance deduct from trading account.</small>
                                    </div>
                                    <!-- Amount -->
                                    <div class="mb-1 row">
                                        <label for="amount" class="col-sm-3 col-form-label">{{ __('finance.Amount') }}</label>
                                        <div class="col-sm-9">
                                            <div class="input-group client-type-group">
                                                <span class="input-group-text" id="basic-addon1"><i data-feather='dollar-sign'></i></span>
                                                <input type="text" id="amount-withdraw" class="form-control" name="amount" placeholder="0.00" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- client -->
                                    <div class="mb-1 row">
                                        <label for="trader" class="col-sm-3 col-form-label">{{ __('finance.Trader') }}</label>
                                        <div class="col-sm-9 fg">
                                            <select class="max-length form-select select2-trader" id="trader-withdraw" name="trader">

                                            </select>
                                        </div>
                                    </div>
                                    <!-- trading account -->
                                    <div class="mb-1 row">
                                        <label for="trading_account_withdraw" class="col-sm-3 col-form-label">{{ __('finance.Trading Account') }}</label>
                                        <div class="col-sm-9 fg">
                                            <select class="select2 form-select" id="trading_account_withdraw" name="trading_account">
                                                <option value="">{{ __('finance.Select an account') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- method -->
                                    <div class="mb-1 row" id="method-row">
                                        <label for="method" class="col-sm-3 col-form-label">{{ __('finance.Method') }}</label>
                                        <div class="col-sm-9 fg">
                                            <select class="select2 form-select" id="method_withdraw" name="transaction_method">
                                                <option value="" selected>{{ __('finance.Select a method') }}</option>
                                                <option value="cash">Cash withdraw</option>
                                                <option value="voucher">Voucher withdraw</option>
                                                <option value="skrill">Skill withdraw</option>
                                                <option value="neteller">Neteller withdraw</option>
                                                <option value="bank">Bank Withdraw</option>
                                                <option value="bitcoin">Bitcoin</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- note -->
                                    <div class="mb-1 row mt-3">
                                        <label for="client-type" class="col-sm-3 col-form-label">Note
                                            &#40;Optional&#41;</label>
                                        <div class="col-sm-9">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-floating mb-0">
                                                        <textarea data-length="191" name="note" class="form-control char-textarea" id="textarea-counter-withdraw" rows="3" placeholder="Counter" style="height: 100px"></textarea>
                                                        <label for="textarea-counter">{{ __('finance.Write a note') }}</label>
                                                    </div>
                                                    <small class="textarea-counter-value float-end"><span class="char-count">0</span> / 191 </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- submit button -->
                                    <div class="d-flex justify-content-between mt-2">
                                        <span>&nbsp;</span>
                                        <button class="btn btn-primary float-end" type="button" id="submit-request-withdraw" data-form="personal-info-modern" data-el="fg" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="createCallBackWithdraw" data-btnid="submit-request-withdraw" data-i18n="Submit Request" onclick="_run(this)" style="width:200px">Submit Request</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                    <!-- /Modern Horizontal Wizard -->
                </div>
                @else
                <div class="col-xl-8 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            @include('errors.permission')
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->
@stop
<!-- BEGIN: vendor JS -->
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js') }}"></script>
<!-- js tree -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/jstree.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')

<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js') }}"></script>

<script>
    // *******************************************************************
    // select 2 for client
    // get IB details
    $(document).on('keypress', '.select2-search__field', function(e) {
        if (e.which === 13) {
            e.preventDefault();
        }
    });

    // $('#recipient').select2('destroy')
    // select2 client for deposit
    $(".select2-trader").select2({
        tags: false,
        // dropdownParent: $('#sub-ib-modal'),
        templateResult: formatOption,
        selectOnClose: true,
        language: {
            noResults: function() {
                return "Enter Email to search here";
            }
        },
        ajax: {
            url: "/get-trader/forfund/management",
            type: "GET",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term, // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });
    // option description for select2
    function formatOption(option) {
        var $option = $('<div><strong>' + option.text + '</strong></div>');

        if (option.title) {
            $option.append('<div>' + option.title + '</div>');
        }

        if (option.name) {
            $option.append('<div><strong>Name: </strong>' + option.name + '</div>');
        }

        return $option;
    }

    // ********************************************************************
    // on change add balance client
    $(document).on("change", '#trader,#trader-withdraw', function() {
        var target_client = "Trader"
        $.ajax({
            url: '/admin/finance/get-client-finance',
            method: 'GET',
            dataType: 'JSON',
            data: {
                client: $(this).val(),
                client_type: target_client,
            },
            success: function(data) {
                $("#user-name-top").text(data.user_name);
                if (data.withdraw_status != "") {
                    $("#last-withdraw-status").text(data.withdraw_status).slideDown();
                }

                $("#last-withdraw").text(data.last_withdraw);
                $("#wallet-balance").text(data.wallet_balance);
                if (data.last_deposit != 0) {
                    $("#last-deposit-status").text(data.deposit_status).slideDown();
                }

                $("#last-deposit").text(data.last_deposit);

                // data for trading account
                if (data.account_options) {
                    var $selectElement = $("#trading_account, #trading_account_withdraw");
                    $selectElement.empty().append(data.account_options);
                }
            }
        })
    })

    $(document).on("change", '#deduct-balance-client', function() {
        $.ajax({
            url: '/admin/client/ib/finance-status',
            method: 'GET',
            dataType: 'JSON',
            data: {
                client: $(this).val(),
                client_type: $("#id-client-type-withdraw").val(),
            },
            success: function(data) {
                $("#user-name-top").text(data.user_name);
                if (data.last_withdraw_status != "") {
                    $("#last-withdraw-status").text(data.last_withdraw_status).slideDown();
                }

                $("#last-withdraw").text(data.last_withdraw);
                $("#wallet-balance").text(data.total_balance);
                if ($("#id-client-type").val() === 'Trader') {
                    if (data.last_deposit != 0) {
                        $("#last-deposit-status").text(data.last_deposit_status).slideDown();
                    }

                    $("#last-deposit").text(data.last_deposit);
                }
            }
        })
    })
    // submit request
    // ****************************************
    $(document).on('click', '#submit-request', function() {
        $(this).prop('disabled', true);
    })

    function createCallBackDeposit(data) {
        if (data.status) {
            notify('success', data.message, 'Fund deposit');
            $("#account-details-modern").trigger('reset');
        } else {
            notify('error', data.message, 'Fund deposit');
        }
        $.validator("account-details-modern", data.errors);
        $('#submit-request').prop('disabled', false);
    }

    function createCallBackWithdraw(data) {
        if (data.status) {
            notify('success', data.message, 'Fund withdraw');
            $("#personal-info-modern").trigger('reset');
        } else {
            notify('error', data.message, 'Fund withdraw');
        }
        $.validator("personal-info-modern", data.errors);
        $('#submit-request').prop('disabled', false);
    }

    /**
     *--------------------------------------------------
     * -- form reset and others handle in bs-stepper---
     * -------------------------------------------------
     */
    $(document).ready(_ => {
        $(".step-trigger").on("click", function() {
            $("#personal-info-modern,#account-details-modern").trigger("reset");
            $("#user-name-top,#user-name-top").text("---");
            $("#wallet-balance,#last-deposit,#last-withdraw").text("0");
            $("#last-deposit-status,#last-withdraw-status").hide();
            $("#trader,#trading_account,#method,#trader-withdraw,#trading_account_withdraw,#method_withdraw").val(null).trigger("change");
        });
    })
</script>
@stop
<!-- BEGIN: page JS -->