@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Admin Registration')
@section('vendor-css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/fonts/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">

    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-pickadate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css') }}">
    <style>
        .al-input-error-fixed {
            position: relative;
        }

        .al-input-error-fixed .error-msg {
            position: absolute;
            left: auto;
            bottom: -20px;
            z-index: 11;
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
            </div>
            <div class="content-body">
                <!-- Role cards -->
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-12">

                        <div class="card">
                            <div class="card-header">
                                <h4><b>{{ __('admin-management.Note') }}</b></h4>
                            </div>
                            <hr>
                            <div class="card-body">
                                <div class="border-start-3 border-start-primary p-1 mb-1 bg-light-info">
                                    <p>{{ __('admin-management.sentence') }}</p>
                                </div>
                                <div class="border-start-3 border-start-success p-1 mb-1 bg-light-info">
                                    <p>{{ __('admin-management.sentence2') }}</p>
                                </div>

                            </div>
                        </div>
                    </div>
                    @if (Auth::user()->hasDirectPermission('create admin registration'))
                        <div class="col-xl-8 col-lg-8 col-md-12">
                            <section class="page-blockui">
                                <div class="card">
                                    <div class="card-header">
                                        <h3>{{ __('admin-management.New Admin') }} </h3>
                                    </div>
                                    <hr>
                                    <div class="card-body">
                                        <section class="modern-horizontal-wizard">
                                            <div class="bs-stepper wizard-modern modern-wizard-example">
                                                <div class="bs-stepper-header">
                                                    <!-- stepper account details -->
                                                    <div class="step" data-target="#account-details-modern" role="tab"
                                                        id="account-details-modern-trigger">
                                                        <button type="button" class="step-trigger"
                                                            id="account-details-top">
                                                            <span class="bs-stepper-box">
                                                                <i data-feather="file-text" class="font-medium-3"></i>
                                                            </span>
                                                            <span class="bs-stepper-label">
                                                                <span class="bs-stepper-title">Account Details</span>
                                                                <span class="bs-stepper-subtitle">Setup Account
                                                                    Details</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="line">
                                                        <i data-feather="chevron-right" class="font-medium-2"></i>
                                                    </div>
                                                    <!-- stepper personal info -->
                                                    <div class="step" data-target="#personal-info-modern" role="tab"
                                                        id="personal-info-modern-trigger">
                                                        <button type="button" class="step-trigger">
                                                            <span class="bs-stepper-box">
                                                                <i data-feather="user" class="font-medium-3"></i>
                                                            </span>
                                                            <span class="bs-stepper-label">
                                                                <span class="bs-stepper-title">Personal Info</span>
                                                                <span class="bs-stepper-subtitle">Add Personal Info</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="line">
                                                        <i data-feather="chevron-right" class="font-medium-2"></i>
                                                    </div>
                                                    <!-- stepper address -->
                                                    <div class="step" data-target="#address-step-modern" role="tab"
                                                        id="address-step-modern-trigger">
                                                        <button type="button" class="step-trigger">
                                                            <span class="bs-stepper-box">
                                                                <i data-feather="map-pin" class="font-medium-3"></i>
                                                            </span>
                                                            <span class="bs-stepper-label">
                                                                <span class="bs-stepper-title">Address</span>
                                                                <span class="bs-stepper-subtitle">Add Address</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="line">
                                                        <i data-feather="chevron-right" class="font-medium-2"></i>
                                                    </div>
                                                    <!-- stepper social links -->
                                                    <div class="step" data-target="#social-links-modern" role="tab"
                                                        id="social-links-modern-trigger">
                                                        <button type="button" class="step-trigger">
                                                            <span class="bs-stepper-box">
                                                                <i data-feather="link" class="font-medium-3"></i>
                                                            </span>
                                                            <span class="bs-stepper-label">
                                                                <span class="bs-stepper-title">Social Links</span>
                                                                <span class="bs-stepper-subtitle">Add Social Links</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="bs-stepper-content box-shadow-0">

                                                    <!-- content acount details -->
                                                    <form id="account-details-modern"
                                                        action="{{ route('admin.admin-store.account-details') }}"
                                                        method="post" class="content" role="tabpanel"
                                                        aria-labelledby="account-details-modern-trigger">
                                                        @csrf
                                                        <input type="hidden" name="user_id" class="user_id">
                                                        <div class="content-header">
                                                            <h5 class="mb-0">Account Details</h5>
                                                            <small class="text-muted">Enter Your Account Details.</small>
                                                        </div>
                                                        <div class="row">
                                                            <!-- group -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-group">Group</label>
                                                                <select class="select2 form-select form-control"
                                                                    name="admin_group" id="group">
                                                                    <option value="">
                                                                        {{ __('admin-management.Please Choose a Admin Group') }}
                                                                    </option>
                                                                    @foreach ($groups as $group)
                                                                        <option value="{{ $group->id }}">
                                                                            {{ $group->group_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <!-- name -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-name">Name</label>
                                                                <input type="text" id="modern-name" name="name"
                                                                    class="form-control" placeholder="Enter admin name"
                                                                    value="{{ $name }}" />
                                                            </div>
                                                            <!-- email  -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-email">Email</label>
                                                                <input type="email" id="modern-email" name="email"
                                                                    class="form-control" placeholder="john.doe@email.com"
                                                                    aria-label="john.doe" value="{{ $email }}" />
                                                            </div>

                                                        </div>
                                                        <!-- row password -->
                                                        <div class="row">
                                                            <div class="mb-1 form-password-toggle col-md-6">
                                                                <label class="form-label"
                                                                    for="modern-password">Password</label>
                                                                <div class="input-group form-password-toggle mb-2">
                                                                    <input type="password" class="form-control"
                                                                        name="password" id="modern-password"
                                                                        placeholder="Your Password"
                                                                        aria-describedby="modern-password"
                                                                        value="{{ $password }}" />
                                                                    <span class="input-group-text cursor-pointer"><i
                                                                            data-feather="eye"></i></span>
                                                                </div>
                                                            </div>
                                                            <div class="mb-1 form-password-toggle col-md-6">
                                                                <label class="form-label"
                                                                    for="modern-confirm-password">Confirm Password</label>
                                                                <input type="password" id="modern-confirm-password"
                                                                    name="confirm_password" class="form-control"
                                                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                                    value="{{ $password }}" />
                                                            </div>
                                                        </div>
                                                        <!-- has mail or not -->
                                                        <div class="row mb-2">
                                                            <div class="col-md-6">
                                                                <div class="demo-inline-spacing">
                                                                    <label for="Sending mail"> Sending Mail</label>
                                                                    <div class="form-check form-check-success">
                                                                        <input type="radio" id="customColorRadio3"
                                                                            name="sending_mail" class="form-check-input"
                                                                            value="yes" checked />
                                                                        <label class="form-check-label"
                                                                            for="customColorRadio3">Yes</label>
                                                                    </div>
                                                                    <div class="form-check form-check-danger">
                                                                        <input type="radio" id="customColorRadio5"
                                                                            name="sending_mail" class="form-check-input"
                                                                            value="no" />
                                                                        <label class="form-check-label"
                                                                            for="customColorRadio5">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="demo-inline-spacing">
                                                                    <label for="Sending mail"> Auto activate</label>
                                                                    <div class="form-check form-check-success">
                                                                        <input type="radio" id="customColorRadio4"
                                                                            name="auto_activate" class="form-check-input"
                                                                            value="yes" checked />
                                                                        <label class="form-check-label"
                                                                            for="customColorRadio4">Yes</label>
                                                                    </div>
                                                                    <div class="form-check form-check-danger">
                                                                        <input type="radio" id="customColorRadio6"
                                                                            name="auto_activate" class="form-check-input"
                                                                            value="no" />
                                                                        <label class="form-check-label"
                                                                            for="customColorRadio6">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-outline-secondary btn-prev" disabled
                                                                type="button">
                                                                <i data-feather="arrow-left"
                                                                    class="align-middle me-sm-25 me-0"></i>
                                                                <span
                                                                    class="align-middle d-sm-inline-block d-none">Previous</span>
                                                            </button>
                                                            <button class="btn btn-primary" type="button"
                                                                data-file="true" onclick="_run(this)" data-el="fg"
                                                                data-form="account-details-modern"
                                                                data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                                                data-callback="account_details_callback"
                                                                data-btnid="btn-save-account-details"
                                                                id="btn-save-account-details">
                                                                <i data-feather='save'
                                                                    class="align-middle ms-sm-25 ms-0"></i>
                                                                <span
                                                                    class="align-middle d-sm-inline-block d-none">Save</span>
                                                            </button>
                                                            <button type="button" class="btn-next d-none"
                                                                id="next-account"></button>
                                                        </div>
                                                    </form>
                                                    <form id="personal-info-modern"
                                                        action="{{ route('admin.admin-store.personal-info') }}"
                                                        method="post" class="content" role="tabpanel"
                                                        aria-labelledby="personal-info-modern-trigger">
                                                        @csrf
                                                        <input type="hidden" name="user_id" class="user_id">
                                                        <div class="content-header">
                                                            <h5 class="mb-0">Personal Info</h5>
                                                            <small>Enter Your Personal Info.</small>
                                                        </div>
                                                        <div class="row">
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-phone">phone</label>
                                                                <input type="text" id="modern-phone" name="phone"
                                                                    class="form-control" placeholder=""
                                                                    value="{{ $phone }}" />
                                                            </div>
                                                            <!-- gender -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label"
                                                                    for="modern-gender">Gender</label>
                                                                <select class="select2 w-100" name="gender"
                                                                    id="modern-gender">
                                                                    <option value="">Choose your gender</option>
                                                                    <option value="male">Male</option>
                                                                    <option value="female">Female</option>
                                                                    <option value="other">other</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="mb-1 col-md-6 ms-auto">
                                                                <label class="form-label" for="modern-date-of-birth">Date
                                                                    of Birth</label>
                                                                <input type="text" name="date_of_birth"
                                                                    id="modern-date-of-birth"
                                                                    class="form-control flatpickr-basic"
                                                                    placeholder="YYYY-MM-DD"
                                                                    value="{{ $date_of_birth }}" />
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-primary btn-prev" type="button">
                                                                <i data-feather="arrow-left"
                                                                    class="align-middle me-sm-25 me-0"></i>
                                                                <span
                                                                    class="align-middle d-sm-inline-block d-none">Previous</span>
                                                            </button>
                                                            <button class="btn btn-primary" type="button"
                                                                data-file="true" onclick="_run(this)" data-el="fg"
                                                                data-form="personal-info-modern"
                                                                data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                                                data-callback="personal_callback"
                                                                data-btnid="btn-save-personal-info"
                                                                id="btn-save-personal-info">
                                                                <i data-feather='save'
                                                                    class="align-middle ms-sm-25 ms-0"></i>
                                                                <span
                                                                    class="align-middle d-sm-inline-block d-none">Save</span>
                                                            </button>
                                                            <button type="button" class="btn-next d-none"
                                                                id="next-personal"></button>
                                                        </div>
                                                    </form>
                                                    <!-- content address -->
                                                    <form id="address-step-modern"
                                                        action="{{ route('admin.admin-store.address') }}" method="post"
                                                        class="content" role="tabpanel"
                                                        aria-labelledby="address-step-modern-trigger">

                                                        @csrf
                                                        <input type="hidden" name="user_id" class="user_id">
                                                        <div class="content-header">
                                                            <h5 class="mb-0">Address</h5>
                                                            <small>Enter Your Address.</small>
                                                        </div>
                                                        <div class="row">
                                                            <!-- country -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label"
                                                                    for="modern-country">Country</label>
                                                                <select class="select2 w-100" name="country"
                                                                    id="modern-country">
                                                                    <option value="">Choose your country</option>
                                                                    @foreach ($countries as $value)
                                                                        <option value="{{ $value->id }}">
                                                                            {{ $value->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <!-- state -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-state">State</label>
                                                                <input type="text" id="modern-state" name="state"
                                                                    class="form-control" value="{{ $state }}" />
                                                            </div>
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-city">City</label>
                                                                <input type="text" id="modern-city" name="city"
                                                                    class="form-control" value="{{ $city }}" />
                                                            </div>
                                                            <!-- zipcodee -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label"
                                                                    for="modern-zicode">Zipcode</label>
                                                                <input type="text" id="modern-zicode" name="zipcode"
                                                                    class="form-control" value="{{ $zipcode }}" />
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <!-- address -->
                                                            <div class="mb-1 col-md-6 ms-auto">
                                                                <label class="form-label"
                                                                    for="modern-address">Address</label>
                                                                <textarea name="zipcode" class="form-control" placeholder="Borough bridge">{{ $address }}</textarea>
                                                            </div>

                                                        </div>

                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-primary btn-prev">
                                                                <i data-feather="arrow-left"
                                                                    class="align-middle me-sm-25 me-0"></i>
                                                                <span
                                                                    class="align-middle d-sm-inline-block d-none">Previous</span>
                                                            </button>
                                                            <button class="btn btn-primary " type="button"
                                                                data-file="true" onclick="_run(this)" data-el="fg"
                                                                data-form="address-step-modern"
                                                                data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                                                data-callback="address_callback"
                                                                data-btnid="btn-save-address" id="btn-save-address">
                                                                <i data-feather='save'
                                                                    class="align-middle ms-sm-25 ms-0"></i>
                                                                <span class="align-middle d-sm-inline-block d-none">Save
                                                                    Change</span>
                                                            </button>
                                                            <button type="button" class="btn-next d-none"
                                                                id="next-address"></button>
                                                        </div>
                                                    </form>
                                                    <!-- social link -->
                                                    <form id="social-links-modern"
                                                        action="{{ route('admin.admin-store-registration') }}"
                                                        method="post" class="content" role="tabpanel"
                                                        aria-labelledby="social-links-modern-trigger">
                                                        @csrf
                                                        <input type="hidden" name="user_id" class="user_id">
                                                        <div class="content-header">
                                                            <h5 class="mb-0">Social Links</h5>
                                                            <small>Enter Your Social Links.</small>
                                                        </div>
                                                        <div class="row">
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label"
                                                                    for="modern-twitter">Twitter</label>
                                                                <input type="text" id="modern-twitter" name="twitter"
                                                                    class="form-control"
                                                                    placeholder="https://twitter.com/abc" />
                                                            </div>
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label"
                                                                    for="modern-facebook">Facebook</label>
                                                                <input type="text" id="modern-facebook"
                                                                    name="facebook" class="form-control"
                                                                    placeholder="https://facebook.com/abc" />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label"
                                                                    for="modern-telegram">Telegram</label>
                                                                <input type="text" id="modern-telegram"
                                                                    name="telegram" class="form-control"
                                                                    placeholder="" />
                                                            </div>
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label"
                                                                    for="modern-linkedin">Linkedin</label>
                                                                <input type="text" id="modern-linkedin"
                                                                    name="linkedin" class="form-control"
                                                                    placeholder="https://linkedin.com/abc" />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <!-- skeyp -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-skype">Skype</label>
                                                                <input type="text" id="modern-skype" name="skype"
                                                                    class="form-control" placeholder="" />
                                                            </div>
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label"
                                                                    for="modern-whatsapp">Whatsapp</label>
                                                                <input type="text" id="modern-whatsapp"
                                                                    name="whatsapp" class="form-control"
                                                                    placeholder="" />
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-primary btn-prev" type="button">
                                                                <i data-feather="arrow-left"
                                                                    class="align-middle me-sm-25 me-0"></i>
                                                                <span
                                                                    class="align-middle d-sm-inline-block d-none">Previous</span>
                                                            </button>
                                                            <button class="btn btn-success" type="button"
                                                                data-file="true" onclick="_run(this)" data-el="fg"
                                                                data-form="social-links-modern"
                                                                data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                                                data-callback="social_info_callback"
                                                                data-btnid="btn-save-social-info"
                                                                id="btn-save-social-info">
                                                                <i data-feather='save'
                                                                    class="align-middle ms-sm-25 ms-0"></i>
                                                                <span class="align-middle d-sm-inline-block d-none">Save
                                                                    Request</span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </section>
                        </div>
                    @else
                        <div class="col-xl-8 col-lg-8 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    @include('errors.permission')
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <!--/ Role cards -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
    <script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
    <!-- datatable -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>

    <!-- date picker -->

    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/admin-admin-registration.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/extensions/ext-component-blockui.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js') }}"></script>
    <script src="{{ asset('/common-js/copy-js.js') }}"></script>
    <script src="{{ asset('/common-js/password-gen.js') }}"></script>
    <script>
        // genrate randome password
        $(document).on('click', ".btn-gen-password", function() {
            var field = $(this).closest('div').find('input[rel="gp"]');
            field.val(rand_string(field));
            field.attr('type', 'text');
        });
        // select password for copy
        $('input[rel="gp"]').on("click", function() {
            let id = $(this).attr('id');
            $(this).select();
            if ($(this).val() != "") {
                copy_to_clipboard(id)
            }
            $(this).attr('type', 'password');
        });


        function create_admin_call_back(data) {
            $('.error-msg').closest('.al-input-error-fixed').css({
                'margin-bottom': '0px'
            });
            if (data.status == true) {
                toastr['success'](data.message, 'Admin Adding', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                });
                $.validator("admin-reg-form", data.errors);
                $("#admin-reg-form").trigger('reset');

            } else {
                $.validator("admin-reg-form", data.errors);
                toastr['error'](data.message, 'Admin Adding', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                });
                $('.error-msg').closest('.al-input-error-fixed').css({
                    'margin-bottom': '15px'
                });
            }
        }
        // admin registration 
        $("#account-details-modern").trigger('reset');

        function account_details_callback(data) {
            if (data.status) {
                notify('success', data.message, 'Admin account details');
                $(".user_id").each(function() {
                    $(this).val(data.user_id);
                });
                // $("#account-details-modern").trigger('reset');
                $("#next-account").trigger('click');
            } else {
                notify('error', data.message, 'Admin account details');
            }
            $.validator("account-details-modern", data.errors);
        }
        // add personal info
        function personal_callback(data) {
            if (data.status) {
                notify('success', data.message, 'Admin personal info');
                $("#next-account").trigger('click');
            } else {
                notify('error', data.message, 'Admin personal info');
            }
            $.validator("personal-info-modern", data.errors);
        }
        // add address
        function address_callback(data) {
            if (data.status) {
                notify('success', data.message, 'Admin address');
                $("#next-address").trigger('click');
            } else {
                notify('error', data.message, 'Admin address');
            }
            $.validator("address-step-modern", data.errors);
        }

        function social_info_callback(data) {
            if (data.status) {
                notify('success', data.message, 'Admin registration');
                $("#social-links-madern, #address-step-modern, #account-details-modern, #personal-info-modern").trigger(
                    'reset');
                $("#account-details-top").trigger("click");
            } else {
                notify('error', data.message, 'Admin registration');
            }
            $.validator("social-links-modern", data.errors);
        }
    </script>
@stop
<!-- BEGIN: page JS -->
