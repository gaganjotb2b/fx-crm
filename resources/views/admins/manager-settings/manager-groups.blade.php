@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Manager Groups')
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
<!-- number input -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css') }}">
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
        display: flex;
        justify-content: space-between;
        padding: 0 0rem;
    }

    .stretched-link::after {
        z-index: 0 !important;
        position: relative !important;
        display: none !important;
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
                        <h2 class="content-header-title float-start mb-0">{{ __('admin-management.manager_groups') }}
                        </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('finance.home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{ __('admin-management.manager_settings') }}</a>
                                </li>
                                <li class="breadcrumb-item active"> {{ __('admin-management.manager_groups') }}
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
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <form action="" class="card" id="filter-form">
                <div class="card-header">
                    <h3>Filter Groups</h3>
                    <span>
                        <a href="javascript:void(0)" data-bs-target="#offcanvasBackdrop" data-bs-toggle="offcanvas" class="stretched-link text-nowrap add-new-role" aria-controls="offcanvasBackdrop">
                            <span class="btn btn-primary mb-1">{{ __('admin-management.Add New Group') }}</span>
                        </a>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- filter by manager group -->
                        <div class="col-md-4">
                            <label for="manager-group">Manager type</label>
                            <select name="manager_group" id="manager-group" class="form-control form-input form-select">
                                <option value="">All</option>
                                <option value="0">Desk manager</option>
                                <option value="1">Account manager</option>
                                <option value="6">Admin manager</option>
                                <option value="7">Country manager</option>
                            </select>
                        </div>
                        <!-- filter by status -->
                        <div class="col-md-4">
                            <label for="filter-satatus">Satatus</label>
                            <select name="status" id="filter-satatus" class="form-control form-input form-select">
                                <option value="">All</option>
                                <option value="1">Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>
                        <!-- filter by name or email -->
                        <div class="col-md-4">
                            <label for="filter-info">Manager info.</label>
                            <input type="text" id="filter-info" name="manager_info" class="form-control form-input" placeholder="Manager Name / Email / Phone">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <!-- filter by client -->
                        <div class="col-md-4 ms-auto">
                            <label for="filter-info">Trader Info.</label>
                            <input type="text" id="clients-name" name="trader_info" class="form-control form-input" placeholder="Trader Name / Email /  Phone">
                        </div>
                        <!-- ib name emal phone -->
                        <div class="col-md-4 ms-auto">
                            <label for="filter-info">IB Info.</label>
                            <input type="text" id="clients-name" name="ib_info" class="form-control form-input" placeholder="IB Name / Email /  Phone">
                        </div>
                        <!-- filter by manager country  -->
                        <div class="col-md-4 ms-auto">
                            <label for="filter-info">Country</label>
                            <select name="country" id="country" class="select2 form-select">
                                <option value="">All</option>
                                @foreach($countries as $value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- button row -->
                    <div class="row mt-2">
                        <div class="col-md-4 ms-auto">
                            <button type="button" id="btn-reset" class="btn btn-danger w-100 mt-2">Reset</button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" id="btn-filter" class="btn btn-primary w-100 mt-2">Filter</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- table -->
            <div class="card p-2">
                <div class="row  position-relative mb-3" id="data-list">
                    <div class="text-center">Loding manager groups....</div>
                </div>
                <div class="table-responsive">
                    <table class="user-list-table table d-none" id="manager-group-list">

                    </table>
                </div>
            </div>
            <!-- table -->
            <!-- manager edit Modal -->
            <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-5 pb-5">
                            <div class="text-center mb-4">
                                <h1 class="role-title">Edit Manager Info</h1>
                                <p id="display-manager-group">Desk Manager</p>
                            </div>
                            <!-- manager edit form -->
                            <form id="edit-manager-info-form" class="row" method="post" action="{{ route('admin.edit-manager') }}">
                                <div id="manager-infos" class="row">
                                    <!-- Load from add manager controller controller -->
                                </div>
                                <div class="col-12 mt-2 text-end">
                                    <hr>
                                    <button type="button" class="btn btn-primary  btn-save-edit-manager" id="save-edit-manager" onclick="_run(this)" data-el="fg" data-form="edit-manager-info-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="edit_manager_call_back" data-btnid="save-edit-manager">Save
                                        Change</button>
                                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                        Discard
                                    </button>
                                </div>
                            </form>
                            <!--/ manager edit  form -->
                        </div>
                    </div>
                </div>
            </div>
            <!--/ manager edit Modal -->

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
        @if (Auth::user()->hasDirectPermission('create manager groups'))
        <div class="offcanvas-body my-auto mx-0 flex-grow-0">
            <p>
                <b>Group Name as Rols Name.</b>
                A role provided access to predefined menus and features so that depending
                on assigned role an administrator can have access to what he need
            </p>
            <form action="{{ route('admin.add-manager-group') }}" method="post" id="manager-group-form">
                @csrf
                <label class="form-label" for="group-type">Basic</label>
                <select class="select2 form-select" id="group-type" name="group_type">
                    <option value="1" selected>{{ __('admin-management.Account Manager') }}</option>
                    <option value="0">{{ __('admin-management.Desk Manager') }}</option>
                    <option value="6">{{ __('admin-management.Admin Manager') }}</option>
                    <option value="7">{{ __('admin-management.Country Manager') }}</option>
                </select>
                <label class="form-label" for="group-name">Group Name</label>
                <input id="group-name" class="form-control" type="text" placeholder="Account Manager" name="group_name" />
                <button type="button" id="save-group" data-btnid="save-group" class="btn btn-primary mb-1 d-grid w-100 mt-1" onclick="_run(this)" data-el="fg" data-form="manager-group-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="group_call_back">Save Group</button>
                <!-- <button type="button" class="btn btn-primary mb-1 d-grid w-100 mt-1" id="save-update-group" onclick="_run(this)" data-el="fg" data-form="manager-group-update-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="update_group_call_back" data-btnid="save-update-group">Save Change</button> -->
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
<!--/ Enable backdrop (default) -->
<!-- Enable backdrop (edit group) -->
<div class="enable-backdrop">
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editGroup" aria-labelledby="editGroupLabel">
        <div class="offcanvas-header">
            <h5 id="editGroupLabel" class="offcanvas-title">Update Manager Group</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body my-auto mx-0 flex-grow-0">
            <p>
                <b>Group Name as Rols Name.</b>
                A role provided access to predefined menus and features so that depending
                on assigned role an administrator can have access to what he need
            </p>
            <form onkeydown="return event.key != 'Enter';" action="{{ route('admin.edit-manager-group') }}" method="post" id="manager-group-update-form">
                @csrf
                <input type="hidden" name="group_id" value="" id="group_id">
                <label class="form-label" for="group-name-edit">Group Name</label>
                <input id="group-name-edit" class="form-control" type="text" placeholder="Manager Group Name" name="group_name" />
                <!-- <button type="button" id="save-group" class="btn btn-primary mb-1 d-grid w-100 mt-1">Save Group</button> -->
                <button type="button" class="btn btn-primary mb-1 d-grid w-100 mt-1" id="save-update-group" onclick="_run(this)" data-el="fg" data-form="manager-group-update-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="update_group_call_back" data-btnid="save-update-group">Save Change</button>
                <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="offcanvas">
                    Cancel
                </button>
            </form>
        </div>
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
<!-- number input -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/manager-list.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-number-input.js') }}"></script>
<script src="{{ asset('common-js/data-col.js') }}"></script>
<script>
    var groupt_list = $('#manager-group-list').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "lengthChange": false,
        "pageLength": 6,
        "ajax": {
            "url": "/admin/manager-settings/manager-group/datatable-custom", // Replace with your server endpoint URL
            "type": "GET",
            "dataSrc": "data",
            "data": function(d) {
                return $.extend({}, d, $("#filter-form").serializeObject());
            },
        },
        "columns": [{
                "data": "total_manager"
            },
            {
                "data": "manager_list"
            },
            {
                "data": null,
                "render": function(data, type, full, meta) {
                    console.log(data.location_url);
                    return '<a href="' + data.location_url + '" class="btn btn-sm btn-outline-warning">View</a>';
                }
            }
        ],

        "fnDrawCallback": function(oSettings) {
            // Clear the existing list items
            $('#manager-group-list').empty();
            $('#data-list').empty();

            // Add new list items based on the data from DataTables
            var data = oSettings.json.data;
            if (data.length === 0) {
                // Display the default DataTables empty message in the listItem
                var listItem = `<div class="col-md-12 text-center">No Group available in my system</div>`;
                $('#data-list').append(listItem);
            } else {
                for (var i = 0; i < data.length; i++) {
                    var cardClass = data[i].group_type == 0 ? 'bg-light-success' : 'bg-light-info';
                    var listItem = `<div class="col-md-4">
                                    <div class="card ${cardClass}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <span>Total  ${data[i].total_manager} Manager</span>
                                                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                                ${data[i].manager_list}
                                                </ul>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-end mt-1 pt-25">
                                                <div class="role-heading">
                                                    <h4 class="fw-bolder">${data[i].group_name}</h4>
                                                    ${data[i].edit_button}
                                                    ${data[i].delete_button}
                                                </div>
                                                <a href="javascript:void(0);" class="text-body"><i data-feather="copy" class="font-medium-5"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                    $('#data-list').append(listItem);
                }
            }
        }
    });
    // filter group
    $("#btn-filter").on("click", function() {
        groupt_list.draw();
    });
    // filter reset
    $("#btn-reset").on("click", function(e) {
        $("#filter-form").find("select").val('').change();
        $(".start_date").val('');
        $(".end_date").val('');
        $("#filter-form").trigger('reset');
        $(".select2").val("").trigger('change');
        groupt_list.draw();
    });
    // add new group 
    // ------------------------------------------------------------------
    function group_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Manager Group')
            $("#manager-group-form").trigger('reset');
            $(".btn-close").trigger('click');
        }
        if (data.status == false) {
            notify('success', data.message, 'Manager Group')
        }
        groupt_list.draw();
        $.validator("manager-group-form", data.errors);
    }
    // update existing group
    // ------------------------------------------------------------------
    function update_group_call_back(data) {
        if (data.status == true) {
            // comment_table_obj.DataTable().draw();
            notify('success', data.message, 'Edit Manager Group')
            $(".btn-close").trigger('click');
        } else {
            notify('error', 'Please fix following errors', 'Edit Manager Group')
        }
        groupt_list.draw();
        $.validator("manager-group-update-form", data.errors);

    }
    // edit manager
    // --------------------------------------------------------------
    function edit_manager_call_back(data) {
        if (data.status == true) {
            toastr['success'](data.message, 'Update Manager', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });
            // comment_table_obj.DataTable().draw();
            $.validator("edit-manager-info-form", data.errors);
        } else {
            $.validator("edit-manager-info-form", data.errors);
        }
    }
    //add this js bottom in the 'all-manager.blade.php'
    $(document).ready(function() {
        $(document).on("click", "#manager-list .page-item", function() {
            const parent = this.parentElement.closest('form');
            const button = parent.querySelector('button');
            $(button).trigger('click');
        });
    });
    // --------------------------------------------

    // get data into edit offcanvas
    $(document).on("click", ".edit-group", function() {
        $("#group_id").val($(this).data('id'));
        $("#group-name-edit").val($(this).data('name'));

    });

    // manager group delete operation
    $(document).on("click", ".delete-manager-group", function() {
        let id = $(this).data('id');
        $(this).confirm2({
            request_url: '/admin/manager-settings/manager-group-delete',
            data: {
                id: id,
            },
            click: false,
            title: 'Delete manager group',
            message: 'Are you confirm to delete this manager group? If you delete this you lost some data.',
            button_text: 'Aprove',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'Delete manager Group');
            } else {
                notify('error', data.message, 'Delete manager Group');
            }
            groupt_list.draw();
        });
    });
</script>
@stop
<!-- BEGIN: page JS -->