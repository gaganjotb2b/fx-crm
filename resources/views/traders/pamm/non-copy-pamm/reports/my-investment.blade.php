@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'My Investment')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
    #deposit_report_datatable tr,
    #deposit_report_datatable td:first-child {
        border-left: 3px solid #4fd1c5;
    }

    #deposit_report_datatable tr,
    #deposit_report_datatable th:first-child {
        border-left: 3px solid;
    }

    #deposit_report_datatable tr,
    #deposit_report_datatable td {
        background-color: #f7fafc;
        vertical-align: middle;
        text-align: left;
    }

    #deposit_report_datatable {
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

    .form-select.form-select-sm {
        display: block;
        width: 100%;
        padding-right: 0.5rem 2rem 0.5rem 0.75rem !important;
        -moz-padding-start: calc(0.75rem - 3px);
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1.4rem;
        color: #495057;
        background-color: #fff;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        border: 1px solid #d2d6da;
        border-radius: 0.5rem;
        transition: box-shadow 0.15s ease, border-color 0.15s ease;
        appearance: none;
    }

    .ps__rail-x {
        display: none !important;
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

    .dark-version .col-1.input-rang-group-date-logo.rang-max.input-range-gpr-right {
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

    .input-rang-group-date-logo {
        border: 1px solid #d8d6de !important;
    }
</style>
@if (App\Services\systems\VersionControllService::check_version() === 'lite')
<style>
    .dt-buttons .buttons-csv,
    .dt-buttons .buttons-excel,
    .dt-buttons .buttons-copy {
        display: none;
    }
</style>
@endif
@stop
@section('bread_crumb')
<!-- bread crumb -->
{!! App\Services\systems\BreadCrumbService::get_trader_breadcrumb() !!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid py-4">
    <div class="custom-height-con">
        <div class="card">
            <div class="card-body">
                <!-- Card header -->
                <div class="d-flex justify-content-between">
                    <div class="">
                        <h5 class="mb-0">{{ __('page.filter') }} {{ __('page.reports') }}</h5>
                    </div>

                    <div class="border-bottom border-0">
                        <div class="btn-exports" style="width:200px">
                            <select data-placeholder="Select a state..." class="form-select btExport" id="fx-export">
                                <option value="download" selected>{{ __('page.export_to') }}</option>
                                <option value="csv">CSV</option>
                                <option value="excel">Excel</option>
                            </select>
                        </div>
                    </div>
                </div>

                <form id="filter-form" class="dt_adv_search" method="POST">
                    @csrf
                    <div class="row gy-2 mb-md-1 my-3">
                        <!-- filter by investor name,email,phone -->
                        <div class="col-lg-4 col-md-6 mb-1 p-1">
                            <input type="text" class="form-control" name="investor_info" id="investor_email" placeholder="investor email/name/account">
                        </div>
                        <div class="col-lg-4 col-md-6 mb-1 p-1">
                            <select class="form-select choice-colors" name="status" id="approved_status">
                                <optgroup label="Search By Status">
                                    <option value="">{{ __('page.all') }}</option>
                                    <option value="A">{{ __('ad-reports.approved') }}</option>
                                    <option value="P">{{ __('page.pending') }}</option>
                                    <option value="D">{{ __('ad-reports.declined') }}</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 p-1">
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
                        <div class="col-lg-4 col-md-6 p-1">
                            <div class="col-12">
                                <div class="col-12 input-rang-group">
                                    <span class="input-rang-group-date-logo rang-min">
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
                                    <input type="text" id="from" class="col-4 min datepicker h-100 w-100" name="from" placeholder="YY-MM-DD">
                                    <span class="input-rang-group-text col-1">-</span>
                                    <input type="text" id="to" class="col-4 max datepicker h-100 w-100" name="to" placeholder="YY-MM-DD">
                                    <span class="input-rang-group-date-logo rang-max"
                                        style="border-top-left-radius: 0rem !important;border-bottom-left-radius: 0rem !important;">
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
                        <div class="col-lg-4 col-md-6 p-1">
                            <div class="col-md-12 text-right">
                                <button id="btn-reset" type="button" class="btn btn-dark w-100"
                                    style="float: right;">
                                    <span class="align-middle">{{ __('category.RESET') }}</span>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 p-1">
                            <div class="col-md-12 text-right">
                                <button id="btn-filter" type="button" class="btn bg-gradient-primary  w-100">
                                    <span class="align-middle">{{ __('category.FILTER') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-flush datatables-ajax w-100 text-center" id="deposit_report_datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('Account') }}</th>
                                <th>{{ __('PAMM User') }}</th>
                                <th>{{ __('PAMM Email') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('page.status') }}</th>
                                <th>{{ __('ad-reports.charge') }}</th>
                                <th>{{ __('page.amount') }}</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="6" style="text-align: right;" class="details-control" rowspan="1">{{ __('ad-reports.total-amount') }} : </th>
                                <th class="text-left" id="total_1" rowspan="1" colspan="1">$0</th>
                            </tr>
                        </tfoot>
                    </table>
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
<script src="{{ asset('trader-assets/assets/js/plugins/datatables.js') }}"></script>
<script type="text/javascript" src="{{ asset('trader-assets/assets/js/datatables.min.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/plugins/flatpickr.min.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>

<!-- Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<!-- Excel Export JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- PDF Export JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<!-- Buttons HTML5 Export JS -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<!-- Buttons Print JS -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script>
    var investment_report = $("#deposit_report_datatable").DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/user/investor-report/my-investment/data",
            "data": function(d) {
                return $.extend({}, d, $("#filter-form").serializeObject());
            }
        },
        "buttons": true,
        "searching": true,
        "lengthChange": false,
        "dom": 'B<"clear">lfrtip',
        buttons: [{
                extend: 'csv',
                text: 'csv',
                className: 'btn btn-success btn-sm',
                action: serverSideButtonAction
            },
            {
                extend: 'excel',
                text: 'excel',
                className: 'btn btn-warning btn-sm',
                action: serverSideButtonAction
            },
        ],
        columns: [{
                "data": "account"
            },
            {
                data: "pamm_user"
            },
            {
                "data": "pamm_email"
            },
            {
                "data": "date"
            },
            {
                "data": "status",
                render: function(data, type, row, meta) {
                    $badge = 'badge-warning';
                    if (data === 'Approved') {
                        $badge = 'badge-success';
                    } else if (data === 'Declined') {
                        $badge === 'badge-danger';
                    }
                    return `
                        <span class="badge ${$badge}">${data}</span>
                    `;
                    return data;
                }
            },
            {
                "data": "charge"
            },
            {
                "data": "amount"
            },
        ],

        footerCallback: function(row, data, start, end, display) {
            var api = this.api();
            var json = api.ajax.json();
            var totalAmount = json.totalAmount;
            $(api.column(5).footer()).html(
                'Total: '
            );
            $(api.column(6).footer()).html(
                `$${totalAmount}`
            );
        },
        drawCallback: function(data) {
            $("#btn-filter").html('Filter');
            $("#btn-reset").html('Reset');
        },
        "language": {
            "paginate": {
                "previous": "<i class='fas fa-chevron-left'></i>", // Custom previous icon
                "next": "<i class='fas fa-chevron-right'></i>" // Custom next icon
            }
        }
    });
    // datatable export function
    $(document).on("change", "#fx-export", function() {
        if ($(this).val() === 'csv') {
            console.log('this')
            $(".buttons-csv").trigger('click');
        }
        if ($(this).val() === 'excel') {
            $(".buttons-excel").trigger('click');
        }

    });
    // action to filter
    $(document).on("click", "#btn-filter", function() {
        const button_text = $(this).text();
        $(this).html('Processing.....')
        investment_report.draw();
    });
    $("#btn-reset").on("click", function(e) {
        $(this).html('Processing.....')
        $("#filter-form").find("select").val('').change();
        $(".start_date").val('');
        $(".end_date").val('');
        $("#filter-form").trigger('reset');
        $(".select2").val("").trigger('change');
        investment_report.draw();
    });
    // Date picker
    if (document.querySelector('.datepicker')) {
        flatpickr('.datepicker', {
            // mode: "range"
            static: true
        });
    }
</script>
@endsection