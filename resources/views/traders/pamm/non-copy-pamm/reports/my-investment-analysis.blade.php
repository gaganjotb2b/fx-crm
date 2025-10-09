@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'My Investment analysis')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
    #deposit_report_datatable tr,
    #deposit_report_datatable td:first-child {
        border-left: 3px solid #4fd1c5;
    }

    #deposit_report_datatable tr,
    #deposit_report_datatable th:first-child {
        border-left: 3px solid;
    }

    #deposit_report_datatable tr,
    #deposit_report_datatable td {
        background-color: #f7fafc;
        vertical-align: middle;
        text-align: left;
    }

    #deposit_report_datatable {
        border-collapse: separate !important;
        border-spacing: 2px 8px;
    }

    .dataTables_length .form-select {
        background-position: right 3px center;
        background-size: 12px 12px;
        padding-right: 1.25rem;
        margin-top: 3px;
    }

    #datatable-search_filter .form-control {
        margin: 3px 3px 0;
    }

    input:focus {
        outline: none !important;
        border: 1px solid #d8d6de;
    }

    .form-select.form-select-sm {
        display: block;
        width: 100%;
        padding-right: 0.5rem 2rem 0.5rem 0.75rem !important;
        -moz-padding-start: calc(0.75rem - 3px);
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1.4rem;
        color: #495057;
        background-color: #fff;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        border: 1px solid #d2d6da;
        border-radius: 0.5rem;
        transition: box-shadow 0.15s ease, border-color 0.15s ease;
        appearance: none;
    }

    .ps__rail-x {
        display: none !important;
    }

    .input-rang-group-text {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.45;
        color: #6e6b7b;
        text-align: center;
        white-space: nowrap;
        background-color: #fff;
        border: 1px solid #d8d6de;
    }

    .min {
        padding: 0 !important;
        margin: 0 !important;
        border-top: 1px solid #d8d6de;
        border-right: none;
        border-bottom: 1px solid #d8d6de;
        border-left: 1px solid #d8d6de;
        text-align: center;
    }

    .dark-version .col-1.input-rang-group-date-logo.rang-max.input-range-gpr-right {
        display: flex;
        align-items: center;
        padding: 0.6rem 0.6rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.45;
        color: #6e6b7b;
        text-align: center;
        white-space: nowrap;
        background-color: #151a2c;
        border-top-left-radius: 0rem !important;
        border-bottom-left-radius: 0rem !important;
        border-right: none !important;
        border: 1px solid #2d3357 !important;
    }

    .input-rang-group-date-logo {
        border: 1px solid #d8d6de !important;
    }
</style>
@if (App\Services\systems\VersionControllService::check_version() === 'lite')
<style>
    .dt-buttons .buttons-csv,
    .dt-buttons .buttons-excel,
    .dt-buttons .buttons-copy {
        display: none;
    }
</style>
@endif
@stop
@section('bread_crumb')
<!-- bread crumb -->
{!! App\Services\systems\BreadCrumbService::get_trader_breadcrumb() !!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid py-4">
    <div class="custom-height-con">
        <div class="card">
            <div class="card-body">
                <form id="filter-form" class="dt_adv_search" method="POST">
                    @csrf
                    <div class="row gy-2 mb-md-1 my-3">
                        <div class="col-lg-4 col-md-6 p-1">
                            <select class="form-select choice-colors" name="pamm_account" id="pamm_account">
                                @foreach ($pamm_accounts as $value)
                                <option value="{{$value->pammProfile?->account}}">PAMM (#{{$value->pammProfile?->account}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 p-1">
                            <div class="col-md-12 text-right">
                                <button id="btn-reset" type="button" class="btn btn-dark w-100"
                                    style="float: right;">
                                    <span class="align-middle">{{ __('category.RESET') }}</span>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 p-1">
                            <div class="col-md-12 text-right">
                                <button id="btn-filter" type="button" class="btn bg-gradient-primary  w-100">
                                    <span class="align-middle">{{ __('category.FILTER') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                                Total Investment
                                            </p>
                                            <h5 class="font-weight-bolder mb-0">
                                                $ <span id="total_investment">0.00</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- remaining investment -->
                    <div class="col-md-6">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                                Remaining Investment
                                            </p>
                                            <h5 class="font-weight-bolder mb-0">
                                                $ <span id="remaining_investment">0.00</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- total trade profit -->
                    <div class="col-md-6">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                                Total Profit
                                            </p>
                                            <h5 class="font-weight-bolder mb-0">
                                                $ <span id="total_profit">0.00</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- total trade loss -->
                    <div class="col-md-6">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                                Total Loss
                                            </p>
                                            <h5 class="font-weight-bolder mb-0">
                                                $ <span id="total_loss">0.00</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                                Todays Profit
                                            </p>
                                            <h5 class="font-weight-bolder mb-0">
                                                $ <span id="todays_profit">0.00</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- profit share -->
                    <div class="col-md-6">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                                Profit Share
                                            </p>
                                            <h5 class="font-weight-bolder mb-0">
                                                $ <span id="profit_share">0.00</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="chart">
                                    <canvas id="doughnut-chart-investment" class="chart-canvas" height="550"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
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
<script src="{{ asset('trader-assets/assets/js/plugins/datatables.js') }}"></script>
<script type="text/javascript" src="{{ asset('trader-assets/assets/js/datatables.min.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/plugins/flatpickr.min.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>

<!-- Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<!-- Excel Export JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- PDF Export JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<!-- Buttons HTML5 Export JS -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<!-- Buttons Print JS -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script>
    $(document).ready(function() {
        function update_hourly_doughnut(data) {
            var daily_doughnut = document.getElementById("doughnut-chart-investment").getContext("2d");
            var chart_daily_doughnut = new Chart(daily_doughnut, {
                type: "doughnut",
                data: {
                    labels: data.labels,
                    datasets: [{
                        weight: 9,
                        cutout: 80,
                        tension: 0.9,
                        pointRadius: 2,
                        borderWidth: 2,
                        borderColor: 'transparent',
                        backgroundColor: ['#28c8c1', '#FBD38D'],
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

        // filter analysis
        $(document).on("click", "#btn-filter", function() {
            get_data($("#pamm_account").val());
        });
        $(document).on("click", "#btn-reset", function() {
            $("#filter-form").trigger('reset');
            sleep(100);
            get_data($("#pamm_account").val());
        });
        get_data($("#pamm_account").val());
        // ajax request for get data
        function get_data(pammAccount) {
            $.ajax({
                url: '/user/investor-report/my-invested-pamm/data',
                data: {
                    account: pammAccount,
                },
                dataType: 'JSON',
                success: function(response) {
                    $("#total_investment").text(response?.data?.total_investment || 0.00);
                    $("#remaining_investment").text(response?.data?.remaining_investment || 0.00);
                    $("#total_profit").text(response?.data?.total_profit || 0.00);
                    $("#total_loss").text(response?.data?.total_loss || 0.00);
                    $("#todays_profit").text(response?.data?.todays_profit || 0.00);
                    $("#profit_share").text(response?.data?.profit_share || 0.00);

                    update_hourly_doughnut({
                        labels: ['Investment', 'Remaining Investment'],
                        chartData: [response?.data?.total_investment || 0.00, response?.data?.remaining_investment || 0.00]
                    });
                },
            });
        }
    });
</script>
@endsection