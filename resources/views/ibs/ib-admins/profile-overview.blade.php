@extends(App\Services\systems\VersionControllService::get_layout('ib'))
@section('title', 'Profile Overview')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">

@stop
<!-- breadcrumb -->
@section('bread_crumb')
{!!App\Services\systems\BreadCrumbService::get_ib_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid page-ib-profile">
    <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('<?= asset('trader-assets/assets/img/curved-images/curved0.jpg') ?>'); background-position-y: 50%;">
        <span class="mask bg-gradient-primary opacity-6"></span>
    </div>
    <!-- card page header -->
    <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
        <div class="row gx-4">
            <!-- user avater -->
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <img src="{{ asset('admin-assets/app-assets/images/avatars/' . $avatar) }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                </div>
            </div>
            <!-- page tab link -->
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
                        <!-- nav item verification -->
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1 active " href="{{ route('ib.ib-admin-account-verification') }}">
                                <svg class="text-dark" width="16px" height="16px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                            <g transform="translate(1716.000000, 291.000000)">
                                                <g transform="translate(603.000000, 0.000000)">
                                                    <path class="color-background" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z">
                                                    </path>
                                                    <path class="color-background" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z" opacity="0.7"></path>
                                                    <path class="color-background" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z" opacity="0.7"></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                <span class="ms-1">{{ __('page.profile-overview') }}</span>
                            </a>
                        </li>
                        <!-- nav item settings -->
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1 " href="{{ route('ib.ib-admin-account-settings') }}">
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
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">{{ __('page.all-sub-ib') }}</h6>
                </div>
                <div class="card-body p-3">
                    <!-- <h6 class="text-uppercase text-body text-xs font-weight-bolder">Account</h6> -->
                    @for ($i = 0; $i < count($all_sub_id); $i++) <ul class="list-group">
                        <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">

                            <div class="avatar me-3">
                                <img src="{{ asset('admin-assets/app-assets/images/avatars/' . $avatar) }}" alt="profile_image" class="border-radius-lg shadow bg-primary rounded-circle">
                            </div>
                            <div class="d-flex align-items-start flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{{ __('page.name') }}: <span class="text-secondary">{{ $all_sub_id[$i]['name'] }}</span></h6>
                                <p class="mb-0 text-xs"><span class="text-dark text-bolder">{{ __('page.level') }}:
                                        {{ $all_sub_id[$i]['ib_level'] }}</span> </p>
                            </div>
                        </li>
                        </ul>
                        @endfor
                        {{ $trading_account->links() }}
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-4 mt-md-0 mt-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
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
                <!-- profile info -->
                <div class="card-body p-3">
                    <hr class="horizontal gray-light my-4">
                    <ul class="list-group">
                        <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">{{ __('page.full-name') }}:</strong> &nbsp;
                            {{ ucwords(auth()->user()->name) }}
                        </li>
                        <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">{{ __('page.mobile') }}:</strong> &nbsp;
                            <?= (auth()->user()->phone) ? ucwords(auth()->user()->phone) : "---" ?>
                        </li>
                        <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">{{ __('page.email') }}:</strong> &nbsp;
                            {{ auth()->user()->email }}
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
                        <li class="list-group-item border-0 ps-0 pb-0 logo-social-icon" id="logoSocialIcon">

                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- social links -->
        <div class="col-12 col-xl-4 mt-xl-0 mt-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">{{ __('page.social-links') }}</h6>
                </div>
                <div class="card-body p-3">
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
                    <form class="card-body pt-0" action="{{ route('ib.ib-admin-settings.basic-info') }}" method="POST" id="basic-info-form">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">{{ __('page.full-name') }}</label>
                                <div class="input-group">
                                    <input id="full-name" name="full_name" class="form-control" type="text" placeholder="Alec" required="required" value="{{ ucwords(auth()->user()->name) }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">{{ __('page.email') }}</label>
                                <div class="input-group">
                                    <input id="email" name="email" class="form-control" type="email" placeholder="Thompson" required="required" value="{{ auth()->user()->email }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label mt-4">{{ __('page.gender') }}</label>
                                <select class="form-control choice-colors" name="gender" id="choices-gender">
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
                                <select class="form-control btExport" name="country" id="country">
                                    @foreach ($countries as $value)
                                    <option value="{{ $value->id }}" <?= $value->id == $user_description->country_id ? 'selected' : '' ?>>
                                        {{ $value->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label mt-4">{{ __('page.state') }}</label>
                                <div class="input-group">
                                    <input id="state" name="state" class="form-control" value="{{ $user_description->state }}" type="text" placeholder="Your State">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label mt-4">{{ __('page.city') }}</label>
                                <div class="input-group">
                                    <input id="city" name="city" class="form-control" value="{{ $user_description->city }}" type="text" placeholder="Your City">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label mt-4">{{ __('page.phone-number') }}</label>
                                <div class="input-group">
                                    <input id="phone" name="phone" class="form-control" type="text" placeholder="+40 735 631 620" value="{{ $user_description->phone }}">
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
                                <input class="form-control" id="zipcode" name="zipcode" type="text" value="{{ $user_description->zip_code }}" placeholder="Enter Zip Code" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">{{ __('page.close') }}</button>
                    <button type="button" data-label="Update" id="btn-submit-request" data-btnid="btn-submit-request" data-callback="ib_info_call_back" data-loading="<i class='fa-spinner fas fa-circle-notch'></i>" data-form="basic-info-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary">{{ __('page.update') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal for Social links Update -->
<div class="modal fade" id="socialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalMessageTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="popupheader">{{ __('page.update-social-link') }}</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('ib.ib-admin-settings.social-link') }}" method="post" id="modal-social-link">
                    @csrf
                    <div class="form-group">
                        <label for="recipient-name" id="social_link" class="col-form-label">{{ __('page.facebook') }}:</label>
                        <input type="text" class="form-control" value="Creative Tim" name="link_input" id="link_input">
                        <input type="hidden" name="op" id="op">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">{{ __('page.close') }}</button>
                <button type="button" data-label="Update" id="btn-submit-request" data-btnid="btn-submit-request" data-callback="modal_social_call_back" data-loading="<i class='fa-spinner fas fa-circle-notch'></i>" data-form="modal-social-link" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary">{{ __('page.update') }}</button>
            </div>
        </div>
    </div>
</div>
</div>

<!-- include footer -->
@include('layouts.footer')
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
<script>
    function getLink() {
        $.ajax({
            type: "GET",
            url: "/ib/ib-admin/profile-overview-social",
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

    function neteller_withdraw_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Bank Withdraw');
            $("#neteller-withdraw-form").trigger('reset');
            $("#last-amount").text(data.d.amount);
            $("#last-txn-id").text(data.last_transaction.transaction_id);
            let status = '';
            if (data.last_transaction.approved_status === 'A') {
                status = 'Approved';
            } else if (data.last_transaction.approved_status === 'P') {
                status = 'Pending';
            } else {
                status = 'Decline';
            }
            $("#last-status").find('.badge').removeClass('badge-success, badge-warning').addClass('badge-dark').text(
                status);
            $("#btn-js-next").trigger("click");
        }
        if (data.status == false) {
            notify('error', data.message, 'Bank Withdraw');
        }
        $.validator("neteller-withdraw-form", data.errors);
        submit_wait("#btn-submit-request", data.submit_wait);
    }

    // //date picker script added here
    // if (document.getElementById('choices-gender')) {
    //     var gender = document.getElementById('choices-gender');
    //     const example = new Choices(gender);
    // }
    // if (document.getElementById('country')) {
    //     var language = document.getElementById('country');
    //     const example = new Choices(language);
    // }
    // if (document.getElementById('choices-skills')) {
    //     var skills = document.getElementById('choices-skills');
    //     const example = new Choices(skills, {
    //         delimiter: ',',
    //         editItems: true,
    //         maxItemCount: 5,
    //         removeItemButton: true,
    //         addItems: true
    //     });
    // }
    // if (document.getElementById('choices-year')) {
    //     var year = document.getElementById('choices-year');
    //     setTimeout(function() {
    //         const example = new Choices(year);
    //     }, 1);
    //     for (y = 1900; y <= 2020; y++) {
    //         var optn = document.createElement("OPTION");
    //         optn.text = y;
    //         optn.value = y;
    //         if (y == 2020) {
    //             optn.selected = true;
    //         }
    //         year.options.add(optn);
    //     }
    // }
    // if (document.getElementById('choices-day')) {
    //     var day = document.getElementById('choices-day');
    //     setTimeout(function() {
    //         const example = new Choices(day);
    //     }, 1);
    //     for (y = 1; y <= 31; y++) {
    //         var optn = document.createElement("OPTION");
    //         optn.text = y;
    //         optn.value = y;
    //         if (y == 1) {
    //             optn.selected = true;
    //         }
    //         day.options.add(optn);
    //     }
    // }
    // if (document.getElementById('choices-month')) {
    //     var month = document.getElementById('choices-month');
    //     setTimeout(function() {
    //         const example = new Choices(month);
    //     }, 1);
    //     var d = new Date();
    //     var monthArray = new Array();
    //     monthArray[0] = "January";
    //     monthArray[1] = "February";
    //     monthArray[2] = "March";
    //     monthArray[3] = "April";
    //     monthArray[4] = "May";
    //     monthArray[5] = "June";
    //     monthArray[6] = "July";
    //     monthArray[7] = "August";
    //     monthArray[8] = "September";
    //     monthArray[9] = "October";
    //     monthArray[10] = "November";
    //     monthArray[11] = "December";
    //     for (m = 0; m <= 11; m++) {
    //         var optn = document.createElement("OPTION");
    //         optn.text = monthArray[m];
    //         // server side month start from one
    //         optn.value = (m + 1);
    //         // if june selected
    //         if (m == 1) {
    //             optn.selected = true;
    //         }
    //         month.options.add(optn);
    //     }
    // }

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
    function ib_info_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'IB Details');
            $('#full-name').val(data.full_name);
            if (data.gender == 'Female') {
                $('#choices-gender option[value="Famale"]').val(data.gender);
                // $('#choices-gender').prop('selectedIndex', 0).trigger("change");
            } else if (data.gender == 'Male') {
                $('#choices-gender option[value="Male"]').val(data.gender);
                // $('#choices-gender').prop('selectedIndex', 1).trigger("change");
            }
            $('#date_of_birth').val(data.date_of_birth);
            $('#phone').val(data.phone);
            $('#state').val(data.state);
            $('#city').val(data.city);
            $('#zipcode').val(data.zipcode);
            $('#address').val(data.address);
        }
        if (data.status == false) {
            notify('error', data.message, 'IB Details');
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
</script>
@stop
