@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Open Live Trading Account')
@section('page-css')
    <!-- style for lite crm -->
    @if (App\Services\systems\VersionControllService::check_version() === 'lite')
        <link id="pagestyle" href="{{ asset('trader-assets/assets/css/soft-ui-dashboard.css?v=1.0.8') }}" rel="stylesheet" />
    @endif
    <link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css') }}">
    <style>
        .error-msg {
            color: red;
        }

        #b-icon-dollar {
            font-size: 3rem;
        }

        .row.gx-2.gx-sm-3.custom-otp-boxs {
            min-height: 300px;
        }

        .was-validated .form-control:valid,
        .form-control.is-valid {
            border-color: #66d432;
            padding-right: unset;
            background-image: url("");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1rem 1rem;
        }

        .was-validated .form-control:invalid,
        .form-control.is-invalid {
            border-color: #fd5c70;
            padding-right: unset;
            background-image: url("");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1rem 1rem;
        }
    </style>
@stop
@section('bread_crumb')
    <!-- bread crumb -->
    {!! App\Services\systems\BreadCrumbService::get_trader_breadcrumb() !!}
@stop
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12 text-center">
                <h3 class="mt-5">{{ __('page.open-live-account') }}</h3>
                <h5 class="text-secondary font-weight-normal">
                    {{ __('page.this-information-will-let-us-know-more-about-you') }}</h5>
                <div class="multisteps-form mb-5">
                    <!--progress bar-->
                    <div class="row">
                        <div class="col-12 col-lg-8 mx-auto my-5">
                            <div class="multisteps-form__progress">
                                <!-- progress platform -->
                                <button class="multisteps-form__progress-btn js-active" type="button" title="User Info"
                                    disabled>
                                    <span>{{ __('page.platform') }}</span>
                                </button>
                                <!-- progress otp -->
                                @if ($otp_settings == true && $user_otp_settings == true)
                                    @if ($otp_settings->account_create == true && $user_otp_settings->account_create == true)
                                        <button class="multisteps-form__progress-btn" type="button" title="Address"
                                            disabled>
                                            <span>{{ __('page.otp') }}</span>
                                        </button>
                                    @endif
                                @endif
                                <!-- progress save info -->
                                <button class="multisteps-form__progress-btn" type="button" title="Order Info" disabled>
                                    <span>{{ __('page.save-info') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!--form panels-->
                    <div class="row">
                        <div class="col-12 col-lg-8 m-auto">
                            <form class="multisteps-form__form bg-custom-dark-for rounded-3"
                                action="{{ route('user.trading-account.open-live-account-form') }}" method="post"
                                id="live-account-form">
                                <!-- <form class="form-demo" > -->
                                <!--single form panel-->
                                <input type="hidden" id="op" name="op" value="step-1">
                                <input type="hidden" name="bonus_id" value="{{ request()->bonus }}">
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active"
                                    data-animation="FadeIn">
                                    <div class="row text-center">
                                        <!-- first step heading and description -->
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">
                                                {{ __('page.let\'s-start-with-the-platform-information') }}</h5>
                                            <p>{{ __('page.please-first-choose-a-server-or-platform-then-choose-an-account-type-and-finaly-choose-leverage-if-you-need-any-help-contact-our-help-desk') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row mt-3">
                                            <!-- first step platform logo -->
                                            <div class="col-12 col-sm-4">
                                                <div class="avatar avatar-xxl position-relative">
                                                    @php
                                                        $platform_logo =
                                                            get_platform() == 'mt4' ? 'mt4.png' : 'mt5.png';
                                                    @endphp
                                                    <img id="platform-logo"
                                                        src="{{ asset('trader-assets/assets/img/logos/platform-logo/' . $platform_logo) }}"
                                                        class="border-radius-md" alt="team-2">
                                                </div>
                                            </div>
                                            <!-- first step form -->
                                            <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                                @if (\App\Services\AllFunctionService::kyc_required(auth()->user()->id, 'open-account') == false)
                                                    @csrf
                                                    {{-- single or multiple platform handle from the component --}}
                                                    {{-- check condition single platform true or false --}}
                                                    {{-- if platform is single then platform field will be hidden and not otherwise --}}
                                                    <x-platform-option account-type="live"
                                                        use-for="user_portal"></x-platform-option>
                                                    <div class="form-group">
                                                        <label for="client-group">{{ __('page.account-type') }}</label>
                                                        <select class="form-control multisteps-form__input "
                                                            id="client-group" name="account_type">
                                                            <option value="">{{ __('page.choose-an-account-type') }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="leverage">{{ __('page.leverage') }}</label>
                                                        <select class="form-control multisteps-form__input" id="leverage"
                                                            name="leverage">
                                                            <option value="">{{ __('page.choose-a-leverage') }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                @else
                                                    <!-- warning kyc required -->
                                                    <div class="col-8 mx-auto">
                                                        <div class="alert alert-warning" role="alert">
                                                            <strong>Warning!</strong> KYC Required for Open Account
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4"></div>
                                            <div class="col-sm-6 mx-auto">
                                                @php
                                                    $disable = '';
                                                    $has_multi_submit = has_multi_submit('demo-account', 30);
                                                    if ($has_multi_submit) {
                                                        $disable = 'disabled';
                                                    }
                                                @endphp
                                                @if (\App\Services\AllFunctionService::kyc_required(auth()->user()->id, 'open-account') == false)
                                                    <button type="button" data-label="Next" id="btn-submit-request"
                                                        data-btnid="btn-submit-request"
                                                        data-callback="open_live_account_call_back"
                                                        data-loading="<i class='fa-spin fas fa-circle-notch'></i>"
                                                        data-form="live-account-form" data-el="fg" onclick="_run(this)"
                                                        class="btn bg-gradient-primary ms-auto float-end mb-0 mt-4 btn-submit-request"
                                                        data-op="step-1"
                                                        data-submit_wait="{{ submit_wait('demo-account', 30) }}"
                                                        {{ $disable }}
                                                        style="width:200px">{{ __('page.next') }}</button>
                                                @endif
                                                <button
                                                    class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden"
                                                    type="button" title="Next"
                                                    id="js-btn-next">{{ __('page.next') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- otp checking -->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white"
                                    data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{ __('page.confirm-its-you?') }}</h5>
                                            <p>{{ __('page.we-check-otp-for-your-account-make-secure-check-its-you?-please-check-your-email-we-sent-6-digit-code-to-your-email') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">

                                        <div class="row mt-3">
                                            <div class="col-12 col-sm-4">
                                                <div class="avatar avatar-xxl position-relative">
                                                    @php
                                                        $platform_logo =
                                                            get_platform() == 'mt4' ? 'mt4.png' : 'mt5.png';
                                                    @endphp
                                                    <img id="platform-logo"
                                                        src="{{ asset('trader-assets/assets/img/logos/platform-logo/' . $platform_logo) }}"
                                                        class="border-radius-md" alt="team-2">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                                <div
                                                    class=" text-center row gx-2 gx-sm-3 custom-otp-boxs border p-6 rounded">
                                                    <input type="hidden" name="otp" id="otp">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" name="otp_1"
                                                                class="form-control form-control-lg otp-value"
                                                                maxlength="1" autocomplete="off" autocapitalize="none">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" name="otp_2"
                                                                class="form-control form-control-lg otp-value"
                                                                maxlength="1" autocomplete="off" autocapitalize="none">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" name="otp_3"
                                                                class="form-control form-control-lg otp-value"
                                                                maxlength="1" autocomplete="off" autocapitalize="none">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" name="otp_4"
                                                                class="form-control form-control-lg otp-value"
                                                                maxlength="1" autocomplete="off" autocapitalize="none">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" name="otp_5"
                                                                class="form-control form-control-lg otp-value"
                                                                maxlength="1" autocomplete="off" autocapitalize="none">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" name="otp_6"
                                                                class="form-control form-control-lg otp-value"
                                                                maxlength="1" autocomplete="off" autocapitalize="none">
                                                        </div>
                                                    </div>
                                                    <span
                                                        class="text-muted text-sm">{{ __('page.haven\'t-received-it?') }}
                                                        <button type="button" id="resend-code-click"
                                                            data-label="Resend Code" class="btn mb-0 text-capitalize"
                                                            data-loading="<i class='fa-spin fas fa-circle-notch'></i>">{{ __('page.resend-code') }}</button>
                                                        <button type="button" data-label="Resend Code"
                                                            id="btn-resend-code" data-btnid="btn-resend-code"
                                                            data-callback="open_live_account_call_back"
                                                            data-loading="<i class='fa-spin fas fa-circle-notch'></i>"
                                                            data-form="live-account-form" data-el="fg"
                                                            onclick="_run(this)"
                                                            class="btn mb-0 text-capitalize btn-submit-request visually-hidden"
                                                            data-op="step-2"
                                                            data-submit_wait="{{ submit_wait('resend-code', 30) }}"
                                                            {{ $disable }}
                                                            style="width:200px">{{ __('page.resend-code') }}</button>.</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4"></div>
                                            <div class="col-sm-6 mx-auto pe-0">
                                                @php
                                                    $disable = '';
                                                    $has_multi_submit = has_multi_submit('live-account', 30);
                                                    if ($has_multi_submit) {
                                                        $disable = 'disabled';
                                                    }
                                                @endphp
                                                <button type="button" data-label="Submit Request"
                                                    id="btn-submit-request-final" data-btnid="btn-submit-request-final"
                                                    data-callback="open_live_account_call_back"
                                                    data-loading="<i class='fa-spin fas fa-circle-notch'></i>"
                                                    data-form="live-account-form" data-el="fg" onclick="_run(this)"
                                                    class="btn bg-gradient-primary ms-auto float-end mb-0 mt-4 btn-submit-request"
                                                    data-op="step-2"
                                                    data-submit_wait="{{ submit_wait('live-account', 30) }}"
                                                    {{ $disable }}
                                                    style="width:200px">{{ __('page.submit-request') }}</button>
                                                <button
                                                    class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden"
                                                    type="button" title="Next"
                                                    id="js-btn-next-2">{{ __('page.next') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--account information -->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white"
                                    data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">{{ __('page.your-account-information') }}
                                                ({{ __('page.save-it\'s') }})</h5>
                                            <p>{{ __('page.please-save-your-account-information-in-a-save-zone-its-need-to-access-your-account-its-also-need-to-make-your-transaction-or-other-criteria') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row mt-4">
                                            <div class="col-8 mx-auto">
                                                <div class="form-control multisteps-form__input" contentEditable="true"
                                                    id="copy_account_info" rows="10" autofucus=>
                                                    <table class="table text-start">
                                                        <tr>
                                                            <th>{{ __('page.account-no') }}: </th>
                                                            <td id="c-account-no"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __('page.master-password') }}: </th>
                                                            <td id="c-masster-pass"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __('page.investor-password') }}: </th>
                                                            <td id="c-investor-pass"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Server Name: </th>
                                                            <td class="border-bottom">Core Prime Ltd</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <button class="btn bg-gradient-primary ms-auto mt-3 float-end"
                                                    id="btn-copy" type="button">
                                                    <i class="fas fa-copy"></i>
                                                    {{ __('page.copy-info') }}
                                                </button>
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
        @include('layouts.footer')
    </div>
@stop
@section('corejs')
    <script src="{{ asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js') }}"></script>
@stop
@section('page-js')
    <script src="{{ asset('trader-assets/assets/js/plugins/multistep-form.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/scripts/pages/get-client-group.js') }}"></script>
    <script src="{{ asset('trader-assets/assets/js/scripts/pages/file-upload-with-form.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js') }}"></script>
    <script src="{{ asset('/common-js/copy-js.js') }}"></script>
    <script>
        $(document).on('click', '#btn-submit-request', function() {
            $(this).prop('disabled', true);
        })
        submit_wait("#btn-submit-request");
        submit_wait("#btn-submit-request-final");
        $("#live-account-form").trigger("reset");
        $("#op").val('step-1');
        // open demo trading account--------------
        $(document).on("click", "#resend-code-click", function() {
            $("#op").val('resend');
            $(this).html($(this).data('loading'));
            $("#btn-resend-code").trigger('click');
        })

        function open_live_account_call_back(data) {
            if (data.status == true) {
                notify('success', data.message, 'Open trading account');
                $("#live-account-form").trigger('reset');
                $("#js-btn-next-2").trigger('click');
                $("#c-account-no").text(data.account_no);
                $("#c-investor-pass").text(data.inv_password);
                $("#c-masster-pass").text(data.master_password);
                $("#c-phone-pass").text(data.phone_password);
            }

            if (data.otp_send == true) {
                $("#op").val('step-2');
                $("#resend-code-click").html($("#resend-code-click").data('label'));
                // $("#js-btn-next").trigger('click');
            }

            if (data.otp_status == false) {
                notify('error', data.message, 'OTP Errors');
                $.each(data.errors, function(index, value) {
                    $("input[name=" + index + "]").addClass('is-invalid');
                });
            }
            if (data.valid_status == false) {
                notify('error', data.message, 'Open trading account');
                $.validator("live-account-form", data.errors);
            }
            if (data.valid_status == true) {
                $("#js-btn-next").trigger('click');
                $("#op").val('step-2');
                submit_wait("#btn-submit-request-final", data.submit_wait);
                $.validator("live-account-form", data.errors);
            }
            if (data.status == false) {
                notify('error', data.message, 'Open trading account');
                $.validator("live-account-form", data.errors);
            }
            $('#btn-submit-request').prop('disabled', false);
            submit_wait("#btn-submit-request", data.submit_wait);
            $('.multisteps-form__panel').height_control()
        }
        // auto fucus
        $(window).on("load", function() {
            $("#copy_account_info").focus();
        });
        // copy content from editable div
        $(document).on("click", "#btn-copy", function() {
            // copy js
            copy_to_clipboard("copy_account_info"); //provide id of text container
        });
        // onload server-------------------
        $(document).ready(function() {
            let server = $("#server").val();
            // get client js
            get_client_group(server, 'live', 'server');
        });
        // change server-------------------
        $(document).on("change", "#server", function() {
            let server = $(this).val();
            // get client js
            get_client_group(server, 'live', 'server');
        });

        // change account type/client group
        $(document).on("change", "#client-group", function() {
            let client_group = $(this).val();
            // get client js
            get_client_group(server, 'live', 'client-group', client_group);
        });

        // otp input fucus
        $(document).on("keyup", ".otp-value", function() {
            let $value = $(this).val();
            if ($value != "") {
                $(this).addClass('is-valid').removeClass('is-invalid');
                $(this).closest(".col").next(".col").find(".otp-value").focus();
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        });
    </script>
@stop
