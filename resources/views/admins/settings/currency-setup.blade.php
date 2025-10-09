@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Software Setting')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/assets/css/config-form.css')}}">
<style>
    .todo-title {
        margin-top: 3px;
    }

    @media screen and (max-width: 767px) {
        .description-section {
            display: none;
        }
    }
</style>
@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0"> Currency Setup </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{__('category.Settings')}}</a></li>
                                <li class="breadcrumb-item active"> Currency Setup </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                        <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#"><i class="me-1" data-feather="info"></i>
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 description-section">
                    <div class="content-body">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <h4> {{__('ib-management.Note')}}</h4>
                                    <code class="bg">{{__('ib-management.please read carefully')}}</code>
                                </div>
                            </div>
                            <hr>
                            <div class="card-body">
                                <div class="border-start-3 border-start-primary p-1 bg-light-primary mb-1">
                                    This is a one-time setup
                                </div>
                                <div class="border-start-3 border-start-info p-1 bg-light-primary mb-1">
                                    CRM settings, social login, password settings means your admin Level will can set permission.
                                </div>
                                <div class="border-start-3 border-start-danger p-1 bg-light-primary mb-1">
                                    Admin can will change and set permission on crm settings crm type, meta account platform book, social account, account limit and also social login settings like facebook,google,mac then last password settings.
                                </div>
                                <div class="border-start-3 border-start-success p-1 bg-light-primary mb-1">
                                    CRM Settings when an Admin can Change CRM Settings
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-12">
                    <div class="card">
                        <div class="card-header border-bottom mb-0">
                            <div class="card my-0 py-0 w-100">
                                <div class="card-body p-0 py-1">
                                    <div class="title-wrapper d-flex w-50 float-start">
                                        <div class="form-check form-switch form-check-primary m-0 l-0">
                                            <input type="checkbox" class="form-check-input" id="is_multicurrency" value="1" <?= ($software_settings->is_multicurrency == 1) ? 'checked' : '' ?> />
                                            <label class="form-check-label" for="is_multicurrency">
                                                <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                <span class="switch-icon-right"><i data-feather="x"></i></span>
                                            </label>
                                        </div>
                                        <p class="todo-title">Is Multicurrency?</p>
                                    </div>
                                    <div class="title-wrapper d-flex w-50 float-end">
                                        <div class="form-check form-switch form-check-primary m-0 l-0 float-end">
                                            <input type="checkbox" class="form-check-input" id="auto_c_rate" value="1" <?= ($software_settings->auto_c_rate == 1) ? 'checked' : '' ?> />
                                            <label class="form-check-label" for="auto_c_rate">
                                                <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                <span class="switch-icon-right"><i data-feather="x"></i></span>
                                            </label>
                                        </div>
                                        <p class="todo-title text-warning">Menual/Automatic update currency rate?</p>
                                    </div>
                                </div>
                                <form id="currency-setup-form" method="post" action="{{route('admin.settings.currency-setup-store')}}" class="card-body my-0 py-4">
                                    @csrf
                                    <div class="col-md-8 mx-auto">
                                        <div class="form-group fg">
                                            <label for="currency">Currency</label>
                                            <select name="currency" id="currency" class="select2 form-select currency">
                                                <option value="">Choose currency</option>
                                                <option value="AED" title="United Arab Emirates">AED</option>
                                                <option value="CNY" title="China">CNY</option>
                                                <option value="EUR" title="Europe">EUR</option>
                                                <option value="IDR" title="Indonesia">IDR</option>
                                                <option value="INR" title="India">INR</option>
                                                <option value="TRY" title="Turkey">TRY</option>
                                                <option value="MYR" title="Malaysia">MYR</option>
                                                <option value="THB" title="Thailand">THB</option>
                                                <option value="VND" title="Vietnam">VND</option>
                                            </select>
                                        </div>
                                        <div class="form-group fg mt-2">
                                            <label for="transaction_type">Transaction Type</label>
                                            <select name="transaction_type" id="transaction_type" class="select2 form-select">
                                                <option value="all">All</option>
                                                <option value="deposit">Deposit</option>
                                                <option value="withdraw">Withdraw</option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="currency_rate">Currency Rate</label>
                                            <input type="text" name="currency_rate" id="currency_rate" class="form-control">
                                        </div>
                                        <div class="form-group mt-2">
                                            <input type="hidden" id="currency_id" name="currency_id" value="">
                                            <button type="button" id="btn-currency-setup" data-btnid="btn-currency-setup" data-callback="currency_setup_call_back" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-form="currency-setup-form" data-el="fg" onclick="_run(this)" class="btn btn-primary" style="float: right; width:200px">Save</button>
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
</div>
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<!-- datatable -->
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- toaster js -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<!-- <script src="{{asset('admin-assets/app-assets/js/scripts/pages/page-account-settings-account.js')}}"></script> -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-page-configuration.js')}}"></script>
<!-- form hide/show -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-config-form.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-select2.js')}}"></script>
<script>
    $(function() {
        $(".currency").select2({
            templateResult: formatOption,
        });

        function formatOption(option) {
            var $option = $(
                '<div><strong>' + option.text + '</strong></div><div>' + option.title + '</div>'
            );
            return $option;
        };
    });

    // get existing currency setup
    $(document).on('change', '#transaction_type', function() {
        let currency = $('#currency').val();
        let transaction_type = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/settings/currency-setup-get',
            method: 'POST',
            dataType: 'json',
            data: {
                currency: currency,
                transaction_type: transaction_type
            },
            cache: false,
            processData: true,
            success: function(data) {
                if (data.status == true) {
                    $('#currency_rate').val(data.currency_rate);
                    $('#currency_id').val(data.currency_id);
                } else {
                    $('#currency_rate').val("");
                    $('#currency_id').val("");
                }
            }
        })
    });
    // create callback
    function currency_setup_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Currency Setup')
        }
        if (data.status == false) {
            notify('error', data.message, 'Currency Setup')
        }
        $.validator("currency-setup-form", data.errors);
    }

    // multicurrency enable/disable
    $(document).on('change', '#is_multicurrency', function(event) {
        let is_multicurrency = ($(this).prop('checked') == true) ? 1 : 0;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/settings/multi-currency-setup/' + is_multicurrency,
            method: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,

            success: function(data) {
                if (data.success) {
                    notify('success', data.message, 'Multiple Currency');
                    if (data.is_multicurrency == 1) {
                        $('#is_multicurrency').prop('checked', true);
                    } else {
                        $('#is_multicurrency').prop('checked', false);
                    }
                } else {
                    notify('error', data.message, 'Multiple Currency');
                }
            }
        });
    });

    // menual or automatic update currency rate
    $(document).on('change', '#auto_c_rate', function(event) {
        let auto_c_rate = ($(this).prop('checked') == true) ? 1 : 0;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"').attr('content')
            }
        });
        $.ajax({
            url: '/admin/settings/auto-currency-rate/' + auto_c_rate,
            method: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data.success) {
                    notify('success', data.message, 'Automatic Currency Rate');
                } else {
                    notify('false', data.message, 'Automatic Currency Rate');
                }
            }
        })
    });
</script>
@stop
<!-- BEGIN: page JS -->