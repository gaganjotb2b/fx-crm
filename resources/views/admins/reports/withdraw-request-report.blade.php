@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Withdraw Request Report')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<style>
    .dark-layout .tab-inner-dark li {
        background-color: #283046;
        width: 215px !important;
    }

    .light-layout .tab-inner-dark li {
        background-color: #fff;
        width: max-content !important;
    }

    /* for Laptop */
    @media screen and (max-width: 1280px) and (min-width: 800px) {

        .deposit-request thead tr th:nth-child(4),
        .deposit-request tbody tr td:nth-child(4) {
            display: none;
        }

        .small-none {
            display: none;
        }

        .small-none-three {
            display: none
        }
    }

    @media screen and (max-width: 1280px) and (min-width: 800px) {

        .deposit-request thead tr th:nth-child(5),
        .deposit-request tbody tr td:nth-child(5) {
            display: none;
        }

        .small-none-two {
            display: none;
        }
    }



    @media screen and (max-width: 1440px) and (min-width: 900px) {

        .deposit-request thead tr th:nth-child(3),
        .deposit-request tbody tr td:nth-child(3) {
            display: none;
        }

        .small-none {
            display: none;
        }
    }

    thead,
    tbody,
    tfoot,
    tr,
    td,
    th {
        border-style: none !important;
    }

    /* .dt-trader-img.ms-1 {
    width: 170px !important;
    height: 170px !important;
} */
.table thead th, .table tfoot th {
    vertical-align: top;
    text-transform: none !important;
    font-size: 0.857rem;
    letter-spacing: 0.5px;
}
</style>
@stop
<!-- END: page css -->
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
                        <h2 class="content-header-title float-start mb-0">{{__('admin-breadcrumbs.withdraw_req')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('ib-management.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{__('admin-breadcrumbs.manage_request')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('admin-breadcrumbs.withdraw_req')}}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                        <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#"><i class="me-1" data-feather="info"></i>
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Ajax Sourced Server-side -->
            <section id="ajax-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-bottom d-flex justfy-content-between">
                                <h4 class="card-title">{{__('ad-reports.filter_report')}}</h4>
                                <div class="btn-exports" style="width:200px">
                                    <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                        <option value="download" data-icon="download" selected>{{__('ib-management.export')}}</option>
                                        <option value="csv" data-icon="file">CSV</option>
                                        <option value="excel" data-icon="file">Excel</option>
                                    </select>
                                </div>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form id="filter-form" class="dt_adv_search" method="POST">
                                    <div class="row g-1 mb-1">
                                        <div class="col-md-4">
                                            <!-- filter by transaction type -->
                                            <label for="" class="form-label cu-form-label">Transaction Type</label>
                                            <select class="select2 form-select" name="transaction_type" id="transaction_type">
                                                <optgroup label="Search By Method">
                                                    <option value="" selected>{{ __('ad-reports.all') }}</option>
                                                    @foreach ($withdraw as $row)
                                                        <option value="{{ $row->transaction_type }}">{{ ucwords($row->transaction_type) }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- fitler by kyc verification status -->
                                            <label for="" class="form-label cu-form-label">KYC Verification Status</label>
                                            <select class="select2 form-select" name="verification_status" id="verification_status">
                                                <optgroup label="Verification Status">
                                                    <option value="">{{__('ad-reports.all')}}</option>
                                                    <option value="Verified">{{__('ad-reports.verified')}}</option>
                                                    <option value="Unverified">{{__('ad-reports.unverified')}}</option>

                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by appoved status -->
                                            <label for="" class="form-label cu-form-label">Approved Status</label>
                                            <select class="select2 form-select" name="status" id="status">
                                                <optgroup label="Search By Status">
                                                    <option value="" selected>{{__('ad-reports.all')}}</option>
                                                    <option value="A">{{__('ad-reports.approved')}}</option>
                                                    <option value="P" selected>{{__('ad-reports.pending')}}</option>
                                                    <option value="D">{{__('ad-reports.declined')}}</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        
                                    </div>
                                    <div class="row mb-1 g-1">
                                        <div class="col-md-4 ">
                                            <!-- filter by client type -->
                                            <label for="" class="form-label cu-form-label">Client Type</label>
                                            <select class="select2 form-select" name="client_type" id="client_type">
                                                <optgroup label="Client Type">
                                                    <option value="" selected>All</option>
                                                    <option value="ib">IB</option>
                                                    <option value="trader">Trader</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by trader info -->
                                            <label for="" class="form-label cu-form-label">Trader Info</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Trader Information" class="form-control dt-input dt-full-name" data-column="1" name="trader_info" id="trader_info" placeholder=" {{ ($varsion == 'pro') ? 'Trader Email / Name / Phone / Country' : 'Trader Email / Name / Phone' }}" data-column-index="0" />
                                        </div>

                                        <div class="col-md-4">
                                            <!-- filter by IB info -->
                                            <label for="" class="form-label cu-form-label">IB Info</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By IB Information" class="form-control dt-input dt-full-name" data-column="1" name="ib_info" id="ib_info" placeholder="{{ ($varsion == 'pro') ? 'IB Email / Name / Phone / Country' : 'IB Email / Name / Phone' }}" data-column-index="0" />
                                        </div>
                                    </div>
                                    <div class="row g-1 mb-1">

                                        <div class="col-md-4">
                                            <!-- filter by trading account info -->
                                            <label for="trading-account" class="form-label cu-form-label">Trading account</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Trading account" class="form-control dt-input dt-full-name" data-column="1" name="trading_account" id="trading-account" placeholder="Account number" data-column-index="0" />
                                        </div>

                                        <div class="col-md-4">
                                            <!-- filter by amount min / max-->
                                            <label for="" class="form-label cu-form-label">Amount</label>
                                            <div class="form-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter MIN MAX Amount">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        {{__('ad-reports.min')}}
                                                    </span>
                                                    <input id="min" type="text" class="form-control" name="min">
                                                    <span class="input-group-text">-</span>
                                                    <input id="max" type="text" class="form-control" name="max">
                                                    <span class="input-group-text">{{__('ad-reports.max')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by request date range -->
                                            <label for="" class="form-label cu-form-label">Request Date</label>
                                            <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Request Date">
                                                <span class="input-group-text">
                                                    <div class="icon-wrapper">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                                        </svg>
                                                    </div>
                                                </span>
                                                <input id="from" type="text" name="from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                <span class="input-group-text">to</span>
                                                <input id="to" type="text" name="to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-1">
                                        <div class="col-md-4">
                                            <!-- filter by appoved status -->
                                            <label for="created-by" class="form-label cu-form-label">Created by</label>
                                            <select class="select2 form-select" name="created_by" id="created-by">
                                                <option value="" selected>{{__('ad-reports.all')}}</option>
                                                @if($varsion =='pro')
                                                    <option value="admin">Admin</option>
                                                    <option value="system">System (Trader/IB)</option>
                                                    <option value="manager">Manager</option>
                                                @else
                                                    <option value="admin">Admin</option>
                                                @endif
                                            </select>
                                        </div>
                                        @if($varsion =='pro')
                                        <div class="col-md-4">
                                            <!-- filter by manager info -->
                                            <label for="" class="form-label cu-form-label">Manager Info</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Manager" class="form-control dt-input dt-full-name" data-column="1" name="manager_info" id="manager-info" placeholder="Account Manager / Desk Manager" data-column-index="0" />
                                        </div>
                                        @else
                                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Country">
                                                <!-- filter by Country -->
                                                <label class="form-label">Country</label>
                                                <select class="select2 form-select" name="country">
                                                    <option value="">{{ __('client-management.All') }}</option>
                                                    @foreach ($countries as $value)
                                                        <option value="{{ $value->name }}">{{ $value->name }}</option>
                                                    @endforeach
                                                    
                                                </select>
                                            </div>
                                        @endif
                                        <div class="col-md-2 text-right">
                                            <label class="form-label">&nbsp;</label>
                                            <button id="btn-reset" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('ad-reports.btn-reset')}}</span>
                                            </button>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <label class="form-label">&nbsp;</label>
                                            <button id="btn-filter" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('category.FILTER')}}</span>
                                            </button>
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
                            <div class="card-body mt-2 table-responsive">

                                <table id="fund_transfer_tbl" class="datatables-ajax deposit-request table ">
                                    <thead>
                                        <tr>
                                            <th>{{__('admin-deposit-report.name')}}</th>
                                            <th>{{__('client-management.Email')}}</th>
                                            <th>{{__('ad-reports.method')}}</th>
                                            <th>{{ __('page.client-type') }}</th>
                                            <th>{{__('ad-reports.status')}}</th>
                                            <th>Created by</th>
                                            <th>{{__('admin-deposit-report.date')}}</th>
                                            <th>{{__('ad-reports.amount')}}</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th class="small-none-three"></th>
                                            <th class="small-none-two"></th>
                                            <th class="small-none"></th>
                                            <th colspan="4" style="text-align: right;" class="details-control" rowspan="1">{{__('ad-reports.total-amount')}}</th>
                                            <th id="total_1" rowspan="1" colspan="1">$0</th>
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

<!-- add new card modal  -->
<div class="modal fade" id="addNewCard" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-5">
                <h1 class="text-center mb-1" id="addNewCardTitle">Reason For Decline</h1>

                <!-- form -->
                <form id="withdraw_decline_request" class="row gy-1 gx-2 mt-75" action="{{route('withdraw.decline.request')}}" method="POST">

                    <div class="col-12">
                        <label class="form-label cu-form-label" for="modalAddCardNumber">Reason:</label>
                        <div class="input-group input-group-merge">
                            <input id="reason" name="reason" class="form-control add-credit-card-mask" type="text" placeholder="type here....." aria-describedby="modalAddCard2" />
                            <span class="input-group-text cursor-pointer p-25" id="modalAddCard2">
                                <span class="add-card-type"></span>
                            </span>
                            <input type="hidden" name="decline_id" id="decline_id">
                            <input type="hidden" name="user_id" id="user_id">
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-1 mt-1">Yes</button>
                        <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                            No
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ add new card modal  -->

<!-- Modal sending mail-->
<div class="modal fade text-start modal-success" id="send-mail-pass" tabindex="-1" aria-labelledby="mail-sending-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mail-sending-modal">Sending Mail.....</h5>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <p class="text-warning">Please wait, While we sending mail to - user.</p>
                    <div class="spinner-border text-success" style="width: 3rem; height: 3rem" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<!-- Modal Themes end -->

<!-- add new card modal  -->
<div class="modal fade" id="amount_edit" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body px-sm-5 mx-50 pb-5">
                <h1 class="text-center mb-1" id="addNewCardTitle">Update Request Amount</h1>

                <!-- form -->
                <form id="amountRequest" class="row gy-1 gx-2 mt-75" action="{{route('admin.withdraw.amount.update')}}" method="POST">
                    @csrf
                    <div class="col-12">
                        <label class="form-label cu-form-label" for="modalAddCardNumber">Current Amount:</label>
                        <div class="input-group input-group-merge">
                            <input id="request_amount" name="request_amount" class="form-control add-credit-card-mask" type="text" placeholder="amount" aria-describedby="modalAddCard2" />
                            <span class="input-group-text cursor-pointer p-25" id="modalAddCard2">
                                <span class="add-card-type"></span>
                            </span>
                            <input type="hidden" name="amount_id" id="amount_id">
                        </div>
                    </div>
                    <div class="col-12 text-center">

                        <button type="button" class="btn btn-primary me-1 mt-1" id="amountUpdateBtn" onclick="_run(this)" data-el="fg" data-form="amountRequest" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="amountUpdateCallBack" data-btnid="amountUpdateBtn">Save Change</button>
                        <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                            No
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ add new card modal  -->

<!-- END: Content-->


@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
{{-- <script src="{{ asset('admin-assets/app-assets/vendors/js/vendors.min.js') }}"></script> --}}
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js')}}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<!-- datatable -->
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>


<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>


<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>

<!-- datatable  -->
<script>
    var dt = $('.datatables-ajax').fetch_data({
        url: "/admin/manage-report/withdraw-request?op=data_table",
        columns: [{
                "data": "name"
            },
            {
                "data": "email"
            },
            {
                "data": "method"
            },
            {
                "data": "client_type"
            },
            {
                "data": "status"
            },
            {
                "data": "created_by"
            },
            {
                "data": "request_date"
            },
            {
                "data": "amount"
            }

        ],
        csv_export: true,
        export_col: [0, 1, 2, 3, 4, 5],
        length_change: true,
        total_sum: 1,
        customorder:6
    });



    /*<---------------Datatable Descriptions Start------------>*/
    $("body").fetch_description({
        url: '/admin/manage-report/withdraw-request-description',
        description_dt: false,
    });


    /*<!---------------Approve Data request operation------------------!>*/

    //requet amount data
    $(document).on("click", ".edit-amount-button", function() {
        $('#amount_id').val($(this).data('id'));
    });


    $(document).on('click', ".btn-transaction-approve", function() {
        let id = $(this).data('id');
        $(this).confirm2({
            request_url: '/admin/manage-report/withdraw-request/approve-request',
            data: {
                id: id
            },
            click: false,
            title: 'Approve withdraw',
            message: 'Are you confirm to approve this withdraw request?',
            button_text: 'Aprove',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'Withdraw approve');
            } else {
                notify('error', data.message, 'Withdraw approve');
            }
            dt.draw();
        });
    })
    /*<!---------------Approve Data request operation End------------------!>*/
    $(document).on('click', ".btn-transaction-declined", function() {
        let id = $(this).data('id');
        $(this).confirm2({
            request_url: '/admin/manage-report/withdraw-request/decline-request',
            data: {
                id: id
            },
            input: 'text',
            click: false,
            title: 'Decline Withdraw',
            message: 'Are you confirm to decline this withdraw?',
            button_text: 'Decline',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'Withdraw decline');
            } else {
                notify('error', data.message, 'Withdraw decline');
            }
            dt.draw();
        });
    })
    /*<-------------------Decline request End--------------------->*/

    /*<-------------------Total Deposit  Report--------------------->*/

    $(document).on("click", ".total-deposit-tab-fill", function() {
        let id = $(this).data('id');
        let user_id = $(this).data('user_id');
        if ($(this).closest('tr').find('.deposit').length) {
            $(this).closest('tr').find('.deposit').DataTable().clear().destroy();
            var cd = (new Date()).toISOString().split('T')[0];
            var dt_deposit = $(this).closest('tr').find('.deposit').fetch_data({
                url: "/admin/manage-report/deposit-inner-fetch-data/" + id,
                columns: [{
                        "data": "date"
                    },
                    {
                        "data": "method"
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": "wallet"
                    },
                    {
                        "data": "created_by"
                    },
                    {
                        "data": "amount"
                    },
                ],
            });
        }
    })


    /*<-------------------Total Withdraw Report--------------------->*/

    $(document).on("click", ".total-withdraw-tab-fill", function() {
        let id = $(this).data('id');
        if ($(this).closest('tr').find('.withdraw').length) {
            $(this).closest('tr').find('.withdraw').DataTable().clear().destroy();
            var cd = (new Date()).toISOString().split('T')[0];
            var dt_withdraw = $(this).closest('tr').find('.withdraw').fetch_data({

                url: "/admin/manage-report/withdraw-inner-fetch-data/" + id,
                columns: [{
                        "data": "date"
                    },
                    {
                        "data": "method"
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": "wallet"
                    },
                    {
                        "data": "created_by"
                    },
                    {
                        "data": "amount"
                    },
                ],
            });
        }
    })
    $(document).ready(function() {
        $("#btn-reset").click(function() {
            $("#filter-form")[0].reset();
            $('#verification_status').prop('selectedIndex', 0).trigger("change");
            $('#status').prop('selectedIndex', 2).trigger("change");
            $('#client_type').prop('selectedIndex', 0).trigger("change");
            dt.draw();

        });
    });
    /*<-------------------Total Bonus Report--------------------->*/
    //  bonus report
    $(document).on("click", ".bonus-tab-fill", function() {
        let id = $(this).data('id');
        let user_id = $(this).data('user_id');
        if ($(this).closest('tr').find('.bonus').length) {
            $(this).closest('tr').find('.bonus').DataTable().clear().destroy();
            var cd = (new Date()).toISOString().split('T')[0];
            var dt_bonus = $(this).closest('tr').find('.bonus').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "lengthChange": false,
                "buttons": true,
                "dom": 'Bfrtip',
                "ajax": {
                    "url": "/admin/manage-report/bonus-inner-fetch-data/" + id + '/' + user_id
                },
                "columns": [{
                        "data": "bonus_title"
                    },
                    {
                        "data": "amount"
                    },
                    {
                        "data": "platform"
                    },
                    {
                        "data": "start_date"
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": "ending_date"
                    },

                ],
                "order": [
                    [1, 'desc']
                ],
                "drawCallback": function(settings) {
                    var rows = this.fnGetData();
                    if (rows.length !== 0) {
                        feather.replace();
                    }
                }
            });
        }
    });

    // User amount view
    function view_amount(e) {
        let obj = $(e);
        var id = obj.data('id');

        var table_id = obj.data('table_id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/manage-report/withdraw-request-amount-view/' + id,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#request_amount').val(data);
            }
        });
    }



    // update amount callback
    function amountUpdateCallBack(data) {
        if (data.status == true) {
            notify('success', data.message, 'Amount Update');
            $('#amount_edit').modal('toggle');
            dt.draw();
        } else {
            notify('error', data.message, 'Amount Update');
        }
        $.validator("amountRequest", data.errors);
    }
    // disable submit button
    $(document).on('click', "#amountUpdateBtn", function() {
        $(this).prop('disabled', true);
        setTimeout(() => {
            $(this).prop('disabled', false);
        }, 5000);
    });
</script>
@stop
<!-- BEGIN: page JS -->