@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','KYC Report')
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
        width: 300px;
        height: 300px;
        overflow: hidden;
        margin: 0 auto;
    }

    .geeks img {
        width: 100%;
        transition: 0.5s all ease-in-out;
    }

    .geeks:hover img {
        transform: scale(1.5);
    }

    ul#myTab {
        width: 80%;
        margin: 0 auto;
    }

    .user-description>ul {
        margin-left: 30px;
    }

    .user-description li {
        padding-bottom: 2vh;
    }
    .user-card {
        min-height: 100% !important;
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
                        <h2 class="content-header-title float-start mb-0">{{__('admin-breadcrumbs.kyc_report')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('finance.home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{__('admin-menue-left.kyc_management')}}</a>
                                </li>
                                <li class="breadcrumb-item active"> {{__('admin-breadcrumbs.kyc_report')}}
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
                                <h4 class="card-title">{{__('ib-management.filter_report')}}</h4>
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
                                        <div class="col-lg-4 col-md-6">
                                            <label for="type" class="form-label">Document Type</label>
                                            <select class="select2 form-select" name="type" id="type">
                                                <optgroup label="Search By Category">
                                                    <option value="">{{__('ib-management.all')}}</option>
                                                    <option value="adhar card">ADHAR CARD</option>
                                                    <option value="passport">PASSPORT</option>
                                                    <option value="driving license">DRIVING LICENSE</option>
                                                    <option value="credit card statement">CREDIT CARD STATEMENT</option>
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
                                        <!-- Filter By Client Type -->
                                        <div class="col-lg-4 col-md-6">
                                            <label for="client_type" class="form-label">Client Type</label>
                                            <select class="select2 form-select" name="client_type" id="client_type">
                                                <optgroup label="Search By Account Type">
                                                    <option value="">{{__('ib-management.all')}}</option>
                                                    <option value="ib">IB</option>
                                                    <option value="trader">TRADER</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <!-- Filter By Verification Status -->
                                        <div class="col-lg-4 col-md-6">
                                            <label for="status" class="form-label">Verification Status</label>
                                            <select class="select2 form-select" name="status" id="status">
                                                <optgroup label="Search By Status">
                                                    <option value="">{{__('ib-management.all')}}</option>
                                                    <option value="0">Pending</option>
                                                    <option value="1">Verified</option>
                                                    <option value="2">Declined</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <!-- Filter By Request Date -->
                                        <div class="col-lg-4 col-md-6">
                                            <label for="" class="form-label">Request Date</label>
                                            <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Create Date" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
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
                                        <!---- User Email / Name  ---->
                                        <div class="col-lg-4 col-md-6">
                                            <label for="" class="form-label">User Info.</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter User Name or Email" class="form-control dt-input dt-full-name" data-column="1" name="info" id="info" placeholder="Name / Email" data-column-index="0" />
                                        </div>
                                        <!---- Account & Desk Manager  ---->
                                        @if(App\Services\systems\VersionControllService::check_version()==='lite')
                                         <div class="col-lg-4 col-md-6">
                                            <label for="country_info" class="form-label">Country</label>
                                            <select class="select2 form-select js-example-basic-single" name="country_info" id="country_info" title="Search by Country">
                                                <option value="" selected>Select Country</option>
                                                @foreach ($countryList as $value)
                                                    <option value=" {{ $value->id }} ">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>   
                                        @else
                                        <div class="col-lg-4 col-md-6">
                                            <label for="" class="form-label">Manager Info.</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Put Account & Desk Manager Email / Phone / Name" class="form-control dt-input dt-full-name" data-column="1" name="manager_email" id="manager_email" placeholder="Account / Desk Manager Email" data-column-index="0"/>
                                        </div>
                                        @endif
                                        <!---- Trading-Account Number ---->
                                        <div class="col-lg-4 col-md-6">
                                            <label for="" class="form-label">Trading Account</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Trading Account Number" class="form-control dt-input dt-full-name" data-column="1" name="trading_number" id="trading_number" placeholder="Account Number" data-column-index="0" />
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <label></label>  
                                            <button id="resetBtn" type="button" class="btn btn-danger waves-effect waves-float waves-light w-100">
                                                <span class="align-middle">{{__('ib-management.reset')}}</span>
                                            </button>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <label></label>  
                                            <button id="filterBtn" type="button" class="btn btn-primary waves-effect waves-float waves-light w-100">
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

                                <table id="kyc_report_tbl" class="datatables-ajax ib-withdraw table">
                                    <thead>
                                        <tr>
                                            <th>{{__('page.client_name')}}</th>
                                            <th>{{__('finance.Client')}} {{__('page.type')}}</th>
                                            <th>{{__('page.document-type')}}</th>
                                            <th>{{__('page.issue-date')}}</th>
                                            <th>{{__('page.expire-date')}}</th>
                                            <th>{{__('page.status')}}</th>
                                            <th>{{__('page.date')}}</th>
                                            <th id="action">{{__('page.action')}}</th>
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
<!-- kyc request user profile modal  -->
<!--<div class="modal fade" id="pricingModal" tabindex="-1" aria-labelledby="userDescriptionModel" aria-hidden="true">-->
<!--    <div class="modal-dialog modal-dialog-centered modal-xl">-->
<!--        <div class="modal-content">-->
<!--            <div class="modal-header bg-transparent">-->
<!--                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
<!--            </div>-->
<!--            <div class="modal-body px-sm-5 mx-50 pb-5">-->
<!--                <div id="pricing-plan">-->
                    <!-- title text and switch button -->
<!--                    <div class="card-header d-flex justfy-content-between">-->
<!--                        <h1 id="pricingModalTitle">User Proof</h1>-->
<!--                    </div>-->
                    <!--/ title text and switch button -->
                    <!-- pricing plan cards -->
<!--                    <div class="row pricing-card">-->
                        <!-- First Card -->
<!--                        <div class="col-8 col-lg-8 content-body">-->
<!--                            <section id="nav-filled">-->
<!--                                <div class="row match-height">-->
                                    <!-- Filled Tabs starts -->
<!--                                    <div class="col-xl-12 col-lg-12">-->
<!--                                        <div class="card">-->
<!--                                            <div class="card-body pt-2">-->
                                                <!-- Nav tabs -->
<!--                                                <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">-->
<!--                                                    <li class="nav-item">-->
<!--                                                        <a class="nav-link active" id="home-tab-fill" data-bs-toggle="tab" href="#home-fill" role="tab" aria-controls="home-fill" aria-selected="true">Front Part</a>-->
<!--                                                    </li>-->
<!--                                                    <li class="nav-item">-->
<!--                                                        <a class="nav-link" id="profile-tab-fill" data-bs-toggle="tab" href="#profile-fill" role="tab" aria-controls="profile-fill" aria-selected="false">Back Part</a>-->
<!--                                                    </li>-->
<!--                                                </ul>-->
                                                <!-- Tab panes -->
<!--                                                <div class="tab-content pt-1 pb-4">-->
<!--                                                    <div class="tab-pane active" id="home-fill" role="tabpanel" aria-labelledby="home-tab-fill">-->
<!--                                                        <div class="geeks" style="height: 80%; width: 80%;">-->
<!--                                                            <img id="front_part" class="img-thumbnail" src="{{asset('admin-assets/driver_license.png')}}">-->
<!--                                                            <embed src="" id="front_part_pdf" type="application/pdf" width="100%" height="600px" />-->
<!--                                                        </div>-->
<!--                                                    </div>-->
<!--                                                    <div class="tab-pane" id="profile-fill" role="tabpanel" aria-labelledby="profile-tab-fill">-->
<!--                                                        <div class="geeks" style="height: 80%; width: 80%;">-->
<!--                                                            <img id="backpart_part" class="img-thumbnail" src="{{asset('admin-assets/passport.png') }}">-->
<!--                                                            <embed src="" id="backpart_part_pdf" type="application/pdf" width="100%" height="600px" />-->
<!--                                                        </div>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </section>-->
<!--                        </div>-->
                        <!--/ standard plan -->

                        <!-- enterprise plan -->
<!--                        <div class="col-4 col-lg-4 content-body">-->
<!--                            <section id="nav-filled">-->
<!--                                <div class="row match-height">-->
<!--                                    <div class="col-xl-12 col-lg-12">-->
<!--                                        <div class="card">-->
<!--                                            <div class="card-body p-3 user-description">-->
<!--                                                <h3 class="pb-1">User Description</h3>-->
<!--                                                <ul class="list-group list-group-circle text-start fw-bold">-->
<!--                                                    <li>Name: <span class="text-primary" id="user_name"></span></li>-->
<!--                                                    <li>Email : <span class="text-primary" id="user-email"> </span></li>-->
<!--                                                    <li>Country : <span class="text-primary" id="user-country"></span></li>-->
<!--                                                    <li>Address : <span class="text-primary" id="user-address"></span></li>-->
<!--                                                    <li>City : <span class="text-primary" id="user-city"></span></li>-->
<!--                                                    <li>State : <span class="text-primary" id="user-state"></span></li>-->
<!--                                                    <li>Phone : <span class="text-primary" id="user-phone"></span></li>-->
<!--                                                    <li>Zip : <span class="text-primary" id="user-zip-code"></span></li>-->
<!--                                                    <li>Date Of Birth : <span class="text-primary" id="user-dob"></span></li>-->
<!--                                                    <li>Status : <span id="user-status"> </span></li>-->
<!--                                                </ul>-->
<!--                                                <hr />-->
<!--                                                <ul class="list-group list-group-circle text-start fw-bold">-->
<!--                                                    <li class="modal-issue-date">Issue Date : <span class="text-primary" id="user-issue_date"></span></li>-->
<!--                                                    <li class="modal-issue-date">Expire Date : <span class="text-primary" id="user-exp_date"></span></li>-->
<!--                                                    <li>Document Type : <span class="text-primary" id="user-doc_type"></span></li>-->
<!--                                                    <li>Issuer Country : <span class="text-primary" id="user-issuer-country"></span></li>-->
<!--                                                </ul>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </section>-->
<!--                        </div>-->
                        <!--/ enterprise plan -->
<!--                    </div>-->
                    <!--/ pricing plan cards -->
<!--                    <div class="text-center">-->
<!--                        <p class="details-text" style="float:right;">-->
<!--                            <button data-type="button" class="btn btn-success waves-effect waves-float waves-light" data-bs-dismiss="modal" onclick="">Close</button>-->
<!--                        </p>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<div class="modal fade" id="pricingModal" tabindex="-1" aria-labelledby="userDescriptionModel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="pricing-plan">
                    <!-- title text and switch button -->
                    <div class="card-header d-flex justfy-content-between">
                        <h1 id="pricingModalTitle">User Proof</h1>
                    </div>
                    <!--/ title text and switch button -->
                    <!-- pricing plan cards -->
                    <div class="row pricing-card">
                        <!-- KYC Document IMG / PDF Start -->
                        <div class="col-lg-8 col-12 docFile">
                            <div class="card user-card" id="nav-filled">
                                <div class="card-body">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="home-tab-fill" data-bs-toggle="tab" href="#home-fill" role="tab" aria-controls="home-fill" aria-selected="true">Front Part</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="profile-tab-fill" data-bs-toggle="tab" href="#profile-fill" role="tab" aria-controls="profile-fill" aria-selected="false">Back Part</a>
                                        </li>
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content pt-5">
                                        <div class="tab-pane active" id="home-fill" role="tabpanel" aria-labelledby="home-tab-fill">
                                            <div class="geeks">
                                                <img id="front_part" class="img-thumbnail" src="{{asset('admin-assets/driver_license.png')}}">
                                                <embed src="" id="front_part_pdf" type="application/pdf" width="100%" height="600px" />

                                            </div>
                                        </div>
                                        <div class="tab-pane" id="profile-fill" role="tabpanel" aria-labelledby="profile-tab-fill">
                                            <div class="geeks">
                                                <img id="backpart_part" class="img-thumbnail" src="{{asset('admin-assets/passport.png') }}">
                                                <embed src="" id="backpart_part_pdf" type="application/pdf" width="100%" height="600px" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- KYC Document IMG / PDF End -->
                        <!-- KYC Description Start -->
                        <div class="col-lg-4 col-12 docText">
                            <div class="card user-card" id="nav-filled">
                                <div class="card-body">
                                    <h3 class="text-center">KYC Information</h3>
                                    <ul class="fw-bold pt-5 list-style-icons user-description">
                                        <li>Name: <span class="text-primary" id="user_name"></span></li>
                                        <li>Email : <span class="text-primary" id="user-email"> </span></li>
                                        <li>Country : <span class="text-primary" id="user-country"></span></li>
                                        <li>Address : <span class="text-primary" id="user-address"></span></li>
                                        <li>City : <span class="text-primary" id="user-city"></span></li>
                                        <li>State : <span class="text-primary" id="user-state"></span></li>
                                        <li>Phone : <span class="text-primary" id="user-phone"></span></li>
                                        <li>Zip : <span class="text-primary" id="user-zip-code"></span></li>
                                        <li>Date Of Birth : <span class="text-primary" id="user-dob"></span></li>
                                        <li>Status : <span id="user-status"> </span></li>
                                        <li class="modal-issue-date">Issue Date : <span class="text-primary" id="user-issue_date"></span></li>
                                        <li class="modal-issue-date">Expire Date : <span class="text-primary" id="user-exp_date"></span></li>
                                        <li>Document Type : <span class="text-primary" id="user-doc_type"></span></li>
                                        <li>Issuer Country : <span class="text-primary" id="user-issuer-country"></span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- KYC Description End -->
                    </div>
                    <!--/ pricing plan cards -->
                    <div class="mt-2">
                        <button data-type="button" class="btn btn-success waves-effect waves-float waves-light float-end" data-bs-dismiss="modal" onclick="">Close</button>
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
{{-- <script src="{{ asset('admin-assets/app-assets/vendors/js/vendors.min.js') }}"></script> --}}
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js')}}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
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
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>


<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@stop
<!-- END: page vendor js -->

<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
<!-- <script src="{{asset('admin-assets/app-assets/js/scripts/tables/table-datatable-kyc-report-v2.js')}}"></script>
 -->
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


            "url": "/admin/kyc-management/kyc-report?op=data_table",
            "data": function(d) {
                return $.extend({}, d, {
                    "from": $("#from").val(),
                    "to": $("#to").val(),
                    "type": $("#type").val(),
                    "status": $("#status").val(),
                    "client_type": $("#client_type").val(),
                    "info": $("#info").val(),
                    // "issue_from": $("#issue_from").val(),
                    // "issue_to": $("#issue_to").val(),
                    // "expire_from": $("#expire_from").val(),
                    // "expire_to": $("#expire_to").val(),
                     "manager_email": $("#manager_email").val(),
                    "ib_email": $("#ib_email").val(),
                    "trading_number": $("#trading_number").val(),
                    "country_info": $("#country_info").val()

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


    /*<---------For reset button script-------------->*/
    $(document).ready(function() {
        $("#resetBtn").click(function() {
            $("#filterForm")[0].reset();
            $('#type').prop('selectedIndex', 0).trigger("change");
            $('#verification_status').prop('selectedIndex', 0).trigger("change");
            $('#status').prop('selectedIndex', 0).trigger("change");
            $('#client_type').prop('selectedIndex', 0).trigger("change");
            $('#country_info').prop('selectedIndex', 0).trigger("change");
            $('#trading_number').trigger("change");
            dt.draw();
        });
    });



    // User Description view
    function view_document(e) {
        let obj = $(e);
        var id = obj.data('id');

        var table_id = obj.data('table_id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/kyc-management/kyc-report-view-descrption/' + id + '/' + table_id,
            method: 'GET',
            dataType: 'json',
            success: function(data) {

                if (data.group_name == 'id proof') {
                    $('#profile-tab-fill').show();
                    if (data.front_part_file_type === 'pdf') {
                        $('#front_part_pdf').attr("src", data.front_part).show();
                        $('#front_part').hide();
                    } else {
                        $('#front_part').attr("src", data.front_part).show();
                        $('#front_part_pdf').hide();
                    }
                    if (data.back_part_file_type === 'pdf') {
                        $('#backpart_part_pdf').attr("src", data.back_part).show();
                        $('#backpart_part').hide();
                    } else {
                        $('#backpart_part').attr("src", data.back_part).show();
                        $('#backpart_part_pdf').hide();
                    }
                } else if (data.group_name == 'address proof') {
                    $('#profile-tab-fill').hide();
                    if (data.front_part_file_type === 'pdf') {
                        $('#front_part_pdf').attr("src", data.front_part);
                    }
                    else {
                        $('#front_part').attr("src", data.front_part);
                    }
                }

                if (data.document_name == "adhar card") {
                    $('.modal-issue-date', ).addClass('d-none');
                    $('.modal-expire-date').addClass('d-none');
                } else {
                    $('.modal-issue-date').removeClass('d-none');
                    $('.modal-expire-date').removeClass('d-none');
                }

                $('#user-status').html(data.status);
                $('#user_name').text(data.user.name);
                $('#user-email').text(data.user.email);
                $('#user-phone').text(data.user.phone);
                $('#user-city').text(data.user.city);
                $('#user-state').text(data.user.state);
                $('#user-address').text(data.user.address);
                $('#user-zip-code').text(data.user.zip_code);
                $('#user-issue_date').text(data.issue_date);
                $('#user-exp_date').text(data.exp_date);
                $('#user-doc_type').text(data.document_name);
                $('#user-country').text(data.country.name);
                $('#user-dob').text(data.dob);
                $('#user-issuer-country').text(data.country.name);

                var hidden = data.user_kyc_sts;
                if (hidden != 0) {
                    document.getElementById('decline_button').style.visibility = 'hidden';
                    document.getElementById('approve_button').style.visibility = 'hidden';
                } else {
                    document.getElementById('decline_button').style.visibility = 'visible';
                    document.getElementById('approve_button').style.visibility = 'visible';
                }
            }
        });
    }
</script>
@stop
<!-- BEGIN: page JS -->
