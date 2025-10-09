@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Trader Dashboard')
@php use App\Services\AllFunctionService; @endphp
@section('page-css')
<!-- page css -->
@stop
@php
    $logo = get_favicon_icon();
@endphp
<style>
    .widget-tweet:after {
    background: transparent url(../lite-asset/assets/images/icons/mt5.svg) 0 0 no-repeat  !important;
   
}
.widget-tweet{
    height: 185px !important;
}

.widget-bank-card .widget-bank-card-mastercard::after{
    background: transparent url(../lite-asset/assets/images/icons/bitcoin.png) no-repeat !important;
}

.widget-bank-card .widget-bank-card-visa::after {
  
    background: transparent url({{$logo}}) 0 0 no-repeat  !important;
   
}

.bank-withdraw-card{
    height: 222px;
}
.widget-list .widget-list-content .widget-list-item{
    padding: 1px 0 !important;
}

.deposit_card{
    height: 150px;
}

</style>
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
            <div class="col-xl-8">
                <div class="card widget widget-action-list">
                    <div class="card-body">
                        <div class="widget-action-list-container">
                            <ul class="list-unstyled d-flex no-m">
                                <li class="widget-action-list-item">
                                    <a href="{{ meta_download_link('windows') }}" target="_blank">
                                        <span class="widget-action-list-item-icon">
                                            <i class="material-icons text-primary">computer</i>
                                        </span>
                                        <span class="widget-action-list-item-title">
                                            Desktop
                                        </span>
                                    </a>
                                </li>
                                <li class="widget-action-list-item">
                                    <a href="{{ meta_download_link('android') }}" target="_blank">
                                        <span class="widget-action-list-item-icon">
                                            <i class="material-icons-outlined text-success">android</i>
                                        </span>
                                        <span class="widget-action-list-item-title">
                                            Android
                                        </span>
                                    </a>
                                </li>
                                <li class="widget-action-list-item">
                                    <a href="{{ meta_download_link('ios') }}" target="_blank">
                                        <span class="widget-action-list-item-icon">
                                            <i class="material-icons-outlined text-danger">apple</i>
                                        </span>
                                        <span class="widget-action-list-item-title">
                                            iOS
                                        </span>
                                    </a>
                                </li>
                                <!-- <li class="widget-action-list-item">
                                    <a href="#">
                                        <span class="widget-action-list-item-icon">
                                            <i class="material-icons text-info">web</i>
                                        </span>
                                        <span class="widget-action-list-item-title">
                                            Web Trader
                                        </span>
                                    </a>
                                </li> -->
                                <li class="widget-action-list-item">
                                    <a href="{{ route('user.user-admin-account-settings') }}">
                                        <span class="widget-action-list-item-icon">
                                            <i class="material-icons text-warning">settings</i>
                                        </span>
                                        <span class="widget-action-list-item-title">
                                            Settings
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <a href="{{ meta_download_link('windows') }}">
                    <div class="card widget-tweet">
                        <div class="card-body">
                            <div class="widget-tweet-container">
                                <div class="widget-tweet-content">
                                    <p class="widget-tweet-text">Download MetaTrader The best Trading platform in industry.</p>
                                    <p class="widget-tweet-author">- Download Now</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">

                <div class="col-xl-4">
                    <div class="card widget widget-payment-request">
                        <div class="card-header">
                            <h5 class="card-title">Trader Profile<span class="badge badge-warning badge-style-light">{{ date_format(auth()->user()->created_at, "d M Y") }}</span></h5>
                        </div>
                        <div class="card-body">
                            <div class="widget-payment-request-container">
                                <div class="widget-payment-request-author">
                                    <div class="avatar m-r-sm">
                                        <img src="{{ asset('lite-asset/assets/img/avatar2.png') }}" alt="">
                                    </div>
                                    <div class="widget-payment-request-author-info">
                                        <span class="widget-payment-request-author-name">{{ auth()->user()->name }}</span>
                                        <span class="widget-payment-request-author-about">{{ auth()->user()->country }}</span>
                                    </div>
                                </div>
                                <div class="widget-payment-request-product">
                                    <div class="widget-payment-request-product-image m-r-sm">
                                        <img src="{{ asset('lite-asset/assets/img/other/facebook_logo.png') }}" class="mt-auto" alt="">
                                    </div>
                                    <div class="widget-payment-request-product-info d-flex">
                                        <div class="widget-payment-request-product-info-content">
                                            <span class="widget-payment-request-product-name">Email</span>
                                            <span class="widget-payment-request-product-about">{{ auth()->user()->email }}</span>
                                        </div>
                                        <!-- <span class="widget-payment-request-product-price">$2,399.99</span> -->
                                    </div>
                                </div>
                                <div class="widget-payment-request-info m-t-md">
                                    <div class="widget-payment-request-info-item">
                                        <span class="widget-payment-request-info-title d-block">
                                            Phone Number
                                        </span>
                                        <span class="text-muted d-block">{{ auth()->user()->phone }}</span>
                                    </div>
                                    
                                    
                                    <div class="widget-payment-request-info-item">
                                        <span class="widget-payment-request-info-title d-block">
                                            Join Date
                                        </span>
                                        <span class="text-muted d-block">{{ date_format(auth()->user()->created_at, "d M Y") }}</span>
                                    </div>
                                </div>
                                <div class="widget-payment-request-actions m-t-lg d-flex">
                                    <a href="{{ route('user.user-admin-account-settings') }}" class="btn btn-light flex-grow-1 m-r-xxs">Edit Profile</a>
                                    <a href="{{ route('user.user-admin-account-verification') }}" class="btn btn-primary flex-grow-1 m-l-xxs">Verify Account</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- trading accounts -->
                <div class="col-xl-4">
                    <div class="card widget widget-list" style="height: 460px;">
                        <div class="card-header">
                            <h5 class="card-title">{{ __('page.trading_accounts') }}<span class="badge badge-success badge-style-light">{{ AllFunctionService::total_trading_account_of_user(auth()->user()->id) }} Total</span></h5>
                        </div>
                        <div class="card-body">
                        @if ($trading_account_exists)
                            <ul class="widget-list-content list-unstyled" id="data-list">
                                
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
                                <!-- <div class="widget-bank-card-logo rounded rounded-circle bg-info rounded-2"></div> -->
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

                    <div class="card widget widget-bank-card" style="height: 220px;">
                        <div class="card-body">
                            <div class="widget-bank-card-container widget-bank-card-mastercard d-flex flex-column">
                                
                                <span class="widget-bank-card-balance-title">
                                    PENDING WITHDRAW
                                </span>
                                <span class="widget-bank-card-balance">
                                    ${{$pending_withdraw}}
                                </span>
                                <!-- <span class="widget-bank-card-number mt-auto">
                                    bc1q........06578zhsr0kju
                                </span> -->
                            </div>
                        </div>
                    </div>

                    
                </div>
        </div>
        <div class="row">
            <!-- actual balance -->
            <div class="col-xl-8">
                <div class="card widget widget-list">
                    <div class="card-header pb-0">
                        <h6> Financial Chart</h6>
                        <!-- <p class="text-sm d-none">
                            <i class="fa fa-arrow-up text-success d-none"></i>
                            <span class="font-weight-bold d-none">4% more</span> in 2021
                        </p> -->
                    </div>

                    <div class="card-body">
                        <div id="apex3"></div>
                    </div>
                </div>
            </div>
            <!-- overview -->
            <div class="col-xl-4">

                <div class="card widget widget-info-inline bank-deposit-card">
                    <div class="card-header">
                        <h5 class="card-title">Bank Deposit</h5>
                    </div>
                    <div class="card-body deposit_card">
                        <div class="widget-info-container">
                            <p class="widget-info-text">Submit Your Bank Deposit Proof As Bank Statement, Bank Receipt</p>
                            <a href="{{ route('user.deposit.bank-deposit-form') }}" class="btn btn-outline-primary widget-info-action">Bank Deposit</a>
                            <div class="widget-info-image" style="background: url(../lite-asset/assets/images/icons/bank.png)"></div>
                        </div>
                    </div>
                </div>
                <div class="card widget widget-info-inline bank-withdraw-card">
                    <div class="card-body">
                        <div class="widget-info-container">
                            <div class="widget-info-image" style="background: url(../lite-asset/assets/images/icons/withdraw.png)"></div>
                            <h5 class="widget-info-title">Withdraw Fund</h5>
                            <p class="widget-info-text m-t-n-xs">Make A withdraw request. By Bank</p>
                            <a href="{{ route('user.withdraw.bank-withdraw-form') }}" class="btn btn-primary widget-info-action">Bank Withdraw</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- trading account reports modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex">
                <img class="modal-platform-logo" src="" alt="" height="30">
                <h5 class="modal-title modal-account-number d-inline" id="exampleModalCenterTitle">
                    <div class="d-flex align-items-center">
                        <strong>Loading...</strong>
                        <div class="spinner-border ms-auto" role="status" aria-hidden="true"></div>
                    </div>
                </h5>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr class="bg-light rounded top-left">
                        <th>
                            <p class="font-weight-bold m-0 p-0 float-start">Leverage:</p>
                        </th>
                        <td>
                            <h6 class="mt-1 pt-1 cursor-pointer modal-leverage" id="modal-leverage">
                                1:---
                            </h6>
                        </td>
                    </tr>
                    <tr class="bg-light rounded top-left">
                        <th>
                            <p class="font-weight-bold m-0 p-0 float-start">Balance:</p>
                        </th>
                        <td>
                            <h6 class="mt-1 pt-1 cursor-pointer modal-account-balance" id="modal-account-balanc">$ 0.0</h6>
                        </td>
                    </tr>
                    <tr class="bg-light rounded top-left">
                        <th>
                            <p class="font-weight-bold m-0 p-0 float-start">Equity:</p>
                        </th>
                        <td>
                            <h6 class="mt-1 pt-1 cursor-pointer modal-account-equity" id="modal-account-equity">$ 0.0</h6>
                        </td>
                    </tr>
                    <tr class="bg-light rounded top-left">
                        <th style="border:none">
                            <p class="font-weight-bold m-0 p-0 float-start">Free Margin:</p>
                        </th>
                        <td>
                            <h6 class="mt-1 pt-1 cursor-pointer modal-free-margin" id="modal-free-margin">$ 0.0</h6>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
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

