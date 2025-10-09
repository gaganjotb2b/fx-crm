@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','IB Tree')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/fonts/font-awesome/css/font-awesome.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/jstree.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
<!-- number input -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-tree.css')}}">
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content ib-tree-admin">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{__('ib-management.IB Tree')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('ib-management.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{__('ib-management.Ib-Management')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('ib-management.IB Tree')}}
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
            <form action="" method="post" id="ib-tree-form">
                <div class="card">
                    <div class="p-3">
                        <div class="row">
                            <div class="col-12 col-md-3" data-bs-toggle="tooltip" data-bs-placement="top" title="IB Name / email / phone ">
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="basic-addon1">@</span>
                                    <!-- <select name="user_email" id="ib-email" class="form-select">
                                    <option value="">Choose an IB</option>
                                </select> -->
                                    <input type="text" class="form-control" name="ib_info" placeholder="IB name / email / phone">
                                </div>
                            </div>
                            <div class="col-12 col-md-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Trader Name / email / phone ">
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="basic-addon1">@</span>
                                    <input type="text" class="form-control" name="trader_info" placeholder="Trader name / email / phone">
                                </div>
                            </div>
                            <div class="col-12 col-md-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Trading account number">
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="basic-addon1">@</span>
                                    <input type="text" class="form-control" name="account_number" placeholder="Account number">
                                </div>
                            </div>
                            <div class="col-md-3 d-flex ">
                                @if(Auth::user()->hasDirectPermission('read ib tree'))
                                <div class="input-group mb-2 me-1">
                                    <button type="button" class="btn btn-warning w-100" id="reset-tree" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>">Reset</button>
                                </div>
                                <div class="input-group mb-2">
                                    <button type="button" class="btn btn-primary w-100" id="view-tree" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>">{{__('ib-management.View IB Tree')}}</button>
                                </div>
                                @else
                                <div class="input group mb-2"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </form>
        <div class="card">
            <div class="card-header">
                <div></div>
                <div>
                    <div class="d-flex">
                        <div class="d-flex">
                            <div class="bg-light-primary" style="width:20px; height:20px"></div>
                            <div style="margin-left:3px">{{__('ib-management.Roots')}}</div>
                        </div>
                        <div class="d-flex ms-3">
                            <div class="bg-dropbox" style="width:20px; height:20px"></div>
                            <div style="margin-left:3px">{{__('ib-management.IB\'s')}}</div>
                        </div>
                        <div class="d-flex ms-3">
                            <div class="bg-success" style="width:20px; height:20px"></div>
                            <div style="margin-left:3px">{{__('ib-management.Traders')}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="card-body">
                <div id="parent"></div>
                <div id="filer_0">
                    {{__('ib-management.Search for IB Tree')}}
                </div>
            </div>
        </div>

    </div>
</div>
</div>
<!-- END: Content-->
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
<!-- here include vendor js -->
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>

<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>

<!-- js tree -->
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/jstree.min.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/ib-commission-structure.js')}}"> </script>
<!-- <script src="{{asset('admin-assets/app-assets/js/scripts/pages/ib-tree.js')}}"></script> -->
<script src="{{asset('common-js/select-get-ib.js')}}"></script>
<script>
    /*********************************************
     * view more info
     ********************************************/
    function tree_more(e = null, e_event) {
        $(".jstree-anchor").each(function() {
            $(this).attr('href', 'javaScript:void(0)')
        })
        // e.stopPropagation();
        if (!e_event) var e_event = window.event
        e_event.cancelBubble = true;
        e_event.stopImmediatePropagation();
        let item_type = $(e).closest("li").data("type");
        if ($(e).closest("a").next("div").length) {
            $(e).closest("a").next("div").slideToggle();
        } else {
            if (item_type == 'trader') {
                $(e).closest('a').after("<div class='more-info-con' style='display:none'><div class='tree-info-row'><div>Name</div><div>:</div><div class='tdr-name'></div></div><div class='tree-info-row tdr-ac-con'><div class='tdr-ac-lavel'>Phone</div><div>:</div><div class='tdr-account'></div></div><div class='s-jstree-more-btn d-none'><span class='s-tdr-more-ac-icon'></span>More</div></div>");
            } else {
                $(e).closest('a').after("<div class='more-info-con' style='display:none'><div class='tree-info-row'><div>Name</div><div>:</div><div class='tdr-name'></div></div><div class='tree-info-row'><div class='tdr-ac-lavel'>Phone</div><div>:</div><div class='tdr-account'></div></div></div><div class='s-jstree-more-btn d-none'><span class='s-tdr-more-ac-icon d-none'></span>More</div></div>");
            }
            $(e).closest("a").next("div").slideToggle();
        }
        $(e).toggleClass("rotate-caret-tree");
        if (item_type == 'trader') {
            let tradingAcc = $(e).closest("li").data("tradingacc");
            $(e).closest('a').next(".more-info-con").addClass("tree-color-trader");
            $(e).closest('a').next("div").find(".tdr-account").html(tradingAcc);
            console.log(tradingAcc);
        } else {
            let tradingAcc = $(e).closest("li").data("tradingacc");
            $(e).closest('a').next(".more-info-con").addClass("tree-color-trader");
            $(e).closest('a').next("div").find(".tdr-account").html(tradingAcc);
            // 
            $(e).closest('a').next(".more-info-con").addClass("tree-color-ib");
            $(e).closest('a').next("div").find(".tdr-ac-con").css({
                "visibility": "hidden"
            });
        }
        // display name
        let trader_name = $(e).closest("li").data("apnmae");
        $(e).closest('a').next("div").find(".tdr-name").html(trader_name);
    }
    // *******************************************
    // ib tree start ajax
    //********************************************
    // START: IB Tree
    // ------------------------------------------------------------------------------
    $(document).on("click", "#view-tree", function() {
        $(this).prop('disabled',true);
        let loader = $(this).data('loading');
        let btn_text = $(this).text();
        $(this).html(loader);
        var obj = $(this);
        var filter_0 = $('#filer_0').text();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/ib-management/ib-tree-create',
            type: 'POST',
            data: $("#ib-tree-form").serializeObject(),
            dataType: 'json',
            success: function(data) {
                // Configaration Of Js Tree
                if (data.status == true) {
                    $("#parent").html(data.parent);
                    $("#filer_0").html(data.ib_tree);
                    $("#ib-tree").jstree({
                        "plugins": ["themes", "html_data", "ui", "crrm", "hotkeys", "types"],
                        "core": {
                            "initially_open": ["phtml_1"]
                        },
                        "types": {

                            "default": {
                                "icon": "fas fa-plus"
                            },
                            "root": {
                                "icon": "fas fa-plus"
                            },
                            'f-open': {
                                'icon': "fas fa-minus"
                            },
                            'f-closed': {
                                'icon': "fas fa-plus"
                            }
                        },
                    }).bind("select_node.jstree", function(event, data) {
                        return data.instance.toggle_node(data.node);
                    });
                    /* Toggle between folder open and folder closed */
                    $("#ib-tree").on('open_node.jstree', function(event, data) {
                        data.instance.set_type(data.node, 'f-open');
                    });
                    $("#ib-tree").on('close_node.jstree', function(event, data) {
                        data.instance.set_type(data.node, 'f-closed');
                    });
                    $("#ib-tree").find(".jstree-unchecked").each(function() {
                        alert($(this).attr("id"));
                    });
                    /*************remove plus icon for leaf****** */
                    $('.jstree-leaf').each(function(index, item) {
                        $(this).find(".jstree-themeicon").remove();
                    });

                    // display success message
                    // ----------------------------------------------
                    toastr['success']('Your IB Tree was  generated', 'Tree Generated', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                    $(".jstree-anchor").each(function() {
                        $(this).attr('href', 'javaScript:void(0)')
                    })
                } else {
                    notify('error', data.message, 'IB Tree')
                    $("#parent").html(data.parent);
                    $("#filer_0").html(filter_0);
                }
                $(obj).html(btn_text);
                $(obj).prop('disabled',false);
            },
            error: function(e) {

            }
        });
        $(".jstree-anchor").each(function() {
            $(this).attr('href', 'javaScript:void(0)')
        })
    }) //END: click function


    /*************************
     * s-jstree-root-icon
     *************************/
    $(document).on('click', ".s-jstree-root-icon , .s-ib-email-inner", function() {
        $("#ib-tree").slideToggle();
        $(this).closest("p").toggleClass("tree-is-open");
        if ($(this).closest("p").hasClass("tree-is-open")) {
            $(".s-jstree-root-icon").html('<i class="fas fa-plus"></i>');
        } else {
            $(".s-jstree-root-icon").html('<i class="fas fa-minus"></i>');
        }
    })
    /*****************root caret down click and expand the more info******************** */
    /************************************************************************************ */
    $("#parent").on("click", ".s-jstree-root-caret", function(event) {
        event.stopPropagation();
        if ($(this).closest("p").next(".more-info-con").length) {
            $(this).closest("p").next(".more-info-con").slideToggle();
        } else {
            $(this).closest('p').after("<div class='more-info-con root-mor-info-con' style='display:none'><div class='tree-info-row'><div>Name</div><div>:</div><div class='tdr-name'></div></div><div class='tree-info-row'><div class='phone-label'>Phone</div> <div>:</div> <div class='phone-number'>01747894142</div></div><div class='s-jstree-more-btn d-none'><span class='s-tdr-more-ac-icon'></span>More</div></div>");
            $(this).closest("p").next(".more-info-con").slideToggle();
        }
        $(this).toggleClass("rotate-caret-tree");
        let item_type = $(this).closest("li").data("type");
        if (item_type == 'trader') {
            $(this).closest('p').next(".more-info-con").addClass("tree-color-trader");
        } else {
            $(this).closest('p').next(".more-info-con").addClass("tree-color-ib");
        }
        // display name
        let trader_name = $(this).prev(".s-ib-email-inner").data("apnmae");
        $(this).closest('p').next(".more-info-con").find(".tdr-name").html(trader_name);
    });

    $(".jstree-anchor").hover(function() {
        $(this).css({
            "background-color": "rgba(0,0,0,0.2) !important"
        });
    });
    // tree reset
    // *****************************************************************
    $(document).ready(function() {
        var filter_0 = $("#filer_0").text();
        $(document).on('click', "#reset-tree", function() {
            $("#ib-tree-form").trigger('reset');
            $("#parent").html('');
            $("#filer_0").html(filter_0);
        });
    })
</script>
@stop
<!-- BEGIN: page JS -->