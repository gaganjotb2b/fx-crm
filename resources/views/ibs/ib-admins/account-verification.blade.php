@extends(App\Services\systems\VersionControllService::get_layout('ib'))
@section('title', 'IB Account verification')
@section('page-css')@if(App\Services\systems\VersionControllService::check_version()==='lite')
<link id="pagestyle" href="{{ asset('trader-assets/assets/css/soft-ui-dashboard.css?v=1.0.8') }}" rel="stylesheet" />
@endif
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css') }}">
<style>
    .dropzone {
        min-height: 210px;
        /* border: 2px dashed var(--custom-primary); */
        background: #f8f8f8;
        position: relative;
    }

    .dropzone .dz-message {
        font-size: 2rem;
        color: var(--custom-primary);
    }

    .dropzone .dz-message {
        font-size: 2rem;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        color: var(--custom-primary);
        display: flex;
        justify-content: center;
        align-items: baseline;
        margin: 0;
    }

    .dropzone .dz-message::before {
        content: '';
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%237367f0' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-download'%3E%3Cpath d='M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4'%3E%3C/path%3E%3Cpolyline points='7 10 12 15 17 10'%3E%3C/polyline%3E%3Cline x1='12' y1='15' x2='12' y2='3'%3E%3C/line%3E%3C/svg%3E");
        font-size: 80px;
        position: absolute;
        top: 5rem;
        width: 80px;
        height: 80px;
        display: inline-block;
        line-height: 1;
        z-index: 2;
        color: var(--custom-primary);
        text-indent: 0px;
        font-weight: normal;
        -webkit-font-smoothing: antialiased;
    }

    .dropzone.dropzone-area.redirect {
        min-height: 100px;
    }

    .upload-id-proof.d-flex {
        justify-content: center;
        align-items: center;
        border: 2px dashed var(--custom-primary);
        min-height: 90px;
    }

    .up-message.ms-3 {
        color: var(--custom-primary);
    }

    .custom_avatar_div .avatar {

        height: auto;
        width: auto;
    }
</style>
@if(App\Services\systems\VersionControllService::check_version()==='lite')
<style>
    .text-kyc-verification {
        color: #6c757d !important
    }

    .id-proof-dz-con {
        margin-top: 10px;
    }

    .id-proof-dz-box {
        max-height: 150px;
    }

    .dropzone {
        min-height: 152px;
        border: 2px dashed var(--custom-primary);
        background: #f8f8f8;
        position: relative;
    }

    .dropzone .dz-message::before {
        content: '';
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%237367f0' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-download'%3E%3Cpath d='M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4'%3E%3C/path%3E%3Cpolyline points='7 10 12 15 17 10'%3E%3C/polyline%3E%3Cline x1='12' y1='15' x2='12' y2='3'%3E%3C/line%3E%3C/svg%3E");
        font-size: 80px;
        position: absolute;
        top: 3rem;
        width: 35px;
        height: 35px;
        display: inline-block;
        line-height: 1;
        z-index: 2;
        color: var(--custom-primary);
        text-indent: 0px;
        font-weight: normal;
        -webkit-font-smoothing: antialiased;
    }
