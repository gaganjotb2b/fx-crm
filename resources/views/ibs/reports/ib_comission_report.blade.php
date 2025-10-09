@extends(App\Services\systems\VersionControllService::get_ib_layout())
@section('title','Trade Commission Report')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<style>
    #datatable-balance-transfer tr,
    #datatable-balance-transfer td:first-child {
        border-left: 3px solid #D1B970;
    }

    #datatable-balance-transfer tr,
    #datatable-balance-transfer th:first-child {
        border-left: 3px solid;
    }

    #datatable-balance-transfer tr,
    #datatable-balance-transfer td {
        /*background-color: #f7fafc !important;*/
        vertical-align: middle;
    }

    #datatable-balance-transfer tr,
    #datatable-balance-transfer td {
        background-color: #e9e9e9;
        vertical-align: middle;
    }

    #datatable-balance-transfer {
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

    input:focus {
        outline: none !important;
        border: 1px solid #d8d6de;
    }

    input.form-control.form-control-sm {
        display: none !important;
    }

    .form-select-sm {
        font-size: 17px !important;
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

    .dt-buttons{
        float: right;
    }
</style>
@endsection
<!-- breadcrumb -->
@section('bread_crumb')
{!!App\Services\systems\BreadCrumbService::get_ib_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid py-4">
    <div class="custom-height-con">
        {{-- START: Header + Filter --}}
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card d-none" id="filter-form">
                    <!-- Card header -->
                    <div class="d-flex justify-content-between flex-row">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('page.ib_commission_report') }}</h5>
                            <p class="text-sm mb-0">
                                {{ __('page.all_reports_for_ib_comission_in_details') }}
                            </p>
                        </div>
                        <div class="btn-exports" style="width:160px; margin-top:20px; margin-right:30px">
                            <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                    <option value="download" data-icon="download" selected>
                                        {{ __('ad-reports.export') }}
                                    </option>
                                    <option value="csv" data-icon="file">CSV</option>
                                    <option value="excel" data-icon="file">EXCEL</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="filterForm" class="dt_adv_search" method="POST">
                            <div class="row g-2">
                                <div class="col-lg-4 col-md-6 mb-3" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Search By Group">
                                    <!-- ib groups filter input -->
                                    <select class="form-select" name="ibg" id="ibg">
                                        <option value="" selected>{{ __('page.all_groups') }}
                                        </option>
                                        @foreach ($ib_groups as $ib_group)
                                        <option value="{{ $ib_group->id }}">{{ $ib_group->group_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- filter by sub ib name/email/phone -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="">
                                        <input type="text" name="ib_info" class="form-control" id="ib_info" placeholder="Sub IB Name / Email /phone">
                                    </div>
                                </div>
                                <!-- filter by trader name/email/phone -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="">
                                        <input type="text" name="trader_info" class="form-control" id="trader_info" placeholder="Trader Name / Email /phone">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Search By Account Number">
                                    <div class="">
                                        <input type="text" name="trading_account" class="form-control" id="trading_account" placeholder="{{ __('page.Trading Account Number') }}">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Search By Order Number">
                                    <div class="">
                                        <input type="text" name="order_number" class="form-control" id="order_number" placeholder="{{ __('page.order_number') }}">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 mb-3" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Search By Symbol">
                                    <select class="form-select" name="symbol" id="symbol">
                                        <option value="" selected>{{ __('page.all_symbols') }}</option>
                                        @foreach ($symbols as $symbol)
                                        @if($symbol->SYMBOL != "")
                                        <option value="{{ $symbol->SYMBOL }}">{{ $symbol->SYMBOL }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Filter By IB Level -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <select class="form-select" name="level" id="level">
                                        <option value="" selected>{{ __('page.select ib level') }}</option>
                                        <option value="mdt">{{ __('page.my direct traders') }}</option>
                                        <option value="msib">{{ __('page.my sub ib') }}</option>
                                    </select>
                                </div>
                                <!-- Filter By level -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <select class="form-select" name="level_2" id="level_2">
                                        <option value="" disabled selected>Select Level</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                        <option value="4">Four</option>
                                        <option value="5">Five</option>
                                    </select>
                                </div>
                                <!-- Filter By Group -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <select class="form-select" name="account_group" id="account_group">
                                        <option value="" selected>Group</option>
                                        <option value="ECN">ECN</option>
                                        <option value="STANDARD">STANDARD</option>
                                        <option value="VIP">VIP</option>
                                    </select>
                                </div>
                                <!-- Filter By Status -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <select class="form-select" name="status" id="status">
                                        <option value="" selected>{{ __('page.all') }}</option>
                                        <option value="A">{{ __('page.approved') }}</option>
                                        <option value="P">{{ __('page.pending') }}</option>
                                        <option value="D">{{ __('page.declined') }}</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Search By Amount">
                                    <div class="col-12">
                                        <div class="col-12 input-rang-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Amount">
                                            <span class="col-2 input-rang-group-text rang-min">{{ __('page.MIN') }}</span>
                                            <input type="text" id="min" class="col-3 min" name="min">
                                            <span class="input-rang-group-text col-1">-</span>
                                            <input type="text" id="max" class="col-3 max" name="max">
                                            <span class="col-2 input-rang-group-text rang-max">{{ __('page.MAX') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Search By Date">
                                    <div class="col-12">
                                        <div class="col-12 input-rang-group">
                                            <span class="col-1 input-rang-group-date-logo rang-min">
                                                <i class="ni ni-calendar-grid-58"></i>
                                            </span>
                                            <input type="text" id="from" class="col-4 min flatpickr-basic" name="from" placeholder="YY-MM-DD">
                                            <span class="input-rang-group-text col-1">-</span>
                                            <input type="text" id="to" class="col-4 max flatpickr-basic" name="to" placeholder="YY-MM-DD">
                                            <span class="col-1 input-rang-group-date-logo rang-max">
                                                <i class="ni ni-calendar-grid-58"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-12"></div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="col-md-12 text-right p-0 m-0">
                                        <button id="resetBtn" type="button" class="btn btn-dark w-100" style="float: right;">
                                            <span class="align-middle">{{ __('page.RESET') }}</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="col-md-12 text-right">
                                        <button id="filterBtn" type="button" class="btn btn-primary w-100">
                                            {{ __('page.FILTER') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        {{-- END: Header + Filter --}}

        {{-- START: Data table --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <button id="advance-filter-btn" type="button" class="btn bg-gradient-primary">
                            <span class="align-middle">Advance Filter</span>
                        </button>
                        <div class="table-responsive">
                            <table class="table datatables-ajax w-100" id="datatable-balance-transfer">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('page.order_number') }}</th>
                                        <th>{{ __('page.account-number') }}</th>
                                        <th>{{ __('page.group') }}</th>
                                        <th>{{ __('page.Open Time') }}</th>
                                        <th>{{ __('page.Close Time') }}</th>
                                        <th>{{ __('page.Trade Type') }}</th>
                                        <th>{{ __('page.Symbol') }}</th>
                                        <th>{{ __('page.Level') }}</th>
                                        <th>{{ __('page.Volume') }}</th>
                                        <th>{{ __('page.Amount') }}</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>

                                        <th colspan="9" class="text-end">Total:</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- END: Data table --}}
    </div>
    @include('layouts.footer')
</div>
@endsection
@section('corejs')
<script type="text/javascript" src="{{ asset('trader-assets/assets/js/datatables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>



<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>

<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>

<script>
    var balanceTransferDT = $("#datatable-balance-transfer").DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "lengthChange": true,
        "buttons": true,
        "dom": 'B<"clear">lfrtip',
        ajax: {
            url: "{{ url('ib/reports/ib-comission') }}?action=table",
            data: function(d) {
                return $.extend({}, d, {
                    "level": $("#level").val(),
                    "level_2": $("#level_2").val(),
                    "ibg": $("#ibg").val(),
                    "ib_info": $("#ib_info").val(),
                    "trader_info": $("#trader_info").val(),
                    "account_group": $("#account_group").val(),
                    "symbol": $("#symbol").val(),
                    "trading_account": $("#trading_account").val(),
                    "order_number": $("#order_number").val(),
                    "min": $("#min").val(),
                    "max": $("#max").val(),
                    "from": $("#from").val(),
                    "to": $("#to").val(),
                });

            }
        },
        columns: [{

                "data": "id",
                "visible": false
            },
            {

                "data": "order_num"
            },
            {

                "data": "trading_account"
            },
            {

                "data": "ib_group"
            },
            {

                "data": "open_time"
            },
            {

                "data": "close_time"
            },
            {

                "data": "cmd"
            },
            {

                "data": "symbol"
            },
            {

                "data": "com_level"
            },
            {

                "data": "volume"
            },
            {

                "data": "amount"
            },
        ],
        buttons: [{
                extend: 'csv',
                text: 'csv',
                className: 'btn btn-success btn-sm',
                action: serverSideButtonAction
            },
            {
                extend: 'copy',
                text: 'copy',
                className: 'btn btn-warning btn-sm',
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
        order: [
            [2, 'desc']
        ],
        oLanguage: {
            "sLengthMenu": "\_MENU_",
            "sSearch": ""
        },
        language: {
            paginate: {
                previous: "<",
                next: ">",
            },
        },
        footerCallback: function(row, data, start, end, display) {
            var api = this.api();
            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i :
                    0;
            };
            // Total over all pages
            totalAmount = api.table().ajax.json().totalAmount;
            totalVolume = api.table().ajax.json().totalVolume;
            // Total over this page
            pageAmount = api
                .column(10, {
                    page: 'current'
                })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            pageVolume = api
                .column(9, {
                    page: 'current'
                })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            // Update footer
            // $(api.column(9).footer()).html(totalVolume);
            // $(api.column(10).footer()).html('$' + totalAmount);
            $(api.column(9).footer()).html(pageVolume);
            $(api.column(10).footer()).html('$' + pageAmount);
            // $(api.column(9).footer()).html('$' + pageVolume + ' ( $' + totalVolume + ' total)');
            // $(api.column(10).footer()).html('$' + pageAmount + ' ( $' + totalAmount + ' total)');
        },
        drawCallback: function(settings) {
            $("#filterBtn").html("FILTER");
        }
    });
    // data table export function --------------------------------------
    $(document).on("change", "#fx-export", function() {
        if ($(this).val() === 'csv') {
            $(".buttons-csv").trigger('click');
        }
        if ($(this).val() === 'excel') {
            console.log($(this).val());

            $(".buttons-excel").trigger('click');
        }
    });
    // filter button click event for filtering in data table
    $('#filterBtn').click(function(e) {
        $(this).html(`<img src="{{ asset('trader-assets/assets/icon/puff.svg') }}" />`);
        balanceTransferDT.draw();
    });
    // click event for resetting filter form
    $('#resetBtn').click(function(e) {
        $('#filterForm')[0].reset();
        $('#level').prop('selectedIndex', 0).trigger("change");
        $('#mtg').prop('selectedIndex', 0).trigger("change");
        $('#symbol').prop('selectedIndex', 0).trigger("change");
        balanceTransferDT.draw();
    });
    
        $(document).on("click", "#advance-filter-btn", function() {
            $("#filter-form").toggleClass("d-none");
        });
</script>
@endsection