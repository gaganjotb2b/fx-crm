@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Account Request')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/calendars/fullcalendar.min.css') }}">
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
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/pages/app-calendar.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
<style>
    .bank-identify-modal {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 60% !important;
        pointer-events: auto;
        background-color: #fff;
        background-clip: padding-box;
        border: 0 solid rgba(34, 41, 47, 0.2);
        border-radius: 0.357rem;
        outline: 0;
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
                        <h2 class="content-header-title float-start mb-0">{{__('admin-breadcrumbs.request')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">{{__('ib-management.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{__('admin-breadcrumbs.request')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('admin-breadcrumbs.account-request')}}
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
                                <form class="dt_adv_search" method="POST" id="filter-form">
                                    <div class="row g-1 mb-md-1">
                                        <!-- <div class="col-md-4">
                                            <label class="form-label"
                                                for="finance">{{ __('client-management.Search By Finance') }}</label>
                                            <select class="select2 form-select" id="finance">
                                                <option value="">{{ __('client-management.All') }}</option>
                                                <option value="deposit">{{ __('client-management.Deposit') }}</option>
                                                <option value="withdraw">{{ __('client-management.Withdraw') }}
                                                </option>
                                            </select>
                                        </div> -->
                                        <div class="col-md-4">
                                            <label class="form-label">{{__('page.trading-accounts')}}</label>
                                            <input id="trading_acc" type="text" name="trading_acc" class="form-control dt-input" data-column="4" placeholder="Trading Account" data-column-index="3" />
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="platform">{{__('page.search_by')}} {{__('page.platform')}}</label>
                                            <select class="select2 form-select" id="platform" name="platform">
                                                <option value="">{{ __('client-management.All') }}</option>
                                                <option value="mt4">MT4</option>
                                                <option value="mt5">MT5</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('page.approve-status') }}</label>
                                            <select class="select2 form-select" id="verification-status" name="approve_status">
                                                <option value="">{{ __('client-management.All') }}</option>
                                                <option value="0" selected>{{ __('client-management.Pending') }}</option>
                                                <option value="1">{{ __('page.approved') }}</option>
                                                <option value="2">{{ __('page.declined') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-1 mb-md-1">
                                        <!-- <div class="col-md-4">
                                            <label class="form-label">{{__('page.manager_email')}}</label>
                                            <input id="manager" type="text" name="manager"
                                                class="form-control dt-input" data-column="4"
                                                placeholder="Manager Email" data-column-index="3" />
                                        </div> -->
                                        <div class="col-md-4">
                                            <label class="form-label">{{__('page.leverage')}}</label>
                                            <input id="leverage" name="leverage" type="text" class="form-control dt-input" data-column="4" placeholder="Account leverage" data-column-index="3" />
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{__('finance.Trader')}} {{__('page.account_info')}}</label>
                                            <div class="mb-0">
                                                <input id="info" type="text" name="info" class="form-control dt-input" data-column="4" placeholder="Email / Name / Phone" data-column-index="3" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">IB {{__('page.information')}} </label>
                                            <div class="mb-0">
                                                <input id="ib_info" type="text" name="ib_info" class="form-control dt-input" data-column="4" placeholder="IB Name /Email" data-column-index="3" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4"></div>
                                        <div class="col-md-2">
                                            <label class="form-label">{{__('page.action')}}</label>
                                            <button id="btn-reset" type="button" class="btn btn-secondary form-control" data-column="4" data-column-index="3">{{ __('client-management.Reset') }}</button>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">{{__('page.action')}}</label>
                                            <button id="btn-filter" type="button" class="btn btn-primary form-control" data-column="4" data-column-index="3">{{ __('client-management.Filter') }}</button>
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

                                <table id="fund_transfer_tbl" class="datatables-ajax table">
                                    <thead>
                                        <tr>
                                            <th>{{__('admin-deposit-report.email')}}</th>
                                            <th>{{__('page.account-number')}}</th>
                                            <th>{{__('page.server')}}</th>
                                            <th>{{__('page.approve-status')}}</th>
                                            <th>{{__('page.account-type')}}</th>
                                            <th>{{__('page.opening-date')}}</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align: right;" class="details-control" rowspan="1">{{__('ad-reports.total-amount')}}</th>
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

<!-- add new card modal  -->
<div class="modal fade" id="addNewCard" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body px-sm-5 mx-50 pb-5">
                <h1 class="text-center mb-1" id="addNewCardTitle">Reason For Decline</h1>

                <!-- form -->
                <form id="decline_request" class="row gy-1 gx-2 mt-75" action="{{route('admin.decline-request')}}" method="POST">
                    <div class="col-12">
                        <label class="form-label" for="modalAddCardNumber">Reason:</label>
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
                        <button type="button" class="btn btn-primary me-1 mt-1" id="reason-yes">Yes</button>
                        <button type="button" class="btn btn-outline-secondary mt-1" id="reason-no">
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

<!-- Edit User Modal -->
<div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content" id="bank-identify-modal">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1 text-start">Bank Proof</h1>
                    <!-- Vertical Left Tabs start -->
                    <div class="col-xl-12 col-lg-12">
                        <div class="card shadow-none">
                            <div class="card-body p-0 m-0">
                                <div class="nav-vertical">

                                    <div class="tab-content bank_identify">
                                        <div class="geeks" style="height: 80%; width: 100%;">
                                            <img id="frontPart" class="img-thumbnail" src="{{ url('admin-assets/driver_license.png') }}">
                                        </div>
                                    </div>
                                    <div class="tab-content crypto_identify">
                                        <div class="geeks" style="height: 80%; width: 100%;">
                                            {{-- <div class="collapse" id="collapseExample"> --}}
                                            <ul class="list-group list-group-flush mt-1">
                                                <li class="d-flex justify-content-between flex-wrap invoice">
                                                    <span>Invoice : <span class="fw-bold "></span></span>
                                                    <i data-feather="share-2" class="cursor-pointer font-medium-2"></i>
                                                </li>
                                                <li class="d-flex justify-content-between flex-wrap transaction">
                                                    <span> Transaction : <span class="fw-bold "></span></span>
                                                    <i data-feather="share-2" class="cursor-pointer font-medium-2"></i>
                                                </li>
                                            </ul>
                                            {{-- </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Vertical Left Tabs ends -->
                    <div class="col-12 text-right">
                        <button type="reset" class="btn btn-primary waves-effect waves-float waves-light" data-bs-dismiss="modal" aria-label="Close" style="float: right" ;>
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Edit User Modal -->
<!-- END: Content-->

@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')

{{-- <script src="{{ asset('admin-assets/app-assets/vendors/js/calendar/fullcalendar.min.js') }}"></script> --}}
{{-- <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/moment.min.js') }}"></script> --}}
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
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
{{-- <script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js')}}"></script> --}}
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>


<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
{{-- <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/app-calendar-events.js') }}"></script> --}}
{{-- <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/app-calendar.js') }}"></script> --}}
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>

<!-- datatable  -->
<script>
    var dt = $('#fund_transfer_tbl').fetch_data({
        url: "/admin/manage-report/account-request",
        columns: [{
                "data": "email"
            },
            {
                "data": "account_number"
            },
            {
                "data": "server"
            },
            {
                "data": "status"
            },
            {
                "data": "account_type"
            },
            {
                "data": "action"
            },
        ]
    });


    /*<---------------Datatable Descriptions Start------------>*/
    $("body").fetch_description({
        url: "/admin/manage-report/account-request",
    });

    /*<!---------------Approve Data request operation------------------!>*/
    function approve_request(e) {
        let obj = $(e);
        var id = obj.data('id');
        var user_id = obj.data('user_id');
        // console.log(user_id);
        let warning_title = "";
        let warning_msg = "";
        let request_for;

        warning_title = 'Are you sure? to Approve this account!';
        warning_msg = 'If you want to Approve this account please click OK, otherwise simply click cancel'
        request_for = 'approve'

        let data = {
            id: id,
            request_for: request_for
        };
        let request_url = '/admin/manage-report/account-request/approve/';
        let url = '/admin/manage-report/account-request/approve?op=mail & id=' + '&user_id=' + user_id;
        confirm_alert(warning_title, warning_msg, request_url, data, 'Account ' + request_for, dt, true, url, $(this));

    }

    /*<!---------------Approve Data request operation End------------------!>*/
    function decline_request(e) {
        let obj = $(e);
        var id = obj.data('id');
        var user_id = obj.data('user_id');
        // console.log(user_id);
        let warning_title = "";
        let warning_msg = "";
        let request_for;

        warning_title = 'Are you sure? to decline this account!';
        warning_msg = 'If you want to decline this account please click OK, otherwise simply click cancel'
        request_for = 'decline'

        let data = {
            id: id,
            request_for: request_for
        };
        let request_url = '/admin/manage-report/account-request/approve/';
        let url = '/admin/manage-report/account-request/approve?op=mail & id=' + '&user_id=' + user_id;
        confirm_alert(warning_title, warning_msg, request_url, data, 'Account ' + request_for, dt, true, url, $(this));

    }

    /*<-------------------Decline Deposit request operation Start--------------------->*/


    /*<-------------------Decline request End--------------------->*/
</script>
@stop
<!-- BEGIN: page JS -->