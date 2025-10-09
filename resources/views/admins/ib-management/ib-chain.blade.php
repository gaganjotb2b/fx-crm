@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','IB Chain')
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
                        <h2 class="content-header-title float-start mb-0">{{__('page.IB_Chain')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}">{{__('ib-management.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#">{{__('ib-management.Ib-Management')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('page.IB_Chain')}}</li>
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
                        <form action="#" method="post" id="filter-form">
                            <div class="card">
                                <div class="card-header border-bottom d-flex justfy-content-between">
                                    <h4 class="card-title">Filter IB Chain</h4>
                                    <div class="btn-exports d-flex justify-content-between">
                                        <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                            <option value="download" data-icon="download" selected>{{__('ib-management.export')}}</option>
                                            <option value="csv" data-icon="file">CSV</option>
                                            <option value="excel" data-icon="file">Excel</option>
                                        </select>
                                    </div>
                                </div>

                                <!--Search Form -->
                                <div class="card-body mt-2">
                                    <div class="dt_adv_search">
                                        <div class="row g-1 mt-1">
                                            <!-- filter by IB info -->
                                            <div class="col-md-3" data-bs-toggle="tooltip" data-bs-placement="top" title="IB name / email / phone">
                                                <!-- <input type="text" class="form-control dt-input" data-column="1" name="search" id="search" placeholder="Search By IB Name/Email" data-column-index="1" /> -->
                                                <!-- <select name="search" id="ib-email" class="form-select">
                                                    <option value="">Choose an IB</option>
                                                </select> -->
                                                <input type="text" class="form-control" id="ib-info" name="ib_info" placeholder="IB name / email / phone">
                                            </div>
                                            <!-- filter by trader info -->
                                            <div class="col-md-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Trader name / email / phone">
                                                <input type="text" class="form-control" id="trader-info" name="trader_info" placeholder="Trader name / email / phone">
                                            </div>
                                            <!-- filter by account number -->
                                            <div class="col-md-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Trading account number">
                                                <input type="text" class="form-control" id="account-number" name="account_number" placeholder="Account number">
                                            </div>
                                            <div class="col-md-3 d-flex">
                                                <button id="btn-reset" type="button" name="reset" value="filter" class="btn btn-warning w-100 waves-effect waves-float waves-light me-1">
                                                    <span class="align-middle">Reset</span>
                                                </button>
                                                <button id="btn-filter" type="button" name="filter" value="filter" class="btn btn-primary w-100 waves-effect waves-float waves-light">
                                                    <span class="align-middle">Filter</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-0" />
                            <!-- filter ib after create chain -->

                        </form>
                        <div class="card">
                            <!-- card datatable -->
                            <div class="card-datatable m-1" id="ib-chain">
                                <table class="master_ib_table table table-responsive datatables-ajax" id="table-dt-root">
                                    <thead>
                                        <tr>
                                            <th>IB {{__('page.name')}}</th>
                                            <th>IB {{__('page.email')}}</th>
                                            <th>{{__('page.level')}}</th>
                                            <th>{{__('page.commission')}} {{__('page.Earned')}} </th>
                                            <th>{{__('page.commission')}} {{__('page.volume')}}</th>
                                            <th>Joining Date</th>
                                            <th>Verification Status</th>
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
<script src="{{asset('common-js/select-get-ib.js')}}"></script>
<script>
    $(document).ready(function() {
        var master_ib_report = dt_fetch_data(
            '/admin/ib-management/ib-chain',
            [{
                    "data": "name"
                },
                {
                    "data": "email"
                },
                {
                    "data": "level"
                },
                {
                    "data": "commission_earned"
                },
                {
                    "data": "commission_volume"
                },
                {
                    "data": "join_date"
                },
                {
                    "data": "kyc_status"
                },
            ],
            true, true, true, [0, 1, 2, 3, 4, 5, 6], '', false, false, false
        )
        $("#btn-reset").on("click", function(e) {
            $("#filter-form").trigger('reset');
            $("#ib-info").val('');
            $("#trader-info").val('');
            $("#account-number").val('');
            $("#table-dt-root").DataTable().draw();
        });
        $(document).on("click", "#btn-filter", function() {
            if ($('#ib-email').val() != "") {
                $("#filter-body").slideDown();
            } else {
                $("#filter-body").slideUp();
            }
        })
        $("#filter-body").slideUp();

        // filter created ib chain
        $(document).on("click", '#btn-filter-chain', function() {
            $("#btn-filter").trigger('click');
        });

        reset_ib_chain_filter();

        function reset_ib_chain_filter() {
            $("#ib_info").val("");
            $("#trader-info").val("");
            $("#date_from").val("");
            $("#date_to").val("");
            $("#trading-account").val("");
            $("#verification-status").val("");
            $("#manager").val();
        }
        $(document).on("click", "#btn-reset-chain", function() {
            reset_ib_chain_filter();
            $("#table-dt-root").DataTable().draw();
        });
        // filter reset whene change chain
        $(document).on("change", "#ib-email", function() {
            reset_ib_chain_filter();
        })

        // // master ib datatable description
        // $(document).on("click", ".dt-description", function(params) {
        //     let __this = $(this);
        //     let ib_id = $(this).data('ib_id');
        //     $.ajax({
        //         type: "GET",
        //         url: '/admin/ib-management/master-ib-report/description/fetch-data/' + ib_id,
        //         dataType: 'json',
        //         success: function(data) {
        //             if (data.status == true) {
        //                 if ($(__this).closest("tr").next().hasClass("description")) {
        //                     $(__this).closest("tr").next().remove();
        //                     $(__this).find('.w').html(feather.icons['plus'].toSvg());
        //                 } else {
        //                     $(__this).closest('tr').after(data.description);
        //                     $(__this).closest('tr').next('.description').slideDown('slow').delay(5000);

        //                     $(__this).find('.w').html(feather.icons['minus'].toSvg());

        //                     // Inner datatable
        //                     if ($(__this).closest("tr").next(".description").find('.datatable-inner').length) {
        //                         $(__this).closest("tr").next(".description").find('.datatable-inner').DataTable().clear().destroy();
        //                         var dt_inner = $(__this).closest('tr').next('.description').find('.sub-ib-list').DataTable({
        //                             "processing": true,
        //                             "serverSide": true,
        //                             "searching": false,
        //                             "lengthChange": false,
        //                             "dom": 'Bfrtip',
        //                             "ajax": {
        //                                 "url": "/admin/ib-management/master-ib-report/description/inner-datatable/fetch-data/" + ib_id
        //                             },
        //                             "columns": [{
        //                                     "data": "name"
        //                                 },
        //                                 {
        //                                     "data": "email"
        //                                 },
        //                                 {
        //                                     "data": "trader"
        //                                 },
        //                                 {
        //                                     "data": "sponsor"
        //                                 }
        //                             ],
        //                             "order": [
        //                                 [1, 'desc']
        //                             ],
        //                             "drawCallback": function(settings) {
        //                                 var rows = this.fnGetData();
        //                                 if (rows.length !== 0) {
        //                                     feather.replace();
        //                                 }
        //                             }
        //                         });
        //                     }
        //                 }
        //             }
        //         }
        //     });
    });
</script>
@stop
<!-- BEGIN: page JS -->