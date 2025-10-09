@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Admin Groups')
@section('vendor-css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/fonts/font-awesome/css/font-awesome.min.css') }}">
    <!-- datatable -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <!-- number input -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('common-css/data-list-style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css') }}">
    <style>
        .lgrp-paginate {
            position: absolute;
            right: 17px;
            bottom: -24px;
        }

        .data-list-footer {
            display: flex;
            justify-content: space-between;
            padding: 0 0rem;
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
                <h3>{{ __('admin-management.Group List') }}</h3>
                <p class="mb-2">
                    {{ __('admin-management.sentence') }}
                </p>
                <!-- Role cards -->
                <div class="row position-relative mb-3">
                    <div class="col-md-8 col-sm-12">
                        <div class="row" id="data-list">

                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="card">
                            <div class="row p-2">
                                <div class="col-lg-5 d-lg-block d-md-none d-sm-block">
                                    <div class="w-50">
                                        <img src="{{ asset('admin-assets/app-assets/images/illustration/faq-illustrations.svg') }}"
                                            class="img-fluid" alt="Image" />
                                    </div>
                                    <!--<div class="d-flex align-items-end justify-content-center h-100">-->
                                    <!--    <img src="{{ asset('admin-assets/app-assets/images/illustration/faq-illustrations.svg') }}" class="img-fluid mt-2" alt="Image" width="85" />-->
                                    <!--</div>-->
                                </div>
                                @if (Auth::user()->hasDirectPermission('create admin groups'))
                                    <div class="col-lg-7 col-md-12">
                                        <div class="">
                                            <a href="javascript:void(0)" data-bs-target="#offcanvasBackdrop"
                                                data-bs-toggle="offcanvas" class="stretched-link text-nowrap add-new-role"
                                                aria-controls="offcanvasBackdrop">
                                                <span
                                                    class="btn btn-primary mb-1">{{ __('admin-management.Add New Group') }}</span>
                                            </a>
                                            <p class="mb-0">{{ __('admin-management.Add Groups') }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-lg-7 col-md-12">
                                        <div class="">
                                            <span class="text-danger">{{ 'You Dont Have Create Permisstion' }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!--/ Role cards -->

                <h3 class="mt-50">{{ __('admin-management.total') }}</h3>
                <p class="mb-2">{{ __('admin-management.find') }}</p>
                <!-- table -->
                <div class="card p-3">
                    <div class="table-responsive">
                        <table class="user-list-table table " id="admin-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('admin-management.Name') }}</th>
                                    <th>{{ __('admin-management.Groups') }}</th>
                                    <th>{{ __('admin-management.Country') }}</th>
                                    <th>{{ __('admin-management.Status') }}</th>
                                    <th>{{ __('admin-management.Actions') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- table -->
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- Enable backdrop (default) -->
    <div class="enable-backdrop">
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasBackdrop"
            aria-labelledby="offcanvasBackdropLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasBackdropLabel" class="offcanvas-title">{{ __('page.add_new_admin_group') }}</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body my-auto mx-0 flex-grow-0">
                <p>
                    <b>{{ __('page.group_name_as_rols_name') }}.</b>
                    {{ __('page.a_role_provided_access') }}
                </p>
                <form onkeydown="return event.key != 'Enter';" action="{{ route('admin.add-admin-group') }}"
                    method="post" id="admin-group-form">
                    @csrf
                    <label class="form-label" for="group-name">{{ __('page.group_name') }}</label>
                    <input id="group-name" class="form-control" type="text" placeholder="Normal Input"
                        name="group_name" />

                    <button type="button" class="btn btn-primary mb-1 d-grid w-100 mt-1" id="save-group"
                        onclick="_run(this)" data-el="fg" data-form="admin-group-form"
                        data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                        data-callback="add_group_call_back" data-btnid="save-group">{{ __('page.save_group') }}</button>
                    <button type="button" id="add-group-cancel" class="btn btn-outline-secondary d-grid w-100"
                        data-bs-dismiss="offcanvas">
                        {{ __('page.cancel') }}
                    </button>
                </form>

            </div>
        </div>
    </div>
    <!--/ Enable backdrop (default) -->
    <!-- Enable backdrop (edit group) -->
    <div class="enable-backdrop">
        <div class="offcanvas offcanvas-end" tabindex="-1" id="editGroup" aria-labelledby="editGroupLabel">
            <div class="offcanvas-header">
                <h5 id="editGroupLabel" class="offcanvas-title">{{ __('page.update_admin_group') }}</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body my-auto mx-0 flex-grow-0">
                <p>
                    <b>{{ __('page.group_name_as_rols_name') }}.</b>
                    {{ __('page.a_role_provided_access') }}
                </p>
                <form onkeydown="return event.key != 'Enter';" action="{{ route('admin.update-admin-group') }}"
                    method="post" id="admin-group-update-form">
                    @csrf
                    <input type="hidden" name="group_id" value="" id="group_id">
                    <label class="form-label" for="group-name-edit">{{ __('page.group_name') }}</label>
                    <input id="group-name-edit" class="form-control" type="text" placeholder="Normal Input"
                        name="group_name" />
                    <!-- <button type="button" id="save-group" class="btn btn-primary mb-1 d-grid w-100 mt-1">Save Group</button> -->
                    <button type="button" class="btn btn-primary mb-1 d-grid w-100 mt-1" id="save-update-group"
                        onclick="_run(this)" data-el="fg" data-form="admin-group-update-form"
                        data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                        data-callback="update_group_call_back"
                        data-btnid="save-update-group">{{ __('page.save-change') }}</button>
                    <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="offcanvas">
                        {{ __('page.cancel') }}
                    </button>
                </form>

            </div>
        </div>
    </div>
    <!--/ Enable backdrop (edit group) -->

    <!--start update Modal -->
    <div class="modal fade text-start" id="modal-update-admin" tabindex="-1" aria-labelledby="Update Admin"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-header-update-admin">{{ __('page.update_admin') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <section class="modern-horizontal-wizard">
                        <div class="bs-stepper wizard-modern modern-wizard-example">
                            <div class="bs-stepper-header">
                                <!-- stepper account details -->
                                <div class="step" data-target="#account-details-modern" role="tab"
                                    id="account-details-modern-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="file-text" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Account Details</span>
                                            <span class="bs-stepper-subtitle">Setup Account Details</span>
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
                            <div class="bs-stepper-content">

                                <!-- content acount details -->
                                <form id="account-details-modern"
                                    action="{{ route('admin.admin-group.account-details') }}" method="post"
                                    class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger">
                                    @csrf
                                    <input type="hidden" name="user_id" class="user_id">
                                    <div class="content-header">
                                        <h5 class="mb-0">Account Details</h5>
                                        <small class="text-muted">Enter Your Account Details.</small>
                                    </div>
                                    <div class="row">
                                        <!-- email  -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-email">Email</label>
                                            <input type="email" id="modern-email" name="email" disabled
                                                class="form-control" placeholder="john.doe@email.com"
                                                aria-label="john.doe" />
                                        </div>
                                        <!-- phone -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-phone">phone</label>
                                            <input type="text" id="modern-phone" name="phone" class="form-control"
                                                placeholder="johndoe" />
                                        </div>
                                    </div>
                                    <!-- row password -->
                                    <div class="row">
                                        <div class="mb-1 form-password-toggle col-md-6">
                                            <label class="form-label" for="modern-password">Password</label>
                                            <div class="input-group form-password-toggle mb-2">
                                                <input type="password" class="form-control" name="password"
                                                    id="modern-password" placeholder="Your Password"
                                                    aria-describedby="modern-password" />
                                                <span class="input-group-text cursor-pointer"><i
                                                        data-feather="eye"></i></span>
                                            </div>
                                        </div>
                                        <div class="mb-1 form-password-toggle col-md-6">
                                            <label class="form-label" for="modern-confirm-password">Confirm
                                                Password</label>
                                            <input type="password" id="modern-confirm-password" name="confirm_password"
                                                class="form-control"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                        </div>
                                    </div>
                                    <!-- has mail or not -->
                                    <div class="row mb-2">
                                        <div class="demo-inline-spacing">
                                            <label for="Sending mail"> Sending Mail</label>
                                            <div class="form-check form-check-success">
                                                <input type="radio" id="customColorRadio3" name="sending_mail"
                                                    class="form-check-input" value="yes" checked />
                                                <label class="form-check-label" for="customColorRadio3">Yes</label>
                                            </div>
                                            <div class="form-check form-check-danger">
                                                <input type="radio" id="customColorRadio5" name="sending_mail"
                                                    class="form-check-input" value="no" />
                                                <label class="form-check-label" for="customColorRadio5">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-outline-secondary btn-prev" disabled type="button">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-primary" type="button" data-file="true"
                                            onclick="_run(this)" data-el="fg" data-form="account-details-modern"
                                            data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                            data-callback="account_details_callback" data-btnid="btn-save-account-details"
                                            id="btn-save-account-details">
                                            <i data-feather='save' class="align-middle ms-sm-25 ms-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Save</span>
                                        </button>
                                    </div>
                                </form>
                                <form id="personal-info-modern" action="{{ route('admin.admin-group.personal-info') }}"
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
                                            <label class="form-label" for="modern-name">Name</label>
                                            <input type="text" id="modern-name" name="name" class="form-control"
                                                placeholder="John" />
                                        </div>
                                        <!-- gender -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-gender">Gender</label>
                                            <select class="select2 w-100" name="gender" id="modern-gender">
                                                <option label="Choose your gender">Choose your gender</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-6 ms-auto">
                                            <label class="form-label" for="modern-date-of-birth">Date</label>
                                            <input type="text" name="date_of_birth" id="modern-date-of-birth"
                                                class="form-control flatpickr-basic" placeholder="YYYY-MM-DD"
                                                value="" />
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-primary btn-prev" type="button">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-primary" type="button" data-file="true"
                                            onclick="_run(this)" data-el="fg" data-form="personal-info-modern"
                                            data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                            data-callback="personal_callback" data-btnid="btn-save-personal-info"
                                            id="btn-save-personal-info">
                                            <i data-feather='save' class="align-middle ms-sm-25 ms-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Save change</span>

                                        </button>
                                    </div>
                                </form>
                                <!-- content address -->
                                <form id="address-step-modern" action="{{ route('admin.admin-group.update-address') }}"
                                    method="post" class="content" role="tabpanel"
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
                                            <label class="form-label" for="modern-country">Country</label>
                                            <select class="select2 w-100" name="country" id="modern-country">
                                                <option label="Choose your country">Choose your country</option>
                                            </select>
                                        </div>
                                        <!-- state -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-state">State</label>
                                            <input type="text" id="modern-state" name="state"
                                                class="form-control" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-city">City</label>
                                            <input type="text" id="modern-city" name="city"
                                                class="form-control" />
                                        </div>
                                        <!-- zipcodee -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-zicode">Zipcode</label>
                                            <input type="text" id="modern-zicode" name="zipcode"
                                                class="form-control" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- address -->
                                        <div class="mb-1 col-md-6 ms-auto">
                                            <label class="form-label" for="modern-address">Address</label>
                                            <textarea name="zipcode" class="form-control" placeholder="Borough bridge"></textarea>
                                        </div>

                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-primary btn-prev">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-primary " type="button" data-file="true"
                                            onclick="_run(this)" data-el="fg" data-form="address-step-modern"
                                            data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                            data-callback="address_callback" data-btnid="btn-save-address"
                                            id="btn-save-address">
                                            <i data-feather='save' class="align-middle ms-sm-25 ms-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Save Change</span>
                                        </button>
                                    </div>
                                </form>
                                <!-- social link -->
                                <form id="social-links-modern"
                                    action="{{ route('admin.admin-group.update-social-link') }}" method="post"
                                    class="content" role="tabpanel" aria-labelledby="social-links-modern-trigger">
                                    @csrf
                                    <input type="hidden" name="user_id" class="user_id">
                                    <div class="content-header">
                                        <h5 class="mb-0">Social Links</h5>
                                        <small>Enter Your Social Links.</small>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-twitter">Twitter</label>
                                            <input type="text" id="modern-twitter" name="twitter"
                                                class="form-control" placeholder="https://twitter.com/abc" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-facebook">Facebook</label>
                                            <input type="text" id="modern-facebook" name="facebook"
                                                class="form-control" placeholder="https://facebook.com/abc" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-telegram">Telegram</label>
                                            <input type="text" id="modern-telegram" name="telegram"
                                                class="form-control" placeholder="" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-linkedin">Linkedin</label>
                                            <input type="text" id="modern-linkedin" name="linkedin"
                                                class="form-control" placeholder="https://linkedin.com/abc" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- skeyp -->
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-skype">Skype</label>
                                            <input type="text" id="modern-skype" name="skype" class="form-control"
                                                placeholder="" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="modern-whatsapp">Whatsapp</label>
                                            <input type="text" id="modern-whatsapp" name="whatsapp"
                                                class="form-control" placeholder="" />
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-primary btn-prev" type="button">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-success" type="button" data-file="true"
                                            onclick="_run(this)" data-el="fg" data-form="social-links-modern"
                                            data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"
                                            data-callback="social_info_callback" data-btnid="btn-save-social-info"
                                            id="btn-save-social-info">
                                            <i data-feather='save' class="align-middle ms-sm-25 ms-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Save Change</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <!-- ending update modal -->
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
    <!-- picker js -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <!-- number input -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/admin-admin-registration.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/admin-groups.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-number-input.js') }}"></script>
    <script src="{{ asset('/common-js/copy-js.js') }}"></script>
    <script src="{{ asset('/common-js/password-gen.js') }}"></script>
    <script src="{{ asset('common-js/data-col.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/extensions/ext-component-clipboard.js') }}"></script>
    <script src="{{ asset('common-js/select2-get-country.js') }}"></script>
    <script>
        // copy password
        $('#modern-password').copy_clipboard({
            copy_el: '#modern-password'
        });
        // genrate randome password
        $(document).on('click', ".btn-gen-password", function() {
            var field = $(this).closest('div').find('input[rel="gp"]');
            field.val(rand_string(field));
            field.attr('type', 'text');
        });
        // select password for copy

        $(document).on("click", 'input[rel="gp"]', function() {
            let id = $(this).attr('id');
            $(this).select();
            if ($(this).val() != "") {
                copy_to_clipboard(id)
            }
            $(this).attr('type', 'password');
        });
    </script>
    <script>
        var data_list = $("#data-list");
        var dataList = data_list.data_list({
            serverSide: true,
            url: '/admin/admin-management/admin-groups',
            listPerPage: 2
        });
        // add new groups
        // ----------------------------------------------------------------
        function add_group_call_back(data) {
            if (data.status == true) {
                toastr['success'](data.message, 'Admin Group', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                });
                // $("#offcanvasBackdrop").modal('hide');
                $("#add-group-cancel").trigger("click");
                dataList.draw_list();
                $.validator("admin-group-form", data.errors);
            } else {
                $.validator("admin-group-form", data.errors);
            }
        }
        // update existing group
        // ------------------------------------------------------------------
        function update_group_call_back(data) {
            if (data.status == true) {
                toastr['success'](data.message, 'Admin Group', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                });
                $(".btn-close").trigger('click');
                dataList.draw_list();
                comment_table_obj.DataTable().draw();
                $.validator("admin-group-update-form", data.errors);
            } else {
                $.validator("admin-group-update-form", data.errors);
            }
        }
        // asign permission to a role
        // like as read, write, and create permission
        function assing_permission_call_back(data) {
            if (data.status == true) {
                notify('success', data.message, 'Admin Right');
            } else {
                notify('error', 'Please fix following errors', 'Admin Right');
            }
        }

        // update admins-------------------
        function admin_update_call_back(data) {
            if (data.status == true) {
                notify('success', data.message, 'Admin update');
                $("#modal-update-admin").modal('hide');
            } else {
                notify('error', 'Please fix following errors', 'Admin update');
            }
            $.validator("admin-update-form", data.errors);
        }
        // account details update
        function account_details_callback(data) {
            if (data.status == true) {
                notify('success', data.message, 'Account details');
            } else {
                notify('error', data.message, 'Account Details');
            }
            $.validator("account-details-modern", data.errors);
        }
        // update personal info
        function personal_callback(data) {
            if (data.status == true) {
                notify('success', data.message, 'Personal info');
            } else {
                notify('error', data.message, 'Personal info');
            }
            $.validator("personal-info-modern", data.errors);
        }
        // update address
        function address_callback(data) {
            if (data.status == true) {
                notify('success', data.message, 'Admin address');
            } else {
                notify('error', data.message, 'Admin address');
            }
            $.validator("address-step-modern", data.errors);
        }
        // update social link
        function social_info_callback(data) {
            if (data.status == true) {
                notify('success', data.message, 'Admin Social Links');
            } else {
                notify('error', data.message, 'Admin Social Links');
            }
            $.validator("social-links-modern", data.errors);
        }
    </script>
@stop
<!-- BEGIN: page JS -->
