@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Create Contest')
@section('vendor-css')

<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/calendars/fullcalendar.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
@stop

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/pages/app-calendar.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/wizard/bs-stepper.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-wizard.css')}}">
<style>
    /* input.form-control.filter-num {
        margin-top: 10px;
    } */

    /* #editor-container {
        height: 375px;
    } */
</style>
@stop
<!-- BEGIN: content -->
@section('content')
<!-- BEGIN: Content-->
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Update Reward</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('group-setting.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item active">Reward
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
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <!-- Modern Horizontal Wizard -->
                    <section class="modern-horizontal-wizard">
                        <div class="bs-stepper wizard-modern modern-wizard-example">
                            <div class="bs-stepper-header d-none" id="head_remove">
                                <div class="step" data-target="#account-details-modern" role="tab" id="account-details-modern-trigger">
                                    <!-- for trader -->
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="file-text" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Trader</span>
                                            <span class="bs-stepper-subtitle">For trader clients</span>
                                        </span>
                                    </button>
                                </div>
                                <div class="line">
                                    &nbsp;
                                </div>
                                <div class="step" data-target="#personal-info-modern" role="tab" id="personal-info-modern-trigger">
                                    <!-- for IB -->
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="user" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">IB</span>
                                            <span class="bs-stepper-subtitle">For IB Clients</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="bs-stepper-content">
                                <!-- for trader -->
                                <form id="account-details-modern" action="{{route('admin.reward.update', ['id' => $reward->id])}}" method="put" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="text_quill" name="description">
                                    <!-- <textarea name="comment" style="display: none;" id="text_quill"></textarea> -->
                                    <div class="content-header">
                                        <h5 class="mb-0">For Trader</h5>
                                        <small class="text-muted">Enter info amd complete setup.</small>
                                    </div>
                                    <div class="row">
                                        <!-- contest name -->
                                        <div class="mb-1 col-md-3">
                                            <label class="form-label" for="contest-name">Reward name</label>
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="edit-3"></i></span>
                                                <input 
                                                    type="test" 
                                                    id="contest-name"
                                                    class="form-control"
                                                    name="reward_name" 
                                                    placeholder="Reward Package Name"
                                                    value="{{ $reward->name }}"
                                                     
                                                />
                                            </div>
                                        </div>

                                        <div class="mb-1 col-md-3">
                                            <label class="form-label" for="contest-name">Reward Anount</label>
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="edit-3"></i></span>
                                                <input 
                                                    type="test" 
                                                    id="reward_amount"
                                                    class="form-control" 
                                                    name="reward_amount" 
                                                    placeholder="Reward Amount" 
                                                    value="{{ $reward->amount }}"
                                                />
                                            </div>
                                        </div>
                                    
                                        <div class="mb-1 col-md-3">
                                            <label for="require-is_admin">Is Admin</label>
                                            <div class="title-wrapper d-flex">
                                                <div class="d-flex flex-column float-start">
                                                    <div class="form-check form-switch form-check-primary">
                                                        <input type="checkbox" class=" password_settings form-check-input" id="is_admin" name="is_admin"  {{ $reward->is_admin == 1 ? 'checked' : '' }}/>
                                                    </div>
                                                </div>
                                                <label for="require-is_admin" class="cursor-pointer">Admin Reward</label>
                                            </div>
                                        </div>

                                        <div class="mb-1 col-md-3" id="search_client" @if(isset($reward) && $reward->is_admin) style="display: none;" @endif>
                                            {{-- <label class="form-label" for="contest-name">Customer</label> --}}
                                            {{-- <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="edit-3"></i></span>
                                                <input type="test" id="reward_amount" class="form-control" name="reward_amount" placeholder="Reward Amount" />
                                            </div> --}}
                                            <label for="require-is_admin">Select Client</label>
                                            <select name="client_id" id="userSelect" class="select2 form-select">
                                                @if(isset($client))
                                                    <option value="{{ $client->id }}" selected>{{ $client->name }}</option>
                                                @else
                                                    <option value="">Choose a Client</option>
                                                @endif
                                            </select>
                                        </div>
                                    
                                    </div>
                                    <div class="row">
                                        <!-- client group -->
                                        <div class="col-md-6 mb-1">
                                            <label for="client-group">Client Group</label>
                                            <select name="group" id="client-group" class="select2 form-select">
                                                <option value="">Choose a group</option>
                                                @foreach($groups as $value)
                                                <option value="{{$value->id}}">{{$value->group_id}}</option>
                                                @endforeach
                                            </select>
                                            <!-- Selected Groups -->
                                            <div id="selected-groups">
                                                @foreach($selectedGroups as $key=>$value)
                                                <span id="{{$key}}" class="badge bg-primary me-1 mt-1">
                                                    {{$value}}
                                                    <span type="button" class="ms-2 cursor-pointer text-white delete-group" data-id="{{$key}}">x</span>
                                                </span>
                                                
                                                @endforeach


                                            </div>
                                            <input type="hidden" name="client_groups" id="selected-groups-input" value="{{$storedGroups}}">

                                        </div>
                                        <!-- require KYC -->
                                        <div class="mb-1 form-password-toggle col-md-6">
                                            <label for="require-kyc">KYC</label>
                                            <div class="title-wrapper d-flex">
                                                <div class="d-flex flex-column float-start">
                                                    <div class="form-check form-switch form-check-primary">
                                                        <input type="checkbox" class=" password_settings form-check-input" id="require-kyc" name="kyc" {{ $reward->is_kyc == 1 ? 'checked' : '' }} />
                                                    </div>
                                                </div>
                                                <label for="require-kyc" class="cursor-pointer">Require KYC ?</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        {{-- <!-- contest type -->
                                        <div class="col-md-6 mb-1">
                                            <label for="contest-type">Contest type</label>
                                            <select name="contest_type" id="contest-type" class="form-select select2">
                                                <option value="on_profit">Contest on profit</option>
                                                <!-- <option value="on_profit_ratio">Contest on profit ratio</option> -->
                                                <option value="on_profit_lot">Contest on lot</option>
                                            </select>
                                        </div> --}}

                                        <div class="col-md-6 mb-1">
                                            <label for="popup-image">Popup Image</label>
                                            <input type="file" name="popup_image" id="popup-image" class="form-input form-control" value="{{$reward->popup_img}}">
                                        </div>
                                        <!-- country /is global-->
                                        <div class="col-md-3 mb-1">
                                            <label for="contest-country">Country</label>
                                            <div class="title-wrapper d-flex">
                                                <div class="d-flex flex-column float-start">
                                                    <div class="form-check form-switch form-check-primary">
                                                        <input type="checkbox" id="is_global" class="form-check-input is_global" name="is_global" value="1" {{ $reward->is_global == 1 ? 'checked' : '' }} />
                                                    </div>
                                                </div>
                                                <label for="is_global" class="cursor-pointer">Is Global ?</label>
                                            </div>
                                        </div>
                                        <!-- country selects -->
                                        <div class="col-md-3 mb-1" id="trader-country-wrapper">
                                            <select class="form-select select2" name="countries[]" placeholder="select country" multiple="multiple" id="contest-country">
                                                <option value="">Select Country</option>
                                                
                                                {{-- @foreach ($countries as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                                @endforeach --}}


                                                @foreach ($countries as $key => $value)
                                                    <option value="{{ $value->id }}" >
                                                        {{ $value->name }}
                                                    </option>
                                                @endforeach
                                            
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <!-- date range -->
                                        <div class="col-md-12 mb-1">
                                            <label for="date-range">Date Range</label>
                                            <div class="d-flex flex-column">
                                                <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Date" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
                                                    <span class="input-group-text">
                                                        <div class="icon-wrapper">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                                            </svg>
                                                        </div>
                                                    </span>
                                                    <input id="from" type="text" name="from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" value="{{$reward->start_date}}">
                                                    <span class="input-group-text">to</span>
                                                    <input id="to" type="text" name="to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" value="{{$reward->end_date}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Dynamic Rows Container -->
                                    <div id="dynamic-rows-container">
                                        @foreach($rewardDependencies as $dependency)
                                            <div class="row mb-2 dynamic-row">
                                                <div class="col-md-4">
                                                    <select class="form-select option-select">
                                                        <option value="">Choose an option</option>
                                                        @foreach($options as $key => $value)
                                                            <option value="{{ $key }}" {{ $dependency['type'] == $key ? 'selected' : '' }}>
                                                                {{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control value-input" placeholder="Enter value"
                                                        value="{{ $dependency['value'] }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Add New Row Button -->
                                    <button type="button" id="add-row" class="btn btn-primary my-2">Add Dependency</button>

                                    <!-- Hidden Input to Store Data -->
                                    <input type="hidden" name="dependencies" id="stored-data" value='@json($rewardDependencies)'>
                                    <!-- editor -->
                                    <div class="row mb-2 mb-5">
                                        <div class="col-md-12">
                                            <div id="editor-container">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>&nbsp;</span>
                                        <button class="btn btn-primary" type="button" data-file="true" id="btn-save-trader-contest" data-btnid="btn-save-trader-contest" onclick="_run(this)" data-el="fg" data-form="account-details-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="trader_contest_callback">
                                            <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Save</span>
                                        </button>
                                    </div>
                                </form>
                                <!-- IB AREA -->
                                <form id="personal-info-modern" action="{{route('admin.create.ib-contest')}}" method="post" enctype="multipart/form-data" class="content" role="tabpanel" aria-labelledby="personal-info-modern-trigger">
                                    @csrf
                                    <input type="hidden" id="text_quill_ib" name="description">
                                    <div class="content-header">
                                        <h5 class="mb-0">For IB</h5>
                                        <small class="text-muted">Enter info amd complete setup.</small>
                                    </div>
                                    <div class="row">
                                        <!-- contest name /IB -->
                                        <div class="mb-1 col-md-12">
                                            <label class="form-label" for="contest-name-ib">Contest name</label>
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="edit-3"></i></span>
                                                <input type="test" id="contest-name-ib" class="form-control" name="contest_name" placeholder="Contest Package Name" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- clients /IB -->
                                        <div class="mb-1 form-password-toggle col-md-6">
                                            <label class="form-label" for="contest-client-ib">Clients</label>
                                            <select name="client" id="contest-client-ib" class="form-select select2">
                                                <option value="all_clients">For all clients</option>
                                                <option value="new_registration">For new registration</option>
                                                <option value="new_accounts">For new accounts</option>
                                            </select>
                                        </div>
                                        <!-- require KYC /IB-->
                                        <div class="mb-1 form-password-toggle col-md-6">
                                            <label for="require-kyc-ib">KYC</label>
                                            <div class="title-wrapper d-flex">
                                                <div class="d-flex flex-column float-start">
                                                    <div class="form-check form-switch form-check-primary">
                                                        <input type="checkbox" class=" password_settings form-check-input" id="require-kyc-ib" name="kyc" value="1" />
                                                    </div>
                                                </div>
                                                <label for="require-kyc-ib" class="cursor-pointer">Require KYC ?</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- contest type /IB-->
                                        <div class="col-md-6 mb-1">
                                            <label for="contest-type-ib">Contest type</label>
                                            <select name="contest_type" id="contest-type-ib" class="form-select select2">
                                                <option value="on_profit">Contest on profit</option>
                                                <option value="on_profit_ratio">Contest on profit ratio</option>
                                                <option value="on_profit_lot">Contest on lot</option>
                                            </select>
                                        </div>
                                        <!-- country /is global /IB-->
                                        <div class="col-md-3 mb-1">
                                            <label for="contest-country-ib">Country</label>
                                            <div class="title-wrapper d-flex">
                                                <div class="d-flex flex-column float-start">
                                                    <div class="form-check form-switch form-check-primary">
                                                        <input type="checkbox" id="is_global_ib" class="form-check-input is_global" name="is_global" value="1" checked />
                                                    </div>
                                                </div>
                                                <label for="is_global_ib" class="cursor-pointer">Is Global ?</label>
                                            </div>
                                        </div>
                                        <!-- country selects /IB-->
                                        <div class="col-md-3 mb-1" id="ib-country-wrapper">
                                            <select class="form-select select2" name="countries[]" placeholder="select country" multiple="multiple" id="contest-country-ib">
                                                <option value="">Select Country</option>
                                                @foreach ($countries as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- IB group -->
                                        <div class="col-md-6 mb-1">
                                            <label for="client-group-ib">IB Group</label>
                                            <select name="group" id="client-group-ib" class="select2 form-select">
                                                <option value="">Choose a group</option>
                                                @foreach($ib_group as $value)
                                                <option value="{{$value->id}}">{{$value->group_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- credit type -->
                                        <div class="col-md-6 mb-1">
                                            <label for="popup-image-ib">Popup Image</label>
                                            <input type="file" name="popup_image" id="popup-image-ib" class="form-input form-control">
                                        </div>
                                    </div>
                                    <!-- expire after /IB-->
                                    <div class="row">
                                        <!-- expire after -->
                                        <div class="col-md-6 mb-1">
                                            <label for="expire-after-ib">Expire after</label>
                                            <input type="text" name="expire_after" class="form-control" id="expire-after-ib">
                                        </div>
                                        <!-- expire /IB-->
                                        <div class="col-md-6 mb-1">
                                            <label for="expire-ib">&nbsp;</label>
                                            <select name="expire_type" id="expire-ib" class="form-select select2">
                                                <option value="days">Days</option>
                                                <option value="months">Months</option>
                                                <option value="years">Years</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <!-- maximum contest /IB-->
                                        <div class="col-md-6 mb-1">
                                            <label for="max-contest-ib">Maximum contest</label>
                                            <input type="text" id="max-contest-ib" placeholder="0" class="form-control" name="maximum_contest">
                                        </div>
                                        <!-- minimum contest /IB-->
                                        <div class="col-md-6 mb-1">
                                            <label for="min-join-ib">Minimum Join</label>
                                            <input type="text" class="form-control" id="min-contest-ib" name="minimum_join" placeholder="0">
                                        </div>
                                    </div> --}}
                                    <div class="row">
                                        <!-- date range /IB-->
                                        <div class="col-md-12 mb-1">
                                            <label for="date-range-ib">Date Range</label>
                                            <div class="d-flex flex-column">
                                                <div class="input-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Enter Date" data-date="2017/01/01" data-date-format="yyyy/mm/dd">
                                                    <span class="input-group-text">
                                                        <div class="icon-wrapper">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                                            </svg>
                                                        </div>
                                                    </span>
                                                    <input id="from-ib" type="text" name="from" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                    <span class="input-group-text">to</span>
                                                    <input id="to-ib" type="text" name="to" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-1 row create-container">
                                        <div class="col-md-8">
                                            <label>Prize</label>
                                            <div class="row" id="prize-row">
                                                <!-- prize  /IB-->
                                                <div class="col-md-6">
                                                    <input class="form-control mb-1 label" value="1st prize" type="text" name="level[]" />
                                                </div>
                                                <!-- value /IB-->
                                                <div class="col-md-6 second-col d-flex">
                                                    <input class="form-control mb-1" placeholder="Value" type="text" name="amount[]" />
                                                </div>
                                            </div>
                                            <div class="target-row">

                                            </div>
                                        </div>
                                        <div class=" col-md-4">
                                            <button type="button" id="ib-btn-add-prize" class="add_field_button btn btn-success btn-md pull-right mt-2">Add More prize Field</button>
                                        </div>
                                    </div>
                                    <!-- editor /IB-->
                                    <div class="row mb-2 mb-5">
                                        <div class="col-md-12">
                                            <div id="editor-container-ib">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- submit buttons /IB-->
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-primary btn-prev" type="button">
                                            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-primary btn-next" type="button" data-file="true" data-btnid="btn-save-ib-contest" id="btn-save-ib-contest" onclick="_run(this)" data-el="fg" data-form="personal-info-modern" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="ib_contest_callback">
                                            <i data-feather="save" class="align-middle ms-sm-25 ms-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Save</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                    <!-- /Modern Horizontal Wizard -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->
@stop

@section('page-vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@stop

@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/quill/quill.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/wizard/bs-stepper.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-wizard.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>



{{-- <script src="//cdn.quilljs.com/1.3.6/quill.js"></script> --}}
<script>
    $(document).ready(function() {
        $.fn.create_field = function(options) {
            var settings = $.extend({
                row_id: "#prize-row",
                container: '.create-container',
                label: [, , '2nd', '3rd', '4th', '5th', '7th', '8th', '9th', '10th'],
                max_field: 100,
            }, options);
            var x = 1; //initlal text box count

            this.click(function(e) {
                if (x < settings.max_field) {
                    x++; //text box increment
                    let elements = $(settings.row_id).clone(true);
                    console.log(elements);
                    $(elements).removeAttr('id');
                    $(elements).find('.label').val(settings.label[x] + ' prize');
                    $(elements).find('.second-col').append('<span data-target="' + x + '" class="ms-2 btn btn-sm btn-outline-soundcloud btn-remove-price" style="height:32px; margin-top:3px"><i data-feather="x-square"></i></span>');
                    $(this).closest(settings.container).find('.target-row').append(elements);
                }
                feather.replace();
            });
        }
        $("#btn-add-prize").create_field({
            row_id: '#prize-row-trader',
        });
        $("#ib-btn-add-prize").create_field();
        // remove input fild
        $(document).on('click', '.btn-remove-price', function() {
            $(this).closest('.row').remove();
        });

    });

    //quil editor
    var quill = new Quill('#editor-container', {
        modules: {
            toolbar: true
        },
        placeholder: 'Compose your describtion...',
        theme: 'snow' // or 'bubble'
    });
    quill.on('text-change', function(delta, oldDelta, source) {
        $('#text_quill').val(quill.container.firstChild.innerHTML);
    });
    // quill editor IB
    var quill_ib = new Quill('#editor-container-ib', {
        modules: {
            toolbar: true
        },
        placeholder: 'Compose your describtion...',
        theme: 'snow' // or 'bubble'
    });
    quill_ib.on('text-change', function(delta, oldDelta, source) {
        $('#text_quill_ib').val(quill.container.firstChild.innerHTML);
    });
    // *********************************************************************
    function trader_contest_callback(data) {
        if (data.status) {
            notify('success', data.message, 'Trader contest');
            // redirect to reward list
            window.location.href = "/admin/reward/rewards"
            $("#account-details-modern").trigger('reset');
        } else {
            notify('error', data.message, 'Trader contest');
        }
        $.validator('account-details-modern', data.errors);
    }
    // ib contest
    function ib_contest_callback(data) {
        if (data.status) {
            notify('success', data.message, 'IB contest');
            $("#personal-info-modern").trigger('reset');
        } else {
            notify('error', data.message, 'IB contest');
        }
        $.validator('personal-info-modern', data.errors);
    }
    // show hide country field
    display_country()
    display_country_ib()


    function display_country() {
        if ($("#is_global").is(':checked')) {
            $("#trader-country-wrapper").slideUp();
        } else {
            $("#trader-country-wrapper").slideDown();
        }
    }

    function display_country_ib() {
        if ($("#is_global_ib").is(':checked')) {
            $("#ib-country-wrapper").slideUp();
        } else {
            $("#ib-country-wrapper").slideDown();
        }
    }
    $(document).on("change", '#is_global', function() {
        display_country();
    })
    $(document).on("change", '#is_global_ib', function() {
        display_country_ib();
    })




    $(document).ready(function () {
        $('#client-group').on('change', function () {
            const selectedId = $(this).val();
            const selectedText = $("#client-group option:selected").text();

            if (selectedId && !$(`#${selectedId}`).length) {
                // Append selected group
                $('#selected-groups').append(`
                    <span id="${selectedId}" class="badge bg-primary me-1 mt-1">
                        ${selectedText}
                        <span type="button" class="ms-2 cursor-pointer text-white delete-group" data-id="${selectedId}">x</span>
                    </span>
                `);

                // Update hidden input
                updateSelectedGroups();
            }

            // Reset dropdown
            $(this).val('');
        });

        // Delete functionality
        $(document).on('click', '.delete-group', function () {
            const groupId = $(this).data('id');
            $(`#${groupId}`).remove();
            updateSelectedGroups();
        });

        // Function to update hidden input
        function updateSelectedGroups() {
            const selectedIds = [];
            console.log("click")
            $('#selected-groups span').each(function () {
                const id = $(this).attr('id')
                if (typeof id !== 'undefined'){
                    console.log("attrib id :"+id)
                    selectedIds.push(id);
                }
                
            });
            console.log("select ids :"+selectedIds)
            $('#selected-groups-input').val(selectedIds.join(','));
        }
    });

    $(document).ready(function () {
    let availableOptions = @json($options); // Store available options from the server

    // Function to update the hidden input field
    function updateStoredData() {
        let storedData = [];
        $(".dynamic-row").each(function () {
            let optionValue = $(this).find(".option-select").val();
            let inputValue = $(this).find(".value-input").val();
            if (optionValue && inputValue) {
                storedData.push({ type: optionValue, value: inputValue });
            }
        });
        $("#stored-data").val(JSON.stringify(storedData));
    }

    // Function to update dropdowns dynamically
    function updateDropdowns() {
        let selectedOptions = $(".option-select").map(function () {
            return $(this).val();
        }).get().filter(value => value !== ""); // Get all selected values (excluding empty ones)

        $(".option-select").each(function () {
            let currentValue = $(this).val();
            let dropdown = $(this);

            // Clear dropdown and re-add available options
            dropdown.empty().append('<option value="">Choose an option</option>');

            $.each(availableOptions, function (key, value) {
                if (!selectedOptions.includes(key.toString()) || key.toString() === currentValue) {
                    dropdown.append(`<option value="${key}">${value}</option>`);
                }
            });

            dropdown.val(currentValue); // Retain the current selection
        });
    }

    // Function to add a new row
    $("#add-row").click(function () {
        let newRow = `<div class="row mb-2 dynamic-row">
            <div class="col-md-4">
                <select class="form-select option-select">
                    <option value="">Choose an option</option>
                    ${Object.keys(availableOptions).map(key => `<option value="${key}">${availableOptions[key]}</option>`).join('')}
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control value-input" placeholder="Enter value">
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-danger remove-row">Remove</button>
            </div>
        </div>`;
        
        $("#dynamic-rows-container").append(newRow);
        updateDropdowns(); // Ensure options update dynamically
    });

    // Handle option selection
    $(document).on("change", ".option-select", function () {
        updateDropdowns();
        updateStoredData();
    });

    // Handle row removal and restore options
    $(document).on("click", ".remove-row", function () {
        $(this).closest(".dynamic-row").remove();
        updateDropdowns(); // Restore removed options
        updateStoredData();
    });

    // Update hidden input on input change
    $(document).on("input", ".value-input", function () {
        updateStoredData();
    });

    $('#head_remove').remove()
});

$(document).ready(function() {


$('#client-group').select2({
    placeholder: "Choose a group",
    allowClear: true
});



$('#userSelect').select2({
    placeholder: "Search for a user...",
    allowClear: true,
    ajax: {
        url: "{{ route('admin.search.client') }}",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return { q: params.term };
        },
        processResults: function (data) {
            return {
                results: data.map(user => ({ id: user.id, text: user.email }))
            };
        },
        cache: true
    }
});

// Toggle Client Selection
// $('#is_admin').on('change', function () {
//     if ($(this).is(':checked')) {
//         $('#search_client').show(); // Show Client Dropdown
//         $('#client-group').empty().append('<option value="">Choose a group</option>'); // Reset Group Dropdown
//     } else {
//         $('#csearch_client').hide(); // Hide Client Dropdown
//         $('#client-group').empty().append(`<option value="">Choose a group</option>`); // Reset Group Dropdown

//         // Restore All Groups (Original State)
//         @foreach($groups as $value)
//             $('#client-group').append(`<option value="{{$value->id}}">{{$value->group_id}}</option>`);
//         @endforeach

//         $('#client-group').trigger('change'); // Refresh Select2
//     }
// });


$('#is_admin').on('click', function() {


    $('#selected-groups-input').val('');
    $('#selected-groups').empty();
    $('#userSelect').empty()

    if ($(this).prop('checked')) {

        $('#search_client').hide(); // Hide Client Dropdown
        $('#client-group').empty().append(`<option value="">Choose a group</option>`); // Reset Group Dropdown

        // Restore All Groups (Original State)
        @foreach($groups as $value)
            $('#client-group').append(`<option value="{{$value->id}}">{{$value->group_id}}</option>`);
        @endforeach

        $('#client-group').trigger('change'); // Refresh Select2

    } else {

        $('#search_client').show(); // Show Client Dropdown
        $('#client-group').empty().append('<option value="">Choose a group</option>'); // Reset Group Dropdown
        
    }

});



    // Handle Client Selection
    $('#userSelect').on('change', function () {
        $('#selected-groups-input').val('');
        $('#selected-groups').empty();
        let clientId = $(this).val();
        setGroupDropdown(clientId)
    });

    function setGroupDropdown(clientId){
        if (clientId) {
            $.ajax({
                url: "{{ route('admin.search.client.groups', ':id') }}".replace(':id', clientId),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    let groupDropdown = $('#client-group');
                    groupDropdown.empty();
                    groupDropdown.append('<option value="">Choose a group</option>');
                    data.forEach(group => {
                        groupDropdown.append(`<option value="${group.id}">${group.group_id}</option>`);
                    });
                    groupDropdown.trigger('change'); // Refresh Select2
                }
            });
        } else {
            $('#client-group').empty().append('<option value="">Choose a group</option>').trigger('change');
        }

    }


    @if(isset($client))
        setGroupDropdown({{ $client->id }});
    @endif

});


$(document).ready(function() {
    var selectedCountries = @json($selectedCountries); // Convert PHP array to JavaScript array

    $("#contest-country").val(selectedCountries).trigger("change"); // Set selected options
});


    
</script>
@stop