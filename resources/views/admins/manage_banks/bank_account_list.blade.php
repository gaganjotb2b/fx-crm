@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Bank Account List')
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

    .table.tbl-balanc.tbl-bank-list {
        border-collapse: separate;
        border-spacing: 3px;
    }

    .table.tbl-balanc.tbl-bank-list th {
        border: none !important;
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
                        <h2 class="content-header-title float-start mb-0">{{ __('page.bank-list') }}
                            {{ __('page.list') }}
                        </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}">{{ __('ib-management.Home') }}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#">{{ __('admin-menue-left.Manage_Banks') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('page.bank-list') }} {{ __('page.list') }}
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
                                <div class="btn-exports" style="width:200px">
                                    <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                        <option value="download" data-icon="download" selected>
                                            {{ __('ib-management.export') }}
                                        </option>
                                        <option value="csv" data-icon="file">CSV</option>
                                        <option value="excel" data-icon="file">Excel</option>
                                    </select>
                                </div>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form id="filter-form" class="dt_adv_search" method="POST">
                                    <div class="row g-1 mb-md-1">
                                        <!-- Filter By Approved Status -->
                                        <div class="col-md-4">
                                            <label for="approved_status" class="form-label">Approved Status</label>
                                            <select class="select2 form-select" name="approved_status" id="approved_status">
                                                <option value="" selected>{{ __('ad-reports.all') }}
                                                </option>
                                                <option value="a">{{ __('ad-reports.approved') }}</option>
                                                <option value="p">{{ __('ad-reports.pending') }}
                                                </option>
                                                <option value="d">{{ __('ad-reports.declined') }}</option>
                                            </select>
                                        </div>
                                        <!-- Filter By Client Type -->
                                        <div class="col-md-4">
                                            <label for="client_type" class="form-label">Client Type</label>
                                            <select class="select2 form-select" name="client_type" id="client_type">
                                                <optgroup label="Client Type">
                                                    <option value="" selected>All
                                                    </option>
                                                    <option value="4">IB</option>
                                                    <option value="0">Trader
                                                    </option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <!-- Filter By Trader Info -->
                                        <div class="col-md-4">
                                            <label for="trader_info" class="form-label">Trader Info.</label>
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="trader_info" id="trader_info" placeholder="Trader Name / Email / Phone / Country" data-column-index="0" />
                                        </div>
                                        <!-- Filter By IB info -->
                                        <div class="col-md-4">
                                            <label for="ib_info" class="form-label">IB Info.</label>
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="ib_info" id="ib_info" placeholder="IB Name / Email / Phone / Country" data-column-index="0" />
                                        </div>

                                        <!-- Filter By Trading Account Number -->
                                        <div class="col-md-4">
                                            <label for="account_number" class="form-label">Trading Account</label>
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="account_number" id="account_number" placeholder="Account Number" data-column-index="0" />
                                        </div>
                                        <!-- Filter By Bank Info -->
                                        <div class="col-md-4">
                                            <label for="bank_name_account" class="form-label">Bank Info.</label>
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="bank_name_account" id="bank_name_account" placeholder="Bank Name / Account Number" data-column-index="0" />
                                        </div>
                                    </div>
                                    <div class="row g-1 text-right">
                                        <!-- Filter By Request Date -->
                                        <div class="col-md-4">
                                            <label for="" class="form-label">Request Date</label>
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
                                        <div class="col-md-4 text-right">
                                            <label for=""></label>
                                            <button id="btn-reset" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{ __('ad-reports.btn-reset') }}</span>
                                            </button>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <label for=""></label>
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
                                <table class="datatables-ajax table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>{{ __('page.account-name') }}</th>
                                            <th>{{ __('page.email') }}</th>
                                            <th>{{ __('page.account-number') }}</th>
                                            <th>{{ __('page.client-type') }}</th>
                                            <th>{{ __('page.status') }}</th>
                                            <th>{{ __('page.bank-name') }}</th>
                                            <th>{{ __('page.date') }}</th>
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
    <!--Edit Finace Modal -->
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
        // Handle URL parameters for notification links
        const urlParams = new URLSearchParams(window.location.search);
        const statusParam = urlParams.get('status');
        const notificationId = urlParams.get('not');
        
        // Set status filter if coming from notification
        if (statusParam === 'pending') {
            $('#approved_status').val('p').trigger('change');
        } else if (statusParam === 'approved') {
            $('#approved_status').val('a').trigger('change');
        } else if (statusParam === 'declined') {
            $('#approved_status').val('d').trigger('change');
        }
        bank_account_list_report = dt_fetch_data(
            '/admin/manage_banks/bank_account_list/fetch_data',
            [{
                    "data": "plus"
                },
                {
                    "data": "account_name"
                },
                {
                    "data": "email"
                },
                {
                    "data": "account_number"
                },
                {
                    "data": "client_type"
                },
                {
                    "data": "status"
                },
                {
                    "data": "bank_name"
                },
                {
                    "data": "date"
                },
            ],
            true, true, true, [0, 1, 2, 3, 4], null, true, false
        );

        // bank account delete modal
        $(document).on("click", "#bank-account-delete-button", function(event) {
            let id = $(this).data('id');
            $('#bank-account-delete-id').val(id);
        });
        // bank account delete action
        $(document).on("click", "#bank-account-delete", function(event) {
            var id = $('#bank-account-delete-id').val();
            event.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "/admin/manage_banks/bank_account_list/delete/" + id,
                dataType: "json",
                data: id,
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    if (data.status == false) {
                        // Swal.fire({
                        //     icon: "error",
                        //     title: "Error found!",
                        //     html: $errors,
                        //     customClass: {
                        //         confirmButton: "btn btn-danger"
                        //     }
                        // });
                        notify('error',data.message,'Bank account delete');
                    }
                    if (data.status == true) {
                        // Swal.fire({
                        //     icon: "success",
                        //     title: "Deleted!",
                        //     html: data.message,
                        //     customClass: {
                        //         confirmButton: "btn btn-success"
                        //     }
                        // });
                        notify('success',data.message,'Bank account delete');
                        bank_account_list_report.draw();
                    }
                }
            });
        }); //END: click function
    });
    // bank account list fetch data end

    // bank account approve status modal
    $(document).on("click", "#bank-account-approve-status-button", function(event) {
        let id = $(this).data('id');
        let status = $(this).data('status');
        $('#bank-account-update-status-id').val(id);
        $('#bank-account-update-status').val(status);
        $('#bank-account-update-bank-ac-number').val(bank_ac_number);
        if (status == 'a') {
            $('#update-modal-message').html(
                '<h4 class="text-center" id="update-modal-message">Do you really want to <span class="text-success p-0 m-0">approve</span> these records?</h4>'
            );
        } else if (status == 'd') {
            $('#update-modal-message').html(
                '<h4 class="text-center" id="update-modal-message">Do you really want to <span class="text-danger p-0 m-0">decline</span> these records?</h4>'
            );
        }
    });


    //bank account approve status update callback
    function statusUpdateCallBack(data) {
        $('#statusUpdateBtn').prop('disabled', false);
        if (data.success) {
            // Swal.fire({
            //     icon: "success",
            //     title: "Bank Account List",
            //     html: data.message,
            //     customClass: {
            //         confirmButton: "btn btn-success"
            //     }
            // });
            notify('success',data.message,'Bank account list');
            $('#bank-account-approve-status-modal').modal('toggle');
            bank_account_list_report.draw();
        } else {
            notify('error', data.message, 'Bank Account List');
            $.validator("smtp-setup-form", data.errors);
        }
    }
    // update bank account callback
    function updateBankAccountCallBack(data) {
        $('#editBtn').prop('disabled', false);
        if (data.success) {
            notify('success', data.message, 'Bank Account List');
            bank_account_list_report.draw();
            $('#bank-account-edit-modal').modal('toggle');
        } else {
            notify('error', 'Please fix the following errors', 'Bank Account List');
            $.validator("smtp-setup-form", data.errors);
        }
    }

    // bank account list datatable description
    $(document).on("click", ".dt-description", function(params) {
        let __this = $(this);
        let id = $(this).data('id');
        let bank_ac_number = $(this).data('bank_ac_number');
        $.ajax({
            type: "GET",
            url: '/admin/manage_banks/bank_account_list/description/fetch_data/' + id,
            dataType: 'json',
            success: function(data) {
                if (data.status == true) {
                    if ($(__this).closest("tr").next().hasClass("description")) {
                        $(__this).closest("tr").next().remove();
                        $(__this).find('.w').html(feather.icons['plus'].toSvg());
                    } else {
                        $(__this).closest('tr').after(data.description);
                        $(__this).closest('tr').next('.description').slideDown('slow').delay(5000);

                        $(__this).find('.w').html(feather.icons['minus'].toSvg());
                    }
                }
            }
        });
    });


    // edit bank account
    $(document).on('click', '#bank-account-edit-button', function(event) {
        let id = $(this).data('id');
        $("#id").val(id);
        $.ajax({
            type: "GET",
            url: "/admin/manage_banks/bank_account/edit_modal/fetch_data/" + id,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,

            success: function(data) {
                if (data.status == false) {
                    Swal.fire({
                        icon: "error",
                        title: "Error found!",
                        html: $errors,
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
                    });
                }
                if (data.status == true) {
                    $("#user_bank_name").val(data.bank_name);
                    $("#bank_ac_name").val(data.bank_ac_name);
                    $("#bank_ac_number").val(data.bank_ac_number);
                    $("#bank_swift_code").val(data.bank_swift_code);
                    $("#bank_iban").val(data.bank_iban);
                    $("#bank_address").val(data.bank_address);
                    $("#bank_country").html(data.bank_country);
                }
            }
        });
    });
</script>
@stop
<!-- BEGIN: page JS -->