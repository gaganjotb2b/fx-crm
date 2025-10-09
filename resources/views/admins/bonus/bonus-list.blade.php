@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Bonus List')
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
    span.input-group-text {
        height: 38px;
    }

    /* for Laptop */
    td,
    th {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    @media screen and (max-width: 1280px) and (min-width: 800px) {

        .ib-withdraw thead tr th:nth-child(4),
        .ib-withdraw tbody tr td:nth-child(4) {
            display: none;
        }

    }



    @media screen and (max-width: 1440px) and (min-width: 900px) {

        .ib-withdraw thead tr th:nth-child(4),
        .ib-withdraw tbody tr td:nth-child(4) {
            display: none;
        }

        .small-none {
            display: none;
        }
    }

    .add_field_button {
        background-color: #34d9ec !important;
    }

    .add_field_button:hover {
        background-color: #34d9ec !important;
    }

    input.form-control.filter-num {
        margin-top: 10px;
    }

    #editor-container {
        height: 375px;
    }

    #group-wrapper>div,
    #group-wrapper-account>div,
    #group-wrapper-reg>div {
        width: 60%;
    }

    #select-all-wrapper,
    #select-all-wrapper-account,
    #select-all-wrapper-reg {
        padding: 8px 14px;
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
                        <h2 class="content-header-title float-start mb-0">Bonus List</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('ib-management.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Offers</a>
                                </li>
                                <li class="breadcrumb-item active">Bonus list</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">
                                <i class="me-1" data-feather="info"></i>
                                <span class="align-middle">Note</span>
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="me-1" data-feather="play"></i>
                                <span class="align-middle">Vedio</span>
                            </a>
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
                                <form id="filterForm" class="dt_adv_search" method="POST">
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-md-4 mb-1">
                                            <label for="bonus-category">Bonus Category</label>
                                            <select class="select2 form-select" name="bonus_category" id="bonus-category">
                                                <optgroup label="Method">
                                                    <option value="">{{__('ad-reports.all')}}</option>
                                                    <option value="deposit">Deposit</option>
                                                    <option value="new_registration">New Registration</option>
                                                    <option value="new_account">New Account</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-4  mb-1">
                                            <label for="active-status">Status</label>
                                            <select class="select2 form-select" name="status" id="active-status">
                                                <optgroup label="Search By Status">
                                                    <option value="">{{__('ad-reports.all')}}</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Disable</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="bonus-for">Bonus For</label>
                                            <select class="select2 form-select" name="bonus_for" id="bonus-for">
                                                <optgroup label="Search By Status">
                                                    <option value="">{{__('ad-reports.all')}}</option>
                                                    <option value="all">All Clients</option>
                                                    <option value="specific_client">Specific Client</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter MIN MAX Amount to Filter">
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
                                            <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Create Date To Filter" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
                                                <span class="input-group-text">
                                                    <div class="icon-wrapper">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
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

                                        <div class="col-md-2">
                                            <button id="resetBtn" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('ad-reports.btn-reset')}}</span>
                                            </button>
                                        </div>
                                        <div class="col-md-2">
                                            <button id="filterBtn" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('category.FILTER')}}</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!--Search Form -->
                            <div class="card-body mt-2">

                                <table id="ib_transfer_tbl" class="datatables-ajax ib-withdraw table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Bonus Name</th>
                                            <th>Amount</th>
                                            <th>Bonus Category</th>
                                            <th>Start/End</th>
                                            <th>Status</th>
                                            <th>Create Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
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
<!-- Modal display group -->
<div class="modal fade text-start modal-success" id="group-modal" tabindex="-1" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">Bonus available groups</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table" id="table-groups">
                    <tr>
                        <th>Group Name: </th>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- modal countries -->
<div class="modal fade text-start modal-success" id="country-modal" tabindex="-1" aria-labelledby="myModalLabel111" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel111">Bonus available Countries</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table" id="table-countries">
                    <tr>
                        <th>Country Name: </th>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- modal clients -->
<div class="modal fade text-start modal-success" id="client-modal" tabindex="-1" aria-labelledby="client-modal-aria" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="client-modal-aria">Bonus available clients</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table" id="table-clients">
                    <tr>
                        <th>Client Email: </th>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- edit modal -->
