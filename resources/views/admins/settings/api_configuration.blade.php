@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
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
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{__('page.API_Configuration')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{__('category.Settings')}}</a></li>
                                <li class="breadcrumb-item active">{{__('page.API_Configuration')}}</li>
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
                                    <h4 class="card-title">{{__('page.API_Configuration')}}</h4>
                                </div>
                            </div>
                        </div>
                        <!--start API configuration form -->
                        <form action="{{route('admin.settings.api_configuration')}}" class="mt-0 pt-50" method="POST" enctype="multipart/form-data" id="api-configuration-form">
                            @csrf
                            <div class="card-body py-0 my-25">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- platform type -->
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="platform_type">{{__('page.platform')}} {{__('page.type')}}</label>
                                                <select id="platform_type" class="select2 form-select" name="platform_type">
                                                    <option value="MT4" <?php echo ((isset($configs->platform_type) && strtolower($configs->platform_type) === 'mt4') ?  'selected="selected"' : '') ?>>MT4</option>
                                                    <option value="MT5" <?php echo ((isset($configs->platform_type) && strtolower($configs->platform_type) === 'mt5') ?  'selected="selected"' : '') ?>>MT5</option>
                                                    <option value="Both" <?php echo ((isset($configs->platform_type) && strtolower($configs->platform_type) === 'both') ?  'selected="selected"' : '') ?>>Both</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1"></div>
                                            <!--mt4 server type -->
                                            <div class="col-12 col-sm-6 mb-1" id="mt4_server_type">
                                                <label class="form-label" for="mt4_server_type">*MT4 {{__('page.server')}} {{__('page.type')}}</label>
                                                <select class="select2 form-select mt4_server_type" name="mt4_server_type">
                                                    <option value="">Select Server Type</option>
                                                    <option value="Manager API" <?php echo ((isset($server_type->mt4_server_type) && strtolower($server_type->mt4_server_type) === 'manager api') ?  'selected="selected"' : '') ?>>Manager API</option>
                                                    <option value="Web App" <?php echo ((isset($server_type->mt4_server_type) && strtolower($server_type->mt4_server_type) === 'web app') ?  'selected="selected"' : '') ?>>Web App</option>
                                                </select>
                                                <span class="text-danger" id="mt4_server_type_error"></span>
                                            </div>
                                            <!-- mt4 download link -->
                                            <div class="col-12 col-sm-6 mb-1 mt4-download-link">
                                                <label class="form-label" for="mt4-download-link">MT4 {{__('page.download')}} {{__('page.linked-in')}} </label>
                                                <input type="text" class="form-control" id="mt4-download-link" name="mt4_download_link" placeholder="MT4 download link" value="<?php echo (isset($platform_download_link->mt4_download_link) ? $platform_download_link->mt4_download_link : ''); ?>" />
                                                <span class="text-danger" id="mt4-download-link-error"></span>
                                            </div>

                                            <!-- mt4 server type expended for manager api-->
                                            <div class="col-12 col-sm-6 mb-1 mt4_manager_ip_config">
                                                <label class="form-label" for="mt4_server_ip">{{__('page.server')}} IP</label>
                                                <input type="text" class="form-control" id="mt4_server_ip" name="mt4_server_ip" placeholder="mt4_server_ip" value="<?php echo (isset($server_ip->mt4_server_ip) ? $server_ip->mt4_server_ip : ''); ?>" />
                                                <span class="text-danger" id="mt4_server_ip_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1 mt4_manager_ip_config">
                                                <label class="form-label" for="mt4_manager_login">{{__('admin-management.Manager')}} {{__('page.login')}} </label>
                                                <input type="text" class="form-control" id="mt4_manager_login" name="mt4_manager_login" placeholder="mt4_manager_login" value="<?php echo (isset($manager_login->mt4_manager_login) ? $manager_login->mt4_manager_login : ''); ?>" />
                                                <span class="text-danger" id="mt4_manager_login_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1 mt4_manager_ip_config">
                                                <label class="form-label" for="mt4_manager_password">{{__('admin-management.Manager')}} {{__('page.password')}}</label>
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
                                                <label class="form-label" for="mt5_server_type">*MT5 {{__('page.server')}} {{__('page.type')}}</label>
                                                <select class="select2 form-select mt5_server_type" name="mt5_server_type">
                                                    <option value="">Select Server Type</option>
                                                    <option value="Demo" <?php echo ((isset($server_type->mt5_server_type) && strtolower($server_type->mt5_server_type) === 'demo') ?  'selected="selected"' : '') ?>>Demo</option>
                                                    <option value="Live" <?php echo ((isset($server_type->mt5_server_type) && strtolower($server_type->mt5_server_type) === 'live') ?  'selected="selected"' : '') ?>>Live</option>
                                                </select>
                                                <span class="text-danger" id="mt5_server_type_error"></span>
                                            </div>
                                            <!-- mt5 download link -->
                                            <div class="col-12 col-sm-6 mb-1 mt5-download-link">
                                                <label class="form-label" for="mt5-download-link">MT5 {{__('page.download')}} </label>
                                                <input type="text" class="form-control" id="mt5-download-link" name="mt5_download_link" placeholder="MT5 download link" value="<?php echo (isset($platform_download_link->mt5_download_link) ? $platform_download_link->mt5_download_link : ''); ?>" />
                                                <span class="text-danger" id="mt5-download-link-error"></span>
                                            </div>
                                            <!-- mt5 server type expended -->
                                            <div class="col-12 col-sm-6 mb-1 mt5_server_type_config">
                                                <label class="form-label" for="mt5_server_ip">{{__('page.server')}} IP</label>
                                                <input type="text" class="form-control" id="mt5_server_ip" name="mt5_server_ip" placeholder="mt5_server_ip" value="<?php echo (isset($server_ip->mt5_server_ip) ? $server_ip->mt5_server_ip : ''); ?>" />
                                                <span class="text-danger" id="mt5_server_ip_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1 mt5_server_type_config">
                                                <label class="form-label" for="mt5_manager_login">{{__('admin-management.Manager')}} {{__('page.login')}}</label>
                                                <input type="text" class="form-control" id="mt5_manager_login" name="mt5_manager_login" placeholder="mt5_manager_login" value="<?php echo (isset($manager_login->mt5_manager_login) ? $manager_login->mt5_manager_login : ''); ?>" />
                                                <span class="text-danger" id="mt5_manager_login_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1 mt5_server_type_config">
                                                <label class="form-label" for="mt5_manager_password">{{__('admin-management.Manager')}} {{__('page.password')}}</label>
                                                <input type="password" class="form-control" id="mt5_manager_password" name="mt5_manager_password" placeholder="mt5_manager_password" value="<?php echo (isset($manager_password->mt5_manager_password) ? $manager_password->mt5_manager_password : ''); ?>" />
                                                <span class="text-danger" id="mt5_manager_password_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1 mt5_server_type_config">
                                                <label class="form-label" for="mt5_api_password">API {{__('page.password')}}</label>
                                                <input type="password" class="form-control" id="mt5_api_password" name="mt5_api_password" placeholder="mt5_api_password" value="<?php echo (isset($api_password->mt5_api_password) ? $api_password->mt5_api_password : ''); ?>" />
                                                <span class="text-danger" id="mt5_api_password_error"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <input type="hidden" name="config_id" value="<?= (isset($configs->id) ? $configs->id : '') ?>">
                                            <div class="col-12">
                                                <label class="form-label">&nbsp;</label>
                                                <div>
                                                    <button type="submit" class="btn btn-primary" style="float:right;">{{__('page.save-change')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--end API configuration form -->
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
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- toaster js -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-page-configuration.js')}}"></script>
<!-- form hide/show -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-config-form.js')}}"></script>
@stop
<!-- BEGIN: page JS -->