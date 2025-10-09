@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Social Copy Trading Reports')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/pamm/mam.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/pamm/color.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<style>
    /* table.dataTable>thead .sorting::before,
    table.dataTable>thead .sorting::after,
    table.dataTable>thead .sorting_asc::before,
    table.dataTable>thead .sorting_asc::after,
    table.dataTable>thead .sorting_desc::before,
    table.dataTable>thead .sorting_desc::after,
    table.dataTable>thead .sorting_asc_disabled::before,
    table.dataTable>thead .sorting_asc_disabled::after,
    table.dataTable>thead .sorting_desc_disabled::before,
    table.dataTable>thead .sorting_desc_disabled::after {
        top: 11px;
    } */

    .app-content .card {
        margin: 0 !important;
    }

    .table-hover>tbody>tr:hover>* {
        --bs-table-accent-bg: var(--bs-table-hover-bg);
        color: #c2c4d1;
    }

    .table * {
        border-color: #7b8c9d !important;

    }

    h5 {
        color: #fff;
        border-left: 3px solid #fff !important;
        padding-left: 10px;
    }

    .datatables-ajax tr,
    .datatables-ajax td:first-child {
        border-left: 3px solid #2269f5 !important;
    }

    .datatables-ajax tr,
    .datatables-ajax th:first-child {
        border-left: 3px solid #fff !important;
    }

    .datatables-ajax tr,
    .datatables-ajax td {
        vertical-align: middle;
    }

    .datatables-ajax {
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

    table.dataTable {
        clear: both;
        margin-top: 6px !important;
        margin-bottom: 6px !important;
        max-width: none !important;
        border-collapse: separate !important;
        border-spacing: 2px 3px;
    }

    #datatables-ajax tr,
    #datatables-ajax td {
        background: var(--background-color) !important;
    }

    .dataTables_scrollBody {
        height: auto !important;
    }

    .table-column-width>th {
        width: 12.5% !important;
    }

    .odd>td {
        width: 12.5% !important;
    }

    .dark-version #date_to,
    .dark-version #date_from {
        width: auto !important;
    }

    input#date_from {
        margin-right: 1rem !important;
    }

    input#date_to {
        margin-left: 1rem !important;
    }

    .input-rang-group {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        width: auto !important;
    }

    table.dataTable tfoot th,
    table.dataTable thead th {
        color: inherit !important;
    }

    .select-dropdown select {
        font-size: inherit !important;
        font-weight: inherit !important;
        width: 100%;
        padding: 8px 24px 8px 10px;
        /* border: ivory; */
        /* background-color: transparent; */
        /* appearance: none; */
        /* border-radius: 5px; */
        /* border: inherit; */
        color: inherit !important;
    }
</style>
@stop
@section('bread_crumb')
<!-- bread crumb -->
{!!App\Services\systems\BreadCrumbService::get_trader_breadcrumb()!!}
@stop

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <div class="col p-0">
            <div class="card">
                <!-- Card header -->
                <div class="d-flex justify-content-between flex-row">
                    <div class="card-header">
                        <h5 class="p-0 mb-0 filter-title">Filter Report</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form id="filterForm" action="" class="dt_adv_search" method="POST">
                        @csrf
                        <input type="hidden" name="op" value="datatable_mt5">
                        <input type="hidden" name="server" value="mt5">
                        <div class="row ml-1 mr-1">
                            <div class="col-md-4 margin_bottom">
                                <div class="select-dropdown">
                                    <select id="by_time" name="by_time" class="form-select js-states" name="type" id="type">
                                        <optgroup label="Select Method">
                                            <option value="">Search By DATE TIME</option>
                                            <option value="">All</option>
                                            <option value="open_time">Open Time</option>
                                            <option value="close_time">Close Time</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-rang-group">
                                    <div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Amount">
                                        <input type="date" id="date_from" name="date_from" class="form-control" placeholder="Start Date" aria-label="Username">
                                        <span class="input-group-text">to</span>
                                        <input type="date" id="date_to" name="date_to" class="form-control" placeholder="End Date" aria-label="Server">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 margin_bottom">
                                <div class="form-group2 form-group-text">
                                    <input type="text" name="master_account" placeholder="MASTER ACCOUNT" id="master_account">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="select-dropdown">
                                    <select class="form-select js-states" name="symbol" id="symbol">
                                        <optgroup label="Select Method">
                                            <option value="">Search By Symbol</option>
                                            <?= copy_symbols() ?>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group2 form-group-text">
                                    <input type="text" name="copy_of" placeholder="COPY TICKET" id="copy_of">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="select-dropdown">
                                    <select class="form-select js-states" name="trade_account" id="trade_account">
                                        <optgroup label="Select Method">
                                            @foreach ($trading_account as $account)
                                            <option value="{{ $account->account_number }}">
                                                &nbsp;{{ $account->account_number }}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="select-dropdown">
                                    <select class="form-select js-states" name="cmd" id="cmd">
                                        <optgroup label="Select Method">
                                            <option value="">Search By Order Type</option>
                                            <option value="">All</option>
                                            <option value="0">Buy</option>
                                            <option value="1">Sell</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group2 form-group-text">
                                    <input type="text" name="ticket" placeholder="TICKET HERE" id="ticket">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class=" text-right w-100" style="float: left; ">
                                    <button id="filterBtn" type="button" class="btn custom-btn flex-grow-1 m-l-xxs datatable-button  w-100">
                                        <span class="align-middle">FILTER</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row  mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Total Trades</h4>
                    <p class="card-text ">
                        {{-- <span>$546</span> --}}
                        <strong class="amount counter ct_total_trades">0</strong>
                        <span class="text-primary">(<span class="counter ct_total_closed">0 </span> Closed)</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-left">
                <div class="card-body">
                    <h4 class="card-title">Total Volume</h4>
                    <p class="card-text">
                        <strong class="text-primary amount counter ct_total_volume">0</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-5">
        <div class="card text-left">
            <div class="card-body">
                <div class="tab-pane active ">
                    <div class="table-response">
                        <table class="table table-flush datatables-ajax w-100" id="datatables_ajax">
                            <thead>
                                <tr class="table-column-width">
                                    <th>Ticket</th>
                                    <th>Login</th>
                                    <th>Symbol</th>
                                    <th>Volume</th>
                                    <th>Open Time</th>
                                    <th>Close Time</th>
                                    <th>Profit</th>
                                    <th>Comment</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@stop
