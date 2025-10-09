@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Trading Account History')
@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
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
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.45;
            color: #6e6b7b;
            text-align: center;
            white-space: nowrap;
            background-color: #2d3357;
            border-top-left-radius: 0rem !important;
            border-bottom-left-radius: 0rem !important;
            border-right: none !important;
            border: 1px solid #2d3357 !important;
        }

        .rang-max {
            border-top-left-radius: 0rem !important;
            border-bottom-left-radius: 0rem !important;
        }

        .dark-version .input-rang-group-date-logo {
            display: flex;
            align-items: center;
            padding: 0.6rem 0.6rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.45;
            color: #6e6b7b;
            text-align: center;
            white-space: nowrap;
            background-color: #2d3357;
        }
    </style>
@endsection

@section('bread_crumb')
    <!-- bread crumb -->
    {!! App\Services\systems\BreadCrumbService::get_trader_breadcrumb() !!}
@stop
@section('content')
    <div class="container-fluid py-4">
        <div class="custom-height-con">
            <div class="card d-none" id="filter-form">
                <div class="card-body">
                    <!-- Card header -->
                    <div class="d-flex justify-content-between flex-row">
                        <div class="dsdsds">
                            <h5 class="mb-0">{{ __('page.filter') }} {{ __('page.reports') }}</h5>
                        </div>
                        <div class=" border-bottom border-0">
                            <div class="btn-exports" style="width:200px">
                                <select data-placeholder="Select a state..." class="form-select btExport" id="fx-export">
                                    <option value="" selected>{{ __('page.export_to') }}</option>
                                    <option value="csv">CSV</option>
                                    <option value="excel">Excel</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <form id="filter-form" class="dt_adv_search" method="POST">
                        @csrf
                        <div class="row g-1 mb-md-1 my-3">
                            <div class="col-lg-4 col-md-6 mb-3">
                                <select class="form-select choice-material" name="symbol" id="symbol">
                                    <optgroup label="Select Symbol">
                                        <option value="">{{ __('page.all') }}</option>
                                        @foreach ($symbols as $key => $symbol)
                                            <option value="{{ $symbol->SYMBOL }}">{{ $symbol->SYMBOL }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <input type="text" class="form-control dt-input dt-full-name" name="ticket"
                                    id="ticket" placeholder="{{ __('page.ticket') }}" />
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <input type="text" class="form-control dt-input dt-full-name" name="trade_account"
                                    id="trade_account" placeholder="{{ __('page.trading_account') }}" />
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="col-12">
                                    <div class="col-12 input-rang-group" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Search By Volume">
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
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="col-12">
                                    <div class="col-12 input-rang-group" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Search By Create Date">
                                        <span class="col-1 input-rang-group-date-logo rang-min">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-calendar">
                                                <rect x="3" y="4" width="18" height="18" rx="2"
                                                    ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
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
                            <div class="col-lg-4 col-md-6">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 text-right">
                                        <button id="btn-reset" type="button" class="btn btn-dark w-100"
                                            style="float: right;padding: 0.64rem;">
                                            <span class="align-middle">{{ __('category.RESET') }}</span>
                                        </button>
                                    </div>
                                    <div class="col-lg-6 col-md-6 text-right">
                                        <button id="btn-filter" type="button" class="btn bg-gradient-primary  w-100"
                                            style="padding: 0.64rem;">
                                            <span class="align-middle">{{ __('category.FILTER') }}</span>
                                        </button>
                                    </div>
                                </div>
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
                             <a type="button" class="btn bg-gradient-primary" href="{{url('user/tournament/dashboard')}}">
                                <span class="align-middle">Go Back To Tournament</span>
                            </a>
                            <div class="table-responsive">
                                <table class="table table-flush datatables-ajax w-100" id="trading_report_datatable">
                                    <thead class="thead-light cell-border compact stripe">
                                        <tr>
                                            <th>{{ __('page.ticket') }}</th>
                                            <th>{{ __('page.account') }}</th>
                                            <th>{{ __('page.open_time') }}</th>
                                            <th>{{ __('page.close_time') }}</th>
                                            <th>{{ __('page.symbol') }}</th>
                                            <th>{{ __('page.open_price') }}</th>
                                            <th>{{ __('page.close_price') }}</th>
                                            <th>{{ __('page.profit') }}</th>
                                            <th>{{ __('page.volume') }}</th>
                                        </tr>
                                    </thead>
                                    <tfoot style="">
                                        <tr>
                                            <th colspan="7" style="text-align: right;" class="details-control"
                                                rowspan="1">{{ __('page.total') }}: </th>
                                            <th rowspan="1" colspan="1" id="total_1">$0.00</th>
                                            <th rowspan="1" colspan="1" id="total_2"></th>
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

    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Extract account number from the URL
            var pathParts = window.location.pathname.split('/');
            var account_number = pathParts[pathParts.length - 1]; // Gets the last part: 2101070
            var group_id = pathParts[pathParts.length - 2]; // Gets the last part: 2101070
        
            var trade_report = dt_fetch_data(
                '/user/tournament/trading-account-history/' + account_number + '/' + group_id + '?op=data_table',
                [{
                        "data": "ticket"
                    },
                    {
                        "data": "account"
                    },
                    {
                        "data": "open_time"
                    },
                    {
                        "data": "close_time"
                    },
                    {
                        "data": "symbol"
                    },
                    {
                        "data": "open_price"
                    },
                    {
                        "data": "close_price"
                    },
                    {
                        "data": "profit"
                    },
                    {
                        "data": "volume"
                    },
                ],
                true, false, true, [0, 1, 2, 3, 4, 5, 6, 7, 8], 2, true, true
            )
        });
        
        
        $(document).on("click", "#advance-filter-btn", function() {
            $("#filter-form").toggleClass("d-none");
        });
    </script>
@endsection
