@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title','IB Transfer Report')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
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
        padding: 0.7rem 1rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.45;
        color: #6e6b7b;
        text-align: center;
        white-space: nowrap;
        background-color: #fff;
        border: 1px solid #d8d6de;
    }
</style>
@endsection

@section('bread_crumb')
<!-- bread crumb -->
{!!App\Services\systems\BreadCrumbService::get_trader_breadcrumb()!!}
@stop
@section('content')
<div class="container-fluid py-4">
    <div class="custom-height-con">
        <div class="card ">
            <div class="card-body">
                <!-- Card header -->
                <div class="d-flex justify-content-between flex-row">
                    <div class="dsddfe">
                        <h5 class="mb-0">{{__('page.filter')}} {{ __('page.reports') }}</h5>
                    </div>

                    <div class=" border-bottom border-0">
                        <div class="btn-exports" style="width:200px">
                            <select data-placeholder="Select a state..." class="form-select btExport" id="fx-export">
                                <option value="download" selected>{{__('page.export_to')}}</option>
                                <option value="csv">CSV</option>
                                <option value="excel">Excel</option>
                            </select>
                        </div>
                    </div>
                </div>
                <form id="filter-form" class="dt_adv_search" method="POST">
                    @csrf
                    <div class="row g-1 mb-md-1 my-3">
                        <div class="col-md-4 my-3">
                            <select class="form-select choice-material" name="status" id="status">
                                <optgroup label="Select Symbol">
                                    <option value="">{{__('page.all')}}</option>
                                    <option value="A">{{__('ad-reports.approved')}}</option>
                                    <option value="P">{{__('page.pending')}}</option>
                                    <option value="D">{{__('ad-reports.declined')}}</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="col-md-4 my-3">
                            <div class="col-12">
                                <div class="col-12 input-rang-group">
                                    <span class="col-2 input-rang-group-text rang-min">{{__('ad-reports.min')}}</span>
                                    <input type="text" id="min" class="col-3 min" name="min">
                                    <span class="input-rang-group-text col-1">-</span>
                                    <input type="text" id="max" class="col-3 bg-body max" name="max">
                                    <span class="col-2 input-rang-group-text rang-max">{{__('ad-reports.max')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 my-3">
                            <div class="col-12">
                                <div class="col-12 input-rang-group">
                                    <span class="col-1 input-rang-group-date-logo rang-min">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                    </span>
                                    <input type="text" id="from" class="col-4 min flatpickr-basic" name="from" placeholder="YY-MM-DD">
                                    <span class="input-rang-group-text col-1">-</span>
                                    <input type="text" id="to" class="col-4 max flatpickr-basic" name="to" placeholder="YY-MM-DD">
                                    <span class="col-1 input-rang-group-date-logo rang-max" style="border-top-left-radius: 0rem !important;border-bottom-left-radius: 0rem !important;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-1 mb-md-1 my-1">
                        <div class="col-md-4" style="float: left;">&nbsp;</div>
                        <div class="col-md-4 text-right">
                            <button id="btn-reset" type="button" class="btn btn-dark w-100" style="float: right;">
                                <span class="align-middle">{{__('category.RESET')}}</span>
                            </button>
                        </div>
                        <div class="col-md-4 text-right">
                            <button id="btn-filter" type="button" class="btn bg-gradient-primary  w-100">
                                <span class="align-middle">{{__('category.FILTER')}}</span>
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
                        <div class="table-responsive">
                            <table class="table table-flush datatables-ajax w-100" id="trading_report_datatable">
                                <thead class="thead-light cell-border compact stripe">
                                    <tr>
                                        <th>IB {{__('page.name')}}</th>
                                        <th>IB {{__('page.email')}}</th>
                                        <th>{{__('page.type')}}</th>
                                        <th>{{__('page.status')}}</th>
                                        <th>{{__('ad-reports.charge')}}</th>
                                        <th>{{__('page.date')}}</th>
                                        <th>{{__('page.amount')}}</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" style="text-align: right;" class="details-control" rowspan="1">{{__('page.total')}}: </th>
                                        <th rowspan="1" colspan="1" id="total_1">$0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
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
    $(document).ready(function() {
        var trade_report = dt_fetch_data(
            '/user/reports/ib-transfer-report?op=data_table',
            [{
                    "data": "ib_name"
                },
                {
                    "data": "ib_email"
                },
                {
                    "data": "type"
                },
                {
                    "data": "status"
                },
                {
                    "data": "charge"
                },
                {
                    "data": "date"
                },
                {
                    "data": "amount"
                },

            ],
            true, false, true, [1, 2, 3, 4], 2, true, true
        )
    });
</script>
@endsection