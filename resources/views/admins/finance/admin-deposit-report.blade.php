@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Admin Deposit Report')
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
    /* @media screen and (max-width: 1280px) and (min-width: 800px) {

        .ib-withdraw thead tr th:nth-child(5),
        .ib-withdraw tbody tr td:nth-child(5) {
            display: none;
        }

        .small-none-three {
            display: none;
        }
    }

    @media screen and (max-width: 1440px) and (min-width: 900px) {

        .ib-withdraw thead tr th:nth-child(5),
        .ib-withdraw tbody tr td:nth-child(5) {
            display: none;
        }

        .small-none {
            display: none;
        }

        .small-none-three {
            display: none;
        }

    }

    @media screen and (max-width: 1440px) and (min-width: 900px) {

        .ib-withdraw thead tr th:nth-child(1),
        .ib-withdraw tbody tr td:nth-child(1) {
            display: none;
        }

        .small-none {
            display: none;
        }

        .small-none-three {
            display: none;
        }

    } */
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
                        <h2 class="content-header-title float-start mb-0">Admin Deposit Report</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('ib-management.Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{ __('admin-breadcrumbs.reports') }}</a>
                                </li>
                                <li class="breadcrumb-item active">Admin Deposit Report
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content body -->
            <div class="content-body">
                <!-- Ajax Sourced Server-side -->
                <section id="ajax-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom d-flex justfy-content-between">
                                    <h4 class="card-title">{{ __('ad-reports.filter_report') }}</h4>
                                    <div class="btn-exports" style="width:200px">
                                        <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                            <option value="download" data-icon="download" selected>
                                                {{ __('ib-management.export') }}
                                            </option>
                                            <option value="csv" data-icon="file">CSV</option>
                                            <option value="excel" data-icon="file">Excel</option>
                                        </select>
                                    </div>
                                </div>
                                <!--Search Form -->
                                <div class="card-body mt-2">
                                    <form class="dt_adv_search" id="filterForm" method="POST">
                                        <div class="row g-1 mb-md-1">
                                            <!-- Filter By Approved Status -->
                                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Approved Status">
                                                <label for="approved_status" class="form-label">Approved Status</label>
                                                <select class="select2 form-select" name="approved_status" id="approved_status">
                                                    <option value="">{{ __('ad-reports.all') }}</option>
                                                    <option value="A">{{ __('ad-reports.approved') }}</option>
                                                    <option value="P">{{ __('ad-reports.pending') }}</option>
                                                    <option value="D">{{ __('ad-reports.declined') }}</option>
                                                </select>
                                            </div>
                                            <!-- Filter By Client Type -->
                                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Client Type">
                                                <label for="client_type" class="form-label">Client Type</label>
                                                <select class="select2 form-select" name="client_type" id="client_type">
                                                    <option value="">{{ __('ad-reports.all') }}</option>
                                                    <option value="trader">Trader</option>
                                                    <option value="ib">IB</option>
                                                </select>
                                            </div>
                                            <!-- Filter By KYC Verification Status -->
                                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="KYC Verification Status">
                                                <label for="kyc-verification" class="form-label">KYC Verification Status</label>
                                                <select class="select2 form-select" name="verify_status" id="kyc-verification">
                                                    <optgroup label="Verification Status">
                                                        <option value="">All</option>
                                                        <option value="1">Verified</option>
                                                        <option value="0">Unverified</option>
                                                        <option value="2">Pending</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                            <!-- Filter By Trader Name/Email/Phone/Country -->
                                            <div class="col-md-4">
                                                <label for="trader_info" class="form-label">Trader Info.</label>
                                                <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Trader Info." class="form-control dt-input dt-full-name" data-column="1" name="trader_info" id="trader_info" placeholder="Trader Name / Email / Phone / Country" data-column-index="0" />
                                            </div>
                                            <!-- Filter By IB Name/Email/Phone/Country -->
                                            <div class="col-md-4">
                                                <label for="ib_info" class="form-label">IB Info.</label>
                                                <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="IB Info." class="form-control dt-input dt-full-name" data-column="1" name="ib_info" id="ib_info" placeholder="IB Name / Email / Phone / Country" data-column-index="0" />
                                            </div>
                                            <!-- Filter By Admin Name/Email -->
                                            <div class="col-md-4">
                                                <label for="admin_info" class="form-label">Admin Info.</label>
                                                <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Admin Info." class="form-control dt-input dt-full-name" data-column="1" name="admin_info" id="admin_info" placeholder="Admin Name / Email / Phone" data-column-index="0" />
                                            </div>
                                            <!-- Filter By Trading Account Number -->
                                            <div class="col-md-4">
                                                <label for="trading_acc" class="form-label">Account Number</label>
                                                <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Account Number" class="form-control dt-input dt-full-name" data-column="1" name="trading_acc" id="trading_acc" placeholder="Trading Account Number" data-column-index="0" />
                                            </div>
                                            <!-- Filter By Amount -->
                                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Amount Value">
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
                                            <!-- Filter By ReQuest Date -->
                                            <div class="col-md-4">
                                                <label for="" class="form-label">Request Date</label>
                                                <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Request Date" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
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
                                        </div>
                                        <div class="row g-1">
                                            <div class="col-md-4">
                                                
                                            </div>
                                            <div class="col-md-4">
                                                <button id="btn_reset" type="button" class="btn btn-secondary form-control" data-column="4" data-column-index="3">{{ __('client-management.Reset') }}</button>
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

                                    <table id="deposit_tbl" class="datatables-ajax ib-withdraw table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('admin-deposit-report.name') }}</th>
                                                <th>{{ __('client-management.Email') }}</th>
                                                <th>Client Type</th>
                                                <th>Admin Name</th>
                                                <th>Admin Email</th>
                                                <th>{{ __('ad-reports.amount') }}</th>
                                                <th>{{ __('ad-reports.status') }} </th>
                                                <th>Request Date</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th class="small-none-three"></th>
                                                <th colspan="4" style="text-align: right;" class="details-control" rowspan="1">{{ __('ad-reports.total-amount') }} </th>
                                                <th id="total_amount" rowspan="1" colspan="1">$0</th>
                                                <th colspan="2" class="small-none-two"></th>
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

<!-- datatable  -->
<script>
    // $(document).ready(function() {

    var dt = $('#deposit_tbl').DataTable({
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
            "url": "/admin/finance/deposit-report-dt?op=data_table",
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
                "data": "admin_name"
            },
            {
                "data": "admin_email"
            },
            {
                "data": "amount"
            },
            {
                "data": "status"
            },
            {
                "data": "request_at"
            },
        ],

        "drawCallback": function(settings) {
            $("#filterBtn").html("FILTER");
            $("#total_amount").html('$' + settings.json.total_amount);
            var rows = this.fnGetData();
            if (rows.length !== 0) {
                feather.replace();
            }
        }


    });
    $('#filterBtn').click(function(e) {
        dt.draw();
    });

    // });
    //    datatable descriptions
    $(document).on("click", ".dt-description", function(params) {
        let __this = $(this);
        let id = $(this).data('id');
        let user_id = $(this).data('user_id');
        // console.log(user_id);

        $.ajax({
            type: "GET",
            url: '/admin/finance/deposit-report-dt/description/' + id,
            dataType: 'json',
            success: function(data) {
                if (data.status == true) {
                    if ($(__this).closest("tr").next().hasClass("description")) {
                        $(__this).closest("tr").next().remove();
                        $(__this).find('.w').html(feather.icons['plus'].toSvg());
                    } else {
                        $(__this).closest('tr').after(data.description);
                        $(__this).closest('tr').next('.description').slideDown('slow').delay(5000);
                        // $(__this).find('svg').remove();
                        $(__this).find('.w').html(feather.icons['minus'].toSvg());
                    }
                }
            }
        })
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

    $(document).ready(function() {
        $("#btn_reset").click(function() {
            $("#filterForm")[0].reset();
            $('#kyc-verification').prop('selectedIndex', 0).trigger("change");
            $('#method').prop('selectedIndex', 0).trigger("change");
            $('#approved_status').prop('selectedIndex', 0).trigger("change");
            $('#platform').prop('selectedIndex', 0).trigger("change");
            $('#info').prop('selectedIndex', 0).trigger("change");
            $('#client_type').prop('selectedIndex', 0).trigger("change");
            dt.draw();
        });
    });
</script>
@stop
<!-- BEGIN: page JS -->