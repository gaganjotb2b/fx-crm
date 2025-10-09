@extends('layouts.system-layout')
@section('title','API Configuration')
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
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/assets/css/config-form.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css')}}">
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
                        <h2 class="content-header-title float-start mb-0">API Configuration</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">Configurations</a></li>
                                <li class="breadcrumb-item active">API Configuration</li>
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
            <!-- Modern Horizontal Wizard -->
            <section class="modern-horizontal-wizard vertical-wizard">
                <div class="bs-stepper wizard-modern modern-wizard-example">
                    <div class="bs-stepper-header bg-light-primary">
                        <!-- step crm setup -->
                        <div class="step mt4" data-target="#account-details-modern" role="tab" id="account-details-modern-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="file-text" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">MT4 API</span>
                                    <span class="bs-stepper-subtitle">Setup MT4 API Configuration</span>
                                </span>
                            </button>
                        </div>
                        <div class="line">
                            &nbsp;
                        </div>
                        <!-- required fields -->
                        <div class="step mt5" data-target="#personal-info-modern" role="tab" id="personal-info-modern-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="user" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">MT5 API</span>
                                    <span class="bs-stepper-subtitle">Setup MT5 API Configuration</span>
                                </span>
                            </button>
                        </div>
                        <div class="line">
                            &nbsp;
                        </div>
                        <!-- CRM Version -->
                        <div class="step" data-target="#address-step-modern" role="tab" id="address-step-modern-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="map-pin" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Vertex API</span>
                                    <span class="bs-stepper-subtitle">Setup Vertex API Configuration</span>
                                </span>
                            </button>
                        </div>
                        <div class="line">
                            &nbsp;
                        </div>
                    </div>
                    <!-- bs stepper content -->

                    <div class="bs-stepper-content">
                        <!-- CRM Ddfault setup -->
                        <!-- stepper content -->
                        <div id="account-details-modern" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger">
                            <div class="content-header">
                                <h5 class="mb-0">MT4 API Configuration</h5>
                                <small class="text-muted">Enter your MT4 API info.</small>
                            </div>
                            <!-- Start vertical wizard -->
                            <section class="vertical-wizard">
                                <div class="bs-stepper vertical vertical-wizard-example shadow-none">
                                    <div class="bs-stepper-header border-end">
                                        <div class="step live-server-btn" data-target="#account-details-vertical" role="tab" id="account-details-vertical-trigger">
                                            <button type="button" class="step-trigger">
                                                <span class="bs-stepper-box">1</span>
                                                <span class="bs-stepper-label">
                                                    <span class="bs-stepper-title">Live Server</span>
                                                    <span class="bs-stepper-subtitle">Setup Live Server Details</span>
                                                </span>
                                            </button>
                                        </div>
                                        <div class="step demo-server-btn" data-target="#personal-info-vertical" role="tab" id="personal-info-vertical-trigger">
                                            <button type="button" class="step-trigger">
                                                <span class="bs-stepper-box">2</span>
                                                <span class="bs-stepper-label">
                                                    <span class="bs-stepper-title">Demo Server</span>
                                                    <span class="bs-stepper-subtitle">Setup Demo Server Details</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="bs-stepper-content shadow-none">

                                        <!-- Live server form start -->
                                        <div id="account-details-vertical" class="content live-server-details" role="tabpanel" aria-labelledby="account-details-vertical-trigger">
                                            <div class="content-header">
                                                <h5 class="mb-0">Live Server Details</h5>
                                                <small class="text-muted">Enter Your Server Details.</small>
                                            </div>
                                            <div class="row">
                                                <!-- ------------------------------------------------------------------------
                                                |                       MT4 Live Form
                                                ----------------------------------------------------------------------------->
                                                <form action="{{route('system.configarations.mt4-live-api-config')}}" method="post" id="live-api-form-mt4">
                                                    @csrf
                                                    <!-- API url -->
                                                    <input type="hidden" name="platform_type" value="mt4">
                                                    <input type="hidden" name="server_type" value="live">
                                                    <div class="row">
                                                        <div class="mb-1 col-md-6">
                                                            <label class="form-label" for="mt4_api_url">API URL</label>
                                                            <input type="text" class="form-control" id="mt4_api_url" name="mt4_api_url" value="<?php echo (isset($api_configs->api_url) ? $api_configs->api_url : '') ?>" />
                                                            <span class="text-danger" id="mt4_api_url_error"></span>
                                                        </div>
                                                        <!-- API key -->
                                                        <div class="mb-1 col-md-6">
                                                            <label class="form-label" for="mt4_api_key">API Key</label>
                                                            <input type="password" class="form-control" id="mt4_api_key" name="mt4_api_key" value="<?php echo (isset($api_configs->live_api_key) ? $api_configs->live_api_key : '') ?>" />
                                                            <span class="text-danger" id="mt4_api_key_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <button class="btn btn-outline-secondary btn-prev" disabled>
                                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                        </button>
                                                        <button class="btn btn-primary" type="button" id="mt4_submit_btn" data-btnid="mt4_submit_btn" onclick="_run(this)" data-form="live-api-form-mt4" data-callback="live_api_mt4_callback" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>">
                                                            <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                                            <span class="align-middle d-sm-inline-block d-none">Save</span>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- //End live server form -->
                                        <!-- Demo server form start -->
                                        <div id="personal-info-vertical" class="content shadow-none demo-server-details" role="tabpanel" aria-labelledby="personal-info-vertical-trigger">
                                            <div class="content-header">
                                                <h5 class="mb-0">Demo Server Details</h5>
                                                <small>Enter Your Server Details</small>
                                            </div>
                                            <div class="row">
                                                <!-- ------------------------------------------------------------------------
                                                |                       MT4 Demo Form
                                                ----------------------------------------------------------------------------->
                                                <form action="{{route('system.configarations.mt4-demo-api-config')}}" method="POST" id="demo_api_form">
                                                    @csrf
                                                    <!-- API url -->
                                                    <div class="row">
                                                        <input type="hidden" name="platform_type" value="mt4">
                                                        <input type="hidden" name="server_type" value="demo">
                                                        <div class="mb-1 col-md-6">
                                                            <label class="form-label" for="mt4_api_url">API URL</label>
                                                            <input type="text" class="form-control" id="mt4_api_url" name="mt4_api_url" value="<?php echo (isset($demo_api->api_url) ? $demo_api->api_url : '') ?>" />
                                                            <span class="text-danger" id="mt4_api_url_error"></span>
                                                        </div>
                                                        <!-- API key -->
                                                        <div class="mb-1 col-md-6">
                                                            <label class="form-label" for="mt4_api_key">API Key</label>
                                                            <input type="password" class="form-control" id="mt4_api_key" name="mt4_api_key" value="<?php echo (isset($demo_api->demo_api_key) ? $demo_api->demo_api_key : '') ?>" />
                                                            <span class="text-danger" id="mt4_api_key_error"></span>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <button class="btn btn-primary btn-prev">
                                                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                </button>
                                                <button class="btn btn-primary" type="button" id="demo_api_submit_btn" data-btnid="demo_api_submit_btn" onclick="_run(this)" data-form="demo_api_form" data-callback="demo_api_mt4_callback" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>">
                                                    <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                                    <span class="align-middle d-sm-inline-block d-none">Save</span>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- End demo server form -->

                                    </div>
                                </div>
                            </section>
                            <!-- //End Vertical Wizard -->
                        </div>
                        <!-- Filed required -->
                        <!-- ------------------------------------------------------------------------
                        |                       MT4 Demo Form
                        ----------------------------------------------------------------------------->
                        <!-- stepper content / 2nd step -->
                        <div id="personal-info-modern" class="content" role="tabpanel" aria-labelledby="personal-info-modern-trigger">
                            @csrf
                            <div class="d-flex justify-content-between me-2">
                                <div class="">
                                    <div class="content-header">
                                        <h5 class="mb-0">MT5 API Configuration</h5>
                                        <small class="text-muted">Enter your MT5 API info.</small>
                                    </div>
                                </div>
                                <div class="">
                                    <!-- Manager/Web Status -->
                                    <div class="mb-1 form-password-toggle col-md-4">
                                        <h6 class="fw-bolder mb-75">
                                            <label class="form-check-label mb-50" for="manage_web_selection_btn">Manager/Web</label>
                                        </h6>
                                        <div class="d-flex flex-column">
                                            <div class="form-check form-check-success form-switch">
                                                <input type="checkbox" checked="" name="status" value="1" class="form-check-input" id="manage_web_selection_btn" checked>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Start vertical wizard -->
                            <section class="vertical-wizard">
                                <div class="bs-stepper vertical vertical-wizard-example shadow-none" id="manager_wizard">
                                    <div class="bs-stepper-header border-end">
                                        <div class="step" data-target="#manager-details-vertical" role="tab" id="manager-details-vertical-trigger">
                                            <button type="button" class="step-trigger">
                                                <span class="bs-stepper-box">1</span>
                                                <span class="bs-stepper-label">
                                                    <span class="bs-stepper-title">Manager Live API</span>
                                                    <span class="bs-stepper-subtitle">Setup Manager API Details</span>
                                                </span>
                                            </button>
                                        </div>
                                        <div class="step" data-target="#manager-details-vertical" role="tab" id="manager-demo-details-vertical-trigger">
                                            <button type="button" class="step-trigger">
                                                <span class="bs-stepper-box">2</span>
                                                <span class="bs-stepper-label">
                                                    <span class="bs-stepper-title">Manager Demo API</span>
                                                    <span class="bs-stepper-subtitle">Setup Manager API Details</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="bs-stepper-content shadow-none">
                                        <!-- Live server form start -->
                                        <div id="manager-details-vertical" class="content" role="tabpanel" aria-labelledby="manager-details-vertical-trigger">
                                            <div class="content-header">
                                                <h5 class="mb-0">Manager Details Live API</h5>
                                                <small class="text-muted">Enter Your Manager Details.</small>
                                            </div>
                                            <!-- ------------------------------------------------------------------------
                                                |                       Manager Live Details Form
                                             ----------------------------------------------------------------------------->
                                            <form action="{{route('system.configarations.mt5-manager-api-config')}}" method="POST" id="manager_api_form">
                                                @csrf
                                                <div class="row">
                                                    <input type="hidden" name="platform_type" value="mt5">
                                                    <input type="hidden" name="server_type" value="manager-api">
                                                    <!-- Server API -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <label class="form-label" for="mt5_server_ip">Server IP</label>
                                                        <input type="text" class="form-control" id="mt5_server_ip" name="mt5_server_ip" value="<?php echo (isset($manager_api->server_ip) ? $manager_api->server_ip : ''); ?>" />
                                                        <span class="text-danger" id="mt5_server_ip_error"></span>
                                                    </div>
                                                    <!-- Server port -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <label class="form-label" for="mt5_server_port">Server Port</label>
                                                        <input type="text" class="form-control" id="mt5_server_port" name="mt5_server_port" value="<?php echo (isset($manager_api->server_port) ? $manager_api->server_port : ''); ?>" />
                                                        <span class="text-danger" id="mt5_server_port_error"></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <!-- API url -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <label class="form-label" for="mt5_api_url">API URL</label>
                                                        <input type="text" class="form-control" id="mt5_api_url" name="mt5_api_url" value="<?php echo (isset($manager_api->api_url) ? $manager_api->api_url : ''); ?>" />
                                                        <span class="text-danger" id="mt5_api_url_error"></span>
                                                    </div>
                                                    <!-- Manager Login -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <label class="form-label" for="mt5_manager_login">Manager Login</label>
                                                        <input type="text" class="form-control" id="mt5_manager_login" name="mt5_manager_login" value="<?php echo (isset($manager_api->manager_login) ? $manager_api->manager_login : ''); ?>" />
                                                        <span class="text-danger" id="mt5_manager_login_error"></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <!-- Manager password -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <label class="form-label" for="mt5_manager_password">Manager Password</label>
                                                        <input type="password" class="form-control" id="mt5_manager_password" name="mt5_manager_password" value="<?php echo (isset($manager_api->manager_password) ? $manager_api->manager_password : ''); ?>" />
                                                        <span class="text-danger" id="mt5_manager_password_error"></span>
                                                    </div>
                                                    <!-- Active/Disable Status -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <h6 class="fw-bolder mb-75">
                                                            <label class="form-check-label mb-50" for="remaining-com">Active/Disable</label>
                                                        </h6>
                                                        <div class="d-flex flex-column">
                                                            <div class="form-check form-check-success form-switch">
                                                                <input type="checkbox" checked="" name="status" class="form-check-input" id="remaining-com" value="1" <?php echo ((isset($manager_api->status) === '1') ?  'checked="checked"' : '') ?>>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <button class="btn btn-outline-secondary btn-prev" disabled>
                                                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                    </button>
                                                    <button class="btn btn-primary" type="button" id="manager_api_submit_btn" data-btnid="manager_api_submit_btn" data-form="manager_api_form" onclick="_run(this)" data-callback="manager_api_callback" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>">
                                                        <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                                        <span class="align-middle d-sm-inline-block d-none">Save</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- //End manager api form -->
                                        <!-- Live server form start -->
                                        <div id="manager-demo-details-vertical" class="content" role="tabpanel" aria-labelledby="manager-details-vertical-trigger">
                                            <div class="content-header">
                                                <h5 class="mb-0">Manager Details Demo API</h5>
                                                <small class="text-muted">Enter Your Manager Details.</small>
                                            </div>
                                            <!-- ------------------------------------------------------------------------
                                                |                       Manager Demo API Details Form
                                             ----------------------------------------------------------------------------->
                                            <form action="{{route('system.configarations.mt5-manager-api-config')}}" method="POST" id="manager_api_form">
                                                @csrf
                                                <div class="row">
                                                    <input type="hidden" name="platform_type" value="mt5">
                                                    <input type="hidden" name="server_type" value="manager-api">
                                                    <!-- Server API -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <label class="form-label" for="mt5_server_ip">Server IP</label>
                                                        <input type="text" class="form-control" id="mt5_server_ip" name="mt5_server_ip" value="<?php echo (isset($manager_api->server_ip) ? $manager_api->server_ip : ''); ?>" />
                                                        <span class="text-danger" id="mt5_server_ip_error"></span>
                                                    </div>
                                                    <!-- Server port -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <label class="form-label" for="mt5_server_port">Server Port</label>
                                                        <input type="text" class="form-control" id="mt5_server_port" name="mt5_server_port" value="<?php echo (isset($manager_api->server_port) ? $manager_api->server_port : ''); ?>" />
                                                        <span class="text-danger" id="mt5_server_port_error"></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <!-- API url -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <label class="form-label" for="mt5_api_url">API URL</label>
                                                        <input type="text" class="form-control" id="mt5_api_url" name="mt5_api_url" value="<?php echo (isset($manager_api->api_url) ? $manager_api->api_url : ''); ?>" />
                                                        <span class="text-danger" id="mt5_api_url_error"></span>
                                                    </div>
                                                    <!-- Manager Login -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <label class="form-label" for="mt5_manager_login">Manager Login</label>
                                                        <input type="text" class="form-control" id="mt5_manager_login" name="mt5_manager_login" value="<?php echo (isset($manager_api->manager_login) ? $manager_api->manager_login : ''); ?>" />
                                                        <span class="text-danger" id="mt5_manager_login_error"></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <!-- Manager password -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <label class="form-label" for="mt5_manager_password">Manager Password</label>
                                                        <input type="password" class="form-control" id="mt5_manager_password" name="mt5_manager_password" value="<?php echo (isset($manager_api->manager_password) ? $manager_api->manager_password : ''); ?>" />
                                                        <span class="text-danger" id="mt5_manager_password_error"></span>
                                                    </div>
                                                    <!-- Active/Disable Status -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <h6 class="fw-bolder mb-75">
                                                            <label class="form-check-label mb-50" for="remaining-com">Active/Disable</label>
                                                        </h6>
                                                        <div class="d-flex flex-column">
                                                            <div class="form-check form-check-success form-switch">
                                                                <input type="checkbox" checked="" name="status" class="form-check-input" id="remaining-com" value="1" <?php echo ((isset($manager_api->status) === '1') ?  'checked="checked"' : '') ?>>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <button class="btn btn-outline-secondary" disabled>
                                                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                    </button>
                                                    <button class="btn btn-primary" type="button" id="manager_api_submit_btn" data-btnid="manager_api_submit_btn" data-form="manager_api_form" onclick="_run(this)" data-callback="manager_api_callback" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>">
                                                        <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                                        <span class="align-middle d-sm-inline-block d-none">Save</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- //End manager demo api form -->

                                    </div>
                                </div>
                            </section>
                            <!-- //End Vertical Wizard -->
                            <!-- ---------------------------------------------------------------------------------------------------------------------------
                                                                                ||Web API
                             ------------------------------------------------------------------------------------------------------------------------------>
                            <section class="vertical-wizard" id="web_wizard">
                                <div class="bs-stepper vertical vertical-wizard-example shadow-none" id="manager_wizard">
                                    <div class="bs-stepper-header border-end">
                                        <div class="step" data-target="#web-info-vertical-trigger" role="tab" id="web-info-vertical-trigger">
                                            <button type="button" class="step-trigger">
                                                <span class="bs-stepper-box">1</span>
                                                <span class="bs-stepper-label">
                                                    <span class="bs-stepper-title">Web Live API</span>
                                                    <span class="bs-stepper-subtitle">Setup Web API Details</span>
                                                </span>
                                            </button>
                                        </div>
                                        <div class="step" data-target="#web-demo-info-vertical-trigger" role="tab" id="web-demo-info-vertical-trigger">
                                            <button type="button" class="step-trigger">
                                                <span class="bs-stepper-box">2</span>
                                                <span class="bs-stepper-label">
                                                    <span class="bs-stepper-title">Web Demo API</span>
                                                    <span class="bs-stepper-subtitle">Setup Web API Details</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="bs-stepper-content shadow-none">
                                        <!-- Live server form start -->

                                        <!-- Web App server form start -->
                                        <div id="web-info-vertical" class="content shadow-none" role="tabpanel" aria-labelledby="web-info-vertical-trigger">
                                            <div class="content-header">
                                                <h5 class="mb-0">Web Live API Details</h5>
                                                <small>Enter Your Web Api Details</small>
                                            </div>
                                            <!-- ------------------------------------------------------------------------
                                                |                       Web App Form
                                            ----------------------------------------------------------------------------->
                                            <form action="{{route('system.configarations.mt5-web-api-config')}}" method="post" id="web_api_form">
                                                @csrf
                                                <div class="row">
                                                    <input type="hidden" name="platform_type" value="mt5">
                                                    <input type="hidden" name="server_type" value="web-api">
                                                    <!-- Server API -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <label class="form-label" for="mt5_server_ip">Server IP</label>
                                                        <input type="text" class="form-control" id="mt5_server_ip" name="mt5_server_ip" value="<?php echo (isset($web_api->server_ip) ? $web_api->server_ip : '') ?>" />
                                                        <span class="text-danger" id="mt5_server_ip_error"></span>
                                                    </div>
                                                    <!-- Server port -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <label class="form-label" for="mt5_server_port">Server Port</label>
                                                        <input type="text" class="form-control" id="mt5_server_port" name="mt5_server_port" value="<?php echo (isset($web_api->server_port) ? $web_api->server_port : '') ?>" />
                                                        <span class="text-danger" id="mt5_server_port_error"></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <!-- API url -->
                                                    <div class="mb-1 col-md-6">
                                                        <label class="form-label" for="mt5_api_url">API URL</label>
                                                        <input type="text" class="form-control" id="mt5_api_url" name="mt5_api_url" value="<?php echo (isset($web_api->api_url) ? $web_api->api_url : '') ?>" />
                                                        <span class="text-danger" id="mt5_api_url_error"></span>
                                                    </div>
                                                    <!-- Web password -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <label class="form-label" for="mt5_web_password">Web Password</label>
                                                        <input type="password" class="form-control" id="mt5_web_password" name="mt5_web_password" value="<?php echo (isset($web_api->web_password) ? $web_api->web_password : '') ?>" />
                                                        <span class="text-danger" id="mt5_web_password_error"></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <!-- Manager Login -->
                                                    <div class="mb-1 form-password-toggle col-md-4">
                                                        <label class="form-label" for="mt5_manager_login">Manager Login</label>
                                                        <input type="text" class="form-control" id="mt5_manager_login" name="mt5_manager_login" value="<?php echo (isset($web_api->manager_login) ? $web_api->manager_login : '') ?>" />
                                                        <span class="text-danger" id="mt5_manager_login_error"></span>
                                                    </div>
                                                    <!-- Manager password -->
                                                    <div class="mb-1 form-password-toggle col-md-4">
                                                        <label class="form-label" for="mt5_manager_password">Manager Password</label>
                                                        <input type="password" class="form-control" id="mt5_manager_password" name="mt5_manager_password" value="<?php echo (isset($web_api->manager_password) ? $web_api->manager_password : '') ?>" />
                                                        <span class="text-danger" id="mt5_manager_password_error"></span>
                                                    </div>
                                                    <!-- Active/Disable Status -->
                                                    <div class="mb-1 form-password-toggle col-md-4">
                                                        <h6 class="fw-bolder mb-75">
                                                            <label class="form-check-label mb-50" for="remaining-com">Active/Disable</label>
                                                        </h6>
                                                        <div class="d-flex flex-column">
                                                            <div class="form-check form-check-success form-switch">
                                                                <input type="checkbox" checked="" name="status" value="1" class="form-check-input" id="remaining-com" <?php echo (isset($web_api->status) == '1') ? 'checked="checked"' : '' ?>>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <button class="btn btn-outline-secondary btn-prev" disabled>
                                                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                    </button>
                                                    <button class="btn btn-primary" type="button" id="web_api_submit_btn" data-btnid="web_api_submit_btn" data-form="web_api_form" onclick="_run(this)" data-callback="web_api_callback" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>">
                                                        <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                                        <span class="align-middle d-sm-inline-block d-none">Save</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- End demo server form -->
                                        <!-- ------------------------------------------------------------------------
                                                |                       Web Demo API Form
                                        ----------------------------------------------------------------------------->
                                        <!-- Web App server form start -->
                                        <div id="web-demo-info-vertical" class="content shadow-none" role="tabpanel" aria-labelledby="web-info-vertical-trigger">
                                            <div class="content-header">
                                                <h5 class="mb-0">Web Demo API Details</h5>
                                                <small>Enter Your Web Api Details</small>
                                            </div>

                                            <form action="{{route('system.configarations.mt5-web-api-config')}}" method="post" id="web_api_form">
                                                @csrf
                                                <div class="row">
                                                    <input type="hidden" name="platform_type" value="mt5">
                                                    <input type="hidden" name="server_type" value="web-api">
                                                    <!-- Server API -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <label class="form-label" for="mt5_server_ip">Server IP</label>
                                                        <input type="text" class="form-control" id="mt5_server_ip" name="mt5_server_ip" value="<?php echo (isset($web_api->server_ip) ? $web_api->server_ip : '') ?>" />
                                                        <span class="text-danger" id="mt5_server_ip_error"></span>
                                                    </div>
                                                    <!-- Server port -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <label class="form-label" for="mt5_server_port">Server Port</label>
                                                        <input type="text" class="form-control" id="mt5_server_port" name="mt5_server_port" value="<?php echo (isset($web_api->server_port) ? $web_api->server_port : '') ?>" />
                                                        <span class="text-danger" id="mt5_server_port_error"></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <!-- API url -->
                                                    <div class="mb-1 col-md-6">
                                                        <label class="form-label" for="mt5_api_url">API URL</label>
                                                        <input type="text" class="form-control" id="mt5_api_url" name="mt5_api_url" value="<?php echo (isset($web_api->api_url) ? $web_api->api_url : '') ?>" />
                                                        <span class="text-danger" id="mt5_api_url_error"></span>
                                                    </div>
                                                    <!-- Web password -->
                                                    <div class="mb-1 form-password-toggle col-md-6">
                                                        <label class="form-label" for="mt5_web_password">Web Password</label>
                                                        <input type="password" class="form-control" id="mt5_web_password" name="mt5_web_password" value="<?php echo (isset($web_api->web_password) ? $web_api->web_password : '') ?>" />
                                                        <span class="text-danger" id="mt5_web_password_error"></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <!-- Manager Login -->
                                                    <div class="mb-1 form-password-toggle col-md-4">
                                                        <label class="form-label" for="mt5_manager_login">Manager Login</label>
                                                        <input type="text" class="form-control" id="mt5_manager_login" name="mt5_manager_login" value="<?php echo (isset($web_api->manager_login) ? $web_api->manager_login : '') ?>" />
                                                        <span class="text-danger" id="mt5_manager_login_error"></span>
                                                    </div>
                                                    <!-- Manager password -->
                                                    <div class="mb-1 form-password-toggle col-md-4">
                                                        <label class="form-label" for="mt5_manager_password">Manager Password</label>
                                                        <input type="password" class="form-control" id="mt5_manager_password" name="mt5_manager_password" value="<?php echo (isset($web_api->manager_password) ? $web_api->manager_password : '') ?>" />
                                                        <span class="text-danger" id="mt5_manager_password_error"></span>
                                                    </div>
                                                    <!-- Active/Disable Status -->
                                                    <div class="mb-1 form-password-toggle col-md-4">
                                                        <h6 class="fw-bolder mb-75">
                                                            <label class="form-check-label mb-50" for="remaining-com">Active/Disable</label>
                                                        </h6>
                                                        <div class="d-flex flex-column">
                                                            <div class="form-check form-check-success form-switch">
                                                                <input type="checkbox" checked="" name="status" value="1" class="form-check-input" id="remaining-com" <?php echo (isset($web_api->status) == '1') ? 'checked="checked"' : '' ?>>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <button class="btn btn-outline-secondary btn-prev" disabled>
                                                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                    </button>
                                                    <button class="btn btn-primary" type="button" id="web_api_submit_btn" data-btnid="web_api_submit_btn" data-form="web_api_form" onclick="_run(this)" data-callback="web_api_callback" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>">
                                                        <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                                        <span class="align-middle d-sm-inline-block d-none">Save</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- End demo server form -->

                                    </div>
                                </div>
                            </section>
                        </div>
                        <!-- 3rd step/ crm version -->
                        <div id="address-step-modern" class="content" role="tabpanel" aria-labelledby="address-step-modern-trigger">
                            <div class="content-header">
                                <h5 class="mb-0">Vertex API Configuration</h5>
                                <small class="text-muted">Enter your vertex API info.</small>
                            </div>
                            <div class="row">
                                <!--mt4 server type -->
                                <div class="col-12 col-sm-6 mb-1" id="vertex_server_type">
                                    <label class="form-label" for="vertex_server_type">*Vertex Server Type</label>
                                    <select class="select2 form-select vertex_server_type" name="vertex_server_type">
                                        <option value="">Select Server Type</option>
                                        <option value="Demo" <?php echo ((isset($server_type->vertex_server_type) && strtolower($server_type->vertex_server_type) === 'demo') ?  'selected="selected"' : '') ?>>Demo</option>
                                        <option value="Live" <?php echo ((isset($server_type->vertex_server_type) && strtolower($server_type->vertex_server_type) === 'live') ?  'selected="selected"' : '') ?>>Live</option>
                                    </select>
                                    <span class="text-danger" id="vertex_server_type_error"></span>
                                </div>
                                <!-- api url -->
                                <div class="col-12 col-sm-6 mb-1 vertex_server_type_config">
                                    <label class="form-label" for="vertex_api_url">API URL</label>
                                    <input type="text" class="form-control" id="vertex_api_url" name="vertex_api_url" placeholder="vertex_api_url" value="<?php echo (isset($api_url->vertex_api_url) ? $api_url->vertex_api_url : ''); ?>" />
                                    <span class="text-danger" id="vertex_api_url_error"></span>
                                </div>
                                <!-- server api -->
                                <div class="col-12 col-sm-6 mb-1 vertex_server_type_config">
                                    <label class="form-label" for="vertex_api_key">API Key</label>
                                    <input type="password" class="form-control" id="vertex_api_key" name="vertex_api_key" placeholder="vertex_api_key" value="<?php echo (isset($api_key->vertex_api_key) ? $api_key->vertex_api_key : ''); ?>" />
                                    <span class="text-danger" id="vertex_api_key_error"></span>
                                </div>
                                <!-- server ip -->
                                <div class="col-12 col-sm-6 mb-1 vertex_server_type_config">
                                    <label class="form-label" for="vertex_server_ip">Server IP</label>
                                    <input type="text" class="form-control" id="vertex_server_ip" name="vertex_server_ip" placeholder="vertex_server_ip" value="<?php echo (isset($server_ip->vertex_server_ip) ? $server_ip->vertex_server_ip : ''); ?>" />
                                    <span class="text-danger" id="vertex_server_ip_error"></span>
                                </div>
                                <!-- server port -->
                                <div class="col-12 col-sm-6 mb-1 vertex_server_type_config">
                                    <label class="form-label" for="vertex_server_port">Server Port</label>
                                    <input type="text" class="form-control" id="vertex_server_port" name="vertex_server_port" placeholder="vertex_server_port" value="<?php echo (isset($server_port->vertex_server_port) ? $server_port->vertex_server_port : ''); ?>" />
                                    <span class="text-danger" id="vertex_server_port_error"></span>
                                </div>
                                <!-- web password -->
                                <div class="col-12 col-sm-6 mb-1 vertex_server_type_config">
                                    <label class="form-label" for="vertex_web_password">Web Password</label>
                                    <input type="password" class="form-control" id="vertex_web_password" name="vertex_web_password" placeholder="vertex_web_password" value="<?php echo (isset($web_password->vertex_web_password) ? $web_password->vertex_web_password : ''); ?>" />
                                    <span class="text-danger" id="vertex_web_password_error"></span>
                                </div>
                                <!-- manager login -->
                                <div class="col-12 col-sm-6 mb-1 vertex_server_type_config">
                                    <label class="form-label" for="vertex_manager_login">Manager Login</label>
                                    <input type="text" class="form-control" id="vertex_manager_login" name="vertex_manager_login" placeholder="vertex_manager_login" value="<?php echo (isset($manager_login->vertex_manager_login) ? $manager_login->vertex_manager_login : ''); ?>" />
                                    <span class="text-danger" id="vertex_manager_login_error"></span>
                                </div>
                                <!-- manager password -->
                                <div class="col-12 col-sm-6 mb-1 vertex_server_type_config">
                                    <label class="form-label" for="vertex_manager_password">Manager Password</label>
                                    <input type="password" class="form-control" id="vertex_manager_password" name="vertex_manager_password" placeholder="vertex_manager_password" value="<?php echo (isset($manager_password->vertex_manager_password) ? $manager_password->vertex_manager_password : ''); ?>" />
                                    <span class="text-danger" id="vertex_manager_password_error"></span>
                                </div>
                            </div>
                            <!-- 3rd step buttons -->
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-primary btn-prev" type="button">
                                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-primary btn-next" type="button">
                                    <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Save</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /Modern Horizontal Wizard -->
        </div>
    </div>
