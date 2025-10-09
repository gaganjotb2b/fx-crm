@php use App\Services\CombinedService; @endphp
@extends(App\Services\systems\VersionControllService::get_ib_layout())
@section('title', 'Withdraw Report')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<style>
    .dt-buttons{
        float: right !important;
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
    {{-- START: Header + Filter --}}
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card ">
                <!-- Card header -->
                <div class="d-flex justify-content-between">
                    <div class="card-body">
                        <h5 class="mb-0">{{ __('page.filter') }} {{ __('page.reports') }}</h5>
                    </div>
                    <div class="p-4 border-bottom border-0">
                        <div class="btn-exports" style="width:160px">
                            <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                <option value="download" selected>Export to</option>
                                <option value="csv">CSV</option>
                                <option value="excel">Excel</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="filterForm" class="dt_adv_search" method="POST">
                        <div class="row g-2">
                            <div class="col-lg-4 col-md-6 mb-3">
                                <select class="form-select" name="status" id="status">
                                    <option value="" selected>{{ __('page.all') }}</option>
                                    <option value="A">{{ __('page.approved') }}</option>
                                    <option value="P">{{ __('page.pending') }}</option>
                                    <option value="D">{{ __('page.declined') }}</option>
                                </select>
                            </div>
                            <!-- filter by ib name/email/phone -->
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="">
                                    <input type="text" name="ib_info" class="form-control" id="ib_info" placeholder="IB Name / Email /phone">
                                </div>
                            </div>
                            <!-- filter by trader name/email/phone -->
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="">
                                    <input type="text" name="trader_info" class="form-control" id="trader_info" placeholder="Trader Name / Email /phone">
                                </div>
                            </div>
                            <!-- filter by account number -->
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="">
                                    <input type="text" name="account_number" class="form-control" id="account_number" placeholder="Account Number">
                                </div>
                            </div>
                            <!-- filter by platform -->
                            <div class="col-lg-4 col-md-6 mb-3">
                                <select class="form-select" name="withdraw_method" id="platform">
                                    <option value="" disabled selected>Withdraw Method (All)</option>
                                    <option value="bank">Bank Withdraw</option>
                                    <option value="crypto">Crypto Withdraw</option>
                                </select>
                            </div>
                            <!-- filter by affiliates -->
                            <div class="col-lg-4 col-md-6 mb-3">
                                <select class="form-select" name="fiGroup" id="fiGroup">
                                    <option value="full_team" selected>{{ __('page.full team') }}</option>
                                    <option value="my_direct">{{ __('page.my direct') }}</option>
                                    <option value="my_team">{{ __('page.my team') }}</option>
                                </select>
                            </div>
                            <!-- filter by min max -->
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="col-12">
                                    <div class="col-12 input-rang-group">
                                        <span class="col-2 input-rang-group-text rang-min">{{ __('page.MIN') }}</span>
                                        <input type="text" id="min" class="col-3 min" name="min">
                                        <span class="input-rang-group-text col-1">-</span>
                                        <input type="text" id="max" class="col-3 max" name="max">
                                        <span class="col-2 input-rang-group-text rang-max">{{ __('page.MAX') }}</span>
                                    </div>
                                </div>
                            </div>
                            <!-- filter by date -->
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="col-12">
                                    <div class="col-12 input-rang-group">
                                        <span class="col-1 input-rang-group-date-logo rang-min">
                                            <i class="ni ni-calendar-grid-58"></i>
                                        </span>
                                        <!-- date from -->
                                        <input type="text" id="from" class="col-4 min flatpickr-basic" name="from" placeholder="YY-MM-DD">
                                        <span class="input-rang-group-text col-1">-</span>
                                        <!-- date to -->
                                        <input type="text" id="to" class="col-4 max flatpickr-basic" name="to" placeholder="YY-MM-DD">
                                        <span class="col-1 input-rang-group-date-logo rang-max">
                                            <i class="ni ni-calendar-grid-58"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <!-- button filter reset -->
                                        <div class="col-md-12 text-right">
                                            <button id="resetBtn" type="button" class="btn btn-dark w-100 waves-effect waves-float waves-light" style="float: right;">
                                                <span class="align-middle">{{ __('page.RESET') }}</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <!-- button filter submit -->
                                        <div class="col-md-12 text-right">
                                            <button id="filterBtn" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{ __('page.FILTER') }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    {{-- END: Header + Filter --}}

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped w-100 datatables-ajax" id="datatable-withdraw">
                            <thead>
                                <tr class="table-secondary">
                                    <th>#</th>
                                    <th>{{ __('page.Name') }}</th>
                                    <th>{{ __('page.Email') }}</th>
                                    <th>{{ __('page.IB') }}</th>
                                    <th>{{ __('page.method') }}</th>
                                    <th>{{ __('page.Status') }}</th>
                                    <th>{{ __('page.Date') }}</th>
                                    <th>{{ __('page.Amount') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="table-secondary">

                                    <th colspan="7" class="text-end">{{ __('page.Total Amount') }}:</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    var withdraw_report = $("#datatable-withdraw").DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "lengthChange": true,
        "buttons": true,
        "dom": 'B<"clear">lfrtip',
        ajax: {
            url: "{{ url('ib/affiliates/clients-withdraw-report') }}?action=table",
            data: function(d) {
                return $.extend({}, d, {
                    "status": $("#status").val(),
                    "fiGroup": $("#fiGroup").val(),
                    "ib_info": $("#ib_info").val(),
                    "trader_info": $("#trader_info").val(),
                    "withdraw_method": $("#platform").val(),
                    "account_number": $("#account_number").val(),
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

                "data": "name"
            },
            {

                "data": "email"
            },
            {

                "data": "ib",
                "orderable": false
            },
            {

                "data": "transaction_type"
            },
            {

                "data": "approved_status"
            },
            {

                "data": "created_at"
            },
            {

                "data": "amount"
            }
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
            total = api.table().ajax.json().totalAmount;
            // Total over this page
            pageTotal = api
                .column(7, {
                    page: 'current'
                })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            // Update footer
            $(api.column(7).footer()).html('$' + pageTotal + ' ( $' + total + ' total)');
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
        // $(this).html(`<img src="{{ asset('trader-assets/assets/icon/puff.svg') }}" />`);
        withdraw_report.draw();
    });
    // click event for resetting filter form
    $('#resetBtn').click(function(e) {
        $('#filterForm')[0].reset();
        withdraw_report.draw();
    });
</script>
@endsection