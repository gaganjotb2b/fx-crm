@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','IB Withdraw Request Report')
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
<style>
    /* for Laptop */
    @media screen and (max-width: 1280px) and (min-width: 800px) {

        .ib-withdraw thead tr th:nth-child(3),
        .ib-withdraw tbody tr td:nth-child(3) {
            display: none;
        }

        .small-none {
            display: none;
        }
    }

    @media screen and (max-width: 1280px) and (min-width: 800px) {

        .ib-withdraw thead tr th:nth-child(4),
        .ib-withdraw tbody tr td:nth-child(4) {
            display: none;
        }

        .small-none-two {
            display: none;
        }
    }



    @media screen and (max-width: 1440px) and (min-width: 900px) {

        .ib-withdraw thead tr th:nth-child(4),
        .ib-withdraw tbody tr td:nth-child(4) {
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
                        <h2 class="content-header-title float-start mb-0">{{__('admin-breadcrumbs.ib_with')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('ib-management.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{__('admin-breadcrumbs.manage_request')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('admin-menue-left.ib_withdraw_request')}}
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
            <!-- Ajax Sourced Server-side -->
            <section id="ajax-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-bottom d-flex justfy-content-between">
                                <h4 class="card-title">{{__('ad-reports.filter_report')}}</h4>
                                <div class="btn-exports" style="width:200px">
                                    <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                        <option value="download" data-icon="download" selected>{{__('ib-management.export')}}</option>
                                        <option value="csv" data-icon="file">CSV</option>
                                        <option value="excel" data-icon="file">Excel</option>
                                    </select>
                                </div>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form id="filterForm" class="dt_adv_search" method="POST">
                                    <div class="row mb-1 g-1">
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Transaction method">
                                            <!-- filter by transaction method -->
                                            <label for="transaction-method" class="form-label">Transaction method</label>
                                            <select class="select2 form-select" name="method" id="method">
                                                <option value="">{{__('ad-reports.all')}}</option>
                                                @foreach($methods as $method)
                                                <option value="{{ $method->transaction_type }}">{{ ucWords($method->transaction_type) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by kyc verification status -->
                                            <label for="verification-status" class="form-label">KYC Verification status</label>
                                            <select class="select2 form-select" name="verification" id="verification">
                                                <option value="">{{__('ad-reports.all')}}</option>
                                                <option value="1">{{__('ad-reports.verified')}}</option>
                                                <option value="0">{{__('ad-reports.unverified')}}</option>
                                                <option value="2">{{__('ad-reports.pending')}}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Approved status">
                                            <!-- filter by approved status -->
                                            <label for="approved-status" class="form-label">Approved status</label>
                                            <select class="select2 form-select" name="approved_status" id="approved_status">
                                                <option value="">{{__('ad-reports.all')}}</option>
                                                <option value="A">{{__('ad-reports.approved')}}</option>
                                                <option value="P" selected>{{__('ad-reports.pending')}}</option>
                                                <option value="D">{{__('ad-reports.declined')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-1 g-1">
                                        <div class="col-md-4">
                                            <!-- filter by ib info name / email / phone / country -->
                                            <label for="ib-info" class="form-label">IB info</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="{{($varsion =='pro') ? 'IB name / email / phone / country': 'IB name / email / phone'}}" class="form-control dt-input dt-full-name" data-column="1" name="ib_info" id="ib-info" placeholder="{{($varsion =='pro') ? 'IB name / email / phone / country': 'IB name / email / phone'}}" data-column-index="0" />
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by trader info name / email / phone / country -->
                                            <label for="trader-info" class="form-label">Trader info</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="{{($varsion =='pro') ? 'Trader name / email / phone / country': 'Trader name / email / phone'}}" class="form-control dt-input dt-full-name" data-column="1" name="trader_info" id="trader-info" placeholder="{{($varsion =='pro') ? 'Trader name / email / phone / country': 'Trader name / email / phone'}}" data-column-index="0" />
                                        </div>
                                        @if($varsion =='pro')
                                        <div class="col-md-4">
                                            <!-- filter by manager info name / email / phone / country -->
                                            <label for="manager-info" class="form-label">Manager info</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Desk manager / Account manager" class="form-control dt-input dt-full-name" data-column="1" name="manager_info" id="manager-info" placeholder="Desk manager / Account manager" data-column-index="0" />
                                        </div>
                                        @else
                                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Country">
                                                <label class="form-label">Country</label>
                                                <select class="select2 form-select" name="country">
                                                    <option value="">{{ __('client-management.All') }}</option>
                                                    @foreach ($countries as $value)
                                                        <option value="{{ $value->name }}">{{ $value->name }}</option>
                                                    @endforeach
                                                    
                                                </select>
                                            </div> 
                                            
                                            @endif

                                    </div>
                                    <div class="row g-1 mb-1">
                                        <div class="col-md-4">
                                            <!-- filter by trading account -->
                                            <label for="trading-account" class="form-label">Trading account</label>
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Trading account" class="form-control dt-input dt-full-name" data-column="1" name="trading_account" id="trading-account" placeholder="Account number" data-column-index="0" />
                                        </div>
                                        <div class="col-md-4">
                                            <!-- amount range min / max -->
                                            <label for="amount-request" class="form-label">Amount</label>
                                            <div class="form-group">
                                                <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter MIN MAX Amount To Filter">
                                                    <span class="input-group-text">
                                                        {{__('ad-reports.min')}}
                                                    </span>
                                                    <input id="min" type="text" class="form-control" name="min">
                                                    <span class="input-group-text">-</span>
                                                    <input id="max" type="text" class="form-control" name="max">
                                                    <span class="input-group-text">{{__('ad-reports.max')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- date range start date / end date -->
                                            <label for="request-date" class="form-label">Request Date</label>
                                            <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Request Date To Filter" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
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

                                    <div class="row g-1">
                                        <!-- submit buttons -->
                                        <div class="col-md-2 col-6 text-right ms-auto">
                                            <!-- reset button -->
                                            <button id="resetBtn" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('ad-reports.btn-reset')}}</span>
                                            </button>
                                        </div>
                                        <div class="col-md-2 col-6 text-right">
                                            <!-- submit button -->
                                            <button id="filterBtn" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('category.FILTER')}}</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!--Search Form -->
                            <div class="card-body mt-2 table-responsive">
                                <table id="ib_transfer_tbl" class="datatables-ajax ib-withdraw table">
                                    <thead>
                                        <tr>
                                            <th>{{__('admin-deposit-report.name')}}</th>
                                            <th>{{__('client-management.Email')}}</th>
                                            <th>{{__('ad-reports.method')}}</th>
                                            <th>{{__('ad-reports.status')}}</th>
                                            <th>{{__('admin-deposit-report.date')}}</th>
                                            <th>{{__('ad-reports.amount')}}</th>

                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th class="small-none-two"></th>
                                            <th class="small-none"></th>
                                            <th colspan="3" style="text-align: right;" class="details-control" rowspan="1">{{__('ad-reports.total-amount')}}</th>
                                            <th id="total_amount" rowspan="1" colspan="1">$0</th>
                                        </tr>
                                    </tfoot>
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

<!-- Modal sending mail-->
<div class="modal fade text-start modal-success" id="send-mail-pass" tabindex="-1" aria-labelledby="mail-sending-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mail-sending-modal">Sending Mail.....</h5>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <p class="text-warning">Please wait, While we sending mail to - user.</p>
                    <div class="spinner-border text-success" style="width: 3rem; height: 3rem" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<!-- Modal Themes end -->

<!-- add new card modal  -->
<div class="modal fade" id="addNewCard" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-5">
                <h1 class="text-center mb-1" id="addNewCardTitle">Reason For Decline</h1>

                <!-- form -->
                <form id="ib_withdraw_decline_request" class="row gy-1 gx-2 mt-75" action="{{route('admin.ib-transfer.declined')}}" method="POST">
                    <div class="col-12">
                        <label class="form-label" for="modalAddCardNumber">Reason:</label>
                        <div class="input-group input-group-merge">
                            <input id="reason" name="reason" class="form-control add-credit-card-mask" type="text" placeholder="type here....." aria-describedby="modalAddCard2" />
                            <span class="input-group-text cursor-pointer p-25" id="modalAddCard2">
                                <span class="add-card-type"></span>
                            </span>
                            <input type="hidden" name="decline_id" id="decline_id">
                            <input type="hidden" name="table_id" id="table_id">
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-1 mt-1">Yes</button>
                        <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                            No
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ add new card modal  -->
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
    var dt = $('#ib_transfer_tbl').DataTable({
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
            "url": "/admin/manage-report/ib-withdraw-request?op=data_table",
            "data": function(d) {
                return $.extend({}, d, $("#filterForm").serializeObject());
            }
        },

        "columns": [{
                "data": "name"
            },
            {
                "data": "email"
            },
            {
                "data": "method"
            },
            {
                "data": "status"
            },
            {
                "data": "date"
            },
            {
                "data": "amount"
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



    //    datatable descriptions
    $(document).on("click", ".dt-description", function(params) {
        let __this = $(this);
        let user_id = $(this).data('id');
        let table_id = $(this).data('table_id');
        //    console.log(table_id);
        $.ajax({
            type: "GET",
            url: '/admin/manage-report/ib-withdraw-description/' + user_id,
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


    $(document).ready(function() {
        $("#resetBtn").click(function() {
            $("#filterForm")[0].reset();
            $('#verification').prop('selectedIndex', 0).trigger("change");
            $('#method').prop('selectedIndex', 0).trigger("change");
            $('#approved_status').prop('selectedIndex', 2).trigger("change");
            $('#platform').prop('selectedIndex', 0).trigger("change");
            $('#info').prop('selectedIndex', 0).trigger("change");
            dt.draw();

        });
    });


    /*<!---------------Approve Data request operation------------------!>*/
    // function ibWithdrawApprove(e) {
    //     let obj = $(e);
    //     var id = obj.data('id');
    //     var table_id = obj.data('table_id');

    //     let warning_title = "";
    //     let warning_msg = "";
    //     let request_for;

    //     warning_title = 'Are you sure? to Approve this user!';
    //     warning_msg = 'If you want to Approve this User please click OK, otherwise simply click cancel'
    //     request_for = 'block'

    //     Swal.fire({
    //         icon: 'warning',
    //         title: warning_title,
    //         html: warning_msg,

    //         showCancelButton: true,
    //         customClass: {
    //             confirmButton: 'btn btn-warning',
    //             cancelButton: 'btn btn-danger'
    //         },
    //         closeOnCancel: false,
    //         closeOnConfirm: false,
    //     }).then((willDelete) => {
    //         if (willDelete.isConfirmed) {
    //             $('#send-mail-pass').modal('toggle');
    //             $.ajaxSetup({
    //                 headers: {
    //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //                 }
    //             });
    //             $.ajax({
    //                 url: '/admin/manage-report/ib-withdraw-approve-request/' + id + '/' + table_id,
    //                 method: 'POST',
    //                 dataType: 'json',
    //                 data: {
    //                     id: id,
    //                     request_for: request_for
    //                 },
    //                 success: function(data) {
    //                     if (data.success === true) {
    //                         toastr['success'](data.message, 'Mail send', {
    //                             showMethod: 'slideDown',
    //                             hideMethod: 'slideUp',
    //                             closeButton: true,
    //                             tapToDismiss: false,
    //                             progressBar: true,
    //                             timeOut: 2000,

    //                         });
    //                         $('#send-mail-pass').modal('toggle');
    //                         Swal.fire({
    //                             icon: 'success',
    //                             title: data.success_title,
    //                             html: data.message,
    //                             customClass: {
    //                                 confirmButton: 'btn btn-success'
    //                             }

    //                         }).then((willDelete) => {
    //                             const table = $("#ib_transfer_tbl").DataTable();
    //                             table.draw();
    //                         });
    //                     } else {
    //                         Swal.fire({
    //                             icon: 'error',
    //                             title: 'Mail sending failed!',
    //                             html: data.message,
    //                             customClass: {
    //                                 confirmButton: 'btn btn-danger'
    //                             }
    //                         });
    //                     }
    //                 }
    //             })
    //         }
    //     });
    // }
    // /*<!---------------Approve Data request operation End------------------!>*/

    // /*<-------------------Decline Withdraw request operation Start--------------------->*/
    // $(document).on("click", ".decline-ib-withdraw-btn", function() {
    //     $('#decline_id').val($(this).data('id'));
    //     $('#table_id').val($(this).data('table_id'));
    // });

    // $(document).on("submit", "#ib_withdraw_decline_request", function(event) {
    //     let form_data = $(this).serializeArray();
    //     $('#addNewCard').modal('hide');
    //     $('#send-mail-pass').modal('toggle');
    //     event.preventDefault();
    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });
    //     $.ajax({
    //         url: '/admin/manage-report/ib-withdraw-decline-request',
    //         method: 'POST',
    //         dataType: 'json',
    //         data: form_data,
    //         success: function(data) {
    //             if (data.success === true) {

    //                 toastr['success'](data.message, 'Mail send', {

    //                     showMethod: 'slideDown',
    //                     hideMethod: 'slideUp',
    //                     closeButton: true,
    //                     tapToDismiss: false,
    //                     progressBar: true,
    //                     timeOut: 2000,
    //                 });

    //                 Swal.fire({
    //                     icon: 'success',
    //                     title: data.success_title,
    //                     html: data.message,
    //                     customClass: {
    //                         confirmButton: 'btn btn-success'
    //                     }
    //                 }).then((willDelete) => {
    //                     const table = $("#ib_transfer_tbl").DataTable();
    //                     table.draw();
    //                     location.reload();

    //                 });
    //             } else {
    //                 let $errors = 'Please Enter a Reason';
    //                 //     if (data.errors.hasOwnProperty('password')) {
    //                 //     $errors += "  " + data.errors.password[0] + '<br>';
    //                 //   }
    //                 Swal.fire({
    //                     icon: 'error',
    //                     title: 'Decline operation failed!',
    //                     html: $errors,
    //                     customClass: {
    //                         confirmButton: 'btn btn-danger'
    //                     }
    //                 });
    //             }
    //         }
    //     })
    // });

    $(document).on('click', ".btn-transaction-approve", function() {
        let id = $(this).data('id');
        $(this).confirm2({
            request_url: '/admin/manage-report/ib-withdraw-approve-request',
            data: {
                id: id
            },
            click: false,
            title: 'Approve IB withdraw',
            message: 'Are you confirm to approve this withdraw request?',
            button_text: 'Aprove',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'IB withdraw approve');
            } else {
                notify('error', data.message, 'IB withdraw approve');
            }
            dt.draw();
        });
    })
    /*<!---------------Approve Data request operation End------------------!>*/
    $(document).on('click', ".btn-transaction-declined", function() {
        let id = $(this).data('id');
        $(this).confirm2({
            request_url: '/admin/manage-report/ib-withdraw-decline-request',
            data: {
                id: id
            },
            input: 'text',
            click: false,
            title: 'Decline IB Withdraw',
            message: 'Are you confirm to decline this withdraw?',
            button_text: 'Aprove',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'IB withdraw decline');
            } else {
                notify('error', data.message, 'IB withdraw decline');
            }
            dt.draw();
        });
    })

    /*<-------------------Decline request End--------------------->*/

    // .then((willReload)=>{
    //         location.reload();
    //       });
</script>


@stop
<!-- BEGIN: page JS -->