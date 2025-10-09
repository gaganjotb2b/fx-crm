@php use App\Services\CombinedService; @endphp
@extends(App\Services\systems\VersionControllService::get_ib_layout())
@section('title','Balance Transfer Report to Trader')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
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

    .btn-check:focus+.btn-primary,
    .btn-primary:focus {
        /* color: #fff; */
        background-color: var(--custom-primary);
        border-color: unset !important;
        box-shadow: unset !important;
    }
</style>
@endsection
<!-- breadcrum -->
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
                            {{ __('page.all_reports_for_ib_balance') }}
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
                    <form id="filter-form" class="dt_adv_search" method="POST">
                        <div class="row g-2">
                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Search By IB Email">
                                <div class="form-group">
                                    <!-- filter by receiver email / name -->
                                    <input type="text" name="receiver_info" class="form-control" id="receiver-info" placeholder="Receiver name / email">
                                </div>
                            </div>

                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Search By Status">
                                <!-- filter by approved status -->
                                <select class="form-select" name="status" id="status">
                                    <option value="">{{ __('page.all') }}</option>
                                    <option value="A">{{ __('page.approved') }}</option>
                                    <option value="P">{{ __('page.pending') }}</option>
                                    <option value="D">{{ __('page.declined') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Search by transaction type">
                                <!-- filter by transaction type -->
                                <select class="form-select" name="transaction_type" id="transaction-type">
                                    <option value="">{{ __('page.all') }}</option>
                                    <option value="send">Send</option>
                                    <option value="receive">Receive</option>
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

                            <div class="col-md-2 ms-auto">
                                <div class="col-md-12 text-right" style="float: left; padding: 0 0.25rem;">
                                    <button id="btn-reset" type="button" class="btn btn-dark w-100 waves-effect waves-float waves-light" style="float: right;">
                                        <span class="align-middle">{{ __('page.RESET') }}</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="col-md-12 text-right" style="float: left; ">
                                    <button id="btn-filter" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
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
                            <thead class="thead-light">
                                <tr>
                                    <th>Receiver name</th>
                                    <th>Receiver email</th>
                                    <th>{{ __('page.type') }}</th>
                                    <th>{{ __('page.status') }}</th>
                                    <th>{{ __('page.date') }}</th>
                                    <th>{{ __('page.charge') }}</th>
                                    <th>{{ __('page.amount') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-end">{{ __('page.total_amount') }}:</th>
                                    <th id="total_1"></th>
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
    const balanceTransferDT = $("#datatable-balance-transfer").fetch_data({
        url: "/ib/reports/balance-transfer-ib-to-trader?action=table",
        columns: [{

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
        icon_feather: false,
        total_sum: 1,
        o_Language: true,
    });
</script>
@endsection