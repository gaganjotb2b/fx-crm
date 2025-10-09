@extends(App\Services\systems\VersionControllService::get_layout('ib'))
@section('title', 'IB Dashboard')
@section('page-css')
<style>
    .ib-balance-dollar {
        font-size: 3rem;
        display: block;
        width: 60px;
        height: 60px;
        text-align: center;
        float: right;
    }

    @media only screen and (max-width: 1718px) {
        .al-pyChart-wrapper {
            flex-direction: column;
        }

        .al-pyChart-wrapper .chart {
            margin-bottom: 10px;
        }
    }

    .apx-load {
        top: 37%;
        left: 33%;
    }

    .modal-content {
        top: 250px;
    }

    .modal-body {
        font-size: 15px;
        color: #444;
        font-weight: 300;
    }
</style>
@stop
@section('bread_crumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-container breadcrumb-container-light bg-body mb-0">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </ol>
</nav>
@stop
@section('content')
@php
use App\Services\AllFunctionService;
use App\Services\IBManagementService;
@endphp
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-7 col-md-12 mt-4 mt-lg-0">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0">{{ __('page.referale-links') }}</h6>
                        <button data-bs-toggle="modal" data-bs-target="#Referale_Links" type="button" class="btn btn-icon-only note_popup btn-rounded btn-outline-secondary mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Read Note For Referale Links">
                            <i class="fas fa-info" aria-hidden="true"></i>
                        </button>
                        <div class="modal fade" id="Referale_Links" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Referale_LinksLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="Referale_LinksLabel">Note Referale Links</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Referral marketing is a growth marketing tactic that seeks to encourage existing
                                        and past customers to recommend a brand to their friends, family, and
                                        colleagues.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Exit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center visually-hidden">
                        <span class="badge badge-md badge-dot me-4">
                            <i class="bg-success"></i>
                            <span class="text-dark text-xs">{{ __('page.facebook-ads') }}</span>
                        </span>
                        <span class="badge badge-md badge-dot me-4">
                            <i class="bg-dark"></i>
                            <span class="text-dark text-xs">{{ __('page.google-ads') }}</span>
                        </span>
                    </div>
                </div>
                @php $kyc_status = auth()->user()->kyc_status; @endphp
                @if (IBManagementService::referralLinkStatus())
                <!-- referral links -->
                <div class="card-body p-3">
                    <div class="input-group">
                        <input id="ib-referral-link" type="text" class="form-control" placeholder="https://" value="{{ $ib_referral }}">
                        <button class="btn btn-outline-primary mb-0" type="button" id="referale-link-1">{{ __('page.copy') }}</button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="trader-referral-link" placeholder="https://" aria-label="https://" aria-describedby="button-addon2" value="{{ $trader_referral }}">
                        <button class="btn btn-outline-primary mb-0" type="button" id="referale-link-2">{{ __('page.copy') }}</button>
                    </div>
                </div>
                <!-- end referral links -->
                @else
                <!-- kyc status alart -->
                <div class="card-body">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                        <span class="alert-text">
                            <strong>Warning!</strong>
                            <a href="{{ route('ib.ib-admin-account-verification') }}" class="text-decoration-underline">
                                @php
                                if ($kyc_status == 2) {
                                echo 'Please wait while your kyc approve to get your referral links';
                                } else {
                                echo 'Please first verify your kyc to get referral link';
                                }
                                @endphp
                            </a>!
                        </span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                @endif
            </div>
            <div class="card mt-4">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0">{{ __('page.last-12-month-ib-commission') }}</h6>
                        <button data-bs-toggle="modal" data-bs-target="#Month_Commission" type="button" class="btn btn-icon-only note_popup btn-rounded btn-outline-secondary mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Read Note For Last 12 Month Commission">
                            <i class="fas fa-info" aria-hidden="true"></i>
                        </button>
                        <div class="modal fade" id="Month_Commission" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Month_CommissionLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="Month_CommissionLabel">Note 12 Month Commission
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Also called a stock trading fee, this is a brokerage fee that is charged when
                                        you buy or sell stocks. You may also pay commissions or fees for buying and
                                        selling other investments, such as options or exchange-traded funds.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Exit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center d-none">
                        <span class="badge badge-md badge-dot me-4">
                            <i class="bg-success"></i>
                            <span class="text-dark text-xs">{{ __('page.facebook-ads') }}</span>
                        </span>
                        <span class="badge badge-md badge-dot me-4">
                            <i class="bg-dark"></i>
                            <span class="text-dark text-xs">{{ __('page.google-ads') }}</span>
                        </span>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="line-chart-gradient" class="chart-canvas" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5 ms-auto mt-xl-0 mt-4">
            <div class="row">
                <div class="col-12">
                    <div class="card bg-info">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8 my-auto">
                                    <div class="numbers">
                                        <p class="text-white text-sm mb-0 text-capitalize font-weight-bold opacity-7">
                                            {{ __('page.total-balance') }}
                                        </p>
                                        <h5 class="text-white font-weight-bolder mb-0">
                                            {{ $ib_balance }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <span class="ib-balance-dollar bg-body rounded-circle d-flex content-center justify-content-center align-items-center text-primary font-weight-bold">&dollar;</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1 class="text-gradient text-primary">
                                <span class="text-lg ms-n2">$</span><span id="status1" countto="21">{{ $total_commission }}</span>
                            </h1>
                            <h6 class="mb-0 font-weight-bolder">{{ __('page.total-commission') }}</h6>
                            <p class="opacity-8 mb-0 text-sm">{{ __('page.all-commission') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-md-0 mt-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1 class="text-gradient text-primary"> <span id="status2" countto="{{ $total_trader }}">{{ $total_trader }}</span> <span class="text-lg ms-n1"></span></h1>
                            <h6 class="mb-0 font-weight-bolder">{{ __('page.total-trader') }}</h6>
                            <p class="opacity-8 mb-0 text-sm">{{ __('page.all-trader') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1 class="text-gradient text-primary"> <span class="text-lg ms-n2">$</span><span id="status3" countto="87">{{ $client_deposit_balance }}</span> <span class="text-lg ms-n2"></span></h1>
                            <h6 class="mb-0 font-weight-bolder">{{ __('page.total-client-deposit') }}</h6>
                            <p class="opacity-8 mb-0 text-sm">{{ __('page.all-time') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-md-0 mt-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1 class="text-gradient text-primary"><span id="status4" countto="{{ $total_sub_ib }}">{{ $total_sub_ib }}</span> <span class="text-lg ms-n2"></span></h1>
                            <h6 class="mb-0 font-weight-bolder">{{ __('page.sub-ib') }}</h6>
                            <p class="opacity-8 mb-0 text-sm">{{ __('page.all-level') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1 class="text-gradient text-primary">
                                <span class="text-lg ms-n2">$</span><span id="status3" countto="87">{{ $todays_ib_erning }}</span> <span class="text-lg ms-n2"></span>
                            </h1>
                            <h6 class="mb-0 font-weight-bolder">{{ __('page.todays-earning') }}</h6>
                            <p class="opacity-8 mb-0 text-sm">{{ __('page.24-hours') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-md-0 mt-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1 class="text-gradient text-primary"> <span class="text-lg ms-n2">$</span><span id="status4" countto="{{ $yesterday_ib_erning }}">{{ $yesterday_ib_erning }}</span> <span class="text-lg ms-n2"></span></h1>
                            <h6 class="mb-0 font-weight-bolder">{{ __('page.yesterday-earning') }}</h6>
                            <p class="opacity-8 mb-0 text-sm">{{ __('page.24-hours') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-6 ms-auto">
            <div class="card" style="min-height:275px">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex align-items-center">
                        <h6 class="mb-0">{{ __('page.commission-by-instruments') }}</h6>
                        <button data-bs-toggle="modal" data-bs-target="#staticBackdrop" type="button" class="btn btn-icon-only note_popup btn-rounded btn-outline-secondary mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center ms-auto" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Read Note For Commission By Instruments">
                            <i class="fas fa-info"></i>
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Note For Commission</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Investing is one of the best ways to set aside money for retirement or to grow
                                        your wealth. To do so, you’ll need to open an account through a brokerage or
                                        trading platform.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Exit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-5 text-center position-relative">
                            <div class="chart">
                                <canvas id="chart-consumption" class="chart-canvas" height="197"></canvas>
                            </div>
                            <h4 class="font-weight-bold mt-n8 apx-load position-absolute">
                                <span>{{ $apx_lot }}</span>
                                <span class="d-block text-body text-sm text-muted text-reset">{{ __('page.apx-lots') }}</span>
                            </h4>
                        </div>
                        <div class="col-7">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <tbody>
                                        <?php
                                        if (empty($instruments) && empty($instruments_amount)) {
                                            for ($i = 0; $i < count($all_instrument_percent['instruments']); $i++) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-0">
                                                            <span class="badge  me-3" style="background-color: <?= $colors[$i] ?>"> </span>
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">
                                                                    {{ $all_instrument_percent['instruments'][$i] }}
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle text-center text-sm">
                                                        <span class="text-xs font-weight-bold">
                                                            {{ $all_instrument_percent['amount_percents'][$i] }} % </span>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mt-lg-0 mt-4">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card" style="height: 275px">
                        <div class="card-body p-3">
                            <h6>{{ __('page.commission-day-chart') }}</h6>
                            <div class="chart pt-3">
                                <canvas id="chart-cons-week" class="chart-canvas" height="170"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 mt-sm-0 mt-4">
                    <div class="card" style="min-height:273px;">
                        <div class="card-body text-center p-3">
                            <h6 class="text-start">{{ __('page.commission-by-referrance') }}</h6>
                            <div class="d-flex justify-content-between align-items-center al-pyChart-wrapper">
                                <div class="chart" style="width: 233px">
                                    <canvas id="pie-chart" class="chart-canvas" height="200"></canvas>
                                </div>
                                <p class="ps-1 mb-0">
                                    <span class="text-xs d-flex align-items-center">
                                        <span style="background-color:#3a416f;width: 20px; height:20px; border-radius:7px; margin-right:5px;"></span>
                                        {{ __('page.my-trader') }}
                                    </span>
                                    <span class="px-3"></span>
                                    <span class="text-xs d-flex align-items-center text-truncate">
                                        <span style="background-color:#cb0c9f; width: 20px; height:20px; border-radius:7px; margin-right:5px;">

                                        </span>
                                        {{ __('page.subib-trader') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="horizontal dark my-5">
</div>
@stop
@section('page-js')
<script src="{{ asset('/common-js/copy-js.js') }}"></script>
<script src="{{ asset('/common-js/finance.js') }}"></script>
<script src="{{ asset('/common-js/data-list.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/plugins/chartjs.min.js') }}"></script>
<script>
    $(document).on("click", "#referale-link-1", function() {
        // copy js
        $("#ib-referral-link").select();
        copy_to_clipboard("ib-referral-link"); //provide id of text container
    });
    $(document).on("click", "#referale-link-2", function() {
        // copy js
        $("#trader-referral-link").select();
        copy_to_clipboard("trader-referral-link"); //provide id of text container
    });
    var ctx2 = document.getElementById("line-chart-gradient").getContext("2d");

    var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

    var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
    gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

    new Chart(ctx2, {
        type: "line",
        data: {
            labels: JSON.parse('<?php echo $commission_months; ?>'),
            datasets: [{
                    label: "Commission",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "var(--custom-primary)",
                    borderWidth: 3,
                    backgroundColor: gradientStroke1,
                    fill: true,
                    data: JSON.parse('<?php echo $commission_amounts; ?>'),
                    maxBarThickness: 6

                },
                {
                    label: "Withdraw",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#3A416F",
                    borderWidth: 3,
                    backgroundColor: gradientStroke2,
                    fill: true,
                    data: JSON.parse('<?php echo $withdraw_amounts; ?>'),
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
                        padding: 20,
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

    // Chart Doughnut Consumption by room
    var ctx1 = document.getElementById("chart-consumption").getContext("2d");

    var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

    new Chart(ctx1, {
        type: "doughnut",
        data: {
            labels: JSON.parse('<?php echo $instruments; ?>'),
            datasets: [{
                label: "Consumption",
                weight: 9,
                cutout: 90,
                tension: 0.9,
                pointRadius: 2,
                borderWidth: 2,
                backgroundColor: JSON.parse('<?php echo $instrument_backround; ?>'),
                data: JSON.parse('<?php echo $instruments_amount; ?>'),
                fill: false
            }],
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
    // Chart Consumption by day
    var ctx = document.getElementById("chart-cons-week").getContext("2d");

    new Chart(ctx, {
        type: "bar",
        data: {
            labels: JSON.parse('<?php echo $commission_day_chart_days; ?>'),
            datasets: [{
                label: "Watts",
                tension: 0.4,
                borderWidth: 0,
                borderRadius: 4,
                borderSkipped: false,
                backgroundColor: "#3A416F",
                data: JSON.parse('<?php echo $commission_day_chart_value; ?>'),
                maxBarThickness: 6
            }, ],
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
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                    },
                    ticks: {
                        display: false
                    },
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false
                    },
                    ticks: {
                        beginAtZero: true,
                        font: {
                            size: 12,
                            family: "Open Sans",
                            style: 'normal',
                        },
                        color: "#9ca2b7"
                    },
                },
                y: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        padding: 10,
                        color: '#9ca2b7'
                    }
                },
                x: {
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
                        color: '#9ca2b7'
                    }
                },
            },
        },
    });
    // Pie chart
    var ctx4 = document.getElementById("pie-chart").getContext("2d");

    new Chart(ctx4, {
        type: "pie",
        data: {
            labels: ['My Trader', 'Sub IB Trader'],
            datasets: [{
                label: "Projects",
                weight: 9,
                cutout: 0,
                tension: 0.9,
                pointRadius: 2,
                borderWidth: 2,
                backgroundColor: ['#3a416f', '#cb0c9f'],
                data: ["<?php echo $my_trader_commission; ?>", "<?php echo $sub_ib_trader_com; ?>"],
                fill: false
            }],
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

    // get balance
    $(document).on("click", ".btn-load-ac-balance", function() {
        let $this = $(this);
        let account = $(this).data('id');
        balance_equity($this, account, 'balance'); //finance js
    });
</script>
@stop