$(document).ready(function () {

"use strict";

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
          name: 'Deposit',
          data: <?= json_encode($deposit_array)?>,
      }, {
          name: 'Withdraw',
          data: <?= json_encode($withdraw_array)?>,
      }],
      xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          labels: {
              style: {
                  colors: 'rgba(94, 96, 110, .5)'
              }
          }
      },
      yaxis: {
          title: {
              text: '$ Dollar'
          }
      },
      fill: {
          opacity: 1

      },
      tooltip: {
          y: {
              formatter: function (val) {
                  return "$ " + val + ""
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
});
    // get balance
    $(document).on("click", ".btn-load-ac-balance", function() {
        let $this = $(this);
        let account = $(this).data('id');
        let search = $(this).data('search');
        // console.log(account);
        // balance_equity($this, account, 'balance'); //finance js
        $('.fa-redo-alt').addClass('fa-spin');
        
        $.ajax({
            url: '/user/balance-equity/' + search + '/account/' + account,
            dataType: 'json',
            method: 'get',
            success: function (data) {
                console.log(data);
                $('.fa-redo-alt').removeClass('fa-spin');
                $('.account_balance').text(data.balance);
                $('.account_equity').text(data.equity);
            }
        });
    });
    
    // list plugin start
    var data_list = $("#data-list");
    var dataList = data_list.data_list({
        serverSide: true,
        url: '/user/dashboard',
        listPerPage: 2
    });
    // var ctx = document.getElementById("chart-bars").getContext("2d");
    // new Chart(ctx, {
    //     type: "bar",
    //     data: {
    //         labels: JSON.parse('<?php echo $monthly; ?>'),
    //         datasets: [{
    //             label: "Monthly Amount",
    //             tension: 0.4,
    //             borderWidth: 0,
    //             borderRadius: 4,
    //             borderSkipped: false,
    //             backgroundColor: "#fff",
    //             data: JSON.parse('<?php echo $monthly_balance; ?>'),
    //             maxBarThickness: 6
    //         }, ],
    //     },
    //     options: {
    //         responsive: true,
    //         maintainAspectRatio: false,
    //         plugins: {
    //             legend: {
    //                 display: false,
    //             }
    //         },
    //         interaction: {
    //             intersect: false,
    //             mode: 'index',
    //         },
    //         scales: {
    //             y: {
    //                 grid: {
    //                     drawBorder: false,
    //                     display: false,
    //                     drawOnChartArea: false,
    //                     drawTicks: false,
    //                 },
    //                 ticks: {
    //                     suggestedMin: 0,
    //                     suggestedMax: 500,
    //                     beginAtZero: true,
    //                     padding: 15,
    //                     font: {
    //                         size: 14,
    //                         family: "Open Sans",
    //                         style: 'normal',
    //                         lineHeight: 2
    //                     },
    //                     color: "#fff"
    //                 },
    //             },
    //             x: {
    //                 grid: {
    //                     drawBorder: false,
    //                     display: false,
    //                     drawOnChartArea: false,
    //                     drawTicks: false
    //                 },
    //                 ticks: {
    //                     display: false
    //                 },
    //             },
    //         },
    //     },
    // });


    // var ctx2 = document.getElementById("chart-line").getContext("2d");

    // var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

    // gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
    // gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    // gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

    // var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

    // gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
    // gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    // gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

    // new Chart(ctx2, {
    //     type: "line",
    //     data: {
    //         labels: JSON.parse('<?php echo $withdraw_months; ?>'),
    //         datasets: [{
    //                 label: "Withdraws",
    //                 tension: 0.4,
    //                 borderWidth: 0,
    //                 pointRadius: 0,
    //                 borderColor: "var(--custom-primary)",
    //                 borderWidth: 3,
    //                 backgroundColor: gradientStroke1,
    //                 fill: true,
    //                 data: JSON.parse('<?php echo $withdraw_amounts; ?>'),
    //                 maxBarThickness: 6

    //             },
    //             {
    //                 label: "Deposits",
    //                 tension: 0.4,
    //                 borderWidth: 0,
    //                 pointRadius: 0,
    //                 borderColor: "#3A416F",
    //                 borderWidth: 3,
    //                 backgroundColor: gradientStroke2,
    //                 fill: true,
    //                 data: JSON.parse('<?php echo $deposit_amounts; ?>'),
    //                 maxBarThickness: 6
    //             },
    //         ],
    //     },
    //     options: {
    //         responsive: true,
    //         maintainAspectRatio: false,
    //         plugins: {
    //             legend: {
    //                 display: false,
    //             }
    //         },
    //         interaction: {
    //             intersect: false,
    //             mode: 'index',
    //         },
    //         scales: {
    //             y: {
    //                 grid: {
    //                     drawBorder: false,
    //                     display: true,
    //                     drawOnChartArea: true,
    //                     drawTicks: false,
    //                     borderDash: [5, 5]
    //                 },
    //                 ticks: {
    //                     display: true,
    //                     padding: 10,
    //                     color: '#b2b9bf',
    //                     font: {
    //                         size: 11,
    //                         family: "Open Sans",
    //                         style: 'normal',
    //                         lineHeight: 2
    //                     },
    //                 }
    //             },
    //             x: {
    //                 grid: {
    //                     drawBorder: false,
    //                     display: false,
    //                     drawOnChartArea: false,
    //                     drawTicks: false,
    //                     borderDash: [5, 5]
    //                 },
    //                 ticks: {
    //                     display: true,
    //                     color: '#b2b9bf',
    //                     padding: 20,
    //                     font: {
    //                         size: 11,
    //                         family: "Open Sans",
    //                         style: 'normal',
    //                         lineHeight: 2
    //                     },
    //                 }
    //             },
    //         },
    //     },
    // });


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
    // get balance
    $(document).on("click", ".btn-load-ac-balance", function() {
        let $this = $(this);
        let account = $(this).data('id');
        $('.fa-redo-alt').removeClass('fa-spin');
        balance_equity($this, account, 'balance'); //finance js
    });







</script>
@stop