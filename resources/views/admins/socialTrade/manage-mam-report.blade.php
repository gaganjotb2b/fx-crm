@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Manage MAMM')
@section('vendor-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/calendars/fullcalendar.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <!-- datatable -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/pages/app-calendar.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/morris.css') }}">

    <style>
        td,
        th {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .bank-identify-modal {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 60% !important;
            pointer-events: auto;
            background-color: #fff;
            background-clip: padding-box;
            border: 0 solid rgba(34, 41, 47, 0.2);
            border-radius: 0.357rem;
            outline: 0;
        }

        @media screen and (max-width: 1440px) and (min-width: 900px) {

            .deposit-request thead tr th:nth-child(3),
            .deposit-request tbody tr td:nth-child(3) {
                display: none;
            }

            .small-none {
                display: none;
            }
        }

        .dataTables_scrollBody {
            height: auto !important;
        }

        td.details-control {
            background: url("{{ asset('admin-assets/assets/icon/plus.png') }}") no-repeat center center;
            cursor: pointer;
        }

        tr.details td.details-control {
            background: url("{{ asset('admin-assets/assets/icon/minus.png') }}") no-repeat center center;
        }

        table.table.table-bordered {
            margin-top: 40px !important;
        }

        .multiselect-dropdown-list-wrapper {
            background: transparent !important;
        }

        .multiselect-dropdown-list {
            background: #fff
        }

        .multiselect-dropdown-list div:hover {
            background-color: var(--custom-primary) !important;
        }

        .multiselect-dropdown {
            border: 1px solid #ddd !important;
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
            <div id="orver_loading" class="lds-ripple loading" style="display: none;">
                <div></div>
                <div></div>
            </div>
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Manage MAMM</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('admin.dashboard') }}">{{ __('ib-management.Home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Social Trade</a>
                                    </li>
                                    <li class="breadcrumb-item active">Manage MAMM
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1 breadcrumb-right">
                        <div class="dropdown">
                            <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    data-feather="grid"></i></button>
                            <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#"><i
                                        class="me-1" data-feather="info"></i>
                                    <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i
                                        class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a>
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
                                {{-- <div class="card-header border-bottom d-flex justfy-content-between">
                                <h4 class="card-title">{{__('ad-reports.filter_report')}}</h4>
                                <div class="btn-exports" style="width:200px">
                                    <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                        <option value="download" data-icon="download" selected>{{__('ib-management.export')}}</option>
                                        <option value="csv" data-icon="file">CSV</option>
                                        <option value="excel" data-icon="file">Excel</option>
                                    </select>
                                </div>
                            </div> --}}
                                <!--Search Form -->
                                <div class="card-body mt-2">
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-md-6">
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Master Account" class="form-control dt-input dt-full-name"
                                                name="master_ac" id="master_ac" placeholder="Search By Master Account"  required/>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button id="btn_search" type="button"
                                                class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{ __('category.FILTER') }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-0" />
                            </div>
                        </div>
                    </div>


                    <div class="row master-chart" style="display: none">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="mt-lg">Master Info</h3>
                                    <p class="mb-lg">Master Yearly Trade Chart</p>

                                    <div class="chart chart-md" id="morrisBar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!--trading account info show part-->
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                            <div class="card">
                                <div class="card-body border-start-3 border-start-primary p-1">
                                    <div class="d-flex">
                                        <div class="section-icon">
                                            <i data-feather='dollar-sign' class="icon-trd text-primary"></i>
                                        </div>
                                        <div class="section-data">
                                            <div class="tv-title">
                                                Total Equity
                                            </div>
                                            <div class="tv-total">
                                                <span class="total-trade" id="equity">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                            <div class="card">
                                <div class="card-body border-start-3 border-start-primary p-1">
                                    <div class="d-flex">
                                        <div class="section-icon">
                                            <i data-feather='dollar-sign' class="icon-trd text-primary"></i>
                                        </div>
                                        <div class="section-data ms-1">
                                            <div class="tv-title">
                                                Balance
                                            </div>
                                            <div class="tv-total amount counter ct_total_volume" id="balance">
                                                0
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                            <div class="card">
                                <div class="card-body border-start-3 border-start-primary p-1">
                                    <div class="d-flex">
                                        <div class="section-icon">
                                            <i data-feather='dollar-sign' class="icon-trd text-primary"></i>
                                        </div>
                                        <div class="section-data ms-1">
                                            <div class="tv-title">
                                                Total Profit
                                            </div>
                                            <div class="tv-total amount counter ct_total_volume" id="total_profit">
                                                0
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                            <div class="card">
                                <div class="card-body border-start-3 border-start-primary p-1">
                                    <div class="d-flex">
                                        <div class="section-icon">
                                            <i data-feather='bar-chart-2' class="icon-trd text-primary"></i>
                                        </div>
                                        <div class="section-data">
                                            <div class="tv-title">
                                                Total Copied
                                            </div>
                                            <div class="tv-total">
                                                <span class="total-trade" id="total_copied">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                            <div class="card">
                                <div class="card-body border-start-3 border-start-primary p-1">
                                    <div class="d-flex">
                                        <div class="section-icon">
                                            <i data-feather='layers' class="icon-trd text-primary"></i>
                                        </div>
                                        <div class="section-data ms-1">
                                            <div class="tv-title">
                                                Total Trades/Total Volume
                                            </div>
                                            <div class="tv-total amount counter ct_total_volume" id="total_trade">
                                                0
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                            <div class="card">
                                <div class="card-body border-start-3 border-start-primary p-1">
                                    <div class="d-flex">
                                        <div class="section-icon">
                                            <i data-feather='users' class="icon-trd text-primary"></i>
                                        </div>
                                        <div class="section-data ms-1">
                                            <div class="tv-title">
                                                Total Slaves
                                            </div>
                                            <div class="tv-total amount counter" id="total_slave">
                                                0
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--trading account info show part end-->

                    <div class="row">
                        <div class="card">
                            <div class="card-body mt-2">
                                <button class="btn btn-primary   waves-effect waves-float waves-light add_new_slave_btn"
                                    style="display:none" type="button" data-bs-toggle="modal"
                                    data-bs-target="#addNewAddressModal"> <i class="fa fa-plus"></i> Add Slave
                                    Tradding Account</button>
                                <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                    <table id="example" class="datatables-ajax deposit-request table table-responsive">
                                        <thead class="thead-light cell-border compact stripe">
                                            <tr>
                                                <th></th>
                                                <th>Account Number</th>
                                                <th>Allocation (%)</th>
                                                <th>Platform</th>
                                                <th>Group</th>
                                                <th>Leverage</th>
                                                <th>Status</th>
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
    </div>

    <div class="modal fade" id="addNewAddressModal" tabindex="-1" aria-labelledby="addNewAddressTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-4 mx-50">
                    <h1 class="address-title text-center mb-1" id="addNewAddressTitle">Add Slave Account</h1>
                    {{-- <p class="address-subtitle text-center mb-2 pb-75">Add address for billing address</p> --}}

                    <form id="contact" class="row gy-1 gx-2" action="{{ route('admin.addSlaveAccount') }}"
                        method="POST" onsubmit="return false">
                        @csrf
                        <input type="hidden" name="master_ac_hide" value="" id="master_ac_hide">
                        <input type="hidden" name="platform" value="mt5" id="master_server">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalAddressFirstName">Account Number</label>
                            <input type="text" id="slave_account" name="slave_account" class="form-control"
                                placeholder="Trading Account Number" data-msg="Please enter slave account" />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalAddressLastName">Password</label>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Enter Password" data-msg="Please enter your password" />
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="modalAddressCountry">Symbol</label>
                            <select id="field2" name="symbol[]" class=" form-select" multiple
                                multiselect-search="true" multiselect-select-all="true" multiselect-max-items="3"
                                onchange="console.log(this.selectedOptions)">
                                <?= copy_symbols() ?>

                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="modalAddressAddress1">Allocation</label>
                            <input type="text" id="allocation" name="allocation" class="form-control"
                                placeholder="Allocation" />
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="modalAddressAddress2">Max Number of Trade</label>
                            <input type="text" id="max_trade_number" name="max_trade_number" class="form-control"
                                placeholder="Max Number of Trade" />
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="modalAddressTown">Max Trade Volume</label>
                            <input type="text" id="max_trade_vol" name="max_trade_vol" class="form-control"
                                placeholder="Enter Trade Volume" />
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="modalAddressTown">Min Trade Volume</label>
                            <input type="text" id="min_trade_vol" name="min_trade_vol" class="form-control"
                                placeholder="Enter Min Trade Volume" />
                        </div>
                        <div class="col-12 text-center">
                            <button class="btn btn-primary me-1 mt-2" id="contact-submit" type="button"
                                onclick="_run(this)" data-form="contact"
                                data-loading="<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>"
                                data-callback="addSlaveCallBack" data-submit="...Sending" data-file="true"
                                data-btnid="contact-submit" data-animation="modalAddSlave">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary mt-2" data-bs-dismiss="modal"
                                aria-label="Close">
                                Discard
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--add new slave modal Modal -->

    <!--add symbol modal-->
    <div id="modalSymbolAdd" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Symbol</h5>

                </div>
                <div class="modal-body">
                    <form id="addSymbolForm" action="/admin/meta5_mam_add/add_symbol" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="col-sm-12">
                                <select name="add_new_symbol" id="add_symbol" class="form-control">
                                    <option value="">Select A Symbol</option>
                                    <?= copy_symbols() ?>
                                </select>
                            </div>
                        </div>
                        <input name="slave" value="" type="hidden" class="form-control" id="symbol-slave">

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button class="btn btn-primary" id="add_new_symbol" type="button" onclick="_run(this)"
                        data-form="addSymbolForm" data-loading="<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>"
                        data-callback="addSymbolCallBack" data-file="true" data-btnid="add_new_symbol"
                        data-animation="modalAddSlave">Add</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Button trigger modal -->
    <button type="button" id="deleteSymbolButton" class="btn btn-primary d-none" data-bs-toggle="modal"
        data-bs-target="#delete_symbol">
        Check Button
    </button>
    <!-- Modal -->
    <div class="modal fade" id="delete_symbol" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Delete Symbol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="del_sym_id">
                    <input type="hidden" id="del_sym_slave">
                    <input type="hidden" id="del_sym_symbol">
                    Are you sure that you want to delete this <span class="show_symbol" style="color: red"></span> Symbol?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="del_symbol">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Button trigger modal -->
    <button type="button" id="deleteSlaveButton" class="btn btn-primary d-none" data-bs-toggle="modal"
        data-bs-target="#staticBackdrop2">
        Check Button
    </button>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Delete Slave Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="del_id">
                    <input type="hidden" id="del_ma">
                    <input type="hidden" id="del_sa">
                    Are you sure that you want to delete this <span class="show_id"></span> account?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="del_confirm">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->

