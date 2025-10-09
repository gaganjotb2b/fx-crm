@extends('layouts.system-layout')
@section('title','IB Commission Structure Replace')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css')}}">
<!-- number input -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/assets/css/config-form.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css')}}">
<style>
    .al-col-m {
        transition: 1s;
    }

    @media only screen and (max-width: 400px) {
        .al-col-m {
            flex: 0 0 auto;
            width: 100%;
        }
    }

    /* .details-section-dark.dt-details.border-start-3.border-start-primary.p-2 {
        padding: 0px !important;
    } */
</style>
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
                        <h2 class="content-header-title float-start mb-0">IB Commission Structure</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item ">IB Commission Structure Replace</li>
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
            <!-- Modern Horizontal Wizard -->
            <section class="modern-horizontal-wizard vertical-wizard">
                <div class="bs-stepper wizard-modern modern-wizard-example">
                    <div class="bs-stepper-header bg-light-primary">
                        <!-- step crm setup -->
                        <div class="step mt4" data-target="#account-details-modern" role="tab" id="account-details-modern-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="file-text" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">First Level Structure</span>
                                    <span class="bs-stepper-subtitle">Setup First Level Structure Configuration</span>
                                </span>
                            </button>
                        </div>
                        <div class="line">
                            &nbsp;
                        </div>
                        <!-- required fields -->
                        <div class="step mt5" data-target="#personal-info-modern" role="tab" id="personal-info-modern-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">
                                    <i data-feather="user" class="font-medium-3"></i>
                                </span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Custom Structure</span>
                                    <span class="bs-stepper-subtitle">Setup Custom Structure Configuration</span>
                                </span>
                            </button>
                        </div>
                        <div class="line">
                            &nbsp;
                        </div>
                    </div>
                    <!-- bs stepper content -->

                    <div class="bs-stepper-content">
                        <!-- CRM Ddfault setup -->
                        <!-- stepper content -->
                        <div id="account-details-modern" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger">
                            <div class="content-header">
                                <h5 class="mb-0">First Level Configuration</h5>
                                <small class="text-muted">Enter your First Level info.</small>
                            </div>
                            <!-- Start vertical wizard -->
                            <section class="vertical-wizard">
                                <div class="bs-stepper vertical vertical-wizard-example shadow-none">
                                    <div class="bs-stepper-content shadow-none">
                                        <!-- First Level form start -->
                                        <div id="account-details-vertical" class="content live-server-details" role="tabpanel" aria-labelledby="account-details-vertical-trigger">
                                            <div class="row">
                                                <!-- ------------------------------------------------------------------------
                                                |                       First Level Form Start Here
                                                ----------------------------------------------------------------------------->
                                                <form action="{{route('system.ib-commission-structure-replace.store')}}" method="post" id="first_level_form">
                                                    @csrf
                                                    <!-- Dynamic field start -->
                                                    <div class="card d-flex">
                                                        <div class="card-header justify-content-start">
                                                            <div class="mb-1" style="width:300px; margin-right:50px" data-bs-toggle="tooltip" data-bs-placement="top" title="Select Trader Group">
                                                                <div class="">
                                                                    <label for="client_group" class="col-form-label">{{ __('ib-management.Select Trader Group') }}</label>
                                                                    <select class="select2 form-select" id="client_group" name="client_group">
                                                                        @foreach($clientGroup as $groupId => $groupName)
                                                                        <option value="{{ $groupId }}">{{ $groupName }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            @foreach($ibGroup as $groupName => $groupId)
                                                            <div class="custom-options-checkable me-2">
                                                                <div class="">
                                                                    <input class="custom-option-item-check" type="radio" name="ib_group" id="{{$groupName}}" value="{{$groupId}}" />
                                                                    <label class="custom-option-item p-1" for="{{$groupName}}">
                                                                        <span class="d-flex justify-content-between flex-wrap mb-50 text-center">
                                                                            <span class="fw-bolder">{{$groupName}}</span>
                                                                            <span class="fw-bolder"></span>
                                                                        </span>
                                                                        <small class="d-block"></small>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <!-- Dynamic field end -->
                                                    <!-- ------------------------------------------------------------------------
                                                    |                      First level Form Container
                                                    ----------------------------------------------------------------------------->
                                                    <!-- This is hidden input -->
                                                    <div class="d-none">
                                                        <div class="col-2" id="input_container">
                                                            <div class="" id="field_title">Level 1</div>
                                                            <div class="input-group mb-1 touchspin-wrapper w-100 mt-1">
                                                                <input type="text" id="input_field" class="touchspin-color ib-levels p-75" value="0" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" data-bts-decimals="2" data-bts-step="0.01" min="0" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- //End -->
                                                    <div class="row" id="first_level_form_container">
                                                        @php
                                                        $levelNum = $level;
                                                        @endphp
                                                        @for($i = 1; $i <= $levelNum; $i++) <div class="col-2" id="input_container">
                                                            <div class="" id="field_title">Level {{ $i }}</div>
                                                            <div class="input-group mb-1 touchspin-wrapper w-100 mt-1">
                                                                <input type="text" id="input_field_{{ $i }}" name="input_field[]" class="touchspin-color ib-levels p-75" value="0" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" data-bts-decimals="2" data-bts-step="0.01" min="0" />
                                                            </div>
                                                    </div>
                                                    @endfor
                                            </div>
                                            <div id="loader" data-loader="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"></div>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-primary" type="button" id="first_level_btn" data-form="first_level_form" data-loader="loader">
                                                <i data-feather='save' class="ib-com-save-icon"></i>
                                                    <span class="align-middle d-sm-inline-block d-none">Save</span>
                                                </button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- //End live server form -->
                                </div>
                        </div>
            </section>
            <!-- //End Vertical Wizard -->
        </div>
        <!-- Filed required -->
        <!-- ------------------------------------------------------------------------
        |                      Custom Structure Section
        ----------------------------------------------------------------------------->
        <!-- stepper content / 2nd step -->
        <div id="personal-info-modern" class="content" role="tabpanel" aria-labelledby="personal-info-modern-trigger">
            <div class="content-header">
                <h5 class="mb-0">Custom Structure Configuration</h5>
                <small class="text-muted">Enter your Custom Structure info.</small>
            </div>
            <div class="bs-stepper vertical vertical-wizard-example shadow-none">
                <div class="bs-stepper-content shadow-none">
                    <!-- Cusrom Structure form start -->
                    <div id="account-details-vertical" class="content live-server-details" role="tabpanel" aria-labelledby="account-details-vertical-trigger">
                        <div class="row">
                            <!-- ------------------------------------------------------------------------
                            |                       Cusrom Structure Form Start Here
                            ----------------------------------------------------------------------------->
                            <form action="{{route('system.ib-commission-structure-replace.customStructureStore')}}" method="post" id="custom_structure_form">
                                @csrf
                                <!-- Dynamic field start -->
                                <div class="card d-flex">
                                    <div class="card-header justify-content-start">
                                        <div class="mb-1" style="width:300px; margin-right:50px" data-bs-toggle="tooltip" data-bs-placement="top" title="Select Trader Group">
                                            <div class="">
                                                <label for="client_group" class="col-form-label">{{ __('ib-management.Select Trader Group') }}</label>
                                                <select class="select2 form-select" id="client_group" name="client_group">
                                                    @foreach($clientGroup as $groupId => $groupName)
                                                        <option value="{{ $groupId }}">{{ $groupName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @foreach($ibGroup as $groupName => $groupId)
                                        <div class="custom-options-checkable me-2">
                                            <div class="">
                                                <input class="custom-option-item-check" type="radio" name="ib_group" id="{{$groupName.'_cus'}}" value="{{$groupId}}" />
                                                <label class="custom-option-item p-1" for="{{$groupName.'_cus'}}">
                                                    <span class="d-flex justify-content-between flex-wrap mb-50 text-center">
                                                        <span class="fw-bolder">{{$groupName}}</span>
                                                        <span class="fw-bolder"></span>
                                                    </span>
                                                    <small class="d-block"></small>
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Dynamic field end -->

                                @php
                                    $levelNum1 = $level-1;
                                @endphp
                                @for ($i = $levelNum1; $i >= 1; $i--)
                                <div class="row">
                                    @php
                                        $levelNum2 = $i;
                                    @endphp
                                    @for ($j = 1; $j <= $levelNum2; $j++) <div class="col-2" id="input_container">
                                        <div class="" id="field_title">Level {{ $j }}</div>
                                        <div class="input-group mb-1 touchspin-wrapper w-100 mt-1">
                                            <input type="text" id="input_field_{{ $j }}" name="input_field[]" class="touchspin-color ib-levels p-75" value="0" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" data-bts-decimals="2" data-bts-step="0.01" min="0" />
                                        </div>
                                </div>
                                @endfor
                        </div>
                        @endfor
                        <div id="loader" data-loader="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"></div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="button" id="custom_structure_btn" data-form="custom_structure_form" data-loader="loader">
                                <i data-feather='save' class="ib-com-save-icon"></i>
                                <span class="align-middle d-sm-inline-block d-none">Save</span>
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
                <!-- //End live server form -->
            </div>
        </div>
    </div>

</div>
</div>
</section>
<!-- /Modern Horizontal Wizard -->
</div>
</div>
</div>
@stop
<!-- BEGIN: vendor JS -->

<!-- BEGIN: vendor JS -->
@section('vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js')}}"></script>
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
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- toaster js -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/system-page-configuration.js')}}"></script>
<!-- form hide/show -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-config-form.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{asset('common-js/rz-plugins/z-datatable.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/ib-commission-structure.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-number-input.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
<script src="{{asset('common-js/rz-plugins/rz-ajax.js')}}"></script>
<script>
    $(document).ready(function() {
        $("#account-details-vertical-trigger").addClass('active');
        $(".live-server-details").addClass('dstepper-block');
        $(".live-server-details").css({
            "display": "block",
            "visibility": "visible",
        });
        // ************  Custom Structure -->This is copied from MT5
        $(".mt5").click(function() {
            $("#manager-details-vertical-trigger").addClass('active');
            $("#web-info-vertical-trigger").removeClass('active');
            $("#web-demo-info-vertical-trigger").removeClass('active');
            $("#manager-demo-details-vertical-trigger").removeClass('active');
            $("#manager-demo-details-vertical").hide();
            $("#manager-details-vertical").css({
                "display": "block",
                "visibility": "visible"
            });
            $("#web-info-vertical").css({
                "display": "none",
                "visibility": "hidden"
            });
            $("#web-demo-info-vertical").css({
                "display": "none",
                "visibility": "hidden"
            });

            $("#web_wizard").css({
                "display": "none",
                "visibility": "hidden"
            });
        });

        // First Level Structure Form Submit
        $('#first_level_btn').form_submit({
            form_id:"first_level_form",
            title: 'First Level Structure'
        },function(data){
            console.log(data);
        });

        //Custom Level Structure Form Submit
        $('#custom_structure_btn').form_submit({
            form_id:"custom_structure_form",
            title:'Custom Structure'
        },function(data){
            console.log(data);
        });

    });

    
</script>
<script>

</script>
@stop
<!-- BEGIN: page JS -->