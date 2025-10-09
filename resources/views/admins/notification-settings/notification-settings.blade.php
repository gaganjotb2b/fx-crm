@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Notification template settings')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css') }}">
<link rel="preconnect" href="https://fonts.gstatic.com">
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}"> --}}
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/fonts/font-awesome/css/font-awesome.min.css') }}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
<style>
    .dataTables_info {
        margin: 1rem !important;
    }

    .dataTables_paginate {
        margin: 1rem !important;
    }

    .light-layout .text-body-heading {
        /* color: #5e5873; */
        color: white;
    }

    /* td,
    th {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    } */
    .mt-2.kyc-document-inmodal {
        position: absolute;
        top: 0;
        bottom: 134px;
        left: 0;
        right: 0;
        background-color: #283046;
        z-index: 10;
        display: none;
    }

    .dark-layout .page-trader-admin .datatables-ajax tr,
    .dark-layout .page-ib-admin .datatables-ajax tr,
    .dark-layout .page-ib-admin .datatables-ajax td,
    .dark-layout .page-trader-admin .datatables-ajax td {
        background-color: #323c59 !important;
    }

    .table.table-responsive.tbl-balance thead,
    .table.table-responsive.tbl-balance tbody,
    .table.table-responsive.tbl-balance tfoot,
    .table.table-responsive.tbl-balance tr,
    .table.table-responsive.tbl-balance td,
    .table.table-responsive.tbl-balance th {
        border-color: inherit;
        border-style: solid;
        border-width: 0;
        border: none;
    }

    .table.table-responsive.tbl-trader-details thead,
    .table.table-responsive.tbl-trader-details tbody,
    .table.table-responsive.tbl-trader-details tfoot,
    .table.table-responsive.tbl-trader-details tr,
    .table.table-responsive.tbl-trader-details td,
    .table.table-responsive.tbl-trader-details th {
        border-color: inherit;
        border-style: solid;
        border-width: 0;
        border: none;
    }
