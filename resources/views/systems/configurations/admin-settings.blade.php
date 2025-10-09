@extends('layouts.system-layout')
@section('title', 'Admin Rights Management')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('common-css/data-list-style.css') }}">
<style>
    .lgrp-paginate {
        position: absolute;
        right: 17px;
        bottom: -24px;
    }

    .data-list-footer {
        display: flex !important;
        justify-content: space-between;
        padding: 0 0rem;
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
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <h3>{{ __('admin-management.Group List') }}</h3>
            <div class="d-flex justify-content-between">
                <div>
                    <p class="mb-2">
                        {{ __('admin-management.sentence') }}
                    </p>
                </div>
                <div>
                    <!-- <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBackdrop" aria-controls="offcanvasBackdrop">
                                Add new Right
                            </button> -->
                </div>
            </div>
            <!-- Role cards -->
            <div class="row position-relative">
                {{-- {!!$group_list!!} --}}
                <div class="col-md-8">
                    <div class="row" id="data-list">

                    </div>
                </div>


                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="d-flex align-items-end justify-content-center h-100">
                                    <img src="{{ asset('admin-assets/app-assets/images/illustration/faq-illustrations.svg') }}" class="img-fluid mt-2" alt="Image" width="85" />
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="card-body text-sm-end text-center ps-sm-0">
                                    <a href="javascript:void(0)" data-bs-target="#offcanvas-new-role" data-bs-toggle="offcanvas" aria-controls="offcanvasBackdrop" class="stretched-link text-nowrap add-new-role">
                                        <span class="btn btn-primary mb-1">{{ __('admin-management.new-role') }}</span>
                                    </a>
                                    <p class="mb-0">{{ __('admin-management.role') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--/ Role cards -->

            <h3 class="mt-50">{{ __('admin-management.admins') }}</h3>
            <p class="mb-2">{{ __('admin-management.find') }}</p>
            <!-- table -->
            <div class="card p-3">
                <div class="table-responsive">
                    <table class="user-list-table table role-datatable">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('admin-management.Name') }}</th>
                                <th>{{ __('admin-management.Groups') }}</th>
                                <th>{{ __('admin-management.Country') }}</th>
                                <th>{{ __('admin-management.Status') }}</th>
                                <th data-bs-toggle="tooltip" data-bs-placement="top" title="Click Action to assign permission">{{ __('admin-management.Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- table -->
            <!-- Add Role Modal -->
            <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role" style="max-width: 1058px;">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-5 pb-5">
                            <div class="text-center mb-4">
                                <h1 class="role-title">Assign Permission</h1>
                                <p>Set role permissions to- <span class="to-name"></span></p>
                            </div>
                            <!-- assign permission form -->
                            <form class="row" action="{{ route('admin.set-all-roles-permissions') }}" id="form-asign-role-perimission" method="post">
                                @csrf
                                <div class="col-12">
                                    <b id="display-role-name"></b>
                                    <hr>
                                </div>
                                <div class="col-12">
                                    <h4 class="mt-2 pt-50">Available Right(s)</h4>
                                    <!-- Permission table -->
                                    <div class="table-responsive">
                                        <table class=" table role-permission-datatable" id="role-permission">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Read</th>
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                    <th>Create</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <!-- Permission table -->
                                </div>
                                <div class="col-12 text-end mt-2">

                                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                        Close
                                    </button>
                                    <button type="button" class="btn btn-primary" id="save-permission">Save
                                        Permission</button>


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
<!-- Add new Roll offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-new-role" aria-labelledby="offcanvasLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasLabel" class="offcanvas-title">Add New (Right)</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    @if (Auth::user()->hasDirectPermission('create admin right management'))
    <div class="offcanvas-body my-auto mx-0 flex-grow-0">
        <p>A role provided access to predefined menus and features so that depending
            on assigned role an administrator can have access to what he need </p>
        <form onkeydown="return event.key != 'Enter';" action="{{ route('admin.add-new-right') }}" method="post" class="role-form p-1" id="add-role-from">
            @csrf
            <div class="mb-1">
                <label class="form-label" for="role">Right Name</label>
                <input type="text" id="role" class="form-control" name="name" placeholder="Admin Management" />
            </div>
            <!-- <button type="button" id="save-role" class="btn btn-primary mb-1 d-grid w-100">Save Role</button> -->
            <button type="button" class="btn btn-primary mb-1 d-grid w-100 mt-1" id="save-role" onclick="_run(this)" data-el="fg" data-form="add-role-from" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="add_right_call_back" data-btnid="save-role">Save Right</button>
            <button type="button" id="btn-close-ofcanvas" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="offcanvas">
                Cancel
            </button>
        </form>
    </div>
    @else
    <div class="offcanvas-body my-auto mx-0 flex-grow-0">
        <div class="card">
            <div class="card-body">
                @include('errors.permission')
            </div>
        </div>
    </div>
    @endif
</div>
<!-- Enable backdrop (edit group) -->
<div class="enable-backdrop">
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editGroup" aria-labelledby="editGroupLabel">
        <div class="offcanvas-header">
            <h5 id="editGroupLabel" class="offcanvas-title">Update Admin Group</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        @if (Auth::user()->hasDirectPermission('edit admin right management'))
        <div class="offcanvas-body my-auto mx-0 flex-grow-0">
            <p>
                <b>Group Name as Rols Name.</b>
                A role provided access to predefined menus and features so that depending
                on assigned role an administrator can have access to what he need
            </p>
            <form onkeydown="return event.key != 'Enter';" action="{{ route('admin.update-admin-group') }}" method="post" id="admin-group-update-form">
                @csrf
                <input type="hidden" name="group_id" value="" id="group_id">
                <label class="form-label" for="group-name-edit">Group Name</label>
                <input id="group-name-edit" class="form-control" type="text" placeholder="Normal Input" name="group_name" />
                <!-- <button type="button" id="save-group" class="btn btn-primary mb-1 d-grid w-100 mt-1">Save Group</button> -->
                <button type="button" class="btn btn-primary mb-1 d-grid w-100 mt-1" id="save-update-group" onclick="_run(this)" data-el="fg" data-form="admin-group-update-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="update_group_call_back" data-btnid="save-update-group">Save Change</button>
                <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="offcanvas">
                    Cancel
                </button>
            </form>
        </div>
        @else
        <div class="offcanvas-body my-auto mx-0 flex-grow-0">
            <div class="card">
                <div class="card-body">
                    @include('errors.permission')
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
<!--/ Enable backdrop (edit group) -->
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
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
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/admin-settinges-permission.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/admin-groups.js') }}"></script>
<script src="{{ asset('common-js/data-col.js') }}"></script>

<script>
    // $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
    //     $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    // } );
    //  $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    // update existing group
    // ------------------------------------------------------------------

    var data_list = $("#data-list");
    var dataList = data_list.data_list({
        serverSide: true,
        url: '/admin/admin-management/admin-groups',
        listPerPage: 2
    });


    function update_group_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Admin group');
            $(".btn-close").trigger('click');
            dataList.draw_list();
        } else {
            notify('error', 'Please fix following errors', 'Admin group');
        }
        $.validator("admin-group-update-form", data.errors);
    }

    // add right/role
    // ----------------------------------------------------------
    function add_right_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Admin Right');
            $("#btn-close-ofcanvas").trigger("click");
            $("#add-role-from").trigger("reset");
        } else {
            notify('error', 'Please fix following errors', 'Admin Right');
        }
        $.validator("add-role-from", data.errors);
    }

    // asign permission to a role 
    // like as read, write, and create permission
    function assing_permission_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Admin Right');
        } else {
            notify('error', 'Please fix following errors', 'Admin Right');
        }

    }

    $('#save-permission').click(function() {
        var checkRolesSe = $('#form-asign-role-perimission input[type="checkbox"][name="roles[]"]:checked');
        var unCheckRolesSe = $('#form-asign-role-perimission input[type="checkbox"][name="roles[]"]:unchecked');
        var unCheckPermissionSe = $(
            '#form-asign-role-perimission input[type="checkbox"][name="permission[]"]:unchecked');
        var checkPermissionSe = $(
            '#form-asign-role-perimission input[type="checkbox"][name="permission[]"]:checked');
        var checkRoles = [];
        var unCheckRoles = [];
        var checkPermission = [];
        var unCheckPermission = [];
        var id = $('#form-asign-role-perimission input[name="id"]').val();
        for (let i = 0; i < checkRolesSe.length; i++) {
            checkRoles[i] = checkRolesSe[i].value;
        }
        for (let i = 0; i < unCheckRolesSe.length; i++) {
            unCheckRoles[i] = unCheckRolesSe[i].value;
        }
        for (let i = 0; i < checkPermissionSe.length; i++) {
            checkPermission[i] = checkPermissionSe[i].value;
        }
        for (let i = 0; i < unCheckPermissionSe.length; i++) {
            unCheckPermission[i] = unCheckPermissionSe[i].value;
        }

        var data = {
            'id': id,
            'checkRoles': checkRoles,
            'unCheckRoles': unCheckRoles,
            'unCheckPermission': unCheckPermission,
            'checkPermission': checkPermission
        }


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/admin-management/assign-perimission-to-role',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    notify('success', data.message);
                }
            }
        });
    });


    // add this js bottom in the 'all-manager.blade.php'
    $(document).ready(function() {
        $(document).on("click", "#form-asign-role-perimission .page-item", function() {
            var checkRolesSe = $(
                '#form-asign-role-perimission input[type="checkbox"][name="roles[]"]:checked');
            var unCheckRolesSe = $(
                '#form-asign-role-perimission input[type="checkbox"][name="roles[]"]:unchecked');
            var unCheckPermissionSe = $(
                '#form-asign-role-perimission input[type="checkbox"][name="permission[]"]:unchecked'
            );
            var checkPermissionSe = $(
                '#form-asign-role-perimission input[type="checkbox"][name="permission[]"]:checked');
            var checkRoles = [];
            var unCheckRoles = [];
            var checkPermission = [];
            var unCheckPermission = [];
            var id = $('#form-asign-role-perimission input[name="id"]').val();
            for (let i = 0; i < checkRolesSe.length; i++) {
                checkRoles[i] = checkRolesSe[i].value;
            }
            for (let i = 0; i < unCheckRolesSe.length; i++) {
                unCheckRoles[i] = unCheckRolesSe[i].value;
            }
            for (let i = 0; i < checkPermissionSe.length; i++) {
                checkPermission[i] = checkPermissionSe[i].value;
            }
            for (let i = 0; i < unCheckPermissionSe.length; i++) {
                unCheckPermission[i] = unCheckPermissionSe[i].value;
            }

            var data = {
                'id': id,
                'checkRoles': checkRoles,
                'unCheckRoles': unCheckRoles,
                'unCheckPermission': unCheckPermission,
                'checkPermission': checkPermission
            }


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/admin-management/assign-perimission-to-role',
                method: 'POST',
                data: data,
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        return false;
                    }
                }
            });
        });
    });
</script>
@stop
<!-- BEGIN: page JS -->