@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Contest List Report')
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

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/pages/app-calendar.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css')}}">
<style>
    span.input-group-text {
        height: 38px;
    }

    .btn-close.text-dark.btn-popup-close {
        position: absolute;
        right: 19px;
        top: 68px;
        z-index: 2;
    }

    .modal-content {
        width: 750px;
    }
</style>
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content contest-white">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Reward List</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('ib-management.Home')}}</a>
                                </li>
                                {{-- <li class="breadcrumb-item"><a href="#">{{__('admin-breadcrumbs.manage_request')}}</a> --}}
                                </li>
                                <li class="breadcrumb-item active">Reward
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
                                    <div class="row g-1 mb-md-1">
                                        <!-- filter by status -->
                                        <div class="col-md-4 mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Status">
                                            <select class="select2 form-select" name="status" id="filter-status">
                                                <optgroup label="status">
                                                    <option value="">{{__('ad-reports.all')}}</option>
                                                    <option value="active">Active</option>
                                                    <option value="closed">Closed</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <!-- filter by client type -->
                                        <div class="col-md-4  mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Client Type">
                                            <select class="select2 form-select" name="client_type" id="approved_status">
                                                <optgroup label="Search client type">
                                                    <option value="">{{__('ad-reports.all')}}</option>
                                                    <option value="trader">Trader</option>
                                                    <option value="ib">IB</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <!-- filter by contest name -->
                                        <div class="col-md-4">
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter contest name" class="form-control dt-input dt-full-name" data-column="1" name="contest_name" id="filter-name" placeholder="Contest name" data-column-index="0" />
                                        </div>
                                        <!-- filter by contest type -->
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Contest Type">
                                            <div class="form-group">
                                                <select class="select2 form-select" name="contest_type" id="filter-contest-type">
                                                    <optgroup label="Search contest type">
                                                        <option value="">{{__('ad-reports.all')}}</option>
                                                        <option value="on_profit">On Profit</option>
                                                        <option value="on_profit_ratio">On profit ratio</option>
                                                        <option value="on_lot">On Lot</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- filter by date range -->
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
                                                <input id="date_from" type="text" name="from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                <span class="input-group-text">to</span>
                                                <input id="date_to" type="text" name="to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                            </div>
                                        </div>
                                        <!-- filter reset button -->
                                        <div class="col-md-2">
                                            <button id="btn-reset" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('ad-reports.btn-reset')}}</span>
                                            </button>
                                        </div>
                                        <!-- filter button -->
                                        <div class="col-md-2">
                                            <button id="btn-filter" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
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
                                            <th>Reward Name</th>
                                            <th>Reward amount</th>
                                            <th>Date Range</th>
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