</style>
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content page-trader-admin">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('client-management.Notification Template') }}
                        </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}">{{ __('client-management.Home') }}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#">{{ __('client-management.settings') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('client-management.Notification Template') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                        <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="app-todo.html"><i class="me-1" data-feather="info"></i>
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="app-chat.html"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a></div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Ajax Sourced Server-side -->
                <section id="ajax-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom d-flex justfy-content-between">
                                    <h4 class="card-title">{{ __('client-management.Report Filter') }}</h4>
                                    <div class="d-flex justify-content-between">
                                        <div class="btn-exports me-1">

                                            <button type="button" class="btn btn-primary ms-1 d-none" data-bs-toggle="modal" data-bs-target="#add-new-trader"><i data-feather='plus'></i>Add New Template</button>

                                        </div>
                                        <div class="btn-exports">
                                            <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                                <option value="download" data-icon="download" selected>
                                                    {{ __('client-management.Export') }}
                                                </option>
                                                <option value="csv" data-icon="file">CSV</option>
                                                <option value="excel" data-icon="file">Excel</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--Search Form -->
                                <div class="card-body mt-2">
                                    <form class="dt_adv_search" method="POST" id="filter-form">
                                        <div class="row g-1">
                                            <div class="col-md-4">
                                                <!-- filter by category -->
                                                <label class="form-label" for="category">Notification Type</label>
                                                <select class="select2 form-select" id="notification-type" name="notification-type">
                                                    <option value="">{{ __('client-management.All') }}</option>

                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <!-- filter by kyc status -->
                                                <label id="template_status" class="form-label">Status</label>
                                                <select class="select2 form-select" id="template_status" name="template_status">
                                                    <option value="">{{ __('client-management.All') }}</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Disabled</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <!-- filter by active status -->
                                                <label class="form-label" for="active-status">Client Type</label>
                                                <select class="select2 form-select" id="active-status" name="active_status">
                                                    <option value="">{{ __('client-management.All') }}</option>
                                                    <option value="trader">Trader</option>
                                                    <option value="ib">IB</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row g-1 mt-1">
                                            <div class="col-md-4 mb-2">
                                                <!-- filter by joining date -->
                                                <label class="form-label">Create Date</label>
                                                <div class="mb-0">
                                                    <input type="text" class="form-control dt-date flatpickr-range dt-input" data-column="5" placeholder="StartDate to EndDate" data-column-index="4" name="dt_date" />
                                                    <input type="hidden" class="form-control dt-date start_date dt-input" data-column="5" data-column-index="4" name="value_from_start_date" />
                                                    <input type="hidden" class="form-control dt-date end_date dt-input" name="value_from_end_date" data-column="5" data-column-index="4" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <!-- filter reset button -->
                                                <label for=""></label>
                                                <button id="btn-reset" type="button" class="btn btn-secondary form-control" data-column="4" data-column-index="3">{{ __('client-management.Reset') }}</button>
                                            </div>
                                            <div class="col-md-4">
                                                <!-- filter submit button -->
                                                <label for=""></label>
                                                <button id="btn-filter" type="button" class="btn btn-primary form-control" data-column="4" data-column-index="3">{{ __('client-management.Filter') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <hr class="my-0" />
                            </div>
                            <div class="card p-2">
                                <div class="card-datatable table-responsive">
                                    <!-- main datatable -->
                                    <table class="datatables-ajax table w-100" id="notification-template">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Subject</th>
                                                <th>notification body</th>
                                                <th>notification footer</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Ajax Sourced Server-side -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
    <!-- modal edit template -->
    <div class="modal fade text-start modal-primary" id="add-new-template-modal" tabindex="-1" aria-labelledby="myModalLabel160" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="{{ route('admin.comment-trader-admin-form') }}" method="post" id="form-add-comment">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel160">Comment to - <span class="comment-to"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- temp subject-->
                        <div class="form-group">
                            <label for="notification-subject" class="form-label">Notification subject</label>
                            <input type="text" name="notification_subject" id="nofitication-subject" class="form-input form-control">
                        </div>
                        <!-- Snow Editor start -->
                        <section class="snow-editor ">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Template Body</h4>
                                        </div>
                                        <div class="card-body">
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
                                                                        <option value="inconsolata">Inconsolata
                                                                        </option>
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
                                                            <div class="editor" style="min-height:150px">

                                                            </div>
                                                            <textarea name="comment" style="display: none;" id="text_quill"></textarea>
                                                            <input type="hidden" name="trader_id" id="trader-id">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Snow Editor end -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="save-comment-btn" onclick="_run(this)" data-el="fg" data-form="form-add-comment" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="create_new_comment_call_back" data-btnid="save-comment-btn">Save
                            Comment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal update comments -->
    <div class="modal fade text-start modal-primary" id="edit-new-template-modal" tabindex="-1" aria-labelledby="myModalLabel160" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="{{ route('admin.settings.notification-templates-update') }}" method="post" id="notification-update-form" enctype="multipart/form-data">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel160">Comment update to - <span class="comment-to"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- temp subject-->
                        <div class="form-group mx-2">
                            <label for="notification_subject" class="form-label">Notification subject</label>
                            <input type="text" name="notification_subject" id="notificationSubject" class="form-input form-control notificationSub">
                        </div>
                        <!-- Snow Editor start -->
                        <section class="snow-editor m-2">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label">Notification Body</label>
                                    <div id="snow-wrapper">
                                        <div id="snow-container-update">
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
                                                        <option value="inconsolata">Inconsolata
                                                        </option>
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
                                            <div class="editor" style="min-height:150px">

                                            </div>
                                            <textarea name="notification_body" style="display: none;" id="text_quill_update"></textarea>
                                            <input type="hidden" name="trader_id" id="trader-id-update">
                                            <input type="hidden" name="notification_id" id="notification-id">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Snow Editor end -->
                        <div class="form-group mx-2">
                            <label for="notification_footer" class="form-label">Notification Footer</label>
                            <input type="text" name="notification_footer" id="notificationFooter" class="form-input form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="dataId" id="dataId">
                        <button type="button" class="btn btn-primary me-1 mb-1" id="notificationUpdateBtn" onclick="_run(this)" data-el="fg" data-form="notification-update-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="notificationUpdateCallBack" data-btnid="notificationUpdateBtn">Save Change</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal sending mail-->
    <div class="modal fade text-start modal-success" id="send-mail-pass" tabindex="-1" aria-labelledby="mail-sending-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mail-sending-modal">Sending Mail.....</h5>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <p class="text-warning">Please wait, While we sending mail to - user.</p>
                        <div class="spinner-border text-success" style="width: 3rem; height: 3rem" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>


    @stop
    <!-- BEGIN: vendor JS -->
    @section('vendor-js')
    <script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
    @stop
    <!-- END: vendor JS -->
    <!-- BEGIN: Page vendor js -->
    @section('page-vendor-js')
    <script src="{{asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js')}}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>

    <script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
    <!-- datatable -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>

    <script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>

    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('/common-js/finance.js') }}"></script>
    <script src="{{asset('trader-assets/assets/js/scripts/pages/file-upload-with-form.js')}}"></script>
    @stop
    <!-- END: page vendor js -->
    <!-- BEGIN: page JS -->
    @section('page-js')
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/common-ajax.js') }}"></script>
    <script>
        // quil editor
        var snowEditor;
        var update_editor;
        (function(window, document, $) {
            'use strict';

            var Font = Quill.import('formats/font');
            Font.whitelist = ['sofia', 'slabo', 'roboto', 'inconsolata', 'ubuntu'];
            Quill.register(Font, true);
            // Snow Editor for comment

            snowEditor = new Quill('#snow-container .editor', {
                bounds: '#snow-container .editor',
                modules: {
                    formula: true,
                    syntax: true,
                    toolbar: '#snow-container .quill-toolbar'
                },
                theme: 'snow'
            });

            // comment update editor
            update_editor = new Quill('#snow-container-update .editor', {
                bounds: '#snow-container-update .editor',
                modules: {
                    formula: true,
                    syntax: true,
                    toolbar: '#snow-container-update .quill-toolbar'
                },
                theme: 'snow'
            });
            var editors = [snowEditor, update_editor];
        })(window, document, jQuery);

        snowEditor.on('text-change', function(delta, oldDelta, source) {

            $('#text_quill').val(snowEditor.container.firstChild.innerHTML);
        });

        // for update comment
        update_editor.on('text-change', function(delta, oldDelta, source) {
            console.log(update_editor.container.firstChild.innerHTML);
            $('#text_quill_update').val(update_editor.container.firstChild.innerHTML);
        });
    </script>

    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-quill-editor.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js') }}"></script>
    <script src="{{ asset('admin-assets/src/js/scripts/forms/pickers/custom-form-picker.js') }}"></script>
    <script src="{{ asset('/common-js/copy-js.js') }}"></script>
    <script src="{{ asset('/common-js/password-gen.js') }}"></script>
    <script src="{{asset('admin-assets/app-assets/js/scripts/components/components-navs.js')}}"></script>
    <script>
        // datatable function
        var $datatable = $("#notification-template").fetch_data({
            url: '/admin/settings/notification-templates/datatable',
            columns: [{
                    "data": "type"
                },
                {
                    "data": "subject"
                },
                {
                    'data': 'notification_body'
                },
                {
                    'data': 'notification_footer'
                },
                {
                    'data': 'status'
                },
                {
                    'data': 'action'
                }
            ]
        })
        // edit template button click / get data
        $(document).on("click", '.btn-edit-template', function() {
            let id = $(this).data('id');

            $('#edit-new-template-modal').modal('show');
            $.ajax({
                url: '/admin/settings/notification-templates/edit/' + id,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // console.log(id);
                    $('#notificationSubject').val(data.description);
                    $(".ql-editor").html(data.notification_body);
                    $("#notificationFooter").val(data.notification_footer);
                    $("#dataId").val(id);
                }
            });

        });
        //data-update
        function notificationUpdateCallBack(data) {
            if (data.status == true) {
                notify('success', data.message, 'Notification update');
                $("#edit-new-template-modal").modal('hide');
                $datatable.draw();
            } else {
                notify('error', data.message, 'Notification update');
            }
            $.validator("notification-update-form", data.errors);
        }
    </script>
    @stop
    <!-- BEGIN: page JS -->