<div class="modal fade text-start modal-success" id="group-edit-modal" tabindex="-1" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">Bonus for all clients</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.edit.all-client-bonus')}}" method="post" id="all-client-bonus-edit">
                    @csrf
                    <input type="hidden" name="package_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bonus_name_edit">Bonus Name</label>
                                <input type="text" name="bonus_name" id="bonus_name_edit" class="form-control form-input">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bonus_client_edit">clients</label>
                                <select class="select2 form-select" id="bonus_client_edit" name="clients[]" multiple>
                                    <!-- load by ajax -->
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- second row -->
                    <div class="row mt-2">

                        <!-- bonus type -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="credit-type">Credit Type</label>
                                <select name="bonus_type" id="bonus-type-edit" class="select2 form-select">
                                    <option value="free" data-select2-id="3">No Deposit Bonus</option>
                                    <option value="on_deposit" data-select2-id="40">Bonus On Deposit</option>
                                    <option value="first_deposit" data-select2-id="41">Bonus On First Deposit</option>
                                    <option value="specific_deposit" data-select2-id="42">Bonus On Specific Deposit</option>
                                </select>
                            </div>
                        </div>
                        <!-- deposit amount -->
                        <div class="mb-1 col-md-6" id="deposit-amount-wrapper" style="display: none;">
                            <!-- min max deposit -->
                            <div class="mb-1 col-md-12">
                                <label class="form-label" for="modern-bonus-type">Deposit Amount</label>
                                <div class="col-sm-12 d-flex flex-column float-start">
                                    <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top">
                                        <span class="input-group-text">
                                            <div class="icon-wrapper">
                                                Min
                                            </div>
                                        </span>
                                        <input id="from" type="text" name="min_deposit" class="form-control" placeholder="0">
                                        <span class="input-group-text">Max</span>
                                        <input id="to" type="text" name="max_deposit" class="form-control" placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- country/is global -->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="modern-country">country</label>
                            <div class="row">
                                <!-- is global -->
                                <div class="col-md-5">
                                    <div class="title-wrapper d-flex">
                                        <div class="d-flex flex-column float-start">
                                            <div class="form-check form-switch form-check-primary">
                                                <input type="checkbox" id="is_global" class="form-check-input is_global" name="is_global" value="1" />
                                                <label class="form-check-label" for="master">
                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                </label>
                                            </div>
                                        </div>
                                        <label class="todo-title cursor-pointer" for="is_global"><i class="fab fa-facebook-f"></i> Is Global ?</label>
                                    </div>
                                </div>
                                <!-- country -->
                                <div class="col-md-7 fg" id="bonus-country-wrapper">
                                    <div id="country_list">
                                        <div class="row">
                                            <select class="select2 form-select" name="country[]" placeholder="select country" multiple="multiple" id="bonus-country">
                                                <option value="">Choose country</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-1 form-password-toggle col-md-6">
                            <!-- groups -->
                            <label class="form-label" for="modern-group">Groups</label>
                            <div class="input-group form-password-toggle mb-2" id="group-wrapper">
                                <select class="select2 form-select w-100" id="client-groups" name="client_groups[]" multiple>
                                    <!-- get client groups from ajax -->
                                </select>
                                <span class="input-group-text1 border" id="select-all-wrapper">
                                    <span class="form-check form-check-success">
                                        <input type="checkbox" class="form-check-input" id="colorCheck31" />
                                        <label class="form-check-label" for="colorCheck31">Select All</label>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="mb-1 col-md-6">
                            <!-- credit type -->
                            <label class="form-label" for="modern-confirm-password">Credit Type</label>
                            <div class="demo-inline-spacing">
                                <div class="form-check form-check-success mt-0">
                                    <input type="radio" id="customColorRadio3" name="credit_type" class="form-check-input" value="percent" checked />
                                    <label class="form-check-label" for="customColorRadio3">Percent ?</label>
                                </div>
                                <div class="form-check form-check-warning mt-0">
                                    <input type="radio" id="customColorRadio4" name="credit_type" class="form-check-input" value="fixed" />
                                    <label class="form-check-label" for="customColorRadio4">Fixed ?</label>
                                </div>
                            </div>
                        </div>
                        <!-- bonus amount -->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="modern-credit-amount">Credit Amount</label>
                            <input type="text" class="form-control form-input" name="credit_amount" id="credit-amount" placeholder="0">
                        </div>
                        <!--  -->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="modern-credit-amount">Maximum Bonus</label>
                            <input type="text" class="form-control form-input" name="maximum_bonus" id="maximum-bonus-amount" placeholder="0">
                        </div>
                        <!-- crdit expire -->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="modern-credit-amount">Credit Expire</label>
                            <input type="text" class="form-control form-input" name="credit_expire" id="credit-expire" placeholder="Expire After">
                        </div>
                        <!-- expire after -->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="modern-credit-amount">&nbsp;</label>
                            <select class="select2 form-select w-100" id="expire-after" name="expire">
                                <option value="days">Days</option>
                                <option value="months">Months</option>
                                <option value="years">Years</option>
                            </select>
                        </div>
                        <!-- date range -->
                        <div class="mb-1 col-md-12">
                            <label class="form-label" for="modern-credit-amount">Date Range</label>
                            <div class="col-sm-12 d-flex flex-column float-start">
                                <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Date" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
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

                                    <input id="from-edit" type="text" name="date_from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                    <span class="input-group-text">to</span>

                                    <input id="to-edit" type="text" name="date_to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- all client bonus submit button -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success" type="button" id="btn-save-change-all" data-loader="loader">
                    <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                    <span class="align-middle d-sm-inline-block d-none">Save Changes</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- edit new registrtion bonus -->
