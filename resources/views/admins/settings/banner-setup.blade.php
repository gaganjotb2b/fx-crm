@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Banner Setup')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/fonts/font-awesome/css/font-awesome.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/jstree.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
<!-- number input -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')}}">
<!-- file uploader -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-tree.css')}}">

<!-- file uploader -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css')}}">
<style>
    .dark-layout .card {
        background-color: #161d31;
        box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.24);
    }

    .dropzone {
        min-height: 156px;
        border: 1px solid var(--custom-primary) !important;
        background: #f8f8f8;
        position: relative;
        border-radius: 5px;
    }

    .dz-message-label {
        font-size: 1rem !important;
    }

    .card .card-title {
        font-weight: 500;
        font-size: 1.2rem !important
    }

    .dropzone .dz-message {
        color: #d1b970 !important;
    }

    .bg-facebook {
        background-color: #2e3750 !important;
    }

    .dark-layout .bs-stepper .bs-stepper-header .step .step-trigger .bs-stepper-label .bs-stepper-subtitle {
        color: #b0b3ba;
    }

    .dropzone .dz-message::before {
        display: none !important;
    }

    .dropzone-upload-icon {
        width: 50px;
        height: 50px;
        color: #bfaa6b;
        position: absolute;
        top: 40%;
        left: 40%;
    }

    .dz-message-label {
        font-size: 1rem !important;
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
        <div class="content-body">
            <!-- Modern Horizontal Wizard -->
            <section class="modern-horizontal-wizard">
                <div class="bs-stepper wizard-modern modern-wizard-example">
                    <div class="bs-stepper-header">
                        <div class="step" data-target="#ib-banner-details" role="tab" id="ib-banner-details-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="file-text" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">IB Banner</span>
                                    <span class="bs-stepper-subtitle">IB Banner Setup</span>
                                </span>
                            </button>
                        </div>
                        <div class="line">
                            <i data-feather="chevron-right" class="font-medium-2"></i>
                        </div>
                        <div class="step" data-target="#trader-banner-details" role="tab" id="trader-banner-details-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="user" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Trader Banner</span>
                                    <span class="bs-stepper-subtitle">Trader Banner Setup</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <div id="ib-banner-details" class="content" role="tabpanel" aria-labelledby="ib-banner-details-trigger">
                            <div class="content-body row">
                                <!-- Vertical Right Tabs start -->
                                <div class="col-xl-3 col-lg-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">{{__('category.Choose a banner size')}}</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="nav-vertical">
                                                <ul class="nav nav-tabs nav-left flex-column w-100" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active tab-banner-link ib_banner_link1" data-use_for="ib" data-size="160x600" data-bs-toggle="tab" aria-controls="banner_160_600" href="#banner_160_600" role="tab" aria-selected="true">Banner 160 X 600</a>
                                                        <a class="nav-link tab-banner-link" data-use_for="ib" data-size="200x200" data-bs-toggle="tab" aria-controls="ib_banner_200_200" href="#ib_banner_200_200" role="tab" aria-selected="false">Banner 200 X 200</a>
                                                        <a class="nav-link tab-banner-link" data-use_for="ib" data-size="250x250" data-bs-toggle="tab" aria-controls="ib_banner_250_250" href="#ib_banner_250_250" role="tab" aria-selected="false">Banner 250 X 250</a>
                                                        <a class="nav-link tab-banner-link" data-use_for="ib" data-size="300x250" data-bs-toggle="tab" aria-controls="ib_banner_300_250" href="#ib_banner_300_250" role="tab" aria-selected="false">Banner 300 X 250</a>
                                                        <a class="nav-link tab-banner-link" data-use_for="ib" data-size="300x600" data-bs-toggle="tab" aria-controls="ib_banner_300_600" href="#ib_banner_300_600" role="tab" aria-selected="false">Banner 300 X 600</a>

                                                        <a class="nav-link tab-banner-link" data-use_for="ib" data-size="300x1050" data-bs-toggle="tab" aria-controls="ib_banner_300x1050" href="#ib_banner_300_1050" role="tab" aria-selected="false">Banner 300 X 1050</a>
                                                        <a class="nav-link tab-banner-link" data-use_for="ib" data-size="600x90" data-bs-toggle="tab" aria-controls="ib_banner_600_90" href="#ib_banner_600_90" role="tab" aria-selected="false">Banner 600 X 90</a>
                                                        <a class="nav-link tab-banner-link" data-use_for="ib" data-size="728x90" data-bs-toggle="tab" aria-controls="ib_banner_728_90" href="#ib_banner_728_90" role="tab" aria-selected="false">Banner 728 X 90</a>
                                                        <a class="nav-link tab-banner-link" data-use_for="ib" data-size="980x90" data-bs-toggle="tab" aria-controls="ib_banner_980_90" href="#ib_banner_980_90" role="tab" aria-selected="false">Banner 980 X 90</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- tab content -->
                                <div class="col-xl-9 col-lg-9">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <!-- tab banner 160 x 600 -->
                                                <div class="tab-pane active" id="banner_160_600" role="tabpanel" aria-labelledby="banner_160_600">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 160 X 600</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_160_600_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 160 X 600 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_160_600_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 160 X 600 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_160_600_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 160 X 600 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- tab banner 200 x 200 -->
                                                <div class="tab-pane" id="ib_banner_200_200" role="tabpanel" aria-labelledby="ib_banner_200_200">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 200 X 200</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_200_200_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 200 X 200 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_200_200_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 200 X 200 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_200_200_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 200 X 200 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- tab banner 250 x 250 -->
                                                <div class="tab-pane" id="ib_banner_250_250" role="tabpanel" aria-labelledby="ib_banner_250_250">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 250 X 250</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_250_250_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 250 X 250 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_250_250_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 250 X 250 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_250_250_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 250 X 250 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- tab banner 300 x 250 -->
                                                <div class="tab-pane" id="ib_banner_300_250" role="tabpanel" aria-labelledby="ib_banner_300_250">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 300 X 250</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_300_250_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 250 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_300_250_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 250 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_300_250_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 250 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- tab banner 300 x 600 -->
                                                <div class="tab-pane" id="ib_banner_300_600" role="tabpanel" aria-labelledby="ib_banner_300_600">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 300 X 600</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_300_600_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 600 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_300_600_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 600 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_300_600_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 600 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>


                                                <!-- tab banner 300 x 1050 -->
                                                <div class="tab-pane" id="ib_banner_300_1050" role="tabpanel" aria-labelledby="ib_banner_300_1050">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 300 X 1050</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_300_1050_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 1050 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_300_1050_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 1050 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_300_1050_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 1050 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- tab banner 600 x 90 -->
                                                <div class="tab-pane" id="ib_banner_600_90" role="tabpanel" aria-labelledby="ib_banner_600_90">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 600 X 90</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_600_90_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 600 X 90 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_600_90_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 600 X 90 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_600_90_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 600 X 90 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- tab banner 728 x 90 -->
                                                <div class="tab-pane" id="ib_banner_728_90" role="tabpanel" aria-labelledby="ib_banner_728_90">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 728 X 90</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_728_90_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 728 X 90 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_728_90_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 728 X 90 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_728_90_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 728 X 90 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- tab banner 980 x 90 -->
                                                <div class="tab-pane" id="ib_banner_980_90" role="tabpanel" aria-labelledby="ib_banner_980_90">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 980 X 90</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_980_90_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 980 X 90 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_980_90_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 980 X 90 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="dpz_980_90_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="ib">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 980 X 90 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Vertical Right Tabs ends -->
                            </div>
                        </div>
                        <div id="trader-banner-details" class="content" role="tabpanel" aria-labelledby="trader-banner-details-trigger">
                            <div class="content-body row">
                                <!-- Vertical Right Tabs start -->
                                <div class="col-xl-3 col-lg-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">{{__('category.Choose a banner size')}}</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="nav-vertical">
                                                <ul class="nav nav-tabs nav-left flex-column w-100" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active tab-banner-link trader_banner_link1" data-use_for="trader" data-size="160x600" data-bs-toggle="tab" aria-controls="trader_banner_160_600" href="#trader_banner_160_600" role="tab" aria-selected="true">Banner 160 X 600</a>
                                                        <a class="nav-link tab-banner-link" data-use_for="trader" data-size="200x200" data-bs-toggle="tab" aria-controls="trader_banner_200_200" href="#trader_banner_200_200" role="tab" aria-selected="false">Banner 200 X 200</a>
                                                        <a class="nav-link tab-banner-link" data-use_for="trader" data-size="250x250" data-bs-toggle="tab" aria-controls="trader_banner_250_250" href="#trader_banner_250_250" role="tab" aria-selected="false">Banner 250 X 250</a>
                                                        <a class="nav-link tab-banner-link" data-use_for="trader" data-size="300x250" data-bs-toggle="tab" aria-controls="trader_banner_300_250" href="#trader_banner_300_250" role="tab" aria-selected="false">Banner 300 X 250</a>
                                                        <a class="nav-link tab-banner-link" data-use_for="trader" data-size="300x600" data-bs-toggle="tab" aria-controls="trader_banner_300_600" href="#trader_banner_300_600" role="tab" aria-selected="false">Banner 300 X 600</a>

                                                        <a class="nav-link tab-banner-link" data-use_for="trader" data-size="300x1050" data-bs-toggle="tab" aria-controls="trader_banner_300x1050" href="#trader_banner_300_1050" role="tab" aria-selected="false">Banner 300 X 1050</a>
                                                        <a class="nav-link tab-banner-link" data-use_for="trader" data-size="600x90" data-bs-toggle="tab" aria-controls="trader_banner_600_90" href="#trader_banner_600_90" role="tab" aria-selected="false">Banner 600 X 90</a>
                                                        <a class="nav-link tab-banner-link" data-use_for="trader" data-size="728x90" data-bs-toggle="tab" aria-controls="trader_banner_728_90" href="#trader_banner_728_90" role="tab" aria-selected="false">Banner 728 X 90</a>
                                                        <a class="nav-link tab-banner-link" data-use_for="trader" data-size="980x90" data-bs-toggle="tab" aria-controls="trader_banner_980_90" href="#trader_banner_980_90" role="tab" aria-selected="false">Banner 980 X 90</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- tab content -->
                                <div class="col-xl-9 col-lg-9">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="trader_banner_160_600" role="tabpanel" aria-labelledby="trader_banner_160_600">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 160 X 600</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_160_600_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 160 X 600 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_160_600_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 160 X 600 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_160_600_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 160 X 600 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="trader_banner_200_200" role="tabpanel" aria-labelledby="trader_banner_200_200">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 200 X 200</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_200_200_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 200 X 200 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_200_200_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 200 X 200 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_200_200_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 200 X 200 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="trader_banner_250_250" role="tabpanel" aria-labelledby="trader_banner_250_250">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 250 X 250</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_250_250_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 250 X 250 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_250_250_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 250 X 250 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_250_250_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 250 X 250 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- tab banner 300 x 250 -->
                                                <div class="tab-pane" id="trader_banner_300_250" role="tabpanel" aria-labelledby="trader_banner_300_250">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 300 X 250</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_300_250_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 250 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_300_250_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 250 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_300_250_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 250 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- tab banner 300 x 600 -->
                                                <div class="tab-pane" id="trader_banner_300_600" role="tabpanel" aria-labelledby="trader_banner_300_600">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 300 X 600</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_300_600_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 600 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_300_600_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 600 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_300_600_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 600 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>



                                                <!-- tab banner 300 x 1050 -->
                                                <div class="tab-pane" id="trader_banner_300_1050" role="tabpanel" aria-labelledby="trader_banner_300_1050">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 300 X 1050</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_300_1050_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 1050 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_300_1050_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 1050 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_300_1050_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 300 X 1050 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- tab banner 600 x 90 -->
                                                <div class="tab-pane" id="trader_banner_600_90" role="tabpanel" aria-labelledby="trader_banner_600_90">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 600 X 90</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_600_90_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 600 X 90 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_600_90_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 600 X 90 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_600_90_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 600 X 90 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- tab banner 728 x 90 -->
                                                <div class="tab-pane" id="trader_banner_728_90" role="tabpanel" aria-labelledby="trader_banner_728_90">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 728 X 90</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_728_90_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 728 X 90 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_728_90_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 728 X 90 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_728_90_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 728 X 90 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- tab banner 980 x 90 -->
                                                <div class="tab-pane" id="trader_banner_980_90" role="tabpanel" aria-labelledby="trader_banner_980_90">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <h4 class="card-title">{{__('category.Upload Banner')}} 980 X 90</h4>
                                                        </div>
                                                        <div class="col-lg-4 d-none">
                                                            <div class="language-section">
                                                                <select name="language" class="form-control mb-md filter-error select2 language">
                                                                    <option value="">Select Language</option>
                                                                    <option value="english">English</option>
                                                                    <option value="arabic">Arabic</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <!-- col 1 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_980_90_col_1" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 980 X 90 banner 1.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 2 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_980_90_col_2" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 980 X 90 banner 2.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- col 3 -->
                                                        <div class="col-lg-4 mb-2">
                                                            <form action="#" method="post" class="dropzone dropzone-area" id="trader_dpz_980_90_col_3" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                @csrf
                                                                <input type="hidden" name="choosen_language" class="choosen_language" value="">
                                                                <input type="hidden" name="use_for" value="trader">
                                                                <div class="dz-message">
                                                                    <div class="dz-message-label">Drop your 980 X 90 banner 3.</div>
                                                                    <i class="dropzone-upload-icon" data-feather='upload'></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mt-4">
                                                        <table class="datatables-ajax table banners-table-160-600">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('category.Banner')}} 1</th>
                                                                    <th>{{__('category.Banner')}} 2</th>
                                                                    <th>{{__('category.Banner')}} 3</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- load all banners here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Vertical Right Tabs ends -->
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
            <!-- /Modern Horizontal Wizard -->

        </div>
    </div>
</div>
<!-- END: Content-->
<!-- Modal -->
<div class="modal fade text-start modal-primary" id="kyc-decline-mail-modal" tabindex="-1" aria-labelledby="myModalLabel160" aria-hidden="true">
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
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js')}}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
<!-- datatable -->
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>

<!-- picker js -->
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- number input -->
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>
<!-- js tree -->
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/jstree.min.js')}}"></script>

<!-- file uploader -->
<script src="{{asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/banner-upload.js')}}"> </script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-select2.js')}}"></script>
<!-- <script src="{{asset('admin-assets/app-assets/js/scripts/components/components-tooltips.js')}}"></script> -->
<script>
    $(document).on('change', '.language', function() {
        var language = $(this).val();
        $('.choosen_language').val(language);
    });
</script>

@stop
<!-- BEGIN: page JS -->