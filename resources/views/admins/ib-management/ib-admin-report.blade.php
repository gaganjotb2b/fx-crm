@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'IB admin report')
@section('vendor-css')
    <!-- quill editor  -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css') }}">

    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/fonts/font-awesome/css/font-awesome.min.css') }}">
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
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/ib_admin.css') }}">

    <style>
        .input-group> :not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
            margin-left: 0px;
            padding-left: 20px;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* for Laptop */
        @media screen and (max-width: 1280px) and (min-width: 800px) {

            .ib-withdraw thead tr th:nth-child(3),
            .ib-withdraw tbody tr td:nth-child(3) {
                display: none;
            }

            .small-none {
                display: none;
            }
        }

        @media screen and (max-width: 1280px) and (min-width: 800px) {

            .ib-withdraw thead tr th:nth-child(6),
            .ib-withdraw tbody tr td:nth-child(6) {
                display: none;
            }

            .small-none-two {
                display: none;
            }
        }



        @media screen and (max-width: 1440px) and (min-width: 900px) {

            .ib-withdraw thead tr th:nth-child(6),
            .ib-withdraw tbody tr td:nth-child(6) {
                display: none;
            }

            .small-none {
                display: none;
            }
        }

        .add_field_button {
            margin-top: 22px;
        }

        .dark-layout .page-trader-admin .datatables-ajax tr,
        .dark-layout .page-ib-admin .datatables-ajax tr,
        .dark-layout .page-ib-admin .datatables-ajax td,
        .dark-layout .page-trader-admin .datatables-ajax td {
            background-color: #323c59 !important;
        }

        /* .page-ib-admin .dataTables_filter {
                    position: absolute;
                    top: -14px;
                    right: 0;
                    float: right;
                } */
        /*.update-resendactivation-btn {*/
        /*    display: none;*/
        /*}*/
    </style>
@stop

