@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Bank Account List')
@section('vendor-css')
<!-- quill editor  -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
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
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/ib_admin.css') }}">
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
                        <h2 class="content-header-title float-start mb-0">{{__('page.bank-account')}} {{__('page.list')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="index.html">{{__('ib-management.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#">{{__('ib-management.Ib-Management')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('page.bank-account')}} {{__('page.list')}}</li>
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
            <!-- Ajax Sourced Server-side -->
            <section id="ajax-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-datatable m-1">
                                <table class="master_ib_table table table-responsive datatables-ajax">
                                    <thead>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>{{__('page.account-name')}}</th>
                                            <th>{{__('page.account-number')}}</th>
                                            <th>{{__('page.bank-name')}}</th>
                                            <th>{{__('page.date')}}</th>
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
                <form action="{{ route('admin.ib_management.bank_account.edit_modal.update') }}" method="POST" enctype="multipart/form-data" id="bank-account-edit-form">
                    @csrf
                    <div class="modal-body">
                        <div class="col-12 col-sm-12 mb-1" style="float: left; margin-right:1rem;">
                            <label class="form-label">Bank Name</label>
                            <div class="input-group">
                                <input id="bank_name" type="text" name="bank_name" class="form-control" value="" require>
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
                            </h5 class="text-center">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger data-submit me-1" data-bs-dismiss="modal" id="bank-account-delete">Confirm</button>
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
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js')}}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<!-- datatable -->
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>

<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>
@stop
<!-- END: page vendor js -->

<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>

<script>
    var bank_account_list_report;
    // bank account list fetch data
    $(document).ready(function() {
        bank_account_list_report = dt_fetch_data(
            '/admin/ib_management/bank_account_list/fetch-data',
            [{
                    "data": "plus"
                },
                {
                    "data": "account_name"
                },
                {
                    "data": "account_number"
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
                url: "/admin/ib_management/bank_account_list/delete/" + id,
                dataType: "json",
                data: id,
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
                        Swal.fire({
                            icon: "success",
                            title: "Deleted!",
                            html: data.message,
                            customClass: {
                                confirmButton: "btn btn-success"
                            }
                        });
                        bank_account_list_report.draw();
                    }
                }
            });
        }); //END: click function
    });
    // bank account list fetch data end

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
        $.ajax({
            type: "GET",
            url: '/admin/ib_management/bank_account_list/description/fetch-data/' + id,
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

                        // Inner datatable
                        if ($(__this).closest("tr").next(".description").find('.datatable-inner').length) {
                            $(__this).closest("tr").next(".description").find('.datatable-inner').DataTable().clear().destroy();
                            var dt_inner = $(__this).closest('tr').next('.description').find('.sub-ib-list').DataTable({
                                "processing": true,
                                "serverSide": true,
                                "searching": false,
                                "lengthChange": false,
                                "dom": 'Bfrtip',
                                "ajax": {
                                    "url": "/admin/ib-management/master-ib-report/description/inner-datatable/fetch-data/" + id
                                },
                                "columns": [{
                                        "data": "name"
                                    },
                                    {
                                        "data": "email"
                                    },
                                    {
                                        "data": "trader"
                                    },
                                    {
                                        "data": "sponsor"
                                    }
                                ],
                                "order": [
                                    [1, 'desc']
                                ],
                                "drawCallback": function(settings) {
                                    var rows = this.fnGetData();
                                    if (rows.length !== 0) {
                                        feather.replace();
                                    }
                                }
                            });
                        }
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
            url: "/admin/ib_management/bank_account/edit_modal/fetch-data/" + id,
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
                    $("#bank_name").val(data.bank_name);
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
