@extends(App\Services\systems\VersionControllService::get_ib_layout())
@section('title', 'IB Tree')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/jstree.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-tree.css') }}">
<style>
    .error-msg {
        color: red;
    }

    #b-icon-dollar {
        font-size: 3rem;
    }

    /* START: tree style */
    /* ------------------------------------------------------------------------------- */
    .s-ib-email {
        border-left: 9px solid var(--custom-primary);
    }

    #jstree {
        padding-left: 2rem;
    }

    .jstree-anchor {
        height: auto !important;
        padding: 0.5rem;
        /* background: rgb(241, 242, 252); */
        margin-top: 6px;
        width: 350px;
        border-left: 8px solid var(--custom-primary);
        background: aliceblue;
    }

    .jstree-anchor {
        color: var(--custom-primary) !important
    }

    .trader-tree-item a {
        border-color: #21bdfd !important;
        color: #21bdfd !important;
    }

    .tree-more-info {
        float: right;
        font-size: 24px;
        transition: 1s all ease;
    }

    /* for roots */
    /* --------------------------------------------------------------- */
    .bg-gray-100 #parent .more-info-con {
        width: 375px;
        margin-left: 0;
        background-color: #e8f2fa;
        padding: 1rem;
    }

    .dark-layout #parent .more-info-con {
        width: 375px;
        margin-left: 0;
        background-color: #36436A91;
        padding: 1rem;
    }

    /* for chileds */
    /* --------------------------------------------------------------- */
    .bg-gray-100 .more-info-con {
        width: 350px;
        margin-left: 1.5rem;
        background-color: #D9E9F799;
        padding: 1rem;
        overflow: hidden;
    }

    .dark-layout .more-info-con {
        width: 350px;
        margin-left: 1.72rem;
        background-color: #36436A91;
        padding: 1rem;
    }

    .bg-gray-100 .jstree-anchor {
        height: auto !important;
        padding: 0.5rem;
        /* background: rgb(241, 242, 252); */
        margin-top: 6px;
        width: 350px;
        border-left: 8px solid var(--custom-primary);
        position: relative;
    }

    .dark-layout .jstree-anchor {
        height: auto !important;
        padding: 0.5rem;
        background: #161D31;
        margin-top: 6px;
        width: 350px;
        border-left: 8px solid var(--custom-primary);
        position: relative;
    }

    .jstree-default .jstree-clicked {
        background: #25474583 !important;
        border-radius: 2px;
        box-shadow: none !important;
        position: relative;
    }

    .jstree-hovered {
        background-color: rgba(0, 0, 0, 0.2) !important;
    }

    .rotate-caret-tree {
        transform: rotateX(180deg);
    }

    .tree-info-row div:nth-child(1) {
        margin-right: 1rem;
        opacity: 0.7;
    }

    .tree-info-row {
        display: flex;
    }

    .tree-info-row div:nth-child(2) {
        margin-right: 1rem;
    }

    .tree-color-ib {
        color: var(--custom-primary);
    }

    .tree-color-trader {
        color: #21bdfd;
    }

    .s-ib-email {
        border-left: 8px solid var(--custom-primary);
        width: 375px;
        display: flex;
        padding: 3px !important;
        margin-bottom: 0 !important;
        background-color: aliceblue;
    }

    .jstree-leaf a .jstree-themeicon {
        display: none;
    }

    .jstree-default .jstree-node,
    .jstree-default .jstree-icon {
        background-image: url(32px.png);
    }

    .jstree-icon.jstree-ocl {
        height: 2px !important;
        background-color: var(--custom-primary);
        margin-top: 22px;
    }

    .jstree-node {
        border-left: 2px solid var(--custom-primary);
        margin-left: 4rem !important;
    }

    .jstree-themeicon {
        background-position: center !important;
        background-size: contain !important;
        opacity: 0.5;
    }

    .jstree-anchor>.jstree-themeicon {
        margin-right: 1rem;
    }

    .s-jstree-root-icon {
        width: 32px;
        display: block;
        height: 24px;
        background-size: contain !important;
        cursor: pointer;
        opacity: 0.56;
        /* background: rgba(0, 0, 0, 0) url("{{ asset('trader-assets/assets/jstree-icon/minus.png') }}") no-repeat scroll center center; */
    }

    .s-ib-email {
        border-left: 8px solid var(--custom-primary);
        width: 375px;
        display: flex;
        padding: 3px !important;
        margin-bottom: 0 !important;
    }

    .s-jstree-root-caret {
        width: 32px;
        display: block;
        float: right;
        font-size: 23px;
        text-align: center;
        cursor: pointer;
        opacity: 0.5;
    }

    .s-ib-email-inner {
        width: 100%;
        margin-left: 1rem;
        cursor: pointer;
    }

    .tree-more-info {
        width: 32px;
        text-align: center;
        position: absolute;
        right: 0;
        height: 35px;
        /* background-color: antiquewhite; */
        top: 0;
        padding-top: 6px;
    }

    .root-mor-info-con {
        margin-left: 0;
        width: 375px;
    }

    .s-jstree-more-btn {
        display: flex;
        color: #0000008c;
        cursor: pointer;
    }

    .dark-version .s-jstree-root-caret .fa.fa-caret-down {
        color: #6c757d;
    }

    .s-tdr-more-ac-icon {
        width: 20px;
        background: rgba(0, 0, 0, 0) url("{{ asset('trader-assets/assets/jstree-icon/plus.png') }}") no-repeat scroll center center;
        background-size: 14px;
        opacity: 0.8;
    }

    .s-js-tree-ib-color {
        color: var(--custom-primary);
    }

    .s-tree-ib-indicator {
        display: flex;
    }

    .s-tree-ib-indicator {
        display: flex;
        color: var(--custom-primary);
    }

    .s-tree-indicator-con {
        display: flex;
    }

    .s-tree-trader-indicator {
        display: flex;
        color: #65c07e;
    }

    .s-js-tree-ib-color {
        color: var(--custom-primary);
        background: var(--custom-primary);
        width: 19;
        height: 12px;
        margin-top: 4px;
        margin-right: 3px;
    }

    .s-js-tree-trader-color {
        background: #65c07e;
        width: 24;
        height: 13;
        margin-top: 4px;
        margin-right: 3px;
    }

    .s-tree-accnotfnd {
        /* border: 1px solid red; */
        padding: 1rem;
        color: #a61b1b;
        border-radius: 3px;
    }

    .jstree-hovered {
        background-color: #d9e9f7 !important;
    }

    .jstree-default .jstree-clicked {
        background: #d9e9f7 !important;
        border-radius: 2px;
        box-shadow: none !important;
        position: relative;
    }