<!-- Modal edit contest-->
<div class="modal fade text-start modal-success" id="edit_contest_modal" tabindex="-1" aria-labelledby="mail-sending-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="">
                <!-- Modern Horizontal Wizard -->
                <section class="modern-horizontal-wizard">
                    <div class="bs-stepper wizard-modern modern-wizard-example">
                        <div class="bs-stepper-content">
                            <!-- for trader -->
                            <form id="account-details-modern" action="{{route('admin.trader.update.contest')}}" method="post" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="trader_contest_id" name="id">
                                <!-- <textarea name="comment" style="display: none;" id="text_quill"></textarea> -->
                                <div class="content-header">
                                    <h5 class="mb-0">For Trader</h5>
                                    <small class="text-muted">Enter info amd complete setup.</small>
                                </div>
                                <div class="row">
                                    <!-- contest name -->
                                    <div class="mb-1 col-md-12">
                                        <label class="form-label" for="contest-name">Contest name</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather="edit-3"></i></span>
                                            <input type="test" id="contest-name" class="form-control" name="contest_name" placeholder="Contest Package Name" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- clients -->
                                    <div class="mb-1 form-password-toggle col-md-6">
                                        <label class="form-label" for="contest-client">Clients</label>
                                        <select name="client" id="contest-client" class="form-select select2">
                                            <option value="all_clients">For all clients</option>
                                            <option value="new_registration">For new registration</option>
                                            <option value="new_accounts">For new accounts</option>
                                        </select>
                                    </div>
                                    <!-- require KYC -->
                                    <div class="mb-1 form-password-toggle col-md-6">
                                        <label for="require-kyc">KYC</label>
                                        <div class="title-wrapper d-flex">
                                            <div class="d-flex flex-column float-start">
                                                <div class="form-check form-switch form-check-primary">
                                                    <input type="checkbox" class=" password_settings form-check-input" id="require-kyc" name="kyc" value="1" />
                                                </div>
                                            </div>
                                            <label for="require-kyc" class="cursor-pointer">Require KYC ?</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- contest type -->
                                    <div class="col-md-6 mb-1">
                                        <label for="contest-type">Contest type</label>
                                        <select name="contest_type" id="contest-type" class="form-select select2">
                                            <option value="on_profit">Contest on profit</option>
                                            <option value="on_profit_ratio">Contest on profit ratio</option>
                                            <option value="on_profit_lot">Contest on lot</option>
                                        </select>
                                    </div>
                                    <!-- country /is global-->
                                    <div class="col-md-6 mb-1">
                                        <label for="contest-country">Country</label>
                                        <div class="title-wrapper d-flex">
                                            <div class="d-flex flex-column float-start">
                                                <div class="form-check form-switch form-check-primary">
                                                    <input type="checkbox" id="is_global" class="form-check-input is_global" name="is_global" value="1" checked />
                                                </div>
                                            </div>
                                            <label for="is_global" class="cursor-pointer">Is Global ?</label>
                                        </div>
                                    </div>
                                    <!-- country selects -->
                                    <div class="col-md-12 mb-1" id="trader-country-wrapper">
                                        <label for="contest-country">Select Country</label>
                                        <select class="form-select select2" name="countries[]" placeholder="select country" multiple="multiple" id="contest-country">
                                            <option value="">Select Country</option>
                                            @foreach ($countries as $key => $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- client group -->
                                    <div class="col-md-6 mb-1">
                                        <label for="client-group">Client Group</label>
                                        <select name="group" id="client-group" class="select2 form-select">
                                            <option value="">Choose a group</option>
                                            @foreach($groups as $value)
                                            <option value="{{$value->id}}">{{$value->group_id}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- popup -image -->
                                    <div class="col-md-6 mb-1">
                                        <label for="popup-image">Popup Image</label>
                                        <input type="file" name="popup_image" id="popup-image" class="form-input form-control">
                                    </div>
                                </div>
                                <!-- expire after -->
                                <div class="row">
                                    <!-- expire after -->
                                    <div class="col-md-6 mb-1">
                                        <label for="expire-after">Expire after</label>
                                        <input type="text" name="expire_after" class="form-control" id="expire-after">
                                    </div>
                                    <!-- expire -->
                                    <div class="col-md-6 mb-1">
                                        <label for="expire">&nbsp;</label>
                                        <select name="expire_type" id="expire" class="form-select select2">
                                            <option value="days">Days</option>
                                            <option value="months">Months</option>
                                            <option value="years">Years</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- maximum contest -->
                                    <div class="col-md-6 mb-1">
                                        <label for="max-contest">Maximum contest</label>
                                        <input type="text" id="max-contest" placeholder="0" class="form-control" name="maximum_contest">
                                    </div>
                                    <!-- minimum contest -->
                                    <div class="col-md-6 mb-1">
                                        <label for="min-join">Minimum Join</label>
                                        <input type="text" class="form-control" id="min-contest" name="minimum_join" placeholder="0">
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- date range -->
                                    <div class="col-md-12 mb-1">
                                        <label for="date-range">Date Range</label>
                                        <div class="d-flex flex-column">
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
                                                <input id="from" type="text" name="from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                <span class="input-group-text">to</span>
                                                <input id="to" type="text" name="to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-1 row create-container">
                                    <div class="col-md-8">
                                        <label>Prize</label>
                                        <div class="row" id="prize-row">

                                        </div>
                                        <div class="target-row">

                                        </div>
                                    </div>
                                    <div class=" col-md-4">
                                        <button type="button" id="btn-add-prize" class="add_field_button btn btn-success btn-md pull-right mt-2">Add More prize Field</button>
                                    </div>
                                </div>
                                <!-- editor -->
                                <div class="row mb-2 mb-5">
                                    <div class="col-md-12">
                                        <div id="editor-container">

                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>&nbsp;</span>
                                    <button class="btn btn-primary" type="button" data-file="true" id="btn-save-trader-contest" data-btnid="btn-save-trader-contest" onclick="_run(this)" data-el="fg" data-form="account-details-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="trader_contest_callback">
                                        <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Save</span>
                                    </button>
                                </div>
                            </form>
                            <!-- IB AREA -->
                            <form id="personal-info-modern" action="{{route('admin.ib.update.contest')}}" method="post" enctype="multipart/form-data" class="content" role="tabpanel" aria-labelledby="personal-info-modern-trigger">
                                @csrf
                                <input type="hidden" id="ib_contest_id" name="id">
                                <div class="content-header">
                                    <h5 class="mb-0">For IB</h5>
                                    <small class="text-muted">Enter info amd complete setup.</small>
                                </div>
                                <div class="row">
                                    <!-- contest name /IB -->
                                    <div class="mb-1 col-md-12">
                                        <label class="form-label" for="contest-name-ib">Contest name</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather="edit-3"></i></span>
                                            <input type="test" id="contest-name-ib" class="form-control" name="contest_name" placeholder="Contest Package Name" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- clients /IB -->
                                    <div class="mb-1 form-password-toggle col-md-6">
                                        <label class="form-label" for="contest-client-ib">Clients</label>
                                        <select name="client" id="contest-client-ib" class="form-select select2">
                                            <option value="all_clients">For all clients</option>
                                            <option value="new_registration">For new registration</option>
                                            <option value="new_accounts">For new accounts</option>
                                        </select>
                                    </div>
                                    <!-- require KYC /IB-->
                                    <div class="mb-1 form-password-toggle col-md-6">
                                        <label for="require-kyc-ib">KYC</label>
                                        <div class="title-wrapper d-flex">
                                            <div class="d-flex flex-column float-start">
                                                <div class="form-check form-switch form-check-primary">
                                                    <input type="checkbox" class=" password_settings form-check-input" id="require-kyc-ib" name="kyc" value="1" />
                                                </div>
                                            </div>
                                            <label for="require-kyc-ib" class="cursor-pointer">Require KYC ?</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- contest type /IB-->
                                    <div class="col-md-6 mb-1">
                                        <label for="contest-type-ib">Contest type</label>
                                        <select name="contest_type" id="contest-type-ib" class="form-select select2">
                                            <option value="on_profit">Contest on profit</option>
                                            <option value="on_profit_ratio">Contest on profit ratio</option>
                                            <option value="on_profit_lot">Contest on lot</option>
                                        </select>
                                    </div>
                                    <!-- country /is global /IB-->
                                    <div class="col-md-6 mb-1">
                                        <label for="contest-country-ib">Country</label>
                                        <div class="title-wrapper d-flex">
                                            <div class="d-flex flex-column float-start">
                                                <div class="form-check form-switch form-check-primary">
                                                    <input type="checkbox" id="is_global_ib" class="form-check-input is_global" name="is_global" value="1" checked />
                                                </div>
                                            </div>
                                            <label for="is_global_ib" class="cursor-pointer">Is Global ?</label>
                                        </div>
                                    </div>
                                    <!-- country selects /IB-->
                                    <div class="col-md-12 mb-1" id="ib-country-wrapper">
                                        <label for="contest-country-ib">Select Country</label>
                                        <select class="form-select select2" name="countries[]" placeholder="select country" multiple="multiple" id="contest-country-ib">
                                            <option value="">Select Country</option>
                                            @foreach ($countries as $key => $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- IB group -->
                                    <div class="col-md-6 mb-1">
                                        <label for="client-group-ib">IB Group</label>
                                        <select name="group" id="client-group-ib" class="select2 form-select">
                                            <option value="">Choose a group</option>
                                            @foreach($ib_group as $value)
                                            <option value="{{$value->id}}">{{$value->group_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- credit type -->
                                    <div class="col-md-6 mb-1">
                                        <label for="popup-image-ib">Popup Image</label>
                                        <input type="file" name="popup_image" id="popup-image-ib" class="form-input form-control">
                                    </div>
                                </div>
                                <!-- expire after /IB-->
                                <div class="row">
                                    <!-- expire after -->
                                    <div class="col-md-6 mb-1">
                                        <label for="expire-after-ib">Expire after</label>
                                        <input type="text" name="expire_after" class="form-control" id="expire-after-ib">
                                    </div>
                                    <!-- expire /IB-->
                                    <div class="col-md-6 mb-1">
                                        <label for="expire-ib">&nbsp;</label>
                                        <select name="expire_type" id="expire-ib" class="form-select select2">
                                            <option value="days">Days</option>
                                            <option value="months">Months</option>
                                            <option value="years">Years</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- maximum contest /IB-->
                                    <div class="col-md-6 mb-1">
                                        <label for="max-contest-ib">Maximum contest</label>
                                        <input type="text" id="max-contest-ib" placeholder="0" class="form-control" name="maximum_contest">
                                    </div>
                                    <!-- minimum contest /IB-->
                                    <div class="col-md-6 mb-1">
                                        <label for="min-join-ib">Minimum Join</label>
                                        <input type="text" class="form-control" id="min-contest-ib" name="minimum_join" placeholder="0">
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- date range /IB-->
                                    <div class="col-md-12 mb-1">
                                        <label for="date-range-ib">Date Range</label>
                                        <div class="d-flex flex-column">
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
                                                <input id="from-ib" type="text" name="from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                <span class="input-group-text">to</span>
                                                <input id="to-ib" type="text" name="to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-1 row create-container">
                                    <div class="col-md-8">
                                        <label>Prize</label>
                                        <div class="row" id="prize-row">

                                        </div>
                                        <div class="target-row">

                                        </div>
                                    </div>
                                    <div class=" col-md-4">
                                        <button type="button" id="ib-btn-add-prize" class="add_field_button btn btn-success btn-md pull-right mt-2">Add More prize Field</button>
                                    </div>
                                </div>
                                <!-- editor /IB-->
                                <div class="row mb-2 mb-5">
                                    <div class="col-md-12">
                                        <div id="editor-container-ib">
                                        </div>
                                    </div>
                                </div>
                                <!-- submit buttons /IB-->
                                <div class="d-flex justify-content-between">
                                    <div></div>
                                    <button class="btn btn-primary btn-next" type="button" data-file="true" data-btnid="btn-save-ib-contest" id="btn-save-ib-contest" onclick="_run(this)" data-el="fg" data-form="personal-info-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="ib_contest_callback">
                                        <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Save</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                <!-- /Modern Horizontal Wizard -->
            </div>
        </div>
    </div>
</div>
<!-- Modal edit contest end -->
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
<div class="modal fade" id="addNewCard" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-5">
                <h1 class="text-center mb-1" id="addNewCardTitle">Reason For Decline</h1>

                <!-- form -->
                <form id="ib_decline_request" class="row gy-1 gx-2 mt-75" action="{{route('admin.ib-transfer.decline')}}" method="POST">
                    <div class="col-12">
                        <label class="form-label" for="modalAddCardNumber">Reason:</label>
                        <div class="input-group input-group-merge">
                            <input id="reason" name="reason" class="form-control add-credit-card-mask" type="text" placeholder="type here....." aria-describedby="modalAddCard2" />
                            <span class="input-group-text cursor-pointer p-25" id="modalAddCard2">
                                <span class="add-card-type"></span>
                            </span>
                            <input type="hidden" name="decline_id" id="decline_id">
                            <input type="hidden" name="user_main_id" id="user_main_id">
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
<!-- modal for popup -->
<div class="modal fade" id="contest-popup" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 bg-transparent">
                <button type="button" class="btn-close text-dark btn-popup-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="d-none">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="py-3 text-center">
                    <div class="row">
                        <div class="col-md-12">
                            <img class="img-fluid" id="popup-image-img" src="" alt="Display Popup image">
                        </div>
                    </div>
                </div>
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
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js')}}"></script>

<!-- datatable  -->
<script>
    var dt = $('#ib_transfer_tbl').fetch_data({
        "url": "/admin/reward/list-reward",
        "columns": [{
                "data": "reward_name"
            },
            {
                "data": "reward_amount"
            },
            // {
            //     "data": "total_join"
            // },
            {
                "data": "date_range"
            },
            {
                "data": "create_date"
            },
            // {
            //     "data": "status"
            // },
            {
                "data": "action"
            },

        ],
        customorder: 3
    })
    $(document).fetch_description({
        url: '/admin/contest/contest-list-description',
        feather: true,
    });
    // display popup image
    $(document).on('click', '.btn-view-popup', function() {
        $("#contest-popup").modal('show');
        let id = $(this).data('id');
        $.ajax({
            url: '/admin/contest/contest-popup',
            data: {
                id: id
            },
            dataType: 'JSON',
            method: 'GET',
            success: function(data) {
                if (data.status) {
                    let sourch = data.file_url;
                    $("#popup-image-img").attr('src', sourch);                    
                }
            }
        })

    });
    // close contest
    $(document).on('click', '.btn-close-contest', function() {
        let contest_id = $(this).data('contest_id');
        $(".btn-close-contest").confirm2({
            method: 'POST',
            request_url: '/admin/contest/close',
            data: {
                contest_id: contest_id
            },
            title: 'Close contest',
            button_text: 'Process'
        }, function(data) {
            if (data.status) {
                notify('success', data.message, 'Contest close');
                dt.draw();
            } else {
                notify('error', data.message, 'Contest close');
            }
        });
    });
    //Edit Contest
    $(document).on('click', '.btn-edit-reward', function() {
        let reward_id = $(this).data('reward_id');
        // console.log(contest_id );
        // $("#trader_contest_id").val(contest_id);
        // $("#ib_contest_id").val(contest_id);
        // $('#edit_contest_modal').modal('show');
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });
        // $.ajax({
        //     type: 'POST',
        //     url: '/admin/contest/edit',
        //     data: {
        //         contest_id: contest_id
        //     },

        //     success: function(data) {
        //         console.log(data);
        //         if (data.user_type === "trader") {
        //             $("#personal-info-modern-trigger").hide();
        //             $("#account-details-modern-trigger").hide();
        //             $("#personal-info-modern").hide();
        //             $("#account-details-modern").addClass('active');
        //             $("#account-details-modern").show();

        //             $("#contest-name").val(data.contest_name);
        //             $("#contest-client").val(data.allowed_for);
        //             $("#contest-client").trigger("change");
        //             $("#require-kyc").prop("checked", data.require_kyc);
        //             $("#contest-type").val(data.contest_type);
        //             $("#contest-type").trigger("change");
        //             $("#is_global").prop("checked", data.is_global);
        //             $("#client-group").val(data.client_group);
        //             $("#client-group").trigger("change");
        //             $("#expire-after").val(data.expire_after);
        //             $("#expire").val(data.expire_type);
        //             $("#expire").trigger("change");
        //             $("#max-contest").val(data.max_contest);
        //             $("#min-contest").val(data.min_join);
        //             $("#from").val(data.start_date);
        //             $("#to").val(data.end_date);
        //             $("#editor-container").children().filter(".ql-editor").append(data.description);
        //             const contest_prices = data.contest_price;
        //             const trader_price = JSON.parse(contest_prices);

        //             $("#prize-row").empty();

        //             trader_price.forEach((item, index) => {
        //                 var prize = Object.keys(item)[0];
        //                 var value = item[prize];
        //                 var contestValue = `<div class="col-md-6">
        //                                         <input class="form-control mb-1 label" value="${prize}" type="text" name="level[]" />
        //                                     </div>`;
        //                 var contestPrice = `<div class="col-md-6 second-col d-flex">
        //                     <input class="form-control mb-1" placeholder="Value" value="${value}" type="text" name="amount[]" />
        //                 </div>`;

        //                 $("#prize-row").append(contestValue);
        //                 $("#prize-row").append(contestPrice);
        //             });
        //         } else {
        //             $("#personal-info-modern-trigger").addClass('active');
        //             $("#personal-info-modern").addClass('active');
        //             $("#personal-info-modern").show();
        //             $("#account-details-modern-trigger").removeClass('active');
        //             $("#account-details-modern").hide();

        //             $("#contest-name-ib").val(data.contest_name);
        //             $("#contest-client-ib").val(data.allowed_for);
        //             $("#contest-client-ib").trigger("change");
        //             $("#require-kyc-ib").prop("checked", data.require_kyc);
        //             $("#contest-type-ib").val(data.contest_type);
        //             $("#contest-type-ib").trigger("change");
        //             $("#is_global_ib").prop("checked", data.is_global);
        //             $("#client-group-ib").val(data.ib_group);
        //             $("#client-group-ib").trigger("change");
        //             $("#expire-after-ib").val(data.expire_after);
        //             $("#expire-ib").val(data.expire_type);
        //             $("#expire-ib").trigger("change");
        //             $("#max-contest-ib").val(data.max_contest);
        //             $("#min-contest-ib").val(data.min_join);
        //             $("#from-ib").val(data.start_date);
        //             $("#to-ib").val(data.end_date);
        //             $("#popup-image-ib").val(data.popup_image);
        //             $("#editor-container-ib").children().filter(".ql-editor").append(data.description);



        //             const contest_prices = data.contest_price;
        //             const trader_price = JSON.parse(contest_prices);
        //             $("#prize-row").empty();

        //             trader_price.forEach((item, index) => {
        //                 var prize = Object.keys(item)[0];
        //                 var value = item[prize];
        //                 var contestValue = `<div class="col-md-6">
        //                                         <input class="form-control mb-1 label" value="${prize}" type="text" name="level[]" />
        //                                     </div>`;
        //                 var contestPrice = `<div class="col-md-6 second-col d-flex">
        //                     <input class="form-control mb-1" placeholder="Value" value="${value}" type="text" name="amount[]" />
        //                 </div>`;
        //                 $("#prize-row").append(contestValue);
        //                 $("#prize-row").append(contestPrice);
        //             });
        //         }

        //     }

        // });
    });
    // delete contest
    $(document).on('click', '.btn-delete-contest', function() {
        let contest_id = $(this).data('contest_id');
        $(".btn-delete-contest").confirm2({
            method: 'POST',
            request_url: '/admin/contest/delete',
            data: {
                contest_id: contest_id
            },
            title: 'Delete contest',
            button_text: 'Process',
            message: 'Are you confirm to delete this? If you parmanently delete this can not back...'
        }, function(data) {
            if (data.status) {
                notify('success', data.message, 'Contest delete');
                dt.draw();
            } else {
                notify('error', data.message, 'Contest delete');
            }
        });
    })
    /*<-------------------Decline request End--------------------->*/
    $.fn.create_field = function(options) {
        var settings = $.extend({
            row_id: "#prize-row",
            container: '.create-container',
            label: [, , '2nd', '3rd', '4th', '5th', '7th', '8th', '9th', '10th'],
            max_field: 100,
        }, options);
        var x = 1; //initlal text box count

        this.click(function(e) {
            if (x < settings.max_field) {
                x++; //text box increment
                let elements = $(settings.row_id).clone(true);
                console.log(elements);
                $(elements).removeAttr('id');
                $(elements).find('.label').val(settings.label[x] + ' prize');
                $(elements).find('.second-col').append('<span data-target="' + x + '" class="ms-2 btn btn-sm btn-outline-soundcloud btn-remove-price" style="height:32px; margin-top:3px"><i data-feather="x-square"></i></span>');
                $(this).closest(settings.container).find('.target-row').append(elements);
            }
            feather.replace();
        });
    }
    $("#btn-add-prize").create_field({
        row_id: '#prize-row-trader',
    });
    $("#ib-btn-add-prize").create_field();
    // remove input fild
    $(document).on('click', '.btn-remove-price', function() {
        $(this).closest('.row').remove();
    });

    //quil editor
    var quill = new Quill('#editor-container', {
        modules: {
            toolbar: true
        },
        placeholder: 'Compose your describtion...',
        theme: 'snow' // or 'bubble'
    });
    quill.on('text-change', function(delta, oldDelta, source) {
        $('#text_quill').val(quill.container.firstChild.innerHTML);
    });
    // quill editor IB
    var quill_ib = new Quill('#editor-container-ib', {
        modules: {
            toolbar: true
        },
        placeholder: 'Compose your describtion...',
        theme: 'snow' // or 'bubble'
    });
    quill_ib.on('text-change', function(delta, oldDelta, source) {
        $('#text_quill_ib').val(quill.container.firstChild.innerHTML);
    });
    // *********************************************************************
    function trader_contest_callback(data) {
        if (data.status) {
            notify('success', data.message, 'Trader contest');
            $("#account-details-modern").trigger('reset');
            dt.draw();
        } else {
            notify('error', data.message, 'Trader contest');
        }
        $.validator('account-details-modern', data.errors);
    }
    // ib contest
    function ib_contest_callback(data) {
        if (data.status) {
            notify('success', data.message, 'IB contest');
            $("#personal-info-modern").trigger('reset');
            dt.draw();
        } else {
            notify('error', data.message, 'IB contest');
        }
        $.validator('personal-info-modern', data.errors);
    }
    // show hide country field
    display_country()
    display_country_ib()

    function display_country() {
        if ($("#is_global").is(':checked')) {
            $("#trader-country-wrapper").slideUp();
        } else {
            $("#trader-country-wrapper").slideDown();
        }
    }

    function display_country_ib() {
        if ($("#is_global_ib").is(':checked')) {
            $("#ib-country-wrapper").slideUp();
        } else {
            $("#ib-country-wrapper").slideDown();
        }
    }
    $(document).on("change", '#is_global', function() {
        display_country();
    })
    $(document).on("change", '#is_global_ib', function() {
        display_country_ib();
    })
</script>


@stop
<!-- BEGIN: page JS -->