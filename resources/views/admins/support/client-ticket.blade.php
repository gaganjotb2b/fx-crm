@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Client Tickets')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/veditors/quill/katex.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/toastr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">


<link rel="preconnect" href="https://fonts.gstatic.com">
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=Inconsolata&amp;family=Roboto+Slab&amp;family=Slabo+27px&amp;family=Sofia&amp;family=Ubuntu+Mono&amp;display=swap">
@stop
<!-- BEGIN: page css -->
@section('page-css')

<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/pages/app-email.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('common-css/data-list-style.css') }}">
<style>
    .list-group-item {
        background: transparent !important;
    }

    .data-list-footer {
        display: flex;
        justify-content: space-between;
        padding: 20px;
    }

    .item_prority {
        border-radius: 50px;
        padding: 1px 6px;
        margin-right: 5px !important;
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

    .row.repalyform {
        position: fixed;
        bottom: 0;
        width: 100%;
        left: 0;
        right: 0;
        transform: translateX(10px);
    }

    .email-application .email-app-details .email-scroll-area {
        height: calc(100% - 10rem);
    }

    .chat-app-form {
        width: 100%;
        display: flex;
    }

    .dropdown-menu.dropActive {
        display: block;
        transform: translate(-123px, 25px);
        inset: 0px auto auto 0px;
    }

    #tag {
        padding: 10px;
    }

    .show_select_img {
        position: absolute;
        right: 0;
        bottom: -250%;
        width: 100%;
        margin: 0 auto;
        text-align: center;
        visibility: hidden;
        opacity: 0;
        transition: .4s;
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
        padding: 10px;
        /* font-size: 20px; */
    }
