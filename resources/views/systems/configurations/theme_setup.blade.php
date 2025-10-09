@extends('layouts.system-layout')
@section('title', 'System Configuration')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/config-form.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css')}}">
<style>
    .al_resize {
        height: revert-layer;
        object-fit: contain;
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
                        <h2 class="content-header-title float-start mb-0">Theme Setup</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">{{ __('category.Home') }}</a></li>
                                <li class="breadcrumb-item"><a href="#">Configurations</a></li>
                                <li class="breadcrumb-item active">Theme Setup</li>
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
                    <!-- Modern Horizontal Wizard -->
                    <section class="modern-horizontal-wizard">
                        <div class="bs-stepper wizard-modern modern-wizard-example">
                            <!-- stepper header -->
                            <div class="bs-stepper-header">
                                <!-- stepper user portal logo -->
                                <div class="step" data-target="#account-details-modern" role="tab" id="account-details-modern-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="file-text" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Logo Setup</span>
                                            <span class="bs-stepper-subtitle">Set Logos for Dark & White</span>
                                        </span>
                                    </button>
                                </div>
                                <div class="line">
                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                </div>
                                <!-- stepper user portal theme -->
                                <div class="step" data-target="#personal-info-modern" role="tab" id="personal-info-modern-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="user" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Client Portal Theme</span>
                                            <span class="bs-stepper-subtitle">Change and update client portal theme</span>
                                        </span>
                                    </button>
                                </div>
                                <div class="line">
                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                </div>
                                <!-- stepper admin theme -->
                                <div class="step" data-target="#address-step-modern" role="tab" id="address-step-modern-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="map-pin" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Admin Theme</span>
                                            <span class="bs-stepper-subtitle">Change admin theme</span>
                                        </span>
                                    </button>
                                </div>
                                <div class="line">
                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                </div>
                                <div class="step" data-target="#social-links-modern" role="tab" id="social-links-modern-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="link" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Theme Colors</span>
                                            <span class="bs-stepper-subtitle">Set Theme Colors</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="bs-stepper-content">
                                <!-- theme logo setup -->
                                <div id="account-details-modern" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger">
                                    <div class="content-header">
                                        <h5 class="mb-0">Brand Logo</h5>
                                        <small class="text-muted">Change branding and Logos</small>
                                    </div>
                                    <form class="row" id="logo-setup-form" action="{{route('system.configarations.logo_upload')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <!-- BEGIN: Dark logo -->
                                        <div class="col-lg-3 mb-3">
                                            <div class="d-flex">
                                                <!-- upload and reset button -->
                                                <div class="d-flex align-items-end mt-75">
                                                    <div class="w-100">
                                                        <a href="#" class="me-25 w-100">
                                                            <img src="{{ get_favicon_icon() }}" id="favicon-img" class="upload-dark-logo rounded me-50 img img-fluid al_resize" alt="Favicon image" height="100" width="100" />
                                                        </a>
                                                        <div class="w-100 pt-1 pb-1">
                                                            <label for="favicon-upload" class="btn btn-sm btn-primary">Favicon Icon
                                                            </label>
                                                            <input type="file" id="favicon-upload" hidden accept="image/*" name="favicon_icon" onchange="document.getElementById('favicon-img').src = window.URL.createObjectURL(this.files[0])" />
                                                        </div>
                                                        <button type="button" data-reset-url="{{ get_favicon_icon() }}" data-reset-inputID="favicon-upload" data-reset-preview="favicon-img" id="favicon_icon-reset" class="btn btn-sm btn-outline-secondary mb-75">Reset</button>
                                                        <p class="mb-0">Allowed file types: png, jpg, jpeg.</p>
                                                    </div>
                                                </div>
                                                <!--/ upload and reset button -->
                                            </div>
                                        </div>
                                        <!-- END: Dark logo -->

                                        <!-- BEGIN: Dark logo -->
                                        <div class="col-lg-3 mb-3">
                                            <div class="d-flex">
                                                <!-- upload and reset button -->
                                                <div class="d-flex align-items-end mt-75">
                                                    <div class="w-100">
                                                        <a href="#" class="me-25 w-100">
                                                            <img src="{{ get_dark_logo() }}" id="dark-logo-img" class="upload-dark-logo rounded me-50 img img-fluid al_resize" alt="profile image" height="100" width="100" />
                                                        </a>
                                                        <div class="w-100 pt-1 pb-1">
                                                            <label for="dark-logo-upload" class="btn btn-sm btn-primary">Logo for Dark
                                                                layout</label>
                                                            <input type="file" id="dark-logo-upload" hidden accept="image/*" name="dark_logo" onchange="document.getElementById('dark-logo-img').src = window.URL.createObjectURL(this.files[0])" />
                                                        </div>
                                                        <button type="button" data-reset-url="{{ get_dark_logo() }}" data-reset-inputID="dark-logo-upload" data-reset-preview="dark-logo-img" id="dark-logo-reset" class="btn btn-sm btn-outline-secondary mb-75">Reset</button>
                                                        <p class="mb-0">Allowed file types: png, jpg, jpeg.</p>
                                                    </div>
                                                </div>
                                                <!--/ upload and reset button -->
                                            </div>
                                        </div>
                                        <!-- END: Dark logo -->
                                        <!-- BEGIN: Light logo -->
                                        <div class="col-lg-3 mb-3">
                                            <div class="d-flex">
                                                <!-- upload and reset button -->
                                                <div class="d-flex align-items-end mt-75 ms-1">
                                                    <div class="w-100">
                                                        <a href="#" class="me-25 w-100">
                                                            <img src="{{ get_light_logo() }}" id="account-upload-img" class="uploadedAvatar rounded me-50 img img-fluid al_resize" alt="profile image" height="100" width="100" />
                                                        </a>
                                                        <div class="w-100 pt-1 pb-1">
                                                            <label for="account-upload" class="btn btn-sm btn-primary">Logo for light
                                                                layout</label>
                                                            <input type="file" id="account-upload" hidden accept="image/*" name="light_logo" onchange="document.getElementById('account-upload-img').src = window.URL.createObjectURL(this.files[0])" />
                                                        </div>
                                                        <button type="button" data-reset-url="{{ get_light_logo() }}" data-reset-inputID="account-upload" data-reset-preview="account-upload-img" id="account-reset" class="btn btn-sm btn-outline-secondary mb-75">Reset</button>
                                                        <p class="mb-0">Allowed file types: png, jpg, jpeg.</p>
                                                    </div>
                                                </div>
                                                <!--/ upload and reset button -->
                                            </div>
                                        </div>
                                        <!-- END: Light logo -->
                                        <!-- BEGIN: Email logo -->
                                        <div class="col-lg-3 mb-3">
                                            <div class="d-flex">
                                                <!-- upload and reset button -->
                                                <div class="d-flex align-items-end mt-75 ms-1">
                                                    <div class="w-100">
                                                        <a href="#" class="me-25 w-100">
                                                            <img src="{{ get_email_logo() }}" id="email-upload-img" class="uploadedAvatar rounded me-50 img img-fluid al_resize" alt="profile image" height="100" width="100" />
                                                        </a>
                                                        <div class="w-100 pt-1 pb-1">
                                                            <label for="email-logo-upload" class="btn btn-sm btn-primary">Logo for Email
                                                                Template</label>
                                                            <input type="file" id="email-logo-upload" hidden accept="image/*" name="email_logo" onchange="document.getElementById('email-upload-img').src = window.URL.createObjectURL(this.files[0])" />
                                                        </div>
                                                        <button type="button" data-reset-url="{{ get_email_logo() }}" data-reset-inputID="email-logo-upload" data-reset-preview="email-upload-img" id="email-reset" class="btn btn-sm btn-outline-secondary mb-75">Reset</button>
                                                        <p class="mb-0">Allowed file types: png, jpg, jpeg.</p>
                                                    </div>
                                                </div>
                                                <!--/ upload and reset button -->
                                            </div>
                                        </div>
                                        <!-- END: Email logo -->
                                    </form>
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-outline-secondary btn-prev" disabled>
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-primary " data-file="true" onclick="_run(this)" data-el="fg" data-form="logo-setup-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="upload_logo_callback" data-btnid="btn-logo-upload" id="btn-logo-upload">
                                            <span class="align-middle d-sm-inline-block d-none">Save Change</span>
                                            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                        </button>
                                        <button class="btn-next d-none">&nbsp;</button>
                                    </div>
                                </div>
                                <!-- change user portal theme and color -->
                                <div id="personal-info-modern" class="content" role="tabpanel" aria-labelledby="personal-info-modern-trigger">
                                    <div class="content-header">
                                        <h5 class="mb-0">Set client portal theme</h5>
                                        <small>Change and update client portal theme</small>
                                    </div>
                                    <form id="client-theme-form" action="{{route('system.configarations.update_client_theme')}}" method="post" enctype="multipart/form-data" class="row custom-options-checkable g-1 mb-3">
                                        @csrf
                                        <div class="col-md-8 row g-1 bg-light-info p-3">
                                            <h3>Theme Style</h3>
                                            <div class="col-md-6">
                                                <input class="custom-option-item-check" type="radio" name="user_theme" id="user-theme-light" value="light-layout" <?php echo checked_user_theme('light-layout'); ?> />
                                                <label class="custom-option-item text-center p-1" for="user-theme-light">
                                                    <i data-feather="sun" class="font-large-1 mb-75"></i>
                                                    <span class="custom-option-item-title h4 d-block">Light
                                                        Theme</span>
                                                    <small>Set background color light and text color
                                                        black.</small>
                                                </label>
                                            </div>

                                            <div class="col-md-6">
                                                <input class="custom-option-item-check" type="radio" name="user_theme" id="user-theme-dark" value="dark-layout" <?php echo checked_user_theme('dark-layout'); ?> />
                                                <label class="custom-option-item text-center text-center p-1 bg-theme-dark" for="user-theme-dark">
                                                    <i data-feather="moon" class="font-large-1 mb-75"></i>
                                                    <span class="custom-option-item-title h4 d-block">Dark
                                                        Theme</span>
                                                    <small>Set background color dark and text color
                                                        light.</small>
                                                </label>
                                            </div>
                                            <div class="col-md-6">
                                                <input class="custom-option-item-check" type="radio" name="user_theme" id="user-theme-semi-dark" value="semi-dark-layout" <?php echo checked_user_theme('semi-dark-layout'); ?> />
                                                <label class="custom-option-item text-center p-1 position-relative" for="user-theme-semi-dark">
                                                    <span class="semi-dark-overlay">
                                                        <span class="top-triangle"></span>
                                                        <span class="top-triangle"></span>
                                                    </span>
                                                    <span class="semi-dark-content">
                                                        <i data-feather="moon" class="font-large-1 mb-75"></i>
                                                        <span class="custom-option-item-title h4 d-block">Semi
                                                            Dark Theme</span>
                                                        <small>Set background color semi dark and text color
                                                            black and light.</small>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-md-6">
                                                <input class="custom-option-item-check" type="radio" name="user_theme" id="user-theme-border" value="bordered-layout" <?php echo checked_user_theme('bordered-layout'); ?> />
                                                <label class="custom-option-item text-center p-1 position-relative" for="user-theme-border">
                                                    <i data-feather="sun" class="font-large-1 mb-75"></i>
                                                    <span class="custom-option-item-title h4 d-block">Bordered
                                                        Theme</span>
                                                    <small>Set background color light and borderd and text color
                                                        black.</small>
                                                    <span class="border-theme-overlay border"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 row g-1 bg-light-success p-3">
                                            <!-- theme name -->
                                            <h3>Theme Name</h3>
                                            <div class="col-md-12">
                                                <input class="custom-option-item-check" type="radio" name="theme_name" id="soft-ui-theme" value="soft-ui" <?= \App\Services\systems\ThemeService::selected_theme('client', 'soft-ui') ?> />
                                                <label class="custom-option-item text-center p-1 position-relative" for="soft-ui-theme">
                                                    <span class="semi-dark-overlay">
                                                        <span class="top-triangle"></span>
                                                        <span class="top-triangle"></span>
                                                    </span>
                                                    <span class="semi-dark-content">
                                                        <i data-feather='credit-card' class="font-large-1 mb-75"></i>
                                                        <span class="custom-option-item-title h4 d-block">Soft UI</span>
                                                        <small>
                                                            Change Theme Version
                                                        </small>
                                                    </span>
                                                </label>
                                            </div>
                                            <!-- theme name -->
                                            <div class="col-md-">
                                                <input class="custom-option-item-check" type="radio" name="theme_name" id="metronic-theme" value="metronic" <?= \App\Services\systems\ThemeService::selected_theme('client', 'metronic') ?> />
                                                <label class="custom-option-item text-center p-1 position-relative" for="metronic-theme">
                                                    <i data-feather='credit-card' class="font-large-1 mb-75"></i>
                                                    <span class="custom-option-item-title h4 d-block">Metronic</span>
                                                    <small>Change Theme Version</small>
                                                    <span class="border-theme-overlay border"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-primary btn-prev" type="button">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-primary" type="button" data-file="true" onclick="_run(this)" data-el="fg" data-form="client-theme-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="client_thene_update_callback" data-btnid="btn-client-thene-save" id="btn-client-thene-save">
                                            <span class="align-middle d-sm-inline-block d-none">Save Change</span>
                                            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                        </button>
                                        <button type="button" class="btn-next d-none">&nbsp;</button>
                                    </div>
                                </div>
                                <!-- admin theme -->
                                <div id="address-step-modern" class="content" role="tabpanel" aria-labelledby="address-step-modern-trigger">
                                    <div class="content-header">
                                        <h5 class="mb-0">Admin Theme</h5>
                                        <small>Change and update admin theme</small>
                                    </div>
                                    <form class="row custom-options-checkable g-1 mb-3" action="{{route('system.configarations.update_admin_theme')}}" method="post" enctype="multipart/form-data" id="admin-theme-form">
                                        @csrf
                                        <div class="col-md-8 row g-1 bg-light-info p-3">
                                            <h3>Theme Style</h3>
                                            <div class="col-md-6">
                                                <input class="custom-option-item-check" type="radio" name="admin_theme" id="admin-light-theme" value="light-layout" <?php echo checked_admin_theme('light-layout'); ?> />
                                                <label class="custom-option-item text-center p-1" for="admin-light-theme">
                                                    <i data-feather="sun" class="font-large-1 mb-75"></i>
                                                    <span class="custom-option-item-title h4 d-block">Light
                                                        Theme</span>
                                                    <small>Set background color light and text color
                                                        black.</small>
                                                </label>
                                            </div>

                                            <div class="col-md-6">
                                                <input class="custom-option-item-check" type="radio" name="admin_theme" id="admin-dark-theme" value="dark-layout" <?php echo checked_admin_theme('dark-layout'); ?> />
                                                <label class="custom-option-item text-center text-center p-1 bg-theme-dark" for="admin-dark-theme">
                                                    <i data-feather="moon" class="font-large-1 mb-75"></i>
                                                    <span class="custom-option-item-title h4 d-block">Dark
                                                        Theme</span>
                                                    <small>Set background color dark and text color
                                                        light.</small>
                                                </label>
                                            </div>
                                            <div class="col-md-6">
                                                <input class="custom-option-item-check" type="radio" name="admin_theme" id="admin-semi-dark-theme" value="semi-dark-layout" <?php echo checked_admin_theme('semi-dark-layout'); ?> />
                                                <label class="custom-option-item text-center p-1 position-relative" for="admin-semi-dark-theme">
                                                    <span class="semi-dark-overlay">
                                                        <span class="top-triangle"></span>
                                                        <span class="top-triangle"></span>
                                                    </span>
                                                    <span class="semi-dark-content">
                                                        <i data-feather="moon" class="font-large-1 mb-75"></i>
                                                        <span class="custom-option-item-title h4 d-block">Semi
                                                            Dark Theme</span>
                                                        <small>Set background color semi dark and text color
                                                            black and light.</small>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-md-6">
                                                <input class="custom-option-item-check" type="radio" name="admin_theme" id="admin-bordered-theme" value="bordered-layout" <?php echo checked_admin_theme('bordered-layout'); ?> />
                                                <label class="custom-option-item text-center p-1 position-relative" for="admin-bordered-theme">
                                                    <i data-feather="sun" class="font-large-1 mb-75"></i>
                                                    <span class="custom-option-item-title h4 d-block">Bordered
                                                        Theme</span>
                                                    <small>Set background color light and borderd and text color
                                                        black.</small>
                                                    <span class="border-theme-overlay border"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 row g-1 bg-light-success p-3">
                                            <!-- theme name -->
                                            <h3>Theme Name</h3>
                                            <div class="col-md-12">
                                                <input class="custom-option-item-check" type="radio" name="theme_name" id="vieuxy-theme" value="vieuxy" <?= \App\Services\systems\ThemeService::selected_theme('admin', 'vieuxy') ?> />
                                                <label class="custom-option-item text-center p-1 position-relative" for="vieuxy-theme">
                                                    <span class="semi-dark-overlay">
                                                        <span class="top-triangle"></span>
                                                        <span class="top-triangle"></span>
                                                    </span>
                                                    <span class="semi-dark-content">
                                                        <i data-feather='credit-card' class="font-large-1 mb-75"></i>
                                                        <span class="custom-option-item-title h4 d-block">Vieuxy</span>
                                                        <small>
                                                            Change Theme Version
                                                        </small>
                                                    </span>
                                                </label>
                                            </div>
                                            <!-- theme name -->
                                            <div class="col-md-">
                                                <input class="custom-option-item-check" type="radio" name="theme_name" id="metronic-theme-admin" value="metronic" <?= \App\Services\systems\ThemeService::selected_theme('admin', 'metronic') ?> />
                                                <label class="custom-option-item text-center p-1 position-relative" for="metronic-theme-admin">
                                                    <i data-feather='credit-card' class="font-large-1 mb-75"></i>
                                                    <span class="custom-option-item-title h4 d-block">Metronic</span>
                                                    <small>Change Theme Version</small>
                                                    <span class="border-theme-overlay border"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-primary btn-prev">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-primary " type="button" data-file="true" onclick="_run(this)" data-el="fg" data-form="admin-theme-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="admin_theme_update_callback" data-btnid="btn-admin-thene-save" id="btn-admin-thene-save">
                                            <span class="align-middle d-sm-inline-block d-none">Save Change</span>
                                            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                        </button>
                                        <button type="button" class="btn-next d-none">&nbsp;</button>
                                    </div>
                                </div>
                                <!-- colors -->
                                <div id="social-links-modern" class="content" role="tabpanel" aria-labelledby="social-links-modern-trigger">
                                    <div class="content-header">
                                        <h5 class="mb-0">Colors</h5>
                                        <small>Change colors</small>
                                    </div>
                                    <hr>
                                    <form class="row" id="client-theme-color-form" method="post" action="{{route('system.configarations.update_theme_color')}}">
                                        @csrf
                                        <div class="col-md-6">
                                            <!-- set client theme colors -->
                                            <h3>Client theme colors</h3>
                                            <div class="row custom-options-checkable g-1 ">
                                                <div class="col-md-6">
                                                    <label for="user_primary_color">Primary Color</label>
                                                    <div class="input-group mb-2 mt-1">
                                                        <span class="input-group-text">
                                                            <input type="color" id="" value="{{ $userThemeColors->primary_color ?? '' }}" data-input-for="user_primary_color">
                                                        </span>
                                                        <input name="user_primary_color" id="user_primary_color" type="text" class="form-control set_color" placeholder="#FFFFF" value="{{ $userThemeColors->primary_color ?? '' }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="user_body_color">Body Color</label>
                                                    <div class="input-group mb-2 mt-1">
                                                        <span class="input-group-text">
                                                            <input type="color" id="" value="{{ $userThemeColors->body_color ?? '' }}" data-input-for="user_body_color">
                                                        </span>
                                                        <input name="user_body_color" id="user_body_color" type="text" class="form-control set_color" placeholder="#FFFFF" value="{{ $userThemeColors->body_color ?? '' }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="user_button_color">Button Color</label>
                                                    <div class="input-group mb-2 mt-1">
                                                        <span class="input-group-text">
                                                            <input type="color" id="" value="{{ $userThemeColors->button_color ?? '' }}" data-input-for="user_button_color">
                                                        </span>
                                                        <input name="user_secondary_color" id="user_button_color" type="text" class="form-control set_color" placeholder="#FFFFF" value="{{ $userThemeColors->button_color ?? '' }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="user_form_color">Form Color</label>
                                                    <div class="input-group mb-2 mt-1">
                                                        <span class="input-group-text">
                                                            <input type="color" id="" value="{{ $userThemeColors->form_color ?? '' }}" data-input-for="user_form_color">
                                                        </span>
                                                        <input name="user_form_color" id="user_form_color" type="text" class="form-control set_color" placeholder="#FFFFF" value="{{ $userThemeColors->form_color ?? '' }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h3>Admin theme colors</h3>
                                            <div class="row custom-options-checkable g-1 ">
                                                <div class="col-md-6">
                                                    <label for="ad_primary_color">Primary Color</label>
                                                    <div class="input-group mb-2 mt-1">
                                                        <span class="input-group-text">
                                                            <input type="color" id="" value="{{ $adminThemeColors->primary_color ?? '' }}" data-input-for="ad_primary_color">
                                                        </span>
                                                        <input name="ad_primary_color" id="ad_primary_color" type="text" class="form-control set_color" placeholder="#FFFFF" value="{{ $adminThemeColors->primary_color ?? '' }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="ad_body_color">Body Color</label>
                                                    <div class="input-group mb-2 mt-1">
                                                        <span class="input-group-text">
                                                            <input type="color" id="" value="{{ $adminThemeColors->body_color ?? '' }}" data-input-for="ad_body_color">
                                                        </span>
                                                        <input name="ad_body_color" id="ad_body_color" type="text" class="form-control set_color" placeholder="#FFFFF" value="{{ $adminThemeColors->body_color ?? '' }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="ad_button_color">Button Color</label>
                                                    <div class="input-group mb-2 mt-1">
                                                        <span class="input-group-text input_color_picker">
                                                            <input type="color" value="{{ $adminThemeColors->button_color ?? '' }}" data-input-for="ad_button_color">
                                                        </span>
                                                        <input name="admin_secondary_color" id="ad_button_color" type="text" class="form-control set_color" placeholder="#FFFFF" value="{{ $adminThemeColors->button_color ?? '' }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="ad_form_color">Form Color</label>
                                                    <div class="input-group mb-2 mt-1">
                                                        <span class="input-group-text">
                                                            <input type="color" id="" value="{{ $adminThemeColors->form_color ?? '' }}" data-input-for="ad_form_color">
                                                        </span>
                                                        <input name="ad_form_color" id="ad_form_color" type="text" class="form-control set_color" placeholder="#FFFFF" value="{{ $adminThemeColors->form_color ?? '' }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-primary btn-prev" type="button">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-success" type="button" data-file="true" onclick="_run(this)" data-el="fg" data-form="client-theme-color-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="theme_colors_callback" data-btnid="btn-change-theme-color" id="btn-change-theme-color">Save Change</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- /Modern Horizontal Wizard -->
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
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<!-- toaster js -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js')}}"></script>

<script>
    // logo upload
    function upload_logo_callback(data) {
        if (data.status) {
            notify('success', data.message, 'Logo Upload');
            location.reload();
        } else {
            notify('error', data.message, 'Logo Upload');
        }
    }
    // client theme setup callback
    function client_thene_update_callback(data) {
        if (data.status) {
            notify('success', data.message, 'Client Theme Update');
            // location.reload();
        } else {
            notify('error', data.message, 'Client Theme Update');
        }
    }
    // admin theme setup callback
    function admin_theme_update_callback(data) {
        if (data.status) {
            notify('success', data.message, 'Admin Theme Update');
            // location.reload();
        } else {
            notify('error', data.message, 'Admin Theme Update');
        }
    }
    // theme colors callback
    function theme_colors_callback(data) {
        if (data.status) {
            notify('success', data.message, 'Theme Colors Update');
            // location.reload();
        } else {
            notify('error', data.message, 'Theme Colors Update');
        }
    }

    // color picker  with html  view  
    $(document).on("change", "input[type=color]", function() {
        $('#' + $(this).data('input-for')).val($(this).val());
    });
    //set color 
    $(document).on('click', '.set_color', function() {
        $(this).parent('div').find('input[type=color]').val(this.value);
    });

    //reset image inputs 
    $(document).on('click', '[data-reset-url]', function() {
        $('#' + $(this).data('reset-preview')).attr('src', $(this).data('reset-url'));
        $('#' + $(this).attr('data-reset-inputID')).val(null);
    });
</script>
@stop
<!-- BEGIN: page JS -->