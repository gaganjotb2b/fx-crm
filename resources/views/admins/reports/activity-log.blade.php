@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Activity Logs')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/vendors.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css')}}">
<link rel="preconnect" href="https://fonts.gstatic.com">
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

<style>
    /* for Laptop */
    @media screen and (max-width: 1280px) and (min-width: 800px) {

        .ib-withdraw thead tr th:nth-child(6),
        .ib-withdraw tbody tr td:nth-child(6) {
            display: none;
        }

        .small-none {
            display: none;
        }
    }


    @media screen and (max-width: 1440px) and (min-width: 900px) {

        .ib-withdraw thead tr th:nth-child(6),
        .ib-withdraw tbody tr td:nth-child(6) {
            display: none;
        }

        .small-none {
            display: none;
        }
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
                        <h2 class="content-header-title float-start mb-0">{{__('page.activity_log')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}">{{__('admin-breadcrumbs.home')}}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#">{{__('admin-breadcrumbs.reports')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('page.activity_log')}}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                        <button class="btn-icon btn btn-primary btn-round btn-sm" type="button"
                            aria-haspopup="true" aria-expanded="false" id="advance-filter-btn">Advance Filetr</button>
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
                        <div class="card d-none" id="filter-form">
                            <div class="card-header border-bottom d-flex justfy-content-between">
                                <h4 class="card-title">{{__('client-management.Report Filter')}}</h4>
                                <div class="btn-exports d-flex justify-content-between">
                                    <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                        <option value="download" data-icon="download" selected>{{__('client-management.Export')}}</option>
                                        <option value="csv" data-icon="file">CSV</option>
                                        <option value="excel" data-icon="file">Excel</option>
                                    </select>
                                </div>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form class="dt_adv_search" method="POST" id="filter-form">
                                    <div class="row g-1 mb-md-1">
                                        <!-- Search By Month -->
                                        <div class="col-md-4">
                                            <label class="form-label" for="month">{{__('page.search_by_month')}}</label>
                                            <select class="select2 form-select" id="month" name="month">
                                                <option value="">{{__('page.all')}}</option>
                                                <option value="this_month">{{__('page.this_month')}}</option>
                                                <option value="last_month">{{__('page.last_month')}}</option>
                                            </select>
                                        </div>
                                        <!-- Search By User Type -->
                                        <div class="col-md-4">
                                            <label for="user_type" class="form-label">User Type</label>
                                            <select class="select2 form-select" name="user_type" id="user_type">
                                                <optgroup label="Search By User Type">
                                                    <option value="">All Users</option>
                                                    <option value="1">System</option>
                                                    <option value="2">Admin</option>
                                                    <option value="4">IB</option>
                                                    <option value="5">Manager</option>
                                                    <option value="0">Trader</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <!-- Search By Activity -->
                                        <div class="col-md-4">
                                            <label for="activity" class="form-label">Activity</label>
                                            <select class="select2 form-select" name="activity" id="activity">
                                                <optgroup label="Search By Activity">
                                                    <option value="">All</option>
                                                    @foreach($activitys as $result)
                                                    <option value="{{ $result->log_name }}">{{ ucwords($result->log_name)}}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                        <!-- Search By Event -->
                                        <div class="col-md-4">
                                            <label for="event" class="form-label">Event</label>
                                            <select class="select2 form-select" name="event" id="event">
                                                <optgroup label="Search By Event">
                                                    <option value="">All</option>
                                                    @foreach($events as $result)
                                                    <option value="{{ $result->event }}">{{ ucwords($result->event)}}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                        <!-- Search By Trading Account -->
                                        <div class="col-md-4">
                                            <label class="form-label">Trading Account</label>
                                            <input id="trading_account" name="trading_account" type="text" class="form-control dt-input" data-column="4" placeholder="Trading Account Number" data-column-index="3" />
                                        </div>
                                        <!-- Search By Trader Info -->
                                        <div class="col-md-4">
                                            <label class="form-label">Trader Info</label>
                                            <input id="trader_info" name="trader_info" type="text" class="form-control dt-input" data-column="4" placeholder="Trader Name / Email / Phone / Country" data-column-index="3" />
                                        </div>
                                        <!-- Search By IB Info -->
                                        <div class="col-md-4">
                                            <label class="form-label">IB Info</label>
                                            <input id="ib_info" name="ib_info" type="text" class="form-control dt-input" data-column="4" placeholder="IB Name / Email / Phone / Country" data-column-index="3" />
                                        </div>
                                        <!-- Search By Manager Info -->
                                        <div class="col-md-4">
                                            <label class="form-label">Manager Info</label>
                                            <input id="manager_info" name="manager_info" type="text" class="form-control dt-input" data-column="4" placeholder="Manager Name / Email / Phone" data-column-index="3" />
                                        </div>
                                        <!-- Search By Request Date -->
                                        <div class="col-md-4">
                                            <label class="form-label">Request Date</label>
                                            <div class="mb-0">
                                                <input type="text" class="form-control dt-date flatpickr-range dt-input" data-column="5" placeholder="StartDate to EndDate" data-column-index="4" name="dt_date" />
                                                <input type="hidden" class="form-control dt-date start_date dt-input" data-column="5" data-column-index="4" id="value_from_start_date" name="value_from_start_date" />
                                                <input type="hidden" class="form-control dt-date end_date dt-input" id="value_from_end_date" name="value_from_end_date" data-column="5" data-column-index="4" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-1">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4 d-grid">
                                            <button id="btn-reset" type="button" class="btn btn-secondary">{{__('client-management.Reset')}}</button>
                                        </div>
                                        <div class="col-md-4 d-grid">
                                            <button id="btn-filter" type="button" class="btn btn-primary">{{__('client-management.Filter')}}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <hr class="my-0" />
                        </div>

                        <div class="card">
                            <div class="card-datatable table-responsive">
                                <table class="datatables-ajax ib-withdraw table">
                                    <thead>
                                        <tr>
                                            <th>{{__('finance.Name')}}</th>
                                            <th>{{__('ad-reports.user-type')}}</th>
                                            <th>{{__('ad-reports.email')}}</th>
                                            <th>{{__('page.activity')}}</th>
                                            <th>{{__('page.event')}}</th>
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
</div>
<!-- END: Content-->
<!-- Modal Themes start -->
<!-- add new trader modal -->
<div class="modal fade text-start modal-primary" id="add-new-trader" tabindex="-1" aria-labelledby="Add New Trader" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{route('admin.trader-admin-add-new-trader')}}" method="post" class="modal-content" id="trader-registration-form">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add New Trader</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <!-- full name -->
                <div class="mb-1">
                    <label class="form-label" for="full-name">Full Name</label>
                    <input type="text" class="form-control" id="full-name" name="full_name" placeholder="Ex: John Arifin" />
                </div>
                <!-- emmail -->
                <div class="mb-1">
                    <label class="form-label" for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Ex: mail@example.como" />
                </div>
                <!-- phone -->
                <div class="mb-1">
                    <label class="form-label" for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="+8801747XXXXXXX" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary mb-1 text-center" id="btn-add-new-trader" onclick="_run(this)" data-el="fg" data-form="trader-registration-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="trader_registration_call_back" data-btnid="btn-add-new-trader" style="width:200px">Save Trader</button>
            </div>
        </form>
    </div>
</div>
<!-- end add new trader modal -->
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
<!-- <script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js')}}"></script> -->
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>

<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-select2.js')}}"></script>
<script>
    // start: add mew traders--------------------------------------------------
    function trader_registration_call_back(data) {
        $.validator("trader-registration-form", data.errors);
        if (data.trader_registration == true) {
            toastr['success']('New Trader Successfully Registered', 'Trader Registration', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });
            $("#add-new-trader").modal('hide');
            $("#trader-registration-form").trigger('reset');
            $("#server, #client-type, #account-type, #leverage, #country").trigger("change");
        } else {
            toastr['error']('New Trader registration Failed', 'Trader Registration', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });
        }
        // message for create trading account
        if (data.create_trading_account == true) {
            toastr['success']('Trading Account Successfully Created', 'Trading Account', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });
        } else {
            toastr['error']('Trading Account Creation Failed', 'Trading Account', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });
        }
        // sending welcome mail-----------------
        if (data.welcome_mail == true) {
            let trader_id = data.trader_id;
            Swal.fire({
                title: 'Welcome Email',
                text: 'Are You Confirm to send welcome mail ?',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Send Email',
                showLoaderOnConfirm: true,
                preConfirm: (login) => {
                    $(".swal2-html-container").text("We Sending Email, Please Wait.....")
                    return fetch(`/admin/client-management/send-welcome-email/` + trader_id)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText)
                            }
                            return response.json()
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Request failed: ${error}`
                            )
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    if (result.value.status == false) {
                        toastr['error'](result.value.message, 'Welcome Email', {
                            showMethod: 'slideDown',
                            hideMethod: 'slideUp',
                            closeButton: true,
                            tapToDismiss: false,
                            progressBar: true,
                            timeOut: 2000,
                        });
                    } else {
                        toastr['success'](result.value.message, 'Welcome Email', {
                            showMethod: 'slideDown',
                            hideMethod: 'slideUp',
                            closeButton: true,
                            tapToDismiss: false,
                            progressBar: true,
                            timeOut: 2000,
                        });
                    }

                }
            })
            $(".swal2-confirm").trigger("click");
        }
        $('.datatables-ajax').DataTable().draw();
    }
    // END: assign account manager-----------------------------------------------------
    
    //----------------------------------------Activity Log Js Start---------------------------------
    
// Datepicker for advanced filter
// ---------------------------------------------------------------------------
var separator = ' - ',
    rangePickr = $('.flatpickr-range'),
    dateFormat = 'MM/DD/YYYY';
var options = {
    autoUpdateInput: false,
    autoApply: true,
    locale: {
        format: dateFormat,
        separator: separator
    },
    opens: $('html').attr('data-textdirection') === 'rtl' ? 'left' : 'right'
};

//Range Picker
// ---------------------------------------------------------------------------------------------
if (rangePickr.length) {
    rangePickr.flatpickr({
        mode: 'range',
        dateFormat: 'm/d/Y',
        onClose: function (selectedDates, dateStr, instance) {
            var startDate = '',
                endDate = new Date();
            if (selectedDates[0] != undefined) {
                startDate =
                    selectedDates[0].getMonth() + 1 + '/' + selectedDates[0].getDate() + '/' + selectedDates[0].getFullYear();
                $('.start_date').val(startDate);
            }
            if (selectedDates[1] != undefined) {
                endDate =
                    selectedDates[1].getMonth() + 1 + '/' + selectedDates[1].getDate() + '/' + selectedDates[1].getFullYear();
                $('.end_date').val(endDate);
            }
            $(rangePickr).trigger('change').trigger('keyup');
        }
    });
}

// Advanced Search Functions Ends

$(function () {
    var isRtl = $('html').attr('data-textdirection') === 'rtl';

    var dt_ajax_table = $('.datatables-ajax'),
        assetPath = '../../../app-assets/';

    if ($('body').attr('data-framework') === 'laravel') {
        assetPath = $('body').attr('data-asset-path');
    }

    // Ajax Sourced Server-side datatable
    // --------------------------------------------------------------------
    if (dt_ajax_table.length) {

        feather.replace();
        var datatable = dt_ajax_table.DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "lengthChange": false,
            "buttons": true,
            "dom": 'B<"clear">lfrtip',
            buttons: [
                {
                    extend: 'csv',
                    text: 'csv',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4,5]
                    },
                    action: serverSideButtonAction
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    className: 'btn btn-warning btn-sm',
                    action: serverSideButtonAction
                },
            ],
            "ajax": {
                "url": "/admin/report/activity-log-dt",
                "data": function (d) {
                    return $.extend({}, d, {
                        "month": $("#month").val(),
                        "user_type": $("#user_type").val(),
                        "activity": $("#activity").val(),
                        "event": $("#event").val(),
                        "trading_account": $("#trading_account").val(),
                        "trader_info": $("#trader_info").val(),
                        "ib_info": $("#ib_info").val(),
                        "manager_info":$("#manager_info").val(),
                        "value_from_start_date": $("#value_from_start_date").val(),
                        "value_from_end_date": $("#value_from_end_date").val(),
                    });
                }
            },

            "columns": [
                { "data": "name","orderable":false },
                { "data": "user_type", "orderable":false},//<---as user type like IB/Trader
                { "data": "email", "orderable":false },
                { "data": "activity"},
                { "data": "event" },
                { "data": "date" },
            ],
            "columnDefs": [ {
                "targets": 1,
                "orderable": false
                } ],
            "order": [[5, 'desc']],
            "drawCallback": function (settings) {
                var rows = this.fnGetData();
                if (rows.length !== 0) {
                    feather.replace();
                }
            }
        });
        // Filter operation
        $("#btn-filter").on("click", function (e) {
            console.log($(".start_date").val());
            datatable.draw();
        });
        // reset operation
        $("#btn-reset").on("click", function (e) {
            $(".start_date").val('');
            $(".end_date").val('');
            $('#month').prop('selectedIndex', 0).trigger("change");
            $('#user_type').prop('selectedIndex', 0).trigger("change");
            $('#activity').prop('selectedIndex', 0).trigger("change");
            $('#event').prop('selectedIndex', 0).trigger("change");
            $("#filter-form").trigger('reset');
            datatable.draw();
        });

    }

    // datatable description
    dt_description(null,'/admin/report/activity-log-dt-desctiption/',true);

    // datatable export function
    $(document).on("change", "#fx-export", function () {
        if ($(this).val() === 'csv') {
            $(".buttons-csv").trigger('click');
        }
        if ($(this).val() === 'excel') {
            $(".buttons-excel").trigger('click');
        }

    });


    // Filter form control to default size for all tables
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm').removeClass('form-control-sm');
    // block unblock-------------------------------------------------
    $(document).on("change click", ".switch-user-block", function () {
        let warning_title = "";
        let warning_msg = "";
        let request_for;
        let id = $(this).val();
        console.log(id);
        if ($(this).is(":checked") || ($(this).data('request_for') != "" && $(this).data('request_for') === 'block')) {
            warning_title = 'Are you sure? to Block this user!';
            warning_msg = 'If you want to Block this User please click OK, otherwise simply click cancel'
            request_for = 'block'
        }
        else if ($(this).is(":not(:checked)")) {
            warning_title = 'Are you sure? to Unblock this user!';
            warning_msg = 'If you want to Unblock this User please click OK, otherwise simply click cancel'
            request_for = 'unblock'
        }
        let data = { id: id, request_for: request_for };
        let request_url = '/admin/client-management/trader-admin-block-trader';
        confirm_alert(warning_title, warning_msg, request_url, data, 'User ' + request_for);
    })
});

$(document).on("click", "#advance-filter-btn", function() {
            $("#filter-form").toggleClass("d-none");
        });
    //----------------------------------------Activity Log Js End---------------------------------
</script>
@stop
<!-- BEGIN: page JS -->