@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Social Trade Manager')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
<!-- number input -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('bootstrap-multiselect-master/dist/css/bootstrap-multiselect.min.css')}}">
<style>
    img.img.img-fluid {
        /* height: 40px; */
        width: 100%;
        margin-top: 0px !important;
    }

    .dt-trader-img img {
        height: 200px !important;
    }

    img.img.img-fluid {
        /* height: 40px; */
        width: 284px !important;
        margin-top: -10px;
    }

    .multiselect-container.dropdown-menu.show {
        width: 100%;
        max-height: 150px;
        overflow: auto;
    }

    .dropdown-item.multiselect-all.active {
        width: 100%;
        margin-bottom: 1rem;
    }

    .multiselect-option.dropdown-item.active {
        width: 100%;
        margin-bottom: 3px;
    }

    .dropdown-item.multiselect-all {
        width: 100%;
        /* margin-bottom: 1rem;
        color: #28c76f; */
        padding-top: 13px;
        font-weight: bold;
    }

    /*multiselect color */
    .dark-layout .multiselect-container .multiselect-option.active label {
        color: #000;
    }

    div.dataTables_wrapper div.dataTables_processing {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 200px;
        margin-left: -100px;
        margin-top: 11px;
        text-align: center;
        padding: 1em 0;
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
                        <h2 class="content-header-title float-start mb-0">Social Trade Manager</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('admin-management.home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Social Trade</a>
                                </li>
                                <li class="breadcrumb-item active">Social Trade Manager
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
        <!-- content body -->
        <div class="content-body">
            <!-- filter card -->
            <div class="card">
                <div class="card-header">
                    <h2>Filter Search</h2>
                </div>
                <div class="card-body">
                    <form id="filter-form" action="scripts/export-pamm_manager_process.php?export=1" method="post">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-2">
                                    <div class="form-group" data-toggle="tooltip" data-original-title="Filter By Finance" id="inputTooltip" title="">
                                        <select id="finance_filter" name="finance_filter" data-plugin-selectTwo class="form-control form-select populate">
                                            <optgroup label="Filter By Finance">
                                                <option value=""> All</option>
                                                <option value="profit_loss"> Profit/Loss</option>
                                                <option value="slave_account"> Slave Account</option>
                                                <option value="volume"> Volume</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-2">
                                    <div class="form-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By MIN MAX Amount Value">
                                        <div class="input-group">
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
                                <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-2">
                                    <div class="form-group" data-toggle="tooltip" data-original-title="Filter By Trade Duration">
                                        <div class="input-group" data-date="2017/01/01" data-date-format="yyyy/mm/dd" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Create Date">
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
                                            <input id="from" type="text" name="date_from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                            <span class="input-group-text">to</span>
                                            <input id="to" type="text" name="date_to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-2">
                                    <div class="form-group" title="" data-toggle="tooltip" data-trigger="hover" data-original-title="Filter By Ratting" id="inputTooltip">
                                        <select id="ratting" name="ratting" data-plugin-selectTwo class="form-control populate form-select">
                                            <option value="">Select A Ratting </option>
                                            <option value="5">5 STAT </option>
                                            <option value="4.5">4.5 STAR</option>
                                            <option value="4">4 STAR</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-2">
                                    <input id="account_filter" name="account_filter" type="text" placeholder="Account Number Email/ Name" title="" data-toggle="tooltip" data-trigger="hover" class="form-control" data-original-title="Filter By Account Number Email/ Name">
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-2">
                                    <input type="text" placeholder="Slave Email or Account Number" title="" data-toggle="tooltip" data-trigger="hover" class="form-control" data-original-title="Filter By Slave Email/Account Number" name="slave_filter" id="slave-filter">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <!-- pamm local/global -->
                                <div class="col-lg-4 col-md-4 col-sm-12 col-12 mb-2">
                                    <div class="form-group" title="" data-toggle="tooltip" data-trigger="hover" data-original-title="Filter By PAMM Type" id="inputTooltip">
                                        <select id="pamm-type-filter" name="pamm_type_filter" data-plugin-selectTwo class="form-control populate form-select">
                                            <option value="">All</option>
                                            <option value="pamm_local">Social Trade Local</option>
                                            <option value="pamm_global">Social Trade Global</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- status active/inactive -->
                                <div class="col-lg-4 col-md-4 col-sm-12 col-12 mb-2">
                                    <div class="form-group" title="" data-toggle="tooltip" data-trigger="hover" data-original-title="Filter By Social Trade Type" id="inputTooltip">
                                        <select id="active-inactive-filter" name="active_inactive_filter" data-plugin-selectTwo class="form-control populate form-select">
                                            <option value="">Select Status</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- button exports -->
                                <div class="col-lg-4 col-md-4 col-sm-12 col-12 mb-2">
                                    <button type="button" id="fx-export" name="Export" class="btn btn-block btn-success w-100">EXPORT</button>
                                </div>
                            </div>
                            <div class="row float-end mt-1">
                                <!-- button reset -->
                                <div class="col-md-6 col-sm-6 col-6">
                                    <button id="btn-reset" type="button" class=" btn btn-block btn-secondary">RESET</button>
                                </div>
                                <!-- button filter -->
                                <div class="col-md-6 col-sm-6 col-6">
                                    <button id="btn-filter" type="button" class=" btn btn-block btn-primary">FILTER</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--/ Form cards -->
        </div>
        <div class="card">
            <!--Search Form -->
            <div class="card-body mt-2">
                <table id="deposit_report_tbl" class="datatables-ajax ib-withdraw table table-responsive">
                    <thead>
                        <tr>
                            <th>{{ __('page.account-number') }}</th>
                            <th>{{ __('page.account-name') }}</th>
                            <th>{{ __('page.username') }}</th>
                            <th>{{ __('page.share-profit') }} </th>
                            <th>{{ __('page.total-slave') }}</th>
                            <th>{{ __('page.volume') }}</th>
                            <th>{{ __('page.profit-loss') }}</th>
                            <th>{{ __('page.status') }}</th>
                            <!-- <th>{{ __('page.action') }}</th> -->
                        </tr>
                    </thead>
                </table>
            </div>
            <hr class="my-0" />
        </div>
    </div>
</div>
<!-- END: Content-->
<!-- modal add slave account -->
<div class="modal fade text-start modal-success" id="modal-add-slave" tabindex="-1" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{route('admin.manager.master-add-slave')}}" method="POST" id="form-add-slave" class="modal-content">
            @csrf
            <input type="hidden" name="account" value="" id="master_account">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">Add Slave Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="account-number">Account Number</label>
                            <input name="account_number" type="text" class="form-control" id="account-number" placeholder="Trading Account Number" />
                        </div>
                    </div>
                    <div class="col-xl-12 col-md-12 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="password">Password</label>
                            <input name="password" type="text" class="form-control" id="password" placeholder="Password" />
                        </div>
                    </div>
                </div>
                <hr>
                <span class="d-block text-center">Risk Management (Advance Settings)</span>
                <hr>
                <!-- symbol -->
                <div class="mb-1 row">
                    <label for="symbol" class="col-sm-3 col-form-label-lg">Copy Symbol</label>
                    <div class="col-sm-9">
                        <select name="symbol[]" id="symbol" class="form-control" multiple="multiple">
                            <?= copy_symbols() ?>
                        </select>
                        <span class="symbol-error d-block text-danger" style="display: none"></span>
                    </div>
                </div>
                <!-- allocation  -->
                <div class="mb-1 row">
                    <label for="allocation" class="col-sm-3 col-form-label-lg">Allocation</label>
                    <div class="col-sm-9">
                        <select name="allocation" id="allocation" class="form-control">
                            <option value="10">10%</option>
                            <option value="20">20%</option>
                            <option value="25">25%</option>
                            <option value="30">30%</option>
                            <option value="40">40%</option>
                            <option value="50">50%</option>
                            <option value="75">75%</option>
                            <option value="100">100%</option>
                            <option value="200">200%</option>
                            <option value="250">250%</option>
                            <option value="500">500%</option>
                            <option value="1000">1000%</option>
                            <option value="1500">1500%</option>
                            <option value="2000">2000%</option>
                            <option value="2500">2500%</option>
                        </select>
                    </div>
                </div>
                <!-- maximum number of trade -->
                <div class="mb-1 row">
                    <label for="max-trade" class="col-sm-3 col-form-label-lg">Max Trade</label>
                    <div class="col-sm-9">
                        <input type="text" name="max_trade" id="max-trade" class="form-control">
                    </div>
                </div>
                <!-- maximum volume -->
                <div class="mb-1 row">
                    <label for="max-volume" class="col-sm-3 col-form-label-lg">Max Volume</label>
                    <div class="col-sm-9">
                        <input type="text" name="max_volume" id="max-volume" class="form-control">
                    </div>
                </div>
                <!-- maximum volume -->
                <div class="mb-1 row">
                    <label for="min-volume" class="col-sm-3 col-form-label-lg">Min Volume</label>
                    <div class="col-sm-9">
                        <input type="text" name="min_volume" id="min-volume" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="add-slave-btn" onclick="_run(this)" data-el="fg" data-form="form-add-slave" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="create_new_slave_call_back" data-btnid="add-slave-btn">Submit Request</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
<!-- end modal add slave account -->
<!-- edit slave account -->
<div class="modal fade text-start modal-success" id="modal-edit-slave" tabindex="-1" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" id="form-edit-slave" action="{{route('admin.manager.master-edit-slave')}}">
            @csrf
            <input type="hidden" name="master_account" id="master_account_edit" value="">
            <div class="modal-header">
                <h5 class="modal-title" id="myModaleditslave">Edit Slave Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="account-number-edit">Account Number</label>
                            <input type="text" class="form-control" name="account_number" id="account-number-edit" placeholder="Trading Account Number" />
                        </div>
                    </div>
                    <div class="col-xl-12 col-md-12 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="password-edit">Password</label>
                            <input type="text" class="form-control" name="password" id="password-edit" placeholder="Password" />
                        </div>
                    </div>
                </div>
                <hr>
                <span class="d-block text-center">Risk Management (Advance Settings)</span>
                <hr>
                <!-- symbol -->
                <div class="mb-1 row">
                    <label for="symbol-edit" class="col-sm-3 col-form-label-lg">Copy Symbol</label>
                    <div class="col-sm-9">
                        <select name="symbol[]" id="symbol-edit" class="form-control" multiple="multiple">
                            <?= copy_symbols() ?>
                        </select>
                        <span class="symbol-error d-block text-danger" style="display: none"></span>
                    </div>
                </div>
                <!-- allocation  -->
                <div class="mb-1 row">
                    <label for="allocation-edit" class="col-sm-3 col-form-label-lg">Allocation</label>
                    <div class="col-sm-9">
                        <select name="allocation" id="allocation-edit" class="form-control">
                            <option value="10">10%</option>
                            <option value="20">20%</option>
                            <option value="25">25%</option>
                            <option value="30">30%</option>
                            <option value="40">40%</option>
                            <option value="50">50%</option>
                            <option value="75">75%</option>
                            <option value="100">100%</option>
                            <option value="200">200%</option>
                            <option value="250">250%</option>
                            <option value="500">500%</option>
                            <option value="1000">1000%</option>
                            <option value="1500">1500%</option>
                            <option value="2000">2000%</option>
                            <option value="2500">2500%</option>
                        </select>
                    </div>
                </div>
                <!-- maximum number of trade -->
                <div class="mb-1 row">
                    <label for="max-trade-edit" class="col-sm-3 col-form-label-lg">Max Trade</label>
                    <div class="col-sm-9">
                        <input type="text" name="max_trade" id="max-trade-edit" class="form-control">
                    </div>
                </div>
                <!-- maximum volume -->
                <div class="mb-1 row">
                    <label for="max-volume-edit" class="col-sm-3 col-form-label-lg">Max Volume</label>
                    <div class="col-sm-9">
                        <input type="text" name="max_volume" id="max-volume-edit" class="form-control">
                    </div>
                </div>
                <!-- maximum volume -->
                <div class="mb-1 row">
                    <label for="min-volume-edit" class="col-sm-3 col-form-label-lg">Min Volume</label>
                    <div class="col-sm-9">
                        <input type="text" name="min_volume" id="min-volume-edit" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="edit-slave-btn" onclick="_run(this)" data-el="fg" data-form="form-edit-slave" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="edit_slave_call_back" data-btnid="edit-slave-btn">Save Change</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
<!-- end edit slave account -->
<div class="modal fade text-start modal-success" id="modal-more--detttails" tabindex="-1" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">More details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Volume</th>
                                <th>Minimum Volume</th>
                                <th>Maximum Volume</th>
                                <th>Group</th>
                                <th>Leverage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="display-volume"></td>
                                <td id="display-min-volume"></td>
                                <td id="display-max-volume"></td>
                                <td id="display-group"></td>
                                <td id="display-leverage"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- modal update pamm profile -->
<div class="modal fade text-start modal-success" id="modal-pamm-profile" tabindex="-1" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">Update Social Trade Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.manager.update-pamm-profile')}}" method="post" id="pamm-update-form">
                    <input type="hidden" name="account" id="hidden-pamm-account">
                    @csrf
                    <!-- account number -->
                    <div class="form-group mb-2">
                        <label for="p-account-number">Account Number</label>
                        <input type="text" name="account_nubmer" class="form-input form-control" id="p-account-number" disabled>
                    </div>
                    <!-- user name -->
                    <div class="form-group mb-2">
                        <label for="user-name">User Name</label>
                        <input type="text" name="user_name" class="form-input form-control" id="user-name">
                    </div>
                    <!-- share profit -->
                    <div class="form-group mb-2">
                        <label for="p-share-profit">Share Profit</label>
                        <input type="text" name="share_profit" class="form-input form-control" id="p-share-profit">
                    </div>
                    <!-- min deposit -->
                    <div class="form-group mb-2">
                        <label for="min-deposit">Min Deposit</label>
                        <input type="text" name="min_deposit" class="form-input form-control" id="min-deposit">
                    </div>
                    <!-- max share profit -->
                    <div class="form-group mb-2">
                        <label for="max-deposit">Max Deposit</label>
                        <input type="text" name="max_deposit" class="form-input form-control" id="max-deposit">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btn-save-pamm-profile" onclick="_run(this)" data-el="fg" data-form="pamm-update-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="update_pamm_profile_callBack" data-btnid="btn-save-pamm-profile">Save Change</button>
            </div>
        </div>
    </div>
</div>
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
<!-- add vendor js from veuxy template -->
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>

<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
<!-- datatable -->
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- number input -->
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')

<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-number-input.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-select2.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/create-manager.js')}}"> </script>
<script src="{{asset('bootstrap-multiselect-master/dist/js/bootstrap-multiselect.min.js')}}"></script>

<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>

<script>
    var datatable;
    window.addEventListener('load', function() {
        datatable = $(".datatables-ajax").fetch_data({
            url: '/admin/pamm/pamm-manager-dt',
            columns: [{
                    'data': 'account'
                },
                {
                    'data': 'name'
                },
                {
                    'data': 'username'
                },
                {
                    'data': 'share_profit'
                },
                {
                    'data': 'total_slave'
                },
                {
                    'data': 'volume'
                },
                {
                    'data': 'profit_loss'
                },
                {
                    'data': 'status'
                },
            ],
            csv_export: true,
            description: true,
            description_dt: true,
            export_col: [0, 1, 2, 3, 4, 5, 6, 7]
        });
        $(document).on("click", "#fx-export", function() {
            console.log('0o');
            if ($(this).val() === 'csv') {
                $(".buttons-csv").trigger('click');
            }
            if ($(this).val() === 'excel') {

            }
            $(".buttons-excel").trigger('click');

        });
        $(document.body).fetch_description({
            description_dt: true,
            inner_col: [{
                    'data': 'account'
                },
                {
                    'data': 'allocation'
                },
                {
                    'data': 'max_number_of_trade'
                },
                {
                    'data': 'platform'
                },
                {
                    'data': 'profit_loss'
                },
                // {'data':'profit_loss'},
                {
                    'data': 'status'
                },
                {
                    'data': 'action'
                },
            ]
        });
    });
    // add new slave account
    $(document).ready(function() {
        $('#symbol').multiselect({
            templates: {
                button: '<button type="button" class="multiselect dropdown-toggle btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span></button>',
            },
            includeSelectAllOption: true,
            selectAllText: true,
            selectAllText: ' Select all',
            buttonClass: 'fx-custom-select',
            inheritClass: false,
            nonSelectedText: 'Plese select symbol',
        });
    });
    $(document).ready(function() {
        $('#symbol-edit').multiselect({
            templates: {
                button: '<button type="button" class="multiselect dropdown-toggle btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span></button>',
            },
            includeSelectAllOption: true,
            selectAllText: true,
            selectAllText: ' Select all',
            buttonClass: 'fx-custom-select',
            inheritClass: false,
            nonSelectedText: 'Plese select symbol',
        });
    });
    $(document).on('click', ".btn-add-slave", function() {
        $("#modal-add-slave").modal('show');
        $("#master_account").val($(this).data('account'));
    });

    $(document).on("click", ".btn-edit", function() {
        console.log($(this).data('masteraccount'));
        $("#modal-edit-slave").modal('show');
        $("#account-number-edit").val($(this).data('account'));
        $("#allocation-edit").val($(this).data('allocation'));
        $("#max-trade-edit").val($(this).data('maxtrade'));
        $("#max-volume-edit").val($(this).data('maxvolume'));
        $("#min-volume-edit").val($(this).data('minvolume'));
        $('#allocation-edit').append(new Option($(this).data('allocation') + '%', $(this).data('allocation'))).prop('selected', true);
        $("#master_account_edit").val($(this).data('masteraccount'));
    });
    // active inactive master account
    $(document).on("click", ".btn-active", function() {
        let data = {
            master_ac: $(this).data('account'),
            op: $(this).data('op')
        };
        let request_url = '/admin/pamm/pamm-manager-active-inactive';
        confirm_alert("Are you confirm? to change active status!", "If you want change this master active status, click OK otherwise click cancel button", request_url, data, 'Master account ' + $(this).data('op'), datatable, false, null, $(this));
    });
    // add new slave account to master
    function create_new_slave_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Add new slave');
        }
        if (data.status == false) {
            notify('error', data.message, 'Add new slave');
        }
        if (data.errors.hasOwnProperty('symbol')) {
            $('.symbol-error').text(data.errors.symbol).fadeIn();
        } else {
            $('.symbol-error').text('').fadeOut();
        }
        $.validator("form-add-slave", data.errors);
    }
    // view more details
    $(document).on("click", ".btn-more-details", function() {
        $("#modal-more--detttails").modal("show");
        $("#display-volume").text($(this).data('volume'));
        $("#display-min-volume").text($(this).data('minvolume'));
        $("#display-max-volume").text($(this).data('maxvolume'));
        $("#display-group").text($(this).data('group'));
        $("#display-leverage").text($(this).data('leverage'));
    });
    // edit slave
    function edit_slave_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Edit new slave');
        }
        if (data.status == false) {
            notify('error', data.message, 'Edit new slave');
            if (data.errors.hasOwnProperty('symbol')) {
                $('.symbol-error-edit').text(data.errors.symbol).fadeIn();
            } else {
                $('.symbol-error-edit').text('').fadeOut();
            }
        }

        $.validator("form-add-slave", data.errors);
    }
    // delete slave account
    $(document).on("click", ".btn-delete", function() {
        let data = {
            account: $(this).data('account'),
            op: 'delete'
        };
        let request_url = '/admin/pamm/pamm-manager-delete slave';
        confirm_alert("Are you confirm? to delete this slave account!", "If you want delete this slave account, click OK otherwise click cancel button", request_url, data, 'Delete slave account', datatable, false, null, $(this));
    });
    // update pamm profile
    // get pamm data inside modal
    $(document).on('click', ".btn-update-pamm", function() {
        let account_number = $(this).data('account');
        $.ajax({
            url: '/admin/pamm/pamm-manager-get-profile/' + account_number,
            dataType: 'JSON',
            method: 'GET',
            success: function(data) {
                if (data.status) {
                    $("#p-account-number").val(data.account_number);
                    $("#hidden-pamm-account").val(data.account_number);
                    $("#user-name").val(data.user_name);
                    $("#p-share-profit").val(data.share_profit);
                    $("#min-deposit").val(data.min_deposit);
                    $("#max-deposit").val(data.max_deposit);
                }
            }
        })
        $("#modal-pamm-profile").modal('show');
    })
    // update pamm profile
    function update_pamm_profile_callBack(data) {
        if (data.success) {
            notify('success', "Updated successfully.", 'Social Trade Profile');
            $("#modal-pamm-profile").modal('hide');
            datatable.draw();
        } else {
            notify('error', "Failed to update!.", 'Social Trade Profile');
        }
    }
</script>
@stop
<!-- BEGIN: page JS -->