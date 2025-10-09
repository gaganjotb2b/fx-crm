@extends('layouts.manager-layout')
@section('title','Manager Analysis')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
<!-- number input -->
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/charts/chart-apex.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css')}}">
<style>
    .apexcharts-legend.apexcharts-align-center.position-right {
        margin-top: 3rem;
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
    <div class="content-wrapper container-xxl p-0">
        <!-- breadcrumb -->
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Manager Analysis</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}">{{ __('admin-breadcrumbs.home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">Manager Analysis</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                        <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#">
                                <i class="me-1" data-feather="info"></i>
                                <span class="align-middle">Note</span>
                            </a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Role cards -->
            <div class="card">
                <div class="card-body">
                    <!-- filter form -->
                    <form action="{{route('manager.analysis')}}" method="post" id="manager-analysis-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3 ms-auto" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter data by date">
                                <input type="text" class="form-control dt-date flatpickr-range dt-input" data-column="5" placeholder="StartDate to EndDate" data-column-index="4" name="dt_date" />
                                <input type="hidden" class="form-control dt-date start_date dt-input" data-column="5" data-column-index="4" name="start_date" />
                                <input type="hidden" class="form-control dt-date end_date dt-input" name="end_date" data-column="5" data-column-index="4" />
                            </div>
                            <div class="col-lg-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter reset">
                                <button type="button" class="btn btn-secondary mb-1 w-100" id="btn-reset">Reset</button>
                            </div>
                            <div class="col-lg-3 d-grid">
                                <button type="button" class="btn btn-primary mb-1 w-100" id="filter" onclick="_run(this)" data-el="fg" data-form="manager-analysis-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="manager_analysis_call_back" data-btnid="filter">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- details-->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 d-flex">
                                    <img src="{{asset('admin-assets/app-assets/images/avatars/avater-men.png')}}" alt="user avatar" style="width:48px; height:48px" class="bg-bitbucket rounded-circle">
                                    <div class="ms-2">
                                        <div id="name-group">
                                            <span class="name-label"><b>Name : </b></span>
                                            <span class="name">{{auth()->user()->name}}</span>
                                        </div>
                                        <div id="email-group">
                                            <span class="email-label"><b>Email : </b></span>
                                            <span class="email">{{auth()->user()->email}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div id="Country-group">
                                        <span class="Country-label"><b>Country : </b></span>
                                        <span class="country">{{ucwords($country)}}</span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <!-- total ib -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-success">
                                        <div class="card-body">
                                            <div id="total-ib"><b>N/A</b></div>
                                            <div id="total-ib-label">Total IB (Total)</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- total trader -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-success">
                                        <div class="card-body">
                                            <div id="total-trader"><b>N/A</b></div>
                                            <div id="total-trader-label">Total Trader</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- total deposit -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-success">
                                        <div class="card-body">
                                            <div id="total-deposit"><b>N/A</b></div>
                                            <div id="total-deposit">Total Deposit( <b>&dollar;</b> )</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- total withdraw -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-success">
                                        <div class="card-body">
                                            <div id="total-withdraw"><b>N/A</b></div>
                                            <div id="total-withdraw-label">Total Withdraw (<b>&dollar;</b>)</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- trading accounts -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-success">
                                        <div class="card-body">
                                            <div id="trading-accounts"><b>N/A</b></div>
                                            <div id="trading-accounts-label">Trading Accounts (Total)</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- total trade volume -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-success">
                                        <div class="card-body">
                                            <div id="trade-volume"><b>N/A</b></div>
                                            <div id="trade-volume-label">Trade Volume (All Clients)</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- total ib commission -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-success">
                                        <div class="card-body">
                                            <div id="ib-commission"><b>N/A</b></div>
                                            <div id="ib-commission-label">IB Commission (&dollar;)</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- trade volume trader -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-success">
                                        <div class="card-body">
                                            <div id="toal-leads"><b>8526</b></div>
                                            <div id="toal-leads-label">Total Leads</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- total bonus -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-success">
                                        <div class="card-body">
                                            <div id="total-bonus"><b>N/A</b></div>
                                            <div id="total-bonus-label">Total Bonus</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--ending column 8-->
                <!-- chart -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div id="chart"></div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <!-- ib refferal link -->
                            <div class="col-12 mb-2 d-none">
                                <div class="input-group">
                                    <button class="btn btn-outline-primary" type="button">
                                        <i data-feather="users"></i>
                                    </button>
                                    <input type="text" class="form-control" id="copy-to-clipboard-input" placeholder="Button on both side" value="{{$ib_referral_link}}" aria-label="Amount" />
                                    <button class="btn btn-outline-primary" id="btn-copy-ib" type="button">Copy !</button>
                                </div>
                            </div>
                            <!-- trader link -->
                            <div class="col-12 mb-2">
                                <div class="input-group">
                                    <button class="btn btn-outline-primary" type="button">
                                        <i data-feather="user"></i>
                                    </button>
                                    <input type="text" class="form-control" id="trader-link-copy-clipboard" placeholder="Button on both side" value="{{$trader_referral_link}}" aria-label="Amount" />
                                    <button class="btn btn-outline-primary" id="btn-copy-trader" type="button">Copy !</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- table tabs -->
            <!-- Modern Horizontal Wizard -->
            <section class="modern-horizontal-wizard">
                <div class="bs-stepper wizard-modern modern-wizard-example">
                    <!-- stepper header -->
                    <div class="bs-stepper-header">
                        <!-- total finance -->
                        <div class="step" data-target="#account-details-modern" role="tab" id="account-details-modern-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="file-text" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Total Finance</span>
                                    <span class="bs-stepper-subtitle">All finance details</span>
                                </span>
                            </button>
                        </div>
                        <div class="line">
                            <i data-feather="chevron-right" class="font-medium-2"></i>
                        </div>
                        <!-- Total Trader -->
                        <div class="step" data-target="#personal-info-modern" role="tab" id="personal-info-modern-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="user" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Total Trader</span>
                                    <span class="bs-stepper-subtitle">All trader under manager</span>
                                </span>
                            </button>
                        </div>
                        <div class="line">
                            <i data-feather="chevron-right" class="font-medium-2"></i>
                        </div>
                        <!-- Total IB -->
                        <div class="step" data-target="#address-step-modern" role="tab" id="address-step-modern-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="map-pin" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Total IB</span>
                                    <span class="bs-stepper-subtitle">All IB Under Manager</span>
                                </span>
                            </button>
                        </div>
                        <div class="line">
                            <i data-feather="chevron-right" class="font-medium-2"></i>
                        </div>
                        <div class="step" data-target="#social-links-modern" role="tab" id="social-links-modern-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="link" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Total Deposit</span>
                                    <span class="bs-stepper-subtitle">Total Deposit Under Manager</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <!-- stepper content -->
                    <div class="bs-stepper-content">
                        <!-- content total finance -->
                        <div id="account-details-modern" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger">
                            <div class="content-header">
                                <h5 class="mb-0">Finance Detailes</h5>
                                <small class="text-muted">All Finance detailes analysis</small>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table" id="table-total-finance">
                                        <thead>
                                            <tr>
                                                <th class="bg-light-warning">&nbsp;</th>
                                                <th>Trader(Total)</th>
                                                <th>Trader(Direct)</th>
                                                <th>Trader(From IB)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- deposit -->
                                            <tr>
                                                <th class="bg-light-warning">
                                                    Deposit(Approved)
                                                </th>
                                                <td>
                                                    &dollar; <span id="deposit-total-tbl"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="deposit-direct-tbl"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="deposit-fromIB-tbl"> 0.00 </span>
                                                </td>
                                            </tr>
                                            <!-- deposit total pending -->
                                            <tr>
                                                <th class="bg-light-warning">
                                                    Deposit(Pending)
                                                </th>
                                                <td>
                                                    &dollar; <span id="deposit-total-pending"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="deposit-direct-pending"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="deposit-fromIB-pending"> 0.00 </span>
                                                </td>
                                            </tr>
                                            <!-- deposit total -->
                                            <tr>
                                                <th class="bg-light-warning">
                                                    Deposit(Total)
                                                </th>
                                                <td>
                                                    &dollar; <span id="deposit-total-total"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="deposit-direct-total"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="deposit-fromIB-total"> 0.00 </span>
                                                </td>
                                            </tr>
                                            <!-- withdraw approved -->
                                            <tr>
                                                <th class="bg-light-warning">
                                                    Withdraw(Approved)
                                                </th>
                                                <td>
                                                    &dollar; <span id="withdraw-total-approved"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="withdraw-direct-approved"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="withdraw-fromIB-approved"> 0.00 </span>
                                                </td>
                                            </tr>
                                            <!-- withdraw pending -->
                                            <tr>
                                                <th class="bg-light-warning">
                                                    Withdraw(Pending)
                                                </th>
                                                <td>
                                                    &dollar; <span id="withdraw-total-pending"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="withdraw-direct-pending"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="withdraw-fromIB-pending"> 0.00 </span>
                                                </td>
                                            </tr>
                                            <!-- withdraw Total -->
                                            <tr>
                                                <th class="bg-light-warning">
                                                    Withdraw(Total)
                                                </th>
                                                <td>
                                                    &dollar; <span id="withdraw-total-total"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="withdraw-direct-total"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="withdraw-fromIB-total"> 0.00 </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- next and previews  button -->
                            <!-- section total finance -->
                            <div class="d-flex justify-content-between d-none">
                                <button class="btn btn-outline-secondary btn-prev" disabled>
                                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-primary btn-next">
                                    <span class="align-middle d-sm-inline-block d-none">Next</span>
                                    <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                </button>
                            </div>
                        </div>
                        <!-- content section -->
                        <!-- toal trader -->
                        <div id="personal-info-modern" class="content" role="tabpanel" aria-labelledby="personal-info-modern-trigger">
                            <div class="content-header">
                                <h5 class="mb-0">Total Trader</h5>
                                <small>Total Trader Details</small>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table" id="table-total-trader">
                                        <thead>
                                            <tr>
                                                <th class="bg-light-success">&nbsp;</th>
                                                <th>Trader(Total)</th>
                                                <th>Trader(Direct)</th>
                                                <th>Trader(From IB)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- clients -->
                                            <tr>
                                                <th class="bg-light-success">
                                                    Clients (Active)
                                                </th>
                                                <td>
                                                    <span id="clients-total-active"> 0 </span>
                                                </td>
                                                <td>
                                                    <span id="clients-direct-active"> 0 </span>
                                                </td>
                                                <td>
                                                    <span id="clients-fromIB-active"> 0 </span>
                                                </td>
                                            </tr>

                                            <!-- client diabled -->
                                            <tr>
                                                <th class="bg-light-success">
                                                    Clients (Disabled)
                                                </th>
                                                <td>
                                                    <span id="clients-total-disabled"> 0</span>
                                                </td>
                                                <td>
                                                    <span id="clients-direct-disabled"> 0</span>
                                                </td>
                                                <td>
                                                    <span id="clients-fromIB-disabled"> 0</span>
                                                </td>
                                            </tr>
                                            <!-- client Live -->
                                            <tr>
                                                <th class="bg-light-success">
                                                    Clients (Live)
                                                </th>
                                                <td>
                                                    <span id="clients-total-live"> 0</span>
                                                </td>
                                                <td>
                                                    <span id="clients-direct-live"> 0</span>
                                                </td>
                                                <td>
                                                    <span id="clients-fromIB-live"> 0</span>
                                                </td>
                                            </tr>
                                            <!-- client demo -->
                                            <tr>
                                                <th class="bg-light-success">
                                                    Clients (Demo)
                                                </th>
                                                <td>
                                                    <span id="clients-total-demo"> 0</span>
                                                </td>
                                                <td>
                                                    <span id="clients-direct-demo"> 0</span>
                                                </td>
                                                <td>
                                                    <span id="clients-fromIB-demo"> 0</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- prevous and next button -->
                            <div class="d-flex justify-content-between d-none">
                                <button class="btn btn-primary btn-prev">
                                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-primary btn-next">
                                    <span class="align-middle d-sm-inline-block d-none">Next</span>
                                    <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                </button>
                            </div>
                        </div>
                        <!-- content section -->
                        <!-- Total IB details -->
                        <div id="address-step-modern" class="content" role="tabpanel" aria-labelledby="address-step-modern-trigger">
                            <div class="content-header">
                                <h5 class="mb-0">Total IB</h5>
                                <small>IB Client Detailes</small>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table" id="table-total-ib">
                                        <thead>
                                            <tr>
                                                <th class="bg-light-success">&nbsp;</th>
                                                <th>IB(Total)</th>
                                                <th>IB(Direct)</th>
                                                <th>IB(From IB)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- IB clients active -->
                                            <tr>
                                                <th class="bg-light-success">
                                                    IB Clients (Active)
                                                </th>
                                                <td>
                                                    <span id="ib-total-active"> 0 </span>
                                                </td>
                                                <td>
                                                    <span id="ib-direct-active"> 0 </span>
                                                </td>
                                                <td>
                                                    <span id="ib-fromIB-active"> 0 </span>
                                                </td>
                                            </tr>
                                            <!-- ib clients disabled -->
                                            <tr>
                                                <th class="bg-light-success">
                                                    IB Clients (Disabled)
                                                </th>
                                                <td>
                                                    <span id="ib-total-disabled"> 0 </span>
                                                </td>
                                                <td>
                                                    <span id="ib-direct-disabled"> 0 </span>
                                                </td>
                                                <td>
                                                    <span id="ib-fromIB-disabled"> 0 </span>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- buttons next and previous -->
                            <div class="d-flex justify-content-between d-none">
                                <button class="btn btn-primary btn-prev">
                                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-primary btn-next">
                                    <span class="align-middle d-sm-inline-block d-none">Next</span>
                                    <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                </button>
                            </div>
                        </div>
                        <!-- section content -->
                        <!-- content total deposit -->
                        <div id="social-links-modern" class="content" role="tabpanel" aria-labelledby="social-links-modern-trigger">
                            <div class="content-header">
                                <h5 class="mb-0">Total Deposit</h5>
                                <small>Total Client Deposit Detailes</small>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table" id="table-total-deposit">
                                        <thead>
                                            <tr>
                                                <th class="bg-light-info">&nbsp;</th>
                                                <th>Trader(Total)</th>
                                                <th>Trader(Direct)</th>
                                                <th>Trader(From IB)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- deposit -->
                                            <tr>
                                                <th class="bg-light-info">
                                                    Deposit(Approved)
                                                </th>
                                                <td>
                                                    &dollar; <span id="deposit-total-approved2"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="deposit-direct-approved2"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="deposit-fromIB-approved2"> 0.00 </span>
                                                </td>
                                            </tr>
                                            <!-- deposit total pending -->
                                            <tr>
                                                <th class="bg-light-info">
                                                    Deposit(Pending)
                                                </th>
                                                <td>
                                                    &dollar; <span id="deposit-total-pending2"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="deposit-direct-pending2"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="deposit-fromIB-pending2"> 0.00 </span>
                                                </td>
                                            </tr>
                                            <!-- deposit total -->
                                            <tr>
                                                <th class="bg-light-info">
                                                    Deposit(Total)
                                                </th>
                                                <td>
                                                    &dollar; <span id="deposit-total-total2"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="deposit-direct-total2"> 0.00 </span>
                                                </td>
                                                <td>
                                                    &dollar; <span id="deposit-fromIB-total2"> 0.00 </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- button previous and next -->
                            <div class="d-flex justify-content-between d-none">
                                <button class="btn btn-primary btn-prev">
                                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-success btn-submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /Modern Horizontal Wizard -->
            <!-- manager edit Modal -->
            <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-5 pb-5">
                            <div class="text-center mb-4">
                                <h1 class="role-title">Edit Manager Info</h1>
                                <p id="display-manager-group">Desk Manager</p>
                            </div>
                            <!-- manager edit form -->
                            <form id="edit-manager-info-form" class="row" method="post" action="{{route('admin.edit-manager')}}">
                                <div id="manager-infos" class="row">
                                    <!-- Load from add manager controller controller -->
                                </div>
                                <div class="col-12 mt-2 text-end">
                                    <hr>
                                    <!-- <button type="submit" class="btn btn-primary me-1 btn-save-edit-manager">Save Change</button> -->
                                    <button type="button" class="btn btn-primary  btn-save-edit-manager" id="save-edit-manager" onclick="_run(this)" data-el="fg" data-form="edit-manager-info-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="edit_manager_call_back" data-btnid="save-edit-manager">Save Change</button>
                                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                        Discard
                                    </button>
                                </div>
                            </form>
                            <!--/ manager edit  form -->
                        </div>
                    </div>
                </div>
            </div>
            <!--/ manager edit Modal -->
        </div>
    </div>
</div>
<!-- END: Content-->

<!-- Enable backdrop (default) -->
<div class="enable-backdrop">
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasBackdrop" aria-labelledby="offcanvasBackdropLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasBackdropLabel" class="offcanvas-title">Add New Admin Group</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body my-auto mx-0 flex-grow-0">
            <p>
                <b>Group Name as Rols Name.</b>
                A role provided access to predefined menus and features so that depending
                on assigned role an administrator can have access to what he need
            </p>
            <form action="{{route('admin.add-admin-group')}}" method="post" id="admin-group-form">
                @csrf
                <label class="form-label" for="group-name">Group Name</label>
                <input id="group-name" class="form-control" type="text" placeholder="Normal Input" name="group_name" />
                <button type="button" id="save-group" class="btn btn-primary mb-1 d-grid w-100 mt-1">Save Group</button>
                <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="offcanvas">
                    Cancel
                </button>
            </form>
        </div>
    </div>
</div>
<!--/ Enable backdrop (default) -->
@stop
<!-- BEGIN: page JS -->
<!-- BEGIN: vendor JS -->
@section('vendor-js')
<!-- here add vendor js -->
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
<!-- datatable -->

<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- number input -->
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/charts/apexcharts.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')

<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-number-input.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/manager-analysis.js')}}"> </script>
<script src="{{asset('admin-assets/app-assets/js/scripts/extensions/ext-component-clipboard.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js')}}"></script>
<script>
    // simple pie chart/ apext chart
    // --------------------------------
    var options = {
        series: [1, 1, 1],
        chart: {
            width: 380,
            type: 'pie',
        },
        labels: ['IB Commission', 'Withdraw', 'Deposit'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
    // filter manager
    // --------------------------------------------------------------
    function manager_analysis_call_back(data) {
        if (data.status == true) {
            $('.name').text(data.user_info.name);
            $('.email').text(data.user_info.email);
            $('.country').text(data.user_info.country);
            $('#total-trader').find('b').text(data.total_trader);
            $('#total-ib').find('b').text(data.total_ib);
            $('#trade-volume').find('b').text(data.total_ib);
            $('#ib-commission').find('b').text(data.total_ib_commission);
            $('#ib-commission-lot').find('b').text(data.ib_commission_lot);
            $('#trading-accounts').find('b').text(data.trading_accounts);
            $('#trade-volume-from-ib').find('b').text(data.trade_volume_from_ib);
            $('#trade-volume-trader').find('b').text(data.trade_volume_trader);
            $('#total-bonus').find('b').text(data.total_bonus);

            // update apex chart
            chart.updateOptions({
                series: [parseFloat(data.total_ib_commission), parseFloat(data.total_withdraw), parseFloat(data.total_deposit)],
            });
        } else {
            toastr['warning'](data.message, 'Manager Analysis', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });
        }
    }
    // call ajax when page load
    window.addEventListener('load', function() {
        let formData = $("#manager-analysis-form").serializeObject();
        let form_url = $("#manager-analysis-form").attr('action');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: form_url,
            method: 'POST',
            dataType: 'JSON',
            data: formData,
            success: function(data) {
                if (data.status == true) {
                    $('.name').text(data.user_info.name);
                    $('.email').text(data.user_info.email);
                    $('.country').text(data.user_info.country);
                    $('#total-trader').find('b').text(data.total_trader);
                    $('#total-ib').find('b').text(data.total_ib);
                    $('#trade-volume').find('b').text(data.total_ib);
                    $('#ib-commission').find('b').text(data.total_ib_commission);
                    $('#ib-commission-lot').find('b').text(data.ib_commission_lot);
                    $('#trading-accounts').find('b').text(data.trading_accounts);
                    $('#trade-volume-from-ib').find('b').text(data.trade_volume_from_ib);
                    $('#trade-volume-trader').find('b').text(data.trade_volume_trader);
                    $('#total-bonus').find('b').text(data.total_bonus);
                    // push total deposit/withdraw
                    $("#total-deposit").find('b').text(data.total_deposit);
                    $("#total-withdraw").find('b').text(data.total_withdraw);


                    // update apex chart
                    chart.updateOptions({
                        series: [parseFloat(data.total_ib_commission), parseFloat(data.total_withdraw), parseFloat(data.total_deposit)],
                    });
                } else {
                    toastr['warning'](data.message, 'Manager Analysis', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                }
            }
        })
    });
    // copy trader link
    $("#btn-copy-trader").copy_clipboard({
        copy_el: "#trader-link-copy-clipboard"
    });
    // copy ib link
    $("#btn-copy-ib").copy_clipboard({
        copy_el: "#copy-to-clipboard-input"
    });
    // filter reset
    $(document).on('click', "#btn-reset", function() {
        $("#manager-analysis-form").trigger('reset');
    });
    // get finance data
    finance();

    function finance() {
        $.ajax({
            url: '/manager/analysis/finance',
            method: 'GET',
            success: function(data) {
                // approved deposit
                $("#deposit-total-tbl").text(data.approved_deposit_all)
                $("#deposit-direct-tbl").text(data.approved_deposit_direct)
                $("#deposit-fromIB-tbl").text(data.approved_deposit_affiliated)
                // pending deposit
                $("#deposit-total-pending").text(data.pending_deposit_all)
                $("#deposit-direct-pending").text(data.pending_deposit_direct)
                $("#deposit-fromIB-pending").text(data.pending_deposit_affiliated)
                // declined deposit
                $("#deposit-total-total").text(data.total_deposit_all)
                $("#deposit-direct-total").text(data.total_deposit_direct)
                $("#deposit-fromIB-total").text(data.total_deposit_affiliated)
                // approved withdraw
                $("#withdraw-total-approved").text(data.approved_withdraw_all)
                $("#withdraw-direct-approved").text(data.approved_withdraw_direct)
                $("#withdraw-fromIB-approved").text(data.approved_withdraw_affiliated)
                // pending withdraw
                $("#withdraw-total-pending").text(data.pending_withdraw_all)
                $("#withdraw-direct-pending").text(data.pending_withdraw_direct)
                $("#withdraw-fromIB-pending").text(data.pending_withdraw_affiliated)
                // declined withdraw
                $("#withdraw-total-total").text(data.total_withdraw_all)
                $("#withdraw-direct-total").text(data.total_withdraw_direct)
                $("#withdraw-fromIB-total").text(data.total_withdraw_affiliated)
            }
        });
    }
    // deposit details
    deposit_detailes();

    function deposit_detailes() {
        $.ajax({
            url: '/manager/analysis/deposit-detailes',
            method: 'GET',
            success: function(data) {
                // approved deposit
                $("#deposit-total-approved2").text(data.approved_deposit_all)
                $("#deposit-direct-approved2").text(data.approved_deposit_direct)
                $("#deposit-fromIB-approved2").text(data.approved_deposit_affiliated)
                // pending deposit
                $("#deposit-total-pending2").text(data.pending_deposit_all)
                $("#deposit-direct-pending2").text(data.pending_deposit_direct)
                $("#deposit-fromIB-pending2").text(data.pending_deposit_affiliated)
                // declined deposit
                $("#deposit-total-total2").text(data.total_deposit_all)
                $("#deposit-direct-total2").text(data.total_deposit_direct)
                $("#deposit-fromIB-total2").text(data.total_deposit_affiliated)
            }
        });
    }
    // trade detailes
    // client detailes
    client_details();

    function client_details() {
        $.ajax({
            url: '/manager/analysis/client-details',
            method: 'GET',
            success: function(data) {
                // active clients
                $("#clients-total-active").text(data.active_total)
                $("#clients-direct-active").text(data.active_direct)
                $("#clients-fromIB-active").text(data.active_affiliated)
                // disabled clients
                $("#clients-total-disabled").text(data.disabled_total)
                $("#clients-direct-disabled").text(data.disabled_direct)
                $("#clients-fromIB-disabled").text(data.disabled_affiliated)
                // live clients
                $("#clients-total-live").text(data.live_total)
                $("#clients-direct-live").text(data.live_direct)
                $("#clients-fromIB-live").text(data.live_affiliated)
                // approved withdraw
                $("#clients-total-demo").text(data.demo_total)
                $("#clients-direct-demo").text(data.demo_direct)
                $("#clients-fromIB-demo").text(data.demo_affiliated)

            }
        });
    }
    // ib client details
    ib_client_details();

    function ib_client_details() {
        $.ajax({
            url: '/manager/analysis/ib-client-details',
            method: 'GET',
            success: function(data) {
                // active clients
                $("#ib-total-active").text(data.active_total)
                $("#ib-direct-active").text(data.active_direct)
                $("#ib-fromIB-active").text(data.active_affiliated)
                // disabled ib
                $("#ib-total-disabled").text(data.disabled_total)
                $("#ib-direct-disabled").text(data.disabled_direct)
                $("#ib-fromIB-disabled").text(data.disabled_affiliated)
            }
        });
    }
</script>
@stop