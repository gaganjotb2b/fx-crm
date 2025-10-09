@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Blocked User')
@section('vendor-css')
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
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
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
                        <h2 class="content-header-title float-start mb-0">Blocked User</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{__('category.Settings')}}</a></li>
                                <li class="breadcrumb-item active">Blocked User</li>
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
                            <div class="card-header border-bottom d-flex justfy-content-between">
                                <h4 class="card-title">{{__('ad-reports.filter_report')}}</h4>
                                <div class="btn-exports" style="width:200px">
                                    <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                        <option value="download" data-icon="download" selected>{{__('ib-management.export')}} </option>
                                        <option value="csv" data-icon="file">CSV</option>
                                        <option value="excel" data-icon="file">Excel</option>
                                    </select>
                                </div>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form id="filterForm" class="dt_adv_search" method="POST">
                                    <div class="row g-1">
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By User Type">
                                            <label for="" class="form-label">User Type</label>
                                            <select class="select2 form-select" name="user_type" id="user_type">
                                                <optgroup label="Search By User Type">
                                                    <option value="">All Users</option>
                                                    <option value="2">Admin</option>
                                                    <option value="4">IB</option>
                                                    <option value="5">Manager</option>
                                                    <option value="0">Trader</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-4 ">
                                            <label for="" class="form-label">KYC Verification Status</label>
                                            <select class="select2 form-select" name="verification_status" id="verification_status">
                                                <optgroup label="Verification Status">
                                                    <option value="">{{__('ad-reports.all')}}</option>
                                                    <option value="2">{{__('ad-reports.pending')}}</option>
                                                    <option value="1">{{__('ad-reports.verified')}}</option>
                                                    <option value="0">{{__('ad-reports.unverified')}}</option>

                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="" class="form-label">Trader Info</label>
                                            <input type="text" class="form-control" data-column="1" name="trader_info" id="trader_info" placeholder="Trader Name / Email / Phone / Country" />
                                        </div>
                                        <div class="col-md-4">
                                            <label for="" class="form-label">IB Info</label>
                                            <input type="text" class="form-control" data-column="1" name="ib_info" id="ib_info" placeholder="IB Name / Email / Phone / Country" />
                                        </div>
                                        <div class="col-md-4">
                                            <label for="" class="form-label">Manager Info</label>
                                            <input type="text" class="form-control" data-column="1" name="manager_info" id="manager_info" placeholder="Account Manager / Desk Manager" />
                                        </div>
                                        <div class="col-md-4">
                                            <label for="" class="form-label">Trading Account Number</label>
                                            <input type="text" class="form-control" data-column="1" name="trading_account" id="trading_account" placeholder="Trading Account Number" />
                                        </div>
                                        <div class="col-md-4">
                                        <label for="" class="form-label">Joining Date</label>
                                            <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Joining Date">
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
                                        <div class="col-md-4">
                                        <label for="" class="form-label">Blocked Date</label>
                                            <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Blocked Date">
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
                                                <input id="block_from" type="text" name="block_from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                <span class="input-group-text">to</span>
                                                <input id="block_to" type="text" name="block_to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <label for=""></label>
                                            <button id="resetBtn" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('ad-reports.btn-reset')}}</span>
                                            </button>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <label for=""></label>
                                            <button id="filterBtn" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('category.FILTER')}}</span>
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
                            <div class="card-body mt-2 table-responsive">
                                <table id="fund_transfer_tbl" class="datatables-ajax int-fund-transfer table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>User Type</th>
                                            <th>Active Status</th>
                                            <th>Join Date</th>
                                            <th>Block Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- END: Content-->
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
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<!-- datatable -->
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>


<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>


<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>

