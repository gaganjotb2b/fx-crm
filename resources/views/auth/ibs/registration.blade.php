<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="{{get_company_name()}} is a broker company focuses in Forex Trading. We believe in transparency, accountability, and accuracy of services. Experience trading in the most seamless way, straight to global market, and the easiness of withdrawal.">
    <meta name="keywords" content="{{get_company_name()}} is operated by {{get_company_name()}} and has registered in Saint Vincent & the Grenadines with LLC number 892 LLC 2021, regulated by the Financial Services Authority (FSA) of Saint Vincent and the Grenadines. High Risk Warning : Before you enter foreign exchange and stock markets, you have to remember that trading currencies and other investment products is trading in nature and always involves a considerable risk. As a result of various financial fluctuations, you may not only significantly increase your capital, but also lose it completely.">
    <meta name="author" content="{{get_company_name()}}">

    <link rel="stylesheet" href="{{ asset('trader-assets/assets/css/root-color.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ get_favicon_icon() }}">
    <link rel="icon" type="image/png" href="{{ get_favicon_icon() }}">
    <title id="minutes">{{ strtoupper(config('app.name')) }} - IB Registration </title>

    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('trader-assets/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('trader-assets/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ asset('trader-assets/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
    <link id="pagestyle" href="{{ asset('trader-assets/assets/css/soft-ui-dashboard.css?v=1.0.8') }}" rel="stylesheet" />
    @php $themeColor = get_theme_colors_forAll('user_theme') @endphp
    <style>
        :root {
            --custom-primary: <?= $themeColor->primary_color ?? '#D1B970' ?>;
            --custom-form-color: <?= $themeColor->form_color ?? '#979fa6' ?>;
            --bs-body-color: <?= $themeColor->body_color ?? '#67748e' ?>;
        }

        .card .card-header {
            padding: 1.5rem;
            border-left: none !important;
        }

        .text-border::before {
            right: 0.5em;
            margin-left: -38%;
        }

        .brand-logo {
            top: 10%;
            left: 24%;
            position: absolute;
        }

        .error-msg {
            color: red;
        }

        .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: var(--custom-primary);
            outline: 0;
            box-shadow: none !important;
        }

        .navbar-nav.language-nav,
        .nav-item.dropdown.dropdown-language {
            height: 50px;
        }

        .card {
            box-shadow: none !important;
        }
    </style>
    <style>
        .date_picker_field:focus {
            color: #495057;
            background-color: #fff;
            border-color: var(--custom-primary);
            outline: 0;
            box-shadow: 0 0 0 2px var(--custom-primary);
        }

        #date_of_birth {
            border-top-right-radius: 0.5rem !important;
            border-bottom-right-radius: 0.5rem !important;
            font-size: 0.9rem;
            padding-left: 1rem;
        }

        .input-rang-group-date-logo {
            display: flex;
            align-items: center;
            padding: 0.6rem 0.6rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.45;
            color: #6e6b7b;
            text-align: center;
            white-space: nowrap;
            background-color: #fff;
            border-top-left-radius: 0.5rem !important;
            border-bottom-left-radius: 0.5rem !important;
            border-right: none !important;
        }

        .error-msg {
            color: red;
            font-size: 14px;
            display: block;
        }

        /* .language-nav {
            float: right;
            position: absolute;
            top: 13px;
            right: -52px;
        } */

        .choices__inner .error-msg {
            position: absolute;
            bottom: -26px;
            left: 0;
        }

        .date-of-birth-gp .error-msg {
            position: absolute;
            bottom: -26px;
        }

        #server-grp .choices[data-type*="select-one"] .choices__input,
        #gender-grp .choices[data-type*="select-one"] .choices__input,
        #account-type-grp .choices[data-type*="select-one"] .choices__input {
            display: none;
            width: 100%;
            padding: 10px;
            border-bottom: 1px solid #dddddd;
            background-color: #ffffff;
            margin: 0;
        }

        .flag-icon {
            margin-right: 5px;
        }

        .pasGen-form-group {
            position: relative;
        }

        .copy_btn {
            position: absolute;
            top: -31px;
            right: 0;
            z-index: 99;
            border: none;
            background: var(--custom-primary);
            padding: 0 12px;
            display: none;
            border-radius: 5px !important;
            color: #fff;
        }

        .copy_btn::after {
            content: '';
            position: absolute;
            width: 10px;
            height: 10px;
            top: 24px;
            left: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-top: 8px solid var(--custom-primary);
            border-bottom: 8px solid transparent;
            right: 0;
            margin: 0 auto;
        }

        .btn-gen-password {
            color: #fff;
        }

        .copy_password {
            border: 1px solid #d2d6da !important;
            padding: 0.5rem 0.75rem !important;
        }

        .info-icon {
            margin-right: -5px;
            background: var(--custom-primary);
            color: #fff;
        }

        .input-group-text+.form-control {
            padding-left: 10px !important;
        }

        .pass_toltip_content {
            margin: 0;
            background: #E0E5EA;
            font-size: 13px;
            position: absolute;
            top: -190px;
            padding: 19px 25px;
            border-radius: 5px !important;
            display: none;
            list-style: none;
            z-index: 99999;
        }

        .pass_toltip_content::after {
            content: '';
            position: absolute;
            width: 10px;
            height: 10px;
            top: 100%;
            left: 3px;
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-top: 20px solid #E0E5EA;
            border-bottom: 15px solid transparent;
        }

        .pas_info_text {
            margin: 0;
            font-size: 16px;
        }

        .pass_toltip_content li i {
            margin-right: 5px;
        }

        .page-header {
            overflow: inherit;
        }
        .img{
            background: aliceblue;
            border-radius: 4px;
            padding: 0.3rem;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3  navbar-transparent mt-4">
        <div class="container">

            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon mt-2">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </span>
            </button>
        </div>
    </nav>
    <!-- End Navbar -->
    <main class="main-content  mt-0">
        <!-- <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('{{ asset('/trader-assets/assets/img/curved-images/curved1.jpg') }}')">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 text-center mx-auto">
                        <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 text-white" href="#">
                            <img class="img mb-4" src="{{ get_user_logo() }}" alt="{{ config('app.name') }}" height="50">
                        </a>
                        <p class="text-lead text-white">This information will let us know more about you.</p>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-color: var(--custom-primary);">
            <!-- <span class="mask bg-gradient-dark opacity-6"></span> -->
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 text-center mx-auto">
                        <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 text-white" href="#">
                            <img class="img mb-4" src="{{ get_user_logo() }}" alt="{{ config('app.name') }}" height="50">
                        </a>
                        <p class="text-lead text-white">This information will let us know more about you.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-xl-6 col-lg-6 col-md-6 mx-auto">
                    <div class="card z-index-0">
                        <div class="card-header text-center pt-4">
                            <h5 class="text-primary text-gradient">IB Registration</h5>
                        </div>
                        <div class="multisteps-form mb-5">
                            <!--progress bar-->
                            <div class="row">
                                <div class="col-12 col-lg-12 mx-auto my-1">
                                    <div class="multisteps-form__progress">
                                        <button disabled class="multisteps-form__progress-btn js-active" type="button" title="User Info">
                                            <span>Personal</span>
                                        </button>
                                        <button disabled class="multisteps-form__progress-btn" type="button" title="Address">
                                            <span>Address</span>
                                        </button>
                                        @if($social_account==1)
                                        <button disabled class="multisteps-form__progress-btn" type="button" title="Order Info">
                                            <span>Social</span>
                                        </button>
                                        @endif
                                        <button disabled class="multisteps-form__progress-btn" type="button" title="Order Info">
                                            <span>Confirm</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!--form panels-->
                            <div class="row">
                                <div class="col-12 col-lg-12 m-auto">
                                    <form class="multisteps-form__form" id="ib-registration-form" action="{{route('ib.registration')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="op" value="step-persional">
                                        <input type="hidden" name="op_social" value="{{$social_account}}">
                                        <input type="hidden" name="referKey" value="{{ $referKey }}">
                                        <input type="hidden" name="manager" value="{{$manager}}">
                                        <!--Persional section-->
                                        <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                            <div class="row text-center">
                                                <div class="col-10 mx-auto">
                                                    <h5 class="font-weight-normal">Let's start with the basic information</h5>
                                                    <p>Let us know your name and email address. Use an address you don't mind other users contacting you at</p>
                                                    <ul class="navbar-nav language-nav">
                                                        <li class="nav-item dropdown dropdown-language" style="margin-right: 1rem;">
                                                            <a class="nav-link dropdown-toggle" id="dropdown-flag" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                @if(session()->get('locale')=='fr')
                                                                @php ($lang = __('language.french'))
                                                                @php ($flag = 'fr')
                                                                @elseif(session()->get('locale')=='de')
                                                                @php( $lang = __('language.german'))
                                                                @php( $flag = 'de')
                                                                @elseif(session()->get('locale')=='pt')
                                                                @php( $lang = __('language.portuguese'))
                                                                @php( $flag = 'pt')
                                                                @elseif(session()->get('locale')=='zh')
                                                                @php( $lang = __('language.chinese'))
                                                                @php( $flag = 'cn')
                                                                @else
                                                                @php( $lang = __('language.english'))
                                                                @php( $flag = 'us')
                                                                @endif
                                                                <i class="flag-icon flag-icon-{{$flag}}"></i>
                                                                <span class="selected-language">
                                                                    {{$lang}}
                                                                </span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-flag">
                                                                <a class="dropdown-item lang-change" href="#" data-language="en"><i class="flag-icon flag-icon-us"></i>{{__('language.english')}}</a>
                                                                <a class="dropdown-item lang-change" href="#" data-language="fr"><i class="flag-icon flag-icon-fr"></i> {{__("language.french")}}</a>
                                                                <a class="dropdown-item lang-change" href="#" data-language="de"><i class="flag-icon flag-icon-de"></i> {{__('language.german')}}</a>
                                                                <a class="dropdown-item lang-change" href="#" data-language="pt"><i class="flag-icon flag-icon-pt"></i> {{__('language.portuguese')}}</a>
                                                                <a class="dropdown-item lang-change" href="#" data-language="zh"><i class="flag-icon flag-icon-cn"></i> {{__('language.chinese')}}</a>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="multisteps-form__content">
                                                <div class="row mt-3 px-4">
                                                    <!-- full name -->
                                                    <div class="col-12 col-sm-6 text-start">
                                                        <div class="form-group">
                                                            <label for="full-name">Full Name</label>
                                                            <input class="multisteps-form__input form-control" type="text" placeholder="Eg. Michael" name="full_name" id="full-name" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6 text-start">
                                                        <div class="form-group">
                                                            <label>Phone</label>
                                                            <input class="multisteps-form__input form-control" type="text" name="phone" placeholder="Eg. +10161675XXXX" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6 text-start">
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input class="multisteps-form__input form-control" type="email" name="email" placeholder="Eg. tomson@example.com" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6 text-start">
                                                        <div class="form-group">
                                                            <label>Confirm Email</label>
                                                            <input class="multisteps-form__input form-control" type="email" name="confirm_email" placeholder="Eg. tomson@example.com" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6 text-start">
                                                        <div class="form-group" id="gender-grp">
                                                            <label>Gender</label>
                                                            <select class="form-control" name="gender" id="gender">
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                                        <div class="form-group">
                                                            <label>Date of Birth</label>
                                                            <div class="col-12 d-flex date-of-birth-gp position-relative">
                                                                <span class="input-rang-group-date-logo border">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                                                    </svg>
                                                                </span>
                                                                <input type="text" id="date_of_birth" class="flatpickr-basic border w-100 date_picker_field" name="date_of_birth" placeholder="YY-MM-DD">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="button-row d-flex mt-4">
                                                    <div class="col-4"></div>
                                                    <div class="col-6 mx-auto">
                                                        <button class="btn bg-gradient-primary ms-auto mb-0 float-end" id="personal-submit" data-label="Next" data-btnid="personal-submit" data-callback="trader_reg_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="ib-registration-form" data-el="fg" onclick="_run(this)" type="button" title="Next" style="width: 200px;">Next</button>
                                                    </div>
                                                    <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" id="personal-next" type="button" title="Next">Next</button>
                                                </div>
                                            </div>
                                        </div>
                                        <!--Address section-->

                                        <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                            <div class="multisteps-form__content">
                                                <div class="row mt-4">
                                                    <div class="col-12 col-sm-12 mx-auto mt-4 mt-sm-0 text-start">
                                                        <div class="form-group">
                                                            <label>Country</label>
                                                            <select class="form-control" name="country" id="country">
                                                                <option value="">Select Your Country</option>
                                                                @foreach($countries as $value)
                                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>State</label>
                                                            <input class="multisteps-form__input form-control" type="text" name="state" placeholder="Your state" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>City</label>
                                                            <input class="multisteps-form__input form-control" type="text" name="city" placeholder="Your city" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Zip Code</label>
                                                            <input class="multisteps-form__input form-control" type="text" name="zip_code" placeholder="Your zip code" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Address</label>
                                                            <textarea name="address" id="address" rows="3" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="button-row d-flex mt-4">
                                                    <div class="col-12 mx-auto">
                                                        <button class="btn bg-gradient-primary ms-auto mb-0 float-end" id="addresss-submit" data-label="Next" data-btnid="address-submit" data-callback="trader_reg_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="ib-registration-form" data-el="fg" onclick="_run(this)" type="button" title="Next" style="width: 200px;">Next</button>
                                                        <button class="btn bg-gradient-light mb-0 js-btn-prev me-1 float-end" type="button" title="Prev">Prev</button>
                                                    </div>
                                                    <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" id="address-next" type="button" title="Next">Next</button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- social section -->
                                        @if($social_account==1)
                                        <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                            <div class="row text-center">
                                                <div class="col-10 mx-auto">
                                                    <h5 class="font-weight-normal">Your Social Accounts(Optional).</h5>
                                                    <p>Give us more details about you</p>
                                                </div>
                                            </div>
                                            <div class="multisteps-form__content">
                                                <div class="row mt-4">
                                                    <!-- <div class="col-sm-3 ms-auto">
                                                        <div class="avatar avatar-xxl position-relative">
                                                            <img src="{{ asset('admin-assets\app-assets\images\avatars\avater-men.png') }}" class="border-radius-md" alt="team-2">
                                                        </div>
                                                    </div> -->
                                                    <div class="col-12 col-sm-12 mx-auto mt-4 mt-sm-0 text-start">
                                                        <div class="form-group">
                                                            <label>Skype</label>
                                                            <input class="multisteps-form__input form-control" type="text" name="skupe" placeholder="Optional" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Linkedin</label>
                                                            <input class="multisteps-form__input form-control" type="url" name="linkedin" placeholder="Optional" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Facebook</label>
                                                            <input class="multisteps-form__input form-control" type="url" name="facebook" placeholder="Optional" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Twitter</label>
                                                            <input class="multisteps-form__input form-control" type="url" name="twitter" placeholder="Optional" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Telegram</label>
                                                            <input class="multisteps-form__input form-control" type="url" name="telegram" placeholder="Optional" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="button-row d-flex mt-4">
                                                    <div class="col-12 mx-auto">
                                                        <button class="btn bg-gradient-primary ms-auto mb-0 float-end" id="social-submit" data-label="Next" data-btnid="social-submit" data-callback="trader_reg_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="ib-registration-form" data-el="fg" onclick="_run(this)" type="button" title="Next" style="width: 200px;">Next</button>
                                                        <button class="btn bg-gradient-light mb-0 js-btn-prev me-1 float-end" type="button" title="Prev">Prev</button>
                                                    </div>
                                                    <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" id="social-next" type="button" title="Next">Next</button>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <!-- account section -->
                                        <!--single form panel-->
                                        <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                                            <div class="row text-center">
                                                <div class="col-10 mx-auto">
                                                    <h5 class="font-weight-normal">Secure your account</h5>
                                                    <p>Password Should be at least six characters</p>
                                                </div>
                                            </div>
                                            <div class="multisteps-form__content">
                                                <div class="row text-start">
                                                    <!-- <div class="col-sm-4 ms-auto">
                                                        <div class="avatar avatar-xxl position-relative">
                                                            <i class="fas fa-lock text-dark" style="font-size: 5rem;"></i>
                                                        </div>
                                                    </div> -->
                                                    <!-- <div class="col-12 col-sm-12 mx-auto mt-4 mt-sm-0 text-start">
                                                        <label>Password</label>
                                                        <div class="input-group">
                                                            <input class="form-control" name="password" data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" placeholder="Password" type="password" id="new-password">
                                                            <span class="input-group-text position-relative bg-gradient-primary cursor-pointer btn-gen-password" style="padding:13px">
                                                                <i class="fas fa-key"></i>
                                                            </span>
                                                        </div>
                                                        <label>Confirm Password</label>
                                                        <input class="multisteps-form__input form-control" type="password" name="confirm_password" placeholder="Confirm Password" />
                                                        <label>Transaction Password</label>
                                                        <div class="input-group">
                                                            <input class="form-control" name="transaction_password" data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" placeholder="Transaction Password" type="password" id="trans-password">
                                                            <span class="input-group-text position-relative bg-gradient-primary cursor-pointer btn-gen-password" style="padding:13px">
                                                                <i class="fas fa-key"></i>
                                                            </span>
                                                        </div>
                                                        <label>Confirm Transaction Password</label>
                                                        <input class="multisteps-form__input form-control" type="password" name="confirm_transaction_password" placeholder="Confirm Transaction Password" />
                                                    </div> -->
                                                    <div class="col-12 col-sm-12 mx-auto mt-4 mt-sm-0 text-start">
                                                        <div class="password_gen">
                                                            <label>Password</label>
                                                            {{-- <i class="fas fa-times"></i>  <i class="fas fa-check"></i>--}}
                                                            <div class="input-group pasGen-form-group password_ch_toltip">
                                                                <ul class="pass_toltip_content">
                                                                    <h6 class="pas_info_text">Password Must:</h6>
                                                                    <li class="pwd-restriction-length"><i class="fas fa-info-circle"></i> Be between 10-16 characters in length</li>
                                                                    <li class="pwd-restriction-upperlower"><i class="fas fa-info-circle"></i> Contain at least 1 lowercase and 1 uppercase letter</li>
                                                                    <li class="pwd-restriction-number"><i class="fas fa-info-circle"></i> Contain at least 1 number (0–9)</li>
                                                                    <li class="pwd-restriction-special"><i class="fas fa-info-circle"></i> Contain at least 1 special character (!@#$%^&()'[]"?+-/*)</li>
                                                                </ul>
                                                                <button class="copy_btn" type="button">Copy</button>
                                                                <span class="input-group-text position-relative bg-gradient-primary cursor-pointer  info-icon" style="padding:13px">
                                                                    <i class="fas fa-info"></i>
                                                                </span>
                                                                <input class="form-control copy_password check_password_chrac" name="password" data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" placeholder="Password" type="password" id="new-password">
                                                                <span class="input-group-text position-relative bg-gradient-primary cursor-pointer btn-gen-password" style="padding:13px">
                                                                    <i class="fas fa-key"></i>
                                                                </span>
                                                            </div>
                                                            <label>Confirm Password </label>
                                                            <div class="input-group pasGen-form-group">
                                                                <input data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" class="multisteps-form__input form-control password_gen copy-pass-input" type="password" name="confirm_password" placeholder="Confirm Password" id="confirm-password" />
                                                            </div>
                                                        </div>
                                                        <!-- transection password  -->


                                                        <!-- <div class="password_gen">
                                                            <label>Transaction Password</label>
                                                            <div class="input-group pasGen-form-group password_ch_toltip">
                                                                <ul class="pass_toltip_content">
                                                                    <h6 class="pas_info_text">Password Must:</h6>
                                                                    <li class="pwd-restriction-length"><i class="fas fa-info-circle"></i> Be between 10-16 characters in length</li>
                                                                    <li class="pwd-restriction-upperlower"><i class="fas fa-info-circle"></i> Contain at least 1 lowercase and 1 uppercase letter</li>
                                                                    <li class="pwd-restriction-number"><i class="fas fa-info-circle"></i> Contain at least 1 number (0–9)</li>
                                                                    <li class="pwd-restriction-special"><i class="fas fa-info-circle"></i> Contain at least 1 special character (!@#$%^&()'[]"?+-/*)</li>
                                                                </ul>
                                                                <button class="copy_btn" type="button">Copy</button>
                                                                <span class="input-group-text position-relative bg-gradient-primary cursor-pointer  info-icon" style="padding:13px">
                                                                    <i class="fas fa-info"></i>
                                                                </span>
                                                                <input class="form-control copy_password check_password_chrac" name="transaction_password" data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" placeholder="Transaction Password" type="password" id="trans-password">
                                                                <span class="input-group-text position-relative bg-gradient-primary cursor-pointer btn-gen-password" style="padding:13px">
                                                                    <i class="fas fa-key"></i>
                                                                </span>
                                                            </div>
                                                            <label>Confirm Transaction Password</label>
                                                            <input data-size="16" data-character-set="a-z,A-Z,0-9,#" class="multisteps-form__input form-control copy-pass-input" type="password" rel="gp" name="confirm_transaction_password" placeholder="Confirm Transaction Password" id="confirm-transaction-pass" />
                                                        </div> -->
                                                    </div>
                                                    <div class="d-flex mt-4">
                                                        <div class="col-12 mx-auto">
                                                            <button class="btn bg-gradient-primary ms-auto mb-0 float-end" id="confirm-submit" data-label="Next" data-btnid="confirm-submit" data-callback="trader_reg_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="ib-registration-form" data-el="fg" onclick="_run(this)" type="button" title="Next" style="width: 200px;">Submit</button>
                                                            <button class="btn bg-gradient-light mb-0 js-btn-prev me-1 float-end" type="button" title="Prev">Prev</button>
                                                        </div>
                                                        <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" id="password-next" type="button" title="Next">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- include login footer -->
    @include('layouts.login-footer')
    <!--   Core JS Files   -->
    <script src="{{ asset('trader-assets/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/core/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>

    <script src="{{ asset('trader-assets/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <!-- Kanban scripts -->
    <script src="{{ asset('trader-assets/assets/js/plugins/dragula/dragula.min.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/plugins/jkanban/jkanban.js') }}"></script>

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- common ajax -->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('admin-assets/src/js/core/confirm-alert.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/common-ajax.js') }}"></script>
    <script src="{{ asset('/common-js/custom-from-validation.js') }}"></script>
    <!-- enter key handler -->
    <script src="{{asset('common-js/enter-key-handler.js')}}"></script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
    <script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
    <script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
    <script src="{{ asset('trader-assets/assets/js/plugins/multistep-form.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/plugins/choices.min.js') }}"></script>
    <script src="{{asset('/common-js/copy-js.js')}}"></script>
    <script src="{{asset('/common-js/password-gen.js')}}"></script>

    <!-- END: Page JS-->
    <script>
        if (document.getElementById('country')) {
            var country = document.getElementById('country');
            const example = new Choices(country);
        }
        if (document.getElementById('gender')) {
            var gender = document.getElementById('gender');
            const gender_choice = new Choices(gender);
        }
        if (document.getElementById('server')) {
            var server = document.getElementById('server');
            const server_choice = new Choices(server);
        }

        var openFile = function(event) {
            var input = event.target;

            // Instantiate FileReader
            var reader = new FileReader();
            reader.onload = function() {
                imageFile = reader.result;

                document.getElementById("imageChange").innerHTML = '<img width="200" src="' + imageFile + '" class="rounded-circle w-100 shadow" />';
            };
            reader.readAsDataURL(input.files[0]);
        };
    </script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
        // password character check 
        $('.check_password_chrac').focusin(function() {
            $(this).closest('.password_ch_toltip').find('.pass_toltip_content').show();
        });
        $('.check_password_chrac').focusout(function() {
            $(this).closest('.password_ch_toltip').find('.pass_toltip_content').hide();
        });
        $('.password_ch_toltip').find('.check_password_chrac').keyup(function() {

            var pwdLength = /^.{10,16}$/;
            var pwdUpper = /[A-Z]+/;
            var pwdLower = /[a-z]+/;
            var pwdNumber = /[0-9]+/;
            var pwdSpecial = /[!@#$%^&()'[\]"?+-/*={}.,;:_]+/;
            pwdLength.test($(this).val());

            var s = $(this).val();

            if (pwdLength.test(s)) {
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-length i').css("color", "green");
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-length i').removeClass('fa-info-circle');
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-length i').addClass('fa-check-circle');
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-length i').removeClass("fa-times-circle");
            } else {
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-length i').css("color", "#E84B21");
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-length i').removeClass("fa-check-circle");
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-length i').removeClass("fa-info-circle");
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-length i').addClass("fa-times-circle");
            }
            if (pwdUpper.test(s) && pwdLower.test(s)) {
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-upperlower i').css("color", "green");
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-upperlower i').removeClass('fa-info-circle');
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-upperlower i').addClass('fa-check-circle');
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-upperlower i').removeClass("fa-times-circle");
            } else {
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-upperlower i').css("color", "#E84B21");
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-upperlower i').removeClass("fa-check-circle");
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-upperlower i').removeClass("fa-info-circle");
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-upperlower i').addClass("fa-times-circle");
            }
            if (pwdNumber.test(s)) {
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-number i').css("color", "green");
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-number i').removeClass('fa-info-circle');
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-number i').addClass('fa-check-circle');
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-number i').removeClass("fa-times-circle");
            } else {
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-number i').css("color", "#E84B21");
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-number i').removeClass("fa-check-circle");
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-number i').removeClass("fa-info-circle");
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-number i').addClass("fa-times-circle");
            }
            if (pwdSpecial.test(s)) {
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-special i').css("color", "green");
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-special i').removeClass('fa-info-circle');
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-special i').addClass('fa-check-circle');
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-special i').removeClass("fa-times-circle");
            } else {
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-special i').css("color", "#E84B21");
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-special i').removeClass("fa-check-circle");
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-special i').removeClass("fa-info-circle");
                $(this).closest('.password_ch_toltip').find('.pwd-restriction-special i').addClass("fa-times-circle");
            }
        });




        // genrate randome password
        $(document).on('click', ".btn-gen-password", function() {
            var field = $(this).closest('div.password_gen').find('input[rel="gp"]');
            field.val(rand_string(field));
            field.attr('type', 'text');
            $(this).closest('div.password_gen').find('.copy_btn').show();
        });
        $('.copy_btn').on("click", function(e) {
            e.preventDefault();
            $(this).html('Copyed');
            setTimeout(() => {
                $(this).hide();
                $(this).html('Copy');
            }, 1000);
            let id = $(this).closest('div.password_gen').find('.copy-pass-input').attr('id');
            $(this).closest('div.password_gen').find('.copy-pass-input').select();
            if ($(this).closest('div.password_gen').find('.copy-pass-input').val() != "") {
                copy_to_clipboard(id);
            }
            $(this).closest('div.password_gen').find('input[rel="gp"]').attr('type', 'password');
        });
        // registration call back
        $('input[name="op"]').val('step-persional');

        function trader_reg_call_back(data) {
            if (data.persional_status == true) {
                $('input[name="op"]').val('step-address');
                $("#personal-next").trigger('click');
            }
            // step address validation check
            if (data.address_status == true) {
                if ($("input[name='op_social']").val() == 1) {
                    $('input[name="op"]').val('step-social');
                }
                if ($("input[name='op_account']").val() == 1) {
                    $('input[name="op"]').val('step-account');
                }
                if (($("input[name='op_social']").val() !== '1') && ($("input[name='op_account']").val() !== '1')) {
                    $('input[name="op"]').val('step-confirm');
                }
                if (($("input[name='op_social']").val() == '1') && ($("input[name='op_account']").val() == '1')) {
                    $('input[name="op"]').val('step-social');
                }
                $("#address-next").trigger('click');
            }
            // step address validation check
            if (data.social_status == true) {
                // meta account auto create ativated
                if ($("input[name='op_account']").val() == 1) {
                    $('input[name="op"]').val('step-account');
                }
                // meta account auto create disabled
                else {
                    $('input[name="op"]').val('step-confirm');
                }
                $("#social-next").trigger('click');
            }
            if (data.account_status == true) {
                $('input[name="op"]').val('step-confirm');
                $("#account-next").trigger('click');
            }
            // check final status
            if (data.status == true) {
                $('input[name="op"]').val('step-persional');
                notify('success', data.message, "IB Registration");
                $("#ib-registration-form").trigger('reset');
                window.location.href = "/ib/success";
            }
            if (data.status == false) {
                notify('error', data.message, "IB Registration");
            }
            $("#ib-registration-form").css({
                "height": "600px !important"
            })
            $.validator("ib-registration-form", data.errors);
            //SETTING PROPER FORM HEIGHT ONRESIZE
            setFormHeight();

        }
        // disable final step button
        $(document).on('click', '#personal-submit', function() {
            $(this).prop('disabled', true);
            setTimeout(() => {
                $(this).prop('disabled', false);
            }, 3000);
        });
        $(document).on('click', '#addresss-submit', function() {
            $(this).prop('disabled', true);
            setTimeout(() => {
                $(this).prop('disabled', false);
            }, 3000);
        });
        $(document).on('click', '#social-submit', function() {
            $(this).prop('disabled', true);
            setTimeout(() => {
                $(this).prop('disabled', false);
            }, 3000);
        });
        $(document).on('click', '#confirm-submit', function() {
            $(this).prop('disabled', true);
            setTimeout(() => {
                $(this).prop('disabled', false);
            }, 3000);
        });
        // end disable final step button
        // prev button click 
        $(document).on("click", ".js-btn-prev", function() {
            var currentOP = $('input[name="op"]').val();
            if (currentOP == 'step-address') {
                $('input[name="op"]').val('step-persional');
            } else if (currentOP == 'step-social') {
                $('input[name="op"]').val('step-address');
            } else if (currentOP == 'step-confirm') {
                if ($("input[name='op_social']").val() == 1) {
                    $('input[name="op"]').val('step-social');
                } else {
                    $('input[name="op"]').val('step-address');
                }
            }

        })

        // get account category data for registrations------------------------------------
        $(document).on("change", "#server", function() {
            let server = $(this).val();
            $.ajax({
                url: '/admin/client-management/get-account-type/' + server + "?op=demo",
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $("#account-type").html(data);
                    $("#account-type").data('server', server);
                }
            });
        })
        // end: get account category data------------------------------------------
        // get client group data for registrations------------------------------------
        $(document).on("change", "#account-type", function() {

            let group_id = $(this).val();
            $.ajax({
                url: '/admin/client-management/get-leverage/' + group_id,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // $("#account-type").html(data.client_groups);
                    $("#leverage").html(data);
                }
            });
        })
        // end: get client group data------------------------------------------
    </script>
    <!-- language change -->
    <script>
        (function(window, document, $) {
            $(document).on('click', ".lang-change", function() {
                let lang = $(this).data('language');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/user/change-language',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        lang: lang
                    },
                    success: function(data) {
                        if (data.status === true) {
                            location.reload();
                        }
                    }
                });
            });
        })(window, document, jQuery);
    </script>
    <!-- END: Page JS-->
</body>

</html>