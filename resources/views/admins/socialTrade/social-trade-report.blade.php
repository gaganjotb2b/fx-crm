@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Social Trades Activities Report')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/calendars/fullcalendar.min.css') }}">
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
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/pages/app-calendar.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
<style>
        tbody tr td:first-child {
            border-left: 4px solid #948bf4 !important;
        }
     td,th{
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
            .deposit-request tbody tr td:nth-child(3){
                display: none;
            }
           .small-none{
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
        <div id="orver_loading" class="lds-ripple loading" style="display: none;"><div></div><div></div></div>
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Social Trades Activities Report</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('ib-management.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Social Trade</a>
                                </li>
                                <li class="breadcrumb-item active">Social Trades Activities Report
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
                                <form id="filterForm" action="" class="dt_adv_search" method="POST">
                                    @csrf
                                    <input type="hidden" name="op" value="datatable_mt5">
                                    <input type="hidden" name="server" value="mt5">
                                    <input type="hidden" name="start" value="0">
                                    <input type="hidden" name="length" id="export_length" value="0">
                                    <input type="hidden" name="isnew" value="false">
                                    <input type="hidden" name="order" value="Order">
                                    <input type="hidden" name="dir" value="desc">
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-md-4 mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Status">
                                            <select class="select2 form-select" name="type" id="type">
                                                <optgroup label="Search By  Type">
                                                    <option value="">All </option>
                                                    <option value="mam">Mam </option>
                                                    <option value="pamm">Social Trade</option>
                                                </optgroup>
                                            </select>
                                        </div>

                                        <div class="col-md-4  mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Search By Status">
                                            <select class="select2 form-select" name="status" id="status">
                                                <optgroup label="Search By  Type">
                                                    <option value="">All </option>
                                                    <option value="copy">Copy </option>
                                                    <option value="uncopy">Uncopy </option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    

                                        <div class="col-md-4">
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Master Account" class="form-control dt-input dt-full-name" data-column="1" name="master_account" id="master_account" placeholder="Search By Master Account" data-column-index="0" />
                                        </div>

                                        <div class="col-md-4">
                                            <input type="text" data-bs-toggle="tooltip" data-bs-placement="top" title="Slave Account" class="form-control dt-input dt-full-name" data-column="1" name="slave_account" id="slave_account" placeholder="Search By Slave Account" data-column-index="0" />
                                        </div>

                                
                                        <div class="col-md-4">
                                            <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter create Date" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
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
                                                <span class="input-group-text">to</span>
                                                <input id="date_to" type="text" name="date_to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                    
                                            </div>
                                        </div>

                                     
                                        <div class="col-md-2 text-right">
                                            <button id="resetBtn" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('ad-reports.btn-reset')}}</span>
                                            </button>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <button id="filterBtn" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                                <span class="align-middle">{{__('category.FILTER')}}</span>
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
                            <div class="card-body mt-2">

                                <table id="example" class="datatables-ajax deposit-request table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Master Account </th>
                                            <th>Slave Account </th>
                                            <th>Status </th>                 
                                            <th>Type </th>                 
                                            <th>Date </th>      
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
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')

<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
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

<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>


<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
<script src="{{ asset('trader-assets/assets/js/filter.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/common.js') }}"></script>

<!-- datatable  -->
<script>
        var dt;
        var total_trades = 0;
        var isnew = true;
        var columns = ['master', "slave", "action", "type" , "created_at"];
        // $(document).ready(function() {
            $("#orver_loading").show();
            dt = $('#example').DataTable( {
                serverSide: true,
                ordering: true,
                searching: false,
                lengthMenu: [10,25, 50, 100],
                pageLength: 10,
                ajax: function ( data, callback, settings ) {
                    $("#filterForm").submit(function(e){

                        var oderBY = columns[data.order[0].column];
                        var oderDir = data.order[0].dir;

                        var postData = $(this).serializeArray();

                       
                        
                        let typeValue = $('#type').val();
                        let master_account = $('#master_account').val();
                        let slave_account = $('#slave_account').val();
                        let date_from = $('#date_from').val();
                        let date_to = $('#date_to').val();
                        let status = $('#status').val();
                        
                        postData.push({ name: 'start', value: data.start });
                        postData.push({ name: 'length', value: data.length });
                        postData.push({ name: 'order', value: oderBY });
                        postData.push({ name: 'dir', value: oderDir });
                        postData.push({ name: 'type', value: typeValue });
                        postData.push({ name: 'master_account', value: master_account });
                        postData.push({ name: 'slave_account', value: slave_account });
                        postData.push({ name: 'date_from', value: date_from });
                        postData.push({ name: 'date_to', value: date_to });
                        postData.push({ name: 'status', value: status });

                        $.ajax({
                            url: '/admin/pamm/social-trades-ativity-report-process',
                            dataType: 'json',
                            method: 'POST',
                            data: postData,
                            success: function(result){ 
                                
                                // if (isnew) {
                                    
                                  
                                // }
                                callback( {
                                    
                                    draw: data.draw,
                                    data: result.data,
                                    recordsTotal: result.recordsTotal,
                                    recordsFiltered: result.recordsTotal
                                });        
                            },
                           
                        });
                        e.preventDefault(); //STOP default action
                        $(this).unbind();
                    });
                    $("#filterForm").submit(); //SUBMIT FORM
                },
                fixedColumns: true,
                fixedHeader: true,
                scrollX: true,
                scrollY: 350,
                scroller: {
                    loadingIndicator: true
                },
                "drawCallback": function( settings ) {
                    isnew = false;
                    $("#filterBtn").html("Filter");
                    $("#orver_loading").fadeOut();
                      
                },
                "Order": [[0, 'desc']]
            } );


            $('#filterBtn').click(function (e) {
                isnew = true;
                $(this).html("<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>");
                $("#orver_loading").show();
                dt.draw();
            });
        // } );

    
        $(document).ready(function() {
        $("#resetBtn").click(function() {
            $("#filterForm")[0].reset();
            $('#transaction_type').prop('selectedIndex', 0).trigger("change");
            $('#verification_status').prop('selectedIndex', 0).trigger("change");
            $('#status').prop('selectedIndex', 0).trigger("change");
            dt.draw();
        });
    });

    /*<--------------Datatable export function End----------------->*/

</script>
@stop
<!-- BEGIN: page JS -->