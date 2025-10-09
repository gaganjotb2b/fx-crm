@extends(App\Services\systems\VersionControllService::get_ib_layout())
@section('title', 'Support Ticket')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('common-css/data-list-style.css') }}">
<style>
    .error-msg {
        color: red;
    }

    #b-icon-dollar {
        font-size: 3rem;
    }

    .bullet {
        height: 12px;
        width: 12px;
        display: inline-block;
        border-radius: 50%;
    }

    #data-list li:hover {
        background-color: #EEE;
        border-radius: 5px;
    }

    .ticket_active:hover,
    .ticket_active {
        background-color: var(--custom-primary) !important;
        border-radius: 5px !important;
    }


    .al_ajax_loder {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .al_ajax_loder img {
        width: 100%;
        height: 100%;
        padding: 10px;
        max-width: 100px;
        max-height: 100px;
    }

    .item_prority {
        border: 1px solid;
        text-align: center;
        border-radius: 50px;
        font-size: 14px;
    }

    #add_new_ticket,
    #chet-details {
        position: absolute;
        width: 95%;
        margin: 0 auto;
        top: 0;
        left: 110%;
        transition: 0.4s;
        height: 100%
    }

    .overflow-x-hidden {
        overflow-x: hidden !important;
    }

    .cheting_auto_card {
        flex: 0 0 auto;
        width: auto;
        max-width: 70%;
        text-align: justify;
    }

    .al_des_file {
        width: 40px;
        border: 1px solid gray;
        height: 100%;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 17px;
        cursor: pointer;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #d2d6da;
        border-right-color: rgb(210, 214, 218);
        border-right-style: solid;
        border-right-width: 1px;
        border-left-color: rgb(210, 214, 218);
        border-left-style: solid;
        border-left-width: 1px;
        appearance: none;
        border-radius: 0.5rem;
        transition: box-shadow 0.15s ease, border-color 0.15s ease;
        border-radius: 0 .5rem .5rem 0 !important;
    }

    .show_select_img {
        padding: 15px;
        border-top: 1px solid #d2d6da;
        border-radius: 6px;
        position: absolute;
        right: 0;
        bottom: 0;
        width: 100%;
        margin: 0 auto;
        text-align: center;
        visibility: hidden;
        opacity: 0;
        transition: .4s;
        padding-bottom: 50px;
    }

    .show_select_img.activePrv {
        bottom: 100%;
        visibility: visible;
        opacity: 1;
    }

    .show_select_img .card-body,
    .show_select_img .card-header {
        padding: 10px;
        justify-content: center;
    }

    .show_select_img img {
        width: 100%;
        height: 100%;
        max-width: 150px;
        max-height: 150px;
        object-fit: cover;
    }

    .btn-close.al_imgPreClose {
        position: absolute;
        right: 0;
        padding: 5px;
        color: #495057;
        top: 0;
        line-height: 0;
    }

    .imag_prev img {
        width: 100%;
        height: 80px;
        object-fit: contain;
    }

    .al-minHei {
        height: 100%;
        min-height: 540px;
    }

    .al_tr_width {
        flex: 0 0 80%;
        width: 100%;
    }



    @media only screen and (max-width: 991px) {

        .al-md-pop {
            position: absolute !important;
            width: 97%;
            /* height: 100%; */
            left: 15px;
            /* right: 0; */
            display: none;
            padding: 0 24px;
            box-sizing: border-box;
        }

        #add_new_ticket,
        #chet-details {
            width: 100%;
        }

        .moving-tab {
            display: none;
        }

        .flex-column {
            flex-direction: row !important;
        }

        .nav.nav-pills .nav-item {
            flex: 0 0 33.33%;
        }

        .nav.nav-pills .nav-link.active {
            box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
        }
    }

    @media only screen and (max-width: 576px) {
        .nav.nav-pills .nav-item {
            flex: 0 0 50%;
            text-align: left;
        }

    }

    .close {
        padding: 5px 10px;
        line-height: 14px;
    }

    div#data-list li {
        list-style: none;
    }
