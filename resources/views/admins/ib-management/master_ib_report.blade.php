@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Master IB')
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
<style>
    .top-table-border-bottom-3 {
        border-bottom-width: 5px !important;
    }


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
<div class="app-content content master-ib-admin">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('admin-menue-left.Master_IB') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}">{{__('ib-management.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#">{{__('ib-management.Ib-Management')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('admin-menue-left.Master_IB') }}</li>
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
                                <form id="filter-form" class="dt_adv_search" method="POST">
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Kyc Verification Status">
                                            <label for="status" class="form-label">Verification Status</label>
                                            <select class="select2 form-select" name="status" id="status">
                                                <option value="">{{__('ib-management.all')}}</option>
                                                <option value="0">{{__('page.pending')}}</option>
                                                <option value="1">{{__('page.Verified')}}</option>
                                                <option value="2">{{__('page.declined')}}</option>
                                            </select>
                                        </div>
                                        <!--Filter By IB Level-->
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="IB Level">
                                            <label for="ibg_group" class="form-label">IB Group</label>
                                            <select class="select2 form-select" name="ibg_group" id="ibg_group">
                                                <optgroup label="IB Group">
                                                    <option value="">{{__('ib-management.all')}}</option>
                                                    @foreach($ib_groups as $ib_group)
                                                    <option value="{{ $ib_group->group_name }}">{{ $ib_group->group_name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                        <!--Filter By IB Info-->
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="IB">
                                            <label for="ib_info" class="form-label">IB Info.</label>
                                            <input type="text" class="form-control dt-input dt-full-name" data-column="1" name="ib_info" id="ib_info" placeholder="{{ ($varsion == 'pro') ? 'IB Email / Name / Phone / Country' : 'IB Email / Name / Phone' }}" data-column-index="0" />
                                        </div>
                                        <!--Filter By Trader Info-->
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Trader">
                                            <label for="trader_info" class="form-label">Trader Info.</label>
                                            <input type="text" class="form-control dt-input" data-column="1" name="trader_info" id="trader_info" placeholder="{{ ($varsion == 'pro') ? 'Trader Name / Name / Phone / Country' : 'Trader Name / Name / Phone' }}" data-column-index="1" />
                                        </div>
                                        <!--Filter By trading account-->
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Account Number">
                                            <label for="trading_acc" class="form-label">Trading Account</label>
                                            <input type="text" class="form-control dt-input" data-column="1" name="trading_acc" id="trading_acc" placeholder="Account number" data-column-index="1" />
                                        </div>
                                        <!--Filter By Manager-->
                                        @if($varsion =='pro')
                                            <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Manager">
                                                <label for="manager" class="form-label">Manager</label>
                                                <input type="text" class="form-control dt-input" data-column="1" name="manager" id="manager" placeholder="Account Manager / Desk Manager" data-column-index="1" />
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
                                        <!--Filter By Category-->
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Category">
                                            <label class="form-label" for="category">Category</label>
                                            <select class="select2 form-select" id="category" name="category">
                                                <option value="">{{ __('client-management.All') }}</option>
                                                @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">
                                                    {{ ucwords($category->name) }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--Filter By Joining Date-->
                                        <div class="col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="IB Joining Date">
                                            <label for="" class="form-label">Joining Date</label>
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
                                                <input id="date_from" type="text" name="date_from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                <span class="input-group-text">To</span>
                                                <input id="date_to" type="text" name="date_to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-right" data-bs-toggle="tooltip" data-bs-placement="top" title="Reset">
                                            <label for=""></label>
                                            <button id="btn-reset" type="button" class="btn btn-secondary w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('ib-management.reset')}}</span>
                                            </button>
                                        </div>
                                        <div class="col-md-2 text-right" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter">
                                            <label for=""></label>
                                            <button id="btn-filter" type="button" name="filter" value="filter" class="btn btn-primary w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('category.FILTER')}}</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <hr class="my-0" />
                        </div>
                        <div class="card">
                            <div class="card-datatable m-1">
                                <table class="master_ib_table ib-withdraw table table-responsive datatables-ajax">
                                    <thead>
                                        <tr>
                                            <th>{{__('page.name')}}</th>
                                            <th>{{__('page.email')}}</th>
                                            <th>{{__('page.phone')}}</th>
                                            <th>{{__('page.country')}}</th>
                                            <th>IB Group</th>
                                            <th>{{__('page.joining-date')}}</th>
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
<!-- END: Content-->

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
    // $(document).ready(function() {
    //     var master_ib_report = dt_fetch_data(
    //         '/admin/ib-management/master-ib-report/fetch-data',
    //         [{
    //                 "data": "name"
    //             },
    //             {
    //                 "data": "email"
    //             },
    //             {
    //                 "data": "phone"
    //             },
    //             {
    //                 "data": "country"
    //             },
    //             {
    //                 "data": "joining_date"
    //             },
    //             {
    //                 "data": "action"
    //             },
    //         ],
    //         true, true, true, [0, 1, 2, 3, 4], null, true, false
    //     )
    // });

    $(function() {
        var isRtl = $('html').attr('data-textdirection') === 'rtl';

        var dt_ajax_table = $('.datatables-ajax'),
            assetPath = '../../../app-assets/';

        if ($('body').attr('data-framework') === 'laravel') {
            assetPath = $('body').attr('data-asset-path');
        }
        var master_ib_report = dt_ajax_table.fetch_data({
            url: '/admin/ib-management/master-ib-report/fetch-data',
            csv_export: true,
            description: true,
            total_sum: false,
            export_col:[0, 1, 2, 3, 4, 5],
            length_change: true,
            icon_feather: true,
            customorder: 4,
            columns: [{
                    "data": "name"
                },
                {
                    "data": "email"
                },
                {
                    "data": "phone"
                },
                {
                    "data": "country"
                },
                {
                    "data": "ibg"
                },
                {
                    "data": "joining_date"
                },
                {
                    "data": "action"
                },
            ],
        });

    });


    // master ib datatable description
    $(document).on("click", ".dt-description", function(params) {
        let __this = $(this);
        let ib_id = $(this).data('ib_id');
        $.ajax({
            type: "GET",
            url: '/admin/ib-management/master-ib-report/description/fetch-data/' + ib_id,
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
                                    "url": "/admin/ib-management/master-ib-report/description/inner-datatable/fetch-data/" + ib_id
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
</script>
@stop
<!-- BEGIN: page JS -->