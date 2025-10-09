@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Company Bank List')
@section('vendor-css')
<!-- quill editor  -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
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
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/ib_admin.css') }}">
<style>
    table.dataTable.table-responsive {
        display: table !important;
    }
</style>
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content page-bank-list">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">
                            Company Bank List
                        </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}">{{ __('ib-management.Home') }}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#">{{ __('admin-menue-left.Manage_Banks') }}</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Company Bank List
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
            <!-- Ajax Sourced Server-side -->
            <section id="ajax-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-bottom d-flex justfy-content-between">
                                <h4 class="card-title">{{ __('ad-reports.filter_report') }}</h4>
                                <div class="btn-exports w-50">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                                <option value="download" data-icon="download" selected>
                                                    {{ __('ib-management.export') }}
                                                </option>
                                                <option value="csv" data-icon="file">CSV</option>
                                                <option value="excel" data-icon="file">Excel</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <a role="button" href="{{route('admin.bank-account-setup')}}" class="btn btn-primary waves-float waves-light waves-button-input w-100 btn-success">Add New Bank</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form id="filter-form" class="dt_adv_search" method="POST">
                                    <div class="row g-1 mb-md-1">
                                        <!-- Filter By Active Status -->
                                        <div class="col-md-4">
                                            <label for="approved_status" class="form-label">Active Status</label>
                                            <select class="select2 form-select" name="approved_status" id="approved_status">
                                                <optgroup label="Search By Status">
                                                    <option value="" selected>{{ __('ad-reports.all') }}</option>
                                                    <option value="0">Disable</option>
                                                    <option value="1">Active</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <!-- Filter By Bank Info. -->
                                        <div class="col-md-4">
                                            <label for="bank_info" class="form-label">Bank Info.</label>
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="bank_info" id="bank_info" placeholder="Bank Name / Account Number" data-column-index="0" />
                                        </div>
                                        <!-- Filter By Account Name -->
                                        <div class="col-md-4">
                                            <label for="account_name" class="form-label">Account Name</label>
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="account_name" id="account_name" placeholder="Account Name" data-column-index="0" />
                                        </div>
                                        <!-- Filter By Swift Code -->
                                        <div class="col-md-4">
                                            <label for="swift_code" class="form-label">Swift Code</label>
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="swift_code" id="swift_code" placeholder="Bank Swift Code" data-column-index="0" />
                                        </div>
                                        <!-- filter by amount min / max -->
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By MIN MAX Amount Value">
                                            <label for="amount" class="form-label">Amount</label>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        {{ __('ad-reports.min') }}
                                                    </span>
                                                    <input id="min" type="text" class="form-control" name="min">
                                                    <span class="input-group-text">-</span>
                                                    <input id="max" type="text" class="form-control" name="max">
                                                    <span class="input-group-text">{{ __('ad-reports.max') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Filter by Request Date -->
                                        <div class="col-md-4">
                                            <label for="" class="label-form">Request Date</label>
                                            <div class="input-group" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
                                                <span class="input-group-text">
                                                    <div class="icon-wrapper">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                                        </svg>
                                                    </div>
                                                </span>
                                                <input id="from" type="text" name="from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                <span class="input-group-text">to</span>
                                                <input id="to" type="text" name="to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                            </div>
                                        </div>


                                    </div>
                                    <div class="row g-1 mt-1 text-right">
                                        <div class="col-md-4">

                                        </div>
                                        <div class="col-md-4 text-right">
                                            <button id="btn-reset" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{ __('ad-reports.btn-reset') }}</span>
                                            </button>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <button id="btn-filter" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{ __('category.FILTER') }}</span>
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            <hr class="my-0" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-datatable m-1 table-responsive">

                                <table class="datatables-ajax table table-responsive" id="admin-banks">
                                    <thead>
                                        <tr>
                                            <th>Tab</th>
                                            <th>Tab Name</th>
                                            <th>Bank Name</th>
                                            <th>Account Name</th>
                                            <th>Account Number</th>
                                            <th>Bank Country</th>
                                            <th>Minimum Deposit</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Ajax Sourced Server-side -->
        </div>
    </div>
    <!--Edit bank Modal -->
    <div class="modal fade text-start" id="bank-account-edit-modal" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Update Bank Account</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.manage_banks.bank_account.edit_modal.update') }}" method="POST" enctype="multipart/form-data" id="bank-account-edit-form">
                    @csrf
                    <div class="modal-body">
                        <div class="col-12 col-sm-12 mb-1" style="float: left; margin-right:1rem;">
                            <label class="form-label">Bank Name</label>
                            <div class="input-group">
                                <input id="user_bank_name" type="text" name="user_bank_name" class="form-control" value="" require>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 mb-1" style="float: left; margin-right:1rem;">
                            <label class="form-label">Account Name</label>
                            <div class="input-group">
                                <input id="bank_ac_name" type="text" name="bank_ac_name" class="form-control" value="" require>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 mb-1" style="float: left; margin-right:1rem;">
                            <label class="form-label">Account Number</label>
                            <div class="input-group">
                                <input id="bank_ac_number" type="text" name="bank_ac_number" class="form-control" value="" require>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 mb-1" style="float: left; margin-right:1rem;">
                            <label class="form-label">Swift Code</label>
                            <div class="input-group">
                                <input id="bank_swift_code" type="text" name="bank_swift_code" class="form-control" value="" require>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 mb-1" style="float: left; margin-right:1rem;">
                            <label class="form-label">Bank IBAN</label>
                            <div class="input-group">
                                <input id="bank_iban" type="text" name="bank_iban" class="form-control" value="" require>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 mb-1" style="float: left; margin-right:1rem;">
                            <label class="form-label">Address</label>
                            <div class="input-group">
                                <input id="bank_address" type="text" name="bank_address" class="form-control" value="" require>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 mb-1" style="float: left; margin-right:1rem;">
                            <label for="bank_country" class="form-label">Country</label>
                            <div class="col-12">
                                <select class="select2 form-select" name="bank_country" id="bank_country">
                                    <?php
                                    print_r($countries);
                                    foreach ($countries as $row) {
                                    ?>
                                        <option value="<?= $row->id ?>"><?= $row->name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" id="id" value="">
                        <button type="button" class="btn btn-primary me-1 mb-1" id="editBtn" onclick="_run(this)" data-el="fg" data-form="bank-account-edit-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="updateBankAccountCallBack" data-btnid="editBtn">Save Change</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Edit Finace Modal End-->
    <!-- bank account list delete modal  -->
    <div class="modal fade" id="bank-account-delete-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalCenterTitle">Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" class="modal-content pt-0">
                    @csrf
                    <input type="hidden" name="id" id="bank-account-delete-id" value="">
                    <div class="modal-body my-3">
                        <h4 class="text-center">
                            Do you really want to delete these records? This process cannot be undone.
                        </h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger data-submit me-1" data-bs-dismiss="modal" id="bank-account-delete">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- bank account list approve status modal  -->
    <div class="modal fade" id="bank-account-approve-status-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalCenterTitle">Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-content pt-0" action="{{ route('admin.manage_banks.bank_account_request.update') }}" method="POST" enctype="multipart/form-data" id="bank-account-status-update-form">
                    @csrf
                    <input type="hidden" name="id" id="bank-account-update-status-id" value="">
                    <input type="hidden" name="status" id="bank-account-update-status" value="">
                    <div class="modal-body my-3">
                        <h4 class="text-center" id="update-modal-message">Do you really want to update this request?
                        </h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger data-submit me-1" id="statusUpdateBtn" onclick="_run(this)" data-el="fg" data-form="bank-account-status-update-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="statusUpdateCallBack" data-btnid="statusUpdateBtn">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->

<!-- Modal Themes end -->
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>

<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>
@stop
<!-- END: page vendor js -->

<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>

<script>
    var bank_account_list_report;
    // bank account list fetch data
    $(document).ready(function() {
        bank_account_list_report = dt_fetch_data(
            '/admin/manage_banks/company-bank-list/dt',
            [{
                    "data": "tab"
                },
                {
                    "data": "tab_name"
                },
                {
                    "data": "bank_name"
                },
                {
                    "data": "account_name"
                },
                {
                    "data": "account_number"
                },
                {
                    "data": "bank_country"
                },
                {
                    "data": "minimum_deposit"
                },
                {
                    "data": "action"
                },
            ],
            true, true, true, [0, 1, 2, 3, 4], null, true, false
        );
    });
    // bank account list fetch data end

    // activate bank account
    $(document).on("click", ".btn-active", function() {
        let id = $(this).data('id');
        Swal.fire({
            icon: 'warning',
            title: 'Are you sure? to activate this!',
            html: 'If you want to activate this bank account please click OK, otherwise simply click cancel',

            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-warning',
                cancelButton: 'btn btn-danger'
            },
            closeOnCancel: false,
            closeOnConfirm: false,
        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/manage_banks/company-bank-list/active/' + id,
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        if (data.status === true) {
                            toastr['success'](data.message, 'Bank account activate', {
                                showMethod: 'slideDown',
                                hideMethod: 'slideUp',
                                closeButton: true,
                                tapToDismiss: false,
                                progressBar: true,
                                timeOut: 2000,
                            });
                            $("#admin-banks").DataTable().draw();
                        } else {
                            Swal.fire({
                                icon: 'danger',
                                title: 'Activation failed!',
                                html: 'The bank account activation  failed, please try again later.',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })

            } //ending if condition 

        }); //ending swite alert
    })
    // disable bank account
    
    $(document).on('click', ".btn-disable", function() {
        let id = $(this).data('id');
        $(this).confirm2({
            request_url: '/admin/manage_banks/company-bank-list/disable/' + id,
            data: {
                id: id
            },
            click: false,
            title: 'Company bank disable',
            message: 'Are you confirm to disable company bank?',
            button_text: 'disable',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'Bank disable');
            } else {
                notify('error', data.message, 'Bank disable');
            }
            $("#admin-banks").DataTable().draw();
        });
    })
    // delete bank
    $(document).on('click', ".btn-delete", function() {
        let id = $(this).data('id');
        $(this).confirm2({
            request_url: '/admin/manage_banks/company-bank-list/delete/' + id,
            data: {
                id: id
            },
            click: false,
            title: 'Company bank delete',
            message: 'Are you confirm to delete company bank?',
            button_text: 'delete',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'Bank delete');
            } else {
                notify('error', data.message, 'Bank delete');
            }
            $("#admin-banks").DataTable().draw();
        });
    })
</script>
@stop
<!-- BEGIN: page JS -->