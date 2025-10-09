@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Popup Setup')
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

    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/fonts/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/extensions/jstree.min.css') }}">
    <!-- datatable -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <!-- number input -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css') }}">
    <!-- file uploader -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
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
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-tree.css') }}">

    <!-- file uploader -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('common-css/search-dropdown.css') }}">
    <style>
        .dark-layout .dropdown-content {
            background-color: #283046;
            border-color: #404656;
            border-radius: 6px;
        }

        .dropdown-content {
            background-color: #fff;
            border-color: #d8d6de;
            border-radius: 6px;
        }

        .dark-layout .dropdown-content a:hover {
            background-color: #404656;
            color: #fff;
        }

        .dark-layout .dropdown-content a {
            color: #b4b7bd;
        }

        #myInput:focus {
            outline: none;
        }

        .dark-layout #myInput {
            background-image: url('searchicon.png');
            border-bottom: 1px solid;
            border-color: #404656;
            border-radius: 6px;
        }

        .al-fixed-input-error .has-error {
            position: absolute;
            left: auto;
            bottom: auto;
        }

        .position-relative.al-fixed-input-error-select2 {
            margin-bottom: 15px;
        }

        .position-relative.al-fixed-input-error-select2 .has-error {
            position: absolute;
            bottom: -20px;
            left: 0;
        }
    </style>
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-fluid p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Popup Setup</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Settings</a>
                                    </li>
                                    <li class="breadcrumb-item active">Popup Setup</li>
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
                            <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="app-todo.html"><i
                                        class="me-1" data-feather="check-square"></i><span
                                        class="align-middle">Todo</span></a><a class="dropdown-item" href="app-chat.html"><i
                                        class="me-1" data-feather="message-square"></i><span
                                        class="align-middle">Chat</span></a><a class="dropdown-item"
                                    href="app-email.html"><i class="me-1" data-feather="mail"></i><span
                                        class="align-middle">Email</span></a><a class="dropdown-item"
                                    href="app-calendar.html"><i class="me-1" data-feather="calendar"></i><span
                                        class="align-middle">Calendar</span></a></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Upload Popup Image</h4>
                            </div>
                            <hr>
                            <div class="card-body">
                                <form class="tab-pane active" enctype="multipart/form-data" action="#"
                                    method="post" id="home" aria-labelledby="home-tab" role="tabpanel">
                                    @csrf

                                    <div class="row">
                                        <!-- issue date -->
                                        <div class="col-12 col-sm-6 mb-1">
                                            <label for="issue-date"
                                                class="col-sm-6 col-form-label">{{ __('page.issue-date') }} </label>
                                            <input type="text" id="issue-date" class="form-control flatpickr-basic"
                                                placeholder="YYYY-MM-DD" name="issue_date" />
                                        </div>
                                        <!-- expire date -->
                                        <div class="col-12 col-sm-6 mb-1">
                                            <label class="col-sm-6 col-form-label"
                                                for="expire-date">{{ __('page.expire-date') }}</label>
                                            <input type="text" id="expire-date" name="expire_date"
                                                class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                                        </div>
                                        <!-- user type -->
                                        <div class="col-12 col-sm-6 mb-1">
                                            <label for="user-type" class="col-sm-6 col-form-label">User Type</label>
                                            <select class="select2 form-select" id="user-type" name="user_type">
                                                <option value="">Select User Type</option>
                                                <option value="both">Both</option>
                                                <option value="trader">Trader</option>
                                                <option value="ib">IB</option>
                                            </select>
                                        </div>
                                        <!-- status -->
                                        <div class="col-12 col-sm-6 mb-1">
                                            <label for="status"
                                                class="col-sm-6 col-form-label">{{ __('page.status') }}</label>
                                            <select class="select2 form-select" id="status" name="status">
                                                <option value="">Select Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                        <!-- kyc files upload -->
                                        <div class="mb-1 row mt-1 p-0">
                                            <div class="col-sm-12 p-0">
                                                <div class="d-flex">
                                                    <!-- id front part -->
                                                    <div class="w-50">
                                                    </div>
                                                    <div class="w-50">
                                                        <div class="dropzone dropzone-area image-dropzone w-100"
                                                            data-field="front_part" id="id-dropzone"
                                                            enctype="multipart/form-data" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Drag and Drop or click your ID">
                                                            <div class="dz-message">
                                                                <div class="dz-message-label">Drop your popup image</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary float-end text-truncate" type="button"
                                        id="upload-popup-button" data-label="Save Popup Setup" data-form="popup-form"
                                        data-i18n="Save Popup Setup"
                                        data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>">Save
                                        Popup Setup</button>
                                </form>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-12">
                            <div class="card p-2">
                                <div class="card-header" style="padding-left: 10px;">
                                    <h4 class="card-title">Popup Setup</h4>
                                </div>
                                <div class="table-responsive">
                                    <table class="table popup-setup-table scrollbar-primary">
                                        <thead>
                                            <tr>
                                                <th style="width:20% !important;">Image</th>
                                                <th>Issue Date</th>
                                                <th>Expire Date</th>
                                                <th>User Type</th>
                                                <th>Status</th>
                                                <th>{{ __('page.action') }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

    <!-- picker js -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <!-- number input -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js') }}"></script>
    <!-- js tree -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/jstree.min.js') }}"></script>

    <!-- file uploader -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/admin-kyc-upload.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/components/components-navs.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/scripts/pages/file-upload-with-form.js') }}"></script>

    <script src="{{ asset('common-js/search-dropdown.js') }}"></script>
    <script src="{{ asset('common-js/select2-get-both.js') }}"></script>

    <script>
        // start: popup datatable

        var dt_popup_setup;
        $(document).ready(function() {
            // get data by ajax 
            dt_popup_setup = $('.popup-setup-table').DataTable({
                language: {
                    search: "",
                    lengthMenu: " _MENU_ "
                },
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "/admin/settings/popup-setup-fetch-data",
                    "data": function(d) {
                        return $.extend({}, d, {

                        });
                    }
                },
                "columns": [{
                        "data": "image"
                    },
                    {
                        "data": "issue_date"
                    },
                    {
                        "data": "expire_date"
                    },
                    {
                        "data": "user_type"
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": "action"
                    },
                ],
                "order": [
                    [1, 'desc']
                ],
                "drawCallback": function(settings) {
                    var rows = this.fnGetData();
                    if (rows.length !== 0) {
                        feather.replace();
                    }
                }
            });
        });
        // end: popup datatable

        // Popup Setup--------------
        file_upload(
            "/admin/settings/popup-upload", //<-- request URL for processing
            false, //<-- auto process true or false
            ".image-dropzone", //<-- dropzone selector
            "home", //<-- form id/selector
            "#upload-popup-button", //<-- submit button selector
            "Popup Setup", //<-- Notification Title
            false, // multiple: whether to allow multiple file uploads
            null, // datatable: additional parameter, not used in this call
            true // reload: whether to reload after upload
        );

        // Reset form and select elements
        // $("select").val('').trigger('change');
        // $("#home").trigger('reset');

        // view contest modal
        $(document).on("click", ".update-status", function() {
            var popup_id = $(this).data('id');
            let status = $(this).data('status');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: 'POST',
                url: '/admin/settings/popup-update',
                dataType: 'JSON',
                data: {
                    popup_id: popup_id,
                    status: status
                },
                success: function(data) {
                    if (data.status == true) {
                        notify('success', data.message, 'Popup');
                        dt_popup_setup.draw();
                    } else {
                        notify('error', data.message, 'Popup');
                    }
                }
            });
        });
    </script>
@stop
<!-- BEGIN: page JS -->
