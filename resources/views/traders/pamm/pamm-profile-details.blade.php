@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Pamm Copy Traders Details')
@section('page-css')
<link rel="stylesheet" href="{{asset('trader-assets/assets/plugins/datatables/datatables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/pamm/profile.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/pamm/color.css') }}" />
<link rel="stylesheet" type="text/css" href="{{asset('bootstrap-multiselect-master/dist/css/bootstrap-multiselect.min.css')}}">
<style>
    .input-group> :not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
        margin-left: 27px;
    }

    .btn-check:focus+.btn-primary,
    .btn-primary:focus {
        /* color: #fff; */
        background-color: #d1b970 !important;
        border-color: #d1b970 !important;
        box-shadow: 0 0 0 0.2rem rgba(211, 48, 173, 0.5);
    }

    .fx-frm-err {
        color: red;
    }

    .dark-version .multiselect-container .multiselect-all.active:not(.multiselect-active-item-fallback),
    .multiselect-container .multiselect-all:not(.multiselect-active-item-fallback):active,
    .multiselect-container .multiselect-group.active:not(.multiselect-active-item-fallback),
    .multiselect-container .multiselect-group:not(.multiselect-active-item-fallback):active,
    .multiselect-container .multiselect-option.active:not(.multiselect-active-item-fallback),
    .multiselect-container .multiselect-option:not(.multiselect-active-item-fallback):active {
        /*background-color: inherit;*/
        background-color: #f0f0f0 !important;
        color: #000;
    }

    .trd-row-height {
        height: 40px;
    }

    .trd-row-height {
        height: 40px;
        background-color: aliceblue;
    }

    .dataTables_empty {
        background: beige !important;
        padding: 32px 10px !important;
    }

    .ladda-button {
        background-color: red !important;
    }

    .trd-row-height.ps-3.align-items-center.d-flex.duration-fx.bg-gradient-faded-light-vertical.p-1.mb-3 {
        background: #ECF2F7 !important;
    }

    .trd-row-height.ps-3.align-items-center.d-flex.fx-profit-row.bg-gradient-faded-light-vertical.p-1.mb-3 {
        background: #ECF2F7 !important;
    }
