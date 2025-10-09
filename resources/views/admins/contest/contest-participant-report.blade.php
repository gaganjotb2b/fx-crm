@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Contest List Report')
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
        span.input-group-text {
    height: 38px;
}

    /* for Laptop */
       td,th{
        	overflow: hidden;
        	text-overflow: ellipsis;
        	white-space: nowrap;
        }
    
        @media screen and (max-width: 1280px) and (min-width: 800px) {
            .ib-withdraw thead tr th:nth-child(4),
            .ib-withdraw tbody tr td:nth-child(4){
                display: none;
            }
          
        }
    
    
        
        @media screen and (max-width: 1440px) and (min-width: 900px) {
            .ib-withdraw thead tr th:nth-child(4),
            .ib-withdraw tbody tr td:nth-child(4){
                display: none;
            }
           .small-none{
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
                            <h2 class="content-header-title float-start mb-0">Contest List</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('ib-management.Home')}}</a>
                                    </li>
                                    {{-- <li class="breadcrumb-item"><a href="#">{{__('admin-breadcrumbs.manage_request')}}</a> --}}
                                    </li>
                                    <li class="breadcrumb-item active">Contest
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
                <!-- Collapsible and Refresh Actions -->
                <div class="row">
                    <div class="col-lg-4 col-6">
                        <div class="card">
                            <div class="card-body border-start-3 border-start-primary">
                                <div class="d-flex">
                                    <div class="section-icon">
                                        <i data-feather='layers' class="icon-trd text-primary"></i>
                                    </div>
                                    <div> <h4>Total Loss/Profit</h4>
                                    <div class="info">
                                        <strong class="counter total_commission">20</strong>
                                        <span class="text-primary">(<span class="counter total_trades">2</span>)</span>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="card">
                            <div class="card-body border-start-3 border-start-primary">
                                 <div class="d-flex">
                                    <div class="section-icon">
                                            <i data-feather='layers' class="icon-trd text-primary"></i>
                                    </div>
                                    <div><h4>Total Participant</h4>
                                        <div class="info">
                                            <strong class="counter volume">10</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="card">
                            <div class="card-body border-start-3 border-start-primary">
                                 <div class="d-flex">
                                    <div class="section-icon">
                                            <i data-feather='layers' class="icon-trd text-primary"></i>
                                    </div>
                                    <div><h4>Total Trade Volume</h4>
                                        <div class="info">
                                            <strong class="counter volume">20</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Collapsible and Refresh Actions -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <!--Search Form -->
                                <div class="card-body mt-2">

                                    <table id="ib_transfer_tbl" class="datatables-ajax ib-withdraw table table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Rank</th>
                                                <th>Account Number</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Total Trade</th>
                                                <th>Loss/Profit</th>
                                                <th>Total Deposit</th>
                                                <th>Platform</th>
                                                <th>Contest</th>
                                                <th>Joind</th>
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
    $(document).ready(function(){

        var dt = $('#ib_transfer_tbl').DataTable( {
            "processing": true,
            "serverSide": true,
            "searching":false,
            "lengthChange":true,
            "buttons": true,
            "dom": 'B<"clear">lfrtip',
                buttons: [
                {
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
                "url": "/admin/contest/contest-participant-list-report?op=data_table",
                "data": function (d) {
                      return $.extend( {}, d, {
                        "from": $("#from").val(),
                        "to": $("#to").val(),
                        "min": $("#min").val(),
                        "max": $("#max").val(),
                        "verification": $('#verification').val(),
                        "approved_status":$("#approved_status").val(),
                        "info":$("#info").val(),
                      });
                    }
            },

            "columns": [
                { "data": "rank" },
                { "data": "ac_num" },
                { "data": "name" },
                { "data": "email" },
                { "data": "total_trade" },
                { "data": "loss_profit" },
                { "data": "total_deposit" },
                { "data": "platform" },
                { "data": "contest" },
                { "data": "join_contest" },

            ],

            "drawCallback": function( settings ) {
                $("#filterBtn").html("FILTER");
                $("#total_amount").html('$'+settings.json.total_amount);

                var rows = this.fnGetData();
                if (rows.length !== 0 ) {
                    feather.replace();
                }
            }


        });
        $('#filterBtn').click(function (e) {
            dt.draw();
        });

    });

           //    datatable descriptions
//    $(document).on("click",".dt-description",function (params) {
    
//        let __this = $(this);
//        let id=$(this).data('id');
//     //    let table_id=$(this).data('table_id');
//     //    console.log(table_id);
//        $.ajax({
//             type : "GET",
//             url : '/admin/contest/contest-list-description/'+id,
//             dataType : 'json',
//             success :function (data) {
//                 if (data.status==true) {
//                     if($(__this).closest("tr").next().hasClass("description")){
//                         $(__this).closest("tr").next().remove();
//                         $(__this).find('.w').html(feather.icons['plus'].toSvg());
//                     }else{
//                         $(__this).closest('tr').after(data.description);
//                         $(__this).closest('tr').next('.description').slideDown('slow').delay(5000);
//                         // $(__this).find('svg').remove();
//                         $(__this).find('.w').html(feather.icons['minus'].toSvg());

//                         //Inner datatable
//                     //     if ($(__this).closest('tr').next('.description').find('.withdraw-account-details').length) {
//                     //     $(__this).closest('tr').next('.description').find('.withdraw-account-details').DataTable().clear().destroy();
//                     //     var cd = (new Date()).toISOString().split('T')[0];
//                     //     var dt_trading_account = $(__this).closest('tr').next('.description').find('.withdraw-account-details').DataTable({
//                     //         "processing": true,
//                     //         "serverSide": true,
//                     //         "searching": false,
//                     //         "lengthChange": false,
//                     //         "dom": 'Bfrtip',
//                     //         "ajax": { "url": "/admin/manage-report/trading-account-inner-fetch-data/" + user_id },
//                     //         "columns": [
//                     //             { "data": "acount_number" },
//                     //             { "data": "platform" },
//                     //             { "data": "group" },
//                     //             { "data": "leverage" },
//                     //             { "data": "date" },
//                     //         ],
//                     //         "order": [[1, 'desc']],
//                     //         "drawCallback": function (settings) {
//                     //         var rows = this.fnGetData();
//                     //         if (rows.length !== 0) {
//                     //             feather.replace();
//                     //          }
//                     //         }
//                     //     });
//                     //   }
//                     }
//                 }
//             }
//        })
//    });

// datatable export function
$(document).on("change","#fx-export",function () {
  if ($(this).val()==='csv') {
    $(".buttons-csv").trigger('click');
  }
  if ($(this).val()==='excel') {
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
      "fn": function () {
          $.fn.dataTable.ext.buttons[button].action.call(me, e, dt, node, config);
          dt.context[0].aoDrawCallback = dt.context[0].aoDrawCallback.filter(function (e) { return e.sName !== "ssb" });
      }
  });
  dt.page.len(999999999).draw();
  setTimeout(function () {
      dt.page(start);
      dt.page.len(len).draw();
  }, 500);
  }


  $(document).ready(function () {
    $("#resetBtn").click(function () {
        $("#filterForm")[0].reset();
        $('#verification').prop('selectedIndex', 0).trigger("change");
        $('#approved_status').prop('selectedIndex', 0).trigger("change");
        $('#platform').prop('selectedIndex', 0).trigger("change");
        $('#info').prop('selectedIndex', 0).trigger("change");

    });
});


 /*<-------------------Decline request End--------------------->*/
    </script>


@stop
<!-- BEGIN: page JS -->