@section('page-js')
<script src="{{ asset('trader-assets/assets/js/common.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/filter.js') }}"></script>
</script>
<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>

<script>
    var dt;
    var total_trades = 0;
    var isnew = true;
    var columns = ['Order', "Login", "Symbol", "Profit", "OpenTime", "CloseTime", "Volume", "Comment"];
    $(document).ready(function() {
        $("#orver_loading").show();
        dt = $('#datatables_ajax').DataTable({
            serverSide: true,
            ordering: true,
            searching: false,
            lengthMenu: [10, 25, 50, 100],
            pageLength: 10,
            oLanguage: {
                "sLengthMenu": "\_MENU_",
                "sSearch": ""
            },
            language: {
                paginate: {
                    previous: "<<",
                    next: ">>",
                },
            },
            ajax: function(data, callback, settings) {


                $("#filterForm").submit(function(e) {
                    var oderBY = columns[data.order[0].column];
                    var oderDir = data.order[0].dir;

                    var postData = $(this).serializeArray();
                    postData.push({
                        name: 'start',
                        value: data.start
                    });
                    postData.push({
                        name: 'length',
                        value: data.length
                    });
                    postData.push({
                        name: 'isnew',
                        value: isnew
                    });
                    postData.push({
                        name: 'order',
                        value: oderBY
                    });
                    postData.push({
                        name: 'dir',
                        value: oderDir
                    });

                    // console.log(postData);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content')
                        }
                    });

                    $.ajax({
                        url: '/user/user-copy/social-traders-report-process',
                        dataType: 'json',
                        type: 'POST',
                        data: postData,
                        success: function(result) {
                            console.log(result);
                            if (isnew) {
                                $('.ct_total_volume').html(result.counter
                                    .total_volume);
                                $('.ct_total_trades').html(result.counter
                                    .total_trades);
                                $('.ct_total_closed').html(result.counter
                                    .total_closed);
                                total_trades = result.counter.total_trades;
                            }
                            callback({
                                draw: data.draw,
                                data: result.data,
                                recordsTotal: total_trades,
                                recordsFiltered: total_trades
                            });
                        },
                        error: function(e) {
                            console.log(e);
                        }
                    });
                    e.preventDefault(); //STOP default action
                    $(this).unbind();
                });
                $("#filterForm").submit(); //SUBMIT FORM
            },
            fixedColumns: true,
            fixedHeader: true,
            scrollX: true,
            scrollY: 350,
            scroller: {
                loadingIndicator: true
            },
            "drawCallback": function(settings) {
                isnew = false;
                $("#filterBtn").html("FILTER");
                $("#orver_loading").fadeOut();
            },
            "order": [
                [0, 'desc']
            ]
        });


        $('#filterBtn').click(function(e) {
            isnew = true;
            $(this).html("<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>");
            $("#orver_loading").show();
            dt.draw();
        });
    });

    $(document).ready(function() {
        $('#datatables_ajax_previous a').html('prev')
    })
</script>
@stop