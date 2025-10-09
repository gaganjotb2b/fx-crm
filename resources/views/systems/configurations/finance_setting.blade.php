@extends('layouts.system-layout')
@section('title','Finance Settings')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
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
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Finance Setting</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">Configurations</a></li>
                                <li class="breadcrumb-item active">Finance Setting</li>
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
                        <div class="card-header border-bottom">
                            <div class="card my-0 py-0">
                                <div class="card-body my-0 py-0">
                                    <h4 class="card-title">Finance Settings</h4>
                                </div>
                            </div>
                        </div>
                        <!-- finance setting form -->
                        <div class="card-body py-2 my-25" id="finance-setting-form">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 mb-1">
                                            <div class="card">
                                                <div class="card-body p-0">
                                                    <div class="card-body">
                                                        <div class="tab-content mt-3">
                                                            <form action="{{route('system.configurations.finance_setting_add')}}" class="pt-50" method="POST" enctype="multipart/form-data" id="finance-settings-form-add">
                                                                @csrf
                                                                <div class="tab-pane active" id="tab-panel" role="tabpanel" aria-labelledby="transaction-tab">
                                                                    <div class="col-12 col-sm-12 mb-1">
                                                                        <!-- transaction type  -->
                                                                        <div class="col-12 col-sm-6 mb-1" style="float: left; padding-right:1rem;" id="transaction_type">
                                                                            <label class="form-label">Transaction Type</label>
                                                                            <select class="select2 form-select" name="transaction_type">
                                                                                <option value="Deposit" <?php echo ((isset($transaction_type->transaction_type) && strtolower($transaction_type->transaction_type) == 'deposit') ?  'selected="selected"' : '') ?>>Deposit</option>
                                                                                <option value="Withdraw" <?php echo ((isset($transaction_type->transaction_type) && strtolower($transaction_type->transaction_type) == 'withdraw') ?  'selected="selected"' : '') ?>>Withdraw</option>
                                                                                <option value="a_to_w" <?php echo ((isset($transaction_type->transaction_type) && strtolower($transaction_type->transaction_type) == 'a_to_w') ?  'selected="selected"' : '') ?>>Account To Wallet</option>
                                                                                <option value="w_to_a" <?php echo ((isset($transaction_type->transaction_type) && strtolower($transaction_type->transaction_type) == 'w_to_a') ?  'selected="selected"' : '') ?>>Wallet To Account</option>
                                                                                <option value="a_to_a" <?php echo ((isset($transaction_type->transaction_type) && strtolower($transaction_type->transaction_type) == 'a_to_a') ?  'selected="selected"' : '') ?>>Account To Account</option>
                                                                                <option value="w_to_w" <?php echo ((isset($transaction_type->transaction_type) && strtolower($transaction_type->transaction_type) == 'w_to_w') ?  'selected="selected"' : '') ?>>Wallet To Wallet</option>
                                                                            </select>
                                                                        </div>
                                                                        <!-- transaction limit  -->
                                                                        <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                            <label class="form-label">Set Transaction Limit</label>
                                                                            <div class="input-group">
                                                                                <input type="number" name="min_transaction" class="form-control flatpickr-basic" placeholder="Min">
                                                                                <span class="input-group-text">To</span>
                                                                                <input type="number" name="max_transaction" class="form-control flatpickr-basic" placeholder="Max">
                                                                            </div>
                                                                        </div>
                                                                        <!-- transaction charge type  -->
                                                                        <div class="col-12 col-sm-6 mb-1" style="float: left; padding-right: 1rem;">
                                                                            <div class="card-body pb-0 social-media-card">
                                                                                <label class="form-label">Charge Type</label>
                                                                                <div class="social-media-filter border">
                                                                                    <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="All">
                                                                                        <input type="checkbox" class="form-check-input input-filter" name="fixed" id="fixed" data-value="fixed" checked />
                                                                                        <label class="form-check-label" for="fixed">Fixed(&dollar;)</label>
                                                                                    </div>
                                                                                    <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="All">
                                                                                        <input type="checkbox" class="form-check-input input-filter" name="percentage" id="percentage" data-value="percentage" checked />
                                                                                        <label class="form-check-label" for="percentage">Percentage(&percnt;)</label>
                                                                                    </div>
                                                                                </div>
                                                                                <span id="charge_type_error" class="text-danger"></span>
                                                                            </div>
                                                                        </div>
                                                                        <!-- charge limit -->
                                                                        <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                            <label class="form-label">Set Charge Limit</label>
                                                                            <div class="input-group">
                                                                                <input type="number" name="limit_start" class="form-control flatpickr-basic" placeholder="Start">
                                                                                <span class="input-group-text">To</span>
                                                                                <input type="number" name="limit_end" class="form-control flatpickr-basic" placeholder="End">
                                                                            </div>
                                                                        </div>
                                                                        <div class="clear-fixed"></div>
                                                                        <!-- KYC required  -->
                                                                        <div class="col-12 col-sm-4 mb-1" style="float: left; padding-right: 1rem;">
                                                                            <div class="card-body pb-0 social-media-card">
                                                                                <label class="form-label">KYC Required</label>
                                                                                <div class="social-media-filter border">
                                                                                    <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="KYC Required">
                                                                                        <input type="checkbox" class="form-check-input input-filter kyc" name="kyc" data-value="kyc" />
                                                                                        <label class="form-check-label" for="kyc">KYC Required For Finace Transaction</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- amount -->
                                                                        <div class="col-12 col-sm-2 mb-1" style="float: left; padding-right:1rem;">
                                                                            <label class="form-label">Charge (Amount)</label>
                                                                            <div class="input-group">
                                                                                <input id="charge" type="text" name="amount" class="form-control flatpickr-basic" placeholder="0$">
                                                                            </div>
                                                                            <span id="charge_error" class="text-danger"></span>
                                                                        </div>
                                                                        <!-- transaction permission -->
                                                                        <div class="col-12 col-sm-3 mb-1" style="float: left; padding-right:1rem;">
                                                                            <label class="form-label">Permission</label>
                                                                            <select class="select2 form-select" name="permission">
                                                                                <option value="panding">Panding</option>
                                                                                <option value="approved">Approved</option>
                                                                            </select>
                                                                        </div>
                                                                        <!-- active status -->
                                                                        <div class="col-12 col-sm-3 mb-1" style="float: left;">
                                                                            <label class="form-label">Active Status</label>
                                                                            <select class="select2 form-select" name="active_status">
                                                                                <option value="0">Deactivate</option>
                                                                                <option value="1">Activate</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="clear-fixed"></div>
                                                                    <div class="col-12">
                                                                        <input type="hidden" name="config_id" value="<?= (isset($configs->id) ? $configs->id : '') ?>">
                                                                        <div class="p-0 m-0">
                                                                            <button type="submit" class="btn btn-primary" style="float: right">Add Charge</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="clear-fixed"></div>
                                                                <!-- finance view and action table  -->
                                                                <!-- Dark Tables start -->
                                                                <div class="row" id="dark-table">
                                                                    <div class="col-12">
                                                                        <div class="card">
                                                                            <div class="card-header" style="padding-left: 0px;">
                                                                                <h4 class="card-title">View Finance Settings</h4>
                                                                            </div>
                                                                            <div class="table-responsive">
                                                                                <table id="finance_settings_table" class="datatables-basic table finance-settings-table scrollbar-primary">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Transaction Type</th>
                                                                                            <th>Transaction Limit</th>
                                                                                            <th>Charge Type</th>
                                                                                            <th>Charge Limit</th>
                                                                                            <th>KYC</th>
                                                                                            <th>Amount</th>
                                                                                            <th>Status</th>
                                                                                            <th>Active Status</th>
                                                                                            <th>Actions</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/finance setting form -->

                        <!--Delete Finace Modal End-->
                        <div class="modal fade" id="finance-setting-delete-modal">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="exampleModalCenterTitle">Confirmation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="" class="modal-content pt-0">
                                        @csrf
                                        <input type="hidden" name="id" id="finance-setting-delete-id" value="">
                                        <div class="modal-body my-3">
                                            <h4 class="text-center">
                                                Do you really want to delete these records? This process cannot be undone.
                                                </h5 class="text-center">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger data-submit me-1" data-bs-dismiss="modal" id="finance-setting-delete">Delete</button>
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
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<!-- datatable -->
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- toaster js -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/system-page-configuration.js')}}"></script>
<!-- form hide/show -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/system-config-form.js')}}"></script>
@stop
<!-- BEGIN: page JS -->