@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')

    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
    <!-- datatable -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>


    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>

    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>


    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/filter.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/common.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/js/morris/morris.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/js/morris/raphael.js') }}"></script>
    <script src="{{ asset('common-js/custom-multiselect.js') }}"></script>

    <!-- datatable  -->
    <script>
        $("#btn_search").click(function() {
            var master_ac = $('#master_ac').val(); 
            if (master_ac !== '') {
                $('.master-chart').show();
                $('.add_new_slave_btn').show();
                var master_login = $('#master_ac').val();
                dt.ajax.url("/admin/pamm/social-trades/manage-mam/slave-list?login=" + master_login).load();
                $("#master_ac_hide").val(master_login);

                if (master_login != "") {
                    $("#balance").html('Loading..');
                    $("#equity").html('Loading..');
                    $("#total-slave").html(0);

                    $.ajax({
                        url: '/admin/trading-account-balance-equity',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            login: master_login
                        },
                        success: function(data) {
                            $("#morrisBar").empty();
                            if (data.success) {

                                var morrisBarData = data.chart;

                                barChart(morrisBarData);



                                $("#balance").html((!data.balance != 'faild') ? data.balance :
                                    'Not Found');
                                $("#equity").html((!data.equity != 'faild') ? data.equity :
                                'Not Found');

                                $("#total_profit").html(data.total_profit);
                                $("#total_trade").html(data.total_trade);
                                $("#total_copied").html(data.total_copied);
                                $("#total_slave").html(data.total_slave);
                            }
                        },
                        error: function(e) {
                            console.log(e);
                        }
                    });
                }
            }else{
                notify('error', "Please enter master account for Filter!", "Error");
            }
        });

        function format(d) {
            return d.extra;
        }


        var dt = $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ordering": true,
            "ajax": "/admin/pamm/social-trades/manage-mam/slave-list?login=0",
            "columns": [{
                    "class": "details-control",
                    "orderable": false,
                    "data": null,
                    "defaultContent": ""
                },
                {
                    "data": "slave_account"
                },
                {
                    "data": "allocation"
                },

                {
                    "data": "platform"
                },
                {
                    "data": "group"
                },
                {
                    "data": "leverage"
                },
                {
                    "data": "status"
                },
                {
                    "data": "action"
                }
            ],
        });
        var detailRows = [];

        $('#example tbody').on('click', 'tr td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = dt.row(tr);
            var idx = $.inArray(tr.attr('id'), detailRows);

            if (row.child.isShown()) {
                tr.removeClass('details');
                row.child.hide();

                // Remove from the 'open' array
                detailRows.splice(idx, 1);
            } else {
                tr.addClass('details');
                row.child(format(row.data())).show();

                // Add to the 'open' array
                if (idx === -1) {
                    detailRows.push(tr.attr('id'));
                }
            }
        });

        // On each draw, loop over the `detailRows` array and show any child rows
        dt.on('draw', function() {
            $.each(detailRows, function(i, id) {
                $('#' + id + ' td.details-control').trigger('click');
            });
        });


        //add symbol modal function
        function addSymbolReady(sl) {
            console.log(sl);
            $('#modalSymbolAdd').modal('show');
            $('#addSymbolForm #symbol-slave').val(sl);
        }

        function addSymbolCallBack(data) {
            console.log(data);
            $('#add_new_symbol').prop('disabled', false);
            if (data.status) {
                notify('success', data.message, "Add New Symbol");
                $("#modalSymbolAdd").modal('hide');
                dt.ajax.reload(null, true);
            } else {
                notify('error', data.message, "Add New Symbol");
                if (data.errors)
                    $.validator("addSymbolForm", data.errors);
            }
        }



        //open delete symbol modal 
        function deleteSymbol(e) {
            $('#deleteSymbolButton').trigger('click');
            var parentObj = $(e).closest('tr');
            id = parentObj.data('id');
            slave = parentObj.data('slave');
            symbol = parentObj.data('symbolname'),

                $("#del_sym_id").val(id);
            $("#del_sym_slave").val(slave);
            $("#del_sym_symbol").val(symbol);
            $('.show_symbol').html(symbol);
        }

        $('#del_symbol').click(function() {

            var id = $("#del_sym_id").val();
            var slave = $("#del_sym_slave").val();
            var symbol = $("#del_sym_symbol").val();
            $("#del_symbol").html('<i class="fa fa-spinner fa-pulse"></i>');


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "/admin/meta5_mam_add/symbol_delete",
                type: "POST",
                dataType: "json",
                data: {
                    'id': id,
                    'slave': slave,
                    'symbol': symbol
                },
                success: function(response) {
                    $("#del_symbol").html('Confirm');
                    $("#delete_symbol").modal('hide');
                    if (response.status == true) {
                        notify('success', 'Symbol successfully deleted!', "Success");
                        dt.ajax.reload(null, true);
                    } else {
                        notify('error', 'Fail To Delete Symbol', "Error");
                    }
                }
            })
        });
        //delete function end



        //symbol edit function here
        function editSymbol(e) {
            var parentObj = $(e).closest('tr');
            var fields = parentObj.find('td');


            parentObj.find('.fa-trash').hide();
            parentObj.find('.fa-edit').hide();
            parentObj.find('.fa-save').show();
            parentObj.find('.fa-times').show();

            $.each(fields, function(k, v) {
                var obj = $(v);


                if (obj.data('name') == "symbol") {
                    obj.html(obj.html() + '<input class="form-control filter" type="hidden" value="' + obj.html() +
                        '" name="' + obj.data('name') + '">');
                } else {
                    if (obj.data('name') == 'status') {
                        obj.html(
                            '<select name="status" class="form-control"><option value="active">Active</option><option value="inactive">Inactive</option></select>'
                        );
                    }
                }

            });
            // $("body").filterInput();
        }


        function editSymbolUpdate(e) {
            var _self = $(e);
            var parentObj = $(e).closest('tr');
            var fields = parentObj.find('td');

            _self.removeClass('.fa-save');
            _self.addClass('fa-spinner fa-pulse');
            var postData = {
                master: parentObj.data('master'),
                slave: parentObj.data('slave'),
                role_id: parentObj.data('role'),


                id: parentObj.data('id'),
                status: parentObj.find('td select[name="status"]').val(),
                symbol: parentObj.find('td input[name="symbol"]').val(),
                allocation: parentObj.find('td input[name="allocation"]').val(),
                sl: parentObj.find('td input[name="sl"]').val(),
                max_trade: parentObj.find('td input[name="max_trade"]').val(),
                max_volume: parentObj.find('td input[name="max_volume"]').val(),
                min_volume: parentObj.find('td input[name="min_volume"]').val(),
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $.ajax({
                url: '/admin/meta5_mam_delete/submit_symbol',
                type: 'POST',
                dataType: 'json',
                data: postData,
                success: function(data) {
                    if (data.status) {
                        // notify('success', 'Symbol successfully Updated!',"Success");
                        notify('success', data.message);
                        dt.ajax.reload(null, true);
                    } else {
                        notify('success', 'Failed To Update!', "Success");
                        notify('error', data.message);
                    }
                    _self.removeClass('fa-spinner fa-pulse');
                    _self.addClass('.fa-save');
                },
                error: function(e) {
                    console.log(e);
                }
            });
        }

        function editSymbolCancel(e) {
            dt.ajax.reload(null, true);
        }



        /*
          Morris: Bar
          */
        function barChart(morrisBarData) {
            Morris.Bar({
                resize: true,
                element: 'morrisBar',
                data: morrisBarData,
                xkey: 'y',
                ykeys: ['a', 'b'],
                labels: ['Master Trades Volume', 'Copied Trades Volume'],
                hideHover: true,
                barColors: ['#0088cc', '#2baab1']
            });
        }


        var morrisBarData = [{
            y: 'Jan',
            a: 0,
            b: 0
        }, {
            y: 'Feb',
            a: 0,
            b: 0
        }, {
            y: 'Mar',
            a: 0,
            b: 0
        }, {
            y: 'Apr',
            a: 0,
            b: 0
        }, {
            y: 'May',
            a: 0,
            b: 0
        }, {
            y: 'Jun',
            a: 0,
            b: 0
        }, {
            y: 'Jul',
            a: 0,
            b: 0
        }, {
            y: 'Aug',
            a: 0,
            b: 0
        }, {
            y: 'Sep',
            a: 0,
            b: 0
        }, {
            y: 'Aug',
            a: 0,
            b: 0
        }, {
            y: 'Nov',
            a: 0,
            b: 0
        }, {
            y: 'Dec',
            a: 0,
            b: 0
        }];

        barChart(morrisBarData);

        //delete slave
        function openDeleteModal(id, ma, sa) {
            $('#deleteSlaveButton').trigger('click');
            $("#del_id").val(id);
            $("#del_ma").val(ma);
            $("#del_sa").val(sa);
            $(".show_id").html(sa);
        }


        $("#del_confirm").click(function() {
            var id = $("#del_id").val();
            var ma = $("#del_ma").val();
            var sa = $("#del_sa").val();
            $("#del_confirm").html('<i class="fa fa-spinner fa-pulse"></i>');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/admin/meta5_mam_delete",
                type: "POST",
                dataType: "json",
                data: {
                    'id': id,
                    'ma': ma,
                    'sa': sa
                },
                success: function(response) {
                    $("#del_confirm").html('Confirm');
                    $("#staticBackdrop2").modal('hide');
                    if (response.status == true) {
                        notify('success', 'Account successfully deleted!', "Success");
                        dt.ajax.reload(null, true);
                    } else {
                        notify('error', 'Fail To Delete Account', "Error");
                    }
                }
            })

        });


        //add slave account
        function addSlaveCallBack(data) {
            if (data.status == true) {
                $("#staticBackdrop").modal('hide');

                notify('success', "Slave Account Added Successfully!", "Success");
            } else {
                notify('error', data.message, "Error");
                $.validator("contact", data.errors);
            }
        }
    </script>
@stop
<!-- BEGIN: page JS -->
