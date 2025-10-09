@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Trader Dashboard')
@php use App\Services\AllFunctionService; @endphp
@section('page-css')
<!-- page css -->
@stop
<!-- bread crumb -->
@section('bread_crumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-container breadcrumb-container-light bg-body mb-0">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <!-- <li class="breadcrumb-item active " aria-current="page"></li> -->
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </ol>
</nav>
@stop
@section('content')

@php
$all_fun = new AllFunctionService();
@endphp
<div class="content-wrapper page-dashboard">
    <div class="container-fluit">
        <div class="row">
            <!-- trader volume -->
            <div class="col-xl-4">
                <!-- card trader volume -->
                <div class="card widget widget-stats">
                    <div class="card-body">
                        <div class="widget-stats-container d-flex">
                            <div class="widget-stats-icon widget-stats-icon-primary">
                                <i class="material-icons-outlined">person</i>
                            </div>
                            <div class="widget-stats-content flex-fill">
                                <span class="widget-stats-title">{{ __('page.trader') }} {{ __('page.volume') }}</span>
                                <span class="widget-stats-amount">{{ $all_fun->get_total_volume(auth()->user()->id) }}</span>
                                <span class="widget-stats-info">{{ $all_fun->get_today_volume(auth()->user()->id) }}
                                    Today Volume</span>
                            </div>
                            <div class="widget-stats-indicator widget-stats-indicator-negative align-self-start d-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Today volume">
                                <i class="material-icons">keyboard_arrow_down</i>
                                {{ AllFunctionService::volume_percent(auth()->user()->id) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- total deposit -->
            <div class="col-xl-4">
                <div class="card widget widget-stats">
                    <div class="card-body">
                        <div class="widget-stats-container d-flex">
                            <div class="widget-stats-icon widget-stats-icon-warning">
                                <i class="material-icons-outlined">paid</i>
                            </div>
                            <div class="widget-stats-content flex-fill">
                                <span class="widget-stats-title">{{ __('page.total_deposit') }}</span>
                                <span class="widget-stats-amount">&dollar;
                                    {{ $all_fun->get_total_deposit(auth()->user()->id) }}</span>
                                <span class="widget-stats-info">&dollar;
                                    {{ (AllFunctionService::pending_deposit_total(auth()->user()->id))? AllFunctionService::pending_deposit_total(auth()->user()->id) : 0 }} Pending</span>
                            </div>
                            <div class="widget-stats-indicator widget-stats-indicator-positive align-self-start d-none">
                                <i class="material-icons">keyboard_arrow_up</i>
                                {{ AllFunctionService::deposit_percent(auth()->user()->id) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- total withdraw -->
            <div class="col-xl-4">
                <div class="card widget widget-stats">
                    <div class="card-body">
                        <div class="widget-stats-container d-flex">
                            <div class="widget-stats-icon widget-stats-icon-danger">
                                <i class="material-icons-outlined">paid</i>
                            </div>
                            <div class="widget-stats-content flex-fill">
                                <span class="widget-stats-title">{{ __('page.total_withdraw') }}</span>
                                <span class="widget-stats-amount">&dollar;
                                    {{ \App\Services\BalanceService::trader_total_withdraw(auth()->user()->id) }}</span>
                                <span class="widget-stats-info">&dollar;
                                    {{ \App\Services\BalanceService::trader_total_pending_withdraw(auth()->user()->id) }} Pending</span>
                            </div>
                            <div class="widget-stats-indicator widget-stats-indicator-positive align-self-start d-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Withdraw approved">
                                <i class="material-icons">keyboard_arrow_up</i>
                                {{ AllFunctionService::withdraw_percent(auth()->user()->id) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- trading accounts -->
            <div class="col-xl-8">
                <div class="card widget widget-list" style="min-height: 221px;">
                    <div class="card-header">
                        <h5 class="card-title">{{ __('page.trading_accounts') }}<span class="badge badge-success badge-style-light">Total
                                {{ AllFunctionService::total_trading_account_of_user(auth()->user()->id) }}</span></h5>
                    </div>
                    <div class="card-body ac-card-body">
                        <!-- <span class="text-muted m-b-xs d-block">showing 5 out of 23 active tasks.</span> -->
                        @if ($trading_account_exists)
                        <ul class="table-responsive px-0 list-group-flush" id="data-list">

                        </ul>
                        @else
                        <div class="alert alert-warning alert-dismissible fade show m-3" role="alert">
                            <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                            <span class="alert-text">
                                <strong>Warning!</strong>
                                Currently you don't have any account
                                <a href="{{ route('user.trading.open-account') }}" class="text-decoration-underline">
                                    Please open an account first
                                </a>
                            </span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- balance card -->
            <div class="col-xl-4">
                <div class="card widget widget-bank-card" style="height: 220px;">
                    <div class="card-body">
                        <div class="widget-bank-card-container widget-bank-card-visa d-flex flex-column">
                            <div class="widget-bank-card-logo rounded rounded-circle bg-info rounded-2"></div>
                            <span class="widget-bank-card-balance-title">
                                BALANCE
                            </span>
                            <span class="widget-bank-card-balance">
                                &dollar; {{ $total_balance }}
                            </span>
                            <span class="widget-bank-card-number mt-auto">
                                {{ auth()->user()->name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- actual balance -->
            <div class="col-xl-6">
                <div class="card widget widget-list">
                    <div class="card-header pb-0">
                        <h6> {{ __('page.actual_balance') }}</h6>
                        <p class="text-sm d-none">
                            <i class="fa fa-arrow-up text-success d-none"></i>
                            <span class="font-weight-bold d-none">4% more</span> in 2021
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="chart rounded" style="background: #21336e;">
                            <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
                        </div>
                        <h6 class="ms-2 mt-4 mb-0">
                            <img src="{{ asset('comon-icon/metatrader.png') }}" style="width:50px">
                            @if (isset($platform->platform_type))
                            {{ __('page.MetaTrader') }}
                            {{ $platform->platform_type != 'both' ? substr($platform->platform_type, 2) : '' }}
                            @endif
                        </h6>

                        <div class="container border-radius-lg">
                            <div class="row">
                                <div class="col-4 py-3 ps-0">
                                    <a class="col-4 py-3 ps-0" href="{{ meta_download_link('windows') }}" target="_blank">
                                        <div class="d-flex mb-2">
                                            <i class="fab fa-windows" style="font-size:20px;"></i>&nbsp
                                            <p class="text-xs mb-0 font-weight-bold">Desktop</p>
                                        </div>
                                        <h4 class="font-weight-bolder"><i class="fas fa-cloud-download-alt"></i> <span style="font-weight:600 !important; font-size:12px !important;">DOWNLOAD</span>
                                        </h4>
                                        <div class="progress w-100">
                                            <div class="progress-bar bg-dark w-100" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-4 py-3 ps-0">
                                    <a class="col-4 py-3 ps-0" href="{{ meta_download_link('ios') }}" target="_blank">
                                        <div class="d-flex mb-2">
                                            <i class="fab fa-apple" style="font-size:20px; "></i>&nbsp
                                            <p class="text-xs mt-1 mb-0 font-weight-bold">iOS</p>

                                        </div>
                                        <h4 class="font-weight-bolder"><i class="fas fa-cloud-download-alt"></i> <span style="font-weight:600 !important; font-size:12px !important;">DOWNLOAD</span>
                                        </h4>
                                        <div class="progress w-100">
                                            <div class="progress-bar bg-dark w-100" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-4 py-3 ps-0">
                                    <a class="col-4 py-3 ps-0" href="{{ meta_download_link('android') }}" target="_blank">
                                        <div class="d-flex mb-2">
                                            <i class="fab fa-android" style="font-size:20px;"></i>&nbsp
                                            <p class="text-xs mt-1 mb-0 font-weight-bold">Android</p>
                                        </div>
                                        <h4 class="font-weight-bolder"><i class="fas fa-cloud-download-alt"></i> <span style="font-weight:600 !important; font-size:12px !important;">DOWNLOAD</span>
                                        </h4>
                                        <div class="progress w-100">
                                            <div class="progress-bar bg-dark w-100" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- overview -->
            <div class="col-xl-6">
                <div class="card widget widget-popular-product" style="min-height:397px">
                    <div class="card-header pb-0">
                        <h6> {{ __('page.overview') }}</h6>
                        <p class="text-sm d-none">
                            <i class="fa fa-arrow-up text-success d-none"></i>
                            <span class="font-weight-bold d-none">4% more</span> in 2021
                        </p>
                    </div>
                    <div class="card-body overview-chart">
                        <div class="widget-popular-product-container">
                            <div class="chart">
                                <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('page-js')
<script src="{{ asset('/common-js/finance.js') }}"></script>
<script src="{{ asset('/common-js/data-list.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/plugins/chartjs.min.js') }}"></script>
<script>
    // list plugin start
    var data_list = $("#data-list");
    var dataList = data_list.data_list({
        serverSide: true,
        url: '/user/dashboard',
        listPerPage: 2
    });
    var ctx = document.getElementById("chart-bars").getContext("2d");
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: JSON.parse('<?php echo $monthly; ?>'),
            datasets: [{
                label: "Monthly Amount",
                tension: 0.4,
                borderWidth: 0,
                borderRadius: 4,
                borderSkipped: false,
                backgroundColor: "#fff",
                data: JSON.parse('<?php echo $monthly_balance; ?>'),
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
                        suggestedMin: 0,
                        suggestedMax: 500,
                        beginAtZero: true,
                        padding: 15,
                        font: {
                            size: 14,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#fff"
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
                        display: false
                    },
                },
            },
        },
    });



    var ctx2 = document.getElementById("chart-line").getContext("2d");

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
            labels: JSON.parse('<?php echo $withdraw_months; ?>'),
            datasets: [{
                    label: "Withdraws",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "var(--custom-primary)",
                    borderWidth: 3,
                    backgroundColor: gradientStroke1,
                    fill: true,
                    data: JSON.parse('<?php echo $withdraw_amounts; ?>'),
                    maxBarThickness: 6

                },
                {
                    label: "Deposits",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#3A416F",
                    borderWidth: 3,
                    backgroundColor: gradientStroke2,
                    fill: true,
                    data: JSON.parse('<?php echo $deposit_amounts; ?>'),
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


    // (function() {
    //     const container = document.getElementById("globe");
    //     const canvas = container.getElementsByTagName("canvas")[0];

    //     const globeRadius = 100;
    //     const globeWidth = 4098 / 2;
    //     const globeHeight = 1968 / 2;

    //     function convertFlatCoordsToSphereCoords(x, y) {
    //         let latitude = ((x - globeWidth) / globeWidth) * -180;
    //         let longitude = ((y - globeHeight) / globeHeight) * -90;
    //         latitude = (latitude * Math.PI) / 180;
    //         longitude = (longitude * Math.PI) / 180;
    //         const radius = Math.cos(longitude) * globeRadius;

    //         return {
    //             x: Math.cos(latitude) * radius,
    //             y: Math.sin(longitude) * globeRadius,
    //             z: Math.sin(latitude) * radius
    //         };
    //     }

    //     function makeMagic(points) {
    //         const {
    //             width,
    //             height
    //         } = container.getBoundingClientRect();

    //         // 1. Setup scene
    //         const scene = new THREE.Scene();
    //         // 2. Setup camera
    //         const camera = new THREE.PerspectiveCamera(45, width / height);
    //         // 3. Setup renderer
    //         const renderer = new THREE.WebGLRenderer({
    //             canvas,
    //             antialias: true
    //         });
    //         renderer.setSize(width, height);
    //         // 4. Add points to canvas
    //         // - Single geometry to contain all points.
    //         const mergedGeometry = new THREE.Geometry();
    //         // - Material that the dots will be made of.
    //         const pointGeometry = new THREE.SphereGeometry(0.5, 1, 1);
    //         const pointMaterial = new THREE.MeshBasicMaterial({
    //             color: "#989db5",
    //         });

    //         for (let point of points) {
    //             const {
    //                 x,
    //                 y,
    //                 z
    //             } = convertFlatCoordsToSphereCoords(
    //                 point.x,
    //                 point.y,
    //                 width,
    //                 height
    //             );

    //             if (x && y && z) {
    //                 pointGeometry.translate(x, y, z);
    //                 mergedGeometry.merge(pointGeometry);
    //                 pointGeometry.translate(-x, -y, -z);
    //             }
    //         }

    //         const globeShape = new THREE.Mesh(mergedGeometry, pointMaterial);
    //         scene.add(globeShape);

    //         container.classList.add("peekaboo");

    //         // Setup orbital controls
    //         camera.orbitControls = new THREE.OrbitControls(camera, canvas);
    //         camera.orbitControls.enableKeys = false;
    //         camera.orbitControls.enablePan = false;
    //         camera.orbitControls.enableZoom = false;
    //         camera.orbitControls.enableDamping = false;
    //         camera.orbitControls.enableRotate = true;
    //         camera.orbitControls.autoRotate = true;
    //         camera.position.z = -265;

    //         function animate() {
    //             // orbitControls.autoRotate is enabled so orbitControls.update
    //             // must be called inside animation loop.
    //             camera.orbitControls.update();
    //             requestAnimationFrame(animate);
    //             renderer.render(scene, camera);
    //         }
    //         animate();
    //     }

    //     function hasWebGL() {
    //         const gl =
    //             canvas.getContext("webgl") || canvas.getContext("experimental-webgl");
    //         if (gl && gl instanceof WebGLRenderingContext) {
    //             return true;
    //         } else {
    //             return false;
    //         }
    //     }

    //     function init() {
    //         if (hasWebGL()) {
    //             window
    //             window.fetch("https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-dashboard-pro/assets/js/points.json")
    //                 .then(response => response.json())
    //                 .then(data => {
    //                     makeMagic(data.points);
    //                 });
    //         }
    //     }
    //     init();
    // })();

    // // get balance
    // $(document).on("click", ".btn-load-ac-balance", function() {
    //     let $this = $(this);
    //     let account = $(this).data('id');
    //     balance_equity($this, account, 'balance'); //finance js
    // });
    // get balance
    $(document).on("click", ".btn-load-ac-balance", function() {
        let $this = $(this);
        let account = $(this).data('id');
        alert(account);
        let b = balance_equity($this, account, 'balance'); //finance js

    });
</script>
@stop