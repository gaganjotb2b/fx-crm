@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'PAMM Registration')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<style>
    .btn-check:focus+.btn-primary,
    .btn-primary:focus {
        /* color: #fff; */
        background-color: var(--custom-primary);
        border-color: unset !important;
        box-shadow: unset !important;
    }
</style>
@stop
<!-- breadcrumb -->
@section('bread_crumb')
{!!App\Services\systems\BreadCrumbService::get_trader_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid page-ib-profile">
    <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('<?= asset('comon-icon/pamm-registration.png') ?>'); background-position-y: 50%;">
        <span class="mask bg-gradient-primary opacity-6"></span>
    </div>
    <!-- card page header -->
    <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
        <div class="row gx-4">
            <!-- user avatar -->
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <img src="{{ asset(avatar()) }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
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
                    <ul class="nav nav-pills nav-fill p-1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#profile-tabs-simple" role="tab" aria-controls="profile" aria-selected="true">
                                PAMM Registration
                            </a>
                        </li>
                        <li class="nav-item d-none">
                            <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#dashboard-tabs-simple" role="tab" aria-controls="dashboard" aria-selected="false">
                                My PAMM Accounts
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
        <!-- profile information -->
        <div class="col-12 col-md-6 col-xl-4 mt-md-0 mt-4">
            <div class="card h-100">
                <div class="card-body">
                    <p>Please fill-up all the field carefully. All the fields are required. The registration is you PAMM profile settings. your share profit depend on your settings, so please submit the form carefully. You can update the PAMM profile later using this form. This form act as update an create. </p>
                    <p>If you already register an account as PAMM Choose the account and submit its will update your selected account.</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Start with just {{$require_balance = 200}} USD</li>
                        <li class="list-group-item">Get up to {{$share_profit=50}}% commission </li>
                        <li class="list-group-item">Trade on ECN or ECN Zero conditions </li>
                        <li class="list-group-item">Show your profile on the Strategy Managers Ranking page </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- social links -->
        <div class="col-12 col-xl-8 mt-xl-0 mt-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <div class="avatar avatar-xl m-r-xs">
                                <img class="bg-primary rounded-circle" src="{{ asset(avatar()) }}">
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="col-md-12">
                                <span class="widget-connection-request-info-name" id="top_username">{{auth()->user()->name}}</span>
                            </div>
                            <div class="col-md-12">
                                <span class="widget-connection-request-info-count">{{auth()->user()->email}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card  mb-4">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                                    Balance
                                                </p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    $ <span id="total_account_balance">0.00</span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card ">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                                    Equity
                                                </p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    $ <span id="total_account_equity">0.00</span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                                <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ending the avatar -->
                    <!-- starting the form -->
                    <form action="{{route('user.no-copy-pamm-registration')}}" method="post" id="form_pamm_registration">
                        @csrf
                        <!-- trading account -->
                        <div class="form-group mb-0">
                            <select class="form-control form-select" id="input_account" name="account" aria-label="Example text with button addon" aria-describedby="button-addon1">
                                <option value="">Choose an account</option>
                                @foreach ($accounts as $account)
                                <option value="{{$account->id}}">{{$account->account_number}}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- set username -->
                        <div class="mt-3">
                            <div class="form-group mb-0 fg">
                                <div class="input-group">
                                    <button class="btn btn-outline-primary mb-0" type="button" id="button-addon1">
                                        <i class="fas fa-user"></i>
                                    </button>
                                    <input type="text" id="set_username" name="username" class="form-control" placeholder="Set Username" style="padding-left: 10px; margin-left:-4px;" aria-label="Example text with button addon" aria-describedby="button-addon1">
                                </div>
                            </div>
                        </div>
                        <!-- minimum deposit -->
                        <div class="mt-3">
                            <div class="form-group mb-0 fg">
                                <div class="input-group">
                                    <button class="btn btn-outline-primary mb-0" type="button" id="button-addon2">
                                        <i class="fas fa-dollar"></i>
                                    </button>
                                    <input type="text" id="minimum_deposit" name="minimum_deposit" class="form-control" placeholder="Minimum Deposit" style="padding-left: 10px; margin-left:-4px;" aria-label="Example text with button addon" aria-describedby="button-addon2">
                                </div>
                            </div>
                        </div>
                        <!-- maximum deposit -->
                        <div class="mt-3">
                            <div class="form-group mb-0 fg">
                                <div class="input-group">
                                    <button class="btn btn-outline-primary mb-0" type="button" id="button-addon3">
                                        <i class="fas fa-dollar"></i>
                                    </button>
                                    <input type="text" id="maximum_deposit" name="maximum_deposit" class="form-control" placeholder="Maximum Deposit" style="padding-left: 10px; margin-left:-4px;" aria-label="Example text with button addon" aria-describedby="button-addon3">
                                </div>
                            </div>
                        </div>
                        <!-- profit share -->
                        <div class="mt-3">
                            <div class="form-group mb-0 fg">
                                <div class="input-group">
                                    <button class="btn btn-outline-primary mb-0" type="button" id="button-addon4">
                                        <i class="fas fa-percent"></i>
                                    </button>
                                    <input type="text" id="share_profit" name="share_profit" class="form-control" placeholder="Share Profit" style="padding-left: 10px; margin-left:-4px;" aria-label="Example text with button addon" aria-describedby="button-addon4">
                                </div>
                            </div>
                        </div>
                        <!-- submit button -->
                        <div class="mt-5">
                            <button type="button" style="width: 220px;" class="btn btn-primary float-end" id="btn_save_pamm" onclick="_run(this)" data-el="fg" data-form="form_pamm_registration" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="pammRegistrationCallback" data-btnid="btn_save_pamm">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- include footer -->
    @include('layouts.footer')
</div>
@stop

@section('corejs')

<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{ asset('trader-assets/assets/js/plugins/dropzone.min.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/plugins/flatpickr.min.js') }}"></script>
@stop
@section('page-js')
<script src="{{ asset('trader-assets/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/plugins/choices.min.js') }}"></script>

<script>
    // pamm registrations
    $(document).on('change', '#input_account', function() {
        let account = $(this).val();
        change_account(account);

    });
    $(document).ready(function() {
        change_account($("#input_account").val());
    });
    $(document).on("input", "#set_username", function() {
        let value = $(this).val();
        $("#top_username").text(value);
    });

    function change_account(account) {
        $("#total_account_equity").text('loading.....');
        $("#total_account_balance").text('loading.....');
        $.ajax({
            url: '/user/pamm/non-copy-pamm-registration/balance',
            data: {
                account: account,
            },
            success: function(response) {
                // console.log(response);
                if (response.status === true) {
                    // account balance
                    // ---------------------- 
                    $("#total_account_equity").text(response?.data?.equity || 0);
                    $("#total_account_balance").text(response?.data?.balance || 0);

                    // user data
                    // ------------------
                    $("#top_username").text(response?.pamm?.username || "{{auth()->user()->name}}");
                    $("#set_username").val(response?.pamm?.username || "{{auth()->user()->name}}");
                    $("#minimum_deposit").val(response?.pamm?.min_deposit || '');
                    $("#maximum_deposit").val(response?.pamm?.max_deposit || '');
                    $("#share_profit").val(response?.pamm?.share_profit || '');

                }
            },
            error: function(data, request, status) {
                // account balance
                // ---------------------- 
                $("#total_account_equity").text(0);
                $("#total_account_balance").text(0);

                // user data
                // ------------------
                $("#top_username").text("{{auth()->user()->name}}");
                $("#set_username").val('');
                $("#minimum_deposit").val('');
                $("#maximum_deposit").val('');
                $("#share_profit").val('');
            }
        });
    }

    $(document).on("click", "#btn_save_pamm", function() {
        $(this).prop('disabled', true);
    });

    function pammRegistrationCallback(response) {
        if (response.status === true) {
            notify('success', response.message, 'PAMM Profile');
        } else {
            notify('error', response.message, 'PAMM Profile');
        }
        $.validator("form_pamm_registration", response.errors);
        $('.error-msg').addClass('text-danger');
        $("#btn_save_pamm").prop('disabled', false);
    }
</script>
@stop