@extends('layouts.system-layout')
@section('title','H2Pay Config')
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

<link rel="stylesheet" type="text/css" href="http://127.0.0.1:8000/admin-assets/app-assets/vendors/css/forms/select/select2.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/assets/css/config-form.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/admin.css') }}">
<style>
    h4.card-title.float-start.d-flex {
        padding-top: 8px;
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
                        <h2 class="content-header-title float-start mb-0">Bank</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item active">Help 2 Pay Config</li>
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
                <div class="col-12 col-sm-5">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h4> {{__('ib-management.Note')}}</h4>
                                <code class="bg-light-primary">{{__('ib-management.please read carefully')}}</code>
                            </div>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="border-start-3 border-start-primary p-1 bg-light-primary mb-1">
                                {{__('page.sent_1')}}
                            </div>
                            <div class="border-start-3 border-start-info p-1 bg-light-primary mb-1">
                                {{__('page.sent_2')}}
                            </div>
                            <div class="border-start-3 border-start-danger p-1 bg-light-primary mb-1">
                                {{__('page.sent_3')}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-7">
                    <div class="card">
                        <div class="card-header border-bottom mb-0">
                            <div class="card my-0 py-0 w-100">
                                <div class="card-body my-0 py-0">
                                    <h4 class="card-title float-start d-flex">H2Pay Config</h4>
                                </div>
                            </div>
                        </div>
                        <!--add ib form -->
                        <div class="card mt-0 mb-0">
                            <div class="col-12">
                                <form class="mt-2 pt-50" method="POST" action="{{ route('system.h2pay-config-store') }}" id="h2pay-config-form">
                                    @csrf
                                    <div class="card-body py-2 my-25">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <input type="hidden" name="id" value="{{ $result->id }}">
                                                    <input type="hidden" name="created_by" value="">
                                                    <input type="hidden" name="ip_address" value="">
                                                    <div class="col-12 mt-1">
                                                        <label class="form-label" for="code">Code (Merchent Code)</label>
                                                        <input type="text" class="form-control" id="code" name="code" placeholder="Code (Merchent Code)" value="{{ isset($result->merchent_code) ? $result->merchent_code : '' }}" />
                                                        <span class="text-danger" id="code"></span>
                                                    </div>
                                                    <div class="col-12 mt-1">
                                                        <label class="form-label" for="user_name">User Name</label>
                                                        <input type="text" class="form-control" id="user_name" name="user_name" placeholder="User Name" value="{{ isset($result->user_name) ? $result->user_name : '' }}" />
                                                        <span class="text-danger" id="user_name_error"></span>
                                                    </div>
                                                    <div class="col-12 mt-1">
                                                        <label class="form-label" for="password">Password</label>
                                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="{{ isset($result->password) ? $result->password : '' }}" />
                                                        <span class="text-danger" id="password"></span>
                                                    </div>
                                                    <div class="col-12 mt-1">
                                                        <label class="form-label" for="security_code">Security Code</label>
                                                        <input type="text" class="form-control" id="security_code" name="security_code" placeholder="Security Code" value="{{ isset($result->security_code) ? $result->security_code :'' }}" />
                                                        <span class="text-danger" id="security_code_error"></span>
                                                    </div>
                                                    <div class="col-12 mt-1">
                                                        <label class="form-label" for="api_url">Api Url</label>
                                                        <input type="text" class="form-control" id="api_url" name="api_url" placeholder="Api Url" value="{{ isset($result->api_url) ? $result->api_url : ''}}" />
                                                        <span class="text-danger" id="api_url_error"></span>
                                                    </div>
                                                    <div id="loader" data-loader="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"></div>
                                                    <div class="col-12">
                                                        <label class="form-label">&nbsp;</label>
                                                        <div>
                                                            <button type="button" class="btn btn-primary" style="float: right" id="h2pay-submit-btn" data-form="h2pay-config-form" data-loader="loader">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                            </div>
                        </div>
                        <!--/add ib  form -->
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
<script src="{{asset('common-js/ib-settings.js')}}"></script>
<script src="{{asset('common-js/rz-plugins/rz-ajax.js')}}"></script>
<script>
    $("#h2pay-submit-btn").form_submit({
        form_id: "h2pay-config-form",
        title: 'H2Pay Config',

    }, function(data) {
        console.log(data);
    })
</script>

@stop