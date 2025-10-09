@php use App\Services\CombinedService; @endphp
@extends(App\Services\systems\VersionControllService::get_ib_layout())
@section('title','Trader To IB Balance Transfer Report')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<style>
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
    {{-- START: Header + Filter --}}
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card ">
                <!-- Card header -->
                <div class="d-flex justify-content-between flex-row">
                    <div class="card-body">
                        <h5 class="mb-0">{{ __('page.filter') }} {{ __('page.reports') }}</h5>
                        <p class="text-sm mb-0">
                            {{ __('page.ib_in_details') }}
                        </p>
                    </div>

                    <div class="p-4 border-bottom border-0">
                        <div class="btn-exports" style="width:200px">
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
                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Search By IB Email">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" id="email" placeholder="IB Email">
                                </div>
                            </div>

                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Search By Status">
                                <select class="form-select" name="status" id="status">
                                    <option value="">{{ __('page.all') }}</option>
                                    <option value="A">{{ __('page.approved') }}</option>
                                    <option value="P">{{ __('page.pending') }}</option>
                                    <option value="D">{{ __('page.declined') }}</option>
                                </select>
                            </div>

                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Search By Amount">
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
                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Search By Date">
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

                            <div class="col-md-4">
                                <div class="col-md-12 text-right" style="float: left; padding: 0 0.25rem;">
                                    <button id="resetBtn" type="button" class="btn btn-dark w-100 waves-effect waves-float waves-light" style="float: right;">
                                        <span class="align-middle">{{ __('page.RESET') }}</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-md-12 text-right" style="float: left; ">
                                    <button id="filterBtn" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                        <span class="align-middle">{{ __('page.FILTER') }}</span>
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
                    <div class="table-responsive">
                        <table class="table table-flush datatables-ajax w-100" id="datatable-balance-transfer">
                            <thead>
                                <tr class="thead-light">
                                    <th>#</th>
                                    <th>{{ __('page.trader_name') }}</th>
                                    <th>{{ __('page.trader_email') }}</th>
                                    <th>{{ __('page.type') }}</th>
                                    <th>{{ __('page.status') }}</th>
                                    <th>{{ __('page.date') }}</th>
                                    <th>{{ __('page.charge') }}</th>
                                    <th>{{ __('page.amount') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="thead-light">
                                    <th colspan="7" class="text-end">{{ __('page.total_amount') }}:</th>
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
@endsection
@section('page-js')
<script type="text/javascript" src="{{ asset('trader-assets/assets/js/datatables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>

<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>

<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>

<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js')}}"></script>
<!-- <script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js')}}"></script> -->
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>
<script>
    const balanceTransferDT = $("#datatable-balance-transfer").DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "lengthChange": true,
        "buttons": true,
        "dom": 'B<"clear">lfrtip',
        ajax: {
            url: "{{ url('ib/reports/balance-transfer-trader-to-ib') }}?action=table",
            data: function(d) {
                return $.extend({}, d, {
                    "email": $("#email").val(),
                    "status": $("#status").val(),
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

                "data": "type"
            },
            {

                "data": "status"
            },
            {

                "data": "created_at"
            },
            {

                "data": "charge"
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
        $(this).html(`<img src="{{ asset('trader-assets/assets/icon/puff.svg') }}" />`);
        balanceTransferDT.draw();
    });
    // click event for resetting filter form
    $('#resetBtn').click(function(e) {
        $('#filterForm')[0].reset();
        balanceTransferDT.draw();
    });
</script>
@endsection