@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Category Manager')
@section('vendor-css')

<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<!-- BEGIN: content -->
@section('page-css')
<style>
    /* for Laptop */
    @media screen and (max-width: 1280px) and (min-width: 800px) {

        .ib-withdraw thead tr th:nth-child(5),
        .ib-withdraw tbody tr td:nth-child(5) {
            display: none;
        }

        .small-none {
            display: none;
        }
    }

    @media screen and (max-width: 1280px) and (min-width: 800px) {

        .ib-withdraw thead tr th:nth-child(5),
        .ib-withdraw tbody tr td:nth-child(5) {
            display: none;
        }

        .small-none-two {
            display: none;
        }
    }



    @media screen and (max-width: 1440px) and (min-width: 900px) {

        .ib-withdraw thead tr th:nth-child(3),
        .ib-withdraw tbody tr td:nth-child(3) {
            display: none;
        }

        .small-none {
            display: none;
        }
    }
</style>
@stop
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
                        <h2 class="content-header-title float-start mb-0">{{ __('category.Categories') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('category.Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('category.Category List') }}
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
            <!-- Basic table -->
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <table class="datatables-basic ib-withdraw table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>id</th>
                                        <th>{{ __('category.Type') }}</th>
                                        <th>{{ __('category.Category Name') }}</th>
                                        <th>{{ __('category.Priority') }}</th>
                                        <th>{{ __('category.Created Date') }}</th>
                                        <th>{{ __('category.ACTIONS') }}</th>

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
<div class="modal modal-slide-in fade" id="add-category">
    <div class="modal-dialog sidebar-sm">
        <form class="add-new-category modal-content pt-0">
            @csrf
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="exampleModalLabel">New Category</h5>
            </div>
            @if (Auth::user()->hasDirectPermission('create category manager'))
            <div class="modal-body flex-grow-1">
                <div class="mb-1">
                    <div class="form-element">
                        <label class="form-label" for="client-type">Client Type</label>
                        <select class="select2 form-select dt-client-type" name="client_type" id="client-type">
                            <optgroup>
                                <option value="demo">Demo</option>
                                <option value="trader">Trader</option>
                                <option value="ib">Ib</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="mb-1">
                    <div class="form-element">
                        <label class="form-label" for="category-name">Category Name</label>
                        <input type="text" class="form-control dt-category-name" id="category-name" name="name" placeholder="Name of the category" />
                    </div>
                </div>
                <div class="mb-1">
                    <div class="form-element">
                        <label class="form-label" for="category-priority">Category Priority</label>
                        <select class="select2 form-select dt-category-priority" name="priority" id="category-priority">
                            <optgroup>
                                <option value="1">Normal</option>
                                <option value="2">Important</option>
                                <option value="3">Very Important</option>
                            </optgroup>
                        </select>
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
<div class="modal modal-slide-in fade" id="edit-category">
    <div class="modal-dialog sidebar-sm">
        <form class="edit-category-form modal-content pt-0">
            @csrf
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
            </div>
            @if (Auth::user()->hasDirectPermission('edit category manager'))
            <div class="modal-body flex-grow-1">
                <input type="hidden" id="edit_category-id" name="id" />

                <div class="mb-1">
                    <div class="form-element">
                        <label class="form-label" for="client-type">Client Type</label>
                        <select class="select2 form-select dt-client-type" name="client_type" id="edit_client-type">
                            <optgroup>
                                <option value="demo">Demo</option>
                                <option value="trader">Trader</option>
                                <option value="ib">Ib</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="mb-1">
                    <div class="form-element">
                        <label class="form-label" for="edit_category-name">Category Name</label>
                        <input type="text" class="form-control dt-category-name" id="edit_category-name" name="name" placeholder="Name of the category" />
                    </div>
                </div>
                <div class="mb-1">
                    <div class="form-element">
                        <label class="form-label" for="category-priority">Category Priority</label>
                        <select class="select2 form-select dt-category-priority" name="priority" id="edit_category-priority">
                            <optgroup>
                                <option value="1">Normal</option>
                                <option value="2">Important</option>
                                <option value="3">Very Important</option>
                            </optgroup>
                        </select>
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
@if (Auth::user()->hasDirectPermission('delete category manager'))
<div class="modal fade" id="delete-category">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalCenterTitle">Please Confirm</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" class="modal-content pt-0">
                @csrf
                <input type="hidden" id="delete_category-id" name="id" />

                <div class="modal-body my-3">
                    <h5 class="text-center">
                        Do You Really Want To Delete This Category?
                    </h5 class="text-center">
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger data-submit me-1">Yes Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
@else
<div class="modal fade" id="delete-category">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body my-3">
                <h5 class="text-center text-danger">
                    You Don't have right permission🔐
                </h5 class="text-center">
            </div>
        </div>
    </div>
</div>
@endif
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
    const categoriesData = "{{ url('admin/categories') }}?op=data-table";
    const addCategoryUrl = "{{ url('admin/categories') }}";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script src="{{ asset('admin-assets/assets/js/categories/categories.js') }}"></script>
@stop
<!-- END: page JS -->