</style>
@stop
<!-- breadcrumb -->
@section('bread_crumb')
{!!App\Services\systems\BreadCrumbService::get_trader_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid profile_main_content">
    <div class="containerdd mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="upper-profile d-flex align-items-center">
                            <img src="https://flagcdn.com/32x24/' . $flag .'.png" alt="bangladesh" class="small-icon" height="22" width="30">
                            <div class="logo float-left">
                                <img src="{{ asset('trader-assets/assets/img/pamm/user_image.png') }}" alt="logo">
                            </div>
                            <div class="profile-about float-left">
                                <h2 class="profile-name">{{ $copy_user->username }}</h2>
                                <p class="title">
                                    <img src="{{ asset('trader-assets/assets/img/pamm/logo/star.png') }}" alt="star" height="15" width="15">
                                    {{ $achiever }}
                                </p>
                            </div>
                        </div>
                        <div class="lower-profile">
                            @if($slave )
                            <button id="btn-setup-uncopy" class="btn btn-danger ladda-button d-block" style="padding:20px; background: red;" data-style="zoom-out" data-spinner-color="#007bff" type="button">
                                Uncopy
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                            @else
                            <button data-modal_title="{{ $copy_user->name }}" id="btn-setup-copying" class="btn btn-primary ladda-button" data-bs-toggle="modal" data-bs-target="#setupCopingModal" data-style="zoom-out" data-spinner-color="#007bff" type="button">set up copying
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </button>
                            @endif
                            <p>Minimum investment <b>${{ $copy_user->min_deposit }}</b></p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="profile-info">
                            <div class="profile-info-head">
                                <div class="profile-info-stat">
                                    <h4 class="text-uppercase d-flex">risk score
                                        <!-- <img src="assets/img/logo/question.svg" alt="upper-icon" class="tooltip-image" data-toggle="tooltip" title=""> -->
                                        <span class="bg-gradient-faded-dark-vertical text-sm rounded-circle text-center d-flex align-items-center justify-content-around ms-3" style="width: 20px; height:20px" data-toggle="tooltip" title="" data-original-title="Floating Profit is an aggregate Profit from all currently open positions on this Master Trader's Account.">
                                            <i class="fas fa-question"></i>
                                        </span>
                                    </h4>
                                    <div class="state-value text-center font-weight-bolder">
                                        <span class="font-weight-bold">1</span>
                                    </div>
                                </div>
                                <div class="profile-info-stat api-balance-wrapper">
                                    <h4 class="text-uppercase d-flex">equity
                                        <span class="bg-gradient-faded-dark-vertical text-sm rounded-circle text-center d-flex align-items-center justify-content-around ms-3 btn-balance-load cursor-pointer" data-account="{{ request()->ac }}" style="width: 20px; height:20px" data-toggle="tooltip" title="" data-original-title="Floating Profit is an aggregate Profit from all currently open positions on this Master Trader's Account.">
                                            <i class="fas fa-redo-alt"></i>
                                        </span>
                                    </h4>
                                    <span class="font-weight-bold">$ <span class="api-equity">0</span></span>
                                </div>
                                <div class="profile-info-stat">
                                    <h4 class="text-uppercase">commission</h4>
                                    <span class="font-weight-bold">{{ $copy_user->share_profit }}%</span>
                                </div>
                                <div class="profile-info-stat">
                                    <h4 class="text-uppercase">with us</h4>
                                    <span class="font-weight-bold">{{ $copy_user->with_us }} D</span>
                                </div>
                            </div>
                        </div>
                        <div class="profile-info-description">
                            <div class="description-title">
                                <h4>Strategy description</h4>
                                <p class="small-description">
                                    Start small and see how your funds grow! We trade with small lot(0.01) carefully.
                                    Gain
                                    and Loss is part of trading, our top priority is to follow the risk management for
                                    small
                                    accounts. Safe trading, Low risk Lets Grow Together
                                </p>
                            </div>
                            <!-- Join Chat : <a href="#" class="link"></a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="containerdd">
        <div class="row section2 ">
            <div class="col-sm-6 mt-3 ps-0">
                <div class="card">
                    <div class="left-section card-body">
                        <h4>Performance</h4>
                        <div class="profile-info">
                            <div class="profile-info-head">
                                <div class="profile-info-stat">
                                    <p class="state-title">gain</p>
                                    <p class="font-weight-bold gain_value">{{ $gain }}%</p>
                                </div>
                                <div class="profile-info-stat">
                                    <p class="state-title">copiers</p>
                                    <div class="stat-inner">
                                        <p class="font-weight-bold copiers_delta_value">
                                            {{ isset($partial_copy_user->today_copy) ? $partial_copy_user->today_copy : 0 }}
                                        </p>
                                        <div class="upper_icon" style="padding: 0px 5px; margin-top: -4px;">
                                            <img src="{{ asset('trader-assets/assets/img/pamm/logo/upper.svg') }}" alt="upper-icon">
                                        </div>
                                        <p>{{ isset($partial_copy_user->total_copy) ? $partial_copy_user->total_copy : 0 }}
                                        </p>
                                    </div>
                                </div>
                                <div class="profile-info-stat">
                                    <p class="state-title">profit and loss</p>
                                    <div class="stat-inner">
                                        <div class="profit_underline">
                                            <p class="font-weight-bold profit_value">$ <span class="profit">0</span></p>
                                            <div class="space"></div>
                                            <p class="font-weight-bold loss_value">$ <span class="loss">0</span></p>
                                            <div class="profit-loss-progress d-flex w-100">
                                                <span id="profit-underline" class="d-block border-bottom border-2 border-primary" style="width: 50%;"></span>
                                                <span id="loss-underline" class="d-block border-bottom border-2 border-secondary" style="width: 50%;"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="performance-nav justify-content-center">
                            <button type="button" class="btn btn-primary btn-rounded btn-performance bg-gradient-info me-3" data-account="{{ Request::get('ac') }}" data-duration="14">2W</button>
                            <button type="button" class="btn btn-primary btn-rounded btn-performance  me-3" data-account="{{ Request::get('ac') }}" data-duration="30">1M</button>
                            <button type="button" class="btn btn-primary btn-rounded btn-performance me-3" data-account="{{ Request::get('ac') }}" data-duration="90">3M</button>
                            <button type="button" class="btn btn-primary btn-rounded btn-performance  me-3" data-account="{{ Request::get('ac') }}" data-duration="180">6M</button>
                            <button type="button" class="btn btn-primary btn-rounded btn-performance " data-account="{{ Request::get('ac') }}" data-duration="0">All</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 p-0 m-0 mt-3">
                <div class="card account-details-se">
                    <div class="right-section card-body">
                        <h4>Account Details</h4>
                        <div class="acc-details">
                            <div class="acc-details-head">
                                <div class="acc-details-stat">
                                    <p class="state-title d-flex">floating profit
                                        <span class="bg-gradient-faded-dark-vertical rounded-circle text-center d-flex align-items-center justify-content-around ms-3" style="width: 20px; height:20px" data-toggle="tooltip" title="" data-original-title="Floating Profit is an aggregate Profit from all currently open positions on this Master Trader's Account.">
                                            <i class="fas fa-question"></i>
                                        </span>
                                    </p>
                                    <p class="font-weight-bold "> -$ <span class="floating-profit">0</span></p>
                                </div>
                                <div class="acc-details-stat api-balance-wrapper">
                                    <p class="state-title d-flex">balance
                                        <!-- <img src="{{ asset('trader-assets/assets/img/pamm/logo/question.svg') }}" alt="upper-icon" class="tooltip-image"> -->
                                        <span class="bg-gradient-faded-dark-vertical rounded-circle text-center d-flex align-items-center justify-content-around ms-3 cursor-pointer btn-balance-load" data-account="{{ request()->ac }}" style="width: 20px; height:20px" data-toggle="tooltip" title="" data-original-title="Floating Profit is an aggregate Profit from all currently open positions on this Master Trader's Account.">
                                            <!-- <i class="fas fa-question"></i> -->
                                            <i class="fas fa-redo-alt"></i>
                                        </span>
                                    </p>
                                    <p class="font-weight-bold d-flex">$ <span class="api-balance"> 0</span></p>
                                </div>
                                <div class="acc-details-stat">
                                    <p class="state-title d-flex">master trader's bonus
                                        <!-- <img src="{{ asset('trader-assets/assets/img/pamm/logo/question.svg') }}" alt="upper-icon" class="tooltip-image"> -->
                                        <span class="bg-gradient-faded-dark-vertical rounded-circle text-center d-flex align-items-center justify-content-around ms-3" style="width: 20px; height:20px" data-toggle="tooltip" title="" data-original-title="Floating Profit is an aggregate Profit from all currently open positions on this Master Trader's Account.">
                                            <i class="fas fa-question"></i>
                                        </span>
                                    </p>
                                    <p class="font-weight-bold">$0.00</p>
                                </div>
                                <div class="acc-details-stat">
                                    <p class="state-title d-flex">leverage
                                        <!-- <img src="{{ asset('trader-assets/assets/img/pamm/logo/question.svg') }}" alt="upper-icon" class="tooltip-image"> -->
                                        <span class="bg-gradient-faded-dark-vertical rounded-circle text-center d-flex align-items-center justify-content-around ms-3" style="width: 20px; height:20px" data-toggle="tooltip" title="" data-original-title="Floating Profit is an aggregate Profit from all currently open positions on this Master Trader's Account.">
                                            <i class="fas fa-question"></i>
                                        </span>
                                    </p>
                                    <p class="font-weight-bold">
                                        1:{{ isset($trading_account->leverage) && $trading_account->leverage != null ? $trading_account->leverage : 0 }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- tab section -->
            </div>
        </div>
    </div>
    <!-- tab sections -->
    <div class="containerd mt-3">
        <div class="card text-center mb-0 border-radius-bottom-end-0 border-radius-bottom-start">
            <div class="row mt-2">
                <div class="col-md-6">&nbsp;</div>
                <div class="col-md-6">
                    <ul class="nav nav-pills nav-justified" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active w-100" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Profile</button>
                        </li>
                        <li class="nav-item w-100" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Analytics</button>
                        </li>
                        <li class="nav-item w-100" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">News</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- start tab content -->
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="card border-radius-top-end-0 border-radius-top-start-0">

                    <div class="card-body">
                        <h4 class="ps-3">Account Details</h4>
                        <div id="chart"></div>
                        <div class="chart-nav">
                            <button type="button" class="btn btn-primary btn-rounded bg-gradient-info fx-follower-btn me-3" data-duration="14">2W</button>
                            <button type="button" class="btn btn-primary btn-rounded fx-follower-btn me-3" data-duration="30">1M</button>
                            <button type="button" class="btn btn-primary btn-rounded me-3 fx-follower-btn" data-duration="90">3M</button>
                            <button type="button" class="btn btn-primary btn-rounded fx-follower-btn me-3" data-duration="180">6M</button>
                            <button type="button" class="btn btn-primary btn-rounded fx-follower-btn" data-duration="">All</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="card border-radius-top-end-0 border-radius-top-start-0 shadow-card">
                    <div class="card-header border-0">
                        <h4>Trader per months</h4>
                    </div>
                    <div class="card-body">
                        <div id="apex3"></div>
                    </div>
                </div>
                <div class="card shadow-card" id="fx-instrument-chart" class="fx-instrument-chart">
                    <div class="card-header border-0">Instrtument Trades</div>
                    <div class="card-body text-center d-flex justify-content-center">
                        <div class=" bg-gradient-faded-white-vertical loader-container text-center" style="display: none" id="instrtument-card-loader">
                            <div class="spinner-border text-default" role="status">
                                <span class="sr-only"></span>
                            </div>
                        </div>
                        <canvas id="myPieChart" height="400" class="pb-5 px-5 w-auto"></canvas>
                    </div>
                </div>
                <!-- end instrument trades chart -->
                <!-- details section -->
                <div class="fx-pumm-con-without-m mt-0 border-top-fx" style="background-color: #18151e;" id="fx-more-details">

                    <div class="card bg-gradient-faded-light-vertical pt-2">
                        <div class="card-body p-0">
                            <div class="row p-0">
                                <div class="col-md-4 ps-0">
                                    <div class="details_first_section">
                                        <div class="card mb-2">
                                            <div class="card-body">
                                                <div class="item d-flex justify-content-between">
                                                    <div class="string">
                                                        <h5>Total Trades </h5>
                                                    </div>
                                                    <div class="number">
                                                        <h5 class="total-trade">0</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-2">
                                            <div class="card-body">
                                                <div class="item  d-flex justify-content-between">
                                                    <div class="string">
                                                        <h5>Deals with Stop Loss</h5>
                                                    </div>

                                                    <div class="number">
                                                        <h5>
                                                            <span class="stop-loss">0</span>
                                                            <span>( <span class="d-stop-loss"></span> )%</span>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-2">
                                            <div class="card-body">
                                                <div class="item  d-flex justify-content-between">
                                                    <div class="string">
                                                        <h5>Consecutive Wins</h5>
                                                    </div>

                                                    <div class="number">
                                                        <h5 class="consicutive-wins">0</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-2">
                                            <div class="card-body">
                                                <div class="item d-flex justify-content-between">
                                                    <div class="string">
                                                        <h5>Profitable</h5>
                                                    </div>
                                                    <div class="number">
                                                        <h5 class="profitable-trade">
                                                            <span class="profitable-trade">0</span>
                                                            <span>( <span class="profitable">0</span> )%</span>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-2">
                                            <div class="card-body">
                                                <div class="item d-flex justify-content-between">
                                                    <div class="string">
                                                        <h5>Deals with Take Profit</h5>
                                                    </div>
                                                    <div class="number">
                                                        <h5>
                                                            <span class="stop-loss">0</span>
                                                            <span>( <span class="take-profit">0</span> %)</span>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <div class="item d-flex justify-content-between">
                                                <div class="string">
                                                    <h5>Consecutive Losses</h5>
                                                </div>

                                                <div class="number">
                                                    <h5 class="consicutive-loss">0<h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <div class="item d-flex justify-content-between">
                                                <div class="string">
                                                    <h5>Unprofitable</h5>
                                                </div>

                                                <div class="number">
                                                    <h5>
                                                        <span class="un-profitable-trade">0</span>
                                                        <span>( <span class="un-profit">0</span> )%</span>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <div class="item d-flex justify-content-between">
                                                <div class="string">
                                                    <h5>Sell</h5>
                                                </div>

                                                <div class="number">
                                                    <h5>
                                                        <span class="sell">0</span>
                                                        <span>( <span class="sell-percent">0</span> )%</span>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <div class="item d-flex justify-content-between">
                                                <div class="string">
                                                    <h5>Greatest Win(Pips) </h5>
                                                </div>

                                                <div class="number">
                                                    <h5 class="greatest-win">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <div class="item d-flex justify-content-between">

                                                <div class="string">
                                                    <h5>Average Trade Length</h5>
                                                </div>

                                                <div class="number">
                                                    <h5 class="trade-length">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 pe-0">

                                    <div class="card mb-2">

                                        <div class="card-body">
                                            <div class="item d-flex justify-content-between">
                                                <div class="string">
                                                    <h5>Buy</h5>
                                                </div>
                                                <div class="number">
                                                    <h5>
                                                        <span class="sell">0</span>
                                                        <span>( <span class="buy">0</span> )%</span>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-2">

                                        <div class="card-body">
                                            <div class="item d-flex justify-content-between">
                                                <div class="string">
                                                    <h5>Greatest Loss(Pips) </h5>
                                                </div>
                                                <div class="number">
                                                    <h5 class="greatest-loss">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-2">

                                        <div class="card-body">
                                            <div class="item d-flex justify-content-between">
                                                <div class="string">
                                                    <h5>Average Daily Profit</h5>
                                                </div>
                                                <div class="number">
                                                    <h5 class="daily-profit">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-2">

                                        <div class="card-body">
                                            <div class="item d-flex justify-content-between">
                                                <div class="string">
                                                    <h5>Max Simulatneous Open Trades</h5>
                                                </div>
                                                <div class="number">
                                                    <h5 class="open-trade">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-2">

                                        <div class="card-body">
                                            <div class="item d-flex justify-content-between">
                                                <div class="string">
                                                    <h5>Average Daily Trades</h5>
                                                </div>
                                                <div class="number">
                                                    <h5 class="daily-trade">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- details section end -->
                <!-- start statistic chart -->
                <div class="card shadow-card">
                    <div class="card-header text-upper text-capitalize border-0">
                        statistics per instruments
                    </div>
                    <div class="card-body">
                        <div class=" bg-gradient-faded-white-vertical loader-container text-center" style="display: none" id="statistics-card-loader">
                            <div>
                                <div class="spinner-border text-default" role="status">
                                    <span class="sr-only"></span>
                                </div>
                            </div>
                        </div>
                        <div id="fx-statistics-chart">
                            <canvas id="statistics_chart_one" style="display: block; box-sizing: border-box; height: 400px;" class="">
                            </canvas>
                        </div>
                    </div>
                </div>
                <!-- statistic chart 2 -->
                <div class="card shadow-card">
                    <div class="card-header border-0 border-top-1"></div>
                    <div class="card-body">
                        <div class=" bg-gradient-faded-white-vertical loader-container text-center" style="display: none" id="statistics2-card-loader">
                            <div>
                                <div class="spinner-border text-default" role="status">
                                    <span class="sr-only"></span>
                                </div>
                            </div>
                        </div>
                        <div class="loss_profit_chart" id="fx-statistics-chart-two">
                            <canvas id="statistics_chart_two" style="display: block; box-sizing: border-box; height: 400px;" class="">
                            </canvas>
                        </div>
                    </div>
                </div>
                <!-- end statistic chart -->
                <!-- trade per hour -->
                <div class="card shadow-card">
                    <div class="card-header border-0">
                        Trade per hour
                    </div>
                    <div class="card-body">
                        <div class=" bg-gradient-faded-white-vertical loader-container text-center" style="display: none" id="trade-card-loader">
                            <div>
                                <div class="spinner-border text-default" role="status">
                                    <span class="sr-only"></span>
                                </div>
                            </div>
                        </div>
                        <div class="traders_by_hour_chart_section" id="fx-traders-per-hour">
                            <canvas id="traders_by_hour_chart" style="display: block; box-sizing: border-box; height: 400px;" class="">
                            </canvas>
                        </div>
                    </div>
                </div>
                <!-- end trade per hour -->
                <!-- start trade per day -->
                <div class="card shadow-card">
                    <div class="card-header border-0">Trade per day</div>
                    <div class="card-body">
                        <div class="traders_by_hour_chart_section" id="fx-traders-per-day">
                            <div class=" bg-gradient-faded-white-vertical loader-container text-center" style="display: none" id="trade-day-card-loader">
                                <div>
                                    <div class="spinner-border text-default" role="status">
                                        <span class="sr-only"></span>
                                    </div>
                                </div>
                            </div>
                            <canvas id="traders_by_day_chart" style="display: block; box-sizing: border-box; height: 400px;" class="">
                            </canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="card border-radius-top-end-0 border-radius-top-start-0">
                    <div class="card-body">
                        <h4>A new year</h4>
                        <p>Fluctuation is very big ,pay attention to risk .</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- end tab content -->
    </div>
    <div class="containerdd">
        <div class="row p-0">
            <div class="col-sm-12 p-0 m-0 mt-2">
                <div class="card card_shadow_none">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-7">
                                History
                            </div>
                            <div class="col-md-5">
                                <div class="nav-wrapper position-relative end-0">
                                    <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link mb-0 px-0 py-1 active" id="close-order-tab" data-bs-toggle="tab" href="#close-order" role="tab" aria-controls="profile" aria-selected="true">
                                                Close Order(s)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-0 px-0 py-1" id="open-order-tab" data-bs-toggle="tab" href="#open-order" role="tab" aria-controls="dashboard" aria-selected="false">
                                                Open orders(1)
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- tab 2 contents -->
                <div class="tab-content" id="historytabcontent">

                    <div class="tab-pane fade show active close-order-tab" id="close-order" role="tabpanel" aria-labelledby="close-order-tab">
                        <div class="card shadow-card">
                            <div class="card-body pt-0">
                                <!-- closed order tables -->
                                <table class="table table-borderless" id="example" width="100%">
                                    <thead style="border-bottom: 1px solid #18151e !important;">
                                        <tr>
                                            <th>TRADE</th>
                                            <th>CLOSE TIME</th>
                                            <th scope="col">DURATION</th>
                                            <th scope="col">PROFIT</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane open-order-tab" id="open-order" role="tabpanel" aria-labelledby="open-order-tab">
                        <div class="card shadow-card">
                            <div class="card-body pt-0">
                                <div class="table-response">
                                    <table class="table table-borderless" id="example2" width="100%">
                                        <thead style="border-bottom: 1px solid #18151e  !important;">
                                            <tr>
                                                <th>TRADE</th>
                                                <th> CLOSE TIME</th>
                                                <th>DURATION</th>
                                                <th>PROFIT</th>
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
                <!-- end tab 2 contents -->
            </div>
        </div>
    </div>
</div>
<!-- slave account modal -->

<div class="modal" id="setupCopingModal" tabindex="-1" aria-labelledby="setupCopingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setupCopingModalLabel">Add Slave Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="modal-body" id="slave-account-form" action="{{ route('user.copy-trades.add-slave-account') }}" method="POST">
                @csrf
                <input type="hidden" name="op" value="add_slave_ac">
                <input type="hidden" name="account" value="{{ request()->ac }}" id="master_ac_hide">
                <input type="hidden" name="server" value="" id="master_server">
                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-lg-12">
                            <div class="alert" style="display:none"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6 control-label col-lg-12" for="inputDefault">Account Number</label>
                        <div class="col-md-12">
                            <select name="slave_account" class="form-control filter-num filter-error" id="add_an_num">
                                <option value="">Select A Account</option>
                                @foreach ($my_trading_account as $value)
                                <option value="{{ $value->account_number }}">{{ $value->account_number }}
                                </option>
                                @endforeach
                            </select>
                            <!-- <div class="fx-frm-err" id="account-err">This filed is required</div> -->
                        </div>
                    </div>
                    <hr />
                    <p style="text-align:center">Risk Management ( Advance Settings )</p>
                    <hr />
                    <div class="mb-1 row">
                        <label for="symbol-edit" class="col-sm-3 col-form-label-lg">Copy Symbol</label>
                        <div class="col-sm-9">
                            <select name="symbol[]" id="symbol-edit" class="form-control" multiple="multiple">
                                <?= copy_symbols(); ?>
                            </select>
                            <span class="symbol-error d-block text-danger" style="display: none"></span>
                        </div>
                    </div>
                    <span class="error-msg-custom" style="color: red"></span>

                    <div class="form-group input-group">
                        <label class="form-label">Alocation </label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                %
                            </span>
                            <select name="allocation" class="form-control filter-error" id="add_allocation">
                                <option value="">Select Allocation</option>
                                <option value="25">25%</option>
                                <option value="50">50%</option>
                                <option value="100">100%</option>
                                <option value="150">150%</option>
                                <option value="200">200%</option>
                                <option value="400">400%</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group input-group">
                        <label class="col-md-4 form-label">Max Number of Trade</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="fa fa-code"></i>
                            </span>
                            <input name="max_trade" type="text" class="form-control filter-num" placeholder="100" id="fx-max-trade">
                        </div>
                    </div>

                    <div class="form-group input-group">
                        <label class="col-md-4 form-label">Max Trade Volume</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="fa fa-code"></i>
                            </span>
                            <input name="max_volume" type="text" id="fx-max-volume" class="form-control filter-num" placeholder="10">
                        </div>
                    </div>

                    <div class="form-group input-group">
                        <label for="fx-min-volume" class="form-label">Min Trade Volume</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon3"><i class="fa fa-code"></i></span>
                            <input name="min_volume" id="fx-min-volume" type="text" class="form-control filter-num" placeholder="0.01">
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                <button type="button" data-label="Submit Request" id="btn-submit-request" data-btnid="btn-submit-request" data-callback="add_slave_accountCallBack" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="slave-account-form" data-el="fg" onclick="_run(this)" class="btn btn-primary">{{ __('page.submit-request') }}</button>
            </div>
        </div>
    </div>
</div>


<!-- end slave account modal -->


<div class="modal fade" id="uncopyModal" tabindex="-1" aria-labelledby="setupCopingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setupUncopyModalLabel">UnCopy Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="modal-body" id="uncopy-form" action="{{ route('user.copy-trades.uncopy-master-account') }}" method="POST">
                <input type="hidden" name="op" value="uncopy_master_ac">
                <input type="hidden" name="master_ac" value="{{ $master }}" id="master_ac">
                <input type="hidden" name="slave_ac" value="{{ $slave }}" id="slave_ac">
            </form>

            <h4 style="margin-left: 48px;">Do you like to uncopy this account ?</h4></br>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" data-label="Submit Request" id="btn-uncopy-request" data-btnid="btn-uncopy-request" data-callback="UncopyCallBack" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="uncopy-form" data-el="fg" onclick="_run(this)" class="btn btn-primary">{{ __('page.submit') }}</button>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-js')
<!-- Ladda JavaScript -->
<script type="text/javascript" src="{{ asset('trader-assets/assets/js/datatables.min.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>

<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>

<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>

<script src="{{asset('plugins/ladda/spin.min.js')}}"></script>
<script src="{{asset('plugins/ladda/ladda.min.js')}}"></script>
<script>
    Ladda.bind('.ladda-button', {
        timeout: 1000
    });
</script>
<!-- apex chart -->
<script src="{{ asset('lite-asset/assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script type="module" language="javascript" src="{{ asset('common-js/chart.js-4.0.1/package/dist/chart.js') }}"></script>
<script src="{{ asset('common-js/jquery.appear.js') }}"></script>
<script src="{{ asset('common-js/rz-jquery-appear.js') }}"></script>
<script src="{{asset('bootstrap-multiselect-master/dist/js/bootstrap-multiselect.min.js')}}"></script>
<script>
    // get partisal / some days of data
    window.addEventListener('load', function() {
        $.ajax({
            url: '/user/user-pamm/user-pamm-copy-traders-partial/' + "{{request()->ac}}",
            method: 'GET',
            dataType: 'JSON',
            success: function(data) {
                console.log(data);
            }
        })
    })
    // start multiselect
    $(document).ready(function() {
        $('#symbol-edit').multiselect({
            templates: {
                button: '<button type="button" class="multiselect dropdown-toggle btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span></button>',
            },
            includeSelectAllOption: true,
            selectAllText: true,
            selectAllText: ' Select all',
            buttonClass: 'fx-custom-select',
            inheritClass: false,
            nonSelectedText: 'Plese select symbol',
        });
    });
    // end multiselect
    $("#instrtument-card-loader").fadeIn();
    $("#statistics-card-loader").fadeIn();
    $("#statistics2-card-loader").fadeIn();
    $("#trade-card-loader").fadeIn();
    $("#trade-day-card-loader").fadeIn();
    // get performance data
    function add_slave_accountCallBack(data) {
        // console.log(data)
        if (data.success) {
            notify('success', data.message, 'Add Slave Account');
            location.reload();
        } else if (data.status) {
            notify('success', data.message, 'Add Slave Account');
        }
        if (data.success == false) {
            notify('error', data.message, 'Add slave Account');
        } else if (data.status == false) {
            notify('error', data.message, 'Add slave Account');
        }
        // if (data.errors.hasOwnProperty('symbol')) {
        //     $("#field2").closest('.input-group').next('.error-msg-custom').text(data.errors.symbol);
        //     $("#field2").closest('.input-group').next('.error-msg-custom').fadeIn();
        // } else {
        //     $("#field2").closest('.input-group').next('.error-msg-custom').fadeOut();
        // }
        $.validator("slave-account-form", data.errors);
    }
    $(document).on('click', '.btn-performance', function() {
        let __this = $(this);
        $(__this).closest(".performance-nav").find("button").removeClass("bg-gradient-info");
        $(__this).addClass("bg-gradient-info");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            dataType: "JSON",
            data: {
                ac: $(__this).data('account'),
                duration: $(__this).data('duration')
            },
            method: "POST",
            url: "{{ route('user.pamm.copy.traders') }}",
            success: function(data) {
                let profitSum = data.profitSum.toPrecision(4);
                let lossSum = data.lossSum.toPrecision(4);
                let copiers = data.copiers;
                let total = data.total;
                let gain = (total == 0) ? 0 : ((100 * profitSum) / total).toPrecision(4);
                let profit_line = (100 * profitSum) / total;
                let loss_line = (100 * lossSum) / total;

                //set the value
                console.log(loss_line);
                $('.gain_value').text(gain + '%');
                $('.copiers_delta_value').text(copiers);
                $('.profit_value').text(profitSum);
                $('.loss_value').text(lossSum);
                if ((profit_line == '0' && loss_line == '0') || (isNaN(profit_line) == true &&
                        isNaN(loss_line) == true)) {
                    console.log('ok');
                    $('#profit-underline').css('width', '0%');
                    $('#loss-underline').css('width', '100%');
                } else if (loss_line < '0') {
                    $('#profit-underline').css('width', '100%');
                    $('#loss-underline').css('width', '0%');
                } else {
                    $('#profit-underline').css('width', profit_line + '%');
                    $('#loss-underline').css('width', loss_line + '%');
                }

            }
        });
    })

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
                            // __this.closest('api-balance-wrapper').find('.api-equity').text(data.balance);
                            // __this.closest('api-balance-wrapper').find('.api-balance').text(data.balance);
                            $('.api-equity').text(data.balance);
                            $('.api-balance').text(data.balance);
                        }
                    }
                })
            });
        }
    }
    var apiBalance = new Balance();
    apiBalance.balance_equity()

    // end: get balance class and function
    // -----------------------------------------------------------
    // start: chart for profile
    // initialized apexchart for follower

    var options = {
        chart: {
            height: 350,
            type: 'area',
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        series: [{
            name: 'Followers',
            data: [11, 32, 45, 32, 34, 52, 41]
        }],

        xaxis: {
            // type: 'datetime',
            categories: ["12 jun", "2018-09-19T01:30:00", "2018-09-19T02:30:00", "2018-09-19T03:30:00",
                "2018-09-19T04:30:00", "2018-09-19T05:30:00", "2018-09-19T06:30:00"
            ],
            labels: {
                style: {
                    colors: 'rgba(94, 96, 110, .5)'
                }
            }
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm'
            },
        },
        grid: {
            borderColor: 'rgba(94, 96, 110, .5)',
            strokeDashArray: 4
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#chart"),
        options
    );

    chart.render();
    // update apexchart data for follower
    let master = "{{ request()->ac }}";
    class AccountDetails {
        ajaxCall(options) {
            var settings = $.extend({
                master: null,
                day: '',
            }, options);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/user/user-pamm/user-pamm-copy-traders-details",
                method: "POST",
                data: {
                    ac: settings.master,
                    op: 'account-details',
                    day: settings.day
                },
                dataType: "json",
                success: function(data) {
                    chart.updateOptions({
                        xaxis: {
                            categories: data.dates
                        },
                        series: [{
                            data: data.followers
                        }],
                    });
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
    }
    var AcDetails = new AccountDetails();
    // get account details for 14 days only
    AcDetails.ajaxCall({
        master: master,
        day: 14
    });
    // update apexchart data for follower by days, months, all
    $(".fx-follower-btn").click(function() {
        let duration = $(this).data("duration");
        $(this).closest(".chart-nav").find("button").removeClass("bg-gradient-info");
        $(this).addClass("bg-gradient-info");
        AcDetails.ajaxCall({
            master: master,
            day: duration
        });
    });
    // end: chart for profile
    // start chart for analytics 
    $(document).ready(function() {

        $("#profile-tab").click(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/user/user-pamm/user-pamm-copy-traders-details",
                method: "POST",
                data: {
                    ac: master,
                    op: 'more-details',
                },
                dataType: "json",
                success: function(data) {
                    var options3 = {
                        chart: {
                            height: 350,
                            type: 'bar',
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                endingShape: 'rounded',
                                borderRadius: 10
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        series: [{
                            name: 'Total',
                            data: data.traders
                        }],
                        xaxis: {
                            categories: data.months,
                            labels: {
                                style: {
                                    colors: 'rgba(94, 96, 110, .5)'
                                }
                            }
                        },
                        yaxis: {
                            title: {
                                text: 'Traders'
                            }
                        },
                        fill: {
                            opacity: 1

                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val + " Traders"
                                }
                            }
                        },
                        grid: {
                            borderColor: 'rgba(94, 96, 110, .5)',
                            strokeDashArray: 4
                        }
                    }

                    var chart3 = new ApexCharts(
                        document.querySelector("#apex3"),
                        options3
                    );

                    chart3.render();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
    });
    /************************************
     * load pie chart by onscrol
     ********************************/

    var myPieChart = new Chart(document.getElementById("myPieChart"), {
        "type": "doughnut",
        "data": {
            "labels": [],
            "datasets": [{
                "labels": [],
                "data": [],
                "backgroundColor": [
                    '#4CB1FF',
                    '#29E387',
                    '#8968C6',
                    '#FF4521',
                    '#1E82D0',
                    '#3535A0',
                    '#A52A2A',
                    '#63FFC5',
                    '#9F22BE',
                    '#00008B',
                    '#008B8B',
                    '#006400',
                ],
                borderColor: [
                    '#4CB1FF',
                    '#29E387',
                    '#8968C6',
                    '#FF4521',
                    '#1E82D0',
                    '#3535A0',
                    '#A52A2A',
                    '#63FFC5',
                    '#9F22BE',
                    '#00008B',
                    '#008B8B',
                    '#006400',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            animations: {
                tension: {
                    duration: 1000,
                    easing: 'easeInOutQuad',
                    from: 1,
                    to: 0,
                    loop: false
                }
            },
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                    },
                },
            },
        }
    });
    $("main").appearConfig('.fx-instrument-chart').on('appear2', function() {

        // this code is executed for each appeared element
        let user_ac = $("#user_id").val();
        let __this = $(this);
        $(this).find(".fx-datatbl-loader").fadeIn();
        /* instruments traders chart script */
        $.ajax({
            url: "/user/user-pamm/user-pamm-copy-traders-details",
            method: "POST",
            data: {
                ac: master,
                op: "piechart"
            },
            dataType: "json",
            success: function(data) {
                $(__this).find("#instrtument-card-loader").fadeOut();
                $.each(data.symbols, function(i, item) {
                    myPieChart.data.labels.push(item);
                });
                myPieChart.data.datasets.forEach((dataset) => {
                    $.each(data.profits, function(i, item) {
                        dataset.data.push(item);
                    });
                    $.each(data.symbols, function(i, item) {
                        dataset.labels.push(item);
                    });

                });
                myPieChart.options.plugins.legend.position = 'right';
                myPieChart.update();
            }
        });
        // }
    });

    // more details
    // $("#fx-more-details").appear(function() {
    $("main").appearConfig('#fx-more-details').on('appear2', function() {
        let __this = $(this);
        $.ajax({
            url: "/user/user-pamm/user-pamm-copy-traders-details",
            method: "POST",
            data: {
                ac: master,
                op: 'more-details',
            },
            dataType: "json",
            success: function(data) {
                $('.total-trade').text(data.total_trade);
                $('.stop-loss').text(data.stop_loss);
                $('.d-stop-loss').text(data.d_stop_loss);
                $('.consicutive-wins').text(data.consicutive_wins);
                $('.profitable-trade').text(data.profitable_trade);
                $('.profitable').text(data.profitable);
                $('.take-profit').text(data.take_profit);
                $('.consicutive-loss').text(data.consicutive_loss);
                $('.unProfitable-trade').text(data.unProfitable_trade);
                $('.unProfitable').text(data.unProfitable);
                $('.sell').text(data.sell);
                $('.sell-percent').text(data.sell_percent);
                $('.greatest-win').text(data.greatest_win);
                $('.trade-lenth').text(data.trade_lenth);
                $('.buy').text(data.buy);
                $('.greatest-loss').text(data.greatest_loss);
                $('.daily-profit').text(data.daily_profit);
                $('.open_trade').text(data.open_trade);
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
    // statistic char one
    var st_chart_one = "";
    // $(document).on('appear', '#fx-statistics-chart', function(e) {
    $("main").appearConfig('#fx-statistics-chart').on('appear2', function() {
        let user_ac = $("#user_id").val();
        let __this = $(this);
        if (st_chart_one == "") {
            $.ajax({
                url: "/user/user-pamm/user-pamm-copy-traders-details",
                method: "POST",
                data: {
                    ac: master,
                    op: 'st-per-instrument'
                },
                dataType: "json",
                success: function(data) {
                    if (data) {
                        $("#statistics-card-loader").fadeOut();
                    }
                    const statistics_chart_one = document.getElementById('statistics_chart_one')
                        .getContext('2d');
                    st_chart_one = new Chart(statistics_chart_one, {

                        type: 'bar',
                        data: {
                            labels: data.symbols,
                            datasets: [{

                                label: 'Traders',
                                data: data.profits,
                                fill: true,
                                backgroundColor: [
                                    '#04B4FF',
                                    '#04B4FF',
                                    '#04B4FF',
                                    '#04B4FF',
                                    '#04B4FF',
                                    '#04B4FF',
                                    '#04B4FF',
                                    '#04B4FF',
                                    '#04B4FF',
                                    '#04B4FF',
                                    '#04B4FF',
                                    '#04B4FF',
                                ],

                            }]
                        },
                        options: {

                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            animations: {
                                tension: {
                                    duration: 1000,
                                    easing: 'easeInOutQuad',
                                    from: 1,
                                    to: 0,
                                    loop: true
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                    },
                                },
                            },
                            scales: {
                                y: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },


                        }
                    });
                }
            })
        }
    });
    // fx statistics chart two
    var st_chart_two = "";
    // $(document).on('appear', "#fx-statistics-chart-two", function() {
    $("main").appearConfig('#fx-statistics-chart-two').on('appear2', function() {
        let user_ac = $("#user_id").val();
        let __this = $(this);
        if (st_chart_two == "") {
            $.ajax({
                url: "/user/user-pamm/user-pamm-copy-traders-details",
                method: "POST",
                data: {
                    ac: master,
                    op: 'st-chart-two'
                },
                dataType: "json",
                success: function(data) {
                    if (data) {
                        $("#statistics2-card-loader").fadeOut();
                    }
                    const statistics_chart_two = document.getElementById('statistics_chart_two')
                        .getContext('2d');
                    st_chart_two = new Chart(statistics_chart_two, {

                        type: 'bar',
                        data: {
                            labels: data.symbol,
                            datasets: [{
                                    label: 'Loss',
                                    data: data.value,
                                    fill: true,
                                    backgroundColor: [
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                    ],


                                },


                                {
                                    label: 'Profit',
                                    data: data.profit,

                                    fill: true,
                                    backgroundColor: [
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                    ],

                                },


                            ]
                        },



                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            animations: {
                                tension: {
                                    duration: 1000,
                                    easing: 'easeInOutQuad',
                                    from: 1,
                                    to: 0,
                                    loop: true
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {

                                        usePointStyle: true,
                                    },
                                },
                            },

                            scales: {
                                y: {
                                    display: false


                                },
                                /*  x: {
                                    grid: {
                                        display: false
                                    }
                                } */
                            },



                        }
                    });
                }
            });
        }
    });
    // fx trades per hour chart
    var hour_chart = "";
    // $(document).on('appear', "#fx-traders-per-hour", function() {
    $("main").appearConfig('#fx-traders-per-hour').on('appear2', function() {
        let user_ac = $("#user_id").val();
        let __this = $(this);
        if (hour_chart == "") {
            $.ajax({
                url: "/user/user-pamm/user-pamm-copy-traders-details",
                method: "POST",
                data: {
                    ac: master,
                    op: 'trade-per-hour'
                },
                dataType: "json",
                success: function(data) {
                    if (data) {
                        $("#trade-card-loader").fadeOut();
                    }
                    const traders_by_hour_chart = document.getElementById('traders_by_hour_chart')
                        .getContext('2d');
                    hour_chart = new Chart(traders_by_hour_chart, {

                        type: 'bar',
                        data: {
                            labels: ['h0', 'h1', 'h3', 'h4', 'h5', 'h6', 'h7', 'h8', 'h9',
                                'h10', 'h11', 'h12', 'h13', 'h14', 'h15', 'h15', 'h16',
                                'h17', 'h18', 'h19', 'h20', 'h21', 'h22', 'h23'
                            ],
                            datasets: [{
                                    label: 'Loss',
                                    data: data.loss_value,
                                    fill: true,
                                    backgroundColor: [
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                        '#E83E3E',
                                    ],


                                },


                                {
                                    label: 'Profit',
                                    data: data.profit_value,

                                    fill: true,
                                    backgroundColor: [
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                        '#6AD749',
                                    ],

                                },


                            ]
                        },



                        options: {
                            indexAxis: 'x',
                            responsive: true,
                            maintainAspectRatio: false,
                            animations: {
                                tension: {
                                    duration: 1000,
                                    easing: 'easeInOutQuad',
                                    from: 1,
                                    to: 0,
                                    loop: true
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {

                                        usePointStyle: true,
                                    },
                                },
                            },

                            scales: {
                                xAxes: {
                                    grid: {
                                        display: false
                                    }

                                }
                            },



                        }
                    });
                }
            });
        }
    });
    // trade per day chart
    var day_chart = "";
    // $(document).on('appear', "#fx-traders-per-day", function() {
    $("main").appearConfig('#fx-traders-per-day').on('appear2', function() {
        let user_ac = $("#user_id").val();
        let __this = $(this);
        $.ajax({
            url: "/user/user-pamm/user-pamm-copy-traders-details",
            method: "POST",
            data: {
                ac: master,
                op: 'trade-per-day'
            },
            dataType: "json",
            success: function(data) {
                if (data) {
                    $("#trade-day-card-loader").fadeOut();
                }
                const traders_by_day_chart = document.getElementById('traders_by_day_chart')
                    .getContext('2d');
                day_chart = new Chart(traders_by_day_chart, {

                    type: 'bar',
                    data: {
                        labels: data.days,
                        datasets: [{
                                label: 'Loss',
                                data: data.loss,
                                fill: true,
                                backgroundColor: [
                                    '#E83E3E',
                                    '#E83E3E',
                                    '#E83E3E',
                                    '#E83E3E',
                                    '#E83E3E',

                                ],


                            },


                            {
                                label: 'Profit',
                                data: data.profit,

                                fill: true,
                                backgroundColor: [
                                    '#6AD749',
                                    '#6AD749',
                                    '#6AD749',
                                    '#6AD749',
                                    '#6AD749',

                                ],

                            },


                        ]
                    },



                    options: {
                        indexAxis: 'x',
                        responsive: true,
                        maintainAspectRatio: false,
                        animations: {
                            tension: {
                                duration: 1000,
                                easing: 'easeInOutQuad',
                                from: 1,
                                to: 0,
                                loop: true
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {

                                    usePointStyle: true,
                                },
                            },
                        },

                        scales: {
                            xAxes: {
                                grid: {
                                    display: false
                                }

                            }
                        },



                    }
                });
                console.log(day_chart);
            }
        });
    });
    //start datatable 
    // open order datatable
    $("main").appearConfig('.open-order-tab').on('appear2', function() {
        if (!$.fn.DataTable.isDataTable('#example2')) {
            var table = $('#example2').DataTable({
                "ajax": "/user/user-pamm/user-pamm-copy-traders-details/" + master +
                    "?op=dt-open-order",
                searching: false,
                // ordering:  false,
                "lengthChange": false,
                "columns": [{
                        "data": "OpenTime"
                    },
                    {
                        "data": "space1"
                    },
                    {
                        "data": "space2"
                    },
                    {
                        "data": "space3"
                    }
                ],
                language: {
                    paginate: {
                        previous: "<",
                        next: ">",
                    },
                },
            });
        }
    })
    // close order datatable
    $("main").appearConfig('.close-order-tab').on('appear2', function() {
        if (!$.fn.DataTable.isDataTable('#example')) {
            var table = $('#example').DataTable({
                "type": "json",
                "ajax": "/user/user-pamm/user-pamm-copy-traders-details/" + master +
                    "?op=dt-close-order",
                "searching": false,
                "lengthChange": false,
                "columns": [{
                        "data": "CloseTime"
                    },
                    {
                        "data": "space1"
                    },
                    {
                        "data": "space2"
                    },
                    {
                        "data": "space3"
                    }
                ],
                language: {
                    paginate: {
                        previous: "<",
                        next: ">",
                    },
                },
            });
        }
    });
    // set up coping

    $(document).on("click", "#btn-setup-uncopy", function() {

        $("#uncopyModal").modal("show");


    });

    function UncopyCallBack(data) {
        if (data.success) {
            notify('success', data.message);
            location.reload();
        } else {

        }

    }
</script>
@stop