<div class="modal fade text-start modal-success" id="group-edit-modal-register" tabindex="-1" aria-labelledby="myModalLabel110-register" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110-register">Bonus for new registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.edit.new-reg-bonus')}}" method="post" id="reg-client-bonus-edit">
                    @csrf
                    <input type="hidden" id="reg-bonus-id" name="package_id">
                    <div class="row">
                        <!-- bonus name -->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="modern-bonus-type">Bonus Name</label>
                            <input type="text" name="bonus_name" class="form-control form-input">
                        </div>
                        <!-- bonus type -->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="bonus-type-reg">Bonus type</label>
                            <select class="select2 form-select" id="bonus-type-reg" name="bonus_type">
                                <option value="free">No Deposit Bonus</option>
                                <option value="on_deposit">Bonus On Deposit</option>
                                <option value="first_deposit">Bonus On First Deposit</option>
                                <option value="specific_deposit">Bonus On Specific Deposit</option>
                            </select>
                        </div>
                        <div class="mb-1 col-md-6" id="deposit-amount-wrapper-reg" style="display: none;">
                            <!-- min max deposit -->
                            <div class="mb-1 col-md-12">
                                <label class="form-label" for="modern-bonus-type-reg">Deposit Amount</label>
                                <div class="col-sm-12 d-flex flex-column float-start">
                                    <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top">
                                        <span class="input-group-text">
                                            <div class="icon-wrapper">
                                                Min
                                            </div>
                                        </span>
                                        <input id="min-deposit-reg" type="text" name="min_deposit" class="form-control" placeholder="0">
                                        <span class="input-group-text">Max</span>
                                        <input id="max-deposit-reg" type="text" name="max_deposit" class="form-control" placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="modern-country">country</label>
                            <div class="row">
                                <!-- is global -->
                                <div class="col-md-5">
                                    <div class="title-wrapper d-flex">
                                        <div class="d-flex flex-column float-start">
                                            <div class="form-check form-switch form-check-primary">
                                                <input type="checkbox" id="is_global_reg" class="form-check-input is_global" name="is_global" value="1" />
                                                <label class="form-check-label" for="master">
                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                </label>
                                            </div>
                                        </div>
                                        <label class="todo-title cursor-pointer" for="is_global_reg"><i class="fab fa-facebook-f"></i> Is Global ?</label>
                                    </div>
                                </div>
                                <!-- country -->
                                <div class="col-md-7 fg" id="bonus-country-wrapper-reg">
                                    <div id="country_list_reg">
                                        <div class="row">
                                            <select class="select2 form-select" name="country[]" placeholder="select country" multiple="multiple" id="bonus-country-reg">
                                                <!-- need to ajax call function -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-1 form-password-toggle col-md-6">
                            <!-- groups -->
                            <label class="form-label" for="modern-group">Groups</label>
                            <div class="input-group form-password-toggle mb-2" id="group-wrapper-reg">
                                <select class="select2 form-select w-100" id="client-groups-reg" name="client_groups[]" multiple>
                                    <!-- get client groups from ajax -->
                                </select>
                                <span class="input-group-text1 border" id="select-all-wrapper-reg">
                                    <span class="form-check form-check-success">
                                        <input type="checkbox" class="form-check-input" id="group-select-all-reg" />
                                        <label class="form-check-label" for="group-select-all-reg">Select All</label>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="mb-1 col-md-6">
                            <!-- credit type -->
                            <label class="form-label" for="modern-confirm-password">Credit Type</label>
                            <div class="demo-inline-spacing">
                                <div class="form-check form-check-success mt-0">
                                    <input type="radio" id="credit-type-reg" name="credit_type" class="form-check-input" value="percent" checked />
                                    <label class="form-check-label" for="credit-type-reg">Percent ?</label>
                                </div>
                                <div class="form-check form-check-warning mt-0">
                                    <input type="radio" id="credit-type-fixed-reg" name="credit_type" class="form-check-input" value="fixed" />
                                    <label class="form-check-label" for="credit-type-fixed-reg">Fixed ?</label>
                                </div>
                            </div>
                        </div>
                        <!-- bonus amount -->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="bonus-amount-reg">Credit Amount</label>
                            <input type="text" class="form-control form-input" name="credit_amount" id="bonus-amount-reg" placeholder="0">
                        </div>
                        <!-- maximum bonus -->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="maximum-bonus-reg">Maximum Bonus</label>
                            <input type="text" class="form-control form-input" name="maximum_bonus" id="maximum-bonus-reg" placeholder="0">
                        </div>
                        <!-- crdit expire -->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="credit-amount-expire-reg">Credit Expire</label>
                            <input type="text" class="form-control form-input" name="credit_expire" id="credit-amount-expire-reg" placeholder="Expire After">
                        </div>
                        <!-- credit expire after -->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="expire-after-reg">&nbsp;</label>
                            <select class="select2 form-select w-100" id="expire-after-reg" name="expire">
                                <option value="days">Days</option>
                                <option value="months">Months</option>
                                <option value="years">Years</option>
                            </select>
                        </div>
                        <!-- date range -->
                        <div class="mb-1 col-md-12">
                            <label class="form-label" for="modern-credit-amount">Date Range</label>
                            <div class="col-sm-12 d-flex flex-column float-start">
                                <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Date" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
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
                                    <input id="from-reg" type="text" name="date_from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                    <span class="input-group-text">to</span>
                                    <input id="to-reg" type="text" name="date_to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- spinner -->
            <div id="loader" data-loader="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success" type="button" id="btn-reg-save-change" data-loader="loader" data-form="reg-client-bonus-edit">
                    <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                    <span class="align-middle d-sm-inline-block d-none">Save Changes</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- edit new account bonus /new account-->
