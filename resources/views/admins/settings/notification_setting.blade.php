@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Notification Setting')
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
<style>
    h4.card-title.float-start.d-flex {
        padding-top: 8px;
    }

    .form-check {
        float: left;
        margin-right: 1.53rem;
        margin-top: 0.5rem;
    }
</style>
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
                        <h2 class="content-header-title float-start mb-0">{{__('page.notifications')}} {{__('page.settings')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{__('category.Settings')}}</a></li>
                                <li class="breadcrumb-item active">{{__('page.notifications')}} {{__('page.settings')}}</li>
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
                                <h4> Filter</h4>
                                <code class="bg-light-primary">Choose a manager or admin</code>
                            </div>
                        </div>
                        <hr>
                        <div class="card-body">
                            <form action="{{route('admin.allNotification.allNotification.filter')}}" class="filter_form" id="filter_form" method="post">
                                @csrf
                                <div class="row g-1">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="admin-info" class="form-label">Admin / Manager Info.</label>
                                            <input type="text" class="form-control" name="admin_info" placeholder="Name / email / phone" id="admin-info">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="client-info" class="form-label">Manager (Clients).</label>
                                            <input type="text" class="form-control" name="manager_client" placeholder=" Name / email / phone" id="client-info">
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-1 mt-2">
                                    <div class="col-md-6">
                                        <button id="btn_reset" class="btn btn-danger w-100" type="button">Reset</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-primary me-1 mb-1 w-100" id="notificationUpdateBtn" onclick="_run(this)" data-el="fg" data-form="filter_form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="notificationUpdateCallBack" data-btnid="notificationUpdateBtn">Filter</button>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <table id="admin-details-table" class="table table-borderless">
                                <tr>
                                    <th>Name</th>
                                    <td><span id="ad-de-name">---</span></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><span id="ad-de-email">---</span></td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td><span id="ad-de-phone">---</span></td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-sm-7">
                    @if(Auth::user()->hasDirectPermission('create notification setting'))
                    <div class="card">
                        <div class="card-header border-bottom mb-0">
                            <div class="card my-0 py-0 w-100">
                                <div class="card-body my-0 py-0">
                                    <h4 class="card-title float-start d-flex">{{__('page.notifications')}} {{__('page.settings')}}</h4>
                                </div>
                            </div>
                        </div>
                        <!--add notification form -->
                        <div class="card mt-0 mb-0 p-3">
                            <form action="{{route('admin.allNotification.allNotification.save_notification')}}" id="notification-for-admin" method="post">
                                @csrf
                                <input type="hidden" id="notification_user_id" value="" name="notification_user_id">
                                <div class="form-group">
                                    <label for="notification-mail-admin" class="form-label">Notification Email</label>
                                    <input type="email" class="form-control" name="notification_email" id="notification_email">
                                </div>
                                <!-- notification for switch -->
                                <div class="row g-1 mt-2">
                                    <div class="col-md-4">
                                        <div class="d-flex flex-column">
                                            <label class="form-check-label" for="select-all">Disable / Enable all</label>
                                            <div class="form-check form-check-primary form-switch">
                                                <input type="checkbox" checked class="form-check-input" name="all" id="select-all" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row g-1 mt-3">
                                    @foreach($notifications as $value)
                                    <div class="col-md-4">
                                        <div class="d-flex flex-column">
                                            <label class="form-check-label" for="customSwitch{{$value->id}}">{{ucwords($value->user_type)}} {{$value->type}}</label>
                                            <div class="form-check form-check-primary form-switch">
                                                <input type="checkbox" checked class="form-check-input" name="{{str_replace(' ','',$value->user_type . $value->type)}}" id="customSwitch{{$value->id}}" />
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="row g-1 mt-2">
                                    <button type="button" class="btn btn-primary me-1 mb-1 w-100" id="notificationUpdateBtn2" onclick="_run(this)" data-el="fg" data-form="notification-for-admin" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="notificationUpdateCallBack2" data-btnid="notificationUpdateBtn2">Save Change</button>
                                </div>
                            </form>
                        </div>
                        <!--/add notification  form -->
                    </div>
                    @else
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                @include('errors.permission')
                            </div>
                        </div>
                    </div>
                    @endif
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
<script>
    $(document).on('click', '#select-all', function() {
        if ($(this).prop("checked")) {
            $(".form-check-input").prop("checked", true);
        } else {
            $(".form-check-input").prop("checked", false);
        }
    });
    $('#filter_form').trigger('reset');
    $('#notification-for-admin').trigger('reset');
    //update notifications callback
    function notificationUpdateCallBack(data) {
        if (data.status) {
            $("#ad-de-name").html(data.user.name);
            $("#ad-de-email").html(data.user.email);
            $("#ad-de-phone").html(data.user.phone);
            $("#notification_user_id").val(data.user.id);
            if (data.notificaton_status === false) {
                $("#notification_email").val(data.user.email);
                $.each(data.notification_fieds_id, function(index, value) {
                    $("#" + value).prop("checked", false);
                })
            } else {
                $("#notification_email").val(data.notification_email);
                console.log(data);
                $.each(data.setup_data, function(index, value) {
                    // console.log(value);
                    if (value === 'on') {
                        $("input[name=" + index + "]").prop("checked", true);
                    }else{
                        $("input[name=" + index + "]").prop("checked", false);
                    }

                });
            }
            notify('success', data.message, 'Filter admin | manager');
        } else {
            notify('error', data.message, 'Filter admin | manager');
        }
        $.validator("filter_form", data.errors);
    }

    function notificationUpdateCallBack2(data) {
        if (data.status) {

            notify('success', data.message, 'Notification settings');
        } else {
            notify('error', data.message, 'Notification settings');
        }
        $.validator("notification-for-admin", data.errors);
    }
</script>
@stop
<!-- BEGIN: page JS -->