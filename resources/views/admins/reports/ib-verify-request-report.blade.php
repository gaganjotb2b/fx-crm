@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','IB Verification Request')
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
    .geeks {
        /* width: 300px; */
        /* height: 300px; */
        overflow: hidden;
        margin: 0 auto;
    }

    .geeks img {
        width: 100%;
        transition: 0.5s all ease-in-out;
        cursor: pointer;
        /* position: absolute;
            right:100px; */
        height: 100%;
    }

    .geeks:hover img {
        transform: scale(1.5);
    }


    /* for Laptop */
    @media screen and (max-width: 1280px) and (min-width: 800px) {

        .ib-withdraw thead tr th:nth-child(5),
        .ib-withdraw tbody tr td:nth-child(5) {
            display: none;
        }

        .small-none {
            display: none;
        }
    }

    @media screen and (max-width: 1280px) and (min-width: 800px) {

        .ib-withdraw thead tr th:nth-child(5),
        .ib-withdraw tbody tr td:nth-child(5) {
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
                        <h2 class="content-header-title float-start mb-0">IB Verification Request Report</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('ib-management.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{__('admin-menue-left.ib_management')}}</a>
                                </li>
                                <li class="breadcrumb-item active">IB Verification Request Report
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
                                <h4 class="card-title">{{__('ib-management.filter_report')}}</h4>
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
                                    <div class="row g-1 mb-md-1">
                                        <!--Filter By Verification Status-->
                                        <div class="col-md-4">
                                            <label for="verification_status" class="form-label">Verification Status</label>
                                            <select class="select2 form-select" name="verification_status" id="verification_status">
                                                <optgroup label="Search By Status">
                                                    <option value="">{{__('ib-management.all')}}</option>
                                                    <option value="0" selected>{{__('ib-management.pending')}}</option>
                                                    <option value="1">{{__('ib-management.verified')}}</option>
                                                    <option value="2">{{__('ib-management.declined')}}</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <!--Filter By IB Group-->
                                        <div class="col-md-4">
                                            <label for="ib_group" class="form-label">IB Group</label>
                                            <select class="select2 form-select" name="ib_group" id="ib_group">
                                                <optgroup label="Search By IB Group">
                                                    <option value="">{{__('ib-management.all')}}</option>
                                                    @foreach($ib_groups as $ib_group)
                                                    <option value="{{ $ib_group->id }}">{{ $ib_group->group_name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                        <!--Filter By KYC Type-->
                                        <div class="col-md-4">
                                            <label for="kyc_type" class="form-label">KYC Type</label>
                                            <select class="select2 form-select" name="kyc_type" id="kyc_type">
                                                <optgroup label="Search By Type">
                                                    <option value="">{{__('ib-management.all')}}</option>
                                                    @foreach($types as $type)
                                                    <option value="{{ $type->id }}">{{ ucwords($type->id_type) }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                        <!--Filter by manager info-->
                                        @if($varsion =='pro')
                                            <div class="col-md-4">
                                                <label for="manager_info" class="form-label">Manager Info.</label>
                                                <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="manager_info" id="manager_info" placeholder="Manager" data-column-index="0" />
                                            </div>
                                        @else
                                            <div class="col-md-4">
                                                <label for="manager_info" class="form-label">Country</label>
                                                <select class="select2 form-select" name="country">
                                                    <option value="">{{ __('client-management.All') }}</option>
                                                    @foreach ($countries as $value)
                                                        <option value="{{ $value->name }}">{{ $value->name }}</option>
                                                    @endforeach
                                                    
                                                </select>
                                            </div>
                                        @endif
                                        <!--Filter By IB INfo-->
                                        <div class="col-md-4">
                                            <label for="ib_info" class="form-label">IB Info.</label>
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="ib_info" id="ib_info" placeholder="IB Name / Email / Phone" data-column-index="0" />
                                        </div>
                                        <!--Filter By Trader Info-->
                                        <div class="col-md-4">
                                            <label for="trader_info" class="form-label">Trader Info.</label>
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="trader_info" id="trader_info" placeholder="Trader Name / Email / Phone" data-column-index="0" />
                                        </div>
                                        <!--Filter By Date-->
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
                                            <label for="" class="form-label">&nbsp;</label>
                                            <button id="resetBtn" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('ib-management.reset')}}</span>
                                            </button>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <label for="" class="form-label">&nbsp;</label>
                                            <button id="filterBtn" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('ib-management.FILTER')}}</span>
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
                            <!--Search Form -->
                            <div class="card-body mt-2 table-responsive">

                                <table id="ib_verifi_request_tbl" class="datatables-ajax ib-withdraw table">
                                    <thead>
                                        <tr>
                                            <th>{{__('ib-management.name')}}</th>
                                            <th>{{__('ib-management.email')}}</th>
                                            <th>Group</th>
                                            <th>{{__('ib-management.Type')}}</th>
                                            <th>{{__('ib-management.status')}}</th>
                                            <th>{{__('ib-management.date')}}</th>
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

<!-- add new card modal  -->
<div class="modal fade" id="addNewCard" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body px-sm-5 mx-50 pb-5">
                <h1 class="text-center mb-1" id="addNewCardTitle">Reason For Decline</h1>

                <!-- form -->
                <form id="ib_decline_request" class="row gy-1 gx-2 mt-75" action="{{route('admin.decline.verification')}}" method="POST">
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
<!-- add new address modal -->
<div class="modal fade" id="addNewAddressModal" tabindex="-1" aria-labelledby="addNewAddressTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body pb-5 px-sm-4 mx-50">
                <h1 class="address-title text-center mb-1" id="addNewAddressTitle">User Document</h1>
                <form id="addNewAddressForm" class="row gy-1 gx-2 geeks" onsubmit="return false">

                    <body style="margin: 0px; height: 100%">
                        <div class="card-body pt-2">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" id="home-tab-fill" data-bs-toggle="tab" href="#home-fill" role="tab" aria-controls="home-fill" aria-selected="false">Front Part</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" id="profile-tab-fill" data-bs-toggle="tab" href="#profile-fill" role="tab" aria-controls="profile-fill" aria-selected="true">Back Part</a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content pt-1 pb-4">
                                <div class="tab-pane" id="home-fill" role="tabpanel" aria-labelledby="home-tab-fill">
                                    <div class="geeks" style="height: 80%; width: 80%;">
                                        <img id="front_part" class="img-thumbnail" src="{{ url('admin-assets/passport.png') }}">
                                        <embed src="" id="front_part_pdf" type="application/pdf" width="100%" height="600px" />
                                    </div>
                                </div>
                                <div class="tab-pane active" id="profile-fill" role="tabpanel" aria-labelledby="profile-tab-fill">
                                    <div class="geeks" style="height: 80%; width: 80%;">
                                        <img id="backpart_part" class="img-thumbnail" src="{{ url('admin-assets/passport.png') }}">
                                        <embed src="" id="backpart_part_pdf" type="application/pdf" width="100%" height="600px" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </body>
                    <div class="col-12 text-center">
                        <button type="reset" class="btn btn-outline-secondary mt-2" data-bs-dismiss="modal" aria-label="Close">
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- / add new address modal -->

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

<!-- END: Content-->


@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
{{-- <script src="{{ asset('admin-assets/app-assets/vendors/js/vendors.min.js') }}"></script> --}}
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js')}}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>

<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
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
    $(document).ready(function() {

        var dt = $('#ib_verifi_request_tbl').DataTable({
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


                "url": "/admin/ib-management/ib-verification-request?op=data_table",
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
                    "data": "ib_group"
                },
                {
                    "data": "type"
                },
                {
                    "data": "status"
                },
                {
                    "data": "date"
                },

            ],

            "drawCallback": function(settings) {
                $("#filterBtn").html("FILTER");
                var rows = this.fnGetData();
                if (rows.length !== 0) {
                    feather.replace();
                }
            },
            "order": [
                [4, 'desc']
            ]
        });
        $('#filterBtn').click(function(e) {
            dt.draw();
        });
        $("#resetBtn").click(function() {
            $("#filterForm")[0].reset();
            $('#verification_status').prop('selectedIndex', 1).trigger("change");
            $('#ib_group').prop('selectedIndex', 0).trigger("change");
            $('#kyc_type').prop('selectedIndex', 0).trigger("change");
            dt.draw();
        });

    });


    /*<---------------Datatable Descriptions Start------------>*/
    $(document).on("click", ".dt-description", function(params) {
        let __this = $(this);
        let user_id = $(this).data('id');
        let table_id = $(this).data('table_id');
        $.ajax({
            type: "GET",
            url: '/admin/ib-management/ib-verification-request-description/' + user_id + '/' + table_id,
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

                        //Inner datatable
                        if ($(__this).closest("tr").next(".description").find('.ib-verification-details').length) {
                            $(__this).closest("tr").next(".description").find('.ib-verification-details').DataTable().clear().destroy();
                            var dt_inner = $(__this).closest('tr').next('.description').find('.ib-verification-details').DataTable({
                                "processing": true,
                                "serverSide": true,
                                "searching": false,
                                "ajax": {
                                    "url": "/admin/ib-management/ib-verification-request-inner-description/" + user_id + '/' + table_id
                                },
                                "columns": [{
                                        "data": "nid_number"
                                    },
                                    {
                                        "data": "issue_date"
                                    },
                                    {
                                        "data": "exp_date"
                                    },
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
        })
    });


    /*<--------------Datatable export function Start----------------->*/
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

    /*<--------------Datatable export function End----------------->*/


    /*<---------For reset button script-------------->*/




    /*<!---------------Approve Data request operation------------------!>*/
    function ib_approve_request(e) {
        let obj = $(e);
        var id = obj.data('id');
        var table_id = obj.data('table_id');
        // console.log(table_id);

        let warning_title = "";
        let warning_msg = "";
        let request_for;

        warning_title = 'Are you sure? to Approve this user!';
        warning_msg = 'If you want to Approve this User please click OK, otherwise simply click cancel'
        request_for = 'block'

        Swal.fire({
            icon: 'warning',
            title: warning_title,
            html: warning_msg,

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
                    url: '/admin/ib-management/ib-verification-approve-request/' + id + '/' + table_id,
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        request_for: request_for
                    },
                    success: function(data) {
                        if (data.success === true) {
                            toastr['success'](data.message, 'Mail send', {
                                showMethod: 'slideDown',
                                hideMethod: 'slideUp',
                                closeButton: true,
                                tapToDismiss: false,
                                progressBar: true,
                                timeOut: 2000,

                            });
                            $('#send-mail-pass').modal('toggle');
                            Swal.fire({
                                icon: 'success',
                                title: data.success_title,
                                html: data.message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }

                            }).then((willDelete) => {
                                const table = $("#ib_verifi_request_tbl").DataTable();
                                table.draw();
                            });
                        } else {

                            Swal.fire({
                                icon: 'error',
                                title: 'Mail sending failed!',
                                html: data.message,
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    }
                })

            }

        });
    }
    /*<!---------------Approve Data request operation End------------------!>*/


    /*<-------------------Decline Deposit request operation Start--------------------->*/
    $(document).on("click", ".ib-decline-request-btn", function() {
        $('#decline_id').val($(this).data('id'));
        $('#table_id').val($(this).data('table_id'));
    });

    $(document).on("submit", "#ib_decline_request", function(event) {
        $('#addNewCard').modal('hide');
        $('#send-mail-pass').modal('toggle');
        let form_data = $(this).serializeArray();
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/ib-management/ib-verification-decline-request',
            method: 'POST',
            dataType: 'json',
            data: form_data,
            success: function(data) {
                if (data.success === true) {

                    toastr['success'](data.message, 'Mail send', {

                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 500,
                    });

                    Swal.fire({
                        icon: 'success',
                        title: data.success_title,
                        html: data.message,
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    }).then((willDelete) => {

                        const table = $("#ib_verifi_request_tbl").DataTable();
                        table.draw();
                        location.reload();

                    });
                } else {
                    let $errors = 'Please Enter a Reason';
                    Swal.fire({
                        icon: 'error',
                        title: 'Decline operation failed!',
                        html: $errors,
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                }
            }
        })
    });

    /*<-------------------Decline request End--------------------->*/
    // User Description view
    function identify_request(e) {
        let obj = $(e);
        var id = obj.data('id');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/ib-management/ib-verification-proof/' + id,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.group_name == 'id proof') {
                    $('#profile-tab-fill').show();
                    if (data.front_part_file_type === 'pdf') {
                        $('#front_part_pdf').attr("src", data.front_part).show();
                        $('#front_part').hide();
                    } else {
                        $('#front_part').attr("src", data.front_part).show();
                        $('#front_part_pdf').hide();
                    }
                    if (data.back_part_file_type === 'pdf') {
                        $('#backpart_part_pdf').attr("src", data.back_part).show();
                        $('#backpart_part').hide();
                    } else {
                        $('#backpart_part').attr("src", data.back_part).show();
                        $('#backpart_part_pdf').hide();
                    }

                } else if (data.group_name == 'address proof') {
                    $('#profile-tab-fill').hide();
                    if (data.front_part_file_type === 'pdf') {
                        $('#front_part_pdf').attr("src", data.front_part).show();
                        $('#front_part').hide();
                    } else {
                        $('#front_part').attr("src", data.front_part).show();
                        $('#front_part_pdf').hide();
                    }
                }
            }
        });
    }
</script>
@stop
<!-- END: page JS -->
