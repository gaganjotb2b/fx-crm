@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'IB Withdraw Report')
@section('vendor-css')

    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">

    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <!-- datatable -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">


    <style>
        /* for Laptop */
        @media screen and (max-width: 1280px) and (min-width: 800px) {

            .ib-withdraw thead tr th:nth-child(7),
            .ib-withdraw tbody tr td:nth-child(7) {
                display: none;
            }

            .small-none {
                display: none;
            }
        }




        @media screen and (max-width: 1440px) and (min-width: 900px) {

            .ib-withdraw thead tr th:nth-child(2),
            .ib-withdraw tbody tr td:nth-child(2) {
                display: none;
            }

            .small-none {
                display: none;
            }
        }

        .input-group-text {
            display: flex;
            align-items: center;
            padding: 0rem 1rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.45;
            color: #6e6b7b;
            text-align: center;
            white-space: nowrap;
            background-color: #fff;
            border: 1px solid #d8d6de;
            border-radius: 0.357rem;
        }
    </style>
@stop
<!-- END: page css -->
{{-- custom css --}}
@section('custom-css')
    <style>
        .dataTables_length {
            float: left;
        }
    </style>
@stop
<!-- BEGIN: content -->
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-fluid p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">IB Withdraw</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('admin.dashboard') }}">{{ __('admin-breadcrumbs.home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">{{ __('admin-breadcrumbs.reports') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active">IB Withdraw</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1 breadcrumb-right">
                        <div class="dropdown">
                            <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    data-feather="grid"></i></button>
                            <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#"><i
                                        class="me-1" data-feather="info"></i>
                                    <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i
                                        class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a>
                            </div>
                            <button class="btn-icon btn btn-primary btn-round btn-sm" type="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="advance-filter-btn">Advance Filetr</button>
                        </div>
                    </div>
                </div>            </div>
            <div class="content-body">
                <!-- Ajax Sourced Server-side -->
                <section id="ajax-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card d-none" id="filter-form">
                                <div class="card-header border-bottom d-flex justfy-content-between">
                                    <h4 class="card-title">{{ __('ad-reports.filter_report') }}</h4>
                                    <div class="btn-exports" style="width:200px">
                                        <select data-placeholder="Select a state..." class="select2-icons form-select"
                                            id="fx-export">
                                            <optgroup label="Social Media">
                                                <option value="download" data-icon="download" selected>
                                                    {{ __('ad-reports.export') }}
                                                </option>
                                                <option value="csv" data-icon="file">CSV</option>
                                                <option value="excel" data-icon="file">EXCEL</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <!--Search Form -->
                                <div class="card-body mt-2">
                                    <form class="dt_adv_search" id="filterForm" method="POST">
                                        <div class="row mb-1 g-1">
                                            <div class="col-md-3" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Transaction Method Filter">
                                                <!-- filter by transaction method -->
                                                <label for="" class="form-label">Method</label>
                                                <select class="select2 form-select withdraw-method" id="transaction_type"
                                                    name="transaction_type" id="select2-basic">
                                                    <option value="" selected>{{ __('ad-reports.all') }}</option>
                                                    @foreach ($withdraw as $row)
                                                        <option value="{{ $row->transaction_type }}">
                                                            {{ ucfirst($row->transaction_type) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 ">
                                                <!-- filter by kyc verification status -->
                                                <label for="kyc-verification" class="form-label">KYC Verification
                                                    Status</label>
                                                <select class="select2 form-select" name="verify_status"
                                                    id="kyc-verification">
                                                    <optgroup label="Verification Status">
                                                        <option value="">All</option>
                                                        <option value="1">Verified</option>
                                                        <option value="0">Unverified</option>
                                                        <option value="2">Pending</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                            <div class="col-md-3 " data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Approved status">
                                                <!-- filter by approved status -->
                                                <label for="approved_status" class="form-label">Approve Status</label>
                                                <select class="select2 form-select" name="approved_status"
                                                    id="approved_status">
                                                    <option value="">{{ __('ad-reports.all') }}</option>
                                                    <option value="A">{{ __('ad-reports.approved') }}</option>
                                                    <option value="P">{{ __('ad-reports.pending') }}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Search By Created By">
                                                <!-- filter by created by -->
                                                <label for="" class="form-label">Created by</label>
                                                <select class="select2 form-select" name="created_by" id="created_by">
                                                    <option value="">{{ __('ad-reports.all') }}</option>
                                                    <option value="admin">Admin</option>
                                                    <option value="system">System</option>
                                                    <option value="system_admin">System Admin</option>
                                                    <option value="manager">Manager</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row g-1 mb-md-1">
                                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Search By Trader Information">
                                                <label for="" class="form-label">Trader Info</label>
                                                <input id="trader_info" type="text"
                                                    class="form-control dt-input dt-full-name" data-column="1"
                                                    name="trader_info" placeholder="Trader Name / Email / Phone / Country"
                                                    data-column-index="0" />
                                            </div>
                                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Search By IB Information">
                                                <label for="" class="form-label">IB Info</label>
                                                <input id="ib_info" type="text"
                                                    class="form-control dt-input dt-full-name" data-column="1"
                                                    name="ib_info" placeholder="IB Name / Email / Phone / Country"
                                                    data-column-index="0" />
                                            </div>
                                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Search By Trading Account">
                                                <label for="" class="form-label">Trading Account Number</label>
                                                <input id="trading_account" type="text"
                                                    class="form-control dt-input dt-full-name" data-column="1"
                                                    name="trading_account" placeholder="Trading Account Number"
                                                    data-column-index="0" />
                                            </div>
                                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Search By Account / Desk Manager">
                                                <label for="" class="form-label">Manager Info</label>
                                                <input id="manager_info" type="text" class="form-control dt-input"
                                                    data-column="2" name="manager_info"
                                                    placeholder="Account Manager / Desk Manager" data-column-index="1" />
                                            </div>
                                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Search By MIN MAX Amount">
                                                <label for="" class="form-label">Amount</label>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            {{ __('ad-reports.min') }}
                                                        </span>
                                                        <input id="min" type="text" class="form-control"
                                                            name="min">
                                                        <span class="input-group-text">-</span>
                                                        <input id="max" type="text" class="form-control"
                                                            name="max">
                                                        <span class="input-group-text">{{ __('ad-reports.max') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="" class="form-label">Request Date</label>
                                                <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Search By Request Date">
                                                    <span class="input-group-text">
                                                        <div class="icon-wrapper">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="50"
                                                                height="50" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-calendar">
                                                                <rect x="3" y="4" width="18" height="18"
                                                                    rx="2" ry="2">
                                                                </rect>
                                                                <line x1="16" y1="2" x2="16"
                                                                    y2="6"></line>
                                                                <line x1="8" y1="2" x2="8"
                                                                    y2="6"></line>
                                                                <line x1="3" y1="10" x2="21"
                                                                    y2="10"></line>
                                                            </svg>
                                                        </div>
                                                    </span>
                                                    <input id="from" type="text" id="fp-default"
                                                        class="form-control flatpickr-basic" placeholder="YYYY-MM-DD"
                                                        name="from">
                                                    <span class="input-group-text">to</span>
                                                    <input id="to" type="text" id="fp-default"
                                                        class="form-control flatpickr-basic" placeholder="YYYY-MM-DD"
                                                        name="to">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-1">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-4">
                                                <button id="btn_reset" type="button"
                                                    class="btn btn-secondary form-control" data-column="4"
                                                    data-column-index="3">{{ __('client-management.Reset') }}</button>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <button id="filterBtn" type="button"
                                                    class="btn btn-primary form-control" data-column="4"
                                                    data-column-index="3">{{ __('client-management.Filter') }}</button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <hr class="my-0" />

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <!--Search Form -->
                                <div class="card-body mt-2">

                                    <table id="deposit_tbl" class="datatables-ajax ib-withdraw table table-responsive">
                                        <thead>
                                            <tr>
                                                {{-- <th></th> --}}
                                                <th>{{ __('ad-reports.full-name') }}</th>
                                                <th>{{ __('ad-reports.email') }}</th>
                                                <th>{{ __('ad-reports.method') }}</th>
                                                <th>Created By</th>
                                                <th>{{ __('ad-reports.status') }}</th>
                                                <th>{{ __('ad-reports.request') }}</th>
                                                <th>{{ __('ad-reports.approved') }}</th>
                                                <th>{{ __('ad-reports.amount') }}</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th class="small-none-two"></th>
                                                <th class="small-none"></th>
                                                <th class="small-none"></th>
                                                <th class="small-none"></th>
                                                <th class="small-none"></th>
                                                <th colspan="2" style="text-align: right;" class="details-control"
                                                    rowspan="1">{{ __('page.total') }} {{ __('page.amount') }} : </th>
                                                <th id="total_amount" rowspan="1" colspan="1">$0</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <hr class="my-0" />
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Ajax Sourced Server-side -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
@stop
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
    <!-- datatable -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
    {{-- datatable buttons --}}
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>

    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script>
        // $(document).ready(function() {
        var dt = $('#deposit_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            "buttons": true,
            "dom": 'B<"clear">lfrtip',
            buttons: [{
                    extend: 'csv',
                    text: 'csv',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    },
                    action: serverSideButtonAction
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    className: 'btn btn-warning btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    },
                    action: serverSideButtonAction
                },
            ],
            "ajax": {
                "url": "/admin/report/withdraw/ib/dt",
                "data": function(d) {
                    return $.extend({}, d, $("#filterForm").serializeObject());
                }
            },

            "columns": [{
                    "class": "details-control",
                    "data": "name"
                },
                {
                    "data": "email"
                },

                {
                    "data": "transaction_type"
                },
                {
                    "data": "created_by"
                },
                {
                    "data": "approved_status"
                },
                {
                    "data": "created_at"
                },
                {
                    "data": "approved_date"
                },

                {
                    "data": "amount"
                },


            ],

            "drawCallback": function(settings) {
                $("#filterBtn").html("FILTER");
                $("#total_amount").html('$' + settings.json.total_amount);
                var rows = this.fnGetData();
                if (rows.length !== 0) {
                    feather.replace();
                }
            },
            "order": [
                [1, 'asc']
            ]

        });

        $('#filterBtn').click(function(e) {
            $(this).html("<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>");
            dt.draw();
        });

        // showing Withdraw Details
        $(document).on("click", ".dt-description", function(params) {
            let __this = $(this);
            let withdrawId = $(this).data('withdrawid');

            $.ajax({
                type: "GET",
                url: '/admin/report/withdraw/ib/dt-description/' + withdrawId,
                dataType: 'json',
                success: function(data) {
                    if (data.status == true) {
                        if ($(__this).closest("tr").next().hasClass("description")) {
                            $(__this).closest("tr").next().remove();
                            $(__this).find('.w').html(feather.icons['plus'].toSvg());
                        } else {
                            $(__this).closest('tr').after(data.description);
                            $(__this).closest('tr').next('.description').slideDown('slow')
                                .delay(5000);
                            // $(__this).find('svg').remove();
                            $(__this).find('.w').html(feather.icons['minus'].toSvg());
                        }
                    }
                }
            })
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

        // });

        $(document).ready(function() {
            $("#btn_reset").click(function() {
                $("#filterForm")[0].reset();
                $('#kyc-verification').prop('selectedIndex', 0).trigger("change");
                $('#method').prop('selectedIndex', 0).trigger("change");
                $('#approved_status').prop('selectedIndex', 0).trigger("change");
                $('#platform').prop('selectedIndex', 0).trigger("change");
                $('#created_by').prop('selectedIndex', 0).trigger("change");
                $('#transaction_type').prop('selectedIndex', 0).trigger("change");
                dt.draw();
            });
        });
        
        $(document).on("click", "#advance-filter-btn", function() {
            $("#filter-form").toggleClass("d-none");
        });
    </script>
@stop
<!-- END: page JS -->
