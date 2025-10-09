@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Announcement')
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
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/admin.css') }}">
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
                        <h2 class="content-header-title float-start mb-0">{{__('admin-menue-left.announcement')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{__('category.Settings')}}</a></li>
                                <li class="breadcrumb-item active">{{__('admin-menue-left.announcement')}}</li>
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
                        <div class="card-header border-bottom mb-0">
                            <div class="card my-0 py-0">
                                <div class="card-body my-0 py-0">
                                    <h4 class="card-title">{{__('admin-menue-left.announcement')}}</h4>
                                </div>
                            </div>
                        </div>
                        <!--add announcement form -->
                        <div class="card mt-0">
                            <div class="col-12">
                                <form action="{{route('admin.settings.announcement.add')}}" class="mt-2 pt-50" method="POST" enctype="multipart/form-data" id="announcement-add-form">
                                    @csrf
                                    <div class="card-body py-2 my-25">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- title -->
                                                    <div class="col-12 col-sm-6 mb-1">
                                                        <label class="form-label" for="title">{{__('category.Title')}}</label>
                                                        <input type="text" class="form-control" id="title" name="title" placeholder="Announcement Title" value="<?= (isset($announcements->title) ? $announcements->title : ''); ?>" />
                                                    </div>
                                                    <!-- dashboard -->
                                                    <div class="col-12 col-sm-3 mb-1" style="float: left;">
                                                        <label class="form-label" for="dashboard">{{__('page.dashboard')}}</label>
                                                        <select class="select2 form-select" id="" name="dashboard">
                                                            <option value="all">All Dashboard</option>
                                                            <option value="ib">IB Dashboard</option>
                                                            <option value="trader">Trader Dashboard</option>
                                                            <option value="staff">Staff Dashboard</option>
                                                        </select>
                                                    </div>
                                                    <!-- active status -->
                                                    <div class="col-12 col-sm-3 mb-1" style="float: left;">
                                                        <label class="form-label" for="status">{{__('category.Active Status')}}</label>
                                                        <select class="select2 form-select" id="status select_option_design2" name="status">
                                                            <option value="0">Close</option>
                                                            <option value="1">Open</option>
                                                        </select>
                                                    </div>
                                                    <!-- comment -->
                                                    <div class="col-12 col-sm-12 mb-1">
                                                        <label class="form-label" for="comment">{{__('page.comments')}}</label>
                                                        <textarea class="form-control" placeholder="Write A Comment Here......" id="comment" name="comment" value="<?= (isset($announcements->comment) ? $announcements->comment : ''); ?>" style="height: 100px"></textarea>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 col-sm-6 mb-1">
                                                        <label class="form-label">&nbsp;</label>
                                                        <div>
                                                            @if(Auth::user()->hasDirectPermission('create announcement'))
                                                            <button type="button" class="btn btn-primary me-1 mb-1" id="saveBtn" onclick="_run(this)" data-el="fg" data-form="announcement-add-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="announcementCallBack" data-btnid="saveBtn">{{__('page.save-change')}}</button>
                                                            <button type="reset" class="btn btn-outline-secondary mb-1">{{__('ad-reports.btn-reset')}}</button>
                                                            @else

                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12" style="margin-top:-6rem !important">
                                <div class="card m-2">
                                    <div class="card-header" style="padding-left: 10px;">
                                        <h4 class="card-title">{{__('admin-menue-left.announcement')}} {{__('page.list')}}</h4>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table announcement-table scrollbar-primary">
                                            <thead>
                                                <tr>
                                                    <th>{{__('category.Title')}}</th>
                                                    <th>{{__('page.dashboard')}}</th>
                                                    <th>{{__('page.status')}}</th>
                                                    <th>{{__('page.date')}}</th>
                                                    <th>{{__('page.action')}}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/add announcement  form -->
                        <!--Edit Announcement Modal -->
                        <div class="modal fade text-start" id="announcement-edit-form" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel33">Update Announcement</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{route('admin.settings.announcement.update')}}" class="mt-2 pt-50" method="POST" enctype="multipart/form-data" id="announcement-update-form">
                                        @csrf
                                        <div class="card-body py-2 my-25">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <!-- mt5 server type expended -->
                                                        <div class="col-12 col-sm-12 mb-1">
                                                            <label class="form-label" for="modal_title">Title</label>
                                                            <input type="text" class="form-control" id="modal_title" name="title" placeholder="Announcement Title" value="<?php echo (isset($announcements->title) ? $announcements->title : ''); ?>" />
                                                        </div>
                                                        <div class="col-12 col-sm-12 mb-1">
                                                            <label for="comment">Comments</label>
                                                            <textarea class="form-control" name="comment" placeholder="Write A Comment Here......" id="modal_comment" value="<?php echo (isset($announcements->comment) ? $announcements->comment : ''); ?>" style="height: 100px"></textarea>
                                                        </div>
                                                        <!-- dashboard -->
                                                        <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                            <label class="form-label">Dashboard</label>
                                                            <select class="select2 form-select" id="modal_dashboard" name="dashboard">
                                                                <option value="all">All Dashboard</option>
                                                                <option value="ib">IB Dashboard</option>
                                                                <option value="trader">Trader Dashboard</option>
                                                                <option value="staff">Staff Dashboard</option>
                                                            </select>
                                                        </div>
                                                        <!-- active status -->
                                                        <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                            <label class="form-label">Active Status</label>
                                                            <select class="select2 form-select" id="modal_status" name="status">
                                                                <option value="0">Close</option>
                                                                <option value="1">Open</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 mb-1">
                                                            <label class="form-label">&nbsp;</label>
                                                            <div>
                                                                <input type="hidden" id="announcement_id" value="" name="announcement_id">
                                                                <button type="button" data-bs-dismiss="modal" class="btn btn-primary me-1 mb-1" id="updateBtn" onclick="_run(this)" data-el="fg" data-form="announcement-update-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="announcementUpdateCallBack" data-btnid="updateBtn">Save Change</button>
                                                                <button type="reset" class="btn btn-outline-secondary mb-1">Reset</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--Edit Finace Modal End-->
                        <!--Delete Finace Modal End-->
                        <div class="modal fade" id="announcement-delete-modal">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="exampleModalCenterTitle">Confirmation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{route('admin.settings.announcement.delete')}}" class="mt-2 pt-50" method="POST" enctype="multipart/form-data" id="announcement-delete-form">
                                        @csrf
                                        <input type="hidden" name="id" id="announcement-delete-id" value="">
                                        <div class="modal-body my-3">
                                            <h4 class="text-center">
                                                Do you really want to delete these records? This process cannot be undone.
                                                </h5 class="text-center">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" data-bs-dismiss="modal" class="btn btn-danger data-submit me-1" id="deleteBtn" onclick="_run(this)" data-el="fg" data-form="announcement-delete-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="announcementDeleteCallBack" data-btnid="deleteBtn">Confirm</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--Delete Finace Modal End-->
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
@stop
<!-- BEGIN: page JS -->