<div class="modal fade text-start modal-success" id="modal-new-account-bonus" tabindex="-1" aria-labelledby="new-account-bonus-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="new-account-bonus-modal">Bonus for new account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- form new account bonus /new account -->
                <form action="{{route('admin.edit.new-account-bonus')}}" method="post" id="form-new-account-bonus">
                    @csrf
                    <input type="hidden" id="reg-bonus-id" name="package_id">
                    <div class="row">
                        <!-- bonus name /new account-->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="modern-bonus-type">Bonus Name</label>
                            <input type="text" name="bonus_name" class="form-control form-input">
                        </div>
                        <!-- bonus type /new account -->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="bonus-type-account">Bonus type</label>
                            <select class="select2 form-select" id="bonus-type-account" name="bonus_type">
                                <option value="free">No Deposit Bonus</option>
                                <option value="on_deposit">Bonus On Deposit</option>
                                <option value="first_deposit">Bonus On First Deposit</option>
                                <option value="specific_deposit">Bonus On Specific Deposit</option>
                            </select>
                        </div>
                        <div class="mb-1 col-md-6" id="deposit-amount-wrapper-account" style="display: none;">
                            <!-- min max deposit
                            /new account
                            -->
                            <div class="mb-1 col-md-12">
                                <label class="form-label" for="modern-bonus-type-account">Deposit Amount</label>
                                <div class="col-sm-12 d-flex flex-column float-start">
                                    <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top">
                                        <span class="input-group-text">
                                            <div class="icon-wrapper">
                                                Min
                                            </div>
                                        </span>
                                        <input id="min-deposit-account" type="text" name="min_deposit" class="form-control" placeholder="0">
                                        <span class="input-group-text">Max</span>
                                        <input id="max-deposit-account" type="text" name="max_deposit" class="form-control" placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="modern-country-new-account">country</label>
                            <div class="row">
                                <!-- is global 
                                /new account
                                -->
                                <div class="col-md-5">
                                    <div class="title-wrapper d-flex">
                                        <div class="d-flex flex-column float-start">
                                            <div class="form-check form-switch form-check-primary">
                                                <input type="checkbox" id="is_global_account" class="form-check-input is_global" name="is_global" value="1" />
                                                <label class="form-check-label" for="master">
                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                </label>
                                            </div>
                                        </div>
                                        <label class="todo-title cursor-pointer" for="is_global_account"><i class="fab fa-facebook-f"></i> Is Global ?</label>
                                    </div>
                                </div>
                                <!-- country 
                                /new account
                                -->
                                <div class="col-md-7 fg" id="bonus-country-wrapper-account">
                                    <div id="country_list_new-account">
                                        <div class="row">
                                            <select class="select2 form-select" name="country[]" placeholder="select country" multiple="multiple" id="bonus-country-account">
                                                <!-- need to ajax call function -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-1 form-password-toggle col-md-6">
                            <!-- groups /new account -->
                            <label class="form-label" for="modern-group-account">Groups</label>
                            <div class="input-group form-password-toggle mb-2" id="group-wrapper-account">
                                <select class="select2 form-select w-100" id="client-groups-account" name="client_groups[]" multiple>
                                    <!-- get client groups from ajax -->
                                </select>
                                <span class="input-group-text1 border" id="select-all-wrapper-account">
                                    <span class="form-check form-check-success">
                                        <input type="checkbox" class="form-check-input" id="group-select-all-account" />
                                        <label class="form-check-label" for="group-select-all-account">Select All</label>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="mb-1 col-md-6">
                            <!-- credit type /new account -->
                            <label class="form-label" for="modern-confirm-password">Credit Type</label>
                            <div class="demo-inline-spacing">
                                <div class="form-check form-check-success mt-0">
                                    <input type="radio" id="credit-type-account" name="credit_type" class="form-check-input" value="percent" checked />
                                    <label class="form-check-label" for="credit-type-account">Percent ?</label>
                                </div>
                                <div class="form-check form-check-warning mt-0">
                                    <input type="radio" id="credit-type-fixed-account" name="credit_type" class="form-check-input" value="fixed" />
                                    <label class="form-check-label" for="credit-type-fixed-account">Fixed ?</label>
                                </div>
                            </div>
                        </div>
                        <!-- bonus amount /new account -->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="bonus-amount-account">Credit Amount</label>
                            <input type="text" class="form-control form-input" name="credit_amount" id="bonus-amount-account" placeholder="0">
                        </div>
                        <!-- maximum bonus /new account -->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="maximum-bonus-account">Maximum Bonus</label>
                            <input type="text" class="form-control form-input" name="maximum_bonus" id="maximum-bonus-account" placeholder="0">
                        </div>
                        <!-- crdit expire /new account -->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="credit-amount-expire-account">Credit Expire</label>
                            <input type="text" class="form-control form-input" name="credit_expire" id="credit-amount-expire-account" placeholder="Expire After">
                        </div>
                        <!-- credit expire after /new account -->
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="expire-after-account">&nbsp;</label>
                            <select class="select2 form-select w-100" id="expire-after-account" name="expire">
                                <option value="days">Days</option>
                                <option value="months">Months</option>
                                <option value="years">Years</option>
                            </select>
                        </div>
                        <!-- date range /new account -->
                        <div class="mb-1 col-md-12">
                            <label class="form-label" for="date-range-account">Date Range</label>
                            <div class="col-sm-12 d-flex flex-column float-start">
                                <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Date" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
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
                                    <input id="from-account" type="text" name="date_from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                    <span class="input-group-text">to</span>
                                    <input id="to-account" type="text" name="date_to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success" type="button" id="btn-account-save-change" data-loader="loader" data-form="form-new-account-bonus">
                    <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                    <span class="align-middle d-sm-inline-block d-none">Save Changes</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js')}}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
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
<script src="{{asset('common-js/rz-plugins/get-edit-data.js')}}"></script>
<script src="{{asset('common-js/select2-get-country.js')}}"></script>
<script src="{{asset('common-js/rz-plugins/rz-ajax.js')}}"></script>

