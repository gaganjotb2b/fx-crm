@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Credit Management')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/fonts/font-awesome/css/font-awesome.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/jstree.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
<!-- number input -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')}}">
<!-- file uploader -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-tree.css')}}">

<!-- file uploader -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css') }}">
<style>
    .has-error {
        margin-bottom: 1.5rem;
    }
    .clientDetailsBtn {
        display: none;
    }

    @media (max-width: 768px) {
        .col-md-6.d-sm-mt {
            width: 96%;
            margin-top: -2.5rem !important;
        }

        .col-md-6.d-sm-method-mt {
            margin-top: 3rem !important;
        }

        .clientDetailsBtn {
            display: block;
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
                        <h2 class="content-header-title float-start mb-0">{{__('finance.Credit Management')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('finance.home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{__('finance.Finance')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('finance.Credit Management')}}</li>
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
            <div id="admin_credit_form">
                @csrf
                <div class="row">
                    <div class="col-4 d-none d-sm-block">
                        <div class="card">
                            <div class="card-header d-flex">
                                <h4 id="user-type" class="text-capitalize">Trader</h4>
                                <h5 id="user-name-top" class="text-capitalize">---</h5>
                            </div>
                            <hr>
                            <div class="card-body">
                                <div class="rounded ms-1 dt-trader-img img-finance">
                                    <div class="h-100">
                                        <img class="img img-fluid bg-light-primary img-trader-admin user-avatar" src="{{asset('admin-assets/app-assets/images/avatars/'.$avatar)}}" alt="avatar">
                                    </div>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <!-- Name -->
                                    <li class="list-group-item d-flex align-items-center">
                                        <span>{{__('finance.Name')}} </span>
                                        <span class=" ms-auto" id="name">---</span>
                                    </li>
                                    <!-- Address -->
                                    <li class="list-group-item d-flex align-items-center">
                                        <span>{{__('finance.Address')}}</span>
                                        <span class="ms-auto" id="address">---</span>
                                    </li>
                                    <!-- Zip Code -->
                                    <li class="list-group-item d-flex align-items-center" id="zip-code-list">
                                        <span>{{__('finance.Zip Code')}}</span>
                                        <span class=" ms-auto" id="zip-code">---</span>
                                    </li>
                                    <!-- City -->
                                    <li class="list-group-item d-flex align-items-center">
                                        <span>{{__('finance.home')}}</span>
                                        <span class=" ms-auto" id="city">---</span>
                                    </li>
                                    <!-- State -->
                                    <li class="list-group-item d-flex align-items-center">
                                        <span>{{__('finance.State')}}</span>
                                        <span class=" ms-auto" id="state">---</span>
                                    </li>
                                    <!-- Date of Birth -->
                                    <li class="list-group-item d-flex align-items-center d-none">
                                        <span>{{__('finance.Date of Birth')}}</span>
                                        <span class="badge bg-primary rounded-pill ms-auto">---</span>
                                    </li>
                                    <!-- Phone -->
                                    <li class="list-group-item d-flex align-items-center d-none">
                                        <span>{{__('finance.Phone')}}</span>
                                        <span class=" rounded-pill ms-auto">---</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @if(Auth::user()->hasDirectPermission('create credit management'))
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <!-- Modern Horizontal Wizard -->
                        <section class="modern-horizontal-wizard">
                            <div class="bs-stepper wizard-modern modern-wizard-example">
                                <div class="bs-stepper-header">
                                    <div class="step" data-target="#account-details-modern" role="tab" id="account-details-modern-trigger">
                                        <button type="button" class="step-trigger">
                                            <span class="bs-stepper-box">
                                                <i data-feather="file-text" class="font-medium-3"></i>
                                            </span>
                                            <span class="bs-stepper-label">
                                                <span class="bs-stepper-title">Add credit</span>
                                                <span class="bs-stepper-subtitle">Add credit to client</span>
                                            </span>
                                        </button>
                                    </div>
                                    <div class="line">
                                        &nbsp;
                                    </div>
                                    <div class="step" data-target="#personal-info-modern" role="tab" id="personal-info-modern-trigger">
                                        <button type="button" class="step-trigger">
                                            <span class="bs-stepper-box">
                                                <i data-feather="user" class="font-medium-3"></i>
                                            </span>
                                            <span class="bs-stepper-label">
                                                <span class="bs-stepper-title">Deduct credit</span>
                                                <span class="bs-stepper-subtitle">Deduct credit from client</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <!-- stepper content -->
                                <div class="bs-stepper-content">
                                    <!-- add credit -->
                                    <form id="account-details-modern" action="{{route('admin.finance-credit.add')}}" method="post" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger">
                                        @csrf
                                        <div class="d-flex justify-content-between">
                                            <div class="content-header">
                                                <h5 class="mb-0">Add credit</h5>
                                                <small class="text-muted">Add credit to client account.</small>
                                            </div>
                                            <button class="btn btn-sm btn-primary clientDetailsBtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEnd" aria-controls="offcanvasEnd">Client Details</button>
                                        </div>
                                        <!-- client -->
                                        <div class="mb-1 row">
                                            <div class="col-md-6 fg">
                                                <label for="trader" class="col-form-label">{{__('finance.Trader')}}</label>
                                                <select class="max-length form-select select2-trader" id="trader" name="trader">
                                                    <!-- from select2 ajax -->
                                                </select>
                                            </div>
                                            <div class="col-md-6 fg">
                                                <label for="trading_account" class="col-sm-3 col-form-label d-table-cell">{{__('finance.Trading Account')}}</label>
                                                <select class="select2 form-select" id="trading_account" name="trading_account">
                                                    <option value="">{{__('finance.Select an account')}}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- trading account -->
                                        <div class="mb-1 row">
                                            <div class="col-md-6">
                                                <label for="amount" class="col-form-label">{{__('finance.Amount')}}</label>
                                                <div class="input-group client-type-group">
                                                    <span class="input-group-text" id="basic-addon1"><i data-feather='dollar-sign'></i></span>
                                                    <input type="text" id="amount" class="form-control" name="amount" placeholder="0.00" />
                                                </div>
                                            </div>
                                            <!-- expire date -->
                                            <div class="col-md-6">
                                                <label for="expire_date" class="col-form-label">{{__('finance.Expire Date')}}</label>
                                                <div class="input-group client-type-group">
                                                    <span class="input-group-text" id="basic-addon1"><i data-feather='calendar'></i></span>
                                                    <input type="text" id="expire_date" name="expire_date" class="form-control flatpickr-human-friendly" placeholder="October 14, 2022" />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- note -->
                                        <div class="mb-1 row mt-lg-3 mt-md-3">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-floating mb-0">
                                                            <textarea data-length="100" name="note" class="form-control char-textarea" id="note" rows="3" placeholder="Counter" style="height: 100px"></textarea>
                                                            <label for="textarea-counter">Write a note (optional)</label>
                                                        </div>
                                                        <small class="textarea-counter-value float-end"><span class="char-count">0</span> / 100 </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>&nbsp;</span>
                                            <?php
                                            $disable = '';
                                            $has_multi_submit = has_multi_submit('finance-credit', 15);
                                            if ($has_multi_submit) {
                                                $disable = 'disabled';
                                            }
                                            ?>
                                            <button class="btn btn-primary" data-label="Save" onclick="_run(this)" data-submit_wait="{{submit_wait('finance-credit',15)}}" type="button" id="btn-add-credit" data-el="fg" data-form="account-details-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="createCallBack" data-btnid="btn-add-credit" {{$disable}}>
                                                <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                                <span class="align-middle d-sm-inline-block d-none">Save</span>
                                            </button>
                                        </div>
                                    </form>
                                    <!-- deduct credit -->
                                    <form action="{{route('admin.finance-credit.deduct')}}" method="post" id="personal-info-modern" class="content" role="tabpanel" aria-labelledby="personal-info-modern-trigger">
                                        @csrf
                                        <div class="d-flex justify-content-between">
                                            <div class="content-header">
                                                <h5 class="mb-0">Deduct credit</h5>
                                                <small>Deduct credit from client account.</small>
                                            </div>
                                            <button class="btn btn-sm btn-primary clientDetailsBtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEnd" aria-controls="offcanvasEnd">Client Details</button>
                                        </div>
                                        <div class="mb-1 row">
                                            <!-- client /deduct -->
                                            <div class="col-md-6 fg">
                                                <label for="trader" class="col-form-label">{{__('finance.Trader')}}</label>
                                                <select class="max-length form-select select2-trader" id="trader-deduct" name="trader">
                                                    <!-- from select2 ajax -->
                                                </select>
                                            </div>
                                            <div class="col-md-6 fg">
                                                <label for="trading-account-deduct" class="col-sm-3 col-form-label">{{__('finance.Trading Account')}}</label>
                                                <select class="select2 form-select" id="trading-account-deduct" name="trading_account">
                                                    <option value="">{{__('finance.Select an account')}}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- trading account /deduct -->
                                        <div class="mb-1 row">
                                            <div class="col-md-12">
                                                <label for="amount-deduct" class="col-form-label">{{__('finance.Amount')}}</label>
                                                <div class="input-group client-type-group">
                                                    <span class="input-group-text" id="amount-deduct"><i data-feather='dollar-sign'></i></span>
                                                    <input type="text" id="amount-deduct" class="form-control" name="amount" placeholder="0.00" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- note -->
                                        <div class="mb-1 row mt-lg-3 mt-md-2">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-floating mb-0">
                                                            <textarea data-length="100" name="note" class="form-control char-textarea" id="note-deduct" rows="3" placeholder="Counter" style="height: 100px"></textarea>
                                                            <label for="textarea-counter">Write a note (optional)</label>
                                                        </div>
                                                        <small class="textarea-counter-value float-end"><span class="char-count">0</span> / 100 </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>&nbsp;</span>
                                            <?php
                                            $disable = '';
                                            $has_multi_submit = has_multi_submit('finance-credit-deduct', 15);
                                            if ($has_multi_submit) {
                                                $disable = 'disabled';
                                            }
                                            ?>
                                            <button class="btn btn-primary" data-label="Save" onclick="_run(this)" data-submit_wait="{{submit_wait('finance-credit-deduct',15)}}" type="button" id="btn-deduct-credit" data-el="fg" data-form="personal-info-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="deduct_callback" data-btnid="btn-deduct-credit" {{$disable}}>
                                                <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                                <span class="align-middle d-sm-inline-block d-none">Save</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>
                        <!-- /Modern Horizontal Wizard -->
                        <!-- Client Details for mobile screen -->
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd" aria-labelledby="offcanvasEndLabel">
                            <div class="offcanvas-header">
                                <h5 id="offcanvasEndLabel" class="offcanvas-title">Client Details</h5>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body my-auto mx-0 flex-grow-0">
                                <div class="card">
                                    <div class="card-header d-flex">
                                        <h4 id="user-type" class="text-capitalize">Trader</h4>
                                        <h5 id="user-name-top-2" class="text-capitalize">---</h5>
                                    </div>
                                    <hr>
                                    <div class="card-body">
                                        <div class="rounded ms-1 dt-trader-img img-finance">
                                            <div class="h-100">
                                                <img class="img img-fluid bg-light-primary img-trader-admin user-avatar" src="{{asset('admin-assets/app-assets/images/avatars/'.$avatar)}}" alt="avatar">
                                            </div>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <!-- Name -->
                                            <li class="list-group-item d-flex align-items-center">
                                                <span>{{__('finance.Name')}} </span>
                                                <span class=" ms-auto" id="name-2">---</span>
                                            </li>
                                            <!-- Address -->
                                            <li class="list-group-item d-flex align-items-center">
                                                <span>{{__('finance.Address')}}</span>
                                                <span class="ms-auto" id="address-2">---</span>
                                            </li>
                                            <!-- Zip Code -->
                                            <li class="list-group-item d-flex align-items-center" id="zip-code-list">
                                                <span>{{__('finance.Zip Code')}}</span>
                                                <span class=" ms-auto" id="zip-code-2">---</span>
                                            </li>
                                            <!-- City -->
                                            <li class="list-group-item d-flex align-items-center">
                                                <span>{{__('finance.home')}}</span>
                                                <span class=" ms-auto" id="city-2">---</span>
                                            </li>
                                            <!-- State -->
                                            <li class="list-group-item d-flex align-items-center">
                                                <span>{{__('finance.State')}}</span>
                                                <span class=" ms-auto" id="state-2">---</span>
                                            </li>
                                            <!-- Date of Birth -->
                                            <li class="list-group-item d-flex align-items-center d-none">
                                                <span>{{__('finance.Date of Birth')}}</span>
                                                <span class="badge bg-primary rounded-pill ms-auto-2">---</span>
                                            </li>
                                            <!-- Phone -->
                                            <li class="list-group-item d-flex align-items-center d-none">
                                                <span>{{__('finance.Phone')}}</span>
                                                <span class=" rounded-pill ms-auto-2">---</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- //Client Details for mobile screen -->
                    </div>
                    @else
                    <div class="col-xl-8 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                @include('errors.permission')
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->
<!-- Modal -->
<div class="modal fade text-start modal-primary" id="kyc-decline-mail-modal" tabindex="-1" aria-labelledby="myModalLabel160" aria-hidden="true">
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
<!-- include here vendor js -->
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>

<!-- picker js -->
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- number input -->
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>

@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/credit-add-v2.js')}}"> </script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-select2.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
<script src="{{asset('common-js/select2-get-trader.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js') }}"></script>
<script src="{{asset('common-js/send-mail.js')}}"></script>

<script>
    // store credit
    // --------------------------------------------------------------------------------------------
    submit_wait("#btn-add-credit");
    submit_wait("#btn-deduct-credit");

    function createCallBack(data) {
        if (data.status == true) {
            $('#btn-add-credit').confirm2({
                request_url: '/admin/finance/credit/mail/add-credit',
                data: {
                    user_id: data.user_id,
                    amount: data.amount,
                    expire_date: data.expire_date,
                    account_id: data.account_id,
                },
                click: true,
                title: 'Credit add',
                message: 'Please wait, while we sending email...',
                button_text: 'Send',
                method: 'POST'
            }, function(data) {
                if (data.status == true) {
                    notify('success', data.message, 'Credit add mail');
                } else {
                    notify('error', data.message, 'Credit add mail');
                }
            });
            $("#account-details-modern").trigger('reset');
            $("select").val('').trigger('change')
            // end: sending mail-----------------------
        } else {
            notify('error', data.message, 'Credit add')
        }
        $.validator("account-details-modern", data.errors);
        submit_wait("#btn-add-credit", data.submit_wait);
    }
    $("#admin_credit_form").trigger('reset');
    $("select").val('').trigger('change');
    // credit deduct from client accont-----------------------------
    function deduct_callback(data) {
        if (data.status == true) {
            $('#btn-deduct-credit').confirm2({
                request_url: '/admin/finance/credit/mail/deduct-credit',
                data: {
                    user_id: data.user_id,
                    amount: data.amount,
                    account_id: data.account_id,
                },
                click: true,
                title: 'Credit deduct',
                message: 'Please wait, while we sending email...',
                button_text: 'Send',
                method: 'POST'
            }, function(data) {
                if (data.status == true) {
                    notify('success', data.message, 'Credit deduct mail');
                } else {
                    notify('error', data.message, 'Credit deduct mail');
                }
            });
            $("#personal-info-modern").trigger('reset');
            $("select").val('').trigger('change')
            // end: sending mail-----------------------
        } else {
            notify('error', data.message, 'Credit Deduct');
        }
        $.validator("personal-info-modern", data.errors);
        submit_wait("#btn-deduct-credit", data.submit_wait);
    }



    /**
     *--------------------------------------------------
     * -- form reset and others handle in bs-stepper---
     * -------------------------------------------------
     */
    $(document).ready(function() {
        $("#trader,#trading_account,#trader-deduct,#trading-account-deduct").val(null).trigger("change");
        $(".step-trigger").on("click", function() {
            $("#admin_credit_form,#personal-info-modern,#account-details-modern").trigger('reset');
            $("#trader,#trading_account,#trader-deduct,#trading-account-deduct").val(null).trigger("change");
            $("#user-name-top,#user-type,#state,#city,#zip-code,#address,#name").text("---");
        });

    })
</script>
@stop
<!-- BEGIN: page JS -->