</div>
@stop
<!-- BEGIN: vendor JS -->

<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- toaster js -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/system-page-configuration.js')}}"></script>
<!-- form hide/show -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-config-form.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js')}}"></script>
<script>
    $(document).ready(function() {
        $("#account-details-vertical-trigger").addClass('active');
        $(".live-server-details").addClass('dstepper-block');
        $(".live-server-details").css({
            "display": "block",
            "visibility": "visible",
        });

        // Live server Button 
        $(".live-server-btn").click(function() {
            $(".live-server-details").css({
                "display": "block",
                "visibility": "visible",
            });
            $(".demo-server-details").css({
                "display": "none",
            });
        });

        // Demo server Button
        $(".demo-server-btn").click(function() {
            $(".live-server-btn").removeClass('active');
            $(".live-server-details").removeClass('dstepper-block');

            $(".live-server-details").css({
                "display": "none",
                "visibility": "hidden",
            });

            $(".demo-server-details").css({
                "display": "block",
                "visibilty": "visible",
            });
        });

        // ************  MT5 Button 
        $(".mt5").click(function() {
            $("#manager-details-vertical-trigger").addClass('active');
            $("#web-info-vertical-trigger").removeClass('active');
            $("#web-demo-info-vertical-trigger").removeClass('active');
            $("#manager-demo-details-vertical-trigger").removeClass('active');
            $("#manager-demo-details-vertical").hide();
            $("#manager-details-vertical").css({
                "display": "block",
                "visibility": "visible"
            });
            $("#web-info-vertical").css({
                "display": "none",
                "visibility": "hidden"
            });
            $("#web-demo-info-vertical").css({
                "display": "none",
                "visibility": "hidden"
            });

            $("#web_wizard").css({
                "display": "none",
                "visibility": "hidden"
            });
        });

        $("#web-info-vertical-trigger").click(function() {
            $(this).addClass('active');
            $("#manager-details-vertical-trigger").removeClass('active');
            $("#manager-demo-details-vertical-trigger").removeClass('active');
            $("#web-demo-info-vertical-trigger").removeClass('active');
            $("#web-info-vertical").css({
                "display": "block",
                "visibility": "visible"
            });
            $("#web-demo-info-vertical").css({
                "display": "none",
                "visibility": "hidden"
            });
            $("#manager-details-vertical").hide();
            $("#manager-demo-details-vertical").hide();
        });
        //---------------------------Demo web api--------------------------->
        $("#web-demo-info-vertical-trigger").click(function() {
            $(this).addClass('active');
            $("#manager-details-vertical-trigger").removeClass('active');
            $("#manager-demo-details-vertical-trigger").removeClass('active');
            $("#web-info-vertical-trigger").removeClass('active');
            $("#web-demo-info-vertical").css({
                "display": "block",
                "visibility": "visible"
            });
            $("#web-info-vertical").css({
                "display": "none",
                "visibility": "hidden"
            });
            $("#manager-details-vertical").hide();
            $("#manager-demo-details-vertical").hide();
        });

        //--------------------------Manager Live api ----------------------->
        $("#manager-details-vertical-trigger").click(function() {
            $(this).addClass('active');
            $("#web-info-vertical-trigger").removeClass('active');
            $("#manager-demo-details-vertical-trigger").removeClass('active');
            $("#web-demo-info-vertical-trigger").removeClass('active');
            $("#manager-details-vertical").css({
                "display": "block",
                "visibility": "visible"
            });
            $("#manager-demo-details-vertical").hide();
            $("#web-info-vertical").css({
                "display": "none",
                "visibility": "hidden"
            });
            $("#web-demo-info-vertical").css({
                "display": "none",
                "visibility": "hidden"
            });
        });
        // -------------------------Manager Demo api--------------------->
        $("#manager-demo-details-vertical-trigger").click(function() {
            $(this).addClass('active');
            $("#manager-details-vertical-trigger").removeClass('active');
            $("#web-info-vertical-trigger").removeClass('active');
            $("#web-demo-info-vertical-trigger").removeClass('active');
            $("#manager-demo-details-vertical").css({
                "display": "block",
                "visibility": "visible"
            });
            $("#manager-details-vertical").hide();
            $("#web-info-vertical").css({
                "display": "none",
                "visibility": "hidden"
            });

        });

        // -----------------------------------------------------------------------
        //                      Manager/Web Selection Button
        // -----------------------------------------------------------------------

        $("#manager_wizard").show();
        $("#web_wizard").show();

        $("#manage_web_selection_btn").change(function() {
            if ($(this).is(":checked")) {
                $("#manager_wizard").show();
                $("#web_wizard").css({
                    "display": "none",
                    "visibility": "hidden"
                });
            } else {
                $("#web-info-vertical-trigger").addClass('active');
                $("#web-info-vertical-trigger").show();
                $("#web_wizard").show();
                $("#manager_wizard").hide();
                $("#web-info-vertical").css({
                    "display": "block",
                    "visibility": "visible"
                });
                $("#web_wizard").css({
                    "display": "block",
                    "visibility": "visible"
                });

                $('.vertical-wizard').css({
                    "margin-bottom" : "0px"
                });
            }
        });

    });
    // live api mt4 callback
    function live_api_mt4_callback(data) {
        if (data.status) {
            notify('success', data.message, 'MT4 API Configuration');
        } else {
            notify('error', data.message, 'MT4 API Configuration');
        }
        $.validator("live-api-form-mt4", data.errors);
    }

    //Demo api mt4 callback
    function demo_api_mt4_callback(data) {
        if (data.status) {
            notify('success', data.message, 'MT4 API Configuration');
        } else {
            notify('error', data.message, 'MT4 API Configuration');
        }
        $.validator("demo_api_form", data.errors);
    }

    //Manager api mt5 callback function
    function manager_api_callback(data) {
        if (data.status) {
            notify('success', data.message, 'MT5 API Configuration');
        } else {
            notify('error', data.message, 'MT5 API Configuration')
        }
        $.validator("manager_api_form", data.errors);
    }

    //Web app callback function
    function web_api_callback(data) {
        if (data.status) {
            notify('success', data.message, 'MT5 WEB API Configuration')
        } else {
            notify('error', data.message, 'MT5 WEB API Configuration')
        }

        $.validator("web_api_form", data.errors);
    }
</script>
<script>

</script>
@stop
<!-- BEGIN: page JS -->