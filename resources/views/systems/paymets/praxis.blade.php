@extends('layouts.system-layout')
@section('title','Praxis Setting')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/assets/css/config-form.css')}}">

<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/admin.css') }}">
<style>
    h4.card-title.float-start.d-flex {
        padding-top: 8px;
    }

    form#trader-setting-add-form {
        min-height: 300px;
    }
</style>
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Praxis {{__('page.settings')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">Payments {{__('page.settings')}}</a></li>
                                <li class="breadcrumb-item active">Praxis</li>
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
            <div class="row">
                <div class="col-5">
                    <div class="content-body">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <h4> {{__('ib-management.Note')}}</h4>
                                    <code class="bg">{{__('ib-management.please read carefully')}}</code>
                                </div>
                            </div>
                            <hr>
                            <div class="card-body">
                                <div class="border-start-3 border-start-primary p-1 bg-light-primary mb-1">
                                    Praxis is a smart cashier software provides payment and intelligent cashier services.
                                </div>
                                <div class="border-start-3 border-start-info p-1 bg-light-primary mb-1">
                                    Choose the currencies we want to support for online payments. Ensure that our chosen currencies align with our target markets and business model.
                                </div>
                                <div class="border-start-3 border-start-danger p-1 bg-light-primary mb-1">
                                    Implement strong security measures, including data encryption and tokenization, to protect sensitive customer information during payment processing.
                                </div>
                                <div class="border-start-3 border-start-success p-1 bg-light-primary mb-1">
                                    Ensure that our merchant account details are correctly configured, including the merchant ID, API keys, and secret keys.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-7" style="float: right; width: 56%;">
                    <div class="card">
                        <div class="card-header mb-0">
                            <div class="card my-0 py-0 w-100">
                                <div class="card-body my-0 py-0">
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                            <div class="card p-0">
                                                <!-- Help2pay Settings Form -->
                                                <form action="" method="POST" enctype="multipart/form-data" id="praxis_settings_form">
                                                    @csrf
                                                    <div class="row">
                                                        <h5 class="mb-2">Praxis Settings</h5>
                                                        <!-- API URL -->
                                                        <div class="col-12 col-sm-6 pb-1 mt-1">
                                                            <label for="api_url" class="form-label">API URL</label>
                                                            <div class="input-group">
                                                                <input type="text" name="api_url" class="form-control flatpickr-basic" placeholder="API URL">
                                                            </div>
                                                        </div>
                                                        <!-- API Token -->
                                                        <div class="col-12 col-sm-6 pb-1">
                                                            <label for="api_token" class="col-form-label">API Token</label>
                                                            <input type="text" name="api_token" class="form-control flatpickr-basic" placeholder="API token">
                                                        </div>
                                                        <!-- API Secret -->
                                                        <div class="col-12 col-sm-6 pb-1">
                                                            <label for="api_secret" class="col-form-label">API Secret</label>
                                                            <input type="text" name="api_secret" class="form-control flatpickr-basic" placeholder="API secret">
                                                        </div>
                                                        <div class="col-12 mt-2">
                                                            <div class="p-0 m-0">
                                                                <button type="button" class="btn btn-primary text-center" id="btn-submit-praxis-settings" onclick="_run(this)" data-el="fg" data-form="praxis_settings_form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="praxis_submit_callback" data-btnid="btn-submit-praxis-settings" style="width:200px; float:right">Submit request</button>
                                                            </div>
                                                        </div>
                                                </form>
                                                <!--/Transaction Settings Form -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<!-- datatable -->
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- admin settings common ajax -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-settings.js')}}"></script>

<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-select2.js')}}"></script>
<script src="{{asset('common-js/trader-settings.js')}}" type="text/javascript"></script>
@stop
<!-- BEGIN: page JS -->