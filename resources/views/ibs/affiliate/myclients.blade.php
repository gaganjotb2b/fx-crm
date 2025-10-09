@php use App\Services\CombinedService; @endphp
@extends(App\Services\systems\VersionControllService::get_ib_layout())
@section('title','My Clients')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<style>
    .dt-buttons {
        position: absolute;
        right: 3rem;
    }

    input:focus {
        outline: none !important;
        border: 1px solid #d8d6de;
    }

    /* datatable style */
    #datatable-balance-transfer tr,
    #datatable-balance-transfer td:first-child {
        border-left: 3px solid var(--custom-primary);
    }

    #datatable-balance-transfer tr,
    #datatable-balance-transfer th:first-child {
        border-left: 3px solid;
    }

    #datatable-balance-transfer tr,
    #datatable-balance-transfer td {
        background-color: #f7fafc;
        vertical-align: middle;
    }

    #datatable-balance-transfer {
        border-collapse: separate !important;
        border-spacing: 2px 8px;
    }

    .dataTables_length {
        float: left;
    }

    div.dataTables_wrapper div.dataTables_length select {
        width: 160px;
        display: inline-block;
    }

    .dataTables_info {
        float: left;
        padding: 0;
        padding-top: 7px;
    }
</style>
@endsection
<!-- breadcrumb -->
@section('bread_crumb')
{!!App\Services\systems\BreadCrumbService::get_ib_breadcrumb()!!}
@stop
<!-- maincontent -->
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
                    <form id="filter-form" class="dt_adv_search" method="POST">
                        <div class="row g-2">
                            <div class="col-md-4 mb-3">
                                <select class="form-select" name="fiGroup" id="fiGroup">
                                    <option value="full_team" selected>{{ __('page.full team') }}</option>
                                    <option value="my_direct">{{ __('page.my direct') }}</option>
                                    <option value="my_team">{{ __('page.my team') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" name="trader_name_email" class="form-control" id="trader_name" placeholder="{{ __('page.trader_name') }} / Email">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" name="account_number" class="form-control" id="account_number" placeholder="Trading Account Number">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <select class="form-select" name="level" id="level">
                                    <option value="" disabled selected>Select Level</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                    <option value="4">Four</option>
                                    <option value="5">Five</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" name="sub_ib" class="form-control" id="sub_ib" placeholder="IB Name / Email / Phone">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <select class="form-select" name="deposit" id="deposit">
                                    <option value="" disabled selected>Select Deposit Type</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <select class="form-select" name="status" id="status">
                                    <option value="" disabled selected>Select Status Type</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <select class="form-select" name="kyc" id="kyc">
                                    <option value="" disabled selected>Kyc Verified</option>
                                    <option value="1">Verified</option>
                                    <option value="0">Unverified</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="col-12">
                                    <div class="col-12 input-rang-group">
                                        <span class="col-1 input-rang-group-date-logo rang-min">
                                            <i class="ni ni-calendar-grid-58"></i>
                                        </span>
                                        <input type="text" id="from" class="col-4 min flatpickr-basic" name="from" placeholder="Registration From">
                                        <span class="input-rang-group-text col-1">-</span>
                                        <input type="text" id="to" class="col-4 max flatpickr-basic" name="to" placeholder="Registration To">
                                        <span class="col-1 input-rang-group-date-logo rang-max">
                                            <i class="ni ni-calendar-grid-58"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <select class="form-select" name="platform" id="platform">
                                    <option value="" disabled selected>{{ __('page.Select Platform (ALL)') }}
                                    </option>
                                    <option value="MT4">{{ __('page.MT4') }}</option>
                                    <option value="MT5">{{ __('page.MT5') }}</option>
                                </select>
                            </div>


                            <div class="col-md-4">
                                <div class="col-12">
                                    <div class="col-md-12 text-right">
                                        <button id="btn-reset" type="button" class="btn btn-dark w-100 waves-effect waves-float waves-light" style="float: right;">
                                            <span class="align-middle">{{ __('page.RESET') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-12">
                                    <div class="col-md-12 text-right">
                                        <button id="btn-filter" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                            <span class="align-middle">{{ __('page.FILTER') }}</span>
                                        </button>
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
    {{-- START: Data table --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100 datatables-ajax" id="datatable-balance-transfer">
                            <thead>
                                <tr class="table-secondary">
                                    <th>{{ __('page.Name') }}</th>
                                    <th>{{ __('page.Email') }}</th>
                                    <th>{{ __('page.Country') }}</th>
                                    <th>{{ __('page.Registration Date') }}</th>
                                    <th>{{ __('page.Affiliate By') }}</th>
                                    <th>{{ __('page.Current Balance') }}</th>
                                    <th>{{ __('page.Total Deposit') }}</th>
                                    <th>{{ __('page.Total Withdraw') }}</th>
                                </tr>
                            </thead>
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

<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-picker-for-report-filter.js') }}"></script>
@endsection
@section('customjs')
<script>
    $(document).ready(function() {
        var trade_report = dt_fetch_data(
            '/ib/affiliates/my-clients',
            [{
                    "data": "name"
                },
                {
                    "data": "email"
                },
                {
                    "data": "country"
                },
                {
                    "data": "reg_date"
                },
                {
                    "data": "affiliate_by"
                },
                {
                    "data": "current_balance"
                },
                {
                    "data": "total_deposit"
                },
                {
                    "data": "total_withdraw"
                },
            ],
            true, false, true, [0, 1, 2, 3, 4, 5, 6, 7], '', true, true
        )
    });
</script>
@endsection