@extends('layouts.admin-auth')
@section('title','Admin Registration')

<!-- BEGIN: Vendor CSS-->
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@stop
<!-- END: Vendor CSS-->

<!-- BEGIN: Page CSS-->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-pickadate.css') }}">
@section('page-css')
<!-- END: Page CSS-->

@section('content')
<div class="auth-wrapper auth-cover">
    <div class="auth-inner row m-0">
        <!-- Brand logo-->
        <a class="brand-logo" href="{{route('admin.login')}}">
            <img src="{{ asset('uploads/logos/icon.png') }}" height="28" alt="brand-logo">
            <h2 class="brand-text text-primary ms-1 text-uppercase">{{ config('app.name') }}</h2>
        </a>
        <!-- /Brand logo-->

        <!-- Left Text-->
        <div class="col-lg-3 d-none d-lg-flex align-items-center p-0">
            <div class="w-100 d-lg-flex align-items-center justify-content-center">
                <img class="img-fluid w-100" src="{{ asset('admin-assets/app-assets/images/illustration/create-account.svg') }}" alt="multi-steps" />
            </div>
        </div>
        <!-- /Left Text-->

        <!-- Register-->
        <div class="col-lg-9 d-flex align-items-center auth-bg px-2 px-sm-3 px-lg-5 pt-3 pb-5" style="height: fit-content; min-height: 100vh;">
            <div class="width-700 mx-auto">
                <div class="bs-stepper register-multi-steps-wizard shadow-none">
                    <div class="bs-stepper-header px-0" role="tablist">
                        <div class="step" data-target="#account-details" role="tab">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="home" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">{{__('page.account')}}</span>
                                    <span class="bs-stepper-subtitle">{{__('page.fill_details')}}</span>
                                </span>
                            </button>
                        </div>
                        <div class="line">
                            <i data-feather="chevron-right" class="font-medium-2"></i>
                        </div>
                        <div class="step" data-target="#profile" role="tab">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="user" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">{{__('page.profile')}}</span>
                                    <span class="bs-stepper-subtitle">{{__('page.fill_details')}}</span>
                                </span>
                            </button>
                        </div>
                        <div class="line">
                            <i data-feather="chevron-right" class="font-medium-2"></i>
                        </div>
                        <div class="step" data-target="#confirm" role="tab">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="credit-card" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">{{__('page.confirm')}}</span>
                                    <span class="bs-stepper-subtitle">{{__('page.confirm_details')}}</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content px-0 mt-4">
                        <div id="account-details" class="content" role="tabpanel" aria-labelledby="account-details-trigger">
                            <div class="content-header mb-2">
                                <h2 class="fw-bolder mb-75">{{__('page.account_info')}}</h2>
                                <span> {{__('page.enter_your_username_password_details')}}</span>
                            </div>
                            <form action="{{ route('admin.registration') }}" id="account_detail_form" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="name">{{__('page.full-name')}}</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="johndoe" />
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="phone">{{__('page.phone-number')}}</label>
                                        <input type="text" name="phone" id="phone" class="form-control mobile-number-mask" placeholder="(472) 765-3654" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="email">{{__('page.email')}}</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="john.doe@email.com" aria-label="john.doe" />
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="confirm_email">{{__('page.confirm')}} {{__('page.email')}}</label>
                                        <input type="email" name="confirm_email" id="confirm_email" class="form-control" placeholder="john.doe@email.com" aria-label="john.doe" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="gender">{{__('page.gender')}}</label>
                                        <select class="select2 w-100" name="gender" id="gender">
                                            <option value="" label="blank"></option>
                                            <option value="Male">{{__('page.male')}}</option>
                                            <option value="Female">{{__('page.female')}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="country">{{__('page.country')}}</label>
                                        <select class="select2 w-100" name="country" id="country">
                                            <option value="" label="blank"></option>
                                            <option value="BAN">Bangladesh</option>
                                            <option value="PAK">Pakistan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="address">{{__('page.address')}}</label>
                                        <input type="text" name="address" id="address" class="form-control" placeholder="" />
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="city">{{__('page.city')}}</label>
                                        <input type="text" name="city" id="city" class="form-control" placeholder="" />
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="state">{{__('page.state')}}</label>
                                        <input type="text" name="state" id="state" class="form-control" placeholder="" />
                                    </div>
                                    <div class="col-md-2 mb-1">
                                        <label class="form-label" for="zip_code">{{__('page.zip-code')}}</label>
                                        <input type="text" name="zip_code" id="zip_code" class="form-control" placeholder="- - - -" />
                                    </div>
                                    <div class="col-md-4 mb-1">
                                        <label class="form-label" for="dob">{{__('finance.Date of Birth')}}</label>
                                        <input type="text" id="dob" name="dob" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="multiStepsRememberMe" />
                                            <label class="form-check-label" for="multiStepsRememberMe">{{__('page.remember_me')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="d-flex justify-content-between mt-2">
                                <button class="btn btn-outline-secondary btn-prev" disabled>
                                    <i data-feather="chevron-left" class="align-middle me-sm-25 me-0"></i>
                                    <span class="align-middle d-sm-inline-block d-none">{{__('page.previous')}}</span>
                                </button>
                                <button class="btn btn-primary btn-next">
                                    <span class="align-middle d-sm-inline-block d-none">{{__('page.next')}}</span>
                                    <i data-feather="chevron-right" class="align-middle ms-sm-25 ms-0"></i>
                                </button>
                            </div>
                        </div>
                        <div id="profile" class="content" role="tabpanel" aria-labelledby="profile-trigger">
                            <div class="content-header mb-2">
                                <h2 class="fw-bolder mb-75">{{__('page.profile-information')}}</h2>
                                <span>{{__('page.enter_your_profile')}}</span>
                            </div>
                            <form action="{{ route('admin.registration') }}" id="profile_detail_form" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="mb-1 col-md-6">
                                        <label class="form-label" for="approx_investment">{{__('page.approximate_investmen')}}t</label>
                                        <input type="text" name="approx_investment" id="approx_investment" class="form-control" placeholder="Ex:2" />
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="platform">{{__('page.platform')}}</label>
                                        <select class="select2 w-100" name="platform" id="platform">
                                            <option value="">{{__('page.choose_platform')}}</option>
                                            <option value="MT4">MT4</option>
                                            <option value="MT5">MT5</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="acc_type">{{__('page.account-type')}}</label>
                                        <select class="select2 w-100" name="acc_type" id="acc_type">
                                            <option value="">{{__('page.choose-an-account-type')}}</option>
                                            <option value="Demo">{{__('page.demo-account')}}</option>
                                            <option value="Live">Live{{__('page.live-account')}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="leverage">{{__('page.leverage')}}</label>
                                        <select class="select2 w-100" name="leverage" id="leverage">
                                            <option value="">{{__('page.choose-a-leverage')}}</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="est_net_income">{{__('page.estimated_annual_income')}}</label>
                                        <select class="select2 w-100" name="est_net_income" id="est_net_income">
                                            <option value="UP TO 25,000 USD"> Up to 25,000 USD </option>
                                            <option value="25,000 TO 50,000 USD"> 25,000 to 50,000 USD </option>
                                            <option value="OVER 50,000 USD"> Over 50,000 USD</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-1 d-none"></div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="est_net_worth">{{__('page.estimated_annual_Worth')}}</label>
                                        <select class="select2 w-100" name="est_net_worth" id="est_net_worth">
                                            <option value="UP TO 25,000 USD"> Up to 25,000 USD </option>
                                            <option value="25,000 TO 50,000 USD"> 25,000 to 50,000 USD </option>
                                            <option value="OVER 50,000 USD"> Over 50,000 USD</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="emp_info">{{__('page.employment_information')}}</label>
                                        <select class="select2 w-100" name="emp_info" id="emp_info">
                                            <option value="Employed">{{__('page.employed')}}</option>
                                            <option value="Unemployed">{{__('page.unemployed')}}</option>
                                            <option value="Self Employed">{{__('page.self_employed')}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="nob">{{__('page.nature_Of_Businesses')}}</label>
                                        <select class="select2 w-100" name="nob" id="nob">
                                            <option value="Accountancy">{{__('page.accountancy')}}</option>
                                            <option value="Administrative">{{__('page.administrative')}}</option>
                                            <option value="Agriculture">{{__('page.agriculture')}}</option>
                                            <option value="Bank & Finance">{{__('page.bank_&_Finance')}}</option>
                                            <option value="Education">{{__('page.education')}}</option>
                                            <option value="Engineering">{{__('page.engineering')}}</option>
                                            <option value="Government">{{__('page.government')}}</option>
                                            <option value="Health">{{__('page.health')}}</option>
                                            <option value="HR">{{__('page.HR')}}</option>
                                            <option value="Legal">{{__('page.legal')}}</option>
                                            <option value="Manufacturing">{{__('page.manufacturing')}}</option>
                                            <option value="Marketing & Sales">{{__('page.marketing_&_Sales')}}</option>
                                            <option value="Real Estate">{{__('page.real_Estate')}}</option>
                                            <option value="Transport">{{__('page.transport')}}</option>
                                            <option value="Telecommunication">{{__('page.telecommunication')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </form>

                            <div class="d-flex justify-content-between mt-2">
                                <button class="btn btn-primary btn-prev">
                                    <i data-feather="chevron-left" class="align-middle me-sm-25 me-0"></i>
                                    <span class="align-middle d-sm-inline-block d-none">{{__('page.previous')}}</span>
                                </button>
                                <button class="btn btn-primary btn-next">
                                    <span class="align-middle d-sm-inline-block d-none">{{__('page.next')}}</span>
                                    <i data-feather="chevron-right" class="align-middle ms-sm-25 ms-0"></i>
                                </button>
                            </div>
                        </div>
                        <div id="confirm" class="content" role="tabpanel" aria-labelledby="confirm-trigger">
                            <div class="content-header mb-2">
                                <h2 class="fw-bolder mb-75">{{__('page.select_plan')}}</h2>
                                <span>{{__('page.select_plan_as_per_your_retirement')}}</span>
                            </div>

                            <form action="{{ route('admin.registration') }}" id="confirm_detail_form" enctype="multipart/form-data">
                                @csrf
                                <div class="row gx-2">
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="password">{{__('page.password')}}</label>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input type="password" name="password" id="password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label" for="confirm_password">{{__('page.confirm_password')}}</label>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required />
                                            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="d-flex justify-content-between mt-1">
                                <button class="btn btn-primary btn-prev">
                                    <i data-feather="chevron-left" class="align-middle me-sm-25 me-0"></i>
                                    <span class="align-middle d-sm-inline-block d-none">{{__('page.previous')}}</span>
                                </button>
                                <button class="btn btn-success btn-submit">
                                    <i data-feather="check" class="align-middle me-sm-25 me-0"></i>
                                    <span class="align-middle d-sm-inline-block d-none">{{__('page.submit')}}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
<!-- BEGIN: Page Vendor JS-->
@section('page-vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@stop
<!-- END: Page Vendor JS-->

@section('page-js')
<!-- BEGIN: Page JS-->
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/admin-registration.js') }}"></script>
<!-- <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/auth-register.js') }}"></script> -->
<!-- END: Page JS-->

<script>
    (function(window, document, $) {
        // 1st step: account information
        $(document).on('submit', '#account_detail_form', function(event) {
            $(this).prop('disabled', true);
            let form_data = new FormData(this);
            event.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '/admin/registration/account?op=account',
                dataType: 'json',
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    if (data.status == false) {
                        var error;
                        if (data.errors.hasOwnProperty('name')) {
                            error = data.errors.name;
                        }
                        if (data.errors.hasOwnProperty('phone')) {
                            error = data.errors.phone;
                        }
                        if (data.errors.hasOwnProperty('email')) {
                            error = data.errors.email;
                        }
                        toastr['error'](error, "Registraion Validation", {
                            showMethod: 'slideDown',
                            hideMethod: 'slideUp',
                            closeButton: true,
                            tapToDismiss: false,
                            progressBar: true,
                            timeOut: 2000,
                            extendedTimeOut: 0,
                        });
                    }
                }
            });
        });
        // 2nd step: profile information
        $(document).on('submit', '#profile_detail_form', function(event) {
            $(this).prop('disabled', true);
            let form_data = new FormData(this);
            event.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '/admin/registration/account?op=profile',
                dataType: 'json',
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    if (data.status == false) {
                        var error;
                        if (data.errors.hasOwnProperty('platform')) {
                            error = data.errors.platform;
                        }
                        if (data.errors.hasOwnProperty('acc_type')) {
                            error = data.errors.acc_type;
                        }
                        toastr['error'](error, "Registraion Validation", {
                            showMethod: 'slideDown',
                            hideMethod: 'slideUp',
                            closeButton: true,
                            tapToDismiss: false,
                            progressBar: true,
                            timeOut: 2000,
                            extendedTimeOut: 0,
                        });
                    }
                }
            });
        });
        // 3rd step : confirm details
        $(document).on('submit', '#confirm_detail_form', function(event) {
            $(this).prop('disabled', true);
            let form_data = new FormData(this);
            event.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '/admin/registration/account?op=confirm',
                dataType: 'json',
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    if (data.status == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Registration Completed',
                            html: data.message,
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        setTimeout(function() {
                            window.location.href = "/admin";
                        }, 1000 * 2);
                    } else {
                        var error;
                        if (data.errors.hasOwnProperty('password')) {
                            error = data.errors.password;
                        }
                        if (data.errors.hasOwnProperty('password_confirmation')) {
                            error = data.errors.password_confirmation;
                        }
                        toastr['error'](error, "Registraion Validation", {
                            showMethod: 'slideDown',
                            hideMethod: 'slideUp',
                            closeButton: true,
                            tapToDismiss: false,
                            progressBar: true,
                            timeOut: 2000,
                            extendedTimeOut: 0,
                        });
                    }
                }
            });
        });
    })(window, document, jQuery);
</script>
@stop