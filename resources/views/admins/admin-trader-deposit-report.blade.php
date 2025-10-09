@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Trader deposit Report')
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
                            <h2 class="content-header-title float-start mb-0">{{ __('admin-breadcrumbs.trader_deposit') }}
                            </h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('admin.dashboard') }}">{{ __('ib-management.Home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">{{ __('admin-breadcrumbs.reports') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ __('admin-breadcrumbs.trader_deposit') }}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                    <button class="btn-icon btn btn-primary btn-round btn-sm" type="button"
                    aria-haspopup="true" aria-expanded="false" id="advance-filter-btn">Advance Filetr</button>
                </div>
                <!-- content body -->
                <div class="content-body">
                    <!-- Ajax Sourced Server-side -->
                    <section id="ajax-datatable">
                        <div class="row">
                            <div class="col-12">
                                <div class="card d-none" id="filter-form">
                                    <div class="card-header border-bottom d-flex justfy-content-between">
                                        <h4 class="card-title">{{ __('ad-reports.filter_report') }}</h4>
                                        <div class="btn-exports" style="width:200px">
                                            <select data-placeholder="Select a state..." class="select2-icons form-select"
                                                id="fx-export">
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
                                            <div class="row g-1 mb-1">
                                                <div class="col-md-3" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Transaction method">
                                                    <!-- filter by transaction method -->
                                                    <label for="transaction-method" class="form-label">Transaction
                                                        method</label>
                                                    <select class="select2 form-select" name="transaction_type"
                                                        id="transaction_type">
                                                        <option value="">{{ __('ad-reports.all') }}</option>
                                                        @foreach ($deposit as $row)
                                                            <option value="{{ $row->transaction_type }}">
                                                                {{ ucwords($row->transaction_type) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3 " data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Approved status">
                                                    <!-- filter by approved status -->
                                                    <label for="approved-status" class="form-label">Approved status</label>
                                                    <select class="select2 form-select" name="approved_status"
                                                        id="approved_status">
                                                        <option value="">{{ __('ad-reports.all') }}</option>
                                                        <option value="A">{{ __('ad-reports.approved') }}</option>
                                                        <option value="P">{{ __('ad-reports.pending') }}</option>
                                                        <option value="D">{{ __('ad-reports.declined') }}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 " data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Approved status">
                                                    <!-- kyc status -->
                                                    <label for="kyc-verification-status" class="form-label">KYC verification
                                                        status</label>
                                                    <select class="select2 form-select" name="kyc_status"
                                                        id="kyc-verification-status">
                                                        <option value="">{{ __('ad-reports.all') }}</option>
                                                        <option value="1">{{ __('ad-reports.verified') }}</option>
                                                        <option value="2">{{ __('ad-reports.pending') }}</option>
                                                        <option value="0">{{ __('ad-reports.unverified') }}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 " data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Search By Created By">
                                                    <!-- fiilter by created by -->
                                                    <label for="created-by" class="form-label">Create by</label>
                                                    <select class="select2 form-select" name="created_by"
                                                        id="created_by">
                                                        <optgroup label="Search By Status">
                                                            <option value="">{{ __('ad-reports.all') }}</option>
                                                            <option value="admin">Admin</option>
                                                            <option value="system">System</option>
                                                            <option value="system_admin">System Admin</option>
                                                            <option value="manager">Manager</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-1 mb-md-1">
                                                <div class="col-md-4">
                                                    <!-- filter by trader info -->
                                                    <label for="trader-info" class="form-label">Trader info.</label>
                                                    <input type="text" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Trader name / email / phone / country"
                                                        class="form-control dt-input dt-full-name" data-column="1"
                                                        name="trader_info" id="trader_info"
                                                        placeholder="Trader Name / Email / Phone / Country"
                                                        data-column-index="0" />
                                                </div>
                                                <div class="col-md-4">
                                                    <!-- filter by ib info -->
                                                    <label for="IB-info" class="form-label">IB info.</label>
                                                    <input type="text" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="IB name / email / phone / country"
                                                        class="form-control dt-input dt-full-name" data-column="1"
                                                        name="ib_info" id="ib-info"
                                                        placeholder="IB Name / Email / Phone / Country"
                                                        data-column-index="0" />
                                                </div>
                                                <div class="col-md-4">
                                                    <!-- filter by manager info -->
                                                    <label for="manager-info" class="form-label">Manager info.</label>
                                                    <input type="text" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Manager name / email / phone"
                                                        class="form-control dt-input dt-full-name" data-column="1"
                                                        name="manager_info" id="manager_info"
                                                        placeholder="Desk manager / Account manager"
                                                        data-column-index="0" />
                                                </div>
                                                <div class="col-md-4">
                                                    <!-- filter by trading account -->
                                                    <label for="trading-account" class="form-label">Trading
                                                        account</label>
                                                    <input type="text" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Search By Account Number"
                                                        class="form-control dt-input dt-full-name" data-column="1"
                                                        name="account_number" id="account_number"
                                                        placeholder="Account Number" data-column-index="0" />
                                                </div>
                                                <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Search By MIN MAX Amount Value">
                                                    <!-- filter by amount min / max -->
                                                    <label for="amount" class="form-label">Amount</label>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                {{ __('ad-reports.min') }}
                                                            </span>
                                                            <input id="min" type="text" class="form-control"
                                                                name="min">
                                                            <span class="input-group-text">-</span>
                                                            <input id="max" type="text" class="form-control"
                                                                name="max">
                                                            <span
                                                                class="input-group-text">{{ __('ad-reports.max') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <!-- filter by request date -->
                                                    <label for="request-date" class="form-label">Request date</label>
                                                    <div class="input-group" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Search By Create Date"
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
                                                        <input id="from" type="text" name="from"
                                                            class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                        <span class="input-group-text">to</span>
                                                        <input id="to" type="text" name="to"
                                                            class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-1">
                                                <div class="col-md-2 ms-auto">
                                                    <button id="btn_reset" type="button"
                                                        class="btn btn-secondary form-control" data-column="4"
                                                        data-column-index="3">{{ __('client-management.Reset') }}</button>
                                                </div>
                                                <div class="col-md-2 text-right">
                                                    <button id="filterBtn" type="button"
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
                                                    <th>{{ __('ad-reports.Transaction') }} {{ __('ad-reports.method') }}
                                                    </th>
                                                    <th>Created By</th>
                                                    <th>{{ __('ad-reports.approved') }} {{ __('ad-reports.status') }}</th>
                                                    <th>{{ __('ad-reports.request-at') }}</th>
                                                    <th>{{ __('ad-reports.approved') }} </th>
                                                    <th>{{ __('ad-reports.amount') }}</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th class="small-none-three"></th>
                                                    <th class="small-none-two"></th>
                                                    <th class="small-none"></th>
                                                    <th colspan="4" style="text-align: right;" class="details-control"
                                                        rowspan="1">{{ __('ad-reports.total-amount') }} </th>
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
                "url": "/admin/report/trader-deposit?op=data_table",
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
                    "data": "transaction_type"
                },
                {
                    "data": "created_by"
                },
                {
                    "data": "approved_status"
                },
                {
                    "data": "request_at"
                },
                {
                    "data": "approved_at"
                },
                {
                    "data": "amount"
                },

            ],

            "drawCallback": function(settings) {
                $("#filterBtn").html("FILTER");
                $("#total_amount").html('$' + settings.json.total_amount);
                var rows = this.fnGetData();
                if (rows.length !== 0) {
                    feather.replace();
                }
            },
            "order": [
                [5, 'desc']
            ]

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
                url: '/admin/report/trader-description-deposit/' + id,
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
                $('#kyc-verification-status').prop('selectedIndex', 0).trigger("change");
                $('#method').prop('selectedIndex', 0).trigger("change");
                $('#approved_status').prop('selectedIndex', 0).trigger("change");
                $('#platform').prop('selectedIndex', 0).trigger("change");
                $('#created_by').prop('selectedIndex', 0).trigger("change");
                $('#transaction_type').prop('selectedIndex', 0).trigger("change");
                dt.draw();
            });
        });
        
        $(document).on("click", "#advance-filter-btn", function() {
            $("#filter-form").toggleClass("d-none");
        });
    </script>
@stop
<!-- BEGIN: page JS -->
