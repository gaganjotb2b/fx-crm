@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'IB Commission Report')
@section('vendor-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/calendars/fullcalendar.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <!-- datatable -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/pages/app-calendar.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
    <style>
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

        strong.counter.total_commission {
            font-size: large;
        }

        strong.counter.volume {
            font-size: large;
        }


        /* for Laptop */
        /* @media screen and (max-width: 1280px) and (min-width: 800px) {

            .ib-withdraw thead tr th:nth-child(10),
            .ib-withdraw tbody tr td:nth-child(10) {
                display: none;
            }

        }



        @media screen and (max-width: 1280px) and (min-width: 800px) {

            .ib-withdraw thead tr th:nth-child(8),
            .ib-withdraw tbody tr td:nth-child(8) {
                display: none;
            }

        }




        @media screen and (max-width: 1440px) and (min-width: 900px) {

            .ib-withdraw thead tr th:nth-child(7),
            .ib-withdraw tbody tr td:nth-child(7) {
                display: none;
            }

            .small-none {
                display: none;
            }
        }

        @media screen and (max-width: 1440px) and (min-width: 900px) {

            .ib-withdraw thead tr th:nth-child(6),
            .ib-withdraw tbody tr td:nth-child(6) {
                display: none;
            }

            .small-none {
                display: none;
            }
        } */

        td,
        th {
            overflow: hidden;
            text-overflow: ellipsis;
            /*white-space: nowrap;*/
        }

        .section-icon {
            margin-right: 1rem;
        }

        table.dataTable.table-responsive:not(.datatables-ajax) {
            display: table;
        }

        #ib_commission_table th:first-child {
            border-left: 3px solid orange;
        }

        #ib_commission_table td:first-child {
            border-left: 3px solid var(--custom-primary);
        }

        .input-group-text {
            display: flex;
            align-items: center;
            padding: 0rem 1rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.45;
            color: #6e6b7b;
            text-align: center;
            white-space: nowrap;
            background-color: #fff;
            border: 1px solid #d8d6de;
            border-radius: 0.357rem;
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
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">{{ __('admin-menue-left.ib_commission') }}
                            </h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('admin.dashboard') }}">{{ __('ib-management.Home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">{{ __('admin-breadcrumbs.reports') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ __('admin-menue-left.ib_commission') }}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1 breadcrumb-right">
                        <div class="dropdown">
                            <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    data-feather="grid"></i></button>
                            <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#"><i
                                        class="me-1" data-feather="info"></i>
                                    <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i
                                        class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a>
                            </div>
                            <button class="btn-icon btn btn-primary btn-round btn-sm" type="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="advance-filter-btn">Advance Filetr</button>
                        </div>
                    </div>
                </div>            </div>
            <div class="content-body">
                <!-- Ajax Sourced Server-side -->
                <section id="ajax-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card d-none" id="filter-form">
                                <div class="card-header border-bottom d-flex justfy-content-between">
                                    <h4 class="card-title">{{ __('ad-reports.filter_report') }}</h4>
                                    <div class="btn-exports" style="width:200px">
                                        <!-- input exports -->
                                        <select data-placeholder="Select a state..." class="select2-icons form-select"
                                            id="fx-export">
                                            <option value="download" data-icon="download" selected>
                                                {{ __('ib-management.export') }}</option>
                                            <option value="csv" data-icon="file">CSV</option>
                                            <option value="excel" data-icon="file">Excel</option>
                                        </select>
                                    </div>
                                </div>
                                <!--Search Form -->
                                <div class="card-body mt-2">
                                    <form id="filter-form" class="dt_adv_search" method="POST">
                                        <div class="row g-1 mb-md-1">
                                            <div class="col-md-4 mb-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Search By Open Date/Close Date">
                                                <!-- input filter open/close -->
                                                <label for="open_close" class="form-label">Open time | Close time</label>
                                                <select class="select2 form-select" name="open_close" id="open_close">
                                                    <optgroup label="Search By Method">
                                                        <option value="">{{ __('ad-reports.all') }}</option>
                                                        <option value="open_time" selected>Open Time</option>
                                                        <option value="close_time">Close Time</option>
                                                    </optgroup>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="" class="form-label">Date ( <span
                                                        id="time-lebel">Opent time</span> )</label>
                                                <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Search By Request Date">
                                                    <span class="input-group-text">
                                                        <div class="icon-wrapper">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="50"
                                                                height="50" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-calendar">
                                                                <rect x="3" y="4" width="18" height="18"
                                                                    rx="2" ry="2">
                                                                </rect>
                                                                <line x1="16" y1="2" x2="16"
                                                                    y2="6"></line>
                                                                <line x1="8" y1="2" x2="8"
                                                                    y2="6"></line>
                                                                <line x1="3" y1="10" x2="21"
                                                                    y2="10"></line>
                                                            </svg>
                                                        </div>
                                                    </span>
                                                    <input id="from" type="text" id="fp-default"
                                                        class="form-control flatpickr-basic" placeholder="YYYY-MM-DD"
                                                        name="from">
                                                    <span class="input-group-text">to</span>
                                                    <input id="to" type="text" id="fp-default"
                                                        class="form-control flatpickr-basic" placeholder="YYYY-MM-DD"
                                                        name="to">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Search By IB Level">
                                                <!-- input filter ib level -->
                                                <label for="ib_level" class="form-label">IB Level</label>
                                                <select class="select2 form-select" name="ib_level" id="ib_level">
                                                    <optgroup label="Search By IB Level">
                                                        <option value="">{{ __('ad-reports.all') }}</option>
                                                        <?php
                                                        $i = 1;
                                                        for ($i; $i <= $ib_level; $i++) {
                                                            echo '<option value="' . $i . '"> ' . 'Level' . ' ' . $i . '</option>';
                                                        }
                                                        ?>
                                                    </optgroup>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-1">
                                                <!-- filter by trader info -->
                                                <label for="trader-info" class="form-label">Trader Info.</label>
                                                <input type="text" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Trader name / email / phone / country"
                                                    class="form-control dt-input dt-full-name" data-column="1"
                                                    name="trader_info" id="trader_email"
                                                    placeholder="Trader name / email / phone / country"
                                                    data-column-index="0" />
                                            </div>
                                            <div class="col-md-4">
                                                <!-- filter by ib info -->
                                                <label for="ib-info" class="form-label">IB info.</label>
                                                <input type="text" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="IB name / email / phone / country"
                                                    class="form-control dt-input dt-full-name" data-column="1"
                                                    name="ib_info" id="ib_email"
                                                    placeholder="IB name / email / phone / country"
                                                    data-column-index="0" />
                                            </div>
                                            <div class="col-md-4 mb-1">
                                                <!-- filter by trading account -->
                                                <label for="trading-account" class="form-label">Trading account</label>
                                                <input type="text" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Trader Account" class="form-control dt-input dt-full-name"
                                                    data-column="1" name="trading_account" id="trading_account"
                                                    placeholder="Account number" data-column-index="0" />
                                            </div>
                                        </div>
                                        <div class="row g-1">
                                            <div class="col-md-4 mb-1">
                                                <!-- filter by manager info -->
                                                <label for="manager-info" class="form-label">Manager Info.</label>
                                                <input type="text" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Manager Name / Email"
                                                    class="form-control dt-input dt-full-name" data-column="1"
                                                    name="manager_info" id="manager_info"
                                                    placeholder="Manager Name / Email" data-column-index="0" />
                                            </div>
                                            <div class="col-md-4">
                                                <!-- filter by ticket or order number -->
                                                <label class="form-label" for="ticket-number">Order (Ticket)</label>
                                                <input type="text" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Order | Ticket Number"
                                                    class="form-control dt-input dt-full-name" data-column="1"
                                                    name="ticket" id="ticket" placeholder="Order | Ticket Number"
                                                    data-column-index="0" />
                                            </div>
                                            <div class="col-md-4  mb-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Search By Group">
                                                <!-- input filter ib group  -->
                                                <label for="ib-group" class="form-label">IB Group</label>
                                                <select class="select2 form-select" name="ib_group" id="type">
                                                    <optgroup label="Search By Group">
                                                        <option value="">{{ __('ad-reports.all') }}</option>
                                                        @foreach ($ib_group as $group)
                                                            <option value="{{ $group->id }}">{{ $group->group_name }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                            </div>
                                            <div class="col-md-2 text-right ms-auto">
                                                <button id="btn-reset" type="button"
                                                    class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                                    <span class="align-middle">{{ __('ad-reports.btn-reset') }}</span>
                                                </button>
                                            </div>
                                            <div class="col-md-2 text-right">
                                                <button id="btn-filter" type="button"
                                                    class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                                    <span class="align-middle">{{ __('category.FILTER') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <hr class="my-0" />
                            </div>
                        </div>
                    </div>

                    <!-- Collapsible and Refresh Actions -->
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-body border-start-3 border-start-primary">
                                    <div class="d-flex">
                                        <div class="section-icon">
                                            <i data-feather='bar-chart-2' class="icon-trd text-primary"></i>
                                        </div>
                                        <div>
                                            <h4>{{ __('page.total_commission') }}</h4>
                                            <div class="info">
                                                <strong class="counter total_commission">&dollar; <span
                                                        id="total_4">0</span></strong>
                                                <!-- <span class="text-primary">(<span class="counter total_trades" id="total_2">0 </span> Trades)</span> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-body border-start-3 border-start-primary">
                                    <div class="d-flex">
                                        <div class="section-icon">
                                            <i data-feather='layers' class="icon-trd text-primary"></i>
                                        </div>
                                        <div>
                                            <h4>{{ __('page.total_volume') }}</h4>
                                            <div class="info">
                                                <strong class="counter volume" id="total_3">0</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Collapsible and Refresh Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <!--Search Form -->
                                <div class="card-datatable m-1 table-responsive">
                                    <table id="ib_commission_table" class="table ib-withdraw">
                                        <thead>
                                            <tr>
                                                <th>{{ __('page.trader') }}</th>
                                                <th>{{ __('page.ib') }}</th>
                                                <th>{{ __('page.account') }}</th>
                                                <th>{{ __('page.ticket') }}</th>
                                                <th>{{ __('page.symbol') }}</th>
                                                <th>{{ __('page.open_time') }}</th>
                                                <th>{{ __('page.close_time') }}</th>
                                                <th>{{ __('page.level') }}</th>
                                                <th>{{ __('page.status') }}</th>
                                                <th>{{ __('page.volume') }}</th>
                                                <th>{{ __('page.amount') }}</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th colspan="9" style="text-align: right;" class="details-control"
                                                    rowspan="1">{{ __('page.total') }}</th>
                                                <th id="total_1" rowspan="1">0</th>
                                                <th rowspan="1"> &dollar; <span id="total_5">0</span></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <hr class="my-0" />
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Ajax Sourced Server-side -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')

    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
    <!-- datatable -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>


    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>


    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>

    <!-- datatable  -->
    <script>
        var dt = $('#ib_commission_table').fetch_data({
            url: '/admin/report/ib-commission/get-data',
            "columns": [{
                    "data": "trader_mail"
                },
                {
                    "data": "ib_mail"
                },
                {
                    "data": "trade_acc"
                },
                {
                    "data": "ticket"
                },
                {
                    "data": "symbol"
                },
                {
                    "data": "open_time"
                },
                {
                    "data": "close_time"
                },
                {
                    "data": "com_level"
                },
                {
                    "data": "status"
                },
                {
                    "data": "volume"
                },
                {
                    "data": "amount"
                },
            ],
            csv_export: true,
            total_sum: 5,
            multiple: 'single',
            export_col: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            length_change: true,
            customorder: 6
        })
        // change label for date ragne
        $(document).on('change', '#open_close', function() {
            if ($(this).val() === 'close_time') {
                $('#time-lebel').html('Close time');
            } else {
                $('#time-lebel').html('Open time');
            }
        })
        
        $(document).on("click", "#advance-filter-btn", function() {
            $("#filter-form").toggleClass("d-none");
        });
    </script>
@stop
<!-- BEGIN: page JS -->
