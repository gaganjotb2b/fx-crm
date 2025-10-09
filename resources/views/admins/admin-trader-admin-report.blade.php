@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Trader admin report')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css') }}">
<link rel="preconnect" href="https://fonts.gstatic.com">
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}"> --}}
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/fonts/font-awesome/css/font-awesome.min.css') }}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
<style>
    .dataTables_info {
        margin: 1rem !important;
    }

    .dataTables_paginate {
        margin: 1rem !important;
    }

    .light-layout .text-body-heading {
        /* color: #5e5873; */
        color: white;
    }

    /* td,
    th {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    } */
    .mt-2.kyc-document-inmodal {
        position: absolute;
        top: 0;
        bottom: 134px;
        left: 0;
        right: 0;
        background-color: #283046;
        z-index: 10;
        display: none;
    }

    .dark-layout .page-trader-admin .datatables-ajax tr,
    .dark-layout .page-ib-admin .datatables-ajax tr,
    .dark-layout .page-ib-admin .datatables-ajax td,
    .dark-layout .page-trader-admin .datatables-ajax td {
        background-color: #323c59 !important;
    }

    .table.table-responsive.tbl-balance thead,
    .table.table-responsive.tbl-balance tbody,
    .table.table-responsive.tbl-balance tfoot,
    .table.table-responsive.tbl-balance tr,
    .table.table-responsive.tbl-balance td,
    .table.table-responsive.tbl-balance th {
        border-color: inherit;
        border-style: solid;
        border-width: 0;
        border: none;
    }

    .table.table-responsive.tbl-trader-details thead,
    .table.table-responsive.tbl-trader-details tbody,
    .table.table-responsive.tbl-trader-details tfoot,
    .table.table-responsive.tbl-trader-details tr,
    .table.table-responsive.tbl-trader-details td,
    .table.table-responsive.tbl-trader-details th {
        border-color: inherit;
        border-style: solid;
        border-width: 0;
        border: none;
    }