</style>
@stop
<!-- breadcrumb -->
@section('bread_crumb')
{!!App\Services\systems\BreadCrumbService::get_ib_breadcrumb()!!}
@stop
<!-- maincontent -->
@section('content')
<div class="container-fluid py-4">
    <div class="card custom-height-con">
        <div class="card-body">
            <div class="d-flex">
                <div class="d-flex align-items-center me-5">
                    <span class="bg-primary rounded me-1" style="width: 20px; height:20px"></span>
                    <span class="text-dark">{{ __('page.IB(s)') }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="rounded me-1" style="width: 20px; height:20px; background-color:#21bdfd;"></span>
                    <span class="text-dark">{{ __('page.Trader(s)') }}</span>
                </div>
            </div>
            <hr class="horizontal dark">
            <div id="parent">{!! $ib_tree['parent_ib'] !!}</div>

            {!! $ib_tree['ib_tree'] !!}
        </div>
    </div>
    <!-- include footer -->
    @include('layouts.footer')
</div>
@stop
@section('corejs')

@stop
@section('page-js')

<script src="{{ asset('trader-assets/assets/js/scripts/pages/wallet-to-account.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/scripts/pages/get-data-on-input.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/jstree.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/ib-tree.js') }}"></script>
<script>
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
    /*********************************************
     * view more info
     ********************************************/
    function tree_more(e = null, e_event) {
        console.log("ok");
        // e.stopPropagation();
        if (!e_event) var e_event = window.event
        e_event.cancelBubble = true;
        e_event.stopImmediatePropagation();
        let item_type = $(e).closest("li").data("type");
        if ($(e).closest("a").next("div").length) {
            $(e).closest("a").next("div").slideToggle();
        } else {
            if (item_type == 'trader') {
                $(e).closest('a').after(
                    "<div class='more-info-con' style='display:none'><div class='tree-info-row'><div>Name</div><div>:</div><div class='tdr-name'></div></div><div class='tree-info-row tdr-ac-con'><div class='tdr-ac-lavel'>Phone</div><div>:</div><div class='tdr-account'></div></div><div class='s-jstree-more-btn d-none'><span class='s-tdr-more-ac-icon'></span>More</div></div>"
                );
            } else {
                $(e).closest('a').after(
                    "<div class='more-info-con' style='display:none'><div class='tree-info-row'><div>Name</div><div>:</div><div class='tdr-name'></div></div><div class='tree-info-row'><div class='tdr-ac-lavel'>Phone</div><div>:</div><div class='tdr-account'></div></div></div><div class='s-jstree-more-btn d-none'><span class='s-tdr-more-ac-icon d-none'></span>More</div></div>"
                );
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
</script>
@stop