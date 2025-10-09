@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'System Configuration')
@section('vendor-css')

    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">

    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <!-- datatable -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <!-- BEGIN: content -->
@section('content')
    <!-- BEGIN: Content-->
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-fluid p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">{{ __('group-setting.Ib Groups') }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('group-setting.Home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ __('admin-menue-left.group_settins') }}
                                    </li>
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
                                    <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#"><i class="me-1" data-feather="info"></i>
                                        <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Basic table -->
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <table class="datatables-basic table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>id</th>
                                            <th>{{ __('group-setting.Group Name') }}</th>
                                            <th>{{ __('group-setting.Status') }}</th>
                                            <th>{{ __('group-setting.Created Date') }}</th>
                                            <th>{{ __('group-setting.Action') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Basic table -->
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- ########## START MODALS ########## -->
    <!-- Modal to add new record -->
    <div class="modal modal-slide-in fade" id="add-ib-group">
        <div class="modal-dialog sidebar-sm">
            <form class="add-new-ib-group modal-content pt-0">
                @csrf
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title">Add New Ib Group</h5>
                </div>
                @if (Auth::user()->hasDirectPermission('create manage ib group'))
                    <div class="modal-body flex-grow-1">
                        <div class="mb-1">
                            <div class="form-element">
                                <label class="form-label" for="status">Status</label>
                                <select class="select2 form-select dt-status" name="status" id="status">
                                    <optgroup>
                                        <option value="1">Active</option>
                                        <option value="0">Deactive</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="mb-1">
                            <div class="form-element">
                                <label class="form-label" for="group-name">Group Name</label>
                                <input type="text" class="form-control dt-group-name" id="group-name" name="group_name"
                                    placeholder="Name of the category" />
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary data-submit me-1">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            @include('errors.permission')
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Modal to edit record -->
    <div class="modal modal-slide-in fade" id="edit-ib-group">
        <div class="modal-dialog sidebar-sm">
            <form class="edit-ib-group-form modal-content pt-0">
                @csrf
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title">Edit Ib Group</h5>
                </div>
                @if (Auth::user()->hasDirectPermission('edit manage ib group'))
                    <div class="modal-body flex-grow-1">
                        <input type="hidden" id="edit_ib-group-id" name="id" />

                        <div class="mb-1">
                            <div class="form-element">
                                <label class="form-label" for="status">Status</label>
                                <select class="select2 form-select dt-status" name="status" id="edit_status">
                                    <optgroup>
                                        <option value="1">Active</option>
                                        <option value="0">Deactive</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="mb-1">
                            <div class="form-element">
                                <label class="form-label" for="group-name">Group Name</label>
                                <input type="text" class="form-control dt-group-name" id="edit_group-name"
                                    name="group_name" placeholder="Name of the category" />
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary data-submit me-1">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            @include('errors.permission')
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Modal to delete record -->
    <div class="modal fade" id="delete-ib-group">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalCenterTitle">Please Confirm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" class="modal-content pt-0">
                    @csrf
                    <input type="hidden" id="delete_ib-group-id" name="id" />

                    <div class="modal-body my-3">
                        <h5 class="text-center">
                            Do You Really Want To Delete This Ib Group?
                        </h5 class="text-center">
                    </div>
                    <div class="modal-footer">
                        @if (Auth::user()->hasDirectPermission('delete manage ib group'))
                            <button type="reset" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger data-submit me-1">Yes Delete</button>
                        @else
                            <span class="text-danger">Sorry, No Access For Delete</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ########## END MODALS ########## -->

@stop
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <!-- datatable -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script>
        const ibGroupData = "{{ url('admin/ib-groups') }}?op=data-table";
        const addIbGroup = "{{ url('admin/ib-groups') }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="{{ asset('admin-assets/assets/js/group_settings/ib_groups.js') }}"></script>
@stop
<!-- END: page JS -->
