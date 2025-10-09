@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'User Withdraw Report')
@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <style>
        #withdraw_report_datatable tr,
        #withdraw_report_datatable td {
            background-color: #f7fafc;
            vertical-align: middle;
            text-align: left;
            padding-left: 24px;
        }

        #withdraw_report_datatable tr,
        #withdraw_report_datatable th {
            background-color: #f7fafc;
            vertical-align: middle;
            text-align: left !important;
        }

        .dark-version #withdraw_report_datatable tr,
        .dark-version #withdraw_report_datatable th {
            background-color: #141728;
        }

        #withdraw_report_datatable {
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

        #total_amount {
            padding-left: 24px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
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

        /* .dark-version */
    </style>
@endsection

@section('bread_crumb')
    <!-- bread crumb -->
    {!! App\Services\systems\BreadCrumbService::get_trader_breadcrumb() !!}
@stop
<!-- main content -->
@section('content')
    <div class="container-fluid py-4">
        <div class="custom-height-con">
            <div class="card d-none" id="filter-form">
                <!-- Card header -->
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <h5 class="mb-0">{{ __('page.filter') }} {{ __('page.reports') }}</h5>
                        </div>

                        <div class=" border-bottom border-0">
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
                        <div class="row g-1 mb-md-1 my-3">
                            <!-- Filter By Transaction Method -->
                            <div class="col-lg-4 col-md-6" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Search By Transaction Method">
                                <label for="type" class="form-label">Transaction Method</label>
                                <select class="form-select choice-material" name="type" id="type">
                                    <optgroup label="Select Method">
                                        <option value="">{{__('page.all')}}</option>
                                        @php
                                        $trasanction_types = App\Models\Withdraw::select('transaction_type')->distinct('transaction_type')->get();
                                        @endphp
                                        @foreach($trasanction_types as $transaction_type)
                                        <option value="{{ $transaction_type->transaction_type }}">{{ $transaction_type->trasanction_type }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                            <!-- Filter By Approved Status -->
                            <div class="col-lg-4 col-md-6" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Search By Approve Status">
                                <label for="approved_status" class="form-label">Approved Status</label>
                                <select class="form-select choice-colors" name="approved_status" id="approved_status">
                                    <option value="">{{ __('page.all') }}</option>
                                    <option value="A">{{ __('ad-reports.approved') }}</option>
                                    <option value="P">{{ __('page.pending') }}</option>
                                    <option value="D">{{ __('ad-reports.declined') }}</option>
                                </select>
                            </div>

                            <!-- Filter By Amount -->
                            <div class="col-lg-4 col-md-6" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Min To Max Withdraw">
                                <label for="" class="form-label">Amount</label>
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
                            <div class="col-lg-4 col-md-6">
                                <label for="" class="form-label">Request Date</label>
                                <div class="col-12" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Search By Withdraw Date">
                                    <div class="col-12 input-rang-group">
                                        <span class="col-1 input-rang-group-date-logo rang-min">
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
                                        <input type="text" id="from" class="col-4 min flatpickr-basic"
                                            name="from" placeholder="YY-MM-DD">
                                        <span class="input-rang-group-text col-1">-</span>
                                        <input type="text" id="to" class="col-4 max flatpickr-basic"
                                            name="to" placeholder="YY-MM-DD">
                                        <span class="col-1 input-rang-group-date-logo rang-max input-range-gpr-right">
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

            <div class="card">
                <div class="card-body">
                    <button id="advance-filter-btn" type="button" class="btn bg-gradient-primary">
                        <span class="align-middle">Advance Filter</span>
                    </button>
                    <div class="table-responsive">
                        <table class="table table-flush datatables-ajax w-100 text-center" id="withdraw_report_datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('page.method') }}</th>
                                    <th>{{ __('page.create_date') }}</th>
                                    <th>{{ __('page.approve_date') }}</th>
                                    <th>{{ __('page.status') }}</th>
                                    <th>{{ __('ad-reports.charge') }}</th>
                                    <th>{{ __('page.amount') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="5" style="text-align: right;" class="details-control"
                                        rowspan="1">{{ __('ad-reports.total-amount') }} : </th>
                                    <th class="text-left" id="total_sum2" rowspan="1" colspan="1">$0</th>
                                </tr>
                            </tfoot>
                        </table>
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
        var withdraw_report = $("#withdraw_report_datatable").fetch_data({
            url: '/user/reports/withdraw-report?op=data_table',
            csv_export: true,
            total_sum2: true,
            length_change: true,
            icon_feather: false,
            customorder: 2,
            o_Language: true,
            columns: [
                { "data": "transaction_type" },
                { "data": "created_at" },       // was: created_at → renamed
                { "data": "approved_date" },      // was: approve → renamed
                { "data": "status" },
                { "data": "charge" },
                { "data": "amount" },
            ],
        });
    
        $(document).on("click", "#advance-filter-btn", function () {
            $("#filter-form").toggleClass("d-none");
        });
        
        // $(document).on('click', '.btn-decline', function () {
        //     const withdraw_id = $(this).data('id'); // Get the data-id attribute from the button
    
        //     // Show confirmation dialog using SweetAlert2
        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: 'You want to decline the withdraw request!',
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, decline it!',
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             // Make the AJAX call to delete the trader
        //             $.ajax({
        //                 url: '/user/withdraw-decline/'+ withdraw_id, // The route to your delete function
        //                 type: 'GET', // HTTP method
        //                 success: function (response) {
        //                     if (response.status) {
        //                         Swal.fire(
        //                             'Deleted!',
        //                             response.message,
        //                             'success'
        //                         ).then(() => {
        //                             location.reload(); // Reload the page or update the UI dynamically
        //                         });
        //                     } else {
        //                         Swal.fire('Error!', response.message, 'error');
        //                     }
        //                 },
        //                 error: function (xhr) {
        //                     Swal.fire(
        //                         'Error!',
        //                         xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred.',
        //                         'error'
        //                     );
        //                 },
        //             });
        //         }
        //     });
        // });
    </script>
@endsection
