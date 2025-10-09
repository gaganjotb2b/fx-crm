@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Currency Pair')
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
                        <h2 class="content-header-title float-start mb-0">{{__('admin-menue-left.currency_pair')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{__('category.Settings')}}</a></li>
                                <li class="breadcrumb-item">{{__('admin-menue-left.currency_pair')}}</li>
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
        <div class="content-body pt-2">
            <!-- Role cards -->
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="d-flex align-items-end justify-content-center h-100">
                                    <img src="{{asset('admin-assets/app-assets/images/illustration/faq-illustrations.svg')}}" class="img-fluid mt-2" alt="Image" width="85" />
                                </div>
                            </div>
                            <div class="col-sm-7" style="padding-left: 0;">
                                <div class="card-body text-sm-end text-center ps-sm-0">
                                    <a href="javascript:void(0)" data-bs-target="#offcanvasBackdrop" data-bs-toggle="offcanvas" class="stretched-link add-new-role" aria-controls="offcanvasBackdrop">
                                        <span class="btn btn-primary mb-1">{{__('category.Add New Currency Pair')}}</span>
                                    </a>
                                    <p class="mb-0" style="text-align: left;">{{__('category.Add new currency pair, if it does not exist')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="row">
                            <!--Search Form -->
                            <div class="card-body">
                                <form id="filterForm" class="dt_adv_search" method="POST">
                                    <div class="row g-1">
                                        <div class="col-md-4">
                                            <select class="select2 form-select" name="active_status" id="active_status">
                                                <optgroup label="Status">
                                                    <option value="">{{__('page.all')}}</option>
                                                    <option value="0">{{__('category.Disable')}}</option>
                                                    <option value="1">{{__('category.Enable')}}</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control dt-input" data-column="1" name="symbol" id="symbol" placeholder="Currency Name" data-column-index="0" />
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control dt-input" data-column="1" name="title" id="title" placeholder="Title" data-column-index="1" />
                                        </div>
                                    </div>
                                    <div class="row g-1 mt-1">
                                        <div class="col-md-4">
                                            <select class="select2 form-select" name="ib_rebate" id="ib_rebate">
                                                <optgroup label="IB Rebate">
                                                    <option value="">{{__('category.Select IB Rebate')}}</option>
                                                    <option value="NO">{{__('category.No')}}</option>
                                                    <option value="YES">{{__('category.Yes')}}</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <button id="resetBtn" type="button" class="btn btn-secondary w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('category.RESET')}}</span>
                                            </button>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <button id="filterBtn" type="button" name="filter" value="filter" class="btn btn-primary w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('category.FILTER')}}</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Role cards -->

            <h3 class="mt-50">{{__('category.All Currency Pair')}}</h3>
            <!-- table -->
            <div class="card">
                <div class="table-responsive p-1">
                    <table class="user-list-table table pb-5" id="currency-pair-table">
                        <thead class="table-light">
                            <tr>
                                <th>{{__('category.Serial')}}</th>
                                <th>{{__('category.Currency name')}}</th>
                                <th>{{__('category.Title')}}</th>
                                <th>{{__('category.IB REBATE')}}</th>
                                <th>{{__('category.Active Status')}}</th>
                                <th>{{__('category.Actions')}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- table -->
        </div>
    </div>
</div>
<!-- END: Content-->

<!-- Add Currency Pair Modal -->
<div class="enable-backdrop">
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasBackdrop" aria-labelledby="offcanvasBackdropLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasBackdropLabel" class="offcanvas-title">Add New Currency Pair</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        @if(Auth::user()->hasDirectPermission('create currency pair'))
        <div class="offcanvas-body my-auto mx-0 flex-grow-0">
            <form action="{{route('admin.settings.currency-pair-add')}}" method="post" id="currency-pair-form">
                @csrf
                <div class="col-12 mt-1">
                    <label class="form-label" for="symbol">Currency Pair Name</label>
                    <input id="symbol" class="form-control" type="text" placeholder="Currency Pair Name" name="symbol" />
                </div>
                <div class="col-12 mt-1">
                    <label class="form-label" for="title">Title</label>
                    <input id="title" class="form-control" type="text" placeholder="Title" name="title" />
                </div>
                <div class="col-12 mt-1">
                    <label class="form-label" for="ib_rebate">IB Rebate</label>
                    <select class="select2 form-select" name="ib_rebate" id="ib_rebate">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>
                <div class="col-12 mt-1">
                    <label class="form-label">Active Status</label>
                    <select class="select2 form-select" name="status">
                        <option value="0">Deactivate</option>
                        <option value="1">Activate</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" id="save-currency-pair" class="btn btn-primary mb-1 d-grid w-100 mt-1">Save Group</button>
                    <button type="button" id="close-btn" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="offcanvas">Cancel</button>
                </div>
            </form>
        </div>
        @else
        <div class="card">
            <div class="card-body">
                @include('errors.permission')
            </div>
        </div>
        @endif
    </div>
</div>
<!--/ Enable backdrop (default) -->

<!--Edit Finace Modal -->
<div class="modal fade text-start" id="currency-pair-edit-form" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Update Currency Pair</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.setting.currency-pair.edit') }}" method="POST" enctype="multipart/form-data" id="currency-pair-edit-modal-form">
                @csrf
                <div class="modal-body">
                    <div class="col-12 mt-1">
                        <label class="form-label" for="symbol">Currency Pair Name</label>
                        <input id="modal-symbol" class="form-control" type="text" placeholder="Currency Pair Name" name="symbol" />
                    </div>
                    <div class="col-12 mt-1">
                        <label class="form-label" for="title">Title</label>
                        <input id="modal-title" class="form-control" type="text" placeholder="Title" name="title" />
                    </div>
                    <div class="col-12 mt-1">
                        <label class="form-label" for="modal_ib_rebate">IB Rebate</label>
                        <select class="select2 form-select" name="ib_rebate" id="modal_ib_rebate">
                            <option value="no">No</option>
                            <option value="yes">Yes</option>
                        </select>
                    </div>
                    <div class="col-12 mt-1">
                        <label class="form-label">Active Status</label>
                        <select class="select2 form-select" name="status" id="modal-status">
                            <option value="0">Deactivate</option>
                            <option value="1">Activate</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="currency_pair_id" id="currency_pair_id" value="">
                    <button type="button" class="btn btn-primary me-1 mb-1" id="currencyPairUpdateBtn" onclick="_run(this)" data-el="fg" data-form="currency-pair-edit-modal-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="currencyPairUpdateCallBack" data-btnid="currencyPairUpdateBtn">Save Change</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Edit Finace Modal End-->
<!--Delete Currency Pair Modal End-->
<div class="modal fade" id="currency-pair-delete-form">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalCenterTitle">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" class="modal-content pt-0">
                @csrf
                <input type="hidden" name="id" id="currency-pair-delete-id" value="">
                <div class="modal-body my-3">
                    <h4 class="text-center">
                        Do you really want to delete these records? This process cannot be undone.
                        </h5 class="text-center">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger data-submit me-1" data-bs-dismiss="modal" id="currency-pair-delete">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Delete Finace Modal End-->
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

<script src="{{asset('admin-assets/app-assets/js/scripts/pages/settings/admin-settings.js')}}"> </script>
<script>
    // if($('#select_option_design')){
    //     var select_option_design = $('#select_option_design');
    //     const example = new Choices(select_option_design);
    // }
</script>
@stop
<!-- BEGIN: page JS -->
