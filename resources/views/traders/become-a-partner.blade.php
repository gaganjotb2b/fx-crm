@extends('layouts.trader-auth')
@section('title', 'IB Registration')
@section('style')
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

    .language-nav {
        float: right;
        position: absolute;
        top: 13px;
        left: 20px;
    }

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
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-12 text-center">
        <h3 class="mt-5">Become A Partner</h3>
        <h5 class="text-secondary font-weight-normal">This information will let us know more about you.</h5>

        <div class="multisteps-form mb-5">
            <!--progress bar-->
            <div class="row">
                <div class="col-12 col-lg-8 mx-auto my-5">
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
                <div class="col-12 col-lg-8 m-auto">
                    <form class="multisteps-form__form" id="ib-registration-form" action="{{route('user.become-a-partner')}}" method="post">
                        @csrf
                        <input type="hidden" name="op" value="step-persional">
                        <input type="hidden" name="op_social" value="{{$social_account}}">
                        <input type="hidden" name="referKey" value="{{ $referKey }}">
                        <input type="hidden" name="manager" value="{{$manager}}">
                        <!--Persional section-->
                        <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                            <div class="multisteps-form__content">
                                <div class="row mt-3 px-4">
                                    <!-- full name -->
                                    <div class="col-12 col-sm-6 text-start">
                                        <div class="form-group">
                                            <label for="full-name">Full Name</label>
                                            <input class="multisteps-form__input form-control" type="text" value="{{ auth()->user()->name }}" name="full_name" id="full-name" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 text-start">
                                        <div class="form-group" id="gender-grp">
                                            <label>Gender</label>
                                            <select class="form-control" name="gender" id="gender">
                                                <option value="">Please choose your gender</option>
                                                <option value="male" <?php echo $user_descriptions->gender == "male" ? "selected" : "" ?>>Male</option>
                                                <option value="female" <?php echo $user_descriptions->gender == "female" ? "selected" : "" ?>>Female</option>
                                                <option value="other" <?php echo $user_descriptions->gender == "other" ? "selected" : "" ?>>Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 text-start">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input class="multisteps-form__input form-control" type="email" name="email" value="{{ auth()->user()->email }}" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 text-start">
                                        <div class="form-group">
                                            <label>Confirm Email</label>
                                            <input class="multisteps-form__input form-control" type="email" name="confirm_email" value="{{ auth()->user()->email }}" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 text-start">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input class="multisteps-form__input form-control" type="text" name="phone" value="{{ auth()->user()->phone }}" />
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
                                                <input type="text" id="date_of_birth" class="flatpickr-basic border w-100 date_picker_field" name="date_of_birth" value="{{ $user_descriptions->date_of_birth }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-row d-flex mt-4">
                                    <!-- <div class="col-4"></div> -->
                                    <div class="col-11 mx-auto p-0 m-0">
                                        <a href="{{ route('trader.dashboard') }}" class="nav-link p-0 m-0" style="max-width: 200px; float: left;">
                                            <button type="button" class="btn btn-block btn-sm bg-gradient-info">
                                                Go Back To Trader Dashboard
                                            </button>
                                        </a>
                                        <button class="btn bg-gradient-primary ms-auto mb-0 float-end" id="personal-submit" data-label="Next" data-btnid="personal-submit" data-callback="trader_reg_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="ib-registration-form" data-el="fg" onclick="_run(this)" type="button" title="Next" style="width: 200px;">Next</button>
                                    </div>
                                    <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" id="personal-next" type="button" title="Next">Next</button>
                                </div>
                            </div>
                        </div>
                        <!--Address section-->

                        <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                            <div class="row text-center">
                                <div class="col-10 mx-auto">
                                    <h5 class="font-weight-normal">Where from you? (Your Address)</h5>
                                    <p>Give us more details about you</p>
                                </div>
                            </div>
                            <div class="multisteps-form__content">
                                <div class="row mt-4">
                                    <div class="col-sm-3 ms-auto">
                                        <div class="avatar avatar-xxl position-relative">
                                            <img src="{{ asset('admin-assets\app-assets\images\avatars\avater-men.png') }}" class="border-radius-md" alt="team-2">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                        <div class="form-group">
                                            <label>Country</label>
                                            <select class="form-control" name="country" id="country">
                                                <option value="">Select Your Country</option>
                                                @foreach($countries as $value)
                                                <option value="{{$value->id}}" <?php echo $user_descriptions->country_id == $value->id ? "selected" : "" ?>>{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>State</label>
                                            <input class="multisteps-form__input form-control" type="text" name="state" value='{{ $user_descriptions->state ?? "" }}' />
                                        </div>
                                        <div class="form-group">
                                            <label>City</label>
                                            <input class="multisteps-form__input form-control" type="text" name="city" value='{{ $user_descriptions->city ?? "" }}' />
                                        </div>
                                        <div class="form-group">
                                            <label>Zip Code</label>
                                            <input class="multisteps-form__input form-control" type="text" name="zip_code" value='{{ $user_descriptions->zip_code ?? "" }}' />
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <textarea name="address" id="address" rows="3" class="form-control">{{ $user_descriptions->address ?? "" }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-row d-flex mt-4">
                                    <!-- <div class="col-4">

                                    </div> -->
                                    <div class="col-11 mx-auto p-0 m-0">
                                        <a href="{{ route('trader.dashboard') }}" class="nav-link p-0 m-0" style="max-width: 200px; float: left;">
                                            <button type="button" class="btn btn-block btn-sm bg-gradient-info">
                                                Go Back To Trader Dashboard
                                            </button>
                                        </a>
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
                                    <div class="col-sm-3 ms-auto">
                                        <div class="avatar avatar-xxl position-relative">
                                            <img src="{{ asset('admin-assets\app-assets\images\avatars\avater-men.png') }}" class="border-radius-md" alt="team-2">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                        <div class="form-group">
                                            <label>Skype</label>
                                            <input class="multisteps-form__input form-control" type="text" name="skype" value='{{ $user_social->skype ?? "" }}' />
                                        </div>
                                        <div class="form-group">
                                            <label>Linkedin</label>
                                            <input class="multisteps-form__input form-control" type="text" name="linkedin" value='{{ $user_social->linkedin ?? "" }}' />
                                        </div>
                                        <div class="form-group">
                                            <label>Facebook</label>
                                            <input class="multisteps-form__input form-control" type="text" name="facebook" value='{{ $user_social->facebook ?? "" }}' />
                                        </div>
                                        <div class="form-group">
                                            <label>Twitter</label>
                                            <input class="multisteps-form__input form-control" type="text" name="twitter" value='{{ $user_social->twitter ?? "" }}' />
                                        </div>
                                        <div class="form-group">
                                            <label>Telegram</label>
                                            <input class="multisteps-form__input form-control" type="text" name="telegram" value='{{ $user_social->telegram ?? "" }}' />
                                        </div>
                                    </div>
                                </div>
                                <div class="button-row d-flex mt-4">
                                    <div class="col-4">
                                        <a href="{{ route('trader.dashboard') }}" class="nav-link p-0 m-0" style="max-width: 200px; float: left;">
                                            <button type="button" class="btn btn-block btn-sm bg-gradient-info">
                                                Go Back To Trader Dashboard
                                            </button>
                                        </a>
                                    </div>
                                    <div class="col-6 mx-auto p-0 m-0">
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
                                    <div class="col-sm-4 ms-auto">
                                        <div class="avatar avatar-xxl position-relative">
                                            <i class="fas fa-lock text-dark" style="font-size: 5rem;"></i>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                        <div class="password_gen">
                                            <label>Password</label>
                                            <div class="input-group pasGen-form-group password_ch_toltip">
                                                <button class="copy_btn" type="button">Copy</button>
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
                                    </div>
                                    <div class="button-row d-flex mt-4">
                                        <!-- <div class="col-4">

                                    </div> -->
                                        <div class="col-11 mx-auto p-0 m-0">
                                            <a href="{{ route('trader.dashboard') }}" class="nav-link p-0 m-0" style="max-width: 200px; float: left;">
                                                <button type="button" class="btn btn-block btn-sm bg-gradient-info">
                                                    Go Back To Trader Dashboard
                                                </button>
                                            </a>
                                            <button style="margin-left:16rem !important" class="btn bg-gradient-light mb-0 js-btn-prev me-1 float-start" type="button" title="Prev">Prev</button>
                                            <button class="btn bg-gradient-primary ms-auto mb-0 float-end" id="confirm-submit" data-label="Next" data-btnid="confirm-submit" data-callback="trader_reg_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="ib-registration-form" data-el="fg" onclick="_run(this)" type="button" title="Next" style="width: 200px;">Submit</button>
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

<!-- <div class="row text-center">
    <div class="col-10 mx-auto">
        <ul class="navbar-nav language-nav">
            <li class="nav-item dropdown dropdown-language" style="margin-right: 1rem;">
                <a href="{{ route('trader.dashboard') }}" class="nav-link">
                    <button type="button" class="btn btn-block btn-sm bg-gradient-info">
                        Go Back To Trader Dashboard
                    </button>
                </a>
            </li>
        </ul>
    </div>
</div> -->

@stop
@section('page-js')
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
            $('input[name="op"]').val('step-social');
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

    // prev button click 
    $(document).on("click", ".js-btn-prev", function() {
        var currentOP = $('input[name="op"]').val();
        if (currentOP == 'step-address') {
            $('input[name="op"]').val('step-persional');
        } else if (currentOP == 'step-social') {
            $('input[name="op"]').val('step-address');
        } else if (currentOP == 'step-confirm') {
            $('input[name="op"]').val('step-social');
        }

    })
</script>
<!-- END: Page JS-->
@stop