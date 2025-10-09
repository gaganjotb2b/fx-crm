@extends('layouts.system-layout')
@section('title', 'Company links setup')
@section('vendor-css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <!-- datatable -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/config-form.css') }}">
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
                            <h2 class="content-header-title float-start mb-0">Links setup</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">{{ __('category.Home') }}</a></li>
                                    <li class="breadcrumb-item"><a href="#">Configurations</a></li>
                                    <li class="breadcrumb-item active">Company links </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1 breadcrumb-right">
                        <div class="dropdown">
                            <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    data-feather="grid"></i></button>
                            <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#"><i
                                        class="me-1" data-feather="info"></i>
                                    <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i
                                        class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a>
                            </div>
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
                                        <h4 class="card-title">Company links setup</h4>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('system.company_links_add') }}" class="mt-2 pt-50" method="POST"
                                id="company_links_form">
                                @csrf
                                <div class="card-body py-2 my-25">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-sm-6 mb-1">
                                                    <label class="form-label" for="aml_policy">AML policy</label>
                                                    <input type="text" class="form-control" id="aml_policy"
                                                        name="aml_policy" placeholder="https://itcorneronline.com"
                                                        value="{{ $company_links->aml_policy ?? '' }}" />
                                                    <span class="text-danger error" id="aml_policy_error"></span>
                                                </div>
                                                <div class="col-12 col-sm-6 mb-1">
                                                    <label class="form-label" for="contact_us">Contact Us</label>
                                                    <input type="text" class="form-control" id="contact_us"
                                                        name="contact_us"
                                                        placeholder="https://itcorneronline.com/contact.php"
                                                        value="{{ $company_links->contact_us ?? '' }}" />
                                                    <span class="text-danger error" id="contact_us_error"></span>
                                                </div>
                                                <div class="col-12 col-sm-6 mb-1">
                                                    <label class="form-label" for="privacy_policy">Privacy Policy</label>
                                                    <input type="text" class="form-control" id="privacy_policy"
                                                        name="privacy_policy"
                                                        placeholder="https://itcorneronline.com/privacy.php"
                                                        value="{{ $company_links->privacy_policy ?? '' }}" />
                                                    <span class="text-danger error" id="privacy_policy_error"></span>
                                                </div>
                                                <div class="col-12 col-sm-6 mb-1">
                                                    <label class="form-label" for="refund_policy">Refund Policy</label>
                                                    <input type="text" class="form-control" id="refund_policy"
                                                        name="refund_policy" placeholder="https://itcorneronline.com"
                                                        value="{{ $company_links->refund_policy ?? '' }}" />
                                                    <span class="text-danger error" id="refund_policy_error"></span>
                                                </div>
                                                <div class="col-12 col-sm-6 mb-1">
                                                    <label class="form-label" for="terms_condition">Terms
                                                        Condition</label>
                                                    <input type="text" class="form-control" id="terms_condition"
                                                        name="terms_condition" placeholder="https://itcorneronline.com"
                                                        value="{{ $company_links->terms_condition ?? '' }}" />
                                                    <span class="text-danger error" id="terms_condition_error"></span>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <label class="form-label">&nbsp;</label>
                                                    <div>
                                                        <button type="button" class="btn btn-primary"
                                                            style="float:right;" id="btn-submit-request"
                                                            data-btnid="btn-submit-request"
                                                            data-callback="companyLinks_call_back"
                                                            data-loading="<i class='fa-spin fas fa-circle-notch'></i>"
                                                            data-form="company_links_form" onclick="_run(this)">Save
                                                            changes</button>
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
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/system-page-configuration.js') }}"></script>
    <!-- form hide/show -->

    <script>
        function companyLinks_call_back(data) {
            console.log(data);
            if (data.status == false) {
                toastr['error']('Failed To Update!', 'Please fix the following error', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                });
                $('.error').html('');
                if (data.errors) {
                    const objectArray = Object.entries(data.errors);
                    objectArray.forEach(([key, value]) => {
                        let istId = key + "_error";
                        $('#' + istId).html(value[0]);
                        console.log(value[0]);
                    });
                }
            } else {
                // $("#company_links_form")[0].reset();
                $('.error').html('');
                toastr['success'](data.message, 'Company Links', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                });
            }
        }
    </script>
@stop
<!-- BEGIN: page JS -->
