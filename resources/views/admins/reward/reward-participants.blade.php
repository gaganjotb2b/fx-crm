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
    td,
    th {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    @media screen and (max-width: 1280px) and (min-width: 800px) {

        .ib-withdraw thead tr th:nth-child(4),
        .ib-withdraw tbody tr td:nth-child(4) {
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
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Contest Participant</h2>
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
            <!-- Subscribers Chart Card starts -->
            <section id="dashboard-ecommerce">
                <div class="row match-height">
                    <div class="card">
                        <div class="card-body">
                            <h3>Filter Report</h3>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label for="filter-contest-name">Contest name</label>
                                    <input name="contest_name" id="filter-contest-name" class="form-input form-control" placeholder="Contest name">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <!--  -->
                                    <label for="filter-rank">Rank</label>
                                    <select name="rank" id="filter-rank" class="form-select form-control">
                                        <option value="">All</option>
                                        <option value="1st">1st</option>
                                        <option value="2nd">2nd</option>
                                        <option value="3rd">3rd</option>
                                        <option value="4th">4th</option>
                                        <option value="5th">5th</option>
                                        <option value="6th">6th</option>
                                        <option value="7th">7th</option>
                                        <option value="8th">8th</option>
                                        <option value="9th">9th</option>
                                        <option value="10th">10th</option>
                                    </select>
                                </div>
                                <!-- filter by account nuber -->
                                <div class="col-md-4 mb-2">
                                    <label for="account">Account</label>
                                    <input type="text" name="account" placeholder="Account number" class="form-input form-control" id="filter-account">
                                </div>
                                <!-- filter by client name / email -->
                                <div class="col-md-4 mb-2">
                                    <label for="name-email">Client name/email</label>
                                    <input type="text" name="name_email" class="form-control form-input" id="filter-name-email" placeholder="Name/Email">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="filter-date">Joining date</label>
                                    <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Create Date To Filter" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
                                        <span class="input-group-text">
                                            <div class="icon-wrapper">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                                </svg>
                                            </div>
                                        </span>
                                        <input id="date_from" type="text" name="from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                        <span class="input-group-text">to</span>
                                        <input id="date_to" type="text" name="to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                    </div>
                                </div>
                                <!-- filter reset button -->
                                <div class="col-md-2 mt-2">
                                    <button id="btn-reset" type="button" class="btn btn-danger w-100 waves-effect waves-float waves-light">
                                        <span class="align-middle">{{__('ad-reports.btn-reset')}}</span>
                                    </button>
                                </div>
                                <!-- filter button -->
                                <div class="col-md-2 mt-2">
                                    <button id="btn-filter" type="button" class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                        <span class="align-middle">{{__('category.FILTER')}}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table class="table" id="reward-particpant">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Total participant</th>
                                        <th>Progress</th>
                                        <th>Reward Name</th>
                                        <th>Joining Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Subscribers Chart Card ends -->
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // var dt = $('#particpant').fetch_data({
    //     "url": "/admin/reward/participants/report/list",
    //     "columns": [{
    //            "data": "name"
    //        },
    //        {
    //            "data": "email"
    //        },
    //        // {
    //        //     "data": "rank"
    //        // },
    //        // {
    //        //     "data": "account"
    //        // },
    //        {
    //            "data": "total_joined"
    //        },
    //        // {
    //        //     "data": "loss_profit"
    //        // },
    //        // {
    //        //     "data": "total_deposit"
    //        // },
    //        {
    //            "data": "progress"
    //        },
    //        {
    //            "data": "reward_name"
    //        },
    //        {
    //            "data": "join_date"
    //        }

    //     ],
    //     customorder: 8
    // })
    // $(document).fetch_description({
    //     url: '/admin/reward/participants/report/list',
    //     feather: true,
    // });
    // // credit contest
    // $(document).on('click', '.btn-credit-contest', function() {
    //     let contest_id = $(this).data('contest_id');
    //     let user_id = $(this).data('user_id');
    //     $(this).confirm2({
    //         request_url: '/admin/contest/credit',
    //         method: 'POST',
    //         data: {
    //             contest_id: contest_id,
    //             user_id: user_id,
    //         },
    //         click: false,
    //         title: 'Credit contest bonus',
    //         message: 'Are you confirm to credit balance to this contest account?',
    //         button_text: 'Confirm',
    //         // notification:true,
    //     }, function(data) {
    //         if (data.status == true) {
    //             notify('success', data.message, 'Credit add');
    //         } else {
    //             notify('error', data.message, 'Credit add');
    //         }
    //     });
    // });


    $(document).ready(function () {
    let table = $('#reward-particpant').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/reward/participants/report/list',
            type: 'GET',
        },
        columns: [
            { data: 'user_name' },
            { data: 'user_email' },
            { data: 'total_join'},
            { data: 'progress' },
            { data: 'reward_name'},
            { data: 'join_date' },
            { data: 'action' },
        ],
        order: [[3, 'desc']], // Order by join_date descending
        responsive: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous",
            }
        }
    });
});

    $(document).on('click', '.suspend-btn', function () {
        console.log("click")
        var id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to suspend this reward!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, suspend it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading before AJAX call
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we suspend the reward.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '/admin/trader/reward/susspend/' + id,
                    type: 'GET',
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Suspended!',
                            text: 'The reward has been suspended successfully.'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong while suspending.'
                        });
                    }
                });
            }
        });
    });

</script>
@stop
<!-- BEGIN: page JS -->