@extends('layouts.trader-layout')
@section('title','PAMM Overview')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
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
    }

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
    }

    .multiselect-dropdown span.placeholder {
        color: #ced4da;
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
    .light-version .multiselect-dropdown-list-wrapper {
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
        background: #f7fafc;
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
        background-color: #1a1a1a;
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
</style>
@stop
@section('bread_crumb')
{!!App\Services\systems\BreadCrumbService::get_trader_breadcrumb()!!}
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
                            <h6 id="left-user-name"></h6>
                        </div>
                        <div class="ms-auto">
                            <div class="dropdown">
                                <button class="btn btn-link text-secondary ps-0 pe-2" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v text-lg"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end me-sm-n4 me-n3" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="javascript:;">Copy</a>
                                    <a class="dropdown-item" href="javascript:;">Uncopy</a>
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
                                <span class="bg-gradient-faded-dark-vertical text-sm rounded-circle text-center d-flex align-items-center justify-content-around ms-3 btn-balance-load cursor-pointer" data-account="{{ request()->ac }}" style="width: 20px; height:20px">
                                    <i class="fas fa-redo-alt"></i>
                                </span>
                            </p>
                            <h6 id="equity-container">0.00 USD</h6>
                        </li>
                        <li class="list-item list-group-item border-0">
                            <p class="text-sm py-0 my-0 d-flex">
                                Balance
                                <span class="bg-gradient-faded-dark-vertical text-sm rounded-circle text-center d-flex align-items-center justify-content-around ms-3 btn-balance-load cursor-pointer" data-account="{{ request()->ac }}" style="width: 20px; height:20px">
                                    <i class="fas fa-redo-alt"></i>
                                </span>
                            </p>
                            <h6 id="balance-container">0.00 USD</h6>
                        </li>
                    </ul>
                    <button class="btn btn-primary btn-sm w-100" type="button" id="copy-now">Copy Now</button>
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
                                Commission
                            </th>
                            <td>
                                <span id="state-share-profit">0.0</span>%
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-flush bg-gray-100 rounded-2">
                        <tr>
                            <th>
                                Greates Loss
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
                                <span id="account-min-deposit">0.00</span> USD
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Total Copier
                            </th>
                            <td>
                                <span id="account-total-copier">0</span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                With Us
                            </th>
                            <td>
                                <span id="account-with-us">0</span> Days
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Leverage
                            </th>
                            <td>
                                <span id="account-leverage">0:0</span>
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
                            <th>
                                User Name
                            </th>
                            <td id="account-state-username">
                                ---
                            </td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td id="account-state-email">
                                ---
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Maximum Deposit
                            </th>
                            <td>
                                <span id="account-max-deposit">0.00</span> USD
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- monthly daily ourly -->
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
    <div class="row">
        <div class="col-md-4">
            <!-- <button type="button" class="btn btn-block btn-default mb-3" data-bs-toggle="modal" data-bs-target="#modal-form">Form</button> -->
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
                                    <h3 class="font-weight-bolder text-info text-gradient">Copy -{{request()->ac}}</h3>
                                    <p class="mb-0">Enter your settings carefully</p>
                                </div>
                                <div class="card-body">
                                    <form role="form text-left" action="{{ route('user.pamm.trader.copy-master') }}" id="copy-slave-form">
                                        @csrf
                                        <input type="hidden" name="master_account" value="{{request()->ac}}">
                                        <!-- your account -->
                                        <div class="all-error-solve">
                                            <label>Your Account</label>
                                            <div class="input-group mb-3">
                                                <select name="account" id="slave-account" class="form-control form-select input-control">
                                                    <option value="">Choose an account</option>
                                                    @foreach ($accounts as $value)
                                                    <option value="{{ $value->account_number }}">{{ $value->account_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- copy symbol -->
                                        <div class="all-error-solve">
                                            <label>Symbol</label>
                                            <div class="input-group mb-3">
                                                <select name="symbol[]" id="copy-symbol" class="form-control form-select input-control" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="3" onchange="console.log(this.selectedOptions)">
                                                    <?= copy_symbols() ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- allocation -->
                                        <div class="all-error-solve">
                                            <label>Allocation</label>
                                            <div class="input-group mb-3">
                                                <select name="allocation" id="allocation" class="form-control form-select input-control">
                                                    <!-- <option value="">Choose an option</option> -->
                                                    <option value="100">100%</option>
                                                    <option value="30">30%</option>
                                                    <option value="40">40%</option>
                                                    <option value="50">50%</option>
                                                    <option value="80">80%</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- max trade -->
                                        <label>Max number of trade</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="100" name="max_trade">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- max volume -->
                                                <label>Max Volume</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" placeholder="100" name="max_volume">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- min trade voluem -->
                                                <label>Min Volume</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" placeholder="0.01" name="min_volume">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="button" class="btn btn-round btn-sm bg-gradient-info btn-lg w-100 mt-4 mb-0" onclick="_run(this)" data-form="copy-slave-form" data-loading="<i class='fa fa-circle-notch fa-spin fa-1x fa-fw' style='font-size:15px'></i>" data-callback="addSlaveCallBack" id="submit-copy-request" data-btnid="submit-copy-request">Submit request</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-sm mx-auto">
                                        Don't have an account?
                                        <a href="javascript:;" class="text-info text-gradient font-weight-bold">Open one</a>
                                    </p>
                                </div>
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
@section('page-js')
<!-- <script type="text/javascript" src="{{ asset('trader-assets/assets/js/datatables.min.js') }}"></script> -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>

<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>

<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>
<script src="{{ asset('common-js/custom-multiselect-v3.js') }}"></script>
<script>
    $('#copy-symbol option').prop('selected', true);
    $(document).ready(function() {
        function getLast12Months() {
            const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            const today = new Date();
            const last12Months = [];

            for (let i = 0; i < 12; i++) {
                // Get the month index (0-indexed) for the current iteration
                let monthIndex = (today.getMonth() - i) % 12;
                if (monthIndex < 0) {
                    // If monthIndex is negative, add 12 to make it positive
                    monthIndex += 12;
                }
                // Push the corresponding month abbreviation to the array
                last12Months.push(months[monthIndex]);
            }
            return last12Months;
        }
        // first chart for growth curve
        // -----------------------------------------------------------------------------
        var ctx2 = document.getElementById("chart-account-details").getContext("2d");
        var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);
        var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);
        var growth_chart = new Chart(ctx2, {
            type: "line",
            data: {
                labels: getLast12Months(),
                datasets: [{
                        label: "profit",
                        tension: 0.1,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#01c990",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        fill: true,
                        data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                        maxBarThickness: 6
                    },
                    {
                        type: "bar",
                        label: "Volume",
                        weight: 5,
                        tension: 0.4,
                        borderWidth: 0,
                        pointBackgroundColor: "#3A416F",
                        borderColor: "#3A416F",
                        backgroundColor: '#3A416F',
                        borderRadius: 4,
                        borderSkipped: false,
                        data: [20, 35, 50, 40, 300, 220, 500, 250, 400, 230, 500, 300, 256, 365],
                        maxBarThickness: 10,
                    }
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

        function update_growth_chart() {
            // Make a fetch request to your server to get the updated data
            fetch('/user/user-pamm/chart-monthly/growth?account=' + "{{request()->ac}}")
                .then(response => response.json())
                .then(data => {
                    // Update the chart with the new data
                    growth_chart.data.datasets[0].data = data.total_profits;
                    growth_chart.data.datasets[1].data = data.total_volumes;
                    growth_chart.data.labels = data.months;
                    // Update other chart options if needed
                    growth_chart.update();
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        // Call the function to initially set the chart data
        update_growth_chart();

        // Mixed chart
        // monthly----------------------------------------------------------------------
        const chart_monthly = document.getElementById("mixed-chart").getContext("2d");
        const chart_mix_monthly = new Chart(chart_monthly, {
            data: {
                labels: ["Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                        type: "bar",
                        label: "Trades",
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
                        label: "Profits",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        // pointBackgroundColor: "#cb0c9f",
                        pointBackgroundColor: "#01c990",
                        // borderColor: "#cb0c9f",
                        borderColor: "#01c990",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        data: [20, 25, 30, 90, 40, 140, 290, 290, 340, 230, 400],
                        fill: true,
                    }
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                // aspectRatio: 1,
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
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: true,
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
        // Function to update the chart with new data
        function update_monthly_line_chart() {
            // Make a fetch request to your server to get the updated data
            fetch('/user/user-pamm/chart-monthly/linechart?account=' + "{{request()->ac}}")
                .then(response => response.json())
                .then(data => {
                    // Assuming your data structure is similar to the original chart data
                    const trade_per_month = data.trade_per_month;

                    const copier_per_month = data.copier_per_month;


                    // Update the chart with the new data
                    chart_mix_monthly.data.datasets[0].data = trade_per_month;
                    chart_mix_monthly.data.datasets[1].data = copier_per_month;
                    chart_mix_monthly.data.labels = data.months;

                    // Update other chart options if needed
                    chart_mix_monthly.update();
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        // Call the function to initially set the chart data
        update_monthly_line_chart();
        // ----------------------------------------------------------------------------
        // Mixed chart
        // daily
        // ----------------------------------------------------------------------------
        const chart_daily = document.getElementById("mixed-chart-daily").getContext("2d");
        const chart_mix_daily = new Chart(chart_daily, {
            data: {
                labels: ["01", "22", "03", "04", "05", "06", "07", "08", "09", "10"],
                datasets: [{
                        type: "bar",
                        label: "Trades",
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
                        label: "Copier",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        // pointBackgroundColor: "#cb0c9f",
                        pointBackgroundColor: "#01c990",
                        // borderColor: "#cb0c9f",
                        borderColor: "#01c990",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        data: [20, 25, 30, 90, 40, 140, 290, 290, 340, 230],
                        fill: true,
                    }
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 5,
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
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: true,
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

        // Function to update the chart with new data
        function update_daily_line_chart() {
            // Make a fetch request to your server to get the updated data
            fetch('/user/user-pamm/chart-monthly/linechart?account=' + "{{request()->ac}}")
                .then(response => response.json())
                .then(data => {
                    // Assuming your data structure is similar to the original chart data
                    const trade_per_month = data.trade_per_month;

                    const copier_per_month = data.copier_per_month;


                    // Update the chart with the new data
                    chart_mix_monthly.data.datasets[0].data = trade_per_month;
                    chart_mix_monthly.data.datasets[1].data = copier_per_month;
                    chart_mix_monthly.data.labels = data.months;

                    // Update other chart options if needed
                    chart_mix_monthly.update();
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        // Call the function to initially set the chart data
        update_daily_line_chart();
        // ----------------------------------------------------------------------------------
        // Mixed chart
        // hourly
        // ----------------------------------------------------------------------------------
        const chart_hourly = document.getElementById("mixed-chart-hourly").getContext("2d");
        const char_mix_hourly = new Chart(chart_hourly, {
            data: {
                labels: ["h1", "h2", "h3", "h4", "h5", "h6", "h7", "h8", "h9", "h10", "h11"],
                datasets: [{
                        type: "bar",
                        label: "Trades",
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
                        label: "Copier",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        // pointBackgroundColor: "#cb0c9f",
                        pointBackgroundColor: "#01c990",
                        // borderColor: "#cb0c9f",
                        borderColor: "#01c990",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        data: [20, 25, 30, 90, 40, 140, 290, 290, 340, 230, 400],
                        fill: true,
                    }
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                // aspectRatio: 1,
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
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: true,
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
        // ------------------------------------------------------------------------------------
        // Doughnut chart
        // monthly
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
        fetch('/user/user-pamm/chart-monthly/doughnut?account=' + "{{request()->ac}}")
            .then(response => response.json())
            .then(data => {
                // Update the chart with the fetched data
                updateDoughnutChart(data);
            })
            .catch(error => console.error('Error fetching data:', error));
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
        fetch('/user/user-pamm/chart-daily/doughnut?account=' + "{{request()->ac}}")
            .then(response => response.json())
            .then(data => {
                // Update the chart with the fetched data
                update_daily_doughnut(data);
            })
            .catch(error => console.error('Error fetching data:', error));
        // ------------------------------------------------------------------
        // hourly doughnut chart
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
        fetch('/user/user-pamm/chart-hourly/doughnut?account=' + "{{request()->ac}}")
            .then(response => response.json())
            .then(data => {
                // Update the chart with the fetched data
                update_hourly_doughnut(data);
            })
            .catch(error => console.error('Error fetching data:', error));
    });
    // --------------------------------------------------------->
    // open  datatables
    var open_trades = $('#tbl-open-trades').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/user/user-pamm/open-order/version2?account={{request()->ac}}",
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
    // Handle the change event of the custom select field
    $(document).on('change', "#open-order-length", function() {
        var selectedValue = $(this).val();
        // Update the DataTable with the new page length
        open_trades.page.len(selectedValue).draw();
    });
    // Handle the change event of the custom search input field
    $(document).on('input', "#open-order-search", function() {
        var searchValue = $(this).val();
        // Update the DataTable search
        open_trades.search(searchValue).draw();
    });
    var close_trades = $('#close-trades').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/user/user-pamm/close-order/version2?account={{request()->ac}}",
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
    // Handle the change event of the custom select field
    $(document).on('change', "#close-order-length", function() {
        var selectedValue = $(this).val();
        // Update the DataTable with the new page length
        close_trades.page.len(selectedValue).draw();
    });
    // Handle the change event of the custom search input field
    $(document).on('input', "#close-order-search", function() {
        var searchValue = $(this).val();
        // Update the DataTable search
        close_trades.search(searchValue).draw();
    });
    // -------------------------------------------------------------------------
    // trade state
    $(document).ready(function() {
        $.ajax({
            url: '/user/user-pamm/trade-state?account=' + "{{request()->ac}}",
            dataType: 'JSON',
            method: 'GET',
            success: function(data) {
                // set data to trade state
                $("#state-total-trade").text(data.total_trade);
                $("#state-profit-percent").text(data.profit_percent);
                $("#state-loss-percent").text(data.loss_percent);
                $("#state-total-volume").text(data.total_volume);
                $('#state-share-profit').text(data.commission);
                $("#state-max-loss").text(data.max_loss);
                $("#state-max-profit").text(data.max_profit);
                $("#state-average-profit").text(data.avg_profit);
                $("#state-average-loss").text(data.avg_loss);
                $("#state-best-trade").text(data.best_trade);
                // set data to account state
                $("#account-total-profit").text(data.total_profit);
                $("#account-total-loss").text(data.total_loss);
                $("#account-gain").text(data.best_trade);
                // set data to account info
                $("#account-min-deposit").text(data.min_deposit);
                $("#account-max-deposit").text(data.max_deposit);
                $("#account-total-copier").text(data.total_copier);
                $("#account-leverage").text(data.leverage);
                $("#account-with-us").text(data.with_us);

            }
        });
        // copy modal
        $(document).on('click', "#copy-now", function() {
            $("#modal-copy-form").modal('show');
        });
        // disalbe submit button
        $(document).on('click', "#submit-copy-request", function() {
            $(this).prop('disabled', true);
        });

    }); //ending of document ready function--------------->

    // callback for copy function
    function addSlaveCallBack(data) {
        if (data.status) {
            notify('success', data.message, 'Copy master');
        } else {
            notify('error', data.message, 'Copy master');
        }
        $("#submit-copy-request").prop('disabled', false);
        $.validator("copy-slave-form", data.errors);
    }
    // public function account state
    $(document).ready(function() {
        $.ajax({
            url: '/user/user-pamm/pamm-overview/account-details/' + "{{request()->ac}}",
            dataType: 'JSON',
            method: 'GET',
            success: function(response) {
                // set data to trade state
                $("#left-user-name").text(response.data.username);
                $("#account-state-username").text(response.data.username);
                $("#account-state-email").text(response.data.email);
            }
        });
    });

    class Balance {
        // required html class (btn-balance-load)
        // 1. btn-balance-load(for click button)
        // 2. api-balance (for display balance)
        // 3. api-balance-wrapper (for finding unique balance viwer)
        // 4. api-equity (for display equity)

        balance_equity(option) {
            var settings = $.extend({
                account_number: null,
                platform: 'mt5',
                requestFor: 'balance'
            }, option)
            $(document).on('click', '.btn-balance-load', function() {
                var account_number = settings.account_number;
                var platform = settings.platform;
                if (account_number == null) {
                    account_number = $(this).data('account');
                }
                if (platform == null) {
                    platform = $(this).data('platform');
                }
                if (typeof platform == 'undefined') {
                    platform = 'mt5';
                }
                $(this).find('.fa-redo-alt').addClass('fa-spin');
                var __this = $(this);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/user/check-balance-equity/' + account_number + '/pl/mt5',
                    dataType: 'JSON',
                    type: 'GET',
                    success: function(data) {
                        __this.find('.fa-redo-alt').removeClass('fa-spin');
                        if (data.success) {
                            $('#balance-container').text(data.balance);
                            $('#equity-container').text(data.equity);
                        }
                    }
                })
            });
        }
    }
    var apiBalance = new Balance();
    apiBalance.balance_equity()
</script>
@stop