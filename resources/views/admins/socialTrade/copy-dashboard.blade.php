@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Copy Dashboard')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/calendars/fullcalendar.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/pages/app-calendar.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/pie/css/chartist.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/morris.css') }}">
<style>
    td,
    th {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .bank-identify-modal {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 60% !important;
        pointer-events: auto;
        background-color: #fff;
        background-clip: padding-box;
        border: 0 solid rgba(34, 41, 47, 0.2);
        border-radius: 0.357rem;
        outline: 0;
    }
    


    @media screen and (max-width: 1440px) and (min-width: 900px) {

        .deposit-request thead tr th:nth-child(3),
        .deposit-request tbody tr td:nth-child(3) {
            display: none;
        }

        .small-none {
            display: none;
        }
    }

    .dataTables_scrollBody {
        height: auto !important;
    }

    td.details-control {
        background: url("{{ asset('admin-assets/assets/icon/plus.png') }}") no-repeat center center;
        cursor: pointer;
    }

    tr.details td.details-control {
        background: url("{{ asset('admin-assets/assets/icon/minus.png') }}") no-repeat center center;
    }

    table.table.table-bordered {
        margin-top: 40px !important;
    }

    .multiselect-dropdown-list-wrapper {
        background: transparent !important;
    }

    .multiselect-dropdown-list {
        background: #fff
    }

    .multiselect-dropdown-list div:hover {
        background-color: var(--custom-primary) !important;
    }

    .multiselect-dropdown {
        border: 1px solid #ddd !important;
    }

    div#example_info {
        display: none !important;
    }

    a.canvasjs-chart-credit {
        display: none;
    }

    .pagination {
        display: none;
    }
    
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
    <div class="content-wrapper container-fluid p-0">
        <div id="orver_loading" class="lds-ripple loading" style="display: none;">
            <div></div>
            <div></div>
        </div>
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Copy Dashboard</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('ib-management.Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Social Trade</a>
                                </li>
                                <li class="breadcrumb-item active">Copy Dashboard
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
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Ajax Sourced Server-side -->
            <section id="ajax-datatable">
                <div class="row master-chart" style="display: none">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="mt-lg">Master Info</h3>
                                <p class="mb-lg">Master Yearly Trade Chart</p>

                                <div class="chart chart-md" id="morrisBar"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--trading account info show part-->
                <div class="row">
                    <div class="col-lg-6 col-6">
                        <div class="card">
                            <a href="{{ route('admin.pamm.copy-trade-report') }}">
                                <div class="card-body border-start-3 border-start-primary">
                                    <div class="d-flex">
                                        <div class="section-icon">
                                            <i data-feather='layers' class="icon-trd text-primary"></i>
                                        </div>
                                        <div class="section-data ms-1">
                                            <div class="tv-title text-secondary">
                                                Total Trades
                                            </div>
                                            <div class="tv-total amount counter ct_total_volume" id="balance">
                                                <span class=" ct_total_trades text-secondary">{{ isset($ttc->data[0]->total_trades) ? $ttc->data[0]->total_trades:0   }}</span>
                                                &#40;<small class="total-closed counter ct_total_closed text-secondary">{{ isset($ttcc->data[0]->total_trades) ? $ttcc->data[0]->total_trades:0 }}</small>
                                                Copied&#41;

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-6">
                        <div class="card">
                            <div class="card-body border-start-3 border-start-primary">
                                <div class="d-flex">
                                    <div class="section-icon">
                                        <i data-feather='dollar-sign' class="icon-trd text-primary"></i>
                                    </div>
                                    <div class="section-data ms-1">
                                        <div class="tv-title text-secondary">
                                            Total Profit
                                        </div>
                                        <div class="tv-total amount counter ct_total_volume text-secondary" id="total_profit">
                                            {{ isset($tpc->data[0]->total_profit)? $tpc->data[0]->total_profit:0 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-6">
                        <div class="card">
                            <a href="{{ route('admin.manager.pamm') }}">
                                <div class="card-body border-start-3 border-start-primary">
                                    <div class="d-flex">
                                        <div class="section-icon">
                                            <i data-feather='user' class="icon-trd text-primary"></i>
                                        </div>
                                        <div class="section-data ms-1">
                                            <div class="tv-title text-secondary">
                                                Total Master
                                            </div>
                                            <div class="tv-total amount counter ct_total_volume text-secondary" id="total_trade">
                                                {{ isset($tmc->data[0]->total_masters) ? $tmc->data[0]->total_masters:0 }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-6">
                        <div class="card">
                            <a href="{{ route('admin.social-report') }}">
                                <div class="card-body border-start-3 border-start-primary">
                                    <div class="d-flex">
                                        <div class="section-icon">
                                            <i data-feather='users' class="icon-trd text-primary"></i>
                                        </div>
                                        <div class="section-data ms-1">
                                            <div class="tv-title text-secondary">
                                                Total Slaves
                                            </div>
                                            <div class="tv-total amount counter text-secondary" id="total_slave">
                                                {{ isset($tsc->data[0]->total_slaves)?$tsc->data[0]->total_slaves:0 }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <!--trading account info show part end-->

                <!--<div class="row master-chart">-->
                <!--    <div class="col-lg-6 col-md-12 col-sm-12">-->
                <!--        <div class="card">-->
                <!--            <div class="card-body">-->
                <!--                <h3 class="mt-lg">Trades</h3>-->
                <!--                <p class="mb-lg">Pie Chart Based On Others Trades, Masters Trades and Slaves Trades-->
                <!--                </p>-->

                <!--                <div class="chart chart-md" id="flotBasic" style="height: 370px; width: 100%;"></div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->



                <!--    <div class="col-lg-6 col-md-12 col-sm-12">-->
                <!--        <div class="card">-->
                <!--            <div class="card-body">-->
                <!--                <h3 class="mt-lg">Trades</h3>-->
                <!--                <p class="mb-lg">Pie Chart Based On Masters Trades and Slaves Trades</p>-->
                <!--                <div id="flot-placeholder" style="height: 370px; width: 100%;">-->
                <!--                    <div id="flot-memo" style="text-align:center;height:30px;width:250px;height:20px;text-align:center;margin:0 auto">-->
                <!--                    </div>-->
                <!--                </div>-->

                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                <div class="row ">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="mt-lg">Trades</h3>
                                <p class="mb-lg">Pie Chart Based On Others Trades, Masters Trades and Slaves Trades
                                </p>
                                <div class="chart chart-md" style="height: 370px; width: 100%;">
                                    <canvas id="lineChart" class="chartjs" data-height="500" style="width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="mt-lg">Trades</h3>
                                <p class="mb-lg">Pie Chart Based On Masters Trades and Slaves Trades</p>
                                <div style="position: relative; height: 370px; display: flex; justify-content: center; align-items: center;">
                                    <canvas id="doughnutChart" class="chartjs" width="300" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body mt-2">
                                <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                    <table id="example" class="datatables-ajax deposit-request table table-responsive">
                                        <thead class="thead-light cell-border compact stripe">
                                            <tr>
                                                <th>Ticket</th>
                                                <th>Login</th>
                                                <th>Type</th>
                                                <th>Symbol</th>
                                                <th>Volume</th>
                                                <th>Open Time</th>
                                                <th>Close Time</th>
                                                <th>Comment</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Ajax Sourced Server-side -->
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

<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>

<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
<!--<script src="{{ asset('admin-assets/app-assets/vendors/js/charts/chart.min.js') }}"></script>-->
<script src="{{ asset('lite-asset/assets/js/plugins/chartjs.min.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')

<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/filter.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/common.js') }}"></script>

<!--barchart & pie js-->
<script src="{{ asset('admin-assets/assets/pie/jquery.flot.js') }}"></script>
<script src="{{ asset('admin-assets/assets/pie/jquery.flot.pie.js') }}"></script>
<!--<script src="{{ asset('admin-assets/assets/pie/chartist.js') }}"></script>-->
<script src="{{ asset('admin-assets/assets/js/morris/morris.js') }}"></script>
<script src="{{ asset('admin-assets/assets/js/morris/raphael.js') }}"></script>

<!-- datatable  -->
<script>
    var dt = $('#example').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "ordering": true,
        "ajax": "/admin/pamm/copy-dashboard-process",
        "columns": [{
                "data": "ticket"
            },
            {
                "data": "login"
            },

            {
                "data": "type"
            },
            {
                "data": "symbol"
            },
            {
                "data": "volume"
            },
            {
                "data": "open_time"
            },
            {
                "data": "close_time"
            },
            {
                "data": "comment"
            }
        ],
    });

var chart_data_ot = <?php echo $chart_data_ot; ?>;
var chart_data_st = <?php echo $chart_data_st; ?>;
var chart_data_mt = <?php echo $chart_data_mt; ?>;
// console.log(chart_data_mt);

let labels_ot = chart_data_ot.map(item => item[0]);
if(!labels_ot || labels_ot.length === 0){
    labels_ot = ['Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'August', 'Oct', 'Nov'];;
}
let chart_data_ot_data = chart_data_ot.map(item => item[1]);
if (!chart_data_ot_data || chart_data_ot_data.length === 0) {
    chart_data_ot_data = [10, 15, 17,59, 68, 76, 82, 90, 103, 110, 126, 131];
}
let chart_data_st_data = chart_data_st.map(item => item[1]);
if (!chart_data_st_data || chart_data_st_data.length === 0){
    chart_data_st_data = [0,20,30,50, 68, 70, 88, 90, 108, 110, 128, 139];
}
let chart_data_mt_data = chart_data_mt.map(item => item[1]);
if (!chart_data_mt_data || chart_data_mt_data.length === 0){
    chart_data_mt_data = [5,10,35,55, 60, 45, 85, 90, 100, 130, 125, 150];
}
   const config = {
        colors: {
            danger: 'blue',
            primary: '#F73E4A',
        },
    };
    const yellowColor = '#28C76F';
    const labelColor = '#EA5455';
    const headingColor = '#008000';
    const legendColor = '#9ACD32';
    let borderColor, gridColor, tickColor;
    borderColor = 'rgba(169, 169, 169, 1)';
    gridColor = 'rgba(169, 169, 169, 1)';
    tickColor = 'rgba(169, 169, 169, 0.5)'; // x & y axis tick color
    
    const lineChart = document.getElementById('lineChart');
    // console.log(lineChart);
    if (lineChart) {
        const lineChartVar = new Chart(lineChart, {
            type: 'line',
            data: {
                labels: labels_ot,
                datasets: [{
                        data: chart_data_ot_data,
                        label: "Other Trades Volume",
                        borderColor: config.colors.danger,
                        tension: 0.5,
                        pointStyle: 'circle',
                        backgroundColor: config.colors.danger,
                        fill: false,
                        pointRadius: 1,
                        pointHoverRadius: 5,
                        pointHoverBorderWidth: 5,
                        pointBorderColor: 'transparent',
                        pointHoverBorderColor: 'rgba(255,255,255,0.2)',
                        pointHoverBackgroundColor: config.colors.danger
                    },
                    {
                        data: chart_data_st_data,
                        label: "Copied Trades Volume",
                        borderColor: config.colors.primary,
                        tension: 0.5,
                        pointStyle: 'circle',
                        backgroundColor: config.colors.primary,
                        fill: false,
                        pointRadius: 1,
                        pointHoverRadius: 5,
                        pointHoverBorderWidth: 5,
                        pointBorderColor: 'transparent',
                        pointHoverBorderColor: 'rgba(255,255,255,0.2)',
                        pointHoverBackgroundColor: config.colors.primary
                    },
                    {
                        data: chart_data_mt_data,
                        label: "Master Trades Volume",
                        borderColor: yellowColor,
                        tension: 0.5,
                        pointStyle: 'circle',
                        backgroundColor: yellowColor,
                        fill: false,
                        pointRadius: 1,
                        pointHoverRadius: 5,
                        pointHoverBorderWidth: 5,
                        pointBorderColor: 'transparent',
                        pointHoverBorderColor: 'rgba(255,255,255,0.2)',
                        pointHoverBackgroundColor: yellowColor
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: {
                            color: borderColor,
                            drawBorder: false,
                            borderColor: borderColor
                        },
                        ticks: {
                            color: labelColor
                        }
                    },
                    y: {
                        scaleLabel: {
                            display: true
                        },
                        min: 0,
                        max: 400,
                        ticks: {
                            color: labelColor,
                            stepSize: 100
                        },
                        grid: {
                            color: borderColor,
                            drawBorder: false,
                            borderColor: borderColor
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        // Updated default tooltip UI
                        backgroundColor: 'rgba(255,255,255,0.2)',
                        titleColor: headingColor,
                        bodyColor: legendColor,
                        borderWidth: 1,
                        borderColor: borderColor
                    },
                    legend: {
                        position: 'top',
                        align: 'start',
                        labels: {
                            usePointStyle: true,
                            padding: 35,
                            boxWidth: 6,
                            boxHeight: 6,
                            color: legendColor
                        }
                    }
                }
            }
        });
    }
    var pie_mt = <?php echo $pie_mt; ?>;
    var pie_st = <?php echo $pie_st; ?>;
    // console.log(pie_mt);
    if (!pie_mt || pie_mt.length === 0) {
        pie_mt = [80]; 
    }
    if (!pie_st || pie_st.length === 0) {
        pie_st = [20]; 
    }
    // Color Variables
    const cyanColor = '#28C76F', orangeLightColor = '#EA5455', cardColor = '#FFD233';
    
    const doughnutChart = document.getElementById('doughnutChart');
    if (doughnutChart) {
        const doughnutChartVar = new Chart(doughnutChart, {
            type: 'doughnut',
            data: {
                labels: ['Master Trades', 'Copy Trades'],
                datasets: [{
                    data: [pie_mt , pie_st],
                    // data: [10 , 20],
                    backgroundColor: [cyanColor, orangeLightColor],
                    borderWidth: 0,
                    pointStyle: 'rectRounded'
                }]
            },
            options: {
                responsive: false,
                width: 300,
                height: 300,
                animation: {
                    duration: 500
                },
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                return label + ': ' + value + ' %';
                            }
                        },
                        // Updated default tooltip UI
                        backgroundColor: cardColor,
                        titleColor: headingColor,
                        bodyColor: legendColor,
                        borderWidth: 1,
                        borderColor: borderColor
                    }
                }
            }
        });
    }
    //bar chart code here start

    // var flotBasicData = [{
    //     data: "{{ $chart_data_ot }}",
    //     label: "Other Trades Volume",
    //     color: "#0088cc",
    //     opacity: 0.5,
    // }, {
    //     data: "{{ $chart_data_st }}",
    //     label: "Copied Trades Volume",
    //     color: "#2baab1",
    //     opacity: 0.5,
    // }, {
    //     data: "{{ $chart_data_mt }}",
    //     label: "Master Trades Volume",
    //     color: "#734ba9",
    //     opacity: 0.5,
    // }];

    // var plot = $.plot('#flotBasic', flotBasicData, {
    //     series: {
    //         lines: {
    //             show: true,
    //             fill: true,
    //             lineWidth: 1,
    //             fillColor: {
    //                 colors: [{

    //                     opacity: 0.45
    //                 }, {
    //                     opacity: 0.45
    //                 }]
    //             }
    //         },
    //         points: {
    //             show: true
    //         },
    //         shadowSize: 0
    //     },
    //     grid: {
    //         hoverable: true,
    //         clickable: true,
    //         borderColor: '',
    //         borderWidth: 1,
    //         labelMargin: 15,
    //         backgroundColor: 'transparent'
    //     },
    //     yaxis: {
    //         min: 0,
    //         max: "{{ $max_val }}",
    //         color: 'rgba(255,255,255,0.2)',
    //         font: {
    //             color: "rgba(255,255,255,0.5)"
    //         },
    //     },
    //     xaxis: {
    //         font: {
    //             color: "rgba(255,255,255,0.5)"
    //         },
    //         color: 'rgba(255,255,255,0.2)'
    //     },
    //     tooltip: true,
    //     tooltipOpts: {
    //         content: '%s: Value of %x is %y',
    //         shifts: {
    //             x: -60,
    //             y: 25
    //         },
    //         defaultTheme: false
    //     }
    // });

    //bar chart code here end

    //pie chart code here start

    var flotPieData = [{
            label: "Master Trades",
            data: "{{ $pie_mt }}",
            color: "#734ba9"
        },
        {
            label: "Copy Trades",
            data: "{{ $pie_st }}",
            color: "#2baab1"
        },
    ];

    var plot = $.plot('#flot-placeholder', flotPieData, {
        series: {
            pie: {
                show: true,
                label: {
                    show: true,
                    radius: 0.8,
                    formatter: function(label, series) {
                        return '<div style="border:1px solid grey;font-size:8pt;text-align:center;padding:5px;color:black;">' +
                            label + ' : ' +
                            Math.round(series.percent) +
                            '%</div>';
                    },
                    background: {
                        opacity: 0.8,
                        color: '#fff'
                    },

                }
            }
        },
        grid: {
            hoverable: true,
            backgroundColor: 'transparent'
        }
    });


    $.fn.showMemo = function() {
        $(this).bind("plothover", function(event, pos, item) {
            if (!item) {
                return;
            }

            var html = [];
            var percent = parseFloat(item.series.percent).toFixed(2);

            html.push("<div style=\"border:1px solid grey;background-color:",
                item.series.color,
                "\">",
                "<span style=\"color:white\">",
                item.series.label,
                " : ",
                // $.formatNumber(item.series.data[0][1], {
                //     format: "#,###",
                //     locale: "us"
                // }),
                " (", percent, "%)",
                "</span>",
                "</div>");
            $("#flot-memo").html(html.join(''));
        });
    }


    $("#flot-placeholder").showMemo();
    //pie chart code here end
</script>

@stop