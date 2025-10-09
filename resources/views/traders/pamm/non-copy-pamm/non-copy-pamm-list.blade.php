@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Pamm Profile')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<style>
    .user-flug {
        position: absolute;
        width: 21px !important;
        left: 26px;
        bottom: 2px;
    }

    .border-success {
        /* border-color: #145341 !important; */
        border-color: #01b245ab !important;
    }

    .text-success {
        color: #01b245 !important;
    }

    .btn-outline-success {
        color: #01b245 !important;
        border-color: #01b245 !important;
    }

    .position-status {
        font-size: 0.7rem;
    }

    .risk-icon-success {
        width: 15px;
    }

    .chart {
        width: 110% !important;
        margin-left: -17px !important;
    }

    .gain-section {
        font-size: 0.7rem;
    }

    .commission-container {
        font-size: 0.7rem;
    }

    .page-item .page-link,
    .page-item span {
        border-radius: 0 !important;
    }

    .dataTables_info {
        font-size: 0.95rem !important;
    }

    .dark-version a:not(.dropdown-item):not(.choices__item):not(.leaflet-control-zoom-in):not(.leaflet-control-zoom-out):not(.btn):not(.nav-link):not(.fixed-plugin-button):not(.opacity-5) {
        color: #ffffffc4 !important;
    }

    .dark-version .form-label {
        color: white !important;
    }

    #table-pamm-list_wrapper {
        position: relative !important;
    }

    div.dataTables_processing {
        top: 89% !important;
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

    .border-dark {
        border-color: #468ed394 !important;
    }
</style>
@stop
<!-- breadcrumb -->
@section('bread_crumb')
{!!App\Services\systems\BreadCrumbService::get_trader_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid py-4">
    <div class="card card-body">
        <form class="row" id="form-filter">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="whome-to-show-first" class="form-label">Whom to show first</label>
                    <select name="show_first" id="whome-to-show-first" class="form-control form-select">
                        <option value="">Choose an option</option>
                        <option value="popular">Most popular</option>
                        <option value="Gainer">Gainer</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="duration" class="form-label">Filter by duration</label>
                    <select name="duration" id="duration" class="form-control form-select">
                        <option value="">All time</option>
                        <option value="2weeks">2 weeks</option>
                        <option value="1month">1 month</option>
                        <option value="3months">3 months</option>
                        <option value="6months">6 months</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="minimum-investment" class="form-label">Minimum investment</label>
                    <input type="text" class="form-control form-inpuut" name="min_investment" placeholder="$ 25 or mmore">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="trader-info" class="form-label">Trader info.</label>
                    <input type="text" class="form-control form-input" name="trader_info" placeholder="Name / Email / phone">
                </div>
            </div>
        </form>
    </div>
    <div class="card card-body mt-2">
        <div class="row">
            <div class="col-md-3">
                ( <span id="total-pamm">4</span> ) PAMM Profiles
            </div>
            <div class="col-md-3 ms-auto">

            </div>
            <div class="col-md-3 ms-auto">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center justify-content-end text-end pe-0">
                        <labe class="form-label text-end">Display per-page</labe>
                    </div>
                    <div class="col-md-6">
                        <select name="length" id="length" class="form-control form-select">
                            <option value="4">4</option>
                            <option value="8" selected>8</option>
                            <option value="16">16</option>
                            <option value="32">32</option>
                            <option value="64">64</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-outline-secondary w-100" id="btn-reset">Reset all</button>
                    </div>
                    <div class="col-md-6 text-end pt-1">
                        <button class="btn btn-outline-primary w-100" id="btn-filter">Filter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end filter area -->
    <!-- start list -->
    <div class="row mt-3">
        <div class="col-md-3 d-none mb-3" id="pamm-list-col">
            <div class="card card-body card-container border border-success">
                <!-- start name palet -->
                <div class="d-flex" style="max-height:47px">
                    <div class="bg-gradient-dark border-radius-md p-2 position-relative rounded-circle avatar avatar-md">
                        <img src="{{asset('admin-assets/app-assets/images/avatars/avater-men.png')}}" alt="PAMM USER">
                        <img src="{{asset('trader-assets/assets/img/pamm/bd.svg')}}" class="user-flug" style="border-radius: unset !important;" />
                    </div>
                    <div class="ms-3 my-auto">
                        <h6 class="name-value">Demo user</h6>
                        <p class="position-status pb-0">
                            <svg class="text-primary" xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" class="bi bi-asterisk" viewBox="0 0 16 16">
                                <path d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z" />
                            </svg>
                            starter
                        </p>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <button class="btn btn-link text-secondary pe-2 ps-0" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon" style="font-size:13px;">
                                    <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end me-n3 me-sm-n4" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="javascript:;">Copy</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- start risk state -->
                <span class="d-flex align-items-center risk-status">
                    <span>
                        <img class="risk-icon risk-icon-success" src="{{asset('trader-assets/assets/img/pamm/logo/arro-circle-up.png')}}" alt="risk status">
                    </span>
                    <span style="font-size: 0.7rem;">
                        <span class="risk-number"> 1 </span>
                        Potential risk score
                    </span>
                </span>
                <!-- start chart -->
                <div class="chart-container">
                    <div class="chart">
                        <canvas id="line-chart-gradient" class="chart-canvas" height="200"></canvas>
                    </div>
                </div>
                <!-- start gain section -->
                <hr class="horizontal light mb-1">
                <div class="d-flex justify-content-between gain-container">
                    <div class="gain-section">
                        <span class="d-block">Gain</span>
                        <span class="d-block text-success gain-value">0.125</span>
                    </div>
                    <div class="gain-section">
                        <span class="d-block">Profit/Loss</span>
                        <span class="d-block">
                            <span class="text-success total-copy">3</span>
                            <img src="{{asset('trader-assets/assets/img/pamm/up.png')}}" class="uncopy-icon" />
                            <img src="{{asset('trader-assets/assets/img/pamm/arrow-down.png')}}" class="copy-icon" />
                            <span class="text-danger total-uncopy">0</span>
                        </span>
                    </div>
                </div>
                <hr class="horizontal light mb-1">
                <div class="d-flex justify-content-between commission-container">
                    <div class="commission-section">
                        <span class="d-block">Commission</span>
                        <span class="d-block">
                            <span class="commission-value">25</span> %
                        </span>
                    </div>
                    <!-- with us section -->
                    <div class="with-us-section">
                        <span class="d-block">With us</span>
                        <span class="d-block">
                            <span class="with-us-value">10</span> Days
                        </span>
                    </div>
                </div>
                <!-- redirect overview -->
                <div class="d-flex justify-content-around button-section">
                    <a href="#" class="btn btn-outline-success w-60 statistics-link">Statistics</a>
                </div>
            </div>
        </div>

    </div>
    <div class="row" id="pamm-row">

    </div>
    <!-- empty table -->
    <div class="row" id="pamm-list-empty" style="display: none;">
        <div class="col-md-12">
            <div class="d-flex card card-body justify-content-around text-center text-muted height-300">
                There is no PAMM profile available now. You may register as PAMM
            </div>
        </div>
    </div>
    <table id="table-pamm-list" style="visibility:hidden"></table>
</div>
@stop
@section('page-js')
<script type="text/javascript" src="{{ asset('trader-assets/assets/js/datatables.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $.fn.serializeObject = function() {
            var o = {};
            var a = this.serializeArray();
            $.each(a, function() {
                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };
        // <---------------------start chart----------------->

        function make_chart(data, chart_id) {
            var ctx2 = document.getElementById(chart_id).getContext("2d");
            // Check if a chart with the given ID exists and destroy it
            var existingChart = Chart.getChart(ctx2);
            if (existingChart) {
                existingChart.destroy();
            }
            var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

            gradientStroke1.addColorStop(1, 'rgba(0, 208, 148, 0.2)'); // #00d094 with 0.2 opacity
            gradientStroke1.addColorStop(0.2, 'rgba(72, 72, 176, 0.0)'); // transparent with 0.0 opacity
            gradientStroke1.addColorStop(0, 'rgba(0, 208, 148, 0)'); // fully transparent #00d094

            // Your gradientStroke1 is now created with a linear gradient starting from #00d094


            var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

            gradientStroke2.addColorStop(1, 'rgba(68,140,210,0.1)');
            gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
            gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors
            // const index_of_data = [data.growth[0], data.growth[data.growth.length - 1]];

            new Chart(ctx2, {
                type: "line",
                data: {
                    // labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    labels: data.months,
                    // labels: index_of_data,
                    datasets: [{
                            label: "Growth",
                            tension: 0,
                            borderWidth: 0,
                            pointRadius: 0,
                            borderColor: "#00d094",
                            borderWidth: 3,
                            backgroundColor: gradientStroke1,
                            fill: true,
                            data: data.growth,
                            yAxisID: 'yGrowth',
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
                            data: data.equity,
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
                        yGrowth: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: false,
                                padding: 0,
                                color: '#b2b9bf',
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                            },
                            // min: Math.min(...data.equity) - 1, // Slightly below the minimum equity
                            // max: Math.max(...data.equity) + 1
                        },
                        yEquity: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: false,
                                padding: 0.5,
                                color: '#b2b9bf',
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                            },
                            // min: Math.min(...data.equity) - (1),
                            max: Math.max(...data.equity) - 0.1
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
                                display: false,
                                color: '#b2b9bf',
                                padding: 0,
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
        }
        // <-------------end char functions---------------->
        // <-------------start datatable for pamm list--------------->
        const pamm_list = $("#table-pamm-list").DataTable({
            "processing": true,
            "searching": false,
            "serverSide": true,
            "ordering": false,
            "lengthChange": false,
            "pageLength": 8,
            "ajax": {
                "url": "/user/pamm/non-copy-pamm-list/data",
                "data": function(d) {
                    return $.extend({}, d, $("#form-filter").serializeObject());
                }
            },
            columns: [{
                    data: "name",
                },
                {
                    data: "flag"
                },
                {
                    data: "gain"
                },
                {
                    data: "follower"
                },
                {
                    data: "unfollow"
                },
                {
                    data: "commission"
                },
                {
                    data: "with_us"
                },
                {
                    data: "overview_url"
                }
            ],
            "drawCallback": function(data) {
                // set total records
                var info = this.api().page.info();
                $("#total-pamm").text(info.recordsTotal);

                if (info.recordsTotal < 1) {
                    // console.log(info.recordsTotal);
                    $("#pamm-list-empty").slideDown();
                } else {
                    $("#pamm-list-empty").slideUp();
                }
                // This function will be called every time the table is drawn or redrawn

                $("#table-pamm-list").empty();
                $("#pamm-row").empty();
                var table_row = this.fnGetData();

                $.each(table_row, function(index, value) {
                    const html_coll = $("#pamm-list-col").clone();
                    html_coll.removeAttr('id').removeClass('d-none');
                    var line_chart_id = `line-chart-gradient${value?.id}`;
                    // change id of charts
                    html_coll.find("#line-chart-gradient").attr('id', line_chart_id);
                    // set data to each columns
                    html_coll.find('.name-value').text(value?.name);
                    html_coll.find('.gain-value').text(value?.gain + " %");
                    html_coll.find('.risk-icon').attr('src', value?.risk_icon);
                    html_coll.find('.total-copy').text(value?.follower);
                    html_coll.find('.total-uncopy').text(value.unfollow);
                    html_coll.find('.commission-value').text(value?.commission);
                    html_coll.find('.with-us-value').text(value.with_us);
                    html_coll.find('.user-flug').attr('src', value.flag);
                    html_coll.find('.statistics-link').attr('href', value.overview_url);
                    // change colors
                    if (value.follower > 0) {
                        html_coll.find('.card-container').addClass('border-success').removeClass('border-dark');
                        html_coll.find('.statistics-link').addClass('btn-outline-success').removeClass('btn-outline-dark');
                    } else {
                        html_coll.find('.card-container').addClass('border-dark').removeClass('border-success');
                        html_coll.find('.statistics-link').addClass('btn-outline-dark').removeClass('btn-outline-success');
                    }
                    if (value.gain > 0) {
                        html_coll.find('.gain-value').addClass('text-success').removeClass('text-danger');
                    } else {
                        html_coll.find('.gain-value').removeClass('text-success').addClass('text-danger');
                    }
                    // appending the columns
                    $("#pamm-row").append(html_coll);
                    // rendering chart 
                    if (value?.months?.length > 1)
                        make_chart({
                            months: value.months,
                            growth: value.growth,
                            equity: value.equity
                        }, line_chart_id);
                });
            },
            "language": {
                paginate: {
                    previous: "&laquo;",
                    next: "&raquo;",
                },
            },

        });
        // Handle the change event of the custom select field
        $(document).on('change', "#length", function() {
            var selectedValue = $(this).val();
            // Update the DataTable with the new page length
            pamm_list.page.len(selectedValue).draw();
        });
        // filter datatable
        $(document).on('click', "#btn-filter", function() {
            pamm_list.draw();
        });
        $(document).on('click', '#btn-reset', function() {
            $("#form-filter").trigger('reset');
            pamm_list.draw();
        })
    });
</script>

@stop