</style>
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
@php use App\Services\AllFunctionService; @endphp
<!-- BEGIN: Content-->
<div class="app-content content page-trader-admin">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('client-management.Trader Admin') }}
                        </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}">{{ __('client-management.Home') }}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#">{{ __('client-management.Client Management') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('client-management.Trader Admin') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                        <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="app-todo.html"><i class="me-1" data-feather="info"></i>
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="app-chat.html"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a></div>
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
                                    <h4 class="card-title">{{ __('client-management.Report Filter') }}</h4>
                                    <div class="d-flex justify-content-between">
                                        <div class="btn-exports me-1">
                                            @if (Auth::user()->hasDirectPermission('create trader admin'))
                                            <button type="button" class="btn btn-primary ms-1" data-bs-toggle="modal" data-bs-target="#add-new-trader"><i data-feather='plus'></i>{{ __('client-management.Add new Trader') }}</button>
                                            @endif
                                        </div>
                                        <div class="btn-exports">
                                            <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                                <option value="download" data-icon="download" selected>
                                                    {{ __('client-management.Export') }}
                                                </option>
                                                <option value="csv" data-icon="file">CSV</option>
                                                <option value="excel" data-icon="file">Excel</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--Search Form -->
                                <div class="card-body mt-2">
                                    <form class="dt_adv_search" method="POST" id="filter-form">
                                        <div class="row g-1">
                                            <div class="col-md-4">
                                                <!-- filter by category -->
                                                <label class="form-label" for="category">Category</label>
                                                <select class="select2 form-select" id="category" name="category">
                                                    <option value="">{{ __('client-management.All') }}</option>
                                                    @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">
                                                        {{ ucwords($category->name) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <!-- filter by kyc status -->
                                                <label id="verification_status" class="form-label">{{ __('page.kyc_status') }}</label>
                                                <select class="select2 form-select" id="verification_status" name="verification_status">
                                                    <option value="">{{ __('client-management.All') }}</option>
                                                    <option value="2">{{ __('client-management.Pending') }}
                                                    </option>
                                                    <option value="1">{{ __('client-management.verified') }}
                                                    </option>
                                                    <option value="0">{{ __('client-management.unverified') }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <!-- filter by active status -->
                                                <label class="form-label" for="active-status">{{ __('client-management.Search By Active Status') }}</label>
                                                <select class="select2 form-select" id="active-status" name="active_status">
                                                    <option value="">{{ __('client-management.All') }}</option>
                                                    <option value="1">{{ __('client-management.Active') }}
                                                    </option>
                                                    <option value="2">Block</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <!-- filter by IB info -->
                                                <label class="form-label" for="ib">Has IB (IB / NO IB)</label>
                                                <select class="select2 form-select" id="ib" name="ib">
                                                    <option value="">{{ __('client-management.All') }}</option>
                                                    <option value="ib">IB</option>
                                                    <option value="no_ib">No IB</option>
                                                </select>
                                            </div>
                                            <!-- filter by trader info -->
                                            <div class="col-md-4">
                                                <label class="form-label">{{ __('page.trader') }} Info</label>
                                                <div class="mb-0">

                                                    <input id="info" type="text" name="info" class="form-control dt-input" data-column="4" placeholder="{{ ($varsion == 'pro') ? 'Email / Name / Phone / Country' : 'Email / Name / Phone' }}" data-column-index="3" />
                                                </div>
                                            </div>
                                            <!-- Filter By Manager Info -->


                                            @if($varsion =='pro')
                                            <div class="col-md-4 mb-1">
                                                <label class="form-label">Manager Info</label>
                                                <input name="desk_manager" id="desk_manager" type="text" name="desk_manager" class="form-control dt-input" data-column="4" placeholder="Manager Name / Email" data-column-index="3" />
                                            </div>
                                            @else
                                            <div class="col-md-4 mb-1">
                                                <label class="form-label">Country</label>
                                                <select class="select2 form-select" name="country">
                                                    <option value="">{{ __('client-management.All') }}</option>
                                                    @foreach ($countries as $value)
                                                    <option value="{{ $value->name }}">{{ $value->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>

                                            @endif


                                        </div>
                                        <div class="row g-1">
                                            <div class="col-md-4">
                                                <!-- filter by ib -->
                                                <label class="form-label">IB Info</label>
                                                <div class="mb-0">
                                                    <input id="ib_info" type="text" name="ib_info" class="form-control dt-input" data-column="4" placeholder="{{ ($varsion == 'pro') ? 'IB Email / Name / Phone / Country' : 'IB Email / Name / Phone' }}" data-column-index="3" />
                                                </div>
                                            </div>
                                            <!-- filter by trading account -->
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">{{ __('page.trading_account') }}</label>
                                                <div class="mb-0">
                                                    <input id="trading_acc" name="trading_ac" type="text" class="form-control dt-input" data-column="4" placeholder="Trading Account" data-column-index="3" />
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <!-- filter by joining date -->
                                                <label class="form-label">{{ __('page.join_date') }}</label>
                                                <div class="mb-0">
                                                    <input type="text" class="form-control dt-date flatpickr-range dt-input" data-column="5" placeholder="StartDate to EndDate" data-column-index="4" name="dt_date" />
                                                    <input type="hidden" class="form-control dt-date start_date dt-input" data-column="5" data-column-index="4" name="value_from_start_date" />
                                                    <input type="hidden" class="form-control dt-date end_date dt-input" name="value_from_end_date" data-column="5" data-column-index="4" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-1">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-4">
                                                <!-- filter reset button -->
                                                <label for=""></label>
                                                <button id="btn-reset" type="button" class="btn btn-secondary form-control" data-column="4" data-column-index="3">{{ __('client-management.Reset') }}</button>
                                            </div>
                                            <div class="col-md-4">
                                                <!-- filter submit button -->
                                                <label for=""></label>
                                                <button id="btn-filter" type="button" class="btn btn-primary form-control" data-column="4" data-column-index="3">{{ __('client-management.Filter') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <hr class="my-0" />
                            </div>
                            <div class="card p-2">
                                <div class="card-datatable table-responsive">
                                    <!-- main datatable -->
                                    <table class="datatables-ajax table w-100" id="root-table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>{{ __('client-management.Name') }}</th>
                                                <th>{{ __('client-management.Email') }}</th>
                                                <th>{{ __('client-management.Phone') }}</th>
                                                <th>{{ __('client-management.Joined') }}</th>
                                                <th>{{ __('client-management.Status') }}</th>
                                                <th>Account Manager</th>
                                                <th>Category</th>
                                                <th style="width: 66px">{{ __('client-management.Actions') }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body bulk-assign" style="display: none;">
                                    <h5>Assign Account Manager</h5>
                                    <input type="email" id="manager_email" name="manager_email" class="form-control" placeholder="Enter Manager Email">
                                    <input type="hidden" id="selected_users" name="selected_users">
                                    <div id="selected-users-list" class="mt-2"></div> <!-- Selected users will be shown here -->
                                    <button id="assign-btn" class="btn btn-primary mt-2">Assign</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Ajax Sourced Server-side -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
    <!-- Modal Themes start -->
    <!-- Modal add comments -->
    <div class="modal fade text-start modal-primary" id="primary" tabindex="-1" aria-labelledby="myModalLabel160" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="{{ route('admin.comment-trader-admin-form') }}" method="post" id="form-add-comment">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel160">Comment to - <span class="comment-to"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Snow Editor start -->
                        <section class="snow-editor">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Write a Comment</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div id="snow-wrapper">
                                                        <div id="snow-container">
                                                            <div class="quill-toolbar">
                                                                <span class="ql-formats">
                                                                    <select class="ql-header">
                                                                        <option value="1">Heading</option>
                                                                        <option value="2">Subheading</option>
                                                                        <option selected>Normal</option>
                                                                    </select>
                                                                    <select class="ql-font">
                                                                        <option selected>Sailec Light</option>
                                                                        <option value="sofia">Sofia Pro</option>
                                                                        <option value="slabo">Slabo 27px</option>
                                                                        <option value="roboto">Roboto Slab</option>
                                                                        <option value="inconsolata">Inconsolata
                                                                        </option>
                                                                        <option value="ubuntu">Ubuntu Mono</option>
                                                                    </select>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button class="ql-bold"></button>
                                                                    <button class="ql-italic"></button>
                                                                    <button class="ql-underline"></button>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button class="ql-list" value="ordered"></button>
                                                                    <button class="ql-list" value="bullet"></button>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button class="ql-link"></button>
                                                                    <button class="ql-image"></button>
                                                                    <button class="ql-video"></button>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button class="ql-formula"></button>
                                                                    <button class="ql-code-block"></button>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button class="ql-clean"></button>
                                                                </span>
                                                            </div>
                                                            <div class="editor" style="min-height:150px">

                                                            </div>
                                                            <textarea name="comment" style="display: none;" id="text_quill"></textarea>
                                                            <input type="hidden" name="trader_id" id="trader-id">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Snow Editor end -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="save-comment-btn" onclick="_run(this)" data-el="fg" data-form="form-add-comment" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="create_new_comment_call_back" data-btnid="save-comment-btn">Save
                            Comment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal update comments -->
    <div class="modal fade text-start modal-primary" id="comment-edit" tabindex="-1" aria-labelledby="myModalLabel160" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="{{ route('admin.comment-trader-admin-update-form') }}" method="post" id="form-update-comment">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel160">Comment update to - <span class="comment-to"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Snow Editor start -->
                        <section class="snow-editor">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Write a Comment</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div id="snow-wrapper">
                                                        <div id="snow-container-update">
                                                            <div class="quill-toolbar">
                                                                <span class="ql-formats">
                                                                    <select class="ql-header">
                                                                        <option value="1">Heading</option>
                                                                        <option value="2">Subheading</option>
                                                                        <option selected>Normal</option>
                                                                    </select>
                                                                    <select class="ql-font">
                                                                        <option selected>Sailec Light</option>
                                                                        <option value="sofia">Sofia Pro</option>
                                                                        <option value="slabo">Slabo 27px</option>
                                                                        <option value="roboto">Roboto Slab</option>
                                                                        <option value="inconsolata">Inconsolata
                                                                        </option>
                                                                        <option value="ubuntu">Ubuntu Mono</option>
                                                                    </select>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button class="ql-bold"></button>
                                                                    <button class="ql-italic"></button>
                                                                    <button class="ql-underline"></button>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button class="ql-list" value="ordered"></button>
                                                                    <button class="ql-list" value="bullet"></button>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button class="ql-link"></button>
                                                                    <button class="ql-image"></button>
                                                                    <button class="ql-video"></button>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button class="ql-formula"></button>
                                                                    <button class="ql-code-block"></button>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button class="ql-clean"></button>
                                                                </span>
                                                            </div>
                                                            <div class="editor" style="min-height:150px">

                                                            </div>
                                                            <textarea name="comment" style="display: none;" id="text_quill_update"></textarea>
                                                            <input type="hidden" name="trader_id" id="trader-id-update">
                                                            <input type="hidden" name="comment_id" id="comment-id">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Snow Editor end -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="update-comment-btn" onclick="_run(this)" data-el="fg" data-form="form-update-comment" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="update_comment_call_back" data-btnid="update-comment-btn">Save
                            Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal password change -->
    <div class="modal fade text-start modal-primary" id="password-change-modal" tabindex="-1" aria-labelledby="myModalLabel160" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel160">Password Change for - <span class="comment-to"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Reset Password basic -->
                    <div class="card mb-0">
                        <div class="card-body">
                            <h4 class="card-title mb-1">Change Password 🔒</h4>
                            <p class="card-text mb-2">Your new password must be different from previously used
                                passwords</p>

                            <form class="auth-reset-password-form mt-2" id="change-password-form" action="{{ route('admin.change-password-trader-admin') }}" method="POST">
                                @csrf
                                <div class="mb-1">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="reset-password-new">New Password</label>
                                    </div>
                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" type="password" class="form-control form-control-merge" id="reset-password-new" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="reset-password-new" tabindex="1" autofocus />
                                        <span class="input-group-text position-relative bg-primary text-white cursor-pointer btn-gen-password" style="padding:13px">
                                            <i class="fas fa-key"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="reset-password-confirm">Confirm
                                            Password</label>
                                    </div>
                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input type="password" class="form-control form-control-merge" id="reset-password-confirm" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="reset-password-confirm" tabindex="2" />
                                        <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                    </div>
                                </div>
                                <input type="hidden" name="trader_id" id="trader-id-pass">
                            </form>
                        </div>
                    </div>
                    <!-- /Reset Password basic -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="set-new-password" onclick="_run(this)" data-el="fg" data-form="change-password-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="change_password_call_back" data-btnid="set-new-password">Set new
                        password</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal transaction pin change -->
    <div class="modal fade text-start modal-primary" id="pin-change-modal" tabindex="-1" aria-labelledby="myModalLabel160" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel160">Transaction Pin Change for - <span class="pin-to"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card mb-0">
                        <div class="card-body">
                            <h4 class="card-title mb-1">Change Tranaction Pin 🔒</h4>
                            <p class="card-text mb-2">Your new Pin must be different from previously used Pins</p>

                            <form class="auth-reset-password-form mt-2" id="change-pin-form" action="{{ route('admin.change-pin-trader-admin') }}" method="post">
                                @csrf
                                <div class="mb-1">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="reset-password-new">New Pin</label>
                                    </div>
                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input type="password" data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" class="form-control form-control-merge" id="reset-pin-new" name="transaction_pin" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="reset-pin-new" tabindex="1" autofocus />
                                        <span class="input-group-text position-relative bg-primary cursor-pointer btn-gen-password text-white" style="padding:13px">
                                            <i class="fas fa-key"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="reset-password-confirm">Confirm Pin</label>
                                    </div>
                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input type="password" class="form-control form-control-merge" id="reset-pin-confirm" name="transaction_pin_confirm" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="reset-pin-confirm" tabindex="2" />
                                        <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                    </div>
                                </div>
                                <input type="hidden" name="trader_id" id="trader-id-pin">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary mb-1 text-center" id="set-new-pin" onclick="_run(this)" data-el="fg" data-form="change-pin-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="change_trans_pin_call_back" data-btnid="set-new-pin" style="width:200px">Set
                        New Pin</button>
                </div>
            </div>
        </div>
    </div>
    <!-- UPDATE PROFILE -->
    <div class="modal fade text-start modal-primary" id="update-profile" tabindex="-1" aria-labelledby="Profile update" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mail-sending-modal">Profile Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modern Horizontal Wizard -->
                    <section class="modern-horizontal-wizard">
                        <div class="bs-stepper wizard-modern modern-wizard-example">
                            <div class="bs-stepper-header">
                                <!-- step one account details -->
                                <div class="step" data-target="#account-details-modern" role="tab" id="account-details-modern-trigger">
                                    <button type="button" class="step-trigger" id="step-one-update">
                                        <span class="bs-stepper-box">
                                            1
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Account Details</span>
                                            <span class="bs-stepper-subtitle">Update Account Details</span>
                                        </span>
                                    </button>
                                </div>
                                <div class="line">
                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                </div>
                                <!-- step two personal info -->
                                <div class="step" data-target="#personal-info-modern" role="tab" id="personal-info-modern-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            2
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Personal Info</span>
                                            <span class="bs-stepper-subtitle">Update Personal Info</span>
                                        </span>
                                    </button>
                                </div>
                                <div class="line">
                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                </div>
                                <!-- step three social links -->
                                <div class="step" data-target="#address-step-modern" role="tab" id="address-step-modern-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            3
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Social Links</span>
                                            <span class="bs-stepper-subtitle">Update Social Links</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <!-- start stepper content -->
                            <div class="bs-stepper-content">
                                <!-- account details -->
                                <form id="account-details-modern" action="{{route('admin.update-acc-details')}}" method="post" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger">
                                    @csrf
                                    <input type="hidden" name="user_id" value="" class="update-profile-user-id">
                                    <div class="content-header">
                                        <h5 class="mb-0">Account Details</h5>
                                        <small class="text-muted">Update Account Details.</small>
                                    </div>
                                    <div class="row">
                                        <!-- email -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="text" class="form-control" id="email" name="email" placeholder="Ex: mail@example.com" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- password -->
                                        <div class="mb-1 form-password-toggle col-md-6">
                                            <label class="form-label" for="password">Password</label>
                                            <div class="input-group input-group-merge form-password-toggle mb-2">
                                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                                <input data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" type="password" name="password" class="form-control copy_clipboard" id="password" placeholder="Your Password" aria-describedby="password" />
                                                <button class="btn btn-primary waves-effect waves-float waves-light btn-gen-password" type="button" id="rstButton"><i class="fas fa-key"></i></button>
                                            </div>
                                        </div>
                                        <!-- transaction pin -->
                                        <div class="mb-1 form-password-toggle col-md-6">
                                            <label class="form-label" for="transaction-pin">Transaction Pin</label>
                                            <div class="input-group input-group-merge form-password-toggle mb-2">
                                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                                <input data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" type="password" name="transaction_pin" class="form-control copy_clipboard" id="transaction-pin" placeholder="Your Transaction Pin" aria-describedby="Transaction pin" />
                                                <button class="btn btn-primary waves-effect waves-float waves-light btn-gen-password" type="button" id="rstButton"><i class="fas fa-key"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- trading account limit -->
                                        <div class="mb-3 form-password-toggle col-md-6">
                                            <label class="form-label" for="trading-ac-limit">Trading Account
                                                Limit</label>
                                            <div class="input-group">
                                                <input type="number" name="trading_ac_limit" id="trading-ac-limit" class="touchspin-min-max" value="19" />
                                            </div>
                                        </div>
                                        <!-- approximate investment -->
                                        <div class="mb-3 form-password-toggle col-md-6">
                                            <label class="form-label" for="appr-investment">Approximate
                                                Investment</label>
                                            <input type="text" class="form-control" id="appr-investment" name="app_investment" placeholder="2.00" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- trading account limit -->
                                        <div class="mb-3 form-password-toggle col-md-6 col-12">
                                            <!-- kyc checkbox -->
                                            <div class="form-check form-check-success">
                                                <input type="checkbox" class="form-check-input" name="kyc_status" id="verified" />
                                                <label class="form-check-label" for="verified">KYC Verified</label>
                                            </div>
                                        </div>
                                        <!-- email notification -->
                                        <div class="col-xl-6 col-md-6 col-12">
                                            <div class="mb-1">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" name="has_mail" type="checkbox" id="pro_email_send" />
                                                    <label class="form-check-label" for="pro_email_send">Send Notification By
                                                        Email</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- next previous buttons -->
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-primary" id="save-acc-info-btn2" onclick="_run(this)" data-el="fg" data-form="account-details-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="update_acc_details2" data-btnid="save-acc-info-btn2">Save</button>
                                        <div>
                                            <button type="button" class="btn btn-outline-secondary btn-prev" disabled type="button">
                                                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                            </button>
                                            <!-- hidden button next -->
                                            <button class="btn btn-primary btn-next visually-hidden" id="btn-next-from-acc" type="button">
                                                <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                            </button>
                                            <!-- form submit button next -->
                                            <button type="button" class="btn btn-primary" id="save-acc-info-btn" onclick="_run(this)" data-el="fg" data-form="account-details-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="update_acc_details" data-btnid="save-acc-info-btn">Submit & Next</button>
                                        </div>
                                    </div>
                                </form>
                                <!-- personal info  -->
                                <form id="personal-info-modern" method="post" action="{{route('admin.update-persoanl-details')}}" class="content" role="tabpanel" aria-labelledby="personal-info-modern-trigger">
                                    @csrf
                                    <input type="hidden" name="user_id" value="" class="update-profile-user-id">
                                    <div class="content-header">
                                        <h5 class="mb-0">Personal Info</h5>
                                        <small>Update Your Personal Info.</small>
                                    </div>
                                    <div class="row">
                                        <!-- name -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="name">Full Name</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" />
                                        </div>
                                        <!-- phone number -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="email">Phone</label>
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- country -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="country">Country</label>
                                            <select name="country" class="form-select form-control" id="country">
                                                @foreach ($countries as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                |
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <!-- state -->
                                            <div class="mb-1">
                                                <label class="form-label" for="state">State</label>
                                                <input type="text" class="form-control" id="state" name="state" placeholder="State name" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- city collumn -->
                                        <div class="mb-1 col-md-6">
                                            <div class="mb-1">
                                                <label class="form-label" for="city">City</label>
                                                <input type="text" class="form-control" id="city" name="city" placeholder="2.00" />
                                            </div>
                                        </div>
                                        <!-- zip code column -->
                                        <div class="mb-1 col-md-6">
                                            <div class="mb-1">
                                                <label class="form-label" for="zip-code">Zip Code</label>
                                                <input type="text" class="form-control" id="zip-code" name="zip_code" placeholder="zip code" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- address column -->
                                        <div class="mb-1 col-md-12">
                                            <div class="form-floating mb-0">
                                                <textarea data-length="191" name="address" class="form-control char-textarea" id="address" rows="2" placeholder="Counter" style="height: 75px"></textarea>
                                                <label for="textarea-counter">Address</label>
                                            </div>
                                            <small class="textarea-counter-value float-end"><span class="char-count">0</span> / 191 </small>
                                        </div>
                                    </div>
                                    <!-- next and previous controll -->
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-primary" id="save-personal-info-btn2" onclick="_run(this)" data-el="fg" data-form="personal-info-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="update_personal_info2" data-btnid="save-personal-info-btn2">Save</button>
                                        <div>
                                            <button class="btn btn-primary btn-prev" type="button">
                                                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                            </button>
                                            <button class="btn btn-primary btn-next visually-hidden" id="btn-next-from-personal" type="button">
                                                <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary" id="save-personal-info-btn" onclick="_run(this)" data-el="fg" data-form="personal-info-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="update_personal_info" data-btnid="save-personal-info-btn">Submit & Next</button>
                                        </div>
                                    </div>
                                </form>
                                <!-- social info -->
                                <form id="address-step-modern" action="{{route('admin.update-social-details')}}" method="post" class="content" role="tabpanel" aria-labelledby="address-step-modern-trigger">
                                    @csrf
                                    <input type="hidden" name="user_id" value="" class="update-profile-user-id">
                                    <div class="content-header">
                                        <h5 class="mb-0">Social Links</h5>
                                        <small>Update Social Links.</small>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="facebook"> <i class="fab fa-facebook"></i>Facebook</label>
                                            <input type="text" name="facebook" id="facebook" class="form-control" placeholder="https://facebook.com/user-url" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="twitter"><i class="fab fa-twitter"></i>Twitter</label>
                                            <input type="text" name="twitter" id="twitter" class="form-control" placeholder="https://twitter.com/user-url" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="telegram"><i class="fab fa-telegram"></i>Telegram</label>
                                            <input type="text" name="telegram" id="telegram" class="form-control" placeholder="https://telegram.com/user-65892152415" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="linkedin"><i class="fab fa-linkedin"></i>Linkedin</label>
                                            <input type="text" name="linkedin" id="linkedin" class="form-control" placeholder="https://linkedin.com/user-url" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="whatsapp"><i class="fab fa-whatsapp"></i>Whatsapp</label>
                                            <input type="text" name="whatsapp" id="whatsapp" class="form-control" placeholder="https://telegram.com/user-65892152415" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="skype"><i class="fab fa-skype"></i>Skype</label>
                                            <input type="text" name="skype" id="skype" class="form-control" placeholder="https://linkedin.com/user-url" />
                                        </div>
                                    </div>
                                    <!-- social prev next  -->
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-primary btn-prev" type="button">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <!-- hidden button next -->
                                        <button type="button" class="btn btn-primary" id="save-social-info-btn" onclick="_run(this)" data-el="fg" data-form="address-step-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="update_social_info" data-btnid="save-social-info-btn">Submit</button>
                                    </div>
                                </form>
                                <!--    END: address sterp -->
                            </div>
                        </div>
                    </section>
                    <!-- /Modern Horizontal Wizard -->
                </div>
            </div>
        </div>
    </div>
    <!-- assing to desk manager -->
    <div class="modal fade text-start modal-primary" id="assign-to-desk-manager" tabindex="-1" aria-labelledby="Assign to Desk Manger" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="{{ route('admin.trader-admin-assign-desk-manager') }}" method="post" class="modal-content" id="desk-manager-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Assign to Desk Manager</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- desk manager email -->
                    <div class="mb-1">
                        <label class="form-label" for="desk-manager-email">Desk Manager Email</label>
                        <input type="text" class="form-control" id="desk-manager-email" name="desk_manager_email" placeholder="manager@example.com" />
                        <input type="hidden" name="trader_id" class="trader-id-for-manager" value="">
                    </div>
                    <!-- display desk manager -->
                    <div class="row">
                        <div class="col-lg-4">
                            <img class="img img-thumbnail bg-linkedin" src="{{ asset('admin-assets/app-assets/images/avatars/avater-men.png') }}" alt="DESK MANAGER">
                        </div>
                        <div class="col-lg-6">
                            <div class="desk-manager-info">
                                <div class="not-found-msg"></div>
                                <table class="tbl-manager-info">
                                    <tr>
                                        <th>Name</th>
                                        <th>:</th>
                                        <th id="desk-m-name">----</th>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <th>:</th>
                                        <th id="desk-m-email">----</th>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <th>:</th>
                                        <th id="desk-m-phone">----</th>
                                    </tr>
                                    <tr>
                                        <th>Country</th>
                                        <th>:</th>
                                        <th id="desk-m-country">----</th>
                                    </tr>
                                    <tr>
                                        <th>Group</th>
                                        <th>:</th>
                                        <th id="desk-m-group">----</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary mb-1 text-center" id="btn-assign-desk-manager" onclick="_run(this)" data-el="fg" data-form="desk-manager-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="assign_desk_manager_call_back" data-btnid="btn-assign-desk-manager" style="width:200px" disabled>Assign Now</button>
                </div>
            </form>
        </div>
    </div>
    <!-- add new trading account -->
    <div class="modal fade text-start modal-primary" id="add-new-trading-account" tabindex="-1" aria-labelledby="Add new trading account" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add new trading account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link bg-light-primary active" id="manual-create-tab" data-bs-toggle="tab" href="#manual-create" aria-controls="manual-create" role="tab" aria-selected="true">Add Manually</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="open-live-tab" data-bs-toggle="tab" href="#open-live" aria-controls="open-live" role="tab" aria-selected="false">Open Live Account</a>
                        </li>
                        <?php

                        use App\Models\SoftwareSetting;

                        $software_setting = SoftwareSetting::first();
                        $account_remove_button = ($software_setting->account_move == 0) ? "d-none" : "";
                        ?>
                        <li class="nav-item <?= $account_remove_button; ?>">
                            <a class="nav-link" id="deleted-account-list-tab" data-bs-toggle="tab" href="#deleted-account-list" aria-controls="deleted-account-list" role="tab" aria-selected="false">Deleted Account List</a>
                        </li>
                        <li class="nav-item <?= $account_remove_button; ?>">
                            <a class="nav-link" id="account-transfer-tab" data-bs-toggle="tab" href="#account-transfer" aria-controls="account-transfer" role="tab" aria-selected="false">Account Transfer</a>
                        </li>
                    </ul>
                    <div class="tab-content mb-4" style="min-height: 363px;">
                        <div class="tab-pane active" id="manual-create" aria-labelledby="manual-create-tab" role="tabpanel">
                            <form action="{{ route('admin.trader-admin-add-account-manually') }}" method="post" id="form-account-manually">
                                @csrf
                                <input type="hidden" name="user_id" id="user-for-manually">
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="account-number">Account Number</label>
                                            <input name="account_number" type="text" class="form-control" id="account-number" placeholder="9046010811" />
                                        </div>
                                    </div>
                                    {{-- single or multiple platform handle from the component --}}
                                    {{-- check condition single platform true or false --}}
                                    {{-- if platform is single then platform field will be hidden and not otherwise --}}
                                    <x-platform-option account-type="live" use-for="admin_portal_menual"></x-platform-option>
                                    {{-- <div class="col-xl-6 col-md-6 col-12">
                                        <div class="mb-1 fg">
                                            <label class="form-label" for="platform-account">Platform</label>
                                            <select name="platform" class="select2 form-select" id="platform-account">
                                                <option value="">Select a platform</option>
                                                @php $all_platform = AllFunctionService::all_platform(); @endphp
                                                {!! $all_platform !!}
                                            </select>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12">
                                        <div class="mb-1  fg">
                                            <label class="form-label" for="group-account">Group</label>
                                            <select name="group" class="select2 form-select" id="group-account">
                                                <option value="">Select a Group</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12">
                                        <div class="mb-1 fg">
                                            <label class="form-label" for="leverage-account">Leverage</label>
                                            <select name="leverage" class="select2 form-select" id="leverage-account">
                                                <option value="">Select a Leverage</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="master-password">Master password</label>
                                            <input name="master_password" type="password" class="form-control" id="master-password" placeholder="M9046010811" />
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="investor-password">Investor
                                                password</label>
                                            <input name="investor_password" type="password" class="form-control" id="investor-password" placeholder="IN9046010811" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- phone password -->
                                    <div class="col-xl-6 col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="phone-password">Phone password</label>
                                            <input name="phone_password" type="password" class="form-control" id="phone-password" placeholder="P9046010811" />
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12">
                                        <div class="mt-2">
                                            <div class="form-check form-switch">
                                                <input name="has_mail" type="checkbox" class="form-check-input" id="customSwitch1" checked />
                                                <label class="form-check-label" for="customSwitch1">Send Account
                                                    Details Email</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- submit button manually -->
                                    <div class="col-xl-6 col-md-6 col-12 ms-auto">
                                        <button type="button" class="btn btn-primary form-control text-center mt-2" id="btn-account-manually" onclick="_run(this)" data-el="fg" data-form="form-account-manually" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="account_manually_call_back" data-btnid="btn-account-manually">Create</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="open-live" aria-labelledby="open-live-tab" role="tabpanel">
                            <form action="{{ route('admin.trader-admin-add-account-auto') }}" method="post" id="form-account-auto">
                                @csrf
                                <input type="hidden" name="user_id" id="user-for-auto">
                                <div class="row">
                                    {{-- <div class="col-xl-6 col-md-6 col-12">
                                        <div class="mb-1 fg">
                                            <label class="form-label" for="platform-live">Platform</label>
                                            <select name="platform" class="select2 form-select" id="platform-live">
                                                <option value="">Select a platform</option>
                                                {!! $all_platform !!}
                                            </select>
                                        </div>
                                    </div> --}}
                                    {{-- single or multiple platform handle from the component --}}
                                    {{-- check condition single platform true or false --}}
                                    {{-- if platform is single then platform field will be hidden and not otherwise --}}
                                    <x-platform-option account-type="live"
                                        use-for="admin_portal_auto"></x-platform-option>
                                    <div class="col-xl-6 col-md-6 col-12">
                                        <div class="mb-1 fg">
                                            <label class="form-label" for="group-live">Group</label>
                                            <select name="group" class="select2 form-select" id="group-live">
                                                <option value="">Select a group</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12">
                                        <div class="mb-1 fg">
                                            <label class="form-label" for="leverage-live">Leverage</label>
                                            <select name="leverage" class="select2 form-select" id="leverage-live">
                                                <option value="">Select leverage</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12">
                                        <div class="mt-2">
                                            <div class="form-check form-switch">
                                                <input name="has_mail" type="checkbox" class="form-check-input" id="customSwitch2" checked />
                                                <label class="form-check-label" for="customSwitch2">Send Account
                                                    Details Email</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- submit button auto -->
                                    <div class="col-xl-6 col-md-6 col-12 ms-auto">
                                        <button type="button" class="btn btn-primary form-control text-center mt-2" id="btn-account-auto" onclick="_run(this)" data-el="fg" data-form="form-account-auto" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="account_auto_call_back" data-btnid="btn-account-auto">Create</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="deleted-account-list" aria-labelledby="deleted-account-list-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-12">
                                    <div class="mb-5 fg">
                                        <label class="form-label" for="tranfer-account-no">All removed trading account list</label>
                                        <select class="select2 form-select" id="tranfer-account-no">

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="account-transfer" aria-labelledby="account-transfer-tab" role="tabpanel">
                            <form action="{{ route('admin.trader-admin-transfer-account-no') }}" method="post" id="account-transfer-form">
                                @csrf
                                <input type="hidden" name="user_id" id="account_transfer_user">
                                <div class="row">
                                    <!-- <div class="col-xl-6 col-md-6 col-12"> -->
                                    <div class="col-xl-12 col-md-12 col-12">
                                        <div class="mb-5 fg">
                                            <label class="form-label" for="trader_added_filed">Select trading account for this user<span class="text-danger">&#9734;</span></label>
                                            <select class="select2-size-lg form-select select2-whith-des select2" name="account_no[]" multiple="multiple" id="trader_added_filed" data-placeholder="Find Trading Account">

                                            </select>
                                            <span class="transfered-account-err text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- submit button auto -->
                                    <div class="col-xl-6 col-md-6 col-12 ms-auto">
                                        <button type="button" class="btn btn-primary form-control text-center mt-2" id="account_transfer_btn" onclick="_run(this)" data-el="fg" data-form="account-transfer-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="account_transfer_call_back" data-btnid="account_transfer_btn">Confirm</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- assing to account manager -->
    <div class="modal fade text-start modal-primary" id="assign-to-account-manager" tabindex="-1" aria-labelledby="Assign to account Manger" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="{{ route('admin.trader-admin-assign-account-manager') }}" method="post" class="modal-content" id="account-manager-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Assign to Account Manager</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- account manager email -->
                    <div class="mb-1">
                        <label class="form-label" for="account-manager-email">Account Manager Email</label>
                        <input type="text" list="browsers" class="form-control" id="account-manager-email" name="account_manager_email" placeholder="manager@example.com" />
                        <datalist id="browsers">
                            <option value="Edge">
                            <option value="Firefox">
                            <option value="Chrome">
                            <option value="Opera">
                            <option value="Safari">
                        </datalist>
                        <input type="hidden" name="trader_id" class="trader-id-for-manager" value="">
                    </div>
                    <!-- display account manager -->
                    <div class="row">
                        <div class="col-lg-4">
                            <img class="img img-thumbnail bg-linkedin" src="{{ asset('admin-assets/app-assets/images/avatars/avater-men.png') }}" alt="DESK MANAGER">
                        </div>
                        <div class="col-lg-6">
                            <div class="account-manager-info">
                                <div class="not-found-msg"></div>
                                <table class="tbl-manager-info">
                                    <tr>
                                        <th>Name</th>
                                        <th>:</th>
                                        <th id="account-m-name">----</th>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <th>:</th>
                                        <th id="account-m-email">----</th>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <th>:</th>
                                        <th id="account-m-phone">----</th>
                                    </tr>
                                    <tr>
                                        <th>Country</th>
                                        <th>:</th>
                                        <th id="account-m-country">----</th>
                                    </tr>
                                    <tr>
                                        <th>Group</th>
                                        <th>:</th>
                                        <th id="account-m-group">----</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary mb-1 text-center" id="btn-assign-account-manager" onclick="_run(this)" data-el="fg" data-form="account-manager-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="assign_account_manager_call_back" data-btnid="btn-assign-account-manager" style="width:200px" disabled>Assign Now</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Themes end -->
    <!-- Finance modal -->
    <div class="modal fade text-start modal-primary" id="finance-report" tabindex="-1" aria-labelledby="Finance Report" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Finance Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="datatable-inner table ' . table_color() . ' m-0" style="margin:0px !important;">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Amount</th>
                                    <th>Counter</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Total Deposit</th>
                                    <td id="total-deposit-amount">&dollar; <span></span></td>
                                    <td id="total-deposit-counter">&dollar; <span></span></td>
                                </tr>
                                <tr>
                                    <th>Total Fund Received</th>
                                    <td id="total-rec-fund-amount">&dollar; <span></span></td>
                                    <td id="total-rec-fund-counter">&dollar; <span></span></td>
                                </tr>
                                <tr>
                                    <th>Total Upload Balance</th>
                                    <td id="total-up-balance-amount">&dollar; <span></span></td>
                                    <td id="total-up-balance-counter">&dollar; <span></span></td>
                                </tr>
                                <tr>
                                    <th>Total Deduct Balance</th>
                                    <td id="total-dc-balance-amount">&dollar; <span></span></td>
                                    <td id="total-dc-balance-counter">&dollar; <span></span></td>
                                </tr>
                                <tr>
                                    <th>Total Withdraw Approved</th>
                                    <td id="total-withdraw-amount">&dollar; <span></span></td>
                                    <td id="total-withdraw-counter">&dollar; <span></span></td>
                                </tr>
                                <tr>
                                    <th>Total Withdraw Pending</th>
                                    <td id="total-pending-withdraw-amount">&dollar; <span></span></td>
                                    <td id="total-pending-withdraw-counter">&dollar; <span></span></td>
                                </tr>
                                <tr>
                                    <th>Total Send Fund</th>
                                    <td id="total-send-fund-amount">&dollar; <span></span></td>
                                    <td id="total-send-fund-counter">&dollar; <span></span></td>
                                </tr>
                                <tr>
                                    <th>Total IB Balance Received</th>
                                    <td id="total-ib-balance-amount">&dollar; <span></span></td>
                                    <td id="total-ib-balance-counter">&dollar; <span></span></td>
                                </tr>
                                <tr>
                                    <th class="bg-light-primary">Total Current Balance</th>
                                    <td colspan="2" class="text-center bg-light-primary p-2" id="total-current-balance">&dollar; <span></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Themes end -->
    <!-- Add new trader modal -->
    <div class="modal fade text-start modal-primary" id="add-new-trader" tabindex="-1" aria-labelledby="Add New Trader" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="{{ route('admin.trader-admin-add-new-trader') }}" method="post" class="modal-content" id="trader-registration-form">
                <input type="hidden" name="op" value="step-persional">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Trader</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <!-- Modern Vertical Wizard -->
                    <section class="modern-vertical-wizard">
                        <div class="bs-stepper vertical wizard-modern modern-vertical-wizard-example">
                            <div class="bs-stepper-header">
                                <div class="step" data-target="#personal-info-vertical-modern" role="tab" id="personal-info-vertical-modern-trigger">
                                    <button type="button" class="step-trigger m-step-btn" data-step="step-persional" id="btn-m-p-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="user" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Personal Info</span>
                                            <span class="bs-stepper-subtitle">Add Personal Info</span>
                                        </span>
                                    </button>
                                </div>
                                <div class="step" data-target="#address-step-vertical-modern" role="tab" id="address-step-vertical-modern-trigger">
                                    <button type="button" class="step-trigger m-step-btn" data-step="step-address">
                                        <span class="bs-stepper-box">
                                            <i data-feather="map-pin" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Address</span>
                                            <span class="bs-stepper-subtitle">Add Address</span>
                                        </span>
                                    </button>
                                </div>
                                <!-- step social link -->
                                @if (AllFunctionService::social_link_required())
                                <div class="step" data-target="#social-links-vertical-modern" role="tab" id="social-links-vertical-modern-trigger">
                                    <button type="button" class="step-trigger m-step-btn" data-step="step-social">
                                        <span class="bs-stepper-box">
                                            <i data-feather="link" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Social Links</span>
                                            <span class="bs-stepper-subtitle">Add Social Links</span>
                                        </span>
                                    </button>
                                </div>
                                @endif
                                <!-- meta account auto  -->
                                @if (AllFunctionService::create_meta_acc() == true)
                                <div class="step" data-target="#account-details-vertical-modern" role="tab" id="account-details-vertical-modern-trigger">
                                    <button type="button" class="step-trigger m-step-btn" data-step="step-account">
                                        <span class="bs-stepper-box">
                                            <i data-feather="file-text" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Account Details</span>
                                            <span class="bs-stepper-subtitle">Setup Account Details</span>
                                        </span>
                                    </button>
                                </div>
                                @endif
                                <div class="step" data-target="#security-vertical-modern" role="tab" id="security-vertical-modern-trigger">
                                    <button type="button" class="step-trigger m-step-btn" data-step="step-confirm">
                                        <span class="bs-stepper-box">
                                            <i data-feather="key" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Security</span>
                                            <span class="bs-stepper-subtitle">Password and Security</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="bs-stepper-content">
                                <div id="personal-info-vertical-modern" class="content" role="tabpanel" aria-labelledby="personal-info-vertical-modern-trigger">
                                    <div class="content-header">
                                        <h5 class="mb-0">Personal Info</h5>
                                        <small>Enter Your Personal Info.</small>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-12">
                                            <label class="form-label" for="full-name">Full Name</label>
                                            <input type="text" class="form-control" id="full-name" name="full_name" placeholder="Ex: John Arifin" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-1">
                                                <div class="fg position-relative al-error-solve">
                                                    <label class="form-label" for="date-of-birth">Date of
                                                        Birth</label>
                                                    <input type="text" name="data_of_birth" id="date-of-birth" class="form-control flatpickr-human-friendly flatpickr_time mb-2" placeholder="October 14, 2022" />
                                                </div>
                                                <span class="age_erro alert alert-danger"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="text" class="form-control" id="email" name="email" placeholder="Ex: mail@example.como" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="phone">Phone</label>
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="+8801747XXXXXXX" />
                                        </div>
                                    </div>
                                    <div class="row custom-options-checkable g-1 mb-2">
                                        <!-- male -->
                                        <div class="col-md-4">
                                            <input class="custom-option-item-check" type="radio" name="gender" id="male" checked value="male" />
                                            <label class="custom-option-item text-center p-1" for="male">
                                                <span class="d-flex">
                                                    <img class="img img-fluid img-gender-male" src="{{ asset('admin-assets/app-assets/images/avatars/avater-men.png') }}" alt="">
                                                    <span class="custom-option-item-title h4 d-block">Male</span>
                                                </span>
                                            </label>
                                        </div>
                                        <!-- female -->
                                        <div class="col-md-4">
                                            <input class="custom-option-item-check" type="radio" name="gender" id="female" value="female" />
                                            <label class="custom-option-item text-center text-center p-1" for="female">
                                                <span class="d-flex">
                                                    <img class="img img-fluid img-gender-male" src="{{ asset('admin-assets/app-assets/images/avatars/avater-lady.png') }}" alt="">
                                                    <span class="custom-option-item-title h4 d-block">Female</span>
                                                </span>
                                            </label>
                                        </div>
                                        <!-- other -->
                                        <div class="col-md-4">
                                            <input class="custom-option-item-check" type="radio" name="gender" id="other" value="other" />
                                            <label class="custom-option-item text-center p-1" for="other">
                                                <span class="d-flex">
                                                    <i data-feather="users" class="font-large-1 mb-75 other-gender-icon"></i>
                                                    <span class="custom-option-item-title h4 d-block">Other</span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-primary btn-prev m-prev-btn" data-step="step-persional" disabled>
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-next d-none" id="personal-next">
                                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                                            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary text-center" id="btn-valid-personal" onclick="_run(this)" data-el="fg" data-form="trader-registration-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="trader_registration_call_back" data-btnid="btn-valid-personal" style="width:200px">Save Trader</button>
                                    </div>
                                </div>
                                <!-- address section  -->
                                <div id="address-step-vertical-modern" class="content" role="tabpanel" aria-labelledby="address-step-vertical-modern-trigger">
                                    <div class="content-header">
                                        <h5 class="mb-0">Address</h5>
                                        <small>Enter Your Address.</small>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-12 fg">
                                            <label class="form-label" for="country">Country</label>
                                            <select class="select2 form-select" id="country" name="country">
                                                @foreach ($countries as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-1 col-md-12">
                                            <label class="form-label" for="state">State (Optional)</label>
                                            <input type="text" class="form-control" id="state" name="state" placeholder="State name" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="city">City</label>
                                            <input type="text" class="form-control" id="city" name="city" placeholder="City Name" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="zip-code">Zip Code</label>
                                            <input type="text" class="form-control" id="zip-code" name="zip_code" placeholder="Zipcode" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-12">
                                            <label class="form-label" for="address">Address (Optional)</label>
                                            <input type="text" class="form-control" id="address" name="address" placeholder="Client address" />
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-primary btn-prev m-prev-btn" data-step="step-persional">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-next d-none" id="address-next">
                                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                                            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary text-center" id="btn-valid-address" onclick="_run(this)" data-el="fg" data-form="trader-registration-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="trader_registration_call_back" data-btnid="btn-valid-address" style="width:200px">Next</button>
                                    </div>
                                </div>
                                <!-- step social link -->
                                @if (AllFunctionService::social_link_required())
                                <div id="social-links-vertical-modern" class="content" role="tabpanel" aria-labelledby="social-links-vertical-modern-trigger">
                                    <input type="hidden" name="op_social" value="1">
                                    <div class="content-header">
                                        <h5 class="mb-0">Social Links</h5>
                                        <small>Enter Trader Social Links.</small>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="vertical-modern-twitter">Twitter</label>
                                            <input type="text" id="vertical-modern-twitter" class="form-control" placeholder="https://twitter.com/abc" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="vertical-modern-facebook">Facebook</label>
                                            <input type="text" id="vertical-modern-facebook" class="form-control" placeholder="https://facebook.com/abc" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="vertical-modern-google">Telegram</label>
                                            <input type="text" id="vertical-modern-google" class="form-control" placeholder="https://telegram.com/abc" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="vertical-modern-linkedin">Linkedin</label>
                                            <input type="text" id="vertical-modern-linkedin" class="form-control" placeholder="https://linkedin.com/abc" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="vertical-modern-skype">Skype</label>
                                            <input type="text" id="vertical-modern-skype" class="form-control" placeholder="john@examle.com" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="vertical-modern-whatsapp">Whatsapp</label>
                                            <input type="text" id="vertical-modern-whatsapp" class="form-control" placeholder="https://whatsapp.com/user-0125874152" />
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-primary btn-prev m-prev-btn" data-step="step-address">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-next d-none" id="social-next">
                                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                                            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary text-center" id="btn-valid-social" onclick="_run(this)" data-el="fg" data-form="trader-registration-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="trader_registration_call_back" data-btnid="btn-valid-social" style="width:200px">Next</button>
                                    </div>
                                </div>
                                @endif
                                <!-- step account details -->
                                @if (AllFunctionService::create_meta_acc())
                                <div id="account-details-vertical-modern" class="content" role="tabpanel" aria-labelledby="account-details-vertical-modern-trigger">
                                    <input type="hidden" name="op_account" value="1">
                                    <div class="content-header">
                                        <h5 class="mb-0">Account Details</h5>
                                        <small class="text-muted">Enter Your Account Details.</small>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-12 fg">
                                            <label class="form-label" for="server">Server</label>
                                            <select class="select2 form-select" id="server" name="server_name">
                                                <option value="">Choose a Server</option>
                                                @if ($platform === 'mt4')
                                                <option value="mt4">MT4</option>
                                                @elseif($platform === 'mt5')
                                                <option value="mt5">MT5</option>
                                                @else
                                                <option value="mt4">MT4</option>
                                                <option value="mt5">MT5</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="mb-1 col-md-12 fg">
                                            <label class="form-label" for="client-type">Client Type</label>
                                            <select class="select2 form-select" id="client-type" name="client_type">
                                                <option value="">Choose client type</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 form-password-toggle col-md-12">
                                            <div class="mb-1 fg">
                                                <label class="form-label" for="account-type">Account
                                                    Type</label>
                                                <select class="select2 form-select" id="account-type" name="account_type">
                                                    <option value="">Choose account type</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-1 form-password-toggle col-md-12">
                                            <div class="mb-1 fg">
                                                <label class="form-label" for="leverage">Leverage</label>
                                                <select class="select2 form-select" id="leverage" name="leverage">
                                                    <option value="">Choose Leverage</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary btn-prev m-prev-btn" data-step="step-address" id="btn-prev-account-m">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-next d-none" id="account-next">
                                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                                            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary text-center" id="btn-valid-account" onclick="_run(this)" data-el="fg" data-form="trader-registration-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="trader_registration_call_back" data-btnid="btn-valid-account" style="width:200px">Next</button>
                                    </div>
                                </div>
                                @endif
                                <!-- step confirm password and security -->
                                <div id="security-vertical-modern" class="content" role="tabpanel" aria-labelledby="security-vertical-modern-trigger">
                                    <div class="content-header">
                                        <h5 class="mb-0">Security</h5>
                                        <small>Password and Security.</small>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="password">Password</label>
                                            <div class="input-group input-group-merge form-password-toggle">
                                                <input type="password" data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" class="form-control" id="password" placeholder="Your Password" aria-describedby="password" name="password" />
                                                <span class="input-group-text position-relative bg-primary text-white cursor-pointer btn-gen-password" style="padding:13px">
                                                    <i class="fas fa-key"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="confirm-password">Confirm
                                                Password</label>
                                            <div class="input-group input-group-merge form-password-toggle">
                                                <input type="password" class="form-control" id="confirm-password" placeholder="Confirm Password" aria-describedby="confirm-password" name="confirm_password" />
                                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-check-label mb-50" for="welcome-email">Send Welcome
                                                Email</label>
                                            <div class="form-check form-switch form-check-primary">
                                                <input type="checkbox" name="welcome_email" class="form-check-input" id="welcome-email" checked />
                                                <label class="form-check-label" for="welcome-email">
                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-check-label mb-50" for="mark-as-activated">Mark as
                                                Pre Activated</label>
                                            <div class="form-check form-switch form-check-primary">
                                                <input type="checkbox" name="mark_as_activated" class="form-check-input" id="mark-as-activated" checked />
                                                <label class="form-check-label" for="mark-as-activated">
                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-primary btn-prev m-prev-btn" data-step="step-address" id="btn-prev-confirm-m">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <!-- <button type="button" class="btn btn-primary btn-next d-none" id="address-next">
                                                <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                            </button> -->
                                        <button type="button" class="btn btn-primary text-center" id="btn-add-new-trader" onclick="_run(this)" data-el="fg" data-form="trader-registration-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="trader_registration_call_back" data-btnid="btn-add-new-trader" style="width:200px">Save Trader</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- /Modern Vertical Wizard -->
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Themes end -->

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


    @stop
    <!-- BEGIN: vendor JS -->
    @section('vendor-js')
    <script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
    @stop
    <!-- END: vendor JS -->
    <!-- BEGIN: Page vendor js -->
    @section('page-vendor-js')
    <script src="{{asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js')}}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>

    <script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
    <!-- datatable -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>

    <script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>

    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('/common-js/finance.js') }}"></script>
    <script src="{{asset('trader-assets/assets/js/scripts/pages/file-upload-with-form.js')}}"></script>
    <script src="{{asset('common-js/select2-get-country.js')}}"></script>
    <script src="{{asset('/common-js/copy-js.js')}}"></script>
    @stop
    <!-- END: page vendor js -->
    <!-- BEGIN: page JS -->
    @section('page-js')
    <script>
        // kyc tab controll
        // open kyc step for update profile
        $(document).on("change", "#verified", function() {
            $(".btn-v-documents").trigger('click');
        })
        // update user profile with kyc--------------
        // file_upload(
        //     "/admin/client-management/update-profile", //<--request url for proccessing
        //     false, //<---auto process true or false
        //     ".id-proof-dropzone", //<---dropzones selectore
        //     "update-profile-form", //<---form id/selectore
        //     "#btn-update-acc-details", //<---submit button selectore
        //     "Update Profile", //<---Notification Title
        //     null,
        //     // '#root-table' //<---datatable selector for redraw
        // );
        // smooth scrollbar

        // quil editor
        var snowEditor;
        var update_editor;
        (function(window, document, $) {
            'use strict';

            var Font = Quill.import('formats/font');
            Font.whitelist = ['sofia', 'slabo', 'roboto', 'inconsolata', 'ubuntu'];
            Quill.register(Font, true);
            // Snow Editor for comment

            snowEditor = new Quill('#snow-container .editor', {
                bounds: '#snow-container .editor',
                modules: {
                    formula: true,
                    syntax: true,
                    toolbar: '#snow-container .quill-toolbar'
                },
                theme: 'snow'
            });

            // comment update editor
            update_editor = new Quill('#snow-container-update .editor', {
                bounds: '#snow-container-update .editor',
                modules: {
                    formula: true,
                    syntax: true,
                    toolbar: '#snow-container-update .quill-toolbar'
                },
                theme: 'snow'
            });
            var editors = [snowEditor, update_editor];
        })(window, document, jQuery);

        snowEditor.on('text-change', function(delta, oldDelta, source) {
            console.log(snowEditor.container.firstChild.innerHTML);
            $('#text_quill').val(snowEditor.container.firstChild.innerHTML);
        });

        // for update comment
        update_editor.on('text-change', function(delta, oldDelta, source) {
            $('#text_quill_update').val(update_editor.container.firstChild.innerHTML);
        });
    </script>
    <!-- <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-quill-editor.js') }}"></script> -->
    <script src="{{ asset('admin-assets/app-assets/js/scripts/tables/table-datatable-trader-admin.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js') }}"></script>
    <script src="{{ asset('admin-assets/src/js/scripts/forms/pickers/custom-form-picker.js') }}"></script>
    <script src="{{ asset('/common-js/copy-js.js') }}"></script>
    <script src="{{ asset('/common-js/password-gen.js') }}"></script>
    <script src="{{asset('admin-assets/app-assets/js/scripts/components/components-navs.js')}}"></script>
    <script>
        // check kyc doc
        $(document).on("click", ".btn-v-documents", function() {
            $(".kyc-doc-row").toggleClass('d-none');
        })
        // add modal title
        var comment_table_obj;
        $(document).on("click", ".btn-add-comment", function() {
            comment_table_obj = $(this).closest('tr').find('.comment');
            $('.comment-to').html($(this).data('name'));
            $('#trader-id').val($(this).data('id'));
        });

        // store comment
        // --------------------------------------------------------------------
        $("#text_quill").val('')

        function create_new_comment_call_back(data) {
            if (data.status == true) {
                notify('success', data.message, 'Add Comment');
                $('#primary').modal('hide');
                snowEditor.setContents([]);
                $("#text_quill").val('');
                comment_table_obj.DataTable().draw();
                $.validator("form-add-comment", data.errors);
            } else {
                notify('error', data.message, 'Add Comment');
            }
            $.validator("form-add-comment", data.errors);
        }

        // update comment
        // --------------------------------------------------------------------
        // get quil data into form
        $(document).on("click", ".btn-update-comment", function() {
            $('.comment-to').html($(this).data('name'));
            $('#trader-id-update').val($(this).data('id'));
            $('#comment-id').val($(this).data('commentid'));
            $(".ql-editor").html($(this).data('comment'));
            comment_table_obj = $(this).closest('.description').find('.comment');
        });
        $("#text_quill_update").val('')

        function update_comment_call_back(data) {
            if (data.status == true) {
                notify('success', data.message, 'Update Comment');
                snowEditor.setContents([]);
                $("#text_quill_update").val('');
                comment_table_obj.DataTable().draw();
                $("#comment-edit").modal('hide');
            } else {
                notify('errors', 'Please fix the following errors', 'Update Comment');
            }
            $.validator("form-update-comment", data.errors);
        }
        // update user profile-----------------------------------------------------
        // function update_profile_call_back(data) {
        //     if (data.status == true) {
        //         $("#step-one-update").trigger('click');
        //         $("#update-profile").modal('hide');
        //         notify('success', data.message, 'Profile Update');
        //     } else {
        //         notify('error', 'Please fix the following errors', 'Profile Update');
        //     }
        //     $("#btn-update-profile").removeClass('waves-effect');
        //     $.validator("desk-manager-form", data.errors);
        // }
        // END: update user profile-----------------------------------------------------
        // assign desk manager-----------------------------------------------------

        function assign_desk_manager_call_back(data) {
            if (data.status == true) {
                notify('success', data.message, 'Assign Desk Manager');
            } else {
                notify('error', data.message, 'Assign Desk Manager');
            }
        }
        // END: assign desk manager-----------------------------------------------------
        // assign account manager-----------------------------------------------------

        function assign_account_manager_call_back(data) {
            if (data.status == true) {
                notify('success', data.message, 'Assign Account Manager');
            } else {
                notify('error', data.message, 'Assign Account Manager');
            }
        }
        // END: assign account manager-----------------------------------------------------
        // start: add mew traders--------------------------------------------------
        $('input[name="op"]').val('step-persional');
        // tab click and change step
        if ($("input[name='op_social']").val() == 1) {
            $("#btn-prev-account-m").data('step-social');
        }
        if ($("input[name='op_account']").val() == 1) {
            $("#btn-prev-confirm-m").data('step-account');
        }
        $(document).on('click', '.m-step-btn, .m-prev-btn', function() {
            $('input[name="op"]').val($(this).data('step'));
        })

        function trader_registration_call_back(data) {
            $('.error-msg').closest('.al-input-error-fixed').css({
                'margin-bottom': '0px'
            });
            $.validator("trader-registration-form", data.errors);
            // persional info validation check
            if (data.persional_status == true) {
                $('input[name="op"]').val('step-address');
                $("#personal-next").trigger('click');
            }
            // step address validation check
            if (data.address_status == true) {
                if ($("input[name='op_social']").val() == 1 && $("input[name='op_account']").val() == 1) {
                    $('input[name="op"]').val('step-social');
                }
                if ($("input[name='op_social']").val() == 1 && $("input[name='op_account']").val() != 1) {
                    $('input[name="op"]').val('step-social');
                }
                if ($("input[name='op_account']").val() == 1 && $("input[name='op_social']").val() != 1) {
                    $('input[name="op"]').val('step-account');
                }

                if ($("input[name='op_social']").val() != 1 && $("input[name='op_account']").val() != 1) {
                    $('input[name="op"]').val('step-confirm');
                }
                // $('input[name="op"]').val('step-social');
                $("#address-next").trigger('click');
            }
            // step address validation check
            if (data.social_status == true) {
                // meta account auto create ativated
                if ($("input[name='op_account']").val() == 1) {
                    $('input[name="op"]').val('step-account');
                }
                // meta account auto create disabled
                else {
                    $('input[name="op"]').val('step-confirm');
                }
                $("#social-next").trigger('click');
            }
            // step account status
            if (data.account_status == true) {
                $('input[name="op"]').val('step-confirm');
                $("#account-next").trigger('click');
            }
            if (data.trader_registration == true) {
                $("#btn-m-p-trigger").trigger('click');
                $('input[name="op"]').val('step-persional');
                notify('success', 'New Trader Successfully Registered', 'Trader Registration');
                $("#add-new-trader").modal('hide');
                $("#trader-registration-form").trigger('reset');
                $("#server, #client-type, #account-type, #leverage, #country").trigger("change");

            }
            if (data.trader_registration == false) {
                notify('error', 'New Trader Registration Failed', 'Trader Registration');
            }
            // sending welcome mail-----------------
            if (data.welcome_mail == true) {
                let trader_id = data.trader_id;
                let $url = `/admin/client-management/send-welcome-email/` + trader_id;
                send_mail('Welcome Email', 'Welcome mail sending for new account', $url, true);
            }
            $('.error-msg').closest('.al-input-error-fixed').css({
                'margin-bottom': '15px'
            });

            const {
                error
            } = data;
            $.validator("trader-registration-form", data.errors);
            $('.age_erro').html(error.data_of_birth);

            $('.datatables-ajax').DataTable().draw();
        }
        // END: trader registration-----------------------------------------------------
        // start: change transaction pin--------------------------------------------------

        $(document).on("click", ".change-pin-btn", function() {
            $('.pin-to').html($(this).data('name'));
            $('#trader-id-pin').val($(this).data('id'));
        });

        function change_trans_pin_call_back(data) {
            if (data.status === true) {
                notify('success', data.message, 'Transaction pin changes');
                $("#pin-change-modal").modal('hide');
                // send email
                let $url = '/admin/client-management/trader-admin-change-pin-mail/' + data.id;
                send_mail('Mail For Transaction pin', 'Sending Transaction pin changes mail', $url, true);

            } else {
                notify('error', 'Please fix the following errors', 'Change transaction pin');
            }
            $.validator("change-pin-form", data.errors);
        }
        // END: change transaction pin-----------------------------------------------------
        //  START: change password--------------------------------------------------

        $(document).on("click", ".change-password-btn", function() {
            $('.comment-to').html($(this).data('name'));
            $('#trader-id-pass').val($(this).data('id'));
        });

        function change_password_call_back(data) {
            if (data.status === true) {
                notify('success', data.message, 'Password Changes');
                // $('#send-mail-pass').modal('toggle');
                $('#password-change-modal').modal('toggle');
                // sending mail
                let $url = '/admin/client-management/trader-admin-change-password-mail/' + data.id;
                send_mail('Change password mail', 'Sending password change mail', $url, true);

            } else {
                notify('error', 'Please fix the following errors', 'Change Password');
            }
            $.validator("change-password-form", data.errors);
        }
        // END: change password-----------------------------------------------------


        // genrate randome password
        $(document).on('click', ".btn-gen-password", function() {
            var field = $(this).closest('div').find('input[rel="gp"]');
            field.val(rand_string(field));
            field.attr('type', 'text');
        });
        // select password for copy
        $('input[rel="gp"]').on("click", function() {
            let id = $(this).attr('id');
            $(this).select();
            if ($(this).val() != "") {
                copy_to_clipboard(id)
            }
            $(this).attr('type', 'password');
        });
        $(document).on("click", ".btn-load-balance", function() {
            let $this = $(this);
            let account = $(this).data('id');
            balance_equity($this, account, 'balance'); //finance js
        });
        $(document).on("click", ".btn-load-equity", function() {
            let $this = $(this);
            let account = $(this).data('id');
            balance_equity($this, account, 'equity'); // finance js
        });
        var account_table_obj;
        $(document).on('click', '.btn-add-account', function() {
            // account transfer form reset
            $("#account-transfer-form").trigger('reset');
            // $("select").val('').change();
            $('#trader_added_filed').html('');
            $('.transfered-account-err').html('');

            $("#add-new-trading-account").modal('show');
            $("#user-for-manually").val($(this).data('user'));
            $("#user-for-auto").val($(this).data('user'));
            $("#account_transfer_user").val($(this).data('user'));
            account_table_obj = $(this).closest('tr').find('.trading_account');
            console.log("sdfsd");
            
            $("#manual-create-tab").trigger('click');
        });

        
        // get leverage for add acccount
        // for manually
        $(document).on("click", "#manual-create-tab" , function () {
            let server = $("#platform-account").val();
            let client_type = 'live';
            $.ajax({
                url: '/admin/client-management/get-client-groups/' + server + '/meta-server/' + client_type,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    $("#group-account").html(data.client_groups);
                    $("#leverage-account").html(data.leverage);
                }
            });
        });

        // get leverage for add acccount 
        // for manually
        $(document).on("click", "#open-live-tab" , function () {
            let server = $("#platform-live").val();
            let client_type = 'live';
            $.ajax({
                url: '/admin/client-management/get-client-groups/' + server + '/meta-server/' + client_type,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    $("#group-live").html(data.client_groups);
                    $("#leverage-live").html(data.leverage);
                }
            });
        });
        
        // tab background controll for 
        // create trading account
        $(document).on('click', '#add-new-trading-account .nav-link', function() {
            $(this).closest('#add-new-trading-account').find(".nav-link").each(function(index, object) {
                $(object).removeClass('bg-light-primary');
            });
            $(this).addClass('bg-light-primary');
        });

        // add manually
        $("#form-account-manually").trigger('reset');
        $("select").val('').change();

        function account_manually_call_back(data) {
            $("#btn-account-manually").prop('disabled', true);
            if (data.status === true) {
                $('#add-new-trading-account').modal('toggle');
                // sending mail
                if (data.has_mail == true) {
                    let $url = '/admin/client-management/add-account-manually/' + data.id + '/?op=email';
                    send_mail('Add account manually', 'Sending mail to user', $url, true);
                } else {
                    notify('success', data.message, 'Add account manually');
                }
                account_table_obj.DataTable().draw();
                $("#form-account-manually").trigger('reset');
                // $("select").val('').change();

            } else {
                notify('error', data.message, 'Add account manually');
            }
            setTimeout(() => {
                $("#btn-account-manually").prop('disabled', false);
            }, 2000);
            $.validator("form-account-manually", data.errors);
        }
        // create live account for user
        function account_auto_call_back(data) {
            $("#btn-account-auto").prop('disabled', true);
            if (data.status === true) {
                $("#form-account-auto").trigger('reset');
                $('#add-new-trading-account').modal('toggle');
                // sending mail
                if (data.has_mail == true) {
                    let $url = '/admin/client-management/add-account-auto/' + data.id + '/?op=email';
                    send_mail('Open Live Account', 'Sending mail to user', $url, true);
                } else {
                    notify('success', data.message, 'Open Live Account');
                }
                account_table_obj.DataTable().draw();
                $("select").val('').change();

            } else {
                notify('error', data.message, 'Add account auto');
            }
            setTimeout(() => {
                $("#btn-account-auto").prop('disabled', false);
            }, 5000);
            $.validator("form-account-auto", data.errors);
        }

        // account transfer call back
        function account_transfer_call_back(data) {
            if (data.status === true) {
                $('.transfered-account-err').html('');
                $("#account-transfer-form").trigger('reset');
                $('#add-new-trading-account').modal('toggle');
                notify('success', data.message, 'Account Transfer');
                account_table_obj.DataTable().draw();
            } else {
                notify('error', data.message, 'Account Transfer');
                $('.transfered-account-err').html(data.errors.account_no[0]);
            }
        }

        //convert trader to ib 
        $(document).on('click', '.convert-to-ib', function() {
            let user_id = $(this).data('user');
            let warning_title = "";
            let warning_msg = "";
            let request_for;

            warning_title = 'Are you sure? to Convert this User To IB !';
            warning_msg = 'If you want to convert this User please click OK, otherwise simply click cancel';
            request_for = 'block';

            Swal.fire({
                icon: 'warning',
                title: warning_title,
                html: warning_msg,

                showCancelButton: true,
                customClass: {
                    confirmButton: 'btn btn-warning',
                    cancelButton: 'btn btn-danger'
                },
            }).then((willDelete) => {
                if (willDelete.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '/admin/client-management/convert-to-ib/' + user_id,
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            user_id: user_id,
                        },
                        success: function(data) {
                            if (data.success === true) {
                                let $url = '/admin/client-management/convert-to-ib/' + data.user_id + "?op=mail";
                                send_mail('Convert to IB', 'Please wait whilte we sending mail to user.', $url, true)
                                $("#root-table").DataTable().draw();
                            } else {
                                notify('error', data.message, 'Convert to IB');
                            }
                        }
                    })
                }
            });
        });
        //remove ib access
        $(document).on('click', '.remove-ib-access', function() {
            let user_id = $(this).data('user');

            let warning_title = "";
            let warning_msg = "";
            let request_for;

            warning_title = 'Are you sure? to Remove IB Access !';
            warning_msg = 'If you want to remove  User access please click OK, otherwise simply click cancel';
            request_for = 'block';

            Swal.fire({
                icon: 'warning',
                title: warning_title,
                html: warning_msg,

                showCancelButton: true,
                customClass: {
                    confirmButton: 'btn btn-warning',
                    cancelButton: 'btn btn-danger'
                },
            }).then((willDelete) => {
                if (willDelete.isConfirmed) {
                    // $('#send-mail-pass').modal('toggle');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '/admin/client-management/remove-ib-access/' + user_id,
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            user_id: user_id,
                        },
                        success: function(data) {
                            if (data.success === true) {
                                let $url = '/admin/client-management/remove-ib-access/' + data.user_id + "?op=mail";
                                send_mail('Remove From IB', 'Please wait whilte we sending mail to user.', $url, true)
                                $("#root-table").DataTable().draw();
                            } else {
                                notify('error', data.message, 'Remove From IB');
                            }
                        }
                    })
                }
            });
        });
        // fix modal calender
        $(document).ready(function() {
            const flatpickr_time = $('.flatpickr_time').flatpickr({
                //static: position the calendar inside the wrapper and next to the input element*.
                static: true
            });
        });
        // update profile
        // update account details
        $(document).on('click', '#save-acc-info-btn2', function() {
            $(this).prop('disabled', true);
        })
        $(document).on('click', '#save-acc-info-btn', function() {
            $(this).prop('disabled', true);
        })
        $(document).on('click', '#save-personal-info-btn2', function() {
            $(this).prop('disabled', true);
        })
        $(document).on('click', '#save-personal-info-btn', function() {
            $(this).prop('disabled', true);
        })
        $(document).on('click', '#save-social-info-btn', function() {
            $(this).prop('disabled', true);
        })

        function update_acc_details(data) {
            if (data.status === true) {
                notify('success', data.message, 'Update profile account info');
                $("#btn-next-from-acc").trigger('click');
                $("#root-table").DataTable().draw();
            } else {
                notify('error', data.message, 'Update profile inccount info');
            }
            $("#save-acc-info-btn").prop('disabled', false);
            $.validator("account-details-modern", data.errors);
        }
        function update_acc_details2(data) {
            if (data.status === true) {
                notify('success', data.message, 'Update profile account info');
                // $("#btn-next-from-acc").trigger('click');
                $("#root-table").DataTable().draw();
            } else {
                notify('error', data.message, 'Update profile inccount info');
            }
            $("#save-acc-info-btn2").prop('disabled', false);
            $.validator("account-details-modern", data.errors);
        }
        // personal info update
        function update_personal_info(data) {
            if (data.status === true) {
                notify('success', data.message, 'Update personal info');
                $("#btn-next-from-acc").trigger('click');
                $("#root-table").DataTable().draw();
            } else {
                notify('error', data.message, 'Update personal info');
            }
            $("#save-personal-info-btn").prop('disabled', false);
            $.validator("personal-info-modern", data.errors);
        }
        function update_personal_info2(data) {
            if (data.status === true) {
                notify('success', data.message, 'Update personal info');
                $("#btn-next-from-acc").trigger('click');
                $("#root-table").DataTable().draw();
            } else {
                notify('error', data.message, 'Update personal info');
            }
            $("#save-personal-info-btn2").prop('disabled', false);
            $.validator("personal-info-modern", data.errors);
        }
        // social details
        function update_social_info(data) {
            if (data.status === true) {
                notify('success', data.message, 'Update social account info');
                $("#btn-next-from-social").trigger('click');
                $("#root-table").DataTable().draw();
            } else {
                notify('error', data.message, 'Update social account info');
            }
            $("#save-social-info-btn").prop('disabled', false);
            $.validator("address-step-modern", data.errors);
        }

        // select 2 with options descriptions
        $(function() {
            $('.transfered-account-err').html('');
            $(document).on('keypress', '.select2-search__field', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                }
            });

            // select2 for trader add
            $('#trader_added_filed').select2('destroy')
            $("#trader_added_filed").select2({
                tags: false,
                dropdownParent: $('#add-new-trading-account'),
                templateResult: formatOption,
                language: {
                    noResults: function() {
                        return "Find Trading Account Details";
                    }
                },
                ajax: {
                    url: "/search/removed_trading_account_details",
                    // type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            searchTerm: params.term // search term
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
            // option description for select2
            function formatOption(option) {
                var $option = $(
                    '<div><strong>' + option.name +
                    '</strong></div><div><strong>' +
                    option.account + '</strong></div>'
                );
                return $option;
            };
        });

        //all removed trading account ajax
        $(document).on('click', '#deleted-account-list-tab', function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/client-management/deleted-account-list',
                method: 'POST',
                dataType: 'json',
                success: function(data) {
                    $('#tranfer-account-no').html(data);
                    console.log(data);
                }
            });
        });

    $(document).on('click', '.delete-trader', function () {
    const traderId = $(this).data('id'); // Get the data-id attribute from the button

    // Show confirmation dialog using SweetAlert2
    Swal.fire({
        title: 'Are you sure?',
        text: 'You won’t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            // Make the AJAX call to delete the trader
            $.ajax({
                url: `/admin/trader/delete/${traderId}`, // The route to your delete function
                type: 'GET', // HTTP method
                success: function (response) {
                    if (response.success) {
                        Swal.fire(
                            'Deleted!',
                            response.message,
                            'success'
                        ).then(() => {
                            location.reload(); // Reload the page or update the UI dynamically
                        });
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    Swal.fire(
                        'Error!',
                        xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred.',
                        'error'
                    );
                },
            });
        }
    });
});
// console.log("ok")

// $(document).ready(function() {
    
//     // Remove any existing click event handlers on .dt-description
//     $(document).off('click', '.dt-description');

//     //     // Attach your custom click event
//         $(document).on('click', '.dt-description', function() {
//             var id = $(this).data('id'); // Get data-id attribute

//             if (id) {
//                 window.open('/admin/client-management/trader-admin-single/' + id, '_blank');
//             } else {
//                 console.log('ID not found');
//             }
//         });
// });


$(document).ready(function () {
    let selectedUsers = new Map(); // Stores selected users (userID -> {name, email, phone})

    // Function to update UI and hidden input
    function updateSelectedUI() {
        let userArray = Array.from(selectedUsers.keys());
        $("#selected_users").val(userArray.join(',')); // Update hidden input

        if (userArray.length > 0) {
            $(".bulk-assign").show();
        } else {
            $(".bulk-assign").hide();
        }

        renderSelectedUsers(); // Update selected users list
    }

    // Function to render selected users in bulk-assign div
    function renderSelectedUsers() {
        let container = $("#selected-users-list");
        container.empty(); // Clear previous list

        selectedUsers.forEach((user, id) => {
            let userRow = `
                <div class="selected-user-row d-flex justify-content-between align-items-center p-2 border rounded mb-1" data-id="${id}">
                    <div>
                        <strong>${user.name}</strong><br>
                        <small>${user.email} | ${user.phone}</small>
                    </div>
                    <button class="btn btn-danger btn-sm remove-user" data-id="${id}">Remove</button>
                </div>`;
            container.append(userRow);
        });
    }

    // Handle checkbox click event
    $(document).on("change", ".assign-to-manager", function () {
        let userId = $(this).data("id");
        let userName = $(this).data("name");
        let userEmail = $(this).data("email");
        let userPhone = $(this).data("phone");

        if ($(this).is(":checked")) {
            selectedUsers.set(userId, { name: userName, email: userEmail, phone: userPhone });
        } else {
            selectedUsers.delete(userId);
        }

        updateSelectedUI();
    });

    // Handle removing user from bulk-assign div
    $(document).on("click", ".remove-user", function () {
        let userId = $(this).data("id");
        selectedUsers.delete(userId);
        $(`.assign-to-manager[data-id="${userId}"]`).prop("checked", false); // Uncheck checkbox in DataTable
        updateSelectedUI();
    });

    // Handle AJAX request on button click
    $("#assign-btn").on("click", function () {
        let managerEmail = $("#manager_email").val();
        let userIds = $("#selected_users").val();

        if (!managerEmail || userIds.length === 0) {
            alert("Please enter a manager email and select at least one user.");
            return;
        }

        $.ajax({
            url: "{{ route('admin.trader-admin-bulk-assign-account-manager') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                manager_email: managerEmail,
                user_ids: Array.isArray(userIds) ? userIds.join(",") : userIds // Ensure it's an array
            },
            beforeSend: function () {
                Swal.fire({
                    title: "Assigning...",
                    text: "Please wait while we assign the account manager.",
                    icon: "info",
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function (response) {
                if (response.status) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(() => {
                        selectedUsers.clear(); // Clear selected users
                        updateSelectedUI(); // Refresh the UI
                        location.reload(); // Reload to update the datatable
                    });
                } else {
                    Swal.fire({
                        title: "Warning!",
                        text: response.message,
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    title: "Error!",
                    text: "Something went wrong. Please try again.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                console.error("AJAX Error:", status, error, xhr.responseText);
            }
        });


    });

    // Restore checked checkboxes and update UI when DataTable is redrawn (pagination, search, etc.)
    $('#root-table').on('draw.dt', function () {
        $(".assign-to-manager").each(function () {
            let userId = $(this).data("id");
            if (selectedUsers.has(userId)) {
                $(this).prop("checked", true); // Re-check previously selected checkboxes
            }
        });
    });
});

    </script>
    @stop
    <!-- BEGIN: page JS -->