</style>
@if(App\Services\systems\VersionControllService::check_version()==='lite')
<style>
    .page-header {
        padding: 0;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        background-size: cover;
        background-position: 50%;
        min-height: 100px;
        border-radius: 10px;
    }

    .mask {
        position: absolute;
        background-size: cover;
        background-position: center center;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0.8;
    }

    .bg-gradient-primary {
        background-color: var(--custom-primary);
    }

    .mt-n6 {
        margin-top: -4rem !important;
    }

    .shadow-blur {
        box-shadow: inset 0 0px 1px 1px rgba(254, 254, 254, 0.9), 0 20px 27px 0 rgba(0, 0, 0, 0.05) !important;
    }
</style>
@endif
@stop
@section('bread_crumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm">
            <a class="opacity-3 text-dark" href="javascript:;">
                <svg width="12px" height="12px" class="mb-1" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <title>{{ __('page.shop') }}</title>
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-1716.000000, -439.000000)" fill="#252f40" fill-rule="nonzero">
                            <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(0.000000, 148.000000)">
                                    <path d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z">
                                    </path>
                                    <path d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z">
                                    </path>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </a>
        </li>
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">{{ __('page.support') }}</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
            {{ __('support-ticket.support_ticket') }}
        </li>
    </ol>
    <h6 class="font-weight-bolder mb-0">{{ __('support-ticket.all_tickets') }}</h6>
