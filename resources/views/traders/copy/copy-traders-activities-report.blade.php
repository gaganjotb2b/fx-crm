@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Social Copy Trading Activities Reports')
@section('page-css')
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/pamm/mam.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/pamm/color.css') }}" />
<style>
    table.dataTable>thead .sorting::before,
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
    }

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

    /* .datatables-ajax tr,
                        .datatables-ajax td {
                            background-color: #f7fafc;
                            vertical-align: middle;
                        } */

    .datatables-ajax tr,
    .datatables-ajax td {
        /* background-color: var(--background-color) !important; */
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

    .dark-version #date_to,
    .dark-version #date_from {
        width: auto !important;
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
        width: 20% !important;
    }

    .odd>td {
        width: 20% !important;
    }

    input#date_from {
        margin-right: 1rem !important;
    }

    input#date_to {
        margin-left: 1rem !important;
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
            <div class="card " style="padding: 10px 35px">
                <!-- Card header -->
                <div class="d-flex justify-content-between flex-row">
                    <div class="card-header">
                        <h5 class="p-0 mb-0 filter-title">Filter Report</h5>
                    </div>
                </div>
                <form id="filterForm" action="#" class="dt_adv_search" method="POST">
                    @csrf
                    <input type="hidden" name="op" value="datatable_mt5">
                    <input type="hidden" name="server" value="mt5">
                    <div class="row ml-1 mr-1">
                        <div class="col-md-4 margin_bottom">
                            <div class="select-dropdown">
                                <select class="form-select js-states" name="type" id="type">
                                    <optgroup label="Select Method">
                                        <option value="">All</option>
                                        <option value="mam">MAMM</option>
                                        <option value="pamm">PAMM</option>
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
                        <div class="col-md-4 mb-3">
                            <div class="select-dropdown">
                                <select class="form-select js-states" name="trade_account" id="trade_account">
                                    <optgroup label="Select Account">
                                        @foreach ($trading_account as $account)
                                        <option value="{{ $account->account_number }}">&nbsp;{{ $account->account_number }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 margin_bottom">
                            <div class="form-group2 form-group-text">
                                <input type="text" name="master_account" placeholder="MASTER ACCOUNT" id="master_account">
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="select-dropdown">
                                <select class="form-select js-states" name="status" id="status">
                                    <optgroup label="Select Method">
                                        <option value="">Search By Copy</option>
                                        <option value="copy">Copy</option>
                                        <option value="uncopy">Uncopy</option>
                                    </optgroup>
                                </select>
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
    <div class="mb-5">
        <div class="card text-left">
            <div class="card-body">
                <div class="tab-pane active" id="tabs-1" role="tabpanel">
                    <table class="table datatables-ajax table-hover w-100" id="datatables_ajax">
                        <thead>
                            <tr class="table-column-width">
                                <th>Master Account </th>
                                <th>Slave Account </th>
                                <th>Status</th>
                                <th>Type</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-js')


<script src="{{ asset('trader-assets/assets/js/common.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/filter.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
</script>
<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>

<script>
    var dt;
    var total_trades = 0;
    var isnew = true;
    var columns = ['master', "slave", "action", "type", "created_at"];
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



                    let typeValue = $('#type').val();
                    let master_account = $('#master_account').val();
                    let trade_account = $('#trade_account').val();
                    let date_from = $('#date_from').val();
                    let date_to = $('#date_to').val();
                    let status = $('#status').val();

                    postData.push({
                        name: 'start',
                        value: data.start
                    });
                    postData.push({
                        name: 'length',
                        value: data.length
                    });
                    postData.push({
                        name: 'order',
                        value: oderBY
                    });
                    postData.push({
                        name: 'dir',
                        value: oderDir
                    });
                    postData.push({
                        name: 'type',
                        value: typeValue
                    });
                    postData.push({
                        name: 'master_account',
                        value: master_account
                    });
                    postData.push({
                        name: 'trade_account',
                        value: trade_account
                    });
                    postData.push({
                        name: 'date_from',
                        value: date_from
                    });
                    postData.push({
                        name: 'date_to',
                        value: date_to
                    });
                    postData.push({
                        name: 'status',
                        value: status
                    });

                    $.ajax({
                        url: '/user/user-copy/traders-activities-report-process',
                        dataType: 'json',
                        method: 'POST',
                        data: postData,
                        success: function(result) {
                            // console.log(result);
                            // if (isnew) {


                            // }
                            callback({

                                draw: data.draw,
                                data: result.data,
                                recordsTotal: result.recordsTotal,
                                recordsFiltered: result.recordsTotal
                            });
                        },

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
                $("#filterBtn").html("Filter");
                $("#orver_loading").fadeOut();

            },
            "Order": [
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
</script>
@stop