</style>
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
<div class="app-content content email-application">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-area-wrapper container-fluid p-0">
        <div class="sidebar-left">
            <div class="sidebar">
                <div class="sidebar-content email-app-sidebar">
                    <div class="email-app-menu">
                        <div class="sidebar-menu-list">
                            <div class="list-group list-group-messages">
                                <a href="#" class="list-group-item list-group-item-action active" data-filte="all">
                                    <i data-feather="mail" class="font-medium-3 me-50"></i>
                                    <span class="align-middle">{{ __('support-ticket.all') }} </span>
                                    <span class="badge badge-light-primary rounded-pill float-end allCount">{{ $allCount }}</span>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action " data-filte="ib">
                                    <i data-feather="users" class="font-medium-3 me-50"></i>
                                    <span class="align-middle">
                                        {{ __('support-ticket.ticket_from_ib') }}</span>
                                    <span class="badge badge-light-primary rounded-pill float-end IbTCount">{{ $IbTCount }}</span>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action" data-filte="trader">
                                    <i data-feather="user" class="font-medium-3 me-50"></i>
                                    <span class="align-middle"> {{ __('support-ticket.ticket_from_trader') }}</span>
                                    <span class="badge badge-light-primary rounded-pill float-end traderCount">{{ $traderCount }}</span>
                                </a>
                            </div>
                            <!-- <hr /> -->
                            <h6 class="section-label mt-3 mb-1 px-2">{{ __('support-ticket.status') }}</h6>
                            <div class="list-group list-group-labels">
                                <a href="#" data-filte-status="Open" class="list-group-item list-group-item-action"><span class="bullet bullet-sm bullet-success me-1"></span>{{ __('support-ticket.Open') }}</a>
                                <a href="#" data-filte-status="Closed" class="list-group-item list-group-item-action"><span class="bullet bullet-sm bullet-primary me-1"></span>
                                    {{ __('support-ticket.Closed') }}</a>
                                <a href="#" data-filte-status="Answered" class="list-group-item list-group-item-action"><span class="bullet bullet-sm bullet-warning me-1"></span>
                                    {{ __('support-ticket.Answered') }}</a>
                                <a href="#" data-filte-status="In-Progress" class="list-group-item list-group-item-action"><span class="bullet bullet-sm bullet-danger me-1"></span>
                                    {{ __('support-ticket.In-Progress') }}</a>
                                <a href="#" data-filte-status="On-Hold" class="list-group-item list-group-item-action"><span class="bullet bullet-sm bullet-secondary me-1"></span>
                                    {{ __('support-ticket.On-Hold') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="content-right">
            <div class="content-wrapper container-xxl p-0">
                <div class="content-header row">
                </div>
                <div class="content-body">
                    <div class="body-content-overlay"></div>
                    <!-- Email list Area -->
                    <div class="email-app-list">
                        <!-- Email search starts -->
                        <div class="app-fixed-search d-flex align-items-center">
                            <div class="sidebar-toggle d-block d-lg-none ms-1">
                                <i data-feather="menu" class="font-medium-5"></i>
                            </div>
                            <div class="d-flex align-content-center justify-content-between w-100">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i data-feather="search" class="text-muted"></i></span>
                                    <input type="text" class="form-control" id="ticket_search" placeholder="{{ __('support-ticket.Search_tickets') }}" aria-label="Search..." />
                                </div>
                            </div>
                        </div>
                        <!-- Email search ends -->

                        <!-- Email actions starts -->
                        <div class="app-action">
                            <div class="action-left">
                                <div class="form-check selectAll">
                                    <input type="checkbox" class="form-check-input" id="selectAllCheck" />
                                    <label class="form-check-label fw-bolder ps-25" for="selectAllCheck">{{ __('support-ticket.Select All') }}</label>
                                </div>
                            </div>
                            <div class="action-right">
                                <ul class="list-inline m-0">
                                    <li class="list-inline-item">
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle" id="tag">
                                                <i data-feather="tag" class="font-medium-2"></i>
                                            </a>
                                            <div class="dropdown-menu" data_clcikId="tag">
                                                <a data-st-click="Open" href="#" class="dropdown-item"><span class="me-50 bullet bullet-success bullet-sm"></span>{{ __('support-ticket.Open') }}</a>
                                                <a data-st-click="Closed" href="#" class="dropdown-item"><span class="me-50 bullet bullet-primary bullet-sm"></span>{{ __('support-ticket.Closed') }}</a>
                                                <a data-st-click="Answered" href="#" class="dropdown-item"><span class="me-50 bullet bullet-warning bullet-sm"></span>{{ __('support-ticket.Answered') }}</a>
                                                <a data-st-click="In-Progress" href="#" class="dropdown-item"><span class="me-50 bullet bullet-danger bullet-sm"></span>{{ __('support-ticket.In-Progress') }}</a>
                                                <a data-st-click="On-Hold" href="#" class="dropdown-item"><span class="me-50 bullet bullet-secondary  bullet-sm"></span>{{ __('support-ticket.On-Hold') }}</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-inline-item mail-delete" id="al_delete_itmes">
                                        <span class="action-icon"><i data-feather="trash-2" class="font-medium-2"></i></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Email actions ends -->

                        <!-- Email list starts -->
                        <div class="email-user-list">

                            <ul class="email-media-list h-100" id="data-list">
                                <div class="al_ajax_loder">
                                    <img src="{{ asset('comon-icon/ajax-loader-big.gif') }}" alt="Loading">
                                </div>
                            </ul>

                        </div>
                        <!-- Email list ends -->
                    </div>
                    <!--/ Email list Area -->
                    <!-- Detailed Email View -->
                    <div class="email-app-details">
                        <!-- Detailed Email Header starts -->
                        <div class="email-detail-header">
                            <div class="email-header-left d-flex align-items-center">
                                <span id="go-back" class="go-back me-1"><i data-feather="chevron-left" class="font-medium-4"></i></span>
                                <h4 class="email-subject mb-0" id="po_subject"></h4>
                            </div>
                            <div class="email-header-right ms-2 ps-1">

                                <ul class="list-inline m-0">
                                    <li class="d-flex align-items-center" id="bullet_html"></li>
                                </ul>

                            </div>
                        </div>
                        <!-- Detailed Email Header ends -->

                        <!-- Detailed Email Content starts -->
                        <div class="email-scroll-area">
                            <div class="row">
                                <div class="col-12">
                                    <div class="email-label">
                                        <!-- <span class="mail-label badge rounded-pill badge-light-primary">Company</span> -->
                                    </div>
                                </div>
                            </div>

                            <div id="show_item_reply" class="h-100">
                                <div class="al_ajax_loder h-100">
                                    <img src="{{ asset('comon-icon/ajax-loader-big.gif') }}" alt="Loading">
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-12">
                                <div class="email-detail-header repalyform position-relative">
                                    <div class="card show_select_img">
                                        <div class="card-header position-relative">
                                            <div class="nam_close">
                                                <p id="fileName"> </p>
                                            </div>
                                            <button type="button" class="btn-close al_imgPreClose" data-bs-dismiss="modal" aria-label="Close"></button>

                                        </div>
                                        <div class="card-body">
                                            <img id="previewImg" src="{{ asset('admin-assets/app-assets/images/icons/doc.png') }}">
                                        </div>

                                    </div>
                                    <form class="chat-app-form  " action="{{ route('admin.support.support-send-reply') }}" id="form-sent-replay" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" id="al_ticket_id" name="id">
                                        <div class="input-group input-group-merge me-1 form-send-message">

                                            <input type="text" name="msg" class="form-control message" placeholder="{{ __('support-ticket.type_your_message') }}" />
                                            <span class="input-group-text ">
                                                <label for="attach-doc" class="attachment-icon form-label mb-0">
                                                    <i data-feather="image" class="cursor-pointer text-secondary"></i>
                                                    <input onchange="previewFile(this);" type="file" name="file" id="attach-doc" hidden />
                                                </label>
                                            </span>

                                        </div>
                                        <a type="submit" class="btn btn-primary send" id="send_msg" data-el="fg" onclick="_run(this)" data-form="form-sent-replay" data-file='true' data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="replay_call_back" data-btnid="send_msg">
                                            <i data-feather="send" class="d-lg-none"></i>
                                            <span class="d-none d-lg-block">{{ __('support-ticket.send') }}</span>
                                        </a>

                                    </form>

                                </div>
                            </div>
                        </div>
                        <!-- Detailed Email Content ends -->
                    </div>
                    <!--/ Detailed Email View -->

                </div>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
</div>
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('common-js/data-list-for-support.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/app-email.js') }}"></script>
<script>
    const beforeSend = [];
    // smoth scroll in bottom
    function scrollSmoothlyToBottom(id) {
        const element = $(`.${id}`);
        element.animate({
            scrollTop: element.prop("scrollHeight")
        }, 500);
    }

    //get list of support ticket
    function showSupportData(filterData) {
        var data_list = $("#data-list");
        var dataList = data_list.data_list({
            serverSide: true,
            url: '/admin/support/support-ticket-get',
            listPerPage: 6,
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
            $('.email-app-details').removeClass('show');
            $('#data-list').html(loaderHtml);
            var filterVal = $(this).attr('data-filte');
            showSupportData('userType=' + filterVal);
        });
        // data filter by status click function  
        $(document).on('click', '[data-filte-status]', function(e) {
            var userType = $('.active[data-filte]').attr('data-filte');
            $('.email-app-details').removeClass('show');
            $('#data-list').html(loaderHtml);
            var filterVal = $(this).attr('data-filte-status');
            showSupportData('userType=' + userType + '&status=' + filterVal);
        });
        //filter ticket by search 
        $(document).on('keyup', '#ticket_search', function() {
            $('#data-list').html(loaderHtml);
            var userType = $('.active[data-filte]').attr('data-filte');
            showSupportData('userType=' + userType + '&searchval=' + $(this).val());

        });
        //show details on click
        $(document).on('click', '.user-mail', function(e) {
            var emailDetails = $('.email-app-details');
            var showDetails = true;
            var noResult = $(this).find('.no-results');
            if (e.target.tagName == 'INPUT') {
                showDetails = false;
            } else if (noResult.length != 0) {
                showDetails = false;
            }
            if (showDetails) {


                var dataId = $(this).find('div').attr('data-id');
                $('#al_ticket_id').val(dataId);
                $('#po_subject').html($(this).find('.subject').html());
                $('#bullet_html').html($(this).find('.bullet_html').html());
                emailDetails.toggleClass('show');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/support/support-ticket-reply?id=' + dataId,
                    dataType: 'json',
                    method: 'post',
                    success: function(data) {
                        $('#show_item_reply').html(data);
                        scrollSmoothlyToBottom('email-scroll-area');
                        setServerReplay();
                    }
                });


            }
        });

        // click on go back button
        var goBack = $('.go-back');
        var emailDetails = $('.email-app-details');
        if (goBack.length) {
            goBack.on('click', function(e) {
                e.stopPropagation();
                emailDetails.removeClass('show');
                $('#show_item_reply').html(loaderHtml);
            });
        }
        // select all 
        $(document).on('change', '#selectAllCheck', function() {
            if ($(this).is(':checked')) {
                $("[name='ticketID']").each(function(index, obj) {
                    $(obj).prop("checked", true);
                });
            } else {
                $("[name='ticketID']").each(function(index, obj) {
                    $(obj).prop("checked", false);
                });
            }
        });
        //delete  items
        $(document).on('click', '#al_delete_itmes', function() {
            var checkItem = $('#data-list input[type="checkbox"][name="ticketID"]:checked');
            var checkItemId = [];
            if (checkItem.length != 0) {
                for (let i = 0; i < checkItem.length; i++) {
                    checkItemId[i] = checkItem[i].value;
                }
                var data = {
                    'ids': checkItemId,
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/support/support-ticket-delete',
                    dataType: 'json',
                    data: data,
                    method: 'POST',
                    success: function(data) {
                        if (data.status) {
                            for (let index = 0; index < data.retArr.length; index++) {
                                var targetItem = $('[data-id="' + data.retArr[index] + '"]')
                                    .parent('li');
                                targetItem.slideUp(150, function() {
                                    $(this).remove();
                                });

                            }
                            getRealTimeCount();
                            notify('success', "successfully Delete", 'Delete Ticket');
                        } else {
                            notify('error', "Try Again", 'Delete Ticket');
                        }
                    }
                });
            } else {
                notify('info', "Please Select Item", 'Delete Ticket');
            }

        });

        //change  status  items
        $(document).on('click', '[data-st-click]', function() {
            var checkItem = $('#data-list input[type="checkbox"][name="ticketID"]:checked');
            var checkItemId = [];
            if (checkItem.length != 0) {
                for (let i = 0; i < checkItem.length; i++) {
                    checkItemId[i] = checkItem[i].value;
                }
                var data = {
                    'ids': checkItemId,
                    'st': $(this).attr('data-st-click')
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/support/support-ticket-st-update',
                    dataType: 'json',
                    data: data,
                    method: 'POST',
                    success: function(data) {
                        if (data.status) {
                            notify('success', "successfully Update", 'Update Ticket');
                            $('[data_clcikId="tag"]').toggleClass('show');
                            showSupportData(beforeSend[beforeSend.length - 1]);

                        } else {
                            notify('error', "Try Again", 'Update Ticket');
                            $('[data_clcikId="tag"]').toggleClass('show');
                        }
                    }
                });
            } else {
                notify('info', "Please Select Item", 'Update Ticket');
            }

        });
        //show change status dropdown
        $(document).on('click', '#tag', function() {
            $(this).toggleClass('active');
            $('[data_clcikId="tag"]').toggleClass('dropActive');
        });
        //close dropdown on click in body
        $(document).click(function(event) {
            if (!$(event.target).is('.dropActive, .feather')) {
                $('.dropActive').removeClass('dropActive');
            }
        });
        //preview page  click function 
        $(document).on('click', '.al_imgPreClose', function(event) {
            $('.show_select_img').removeClass('activePrv');
            $("#attach-doc").val(null);
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
            $('#show_item_reply').append(data.backData);
            scrollSmoothlyToBottom('email-scroll-area');
            notify('success', data.message, 'Replay');
            $('.show_select_img').removeClass('activePrv');
            $('#bullet_html .bullet').css({
                'background-color': '#ff9f43'
            });
            showSupportData(beforeSend[beforeSend.length - 1]);

        } else {
            notify('error', data.message, 'Replay');

        }
    }

    //preview input image 
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

    //get realtime count
    function getRealTimeCount() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/support/support-ticket-get-count',
            dataType: 'json',
            method: 'POST',
            success: function(data) {
                if (data.status) {
                    $('.allCount').html(data.allcount);
                    $('.IbTCount').html(data.IbTCount);
                    $('.traderCount').html(data.traderCount);
                }
            }
        });
    }

    function setServerReplay() {
        const myInterval = setInterval(getServerReplay, 1000);

        function myStopFunction() {
            clearInterval(myInterval);
        }

        $(document).on('click', '[data-filte],[data-filte-status]', function() {
            myStopFunction();
        });

        var goBack = $('.go-back');
        if (goBack.length) {
            goBack.on('click', function(e) {
                myStopFunction();
            });
        }
    }

    function getServerReplay() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/support/get-server-replay',
            dataType: 'json',
            method: 'POST',
            success: function(data) {
                if (data.status) {
                    $('#show_item_reply').append(data.data);
                    scrollSmoothlyToBottom('email-scroll-area');
                }

            }
        });

    }

    // document.addEventListener("keydown", function(event) {
    //     if (event.key === "Enter") {
    //         event.preventDefault(); // Prevent form from submitting
    //         document.querySelector("input[type=submit]").click(); // Click the hidden submit button
    //     }
    // });

    document.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            // event.preventDefault(); // Prevent default behavior of the Enter key
            document.querySelector("#send_msg").click(); // Click the link with the ID "send_msg"
        }
    });
</script>

@stop
<!-- BEGIN: page JS -->