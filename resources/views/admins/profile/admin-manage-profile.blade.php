@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Admin Manage Profile')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/fonts/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/jstree.min.css') }}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<!-- number input -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-tree.css') }}">
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/profile-setting.css') }}"> -->
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
                        <h2 class="content-header-title float-start mb-0">{{ __('page.Manage_Profile') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('finance.home') }}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{ __('page.admin_profile') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('page.Manage_Profile') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                        <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="app-todo.html"><i class="me-1" data-feather="info"></i><span class="align-middle">Note</span></a><a class="dropdown-item" href="app-chat.html" data-bs-toggle="modal" data-bs-target="#editUser"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- User Sidebar -->
                <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                        <!-- User Card -->
                        <div class="card">
                            <div class="card-body">
                                <div class="user-avatar-section">
                                    <div class="d-flex align-items-center flex-column">
                                        <img class="img-fluid rounded mt-3 mb-2" src="{{ $avatar }}" height="110" width="110" alt="User avatar" />
                                        <div class="user-info text-center">
                                            <h4>{{ $user->name }}</h4>
                                            <span class="badge bg-light-secondary">{{ __('page.Admin') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-around my-2 pt-75" style="width: 100%">
                                    <div class="d-flex profile-social-icon justify-content-evenly w-100">
                                        <a target="_blank" href="{{ isset($link->facebook) ? 'https://'.$link->facebook : '#' }}"> <i class="fab fa-facebook fa-lg"></i></a>
                                        <a target="_blank" href="{{ isset($link->whatsapp) ? 'https://web.whatsapp.com/send?phone=' . $link->whatsapp : '#' }}">
                                            <i class="fab fa-whatsapp fa-lg"></i></a>
                                        <a target="_blank" href="{{ isset($link->skype) ?'https://'.$link->skype : '#' }}"> <i class="fab fa-skype fa-lg"></i></a>
                                        <a target="_blank" href="{{ isset($link->linkedin) ?'https://'.$link->linkedin : '#' }}"> <i class="fab fa-linkedin fa-lg"></i></a>
                                        <a target="_blank" href="{{ isset($link->twitter) ? 'https://'.$link->twitter : '#' }}">
                                            <i class="fab fa-twitter fa-lg"></i></a>
                                    </div>
                                    <div class="d-flex">
                                    </div>
                                </div>
                                <h4 class="fw-bolder border-bottom pb-50 mb-1"> {{ __('page.Details') }}</h4>
                                <div class="info-container">
                                    <ul class="list-unstyled">
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">{{ __('page.name') }}:</span>
                                            <span>{{ $user->name }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">{{ __('page.email') }}:</span>
                                            <span>{{ $user->email }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">{{ __('page.status') }}:</span>
                                            <span class="badge bg-light-success">{{ __('page.active') }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">{{ __('admin-management.role') }}:</span>
                                            <span>{{ __('page.Admin') }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">{{ __('page.Contact') }}:</span>
                                            <span>{{ $user->phone }}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">{{ __('page.country') }}:</span>
                                            <span>{{ $country }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--/ User Sidebar -->
                    </div>
                    <!-- User Content -->
                    <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                        <!-- User Pills -->
                        <ul class="nav nav-tabs nav-fill">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab-fill" data-bs-toggle="tab" href="#form-profile-edit" role="tab" aria-controls="form-profile-edit" aria-selected="true">{{ __('page.profile') }} {{ __('page.Edit') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab-fill" data-bs-toggle="tab" href="#profile-fill" role="tab" aria-controls="profile-fill" aria-selected="false">{{ __('page.Security') }} {{ __('page.settings') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="messages-tab-fill" data-bs-toggle="tab" href="#messages-fill" role="tab" aria-controls="messages-fill" aria-selected="false">{{ __('page.Change') }} {{ __('page.email') }} &
                                    {{ __('page.phone') }} </a>
                            </li>

                        </ul>
                        <!--/ User Pills -->
                        <!-- Activity Timeline -->
                        <div class="card">
                            <div class="card-body pt-1" id="profile-edit">
                                <!-- Tab panes -->
                                <div class="tab-content pt-1">
                                    <!------------profile edit start-------------->
                                    <div class="tab-pane active" id="form-profile-edit" role="tabpanel" aria-labelledby="home-tab-fill">
                                        <!-- Modern Horizontal Wizard -->
                                        <section class="modern-horizontal-wizard">
                                            <div class="bs-stepper wizard-modern modern-wizard-example">
                                                <!-- stepper account details -->
                                                <div class="bs-stepper-header">
                                                    <!-- steppr account details -->
                                                    <div class="step" data-target="#account-details-modern" role="tab" id="account-details-modern-trigger">
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
                                                    <!-- personal info -->
                                                    <div class="line">
                                                        <i data-feather="chevron-right" class="font-medium-2"></i>
                                                    </div>
                                                    <div class="step" data-target="#personal-info-modern" role="tab" id="personal-info-modern-trigger">
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
                                                    <div class="step" data-target="#address-step-modern" role="tab" id="address-step-modern-trigger">
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
                                                    <div class="step" data-target="#social-links-modern" role="tab" id="social-links-modern-trigger">
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
                                                <!-- content account details -->
                                                <div class="bs-stepper-content shadow-none">
                                                    <!-- content account deetails -->
                                                    <form id="account-details-modern" action="{{route('admin.update-account-details')}}" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger">
                                                        @csrf
                                                        <div class="content-header">
                                                            <h5 class="mb-0">Account Details</h5>
                                                            <small class="text-muted">Enter Your Account Details.</small>
                                                        </div>
                                                        <div class="row">
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-email">Email</label>
                                                                <input type="email" name="email" disabled id="modern-email" class="form-control" placeholder="{{auth()->user()->email}}" aria-label="{{auth()->user()->email}}" value="{{auth()->user()->email}}" />
                                                            </div>
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-username">Phone</label>
                                                                <input type="text" id="modern-username" name="phone" disabled class="form-control" placeholder="{{auth()->user()->phone}}" />
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="mb-1 form-password-toggle col-md-4">
                                                                <label class="form-label" for="modern-password">Old Password</label>
                                                                <input type="password" name="old_password" id="modern-password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                                            </div>
                                                            <div class="mb-1 form-password-toggle col-md-4">
                                                                <label class="form-label" for="modern-password">New Password</label>
                                                                <input type="password" name="new_password" id="modern-password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                                            </div>
                                                            <div class="mb-1 form-password-toggle col-md-4">
                                                                <label class="form-label" for="modern-confirm-password">Confirm Password</label>
                                                                <input type="password" name="confirm_password" id="modern-confirm-password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-outline-secondary btn-prev" disabled>
                                                                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                            </button>
                                                            <button class="btn btn-primary" type="button" data-file="true" onclick="_run(this)" data-el="fg" data-form="account-details-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="account_details_callback" data-btnid="btn-save-account-details" id="btn-save-account-details">
                                                                <i data-feather='save' class="align-middle ms-sm-25 ms-0"></i>
                                                                <span class="align-middle d-sm-inline-block d-none">Save Change</span>
                                                            </button>
                                                            <button type="button" class="btn-next d-none">&nbsp;</button>
                                                        </div>
                                                    </form>
                                                    <!-- content step personal -->
                                                    <form id="personal-info-modern" action="{{route('admin.update-personal-info')}}" method="post" class="content" role="tabpanel" aria-labelledby="personal-info-modern-trigger">
                                                        @csrf
                                                        <div class="content-header">
                                                            <h5 class="mb-0">Personal Info</h5>
                                                            <small>Enter Your Personal Info.</small>
                                                        </div>
                                                        <div class="row">
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-first-name">Name</label>
                                                                <input type="text" id="modern-first-name" name="name" class="form-control" placeholder="John" value="{{auth()->user()->name}}" />
                                                            </div>
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-gender">Gender</label>
                                                                <select class="select2 w-100" name="gender" id="modern-gender">
                                                                    <option label=" " value="">Choose Gender</option>
                                                                    <option value="male">Male</option>
                                                                    <option value="female">Female</option>
                                                                    <option value="other">Other</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="mb-1 col-md-6 ms-auto">
                                                                <label class="form-label" for="fp-date-of-birth">Date of Birth</label>
                                                                <input type="text" name="date_of_birth" id="fp-date-of-birth" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" value="{{$date_of_birth}}" />
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-primary btn-prev">
                                                                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                            </button>
                                                            <button class="btn btn-primary" type="button" data-file="true" onclick="_run(this)" data-el="fg" data-form="personal-info-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="personal_callback" data-btnid="btn-save-personal-info" id="btn-save-personal-info">
                                                                <i data-feather='save' class="align-middle ms-sm-25 ms-0"></i>
                                                                <span class="align-middle d-sm-inline-block d-none">Save Change</span>
                                                            </button>
                                                            <button type="button" class="btn-next d-none">&nbsp;</button>
                                                        </div>
                                                    </form>
                                                    <!-- content address -->
                                                    <form id="address-step-modern" action="{{route('admin.update-address')}}" method="post" class="content" role="tabpanel" aria-labelledby="address-step-modern-trigger">
                                                        @csrf
                                                        <div class="content-header">
                                                            <h5 class="mb-0">Address</h5>
                                                            <small>Enter Your Address.</small>
                                                        </div>
                                                        <div class="row">
                                                            <!-- country -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="vertical-modern-country">Country</label>
                                                                <select class="select2 w-100" name="country" id="vertical-modern-country">
                                                                    <option label=" ">Choose a country</option>
                                                                    {!!$country_options!!}
                                                                </select>
                                                            </div>
                                                            <div class="mb-1 col-md-6">
                                                                <!-- state -->
                                                                <label class="form-label" for="state">State</label>
                                                                <input type="text" name="state" id="state" class="form-control" placeholder="Sate" value="{{$state}}" />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <!-- city -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="city3">City</label>
                                                                <input type="text" id="city3" name="city" class="form-control" placeholder="Birmingham" value="{{$city}}" />
                                                            </div>
                                                            <!-- zipcode -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-zipcode">Zipcode</label>
                                                                <input type="text" name="zipcode" id="modern-zipcode" class="form-control" placeholder="Borough bridge" value="{{$zipcode}}" />
                                                            </div>
                                                            <div class="mb-1 col-md-6 ms-auto">
                                                                <label class="form-label" for="modern-address">Address</label>
                                                                <textarea name="address" id="modern-address" class="form-control" placeholder="98  Borough bridge Road, Birmingham">{{$address}}</textarea>
                                                            </div>

                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-primary btn-prev" type="button">
                                                                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                            </button>
                                                            <!-- btn save address -->
                                                            <button class="btn btn-primary" type="button" data-file="true" onclick="_run(this)" data-el="fg" data-form="address-step-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="address_callback" data-btnid="btn-save-address" id="btn-save-address">
                                                                <i data-feather='save' class="align-middle ms-sm-25 ms-0"></i>
                                                                <span class="align-middle d-sm-inline-block d-none">Save Change</span>
                                                            </button>
                                                            <button type="button" class="btn-next d-none"></button>
                                                        </div>
                                                    </form>
                                                    <!-- content social links -->
                                                    <form id="social-links-modern" action="{{route('admin.update-social-links')}}" method="post" class="content" role="tabpanel" aria-labelledby="social-links-modern-trigger">

                                                        @csrf
                                                        <div class="content-header">
                                                            <h5 class="mb-0">Social Links</h5>
                                                            <small>Enter Your Social Links.</small>
                                                        </div>
                                                        <div class="row">
                                                            <!-- twitter -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-twitter">Twitter</label>
                                                                <input type="text" id="modern-twitter" name="twitter" class="form-control" placeholder="https://twitter.com/abc" value="{{($link)?$link->twitter:''}}" />
                                                            </div>
                                                            <!-- facebook -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-facebook">Facebook</label>
                                                                <input type="text" id="modern-facebook" name="facebook" class="form-control" placeholder="https://facebook.com/abc" value="{{($link)?$link->facebook:''}}" />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <!-- telegram -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-google">Telegram</label>
                                                                <input type="text" id="modern-google" name="telegram" class="form-control" placeholder="" value="{{($link)?$link->telegram:''}}" />
                                                            </div>
                                                            <!-- linkedin -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-linkedin">Linkedin</label>
                                                                <input type="text" id="modern-linkedin" name="linkedin" class="form-control" placeholder="https://linkedin.com/abc" value="{{($link)?$link->linkedin:''}}" />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <!-- skype -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-skype">Skype</label>
                                                                <input type="text" id="modern-skype" name="skype" class="form-control" placeholder="" value="{{($link)?$link->skype:''}}" />
                                                            </div>
                                                            <!-- whatsapp -->
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-whatsapp">Whatsapp</label>
                                                                <input type="text" id="modern-whatsapp" name="whatsapp" class="form-control" placeholder="" value="{{($link)?$link->whatsapp:''}}" />
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-primary btn-prev" type="button">
                                                                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                            </button>
                                                            <button class="btn btn-success" type="button" data-file="true" onclick="_run(this)" data-el="fg" data-form="social-links-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="social_info_callback" data-btnid="btn-save-social" id="btn-save-social">Save Change</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </section>
                                        <!-- /Modern Horizontal Wizard -->
                                    </div>
                                    <!-----------Profile edit part end---------------->

                                    <!-----------Security part start------------------>
                                    <div class="tab-pane" id="profile-fill" role="tabpanel" aria-labelledby="profile-tab-fill">
                                        <h4 class="header">{{ __('page.Security') }} {{ __('page.settings') }}</h4>
                                        <hr>
                                        <!--work here for scurity settings-->
                                        <div class="card mt-0">
                                            <div class="col-12">
                                                <div class="card p-2">
                                                    <div class="card-body">
                                                        <div class="title-wrapper">
                                                            <div class="d-flex flex-column float-start">
                                                                <div class="form-check form-switch form-check-primary">
                                                                    <input type="checkbox" class="form-check-input" id="noAuthCheck" value="no_auth" <?= $users->email_auth == 0 && $users->g_auth == 0 ? 'checked' : '' ?> />
                                                                    <label class="form-check-label" for="noAuthCheck">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <p class="todo-title">Normal - Simple security system. No
                                                                additional check require.</p>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="title-wrapper">
                                                            <div class="d-flex flex-column float-start">
                                                                <div class="form-check form-switch form-check-primary">
                                                                    <input type="checkbox" class="form-check-input" id="mailAuthCheck" value="mail_auth" <?= $users->email_auth == 1 ? 'checked' : '' ?> />
                                                                    <label class="form-check-label" for="mailAuthCheck">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <p class="todo-title">Mail Verification - Require mail
                                                                verification for every login when your IP address will
                                                                be changed.</p>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="title-wrapper">
                                                            <div class="d-flex flex-column float-start">
                                                                <div class="form-check form-switch form-check-primary">
                                                                    <input type="checkbox" class="form-check-input" id="googleAuthCheck" value="google_auth" <?= $users->g_auth == 1 ? 'checked' : '' ?> />
                                                                    <label class="form-check-label" for="googleAuthCheck">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <p class="todo-title">Google Authenticate - Need google
                                                                authenticate app where you will get security code for
                                                                login. </p>
                                                        </div>
                                                    </div>
                                                    <!-- google authenticator modal -->
                                                    <section class="panel-dark mt-5" id="google_auth_modal">
                                                        @php

                                                        use App\Services\GoogleAuthenticator;

                                                        $ga = new GoogleAuthenticator();
                                                        $secret = $ga->createSecret();
                                                        $qrCodeUrl = $ga->getQRCodeGoogleUrl(auth()->user()->email, $secret, config('app.name'));

                                                        @endphp
                                                        <header class="panel-heading bg-primary">
                                                            <h3 class="panel-title text-light mb-0">GOOGLE ATHENTICATOR
                                                            </h3>
                                                        </header>
                                                        <style>
                                                            .light-layout .card-bg-color {
                                                                background: #f6f6f6 !important;
                                                            }

                                                            .dark-layout .card-bg-color {
                                                                background: #161d31 !important;
                                                            }
                                                        </style>
                                                        <div class="panel-body card-bg-color">

                                                            <!-- google auth setup form -->
                                                            <form action="{{ route('admin.settings.security_setting.google_auth_set') }}" method="post" enctype="multipart/form-data" id="google_auth_setup_form">
                                                                @csrf
                                                                <input type="hidden" name="user_id" value="<?= auth()->user()->id ?>">
                                                                <ul class="row">
                                                                    <li class="col-sm-12 p-3">
                                                                        <div class="col-sm-1 staper"><span class="step">1</span></div>
                                                                        <div class="col-sm-5 step-title">
                                                                            <h6>Download 2 FA backup key</h6>
                                                                        </div>
                                                                        <div class="col-sm-6" style="float: left;">
                                                                            <div class="input-group has-validation">
                                                                                <input type="text" class="form-control" id="secret_key" name="secret_key" value="<?= $secret ?>" aria-describedby="secret_key" />
                                                                                <button type="button" class="input-group-text" data-clipboard-target="#secret_key" id="copy_secret_key">
                                                                                    <i data-feather='download'></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </li>


                                                                    <li class="col-sm-12 p-3" style="padding-top: 0px !important; padding-bottom: 0px !important;">
                                                                        <div class="col-sm-1 staper"><span class="step">2</span></div>
                                                                        <div class="col-sm-4 step-title">
                                                                            <h6>Download and Install</h6>
                                                                        </div>
                                                                        <div class="col-sm-7" style="float:left;">
                                                                            <div class="app-link">
                                                                                <a class="pb-1 inner-app-link" href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank"><img class="auth-app-logo app-logo1" src="{{ asset('admin-assets/images/iphone.png') }}" /></a>

                                                                                <a class="pb-1" style="float: right;" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank"><img class="auth-app-logo app-logo2" src="{{ asset('admin-assets/images/android.png') }}" /></a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="clr"></div>
                                                                    </li>
                                                                    <li class="col-sm-12 p-3">
                                                                        <div class="col-sm-1 staper"><span class="step">3</span></div>
                                                                        <div class="col-sm-11 step-title">
                                                                            <h6>Scan QR:</h6>
                                                                        </div>
                                                                    </li>
                                                                    <li class="col-sm-12 pl-3 pb-3">
                                                                        <div class="col-sm-1" style="float: left;">
                                                                            &nbsp;</div>
                                                                        <div class="col-sm-4" id="qrcode" style="float: left; height: 100%;">
                                                                            <img style="padding-left: 2rem;" src='<?= $qrCodeUrl ?>' />
                                                                        </div>
                                                                        <div class="col-sm-6 pt-2" style="float: left; padding-left:5%">
                                                                            <h6>Enter 2FA verification code form the app
                                                                            </h6>
                                                                            <div class="input-group mb-md">
                                                                                <button class="input-group-text">
                                                                                    <img class="app-input-logo" src="{{ asset('admin-assets/images/apple-brands.svg') }}" />
                                                                                </button>
                                                                                <input type="text" class="form-control" name="v_code" placeholder="Enter 2FA verification code form the app" />
                                                                                <button class="input-group-text">
                                                                                    <img class="app-input-logo" src="{{ asset('admin-assets/images/android-brands.svg') }}" />
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                                <div class="col-sm-12 mt-1" style="text-align: center;">
                                                                    <label class="form-label">&nbsp;</label>
                                                                    <div>
                                                                        <button type="button" class="btn btn-primary me-1 mb-4" id="googleAuthSetupBtn" onclick="_run(this)" data-el="fg" data-form="google_auth_setup_form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="googleAuthSetupCallBack" data-btnid="googleAuthSetupBtn">Save
                                                                            Change</button>
                                                                        <button type="reset" class="btn btn-outline-secondary mb-4">Reset</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </section>
                                                </div>
                                            </div>
                                        </div>
                                        <h4 class="header">Reset Transaction Password</h4>
                                        <hr>
                                        <p class="card-header" style="color:rgb(115,103,240)">Please click on this
                                            button. After that a new Transaction Password will be send to your mail !!
                                        </p>
                                        <form class="col-12" action="{{ route('admin.profile-settings.reset-transaction-pass') }}" method="post" id="form-sent-reset-mail">
                                            @csrf
                                            <div class="mb-1 row">
                                                <div class="col-sm-1"></div>
                                                <div class="col-sm-2">
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="d-flex justify-content-end pt-2">
                                                        <button class="btn btn-primary me-1 mb-4" type="button" id="create_transaction_pass" onclick="_run(this)" data-el="fg" data-form="form-sent-reset-mail" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="transaction_password_call_back" data-btnid="create_transaction_pass" style="width:180px">Sent Reset Mail</button>

                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <hr>
                                        <!-----------------------Tranasaciton Passsword Change here--------------------------->
                                        <h4 class="header">Change Transaction Password</h4>
                                        <form action="{{ route('admin.profile-settings.change-transaction-pass') }}" method="post" id="form-transaction-pass-change">
                                            @csrf
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-1"></div>
                                                    <div class="col-sm-2">
                                                        <label for="current_tran_pass" class="col-form-label">Current
                                                            Transaction Password<span class="text-danger">&#9734;</span></label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="password" class="form-control mt-1" name="current_tran_pass" id="current_tran_pass" placeholder="" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-1"></div>
                                                    <div class="col-sm-2">
                                                        <label class="col-form-label" for="new_tran_pass">New
                                                            Transaction Password</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="input-group input-group-merge mt-1">
                                                            <input type="password" id="new_tran_pass" data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" class="form-control" name="new_tran_pass" placeholder="Password" />
                                                            <button class="btn btn-primary waves-effect waves-float waves-light btn-gen-password" type="button" id="rstButton"><i class="fas fa-key"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-1"></div>
                                                    <div class="col-sm-2">
                                                        <label for="confirm_tran_pass" class="col-form-label">Confirm
                                                            Transaction Password</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="password" class="form-control mt-1" name="confirm_tran_pass" id="confirm_tran_pass" placeholder="" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-1"></div>
                                                    <div class="col-sm-2">
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="d-flex justify-content-end pt-2">
                                                            <button class="btn btn-primary" type="button" id="transaction_pass" onclick="_run(this)" data-el="fg" data-form="form-transaction-pass-change" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="change_transaction_pass_call_back" data-btnid="transaction_pass" style="width:180px">Save
                                                                Change</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!------------Security part end------------------->

                                    <!------------Change phone and Email------------------->
                                    <div class="tab-pane" id="messages-fill" role="tabpanel" aria-labelledby="messages-tab-fill">
                                        <!-- Modern Horizontal Wizard -->
                                        <section class="modern-horizontal-wizard">
                                            <div class="bs-stepper wizard-modern modern-wizard-example2">
                                                <div class="bs-stepper-header">
                                                    <div class="step" data-target="#account-details-modern2" role="tab" id="account-details-modern-trigger2">
                                                        <button type="button" class="step-trigger">
                                                            <span class="bs-stepper-box">
                                                                <i data-feather='at-sign' class="font-medium-3"></i>
                                                            </span>
                                                            <span class="bs-stepper-label">
                                                                <span class="bs-stepper-title">Change Email</span>
                                                                <span class="bs-stepper-subtitle">Request for change email</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="line">
                                                        <i data-feather="chevron-right" class="font-medium-2"></i>
                                                    </div>
                                                    <div class="step" data-target="#personal-info-modern2" role="tab" id="personal-info-modern-trigger2">
                                                        <button type="button" class="step-trigger">
                                                            <span class="bs-stepper-box">
                                                                <i data-feather="phone" class="font-medium-3"></i>
                                                            </span>
                                                            <span class="bs-stepper-label">
                                                                <span class="bs-stepper-title">Change phone</span>
                                                                <span class="bs-stepper-subtitle">Request for change phone</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="bs-stepper-content">
                                                    <div id="account-details-modern2" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger2">
                                                        <div class="content-header">
                                                            <h5 class="mb-0">Change email</h5>
                                                            <small class="text-muted">Request for change email.</small>
                                                        </div>
                                                        <form action="{{route('admin.email-change-otp')}}" method="post" class="row" id="form-mail-change-otp">
                                                            @csrf
                                                            <div class="mb-1 col-md-6">
                                                                <p>We sending an otp mail for verifiy its you. You need to check OTP verification with this email.</p>
                                                                <p>OTP Session should expire within 1 minutes. If OTP expired you need to resending the OTP</p>
                                                            </div>
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-otp-email">Email</label>
                                                                <input type="email" disabled id="modern-otp-email" class="form-control" placeholder="" aria-label="{{auth()->user()->name}}" value="{{auth()->user()->email}}" />
                                                                <button class="btn btn-success mt-1 float-end" type="button" data-file="true" onclick="_run(this)" data-el="fg" data-form="form-mail-change-otp" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="email_otp_callback" data-btnid="btn-send-email-otp" id="btn-send-email-otp">Send OTP</button>
                                                            </div>
                                                        </form>
                                                        <form class="row g-1" style="display: none;" id="form-email-change" action="{{route('admin.email-change')}}" method="post">
                                                            @csrf
                                                            <div class="mb-1 form-password-toggle col-md-6">
                                                                <h6>Type your 6 digit security code</h6>
                                                                <div class="auth-input-wrapper d-flex align-items-center justify-content-between">
                                                                    <input type="text" name="otp_1" class="form-control auth-input text-center numeral-mask mx-25 mb-1" maxlength="1" autofocus="" />

                                                                    <input type="text" name="otp_2" class="form-control auth-input text-center numeral-mask mx-25 mb-1" maxlength="1" />

                                                                    <input type="text" name="otp_3" class="form-control auth-input text-center numeral-mask mx-25 mb-1" maxlength="1" />

                                                                    <input type="text" name="otp_4" class="form-control auth-input text-center numeral-mask mx-25 mb-1" maxlength="1" />

                                                                    <input type="text" name="otp_5" class="form-control auth-input text-center numeral-mask mx-25 mb-1" maxlength="1" />

                                                                    <input type="text" name="otp_6" class="form-control auth-input text-center numeral-mask mx-25 mb-1" maxlength="1" />
                                                                </div>
                                                            </div>
                                                            <div class="mb-1 form-password-toggle col-md-6">
                                                                <label class="form-label" for="modern-resent-otp">Resend OTP</label>
                                                                <div class="input-group">
                                                                    <input type="text" name="otp" class="form-control" disabled placeholder="Button on right" aria-describedby="button-addon2" value="{{auth()->user()->email}}" />
                                                                    <button class="btn btn-outline-primary" type="button" data-file="true" onclick="_run(this)" data-el="fg" data-form="form-mail-change-otp" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="email_otp_callback" data-btnid="btn-send-email-otp2" id="btn-send-email-otp2" type="button">Resend</button>
                                                                </div>
                                                            </div>
                                                            <div class="mb-1 form-password-toggle col-md-6">
                                                                <label class="form-label" for="modern-new-email">New Email</label>
                                                                <input type="email" name="new_email" id="modern-new-email" class="form-control" />
                                                            </div>
                                                            <div class="mb-1 form-password-toggle col-md-6">
                                                                <label class="form-label" for="modern-confirm-new-email">Confirm New Email</label>
                                                                <input type="email" name="confirm_new_email" id="modern-confirm-new_email" class="form-control" />
                                                            </div>
                                                        </form>

                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-outline-secondary btn-prev" disabled>
                                                                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                            </button>
                                                            <button class="btn btn-primary" disabled type="button" data-file="true" onclick="_run(this)" data-el="fg" data-form="form-email-change" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="save_email_callback" data-btnid="btn-save-email-change" id="btn-save-email-change">
                                                                <span class="align-middle d-sm-inline-block d-none">Save Change</span>
                                                                <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div id="personal-info-modern2" class="content" role="tabpanel" aria-labelledby="personal-info-modern-trigger2">
                                                        <div class="content-header">
                                                            <h5 class="mb-0">Phone Change</h5>
                                                            <small>Phone change request.</small>
                                                        </div>
                                                        <form action="{{route('admin.phone-change-otp')}}" method="post" class="row" id="form-phone-change-otp">
                                                            @csrf
                                                            <div class="mb-1 col-md-6">
                                                                <p>We sending an otp mail for verifiy its you. You need to check OTP verification with this email.</p>
                                                                <p>OTP Session should expire within 1 minutes. If OTP expired you need to resending the OTP</p>
                                                            </div>
                                                            <div class="mb-1 col-md-6">
                                                                <label class="form-label" for="modern-otp-email">Email</label>
                                                                <input type="email" disabled id="modern-otp-email" class="form-control" placeholder="" aria-label="{{auth()->user()->name}}" value="{{auth()->user()->email}}" />
                                                                <button class="btn btn-success mt-1 float-end" type="button" data-file="true" onclick="_run(this)" data-el="fg" data-form="form-phone-change-otp" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="phone_otp_callback" data-btnid="btn-send-phone-otp" id="btn-send-phone-otp">Send OTP</button>
                                                            </div>
                                                        </form>

                                                        <form class="row g-1" style="display: none;" id="form-phone-change" action="{{route('admin.phone-change')}}" method="post">
                                                            @csrf
                                                            <div class="mb-1 form-password-toggle col-md-6">
                                                                <h6>Type your 6 digit security code</h6>
                                                                <div class="auth-input-wrapper d-flex align-items-center justify-content-between">
                                                                    <input type="text" name="otp_1" class="form-control auth-input text-center numeral-mask mx-25 mb-1" maxlength="1" autofocus="" />

                                                                    <input type="text" name="otp_2" class="form-control auth-input text-center numeral-mask mx-25 mb-1" maxlength="1" />

                                                                    <input type="text" name="otp_3" class="form-control auth-input text-center numeral-mask mx-25 mb-1" maxlength="1" />

                                                                    <input type="text" name="otp_4" class="form-control auth-input text-center numeral-mask mx-25 mb-1" maxlength="1" />

                                                                    <input type="text" name="otp_5" class="form-control auth-input text-center numeral-mask mx-25 mb-1" maxlength="1" />

                                                                    <input type="text" name="otp_6" class="form-control auth-input text-center numeral-mask mx-25 mb-1" maxlength="1" />
                                                                </div>
                                                            </div>
                                                            <div class="mb-1 form-password-toggle col-md-6">
                                                                <label class="form-label" for="modern-resent-otp">Resend OTP</label>
                                                                <div class="input-group">
                                                                    <input type="text" name="otp" class="form-control" disabled placeholder="Button on right" aria-describedby="button-addon2" value="{{auth()->user()->email}}" />
                                                                    <button class="btn btn-outline-primary" type="button" data-file="true" onclick="_run(this)" data-el="fg" data-form="form-mail-change-otp" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="phone_otp_callback" data-btnid="btn-send-phone-otp2" id="btn-send-phone-otp2" type="button">Resend</button>
                                                                </div>
                                                            </div>
                                                            <div class="mb-1 form-password-toggle col-md-6">
                                                                <label class="form-label" for="modern-new-email">New Phone</label>
                                                                <input type="email" name="new_phone" id="modern-new-email" class="form-control" />
                                                            </div>
                                                            <div class="mb-1 form-password-toggle col-md-6">
                                                                <label class="form-label" for="modern-confirm-new-phone">Confirm New Phone</label>
                                                                <input type="email" name="confirm_new_phone" id="modern-confirm-new-phone" class="form-control" />
                                                            </div>
                                                        </form>
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-primary btn-prev">
                                                                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                            </button>
                                                            <button class="btn btn-primary" disabled type="button" data-file="true" onclick="_run(this)" data-el="fg" data-form="form-phone-change" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="save_phone_callback" data-btnid="btn-save-phone-change" id="btn-save-phone-change">
                                                                <span class="align-middle d-sm-inline-block d-none">Save Change</span>
                                                                <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <!-- /Modern Horizontal Wizard -->
                                        <!--------------Change phone and email end----------------->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Activity Timeline -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">Vedio Player</h1>
                    </div>
                    <form id="editUserForm" class="row gy-1 pt-75" onsubmit="return false">
                        <video width="400" height="350" controls>
                            {{-- <source src="{{URL::asset("/vedios/pexels.mp4")}}" type="video/mp4"> --}}
                            <source src="{{ URL::asset('/vedios/pexels-ambientnatur.mp4') }}" type="video/mp4">
                            Your browser does not support HTML video.
                        </video>
                        <p>
                            Video courtesy of
                            <a href="https://www.bigbuckbunny.org/" target="_blank"></a>.
                        </p>
                        <div class="col-12 text-center">
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                Discard
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/ Edit User Modal -->
    <!-- END: Content-->
</div>    
    @stop
    <!-- BEGIN: vendor JS -->
    @section('vendor-js')
    @stop
    <!-- END: vendor JS -->
    <!-- BEGIN: Page vendor js -->
    @section('page-vendor-js')
    <script src="{{asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js')}}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>

    <script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
    <script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
    <script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
    <script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
    <script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
    <!-- datatable -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}">
    </script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
    <!-- datatable -->
    <!-- number input -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js') }}"></script>
    <!-- js tree -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/jstree.min.js') }}"></script>
    @stop
    <!-- END: page vendor js -->
    <!-- BEGIN: page JS -->
    @section('page-js')
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/finance-balance.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('/common-js/copy-js.js') }}"></script>
    <script src="{{ asset('/common-js/password-gen.js') }}"></script>
    <script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js')}}"></script>
    <script src="{{asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
    <script src="{{asset('admin-assets/app-assets/js/scripts/pages/auth-two-steps.js')}}"></script>
    <script>
        // update admin profile
        // --------------------------------------------------------------------------------------------
        // submit_wait("#save-wallet-balance");
        function account_details_callback(data) {
            if (data.status == true) {
                notify('success', data.message, 'Admin Account Details')
            }

            if (data.status == false) {
                notify('error', data.message, 'Admin Account Details')
            }
            $.validator("account-details-modern", data.errors);
        }

        function address_callback(data) {
            if (data.status == true) {
                notify('success', data.message, 'Admin address')
            }

            if (data.status == false) {
                notify('error', data.message, 'Admin address')
            }
            $.validator("address-step-modern", data.errors);
        }

        function personal_callback(data) {
            if (data.status == true) {
                notify('success', data.message, 'Admin personal info')
            }

            if (data.status == false) {
                notify('error', data.message, 'Admin personal info')
            }
            $.validator("personal-info-modern", data.errors);
        }
        // update social link
        function social_info_callback(data) {
            if (data.status == true) {
                notify('success', data.message, 'Admin social links');
            }

            if (data.status == false) {
                notify('error', data.message, 'Admin social links');
            }
            $.validator("social-links-modern", data.errors);
        }
        // sending email otp
        function email_otp_callback(data) {
            if (data.status == true) {
                notify('success', data.message, 'Email change OTP');
                $("#form-email-change").slideDown();
                $("#form-mail-change-otp").slideUp();
                $('#btn-save-email-change').prop('disabled', false);
            }

            if (data.status == false) {
                notify('error', data.message, 'Email change OTP');
            }
            $.validator("social-links-modern", data.errors);
        }
        // phone change otp code
        function phone_otp_callback(data) {
            if (data.status == true) {
                notify('success', data.message, 'Email change OTP');
                $("#form-phone-change").slideDown();
                $("#form-phone-change-otp").slideUp();
                $('#btn-save-phone-change').prop('disabled', false);
            }

            if (data.status == false) {
                notify('error', data.message, 'Email change OTP');
            }
            $.validator("social-links-modern", data.errors);
        }
        // chagne email
        function save_email_callback(data) {
            if (data.status == true) {
                notify('success', data.message, 'Admin email change');
                $("#form-email-change").trigger('reset');
            }

            if (data.status == false) {
                notify('error', data.message, 'Admin email change');
            }
            $.validator("form-email-change", data.errors);
        }
        // change phone
        function save_phone_callback(data) {
            if (data.status == true) {
                notify('success', data.message, 'Admin email change');
                $("#form-phone-change").trigger('reset');
            }

            if (data.status == false) {
                notify('error', data.message, 'Admin email change');
            }
            $.validator("form-phone-change", data.errors);
        }
        $("#form-email-change").trigger('reset');
        $("#form-phone-change").trigger('reset');
        //admin sent mail call back function
        function sent_mail_callback(data) {
            // alert('asdfsadfsda');
            if (data.status == true) {
                toastr['success'](data.message, 'Admin Email', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                });
            }
        }

        function sent_phone_callback(data) {
            if (data.status == true) {
                toastr['success'](data.message, 'Admin Phone', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                });
            }
        }
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

        function transaction_password_call_back(data) {
            $('#create_transaction_pass').prop('disabled', false);
            if (data.status == true) {

                toastr['success'](data.message, 'Transaciton Password', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                });
            }
        }

        function change_transaction_pass_call_back(data) {
            $('#transaction_pass').prop('disabled', false);
            if (data.status == false) {
                toastr['error'](data.message, 'Transaciton Password', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                });
            }
            if (data.status == true) {

                toastr['success'](data.message, 'Transaciton Password', {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                });
            }

            $.validator("form-transaction-pass-change", data.errors);
        }
    </script>



    <!-- security setting js start -->
    <!-- admin settings common ajax -->
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/admin-settings.js') }}"></script>
    <script>
        // secret key copy script start
        $(document).on('click', '#copy_secret_key', function() {
            var clipboardText = "";
            clipboardText = $('#secret_key').val();
            copyToClipboard(clipboardText);
            notify('success', "Copied To Clipboard", 'Secret Key');

        });

        function copyToClipboard(text) {
            var sampleTextarea = document.createElement("textarea");
            document.body.appendChild(sampleTextarea);
            sampleTextarea.value = text; //save main text in it
            sampleTextarea.select(); //select textarea contenrs
            document.execCommand("copy");
            document.body.removeChild(sampleTextarea);
        }
        // secret key copy script end

        if ($('#googleAuthCheck[type="checkbox"]')) {
            if ($('#googleAuthCheck').prop("checked") == true) {
                $('#google_auth_modal').show();
            } else if ($('#googleAuthCheck').prop("checked") == false) {
                $('#google_auth_modal').hide();
            }
        }
        // check or uncheck property
        // no auth
        $('#noAuthCheck[type="checkbox"]').click(function() {
            if ($(this).is(":checked")) {
                $('#noAuthCheck').prop('checked', true);
                $('#mailAuthCheck').prop('checked', false);
                $('#googleAuthCheck').prop('checked', false);
            } else if ($(this).is(":not(:checked)")) {
                $('#noAuthCheck').prop('checked', false);
                $('#mailAuthCheck').prop('checked', false);
                $('#googleAuthCheck').prop('checked', false);
            }
        });
        // mail auth
        $('#mailAuthCheck[type="checkbox"]').click(function() {
            if ($(this).is(":checked")) {
                $('#noAuthCheck').prop('checked', false);
                $('#mailAuthCheck').prop('checked', true);
                $('#googleAuthCheck').prop('checked', false);
            } else if ($(this).is(":not(:checked)")) {
                $('#noAuthCheck').prop('checked', false);
                $('#mailAuthCheck').prop('checked', true);
                $('#googleAuthCheck').prop('checked', false);
            }
        });
        // google auth
        $('#googleAuthCheck[type="checkbox"]').click(function() {
            if ($(this).is(":checked")) {
                $('#noAuthCheck').prop('checked', false);
                $('#mailAuthCheck').prop('checked', false);
                $('#googleAuthCheck').prop('checked', true);
                $('#google_auth_modal').show();
            } else if ($(this).is(":not(:checked)")) {
                $('#noAuthCheck').prop('checked', false);
                $('#mailAuthCheck').prop('checked', false);
                $('#googleAuthCheck').prop('checked', false);
                $('#google_auth_modal').hide();
            }
        });

        // no auth
        $(document).on('change', '#noAuthCheck', function(event) {
            $('#google_auth_modal').hide();
            let check_auth = $('#noAuthCheck').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/settings/security_setting/update/' + check_auth,
                method: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    if (data.success) {
                        notify('success', data.message, 'Authentication');
                        $('#noAuthCheck').prop('checked', true);
                        $('#mailAuthCheck').prop('checked', false);
                        $('#googleAuthCheck').prop('checked', false);
                    } else {
                        notify('error', data.message, 'Authentication');
                    }
                }
            });
        });
        // mail auth
        $(document).on('click', '#mailAuthCheck', function(event) {
            $('#google_auth_modal').hide();
            let check_auth = $('#mailAuthCheck').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/settings/security_setting/update/' + check_auth,
                method: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    if (data.success) {
                        notify('success', data.message, 'Authentication');
                        $('#noAuthCheck').prop('checked', false);
                        $('#mailAuthCheck').prop('checked', true);
                        $('#googleAuthCheck').prop('checked', false);
                    } else {
                        notify('error', data.message, 'Authentication');
                    }
                }
            });
        });

        // google auth setup callback
        function googleAuthSetupCallBack(data) {
            $('#googleAuthSetupBtn').prop('disabled', false);
            if (data.success) {
                notify('success', data.message, 'Google Authentication');
                $('#noAuthCheck').prop('checked', false);
                $('#mailAuthCheck').prop('checked', false);
                $('#googleAuthCheck').prop('checked', true);
            } else {
                notify('error', data.message, 'Google Authentication');
            }
        }
    </script>
    <!-- security setting js end -->
    @stop
    <!-- BEGIN: page JS -->