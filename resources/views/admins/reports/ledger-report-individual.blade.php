@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Individual Ledger Report')
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
                        <h2 class="content-header-title float-start mb-0">
                            {{ __('admin-breadcrumbs.individual_ledger_report') }}
                        </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('ib-management.Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{ __('admin-breadcrumbs.reports') }}</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    {{ __('admin-breadcrumbs.individual_ledger_report') }}
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
                        <div class="card  d-none" id="filter-form">
                            <div class="card-header border-bottom d-flex justfy-content-between">
                                <h4 class="card-title">{{ __('ad-reports.filter_report') }}</h4>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form class="dt_adv_search" id="filterForm" method="POST">
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-md-4">
                                            <!-- filter by kyc verification status -->
                                            <label for="kyc-status" class="form-label">KYC verification status</label>
                                            <select class="select2 form-select" name="kyc_status" id="kyc-status">

                                                <option value="">All</option>
                                                <option value="1">Verified</option>
                                                <option value="2">Pending</option>
                                                <option value="0">Unverified</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by block / unblock -->
                                            <label for="active-status" class="form-label">Active Status</label>
                                            <select class="select2 form-select" name="active_status" id="active-status">
                                                <option value="">All</option>
                                                <option value="1">Active</option>
                                                <option value="0">Block</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by client type -->
                                            <label for="client-type" class="form-label">Client type</label>
                                            <select class="select2 form-select" name="client_type" id="client-type">
                                                <option value="">All</option>
                                                <option value="ib">IB</option>
                                                <option value="trader">Trader</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by category -->
                                            <label for="client-category" class="form-label">Category</label>
                                            <select class="select2 form-select" name="category" id="client-category">
                                                <option value="">All</option>
                                                @foreach($category as $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by trader info-->
                                            <label for="trader-info" class="form-label">Trader info.</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Trader name / email / phone / country" class="form-control dt-input dt-full-name" data-column="1" name="trader_info" id="user_info" placeholder="Trader name / email / phone / country" data-column-index="0" />
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by trader info-->
                                            <label for="trading-account" class="form-label">Trading account</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Account number" class="form-control dt-input dt-full-name" data-column="1" name="trading_account" id="trading-account" placeholder="Account number" data-column-index="0" />
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by IB info-->
                                            <label for="ib-info" class="form-label">IB info.</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="IB name / email / phone / country" class="form-control dt-input dt-full-name" data-column="1" name="ib_info" id="ib-info" placeholder="IB name / email / phone / country" data-column-index="0" />
                                        </div>
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Manager name / email / phone">
                                            <!-- filter by manager info -->
                                            <label for="manager-info" class="form-label">Manager info.</label>
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="manager_info" id="manager-info" placeholder="Desk manager / Account manager" data-column-index="0" />
                                        </div>
                                        <div class="col-md-4">
                                            <!-- joining date -->
                                            <label for="" class="form-label">Joining Date</label>
                                            <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Request Date">
                                                <span class="input-group-text">
                                                    <div class="icon-wrapper">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                                            </rect>
                                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                                        </svg>
                                                    </div>
                                                </span>
                                                <input id="from" type="text" id="fp-default" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" name="from">
                                                <span class="input-group-text">to</span>
                                                <input id="to" type="text" id="fp-default" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" name="to">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-1">
                                        <div class="col-md-4">
                                            <a href="{{ route('admin.report.ledger') }}" class="btn btn-outline-dark w-100 waves-effect waves-float waves-light" style="float: right">
                                                <span class="align-middle">{{ __('ad-reports.all_ledger') }}</span>
                                            </a>
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

                                <table id="individual_table" class="datatables-ajax ib-withdraw table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('admin-deposit-report.name') }}</th>
                                            <th>{{ __('client-management.Email') }}</th>
                                            <th>{{ __('ad-reports.phone') }} </th>
                                            <th>{{ __('ad-reports.user_type') }} </th>
                                            <th>{{ __('ad-reports.status') }} </th>
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
    $(document).ready(function() {

        var dt = $('#individual_table').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "lengthChange": true,
            "buttons": true,
            // "dom": 'B<"clear">lfrtip',
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
                "url": "/admin/report/individual-ledger-report?op=data_table",
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
                    "data": "phone"
                },
                {
                    "data": "user_type"
                },
                {
                    "data": "status"
                },

            ],

            "drawCallback": function(settings) {
                $("#filterBtn").html("FILTER");
                var rows = this.fnGetData();
                if (rows.length !== 0) {
                    feather.replace();
                }
            }


        });
        $('#filterBtn').click(function(e) {
            dt.draw();
        });
        $("#btn_reset").click(function() {
            $("#filterForm")[0].reset();
            $(".select2").val("").trigger('change');
            dt.draw();
        });

    });

    //    datatable descriptions
    $(document).on("click", ".dt-description", function(params) {
        let __this = $(this);
        let user_id = $(this).data('user_id');
        let user_name = $(this).data('user_name');
        // console.log(user_id);

        $.ajax({
            type: "GET",
            url: '/admin/report/individual-description-report',
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

                        //Inner datatable
                        if ($(__this).closest("tr").next(".description").find('.deposit-details')
                            .length) {
                            $(__this).closest("tr").next(".description").find('.deposit-details')
                                .DataTable().clear().destroy();
                            var dt_inner = $(__this).closest('tr').next('.description').find(
                                '.deposit-details').DataTable({
                                "processing": true,
                                "serverSide": true,
                                "searching": false,
                                "lengthChange": false,
                                "buttons": true,
                                "dom": 'Bfrtip',
                                buttons: [{
                                        extend: 'csv',
                                        text: 'csv',
                                        className: 'btn btn-success btn-sm ',
                                        action: serverSideButtonAction,
                                        title: user_name + " Ledger Report"
                                    },
                                    {
                                        extend: 'copy',
                                        text: 'Copy',
                                        className: 'btn btn-success btn-sm',
                                        action: serverSideButtonAction,
                                        title: user_name + " Ledger Report"
                                    },
                                    {
                                        extend: 'excel',
                                        text: 'excel',
                                        className: 'btn btn-warning btn-sm',
                                        action: serverSideButtonAction,
                                        title: user_name + " Ledger Report"
                                    },
                                    {
                                        extend: 'pdf',
                                        text: 'pdf',
                                        className: 'btn btn-danger btn-sm',
                                        action: serverSideButtonAction,
                                        title: user_name + " Ledger Report"
                                    }
                                ],

                                "ajax": {
                                    "url": "/admin/report/individual-inner-description/" +
                                        user_id
                                },
                                "columns": [{
                                        "data": "date"
                                    },
                                    {
                                        "data": "ledger"
                                    },
                                    {
                                        "data": "transactin_status"
                                    },
                                    {
                                        "data": "wallet_type"
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
                                "order": [
                                    [1, 'desc']
                                ]
                            });
                        }
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

    $(document).on("change", ".single-export", function() {
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