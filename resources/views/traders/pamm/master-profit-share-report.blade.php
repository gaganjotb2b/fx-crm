@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Master Profit Share Report')
@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <style>
        #withdraw_report_datatable tr,
        #withdraw_report_datatable td {
            background-color: #f7fafc;
            vertical-align: middle;
            text-align: left;
            padding-left: 24px;
        }

        #withdraw_report_datatable tr,
        #withdraw_report_datatable th {
            background-color: #f7fafc;
            vertical-align: middle;
            text-align: left !important;
        }

        .dark-version #withdraw_report_datatable tr,
        .dark-version #withdraw_report_datatable th {
            background-color: #141728;
        }

        #withdraw_report_datatable {
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

        #total_amount {
            padding-left: 24px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-transition: all .2s ease-in-out !important;
            -moz-transition: all .2s ease-in-out !important;
            -o-transition: all .2s ease-in-out !important;
            transition: all .2s ease-in-out !important;
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

        .form-group2.form-group-text input {
            width: 100%;
            padding: 8.2px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 7px;
            border: 1px solid #d2d6da !important;
            background: transparent;
            color: var(--font-color);
        }
        /* .dark-version */
    </style>
@endsection
@section('bread_crumb')
    <!-- bread crumb -->
    {!! App\Services\systems\BreadCrumbService::get_trader_breadcrumb() !!}