<!-- datatable  -->
<script>
    // $(document).ready(function() {

    var dt = $('#fund_transfer_tbl').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "lengthChange": true,
        "buttons": true,
        "dom": 'B<"clear">lfrtip',
        buttons: [{
                extend: 'csv',
                text: 'csv',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },
                action: serverSideButtonAction
            },
            {
                extend: 'copy',
                text: 'Copy',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },
                action: serverSideButtonAction
            },
            {
                extend: 'excel',
                text: 'excel',
                className: 'btn btn-warning btn-sm',
                exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },
                action: serverSideButtonAction
            },
            {
                extend: 'pdf',
                text: 'pdf',
                className: 'btn btn-danger btn-sm',
                exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },
                action: serverSideButtonAction
            }
        ],
        "ajax": {


            "url": "/admin/report/blocked_user?op=data_table",
            "data": function(d) {
                return $.extend({}, d, {
                    "user_type" : $("#user_type").val(),
                    "verification_status" : $("#verification_status").val(),
                    "trader_info" : $("#trader_info").val(),
                    "ib_info" : $("#ib_info").val(),
                    "manager_info": $("#manager_info").val(),
                    "trading_account": $("#trading_account").val(),
                    "from": $("#from").val(),
                    "to": $("#to").val(),
                    "block_from": $("#block_from").val(),
                    "block_to": $("#block_to").val(),
                });
            }
        },

        "columns": [{
                "data": "name"
            },
            {
                "data": "email"
            },
            {
                "data": "user_type"
            },
            {
                "data": "active_status"
            },
            {
                "data": "join_date"
            },
            {
                "data": "block_date"
            },
            {
                "data": "action"
            },
        ],

        "drawCallback": function(settings) {
            $("#filterBtn").html("FILTER");
            $("#total_amount").html('$' + settings.json.total_amount);
        }


    });
    $('#filterBtn').click(function(e) {
        dt.draw();
    });

    // datatable export function
    $(document).on("change", "#fx-export", function() {
        if ($(this).val() === 'csv') {
            $(".buttons-csv").trigger('click');
        }
        if ($(this).val() === 'excel') {
            $(".buttons-excel").trigger('click');
        }

    });

    function serverSideButtonAction(e, dt, node, config) {

        var me = this;
        var button = config.text.toLowerCase();
        if (typeof $.fn.dataTable.ext.buttons[button] === "function") {
            button = $.fn.dataTable.ext.buttons[button]();
        }
        var len = dt.page.len();
        var start = dt.page();
        dt.page(0);

        // Assim que ela acabar de desenhar todas as linhas eu executo a função do botão.
        // ssb de serversidebutton
        dt.context[0].aoDrawCallback.push({
            "sName": "ssb",
            "fn": function() {
                $.fn.dataTable.ext.buttons[button].action.call(me, e, dt, node, config);
                dt.context[0].aoDrawCallback = dt.context[0].aoDrawCallback.filter(function(e) {
                    return e.sName !== "ssb"
                });
            }
        });
        dt.page.len(999999999).draw();
        setTimeout(function() {
            dt.page(start);
            dt.page.len(len).draw();
        }, 500);
    }


    $(document).ready(function() {
        $("#resetBtn").click(function() {
            $("#filterForm")[0].reset();
            $('#user_type').prop('selectedIndex', 0).trigger("change");
            $('#verification_status').prop('selectedIndex', 0).trigger("change");
            dt.draw();
        });
    });


    // update settings
    $(document).on('click', '#enable_btn', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Unblock User',
            html: 'Do you want to unblock this user?',
            icon: 'warning',
            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-warning',
                cancelButton: 'btn btn-danger'
            },
            closeOnCancel: false,
            closeOnConfirm: false,
        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                $('#send-mail-pass').modal('toggle');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/report/unblock_user/' + id,
                    method: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        if (data.success === true) {
                            // Swal.fire({
                            //     icon: 'success',
                            //     title: 'Unblock User',
                            //     html: 'Successfully Unblocked!',
                            //     customClass: {
                            //         confirmButton: 'btn btn-success'
                            //     }
                            // });
                            notify('success', 'Successfully Unblocked!', 'Unblock User');
                            dt.draw();
                        } else {
                            // Swal.fire({
                            //     icon: 'error',
                            //     title: 'Unblock User',
                            //     html: 'Failed To Unblock!',
                            //     customClass: {
                            //         confirmButton: 'btn btn-danger'
                            //     }
                            // });
                            notify('error', 'Failed To Unblock!', 'Unblock User');
                        }
                    }
                });
            }

        });
    });
</script>


@stop
<!-- BEGIN: page JS -->