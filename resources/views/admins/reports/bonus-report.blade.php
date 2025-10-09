@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Bonus Report')
@section('vendor-css')
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
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
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
                        <h2 class="content-header-title float-start mb-0">{{__('ad-reports.bonus-report')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('ib-management.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{__('admin-breadcrumbs.reports')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('ad-reports.bonus-report')}}
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
                        <div class="card d-none" id="filter-form">
                            <!-- export buttons -->
                            <div class="card-header border-bottom d-flex justfy-content-between">
                                <h4 class="card-title">{{__('ad-reports.filter_report')}}</h4>
                                <div class="btn-exports" style="width:200px">
                                    <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                        <option value="download" data-icon="download" selected>{{__('ib-management.export')}}</option>
                                        <option value="csv" data-icon="file">CSV</option>
                                        <option value="excel" data-icon="file">Excel</option>
                                    </select>
                                </div>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form id="filterForm" class="dt_adv_search" method="POST">
                                    <div class="row g-1 mb-md-1">
                                        <!-- filter by type -->
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Search by add or deduct type">
                                            <label for="credit-type" class="form-label">Type</label>
                                            <select class="select2 form-select" name="type" id="credit-type">
                                                <optgroup label="Search By Type">
                                                    <option value="">{{__('ad-reports.all')}}</option>
                                                    <option value="add">{{__('ad-reports.add')}}</option>
                                                    <option value="deduct">{{__('finance.Deduct')}}</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <!-- filter by trader name/email / phone / country -->
                                        <div class="col-md-4">
                                            <label for="info" class="form-label">Trader Info</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Trader Info" class="form-control dt-input dt-full-name" data-column="1" name="info" id="info" placeholder="Trader Name / Email / Phone / Country" data-column-index="0" />
                                        </div>
                                        <!-- filter by ib name/email -->
                                        <div class="col-md-4">
                                            <label for="ib_info">IB Info</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="IB Info" class="form-control dt-input dt-full-name" data-column="1" name="ib_info" id="ib_info" placeholder="IB Name / Email / Phone / Country" data-column-index="0" />
                                        </div>
                                        <!-- account number -->
                                        <div class="col-md-4">
                                            <label for="account_number">Trading Account</label>
                                            <input type="text" class="form-control form-input" id="account_number" name="account_number" placeholder="Account Number">
                                        </div>
                                        <!-- Filter by manager Name /Email -->
                                        <div class="col-md-4">
                                            <label for="manager_info" class="form-label">Manager Info</label>
                                            <input type="text" class="form-control form-input" id="manager_info" name="manager_info" placeholder="Manager Name / Email / Phone">
                                        </div>
                                        <!-- filter by min/max -->
                                        <div class="col-md-4">
                                            <label for="" class="form-label">Amount</label>
                                            <div class="form-group">
                                                <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Amount">
                                                    <span class="input-group-text">
                                                        {{__('ad-reports.min')}}
                                                    </span>
                                                    <input id="min" type="text" class="form-control" name="min">
                                                    <span class="input-group-text">-</span>
                                                    <input id="max" type="text" class="form-control" name="max">
                                                    <span class="input-group-text">{{__('ad-reports.max')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- filter by date from/to -->
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Credit Date">
                                            <label for="" class="form-label">Credit Date</label>
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
                                        <div class="col-md-4 text-right">
                                            <label for="" class="form-label">&nbsp;</label>
                                            <button id="resetBtn" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('ad-reports.btn-reset')}}</span>
                                            </button>
                                        </div>
                                        <!-- filter button -->
                                        <div class="col-md-4 text-right">
                                            <label for="" class="form-label">&nbsp;</label>
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
                    <div class="col-12">
                        <div class="card">
                            <!--Search Form -->
                            <div class="card-body mt-2">

                                <table id="bonus_report_tbl" class="datatables-ajax table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{__('ad-reports.email')}}</th>
                                            <th>Type</th>
                                            <th>Account</th>
                                            <th>Credit Expire</th>
                                            <th>Credit Date</th>
                                            <th>Created By</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" style="text-align: right;" class="details-control" rowspan="1">{{__('page.total')}} {{__('page.amount')}} : </th>
                                            <th id="total_amount" rowspan="1" colspan="1">$0</th>
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
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js')}}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
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
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>


<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>

<!-- datatable  -->
<script>
    $(document).ready(function() {

        var dt = $('#bonus_report_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "lengthChange": true,
            "buttons": true,
            "dom": 'B<"clear">lfrtip',
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


                "url": "/admin/report/user-bonus-report?op=data_table",
                "data": function(d) {
                    return $.extend({}, d, $("#filterForm").serializeObject());
                }
            },

            "columns": [{
                    "data": "email"
                },
                {
                    "data": "type"
                },
                {
                    "data": "account"
                },
                {
                    "data": "credit_expire"
                },
                {
                    "data": "credit_date"
                },
                {
                    "data": "created_by"
                },
                {
                    "data": "amount"
                },

            ],
            "order": [4, 'desc'],
            "drawCallback": function(settings) {
                $("#filterBtn").html("FILTER");
                $("#total_amount").html('$' + settings.json.total_amount);
            }
        });
        $('#filterBtn').click(function(e) {
            dt.draw();
        });

        $("#resetBtn").on("click", function(e) {
            $("#filter-form").find("select").val('').change();
            $(".start_date").val('');
            $(".end_date").val('');
            $("#filter-form").trigger('reset');
            $(".select2").val("").trigger('change');
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