</nav>
@stop
@section('content')
<div class="custom-height-con">


    <div class="container-fluid">
        <div class="page-header min-height-100 border-radius-xl mt-4">
            <span class="mask bg-gradient-primary opacity-6"></span>
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
            <div class="row gx-4">

                <div class="col-lg-6 ">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active " data-filte="all" data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">
                                    <span class="ms-1">{{ __('support-ticket.all') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1" data-filte-status="Open" data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">
                                    <span class="bullet bg-success"></span>
                                    <span class="ms-1">{{ __('support-ticket.Open') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 " data-filte-status="Closed" data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                    <span class="bullet bg-primary"></span>
                                    <span class="ms-1"> {{ __('support-ticket.Closed') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 " data-filte-status="Answered" data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                    <span class="bullet bg-warning"></span>
                                    <span class="ms-1"> {{ __('support-ticket.Answered') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 " data-filte-status="In-Progress" data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                    <span class="bullet bg-danger"></span>
                                    <span class="ms-1"> {{ __('support-ticket.In-Progress') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 " data-filte-status="On-Hold" data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                    <span class="bullet bg-secondary"></span>
                                    <span class="ms-1"> {{ __('support-ticket.On-Hold') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="row ">
            <div class="col-md-12 col-lg-4">
                <div class="card blur shadow-blur max-height-vh-70 overflow-auto overflow-x-hidden mb-5 mb-lg-0">
                    <div class="card-header p-3">
                        <div class="d-flex justify-content-between">
                            <h6>{{ __('support-ticket.my_tickets') }}</h6>
                            <button type="button" class="btn bg-gradient-primary btn-block mb-3" id="new_compose">
                                <i class="fas fa-envelope-open-text  " style="margin-right: 5px;"></i>
                                {{ __('support-ticket.open_ticket') }}
                            </button>
                        </div>

                        <input type="text" id="ticket_search" class="form-control" placeholder="{{ __('support-ticket.Search_tickets') }}">
                    </div>
                    <div class="card-body p-2" id="data-list">
                        <div class="al_ajax_loder">
                            <img src="{{ asset('comon-icon/ajax-loader-big.gif') }}" alt="Loading">
                        </div>

                    </div>
                </div>
            </div>
            <div class="position-relative overflow-hidden col-md-12 col-lg-8 al-md-pop">
                <div class="al-minHei">
                    <!-- show item  cheting  details -->
                    <div class="card blur shadow-blur " id="chet-details">
                        <div class="card-header shadow-lg">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="d-flex align-items-center">
                                        <!-- <span id="bullet_color" class="bullet"></span> -->
                                        <a href="#" id="Close-chat" class="close Close-chat">
                                            <i class="fas fa-arrow-left    "></i>
                                        </a>
                                        <div class="ms-3">
                                            <h6 class="mb-0 d-block " id="tick_subject"></h6>
                                            <span class="text-sm text-dark opacity-8 " id="tick_date_time"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1 my-auto pe-0">
                                    <p class="text-capitalize item_prority m-0 " id="prority_color"></p>
                                </div>
                                <div class="col-1 my-auto ps-0">
                                    <div class="dropdown">
                                        <button class="btn btn-icon-only shadow-none text-dark mb-0" type="button" data-bs-toggle="dropdown">
                                            <i class="ni ni-settings"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end me-sm-n2 p-2" aria-labelledby="chatmsg">
                                            <li>
                                                <a id="al_delete_itmes" class="dropdown-item border-radius-md text-danger" href="javascript:;">
                                                    {{ __('support-ticket.delete_ticket') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body overflow-auto overflow-x-hidden" id="scroll-area">


                        </div>
                        <div class="card-footer d-block">
                            <form class="align-items-center position-relative" action="{{ route('ib.support.support-send-reply') }}" id="form-sent-replay" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="  show_select_img shadow-blur blur ">
                                    <div class="position-relative">
                                        <div class="nam_close">
                                            <p id="fileName">
                                            </p>
                                        </div>
                                        <button type="button" class="btn-close al_imgPreClose" data-bs-dismiss="modal" aria-label="Close">
                                            <i class="fas fa-times    "></i>
                                        </button>

                                    </div>
                                    <div class="">
                                        <img id="previewImg" src="{{ asset('admin-assets/app-assets/images/icons/doc.png') }}">
                                    </div>

                                </div>
                                <div class="d-flex  position-relative">
                                    <div class="input-group">
                                        <input type="text" name="msg" class="form-control" placeholder="Type here" aria-label="Message example input">
                                        <input type="hidden" id="al_ticket_id" name="id">
                                        <label class="al_des_file" for="file">
                                            <i class="fas fa-file-image"></i>
                                        </label>
                                        <input class="form-control" onchange="previewFile(this);" type="file" name="file" id="file" hidden>
                                    </div>
                                    <a type="submit" class="btn bg-gradient-primary mb-0 ms-2" id="send_msg" data-el="fg" onclick="_run(this)" data-form="form-sent-replay" data-file='true' data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="replay_call_back" data-btnid="send_msg">
                                        <i class="ni ni-send"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Add new tickets  -->
                    <div class="card blur shadow-blur" id="add_new_ticket">
                        <div class="card-header shadow-lg">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="d-flex align-items-center">
                                        <div class="ms-3 d-flex">
                                            <a href="#" class="close Close-create">
                                                <i class="fas fa-arrow-left    "></i>
                                            </a>
                                            <h6 class="mb-0 d-block "> {{ __('support-ticket.compose_your_ticket') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-2  text-center my-auto pe-0">
                                                                        <i class="ni ni-send  "></i>
                                                                    </div>
                                                                        -->
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('ib.support.create-ticket') }}" id="form-create-ticket" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-8">
                                        <div class="form-group">
                                            <label for="subject"> {{ __('support-ticket.Subject') }}</label>
                                            <input type="text" class="form-control" name="subject" id="subject" placeholder="{{ __('support-ticket.Subject') }}:">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="Priority">{{ __('support-ticket.Priority') }}</label>
                                            <select class="form-control" id="Priority" name="Priority">
                                                <option value="normal">Normal</option>
                                                <option value="high">High</option>
                                                <option value="critical">Critical</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8">
                                        <div class="form-group">
                                            <label for="attch">{{ __('support-ticket.Attachment') }}</label>
                                            <input type="file" class="form-control" name="attch" id="attch" onchange="previewFileTicket(this);">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group imag_prev">
                                            <label for="file">{{ __('support-ticket.Priview') }}</label>
                                            <img id="tic_file_prev" for="file" src="https://cdn2.iconfinder.com/data/icons/game-files-add-on/48/v-02-512.png">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="msge">{{ __('support-ticket.Description') }}</label>
                                    <textarea class="form-control" id="msge" rows="3" name="description"></textarea>
                                </div>
                                <div class="form-group text-end">
                                    <button type="reset" class="btn btn-block mb-3">
                                        <i class="fas fa-redo-alt " style="margin-right: 5px;"></i>
                                        {{ __('support-ticket.Reset') }}
                                    </button>
                                    <a type="submit" class="btn bg-gradient-primary btn-block mb-3" id="create_ticket" data-el="fg" onclick="_run(this)" data-form="form-create-ticket" data-file='true' data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="create_call_back" data-btnid="create_ticket">
                                        <i class="ni ni-send  " style="margin-right: 5px;"></i>
                                        {{ __('support-ticket.Submit') }}
                                    </a>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- include footer -->
@include('layouts.footer')
</div>
@stop
@section('corejs')
<script src="{{ asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js') }}"></script>
@stop
@section('page-js')
<script src="{{ asset('common-js/data-list-for-trader-support.js') }}"></script>
<script>
    const beforeSend = [];

    function scrollRepalyBox() {
        var scroll = $('#scroll-area');
        scroll.animate({
            scrollTop: scroll.prop("scrollHeight")
        });
    }

    //get list of support ticket
    function showSupportData(filterData) {
        var data_list = $("#data-list");
        var dataList = data_list.data_list({
            serverSide: true,
            url: '/ib/support/support-ticket-get',
            listPerPage: 5,
            filterUrl: filterData
        });
        beforeSend.push(filterData);
        // console.log(beforeSend.pop());
    }
    showSupportData('all');
    $(document).ready(function() {
        var base_url = window.location.origin;
        var loaderImPath = "{{ asset('/comon-icon/ajax-loader-big.gif') }}";
        var loaderHtml =
            '<div class="al_ajax_loder"><img src="' + loaderImPath + '" alt="Loading"></div>';

        // data filter  by user type click function  
        $(document).on('click', '[data-filte]', function(e) {
            $('#data-list').html(loaderHtml);
            var filterVal = $(this).attr('data-filte');
            showSupportData('userType=' + filterVal);
        });
        // data filter by status click function  
        $(document).on('click', '[data-filte-status]', function(e) {
            $('#data-list').html(loaderHtml);
            var filterVal = $(this).attr('data-filte-status');
            showSupportData('status=' + filterVal);
        });
        //filter ticket by search 
        $(document).on('keyup', '#ticket_search', function() {
            $('#data-list').html(loaderHtml);
            if ($('.active[data-filte-status]').length != 0) {
                var activeSt = $('.active[data-filte-status]').attr('data-filte-status');
            } else {
                var activeSt = 'all';
            }
            showSupportData('status=' + activeSt + '&searchval=' + $(this).val());

        });
        //  on click show details 
        $(document).on('click', '#data-list li', function() {
            $(this).addClass('ticket_active').siblings().removeClass('ticket_active');
            $('.al-md-pop').css({
                'display': 'block'
            });
            $('#add_new_ticket').css({
                'left': '110%'
            });
            $('#chet-details').css({
                'left': '0'

            });
            var dataId = $(this).find('a').attr('data-id');
            $('#al_ticket_id').val(dataId);
            $('#scroll-area').html(loaderHtml);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/ib/support/support-ticket-reply?id=' + dataId,
                dataType: 'json',
                method: 'post',
                success: function(data) {
                    $('#tick_subject').html(data.headerinfo.subject);
                    $('#bullet_color').removeAttr('class').attr('class', 'bullet ' + data
                        .headerinfo
                        .bullet_class);
                    $('#prority_color').html(data.headerinfo.prority);
                    $('#prority_color').css({
                        'border-color': data.headerinfo.prority_color
                    });
                    $('#tick_date_time').html(data.headerinfo.dateTime);
                    $('#scroll-area').html(data.html);
                    scrollRepalyBox();
                    setServerReplay();
                }
            });

        });

        // delete single ticket on click
        $(document).on('click', '#al_delete_itmes', function() {
            var data = {
                'id': $('#al_ticket_id').val(),
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/ib/support/support-ticket-delete',
                dataType: 'json',
                data: data,
                method: 'POST',
                success: function(data) {
                    if (data.status) {
                        showSupportData(beforeSend[beforeSend.length - 1]);
                        $('#chet-details').css({
                            'left': '110%'
                        });
                        notify('success', "Successfully Delete", 'Delete Ticket');
                    } else {
                        notify('error', "Try Again", 'Delete Ticket');
                    }
                }
            });
        });
        //preview page  click function 
        $(document).on('click', '.al_imgPreClose', function(event) {
            $('.show_select_img').removeClass('activePrv');
            $("#file").val(null);
        });

        // open ticket button  click function 
        $(document).on('click', '#new_compose', function() {
            $('.al-md-pop').css({
                'display': 'block'
            });
            $('#chet-details').css({
                'left': '110%'
            });
            $('#add_new_ticket').css({
                'left': '0%'
            });

        });
        // Close Chat button  click function 
        $(document).on('click', '.Close-chat', function() {

            $('#chet-details').animate({
                'left': '110%'
            });
            $('.al-md-pop').css({
                'display': 'none'
            });
            return false;

        });
        // Close Create button  click function 
        $(document).on('click', '.Close-create', function() {
            $('#add_new_ticket').animate({
                'left': '110%'
            });
            $('.al-md-pop').css({
                'display': 'none'
            });
            return false;

        });

        // ticket crate reset function 
        $(document).on('reset', '#form-create-ticket', function() {
            $("#tic_file_prev").attr("src",
                'https://cdn2.iconfinder.com/data/icons/game-files-add-on/48/v-02-512.png');
        });

        //input keyup function
        $(document).on('keypress', 'input', function(e) {
            if (e.which == 13) {
                $(this).closest('form').find('[type=submit]').trigger('click');
                return false;
            }
        });


    });
    //replay  call back
    function replay_call_back(data) {
        if (data.status) {
            $('#form-sent-replay').trigger('reset');
            $('#scroll-area').append(data.backData);
            scrollRepalyBox();
            notify('success', data.message, 'Replay');
            $('.show_select_img').removeClass('activePrv');

        } else {
            notify('error', data.message, 'Replay');
        }
    }

    //replay  call back
    function create_call_back(data) {
        if (data.status) {
            notify('success', data.message, 'Create Ticket');
            showSupportData(beforeSend[beforeSend.length - 1]);
            $('#form-create-ticket').trigger('reset');
        } else {
            notify('error', data.message, 'Create Ticket');
        }
        $.validator("form-create-ticket", data.errors);
    }
    //preview input image for repaly
    function previewFile(input) {
        $('.show_select_img').addClass('activePrv');
        var file = $("input[type=file]").get(0).files[0];
        $('#fileName').html(file.name);
        var ext = file.name.split('.').pop();
        if (ext == 'jpg' || ext == 'png' || ext == 'gif' || ext == 'jpeg' || ext == 'JPEG' || ext == 'GIF' || ext ==
            'PNG' || ext == 'JPG') {
            if (file) {
                var reader = new FileReader();
                reader.onload = function() {
                    imgSrc = reader.result;
                    $("#previewImg").attr("src", reader.result);
                }
                reader.readAsDataURL(file);
            }
        } else {
            var defPath = "{{ asset('/admin-assets/app-assets/images/icons/doc.png') }}";
            $("#previewImg").attr("src", defPath);
        }

    }
    //preview input image for new ticket
    function previewFileTicket(input) {
        var file = $("#attch").get(0).files[0];
        var ext = file.name.split('.').pop();
        if (ext == 'jpg' || ext == 'png' || ext == 'gif' || ext == 'jpeg' || ext == 'JPEG' || ext == 'GIF' || ext ==
            'PNG' || ext == 'JPG') {
            if (file) {
                var reader = new FileReader();
                reader.onload = function() {
                    imgSrc = reader.result;
                    $("#tic_file_prev").attr("src", reader.result);
                }
                reader.readAsDataURL(file);
            }
        } else {
            var defPath = "{{ asset('/admin-assets/app-assets/images/icons/doc.png') }}";
            $("#tic_file_prev").attr("src", defPath);
        }

    }

    function setServerReplay() {
        const myInterval = setInterval(getServerReplay, 1000);

        function myStopFunction() {
            clearInterval(myInterval);
        }

        $(document).on('click', '#al_delete_itmes,#Close-chat,#new_compose,#data-list li', function() {
            myStopFunction();
        });
    }

    function getServerReplay() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/ib/support/get-server-replay',
            dataType: 'json',
            method: 'POST',
            success: function(data) {
                if (data.status) {
                    $('#scroll-area').append(data.data);
                    scrollRepalyBox();
                }

            }
        });

    }
</script>
@stop