<!-- datatable  -->
<script>
    // select all select2
    $("#colorCheck31").click(function() {
        if ($("#colorCheck31").is(':checked')) {
            $('#client-groups').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#client-groups').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
    // select all select2
    $("#group-select-all-reg").click(function() {
        if ($("#group-select-all-reg").is(':checked')) {
            $('#client-groups-reg').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#client-groups-reg').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
    // select all select2
    $("#group-select-all-account").click(function() {
        if ($("#group-select-all-account").is(':checked')) {
            $('#client-groups-account').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#client-groups-account').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
    // get country to edit
    $('#bonus-country').get_country({
        modal_id: "#group-edit-modal"
    });
    // display min max deposit
    min_max_deposit();
    min_max_deposit_reg();
    min_max_deposit_account();
    // for all clients bonus
    function min_max_deposit() {
        if ($("#bonus-type").val() === 'specific_deposit') {
            $("#deposit-amount-wrapper").slideDown();
        } else {
            $("#deposit-amount-wrapper").slideUp();
        }
    }
    // for new register bonus
    function min_max_deposit_reg() {
        if ($("#bonus-type-reg").val() === 'specific_deposit') {
            $("#deposit-amount-wrapper-reg").slideDown();
        } else {
            $("#deposit-amount-wrapper-reg").slideUp();
        }
    }
    // for new account bonus
    function min_max_deposit_account() {
        if ($("#bonus-type-account").val() === 'specific_deposit') {
            $("#deposit-amount-wrapper-account").slideDown();
        } else {
            $("#deposit-amount-wrapper-account").slideUp();
        }
    }
    // for all client bonus
    $("#bonus-type").on("change", function() {
        min_max_deposit();
    });
    // for new register bonus
    $("#bonus-type-reg").on("change", function() {
        min_max_deposit_reg();
    });
    // for new account bonus
    $("#bonus-type-account").on("change", function() {
        min_max_deposit_account();
    });
    // display country
    display_country();
    display_country_reg();
    display_country_account();
    // for account bonus
    function display_country() {
        if ($("#is_global").is(':checked')) {
            $("#bonus-country-wrapper").slideUp();
        } else {
            $("#bonus-country-wrapper").slideDown();
        }
    }
    // for new register
    function display_country_reg() {
        if ($("#is_global_reg").is(':checked')) {
            $("#bonus-country-wrapper-reg").slideUp();
        } else {
            $("#bonus-country-wrapper-reg").slideDown();
        }
    }
    // for new account
    function display_country_account() {
        if ($("#is_global_account").is(':checked')) {
            $("#bonus-country-wrapper-account").slideUp();
        } else {
            $("#bonus-country-wrapper-account").slideDown();
        }
    }
    // for all clients
    $("#is_global").on("change", function() {
        display_country();
    });
    // for new register
    $("#is_global_reg").on("change", function() {
        display_country_reg();
    });
    // for new account
    $("#is_global_account").on("change", function() {
        display_country_account();
    });
    // btn view group
    $(document).on('click', '.btn-view-group', function() {
        $("#group-modal").modal('show');
        let package_id = $(this).data('package_id');
        $.ajax({
            url: '/admin/bonus/get-bonus-groups',
            dataType: 'JSON',
            method: 'GET',
            data: {
                package_id: package_id
            },
            success: function(data) {
                $('#table-groups').find('tr').remove();
                $.each(data, function(index, value) {
                    $("#table-groups").append('<tr><th>Group Name: </th><td>' + value.group_name + '</td></tr>');
                })
            }
        });
    });
    // display bonus countryes
    $(document).on('click', '.btn-view-country', function() {
        $("#country-modal").modal('show');
        let package_id = $(this).data('package_id');
        $.ajax({
            url: '/admin/bonus/get-bonus-countries',
            dataType: 'JSON',
            method: 'GET',
            data: {
                package_id: package_id
            },
            success: function(data) {
                $('#table-countries').find('tr').remove();
                $.each(data, function(index, value) {
                    $("#table-countries").append('<tr><th>Group Name: </th><td>' + value.country + '</td></tr>');
                })
            }
        });
    });
    // display bonus clients
    $(document).on('click', '.btn-view-client', function() {
        $("#client-modal").modal('show');
        let package_id = $(this).data('package_id');
        $.ajax({
            url: '/admin/bonus/get-bonus-client',
            dataType: 'JSON',
            method: 'GET',
            data: {
                package_id: package_id
            },
            success: function(data) {
                $('#table-clients').find('tr').remove();
                $.each(data, function(index, value) {
                    $("#table-clients").append('<tr><th>Client Email: </th><td>' + value.email + '</td></tr>');
                })
            }
        });
    });
    // datatable
    $(document).ready(function() {

        var dt = $('#ib_transfer_tbl').DataTable({
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
                    exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },
                    action: serverSideButtonAction
                },
                {
                    extend: 'copy',
                    text: 'Copy',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },
                    action: serverSideButtonAction
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    className: 'btn btn-warning btn-sm',
                    exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },
                    action: serverSideButtonAction
                },
                {
                    extend: 'pdf',
                    text: 'pdf',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },
                    action: serverSideButtonAction
                }
            ],
            "ajax": {
                "url": "/admin/bonus/bonus-list-process",
                "data": function(d) {
                    return $.extend({}, d, {
                        "from": $("#from").val(),
                        "to": $("#to").val(),
                        "min": $("#min").val(),
                        "max": $("#max").val(),
                        "verification": $('#verification').val(),
                        "approved_status": $("#approved_status").val(),
                        "info": $("#info").val(),
                    });
                }
            },

            "columns": [{
                    "data": "bonus_name"
                },
                {
                    "data": "price"
                },
                {
                    "data": "bonus_category"
                },
                {
                    "data": "start_end"
                },
                {
                    "data": "status"
                },
                {
                    "data": "create_date"
                },
                {
                    "data": "action"
                },

            ],

            "drawCallback": function(settings) {
                $("#filterBtn").html("FILTER");
                $("#total_amount").html('$' + settings.json.total_amount);

                var rows = this.fnGetData();
                if (rows.length !== 0) {
                    feather.replace();
                }
            }
        });
        $('#filterBtn').click(function(e) {
            dt.draw();
        });
        $("#resetBtn").click(function() {
            $("#filterForm")[0].reset();
            $('#verification').prop('selectedIndex', 0).trigger("change");
            $('#approved_status').prop('selectedIndex', 0).trigger("change");
            $('#platform').prop('selectedIndex', 0).trigger("change");
            $('#info').prop('selectedIndex', 0).trigger("change");
            dt.draw();
        });
        // get client groups
        $("#client-groups-reg").get_select2({
            modal_id: '#group-edit-modal-register',
            url: '/search/client_group',
            placeholder: "Choose a group",
        });
        $("#bonus_client_edit").get_select2({
            modal_id: '#group-edit-modal',
            url: '/search/clients',
            placeholder: "Choose a client",
        });
        // edit package
        $(document).on("click", ".btn-edit-package", function() {

            if ($(this).data('bonus_for') === 'all') {
                $("#group-edit-modal").modal('show');
            } else if ($(this).data('bonus_for') === 'new_registration') {
                $("#group-edit-modal-register").modal('show');
            }
            // bonus for new account
            else if ($(this).data('bonus_for') === 'new_account') {
                $("#modal-new-account-bonus").modal('show');
            }
            $.ajax({
                url: '/admin/bonus/get-bonus-data-single',
                data: {
                    id: $(this).data('package_id'),
                },
                success: function(data) {
                    if (data.bonus.bonus_for === 'all') {
                        // bonus name
                        $("#group-edit-modal").find('input[name="bonus_name"]').val(data.bonus.pkg_name);
                        // bonus client
                        // groups
                        var client_options;
                        $("#group-edit-modal").find("#bonus_client_edit").html('');
                        $.each(data.clients, function(index, value) {
                            client_options = $("<option selected='selected'></option>").val(value.user_id).text(value.email);
                            $("#group-edit-modal").find("#bonus_client_edit").append(client_options);
                        });
                        $("#group-edit-modal").find("#bonus_client_edit").trigger('change');
                        // bonus package id
                        $("#group-edit-modal").find('input[name="package_id"]').val(data.bonus.id);
                        // bonus type select2

                        $("#group-edit-modal").find("select[name='bonus_type']").val(data.bonus.bonus_type).trigger('change');
                        // bonus country
                        if (data.bonus.is_global == 1) {
                            $("#group-edit-modal").find("#bonus-country-wrapper").slideUp();
                            $("#group-edit-modal").find("input[name='is_global']").prop('checked', true);
                        } else {
                            $("#group-edit-modal").find("input[name='is_global']").prop('checked', false);
                            var country_option
                            $("#group-edit-modal").find("#bonus-country-reg").html('');
                            $.each(data.bonus_country, function(index, value) {
                                country_option = $("<option selected='selected'></option>").val(value.country).text(value.name);
                                $("#group-edit-modal").find("#bonus-country-reg").append(country_option);
                            });
                            $("#group-edit-modal").find("#bonus-country-reg").trigger('change');
                            $("#group-edit-modal").find("#bonus-country-wrapper").slideDown();
                        }
                        // groups
                        var group_options
                        $("#group-edit-modal").find("#client-groups").html('');
                        $.each(data.groups, function(index, value) {
                            group_options = $("<option selected='selected'></option>").val(value.group_id).text(value.group_name);
                            $("#group-edit-modal").find("#client-groups").append(group_options);
                        });
                        $("#group-edit-modal").find("#client-groups").trigger('change');
                        // credit type
                        if (data.bonus.credit_type.toLowerCase() == 'percent') {
                            $("#credit-type").prop('checked', true);
                        } else {
                            $("#credit-type-fixed").prop('checked', true);
                        }
                        // credit amount
                        $('#credit-amount').val(data.bonus.bonus_amount);
                        $('#maximum-bonus-amount').val(data.bonus.max_bonus);
                        // credit expire
                        $("#credit-expire").val(data.bonus.expire_after);
                        // expireation type
                        $("#expire-after").val(data.bonus.expire_type).trigger('change');
                        // date range
                        $("#from-edit").val(data.bonus.start_date);
                        $("#to-edit").val(data.bonus.end_date);
                    }
                    // bonus for new registration
                    else if (data.bonus.bonus_for === 'new_registration') {
                        $("#group-edit-modal-register").find('input[name="bonus_name"]').val(data.bonus.pkg_name);
                        $("#group-edit-modal-register").find('input[name="package_id"]').val(data.bonus.id);
                        // bonus type select2
                        $("#group-edit-modal-register").find("select[name='bonus_type']").val(data.bonus.bonus_type).trigger('change');
                        // min deposit
                        $('#group-edit-modal-register').find("input[name='min_deposit']").val(data.bonus.min_deposit);
                        // bonus country
                        if (data.bonus.is_global == 1) {
                            $("#group-edit-modal-register").find("#bonus-country-wrapper").slideUp();
                            $("#group-edit-modal-register").find("input[name='is_global']").prop('checked', true);
                        } else {
                            $("#group-edit-modal-register").find("input[name='is_global']").prop('checked', false);
                            var country_option
                            $("#group-edit-modal-register").find("#bonus-country-reg").html('');
                            $.each(data.bonus_country, function(index, value) {
                                country_option = $("<option selected='selected'></option>").val(value.country).text(value.name);
                                $("#group-edit-modal-register").find("#bonus-country-reg").append(country_option);
                            });
                            $("#group-edit-modal-register").find("#bonus-country-reg").trigger('change');
                            $("#group-edit-modal-register").find("#bonus-country-wrapper").slideDown();
                        }
                        // groups
                        var group_options
                        $("#group-edit-modal-register").find("#client-groups-reg").html('');
                        $.each(data.groups, function(index, value) {
                            group_options = $("<option selected='selected'></option>").val(value.group_id).text(value.group_name);
                            $("#group-edit-modal-register").find("#client-groups-reg").append(group_options);
                        });
                        $("#group-edit-modal-register").find("#client-groups-reg").trigger('change');
                        // credit type
                        if (data.bonus.credit_type.toLowerCase() == 'percent') {
                            $("#credit-type-reg").prop('checked', true);
                        } else {
                            $("#credit-type-fixed-reg").prop('checked', true);
                        }
                        // credit amount
                        $('#bonus-amount-reg').val(data.bonus.bonus_amount);
                        $('#maximum-bonus-reg').val(data.bonus.max_bonus);
                        // credit expire
                        $("#credit-amount-expire-reg").val(data.bonus.expire_after);
                        // expireation type
                        $("#expire-after-reg").val(data.bonus.expire_type).trigger('change');
                        // date range
                        // start date
                        $("#from-reg").val(data.bonus.start_date);
                        $("#to-reg").val(data.bonus.end_date);
                    }
                    // bonus for new account
                    else if (data.bonus.bonus_for === 'new_account') {
                        // bonus name
                        $("#modal-new-account-bonus").find('input[name="bonus_name"]').val(data.bonus.pkg_name);
                        // package id
                        $("#modal-new-account-bonus").find('input[name="package_id"]').val(data.bonus.id);
                        // bonus type select2
                        $("#modal-new-account-bonus").find("select[name='bonus_type']").val(data.bonus.bonus_type).trigger('change');
                        // bonus country
                        if (data.bonus.is_global == 1) {
                            $("#modal-new-account-bonus").find("#bonus-country-wrapper").slideUp();
                            $("#modal-new-account-bonus").find("input[name='is_global']").prop('checked', true);
                        } else {
                            $("#modal-new-account-bonus").find("input[name='is_global']").prop('checked', false);
                            var country_option
                            $("#modal-new-account-bonus").find("#bonus-country-account").html('');
                            $.each(data.bonus_country, function(index, value) {
                                country_option = $("<option selected='selected'></option>").val(value.country).text(value.name);
                                $("#modal-new-account-bonus").find("#bonus-country-account").append(country_option);
                            });
                            $("#modal-new-account-bonus").find("#bonus-country-account").trigger('change');
                            $("#modal-new-account-bonus").find("#bonus-country-wrapper").slideDown();
                        }
                        // groups
                        var group_options
                        $("#modal-new-account-bonus").find("#client-groups-account").html('');
                        $.each(data.groups, function(index, value) {
                            group_options = $("<option selected='selected'></option>").val(value.group_id).text(value.group_name);
                            $("#modal-new-account-bonus").find("#client-groups-account").append(group_options);
                        });
                        $("#modal-new-account-bonus").find("#client-groups-account").trigger('change');
                        // credit type
                        if (data.bonus.credit_type.toLowerCase() == 'percent') {
                            $("#credit-type-account").prop('checked', true);
                        } else {
                            $("#credit-type-fixed-account").prop('checked', true);
                        }
                        // credit amount
                        $('#bonus-amount-account').val(data.bonus.bonus_amount);
                        $('#maximum-bonus-account').val(data.bonus.max_bonus);
                        // credit expire
                        $("#credit-amount-expire-account").val(data.bonus.expire_after);
                        // expireation type
                        $("#expire-after-account").val(data.bonus.expire_type).trigger('change');
                        // date range
                        // start date
                        $("#from-account").val(data.bonus.start_date);
                        $("#to-account").val(data.bonus.end_date);
                    }
                }
            });
        });
        // save change for new registration
        $("#btn-reg-save-change").form_submit({
            file: false,
            form_id: "reg-client-bonus-edit",
            title: 'Edit Bonus',
            datatable: dt,
            reset: false,
        });
        // save change all client bonus
        $("#btn-save-change-all").form_submit({
            file: false,
            form_id: "all-client-bonus-edit",
            title: 'Edit Bonus',
            datatable: dt,
            reset: false,
        });
        // save change new account bonus
        $("#btn-account-save-change").form_submit({
            file: false,
            form_id: "form-new-account-bonus",
            title: 'Edit Bonus',
            datatable: dt,
            reset: false,
        });
    });

    // manage is global
    function is_global_switch() {

    }

    //    datatable descriptions
    $(document).on("click", ".dt-description", function(params) {

        let __this = $(this);
        let id = $(this).data('id');
        //    let table_id=$(this).data('table_id');
        //    console.log(table_id);
        $.ajax({
            type: "GET",
            url: '/admin/bonus/bonus-list-details/' + id,
            dataType: 'json',
            success: function(data) {
                if (data.status == true) {
                    if ($(__this).closest("tr").next().hasClass("description")) {
                        $(__this).closest("tr").next().remove();
                        $(__this).find('.w').html(feather.icons['plus'].toSvg());
                    } else {
                        $(__this).closest('tr').after(data.description);
                        $(__this).closest('tr').next('.description').slideDown('slow').delay(5000);
                        // $(__this).find('svg').remove();
                        $(__this).find('.w').html(feather.icons['minus'].toSvg());

                        //Inner datatable
                        //     if ($(__this).closest('tr').next('.description').find('.withdraw-account-details').length) {
                        //     $(__this).closest('tr').next('.description').find('.withdraw-account-details').DataTable().clear().destroy();
                        //     var cd = (new Date()).toISOString().split('T')[0];
                        //     var dt_trading_account = $(__this).closest('tr').next('.description').find('.withdraw-account-details').DataTable({
                        //         "processing": true,
                        //         "serverSide": true,
                        //         "searching": false,
                        //         "lengthChange": false,
                        //         "dom": 'Bfrtip',
                        //         "ajax": { "url": "/admin/manage-report/trading-account-inner-fetch-data/" + user_id },
                        //         "columns": [
                        //             { "data": "acount_number" },
                        //             { "data": "platform" },
                        //             { "data": "group" },
                        //             { "data": "leverage" },
                        //             { "data": "date" },
                        //         ],
                        //         "order": [[1, 'desc']],
                        //         "drawCallback": function (settings) {
                        //         var rows = this.fnGetData();
                        //         if (rows.length !== 0) {
                        //             feather.replace();
                        //          }
                        //         }
                        //     });
                        //   }
                    }
                }
            }
        })
    });

    // datatable export function
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

        // Assim que ela acabar de desenhar todas as linhas eu executo a função do botão.
        // ssb de serversidebutton
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


    /*<-------------------Decline request End--------------------->*/
</script>


@stop
<!-- BEGIN: page JS -->