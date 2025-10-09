@extends('layouts.system-layout')
@section('title','Software Setting')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css')}}">
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
                        <h2 class="content-header-title float-start mb-0">Software Setting</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">Configurations</a></li>
                                <li class="breadcrumb-item active">Software Setting</li>
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <div class="card my-0 py-0">
                                <div class="card-body my-0 py-0">
                                    <h4 class="card-title">Software Settings</h4>
                                </div>
                            </div>
                        </div>
                        <!-- sofware setting form -->
                        <div class="mt-2 pt-50" method="POST" enctype="multipart/form-data" id="software-setting-form">
                            @csrf
                            <div class="card-body py-2 my-25">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- Modern Horizontal Wizard -->
                                        <section class="modern-horizontal-wizard">
                                            <div class="bs-stepper wizard-modern modern-wizard-example">
                                                <div class="bs-stepper-header bg-light-primary">
                                                    <!-- step crm setup -->
                                                    <div class="step" data-target="#account-details-modern" role="tab" id="account-details-modern-trigger">
                                                        <button type="button" class="step-trigger">
                                                            <span class="bs-stepper-box">
                                                                <i data-feather="file-text" class="font-medium-3"></i>
                                                            </span>
                                                            <span class="bs-stepper-label">
                                                                <span class="bs-stepper-title">CRM</span>
                                                                <span class="bs-stepper-subtitle">Setup CRM Default</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="line">
                                                        <i data-feather="chevron-right" class="font-medium-2"></i>
                                                    </div>
                                                    <!-- required fields -->
                                                    <div class="step" data-target="#personal-info-modern" role="tab" id="personal-info-modern-trigger">
                                                        <button type="button" class="step-trigger">
                                                            <span class="bs-stepper-box">
                                                                <i data-feather="user" class="font-medium-3"></i>
                                                            </span>
                                                            <span class="bs-stepper-label">
                                                                <span class="bs-stepper-title">Required Field</span>
                                                                <span class="bs-stepper-subtitle">Field Required Whene Signup</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="line">
                                                        <i data-feather="chevron-right" class="font-medium-2"></i>
                                                    </div>
                                                    <!-- CRM Version -->
                                                    <div class="step" data-target="#address-step-modern" role="tab" id="address-step-modern-trigger">
                                                        <button type="button" class="step-trigger">
                                                            <span class="bs-stepper-box">
                                                                <i data-feather="map-pin" class="font-medium-3"></i>
                                                            </span>
                                                            <span class="bs-stepper-label">
                                                                <span class="bs-stepper-title">Version</span>
                                                                <span class="bs-stepper-subtitle">CRM Version Setup</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="line">
                                                        <i data-feather="chevron-right" class="font-medium-2"></i>
                                                    </div>
                                                    <!-- crm security -->
                                                    <div class="step" data-target="#social-links-modern" role="tab" id="social-links-modern-trigger">
                                                        <button type="button" class="step-trigger">
                                                            <span class="bs-stepper-box">
                                                                <i data-feather="link" class="font-medium-3"></i>
                                                            </span>
                                                            <span class="bs-stepper-label">
                                                                <span class="bs-stepper-title">Securiy</span>
                                                                <span class="bs-stepper-subtitle">Setup CRM Security Level</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- bs stepper content -->
                                                <div class="bs-stepper-content">
                                                    <!-- CRM Ddfault setup -->
                                                    <!-- stepper content -->
                                                    <form id="account-details-modern" action="{{route('system.configarations.software_setting_add')}}" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger">
                                                        @csrf
                                                        <div class="content-header">
                                                            <h5 class="mb-0">CRM</h5>
                                                            <small class="text-muted">Enter / Choose CRM Default Function</small>
                                                        </div>
                                                        <div class="row">
                                                            <!-- crm type -->
                                                            <div class="col-12 col-sm-6 mb-1">
                                                                <label class="form-label" for="type">CRM Type</label>
                                                                <select id="crm_type" class="select2 form-select" name="crm_type">
                                                                    <option value="Default" <?php echo ((isset($configs->crm_type) && strtolower($configs->crm_type) === 'default') ?  'selected="selected"' : '') ?>>Default</option>
                                                                    <option value="Combined" <?php echo ((isset($configs->crm_type) && strtolower($configs->crm_type) === 'combined') ?  'selected="selected"' : '') ?>>Combined</option>
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
                                                            <!-- account limit -->
                                                            <div class="col-12 col-sm-6 mb-1">
                                                                <label class="form-label" for="acc_limit">Account Limit</label>
                                                                <input type="number" class="form-control" id="acc_limit" name="acc_limit" placeholder="0" value="<?php echo (isset($configs->acc_limit) ? $configs->acc_limit : ''); ?>" />
                                                            </div>
                                                            <!-- brutforce attack -->
                                                            <div class="col-12 col-sm-6 mb-1">
                                                                <label class="form-label" for="brute_force_attack">Brute Force Attack</label>
                                                                <input type="number" class="form-control" id="brute_force_attack" name="brute_force_attack" placeholder="0" value="<?php echo (isset($configs->brute_force_attack) ? $configs->brute_force_attack : ''); ?>" />
                                                            </div>
                                                        </div>
                                                        <!-- first step buttons -->
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-outline-secondary btn-prev" disabled type="button">
                                                                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                            </button>
                                                            <!-- button submit first step/ -->
                                                            <button type="button" class="btn btn-primary" id="btn-save-crm-default" onclick="_run(this)" data-form="account-details-modern" data-loading="<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>" data-callback="crm_setup_callback" data-btnid="btn-save-crm-default">
                                                                <span class="align-middle d-sm-inline-block d-none">Save & Next</span>
                                                                <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                                            </button>

                                                            <!-- hidden first step / next -->
                                                            <button class="btn btn-primary btn-next visually-hidden" id="crm-next" type="button">
                                                                <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                                <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                                            </button>
                                                        </div>
                                                    </form>
                                                    <!-- Filed required -->
                                                    <!-- stepper content / 2nd step -->
                                                    <form id="personal-info-modern" action="{{route('system.configarations.software_setting_require-field')}}" method="post" class="content" role="tabpanel" aria-labelledby="personal-info-modern-trigger">
                                                        @csrf
                                                        <div class="content-header">
                                                            <h5 class="mb-0">Require Fields</h5>
                                                            <small>Enter / Choose Field Those Require.</small>
                                                        </div>
                                                        <div class="row">
                                                            <!-- Phone Required -->
                                                            <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                <div class="card-body pb-0 social-media-card">
                                                                    <label class="form-label">Phone Required</label>
                                                                    <div class="social-media-filter border">
                                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Phone Required">
                                                                            <input type="checkbox" class="form-check-input input-filter" name="phone" id="phone" data-value="phone" <?php echo (($required_fields->phone == 1) ?  'checked' : ''); ?> />
                                                                            <label class="form-check-label" for="phone">Phone Required</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Password Required  -->
                                                            <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                <div class="card-body pb-0 social-media-card">
                                                                    <label class="form-label">Password Required</label>
                                                                    <div class="social-media-filter border">
                                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Password Required">
                                                                            <input type="checkbox" class="form-check-input input-filter" name="password" id="password" data-value="password" <?php echo (($required_fields->password == 1) ?  'checked' : ''); ?> />
                                                                            <label class="form-check-label" for="password">Password Required</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Gender Required  -->
                                                            <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                <div class="card-body pb-0 social-media-card">
                                                                    <label class="form-label">Gender Required</label>
                                                                    <div class="social-media-filter border">
                                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Gender Required">
                                                                            <input type="checkbox" class="form-check-input input-filter" name="gender" id="gender" data-value="gender" <?php echo (($required_fields->gender == 1) ?  'checked' : ''); ?> />
                                                                            <label class="form-check-label" for="gender">Gender Required</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Country Required  -->
                                                            <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                <div class="card-body pb-0 social-media-card">
                                                                    <label class="form-label">Country Required</label>
                                                                    <div class="social-media-filter border">
                                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Country Required">
                                                                            <input type="checkbox" class="form-check-input input-filter" name="country" id="country" data-value="country" <?php echo (($required_fields->country == 1) ?  'checked' : ''); ?> />
                                                                            <label class="form-check-label" for="country">Country Required</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- State Required  -->
                                                            <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                <div class="card-body pb-0 social-media-card">
                                                                    <label class="form-label">State Required</label>
                                                                    <div class="social-media-filter border">
                                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="State Required">
                                                                            <input type="checkbox" class="form-check-input input-filter" name="state" id="state" data-value="state" <?php echo (($required_fields->state == 1) ?  'checked' : ''); ?> />
                                                                            <label class="form-check-label" for="state">State Required</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- City Required  -->
                                                            <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                <div class="card-body pb-0 social-media-card">
                                                                    <label class="form-label">City Required</label>
                                                                    <div class="social-media-filter border">
                                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="City Required">
                                                                            <input type="checkbox" class="form-check-input input-filter" name="city" id="city" data-value="city" <?php echo (($required_fields->city == 1) ?  'checked' : ''); ?> />
                                                                            <label class="form-check-label" for="city">City Required</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Address Required  -->
                                                            <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                <div class="card-body pb-0 social-media-card">
                                                                    <label class="form-label">Address Required</label>
                                                                    <div class="social-media-filter border">
                                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Address Required">
                                                                            <input type="checkbox" class="form-check-input input-filter" name="address" id="address" data-value="address" <?php echo (($required_fields->address == 1) ?  'checked' : ''); ?> />
                                                                            <label class="form-check-label" for="address">Address Required</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Zip Code Required  -->
                                                            <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                <div class="card-body pb-0 social-media-card">
                                                                    <label class="form-label">Zip Code Required</label>
                                                                    <div class="social-media-filter border">
                                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Zip Code Required">
                                                                            <input type="checkbox" class="form-check-input input-filter" name="zip_code" id="zip_code" data-value="zip_code" <?php echo (($required_fields->zip_code == 1) ?  'checked' : ''); ?> />
                                                                            <label class="form-check-label" for="zip_code">Zip Code Required</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- second step buttons -->
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-primary btn-prev" type="button">
                                                                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                            </button>

                                                            <!-- button next and save / 2nd step -->
                                                            <button type="button" class="btn btn-primary" id="btn-save-rquire-field" onclick="_run(this)" data-form="personal-info-modern" data-loading="<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>" data-callback="require_field_setup_callback" data-btnid="btn-save-rquire-field">
                                                                <span class="align-middle d-sm-inline-block d-none">Save & Next</span>
                                                                <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                                            </button>
                                                            <!-- button next / 2nd step -->
                                                            <button class="btn btn-primary btn-next visually-hidden" type="button" id="require-next">
                                                                <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                                <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                                            </button>
                                                        </div>
                                                    </form>
                                                    <!-- 3rd step/ crm version -->
                                                    <div id="address-step-modern" class="content" role="tabpanel" aria-labelledby="address-step-modern-trigger">
                                                        <div class="content-header">
                                                            <h5 class="mb-0">Version</h5>
                                                            <small>Choose CRM Version</small>
                                                        </div>
                                                        <!-- crm version -->
                                                        <div class="row">
                                                            <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                <div class="d-flex flex-column">
                                                                    <label class="form-check-label mb-50" for="crm-version">CRM Version</label>
                                                                    <div class="form-check form-check-success form-switch">
                                                                        <input type="checkbox" {{App\Services\systems\VersionControllService::version_selected()}} class="form-check-input" id="crm-version" />
                                                                        <label class="form-check-label mb-50" for="crm-version">Lite / Pro</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- 3rd step buttons -->
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-primary btn-prev" type="button">
                                                                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                            </button>
                                                            <button class="btn btn-primary btn-next" type="button">
                                                                <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                                <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!-- security settings -->
                                                    <div id="social-links-modern" class="content" role="tabpanel" aria-labelledby="social-links-modern-trigger">
                                                        <div class="content-header">
                                                            <h5 class="mb-0">Social Links</h5>
                                                            <small>Enter Your Social Links.</small>
                                                        </div>
                                                        
                                                        <div class="row mb-3 text-center">
                                                            <h3>Comming soon................</h3>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-primary btn-prev">
                                                                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                            </button>
                                                            <button class="btn btn-success btn-submit">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <!-- /Modern Horizontal Wizard -->
                                        <!-- <div class="row">
                                            <input type="hidden" name="config_id" value="<?= (isset($configs->id) ? $configs->id : '') ?>">
                                            <input type="hidden" name="required_fields_id" value="<?= (isset($required_fields->id) ? $required_fields->id : '') ?>">
                                            <div class="col-12">
                                                <label class="form-label">&nbsp;</label>
                                                <div>
                                                    <button type="submit" class="btn btn-primary" style="float: right">Save changes</button>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/sofware setting form -->
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
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js')}}"></script>
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
<!-- <script src="{{asset('admin-assets/app-assets/js/scripts/pages/page-account-settings-account.js')}}"></script> -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/system-page-configuration.js')}}"></script>
<!-- form hide/show -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/system-config-form.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js')}}"></script>
<script>
    $(document).on('change', "#crm-version", function() {
        let $this = $(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/system/software-settings/version-controll',
            method: 'POST',
            dataType: 'JSON',
            data: {
                version: ($($this).is(":checked")) ? 'pro' : 'lite'
            },
            success: function(data) {
                if (data.status) {
                    notify('success', data.message, 'Version Upgrate')
                } else {
                    notify('error', data.message, 'Version Upgrate');
                }
            }
        })
    })
    // crm setup callback
    function crm_setup_callback(data) {
        if (data.status) {
            notify('success', data.message, 'CRM Default Setup')
            $("#crm-next").trigger('click');
        } else {
            notify('error', data.message, 'CRM Default Setup');
        }
    }
    // required field setup callback
    function require_field_setup_callback(data) {
        if (data.status) {
            notify('success',data.message,'Require Fields');
            $("#require-next").trigger('click');
        }
        else{
            notify('error',data.message,'Require Fields');
        }
    }
</script>
@stop
<!-- BEGIN: page JS -->