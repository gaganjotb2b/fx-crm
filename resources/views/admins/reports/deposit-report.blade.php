@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Trader Deposit Report')
@section('vendor-css')
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
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">

<style>
    /* for Laptop */
    /* @media screen and (max-width: 1280px) and (min-width: 800px) {
                    .ib-withdraw thead tr th:nth-child(3),
                    .ib-withdraw tbody tr td:nth-child(3){
                        display: none;
                    }
                .small-none{
                    display: none;
                }
                }
    
        @media screen and (max-width: 1280px) and (min-width: 800px) {
            .ib-withdraw thead tr th:nth-child(6),
            .ib-withdraw tbody tr td:nth-child(6){
                display: none;
            }
           .small-none-two{
            display: none;
           }
        }
    
    
        
        @media screen and (max-width: 1440px) and (min-width: 900px) {
            .ib-withdraw thead tr th:nth-child(6),
            .ib-withdraw tbody tr td:nth-child(6){
                display: none;
            }
           .small-none{
            display: none;
           }
        } */
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
                        <h2 class="content-header-title float-start mb-0">{{ __('page.deposit_report') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">{{ __('ib-management.Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{ __('admin-breadcrumbs.reports') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('page.deposit_report') }}
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
                                        <option value="download" data-icon="download" selected>Export to</option>
                                        <option value="csv" data-icon="file">CSV</option>
                                        <option value="excel" data-icon="file">Excel</option>
                                    </select>
                                </div>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form id="filterForm" class="dt_adv_search" method="POST">
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-md-4 mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Transaction Type">
                                            <select class="select2 form-select" name="transaction_type" id="transaction_type">
                                                <optgroup label="Method">
                                                    <option value="">{{ __('ad-reports.all') }}</option>
                                                    @foreach ($deposit as $row)
                                                        <option value="{{ $row->transaction_type }}">{{ ucwords($row->transaction_type) }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-4  mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Status">
                                            <select class="select2 form-select" name="approved_status" id="approved_status">
                                                <optgroup label="Search By Status">
                                                    <option value="">{{ __('ad-reports.all') }}</option>
                                                    <option value="A">{{ __('ad-reports.approved') }}</option>
                                                    <option value="P">{{ __('ad-reports.pending') }}</option>
                                                    <option value="D">{{ __('ad-reports.declined') }}</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control dt-input dt-full-name" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Trader Name & Email" data-column="1" name="name" id="name" placeholder="Name /Email" data-column-index="0" />
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group" data-date="2017/01/01" data-date-format="yyyy/mm/dd" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Request Date">
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
                                            <div class="form-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By MIN MAX Amount Value">
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
                                        <div class="col-md-2 text-right">
                                            <button id="resetBtn" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{ __('ad-reports.btn-reset') }}</span>
                                            </button>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <button id="filterBtn" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{ __('category.FILTER') }}</span>
                                            </button>
                                        </div>

                                    </div>
                                    <div class="row g-1">

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
                            <!--Search Form -->
                            <div class="card-body mt-2 table-responsive">

                                <table id="deposit_report_tbl" class="datatables-ajax ib-withdraw table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('page.full-name') }}</th>
                                            <th>{{ __('page.email') }}</th>
                                            <!-- <th>{{ __('page.account') }}</th> -->
                                            <th>{{ __('page.Transactions') }} {{ __('page.type') }} </th>
                                            <th>{{ __('page.approved') }} {{ __('page.status') }}</th>
                                            <th>{{ __('page.created') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <hr class="my-0" />
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Ajax Sourced Server-side -->
        </div>
    </div>
</div>
<!-- END: Content-->
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
{{-- <script src="{{ asset('admin-assets/app-assets/vendors/js/vendors.min.js') }}"></script> --}}
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>

<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>


<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>


@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>

{{-- <script src="{{asset('admin-assets/app-assets/js/scripts/tables/table-datatable-deposit-report.js')}}"></script> --}}

<!-- datatable  -->
<script>
    $(document).ready(function() {

        var dt = $('#deposit_report_tbl').DataTable({
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
                    action: serverSideButtonAction
                },
                {
                    extend: 'copy',
                    text: 'Copy',
                    className: 'btn btn-success btn-sm',
                    action: serverSideButtonAction
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    className: 'btn btn-warning btn-sm',
                    action: serverSideButtonAction
                },
                {
                    extend: 'pdf',
                    text: 'pdf',
                    className: 'btn btn-danger btn-sm',
                    action: serverSideButtonAction
                }
            ],
            "ajax": {
                "url": "/admin/report/deposit-dt-proccess",
                "data": function(d) {
                    return $.extend({}, d, {
                        "name": $("#name").val(),
                        "email": $("#email").val(),
                        "from": $("#from").val(),
                        "to": $("#to").val(),
                        "min": $("#min").val(),
                        "max": $("#max").val(),
                        "transaction_type": $("#transaction_type").val(),
                        "approved_status": $("#approved_status").val(),
                        "verify_status": $("#verify_status").val(),
                        "manger_email": $("#manger_email").val(),

                    });
                }
            },

            "columns": [{
                    "data": "name"
                },
                {
                    "data": "email"
                },
                // {
                //     "data": "account"
                // },
                {
                    "data": "transaction_type"
                },
                {
                    "data": "approved_status"
                },
                {
                    "data": "created_at"
                },

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

    });

    //    datatable descriptions
    $(document).on("click", ".dt-description", function(params) {
        let __this = $(this);
        let user_id = $(this).data('id');

        $.ajax({
            type: "GET",
            url: '/admin/report/dt-description-deposit',
            dataType: 'json',
            success: function(data) {
                if (data.status == true) {
                    if ($(__this).closest("tr").next().hasClass("description")) {
                        $(__this).closest("tr").next().remove();
                        $(__this).find('.w').html(feather.icons['plus'].toSvg());
                    } else {
                        $(__this).closest('tr').after(data.description);
                        $(__this).closest('tr').next('.description').slideDown('slow').delay(5000);
                        // $(__this).find('svg').remove();
                        $(__this).find('.w').html(feather.icons['minus'].toSvg());

                        //Inner datatable
                        if ($(__this).closest("tr").next(".description").find('.datatable-inner')
                            .length) {
                            $(__this).closest("tr").next(".description").find('.datatable-inner')
                                .DataTable().clear().destroy();

                            var dt_inner = $(__this).closest('tr').next('.description').find(
                                '.datatable-inner').DataTable({
                                "processing": true,
                                "serverSide": true,
                                "searching": false,
                                "lengthChange": false,
                                "buttons": true,
                                "dom": 'Bfrtip',
                                "ajax": {
                                    "url": "/admin/report/deposit-dt-inner-proccess/" +
                                        user_id
                                },
                                "columns": [{
                                        "data": "amount_request"
                                    },
                                    {
                                        "data": "invoice"
                                    },
                                    {
                                        "data": "note"
                                    },
                                ],
                                "order": [
                                    [1, 'desc']
                                ]
                            });
                        }
                    }
                }
            }
        })
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

    /*<---------For reset button script-------------->*/
    $(document).ready(function() {
        $("#resetBtn").click(function() {
            $("#filterForm")[0].reset();
            $('#transaction_type').prop('selectedIndex', 0).trigger("change");
            $('#approved_status').prop('selectedIndex', 0).trigger("change");
        });
    });
</script>

@stop
<!-- BEGIN: page JS -->