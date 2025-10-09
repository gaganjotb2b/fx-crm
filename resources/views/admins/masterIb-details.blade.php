@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Master IB Details')
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
                        <h2 class="content-header-title float-start mb-0">Master IB</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">{{__('finance.home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{__('page.reports')}}</a>
                                </li>
                                <li class="breadcrumb-item active">IB Details
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
                        <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="app-todo.html"><i class="me-1" data-feather="check-square"></i><span class="align-middle">Todo</span></a><a class="dropdown-item" href="app-chat.html"><i class="me-1" data-feather="message-square"></i><span class="align-middle">Chat</span></a><a class="dropdown-item" href="app-email.html"><i class="me-1" data-feather="mail"></i><span class="align-middle">Email</span></a><a class="dropdown-item" href="app-calendar.html"><i class="me-1" data-feather="calendar"></i><span class="align-middle">Calendar</span></a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Vertical Left Tabs start -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">IB Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="nav-vertical">
                            <ul class="nav nav-tabs nav-left flex-column" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="baseVerticalLeft-tab1" data-bs-toggle="tab" aria-controls="tabVerticalLeft1" href="#tabVerticalLeft1" role="tab" aria-selected="true">Overview</a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a class="nav-link" id="baseVerticalLeft-tab2" data-bs-toggle="tab" aria-controls="tabVerticalLeft2" href="#tabVerticalLeft2" role="tab" aria-selected="false">Client Deposits</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="baseVerticalLeft-tab3" data-bs-toggle="tab" aria-controls="tabVerticalLeft3" href="#tabVerticalLeft3" role="tab" aria-selected="false">Client Withdraws
                                    </a>
                                </li> --}}
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active overview" id="tabVerticalLeft1" role="tabpanel" aria-labelledby="baseVerticalLeft-tab1">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="card" style="border: 1px solid #ddd;">
                                                <div class="card-header d-flex">
                                                    <h5 id="user-name-top" class="text-capitalize">IB User</h5>
                                                </div>
                                                <hr>
                                                <div class="card-body">
                                                    <div class="rounded ms-1 dt-trader-img img-finance">
                                                        <div class="h-100">
                                                            <img class="img img-fluid" src="{{ asset('admin-assets/app-assets/images/avatars/' . $avatar) }}" alt="avatar">
                                                        </div>
                                                    </div>
                                                    @foreach($user_info as $info)
                                                    <ul class="list-group list-group-flush">
                                                        <!-- Name -->
                                                        <li class="list-group-item d-flex align-items-center">
                                                            <span>{{__('page.name')}} </span>
                                                            <span class="text-dark ms-auto" id="name">{{ $info->name }}</span>
                                                        </li>
                                                        <!-- Address -->
                                                        <li class="list-group-item d-flex align-items-center">
                                                            <span>{{__('page.country')}}</span>
                                                            <span class="text-dark ms-auto" id="address">{{ $country_name->name }}</span>
                                                        </li>
                                                        <!-- Zip Code -->
                                                        <li class="list-group-item d-flex align-items-center" id="zip-code-list">
                                                            <span>{{__('page.phone')}}</span>
                                                            <span class="text-dark ms-auto" id="zip-code">{{ $info->phone }}</span>
                                                        </li>
                                                        <!-- City -->
                                                        <li class="list-group-item d-flex align-items-center">
                                                            <span>{{__('page.email')}}</span>
                                                            <span class="text-dark ms-auto" id="city">{{ $info->email  }}</span>
                                                        </li>
                                                        <!-- State -->
                                                    </ul>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <!--Affiliate information-->
                                        <div class="col-7">
                                            <div class="card" style="border: 1px solid #ddd;">
                                                <div class="card-header d-flex">
                                                    <h4>Affiliate Information</h4>
                                                </div>
                                                <hr>
                                                <div class="card-body">
                                                    <ul class="list-group list-group-flush">
                                                        <!-- Name -->
                                                        <li class="list-group-item d-flex align-items-center">
                                                            <span>Total Affiliate Clients :</span>
                                                            <span class="text-dark ms-auto" id="total_clients">{{ $total_ib }}</span>
                                                        </li>
                                                        <!-- Address -->
                                                        <li class="list-group-item d-flex align-items-center">
                                                            <span>Total Affiliate IB's :</span>
                                                            <span class="text-dark ms-auto" id="total_ibs">{{ $total_total_affiliate_traders }}</span>
                                                        </li>
                                                        <!-- Zip Code -->
                                                        <li class="list-group-item d-flex align-items-center" id="zip-code-list">
                                                            <span>Affiliate Link For IB :</span>
                                                            <span class="text-dark ms-auto" id="ib_link">{{$ib_link}}</span>
                                                        </li>
                                                        <!-- City -->
                                                        <li class="list-group-item d-flex align-items-center">
                                                            <span>Affiliate Link For Trader :</span>
                                                            <span class="text-dark ms-auto" id="trader_link">{{$trader_link}}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabVerticalLeft2" role="tabpanel" aria-labelledby="baseVerticalLeft-tab2">

                                </div>
                                <div class="tab-pane" id="tabVerticalLeft3" role="tabpanel" aria-labelledby="baseVerticalLeft-tab3">
                                    <p>
                                        Icing croissant powder jelly bonbon cake marzipan fruitcake. Tootsie roll marzipan tart marshmallow
                                        pastry cupcake chupa chups cookie. Fruitcake dessert lollipop pudding jelly. Cookie dragée jujubes
                                        croissant lemon drops cotton candy. Carrot cake candy canes powder donut toffee cookie.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Vertical Left Tabs ends -->
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
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
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
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>


<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@stop
<!-- END: page vendor js -->

<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
<script>

</script>

@stop
<!-- BEGIN: page JS -->