</style>
@endif
@endsection
<!-- bread crumb -->
@section('bread_crumb')
{!!App\Services\systems\BreadCrumbService::get_ib_breadcrumb()!!}
@stop
<!-- main contents -->
@section('content')
<!-- End Navbar -->
<div class="container-fluid py-4">
    <div class="row custom-height-con">
        <div class="col-12 text-center">
            <h3 class="mt-5">{{ __('page.Account Verification') }}</h3>
            <h5 class="text-secondary font-weight-normal text-kyc-verification">
                {{ __('page.This information will let us know more about you.') }}
            </h5>
            <div class="multisteps-form mb-5">
                <!--progress bar-->
                <div class="row">
                    <div class="col-12 col-lg-8 mx-auto my-3">
                        <div class="multisteps-form__progress visually-hidden">
                            <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">
                                <span>{{ __('page.About') }}</span>
                            </button>
                            <button class="multisteps-form__progress-btn" type="button" title="Address">
                                <span>{{ __('page.Account') }}</span>
                            </button>
                            <button class="multisteps-form__progress-btn" type="button" title="Order Info">
                                <span>{{ __('page.Address') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!--form panels-->
                <div class="row">
                    <div class="col-12 col-lg-8 m-auto">
                        <div class="multisteps-form__form">
                            <!--single form panel-->
                            <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                <div class="row text-center">
                                    <div class="col-10 mx-auto">
                                        <div class="nav-wrapper position-relative end-0 mb-3">
                                            <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                                <!-- nav item id verifications -->
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#tab-pan-id-proof" role="tab" aria-controls="preview" aria-selected="true">
                                                        <svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                            <title>document</title>
                                                            <g id="Basic-Elements" stroke="none" stroke-width="1" fill="var(--custom-primary)" fill-rule="evenodd">
                                                                <g id="Rounded-Icons" transform="translate(-1870.000000, -591.000000)" fill="var(--custom-primary)" fill-rule="nonzero">
                                                                    <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                                                        <g id="document" transform="translate(154.000000, 300.000000)">
                                                                            <path class="color-background" d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z" id="Path" opacity="0.603585379">
                                                                            </path>
                                                                            <path class="color-background" d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z" id="Shape"></path>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </svg>{{ __('page.ID Verification') }}
                                                    </a>
                                                </li>
                                                <!-- nav item address verifications -->
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#address-proof" role="tab" aria-controls="code" aria-selected="false">
                                                        <svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                            <title>document</title>
                                                            <g id="Basic-Elements" stroke="none" stroke-width="1" fill="var(--custom-primary)" fill-rule="evenodd">
                                                                <g id="Rounded-Icons" transform="translate(-1870.000000, -591.000000)" fill="var(--custom-primary)" fill-rule="nonzero">
                                                                    <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                                                        <g id="document" transform="translate(154.000000, 300.000000)">
                                                                            <path class="color-background" d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z" id="Path" opacity="0.603585379">
                                                                            </path>
                                                                            <path class="color-background" d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z" id="Shape"></path>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </svg>{{ __('page.Address Verification') }}
                                                    </a>
                                                </li>
                                                @if (isset($check_kyc_status->user_id))
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1" id="kycTab" data-bs-toggle="tab" href="#myKyc" role="tab" aria-controls="code" aria-selected="false">
                                                        <svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                            <title>{{ __('page.document') }}</title>
                                                            <g id="Basic-Elements" stroke="none" stroke-width="1" fill="var(--custom-primary)" fill-rule="evenodd">
                                                                <g id="Rounded-Icons" transform="translate(-1870.000000, -591.000000)" fill="var(--custom-primary)" fill-rule="nonzero">
                                                                    <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                                                        <g id="document" transform="translate(154.000000, 300.000000)">
                                                                            <path class="color-background" d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z" id="Path" opacity="0.603585379">
                                                                            </path>
                                                                            <path class="color-background" d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z" id="Shape"></path>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                        KYC Report
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="multisteps-form__content">
                                    <div class="row mt-3">
                                        <div class="col-12 text-start mx-auto">
                                            <!-- tab content id proof -->
                                            <div class="tab-content" id="tab-id-proof">
                                                <div class="tab-pane fade show active" id="tab-pan-id-proof" role="tabpanel" aria-labelledby="home-tab">

                                                    <div class="row px-6">
                                                        <div class="col-md-4 col-sm-2 p-3 custom_avatar_div">
                                                            <div class="avatar">
                                                                <img id="platform-logo" src="{{ asset($avatar) }}" class="border-radius-md img-thumbnail" alt="team-2">
                                                            </div>
                                                        </div>
                                                        @php $need_upload = \App\Services\KycService::need_upload('id proof',auth()->user()->id); @endphp
                                                        @if (strtolower($need_upload)==='pending')
                                                        <div class="col-md-8 col-sm-10 mx-auto pt-3">
                                                            <div class="alert alert-dismissible alert-warning m-auto text-white w-100 py-7 pb-8" role="alert">
                                                                <p class="mb-0 text-center">Your KYC ID proof document currenly <span class="">Pending</span>, Please contact if you need any help. you can not upload while this document pending</p>
                                                            </div>
                                                        </div>
                                                        @elseif (strtolower($need_upload)==='approved')
                                                        <div class="col-md-8 col-sm-10 mx-auto pt-3">
                                                            <div class="alert alert-dismissible alert-success m-auto text-white w-100 py-7 pb-8" role="alert">
                                                                <p class="mb-0 text-center">Your KYC ID proof document already <span class="">Approved</span>, Please contact if you need any help. you dont need to upload more ID Proof document.</p>
                                                            </div>
                                                        </div>
                                                        @else
                                                        <div class="col-md-8 col-sm-10 mx-auto">
                                                            <div class="pt-2">
                                                                <form action="#" id="id-proof-form" method="post" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input type="hidden" name="perpose" value="id proof">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlSelect1">{{ __('page.document-type') }}</label>
                                                                        <select name="document_type" class="select2 form-control choice-material" id="exampleFormControlSelect1">
                                                                            @foreach ($id_type as $value)
                                                                            <option value="{{ isset($value->id) ? $value->id : '' }}">
                                                                                <!-- {{ ucwords($value->id_type) }} -->
                                                                                {{ isset($value->id_type) ? ucwords($value->id_type) : '' }}
                                                                            </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group" id="row-issue-date">
                                                                        <label for="example-date-input" class="form-control-label">{{ __('page.issue-date') }}</label>
                                                                        <input name="issue_date" class="form-control" type="date" value="2018-11-23" id="example-date-input">
                                                                    </div>
                                                                    <div class="form-group" id="row-exp-date">
                                                                        <label for="example-date-input" class="form-control-label">{{ __('page.expire-date') }}</label>
                                                                        <input name="expire_date" class="form-control" type="date" value="2018-11-23" id="example-date-input">
                                                                    </div>
                                                                    <div class="form-group" id="idDiv">
                                                                        <label for="example-date-input" class="form-control-label">{{ __('page.id-number') }}</label>
                                                                        <input name="id_number" class="form-control" type="text" placeholder="ID Number" id="id_number">
                                                                    </div>
                                                                    <div class="d-flex id-proof-dz-con">
                                                                        <!-- id front part -->
                                                                        <div class="w-50">
                                                                            <div class="dropzone dropzone-area id-proof-dropzone w-100 id-proof-dz-box" data-field="front_part" id="id-dropzone" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your ID">
                                                                                <div class="dz-message">
                                                                                    <div class="dz-message-label">
                                                                                        {{ __('page.drop-your-id') }}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- id back part -->
                                                                        <div class="w-50 ms-2">
                                                                            <div class="dropzone dropzone-area id-proof-dropzone w-100 id-proof-dz-box" data-field="back_part" id="id-back-part" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your ID Back Part">
                                                                                <div class="dz-message">
                                                                                    <div class="dz-message-label">
                                                                                        {{ __('page.drop-your-id-back-part') }}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button type="button" id="btn-save-id-proof" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" class="btn bg-gradient-primary w-100 mt-4">{{ __('page.save-your-kyc') }}</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <!-- address verification tab -->
                                                <div class="tab-pane fade" id="address-proof" role="tabpanel" aria-labelledby="profile-tab">

                                                    <div class="row px-6">
                                                        <div class="col-md-4 col-sm-2 p-3 custom_avatar_div">
                                                            <div class="avatar">
                                                                <img id="platform-logo" src="{{ asset($avatar) }}" class="border-radius-md img-thumbnail" alt="team-2">
                                                            </div>
                                                        </div>
                                                        @php $need_upload = \App\Services\KycService::need_upload('address proof',auth()->user()->id); @endphp
                                                        @if (strtolower($need_upload)==='pending')
                                                        <div class="col-md-8 col-sm-10 mx-auto pt-3">
                                                            <div class="alert alert-dismissible alert-warning m-auto text-white w-100 py-7 pb-8" role="alert">
                                                                <p class="mb-0 text-center">Your KYC address proof document currenly <span class="">Pending</span>, Please contact if you need any help. you can not upload while this document pending</p>
                                                            </div>
                                                        </div>
                                                        @elseif (strtolower($need_upload)==='approved')
                                                        <div class="col-md-8 col-sm-10 mx-auto pt-3">
                                                            <div class="alert alert-dismissible alert-success m-auto text-white w-100 py-7 pb-8" role="alert">
                                                                <p class="mb-0 text-center">Your KYC address proof document already <span class="">Approved</span>, Please contact if you need any help. you dont need to upload more ID Proof document.</p>
                                                            </div>
                                                        </div>
                                                        @else
                                                        <div class="col-md-8 col-sm-10 mx-auto">
                                                            <form action="#" id="address-proof-form" method="post" class="p-3" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="perpose" value="address proof">
                                                                <div class="form-group">
                                                                    <label for="exampleFormControlSelect2">{{ __('page.document-type') }}</label>
                                                                    <select name="document_type" class="select2 form-control select_option_design" id="exampleFormControlSelect2">
                                                                        @foreach ($address_type as $value)
                                                                        <option value="{{ isset($value->id) ? $value->id : '' }}">
                                                                            <!-- {{ ucwords($value->id_type) }} -->
                                                                            {{ isset($value->id_type) ? ucwords($value->id_type) : '' }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="example-date-input" class="form-control-label">{{ __('page.issue-date') }}</label>
                                                                    <input name="issue_date" class="form-control" type="date" value="2018-11-23" id="example-date-input">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="example-date-input" class="form-control-label">{{ __('page.expire-date') }}</label>
                                                                    <input name="expire_date" class="form-control" type="date" value="2018-11-23" id="example-date-input">
                                                                </div>
                                                                <!-- address front part -->
                                                                <div class="dropzone dropzone-area address-proof-dropzone id-proof-dz-con" id="id-dropzone-address-proof" data-field="document" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and Drop or click your Banner">
                                                                    <div class="dz-message">
                                                                        <div class="dz-message-label">
                                                                            {{ __('page.drop-your-document') }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <button type="button" id="btn-save-address-proof" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" class="btn bg-gradient-primary w-100 mt-4">{{ __('page.save-your-kyc') }}</button>
                                                            </form>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <!-- kyc report tab -->
                                                <!-- KYC verification form -->
                                                <div class="tab-pane fade" id="myKyc" role="tabpanel" aria-labelledby="kyc-tab">
                                                    <div class="dfskdkksdl">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="table-responsive">
                                                                    <table class="table table-striped table-bordered table-dark mt-3">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="text-uppercase font-weight-bolder opacity-7 text-center">
                                                                                    {{ __('page.front-part') }}
                                                                                </th>
                                                                                <th class="text-uppercase font-weight-bolder opacity-7 text-center">
                                                                                    {{ __('page.back-part') }}
                                                                                </th>
                                                                                <th class="text-uppercase font-weight-bolder opacity-7 text-center">
                                                                                    {{ __('page.type') }}
                                                                                </th>
                                                                                <th class="text-uppercase font-weight-bolder opacity-7 text-center">
                                                                                    {{ __('page.upload-date') }}
                                                                                </th>
                                                                                <th class="text-uppercase font-weight-bolder opacity-7 text-center">
                                                                                    {{ __('page.status') }}
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($kyc_documents as $value)
                                                                            <tr>
                                                                                @php
                                                                                // dd($value->perpose);
                                                                                $document_names = $value->document_name;
                                                                                $document_name = json_decode($document_names);
                                                                                $docPartsFront = explode('.', $document_name->front_part);
                                                                                $docPartFrontName = preg_replace('/[^a-zA-Z-]/', '', $docPartsFront[0]);

                                                                                $docPartsBack = explode('.', $document_name->back_part);
                                                                                $docPartBackName = preg_replace('/[^a-zA-Z-]/', '', $docPartsBack[0]);

                                                                                @endphp
                                                                                <td style="vertical-align: middle;">
                                                                                    @if ($document_name->front_part !== '')
                                                                                    {{-- <a href="#">
                                                                                            <img class="img img-fluid img-thumbnail me-3" src="{{ asset('Uploads/kyc/' . $document_name->front_part) }}" alt="Kyc Front Part" style="max-height:100px">
                                                                                    </a> --}}
                                                                                    @if ($value->perpose == 'id proof')
                                                                                    <p class="text-center mb-0">
                                                                                        {{ str_replace($docPartFrontName, 'ID Front Part', $docPartFrontName) }}
                                                                                    </p>
                                                                                    @else
                                                                                    <p class="text-center mb-0">
                                                                                        {{ str_replace($docPartFrontName, 'Address Front Part', $docPartFrontName) }}
                                                                                    </p>
                                                                                    @endif
                                                                                    @else
                                                                                    <p class="text-center mb-0">
                                                                                        {{ '--' }}
                                                                                    </p>
                                                                                    @endif
                                                                                </td>
                                                                                <td style="vertical-align: middle;">
                                                                                    @if ($document_name->back_part !== '')
                                                                                    {{-- <a href="#">
                                                                                            <img class="img img-fluid img-thumbnail" src="{{ asset('Uploads/kyc/' . $document_name->back_part) }}" alt="Kyc Front Part" style="max-height:100px">
                                                                                    </a> --}}
                                                                                    @if ($value->perpose == 'id proof')
                                                                                    <p class="text-center mb-0">
                                                                                        {{ str_replace($docPartFrontName, 'ID Back Part', $docPartFrontName) }}
                                                                                    </p>
                                                                                    @else
                                                                                    <p class="text-center mb-0">
                                                                                        {{ str_replace($docPartFrontName, 'Address Back Part', $docPartFrontName) }}
                                                                                    </p>
                                                                                    @endif
                                                                                    @else
                                                                                    <p class="text-center mb-0">
                                                                                        {{ '--' }}
                                                                                    </p>
                                                                                    @endif
                                                                                </td>
                                                                                <td style="vertical-align: middle;">
                                                                                    @if (isset($value->perpose))
                                                                                    @if ($value->perpose == 'id proof')
                                                                                    <p class="text-center mb-0">
                                                                                        {{ str_replace($value->perpose, 'ID Proof', $value->perpose) }}
                                                                                    </p>
                                                                                    @else
                                                                                    <p class="text-center mb-0">
                                                                                        {{ ucwords($value->perpose) }}
                                                                                    </p>
                                                                                    @endif
                                                                                    @endif

                                                                                </td>
                                                                                <td style="vertical-align: middle;">
                                                                                    <div class="d-flex justify-content-between">
                                                                                        <div>
                                                                                            <p class="text-center mb-0">
                                                                                                {{ strtoupper(date('d-M-Y h:i:s A', strtotime($value->created_at))) }}
                                                                                            </p>
                                                                                        </div>
                                                                                        <div>
                                                                                            <div class="badge badge-dark">
                                                                                                {{ $value->created_at->diffForHumans() }}

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td style="vertical-align: middle;" class="text-center">
                                                                                    @php
                                                                                    if ($value->status == 0):
                                                                                    $status = 'Pending';
                                                                                    $badge = 'warning';
                                                                                    elseif ($value->status == 1):
                                                                                    $status = 'Approved';
                                                                                    $badge = 'success';
                                                                                    elseif ($value->status == 2):
                                                                                    $status = 'Decline';
                                                                                    $badge = 'danger';
                                                                                    endif;
                                                                                    @endphp
                                                                                    <div class="badge badge-{{ $badge }}">
                                                                                        {{ $status }}
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-row d-flex mt-4">
                                        <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" type="button" title="Next">Next</button>
                                    </div>
                                </div>
                            </div>
                            <!--single form panel-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- include footer -->
    @include('layouts.footer')
</div>

@endsection
@section('corejs')
<script src="{{ asset('trader-assets/assets/js/plugins/multistep-form.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/scripts/pages/file-upload-with-form.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/scripts/pages/ib-verification.js') }}"></script>
@endsection