@stop
<!-- main content -->
@section('content')
    <div class="container-fluid py-4">
        <div class="custom-height-con">
            <div class="card mb-4 d-none" id="filter-form">
                <div class="card-body">
                    <!-- Card header -->
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <h5 class="mb-0">{{ __('page.filter') }} {{ __('page.reports') }}</h5>
                        </div>

                        <div class="border-bottom border-0">
                            <div class="btn-exports" style="width:200px">
                                <select data-placeholder="Select a state..." class="form-select btExport" id="fx-export">
                                    <option value="download" selected>{{ __('page.export_to') }}</option>
                                    <option value="csv">CSV</option>
                                    <option value="excel">Excel</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <form id="filterForm" class="dt_adv_search" method="POST">
                        @csrf
                        <div class="row gy-2 mb-md-1 my-3">
                            <div class="col-lg-4 col-md-6 p-1">
                                <div class="col-12">
                                    <label for="slave_order">Slave Order</label>
                                    <div class="form-group2 form-group-text">
                                        <input type="text" id="slave_order" name="slave_order">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 p-1">
                                <div class="col-12">
                                    <label for="slave">Slave Login</label>
                                    <div class="form-group2 form-group-text">
                                        <input type="text" id="slave" name="slave">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 p-1">
                                <div class="col-12">
                                    <label for="master">Master Login</label>
                                    <div class="form-group2 form-group-text">
                                        <input type="text" id="master" name="master">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-1 p-1">
                                <label for="status">Profit Share Status</label>
                                <select class="form-select choice-colors" name="status" id="status">
                                    <optgroup label="Search By Status">
                                        <option value="">{{ __('page.all') }}</option>
                                        <option value="pending">Pending</option>
                                        <option value="credited">Credited</option>
                                        <option value="declined">Declined</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 p-1">
                                <div class="col-12">
                                    <label for="amount">Amount</label>
                                    <div class="col-12 input-rang-group">
                                        <span
                                            class="col-2 input-rang-group-text rang-min">{{ __('ad-reports.min') }}</span>
                                        <input type="text" id="min" class="col-3 min" name="min">
                                        <span class="input-rang-group-text col-1">-</span>
                                        <input type="text" id="max" class="col-3 max" name="max">
                                        <span
                                            class="col-2 input-rang-group-text rang-max">{{ __('ad-reports.max') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 p-1">
                                <div class="col-12">
                                    <label for="profit_percent">Profit Percent(%)</label>
                                    <div class="col-12 input-rang-group">
                                        <span
                                            class="col-2 input-rang-group-text rang-min">{{ __('ad-reports.min') }}</span>
                                        <input type="text" id="min_profit_percent" class="col-3 min" name="min_profit_percent">
                                        <span class="input-rang-group-text col-1">-</span>
                                        <input type="text" id="max_profit_percent" class="col-3 max" name="max_profit_percent">
                                        <span
                                            class="col-2 input-rang-group-text rang-max">{{ __('ad-reports.max') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 p-1">
                                <div class="col-12">
                                    <label for="date">Profit Share Date</label>
                                    <div class="col-12 input-rang-group">
                                        <span class="col-1 input-rang-group-date-logo rang-min">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-calendar">
                                                <rect x="3" y="4" width="18" height="18" rx="2"
                                                    ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6">
                                                </line>
                                                <line x1="8" y1="2" x2="8" y2="6">
                                                </line>
                                                <line x1="3" y1="10" x2="21" y2="10">
                                                </line>
                                            </svg>
                                        </span>
                                        <input type="text" id="from" class="col-4 min flatpickr-basic"
                                            name="from" placeholder="YY-MM-DD">
                                        <span class="input-rang-group-text col-1">-</span>
                                        <input type="text" id="to" class="col-4 max flatpickr-basic"
                                            name="to" placeholder="YY-MM-DD">
                                        <span class="col-1 input-rang-group-date-logo rang-max"
                                            style="border-top-left-radius: 0rem !important;border-bottom-left-radius: 0rem !important;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-calendar">
                                                <rect x="3" y="4" width="18" height="18" rx="2"
                                                    ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6">
                                                </line>
                                                <line x1="8" y1="2" x2="8" y2="6">
                                                </line>
                                                <line x1="3" y1="10" x2="21" y2="10">
                                                </line>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mt-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="col-md-12 text-right">
                                    <button id="resetBtn" type="button" class="btn btn-dark w-100 "
                                        style="float: right; margin-top: -4px !important;">
                                        <span class="align-middle">{{ __('category.RESET') }}</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mt-3">
                                <label for="" class="form-label">&nbsp;</label>
                                <div class="col-md-12 text-right">
                                    <button id="filterBtn" type="button" class="btn bg-gradient-primary w-100"
                                        style="margin-top: -4px !important;">
                                        <span class="align-middle">{{ __('category.FILTER') }}</span>
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <button id="advance-filter-btn" type="button" class="btn bg-gradient-primary">
                        <span class="align-middle">Advance Filter</span>
                    </button>
                    <div class="table-responsive">
                        <table class="table table-flush datatables-ajax w-100 text-center" id="profit_share_report_datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Slave Order') }}</th>
                                    <th>{{ __('Slave Login') }}</th>
                                    <th>{{ __('Slave Profit') }}</th>
                                    <th>{{ __('Master Login') }}</th>
                                    <th>{{ __('Profit Percentage') }}(%)</th>
                                    <th>{{ __('Shared Time') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('page.amount') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="7" style="text-align: right;" class="details-control"
                                        rowspan="1">{{ __('ad-reports.total-amount') }} : </th>
                                    <th class="text-left" id="total_amount" rowspan="1" colspan="1">$0</th>
                                </tr>
                            </tfoot>
                        </table>
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
    <script type="text/javascript" src="{{ asset('trader-assets/assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>

    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>

    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
    <!-- <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js') }}"></script> -->
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>
    <script>
        var dt = $('#profit_share_report_datatable').DataTable({
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
                "url": "/user/user-pamm/master-profit-share?op=data_table",
                "data": function(d) {
                    return $.extend({}, d, {
                        "slave_order" : $('#slave_order').val(),
                        "slave" : $('#slave').val(),
                        "master" : $('#master').val(),
                        "status" : $('#status').val(),
                        "min" : $('#min').val(),
                        "max" : $('#max').val(),
                        "min_profit_percent" : $('#min_profit_percent').val(),
                        "max_profit_percent" : $('#max_profit_percent').val(),
                        "from" : $('#from').val(),
                        "to" : $('#to').val()
                    });
                }
            },

            "columns": [
                { "data": "slave_order" },
                { "data": "slave_login" },
                { "data": "slave_profit" },
                { "data": "master_login" },
                { "data": "profit_percent" },
                { "data": "share_time" },
                { "data": "status" },
                { "data": "amount" }
            ],
            order: [
                [2, 'desc']
            ],
            oLanguage: {
                "sLengthMenu": "\_MENU_",
                "sSearch": ""
            },
            language: {
                paginate: {
                    // remove previous & next text from pagination
                    previous: "<",
                    next: ">",
                },
            },
            "drawCallback": function(settings) {
                $("#filterBtn").html("FILTER");
                $("#total_amount").html('$' + settings.json.total_amount);
            }
        });
        $('#filterBtn').click(function(e) {
            dt.draw();
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

        //Reset filter operation
        $(document).ready(function() {
            $("#resetBtn").click(function() {
                $("#filterForm")[0].reset();
                $('#type').prop('selectedIndex', 0).trigger("change");
                $('#approved_status').prop('selectedIndex', 0).trigger("change");
                dt.draw();
            });
        });
        
        $(document).on("click", "#advance-filter-btn", function() {
            $("#filter-form").toggleClass("d-none");
        });
    </script>


@endsection