<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content page-ib-admin">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-fluid p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">{{ __('ib-management.IB Admin') }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}">{{ __('ib-management.Home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="#">{{ __('ib-management.Ib-Management') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ __('ib-management.IB Admin') }}</li>
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
                                <!-- card header -->
                                <div class="card-header border-bottom d-flex justfy-content-between">
                                    <h4 class="card-title">{{ __('ib-management.filter_report') }}</h4>
                                    <div class="btn-exports d-flex justify-content-between">
                                        <!-- export button -->
                                        <select data-placeholder="Select a state..." class="select2-icons form-select"
                                            id="fx-export">
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
                                    <form id="filterForm" class="dt_adv_search" method="get">
                                        <div class="row">
                                            <!-- filter by ib name / email / phone -->
                                            <div class="col-md-4 mb-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ $varsion == 'pro' ? 'IB Email / Name / Phone / Country' : 'IB Email / Name / Phone' }}">
                                                <label for="multiple_filter" class="form-level">IB Info</label>
                                                <input type="text" class="form-control dt-input" data-column="1"
                                                    name="ib_info" id="multiple_filter"
                                                    placeholder="{{ $varsion == 'pro' ? 'IB Email / Name / Phone / Country' : 'IB Email / Name / Phone' }}"
                                                    data-column-index="1" />
                                            </div>
                                            <!-- trader name / email / phone / country -->
                                            <div class="col-md-4 mb-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ $varsion == 'pro' ? 'Trader Email/Name/Country/Phone' : 'Trader Email/Name/Phone' }}">
                                                <label for="multiple_filter_trader" class="form-leve">Trader Info</label>
                                                <input type="text" class="form-control dt-input" data-column="1"
                                                    name="trader_info" id="multiple_filter_trader"
                                                    placeholder="{{ $varsion == 'pro' ? 'Trader Name / Email / Phone / country' : 'Trader Name / Email / Phone ' }}"
                                                    data-column-index="1" />
                                            </div>
                                            <!-- filter by verification status -->
                                            <div class="col-md-4 mb-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Status">
                                                <label for="verification_status" class="form-leve">KYC Status</label>
                                                <select class="select2 form-select" id="verification_status"
                                                    name="verification_status">
                                                    <option value="&nbsp;">All</option>
                                                    <option value="2">{{ __('client-management.Pending') }}
                                                    </option>
                                                    <option value="1">{{ __('client-management.verified') }}
                                                    </option>
                                                    <option value="0">{{ __('client-management.unverified') }}
                                                    </option>
                                                </select>
                                            </div>
                                            <!-- filter by group name -->
                                            <div class="col-md-4 mb-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="IB Group">
                                                <label for="ib-group" class="form-leve">IB Group</label>
                                                <select class="select2 form-select" name="ib_group" id="ib-group">
                                                    <option value="">All</option>
                                                    @foreach ($ib_group as $row)
                                                        <option value="{{ $row->id }}">{{ $row->group_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- filter by master IB / Sub IB -->
                                            <div class="col-md-4 mb-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="IB Status">
                                                <label for="ib_type" class="form-leve">Master IB / Sub IB</label>
                                                <select class="select2 form-select" id="ib_type" name="ib_type">
                                                    <option value="&nbsp;">All</option>
                                                    <option value="master_ib">Master IB</option>
                                                    <option value="sub_ib">Sub IB</option>
                                                </select>
                                            </div>
                                            <!-- filter by trader account -->
                                            <div class="col-md-4 mb-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Trading Account">
                                                <label for="trading_account" class="form-leve">Account Number</label>
                                                <input type="text" class="form-control dt-input dt-full-name"
                                                    data-column="1" name="trading_account" id="trading_account"
                                                    placeholder="Trading Account" data-column-index="0" />
                                            </div>
                                            <!-- filter by account manager -->
                                            @if ($varsion == 'pro')
                                                <div class="col-md-4 mb-1" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Account Manager">
                                                    <label for="account_manager" class="form-leve">Managers</label>
                                                    <input type="text" class="form-control dt-input" data-column="1"
                                                        name="account_or_desk_manager" id="account_manager"
                                                        placeholder="Account Manager / Desk Manager"
                                                        data-column-index="1" />
                                                </div>
                                            @else
                                                <div class="col-md-4 mb-1" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Country">
                                                    <label for="account_manager" class="form-leve">Country</label>
                                                    <select class="select2 form-select" name="country">
                                                        <option value="">{{ __('client-management.All') }}</option>
                                                        @foreach ($countries as $value)
                                                            <option value="{{ $value->name }}">{{ $value->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            @endif
                                            <!-- filter by category -->
                                            <div class="col-md-4 mb-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Category">
                                                <label for="ib-category" class="form-leve">Category</label>
                                                <select class="select2 form-select" id="ib-category" name="category">
                                                    <option value="&nbsp;">All</option>
                                                    @foreach ($category as $value)
                                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- filter by date range -->
                                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Date">
                                                <label for="date-range" class="form-leve">Joining Date Range</label>
                                                <div class="input-group" data-date="2017/01/01"
                                                    data-date-format="yyyy/mm/dd">
                                                    <span class="input-group-text">
                                                        <div class="icon-wrapper">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="50"
                                                                height="50" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-calendar">
                                                                <rect x="3" y="4" width="18" height="18"
                                                                    rx="2" ry="2"></rect>
                                                                <line x1="16" y1="2" x2="16"
                                                                    y2="6"></line>
                                                                <line x1="8" y1="2" x2="8"
                                                                    y2="6"></line>
                                                                <line x1="3" y1="10" x2="21"
                                                                    y2="10"></line>
                                                            </svg>
                                                        </div>
                                                    </span>
                                                    <input id="date_from" type="text" name="date_from"
                                                        class="form-control flatpickr-basic" placeholder="">
                                                    <span class="input-group-text">To</span>
                                                    <input id="date_to" type="text" name="date_to"
                                                        class="form-control flatpickr-basic" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <!-- filter by active status -->
                                                <label class="form-label" for="active-status">Active Status</label>
                                                <select class="select2 form-select" id="active-status"
                                                    name="active_status">
                                                    <option value="">{{ __('client-management.All') }}</option>
                                                    <option value="1">{{ __('client-management.Active') }}
                                                    </option>
                                                    <option value="2">Block</option>
                                                </select>
                                            </div>
                                            <!-- filter reset button -->
                                            <div class="col-md-4 text-right" data-bs-toggle="tooltip"
                                                data-bs-placement="top">
                                                <label for=""></label>
                                                <button id="resetBtn" type="button"
                                                    class="btn btn-secondary w-100 waves-effect waves-float waves-light">
                                                    <span class="align-middle">{{ __('ib-management.reset') }}</span>
                                                </button>
                                            </div>
                                            <!-- filter button -->
                                            <div class="col-md-4 text-right" data-bs-toggle="tooltip"
                                                data-bs-placement="top">
                                                <label for=""></label>
                                                <button id="filterBtn" type="button" name="filter" value="filter"
                                                    class="btn btn-primary w-100 waves-effect waves-float waves-light">
                                                    <span class="align-middle">{{ __('ib-management.FILTER') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <hr class="my-0" />
                            </div>
                            <div class="card">
                                <div class="card-datatable m-1 table-responsive">
                                    <!-- data table -->
                                    <table class="datatables-ajax ib-withdraw table" id="ib-tbl-root">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ib-management.name') }}</th>
                                                <th>{{ __('ib-management.email') }}</th>
                                                <th>{{ __('ib-management.Phone') }}</th>
                                                <th>{{ __('ib-management.Country') }}</th>
                                                <th>{{ __('ib-management.Group') }}</th>
                                                <th>{{ __('ib-management.Joined') }}</th>
                                                <th>{{ __('ib-management.status') }}</th>
                                                <th>{{ __('ib-management.ACTIONS') }}</th>
                                            </tr>
                                        </thead>
                                    </table>
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
    <div class="modal fade text-start modal-primary" id="primary" tabindex="-1" aria-labelledby="myModalLabel160"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="{{ route('admin.comment-trader-admin-form') }}" method="post" id="form-add-comment">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel160">Comment to - <span class="comment-to"></span></h5>
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
                                                                        <option value="inconsolata">Inconsolata</option>
                                                                        <option value="ubuntu">Ubuntu Mono</option>
                                                                    </select>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button type="button" class="ql-bold"></button>
                                                                    <button type="button" class="ql-italic"></button>
                                                                    <button type="button" class="ql-underline"></button>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button type="button" class="ql-list"
                                                                        value="ordered"></button>
                                                                    <button type="button" class="ql-list"
                                                                        value="bullet"></button>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button type="button" class="ql-link"></button>
                                                                    <button type="button" class="ql-image"></button>
                                                                    <button type="button" class="ql-video"></button>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button type="button" class="ql-formula"></button>
                                                                    <button type="button" class="ql-code-block"></button>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button type="button" class="ql-clean"></button>
                                                                </span>
                                                            </div>
                                                            <div class="editor" style="min-height:150px">

                                                            </div>
                                                            <textarea name="comment" style="display: none;" id="text_quill"></textarea>
                                                            <input type="hidden" name="ib_id" id="ib_id">
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
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                            id="save-comment-btn">Save Comment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal update comments -->
    <div class="modal fade text-start modal-primary" id="comment-edit" tabindex="-1" aria-labelledby="myModalLabel160"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="{{ route('admin.comment-trader-admin-update-form') }}" method="post"
                id="form-update-comment">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel160">Comment update to - <span class="comment-to"></span>
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
                                                                        <option value="inconsolata">Inconsolata</option>
                                                                        <option value="ubuntu">Ubuntu Mono</option>
                                                                    </select>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button type="button" class="ql-bold"></button>
                                                                    <button type="button" class="ql-italic"></button>
                                                                    <button type="button" class="ql-underline"></button>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button type="button" class="ql-list"
                                                                        value="ordered"></button>
                                                                    <button type="button" class="ql-list"
                                                                        value="bullet"></button>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button type="button" class="ql-link"></button>
                                                                    <button type="button" class="ql-image"></button>
                                                                    <button type="button" class="ql-video"></button>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button type="button" class="ql-formula"></button>
                                                                    <button type="button" class="ql-code-block"></button>
                                                                </span>
                                                                <span class="ql-formats">
                                                                    <button type="button" class="ql-clean"></button>
                                                                </span>
                                                            </div>
                                                            <div class="editor" style="min-height:150px">

                                                            </div>
                                                            <textarea name="comment" style="display: none;" id="text_quill_update"></textarea>
                                                            <input type="hidden" name="ib_id" id="ib_id-update">
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
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                            id="update-comment-btn">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Themes end -->

    <!-- Modal password change -->
    <div class="modal fade text-start modal-primary change-password-modal" id="" tabindex="-1"
        aria-labelledby="myModalLabel160" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel160">Password Change for - <span
                            class="password-change-for"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Snow Editor start -->
                    <section class="snow-editor">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="auth-wrapper auth-basic px-2">
                                                    <div class="auth-inner my-2">
                                                        <!-- Reset Password basic -->
                                                        <div class="card mb-0">
                                                            <div class="card-body">
                                                                <h4 class="card-title mb-1">Change Password ðŸ”’</h4>
                                                                <p class="card-text mb-2">Your new password must be
                                                                    different from previously used passwords</p>

                                                                <form class="auth-reset-password-form mt-2"
                                                                    id="change-password-form"
                                                                    action="{{ route('admin.change-password-trader-admin') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="mb-1">
                                                                        <div class="d-flex justify-content-between">
                                                                            <label class="form-label"
                                                                                for="reset-password-new">New
                                                                                Password</label>
                                                                        </div>
                                                                        <div
                                                                            class="input-group input-group-merge form-password-toggle">
                                                                            <input type="password"
                                                                                class="form-control form-control-merge"
                                                                                id="reset-password-new" name="password"
                                                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                                                aria-describedby="reset-password-new"
                                                                                tabindex="1" autofocus />
                                                                            <span
                                                                                class="input-group-text cursor-pointer"><i
                                                                                    data-feather="eye"></i></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-1">
                                                                        <div class="d-flex justify-content-between">
                                                                            <label class="form-label"
                                                                                for="reset-password-confirm">Confirm
                                                                                Password</label>
                                                                        </div>
                                                                        <div
                                                                            class="input-group input-group-merge form-password-toggle">
                                                                            <input type="password"
                                                                                class="form-control form-control-merge"
                                                                                id="reset-password-confirm"
                                                                                name="password_confirmation"
                                                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                                                aria-describedby="reset-password-confirm"
                                                                                tabindex="2" />
                                                                            <span
                                                                                class="input-group-text cursor-pointer"><i
                                                                                    data-feather="eye"></i></span>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="trader_id"
                                                                        id="ib-id">
                                                                    <button type="button" class="btn btn-primary w-100"
                                                                        id="set-new-password" onclick="_run(this)"
                                                                        data-el="fg" data-form="change-password-form"
                                                                        data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                                                        data-callback="change_password_call_back"
                                                                        data-btnid="set-new-password">Set new
                                                                        password</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- /Reset Password basic -->
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

                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Password change -->


    <!-- Modal sending mail-->
    <div class="modal fade text-start modal-success send-mail-pass" tabindex="-1" aria-labelledby="mail-sending-modal"
        aria-hidden="true">
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
    <!-- Modal sending mail end-->

    <!-- Modal transaction password change -->
    <div class="modal fade text-start modal-primary change-transaction-password-modal" id="" tabindex="-1"
        aria-labelledby="myModalLabel160" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel160">Transaction Pin Change for - <span
                            class="transaction-password-change-for"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Snow Editor start -->
                    <section class="snow-editor">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="auth-wrapper auth-basic px-2">
                                                    <div class="auth-inner my-2">
                                                        <!-- Reset Password basic -->
                                                        <div class="card mb-0">
                                                            <div class="card-body">
                                                                <h4 class="card-title mb-1">Change Transaction pin ðŸ”’
                                                                </h4>
                                                                <p class="card-text mb-2">Your new transaction pin must be
                                                                    different from previously used transaction pin</p>

                                                                <form class="auth-reset-password-form mt-2"
                                                                    id="change-pin-form"
                                                                    action="{{ route('admin.change-pin-trader-admin') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <div class="mb-1">
                                                                        <div class="d-flex justify-content-between">
                                                                            <label class="form-label"
                                                                                for="reset-password-new2">New Transaction
                                                                                Pin</label>
                                                                        </div>
                                                                        <div
                                                                            class="input-group input-group-merge form-password-toggle">
                                                                            <input type="password"
                                                                                class="form-control form-control-merge"
                                                                                id="reset-password-new2"
                                                                                name="transaction_pin"
                                                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                                                aria-describedby="reset-password-new2"
                                                                                tabindex="1" autofocus />
                                                                            <span
                                                                                class="input-group-text cursor-pointer"><i
                                                                                    data-feather="eye"></i></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-1">
                                                                        <div class="d-flex justify-content-between">
                                                                            <label class="form-label"
                                                                                for="reset-password-confirm2">Confirm
                                                                                Transaction
                                                                                Pin</label>
                                                                        </div>
                                                                        <div
                                                                            class="input-group input-group-merge form-password-toggle">
                                                                            <input type="password"
                                                                                class="form-control form-control-merge"
                                                                                id="reset-password-confirm2"
                                                                                name="transaction_pin_confirm"
                                                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                                                aria-describedby="reset-password-confirm"
                                                                                tabindex="2" />
                                                                            <span
                                                                                class="input-group-text cursor-pointer"><i
                                                                                    data-feather="eye"></i></span>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="trader_id"
                                                                        class="ib-id">
                                                                    <button type="button"
                                                                        class="btn btn-primary mb-1 text-center w-100"
                                                                        id="set-new-pin" onclick="_run(this)"
                                                                        data-el="fg" data-form="change-pin-form"
                                                                        data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                                                        data-callback="change_trans_pin_call_back"
                                                                        data-btnid="set-new-pin" style="width:200px">Set
                                                                        New Pin</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- /Reset Password basic -->
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

                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Password change -->

    <!-- IB Admin profile update modal start-->
    <div class="modal fade text-start modal-primary" id="profile-update-modal" tabindex="-1"
        aria-labelledby="Profile update" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" id="update-profile-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="mail-sending-modal">Profile Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <section class="modern-horizontal-wizard">
                        <div class="bs-stepper wizard-modern modern-wizard-example">
                            <div class="bs-stepper-header">
                                <!-- step account details -->
                                <div class="step" data-target="#account-details-modern" role="tab"
                                    id="account-details-modern-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="file-text" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Account Details</span>
                                            <span class="bs-stepper-subtitle">Setup Account Details</span>
                                        </span>
                                    </button>
                                </div>
                                <div class="line">
                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                </div>
                                <!-- step personal info -->
                                <div class="step" data-target="#personal-info-modern" role="tab"
                                    id="personal-info-modern-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="user" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Personal Info</span>
                                            <span class="bs-stepper-subtitle">Add Personal Info</span>
                                        </span>
                                    </button>
                                </div>
                                <div class="line">
                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                </div>
                                <!-- step social -->
                                <div class="step" data-target="#social-links-modern" role="tab"
                                    id="social-links-modern-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="link" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Social Links</span>
                                            <span class="bs-stepper-subtitle">Add Social Links</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <!-- start stepper content -->
                            <div class="bs-stepper-content">
                                <!-- step account details content -->
                                <form action="{{ route('ibadmin.ib-profile-update.account-details') }}" method="post"
                                    id="account-details-modern" class="content" role="tabpanel"
                                    aria-labelledby="account-details-modern-trigger">
                                    <input type="hidden" name="pro_user_id" value=""
                                        class="update-profile-user-id">
                                    @csrf
                                    <div class="content-header">
                                        <h5 class="mb-0">Account Details</h5>
                                        <small class="text-muted">Enter Your Account Details.</small>
                                    </div>
                                    <div class="row">
                                        <!-- email  of ib-->
                                        <div class="col-xl-6 col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="pro_email">Email</label>
                                                <input type="text" class="form-control" id="pro_email" name="email"
                                                    placeholder="Enter email" />
                                            </div>
                                        </div>
                                        <!-- group -->
                                        <div class="col-xl-6 col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="pro_group">Group</label>
                                                <select name="pro_group" class="form-select form-control" id="pro_group">
                                                    @foreach ($ib_group as $value)
                                                        <option value="{{ $value->id }}">{{ $value->group_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- password -->
                                        <div class="col-xl-6 col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="pro_password">Password</label>
                                                <div class="input-group input-group-merge form-password-toggle mb-2">
                                                    <span class="input-group-text cursor-pointer"><i
                                                            data-feather="eye"></i></span>
                                                    <input data-size="16" data-character-set="a-z,A-Z,0-9,#"
                                                        rel="gp" type="password" name="pro_password"
                                                        class="form-control copy_clipboard" id="pro_password"
                                                        placeholder="Your Password" aria-describedby="password" />
                                                    <button
                                                        class="btn btn-primary waves-effect waves-float waves-light btn-gen-password"
                                                        type="button" id="rstButton"><i class="fas fa-key"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- transaction pin -->
                                        <div class="col-xl-6 col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="pro_transaction_pin">Transaction
                                                    Pin</label>
                                                <div class="input-group input-group-merge form-password-toggle mb-2">
                                                    <span class="input-group-text cursor-pointer"><i
                                                            data-feather="eye"></i></span>
                                                    <input data-size="16" data-character-set="a-z,A-Z,0-9,#"
                                                        rel="gp" type="password" name="pro_transaction_pin"
                                                        class="form-control copy_clipboard" id="pro_transaction_pin"
                                                        placeholder="Your Transaction Pin"
                                                        aria-describedby="Transaction pin" />
                                                    <button
                                                        class="btn btn-primary waves-effect waves-float waves-light btn-gen-password"
                                                        type="button" id="rstButton"><i class="fas fa-key"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- verification status -->
                                        <div class="col-xl-6 col-md-6 col-12">
                                            <!-- kyc checkbox -->
                                            <div class="form-check form-check-success">
                                                <input type="checkbox" class="form-check-input" name="kyc_status"
                                                    id="verified" />
                                                <label class="form-check-label" for="verified">KYC Verified</label>
                                            </div>
                                        </div>
                                        <!-- email notification -->
                                        <div class="col-xl-6 col-md-6 col-12">
                                            <div class="mb-1">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" name="pro_send_email" type="checkbox"
                                                        id="pro_email_send" />
                                                    <label class="form-check-label" for="pro_email_send">Send Notification
                                                        By
                                                        Email</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- button account details -->
                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="button" class="btn btn-primary" id="save-acc-info-btn2"
                                            onclick="_run(this)" data-el="fg" data-form="account-details-modern"
                                            data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                            data-callback="update_acc_details2" data-btnid="save-acc-info-btn2">
                                            Save
                                        </button>
                                        <div>
                                            <button class="btn btn-outline-secondary btn-prev" disabled type="button">
                                                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                            </button>
                                            <button class="btn btn-primary btn-next visually-hidden" type="button"
                                                id="btn-next-from-acc">
                                                <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary" id="save-acc-info-btn"
                                                onclick="_run(this)" data-el="fg" data-form="account-details-modern"
                                                data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                                data-callback="update_acc_details" data-btnid="save-acc-info-btn">
                                                Save & Next
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!-- step personal content -->
                                <form action="{{ route('ibadmin.ib-profile-update.personal-info') }}" method="post"
                                    id="personal-info-modern" class="content" role="tabpanel"
                                    aria-labelledby="personal-info-modern-trigger">
                                    <input type="hidden" name="pro_user_id" value=""
                                        class="update-profile-user-id">
                                    @csrf
                                    <div class="content-header">
                                        <h5 class="mb-0">Personal Info</h5>
                                        <small>Enter Your Personal Info.</small>
                                    </div>
                                    <div class="row">
                                        <!-- full name of ib -->
                                        <div class="col-xl-6 col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="pro_name">Full Name</label>
                                                <input type="text" class="form-control" id="pro_name"
                                                    name="pro_name" placeholder="Full Name" />
                                            </div>
                                        </div>
                                        <!-- phone number of ib-->
                                        <div class="col-xl-6 col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="pro_phone">Phone</label>
                                                <input type="text" class="form-control" id="pro_phone"
                                                    name="pro_phone" placeholder="Enter phone" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- country of ib-->
                                        <div class="col-xl-6 col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="pro_country">Country</label>
                                                <select name="pro_country" class="form-select form-control"
                                                    id="pro_country">
                                                    @foreach ($countries as $value)
                                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- city -->
                                        <div class="col-xl-6 col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="pro_city">City</label>
                                                <input type="text" class="form-control" id="pro_city"
                                                    name="pro_city" placeholder="2.00" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- state -->
                                        <div class="col-xl-6 col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="pro_state">State</label>
                                                <input type="text" class="form-control" id="pro_state"
                                                    name="pro_state" placeholder="State name" />
                                            </div>
                                        </div>
                                        <!-- zip code -->
                                        <div class="col-xl-6 col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="pro_zip_code">Zip Code</label>
                                                <input type="text" class="form-control" id="pro_zip_code"
                                                    name="pro_zip_code" placeholder="zip code" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- Address -->
                                        <div class="col-xl-12 col-md-12 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="pro_address">Address</label>
                                                <div class="form-floating mb-0">
                                                    <textarea data-length="191" name="pro_address" class="form-control char-textarea" id="pro_address" rows="2"
                                                        placeholder="Counter" style="height: 75px"></textarea>
                                                </div>
                                                <small class="textarea-counter-value float-end"><span
                                                        class="char-count">0</span> / 191
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- buttons personal info -->
                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="button" class="btn btn-primary" id="save-personal-info-btn2"
                                            onclick="_run(this)" data-el="fg" data-form="personal-info-modern"
                                            data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                            data-callback="update_personal_info2" data-btnid="save-personal-info-btn2">
                                            Save
                                        </button>
                                        <div>
                                            <button class="btn btn-primary btn-prev" type="button">
                                                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                            </button>
                                            <button class="btn btn-primary btn-next visually-hidden" type="button"
                                                id="btn-next-from-personal">
                                                <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary" id="save-personal-info-btn"
                                                onclick="_run(this)" data-el="fg" data-form="personal-info-modern"
                                                data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                                data-callback="update_personal_info" data-btnid="save-personal-info-btn">
                                                Save & Next
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!-- step sicical content -->
                                <form action="{{ route('ibadmin.ib-profile-update.social-info') }}" method="post"
                                    id="social-links-modern" class="content" role="tabpanel"
                                    aria-labelledby="social-links-modern-trigger">
                                    <input type="hidden" name="pro_user_id" value=""
                                        class="update-profile-user-id">
                                    @csrf
                                    <div class="content-header">
                                        <h5 class="mb-0">Social Links</h5>
                                        <small>Update Social Links.</small>
                                    </div>
                                    <div class="row">
                                        <!-- social facebook -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="facebook"> <i
                                                    class="fab fa-facebook"></i>Facebook</label>
                                            <input type="text" name="facebook" id="facebook" class="form-control"
                                                placeholder="https://facebook.com/user-url" />
                                        </div>
                                        <!-- social twitter -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="twitter"><i
                                                    class="fab fa-twitter"></i>Twitter</label>
                                            <input type="text" name="twitter" id="twitter" class="form-control"
                                                placeholder="https://twitter.com/user-url" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- telegram -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="telegram"><i
                                                    class="fab fa-telegram"></i>Telegram</label>
                                            <input type="text" name="telegram" id="telegram" class="form-control"
                                                placeholder="https://telegram.com/user-65892152415" />
                                        </div>
                                        <!-- linkedin -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="linkedin"><i
                                                    class="fab fa-linkedin"></i>Linkedin</label>
                                            <input type="text" name="linkedin" id="linkedin" class="form-control"
                                                placeholder="https://linkedin.com/user-url" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- whatsapp -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="whatsapp"><i
                                                    class="fab fa-whatsapp"></i>Whatsapp</label>
                                            <input type="text" name="whatsapp" id="whatsapp" class="form-control"
                                                placeholder="https://telegram.com/user-65892152415" />
                                        </div>
                                        <!-- skype -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="skype"><i
                                                    class="fab fa-skype"></i>Skype</label>
                                            <input type="text" name="skype" id="skype" class="form-control"
                                                placeholder="https://linkedin.com/user-url" />
                                        </div>
                                    </div>
                                    <!-- social prev next  -->
                                    <div class="d-flex justify-content-between mt-3">
                                        <!-- button previous -->
                                        <button class="btn btn-primary btn-prev" type="button">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <!-- hidden button next -->
                                        <button type="button" class="btn btn-primary" id="save-social-info-btn"
                                            onclick="_run(this)" data-el="fg" data-form="social-links-modern"
                                            data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                            data-callback="update_sicial_info" data-btnid="save-social-info-btn">
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <!-- IB Admin profile update  modal end-->

    <!-- Added Trader Or Sub IB Modal-->
    <div class="modal fade text-start modal-primary" id="trader-sub-ib-modal" tabindex="-1"
        aria-labelledby="Profile update" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <form action="{{ route('trader-sub-ib-update') }}" method="post" class="modal-content"
                id="added-trader-subIB">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="mail-sending-modal">Add Trader Or Sub IB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 ">
                    <!-- hidden input for id -->
                    <input type="hidden" name="ib_id" value="" id="trader_ib_id">
                    <!-- full name of ib -->
                    <p>If you want to add sub IB or Trader, please choose them by email. You Can add multiple at a time. If
                        any user alreay have a parent IB, you can't add them as sub IB/Trader.</p>
                    <div class="form-group fg">
                        <label for="trader-or-ib">Choose Trader or IB</label>
                        <select class="select2-size-lg form-select select2-whith-des select2" name="reference_id[]"
                            multiple="multiple" id="sub-ib-ref-add">

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-1 text-center" id="reset_btn_trader"
                        style="width:200px">Reset</button>
                    <button type="button" class="btn btn-primary mb-1 text-center" id="btn-added-client"
                        onclick="_run(this)" data-el="fg" data-form="added-trader-subIB"
                        data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                        data-callback="added_client_call_back" data-btnid="btn-added-client"
                        style="width:200px">Save</button>
                </div>
            </form>
        </div>
    </div>




    <!-- Added  Sub IB Modal-->
    <div class="modal fade text-start modal-primary" id="sub-ib-modal" tabindex="-1"
        aria-labelledby="Profile update" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <form action="{{ route('trader-sub-ib-update') }}" method="post" class="modal-content"
                id="added-sub-ib-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="mail-sending-modal">Add Sub IB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 ">
                    <!-- hidden input for id -->
                    <input type="hidden" name="ib_id" value="" id="show_sub_ib_id">
                    <!-- full name of ib -->
                    <p>If you want to add sub IB, please choose them by email. You Can add multiple at a time. If
                        any user alreay have a parent IB, you can't add them as sub IB.</p>
                    <div class="form-group fg">
                        <label for="trader-or-ib">Choose IB</label>
                        <select class="select2-size-lg form-select select2-whith-des select2" name="reference_id[]"
                            multiple="multiple" id="sub_ib_add">

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-1 text-center" id="reset_btn_sub_ib"
                        style="width:200px">Reset</button>
                    <button type="button" class="btn btn-primary mb-1 text-center" id="btn-added-sub-ib"
                        onclick="_run(this)" data-el="fg" data-form="added-sub-ib-form"
                        data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                        data-callback="added_sub_ib_call_back" data-btnid="btn-added-sub-ib"
                        style="width:200px">Save</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Added  End IB Modal-->

    <!-- Added  Trader Modal-->
    <div class="modal fade text-start modal-primary" id="trader-added-modal" tabindex="-1"
        aria-labelledby="Profile update" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <form action="{{ route('trader-sub-ib-update') }}" method="post" class="modal-content"
                id="added-trader-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="mail-sending-modal">Add Trader</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 ">
                    <!-- hidden input for id -->
                    <input type="hidden" name="ib_id" value="" id="show_trader_id">
                    <!-- full name of ib -->
                    <p>If you want to add Trader, please choose them by email. You Can add multiple at a time. If
                        any user alreay have a parent IB, you can't add them as sub IB.</p>
                    <div class="form-group fg">
                        <label for="trader-or-ib">Choose Trader</label>
                        <select class="select2-size-lg form-select select2-whith-des select2" name="reference_id[]"
                            multiple="multiple" id="trader_added_filed">

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-1 text-center" id="reset_btn_sub_ib"
                        style="width:200px">Reset</button>
                    <button type="button" class="btn btn-primary mb-1 text-center" id="btn-added-trader-id"
                        onclick="_run(this)" data-el="fg" data-form="added-trader-form"
                        data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                        data-callback="added_sub_ib_call_back" data-btnid="btn-added-trader-id"
                        style="width:200px">Save</button>
                </div>
            </form>
        </div>
    </div>
    <!-- End Trader added Modal-->

    <!-- Remove Sub IB or Trader  -->
    <div class="modal fade text-start modal-primary" id="remove-trader-ib-modal" tabindex="-1"
        aria-labelledby="addNewCardTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-md">
            <form action="{{ route('show.ib.email') }}" method="post" id="remove-trader-subIB"
                class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="mail-sending-modal">Remove Trader Or Sub IB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- hidden input for id -->
                        <input type="hidden" name="remove_ib_id" value="" id="remove_ib_id">
                        <!-- full name of ib -->
                        <div class="col-xl-12 col-md-12 col-12">
                            <div class="mb-1 fg">
                                <label class="form-label" for="pro_name">Enter Trader or IB Email</label>
                                <!-- <input type="text" class="form-control" id="client" name="remove_ib_id" placeholder="Enter Trader or Sub IB Email" /> -->
                                <select class="select2-size-lg form-select select2-whith-des select2"
                                    name="reference_id[]" multiple="multiple" id="selec2-remove-subib">

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-1 text-center" id="reset_btn"
                        style="width:200px">Reset</button>
                    <button type="button" class="btn btn-primary mb-1 text-center" id="btn-remove-client"
                        onclick="_run(this)" data-el="fg" data-form="remove-trader-subIB"
                        data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                        data-callback="remove_client_call_back" data-btnid="btn-remove-client"
                        style="width:200px">Remove Client</button>
                </div>
            </form>
        </div>
    </div>
    <!--/ add new card modal  -->

    </form>
    </div>
    </div>
    <!-- Modal Themes end -->
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
    <script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
    <script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
    <!-- datatable -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>

    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>
@stop
<!-- END: page vendor js -->

<!-- BEGIN: page JS -->
@section('page-js')
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/tables/table-datatable-ib-admin.js') }}"></script>
    <script src="{{ asset('/common-js/copy-js.js') }}"></script>
    <script src="{{ asset('/common-js/password-gen.js') }}"></script>
    <script src="{{ asset('common-js/ib-admin/ib-mail-sending.js') }}"></script>
    <script src="{{ asset('/common-js/add-sub-ib-trader.js') }}"></script>
    <script>
        // select 2 with options descriptions
        $(function() {
            $(document).on('keypress', '.select2-search__field', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                }
            });
            $('#sub-ib-ref-add').select2('destroy')
            $("#sub-ib-ref-add").select2({
                tags: false,
                dropdownParent: $('#trader-sub-ib-modal'),
                templateResult: formatOption,
                formatNoMatches: function() {
                    return "Nothing found";
                },
                ajax: {
                    url: "{{ route('serch.ib-reference') }}",
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
            // select2 for sub ib remove
            $('#selec2-remove-subib').select2('destroy')
            $("#selec2-remove-subib").select2({
                tags: false,
                dropdownParent: $('#remove-trader-ib-modal'),
                templateResult: formatOption,
                ajax: {
                    url: "{{ route('serch.references_user') }}",
                    // type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            searchTerm: params.term, // search term
                            ib_id: $("#remove_ib_id").val()
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
                    '<div><strong>' + option.text + '</strong></div><div>' + option.title +
                    '</div><div>Has Parent IB: ' + option.parent + '</div>'
                );
                return $option;
            };
        });
        // end select2 option description

        //show hidden id
        $(document).on("click", ".manage-trader-btn", function() {
            let __this = $(this);
            let trader_subIB = $(this).data('ib_id');
            $('#trader_ib_id').val(trader_subIB);
        })
        $(document).on("click", ".manage-sub-ib-btn", function() {
            let __this = $(this);
            let trader_subIB = $(this).data('ib_id');
            $('#remove_ib_id').val(trader_subIB);

        });
        //added field

        //remove dropdown item's sibling active class when this class is active
        $(document).on("click", ".custom-dropdown", function() {
            $(this).siblings().removeClass("active");
        })

        // get data for profile update start
        $(document).on('click', '.update-profile-btn', function() {

            let __this = $(this);
            let id = $(this).data('ib_id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/ib-management/update-profile-getdata',
                method: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                },
                success: function(data) {

                    $('#pro_name').val(data.user.name);
                    $('#pro_email').val(data.user.email);
                    $('#pro_phone').val(data.user.phone);
                    $('#pro_country option[value="' + data.user.country_id + '"]').prop('selected',
                        true);
                    $('#pro_group option[value="' + data.ib_group.id + '"]').prop('selected', true);
                    $('#pro_verification_status option[value="' + data.user.active_status + '"]').prop(
                        'selected', true);
                    $('#pro_city').val(data.user.city);
                    $('#pro_state').val(data.user.state);
                    $('#pro_zip_code').val(data.user.zip_code);
                    $('#pro_address').val(data.user.address);
                    $('#pro_transaction_pin').val(data.transaction_password);
                    $('#pro_password').val(data.password);
                    $('#pro_user_id').val(data.user.user_id);
                    // get social info
                    $("#profile-update-modal").find('#facebook').val(data.social.facebook);
                    $("#profile-update-modal").find('#twitter').val(data.social.twitter);
                    $("#profile-update-modal").find('#whatsapp').val(data.social.whatsapp);
                    $("#profile-update-modal").find('#linkedin').val(data.social.linkedin);
                    $("#profile-update-modal").find('#skype').val(data.social.skype);
                    $("#profile-update-modal").find('#telegram').val(data.social.telegram);
                    // set value each user id
                    $(".update-profile-user-id").each(function() {
                        $(this).val(id);
                    });
                    // verification status
                    // alert(data.user.kyc_status);
                    if (data.user.kyc_status == 1) {
                        $("#verified").prop('checked', true);
                    } else {
                        $("#verified").prop('checked', false);
                    }
                }
            })
        });
        // get data for profile update end

        // genrate random password
        $(document).on('click', ".btn-gen-password", function() {
            var field = $(this).closest('div').find('input[rel="gp"]');
            field.val(rand_string(field));
            field.attr('type', 'text');
        });
        // genrate random transaction password
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
        //call back for trader-subib
        function added_client_call_back(data) {
            if (data.status == true) {
                notify('success', data.message, 'Client Added');

                $('#trader-sub-ib-modal').modal('toggle');
                dt.draw();
            } else {
                notify('error', data.message, 'Failed');
            }
            $.validator("added-trader-subIB", data.errors);
        }

        //call back for remove trader-subib
        function remove_client_call_back(data) {
            if (data.status === true) {
                notify('success', data.message, 'Client Remove');
                $('#remove-trader-ib-modal').modal('toggle');
                dt.draw();
            } else {
                notify('error', data.message, 'Failed');
            }
            $.validator("remove-trader-subIB", data.errors);
        }
        // update profile start
        // update profile account details
        $(document).on('click', "#save-acc-info-btn2", function() {
            $(this).prop('disabled', true);
        });
        $(document).on('click', "#save-personal-info-btn2", function() {
            $(this).prop('disabled', true);
        });
        $(document).on('click', "#save-social-info-btn", function() {
            $(this).prop('disabled', true);
        });

        function update_acc_details(data) {
            if (data.status === true) {
                notify('success', data.message, 'Update profile account info');
                $("#btn-next-from-acc").trigger('click');
                $("#ib-tbl-root").DataTable().draw();
            } else {
                notify('error', data.message, 'Update profile inccount info');
            }
            $('#save-acc-info-btn').prop('disabled', false);
            $.validator("account-details-modern", data.errors);
        }

        function update_acc_details2(data) {
            if (data.status === true) {
                notify('success', data.message, 'Update profile account info');
                $("#ib-tbl-root").DataTable().draw();
            } else {
                notify('error', data.message, 'Update profile inccount info');
            }
            $('#save-acc-info-btn2').prop('disabled', false);
            $.validator("account-details-modern", data.errors);
        }
        // personal info update
        function update_personal_info(data) {
            if (data.status === true) {
                notify('success', data.message, 'Update personal info');
                $("#btn-next-from-acc").trigger('click');
                $("#ib-tbl-root").DataTable().draw();
            } else {
                notify('error', data.message, 'Update personal info');
            }
            $("#save-personal-info-btn").prop('disabled', false);
            $.validator("personal-info-modern", data.errors);
        }

        function update_personal_info2(data) {
            if (data.status === true) {
                notify('success', data.message, 'Update personal info');
                $("#ib-tbl-root").DataTable().draw();
            } else {
                notify('error', data.message, 'Update personal info');
            }
            $("#save-social-info-btn2").prop('disabled', false);
            $.validator("personal-info-modern", data.errors);
        }
        // social details
        function update_sicial_info(data) {
            if (data.status === true) {
                notify('success', data.message, 'Update social account info');
                $("#btn-next-from-social").trigger('click');
                $("#ib-tbl-root").DataTable().draw();
            } else {
                notify('error', data.message, 'Update social account info');
            }
            $("#save-social-info-btn").prop('disabled', false);
            $.validator("social-links-modern", data.errors);
        }

        $(document).ready(function() {
            $("#reset_btn_trader").click(function() {
                $("#added-trader-subIB")[0].reset();
                $("#sub-ib-ref-add").trigger("change");
            });


        });

        $(document).ready(function() {
            $("#reset_btn").click(function() {
                $("#remove-trader-subIB")[0].reset();
                $("#selec2-remove-subib").trigger("change");
            });
        });
        // start change password
        /************************************************ */
        function change_password_call_back(data) {
            if (data.status === true) {
                notify('success', data.message, 'Password Changes');
                $('#send-mail-pass').modal('toggle');
                $('#password-change-modal').modal('toggle');
                // sending mail
                let $url = '/admin/client-management/trader-admin-change-password-mail/' + data.id;
                send_mail('Change password mail', 'Sending password change mail', $url, true);

            } else {
                notify('error', 'Please fix the following errors', 'Change Password');
            }
            $.validator("change-password-form", data.errors);
        }
        // change transaction pin
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

        /*<!---------------Delete sub IB------------------!>*/
        function delete_sub_ib(e) {
            var obj = $(e);
            // var dt_ajax_table = $(__this).closest('tr').find('.sub-ib').DataTable;


            var sub_id = obj.data('ib_id');
            // console.log(sub_id);
            let warning_title = "";
            let warning_msg = "";
            let request_for;

            warning_title = 'Are you sure? to delete this user!';
            warning_msg = 'If you want to delete this User please click OK, otherwise simply click cancel'
            request_for = 'block'

            Swal.fire({
                icon: 'warning',
                title: warning_title,
                html: warning_msg,

                showCancelButton: true,
                customClass: {
                    confirmButton: 'btn btn-warning',
                    cancelButton: 'btn btn-danger'
                },
                closeOnCancel: false,
                closeOnConfirm: false,
            }).then((willDelete) => {
                if (willDelete.isConfirmed) {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '/admin/ib-management/delete-sub-ib',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            sub_id: sub_id,
                            request_for: request_for
                        },
                        success: function(data) {
                            if (data.success === true) {

                                Swal.fire({
                                    icon: 'success',
                                    title: data.success_title,
                                    html: data.message,
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    }
                                }).then((willDelete) => {
                                    // const table = $("").DataTable();
                                    // table.draw();
                                    obj.closest('.sub-ib').DataTable().draw();
                                });
                            } else {

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed to delete user',
                                    html: data.message,
                                    customClass: {
                                        confirmButton: 'btn btn-danger'
                                    }
                                });
                            }
                        }
                    })

                }

            });
        }
        /*<!---------------Delete Sub  IB------------------!>*/

        $(document).on("click", ".ib-balance-tab", function() {
            let __this = $(this);
            let ib_id = $(this).data('ib_id');
            if ($(__this).closest('tr').find('.datatable-inner-ib-balance').length) {
                $(__this).closest('tr').find('.datatable-inner-ib-balance').DataTable().clear().destroy();
                var dt_ajax_table = $(__this).closest('tr').find('.datatable-inner-ib-balance').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "searching": false,
                    "lengthChange": false,
                    "dom": 'Bfrtip',
                    "ajax": {
                        "url": "/admin/ib-management/ib-admin-report-description-inner-ib-balance-add/" +
                            ib_id
                    },
                    "columns": [{
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
                            "data": "request_at"
                        },
                        {
                            "data": "approved_at"
                        },
                        {
                            "data": "amount"
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

        // block unblock status
        $(document).on('click', '.btn-block-ib', function() {
            let id = $(this).data('id');
            $(this).confirm2({
                request_url: '/admin/ib-management/ib/block',
                data: {
                    id: id
                },
                click: false,
                title: 'IB Block',
                message: 'Are you confirm to block this IB?',
                button_text: 'Block',
                method: 'POST'
            }, function(data) {
                if (data.status == true) {
                    notify('success', data.message, 'IB Block');
                    $("#ib-tbl-root").DataTable().draw();
                } else {
                    notify('error', data.message, 'IB Block');
                }
                // dt.draw();
            });
        });
        $(document).on('click', '.btn-unblock-ib', function() {
            let id = $(this).data('id');
            $(this).confirm2({
                request_url: '/admin/ib-management/ib/unblock',
                data: {
                    id: id
                },
                click: false,
                title: 'IB Unblock',
                message: 'Are you confirm to unblock this IB?',
                button_text: 'Unblock',
                method: 'POST'
            }, function(data) {
                if (data.status == true) {
                    notify('success', data.message, 'IB Unblock');
                    $("#ib-tbl-root").DataTable().draw();
                } else {
                    notify('error', data.message, 'IB Unblock');
                }
                // dt.draw();
            });
        });
        // send welcome mail------------------------------------------
        $(document).on("click", ".btn-send-welcome-mail", function() {
            let ib_id = $(this).data('ib_id');
            var close_time;
            Swal.fire({
                title: 'Send Verification Email',
                text: 'Are You Confirm to send verification email ?',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Send Email',
                showLoaderOnConfirm: true,
                preConfirm: (login) => {
                    $(".swal2-html-container").text("We Sending Email, Please Wait.....")
                    return fetch(`/admin/ib-management/welcome-mail/` + ib_id)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText)
                            }
                            return response.json()
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Request failed: ${error}`
                            )
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                // console.log(result);
                if (result.isConfirmed) {
                    if (result.value.status == false) {
                        notify('error', result.value.message, 'Verification Email');
                    } else {
                        notify('success', result.value.message, 'Verification Email');
                    }
                }
            })
        })
        // END: send welcome mail--------------------------------------
    </script>
@stop
<!-- BEGIN: page JS -->
