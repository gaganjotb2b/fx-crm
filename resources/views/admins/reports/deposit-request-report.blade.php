@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Deposit Request Report')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/calendars/fullcalendar.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/pages/app-calendar.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
<style>
    .bank-identify-modal {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 100% !important;
        pointer-events: auto;
        background-color: #fff;
        background-clip: padding-box;
        border: 0 solid rgba(34, 41, 47, 0.2);
        border-radius: 0.357rem;
        outline: 0;
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
                        <h2 class="content-header-title float-start mb-0">{{ __('admin-breadcrumbs.request') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('ib-management.Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{ __('admin-breadcrumbs.manage_request') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('admin-deposit-report.deposit-report') }}
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
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a>
                        </div>
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
                                <h4 class="card-title">{{ __('ad-reports.filter_report') }}</h4>
                                <div class="btn-exports" style="width:200px">
                                    <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                        <option value="download" data-icon="download" selected>
                                            {{ __('ib-management.export') }}
                                        </option>
                                        <option value="csv" data-icon="file">CSV</option>
                                        <option value="excel" data-icon="file">Excel</option>
                                    </select>
                                </div>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form id="filterForm" class="dt_adv_search" method="POST">
                                    <input type="hidden" id="table_id_hidden" name="table_id" value="{{request()->id}}">
                                    <div class="row g-1 mb-1">
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Transaction Type">
                                            <label for="transaction-method" class="form-label cu-form-label">Transaction method</label>
                                            <select class="select2 form-select" name="transaction_type" id="transaction_type">
                                                <!-- filter by transaction method -->
                                                <optgroup label="Search By Method">
                                                    <option value="">{{ __('ad-reports.all') }}</option>
                                                    @foreach ($deposit as $row)
                                                        <option value="{{ $row->transaction_type }}">{{ ucwords($row->transaction_type) }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>

                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="KYC Verification status">
                                            <label for="kyc-status" class="form-label cu-form-label">KYC verification status</label>
                                            <select class="select2 form-select" name="verification_status" id="verification_status">
                                                <!-- filter by verification status -->
                                                <option value="">{{ __('ad-reports.all') }}</option>
                                                <option value="2">Pending</option>
                                                <option value="1">{{ __('ad-reports.verified') }}</option>
                                                <option value="0">{{ __('ad-reports.unverified') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Approve Status">
                                            <label for="approved-status" class="form-label cu-form-label">Approved status</label>
                                            <select class="select2 form-select" name="status" id="status">
                                                <!-- filter by approved status -->
                                                <option value="">{{ __('ad-reports.all') }}</option>
                                                <option value="A">{{ __('ad-reports.approved') }}</option>
                                                <option value="P" selected>{{ __('ad-reports.pending') }}
                                                </option>
                                                <option value="D">{{ __('ad-reports.declined') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-1 mb-1">
                                        <div class="col-md-4">
                                            <!-- filter by cllient type -->
                                            <label for="client-type" class="form-label cu-form-label">Client type</label>
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
                                            <label for="trader-info" class="form-label cu-form-label">Trader Info</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ ($varsion == 'pro') ? 'Trader Email / Name / Phone / Country' : 'Trader Email / Name / Phone' }}" class="form-control dt-input dt-full-name" data-column="1" name="trader_info" id="trader-info" placeholder="{{ ($varsion == 'pro') ? 'Trader Email / Name / Phone / Country' : 'Trader Email / Name / Phone' }}" data-column-index="0" />
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by IB info -->
                                            <label for="ib-info" class="form-label cu-form-label">IB Info</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ ($varsion == 'pro') ? 'IB Email / Name / Phone / Country' : 'IB Email / Name / Phone' }}" class="form-control dt-input dt-full-name" data-column="1" name="ib_info" id="ib-info" placeholder="{{ ($varsion == 'pro') ? 'IB Email / Name / Phone / Country' : 'IB Email / Name / Phone' }}" data-column-index="0" />
                                        </div>
                                    </div>
                                    <div class="row g-1 mb-md-1">

                                        <div class="col-md-4">
                                            <!-- filter by trading account -->
                                            <label for="trading-account" class="form-label cu-form-label">Trading account</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Trading account" class="form-control dt-input dt-full-name" data-column="1" name="trading_account" id="trading-account" placeholder="Account number" data-column-index="0" />
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by amount min / max -->
                                            <label for="amount" class="form-label cu-form-label">Amount</label>
                                            <div class="form-group">
                                                <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Amount To Filter">
                                                    <span class="input-group-text">
                                                        {{ __('ad-reports.min') }}
                                                    </span>
                                                    <input id="min" type="text" class="form-control" name="min">
                                                    <span class="input-group-text">-</span>
                                                    <input id="max" type="text" class="form-control" name="max">
                                                    <span class="input-group-text">{{ __('ad-reports.max') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <!-- filter by request date -->
                                            <label for="date-range" class="form-label cu-form-label">Request Date</label>
                                            <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Request Date" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
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
                                                <!-- date from -->
                                                <input id="from" type="text" name="from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                <span class="input-group-text">to</span>
                                                <!-- date to -->
                                                <input id="to" type="text" name="to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                            </div>
                                        </div>

                                    </div>
                                    <!-- filter buttons -->
                                    <div class="row g-1">
                                        @if($varsion =='pro')
                                            <div class="col-md-4">
                                                <!-- filter by manager email / phone / country -->
                                                <label for="manager-info" class="form-label cu-form-label">Manager Info</label>
                                                <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Account manager / Desk manager" class="form-control dt-input dt-full-name" data-column="1" name="manager_info" id="manager_email" placeholder="Desk manager / Account manager" data-column-index="0" />
                                            </div>
                                        @else
                                            <div class="col-md-4"  data-bs-toggle="tooltip" data-bs-placement="top" title="Country">
                                                <!-- filter  country -->
                                                <label class="form-label">Country</label>
                                                <select class="select2 form-select" name="country">
                                                    <option value="">{{ __('client-management.All') }}</option>
                                                    @foreach ($countries as $value)
                                                        <option value="{{ $value->name }}">{{ $value->name }}</option>
                                                    @endforeach
                                                    
                                                </select>
                                            </div>
                                        @endif
                                        <div class="col-md-4 text-right">
                                            <label class="form-label">&nbsp;</label>
                                            <button id="resetBtn" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{ __('ad-reports.btn-reset') }}</span>
                                            </button>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <label class="form-label">&nbsp;</label>
                                            <button id="filterBtn" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{ __('category.FILTER') }}</span>
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
                                <table id="fund_transfer_tbl" class="datatables-ajax deposit-request table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('admin-deposit-report.name') }}</th>
                                            <th>{{ __('client-management.Email') }}</th>
                                            <th>{{ __('ad-reports.method') }}</th>
                                            <th>{{ __('page.client-type') }}</th>
                                            <th>{{ __('ad-reports.status') }}</th>
                                            <th>Created by</th>
                                            <th>{{ __('admin-deposit-report.date') }}</th>
                                            <th>{{ __('ad-reports.amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th class="small-none-three"></th>
                                            <th class="small-none-two"></th>
                                            <th class="small-none"></th>
                                            <th colspan="4" style="text-align: right;" class="details-control" rowspan="1">{{ __('ad-reports.total-amount') }}</th>
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
                <form id="decline_request" class="row gy-1 gx-2 mt-75" action="{{ route('admin.decline-request') }}" method="POST">
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

<!-- add new card modal  -->
<div class="modal fade" id="amount_edit" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body px-sm-5 mx-50 pb-5">
                <h1 class="text-center mb-1" id="addNewCardTitle">Update Request Amount</h1>

                <!-- form -->
                <form id="amountRequest" class="row gy-1 gx-2 mt-75" action="{{ route('admin.amount.update') }}" method="POST">
                    @csrf
                    <div class="col-12">
                        <label class="form-label cu-form-label" for="modalAddCardNumber">Current Amount:</label>
                        <div class="input-group input-group-merge">
                            <input id="request_amount" name="request_amount" class="form-control add-credit-card-mask" type="text" placeholder="amount" aria-describedby="modalAddCard2" />
                            <span class="input-group-text cursor-pointer p-25" id="modalAddCard2">
                                <span class="add-card-type"></span>
                            </span>
                            <input type="hidden" name="amount_id" id="amount_id">
                            <input type="hidden" name="request_user_id" id="request_user_id">

                        </div>
                    </div>
                    <div class="col-12 text-center">
                        {{-- <button type="submit" class="btn btn-primary me-1 mt-1">Yes</button> --}}
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
                    <h1 class="mb-1 text-start"><span class="modal_name"></span> Proof</h1>
                    <!-- Vertical Left Tabs start -->
                    <div class="col-xl-12 col-lg-12">
                        <div class="card shadow-none">
                            <div class="card-body p-0 m-0">
                                <div class="nav-vertical">
                                    <div class="tab-content bank_identify" id="bank_proof" style="display: none;">
                                        <div class="geeks" style="height: 80%; width: 100%;">
                                            <img id="frontPart" class="img-thumbnail img img-fluid w-100" src="#">
                                            <embed src="" id="frontPart_pdf" type="application/pdf" width="100%" height="600px" />
                                        </div>
                                    </div>
                                    <div class="tab-content crypto_identify" id="cash_voucher_proof" style="display:none">
                                        <div class="geeks" style="height: 80%; width: 100%;">
                                            <ul class="list-group list-group-flush mt-1">
                                                <li class="d-flex justify-content-between flex-wrap" style="margin-left: 59px !important;">
                                                    <span>INVOICE : <span class="fw-bold invoice content"></span></span>
                                                </li>
                                                <li class="d-flex justify-content-between flex-wrap">
                                                    <span> TRANSACTION ID: <span class="fw-bold transaction content"></span></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Vertical Left Tabs ends -->
                    <div class="col-12 text-right">
                        <button type="reset" class="btn btn-primary waves-effect waves-float waves-light btn_close" data-bs-dismiss="modal" aria-label="Close" style="float: right" ;>
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
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>


<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>

<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>


<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>

<!-- datatable  -->
<script>
    var dt = $('#fund_transfer_tbl').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "lengthChange": true,
        "buttons": true,
        "dom": 'B<"clear">lfrtip',
        buttons: [{
                extend: 'csv',
                text: 'csv',
                className: 'btn btn-success btn-sm',
                action: serverSideButtonAction
            },
            {
                extend: 'copy',
                text: 'Copy',
                className: 'btn btn-success btn-sm',
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
        "ajax": {
            "url": "/admin/manage-report/deposit-request?op=data_table",
            "data": function(d) {
                return $.extend({}, d, $("#filterForm").serializeObject());
            }
        },

        "columns": [{
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
            [5, 'desc']
        ]


    });
    $('#filterBtn').click(function(e) {
        dt.draw();
    });




    /*<---------------Datatable Descriptions Start------------>*/
    $(document).on("click", ".dt-description", function(params) {
        let __this = $(this);
        let id = $(this).data('id');
        let u_id = $(this).data('u_id');
        let modal_name = $(this).data('modal_name');

        $.ajax({
            type: "GET",
            url: '/admin/manage-report/deposit-request-description/' + id,
            dataType: 'json',
            success: function(data) {
                if (data.status == true) {
                    if ($(__this).closest("tr").next().hasClass("description")) {
                        $(__this).closest("tr").next().remove();
                        $(__this).find('.w').html(feather.icons['plus'].toSvg());
                    } else {
                        $(__this).closest('tr').after(data.description);
                        $(__this).closest('tr').next('.description').slideDown('slow').delay(5000);
                        $(__this).find('.w').html(feather.icons['minus'].toSvg());
                    }
                }
            }
        })
    });


    /*<--------------Datatable export function Start----------------->*/
    $(document).on("change", "#fx-export", function() {
        if ($(this).val() === 'csv') {
            $(".buttons-csv").trigger('click');
        }
        if ($(this).val() === 'excel') {
            $(".buttons-excel").trigger('click');
        }

    });

    function serverSideButtonAction(e, dt, node, config) {

        var me = this;
        var button = config.text.toLowerCase();
        if (typeof $.fn.dataTable.ext.buttons[button] === "function") {
            button = $.fn.dataTable.ext.buttons[button]();
        }
        var len = dt.page.len();
        var start = dt.page();
        dt.page(0);

        dt.context[0].aoDrawCallback.push({
            "sName": "ssb",
            "fn": function() {
                $.fn.dataTable.ext.buttons[button].action.call(me, e, dt, node, config);
                dt.context[0].aoDrawCallback = dt.context[0].aoDrawCallback.filter(function(e) {
                    return e.sName !== "ssb"
                });
            }
        });
        dt.page.len(999999999).draw();
        setTimeout(function() {
            dt.page(start);
            dt.page.len(len).draw();
        }, 500);
    }

    /*<--------------Datatable export function End----------------->*/


    /*<---------For reset button script-------------->*/
    $(document).ready(function() {
        $("#resetBtn").click(function() {
            $("#filterForm")[0].reset();
            $('#transaction_type').prop('selectedIndex', 0).trigger("change");
            $('#client_type').prop('selectedIndex', 0).trigger("change");
            $('#verification_status').prop('selectedIndex', 0).trigger("change");
            $('#status').prop('selectedIndex', 2).trigger("change");
            $('#table_id_hidden').val('');
            dt.draw();
        });
    });

    // deposit reqeust approve code
    // ******************************************************************
    $(document).on('click', ".btn-transaction-approve", function() {
        let id = $(this).data('id');
        $(this).confirm2({
            request_url: '/admin/manage-report/deposit-request/approve-request',
            data: {
                id: id
            },
            click: false,
            title: 'Approve deposit',
            message: 'Are you confirm to approve this deposit request?',
            button_text: 'Aprove',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'deposit approve');
            } else {
                notify('error', data.message, 'deposit approve');
            }
            dt.draw();
        });
    })
    /*<!---------------Approve Data request operation End------------------!>*/
    $(document).on('click', ".btn-transaction-declined", function() {
        let id = $(this).data('id');
        $(this).confirm2({
            request_url: '/admin/manage-report/deposit-request/decline-request',
            data: {
                id: id
            },
            input: 'text',
            click: false,
            title: 'Decline deposit',
            message: 'Are you confirm to decline this deposit?',
            button_text: 'Aprove',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'deposit decline');
            } else {
                notify('error', data.message, 'deposit decline');
            }
            dt.draw();
        });
    })
    $(document).on("click", ".edit-amount-button", function() {
        $('#amount_id').val($(this).data('id'));
        $('#request_user_id').val($(this).data('user_id'));
    });


    /*<-------------------Decline request End--------------------->*/

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
            url: '/admin/manage-report/request-amount-view/' + id,
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
            // window.location.reload();
            dt.draw();
        } else {
            notify('error', data.message, 'Amount Update');
        }
        $.validator("amountRequest", data.errors);
    }
    $(document).on('click', '#amountUpdateBtn', function() {
        $(this).prop('disabled', true);
        setTimeout(() => {
            $(this).prop('disabled', false);
        }, 5000);
    })
    //remove modal previous content 

    $(document).on('click', '.identify', function(data) {
        var id = $(this).data('id');

        $('.crypto_identify').hide();
        $('.bank_identify').hide();
        $('#frontPart').attr("src", "");
        $('.modal_name').html("");
        $('.invoice').html("");
        $('.transaction').html("");
        $('#bank-identify-modal').removeClass('bank-identify-modal');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/manage-report/show-modal',
            method: 'GET',
            dataType: 'json',
            data: {
                id: id
            },
            success: function(data) {
                // $('#frontPart').attr("src", data.image_path);
                if (data.file_type === 'image') {
                    $('#frontPart').attr("src", data.file_url).show();
                    $('#frontPart_pdf').hide();
                } else {
                    $('#frontPart_pdf').attr("src", data.file_url).show();
                    $('#frontPart').hide();
                }
                if (data.crypto == true) {
                    $('.crypto_identify').show();
                    $('.modal_name').html(data.modal_name);
                    $('.invoice').html(data.invoice);
                    // $('.transaction').html(data.transaction);
                    $('#bank-identify-modal').addClass('bank-identify-modal');
                }
                if (data.cash == true) {
                    $('.crypto_identify').show();
                    $('.invoice').html(data.invoice);
                    $('.modal_name').html(data.modal_name);
                    $('.transaction').html(data.transaction_type);

                }
                if (data.bank == true) {
                    $('.bank_identify').show();
                    $('.modal_name').html(data.modal_name);
                    $('#bank-identify-modal').removeClass('bank-identify-modal');
                }
                if (data.help2pay == true) {
                    $('.crypto_identify').show();
                    $('.invoice').html(data.invoice);
                    $('.modal_name').html(data.modal_name);
                    $('.transaction').html(data.transaction_id);
                }
                if (data.voucher == true) {
                    $('.crypto_identify').show();
                    $('.invoice').html(data.invoice);
                    $('.modal_name').html(data.modal_name);
                    $('.transaction').html(data.transaction_id);
                }
                if (data.PayPal == true) {
                    $('.crypto_identify').show();
                    $('.invoice').html(data.invoice);
                    $('.modal_name').html(data.modal_name);
                    $('.transaction').html(data.transaction_id);
                }
                if (data.status == false) {
                    $('.crypto_identify').show();
                    $('.invoice').html(data.invoice);
                    $('.transaction').html(data.transaction_id);
                    $('.modal_name').html(data.modal_name);
                }
            }
        });
    });

    $(document).on('click', '.btn_close', function(data) {
        $("#editUser").find('.tab-content').html();
        $("#editUser").modal("hide");
    });
</script>
@stop
<!-- BEGIN: page JS -->