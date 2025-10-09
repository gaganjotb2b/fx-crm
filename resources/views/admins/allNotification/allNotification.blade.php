@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'All Notification')

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<style>
    /* .flex-grow-1 {
            display: flex !important;
            align-items: center !important;
        } */
    .media-heading {
        margin: 0 !important;
    }

    ul {
        margin: 0;
        padding: 0;
    }

    .scrollable-container.media-list .card {
        margin-bottom: 1rem;
    }

    .all_notification_ul li {
        margin-bottom: 1rem;
        padding: 10px;
        border-radius: 5px;
    }

    .media-list {
        list-style: none;
    }
</style>
@stop

@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('page.all') }}
                            {{ __('page.notifications') }} &nbsp;<i class="ficon" data-feather="bell"></i>
                        </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard') }}">{{ __('finance.home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ url('/allNotification/allNotification') }}">{{ __('page.notifications') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('page.all') }} {{ __('page.notifications') }}
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
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- <ul class="all_notification_ul " id="notificationsList">

            </ul> -->
            <table id="tbl-notification">
                <tbody>
                    <ul id="notificationsList" class="all_notification_ul">
                        <!-- List items for notifications will be dynamically generated -->
                    </ul>
                </tbody>
            </table>

        </div>
    </div>
</div>
@stop
@section('page-js')
<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>


<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>

<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#tbl-notification').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "lengthChange": false,
            "ajax": {
                "url": "/admin/manage-report/view-all-notification/fetch-data", // Replace with your server endpoint URL
                "type": "GET",
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "notification_type"
                },
                {
                    "data": "notification_text"
                },
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        console.log(data.location_url);
                        return '<a href="' + data.location_url + '" class="btn btn-sm btn-outline-warning">View</a>';
                    }
                }
            ],
            "fnDrawCallback": function(oSettings) {
                // Clear the existing list items
                $('#tbl-notification').empty();
                $('#notificationsList').empty();

                // Add new list items based on the data from DataTables
                var data = oSettings.json.data;
                for (var i = 0; i < data.length; i++) {
                    var icon = data[i].status === 'read' ? 'check' : 'alert-triangle';

                    var listItem = '<li class="media-list ' + (data[i].status === 'read' ? 'bg-light-success' : 'bg-light-danger') + '">';
                    listItem += '<div class="d-flex justify-content-between">';
                    listItem += '<div class="list-item d-flex align-items-center noti-item">';
                    listItem += '<div class="me-1"><div class="avatar ' + (data[i].status === 'read' ? 'bg-light-success' : 'bg-light-warning') + '">';
                    listItem += '<div class="avatar-content"><i class="avatar-icon" data-feather="' + icon + '"></i></div>';
                    listItem += '</div></div>';
                    listItem += '<div class="list-item-body flex-grow-1">';
                    listItem += '<p class="media-heading"><span class="fw-bolder">';
                    listItem += ucwords(data[i].notification_type);
                    listItem += '</p></span>';
                    listItem += '<p class="notification-text mb-0">' + data[i].notification_text + '</p>';
                    listItem += '<p class="notification-text"> Email: ' + data[i].email + '</p>';
                    listItem += '</div></div>';
                    listItem += '<div class="d-flex align-items-center"><a href="' + data[i].location_url + '" class="btn btn-sm btn-outline-warning">View</a></div>';
                    listItem += '</div></li>';


                    $('#notificationsList').append(listItem);
                }
            }
        });

        function ucwords(str) {
            return str.replace(/\b\w/g, function(match) {
                return match.toUpperCase();
            });
        }
    });
</script>
@stop