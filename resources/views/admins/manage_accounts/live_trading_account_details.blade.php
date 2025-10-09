@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'List Of Live Accounts')
@section('vendor-css')

<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
{{-- picker --}}
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
@endsection
@section('custom-css')
<style>
    .dataTables_length {
        float: left;
    }

    #datatable-trading-accounts_wrapper {
        padding: 20px;
    }

    /* loader style */
    .loader {
        border: 2px solid var(--bs-gray-500);
        border-radius: 50%;
        border-top: 2px solid var(--bs-primary);
        width: 20px;
        height: 20px;
        -webkit-animation: spin 2s linear infinite;
        /* Safari */
        animation: spin 2s linear infinite;
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: none !important;
    }
</style>
@endsection
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
                        <h2 class="content-header-title float-start mb-0">{{ __('page.Live_Trading_Account_List') }}
                        </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('finance.home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('admin-menue-left.Manage_Accounts') }}
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
                        <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="app-todo.html"><i class="me-1" data-feather="check-square"></i><span class="align-middle">Todo</span></a><a class="dropdown-item" href="app-chat.html"><i class="me-1" data-feather="message-square"></i><span class="align-middle">Chat</span></a><a class="dropdown-item" href="app-email.html"><i class="me-1" data-feather="mail"></i><span class="align-middle">Email</span></a><a class="dropdown-item" href="app-calendar.html"><i class="me-1" data-feather="calendar"></i><span class="align-middle">Calendar</span></a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Basic table -->
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        {{-- START: Filter form --}}
                        <div class="card">
                            <div class="card-header border-bottom d-flex justfy-content-between">
                                <h4 class="card-title">{{ __('client-management.Report Filter') }}</h4>
                                <div class="btn-exports d-flex justify-content-between">
                                    <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                        <option value="download" data-icon="download" selected>
                                            {{ __('client-management.Export') }}
                                        </option>
                                        <option value="csv" data-icon="file">CSV</option>
                                        <option value="excel" data-icon="file">Excel</option>
                                    </select>
                                </div>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form class="dt_adv_search" method="POST" id="filter-form">
                                    <div class="row g-1 mb-md-1">
                                        {{-- <div class="col-md-4">
                                            <label class="form-label" for="platform">{{ __('page.search_by') }}
                                                {{ __('page.platform') }}</label>
                                            <select class="select2 form-select" id="platform">
                                                <option value="">{{ __('client-management.All') }}</option>
                                                <option value="mt4">MT4</option>
                                                <option value="mt5">MT5</option>
                                            </select>
                                        </div> --}}
                                        <!-- filter by platform -->
                                        <x-platform-option account-type="live" use-for="admin_portal_report_filter"></x-platform-option>
                                        <div class="col-md-4">
                                            <!-- filter by verification status -->
                                            <label class="form-label">{{ __('client-management.Verification Status') }}</label>
                                            <select class="select2 form-select" id="verification-status">
                                                <option value="">{{ __('client-management.All') }}</option>
                                                <option value="0">{{ __('client-management.Pending') }}</option>
                                                <option value="1">{{ __('client-management.active') }}</option>
                                                <option value="2">{{ __('client-management.unverified') }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by search active -->
                                            <label class="form-label" for="finance">{{ __('client-management.Search By Active Status') }}</label>
                                            <select class="select2 form-select" id="finance" name="finance">
                                                <option value="">{{ __('client-management.All') }}</option>
                                                <option value="1">{{ __('client-management.Active') }}
                                                </option>
                                                <option value="0">{{ __('client-management.Disable') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-md-4">
                                            <!-- filter by manager -->
                                            <label class="form-label">Search By Manager</label>
                                            <input id="manager" type="text" name="manager" class="form-control dt-input" data-column="4" placeholder="Manager Name / Email" data-column-index="3" />
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by trader info -->
                                            <label class="form-label">{{ __('finance.Trader') }}
                                                {{ __('page.account_info') }}</label>
                                            <div class="mb-0">
                                                <input id="info" type="text" name="info" class="form-control dt-input" data-column="4" placeholder="Email / Name / Phone" data-column-index="3" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by ib info -->
                                            <label class="form-label">IB {{ __('page.information') }} </label>
                                            <div class="mb-0">
                                                <input id="ib_info" type="text" name="ib_info" class="form-control dt-input" data-column="4" placeholder="IB Name /Email" data-column-index="3" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-md-4">
                                            <!-- filter by leverage -->
                                            <label class="form-label">{{ __('page.leverage') }}</label>
                                            <input id="leverage" name="leverage" type="text" class="form-control dt-input" data-column="4" placeholder="Account leverage" data-column-index="3" />
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by trading account -->
                                            <label class="form-label">{{ __('page.trading-account-number') }}</label>
                                            <input id="trading_acc" type="text" name="trading_acc" class="form-control dt-input" data-column="4" placeholder="Trading Account Number" data-column-index="3" />
                                        </div>
                                        <div class="col-md-4">
                                            <!-- filter by trading account -->
                                            <label class="form-label">Account groups</label>
                                            <select class="select2 form-select" id="account_groups" name="account_groups">
                                                <option value="">{{ __('client-management.All') }}</option>
                                                @foreach($clientGroups as $value)
                                                <option value="{{$value->id}}">{{$value->group_id}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- filter buttons -->
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-md-2 ms-auto">
                                            <!-- filter reset button -->
                                            <label class="form-label">&nbsp;</label>
                                            <button id="btn-reset" type="button" class="btn btn-secondary form-control" data-column="4" data-column-index="3">{{ __('client-management.Reset') }}</button>
                                        </div>
                                        <div class="col-md-2">
                                            <!-- filter submit button -->
                                            <label class="form-label">&nbsp;</label>
                                            <button id="btn-filter" type="button" class="btn btn-primary form-control" data-column="4" data-column-index="3">{{ __('client-management.Filter') }}</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                        {{-- END: Filter form --}}

                        <div class="card">
                            <table class="datatables-basic table" id="datatable-trading-accounts">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>{{ __('page.account-number') }}</th>
                                        <th>{{ __('page.leverage') }}</th>
                                        <th>{{ __('page.group') }}</th>
                                        <th>{{ __('page.email') }}</th>
                                        <th>{{ __('page.platform') }}</th>
                                        <th>{{ __('page.status') }}</th>

                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Basic table -->
        </div>
    </div>
</div>
<!-- END: Content-->

<!-- ########## START MODALS ########## -->
<!-- Modal add comments -->
<div class="modal fade" id="primary">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel160">Comment to - <span class="comment-to"></span></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" id="form-add-comment">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-element">
                                <label class="form-label" for="exampleFormControlTextarea1">Comment</label>
                                <textarea name="comment" class="form-control" rows="3" placeholder="Comment"></textarea>
                                <input type="hidden" name="trader_id" id="trader-id">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="save-comment-btn">Save
                        Comment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal update comments -->
<div class="modal fade" id="comment-edit">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel160">Comment update to - <span class="comment-to"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" id="form-update-comment">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-element">
                                <label class="form-label">Comment</label>
                                <textarea id="text_quill_update" name="comment" class="form-control" rows="3" placeholder="Comment"></textarea>
                                <input type="hidden" name="trader_id" id="trader-id-update">
                                <input type="hidden" name="comment_id" id="comment-id">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="update-comment-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal password change -->
<div class="modal fade" id="password-change-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalCenterTitle">Password Change</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="change-password-form" action="{{ route('admin.change-password-trader-admin') }}" method="POST" class="modal-content py-0 my-0">
                @csrf
                <input type="hidden" class="trader-account-id" name="account_id">
                <input type="hidden" class="password-change-op" name="change_type">
                <input type="hidden" name="op" value="change-password">

                <div class="modal-body my-3">
                    <div class="form-group mb-1">
                        <label class="form-label" for="reset-password-new">New Password</label>
                        <div class="input-group">

                            <input type="password" class="form-control" data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" id="reset-password-new" name="password" placeholder="Enter your new password" />
                            <span class="input-group-text position-relative bg-gradient-primary cursor-pointer btn-gen-password" style="padding:13px">
                                <i class="ficon" data-feather="key"></i>
                            </span>
                        </div>

                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="reset-password-confirm">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" id="reset-password-confirm" placeholder="Retype your new password" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <!-- <button type="submit" id="set-new-password" class="btn btn-danger data-submit me-1">Change</button> -->
                    <button type="button" class="btn btn-primary" id="set-new-password" onclick="_run(this)" data-el="fg" data-form="change-password-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="change_password_callback" data-btnid="set-new-password">Save change</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal password change -->

<!-- Modal sending mail-->
<div class="modal fade text-start modal-success" id="send-mail-pass" tabindex="-1" aria-labelledby="mail-sending-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mail-sending-modal">Sending Mail.....</h5>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <p class="text-warning">Please wait, While we sending mail to - user.</p>
                    <div class="spinner-border text-success" style="width: 3rem; height: 3rem" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<!-- Modal for change group -->
<div class="modal fade" id="change-group">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Change Group</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="change-group-form" action="{{ route('admin.trading-account-details.change-group') }}" method="POST" class="modal-content py-0 my-0">
                @csrf
                <div class="modal-body my-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-element">
                                <label class="form-label">Select Group</label>
                                <select name="group_id" class="form-select form-select-lg">
                                    @foreach ($clientGroups as $clientGroup)
                                    <option value="{{ $clientGroup->id }}">{{ $clientGroup->group_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="trader_account_id" class="trader-account-id">
                            <input type="hidden" name="op" value="change-group">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="set-new-group" onclick="_run(this)" data-el="fg" data-form="change-group-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="changeGroupCallback" data-btnid="set-new-group">Save Change</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="change-leverage">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Change Leverage</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="change-leverage-form" action="{{ route('admin.trading-account-details.change-leverage') }}" method="POST" class="modal-content py-0 my-0">
                @csrf
                <div class="modal-body my-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-element">
                                <label class="form-label">Select Leverage</label>
                                <select name="leverage" class="form-select form-select-lg leverage">
                                </select>
                            </div>
                            <input type="hidden" name="trader_account_id" class="trader-account-id">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="set-new-leverage" onclick="_run(this)" data-el="fg" data-form="change-leverage-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="changeLeverageCallback" data-btnid="set-new-leverage">Save Change</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for change credit -->
<div class="modal fade" id="change-credit">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('finance.Add') }} /{{ __('finance.Deduct Credits') }} <span class="trader-name"></span></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.finance-credit-store') }}" method="post" enctype="multipart/form-data" id="admin_credit_form">
                @csrf
                <div class="modal-body my-3">
                    <div class="row">
                        <div class="col-12">
                            <!-- type -->
                            <div class="mb-2 row">
                                <label for="credit-type" class="col-sm-3 col-form-label">{{ __('finance.Type') }}</label>
                                <div class="col-sm-9 fg">
                                    <select class="select2 form-select" id="credit-type" name="type">
                                        <option value="">{{ __('finance.Select Type') }}</option>
                                        <option value="add">{{ __('finance.Credit Add') }}</option>
                                        <option value="deduct">{{ __('finance.Credit Deduction') }}</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Amount -->
                            <div class="mb-2 row">
                                <label for="credit-amount" class="col-sm-3 col-form-label">{{ __('finance.Amount') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group client-type-group">
                                        <span class="input-group-text" id="basic-addon1"><i data-feather='dollar-sign'></i></span>
                                        <input type="text" id="credit-amount" class="form-control" name="amount" placeholder="0.00" />
                                    </div>
                                </div>
                            </div>

                            <!-- trading account -->
                            <div class="mb-2 row">
                                <label for="trading_account" class="col-sm-3 col-form-label">{{ __('finance.Trading Account') }}</label>
                                <div class="col-sm-9 fg">
                                    <input class="form-control" type="text" name="trading_account" id="credit-trading_account">
                                    <!--<input type="hidden" name="trading_account" id="trading-account2">-->
                                </div>
                            </div>

                            <!-- expire date -->
                            <div class="mb-2 row">
                                <label for="expire_date" class="col-sm-3 col-form-label">{{ __('finance.Expire Date') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group client-type-group">
                                        <span class="input-group-text" id="basic-addon1"><i data-feather='calendar'></i></span>
                                        <input type="text" id="expire_date" name="expire_date" class="form-control flatpickr-human-friendly" placeholder="October 14, 2022" />
                                    </div>
                                </div>
                            </div>
                            <!-- note -->
                            <div class="mb-2 row mt-3">
                                <label for="note" class="col-sm-3 col-form-label">{{ __('finance.Note') }}</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-floating mb-0">
                                                <textarea data-length="100" name="note" class="form-control char-textarea" id="note" rows="3" placeholder="Counter" style="height: 100px"></textarea>
                                                <label for="textarea-counter">{{ __('finance.Write a note, for sending mail') }}</label>
                                            </div>
                                            <small class="textarea-counter-value float-end"><span class="char-count">0</span> / 100 </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- client -->
                            <input class="trader-id" type="hidden" name="trader" id="trader">

                            {{-- <input type="hidden" name="trader_id" class="trader-id"> --}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @php
                    $disable = '';
                    $has_multi_submit = has_multi_submit('finance-credit', 60);
                    if ($has_multi_submit) {
                    $disable = 'disabled';
                    }
                    @endphp
                    <button class="btn btn-primary float-end text-truncate" onclick="_run(this)" data-submit_wait="{{ submit_wait('finance-credit', 60) }}" type="button" id="btn-add-credit" data-el="fg" data-form="admin_credit_form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="createCallBack" data-btnid="btn-add-credit" {{ $disable }} data-i18n="Submit Request" data-label="Submit Request">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for manage fund -->
<div class="modal fade" id="manage-fund">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('finance.Fund Add') }}/{{ __('finance.Deduction Form') }} <span class="trader-name"></span></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.finance-fund-management-store') }}" method="post" id="fund-add-form">
                @csrf
                <div class="modal-body my-3">
                    <div class="row">
                        <div class="col-12">
                            <!-- type -->
                            <div class="row">
                                <label for="fund-type" class="col-sm-3 col-form-label">{{ __('finance.Type') }}</label>
                                <div class="col-sm-9 fg">
                                    <select class="select2 form-select" id="fund-type" name="type">
                                        <option value="">{{ __('finance.Select Type') }}</option>
                                        <option value="deposit">Deposit</option>
                                        <option value="withdraw">Withdraw</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Amount -->
                            <div class="mt-2 row">
                                <label for="fund-amount" class="col-sm-3 col-form-label">{{ __('finance.Amount') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group client-type-group">
                                        <span class="input-group-text" id="basic-addon1"><i data-feather='dollar-sign'></i></span>
                                        <input type="text" id="fund-amount" class="form-control" name="amount" placeholder="0.00" />
                                    </div>
                                </div>
                            </div>

                            <!-- trading account -->
                            <div class="mt-2 row">
                                <label for="fund-trading_account" class="col-sm-3 col-form-label">{{ __('finance.Trading Account') }}</label>
                                <div class="col-sm-9 fg">
                                    <input class="form-control" type="text" name="trading_account" id="fund-trading_account">
                                </div>
                            </div>
                            <!-- method -->
                            <div class="mt-2 row" id="method-row">
                                <label for="method" class="col-sm-3 col-form-label">{{ __('finance.Method') }}</label>
                                <div class="col-sm-9 fg">
                                    <select class="select2 form-select" id="method" name="transaction_method">
                                        <option value="" selected>{{ __('finance.Select a method') }}</option>
                                        <option value="cash">{{ __('finance.Cash Deposit') }}</option>
                                        <option value="voucher">{{ __('finance.Voucher Deposit') }}</option>
                                        <option value="skrill">{{ __('finance.Skrill Deposit') }}t</option>
                                        <option value="neteller">{{ __('finance.Neteller Deposit') }}</option>
                                        <option value="bank">{{ __('finance.Bank Deposit') }}</option>
                                        <option value="bitcoin">{{ __('finance.Bitcoin Deposit') }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- note -->
                            <div class="row mt-2">
                                <label for="client-type" class="col-sm-3 col-form-label">Note
                                    &#40;Optional&#41;</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-floating mb-0">
                                                <textarea data-length="191" name="note" class="form-control char-textarea" id="textarea-counter" rows="3" placeholder="Counter" style="height: 100px"></textarea>
                                                <label for="textarea-counter">{{ __('finance.Write a note') }}</label>
                                            </div>
                                            <small class="textarea-counter-value float-end"><span class="char-count">0</span> / 191 </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- client -->
                            <input class="trader-id" type="hidden" name="trader" id="manage-fund-trader">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @php
                    $disable = '';
                    $has_multi_submit = has_multi_submit('finance-balance', 60);
                    if ($has_multi_submit) {
                    $disable = 'disabled';
                    }
                    @endphp
                    <button class="btn btn-primary float-end" data-submit_wait="{{ submit_wait('fund-management', 60) }}" type="button" id="submit-request" data-label="Submit Request" data-form="fund-add-form" data-el="fg" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="createFundCallBack" data-btnid="submit-request" {{ $disable }} data-i18n="Submit Request" onclick="_run(this)" style="width:200px">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="show_trading_account">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">To See<span class="modelLebel"></span></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="trade_pass_reset_form">
                <input type="hidden" name="account_id" id="account_id_show" value="" class="account_id">
                <input type="hidden" name="pass_type" id="pass_type_show" value="" class="pass_type">
                @csrf
                <div class="modal-body my-3">
                    <div class="form-group details-section-dark dt-details w-100 m-auto" id="show_pass_div">
                        <table class="table table-responsive tbl-balance  w-100 m-auto" id="show_pass_details">
                            <tbody>
                                <tr>
                                    <th>Account</th>
                                    <td id="account_num">xxxxxx</td>
                                </tr>
                                <tr>
                                    <th>Leverage</th>
                                    <td id="leverage_value">000</td>
                                </tr>
                                <tr>
                                    <th>Group Name</th>
                                    <td id="group_name">xxxxxxxxxxx</td>
                                </tr>
                                <tr>
                                    <th>Password</th>
                                    <td>
                                        <div class="input-group">
                                            <span class="form-control password_show" id="master_password"></span>
                                            <span class="input-group-text position-relative bg-gradient-primary cursor-pointer" id="copy_btn_1">
                                                <i class="fas fa-key"></i>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Investor Password</th>
                                    <td>
                                        <div class="input-group">
                                            <span class="form-control al_copy_input2 password_show" id="investor_password"></span>
                                            <span class="input-group-text position-relative bg-gradient-primary cursor-pointer" id="copy_btn_2">
                                                <i class="fas fa-key"></i>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group reset_all_pass" style="display: none" id="show_reset_btn">
                        <h4>We Don't Found Your PasswordðŸ˜”</h4>
                        <label>Please reset your password.</label><br>
                        <button type="button" data-label="Submit Request" id="reset-password" data-btnid="reset-password" data-callback="show_reset_password_call_back" data-loading="<i class='fa-spinner fas fa-circle-notch'></i>" data-form="trade_pass_reset_form" data-el="fg" onclick="_run(this)" class="btn btn-primary ms-auto float-end">RESET PASSWORD</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button" id="">close</button>
                    <button class="btn btn-success" type="button" id="copyButton">copy</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ########## END MODALS ########## -->

@stop
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>

<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
{{-- datatable buttons --}}
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>

<!-- picker js -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>

{{-- auto gen pass and copy on click --}}
<script src="{{ asset('/common-js/copy-js.js') }}"></script>
<script src="{{ asset('/common-js/password-gen.js') }}"></script>

@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    const traders = "{{ url('admin/trading-account-details-live') }}";

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
    $(document).ready(function() {
        const flatpickr_time = $('.flatpickr-human-friendly').flatpickr({
            //static: position the calendar inside the wrapper and next to the input element*.
            static: true
        });
    });

    //show password for modal
    $(document).on("click", ".pass_show", function() {
        $('#al-copy-input').val('');
        $("#account_id_show").val($(this).data('accountid'));
        $("#pass_type_show").val($(this).data('column'));
        $('.modelLebel').html($(this).html());
        $("#show_trading_account_pass").modal('show');
    });

    //show password modal
    $(document).on('click', '.btn-show-password', function() {
        var id = $(this).data('accountid');
        var pass_type = $(this).data('column');
        $.ajax({
            type: "GET",
            url: '/admin/trading-account-details/show-pass',
            data: {
                id: id,
                pass_type: pass_type
            },
            dataType: 'json',
            success: function(data) {

                if (data.status == 0) {
                    $('#show_pass_div').hide();
                    $('#show_reset_btn').show();
                }
                if (data.status == 1) {
                    $('#show_pass_div').show();
                    $('#show_reset_btn').hide();
                }

                $('#pass_type').val(data.password);
                $('.password_show').val(data.password);
            }
        }); //END: get client
    }) //

    function show_reset_password_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Password');
            trading_account_dt.draw();
            $('#show_password').modal('hide');
            $('#show_password').find('#show_reset_btn').hide();
            $('#show_password').find('#show_pass_div').show();
        }
        if (data.status == false) {
            notify('error', data.message, 'Password');
        }
    }


    //Show check password modal information
    $(document).on('click', '.btn-show-password', function() {
        var passId = $(this).data('accountid');

        $.ajax({
            url: "{{route('trading-password.show')}}",
            method: 'get',
            data: {
                passId: passId,

            },
            success: function(data) {
                $('#account_num').text(data.account_number);
                $('#leverage_value').text(data.leverage);
                $('#group_name').text(data.group_name);
                $('#master_password').text(data.master_password);
                $('#investor_password').text(data.investor_password);
                $("#show_trading_account").modal('show');

            }
        })
    });

    // ----------------------------------------------------------------------------------
    //                                     All Information Copied
    // ----------------------------------------------------------------------------------
    $(document).ready(function() {
        $('#copyButton').click(function() {
            // Get the table element by ID
            var table = document.getElementById('show_pass_details');
            var range = document.createRange();
            range.selectNode(table);
            var selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
            document.execCommand('copy');
            selection.removeAllRanges();

            //Toaster
            toastr["success"](
                "All Information Successfully Copied",
                "All Modal Data", {
                    showMethod: "slideDown",
                    hideMethod: "slideUp",
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                }
            );
        });
    });

    // ----------------------------------------------------------------------------------
    //                                     Single Password Copy
    // ----------------------------------------------------------------------------------
    $(document).ready(function() {
        $('#copy_btn_1').click(function() {
            // Get the table element by ID
            var table = document.getElementById('master_password');
            var range = document.createRange();
            range.selectNode(table);
            var selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
            document.execCommand('copy');
            selection.removeAllRanges();

            //Toaster
            toastr["success"](
                "Master Password Copied Successfully",
                "All Modal Data", {
                    showMethod: "slideDown",
                    hideMethod: "slideUp",
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                }
            );
        });
    });

    // --------------------------------------------------Investor Password-----------------------
    $(document).ready(function() {
        $('#copy_btn_2').click(function() {
            // Get the table element by ID
            var table = document.getElementById('investor_password');
            var range = document.createRange();
            range.selectNode(table);
            var selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
            document.execCommand('copy');
            selection.removeAllRanges();

            //Toaster
            toastr["success"](
                "Investor Password Copied Successfully",
                "All Modal Data", {
                    showMethod: "slideDown",
                    hideMethod: "slideUp",
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true,
                    timeOut: 2000,
                }
            );
        });
    });
    // hide show method
    $(document).on('change', '#fund-type', function() {
        if ($(this).val() === 'deposit') {
            $("#method-row").slideDown();
        } else {
            $("#method-row").slideUp();
        }
    });
</script>
<script src="{{ asset('admin-assets/assets/js/manage_accounts/live-trading_account_details.js') }}"></script>
<!-- change master password -->
<script>
    // change master password
    $(document).on("click", ".change-master-password-btn", function() {
        $("#password-change-modal .trader-account-id").val(
            $(this).data("accountid")
        );
        $("#password-change-modal .password-change-op").val("master-password");
    });
    // password change callback
    function change_password_callback(data) {
        if (data.status) {
            notify('success', data.message, 'Password change');
            const op = (data.op == "master-password") ? "change-master-password-mail" : "change-investor-password-mail";
            $(document).sending_mail({
                request_url: '/admin/trading-account-details-live',
                data: {
                    op: op,
                    trader_account_id: data.traderAccountId,
                },
                click: true,
                title: 'Sending mail',
                message: 'Please wait while we sending mail.....',
                button_text: 'Send',
                method: 'POST'
            }, function(data) {
                if (data.status == true) {
                    notify('success', data.message, 'Mail sending');
                } else {
                    notify('error', data.message, 'Mail sending');
                }
                $("#password-change-modal").modal("hide");
            });
        } else {
            notify('error', data.message, 'Password chnage');
        }
        $.validator("change-password-form", data.errors);
    }
    // reset password 
    $(document).on("click", ".reset-password-btn", function() {
        const accountId = $(this).data("accountid");
        const changeType = $(this).data("type");
        $(this).confirm2({
            request_url: '/admin/trading-account-details-live',
            data: {
                op: "reset-password",
                account_id: accountId,
                change_type: changeType,
            },
            click: false,
            title: 'Reset Password',
            message: "Are you confirm to reset password of account <b>#" +
                $(this).data("accountno") +
                "</b>",
            button_text: 'Reset',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'Reset Password');
            } else {
                notify('error', data.message, 'Reset Password');
            }
            // dt.draw();
        });
    })
    // reset investor password
    $(document).on("click", ".reset-investor-password-btn", function() {
        const accountId = $(this).data("accountid");
        const changeType = $(this).data("type");
        $(this).confirm2({
            request_url: '/admin/trading-account-details-live',
            data: {
                op: "reset-password",
                account_id: accountId,
                change_type: changeType,
            },
            click: false,
            title: 'Reset Investor Password',
            message: "Are you confirm to reset investor password of account <b>#" +
                $(this).data("accountno") +
                "</b>",
            button_text: 'Reset',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'Reset Investor Password');
            } else {
                notify('error', data.message, 'Reset Investor Password');
            }
            // dt.draw();
        });
    })

    // change leverage
    function changeLeverageCallback(data) {
        if (data.status) {
            notify('success', data.message, 'Leverage Change');
            $("#change-leverage").modal("hide");
            $("#change-leverage-form").trigger("reset");
        } else {
            notify('error', data.message, 'Leverage Change');
        }
        $.validator("change-leverage-form", data.errors);
    }
    // change group
    function changeGroupCallback(data) {
        if (data.status) {
            notify('success', data.message, 'Group Change');
            $("#change-group").modal("hide");
            $("#change-group-form").trigger("reset");
        } else {
            notify('error', data.message, 'Group Change');
        }
        $.validator("change-group-form", data.errors);
    }
</script>
@stop
<!-- END: page JS -->