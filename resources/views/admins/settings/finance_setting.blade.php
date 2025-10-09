@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Finance Settings')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/assets/css/config-form.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css') }}">
<style>
    div.dataTables_wrapper div.dataTables_filter select,
    div.dataTables_wrapper div.dataTables_length select {
        line-height: 30px;
    }
</style>
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{__('admin-menue-left.Finance_Settings')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{__('category.Settings')}}</a></li>
                                <li class="breadcrumb-item active">{{__('admin-menue-left.Finance_Settings')}}</li>
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
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <div class="card my-0 py-0">
                                <div class="card-body my-0 py-0">
                                    <h4 class="card-title">{{__('admin-menue-left.Finance_Settings')}}</h4>
                                </div>
                            </div>
                        </div>
                        <!-- finance setting form -->
                        <div class="card-body py-2 my-25" id="finance-setting-form">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <section class="modern-horizontal-wizard">
                                            <div class="bs-stepper wizard-modern modern-wizard-example">
                                                <div class="bs-stepper-header">
                                                    <div class="step" data-target="#charge_apply" role="tab" id="charge_apply-trigger">
                                                        <button type="button" class="step-trigger">
                                                            <span class="bs-stepper-box">
                                                                <i data-feather="file-text" class="font-medium-3"></i>
                                                            </span>
                                                            <span class="bs-stepper-label">
                                                                <span class="bs-stepper-title">Charge Apply</span>
                                                                <span class="bs-stepper-subtitle">Charrge Apply to Client Wallet</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="line">
                                                        &nbsp;
                                                    </div>
                                                    <div class="step" data-target="#trader_deposit" role="tab" id="trader_deposit-trigger">
                                                        <button type="button" class="step-trigger">
                                                            <span class="bs-stepper-box">
                                                                <i data-feather="user" class="font-medium-3"></i>
                                                            </span>
                                                            <span class="bs-stepper-label">
                                                                <span class="bs-stepper-title">Trader Deposit</span>
                                                                <span class="bs-stepper-subtitle">Deposit Settings For Transaction Limit</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="step" data-target="#trader_withdraw" role="tab" id="trader_withdraw_trigger">
                                                        <button type="button" class="step-trigger">
                                                            <span class="bs-stepper-box">
                                                                <i data-feather="user" class="font-medium-3"></i>
                                                            </span>
                                                            <span class="bs-stepper-label">
                                                                <span class="bs-stepper-title">Trader Withdraw</span>
                                                                <span class="bs-stepper-subtitle">Withdraw Settings For Transaction Limit</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- stepper content -->
                                                <div class="bs-stepper-content" style="box-shadow: none;">
                                                    <!------------------------------------------------------------------------------------------
                                                                    ||Charge Apply Section Start
                                                    ---------------------------------------------------------------------------------------- -->
                                                    <div class="pt-50 content" id="charge_apply" role="tabpanel" aria-labelledby="charge_apply-trigger">
                                                        <form action="{{route('admin.settings.finance_setting')}}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="tab-pane active" id="tab-panel" role="tabpanel" aria-labelledby="transaction-tab">
                                                                <div class="col-12 col-sm-12 mb-1">
                                                                    <!-- transaction type  -->
                                                                    <div class="col-12 col-sm-6 mb-1" style="float: left; padding-right:1rem;" id="transaction_type">
                                                                        <label class="form-label">{{__('page.Transactions')}} {{__('page.type')}}</label>
                                                                        <select class="select2 form-select" name="transaction_type">
                                                                            <option value="Deposit" <?php echo ((isset($transaction_type->transaction_type) && strtolower($transaction_type->transaction_type) == 'deposit') ?  'selected="selected"' : '') ?>>Deposit</option>
                                                                            <option value="Withdraw" <?php echo ((isset($transaction_type->transaction_type) && strtolower($transaction_type->transaction_type) == 'withdraw') ?  'selected="selected"' : '') ?>>Withdraw</option>
                                                                            <option value="a_to_w" <?php echo ((isset($transaction_type->transaction_type) && strtolower($transaction_type->transaction_type) == 'a_to_w') ?  'selected="selected"' : '') ?>>Account To Wallet</option>
                                                                            <option value="w_to_a" <?php echo ((isset($transaction_type->transaction_type) && strtolower($transaction_type->transaction_type) == 'w_to_a') ?  'selected="selected"' : '') ?>>Wallet To Account</option>
                                                                            <option value="a_to_a" <?php echo ((isset($transaction_type->transaction_type) && strtolower($transaction_type->transaction_type) == 'a_to_a') ?  'selected="selected"' : '') ?>>Account To Account</option>
                                                                            <option value="w_to_w" <?php echo ((isset($transaction_type->transaction_type) && strtolower($transaction_type->transaction_type) == 'w_to_w') ?  'selected="selected"' : '') ?>>Wallet To Wallet</option>
                                                                        </select>
                                                                    </div>
                                                                    <!-- transaction limit  -->
                                                                    <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                        <label class="form-label"> {{__('page.set')}} {{__('page.Transactions')}} {{__('page.limit')}}</label>
                                                                        <div class="input-group">
                                                                            <input type="number" name="min_transaction" class="form-control flatpickr-basic" placeholder="Min">
                                                                            <span class="input-group-text">To</span>
                                                                            <input type="number" name="max_transaction" class="form-control flatpickr-basic" placeholder="Max">
                                                                        </div>
                                                                    </div>
                                                                    <!-- transaction charge type  -->
                                                                    <div class="col-12 col-sm-6 mb-1" style="float: left; padding-right: 1rem;">
                                                                        <div class="card-body pb-0 social-media-card">
                                                                            <label class="form-label">{{__('ad-reports.Charge')}} {{__('page.type')}}</label>
                                                                            <div class="social-media-filter border">
                                                                                <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="All">
                                                                                    <input type="checkbox" class="form-check-input input-filter" name="fixed" id="fixed" data-value="fixed" checked />
                                                                                    <label class="form-check-label" for="fixed">Fixed(&dollar;)</label>
                                                                                </div>
                                                                                <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="All">
                                                                                    <input type="checkbox" class="form-check-input input-filter" name="percentage" id="percentage" data-value="percentage" checked />
                                                                                    <label class="form-check-label" for="percentage">Percentage(&percnt;)</label>
                                                                                </div>
                                                                            </div>
                                                                            <span id="charge_type_error" class="text-danger"></span>
                                                                        </div>
                                                                    </div>
                                                                    <!-- charge limit -->
                                                                    <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                        <label class="form-label">{{__('page.set')}} {{__('ad-reports.Charge')}} {{__('page.limit')}}</label>
                                                                        <div class="input-group">
                                                                            <input type="number" name="limit_start" class="form-control flatpickr-basic" placeholder="Start">
                                                                            <span class="input-group-text">To</span>
                                                                            <input type="number" name="limit_end" class="form-control flatpickr-basic" placeholder="End">
                                                                        </div>
                                                                    </div>
                                                                    <div class="clear-fixed"></div>
                                                                    <!-- KYC required  -->
                                                                    <div class="col-12 col-sm-4 mb-1" style="float: left; padding-right: 1rem;">
                                                                        <div class="card-body pb-0 social-media-card">
                                                                            <label class="form-label">KYC Required</label>
                                                                            <div class="social-media-filter border">
                                                                                <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="KYC Required">
                                                                                    <input type="checkbox" class="form-check-input input-filter kyc" name="kyc" data-value="kyc" />
                                                                                    <label class="form-check-label" for="kyc">KYC Required For Finace Transaction</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- amount -->
                                                                    <div class="col-12 col-sm-2 mb-1" style="float: left; padding-right:1rem;">
                                                                        <label class="form-label">{{__('ad-reports.Charge')}} ({{__('page.amount')}})</label>
                                                                        <div class="input-group">
                                                                            <input id="charge" type="text" name="amount" class="form-control flatpickr-basic" placeholder="0$">
                                                                        </div>
                                                                        <span id="charge_error" class="text-danger"></span>
                                                                    </div>
                                                                    <!-- transaction permission -->
                                                                    <div class="col-12 col-sm-3 mb-1" style="float: left; padding-right:1rem;">
                                                                        <label class="form-label">{{__('page.permission')}}</label>
                                                                        <select class="select2 form-select" name="permission">
                                                                            <option value="panding">Panding</option>
                                                                            <option value="approved">Approved</option>
                                                                        </select>
                                                                    </div>
                                                                    <!-- active status -->
                                                                    <div class="col-12 col-sm-3 mb-1" style="float: left;">
                                                                        <label class="form-label">{{__('category.Active Status')}}</label>
                                                                        <select class="select2 form-select" name="active_status">
                                                                            <option value="0">Deactivate</option>
                                                                            <option value="1">Activate</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="clear-fixed"></div>
                                                                <div class="col-12">
                                                                    <input type="hidden" name="config_id" value="<?= (isset($configs->id) ? $configs->id : '') ?>">
                                                                    <div class="p-0 m-0">
                                                                        @if(Auth::user()->hasDirectPermission('create finance settings'))
                                                                        <button type="submit" class="btn btn-primary" style="float: right">{{__('ib-management.Add')}} {{__('ad-reports.Charge')}}</button>
                                                                        @else

                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="clear-fixed"></div>
                                                            <!-- finance view and action table  -->
                                                            <!-- Dark Tables start -->
                                                            <div class="row" id="dark-table">
                                                                <div class="col-12">
                                                                    <div class="card">
                                                                        <div class="card-header" style="padding-left: 0px;">
                                                                            <h4 class="card-title">{{__('page.view')}} {{__('admin-menue-left.Finance_Settings')}}</h4>
                                                                        </div>
                                                                        <div class="table-responsive">
                                                                            <table id="finance_settings_table" class="datatables-basic table finance-settings-table scrollbar-primary">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>{{__('page.Transactions')}} {{__('page.type')}}</th>
                                                                                        <th>{{__('page.Transactions')}} {{__('page.limit')}}</th>
                                                                                        <th>{{__('ad-reports.Charge')}} {{__('page.type')}}</th>
                                                                                        <th>{{__('ad-reports.Charge')}} {{__('page.limit')}}</th>
                                                                                        <th>KYC</th>
                                                                                        <th>{{__('page.amount')}}</th>
                                                                                        <th>{{__('page.status')}}</th>
                                                                                        <th>{{__('category.Active Status')}}</th>
                                                                                        <th>{{__('page.action')}}</th>
                                                                                    </tr>
                                                                                </thead>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!------------------------------------------------------------------------------------------
                                                                                //Charge Apply Section End
                                                    ---------------------------------------------------------------------------------------- -->
                                                    <div id="trader_deposit" class="content" role="tabpanel" aria-labelledby="trader_deposit-trigger">
                                                        <div>
                                                            <div class="content-body">
                                                                <div class="row justify-content-between">
                                                                    <div class="col-5 border-primary">
                                                                        <div class="content-body">
                                                                            <div class="card">
                                                                                <div class="card-header">
                                                                                    <div>
                                                                                        <h4> {{__('ib-management.Note')}}</h4>
                                                                                        <code class="bg">{{__('ib-management.please read carefully')}}</code>
                                                                                    </div>
                                                                                </div>
                                                                                <hr>
                                                                                <div class="card-body">
                                                                                    <div class="border-start-3 border-start-primary p-1 bg-light-primary mb-1">
                                                                                        Admin Finance Settings.This is a one-time setup
                                                                                    </div>
                                                                                    <div class="border-start-3 border-start-info p-1 bg-light-primary mb-1">
                                                                                        Deposit settings,Withdraw settings means your admin Level will can set transaction limitation.
                                                                                    </div>
                                                                                    <div class="border-start-3 border-start-danger p-1 bg-light-primary mb-1">
                                                                                        Ensure that transaction limits for traders comply with relevant financial regulations and exchange rules.
                                                                                    </div>
                                                                                    <div class="border-start-3 border-start-success p-1 bg-light-primary mb-1">
                                                                                        Provide traders with access to monitoring and reporting tools that allow them to track their transaction usage and stay within their limits.
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-7 border-primary" style="float: right; width: 56%;">
                                                                        <div class="card">
                                                                            <div class="card-header mb-0">
                                                                                <div class="card my-0 py-0 w-100">
                                                                                    <div class="card-body my-0 py-0">
                                                                                        <div class="tab-content" id="pills-tabContent">
                                                                                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                                                                                <div class="card p-0">
                                                                                                    <!-- Transaction Settings Form -->
                                                                                                    <form action="{{route('admin.finance.deposit-settings')}}" method="POST" enctype="multipart/form-data" id="deposit_settings_form">
                                                                                                        @csrf
                                                                                                        <div class="row">
                                                                                                            <!-- -------------------------------Deposit Limit------------------------------ -->
                                                                                                            <h5 class="mb-2 mt-2">Deposit Settings</h5>
                                                                                                            <!-- Local Bank Deposit -->
                                                                                                            <div class="col-12 col-sm-6 mb-1 pb-2">
                                                                                                                <label class="form-label">Local Bank deposit</label>
                                                                                                                <div class="input-group">
                                                                                                                    @foreach($depositSettings as $depositSetting)
                                                                                                                    @if($depositSetting['deposit_method'] === 'bank')
                                                                                                                    <input type="number" name="bank_min_transaction" class="form-control flatpickr-basic" placeholder="Min" value="{{ $depositSetting['min_amount'] }}">
                                                                                                                    <span class="input-group-text">To</span>
                                                                                                                    <input type="number" name="bank_max_transaction" class="form-control flatpickr-basic" placeholder="Max" value="{{ $depositSetting['max_amount'] }}">
                                                                                                                    @endif
                                                                                                                    @endforeach
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <!-- Crypto Deposit -->
                                                                                                            <div class="col-12 col-sm-6 mb-1 pb-2">
                                                                                                                <label class="form-label">Crypto deposit</label>
                                                                                                                <div class="input-group">
                                                                                                                    @foreach($depositSettings as $depositSetting)
                                                                                                                    @if($depositSetting['deposit_method'] === 'crypto')
                                                                                                                    <input type="number" name="crypto_min_transaction" class="form-control flatpickr-basic" placeholder="Min" value="{{ $depositSetting['min_amount'] }}">
                                                                                                                    <span class="input-group-text">To</span>
                                                                                                                    <input type="number" name="crypto_max_transaction" class="form-control flatpickr-basic" placeholder="Max" value="{{ $depositSetting['max_amount'] }}">
                                                                                                                    @endif
                                                                                                                    @endforeach
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <!--Perfect Money Deposit -->
                                                                                                            <div class="col-12 col-sm-6 mb-1 pb-2">
                                                                                                                <label class="form-label">Perfect Money deposit</label>
                                                                                                                <div class="input-group">
                                                                                                                    @foreach($depositSettings as $depositSetting)
                                                                                                                    @if($depositSetting['deposit_method'] === 'perfect_money')
                                                                                                                    <input type="number" name="perfect_money_min_transaction" class="form-control flatpickr-basic" placeholder="Min" value="{{ $depositSetting['min_amount'] }}">
                                                                                                                    <span class="input-group-text">To</span>
                                                                                                                    <input type="number" name="perfect_money_max_transaction" class="form-control flatpickr-basic" placeholder="Max" value="{{ $depositSetting['max_amount'] }}">
                                                                                                                    @endif
                                                                                                                    @endforeach
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <!--Help2Pay Deposit -->
                                                                                                            <div class="col-12 col-sm-6 mb-1 pb-2">
                                                                                                                <label class="form-label">Help2Pay deposit</label>
                                                                                                                <div class="input-group">
                                                                                                                    @foreach($depositSettings as $depositSetting)
                                                                                                                    @if($depositSetting['deposit_method'] === 'help2pay')
                                                                                                                    <input type="number" name="help2pay_min_transaction" class="form-control flatpickr-basic" placeholder="Min" value="{{ $depositSetting['min_amount'] }}">
                                                                                                                    <span class="input-group-text">To</span>
                                                                                                                    <input type="number" name="help2pay_max_transaction" class="form-control flatpickr-basic" placeholder="Max" value="{{ $depositSetting['max_amount'] }}">
                                                                                                                    @endif
                                                                                                                    @endforeach
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <!--B2B Deposit -->
                                                                                                            <div class="col-12 col-sm-6 mb-1 pb-2">
                                                                                                                <label class="form-label">B2B deposit</label>
                                                                                                                <div class="input-group">
                                                                                                                    @foreach($depositSettings as $depositSetting)
                                                                                                                    @if($depositSetting['deposit_method'] === 'b2b')
                                                                                                                    <input type="number" name="b2b_min_transaction" class="form-control flatpickr-basic" placeholder="Min" value="{{ $depositSetting['min_amount'] }}">
                                                                                                                    <span class="input-group-text">To</span>
                                                                                                                    <input type="number" name="b2b_max_transaction" class="form-control flatpickr-basic" placeholder="Max" value="{{ $depositSetting['max_amount'] }}">
                                                                                                                    @endif
                                                                                                                    @endforeach
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <!--Match2Pay Deposit -->
                                                                                                            <div class="col-12 col-sm-6 mb-1 pb-2">
                                                                                                                <label class="form-label">Match2Pay deposit</label>
                                                                                                                <div class="input-group">
                                                                                                                    @foreach($depositSettings as $depositSetting)
                                                                                                                    @if($depositSetting['deposit_method'] === 'm2pay')
                                                                                                                    <input type="number" name="m2pay_min_transaction" class="form-control flatpickr-basic" placeholder="Min" value="{{ $depositSetting['min_amount'] }}">
                                                                                                                    <span class="input-group-text">To</span>
                                                                                                                    <input type="number" name="m2pay_max_transaction" class="form-control flatpickr-basic" placeholder="Max" value="{{ $depositSetting['max_amount'] }}">
                                                                                                                    @endif
                                                                                                                    @endforeach
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-12 mt-2">
                                                                                                                <div class="p-0 m-0">
                                                                                                                    <button type="button" class="btn btn-primary text-center" id="btn-submit-deposit-settings" onclick="_run(this)" data-el="fg" data-form="deposit_settings_form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="deposit_submit_callback" data-btnid="btn-submit-deposit-settings" style="width:200px">Submit request</button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </form>
                                                                                                    <!--/Transaction Settings Form -->
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!------------------------------------------------------------------------------------------
                                                                                ||Trader Withdraw Section Start
                                                    ---------------------------------------------------------------------------------------- -->
                                                    <div id="trader_withdraw" class="content" role="tabpanel" aria-labelledby="trader_withdraw_trigger">
                                                        <div class="content-body">
                                                            <div class="row justify-content-between">
                                                                <div class="col-5 border-primary">
                                                                    <div class="content-body">
                                                                        <div class="card">
                                                                            <div class="card-header">
                                                                                <div>
                                                                                    <h4> {{__('ib-management.Note')}}</h4>
                                                                                    <code class="bg">{{__('ib-management.please read carefully')}}</code>
                                                                                </div>
                                                                            </div>
                                                                            <hr>
                                                                            <div class="card-body">
                                                                                <div class="border-start-3 border-start-primary p-1 bg-light-primary mb-1">
                                                                                    Admin Finance Settings.This is a one-time setup
                                                                                </div>
                                                                                <div class="border-start-3 border-start-info p-1 bg-light-primary mb-1">
                                                                                    Deposit settings,Withdraw settings means your admin Level will can set transaction limitation.
                                                                                </div>
                                                                                <div class="border-start-3 border-start-danger p-1 bg-light-primary mb-1">
                                                                                    Ensure that transaction limits for traders comply with relevant financial regulations and exchange rules.
                                                                                </div>
                                                                                <div class="border-start-3 border-start-success p-1 bg-light-primary mb-1">
                                                                                    Provide traders with access to monitoring and reporting tools that allow them to track their transaction usage and stay within their limits.
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-7 border-primary" style="float: right; width: 56%;">
                                                                    <div class="card">
                                                                        <div class="card-header mb-0">
                                                                            <div class="card my-0 py-0 w-100">
                                                                                <div class="card-body my-0 py-0">
                                                                                    <div class="tab-content" id="pills-tabContent">
                                                                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                                                                            <div class="card p-0">
                                                                                                <!-- Transaction Settings Form -->
                                                                                                <form action="{{ route('admin.finance.trader.withdraw-settings') }}" method="POST" enctype="multipart/form-data" id="withdraw_settings_form">
                                                                                                    @csrf
                                                                                                    <div class="row">
                                                                                                        <!-- -------------------------------Deposit Limit------------------------------ -->
                                                                                                        <h5 class="mb-2">Withdraw Settings</h5>
                                                                                                        <!-- Bank Withdraw -->
                                                                                                        <div class="col-12 col-sm-6 mb-1 pb-2">
                                                                                                            <label class="form-label">Bank withdraw</label>
                                                                                                            <div class="input-group">
                                                                                                                @foreach($withdrawSettings as $withdrawSetting)
                                                                                                                @if($withdrawSetting['withdraw_method'] === 'bank')
                                                                                                                <input type="number" name="bank_min_withdraw" class="form-control flatpickr-basic" placeholder="Min" value="{{ $withdrawSetting['min_amount'] }}">
                                                                                                                <span class="input-group-text">To</span>
                                                                                                                <input type="number" name="bank_max_withdraw" class="form-control flatpickr-basic" placeholder="Max" value="{{ $withdrawSetting['max_amount'] }}">
                                                                                                                @endif
                                                                                                                @endforeach
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <!-- Crypto Withdraw -->
                                                                                                        <div class="col-12 col-sm-6 mb-1 pb-2">
                                                                                                            <label class="form-label">Crypto withdraw</label>
                                                                                                            <div class="input-group">
                                                                                                                @foreach($withdrawSettings as $withdrawSetting)
                                                                                                                @if($withdrawSetting['withdraw_method'] === 'crypto')
                                                                                                                <input type="number" name="crypto_min_withdraw" class="form-control flatpickr-basic" placeholder="Min" value="{{ $withdrawSetting['min_amount'] }}">
                                                                                                                <span class="input-group-text">To</span>
                                                                                                                <input type="number" name="crypto_max_withdraw" class="form-control flatpickr-basic" placeholder="Max" value="{{ $withdrawSetting['max_amount'] }}">
                                                                                                                @endif
                                                                                                                @endforeach
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <!-- PayPal Withdraw -->
                                                                                                        <div class="col-12 col-sm-6 mb-1 pb-2">
                                                                                                            <label class="form-label">PayPal withdraw</label>
                                                                                                            <div class="input-group">
                                                                                                                @foreach($withdrawSettings as $withdrawSetting)
                                                                                                                @if($withdrawSetting['withdraw_method'] === 'paypal')
                                                                                                                <input type="number" name="paypal_min_withdraw" class="form-control flatpickr-basic" placeholder="Min" value="{{ $withdrawSetting['min_amount'] }}">
                                                                                                                <span class="input-group-text">To</span>
                                                                                                                <input type="number" name="paypal_max_withdraw" class="form-control flatpickr-basic" placeholder="Max" value="{{ $withdrawSetting['max_amount'] }}">
                                                                                                                @endif
                                                                                                                @endforeach
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <!-- GCash Withdraw -->
                                                                                                        <div class="col-12 col-sm-6 mb-1 pb-2">
                                                                                                            <label class="form-label">GCash withdraw</label>
                                                                                                            <div class="input-group">
                                                                                                                @foreach($withdrawSettings as $withdrawSetting)
                                                                                                                @if($withdrawSetting['withdraw_method'] === 'gcash')
                                                                                                                <input type="number" name="gcash_min_withdraw" class="form-control flatpickr-basic" placeholder="Min" value="{{ $withdrawSetting['min_amount'] }}">
                                                                                                                <span class="input-group-text">To</span>
                                                                                                                <input type="number" name="gcash_max_withdraw" class="form-control flatpickr-basic" placeholder="Max" value="{{ $withdrawSetting['max_amount'] }}">
                                                                                                                @endif
                                                                                                                @endforeach
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-12 mt-2">
                                                                                                            <div class="p-0 m-0">
                                                                                                                <button type="button" class="btn btn-primary text-center" id="btn-submit-withdraw-settings" onclick="_run(this)" data-el="fg" data-form="withdraw_settings_form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="withdraw_submit_callback" data-btnid="btn-submit-withdraw-settings" style="width:200px">Submit request</button>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                </form>
                                                                                                <!--/Transaction Settings Form -->
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <!------------------------------------------------------------------------------------------
                                                                                //Trader Withdraw Section End
                                                    ---------------------------------------------------------------------------------------- -->
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/finance setting form -->

                        <!--Delete Finace Modal End-->
                        <div class="modal fade" id="finance-setting-delete-modal">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="exampleModalCenterTitle">Confirmation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="" class="modal-content pt-0">
                                        @csrf
                                        <input type="hidden" name="id" id="finance-setting-delete-id" value="">
                                        <div class="modal-body my-3">
                                            <h4 class="text-center">
                                                Do you really want to delete these records? This process cannot be undone.
                                                </h5 class="text-center">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger data-submit me-1" data-bs-dismiss="modal" id="finance-setting-delete">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--Delete Finace Modal End-->
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<!-- datatable -->
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- toaster js -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-page-configuration.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js') }}"></script>
<!-- form hide/show -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-config-form.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js') }}"></script>
<script>
    function deposit_submit_callback(data) {
        if (data.status) {
            notify('success', data.message, 'Trader deposit settings');
        } else {
            notify('error', data.message, 'Trader deposit settings');
        }
        $.validator("deposit_settings_form", data.errors);
    }

    function withdraw_submit_callback(data) {
        if (data.status) {
            notify('success', data.message, 'Trader withdraw settings');
        } else {
            notify('error', data.message, 'Trader withdraw settings');
        }
        $.validator("withdraw_settings_form", data.errors);
    }
</script>
@stop
<!-- BEGIN: page JS -->