@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Trader analysis')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
<!-- number input -->
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/charts/chart-apex.css')}}">
<style>
    .apexcharts-legend.apexcharts-align-center.position-right {
        margin-top: 3rem;
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
                        <h2 class="content-header-title float-start mb-0">Trader Analysis</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin-breadcrumbs.home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Manage Client</a>
                                </li>
                                <li class="breadcrumb-item active">Trader Analysis</li>
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
            <!-- Role cards -->
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.trader-analysis-data')}}" method="post" id="ib-analysis-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4 col-6 mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Name Or Email">
                                <select name="search_email" id="trader-email" class="form-select select2-trader">
                                    <option value="">Choose Trader</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-6" data-bs-toggle="tooltip" data-bs-placement="top" title="Start To End Date">
                                <input type="text" class="form-control dt-date flatpickr-range dt-input" data-column="5" placeholder="StartDate to EndDate" data-column-index="4" name="dt_date" />
                                <input type="hidden" class="form-control dt-date start_date dt-input" data-column="5" data-column-index="4" name="start_date" />
                                <input type="hidden" class="form-control dt-date end_date dt-input" name="end_date" data-column="5" data-column-index="4" />
                            </div>
                            <div class="col-lg-2 col-6" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter">
                                <button type="button" class="btn btn-secondary mb-1 w-100" id="reset">Reset</button>
                            </div>
                            <div class="col-lg-2 col-6" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter">
                                <button type="button" class="btn btn-primary mb-1 w-100" id="filter" onclick="_run(this)" data-el="fg" data-form="ib-analysis-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="trader_analysis_call_back" data-btnid="filter">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- details-->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 d-flex">
                                    <img src="{{asset('admin-assets/app-assets/images/avatars/avater-men.png')}}" alt="user avatar" style="width:48px; height:48px" class="bg-bitbucket rounded-circle">
                                    <div class="ms-2">
                                        <div id="name-group">
                                            <span class="name-label"><b>Name : </b></span>
                                            <span class="name">.........</span>
                                        </div>
                                        <div id="email-group">
                                            <span class="email-label"><b>Email : </b></span>
                                            <span class="email">.........</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div id="Country-group">
                                        <span class="Country-label"><b>Country : </b></span>
                                        <span class="country">.........</span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <!-- total trade volume -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-primary">
                                        <div class="card-body">
                                            <div id="trade-volume"><b>N/A</b></div>
                                            <div id="trade-volume-label">Trade Volume (Total)</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- total ib commission -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-primary">
                                        <div class="card-body">
                                            <div id="total-withdraw"><b>N/A</b></div>
                                            <div id="total-withdraw-label">Total Withdraw (USD)</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- total trader -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-primary">
                                        <div class="card-body">
                                            <div id="total-deposit"><b>N/A</b></div>
                                            <div id="total-deposit-label">Total Deposit (USD)</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- total volume from ib -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-primary">
                                        <div class="card-body">
                                            <div id="wallet-balance"><b>N/A</b></div>
                                            <div id="wallet-balance-label">Wallet Balance (USD)</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ib commission lot -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-primary">
                                        <div class="card-body">
                                            <div id="total-trades"><b>N/A</b></div>
                                            <div id="total-trades-label">Trades (Total)</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- trading accounts -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-primary">
                                        <div class="card-body">
                                            <div id="trading-accounts"><b>N/A</b></div>
                                            <div id="trading-accounts-label">Trading Accounts (Total)</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- wallet to account transfer -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-primary">
                                        <div class="card-body">
                                            <div id="wta-transfer"><b>N/A</b></div>
                                            <div id="wta-transfer-label">Wallet to Account Transfer (USD)</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- total account to wallet transfer -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-primary">
                                        <div class="card-body">
                                            <div id="atw-transfer"><b>N/A</b></div>
                                            <div id="atw-transfer-label">Account to Wallet Transfer (USD)</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- total bonus -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-primary">
                                        <div class="card-body">
                                            <div id="total-bonus"><b>N/A</b></div>
                                            <div id="total-bonus-label">Total Bonus (USD)</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- trader to ib transfer -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-primary">
                                        <div class="card-body">
                                            <div id="trader-to-trader-transfer"><b>N/A</b></div>
                                            <div id="trader-to-trader-transfer-label">Trader to Trader Transfer (USD)</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- trader to ib transfer -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-primary">
                                        <div class="card-body">
                                            <div id="trader-to-ib-transfer"><b>N/A</b></div>
                                            <div id="trader-to-ib-transfer-label">Trader to IB Transfer (USD)</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ib to trader transfer -->
                                <div class="col-lg-4">
                                    <div class="card bg-light-primary">
                                        <div class="card-body">
                                            <div id="ib-to-trader-transfer"><b>N/A</b></div>
                                            <div id="ib-to-trader-transfer-label">IB to Trader Transfer (USD)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--ending column 8-->
                <!-- chart -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- manager edit Modal -->
            <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-5 pb-5">
                            <div class="text-center mb-4">
                                <h1 class="role-title">Edit Manager Info</h1>
                                <p id="display-manager-group">Desk Manager</p>
                            </div>
                            <!-- manager edit form -->
                            <form id="edit-manager-info-form" class="row" method="post" action="{{route('admin.edit-manager')}}">
                                <div id="manager-infos" class="row">
                                    <!-- Load from add manager controller controller -->
                                </div>
                                <div class="col-12 mt-2 text-end">
                                    <hr>
                                    <!-- <button type="submit" class="btn btn-primary me-1 btn-save-edit-manager">Save Change</button> -->
                                    <button type="button" class="btn btn-primary  btn-save-edit-manager" id="save-edit-manager" onclick="_run(this)" data-el="fg" data-form="edit-manager-info-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="edit_manager_call_back" data-btnid="save-edit-manager">Save Change</button>
                                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                        Discard
                                    </button>
                                </div>
                            </form>
                            <!--/ manager edit  form -->
                        </div>
                    </div>
                </div>
            </div>
            <!--/ manager edit Modal -->
        </div>
    </div>
</div>
<!-- END: Content-->

<!-- Enable backdrop (default) -->
<div class="enable-backdrop">
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasBackdrop" aria-labelledby="offcanvasBackdropLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasBackdropLabel" class="offcanvas-title">Add New Admin Group</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body my-auto mx-0 flex-grow-0">
            <p>
                <b>Group Name as Rols Name.</b>
                A role provided access to predefined menus and features so that depending
                on assigned role an administrator can have access to what he need
            </p>
            <form action="{{route('admin.add-admin-group')}}" method="post" id="admin-group-form">
                @csrf
                <label class="form-label" for="group-name">Group Name</label>
                <input id="group-name" class="form-control" type="text" placeholder="Normal Input" name="group_name" />
                <button type="button" id="save-group" class="btn btn-primary mb-1 d-grid w-100 mt-1">Save Group</button>
                <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="offcanvas">
                    Cancel
                </button>
            </form>

        </div>
    </div>
</div>
<!--/ Enable backdrop (default) -->
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
<!-- here add vendor js -->
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
<!-- datatable -->

<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- number input -->
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/charts/apexcharts.min.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')

<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-number-input.js')}}"></script>
<!-- <script src="{{asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script> -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/manager-analysis.js')}}"> </script>
<script src="{{asset('common-js/select2-get-trader.js')}}"></script>
<script>
    // simple pie chart/ apext chart
    // --------------------------------
    var options = {
        series: [1, 1, 1, 1],
        chart: {
            width: 380,
            type: 'pie',
        },
        labels: ['Wallet to Account', 'Account to Wallet', 'Withdraw', 'Deposit'],
        responsive: [{
            breakpoint: 1550,
            options: {
                chart: {
                    width: "100%"
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                }
            }
        },
        {
            breakpoint: 1200,
            options: {
                chart: {
                    width: "100%"
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
    // filter manager
    // --------------------------------------------------------------
    function trader_analysis_call_back(data) {
        if (data.status == true) {
            $('.name').text(data.user_info.name);
            $('.email').text(data.user_info.email);
            $('.country').text(data.country_name);
            // TRADER DATA
            $('#trade-volume').text(data.total_volume);
            $('#total-withdraw').text(data.total_withdraw);
            $('#total-deposit').text(data.total_deposit);
            $('#wallet-balance').text(data.total_balance);
            $('#total-trades').text(data.total_trades);
            $('#trading-accounts').text(data.total_accounts);
            $('#wta-transfer').text(data.total_wta);
            $('#atw-transfer').text(data.total_atw);
            $('#total-bonus').text(data.total_bonus);
            $('#trader-to-trader-transfer').text(data.total_trader_send);
            $('.trade-volume').text(data.total_trader_recive);
            $('#ib-to-trader-transfer').text(data.total_ib_from_recive);
            $('#trader-to-ib-transfer').text(data.total_trader_to_ib_send);

            // update apex chart
            chart.updateOptions({
                series: [parseFloat(data.total_ib_commission), parseFloat(data.total_withdraw), parseFloat(data.total_deposit)],
            });
            notify('success', data.message, 'Trader Analysis')
        } else {
            notify('error', data.message, 'Trader Analysis')
        }
    }
    // reset forms
    $(document).on('click', "#reset", function() {
        $("#ib-analysis-form").trigger('reset');
        $(".select2-trader").val('').trigger('change')
        $(".form-control").val("");
    })
</script>
@stop
<!-- BEGIN: page JS -->