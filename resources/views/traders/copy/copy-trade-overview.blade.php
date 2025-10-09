@extends('layouts.trader-layout')
@section('title','Copy Trade Overview')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/copy/jquery.dataTables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/copy/ladda-themeless.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/copy/ladda.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/copy/copy_main.css') }}" />
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
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pamm</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Pamm Copy User</li>
    </ol>
    <h6 class="font-weight-bolder mb-0"></h6>
</nav>
@stop
@section('content')
<div class="container-fluid">
    <div class="container">
        <div class="row" style="margin-top: 40px;">
            <div class="col-sm-4">
                <div class="upper-profile">
                    <img src="{{asset('/trader-assets/assets/img/copy/bangladesh.png')}}" alt="bangladesh" class="small-icon" height="22" width="30">
                    <div class="logo float-left">
                        <img src="{{asset('/trader-assets/assets/img/copy/logo.jpg')}}" alt="logo">
                    </div>
                    <div class="profile-about float-left">
                        <h2 class="profile-name">GunnenFX</h2>
                        <p class="title"><img src="{{asset('/trader-assets/assets/img/copy/star.png')}}" alt="star" height="15" width="15">
                            Hight achiever</p>
                    </div>
                </div>
                <div class="lower-profile">
                    <button class="btn btn-outline-primary ladda-button" data-style="zoom-out" data-spinner-color="#007bff" type="button">set up copying
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </button>
                    <p>Minimum investment <b>$25</b></p>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="profile-info">
                    <div class="profile-info-head">
                        <div class="profile-info-stat">
                            <p class="state-title">risk score<img src="{{asset('/trader-assets/assets/img/copy/question.svg')}}" alt="upper-icon" class="tooltip-image" data-toggle="tooltip" title=""></p>
                            <div class="state-value">
                                <p class="font-weight-bold">1</p>
                            </div>
                        </div>
                        <div class="profile-info-stat">
                            <p class="state-title">equity</p>
                            <p class="font-weight-bold">$141.50</p>
                        </div>
                        <div class="profile-info-stat">
                            <p class="state-title">commission</p>
                            <p class="font-weight-bold">30%</p>
                        </div>
                        <div class="profile-info-stat">
                            <p class="state-title">with us</p>
                            <p class="font-weight-bold">147d</p>
                        </div>
                    </div>
                </div>
                <div class="profile-info-description">
                    <div class="description-title">
                        <p class="state-title">Strategy description</p>
                        <p class="small-description">
                            Start small and see how your funds grow! We trade with small lot(0.01) carefully. Gain
                            and Loss is part of trading, our top priority is to follow the risk management for small
                            accounts. Safe trading, Low risk Lets Grow Together
                        </p>
                    </div>
                    Join Chat : <a href="#" class="link" style="color: cornflowerblue;">http:://itcorner.com</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: -40px;">
        <div class="row section2">
            <div class="col-sm-6 p-0 m-0">
                <div class="left-section">
                    <h4>Performance</h4>
                    <div class="profile-info">
                        <div class="profile-info-head">
                            <div class="profile-info-stat">
                                <p class="state-title">gain</p>
                                <p class="font-weight-bold">74.52%</p>
                            </div>
                            <div class="profile-info-stat">
                                <p class="state-title">copiers</p>
                                <div class="stat-inner">
                                    <p class="font-weight-bold">7043 </p>
                                    <div class="upper_icon" style="padding: 0px 5px; margin-top: -4px;">
                                        <img src="{{asset('/trader-assets/assets/img/copy/upper.svg')}}" alt="upper-icon">
                                    </div>
                                    <p>7006 </p>
                                </div>
                            </div>
                            <div class="profile-info-stat">
                                <p class="state-title">profit and loss</p>
                                <div class="stat-inner">
                                    <div class="profit_underline">
                                        <p class="font-weight-bold">$119</p>
                                        <div class="space"></div>
                                        <p class="font-weight-bold">$0.01</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="performance-nav">
                        <div class="p-radio">
                            <div class="p-radio-label">
                                <input name="p-radio" type="radio" class="p-radio-input" value="2W">
                                <div class="p-radio-inner">2W</div>
                            </div>
                            <div class="p-radio-label">
                                <input name="p-radio" type="radio" class="p-radio-input" value="1M">
                                <div class="p-radio-inner">1M</div>
                            </div>
                            <div class="p-radio-label" style="background-color: var(--custom-primary); color: white;">
                                <input name="p-radio" type="radio" class="p-radio-input" value="3M">
                                <div class="p-radio-inner">3M</div>
                            </div>
                            <div class="p-radio-label">
                                <input name="p-radio" type="radio" class="p-radio-input" value="6M">
                                <div class="p-radio-inner">6M</div>
                            </div>
                            <div class="p-radio-label">
                                <input name="p-radio" type="radio" class="p-radio-input" value="All">
                                <div class="p-radio-inner">All</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 p-0 m-0">
                <div class="right-section">
                    <h4>Account Details</h4>
                    <div class="acc-details">
                        <div class="acc-details-head">
                            <div class="acc-details-stat">
                                <p class="state-title">floating profit
                                    <img src="{{asset('/trader-assets/assets/img/copy/question.svg')}}" alt="upper-icon" class="tooltip-image">
                                </p>
                                <p class="text-danger font-weight-bold">-$12.50</p>
                            </div>
                            <div class="acc-details-stat">
                                <p class="state-title">balance<img src="{{asset('/trader-assets/assets/img/copy/question.svg')}}" alt="upper-icon" class="tooltip-image"></p>
                                <p class="font-weight-bold">$141.50</p>
                            </div>
                            <div class="acc-details-stat">
                                <p class="state-title">master trader's bonus<img src="{{asset('/trader-assets/assets/img/copy/question.svg')}}" alt="upper-icon" class="tooltip-image"></p>
                                <p class="font-weight-bold">$0.00</p>
                            </div>
                            <div class="acc-details-stat">
                                <p class="state-title">leverage<img src="{{asset('/trader-assets/assets/img/copy/question.svg')}}" alt="upper-icon" class="tooltip-image"></p>
                                <p class="font-weight-bold">1:500</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 p-0 m-0">
                <h4>Account Details</h4>
                <div id="chart"></div>
                <div class="chart-nav">
                    <div class="p-radio">
                        <div class="p-radio-label">
                            <input name="p-radio" type="radio" class="p-radio-input" value="2W">
                            <div class="p-radio-inner">2W</div>
                        </div>
                        <div class="p-radio-label">
                            <input name="p-radio" type="radio" class="p-radio-input" value="1M">
                            <div class="p-radio-inner">1M</div>
                        </div>
                        <div class="p-radio-label">
                            <input name="p-radio" type="radio" class="p-radio-input" value="3M">
                            <div class="p-radio-inner">3M</div>
                        </div>
                        <div class="p-radio-label">
                            <input name="p-radio" type="radio" class="p-radio-input" value="6M">
                            <div class="p-radio-inner">6M</div>
                        </div>
                        <div class="p-radio-label" style="background-color: var(--custom-primary); color: white;">
                            <input name="p-radio" type="radio" class="p-radio-input" value="All">
                            <div class="p-radio-inner">All</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 p-0 m-0">
                <div class="history-nav">
                    <h4 class="p-0 m-0">History</h4>
                    <div class="nav-container">
                        <ul class="nav nav-tabs history-nav-list" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabs-1" type="button" role="tab" aria-controls="home" aria-selected="true">Closed Orders</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabs-2" type="button" role="tab" aria-controls="home" aria-selected="true"> Open Orders (1)</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabs-3" type="button" role="tab" aria-controls="home" aria-selected="true">Balance Operations</button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="tabs-1" role="tabpanel">
                        <table class="table table-borderless">
                            <thead style="border-bottom: 1px solid #90a0b9;">
                                <tr>
                                    <th class="history-fr" scope="col">TRADE</th>
                                    <th class="close-time" scope="col">CLOSE TIME</th>
                                    <th scope="col">DURATION</th>
                                    <th scope="col">PROFIT</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr colspan="4">
                                    <th>2022-03-11</th>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/arrow.svg')}}" alt="arrow">
                                        <p class="table-dgt">0.01</p>
                                        <p class="table-currency">usdjpy</p>
                                    </td>
                                    <td>04:58</td>
                                    <td>24m 27s</td>
                                    <td>$0.15</td>
                                </tr>
                                <tr colspan="4">
                                    <th>2022-03-11</th>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/arrow.svg')}}" alt="arrow">
                                        <p>0.01</p>
                                        <p>USDJPY</p>
                                    </td>
                                    <td>04:58</td>
                                    <td>24m 27s</td>
                                    <td>$0.15</td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/arrow.svg')}}" alt="arrow">
                                        <p>0.01</p>
                                        <p>USDJPY</p>
                                    </td>
                                    <td>04:58</td>
                                    <td>24m 27s</td>
                                    <td>$0.15</td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/arrow.svg')}}" alt="arrow">
                                        <p>0.01</p>
                                        <p>USDJPY</p>
                                    </td>
                                    <td>04:58</td>
                                    <td>24m 27s</td>
                                    <td>$0.15</td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/arrow.svg')}}" alt="arrow">
                                        <p>0.01</p>
                                        <p>USDJPY</p>
                                    </td>
                                    <td>04:58</td>
                                    <td>24m 27s</td>
                                    <td>$0.15</td>
                                </tr>
                                <tr colspan="4">
                                    <th>2022-03-11</th>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/arrow.svg')}}" alt="arrow">
                                        <p>0.01</p>
                                        <p>USDJPY</p>
                                    </td>
                                    <td>04:58</td>
                                    <td>24m 27s</td>
                                    <td>$0.15</td>
                                </tr>
                                <tr style="background-color: #e7f1ff;">
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/withdraw.svg')}}" alt="arrow">
                                        <p style="text-transform: capitalize !important;">Withdraw</p>
                                    </td>
                                    <td>04:58</td>
                                    <td>&nbsp;</td>
                                    <td>$0.15</td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/arrow.svg')}}" alt="arrow">
                                        <p>0.01</p>
                                        <p>USDJPY</p>
                                    </td>
                                    <td>04:58</td>
                                    <td>24m 27s</td>
                                    <td>$0.15</td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/arrow.svg')}}" alt="arrow">
                                        <p>0.01</p>
                                        <p>USDJPY</p>
                                    </td>
                                    <td>04:58</td>
                                    <td>24m 27s</td>
                                    <td>$0.15</td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/arrow.svg')}}" alt="arrow">
                                        <p>0.01</p>
                                        <p>USDJPY</p>
                                    </td>
                                    <td>04:58</td>
                                    <td>24m 27s</td>
                                    <td>$0.15</td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/arrow.svg')}}" alt="arrow">
                                        <p>0.01</p>
                                        <p>USDJPY</p>
                                    </td>
                                    <td>04:58</td>
                                    <td>24m 27s</td>
                                    <td>$0.15</td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <div class="history-more-button">
                                            <div class="lower-profile">
                                                <button class="btn btn-outline-primary ladda-button" data-style="zoom-out" data-spinner-color="#007bff" type="button">show more
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="tabs-2" role="tabpanel">
                        <table class="table table-borderless">
                            <thead style="border-bottom: 1px solid #90a0b9;">
                                <tr>
                                    <th class="history-fr" scope="col">TRADE</th>
                                    <th class="close-time" scope="col">CLOSE TIME</th>
                                    <th scope="col">DURATION</th>
                                    <th scope="col">PROFIT</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr colspan="4">
                                    <th>2022-03-11</th>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/arrow.svg')}}" alt="arrow">
                                        <p class="table-dgt">0.01</p>
                                        <p class="table-currency">usdjpy</p>
                                    </td>
                                    <td>04:58</td>
                                    <td>24m 27s</td>
                                    <td>$0.15</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="tabs-3" role="tabpanel">
                        <table class="table table-borderless">
                            <thead style="border-bottom: 1px solid #90a0b9;">
                                <tr>
                                    <th class="history-fr" scope="col">TRADE</th>
                                    <th class="close-time-balance" scope="col">CLOSE TIME</th>
                                    <th style="text-align: right;" scope="col">PROFIT</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr colspan="4">
                                    <th>2022-03-11</th>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/withdraw.svg')}}" alt="arrow">
                                        <p style="text-transform: capitalize !important;">Withdraw</p>
                                    </td>
                                    <td>04:58</td>
                                    <td style="text-align: right;">$0.15</td>
                                </tr>
                                <tr colspan="4">
                                    <th>2022-03-11</th>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/withdraw.svg')}}" alt="arrow">
                                        <p style="text-transform: capitalize !important;">Withdraw</p>
                                    </td>
                                    <td>04:58</td>
                                    <td style="text-align: right;">$0.15</td>
                                </tr>
                                <tr colspan="4">
                                    <th>2022-03-11</th>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/withdraw.svg')}}" alt="arrow">
                                        <p style="text-transform: capitalize !important;">Withdraw</p>
                                    </td>
                                    <td>04:58</td>
                                    <td style="text-align: right;">$0.15</td>
                                </tr>
                                <tr colspan="4">
                                    <th>2022-03-11</th>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/deposit.svg')}}" alt="arrow">
                                        <p style="text-transform: capitalize !important;">Deposit</p>
                                    </td>
                                    <td>04:58</td>
                                    <td style="text-align: right;">$0.15</td>
                                </tr>
                                <tr colspan="4">
                                    <th>2022-03-11</th>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/bonus.svg')}} " alt="arrow">
                                        <p style="text-transform: capitalize !important;">Bonus</p>
                                    </td>
                                    <td>04:58</td>
                                    <td style="text-align: right;">$0.15</td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/bonus-cancel.svg')}}" alt="arrow">
                                        <p style="text-transform: capitalize !important;">Bonus Cancel</p>
                                    </td>
                                    <td>04:58</td>
                                    <td style="text-align: right;">$0.15</td>
                                </tr>
                                <tr colspan="4">
                                    <th>2022-03-11</th>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/withdraw.svg')}}" alt="arrow">
                                        <p style="text-transform: capitalize !important;">Withdraw</p>
                                    </td>
                                    <td>04:58</td>
                                    <td style="text-align: right;">$0.15</td>
                                </tr>
                                <tr colspan="4">
                                    <th>2022-03-11</th>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{asset('/trader-assets/assets/img/copy/bonus.svg')}}" alt="arrow">
                                        <p style="text-transform: capitalize !important;">Bonus</p>
                                    </td>
                                    <td>04:58</td>
                                    <td style="text-align: right;">$0.15</td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <div class="history-more-button">
                                            <div class="lower-profile">
                                                <button class="btn btn-outline-primary ladda-button" data-style="zoom-out" data-spinner-color="#007bff" type="button">show more
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- include footer -->
    @include('layouts.footer')
</div>
@stop
@section('page-js')
<script src="{{asset('trader-assets/assets/js/copy/apexcharts.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/copy/spin.min.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/copy/ladda.min.js')}}"></script>
<script src="{{asset('trader-assets/assets/js/copy/copy_main.js')}}"></script>
<script>
    Ladda.bind('.ladda-button', {
        timeout: 1000
    });
</script>
@stop