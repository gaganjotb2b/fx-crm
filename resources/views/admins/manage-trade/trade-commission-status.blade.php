@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Trade Commission Status')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
<style>
    td,
    th {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .col-lg-6.d-grid {
        padding-top: 4px;
    }

    #pending-commission-status td:first-child {
        border-left: 3px solid var(--custom-primary) !important;
    }

    #pending-commission-status th:first-child {
        border-left: 3px solid orange !important;
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
                        <h2 class="content-header-title float-start mb-0">{{__('admin-menue-left.trade_commission_status')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}">{{__('client-management.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#">{{__('admin-menue-left.manage_trade')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('admin-menue-left.trade_commission_status')}}</li>
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
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Ajax Sourced Server-side -->
            <section id="ajax-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-bottom d-flex justfy-content-between">
                                <h4 class="card-title">{{__('client-management.Report Filter')}}</h4>
                                <div class="btn-exports d-flex justify-content-between">
                                    <select data-placeholder="Export" class="select2-icons form-select" id="fx-export">
                                        <option value="download" data-icon="download" selected>{{__('client-management.Export')}}</option>
                                        <option value="csv" data-icon="file">CSV</option>
                                        <option value="excel" data-icon="file">Excel</option>
                                    </select>
                                </div>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form class="dt_adv_search" method="POST" id="filter-form">
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-md-4">
                                            <label class="form-label" for="open_time">{{__('page.open_close_time')}}</label>
                                            <select class="select2 form-select" id="open_time" name="open_close_time">
                                                <option value="">{{__('page.all')}}</option>
                                                <option value="close_time">{{__('page.close_time')}}</option>
                                                <option value="open_time">{{__('page.open_time')}}</option>
                                            </select>
                                        </div>
                                        <!-- <div class="col-md-4">
                                            <label class="form-label">{{__('client-management.Date')}}</label>
                                            <div class="mb-0">
                                                <input type="text" class="form-control dt-date flatpickr-range dt-input" data-column="5" placeholder="StartDate to EndDate" data-column-index="4" name="dt_date" />
                                                <input type="hidden" class="form-control dt-date start_date dt-input" data-column="5" data-column-index="4" name="value_from_start_date" />
                                                <input type="hidden" class="form-control dt-date end_date dt-input" name="value_from_end_date" data-column="5" data-column-index="4" />
                                            </div>
                                        </div> -->
                                        <div class="col-md-4">
                                            <!-- filter by request date -->
                                            <label for="request-date" class="form-label">{{__('client-management.Date')}}</label>
                                            <div class="input-group" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Search By Date"
                                                data-date="2017/01/01" data-date-format="yyyy/mm/dd">
                                                <span class="input-group-text">
                                                    <div class="icon-wrapper">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="50"
                                                            height="50" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="feather feather-calendar">
                                                            <rect x="3" y="4" width="18" height="18"
                                                                rx="2" ry="2"></rect>
                                                            <line x1="16" y1="2" x2="16"
                                                                y2="6"></line>
                                                            <line x1="8" y1="2" x2="8"
                                                                y2="6"></line>
                                                            <line x1="3" y1="10" x2="21"
                                                                y2="10"></line>
                                                        </svg>
                                                    </div>
                                                </span>
                                                <input id="value_from_start_date" type="text" name="value_from_start_date"
                                                    class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                <span class="input-group-text">to</span>
                                                <input id="value_from_end_date" type="text" name="value_from_end_date"
                                                    class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{__('page.trading_account')}}</label>
                                            <input type="text" class="form-control dt-input" placeholder="Trading account no." name="trading_account" />
                                        </div>
                                    </div>
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-md-4">
                                            <label class="form-label">{{__('page.ib_email')}}</label>
                                            <input id="ib-email" name="ib_email" type="text" class="form-control dt-input" data-column="4" placeholder="Filter by IB Email" data-column-index="3" />
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="category">{{__('page.trader_email')}}</label>
                                            <input id="trader-email" name="trader_email" type="text" class="form-control dt-input" data-column="4" placeholder="Filter by Trader Email" data-column-index="3" />
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="category">{{__('page.trade_number')}}</label>
                                            <input id="trade_number" name="trade_number" type="text" class="form-control dt-input" data-column="4" placeholder="Filter by Trade Number" data-column-index="3" />
                                        </div>
                                    </div>
                                    <div class="row g-1">
                                        <div class="col-md-4">
                                            <label class="form-label">{{__('page.group')}}</label>
                                            <select class="select2 form-select" id="client_group" name="client_group">
                                                <option value="">{{__('page.all')}}</option>
                                                @foreach($groups as $value)
                                                <option value="{{$value->id}}">{{$value->group_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- <div class="col-md-4">
                                            <label class="form-label">{{__('page.symbol')}}</label>
                                            <select class="select2 form-select" id="copy_symbol" name="copy_symbol">
                                                <option value="">{{__('page.all')}}</option>
                                                @foreach($groups as $value)
                                                <option value="{{$value->id}}">{{$value->group_name}}</option>
                                                @endforeach
                                            </select>
                                        </div> -->
                                        <div class="col-md-4">
                                            <label class="form-label" for="category">{{__('page.status')}}</label>
                                            <select class="select2 form-select" id="status" name="status">
                                                <option value="">{{__('page.all')}}</option>
                                                <option value="CREDITED">{{__('page.credited')}}</option>
                                                <option value="pending">{{__('page.pending')}} </option>
                                                <option value="TIME_IGNORE">{{__('page.ignore')}} </option>
                                                <option value="GROUP_IGNORE">{{__('page.group_ignore')}} </option>
                                                <option value="single">{{__('page.single')}} </option>
                                                <option value="ZERO_COMMISSION">{{__('page.zero_commission')}} </option>
                                                <option value="confuse">{{__('page.confuse')}} </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row mt-2">
                                                <div class="col-lg-6 d-grid">
                                                    <button id="btn-reset" type="button" class="btn btn-secondary">{{__('client-management.Reset')}}</button>
                                                </div>
                                                <div class="col-lg-6 d-grid">
                                                    <button id="btn-filter" type="button" class="btn btn-primary">{{__('client-management.Filter')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <hr class="my-0" />
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-6">
                                <div class="card">
                                    <div class="card-body border-start-3 border-start-primary">
                                        <div class="d-flex">
                                            <div class="section-icon">
                                                <i data-feather='bar-chart-2' class="icon-trd text-primary"></i>
                                            </div>
                                            <div class="section-data">
                                                <div class="tv-title">
                                                    Total trades
                                                </div>
                                                <div class="tv-total">
                                                    <span class="total-trade" id="total_1">0</span>
                                                    &#40;<small class="total-closed" id="total_2">0</small> closed&#41;
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-6">
                                <div class="card">
                                    <div class="card-body border-start-3 border-start-primary">
                                        <div class="d-flex">
                                            <div class="section-icon">
                                                <i data-feather='layers' class="icon-trd text-primary"></i>
                                            </div>
                                            <div class="section-data ms-1">
                                                <div class="tv-title">
                                                    Total volume
                                                </div>
                                                <div class="tv-total" id="total_3">
                                                    0
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card p-2 table-responsive">
                            <div class="card-datatable table-responsive">
                                <table class="datatables-ajax table ib-withdraw table-responsive" id="pending-commission-status">
                                    <thead>
                                        <tr>
                                            <th>{{__('page.trade')}}</th>
                                            <th>{{__('page.login')}}</th>
                                            <th>{{__('page.trader_email')}}</th>
                                            <th>{{__('page.ib_email')}}</th>
                                            <th>{{__('page.symbol')}}</th>
                                            <th>{{__('page.profit')}}</th>
                                            <th>{{__('page.open_time')}}</th>
                                            <th>{{__('page.close_time')}}</th>
                                            <th>{{__('page.volume')}}</th>
                                            <th>{{__('page.commission')}}</th>
                                        </tr>

                                    </thead>
                                </table>
                            </div>
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
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')

<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>


<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>


<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')

<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
<script>
    //  fetch datatable data-----------------------
    var trade_report = $("#pending-commission-status").fetch_data({
        url: '/admin/manage-trade/trade-commission-status-dt/dt',
        columns: [{
                "data": 'trade'
            },
            {
                "data": 'login'
            },
            {
                "data": 'trader_email'
            },
            {
                "data": 'ib_email'
            },
            {
                "data": 'symbol'
            },
            {
                "data": 'profit'
            },
            {
                "data": 'open_time'
            },
            {
                "data": 'close_time'
            },
            {
                "data": 'volume'
            },
            {
                "data": 'commission'
            },
        ],
        total_sum: 3,
        multiple: 'multiple',
        customorder: 7
    });
</script>
@stop
<!-- BEGIN: page JS -->