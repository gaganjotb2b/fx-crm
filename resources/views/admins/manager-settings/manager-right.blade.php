@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','System Configuration')
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
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
<!-- number input -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
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
                            <h2 class="content-header-title float-start mb-0">{{__('finance.manager_right')}}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('finance.home')}}</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">{{__('finance.manager_settings')}}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{__('finance.manager_right')}}
                                    </li>
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
                <!-- Role cards -->
                <h3 class="mt-50">{{__('finance.total')}}</h3>
                <p class="mb-2">{{__('finance.find')}}</p>
                <!-- table -->
                <div class="card p-2">
                    <div class="table-responsive">
                        <table class="user-list-table table " id="manager-list">
                            <thead class="table-light">
                                <tr>
                                    <th>{{__('finance.name')}}</th>
                                    <th>{{__('finance.groups')}}</th>
                                    <th>{{__('finance.country')}}</th>
                                    <th>{{__('finance.status')}}</th>
                                    <th>{{__('finance.actions')}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- table -->
                <!-- Add Role Modal -->
                <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
                        <div class="modal-content">
                            <div class="modal-header bg-transparent">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body px-5 pb-5">
                                <div class="text-center mb-4">
                                    <h1 class="role-title">Edit Manager Info</h1>
                                    <p>Desk Manager</p>
                                </div>
                                <!-- Add role form -->
                                <form id="edit-manager-info-form" class="row" onsubmit="return false" method="post" action="{{route('admin.edit-manager')}}">
                                    <div id="manager-infos" class="row">
                                        <!-- Load from add manager controller controller -->
                                    </div>
                                    <div class="col-12 text-center mt-2">
                                        <button type="submit" class="btn btn-primary me-1 btn-save-edit-manager">Save Change</button>
                                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                            Discard
                                        </button>
                                    </div>
                                </form>
                                <!--/ Add role form -->
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Add Role Modal -->

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- Enable backdrop (default) -->
    <div class="enable-backdrop">
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasBackdrop" aria-labelledby="offcanvasBackdropLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasBackdropLabel" class="offcanvas-title">Add New Admin Group</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body my-auto mx-0 flex-grow-0">
                <p>
                    <b>Group Name as Rols Name.</b>
                    A role provided access to predefined menus and features so that depending
                    on assigned role an administrator can have access to what he need
                </p>
                <form action="{{route('admin.add-admin-group')}}" method="post" id="admin-group-form">
                    @csrf
                    <label class="form-label" for="group-name">Group Name</label>
                    <input id="group-name" class="form-control" type="text" placeholder="Normal Input" name="group_name" />
                    <button type="button" id="save-group" class="btn btn-primary mb-1 d-grid w-100 mt-1">Save Group</button>
                    <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="offcanvas">
                        Cancel
                    </button>
                </form>

            </div>
        </div>
    </div>
    <!--/ Enable backdrop (default) -->
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js')}}"></script>
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
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- number input -->
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/manager-right.js')}}"> </script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-number-input.js')}}"></script>
<script>
    // add right/role
    // ----------------------------------------------------------
    function manager_right_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Manager Right');
        } else {
            notify('error', 'Please fix following errors', 'Manager Right');
        }
        $.validator("add-role-from", data.message);
    }


 



</script>
@stop
<!-- BEGIN: page JS -->
