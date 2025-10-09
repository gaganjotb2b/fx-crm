@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Copy Trades Report')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/calendars/fullcalendar.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/pages/app-calendar.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
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
                        <h2 class="content-header-title float-start mb-0">Copy Trades Report</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('ib-management.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Social Trade</a>
                                </li>
                                <li class="breadcrumb-item active">Copy Trades Report
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
                            {{-- <div class="card-header border-bottom d-flex justfy-content-between">
                                <h4 class="card-title">{{__('ad-reports.filter_report')}}</h4>
                            <div class="btn-exports" style="width:200px">
                                <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                    <option value="download" data-icon="download" selected>{{__('ib-management.export')}}</option>
                                    <option value="csv" data-icon="file">CSV</option>
                                    <option value="excel" data-icon="file">Excel</option>
                                </select>
                            </div>
                        </div> --}}
                        <!--Search Form -->
                        <div class="card-body mt-2">
                            <form id="filterForm" action="/admin/pamm/copy-trades-report-process?export=1" class="dt_adv_search" method="POST">
                                @csrf
                                <input type="hidden" name="op" value="datatable_mt5">
                                <input type="hidden" name="server" value="mt4">
                                <input type="hidden" name="start" value="0">
                                <input type="hidden" name="length" id="export_length" value="10">
                                <input type="hidden" name="isnew" value="0">
                                <input type="hidden" name="order" value="order">
                                <input type="hidden" name="dir" value="desc">
                                <div class="row g-1">
                                    <div class="col-md-4 mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Search Open Date / Close Date">
                                        <select class="select2 form-select" name="by_time" id="by_time">
                                            <optgroup label="Search Open Date / Close Date">
                                                <option value="">All</option>
                                                <option value="OpenTime">OPEN TIME</option>
                                                <option value="CloseTime">CLOSE TIME </option>
                                            </optgroup>
                                        </select>
                                    </div>

                                    <div class="col-md-4  mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Symbol">
                                        <select class="select2 form-select" name="symbol" id="symbol">
                                            <optgroup label="Verification Status">
                                                <option value="">All </option>
                                                <?= copy_symbols() ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Order Position">
                                        <select class="select2 form-select" name="order_type" id="order_type">
                                            <optgroup label="Search By Order Position">
                                                <option value="">All </option>
                                                <option value="OpenOrder">OPEN ORDER </option>
                                                <option value="CloseOrder">CLOSE ORDER </option>
                                            </optgroup>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Order Type">
                                        <select class="select2 form-select" name="cmd" id="cmd">
                                            <optgroup label="Search By Order Type">
                                                <option value="">All</option>
                                                <option value="0">BUY</option>
                                                <option value="1">SEL </option>
                                            </optgroup>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Trade Number" class="form-control dt-input dt-full-name" data-column="1" name="ticket" id="ticket" placeholder="Trade Number" data-column-index="0" />
                                    </div>




                                    <div class="col-md-4">
                                        <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Open or Close Date" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
                                            <span class="input-group-text">
                                                <div class="icon-wrapper">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                                    </svg>
                                                </div>
                                            </span>
                                            {{-- <input type="text" class="form-control" id="start-date" name="from" placeholder="Start Date" /> --}}
                                            <input id="date_from" type="text" name="date_from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                            <span class="input-group-text">to</span>
                                            {{-- <input type="text" class="form-control" id="end-date" name="to" placeholder="End Date" /> --}}
                                            <input id="date_to" type="text" name="date_to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">

                                        </div>
                                    </div>


                                </div>
                                <div class="row g-1 mt-0">
                                    <div class="col-md-3">
                                        <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By slave account" class="form-control dt-input dt-full-name" data-column="1" name="trade_account" id="trade_account" placeholder="Slave Account" data-column-index="0" />
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Master Account" class="form-control dt-input dt-full-name" data-column="1" name="master_account" id="master_account" placeholder="Search By Master Account" data-column-index="0" />
                                    </div>

                                    <div class="col-md-2">
                                        <button id="exportBtn" type="submit" class="btn btn-primary w-100 waves-effect waves-float waves-light">Export </button>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <button id="resetBtn" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                            <span class="align-middle">{{__('ad-reports.btn-reset')}}</span>
                                        </button>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <button id="filterBtn" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                            <span class="align-middle">{{__('category.FILTER')}}</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <hr class="my-0" />

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-12">
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
                                        <span class="total-trade amount counter ct_total_trades">0</span>
                                        &#40;<small class="total-closed counter ct_total_closed">0</small> closed&#41;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
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
                                    <div class="tv-total amount counter ct_total_volume">
                                        0
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mt-2 table-responsive">
                            <table id="example" class="datatables-ajax table">
                                <thead>
                                    <tr>
                                        <th>ORDER</th>
                                        <th>LOGIN</th>
                                        <th>SYMBOL</th>
                                        <th>VOLUME</th>
                                        <th>OPEN TIME</th>
                                        <th>CLOSE TIME</th>
                                        <th>PROFIT</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th colspan="2"></th>
                                        <th class="text-right">TOTAL:</th>
                                        <th class="footer_total_volume">0</th>
                                        <th></th>
                                        <th class="text-right">TOTAL:</th>
                                        <th class="footer_total_profit">0</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
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
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')

<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<!-- datatable -->
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>


<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js')}}"></script>

<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>


<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
<script src="{{ asset('trader-assets/assets/js/filter.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/common.js') }}"></script>


<script>
    $(document).ready(function() {
        var dt;
        var isnew = 1;
        var total_trades = 0;
        var columns = ['order', 'login', 'symbol', 'volume', 'open_time', 'close_time', 'profit', 'comment'];

        dt = $('#example').DataTable({
            serverSide: true,
            ordering: true,
            searching: false,
            lengthMenu: [25, 50, 100],
            pageLength: 25,
            ajax: function(data, callback) {
                var oderBY = columns[data.order[0].column];
                var oderDir = data.order[0].dir;

                var postData = {
                    start: data.start,
                    length: data.length,
                    isnew: isnew,
                    order: oderBY,
                    dir: oderDir
                };

                $.ajax({
                    url: '/admin/pamm/copy-trades-report-process',
                    type: "POST",
                    data: postData,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(result) {
                        if (isnew) {
                            $('.footer_total_volume').html(result.counter.total_volume);
                            $('.footer_total_profit').html('$' + result.counter.total_profit);
                            total_trades = result.counter.total_trades;
                        }

                        callback({
                            draw: data.draw,
                            data: result.data,
                            recordsTotal: total_trades,
                            recordsFiltered: total_trades
                        });

                        isnew = 0;
                    }
                });
            },
            fixedColumns: true,
            fixedHeader: true,
            scrollX: true,
            scrollY: 350,
            scroller: { loadingIndicator: true },
            columns: [
                // { "class": "details-control", "orderable": false, "data": null, "defaultContent": "" },
                { "data": "0" }, { "data": "1" }, { "data": "2" }, { "data": "3" },
                { "data": "4" }, { "data": "5" }, { "data": "6" }, { "data": "7" }
            ],
            drawCallback: function() {
                $("#filterBtn").html("<span class='align-middle'>FILTER</span>");
            },
            order: [[0, 'desc']]
        });

        $("#filterBtn").click(function() {
            isnew = 1;
            $(this).html("<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>");
            dt.draw();
        });

        $("#resetBtn").click(function() {
            $("#filterForm")[0].reset();
            $('#transaction_type, #verification_status, #status, #symbol').prop('selectedIndex', 0).trigger("change");
            dt.draw();
        });

        $(document).on("change", "#fx-export", function() {
            if ($(this).val() === 'csv') $(".buttons-csv").trigger('click');
            if ($(this).val() === 'excel') $(".buttons-excel").trigger('click');
        });
    });
</script>
@stop
<!-- BEGIN: page JS -->