@extends('layouts.system-layout')
@section('title','System Configuration')
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
                        <h2 class="content-header-title float-start mb-0">Configuration</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Software Settings </a>
                                </li>
                                <li class="breadcrumb-item active"> Configuration
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
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-pills mb-2">
                        <!-- theme setup -->
                        <li class="nav-item">
                            <a class="nav-link active" id="theme-setup">
                                <i data-feather="settings" class="font-medium-3 me-50"></i>
                                <span class="fw-bold">Theme Setup</span>
                            </a>
                        </li>
                        <!-- api configuration -->
                        <li class="nav-item">
                            <a class="nav-link" id="api-configuration">
                                <i data-feather="code" class="font-medium-3 me-50"></i>
                                <span class="fw-bold">API Configuration</span>
                            </a>
                        </li>
                        <!-- smtp setup -->
                        <li class="nav-item">
                            <a class="nav-link" id="smtp-setup">
                                <i data-feather="settings" class="font-medium-3 me-50"></i>
                                <span class="fw-bold">SMTP Setup</span>
                            </a>
                        </li>
                        <!-- conpany info -->
                        <li class="nav-item">
                            <a class="nav-link" id="company-info">
                                <i data-feather="settings" class="font-medium-3 me-50"></i>
                                <span class="fw-bold">Company Setup</span>
                            </a>
                        </li>
                        <!-- finance settings -->
                        <li class="nav-item">
                            <a class="nav-link finance-setting" id="finance-setting">
                                <i data-feather="settings" class="font-medium-3 me-50"></i>
                                <span class="fw-bold">Finance Settings</span>
                            </a>
                        </li>
                        <!-- Software settings -->
                        <li class="nav-item">
                            <a class="nav-link software-setting" id="software-setting">
                                <i data-feather="settings" class="font-medium-3 me-50"></i>
                                <span class="fw-bold">Software Settings</span>
                            </a>
                        </li>
                    </ul>
                    <!-- profile -->
                    <div class="card">
                        <div class="card-header border-bottom">
                            <div class="card my-0 py-0">
                                <div class="card-body my-0 py-0">
                                    <h4 class="card-title">Software Configurations</h4>
                                </div>
                            </div>
                        </div>
                        <!--start theme setup form -->
                        <form action="{{route('system.theme_setup')}}" class="mt-2 pt-50" method="POST" enctype="multipart/form-data" id="theme-setup-form">
                            @csrf
                            <div class="card-body py-2 my-25">
                                <!-- header section -->
                                <div class="card my-0 py-0">
                                    <div class="card-body my-0 py-0">
                                        <div class="row">
                                            <!-- BEGIN: Dark logo -->
                                            <div class="col-lg-6 mb-3">
                                                <div class="d-flex">
                                                    <!-- upload and reset button -->
                                                    <div class="d-flex align-items-end mt-75">
                                                        <div class="w-100">
                                                            <a href="#" class="me-25 w-100">
                                                                <img src="{{get_light_logo()}}" id="dark-logo-img" class="upload-dark-logo rounded me-50 img img-fluid" alt="profile image" height="100" width="100" />
                                                            </a>
                                                            <div class="w-100 pt-1 pb-1">
                                                                <label for="dark-logo-upload" class="btn btn-sm btn-primary">Logo for light layout</label>
                                                                <input type="file" id="dark-logo-upload" hidden accept="image/*" name="dark_logo" />
                                                            </div>
                                                            <button type="button" id="dark-logo-reset" class="btn btn-sm btn-outline-secondary mb-75">Reset</button>
                                                            <p class="mb-0">Allowed file types: png, jpg, jpeg.</p>
                                                        </div>
                                                    </div>
                                                    <!--/ upload and reset button -->
                                                </div>
                                            </div>
                                            <!-- END: Dark logo -->
                                            <!-- BEGIN: Light logo -->
                                            <div class="col-lg-6 mb-3">
                                                <div class="d-flex">
                                                    <!-- upload and reset button -->
                                                    <div class="d-flex align-items-end mt-75 ms-1">
                                                        <div class="w-100">
                                                            <a href="#" class="me-25 w-100">
                                                                <img src="{{get_dark_logo()}}" id="account-upload-img" class="uploadedAvatar rounded me-50 img img-fluid" alt="profile image" height="100" width="100" />
                                                            </a>
                                                            <div class="w-100 pt-1 pb-1">
                                                                <label for="account-upload" class="btn btn-sm btn-primary">Logo for dark layout</label>
                                                                <input type="file" id="account-upload" hidden accept="image/*" name="light_logo" />
                                                            </div>
                                                            <button type="button" id="account-reset" class="btn btn-sm btn-outline-secondary mb-75">Reset</button>
                                                            <p class="mb-0">Allowed file types: png, jpg, jpeg.</p>
                                                        </div>
                                                    </div>
                                                    <!--/ upload and reset button -->
                                                </div>
                                            </div>
                                            <!-- END: Light logo -->
                                        </div>
                                    </div>
                                </div>
                                <!--/ header section -->
                                <!-- Set themes -->
                                <div class="row">
                                    <!-- set user theme -->
                                    <div class="col-lg-6 p-0">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Set User Theme Layout</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="row custom-options-checkable g-1">
                                                    <div class="col-md-6">
                                                        <input class="custom-option-item-check" type="radio" name="user_theme" id="user-theme-light" value="light-layout" <?php echo checked_user_theme('light-layout') ?> />
                                                        <label class="custom-option-item text-center p-1" for="user-theme-light">
                                                            <i data-feather="sun" class="font-large-1 mb-75"></i>
                                                            <span class="custom-option-item-title h4 d-block">Light Theme</span>
                                                            <small>Set background color light and text color black.</small>
                                                        </label>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <input class="custom-option-item-check" type="radio" name="user_theme" id="user-theme-dark" value="dark-layout" <?php echo checked_user_theme('dark-layout') ?> />
                                                        <label class="custom-option-item text-center text-center p-1 bg-theme-dark" for="user-theme-dark">
                                                            <i data-feather="moon" class="font-large-1 mb-75"></i>
                                                            <span class="custom-option-item-title h4 d-block">Dark Theme</span>
                                                            <small>Set background color dark and text color light.</small>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input class="custom-option-item-check" type="radio" name="user_theme" id="user-theme-semi-dark" value="semi-dark-layout" <?php echo checked_user_theme('semi-dark-layout') ?> />
                                                        <label class="custom-option-item text-center p-1 position-relative" for="user-theme-semi-dark">
                                                            <span class="semi-dark-overlay">
                                                                <span class="top-triangle"></span>
                                                                <span class="top-triangle"></span>
                                                            </span>
                                                            <span class="semi-dark-content">
                                                                <i data-feather="moon" class="font-large-1 mb-75"></i>
                                                                <span class="custom-option-item-title h4 d-block">Semi Dark Theme</span>
                                                                <small>Set background color semi dark and text color black and light.</small>
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input class="custom-option-item-check" type="radio" name="user_theme" id="user-theme-border" value="bordered-layout" <?php echo checked_user_theme('bordered-layout') ?> />
                                                        <label class="custom-option-item text-center p-1 position-relative" for="user-theme-border">
                                                            <i data-feather="sun" class="font-large-1 mb-75"></i>
                                                            <span class="custom-option-item-title h4 d-block">Bordered Theme</span>
                                                            <small>Set background color light and borderd and text color black.</small>
                                                            <span class="border-theme-overlay border"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- set  admin theme-->
                                    <div class="col-lg-6 p-0">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Set Admin Theme Layout</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="row custom-options-checkable g-1">
                                                    <div class="col-md-6">
                                                        <input class="custom-option-item-check" type="radio" name="admin_theme" id="admin-light-theme" value="light-layout" <?php echo checked_admin_theme('light-layout') ?> />
                                                        <label class="custom-option-item text-center p-1" for="admin-light-theme">
                                                            <i data-feather="sun" class="font-large-1 mb-75"></i>
                                                            <span class="custom-option-item-title h4 d-block">Light Theme</span>
                                                            <small>Set background color light and text color black.</small>
                                                        </label>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <input class="custom-option-item-check" type="radio" name="admin_theme" id="admin-dark-theme" value="dark-layout" <?php echo checked_admin_theme('dark-layout') ?> />
                                                        <label class="custom-option-item text-center text-center p-1 bg-theme-dark" for="admin-dark-theme">
                                                            <i data-feather="moon" class="font-large-1 mb-75"></i>
                                                            <span class="custom-option-item-title h4 d-block">Dark Theme</span>
                                                            <small>Set background color dark and text color light.</small>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input class="custom-option-item-check" type="radio" name="admin_theme" id="admin-semi-dark-theme" value="semi-dark-layout" <?php echo checked_admin_theme('semi-dark-layout') ?> />
                                                        <label class="custom-option-item text-center p-1 position-relative" for="admin-semi-dark-theme">
                                                            <span class="semi-dark-overlay">
                                                                <span class="top-triangle"></span>
                                                                <span class="top-triangle"></span>
                                                            </span>
                                                            <span class="semi-dark-content">
                                                                <i data-feather="moon" class="font-large-1 mb-75"></i>
                                                                <span class="custom-option-item-title h4 d-block">Semi Dark Theme</span>
                                                                <small>Set background color semi dark and text color black and light.</small>
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input class="custom-option-item-check" type="radio" name="admin_theme" id="admin-bordered-theme" value="bordered-layout" <?php echo checked_admin_theme('bordered-layout') ?> />
                                                        <label class="custom-option-item text-center p-1 position-relative" for="admin-bordered-theme">
                                                            <i data-feather="sun" class="font-large-1 mb-75"></i>
                                                            <span class="custom-option-item-title h4 d-block">Bordered Theme</span>
                                                            <small>Set background color light and borderd and text color black.</small>
                                                            <span class="border-theme-overlay border"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row custom-options-checkable g-1">
                                                    <div class="col-12 mt-0 pt-0">
                                                        <button type="submit" class="btn btn-primary me-1 mb-1" id="btn-theme-setup">Save changes</button>
                                                        <button type="reset" class="btn btn-outline-secondary mb-1">Discard</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- / set themes -->
                            </div>
                        </form>
                        <!--end theme setup form -->

                        <!--start API configuration form -->
                        <form action="{{route('system.api_configuration')}}" class="mt-2 pt-50" method="POST" enctype="multipart/form-data" id="api-configuration-form">
                            @csrf
                            <div class="card-body py-2 my-25">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- platform type -->
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="platform_type">Platform Type</label>
                                                <select id="platform_type" class="select2 form-select" name="platform_type">
                                                    <option value="MT4" <?php echo ((isset($configs->platform_type) && strtolower($configs->platform_type) === 'mt4') ?  'selected="selected"' : '') ?>>MT4</option>
                                                    <option value="MT5" <?php echo ((isset($configs->platform_type) && strtolower($configs->platform_type) === 'mt5') ?  'selected="selected"' : '') ?>>MT5</option>
                                                    <option value="Both" <?php echo ((isset($configs->platform_type) && strtolower($configs->platform_type) === 'both') ?  'selected="selected"' : '') ?>>Both</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1"></div>
                                            <!--mt4 server type -->
                                            <div class="col-12 col-sm-6 mb-1" id="mt4_server_type">
                                                <label class="form-label" for="mt4_server_type">*MT4 Server Type</label>
                                                <select class="select2 form-select mt4_server_type" name="mt4_server_type">
                                                    <option value="">Select Server Type</option>
                                                    <option value="Manager API" <?php echo ((isset($server_type->mt4_server_type) && strtolower($server_type->mt4_server_type) === 'manager api') ?  'selected="selected"' : '') ?>>Manager API</option>
                                                    <option value="Web App" <?php echo ((isset($server_type->mt4_server_type) && strtolower($server_type->mt4_server_type) === 'web app') ?  'selected="selected"' : '') ?>>Web App</option>
                                                </select>
                                                <span class="text-danger" id="mt4_server_type_error"></span>
                                            </div>
                                            <!-- mt4 download link -->
                                            <div class="col-12 col-sm-6 mb-1 mt4-download-link">
                                                <label class="form-label" for="mt4-download-link">MT4 download link</label>
                                                <input type="text" class="form-control" id="mt4-download-link" name="mt4_download_link" placeholder="MT4 download link" value="<?php echo (isset($platform_download_link->mt4_download_link) ? $platform_download_link->mt4_download_link : ''); ?>" />
                                                <span class="text-danger" id="mt4-download-link-error"></span>
                                            </div>

                                            <!-- mt4 server type expended for manager api-->
                                            <div class="col-12 col-sm-6 mb-1 mt4_manager_ip_config">
                                                <label class="form-label" for="mt4_server_ip">Server IP</label>
                                                <input type="text" class="form-control" id="mt4_server_ip" name="mt4_server_ip" placeholder="mt4_server_ip" value="<?php echo (isset($server_ip->mt4_server_ip) ? $server_ip->mt4_server_ip : ''); ?>" />
                                                <span class="text-danger" id="mt4_server_ip_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1 mt4_manager_ip_config">
                                                <label class="form-label" for="mt4_manager_login">Manager Login</label>
                                                <input type="text" class="form-control" id="mt4_manager_login" name="mt4_manager_login" placeholder="mt4_manager_login" value="<?php echo (isset($manager_login->mt4_manager_login) ? $manager_login->mt4_manager_login : ''); ?>" />
                                                <span class="text-danger" id="mt4_manager_login_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1 mt4_manager_ip_config">
                                                <label class="form-label" for="mt4_manager_password">Manager Password</label>
                                                <input type="password" class="form-control" id="mt4_manager_password" name="mt4_manager_password" placeholder="mt4_manager_password" value="<?php echo (isset($manager_password->mt4_manager_password) ? $manager_password->mt4_manager_password : ''); ?>" />
                                                <span class="text-danger" id="mt4_manager_password_error"></span>
                                            </div>

                                            <!-- mt4 server type expended for web app-->
                                            <div class="col-12 col-sm-6 mb-1 mt4_web_app_config">
                                                <label class="form-label" for="demo_api_key">Demo API Key</label>
                                                <input type="text" class="form-control" id="demo_api_key" name="demo_api_key" placeholder="demo_api_key" value="<?php echo (isset($demo_api_key->demo_api_key) ? $demo_api_key->demo_api_key : ''); ?>" />
                                                <span class="text-danger" id="demo_api_key_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1 mt4_web_app_config">
                                                <label class="form-label" for="api_url">API URL</label>
                                                <input type="text" class="form-control" id="api_url" name="api_url" placeholder="api_url" value="<?php echo (isset($api_url->api_url) ? $api_url->api_url : ''); ?>" />
                                                <span class="text-danger" id="api_url_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1 mt4_web_app_config">
                                                <label class="form-label" for="live_api_key">Live API Key</label>
                                                <input type="text" class="form-control" id="live_api_key" name="live_api_key" placeholder="live_api_key" value="<?php echo (isset($live_api_key->live_api_key) ? $live_api_key->live_api_key : ''); ?>" />
                                                <span class="text-danger" id="live_api_key_error"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!--mt5 server type -->
                                            <div class="col-12 col-sm-6 mb-1" id="mt5_server_type">
                                                <label class="form-label" for="mt5_server_type">*MT5 Server Type</label>
                                                <select class="select2 form-select mt5_server_type" name="mt5_server_type">
                                                    <option value="">Select Server Type</option>
                                                    <option value="Demo" <?php echo ((isset($server_type->mt5_server_type) && strtolower($server_type->mt5_server_type) === 'demo') ?  'selected="selected"' : '') ?>>Demo</option>
                                                    <option value="Live" <?php echo ((isset($server_type->mt5_server_type) && strtolower($server_type->mt5_server_type) === 'live') ?  'selected="selected"' : '') ?>>Live</option>
                                                </select>
                                                <span class="text-danger" id="mt5_server_type_error"></span>
                                            </div>
                                            <!-- mt5 download link -->
                                            <div class="col-12 col-sm-6 mb-1 mt5-download-link">
                                                <label class="form-label" for="mt5-download-link">MT5 download link</label>
                                                <input type="text" class="form-control" id="mt5-download-link" name="mt5_download_link" placeholder="MT5 download link" value="<?php echo (isset($platform_download_link->mt5_download_link) ? $platform_download_link->mt5_download_link : ''); ?>" />
                                                <span class="text-danger" id="mt5-download-link-error"></span>
                                            </div>
                                            <!-- mt5 server type expended -->
                                            <div class="col-12 col-sm-6 mb-1 mt5_server_type_config">
                                                <label class="form-label" for="mt5_server_ip">Server IP</label>
                                                <input type="text" class="form-control" id="mt5_server_ip" name="mt5_server_ip" placeholder="mt5_server_ip" value="<?php echo (isset($server_ip->mt5_server_ip) ? $server_ip->mt5_server_ip : ''); ?>" />
                                                <span class="text-danger" id="mt5_server_ip_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1 mt5_server_type_config">
                                                <label class="form-label" for="mt5_manager_login">Manager Login</label>
                                                <input type="text" class="form-control" id="mt5_manager_login" name="mt5_manager_login" placeholder="mt5_manager_login" value="<?php echo (isset($manager_login->mt5_manager_login) ? $manager_login->mt5_manager_login : ''); ?>" />
                                                <span class="text-danger" id="mt5_manager_login_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1 mt5_server_type_config">
                                                <label class="form-label" for="mt5_manager_password">Manager Password</label>
                                                <input type="password" class="form-control" id="mt5_manager_password" name="mt5_manager_password" placeholder="mt5_manager_password" value="<?php echo (isset($manager_password->mt5_manager_password) ? $manager_password->mt5_manager_password : ''); ?>" />
                                                <span class="text-danger" id="mt5_manager_password_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1 mt5_server_type_config">
                                                <label class="form-label" for="mt5_api_password">API Password</label>
                                                <input type="password" class="form-control" id="mt5_api_password" name="mt5_api_password" placeholder="mt5_api_password" value="<?php echo (isset($api_password->mt5_api_password) ? $api_password->mt5_api_password : ''); ?>" />
                                                <span class="text-danger" id="mt5_api_password_error"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <input type="hidden" name="config_id" value="<?= (isset($configs->id) ? $configs->id : '') ?>">
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label">&nbsp;</label>
                                                <div>
                                                    <button type="submit" class="btn btn-primary me-1 mb-1">Save changes</button>
                                                    <button type="reset" class="btn btn-outline-secondary mb-1">Discard</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--end API configuration form -->

                        <!-- smtp setup form -->
                        <form action="{{route('system.smtp_setup')}}" class="mt-2 pt-50" method="POST" enctype="multipart/form-data" id="smtp-setup-form">
                            @csrf
                            <div class="card-body py-2 my-25">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- mt5 server type expended -->
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="mail_driver">Mail Driver</label>
                                                <input type="text" class="form-control" id="mail_driver" name="mail_driver" placeholder="Mail Driver" value="<?php echo (isset($configs->mail_driver) ? $configs->mail_driver : ''); ?>" />
                                                <span class="text-danger" id="mail_driver_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="host">Host</label>
                                                <input type="text" class="form-control" id="host" name="host" placeholder="Host Name" value="<?php echo (isset($configs->host) ? $configs->host : ''); ?>" />
                                                <span class="text-danger" id="host_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="port">Port</label>
                                                <input type="text" class="form-control" id="port" name="port" placeholder="Port" value="<?php echo (isset($configs->port) ? $configs->port : ''); ?>" />
                                                <span class="text-danger" id="port_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="mail_user">Mail User</label>
                                                <input type="text" class="form-control" id="mail_user" name="mail_user" placeholder=".....@gmail.com" value="<?php echo (isset($configs->mail_user) ? $configs->mail_user : ''); ?>" />
                                                <span class="text-danger" id="mail_user_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="mail_password">Mail Paasword</label>
                                                <input type="password" class="form-control" id="mail_password" name="mail_password" placeholder="........." value="<?php echo (isset($configs->mail_password) ? $configs->mail_password : ''); ?>" />
                                                <span class="text-danger" id="mail_password_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="mail_encryption">Mail Encryption</label>
                                                <input type="text" class="form-control" id="mail_encryption" name="mail_encryption" placeholder="Mail Encryption" value="<?php echo (isset($configs->mail_encryption) ? $configs->mail_encryption : ''); ?>" />
                                                <span class="text-danger" id="mail_encryption_error"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <input type="hidden" name="config_id" value="<?= (isset($configs->id) ? $configs->id : '') ?>">
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label">&nbsp;</label>
                                                <div>
                                                    <button type="submit" class="btn btn-primary me-1 mb-1">Save changes</button>
                                                    <button type="reset" class="btn btn-outline-secondary mb-1">Discard</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--/smtp setup form -->

                        <!-- company information setup form -->
                        <form action="{{route('system.company_info')}}" class="mt-2 pt-50" method="POST" enctype="multipart/form-data" id="company-info-form">
                            @csrf
                            <div class="card-body py-2 my-25">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_name">Company Name</label>
                                                <input type="text" class="form-control" id="com_name" name="com_name" placeholder="company name" value="<?php echo (isset($configs->com_name) ? $configs->com_name : ''); ?>" />
                                                <span class="text-danger" id="com_name_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_license">License</label>
                                                <input type="text" class="form-control" id="com_license" name="com_license" placeholder="company license" value="<?php echo (isset($configs->com_license) ? $configs->com_license : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_email_1">Company Email(Primary)</label>
                                                <input type="email" class="form-control" id="com_email_1" name="com_email_1" placeholder="company email" value="<?php echo (isset($com_email->com_email_1) ? $com_email->com_email_1 : ''); ?>" />
                                                <span class="text-danger" id="com_email_1_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_email_2">Company Email(Secondary)</label>
                                                <input type="email" class="form-control" id="com_email_2" name="com_email_2" placeholder="company email" value="<?php echo (isset($com_email->com_email_2) ? $com_email->com_email_2 : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_phone_1">Company Phone(Primary)</label>
                                                <input type="number" class="form-control" id="com_phone_1" name="com_phone_1" placeholder="company contact number" value="<?php echo (isset($com_phone->com_phone_1) ? $com_phone->com_phone_1 : ''); ?>" />
                                                <span class="text-danger" id="com_phone_1_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_phone_2">Company Phone(Secondary)</label>
                                                <input type="number" class="form-control" id="com_phone_2" name="com_phone_2" placeholder="company contact number" value="<?php echo (isset($com_phone->com_phone_2) ? $com_phone->com_phone_2 : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_website">Website</label>
                                                <input type="text" class="form-control" id="com_website" name="com_website" placeholder="company website" value="<?php echo (isset($configs->com_website) ? $configs->com_website : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_authority">Authority</label>
                                                <input type="text" class="form-control" id="com_authority" name="com_authority" placeholder="company authority" value="<?php echo (isset($configs->com_authority) ? $configs->com_authority : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_address">Address</label>
                                                <input type="text" class="form-control" id="com_address" name="com_address" placeholder="company address" value="<?php echo (isset($configs->com_address) ? $configs->com_address : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="copyright">Copyright</label>
                                                <input type="text" class="form-control" id="copyright" name="copyright" placeholder="copyright" value="<?php echo (isset($configs->copyright) ? $configs->copyright : ''); ?>" />
                                                <span class="text-danger" id="copyright_error"></span>
                                            </div>

                                            <!-- suppert mail -->
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="support-email">Support Email</label>
                                                <input type="text" class="form-control" id="support-email" name="support_email" placeholder="support email" value="<?php echo (isset($configs->support_email) ? $configs->support_email : ''); ?>" />
                                                <span class="text-danger" id="support-email-error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="auto-email">Auto Email</label>
                                                <input type="text" class="form-control" id="auto-email" name="auto_email" placeholder="Auto Email" value="<?php echo (isset($configs->auto_email) ? $configs->auto_email : ''); ?>" />
                                                <span class="text-danger" id="auto-email-error"></span>
                                            </div>
                                            <!-- social media section start -->
                                            <div class="col-12 col-sm-6 mb-1">
                                                <div class="card-body pb-0 social-media-card">
                                                    <label class="form-label" for="social-media">Social Media</label>
                                                    <div class="social-media-filter border">
                                                        <div class="form-check form-check-success mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View All">
                                                            <input type="checkbox" class="form-check-input input-filter" id="view-all-check" data-value="view-all-check" checked />
                                                            <label class="form-check-label" for="select-all">All</label>
                                                        </div>
                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Facebook">
                                                            <input type="checkbox" class="form-check-input input-filter" id="facebook-check" data-value="facebook-check" checked />
                                                            <label class="form-check-label" for="facebook-check"><i class="social_icon" data-feather='facebook'></i></label>
                                                        </div>
                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Twitter">
                                                            <input type="checkbox" class="form-check-input input-filter" id="twitter-check" data-value="twitter-check" checked />
                                                            <label class="form-check-label" for="twitter-check"><i class="social_icon" data-feather='twitter'></i></label>
                                                        </div>
                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Skype">
                                                            <input type="checkbox" class="form-check-input input-filter" id="skype-check" data-value="skype-check" checked />
                                                            <label class="form-check-label" for="skype-check"><img class="social_icon" style="font-size:1.1rem;" src="{{asset('admin-assets/app-assets/images/icons/social/skype.png')}}" alt="Skype"></label>
                                                        </div>
                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Youtube">
                                                            <input type="checkbox" class="form-check-input input-filter" id="youtube-check" data-value="youtube-check" checked />
                                                            <label class="form-check-label" for="youtube-check"><i class="social_icon" data-feather='youtube'></i></label>
                                                        </div>
                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Telegram">
                                                            <input type="checkbox" class="form-check-input input-filter" id="telegram-check" data-value="telegram-check" checked />
                                                            <label class="form-check-label" for="telegram-check"><i class="social_icon" data-feather='send'></i></label>
                                                        </div>
                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Linkedin">
                                                            <input type="checkbox" class="form-check-input input-filter" id="linkedin-check" data-value="linkedin-check" checked />
                                                            <label class="form-check-label" for="linkedin-check"><img class="social_icon" style="font-size:1.1rem;" src="{{asset('admin-assets/app-assets/images/icons/social/linkedin.png')}}" alt="LinkedIn"></label>

                                                        </div>
                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="LiveChat">
                                                            <input type="checkbox" class="form-check-input input-filter" id="livechat-check" data-value="livechat-check" checked />
                                                            <label class="form-check-label" for="livechat-check"><img class="social_icon" style="font-size:1.1rem;" src="{{asset('admin-assets/app-assets/images/icons/social/livechat.png')}}" alt="LiveChat"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1" id="facebook">
                                                <label class="form-label" for="facebook">Facebook</label>
                                                <input type="text" class="form-control" name="facebook" placeholder="company facebook account" value="<?php echo (isset($com_social_info->facebook) ? $com_social_info->facebook : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1" id="twitter">
                                                <label class="form-label" for="twitter">Twitter</label>
                                                <input type="text" class="form-control" name="twitter" placeholder="company twitter account" value="<?php echo (isset($com_social_info->twitter) ? $com_social_info->twitter : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1" id="skype">
                                                <label class="form-label" for="skype">Skype</label>
                                                <input type="text" class="form-control" name="skype" placeholder="company skype account" value="<?php echo (isset($com_social_info->skype) ? $com_social_info->skype : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1" id="youtube">
                                                <label class="form-label" for="youtube">Youtube</label>
                                                <input type="text" class="form-control" name="youtube" placeholder="company youtube account" value="<?php echo (isset($com_social_info->youtube) ? $com_social_info->youtube : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1" id="telegram">
                                                <label class="form-label" for="telegram">Telegram</label>
                                                <input type="text" class="form-control" name="telegram" placeholder="company telegram account" value="<?php echo (isset($com_social_info->telegram) ? $com_social_info->telegram : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1" id="linkedin">
                                                <label class="form-label" for="linkedin">Linkedin</label>
                                                <input type="text" class="form-control" name="linkedin" placeholder="company linkedin account" value="<?php echo (isset($com_social_info->linkedin) ? $com_social_info->linkedin : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1" id="livechat">
                                                <label class="form-label" for="livechat">LiveChat</label>
                                                <input type="text" class="form-control" name="livechat" placeholder="company livechat account" value="<?php echo (isset($com_social_info->livechat) ? $com_social_info->livechat : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12 mb-1">
                                                <label class="form-label d-none" for="privacy-statement">Privacy Statements</label>
                                                <!-- Snow Editor start -->
                                                <section class="snow-editor d-none">
                                                    <!-- <textarea name="privacy_statement" style="display:none" id="privacy_hidden"></textarea> -->
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
                                                                    <div class="editor">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>
                                                <!-- Snow Editor end -->
                                            </div>
                                            <!-- social media end -->
                                            <input type="hidden" name="config_id" value="<?= (isset($configs->id) ? $configs->id : '') ?>">
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label">&nbsp;</label>
                                                <div>
                                                    <button type="submit" class="btn btn-primary me-1 mb-1">Save changes</button>
                                                    <button type="reset" class="btn btn-outline-secondary mb-1">Discard</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--/company information setup form -->

                        <!-- finance setting form -->
                        <div class="card-body py-2 my-25" id="finance-setting-form">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 mb-1">
                                            <div class="card">
                                                <div class="card-body p-0">
                                                    <div class="card-body p-0">
                                                        <div class="tab-content">
                                                            <form action="{{route('system.finance_setting.add')}}" class="pt-50" method="POST" enctype="multipart/form-data" id="finance-settings-form-add">
                                                                @csrf
                                                                <div class="tab-pane active" id="tab-panel" role="tabpanel" aria-labelledby="transaction-tab">
                                                                    <div class="col-12 col-sm-12 mb-1">
                                                                        <!-- transaction type  -->
                                                                        <div class="col-12 col-sm-6 mb-1" style="float: left; padding-right:1rem;" id="transaction_type">
                                                                            <label class="form-label">Transaction Type</label>
                                                                            <select class="select2 form-select" name="transaction_type" style="position: absolute !important;">
                                                                                <option value="deposit" <?php echo ((isset($transaction_type->transaction_type) && strtolower($transaction_type->transaction_type) == 'deposit') ?  'selected="selected"' : '') ?>>Deposit</option>
                                                                                <option value="withdraw" <?php echo ((isset($transaction_type->transaction_type) && strtolower($transaction_type->transaction_type) == 'withdraw') ?  'selected="selected"' : '') ?>>Withdraw</option>
                                                                                <option value="a_to_w" <?php echo ((isset($transaction_type->transaction_type) && strtolower($transaction_type->transaction_type) == 'a_to_w') ?  'selected="selected"' : '') ?>>Account To Wallet</option>
                                                                                <option value="w_to_a" <?php echo ((isset($transaction_type->transaction_type) && strtolower($transaction_type->transaction_type) == 'w_to_a') ?  'selected="selected"' : '') ?>>Wallet To Account</option>
                                                                            </select>
                                                                        </div>
                                                                        <!-- transaction limit  -->
                                                                        <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                            <label class="form-label">Set Transaction Limit</label>
                                                                            <div class="input-group">
                                                                                <input type="number" name="min_transaction" class="form-control flatpickr-basic" placeholder="Min">
                                                                                <span class="input-group-text">To</span>
                                                                                <input type="number" name="max_transaction" class="form-control flatpickr-basic" placeholder="Max">
                                                                            </div>
                                                                        </div>
                                                                        <!-- transaction charge type  -->
                                                                        <div class="col-12 col-sm-6 mb-1" style="float: left; padding-right: 1rem;">
                                                                            <div class="card-body pb-0 social-media-card">
                                                                                <label class="form-label">Charge Type</label>
                                                                                <div class="social-media-filter border">
                                                                                    <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="All">
                                                                                        <input type="checkbox" class="form-check-input input-filter" name="fixed" id="fixed" data-value="fixed" checked />
                                                                                        <label class="form-check-label" for="fixed">Fixed(&dollar;)</label>
                                                                                    </div>
                                                                                    <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="All">
                                                                                        <input type="checkbox" class="form-check-input input-filter" name="percentage" id="percentage" data-value="percentage" checked />
                                                                                        <label class="form-check-label" for="percentage">Percentage(&percnt;)</label>
                                                                                    </div>
                                                                                </div>
                                                                                <span id="charge_type_error" class="text-danger"></span>
                                                                            </div>
                                                                        </div>
                                                                        <!-- charge limit -->
                                                                        <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                            <label class="form-label">Set Charge Limit</label>
                                                                            <div class="input-group">
                                                                                <input type="number" name="limit_start" class="form-control flatpickr-basic" placeholder="Start">
                                                                                <span class="input-group-text">To</span>
                                                                                <input type="number" name="limit_end" class="form-control flatpickr-basic" placeholder="End">
                                                                            </div>
                                                                        </div>
                                                                        <div class="clear-fixed"></div>
                                                                        <!-- KYC required  -->
                                                                        <div class="col-12 col-sm-4 mb-1" style="float: left; padding-right: 1rem;">
                                                                            <div class="card-body pb-0 social-media-card">
                                                                                <label class="form-label">KYC Required</label>
                                                                                <div class="social-media-filter border">
                                                                                    <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="KYC Required">
                                                                                        <input type="checkbox" class="form-check-input input-filter kyc" name="kyc" data-value="kyc" />
                                                                                        <label class="form-check-label" for="kyc">KYC Required For Finace Transaction</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- amount -->
                                                                        <div class="col-12 col-sm-2 mb-1" style="float: left; padding-right:1rem;">
                                                                            <label class="form-label">Charge (Amount)</label>
                                                                            <div class="input-group">
                                                                                <input id="charge" type="text" name="amount" class="form-control flatpickr-basic" placeholder="0$">
                                                                            </div>
                                                                            <span id="charge_error" class="text-danger"></span>
                                                                        </div>
                                                                        <!-- transaction permission -->
                                                                        <div class="col-12 col-sm-3 mb-1" style="float: left; padding-right:1rem;">
                                                                            <label class="form-label">Permission</label>
                                                                            <select class="select2 form-select" name="permission">
                                                                                <option value="Panding">Panding</option>
                                                                                <option value="Approved">Approved</option>
                                                                            </select>
                                                                        </div>
                                                                        <!-- active status -->
                                                                        <div class="col-12 col-sm-3 mb-1" style="float: left;">
                                                                            <label class="form-label">Active Status</label>
                                                                            <select class="select2 form-select" name="active_status" style="position: absolute !important;">
                                                                                <option value="0">Deactivate</option>
                                                                                <option value="1">Activate</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="clear-fixed"></div>
                                                                    <div class="col-12 col-sm-12 mb-1">
                                                                        <input type="hidden" name="config_id" value="<?= (isset($configs->id) ? $configs->id : '') ?>">
                                                                        <div class="p-0 m-0">
                                                                            <button type="submit" class="btn btn-primary me-1 mb-1">Add Charge</button>
                                                                            <button type="reset" class="btn btn-outline-secondary mb-1">Discard</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- finance view and action table  -->
                                                                <!-- Dark Tables start -->
                                                                <div class="row" id="dark-table">
                                                                    <div class="col-12">
                                                                        <div class="card">
                                                                            <div class="card-header" style="padding-left: 0px;">
                                                                                <h4 class="card-title">View Finance Settings</h4>
                                                                            </div>
                                                                            <div class="table-responsive">
                                                                                <table id="finance_settings_table" class="datatables-basic table finance-settings-table scrollbar-primary">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <!-- <th>Serial</th> -->
                                                                                            <th>Transaction Type</th>
                                                                                            <th>Transaction Limit</th>
                                                                                            <th>Charge Type</th>
                                                                                            <th>Charge Limit</th>
                                                                                            <th>KYC</th>
                                                                                            <th>Amount</th>
                                                                                            <th>Status</th>
                                                                                            <th>Active Status</th>
                                                                                            <th>Actions</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/finance setting form -->
                        <!--Edit Finace Modal -->
                        <div class="modal fade text-start" id="finance-setting-edit-form" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel33">Update Finance Settings</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('system.finance-settings.edit') }}" method="POST" enctype="multipart/form-data" id="finance-settings-edit-form">
                                        <div class="modal-body">
                                            <div class="col-12 col-sm-12 mb-1" style="float: left;">
                                                <label class="form-label">Transaction Type</label>
                                                <select id="modal_transaction_type" class="select2 form-select" name="transaction_type">
                                                    <option value="Deposit">Deposit</option>
                                                    <option value="Withdraw">Withdraw</option>
                                                    <option value="w_to_a">Wallet To Account</option>
                                                    <option value="a_to_w">Account To Wallet</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-sm-12 mb-1" style="float: left;margin-right:1rem;">
                                                <label class="form-label">Set Transaction Limit</label>
                                                <div class="input-group">
                                                    <input id="modal_min_transaction" type="text" name="min_transaction" class="form-control flatpickr-basic" placeholder="Min">
                                                    <span class="input-group-text">To</span>
                                                    <input id="modal_max_transaction" type="text" name="max_transaction" class="form-control flatpickr-basic" placeholder="Max">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 mb-1" style="float: left; margin-right:1rem;">
                                                <div class="card-body pb-0 social-media-card">
                                                    <label class="form-label">Charge Type</label>
                                                    <div class="social-media-filter border">
                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="All">
                                                            <input type="checkbox" class="form-check-input input-filter" name="fixed" id="modal-fixed" data-value="fixed" />
                                                            <label class="modal-fixed" for="fixed">Fixed(&dollar;)</label>
                                                        </div>
                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="All">
                                                            <input type="checkbox" class="form-check-input input-filter" name="percentage" id="modal-percentage" data-value="percentage" />
                                                            <label class="modal-percentage" for="percentage">Percentage(&percnt;)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 mb-1" style="float: left;margin-right:1rem;">
                                                <label class="form-label">Set Charge Limit</label>
                                                <div class="input-group">
                                                    <input id="modal_limit_start" type="text" name="limit_start" class="form-control flatpickr-basic" placeholder="Start">
                                                    <span class="input-group-text">To</span>
                                                    <input id="modal_limit_end" type="text" name="limit_end" class="form-control flatpickr-basic" placeholder="End">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 mb-1" style="float: left;margin-right:1rem;">
                                                <label class="form-label">KYC Required</label>
                                                <div class="social-media-filter border">
                                                    <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="KYC Required">
                                                        <input id="modal-kyc" type="checkbox" class="form-check-input input-filter kyc" name="kyc" data-value="kyc" />
                                                        <label class="form-check-label" for="modal-kyc">KYC Required For Finace Transaction</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 mb-1" style="float: left;margin-right:1rem;">
                                                <label class="form-label">Amount</label>
                                                <div class="input-group">
                                                    <input id="modal_amount" type="text" name="amount" class="form-control flatpickr-basic" placeholder="0$" value="">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 mb-1" style="float: left;">
                                                <label class="form-label">Permission</label>
                                                <select id="modal_permission" class="select2 form-select" name="permission">
                                                    <option value="Panding">Panding</option>
                                                    <option value="Approved">Approved</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-sm-12 mb-1" style="float: left;">
                                                <label class="form-label">Active Status</label>
                                                <select id="modal_active_status" class="select2 form-select" name="active_status">
                                                    <option value="0">Deactivate</option>
                                                    <option value="1">Activate</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="transaction_setting_id" id="transaction_setting_id" value="">
                                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--Edit Finace Modal End-->
                        <!--Delete Finace Modal End-->
                        <div class="modal fade" id="finance-setting-delete-modal">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="exampleModalCenterTitle">Confirmation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="" class="modal-content pt-0">
                                        @csrf
                                        <input type="hidden" name="id" id="finance-setting-delete-id" value="">
                                        <div class="modal-body my-3">
                                            <h4 class="text-center">
                                                Do you really want to delete these records? This process cannot be undone.
                                                </h5 class="text-center">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger data-submit me-1" data-bs-dismiss="modal" id="finance-setting-delete">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--Delete Finace Modal End-->

                        <!-- sofware setting form -->
                        <form action="{{route('system.software_setting')}}" class="mt-2 pt-50" method="POST" enctype="multipart/form-data" id="software-setting-form">
                            @csrf
                            <div class="card-body py-2 my-25">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- crm type -->
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="type">CRM Type</label>
                                                <select id="crm_type" class="select2 form-select" name="crm_type">
                                                    <option value="Default" <?php echo ((isset($configs->crm_type) && strtolower($configs->crm_type) === 'default') ?  'selected="selected"' : '') ?>>Default</option>
                                                    <option value="Combined" <?php echo ((isset($configs->crm_type) && strtolower($configs->crm_type) === 'combined') ?  'selected="selected"' : '') ?>>Combined</option>
                                                    <option value="Pro" <?php echo ((isset($configs->crm_type) && strtolower($configs->crm_type) === 'pro') ?  'selected="selected"' : '') ?>>Pro</option>
                                                </select>
                                            </div>
                                            <!-- create meta account when signup type  -->
                                            <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                <div class="card-body pb-0 social-media-card">
                                                    <label class="form-label">Create Meta Account With Signup</label>
                                                    <div class="social-media-filter border">
                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Create meta account with signup">
                                                            <input type="checkbox" class="form-check-input input-filter" name="create_meta_acc" id="create_meta_acc" data-value="create_meta_acc" <?php echo (($configs->create_meta_acc == 1) ?  'checked' : ''); ?> />
                                                            <label class="form-check-label" for="create_meta_acc">Create Meta Account With Signup</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Platform Book -->
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="type">Platform Book</label>
                                                <select id="platform_book" class="select2 form-select" name="platform_book">
                                                    <option value="">Select Platform Book</option>
                                                    <option value="A Book" <?php echo ((isset($configs->platform_book) && strtolower($configs->platform_book) === 'a book') ?  'selected="selected"' : '') ?>>A Book</option>
                                                    <option value="B Book" <?php echo ((isset($configs->platform_book) && strtolower($configs->platform_book) === 'b book') ?  'selected="selected"' : '') ?>>B Book</option>
                                                </select>
                                            </div>
                                            <!-- Social Accounts Required  -->
                                            <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                <div class="card-body pb-0 social-media-card">
                                                    <label class="form-label">Social Accounts Required</label>
                                                    <div class="social-media-filter border">
                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Social Accounts Required">
                                                            <input type="checkbox" class="form-check-input input-filter" name="social_account" id="social_account" data-value="social_account" <?php echo (($configs->social_account == 1) ?  'checked' : ''); ?> />
                                                            <label class="form-check-label" for="social_account">Social Accounts Required</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="acc_limit">Account Limit</label>
                                                <input type="number" class="form-control" id="acc_limit" name="acc_limit" placeholder="0" value="<?php echo (isset($configs->acc_limit) ? $configs->acc_limit : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="brute_force_attack">Brute Force Attack</label>
                                                <input type="number" class="form-control" id="brute_force_attack" name="brute_force_attack" placeholder="0" value="<?php echo (isset($configs->brute_force_attack) ? $configs->brute_force_attack : ''); ?>" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <input type="hidden" name="config_id" value="<?= (isset($configs->id) ? $configs->id : '') ?>">
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label">&nbsp;</label>
                                                <div>
                                                    <button type="submit" class="btn btn-primary me-1 mb-1">Save changes</button>
                                                    <button type="reset" class="btn btn-outline-secondary mb-1">Discard</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--/sofware setting form -->
                    </div>

                    <!-- deactivate account  -->
                    <div class="card d-none">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Delete Account</h4>
                        </div>
                        <div class="card-body py-2 my-25">
                            <div class="alert alert-warning">
                                <h4 class="alert-heading">Are you sure you want to delete your account?</h4>
                                <div class="alert-body fw-normal">
                                    Once you delete your account, there is no going back. Please be certain.
                                </div>
                            </div>

                            <form id="formAccountDeactivation" class="validate-form" onsubmit="return false">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation" data-msg="Please confirm you want to delete account" />
                                    <label class="form-check-label font-small-3" for="accountActivation">
                                        I confirm my account deactivation
                                    </label>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-danger deactivate-account mt-1">Deactivate Account</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--/ profile -->
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
<!-- toaster js -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/page-account-settings-account.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/page-configuration.js')}}"></script>
<!-- form hide/show -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/config-form.js')}}"></script>
@stop
<!-- BEGIN: page JS -->