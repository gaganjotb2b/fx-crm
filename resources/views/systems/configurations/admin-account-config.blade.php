@extends('layouts.system-layout')
@section('title','Trading Account Configuration')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/assets/css/config-form.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css')}}">
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Account Configuration</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">Configurations</a></li>
                                <li class="breadcrumb-item active">Account Configuration</li>
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
            <!-- modern stepper -->
            <section class="modern-horizontal-wizard">
                <div class="bs-stepper wizard-modern modern-wizard-example">
                    <div class="bs-stepper-header">
                        <!-- stepper account number -->
                        <div class="step" data-target="#account-details-modern" role="tab" id="account-details-modern-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="file-text" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Account Number</span>
                                    <span class="bs-stepper-subtitle">Setup Account Number</span>
                                </span>
                            </button>
                        </div>
                        <div class="line">
                            <!-- <i data-feather="chevron-right" class="font-medium-2"></i> -->
                            &nbsp;
                        </div>
                        <div class="step" data-target="#personal-info-modern" role="tab" id="personal-info-modern-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="user" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Account Transfer</span>
                                    <span class="bs-stepper-subtitle">Account Transfer Permission</span>
                                </span>
                            </button>
                        </div>
                        <div class="line">
                            <!-- <i data-feather="chevron-right" class="font-medium-2"></i> -->
                            &nbsp;
                        </div>
                        <div class="step" data-target="#address-step-modern" role="tab" id="address-step-modern-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="map-pin" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Account Password</span>
                                    <span class="bs-stepper-subtitle">Change password permission</span>
                                </span>
                            </button>
                        </div>
                        <div class="line">
                            <!-- <i data-feather="chevron-right" class="font-medium-2"></i> -->
                            &nbsp;
                        </div>
                        <!-- change group adn leverage permission -->
                        <div class="step" data-target="#social-links-modern" role="tab" id="social-links-modern-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="link" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Group & Leverage</span>
                                    <span class="bs-stepper-subtitle">Group & Leverage permission</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <!-- content account number -->
                        <div id="account-details-modern" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger">
                            <div class="content-header">
                                <h5 class="mb-0">Account Number</h5>
                                <small class="text-muted">Setup account number start & limit.</small>
                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-6 mx-auto">
                                    <div class="demo-inline-spacing">
                                        <div class="form-check form-check-success">
                                            <input type="radio" id="customColorRadio3" name="account_gen" class="form-check-input" checked />
                                            <label class="form-check-label" for="customColorRadio3">Auto</label>
                                        </div>
                                        <div class="form-check form-check-danger">
                                            <input type="radio" id="customColorRadio5" name="account_gen" class="form-check-input" />
                                            <label class="form-check-label" for="customColorRadio5">Custom</label>
                                        </div>
                                        <div class="form-check form-check-warning">
                                            <input type="radio" id="customColorRadio4" name="account_gen" class="form-check-input" />
                                            <label class="form-check-label" for="customColorRadio4">Limit</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <!-- account start from -->
                                <div class="row" style="display: none;">
                                    <div class="mb-1 form-password-toggle col-md-6 mx-auto">
                                        <label class="form-label" for="modern-start-from">Account Start From</label>
                                        <input type="text" id="modern-start-from" class="form-control" name="start_from" value="" />
                                    </div>
                                </div>
                                <!-- account ending -->
                                <div class="row">
                                    <div class="mb-1 form-password-toggle col-md-6 mx-auto">
                                        <label class="form-label" for="modern-account-end">Account End</label>
                                        <input type="text" id="modern-account-end" class="form-control" name="end_account" value="" />
                                    </div>
                                </div>
                            </div>
                            <!-- account limit -->
                            <div class="row">
                                <div class="mb-1 form-password-toggle col-md-6 mx-auto">
                                    <label class="form-label" for="modern-account-limit">Account Limit</label>
                                    <input type="text" id="modern-account-limit" class="form-control" name="account_limit" value="" />
                                </div>
                            </div>
                            <!-- last account -->
                            <div class="row">
                                <div class="mb-1 form-password-toggle col-md-6 mx-auto">
                                    <label class="form-label" for="modern-last-account">Last Account</label>
                                    <input type="text" id="modern-last-account" class="form-control" name="account_limit" value="" />
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6 mx-auto">
                                    <div class="d-flex justify-content-between mt-3">
                                        <button class="btn btn-outline-secondary btn-prev" disabled>
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-primary">
                                            <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Save Change</span>

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="personal-info-modern" class="content" role="tabpanel" aria-labelledby="personal-info-modern-trigger">
                            <div class="content-header">
                                <h5 class="mb-0">Personal Info</h5>
                                <small>Enter Your Personal Info.</small>
                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="modern-first-name">First Name</label>
                                    <input type="text" id="modern-first-name" class="form-control" placeholder="John" />
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="modern-last-name">Last Name</label>
                                    <input type="text" id="modern-last-name" class="form-control" placeholder="Doe" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="modern-country">Country</label>
                                    <select class="select2 w-100" id="modern-country">
                                        <option label=" "></option>
                                        <option>UK</option>
                                        <option>USA</option>
                                        <option>Spain</option>
                                        <option>France</option>
                                        <option>Italy</option>
                                        <option>Australia</option>
                                    </select>
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="modern-language">Language</label>
                                    <select class="select2 w-100" id="modern-language" multiple>
                                        <option>English</option>
                                        <option>French</option>
                                        <option>Spanish</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-primary btn-prev">
                                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-primary btn-next">
                                    <span class="align-middle d-sm-inline-block d-none">Next</span>
                                    <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                </button>
                            </div>
                        </div>
                        <div id="address-step-modern" class="content" role="tabpanel" aria-labelledby="address-step-modern-trigger">
                            <div class="content-header">
                                <h5 class="mb-0">Address</h5>
                                <small>Enter Your Address.</small>
                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="modern-address">Address</label>
                                    <input type="text" id="modern-address" class="form-control" placeholder="98  Borough bridge Road, Birmingham" />
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="modern-landmark">Landmark</label>
                                    <input type="text" id="modern-landmark" class="form-control" placeholder="Borough bridge" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="pincode3">Pincode</label>
                                    <input type="text" id="pincode3" class="form-control" placeholder="658921" />
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="city3">City</label>
                                    <input type="text" id="city3" class="form-control" placeholder="Birmingham" />
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-primary btn-prev">
                                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-primary btn-next">
                                    <span class="align-middle d-sm-inline-block d-none">Next</span>
                                    <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                </button>
                            </div>
                        </div>
                        <div id="social-links-modern" class="content" role="tabpanel" aria-labelledby="social-links-modern-trigger">
                            <div class="content-header">
                                <h5 class="mb-0">Social Links</h5>
                                <small>Enter Your Social Links.</small>
                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="modern-twitter">Twitter</label>
                                    <input type="text" id="modern-twitter" class="form-control" placeholder="https://twitter.com/abc" />
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="modern-facebook">Facebook</label>
                                    <input type="text" id="modern-facebook" class="form-control" placeholder="https://facebook.com/abc" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="modern-google">Google+</label>
                                    <input type="text" id="modern-google" class="form-control" placeholder="https://plus.google.com/abc" />
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="modern-linkedin">Linkedin</label>
                                    <input type="text" id="modern-linkedin" class="form-control" placeholder="https://linkedin.com/abc" />
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-primary btn-prev">
                                    <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-success btn-submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js')}}"></script>
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
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- toaster js -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js')}}"></script>
<script>
    //add Account Configuration callback
    function accountConfigCallBack(data) {
        console.log(data)
        if (data.status) {
            notify('success', data.message, 'Account Configuration');
        } else {
            notify('error', 'Failed To Update!', 'Account Configuration');
        }
    }
</script>
@stop
<!-- BEGIN: page JS -->