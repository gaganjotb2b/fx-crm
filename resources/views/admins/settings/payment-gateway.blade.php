@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Payment Gateways Configuration')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css')}}">
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
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Settings</a>
                                </li>
                                <li class="breadcrumb-item active">Gateways
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
            <!-- Vertical Wizard -->
            <section class="vertical-wizard">
                <div class="bs-stepper vertical vertical-wizard-example">
                    <div class="bs-stepper-header">
                        <div class="step" data-target="#account-details-vertical" role="tab" id="account-details-vertical-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">1</span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Help2Pay</span>
                                    <span class="bs-stepper-subtitle">Help2Pay Configuration</span>
                                </span>
                            </button>
                        </div>
                        <div class="step" data-target="#personal-info-vertical" role="tab" id="personal-info-vertical-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">2</span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Praxis</span>
                                    <span class="bs-stepper-subtitle">Praxis Configuration</span>
                                </span>
                            </button>
                        </div>
                        <div class="step" data-target="#address-step-vertical" role="tab" id="address-step-vertical-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">3</span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">B2BinPay</span>
                                    <span class="bs-stepper-subtitle">B2BinPay Configuration</span>
                                </span>
                            </button>
                        </div>
                        <div class="step" data-target="#social-links-vertical" role="tab" id="social-links-vertical-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">4</span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Paypal</span>
                                    <span class="bs-stepper-subtitle">Paypal Configuration</span>
                                </span>
                            </button>
                        </div>
                        <!-- now pay deposit -->
                        <div class="step" data-target="#now-payments" role="tab" id="now-payments-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">5</span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">NOWPayments</span>
                                    <span class="bs-stepper-subtitle">NOWPayments Configuration</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <!-- Help2pay configuration -->
                        <form id="account-details-vertical" class="content" role="tabpanel" action="{{route('admin.settings.paymentgateway.help2pay')}}" method="post" aria-labelledby="account-details-vertical-trigger">
                            @csrf
                            <div class="content-header">
                                <h5 class="mb-0">Help2Pay Configuration</h5>
                                <small class="text-muted">All fields are required</small>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6 mx-auto">
                                    <h4 class="card-title"><span class="text-muted">Fillup and submit this form</span></h4>
                                    <!-- Merchant -->
                                    <div class="mb-1 col-md-12">
                                        <label class="form-label" for="merchant-help2pay">Merchant</label>
                                        <input type="text" id="merchant-help2pay" class="form-control" name="merchant" autocomplete="off" placeholder="D0306" value="{{($help2pay)?$help2pay->merchent_code:''}}" />
                                    </div>
                                    <!-- SecurityCode  -->
                                    <div class="mb-1 col-md-12">
                                        <label class="form-label" for="security-code-help2pay">Security Code </label>
                                        <input type="text" id="security-code-help2pay" class="form-control" name="security_code" autocomplete="off" placeholder="eQIkGnUeK" value="{{($help2pay)?$help2pay->api_secret:''}}" />
                                    </div>
                                </div>
                            </div>
                            <!-- submit help2pay configuration file -->
                            <div class="row">
                                <div class="col-md-6 mx-auto">
                                    <button type="button" class="btn btn-primary float-end" onclick="_run(this)" data-el="fg" data-form="account-details-vertical" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="help2pay_callback" data-btnid="btn-save-help2pay" id="btn-save-help2pay">Save Configuration</button>
                                </div>
                            </div>
                        </form>
                        <!-- praxis configuration -->
                        <form id="personal-info-vertical" action="{{route('admin.settings.paymentgateway.praxis')}}" method="post" class="content" role="tabpanel" aria-labelledby="personal-info-vertical-trigger">
                            @csrf
                            <div class="content-header">
                                <h5 class="mb-0">Praxis Configuration</h5>
                                <small>All field are required</small>
                            </div>
                            <hr>
                            <div class="row">
                                <!-- merchant ID -->
                                <div class="mb-1 col-md-6 mx-auto">
                                    <h4 class="card-title"><span class="text-muted">Fillup and submit this form</span></h4>
                                    <label class="form-label" for="praxis-merchant-id">Merchant ID</label>
                                    <input type="text" id="praxis-merchant-id" name="merchant_id" value="{{($praxis)?$praxis->merchent_code:''}}" class="form-control" placeholder="API-praxis" />
                                </div>
                            </div>
                            <div class="row">
                                <!-- merchant secret -->
                                <div class="mb-1 col-md-6 mx-auto">
                                    <label class="form-label" for="praxis-secret">Merchant Secret</label>
                                    <input type="text" id="praxis-secret" name="merchant_secret" value="{{($praxis)?$praxis->api_secret:''}}" class="form-control" placeholder="Xsldkdkdkdklsdkfkdeoowowp12" />
                                </div>
                            </div>
                            <div class="row">
                                <!-- application key/ praxis -->
                                <div class="mb-1 col-md-6 mx-auto">
                                    <label class="form-label" for="praxis-app-key">Application Key</label>
                                    <input type="text" id="praxis-app-key" name="application_key" value="{{($praxis)?$praxis->api_token:''}}" class="form-control" placeholder="{{config('app.name')}}" />
                                </div>
                            </div>
                            <!-- submit praxis subtton -->
                            <div class="row">
                                <div class="col-md-6 mx-auto">
                                    <button type="button" class="btn btn-primary float-end" onclick="_run(this)" data-el="fg" data-form="personal-info-vertical" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="praxis_callback" data-btnid="btn-save-praxis" id="btn-save-praxis">Save Configuration</button>
                                </div>
                            </div>
                        </form>
                        <!-- b2b in pay configuration form -->
                        <form id="address-step-vertical" action="{{route('admin.settings.paymentgateway.b2binpay')}}" method="post" class="content" role="tabpanel" aria-labelledby="address-step-vertical-trigger">
                            @csrf
                            <div class="content-header">
                                <h5 class="mb-0">B2BinPay</h5>
                                <small>B2BinPay Configuration</small>
                            </div>
                            <hr>
                            <div class="row">
                                <!-- b2b walelt ID -->
                                <div class="mb-1 mx-auto col-md-6">
                                    <h4 class="card-title"><span class="text-muted">Fillup and submit this form</span></h4>
                                    <label class="form-label" for="b2binpay-wallet-id">Wallet ID</label>
                                    <input type="text" name="wallet_id" value="{{($b2binpay)?$b2binpay->merchent_code:''}}" id="b2binpay-wallet-id" class="form-control" placeholder="1552" />
                                </div>
                            </div>
                            <div class="row">
                                <!-- b2b login -->
                                <div class="mb-1 mx-auto col-md-6">
                                    <label class="form-label" for="b2binpay-login">Login</label>
                                    <input type="text" name="login" id="b2binpay-login" value="{{($b2binpay)?$b2binpay->user_name:''}}" class="form-control" placeholder="sKdybdsdfs0t4RnNyuh" />
                                </div>
                            </div>
                            <div class="row">
                                <!-- b2b password -->
                                <div class="mb-1 col-md-6 mx-auto">
                                    <label class="form-label" for="b2binpay-password">Password</label>
                                    <input type="password" name="password" value="{{($b2binpay)?$b2binpay->password:''}}" id="b2binpay-password" class="form-control" />
                                </div>
                            </div>
                            <!-- submit b2binpay subtton -->
                            <div class="row">
                                <div class="col-md-6 mx-auto">
                                    <button type="button" class="btn btn-primary float-end" onclick="_run(this)" data-el="fg" data-form="address-step-vertical" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="b2binpay_callback" data-btnid="btn-save-b2binpay" id="btn-save-b2binpay">Save Configuration</button>
                                </div>
                            </div>
                        </form>
                        <!-- paypal configuration -->
                        <form id="social-links-vertical" action="{{route('admin.settings.paymentgateway.paypal')}}" method="post" class="content" role="tabpanel" aria-labelledby="social-links-vertical-trigger">
                            @csrf
                            <div class="content-header">
                                <h5 class="mb-0">Paypal configuration</h5>
                                <small>All fileds are required</small>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="mb-1 col-md-6 mx-auto">
                                    <h4 class="card-title"><span class="text-muted">Fillup and submit this form</span></h4>
                                    <label class="form-label" for="vertical-google">Mode</label>
                                    <select name="mode" id="api_type" class="select form-select select2">
                                        <option value="sandbox" {{($paypal && $paypal->mode === 'sandbox')?'selected':''}}>Sandbox</option>
                                        <option value="live" {{($paypal && $paypal->mode === 'live')?'selected':''}}>Live</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <!-- client id -->
                                <div class="mb-1 mx-auto col-md-6">
                                    <label class="form-label" for="paypal-client-id">Client ID</label>
                                    <input type="text" id="paypal-client-id" name="client_id" value="{{($paypal)?$paypal->api_token:''}}" class="form-control" placeholder="djskjfjksdfjkds-dfsdkfkdl-dkslfkdk" />
                                </div>
                            </div>
                            <div class="row">
                                <!-- client secret -->
                                <div class="mb-1 col-md-6 mx-auto">
                                    <label class="form-label" for="paypal-client-secret">Client Secret</label>
                                    <input type="text" id="paypal-client-secret" name="client_secret" value="{{($paypal)?$paypal->api_secret:''}}" class="form-control" placeholder="dkdkkf-dkdkdk-dkdkdk" />
                                </div>
                            </div>
                            <!-- submit buttons -->
                            <div class="row">
                                <div class="col-md-6 mx-auto">
                                    <button type="button" class="btn btn-primary float-end" onclick="_run(this)" data-el="fg" data-form="social-links-vertical" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="paypal_callback" data-btnid="btn-save-paypal" id="btn-save-paypal">Save Configuration</button>
                                </div>
                            </div>
                        </form>
                        <!-- now payments configuration -->
                        <form id="now-payments" action="{{route('admin.settings.paymentgateway.now-pay')}}" method="post" class="content" role="tabpanel" aria-labelledby="now-payments-trigger">
                            @csrf
                            <div class="content-header">
                                <h5 class="mb-0">NOWPayments configuration</h5>
                                <small>All fileds are required</small>
                            </div>
                            <hr>
                            <div class="row">
                                <!-- client id -->
                                <div class="mb-1 mx-auto col-md-6">
                                    <label class="form-label" for="now-pay-api-key">API Key</label>
                                    <input type="text" id="now-pay-api-key" name="api_key" value="{{($nowpay)?$nowpay->api_token:''}}" class="form-control" placeholder="djskjfjksdfjkds-dfsdkfkdl-dkslfkdk" />
                                </div>
                            </div>
                            <div class="row">
                                <!-- client secret -->
                                <div class="mb-1 col-md-6 mx-auto">
                                    <label class="form-label" for="now-pay-ipn-secret">IPN Secret</label>
                                    <input type="text" id="now-pay-ipn-secret" name="ipn_secret" value="{{($nowpay)?$nowpay->api_secret:''}}" class="form-control" placeholder="dkdkkf-dkdkdk-dkdkdk" />
                                </div>
                            </div>
                            <div class="row">
                                <!-- client secret -->
                                <div class="mb-1 col-md-6 mx-auto">
                                    <label class="form-label" for="now-pay-ipn-secret">IPN URL</label>
                                    <input type="text" id="now-pay-ipn-url" name="ipn_url" value="{{url('api/nowpay/payment/notification')}}" class="form-control" placeholder="{{url('api/nowpay/payment/notification')}}" />
                                </div>
                            </div>
                            <!-- submit buttons -->
                            <div class="row">
                                <div class="col-md-6 mx-auto">
                                    <button type="button" class="btn btn-primary float-end" onclick="_run(this)" data-el="fg" data-form="now-payments" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="now_payments" data-btnid="btn-save-nowpay" id="btn-save-nowpay">Save Configuration</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            <!-- /Vertical Wizard -->
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
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- toaster js -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js')}}"></script>
<!-- form hide/show -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-config-form.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js')}}"></script>
<script>
    function help2pay_callback(data) {
        if (data.status) {
            notify('success', data.message, 'Help2Pay Configuration');
        } else {
            notify('error', data.message, 'Help2Pay Configuration');
        }
    }

    function b2binpay_callback(data) {
        if (data.status) {
            notify('success', data.message, 'B2BinPay Configuration');
        } else {
            notify('error', data.message, 'B2BinPay Configuration');
        }
    }

    function paypal_callback(data) {
        if (data.status) {
            notify('success', data.message, 'paypal Configuration');
        } else {
            notify('error', data.message, 'paypal Configuration');
        }
        $.validator("social-links-vertical", data.errors);
    }

    function praxis_callback(data) {
        if (data.status) {
            notify('success', data.message, 'Praxis Configuration');
        } else {
            notify('error', data.message, 'Praxis Configuration');
        }
        $.validator("personal-info-vertical", data.errors);
    }
    // now payments configuration
    function now_payments(data) {
        if (data.status) {
            notify('success', data.message, 'NOWPayments Configuration');
        } else {
            notify('error', data.message, 'NOWPayments Configuration');
        }
        $.validator("now-payments", data.errors);
    }
</script>
@stop
<!-- BEGIN: page JS -->