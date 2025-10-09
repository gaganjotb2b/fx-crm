@extends('layouts.trader-layout')
@section('title','PAMM Overview')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
<style>
    .btn-check:focus+.btn-primary,
    .btn-primary:focus {
        /* color: #fff; */
        background-color: var(--custom-primary);
        border-color: unset !important;
        box-shadow: unset !important;
    }

    /* .table tbody tr:last-child td {
        border-width: 1 !important;
    } */
    #tbl-open-trades tr,
    #tbl-open-trades td:first-child,
    #close-trades tr,
    #close-trades td:first-child {
        border-left: 3px solid #4fd1c5;
    }

    #tbl-open-trades tr,
    #tbl-open-trades th:first-child,
    #close-trades tr,
    #close-trades th:first-child {
        border-left: 3px solid;
    }

    .table-trade-history {
        border-collapse: separate !important;
        border-spacing: 2px 14px !important;
    }

    .table-trade-history tr th {
        background-color: #ebf2f7;
    }

    .table-trade-history tr td {
        background-color: #ebf2f7;
    }

    #tbl-open-trades_filter,
    #tbl-open-trades_filter {
        display: none;
    }

    #close-trades_filter {
        display: none;
    }

    #mixed-chart-daily {
        height: 300px !important;
    }

    #mixed-chart-hourly {
        height: 300px !important;
    }

    .page-item .page-link,
    .page-item span {
        border-radius: 0 !important;
    }

    .dataTables_info {
        font-size: 0.95rem !important;
    }

    .dark-version .bg-gray-100 {
        background-color: rgb(35, 40, 70) !important;
    }

    .dropdown-menu {
        right: 0 !important;
    }

    .text-success {
        color: #01b245ab !important;
    }

    .border-success {
        /* border-color: #145341 !important; */
        border-color: #01b245ab !important;
    }

    .dark-version .form-label {
        color: white !important;
    }

    /* For input elements */
    .dark-version input::-webkit-input-placeholder {
        color: #adb5bd70;
        /* Set the desired color */
    }

    .dark-version input:-ms-input-placeholder {
        color: #adb5bd70;
        /* Set the desired color */
    }

    .dark-version input::-moz-placeholder {
        color: #adb5bd70;
        /* Set the desired color */
    }

    .dark-version input::placeholder {
        color: #adb5bd70;
        /* Set the desired color */
    }

    /* For select elements */
    .dark-version select::-webkit-input-placeholder {
        color: #adb5bd70;
        /* Set the desired color */
    }

    .dark-version select:-ms-input-placeholder {
        color: #adb5bd70;
        /* Set the desired color */
    }

    .dark-version select::-moz-placeholder {
        color: #adb5bd70;
        /* Set the desired color */
    }

    .dark-version select::placeholder {
        color: #adb5bd70;
        /* Set the desired color */
    }

    /* <--------start custom multiselect-----------> */
    .multiselect-dropdown {
        display: inline-block;
        padding: 2px 5px 0px 5px;
        border-radius: 4px;
        width: 100% !important;
        border: 1px solid var(--border-color);
        /* //   background-color: white; */
        position: relative;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right .75rem center;
        background-size: 16px 12px;
        height: 40px;
        display: flex;
        align-items: center;
    }

    /* 
    .multiselect-dropdown span.optext,
    .multiselect-dropdown span.placeholder {
        margin-right: 0.5em;
        margin-bottom: 2px;
        padding: 1px 0;
        border-radius: 4px;
        display: inline-block;
        background: rgba(33, 104, 255, 0.18) !important;
        font-size: 0.7rem;
    }

    .multiselect-dropdown span.optext {
        padding: 1px 0.75em;
    } */
    /* 
    .multiselect-dropdown span.optext .optdel {
        float: right;
        margin: 0 -6px 1px 5px;
        font-size: 0.7em;
        margin-top: 2px;
        cursor: pointer;
        color: #666;
    }

    .multiselect-dropdown span.optext .optdel:hover {
        color: #c66;
    } */

    /* .multiselect-dropdown span.placeholder {
        color: #ced4da;
    } */

    .multiselect-dropdown-list-wrapper {
        box-shadow: gray 0 3px 8px;
        z-index: 100;
        padding: 2px;
        border-radius: 4px;
        border: solid 1px #2165ff;
        display: none;
        margin: -1px;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        background: #fff;
    }

    .dark-version .multiselect-dropdown-list-wrapper {
        box-shadow: gray 0 3px 8px;
        z-index: 100;
        padding: 2px;
        border-radius: 4px;
        border: solid 1px #2165ff;
        display: none;
        margin: -1px;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        background: #000;
    }

    .multiselect-dropdown-list-wrapper .multiselect-dropdown-search {
        margin-bottom: 5px;
    }

    .multiselect-dropdown-list {
        padding: 2px;
        height: 15rem;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .multiselect-dropdown-list::-webkit-scrollbar {
        width: 6px;
    }

    .multiselect-dropdown-list::-webkit-scrollbar-thumb {
        background-color: #bec4ca;
        border-radius: 3px;
    }

    .multiselect-dropdown-list div {
        padding: 5px;
    }

    .multiselect-dropdown-list input {
        height: 1.15em;
        width: 1.15em;
        margin-right: 0.35em;
    }

    /* .multiselect-dropdown-list div.checked {} */

    .multiselect-dropdown-list div:hover {
        background-color: #e5e5e5;
    }

    .dark-version .multiselect-dropdown-list div:hover {
        background-color: #e5e5e5 !important;
    }

    .multiselect-dropdown span.maxselected {
        width: 100%;
    }

    .multiselect-dropdown-all-selector {
        border-bottom: solid 1px #999;
    }

    .dark-version .input-group .form-control {

        width: 100% !important;
    }

    .dark-version #toast-container>div {
        box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.7);
        color: #ea5455;
    }

    .multiselect-dropdown {
        border: 1px solid var(--bs-gray-300);
        border-radius: 7px !important;
    }

    .multiselect-dropdown .placeholder {
        background-color: transparent !important;
    }
