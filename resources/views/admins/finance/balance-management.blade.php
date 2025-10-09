@extends('layouts.admin-layout')
@section('title', 'Balance Management')
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
    .clientDetailsBtn {
        display: none;
    }
    /* OffCanvas */
    .offcanvas-end {
        width: 400px !important;
    }
    @media (max-width: 768px) {
        .col-md-6.d-sm-mt {
            width: 96%;
            margin-top: -2.5rem !important;
        }

        .col-md-6.d-sm-method-mt {
            margin-top: 3rem !important;
        }

        .clientDetailsBtn {
            display: block;
        }
        #id-client-type{
            width : 96%;
        }
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
                        <h2 class="content-header-title float-start mb-0">{{ __('finance.Balance Management') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('finance.home') }}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{ __('finance.Finance') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('finance.Balance Management') }}</li>
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
                <div class="col-lg-4 d-lg-block d-md-none d-sm-none d-none">
                    <div class="card">
                        <div class="card-header d-flex">
                            <h4 id="user-type" class="text-capitalize">---</h4>
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
                @if (Auth::user()->hasDirectPermission('create balance management'))
                <div class="col-lg-8 col-md-12 col-sm-12">
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
                                            <span class="bs-stepper-subtitle">Add Balance to Client Wallet</span>
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
                                            <span class="bs-stepper-subtitle">Deduct Balance From Client Wallet</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <!-- stepper content -->
                            <div class="bs-stepper-content">
                                <!-- add balance to client wallet -->
                                <form id="account-details-modern" action="{{route('admin.finance-balance.add')}}" method="post" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger">
                                    @csrf
                                    <div class="d-flex justify-content-between">
                                        <div class="content-header">
                                            <h5 class="mb-0">Deposit</h5>
                                            <small class="text-muted">Add balance to client wallet.</small>
                                        </div>
                                        <button class="d-lg-none d-md-block btn btn-sm btn-primary clientDetailsBtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEnd" aria-controls="offcanvasEnd">Client Details</button>
                                    </div>
                                    <!-- row client type -->
                                    <div class="mt-3 row mb-3">
                                        <div class="col-md-6 fg position-relative al-fixed-input-error">
                                            <label for="id-client-type" class="col-form-label">Client type</label>
                                            <div class="form-group ">
                                                <select name="client_type" id="id-client-type" class="form-select">
                                                    <option value="">Select client type</option>
                                                    <option value="IB">IB</option>
                                                    <option value="Trader">Trader</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- row client and method -->
                                    <div class=" row">
                                        <div class="col-md-6 d-sm-mt fg position-relative al-fixed-input-error">
                                            <label for="id-client-email" class="col-form-label">Client</label>
                                            <div id="myDropdown" class=" form-group ">
                                                <select name="client" id="add-balance-client" class="select2-trader form-select"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="method" class="col-form-label">{{ __('finance.Method') }}</label>
                                            <select class="select2 form-select" id="method" name="transaction_method">
                                                <option value="" selected>{{ __('finance.Select a method') }}</option>
                                                <option value="cash">{{ __('finance.Cash Deposit') }}</option>
                                                <option value="cashback">{{ __('Cash Back Reward') }}</option>
                                                <option value="ib commission">{{ __('Ib Commission') }}</option>
                                                <option value="trc20">{{ __('Trc20 Deposit') }}</option>
                                                <option value="localbank">{{ __('Local Bsank Deposit') }}</option>
                                                <option value="bitcoin">{{ __('finance.Bitcoin Deposit') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- bank accounts -->
                                    <div class="row" id="bank-row" style="display:none">
                                        <label for="client-bank" class="col-sm-3 col-form-label">{{ __('finance.Bank') }}</label>
                                        <div class="col-sm-9">
                                            <select class="select2 form-select" id="client-bank" name="bank">
                                                <!-- load by ajax -->
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Amount  -->
                                    <div class=" row">
                                        <!-- amount -->
                                        <div class="col-sm-6">
                                            <label for="client-type" class="col-form-label">{{ __('finance.Amount') }}</label>
                                            <div class="input-group client-type-group">
                                                <span class="input-group-text" id="basic-addon1"><i data-feather='dollar-sign'></i></span>
                                                <input type="text" name="amount" class="form-control" id="amount" placeholder="0.00" />
                                            </div>
                                        </div>
                                        <!-- invoice code -->
                                        <div class="col-sm-6">
                                            <label for="client-type" class="col-form-label">{{ __('finance.Invoice-code') }}(Optional)</label>
                                            <div class="input-group client-type-group">
                                                <span class="input-group-text" id="basic-addon1"><i data-feather='credit-card'></i></span>
                                                <input type="text" name="invoice_code" class="form-control" id="colFormLabel" placeholder="Invoce ID or Transaction Number" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- note -->
                                    <div class="row mt-3">
                                        <div class="col-sm-12">
                                            <div class="form-floating mb-0">
                                                <textarea data-length="100" name="note" class="form-control char-textarea" id="textarea-counter" rows="3" placeholder="Counter" style="height: 100px"></textarea>
                                                <label for="textarea-counter">{{ __('finance.Write a note') }} &#40;Optional&#41;</label>
                                            </div>
                                            <small class="textarea-counter-value float-end"><span class="char-count">0</span> / 100 </small>
                                        </div>
                                    </div>
                                    <!-- submit button -->
                                    <div class="d-flex justify-content-between mt-2">
                                        <span>&nbsp;</span>
                                        @php
                                        $disable = '';
                                        $has_multi_submit = has_multi_submit('finance-balance', 15);
                                        if ($has_multi_submit) {
                                        $disable = 'disabled';
                                        }
                                        @endphp
                                        <button class="btn btn-primary" data-label="Save" type="button" data-submit_wait="{{ submit_wait('finance-balance', 15) }}" id="save-wallet-balance" data-form="account-details-modern" data-el="fg" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="createCallBack" onclick="_run(this)" data-btnid="save-wallet-balance" {{ $disable }}>
                                            <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Save</span>
                                        </button>
                                    </div>
                                </form>
                                <!-- withdraw form -->
                                <form id="personal-info-modern" action="{{route('admin.finance-balance.deduct')}}" method="post" class="content" role="tabpanel" aria-labelledby="personal-info-modern-trigger">
                                    @csrf
                                    <div class="d-flex justify-content-between">
                                        <div class="content-header">
                                            <h5 class="mb-0">Withdraw</h5>
                                            <small>Balance deduct from client wallet.</small>
                                        </div>
                                        <button class="d-lg-none d-md-block btn btn-sm btn-primary clientDetailsBtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEnd" aria-controls="offcanvasEnd">Client Details</button>
                                    </div>
                                    <!-- client/deduct -->
                                    <div class="mt-3 row">
                                        <div class="col-md-6 fg position-relative al-fixed-input-error">
                                            <label for="id-client-type-withdraw" class="col-form-label">Client type</label>
                                            <div class="form-group ">
                                                <select name="client_type" id="id-client-type-withdraw" class="form-select">
                                                    <option value="">Select client type</option>
                                                    <option value="IB">IB</option>
                                                    <option value="Trader">Trader</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 fg position-relative al-fixed-input-error">
                                            <label for="id-client-email" class="col-form-label">Client</label>
                                            <div id="myDropdown2" class=" form-group ">
                                                <select name="client" id="deduct-balance-client" class="select2 form-select"></select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Amount  -->
                                    <div class="row mt-md-2">
                                        <!-- amount -->
                                        <div class="col-md-6">
                                            <label for="client-type" class="col-form-label">{{ __('finance.Amount') }}</label>
                                            <div class="input-group client-type-group">
                                                <span class="input-group-text" id="deduct-amount"><i data-feather='dollar-sign'></i></span>
                                                <input type="text" name="amount" class="form-control" id="amount-deduct" placeholder="0.00" />
                                            </div>
                                        </div>
                                        <!-- invoice code -->
                                        <div class="col-md-6">
                                            <label for="client-type" class="col-form-label">{{ __('finance.Invoice-code') }}(Optional)</label>
                                            <div class="input-group client-type-group">
                                                <span class="input-group-text" id="invoice-code-2"><i data-feather='credit-card'></i></span>
                                                <input type="text" name="invoice_code" class="form-control" id="invoice-code-236" placeholder="Invoce ID or Transaction Number" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- transaction method -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="method-withdraw" class="col-form-label">{{ __('finance.Method') }}</label>
                                            <select class="select2 form-select" id="method-withdraw" name="transaction_method">
                                                <option value="" selected>{{ __('finance.Select a method') }}</option>
                                                <option value="cash">Cash withdraw</option>
                                                <option value="bank">Bank</option>
                                                <option value="voucher">{{ __('finance.Voucher Deposit') }}</option>
                                                <option value="skrill">{{ __('finance.Skrill Deposit') }}</option>
                                                <option value="neteller">{{ __('finance.Neteller Deposit') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- note -->
                                    <div class="row mt-3 mt-md-2">
                                        <div class="col-sm-12">
                                            <div class="form-floating mb-0">
                                                <textarea data-length="100" name="note" class="form-control char-textarea" id="textarea-counter" rows="3" placeholder="Counter" style="height: 100px"></textarea>
                                                <label for="textarea-counter">{{ __('finance.Write a note') }} &#40;Optional&#41;</label>
                                            </div>
                                            <small class="textarea-counter-value float-end"><span class="char-count">0</span> / 100 </small>
                                        </div>
                                    </div>
                                    <!-- submit button -->
                                    <div class="d-flex justify-content-between mt-2">
                                        <span>&nbsp;</span>
                                        <?php
                                        $disable = '';
                                        $has_multi_submit = has_multi_submit('balance-deduct', 15);
                                        if ($has_multi_submit) {
                                            $disable = 'disabled';
                                        }
                                        ?>
                                        <button class="btn btn-primary" data-label="Save" type="button" data-submit_wait="{{ submit_wait('balance-deduct', 15) }}" id="dtn-deduct" data-form="personal-info-modern" data-el="fg" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="balance_deduct_callback" onclick="_run(this)" data-btnid="dtn-deduct" {{ $disable }}>
                                            <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Save</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                    <!-- /Modern Horizontal Wizard -->
                    <!-- Client Details for mobile screen -->
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd" aria-labelledby="offcanvasEndLabel">
                        <div class="offcanvas-header">
                            <h5 id="offcanvasEndLabel" class="offcanvas-title">Client Details</h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body my-auto mx-0 flex-grow-0">
                            <div class="card">
                                <div class="card-header d-flex">
                                    <h4 id="user-type-2" class="text-capitalize">---</h4>
                                    <h5 id="user-name-top-2" class="text-capitalize">---</h5>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <div class="rounded m-auto dt-trader-img img-finance">
                                        <div class="h-100">
                                            <img class="img img-fluid bg-light-primary img-trader-admin user-avatar" src="{{ asset('admin-assets/app-assets/images/avatars/' . $avatar) }}" alt="avatar">
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <!-- wallet balance -->
                                        <li class="list-group-item d-flex align-items-center">
                                            <i class="me-1" data-feather="dollar-sign" class="font-medium-2"></i>
                                            <span> {{ __('finance.Wallet Balance') }}</span>
                                            <span class="badge bg-primary rounded-pill ms-auto" id="wallet-balance-2">0</span>
                                        </li>
                                        <!-- last deposit -->
                                        <li class="list-group-item d-flex align-items-center" id="list-last-deposit-2">
                                            <i class="me-1" data-feather="dollar-sign" class="font-medium-2"></i>
                                            <span>{{ __('finance.Last Deposit') }}</span>
                                            <span class="badge bg-danger rounded-pill ms-auto" id="last-deposit-status-2" style="display:none">{{ __('finance.Pending') }}</span>
                                            <span class="badge bg-primary rounded-pill ms-auto" id="last-deposit-2">0</span>
                                        </li>
                                        <!-- last deposit date -->
                                        <li class="list-group-item align-items-center" id="last-d-date-list-2" style="display:none">
                                            <i class="me-1" data-feather="dollar-sign" class="font-medium-2"></i>
                                            <span>{{ __('finance.Last Deposit Date') }}</span>
                                            <span class="rounded-pill ms-auto" id="last-deposit-date-2">27 june 2022</span>
                                        </li>
                                        <!-- last withdraw -->
                                        <li class="list-group-item d-flex align-items-center">
                                            <i class="me-1" data-feather="dollar-sign" class="font-medium-2"></i>
                                            <span>{{ __('finance.Last Withdraw') }}</span>
                                            <span class="badge bg-success rounded-pill ms-auto" id="last-withdraw-status-2" style="display:none">{{ __('finance.Approved') }}</span>
                                            <span class="badge bg-primary rounded-pill ms-auto" id="last-withdraw-2">0</span>
                                        </li>
                                        <!-- last withdraw date -->
                                        <li class="list-group-item align-items-center" id="last-w-date-list-2" style="display:none">
                                            <i class="me-1" data-feather="dollar-sign" class="font-medium-2"></i>
                                            <span>{{ __('finance.Last Withdraw Date') }}</span>
                                            <span class=" ms-auto" id="last-withdraw-date-2">27 june 2022</span>
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
                    </div>
                    <!-- //Client Details for mobile screen -->
                </div>
                @else
                <div class="col-lg-8 col-md-12 col-sm-12">
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
@section('vendor-js')
<!-- <script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
                <script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
                <script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script> -->
@stop
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
<!-- <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/finance-balance.js') }}"></script> -->
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js') }}"></script>
<!-- <script src="{{ asset('common-js/search-dropdown.js') }}"></script> -->
<!-- <script src="{{ asset('common-js/select2-get-trader.js') }}"></script> -->
<script src="{{asset('common-js/send-mail.js')}}"></script>
<script>
    // store credit
    // --------------------------------------------------------------------------------------------
    submit_wait("#save-wallet-balance");
    submit_wait("#dtn-deduct");

    function createCallBack(data) {  
        if (data.status == true) {
            $("#fund-add-form").sending_mail({
                request_url: '/admin/finance/balance-management/mail/add-balance',
                data: {
                    user_id: data.user_id,
                    amount: data.amount,
                    client_type: data.client_type
                },
                method: 'POST',
                title: 'Sending email',
                message: 'Please wait, while we sending email. Dont reload the page.',
                click: true,
            }, function(response) {
                if (response.status) {
                    notify('success', response.message, 'Add Balance');
                } else {
                    notify('error', response.message, 'Add Balance');
                }
            });
            $("select").val('').trigger('change');
            $("#account-details-modern").trigger('reset');

            loadClientBalance(data.user_id,data.client_type); // refetching data
        } else {
            notify('error', data.message, 'Add Balance');
        }
        $.validator("account-details-modern", data.errors);
        submit_wait("#save-wallet-balance", data.submit_wait);
        
    }
    // balance deduct callback--------------------------------------------------
    function balance_deduct_callback(data) {
        if (data.status == true) {
            if (data.status == true) {
                $("#fund-add-form").sending_mail({
                    request_url: '/admin/finance/balance-management/mail/deduct-balance',
                    data: {
                        user_id: data.user_id,
                        amount: data.amount,
                        client_type: data.client_type
                    },
                    method: 'POST',
                    title: 'Sending email',
                    message: 'Please wait, while we sending email. Dont reload the page.',
                    click: true,
                }, function(response) {
                    loadClientBalance(data.user_id,data.client_type);  // refetching data
                    if (response.status) {
                        notify('success', response.message, 'Deduct Balance');
                    } else {
                        notify('error', response.message, 'Deduct Balance');
                    }
                });
                $("select").val('').trigger('change');
                $("#account-details-modern").trigger('reset');

            } else {
                notify('error', data.message, 'Deduct Balance');
            }
            $("#wallet-balance-form").trigger('reset');
        } else {
            notify('error', data.message, 'Balance Deduct');
        }
        $.validator("personal-info-modern", data.errors);
        submit_wait("#dtn-deduct", data.submit_wait);
    }
    // on change client type
    // *********************************************************************
    $(document).on('change', '#id-client-type', function() {
        $("#wallet-balance,#last-deposit,#last-withdraw").text(0);
        $("#last-withdraw-status,#last-deposit-status").hide();
        $("#user-name-top").text("----");
        $("#deduct-balance-client,#add-balance-client").val(null).trigger("change");
        if($(this).val() != "")
        {
            $("#user-type").text($(this).val()); 
        }
        if ($(this).val() === 'IB') {
            $("#list-last-deposit").addClass('d-none');
        } else {
            $("#list-last-deposit").removeClass('d-none');
        }
    });
    // on change withdraw client type
    $(document).on('change', '#id-client-type-withdraw', function() {
        $("#wallet-balance,#last-deposit,#last-withdraw").text(0);
        $("#last-withdraw-status,#last-deposit-status").hide();
        $("#user-name-top").text("----");
        $("#deduct-balance-client,#add-balance-client").val(null).trigger("change");
        if($(this).val() != "")
        {
            $("#user-type").text($(this).val()); 
        }
        if ($(this).val() === 'IB') {
            $("#list-last-deposit").addClass('d-none');
        } else {
            $("#list-last-deposit").removeClass('d-none');
        }
    });

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
            url: "/search/trader/ib",
            // type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term, // search term
                    client_type: $("#id-client-type").val()
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
    // select2 client for withdraw
    $("#deduct-balance-client").select2({
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
            url: "/search/trader/ib",
            // type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term, // search term
                    client_type: $("#id-client-type-withdraw").val()
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
        var $option = $(
            '<div><strong>' + option.text + '</strong></div><div>' + option.title +
            '</div><div><strong>Name: </strong>' + option.name + '</div>'
        );
        return $option;
    };
    // ********************************************************************
    // on change add balance client
    $(document).on("change", '#add-balance-client', function() { 
        $.ajax({
            url: '/admin/client/ib/finance-status',
            method: 'GET',
            dataType: 'JSON',
            data: {
                client: $(this).val(),
                client_type: $("#id-client-type").val(),
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
    });
    /**
    *--------------------------------------------------
    * -- form reset and others handle in bs-stepper---
    * -------------------------------------------------
    */
    $(document).ready(_=>{
        $(".step-trigger").on("click",function(){
            $("#wallet-balance,#last-deposit,#last-withdraw").text(0);
            $("#last-withdraw-status,#last-deposit-status").hide();
            $("#user-name-top,#user-type").text("----");
            $("#deduct-balance-client,#add-balance-client,#method-withdraw,#method").val(null).trigger("change");
            $("#wallet-balance-form,#account-details-modern,#personal-info-modern").trigger('reset'); 
        });
        
    });
    /*
    *----------------------------------------------------------
    * --- Refetching data from database when form is submit ---
    * ---------------------------------------------------------
    */
    function loadClientBalance(user,client)
    {
        $.ajax({
            url: '/admin/client/ib/finance-status',
            method: 'GET',
            dataType: 'JSON',
            data: {
                client: parseInt(user),
                client_type: client,
            },
            success: function(data) {
                $("#user-name-top").text(data.user_name);
                if (data.last_withdraw_status != "") {
                    $("#last-withdraw-status").text(data.last_withdraw_status).slideDown();
                }
                $("#last-withdraw").text(data.last_withdraw);
                $("#wallet-balance").text(data.total_balance);
                if (client === 'Trader') {
                    if (data.last_deposit != 0) {
                        $("#last-deposit-status").text(data.last_deposit_status).slideDown();
                    }

                    $("#last-deposit").text(data.last_deposit);
                } 
            }
        })
    }
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
</script>
@stop
<!-- BEGIN: page JS -->