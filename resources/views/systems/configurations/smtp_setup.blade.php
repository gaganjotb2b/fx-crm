@extends('layouts.system-layout')
@section('title','SMTP Setup')
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
                        <h2 class="content-header-title float-start mb-0">SMTP Setup</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">Configurations</a></li>
                                <li class="breadcrumb-item active">SMTP Setup</li>
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
                                    <h4 class="card-title">SMTP Setup</h4>
                                </div>
                            </div>
                        </div>
                        <!-- smtp setup form -->
                        <form action="{{route('system.configarations.smtp_setup_add')}}" class="mt-2 pt-50" method="POST" enctype="multipart/form-data" id="smtp-setup-form">
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
                                            <div class="col-12">
                                                <label class="form-label">&nbsp;</label>
                                                <div>
                                                    <button type="submit" class="btn btn-primary" style="float:right;">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--/smtp setup form -->
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
<!-- toaster js -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/system-page-configuration.js')}}"></script>
<!-- form hide/show -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/system-config-form.js')}}"></script>
@stop
<!-- BEGIN: page JS -->