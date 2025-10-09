@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Add Voucher')
@section('vendor-css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/extensions/swiper.min.css') }}">
    <!-- datatable -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-swiper.css') }}">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/fontawesome.min.css">
        
    <style>
        .user_mail.error-msg {
        	width: 100%;
        }
        .input-group > :not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
        	margin-left: 0px;
        	border-top-left-radius: 0;
        	border-bottom-left-radius: 0;
        	padding-left: 1rem;
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
                            <h2 class="content-header-title float-start mb-0">{{__('admin-breadcrumbs.add_voucher')}}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">{{__('admin-menue-left.Offers')}}</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1 breadcrumb-right">
                        <div class="dropdown">
                            <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    data-feather="grid"></i></button>
                                    <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#"><i class="me-1" data-feather="info"></i>
                                        <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a></div>
                            </div>
                        </div>
                </div>
            <div class="content-body">
                <!-- Note cards -->
                <div class="row">
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4><b>Note</b></h4>
                                <p>Connect with Investors and receive a commission on every winning trade.</p>
                            </div>
                            <hr>
                            <div class="card-body">
                                <div class="border-start-3 border-start-primary p-1 mb-1 bg-light-info">
                                    <p>Start with just 200 USD/EUR/GBP.</p>
                                </div>
                                <div class="border-start-3 border-start-primary p-1 mb-1 bg-light-info">
                                    <p>Get up to 50% commission.</p>
                                </div>
                                <div class="border-start-3 border-start-success p-1 mb-1 bg-light-info">
                                    <p>Trade on ECN or ECN Zero conditions.</p>
                                </div>
                                <div class="border-start-3 border-start-info p-1 mb-1 bg-light-info">
                                    <p>Show your profile on the Strategy Managers Ranking page. </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(Auth::user()->hasDirectPermission('create voucher generate'))
                    <div class="col-md-8 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Generate Voucher</h4>
                            </div>
                            <div class="card-body">
                                <form id="voucher_form" action="{{ route('admin.create.voucher') }}" method="POST"
                                    class="form form-horizontal" onsubmit="return false">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="fname-icon">User Classification</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="row">
                                                        <select class="select2 form-select" id="user_classifie"  name="user_classifie">                                                          
                                                            <option value="">Select User</option>
                                                            <option value="general">General User</option>
                                                            <option value="classic">Classified User</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row" id="user_switch" style="display:none">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label"  for="fname-icon">User Type</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="row">
                                                        <select class="select2 form-select" id="user_type"  name="user_type">                                                          
                                                            <option value="">Select User</option>
                                                            <option value="0">Trader</option>
                                                            <option value="4">IB</option>
                                                            <option value="5">Manager</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12" id="trader_field" style="display:none">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="contact-icon">Trader</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <select class="max-length form-select" id="trader" multiple name="trader">
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="email-icon">Sent To Email</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i data-feather="mail"></i></span>
                                                        <input type="email" id="email" class="form-control"  name="user_mail" placeholder="Username" />                                                         
                                                        <button class="btn btn-primary" type="button" id="rstButton">Reset</button>                                                           
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="contact-icon">Amount</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                data-feather="dollar-sign"></i></span>
                                                        <input type="text" id="contact-icon" class="form-control"
                                                            name="amount" placeholder="Amount" />
                                                        <span class="input-group-text btn-primary">.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="pass-icon">Expire Date</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i data-feather="calendar"></i></span>
                                                        <input type="text" class="form-control flatpickr-basic" name="expire_date" id="expire_date" />                                                      
                                                    </div>
                                                    <span class="text-danger" id="expire_date_error"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-9 offset-sm-3">
                                            <button class="btn btn-primary me-1" type="button" role="button"
                                                id="submitBtn" onclick="_run(this)" data-form="voucher_form"
                                                data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                                data-callback="createCallBack" data-btnid="submitBtn"
                                                data-param="submit">Generate</button>
                                            <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col-md-8 col-12">
                        <div class="card">
                            <div class="card-body">
                                @include('errors.permission')
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <!--/ Form cards -->
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
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/swiper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
    <!-- datatable -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
    



    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>

@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
    <script src="{{asset('admin-assets/app-assets/js/scripts/pages/voucher-create.js')}}"> </script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/extensions/ext-component-swiper.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
    {{-- <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-validation.js') }}"></script> --}}
    <script>
       

       
    </script>
@stop
<!-- END: page JS -->
