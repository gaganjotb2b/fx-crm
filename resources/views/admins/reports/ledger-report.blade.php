@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'All Ledger Report')
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
    /* for Laptop */
    @media screen and (max-width: 1280px) and (min-width: 800px) {

        .external-fund-transfer thead tr th:nth-child(3),
        .external-fund-transfer tbody tr td:nth-child(3) {
            display: none;
        }

        .small-none {
            display: none;
        }
    }

    @media screen and (max-width: 1280px) and (min-width: 800px) {

        .external-fund-transfer thead tr th:nth-child(2),
        .external-fund-transfer tbody tr td:nth-child(2) {
            display: none;
        }

        .small-none-two {
            display: none;
        }
    }



    @media screen and (max-width: 1440px) and (min-width: 900px) {

        .external-fund-transfer thead tr th:nth-child(3),
        .external-fund-transfer tbody tr td:nth-child(3) {
            display: none;
        }

        .small-none {
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
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('admin-breadcrumbs.ledger_report') }}
                        </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('category.Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{ __('admin-breadcrumbs.reports') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('admin-breadcrumbs.ledger_report') }}
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
                        <button class="btn-icon btn btn-primary btn-round btn-sm" type="button"
                            aria-haspopup="true" aria-expanded="false" id="advance-filter-btn">Advance Filetr</button>
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
                <div class="row">
                    <div class="col-12">
                        <div class="card d-none" id="filter-form">
                            <div class="card-header border-bottom d-flex justfy-content-between">
                                <h4 class="card-title">{{ __('ad-reports.filter_report') }}</h4>
                                <div class="btn-exports" style="width:200px">
                                    <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                        <option value="download" data-icon="download" selected>Export to</option>
                                        <option value="csv" data-icon="file">CSV</option>
                                        <option value="excel" data-icon="file">Excel</option>
                                    </select>
                                </div>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form id="filterForm" class="dt_adv_search" method="POST">
                                    <div class="row g-1">
                                        <!-- Filter By Month -->
                                        <div class="col-md-4">
                                            <label for="filter_month" class="form-label">Filter By Month</label>
                                            <select class="select2 form-select" name="filter_month" id="filter_month">
                                                <optgroup label="Filter by Month">
                                                    <option value="">Filter by Month</option>
                                                    @foreach ($lastMontsOfyear as $item)
                                                    <option value="<?= $item['value'] ?>"><?= $item['title'] ?>
                                                    </option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                        <!-- Filter By weak -->
                                        <div class="col-md-4">
                                            <label for="filter_weak" class="form-label">Filter By Weak</label>
                                            <select class="select2 form-select" name="filter_weak" id="filter_weak">
                                                <optgroup label="Filter by Weak">
                                                    <option value="">Filter by Weak</option>
                                                    <option value="thisWeak">This week</option>
                                                    <option value="lastWeak">Last week</option>
                                                    <option value="last2Weak">Last 2 week</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by client type -->
                                            <label for="client-type" class="form-label">Client type</label>
                                            <select class="select2 form-select" name="client_type" id="client-type">
                                                <optgroup label="Filter by Weak">
                                                    <option value="">All</option>
                                                    <option value="ib">IB</option>
                                                    <option value="trader">Trader</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <!-- Filter By Request Date  -->
                                        <div class="col-md-4">
                                            <label for="" class="form-label">Request Date</label>
                                            <div class="input-group" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
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
                                                <input id="from" type="text" name="from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                <span class="input-group-text">to</span>
                                                <input id="to" type="text" name="to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                            </div>
                                        </div>
                                        <!-- Filter By trader Info -->
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Trader Info">
                                            <label for="info" class="form-label">Trader Info</label>
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="info" id="info" placeholder="Trader Name / Email / Phone / Country" data-column-index="0" />
                                        </div>
                                        <!-- Filter By Ib Info -->
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="IB Info">
                                            <label for="info" class="form-label">IB Info</label>
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="ib_info" id="ib_info" placeholder="IB Name / Email / Phone / Country" data-column-index="0" />
                                        </div>
                                        <!-- Filter By Manager Info -->
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Manager Info">
                                            <label for="manager_info" class="form-label">Manager Info</label>
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="manager_info" id="manager_info" placeholder="Manager Name / Email / Phone" data-column-index="0" />
                                        </div>
                                        <!-- filter by trading account -->
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Trading account">
                                            <label for="trading-account" class="form-label">Trading account</label>
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="trading_account" id="trading-account" placeholder="Account number" data-column-index="0" />
                                        </div>
                                        <div class="col-md-4">
                                            <label for="" class="form-label">Amount</label>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        {{ __('ad-reports.min') }}
                                                    </span>
                                                    <input id="min" type="text" class="form-control" name="min">
                                                    <span class="input-group-text">-</span>
                                                    <input id="max" type="text" class="form-control" name="max">
                                                    <span class="input-group-text">{{ __('ad-reports.max') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-1 mt-1">
                                        <div class="col-md-4">
                                            <a href="{{ route('admin.report.ledger-individual') }}" class="btn btn-outline-dark w-100 waves-effect waves-float waves-light" style="float: right">
                                                <span class="align-middle">{{ __('ad-reports.individual_ledger') }}</span>
                                            </a>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <button id="resetBtn" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light" style="float: right">
                                                <span class="align-middle">{{ __('ad-reports.btn-reset') }}</span>
                                            </button>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <button id="filterBtn" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
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

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!--Search Form -->
                            <div class="card-body mt-2 table-responsive">

                                <table id="externalFund_transfer_tbl" class="datatables-ajax external-fund-transfer table">
                                    <thead>
                                        <tr>
                                            <th class="text-truncate">{{ __('page.name') }} </th>
                                            <th class="text-truncate">{{ __('page.email') }} </th>
                                            <th>Client type</th>
                                            <th class="text-truncate">{{ __('page.date') }}</th>
                                            <th class="text-truncate">{{ __('page.ledger') }}</th>
                                            <th class="text-truncate">{{ __('page.status') }}</th>
                                            <th class="text-truncate">{{ __('page.credit_amount') }}</th>
                                            <th class="text-truncate">{{ __('page.debit_amount') }}</th>
                                            <th class="text-truncate">{{ __('page.remark') }}</th>
                                        </tr>
                                    </thead>

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
{{-- <script src="{{ asset('admin-assets/app-assets/vendors/js/vendors.min.js') }}"></script> --}}
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
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

<!-- datatable  -->
<script>
    $(document).ready(function() {

        var dt = $('#externalFund_transfer_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "lengthChange": true,
            "buttons": true,
            "dom": 'B<"clear">lfrtip',

            columnDefs: [{
                // targets: [0, 1, 2, 3, 4, 5, 6, 7],
                targets: 2,
                className: 'text-truncate'
            }],
            buttons: [{
                    extend: 'csv',
                    text: 'csv',
                    className: 'btn btn-success btn-sm',
                    action: serverSideButtonAction
                },
                {
                    extend: 'copy',
                    text: 'Copy',
                    className: 'btn btn-success btn-sm',
                    action: serverSideButtonAction
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    className: 'btn btn-warning btn-sm',
                    action: serverSideButtonAction
                },
                {
                    extend: 'pdf',
                    text: 'pdf',
                    className: 'btn btn-danger btn-sm',
                    action: serverSideButtonAction
                }
            ],
            "ajax": {


                "url": "/admin/report/ledger-report?op=data_table",
                "data": function(d) {
                    return $.extend({}, d, $("#filterForm").serializeObject());
                }
            },

            "columns": [{
                    "data": "name"
                },
                {
                    "data": "email"
                },
                {
                    "data": "client_type"
                },
                {
                    "data": "date"
                },
                {
                    "data": "ledger"
                },
                {
                    "data": "status"
                },
                {
                    "data": "credit_amount"
                },
                {
                    "data": "debit_amount"
                },
                {
                    "data": "remark"
                },

            ],

            "drawCallback": function(settings) {
                $("#filterBtn").html("FILTER");
            }


        });
        $('#filterBtn').click(function(e) {
            dt.draw();
        });
        $("#resetBtn").click(function() {
            $("#filterForm")[0].reset();
            $(".select2").val("").trigger('change');
            $('#filter_month').prop('selectedIndex', 0).trigger("change");
            $('#filter_weak').prop('selectedIndex', 0).trigger("change");
            dt.draw();
        });

    });


    // datatable export function
    $(document).on("change", "#fx-export", function() {
        if ($(this).val() === 'csv') {
            $(".buttons-csv").trigger('click');
        }
        if ($(this).val() === 'excel') {
            $(".buttons-excel").trigger('click');
        }

    });

    function serverSideButtonAction(e, dt, node, config) {

        var me = this;
        var button = config.text.toLowerCase();
        if (typeof $.fn.dataTable.ext.buttons[button] === "function") {
            button = $.fn.dataTable.ext.buttons[button]();
        }
        var len = dt.page.len();
        var start = dt.page();
        dt.page(0);

        // Assim que ela acabar de desenhar todas as linhas eu executo a função do botão.
        // ssb de serversidebutton
        dt.context[0].aoDrawCallback.push({
            "sName": "ssb",
            "fn": function() {
                $.fn.dataTable.ext.buttons[button].action.call(me, e, dt, node, config);
                dt.context[0].aoDrawCallback = dt.context[0].aoDrawCallback.filter(function(e) {
                    return e.sName !== "ssb"
                });
            }
        });
        dt.page.len(999999999).draw();
        setTimeout(function() {
            dt.page(start);
            dt.page.len(len).draw();
        }, 500);
    }
    
        $(document).on("click", "#advance-filter-btn", function() {
            $("#filter-form").toggleClass("d-none");
        });
</script>
@stop
<!-- BEGIN: page JS -->