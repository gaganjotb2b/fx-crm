@extends('layouts.trader-auth')
@section('title', 'Trader Registration')
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
    }

    .language-nav {
        float: right;
        position: absolute;
        top: 13px;
        right: -52px;
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
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-12 text-center">
        <h3 class="mt-5">Build Your Demo Profile</h3>
        <h5 class="text-secondary font-weight-normal">This information will let us know more about you.</h5>
        <div class="multisteps-form mb-5">
            <!--progress bar-->
            <div class="row">
                <div class="col-12 col-lg-8 mx-auto my-5">
                    <div class="multisteps-form__progress">
                        <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">
                            <span>Personal</span>
                        </button>
                        <button class="multisteps-form__progress-btn" type="button" title="Address">
                            <span>Address</span>
                        </button>
                        @if($social_account==1)
                        <button class="multisteps-form__progress-btn" type="button" title="Order Info">
                            <span>Social</span>
                        </button>
                        @endif
                        @if($create_meta_account==1)
                        <button class="multisteps-form__progress-btn" type="button" title="Address">
                            <span>Account</span>
                        </button>
                        @endif
                        <button class="multisteps-form__progress-btn" type="button" title="Order Info">
                            <span>Confirm</span>
                        </button>
                    </div>
                </div>
            </div>
            <!--form panels-->
            <div class="row">
                <div class="col-12 col-lg-8 m-auto">
                    <form class="multisteps-form__form" id="trader-registration-form" action="{{route('trader.demo-registration')}}" method="post">
                        @csrf
                        <input type="hidden" name="op" value="step-persional">
                        <input type="hidden" name="op_account" value="{{$create_meta_account}}">
                        <input type="hidden" name="op_social" value="{{$social_account}}">
                        <!--Persional section-->
                        <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                            <div class="row text-center">
                                <div class="col-10 mx-auto">
                                    <h5 class="font-weight-normal">Let's start with the basic information</h5>
                                    <p>Let us know your name and email address. Use an address you don't mind other users contacting you at</p>
                                    <ul class="navbar-nav language-nav" style="width: 200px;">
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
                                    <div class="col-12 col-sm-6 text-start">
                                        <div class="form-group">
                                            <label for="full-name">Full Name</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="Eg. Michael" name="full_name" id="full-name" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 text-start">
                                        <div class="form-group" id="gender-grp">
                                            <label>Gender</label>
                                            <select class="form-control" name="gender" id="gender">
                                                <option value="">Please choose your gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 text-start">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input class="multisteps-form__input form-control" type="text" name="email" placeholder="Eg. tomson@example.com" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 text-start">
                                        <div class="form-group">
                                            <label>Confirm Email</label>
                                            <input class="multisteps-form__input form-control" type="email" name="confirm_email" placeholder="Eg. tomson@example.com" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 text-start">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input class="multisteps-form__input form-control" type="text" name="phone" placeholder="Eg. +10161675XXXX" />
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
                                        <button class="btn bg-gradient-primary ms-auto mb-0 float-end" id="personal-submit" data-label="Next" data-btnid="persoanl-submit" data-callback="trader_reg_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="trader-registration-form" data-el="fg" onclick="_run(this)" type="button" title="Next" style="width: 200px;">Next</button>
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
                                    <p>Give us more details about you. What do you enjoy doing in your spare time?</p>
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
                                    <div class="col-4"></div>
                                    <div class="col-6 mx-auto">
                                        <button class="btn bg-gradient-primary ms-auto mb-0 float-end" id="addresss-submit" data-label="Next" data-btnid="address-submit" data-callback="trader_reg_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="trader-registration-form" data-el="fg" onclick="_run(this)" type="button" title="Next" style="width: 200px;">Next</button>
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
                                    <h5 class="font-weight-normal">Your Social Accounts.</h5>
                                    <p>Give us more details about you. What do you enjoy doing in your spare time?</p>
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
                                            <input class="multisteps-form__input form-control" type="text" name="skupe" placeholder="john@example.com" />
                                        </div>
                                        <div class="form-group">
                                            <label>Linkedin</label>
                                            <input class="multisteps-form__input form-control" type="url" name="linkedin" placeholder="http://" />
                                        </div>
                                        <div class="form-group">
                                            <label>Facebook</label>
                                            <input class="multisteps-form__input form-control" type="url" name="facebook" placeholder="http://" />
                                        </div>
                                        <div class="form-group">
                                            <label>Twitter</label>
                                            <input class="multisteps-form__input form-control" type="url" name="twitter" placeholder="http://" />
                                        </div>
                                        <div class="form-group">
                                            <label>Telegram</label>
                                            <input class="multisteps-form__input form-control" type="url" name="telegram" placeholder="http://" />
                                        </div>
                                    </div>
                                </div>
                                <div class="button-row d-flex mt-4">
                                    <div class="col-4"></div>
                                    <div class="col-6 mx-auto">
                                        <button class="btn bg-gradient-primary ms-auto mb-0 float-end" id="social-submit" data-label="Next" data-btnid="social-submit" data-callback="trader_reg_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="trader-registration-form" data-el="fg" onclick="_run(this)" type="button" title="Next" style="width: 200px;">Next</button>
                                        <button class="btn bg-gradient-light mb-0 js-btn-prev me-1 float-end" type="button" title="Prev">Prev</button>
                                    </div>
                                    <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" id="social-next" type="button" title="Next">Next</button>
                                </div>
                            </div>
                        </div>
                        @endif
                        <!-- account section -->
                        @if($create_meta_account==1)
                        <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                            <div class="row text-center">
                                <div class="col-10 mx-auto">
                                    <h5 class="font-weight-normal">Create Your Trading account.</h5>
                                    <p>Give us more details about you. What do you enjoy doing in your spare time?</p>
                                </div>
                            </div>
                            <div class="multisteps-form__content">
                                <div class="row mt-4">
                                    <div class="col-sm-3 ms-auto">
                                        <div class="avatar avatar-xxl position-relative">
                                            <img src="{{ asset('trader-assets/assets/img/logos/platform-logo/mt5.png') }}" class="" alt="team-2">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                        <!-- <label>Approximate Investment</label>
                                        <input class="multisteps-form__input form-control" type="email" placeholder="Eg. 2" /> -->
                                        <div class="form-group" id="server-grp">
                                            <label>Platform</label>
                                            <select class="form-control" name="platform" id="server">
                                                <option value="">Please choose a server</option>
                                                @if($platform==='mt4')
                                                <option value="mt4">Mt4</option>
                                                @endif
                                                @if($platform==='mt5')
                                                <option value="mt5">MT5</option>
                                                @endif
                                                @if($platform==='both')
                                                <option value="mt4">Mt4</option>
                                                <option value="mt5">MT5</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group" id="account-type-grp">
                                            <label>Account Type</label>
                                            <select class="form-control" name="account_type" id="account-type">
                                                <option value="">Please choose an account type</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Leverage</label>
                                            <select class="form-control" name="leverage" id="leverage">
                                                <option value="">Please choose leverage</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <select class="form-control" name="amount" id="ammount">
                                                <option value="">Please choose ammount</option>
                                                <option value="1000">1000</option>
                                                <option value="2000">2000</option>
                                                <option value="1000">3000</option>
                                                <option value="2000">5000</option>
                                                <option value="10000">10000</option>
                                                <option value="20000">20000</option>
                                                <option value="30000">30000</option>
                                                <option value="50000">50000</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-row d-flex mt-4">
                                    <div class="col-4"></div>
                                    <div class="col-6 mx-auto">
                                        <button class="btn bg-gradient-primary ms-auto mb-0 float-end" id="account-submit" data-label="Next" data-btnid="account-submit" data-callback="trader_reg_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="trader-registration-form" data-el="fg" onclick="_run(this)" type="button" title="Next" style="width: 200px;">Next</button>
                                        <button class="btn bg-gradient-light mb-0 js-btn-prev me-1 float-end" type="button" title="Prev">Prev</button>
                                    </div>
                                    <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" id="account-next" type="button" title="Next">Next</button>
                                </div>
                            </div>
                        </div>
                        @endif
                        <!--single form panel-->
                        <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                            <div class="row text-center">
                                <div class="col-10 mx-auto">
                                    <h5 class="font-weight-normal">Secure your account</h5>
                                    <p>One thing I love about the later sunsets is the chance to go for a walk through the neighborhood woods before dinner</p>
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
                                    </div>
                                    <div class="d-flex mt-4">
                                        <div class="col-sm-4 ms-auto"></div>
                                        <div class="col-6 mx-auto">
                                            <button class="btn bg-gradient-primary ms-auto mb-0 float-end" id="confirm-submit" data-label="Next" data-btnid="confirm-submit" data-callback="trader_reg_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="trader-registration-form" data-el="fg" onclick="_run(this)" type="button" title="Next" style="width: 200px;">Next</button>
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
            notify('success', data.message, "Trader Registration");
            $("#trader-registration-form").trigger('reset');
            window.location.href = "/success";
        }
        if (data.status == false) {
            $('input[name="op"]').val('step-persional');
            notify('error', data.message, "Trader Registration");
        }
        $("#trader-registration-form").css({
            "height": "600px !important"
        })
        $.validator("trader-registration-form", data.errors);
        //SETTING PROPER FORM HEIGHT ONRESIZE
        setFormHeight();

    }

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
@stop