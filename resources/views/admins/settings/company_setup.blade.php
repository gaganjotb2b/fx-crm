@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Company Setup')
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
                        <h2 class="content-header-title float-start mb-0">{{__('page.Company_Setup')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{__('category.Settings')}}</a></li>
                                <li class="breadcrumb-item active">{{__('page.Company_Setup')}}</li>
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
                                    <h4 class="card-title">{{__('page.Company_Setup')}}</h4>
                                </div>
                            </div>
                        </div>
                        <!-- company information setup form -->
                        <form action="{{route('admin.settings.company_setup_add')}}" class="mt-2 pt-50" method="POST" enctype="multipart/form-data" id="company-info-form">
                            @csrf
                            <div class="card-body py-2 my-25">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_name">{{__('page.Company')}} {{__('page.name')}}</label>
                                                <input type="text" class="form-control" id="com_name" name="com_name" placeholder="company name" value="<?php echo (isset($configs->com_name) ? $configs->com_name : ''); ?>" />
                                                <span class="text-danger" id="com_name_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_license">{{__('page.license')}}</label>
                                                <input type="text" class="form-control" id="com_license" name="com_license" placeholder="company license" value="<?php echo (isset($configs->com_license) ? $configs->com_license : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_email_1">{{__('page.Company')}} {{__('page.email')}}({{__('page.Primary')}})</label>
                                                <input type="email" class="form-control" id="com_email_1" name="com_email_1" placeholder="company email" value="<?php echo (isset($com_email->com_email_1) ? $com_email->com_email_1 : ''); ?>" />
                                                <span class="text-danger" id="com_email_1_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_email_2">{{__('page.Company')}} {{__('page.email')}}({{__('page.Secondary')}})</label>
                                                <input type="email" class="form-control" id="com_email_2" name="com_email_2" placeholder="company email" value="<?php echo (isset($com_email->com_email_2) ? $com_email->com_email_2 : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_phone_1">{{__('page.Company')}} {{__('page.phone')}}({{__('page.Primary')}})</label>
                                                <input type="number" class="form-control" id="com_phone_1" name="com_phone_1" placeholder="company contact number" value="<?php echo (isset($com_phone->com_phone_1) ? $com_phone->com_phone_1 : ''); ?>" />
                                                <span class="text-danger" id="com_phone_1_error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_phone_2">{{__('page.Company')}} {{__('page.phone')}}({{__('page.Secondary')}})</label>
                                                <input type="number" class="form-control" id="com_phone_2" name="com_phone_2" placeholder="company contact number" value="<?php echo (isset($com_phone->com_phone_2) ? $com_phone->com_phone_2 : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_website">{{__('page.web')}}</label>
                                                <input type="text" class="form-control" id="com_website" name="com_website" placeholder="company website" value="<?php echo (isset($configs->com_website) ? $configs->com_website : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_authority">{{__('page.Authority')}}</label>
                                                <input type="text" class="form-control" id="com_authority" name="com_authority" placeholder="company authority" value="<?php echo (isset($configs->com_authority) ? $configs->com_authority : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="com_address">{{__('page.address')}}</label>
                                                <input type="text" class="form-control" id="com_address" name="com_address" placeholder="company address" value="<?php echo (isset($configs->com_address) ? $configs->com_address : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="copyright">{{__('page.Copyright')}}</label>
                                                <input type="text" class="form-control" id="copyright" name="copyright" placeholder="copyright" value="<?php echo (isset($configs->copyright) ? $configs->copyright : ''); ?>" />
                                                <span class="text-danger" id="copyright_error"></span>
                                            </div>

                                            <!-- suppert mail -->
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="support-email">{{__('page.support')}} {{__('page.email')}} </label>
                                                <input type="text" class="form-control" id="support-email" name="support_email" placeholder="support email" value="<?php echo (isset($configs->support_email) ? $configs->support_email : ''); ?>" />
                                                <span class="text-danger" id="support-email-error"></span>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="auto-email">{{__('page.Auto')}} {{__('page.email')}}</label>
                                                <input type="text" class="form-control" id="auto-email" name="auto_email" placeholder="Auto Email" value="<?php echo (isset($configs->auto_email) ? $configs->auto_email : ''); ?>" />
                                                <span class="text-danger" id="auto-email-error"></span>
                                            </div>
                                            <!-- social media section start -->
                                            <div class="col-12 col-sm-6 mb-1">
                                                <div class="card-body pb-0 social-media-card">
                                                    <label class="form-label" for="social-media">{{__('page.social-links')}}</label>
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
                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Instagram">
                                                            <input type="checkbox" class="form-check-input input-filter" id="instagram-check" data-value="instagram-check" checked />
                                                            <label class="form-check-label" for="instagram-check"><i class="social_icon" data-feather='instagram'></i></label>
                                                        </div>
                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Line">
                                                            <input type="checkbox" class="form-check-input input-filter" id="line-check" data-value="line-check" checked />
                                                            <label class="form-check-label" for="line-check"><img class="social_icon" style="font-size:1.1rem; height: 1.6rem; width: 1.6rem;" src="{{asset('admin-assets/app-assets/images/icons/social/line.png')}}" alt="Skype"></label>
                                                        </div>
                                                        <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="whatsapp">
                                                            <input type="checkbox" class="form-check-input input-filter" id="whatsapp-check" data-value="whatsapp-check" checked />
                                                            <label class="form-check-label" for="whatsapp-check"><img class="social_icon" style="font-size:1.1rem; height: 1.6rem; width: 2.4rem;" src="{{asset('admin-assets/app-assets/images/icons/social/whatsapp.png')}}" alt="Skype"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clear-fixed"></div>
                                            <div class="col-12 col-sm-6 mb-1" id="facebook">
                                                <label class="form-label" for="facebook">Facebook</label>
                                                <input type="text" class="form-control facebook" name="facebook" placeholder="company facebook account" value="<?php echo (isset($com_social_info->facebook) ? $com_social_info->facebook : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1" id="twitter">
                                                <label class="form-label" for="twitter">Twitter</label>
                                                <input type="text" class="form-control twitter" name="twitter" placeholder="company twitter account" value="<?php echo (isset($com_social_info->twitter) ? $com_social_info->twitter : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1" id="skype">
                                                <label class="form-label" for="skype">Skype</label>
                                                <input type="text" class="form-control skype" name="skype" placeholder="company skype account" value="<?php echo (isset($com_social_info->skype) ? $com_social_info->skype : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1" id="youtube">
                                                <label class="form-label" for="youtube">Youtube</label>
                                                <input type="text" class="form-control youtube" name="youtube" placeholder="company youtube account" value="<?php echo (isset($com_social_info->youtube) ? $com_social_info->youtube : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1" id="telegram">
                                                <label class="form-label" for="telegram">Telegram</label>
                                                <input type="text" class="form-control telegram" name="telegram" placeholder="company telegram account" value="<?php echo (isset($com_social_info->telegram) ? $com_social_info->telegram : ''); ?>" />
                                            </div> 

                                            <div class="col-12 col-sm-6 mb-1" id="instagram">
                                                <label class="form-label" for="instagram">Instagram</label>
                                                <input type="text" class="form-control instagram" name="instagram" placeholder="company instagram account" value="<?php echo (isset($com_social_info->instagram) ? $com_social_info->instagram : ''); ?>" />
                                            </div> 
                                            <div class="col-12 col-sm-6 mb-1" id="line">
                                                <label class="form-label" for="line">Line</label>
                                                <input type="text" class="form-control line" name="line" placeholder="company line account" value="<?php echo (isset($com_social_info->line) ? $com_social_info->line : ''); ?>" />
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1" id="whatsapp">
                                                <label class="form-label" for="whatsapp">Whatsapp</label>
                                                <input type="text" class="form-control whatsapp" name="whatsapp" placeholder="company whatsapp account" value="<?php echo (isset($com_social_info->whatsapp) ? $com_social_info->whatsapp : ''); ?>" />
                                                <span class="text-danger" id="whatsapp_error"></span>
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
                                            <div class="col-12">
                                                <label class="form-label">&nbsp;</label>
                                                <div>
                                                    @if(Auth::user()->hasDirectPermission('create company setup'))
                                                    <button type="button" id="btn-compnay-info" data-btnid="btn-compnay-info" data-callback="company_info_call_back" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-form="company-info-form" data-el="fg" onclick="_run(this)" class="btn btn-primary" style="float: right; width:200px">{{__('page.save-change')}}</button>
                                                    @else

                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--/company information setup form -->
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
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-page-configuration.js')}}"></script>
<!-- form hide/show -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-config-form.js')}}"></script>
<script>
    function company_info_call_back(data){
        if (data.status == false) {
            notify('error', data.message, 'Company Setup');
        }
        if (data.status == true) {
            notify('success', data.message, 'Company Setup');
        }
        $.validator("company-info-form", data.errors);
    }
</script>
@stop
<!-- BEGIN: page JS -->
