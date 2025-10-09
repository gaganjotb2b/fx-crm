@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Profile Overview')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
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

    .flatpickr-wrapper {
        width: 100% !important;
    }

    /* Account Manager highlight */
    .manager-highlight {
        background: #f7f9ff;
        border: 1px solid #e6e9ff;
        border-left: 4px solid var(--custom-primary);
        border-radius: 12px;
        padding: 12px 14px;
    }
    .manager-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--custom-primary);
        color: #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,.08);
    }
    .manager-name {
        font-weight: 600;
        color: #111827;
        line-height: 1.2;
    }
    .manager-contact {
        color: #6b7280;
        font-size: .875rem;
    }
</style>
@if (App\Services\systems\VersionControllService::check_version() === 'lite')
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
@stop
<!-- breadcrumb -->
@section('bread_crumb')
{!!App\Services\systems\BreadCrumbService::get_trader_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid page-profile-overview">
    <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('<?= asset('trader-assets/assets/img/curved-images/curved0.jpg') ?>'); background-position-y: 50%;">
        <span class="mask bg-gradient-primary opacity-6"></span>
    </div>
    <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
        <div class="row gx-4">
             <!-- user avater -->
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <?php
                    use App\Services\AllFunctionService;
                    $all_func = new AllFunctionService();  
                    ?>
                    <img src="{{ $all_func->get_user_profile(auth()->user()->id) }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">
                        {{ ucwords(auth()->user()->name) }}
                    </h5>
                    <p class="mb-0 font-weight-bold text-sm">
                        {{ ucwords(auth()->user()->type) }}
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                <div class="nav-wrapper position-relative end-0">
                    <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                        <!-- verification -->
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1  active" href="{{ route('user.user-admin-account-verification') }}">
                                <svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <title>{{ __('page.document') }}</title>
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g transform="translate(-1870.000000, -591.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                            <g transform="translate(1716.000000, 291.000000)">
                                                <g transform="translate(154.000000, 300.000000)">
                                                    <path class="color-background" d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z" opacity="0.603585379"></path>
                                                    <path class="color-background" d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z">
                                                    </path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                <span class="ms-1">{{ __('page.profile-overview') }}</span>
                            </a>
                        </li>
                        <!-- page settings -->
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1 " href="{{ route('user.user-admin-account-settings') }}">
                                <svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <title>{{ __('page.settings') }}</title>
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                            <g transform="translate(1716.000000, 291.000000)">
                                                <g transform="translate(304.000000, 151.000000)">
                                                    <polygon class="color-background" opacity="0.596981957" points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667">
                                                    </polygon>
                                                    <path class="color-background" d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z" opacity="0.596981957"></path>
                                                    <path class="color-background" d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z">
                                                    </path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                <span class="ms-1">{{ __('page.settings') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid py-4">
    <div class="row mt-3 custom-height-con">
        <div class="col-12 col-md-6 col-xl-4">
            <!-- trading account -->
            <div class="card h-100">
                <div class="card-header p-3">
                    <h6 class="mb-0">{{ __('page.trading-accounts') }}</h6>
                </div>
                <div class="card-body pt-0 p-3">
                    <div class="row  position-relative mb-3" id="trading-ac-data-list">
                        <div class="text-center">Loading trading account....</div>
                    </div>
                    <div class="table-responsive">
                        <table class="user-list-table table d-none" id="trading_account"> </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- pfofile information -->
        <div class="col-12 col-md-6 col-xl-4 mt-md-0 mt-4">
            <div class="card h-100">
                <div class="card-header p-3">
                    <div class="row">
                        <div class="col-md-8 d-flex align-items-center">
                            <h6 class="mb-0">{{ __('page.profile-information') }}</h6>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#basicInfoModal">
                                <i class="fas fa-user-edit text-secondary text-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Profile"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group">
                        <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">{{ __('page.full-name') }}:</strong> &nbsp;
                            <!-- {{ ucwords(auth()->user()->name) }} -->
                            <?= (auth()->user()->name) ? ucwords(auth()->user()->name) : "" ?>
                        </li>
                        <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">{{ __('page.mobile') }}:</strong> &nbsp;
                            <?= (auth()->user()->phone) ? ucwords(auth()->user()->phone) : "" ?>
                        </li>
                        <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">{{ __('page.email') }}:</strong> &nbsp;
                            <!-- {{ auth()->user()->email }} -->
                            <?= (auth()->user()->email) ? (auth()->user()->email) : "" ?>
                        </li>
                        <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">{{ __('page.address') }}:</strong> &nbsp;
                            <?= ($user_description->address) ? ucwords($user_description->address) : "" ?>
                        </li>
                        <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">{{ __('page.city') }}:</strong> &nbsp;
                            <?= ($user_description->city) ? ucwords($user_description->city) : "" ?>
                        </li>
                        <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">{{ __('page.country') }}:</strong> &nbsp;
                            <?= ($user_description->name) ? ucwords($user_description->countryName) : "" ?>
                        </li>
                        <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">{{ __('page.verification_status') }}:</strong> &nbsp;
                            <?=
                            ($user_description->kyc_status == 1) ? "Verified" : (($user_description->kyc_status == 2) ? "Pending" : "Un-Verified");
                            ?>
                        </li>
                        @if (App\Services\systems\VersionControllService::check_version() === 'pro')
                            <li class="list-group-item border-0 ps-0 text-sm">
                                <div class="manager-highlight d-flex align-items-center gap-3">
                                    <div class="manager-icon me-3">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="manager-name">
                                            {{ __('page.account_manager') }}
                                        </div>
                                        <div class="manager-contact">
                                            <?php if ($manager) { ?>
                                                <span><?= ucwords($manager->name) ?></span>
                                                <?php if (!empty($manager->phone)) { ?>
                                                    <span> • <?= $manager->phone ?></span>
                                                <?php } ?>
                                                <?php if (!empty($manager->email)) { ?>
                                                    <span> • <?= $manager->email ?></span>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <span>No Account Manager</span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary ms-2">Desk</span>
                                </div>
                            </li>
                        @endif

                        <li class="list-group-item border-0 ps-0 pb-0 logo-social-icon" id="logoSocialIcon">

                        </li>
                    </ul>
                    
                    <ul class="list-group mt-6">
                        <form action="#" id="profile-picture-form" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="w-100">
                                <div class="dropzone dropzone-area profile-picture-dropzone w-100" data-field="front_part" id="id-dropzone" enctype="multipart/form-data" data-bs-toggle="tooltip" data-bs-placement="top" title="Drag and drop or click your profile picture">
                                    <div class="dz-message">
                                        <div class="dz-message-label">
                                            Upload New Picture
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="btn-save-profit-picture" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" class="btn bg-gradient-primary w-100 mt-4">Save</button>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
        <!-- social links -->
        <div class="col-12 col-xl-4 mt-xl-0 mt-4">
            <div class="card h-100">
                <div class="card-header p-3">
                    <h6 class="mb-0">{{ __('page.social-links') }}</h6>
                </div>
                <div class="card-body pt-0 p-3">
                    <ul class="list-group">
                        <!-- facebook -->
                        <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                            <div class="me-3">
                                <div class="border-radius-lg shadow icon-container"><i class="fab fa-facebook"></i></div>
                            </div>
                            <div class="d-flex align-items-start flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{{ __('page.facebook') }}</h6>
                                <p class="mb-0 text-xs" id="facebook-text">
                                    {{ isset($social_link->facebook) ? ($social_link->facebook != null ? $social_link->facebook : '') : '' }}
                                </p>
                            </div>
                            <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto btn-link-edit" data-bs-toggle="modal" data-bs-target="#socialModal" data-link="{{ isset($social_link->facebook) ? ($social_link->facebook != null ? $social_link->facebook : '') : '' }}" data-label="Facebook" href="javascript:;">
                                <i class="fas fa-{{ isset($social_link->facebook) ? ($social_link->facebook != null ? 'edit' : 'plus') : 'plus' }}"></i>
                            </a>
                        </li>
                        <!-- twitter -->
                        <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                            <div class="me-3">
                                <div class="border-radius-lg shadow icon-container"><i class="fab fa-twitter"></i></div>
                            </div>
                            <div class="d-flex align-items-start flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{{ __('page.twitter') }}</h6>
                                <p class="mb-0 text-xs" id="twitter-text">
                                    {{ isset($social_link->twitter) ? ($social_link->twitter != null ? $social_link->twitter : '') : '' }}
                                </p>
                            </div>
                            <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto btn-link-edit" data-bs-toggle="modal" data-bs-target="#socialModal" data-link="{{ isset($social_link->twitter) ? ($social_link->twitter != null ? $social_link->twitter : '') : '' }}" data-label="Twitter" href="javascript:;"><i class="fas fa-{{ isset($social_link->twitter) ? ($social_link->twitter != null ? 'edit' : 'plus') : 'plus' }}"></i></a>
                        </li>
                        <!-- whatsapp -->
                        <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                            <div class="me-3">
                                <div class="border-radius-lg shadow icon-container"><i class="fab fa-whatsapp"></i></div>
                            </div>
                            <div class="d-flex align-items-start flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{{ __('page.whatsapp') }}</h6>
                                <p class="mb-0 text-xs" id="whatsapp-text">
                                    {{ isset($social_link->whatsapp) ? ($social_link->whatsapp != null ? $social_link->whatsapp : '') : '' }}
                                </p>
                            </div>
                            <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto btn-link-edit" data-bs-toggle="modal" data-bs-target="#socialModal" data-link="{{ isset($social_link->whatsapp) ? ($social_link->whatsapp != null ? $social_link->whatsapp : '') : '' }}" data-label="Whatsapp" href="javascript:;"><i class="fas fa-{{ isset($social_link->whatsapp) ? ($social_link->whatsapp != null ? 'edit' : 'plus') : 'plus' }}"></i></a>
                        </li>
                        <!-- skype -->
                        <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                            <div class="me-3">
                                <div class="border-radius-lg shadow icon-container"><i class="fab fa-skype"></i></div>
                            </div>
                            <div class="d-flex align-items-start flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{{ __('page.skype') }}</h6>
                                <p class="mb-0 text-xs" id="skype-text">
                                    {{ isset($social_link->skype) ? ($social_link->skype != null ? $social_link->skype : '') : '' }}
                                </p>
                            </div>
                            <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto btn-link-edit" data-bs-toggle="modal" data-bs-target="#socialModal" data-link="{{ isset($social_link->skype) ? ($social_link->skype != null ? $social_link->skype : '') : '' }}" data-label="Skype" href="javascript:;"><i class="fas fa-{{ isset($social_link->skype) ? ($social_link->skype != null ? 'edit' : 'plus') : 'plus' }}"></i></a>
                        </li>
                        <!-- linkedin -->
                        <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                            <div class="me-3">
                                <div class="border-radius-lg shadow icon-container"><i class="fab fa-linkedin"></i></div>
                            </div>
                            <div class="d-flex align-items-start flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{{ __('page.linked-in') }}</h6>
                                <p class="mb-0 text-xs" id="linkedin-text">
                                    {{ isset($social_link->linkedin) ? ($social_link->linkedin != null ? $social_link->linkedin : '') : '' }}
                                </p>
                            </div>
                            <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto btn-link-edit" data-bs-toggle="modal" data-bs-target="#socialModal" data-link="{{ isset($social_link->linkedin) ? ($social_link->linkedin != null ? $social_link->linkedin : '') : '' }}" data-label="Linkedin" href="javascript:;"><i class="fas fa-{{ isset($social_link->linkedin) ? ($social_link->linkedin != null ? 'edit' : 'plus') : 'plus' }}"></i></a>
                        </li>
                        <!-- telegram -->
                        <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                            <div class="me-3">
                                <div class="border-radius-lg shadow icon-container"><i class="fab fa-telegram"></i></div>
                            </div>
                            <div class="d-flex align-items-start flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{{ __('page.telegram') }}</h6>
                                <p class="mb-0 text-xs" id="telegram-text">
                                    {{ isset($social_link->telegram) ? ($social_link->telegram != null ? $social_link->telegram : '') : '' }}
                                </p>
                            </div>
                            <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto btn-link-edit" data-bs-toggle="modal" data-bs-target="#socialModal" data-link="{{ isset($social_link->telegram) ? ($social_link->telegram != null ? $social_link->telegram : '') : '' }}" data-label="Telegram" href="javascript:;"><i class="fas fa-{{ isset($social_link->telegram) ? ($social_link->telegram != null ? 'edit' : 'plus') : 'plus' }}"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- include footer -->
    @include('layouts.footer')
    <!-- Modal -->
    <div class="modal fade" id="basicInfoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalMessageTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="basicInfoModal"><b>{{ __('page.update-user-info') }}</b></h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="card-body pt-0" action="{{ route('user.user-admin-settings.basic-info') }}" method="POST" id="basic-info-form">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">{{ __('page.full-name') }}</label>
                                <div class="input-group">
                                    <input id="full-name" name="full_name" class="form-control" type="text" placeholder="Alec" required="required" value="{{ isset(auth()->user()->name) ? ucwords(auth()->user()->name) : '' }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">{{ __('page.email') }}</label>
                                <div class="input-group">
                                    <input id="email" name="email" class="form-control" type="email" placeholder="Thompson" required="required" value="{{ isset(auth()->user()->email) ? auth()->user()->email : '' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label mt-4">{{ __('page.gender') }}</label>
                                <select class="select2 form-control choice-material" name="gender" id="choices-gender  ">
                                    <option value="Male" <?= $user_description->gender == 'Male' ? 'selected' : '' ?>>
                                        {{ __('page.male') }}
                                    </option>
                                    <option value="Female" <?= $user_description->gender == 'Female' ? 'selected' : '' ?>>
                                        {{ __('page.female') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label mt-4">{{ __('page.birth-date') }}</label>
                                <div class="col-12 d-flex">
                                    <span class="input-rang-group-date-logo">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6">
                                            </line>
                                            <line x1="8" y1="2" x2="8" y2="6">
                                            </line>
                                            <line x1="3" y1="10" x2="21" y2="10">
                                            </line>
                                        </svg>
                                    </span>
                                    <input type="text" id="date_of_birth" class="flatpickr-basic border w-100 date_picker_field" name="date_of_birth" placeholder="YY-MM-DD" value="<?= $user_description->date_of_birth ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <label class="form-label mt-4">{{ __('page.country') }}</label>
                                <select class="select2 form-control select_option_design" name="country" id="country">
                                    @foreach ($countries as $value)
                                    <option value="{{ isset($value->id) ? $value->id : '' }}" <?= $value->id == $user_description->country_id ? 'selected' : '' ?>>
                                        {{ isset($value->name) ? $value->name : '' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label mt-4">{{ __('page.state') }}</label>
                                <div class="input-group">
                                    <input id="state" name="state" class="form-control" value="{{ isset($user_description->state) ? $user_description->state : '' }}" type="text" placeholder="Your State">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label mt-4">{{ __('page.city') }}</label>
                                <div class="input-group">
                                    <input id="city" name="city" class="form-control" value="{{ isset($user_description->city) ? $user_description->city : '' }}" type="text" placeholder="Your City">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label mt-4">{{ __('page.phone-number') }}</label>
                                <div class="input-group">
                                    <input id="phone" name="phone" class="form-control" type="text" placeholder="+40 735 631 620" value="{{ isset($user_description->phone) ? $user_description->phone : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="message-text" class="form-label mt-4">{{ __('page.address') }}:</label>
                                <textarea class="form-control" name="address" id="address">{{ $user_description->address }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label mt-4">{{ __('page.zip-code') }}</label>
                                <input class="form-control" id="zipcode" name="zipcode" type="text" value="{{ isset($user_description->zip_code) ? $user_description->zip_code : '' }}" placeholder="Enter Zip Code" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">{{ __('page.close') }}</button>
                    <button type="button" data-label="Update" id="btn-submit-request" data-btnid="btn-submit-request" data-callback="user_info_call_back" data-loading="<i class='fa-spinner fas fa-circle-notch'></i>" data-form="basic-info-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary">{{ __('page.update') }}</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Social links Update -->
    <div class="modal fade" id="socialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalMessageTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="popupheader"></h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.user-admin-settings.social-link') }}" method="post" id="modal-social-link">
                        @csrf
                        <div class="form-group">
                            <label for="recipient-name" id="social_link" class="col-form-label">{{ __('page.facebook') }}:</label>
                            <input type="text" class="form-control" value="Creative Tim" name="link_input" id="link_input">
                            <input type="hidden" name="op" id="op">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" data-label="Update" id="btn-submit-request" data-btnid="btn-submit-request" data-callback="modal_social_call_back" data-loading="<i class='fa-spinner fas fa-circle-notch'></i>" data-form="modal-social-link" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary">{{ __('page.update') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('corejs')
<script src="{{ asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js') }}"></script>
<!-- Start: date picker -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
<!-- End: date picker -->
@stop
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/plugins/choices.min.js') }}"></script>
<script src="{{ asset('/common-js/finance.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/scripts/pages/file-upload-with-form.js') }}"></script>

<script>
 // load trading account data
    var groupt_list = $('#trading_account').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "lengthChange": false,
        "pageLength": 5,
        "info": false,
        "ajax": {
            "url": "/user/user-admin/trading-account",
            "type": "GET",
            "dataSrc": "data",
        },
        "columns": [{
                "data": "platform"
            },
            {
                "data": "account_number"
            }, {
                "data": null,
                "render": function(data, type, full, meta) {
                    return '<a href="' + data.location_url + '" class="btn btn-sm btn-outline-warning">View</a>';
                }
            }
        ],
        language: {
            paginate: {
                previous: "<",
                next: ">",
            },
        },
        "fnDrawCallback": function(oSettings) {
            // Clear the existing list items
            $('#trading_account').empty();
            $('#trading-ac-data-list').empty();

            // Add new list items based on the data from DataTables
            var data = oSettings.json.data;
            if (data.length == 0) {
                // Display the default DataTables empty message in the listItem
                var listItem = `<div class="col-md-12 text-center">No Trading available</div>`;
                $('#trading-ac-data-list').append(listItem);
            } else {
                var listItem = `<ul>`;
                for (var i = 0; i < data.length; i++) {
                    var platform = "";
                    var account_number = data[i].account_number;
                    if (data[i].platform === "MT4") {
                        platform += `<div class="avatar me-3">
                                <img src="{{ asset('trader-assets/assets/img/logos/platform-logo/mt4.png') }}" alt="kal" class="border-radius-lg shadow">
                            </div>`;
                    } else if (data[i].platform === "MT5") {
                        platform += `<div class="avatar me-3">
                                <img src="{{ asset('trader-assets/assets/img/logos/platform-logo/mt5.png') }}" alt="kal" class="border-radius-lg shadow">
                            </div>`;
                    }
                    listItem += `<li class="list-group-item border-0 d-flex align-items-center px-0 mb-2"> ${platform} 
                            <div class="d-flex align-items-start flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">Account No: <span class="text-secondary">${account_number}</span></h6>
                                <p class="mb-0 text-xs"><span class="text-dark text-bolder">{{ __('page.platform') }}: </span>
                                    ${data[i].platform}
                                </p>
                            </div>
                            <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto btn-load-balance" href="javascript:;" data-id="${data[i].id}">
                                <span class="d-flex justify-content-between" style="min-width:100px">
                                    <span>&dollar; <span class="balance-value amount">0</span></span>
                                    <span><i class="fas fa-sync" aria-hidden="true"></i></span>
                                </span>
                            </a>
                        </li>`;
                }
                listItem += "</ul>";
                $('#trading-ac-data-list').append(listItem);
            }
        }
    });
    // end trading account data
    function getLink() {
        $.ajax({
            type: "GET",
            url: "/user/user-admin/profile-overview-social",
            dataType: "json",
            success: function(res) {
                console.log("res",res.social_link)
                var socialLinkData = res.social_link;
                var html = '';
                if (socialLinkData.facebook || socialLinkData.twitter || socialLinkData.skype || socialLinkData.whatsapp || socialLinkData.linkedin || socialLinkData.telegram) {
                    html += '<strong class="text-dark text-sm">Social:</strong> &nbsp';
                }
                if (socialLinkData.facebook) {
                    html += '<a class="btn btn-facebook btn-simple mb-0 ps-1 pe-2 py-0" target="_blank" href="https://' + socialLinkData.facebook + '"><i class="fab fa-facebook fa-lg"></i></a>';
                }

                if (socialLinkData.twitter) {
                    html += '<a class="btn btn-twitter btn-simple mb-0 ps-1 pe-2 py-0" target="_blank" href="https://' + socialLinkData.twitter + '"><i class="fab fa-twitter fa-lg"></i></a>';
                }
                if (socialLinkData.skype) {
                    html += '<a class="btn btn-twitter btn-simple mb-0 ps-1 pe-2 py-0" target="_blank" href="https://join.skype.com/invite/' + socialLinkData.skype + '"><i class="fab fa-skype fa-lg"></i></a>';
                }
                if (socialLinkData.whatsapp) {
                    html += '<a class="btn btn-twitter btn-simple mb-0 ps-1 pe-2 py-0" target="_blank" href="https://api.whatsapp.com/send?phone=' + socialLinkData.whatsapp + '"><i class="fab fa-whatsapp fa-lg"></i></a>';
                }
                if (socialLinkData.linkedin) {
                    html += '<a class="btn btn-twitter btn-simple mb-0 ps-1 pe-2 py-0" target="_blank" href="https://' + socialLinkData.linkedin + '"><i class="fab fa-linkedin fa-lg"></i></a>';
                }
                if (socialLinkData.telegram) {
                    html += '<a class="btn btn-twitter btn-simple mb-0 ps-1 pe-2 py-0" target="_blank" href="https://' + socialLinkData.telegram + '"><i class="fab fa-telegram fa-lg"></i></a>';
                }

                $("#logoSocialIcon").html(html);
            }
        });
    }
    getLink();
    submit_wait("#btn-submit-request");

    function visible() {
        var elem = document.getElementById('profileVisibility');
        if (elem) {
            if (elem.innerHTML == "Switch to visible") {
                elem.innerHTML = "Switch to invisible"
            } else {
                elem.innerHTML = "Switch to visible"
            }
        }
    }
    var openFile = function(event) {
        var input = event.target;
        // Instantiate FileReader
        var reader = new FileReader();
        reader.onload = function() {
            imageFile = reader.result;
            document.getElementById("imageChange").innerHTML = '<img width="200" src="' + imageFile +
                '" class="rounded-circle w-100 shadow" />';
        };
        reader.readAsDataURL(input.files[0]);
    };

    //profile update callback function
    function user_info_call_back(data) {
        // console.log(data);
        if (data.status == true) {
            notify('success', data.message, 'User Details');
            $('#full-name').val(data.full_name);
            if (data.gender == 'Female') {
                $('#choices-gender option[value="Famale"]').val(data.gender);
            } else if (data.gender == 'Male') {
                $('#choices-gender option[value="Male"]').val(data.gender);
            }
            $('#date_of_birth').val(data.date_of_birth);
            $('#phone').val(data.phone);
            $('#state').val(data.state);
            $('#city').val(data.city);
            $('#zipcode').val(data.zipcode);
            $('#address').val(data.address);
            $('#basicInfoModal').modal('toggle');
            location.reload();
        }
        if (data.status == false) {
            notify('error', data.message, 'Update User');
        }
        $.validator("basic-info-form", data.errors);
    };
    //social link name update

    $(document).on("click", ".btn-link-edit", function() {
        if ($(this).data('label') == 'Whatsapp') {
            $('#popupheader').html($(this).data('label') + " Number");
        } else if ($(this).data('label') == 'Skype') {
            $('#popupheader').html($(this).data('label') + " ID");
        } else {
            $('#popupheader').html($(this).data('label') + " Profile URL");
        }
        $('#social_link').html($(this).data('label'));
        $('#link_input').val($(this).data('link'));
        $('#op').val($(this).data('label'));
    })

    //modal social update call back function
    function modal_social_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Social link');
            $("#" + data.id + "-text").text(data.link);
            $('#socialModal').modal('hide');
            $("#" + data.id + "-text").closest('li').find('.fa-plus').removeClass('fa-plus').addClass('fa-edit');
            getLink();
        }
        if (data.status == false) {
            notify('error', data.message, 'Social link');
        }
        $.validator("modal-social-link", data.errors);
    }

    // load balance from api
    $(document).on("click", ".btn-load-balance", function() {
        let $this = $(this);
        let account = $(this).data('id');
        balance_equity($this, account, 'balance'); //finance js
    });
    
    // Date picker
    if (document.querySelector('.datepicker')) {
        flatpickr('.datepicker', {
            // mode: "range"
            static: true
        });
    }
    // id proof--------------
    file_upload(
        "/user/profile-picture/upload", //<--request url for proccessing
        false, //<---auto process true or false
        ".profile-picture-dropzone",  //<---dropzones selectore
        "profile-picture-form", //<---form id/selectore
        "#btn-save-profit-picture", //<---submit button selectore
        "Profile Picture" //<---Notification Title
    );
</script>
@stop