</style>
@stop
@section('bread_crumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm">
            <a class="opacity-3 text-dark" href="javascript:;">
                <svg width="12px" height="12px" class="mb-1" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <title>shop </title>
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-1716.000000, -439.000000)" fill="#252f40" fill-rule="nonzero">
                            <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(0.000000, 148.000000)">
                                    <path d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"></path>
                                    <path d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"></path>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </a>
        </li>
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Home</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">PAMM</li>
    </ol>
    <h6 class="font-weight-bolder mb-0">Overview</h6>
</nav>
@stop
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <p class="text-sm mb-0">
                    Account Details
                </p>
                <div class="container2">
                    <div class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle " data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                            Growth
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                            <li>
                                <a class="dropdown-item" href="javascript:;">
                                    All
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:;">
                                    2 week
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:;">
                                    1 month
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:;">
                                    3 month
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card z-index-2">
                <div class="card-body p-3">
                    <!-- profile photo and name -->
                    <div class="d-flex">
                        <div class="avatar avatar-md bg-gradient-dark border-radius-md p-2">
                            <img src="{{asset('admin-assets/app-assets/images/avatars/avater-men.png')}}" alt="PAMM USER">
                        </div>
                        <div class="ms-3 my-auto">
                            <h6>{{$user_name}}</h6>
                        </div>
                        <div class="ms-auto">
                            <div class="dropdown">
                                <button class="btn btn-link text-secondary ps-0 pe-2" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v text-lg"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end me-sm-n4 me-n3" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item btn-investment" href="javascript:;">Invest</a>
                                    <!-- <a class="dropdown-item" href="javascript:;">Uncopy</a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush border-0">
                        <li class="list-item list-group-item border-0">
                            <p class="text-sm py-0 my-0">
                                Profit
                            </p>
                            <h6 class="text-success"><span id="account-total-profit" class="text-success">0.00</span> USD</h6>
                        </li>
                        <li class="list-item list-group-item border-0">
                            <p class="text-sm py-0 my-0">
                                Loss
                            </p>
                            <h6><span id="account-total-loss" class="text-danger">0.00</span> USD</h6>
                        </li>
                        <li class="list-item list-group-item border-0">
                            <p class="text-sm py-0 my-0">
                                Gain
                            </p>
                            <h6><span id="account-gain">0.00</span> %</h6>
                        </li>
                        <li class="list-item list-group-item border-0">
                            <p class="text-sm py-0 my-0 d-flex">
                                Equity
                                <!-- <span class="bg-gradient-faded-dark-vertical text-sm rounded-circle text-center d-flex align-items-center justify-content-around ms-3 btn-balance-load cursor-pointer" data-account="{{ request()->ac }}" style="width: 20px; height:20px">
                                    <i class="fas fa-redo-alt"></i>
                                </span> -->
                            </p>
                            <h6 id="equity-container">0.00 USD</h6>
                        </li>
                        <li class="list-item list-group-item border-0">
                            <p class="text-sm py-0 my-0 d-flex">
                                Balance
                                <!-- <span class="bg-gradient-faded-dark-vertical text-sm rounded-circle text-center d-flex align-items-center justify-content-around ms-3 btn-balance-load cursor-pointer" data-account="{{ request()->ac }}" style="width: 20px; height:20px">
                                    <i class="fas fa-redo-alt"></i>
                                </span> -->
                            </p>
                            <h6 id="balance-container">0.00 USD</h6>
                        </li>
                    </ul>
                    <button class="btn btn-primary btn-sm w-100" type="button" id="copy-now">Invest Now</button>
                </div>
            </div>
        </div>
        <div class="col-md-9 mt-md-0 mt-4">
            <div class="card z-index-2">
                <div class="card-body p-3" style="min-height: 462px;">
                    <div class="chart">
                        <canvas id="chart-account-details" class="chart-canvas" height="380"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-4">
            <div class="nav-wrapper position-relative end-0">
                <ul class="nav nav-pills nav-fill p-1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#profile-tabs-simple" role="tab" aria-controls="profile" aria-selected="true">
                            Trade State
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#dashboard-tabs-simple" role="tab" aria-controls="dashboard" aria-selected="false">
                            Account Info.
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="tab-content" id="pills-tabContent">
        <div class="card card-body tab-pane fade show active" id="profile-tabs-simple" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-flush bg-gray-100 rounded-2">
                        <tr>
                            <th>
                                Total Trades
                            </th>
                            <td>
                                <span id="state-total-trade">0</span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Profit %
                            </th>
                            <td>
                                <span id="state-profit-percent">0.0</span> %
                            </td>
                        </tr>
                        <tr>
                            <th>Loss %</th>
                            <td>
                                <span id="state-loss-percent">0.0</span> %
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Volume
                            </th>
                            <td>
                                <span id="state-total-volume">0.0</span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Share Profit
                            </th>
                            <td>
                                <span id="state-share-profit">{{$share_profit??0.0}}</span>%
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-flush bg-gray-100 rounded-2">
                        <tr>
                            <th>
                                Greatest Loss
                            </th>
                            <td>
                                <span id="state-max-loss">0.0</span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Greatest Profit
                            </th>
                            <td>
                                <span id="state-max-profit">0.0</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Average Profit</th>
                            <td>
                                <span id="state-average-profit">0.0</span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Average Loss
                            </th>
                            <td>
                                <span id="state-average-loss">0.0</span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Best Trade
                            </th>
                            <td>
                                <span id="state-best-trade">0.0</span> %
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card card-body tab-pane fade" id="dashboard-tabs-simple" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-flush bg-gray-100 rounded-3">
                        <tr>
                            <th>
                                Minimum Deposit
                            </th>
                            <td>
                                <span id="account-min-deposit">{{$min_deposit}}</span> USD
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Total Investor
                            </th>
                            <td>
                                <span id="account-total-copier">{{$total_investor}}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                With Us
                            </th>
                            <td>
                                <span id="account-with-us">{{$with_us}}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Leverage
                            </th>
                            <td>
                                <span id="account-leverage">1:{{$leverage??0}}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-flush bg-gray-100 rounded-3">
                        <tr>
                            <th>
                                Account Number
                            </th>
                            <td>
                                #{{request()->ac}}
                            </td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>
                                {{$user_email}}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Maximum Deposit
                            </th>
                            <td>
                                <span id="account-max-deposit">{{$max_deposit}}</span> USD
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Total invested
                            </th>
                            <td>
                                {{$total_invested}} USD
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- monthly daily hourly -->
    <div class="row mt-4">
        <div class="col-lg-4">
            <div class="nav-wrapper position-relative end-0">
                <ul class="nav nav-pills nav-fill p-1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#monthly" role="tab" aria-controls="monthly" aria-selected="true">
                            Monthly
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#daily" role="tab" aria-controls="daily" aria-selected="false">
                            Daily
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#hourly" role="tab" aria-controls="daily" aria-selected="false">
                            Hourly
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="dropdown float-end">
                <a href="javascript:;" class="dropdown-toggle " data-bs-toggle="dropdown" id="navbarDropdownMenuLink3">
                    2023
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink3">
                    <li>
                        <a class="dropdown-item" href="javascript:;">
                            2022
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:;">
                            2021
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="tab-content" id="instruments-charts">
        <!-- monthly chard -->
        <div class="card card-body tab-pane fade show active" id="monthly" role="tabpanel" aria-labelledby="pills-monthly-tab">
            <div class="row">
                <div class="col-md-7">
                    <div class="chart">
                        <canvas id="mixed-chart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="chart">
                        <canvas id="doughnut-chart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- daily chart -->
        <div class="card card-body tab-pane fade" id="daily" role="tabpanel" aria-labelledby="pills-daily-tab">
            <div class="row">
                <div class="col-md-7">
                    <div class="chart">
                        <canvas id="mixed-chart-daily" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="chart">
                        <canvas id="doughnut-chart-daily" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- hourly chart -->
        <div class="card card-body tab-pane fade" id="hourly" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="row">
                <div class="col-md-7">
                    <div class="chart">
                        <canvas id="mixed-chart-hourly" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="chart">
                        <canvas id="doughnut-chart-hourly" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- trades history -->
    <div class="row mt-4">
        <div class="col-lg-4">
            <div class="nav-wrapper position-relative end-0">
                <ul class="nav nav-pills nav-fill p-1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#open-trades" role="tab" aria-controls="open-trades" aria-selected="true">
                            Open Trades
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#closed-trades" role="tab" aria-controls="closed-trades" aria-selected="false">
                            Closed Trades
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- trade history tabs -->
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content" id="trade-history-tabs">
                <!-- monthly chard -->
                <div class="card card-body tab-pane fade show active" id="open-trades" role="tabpanel" aria-labelledby="open-trade-tab">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="d-flex">
                                <input type="text" class="form-input form-control" name="search" id="open-order-search" placeholder="Search">
                                <select name="length" style="width: 100px;" id="open-order-length" class="form-select form-control ms-2">
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                    <option value="300">300</option>
                                    <option value="500">500</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-trade-history w-100" id="tbl-open-trades">
                            <thead>
                                <tr>
                                    <th>Ticket</th>
                                    <th>Account</th>
                                    <th>Open Time</th>
                                    <th>Symbol</th>
                                    <th>Volume</th>
                                    <th>Open price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card card-body tab-pane fade" id="closed-trades" role="tabpanel" aria-labelledby="closed-trades-tab">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="d-flex">
                                <input type="search" class="form-input form-control" name="search" id="close-order-search" placeholder="Search">
                                <select name="length" style="width: 100px;" id="close-order-length" class="form-select form-control ms-2">
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                    <option value="300">300</option>
                                    <option value="500">500</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-trade-history w-100" id="close-trades">
                            <thead>
                                <tr>
                                    <th>Ticket</th>
                                    <th>Account</th>
                                    <th>Open Time</th>
                                    <th>Close Time</th>
                                    <th>Symbol</th>
                                    <th>Volume</th>
                                    <th>Open price</th>
                                    <th>Profit</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- copy modal -->
    <div class="modal fade" id="modal-copy-form" tabindex="-1" role="dialog" aria-labelledby="modal-copy-form" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="card card-plain">
                        <div class="card-header pb-0 text-left">
                            <h3 class="font-weight-bolder text-info text-gradient">Invest to -{{request()->ac}}</h3>
                            <p class="mb-0">Please read carefully before submit.</p>
                        </div>
                        <div class="card-body">
                            <form role="form text-left" action="{{ route('trader.pamm.investment') }}" id="form_invest_to_pamm">
                                @csrf
                                <input type="hidden" name="account" value="{{request()->id}}">
                                <table class="w-100">
                                    <tr>
                                        <th>
                                            Minimum Investment
                                        </th>
                                        <td class="text-end">
                                            ${{$min_deposit}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Maximum Investment
                                        </th>
                                        <td class="text-end">
                                            ${{$max_deposit}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Share profit
                                        </th>
                                        <td class="text-end">
                                            {{$share_profit}} %
                                        </td>
                                    </tr>
                                </table>
                                <hr>
                                <!-- copy symbol -->
                                <div class="form-group mb-0">
                                    <label for="invest_amount" class="form-label">Amount</label>
                                    <input type="text" class="form-control" id="invest_amount" name="amount" placeholder="0.00">
                                </div>
                                <div class="form-group mb-0">
                                    <label for="transaction_password" class="form-label">Transaction Password</label>
                                    <input type="text" class="form-control" id="transaction_password" name="transaction_password" placeholder="0.00">
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-sm bg-gradient-primary btn-lg w-100 mt-4 mb-0" onclick="_run(this)" data-form="form_invest_to_pamm" data-loading="<i class='fa fa-circle-notch fa-spin fa-1x fa-fw' style='font-size:15px'></i>" data-callback="pammInvestCallback" id="btn_submit_investment" data-btnid="btn_submit_investment">Submit</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center pt-0 px-lg-2 px-1">
                            <p class="mb-4 text-sm mx-auto">
                                Need Any help?
                                <a href="javascript:;" class="text-info text-gradient font-weight-bold">Open ticket</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footer')
</div>

@stop
@section('page-js')
<!-- push the javascript code -->
<script>
    // trade details
    $(document).ready(function() {
        let id = "{{request()->id}}";
        let account = "{{request()->ac}}";
        $.ajax({
            url: "/user/pamm/overview/trade-details",
            data: {
                id: id,
                account: account,
            },
            success: function(response) {
                $("#account-total-profit").text(response?.total_profit || 0.00);
                $("#account-total-loss").text(response?.total_loss || 0.00);
                $("#account-gain").text(response?.gain || 0.00);

                $("#state-total-trade").text(response?.trade_percent?.total_trade || 0);
                $("#state-profit-percent").text(response?.trade_percent?.profit_percentage || 0.00);
                $("#state-loss-percent").text(response?.trade_percent?.loss_percentage || 0.00);
                $("#state-total-volume").text(response?.volume || 0.00);
                $("#state-max-profit").text(`${response?.greatest_profit || 0.00} USD`);
                $("#state-max-loss").text(`${response?.greatest_loss || 0.00} USD`);
                $("#state-average-profit").text(`${response?.average_profit || 0.00} USD`);
                $("#state-average-loss").text(`${response?.average_loss || 0.00} USD`);
                $("#state-best-trade").text(`${response?.best_trade_percentage || 0.00}`);
            }
        });

        // render top growth chart
        // -----------------------
        var ctx2 = document.getElementById("chart-account-details").getContext("2d");
        var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);
        var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);
        var growth_chart = new Chart(ctx2, {
            type: "line",
            data: {
                labels: [],
                datasets: [{
                        label: "Growth",
                        tension: 0.1,
                        borderWidth: 0,
                        pointRadius: 0,
                        // borderColor: "#cb0c9f",
                        borderColor: "#01c990",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        fill: true,
                        data: [],
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + '%'; // Add % on hover
                                }
                            }
                        },
                        maxBarThickness: 6
                    },
                    {
                        label: "Equity",
                        tension: 0,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#468ED3",
                        borderWidth: 3,
                        backgroundColor: gradientStroke2,
                        fill: true,
                        yAxisID: 'yEquity',
                        maxBarThickness: 6
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#b2b9bf',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#b2b9bf',
                            padding: 10,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });

        // update growth chart by ajax request
        // -----------------------------------------
        function updateGrowthChartData() {
            $.ajax({
                url: '/user/pamm/overview/growth-chart',
                method: 'GET',
                dataType: 'json',
                data: {
                    id: "<?= request()->id ?>",
                    account: "<?= request()->ac ?>",
                },
                success: function(response) {
                    // Assuming response contains the new data in the same structure
                    growth_chart.data.labels = response.label; // Update labels if needed
                    growth_chart.data.datasets[0].data = response.data;
                    growth_chart.data.datasets[1].data = response.equity;
                    growth_chart.update(); // Refresh the chart
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        }
        updateGrowthChartData();
        // monthly chart growth / equity
        // ----------------------------------
        // monthly growth / equity
        const chart_monthly = document.getElementById("mixed-chart").getContext("2d");
        const chart_mix_monthly = new Chart(chart_monthly, {
            data: {
                labels: [], // Labels will be populated dynamically
                datasets: [{
                        type: "line", // Equity as a line
                        label: "Equity",
                        yAxisID: "yEquity", // Assign this dataset to the "yEquity" axis
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 0,
                        pointBackgroundColor: "#01c990",
                        borderColor: "#01c990",
                        backgroundColor: gradientStroke1,
                        data: [], // Data will be populated dynamically
                        fill: true,
                    },
                    {
                        type: "bar", // Growth as bars
                        label: "Growth",
                        yAxisID: "yGrowth", // Assign this dataset to the "yGrowth" axis
                        weight: 5,
                        borderWidth: 0,
                        pointBackgroundColor: "#3A416F",
                        borderColor: "#3A416F",
                        backgroundColor: '#3A416F',
                        borderRadius: 4,
                        borderSkipped: false,
                        data: [], // Data will be populated dynamically
                        maxBarThickness: 10,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || "";
                                if (label === "Growth") {
                                    return label + ": " + context.parsed.y + "%"; // Add % for Growth
                                }
                                return label + ": " + context.parsed.y; // No % for Equity
                            },
                        },
                    },
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        display: false, // Hide the default y-axis
                    },
                    yGrowth: {
                        type: "linear",
                        display: true,
                        position: "left", // Position on the left side
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: "#3A416F", // Match the bar color
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                            callback: function(value) {
                                return value + "%"; // Add % to the ticks
                            },
                        },
                        title: {
                            display: false,
                            text: "Growth (%)", // Axis title
                        },
                    },
                    yEquity: {
                        type: "linear",
                        display: true,
                        position: "left", // Position on the right side
                        grid: {
                            drawBorder: false,
                            display: false, // Hide grid lines for this axis
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: "#01c990", // Match the line color
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                        title: {
                            display: false,
                            text: "Equity", // Axis title
                        },
                        beginAtZero: true, // Ensure the y-axis starts at zero
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: true,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            display: true,
                            color: "#b2b9bf",
                            padding: 10,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                    },
                },
            },
        });

        // Function to update the chart with new data
        function update_monthly_line_chart() {
            $.ajax({
                url: `/user/pamm/overview/monthly-mix-chart`,
                data: {
                    account: "{{request()->ac}}",
                },
                method: "GET",
                dataType: "JSON",
                success: function(data) {
                    // Ensure equity starts with 0
                    if (data.equity.length > 0 && data.equity[0] !== 0) {
                        data.equity.unshift(0); // Add 0 at the beginning of equity
                        data.label.unshift("Start"); // Add a corresponding label
                        data.data.unshift(0); // Add 0 for growth data
                    }

                    // Update the chart with the new data
                    chart_mix_monthly.data.datasets[0].data = data.equity; // Equity line
                    chart_mix_monthly.data.datasets[1].data = data.data.map(growth => {
                        return growth !== null ? growth : 0; // Ensure we handle null values
                    }); // Growth bars
                    chart_mix_monthly.data.labels = data.label;

                    // Update the scales if needed
                    chart_mix_monthly.options.scales.yGrowth.min = 0; // Set minimum value for Growth axis
                    chart_mix_monthly.options.scales.yGrowth.max = Math.max(...data.data) + 10; // Add padding to the max value
                    chart_mix_monthly.options.scales.yEquity.min = 0; // Ensure Equity axis starts at zero
                    chart_mix_monthly.options.scales.yEquity.max = Math.max(...data.equity) + 10; // Add padding to the max value

                    // Update the chart
                    chart_mix_monthly.update();
                },
            });
        }

        update_monthly_line_chart();
        // update month pie chart
        // --------------------------------
        function updateDoughnutChart(data) {
            var monthly_doughnut = document.getElementById("doughnut-chart").getContext("2d");
            var chart = new Chart(monthly_doughnut, {
                type: "doughnut",
                data: {
                    labels: data.labels,
                    datasets: [{
                        weight: 9,
                        cutout: 98,
                        tension: 0.9,
                        pointRadius: 2,
                        borderWidth: 2,
                        borderColor: 'transparent',
                        backgroundColor: ['#01c990', '#2152ff', '#0086c9', '#cb0c9f', '#a8b8d8'],
                        data: data.chartData,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2.5,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                            },
                        },
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                            },
                            ticks: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                            },
                            ticks: {
                                display: false,
                            }
                        },
                    },
                },
            });
        }

        // Fetch data using AJAX
        fetch(`/user/pamm/overview/monthly-doughnut-chart?account={{request()->ac}}`)
            .then(response => response.json())
            .then(data => {
                // Update the chart with the fetched data
                updateDoughnutChart(data);
            })
            .catch(error => console.error('Error fetching data:', error));

        // Mixed chart
        // daily---------------------------------------
        const chart_daily = document.getElementById("mixed-chart-daily").getContext("2d");
        const chart_mix_daily = new Chart(chart_daily, {
            data: {
                labels: ["01", "22", "03", "04", "05", "06", "07", "08", "09", "10"],
                datasets: [{
                        type: "bar",
                        label: "Growth",
                        yAxisID: "y-growth", // Assign this dataset to the "y-growth" axis
                        weight: 5,
                        tension: 0.4,
                        borderWidth: 0,
                        pointBackgroundColor: "#3A416F",
                        borderColor: "#3A416F",
                        backgroundColor: '#3A416F',
                        borderRadius: 4,
                        borderSkipped: false,
                        data: [20, 35, 50, 40, 300, 220, 500, 250, 400, 230],
                        maxBarThickness: 10,
                    },
                    {
                        type: "line",
                        label: "Equity",
                        yAxisID: "y-equity", // Assign this dataset to the "y-equity" axis
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        pointBackgroundColor: "#01c990",
                        borderColor: "#01c990",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        data: [20, 25, 30, 90, 40, 140, 290, 290, 340, 230],
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 5,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        type: "linear", // Default y-axis
                        display: false, // Hide the default y-axis
                    },
                    "y-growth": {
                        type: "linear",
                        display: true,
                        position: "left", // Position on the left side
                        grid: {
                            drawBorder: false,
                            display: false, // Hide grid lines for this axis
                        },
                        ticks: {
                            display: true,
                            color: "#3A416F", // Match the bar color
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                        title: {
                            display: false,
                            text: "Growth", // Axis title
                        },
                    },
                    "y-equity": {
                        type: "linear",
                        display: true,
                        position: "left", // Position on the left side
                        grid: {
                            drawBorder: false,
                            display: false, // Hide grid lines for this axis
                        },
                        ticks: {
                            display: true,
                            color: "#01c990", // Match the line color
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                        title: {
                            display: false,
                            text: "Equity", // Axis title
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: true,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            display: true,
                            color: "#b2b9bf",
                            padding: 10,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                    },
                },
            },
        });

        // Function to update the chart with new data
        function update_daily_line_chart() {
            fetch('/user/pamm/overview/daily-mix-chart?account=' + "{{request()->ac}}")
                .then(response => response.json())
                .then(data => {
                    // Update the chart with the new data
                    chart_mix_daily.data.datasets[0].data = data.data;
                    chart_mix_daily.data.datasets[1].data = data.equity;
                    chart_mix_daily.data.labels = data.label;

                    // Update other chart options if needed
                    chart_mix_daily.update();
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        update_daily_line_chart();
        // ------------------------------------------------------
        // daily doughnut chart
        function update_daily_doughnut(data) {
            var daily_doughnut = document.getElementById("doughnut-chart-daily").getContext("2d");
            var chart_daily_doughnut = new Chart(daily_doughnut, {
                type: "doughnut",
                data: {
                    labels: data.labels,
                    datasets: [{
                        weight: 9,
                        cutout: 98,
                        tension: 0.9,
                        pointRadius: 2,
                        borderWidth: 2,
                        borderColor: 'transparent',
                        backgroundColor: ['#01c990', '#2152ff', '#0086c9', '#cb0c9f', '#a8b8d8'],
                        data: data.chartData,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2.5,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                            },
                        },
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                            },
                            ticks: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                            },
                            ticks: {
                                display: false,
                            }
                        },
                    },
                },
            });
        }

        // Fetch data using AJAX
        fetch(`/user/pamm/overview/daily-doughnut-chart?account={{request()->ac}}`)
            .then(response => response.json())
            .then(data => {
                // Update the chart with the fetched data
                update_daily_doughnut(data);
            })
            .catch(error => console.error('Error fetching data:', error));

        // mix chart hourly
        // --------------------------
        const chart_hourly = document.getElementById("mixed-chart-hourly").getContext("2d");
        const char_mix_hourly = new Chart(chart_hourly, {
            data: {
                labels: ["h1", "h2", "h3", "h4", "h5", "h6", "h7", "h8", "h9", "h10", "h11"],
                datasets: [{
                        type: "bar",
                        label: "Growth",
                        yAxisID: "y-growth", // Assign this dataset to the "y-growth" axis
                        weight: 5,
                        tension: 0.4,
                        borderWidth: 0,
                        pointBackgroundColor: "#3A416F",
                        borderColor: "#3A416F",
                        backgroundColor: '#3A416F',
                        borderRadius: 4,
                        borderSkipped: false,
                        data: [20, 35, 50, 40, 300, 220, 500, 250, 400, 230, 500],
                        maxBarThickness: 10,
                    },
                    {
                        type: "line",
                        label: "Equity",
                        yAxisID: "y-equity", // Assign this dataset to the "y-equity" axis
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        pointBackgroundColor: "#01c990",
                        borderColor: "#01c990",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        data: [20, 25, 30, 90, 40, 140, 290, 290, 340, 230, 400],
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        type: "linear", // Default y-axis
                        display: false, // Hide the default y-axis
                    },
                    "y-growth": {
                        type: "linear",
                        display: true,
                        position: "left", // Position on the left side
                        grid: {
                            drawBorder: false,
                            display: false, // Hide grid lines for this axis
                        },
                        ticks: {
                            display: true,
                            color: "#3A416F", // Match the bar color
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                        title: {
                            display: false,
                            text: "Growth", // Axis title
                        },
                    },
                    "y-equity": {
                        type: "linear",
                        display: true,
                        position: "left", // Position on the right side
                        grid: {
                            drawBorder: false,
                            display: false, // Hide grid lines for this axis
                        },
                        ticks: {
                            display: true,
                            color: "#01c990", // Match the line color
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                        title: {
                            display: false,
                            text: "Equity", // Axis title
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: true,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            display: true,
                            color: "#b2b9bf",
                            padding: 10,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                    },
                },
            },
        });

        function update_hourly_line_chart() {
            fetch(`/user/pamm/overview/hourly-mix-chart?account={{request()->ac}}`)
                .then(response => response.json())
                .then(data => {
                    char_mix_hourly.data.datasets[0].data = data.data;
                    char_mix_hourly.data.datasets[1].data = data.equity;
                    char_mix_hourly.data.labels = data.label;
                    char_mix_hourly.update();
                })
                .catch(error => console.error("Error fetching data:", error));
        }

        update_hourly_line_chart();

        // hourly doughnut chart
        // ---------------------------
        function update_hourly_doughnut(data) {
            var daily_doughnut = document.getElementById("doughnut-chart-hourly").getContext("2d");
            var chart_daily_doughnut = new Chart(daily_doughnut, {
                type: "doughnut",
                data: {
                    labels: data.labels,
                    datasets: [{
                        weight: 9,
                        cutout: 98,
                        tension: 0.9,
                        pointRadius: 2,
                        borderWidth: 2,
                        borderColor: 'transparent',
                        backgroundColor: ['#01c990', '#2152ff', '#0086c9', '#cb0c9f', '#a8b8d8'],
                        data: data.chartData,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2.5,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                            },
                        },
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                            },
                            ticks: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                            },
                            ticks: {
                                display: false,
                            }
                        },
                    },
                },
            });
        }

        // Fetch data using AJAX
        fetch('/user/pamm/overview/hourly-doughnut-chart?account=' + "{{request()->ac}}")
            .then(response => response.json())
            .then(data => {
                // Update the chart with the fetched data
                update_hourly_doughnut(data);
            })
            .catch(error => console.error('Error fetching data:', error));

        // datatable for open orders
        var open_trades = $('#tbl-open-trades').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/user/pamm/overview/open-trades?account={{request()->ac}}",
            },
            "searching": true,
            "lengthChange": false,
            "columns": [{
                    "data": "ticket"
                },
                {
                    "data": "account"
                },
                {
                    "data": "open_time"
                },
                {
                    "data": "symbol"
                },
                {
                    "data": "volume"
                },
                {
                    "data": "open_price"
                },
                {
                    "data": "status"
                },
            ],
            "language": {
                paginate: {
                    previous: "&laquo;",
                    next: "&raquo;",
                },
            },

        });

        // close trade
        // -----------------------------
        var close_trades = $('#close-trades').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/user/pamm/overview/close-trades?account={{request()->ac}}",
            },
            "searching": true,
            "lengthChange": false,
            "columns": [{
                    "data": "ticket"
                },
                {
                    "data": "account"
                },
                {
                    "data": "open_time"
                },
                {
                    "data": "close_time"
                },
                {
                    "data": "symbol"
                },
                {
                    "data": "volume"
                },
                {
                    "data": "open_price"
                },
                {
                    "data": "profit"
                },
            ],
            "language": {
                paginate: {
                    previous: "&laquo;",
                    next: "&raquo;",
                },
            },

        });

        // check balance equity
        // -----------------------
        $(document).on('click', ".btn-balance-load", function() {
            let account = "{{request()->ac}}";
            balanceEquity(account);
        })
        balanceEquity("{{request()->ac}}");

        function balanceEquity(account) {
            $("#equity-container, #balance-container").text(`loading....`);
            $.ajax({
                url: '/user/pamm/overview/balance-equity',
                method: 'GET',
                dataType: 'json',
                data: {
                    id: "<?= request()->id ?>",
                    account: account,
                },
                success: function(response) {
                    $("#equity-container").text(`${response.equity} USD`);
                    $("#balance-container").text(`${response.balance} USD`);
                },
            });
        }

    });
    // open the modal for invest now
    // -------------------------------
    $(document).on("click", "#copy-now, .btn-investment", function() {
        $("#modal-copy-form").modal("show");
    });
    // investment form submit
    // -------------------------
    $(document).on("click", "#btn_submit_investment", function() {
        $(this).prop('disabled', true);
    });
    // pamm invest callback
    function pammInvestCallback(response) {
        if (response.status === true) {
            notify('success', response.message, 'PAMM Investment');
            $("#modal-copy-form").modal("hide");
            $("#form_invest_to_pamm").trigger('reset');
        } else {
            notify('error', response.message, 'PAMM Investment');
        }
        $("#btn_submit_investment").prop('disabled', false);
        $.validator("form_invest_to_pamm", response.errors);
    }
</script>
@stop