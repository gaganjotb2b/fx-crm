@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Internal-Fund transfer Report')
@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <style>
        .btn {
            display: inline-block;
            padding: 8px 20px;
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

        .dark-version .rang-max {
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
        .rang-max {
            border-top-left-radius: 0rem !important;
            border-bottom-left-radius: 0rem !important;
        }
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
            <div class="card d-none" id="filter-form">
                <!-- Card header -->
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="dfsdf">
                            <h5 class="mb-0">{{ __('page.filter') }} {{ __('page.reports') }}</h5>
                        </div>

                        <div class=" border-bottom border-0">
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
                        <div class="row g-1 mb-md-1 my-3">
                            <div class="col-lg-4 col-md-6 mb-3">
                                <select class="form-select choice-material" name="type" id="type">
                                    <optgroup>
                                        <option value="" selected>{{ __('page.all') }}</option>
                                        <option value="atw">{{ __('page.account_to_wallet') }}</option>
                                        <option value="wta">{{ __('ad-reports.Wallet To Account') }}</option>
                                    </optgroup>
                                </select>
                            </div>

                            <div class="col-lg-4 col-md-6 mb-3">
                                <select class="form-select choice-colors" name="approved_status" id="approved_status">
                                    <optgroup label="Search By Status">
                                        <option value="" selected>{{ __('page.all') }}</option>
                                        <option value="A">{{ __('ad-reports.approved') }}</option>
                                        <option value="P">{{ __('page.pending') }}</option>
                                        <option value="D">{{ __('ad-reports.declined') }}</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <input type="text" class="form-control dt-input dt-full-name" data-column="1"
                                    name="info" id="info"
                                    placeholder="{{ __('page.name') }}/{{ __('page.email') }}" data-column-index="0" />
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <input type="text" class="form-control dt-input dt-full-name" data-column="1"
                                    name="account_number" id="account_number"
                                    placeholder="{{ __('page.trading_account') }}" data-column-index="0" />
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="col-12">
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
                            <div class="col-lg-4 col-md-6">
                                <div class="col-12">
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
                                        <span class="col-1 input-rang-group-date-logo rang-max">
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
                        </div>
                        <div class="row g-1 mb-md-1">
                            <div class="col-lg-4 col-md-12" style="float: left;">&nbsp;</div>
                            <div class="col-lg-4 col-md-6 text-right">
                                <button id="resetBtn" type="button" class="btn btn-dark w-100" style="float: right;">
                                    <span class="align-middle">{{ __('category.RESET') }}</span>
                                </button>
                            </div>
                            <div class="col-lg-4 col-md-6 text-right">
                                <button id="filterBtn" type="button" class="btn bg-gradient-primary  w-100">
                                    <span class="align-middle">{{ __('category.FILTER') }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                             <button id="advance-filter-btn" type="button" class="btn bg-gradient-primary">
                                <span class="align-middle">Advance Filter</span>
                            </button>
                            <div class="table-responsive">
                                <table class="table datatables-ajax table-flush w-100" id="internal_report_datatable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ __('page.name') }}</th>
                                            <th>{{ __('page.email') }}</th>
                                            <th>{{ __('page.account_number') }}</th>
                                            <th>{{ __('page.platform') }}</th>
                                            <th>{{ __('page.method') }}</th>
                                            <th>{{ __('page.status') }}</th>
                                            <th>{{ __('page.date') }}</th>
                                            <th>{{ __('page.amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tfoot style="">
                                        <tr>
                                            <th colspan="7" style="text-align: right;" class="details-control"
                                                rowspan="1">{{ __('page.total') }} {{ __('page.amount') }} : </th>
                                            <th id="total_amount" rowspan="1" colspan="1">$0</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        var dt = $('#internal_report_datatable').DataTable({
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
                "url": "/user/reports/internal-transfer-report?op=data_table",
                "data": function(d) {
                    return $.extend({}, d, {
                        "from": $("#from").val(),
                        "to": $("#to").val(),
                        "min": $("#min").val(),
                        "max": $("#max").val(),
                        "type": $("#type").val(),
                        "approved_status": $("#approved_status").val(),
                        "account_number": $("#account_number").val(),
                        "info": $("#info").val(),
                    });
                }
            },

            "columns": [{
                    "data": "name"
                },
                {
                    "data": "email"
                },
                {
                    "data": "account_number"
                },
                {
                    "data": "platform"
                },
                {
                    "data": "method"
                },
                {
                    "data": "status"
                },
                {
                    "data": "date"
                },
                {
                    "data": "amount"
                },
            ],
            order: [
                [1, 'desc']
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
                $('#info').prop('selectedIndex', 0).trigger("change");
                dt.draw();
            });
        });
        
                $(document).on("click", "#advance-filter-btn", function() {
            $("#filter-form").toggleClass("d-none");
        });
    </script>
@endsection
