@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Trader clients')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/vendors.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css')}}">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
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
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
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
                        <h2 class="content-header-title float-start mb-0">{{__('client-management.Trader Clients')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="index.html">{{__('client-management.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#">{{__('client-management.Client Management')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('client-management.Trader Clients')}}</li>
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
                                <h4 class="card-title">{{__('client-management.Report Filter')}}</h4>
                                <div class="btn-exports d-flex justify-content-between">
                                    <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                        <option value="download" data-icon="download" selected>{{__('client-management.Export')}}</option>
                                        <option value="csv" data-icon="file">CSV</option>
                                        <option value="excel" data-icon="file">Excel</option>
                                    </select>
                                    @if(Auth::user()->hasDirectPermission('create trader client'))
                                    <button type="button" class="btn btn-primary ms-1" data-bs-toggle="modal" data-bs-target="#add-new-trader"><i data-feather='plus'></i>{{__('client-management.Add new Trader')}}</button>
                                    @endif
                                </div>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form class="dt_adv_search" method="POST" id="filter-form">
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-md-4">
                                            <label class="form-label" for="finance">{{__('client-management.Search By Finance')}}</label>
                                            <select class="select2 form-select" id="finance">
                                                <option value="">{{__('client-management.All')}}</option>
                                                <option value="deposit">{{__('client-management.Deposit')}}</option>
                                                <option value="withdraw">{{__('client-management.Withdraw')}}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="category">{{__('client-management.Search By Category')}}</label>
                                            <select class="select2 form-select" id="category">
                                                <option value="">{{__('client-management.All')}}</option>
                                                @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{ucwords($category->name)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{__('client-management.Verification Status')}}</label>
                                            <select class="select2 form-select" id="verification-status">
                                                <option value="">{{__('client-management.All')}}</option>
                                                <option value="0">{{__('client-management.Pending')}}</option>
                                                <option value="1">{{__('client-management.verified')}}</option>
                                                <option value="2">{{__('client-management.unverified')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-1">
                                        <div class="col-md-4">
                                            <label class="form-label">{{__('client-management.Account Manager')}}</label>
                                            <input id="account-manager" type="text" class="form-control dt-input" data-column="4" placeholder="Manager Email" data-column-index="3" />
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{__('client-management.Date')}}</label>
                                            <div class="mb-0">
                                                <input type="text" class="form-control dt-date flatpickr-range dt-input" data-column="5" placeholder="StartDate to EndDate" data-column-index="4" name="dt_date" />
                                                <input type="hidden" class="form-control dt-date start_date dt-input" data-column="5" data-column-index="4" name="value_from_start_date" />
                                                <input type="hidden" class="form-control dt-date end_date dt-input" name="value_from_end_date" data-column="5" data-column-index="4" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row mt-2">
                                                <div class="col-lg-6 d-grid">
                                                    <button id="btn-reset" type="button" class="btn btn-secondary">{{__('client-management.Reset')}}</button>
                                                </div>
                                                <div class="col-lg-6 d-grid">
                                                    <button id="btn-filter" type="button" class="btn btn-primary">{{__('client-management.Filter')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <hr class="my-0" />
                        </div>

                        <div class="card">
                            <div class="card-datatable">
                                <table class="datatables-ajax table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{__('client-management.Name')}}</th>
                                            <th>{{__('client-management.Email')}}</th>
                                            <th>{{__('client-management.Phone')}}</th>
                                            <th>{{__('client-management.Joined')}}</th>
                                            <th>{{__('client-management.Status')}}</th>
                                            <th>{{__('client-management.Actions')}}</th>
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
<div class="modal fade text-start modal-primary" id="primary" tabindex="-1" aria-labelledby="myModalLabel160" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{route('admin.comment-trader-admin-form')}}" method="post" id="form-add-comment">
            @csrf
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
                    <button type="button" class="btn btn-primary" id="save-comment-btn" onclick="_run(this)" data-el="fg" data-form="form-add-comment" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="create_new_comment_call_back" data-btnid="save-comment-btn">Save Comment</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal update comments -->
<div class="modal fade text-start modal-primary" id="comment-edit" tabindex="-1" aria-labelledby="myModalLabel160" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{route('admin.comment-trader-admin-update-form')}}" method="post" id="form-update-comment">
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
                                                                    <option value="inconsolata">Inconsolata</option>
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
                    <button type="button" class="btn btn-primary" id="update-comment-btn" onclick="_run(this)" data-el="fg" data-form="form-update-comment" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="update_comment_call_back" data-btnid="update-comment-btn">Save Changes</button>
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
                        <p class="card-text mb-2">Your new password must be different from previously used passwords</p>

                        <form class="auth-reset-password-form mt-2" id="change-password-form" action="{{route('admin.change-password-trader-admin')}}" method="POST">
                            @csrf
                            <div class="mb-1">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="reset-password-new">New Password</label>
                                </div>
                                <div class="input-group input-group-merge form-password-toggle">
                                    <input type="password" class="form-control form-control-merge" id="reset-password-new" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="reset-password-new" tabindex="1" autofocus />
                                    <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                </div>
                            </div>
                            <div class="mb-1">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="reset-password-confirm">Confirm Password</label>
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
                <button type="button" class="btn btn-primary" id="set-new-password" onclick="_run(this)" data-el="fg" data-form="change-password-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="change_password_call_back" data-btnid="set-new-password">Set new password</button>
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
                <!-- Reset Password basic -->
                <div class="card mb-0">
                    <div class="card-body">
                        <h4 class="card-title mb-1">Change Tranaction Pin 🔒</h4>
                        <p class="card-text mb-2">Your new Pin must be different from previously used Pins</p>

                        <form class="auth-reset-password-form mt-2" id="change-pin-form" action="{{route('admin.change-pin-trader-admin')}}" method="post">
                            @csrf
                            <div class="mb-1">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="reset-password-new">New Pin</label>
                                </div>
                                <div class="input-group input-group-merge form-password-toggle">
                                    <input type="password" class="form-control form-control-merge" id="reset-pin-new" name="transaction_pin" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="reset-pin-new" tabindex="1" autofocus />
                                    <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
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
                            <button type="button" class="btn btn-primary mb-1 text-center" id="set-new-pin" onclick="_run(this)" data-el="fg" data-form="change-pin-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="change_trans_pin_call_back" data-btnid="set-new-pin" style="width:200px">Set New Pin</button>
                        </form>
                    </div>
                </div>
                <!-- /Reset Password basic -->
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<!-- Modal update profile-->
<div class="modal fade text-start modal-primary" id="update-profile" tabindex="-1" aria-labelledby="Profile update" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{route('admin.trader-clients-update-profile')}}" method="post" class="modal-content" id="update-profile-form">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="mail-sending-modal">Profile Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- hidden input for id -->
                    <input type="hidden" name="user_id" value="" id="user-id">
                    <!-- full name of trader -->
                    <div class="col-xl-6 col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="name">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" />
                        </div>
                    </div>
                    <!-- email  of trader-->
                    <div class="col-xl-6 col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" />
                        </div>
                    </div>
                    <!-- phone number of trader-->
                    <div class="col-xl-6 col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="email">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone" />
                        </div>
                    </div>
                    <!-- country of trader-->
                    <div class="col-xl-6 col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="country">Country</label>
                            <select name="country" class="form-select form-control" id="country">
                                @foreach($countries as $value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                                |@endforeach
                            </select>
                        </div>
                    </div>
                    <!-- approximate investment -->
                    <div class="col-xl-6 col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="appr-investment">Approximate Investment</label>
                            <input type="text" class="form-control" id="appr-investment" name="app_investment" placeholder="2.00" />
                        </div>
                    </div>
                    <!-- city -->
                    <div class="col-xl-6 col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="2.00" />
                        </div>
                    </div>
                    <!-- state -->
                    <div class="col-xl-6 col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="state">State</label>
                            <input type="text" class="form-control" id="state" name="state" placeholder="State name" />
                        </div>
                    </div>
                    <!-- zip code -->
                    <div class="col-xl-6 col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="zip-code">Zip Code</label>
                            <input type="text" class="form-control" id="zip-code" name="zip_code" placeholder="zip code" />
                        </div>
                    </div>
                    <!-- Address -->
                    <div class="col-xl-12 col-md-12 col-12">
                        <div class="mb-1 mt-1">
                            <div class="form-floating mb-0">
                                <textarea data-length="191" name="address" class="form-control char-textarea" id="address" rows="2" placeholder="Counter" style="height: 75px"></textarea>
                                <label for="textarea-counter">Address</label>
                            </div>
                            <small class="textarea-counter-value float-end"><span class="char-count">0</span> / 191 </small>
                        </div>
                    </div>
                    <!-- password -->
                    <div class="col-xl-6 col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge form-password-toggle mb-2">
                                <input type="password" name="password" class="form-control" id="password" placeholder="Your Password" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                            </div>
                        </div>
                    </div>
                    <!-- transaction pin -->
                    <div class="col-xl-6 col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="transaction-pin">Transaction Pin</label>
                            <div class="input-group input-group-merge form-password-toggle mb-2">
                                <input type="password" name="transaction_pin" class="form-control" id="transaction-pin" placeholder="Your Transaction Pin" aria-describedby="Transaction pin" />
                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                            </div>
                        </div>
                    </div>
                    <!-- trading account limit -->
                    <div class="col-xl-6 col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="trading-ac-limit">Trading Account Limit</label>
                            <div class="input-group">
                                <input type="number" name="trading_ac_limit" id="trading-ac-limit" class="touchspin-min-max" value="19" />
                            </div>
                        </div>
                    </div>
                    <!-- email notification -->
                    <div class="col-xl-6 col-md-6 col-12">
                        <div class="mb-1">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="send_email" type="checkbox" id="email-send" checked />
                                <label class="form-check-label" for="email-send">Send Notification By Email</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary mb-1 text-center" id="btn-update-profile" onclick="_run(this)" data-el="fg" data-form="update-profile-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="update_profile_call_back" data-btnid="btn-update-profile" style="width:200px">Save Change</button>
            </div>
        </form>
    </div>
</div>
<!-- add new trader modal -->
<div class="modal fade text-start modal-primary" id="add-new-trader" tabindex="-1" aria-labelledby="Add New Trader" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{route('admin.trader-admin-add-new-trader')}}" method="post" class="modal-content" id="trader-registration-form">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add New Trader</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <!-- full name -->
                <div class="mb-1">
                    <label class="form-label" for="full-name">Full Name</label>
                    <input type="text" class="form-control" id="full-name" name="full_name" placeholder="Ex: John Arifin" />
                </div>
                <!-- emmail -->
                <div class="mb-1">
                    <label class="form-label" for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Ex: mail@example.como" />
                </div>
                <!-- phone -->
                <div class="mb-1">
                    <label class="form-label" for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="+8801747XXXXXXX" />
                </div>
                <!-- address row-->
                <div class="row">
                    <!-- address -->
                    <div class="col-lg-6">
                        <div class="mb-1">
                            <label class="form-label" for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Elephand Road, Dhaka" />
                        </div>
                    </div>
                    <!-- zip -->
                    <div class="col-lg-6">
                        <div class="mb-1">
                            <label class="form-label" for="zip-code">Zip Code</label>
                            <input type="text" class="form-control" id="zip-code" name="zip_code" placeholder="1245" />
                        </div>
                    </div>
                </div>
                <!-- city row-->
                <div class="row">
                    <!-- city -->
                    <div class="col-lg-6">
                        <div class="mb-1">
                            <label class="form-label" for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="Dhaka" />
                        </div>
                    </div>
                    <!-- state -->
                    <div class="col-lg-6">
                        <div class="mb-1">
                            <label class="form-label" for="state">State</label>
                            <input type="text" class="form-control" id="state" name="state" placeholder="Dhaka" />
                        </div>
                    </div>
                </div>
                <!-- server row-->
                <div class="row">
                    <!-- server -->
                    <div class="col-lg-6">
                        <div class="mb-1 fg">
                            <label class="form-label" for="server">Server</label>
                            <select class="select2 form-select" id="server" name="server_name">
                                <option value="">Choose a Server</option>
                                @if($platform === 'mt4')
                                <option value="mt4">MT4</option>
                                @elseif($platform === 'mt5')
                                <option value="mt5">MT5</option>
                                @else
                                <option value="mt4">MT4</option>
                                <option value="mt5">MT5</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <!-- client type -->
                    <div class="col-lg-6">
                        <div class="mb-1 fg">
                            <label class="form-label" for="client-type">Client Type</label>
                            <select class="select2 form-select" id="client-type" name="client_type">
                                <option value="">Choose client type</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- account type row-->
                <div class="row">
                    <!-- account type -->
                    <div class="col-lg-6">
                        <div class="mb-1 fg">
                            <label class="form-label" for="account-type">Account Type</label>
                            <select class="select2 form-select" id="account-type" name="account_type">
                                <option value="">Choose account type</option>
                            </select>
                        </div>
                    </div>
                    <!-- leerage type -->
                    <div class="col-lg-6">
                        <div class="mb-1 fg">
                            <label class="form-label" for="leverage">Leverage</label>
                            <select class="select2 form-select" id="leverage" name="leverage">
                                <option value="">Choose Leverage</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- country row-->
                <div class="row">
                    <!-- country -->
                    <div class="col-lg-6">
                        <div class="mb-1 fg">
                            <label class="form-label" for="country">Country</label>
                            <select class="select2 form-select" id="country" name="country">
                                @foreach($countries as $value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- date of birth -->
                    <div class="col-lg-6">
                        <div class="mb-1">
                            <div class="fg position-relative">
                                <label class="form-label" for="date-of-birth">Date of Birth</label>
                                <input type="text" name="data_of_birth" id="date-of-birth" class="form-control flatpickr-human-friendly" placeholder="October 14, 2022" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- email sending row-->
                <div class="row">
                    <!-- mark as pre activated -->
                    <div class="col-lg-6">
                        <div class="mb-1">
                            <label class="form-check-label mb-50" for="mark-as-activated">Mark as Pre Activated</label>
                            <div class="form-check form-switch form-check-primary">
                                <input type="checkbox" name="mark_as_activated" class="form-check-input" id="mark-as-activated" checked />
                                <label class="form-check-label" for="mark-as-activated">
                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- send welcome mail -->
                    <div class="col-lg-6">
                        <div class="mb-1">
                            <label class="form-check-label mb-50" for="welcome-email">Send Welcome Email</label>
                            <div class="form-check form-switch form-check-primary">
                                <input type="checkbox" name="welcome_email" class="form-check-input" id="welcome-email" checked />
                                <label class="form-check-label" for="welcome-email">
                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- gender row-->
                <div class="row mb-2">
                    <div class="row custom-options-checkable g-1">
                        <!-- male -->
                        <div class="col-md-4">
                            <input class="custom-option-item-check" type="radio" name="gender" id="male" checked value="male" />
                            <label class="custom-option-item text-center p-1" for="male">
                                <span class="d-flex">
                                    <img class="img img-fluid img-gender-male" src="{{asset('admin-assets/app-assets/images/avatars/avater-men.png')}}" alt="">
                                    <span class="custom-option-item-title h4 d-block">Gender Male</span>
                                </span>
                            </label>
                        </div>
                        <!-- female -->
                        <div class="col-md-4">
                            <input class="custom-option-item-check" type="radio" name="gender" id="female" value="female" />
                            <label class="custom-option-item text-center text-center p-1" for="female">
                                <span class="d-flex">
                                    <img class="img img-fluid img-gender-male" src="{{asset('admin-assets/app-assets/images/avatars/avater-lady.png')}}" alt="">
                                    <span class="custom-option-item-title h4 d-block">Gender Female</span>
                                </span>
                            </label>
                        </div>
                        <!-- other -->
                        <div class="col-md-4">
                            <input class="custom-option-item-check" type="radio" name="gender" id="other" value="other" />
                            <label class="custom-option-item text-center p-1" for="other">
                                <span class="d-flex">
                                    <i data-feather="users" class="font-large-1 mb-75 other-gender-icon"></i>
                                    <span class="custom-option-item-title h4 d-block">Gender Other</span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- password row-->
                <div class="row">
                    <!-- password -->
                    <div class="col-lg-6">
                        <div class="mb-1">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge form-password-toggle">
                                <input type="password" class="form-control" id="password" placeholder="Your Password" aria-describedby="password" name="password" />
                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                            </div>
                        </div>
                    </div>
                    <!-- confirm password -->
                    <div class="col-lg-6">
                        <div class="mb-1">
                            <label class="form-label" for="confirm-password">Confirm Password</label>
                            <div class="input-group input-group-merge form-password-toggle">
                                <input type="password" class="form-control" id="confirm-password" placeholder="Confirm Password" aria-describedby="confirm-password" name="confirm_password" />
                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary mb-1 text-center" id="btn-add-new-trader" onclick="_run(this)" data-el="fg" data-form="trader-registration-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="trader_registration_call_back" data-btnid="btn-add-new-trader" style="width:200px">Save Trader</button>
            </div>
        </form>
    </div>
</div>
<!-- end add new trader modal -->
<!-- Modal Themes end -->

@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js')}}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')

<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>
<!-- datatable -->
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>

<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js')}}"></script>
<!-- <script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js')}}"></script> -->
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script>
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
        $('#text_quill').val(snowEditor.container.firstChild.innerHTML);
    });

    // for update comment
    update_editor.on('text-change', function(delta, oldDelta, source) {
        $('#text_quill_update').val(update_editor.container.firstChild.innerHTML);
    });
</script>
<!-- <script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-quill-editor.js')}}"></script> -->
<script src="{{asset('admin-assets/app-assets/js/scripts/tables/trader-client.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-select2.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
<script>
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

            snowEditor.setContents([]);
            $("#text_quill").val('');
            comment_table_obj.DataTable().draw();
            notify('success', data.message, 'Create New comment')
        } else {
            notify('success', 'Please fix the following errors!', 'Create New comment')
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
            notify('success', data.message, 'Update Comment')
            snowEditor.setContents([]);
            $("#text_quill_update").val('');
            comment_table_obj.DataTable().draw();

            $("#comment-edit").modal('hide');
        } else {
            notify('success', 'Please fix the following errors!', 'Update Comment')
        }
        $.validator("form-update-comment", data.errors);
    }
    // update user profile-----------------------------------------------------
    function update_profile_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Profile updated');
        } else {
            notify('error', 'Please fix the following errors!', 'Profile updated');
        }
        $.validator("update-profile-form", data.errors);
    }
    // END: update user profile-----------------------------------------------------

    // start: add mew traders--------------------------------------------------
    function trader_registration_call_back(data) {
        $.validator("trader-registration-form", data.errors);
        if (data.trader_registration == true) {
            notify('success', 'New Trader Successfully Registered', 'Trader Registration');
            $("#add-new-trader").modal('hide');
            $("#trader-registration-form").trigger('reset');
            $("#server, #client-type, #account-type, #leverage, #country").trigger("change");
        } else {
            notify('error', 'New Trader Registration Failed', 'Trader Registration');
        }
        // message for create trading account
        if (data.create_trading_account == true) {
            notify('success', 'Trading Account Successfully Created', 'Create Trading Account');
        } else {
            notify('error', 'Trading Account Creation Failed', 'Create Trading Account');
        }
        // sending welcome mail-----------------
        if (data.welcome_mail == true) {
            let trader_id = data.trader_id;
            let $url = `/admin/client-management/send-welcome-email/` + trader_id;
            send_mail('Welcome Email', 'Welcome mail sending for new account', $url, true);
        }
        $('.datatables-ajax').DataTable().draw();
    }
    // END: assign account manager-----------------------------------------------------
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
    // END: change password-----------------------------------------------------
</script>
@stop
<!-- BEGIN: page JS -->