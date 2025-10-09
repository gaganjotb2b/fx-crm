@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','KYC Upload')
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
<link rel="stylesheet" type="text/css" href="{{asset('common-css/search-dropdown.css')}}">
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
                        <h2 class="content-header-title float-start mb-0">{{__('admin-menue-left.kyc_upload')}} </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('finance.home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{__('admin-menue-left.kyc_management')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('admin-menue-left.kyc_upload')}} </li>
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
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header d-flex">
                            <h4 id="user-type" class="text-capitalize">---</h4>
                            <h5 id="user-name-top" class="text-capitalize">{{config('app.name')}} User</h5>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="rounded ms-1 dt-trader-img img-finance">
                                <div class="h-100">
                                    <img class="img img-fluid" src="{{ asset('admin-assets/app-assets/images/avatars/' . $avatar) }}" alt="avatar">
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <!-- Name -->
                                <li class="list-group-item d-flex align-items-center">
                                    <span>{{__('page.name')}} </span>
                                    <span class="text-dark ms-auto" id="name">---</span>
                                </li>
                                <!-- Address -->
                                <li class="list-group-item d-flex align-items-center">
                                    <span>{{__('page.address')}}</span>
                                    <span class="text-dark ms-auto" id="address">---</span>
                                </li>
                                <!-- Zip Code -->
                                <li class="list-group-item d-flex align-items-center" id="zip-code-list">
                                    <span>{{__('page.zip-code')}}</span>
                                    <span class="text-dark ms-auto" id="zip-code">---</span>
                                </li>
                                <!-- City -->
                                <li class="list-group-item d-flex align-items-center">
                                    <span>{{__('page.city')}}</span>
                                    <span class="text-dark ms-auto" id="city">---</span>
                                </li>
                                <!-- State -->
                                <li class="list-group-item d-flex align-items-center">
                                    <span>{{__('page.state')}}</span>
                                    <span class="text-dark ms-auto" id="state">---</span>
                                </li>
                                <!-- Date of Birth -->
                                <li class="list-group-item d-flex align-items-center d-none">
                                    <span>{{__('page.date_of_birth')}}</span>
                                    <span class="badge bg-primary rounded-pill ms-auto">---</span>
                                </li>
                                <!-- Phone -->
                                <li class="list-group-item d-flex align-items-center d-none">
                                    <span>{{__('page.phone')}}</span>
                                    <span class="text-white rounded-pill ms-auto">---</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{__('page.uploaded-document-and-status')}}</h4>
                        </div>
                        <hr>
                        <div class="card-body">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" aria-controls="home" role="tab" aria-selected="true"> {{__('page.id-proof')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#address-proof-form" aria-controls="profile" role="tab" aria-selected="false">{{__('page.address-proof')}} </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <form class="tab-pane active" enctype="multipart/form-data" action="#" method="post" id="home" aria-labelledby="home-tab" role="tabpanel">
                                    @csrf
                                    <input type="hidden" name="perpose" value="id proof">
                                    <input type="hidden" name="op" value="admin">
                                    <div class="mb-1 row">
                                        <label for="id-type" class="col-sm-3 col-form-label">{{__('page.document-type')}} </label>
                                        <div class="col-sm-9">
                                            <select class="select2 form-select select_option_design" id="id-type" name="document_type">
                                                <option value="" selected> {{__('page.document-type')}}</option>
                                                @foreach($id_document_type as $value)
                                                <option value="{{$value->id}}">{{$value->id_type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- issue date -->
                                    <div class="mb-1 row">
                                        <label for="id-issue-date" class="col-sm-3 col-form-label">{{__('page.issue-date')}} </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="id-issue-date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" name="issue_date" />
                                        </div>
                                    </div>
                                    <!-- expire date -->
                                    <div class="mb-1 row">
                                        <label for="id-expire-date" class="col-sm-3 col-form-label">{{__('page.expire-date')}} </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="id-expire-date" name="expire_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                                        </div>
                                    </div>
                                    <!-- client -->
                                    <div class="mb-1 row">
                                        <label for="id-client-email" class="col-sm-3 col-form-label">{{__('finance.Client')}}</label>
                                        <div class="col-sm-9 position-relative al-fixed-input-error">
                                            <!-- <div id="myDropdown" class="dropdown-content form-group ">
                                                <input type="text" name="client_email" class="form-control get-client" placeholder="client_email" id="myInput">
                                            </div> -->
                                            <select class="select2 form-select select2-both" id="client-email" name="client_email">

                                            </select>
                                        </div>
                                    </div>
                                    <!-- status -->
                                    <div class="mb-1 row">
                                        <label for="status" class="col-sm-3 col-form-label">{{__('page.status')}}</label>
                                        <div class="col-sm-9">
                                            <select class="select2 form-select" id="status select_option_design4" name="status">
                                                <option value="">{{__('page.status')}}</option>
                                                <option value="1">Verified</option>
                                                <option value="0">Pending</option>
                                                <option value="2">Decline</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-1 row mt-3" id="decline-reason-row" style="display:none">
                                        <label for="client-type" class="col-sm-3 col-form-label">Decline Reason</label>
                                        <div class="col-sm-9">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-floating mb-0">
                                                        <textarea data-length="100" name="decline_reason" class="form-control char-textarea" id="note" rows="3" placeholder="Counter" style="height: 100px"></textarea>
                                                        <label for="textarea-counter">Write a note, for sending mail</label>
                                                    </div>
                                                    <small class="textarea-counter-value float-end"><span class="char-count">0</span> / 100 </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- kyc files upload -->
                                    <div class="mb-1 row mt-3">
                                        <div class="col-sm-12">
                                            <div class="d-flex">
                                                <!-- id front part -->
                                                <div class="w-50">
                                                    <div class="dropzone dropzone-area id-proof-dropzone w-100" data-field="front_part" id="id-dropzone" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your ID">
                                                        <div class="dz-message">
                                                            <div class="dz-message-label">{{ __('page.drop-your-id') }}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- id back part -->
                                                <div class="w-50 ms-2">
                                                    <div class="dropzone dropzone-area id-proof-dropzone w-100" data-field="back_part" id="id-back-part" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your ID Back Part">
                                                        <div class="dz-message">
                                                            <div class="dz-message-label">{{ __('page.drop-your-id-back-part') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary float-end text-truncate" type="button" id="upload-kyc-button" data-label="Save Wallet Balance" data-form="wallet-balance-form" data-i18n="Save Kyc" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>">{{__('page.save-your-kyc')}}</button>
                                </form>
                                <!-- address proof -->
                                <form class="tab-pane" id="address-proof-form" method="post" adction="#" aria-labelledby="profile-tab" role="tabpanel">
                                    @csrf
                                    <input type="hidden" name="perpose" value="address proof">
                                    <input type="hidden" name="op" value="admin">
                                    <div class="mb-1 row">
                                        <label for="id-type" class="col-sm-3 col-form-label">Document Type</label>
                                        <div class="col-sm-9">
                                            <select class="select2 form-select " id="id-type select_option_design2" name="document_type">
                                                <option value="" selected>Select a document type first</option>
                                                @foreach($address_document_type as $value)
                                                <option value="{{$value->id}}">{{$value->id_type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- issue date -->
                                    <div class="mb-1 row">
                                        <label for="address-issue-date" class="col-sm-3 col-form-label">Issue Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Issue date" id="address-issue-date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" name="issue_date" />
                                        </div>
                                    </div>
                                    <!-- issue date -->
                                    <div class="mb-1 row">
                                        <label for="id-expire-date" class="col-sm-3 col-form-label">Expire Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Expire date" id="id-expire-date" name="expire_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                                        </div>
                                    </div>
                                    <!-- client -->
                                    <div class="mb-1 row">
                                        <label for="address-client-email" class="col-sm-3 col-form-label">Client</label>
                                        <div class="col-sm-9 position-relative al-fixed-input-error">
                                            <!-- <div id="myDropdown2" class="dropdown-content form-group ">
                                                <input type="text" name="client_email" class="form-control get-client" placeholder="client email" id="address-client-email">
                                            </div> -->
                                            <select class="form-select select2-both" id="client-email-2" name="client_email">

                                            </select>
                                        </div>
                                    </div>
                                    <!-- status -->
                                    <div class="mb-1 row">
                                        <label for="status" class="col-sm-3 col-form-label">Status</label>
                                        <div class="col-sm-9">
                                            <select class="select2 form-select" id="status select_option_design3" name="status">
                                                <option value="">Select status</option>
                                                <option value="1">Verified</option>
                                                <option value="0">Pending</option>
                                                <option value="2">Decline</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-1 row mt-3" id="decline-reason-row" style="display:none">
                                        <label for="client-type" class="col-sm-3 col-form-label">Decline Reason</label>
                                        <div class="col-sm-9">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-floating mb-0">
                                                        <textarea data-length="100" name="decline_reason" class="form-control char-textarea" id="note" rows="3" placeholder="Counter" style="height: 100px"></textarea>
                                                        <label for="textarea-counter">Write a note, for sending mail</label>
                                                    </div>
                                                    <small class="textarea-counter-value float-end"><span class="char-count">0</span> / 100 </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- kyc files upload -->
                                    <div class="mb-1 row mt-3">
                                        <div class="col-3"></div>
                                        <div class="col-sm-9">
                                            <div class="dropzone dropzone-area address-proof-dropzone" id="id-dropzone-address-proof" data-field="document" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                <div class="dz-message">
                                                    <div class="dz-message-label">{{ __('page.drop-your-document') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary float-end text-truncate" type="button" id="upload-kyc-button-address" data-label="Save Wallet Balance" data-form="wallet-balance-form" data-i18n="Save Kyc" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>">Save Kyc</button>
                                </form>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                    </div>
                </div>
            </div>
        </div>
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
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/jstree.min.js')}}"></script>\

<!-- file uploader -->
<script src="{{asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-kyc-upload.js')}}"> </script>
<script src="{{asset('admin-assets/app-assets/js/scripts/components/components-navs.js')}}"></script>
<!-- <script src="{{asset('admin-assets/app-assets/js/scripts/pages/ib-tree.js')}}"></script> -->
<!-- <script src="{{asset('admin-assets/app-assets/js/scripts/extensions/ext-component-tree.js')}}"></script> -->
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-select2.js')}}"></script>
<!-- <script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-file-uploader.js')}}"></script> -->
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
<!-- <script src="{{asset('admin-assets/app-assets/js/scripts/components/components-tooltips.js')}}"></script> -->
<!--<script src="{{asset('trader-assets/assets/js/scripts/pages/file-upload-with-form-v2.js')}}"></script>-->
<script src="{{asset('trader-assets/assets/js/scripts/pages/file-upload-with-form.js')}}"></script>

<script src="{{asset('common-js/search-dropdown.js')}}"></script>
<script src="{{asset('common-js/select2-get-both.js')}}"></script>
<script>
    $('#upload-kyc-button, #upload-kyc-button-address').click(function() {
        $(this).prop('disabled', true);
        setTimeout(() => {
            $(this).prop('disabled', false);
        }, 30000);

    })


    // proof--------------
    file_upload(
        "/admin/user-admin/verify-form", //<--request url for proccessing
        false, //<---auto process true or false
        ".id-proof-dropzone", //<---dropzones selectore
        "home", //<---form id/selectore
        "#upload-kyc-button", //<---submit button selectore
        "ID Proof" //<---Notification Title
    );
    // address proof--------------------------------------
    file_upload(
        "/admin/user-admin/verify-form", //<--request url for proccessing
        false, //<---auto process true or false
        ".address-proof-dropzone", //<---dropzones selectore
        "address-proof-form", //<---form id/selectore
        "#upload-kyc-button-address", //<---submit button selectore
        "Address proof" //<---Notification title
    );
    $("select").val('').trigger('change');
    $("#home").trigger('reset');

    // if($('#select_option_design', '#select_option_design2' , '#select_option_design3' , '#select_option_design4')){
    //     var select_option_design = $('#select_option_design', '#select_option_design2' , '#select_option_design3' , '#select_option_design4');
    //     const example = new Choices(select_option_design);
    // }
    // check adhar card
    $(document).on("change", "#id-type", function() {
        check_document_type();
    });
    check_document_type();

    function check_document_type() {
        let $type = $("#id-type option:selected").text();
        if ($type.toLowerCase().trim() == 'adhar card') {
            $("#row-exp-date").slideUp();
            $("#row-issue-date").slideUp();
        } else {
            $("#row-exp-date").slideDown();
            $("#row-issue-date").slideDown();
        }
    }
</script>
@stop
<!-- BEGIN: page JS -->