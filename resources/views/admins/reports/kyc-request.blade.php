@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'KYC Request')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/swiper.min.css') }}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-swiper.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/fontawesome.min.css">

<style>
    input#issue_from:hover {
        display: inline;
    }

    input#issue_to:hover {
        display: inline;
    }

    input#expire_from:hover {
        display: inline;
    }

    input#expire_to:hover {
        display: inline;
    }

    .geeks {
        /*width: 300px;*/
        /*height: 300px;*/
        overflow: hidden;
        margin: 0 auto;
    }

    .geeks img {
        width: 100%;
        transition: 0.5s all ease-in-out;
        cursor: pointer;
    }

    /*.geeks:hover img {*/
    /*    transform: scale(1.5);*/
    /*}*/
    .tab-pane {
        /*position: relative;*/
    }

    .loaderParent {
        min-height: 50vh;
    }

    .card-body {
        flex: 1 1 auto;
        padding: 0.5rem;
    }

    .text-center {
        text-align: left !important;
    }

    ul#myTab {
        width: 80%;
        margin: 0 auto;
    }

    .user-description>ul {
        margin-left: 30px;
    }

    .user-description>ul>li {
        padding-bottom: 5px;
    }

    button.btn-close {
        left: -1rem;
        top: 1rem;
    }

    .dark-layout .modal .modal-content,
    .dark-layout .modal .modal-body,
    .dark-layout .modal .modal-footer {
        /* background-color: #283046;
                        border-color: #3b4253; */
        background: rgba(40, 48, 70, 0.22);
        border-radius: 16px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(6.1px);
        -webkit-backdrop-filter: blur(6.1px);
        border: 1px solid rgba(40, 48, 70, 0.31);
    }

    .dark-layout .card {
        background-color: #283046;
        /* box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.24); */
        box-shadow: rgba(34, 41, 47, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px;
    }

    .dark-layout .nav-tabs .nav-item .nav-link.active,
    .dark-layout .nav-pills .nav-item .nav-link.active,
    .dark-layout .nav-tabs.nav-justified .nav-item .nav-link.active {
        /* background-color: #283046;
                    color: var(--custom-primary); */
        background: rgba(40, 48, 70, 0.2);
        border-radius: 16px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(6.1px);
        -webkit-backdrop-filter: blur(6.1px);
        /* border: 1px solid rgb(255, 255, 255); */
    }

    .nav-tabs .nav-link.active::after {
        background: transparent !important;
    }

    .nav-tabs .nav-link::after {
        background: transparent !important;
    }

    .full_filed {
        padding-bottom: 15px !important;
    }

    .input_field {
        padding-bottom: 15px !important;
    }

    .user-card {
        min-height: 100% !important;
    }

    .bs-stepper .step-trigger {
        padding: 5px 10px !important;
    }

    .imgThum {
        padding: 0.25rem;
        background-color: transparent !important;
        border: 1px transparent !important;
        /*border-radius: 0.357rem;*/
        /*max-width: 100%;*/
        /*height: auto;*/
    }

    /* for Laptop */

    @media (max-width: 575.98px) {
        .kycDoc {
            margin-bottom: 20px !important;
        }

        .kycInfo {}

        .nav-item-custom {
            margin: 10px 10px 10px 0px !important;
        }

        .user-description {
            padding: 0 10px 0px !important;
        }
    }

    @media (min-width: 576px) and (max-width: 767.98px) {
        .kycDoc {
            margin-bottom: 20px !important;
        }

        .kycInfo {}

        .nav-item-custom {
            margin: 10px 10px 10px 0px !important;
        }

        .user-description {
            padding: 0 10px 0px !important;
        }

        .kycDetails {
            padding: 0px !important;
        }

        .user-description ul li {
            list-style: none !important;
        }

        .nav-line-tabs-custom {
            margin: 0 auto;
        }
    }

    @media (min-width: 768px) and (max-width: 991.98px) {
        .kycDoc {
            margin-bottom: 20px !important;
        }

        .kycInfo {}

        .nav-item-custom {
            margin: 10px 10px 10px 0px !important;
        }

        .user-description {
            padding: 0 10px 0px !important;
        }

        .kycDetails {
            padding: 0px !important;
        }

        .user-description ul li {
            list-style: none !important;
        }

        .nav-line-tabs-custom {
            margin: 0 auto;
        }
    }

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

        .ib-withdraw thead tr th:nth-child(4),
        .ib-withdraw tbody tr td:nth-child(4) {
            display: none;
        }

        .small-none-two {
            display: none;
        }
    }

    @media screen and (max-width: 1440px) and (min-width: 900px) {

        .ib-withdraw thead tr th:nth-child(7),
        .ib-withdraw tbody tr td:nth-child(7) {
            display: none;
        }

        .small-none {
            display: none;
        }
    }

    .right-class {
        margin-left: 44px;
    }

    /* kyc-verified-modal-button */
    .nav-item-custom {
        margin-right: 5px !important;
    }

    .nav-item-custom:":last-child{
 margin-right: 0px !important;
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
                        <h2 class="content-header-title float-start mb-0">{{ __('admin-breadcrumbs.kyc_request') }}
                        </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('finance.home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{ __('admin-menue-left.kyc_management') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('admin-breadcrumbs.kyc_request') }}
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
                        <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="app-todo.html"><i class="me-1" data-feather="check-square"></i><span class="align-middle">Todo</span></a><a class="dropdown-item" href="app-chat.html"><i class="me-1" data-feather="message-square"></i><span class="align-middle">Chat</span></a><a class="dropdown-item" href="app-email.html"><i class="me-1" data-feather="mail"></i><span class="align-middle">Email</span></a><a class="dropdown-item" href="app-calendar.html"><i class="me-1" data-feather="calendar"></i><span class="align-middle">Calendar</span></a></div>
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
                                <h4 class="card-title">{{ __('ib-management.filter_report') }}</h4>
                                <div class="btn-exports" style="width:200px">
                                    <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                        <option value="download" data-icon="download" selected>Export to</option>
                                        <option value="csv" data-icon="file">CSV</option>
                                        <option value="excel" data-icon="file">Excel</option>
                                    </select>
                                </div>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form id="filterForm" class="dt_adv_search" method="POST">
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-lg-3 col-md-6 col-12 mb-1">
                                            <select class="select2 form-select" name="type" id="type">
                                                <optgroup label="Search By Document type">
                                                    <option value="">{{ __('ib-management.all') }}</option>
                                                    <option value="adhar card">ADHAR CARD</option>
                                                    <option value="passport">PASSPORT</option>
                                                    <option value="driving license">DRIVING LICENSE</option>
                                                    <option value="credit card statement">CREDIT CARD STATEMENT
                                                    </option>
                                                    <option value="bank statement">BANK STATEMENT</option>
                                                    <option value="bank certificate">BANK CERTIFICATE</option>
                                                    <option value="NATIONAL ID">NATIONAL ID</option>
                                                    <option value="GAS BILL">GAS BILL</option>
                                                    <option value="UTILITY BILL">UTILITY BILL</option>
                                                    <option value="ELECTRIC BILL">ELECTRIC BILL</option>
                                                    <option value="TELEPHONE BILL">TELEPHONE BILL</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-12 mb-1">
                                            <select class="select2 form-select" name="client_type" id="client_type">
                                                <optgroup label="Search By User Type">
                                                    <option value="">{{ __('ib-management.all') }}</option>
                                                    <option value="ib">IB</option>
                                                    <option value="trader">TRADER</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-12 mb-1">
                                            <select class="select2 form-select" name="status" id="status">
                                                <optgroup label="Search By Kyc Status">
                                                    <option value="">{{ __('ib-management.all') }}</option>
                                                    <option value="0" selected="selected">Pending</option>
                                                    <option value="1">Verified</option>
                                                    <option value="2">Declined</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-12 mb-1">
                                            <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Issue Expire Request Date" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
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
                                        {{-- User Email / Name  --}}
                                        <div class="col-lg-3 col-md-6 col-sm-6 mb-1">
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter User Name or Email" class="form-control dt-input dt-full-name" data-column="1" name="info" id="info" placeholder="Name / Email" data-column-index="0" />
                                        </div>
                                        {{-- Account & Desk Manager  --}}

                                        @if (App\Services\systems\VersionControllService::check_version() === 'lite')
                                        <div class="col-lg-3 col-md-6 col-sm-6 mb-1">

                                            <select class="select2 form-select js-example-basic-single" name="country_info" id="country_info" title="Search by Country">
                                                <option value="" selected>Select Country</option>
                                                @foreach ($countryList as $value)
                                                <option value=" {{ $value->id }} ">{{ $value->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @else
                                        <div class="col-lg-3 col-md-6 col-sm-6 mb-1">
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Put Account & Desk Manager Email / Phone / Name" class="form-control dt-input dt-full-name" data-column="1" name="manager_email" id="manager_email" placeholder="Account / Desk Manager Email" data-column-index="0" />
                                        </div>
                                        @endif

                                        {{-- Trading-Account Number --}}
                                        <div class="col-lg-3 col-md-6 col-sm-6 mb-1">
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Trading Account Number" class="form-control dt-input dt-full-name" data-column="1" name="trading_number" id="trading_number" placeholder="Trading Account Number" data-column-index="0" />
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6 text-right mb-1">
                                            <div class="row">
                                                <div class="col-6">
                                                    <button id="resetBtn" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                                        <span class="align-middle">{{ __('ib-management.reset') }}</span>
                                                    </button>
                                                </div>
                                                <div class="col-6">
                                                    <button id="filterBtn" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                                        <span class="align-middle">{{ __('category.FILTER') }}</span>
                                                    </button>
                                                </div>
                                            </div>
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
                                <table id="kyc_report_tbl" class="datatables-ajax ib-withdraw table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('page.client_name') }}</th>
                                            <th>{{ __('finance.Client') }} {{ __('page.type') }}</th>
                                            <th>{{ __('page.document-type') }}</th>
                                            <th>{{ __('page.issue-date') }}</th>
                                            <th>{{ __('page.expire-date') }}</th>
                                            <th>{{ __('page.status') }}</th>
                                            <th>{{ __('page.date') }}</th>
                                            <th id="action">{{ __('page.action') }}</th>
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



<!-- kyc request user profile modal  -->
<div class="modal fade" id="userDescriptionModel" tabindex="-1" data-bs-focus="false" aria-labelledby="userDescriptionModel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-transparent p-0 m-0" style="height: 0px;">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeFunction(this)"></button>
            </div>
            {{-- modal-body --}}
            <div class=" px-sm-5 mx-50 pb-5 kycDetails">
                <div id="pricing-plan">
                    <!-- title text and switch button -->
                    <div class="card-header d-flex justfy-content-between">
                        <h1 id="pricingModalTitleId" style="display: none">ID Proof</h1>
                        <h1 id="pricingModalTitleAdd" style="display: none">Address Proof</h1>
                    </div>
                    <!--/ title text and switch button -->
                    <!-- pricing plan cards -->
                    <div class="row pricing-card pb-2">
                        <!-- First Card -->
                        <div class="col-sm-12 col-lg-8 col-md-12 content-body kycDoc">
                            <div class="card user-card mb-0" id="nav-filled">
                                <div class="card-body">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs nav-fill pt-2" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="home-tab-fill" data-bs-toggle="tab" href="#home-fill" role="tab" aria-controls="home-fill" aria-selected="true">Front
                                                Part</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="profile-tab-fill" data-bs-toggle="tab" href="#profile-fill" role="tab" aria-controls="profile-fill" aria-selected="false">Back
                                                Part</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content p-4">
                                        <!-- kyc front part -->
                                        <div class="tab-pane active" id="home-fill" role="tabpanel" aria-labelledby="home-tab-fill">
                                            <div class="geeks doc-file-container visually-hidden">
                                                <img id="front_part" class="imgThum" src="{{ url('admin-assets/driver_license.png') }}">
                                                <embed src="" id="front_part_pdf" type="application/pdf" width="100%" height="600px" />
                                            </div>
                                            <div class=" d-flex align-items-center loaderParent justify-content-center">
                                                <span class="spinner-border me-1 loader" role="status" aria-hidden="true"></span>
                                                <span class="loader">Loading...</span>
                                            </div>
                                        </div>
                                        <!-- kyc back part -->
                                        <div class="tab-pane" id="profile-fill" role="tabpanel" aria-labelledby="profile-tab-fill">
                                            <div class="geeks doc-file-container visually-hidden">
                                                <img id="backpart_part" src="{{ url('admin-assets/passport.png') }}">
                                                <embed src="" id="backpart_part_pdf" type="application/pdf" width="100%" height="600px" />
                                            </div>
                                            <div class=" d-flex align-items-center loaderParent justify-content-center">
                                                <span class="spinner-border me-1 loader" role="status" aria-hidden="true"></span>
                                                <span class="loader">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ standard plan -->
                        <!-- enterprise plan -->

                        <div class="col-sm-12 col-lg-4 col-md-12 content-body kycInfo">
                            <div class="card user-card " id="nav-filled">
                                <div class="card-body">

                                    <form action="{{ route('kyc.des.update') }}" id="input_form" method="POST">
                                        @csrf
                                        <input type="hidden" name="kyc_id" id="user_table_id">
                                        <input type="hidden" name="group_name" id="group_name">
                                        <div class="p-3 py-0 user-description">
                                            <h3 class="py-1">User Description</h3>
                                            <ul class="ms-0 list-group list-group-circle text-start fw-bold">
                                                <li class="full_filed" style="">Name: <span class="text-primary" id="user_name"></span></li>
                                                <li class="full_filed" style="">Email : <span class="text-primary" id="user-email"> </li>
                                                <li class="full_filed" style="">Country : <span class="text-primary" id="user-country"></li>

                                                <li class="full_filed" style="">Address : <span class="text-primary" id="user-address"></span></li>
                                                <li class="full_filed" style="">City : <span class="text-primary" id="user-city"></li>
                                                <li class="full_filed" style="">State : <span class="text-primary" id="user-state"></span></li>
                                                <li class="full_filed" style="">Phone : <span class="text-primary" id="user-phone"></li>
                                                <li class="full_filed" style="">Zip : <span class="text-primary" id="user-zip-code"></li>
                                                <li class="full_filed" style="">Date Of Birth : <span class="text-primary" id="user-dob"> </li>
                                                <li class="full_filed" style="">Status : <span id="user-status"> </span></li>
                                                <li class="full_filed modal-issue-date" style="">
                                                    Issue Date : <span class="text-primary" id="user-issue_date">
                                                </li>
                                                <li class="full_filed modal-expire-date" style="">
                                                    Expire Date : <span class="text-primary" id="user-exp_date">
                                                </li>
                                                <li class="full_filed" style="">
                                                    Document Type : <span class="text-primary" id="user-doc_type">
                                                </li>
                                                <li class="full_filed" style="">ID Number: <span class="text-primary" id="user-idNumber"></span></li>
                                                <li class="full_filed" style="">
                                                    Sex : <span class="text-primary" id="sex">
                                                </li>
                                            </ul>
                                            <!-- id proof -->
                                            <div id="id_proof">
                                                <div class="modern-horizontal-wizard">
                                                    <div class="bs-stepper wizard-modern modern-wizard-example">
                                                        <div class="bs-stepper-content pb-2">
                                                            <div id="input_form1" class="content m-0" role="tabpanel" aria-labelledby="input_form1_trigger">
                                                                <ul class=" ms-0 list-group list-group-circle text-start fw-bold">

                                                                    <li class="input_field" style="display:none">
                                                                        <label for="update_name">Name:</label>
                                                                        <input type="text" class="text-primary form-control" name="name" id="update_name" value="" />
                                                                    </li>
                                                                    <li class="input_field adhar_input_field" style="display:none">
                                                                        <label for=" Issue Date:">Issue Date</label>
                                                                        <input type="date" class="text-primary form-control" name="issue_date" id="update_issue_date" value="" />
                                                                    </li>
                                                                    <li class="input_field adhar_input_field" style="display:none">
                                                                        <label for="update_expire_date">Expire
                                                                            Date</label>
                                                                        <input type="date" class="text-primary form-control" name="expire_date" id="update_expire_date" value="" />
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="update_date_birth">Date Of
                                                                            Birth</label>
                                                                        <input type="date" class="text-primary form-control" name="date_birth" id="update_date_birth" value="" />
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="document_type">Document
                                                                            Type</label>
                                                                        <select id="update_document_type" name="document_name" class="select2 form-select">
                                                                            <!--<option selected></option>-->
                                                                            @foreach ($kycId as $value)
                                                                            <option value="{{ $value->id }}">
                                                                                {{ $value->id_type }}
                                                                            </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="id_number">ID Number</label>
                                                                        <input type="text" class="text-primary form-control" name="id_number" id="update_idNumber" value="" />
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="sex">Sex</label>
                                                                        <input type="text" class="text-primary form-control" name="gender" id="update_sex" value="" />
                                                                    </li>

                                                                </ul>
                                                            </div>

                                                            {{-- 2ndform --}}
                                                            <div id="input_form2" class="content m-0" role="tabpanel" aria-labelledby="input_form2_trigger">
                                                                <ul class="ms-0 list-group list-group-circle text-start fw-bold">
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="update_name">Phone:</label>
                                                                        <input type="text" class="text-primary form-control" name="phone" id="update_phone" value="" />
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="update_state">State:</label>
                                                                        <input type="text" class="text-primary form-control" name="state" id="update_state" value="" />
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="update_city">City</label>
                                                                        <input type="text" class="text-primary form-control" name="city" id="update_city" value="" />
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="update_address"> Address</label>
                                                                        <input type="text" class="text-primary form-control" name="address" id="update_address" value="" />
                                                                    </li>

                                                                    <li class="input_field" style="display:none">
                                                                        <label for="update_zip_code">Zip Code</label>
                                                                        <input type="text" class="text-primary form-control" name="zip_code" id="update_zip_code" value="" />
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="text-end input_field bs-stepper-header" style="display: none">
                                                            <div class="d-flex justify-content-between flex-row-reverse">
                                                                <div class="step " data-target="#input_form1" role="tab" id="input_form1_trigger">
                                                                    <button type="button" class="step-trigger btn btn-primary  p-0 ">
                                                                        <span class="bs-stepper-label">
                                                                            <span class="bs-stepper-title">Next</span>
                                                                            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                                <div class="text-end d-none" style="display: none" id="kycUpdateBtn">
                                                                    <button type="button" class="btn btn-success" id="kyc_input_Btn" onclick="_run(this)" data-el="fg" data-form="input_form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="kycUpdateCallBack" data-btnid="kyc_input_Btn">Update
                                                                    </button>
                                                                </div>
                                                                <div class="step active d-none" data-target="#input_form2" role="tab" id="input_form2_trigger">
                                                                    <button type="button" class="step-trigger btn btn-primary  p-0">
                                                                        <span class="bs-stepper-label">
                                                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                                            <span class="bs-stepper-title">Previous</span>
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- address proof -->
                                            <div id="add_proof">
                                                <div class="modern-horizontal-wizard">
                                                    <div class="bs-stepper wizard-modern modern-wizard-example ">
                                                        <div class="bs-stepper-content pb-2 ">
                                                            <div id="input_formAdd1" class="content m-0 " role="tabpanel" aria-labelledby="input_formAdd1_trigger">
                                                                <ul class=" ms-0 list-group list-group-circle text-start fw-bold">

                                                                    <li class="input_field" style="display:none">
                                                                        <label for="update_name">Name:</label>
                                                                        <input type="text" class="text-primary form-control" name="name" id="update_nameAdd" value="" />
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="document_type">Document
                                                                            Type</label>
                                                                        <select id="update_document_typeAdd" name="document_name" class="select2 form-select">
                                                                            @foreach ($kycId as $value)
                                                                            <option value="{{ $value->id }}">
                                                                                {{ $value->id_type }}
                                                                            </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="id_number">ID Number</label>
                                                                        <input type="text" class="text-primary form-control" name="id_number" id="update_idNumberAdd" value="" />
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="update_state">State:</label>
                                                                        <input type="text" class="text-primary form-control" name="state" id="update_stateAdd" value="" />
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="update_city">City</label>
                                                                        <input type="text" class="text-primary form-control" name="city" id="update_cityAdd" value="" />
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="update_address"> Address</label>
                                                                        <input type="text" class="text-primary form-control" name="address" id="update_addressAdd" value="" />
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <!-- 2ndform  -->
                                                            <div id="input_formAdd2" role="tabpanel" class="content m-0" aria-labelledby="input_formAdd2_trigger">
                                                                <ul class="ms-0 list-group list-group-circle text-start fw-bold">
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="update_name">Phone:</label>
                                                                        <input type="text" class="text-primary form-control" name="phone" id="update_phoneAdd" value="" />
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="update_zip_code">Zip Code</label>
                                                                        <input type="text" class="text-primary form-control" name="zip_code" id="update_zip_codeAdd" value="" />
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for=" Issue Date:">Issue Date</label>
                                                                        <input type="date" class="text-primary form-control" name="issue_date" id="update_issue_dateAdd" value="" />
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="update_expire_date">Expire
                                                                            Date</label>
                                                                        <input type="date" class="text-primary form-control" name="expire_date" id="update_expire_dateAdd" value="" />
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="update_date_birth">Date Of
                                                                            Birth</label>
                                                                        <input type="date" class="text-primary form-control" name="date_birth" id="update_date_birthAdd" value="" />
                                                                    </li>
                                                                    <li class="input_field" style="display:none">
                                                                        <label for="sex">Sex</label>
                                                                        <input type="text" class="text-primary form-control" name="gender" id="update_sexAdd" value="" />
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="text-end input_field bs-stepper-header" style="display: none">
                                                            <div class="d-flex justify-content-between flex-row-reverse">
                                                                <div class="step " data-target="#input_formAdd1" id="input_formAdd1_trigger" role="tab">
                                                                    <button type="button " class="step-trigger btn btn-primary btn-sm p-0 ">
                                                                        <span class="bs-stepper-label">
                                                                            <span class="bs-stepper-title">Next</span>
                                                                            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                                <div class="text-end d-none" style="display: none" id="kycUpdateBtnAdd">
                                                                    <button type="button" class="btn btn-success" id="kyc_input_Btn" onclick="_run(this)" data-el="fg" data-form="input_form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="kycUpdateCallBack" data-btnid="kyc_input_Btn">Update
                                                                    </button>
                                                                </div>
                                                                <div class="step active d-none" data-target="#input_formAdd2" id="input_formAdd2_trigger" role="tab">
                                                                    <button type="button" class="step-trigger btn btn-primary btn-sm p-0">
                                                                        <span class="bs-stepper-label">
                                                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                                            <span class="bs-stepper-title">Previous</span>
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--/ enterprise plan -->
                    </div>
                    <!--/ pricing plan cards -->
                    <!-- pricing free trial -->
                    <div class="row text-center">
                        <div class="col-lg-6 col-md-12">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input w-30px h-20px" type="checkbox" id="kycUserVerify" name="kyc_status" />
                                <span class="form-check-label text-muted fs-7">Verify Account</span>
                            </label>
                        </div>
                        <div class="col-lg-6 col-md-12 d-flex justify-content-end">
                            <input type="hidden" name="approve_id" id="approve_id">
                            <input type="hidden" name="table_id" id="table_id">
                            <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9 nav-line-tabs-custom">
                                <li class="nav-item nav-item-custom">
                                    <button data-type="button" id="edit_button" class="btn btn-info waves-effect waves-float waves-light" data-loading="processing..." onclick="myFunction(this)">Edit</button>
                                </li>
                                <li class="nav-item nav-item-custom">
                                    <button data-type="button" id="approve_button" class="btn btn-primary waves-effect waves-float waves-light" data-loading="processing...">Approve</button>
                                </li>
                                <li class="nav-item nav-item-custom">
                                    <button data-type="button" id="decline_button" class="btn btn-danger decline-request-btn waves-effect waves-float waves-light" data-loading="processing...">Decline</button>
                                </li>
                                <li class="nav-item nav-item-custom">
                                    <button data-type="button" class="btn btn-success waves-effect waves-float waves-light" data-bs-dismiss="modal" onclick="closeFunction(this)">Close</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--/ pricing free trial -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / user profile modal  -->

<!-- update kyc request  profile modal -->
<div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="addNewAddressTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-4 mx-50">
                <h1 class="address-title text-center mb-1" id="addNewAddressTitle">Update Profile</h1>
                <form id="profile-update-form" action="{{ route('kyc.request.profile.update') }}" method="POST" class="row gy-1 gx-2" onsubmit="return false">
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="modalAddressFirstName">Full Name
                            <font class=" text-danger">*</font>
                        </label>
                        <input type="text" id="name" name="name" class="form-control" value="" data-msg="Please enter your full name" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="modalAddressLastName">State
                            <font class=" text-danger">*</font>
                        </label>
                        <input type="text" id="state" name="state" class="form-control" value="" data-msg="Please enter your state name" />
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="modalAddressCountry">Country
                            <font class=" text-danger">*</font>
                        </label>
                        <select id="country" name="country_id" value="" class="select2 form-select">
                            @foreach ($countryList as $value)
                                <option value=" {{ $value->id }} ">{{ $value->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="modalAddressAddress2">Zip
                            <font class=" text-danger">*</font>
                        </label>
                        <input type="text" id="zip" name="zip" class="form-control" placeholder="Zip" />
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="modalAddressTown">City
                            <font class=" text-danger">*</font>
                        </label>
                        <input type="text" id="city" name="city" class="form-control" placeholder="Los Angeles" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class=" form-label  flatpickr-basic" for="modalAddressState">Issue Date
                            <font class=" text-danger">*</font>
                        </label>
                        <div class="input-group" data-toggle="tooltip" data-trigger="hover" class="form-control" data-original-title="Issue Date">
                            <span class="input-group-text">
                                <div class="icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6">
                                        </line>
                                        <line x1="8" y1="2" x2="8" y2="6">
                                        </line>
                                        <line x1="3" y1="10" x2="21" y2="10">
                                        </line>
                                    </svg>
                                </div>
                            </span>
                            <input id="issue_date" type="text" title="Enter Issue date" name="issue_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="modalAddressZipCode">Expire Date
                            <font class=" text-danger">*</font>
                        </label>
                        <div class="input-group" data-toggle="tooltip" data-trigger="hover" class="form-control" data-original-title="Issue Date">
                            <span class="input-group-text">
                                <div class="icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6">
                                        </line>
                                        <line x1="8" y1="2" x2="8" y2="6">
                                        </line>
                                        <line x1="3" y1="10" x2="21" y2="10">
                                        </line>
                                    </svg>
                                </div>
                            </span>
                            <input id="expire_date" type="text" title="Enter Issue date" name="exp_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                        </div>
                    </div>

                    <div class="col-12 col-md-12">
                        <label class="form-label" for="modalAddressZipCode">Date Of Birth
                            <font class=" text-danger">*</font>
                        </label>
                        <div class="input-group" data-toggle="tooltip" data-trigger="hover" class="form-control" data-original-title="Issue Date">
                            <span class="input-group-text">
                                <div class="icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6">
                                        </line>
                                        <line x1="8" y1="2" x2="8" y2="6">
                                        </line>
                                        <line x1="3" y1="10" x2="21" y2="10">
                                        </line>
                                    </svg>
                                </div>
                            </span>
                            <input id="dob" type="text" title="Enter Issue date" name="date_of_birth" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="modalAddressAddress1">Address
                            <font class=" text-danger">*</font>
                        </label>

                        <textarea type="text" id="address" name="address" class="form-control" placeholder="12, Business Park"></textarea>
                    </div>
                    <div class="col-12 text-center">
                        <input type="hidden" name="kyc_id" id="kyc_id">
                        <button type="button" class="btn btn-primary me-1 mb-1" id="profileUpdateBtn" onclick="_run(this)" data-el="fg" data-form="profile-update-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="profileUpdateCallBack" data-btnid="profileUpdateBtn">Save Change</button>
                        <button type="reset" class="btn btn-outline-secondary mb-1" data-bs-dismiss="modal" aria-label="Close">Discard</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- / update profile modal -->

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

<!-- add new address modal -->
<div class="modal fade" id="addNewAddressModal" tabindex="-1" aria-labelledby="addNewAddressTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body pb-5 px-sm-4 mx-50">
                <h1 class="address-title text-center mb-1" id="addNewAddressTitle">User Document</h1>
                <form id="addNewAddressForm" class="row gy-1 gx-2" onsubmit="return false">

                    <body style="margin: 0px; height: 100%">
                        <img id="img1" style="display: block;-webkit-user-select: none;" class="img-thumbnail" src="{{ url('admin-assets/passport.png') }}">
                    </body>
                    <div class="col-12 text-center">
                        <button type="reset" class="btn btn-outline-secondary mt-2" data-bs-dismiss="modal" aria-label="Close">
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- / add new address modal -->

<!-- add new card modal  -->
<div class="modal fade" id="kyc_decline_req" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body px-sm-5 mx-50 pb-5 ">
                <h1 class="text-center mb-1" id="addNewCardTitle">Reason For Decline</h1>

                <!-- form -->
                <form id="kyc_request" class="row gy-1 gx-2 mt-75" action="{{ route('kyc.management.decline') }}" method="POST">
                    <div class="col-12">
                        <label class="form-label" for="modalAddCardNumber">Reason:</label>
                        <div class="input-group input-group-merge">
                            <input id="reason" name="reason" class="form-control add-credit-card-mask" type="text" placeholder="type here....." aria-describedby="modalAddCard2" />
                            <span class="input-group-text cursor-pointer p-25" id="modalAddCard2">
                                <span class="add-card-type"></span>
                            </span>
                            <input type="hidden" name="kyc_decline_id" id="kyc_decline_id">
                            <input type="hidden" name="tbl_id" id="tbl_id">
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

<!-- END: Content-->


@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/swiper.min.js') }}"></script>
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


<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>

@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/extensions/ext-component-swiper.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/common-ajax.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js') }}"></script>

<!-- datatable  -->
<script>
    var dt = $('#kyc_report_tbl').DataTable({
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
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                action: serverSideButtonAction
            },
            {
                extend: 'copy',
                text: 'Copy',
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
            {
                extend: 'pdf',
                text: 'pdf',
                className: 'btn btn-danger btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                action: serverSideButtonAction
            }
        ],
        "ajax": {
            "url": "/admin/kyc-management/kyc-request?op=data_table",
            "data": function(d) {
                return $.extend({}, d, {

                    "from": $("#from").val(),
                    "to": $("#to").val(),
                    "type": $("#type").val(),
                    "status": $("#status").val(),
                    "client_type": $("#client_type").val(),
                    "info": $("#info").val(),
                    "issue_from": $("#issue_from").val(),
                    "issue_to": $("#issue_to").val(),
                    "expire_from": $("#expire_from").val(),
                    "expire_to": $("#expire_to").val(),
                    "manager_email": $("#manager_email").val(),
                    "ib_email": $("#ib_email").val(),
                    "trading_number": $("#trading_number").val(),
                    "country_info": $("#country_info").val(),

                });
            }
        },

        "columns": [{
                "data": "client_name"
            },
            {
                "data": "client_type"
            },
            {
                "data": "document_type"
            },
            {
                "data": "issue_date"
            },
            {
                "data": "expire_date"
            },
            {
                "data": "status"
            },
            {
                "data": "date"
            },
            {
                "data": "action"
            },

        ],
        "columnDefs": [{
            "targets": 7,
            "orderable": false
        }],

        "drawCallback": function(settings) {
            $("#filterBtn").html("FILTER");

            var rows = this.fnGetData();
            if (rows.length !== 0) {
                feather.replace();
            }
        },
        "order": [
            [6, 'desc']
        ]
    });
    $('#filterBtn').click(function(e) {
        dt.draw();
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

    $(document).ready(function() {
        $('.step-trigger').removeAttr('disabled');
        const stepper0 = new Stepper($('.bs-stepper')[0]);
        $("#input_form1_trigger").on("click", function() {
            $(this).children().find('button').removeAttr('disabled')
            stepper0.next()
        });
        $("#input_form2_trigger").on("click", function() {
            $(this).children().find('button').removeAttr('disabled')
            stepper0.to(1)
        });
    });
    $(document).ready(function() {
        var stepper1 = new Stepper($('.bs-stepper')[1]);
        $("#input_formAdd1_trigger").on("click", function() {
            $(this).children().find('button').removeAttr('disabled')
            stepper1.next()
        });
        $("#input_formAdd2_trigger").on("click", function() {
            $(this).children().find('button').removeAttr('disabled')
            stepper1.to(1)
        });
    });
    $("#input_form2_trigger").on('click', function() {
        $("#input_field_div").css('display', 'none');
        $("#input_form2_trigger").addClass('d-none');
        $("#kycUpdateBtn").css('display', 'none');
        $("#kycUpdateBtn").removeClass('d-none');
        $("#input_form1_trigger").removeClass('d-none');
    });
    $("#input_form1_trigger").on('click', function() {
        $("#input_field_div").css('display', 'block');
        $("#input_form2_trigger").removeClass('d-none');
        $("#input_form1_trigger").addClass('d-none');
        $("#kycUpdateBtn").css('display', 'block');
        $("#kycUpdateBtn").removeClass('d-none')
    });

    $("#input_formAdd1_trigger").on('click', function() {
        $("#input_field_div").css('display', 'block');
        $("#input_formAdd2_trigger").removeClass('d-none');
        $("#input_formAdd1_trigger").addClass('d-none');
        $("#kycUpdateBtnAdd").css('display', 'block');
        $("#kycUpdateBtnAdd").removeClass('d-none')
    });
    $("#input_formAdd2_trigger").on('click', function() {
        $("#input_field_div").css('display', 'none');
        $("#input_formAdd2_trigger").addClass('d-none');
        $("#kycUpdateBtnAdd").css('display', 'none');
        $("#kycUpdateBtnAdd").removeClass('d-none');
        $("#input_formAdd1_trigger").removeClass('d-none');
    });
    $("#userDesCancel").on('click', function() {
        $('.input_field').css('display', 'none');
        $('.full_filed').css('display', 'block');
    });
    /*<---------For reset button script-------------->*/
    $(document).ready(function() {
        $("#resetBtn").click(function() {
            $("#filterForm")[0].reset();
            $('#type').prop('selectedIndex', 0).trigger("change");
            $('#verification_status').prop('selectedIndex', 0).trigger("change");
            $('#status').prop('selectedIndex', 1).trigger("change");
            $('#client_type').prop('selectedIndex', 0).trigger("change");
            $('#country_info').prop('selectedIndex', 0).trigger("change");
            $('#trading_number').trigger("change");

            dt.draw();
        });
    });
    $(document).on('click', ".kyc-modal", function() {
        $("#userDescriptionModel").modal("show");
        var id = $(this).data('id');
        $("#kyc_id, #user_table_id").val(id); // set kyc id for after use
        $("#kycUserVerify").val(id); // user table kyc_status id
        // show preloader
        $(".loaderParent").each(function() {
            $(this).removeClass("visually-hidden");
        });
        // hide file container
        $(".doc-file-container").each(function() {
            $(this).addClass("visually-hidden");
        });
        // min height of modal
        $('.loaderParent').css('min-height', '50vh');
        $.ajax({
            url: '/admin/kyc-management/kyc-request-description/' + id,
            method: 'GET',
            dataType: 'json',

            success: function(data) {
                $("#kycUserVerify").attr("data-status", data.kyc_status);
                $(".loaderParent").each(function() {
                    $(this).addClass("visually-hidden");
                });
                $(".doc-file-container").each(function() {
                    $(this).removeClass("visually-hidden");
                });
                // docuement view for ID proof
                if (data.group_name == 'id proof') {
                    $('#profile-tab-fill').show();
                    // for front part file
                    if (data.front_part_file_type == "") {
                        $("#home-tab-fill").slideUp();
                    } else {
                        $("#home-tab-fill").slideDown();
                        if (data.front_part_file_type == 'pdf') {
                            $('#front_part_pdf').attr("src", data.front_part).show();
                            $('#front_part').hide();
                        } else {
                            $('#front_part').attr("src", data.front_part).show();
                            $('#front_part_pdf').hide();
                        }
                    }
                    // for back part file
                    if (data.back_part_file_type == "") {
                        $("#profile-tab-fill").slideUp();
                    } else {
                        $("#profile-tab-fill").slideDown();
                        if (data.back_part_file_type == 'pdf') {
                            $('#backpart_part_pdf').attr("src", data.back_part).show();
                            $('#backpart_part').hide();
                        } else {
                            $('#backpart_part').attr("src", data.back_part).show();
                            $('#backpart_part_pdf').hide();
                        }
                    }
                    $('#pricingModalTitleId').css('display', 'block');
                    $('#pricingModalTitleAdd').css('display', 'none');

                    $('#id_proof').css('display', 'block');
                    $('#add_proof').css('display', 'none').empty();
                }
                // document view for address proof
                else if (data.group_name === 'address proof') {
                    $('#add_proof').css('display', 'block');
                    $('#id_proof').css('display', 'none').empty();
                    $('#profile-tab-fill').hide();
                    //pdf
                    if (data.front_part_file_type == 'pdf') {
                        $('#front_part_pdf').attr("src", data.front_part).show();
                        $('#front_part').hide();
                    } else {
                        $('#front_part').attr("src", data.front_part).show();
                        $('#front_part_pdf').hide();
                    }
                    // $('#front_part').attr("src", data.front_part);
                    $('#pricingModalTitleAdd').css('display', 'block');
                    $('#pricingModalTitleId').css('display', 'none');
                }

                //show value in input text
                $('#group_name').val(data.group_name);
                $("#update_name").val(data.name);
                $('#update_phone').val(data.phone);
                $('#update_issue_date').val(data.issue_date);
                $('#update_expire_date').val(data.exp_date);
                $('#update_sex').val(data.gender);
                $('#update_document_type option').each(function() {
                    var text = $(this).text();
                    if (text == data.document_name) {
                        $(this).prop("selected", true);
                    } else {
                        $(this).removeAttr("selected");
                    }
                });
                $('#update_document_type').select2();
                $('#update_idNumber').val(data.id_number);
                $('#update_state').val(data.state);
                $('#update_address').val(data.address);
                $('#update_city').val(data.city);
                $('#update_zip_code').val(data.zip_code);
                $('#update_date_birth').val(data.dob);

                $('#user-status').html(data.status);
                $('#user_name').text(data.name);
                $('#user-email').text(data.email);
                $('#user-phone').text(data.phone);
                $('#user-city').text(data.city);
                $('#user-state').text(data.state);
                $('#user-address').text(data.address);
                $('#user-zip-code').text(data.zip_code);
                $('#user-issue_date').text(data.issue_date);
                $('#user-exp_date').text(data.exp_date);
                $('#user-doc_type').text(data.document_name);
                $('#user-idNumber').text(data.id_number);
                $('#user-country').text(data.country);
                $('#user-dob').text(data.dob);
                $('#user-issuer-country').text(data.country);
                $('#sex').text(data.gender);

                if (data.document_name === "adhar card") {
                    $('#update_issue_date').addClass('d-none');
                    $('#update_expire_date').addClass('d-none');
                    $('.adhar_input_field').addClass('d-none').css('padding-bottom', 0);
                    $('.modal-issue-date').addClass('d-none');
                    $('.modal-expire-date').addClass('d-none');

                } else {
                    $('#update_issue_date').removeClass('d-none');
                    $('#update_expire_date').removeClass('d-none');
                    $('.adhar_input_field').removeClass('d-none');
                    $('.modal-issue-date').removeClass('d-none');
                    $('.modal-expire-date').removeClass('d-none');
                }

                //Address Proof
                $('#update_nameAdd').val(data.name);
                $('#update_phoneAdd').val(data.phone);
                $('#update_issue_dateAdd').val(data.issue_date);
                $('#update_expire_dateAdd').val(data.exp_date);
                $('#update_sexAdd').val(data.gender);
                $('#update_document_typeAdd option').each(function() {
                    var text = $(this).text();
                    if (text == data.document_name) {
                        $(this).prop("selected", true);
                    } else {
                        $(this).removeAttr("selected");
                    }
                });
                $('#update_document_typeAdd').select2();
                $('#update_idNumberAdd').val(data.id_number);
                $('#update_stateAdd').val(data.state);
                $('#update_addressAdd').val(data.address);
                $('#update_cityAdd').val(data.city);
                $('#update_zip_codeAdd').val(data.zip_code);
                $('#update_date_birthAdd').val(data.dob);
                // hide approve button
                if (data.kyc_status != 0) {
                    $("#approve_button, #edit_button").removeClass('d-none');
                } else {
                    $("#approve_button, #edit_button").addClass('d-none');
                }
                // checked verification status
                if (data.kyc_status == '1') {
                    $("#kycUserVerify").prop('checked', true);
                } else {
                    $("#kycUserVerify").prop('checked', false);
                }
            }

        });
    });
    //kyc_status change
    $("#kycUserVerify").on("change", function(e) {
        var user_id = $(this).attr("value");
        var status = $(this).prop("checked");
        var data = {
            "userid": user_id,
            "status": status,
        };
        // console.log(user_id);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/kyc-management/kyc-status',
            method: 'POST',
            data,
            dataType: 'json',
            success: function(res) {
                // console.log(res);
                if (res.success === true) {
                    Swal.fire({
                        icon: 'success',
                        title: "Do you want to verify user's KYC status?",
                        text: res.messages,
                    }).then(function() {
                        notify('success', res.messages, 'KYC Status Verified');
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: "Do you want to unverified user's kyc status?",
                        text: res.messages,
                    }).then(function() {
                        notify('error', res.messages, 'KYC Status Unverified');
                    });
                }
            }
        });
    });

    function modalOpen() {
        $('#userDescriptionModel').modal('hide');
    }
    //-----------------Kyc Decline Request-------------------------//
    $(document).on('click', "#decline_button", function() {

        $(this).confirm2({
            request_url: '/admin/kyc-management/kyc-decline-request',
            data: {
                id: $("#kyc_id").val(),
            },
            input: 'text',
            click: false,
            title: 'Decline KYC',
            message: 'Are you confirm to decline this request?',
            button_text: 'Aprove',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'Decline KYC');
            } else {
                notify('error', data.message, 'Decline KYC');
            }
            $("#userDescriptionModel").modal('hide');
            $("#kyc_report_tbl").DataTable().draw();
        });
    })
    // kyc approved alert-----------------------------------------------
    $(document).on('click', "#approve_button", function() {

        $(this).confirm2({
            request_url: '/admin/kyc-management/kyc-approve-request',
            data: {
                id: $("#kyc_id").val(),
            },
            click: false,
            title: 'Approve KYC',
            message: 'Are you confirm to approve this request?',
            button_text: 'Aprove',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'KYC Request approve');
            } else {
                notify('error', data.message, 'KYC Request approve');
            }
            $('#userDescriptionModel').modal('hide');
            $("#kyc_report_tbl").DataTable().draw();
        });
    })
    //kyc profile update callback
    function profileUpdateCallBack(data) {
        if (data.success) {
            notify('success', data.message, 'User Profile');
            $('#updateProfileModal').modal('toggle');
            dt.draw();
        } else {
            notify('error', data.message, 'User Profile');
        }
        $.validator("profile-update-form", data.errors);
    }

    //kyc description edit function
    function myFunction() {
        const nodeList = document.querySelectorAll(".full_filed");
        for (let i = 0; i < nodeList.length; i++) {
            nodeList[i].style.display = "none";
        }

        const classList = document.querySelectorAll(".input_field");
        for (let i = 0; i < classList.length; i++) {
            classList[i].style.display = "block";
        }
    }
    //close modal kyc info
    function closeFunction() {
        const nodeList = document.querySelectorAll(".full_filed");
        for (let i = 0; i < nodeList.length; i++) {
            nodeList[i].style.display = "block";
        }

        const classList = document.querySelectorAll(".input_field");
        for (let i = 0; i < classList.length; i++) {
            classList[i].style.display = "none";
        }
    }
    //kyc description Update callback function
    function kycUpdateCallBack(data) {
        if (data.success) {
            notify('success', data.message, 'KYC');
            $('#updateProfileModal').modal('hide');
            $('.kyc-modal').trigger('click');
            dt.draw();
            const nodeList = document.querySelectorAll(".full_filed");
            for (let i = 0; i < nodeList.length; i++) {
                nodeList[i].style.display = "block";
            }
            // $('#userDescriptionModel').modal('show');

            const classList = document.querySelectorAll(".input_field");
            for (let i = 0; i < classList.length; i++) {
                classList[i].style.display = "none";
            }
        } else {
            notify('error', data.message, 'KYC');
        }
    }

    /*<---------------Datatable Descriptions for admin log Start------------>*/
    $(document).on("click", ".dt-description", function(params) {
        let __this = $(this);
        let id = $(this).data('id');
        $.ajax({
            type: "GET",
            url: '/admin/kyc-management/kyc-description/' + id,
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

    // Update profile
    function update_profile($this) {
        let request_id = $($this).data('id');
        $.ajax({
            url: '/admin/kyc-management/kyc-request-description/' + request_id,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $("#name").val(data.name);
                $("#dob").val(data.dob);
                $("#country").val(data.country_id).prop('selected');
                $("#state").val(data.state);
                $("#city").val(data.city);
                $("#zip").val(data.zip_code);
                $("#address").val(data.address);
                $("#issue_date").val(data.issue_date);
                $("#expire_date").val(data.exp_date);
                // set hidden id
                $(".kyc-id").val(request_id);
                $(".user-id").val(data.user_id);
            }
        });
    }
</script>
@stop
<!-- BEGIN: page JS -->