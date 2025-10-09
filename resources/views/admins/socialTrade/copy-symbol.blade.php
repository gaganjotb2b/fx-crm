@extends('layouts.admin-layout')
@section('title','Copy symbols')
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
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/assets/css/config-form.css')}}">
<style>
    div.dataTables_wrapper div.dataTables_filter select,
    div.dataTables_wrapper div.dataTables_length select {
        background-position: calc(100% - 3px) 4px, calc(100% - 20px) 13px, 100% 0;

    }
</style>
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Social Trade Configuration</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">Configurations</a></li>
                                <li class="breadcrumb-item active">Social Trade Configuration</li>
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
                <div class="col-4">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <div class="card my-0 py-0">
                                <div class="card-body my-0 py-0">
                                    <h4 class="card-title">Social Trade Configurations</h4>
                                </div>

                            </div>
                            <button type="submit" class="btn btn-primary" onclick="activePamm()">Active Social Trade</button>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <div class="card my-0 py-0">
                                <div class="card-body my-0 py-0">
                                    <h4 class="card-title">Add Symbol</h4>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary stretched-link text-nowrap add-new-role" data-bs-target="#offcanvasBackdrop" data-bs-toggle="offcanvas" aria-controls="offcanvasBackdrop">Add Symbol</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!--Search Form -->
                        <div class="card-body mt-2 table-responsive">
                            <table id="deposit_report_tbl" class="datatables-ajax ib-withdraw table">
                                <thead>
                                    <tr>
                                        <th>Symbol Name</th>
                                        <th>IB Rebate</th>
                                        <th>Group Name</th>
                                        <th>Added By</th>
                                        <th>Visibility</th>
                                        <th>Created At</th>
                                        <th>{{__('category.Actions')}}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <hr class="my-0" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Enable backdrop (default) -->
<div class="enable-backdrop">
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasBackdrop" aria-labelledby="offcanvasBackdropLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasBackdropLabel" class="offcanvas-title">Add New Symbol</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body my-auto mx-0 flex-grow-0">
            <p>
                Add New Symbol if does not exist
            </p>
            <form action="{{ route('system.add.symbol') }}" method="post" id="admin-group-form">
                @csrf
                <!-- symbol name -->
                <label class="form-label" for="group-name">Symbol Name</label>
                <input id="symbol_name" class="form-control" type="text" placeholder="Symbol Name" name="symbol_name" />
                <!-- symbol org -->
                <label class="form-label" for="group-name">Symbol ORG</label>
                <input id="symbol_org" class="form-control" type="text" placeholder="Symbol Org" name="symbol_org" />
                <!-- title -->
                <label class="form-label" for="group-control">Title</label>
                <input id="title" class="form-control" type="text" placeholder="Title" name="title" />
                <!-- IB rebate -->
                <label class="form-label" for="group-name">IB Rebate</label>
                <select class="select2 form-select" id="ib_rebate" name="ib_rebate">
                    <option value="YES">YES</option>
                    <option value="NO">NO</option>
                </select>
                <!-- group  -->
                <label class="form-label" for="group-name">Groups</label>
                <select class="select2 form-select" id="client_group" name="client_group">
                    @foreach($groups as $value)
                    <option value="{{$value->id}}">{{$value->group_name}}</option>
                    @endforeach
                </select>
                <!-- visibility -->
                <label class="form-label" for="group-name">Visible</label>
                <select class="select2 form-select" id="visible" name="visible">
                    <option value="visible">Visible</option>
                    <option value="hidden">Hidden</option>
                </select>

                <button type="button" class="btn btn-primary mb-1 d-grid w-100 mt-1" id="save-group" onclick="_run(this)" data-el="fg" data-form="admin-group-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="dataCallback" data-btnid="save-group">Save</button>
                <button type="button" id="add-group-cancel" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="offcanvas">
                    {{ __('page.cancel') }}
                </button>
            </form>

        </div>
    </div>
</div>
<!--/ Enable backdrop (default) -->
<!--Delete Currency Pair Modal End-->
<div class="modal fade" id="currency-pair-delete-form" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Confirmation</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" class="modal-content pt-0">
                @csrf
                <input type="hidden" name="id" id="currency-pair-delete-id">
                <div class="modal-body my-3">
                    <h4 class="text-center">
                        Do you really want to delete these records? This process cannot be undone.
                    </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger data-submit me-1" id="currency-pair-delete">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Delete Finace Modal End-->
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
<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- toaster js -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/system-page-configuration.js')}}"></script>
<!-- form hide/show -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-config-form.js')}}"></script>
<script>
    function activePamm() {
        // alert('dasijdfk');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: '/system/pamm-setting-process',
            dataType: 'json',
            data: '',
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data.status == false) {

                    toastr['error']('Failed To Update!', 'Social Trade Configuration', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                }
                if (data.status == true) {
                    toastr['success'](data.message, 'Social Trade Configuration', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                }
            }
        });
    }

    function dataCallback(data) {
        if (data.status == false) {
            toastr['error']('Failed To Update!', 'Please fix the following error', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });
            $.validator("admin-group-form", data.error);
        }
        if (data.status == true) {
            $("#client_group").trigger('change');
            $("#admin-group-form")[0].reset();
            toastr['success'](data.message, 'Symbol', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });
            dt.draw();
        }
    }


    //data table
    var dt = $('#deposit_report_tbl').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "lengthChange": true,
        "ajax": {
            "url": "/system/add-symbol-table-process",
        },
        "columns": [{
                "data": "symbol"
            },
            {
                "data": "ib_rebate"
            },
            {
                "data": "group_name"
            },
            {
                "data": "added_by"
            },
            {
                "data": "visible"
            },
            {
                "data": "date"
            },
            { 
                "data": "action"
            }
        ],

        "drawCallback": function(settings) {
            $("#filterBtn").html("FILTER");
            $("#total_amount").html('$' + settings.json.total_amount);
            var rows = this.fnGetData();
            if (rows.length !== 0) {
                feather.replace();
            }
        }


    });

    $('#filterBtn').click(function(e) {
        dt.draw();
    });
    
    // passing id to currency pair settings delete modal
    $(document).on("click", "#currency-pair-delete-button", function () {
        let id = $(this).attr("data-id");
        $("#currency-pair-delete-id").val(id);
    });

    // currency pair delete action
    $(document).on("click", "#currency-pair-delete", function (event) {
        let id = $('#currency-pair-delete-id').val();
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "/admin/settings/currency-pair-delete/" + id,
            dataType: "json",
            // data: { 'id': id },
            cache: false,
            contentType: false,
            processData: false,
    
            success: function (data) {
                if (data.status == false) {
                    notify('error', data.message, 'Currency Pair');
                }
                if (data.status == true) {
                    notify('success', data.message, 'Currency Pair');
                    $('#currency-pair-delete-form').modal('hide');
                    dt.draw();
                }
            }
        });
    });  //END: click function 
</script>

@stop
<!-- BEGIN: page JS -->