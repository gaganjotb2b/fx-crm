@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'List Of Demo Accounts')
@section('vendor-css')

    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">

    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <!-- datatable -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
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
                            <h2 class="content-header-title float-start mb-0">{{__('page.Demo_Trading_Account_List')}}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('finance.home')}}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{__('admin-menue-left.Manage_Accounts')}}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1 breadcrumb-right">
                        <div class="dropdown">
                            <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    data-feather="grid"></i></button>
                            <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="app-todo.html"><i
                                        class="me-1" data-feather="check-square"></i><span
                                        class="align-middle">Todo</span></a><a class="dropdown-item" href="app-chat.html"><i
                                        class="me-1" data-feather="message-square"></i><span
                                        class="align-middle">Chat</span></a><a class="dropdown-item"
                                    href="app-email.html"><i class="me-1" data-feather="mail"></i><span
                                        class="align-middle">Email</span></a><a class="dropdown-item"
                                    href="app-calendar.html"><i class="me-1" data-feather="calendar"></i><span
                                        class="align-middle">Calendar</span></a></div>
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
                                        {{-- <select data-placeholder="Select a state..." class="select2-icons form-select"
                                            id="fx-export">
                                            <option value="download" data-icon="download" selected>
                                                {{ __('client-management.Export') }}</option>
                                            <option value="csv" data-icon="file">CSV</option>
                                            <option value="excel" data-icon="file">Excel</option>
                                        </select> --}}
                                    </div>
                                </div>
                                <!--Search Form -->
                                <div class="card-body mt-2">
                                    <form class="dt_adv_search" method="POST" id="filter-form">
                                        <div class="row g-1 mb-md-1">
                                            <div class="col-md-4">
                                                <label class="form-label">{{__('finance.Trader')}} {{__('page.account_info')}}</label>
                                                <div class="mb-0">
                                                    <input id="info" type="text" name="info"
                                                        class="form-control dt-input" data-column="4"
                                                        placeholder="Email / Name / Phone" data-column-index="3" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">{{__('page.trading-accounts')}}</label>
                                                <input id="trading_acc" type="text" name="trading_acc"
                                                    class="form-control dt-input" data-column="4"
                                                    placeholder="Trading Account" data-column-index="3" />
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <button id="btn-reset" type="button" class="btn btn-secondary form-control"
                                                    data-column="4"
                                                    data-column-index="3">{{ __('client-management.Reset') }}</button>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <button id="btn-filter" type="button" class="btn btn-primary form-control"
                                                    data-column="4"
                                                    data-column-index="3">{{ __('client-management.Filter') }}</button>
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
                                            <th>{{__('page.account-number')}}</th>
                                            <th>{{__('page.account-type')}}</th>
                                            <th>{{__('page.group')}}</th>
                                            <th>{{__('page.email')}}</th>
                                            <th>{{__('page.platform')}}</th>

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

    <!-- Modal sending mail-->
    <div class="modal fade text-start modal-success" id="send-mail-pass" tabindex="-1"
        aria-labelledby="mail-sending-modal" aria-hidden="true">
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
                <form action="" method="post" id="change-group-form">
                    @csrf
                    <div class="modal-body my-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-element">
                                    <label class="form-label">Select Group</label>
                                    <select name="group_id" class="form-select form-select-lg">
                                        @foreach ($demoGroups as $demoGroup)
                                            <option value="{{ $demoGroup->id }}">{{ $demoGroup->group_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="trader_account_id" class="trader-account-id">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="save-change-group-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for change leverage -->
    <div class="modal fade" id="change-leverage">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Change Leverage</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" id="change-leverage-form">
                    @csrf
                    <div class="modal-body my-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-element">
                                    <label class="form-label">Select Leverage</label>
                                    <select name="leverage" class="form-select form-select-lg leverage">
                                        {{-- options goes here through ajax --}}
                                    </select>
                                </div>
                                <input type="hidden" name="trader_account_id" class="trader-account-id">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="save-change-leverage-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Create Live Account -->
    <div class="modal fade" id="create-live-account">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Create Live Account</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" id="create-live-account-form">
                    @csrf
                    <div class="modal-body my-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-element">
                                    <label class="form-label">Platform</label>
                                    <select name="platform" class="form-select select2">
                                        <option value="" disabled selected>Choose Platform</option>
                                        <option value="mt4">MT4</option>
                                        <option value="mt5">MT5</option>

                                    </select>
                                </div>
                                <div class="form-element mt-1">
                                    <label class="form-label">Select Group</label>
                                    <select name="group_id" class="form-select select2 group">
                                        <option value="" disabled selected>Choose Group</option>
                                    </select>
                                </div>
                                <div class="form-element mt-1">
                                    <label class="form-label">Select Leverage</label>
                                    <select name="leverage" class="form-select select2 leverage">
                                        <option value="" disabled selected>Choose Leverage</option>
                                    </select>
                                </div>
                                <input type="hidden" name="trader_account_id" class="trader-account-id">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="create-live-account-btn">Save Changes</button>
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
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        const traders = "{{ url('admin/trading-account-details-demo') }}";
    </script>
    <script src="{{ asset('admin-assets/assets/js/manage_accounts/demo-trading_account_details.js') }}"></script>
@stop
<!-- END: page JS -->
