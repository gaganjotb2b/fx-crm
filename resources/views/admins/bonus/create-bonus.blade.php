@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Create Bonus')
@section('vendor-css')

<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/calendars/fullcalendar.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
@stop

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/pages/app-calendar.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css')}}">

<style>
    .add_field_button {
        background-color: #34d9ec !important;
    }

    .add_field_button:hover {
        background-color: #34d9ec !important;
    }

    input.form-control.filter-num {
        margin-top: 10px;
    }

    #editor-container {
        height: 375px;
    }

    #group-wrapper>div,
    #group-wrapper-account>div,
    #group-wrapper-reg>div {
        width: 60%;
    }

    #select-all-wrapper,
    #select-all-wrapper-account,
    #select-all-wrapper-reg {
        padding: 8px 14px;
    }
</style>
@stop
<!-- BEGIN: content -->
@section('content')
<!-- BEGIN: Content-->
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Create Bonus</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('group-setting.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item active">Bonus
                                </li>
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
                {{-- important note section --}}
                <div class="col-md-12 col-lg-5 col-xl-5">
                    <div class="card">
                        <div class="card-header pb-1">
                            <h4 class="card-title">{{__('group-setting.Note')}}</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                {{__('group-setting.Important notes please read')}}
                            </p>
                            <ul class="list-group">
                                <div class="border-start-3 border-start-primary p-1 mb-1 bg-light-info">
                                    Select Bonus type is it no deposit bonus or bonus for a specific deposit or simple deposit bonus.
                                </div>
                                <div class="border-start-3 border-start-primary p-1 mb-1 bg-light-info">
                                    Select clients category is it for all clients or for new client or for only old clients.
                                </div>
                                <div class="border-start-3 border-start-danger p-1 mb-1 bg-light-info">
                                    Trade on ECN or ECN Zero conditions.
                                </div>
                                <div class="border-start-3 border-start-primary p-1 mb-1 bg-light-info">
                                    Show your profile on the Strategy Managers Ranking page.
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- creat form section --}}
                {{-- @if(Auth::user()->hasDirectPermission('create group manager')) --}}
                <div class="col-md-12 col-lg-7">
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
                                            <span class="bs-stepper-title">For Deposit</span>
                                            <span class="bs-stepper-subtitle">Bonus for deposits</span>
                                        </span>
                                    </button>
                                </div>
                                <div class="line">
                                    <!-- <i data-feather="chevron-right" class="font-medium-2"></i> -->
                                    &nbsp;
                                </div>
                                <div class="step" data-target="#personal-info-modern" role="tab" id="personal-info-modern-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="user" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">For New Registration</span>
                                            <span class="bs-stepper-subtitle">Bonus for new registration</span>
                                        </span>
                                    </button>
                                </div>
                                <div class="line">
                                    <!-- <i data-feather="chevron-right" class="font-medium-2"></i> -->
                                    &nbsp;
                                </div>
                                <div class="step" data-target="#address-step-modern" role="tab" id="address-step-modern-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="map-pin" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">For New Trading Account</span>
                                            <span class="bs-stepper-subtitle">Bonus for new accounts only</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="bs-stepper-content">
                                <!-- all clients bonus -->
                                <form action="{{route('admin.create.all-client-bonus')}}" method="post" id="account-details-modern" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger">
                                    @csrf
                                    <div class="content-header">
                                        <h5 class="mb-0">Bonus for Deposit</h5>
                                        <small class="text-muted">Setup bonus details & planning.</small>
                                    </div>
                                    <div class="row">
                                        <!-- bonus name -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-bonus-type">Bonus Name</label>
                                            <input type="text" name="bonus_name" class="form-control form-input">
                                        </div>
                                        <!-- client -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-bonus-type">Client</label>
                                            <div class="row">
                                                <!-- bonus client -->
                                                <div class="col-md-5">
                                                    <div class="title-wrapper d-flex">
                                                        <div class="d-flex flex-column float-start">
                                                            <div class="form-check form-switch form-check-primary">
                                                                <input type="checkbox" id="bonus-client" class="form-check-input bonus-client" name="bonus_client" value="1" checked />
                                                                <label class="form-check-label" for="master">
                                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <label class="todo-title cursor-pointer" for="bonus-client"><i class="fab fa-facebook-f"></i> All Client ?</label>
                                                    </div>
                                                </div>
                                                <!-- clients -->
                                                <div class="col-md-7 fg" id="bonus-client-wrapper">
                                                    <div id="client_list">
                                                        <div class="row">
                                                            <select name="client[]" id="modern-client" class="select2 select2-trader form-select form-control" multiple></select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- bonus type -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-bonus-type">Bonus type</label>
                                            <select class="select2 form-select" id="bonus-type" name="bonus_type">
                                                <option value="first_deposit">Bonus On First Deposit</option>
                                                <option value="specific_deposit">Bonus On Specific Deposit</option>
                                            </select>
                                        </div>
                                        <div class="mb-1 col-md-6" id="deposit-amount-wrapper" style="display: none;">
                                            <!-- min max deposit -->
                                            <div class="mb-1 col-md-12">
                                                <label class="form-label" for="modern-bonus-type">Deposit Amount</label>
                                                <div class="col-sm-12 d-flex flex-column float-start">
                                                    <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top">
                                                        <span class="input-group-text">
                                                            <div class="icon-wrapper">
                                                                Min
                                                            </div>
                                                        </span>
                                                        <input id="from" type="text" name="min_deposit" class="form-control" placeholder="0">
                                                        <span class="input-group-text">Max</span>
                                                        <input id="to" type="text" name="max_deposit" class="form-control" placeholder="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-country">country</label>
                                            <div class="row">
                                                <!-- is global -->
                                                <div class="col-md-5">
                                                    <div class="title-wrapper d-flex">
                                                        <div class="d-flex flex-column float-start">
                                                            <div class="form-check form-switch form-check-primary">
                                                                <input type="checkbox" id="is_global" class="form-check-input is_global" name="is_global" value="1" checked />
                                                                <label class="form-check-label" for="master">
                                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <label class="todo-title cursor-pointer" for="is_global"><i class="fab fa-facebook-f"></i> Is Global ?</label>
                                                    </div>
                                                </div>
                                                <!-- country -->
                                                <div class="col-md-7 fg" id="bonus-country-wrapper">
                                                    <div id="country_list">
                                                        <div class="row">
                                                            <select class="select2 form-select" name="country[]" placeholder="select country" multiple="multiple" id="bonus-country">
                                                                @foreach($countries as $value)
                                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 form-password-toggle col-md-6">
                                            <!-- groups -->
                                            <label class="form-label" for="modern-group">Groups</label>
                                            <div class="input-group form-password-toggle mb-2" id="group-wrapper">
                                                <select class="select2 form-select w-100" id="client-groups" name="client_groups[]" multiple>
                                                    @foreach($groups as $value)
                                                    <option value="{{$value->id}}">{{$value->group_name}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="input-group-text1 border" id="select-all-wrapper">
                                                    <span class="form-check form-check-success">
                                                        <input type="checkbox" class="form-check-input" id="colorCheck31" />
                                                        <label class="form-check-label" for="colorCheck31">Select All</label>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <!-- credit type -->
                                            <label class="form-label" for="modern-confirm-password">Credit Type</label>
                                            <div class="demo-inline-spacing">
                                                <div class="form-check form-check-success mt-0">
                                                    <input type="radio" id="customColorRadio3" name="credit_type" class="form-check-input" value="percent" checked />
                                                    <label class="form-check-label" for="customColorRadio3">Percent ?</label>
                                                </div>
                                                <div class="form-check form-check-warning mt-0">
                                                    <input type="radio" id="customColorRadio4" name="credit_type" class="form-check-input" value="fixed" />
                                                    <label class="form-check-label" for="customColorRadio4">Fixed ?</label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- bonus amount -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-credit-amount">Credit Amount</label>
                                            <input type="text" class="form-control form-input" name="credit_amount" id="credit-amount" placeholder="0">
                                        </div>
                                        <!--  -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-credit-amount">Maximum Bonus</label>
                                            <input type="text" class="form-control form-input" name="maximum_bonus" id="credit-amount" placeholder="0">
                                        </div>
                                        <!-- crdit expire -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-credit-amount">Credit Expire</label>
                                            <input type="text" class="form-control form-input" name="credit_expire" id="credit-amount" placeholder="Expire After">
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-credit-amount">&nbsp;</label>
                                            <select class="select2 form-select w-100" id="client-expire" name="expire">
                                                <option value="days">Days</option>
                                                <option value="months">Months</option>
                                                <option value="years">Years</option>
                                            </select>
                                        </div>
                                        <!-- date range -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-credit-amount">Date Range</label>
                                            <div class="col-sm-12 d-flex flex-column float-start">
                                                <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Date" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
                                                    <span class="input-group-text">
                                                        <div class="icon-wrapper">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                                            </svg>
                                                        </div>
                                                    </span>

                                                    <input id="from" type="text" name="date_from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                    <span class="input-group-text">to</span>

                                                    <input id="to" type="text" name="date_to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- withdraw requirement -->
                                        <div class="mb-1 mt-25 col-md-6">
                                            <div class="form-group">
                                                <label for="withraw-requirement">Withdraw Requirement</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Lot</span>
                                                    <input type="text" class="form-control" id="withdraw-requirement" name="withdraw_requirement" placeholder="10">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- submit buttons -->
                                    <div class="d-flex justify-content-between mt-3">
                                        <button class="btn btn-outline-secondary btn-prev" disabled>
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-primary" type="button" data-file="true" onclick="_run(this)" data-el="fg" data-form="account-details-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="all_clients_callback" data-btnid="btn-save-bonus-all" id="btn-save-bonus-all">
                                            <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Save</span>
                                        </button>
                                    </div>
                                </form>
                                <!-- new registration bonus -->
                                <form id="personal-info-modern" action="{{route('admin.create.new-registration-bonus')}}" method="post" class="content" role="tabpanel" aria-labelledby="personal-info-modern-trigger">
                                    @csrf
                                    <div class="content-header">
                                        <h5 class="mb-0">For New Registration</h5>
                                        <small>Setup bonus details and planning.</small>
                                    </div>
                                    <div class="row">
                                        <!-- bonus name /reg -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-bonus-type">Bonus Name</label>
                                            <input type="text" name="bonus_name" class="form-control form-input">
                                        </div>
                                        <!-- bonus type /reg -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="bonus-type-reg">Bonus type</label>
                                            <select class="select2 form-select" id="bonus-type-reg" name="bonus_type">
                                                <option value="free">No Deposit Bonus</option>
                                                <option value="first_deposit">Bonus On First Deposit</option>
                                                <option value="specific_deposit">Bonus On Specific Deposit</option>
                                            </select>
                                        </div>
                                        <div class="mb-1 col-md-6" id="deposit-amount-wrapper-reg" style="display: none;">
                                            <!-- min max deposit -->
                                            <div class="mb-1 col-md-12">
                                                <label class="form-label" for="modern-bonus-type">Deposit Amount</label>
                                                <div class="col-sm-12 d-flex flex-column float-start">
                                                    <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top">
                                                        <span class="input-group-text">
                                                            <div class="icon-wrapper">
                                                                Min
                                                            </div>
                                                        </span>
                                                        <input id="min-deposit-reg" type="text" name="min_deposit" class="form-control" placeholder="0">
                                                        <span class="input-group-text">Max</span>
                                                        <input id="max-deposit-reg" type="text" name="max_deposit" class="form-control" placeholder="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-country">country</label>
                                            <div class="row">
                                                <!-- is global -->
                                                <div class="col-md-5">
                                                    <div class="title-wrapper d-flex">
                                                        <div class="d-flex flex-column float-start">
                                                            <div class="form-check form-switch form-check-primary">
                                                                <input type="checkbox" id="is_global_reg" class="form-check-input is_global" name="is_global" value="1" checked />
                                                                <label class="form-check-label" for="master">
                                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <label class="todo-title cursor-pointer" for="is_global_reg"><i class="fab fa-facebook-f"></i> Is Global ?</label>
                                                    </div>
                                                </div>
                                                <!-- country -->
                                                <div class="col-md-7 fg" id="bonus-country-wrapper-reg">
                                                    <div id="country_list_reg">
                                                        <div class="row">
                                                            <select class="select2 form-select" name="country[]" placeholder="select country" multiple="multiple" id="bonus-country-reg">
                                                                @foreach($countries as $value)
                                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 form-password-toggle col-md-6">
                                            <!-- groups -->
                                            <label class="form-label" for="modern-group">Groups</label>
                                            <div class="input-group form-password-toggle mb-2" id="group-wrapper-reg">
                                                <select class="select2 form-select w-100" id="client-groups-reg" name="client_groups[]" multiple>
                                                    @foreach($groups as $value)
                                                    <option value="{{$value->id}}">{{$value->group_name}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="input-group-text1 border" id="select-all-wrapper-reg">
                                                    <span class="form-check form-check-success">
                                                        <input type="checkbox" class="form-check-input" id="group-select-all-reg" />
                                                        <label class="form-check-label" for="group-select-all-reg">Select All</label>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <!-- credit type -->
                                            <label class="form-label" for="credit-type-reg">Credit Type</label>
                                            <div class="demo-inline-spacing">
                                                <div class="form-check form-check-success mt-0">
                                                    <input type="radio" id="credit-type-reg" name="credit_type" class="form-check-input" value="percent" checked />
                                                    <label class="form-check-label" for="credit-type-reg">Percent ?</label>
                                                </div>
                                                <div class="form-check form-check-warning mt-0">
                                                    <input type="radio" id="credit-fix-reg" name="credit_type" class="form-check-input" value="fixed" />
                                                    <label class="form-check-label" for="credit-fix-reg">Fixed ?</label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- bonus amount -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="credit-amount-reg">Credit Amount</label>
                                            <input type="text" class="form-control form-input" name="credit_amount" id="credit-amount-reg" placeholder="0">
                                        </div>
                                        <!--  -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="maximum-bonus-reg">Maximum Bonus</label>
                                            <input type="text" class="form-control form-input" name="maximum_bonus" id="maximum-bonus-reg" placeholder="0">
                                        </div>
                                        <!-- crdit expire -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="credit-expire-reg">Credit Expire</label>
                                            <input type="text" class="form-control form-input" name="credit_expire" id="credit-expire-reg" placeholder="Expire After">
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="expire-after-reg">&nbsp;</label>
                                            <select class="select2 form-select w-100" id="expire-after-reg" name="expire">
                                                <option value="days">Days</option>
                                                <option value="months">Months</option>
                                                <option value="years">Years</option>
                                            </select>
                                        </div>
                                        <!-- date range -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="date-range-reg">Date Range</label>
                                            <div class="col-sm-12 d-flex flex-column float-start">
                                                <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Date" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
                                                    <span class="input-group-text">
                                                        <div class="icon-wrapper">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                                            </svg>
                                                        </div>
                                                    </span>

                                                    <input id="date-from-reg" type="text" name="date_from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                    <span class="input-group-text">to</span>

                                                    <input id="date-to-reg" type="text" name="date_to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- withdraw requirement -->
                                        <div class="mt-25 col-md-6">
                                            <div class="form-group">
                                                <label for="withdraw-requirement">Withdraw requirement</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Lot</span>
                                                    <input type="text" class="form-control" id="withdraw-requirement-reg" name="withdraw_requirement" placeholder="10">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- submit buttons -->
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-primary btn-prev">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-primary" type="button" data-file="true" onclick="_run(this)" data-el="fg" data-form="personal-info-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="reg_bonus_callback" data-btnid="btn-save-reg-bonus" id="btn-save-reg-bonus">
                                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                                            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                        </button>
                                    </div>
                                </form>
                                <!-- new trading account bonus -->
                                <form id="address-step-modern" action="{{route('admin.create.new-account-bonus')}}" method="post" class="content" role="tabpanel" aria-labelledby="address-step-modern-trigger">
                                    @csrf
                                    <div class="content-header">
                                        <h5 class="mb-0">Address</h5>
                                        <small>Enter Your Address.</small>
                                    </div>
                                    <div class="row">
                                        <!-- bonus name /new account -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="bonus-name-account">Bonus Name</label>
                                            <input type="text" name="bonus_name" class="form-control form-input">
                                        </div>
                                        <!-- client /new account -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="account-bonus-client">Client</label>
                                            <div class="row">
                                                <!-- bonus client -->
                                                <div class="col-md-5">
                                                    <div class="title-wrapper d-flex">
                                                        <div class="d-flex flex-column float-start">
                                                            <div class="form-check form-switch form-check-primary">
                                                                <input type="checkbox" id="account-bonus-client" class="form-check-input bonus-client" name="bonus_client" value="1" checked />
                                                                <label class="form-check-label" for="master">
                                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <label class="todo-title cursor-pointer" for="account-bonus-client"><i class="fab fa-facebook-f"></i> All Client ?</label>
                                                    </div>
                                                </div>
                                                <!-- clients -->
                                                <div class="col-md-7 fg" id="account-bonus-client-wrapper">
                                                    <div id="client_list">
                                                        <div class="row">
                                                            <select name="client[]" id="account-bonus-client" class="select2 select2-trader form-select form-control" multiple></select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- bonus type -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="bonus-type-account">Bonus type</label>
                                            <select class="select2 form-select" id="bonus-type-account" name="bonus_type">
                                                <option value="free">No Deposit Bonus</option>
                                                <option value="first_deposit">Bonus On First Deposit</option>
                                                <option value="specific_deposit">Bonus On Specific Deposit</option>
                                            </select>
                                        </div>
                                        <div class="mb-1 col-md-6" id="deposit-amount-wrapper-account" style="display: none;">
                                            <!-- min max deposit -->
                                            <div class="mb-1 col-md-12">
                                                <label class="form-label" for="deposit-account">Deposit Amount</label>
                                                <div class="col-sm-12 d-flex flex-column float-start">
                                                    <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top">
                                                        <span class="input-group-text">
                                                            <div class="icon-wrapper">
                                                                Min
                                                            </div>
                                                        </span>
                                                        <input id="min-deposit-account" type="text" name="min_deposit" class="form-control" placeholder="0">
                                                        <span class="input-group-text">Max</span>
                                                        <input id="max-deposit-account" type="text" name="max_deposit" class="form-control" placeholder="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="country-account-bonus">country</label>
                                            <div class="row">
                                                <!-- is global -->
                                                <div class="col-md-5">
                                                    <div class="title-wrapper d-flex">
                                                        <div class="d-flex flex-column float-start">
                                                            <div class="form-check form-switch form-check-primary">
                                                                <input type="checkbox" id="is_global_account" class="form-check-input is_global" name="is_global" value="1" checked />
                                                                <label class="form-check-label" for="master">
                                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <label class="todo-title cursor-pointer" for="is_global_account"><i class="fab fa-facebook-f"></i> Is Global ?</label>
                                                    </div>
                                                </div>
                                                <!-- country -->
                                                <div class="col-md-7 fg" id="bonus-country-wrapper-account">
                                                    <div id="country_list">
                                                        <div class="row">
                                                            <select class="select2 form-select" name="country[]" placeholder="select country" multiple="multiple" id="bonus-country-account">
                                                                @foreach($countries as $value)
                                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 form-password-toggle col-md-6">
                                            <!-- groups -->
                                            <label class="form-label" for="group-account">Groups</label>
                                            <div class="input-group form-password-toggle mb-2" id="group-wrapper-account">
                                                <select class="select2 form-select w-100" id="client-groups-account" name="client_groups[]" multiple>
                                                    @foreach($groups as $value)
                                                    <option value="{{$value->id}}">{{$value->group_name}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="input-group-text1 border" id="select-all-wrapper-account">
                                                    <span class="form-check form-check-success">
                                                        <input type="checkbox" class="form-check-input" id="group-select-all-account" />
                                                        <label class="form-check-label" for="group-select-all-account">Select All</label>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <!-- credit type -->
                                            <label class="form-label" for="credit-type-account">Credit Type</label>
                                            <div class="demo-inline-spacing">
                                                <div class="form-check form-check-success mt-0">
                                                    <input type="radio" id="credit-type-account" name="credit_type" class="form-check-input" value="percent" checked />
                                                    <label class="form-check-label" for="credit-type-account">Percent ?</label>
                                                </div>
                                                <div class="form-check form-check-warning mt-0">
                                                    <input type="radio" id="credit-fix-account" name="credit_type" class="form-check-input" value="fixed" />
                                                    <label class="form-check-label" for="credit-fix-account">Fixed ?</label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- bonus amount -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="credit-amount-account">Credit Amount</label>
                                            <input type="text" class="form-control form-input" name="credit_amount" id="credit-amount-account" placeholder="0">
                                        </div>
                                        <!--  -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="maximum-bonus-account">Maximum Bonus</label>
                                            <input type="text" class="form-control form-input" name="maximum_bonus" id="maximum-bonus-account" placeholder="0">
                                        </div>
                                        <!-- crdit expire -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="credit-expire-account">Credit Expire</label>
                                            <input type="text" class="form-control form-input" name="credit_expire" id="credit-expire-account" placeholder="Expire After">
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="expire-after-account">&nbsp;</label>
                                            <select class="select2 form-select w-100" id="expire-after-account" name="expire">
                                                <option value="days">Days</option>
                                                <option value="months">Months</option>
                                                <option value="years">Years</option>
                                            </select>
                                        </div>
                                        <!-- date range -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="date-range-account">Date Range</label>
                                            <div class="col-sm-12 d-flex flex-column float-start">
                                                <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Date" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
                                                    <span class="input-group-text">
                                                        <div class="icon-wrapper">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                                            </svg>
                                                        </div>
                                                    </span>

                                                    <input id="date-from-account" type="text" name="date_from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                    <span class="input-group-text">to</span>

                                                    <input id="date-to-account" type="text" name="date_to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- withdraw requirement /new account -->
                                        <div class="mt-25 col-md-6">
                                            <div class="form-group">
                                                <label for="withdraw-requirement-ac">Withdraw Requirement</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        Lot
                                                    </span>
                                                    <input type="text" class="form-control" id="withdraw-requirement-ac" name="withdraw_requirement" placeholder="10">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- button section/ new account -->
                                    <div class="d-flex justify-content-between">
                                        <!-- previous from trading account -->
                                        <button class="btn btn-primary btn-prev" type="button">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <!-- submit account bonus -->
                                        <button class="btn btn-primary" type="button" data-file="true" onclick="_run(this)" data-el="fg" data-form="address-step-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="bonus_for_account_callback" data-btnid="btn-save-bonus-account" id="btn-save-bonus-account">
                                            <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Save Bonus</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                    <!-- /Modern Horizontal Wizard -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->
@stop

@section('page-vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@stop

@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>

<script src="{{asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js')}}"></script>
<script src="{{asset('common-js/select2-get-trader.js')}}"></script>
<script>
    // select all select2
    $("#colorCheck31").click(function() {
        if ($("#colorCheck31").is(':checked')) {
            $('#client-groups').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#client-groups').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
    // select all select2
    $("#group-select-all-reg").click(function() {
        if ($("#group-select-all-reg").is(':checked')) {
            $('#client-groups-reg').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#client-groups-reg').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
    // select all select2
    $("#group-select-all-account").click(function() {
        if ($("#group-select-all-account").is(':checked')) {
            $('#client-groups-account').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#client-groups-account').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
    // bonus for all clients
    function all_clients_callback(data) {
        if (data.status == true) {
            notify('success', data.message, 'All Clients Bonus');
            $("#account-details-modern").trigger('reset');
        } else {
            notify('error', data.message, 'All Clients Bonus');
        }
        $.validator('account-details-modern', data.errors);
    }
    // display min max deposit
    min_max_deposit();
    min_max_deposit_reg();
    min_max_deposit_account();
    // for all clients bonus
    function min_max_deposit() {
        if ($("#bonus-type").val() === 'specific_deposit') {
            $("#deposit-amount-wrapper").slideDown();
        } else {
            $("#deposit-amount-wrapper").slideUp();
        }
    }
    // for nw register bonus
    function min_max_deposit_reg() {
        if ($("#bonus-type-reg").val() === 'specific_deposit') {
            $("#deposit-amount-wrapper-reg").slideDown();
        } else {
            $("#deposit-amount-wrapper-reg").slideUp();
        }
    }
    // for account bonus
    function min_max_deposit_account() {
        if ($("#bonus-type-account").val() === 'specific_deposit') {
            $("#deposit-amount-wrapper-account").slideDown();
        } else {
            $("#deposit-amount-wrapper-account").slideUp();
        }
    }
    // for all client bonus
    $("#bonus-type").on("change", function() {
        min_max_deposit();
    });
    // for new register bonus
    $("#bonus-type-reg").on("change", function() {
        min_max_deposit_reg();
    });
    // for new account bonus
    $("#bonus-type-account").on("change", function() {
        min_max_deposit_account();
    });
    // display country
    display_country();
    display_country_reg();
    display_country_account();
    // for account bonus
    function display_country() {
        if ($("#is_global").is(':checked')) {
            $("#bonus-country-wrapper").slideUp();
        } else {
            $("#bonus-country-wrapper").slideDown();
        }
    }
    // for new register
    function display_country_reg() {
        if ($("#is_global_reg").is(':checked')) {
            $("#bonus-country-wrapper-reg").slideUp();
        } else {
            $("#bonus-country-wrapper-reg").slideDown();
        }
    }
    // for new account
    function display_country_account() {
        if ($("#is_global_account").is(':checked')) {
            $("#bonus-country-wrapper-account").slideUp();
        } else {
            $("#bonus-country-wrapper-account").slideDown();
        }
    }
    // for all clients
    $("#is_global").on("change", function() {
        display_country();
    });
    // for new register
    $("#is_global_reg").on("change", function() {
        display_country_reg();
    });
    // for new account
    $("#is_global_account").on("change", function() {
        display_country_account();
    });
    // client for deposit
    display_client();
    display_client_account();

    function display_client() {
        if ($("#bonus-client").is(':checked')) {
            $("#bonus-client-wrapper").slideUp();
        } else {
            $("#bonus-client-wrapper").slideDown();
        }
    }

    function display_client_account() {
        if ($("#account-bonus-client").is(':checked')) {
            $("#account-bonus-client-wrapper").slideUp();
        } else {
            $("#account-bonus-client-wrapper").slideDown();
        }
    }
    // for deposit
    $("#bonus-client").on("change", function() {
        display_client();
    });
    $("#account-bonus-client").on("change", function() {
        display_client_account();
    });
    // new registration bonus callback
    function reg_bonus_callback(data) {
        if (data.status == true) {
            notify('success', data.message, 'New Registration Bonus');
            $("#personal-info-modern").trigger('reset');
        } else {
            notify('error', data.message, 'New Registration Bonus');
        }
        $.validator('personal-info-modern', data.errors);
    }
    // for new account bonus
    function bonus_for_account_callback(data) {
        if (data.status == true) {
            notify('success', data.message, 'New Account Bonus');
            $("#address-step-modern").trigger('reset');
        } else {
            notify('error', data.message, 'New Account Bonus');
        }
        $.validator('address-step-modern', data.errors);
    }
    // disable credit type
    disable_credit_type();
    disable_credit_type_account();

    function disable_credit_type() {
        if ($("#bonus-type-reg").val() === 'free') {
            $("#credit-type-reg").prop('checked', false);
            $("#credit-type-reg").prop('disabled', true);
            // $("#credit-type-reg").disabled = false;
            $("#credit-fix-reg").prop('checked', true);
        } else {
            $("#credit-type-reg").prop('disabled', false);
            $("#credit-fix-reg").prop('checked', true);
        }
    }

    function disable_credit_type_account() {
        if ($("#bonus-type-account").val() === 'free') {
            $("#credit-type-account").prop('checked', false);
            $("#credit-type-account").prop('disabled', true);
            // $("#credit-type-account").disabled = false;
            $("#credit-fix-account").prop('checked', true);
        } else {
            $("#credit-type-account").prop('disabled', false);
            $("#credit-fix-account").prop('checked', true);
        }
    }
    // change bonus type 
    $(document).on('change', "#bonus-type-reg", function() {
        disable_credit_type();
    });
    $(document).on('change', "#bonus-type-account", function() {
        disable_credit_type_account();
    });
</script>
@stop